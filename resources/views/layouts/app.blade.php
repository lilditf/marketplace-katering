<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Marketplace Katering')</title>
    {{-- Bootstrap 5 via CDN: UI responsif tanpa build step (Vite/npm tidak dipakai) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Google Fonts: Karla (UI) + Playfair Display SC (wordmark & hero saja) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Karla:wght@400;500;600;700&family=Playfair+Display+SC:wght@700&display=swap" rel="stylesheet">
    {{-- Design tokens & brand layer (public/css/app.css) --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <a href="#konten" class="skip-link">Langsung ke konten</a>
    @include('partials.navbar')

    <main id="konten" class="container py-4 flex-grow-1">
        @include('partials.flash')
        @yield('content')
    </main>

    <footer class="footer-app py-3 no-print">
        <div class="container d-flex flex-wrap justify-content-between gap-2 small text-muted">
            <span>Marketplace Katering · menghubungkan katering &amp; kantor.</span>
            <span>Dito Firmansyach</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
