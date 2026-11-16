<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header pb-2">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <div class="bg-primary text-white rounded-3 p-2 me-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px;">
                            <i class="bi bi-houses-fill fs-4"></i>
                        </div>
                        <div>
                            <span class="fs-5 fw-bold text-primary d-block lh-1">E-RTLH</span>
                            <small class="text-muted fw-semibold" style="font-size: 0.72rem; letter-spacing: 0.5px;">KOTA KEDIRI</small>
                        </div>
                    </a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle fs-3"></i></a>
                </div>
            </div>
        </div>

        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu Utama</li>

                <li class="sidebar-item {{ request()->is('dashboard*') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sidebar-title">Pendataan &amp; Verifikasi</li>

                <li class="sidebar-item {{ request()->routeIs('warga.index', 'warga.create', 'warga.show', 'warga.edit') ? 'active' : '' }}">
                    <a href="{{ route('warga.index') }}" class='sidebar-link d-flex justify-content-between align-items-center'>
                        <div>
                            <i class="bi bi-people-fill"></i>
                            <span>Daftar Usulan</span>
                        </div>
                        <span class="badge bg-primary rounded-pill small" style="font-size: 0.65rem;">Usulan</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->is('spk*', 'verifikasi*') ? 'active' : '' }}">
                    <a href="{{ route('spk.index') }}" class='sidebar-link d-flex justify-content-between align-items-center'>
                        <div>
                            <i class="bi bi-shield-check"></i>
                            <span>Verifikasi &amp; SPK</span>
                        </div>
                        <span class="badge bg-warning text-dark rounded-pill small" style="font-size: 0.65rem;">SAW</span>
                    </a>
                </li>

                <li class="sidebar-title">Penetapan &amp; Pelaksanaan</li>

                <li class="sidebar-item {{ request()->routeIs('warga.disetujui') || request()->is('penerima-disetujui*') ? 'active' : '' }}">
                    <a href="{{ route('warga.disetujui') }}" class='sidebar-link d-flex justify-content-between align-items-center'>
                        <div>
                            <i class="bi bi-patch-check-fill"></i>
                            <span>Penerima Disetujui</span>
                        </div>
                        <span class="badge bg-info text-dark rounded-pill small" style="font-size: 0.65rem;">SK</span>
                    </a>
                </li>

                <li class="sidebar-item {{ request()->is('realisasi*') ? 'active' : '' }}">
                    <a href="{{ route('realisasi.index') }}" class='sidebar-link d-flex justify-content-between align-items-center'>
                        <div>
                            <i class="bi bi-hammer"></i>
                            <span>Realisasi Bantuan</span>
                        </div>
                        <span class="badge bg-success rounded-pill small" style="font-size: 0.65rem;">Progres</span>
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
                @endif

                <li class="sidebar-title">Sistem & Akun</li>

                @auth
                <li class="sidebar-item">
                    <div class="px-4 py-2 my-1 mx-2 rounded bg-light border border-1">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-md bg-primary text-white me-2 d-flex align-items-center justify-content-center rounded-circle" style="width:36px; height:36px;">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div class="overflow-hidden">
                                <h6 class="mb-0 text-truncate text-dark" style="font-size: 0.85rem;">{{ Auth::user()->name }}</h6>
                                <span class="badge bg-light-primary text-primary px-2 py-0" style="font-size: 0.7rem;">
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
                    <a href="{{ route('logout') }}" class='sidebar-link text-danger' onclick="return confirm('Apakah Anda yakin ingin logout?');">
                        <i class="bi bi-box-arrow-right text-danger"></i>
                        <span>Keluar (Logout)</span>
                    </a>
                </li>
                @endauth
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>