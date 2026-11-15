<?php

namespace App\Http\Controllers;

use App\Models\Bantuan;
use App\Models\ProgramBantuan;
use App\Models\SpkKriteria;
use App\Models\SpkRanking;
use App\Models\Wilayah;
use App\Services\SawRankingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SpkController extends Controller
{
    /**
     * Menampilkan daftar perankingan SPK SAW dan status verifikasi dinas.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Batasi akses hanya untuk Admin Dinas dan Super Admin
        if (!$user || (!$user->isSuperadmin() && !$user->isAdminDinas())) {
            abort(403, 'Akses Ditolak: Menu SPK dan Penetapan Bantuan hanya dapat diakses oleh pihak Dinas Perumahan dan Kawasan Permukiman.');
        }

        // 1. Tentukan tahun anggaran aktif
        $availableYears = Bantuan::distinct()->orderByDesc('tahun_bantuan')->pluck('tahun_bantuan')->toArray();
        $tahun = $request->input('tahun', !empty($availableYears) ? $availableYears[0] : (int) date('Y'));

        // 2. Query data perankingan
        $query = SpkRanking::with([
            'bantuan.warga.wilayah',
            'bantuan.program',
            'bantuan.verifikator',
        ])
        ->where('tahun_anggaran', $tahun);

        // Filter Pencarian (Nama Warga, NIK, atau No KK)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('bantuan.warga', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('no_kk', 'like', "%{$search}%");
            });
        }

        // Filter Program Bantuan
        if ($request->filled('id_program')) {
            $idProgram = $request->input('id_program');
            $query->whereHas('bantuan', function ($q) use ($idProgram) {
                $q->where('id_program', $idProgram);
            });
        }

        // Filter Kelurahan / Wilayah
        if ($request->filled('id_wilayah')) {
            $idWilayah = $request->input('id_wilayah');
            $query->whereHas('bantuan.warga', function ($q) use ($idWilayah) {
                $q->where('id_wilayah', $idWilayah);
            });
        }

        // Filter Status Bantuan / Verifikasi Dinas
        if ($request->filled('status_bantuan')) {
            $status = $request->input('status_bantuan');
            $query->whereHas('bantuan', function ($q) use ($status) {
                $q->where('status_bantuan', $status);
            });
        }

        // Ambil data statistik untuk card ringkasan
        $statsQuery = clone $query;
        $totalKandidat = (clone $statsQuery)->count();
        $avgSkor = $totalKandidat > 0 ? round((clone $statsQuery)->avg('skor_akhir'), 4) : 0;
        
        $totalDisetujui = (clone $statsQuery)->whereHas('bantuan', function ($q) {
            $q->where('status_bantuan', Bantuan::STATUS_DISETUJUI);
        })->count();

        $totalMenunggu = (clone $statsQuery)->whereHas('bantuan', function ($q) {
            $q->whereIn('status_bantuan', [Bantuan::STATUS_DIAJUKAN, Bantuan::STATUS_DIVERIFIKASI]);
        })->count();

        $totalRevisi = (clone $statsQuery)->whereHas('bantuan', function ($q) {
            $q->where('status_bantuan', Bantuan::STATUS_REVISI);
        })->count();

        $totalDitolak = (clone $statsQuery)->whereHas('bantuan', function ($q) {
            $q->where('status_bantuan', Bantuan::STATUS_DITOLAK);
        })->count();

        // Paginasi hasil
        $rankings = $query->orderBy('ranking', 'asc')->paginate(15)->withQueryString();

        // Master data untuk filter & modal kriteria
        $kriterias = SpkKriteria::orderBy('kode')->get();
        $programs = ProgramBantuan::all();
        $wilayahs = Wilayah::orderBy('desa')->get();

        return view('pages.spk.index', compact(
            'rankings',
            'tahun',
            'availableYears',
            'kriterias',
            'programs',
            'wilayahs',
            'totalKandidat',
            'avgSkor',
            'totalDisetujui',
            'totalMenunggu',
            'totalRevisi',
            'totalDitolak'
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
                ->with('success', "Proses perhitungan SAW selesai. Berhasil memperbarui perankingan untuk {$result['total']} kandidat.");
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
            $bantuan->update([
                'status_bantuan' => $validated['status_bantuan'],
                'catatan_verifikasi' => $validated['catatan_verifikasi'],
                'id_verifikator_bantuan' => $user->id,
            ]);

            DB::commit();

            $statusText = match ((int) $validated['status_bantuan']) {
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
