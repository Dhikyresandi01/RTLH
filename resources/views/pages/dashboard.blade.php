@extends('app')

@section('title', 'Dashboard')

@section('content')
<div class="page-heading">
    <!-- Header Title & Action Buttons -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold mb-1 text-dark">
                <i class="bi bi-speedometer2 text-primary me-2"></i>Dashboard E-RTLH
            </h3>
            <p class="text-muted mb-0">
                Sistem Pengelolaan Bantuan Sosial Rumah Tidak Layak Huni Kota Kediri
                <span class="badge bg-light-primary text-primary ms-1">Tahun Anggaran {{ date('Y') }}</span>
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if(Route::has('warga.create'))
                <a href="{{ route('warga.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>+ Usulan Calon Penerima</span>
                </a>
            @else
                <a href="{{ route('warga') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-people-fill"></i>
                    <span>Data Calon Penerima</span>
                </a>
            @endif

            <a href="{{ route('hunian.index') }}" class="btn btn-outline-primary d-flex align-items-center gap-2">
                <i class="bi bi-geo-alt"></i>
                <span>Data Hunian</span>
            </a>

            <a href="{{ route('rumah.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                <i class="bi bi-house-gear"></i>
                <span>Survei Fisik</span>
            </a>
        </div>
    </div>

    <!-- User Notice Banner -->
    <div class="card bg-primary text-white mb-4 border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #435ebe 0%, #25396f 100%);">
        <div class="card-body p-4 position-relative">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-9">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-white text-primary fw-bold px-3 py-1 rounded-pill me-2">
                            @if(Auth::user()->isSuperadmin())
                                Superadmin DPKP
                            @elseif(Auth::user()->isAdminDinas())
                                Administrator Dinas
                            @else
                                Petugas {{ Auth::user()->wilayah->desa ?? 'Kelurahan' }}
                            @endif
                        </span>
                        <span class="text-white-50 small">
                            <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}
                        </span>
                    </div>
                    <h4 class="text-white fw-bold mb-1">
                        Selamat Datang, {{ Auth::user()->name }}!
                    </h4>
                    <p class="text-white-50 mb-0 small">
                        Monitoring terpusat penetapan, verifikasi kriteria fisik rumah, dan realisasi bantuan RTLH Kota Kediri sesuai dengan 
                        <strong>Peraturan Walikota Kediri Nomor 18 Tahun 2026</strong>.
                    </p>
                </div>
                <div class="col-lg-4 col-md-3 text-md-end mt-3 mt-md-0 d-none d-md-block">
                    <div class="d-inline-flex p-3 rounded-circle bg-white bg-opacity-10">
                        <i class="bi bi-building-check text-white" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-content">
    <!-- 4 Main KPI Cards -->
    <section class="row">
        <!-- Total Calon Penerima -->
        <div class="col-6 col-lg-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body px-3 py-4-5">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon purple me-3">
                            <i class="iconly-boldProfile text-white"></i>
                        </div>
                        <div>
                            <h6 class="text-muted font-semibold mb-1">Total Calon Penerima</h6>
                            <h4 class="font-extrabold mb-0 text-dark">{{ number_format($totalWarga) }}</h4>
                            <small class="text-muted" style="font-size: 0.75rem;">
                                <i class="bi bi-people me-1"></i> Warga Terdaftar
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menunggu Verifikasi -->
        <div class="col-6 col-lg-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body px-3 py-4-5">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon amber me-3">
                            <i class="iconly-boldDocument text-white"></i>
                        </div>
                        <div>
                            <h6 class="text-muted font-semibold mb-1">Menunggu Verifikasi</h6>
                            <h4 class="font-extrabold mb-0 text-dark">{{ number_format($usulanDiajukan) }}</h4>
                            <small class="text-warning fw-semibold" style="font-size: 0.75rem;">
                                <i class="bi bi-clock-history me-1"></i> Usulan Masuk
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bantuan Disetujui -->
        <div class="col-6 col-lg-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body px-3 py-4-5">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon blue me-3">
                            <i class="iconly-boldTicket-Star text-white"></i>
                        </div>
                        <div>
                            <h6 class="text-muted font-semibold mb-1">Disetujui / SK</h6>
                            <h4 class="font-extrabold mb-0 text-dark">{{ number_format($usulanDisetujui) }}</h4>
                            <small class="text-info fw-semibold" style="font-size: 0.75rem;">
                                <i class="bi bi-check2-circle me-1"></i> Siap Realisasi
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Realisasi Bantuan -->
        <div class="col-6 col-lg-3 col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-body px-3 py-4-5">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon green me-3">
                            <i class="iconly-boldWork text-white"></i>
                        </div>
                        <div>
                            <h6 class="text-muted font-semibold mb-1">Realisasi Bantuan</h6>
                            <h4 class="font-extrabold mb-0 text-dark">{{ number_format($usulanRealisasi) }}</h4>
                            <small class="text-success fw-semibold" style="font-size: 0.75rem;">
                                <i class="bi bi-hammer me-1"></i> Pelaksanaan Fisik
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Secondary Summary Stats Row -->
    <section class="row mb-4">
        <div class="col-12">
            <div class="card bg-white border-0 py-2">
                <div class="card-body py-2">
                    <div class="row text-center text-md-start g-3">
                        <div class="col-6 col-md-3 border-end">
                            <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="bg-light-info text-info rounded p-2 me-2">
                                    <i class="bi bi-clipboard2-check fs-5"></i>
                                </div>
                                <div>
                                    <span class="d-block text-muted small">Survei Lapangan</span>
                                    <strong class="fs-6 text-dark">{{ $totalRumahSurvei }} Rumah</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 border-end">
                            <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="bg-light-danger text-danger rounded p-2 me-2">
                                    <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                                </div>
                                <div>
                                    <span class="d-block text-muted small">Rumah Rusak Berat</span>
                                    <strong class="fs-6 text-danger">{{ $rumahRusakBerat }} Rumah</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 border-end">
                            <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="bg-light-primary text-primary rounded p-2 me-2">
                                    <i class="bi bi-geo-alt fs-5"></i>
                                </div>
                                <div>
                                    <span class="d-block text-muted small">Cakupan Wilayah</span>
                                    <strong class="fs-6 text-dark">{{ $totalWilayah > 0 ? $totalWilayah : '46' }} Kelurahan</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                                <div class="bg-light-success text-success rounded p-2 me-2">
                                    <i class="bi bi-shield-fill-check fs-5"></i>
                                </div>
                                <div>
                                    <span class="d-block text-muted small">Status Usulan Diverifikasi</span>
                                    <strong class="fs-6 text-success">{{ $usulanDiverifikasi }} Usulan</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Analytics & Charts Section -->
    <section class="row">
        <!-- Chart: Status Usulan Bantuan RTLH -->
        <div class="col-12 col-xl-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center pb-0">
                    <div>
                        <h5 class="card-title mb-1 fw-bold">Statistik Tahapan Usulan Bantuan</h5>
                        <p class="text-muted small mb-0">Distribusi usulan berdasarkan status proses penanganan RTLH</p>
                    </div>
                    <span class="badge bg-light-primary text-primary">Kota Kediri</span>
                </div>
                <div class="card-body">
                    <div id="chart-status-bantuan" style="min-height: 310px;"></div>
                </div>
            </div>
        </div>

        <!-- Chart: Sebaran per Kecamatan di Kota Kediri -->
        <div class="col-12 col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header pb-0">
                    <h5 class="card-title mb-1 fw-bold">Sebaran per Kecamatan</h5>
                    <p class="text-muted small mb-0">Proporsi calon penerima di 3 Kecamatan</p>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <div id="chart-sebaran-kecamatan" style="min-height: 250px;"></div>
                    <div class="mt-3 pt-3 border-top">
                        <div class="row text-center">
                            <div class="col-4">
                                <span class="d-block text-muted small">Mojoroto</span>
                                <h6 class="fw-bold mb-0 text-primary">{{ $kecamatanData[0] }}</h6>
                            </div>
                            <div class="col-4 border-start border-end">
                                <span class="d-block text-muted small">Kota</span>
                                <h6 class="fw-bold mb-0 text-success">{{ $kecamatanData[1] }}</h6>
                            </div>
                            <div class="col-4">
                                <span class="d-block text-muted small">Pesantren</span>
                                <h6 class="fw-bold mb-0 text-info">{{ $kecamatanData[2] }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Alur Tahapan Perwali No. 18 Tahun 2026 -->
    <section class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-2">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h5 class="card-title fw-bold mb-1">
                                <i class="bi bi-diagram-3-fill text-primary me-2"></i>Alur Kegiatan Bantuan Sosial RTLH
                            </h5>
                            <p class="text-muted small mb-0">
                                Berdasarkan Petunjuk Pelaksanaan Perwali Kota Kediri Nomor 18 Tahun 2026
                            </p>
                        </div>
                        <span class="badge bg-light-info text-info px-3 py-2">
                            <i class="bi bi-info-circle me-1"></i> 5 Tahapan Pokok
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($tahapanAlur as $alur)
                            <div class="col-12 col-md-6 col-lg">
                                <div class="border rounded-3 p-3 h-100 d-flex flex-column justify-content-between position-relative bg-light bg-opacity-50">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge bg-{{ $alur['color'] }} rounded-circle p-2 d-inline-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                            {{ $alur['step'] }}
                                        </span>
                                        <span class="badge bg-light-{{ $alur['color'] }} text-{{ $alur['color'] }} small">
                                            {{ $alur['status'] }}
                                        </span>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-dark" style="font-size: 0.9rem;">
                                            <i class="bi {{ $alur['icon'] }} text-{{ $alur['color'] }} me-1"></i>
                                            {{ $alur['judul'] }}
                                        </h6>
                                        <p class="text-muted small mb-3" style="font-size: 0.78rem; line-height: 1.4;">
                                            {{ $alur['deskripsi'] }}
                                        </p>
                                    </div>
                                    <div class="pt-2 border-top d-flex justify-content-between align-items-center text-muted small">
                                        <span>Total Data:</span>
                                        <span class="fw-bold text-dark">{{ $alur['count'] }} item</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Usulan Bantuan Terkini & Calon Penerima Terbaru -->
    <section class="row">
        <!-- Tabel Usulan Bantuan Terkini -->
        <div class="col-12 col-xl-8 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title fw-bold mb-1">Usulan Bantuan RTLH Terkini</h5>
                        <p class="text-muted small mb-0">Daftar usulan bantuan yang masuk ke sistem</p>
                    </div>
                    @if(Route::has('bantuan.index'))
                        <a href="{{ route('bantuan.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    @endif
                </div>
                <div class="card-body px-0 pt-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Calon Penerima</th>
                                    <th>Wilayah</th>
                                    <th>Tahun</th>
                                    <th>Status Usulan</th>
                                    <th class="text-end pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBantuan as $bantuan)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark">{{ $bantuan->warga->nama ?? 'Nama Warga' }}</div>
                                            <small class="text-muted">NIK: {{ $bantuan->warga->nik ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <div>{{ $bantuan->warga->wilayah->desa ?? 'Kelurahan' }}</div>
                                            <small class="text-muted">{{ $bantuan->warga->wilayah->kecamatan ?? 'Kota Kediri' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light-secondary text-secondary">{{ $bantuan->tahun_bantuan }}</span>
                                        </td>
                                        <td>
                                            @if($bantuan->status_bantuan == \App\Models\Bantuan::STATUS_DIAJUKAN)
                                                <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Diajukan</span>
                                            @elseif($bantuan->status_bantuan == \App\Models\Bantuan::STATUS_DIVERIFIKASI)
                                                <span class="badge bg-info text-white"><i class="bi bi-search me-1"></i>Diverifikasi</span>
                                            @elseif($bantuan->status_bantuan == \App\Models\Bantuan::STATUS_DISETUJUI)
                                                <span class="badge bg-primary text-white"><i class="bi bi-check-circle me-1"></i>Disetujui</span>
                                            @elseif($bantuan->status_bantuan == \App\Models\Bantuan::STATUS_DIREALISASIKAN)
                                                <span class="badge bg-success text-white"><i class="bi bi-hammer me-1"></i>Realisasi</span>
                                            @elseif($bantuan->status_bantuan == \App\Models\Bantuan::STATUS_REVISI)
                                                <span class="badge bg-secondary text-white"><i class="bi bi-arrow-repeat me-1"></i>Revisi</span>
                                            @else
                                                <span class="badge bg-danger text-white"><i class="bi bi-x-circle me-1"></i>Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            @if(Route::has('bantuan.show'))
                                                <a href="{{ route('bantuan.show', $bantuan->id_bantuan) }}" class="btn btn-sm btn-light-primary">
                                                    <i class="bi bi-eye"></i> Detail
                                                </a>
                                            @else
                                                <a href="{{ route('warga.show', $bantuan->id_warga) }}" class="btn btn-sm btn-light-primary">
                                                    <i class="bi bi-eye"></i> Warga
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <div class="py-3">
                                                <i class="bi bi-inbox fs-1 d-block mb-2 text-muted opacity-50"></i>
                                                <p class="mb-2">Belum ada data usulan bantuan RTLH terkini.</p>
                                                @if(Route::has('warga.create'))
                                                    <a href="{{ route('warga.create') }}" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-plus-lg me-1"></i> Tambah Usulan Calon Penerima
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calon Penerima Terdaftar Terkini -->
        <div class="col-12 col-xl-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title fw-bold mb-1">Calon Penerima Baru</h5>
                        <p class="text-muted small mb-0">Warga yang baru didaftarkan</p>
                    </div>
                    <a href="{{ route('warga') }}" class="btn btn-sm btn-outline-secondary">Semua</a>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($recentWarga as $warga)
                            <div class="list-group-item px-0 py-2 border-0 border-bottom">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center overflow-hidden me-2">
                                        <div class="avatar bg-light-primary text-primary rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-person-fill fs-5"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <h6 class="mb-0 text-truncate fw-bold text-dark" style="font-size: 0.9rem;">
                                                {{ $warga->nama }}
                                            </h6>
                                            <small class="text-muted d-block text-truncate" style="font-size: 0.78rem;">
                                                {{ $warga->wilayah->desa ?? 'Kelurahan' }} &bull; RT {{ $warga->rt ?? '-' }}/RW {{ $warga->rw ?? '-' }}
                                            </small>
                                        </div>
                                    </div>
                                    <div>
                                        @if($warga->status_update == 1)
                                            <span class="badge bg-success small"><i class="bi bi-check2"></i> Terverifikasi</span>
                                        @else
                                            <span class="badge bg-light-warning text-dark small">Baru</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-people fs-2 d-block mb-2 text-muted opacity-50"></i>
                                <small>Belum ada data warga terdaftar.</small>
                            </div>
                        @endforelse
                    </div>

                    <!-- Quick Information Card -->
                    <div class="mt-4 p-3 bg-light rounded-3 border">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-info-circle-fill text-primary me-2"></i>
                            <h6 class="mb-0 fw-bold small text-dark">Kriteria Penilaian RTLH</h6>
                        </div>
                        <ul class="text-muted small ps-3 mb-0" style="font-size: 0.75rem;">
                            <li>Prioritas kerusakan konstruksi atap, lantai, dan dinding.</li>
                            <li>Status kepemilikan tanah sah (SHM/Letter C/Surat Pernyataan).</li>
                            <li>Satu-satunya rumah yang dihuni oleh calon penerima bantuan.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Chart Statistik Status Usulan Bantuan RTLH
    const statusLabels = @json($statusLabels);
    const statusData = @json($statusData);

    const optionsStatus = {
        series: [{
            name: 'Jumlah Usulan',
            data: statusData
        }],
        chart: {
            type: 'bar',
            height: 310,
            toolbar: {
                show: false
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 6,
                horizontal: false,
                columnWidth: '45%',
                distributed: true
            }
        },
        colors: ['#ffab00', '#03c3ec', '#696cff', '#71dd37', '#8592a3'],
        dataLabels: {
            enabled: true,
            style: {
                fontSize: '12px',
                colors: ['#fff']
            }
        },
        legend: {
            show: false
        },
        xaxis: {
            categories: statusLabels,
            labels: {
                style: {
                    fontSize: '12px',
                    fontWeight: 500
                }
            }
        },
        yaxis: {
            title: {
                text: 'Jumlah Usulan (Kasus)',
                style: {
                    fontSize: '12px',
                    color: '#8592a3'
                }
            }
        },
        grid: {
            borderColor: '#f1f1f1',
        },
        noData: {
            text: 'Belum ada data usulan di database',
            align: 'center',
            verticalAlign: 'middle',
            style: {
                color: '#8592a3',
                fontSize: '13px'
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + ' Usulan Bantuan';
                }
            }
        }
    };

    const chartStatusEl = document.querySelector("#chart-status-bantuan");
    if (chartStatusEl) {
        const chartStatus = new ApexCharts(chartStatusEl, optionsStatus);
        chartStatus.render();
    }

    // 2. Chart Sebaran Calon Penerima per Kecamatan di Kota Kediri
    const kecLabels = @json($kecamatanLabels);
    const kecData = @json($kecamatanData);

    const optionsKecamatan = {
        series: kecData,
        labels: kecLabels,
        chart: {
            type: 'donut',
            height: 250
        },
        noData: {
            text: 'Belum ada data wilayah di database',
            align: 'center',
            verticalAlign: 'middle',
            style: {
                color: '#8592a3',
                fontSize: '13px'
            }
        },
        colors: ['#435ebe', '#55c6e8', '#41be9a'],
        legend: {
            position: 'bottom',
            fontSize: '12px'
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '60%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '14px',
                            fontWeight: 600,
                            formatter: function (w) {
                                return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                            }
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: false
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return val + " Penerima";
                }
            }
        }
    };

    const chartKecEl = document.querySelector("#chart-sebaran-kecamatan");
    if (chartKecEl) {
        const chartKecamatan = new ApexCharts(chartKecEl, optionsKecamatan);
        chartKecamatan.render();
    }
});
</script>
@endpush