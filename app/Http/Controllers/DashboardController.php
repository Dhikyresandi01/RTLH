<?php

namespace App\Http\Controllers;

use App\Models\Bantuan;
use App\Models\Hunian;
use App\Models\Rumah;
use App\Models\Warga;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama sistem E-RTLH Kota Kediri menggunakan Laravel Blade.
     */
    public function index()
    {
        $user = Auth::user();

        // Nilai awal / default
        $totalWarga = 0;
        $totalWilayah = 46; // Kota Kediri memiliki 46 Kelurahan
        $totalUsulan = 0;
        $usulanDiajukan = 0;
        $usulanDiverifikasi = 0;
        $usulanDisetujui = 0;
        $usulanRevisi = 0;
        $usulanDitolak = 0;
        $usulanRealisasi = 0;
        $totalHunian = 0;
        $totalRumahSurvei = 0;
        $rumahRusakBerat = 0;
        $rumahRusakSedang = 0;
        $rumahRusakRingan = 0;
        $recentBantuan = collect();
        $recentWarga = collect();
        $kecamatanLabels = ['Mojoroto', 'Kota', 'Pesantren'];
        $kecamatanData = [0, 0, 0];

        try {
            // 1. Data Ringkasan Calon Penerima & Wilayah
            $wargaQuery = Warga::query();
            if ($user) {
                $wargaQuery->visibleTo($user);
            }
            $totalWarga = $wargaQuery->count();

            $totalWilayah = Wilayah::count() ?: 46;

            // 2. Data Statistik Usulan Bantuan RTLH
            $bantuanQuery = Bantuan::query();
            if ($user) {
                $bantuanQuery->visibleTo($user);
            }

            $totalUsulan = (clone $bantuanQuery)->count();
            $usulanDiajukan = (clone $bantuanQuery)->where('status_bantuan', Bantuan::STATUS_DIAJUKAN)->count();
            $usulanDiverifikasi = (clone $bantuanQuery)->where('status_bantuan', Bantuan::STATUS_DIVERIFIKASI)->count();
            $usulanDisetujui = (clone $bantuanQuery)->where('status_bantuan', Bantuan::STATUS_DISETUJUI)->count();
            $usulanRevisi = (clone $bantuanQuery)->where('status_bantuan', Bantuan::STATUS_REVISI)->count();
            $usulanDitolak = (clone $bantuanQuery)->where('status_bantuan', Bantuan::STATUS_DITOLAK)->count();
            $usulanRealisasi = (clone $bantuanQuery)->where('status_bantuan', Bantuan::STATUS_DIREALISASIKAN)->count();

            // 3. Data Survei Fisik Rumah & Hunian
            $hunianQuery = Hunian::query();
            $rumahQuery = Rumah::query();

            if ($user && !$user->isSuperadmin() && !$user->isAdminDinas()) {
                $hunianQuery->whereHas('warga', function ($q) use ($user) {
                    $q->where('id_wilayah', $user->id_wilayah);
                });
                $rumahQuery->whereHas('hunian.warga', function ($q) use ($user) {
                    $q->where('id_wilayah', $user->id_wilayah);
                });
            }

            $totalHunian = $hunianQuery->count();
            $totalRumahSurvei = $rumahQuery->count();

            // Rumah dengan tingkat kerusakan berat
            $rumahRusakBerat = (clone $rumahQuery)->where(function ($q) {
                $q->where('kondisi_atap', 3)
                  ->orWhere('kondisi_dinding', 3)
                  ->orWhere('kondisi_lantai', 3);
            })->count();

            $rumahRusakSedang = (clone $rumahQuery)->where(function ($q) {
                $q->where('kondisi_atap', 2)
                  ->orWhere('kondisi_dinding', 2)
                  ->orWhere('kondisi_lantai', 2);
            })->count();

            $rumahRusakRingan = max(0, $totalRumahSurvei - $rumahRusakBerat - $rumahRusakSedang);

            // 4. Data Usulan Bantuan Terkini
            $recentBantuan = Bantuan::with(['warga.wilayah', 'program', 'userPengajuan'])
                ->when($user, function ($q) use ($user) {
                    $q->visibleTo($user);
                })
                ->latest('id_bantuan')
                ->take(6)
                ->get();

            // 5. Data Calon Penerima Terkini
            $recentWarga = Warga::with(['wilayah'])
                ->when($user, function ($q) use ($user) {
                    $q->visibleTo($user);
                })
                ->latest('id_warga')
                ->take(5)
                ->get();

            // 6. Sebaran Usulan / Warga per Kecamatan di Kota Kediri
            $sebaranKecamatan = Warga::join('wilayah', 'warga.id_wilayah', '=', 'wilayah.id_wilayah')
                ->select('wilayah.kecamatan', DB::raw('count(*) as total'))
                ->groupBy('wilayah.kecamatan')
                ->pluck('total', 'kecamatan')
                ->toArray();

            $kecamatanData = [
                $sebaranKecamatan['Mojoroto'] ?? ($sebaranKecamatan['Kecamatan Mojoroto'] ?? 0),
                $sebaranKecamatan['Kota'] ?? ($sebaranKecamatan['Kecamatan Kota'] ?? 0),
                $sebaranKecamatan['Pesantren'] ?? ($sebaranKecamatan['Kecamatan Pesantren'] ?? 0),
            ];
        } catch (\Throwable $e) {
            Log::warning('DashboardController query warning: ' . $e->getMessage());
        }

        // Sebaran status bantuan untuk chart
        $statusLabels = ['Diajukan', 'Diverifikasi', 'Disetujui', 'Realisasi', 'Revisi / Ditolak'];
        $statusData = [
            $usulanDiajukan,
            $usulanDiverifikasi,
            $usulanDisetujui,
            $usulanRealisasi,
            $usulanRevisi + $usulanDitolak,
        ];

        // Status alur Perwali Kota Kediri No. 18 Tahun 2026
        $tahapanAlur = [
            [
                'step' => 1,
                'judul' => 'Pengusulan Calon Penerima',
                'deskripsi' => 'Pengajuan berkas, data kependudukan, status sosial & lahan calon penerima.',
                'icon' => 'bi-file-earmark-person-fill',
                'status' => 'Aktif',
                'color' => 'primary',
                'count' => $totalWarga
            ],
            [
                'step' => 2,
                'judul' => 'Verifikasi & Survei Lapangan',
                'deskripsi' => 'Pemeriksaan fisik konstruksi atap, lantai, dinding & utilitas rumah RTLH.',
                'icon' => 'bi-house-check-fill',
                'status' => 'Proses',
                'color' => 'info',
                'count' => $totalRumahSurvei
            ],
            [
                'step' => 3,
                'judul' => 'Validasi & Penetapan SK',
                'deskripsi' => 'Penyusunan rekomendasi SPK, sidang penetapan penerima bansos Kota Kediri.',
                'icon' => 'bi-award-fill',
                'status' => 'Tahapan',
                'color' => 'warning',
                'count' => $usulanDisetujui
            ],
            [
                'step' => 4,
                'judul' => 'Pelaksanaan & Realisasi Fisik',
                'deskripsi' => 'Pencairan dana perbaikan, monitoring progres konstruksi 0%, 50%, dan 100%.',
                'icon' => 'bi-tools',
                'status' => 'Monitoring',
                'color' => 'success',
                'count' => $usulanRealisasi
            ],
            [
                'step' => 5,
                'judul' => 'Pertanggungjawaban (SPJ)',
                'deskripsi' => 'Penyusunan laporan realisasi dana, kuitansi belanja & berita acara selesai.',
                'icon' => 'bi-journal-check',
                'status' => 'Audit',
                'color' => 'secondary',
                'count' => $usulanRealisasi
            ],
        ];

        return view('pages.dashboard', compact(
            'user',
            'totalWarga',
            'totalWilayah',
            'totalUsulan',
            'usulanDiajukan',
            'usulanDiverifikasi',
            'usulanDisetujui',
            'usulanRevisi',
            'usulanDitolak',
            'usulanRealisasi',
            'totalHunian',
            'totalRumahSurvei',
            'rumahRusakBerat',
            'rumahRusakSedang',
            'rumahRusakRingan',
            'recentBantuan',
            'recentWarga',
            'kecamatanLabels',
            'kecamatanData',
            'statusLabels',
            'statusData',
            'tahapanAlur'
        ));
    }
}
