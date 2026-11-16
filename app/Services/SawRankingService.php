<?php

namespace App\Services;

use App\Models\Bantuan;
use App\Models\SpkKriteria;
use App\Models\SpkRanking;
use Illuminate\Support\Facades\DB;

class SawRankingService
{
    /**
     * Hitung perankingan SAW untuk usulan bantuan pada tahun anggaran tertentu.
     * 
     * @param int $tahun
     * @param int|null $idProgram
     * @param array $statuses
     * @return array
     */
    public function hitungDanSimpan(int $tahun, ?int $idProgram = null, array $statuses = [Bantuan::STATUS_DIVERIFIKASI, Bantuan::STATUS_DISETUJUI]): array
    {
        // 1. Ambil data usulan bantuan aktif yang SUDAH DIVERIFIKASI untuk tahun bersangkutan
        // Memuat relasi terpadu: warga, hunian, rumah, dan utilitas
        $query = Bantuan::with([
            'warga.wilayah',
            'warga.Hunian.Rumah.Utilitas',
            'program',
        ])
        ->where('tahun_bantuan', $tahun)
        ->where('is_aktif', true)
        ->whereIn('status_bantuan', $statuses);

        if ($idProgram) {
            $query->where('id_program', $idProgram);
        }

        $bantuans = $query->get();

        if ($bantuans->isEmpty()) {
            return [
                'success' => false,
                'message' => "Tidak ada data usulan bantuan yang sudah diverifikasi pada tahun anggaran {$tahun}.",
                'total' => 0,
            ];
        }

        // 2. Ambil kriteria aktif dari database
        $kriterias = SpkKriteria::all()->keyBy('kode');
        if ($kriterias->isEmpty()) {
            throw new \Exception('Kriteria SPK belum dikonfigurasi. Jalankan seeder SpkKriteriaSeeder terlebih dahulu.');
        }

        // 3. Bentuk Matriks Keputusan X (Kandidat Alternatif x Kriteria)
        $matrix = [];
        foreach ($bantuans as $bantuan) {
            $warga = $bantuan->warga;
            $hunian = $warga?->Hunian?->first();
            $rumah = $hunian?->Rumah?->first();
            $utilitas = $rumah?->Utilitas?->first();

            // C1: Tingkat Kerusakan Fisik Bangunan (Benefit)
            // Akumulasi skor: atap, dinding, lantai, pondasi, kerangka atap (skala 1=Baik, 2=Sedang, 3=Rusak)
            $c1 = ($rumah?->kondisi_atap ?? 1)
                + ($rumah?->kondisi_dinding ?? 1)
                + ($rumah?->kondisi_lantai ?? 1)
                + ($rumah?->pondasi ?? 1)
                + ($rumah?->kerangka_atap ?? 1);

            // C2: Kepadatan / Luas Hunian per Jiwa dalam m² (Cost)
            // Semakin kecil luas per orang, semakin mendesak
            $jmlPenghuni = max(1, (int) ($hunian?->jml_penghuni ?? 1));
            $luasRumah = max(6.0, (float) ($hunian?->luas_rumah ?? 36));
            $c2 = round($luasRumah / $jmlPenghuni, 2);

            // C3: Kondisi Sanitasi & Air Minum (Benefit)
            // Semakin tinggi skor, semakin tidak layak utilitasnya
            $c3 = ($utilitas?->sumber_air ?? 1)
                + ($utilitas?->mck ?? 1)
                + ($utilitas?->jenis_jamban ?? 1);

            // C4: Tingkat Kesejahteraan / Desil DTSEN (Benefit)
            // Desil 1 (Sangat Miskin) = urgensi 4, Desil 2 = 3, Desil 3 = 2, Desil 4/lain = 1
            $desil = $warga?->dtsen ?? 4;
            $c4 = match ((int) $desil) {
                1 => 4,
                2 => 3,
                3 => 2,
                default => 1,
            };

            // C5: Tingkat Kemampuan Finansial (Cost)
            // Semakin rendah pendapatan (1: terendah), semakin prioritas
            $c5 = max(1, (int) ($warga?->penghasilan ?? 1));

            $matrix[$bantuan->id_bantuan] = [
                'bantuan' => $bantuan,
                'C1' => (float) $c1,
                'C2' => (float) $c2,
                'C3' => (float) $c3,
                'C4' => (float) $c4,
                'C5' => (float) $c5,
            ];
        }

        // 4. Cari nilai Ekstrem (Max & Min) untuk setiap kriteria
        $extrema = [];
        $kodeList = ['C1', 'C2', 'C3', 'C4', 'C5'];
        foreach ($kodeList as $kode) {
            $colValues = array_column($matrix, $kode);
            $extrema[$kode] = [
                'max' => !empty($colValues) ? max($colValues) : 1.0,
                'min' => !empty($colValues) ? min($colValues) : 1.0,
            ];
        }

        // 5. Normalisasi Matriks (r_ij) dan Hitung Nilai Preferensi SAW (V_i)
        $hasil = [];
        foreach ($matrix as $idBantuan => $row) {
            $vi = 0.0;
            $rincian = [];

            foreach ($kodeList as $kode) {
                $kriteria = $kriterias->get($kode);
                $bobot = $kriteria ? (float) $kriteria->bobot : 0.20;
                $jenis = $kriteria ? $kriteria->jenis : 'benefit';

                $x = $row[$kode];
                if ($jenis === 'benefit') {
                    $max = $extrema[$kode]['max'];
                    $r = $max > 0 ? ($x / $max) : 0.0;
                } else {
                    $min = $extrema[$kode]['min'];
                    $r = $x > 0 ? ($min / $x) : 0.0;
                }

                $skorTerbobot = $r * $bobot;
                $vi += $skorTerbobot;

                $rincian[$kode] = [
                    'nama' => $kriteria?->nama_kriteria ?? $kode,
                    'jenis' => $jenis,
                    'bobot' => $bobot,
                    'mentah' => $x,
                    'normalisasi' => round($r, 4),
                    'terbobot' => round($skorTerbobot, 4),
                ];
            }

            $hasil[] = [
                'id_bantuan' => $idBantuan,
                'bantuan' => $row['bantuan'],
                'skor_akhir' => round($vi, 4),
                'rincian' => $rincian,
                'c1_raw' => $row['C1'],
                'c4_raw' => $row['C4'],
                'tgl_pengajuan' => $row['bantuan']->tgl_pengajuan,
            ];
        }

        // 6. Urutkan berdasarkan Skor Akhir SAW Tertinggi (Descending) dengan tie-breakers
        usort($hasil, function ($a, $b) {
            if ($b['skor_akhir'] != $a['skor_akhir']) {
                return $b['skor_akhir'] <=> $a['skor_akhir'];
            }
            // Tie breaker 1: Kerusakan fisik C1
            if ($b['c1_raw'] != $a['c1_raw']) {
                return $b['c1_raw'] <=> $a['c1_raw'];
            }
            // Tie breaker 2: Kemiskinan C4
            if ($b['c4_raw'] != $a['c4_raw']) {
                return $b['c4_raw'] <=> $a['c4_raw'];
            }
            // Tie breaker 3: Waktu pengajuan lebih awal
            return $a['tgl_pengajuan'] <=> $b['tgl_pengajuan'];
        });

        // 7. Simpan Hasil Perankingan ke Tabel `spk_ranking`
        DB::beginTransaction();
        try {
            // Hapus riwayat ranking lama untuk tahun ini
            $bantuanIds = array_column($hasil, 'id_bantuan');
            SpkRanking::whereIn('id_bantuan', $bantuanIds)->delete();

            foreach ($hasil as $index => $item) {
                SpkRanking::create([
                    'id_bantuan' => $item['id_bantuan'],
                    'tahun_anggaran' => $tahun,
                    'skor_akhir' => $item['skor_akhir'],
                    'ranking' => $index + 1,
                    'rincian_kriteria' => $item['rincian'],
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return [
            'success' => true,
            'message' => 'Perhitungan Simple Additive Weighting berhasil diperbarui.',
            'total' => count($hasil),
        ];
    }
}
