{{-- Bottom navigation untuk mobile (pengganti hamburger): semua item utama
     langsung terjangkau ibu jari. Maksimal 5 item per role. --}}
<nav class="portal-bottomnav d-lg-none no-print" aria-label="Menu portal">
    @if (auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i>Dashboard</a>
        <a href="{{ route('admin.merchants.index') }}" class="{{ request()->routeIs('admin.merchants.*') ? 'active' : '' }}"><i class="bi bi-shop"></i>Katering</a>
        <a href="{{ route('admin.customers.index') }}" class="{{ request()->routeIs('admin.customers.*') ? 'active' : '' }}"><i class="bi bi-building"></i>Kantor</a>
        <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"><i class="bi bi-receipt"></i>Order</a>
        <a href="{{ route('admin.cuisines.index') }}" class="{{ request()->routeIs('admin.cuisines.*') ? 'active' : '' }}"><i class="bi bi-tags"></i>Jenis</a>
    @elseif (auth()->user()->isMerchant())
        <a href="{{ route('merchant.dashboard') }}" class="{{ request()->routeIs('merchant.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i>Dashboard</a>
        <a href="{{ route('merchant.menus.index') }}" class="{{ request()->routeIs('merchant.menus.*') ? 'active' : '' }}"><i class="bi bi-egg-fried"></i>Menu</a>
        <a href="{{ route('merchant.orders.index') }}" class="{{ request()->routeIs('merchant.orders.*') || request()->routeIs('merchant.invoices.*') ? 'active' : '' }}"><i class="bi bi-inboxes"></i>Order</a>
        <a href="{{ route('merchant.profile.edit') }}" class="{{ request()->routeIs('merchant.profile.*') ? 'active' : '' }}"><i class="bi bi-person-badge"></i>Profil</a>
    @else
        <a href="{{ route('customer.dashboard') }}" class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i>Dashboard</a>
        <a href="{{ route('customer.caterings.index') }}" class="{{ request()->routeIs('customer.caterings.*') ? 'active' : '' }}"><i class="bi bi-search"></i>Cari</a>
        <a href="{{ route('customer.orders.index') }}" class="{{ request()->routeIs('customer.orders.*') || request()->routeIs('customer.invoices.*') ? 'active' : '' }}"><i class="bi bi-clock-history"></i>Order</a>
        <a href="{{ route('customer.profile.edit') }}" class="{{ request()->routeIs('customer.profile.*') ? 'active' : '' }}"><i class="bi bi-building"></i>Profil</a>
    @endif
</nav>
