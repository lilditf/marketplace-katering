<nav class="navbar navbar-expand-lg navbar-dark bg-success no-print">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="bi bi-basket2-fill me-1"></i>Marketplace Katering
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"
                aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigasi">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                @auth
                    @if (auth()->user()->isMerchant())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('merchant.dashboard') ? 'active fw-semibold' : '' }}"
                               href="{{ route('merchant.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('merchant.menus.*') ? 'active fw-semibold' : '' }}"
                               href="{{ route('merchant.menus.index') }}">Menu Saya</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('merchant.orders.*') || request()->routeIs('merchant.invoices.*') ? 'active fw-semibold' : '' }}"
                               href="{{ route('merchant.orders.index') }}">Order Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('merchant.profile.*') ? 'active fw-semibold' : '' }}"
                               href="{{ route('merchant.profile.edit') }}">Profil</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active fw-semibold' : '' }}"
                               href="{{ route('customer.dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customer.caterings.*') ? 'active fw-semibold' : '' }}"
                               href="{{ route('customer.caterings.index') }}">Cari Katering</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customer.orders.*') || request()->routeIs('customer.invoices.*') ? 'active fw-semibold' : '' }}"
                               href="{{ route('customer.orders.index') }}">Order Saya</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('customer.profile.*') ? 'active fw-semibold' : '' }}"
                               href="{{ route('customer.profile.edit') }}">Profil</a>
                        </li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav align-items-lg-center">
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active fw-semibold' : '' }}"
                           href="{{ route('login') }}">Masuk</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-light btn-sm px-3" href="{{ route('register') }}">Daftar</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <span class="dropdown-item-text small text-muted">
                                    {{ auth()->user()->isMerchant() ? 'Portal Katering' : 'Portal Kantor' }}
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
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
