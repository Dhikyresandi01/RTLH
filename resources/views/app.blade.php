<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | E-RTLH Kota Kediri</title>

    <link rel="preload" href="{{ asset('assets/vendors/bootstrap-icons/fonts/bootstrap-icons.woff2?4601c71fb26c9277391ec80789bfde9c') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('assets/vendors/iconly/fonts/Iconly---Bold.woff?jilz72') }}" as="font" type="font/woff" crossorigin>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendors/iconly/bold.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/image.png') }}" type="image/png">

    <style>
        :root {
            --primary-color: #002b84;
            --primary-dark: #1e3653;
            --forest-green: #054735;
            --warm-khaki: #ebb866;
            --gold-accent: #ffc000;
            --bg-page: #f0f4f8;
        }

        body {
            background: linear-gradient(rgba(240, 244, 248, 0.50)),url('{{ asset('assets/images/logo/image.png') }}');
            background-size: cover ;
            background-position: center ;
            background-repeat: no-repeat ;
            background-attachment: fixed ;
        }

        #main {
            background-image: transparent;
        }

        /* Card Styling */
        .card {
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 18px 0 rgba(30, 54, 83, 0.04);
            border-radius: 12px;
            background-color: #ffffff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card:hover {
            box-shadow: 0 8px 24px 0 rgba(30, 54, 83, 0.08);
        }

        /* Buttons Palette */
        .btn-primary {
            background-color: #002b84 !important;
            border-color: #002b84 !important;
            color: #ffffff !important;
            box-shadow: 0 2px 6px rgba(0, 43, 132, 0.2);
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: #1e3653 !important;
            border-color: #1e3653 !important;
            color: #ffffff !important;
        }
        .btn-outline-primary {
            color: #002b84 !important;
            border-color: #002b84 !important;
        }
        .btn-outline-primary:hover {
            background-color: #002b84 !important;
            color: #ffffff !important;
        }
        .btn-success {
            background-color: #054735 !important;
            border-color: #054735 !important;
            color: #ffffff !important;
        }
        .btn-success:hover {
            background-color: #032e22 !important;
            border-color: #032e22 !important;
        }
        .btn-warning {
            background-color: #ebb866 !important;
            border-color: #ebb866 !important;
            color: #1e3653 !important;
            font-weight: 600;
        }
        .btn-warning:hover {
            background-color: #dfa851 !important;
            border-color: #dfa851 !important;
            color: #1e3653 !important;
        }

        /* Text & Background Utilities */
        .text-primary { color: #002b84 !important; }
        .text-dark { color: #1e3653 !important; }
        .bg-primary { background-color: #002b84 !important; }
        .bg-success { background-color: #054735 !important; }
        .bg-warning { background-color: #ebb866 !important; color: #1e3653 !important; }
        .bg-info { background-color: #1e3653 !important; color: #ffffff !important; }

        .bg-light-primary { background-color: rgba(0, 43, 132, 0.08) !important; color: #002b84 !important; }
        .bg-light-success { background-color: rgba(5, 71, 53, 0.1) !important; color: #054735 !important; }
        .bg-light-warning { background-color: rgba(235, 184, 102, 0.2) !important; color: #875c00 !important; }
        .bg-light-info { background-color: rgba(30, 54, 83, 0.08) !important; color: #1e3653 !important; }

        /* Stats Icons for Dashboard */
        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .stats-icon.purple { background-color: #1e3653; }
        .stats-icon.amber { background-color: #ffc000; }
        .stats-icon.amber i { color: #1e3653 !important; }
        .stats-icon.blue { background-color: #002b84; }
        .stats-icon.green { background-color: #054735; }
        .stats-icon.red { background-color: #dc3545; }

        /* Badges */
        .badge.bg-primary { background-color: #002b84 !important; color: #ffffff !important; }
        .badge.bg-success { background-color: #054735 !important; color: #ffffff !important; }
        .badge.bg-warning { background-color: #ebb866 !important; color: #1e3653 !important; font-weight: 600; }
        .badge.bg-info { background-color: #1e3653 !important; color: #ffffff !important; }

        .badge-soft-primary { background-color: rgba(0, 43, 132, 0.1); color: #002b84; }
        .badge-soft-success { background-color: rgba(5, 71, 53, 0.12); color: #054735; }
        .badge-soft-warning { background-color: rgba(235, 184, 102, 0.25); color: #875c00; }
        .badge-soft-danger { background-color: #ffe5e0; color: #dc3545; }
        .badge-soft-info { background-color: rgba(30, 54, 83, 0.1); color: #1e3653; }

        /* Pagination & Tabs */
        .pagination .page-item.active .page-link {
            background-color: #002b84;
            border-color: #002b84;
        }
        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background-color: #002b84 !important;
            box-shadow: 0 4px 12px rgba(0, 43, 132, 0.25);
        }
    </style>
    @yield('styles')
    @stack('styles')
</head>

<body>
    <div id="app">
        @include('layouts.sidebar')
        <div id="main" class="d-flex flex-column min-vh-100">
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

    <!-- Modal Universal Preview Foto & Download -->
    @include('partials._modal_image_preview')

    @yield('scripts')
    @stack('scripts')
</body>

</html>