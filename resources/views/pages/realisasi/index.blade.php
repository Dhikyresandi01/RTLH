@extends('app')

@section('title', 'Realisasi & Penyaluran Bantuan RTLH')

@section('content')
@php
    $isDinas = Auth::check() && (Auth::user()->isSuperadmin() || Auth::user()->isAdminDinas());
@endphp
<style>
    .kpi-card {
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
    }
    .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .table-realisasi thead th {
        background-color: #f8f9fa !important;
        color: #495057;
        font-weight: 700;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.85rem 1rem !important;
        border-bottom: 2px solid #e9ecef !important;
        white-space: nowrap;
    }
    .table-realisasi tbody td {
        padding: 0.95rem 1rem !important;
        vertical-align: middle;
        font-size: 0.88rem;
    }
    .thumb-foto {
        width: 36px;
        height: 36px;
        object-fit: cover;
        border-radius: 6px;
        cursor: pointer;
        transition: transform 0.15s ease;
        border: 1px solid #dee2e6;
    }
    .thumb-foto:hover {
        transform: scale(1.15);
    }
    .thumb-empty {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        border: 1px dashed #ced4da;
        background: #f8f9fa;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #adb5bd;
        font-size: 0.72rem;
    }
</style>

<div class="page-heading">
    <!-- Header Halaman -->
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-12 col-md-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Pelaksanaan & Bantuan</li>
                        <li class="breadcrumb-item active" aria-current="page">Realisasi Bantuan</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-1 text-primary">
                    <i class="bi bi-hammer me-2"></i>Realisasi & Progres Bantuan RTLH
                </h3>
                <p class="text-muted small mb-0">
                    @if($isDinas)
                        Monitoring penyaluran bantuan, progres pengerjaan fisik bangunan (0%, 25%, 50%, 100%), dan dokumen pertanggungjawaban calon penerima yang telah disetujui Dinas.
                    @else
                        Pantau progres pengerjaan fisik bangunan (0%, 25%, 50%, 100%) dan dokumentasi penyaluran bantuan RTLH di wilayah <strong>{{ Auth::user()->wilayah->desa ?? 'Kelurahan' }}</strong>.
                    @endif
                </p>
            </div>
            <div class="col-12 col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('warga.index') }}" class="btn btn-outline-primary shadow-sm px-3 py-2">
                    <i class="bi bi-people-fill me-1"></i> Daftar Usulan
                </a>
            </div>
        </div>
    </div>

    @if(!$isDinas)
        <div class="alert alert-light-info border border-info-subtle shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-eye-fill text-info fs-5 me-2"></i>
                <small class="text-dark mb-0">
                    <strong>Mode Pantau Kelurahan (View-Only):</strong> Anda dapat memantau progres fisik dan galeri dokumentasi realisasi untuk warga di kelurahan Anda. Perubahan atau penghapusan progres realisasi hanya dilakukan oleh Admin Dinas / Superadmin.
                </small>
            </div>
        </div>
    @endif

    <!-- Alert Flash Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-4 me-2 text-success"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible show fade shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-2 text-danger"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- 4 KPI Summary Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 border-start border-primary border-4 h-100 kpi-card">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="kpi-icon bg-light-primary text-primary me-3">
                        <i class="bi bi-person-check-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Penerima Terverifikasi</div>
                        <h4 class="fw-bold mb-0 text-primary">{{ $totalPenerima }}</h4>
                        <small class="text-muted" style="font-size: 0.72rem;">Siap / Sedang Realisasi</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 border-start border-secondary border-4 h-100 kpi-card">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="kpi-icon bg-light text-secondary me-3">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Belum Mulai (0%)</div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalBelumMulai }}</h4>
                        <small class="text-muted" style="font-size: 0.72rem;">Persiapan & Titik Nol</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 border-start border-warning border-4 h-100 kpi-card">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="kpi-icon bg-light-warning text-warning me-3">
                        <i class="bi bi-cone-striped fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Dalam Pengerjaan</div>
                        <h4 class="fw-bold mb-0 text-warning">{{ $totalProgres25 + $totalProgres50 }}</h4>
                        <small class="text-muted" style="font-size: 0.72rem;">Progres 25% & 50%</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 border-start border-success border-4 h-100 kpi-card">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="kpi-icon bg-light-success text-success me-3">
                        <i class="bi bi-house-check-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Selesai 100%</div>
                        <h4 class="fw-bold mb-0 text-success">{{ $totalSelesai }}</h4>
                        <small class="text-muted" style="font-size: 0.72rem;">Direalisasikan Penuh</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('realisasi.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-md-{{ $isDinas ? '2' : '3' }}">
                        <label class="form-label small fw-semibold text-muted mb-1">Cari NIK / Nama / KK</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Ketik pencarian..." value="{{ request('search') }}">
                        </div>
                    </div>

                    @if($isDinas)
                        <div class="col-12 col-md-2">
                            <label class="form-label small fw-semibold text-muted mb-1">Filter Kelurahan</label>
                            <select name="id_wilayah" class="form-select form-select-sm">
                                <option value="">Semua Kelurahan</option>
                                @foreach($wilayahs as $w)
                                    <option value="{{ $w->id_wilayah }}" {{ request('id_wilayah') == $w->id_wilayah ? 'selected' : '' }}>
                                        {{ $w->desa }} (Kec. {{ $w->kecamatan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-12 col-md-2">
                        <label class="form-label small fw-semibold text-muted mb-1">Jenis Bantuan</label>
                        <select name="id_program" class="form-select form-select-sm">
                            <option value="">Semua Program</option>
                            @foreach($programs as $prog)
                                <option value="{{ $prog->id_program }}" {{ request('id_program') == $prog->id_program ? 'selected' : '' }}>
                                    {{ $prog->nama_program }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="form-label small fw-semibold text-muted mb-1">Status Bantuan</label>
                        <select name="status_bantuan" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="{{ \App\Models\Bantuan::STATUS_DISETUJUI }}" {{ request('status_bantuan') === (string) \App\Models\Bantuan::STATUS_DISETUJUI ? 'selected' : '' }}>Disetujui / Siap Realisasi</option>
                            <option value="{{ \App\Models\Bantuan::STATUS_DIREALISASIKAN }}" {{ request('status_bantuan') === (string) \App\Models\Bantuan::STATUS_DIREALISASIKAN ? 'selected' : '' }}>Direalisasikan (100%)</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <label class="form-label small fw-semibold text-muted mb-1">Progres Fisik</label>
                        <select name="progres" class="form-select form-select-sm">
                            <option value="">Semua Progres</option>
                            <option value="belum" {{ request('progres') === 'belum' ? 'selected' : '' }}>Belum Mulai (0%)</option>
                            <option value="25" {{ request('progres') === '25' ? 'selected' : '' }}>Progres 25%</option>
                            <option value="50" {{ request('progres') === '50' ? 'selected' : '' }}>Progres 50%</option>
                            <option value="100" {{ request('progres') === '100' ? 'selected' : '' }}>Selesai (100%)</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-{{ $isDinas ? '1' : '2' }}">
                        <label class="form-label small fw-semibold text-muted mb-1">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            @foreach($availableYears as $y)
                                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-1 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100" title="Terapkan Filter">
                            <i class="bi bi-funnel"></i>
                        </button>
                        @if(request()->hasAny(['search', 'id_wilayah', 'id_program', 'progres', 'tahun', 'status_bantuan']))
                            <a href="{{ route('realisasi.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Realisasi -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h5 class="card-title fw-bold text-dark mb-0">Daftar Penerima Bantuan RTLH Terverifikasi</h5>
                <small class="text-muted">Kelola progres fisik, dokumentasi foto bertahap, dan kuitansi pertanggungjawaban pelaksanaan bantuan.</small>
            </div>
            <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill fw-bold">
                Total: {{ $bantuans->total() }} Penerima
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle table-realisasi">
                    <thead>
                        <tr>
                            <th class="ps-4" style="width: 50px;">No</th>
                            <th>Penerima Manfaat</th>
                            <th>Kelurahan & Alamat</th>
                            <th>Program Bantuan</th>
                            <th>Status Verifikasi</th>
                            <th style="min-width: 170px;">Progres Fisik</th>
                            <th>Dokumentasi Foto</th>
                            <th>Kuitansi / SPJ</th>
                            <th class="text-end pe-4" style="width: 130px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bantuans as $index => $bantuan)
                            @php
                                $warga = $bantuan->warga;
                                $realisasi = $bantuan->realisasiTerbaru;
                                $progres = $realisasi ? (int) $realisasi->status : 0;
                            @endphp
                            <tr>
                                <td class="ps-4 fw-semibold text-muted">
                                    {{ $bantuans->firstItem() + $index }}
                                </td>

                                <!-- Identitas Penerima -->
                                <td>
                                    @if($warga)
                                        <a href="{{ route('warga.show', $warga->id_warga) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $warga->nama }}
                                        </a>
                                        <small class="text-muted d-block">NIK: {{ $warga->nik }}</small>
                                        <small class="text-muted">KK: {{ $warga->no_kk }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <!-- Wilayah & Alamat -->
                                <td>
                                    <div class="text-dark fw-semibold">{{ $warga?->wilayah?->desa ?? '-' }}</div>
                                    <small class="text-muted d-block">
                                        {{ $warga?->alamat ?? 'Alamat belum diisi' }} (RT {{ $warga?->rt ?? '-' }} / RW {{ $warga?->rw ?? '-' }})
                                    </small>
                                    <small class="text-secondary">Kec. {{ $warga?->wilayah?->kecamatan ?? 'Kediri' }}</small>
                                </td>

                                <!-- Jenis / Program Bantuan -->
                                <td>
                                    <div class="fw-semibold text-dark">{{ $bantuan->program?->nama_program ?? 'Bantuan RTLH' }}</div>
                                    @if($bantuan->program?->nominal_bantuan)
                                        <span class="badge bg-light text-success border border-success-subtle mt-1" style="font-size: 0.72rem;">
                                            Rp {{ number_format($bantuan->program->nominal_bantuan, 0, ',', '.') }}
                                        </span>
                                    @endif
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Tahun {{ $bantuan->tahun_bantuan }}</small>
                                </td>

                                <!-- Status Verifikasi Dinas -->
                                <td>
                                    @include('pages.warga.partials._status_badge', [
                                        'bantuan' => $bantuan,
                                        'showVerifikator' => $isDinas,
                                    ])
                                </td>

                                <!-- Progres Fisik Bangunan -->
                                <td>
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold" style="font-size: 0.8rem;">
                                            @if($progres == 100)
                                                <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Selesai 100%</span>
                                            @elseif($progres > 0)
                                                <span class="text-warning"><i class="bi bi-cone-striped me-1"></i>{{ $progres }}% Selesai</span>
                                            @else
                                                <span class="text-muted"><i class="bi bi-circle me-1"></i>0% (Titik Nol)</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="progress" style="height: 7px;">
                                        <div class="progress-bar {{ $progres == 100 ? 'bg-success' : ($progres >= 50 ? 'bg-warning' : ($progres >= 25 ? 'bg-info' : 'bg-secondary')) }}" 
                                             role="progressbar" 
                                             style="width: {{ $progres }}%" 
                                             aria-valuenow="{{ $progres }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                    @if($realisasi?->tgl_realisasi)
                                        <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">
                                            <i class="bi bi-calendar3 me-1"></i>{{ $realisasi->tgl_realisasi->format('d M Y') }}
                                        </small>
                                    @endif
                                </td>

                                <!-- Dokumentasi Foto Bertahap (0%, 25%, 50%, 100%) -->
                                <td>
                                    <div class="d-flex gap-1 align-items-center">
                                        @foreach(['foto_0' => '0%', 'foto_25' => '25%', 'foto_50' => '50%', 'foto_100' => '100%'] as $fotoKey => $fotoLabel)
                                            @if($realisasi && $realisasi->$fotoKey)
                                                <a href="{{ asset('storage/' . $realisasi->$fotoKey) }}" target="_blank" title="Foto {{ $fotoLabel }}">
                                                    <img src="{{ asset('storage/' . $realisasi->$fotoKey) }}" alt="{{ $fotoLabel }}" class="thumb-foto">
                                                </a>
                                            @else
                                                <span class="thumb-empty" title="Foto {{ $fotoLabel }} belum ada">{{ $fotoLabel }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>

                                <!-- Kuitansi / SPJ -->
                                <td>
                                    @if($realisasi && $realisasi->kwitansi)
                                        <a href="{{ asset('storage/' . $realisasi->kwitansi) }}" target="_blank" class="btn btn-sm btn-outline-success py-1 px-2" style="font-size: 0.78rem;">
                                            <i class="bi bi-file-earmark-check me-1"></i> Lihat SPJ
                                        </a>
                                    @else
                                        <span class="text-muted small">Belum ada</span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        @if($isDinas)
                                            <!-- Tombol Update Realisasi -->
                                            <button type="button" 
                                                    class="btn btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalUpdateRealisasi{{ $bantuan->id_bantuan }}" 
                                                    title="Input / Update Progres Realisasi">
                                                <i class="bi bi-pencil-square me-1"></i> Update
                                            </button>
                                        @elseif($warga)
                                            <a href="{{ route('warga.show', $warga->id_warga) }}" class="btn btn-light-primary" title="Detail Warga">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endif

                                        <!-- Tombol Galeri Detail -->
                                        <button type="button" 
                                                class="btn btn-outline-secondary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalGaleri{{ $bantuan->id_bantuan }}" 
                                                title="Galeri Dokumentasi Foto Fisik">
                                            <i class="bi bi-images"></i>
                                        </button>

                                        @if($isDinas && $realisasi)
                                            <form action="{{ route('realisasi.destroy', $realisasi->id_realisasi) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus / reset data realisasi untuk {{ addslashes($warga?->nama ?? 'warga ini') }}? Status bantuan akan dikembalikan ke Disetujui Dinas.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-light-danger" title="Hapus / Reset Data Realisasi">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            @if($isDinas)
                            <!-- ================= MODAL UPDATE PROGRES REALISASI ================= -->
                            <div class="modal fade" id="modalUpdateRealisasi{{ $bantuan->id_bantuan }}" tabindex="-1" aria-labelledby="labelUpdate{{ $bantuan->id_bantuan }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header border-bottom">
                                            <h5 class="modal-title fw-bold text-primary" id="labelUpdate{{ $bantuan->id_bantuan }}">
                                                <i class="bi bi-hammer me-2"></i>Update Progres Realisasi Bantuan
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('realisasi.update', $bantuan->id_bantuan) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <!-- Info Calon Penerima -->
                                                <div class="bg-light p-3 rounded mb-3">
                                                    <div class="row g-2">
                                                        <div class="col-12 col-md-6">
                                                            <small class="text-muted d-block">Penerima Manfaat:</small>
                                                            <strong class="text-dark">{{ $warga?->nama }}</strong>
                                                            <div class="small text-muted">NIK: {{ $warga?->nik }}</div>
                                                        </div>
                                                        <div class="col-12 col-md-6">
                                                            <small class="text-muted d-block">Program & Lokasi:</small>
                                                            <strong class="text-dark">{{ $bantuan->program?->nama_program ?? 'Bantuan RTLH' }}</strong>
                                                            <div class="small text-muted">{{ $warga?->wilayah?->desa ?? '-' }} (RT {{ $warga?->rt ?? '-' }} / RW {{ $warga?->rw ?? '-' }})</div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row g-3">
                                                    <!-- Status Progres -->
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-semibold">Progres Fisik Pelaksanaan <span class="text-danger">*</span></label>
                                                        <select name="status" class="form-select" required>
                                                            <option value="0" {{ $progres == 0 ? 'selected' : '' }}>0% - Titik Nol / Persiapan Awal</option>
                                                            <option value="25" {{ $progres == 25 ? 'selected' : '' }}>25% - Pengerjaan Pondasi & Struktur</option>
                                                            <option value="50" {{ $progres == 50 ? 'selected' : '' }}>50% - Pemasangan Dinding & Rangka Atap</option>
                                                            <option value="100" {{ $progres == 100 ? 'selected' : '' }}>100% - Selesai & Serah Terima Fisik</option>
                                                        </select>
                                                        <small class="text-muted">Status 100% otomatis menandai bantuan selesai direalisasikan.</small>
                                                    </div>

                                                    <!-- Tanggal Realisasi -->
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-semibold">Tanggal Realisasi / Penyaluran</label>
                                                        <input type="date" name="tgl_realisasi" class="form-control" 
                                                               value="{{ $realisasi?->tgl_realisasi ? $realisasi->tgl_realisasi->format('Y-m-d') : date('Y-m-d') }}">
                                                    </div>

                                                    <div class="col-12">
                                                        <hr class="my-2">
                                                        <h6 class="fw-bold text-dark mb-2">
                                                            <i class="bi bi-camera-fill text-primary me-1"></i> Unggah Dokumentasi Foto Progres Fisik
                                                        </h6>
                                                        <p class="text-muted small mb-3">Format foto: JPG/PNG, ukuran maksimal 3MB per foto.</p>
                                                    </div>

                                                    <!-- Upload Foto 0% -->
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-semibold small">Foto Titik Nol (Progres 0%)</label>
                                                        @if($realisasi?->foto_0)
                                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                                <img src="{{ asset('storage/' . $realisasi->foto_0) }}" class="thumb-foto" alt="0%">
                                                                <small class="text-success"><i class="bi bi-check-circle me-1"></i>Sudah diunggah</small>
                                                            </div>
                                                        @endif
                                                        <input type="file" name="foto_0" accept="image/*" class="form-control form-control-sm">
                                                    </div>

                                                    <!-- Upload Foto 25% -->
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-semibold small">Foto Progres 25% (Struktur/Pondasi)</label>
                                                        @if($realisasi?->foto_25)
                                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                                <img src="{{ asset('storage/' . $realisasi->foto_25) }}" class="thumb-foto" alt="25%">
                                                                <small class="text-success"><i class="bi bi-check-circle me-1"></i>Sudah diunggah</small>
                                                            </div>
                                                        @endif
                                                        <input type="file" name="foto_25" accept="image/*" class="form-control form-control-sm">
                                                    </div>

                                                    <!-- Upload Foto 50% -->
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-semibold small">Foto Progres 50% (Dinding/Atap)</label>
                                                        @if($realisasi?->foto_50)
                                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                                <img src="{{ asset('storage/' . $realisasi->foto_50) }}" class="thumb-foto" alt="50%">
                                                                <small class="text-success"><i class="bi bi-check-circle me-1"></i>Sudah diunggah</small>
                                                            </div>
                                                        @endif
                                                        <input type="file" name="foto_50" accept="image/*" class="form-control form-control-sm">
                                                    </div>

                                                    <!-- Upload Foto 100% -->
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-semibold small">Foto Selesai (Progres 100%)</label>
                                                        @if($realisasi?->foto_100)
                                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                                <img src="{{ asset('storage/' . $realisasi->foto_100) }}" class="thumb-foto" alt="100%">
                                                                <small class="text-success"><i class="bi bi-check-circle me-1"></i>Sudah diunggah</small>
                                                            </div>
                                                        @endif
                                                        <input type="file" name="foto_100" accept="image/*" class="form-control form-control-sm">
                                                    </div>

                                                    <div class="col-12">
                                                        <hr class="my-2">
                                                    </div>

                                                    <!-- Upload Kuitansi / SPJ -->
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-semibold">Berkas Kuitansi / SPJ / Bukti Belanja</label>
                                                        @if($realisasi?->kwitansi)
                                                            <div class="mb-2">
                                                                <a href="{{ asset('storage/' . $realisasi->kwitansi) }}" target="_blank" class="badge bg-light text-success border border-success-subtle p-2 text-decoration-none">
                                                                    <i class="bi bi-file-earmark-check me-1"></i> Lihat Berkas SPJ Terunggah
                                                                </a>
                                                            </div>
                                                        @endif
                                                        <input type="file" name="kwitansi" accept="image/*,application/pdf" class="form-control form-control-sm">
                                                        <small class="text-muted">Format: PDF, JPG, PNG (maks. 4MB).</small>
                                                    </div>

                                                    <!-- Deskripsi / Catatan Lapangan -->
                                                    <div class="col-12 col-md-6">
                                                        <label class="form-label fw-semibold">Catatan / Deskripsi Lapangan</label>
                                                        <textarea name="deskripsi" rows="3" class="form-control form-control-sm" placeholder="Catatan mengenai kondisi pengerjaan, kendala material, atau penyaluran dana...">{{ $realisasi?->deskripsi }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    <i class="bi bi-x me-1"></i> Batal
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-save me-1"></i> Simpan Progres Realisasi
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- ================= END MODAL UPDATE ================= -->
                            @endif

                            <!-- ================= MODAL GALERI FOTO PERBANDINGAN ================= -->
                            <div class="modal fade" id="modalGaleri{{ $bantuan->id_bantuan }}" tabindex="-1" aria-labelledby="labelGaleri{{ $bantuan->id_bantuan }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header border-bottom">
                                            <div>
                                                <h5 class="modal-title fw-bold text-dark mb-0">
                                                    <i class="bi bi-images text-primary me-2"></i>Dokumentasi Fisik Realisasi: {{ $warga?->nama }}
                                                </h5>
                                                <small class="text-muted">{{ $warga?->wilayah?->desa ?? '-' }} | Program: {{ $bantuan->program?->nama_program ?? 'RTLH' }}</small>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="row g-3">
                                                <!-- Foto 0% -->
                                                <div class="col-12 col-sm-6 col-md-3">
                                                    <div class="card border shadow-none h-100 text-center">
                                                        <div class="card-header bg-light py-2 fw-bold text-muted small">
                                                            <i class="bi bi-circle me-1"></i> Titik Nol (0%)
                                                        </div>
                                                        <div class="card-body p-2 d-flex align-items-center justify-content-center" style="min-height: 200px; background: #fafafa;">
                                                            @if($realisasi?->foto_0)
                                                                <a href="{{ asset('storage/' . $realisasi->foto_0) }}" target="_blank">
                                                                    <img src="{{ asset('storage/' . $realisasi->foto_0) }}" class="img-fluid rounded" style="max-height: 190px;" alt="0%">
                                                                </a>
                                                            @else
                                                                <div class="text-muted small py-4">
                                                                    <i class="bi bi-image fs-1 d-block mb-1 text-secondary opacity-50"></i>
                                                                    Foto belum diunggah
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Foto 25% -->
                                                <div class="col-12 col-sm-6 col-md-3">
                                                    <div class="card border shadow-none h-100 text-center">
                                                        <div class="card-header bg-light-info py-2 fw-bold text-info small">
                                                            <i class="bi bi-cone me-1"></i> Progres 25%
                                                        </div>
                                                        <div class="card-body p-2 d-flex align-items-center justify-content-center" style="min-height: 200px; background: #fafafa;">
                                                            @if($realisasi?->foto_25)
                                                                <a href="{{ asset('storage/' . $realisasi->foto_25) }}" target="_blank">
                                                                    <img src="{{ asset('storage/' . $realisasi->foto_25) }}" class="img-fluid rounded" style="max-height: 190px;" alt="25%">
                                                                </a>
                                                            @else
                                                                <div class="text-muted small py-4">
                                                                    <i class="bi bi-image fs-1 d-block mb-1 text-secondary opacity-50"></i>
                                                                    Foto belum diunggah
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Foto 50% -->
                                                <div class="col-12 col-sm-6 col-md-3">
                                                    <div class="card border shadow-none h-100 text-center">
                                                        <div class="card-header bg-light-warning py-2 fw-bold text-warning small">
                                                            <i class="bi bi-bricks me-1"></i> Progres 50%
                                                        </div>
                                                        <div class="card-body p-2 d-flex align-items-center justify-content-center" style="min-height: 200px; background: #fafafa;">
                                                            @if($realisasi?->foto_50)
                                                                <a href="{{ asset('storage/' . $realisasi->foto_50) }}" target="_blank">
                                                                    <img src="{{ asset('storage/' . $realisasi->foto_50) }}" class="img-fluid rounded" style="max-height: 190px;" alt="50%">
                                                                </a>
                                                            @else
                                                                <div class="text-muted small py-4">
                                                                    <i class="bi bi-image fs-1 d-block mb-1 text-secondary opacity-50"></i>
                                                                    Foto belum diunggah
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Foto 100% -->
                                                <div class="col-12 col-sm-6 col-md-3">
                                                    <div class="card border shadow-none h-100 text-center">
                                                        <div class="card-header bg-light-success py-2 fw-bold text-success small">
                                                            <i class="bi bi-house-check-fill me-1"></i> Selesai (100%)
                                                        </div>
                                                        <div class="card-body p-2 d-flex align-items-center justify-content-center" style="min-height: 200px; background: #fafafa;">
                                                            @if($realisasi?->foto_100)
                                                                <a href="{{ asset('storage/' . $realisasi->foto_100) }}" target="_blank">
                                                                    <img src="{{ asset('storage/' . $realisasi->foto_100) }}" class="img-fluid rounded" style="max-height: 190px;" alt="100%">
                                                                </a>
                                                            @else
                                                                <div class="text-muted small py-4">
                                                                    <i class="bi bi-image fs-1 d-block mb-1 text-secondary opacity-50"></i>
                                                                    Foto belum diunggah
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            @if($realisasi?->deskripsi)
                                                <div class="bg-light p-3 rounded mt-3">
                                                    <strong class="text-dark d-block mb-1"><i class="bi bi-info-circle text-primary me-1"></i> Catatan Pelaksanaan:</strong>
                                                    <p class="text-muted small mb-0">{{ $realisasi->deskripsi }}</p>
                                                </div>
                                            @endif

                                            @if($realisasi?->kwitansi)
                                                <div class="mt-3 text-end">
                                                    <a href="{{ asset('storage/' . $realisasi->kwitansi) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                                        <i class="bi bi-download me-1"></i> Unduh / Buka Berkas SPJ & Kuitansi
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer border-top">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ================= END MODAL GALERI ================= -->

                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="py-3">
                                        <div class="avatar bg-light-primary text-primary mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px;">
                                            <i class="bi bi-hammer fs-2"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">Tidak ada data penerima bantuan terverifikasi</h6>
                                        <p class="text-muted small mb-3">Belum ada bantuan dengan status verifikasi disetujui atau sesuai filter yang dipilih.</p>
                                        <a href="{{ route('spk.index') }}" class="btn btn-sm btn-primary px-3 shadow-sm">
                                            <i class="bi bi-award me-1"></i> Buka Menu SPK & Verifikasi Dinas
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bantuans->hasPages())
                <div class="px-4 py-3 border-top">
                    {{ $bantuans->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
