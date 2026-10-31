<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | E-RTLH Kota Kediri</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendors/iconly/bold.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon">

    <style>
        .card {
            border: none;
            box-shadow: 0 4px 18px 0 rgba(0, 0, 0, 0.04);
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            box-shadow: 0 8px 24px 0 rgba(0, 0, 0, 0.08);
        }
        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stats-icon.purple { background-color: #696cff; }
        .stats-icon.blue { background-color: #03c3ec; }
        .stats-icon.green { background-color: #71dd37; }
        .stats-icon.red { background-color: #ff3e1d; }
        .stats-icon.amber { background-color: #ffab00; }
        .badge-soft-primary { background-color: #e7e7ff; color: #696cff; }
        .badge-soft-success { background-color: #e8fadf; color: #71dd37; }
        .badge-soft-warning { background-color: #fff2d6; color: #ffab00; }
        .badge-soft-danger { background-color: #ffe5e0; color: #ff3e1d; }
        .badge-soft-info { background-color: #d7f5fc; color: #03c3ec; }
    </style>
    @yield('styles')
    @stack('styles')
</head>

<body>
    <div id="app">
        @include('layouts.sidebar')
        <div id="main" class="d-flex flex-column min-vh-100">
            <!-- Top Header Navbar -->
            <header class="mb-4">
                <nav class="navbar navbar-expand navbar-light px-0 py-2">
                    <div class="container-fluid p-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <a href="#" class="burger-btn d-block d-xl-none me-3">
                                <i class="bi bi-justify fs-3"></i>
                            </a>
                            <div>
                                <span class="badge bg-light-primary text-primary fw-bold text-uppercase d-none d-md-inline-block me-2">
                                    <i class="bi bi-geo-alt-fill me-1"></i> Kota Kediri
                                </span>
                                <small class="text-muted d-none d-lg-inline">
                                    Sistem Penanganan Bantuan Sosial Rumah Tidak Layak Huni
                                </small>
                            </div>
                        </div>

                        @auth
                        <div class="d-flex align-items-center gap-2">
                            <div class="text-end me-2 d-none d-sm-block">
                                <div class="fw-bold text-dark lh-1" style="font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    @if(Auth::user()->isSuperadmin())
                                        Super Administrator
                                    @elseif(Auth::user()->isAdminDinas())
                                        Admin DPKP Kota Kediri
                                    @else
                                        Admin {{ Auth::user()->wilayah->desa ?? 'Kelurahan' }}
                                    @endif
                                </small>
                            </div>
                            <div class="dropdown">
                                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="avatar bg-primary text-white d-flex align-items-center justify-content-center rounded-circle shadow-sm" style="width: 38px; height: 38px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userDropdown">
                                    <li><h6 class="dropdown-header">{{ Auth::user()->email }}</h6></li>
                                    <li><span class="dropdown-item-text text-muted small"><i class="bi bi-shield-check me-2"></i>{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</span></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="return confirm('Keluar dari aplikasi?')">
                                            <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endauth
                    </div>
                </nav>
            </header>

            <!-- Main Blade Content -->
            <div class="flex-grow-1">
                @yield('content')
            </div>

            <!-- Footer -->
            <footer class="mt-5 pt-3 border-top">
                <div class="footer clearfix mb-0 text-muted d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <p class="mb-0">&copy; {{ date('Y') }} <strong>Dinas Perumahan dan Kawasan Permukiman Kota Kediri</strong></p>
                        <small class="text-muted">Berdasarkan Perwali Kota Kediri No. 18 Tahun 2026</small>
                    </div>
                    <div>
                        <span class="badge bg-light-secondary text-secondary">E-RTLH v1.0 Blade</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/apexcharts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    @yield('scripts')
    @stack('scripts')
</body>

</html>