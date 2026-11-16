@extends('app')

@section('title', 'Edit Data Terpadu Calon Penerima RTLH')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    #mapPickerEdit {
        height: 280px;
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
<div class="page-heading">
    <!-- Page Header -->
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-12 col-md-7">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('warga') }}">Data Calon Penerima</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Terpadu</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-1 text-primary">Edit Data Terpadu: {{ $warga->nama }}</h3>
                <p class="text-muted small mb-0">Ubah data identitas calon penerima, legalitas kepemilikan hunian, dan evaluasi fisik bangunan.</p>
            </div>
            <div class="col-12 col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
                <a href="{{ route('warga.show', $warga->id_warga) }}" class="btn btn-outline-info">
                    <i class="bi bi-eye me-1"></i> Lihat Profil
                </a>
                <a href="{{ route('warga') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Notifikasi Error Validasi -->
    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible show fade shadow-sm mb-4">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-octagon-fill fs-3 me-3 text-danger"></i>
                <div>
                    <h6 class="alert-heading fw-bold mb-1">Terdapat kesalahan pengisian data!</h6>
                    <ul class="mb-0 small ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        $hunian = $warga->Hunian->first();
        $rumah = $hunian?->Rumah->first();
        $utilitas = $rumah?->Utilitas->first();
        $dokumen = $warga->Dokumen;
    @endphp

    <form action="{{ route('warga.update', $warga->id_warga) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Wizard Nav Tabs Header -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-2">
                <ul class="nav nav-pills nav-fill gap-2" id="formTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active py-3 fw-semibold text-start d-flex align-items-center" id="tab-step1" data-bs-toggle="pill" data-bs-target="#step1" type="button" role="tab">
                            <span class="badge bg-primary rounded-circle p-2 me-2 d-inline-flex align-items-center justify-content-center" style="width:28px; height:28px;">1</span>
                            <div>
                                <div class="text-dark fw-bold">Data Warga & Sosio-Ekonomi</div>
                                <small class="text-muted d-none d-md-block" style="font-size:0.75rem;">Identitas KTP, KK, dan Pekerjaan</small>
                            </div>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 fw-semibold text-start d-flex align-items-center" id="tab-step2" data-bs-toggle="pill" data-bs-target="#step2" type="button" role="tab">
                            <span class="badge bg-secondary rounded-circle p-2 me-2 d-inline-flex align-items-center justify-content-center" style="width:28px; height:28px;">2</span>
                            <div>
                                <div class="text-dark fw-bold">Kepemilikan Hunian & Lahan</div>
                                <small class="text-muted d-none d-md-block" style="font-size:0.75rem;">Status Tanah, Luas, & Koordinat GPS</small>
                            </div>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 fw-semibold text-start d-flex align-items-center" id="tab-step3" data-bs-toggle="pill" data-bs-target="#step3" type="button" role="tab">
                            <span class="badge bg-secondary rounded-circle p-2 me-2 d-inline-flex align-items-center justify-content-center" style="width:28px; height:28px;">3</span>
                            <div>
                                <div class="text-dark fw-bold">Fisik Konstruksi Rumah</div>
                                <small class="text-muted d-none d-md-block" style="font-size:0.75rem;">Kondisi Atap, Dinding, dan Lantai</small>
                            </div>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 fw-semibold text-start d-flex align-items-center" id="tab-step4" data-bs-toggle="pill" data-bs-target="#step4" type="button" role="tab">
                            <span class="badge bg-secondary rounded-circle p-2 me-2 d-inline-flex align-items-center justify-content-center" style="width:28px; height:28px;">4</span>
                            <div>
                                <div class="text-dark fw-bold">Sanitasi & Dokumentasi Foto</div>
                                <small class="text-muted d-none d-md-block" style="font-size:0.75rem;">MCK, Air, Listrik & Foto Fisik</small>
                            </div>
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content" id="formTabsContent">

            <!-- ================= STEP 1: DATA WARGA ================= -->
            <div class="tab-pane fade show active" id="step1" role="tabpanel">
                <div class="row">
                    <div class="col-12 col-xl-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark">
                                    <i class="bi bi-person-vcard-fill text-primary me-2"></i> Identitas Kependudukan
                                </h5>
                            </div>
                            <div class="card-body pt-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nomor Induk Kependudukan (NIK) <span class="text-danger">*</span></label>
                                        <input type="text" name="nik" maxlength="16" class="form-control" value="{{ old('nik', $warga->nik) }}" required>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nomor Kartu Keluarga (KK) <span class="text-danger">*</span></label>
                                        <input type="text" name="no_kk" maxlength="16" class="form-control" value="{{ old('no_kk', $warga->no_kk) }}" required>
                                    </div>

                                    <div class="col-12 col-md-8">
                                        <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $warga->nama) }}" required>
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select name="jenis_kelamin" class="form-select" required>
                                            <option value="1" {{ old('jenis_kelamin', $warga->jenis_kelamin) == '1' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="2" {{ old('jenis_kelamin', $warga->jenis_kelamin) == '2' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $warga->tempat_lahir) }}">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Tanggal Lahir</label>
                                        <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir', $warga->tgl_lahir?->format('Y-m-d')) }}">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Alamat Lengkap</label>
                                        <input type="text" name="alamat" class="form-control" value="{{ old('alamat', $warga->alamat) }}">
                                    </div>

                                    <div class="col-6 col-md-3">
                                        <label class="form-label fw-semibold">RT</label>
                                        <input type="text" name="rt" maxlength="5" class="form-control" value="{{ old('rt', $warga->rt) }}">
                                    </div>

                                    <div class="col-6 col-md-3">
                                        <label class="form-label fw-semibold">RW</label>
                                        <input type="text" name="rw" maxlength="5" class="form-control" value="{{ old('rw', $warga->rw) }}">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kelurahan / Wilayah <span class="text-danger">*</span></label>
                                        <select name="id_wilayah" class="form-select" required>
                                            @foreach($wilayahs as $wilayah)
                                                <option value="{{ $wilayah->id_wilayah }}" {{ old('id_wilayah', $warga->id_wilayah) == $wilayah->id_wilayah ? 'selected' : '' }}>
                                                    {{ $wilayah->desa }} (Kec. {{ $wilayah->kecamatan }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">No. Handphone / WA</label>
                                        <input type="text" name="no_hp" maxlength="15" class="form-control" value="{{ old('no_hp', $warga->no_hp) }}">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status Pernikahan <span class="text-danger">*</span></label>
                                        <select name="kawin" class="form-select" required>
                                            <option value="1" {{ old('kawin', $warga->kawin) == '1' ? 'selected' : '' }}>Kawin</option>
                                            <option value="2" {{ old('kawin', $warga->kawin) == '2' ? 'selected' : '' }}>Belum Kawin</option>
                                            <option value="3" {{ old('kawin', $warga->kawin) == '3' ? 'selected' : '' }}>Cerai</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sosio-Ekonomi -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark">
                                    <i class="bi bi-cash-stack text-success me-2"></i> Kondisi Sosial & Ekonomi
                                </h5>
                            </div>
                            <div class="card-body pt-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Pekerjaan Utama</label>
                                        <select name="pekerjaan" class="form-select">
                                            <option value="1" {{ old('pekerjaan', $warga->pekerjaan) == '1' ? 'selected' : '' }}>Buruh Harian Lepas</option>
                                            <option value="2" {{ old('pekerjaan', $warga->pekerjaan) == '2' ? 'selected' : '' }}>Petani</option>
                                            <option value="3" {{ old('pekerjaan', $warga->pekerjaan) == '3' ? 'selected' : '' }}>Pedagang Kecil</option>
                                            <option value="4" {{ old('pekerjaan', $warga->pekerjaan) == '4' ? 'selected' : '' }}>Karyawan Swasta</option>
                                            <option value="5" {{ old('pekerjaan', $warga->pekerjaan) == '5' ? 'selected' : '' }}>Tidak Bekerja</option>
                                            <option value="6" {{ old('pekerjaan', $warga->pekerjaan) == '6' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Desil DTSEN / DTKS</label>
                                        <select name="dtsen" class="form-select">
                                            <option value="1" {{ old('dtsen', $warga->dtsen) == '1' ? 'selected' : '' }}>Desil 1 (Sangat Miskin)</option>
                                            <option value="2" {{ old('dtsen', $warga->dtsen) == '2' ? 'selected' : '' }}>Desil 2 (Miskin)</option>
                                            <option value="3" {{ old('dtsen', $warga->dtsen) == '3' ? 'selected' : '' }}>Desil 3 (Hampir Miskin)</option>
                                            <option value="4" {{ old('dtsen', $warga->dtsen) == '4' ? 'selected' : '' }}>Desil 4 (Rentan)</option>
                                            <option value="0" {{ old('dtsen', $warga->dtsen) == '0' ? 'selected' : '' }}>Non-DTKS</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status Verifikasi Data</label>
                                        <select name="status_update" class="form-select">
                                            <option value="0" {{ old('status_update', $warga->status_update) == 0 ? 'selected' : '' }}>0 - Belum Diverifikasi / Draft</option>
                                            <option value="1" {{ old('status_update', $warga->status_update) == 1 ? 'selected' : '' }}>1 - Terverifikasi Layak</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="form-check p-3 bg-light rounded mt-2">
                                            <input class="form-check-input ms-0 me-2" type="checkbox" name="mampu_swadaya" value="1" id="mampuSwadaya" {{ old('mampu_swadaya', $warga->mampu_swadaya) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-dark" for="mampuSwadaya">
                                                Mampu Berswadaya
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Upload Dokumen Warga -->
                    <div class="col-12 col-xl-4">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark">
                                    <i class="bi bi-paperclip text-primary me-2"></i> Berkas Kependudukan
                                </h5>
                            </div>
                            <div class="card-body pt-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Ganti Foto KTP</label>
                                    @if($dokumen?->foto_ktp)
                                        <div class="mb-1"><a href="{{ asset('storage/' . $dokumen->foto_ktp) }}" target="_blank" class="small text-primary"><i class="bi bi-eye"></i> Lihat KTP saat ini</a></div>
                                    @endif
                                    <input type="file" name="foto_ktp" accept="image/*,application/pdf" class="form-control form-control-sm">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Ganti Foto KK</label>
                                    @if($dokumen?->foto_kk)
                                        <div class="mb-1"><a href="{{ asset('storage/' . $dokumen->foto_kk) }}" target="_blank" class="small text-primary"><i class="bi bi-eye"></i> Lihat KK saat ini</a></div>
                                    @endif
                                    <input type="file" name="foto_kk" accept="image/*,application/pdf" class="form-control form-control-sm">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Ganti Bukti DTKS / DTSEN</label>
                                    @if($dokumen?->foto_dtsen)
                                        <div class="mb-1"><a href="{{ asset('storage/' . $dokumen->foto_dtsen) }}" target="_blank" class="small text-primary"><i class="bi bi-eye"></i> Lihat Bukti saat ini</a></div>
                                    @endif
                                    <input type="file" name="foto_dtsen" accept="image/*,application/pdf" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mb-4">
                    <button type="button" class="btn btn-primary px-4 py-2" onclick="goToStep('step2')">
                        Lanjut ke Kepemilikan Hunian <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            <!-- ================= STEP 2: KEPEMILIKAN HUNIAN ================= -->
            <div class="tab-pane fade" id="step2" role="tabpanel">
                <div class="row">
                    <div class="col-12 col-xl-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark">
                                    <i class="bi bi-shield-check text-primary me-2"></i> Status Kepemilikan Rumah & Tanah
                                </h5>
                            </div>
                            <div class="card-body pt-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status Kepemilikan Rumah</label>
                                        <select name="status_rumah" class="form-select">
                                            <option value="1" {{ old('status_rumah', $hunian?->status_rumah) == '1' ? 'selected' : '' }}>1 - Milik Sendiri</option>
                                            <option value="2" {{ old('status_rumah', $hunian?->status_rumah) == '2' ? 'selected' : '' }}>2 - Sewa / Kontrak</option>
                                            <option value="3" {{ old('status_rumah', $hunian?->status_rumah) == '3' ? 'selected' : '' }}>3 - Menumpang / Milik Keluarga</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status Hak Atas Tanah</label>
                                        <select name="status_tanah" class="form-select">
                                            <option value="1" {{ old('status_tanah', $hunian?->status_tanah) == '1' ? 'selected' : '' }}>1 - Milik Sendiri (SHM)</option>
                                            <option value="2" {{ old('status_tanah', $hunian?->status_tanah) == '2' ? 'selected' : '' }}>2 - Bukan Milik Sendiri (Girik / Letter C)</option>
                                            <option value="3" {{ old('status_tanah', $hunian?->status_tanah) == '3' ? 'selected' : '' }}>3 - Tanah Negara / Kas Desa</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Luas Bangunan Rumah (m²)</label>
                                        <input type="number" step="0.1" name="luas_rumah" class="form-control" value="{{ old('luas_rumah', $hunian?->luas_rumah) }}">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Luas Tanah (m²)</label>
                                        <input type="number" step="0.1" name="luas_tanah" class="form-control" value="{{ old('luas_tanah', $hunian?->luas_tanah) }}">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="form-check p-3 bg-light rounded mt-2">
                                            <input class="form-check-input ms-0 me-2" type="checkbox" name="rumah_lain" value="1" id="editRumahLain" {{ old('rumah_lain', $hunian?->rumah_lain) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-dark" for="editRumahLain">
                                                Memiliki Rumah di Tempat Lain
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="form-check p-3 bg-light rounded mt-2">
                                            <input class="form-check-input ms-0 me-2" type="checkbox" name="tanah_lain" value="1" id="editTanahLain" {{ old('tanah_lain', $hunian?->tanah_lain) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-dark" for="editTanahLain">
                                                Memiliki Tanah di Tempat Lain
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-4">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark">
                                    <i class="bi bi-geo-alt-fill text-danger me-2"></i> Koordinat & Penghuni
                                </h5>
                            </div>
                            <div class="card-body pt-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Kategori Kawasan</label>
                                    <select name="kawasan" class="form-select">
                                        <option value="1" {{ old('kawasan', $hunian?->kawasan) == '1' ? 'selected' : '' }}>Permukiman Perkotaan</option>
                                        <option value="2" {{ old('kawasan', $hunian?->kawasan) == '2' ? 'selected' : '' }}>Bantaran Sungai / Sempadan</option>
                                        <option value="3" {{ old('kawasan', $hunian?->kawasan) == '3' ? 'selected' : '' }}>Kawasan Kumuh</option>
                                        <option value="4" {{ old('kawasan', $hunian?->kawasan) == '4' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Jumlah Penghuni (Jiwa)</label>
                                    <input type="number" min="1" name="jml_penghuni" class="form-control" value="{{ old('jml_penghuni', $hunian?->jml_penghuni ?? 1) }}">
                                </div>

                                <hr class="my-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold text-dark mb-0">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> Titik Lokasi Rumah (OSM)
                                    </h6>
                                    <button type="button" class="btn btn-xs btn-outline-primary py-1 px-2" style="font-size: 0.75rem;" onclick="getCurrentLocationEdit()" title="Ambil GPS perangkat Anda saat ini">
                                        <i class="bi bi-crosshair me-1"></i> Lokasi Saya
                                    </button>
                                </div>

                                <!-- Wadah Peta Leaflet OpenStreetMap -->
                                <div class="mb-2">
                                    <div id="mapPickerEdit"></div>
                                    <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">
                                        <i class="bi bi-cursor-fill text-primary me-1"></i> Klik pada peta atau geser pin merah untuk memperbarui titik koordinat rumah.
                                    </small>
                                </div>

                                <div class="row g-2 mb-1">
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold mb-1">Latitude</label>
                                        <input type="text" name="latitude" id="editLat" class="form-control form-control-sm" placeholder="-7.816667" value="{{ old('latitude', $hunian?->latitude) }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold mb-1">Longitude</label>
                                        <input type="text" name="longitude" id="editLong" class="form-control form-control-sm" placeholder="112.011667" value="{{ old('longitude', $hunian?->longitude) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mb-4">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" onclick="goToStep('step1')">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Data Warga
                    </button>
                    <button type="button" class="btn btn-primary px-4 py-2" onclick="goToStep('step3')">
                        Lanjut ke Fisik Konstruksi <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            <!-- ================= STEP 3: FISIK KONSTRUKSI ================= -->
            <div class="tab-pane fade" id="step3" role="tabpanel">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-bricks text-danger me-2"></i> Evaluasi Kerusakan Konstruksi Utama Bangunan
                        </h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row">
                            <!-- Atap -->
                            <div class="col-12 col-lg-4 mb-3">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-house-up text-danger me-1"></i> Bagian Atap</h6>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Kondisi Kerusakan Atap</label>
                                        <select name="kondisi_atap" class="form-select form-select-sm">
                                            <option value="1" {{ old('kondisi_atap', $rumah?->kondisi_atap) == '1' ? 'selected' : '' }}>1 - Baik</option>
                                            <option value="2" {{ old('kondisi_atap', $rumah?->kondisi_atap) == '2' ? 'selected' : '' }}>2 - Rusak Sedang</option>
                                            <option value="3" {{ old('kondisi_atap', $rumah?->kondisi_atap) == '3' ? 'selected' : '' }}>3 - Rusak Berat</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Bahan Penutup Atap</label>
                                        <select name="bahan_atap" class="form-select form-select-sm">
                                            <option value="1" {{ old('bahan_atap', $rumah?->bahan_atap) == '1' ? 'selected' : '' }}>Genteng Tanah Liat / Keramik</option>
                                            <option value="2" {{ old('bahan_atap', $rumah?->bahan_atap) == '2' ? 'selected' : '' }}>Seng / Asbes</option>
                                            <option value="3" {{ old('bahan_atap', $rumah?->bahan_atap) == '3' ? 'selected' : '' }}>Ijuk / Anyaman</option>
                                            <option value="4" {{ old('bahan_atap', $rumah?->bahan_atap) == '4' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Dinding -->
                            <div class="col-12 col-lg-4 mb-3">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-border-all text-warning me-1"></i> Bagian Dinding</h6>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Kondisi Kerusakan Dinding</label>
                                        <select name="kondisi_dinding" class="form-select form-select-sm">
                                            <option value="1" {{ old('kondisi_dinding', $rumah?->kondisi_dinding) == '1' ? 'selected' : '' }}>1 - Baik</option>
                                            <option value="2" {{ old('kondisi_dinding', $rumah?->kondisi_dinding) == '2' ? 'selected' : '' }}>2 - Rusak Sedang</option>
                                            <option value="3" {{ old('kondisi_dinding', $rumah?->kondisi_dinding) == '3' ? 'selected' : '' }}>3 - Rusak Berat</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Bahan Dinding Terluas</label>
                                        <select name="bahan_dinding" class="form-select form-select-sm">
                                            <option value="1" {{ old('bahan_dinding', $rumah?->bahan_dinding) == '1' ? 'selected' : '' }}>Tembok Bata / Batako</option>
                                            <option value="2" {{ old('bahan_dinding', $rumah?->bahan_dinding) == '2' ? 'selected' : '' }}>Kayu / Papan / Triplek</option>
                                            <option value="3" {{ old('bahan_dinding', $rumah?->bahan_dinding) == '3' ? 'selected' : '' }}>Bambu / Gedek</option>
                                            <option value="4" {{ old('bahan_dinding', $rumah?->bahan_dinding) == '4' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Lantai -->
                            <div class="col-12 col-lg-4 mb-3">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-grid-3x3 text-info me-1"></i> Bagian Lantai</h6>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Kondisi Kerusakan Lantai</label>
                                        <select name="kondisi_lantai" class="form-select form-select-sm">
                                            <option value="1" {{ old('kondisi_lantai', $rumah?->kondisi_lantai) == '1' ? 'selected' : '' }}>1 - Baik</option>
                                            <option value="2" {{ old('kondisi_lantai', $rumah?->kondisi_lantai) == '2' ? 'selected' : '' }}>2 - Rusak Sedang</option>
                                            <option value="3" {{ old('kondisi_lantai', $rumah?->kondisi_lantai) == '3' ? 'selected' : '' }}>3 - Rusak Berat / Tanah</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Bahan Lantai Terluas</label>
                                        <select name="bahan_lantai" class="form-select form-select-sm">
                                            <option value="1" {{ old('bahan_lantai', $rumah?->bahan_lantai) == '1' ? 'selected' : '' }}>Keramik / Granit / Ubin</option>
                                            <option value="2" {{ old('bahan_lantai', $rumah?->bahan_lantai) == '2' ? 'selected' : '' }}>Semen / Plesteran</option>
                                            <option value="3" {{ old('bahan_lantai', $rumah?->bahan_lantai) == '3' ? 'selected' : '' }}>Tanah Langsung</option>
                                            <option value="4" {{ old('bahan_lantai', $rumah?->bahan_lantai) == '4' ? 'selected' : '' }}>Papan Kayu</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mb-4">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" onclick="goToStep('step2')">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Hunian
                    </button>
                    <button type="button" class="btn btn-primary px-4 py-2" onclick="goToStep('step4')">
                        Lanjut ke Sanitasi & Foto <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            <!-- ================= STEP 4: SANITASI & FOTO ================= -->
            <div class="tab-pane fade" id="step4" role="tabpanel">
                <div class="row">
                    <div class="col-12 col-xl-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark">
                                    <i class="bi bi-droplet-half text-primary me-2"></i> Sanitasi & Utilitas
                                </h5>
                            </div>
                            <div class="card-body pt-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Sumber Air Bersih</label>
                                        <select name="sumber_air" class="form-select form-select-sm">
                                            <option value="1" {{ old('sumber_air', $utilitas?->sumber_air) == '1' ? 'selected' : '' }}>PDAM</option>
                                            <option value="2" {{ old('sumber_air', $utilitas?->sumber_air) == '2' ? 'selected' : '' }}>Sumur Terlindung / Bor</option>
                                            <option value="3" {{ old('sumber_air', $utilitas?->sumber_air) == '3' ? 'selected' : '' }}>Sumur Tidak Terlindung</option>
                                            <option value="4" {{ old('sumber_air', $utilitas?->sumber_air) == '4' ? 'selected' : '' }}>Mata Air / Sungai</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Fasilitas MCK</label>
                                        <select name="mck" class="form-select form-select-sm">
                                            <option value="1" {{ old('mck', $utilitas?->mck) == '1' ? 'selected' : '' }}>Milik Sendiri (Di Dalam)</option>
                                            <option value="2" {{ old('mck', $utilitas?->mck) == '2' ? 'selected' : '' }}>Milik Sendiri (Di Luar)</option>
                                            <option value="3" {{ old('mck', $utilitas?->mck) == '3' ? 'selected' : '' }}>MCK Bersama / Umum</option>
                                            <option value="4" {{ old('mck', $utilitas?->mck) == '4' ? 'selected' : '' }}>Tidak Memiliki</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Tempat Pembuangan Akhir Tinja</label>
                                        <select name="jenis_tpa" class="form-select form-select-sm">
                                            <option value="1" {{ old('jenis_tpa', $utilitas?->jenis_tpa) == '1' ? 'selected' : '' }}>Tangki Septik (Septic Tank)</option>
                                            <option value="2" {{ old('jenis_tpa', $utilitas?->jenis_tpa) == '2' ? 'selected' : '' }}>Sungai / Sawah</option>
                                            <option value="3" {{ old('jenis_tpa', $utilitas?->jenis_tpa) == '3' ? 'selected' : '' }}>Lubang Tanah</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Sumber Penerangan</label>
                                        <select name="sumber_penerangan" class="form-select form-select-sm">
                                            <option value="1" {{ old('sumber_penerangan', $utilitas?->sumber_penerangan) == '1' ? 'selected' : '' }}>PLN Sendiri</option>
                                            <option value="2" {{ old('sumber_penerangan', $utilitas?->sumber_penerangan) == '2' ? 'selected' : '' }}>PLN Menumpang</option>
                                            <option value="3" {{ old('sumber_penerangan', $utilitas?->sumber_penerangan) == '3' ? 'selected' : '' }}>Non-PLN</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark">
                                    <i class="bi bi-camera-fill text-danger me-2"></i> Update Foto Lapangan
                                </h5>
                            </div>
                            <div class="card-body pt-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Foto Tampak Depan</label>
                                        <input type="file" name="foto_depan" accept="image/*" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Foto Ruang Dalam</label>
                                        <input type="file" name="foto_dalam" accept="image/*" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Foto Bagian Atap</label>
                                        <input type="file" name="foto_atap" accept="image/*" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Foto Bagian Dinding</label>
                                        <input type="file" name="foto_dinding" accept="image/*" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Foto Bagian Lantai</label>
                                        <input type="file" name="foto_lantai" accept="image/*" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label small fw-semibold">Foto MCK</label>
                                        <input type="file" name="foto_mck" accept="image/*" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Submit Update -->
                        <div class="card border-0 shadow-sm bg-light-primary mb-4">
                            <div class="card-body p-4 text-center">
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow">
                                    <i class="bi bi-save2-fill me-1"></i> SIMPAN PEMBARUAN DATA TERPADU
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mb-4">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" onclick="goToStep('step3')">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Fisik Konstruksi
                    </button>
                    <button type="submit" class="btn btn-success px-4 py-2">
                        <i class="bi bi-save2-fill me-1"></i> Simpan Pembaruan
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    // 1. Inisialisasi Peta Leaflet OpenStreetMap
    var defaultLat = -7.816667; // Titik Alun-Alun Kota Kediri
    var defaultLng = 112.011667;

    var inputLatVal = document.getElementById('editLat').value;
    var inputLongVal = document.getElementById('editLong').value;

    var startLat = inputLatVal && !isNaN(parseFloat(inputLatVal)) ? parseFloat(inputLatVal) : defaultLat;
    var startLng = inputLongVal && !isNaN(parseFloat(inputLongVal)) ? parseFloat(inputLongVal) : defaultLng;
    var initialZoom = (inputLatVal && inputLongVal) ? 16 : 13;

    var mapEdit = L.map('mapPickerEdit').setView([startLat, startLng], initialZoom);

    // Pasang Tile Layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> kontributor'
    }).addTo(mapEdit);

    // Pasang Pin Marker Draggable
    var markerEdit = L.marker([startLat, startLng], {
        draggable: true
    }).addTo(mapEdit);

    markerEdit.bindPopup("<b>Titik Lokasi Rumah</b><br>Geser pin merah ini atau klik pada peta.").openPopup();

    // Fungsi perbarui nilai input Latitude & Longitude
    function updateCoordinatesEdit(lat, lng) {
        document.getElementById('editLat').value = lat.toFixed(6);
        document.getElementById('editLong').value = lng.toFixed(6);
        markerEdit.setPopupContent("<b>Titik Lokasi Rumah</b><br>Lat: " + lat.toFixed(6) + "<br>Long: " + lng.toFixed(6));
    }

    // Jika pin marker digeser (dragend)
    markerEdit.on('dragend', function (e) {
        var pos = markerEdit.getLatLng();
        updateCoordinatesEdit(pos.lat, pos.lng);
    });

    // Jika peta diklik di mana saja
    mapEdit.on('click', function (e) {
        markerEdit.setLatLng(e.latlng);
        updateCoordinatesEdit(e.latlng.lat, e.latlng.lng);
    });

    // Sinkronisasi jika user mengetik manual angka di kotak input
    function syncMapFromInputsEdit() {
        var lat = parseFloat(document.getElementById('editLat').value);
        var lng = parseFloat(document.getElementById('editLong').value);
        if (!isNaN(lat) && !isNaN(lng)) {
            markerEdit.setLatLng([lat, lng]);
            mapEdit.panTo([lat, lng]);
            markerEdit.setPopupContent("<b>Titik Lokasi Rumah</b><br>Lat: " + lat.toFixed(6) + "<br>Long: " + lng.toFixed(6));
        }
    }
    document.getElementById('editLat').addEventListener('change', syncMapFromInputsEdit);
    document.getElementById('editLong').addEventListener('change', syncMapFromInputsEdit);

    // Ambil koordinat GPS saat ini menggunakan Geolocation API browser
    function getCurrentLocationEdit() {
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                updateCoordinatesEdit(lat, lng);
                markerEdit.setLatLng([lat, lng]);
                mapEdit.setView([lat, lng], 17);
                markerEdit.openPopup();
            }, function(error) {
                alert("Gagal mengambil lokasi GPS: " + error.message + ". Pastikan izin lokasi (GPS) diaktifkan pada browser.");
            }, {
                enableHighAccuracy: true
            });
        } else {
            alert("Browser Anda tidak mendukung fitur Geolocation.");
        }
    }

    // Perbaiki rendering peta saat tab Langkah 2 dibuka
    var step2TabEdit = document.querySelector('#tab-step2');
    if (step2TabEdit) {
        step2TabEdit.addEventListener('shown.bs.tab', function () {
            setTimeout(function() {
                mapEdit.invalidateSize();
            }, 200);
        });
    }

    // Fungsi untuk berpindah tab langkah wizard
    function goToStep(stepId) {
        var triggerEl = document.querySelector('#tab-' + stepId);
        if (triggerEl) {
            var tab = new bootstrap.Tab(triggerEl);
            tab.show();
            window.scrollTo({ top: 120, behavior: 'smooth' });
            if (stepId === 'step2') {
                setTimeout(function() {
                    mapEdit.invalidateSize();
                }, 200);
            }
        }
    }
</script>
@endpush
@endsection
