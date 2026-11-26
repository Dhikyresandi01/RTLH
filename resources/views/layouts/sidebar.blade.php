<style>
    :root {
        --stitch-sidebar-bg: #112244;
        --stitch-sidebar-surface: #192E59;
        --stitch-sidebar-active: #1E40AF;
        --stitch-sidebar-text: #E2E8F0;
        --stitch-sidebar-muted: #94A3B8;
        --stitch-sidebar-border: rgba(255, 255, 255, 0.08);
    }

    #sidebar .sidebar-wrapper {
        background-color: var(--stitch-sidebar-bg) !important;
        border-right: 1px solid var(--stitch-sidebar-surface) !important;
        box-shadow: 2px 0 16px rgba(0, 15, 35, 0.25);
    }

    #sidebar .sidebar-wrapper .sidebar-header {
        border-bottom: 1px solid var(--stitch-sidebar-border);
        padding: 1.25rem 1.25rem 0.85rem;
    }

    #sidebar .sidebar-wrapper .menu .sidebar-title {
        font-family: 'Instrument Sans', sans-serif;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--stitch-sidebar-muted);
        margin: 1.1rem 0 0.35rem;
        padding: 0.5rem 1.1rem 0;
        pointer-events: none;
        user-select: none;
    }

    #sidebar .sidebar-wrapper .menu .sidebar-title:first-child {
        margin-top: 0.35rem;
        padding-top: 0;
    }

    #sidebar .sidebar-wrapper .menu .sidebar-item {
        margin-bottom: 3px;
        list-style: none;
    }

    #sidebar .sidebar-wrapper .menu .sidebar-item .sidebar-link {
        font-family: 'Instrument Sans', sans-serif;
        font-size: 13.5px;
        font-weight: 500;
        color: var(--stitch-sidebar-text);
        border-radius: 8px;
        padding: 8px 12px;
        margin: 0 10px;
        display: flex;
        align-items: center;
        transition: all 0.15s ease-out;
        text-decoration: none;
        border-left: none !important;
    }

    #sidebar .sidebar-wrapper .menu .sidebar-item .sidebar-link .material-symbols-outlined {
        color: var(--stitch-sidebar-muted);
        font-size: 20px;
        line-height: 1;
        transition: color 0.15s ease;
    }

    #sidebar .sidebar-wrapper .menu .sidebar-item .sidebar-link:hover {
        background-color: var(--stitch-sidebar-surface);
        color: #FFFFFF;
    }

    #sidebar .sidebar-wrapper .menu .sidebar-item .sidebar-link:hover .material-symbols-outlined {
        color: #FFFFFF;
    }

    #sidebar .sidebar-wrapper .menu .sidebar-item.active .sidebar-link {
        background-color: var(--stitch-sidebar-active) !important;
        color: #FFFFFF !important;
        box-shadow: 0 2px 8px rgba(30, 64, 175, 0.35);
    }

    #sidebar .sidebar-wrapper .menu .sidebar-item.active .sidebar-link .material-symbols-outlined {
        color: #FFFFFF !important;
        font-variation-settings: 'FILL' 1;
    }

    .stitch-sidebar-badge {
        font-family: 'Instrument Sans', sans-serif;
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 9999px;
        line-height: 1.2;
    }
</style>

