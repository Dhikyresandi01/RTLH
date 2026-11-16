@extends('app')

@section('title', 'Tambah Data Terpadu Calon Penerima RTLH')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    #mapPicker {
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
                        <li class="breadcrumb-item active" aria-current="page">Formulir Terpadu RTLH</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-1 text-primary">Input Data Terpadu Calon Penerima RTLH</h3>
                <p class="text-muted small mb-0">Pendataan lengkap: Identitas Warga, Status Kepemilikan Hunian, dan Survei Fisik Konstruksi Bangunan dalam satu form.</p>
            </div>
            <div class="col-12 col-md-5 text-md-end mt-3 mt-md-0">
                <a href="{{ route('warga') }}" class="btn btn-outline-secondary shadow-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
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
                    <p class="small mb-1">Mohon periksa kembali kolom-kolom inputan yang ditandai merah di bawah ini:</p>
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

    <form action="{{ route('warga.store') }}" method="POST" enctype="multipart/form-data" id="formRTLH">
        @csrf

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

        <!-- Tab Content -->
        <div class="tab-content" id="formTabsContent">

            <!-- ================= STEP 1: DATA WARGA & SOSIO-EKONOMI ================= -->
            <div class="tab-pane fade show active" id="step1" role="tabpanel">
                <div class="row">
                    <!-- Kolom Utama: Identitas Kependudukan -->
                    <div class="col-12 col-xl-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark">
                                    <i class="bi bi-person-vcard-fill text-primary me-2"></i> Identitas Kependudukan Calon Penerima
                                </h5>
                            </div>
                            <div class="card-body pt-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nomor Induk Kependudukan (NIK) <span class="text-danger">*</span></label>
                                        <input type="text" name="nik" maxlength="16" class="form-control @error('nik') is-invalid @enderror" 
                                               placeholder="16 Digit NIK sesuai KTP" value="{{ old('nik') }}" required>
                                        @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Nomor Kartu Keluarga (KK) <span class="text-danger">*</span></label>
                                        <input type="text" name="no_kk" maxlength="16" class="form-control @error('no_kk') is-invalid @enderror" 
                                               placeholder="16 Digit No. KK" value="{{ old('no_kk') }}" required>
                                        @error('no_kk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-12 col-md-8">
                                        <label class="form-label fw-semibold">Nama Lengkap (sesuai KTP) <span class="text-danger">*</span></label>
                                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                                               placeholder="Nama Lengkap Kepala Keluarga" value="{{ old('nama') }}" required>
                                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-12 col-md-4">
                                        <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select name="jenis_kelamin" class="form-select @error('jenis_kelamin') is-invalid @enderror" required>
                                            <option value="">-- Pilih --</option>
                                            <option value="1" {{ old('jenis_kelamin') == '1' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="2" {{ old('jenis_kelamin') == '2' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('jenis_kelamin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Tempat Lahir</label>
                                        <input type="text" name="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" 
                                               placeholder="Kota / Kabupaten Lahir" value="{{ old('tempat_lahir') }}">
                                        @error('tempat_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Tanggal Lahir</label>
                                        <input type="date" name="tgl_lahir" class="form-control @error('tgl_lahir') is-invalid @enderror" 
                                               value="{{ old('tgl_lahir') }}">
                                        @error('tgl_lahir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Alamat Lengkap / Nama Jalan</label>
                                        <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                                               placeholder="Contoh: Jl. Semeru No. 12" value="{{ old('alamat') }}">
                                        @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-6 col-md-3">
                                        <label class="form-label fw-semibold">RT</label>
                                        <input type="text" name="rt" maxlength="5" class="form-control @error('rt') is-invalid @enderror" 
                                               placeholder="001" value="{{ old('rt') }}">
                                    </div>

                                    <div class="col-6 col-md-3">
                                        <label class="form-label fw-semibold">RW</label>
                                        <input type="text" name="rw" maxlength="5" class="form-control @error('rw') is-invalid @enderror" 
                                               placeholder="002" value="{{ old('rw') }}">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Kelurahan / Wilayah <span class="text-danger">*</span></label>
                                        <select name="id_wilayah" class="form-select @error('id_wilayah') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kelurahan --</option>
                                            @foreach($wilayahs as $wilayah)
                                                <option value="{{ $wilayah->id_wilayah }}" {{ old('id_wilayah') == $wilayah->id_wilayah ? 'selected' : '' }}>
                                                    {{ $wilayah->desa }} (Kec. {{ $wilayah->kecamatan }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_wilayah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">No. Handphone / WhatsApp</label>
                                        <input type="text" name="no_hp" maxlength="15" class="form-control @error('no_hp') is-invalid @enderror" 
                                               placeholder="Contoh: 081234567890" value="{{ old('no_hp') }}">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status Pernikahan <span class="text-danger">*</span></label>
                                        <select name="kawin" class="form-select @error('kawin') is-invalid @enderror" required>
                                            <option value="">-- Pilih Status --</option>
                                            <option value="1" {{ old('kawin') == '1' ? 'selected' : '' }}>Kawin / Menikah</option>
                                            <option value="2" {{ old('kawin') == '2' ? 'selected' : '' }}>Belum Kawin</option>
                                            <option value="3" {{ old('kawin') == '3' ? 'selected' : '' }}>Cerai Hidup / Mati</option>
                                        </select>
                                        @error('kawin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Sosio-Ekonomi -->
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark">
                                    <i class="bi bi-cash-stack text-success me-2"></i> Kondisi Sosial & Ekonomi
                                </h5>
                            </div>
                            <div class="card-body pt-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Pendidikan Terakhir</label>
                                        <select name="pendidikan" class="form-select">
                                            <option value="">-- Pilih Pendidikan --</option>
                                            <option value="1" {{ old('pendidikan') == '1' ? 'selected' : '' }}>Tidak Sekolah / Belum Tamat SD</option>
                                            <option value="2" {{ old('pendidikan') == '2' ? 'selected' : '' }}>SD / Sederajat</option>
                                            <option value="3" {{ old('pendidikan') == '3' ? 'selected' : '' }}>SMP / Sederajat</option>
                                            <option value="4" {{ old('pendidikan') == '4' ? 'selected' : '' }}>SMA / SMK / Sederajat</option>
                                            <option value="5" {{ old('pendidikan') == '5' ? 'selected' : '' }}>Diploma / Sarjana</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Pekerjaan Utama</label>
                                        <select name="pekerjaan" class="form-select">
                                            <option value="">-- Pilih Pekerjaan --</option>
                                            <option value="1" {{ old('pekerjaan') == '1' ? 'selected' : '' }}>Buruh Harian Lepas / Serabutan</option>
                                            <option value="2" {{ old('pekerjaan') == '2' ? 'selected' : '' }}>Petani / Pekebun</option>
                                            <option value="3" {{ old('pekerjaan') == '3' ? 'selected' : '' }}>Pedagang Kecil / UMKM</option>
                                            <option value="4" {{ old('pekerjaan') == '4' ? 'selected' : '' }}>Karyawan Swasta</option>
                                            <option value="5" {{ old('pekerjaan') == '5' ? 'selected' : '' }}>Tidak / Belum Bekerja</option>
                                            <option value="6" {{ old('pekerjaan') == '6' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Penghasilan Rata-rata / Bulan</label>
                                        <select name="penghasilan" class="form-select">
                                            <option value="">-- Pilih Penghasilan --</option>
                                            <option value="1" {{ old('penghasilan') == '1' ? 'selected' : '' }}>< Rp 1.000.000</option>
                                            <option value="2" {{ old('penghasilan') == '2' ? 'selected' : '' }}>Rp 1.000.000 - Rp 2.000.000</option>
                                            <option value="3" {{ old('penghasilan') == '3' ? 'selected' : '' }}>Rp 2.000.000 - Rp 3.000.000</option>
                                            <option value="4" {{ old('penghasilan') == '4' ? 'selected' : '' }}>> Rp 3.000.000</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Desil DTSEN / DTKS Kemensos</label>
                                        <select name="dtsen" class="form-select">
                                            <option value="">-- Pilih Status Desil --</option>
                                            <option value="1" {{ old('dtsen') == '1' ? 'selected' : '' }}>Desil 1 (Sangat Miskin)</option>
                                            <option value="2" {{ old('dtsen') == '2' ? 'selected' : '' }}>Desil 2 (Miskin)</option>
                                            <option value="3" {{ old('dtsen') == '3' ? 'selected' : '' }}>Desil 3 (Hampir Miskin)</option>
                                            <option value="4" {{ old('dtsen') == '4' ? 'selected' : '' }}>Desil 4 (Rentan Miskin)</option>
                                            <option value="0" {{ old('dtsen') == '0' ? 'selected' : '' }}>Non-DTKS</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">No. NPWP (Bila Ada)</label>
                                        <input type="text" name="no_npwp" maxlength="20" class="form-control" placeholder="Nomor NPWP" value="{{ old('no_npwp') }}">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="form-check p-3 bg-light rounded mt-2">
                                            <input class="form-check-input ms-0 me-2" type="checkbox" name="mampu_swadaya" value="1" id="mampuSwadaya" {{ old('mampu_swadaya') ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-dark" for="mampuSwadaya">
                                                Mampu Berswadaya / Gotong Royong
                                            </label>
                                            <small class="text-muted d-block ms-4">Keluarga sanggup menyediakan tenaga/material tambahan.</small>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label fw-semibold">Jenis Bantuan <span class="text-muted fw-normal"></span></label>
                                        <select name="id_program" class="form-select @error('id_program') is-invalid @enderror">
                                            <option value="">-- Pilih Jenis Bantuan --</option>
                                            @forelse($programs as $prog)
                                                <option value="{{ $prog->id_program }}" {{ old('id_program') == $prog->id_program ? 'selected' : '' }}>
                                                    {{ $prog->nama_program }}
                                                </option>
                                            @empty
                                                <option value="" disabled>Belum ada jenis bantuan — hubungi Superadmin</option>
                                            @endforelse
                                        </select>
                                        @error('id_program') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        <small class="text-muted">Jenis bantuan yang akan diusulkan untuk warga ini</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Samping: Upload Dokumen Warga -->
                    <div class="col-12 col-xl-4">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark">
                                    <i class="bi bi-file-earmark-arrow-up-fill text-primary me-2"></i> Berkas Kependudukan
                                </h5>
                            </div>
                            <div class="card-body pt-4">
                                <p class="text-muted small mb-3">Format berkas: JPG, PNG, atau PDF (maks. 2MB).</p>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Foto KTP Calon Penerima</label>
                                    <input type="file" name="foto_ktp" accept="image/*,application/pdf" class="form-control form-control-sm">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Foto Kartu Keluarga (KK)</label>
                                    <input type="file" name="foto_kk" accept="image/*,application/pdf" class="form-control form-control-sm">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Bukti DTKS / DTSEN / Surat Keterangan</label>
                                    <input type="file" name="foto_dtsen" accept="image/*,application/pdf" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                        <div class="card bg-light-primary border-0 p-3 mb-4">
                            <div class="d-flex">
                                <i class="bi bi-info-circle-fill text-primary fs-4 me-2"></i>
                                <small class="text-dark">
                                    Pastikan NIK dan No. KK sudah terverifikasi dengan data Disdukcapil sebelum melanjutkan ke pendataan hunian.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Navigasi Bawah Langkah 1 -->
                <div class="d-flex justify-content-end mb-4">
                    <button type="button" class="btn btn-primary px-4 py-2 fw-semibold" onclick="goToStep('step2')">
                        Lanjut ke Kepemilikan Hunian <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            <!-- ================= STEP 2: KEPEMILIKAN HUNIAN & LAHAN ================= -->
            <div class="tab-pane fade" id="step2" role="tabpanel">
                <div class="row">
                    <!-- Kolom Utama: Legalitas & Ukuran -->
                    <div class="col-12 col-xl-8">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark">
                                    <i class="bi bi-shield-check text-primary me-2"></i> Legalitas Kepemilikan Rumah & Hak Atas Tanah
                                </h5>
                            </div>
                            <div class="card-body pt-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status Kepemilikan Rumah</label>
                                        <select name="status_rumah" class="form-select @error('status_rumah') is-invalid @enderror">
                                            <option value="">-- Pilih Status Rumah --</option>
                                            <option value="1" {{ old('status_rumah') == '1' ? 'selected' : '' }}>1 - Milik Sendiri</option>
                                            <option value="2" {{ old('status_rumah') == '2' ? 'selected' : '' }}>2 - Sewa / Kontrak</option>
                                            <option value="3" {{ old('status_rumah') == '3' ? 'selected' : '' }}>3 - Menumpang / Milik Keluarga</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Status Hak Atas Tanah</label>
                                        <select name="status_tanah" class="form-select @error('status_tanah') is-invalid @enderror">
                                            <option value="">-- Pilih Status Tanah --</option>
                                            <option value="1" {{ old('status_tanah') == '1' ? 'selected' : '' }}>1 - Milik Sendiri (SHM / Hak Milik)</option>
                                            <option value="2" {{ old('status_tanah') == '2' ? 'selected' : '' }}>2 - Bukan Milik Sendiri (Girik / Letter C)</option>
                                            <option value="3" {{ old('status_tanah') == '3' ? 'selected' : '' }}>3 - Tanah Negara / Kas Desa / Lainnya</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Luas Bangunan Rumah (m²)</label>
                                        <div class="input-group">
                                            <input type="number" step="0.1" name="luas_rumah" class="form-control" placeholder="Contoh: 36" value="{{ old('luas_rumah') }}">
                                            <span class="input-group-text bg-light">m²</span>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold">Luas Tanah / Lahan (m²)</label>
                                        <div class="input-group">
                                            <input type="number" step="0.1" name="luas_tanah" class="form-control" placeholder="Contoh: 60" value="{{ old('luas_tanah') }}">
                                            <span class="input-group-text bg-light">m²</span>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="form-check p-3 bg-light rounded mt-2">
                                            <input class="form-check-input ms-0 me-2" type="checkbox" name="rumah_lain" value="1" id="inputRumahLain" {{ old('rumah_lain') ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-dark" for="inputRumahLain">
                                                Memiliki Rumah di Tempat Lain
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <div class="form-check p-3 bg-light rounded mt-2">
                                            <input class="form-check-input ms-0 me-2" type="checkbox" name="tanah_lain" value="1" id="inputTanahLain" {{ old('tanah_lain') ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold text-dark" for="inputTanahLain">
                                                Memiliki Tanah di Tempat Lain
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Samping: Kawasan, Penghuni & Titik Koordinat -->
                    <div class="col-12 col-xl-4">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark">
                                    <i class="bi bi-geo-alt-fill text-danger me-2"></i> Kawasan & Koordinat
                                </h5>
                            </div>
                            <div class="card-body pt-4">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Kategori Kawasan Hunian</label>
                                    <select name="kawasan" class="form-select">
                                        <option value="">-- Pilih Kawasan --</option>
                                        <option value="1" {{ old('kawasan') == '1' ? 'selected' : '' }}>Permukiman Perkotaan</option>
                                        <option value="2" {{ old('kawasan') == '2' ? 'selected' : '' }}>Bantaran Sungai / Sempadan</option>
                                        <option value="3" {{ old('kawasan') == '3' ? 'selected' : '' }}>Kawasan Kumuh</option>
                                        <option value="4" {{ old('kawasan') == '4' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Jumlah KK dalam 1 Rumah</label>
                                    <input type="number" min="1" name="jml_kk" class="form-control" value="{{ old('jml_kk', 1) }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Jumlah Penghuni (Jiwa)</label>
                                    <input type="number" min="1" name="jml_penghuni" class="form-control" value="{{ old('jml_penghuni', 1) }}">
                                </div>

                                <hr class="my-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold text-dark mb-0">
                                        <i class="bi bi-geo-alt-fill text-danger me-1"></i> Titik Lokasi Rumah (OSM)
                                    </h6>
                                    <button type="button" class="btn btn-xs btn-outline-primary py-1 px-2" style="font-size: 0.75rem;" onclick="getCurrentLocation()" title="Ambil GPS perangkat Anda saat ini">
                                        <i class="bi bi-crosshair me-1"></i> Lokasi Saya
                                    </button>
                                </div>

                                <!-- Wadah Peta Leaflet OpenStreetMap -->
                                <div class="mb-2">
                                    <div id="mapPicker"></div>
                                    <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">
                                        <i class="bi bi-cursor-fill text-primary me-1"></i> Klik pada peta atau geser pin merah untuk menandai lokasi hunian.
                                    </small>
                                </div>

                                <div class="row g-2 mb-1">
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold mb-1">Latitude</label>
                                        <input type="text" name="latitude" id="inputLat" class="form-control form-control-sm" placeholder="-7.816667" value="{{ old('latitude') }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-semibold mb-1">Longitude</label>
                                        <input type="text" name="longitude" id="inputLong" class="form-control form-control-sm" placeholder="112.011667" value="{{ old('longitude') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Navigasi Bawah Langkah 2 -->
                <div class="d-flex justify-content-between mb-4">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" onclick="goToStep('step1')">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Data Warga
                    </button>
                    <button type="button" class="btn btn-primary px-4 py-2 fw-semibold" onclick="goToStep('step3')">
                        Lanjut ke Fisik Konstruksi <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            <!-- ================= STEP 3: FISIK KONSTRUKSI RUMAH ================= -->
            <div class="tab-pane fade" id="step3" role="tabpanel">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-bricks text-danger me-2"></i> Evaluasi Kerusakan Konstruksi Utama Bangunan
                        </h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row">
                            <!-- Bagian Atap -->
                            <div class="col-12 col-lg-4 mb-3">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="p-2 rounded bg-danger text-white me-2">
                                            <i class="bi bi-house-up fs-5"></i>
                                        </div>
                                        <h6 class="fw-bold mb-0 text-dark">Bagian Atap Bangunan</h6>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Kondisi Kerusakan Atap</label>
                                        <select name="kondisi_atap" class="form-select form-select-sm">
                                            <option value="">-- Pilih Kondisi --</option>
                                            <option value="1" {{ old('kondisi_atap') == '1' ? 'selected' : '' }}>1 - Baik (Utuh / Layak)</option>
                                            <option value="2" {{ old('kondisi_atap') == '2' ? 'selected' : '' }}>2 - Rusak Sedang (Sebagian Bocor)</option>
                                            <option value="3" {{ old('kondisi_atap') == '3' ? 'selected' : '' }}>3 - Rusak Berat (Bocor Parah / Rawan Runtuh)</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Bahan Penutup Atap</label>
                                        <select name="bahan_atap" class="form-select form-select-sm">
                                            <option value="">-- Pilih Bahan --</option>
                                            <option value="1" {{ old('bahan_atap') == '1' ? 'selected' : '' }}>Genteng Tanah Liat / Keramik</option>
                                            <option value="2" {{ old('bahan_atap') == '2' ? 'selected' : '' }}>Seng / Asbes</option>
                                            <option value="3" {{ old('bahan_atap') == '3' ? 'selected' : '' }}>Ijuk / Rumbia / Anyaman</option>
                                            <option value="4" {{ old('bahan_atap') == '4' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="form-label small fw-semibold">Rangka Atap / Kuda-kuda</label>
                                        <select name="kerangka_atap" class="form-select form-select-sm">
                                            <option value="">-- Pilih --</option>
                                            <option value="1" {{ old('kerangka_atap') == '1' ? 'selected' : '' }}>1 - Baik</option>
                                            <option value="2" {{ old('kerangka_atap') == '2' ? 'selected' : '' }}>2 - Sedang</option>
                                            <option value="3" {{ old('kerangka_atap') == '3' ? 'selected' : '' }}>3 - Rusak / Lapuk</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Bagian Dinding -->
                            <div class="col-12 col-lg-4 mb-3">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="p-2 rounded bg-warning text-dark me-2">
                                            <i class="bi bi-border-all fs-5"></i>
                                        </div>
                                        <h6 class="fw-bold mb-0 text-dark">Bagian Dinding Bangunan</h6>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Kondisi Kerusakan Dinding</label>
                                        <select name="kondisi_dinding" class="form-select form-select-sm">
                                            <option value="">-- Pilih Kondisi --</option>
                                            <option value="1" {{ old('kondisi_dinding') == '1' ? 'selected' : '' }}>1 - Baik (Kokoh & Tegak)</option>
                                            <option value="2" {{ old('kondisi_dinding') == '2' ? 'selected' : '' }}>2 - Rusak Sedang (Retak Sebagian)</option>
                                            <option value="3" {{ old('kondisi_dinding') == '3' ? 'selected' : '' }}>3 - Rusak Berat (Retak Parah / Bolong / Miring)</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Bahan Dinding Terluas</label>
                                        <select name="bahan_dinding" class="form-select form-select-sm">
                                            <option value="">-- Pilih Bahan --</option>
                                            <option value="1" {{ old('bahan_dinding') == '1' ? 'selected' : '' }}>Tembok Bata / Batako</option>
                                            <option value="2" {{ old('bahan_dinding') == '2' ? 'selected' : '' }}>Kayu / Papan / Triplek</option>
                                            <option value="3" {{ old('bahan_dinding') == '3' ? 'selected' : '' }}>Bambu / Gedek / Sasak</option>
                                            <option value="4" {{ old('bahan_dinding') == '4' ? 'selected' : '' }}>Lainnya</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="form-label small fw-semibold">Kondisi Kolom / Tiang</label>
                                        <select name="kolom" class="form-select form-select-sm">
                                            <option value="">-- Pilih --</option>
                                            <option value="1" {{ old('kolom') == '1' ? 'selected' : '' }}>1 - Baik</option>
                                            <option value="2" {{ old('kolom') == '2' ? 'selected' : '' }}>2 - Sedang</option>
                                            <option value="3" {{ old('kolom') == '3' ? 'selected' : '' }}>3 - Rusak / Keropos</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Bagian Lantai -->
                            <div class="col-12 col-lg-4 mb-3">
                                <div class="border rounded p-3 h-100 bg-light">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="p-2 rounded bg-info text-white me-2">
                                            <i class="bi bi-grid-3x3 fs-5"></i>
                                        </div>
                                        <h6 class="fw-bold mb-0 text-dark">Bagian Lantai Bangunan</h6>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Kondisi Kerusakan Lantai</label>
                                        <select name="kondisi_lantai" class="form-select form-select-sm">
                                            <option value="">-- Pilih Kondisi --</option>
                                            <option value="1" {{ old('kondisi_lantai') == '1' ? 'selected' : '' }}>1 - Baik (Kedap Air & Bersih)</option>
                                            <option value="2" {{ old('kondisi_lantai') == '2' ? 'selected' : '' }}>2 - Rusak Sedang (Plester Retak)</option>
                                            <option value="3" {{ old('kondisi_lantai') == '3' ? 'selected' : '' }}>3 - Rusak Berat (Tanah / Lembab Becek)</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Bahan Lantai Terluas</label>
                                        <select name="bahan_lantai" class="form-select form-select-sm">
                                            <option value="">-- Pilih Bahan --</option>
                                            <option value="1" {{ old('bahan_lantai') == '1' ? 'selected' : '' }}>Keramik / Granit / Ubin</option>
                                            <option value="2" {{ old('bahan_lantai') == '2' ? 'selected' : '' }}>Semen / Plesteran</option>
                                            <option value="3" {{ old('bahan_lantai') == '3' ? 'selected' : '' }}>Tanah Langsung</option>
                                            <option value="4" {{ old('bahan_lantai') == '4' ? 'selected' : '' }}>Papan Kayu / Bambu</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="form-label small fw-semibold">Kondisi Pondasi</label>
                                        <select name="pondasi" class="form-select form-select-sm">
                                            <option value="">-- Pilih --</option>
                                            <option value="1" {{ old('pondasi') == '1' ? 'selected' : '' }}>1 - Baik</option>
                                            <option value="2" {{ old('pondasi') == '2' ? 'selected' : '' }}>2 - Sedang</option>
                                            <option value="3" {{ old('pondasi') == '3' ? 'selected' : '' }}>3 - Rusak / Tanpa Pondasi</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Struktur Tambahan (Sloof & Plafon) -->
                        <div class="row g-3 pt-2">
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-semibold">Balok Struktur</label>
                                <select name="balok" class="form-select form-select-sm">
                                    <option value="">-- Pilih --</option>
                                    <option value="1" {{ old('balok') == '1' ? 'selected' : '' }}>1 - Baik</option>
                                    <option value="2" {{ old('balok') == '2' ? 'selected' : '' }}>2 - Sedang</option>
                                    <option value="3" {{ old('balok') == '3' ? 'selected' : '' }}>3 - Rusak</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-semibold">Sloof Bawah</label>
                                <select name="sloof" class="form-select form-select-sm">
                                    <option value="">-- Pilih --</option>
                                    <option value="1" {{ old('sloof') == '1' ? 'selected' : '' }}>1 - Baik</option>
                                    <option value="2" {{ old('sloof') == '2' ? 'selected' : '' }}>2 - Sedang</option>
                                    <option value="3" {{ old('sloof') == '3' ? 'selected' : '' }}>3 - Rusak / Tanpa Sloof</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label small fw-semibold">Plafon Rumah</label>
                                <select name="plafon" class="form-select form-select-sm">
                                    <option value="">-- Pilih --</option>
                                    <option value="1" {{ old('plafon') == '1' ? 'selected' : '' }}>1 - Baik</option>
                                    <option value="2" {{ old('plafon') == '2' ? 'selected' : '' }}>2 - Sedang</option>
                                    <option value="3" {{ old('plafon') == '3' ? 'selected' : '' }}>3 - Rusak / Tanpa Plafon</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Navigasi Bawah Langkah 3 -->
                <div class="d-flex justify-content-between mb-4">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" onclick="goToStep('step2')">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Kepemilikan Hunian
                    </button>
                    <button type="button" class="btn btn-primary px-4 py-2 fw-semibold" onclick="goToStep('step4')">
                        Lanjut ke Sanitasi & Dokumentasi <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

            <!-- ================= STEP 4: SANITASI, UTILITAS & FOTO ================= -->
            <div class="tab-pane fade" id="step4" role="tabpanel">
                <div class="row">
                    <!-- Kolom Kiri: Sanitasi & Utilitas -->
                    <div class="col-12 col-xl-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark">
                                    <i class="bi bi-droplet-half text-primary me-2"></i> Sanitasi & Fasilitas Utilitas
                                </h5>
                            </div>
                            <div class="card-body pt-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold small">Sumber Air Bersih / Minum</label>
                                        <select name="sumber_air" class="form-select form-select-sm">
                                            <option value="">-- Pilih Sumber Air --</option>
                                            <option value="1" {{ old('sumber_air') == '1' ? 'selected' : '' }}>PDAM / Air Perpipaan</option>
                                            <option value="2" {{ old('sumber_air') == '2' ? 'selected' : '' }}>Sumur Terlindung / Pompa Bor</option>
                                            <option value="3" {{ old('sumber_air') == '3' ? 'selected' : '' }}>Sumur Tidak Terlindung</option>
                                            <option value="4" {{ old('sumber_air') == '4' ? 'selected' : '' }}>Mata Air / Hujan / Sungai</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold small">Fasilitas MCK (Kamar Mandi & WC)</label>
                                        <select name="mck" class="form-select form-select-sm">
                                            <option value="">-- Pilih MCK --</option>
                                            <option value="1" {{ old('mck') == '1' ? 'selected' : '' }}>Milik Sendiri (Di Dalam Rumah)</option>
                                            <option value="2" {{ old('mck') == '2' ? 'selected' : '' }}>Milik Sendiri (Di Luar Rumah)</option>
                                            <option value="3" {{ old('mck') == '3' ? 'selected' : '' }}>MCK Bersama / Umum</option>
                                            <option value="4" {{ old('mck') == '4' ? 'selected' : '' }}>Tidak Memiliki MCK</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold small">Jenis Kloset / Jamban</label>
                                        <select name="jenis_jamban" class="form-select form-select-sm">
                                            <option value="">-- Pilih Jenis Jamban --</option>
                                            <option value="1" {{ old('jenis_jamban') == '1' ? 'selected' : '' }}>Leher Angsa</option>
                                            <option value="2" {{ old('jenis_jamban') == '2' ? 'selected' : '' }}>Plengsengan dengan Tutup</option>
                                            <option value="3" {{ old('jenis_jamban') == '3' ? 'selected' : '' }}>Cemplung / Cubluk Terbuka</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold small">Tempat Pembuangan Akhir Tinja</label>
                                        <select name="jenis_tpa" class="form-select form-select-sm">
                                            <option value="">-- Pilih TPA Tinja --</option>
                                            <option value="1" {{ old('jenis_tpa') == '1' ? 'selected' : '' }}>Tangki Septik (Septic Tank)</option>
                                            <option value="2" {{ old('jenis_tpa') == '2' ? 'selected' : '' }}>Kolam / Sawah / Sungai</option>
                                            <option value="3" {{ old('jenis_tpa') == '3' ? 'selected' : '' }}>Lubang Tanah</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold small">Sumber Penerangan (Listrik)</label>
                                        <select name="sumber_penerangan" class="form-select form-select-sm">
                                            <option value="">-- Pilih Penerangan --</option>
                                            <option value="1" {{ old('sumber_penerangan') == '1' ? 'selected' : '' }}>Listrik PLN Meteran Sendiri</option>
                                            <option value="2" {{ old('sumber_penerangan') == '2' ? 'selected' : '' }}>Listrik PLN Sambung Tetangga</option>
                                            <option value="3" {{ old('sumber_penerangan') == '3' ? 'selected' : '' }}>Bukan Listrik</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold small">Daya Listrik</label>
                                        <select name="daya_listrik" class="form-select form-select-sm">
                                            <option value="">-- Pilih Daya --</option>
                                            <option value="1" {{ old('daya_listrik') == '1' ? 'selected' : '' }}>450 VA (Bersubsidi)</option>
                                            <option value="2" {{ old('daya_listrik') == '2' ? 'selected' : '' }}>900 VA</option>
                                            <option value="3" {{ old('daya_listrik') == '3' ? 'selected' : '' }}>1300 VA</option>
                                            <option value="0" {{ old('daya_listrik') == '0' ? 'selected' : '' }}>Tanpa Listrik</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold small">Penghawaan & Ventilasi</label>
                                        <select name="ventilasi" class="form-select form-select-sm">
                                            <option value="">-- Pilih --</option>
                                            <option value="1" {{ old('ventilasi') == '1' ? 'selected' : '' }}>Cukup (≥ 10% Luas Lantai)</option>
                                            <option value="2" {{ old('ventilasi') == '2' ? 'selected' : '' }}>Kurang (< 10% Luas Lantai)</option>
                                            <option value="3" {{ old('ventilasi') == '3' ? 'selected' : '' }}>Tidak Ada Ventilasi</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-semibold small">Pencahayaan (Jendela)</label>
                                        <select name="jendela" class="form-select form-select-sm">
                                            <option value="">-- Pilih --</option>
                                            <option value="1" {{ old('jendela') == '1' ? 'selected' : '' }}>Ada dan Berfungsi Baik</option>
                                            <option value="2" {{ old('jendela') == '2' ? 'selected' : '' }}>Ada namun Tertutup / Rusak</option>
                                            <option value="3" {{ old('jendela') == '3' ? 'selected' : '' }}>Tidak Memiliki Jendela</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Upload Foto Fisik Rumah Lapangan -->
                    <div class="col-12 col-xl-6">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="card-title mb-0 fw-bold text-dark">
                                    <i class="bi bi-camera-fill text-danger me-2"></i> Upload Foto Dokumentasi Fisik Lapangan
                                </h5>
                            </div>
                            <div class="card-body pt-4">
                                <p class="text-muted small mb-3">Format foto: JPG, JPEG, PNG (Maksimal 2MB per foto).</p>

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
                                        <label class="form-label small fw-semibold">Foto MCK / Sanitasi</label>
                                        <input type="file" name="foto_mck" accept="image/*" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Submit Akhir -->
                        <div class="card border-0 shadow-sm bg-light-primary mb-4">
                            <div class="card-body p-4 text-center">
                                <h6 class="fw-bold text-primary mb-2">Semua Data Sudah Lengkap Diisi?</h6>
                                <p class="text-muted small mb-3">Klik tombol di bawah ini untuk menyimpan seluruh data calon penerima, berkas dokumen, kepemilikan hunian, dan survei teknis fisik rumah secara bersamaan ke database.</p>
                                
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold shadow">
                                    <i class="bi bi-check2-circle me-1"></i> SIMPAN SELURUH DATA TERPADU RTLH
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Navigasi Bawah Langkah 4 -->
                <div class="d-flex justify-content-between mb-4">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" onclick="goToStep('step3')">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Fisik Konstruksi
                    </button>
                    <button type="submit" class="btn btn-success px-4 py-2 fw-semibold">
                        <i class="bi bi-save2-fill me-1"></i> Simpan Sekarang
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

    var inputLatVal = document.getElementById('inputLat').value;
    var inputLongVal = document.getElementById('inputLong').value;

    var startLat = inputLatVal && !isNaN(parseFloat(inputLatVal)) ? parseFloat(inputLatVal) : defaultLat;
    var startLng = inputLongVal && !isNaN(parseFloat(inputLongVal)) ? parseFloat(inputLongVal) : defaultLng;
    var initialZoom = (inputLatVal && inputLongVal) ? 16 : 13;

    var map = L.map('mapPicker').setView([startLat, startLng], initialZoom);

    // Pasang Tile Layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> kontributor'
    }).addTo(map);

    // Pasang Pin Marker Draggable
    var marker = L.marker([startLat, startLng], {
        draggable: true
    }).addTo(map);

    marker.bindPopup("<b>Titik Lokasi Rumah</b><br>Geser pin merah ini atau klik pada peta.").openPopup();

    // Fungsi perbarui nilai input Latitude & Longitude
    function updateCoordinates(lat, lng) {
        document.getElementById('inputLat').value = lat.toFixed(6);
        document.getElementById('inputLong').value = lng.toFixed(6);
        marker.setPopupContent("<b>Titik Lokasi Rumah</b><br>Lat: " + lat.toFixed(6) + "<br>Long: " + lng.toFixed(6));
    }

    // Jika pin marker digeser (dragend)
    marker.on('dragend', function (e) {
        var pos = marker.getLatLng();
        updateCoordinates(pos.lat, pos.lng);
    });

    // Jika peta diklik di mana saja
    map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        updateCoordinates(e.latlng.lat, e.latlng.lng);
    });

    // Sinkronisasi jika user mengetik manual angka di kotak input
    function syncMapFromInputs() {
        var lat = parseFloat(document.getElementById('inputLat').value);
        var lng = parseFloat(document.getElementById('inputLong').value);
        if (!isNaN(lat) && !isNaN(lng)) {
            marker.setLatLng([lat, lng]);
            map.panTo([lat, lng]);
            marker.setPopupContent("<b>Titik Lokasi Rumah</b><br>Lat: " + lat.toFixed(6) + "<br>Long: " + lng.toFixed(6));
        }
    }
    document.getElementById('inputLat').addEventListener('change', syncMapFromInputs);
    document.getElementById('inputLong').addEventListener('change', syncMapFromInputs);

    // Ambil koordinat GPS saat ini menggunakan Geolocation API browser
    function getCurrentLocation() {
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                updateCoordinates(lat, lng);
                marker.setLatLng([lat, lng]);
                map.setView([lat, lng], 17);
                marker.openPopup();
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
    var step2Tab = document.querySelector('#tab-step2');
    if (step2Tab) {
        step2Tab.addEventListener('shown.bs.tab', function () {
            setTimeout(function() {
                map.invalidateSize();
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
                    map.invalidateSize();
                }, 200);
            }
        }
    }
</script>
@endpush
@endsection
