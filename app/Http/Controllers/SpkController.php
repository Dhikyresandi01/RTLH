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
     * Mengarahkan langsung ke data warga yang sudah diverifikasi (View 2 / Tab SPK).
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Batasi akses hanya untuk Admin Dinas dan Super Admin
        if (!$user || (!$user->isSuperadmin() && !$user->isAdminDinas())) {
            abort(403, 'Akses Ditolak: Menu SPK dan Penetapan Bantuan hanya dapat diakses oleh pihak Dinas Perumahan dan Kawasan Permukiman.');
        }

        // T2.5: Mengarah langsung ke data warga yang sudah diverifikasi (status_bantuan = 1)
        $params = array_merge([
            'status_bantuan' => Bantuan::STATUS_DIVERIFIKASI,
            'tab'            => 'spk',
            'sort'           => 'rank_asc',
        ], $request->query());

        return redirect()->route('warga.index', $params);
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
                return redirect()->route('warga.index', [
                    'status_bantuan' => Bantuan::STATUS_DIVERIFIKASI,
                    'tab'            => 'spk',
                    'sort'           => 'rank_asc',
                ])->with('error', $result['message']);
            }

            return redirect()->route('warga.index', [
                'status_bantuan' => Bantuan::STATUS_DIVERIFIKASI,
                'tab'            => 'spk',
                'sort'           => 'rank_asc',
            ])->with('success', "Proses perhitungan SAW selesai. Berhasil memperbarui perankingan untuk {$result['total']} kandidat yang telah diverifikasi.");
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
