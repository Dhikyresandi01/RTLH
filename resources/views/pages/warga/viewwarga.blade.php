@extends('app')

@php
    $isDinas = Auth::user()->isSuperadmin() || Auth::user()->isAdminDinas();
@endphp

@section('title', 'Daftar Usulan Calon Penerima RTLH')

@section('content')
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
    .kpi-icon i {
        width: auto;
        height: auto;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .kpi-icon i::before {
        vertical-align: middle;
        line-height: 1;
    }
    .nav-pills-custom .nav-link {
        color: #495057;
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.86rem;
        padding: 0.55rem 1.1rem;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
    }
    .nav-pills-custom .nav-link i {
        display: inline-flex;
        align-items: center;
        line-height: 1;
        font-size: 1rem;
    }
    .nav-pills-custom .nav-link i::before {
        vertical-align: middle;
        line-height: 1;
    }
    .nav-pills-custom .nav-link:hover {
        background-color: #f1f3f9;
        color: #002b84;
        border-color: #99b4ea;
    }
    .nav-pills-custom .nav-link.active {
        background-color: #002b84 !important;
        color: #ffffff !important;
        border-color: #002b84 !important;
        box-shadow: 0 4px 12px rgba(0, 43, 132, 0.25);
    }
    .table-terpadu thead th {
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
    .table-terpadu tbody td {
        padding: 0.95rem 1rem !important;
        vertical-align: middle;
        font-size: 0.88rem;
    }
</style>

<div class="page-heading">
    <!-- Header Halaman -->
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-12 col-xl-7">
                <h3 class="fw-bold mb-1 text-primary">
                    <i class="bi bi-people-fill me-2"></i>Daftar Usulan Calon Penerima RTLH
                </h3>
                @if($isDinas)
                    <p class="text-muted mb-0">
                        Monitoring usulan pra-verifikasi lintas kelurahan (status <em>Menunggu Verifikasi</em>, <em>Perlu Revisi</em>, dan <em>Ditolak</em>). Gunakan tombol <strong>Verifikasi</strong> untuk meloloskan usulan ke antrean SPK SAW.
                    </p>
                @else
                    <p class="text-muted mb-0">
                        Pusat data usulan terpadu kependudukan warga, kepemilikan hunian, dan survei fisik bangunan wilayah <strong>{{ Auth::user()->wilayah->desa ?? 'Kelurahan' }}</strong>.
                    </p>
                @endif
            </div>
            <div class="col-12 col-xl-5 text-xl-end mt-3 mt-xl-0 d-flex flex-wrap justify-content-xl-end gap-2">
                <!-- Tombol Ekspor Excel Terpadu -->
                <a href="{{ route('warga.export', request()->query()) }}" class="btn btn-success shadow-sm px-3 py-2 fw-semibold" title="Unduh Data Calon Penerima Format Excel (.xls)">
                    <i class="bi bi-file-earmark-excel-fill me-1"></i> Ekspor Excel
                </a>

                @can('create', \App\Models\Warga::class)
                    <a href="{{ route('warga.create') }}" class="btn btn-primary shadow-sm px-3 py-2 fw-semibold">
                        <i class="bi bi-person-plus-fill me-1"></i> Tambah Data Terpadu
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Alert Notifikasi Flash -->
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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible show fade shadow-sm mb-4">
            <div class="d-flex align-items-start">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-2 text-danger"></i>
                <div>
                    <strong>Validasi gagal:</strong>
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Kartu Statistik KPI Ringkasan -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 border-start border-primary border-4 h-100 kpi-card">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="kpi-icon bg-light-primary text-primary me-3">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">
                            {{ $isDinas ? 'Total Usulan Masuk' : 'Usulan di Kelurahan' }}
                        </div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $wargas->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        @if($isDinas)
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0 border-start border-info border-4 h-100 kpi-card">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="kpi-icon bg-light-info text-info me-3">
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold">Menunggu Verifikasi</div>
                            <h4 class="fw-bold mb-0 text-dark">
                                {{ $wargas->filter(fn($w) => $w->bantuanAktif !== null && (int) $w->bantuanAktif->status_bantuan === \App\Models\Bantuan::STATUS_DIAJUKAN)->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0 border-start border-success border-4 h-100 kpi-card">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="kpi-icon bg-light-success text-success me-3">
                            <i class="bi bi-house-door-fill fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold">Rumah Milik Sendiri</div>
                            <h4 class="fw-bold mb-0 text-dark">
                                {{ $wargas->filter(fn($w) => $w->Hunian->first()?->status_rumah == 1)->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 border-start border-danger border-4 h-100 kpi-card">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="kpi-icon bg-light-danger text-danger me-3">
                        <i class="bi bi-exclamation-octagon-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Kondisi Rusak Berat</div>
                        <h4 class="fw-bold mb-0 text-dark">
                            {{ $wargas->filter(fn($w) => $w->Hunian->first()?->Rumah->first()?->kondisi_atap == 3 || $w->Hunian->first()?->Rumah->first()?->kondisi_dinding == 3 || $w->Hunian->first()?->Rumah->first()?->kondisi_lantai == 3)->count() }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            @if($isDinas)
                <div class="card shadow-sm border-0 border-start border-warning border-4 h-100 kpi-card">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="kpi-icon bg-light-warning text-warning me-3">
                            <i class="bi bi-arrow-repeat fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold">Perlu Revisi</div>
                            <h4 class="fw-bold mb-0 text-dark">
                                {{ $wargas->filter(fn($w) => $w->bantuanAktif !== null && (int) $w->bantuanAktif->status_bantuan === \App\Models\Bantuan::STATUS_REVISI)->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
            @else
                <div class="card shadow-sm border-0 border-start border-info border-4 h-100 kpi-card">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="kpi-icon bg-light-info text-info me-3">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold">Profil Terverifikasi</div>
                            <h4 class="fw-bold mb-0 text-dark">
                                {{ $wargas->filter(fn($w) => $w->status_update == 1)->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Filter & Pencarian Terpadu -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('warga.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-semibold text-muted mb-1">Cari NIK / Nama / No. KK</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Ketik pencarian..." value="{{ request('search') }}">
                        </div>
                    </div>

                    @if($isDinas)
                        <div class="col-12 col-md-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Filter Kelurahan</label>
                            <select name="id_wilayah" class="form-select form-select-sm">
                                <option value="">Semua Kelurahan</option>
                                @foreach($wilayahs as $wilayah)
                                    <option value="{{ $wilayah->id_wilayah }}" {{ request('id_wilayah') == $wilayah->id_wilayah ? 'selected' : '' }}>
                                        {{ $wilayah->desa }} (Kec. {{ $wilayah->kecamatan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-12 col-md-2">
                        <label class="form-label small fw-semibold text-muted mb-1">Status Usulan</label>
                        <select name="status_bantuan" class="form-select form-select-sm">
                            <option value="">Semua Status View 1</option>
                            <option value="{{ \App\Models\Bantuan::STATUS_DIAJUKAN }}" {{ request('status_bantuan') === (string) \App\Models\Bantuan::STATUS_DIAJUKAN ? 'selected' : '' }}>Menunggu Verifikasi</option>
                            <option value="{{ \App\Models\Bantuan::STATUS_REVISI }}" {{ request('status_bantuan') === (string) \App\Models\Bantuan::STATUS_REVISI ? 'selected' : '' }}>Perlu Revisi</option>
                            <option value="{{ \App\Models\Bantuan::STATUS_DITOLAK }}" {{ request('status_bantuan') === (string) \App\Models\Bantuan::STATUS_DITOLAK ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-{{ $isDinas ? '3' : '2' }}">
                        <label class="form-label small fw-semibold text-muted mb-1">Jenis Bantuan</label>
                        <select name="id_program" class="form-select form-select-sm">
                            <option value="">Semua Jenis</option>
                            @foreach($programs as $prog)
                                <option value="{{ $prog->id_program }}" {{ request('id_program') == $prog->id_program ? 'selected' : '' }}>
                                    {{ $prog->nama_program }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if(!$isDinas)
                        <div class="col-12 col-md-2">
                            <label class="form-label small fw-semibold text-muted mb-1">Status Kepemilikan</label>
                            <select name="status_rumah" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="1" {{ request('status_rumah') == '1' ? 'selected' : '' }}>Milik Sendiri</option>
                                <option value="2" {{ request('status_rumah') == '2' ? 'selected' : '' }}>Sewa / Kontrak</option>
                                <option value="3" {{ request('status_rumah') == '3' ? 'selected' : '' }}>Menumpang</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-2">
                            <label class="form-label small fw-semibold text-muted mb-1">Kondisi Atap</label>
                            <select name="kondisi_atap" class="form-select form-select-sm">
                                <option value="">Semua Kondisi</option>
                                <option value="3" {{ request('kondisi_atap') == '3' ? 'selected' : '' }}>Rusak Berat</option>
                                <option value="2" {{ request('kondisi_atap') == '2' ? 'selected' : '' }}>Rusak Sedang</option>
                                <option value="1" {{ request('kondisi_atap') == '1' ? 'selected' : '' }}>Baik</option>
                            </select>
                        </div>
                    @endif

                    <div class="col-12 col-md-1 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100" title="Terapkan Filter">
                            <i class="bi bi-funnel"></i>
                        </button>
                        @if(request()->hasAny(['search', 'id_wilayah', 'status_bantuan', 'id_program', 'status_rumah', 'kondisi_atap']))
                            <a href="{{ route('warga.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Card Utama -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h5 class="card-title fw-bold text-dark mb-0">
                    Daftar Usulan Calon Penerima RTLH
                </h5>
                <small class="text-muted">
                    @if($isDinas)
                        Menampilkan usulan berstatus <code>Menunggu Verifikasi</code>, <code>Perlu Revisi</code>, dan <code>Ditolak</code> dari seluruh kelurahan. Usulan yang lolos verifikasi lapangan berpindah ke menu <a href="{{ route('spk.index') }}" class="fw-semibold text-decoration-none">Verifikasi Dinas &amp; SPK SAW</a>.
                    @else
                        Menampilkan usulan di wilayah Kelurahan {{ Auth::user()->wilayah->desa ?? '' }} sebelum lolos verifikasi lapangan (<code>Menunggu Verifikasi</code>, <code>Perlu Revisi</code>, <code>Ditolak</code>).
                    @endif
                </small>
            </div>
            <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill fw-bold">
                Total: {{ $wargas->total() }} Data
            </span>
        </div>

        <!-- Toolbar Tab Navigasi Pills (3 Tab View 1) -->
        <div class="bg-light px-3 py-2 border-bottom">
            <ul class="nav nav-pills nav-pills-custom gap-2 mb-0" id="tableTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-master" data-bs-toggle="pill" data-bs-target="#content-master" type="button" role="tab">
                        <i class="bi bi-grid-fill me-1"></i> 1. Ringkasan Usulan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-hunian" data-bs-toggle="pill" data-bs-target="#content-hunian" type="button" role="tab">
                        <i class="bi bi-geo-alt-fill me-1"></i> 2. Kepemilikan Hunian &amp; Lahan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-fisik" data-bs-toggle="pill" data-bs-target="#content-fisik" type="button" role="tab">
                        <i class="bi bi-bricks me-1"></i> 3. Survei Konstruksi &amp; Sanitasi
                    </button>
                </li>
            </ul>
        </div>

        <!-- Isi Tabel Sesuai Tab Terpilih -->
        <div class="card-body p-0">
            <div class="tab-content" id="tableTabsContent">

                <!-- ================= TAB 1: RINGKASAN USULAN (VIEW 1) ================= -->
                <div class="tab-pane fade show active" id="content-master" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 align-middle table-terpadu">
                            <thead>
                                <tr>
                                    <th class="ps-4" style="width: 50px;">No</th>
                                    <th>Calon Penerima (Warga)</th>
                                    <th>Wilayah &amp; Alamat</th>
                                    <th>Kepemilikan Hunian</th>
                                    <th>Kondisi Konstruksi RTLH</th>
                                    <th>Jenis Bantuan</th>
                                    <th>Status Usulan</th>
                                    <th class="text-end pe-4" style="width: 190px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wargas as $index => $warga)
                                    @php
                                        $hunian = $warga->Hunian->first();
                                        $rumah = $hunian?->Rumah->first();
                                        $bantuan = $warga->bantuanAktif;
                                    @endphp
                                    <tr>
                                        <td class="ps-4 fw-semibold text-muted">
                                            {{ $wargas->firstItem() + $index }}
                                        </td>

                                        <td>
                                            <a href="{{ route('warga.show', $warga->id_warga) }}" class="fw-bold text-dark text-decoration-none">
                                                {{ $warga->nama }}
                                            </a>
                                            <small class="text-muted d-block">NIK: {{ $warga->nik }}</small>
                                            <small class="text-muted">KK: {{ $warga->no_kk }}</small>
                                        </td>
                                        <td>
                                            <div class="text-dark fw-semibold">{{ $warga->wilayah->desa ?? '-' }}</div>
                                            <small class="text-muted d-block">
                                                {{ $warga->alamat ?? 'Alamat belum diisi' }} (RT {{ $warga->rt ?? '-' }} / RW {{ $warga->rw ?? '-' }})
                                            </small>
                                            <small class="text-secondary">Kec. {{ $warga->wilayah->kecamatan ?? 'Kediri' }}</small>
                                        </td>
                                        <td>
                                            @if($hunian)
                                                <div>
                                                    @if($hunian->status_rumah == 1)
                                                        <span class="badge bg-light-success text-success fw-semibold">Milik Sendiri</span>
                                                    @elseif($hunian->status_rumah == 2)
                                                        <span class="badge bg-light-warning text-dark fw-semibold">Sewa / Kontrak</span>
                                                    @elseif($hunian->status_rumah == 3)
                                                        <span class="badge bg-light-secondary text-dark fw-semibold">Menumpang</span>
                                                    @else
                                                        <span class="badge bg-light text-muted">Belum disurvei</span>
                                                    @endif
                                                </div>
                                                <small class="text-muted d-block mt-1">
                                                    Luas: {{ $hunian->luas_rumah ?? '-' }} m&sup2; | {{ $hunian->jml_penghuni ?? 1 }} Jiwa
                                                </small>
                                            @else
                                                <span class="badge bg-light text-muted">Data Hunian Kosong</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($rumah)
                                                <div class="d-flex flex-wrap gap-1">
                                                    @if($rumah->kondisi_atap == 3)
                                                        <span class="badge bg-danger">Atap: Rusak</span>
                                                    @elseif($rumah->kondisi_atap == 2)
                                                        <span class="badge bg-warning text-dark">Atap: Sedang</span>
                                                    @elseif($rumah->kondisi_atap == 1)
                                                        <span class="badge bg-success">Atap: Baik</span>
                                                    @endif

                                                    @if($rumah->kondisi_dinding == 3)
                                                        <span class="badge bg-danger">Dinding: Rusak</span>
                                                    @elseif($rumah->kondisi_dinding == 2)
                                                        <span class="badge bg-warning text-dark">Dinding: Sedang</span>
                                                    @elseif($rumah->kondisi_dinding == 1)
                                                        <span class="badge bg-success">Dinding: Baik</span>
                                                    @endif

                                                    @if($rumah->kondisi_lantai == 3)
                                                        <span class="badge bg-danger">Lantai: Rusak</span>
                                                    @elseif($rumah->kondisi_lantai == 2)
                                                        <span class="badge bg-warning text-dark">Lantai: Sedang</span>
                                                    @elseif($rumah->kondisi_lantai == 1)
                                                        <span class="badge bg-success">Lantai: Baik</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="badge bg-light text-muted">Belum Disurvei</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($bantuan && $bantuan->program)
                                                <span class="badge bg-light-primary text-primary fw-semibold px-2 py-1 border border-primary-subtle">
                                                    <i class="bi bi-gift-fill me-1"></i>{{ $bantuan->program->nama_program }}
                                                </span>
                                                @if($bantuan->program->jenis_bantuan)
                                                    <small class="text-muted d-block mt-1 text-capitalize" style="font-size: 0.72rem;">
                                                        <i class="bi bi-tag me-1"></i>{{ str_replace('_', ' ', $bantuan->program->jenis_bantuan) }}
                                                    </small>
                                                @endif
                                            @else
                                                <span class="badge bg-light text-muted">Belum Ditentukan</span>
                                            @endif
                                        </td>
                                        <td>
                                            @include('pages.warga.partials._status_badge', [
                                                'bantuan' => $bantuan,
                                                'showVerifikator' => true,
                                                'showCatatan' => true,
                                            ])
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('warga.show', $warga->id_warga) }}" class="btn btn-light-primary" title="Detail Lengkap">
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                                @if($isDinas && $bantuan)
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVerifikasiWarga{{ $bantuan->id_bantuan }}" title="Verifikasi Lapangan">
                                                        <i class="bi bi-clipboard-check me-1"></i>Verifikasi
                                                    </button>
                                                @endif

                                                @can('update', $warga)
                                                    <a href="{{ route('warga.edit', $warga->id_warga) }}" class="btn btn-light-warning" title="Edit Data">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                @endcan

                                                @can('delete', $warga)
                                                    <form action="{{ route('warga.destroy', $warga->id_warga) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh data terpadu warga ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-light-danger" title="Hapus Data">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="py-3">
                                                <div class="avatar bg-light-primary text-primary mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px;">
                                                    <i class="bi bi-people fs-2"></i>
                                                </div>
                                                <h6 class="fw-bold text-dark mb-1">Belum ada data usulan di daftar antrean awal</h6>
                                                <p class="text-muted small mb-3">Belum ada usulan berstatus Menunggu Verifikasi, Perlu Revisi, atau Ditolak yang sesuai dengan filter yang dipilih.</p>
                                                @can('create', \App\Models\Warga::class)
                                                    <a href="{{ route('warga.create') }}" class="btn btn-sm btn-primary px-3 shadow-sm">
                                                        <i class="bi bi-plus-lg me-1"></i> Tambah Calon Penerima Baru
                                                    </a>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @include('pages.warga.partials._tabs_survei', ['wargas' => $wargas])

            </div>

            <!-- Pagination Footer -->
            @if($wargas->hasPages())
                <div class="card-footer bg-white border-0 py-3 d-flex flex-wrap justify-content-between align-items-center">
                    <small class="text-muted">
                        Menampilkan <strong>{{ $wargas->firstItem() }}</strong> - <strong>{{ $wargas->lastItem() }}</strong> dari <strong>{{ $wargas->total() }}</strong> calon penerima
                    </small>
                    <div>
                        {{ $wargas->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Verifikasi Lapangan (Khusus Dinas & Superadmin untuk meloloskan status 0/3 ke status 1) -->
@if($isDinas)
    @foreach($wargas as $warga)
        @php
            $bantuan = $warga->bantuanAktif;
        @endphp
        @if($bantuan)
            @include('pages.warga.partials._modal_verifikasi', [
                'bantuan' => $bantuan,
                'warga' => $warga,
                'allowedStatuses' => [
                    \App\Models\Bantuan::STATUS_DIVERIFIKASI,
                    \App\Models\Bantuan::STATUS_REVISI,
                    \App\Models\Bantuan::STATUS_DITOLAK,
                ],
                'modalTitle' => 'Verifikasi Lapangan',
                'showSawBox' => false,
            ])
        @endif
    @endforeach
@endif
@endsection
