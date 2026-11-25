@extends('app')

@section('title', 'Detail Calon Penerima RTLH - ' . $warga->nama)

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    #mapDetail {
        height: 250px;
        width: 100%;
        border-radius: 8px;
        border: 1px solid #ced4da;
        z-index: 1;
    }
    .leaflet-container {
        font-family: inherit;
    }
</style>
@endpush

@section('content')
@php
    $hunian = $warga->Hunian->first();
    $rumah = $hunian?->Rumah->first();
    $utilitas = $rumah?->Utilitas->first();
    $dokumenWarga = $warga->Dokumen;
    $dokumenRumah = $rumah?->DokumenRumah ?? collect();
    $bantuan = $warga->bantuanAktif ?? $warga->Bantuan?->firstWhere('is_aktif', true);

    // Menentukan asal rute dan navigasi Kembali dinamis
    $navFrom = request('from');
    $prevUrl = url()->previous();

    if ($navFrom === 'spk' || str_contains($prevUrl, '/spk') || str_contains($prevUrl, '/verifikasi')) {
        $backUrl = route('spk.index');
        $backLabel = 'Verifikasi & SPK';
        $navFrom = 'spk';
    } elseif ($navFrom === 'disetujui' || str_contains($prevUrl, 'penerima-disetujui') || str_contains($prevUrl, 'disetujui')) {
        $backUrl = route('warga.disetujui');
        $backLabel = 'Penerima Disetujui';
        $navFrom = 'disetujui';
    } elseif ($navFrom === 'realisasi' || str_contains($prevUrl, 'realisasi')) {
        $backUrl = route('realisasi.index');
        $backLabel = 'Realisasi Bantuan';
        $navFrom = 'realisasi';
    } elseif ($navFrom === 'dashboard' || str_contains($prevUrl, 'dashboard')) {
        $backUrl = route('dashboard');
        $backLabel = 'Dashboard';
        $navFrom = 'dashboard';
    } elseif ($bantuan && (int)$bantuan->status_bantuan === \App\Models\Bantuan::STATUS_DIVERIFIKASI) {
        $backUrl = route('spk.index');
        $backLabel = 'Verifikasi & SPK';
        $navFrom = 'spk';
    } elseif ($bantuan && (int)$bantuan->status_bantuan === \App\Models\Bantuan::STATUS_DISETUJUI) {
        $backUrl = route('warga.disetujui');
        $backLabel = 'Penerima Disetujui';
        $navFrom = 'disetujui';
    } elseif ($bantuan && (int)$bantuan->status_bantuan === \App\Models\Bantuan::STATUS_DIREALISASIKAN) {
        $backUrl = route('realisasi.index');
        $backLabel = 'Realisasi Bantuan';
        $navFrom = 'realisasi';
    } else {
        $backUrl = route('warga.index');
        $backLabel = 'Daftar Usulan';
        $navFrom = 'warga';
    }
@endphp

