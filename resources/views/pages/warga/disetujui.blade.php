@extends('app')

@section('title', 'Calon Penerima Bantuan Disetujui Dinas')

@section('content')
@php
    $isDinas = Auth::user()->isSuperadmin() || Auth::user()->isAdminDinas();
    $wilayahs = $wilayahs ?? \App\Models\Wilayah::orderBy('desa')->get();
    $programs = $programs ?? \App\Models\ProgramBantuan::orderBy('nama_program')->get();
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
            <div class="col-12 col-xl-7">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item">Penetapan &amp; Pelaksanaan</li>
                        <li class="breadcrumb-item active" aria-current="page">Penerima Disetujui</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-1 text-primary">
                    <i class="bi bi-patch-check-fill me-2"></i>Calon Penerima Bantuan Disetujui Dinas
                </h3>
                <p class="text-muted small mb-0">
                    Daftar calon penerima yang telah ditetapkan lolos verifikasi Dinas (<strong>Disetujui Dinas</strong>) dan siap memasuki tahap penyaluran. Data akan berpindah ke menu Realisasi setelah progres mencapai 100%.
                </p>
            </div>
            <div class="col-12 col-xl-5 text-xl-end mt-3 mt-xl-0 d-flex flex-wrap justify-content-xl-end gap-2">
                <a href="{{ route('warga.export', array_merge(request()->query(), ['status_bantuan' => \App\Models\Bantuan::STATUS_DISETUJUI])) }}" class="btn btn-success shadow-sm px-3 py-2 fw-semibold" title="Unduh Daftar Penerima Disetujui (.xls)">
                    <i class="bi bi-file-earmark-excel-fill me-1"></i> Ekspor Excel
                </a>

                @if($isDinas)
                    <a href="{{ route('realisasi.index') }}" class="btn btn-primary shadow-sm px-3 py-2 fw-semibold">
                        <i class="bi bi-hammer me-1"></i> Ke Menu Realisasi Bantuan
                    </a>
                @endif
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

    @if(!$isDinas)
        <div class="alert alert-light-info border border-info-subtle shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-lock-fill text-info fs-5 me-2"></i>
                <small class="text-dark mb-0">
                    <strong>Mode Lihat Saja (View-Only):</strong> Data penerima di halaman ini sudah ditetapkan oleh Dinas dan terkunci dari pengubahan oleh pihak Kelurahan.
                </small>
            </div>
        </div>
    @endif

    <!-- KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 border-start border-primary border-4 h-100 kpi-card">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="kpi-icon bg-light-primary text-primary me-3">
                        <i class="bi bi-patch-check-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Total Penerima Disetujui</div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $wargas->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>

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
            <div class="card shadow-sm border-0 border-start border-warning border-4 h-100 kpi-card">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="kpi-icon bg-light-warning text-warning me-3">
                        <i class="bi bi-gift-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Sudah Memilih Program</div>
                        <h4 class="fw-bold mb-0 text-dark">
                            {{ $wargas->filter(fn($w) => $w->bantuanAktif?->program !== null)->count() }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('warga.disetujui') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-md-4">
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

                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-semibold text-muted mb-1">Jenis Bantuan</label>
                            <select name="id_program" class="form-select form-select-sm">
                                <option value="">Semua Jenis Bantuan</option>
                                @foreach($programs as $prog)
                                    <option value="{{ $prog->id_program }}" {{ request('id_program') == $prog->id_program ? 'selected' : '' }}>
                                        {{ $prog->nama_program }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-semibold text-muted mb-1">Jenis Bantuan</label>
                            <select name="id_program" class="form-select form-select-sm">
                                <option value="">Semua Jenis Bantuan</option>
                                @foreach($programs as $prog)
                                    <option value="{{ $prog->id_program }}" {{ request('id_program') == $prog->id_program ? 'selected' : '' }}>
                                        {{ $prog->nama_program }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-3">
                            <label class="form-label small fw-semibold text-muted mb-1">Status Kepemilikan</label>
                            <select name="status_rumah" class="form-select form-select-sm">
                                <option value="">Semua Status</option>
                                <option value="1" {{ request('status_rumah') == '1' ? 'selected' : '' }}>Milik Sendiri</option>
                                <option value="2" {{ request('status_rumah') == '2' ? 'selected' : '' }}>Sewa / Kontrak</option>
                                <option value="3" {{ request('status_rumah') == '3' ? 'selected' : '' }}>Menumpang</option>
                            </select>
                        </div>
                    @endif

                    <div class="col-12 col-md-1 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100" title="Terapkan Filter">
                            <i class="bi bi-funnel"></i>
                        </button>
                        @if(request()->hasAny(['search', 'id_wilayah', 'id_program', 'status_rumah']))
                            <a href="{{ route('warga.disetujui') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
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
                <h5 class="card-title fw-bold text-dark mb-0">Daftar Calon Penerima Disetujui Dinas</h5>
                <small class="text-muted">
                    @if($isDinas)
                        Menampilkan penerima yang disetujui Dinas (siap realisasi). Gunakan tombol aksi untuk meninjau detail atau memperbarui keputusan verifikasi bila diperlukan.
                    @else
                        Daftar warga di Kelurahan {{ Auth::user()->wilayah->desa ?? '' }} yang telah disetujui Dinas untuk menerima bantuan RTLH.
                    @endif
                </small>
            </div>
            <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill fw-bold">
                Total: {{ $wargas->total() }} Penerima
            </span>
        </div>

        <!-- Tabs -->
        <div class="bg-light px-3 py-2 border-bottom">
            <ul class="nav nav-pills nav-pills-custom gap-2 mb-0" id="tableTabsDisetujui" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-master" data-bs-toggle="pill" data-bs-target="#content-master" type="button" role="tab">
                        <i class="bi bi-patch-check-fill me-1"></i> 1. Daftar Penerima Disetujui
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

        <div class="card-body p-0">
            <div class="tab-content" id="tableTabsDisetujuiContent">
                <!-- TAB 1: DAFTAR PENERIMA DISETUJUI -->
                <div class="tab-pane fade show active" id="content-master" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 align-middle table-terpadu">
                            <thead>
                                <tr>
                                    <th class="ps-4" style="width: 50px;">No</th>
                                    @if($isDinas)
                                        <th>Peringkat SAW</th>
                                    @endif
                                    <th>Penerima Bantuan (Warga)</th>
                                    <th>Wilayah &amp; Alamat</th>
                                    <th>Jenis &amp; Nominal Bantuan</th>
                                    <th>Status &amp; Verifikator</th>
                                    <th class="text-end pe-4" style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wargas as $index => $warga)
                                    @php
                                        $bantuan = $warga->bantuanAktif;
                                        $ranking = $bantuan?->spkRanking;
                                    @endphp
                                    <tr>
                                        <td class="ps-4 fw-semibold text-muted">
                                            {{ $wargas->firstItem() + $index }}
                                        </td>

                                        @if($isDinas)
                                            <td>
                                                @if($ranking)
                                                    <button type="button" class="btn btn-sm btn-light border fw-bold text-primary py-0 px-2" data-bs-toggle="modal" data-bs-target="#modalDetailSAW{{ $warga->id_warga }}" title="Lihat Rincian SAW">
                                                        #{{ $ranking->ranking }} ({{ number_format($ranking->skor_akhir, 3) }})
                                                    </button>
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
                                            @if($bantuan && $bantuan->program)
                                                <span class="badge bg-light-primary text-primary fw-semibold px-2 py-1 border border-primary-subtle">
                                                    <i class="bi bi-gift-fill me-1"></i>{{ $bantuan->program->nama_program }}
                                                </span>
                                                @if($bantuan->program->nominal_bantuan)
                                                    <div class="small fw-semibold text-success mt-1">
                                                        Rp {{ number_format($bantuan->program->nominal_bantuan, 0, ',', '.') }}
                                                    </div>
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
                                                    <button type="button" class="btn btn-light-success" data-bs-toggle="modal" data-bs-target="#modalVerifikasiWarga{{ $bantuan->id_bantuan }}" title="Ubah Keputusan Verifikasi Dinas">
                                                        <i class="bi bi-shield-check"></i>
                                                    </button>
                                                @endif

                                                @can('update', $warga)
                                                    <a href="{{ route('warga.edit', $warga->id_warga) }}" class="btn btn-light-warning" title="Edit Data (Superadmin)">
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
                                        <td colspan="{{ $isDinas ? '7' : '6' }}" class="text-center py-5">
                                            <div class="py-3">
                                                <div class="avatar bg-light-primary text-primary mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px;">
                                                    <i class="bi bi-patch-check fs-2"></i>
                                                </div>
                                                <h6 class="fw-bold text-dark mb-1">Belum ada calon penerima berstatus Disetujui Dinas</h6>
                                                <p class="text-muted small mb-0">Data akan tampil di halaman ini setelah usulan disetujui pada menu Verifikasi &amp; SPK (dan belum selesai direalisasikan 100%).</p>
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
                        Menampilkan <strong>{{ $wargas->firstItem() }}</strong> - <strong>{{ $wargas->lastItem() }}</strong> dari <strong>{{ $wargas->total() }}</strong> penerima disetujui
                    </small>
                    <div>
                        {{ $wargas->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@if($isDinas)
    @foreach($wargas as $warga)
        @php
            $bantuan = $warga->bantuanAktif;
            $rank = $bantuan?->spkRanking;
        @endphp
        @include('pages.warga.partials._modal_verifikasi', ['bantuan' => $bantuan, 'warga' => $warga, 'rank' => $rank])
        @include('pages.warga.partials._modal_detail_saw', ['warga' => $warga, 'rank' => $rank])
    @endforeach
@endif
@endsection
