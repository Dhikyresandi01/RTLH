@extends('app')

@section('title', 'Input Survei Fisik Rumah')

@section('content')
<div class="page-heading">
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-12 col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('rumah.index') }}">Survei Fisik Rumah</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Input Hasil Survei</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-primary">Input Hasil Survei Fisik Rumah RTLH</h3>
                <p class="text-muted small mb-0">Pendataan kondisi teknis konstruksi bangunan (atap, lantai, dinding), sanitasi, dan dokumentasi foto</p>
            </div>
            <div class="col-12 col-md-6 text-md-end mt-3 mt-md-0">
                <a href="{{ route('rumah.index') }}" class="btn btn-outline-secondary">
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
                    <strong>Terdapat kesalahan pengisian data survei!</strong>
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

    <form action="{{ route('rumah.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-12 col-xl-8">
                <!-- 1. Calon Penerima -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-person-check-fill text-primary me-2"></i> Calon Penerima Bantuan
                        </h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih Warga / Kepala Keluarga <span class="text-danger">*</span></label>
                            <select name="id_warga" class="form-select @error('id_warga') is-invalid @enderror" required>
                                <option value="">-- Cari & Pilih Warga Calon Penerima --</option>
                                @foreach($wargas as $w)
                                    <option value="{{ $w->id_warga }}" 
                                        {{ (old('id_warga', $selectedWarga?->id_warga) == $w->id_warga) ? 'selected' : '' }}>
                                        {{ $w->nama }} (NIK: {{ $w->nik }}) - {{ $w->wilayah->desa ?? 'Kelurahan' }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih warga yang rumahnya disurvei di lapangan.</small>
                            @error('id_warga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- 2. Komponen Konstruksi Utama (Atap, Dinding, Lantai) -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-bricks text-primary me-2"></i> Kondisi Keselamatan Konstruksi (Atap, Dinding, Lantai)
                        </h5>
                    </div>
                    <div class="card-body pt-4">
                        <!-- Kondisi Atap -->
                        <div class="p-3 bg-light rounded mb-3">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-house-up text-danger me-1"></i> Bagian Atap Bangunan</h6>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Kondisi Kerusakan Atap</label>
                                    <select name="kondisi_atap" class="form-select @error('kondisi_atap') is-invalid @enderror">
                                        <option value="">-- Pilih Kondisi --</option>
                                        <option value="1" {{ old('kondisi_atap') == '1' ? 'selected' : '' }}>1 - Baik (Utuh / Layak)</option>
                                        <option value="2" {{ old('kondisi_atap') == '2' ? 'selected' : '' }}>2 - Rusak Sedang (Sebagian Bocor / Keropos)</option>
                                        <option value="3" {{ old('kondisi_atap') == '3' ? 'selected' : '' }}>3 - Rusak Berat (Bocor Parah / Rawan Runtuh)</option>
                                    </select>
                                    @error('kondisi_atap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Bahan Penutup Atap</label>
                                    <select name="bahan_atap" class="form-select @error('bahan_atap') is-invalid @enderror">
                                        <option value="">-- Pilih Bahan Atap --</option>
                                        <option value="1" {{ old('bahan_atap') == '1' ? 'selected' : '' }}>Genteng Tanah Liat / Keramik</option>
                                        <option value="2" {{ old('bahan_atap') == '2' ? 'selected' : '' }}>Seng / Asbes</option>
                                        <option value="3" {{ old('bahan_atap') == '3' ? 'selected' : '' }}>Ijuk / Rumbia / Anyaman</option>
                                        <option value="4" {{ old('bahan_atap') == '4' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Kondisi Dinding -->
                        <div class="p-3 bg-light rounded mb-3">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-border-all text-warning me-1"></i> Bagian Dinding Bangunan</h6>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Kondisi Kerusakan Dinding</label>
                                    <select name="kondisi_dinding" class="form-select @error('kondisi_dinding') is-invalid @enderror">
                                        <option value="">-- Pilih Kondisi --</option>
                                        <option value="1" {{ old('kondisi_dinding') == '1' ? 'selected' : '' }}>1 - Baik (Kokoh & Tegak)</option>
                                        <option value="2" {{ old('kondisi_dinding') == '2' ? 'selected' : '' }}>2 - Rusak Sedang (Retak Rambut / Sebagian Rapuh)</option>
                                        <option value="3" {{ old('kondisi_dinding') == '3' ? 'selected' : '' }}>3 - Rusak Berat (Retak Menganga / Miring / Bolong)</option>
                                    </select>
                                    @error('kondisi_dinding')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Bahan Dinding Terluas</label>
                                    <select name="bahan_dinding" class="form-select @error('bahan_dinding') is-invalid @enderror">
                                        <option value="">-- Pilih Bahan Dinding --</option>
                                        <option value="1" {{ old('bahan_dinding') == '1' ? 'selected' : '' }}>Tembok Batu Bata / Batako</option>
                                        <option value="2" {{ old('bahan_dinding') == '2' ? 'selected' : '' }}>Kayu / Papan / Triplek</option>
                                        <option value="3" {{ old('bahan_dinding') == '3' ? 'selected' : '' }}>Bambu / Gedek / Sasak</option>
                                        <option value="4" {{ old('bahan_dinding') == '4' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Kondisi Lantai -->
                        <div class="p-3 bg-light rounded mb-3">
                            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-grid-3x3 text-info me-1"></i> Bagian Lantai Bangunan</h6>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Kondisi Kerusakan Lantai</label>
                                    <select name="kondisi_lantai" class="form-select @error('kondisi_lantai') is-invalid @enderror">
                                        <option value="">-- Pilih Kondisi --</option>
                                        <option value="1" {{ old('kondisi_lantai') == '1' ? 'selected' : '' }}>1 - Baik (Kedap Air & Bersih)</option>
                                        <option value="2" {{ old('kondisi_lantai') == '2' ? 'selected' : '' }}>2 - Rusak Sedang (Plester Retak / Sebagian Rusak)</option>
                                        <option value="3" {{ old('kondisi_lantai') == '3' ? 'selected' : '' }}>3 - Rusak Berat (Tanah / Lembab Becek / Pecah Hancur)</option>
                                    </select>
                                    @error('kondisi_lantai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Bahan Lantai Terluas</label>
                                    <select name="bahan_lantai" class="form-select @error('bahan_lantai') is-invalid @enderror">
                                        <option value="">-- Pilih Bahan Lantai --</option>
                                        <option value="1" {{ old('bahan_lantai') == '1' ? 'selected' : '' }}>Keramik / Granit / Ubin</option>
                                        <option value="2" {{ old('bahan_lantai') == '2' ? 'selected' : '' }}>Semen / Plesteran</option>
                                        <option value="3" {{ old('bahan_lantai') == '3' ? 'selected' : '' }}>Tanah Langsung</option>
                                        <option value="4" {{ old('bahan_lantai') == '4' ? 'selected' : '' }}>Papan Kayu / Bambu</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Struktur Tambahan -->
                        <div class="row g-3 pt-2">
                            <div class="col-6 col-md-4">
                                <label class="form-label small fw-semibold">Pondasi</label>
                                <select name="pondasi" class="form-select form-select-sm">
                                    <option value="">-- Pilih --</option>
                                    <option value="1" {{ old('pondasi') == '1' ? 'selected' : '' }}>1 - Baik</option>
                                    <option value="2" {{ old('pondasi') == '2' ? 'selected' : '' }}>2 - Sedang</option>
                                    <option value="3" {{ old('pondasi') == '3' ? 'selected' : '' }}>3 - Rusak / Tanpa Pondasi</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-4">
                                <label class="form-label small fw-semibold">Kolom / Tiang</label>
                                <select name="kolom" class="form-select form-select-sm">
                                    <option value="">-- Pilih --</option>
                                    <option value="1" {{ old('kolom') == '1' ? 'selected' : '' }}>1 - Baik</option>
                                    <option value="2" {{ old('kolom') == '2' ? 'selected' : '' }}>2 - Sedang</option>
                                    <option value="3" {{ old('kolom') == '3' ? 'selected' : '' }}>3 - Rusak / Keropos</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-4">
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
                </div>

                <!-- 3. Dokumentasi Foto Lapangan -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-camera-fill text-primary me-2"></i> Upload Foto Dokumentasi Fisik Lapangan
                        </h5>
                    </div>
                    <div class="card-body pt-4">
                        <p class="text-muted small mb-3">Unggah foto kondisi asli rumah calon penerima (Format: JPG / PNG, maksimal 2MB per foto).</p>
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small">Foto Tampak Depan</label>
                                <input type="file" name="foto_depan" accept="image/*" class="form-control form-control-sm">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small">Foto Ruang Dalam</label>
                                <input type="file" name="foto_dalam" accept="image/*" class="form-control form-control-sm">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small">Foto Kondisi Atap</label>
                                <input type="file" name="foto_atap" accept="image/*" class="form-control form-control-sm">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small">Foto Kondisi Dinding</label>
                                <input type="file" name="foto_dinding" accept="image/*" class="form-control form-control-sm">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small">Foto Kondisi Lantai</label>
                                <input type="file" name="foto_lantai" accept="image/*" class="form-control form-control-sm">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label fw-semibold small">Foto MCK / Sanitasi</label>
                                <input type="file" name="foto_mck" accept="image/*" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Sanitasi, Utilitas & Tombol Simpan -->
            <div class="col-12 col-xl-4">
                <!-- Utilitas & Sanitasi -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold text-dark">
                            <i class="bi bi-droplet-half text-primary me-2"></i> Sanitasi & Utilitas
                        </h5>
                    </div>
                    <div class="card-body pt-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sumber Air Minum / Bersih</label>
                            <select name="sumber_air" class="form-select">
                                <option value="">-- Pilih Sumber Air --</option>
                                <option value="1" {{ old('sumber_air') == '1' ? 'selected' : '' }}>PDAM / Air Perpipaan</option>
                                <option value="2" {{ old('sumber_air') == '2' ? 'selected' : '' }}>Sumur Terlindung / Pompa Bor</option>
                                <option value="3" {{ old('sumber_air') == '3' ? 'selected' : '' }}>Sumur Tidak Terlindung</option>
                                <option value="4" {{ old('sumber_air') == '4' ? 'selected' : '' }}>Mata Air / Air Hujan / Sungai</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Fasilitas MCK (Kamar Mandi & WC)</label>
                            <select name="mck" class="form-select">
                                <option value="">-- Pilih MCK --</option>
                                <option value="1" {{ old('mck') == '1' ? 'selected' : '' }}>Milik Sendiri (Di Dalam Rumah)</option>
                                <option value="2" {{ old('mck') == '2' ? 'selected' : '' }}>Milik Sendiri (Di Luar Rumah)</option>
                                <option value="3" {{ old('mck') == '3' ? 'selected' : '' }}>MCK Bersama / Umum</option>
                                <option value="4" {{ old('mck') == '4' ? 'selected' : '' }}>Tidak Memiliki MCK</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Jenis Kloset / Jamban</label>
                            <select name="jenis_jamban" class="form-select">
                                <option value="">-- Pilih Jenis Jamban --</option>
                                <option value="1" {{ old('jenis_jamban') == '1' ? 'selected' : '' }}>Leher Angsa (Septik)</option>
                                <option value="2" {{ old('jenis_jamban') == '2' ? 'selected' : '' }}>Plengsengan dengan Tutup</option>
                                <option value="3" {{ old('jenis_jamban') == '3' ? 'selected' : '' }}>Cemplung / Cubluk Terbuka</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tempat Pembuangan Akhir Tinja</label>
                            <select name="jenis_tpa" class="form-select">
                                <option value="">-- Pilih TPA Tinja --</option>
                                <option value="1" {{ old('jenis_tpa') == '1' ? 'selected' : '' }}>Tangki Septik (Septic Tank)</option>
                                <option value="2" {{ old('jenis_tpa') == '2' ? 'selected' : '' }}>Kolam / Sawah / Sungai</option>
                                <option value="3" {{ old('jenis_tpa') == '3' ? 'selected' : '' }}>Lubang Tanah</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sumber Penerangan (Listrik)</label>
                            <select name="sumber_penerangan" class="form-select">
                                <option value="">-- Pilih Penerangan --</option>
                                <option value="1" {{ old('sumber_penerangan') == '1' ? 'selected' : '' }}>Listrik PLN Meteran Sendiri</option>
                                <option value="2" {{ old('sumber_penerangan') == '2' ? 'selected' : '' }}>Listrik PLN Numpang / Sambung Tetangga</option>
                                <option value="3" {{ old('sumber_penerangan') == '3' ? 'selected' : '' }}>Bukan Listrik (Lilin / Minyak)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Daya Listrik</label>
                            <select name="daya_listrik" class="form-select">
                                <option value="">-- Pilih Daya --</option>
                                <option value="1" {{ old('daya_listrik') == '1' ? 'selected' : '' }}>450 VA (Bersubsidi)</option>
                                <option value="2" {{ old('daya_listrik') == '2' ? 'selected' : '' }}>900 VA</option>
                                <option value="3" {{ old('daya_listrik') == '3' ? 'selected' : '' }}>1300 VA</option>
                                <option value="0" {{ old('daya_listrik') == '0' ? 'selected' : '' }}>Tanpa Listrik</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Penghawaan & Ventilasi Udara</label>
                            <select name="ventilasi" class="form-select">
                                <option value="">-- Pilih Ventilasi --</option>
                                <option value="1" {{ old('ventilasi') == '1' ? 'selected' : '' }}>Cukup (≥ 10% Luas Lantai)</option>
                                <option value="2" {{ old('ventilasi') == '2' ? 'selected' : '' }}>Kurang (< 10% Luas Lantai)</option>
                                <option value="3" {{ old('ventilasi') == '3' ? 'selected' : '' }}>Tidak Ada Ventilasi</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pencahayaan Alami (Jendela)</label>
                            <select name="jendela" class="form-select">
                                <option value="">-- Pilih Pencahayaan --</option>
                                <option value="1" {{ old('jendela') == '1' ? 'selected' : '' }}>Ada dan Berfungsi Baik</option>
                                <option value="2" {{ old('jendela') == '2' ? 'selected' : '' }}>Ada namun Tertutup / Rusak</option>
                                <option value="3" {{ old('jendela') == '3' ? 'selected' : '' }}>Tidak Memiliki Jendela</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-2 shadow-sm">
                            <i class="bi bi-save2-fill me-1"></i> Simpan Hasil Survei Rumah
                        </button>
                        <a href="{{ route('rumah.index') }}" class="btn btn-light-secondary w-100 py-2">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
