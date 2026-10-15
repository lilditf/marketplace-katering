{{-- Menu sidebar portal (desktop). Label dibungkus .sidebar-label agar
     sidebar bisa menyempit jadi rail ikon saat tombol collapse ditekan.
     Atribut title = tooltip native saat mode rail. --}}
<a class="sidebar-brand" href="{{ route('home') }}" title="Marketplace Katering">
    <i class="bi bi-basket2-fill"></i><span class="brand-text">Marketplace Katering</span>
</a>

<button type="button" class="sidebar-collapse-btn d-none d-lg-flex" id="sidebarCollapse"
        aria-label="Sembunyikan atau lebarkan menu" title="Sembunyikan atau lebarkan menu">
    <i class="bi bi-chevron-double-left"></i><span class="collapse-text">Sembunyikan</span>
</button>

<div class="sidebar-section">Menu</div>
<nav class="d-flex flex-column">
    @if (auth()->user()->isAdmin())
        <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
           href="{{ route('admin.dashboard') }}" title="Dashboard"><i class="bi bi-speedometer2"></i><span class="sidebar-label">Dashboard</span></a>
        <a class="sidebar-link {{ request()->routeIs('admin.merchants.*') ? 'active' : '' }}"
           href="{{ route('admin.merchants.index') }}" title="Katering"><i class="bi bi-shop"></i><span class="sidebar-label">Katering</span></a>
        <a class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}"
           href="{{ route('admin.customers.index') }}" title="Kantor"><i class="bi bi-building"></i><span class="sidebar-label">Kantor</span></a>
        <a class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
           href="{{ route('admin.orders.index') }}" title="Order"><i class="bi bi-receipt"></i><span class="sidebar-label">Order</span></a>
        <a class="sidebar-link {{ request()->routeIs('admin.cuisines.*') ? 'active' : '' }}"
           href="{{ route('admin.cuisines.index') }}" title="Jenis Makanan"><i class="bi bi-tags"></i><span class="sidebar-label">Jenis Makanan</span></a>
    @elseif (auth()->user()->isMerchant())
        <a class="sidebar-link {{ request()->routeIs('merchant.dashboard') ? 'active' : '' }}"
           href="{{ route('merchant.dashboard') }}" title="Dashboard"><i class="bi bi-speedometer2"></i><span class="sidebar-label">Dashboard</span></a>
        <a class="sidebar-link {{ request()->routeIs('merchant.menus.*') ? 'active' : '' }}"
           href="{{ route('merchant.menus.index') }}" title="Menu Saya"><i class="bi bi-egg-fried"></i><span class="sidebar-label">Menu Saya</span></a>
        <a class="sidebar-link {{ request()->routeIs('merchant.orders.*') || request()->routeIs('merchant.invoices.*') ? 'active' : '' }}"
           href="{{ route('merchant.orders.index') }}" title="Order Masuk"><i class="bi bi-inboxes"></i><span class="sidebar-label">Order Masuk</span></a>
        <a class="sidebar-link {{ request()->routeIs('merchant.profile.*') ? 'active' : '' }}"
           href="{{ route('merchant.profile.edit') }}" title="Profil"><i class="bi bi-person-badge"></i><span class="sidebar-label">Profil</span></a>
    @else
        <a class="sidebar-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}"
           href="{{ route('customer.dashboard') }}" title="Dashboard"><i class="bi bi-speedometer2"></i><span class="sidebar-label">Dashboard</span></a>
        <a class="sidebar-link {{ request()->routeIs('customer.caterings.*') ? 'active' : '' }}"
           href="{{ route('customer.caterings.index') }}" title="Cari Katering"><i class="bi bi-search"></i><span class="sidebar-label">Cari Katering</span></a>
        <a class="sidebar-link {{ request()->routeIs('customer.orders.*') || request()->routeIs('customer.invoices.*') ? 'active' : '' }}"
           href="{{ route('customer.orders.index') }}" title="Order Saya"><i class="bi bi-clock-history"></i><span class="sidebar-label">Order Saya</span></a>
        <a class="sidebar-link {{ request()->routeIs('customer.profile.*') ? 'active' : '' }}"
           href="{{ route('customer.profile.edit') }}" title="Profil"><i class="bi bi-building"></i><span class="sidebar-label">Profil</span></a>
    @endif
</nav>

<div class="sidebar-foot">
    <a href="{{ route('home') }}"><i class="bi bi-house me-1"></i>Lihat Beranda</a>
    <div class="mt-1">Masuk sebagai {{ auth()->user()->name }}</div>
</div>
