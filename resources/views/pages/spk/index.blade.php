@extends('app')

@section('title', 'Verifikasi Dinas & Perankingan SPK (SAW)')

@section('content')
<div class="page-heading">
    <!-- Header Halaman Versi Stitch -->
    <div class="d-flex flex-column flex-md-row md:items-start justify-content-between gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1 font-headline" style="color: var(--stitch-primary); text-shadow: 0 1px 2px rgba(255, 255, 255, 0.95);">
                <span class="material-symbols-outlined me-2 text-warning" style="font-size: 28px; vertical-align: middle;">award_star</span>Verifikasi Dinas &amp; Perankingan SPK (SAW)
            </h3>
            @if($isDinas)
                <p class="text-muted mb-0 font-stitch" style="font-size: 13.5px; color: #0F172A; font-weight: 500; text-shadow: 0 1px 2px rgba(255, 255, 255, 0.9);">
                    Evaluasi prioritas calon penerima berstatus <strong style="color: #001D4E;">Diverifikasi Lapangan</strong> Tahun Anggaran <strong style="color: #001D4E;">{{ $tahun }}</strong> menggunakan metode <strong style="color: #001D4E;">Simple Additive Weighting (SAW)</strong> serta penetapan keputusan Dinas.
                </p>
            @else
                <p class="text-muted mb-0 font-stitch" style="font-size: 13.5px; color: #0F172A; font-weight: 500; text-shadow: 0 1px 2px rgba(255, 255, 255, 0.9);">
                    Pantau peringkat prioritas SPK SAW untuk usulan berstatus <strong style="color: #001D4E;">Diverifikasi Lapangan</strong> Tahun Anggaran <strong style="color: #001D4E;">{{ $tahun }}</strong> di wilayah <strong style="color: #001D4E;">{{ Auth::user()->wilayah->desa ?? 'Kelurahan' }}</strong>.
                </p>
            @endif
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2 shrink-0">
            @if($isDinas)
                @if($kriterias->isNotEmpty())
                    <button type="button" class="btn btn-sm btn-outline-primary bg-white shadow-sm px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1 font-stitch" data-bs-toggle="modal" data-bs-target="#modalKriteriaSAW" style="font-size: 12.5px; border-radius: 8px;">
                        <span class="material-symbols-outlined" style="font-size: 17px;">tune</span>
                        <span>Bobot Kriteria</span>
                    </button>
                @endif

                <form action="{{ route('spk.hitung') }}" method="POST" class="d-inline" onsubmit="return confirm('Jalankan kalkulasi ulang perankingan SAW untuk data terverifikasi/disetujui tahun {{ $tahun }}?');">
                    @csrf
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    @if(request()->filled('id_program'))
                        <input type="hidden" name="id_program" value="{{ request('id_program') }}">
                    @endif
                    <button type="submit" class="btn btn-sm btn-warning text-dark shadow-sm px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1 font-stitch" title="Perbarui Peringkat SAW" style="font-size: 12.5px; border-radius: 8px;">
                        <span class="material-symbols-outlined" style="font-size: 17px;">refresh</span>
                        <span>Hitung Ulang SAW</span>
                    </button>
                </form>
            @endif

            <a href="{{ route('warga.index') }}" class="btn btn-sm btn-outline-secondary bg-white shadow-sm px-3 py-2 fw-semibold d-inline-flex align-items-center gap-1 font-stitch" style="font-size: 12.5px; border-radius: 8px;">
                <span class="material-symbols-outlined" style="font-size: 17px;">arrow_back</span>
                <span>Daftar Usulan</span>
            </a>
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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible show fade shadow-sm mb-4">
            <div class="d-flex align-items-start font-stitch" style="font-size: 13.5px;">
                <span class="material-symbols-outlined fs-4 me-2 text-danger">warning</span>
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

    @if(!$isDinas)
        <div class="alert alert-light-info border shadow-sm mb-4 font-stitch" style="font-size: 13px; background: #EFF6FF; border-color: #BFDBFE !important;">
            <div class="d-flex align-items-center">
                <span class="material-symbols-outlined text-primary fs-5 me-2">visibility</span>
                <span class="text-dark mb-0">
                    <strong>Mode Pantau Kelurahan:</strong> Anda dapat melihat hasil perankingan SPK SAW, rincian skor <code>C1</code>–<code>C5</code>, dan catatan verifikasi Dinas untuk usulan di kelurahan Anda.
                </span>
            </div>
        </div>
    @endif

    <!-- 4 Kartu Statistik KPI Ringkasan Data (Telah Dirapikan) -->
    <div class="row g-3 mb-4">
        <!-- KPI 1: Kandidat Diverifikasi -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stitch-kpi-card">
                <div class="stitch-kpi-icon" style="background: var(--stitch-info-bg); color: var(--stitch-info-text);">
                    <span class="material-symbols-outlined">person_check</span>
                </div>
                <div class="min-w-0">
                    <div class="stitch-kpi-label">Kandidat Diverifikasi ({{ $tahun }})</div>
                    <div class="stitch-kpi-val font-mono-num" style="font-size: 22px;">{{ $totalKandidat }}</div>
                    <small class="text-muted font-stitch" style="font-size: 11px;">Status siap dirangking</small>
                </div>
            </div>
        </div>

        <!-- KPI 2: Sudah Diranking SAW -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stitch-kpi-card">
                <div class="stitch-kpi-icon" style="background: var(--stitch-warning-bg); color: var(--stitch-warning-text);">
                    <span class="material-symbols-outlined">award_star</span>
                </div>
                <div class="min-w-0">
                    <div class="stitch-kpi-label">Sudah Diranking SAW</div>
                    <div class="stitch-kpi-val font-mono-num" style="font-size: 22px;">{{ $totalSudahRanking }}</div>
                    @if($totalBelumRanking > 0)
                        <small class="text-danger font-stitch" style="font-size: 11px;">{{ $totalBelumRanking }} belum diranking</small>
                    @else
                        <small class="text-success font-stitch" style="font-size: 11px;">Semua terproses</small>
                    @endif
                </div>
            </div>
        </div>

        <!-- KPI 3: Disetujui Dinas -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stitch-kpi-card">
                <div class="stitch-kpi-icon" style="background: var(--stitch-success-bg); color: var(--stitch-success-text);">
                    <span class="material-symbols-outlined">verified</span>
                </div>
                <div class="min-w-0">
                    <div class="stitch-kpi-label">Disetujui Dinas ({{ $tahun }})</div>
                    <div class="stitch-kpi-val font-mono-num" style="font-size: 22px; color: var(--stitch-success-text);">{{ $totalDisetujui }}</div>
                    <small class="text-muted font-stitch" style="font-size: 11px;">Lolos penetapan dinas</small>
                </div>
            </div>
        </div>

        <!-- KPI 4: Rata-rata Skor SAW -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stitch-kpi-card">
                <div class="stitch-kpi-icon" style="background: #F1F5F9; color: var(--stitch-primary);">
                    <span class="material-symbols-outlined">analytics</span>
                </div>
                <div class="min-w-0">
                    <div class="stitch-kpi-label">Rata-rata Skor SAW</div>
                    <div class="stitch-kpi-val font-mono-num" style="font-size: 20px; color: var(--stitch-primary);">{{ number_format((float) $avgSkor, 4) }}</div>
                    <small class="text-muted font-stitch" style="font-size: 11px;">Nilai preferensi (V)</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian SPK Versi Toolbar Stitch -->
    <div class="stitch-card mb-4">
        <div class="stitch-toolbar">
            <form method="GET" action="{{ route('spk.index') }}">
                <div class="row g-2 align-items-center">
                    <!-- Search input -->
                    <div class="col-12 col-md-{{ $isDinas ? '3' : '5' }}">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <span class="material-symbols-outlined" style="font-size: 16px;">search</span>
                            </span>
                            <input type="text" name="search" class="form-control font-stitch" style="font-size: 12.5px;" placeholder="Cari NIK / Nama / KK..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <!-- Tahun Anggaran -->
                    <div class="col-12 col-md-2">
                        <select name="tahun" class="form-select form-select-sm font-stitch" style="font-size: 12.5px;" onchange="this.form.submit()">
                            @foreach($availableYears as $yr)
                                <option value="{{ $yr }}" {{ (int) $tahun === (int) $yr ? 'selected' : '' }}>
                                    Tahun {{ $yr }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Kelurahan (Khusus Dinas) -->
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
                    @endif

                    <!-- Filter Jenis Bantuan -->
                    <div class="col-12 col-md-{{ $isDinas ? '3' : '4' }}">
                        <select name="id_program" class="form-select form-select-sm font-stitch" style="font-size: 12.5px;" onchange="this.form.submit()">
                            <option value="">Semua Jenis Bantuan</option>
                            @foreach($programs as $prog)
                                <option value="{{ $prog->id_program }}" {{ request('id_program') == $prog->id_program ? 'selected' : '' }}>
                                    {{ $prog->nama_program }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-1 d-flex gap-1 justify-content-end">
                        <button type="submit" class="btn btn-sm btn-primary px-3 fw-semibold d-inline-flex align-items-center gap-1 font-stitch" style="font-size: 12.5px;" title="Terapkan Filter">
                            <span class="material-symbols-outlined" style="font-size: 15px;">filter_list</span>
                        </button>
                        @if(request()->hasAny(['search', 'id_wilayah', 'id_program']) || (int) $tahun !== (int) date('Y'))
                            <a href="{{ route('spk.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center" title="Reset Filter">
                                <span class="material-symbols-outlined" style="font-size: 15px;">restart_alt</span>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Card Utama Tabel SPK SAW Versi Stitch -->
    <div class="stitch-card">
        <!-- Header Tabel & Live Badge Counter -->
        <div class="p-3 bg-white border-bottom d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
            <div>
                <h5 class="fw-bold text-dark mb-0 font-headline">
                    Matriks Perankingan SAW &amp; Keputusan Verifikasi
                </h5>
                <small class="text-muted font-stitch">
                    @if($isDinas)
                        Usulan berstatus <strong>Diverifikasi Lapangan</strong> Tahun {{ $tahun }}. Klik <strong>Validasi Bantuan</strong> untuk menetapkan keputusan Dinas.
                    @else
                        Peringkat SAW usulan berstatus <strong>Diverifikasi Lapangan</strong> di Kelurahan {{ Auth::user()->wilayah->desa ?? '' }} Tahun {{ $tahun }}.
                    @endif
                </small>
            </div>
            <div class="stitch-header-badge font-stitch">
                <span class="dot"></span>
                <span>Total: <strong class="font-mono-num">{{ $wargas->total() }}</strong> Kandidat</span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="stitch-table">
                <thead>
                    <tr>
                        <th class="ps-3 text-center" style="width: 65px;">Rank</th>
                        <th>Calon Penerima &amp; Wilayah</th>
                        <th class="text-center" title="Kondisi Fisik Bangunan">C1 (Fisik)</th>
                        <th class="text-center" title="Luas Lantai per Orang">C2 (Luas)</th>
                        <th class="text-center" title="Sanitasi &amp; Air Minum">C3 (Sanitasi)</th>
                        <th class="text-center" title="Desil DTSEN">C4 (DTSEN)</th>
                        <th class="text-center" title="Penghasilan">C5 (Ekonomi)</th>
                        <th class="text-center" style="width: 130px;">Skor SAW (V<sub>i</sub>)</th>
                        <th>Status Verifikasi</th>
                        <th class="text-end pe-3" style="width: 170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($wargas as $warga)
                        @php
                            $bantuan = $warga->bantuanAktif;
                            $rank = $bantuan?->spkRanking;
                            $rincian = $rank?->rincian_kriteria ?? [];
                        @endphp
                        <tr>
                            <td class="ps-3 text-center">
                                @if($rank)
                                    @if($rank->ranking == 1)
                                        <span class="badge rounded-circle p-2 bg-warning text-dark fw-bold font-mono-num shadow-sm" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px;">#1</span>
                                    @elseif($rank->ranking == 2)
                                        <span class="badge rounded-circle p-2 bg-secondary text-white fw-bold font-mono-num shadow-sm" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px;">#2</span>
                                    @elseif($rank->ranking == 3)
                                        <span class="badge rounded-circle p-2 bg-danger text-white fw-bold font-mono-num shadow-sm" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; font-size: 12px;">#3</span>
                                    @else
                                        <span class="badge bg-light text-dark border fw-bold font-mono-num" style="font-size: 11px;">#{{ $rank->ranking }}</span>
                                    @endif
                                @else
                                    <span class="text-muted font-mono-num">-</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <a href="{{ route('warga.show', ['warga' => $warga->id_warga, 'from' => 'spk']) }}" class="fw-bold text-dark text-decoration-none font-stitch" style="font-size: 13.5px;">
                                        {{ $warga->nama }}
                                    </a>
                                    <span class="font-mono-num text-muted">NIK: {{ $warga->nik }} &middot; Kel. {{ $warga->wilayah->desa ?? '-' }}</span>
                                    @if($bantuan && $bantuan->program)
                                        <span class="badge rounded-pill font-stitch w-fit" style="background: var(--stitch-info-bg); color: var(--stitch-info-text); font-size: 11px; font-weight: 600;">
                                            <span class="material-symbols-outlined me-1" style="font-size: 12px; vertical-align: middle;">card_giftcard</span>{{ $bantuan->program->nama_program }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="text-center">
                                <span class="badge bg-light text-dark border font-mono-num">{{ $rincian['C1']['mentah'] ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border font-mono-num">{{ isset($rincian['C2']['mentah']) ? $rincian['C2']['mentah'] . ' m²' : '-' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border font-mono-num">{{ $rincian['C3']['mentah'] ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border font-mono-num">{{ $rincian['C4']['mentah'] ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border font-mono-num">{{ $rincian['C5']['mentah'] ?? '-' }}</span>
                            </td>

                            <td class="text-center">
                                @if($rank)
                                    <div class="fw-bold font-mono-num" style="color: var(--stitch-primary); font-size: 13px;">{{ number_format($rank->skor_akhir, 4) }}</div>
                                    <div class="progress mt-1 mx-auto" style="height: 4px; max-width: 90px; background-color: #E2E8F0;">
                                        <div class="progress-bar" style="background-color: var(--stitch-primary); width: {{ min(100, $rank->skor_akhir * 100) }}%"></div>
                                    </div>
                                @else
                                    <span class="text-muted font-stitch" style="font-size: 11px;">Belum diranking</span>
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
                                    <a href="{{ route('warga.show', ['warga' => $warga->id_warga, 'from' => 'spk']) }}" class="stitch-btn-icon" title="Lihat Detail {{ $warga->nama }}">
                                        <span class="material-symbols-outlined" style="font-size: 17px;">visibility</span>
                                    </a>

                                    <!-- Detail Matriks SAW -->
                                    @if($rank)
                                        <button type="button" class="stitch-btn-icon" data-bs-toggle="modal" data-bs-target="#modalDetailSAW{{ $warga->id_warga }}" title="Rincian Perhitungan SAW">
                                            <span class="material-symbols-outlined" style="font-size: 17px;">calculate</span>
                                        </button>
                                    @endif

                                    <!-- Edit -->
                                    @can('update', $warga)
                                        <a href="{{ route('warga.edit', ['warga' => $warga->id_warga, 'from' => 'spk']) }}" class="stitch-btn-edit" title="Edit / Perbaiki Usulan">
                                            <span class="material-symbols-outlined" style="font-size: 16px;">edit</span>
                                        </a>
                                    @endcan

                                    <!-- Validasi Bantuan (Dinas) -->
                                    @if($isDinas && $bantuan)
                                        <button type="button" class="stitch-btn-verify" data-bs-toggle="modal" data-bs-target="#modalValidasiBantuan{{ $bantuan->id_bantuan }}" title="Validasi &amp; Tetapkan Bantuan">
                                            <span class="material-symbols-outlined" style="font-size: 15px;">check_circle</span>
                                            <span>Validasi</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <div class="py-4">
                                    <span class="material-symbols-outlined text-muted mb-2" style="font-size: 44px;">award_star</span>
                                    <h6 class="fw-bold text-dark mb-1 font-headline">Belum ada kandidat berstatus Diverifikasi Lapangan</h6>
                                    <p class="text-muted small mb-0 font-stitch">
                                        Usulan berstatus <em>Menunggu Verifikasi</em> perlu diverifikasi terlebih dahulu melalui menu <a href="{{ route('warga.index') }}" class="fw-semibold text-decoration-none">Daftar Usulan</a> sebelum masuk ke tahap perankingan SPK SAW.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer Versi Stitch -->
        @if($wargas->hasPages())
            <div class="p-3 bg-light border-top d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                <span class="font-stitch text-muted font-mono-num" style="font-size: 12.5px;">
                    Menampilkan <strong>{{ $wargas->firstItem() }}</strong>–<strong>{{ $wargas->lastItem() }}</strong> dari <strong>{{ $wargas->total() }}</strong> kandidat
                </span>
                <div>
                    {{ $wargas->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modals Validasi Bantuan Dinas & Detail SAW -->
@foreach($wargas as $warga)
    @php
        $bantuan = $warga->bantuanAktif;
        $rank = $bantuan?->spkRanking;
    @endphp
    @if($isDinas && $bantuan)
        @include('pages.spk.partials._modal_validasi_bantuan', [
            'bantuan' => $bantuan,
            'warga' => $warga,
            'rank' => $rank,
            'programs' => $programs,
        ])
    @endif
    @if($rank)
        @include('pages.warga.partials._modal_detail_saw', ['warga' => $warga, 'rank' => $rank])
    @endif
@endforeach

<!-- Modal Konfigurasi Bobot Kriteria SAW (Khusus Dinas & Superadmin) -->
@if($isDinas && $kriterias->isNotEmpty())
<div class="modal fade" id="modalKriteriaSAW" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('spk.kriteria.update') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-dark font-headline">
                        <span class="material-symbols-outlined text-primary me-2" style="font-size: 20px; vertical-align: middle;">tune</span>Konfigurasi Bobot Kriteria SPK (SAW)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 font-stitch">
                    <div class="alert alert-info small mb-3">
                        Total akumulasi seluruh bobot kriteria harus bernilai tepat <strong>1.00</strong> (100%). Setelah mengubah bobot, jalankan kembali <strong>Hitung Ulang SAW</strong>.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0 font-stitch">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 80px;">Kode</th>
                                    <th>Nama Kriteria</th>
                                    <th style="width: 100px;">Sifat</th>
                                    <th style="width: 150px;">Bobot (0.00 - 1.00)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kriterias as $kriteria)
                                    <tr>
                                        <td class="fw-bold text-center font-mono-num">{{ $kriteria->kode }}</td>
                                        <td>
                                            <div class="fw-semibold text-dark">{{ $kriteria->nama_kriteria }}</div>
                                            @if($kriteria->keterangan)
                                                <small class="text-muted">{{ $kriteria->keterangan }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $kriteria->jenis === 'benefit' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ strtoupper($kriteria->jenis) }}
                                            </span>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" max="1" name="bobot[{{ $kriteria->kode }}]" class="form-control form-control-sm input-bobot-saw text-end fw-semibold font-mono-num" value="{{ number_format((float) $kriteria->bobot, 2, '.', '') }}" required>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">Total Akumulasi Bobot:</th>
                                    <th class="text-end">
                                        <span id="totalBobotSaw" class="fw-bold text-primary font-mono-num">{{ number_format((float) $kriterias->sum('bobot'), 2, '.', '') }}</span>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm font-stitch" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm font-stitch">
                        <span class="material-symbols-outlined me-1" style="font-size: 16px; vertical-align: middle;">save</span>
                        <span>Simpan Konfigurasi Bobot</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    (function () {
        var inputs = document.querySelectorAll('.input-bobot-saw');
        var totalEl = document.getElementById('totalBobotSaw');
        if (inputs.length && totalEl) {
            function updateTotalBobot() {
                var sum = 0;
                inputs.forEach(function (el) {
                    var val = parseFloat(el.value);
                    if (!isNaN(val)) sum += val;
                });
                totalEl.textContent = sum.toFixed(2);
                if (Math.abs(sum - 1.0) <= 0.001) {
                    totalEl.classList.remove('text-danger');
                    totalEl.classList.add('text-primary');
                } else {
                    totalEl.classList.remove('text-primary');
                    totalEl.classList.add('text-danger');
                }
            }
            inputs.forEach(function (el) {
                el.addEventListener('input', updateTotalBobot);
            });
        }
    })();
</script>
@endpush
