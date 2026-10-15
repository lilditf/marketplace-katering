{{-- Menu sidebar portal. Dipakai dua kali: sidebar fixed (desktop) dan
     offcanvas (mobile) — isi identik, cukup ubah di satu file ini. --}}
<a class="sidebar-brand" href="{{ route('home') }}">
    <i class="bi bi-basket2-fill"></i>Marketplace Katering
</a>

<div class="sidebar-section">Menu</div>
<nav class="d-flex flex-column">
    @if (auth()->user()->isAdmin())
        <a class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
           href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2"></i>Dashboard</a>
        <a class="sidebar-link {{ request()->routeIs('admin.merchants.*') ? 'active' : '' }}"
           href="{{ route('admin.merchants.index') }}"><i class="bi bi-shop"></i>Katering</a>
        <a class="sidebar-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}"
           href="{{ route('admin.customers.index') }}"><i class="bi bi-building"></i>Kantor</a>
        <a class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"
           href="{{ route('admin.orders.index') }}"><i class="bi bi-receipt"></i>Order</a>
        <a class="sidebar-link {{ request()->routeIs('admin.cuisines.*') ? 'active' : '' }}"
           href="{{ route('admin.cuisines.index') }}"><i class="bi bi-tags"></i>Jenis Makanan</a>
    @elseif (auth()->user()->isMerchant())
        <a class="sidebar-link {{ request()->routeIs('merchant.dashboard') ? 'active' : '' }}"
           href="{{ route('merchant.dashboard') }}"><i class="bi bi-speedometer2"></i>Dashboard</a>
        <a class="sidebar-link {{ request()->routeIs('merchant.menus.*') ? 'active' : '' }}"
           href="{{ route('merchant.menus.index') }}"><i class="bi bi-egg-fried"></i>Menu Saya</a>
        <a class="sidebar-link {{ request()->routeIs('merchant.orders.*') || request()->routeIs('merchant.invoices.*') ? 'active' : '' }}"
           href="{{ route('merchant.orders.index') }}"><i class="bi bi-inboxes"></i>Order Masuk</a>
        <a class="sidebar-link {{ request()->routeIs('merchant.profile.*') ? 'active' : '' }}"
           href="{{ route('merchant.profile.edit') }}"><i class="bi bi-person-badge"></i>Profil</a>
    @else
        <a class="sidebar-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}"
           href="{{ route('customer.dashboard') }}"><i class="bi bi-speedometer2"></i>Dashboard</a>
        <a class="sidebar-link {{ request()->routeIs('customer.caterings.*') ? 'active' : '' }}"
           href="{{ route('customer.caterings.index') }}"><i class="bi bi-search"></i>Cari Katering</a>
        <a class="sidebar-link {{ request()->routeIs('customer.orders.*') || request()->routeIs('customer.invoices.*') ? 'active' : '' }}"
           href="{{ route('customer.orders.index') }}"><i class="bi bi-clock-history"></i>Order Saya</a>
        <a class="sidebar-link {{ request()->routeIs('customer.profile.*') ? 'active' : '' }}"
           href="{{ route('customer.profile.edit') }}"><i class="bi bi-building"></i>Profil</a>
    @endif
</nav>

<div class="sidebar-foot">
    <a href="{{ route('home') }}"><i class="bi bi-house me-1"></i>Lihat Beranda</a>
    <div class="mt-1">Masuk sebagai {{ auth()->user()->name }}</div>
</div>
