<?php

namespace App\Http\Controllers;

use App\Models\Bantuan;
use App\Models\ProgramBantuan;
use App\Models\SpkKriteria;
use App\Models\SpkRanking;
use App\Models\Warga;
use App\Models\Wilayah;
use App\Services\SawRankingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpkController extends Controller
{
    /**
     * Menampilkan daftar perankingan SPK SAW & penetapan keputusan Dinas (View 2).
     * Hanya menampilkan antrean berstatus Diverifikasi Lapangan (1).
     * Admin kelurahan dapat memantau peringkat & catatan wilayahnya secara view-only.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Akses Ditolak: Silakan login terlebih dahulu.');
        }

        $isDinas = $user->isSuperadmin() || $user->isAdminDinas();

        $availableYears = Bantuan::distinct()->orderByDesc('tahun_bantuan')->pluck('tahun_bantuan')->toArray();
        $tahun = (int) $request->input('tahun', !empty($availableYears) ? $availableYears[0] : date('Y'));

        $query = Warga::with([
            'wilayah',
            'bantuanAktif.program',
            'bantuanAktif.verifikator',
            'bantuanAktif.spkRanking',
        ])
        ->visibleTo($user)
        ->whereHas('bantuanAktif', function ($q) use ($tahun, $request) {
            $q->where('tahun_bantuan', $tahun)
              ->where('status_bantuan', Bantuan::STATUS_DIVERIFIKASI);

            if ($request->filled('id_program')) {
                $q->where('id_program', $request->input('id_program'));
            }
        });

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('warga.nama', 'like', "%{$search}%")
                  ->orWhere('warga.nik', 'like', "%{$search}%")
                  ->orWhere('warga.no_kk', 'like', "%{$search}%");
            });
        }

        if ($isDinas && $request->filled('id_wilayah')) {
            $query->where('warga.id_wilayah', $request->input('id_wilayah'));
        }

        $statsQuery = clone $query;
        $totalKandidat = (clone $statsQuery)->count();
        $totalSudahRanking = (clone $statsQuery)->whereHas('bantuanAktif.spkRanking')->count();
        $totalBelumRanking = max(0, $totalKandidat - $totalSudahRanking);

        $totalDisetujui = Bantuan::aktif()
            ->visibleTo($user)
            ->where('tahun_bantuan', $tahun)
            ->where('status_bantuan', Bantuan::STATUS_DISETUJUI)
            ->count();

        $avgSkor = (float) (SpkRanking::where('tahun_anggaran', $tahun)
            ->whereHas('bantuan', function ($q) use ($user) {
                $q->aktif()
                  ->visibleTo($user)
                  ->where('status_bantuan', Bantuan::STATUS_DIVERIFIKASI);
            })
            ->avg('skor_akhir') ?? 0);

        $query->leftJoin('bantuan', function ($join) {
            $join->on('warga.id_warga', '=', 'bantuan.id_warga')
                 ->where('bantuan.is_aktif', true);
        })
        ->leftJoin('spk_ranking', 'bantuan.id_bantuan', '=', 'spk_ranking.id_bantuan')
        ->select('warga.*')
        ->orderByRaw('spk_ranking.ranking IS NULL, spk_ranking.ranking ASC')
        ->latest('warga.id_warga');

        $wargas = $query->paginate(15)->withQueryString();
        $kriterias = SpkKriteria::orderBy('kode')->get();
        $programs = ProgramBantuan::orderBy('nama_program')->get();
        $wilayahs = Wilayah::orderBy('desa')->get();

        return view('pages.spk.index', compact(
            'wargas',
            'tahun',
            'availableYears',
            'kriterias',
            'programs',
            'wilayahs',
            'totalKandidat',
            'totalSudahRanking',
            'totalBelumRanking',
            'totalDisetujui',
            'avgSkor',
            'isDinas'
        ));
    }

    /**
     * Jalankan kalkulasi perankingan Simple Additive Weighting (SAW).
     */
    public function hitung(Request $request, SawRankingService $sawService)
    {
        $user = Auth::user();
        if (!$user || (!$user->isSuperadmin() && !$user->isAdminDinas())) {
            abort(403, 'Akses Ditolak: Hanya pihak Dinas yang dapat menjalankan perhitungan SPK.');
        }

        $request->validate([
            'tahun' => ['required', 'integer', 'digits:4'],
            'id_program' => ['nullable', 'exists:program_bantuan,id_program'],
        ]);

        $tahun = (int) $request->input('tahun');
        $idProgram = $request->filled('id_program') ? (int) $request->input('id_program') : null;

        try {
            $result = $sawService->hitungDanSimpan($tahun, $idProgram);

            if (!$result['success']) {
                return redirect()->route('spk.index', ['tahun' => $tahun])
                    ->with('error', $result['message']);
            }

            return redirect()->route('spk.index', ['tahun' => $tahun])
                ->with('success', "Proses perhitungan SAW selesai. Berhasil memperbarui perankingan untuk {$result['total']} kandidat berstatus Diverifikasi/Disetujui.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses perankingan SAW: ' . $e->getMessage());
        }
    }

    /**
     * Verifikasi & Validasi Hasil Rekomendasi SPK oleh Tim Dinas.
     * Hanya boleh dieksekusi oleh Superadmin atau Admin Dinas.
     */
    public function verifikasiDinas(Request $request, $id_bantuan)
    {
        $user = Auth::user();

        if (!$user->isSuperadmin() && !$user->isAdminDinas()) {
            abort(403, 'Akses Ditolak: Hanya pihak Dinas Perumahan dan Kawasan Permukiman yang berwenang melakukan verifikasi dan penetapan bantuan.');
        }

        $validated = $request->validate([
            'status_bantuan' => ['required', 'in:1,2,3,4'], // 1=Diverifikasi, 2=Disetujui, 3=Revisi, 4=Ditolak
            'catatan_verifikasi' => ['required', 'string', 'max:1000'],
        ], [
            'status_bantuan.required' => 'Keputusan verifikasi wajib dipilih.',
            'status_bantuan.in' => 'Pilihan keputusan verifikasi tidak sah.',
            'catatan_verifikasi.required' => 'Catatan / alasan verifikasi dinas wajib diisi.',
            'catatan_verifikasi.max' => 'Catatan verifikasi maksimal 1000 karakter.',
        ]);

        $bantuan = Bantuan::with('warga')->findOrFail($id_bantuan);

        DB::beginTransaction();
        try {
            $newStatus = (int) $validated['status_bantuan'];

            $bantuan->update([
                'status_bantuan' => $newStatus,
                'catatan_verifikasi' => $validated['catatan_verifikasi'],
                'id_verifikator_bantuan' => $user->id,
            ]);

            if (!in_array($newStatus, [Bantuan::STATUS_DIVERIFIKASI, Bantuan::STATUS_DISETUJUI], true)) {
                SpkRanking::where('id_bantuan', $bantuan->id_bantuan)->delete();
            }

            DB::commit();

            if (in_array($newStatus, [Bantuan::STATUS_DIVERIFIKASI, Bantuan::STATUS_DISETUJUI], true)) {
                try {
                    app(SawRankingService::class)->hitungDanSimpan((int) ($bantuan->tahun_bantuan ?? date('Y')));
                } catch (\Throwable $e) {
                    Log::warning('Otomatisasi SAW pada verifikasiDinas: ' . $e->getMessage());
                }
            }

            $statusText = match ($newStatus) {
                Bantuan::STATUS_DISETUJUI => 'DISETUJUI',
                Bantuan::STATUS_REVISI => 'DIKEMBALIKAN UNTUK REVISI',
                Bantuan::STATUS_DITOLAK => 'DITOLAK',
                default => 'DIVERIFIKASI',
            };

            return back()->with('success', "Usulan bantuan untuk warga {$bantuan->warga->nama} berhasil diperbarui menjadi status {$statusText}.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan verifikasi dinas: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui bobot kriteria SPK (Fitur Fleksibilitas Konfigurasi).
     */
    public function updateKriteria(Request $request)
    {
        $user = Auth::user();

        if (!$user->isSuperadmin() && !$user->isAdminDinas()) {
            abort(403, 'Akses Ditolak: Hanya pihak berwenang yang dapat mengubah bobot kriteria SPK.');
        }

        $validated = $request->validate([
            'bobot' => ['required', 'array'],
            'bobot.*' => ['required', 'numeric', 'min:0', 'max:1'],
        ]);

        $totalBobot = array_sum($validated['bobot']);
        if (abs($totalBobot - 1.0) > 0.001) {
            return back()->with('error', "Total akumulasi seluruh bobot harus bernilai tepat 1.00 (100%). Total saat ini: " . number_format($totalBobot, 2));
        }

        DB::beginTransaction();
        try {
            foreach ($validated['bobot'] as $kode => $nilai) {
                SpkKriteria::where('kode', $kode)->update(['bobot' => $nilai]);
            }
            DB::commit();

            return back()->with('success', 'Konfigurasi bobot kriteria SPK SAW berhasil diperbarui. Silakan hitung ulang perankingan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui kriteria: ' . $e->getMessage());
        }
    }
}