<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <!-- Brand Header Stitch Style -->
        <div class="sidebar-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <div class="rounded-3 p-1 me-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px; background-color: var(--stitch-sidebar-surface); border: 1.5px solid var(--stitch-sidebar-active); flex-shrink: 0;">
                            <img src="{{ asset('assets/images/logo/image.png') }}" alt="Logo Kota Kediri" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                        <div>
                            <span class="fs-4 fw-bold text-white d-block font-headline" style="letter-spacing: -0.3px; line-height: 1.1;">E-RTLH</span>
                            <small class="fw-semibold d-block text-uppercase font-stitch" style="font-size: 0.68rem; letter-spacing: 0.8px; color: var(--stitch-sidebar-muted);">KOTA KEDIRI</small>
                        </div>
                    </a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block text-white" aria-label="Tutup navigasi">
                        <span class="material-symbols-outlined" style="font-size: 24px;">close</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar Navigation Menu -->
        <div class="sidebar-menu">
            @php
                $navFrom = request('from');
                $isWargaDetailOrEdit = request()->routeIs('warga.show', 'warga.edit');

                // Fallback referer jika parameter 'from' tidak disertakan di URL
                if ($isWargaDetailOrEdit && empty($navFrom)) {
                    $prevUrl = url()->previous();
                    if (str_contains($prevUrl, '/spk') || str_contains($prevUrl, '/verifikasi')) {
                        $navFrom = 'spk';
                    } elseif (str_contains($prevUrl, 'penerima-disetujui') || str_contains($prevUrl, 'disetujui')) {
                        $navFrom = 'disetujui';
                    } elseif (str_contains($prevUrl, 'realisasi')) {
                        $navFrom = 'realisasi';
                    } elseif (str_contains($prevUrl, 'dashboard')) {
                        $navFrom = 'dashboard';
                    }
                }

                $isWargaActive = request()->routeIs('warga.index', 'warga.create') 
                    || ($isWargaDetailOrEdit && (empty($navFrom) || $navFrom === 'warga' || $navFrom === 'usulan'));

                $isSpkActive = request()->is('spk*', 'verifikasi*') 
                    || ($isWargaDetailOrEdit && $navFrom === 'spk');

                $isDisetujuiActive = request()->routeIs('warga.disetujui') || request()->is('penerima-disetujui*') 
                    || ($isWargaDetailOrEdit && $navFrom === 'disetujui');

                $isRealisasiActive = request()->is('realisasi*') 
                    || ($isWargaDetailOrEdit && $navFrom === 'realisasi');

                $isDashboardActive = request()->is('dashboard*')
                    || ($isWargaDetailOrEdit && $navFrom === 'dashboard');
            @endphp

            <ul class="menu ps-0">
                <!-- Group: Menu Utama -->
                <li class="sidebar-title">Menu Utama</li>

                <li class="sidebar-item {{ $isDashboardActive ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class='sidebar-link'>
                        <span class="material-symbols-outlined me-2" style="font-size: 20px;">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                </li>

                <!-- Group: Pendataan & Verifikasi -->
                <li class="sidebar-title">Pendataan &amp; Verifikasi</li>

                <li class="sidebar-item {{ $isWargaActive ? 'active' : '' }}">
                    <a href="{{ route('warga.index') }}" class='sidebar-link d-flex justify-content-between align-items-center'>
                        <div class="d-flex align-items-center">
                            <span class="material-symbols-outlined me-2" style="font-size: 20px;">group</span>
                            <span>Daftar Usulan</span>
                        </div>
                        <span class="stitch-sidebar-badge" style="background: #FFFFFF; color: #001D4E;">Usulan</span>
                    </a>
                </li>

                <li class="sidebar-item {{ $isSpkActive ? 'active' : '' }}">
                    <a href="{{ route('spk.index') }}" class='sidebar-link d-flex justify-content-between align-items-center'>
                        <div class="d-flex align-items-center">
                            <span class="material-symbols-outlined me-2" style="font-size: 20px;">fact_check</span>
                            <span>Verifikasi &amp; SPK</span>
                        </div>
                        <span class="stitch-sidebar-badge" style="background: #FEF3C7; color: #92400E;">SAW</span>
                    </a>
                </li>

                <!-- Group: Penetapan & Pelaksanaan -->
                <li class="sidebar-title">Penetapan &amp; Pelaksanaan</li>

                <li class="sidebar-item {{ $isDisetujuiActive ? 'active' : '' }}">
                    <a href="{{ route('warga.disetujui') }}" class='sidebar-link d-flex justify-content-between align-items-center'>
                        <div class="d-flex align-items-center">
                            <span class="material-symbols-outlined me-2" style="font-size: 20px;">verified</span>
                            <span>Penerima SK</span>
                        </div>
                        <span class="stitch-sidebar-badge" style="background: var(--stitch-sidebar-surface); color: #E2E8F0; border: 1px solid rgba(255, 255, 255, 0.15);">SK</span>
                    </a>
                </li>

                <li class="sidebar-item {{ $isRealisasiActive ? 'active' : '' }}">
                    <a href="{{ route('realisasi.index') }}" class='sidebar-link d-flex justify-content-between align-items-center'>
                        <div class="d-flex align-items-center">
                            <span class="material-symbols-outlined me-2" style="font-size: 20px;">engineering</span>
                            <span>Realisasi</span>
                        </div>
                        <span class="stitch-sidebar-badge" style="background: #DCFCE7; color: #166534;">Progres</span>
                    </a>
                </li>

                <!-- Group: Master Data (Khusus Superadmin) -->
                @if(Auth::check() && Auth::user()->isSuperadmin())
                <li class="sidebar-title">Master Data</li>

                <li class="sidebar-item {{ request()->is('program-bantuan*') ? 'active' : '' }}">
                    <a href="{{ route('program-bantuan.index') }}" class='sidebar-link'>
                        <span class="material-symbols-outlined me-2" style="font-size: 20px;">card_giftcard</span>
                        <span>Jenis Bantuan</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->is('users*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}" class='sidebar-link'>
                        <span class="material-symbols-outlined me-2" style="font-size: 20px;">manage_accounts</span>
                        <span>Kelola Pengguna</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->is('landing-page*') ? 'active' : '' }}">
                    <a href="{{ route('landing-page.manage') }}" class='sidebar-link'>
                        <span class="material-symbols-outlined me-2" style="font-size: 20px;">web</span>
                        <span>Landing Page</span>
                    </a>
                </li>
                @endif

                <!-- Group: Sistem & Akun -->
                <li class="sidebar-title">Sistem &amp; Akun</li>

                @auth
                <!-- Info Akun Pengguna -->
                <li class="sidebar-item">
                    <div class="p-2 my-1 mx-2 rounded-3" style="background: var(--stitch-sidebar-surface); border: 1px solid rgba(255, 255, 255, 0.08);">
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center justify-content-center rounded-circle text-white font-headline fw-bold shrink-0" style="width: 36px; height: 36px; background-color: var(--stitch-sidebar-active); font-size: 13px;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div class="overflow-hidden min-w-0">
                                <div class="text-truncate text-white fw-semibold font-stitch" style="font-size: 12.5px;">{{ Auth::user()->name }}</div>
                                <span class="text-truncate d-block font-stitch" style="font-size: 11px; color: var(--stitch-sidebar-muted);">
                                    @if(Auth::user()->isSuperadmin())
                                        Super Admin
                                    @elseif(Auth::user()->isAdminDinas())
                                        Admin Dinas
                                    @else
                                        Admin Kelurahan
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- Tombol Logout -->
                <li class="sidebar-item">
                    <a href="{{ route('logout') }}" class='sidebar-link d-flex align-items-center' style="color: #FF8A8A; transition: all 0.15s ease;" onmouseover="this.style.backgroundColor='var(--stitch-sidebar-surface)'; this.style.color='#FFB4AB';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#FF8A8A';" onclick="return confirm('Apakah Anda yakin ingin logout?');">
                        <span class="material-symbols-outlined me-2" style="font-size: 20px; color: inherit;">logout</span>
                        <span>Keluar (Logout)</span>
                    </a>
                </li>
                @endauth
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
