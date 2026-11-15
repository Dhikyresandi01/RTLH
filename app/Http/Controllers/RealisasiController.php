<?php

namespace App\Http\Controllers;

use App\Models\Bantuan;
use App\Models\ProgramBantuan;
use App\Models\Realisasi;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RealisasiController extends Controller
{
    /**
     * Pastikan hanya Admin Dinas dan Superadmin yang dapat mengakses fitur Realisasi.
     */
    protected function authorizeAccess(): void
    {
        $user = Auth::user();
        if (!$user || (!$user->isSuperadmin() && !$user->isAdminDinas())) {
            abort(403, 'Akses Ditolak: Fitur Realisasi Bantuan hanya dapat diakses oleh Admin Dinas dan Super Admin.');
        }
    }

    /**
     * Menampilkan daftar bantuan yang telah terverifikasi/disetujui beserta progres realisasinya.
     */
    public function index(Request $request)
    {
        $this->authorizeAccess();

        // Query data bantuan yang sudah terverifikasi (Disetujui / Diverifikasi / Direalisasikan)
        $query = Bantuan::with([
            'warga.wilayah',
            'program',
            'realisasiTerbaru',
            'verifikator',
        ])
        ->where('is_aktif', true);

        // Filter status verifikasi dinas (default: hanya data yang sudah terverifikasi / disetujui / direalisasikan)
        if ($request->filled('status_bantuan')) {
            $query->where('status_bantuan', $request->input('status_bantuan'));
        } else {
            $query->whereIn('status_bantuan', [
                Bantuan::STATUS_DISETUJUI,
                Bantuan::STATUS_DIREALISASIKAN,
                Bantuan::STATUS_DIVERIFIKASI,
            ]);
        }

        // Filter Pencarian (Nama Warga, NIK, atau No. KK)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('warga', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_kk', 'like', "%{$search}%");
            });
        }

        // Filter Kelurahan / Wilayah
        if ($request->filled('id_wilayah')) {
            $idWilayah = $request->input('id_wilayah');
            $query->whereHas('warga', function ($q) use ($idWilayah) {
                $q->where('id_wilayah', $idWilayah);
            });
        }

        // Filter Program / Jenis Bantuan
        if ($request->filled('id_program')) {
            $query->where('id_program', $request->input('id_program'));
        }

        // Filter Tahun Anggaran
        if ($request->filled('tahun')) {
            $query->where('tahun_bantuan', $request->input('tahun'));
        }

        // Filter Progres Realisasi Fisik
        if ($request->filled('progres')) {
            $progres = $request->input('progres');
            if ($progres === 'belum') {
                $query->where(function ($q) {
                    $q->whereDoesntHave('realisasiTerbaru')
                      ->orWhereHas('realisasiTerbaru', fn($r) => $r->where('status', 0));
                });
            } elseif (in_array((int) $progres, [25, 50, 100], true)) {
                $query->whereHas('realisasiTerbaru', fn($r) => $r->where('status', (int) $progres));
            }
        }

        // Statistik KPI Card
        $statsQuery = clone $query;
        $totalPenerima = (clone $statsQuery)->count();

        $totalBelumMulai = (clone $statsQuery)->where(function ($q) {
            $q->whereDoesntHave('realisasiTerbaru')
              ->orWhereHas('realisasiTerbaru', fn($r) => $r->where('status', 0));
        })->count();

        $totalProgres25 = (clone $statsQuery)->whereHas('realisasiTerbaru', fn($r) => $r->where('status', 25))->count();
        $totalProgres50 = (clone $statsQuery)->whereHas('realisasiTerbaru', fn($r) => $r->where('status', 50))->count();
        $totalSelesai   = (clone $statsQuery)->whereHas('realisasiTerbaru', fn($r) => $r->where('status', 100))->count();

        // Paginasi
        $bantuans = $query->latest('id_bantuan')->paginate(10)->withQueryString();

        // Master Data untuk Filter
        $wilayahs = Wilayah::orderBy('desa')->get();
        $programs = ProgramBantuan::orderBy('nama_program')->get();
        $availableYears = Bantuan::distinct()->orderByDesc('tahun_bantuan')->pluck('tahun_bantuan')->toArray();

        return view('pages.realisasi.index', compact(
            'bantuans',
            'totalPenerima',
            'totalBelumMulai',
            'totalProgres25',
            'totalProgres50',
            'totalSelesai',
            'wilayahs',
            'programs',
            'availableYears'
        ));
    }

    /**
     * Menyimpan atau memperbarui data progres realisasi fisik & dokumen pendukung.
     */
    public function storeOrUpdate(Request $request, $id_bantuan)
    {
        $this->authorizeAccess();

        $request->validate([
            'status'        => ['required', 'in:0,25,50,100'],
            'tgl_realisasi' => ['nullable', 'date'],
            'deskripsi'     => ['nullable', 'string', 'max:1000'],
            'foto_0'        => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:3072'],
            'foto_25'       => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:3072'],
            'foto_50'       => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:3072'],
            'foto_100'      => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:3072'],
            'kwitansi'      => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:4096'],
        ], [
            'status.required'   => 'Progres fisik pelaksanaan wajib dipilih.',
            'status.in'         => 'Pilihan progres fisik tidak sah (pilih 0%, 25%, 50%, atau 100%).',
            'foto_0.max'        => 'Ukuran foto titik 0% maksimal 3MB.',
            'foto_25.max'       => 'Ukuran foto progres 25% maksimal 3MB.',
            'foto_50.max'       => 'Ukuran foto progres 50% maksimal 3MB.',
            'foto_100.max'      => 'Ukuran foto selesai 100% maksimal 3MB.',
            'kwitansi.max'      => 'Ukuran berkas kuitansi/SPJ maksimal 4MB.',
        ]);

        $bantuan = Bantuan::with('warga')->findOrFail($id_bantuan);

        DB::beginTransaction();
        try {
            $realisasi = Realisasi::firstOrNew(['id_bantuan' => $bantuan->id_bantuan]);

            // Unggah foto progres jika ada
            foreach (['foto_0', 'foto_25', 'foto_50', 'foto_100'] as $field) {
                if ($request->hasFile($field)) {
                    if ($realisasi->$field && Storage::disk('public')->exists($realisasi->$field)) {
                        Storage::disk('public')->delete($realisasi->$field);
                    }
                    $realisasi->$field = $request->file($field)->store('dokumen_realisasi/' . $field, 'public');
                }
            }

            // Unggah kuitansi/berkas SPJ jika ada
            if ($request->hasFile('kwitansi')) {
                if ($realisasi->kwitansi && Storage::disk('public')->exists($realisasi->kwitansi)) {
                    Storage::disk('public')->delete($realisasi->kwitansi);
                }
                $realisasi->kwitansi = $request->file('kwitansi')->store('dokumen_realisasi/kwitansi', 'public');
            }

            $realisasi->status = (int) $request->input('status');
            $realisasi->tgl_realisasi = $request->filled('tgl_realisasi') ? $request->input('tgl_realisasi') : now();
            $realisasi->deskripsi = $request->input('deskripsi');
            $realisasi->save();

            // Jika progres sudah mencapai 100%, tandai status bantuan sebagai DIREALLISASIKAN
            if ($realisasi->status === 100) {
                $bantuan->update(['status_bantuan' => Bantuan::STATUS_DIREALISASIKAN]);
            } elseif ($bantuan->status_bantuan === Bantuan::STATUS_DIREALISASIKAN) {
                // Jika diturunkan dari 100%, kembalikan ke status DISETUJUI
                $bantuan->update(['status_bantuan' => Bantuan::STATUS_DISETUJUI]);
            }

            DB::commit();

            return back()->with('success', "Progres realisasi untuk warga {$bantuan->warga->nama} berhasil disimpan ({$realisasi->status}%).");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal menyimpan progres realisasi: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus record realisasi jika diperlukan.
     */
    public function destroy($id_realisasi)
    {
        $this->authorizeAccess();

        $realisasi = Realisasi::with('bantuan.warga')->findOrFail($id_bantuan ?? $id_realisasi);

        DB::beginTransaction();
        try {
            // Hapus file fisik jika tersimpan
            foreach (['foto_0', 'foto_25', 'foto_50', 'foto_100', 'kwitansi'] as $field) {
                if ($realisasi->$field && Storage::disk('public')->exists($realisasi->$field)) {
                    Storage::disk('public')->delete($realisasi->$field);
                }
            }

            // Reset status bantuan ke disetujui jika sebelumnya direalisasikan
            if ($realisasi->bantuan && $realisasi->bantuan->status_bantuan === Bantuan::STATUS_DIREALISASIKAN) {
                $realisasi->bantuan->update(['status_bantuan' => Bantuan::STATUS_DISETUJUI]);
            }

            $nama = $realisasi->bantuan?->warga?->nama ?? 'Calon Penerima';
            $realisasi->delete();

            DB::commit();

            return back()->with('success', "Data realisasi untuk warga {$nama} berhasil dihapus/direset.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data realisasi: ' . $e->getMessage());
        }
    }
}
