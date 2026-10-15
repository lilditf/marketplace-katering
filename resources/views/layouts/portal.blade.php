<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Marketplace Katering')</title>
    {{-- Bootstrap 5 via CDN: UI responsif tanpa build step (Vite/npm tidak dipakai) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Google Fonts: Plus Jakarta Sans (UI) + Fraunces (aksen display) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Design tokens & brand layer (public/css/app.css) --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    {{-- Terapkan mode rail sidebar SEBELUM render agar tidak ada kedipan --}}
    <script>
        if (localStorage.getItem('mk-sidebar') === 'rail') {
            document.documentElement.classList.add('sidebar-collapsed');
        }
    </script>
</head>
<body>
    <a href="#konten" class="skip-link">Langsung ke konten</a>

    <div class="portal-shell">
        {{-- Sidebar fixed untuk desktop (lg ke atas); mobile memakai bottom nav --}}
        <aside class="portal-sidebar d-none d-lg-flex no-print">
            @include('partials.sidebar')
        </aside>

        <div class="portal-main">
            {{-- Topbar: judul halaman + menu user (tanpa hamburger) --}}
            <header class="portal-topbar no-print">
                {{-- Judul topbar diambil dari @section('title') tiap halaman
                     (bagian sebelum " — "), tanpa perlu mengubah view anak. --}}
                @php($pageTitle = \Illuminate\Support\Str::before(trim($__env->yieldContent('title')), ' — '))
                <h1 class="topbar-title">{{ $pageTitle !== '' && $pageTitle !== 'Marketplace Katering' ? $pageTitle : 'Dashboard' }}</h1>

                <div class="ms-auto dropdown">
                    <a class="btn btn-sm btn-outline-secondary dropdown-toggle" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <span class="dropdown-item-text small text-muted">
                                {{ auth()->user()->isMerchant() ? 'Portal Katering' : (auth()->user()->isAdmin() ? 'Portal Admin' : 'Portal Kantor') }}
                            </span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            {{-- Logout wajib POST: melindungi dari CSRF via link GET --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-1"></i>Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </header>

            <main id="konten" class="portal-content">
                @include('partials.flash')
                @yield('content')
            </main>
        </div>
    </div>

    {{-- Bottom nav mobile (pengganti hamburger) --}}
    @include('partials.bottomnav')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar rail di desktop; pilihan disimpan di localStorage.
        document.getElementById('sidebarCollapse')?.addEventListener('click', function () {
            const collapsed = document.documentElement.classList.toggle('sidebar-collapsed');
            localStorage.setItem('mk-sidebar', collapsed ? 'rail' : 'full');
        });
    </script>
    @stack('scripts')
</body>
</html>
