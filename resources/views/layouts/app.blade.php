<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Marketplace Katering')</title>
    {{-- Bootstrap 5 via CDN: UI responsif tanpa build step (Vite/npm tidak dipakai) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f5f6f8; }
        .navbar-brand { font-weight: 700; letter-spacing: .3px; }
        .card { border: 1px solid rgba(0, 0, 0, .08); }
        .table > :not(caption) > * > * { vertical-align: middle; }
        footer { background: #fff; }
        .menu-thumb { width: 64px; height: 48px; object-fit: cover; border-radius: .375rem; }
        @media print {
            .no-print, .navbar, footer { display: none !important; }
            body { background: #fff; }
            .card { border: none; box-shadow: none; }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    @include('partials.navbar')

    <main class="container py-4 flex-grow-1">
        @include('partials.flash')
        @yield('content')
    </main>

    <footer class="border-top py-3 no-print">
        <div class="container d-flex flex-wrap justify-content-between gap-2 small text-muted">
            <span>Marketplace Katering — menghubungkan katering &amp; kantor.</span>
            <span>Dito Firmansyach</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