<div class="page-heading">
    <!-- Breadcrumb & Navigasi -->
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-12 col-md-7">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ $backUrl }}">{{ $backLabel }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail Profil Terpadu</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-primary">{{ $warga->nama }}</h3>
                <p class="text-muted small mb-0">NIK: {{ $warga->nik }} | No. KK: {{ $warga->no_kk }} | Kelurahan {{ $warga->wilayah->desa ?? '-' }}</p>
            </div>
            <div class="col-12 col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
                <a href="{{ $backUrl }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke {{ $backLabel }}
                </a>
                @can('update', $warga)
                    <a href="{{ route('warga.edit', ['warga' => $warga->id_warga, 'from' => $navFrom]) }}" class="btn btn-warning shadow-sm">
                        <i class="bi bi-pencil me-1"></i> Edit Data
                    </a>
                @endcan
                <button type="button" class="btn btn-primary shadow-sm" onclick="window.print()">
                    <i class="bi bi-printer-fill me-1"></i> Cetak Profil
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Kolom Kiri: Profil Singkat & Status -->
        <div class="col-12 col-xl-4">
            <!-- Profil Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center p-4">
                    <div class="avatar bg-light-primary text-primary rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <i class="bi bi-person-fill fs-1"></i>
                    </div>
                    <h5 class="fw-bold mb-1 text-dark">{{ $warga->nama }}</h5>
                    <p class="text-muted small mb-3">
                        <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                        {{ $warga->wilayah->desa ?? '-' }}, Kec. {{ $warga->wilayah->kecamatan ?? '-' }}
                    </p>

                    <div class="d-flex justify-content-center flex-wrap gap-2 mb-3">
                        @if($warga->status_update == 1)
                            <span class="badge bg-success py-2 px-3"><i class="bi bi-check-circle-fill me-1"></i>Terverifikasi</span>
                        @else
                            <span class="badge bg-warning text-dark py-2 px-3"><i class="bi bi-clock-fill me-1"></i>Belum Diverifikasi</span>
                        @endif

                        @if($warga->mampu_swadaya)
                            <span class="badge bg-info text-white py-2 px-3"><i class="bi bi-hand-thumbs-up-fill me-1"></i>Swadaya</span>
                        @endif
                    </div>

                    <div class="bg-light rounded p-3 text-start mb-3 border">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="small text-muted fw-semibold">Status Usulan Bantuan:</span>
                            <div>
                                @include('pages.warga.partials._status_badge', ['bantuan' => $bantuan])
                            </div>
                        </div>
                        <div class="small text-muted">
                            Program: <strong class="text-dark">{{ $bantuan?->program?->nama_program ?? 'Belum Ditentukan' }}</strong>
                        </div>
                        @if($bantuan?->verifikator)
                            <div class="small text-muted">
                                Verifikator: <strong class="text-dark">{{ $bantuan->verifikator->name }}</strong>
                            </div>
                        @endif
                        @if($bantuan?->catatan_verifikasi)
                            <div class="mt-2 pt-2 border-top small text-dark">
                                <span class="text-muted d-block fw-semibold mb-1"><i class="bi bi-chat-left-text me-1"></i>Catatan Verifikator:</span>
                                {{ $bantuan->catatan_verifikasi }}
                            </div>
                        @endif
                    </div>

                    <hr class="my-3">

                    <div class="text-start small">
                        <div class="row mb-2">
                            <span class="col-5 text-muted">No. HP:</span>
                            <span class="col-7 fw-semibold text-dark">{{ $warga->no_hp ?? '-' }}</span>
                        </div>
                        <div class="row mb-2">
                            <span class="col-5 text-muted">Status Kawin:</span>
                            <span class="col-7 fw-semibold text-dark">
                                @if($warga->kawin == 1) Kawin
                                @elseif($warga->kawin == 2) Belum Kawin
                                @elseif($warga->kawin == 3) Cerai
                                @else - @endif
                            </span>
                        </div>
                        <div class="row mb-2">
                            <span class="col-5 text-muted">Pekerjaan:</span>
                            <span class="col-7 fw-semibold text-dark">
                                @if($warga->pekerjaan == 1) Buruh Serabutan
                                @elseif($warga->pekerjaan == 2) Petani
                                @elseif($warga->pekerjaan == 3) Pedagang Kecil
                                @elseif($warga->pekerjaan == 4) Karyawan Swasta
                                @elseif($warga->pekerjaan == 5) Tidak Bekerja
                                @else Lainnya @endif
                            </span>
                        </div>
                        <div class="row mb-2">
                            <span class="col-5 text-muted">Desil DTKS:</span>
                            <span class="col-7 fw-semibold text-primary">
                                @if($warga->dtsen == 1) Desil 1 (Sangat Miskin)
                                @elseif($warga->dtsen == 2) Desil 2 (Miskin)
                                @elseif($warga->dtsen == 3) Desil 3 (Hampir Miskin)
                                @elseif($warga->dtsen == 4) Desil 4 (Rentan)
                                @else Non-DTKS @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Berkas Dokumen Kependudukan -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-paperclip text-primary me-2"></i> Berkas Kependudukan
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <i class="bi bi-file-earmark-person text-primary me-2"></i>
                                <span class="fw-semibold small">Foto KTP</span>
                            </div>
                            @if($dokumenWarga?->foto_ktp)
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" onclick="bukaPreviewFoto('{{ asset('storage/' . $dokumenWarga->foto_ktp) }}', 'Foto KTP - {{ addslashes($warga->nama) }}', 'KTP_{{ $warga->nik }}.jpg')" title="Lihat Foto">
                                        <i class="bi bi-eye me-1"></i>Lihat
                                    </button>
                                    <a href="{{ asset('storage/' . $dokumenWarga->foto_ktp) }}" download="KTP_{{ $warga->nik }}.jpg" class="btn btn-sm btn-outline-success py-0 px-2" title="Unduh Foto Langsung">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            @else
                                <span class="badge bg-light text-muted">Tidak Ada</span>
                            @endif
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <i class="bi bi-file-earmark-text text-success me-2"></i>
                                <span class="fw-semibold small">Foto Kartu Keluarga</span>
                            </div>
                            @if($dokumenWarga?->foto_kk)
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" onclick="bukaPreviewFoto('{{ asset('storage/' . $dokumenWarga->foto_kk) }}', 'Foto Kartu Keluarga - {{ addslashes($warga->nama) }}', 'KK_{{ $warga->nik }}.jpg')" title="Lihat Foto">
                                        <i class="bi bi-eye me-1"></i>Lihat
                                    </button>
                                    <a href="{{ asset('storage/' . $dokumenWarga->foto_kk) }}" download="KK_{{ $warga->nik }}.jpg" class="btn btn-sm btn-outline-success py-0 px-2" title="Unduh Foto Langsung">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            @else
                                <span class="badge bg-light text-muted">Tidak Ada</span>
                            @endif
                        </div>

                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <i class="bi bi-file-earmark-check text-info me-2"></i>
                                <span class="fw-semibold small">Bukti DTKS / DTSEN</span>
                            </div>
                            @if($dokumenWarga?->foto_dtsen)
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-sm btn-outline-primary py-0 px-2" onclick="bukaPreviewFoto('{{ asset('storage/' . $dokumenWarga->foto_dtsen) }}', 'Bukti DTKS / DTSEN - {{ addslashes($warga->nama) }}', 'DTKS_{{ $warga->nik }}.jpg')" title="Lihat Foto">
                                        <i class="bi bi-eye me-1"></i>Lihat
                                    </button>
                                    <a href="{{ asset('storage/' . $dokumenWarga->foto_dtsen) }}" download="DTKS_{{ $warga->nik }}.jpg" class="btn btn-sm btn-outline-success py-0 px-2" title="Unduh Foto Langsung">
                                        <i class="bi bi-download"></i>
                                    </a>
                                </div>
                            @else
                                <span class="badge bg-light text-muted">Tidak Ada</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Detail Hunian, Fisik Rumah & Dokumentasi Foto -->
        <div class="col-12 col-xl-8">
            <!-- 1. Kepemilikan Hunian & Lahan -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-geo-alt-fill text-danger me-2"></i> Status Kepemilikan Hunian & Lahan
                    </h5>
                    @if($hunian?->latitude && $hunian?->longitude)
                        <a href="https://www.google.com/maps?q={{ $hunian->latitude }},{{ $hunian->longitude }}" target="_blank" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-map me-1"></i> Buka Titik Maps
                        </a>
                    @endif
                </div>
                <div class="card-body pt-3">
                    @if($hunian)
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="text-muted small d-block">Status Kepemilikan Rumah</label>
                                <span class="fw-bold text-dark">
                                    @if($hunian->status_rumah == 1) Milik Sendiri
                                    @elseif($hunian->status_rumah == 2) Sewa / Kontrak
                                    @elseif($hunian->status_rumah == 3) Menumpang / Milik Keluarga
                                    @else - @endif
                                </span>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="text-muted small d-block">Status Hak Atas Tanah</label>
                                <span class="fw-bold text-dark">
                                    @if($hunian->status_tanah == 1) Milik Sendiri (SHM / Hak Milik)
                                    @elseif($hunian->status_tanah == 2) Bukan Milik Sendiri (Girik / Letter C)
                                    @elseif($hunian->status_tanah == 3) Tanah Negara / Kas Desa
                                    @else - @endif
                                </span>
                            </div>

                            <div class="col-6 col-md-3">
                                <label class="text-muted small d-block">Luas Bangunan</label>
                                <span class="fw-bold text-dark">{{ $hunian->luas_rumah ?? '-' }} m²</span>
                            </div>

                            <div class="col-6 col-md-3">
                                <label class="text-muted small d-block">Luas Tanah</label>
                                <span class="fw-bold text-dark">{{ $hunian->luas_tanah ?? '-' }} m²</span>
                            </div>

                            <div class="col-6 col-md-3">
                                <label class="text-muted small d-block">Jumlah KK</label>
                                <span class="fw-bold text-dark">{{ $hunian->jml_kk ?? 1 }} KK</span>
                            </div>

                            <div class="col-6 col-md-3">
                                <label class="text-muted small d-block">Jumlah Penghuni</label>
                                <span class="fw-bold text-dark">{{ $hunian->jml_penghuni ?? 1 }} Jiwa</span>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="text-muted small d-block">Kategori Kawasan</label>
                                <span class="fw-bold text-dark">
                                    @if($hunian->kawasan == 1) Permukiman Perkotaan
                                    @elseif($hunian->kawasan == 2) Bantaran Sungai / Sempadan
                                    @elseif($hunian->kawasan == 3) Kawasan Kumuh
                                    @else Lainnya @endif
                                </span>
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="text-muted small d-block">Koordinat GPS</label>
                                <span class="fw-bold text-primary">
                                    {{ $hunian->latitude ?? '-' }}, {{ $hunian->longitude ?? '-' }}
                                </span>
                            </div>

                            @if($hunian->latitude && $hunian->longitude)
                                <div class="col-12 mt-2">
                                    <label class="text-muted small d-block mb-1">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> Peta Lokasi Rumah (OpenStreetMap)
                                    </label>
                                    <div id="mapDetail"></div>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted small mb-0 py-2">Belum ada data kepemilikan hunian yang tercatat.</p>
                    @endif
                </div>
            </div>

            <!-- 2. Hasil Survei Fisik Konstruksi & Sanitasi -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-bricks text-warning me-2"></i> Kondisi Fisik Bangunan & Sanitasi RTLH
                    </h5>
                </div>
                <div class="card-body pt-3">
                    @if($rumah)
                        <h6 class="fw-bold text-secondary mb-3">Kondisi Komponen Konstruksi:</h6>
                        <div class="row g-3 mb-4">
                            <!-- Atap -->
                            <div class="col-12 col-md-4">
                                <div class="border rounded p-3 text-center bg-light">
                                    <small class="text-muted d-block mb-1">Kondisi Atap</small>
                                    @if($rumah->kondisi_atap == 3)
                                        <span class="badge bg-danger fs-6 px-3 py-2">Rusak Berat</span>
                                    @elseif($rumah->kondisi_atap == 2)
                                        <span class="badge bg-warning text-dark fs-6 px-3 py-2">Rusak Sedang</span>
                                    @elseif($rumah->kondisi_atap == 1)
                                        <span class="badge bg-success fs-6 px-3 py-2">Baik / Layak</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Dinding -->
                            <div class="col-12 col-md-4">
                                <div class="border rounded p-3 text-center bg-light">
                                    <small class="text-muted d-block mb-1">Kondisi Dinding</small>
                                    @if($rumah->kondisi_dinding == 3)
                                        <span class="badge bg-danger fs-6 px-3 py-2">Rusak Berat</span>
                                    @elseif($rumah->kondisi_dinding == 2)
                                        <span class="badge bg-warning text-dark fs-6 px-3 py-2">Rusak Sedang</span>
                                    @elseif($rumah->kondisi_dinding == 1)
                                        <span class="badge bg-success fs-6 px-3 py-2">Baik / Layak</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Lantai -->
                            <div class="col-12 col-md-4">
                                <div class="border rounded p-3 text-center bg-light">
                                    <small class="text-muted d-block mb-1">Kondisi Lantai</small>
                                    @if($rumah->kondisi_lantai == 3)
                                        <span class="badge bg-danger fs-6 px-3 py-2">Rusak / Tanah</span>
                                    @elseif($rumah->kondisi_lantai == 2)
                                        <span class="badge bg-warning text-dark fs-6 px-3 py-2">Rusak Sedang</span>
                                    @elseif($rumah->kondisi_lantai == 1)
                                        <span class="badge bg-success fs-6 px-3 py-2">Baik / Kedap Air</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Detail Sanitasi & Utilitas -->
                        @if($utilitas)
                            <h6 class="fw-bold text-secondary mb-3">Fasilitas Sanitasi & Utilitas:</h6>
                            <div class="row g-2 small">
                                <div class="col-6 col-md-4">
                                    <span class="text-muted">Sumber Air:</span>
                                    <div class="fw-semibold">
                                        @if($utilitas->sumber_air == 1) PDAM
                                        @elseif($utilitas->sumber_air == 2) Sumur Terlindung / Bor
                                        @elseif($utilitas->sumber_air == 3) Sumur Tak Terlindung
                                        @else Sungai/Mata Air @endif
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <span class="text-muted">Fasilitas MCK:</span>
                                    <div class="fw-semibold">
                                        @if($utilitas->mck == 1) Milik Sendiri (Di Dalam)
                                        @elseif($utilitas->mck == 2) Milik Sendiri (Di Luar)
                                        @elseif($utilitas->mck == 3) MCK Umum/Komunal
                                        @else Tidak Memiliki @endif
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <span class="text-muted">TPA Tinja:</span>
                                    <div class="fw-semibold">
                                        @if($utilitas->jenis_tpa == 1) Septic Tank
                                        @elseif($utilitas->jenis_tpa == 2) Sungai / Kolam
                                        @else Lubang Tanah @endif
                                    </div>
                                </div>
                                <div class="col-6 col-md-4 mt-2">
                                    <span class="text-muted">Penerangan:</span>
                                    <div class="fw-semibold">
                                        @if($utilitas->sumber_penerangan == 1) PLN Meteran Sendiri
                                        @elseif($utilitas->sumber_penerangan == 2) PLN Sambung Tetangga
                                        @else Non-PLN @endif
                                    </div>
                                </div>
                                <div class="col-6 col-md-4 mt-2">
                                    <span class="text-muted">Ventilasi Udara:</span>
                                    <div class="fw-semibold">
                                        @if($utilitas->ventilasi == 1) Cukup (≥ 10%)
                                        @elseif($utilitas->ventilasi == 2) Kurang (< 10%)
                                        @else Tidak Ada @endif
                                    </div>
                                </div>
                                <div class="col-6 col-md-4 mt-2">
                                    <span class="text-muted">Pencahayaan Jendela:</span>
                                    <div class="fw-semibold">
                                        @if($utilitas->jendela == 1) Berfungsi Baik
                                        @elseif($utilitas->jendela == 2) Rusak / Tertutup
                                        @else Tidak Ada @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <p class="text-muted small mb-0 py-2">Belum ada data survei fisik rumah yang diinputkan.</p>
                    @endif
                </div>
            </div>

            <!-- 3. Galeri Dokumentasi Foto Lapangan -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title mb-0 fw-bold text-dark">
                        <i class="bi bi-camera-fill text-danger me-2"></i> Dokumentasi Foto Fisik Lapangan
                    </h5>
                </div>
                <div class="card-body pt-3">
                    @if($dokumenRumah->isNotEmpty())
                        <div class="row g-3">
                            @foreach($dokumenRumah as $foto)
                                <div class="col-12 col-sm-6 col-md-4">
                                    <div class="card h-100 border shadow-sm">
                                        <a href="javascript:void(0)" onclick="bukaPreviewFoto('{{ asset('storage/' . $foto->path_foto) }}', '{{ addslashes($foto->jenis_foto) }} - {{ addslashes($warga->nama) }}', '{{ Str::slug($foto->jenis_foto) }}_{{ $warga->nik }}.jpg')" title="Klik untuk memperbesar & unduh">
                                            <img src="{{ asset('storage/' . $foto->path_foto) }}" alt="{{ $foto->jenis_foto }}" class="card-img-top" style="height: 160px; object-fit: cover; cursor: pointer;">
                                        </a>
                                        <div class="card-body p-2 d-flex justify-content-between align-items-center bg-light">
                                            <span class="fw-semibold small text-dark">{{ $foto->jenis_foto }}</span>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-primary btn-xs py-0 px-2" style="font-size: 0.75rem;" onclick="bukaPreviewFoto('{{ asset('storage/' . $foto->path_foto) }}', '{{ addslashes($foto->jenis_foto) }} - {{ addslashes($warga->nama) }}', '{{ Str::slug($foto->jenis_foto) }}_{{ $warga->nik }}.jpg')" title="Buka Preview Foto">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <a href="{{ asset('storage/' . $foto->path_foto) }}" download="{{ Str::slug($foto->jenis_foto) }}_{{ $warga->nik }}.jpg" class="btn btn-outline-success btn-xs py-0 px-2" style="font-size: 0.75rem;" title="Unduh Foto Langsung">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-images fs-2 d-block mb-2 text-muted opacity-50"></i>
                            <span class="small">Belum ada foto dokumentasi fisik rumah yang diunggah.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($hunian?->latitude && $hunian?->longitude)
@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    var lat = {{ (float) $hunian->latitude }};
    var lng = {{ (float) $hunian->longitude }};

    var mapShow = L.map('mapDetail').setView([lat, lng], 16);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> kontributor'
    }).addTo(mapShow);

    var markerShow = L.marker([lat, lng]).addTo(mapShow);
    markerShow.bindPopup("<b>{{ addslashes($warga->nama) }}</b><br>{{ addslashes($warga->alamat ?? '-') }}<br>Lat: " + lat + "<br>Long: " + lng).openPopup();
</script>
@endpush
@endif

@endsection
