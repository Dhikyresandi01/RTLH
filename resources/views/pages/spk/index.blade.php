@extends('app')

@section('title', 'Verifikasi Dinas & Perankingan SPK (SAW)')

@section('content')
@php
    $isDinas = Auth::check() && (Auth::user()->isSuperadmin() || Auth::user()->isAdminDinas());
    $items = $rankings ?? $wargas ?? collect();
    $tahun = $tahun ?? request('tahun', date('Y'));
    $availableYears = !empty($availableYears) ? $availableYears : [(int) date('Y')];
    $kriterias = $kriterias ?? \App\Models\SpkKriteria::orderBy('kode')->get();
    $programs = $programs ?? \App\Models\ProgramBantuan::orderBy('nama_program')->get();
    $wilayahs = $wilayahs ?? \App\Models\Wilayah::orderBy('desa')->get();

    $totalKandidat = $totalKandidat ?? (method_exists($items, 'total') ? $items->total() : $items->count());
    $avgSkor = $avgSkor ?? 0;
    $totalMenunggu = $totalMenunggu ?? 0;
    $totalRevisi = $totalRevisi ?? 0;
    $totalDitolak = $totalDitolak ?? 0;
    $totalDisetujui = $totalDisetujui ?? 0;
    $totalRevisiAtauDitolak = $totalRevisi + $totalDitolak;
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
    .table-spk thead th {
        background-color: #f8f9fa !important;
        color: #495057;
        font-weight: 700;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.85rem 0.9rem !important;
        border-bottom: 2px solid #e9ecef !important;
        white-space: nowrap;
    }
    .table-spk tbody td {
        padding: 0.9rem 0.9rem !important;
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
                        <li class="breadcrumb-item">Pendataan &amp; Verifikasi</li>
                        <li class="breadcrumb-item active" aria-current="page">Verifikasi Dinas &amp; SPK SAW</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-1 text-primary">
                    <i class="bi bi-shield-check me-2"></i>Verifikasi Dinas &amp; Perankingan SPK (SAW)
                </h3>
                @if($isDinas)
                    <p class="text-muted small mb-0">
                        Panel evaluasi prioritas calon penerima bantuan RTLH dengan metode <strong>Simple Additive Weighting (SAW)</strong> serta penetapan keputusan verifikasi Dinas.
                    </p>
                @else
                    <p class="text-muted small mb-0">
                        Pantau peringkat prioritas SPK SAW serta status dan catatan verifikasi Dinas untuk usulan wilayah <strong>{{ Auth::user()->wilayah->desa ?? 'Kelurahan' }}</strong>.
                    </p>
                @endif
            </div>
            @if($isDinas)
                <div class="col-12 col-xl-5 text-xl-end mt-3 mt-xl-0 d-flex flex-wrap justify-content-xl-end gap-2">
                    @if($kriterias->isNotEmpty())
                        <button type="button" class="btn btn-outline-primary shadow-sm px-3 py-2 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalKriteriaSAW">
                            <i class="bi bi-sliders me-1"></i> Bobot Kriteria
                        </button>
                    @endif

                    <form action="{{ route('spk.hitung') }}" method="POST" class="d-inline" onsubmit="return confirm('Jalankan kalkulasi ulang perankingan SAW untuk tahun anggaran {{ $tahun }}?');">
                        @csrf
                        <input type="hidden" name="tahun" value="{{ $tahun }}">
                        @if(request()->filled('id_program'))
                            <input type="hidden" name="id_program" value="{{ request('id_program') }}">
                        @endif
                        <button type="submit" class="btn btn-warning text-dark shadow-sm px-3 py-2 fw-semibold">
                            <i class="bi bi-arrow-repeat me-1"></i> Hitung Ulang SAW
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    @if(!$isDinas)
        <div class="alert alert-light-info border border-info-subtle shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-eye-fill text-info fs-5 me-2"></i>
                <small class="text-dark mb-0">
                    <strong>Mode Pantau Kelurahan (View-Only Keputusan Dinas):</strong> Anda dapat melihat hasil perankingan SPK SAW, rincian skor, dan catatan verifikasi Dinas untuk warga di kelurahan Anda. Perbaikan data tetap tersedia pada usulan berstatus <em>Menunggu Verifikasi</em>, <em>Diverifikasi Lapangan</em>, atau <em>Perlu Revisi</em>.
                </small>
            </div>
        </div>
    @endif

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

    <!-- KPI Cards Ringkasan SPK & Verifikasi -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 border-start border-primary border-4 h-100 kpi-card">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="kpi-icon bg-light-primary text-primary me-3">
                        <i class="bi bi-award-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Kandidat SPK ({{ $tahun }})</div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalKandidat }}</h4>
                        <small class="text-muted" style="font-size: 0.72rem;">Rata-rata skor: {{ number_format($avgSkor, 4) }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 border-start border-info border-4 h-100 kpi-card">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="kpi-icon bg-light-info text-info me-3">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Menunggu / Diverifikasi</div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalMenunggu }}</h4>
                        <small class="text-muted" style="font-size: 0.72rem;">Antrean keputusan Dinas</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 border-start border-warning border-4 h-100 kpi-card">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="kpi-icon bg-light-warning text-warning me-3">
                        <i class="bi bi-exclamation-circle-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Revisi / Ditolak</div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $totalRevisiAtauDitolak }}</h4>
                        <small class="text-muted" style="font-size: 0.72rem;">Revisi: {{ $totalRevisi }} | Ditolak: {{ $totalDitolak }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card shadow-sm border-0 border-start border-success border-4 h-100 kpi-card">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="kpi-icon bg-light-success text-success me-3">
                        <i class="bi bi-check-circle-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Disetujui Dinas</div>
                        <h4 class="fw-bold mb-0 text-success">{{ $totalDisetujui }}</h4>
                        <small class="text-muted" style="font-size: 0.72rem;">Masuk daftar penerima SK</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Pencarian -->
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('spk.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-md-{{ $isDinas ? '3' : '4' }}">
                        <label class="form-label small fw-semibold text-muted mb-1">Cari NIK / Nama / No. KK</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Ketik pencarian..." value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-6 col-md-{{ $isDinas ? '1' : '2' }}">
                        <label class="form-label small fw-semibold text-muted mb-1">Tahun</label>
                        <select name="tahun" class="form-select form-select-sm">
                            @foreach($availableYears as $th)
                                <option value="{{ $th }}" {{ (string) $tahun === (string) $th ? 'selected' : '' }}>{{ $th }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if($isDinas)
                        <div class="col-6 col-md-2">
                            <label class="form-label small fw-semibold text-muted mb-1">Kelurahan</label>
                            <select name="id_wilayah" class="form-select form-select-sm">
                                <option value="">Semua Kelurahan</option>
                                @foreach($wilayahs as $wilayah)
                                    <option value="{{ $wilayah->id_wilayah }}" {{ request('id_wilayah') == $wilayah->id_wilayah ? 'selected' : '' }}>
                                        {{ $wilayah->desa }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="col-12 col-md-3">
                        <label class="form-label small fw-semibold text-muted mb-1">Program Bantuan</label>
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
                        <label class="form-label small fw-semibold text-muted mb-1">Status Verifikasi</label>
                        <select name="status_bantuan" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            @foreach(\App\Models\Bantuan::statusLabels() as $kode => $label)
                                <option value="{{ $kode }}" {{ request('status_bantuan') === (string) $kode ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-1 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100" title="Terapkan Filter">
                            <i class="bi bi-funnel"></i>
                        </button>
                        @if(request()->hasAny(['search', 'id_wilayah', 'id_program', 'status_bantuan', 'tahun']))
                            <a href="{{ route('spk.index') }}" class="btn btn-sm btn-outline-secondary" title="Reset Filter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Utama Perankingan SPK SAW & Verifikasi Dinas -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h5 class="card-title fw-bold text-dark mb-0">Matriks Perankingan SAW &amp; {{ $isDinas ? 'Keputusan Verifikasi' : 'Status Verifikasi Dinas' }}</h5>
                <small class="text-muted">
                    @if($isDinas)
                        Klik tombol <strong>Verifikasi</strong> untuk menetapkan status (Diverifikasi Lapangan, Disetujui Dinas, Perlu Revisi, atau Ditolak).
                    @else
                        Klik ikon kalkulator untuk melihat rincian skor SAW atau tombol edit jika usulan masih berstatus Menunggu Verifikasi, Diverifikasi Lapangan, atau Perlu Revisi.
                    @endif
                </small>
            </div>
            <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill fw-bold">
                Tahun Anggaran {{ $tahun }}
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle table-spk">
                    <thead>
                        <tr>
                            <th class="ps-4 text-center" style="width: 75px;">Rank</th>
                            <th>Calon Penerima &amp; Wilayah</th>
                            <th class="text-center" title="Kerusakan Fisik Bangunan">C1 (Fisik)</th>
                            <th class="text-center" title="Luas Lantai per Orang">C2 (Luas)</th>
                            <th class="text-center" title="Sanitasi &amp; Air Minum">C3 (Sanitasi)</th>
                            <th class="text-center" title="Desil DTSEN">C4 (DTSEN)</th>
                            <th class="text-center" title="Penghasilan">C5 (Ekonomi)</th>
                            <th class="text-center" style="width: 135px;">Skor SAW (V<sub>i</sub>)</th>
                            <th>Status Verifikasi</th>
                            <th class="text-end pe-4" style="width: 190px;">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                            @php
                                $isRankingModel = $item instanceof \App\Models\SpkRanking;
                                $rank = $isRankingModel ? $item : $item->bantuanAktif?->spkRanking;
                                $bantuan = $isRankingModel ? $item->bantuan : $item->bantuanAktif;
                                $warga = $isRankingModel ? $bantuan?->warga : $item;
                                $rincian = $rank?->rincian_kriteria ?? [];
                            @endphp
                            <tr>
                                <!-- Rank -->
                                <td class="ps-4 text-center">
                                    @if($rank)
                                        @if($rank->ranking == 1)
                                            <span class="badge rounded-circle p-2 bg-warning text-dark fw-bold shadow-sm" style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;">#1</span>
                                        @elseif($rank->ranking == 2)
                                            <span class="badge rounded-circle p-2 bg-secondary text-white fw-bold shadow-sm" style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;">#2</span>
                                        @elseif($rank->ranking == 3)
                                            <span class="badge rounded-circle p-2 bg-danger text-white fw-bold shadow-sm" style="width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;">#3</span>
                                        @else
                                            <span class="badge bg-light text-dark border fw-bold">#{{ $rank->ranking }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>

                                <!-- Warga & Wilayah -->
                                <td>
                                    @if($warga)
                                        <a href="{{ route('warga.show', $warga->id_warga) }}" class="fw-bold text-dark text-decoration-none">
                                            {{ $warga->nama }}
                                        </a>
                                        <small class="text-muted d-block">NIK: {{ $warga->nik }} | {{ $warga->wilayah->desa ?? '-' }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                    @if($bantuan && $bantuan->program)
                                        <span class="badge bg-light text-primary border mt-1" style="font-size: 0.7rem;">
                                            <i class="bi bi-gift-fill me-1"></i>{{ $bantuan->program->nama_program }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Kriteria C1..C5 -->
                                <td class="text-center small">
                                    <span class="badge bg-light text-dark border">{{ $rincian['C1']['mentah'] ?? '-' }}</span>
                                </td>
                                <td class="text-center small">
                                    <span class="badge bg-light text-dark border">{{ isset($rincian['C2']['mentah']) ? $rincian['C2']['mentah'] . 'm²' : '-' }}</span>
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
                                        <div class="progress mt-1" style="height: 4px;">
                                            <div class="progress-bar bg-primary" style="width: {{ min(100, $rank->skor_akhir * 100) }}%"></div>
                                        </div>
                                    @else
                                        <span class="text-muted small">Belum dihitung</span>
                                    @endif
                                </td>

                                <!-- Status Verifikasi -->
                                <td>
                                    @include('pages.warga.partials._status_badge', [
                                        'bantuan' => $bantuan,
                                        'showVerifikator' => true,
                                        'showCatatan' => true,
                                    ])
                                </td>

                                <!-- Aksi -->
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm">
                                        @if($warga)
                                            <a href="{{ route('warga.show', $warga->id_warga) }}" class="btn btn-light-primary" title="Detail Warga">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endif

                                        @if($rank && $warga)
                                            <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#modalDetailSAW{{ $warga->id_warga }}" title="Rincian Perhitungan SAW">
                                                <i class="bi bi-calculator"></i>
                                            </button>
                                        @endif

                                        @if($warga)
                                            @can('update', $warga)
                                                <a href="{{ route('warga.edit', $warga->id_warga) }}" class="btn btn-light-warning" title="Edit / Perbaiki Data Usulan">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endcan
                                        @endif

                                        @if($isDinas && $bantuan)
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVerifikasiWarga{{ $bantuan->id_bantuan }}" title="Verifikasi Dinas">
                                                <i class="bi bi-check2-square me-1"></i>Verifikasi
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="py-3">
                                        <div class="avatar bg-light-warning text-warning mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width: 54px; height: 54px;">
                                            <i class="bi bi-award fs-2"></i>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">Belum ada hasil perankingan SPK untuk kriteria ini</h6>
                                        @if($isDinas)
                                            <p class="text-muted small mb-3">Silakan klik tombol <strong>Hitung Ulang SAW</strong> di kanan atas untuk memproses data usulan tahun {{ $tahun }}.</p>
                                        @else
                                            <p class="text-muted small mb-3">Belum ada hasil perankingan SAW atau usulan terdata untuk wilayah kelurahan Anda pada tahun {{ $tahun }}.</p>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            @if(method_exists($items, 'hasPages') && $items->hasPages())
                <div class="card-footer bg-white border-0 py-3 d-flex flex-wrap justify-content-between align-items-center">
                    <small class="text-muted">
                        Menampilkan <strong>{{ $items->firstItem() }}</strong> - <strong>{{ $items->lastItem() }}</strong> dari <strong>{{ $items->total() }}</strong> kandidat
                    </small>
                    <div>
                        {{ $items->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modals Verifikasi & Detail SAW -->
@foreach($items as $item)
    @php
        $isRankingModel = $item instanceof \App\Models\SpkRanking;
        $rank = $isRankingModel ? $item : $item->bantuanAktif?->spkRanking;
        $bantuan = $isRankingModel ? $item->bantuan : $item->bantuanAktif;
        $warga = $isRankingModel ? $bantuan?->warga : $item;
    @endphp
    @if($isDinas)
        @include('pages.warga.partials._modal_verifikasi', ['bantuan' => $bantuan, 'warga' => $warga, 'rank' => $rank])
    @endif
    @include('pages.warga.partials._modal_detail_saw', ['warga' => $warga, 'rank' => $rank])
@endforeach

<!-- Modal Konfigurasi Bobot Kriteria SAW -->
@if($isDinas && $kriterias->isNotEmpty())
<div class="modal fade" id="modalKriteriaSAW" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('spk.kriteria.update') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold text-dark">
                        <i class="bi bi-sliders text-primary me-2"></i>Konfigurasi Bobot Kriteria SPK (SAW)
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info small mb-3">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Total akumulasi seluruh bobot kriteria harus bernilai tepat <strong>1.00</strong> (100%). Setelah mengubah bobot, jalankan kembali <strong>Hitung Ulang SAW</strong>.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
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
                                        <td class="fw-bold text-center">{{ $kriteria->kode }}</td>
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
                                            <input type="number" step="0.01" min="0" max="1" name="bobot[{{ $kriteria->kode }}]" class="form-control form-control-sm input-bobot-saw text-end fw-semibold" value="{{ number_format((float) $kriteria->bobot, 2, '.', '') }}" required>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">Total Akumulasi Bobot:</th>
                                    <th class="text-end">
                                        <span id="totalBobotSaw" class="fw-bold text-primary">{{ number_format((float) $kriterias->sum('bobot'), 2, '.', '') }}</span>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-save me-1"></i> Simpan Konfigurasi Bobot
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
        if (!inputs.length || !totalEl) return;

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
    })();
</script>
@endpush
