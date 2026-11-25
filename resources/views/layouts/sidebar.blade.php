<style>
    #sidebar .sidebar-wrapper {
        background-color: #1e3653 !important;
        border-right: 1px solid #14253a !important;
        box-shadow: 2px 0 16px rgba(10, 25, 45, 0.15);
    }
    #sidebar .sidebar-wrapper .sidebar-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    #sidebar .sidebar-wrapper .menu .sidebar-title {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #8da4be;
        margin: 1.2rem 0 0.4rem;
        padding: 0.75rem 1rem 0;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        pointer-events: none;
        user-select: none;
    }
    #sidebar .sidebar-wrapper .menu .sidebar-title:first-child {
        margin-top: 0.25rem;
        padding-top: 0;
        border-top: none;
    }
    #sidebar .sidebar-wrapper .menu .sidebar-item .sidebar-link {
        color: #cad7e6;
        border-radius: 10px;
        transition: all 0.2s ease;
    }
    #sidebar .sidebar-wrapper .menu .sidebar-item .sidebar-link i {
        color: #ebb866;
    }
    #sidebar .sidebar-wrapper .menu .sidebar-item .sidebar-link:hover {
        background-color: rgba(255, 255, 255, 0.08);
        color: #ffffff;
    }
    #sidebar .sidebar-wrapper .menu .sidebar-item .sidebar-link:hover i {
        color: #ffc000;
    }
    #sidebar .sidebar-wrapper .menu .sidebar-item.active .sidebar-link {
        background-color: #002b84 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
        border-left: 4px solid #ffc000;
    }
    #sidebar .sidebar-wrapper .menu .sidebar-item.active .sidebar-link i {
        color: #ffc000 !important;
    }
</style>

<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header pb-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <div class="rounded-3 p-1 me-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px; background-color: #ffffff; border: 1.5px solid rgba(235, 184, 102, 0.6); flex-shrink: 0;">
                            <img src="{{ asset('assets/images/logo/image.png') }}" alt="Logo Kota Kediri" style="width: 100%; height: 100%; object-fit: contain;">
                        </div>
                        <div>
                            <span class="fs-3 fw-bold text-white d-block">E-RTLH</span>
                            <small class="fw-bold d-block" style="font-size: 0.72rem; letter-spacing: 0.8px; color: #ebb866;">KOTA KEDIRI</small>
                        </div>
                    </a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block text-white"><i class="bi bi-x bi-middle fs-3"></i></a>
                </div>
            </div>
        </div>

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
            <ul class="menu">
                <li class="sidebar-title">Menu Utama</li>

                <li class="sidebar-item {{ $isDashboardActive ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-title">Pendataan &amp; Verifikasi</li>

                <li class="sidebar-item {{ $isWargaActive ? 'active' : '' }}">
                    <a href="{{ route('warga.index') }}" class='sidebar-link d-flex justify-content-between align-items-center'>
                        <div>
                            <i class="bi bi-people-fill"></i>
                            <span>Daftar Usulan</span>
                        </div>
                        <span class="badge rounded-pill small" style="font-size: 0.65rem; background-color: #002b84; color: #ffffff;">Usulan</span>
                    </a>
                </li>

                <li class="sidebar-item {{ $isSpkActive ? 'active' : '' }}">
                    <a href="{{ route('spk.index') }}" class='sidebar-link d-flex justify-content-between align-items-center'>
                        <div>
                            <i class="bi bi-shield-check"></i>
                            <span>Verifikasi &amp; SPK</span>
                        </div>
                        <span class="badge rounded-pill small fw-bold" style="font-size: 0.65rem; background-color: #ffc000; color: #1e3653;">SAW</span>
                    </a>
                </li>

                <li class="sidebar-title">Penetapan &amp; Pelaksanaan</li>

                <li class="sidebar-item {{ $isDisetujuiActive ? 'active' : '' }}">
                    <a href="{{ route('warga.disetujui') }}" class='sidebar-link d-flex justify-content-between align-items-center'>
                        <div>
                            <i class="bi bi-patch-check-fill"></i>
                            <span>Penerima Disetujui</span>
                        </div>
                        <span class="badge rounded-pill small" style="font-size: 0.65rem; background-color: rgba(255, 255, 255, 0.15); color: #ffffff;">SK</span>
                    </a>
                </li>

                <li class="sidebar-item {{ $isRealisasiActive ? 'active' : '' }}">
                    <a href="{{ route('realisasi.index') }}" class='sidebar-link d-flex justify-content-between align-items-center'>
                        <div>
                            <i class="bi bi-hammer"></i>
                            <span>Realisasi Bantuan</span>
                        </div>
                        <span class="badge rounded-pill small" style="font-size: 0.65rem; background-color: #054735; color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.2);">Progres</span>
                    </a>
                </li>

                @if(Auth::check() && Auth::user()->isSuperadmin())
                <li class="sidebar-title">Master Data</li>
                <li class="sidebar-item {{ request()->is('program-bantuan*') ? 'active' : '' }}">
                    <a href="{{ route('program-bantuan.index') }}" class='sidebar-link'>
                        <i class="bi bi-gift-fill"></i>
                        <span>Jenis Bantuan</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->is('users*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}" class='sidebar-link'>
                        <i class="bi bi-people-fill"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                </li>
                <li class="sidebar-item {{ request()->is('landing-page*') ? 'active' : '' }}">
                    <a href="{{ route('landing-page.manage') }}" class='sidebar-link'>
                        <i class="bi bi-window-stack"></i>
                        <span>Kelola Landing Page</span>
                    </a>
                </li>
                @endif

                <li class="sidebar-title">Sistem &amp; Akun</li>

                @auth
                <li class="sidebar-item">
                    <div class="px-3 py-2 my-1 mx-2 rounded" style="background: rgba(0, 0, 0, 0.25); border: 1px solid rgba(255, 255, 255, 0.1);">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-md me-2 d-flex align-items-center justify-content-center rounded-circle" style="width:36px; height:36px; background-color: #002b84; color: #ffc000; border: 1px solid rgba(255, 192, 0, 0.3);">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div class="overflow-hidden">
                                <h6 class="mb-0 text-truncate text-white" style="font-size: 0.85rem;">{{ Auth::user()->name }}</h6>
                                <span class="badge px-2 py-0" style="font-size: 0.7rem; background-color: rgba(235, 184, 102, 0.2); color: #ebb866; border: 1px solid rgba(235, 184, 102, 0.3);">
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

                <li class="sidebar-item">
                    <a href="{{ route('logout') }}" class='sidebar-link' style="color: #ff8585; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='rgba(255,133,133,0.1)'" onmouseout="this.style.backgroundColor='transparent'" onclick="return confirm('Apakah Anda yakin ingin logout?');">
                        <i class="bi bi-box-arrow-right" style="color: #ff8585;"></i>
                        <span>Keluar (Logout)</span>
                    </a>
                </li>
                @endauth
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
