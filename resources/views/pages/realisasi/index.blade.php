@extends('app')

@section('title', 'Realisasi & Penyaluran Bantuan RTLH')

@section('content')
@php
    $isDinas = Auth::check() && (Auth::user()->isSuperadmin() || Auth::user()->isAdminDinas());
@endphp

<style>
    .thumb-foto {
        width: 36px;
        height: 36px;
        object-fit: cover;
        border-radius: 6px;
        cursor: pointer;
        transition: transform 0.15s ease;
        border: 1px solid #CBD5E1;
    }
    .thumb-foto:hover {
        transform: scale(1.15);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .thumb-empty {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        border: 1px dashed #CBD5E1;
        background: #F8FAFC;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #94A3B8;
        font-family: 'Spline Sans Mono', monospace;
        font-size: 10.5px;
        font-weight: 600;
    }
</style>

<div class="page-heading">
    <!-- Header Halaman Versi Stitch -->
    <div class="d-flex flex-column flex-md-row md:items-start justify-content-between gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1 font-headline" style="color: var(--stitch-primary);">
                <span class="material-symbols-outlined me-2 text-primary" style="font-size: 28px; vertical-align: middle;">engineering</span>Realisasi &amp; Progres Bantuan RTLH
            </h3>
            <p class="text-muted mb-0 font-stitch" style="font-size: 13.5px;">
                @if($isDinas)
                    Monitoring penyaluran bantuan, progres pengerjaan fisik bangunan (0%, 25%, 50%, 100%), dan dokumen pertanggungjawaban calon penerima yang telah disetujui Dinas.
                @else
                    Pantau progres pengerjaan fisik bangunan (0%, 25%, 50%, 100%) dan dokumentasi penyaluran bantuan RTLH di wilayah <strong>{{ Auth::user()->wilayah->desa ?? 'Kelurahan' }}</strong>.
                @endif
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2 shrink-0">
            <a href="{{ route('warga.index') }}" class="btn btn-sm btn-outline-secondary shadow-sm px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1 font-stitch" style="font-size: 12.5px; border-radius: 8px;">
                <span class="material-symbols-outlined" style="font-size: 17px;">group</span>
                <span>Daftar Usulan</span>
            </a>
        </div>
    </div>

    @if(!$isDinas)
        <div class="alert alert-light-success border shadow-sm mb-4 font-stitch" style="font-size: 13px; background: #F0FDF4; border-color: #BBF7D0 !important;">
            <div class="d-flex align-items-center">
                <span class="material-symbols-outlined text-success fs-5 me-2">engineering</span>
                <span class="text-dark mb-0">
                    <strong>Pelaporan Realisasi Fisik Kelurahan:</strong> Petugas Kelurahan bertugas menginput dan memperbarui progres fisik (titik 0%, 25%, 50%, 100%) serta mengunggah dokumentasi foto lapangan &amp; bukti kuitansi untuk warga penerima bantuan di wilayah Anda.
                </span>
            </div>
        </div>
    @endif

    <!-- Alert Flash Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible show fade shadow-sm mb-4">
            <div class="d-flex align-items-center font-stitch" style="font-size: 13.5px;">
                <span class="material-symbols-outlined fs-4 me-2 text-success">check_circle</span>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible show fade shadow-sm mb-4">
            <div class="d-flex align-items-center font-stitch" style="font-size: 13.5px;">
                <span class="material-symbols-outlined fs-4 me-2 text-danger">warning</span>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- 4 KPI Summary Cards (Telah Dirapikan) -->
    <div class="row g-3 mb-4">
        <!-- KPI 1: Penerima Terverifikasi -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stitch-kpi-card">
                <div class="stitch-kpi-icon" style="background: var(--stitch-info-bg); color: var(--stitch-info-text);">
                    <span class="material-symbols-outlined">person_check</span>
                </div>
                <div class="min-w-0">
                    <div class="stitch-kpi-label">Penerima Terverifikasi</div>
                    <div class="stitch-kpi-val font-mono-num" style="font-size: 22px; color: var(--stitch-info-text);">{{ $totalPenerima }}</div>
                    <small class="text-muted font-stitch" style="font-size: 11px;">Siap / sedang realisasi</small>
                </div>
            </div>
        </div>

        <!-- KPI 2: Belum Mulai (0%) -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stitch-kpi-card">
                <div class="stitch-kpi-icon" style="background: #F1F5F9; color: #475569;">
                    <span class="material-symbols-outlined">hourglass_empty</span>
                </div>
                <div class="min-w-0">
                    <div class="stitch-kpi-label">Belum Mulai (0%)</div>
                    <div class="stitch-kpi-val font-mono-num" style="font-size: 22px;">{{ $totalBelumMulai }}</div>
                    <small class="text-muted font-stitch" style="font-size: 11px;">Persiapan &amp; titik nol</small>
                </div>
            </div>
        </div>

        <!-- KPI 3: Dalam Pengerjaan -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stitch-kpi-card">
                <div class="stitch-kpi-icon" style="background: var(--stitch-warning-bg); color: var(--stitch-warning-text);">
                    <span class="material-symbols-outlined">construction</span>
                </div>
                <div class="min-w-0">
                    <div class="stitch-kpi-label">Dalam Pengerjaan</div>
                    <div class="stitch-kpi-val font-mono-num" style="font-size: 22px; color: var(--stitch-warning-text);">{{ $totalProgres25 + $totalProgres50 }}</div>
                    <small class="text-muted font-stitch" style="font-size: 11px;">Progres 25% &amp; 50%</small>
                </div>
            </div>
        </div>

        <!-- KPI 4: Selesai 100% -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stitch-kpi-card">
                <div class="stitch-kpi-icon" style="background: var(--stitch-success-bg); color: var(--stitch-success-text);">
                    <span class="material-symbols-outlined">task_alt</span>
                </div>
                <div class="min-w-0">
                    <div class="stitch-kpi-label">Selesai 100%</div>
                    <div class="stitch-kpi-val font-mono-num" style="font-size: 22px; color: var(--stitch-success-text);">{{ $totalSelesai }}</div>
                    <small class="text-muted font-stitch" style="font-size: 11px;">Direalisasikan penuh</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian Toolbar Stitch -->
    <div class="stitch-card mb-4">
        <div class="stitch-toolbar">
            <form method="GET" action="{{ route('realisasi.index') }}">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-{{ $isDinas ? '2' : '3' }}">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <span class="material-symbols-outlined" style="font-size: 16px;">search</span>
                            </span>
                            <input type="text" name="search" class="form-control font-stitch" style="font-size: 12.5px;" placeholder="Cari NIK / Nama / KK..." value="{{ request('search') }}">
                        </div>
                    </div>

                    @if($isDinas)
                        <div class="col-12 col-md-2">
                            <select name="id_wilayah" class="form-select form-select-sm font-stitch" style="font-size: 12.5px;" onchange="this.form.submit()">
                                <option value="">Semua Kelurahan</option>
                                @foreach($wilayahs as $w)
                                    <option value="{{ $w->id_wilayah }}" {{ request('id_wilayah') == $w->id_wilayah ? 'selected' : '' }}>
                                        Kel. {{ $w->desa }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-12 col-md-2">
                        <select name="id_program" class="form-select form-select-sm font-stitch" style="font-size: 12.5px;" onchange="this.form.submit()">
                            <option value="">Semua Program</option>
                            @foreach($programs as $prog)
                                <option value="{{ $prog->id_program }}" {{ request('id_program') == $prog->id_program ? 'selected' : '' }}>
                                    {{ $prog->nama_program }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <select name="status_bantuan" class="form-select form-select-sm font-stitch" style="font-size: 12.5px;" onchange="this.form.submit()">
                            <option value="">Semua Status Bantuan</option>
                            <option value="{{ \App\Models\Bantuan::STATUS_DISETUJUI }}" {{ request('status_bantuan') === (string) \App\Models\Bantuan::STATUS_DISETUJUI ? 'selected' : '' }}>Disetujui / Siap Realisasi</option>
                            <option value="{{ \App\Models\Bantuan::STATUS_DIREALISASIKAN }}" {{ request('status_bantuan') === (string) \App\Models\Bantuan::STATUS_DIREALISASIKAN ? 'selected' : '' }}>Direalisasikan (100%)</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-2">
                        <select name="progres" class="form-select form-select-sm font-stitch" style="font-size: 12.5px;" onchange="this.form.submit()">
                            <option value="">Semua Progres</option>
                            <option value="belum" {{ request('progres') === 'belum' ? 'selected' : '' }}>Belum Mulai (0%)</option>
                            <option value="25" {{ request('progres') === '25' ? 'selected' : '' }}>Progres 25%</option>
                            <option value="50" {{ request('progres') === '50' ? 'selected' : '' }}>Progres 50%</option>
                            <option value="100" {{ request('progres') === '100' ? 'selected' : '' }}>Selesai (100%)</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-{{ $isDinas ? '1' : '2' }}">
                        <select name="tahun" class="form-select form-select-sm font-stitch" style="font-size: 12.5px;" onchange="this.form.submit()">
                            <option value="">Semua Tahun</option>
                            @foreach($availableYears as $y)
                                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-1 d-flex gap-1 justify-content-end">
                        <button type="submit" class="btn btn-sm btn-primary px-3 fw-semibold d-inline-flex align-items-center gap-1 font-stitch" style="font-size: 12.5px;" title="Terapkan Filter">
                            <span class="material-symbols-outlined" style="font-size: 15px;">filter_list</span>
                        </button>
                        @if(request()->hasAny(['search', 'id_wilayah', 'id_program', 'progres', 'tahun', 'status_bantuan']))
                            <a href="{{ route('realisasi.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center" title="Reset Filter">
                                <span class="material-symbols-outlined" style="font-size: 15px;">restart_alt</span>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Realisasi Versi Stitch -->
    <div class="stitch-card">
        <!-- Header Tabel & Live Badge Counter -->
        <div class="p-3 bg-white border-bottom d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
            <div>
                <h5 class="fw-bold text-dark mb-0 font-headline">Daftar Penerima Bantuan RTLH Terverifikasi</h5>
                <small class="text-muted font-stitch">Kelola progres fisik bertahap, dokumentasi foto pelaksanaan, dan kuitansi pertanggungjawaban bantuan.</small>
            </div>
            <div class="stitch-header-badge font-stitch">
                <span class="dot"></span>
                <span>Total: <strong class="font-mono-num">{{ $bantuans->total() }}</strong> Penerima</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="stitch-table">
                <thead>
                    <tr>
                        <th class="ps-3" style="width: 50px;">No</th>
                        <th>Penerima Manfaat</th>
                        <th>Kelurahan &amp; Alamat</th>
                        <th>Program Bantuan</th>
                        <th>Status Verifikasi</th>
                        <th style="min-width: 170px;">Progres Fisik</th>
                        <th>Dokumentasi Foto</th>
                        <th>Kuitansi / SPJ</th>
                        <th class="text-end pe-3" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bantuans as $index => $bantuan)
                        @php
                            $warga = $bantuan->warga;
                            $realisasi = $bantuan->realisasiTerbaru;
                            $progres = $realisasi ? (int) $realisasi->status : 0;
                            $canInputRealisasi = $isDinas || (Auth::check() && Auth::user()->isAdminKelurahan() && (int)Auth::user()->id_wilayah === (int)($warga?->id_wilayah ?? 0));
                        @endphp
                        <tr>
                            <td class="ps-3 tabular-nums font-mono-num text-muted">
                                {{ $bantuans->firstItem() + $index }}
                            </td>

                            <!-- Identitas Penerima -->
                            <td>
                                @if($warga)
                                    <div class="d-flex flex-column gap-1">
                                        <a href="{{ route('warga.show', ['warga' => $warga->id_warga, 'from' => 'realisasi']) }}" class="fw-bold text-dark text-decoration-none font-stitch" style="font-size: 13.5px;">
                                            {{ $warga->nama }}
                                        </a>
                                        <span class="font-mono-num text-muted">NIK: {{ $warga->nik }}</span>
                                        @if($warga->no_kk)
                                            <span class="font-mono-num text-muted">KK: {{ $warga->no_kk }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted font-stitch">-</span>
                                @endif
                            </td>

                            <!-- Wilayah & Alamat -->
                            <td>
                                <div class="d-flex flex-column gap-1 font-stitch">
                                    <span class="text-dark fw-semibold" style="font-size: 13px;">Kel. {{ $warga?->wilayah?->desa ?? '-' }}</span>
                                    <small class="text-muted" style="font-size: 11.5px;">
                                        {{ $warga?->alamat ?? 'Alamat belum diisi' }} (RT {{ $warga?->rt ?? '-' }} / RW {{ $warga?->rw ?? '-' }})
                                    </small>
                                    <small class="text-secondary" style="font-size: 11px;">Kec. {{ $warga?->wilayah?->kecamatan ?? 'Kediri' }}</small>
                                </div>
                            </td>

                            <!-- Jenis / Program Bantuan -->
                            <td>
                                <div class="d-flex flex-column align-items-start gap-1">
                                    <span class="badge rounded-pill font-stitch" style="background: var(--stitch-info-bg); color: var(--stitch-info-text); font-weight: 600; font-size: 11.5px;">
                                        <span class="material-symbols-outlined me-1" style="font-size: 13px; vertical-align: middle;">card_giftcard</span>{{ $bantuan->program?->nama_program ?? 'Bantuan RTLH' }}
                                    </span>
                                    @if($bantuan->program?->nominal_bantuan)
                                        <span class="font-mono-num fw-semibold" style="color: var(--stitch-success-text); font-size: 12px;">
                                            Rp {{ number_format($bantuan->program->nominal_bantuan, 0, ',', '.') }}
                                        </span>
                                    @endif
                                    <small class="text-muted font-mono-num" style="font-size: 11px;">Tahun {{ $bantuan->tahun_bantuan }}</small>
                                </div>
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
                                <div class="d-flex flex-column gap-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold font-stitch" style="font-size: 12px;">
                                            @if($progres == 100)
                                                <span style="color: var(--stitch-success-text);"><i class="bi bi-check-circle-fill me-1"></i>Selesai 100%</span>
                                            @elseif($progres > 0)
                                                <span style="color: var(--stitch-warning-text);"><i class="bi bi-cone-striped me-1"></i>{{ $progres }}% Selesai</span>
                                            @else
                                                <span class="text-muted"><i class="bi bi-circle me-1"></i>0% (Titik Nol)</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="progress" style="height: 6px; background-color: #E2E8F0; border-radius: 9999px;">
                                        <div class="progress-bar {{ $progres == 100 ? 'bg-success' : ($progres >= 50 ? 'bg-warning' : ($progres >= 25 ? 'bg-info' : 'bg-secondary')) }}" 
                                             role="progressbar" 
                                             style="width: {{ $progres }}%; border-radius: 9999px;" 
                                             aria-valuenow="{{ $progres }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                    @if($realisasi?->tgl_realisasi)
                                        <small class="text-muted font-mono-num" style="font-size: 11px;">
                                            <i class="bi bi-calendar3 me-1"></i>{{ $realisasi->tgl_realisasi->format('d M Y') }}
                                        </small>
                                    @endif
                                </div>
                            </td>

                            <!-- Dokumentasi Foto Bertahap (0%, 25%, 50%, 100%) -->
                            <td>
                                <div class="d-flex gap-1 align-items-center">
                                    @foreach(['foto_0' => '0%', 'foto_25' => '25%', 'foto_50' => '50%', 'foto_100' => '100%'] as $fotoKey => $fotoLabel)
                                        @if($realisasi && $realisasi->$fotoKey)
                                            <a href="javascript:void(0)" onclick="bukaPreviewFoto('{{ asset('storage/' . $realisasi->$fotoKey) }}', 'Dokumentasi Progres Fisik {{ $fotoLabel }} - {{ addslashes($bantuan->warga->nama ?? '') }}', 'progres_{{ $fotoLabel }}_{{ $bantuan->warga->nik ?? 'penerima' }}.jpg')" title="Klik untuk lihat &amp; unduh Foto {{ $fotoLabel }}">
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
                                    <button type="button" class="btn btn-sm btn-outline-success py-1 px-2 font-stitch d-inline-flex align-items-center gap-1" style="font-size: 11.5px; border-radius: 6px;" onclick="bukaPreviewFoto('{{ asset('storage/' . $realisasi->kwitansi) }}', 'Bukti Kwitansi / SPJ - {{ addslashes($bantuan->warga->nama ?? '') }}', 'SPJ_{{ $bantuan->warga->nik ?? 'penerima' }}.jpg')">
                                        <span class="material-symbols-outlined" style="font-size: 14px;">description</span>
                                        <span>Lihat SPJ</span>
                                    </button>
                                @else
                                    <span class="text-muted font-stitch" style="font-size: 11.5px;">Belum ada</span>
                                @endif
                            </td>

                            <!-- Aksi -->
                            <td class="text-end pe-3">
                                <div class="d-inline-flex align-items-center justify-content-end gap-1">
                                    @if($canInputRealisasi)
                                        <!-- Tombol Input / Update Realisasi -->
                                        <button type="button" 
                                                class="stitch-btn-verify" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalUpdateRealisasi{{ $bantuan->id_bantuan }}" 
                                                title="{{ $isDinas ? 'Verifikasi & Perbarui Data Realisasi' : 'Input / Laporkan Progres Fisik' }}">
                                            <span class="material-symbols-outlined" style="font-size: 15px;">engineering</span>
                                            <span>{{ $progres > 0 ? $progres . '%' : 'Input' }}</span>
                                        </button>
                                    @endif

                                    @if($warga)
                                        <a href="{{ route('warga.show', ['warga' => $warga->id_warga, 'from' => 'realisasi']) }}" class="stitch-btn-icon" title="Detail Profil Warga">
                                            <span class="material-symbols-outlined" style="font-size: 17px;">visibility</span>
                                        </a>
                                    @endif

                                    <!-- Tombol Galeri Detail -->
                                    <button type="button" 
                                            class="stitch-btn-icon" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalGaleri{{ $bantuan->id_bantuan }}" 
                                            title="Galeri Dokumentasi Foto Fisik">
                                        <span class="material-symbols-outlined" style="font-size: 17px;">photo_library</span>
                                    </button>

                                    @if($isDinas && $realisasi)
                                        <form action="{{ route('realisasi.destroy', $realisasi->id_realisasi) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus / reset data realisasi untuk {{ addslashes($warga?->nama ?? 'warga ini') }}? Status bantuan akan dikembalikan ke Disetujui Dinas.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="stitch-btn-delete" title="Hapus / Reset Data Realisasi">
                                                <span class="material-symbols-outlined" style="font-size: 16px;">delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        @if($canInputRealisasi)
                        <!-- ================= MODAL UPDATE PROGRES REALISASI ================= -->
                        <div class="modal fade" id="modalUpdateRealisasi{{ $bantuan->id_bantuan }}" tabindex="-1" aria-labelledby="labelUpdate{{ $bantuan->id_bantuan }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header border-bottom">
                                        <h5 class="modal-title fw-bold font-headline" style="color: var(--stitch-primary);" id="labelUpdate{{ $bantuan->id_bantuan }}">
                                            <span class="material-symbols-outlined me-2" style="font-size: 22px; vertical-align: middle;">engineering</span>Update Progres Realisasi Bantuan
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('realisasi.update', $bantuan->id_bantuan) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body font-stitch">
                                            <!-- Info Calon Penerima -->
                                            <div class="bg-light p-3 rounded mb-3 border">
                                                <div class="row g-2">
                                                    <div class="col-12 col-md-6">
                                                        <small class="text-muted d-block">Penerima Manfaat:</small>
                                                        <strong class="text-dark">{{ $warga?->nama }}</strong>
                                                        <div class="small text-muted font-mono-num">NIK: {{ $warga?->nik }}</div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                        <small class="text-muted d-block">Program &amp; Lokasi:</small>
                                                        <strong class="text-dark">{{ $bantuan->program?->nama_program ?? 'Bantuan RTLH' }}</strong>
                                                        <div class="small text-muted">{{ $warga?->wilayah?->desa ?? '-' }} (RT {{ $warga?->rt ?? '-' }} / RW {{ $warga?->rw ?? '-' }})</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row g-3">
                                                <!-- Status Progres -->
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label fw-semibold">Progres Fisik Pelaksanaan <span class="text-danger">*</span></label>
                                                    <select name="status" class="form-select font-stitch" required>
                                                        <option value="0" {{ $progres == 0 ? 'selected' : '' }}>0% - Titik Nol / Persiapan Awal</option>
                                                        <option value="25" {{ $progres == 25 ? 'selected' : '' }}>25% - Pengerjaan Pondasi &amp; Struktur</option>
                                                        <option value="50" {{ $progres == 50 ? 'selected' : '' }}>50% - Pemasangan Dinding &amp; Rangka Atap</option>
                                                        <option value="100" {{ $progres == 100 ? 'selected' : '' }}>100% - Selesai &amp; Serah Terima Fisik</option>
                                                    </select>
                                                    <small class="text-muted">Status 100% otomatis menandai bantuan selesai direalisasikan.</small>
                                                </div>

                                                <!-- Tanggal Realisasi -->
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label fw-semibold">Tanggal Realisasi / Penyaluran</label>
                                                    <input type="date" name="tgl_realisasi" class="form-control font-stitch" 
                                                           value="{{ $realisasi?->tgl_realisasi ? $realisasi->tgl_realisasi->format('Y-m-d') : date('Y-m-d') }}">
                                                </div>

                                                <div class="col-12">
                                                    <hr class="my-2">
                                                    <h6 class="fw-bold text-dark mb-2 font-headline">
                                                        <span class="material-symbols-outlined text-primary me-1" style="font-size: 18px; vertical-align: middle;">photo_camera</span>Unggah Dokumentasi Foto Progres Fisik
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
                                                    <textarea name="deskripsi" rows="3" class="form-control form-control-sm font-stitch" placeholder="Catatan mengenai kondisi pengerjaan, kendala material, atau penyaluran dana...">{{ $realisasi?->deskripsi }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top">
                                            <button type="button" class="btn btn-secondary font-stitch" data-bs-dismiss="modal">
                                                <i class="bi bi-x me-1"></i> Batal
                                            </button>
                                            <button type="submit" class="btn btn-primary font-stitch">
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
                                            <h5 class="modal-title fw-bold text-dark mb-0 font-headline">
                                                <span class="material-symbols-outlined text-primary me-2" style="font-size: 22px; vertical-align: middle;">photo_library</span>Dokumentasi Fisik Realisasi: {{ $warga?->nama }}
                                            </h5>
                                            <small class="text-muted font-stitch">{{ $warga?->wilayah?->desa ?? '-' }} | Program: {{ $bantuan->program?->nama_program ?? 'RTLH' }}</small>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4 font-stitch">
                                        <div class="row g-3">
                                            <!-- Foto 0% -->
                                            <div class="col-12 col-sm-6 col-md-3">
                                                <div class="stitch-card h-100 text-center">
                                                    <div class="bg-light py-2 fw-bold text-muted small border-bottom font-stitch">
                                                        <i class="bi bi-circle me-1"></i> Titik Nol (0%)
                                                    </div>
                                                    <div class="p-2 d-flex align-items-center justify-content-center" style="min-height: 200px; background: #fafafa;">
                                                        @if($realisasi?->foto_0)
                                                            <a href="{{ asset('storage/' . $realisasi->foto_0) }}" target="_blank">
                                                                <img src="{{ asset('storage/' . $realisasi->foto_0) }}" class="img-fluid rounded" style="max-height: 190px;" alt="0%">
                                                            </a>
                                                        @else
                                                            <div class="text-muted small py-4">
                                                                <span class="material-symbols-outlined fs-1 d-block mb-1 text-secondary opacity-50">hide_image</span>
                                                                Foto belum diunggah
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Foto 25% -->
                                            <div class="col-12 col-sm-6 col-md-3">
                                                <div class="stitch-card h-100 text-center">
                                                    <div class="py-2 fw-bold small border-bottom font-stitch" style="background: var(--stitch-info-bg); color: var(--stitch-info-text);">
                                                        <i class="bi bi-cone me-1"></i> Progres 25%
                                                    </div>
                                                    <div class="p-2 d-flex align-items-center justify-content-center" style="min-height: 200px; background: #fafafa;">
                                                        @if($realisasi?->foto_25)
                                                            <a href="{{ asset('storage/' . $realisasi->foto_25) }}" target="_blank">
                                                                <img src="{{ asset('storage/' . $realisasi->foto_25) }}" class="img-fluid rounded" style="max-height: 190px;" alt="25%">
                                                            </a>
                                                        @else
                                                            <div class="text-muted small py-4">
                                                                <span class="material-symbols-outlined fs-1 d-block mb-1 text-secondary opacity-50">hide_image</span>
                                                                Foto belum diunggah
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Foto 50% -->
                                            <div class="col-12 col-sm-6 col-md-3">
                                                <div class="stitch-card h-100 text-center">
                                                    <div class="py-2 fw-bold small border-bottom font-stitch" style="background: var(--stitch-warning-bg); color: var(--stitch-warning-text);">
                                                        <i class="bi bi-bricks me-1"></i> Progres 50%
                                                    </div>
                                                    <div class="p-2 d-flex align-items-center justify-content-center" style="min-height: 200px; background: #fafafa;">
                                                        @if($realisasi?->foto_50)
                                                            <a href="{{ asset('storage/' . $realisasi->foto_50) }}" target="_blank">
                                                                <img src="{{ asset('storage/' . $realisasi->foto_50) }}" class="img-fluid rounded" style="max-height: 190px;" alt="50%">
                                                            </a>
                                                        @else
                                                            <div class="text-muted small py-4">
                                                                <span class="material-symbols-outlined fs-1 d-block mb-1 text-secondary opacity-50">hide_image</span>
                                                                Foto belum diunggah
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Foto 100% -->
                                            <div class="col-12 col-sm-6 col-md-3">
                                                <div class="stitch-card h-100 text-center">
                                                    <div class="py-2 fw-bold small border-bottom font-stitch" style="background: var(--stitch-success-bg); color: var(--stitch-success-text);">
                                                        <i class="bi bi-house-check-fill me-1"></i> Selesai (100%)
                                                    </div>
                                                    <div class="p-2 d-flex align-items-center justify-content-center" style="min-height: 200px; background: #fafafa;">
                                                        @if($realisasi?->foto_100)
                                                            <a href="{{ asset('storage/' . $realisasi->foto_100) }}" target="_blank">
                                                                <img src="{{ asset('storage/' . $realisasi->foto_100) }}" class="img-fluid rounded" style="max-height: 190px;" alt="100%">
                                                            </a>
                                                        @else
                                                            <div class="text-muted small py-4">
                                                                <span class="material-symbols-outlined fs-1 d-block mb-1 text-secondary opacity-50">hide_image</span>
                                                                Foto belum diunggah
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if($realisasi?->deskripsi)
                                            <div class="bg-light p-3 rounded mt-3 border">
                                                <strong class="text-dark d-block mb-1 font-headline"><i class="bi bi-info-circle text-primary me-1"></i> Catatan Pelaksanaan:</strong>
                                                <p class="text-muted small mb-0">{{ $realisasi->deskripsi }}</p>
                                            </div>
                                        @endif

                                        @if($realisasi?->kwitansi)
                                            <div class="mt-3 text-end">
                                                <a href="{{ asset('storage/' . $realisasi->kwitansi) }}" target="_blank" class="btn btn-sm btn-outline-success font-stitch d-inline-flex align-items-center gap-1">
                                                    <span class="material-symbols-outlined" style="font-size: 16px;">download</span>
                                                    <span>Unduh / Buka Berkas SPJ &amp; Kuitansi</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="modal-footer border-top">
                                        <button type="button" class="btn btn-secondary btn-sm font-stitch" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ================= END MODAL GALERI ================= -->

                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="py-4">
                                    <span class="material-symbols-outlined text-muted mb-2" style="font-size: 44px;">engineering</span>
                                    <h6 class="fw-bold text-dark mb-1 font-headline">Tidak ada data penerima bantuan terverifikasi</h6>
                                    <p class="text-muted small mb-3 font-stitch">Belum ada bantuan dengan status verifikasi disetujui atau sesuai filter yang dipilih.</p>
                                    <a href="{{ route('spk.index') }}" class="btn btn-sm btn-primary px-3 shadow-sm font-stitch">
                                        <span class="material-symbols-outlined me-1" style="font-size: 16px; vertical-align: middle;">award_star</span> Buka Menu SPK &amp; Verifikasi Dinas
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer Versi Stitch -->
        @if($bantuans->hasPages())
            <div class="p-3 bg-light border-top d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                <span class="font-stitch text-muted font-mono-num" style="font-size: 12.5px;">
                    Menampilkan <strong>{{ $bantuans->firstItem() }}</strong>–<strong>{{ $bantuans->lastItem() }}</strong> dari <strong>{{ $bantuans->total() }}</strong> penerima
                </span>
                <div>
                    {{ $bantuans->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
