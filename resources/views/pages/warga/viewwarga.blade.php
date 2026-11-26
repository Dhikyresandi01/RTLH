@extends('app')

@php
    $isDinas = Auth::user()->isSuperadmin() || Auth::user()->isAdminDinas();
@endphp

@section('title', 'Daftar Usulan Calon Penerima RTLH')

@section('content')
<!-- Google Fonts & Material Symbols for Stitch Modern Table Design -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=Spline+Sans+Mono:wght@400;500;600&family=Wix+Madefor+Display:wght@600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">

<style>
    :root {
        --stitch-primary: #001D4E;
        --stitch-primary-hover: #16336B;
        --stitch-surface: #F8FAFC;
        --stitch-border: #CBD5E1;
        --stitch-info-bg: #DBEAFE;
        --stitch-info-text: #1E40AF;
        --stitch-success-bg: #DCFCE7;
        --stitch-success-text: #166534;
        --stitch-warning-bg: #FEF3C7;
        --stitch-warning-text: #92400E;
        --stitch-error-bg: #FFDAD6;
        --stitch-error-text: #93000A;
    }

    .font-mono-num {
        font-family: 'Spline Sans Mono', monospace !important;
        font-variant-numeric: tabular-nums;
        font-size: 11.5px;
    }

    .font-headline {
        font-family: 'Wix Madefor Display', sans-serif !important;
    }

    .font-stitch {
        font-family: 'Instrument Sans', sans-serif !important;
    }

    .stitch-header-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 9999px;
        background: #FFFFFF;
        border: 1px solid var(--stitch-border);
        color: var(--stitch-primary);
        font-weight: 600;
        font-size: 13px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }

    .stitch-header-badge .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #1D4ED8;
    }

    /* Stitch Nav Tabs */
    .stitch-tablist {
        display: inline-flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px;
        padding: 5px;
        border-radius: 10px;
        background: #FFFFFF;
        border: 1px solid var(--stitch-border);
    }

    .stitch-tab-trigger {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 16px;
        border-radius: 6px;
        font-family: 'Instrument Sans', sans-serif;
        font-weight: 600;
        font-size: 13px;
        color: #475569;
        background: transparent;
        border: none;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .stitch-tab-trigger:hover {
        background: #F1F5F9;
        color: #0F172A;
    }

    .stitch-tab-trigger.active {
        background: var(--stitch-primary) !important;
        color: #FFFFFF !important;
        box-shadow: 0 2px 6px rgba(0, 29, 78, 0.25);
    }

    .stitch-tab-trigger .material-symbols-outlined {
        font-size: 18px;
        line-height: 1;
        vertical-align: middle;
    }

    /* Stitch Table Card & Header */
    .stitch-card {
        background: #FFFFFF;
        border: 1px solid var(--stitch-border);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04);
    }

    .stitch-toolbar {
        padding: 12px 18px;
        background: #FFFFFF;
        border-bottom: 1px solid var(--stitch-border);
    }

    .stitch-table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Instrument Sans', sans-serif;
    }

    .stitch-table thead th {
        background: #F8FAFC !important;
        border-bottom: 1px solid var(--stitch-border) !important;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 11px 14px !important;
        white-space: nowrap;
    }

    .stitch-table tbody tr {
        transition: background-color 0.12s ease;
        border-bottom: 1px solid #E2E8F0;
    }

    .stitch-table tbody tr:hover {
        background-color: #F8FAFC !important;
    }

    .stitch-table tbody td {
        padding: 12px 14px !important;
        vertical-align: top;
        font-size: 13px;
    }

    /* Micro indicators for structure */
    .micro-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 2px 7px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        line-height: 1.3;
        width: fit-content;
    }

    .micro-pill .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }

    .micro-pill-danger {
        background-color: var(--stitch-error-bg);
        color: var(--stitch-error-text);
    }
    .micro-pill-danger .dot { background-color: #BA1A1A; }

    .micro-pill-success {
        background-color: var(--stitch-success-bg);
        color: var(--stitch-success-text);
    }
    .micro-pill-success .dot { background-color: #15803D; }

    .micro-pill-warning {
        background-color: var(--stitch-warning-bg);
        color: var(--stitch-warning-text);
    }
    .micro-pill-warning .dot { background-color: #B45309; }

    /* Action icon buttons */
    .stitch-btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--stitch-border);
        background: #F8FAFC;
        color: #475569;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .stitch-btn-icon:hover {
        background: #EFF6FF;
        color: #1D4ED8;
        border-color: #93C5FD;
    }

    .stitch-btn-verify {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 6px;
        background: var(--stitch-primary);
        color: #FFFFFF !important;
        font-size: 12px;
        font-weight: 600;
        border: none;
        transition: background-color 0.15s ease;
        text-decoration: none;
    }

    .stitch-btn-verify:hover {
        background: var(--stitch-primary-hover);
    }

    .stitch-btn-edit {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--stitch-warning-bg);
        color: var(--stitch-warning-text) !important;
        border: 1px solid #FDE68A;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .stitch-btn-edit:hover {
        background: #F59E0B;
        color: #FFFFFF !important;
    }

    .stitch-btn-delete {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--stitch-error-bg);
        color: var(--stitch-error-text) !important;
        border: 1px solid #FECACA;
        transition: all 0.15s ease;
    }

    .stitch-btn-delete:hover {
        background: #DC2626;
        color: #FFFFFF !important;
    }

    .material-symbols-outlined {
        display: inline-block;
        width: 1em;
        height: 1em;
        line-height: 1 !important;
        vertical-align: middle;
    }
