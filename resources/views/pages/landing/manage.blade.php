@extends('app')

@section('title', 'Kelola Landing Page - E-RTLH Kota Kediri')

@section('content')
<div class="page-heading">
    <!-- Header Halaman -->
    <div class="page-title mb-4">
        <div class="row align-items-center">
            <div class="col-12 col-md-7">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item text-muted">Master Data</li>
                        <li class="breadcrumb-item active" aria-current="page">Kelola Landing Page</li>
                    </ol>
                </nav>
                <h3 class="fw-bold mb-0 text-primary">Kelola Konten Landing Page</h3>
                <p class="text-muted small mb-0">Atur tampilan publik halaman utama website E-RTLH Kota Kediri (welcome.blade.php).</p>
            </div>
            <div class="col-12 col-md-5 text-md-end mt-3 mt-md-0">
                <a href="{{ route('welcome') }}" target="_blank" class="btn btn-outline-primary shadow-sm me-2">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Pratinjau Publik
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-octagon-fill me-2"></i>Validasi Gagal:</h6>
            <ul class="mb-0 small ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Card Navigasi Tab Pengaturan -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom p-0">
            <ul class="nav nav-tabs card-header-tabs m-0 px-3 pt-2" id="landingTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-semibold text-primary" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero-pane" type="button" role="tab" aria-controls="hero-pane" aria-selected="true">
                        <i class="bi bi-images me-1"></i> Banner Hero
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-primary" id="profil-tab" data-bs-toggle="tab" data-bs-target="#profil-pane" type="button" role="tab" aria-controls="profil-pane" aria-selected="false">
                        <i class="bi bi-info-circle me-1"></i> Profil &amp; Kontak
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-primary" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats-pane" type="button" role="tab" aria-controls="stats-pane" aria-selected="false">
                        <i class="bi bi-bar-chart-line me-1"></i> Indikator Statistik
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-primary" id="berita-tab" data-bs-toggle="tab" data-bs-target="#berita-pane" type="button" role="tab" aria-controls="berita-pane" aria-selected="false">
                        <i class="bi bi-newspaper me-1"></i> Berita &amp; Publikasi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-primary" id="realisasi-tab" data-bs-toggle="tab" data-bs-target="#realisasi-pane" type="button" role="tab" aria-controls="realisasi-pane" aria-selected="false">
                        <i class="bi bi-hammer me-1"></i> Galeri Realisasi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-semibold text-primary" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faq-pane" type="button" role="tab" aria-controls="faq-pane" aria-selected="false">
                        <i class="bi bi-question-circle me-1"></i> Pertanyaan FAQ
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="landingTabContent">

                <!-- 1. TAB HERO BANNER -->
                <div class="tab-pane fade show active" id="hero-pane" role="tabpanel" aria-labelledby="hero-tab">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-1 text-primary">Pengaturan Banner Hero Slider</h5>
                            <p class="text-muted small mb-0">Halaman beranda memiliki slider dengan 2 slide foto latar belakang dan teks sambutan resmi.</p>
                        </div>
                        <form action="{{ route('landing-page.reset') }}" method="POST" onsubmit="return confirm('Kembalikan bagian Hero Banner ke pengaturan awal?');">
                            @csrf
                            <input type="hidden" name="section" value="hero_slides">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Default
                            </button>
                        </form>
                    </div>

                    <div class="row g-4">
                        @foreach($heroSlides as $idx => $slide)
                            <div class="col-12 col-lg-6">
                                <div class="card border h-100 shadow-none">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3">
                                        <span class="fw-bold text-dark"><i class="bi bi-sliders me-1"></i> Slide {{ $idx + 1 }}</span>
                                        <span class="badge {{ ($slide['accent_color'] ?? '') === 'gold' ? 'bg-warning text-dark' : 'bg-primary' }}">
                                            Tema: {{ ucfirst($slide['accent_color'] ?? 'khaki') }}
                                        </span>
                                    </div>
                                    <div class="card-body p-3">
                                        <!-- Pratinjau Foto -->
                                        <div class="mb-3 position-relative rounded overflow-hidden" style="height: 180px; background-color: #1e3653;">
                                            <img src="{{ $slide['image_url'] ?? '' }}" alt="Preview Slide {{ $idx + 1 }}" class="w-100 h-100 object-fit-cover opacity-75">
                                            <div class="position-absolute bottom-0 start-0 p-2 text-white bg-dark bg-opacity-75 w-100 small">
                                                <div class="fw-bold text-truncate">{{ $slide['title'] ?? '' }}</div>
                                            </div>
                                        </div>

                                        <form action="{{ route('landing-page.update-hero') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" name="slide_index" value="{{ $idx }}">

                                            <div class="row g-2 mb-2">
                                                <div class="col-8">
                                                    <label class="form-label small fw-bold">Teks Kicker (Label Atas):</label>
                                                    <input type="text" name="kicker" class="form-control form-control-sm" value="{{ $slide['kicker'] ?? '' }}" required>
                                                </div>
                                                <div class="col-4">
                                                    <label class="form-label small fw-bold">Warna Aksen:</label>
                                                    <select name="accent_color" class="form-select form-select-sm" required>
                                                        <option value="khaki" {{ ($slide['accent_color'] ?? '') === 'khaki' ? 'selected' : '' }}>Khaki (Kuning Lembut)</option>
                                                        <option value="gold" {{ ($slide['accent_color'] ?? '') === 'gold' ? 'selected' : '' }}>Gold (Emas Cerah)</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="mb-2">
                                                <label class="form-label small fw-bold">Judul Headline:</label>
                                                <input type="text" name="title" class="form-control form-control-sm" value="{{ $slide['title'] ?? '' }}" required>
                                            </div>

                                            <div class="mb-2">
                                                <label class="form-label small fw-bold">Deskripsi / Subjudul:</label>
                                                <textarea name="description" class="form-control form-control-sm" rows="3" required>{{ $slide['description'] ?? '' }}</textarea>
                                            </div>

                                            <div class="row g-2 mb-2">
                                                <div class="col-6">
                                                    <label class="form-label small fw-bold">Tombol Utama (Teks):</label>
                                                    <input type="text" name="primary_cta_label" class="form-control form-control-sm" value="{{ $slide['primary_cta_label'] ?? 'Lihat Data Usulan' }}" required>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label small fw-bold">Tombol Utama (Link/Anchor):</label>
                                                    <input type="text" name="primary_cta_href" class="form-control form-control-sm" value="{{ $slide['primary_cta_href'] ?? '#data-rtlh' }}" required>
                                                </div>
                                            </div>

                                            <div class="row g-2 mb-2">
                                                <div class="col-6">
                                                    <label class="form-label small fw-bold">Tombol Sekunder (Teks):</label>
                                                    <input type="text" name="secondary_cta_label" class="form-control form-control-sm" value="{{ $slide['secondary_cta_label'] ?? '' }}" placeholder="Opsional">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label small fw-bold">Tombol Sekunder (Link):</label>
                                                    <input type="text" name="secondary_cta_href" class="form-control form-control-sm" value="{{ $slide['secondary_cta_href'] ?? '' }}" placeholder="Opsional">
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label small fw-bold">Ganti Foto Latar (Upload File):</label>
                                                <input type="file" name="image_file" class="form-control form-control-sm" accept="image/*">
                                                <div class="form-text small">Atau gunakan URL:</div>
                                                <input type="text" name="image_url" class="form-control form-control-sm mt-1" value="{{ $slide['image_url'] ?? '' }}" placeholder="https://...">
                                            </div>

                                            <div class="text-end">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="bi bi-save me-1"></i> Simpan Slide {{ $idx + 1 }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- 2. TAB PROFIL & KONTAK -->
                <div class="tab-pane fade" id="profil-pane" role="tabpanel" aria-labelledby="profil-tab">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-1 text-primary">Profil &amp; Kontak Dinas Perumahan dan Kawasan Permukiman</h5>
                            <p class="text-muted small mb-0">Atur deskripsi resmi portal E-RTLH, foto dokumentasi, dan kontak layanan pengaduan dinas.</p>
                        </div>
                        <form action="{{ route('landing-page.reset') }}" method="POST" onsubmit="return confirm('Kembalikan Profil & Kontak ke default?');">
                            @csrf
                            <input type="hidden" name="section" value="profil">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Default
                            </button>
                        </form>
                    </div>

                    <form action="{{ route('landing-page.update-profil-kontak') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <!-- Kolom Profil -->
                            <div class="col-12 col-lg-7">
                                <div class="card border shadow-none p-3 h-100">
                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-building me-1"></i> Konten Profil E-RTLH</h6>

                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Label Kicker Profil:</label>
                                        <input type="text" name="profil_kicker" class="form-control form-control-sm" value="{{ $profil['kicker'] ?? 'Tentang E-RTLH Kota Kediri' }}" required>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Judul Utama Profil:</label>
                                        <input type="text" name="profil_title" class="form-control form-control-sm" value="{{ $profil['title'] ?? '' }}" required>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Paragraf 1:</label>
                                        <textarea name="profil_p1" class="form-control form-control-sm" rows="3" required>{{ $profil['p1'] ?? '' }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Paragraf 2:</label>
                                        <textarea name="profil_p2" class="form-control form-control-sm" rows="3" required>{{ $profil['p2'] ?? '' }}</textarea>
                                    </div>

                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <div class="p-2 border rounded bg-light">
                                                <label class="form-label small fw-bold mb-1">Sorotan 1 (Judul):</label>
                                                <input type="text" name="highlight_1_title" class="form-control form-control-sm mb-1" value="{{ $profil['highlight_1_title'] ?? '46 Kelurahan Terintegrasi' }}" required>
                                                <label class="form-label small text-muted mb-0">Keterangan:</label>
                                                <input type="text" name="highlight_1_desc" class="form-control form-control-sm" value="{{ $profil['highlight_1_desc'] ?? 'Mencakup Mojoroto, Kota, Pesantren.' }}" required>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 border rounded bg-light">
                                                <label class="form-label small fw-bold mb-1">Sorotan 2 (Judul):</label>
                                                <input type="text" name="highlight_2_title" class="form-control form-control-sm mb-1" value="{{ $profil['highlight_2_title'] ?? 'Data Terbuka & Terverifikasi' }}" required>
                                                <label class="form-label small text-muted mb-0">Keterangan:</label>
                                                <input type="text" name="highlight_2_desc" class="form-control form-control-sm" value="{{ $profil['highlight_2_desc'] ?? 'Pembaruan berkala tanpa data pribadi.' }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Foto Dokumentasi Profil:</label>
                                        <div class="d-flex align-items-center gap-3 mb-2">
                                            <img src="{{ $profil['image_url'] ?? '' }}" alt="Foto Profil" class="rounded border" style="width: 100px; height: 60px; object-fit: cover;">
                                            <div class="flex-grow-1">
                                                <input type="file" name="profil_image_file" class="form-control form-control-sm" accept="image/*">
                                                <input type="text" name="profil_image_url" class="form-control form-control-sm mt-1" value="{{ $profil['image_url'] ?? '' }}" placeholder="Atau tempel URL foto...">
                                            </div>
                                        </div>
                                        <input type="text" name="profil_image_caption" class="form-control form-control-sm" value="{{ $profil['image_caption'] ?? '' }}" placeholder="Keterangan teks foto di bawah gambar">
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Kontak Layanan -->
                            <div class="col-12 col-lg-5">
                                <div class="card border shadow-none p-3 h-100">
                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-telephone-inbound me-1"></i> Kontak &amp; Alamat Layanan</h6>

                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Nama Instansi:</label>
                                        <input type="text" name="nama_dinas" class="form-control form-control-sm" value="{{ $kontak['nama_dinas'] ?? 'Dinas Perumahan dan Kawasan Permukiman Kota Kediri' }}" required>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Alamat Kantor:</label>
                                        <textarea name="alamat" class="form-control form-control-sm" rows="3" required>{{ $kontak['alamat'] ?? 'Jl. Mayor Bismo No. 4, Kota Kediri, Jawa Timur 64121' }}</textarea>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Nomor Telepon Layanan:</label>
                                        <input type="text" name="telepon" class="form-control form-control-sm" value="{{ $kontak['telepon'] ?? '(0354) 682-145' }}" required>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Email Resmi Dinas:</label>
                                        <input type="email" name="email" class="form-control form-control-sm" value="{{ $kontak['email'] ?? 'dpkp@kedirikota.go.id' }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Jam Layanan Operasional:</label>
                                        <input type="text" name="jam_layanan" class="form-control form-control-sm" value="{{ $kontak['jam_layanan'] ?? 'Senin–Jumat, 08.00–15.30 WIB' }}" required>
                                    </div>

                                    <div class="alert alert-info py-2 px-3 small mb-0">
                                        <i class="bi bi-info-circle me-1"></i> Kontak ini ditampilkan di bagian informasi publik dan footer landing page.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan Profil &amp; Kontak
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 3. TAB INDIKATOR STATISTIK -->
                <div class="tab-pane fade" id="stats-pane" role="tabpanel" aria-labelledby="stats-tab">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-1 text-primary">Pengaturan Indikator Capaian RTLH</h5>
                            <p class="text-muted small mb-0">Pilih apakah angka capaian dihitung otomatis dari database sistem atau ditentukan manual.</p>
                        </div>
                    </div>

                    <form action="{{ route('landing-page.update-statistik') }}" method="POST">
                        @csrf
                        <div class="card border shadow-none p-3 mb-4">
                            <h6 class="fw-bold text-dark mb-3">Mode Perhitungan Statistik</h6>
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="form-check p-3 border rounded bg-light h-100">
                                        <input class="form-check-input ms-0 me-2" type="radio" name="statistik_mode" id="mode_auto" value="auto" {{ $statistikMode === 'auto' ? 'checked' : '' }} onchange="toggleStatMode()">
                                        <label class="form-check-label fw-bold" for="mode_auto">
                                            Otomatis (Live Real-time Database)
                                        </label>
                                        <p class="text-muted small mb-0 mt-1">
                                            Angka dihitung otomatis secara langsung dari database sistem (tabel usulan bantuan, verifikasi dinas, dan realisasi pada 46 kelurahan).
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-check p-3 border rounded bg-light h-100">
                                        <input class="form-check-input ms-0 me-2" type="radio" name="statistik_mode" id="mode_manual" value="manual" {{ $statistikMode === 'manual' ? 'checked' : '' }} onchange="toggleStatMode()">
                                        <label class="form-check-label fw-bold" for="mode_manual">
                                            Manual (Angka Custom yang Ditentukan)
                                        </label>
                                        <p class="text-muted small mb-0 mt-1">
                                            Gunakan angka tetap/target khusus yang Anda inputkan di bawah ini untuk kebutuhan publikasi resmi.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="manual-stats-box" class="card border shadow-none p-3 {{ $statistikMode === 'manual' ? '' : 'd-none' }}">
                            <h6 class="fw-bold text-dark mb-3">Input Angka Manual</h6>
                            <div class="row g-3">
                                <div class="col-6 col-md">
                                    <label class="form-label small fw-bold">Total Usulan:</label>
                                    <input type="number" name="total_usulan" class="form-control" value="{{ $statistikManual['total_usulan'] ?? 2846 }}">
                                </div>
                                <div class="col-6 col-md">
                                    <label class="form-label small fw-bold">Terverifikasi:</label>
                                    <input type="number" name="terverifikasi" class="form-control" value="{{ $statistikManual['terverifikasi'] ?? 2310 }}">
                                </div>
                                <div class="col-6 col-md">
                                    <label class="form-label small fw-bold">Penerima (SK):</label>
                                    <input type="number" name="ditetapkan" class="form-control" value="{{ $statistikManual['ditetapkan'] ?? 1645 }}">
                                </div>
                                <div class="col-6 col-md">
                                    <label class="form-label small fw-bold">Dalam Pengerjaan:</label>
                                    <input type="number" name="proses" class="form-control" value="{{ $statistikManual['proses'] ?? 318 }}">
                                </div>
                                <div class="col-6 col-md">
                                    <label class="form-label small fw-bold">Selesai 100%:</label>
                                    <input type="number" name="selesai" class="form-control" value="{{ $statistikManual['selesai'] ?? 1327 }}">
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-3">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Simpan Pengaturan Statistik
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 4. TAB BERITA & PUBLIKASI -->
                <div class="tab-pane fade" id="berita-pane" role="tabpanel" aria-labelledby="berita-tab">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-1 text-primary">Berita, Pengumuman &amp; Laporan Publik</h5>
                            <p class="text-muted small mb-0">Kelola artikel berita utama, jadwal pengumuman, dan tautan laporan publik yang tampil di landing page.</p>
                        </div>
                        <form action="{{ route('landing-page.reset') }}" method="POST" onsubmit="return confirm('Kembalikan Berita & Publikasi ke default?');">
                            @csrf
                            <input type="hidden" name="section" value="publikasi">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Default
                            </button>
                        </form>
                    </div>

                    @php
                        $berita = $publikasi['berita_utama'] ?? [];
                        $pengumuman = $publikasi['daftar_pengumuman'] ?? [];
                    @endphp

                    <form action="{{ route('landing-page.update-publikasi') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <!-- Berita Utama -->
                            <div class="col-12 col-lg-7">
                                <div class="card border shadow-none p-3 h-100">
                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-card-heading me-1"></i> Berita Utama Unggulan</h6>

                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Judul Berita:</label>
                                        <input type="text" name="berita_judul" class="form-control form-control-sm" value="{{ $berita['judul'] ?? '' }}" required>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Tanggal Berita:</label>
                                        <input type="text" name="berita_tanggal" class="form-control form-control-sm" value="{{ $berita['tanggal'] ?? '18 November 2026' }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold">Ringkasan Berita:</label>
                                        <textarea name="berita_ringkasan" class="form-control form-control-sm" rows="3" required>{{ $berita['ringkasan'] ?? '' }}</textarea>
                                    </div>

                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Foto Berita:</label>
                                        <div class="d-flex align-items-center gap-3 mb-2">
                                            <img src="{{ $berita['image_url'] ?? '' }}" alt="Foto Berita" class="rounded border" style="width: 100px; height: 60px; object-fit: cover;">
                                            <div class="flex-grow-1">
                                                <input type="file" name="berita_image" class="form-control form-control-sm" accept="image/*">
                                                <input type="text" name="berita_image_url" class="form-control form-control-sm mt-1" value="{{ $berita['image_url'] ?? '' }}" placeholder="Atau masukkan URL foto...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pengumuman & Laporan Publik -->
                            <div class="col-12 col-lg-5">
                                <div class="card border shadow-none p-3 h-100">
                                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-megaphone me-1"></i> Daftar Pengumuman &amp; Laporan</h6>

                                    <!-- Pengumuman 1 -->
                                    <div class="p-2 border rounded bg-light mb-3">
                                        <span class="badge bg-secondary mb-1">Item 1: Pengumuman</span>
                                        <label class="form-label small fw-bold d-block mb-1">Judul Pengumuman:</label>
                                        <input type="text" name="pengumuman_1_judul" class="form-control form-control-sm mb-1" value="{{ $pengumuman[0]['judul'] ?? '' }}">
                                        <label class="form-label small text-muted mb-0">Tanggal / Keterangan:</label>
                                        <input type="text" name="pengumuman_1_tgl" class="form-control form-control-sm" value="{{ $pengumuman[0]['tanggal'] ?? '10 November 2026' }}">
                                    </div>

                                    <!-- Pengumuman 2 -->
                                    <div class="p-2 border rounded bg-light">
                                        <span class="badge bg-primary mb-1">Item 2: Laporan Publik</span>
                                        <label class="form-label small fw-bold d-block mb-1">Judul Laporan Publik:</label>
                                        <input type="text" name="pengumuman_2_judul" class="form-control form-control-sm mb-1" value="{{ $pengumuman[1]['judul'] ?? '' }}">
                                        <label class="form-label small text-muted mb-0">Format / Keterangan:</label>
                                        <input type="text" name="pengumuman_2_tgl" class="form-control form-control-sm" value="{{ $pengumuman[1]['tanggal'] ?? 'PDF 2,4 MB' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Simpan Publikasi &amp; Berita
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 5. TAB GALERI REALISASI BEFORE-AFTER -->
                <div class="tab-pane fade" id="realisasi-pane" role="tabpanel" aria-labelledby="realisasi-tab">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-1 text-primary">Dokumentasi Realisasi Lapangan (Before vs After)</h5>
                            <p class="text-muted small mb-0">Kelola foto perbandingan kondisi rumah sebelum dan sesudah perbaikan fisik RTLH.</p>
                        </div>
                        <form action="{{ route('landing-page.reset') }}" method="POST" onsubmit="return confirm('Kembalikan Galeri Realisasi ke default?');">
                            @csrf
                            <input type="hidden" name="section" value="realisasi_gallery">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Default
                            </button>
                        </form>
                    </div>

                    @php
                        $gal = $realisasiGallery[0] ?? [];
                    @endphp

                    <form action="{{ route('landing-page.update-realisasi') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card border shadow-none p-3 mb-4">
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-5">
                                    <label class="form-label small fw-bold">Lokasi Kelurahan &amp; Kecamatan:</label>
                                    <input type="text" name="lokasi" class="form-control form-control-sm" value="{{ $gal['lokasi'] ?? 'Kelurahan Bandar Lor, Kec. Mojoroto' }}" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-bold">Jenis Program Bantuan:</label>
                                    <input type="text" name="jenis_bantuan" class="form-control form-control-sm" value="{{ $gal['jenis_bantuan'] ?? 'RTLH APBD Kota Kediri' }}" required>
                                </div>
                                <div class="col-12 col-md-3">
                                    <label class="form-label small fw-bold">Tahun Pelaksanaan:</label>
                                    <input type="text" name="tahun" class="form-control form-control-sm" value="{{ $gal['tahun'] ?? '2026' }}" required>
                                </div>
                            </div>

                            <div class="row g-4">
                                <!-- Sebelum -->
                                <div class="col-12 col-md-6">
                                    <div class="border rounded p-3 bg-light">
                                        <span class="badge bg-dark mb-2">Sebelum Perbaikan</span>
                                        <div class="mb-2 text-center">
                                            <img src="{{ $gal['before_image'] ?? '' }}" alt="Before" class="rounded border w-100" style="height: 200px; object-fit: cover;">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label small fw-bold">Upload Foto Baru (Sebelum):</label>
                                            <input type="file" name="before_image_file" class="form-control form-control-sm" accept="image/*">
                                            <input type="text" name="before_image_url" class="form-control form-control-sm mt-1" value="{{ $gal['before_image'] ?? '' }}" placeholder="Atau URL foto...">
                                        </div>
                                        <div>
                                            <label class="form-label small fw-bold">Keterangan Kondisi Sebelum:</label>
                                            <textarea name="before_caption" class="form-control form-control-sm" rows="2" required>{{ $gal['before_caption'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Sesudah -->
                                <div class="col-12 col-md-6">
                                    <div class="border rounded p-3 bg-light">
                                        <span class="badge bg-success mb-2">Sesudah Perbaikan</span>
                                        <div class="mb-2 text-center">
                                            <img src="{{ $gal['after_image'] ?? '' }}" alt="After" class="rounded border w-100" style="height: 200px; object-fit: cover;">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label small fw-bold">Upload Foto Baru (Sesudah):</label>
                                            <input type="file" name="after_image_file" class="form-control form-control-sm" accept="image/*">
                                            <input type="text" name="after_image_url" class="form-control form-control-sm mt-1" value="{{ $gal['after_image'] ?? '' }}" placeholder="Atau URL foto...">
                                        </div>
                                        <div>
                                            <label class="form-label small fw-bold">Keterangan Kondisi Sesudah:</label>
                                            <textarea name="after_caption" class="form-control form-control-sm" rows="2" required>{{ $gal['after_caption'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Simpan Dokumentasi Realisasi
                            </button>
                        </div>
                    </form>
                </div>

                <!-- 6. TAB FAQ (PERTANYAAN UMUM) -->
                <div class="tab-pane fade" id="faq-pane" role="tabpanel" aria-labelledby="faq-tab">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold mb-1 text-primary">Pertanyaan Umum (FAQ)</h5>
                            <p class="text-muted small mb-0">Kelola daftar pertanyaan dan jawaban seputar persyaratan dan proses pengusulan bantuan RTLH.</p>
                        </div>
                        <form action="{{ route('landing-page.reset') }}" method="POST" onsubmit="return confirm('Kembalikan daftar FAQ ke default?');">
                            @csrf
                            <input type="hidden" name="section" value="faq_items">
                            <button type="submit" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Default
                            </button>
                        </form>
                    </div>

                    <!-- Tabel Daftar FAQ -->
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th style="width: 35%;">Pertanyaan</th>
                                    <th>Jawaban</th>
                                    <th style="width: 80px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($faqItems as $fIdx => $faq)
                                    <tr>
                                        <td class="text-center">{{ $fIdx + 1 }}</td>
                                        <td class="fw-semibold text-dark">{{ $faq['tanya'] ?? '' }}</td>
                                        <td class="small text-muted">{{ $faq['jawab'] ?? '' }}</td>
                                        <td class="text-center">
                                            <form action="{{ route('landing-page.delete-faq', $faq['id'] ?? $fIdx) }}" method="POST" onsubmit="return confirm('Hapus pertanyaan FAQ ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus FAQ">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">Belum ada pertanyaan FAQ.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Form Tambah FAQ Baru -->
                    <div class="card border shadow-none p-3 bg-light">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-plus-circle me-1"></i> Tambah Pertanyaan FAQ Baru</h6>
                        <form action="{{ route('landing-page.store-faq') }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label small fw-bold">Pertanyaan:</label>
                                <input type="text" name="tanya" class="form-control form-control-sm" placeholder="Contoh: Apakah proses pengusulan RTLH dipungut biaya?" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Jawaban:</label>
                                <textarea name="jawab" class="form-control form-control-sm" rows="3" placeholder="Tuliskan jawaban yang jelas dan informatif bagi masyarakat..." required></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bi bi-plus-lg me-1"></i> Tambahkan ke Daftar FAQ
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function toggleStatMode() {
        const isManual = document.getElementById('mode_manual').checked;
        const box = document.getElementById('manual-stats-box');
        if (isManual) {
            box.classList.remove('d-none');
        } else {
            box.classList.add('d-none');
        }
    }
</script>
@endsection
