@extends('app')

@section('title', 'Calon Penerima Bantuan Disetujui Dinas')

@section('content')
@php
    $isDinas = Auth::user()->isSuperadmin() || Auth::user()->isAdminDinas();
    $wilayahs = $wilayahs ?? \App\Models\Wilayah::orderBy('desa')->get();
    $programs = $programs ?? \App\Models\ProgramBantuan::orderBy('nama_program')->get();
@endphp

<div class="page-heading">
    <!-- Header Halaman Versi Stitch -->
    <div class="d-flex flex-column flex-md-row md:items-start justify-content-between gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1 font-headline" style="color: var(--stitch-primary);">
                <span class="material-symbols-outlined me-2 text-success" style="font-size: 28px; vertical-align: middle;">verified</span>Calon Penerima Bantuan Disetujui Dinas
            </h3>
            <p class="text-muted mb-0 font-stitch" style="font-size: 13.5px;">
                Daftar calon penerima yang telah ditetapkan lolos verifikasi Dinas (<strong>Disetujui Dinas</strong>) dan siap memasuki tahap penyaluran. Data berpindah ke Realisasi setelah progres fisik berjalan.
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2 shrink-0">
            <a href="{{ route('warga.export', array_merge(request()->query(), ['status_bantuan' => \App\Models\Bantuan::STATUS_DISETUJUI])) }}" class="btn btn-sm btn-success shadow-sm px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1 font-stitch" title="Unduh Daftar Penerima Disetujui (.xls)" style="font-size: 12.5px; border-radius: 8px;">
                <span class="material-symbols-outlined" style="font-size: 17px;">file_download</span>
                <span>Ekspor Excel</span>
            </a>

            @if($isDinas)
                <a href="{{ route('realisasi.index') }}" class="btn btn-sm btn-primary shadow-sm px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1 font-stitch" style="font-size: 12.5px; border-radius: 8px;">
                    <span class="material-symbols-outlined" style="font-size: 17px;">engineering</span>
                    <span>Realisasi Bantuan</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Alert Notifikasi Flash -->
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

    @if(!$isDinas)
        <div class="alert alert-light-info border shadow-sm mb-4 font-stitch" style="font-size: 13px; background: #EFF6FF; border-color: #BFDBFE !important;">
            <div class="d-flex align-items-center">
                <span class="material-symbols-outlined text-primary fs-5 me-2">lock</span>
                <span class="text-dark mb-0">
                    <strong>Mode Lihat Saja (View-Only):</strong> Data penerima di halaman ini sudah ditetapkan oleh Dinas dan terkunci dari pengubahan oleh pihak Kelurahan.
                </span>
            </div>
        </div>
    @endif

    <!-- 4 KPI Summary Cards (Telah Dirapikan & Diselaraskan) -->
    <div class="row g-3 mb-4">
        <!-- KPI 1: Total Penerima Disetujui -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stitch-kpi-card">
                <div class="stitch-kpi-icon" style="background: var(--stitch-success-bg); color: var(--stitch-success-text);">
                    <span class="material-symbols-outlined">verified</span>
                </div>
                <div class="min-w-0">
                    <div class="stitch-kpi-label">Total Penerima Disetujui</div>
                    <div class="stitch-kpi-val font-mono-num" style="font-size: 22px; color: var(--stitch-success-text);">{{ $wargas->total() }}</div>
                    <small class="text-muted font-stitch" style="font-size: 11px;">Penetapan resmi SK Dinas</small>
                </div>
            </div>
        </div>

        <!-- KPI 2: Rumah Milik Sendiri -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stitch-kpi-card">
                <div class="stitch-kpi-icon" style="background: var(--stitch-info-bg); color: var(--stitch-info-text);">
                    <span class="material-symbols-outlined">home</span>
                </div>
                <div class="min-w-0">
                    <div class="stitch-kpi-label">Rumah Milik Sendiri</div>
                    <div class="stitch-kpi-val font-mono-num" style="font-size: 22px;">
                        {{ $wargas->filter(fn($w) => $w->Hunian->first()?->status_rumah == 1)->count() }}
                    </div>
                    <small class="text-muted font-stitch" style="font-size: 11px;">Kepemilikan pribadi</small>
                </div>
            </div>
        </div>

        <!-- KPI 3: Kondisi Rusak Berat -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stitch-kpi-card">
                <div class="stitch-kpi-icon" style="background: var(--stitch-error-bg); color: var(--stitch-error-text);">
                    <span class="material-symbols-outlined">construction</span>
                </div>
                <div class="min-w-0">
                    <div class="stitch-kpi-label">Kondisi Rusak Berat</div>
                    <div class="stitch-kpi-val font-mono-num" style="font-size: 22px; color: var(--stitch-error-text);">
                        {{ $wargas->filter(fn($w) => $w->Hunian->first()?->Rumah->first()?->kondisi_atap == 3 || $w->Hunian->first()?->Rumah->first()?->kondisi_dinding == 3 || $w->Hunian->first()?->Rumah->first()?->kondisi_lantai == 3)->count() }}
                    </div>
                    <small class="text-muted font-stitch" style="font-size: 11px;">Prioritas penanganan fisik</small>
                </div>
            </div>
        </div>

        <!-- KPI 4: Sudah Memilih Program -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stitch-kpi-card">
                <div class="stitch-kpi-icon" style="background: var(--stitch-warning-bg); color: var(--stitch-warning-text);">
                    <span class="material-symbols-outlined">card_giftcard</span>
                </div>
                <div class="min-w-0">
                    <div class="stitch-kpi-label">Sudah Memilih Program</div>
                    <div class="stitch-kpi-val font-mono-num" style="font-size: 22px;">
                        {{ $wargas->filter(fn($w) => $w->bantuanAktif?->program !== null)->count() }}
                    </div>
                    <small class="text-muted font-stitch" style="font-size: 11px;">Alokasi jenis bantuan aktif</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Bar Versi Toolbar Stitch -->
    <div class="stitch-card mb-4">
        <div class="stitch-toolbar">
            <form method="GET" action="{{ route('warga.disetujui') }}">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <span class="material-symbols-outlined" style="font-size: 16px;">search</span>
                            </span>
                            <input type="text" name="search" class="form-control font-stitch" style="font-size: 12.5px;" placeholder="Cari NIK / Nama / KK..." value="{{ request('search') }}">
                        </div>
                    </div>

                    @if($isDinas)
                        <div class="col-12 col-md-3">
                            <select name="id_wilayah" class="form-select form-select-sm font-stitch" style="font-size: 12.5px;" onchange="this.form.submit()">
                                <option value="">Semua Kelurahan</option>
                                @foreach($wilayahs as $wilayah)
                                    <option value="{{ $wilayah->id_wilayah }}" {{ request('id_wilayah') == $wilayah->id_wilayah ? 'selected' : '' }}>
                                        Kel. {{ $wilayah->desa }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-4">
                            <select name="id_program" class="form-select form-select-sm font-stitch" style="font-size: 12.5px;" onchange="this.form.submit()">
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
                            <select name="id_program" class="form-select form-select-sm font-stitch" style="font-size: 12.5px;" onchange="this.form.submit()">
                                <option value="">Semua Jenis Bantuan</option>
                                @foreach($programs as $prog)
                                    <option value="{{ $prog->id_program }}" {{ request('id_program') == $prog->id_program ? 'selected' : '' }}>
                                        {{ $prog->nama_program }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 col-md-3">
                            <select name="status_rumah" class="form-select form-select-sm font-stitch" style="font-size: 12.5px;" onchange="this.form.submit()">
                                <option value="">Semua Status Kepemilikan</option>
                                <option value="1" {{ request('status_rumah') == '1' ? 'selected' : '' }}>Milik Sendiri</option>
                                <option value="2" {{ request('status_rumah') == '2' ? 'selected' : '' }}>Sewa / Kontrak</option>
                                <option value="3" {{ request('status_rumah') == '3' ? 'selected' : '' }}>Menumpang</option>
                            </select>
                        </div>
                    @endif

                    <div class="col-12 col-md-1 d-flex gap-1 justify-content-end">
                        <button type="submit" class="btn btn-sm btn-primary px-3 fw-semibold d-inline-flex align-items-center gap-1 font-stitch" style="font-size: 12.5px;" title="Terapkan Filter">
                            <span class="material-symbols-outlined" style="font-size: 15px;">filter_list</span>
                        </button>
                        @if(request()->hasAny(['search', 'id_wilayah', 'id_program', 'status_rumah']))
                            <a href="{{ route('warga.disetujui') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center" title="Reset Filter">
                                <span class="material-symbols-outlined" style="font-size: 15px;">restart_alt</span>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Navigasi 3 Tab Model Stitch -->
    <div class="mb-3">
        <div class="stitch-tablist" role="tablist">
            <button class="stitch-tab-trigger active" id="tab-master" data-bs-toggle="pill" data-bs-target="#content-master" type="button" role="tab">
                <span class="material-symbols-outlined">verified</span>
                <span>1. Daftar Penerima Disetujui</span>
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

    <!-- Card Utama Tabel Versi Stitch -->
    <div class="stitch-card">
        <!-- Header Tabel & Live Badge Counter -->
        <div class="p-3 bg-white border-bottom d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
            <div>
                <h5 class="fw-bold text-dark mb-0 font-headline">Daftar Calon Penerima Disetujui Dinas</h5>
                <small class="text-muted font-stitch">
                    @if($isDinas)
                        Penerima yang telah disetujui Dinas (siap realisasi). Gunakan tombol aksi untuk meninjau detail atau mengubah keputusan bila diperlukan.
                    @else
                        Daftar warga di Kelurahan {{ Auth::user()->wilayah->desa ?? '' }} yang telah disetujui Dinas untuk menerima bantuan RTLH.
                    @endif
                </small>
            </div>
            <div class="stitch-header-badge font-stitch">
                <span class="dot"></span>
                <span>Total: <strong class="font-mono-num">{{ $wargas->total() }}</strong> Penerima</span>
            </div>
        </div>

        <div class="tab-content" id="tableTabsDisetujuiContent">
            <!-- TAB 1: DAFTAR PENERIMA DISETUJUI -->
            <div class="tab-pane fade show active" id="content-master" role="tabpanel">
                <div class="table-responsive">
                    <table class="stitch-table">
                        <thead>
                            <tr>
                                <th class="ps-3" style="width: 50px;">No</th>
                                @if($isDinas)
                                    <th style="width: 130px;">Peringkat SAW</th>
                                @endif
                                <th>Penerima Bantuan (Warga)</th>
                                <th>Wilayah &amp; Alamat</th>
                                <th>Jenis &amp; Nominal Bantuan</th>
                                <th>Status &amp; Verifikator</th>
                                <th class="text-end pe-3" style="width: 140px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($wargas as $index => $warga)
                                @php
                                    $bantuan = $warga->bantuanAktif;
                                    $ranking = $bantuan?->spkRanking;
                                @endphp
                                <tr>
                                    <td class="ps-3 tabular-nums font-mono-num text-muted">
                                        {{ $wargas->firstItem() + $index }}
                                    </td>

                                    @if($isDinas)
                                        <td>
                                            @if($ranking)
                                                <button type="button" class="btn btn-sm btn-light border fw-bold text-primary py-0 px-2 font-mono-num d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#modalDetailSAW{{ $warga->id_warga }}" title="Lihat Rincian Perhitungan SAW" style="border-radius: 6px; font-size: 11.5px;">
                                                    <span class="material-symbols-outlined" style="font-size: 14px;">calculate</span>
                                                    <span>#{{ $ranking->ranking }} ({{ number_format($ranking->skor_akhir, 3) }})</span>
                                                </button>
                                            @else
                                                <span class="badge bg-light text-muted border font-stitch" style="font-size: 11px;">Belum Dirangking</span>
                                            @endif
                                        </td>
                                    @endif

                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <a href="{{ route('warga.show', ['warga' => $warga->id_warga, 'from' => 'disetujui']) }}" class="fw-bold text-dark text-decoration-none font-stitch" style="font-size: 13.5px;">
                                                {{ $warga->nama }}
                                            </a>
                                            <span class="font-mono-num text-muted">NIK: {{ $warga->nik }}</span>
                                            @if($warga->no_kk)
                                                <span class="font-mono-num text-muted">KK: {{ $warga->no_kk }}</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column gap-1 font-stitch">
                                            <span class="text-dark fw-semibold" style="font-size: 13px;">Kel. {{ $warga->wilayah->desa ?? '-' }}</span>
                                            <small class="text-muted" style="font-size: 11.5px;">
                                                {{ $warga->alamat ?? 'Alamat belum diisi' }} (RT {{ $warga->rt ?? '-' }} / RW {{ $warga->rw ?? '-' }})
                                            </small>
                                            <small class="text-secondary" style="font-size: 11px;">Kec. {{ $warga->wilayah->kecamatan ?? 'Kediri' }}</small>
                                        </div>
                                    </td>

                                    <td>
                                        @if($bantuan && $bantuan->program)
                                            <div class="d-flex flex-column align-items-start gap-1">
                                                <span class="badge rounded-pill font-stitch" style="background: var(--stitch-info-bg); color: var(--stitch-info-text); font-weight: 600; font-size: 11.5px;">
                                                    <span class="material-symbols-outlined me-1" style="font-size: 13px; vertical-align: middle;">card_giftcard</span>{{ $bantuan->program->nama_program }}
                                                </span>
                                                @if($bantuan->program->nominal_bantuan)
                                                    <span class="font-mono-num fw-semibold" style="color: var(--stitch-success-text); font-size: 12px;">
                                                        Rp {{ number_format($bantuan->program->nominal_bantuan, 0, ',', '.') }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="badge bg-light text-muted border font-stitch" style="font-size: 11px;">Belum Ditentukan</span>
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
                                            <a href="{{ route('warga.show', ['warga' => $warga->id_warga, 'from' => 'disetujui']) }}" class="stitch-btn-icon" title="Lihat Detail {{ $warga->nama }}">
                                                <span class="material-symbols-outlined" style="font-size: 17px;">visibility</span>
                                            </a>

                                            <!-- Verifikasi Ulang (Dinas) -->
                                            @if($isDinas && $bantuan)
                                                <button type="button" class="stitch-btn-icon" data-bs-toggle="modal" data-bs-target="#modalVerifikasiWarga{{ $bantuan->id_bantuan }}" title="Ubah Keputusan Verifikasi Dinas">
                                                    <span class="material-symbols-outlined" style="font-size: 17px; color: var(--stitch-primary);">fact_check</span>
                                                </button>
                                            @endif

                                            <!-- Edit -->
                                            @can('update', $warga)
                                                <a href="{{ route('warga.edit', ['warga' => $warga->id_warga, 'from' => 'disetujui']) }}" class="stitch-btn-edit" title="Edit Data {{ $warga->nama }}">
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
                                    <td colspan="{{ $isDinas ? '7' : '6' }}" class="text-center py-5">
                                        <div class="py-4">
                                            <span class="material-symbols-outlined text-muted mb-2" style="font-size: 44px;">verified</span>
                                            <h6 class="fw-bold text-dark mb-1 font-headline">Belum ada calon penerima berstatus Disetujui Dinas</h6>
                                            <p class="text-muted small mb-0 font-stitch">Data akan tampil di halaman ini setelah usulan disetujui pada menu Verifikasi &amp; SPK (dan belum selesai direalisasikan 100%).</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 2 & TAB 3: Kepemilikan & Survei Fisik -->
            @include('pages.warga.partials._tabs_survei', ['wargas' => $wargas])
        </div>

        <!-- Pagination Footer Versi Stitch -->
        @if($wargas->hasPages())
            <div class="p-3 bg-light border-top d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                <span class="font-stitch text-muted font-mono-num" style="font-size: 12.5px;">
                    Menampilkan <strong>{{ $wargas->firstItem() }}</strong>–<strong>{{ $wargas->lastItem() }}</strong> dari <strong>{{ $wargas->total() }}</strong> penerima disetujui
                </span>
                <div>
                    {{ $wargas->links() }}
                </div>
            </div>
        @endif
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
