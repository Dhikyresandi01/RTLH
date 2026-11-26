<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-RTLH Kota Kediri — Dinas Perumahan dan Kawasan Permukiman</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo/image.png') }}">

    <!-- Google Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">

    <!-- Leaflet.js Interactive Map CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        .custom-district-marker {
            background: transparent;
            border: none;
        }
        .leaflet-popup-content-wrapper {
            border-radius: 8px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.25);
            padding: 4px;
        }
        .leaflet-popup-content {
            margin: 10px 12px;
            line-height: 1.4;
        }
        #kediri-map {
            outline: none;
            z-index: 10;
        }
    </style>

    <!-- Tailwind CSS with Official Design System Tokens -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'primary': '#054735',
                        'on-primary': '#FFFFFF',
                        'primary-hover': '#003829',
                        'primary-container': '#F4FFF7',
                        'on-primary-container': '#054735',
                        'secondary': '#002B84',
                        'on-secondary': '#FFFFFF',
                        'secondary-container': '#EFF0FF',
                        'on-secondary-container': '#002B84',
                        'dark-blue': '#1E3653',
                        'on-dark-blue': '#FFFFFF',
                        'khaki': '#EBB866',
                        'on-khaki': '#281900',
                        'gold': '#FFC000',
                        'on-gold': '#261A00',
                        'surface': '#FFFFFF',
                        'surface-subtle': '#F7F9F8',
                        'surface-container': '#EFF3F1',
                        'on-surface': '#1E3653',
                        'on-surface-muted': '#49607F',
                        'outline': '#BFC9C3',
                        'outline-variant': '#DBE5DE',
                        'success': '#14503E',
                        'warning': '#7E570B',
                        'warning-container': '#FFEEDA',
                    },
                    borderRadius: {
                        'xs': '2px',
                        'sm': '4px',
                        'DEFAULT': '4px',
                        'md': '6px',
                        'lg': '8px',
                        'xl': '12px',
                        'full': '9999px',
                    },
                    spacing: {
                        'gutter-xs': '4px',
                        'gutter-sm': '8px',
                        'gutter-md': '16px',
                        'gutter-lg': '24px',
                        'gutter-xl': '32px',
                        'section-sm': '48px',
                        'section-md': '64px',
                        'section-lg': '96px',
                    },
                    fontFamily: {
                        'sans': ['"Inter"', 'sans-serif'],
                        'headline': ['"Inter"', 'sans-serif'],
                        'body': ['"Inter"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .material-symbols-outlined {
            display: inline-block;
            width: 1em;
            height: 1em;
            line-height: 1 !important;
            vertical-align: middle;
            user-select: none;
        }
    </style>
</head>
<body class="bg-surface text-on-surface font-sans antialiased min-h-screen flex flex-col">

    <!-- 1. HEADER: Corporate Navigation -->
    <header class="sticky top-0 z-40 bg-surface/95 backdrop-blur border-b border-outline-variant transition-all">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            <!-- Brand Identity -->
            <a href="#beranda" class="flex items-center gap-3 shrink-0 group">
                <div class="w-10 h-10 rounded border border-khaki/60 p-1 bg-white shadow-sm flex items-center justify-center shrink-0">
                    <img src="{{ asset('assets/images/logo/image.png') }}" alt="Logo Kota Kediri" class="w-full h-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-base text-on-surface tracking-tight leading-tight group-hover:text-primary transition-colors">E-RTLH KOTA KEDIRI</span>
                    <span class="text-xs text-on-surface-muted font-medium">Dinas Perumahan &amp; Kawasan Permukiman</span>
                </div>
            </a>

            <!-- Corporate Navigation Links -->
            <nav aria-label="Navigasi Utama" class="hidden lg:flex items-center gap-6 xl:gap-8">
                <a href="#beranda" class="text-sm font-semibold text-primary border-b-2 border-primary py-2">Beranda</a>
                <a href="#profil" class="text-sm font-medium text-on-surface hover:text-primary py-2 transition-colors">Profil</a>
                <a href="#data-rtlh" class="text-sm font-medium text-on-surface hover:text-primary py-2 transition-colors">Data RTLH</a>
                <a href="#program" class="text-sm font-medium text-on-surface hover:text-primary py-2 transition-colors">Program</a>
                <a href="#realisasi" class="text-sm font-medium text-on-surface hover:text-primary py-2 transition-colors">Realisasi</a>
                <a href="#publikasi" class="text-sm font-medium text-on-surface hover:text-primary py-2 transition-colors">Berita</a>
                <a href="#faq" class="text-sm font-medium text-on-surface hover:text-primary py-2 transition-colors">FAQ</a>
            </nav>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 shrink-0">
                @auth
                    @if(Auth::user()->isSuperadmin())
                        <a href="{{ route('landing-page.manage') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 rounded text-xs font-semibold bg-amber-50 text-amber-900 border border-amber-300 hover:bg-amber-100 transition-colors" title="Buka Panel CMS Landing Page">
                            <span class="material-symbols-outlined text-base">settings</span>
                            <span>Kelola CMS</span>
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded bg-primary text-white hover:bg-primary-hover font-semibold text-sm transition-all shadow-sm">
                        <span class="material-symbols-outlined text-lg">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded bg-surface text-primary border border-primary hover:bg-primary-container font-semibold text-sm transition-all shadow-sm">
                        <span class="material-symbols-outlined text-lg">login</span>
                        <span>Masuk Sistem</span>
                    </a>
                @endauth

                <!-- Mobile Menu Button -->
                <button type="button" onclick="toggleMobileNav()" class="lg:hidden p-2 text-on-surface hover:text-primary focus:outline-none" aria-label="Toggle Menu">
                    <span class="material-symbols-outlined text-2xl">menu</span>
                </button>
            </div>
        </div>

        <!-- Mobile Nav Menu -->
        <div id="mobile-nav" class="hidden lg:hidden border-t border-outline-variant bg-surface px-4 py-4 space-y-2">
            <a href="#beranda" onclick="toggleMobileNav()" class="block py-2 text-sm font-semibold text-primary">Beranda</a>
            <a href="#profil" onclick="toggleMobileNav()" class="block py-2 text-sm font-medium text-on-surface hover:text-primary">Profil E-RTLH</a>
            <a href="#data-rtlh" onclick="toggleMobileNav()" class="block py-2 text-sm font-medium text-on-surface hover:text-primary">Data RTLH per Kelurahan</a>
            <a href="#program" onclick="toggleMobileNav()" class="block py-2 text-sm font-medium text-on-surface hover:text-primary">Program Bantuan</a>
            <a href="#realisasi" onclick="toggleMobileNav()" class="block py-2 text-sm font-medium text-on-surface hover:text-primary">Realisasi Lapangan</a>
            <a href="#publikasi" onclick="toggleMobileNav()" class="block py-2 text-sm font-medium text-on-surface hover:text-primary">Publikasi &amp; Berita</a>
            <a href="#faq" onclick="toggleMobileNav()" class="block py-2 text-sm font-medium text-on-surface hover:text-primary">Pertanyaan Umum (FAQ)</a>
        </div>
    </header>

    <main class="flex-1">
        <!-- 2. HERO: Photographic Slider -->
        <section id="beranda" class="relative w-full min-h-[560px] lg:h-[580px] bg-dark-blue overflow-hidden flex items-center">
            @foreach($heroSlides as $idx => $slide)
                <div id="hero-slide-{{ $idx }}" class="hero-slide absolute inset-0 transition-opacity duration-700 {{ $idx === 0 ? 'opacity-100 z-10' : 'opacity-0 pointer-events-none z-0' }}">
                    <!-- Background Image (Absolute to prevent pushing content downward) -->
                    <img src="{{ $slide['image_url'] ?? '' }}" alt="{{ $slide['image_alt'] ?? 'Hunian Kota Kediri' }}" class="absolute inset-0 w-full h-full object-cover">
                    <!-- Gradient Scrim Overlay -->
                    <div class="absolute inset-0 bg-dark-blue/70 bg-gradient-to-r from-dark-blue/95 via-dark-blue/80 to-transparent"></div>
                    <!-- Slide Content Text & Buttons -->
                    <div class="relative z-10 max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-center py-16">
                        <div class="max-w-2xl flex flex-col gap-4">
                            <div class="flex items-center gap-2">
                                <span class="w-8 h-0.5 {{ ($slide['accent_color'] ?? '') === 'gold' ? 'bg-gold' : 'bg-khaki' }}"></span>
                                <span class="text-xs font-bold uppercase tracking-wider {{ ($slide['accent_color'] ?? '') === 'gold' ? 'text-gold' : 'text-khaki' }}">
                                    {{ $slide['kicker'] ?? 'Portal Resmi DPKP Kota Kediri' }}
                                </span>
                            </div>
                            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white tracking-tight leading-tight">
                                {{ $slide['title'] ?? 'Mewujudkan Hunian Layak bagi Masyarakat Kota Kediri' }}
                            </h1>
                            <p class="text-base sm:text-lg text-white/90 leading-relaxed max-w-xl">
                                {{ $slide['description'] ?? 'Sistem informasi terpadu pendataan, verifikasi teknis, dan transparansi realisasi penanganan Rumah Tidak Layak Huni di Kota Kediri.' }}
                            </p>
                            <div class="flex flex-wrap items-center gap-3 pt-2">
                                <a href="{{ $slide['primary_cta_href'] ?? '#data-rtlh' }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded bg-primary text-white hover:bg-primary-hover font-semibold text-sm transition-all shadow-md">
                                    <span>{{ $slide['primary_cta_label'] ?? 'Lihat Data Usulan' }}</span>
                                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                                </a>
                                @if(!empty($slide['secondary_cta_label']))
                                    <a href="{{ $slide['secondary_cta_href'] ?? '#program' }}" class="inline-flex items-center justify-center px-6 py-3 rounded bg-transparent text-white border border-white/60 hover:bg-white/10 font-semibold text-sm transition-all">
                                        <span>{{ $slide['secondary_cta_label'] }}</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Slider Controls -->
            <div class="absolute bottom-6 inset-x-0 z-20">
                <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        @foreach($heroSlides as $idx => $slide)
                            <button type="button" onclick="setHeroSlide({{ $idx }})" id="hero-dot-{{ $idx }}" aria-label="Slide {{ $idx + 1 }}" class="w-10 h-1.5 rounded-full transition-all {{ $idx === 0 ? 'bg-gold' : 'bg-white/40 hover:bg-white/70' }}"></button>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="prevHeroSlide()" aria-label="Slide sebelumnya" class="w-9 h-9 rounded border border-white/40 bg-dark-blue/60 text-white hover:bg-dark-blue flex items-center justify-center transition-colors">
                            <span class="material-symbols-outlined text-lg">chevron_left</span>
                        </button>
                        <button type="button" onclick="nextHeroSlide()" aria-label="Slide berikutnya" class="w-9 h-9 rounded border border-white/40 bg-dark-blue/60 text-white hover:bg-dark-blue flex items-center justify-center transition-colors">
                            <span class="material-symbols-outlined text-lg">chevron_right</span>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. PROFIL: Editorial Layout -->
        <section id="profil" class="py-16 lg:py-20 bg-surface border-b border-outline-variant">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <div class="lg:col-span-6">
                    <figure class="border border-outline-variant rounded overflow-hidden bg-surface-subtle shadow-sm">
                        <img src="{{ $profil['image_url'] ?? '' }}" alt="Penerima manfaat program E-RTLH Kota Kediri" class="w-full h-[360px] object-cover">
                        <figcaption class="px-4 py-3 border-t border-outline-variant text-xs text-on-surface-muted bg-surface">
                            {{ $profil['image_caption'] ?? 'Dokumentasi penerima manfaat perbaikan rumah swadaya — Kota Kediri.' }}
                        </figcaption>
                    </figure>
                </div>
                <div class="lg:col-span-6 flex flex-col gap-5">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-primary">{{ $profil['kicker'] ?? 'Tentang E-RTLH Kota Kediri' }}</span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-on-surface mt-1 leading-snug">
                            {{ $profil['title'] ?? 'Tata Kelola Penanganan Rumah Tidak Layak Huni yang Akuntabel' }}
                        </h2>
                    </div>
                    <p class="text-sm sm:text-base text-on-surface-muted leading-relaxed">
                        {{ $profil['p1'] ?? '' }}
                    </p>
                    <p class="text-sm sm:text-base text-on-surface-muted leading-relaxed">
                        {{ $profil['p2'] ?? '' }}
                    </p>
                    <div class="pt-4 border-t border-outline-variant grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-3 bg-surface-subtle rounded border border-outline-variant/60">
                            <p class="font-bold text-sm text-on-surface">{{ $profil['highlight_1_title'] ?? '46 Kelurahan Terintegrasi' }}</p>
                            <p class="text-xs text-on-surface-muted mt-0.5">{{ $profil['highlight_1_desc'] ?? 'Mencakup Kecamatan Mojoroto, Kota, dan Pesantren.' }}</p>
                        </div>
                        <div class="p-3 bg-surface-subtle rounded border border-outline-variant/60">
                            <p class="font-bold text-sm text-on-surface">{{ $profil['highlight_2_title'] ?? 'Data Terbuka & Terverifikasi' }}</p>
                            <p class="text-xs text-on-surface-muted mt-0.5">{{ $profil['highlight_2_desc'] ?? 'Pembaruan berkala tanpa menampilkan data pribadi sensitif.' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. STATISTIK: Large Numerical Indicators -->
        <section aria-label="Statistik Penanganan RTLH" class="py-12 lg:py-16 bg-surface-subtle border-b border-outline-variant">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-2 mb-8">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-primary">Ringkasan Capaian {{ date('Y') }}</span>
                        <h2 class="text-2xl font-bold text-on-surface mt-0.5">Indikator Penanganan RTLH Kota Kediri</h2>
                    </div>
                    <span class="text-xs text-on-surface-muted">
                        Data Publikasi Resmi — Diperbarui {{ date('d F Y') }}
                        @if($statistikMode === 'auto')
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-green-100 text-green-800 ml-1">Live Database</span>
                        @endif
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 lg:gap-0 lg:divide-x divide-outline-variant border-t-2 border-primary pt-6">
                    <div class="lg:pr-6 flex flex-col">
                        <span class="text-3xl lg:text-4xl font-extrabold text-on-surface tabular-nums">{{ number_format($stats['total_usulan'] ?? 0, 0, ',', '.') }}</span>
                        <span class="text-sm font-semibold text-on-surface mt-1">Total Usulan</span>
                        <span class="text-xs text-on-surface-muted mt-0.5">Usulan masuk kelurahan</span>
                    </div>
                    <div class="lg:px-6 flex flex-col">
                        <span class="text-3xl lg:text-4xl font-extrabold text-on-surface tabular-nums">{{ number_format($stats['terverifikasi'] ?? 0, 0, ',', '.') }}</span>
                        <span class="text-sm font-semibold text-on-surface mt-1">Terverifikasi</span>
                        <span class="text-xs text-on-surface-muted mt-0.5">Lolos survei teknis dinas</span>
                    </div>
                    <div class="lg:px-6 flex flex-col">
                        <span class="text-3xl lg:text-4xl font-extrabold text-secondary tabular-nums">{{ number_format($stats['ditetapkan'] ?? 0, 0, ',', '.') }}</span>
                        <span class="text-sm font-semibold text-on-surface mt-1">Penerima Bantuan</span>
                        <span class="text-xs text-on-surface-muted mt-0.5">Ditetapkan melalui SK Wali Kota</span>
                    </div>
                    <div class="lg:px-6 flex flex-col">
                        <span class="text-3xl lg:text-4xl font-extrabold text-amber-600 tabular-nums">{{ number_format($stats['proses'] ?? 0, 0, ',', '.') }}</span>
                        <span class="text-sm font-semibold text-on-surface mt-1">Dalam Pengerjaan</span>
                        <span class="text-xs text-on-surface-muted mt-0.5">Tahap konstruksi berjalan</span>
                    </div>
                    <div class="lg:pl-6 flex flex-col">
                        <span class="text-3xl lg:text-4xl font-extrabold text-primary tabular-nums">{{ number_format($stats['selesai'] ?? 0, 0, ',', '.') }}</span>
                        <span class="text-sm font-semibold text-on-surface mt-1">Selesai</span>
                        <span class="text-xs text-on-surface-muted mt-0.5">Hunian layak diserahterimakan</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. DATA USULAN: Interactive Table with Search & Filter -->
        <section id="data-rtlh" class="py-16 lg:py-20 bg-surface border-b border-outline-variant">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-6">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                    <div class="max-w-2xl">
                        <span class="text-xs font-bold uppercase tracking-wider text-primary">Transparansi Data Wilayah</span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-on-surface mt-1">Rekapitulasi Data Usulan per Kelurahan</h2>
                        <p class="text-sm text-on-surface-muted mt-1">
                            Telusuri rekapitulasi status penanganan RTLH di 46 kelurahan se-Kota Kediri. Data disajikan secara agregat untuk melindungi privasi warga.
                        </p>
                    </div>
                    <div class="shrink-0 flex items-center gap-2">
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded bg-primary text-white hover:bg-primary-hover font-semibold text-xs sm:text-sm transition-all shadow-sm">
                            <span>Akses Internal Petugas</span>
                            <span class="material-symbols-outlined text-base">lock</span>
                        </a>
                    </div>
                </div>

                <!-- Table Component -->
                <div class="border border-outline-variant rounded bg-surface shadow-sm overflow-hidden">
                    <!-- Table Controls -->
                    <div class="p-4 border-b border-outline-variant bg-surface-subtle flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="relative w-full sm:w-80">
                            <span class="material-symbols-outlined text-lg text-on-surface-muted absolute left-3 top-1/2 -translate-y-1/2">search</span>
                            <input id="table-search" type="search" oninput="filterTable()" placeholder="Cari nama kelurahan..." class="w-full pl-9 pr-3 py-2 text-sm border border-outline rounded bg-surface text-on-surface placeholder:text-on-surface-muted focus:border-primary focus:ring-1 focus:ring-primary">
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="kecamatan-filter" class="text-xs sm:text-sm font-semibold text-on-surface-muted whitespace-nowrap">Filter Kecamatan:</label>
                            <select id="kecamatan-filter" onchange="filterTable()" class="text-sm border border-outline rounded bg-surface text-on-surface py-2 pl-3 pr-8 focus:border-primary focus:ring-1 focus:ring-primary">
                                <option value="ALL">Semua Kecamatan (46 Kelurahan)</option>
                                <option value="Mojoroto">Kecamatan Mojoroto</option>
                                <option value="Kota">Kecamatan Kota</option>
                                <option value="Pesantren">Kecamatan Pesantren</option>
                            </select>
                        </div>
                    </div>

                    <!-- Table Responsive Wrapper -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead class="bg-surface-subtle border-b border-outline-variant text-xs uppercase tracking-wider font-semibold text-on-surface">
                                <tr>
                                    <th scope="col" class="px-4 py-3.5">Kecamatan</th>
                                    <th scope="col" class="px-4 py-3.5">Kelurahan</th>
                                    <th scope="col" class="px-4 py-3.5 text-right">Jumlah Usulan</th>
                                    <th scope="col" class="px-4 py-3.5 text-right">Terverifikasi</th>
                                    <th scope="col" class="px-4 py-3.5 text-right">Ditetapkan</th>
                                    <th scope="col" class="px-4 py-3.5 text-right">Dalam Pengerjaan</th>
                                    <th scope="col" class="px-4 py-3.5 text-right">Selesai</th>
                                </tr>
                            </thead>
                            <tbody id="rtlh-table-body" class="divide-y divide-outline-variant">
                                <!-- Populated by JS -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-4 py-3 border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-3 bg-surface-subtle text-xs text-on-surface-muted">
                        <span id="table-count-label">Memuat data...</span>
                        <div id="table-pagination" class="flex items-center gap-1">
                            <!-- Populated by JS -->
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. PETA SPASIAL: 3 Kecamatan Kota Kediri -->
        <section class="py-16 lg:py-20 bg-surface-subtle border-b border-outline-variant">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                <div class="lg:col-span-7 flex flex-col gap-4">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-primary">Peta Sebaran Spasial</span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-on-surface mt-1">Sebaran Penanganan per Kecamatan</h2>
                    </div>
                    <div class="relative flex-1 min-h-[420px] lg:min-h-[450px] border border-outline-variant rounded-lg overflow-hidden bg-surface shadow-sm">
                        <!-- Leaflet Interactive Map Container -->
                        <div id="kediri-map" class="w-full h-full min-h-[420px] lg:min-h-[450px]"></div>

                        <!-- Badge Overlay Info -->
                        <div class="absolute bottom-3 left-3 z-[400] px-3 py-1.5 bg-surface/90 border border-outline-variant rounded-md text-[11px] text-on-surface font-medium backdrop-blur shadow-sm flex items-center gap-2 pointer-events-none">
                            <span class="inline-block w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                            <span>Peta Interaktif Kota Kediri &bull; 3 Kecamatan &bull; 46 Kelurahan</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 flex flex-col justify-between bg-surface border border-outline-variant rounded-lg p-6 shadow-sm">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-on-surface-muted">Statistik Agregat Wilayah</span>
                        <h3 id="map-district-title" class="text-xl sm:text-2xl font-bold text-on-surface mt-1">Kecamatan Mojoroto</h3>
                        <p class="text-xs text-on-surface-muted mt-1">Klik penanda kecamatan pada peta interaktif atau pilih tombol kecamatan di bawah.</p>

                        <div class="mt-6 divide-y divide-outline-variant border-y border-outline-variant">
                            <div class="py-3 flex items-center justify-between">
                                <span class="text-sm text-on-surface-muted">Jumlah Kelurahan</span>
                                <span id="map-stat-kel" class="font-bold text-base text-on-surface tabular-nums">14 Kelurahan</span>
                            </div>
                            <div class="py-3 flex items-center justify-between">
                                <span class="text-sm text-on-surface-muted">Total Usulan</span>
                                <span id="map-stat-usulan" class="font-bold text-base text-on-surface tabular-nums">1.084 Unit</span>
                            </div>
                            <div class="py-3 flex items-center justify-between">
                                <span class="text-sm text-on-surface-muted">Dalam Pengerjaan</span>
                                <span id="map-stat-proses" class="font-bold text-base text-amber-600 tabular-nums">124 Unit</span>
                            </div>
                            <div class="py-3 flex items-center justify-between">
                                <span class="text-sm text-on-surface-muted">Selesai Terealisasi</span>
                                <span id="map-stat-selesai" class="font-bold text-base text-primary tabular-nums">512 Unit</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 flex items-center gap-2">
                        <button type="button" onclick="selectDistrict('Mojoroto')" id="btn-dist-mojoroto" class="district-btn flex-1 py-2 px-3 rounded border font-semibold text-xs transition-all bg-primary text-white border-primary shadow-sm">Mojoroto</button>
                        <button type="button" onclick="selectDistrict('Kota')" id="btn-dist-kota" class="district-btn flex-1 py-2 px-3 rounded border font-semibold text-xs transition-all bg-surface-subtle text-on-surface border-outline-variant hover:border-primary">Kota</button>
                        <button type="button" onclick="selectDistrict('Pesantren')" id="btn-dist-pesantren" class="district-btn flex-1 py-2 px-3 rounded border font-semibold text-xs transition-all bg-surface-subtle text-on-surface border-outline-variant hover:border-primary">Pesantren</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- 7. PROGRAM BANTUAN -->
        <section id="program" class="py-16 lg:py-20 bg-surface border-b border-outline-variant">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl mb-10">
                    <span class="text-xs font-bold uppercase tracking-wider text-primary">Skema Pendanaan &amp; Bantuan</span>
                    <h2 class="text-2xl sm:text-3xl font-bold text-on-surface mt-1">Program Bantuan Perumahan Kota Kediri</h2>
                    <p class="text-sm text-on-surface-muted mt-1">
                        Tiga skema utama peningkatan kualitas rumah tidak layak huni yang disinergikan antara pemerintah pusat dan Pemerintah Kota Kediri.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                    <!-- Program 1: BSPS -->
                    <article class="flex flex-col border border-outline-variant rounded overflow-hidden bg-surface shadow-sm hover:shadow-md transition-shadow">
                        <img src="https://lh3.googleusercontent.com/aida/ALJJyrA9XfhqrcYvkxOtL-VrsH1ew_2pU0pB-JU6GPBn2I1_tkskc3Xmgy7HVcGPPU9qppee6wkbr-pp7OSeDr5vJb-g4Zae7TjiVYFl0zhFq1oYUgAtx2NteJVQQ7I4zsI2yBWP5MaSPM8JkEbBqWkNXCcEaFqvq131v6CqYPemMZ9uqF4KB0DoaWO99DtH76U0gHP4yX9COyHl9GEvRKrcWpjpUGd35dckD8-Fmc7w6LH8nPpZtUtWoQ" alt="Program BSPS" referrerpolicy="no-referrer" class="w-full h-48 object-cover">
                        <div class="p-5 flex flex-col gap-2 flex-1 justify-between">
                            <div>
                                <span class="text-xs font-bold text-secondary uppercase tracking-wider">APBN • Kementerian PKP</span>
                                <h3 class="text-lg font-bold text-on-surface mt-1">BSPS</h3>
                                <p class="text-xs sm:text-sm text-on-surface-muted mt-2 leading-relaxed">
                                    Bantuan Stimulan Perumahan Swadaya bagi masyarakat berpenghasilan rendah untuk mendorong keswadayaan peningkatan struktur atap, lantai, dan dinding.
                                </p>
                            </div>
                            <button type="button" onclick="openProgramModal('bsps')" class="inline-flex items-center gap-1 text-xs sm:text-sm font-semibold text-primary hover:underline pt-3">
                                <span>Pelajari Kriteria BSPS</span>
                                <span class="material-symbols-outlined text-base">arrow_forward</span>
                            </button>
                        </div>
                    </article>

                    <!-- Program 2: APBD Kota -->
                    <article class="flex flex-col border border-outline-variant rounded overflow-hidden bg-surface shadow-sm hover:shadow-md transition-shadow">
                        <img src="https://lh3.googleusercontent.com/aida/ALJJyrDvV5w_i9Ornem7HcBVWldMMn2-8kIsipV_jc8WrU_APTLHqMChoqLM9PfWa4s0ijnRtzKgVBmhnMdPDI-foAZBrHZtUx2e6SmuPx_KmNdsRhjl_V2EsmLvomVFZzXRA5g1S8YLnCHwSIlL6LCUV49R8cAIkwa_vaLnPgJWMyEM460mOYZcbLHwYkUpXAYd9lI5k22VxkgTrmOU4MsUSmSEiqTu-U-mS6k-2MZa7BiWawPQrhFoPw" alt="Program RTLH APBD" referrerpolicy="no-referrer" class="w-full h-48 object-cover">
                        <div class="p-5 flex flex-col gap-2 flex-1 justify-between">
                            <div>
                                <span class="text-xs font-bold text-primary uppercase tracking-wider">APBD • Pemerintah Kota Kediri</span>
                                <h3 class="text-lg font-bold text-on-surface mt-1">RTLH APBD Kota</h3>
                                <p class="text-xs sm:text-sm text-on-surface-muted mt-2 leading-relaxed">
                                    Program perbaikan rumah tidak layak huni yang bersumber dari APBD Kota Kediri untuk mempercepat pengentasan kawasan permukiman kumuh perkotaan.
                                </p>
                            </div>
                            <button type="button" onclick="openProgramModal('apbd')" class="inline-flex items-center gap-1 text-xs sm:text-sm font-semibold text-primary hover:underline pt-3">
                                <span>Pelajari Kriteria APBD Kota</span>
                                <span class="material-symbols-outlined text-base">arrow_forward</span>
                            </button>
                        </div>
                    </article>

                    <!-- Program 3: Bantuan Khusus -->
                    <article class="flex flex-col border border-outline-variant rounded overflow-hidden bg-surface shadow-sm hover:shadow-md transition-shadow">
                        <img src="https://lh3.googleusercontent.com/aida/ALJJyrD6PgT4WbLbLBnyMzyhM5MmF8ZVSLMJ0N3uSunEmqULlh_Fpn1r37WbVVh-xWxQYsarDa5uCwfarZ6C6vDJrnAhfeqw1ffCWJgwetMImc-Hwj2yajcHn8lRyFv8c1n6JvXSClv_Ps25nWD0j8PO8gEDgCljTbJpavJWPxfJABRNaASNr15G7sr0zzfJJExPggFb0P1iOOB5LWz31oXu12rw7-5rHECzX_kWMfZk6sdZicAJtbwSbolLf0uG" alt="Bantuan Khusus Stimulan" referrerpolicy="no-referrer" class="w-full h-48 object-cover">
                        <div class="p-5 flex flex-col gap-2 flex-1 justify-between">
                            <div>
                                <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Penanganan Khusus &amp; Prioritas</span>
                                <h3 class="text-lg font-bold text-on-surface mt-1">Bantuan Khusus Stimulan</h3>
                                <p class="text-xs sm:text-sm text-on-surface-muted mt-2 leading-relaxed">
                                    Dukungan stimulan material dan pendampingan teknis bagi hunian terdampak kondisi darurat, lansia tunggal, serta keluarga rentan di Kota Kediri.
                                </p>
                            </div>
                            <button type="button" onclick="openProgramModal('khusus')" class="inline-flex items-center gap-1 text-xs sm:text-sm font-semibold text-primary hover:underline pt-3">
                                <span>Pelajari Bantuan Khusus</span>
                                <span class="material-symbols-outlined text-base">arrow_forward</span>
                            </button>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- 8. ALUR MEKANISME: 6-Step Horizontal Timeline -->
        <section class="py-14 lg:py-16 bg-surface-subtle border-b border-outline-variant">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <span class="text-xs font-bold uppercase tracking-wider text-primary">Alur Mekanisme</span>
                    <h2 class="text-2xl font-bold text-on-surface mt-0.5">Tahapan Penanganan RTLH hingga Selesai</h2>
                </div>

                <ol class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6 border-t border-outline-variant pt-6">
                    <li class="flex flex-col gap-1.5">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-primary text-white text-xs font-bold flex items-center justify-center">1</span>
                            <span class="font-bold text-sm text-on-surface">Pendataan</span>
                        </div>
                        <p class="text-xs text-on-surface-muted leading-relaxed">Pengusulan awal warga melalui kelurahan &amp; basis data daerah.</p>
                    </li>
                    <li class="flex flex-col gap-1.5">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-primary text-white text-xs font-bold flex items-center justify-center">2</span>
                            <span class="font-bold text-sm text-on-surface">Verifikasi</span>
                        </div>
                        <p class="text-xs text-on-surface-muted leading-relaxed">Survei kondisi fisik bangunan &amp; keabsahan sertifikat lahan.</p>
                    </li>
                    <li class="flex flex-col gap-1.5">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-primary text-white text-xs font-bold flex items-center justify-center">3</span>
                            <span class="font-bold text-sm text-on-surface">Seleksi SPK</span>
                        </div>
                        <p class="text-xs text-on-surface-muted leading-relaxed">Perankingan prioritas tingkat kerusakan dan kelayakan.</p>
                    </li>
                    <li class="flex flex-col gap-1.5">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-secondary text-white text-xs font-bold flex items-center justify-center">4</span>
                            <span class="font-bold text-sm text-on-surface">Penetapan</span>
                        </div>
                        <p class="text-xs text-on-surface-muted leading-relaxed">Penerbitan Keputusan Resmi Wali Kota Kediri.</p>
                    </li>
                    <li class="flex flex-col gap-1.5">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-amber-600 text-white text-xs font-bold flex items-center justify-center">5</span>
                            <span class="font-bold text-sm text-on-surface">Realisasi</span>
                        </div>
                        <p class="text-xs text-on-surface-muted leading-relaxed">Penyaluran bahan material &amp; pelaksanaan konstruksi renovasi.</p>
                    </li>
                    <li class="flex flex-col gap-1.5">
                        <div class="flex items-center gap-2">
                            <span class="w-6 h-6 rounded-full bg-green-700 text-white text-xs font-bold flex items-center justify-center">6</span>
                            <span class="font-bold text-sm text-green-800">Selesai</span>
                        </div>
                        <p class="text-xs text-on-surface-muted leading-relaxed">Pemeriksaan hasil 100% dan serah terima hunian layak.</p>
                    </li>
                </ol>
            </div>
        </section>

        <!-- 9. REALISASI: Before-and-After Housing Photography -->
        <section id="realisasi" class="py-16 lg:py-20 bg-surface border-b border-outline-variant">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                    <div class="max-w-2xl">
                        <span class="text-xs font-bold uppercase tracking-wider text-primary">Dokumentasi Lapangan</span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-on-surface mt-1">Realisasi Peningkatan Kualitas Hunian</h2>
                        <p class="text-sm text-on-surface-muted mt-1">
                            Perbandingan kondisi fisik bangunan sebelum dan sesudah pelaksanaan program tanpa menampilkan identitas sensitif penerima.
                        </p>
                    </div>
                </div>

                @php
                    $gal = $realisasiGallery[0] ?? [];
                @endphp
                <div class="border border-outline-variant rounded bg-surface p-6 shadow-sm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <figure class="flex flex-col gap-2">
                            <div class="relative border border-outline-variant rounded overflow-hidden shadow-sm group">
                                <img src="{{ $gal['before_image'] ?? '' }}" alt="Kondisi Rumah Sebelum Bantuan" class="w-full h-72 sm:h-80 object-cover">
                                <span class="absolute top-3 left-3 px-3 py-1 rounded bg-dark-blue/90 text-white font-semibold text-xs backdrop-blur">Sebelum Perbaikan</span>
                            </div>
                            <figcaption class="text-xs text-on-surface-muted">
                                {{ $gal['before_caption'] ?? 'Dinding bata belum terplester, rangka atap lapuk, dan ventilasi terbatas.' }}
                            </figcaption>
                        </figure>

                        <figure class="flex flex-col gap-2">
                            <div class="relative border border-outline-variant rounded overflow-hidden shadow-sm group">
                                <img src="{{ $gal['after_image'] ?? '' }}" alt="Kondisi Rumah Sesudah Bantuan" class="w-full h-72 sm:h-80 object-cover">
                                <span class="absolute top-3 left-3 px-3 py-1 rounded bg-primary/95 text-white font-semibold text-xs backdrop-blur">Sesudah Perbaikan</span>
                            </div>
                            <figcaption class="text-xs text-on-surface-muted">
                                {{ $gal['after_caption'] ?? 'Struktur dinding kokoh dan terplester rapi, atap genteng baru, serta ventilasi sehat.' }}
                            </figcaption>
                        </figure>
                    </div>

                    <div class="mt-6 pt-4 border-t border-outline-variant grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <span class="text-xs uppercase text-on-surface-muted font-bold">Lokasi Wilayah</span>
                            <p class="font-bold text-sm text-on-surface mt-0.5">{{ $gal['lokasi'] ?? 'Kelurahan Bandar Lor, Kec. Mojoroto' }}</p>
                        </div>
                        <div>
                            <span class="text-xs uppercase text-on-surface-muted font-bold">Jenis Bantuan</span>
                            <p class="font-bold text-sm text-on-surface mt-0.5">{{ $gal['jenis_bantuan'] ?? 'RTLH APBD Kota Kediri' }}</p>
                        </div>
                        <div>
                            <span class="text-xs uppercase text-on-surface-muted font-bold">Tahun Realisasi</span>
                            <p class="font-bold text-sm text-on-surface mt-0.5 tabular-nums">{{ $gal['tahun'] ?? '2026' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 10. PUBLIKASI & FAQ -->
        <section id="publikasi" class="py-16 lg:py-20 bg-surface-subtle">
            <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Left: Berita Terkini -->
                <div class="lg:col-span-7 flex flex-col gap-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-primary">Kabar &amp; Informasi</span>
                        <h2 class="text-2xl sm:text-3xl font-bold text-on-surface mt-1">Berita Seputar Penanganan RTLH</h2>
                        <p class="text-xs sm:text-sm text-on-surface-muted mt-1">
                            Informasi dan dokumentasi kegiatan survei teknis, penyaluran bantuan, serta progres fisik peningkatan kualitas hunian Kota Kediri.
                        </p>
                    </div>

                    @if(isset($beritaList) && count($beritaList) > 0)
                        @php
                            $utama = $beritaList[0];
                            $lainnya = array_slice($beritaList, 1);
                        @endphp

                        <!-- Berita Utama / Unggulan -->
                        <article class="grid grid-cols-1 sm:grid-cols-12 gap-5 p-4 rounded-xl bg-surface border border-outline-variant shadow-sm hover:border-primary/50 transition-all">
                            <div class="sm:col-span-5 overflow-hidden rounded-lg">
                                <img src="{{ $utama['image_url'] ?? asset('assets/images/samples/building.jpg') }}" 
                                     alt="{{ $utama['judul'] ?? 'Berita DPKP' }}" 
                                     class="w-full h-48 sm:h-full object-cover rounded-lg hover:scale-105 transition-all duration-300"
                                     onerror="this.onerror=null; this.src='{{ asset('assets/images/samples/building.jpg') }}';">
                            </div>
                            <div class="sm:col-span-7 flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-primary-container text-primary">
                                            {{ $utama['kategori'] ?? 'Berita' }}
                                        </span>
                                        <span class="text-xs text-on-surface-muted flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">calendar_today</span>
                                            {{ $utama['tanggal'] ?? '' }}
                                        </span>
                                    </div>
                                    <h3 class="font-bold text-base sm:text-lg text-on-surface hover:text-primary transition-colors cursor-pointer leading-snug" onclick="openBeritaModal(0)">
                                        {{ $utama['judul'] ?? '' }}
                                    </h3>
                                    <p class="text-xs sm:text-sm text-on-surface-muted mt-2 line-clamp-3 leading-relaxed">
                                        {{ $utama['ringkasan'] ?? '' }}
                                    </p>
                                </div>
                                <div class="mt-4 pt-3 border-t border-outline-variant flex items-center justify-between">
                                    <button type="button" onclick="openBeritaModal(0)" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary hover:text-primary-hover">
                                        <span>Baca Selengkapnya</span>
                                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                    </button>
                                </div>
                            </div>
                        </article>

                        <!-- Daftar Berita Lainnya (Jika ada lebih dari 1 berita) -->
                        @if(count($lainnya) > 0)
                            <div class="flex flex-col gap-3">
                                <span class="text-xs font-bold text-on-surface uppercase tracking-wider">Berita Lainnya</span>
                                <div class="divide-y divide-outline-variant border-y border-outline-variant">
                                    @foreach($lainnya as $subIdx => $b)
                                        @php $actualIdx = $subIdx + 1; @endphp
                                        <div class="py-3.5 flex items-center gap-4 group cursor-pointer hover:bg-surface-container/50 px-2 rounded-lg transition-colors" onclick="openBeritaModal({{ $actualIdx }})">
                                            <div class="w-20 h-16 sm:w-24 sm:h-16 shrink-0 rounded overflow-hidden bg-surface-container border border-outline-variant">
                                                <img src="{{ $b['image_url'] ?? asset('assets/images/samples/building.jpg') }}" 
                                                     alt="{{ $b['judul'] ?? 'Berita' }}" 
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-all"
                                                     onerror="this.onerror=null; this.src='{{ asset('assets/images/samples/building.jpg') }}';">
                                            </div>
                                            <div class="flex-grow min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="text-[10px] font-bold text-secondary uppercase bg-secondary-container px-2 py-0.5 rounded">
                                                        {{ $b['kategori'] ?? 'Kegiatan' }}
                                                    </span>
                                                    <span class="text-[11px] text-on-surface-muted">
                                                        {{ $b['tanggal'] ?? '' }}
                                                    </span>
                                                </div>
                                                <h4 class="font-bold text-sm text-on-surface group-hover:text-primary transition-colors line-clamp-1 leading-snug">
                                                    {{ $b['judul'] ?? '' }}
                                                </h4>
                                                <p class="text-xs text-on-surface-muted line-clamp-1 mt-0.5">
                                                    {{ $b['ringkasan'] ?? '' }}
                                                </p>
                                            </div>
                                            <span class="material-symbols-outlined text-primary text-lg shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                                arrow_forward
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="p-8 text-center bg-surface border border-outline-variant rounded-xl text-on-surface-muted">
                            <span class="material-symbols-outlined text-4xl text-on-surface-muted mb-2">newspaper</span>
                            <p class="text-sm">Belum ada artikel berita yang dipublikasikan saat ini.</p>
                        </div>
                    @endif
                </div>

                <!-- Right: FAQ & Kontak -->
                <div id="faq" class="lg:col-span-5 flex flex-col gap-6">
                    <div class="bg-surface border border-outline-variant rounded p-6 shadow-sm">
                        <h3 class="font-bold text-lg text-on-surface mb-4">Pertanyaan Umum (FAQ)</h3>
                        <div class="divide-y divide-outline-variant">
                            @foreach($faqItems as $fIdx => $faq)
                                <div class="py-3">
                                    <button type="button" onclick="toggleFaq({{ $fIdx }})" class="w-full flex items-center justify-between text-left gap-3 focus:outline-none">
                                        <span class="font-semibold text-sm text-on-surface">{{ $faq['tanya'] ?? '' }}</span>
                                        <span id="faq-icon-{{ $fIdx }}" class="material-symbols-outlined text-on-surface-muted text-lg transition-transform {{ $fIdx === 0 ? 'rotate-180' : '' }}">expand_more</span>
                                    </button>
                                    <p id="faq-ans-{{ $fIdx }}" class="text-xs sm:text-sm text-on-surface-muted mt-2 leading-relaxed {{ $fIdx === 0 ? '' : 'hidden' }}">
                                        {{ $faq['jawab'] ?? '' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Kontak Box -->
                    <div class="bg-surface border border-outline-variant rounded p-6 shadow-sm flex flex-col gap-3">
                        <h3 class="font-bold text-base text-on-surface">Layanan Informasi &amp; Pengaduan</h3>
                        <p class="text-xs text-on-surface-muted leading-relaxed">
                            <strong>{{ $kontak['nama_dinas'] ?? 'Dinas Perumahan dan Kawasan Permukiman Kota Kediri' }}</strong><br>
                            {{ $kontak['alamat'] ?? 'Jl. Mayor Bismo No. 4, Kota Kediri, Jawa Timur 64121' }}
                        </p>
                        <div class="pt-2 border-t border-outline-variant flex flex-col gap-1 text-xs text-on-surface">
                            <span><strong>Telepon:</strong> {{ $kontak['telepon'] ?? '(0354) 682-145' }}</span>
                            <span><strong>Email:</strong> {{ $kontak['email'] ?? 'dpkp@kedirikota.go.id' }}</span>
                            <span><strong>Jam Layanan:</strong> {{ $kontak['jam_layanan'] ?? 'Senin–Jumat, 08.00–15.30 WIB' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- 11. FOOTER -->
    <footer class="bg-dark-blue text-white">
        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-8 border-b border-white/10">
            <div class="lg:col-span-4 flex flex-col gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded p-1 bg-white flex items-center justify-center shrink-0">
                        <img src="{{ asset('assets/images/logo/image.png') }}" alt="Logo Kota Kediri" class="w-full h-full object-contain">
                    </div>
                    <span class="font-bold text-base tracking-tight text-white">E-RTLH KOTA KEDIRI</span>
                </div>
                <p class="text-xs text-white/80 leading-relaxed max-w-sm">
                    Portal resmi transparansi pendataan, seleksi kelayakan teknis, dan realisasi penanganan Rumah Tidak Layak Huni oleh Dinas Perumahan dan Kawasan Permukiman Kota Kediri.
                </p>
            </div>

            <div class="lg:col-span-2 flex flex-col gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-khaki">Navigasi</span>
                <a href="#beranda" class="text-xs text-white/80 hover:text-white transition-colors">Beranda</a>
                <a href="#profil" class="text-xs text-white/80 hover:text-white transition-colors">Profil E-RTLH</a>
                <a href="#data-rtlh" class="text-xs text-white/80 hover:text-white transition-colors">Data RTLH</a>
                <a href="#realisasi" class="text-xs text-white/80 hover:text-white transition-colors">Realisasi</a>
                <a href="#faq" class="text-xs text-white/80 hover:text-white transition-colors">FAQ</a>
            </div>

            <div class="lg:col-span-3 flex flex-col gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-khaki">Skema Bantuan</span>
                <a href="#program" class="text-xs text-white/80 hover:text-white transition-colors">BSPS (APBN Kementerian PKP)</a>
                <a href="#program" class="text-xs text-white/80 hover:text-white transition-colors">RTLH APBD Kota Kediri</a>
                <a href="#program" class="text-xs text-white/80 hover:text-white transition-colors">Bantuan Khusus Stimulan</a>
                <a href="#publikasi" class="text-xs text-white/80 hover:text-white transition-colors">Laporan Triwulan</a>
            </div>

            <div class="lg:col-span-3 flex flex-col gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-khaki">Kantor Dinas</span>
                <span class="text-xs text-white/80">{{ $kontak['alamat'] ?? 'Jl. Mayor Bismo No. 4, Kota Kediri' }}</span>
                <span class="text-xs text-white/80">Telp: {{ $kontak['telepon'] ?? '(0354) 682-145' }}</span>
                <span class="text-xs text-white/80">Email: {{ $kontak['email'] ?? 'dpkp@kedirikota.go.id' }}</span>
            </div>
        </div>

        <div class="max-w-[1280px] mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-white/70">
            <span>&copy; {{ date('Y') }} Dinas Perumahan dan Kawasan Permukiman Kota Kediri. Seluruh hak cipta dilindungi.</span>
            <span>Sistem Informasi E-RTLH Kota Kediri</span>
        </div>
    </footer>

    <!-- MODAL: Program Bantuan Detail -->
    <div id="program-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-dark-blue/70 backdrop-blur-sm" role="dialog" aria-modal="true">
        <div class="bg-surface rounded max-w-lg w-full p-6 shadow-xl border border-outline-variant relative max-h-[90vh] overflow-y-auto">
            <button type="button" onclick="closeProgramModal()" class="absolute top-4 right-4 text-on-surface-muted hover:text-on-surface">
                <span class="material-symbols-outlined text-2xl">close</span>
            </button>
            <div id="program-modal-content">
                <!-- Filled dynamically by JS -->
            </div>
        </div>
    </div>

    <!-- MODAL: Pembaca Artikel Berita Lengkap -->
    <div id="berita-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-dark-blue/70 backdrop-blur-sm" role="dialog" aria-modal="true">
        <div class="bg-surface rounded-xl max-w-2xl w-full p-6 shadow-2xl border border-outline-variant relative max-h-[90vh] overflow-y-auto">
            <button type="button" onclick="closeBeritaModal()" class="absolute top-4 right-4 text-on-surface-muted hover:text-on-surface p-1 rounded-full hover:bg-surface-container">
                <span class="material-symbols-outlined text-2xl">close</span>
            </button>
            <div id="berita-modal-content">
                <!-- Filled dynamically by JS -->
            </div>
        </div>
    </div>

    <!-- Client-Side JavaScript -->
    <script>
        // Mobile Navigation Toggle
        function toggleMobileNav() {
            const el = document.getElementById('mobile-nav');
            el.classList.toggle('hidden');
        }

        // Hero Slider
        let currentSlide = 0;
        const totalSlides = {{ count($heroSlides) }};
        let heroTimer = null;

        function setHeroSlide(idx, restartTimer = true) {
            currentSlide = (idx + totalSlides) % totalSlides;

            document.querySelectorAll('.hero-slide').forEach((el, i) => {
                if (i === currentSlide) {
                    el.classList.remove('opacity-0', 'pointer-events-none', 'z-0');
                    el.classList.add('opacity-100', 'z-10');
                } else {
                    el.classList.add('opacity-0', 'pointer-events-none', 'z-0');
                    el.classList.remove('opacity-100', 'z-10');
                }
            });

            for (let i = 0; i < totalSlides; i++) {
                const dot = document.getElementById('hero-dot-' + i);
                if (dot) {
                    dot.classList.toggle('bg-gold', i === currentSlide);
                    dot.classList.toggle('bg-white/40', i !== currentSlide);
                }
            }

            if (restartTimer) {
                resetHeroTimer();
            }
        }

        function nextHeroSlide() {
            setHeroSlide(currentSlide + 1);
        }

        function prevHeroSlide() {
            setHeroSlide(currentSlide - 1);
        }

        function resetHeroTimer() {
            if (heroTimer) clearInterval(heroTimer);
            heroTimer = setInterval(nextHeroSlide, 6500);
        }

        // Jalankan timer awal
        resetHeroTimer();

        // Pause pergantian otomatis saat kursor mouse berada di area banner
        const heroSection = document.getElementById('beranda');
        if (heroSection) {
            heroSection.addEventListener('mouseenter', () => {
                if (heroTimer) clearInterval(heroTimer);
            });
            heroSection.addEventListener('mouseleave', () => {
                resetHeroTimer();
            });
        }

        // Data Kelurahan dari Laravel
        const rtlhRows = @json($kelurahanData);
        let currentPage = 1;
        const perPage = 8;

        function renderTable() {
            const q = (document.getElementById('table-search').value || '').toLowerCase().trim();
            const kec = document.getElementById('kecamatan-filter').value;

            const filtered = rtlhRows.filter(r => {
                const matchKec = (kec === 'ALL' || r.kec === kec);
                const matchQuery = (r.kel.toLowerCase().includes(q) || r.kec.toLowerCase().includes(q));
                return matchKec && matchQuery;
            });

            const totalPages = Math.ceil(filtered.length / perPage) || 1;
            if (currentPage > totalPages) currentPage = totalPages;

            const start = (currentPage - 1) * perPage;
            const pageItems = filtered.slice(start, start + perPage);

            const tbody = document.getElementById('rtlh-table-body');
            if (pageItems.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-on-surface-muted text-sm">
                            Tidak ditemukan data kelurahan yang sesuai kriteria pencarian.
                        </td>
                    </tr>
                `;
            } else {
                tbody.innerHTML = pageItems.map(r => `
                    <tr class="hover:bg-surface-subtle transition-colors">
                        <td class="px-4 py-3 font-medium text-on-surface">${r.kec}</td>
                        <td class="px-4 py-3 text-on-surface-muted">${r.kel}</td>
                        <td class="px-4 py-3 text-right font-semibold text-on-surface tabular-nums">${r.usulan.toLocaleString('id-ID')}</td>
                        <td class="px-4 py-3 text-right tabular-nums text-on-surface-muted">${r.verif.toLocaleString('id-ID')}</td>
                        <td class="px-4 py-3 text-right tabular-nums text-secondary font-medium">${r.tetap.toLocaleString('id-ID')}</td>
                        <td class="px-4 py-3 text-right tabular-nums text-amber-700">${r.proses.toLocaleString('id-ID')}</td>
                        <td class="px-4 py-3 text-right tabular-nums font-bold text-primary">${r.selesai.toLocaleString('id-ID')}</td>
                    </tr>
                `).join('');
            }

            const end = Math.min(start + perPage, filtered.length);
            document.getElementById('table-count-label').textContent = filtered.length
                ? `Menampilkan ${start + 1}–${end} dari ${filtered.length} kelurahan terdaftar`
                : 'Tidak ada data kelurahan';

            // Pagination Buttons
            let paginationHtml = '';
            for (let i = 1; i <= totalPages; i++) {
                if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                    const activeClass = i === currentPage ? 'bg-primary text-white border-primary' : 'bg-surface text-on-surface border-outline-variant hover:bg-surface-subtle';
                    paginationHtml += `<button type="button" onclick="goToPage(${i})" class="px-2.5 py-1 text-xs font-semibold rounded border ${activeClass}">${i}</button>`;
                } else if (i === currentPage - 2 || i === currentPage + 2) {
                    paginationHtml += `<span class="px-1 text-xs text-on-surface-muted">...</span>`;
                }
            }
            document.getElementById('table-pagination').innerHTML = paginationHtml;
        }

        function filterTable() {
            currentPage = 1;
            renderTable();
        }

        function goToPage(p) {
            currentPage = p;
            renderTable();
        }

        // Initialize table
        renderTable();

        // Peta Spasial District Selector & Leaflet Initialization
        const districtData = @json($districtStats);

        const districtCoords = {
            'Mojoroto': { lat: -7.8115, lng: 111.9890, color: '#054735', label: 'Mojoroto' },
            'Kota': { lat: -7.8185, lng: 112.0175, color: '#002B84', label: 'Kota' },
            'Pesantren': { lat: -7.8380, lng: 112.0460, color: '#1E3653', label: 'Pesantren' }
        };

        let mapInstance = null;
        const districtMarkers = {};

        function initKediriMap() {
            const mapEl = document.getElementById('kediri-map');
            if (!mapEl || typeof L === 'undefined') return;

            // Inisialisasi peta di koordinat pusat Kota Kediri
            mapInstance = L.map('kediri-map', {
                center: [-7.8228, 112.0119],
                zoom: 13,
                minZoom: 11,
                maxZoom: 18,
                scrollWheelZoom: false
            });

            // Tile Layer OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a>'
            }).addTo(mapInstance);

            // Tambahkan Custom Badge Pin Marker untuk masing-masing kecamatan
            Object.keys(districtCoords).forEach(name => {
                const info = districtCoords[name];
                const stats = districtData[name] || {};

                const customIcon = L.divIcon({
                    className: 'custom-district-marker',
                    html: `
                        <div class="cursor-pointer flex items-center justify-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-white text-xs font-bold shadow-md border-2 border-white transition-all transform hover:scale-105" style="background-color: ${info.color};">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                Kec. ${name}
                            </span>
                        </div>
                    `,
                    iconSize: [120, 36],
                    iconAnchor: [60, 18]
                });

                const marker = L.marker([info.lat, info.lng], { icon: customIcon }).addTo(mapInstance);

                const popupHtml = `
                    <div style="min-width: 170px; font-family: Inter, sans-serif;">
                        <div style="font-weight: 700; font-size: 13px; color: ${info.color}; margin-bottom: 6px; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px;">
                            Kecamatan ${name}
                        </div>
                        <div style="font-size: 11.5px; color: #4b5563; line-height: 1.5;">
                            <div>Kelurahan: <strong style="color: #111827;">${stats.kel || '-'}</strong></div>
                            <div>Total Usulan: <strong style="color: #111827;">${stats.usulan || '-'}</strong></div>
                            <div>Pengerjaan: <strong style="color: #d97706;">${stats.proses || '-'}</strong></div>
                            <div>Selesai: <strong style="color: #054735;">${stats.selesai || '-'}</strong></div>
                        </div>
                    </div>
                `;
                marker.bindPopup(popupHtml);

                marker.on('click', () => {
                    selectDistrict(name, false);
                });

                districtMarkers[name] = marker;
            });

            // Resize observer / window resize handler agar tile tidak berantakan
            setTimeout(() => {
                mapInstance.invalidateSize();
            }, 300);
        }

        function selectDistrict(name, shouldFly = true) {
            const d = districtData[name];
            if (!d) return;

            document.getElementById('map-district-title').textContent = 'Kecamatan ' + name;
            document.getElementById('map-stat-kel').textContent = d.kel;
            document.getElementById('map-stat-usulan').textContent = d.usulan;
            document.getElementById('map-stat-proses').textContent = d.proses;
            document.getElementById('map-stat-selesai').textContent = d.selesai;

            // Highlight tombol kecamatan
            ['mojoroto', 'kota', 'pesantren'].forEach(key => {
                const btn = document.getElementById('btn-dist-' + key);
                if (btn) {
                    if (key === name.toLowerCase()) {
                        btn.className = 'district-btn flex-1 py-2 px-3 rounded border font-semibold text-xs transition-all bg-primary text-white border-primary shadow-sm';
                    } else {
                        btn.className = 'district-btn flex-1 py-2 px-3 rounded border font-semibold text-xs transition-all bg-surface-subtle text-on-surface border-outline-variant hover:border-primary';
                    }
                }
            });

            // Fly map to district coordinates & open popup
            const info = districtCoords[name];
            if (mapInstance && info) {
                if (shouldFly) {
                    mapInstance.flyTo([info.lat, info.lng], 14, { duration: 0.8 });
                }
                const marker = districtMarkers[name];
                if (marker) {
                    marker.openPopup();
                }
            }
        }

        // Jalankan inisialisasi peta setelah DOM selesai dimuat
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initKediriMap);
        } else {
            initKediriMap();
        }

        // FAQ Toggle
        function toggleFaq(idx) {
            const ans = document.getElementById('faq-ans-' + idx);
            const icon = document.getElementById('faq-icon-' + idx);
            if (ans) {
                ans.classList.toggle('hidden');
                if (icon) icon.classList.toggle('rotate-180');
            }
        }

        // Program Modals Data
        const programDetails = {
            bsps: {
                title: 'Bantuan Stimulan Perumahan Swadaya (BSPS)',
                source: 'APBN • Kementerian Perumahan dan Kawasan Permukiman',
                desc: 'Bantuan pemerintah bagi masyarakat berpenghasilan rendah untuk mendorong dan meningkatkan keswadayaan dalam peningkatan kualitas rumah menjadi layak huni.',
                criteria: [
                    'Warga Negara Indonesia yang sudah berkeluarga',
                    'Memiliki atau menguasai tanah dengan bukti kepemilikan/penguasaan yang sah',
                    'Belum memiliki rumah, atau memiliki dan menempati satu-satunya RTLH',
                    'Belum pernah memperoleh bantuan perumahan dari pemerintah',
                    'Penghasilan maksimal setara Upah Minimum Kota (UMK) Kediri'
                ]
            },
            apbd: {
                title: 'RTLH APBD Pemerintah Kota Kediri',
                source: 'APBD • Dinas Perumahan dan Kawasan Permukiman Kota Kediri',
                desc: 'Alokasi bantuan stimulus material bersumber dari anggaran daerah Kota Kediri untuk mempercepat penanganan kawasan kumuh perkotaan dan kemiskinan ekstrem.',
                criteria: [
                    'Penduduk ber-KTP dan KK Kota Kediri yang tercatat dalam data kemiskinan daerah',
                    'Tanah tidak dalam sengketa hukum atau berada pada bantaran sungai / rel KA',
                    'Kondisi rumah mengalami kerusakan struktur (atap, lantai, dinding) di atas 45%',
                    'Bersedia berswadaya tenaga kerja bersama kelompok masyarakat',
                    'Lolos seleksi Sistem Pendukung Keputusan (SPK) Dinas'
                ]
            },
            khusus: {
                title: 'Bantuan Khusus Stimulan Prioritas',
                source: 'APBD & Kolaborasi CSR Kota Kediri',
                desc: 'Skema penanganan cepat bagi keluarga dengan lansia tunggal, penyandang disabilitas berat, atau hunian yang terdampak musibah bencana darurat.',
                criteria: [
                    'Keluarga rentan dengan kepala keluarga lansia atau disabilitas',
                    'Kondisi hunian darurat yang membahayakan keselamatan penghuni',
                    'Rekomendasi tertulis dari Kepala Kelurahan setempat',
                    'Didampingi fasilitator teknis lapangan selama proses rehabilitasi'
                ]
            }
        };

        function openProgramModal(key) {
            const data = programDetails[key];
            if (!data) return;

            const content = `
                <div class="flex flex-col gap-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-primary">${data.source}</span>
                    <h3 class="text-xl font-bold text-on-surface">${data.title}</h3>
                    <p class="text-sm text-on-surface-muted leading-relaxed">${data.desc}</p>
                    <div class="mt-3 pt-3 border-t border-outline-variant">
                        <h4 class="font-bold text-xs uppercase tracking-wider text-on-surface mb-2">Kriteria &amp; Persyaratan:</h4>
                        <ul class="space-y-1.5 text-xs text-on-surface-muted">
                            ${data.criteria.map(c => `<li class="flex items-start gap-2"><span class="material-symbols-outlined text-primary text-base shrink-0">check_circle</span><span>${c}</span></li>`).join('')}
                        </ul>
                    </div>
                    <div class="mt-4 pt-3 border-t border-outline-variant flex justify-end">
                        <button type="button" onclick="closeProgramModal()" class="px-4 py-2 rounded bg-primary text-white text-xs font-semibold hover:bg-primary-hover">Tutup</button>
                    </div>
                </div>
            `;

            document.getElementById('program-modal-content').innerHTML = content;
            document.getElementById('program-modal').classList.remove('hidden');
        }

        function closeProgramModal() {
            document.getElementById('program-modal').classList.add('hidden');
        }

        // Modal Pembaca Berita
        const allBerita = @json($beritaList);

        function openBeritaModal(idx) {
            const b = allBerita[idx];
            if (!b) return;

            const fallbackImg = "{{ asset('assets/images/samples/building.jpg') }}";
            const imgUrl = b.image_url || fallbackImg;

            const content = `
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary-container text-primary">${b.kategori || 'Berita'}</span>
                        <span class="text-xs text-on-surface-muted flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">calendar_today</span>
                            ${b.tanggal || ''}
                        </span>
                    </div>

                    <h3 class="text-xl sm:text-2xl font-bold text-on-surface leading-snug">${b.judul || ''}</h3>

                    <div class="rounded-lg overflow-hidden border border-outline-variant bg-surface-container max-h-80">
                        <img src="${imgUrl}" alt="${b.judul || 'Foto Berita'}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='${fallbackImg}';">
                    </div>

                    <div class="text-xs sm:text-sm text-on-surface leading-relaxed space-y-3 whitespace-pre-line">
                        ${b.isi || b.ringkasan || ''}
                    </div>

                    <div class="mt-6 pt-4 border-t border-outline-variant flex justify-end">
                        <button type="button" onclick="closeBeritaModal()" class="px-5 py-2 rounded bg-primary text-white text-xs font-bold hover:bg-primary-hover transition-colors">Tutup</button>
                    </div>
                </div>
            `;

            document.getElementById('berita-modal-content').innerHTML = content;
            document.getElementById('berita-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeBeritaModal() {
            document.getElementById('berita-modal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    </script>
</body>
</html>
