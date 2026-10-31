@extends('app')

@section('title', 'Data Terpadu Calon Penerima RTLH')

@section('content')
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
        align: center;
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
                <h3 class="fw-bold mb-1 text-primary">Data Terpadu Calon Penerima RTLH</h3>
                <p class="text-muted mb-0">Pusat data terintegrasi: identitas warga, kepemilikan hunian & lahan, serta hasil survei konstruksi fisik rumah.</p>
            </div>
            <div class="col-12 col-md-5 text-md-end mt-3 mt-md-0">
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
                        <div class="text-muted small fw-semibold">Total Calon Penerima</div>
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
            <div class="card shadow-sm border-0 border-start border-info border-4 h-100 kpi-card">
                <div class="card-body p-3 d-flex align-items-center">
                    <div class="kpi-icon bg-light-info text-info me-3">
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>
                    <div>
                        <div class="text-muted small fw-semibold">Terverifikasi</div>
                        <h4 class="fw-bold mb-0 text-dark">
                            {{ $wargas->filter(fn($w) => $w->status_update == 1)->count() }}
                        </h4>
                    </div>
                </div>
            </div>
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

                    <div class="col-12 col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            <i class="bi bi-funnel me-1"></i> Filter
                        </button>
                        @if(request()->hasAny(['search', 'id_wilayah', 'status_rumah', 'kondisi_atap', 'status_update']))
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
        <!-- Card Header Bersih: Judul & Jumlah Total -->
        <div class="card-header bg-white border-bottom py-3 d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h5 class="card-title fw-bold text-dark mb-0">Daftar Terpadu RTLH</h5>
                <small class="text-muted">Pilih perspektif tab di bawah untuk melihat rincian data calon penerima</small>
            </div>
            <span class="badge bg-light-primary text-primary px-3 py-2 rounded-pill fw-bold">
                Total: {{ $wargas->total() }} Data
            </span>
        </div>

        <!-- Toolbar Tab Navigasi Pills Mandiri (Terpisah Rapi dari thead tabel) -->
        <div class="bg-light px-3 py-2 border-bottom">
            <ul class="nav nav-pills nav-pills-custom gap-2 mb-0" id="tableTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-master" data-bs-toggle="pill" data-bs-target="#content-master" type="button" role="tab">
                        <i class="bi bi-grid-fill me-1"></i> 1. Ringkasan Terpadu (All-in-One)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-hunian" data-bs-toggle="pill" data-bs-target="#content-hunian" type="button" role="tab">
                        <i class="bi bi-geo-alt-fill me-1"></i> 2. Kepemilikan Hunian & Lahan
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-fisik" data-bs-toggle="pill" data-bs-target="#content-fisik" type="button" role="tab">
                        <i class="bi bi-bricks me-1"></i> 3. Survei Konstruksi & Sanitasi
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
                                    <th>Calon Penerima (Warga)</th>
                                    <th>Wilayah & Alamat</th>
                                    <th>Kepemilikan Hunian</th>
                                    <th>Kondisi Konstruksi RTLH</th>
                                    <th>Status Verifikasi</th>
                                    <th class="text-end pe-4" style="width: 130px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wargas as $index => $warga)
                                    @php
                                        $hunian = $warga->Hunian->first();
                                        $rumah = $hunian?->Rumah->first();
                                    @endphp
                                    <tr>
                                        <td class="ps-4 fw-semibold text-muted">
                                            {{ $wargas->firstItem() + $index }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-light-primary text-primary rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                                    <i class="bi bi-person-fill fs-5"></i>
                                                </div>
                                                <div>
                                                    <a href="{{ route('warga.show', $warga->id_warga) }}" class="fw-bold text-dark text-decoration-none">
                                                        {{ $warga->nama }}
                                                    </a>
                                                    <small class="text-muted d-block">NIK: {{ $warga->nik }}</small>
                                                    <small class="text-muted">KK: {{ $warga->no_kk }}</small>
                                                </div>
                                            </div>
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
                                            @if($warga->status_update == 1)
                                                <span class="badge bg-success"><i class="bi bi-check2-circle me-1"></i>Terverifikasi</span>
                                            @else
                                                <span class="badge bg-light-warning text-dark"><i class="bi bi-clock me-1"></i>Draft / Belum</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group btn-group-sm">
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
                                        <td colspan="7" class="text-center py-5">
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

                <!-- ================= TAB 2: DETAIL KEPEMILIKAN HUNIAN ================= -->
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

                <!-- ================= TAB 3: DETAIL FISIK & SANITASI ================= -->
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