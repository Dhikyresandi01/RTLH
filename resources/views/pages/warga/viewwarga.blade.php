@extends('app')

@section('title', 'Data Terpadu Calon Penerima RTLH')

@section('content')
@php
    $isDinas = Auth::user()->isSuperadmin() || Auth::user()->isAdminDinas();
@endphp

<style>
    /* Styling khusus perapian tampilan Data Terpadu RTLH */
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
    .kpi-icon i::before{
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
    .nav-pills-custom .nav-link i::before{
        vertical-align: middle;
        line-height: 1;
    }
    .nav-pills-custom .nav-link:hover {
        background-color: #f1f3f9;
        color: #435ebe;
        border-color: #c7d2fe;
    }
    .nav-pills-custom .nav-link.active {
        background-color: #435ebe !important;
        color: #ffffff !important;
        border-color: #435ebe !important;
        box-shadow: 0 4px 12px rgba(67, 94, 190, 0.25);
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
            <div class="col-12 col-md-7">
                <h3 class="fw-bold mb-1 text-primary">
                    <i class="bi bi-people-fill me-2"></i>Data Terpadu Calon Penerima RTLH
                </h3>
                @if($isDinas)
                    <p class="text-muted mb-0">Pusat data terintegrasi: identitas warga, survei fisik, <strong>perankingan otomatis SPK SAW</strong>, serta penetapan verifikasi Dinas.</p>
                @else
                    <p class="text-muted mb-0">Pusat data usulan terpadu kependudukan warga, kepemilikan hunian, dan survei fisik bangunan wilayah <strong>{{ Auth::user()->wilayah->desa ?? 'Kelurahan' }}</strong>.</p>
                @endif
            </div>
            <div class="col-12 col-md-5 text-md-end mt-3 mt-md-0 d-flex flex-wrap justify-content-md-end gap-2">
                @if($isDinas)
                    <!-- Tombol Hitung Ulang SAW Khusus Dinas -->
                    <form action="{{ route('spk.hitung') }}" method="POST" class="d-inline" onsubmit="return confirm('Jalankan kalkulasi ulang perankingan SAW untuk seluruh data tahun ini?');">
                        @csrf
                        <input type="hidden" name="tahun" value="{{ date('Y') }}">
                        <button type="submit" class="btn btn-outline-warning text-dark shadow-sm px-3 py-2 fw-semibold" title="Perbarui Peringkat SAW">
                            <i class="bi bi-arrow-repeat me-1"></i> Hitung Ulang SAW
                        </button>
                    </form>
                @endif

                <!-- Tombol Ekspor Excel Terpadu -->
                <a href="{{ route('warga.export', request()->query()) }}" class="btn btn-success shadow-sm px-3 py-2 fw-semibold" title="Unduh Data Calon Penerima Format Excel (.xls)">
                    <i class="bi bi-file-earmark-excel-fill me-1"></i> Ekspor Excel
                </a>

                <a href="{{ route('warga.create') }}" class="btn btn-primary shadow-sm px-3 py-2 fw-semibold">
                    <i class="bi bi-person-plus-fill me-1"></i> Tambah Data Terpadu
                </a>
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

    <!-- Kartu Statistik KPI Ringkasan Data -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 border-start border-primary border-4 h-100 kpi-card">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="kpi-icon bg-light-primary text-primary me-3">
                        <i class="bi bi-people-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">
                            {{ $isDinas ? 'Total Calon Penerima' : 'Calon Terdata di Kelurahan' }}
                        </div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $wargas->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        @if($isDinas)
            <!-- Kartu Khusus Dinas: SAW & Verifikasi Dinas -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0 border-start border-warning border-4 h-100 kpi-card">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="kpi-icon bg-light-warning text-dark me-3">
                            <i class="bi bi-award-fill fs-4 text-warning"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold">Sudah Diranking SAW</div>
                            <h4 class="fw-bold mb-0 text-dark">
                                {{ $wargas->filter(fn($w) => $w->bantuanAktif?->spkRanking !== null)->count() }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Kartu Khusus Kelurahan: Rumah Milik Sendiri -->
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
                <div class="card shadow-sm border-0 border-start border-success border-4 h-100 kpi-card">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="kpi-icon bg-light-success text-success me-3">
                            <i class="bi bi-check-circle-fill fs-4"></i>
                        </div>
                        <div>
                            <div class="text-muted small fw-semibold">Disetujui Dinas</div>
                            <h4 class="fw-bold mb-0 text-success">
                                {{ $wargas->filter(fn($w) => $w->bantuanAktif?->status_bantuan == \App\Models\Bantuan::STATUS_DISETUJUI)->count() }}
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
            <form method="GET" action="{{ route('warga') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-semibold text-muted mb-1">Cari NIK / Nama / No. KK</label>
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
                                @foreach($wilayahs as $wilayah)
                                    <option value="{{ $wilayah->id_wilayah }}" {{ request('id_wilayah') == $wilayah->id_wilayah ? 'selected' : '' }}>
                                        {{ $wilayah->desa }} (Kec. {{ $wilayah->kecamatan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-2">
                            <label class="form-label small fw-semibold text-muted mb-1">Status Verifikasi</label>
                            <select name="status_bantuan" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="0" {{ request('status_bantuan') === '0' ? 'selected' : '' }}>Diajukan</option>
                                <option value="1" {{ request('status_bantuan') === '1' ? 'selected' : '' }}>Diverifikasi Lapangan</option>
                                <option value="2" {{ request('status_bantuan') === '2' ? 'selected' : '' }}>Disetujui Dinas</option>
                                <option value="3" {{ request('status_bantuan') === '3' ? 'selected' : '' }}>Perlu Revisi</option>
                                <option value="4" {{ request('status_bantuan') === '4' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <div class="col-12 col-md-2">
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

                        <div class="col-12 col-md-2">
                            <label class="form-label small fw-semibold text-muted mb-1">Urutan Data</label>
                            <select name="sort" class="form-select form-select-sm">
                                <option value="rank_asc" {{ request('sort') == 'rank_asc' ? 'selected' : '' }}>🥇 Prioritas SAW Tertinggi</option>
                                <option value="latest" {{ request('sort') == 'latest' || !request('sort') ? 'selected' : '' }}>🕒 Paling Baru Diinput</option>
                            </select>
                        </div>
                    @else
                        <!-- Filter Sederhana Khusus Petugas Kelurahan -->
                        <div class="col-12 col-md-2">
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
                            <label class="form-label small fw-semibold text-muted mb-1">Kondisi Atap Rumah</label>
                            <select name="kondisi_atap" class="form-select form-select-sm">
                                <option value="">Semua Kondisi</option>
                                <option value="3" {{ request('kondisi_atap') == '3' ? 'selected' : '' }}>Rusak Berat</option>
                                <option value="2" {{ request('kondisi_atap') == '2' ? 'selected' : '' }}>Rusak Sedang</option>
                                <option value="1" {{ request('kondisi_atap') == '1' ? 'selected' : '' }}>Baik</option>
                            </select>
                        </div>
                    @endif

                    <div class="col-12 col-md-{{ $isDinas ? '1' : '3' }} d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100" title="Terapkan Filter">
                            <i class="bi bi-funnel"></i>
                        </button>
                        @if(request()->hasAny(['search', 'id_wilayah', 'status_bantuan', 'id_program', 'sort', 'status_rumah', 'kondisi_atap']))
                            <a href="{{ route('warga') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Card Utama: Tabel Terpadu RTLH -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h5 class="card-title fw-bold text-dark mb-0">Daftar Terpadu Calon Penerima RTLH</h5>
                <small class="text-muted">
                    @if($isDinas)
                        Data diperingkat menggunakan metode Simple Additive Weighting (SAW) untuk penentuan prioritas bantuan dinas.
                    @else
                        Daftar usulan calon penerima bantuan RTLH di wilayah Kelurahan {{ Auth::user()->wilayah->desa ?? '' }}.
                    @endif
                </small>
            </div>
            <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill fw-bold">
                Total: {{ $wargas->total() }} Data
            </span>
        </div>

        <!-- Toolbar Tab Navigasi Pills Mandiri -->
        <div class="bg-light px-3 py-2 border-bottom">
            <ul class="nav nav-pills nav-pills-custom gap-2 mb-0" id="tableTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-master" data-bs-toggle="pill" data-bs-target="#content-master" type="button" role="tab">
                        <i class="bi bi-grid-fill me-1"></i> 1. Ringkasan Terpadu
                    </button>
                </li>
                @if($isDinas)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-spk" data-bs-toggle="pill" data-bs-target="#content-spk" type="button" role="tab">
                            <i class="bi bi-award-fill me-1 text-warning"></i> 2. Prioritas SAW & Verifikasi Dinas
                        </button>
                    </li>
                @endif
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-hunian" data-bs-toggle="pill" data-bs-target="#content-hunian" type="button" role="tab">
                        <i class="bi bi-geo-alt-fill me-1"></i> {{ $isDinas ? '3' : '2' }}. Kepemilikan Hunian & Lahan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-fisik" data-bs-toggle="pill" data-bs-target="#content-fisik" type="button" role="tab">
                        <i class="bi bi-bricks me-1"></i> {{ $isDinas ? '4' : '3' }}. Survei Konstruksi & Sanitasi
                    </button>
                </li>
            </ul>
        </div>

        <!-- Isi Tabel Sesuai Tab Terpilih -->
        <div class="card-body p-0">
            <div class="tab-content" id="tableTabsContent">

                <!-- ================= TAB 1: RINGKASAN TERPADU ================= -->
                <div class="tab-pane fade show active" id="content-master" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 align-middle table-terpadu">
                            <thead>
                                <tr>
                                    <th class="ps-4" style="width: 50px;">No</th>
                                    @if($isDinas)
                                        <th>Prioritas SAW</th>
                                    @endif
                                    <th>Calon Penerima (Warga)</th>
                                    <th>Wilayah & Alamat</th>
                                    <th>Kepemilikan Hunian</th>
                                    <th>Kondisi Konstruksi RTLH</th>
                                    <th>Jenis Bantuan</th>
                                    <th>Status Usulan</th>
                                    <th class="text-end pe-4" style="width: 140px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wargas as $index => $warga)
                                    @php
                                        $hunian = $warga->Hunian->first();
                                        $rumah = $hunian?->Rumah->first();
                                        $bantuan = $warga->bantuanAktif;
                                        $ranking = $bantuan?->spkRanking;
                                    @endphp
                                    <tr>
                                        <td class="ps-4 fw-semibold text-muted">
                                            {{ $wargas->firstItem() + $index }}
                                        </td>

                                        <!-- Prioritas SAW (HANYA DITAMPILKAN UNTUK DINAS) -->
                                        @if($isDinas)
                                            <td>
                                                @if($ranking)
                                                    @if($ranking->ranking == 1)
                                                        <span class="badge bg-warning text-dark fw-bold px-2 py-1 shadow-sm">🥇 #1 ({{ number_format($ranking->skor_akhir, 3) }})</span>
                                                    @elseif($ranking->ranking == 2)
                                                        <span class="badge bg-secondary text-white fw-bold px-2 py-1 shadow-sm">🥈 #2 ({{ number_format($ranking->skor_akhir, 3) }})</span>
                                                    @elseif($ranking->ranking == 3)
                                                        <span class="badge bg-danger text-white fw-bold px-2 py-1 shadow-sm">🥉 #3 ({{ number_format($ranking->skor_akhir, 3) }})</span>
                                                    @else
                                                        <span class="badge bg-light text-primary border fw-bold px-2 py-1">
                                                            #{{ $ranking->ranking }} ({{ number_format($ranking->skor_akhir, 3) }})
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="badge bg-light text-muted">Belum Dirangking</span>
                                                @endif
                                            </td>
                                        @endif

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
                                                    Luas: {{ $hunian->luas_rumah ?? '-' }} m² | {{ $hunian->jml_penghuni ?? 1 }} Jiwa
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
                                            @if($bantuan)
                                                @if($bantuan->status_bantuan == \App\Models\Bantuan::STATUS_DISETUJUI)
                                                    <span class="badge bg-success"><i class="bi bi-check2-circle me-1"></i>Disetujui Dinas</span>
                                                @elseif($bantuan->status_bantuan == \App\Models\Bantuan::STATUS_REVISI)
                                                    <span class="badge bg-warning text-dark"><i class="bi bi-arrow-repeat me-1"></i>Perlu Revisi</span>
                                                @elseif($bantuan->status_bantuan == \App\Models\Bantuan::STATUS_DITOLAK)
                                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Ditolak Dinas</span>
                                                @else
                                                    <span class="badge bg-light-warning text-dark"><i class="bi bi-clock me-1"></i>Menunggu Verifikasi</span>
                                                @endif

                                                @if($isDinas && $bantuan->verifikator)
                                                    <div class="small text-muted" style="font-size: 0.7rem;">Oleh: {{ $bantuan->verifikator->name }}</div>
                                                @endif
                                            @else
                                                <span class="badge bg-light text-muted">Belum Ada Usulan</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group btn-group-sm">
                                                @if($isDinas && $bantuan)
                                                    <button type="button" class="btn btn-light-success" data-bs-toggle="modal" data-bs-target="#modalVerifikasiWarga{{ $bantuan->id_bantuan }}" title="Verifikasi Dinas">
                                                        <i class="bi bi-shield-check"></i>
                                                    </button>
                                                @endif

                                                <a href="{{ route('warga.show', $warga->id_warga) }}" class="btn btn-light-primary" title="Detail Lengkap">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('warga.edit', $warga->id_warga) }}" class="btn btn-light-warning" title="Edit Data">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('warga.destroy', $warga->id_warga) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus seluruh data terpadu warga ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-light-danger" title="Hapus Data">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $isDinas ? '9' : '8' }}" class="text-center py-5">
                                            <div class="py-3">
                                                <div class="avatar bg-light-primary text-primary mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px;">
                                                    <i class="bi bi-people fs-2"></i>
                                                </div>
                                                <h6 class="fw-bold text-dark mb-1">Belum ada data calon penerima RTLH</h6>
                                                <p class="text-muted small mb-3">Database warga saat ini masih kosong atau tidak ditemukan dengan filter yang dipilih.</p>
                                                <a href="{{ route('warga.create') }}" class="btn btn-sm btn-primary px-3 shadow-sm">
                                                    <i class="bi bi-plus-lg me-1"></i> Tambah Calon Penerima Baru
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ================= TAB 2: PRIORITAS SPK SAW & VERIFIKASI DINAS (KHUSUS DINAS) ================= -->
                @if($isDinas)
                <div class="tab-pane fade" id="content-spk" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 align-middle table-terpadu">
                            <thead>
                                <tr>
                                    <th class="ps-4 text-center" style="width: 70px;">Rank</th>
                                    <th>Calon Penerima & Wilayah</th>
                                    <th class="text-center" title="Kerusakan Fisik Bangunan">C1 (Fisik)</th>
                                    <th class="text-center" title="Luas Lantai per Orang">C2 (Luas)</th>
                                    <th class="text-center" title="Sanitasi & Air Minum">C3 (Sanitasi)</th>
                                    <th class="text-center" title="Desil DTSEN">C4 (DTSEN)</th>
                                    <th class="text-center" title="Penghasilan">C5 (Ekonomi)</th>
                                    <th class="text-center" style="width: 130px;">Skor SAW ($V_i$)</th>
                                    <th class="text-center">Status Verifikasi</th>
                                    <th class="text-end pe-4" style="width: 150px;">Aksi Verifikasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wargas as $index => $warga)
                                    @php
                                        $bantuan = $warga->bantuanAktif;
                                        $rank = $bantuan?->spkRanking;
                                        $rincian = $rank?->rincian_kriteria ?? [];
                                    @endphp
                                    <tr>
                                        <!-- Rank Column with Badges -->
                                        <td class="ps-4 text-center">
                                            @if($rank)
                                                @if($rank->ranking == 1)
                                                    <span class="badge rounded-circle p-2 bg-warning text-dark fw-bold shadow-sm" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">🥇1</span>
                                                @elseif($rank->ranking == 2)
                                                    <span class="badge rounded-circle p-2 bg-secondary text-white fw-bold shadow-sm" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">🥈2</span>
                                                @elseif($rank->ranking == 3)
                                                    <span class="badge rounded-circle p-2 bg-danger text-white fw-bold shadow-sm" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">🥉3</span>
                                                @else
                                                    <span class="badge bg-light text-dark border fw-bold">#{{ $rank->ranking }}</span>
                                                @endif
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="fw-bold text-dark">{{ $warga->nama }}</div>
                                            <small class="text-muted d-block">NIK: {{ $warga->nik }} | {{ $warga->wilayah->desa ?? '-' }}</small>
                                            @if($bantuan && $bantuan->program)
                                                <span class="badge bg-light text-primary border mt-1" style="font-size: 0.7rem;">
                                                    <i class="bi bi-gift-fill me-1"></i>{{ $bantuan->program->nama_program }}
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-center small">
                                            <span class="badge bg-light text-dark border">{{ $rincian['C1']['mentah'] ?? '-' }}</span>
                                        </td>
                                        <td class="text-center small">
                                            <span class="badge bg-light text-dark border">{{ isset($rincian['C2']['mentah']) ? $rincian['C2']['mentah'].'m²' : '-' }}</span>
                                        </td>
                                        <td class="text-center small">
                                            <span class="badge bg-light text-dark border">{{ $rincian['C3']['mentah'] ?? '-' }}</span>
                                        </td>
                                        <td class="text-center small">
                                            <span class="badge bg-light text-dark border">{{ $rincian['C4']['mentah'] ?? '-' }}</span>
                                        </td>
                                        <td class="text-center small">
                                            <span class="badge bg-light text-dark border">{{ $rincian['C5']['mentah'] ?? '-' }}</span>
                                        </td>

                                        <!-- Skor Akhir SAW -->
                                        <td class="text-center">
                                            @if($rank)
                                                <div class="fw-bold text-primary">{{ number_format($rank->skor_akhir, 4) }}</div>
                                                <div class="progress mt-1" style="height: 3px;">
                                                    <div class="progress-bar bg-primary" style="width: {{ min(100, $rank->skor_akhir * 100) }}%"></div>
                                                </div>
                                            @else
                                                <span class="text-muted small">Belum dihitung</span>
                                            @endif
                                        </td>

                                        <!-- Status Verifikasi Dinas -->
                                        <td class="text-center">
                                            @if($bantuan)
                                                @if($bantuan->status_bantuan == \App\Models\Bantuan::STATUS_DISETUJUI)
                                                    <span class="badge bg-success">Disetujui Dinas</span>
                                                @elseif($bantuan->status_bantuan == \App\Models\Bantuan::STATUS_REVISI)
                                                    <span class="badge bg-warning text-dark">Perlu Revisi</span>
                                                @elseif($bantuan->status_bantuan == \App\Models\Bantuan::STATUS_DITOLAK)
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @else
                                                    <span class="badge bg-secondary">Menunggu</span>
                                                @endif
                                            @else
                                                <span class="text-muted small">-</span>
                                            @endif
                                        </td>

                                        <!-- Aksi Verifikasi & Detail -->
                                        <td class="text-end pe-4">
                                            <div class="btn-group btn-group-sm">
                                                @if($rank)
                                                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalDetailSAW{{ $warga->id_warga }}" title="Rincian Perhitungan SAW">
                                                        <i class="bi bi-calculator"></i>
                                                    </button>
                                                @endif

                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVerifikasiWarga{{ $bantuan->id_bantuan }}" title="Verifikasi Dinas">
                                                    <i class="bi bi-check2-square"></i> Verifikasi
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5 text-muted">
                                            Tidak ada data untuk ditampilkan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <!-- ================= TAB: DETAIL KEPEMILIKAN HUNIAN ================= -->
                <div class="tab-pane fade" id="content-hunian" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 align-middle table-terpadu">
                            <thead>
                                <tr>
                                    <th class="ps-4" style="width: 50px;">No</th>
                                    <th>Calon Penerima</th>
                                    <th>Status Kepemilikan Rumah</th>
                                    <th>Status Hak Atas Tanah</th>
                                    <th>Luas Bangunan & Lahan</th>
                                    <th>Penghuni & Kawasan</th>
                                    <th>Titik Koordinat GPS</th>
                                    <th class="text-end pe-4" style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wargas as $index => $warga)
                                    @php $hunian = $warga->Hunian->first(); @endphp
                                    <tr>
                                        <td class="ps-4 fw-semibold text-muted">{{ $wargas->firstItem() + $index }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $warga->nama }}</div>
                                            <small class="text-muted">NIK: {{ $warga->nik }}</small>
                                        </td>
                                        <td>
                                            @if($hunian?->status_rumah == 1)
                                                <span class="badge bg-success">Milik Sendiri</span>
                                            @elseif($hunian?->status_rumah == 2)
                                                <span class="badge bg-warning text-dark">Sewa / Kontrak</span>
                                            @elseif($hunian?->status_rumah == 3)
                                                <span class="badge bg-secondary">Menumpang</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($hunian?->status_tanah == 1)
                                                <span class="badge bg-light-primary text-primary fw-semibold">SHM / Hak Milik</span>
                                            @elseif($hunian?->status_tanah == 2)
                                                <span class="badge bg-light-secondary text-dark fw-semibold">Girik / Letter C</span>
                                            @elseif($hunian?->status_tanah == 3)
                                                <span class="badge bg-light-danger text-danger fw-semibold">Tanah Negara</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark">Rumah: {{ $hunian?->luas_rumah ?? '-' }} m²</span><br>
                                            <small class="text-muted">Tanah: {{ $hunian?->luas_tanah ?? '-' }} m²</small>
                                        </td>
                                        <td>
                                            <div class="text-dark">{{ $hunian?->jml_kk ?? 1 }} KK / {{ $hunian?->jml_penghuni ?? 1 }} Jiwa</div>
                                            <small class="text-muted">
                                                @if($hunian?->kawasan == 1) Permukiman Kota
                                                @elseif($hunian?->kawasan == 2) Bantaran Sungai
                                                @elseif($hunian?->kawasan == 3) Kawasan Kumuh
                                                @else - @endif
                                            </small>
                                        </td>
                                        <td>
                                            @if($hunian?->latitude && $hunian?->longitude)
                                                <a href="https://www.google.com/maps?q={{ $hunian->latitude }},{{ $hunian->longitude }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.78rem;">
                                                    <i class="bi bi-geo-alt-fill me-1"></i> Buka Maps
                                                </a>
                                            @else
                                                <span class="text-muted small">Belum ada</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('warga.show', $warga->id_warga) }}" class="btn btn-sm btn-light-primary" title="Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="py-3">
                                                <div class="avatar bg-light-primary text-primary mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px;">
                                                    <i class="bi bi-house-door fs-2"></i>
                                                </div>
                                                <h6 class="fw-bold text-dark mb-1">Belum ada data kepemilikan hunian</h6>
                                                <p class="text-muted small mb-0">Silakan tambahkan data melalui formulir terpadu.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ================= TAB: DETAIL FISIK & SANITASI ================= -->
                <div class="tab-pane fade" id="content-fisik" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 align-middle table-terpadu">
                            <thead>
                                <tr>
                                    <th class="ps-4" style="width: 50px;">No</th>
                                    <th>Calon Penerima</th>
                                    <th>Kondisi Atap</th>
                                    <th>Kondisi Dinding</th>
                                    <th>Kondisi Lantai</th>
                                    <th>Sanitasi & MCK</th>
                                    <th>Dokumentasi Foto</th>
                                    <th class="text-end pe-4" style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wargas as $index => $warga)
                                    @php
                                        $hunian = $warga->Hunian->first();
                                        $rumah = $hunian?->Rumah->first();
                                        $utilitas = $rumah?->Utilitas->first();
                                        $fotoCount = $rumah?->DokumenRumah->count() ?? 0;
                                    @endphp
                                    <tr>
                                        <td class="ps-4 fw-semibold text-muted">{{ $wargas->firstItem() + $index }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $warga->nama }}</div>
                                            <small class="text-muted">{{ $warga->wilayah->desa ?? '-' }}</small>
                                        </td>
                                        <td>
                                            @if($rumah?->kondisi_atap == 3)
                                                <span class="badge bg-danger">Rusak Berat</span>
                                            @elseif($rumah?->kondisi_atap == 2)
                                                <span class="badge bg-warning text-dark">Rusak Sedang</span>
                                            @elseif($rumah?->kondisi_atap == 1)
                                                <span class="badge bg-success">Baik</span>
                                            @else - @endif
                                        </td>
                                        <td>
                                            @if($rumah?->kondisi_dinding == 3)
                                                <span class="badge bg-danger">Rusak Berat</span>
                                            @elseif($rumah?->kondisi_dinding == 2)
                                                <span class="badge bg-warning text-dark">Rusak Sedang</span>
                                            @elseif($rumah?->kondisi_dinding == 1)
                                                <span class="badge bg-success">Baik</span>
                                            @else - @endif
                                        </td>
                                        <td>
                                            @if($rumah?->kondisi_lantai == 3)
                                                <span class="badge bg-danger">Rusak / Tanah</span>
                                            @elseif($rumah?->kondisi_lantai == 2)
                                                <span class="badge bg-warning text-dark">Rusak Sedang</span>
                                            @elseif($rumah?->kondisi_lantai == 1)
                                                <span class="badge bg-success">Baik</span>
                                            @else - @endif
                                        </td>
                                        <td>
                                            @if($utilitas)
                                                <div class="small">
                                                    <i class="bi bi-droplet text-primary"></i> 
                                                    @if($utilitas->sumber_air == 1) PDAM
                                                    @elseif($utilitas->sumber_air == 2) Sumur Bor
                                                    @else Sumur/Lainnya @endif
                                                    <br>
                                                    <i class="bi bi-badge-wc text-secondary"></i>
                                                    @if($utilitas->mck == 1) MCK Sendiri
                                                    @elseif($utilitas->mck == 3) Komunal
                                                    @else Tidak Ada @endif
                                                </div>
                                            @else
                                                <span class="text-muted small">Belum ada</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($fotoCount > 0)
                                                <button type="button" class="btn btn-sm btn-outline-info py-0 px-2" data-bs-toggle="modal" data-bs-target="#modalFoto{{ $warga->id_warga }}" style="font-size: 0.78rem;">
                                                    <i class="bi bi-camera-fill me-1"></i> {{ $fotoCount }} Foto
                                                </button>
                                            @else
                                                <span class="badge bg-light text-muted">0 Foto</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('warga.show', $warga->id_warga) }}" class="btn btn-sm btn-light-primary" title="Detail Lengkap">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Modal Lightbox Galeri Foto Warga -->
                                    @if($fotoCount > 0)
                                        <div class="modal fade" id="modalFoto{{ $warga->id_warga }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">
                                                            <i class="bi bi-images text-primary me-2"></i> Foto Dokumentasi: {{ $warga->nama }}
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row g-3">
                                                            @foreach($rumah->DokumenRumah as $foto)
                                                                <div class="col-12 col-md-4 text-center">
                                                                    <div class="border rounded p-2 bg-light">
                                                                        <img src="{{ asset('storage/' . $foto->path_foto) }}" alt="{{ $foto->jenis_foto }}" class="img-fluid rounded mb-2" style="max-height: 180px; object-fit: cover; width: 100%;">
                                                                        <div class="fw-semibold small text-dark">{{ $foto->jenis_foto }}</div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="py-3">
                                                <div class="avatar bg-light-primary text-primary mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px;">
                                                    <i class="bi bi-bricks fs-2"></i>
                                                </div>
                                                <h6 class="fw-bold text-dark mb-1">Belum ada data survei fisik rumah</h6>
                                                <p class="text-muted small mb-0">Silakan tambahkan data melalui formulir terpadu.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Modals Khusus Dinas (Verifikasi Dinas & Detail SAW) -->
            @if($isDinas)
                @foreach($wargas as $warga)
                    @php
                        $bantuan = $warga->bantuanAktif;
                        $rank = $bantuan?->spkRanking;
                        $rincian = $rank?->rincian_kriteria ?? [];
                    @endphp

                    <!-- Modal Verifikasi Dinas -->
                    @if($bantuan)
                    <div class="modal fade" id="modalVerifikasiWarga{{ $bantuan->id_bantuan }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('spk.verifikasi', $bantuan->id_bantuan) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title text-white fw-bold">
                                            <i class="bi bi-shield-check me-2"></i>Verifikasi Dinas: {{ $warga->nama }}
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="alert alert-light border mb-3">
                                            <div class="small text-muted">Hasil SPK SAW Terkini:</div>
                                            <div class="fw-bold text-dark">
                                                Peringkat: <span class="text-primary">#{{ $rank?->ranking ?? '-' }}</span> |
                                                Skor Preferensi ($V_i$): <span class="text-primary">{{ number_format($rank?->skor_akhir ?? 0, 4) }}</span>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-dark">Keputusan Verifikasi Dinas <span class="text-danger">*</span></label>
                                            <select name="status_bantuan" class="form-select" required>
                                                <option value="2" {{ $bantuan->status_bantuan == \App\Models\Bantuan::STATUS_DISETUJUI ? 'selected' : '' }}>
                                                    ✅ Disetujui (Lolos Prioritas & Penetapan Penerima)
                                                </option>
                                                <option value="3" {{ $bantuan->status_bantuan == \App\Models\Bantuan::STATUS_REVISI ? 'selected' : '' }}>
                                                    🔄 Perlu Revisi / Perbaikan Data Lapangan
                                                </option>
                                                <option value="4" {{ $bantuan->status_bantuan == \App\Models\Bantuan::STATUS_DITOLAK ? 'selected' : '' }}>
                                                    ❌ Ditolak (Tidak Memenuhi Syarat Lapangan)
                                                </option>
                                                <option value="1" {{ $bantuan->status_bantuan == \App\Models\Bantuan::STATUS_DIVERIFIKASI ? 'selected' : '' }}>
                                                    ⏳ Terverifikasi Lapangan (Menunggu SK Penetapan)
                                                </option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold text-dark">Catatan Resmi Verifikator <span class="text-danger">*</span></label>
                                            <textarea name="catatan_verifikasi" class="form-control" rows="4" placeholder="Tuliskan catatan verifikasi fisik, kelayakan faktual, atau pertimbangan kuota..." required>{{ $bantuan->catatan_verifikasi }}</textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary btn-sm">Simpan Keputusan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                    <!-- Modal Detail Perhitungan Transparan SAW -->
                    @if($rank)
                    <div class="modal fade" id="modalDetailSAW{{ $warga->id_warga }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-light">
                                    <h5 class="modal-title fw-bold text-dark">
                                        <i class="bi bi-calculator me-2 text-primary"></i>Rincian Skor SAW #{{ $rank->ranking }} - {{ $warga->nama }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body p-4">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm text-center align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Kode</th>
                                                    <th class="text-start">Kriteria</th>
                                                    <th>Sifat</th>
                                                    <th>Bobot ($W$)</th>
                                                    <th>Nilai Mentah ($X$)</th>
                                                    <th>Normalisasi ($r_{ij}$)</th>
                                                    <th>Skor Terbobot</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach(['C1', 'C2', 'C3', 'C4', 'C5'] as $kode)
                                                    @php $k = $rincian[$kode] ?? null; @endphp
                                                    <tr>
                                                        <td class="fw-bold">{{ $kode }}</td>
                                                        <td class="text-start">{{ $k['nama'] ?? '-' }}</td>
                                                        <td>
                                                            <span class="badge {{ ($k['jenis'] ?? '') == 'benefit' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                                {{ strtoupper($k['jenis'] ?? '-') }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $k['bobot'] ?? '-' }}</td>
                                                        <td class="fw-semibold">{{ $k['mentah'] ?? '-' }}</td>
                                                        <td>{{ $k['normalisasi'] ?? '-' }}</td>
                                                        <td class="fw-bold text-primary">{{ $k['terbobot'] ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <th colspan="6" class="text-end">Total Skor Akhir Preferensi ($V_i$):</th>
                                                    <th class="text-primary fs-6">{{ number_format($rank->skor_akhir, 4) }}</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            @endif

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
@endsection
