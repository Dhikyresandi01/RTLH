@extends('app')

@section('title', 'Tambah Data Hunian')

@section('content')
<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-12 col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('hunian.index') }}">Kepemilikan Hunian</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah Data Hunian</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-primary">Tambah Data Kepemilikan Hunian & Lahan</h3>
                <p class="text-muted small mb-0">Input informasi kepemilikan rumah, hak atas tanah, luas, dan koordinat calon penerima</p>
            </div>
            <div class="col-12 col-md-6 text-md-end mt-3 mt-md-0">
                <a href="{{ route('hunian.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>

    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible show fade">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-octagon-fill fs-4 me-3"></i>
                <div>
                    <strong>Terdapat kesalahan pengisian data!</strong>
                    <ul class="mb-0 mt-1 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('hunian.store') }}" method="POST">
        @csrf

        <div class="row">
            <!-- Kolom Kiri: Pemilihan Warga & Status Kepemilikan -->
            <div class="col-12 col-xl-8">
                <!-- Calon Penerima -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-person-fill text-primary me-2"></i> Calon Penerima Bantuan
                        </h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Warga / Kepala Keluarga <span class="text-danger">*</span></label>
                            <select name="id_warga" class="form-select @error('id_warga') is-invalid @enderror" required>
                                <option value="">-- Cari & Pilih Calon Penerima --</option>
                                @foreach($wargas as $w)
                                    <option value="{{ $w->id_warga }}" 
                                        {{ (old('id_warga', $selectedWarga?->id_warga) == $w->id_warga) ? 'selected' : '' }}>
                                        {{ $w->nama }} (NIK: {{ $w->nik }}) - {{ $w->wilayah->desa ?? 'Kelurahan' }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Hanya menampilkan warga yang terdaftar di wilayah Anda.</small>
                            @error('id_warga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Status Legalitas Rumah & Tanah -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-shield-check text-primary me-2"></i> Legalitas Kepemilikan Rumah & Tanah
                        </h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Status Kepemilikan Rumah <span class="text-danger">*</span></label>
                                <select name="status_rumah" class="form-select @error('status_rumah') is-invalid @enderror" required>
                                    <option value="">-- Pilih Status Rumah --</option>
                                    <option value="1" {{ old('status_rumah') == '1' ? 'selected' : '' }}>Milik Sendiri</option>
                                    <option value="2" {{ old('status_rumah') == '2' ? 'selected' : '' }}>Sewa / Kontrak</option>
                                    <option value="3" {{ old('status_rumah') == '3' ? 'selected' : '' }}>Menumpang / Milik Keluarga</option>
                                </select>
                                @error('status_rumah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Status Hak Atas Tanah <span class="text-danger">*</span></label>
                                <select name="status_tanah" class="form-select @error('status_tanah') is-invalid @enderror" required>
                                    <option value="">-- Pilih Status Tanah --</option>
                                    <option value="1" {{ old('status_tanah') == '1' ? 'selected' : '' }}>Milik Sendiri (SHM / Hak Milik)</option>
                                    <option value="2" {{ old('status_tanah') == '2' ? 'selected' : '' }}>Bukan Milik Sendiri (Girik / Letter C)</option>
                                    <option value="3" {{ old('status_tanah') == '3' ? 'selected' : '' }}>Tanah Negara / Kas Desa / Lainnya</option>
                                </select>
                                @error('status_tanah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Luas Bangunan Rumah (m²)</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" name="luas_rumah" class="form-control @error('luas_rumah') is-invalid @enderror" 
                                           placeholder="Contoh: 36" value="{{ old('luas_rumah') }}">
                                    <span class="input-group-text">m²</span>
                                </div>
                                @error('luas_rumah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">Luas Tanah / Lahan (m²)</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" name="luas_tanah" class="form-control @error('luas_tanah') is-invalid @enderror" 
                                           placeholder="Contoh: 60" value="{{ old('luas_tanah') }}">
                                    <span class="input-group-text">m²</span>
                                </div>
                                @error('luas_tanah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-check p-3 bg-light rounded mt-2">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="rumah_lain" value="1" id="rumahLain" 
                                           {{ old('rumah_lain') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold text-dark" for="rumahLain">
                                        Memiliki Rumah di Tempat Lain
                                    </label>
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-check p-3 bg-light rounded mt-2">
                                    <input class="form-check-input ms-0 me-2" type="checkbox" name="tanah_lain" value="1" id="tanahLain" 
                                           {{ old('tanah_lain') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold text-dark" for="tanahLain">
                                        Memiliki Tanah di Tempat Lain
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Kawasan, Penghuni & Koordinat -->
            <div class="col-12 col-xl-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-geo-alt-fill text-primary me-2"></i> Kawasan & Penghuni
                        </h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kategori Kawasan</label>
                            <select name="kawasan" class="form-select @error('kawasan') is-invalid @enderror">
                                <option value="">-- Pilih Kawasan --</option>
                                <option value="1" {{ old('kawasan') == '1' ? 'selected' : '' }}>Permukiman Perkotaan</option>
                                <option value="2" {{ old('kawasan') == '2' ? 'selected' : '' }}>Bantaran Sungai / Sempadan</option>
                                <option value="3" {{ old('kawasan') == '3' ? 'selected' : '' }}>Kawasan Kumuh</option>
                                <option value="4" {{ old('kawasan') == '4' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('kawasan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah KK dalam 1 Rumah</label>
                            <input type="number" min="1" name="jml_kk" class="form-control @error('jml_kk') is-invalid @enderror" 
                                   value="{{ old('jml_kk', 1) }}">
                            @error('jml_kk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jumlah Penghuni (Jiwa)</label>
                            <input type="number" min="1" name="jml_penghuni" class="form-control @error('jml_penghuni') is-invalid @enderror" 
                                   value="{{ old('jml_penghuni', 1) }}">
                            @error('jml_penghuni')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-3">
                        <h6 class="fw-bold text-secondary mb-2"><i class="bi bi-pin-map-fill me-1"></i> Titik Koordinat GPS</h6>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Latitude (Garis Lintang)</label>
                            <input type="text" name="latitude" class="form-control @error('latitude') is-invalid @enderror" 
                                   placeholder="Contoh: -7.816667" value="{{ old('latitude') }}">
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Longitude (Garis Bujur)</label>
                            <input type="text" name="longitude" class="form-control @error('longitude') is-invalid @enderror" 
                                   placeholder="Contoh: 112.011667" value="{{ old('longitude') }}">
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-2 shadow-sm">
                            <i class="bi bi-save2-fill me-1"></i> Simpan Data Hunian
                        </button>
                        <a href="{{ route('hunian.index') }}" class="btn btn-light-secondary w-100 py-2">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
