<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Marketplace Katering')</title>
    {{-- Bootstrap 5 via CDN: UI responsif tanpa build step (Vite/npm tidak dipakai) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Tipografi: Plus Jakarta Sans untuk UI, Fraunces untuk aksen kuliner. --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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

    <footer class="footer-app py-4 no-print">
        <div class="container d-flex flex-wrap align-items-center justify-content-between gap-3 small">
            <div>
                <div class="footer-brand"><i class="bi bi-basket2-fill me-1"></i>Marketplace Katering</div>
                <div class="footer-copy">Makan siang kantor lebih teratur, dari pemesanan sampai invoice.</div>
            </div>
            <span class="footer-copy">Dito Firmansyach</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