</style>

<div class="page-heading font-stitch mb-4">
    <!-- Header Halaman Versi Stitch -->
    <div class="d-flex flex-column flex-md-row align-items-md-start justify-content-between gap-3 mb-4">
        <div class="max-w-3xl">
            <h1 class="font-headline fw-bold mb-1 text-dark" style="font-size: 24px; letter-spacing: -0.25px;">
                Daftar Usulan Calon Penerima RTLH
            </h1>
            <p class="text-muted mb-0" style="font-size: 13px; line-height: 1.5;">
                @if($isDinas)
                    Menampilkan usulan berstatus <code>Menunggu Verifikasi</code>, <code>Perlu Revisi</code>, dan <code>Ditolak</code> dari seluruh kelurahan. Usulan yang lolos verifikasi lapangan berpindah ke menu <a href="{{ route('spk.index') }}" class="fw-semibold text-decoration-none">Verifikasi Dinas &amp; SPK SAW</a>.
                @else
                    Pusat data usulan terpadu kependudukan warga, kepemilikan hunian, dan survei fisik bangunan wilayah <strong>{{ Auth::user()->wilayah->desa ?? 'Kelurahan' }}</strong>.
                @endif
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2 shrink-0">
            <!-- Numeric Badge -->
            <span class="stitch-header-badge">
                <span class="dot"></span>
                <span>Total: {{ $wargas->total() }} Data</span>
            </span>

            <!-- Tombol Ekspor Excel -->
            <a href="{{ route('warga.export', request()->query()) }}" class="btn btn-sm btn-outline-success px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1 shadow-sm" title="Unduh Data Calon Penerima Format Excel (.xls)">
                <span class="material-symbols-outlined" style="font-size: 16px;">download</span>
                <span>Ekspor Excel</span>
            </a>

            @can('create', \App\Models\Warga::class)
                <a href="{{ route('warga.create') }}" class="btn btn-sm btn-primary px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1 shadow-sm">
                    <span class="material-symbols-outlined" style="font-size: 16px;">person_add</span>
                    <span>Tambah Data</span>
                </a>
            @endcan
        </div>
    </div>

    <!-- Alert Flash -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade shadow-sm mb-3">
            <div class="d-flex align-items-center">
                <span class="material-symbols-outlined me-2 fs-5">check_circle</span>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible show fade shadow-sm mb-3">
            <div class="d-flex align-items-center">
                <span class="material-symbols-outlined me-2 fs-5">warning</span>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tabs Navigasi dengan Ikon (Stitch Tabs Component) -->
    <div class="mb-3">
        <div class="stitch-tablist" role="tablist">
            <button class="stitch-tab-trigger active" id="tab-master" data-bs-toggle="pill" data-bs-target="#content-master" type="button" role="tab">
                <span class="material-symbols-outlined">grid_view</span>
                <span>1. Ringkasan Usulan</span>
            </button>
            <button class="stitch-tab-trigger" id="tab-hunian" data-bs-toggle="pill" data-bs-target="#content-hunian" type="button" role="tab">
                <span class="material-symbols-outlined">location_on</span>
                <span>2. Kepemilikan Hunian &amp; Lahan</span>
            </button>
            <button class="stitch-tab-trigger" id="tab-fisik" data-bs-toggle="pill" data-bs-target="#content-fisik" type="button" role="tab">
                <span class="material-symbols-outlined">construction</span>
                <span>3. Survei Konstruksi &amp; Sanitasi</span>
            </button>
        </div>
    </div>

    <!-- Kontainer Kartu Tabel Utama -->
    <div class="stitch-card">
        <!-- Table Toolbar: Search & Filters -->
        <div class="stitch-toolbar">
            <form method="GET" action="{{ route('warga.index') }}" class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <!-- Search Input -->
                <div class="position-relative" style="min-width: 260px; max-width: 380px; flex: 1;">
                    <span class="material-symbols-outlined position-absolute top-50 start-0 translate-middle-y ms-3 text-muted" style="font-size: 18px; pointer-events: none;">search</span>
                    <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama warga, NIK, atau kelurahan..." class="form-control form-control-sm ps-5 bg-light border-secondary-subtle font-stitch" style="font-size: 13px;">
                </div>

                <!-- Dropdown Filters -->
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <!-- Status Filter -->
                    <select name="status_bantuan" class="form-select form-select-sm font-stitch" style="width: auto; font-size: 12.5px;" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="{{ \App\Models\Bantuan::STATUS_DIAJUKAN }}" {{ request('status_bantuan') === (string) \App\Models\Bantuan::STATUS_DIAJUKAN ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="{{ \App\Models\Bantuan::STATUS_REVISI }}" {{ request('status_bantuan') === (string) \App\Models\Bantuan::STATUS_REVISI ? 'selected' : '' }}>Perlu Revisi</option>
                        <option value="{{ \App\Models\Bantuan::STATUS_DITOLAK }}" {{ request('status_bantuan') === (string) \App\Models\Bantuan::STATUS_DITOLAK ? 'selected' : '' }}>Ditolak</option>
                    </select>

                    <!-- Kelurahan Filter (Khusus Dinas) -->
                    @if($isDinas)
                        <select name="id_wilayah" class="form-select form-select-sm font-stitch" style="width: auto; font-size: 12.5px;" onchange="this.form.submit()">
                            <option value="">Semua Kelurahan</option>
                            @foreach($wilayahs as $wilayah)
                                <option value="{{ $wilayah->id_wilayah }}" {{ request('id_wilayah') == $wilayah->id_wilayah ? 'selected' : '' }}>
                                    Kel. {{ $wilayah->desa }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    <!-- Jenis Bantuan Filter -->
                    <select name="id_program" class="form-select form-select-sm font-stitch" style="width: auto; font-size: 12.5px;" onchange="this.form.submit()">
                        <option value="">Semua Jenis Bantuan</option>
                        @foreach($programs as $prog)
                            <option value="{{ $prog->id_program }}" {{ request('id_program') == $prog->id_program ? 'selected' : '' }}>
                                {{ $prog->nama_program }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-sm btn-primary px-3 fw-semibold d-inline-flex align-items-center gap-1" style="font-size: 12.5px;">
                        <span class="material-symbols-outlined" style="font-size: 15px;">filter_list</span>
                        <span>Filter</span>
                    </button>

                    @if(request()->hasAny(['search', 'id_wilayah', 'status_bantuan', 'id_program']))
                        <a href="{{ route('warga.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                            <span class="material-symbols-outlined" style="font-size: 15px;">restart_alt</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Konten 3 Tab Tabel -->
        <div class="tab-content" id="tableTabsContent">

            <!-- ================= TAB 1: RINGKASAN USULAN ================= -->
            <div class="tab-pane fade show active" id="content-master" role="tabpanel">
                <div class="table-responsive">
                    <table class="stitch-table">
                        <thead>
                            <tr>
                                <th class="ps-3" style="width: 48px;">No</th>
                                <th>Calon Penerima</th>
                                <th>Wilayah</th>
                                <th>Hunian</th>
                                <th>Kondisi</th>
                                <th>Bantuan</th>
                                <th>Status</th>
                                <th class="text-end pe-3" style="width: 170px;">Aksi</th>
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
                                    <td class="ps-3 font-mono-num text-muted">
                                        {{ $wargas->firstItem() + $index }}
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <a href="{{ route('warga.show', $warga->id_warga) }}" class="fw-bold text-dark text-decoration-none" style="font-size: 13.5px;">
                                                {{ $warga->nama }}
                                            </a>
                                            <span class="font-mono-num text-muted">NIK: {{ $warga->nik }}</span>
                                            <span class="font-mono-num text-muted">KK: {{ $warga->no_kk }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-0.5" style="max-width: 220px;">
                                            <span class="fw-bold text-dark" style="font-size: 13px;">{{ $warga->wilayah->desa ?? '-' }}</span>
                                            <span class="text-muted" style="font-size: 12px; line-height: 1.3;">{{ $warga->alamat ?? 'Alamat belum diisi' }} (RT {{ $warga->rt ?? '-' }}/RW {{ $warga->rw ?? '-' }})</span>
                                            <span class="text-muted" style="font-size: 11.5px;">Kec. {{ $warga->wilayah->kecamatan ?? 'Kediri' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column align-items-start gap-1">
                                            @if($hunian)
                                                @if($hunian->status_rumah == 1)
                                                    <span class="badge" style="background: var(--stitch-success-bg); color: var(--stitch-success-text); font-size: 11px; font-weight: 600;">Milik Sendiri</span>
                                                @elseif($hunian->status_rumah == 2)
                                                    <span class="badge" style="background: var(--stitch-warning-bg); color: var(--stitch-warning-text); font-size: 11px; font-weight: 600;">Sewa / Kontrak</span>
                                                @elseif($hunian->status_rumah == 3)
                                                    <span class="badge" style="background: #E2E8F0; color: #334155; font-size: 11px; font-weight: 600;">Menumpang</span>
                                                @else
                                                    <span class="badge bg-light text-muted">Belum disurvei</span>
                                                @endif
                                                <span class="text-muted font-mono-num">{{ $hunian->luas_rumah ?? '-' }} m² · {{ $hunian->jml_penghuni ?? 1 }} Jiwa</span>
                                            @else
                                                <span class="badge bg-light text-muted">Data Kosong</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($rumah)
                                            <div class="d-flex flex-column gap-1">
                                                <!-- Atap -->
                                                @if($rumah->kondisi_atap == 3)
                                                    <span class="micro-pill micro-pill-danger"><span class="dot"></span>Atap: Rusak</span>
                                                @elseif($rumah->kondisi_atap == 2)
                                                    <span class="micro-pill micro-pill-warning"><span class="dot"></span>Atap: Sedang</span>
                                                @elseif($rumah->kondisi_atap == 1)
                                                    <span class="micro-pill micro-pill-success"><span class="dot"></span>Atap: Baik</span>
                                                @endif

                                                <!-- Dinding -->
                                                @if($rumah->kondisi_dinding == 3)
                                                    <span class="micro-pill micro-pill-danger"><span class="dot"></span>Dinding: Rusak</span>
                                                @elseif($rumah->kondisi_dinding == 2)
                                                    <span class="micro-pill micro-pill-warning"><span class="dot"></span>Dinding: Sedang</span>
                                                @elseif($rumah->kondisi_dinding == 1)
                                                    <span class="micro-pill micro-pill-success"><span class="dot"></span>Dinding: Baik</span>
                                                @endif

                                                <!-- Lantai -->
                                                @if($rumah->kondisi_lantai == 3)
                                                    <span class="micro-pill micro-pill-danger"><span class="dot"></span>Lantai: Rusak</span>
                                                @elseif($rumah->kondisi_lantai == 2)
                                                    <span class="micro-pill micro-pill-warning"><span class="dot"></span>Lantai: Sedang</span>
                                                @elseif($rumah->kondisi_lantai == 1)
                                                    <span class="micro-pill micro-pill-success"><span class="dot"></span>Lantai: Baik</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="badge bg-light text-muted">Belum Disurvei</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($bantuan && $bantuan->program)
                                            <div class="d-flex flex-column align-items-start gap-1">
                                                <span class="badge rounded-pill" style="background: var(--stitch-info-bg); color: var(--stitch-info-text); font-size: 11.5px; font-weight: 600;">
                                                    <span class="material-symbols-outlined me-1" style="font-size: 13px; vertical-align: middle;">card_giftcard</span>{{ $bantuan->program->nama_program }}
                                                </span>
                                                @if($bantuan->program->jenis_bantuan)
                                                    <span class="text-muted text-capitalize" style="font-size: 11px;">
                                                        {{ str_replace('_', ' ', $bantuan->program->jenis_bantuan) }}
                                                    </span>
                                                @endif
                                            </div>
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
                                    <td class="text-end pe-3">
                                        <div class="d-inline-flex align-items-center justify-content-end gap-1">
                                            <!-- Detail -->
                                            <a href="{{ route('warga.show', $warga->id_warga) }}" class="stitch-btn-icon" title="Lihat Detail {{ $warga->nama }}">
                                                <span class="material-symbols-outlined" style="font-size: 17px;">visibility</span>
                                            </a>

                                            <!-- Verifikasi Lapangan (Dinas) -->
                                            @if($isDinas && $bantuan && in_array((int)$bantuan->status_bantuan, [\App\Models\Bantuan::STATUS_DIAJUKAN, \App\Models\Bantuan::STATUS_REVISI]))
                                                <button type="button" class="stitch-btn-verify" data-bs-toggle="modal" data-bs-target="#modalVerifikasiWarga{{ $bantuan->id_bantuan }}" title="Verifikasi Lapangan">
                                                    <span class="material-symbols-outlined" style="font-size: 15px;">fact_check</span>
                                                    <span>Verifikasi</span>
                                                </button>
                                            @endif

                                            <!-- Edit -->
                                            @can('update', $warga)
                                                <a href="{{ route('warga.edit', $warga->id_warga) }}" class="stitch-btn-edit" title="Edit Data {{ $warga->nama }}">
                                                    <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                                                </a>
                                            @endcan

                                            <!-- Hapus -->
                                            @can('delete', $warga)
                                                <form action="{{ route('warga.destroy', $warga->id_warga) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh data terpadu warga ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="stitch-btn-delete" title="Hapus Data">
                                                        <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="py-4">
                                            <span class="material-symbols-outlined text-muted mb-2" style="font-size: 44px;">person_search</span>
                                            <h6 class="fw-bold text-dark mb-1 font-headline">Tidak ada data usulan yang cocok</h6>
                                            <p class="text-muted small mb-3">Coba ubah kata kunci pencarian atau pilih filter status dan kelurahan lain.</p>
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

            <!-- Tab 2 & Tab 3: Kepemilikan & Survei Fisik -->
            @include('pages.warga.partials._tabs_survei', ['wargas' => $wargas])

        </div>

        <!-- Pagination Footer Versi Stitch -->
        @if($wargas->hasPages())
            <div class="p-3 bg-light border-top d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                <span class="font-stitch text-muted font-mono-num" style="font-size: 12.5px;">
                    Menampilkan <strong>{{ $wargas->firstItem() }}</strong>–<strong>{{ $wargas->lastItem() }}</strong> dari <strong>{{ $wargas->total() }}</strong> calon penerima
                </span>
                <div>
                    {{ $wargas->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal Verifikasi Lapangan (Khusus Dinas & Superadmin) -->
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
