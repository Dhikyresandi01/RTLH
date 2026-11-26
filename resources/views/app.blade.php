<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | E-RTLH Kota Kediri</title>

    <link rel="preload" href="{{ asset('assets/vendors/bootstrap-icons/fonts/bootstrap-icons.woff2?4601c71fb26c9277391ec80789bfde9c') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ asset('assets/vendors/iconly/fonts/Iconly---Bold.woff?jilz72') }}" as="font" type="font/woff" crossorigin>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=Nunito:wght@300;400;600;700;800&family=Spline+Sans+Mono:wght@400;500;600&family=Wix+Madefor+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendors/iconly/bold.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/bg/image (1).jpg') }}" type="image/jpg">

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
            background: linear-gradient(rgba(240, 244, 248, 0.80)),url('{{ asset('assets/images/bg/images (1).jpg') }}');
            background-size: cover ;
            background-position: center ;
            background-repeat: no-repeat ;
            background-attachment: fixed ;
        }

        #main {
            background-image: transparent;
        }

        /* ================= STITCH DESIGN SYSTEM TOKENS & TABLES ================= */
        :root {
            --stitch-primary: #001D4E;
            --stitch-primary-hover: #16336B;
            --stitch-surface: #F8FAFC;
            --stitch-border: #CBD5E1;
            --stitch-info-bg: #DBEAFE;
            --stitch-info-text: #1E40AF;
            --stitch-success-bg: #DCFCE7;
            --stitch-success-text: #166534;
            --stitch-warning-bg: #FEF3C7;
            --stitch-warning-text: #92400E;
            --stitch-error-bg: #FFDAD6;
            --stitch-error-text: #93000A;
        }

        .font-mono-num {
            font-family: 'Spline Sans Mono', monospace !important;
            font-variant-numeric: tabular-nums;
            font-size: 11.5px;
        }

        .font-headline {
            font-family: 'Wix Madefor Display', sans-serif !important;
        }

        .font-stitch {
            font-family: 'Instrument Sans', sans-serif !important;
        }

        .stitch-header-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 9999px;
            background: #FFFFFF;
            border: 1px solid var(--stitch-border);
            color: var(--stitch-primary);
            font-weight: 600;
            font-size: 13px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
            white-space: nowrap;
        }

        .stitch-header-badge .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #1D4ED8;
        }

        /* Stitch Card & Table */
        .stitch-card {
            background: #FFFFFF;
            border: 1px solid var(--stitch-border);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04);
        }

        .stitch-toolbar {
            padding: 12px 18px;
            background: #FFFFFF;
            border-bottom: 1px solid var(--stitch-border);
        }

        .stitch-table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Instrument Sans', sans-serif;
            margin-bottom: 0;
        }

        .stitch-table thead th {
            background: #F8FAFC !important;
            border-bottom: 1px solid var(--stitch-border) !important;
            color: #475569;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 11px 14px !important;
            white-space: nowrap;
        }

        .stitch-table tbody tr {
            transition: background-color 0.12s ease;
            border-bottom: 1px solid #E2E8F0;
        }

        .stitch-table tbody tr:hover {
            background-color: #F8FAFC !important;
        }

        .stitch-table tbody td {
            padding: 12px 14px !important;
            vertical-align: middle;
            font-size: 13px;
            color: #1E293B;
            border-top: none !important;
        }

        /* Micro-indicators */
        .micro-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 4px;
            line-height: 1.2;
            width: fit-content;
        }

        .micro-pill .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .micro-pill-danger { background: var(--stitch-error-bg); color: var(--stitch-error-text); }
        .micro-pill-danger .dot { background-color: #BA1A1A; }
        .micro-pill-warning { background: var(--stitch-warning-bg); color: var(--stitch-warning-text); }
        .micro-pill-warning .dot { background-color: #B45309; }
        .micro-pill-success { background: var(--stitch-success-bg); color: var(--stitch-success-text); }
        .micro-pill-success .dot { background-color: #15803D; }

        /* Action Buttons */
        .stitch-btn-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: #F8FAFC;
            border: 1px solid var(--stitch-border);
            color: #475569;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .stitch-btn-icon:hover { background: #FFFFFF; color: var(--stitch-primary); border-color: #94A3B8; }

        .stitch-btn-verify {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 10px;
            height: 32px;
            border-radius: 6px;
            background: var(--stitch-primary);
            color: #FFFFFF;
            font-size: 12px;
            font-weight: 600;
            border: none;
            transition: background-color 0.15s ease;
        }
        .stitch-btn-verify:hover { background: var(--stitch-primary-hover); color: #FFFFFF; }

        .stitch-btn-edit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: var(--stitch-warning-bg);
            color: var(--stitch-warning-text);
            border: none;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .stitch-btn-edit:hover { background: #FDE68A; color: #78350F; }

        .stitch-btn-delete {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: var(--stitch-error-bg);
            color: var(--stitch-error-text);
            border: none;
            transition: all 0.15s ease;
        }
        .stitch-btn-delete:hover { background: #FECDD3; color: #7F1D1D; }

        /* Stitch Modern KPI Summary Card */
        .stitch-kpi-card {
            background: #FFFFFF;
            border: 1px solid var(--stitch-border);
            border-radius: 12px;
            padding: 16px 18px;
            box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
            display: flex;
            align-items: center;
            gap: 14px;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            height: 100%;
        }
        .stitch-kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
        }
        .stitch-kpi-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .stitch-kpi-icon .material-symbols-outlined {
            font-size: 24px;
        }
        .stitch-kpi-val {
            font-family: 'Wix Madefor Display', sans-serif;
            font-size: 22px;
            font-weight: 700;
            line-height: 1.2;
            color: #0F172A;
        }
        .stitch-kpi-label {
            font-family: 'Instrument Sans', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #64748B;
            margin-bottom: 2px;
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

        /* High-contrast page headers & text readability over background photo (Opsi A) */
        .page-heading h3, 
        .page-title h3,
        .page-heading h1,
        .page-heading h2 {
            color: #001D4E !important;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.95);
        }

        .page-heading .text-muted,
        .page-title .text-muted,
        .page-heading p.text-muted,
        .page-title p.text-muted,
        .page-subtitle {
            color: #0F172A !important;
            font-weight: 500 !important;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.95);
        }

        .page-heading .text-muted strong,
        .page-title .text-muted strong,
        .page-heading p strong {
            color: #001D4E !important;
            font-weight: 700 !important;
        }

        /* Solid white background for outline buttons in page headers so they stay clear */
        .page-heading .btn-outline-primary,
        .page-heading .btn-outline-secondary,
        .page-heading .btn-outline-info,
        .page-heading .btn-outline-dark,
        .page-title .btn-outline-primary,
        .page-title .btn-outline-secondary {
            background-color: #FFFFFF !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12) !important;
            font-weight: 600 !important;
        }

        .page-heading .btn-outline-primary {
            color: #002b84 !important;
            border-color: #002b84 !important;
        }

        .page-heading .btn-outline-primary:hover {
            background-color: #002b84 !important;
            color: #FFFFFF !important;
        }

        .page-heading .btn-outline-secondary {
            color: #1E293B !important;
            border-color: #94A3B8 !important;
        }

        .page-heading .btn-outline-secondary:hover {
            background-color: #F1F5F9 !important;
            color: #0F172A !important;
            border-color: #64748B !important;
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