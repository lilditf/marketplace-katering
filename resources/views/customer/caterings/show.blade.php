@extends('layouts.portal')

@section('title', $catering->company_name . ' · Marketplace Katering')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('customer.caterings.index') }}">Cari Katering</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $catering->company_name }}</li>
        </ol>
    </nav>

    {{-- Profil katering --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <h1 class="h4 fw-bold mb-1">{{ $catering->company_name }}</h1>
                    <div class="small text-muted mb-2">
                        <i class="bi bi-geo-alt me-1"></i>{{ $catering->city }}
                        @if ($catering->address) · {{ $catering->address }} @endif
                        <span class="mx-1">·</span>
                        <i class="bi bi-telephone me-1"></i>{{ $catering->user->phone ?? '-' }}
                    </div>
                    <span class="badge bg-brand-subtle text-brand-emphasis">{{ $catering->cuisine_type }}</span>
                </div>
                <a href="{{ route('customer.orders.create', $catering) }}" class="btn btn-brand">
                    <i class="bi bi-cart-plus me-1"></i>Pesan dari Katering Ini
                </a>
            </div>
            @if ($catering->description)
                <hr>
                <p class="mb-0 text-muted">{{ $catering->description }}</p>
            @endif
        </div>
    </div>

    {{-- Menu per kategori --}}
    @forelse ($menus as $category => $items)
        <h2 class="h5 fw-bold mb-3 mt-4">{{ $category }}</h2>
        <div class="row g-3">
            @foreach ($items as $menu)
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100">
                        @if ($menu->photo_path)
                            {{-- path dari Storage::store() sudah termasuk folder "menus/" --}}
                            <img src="{{ asset('storage/' . $menu->photo_path) }}" class="card-img-top"
                                 alt="Foto {{ $menu->name }}" style="height: 160px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center text-muted"
                                 style="height: 160px;"><i class="bi bi-image" style="font-size: 2rem;"></i></div>
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <h3 class="h6 fw-bold mb-1">{{ $menu->name }}</h3>
                                <span class="badge bg-brand-subtle text-brand-emphasis text-nowrap">Rp{{ number_format($menu->price, 0, ',', '.') }}</span>
                            </div>
                            <p class="small text-muted mb-0">{{ $menu->description ?: 'Tanpa deskripsi.' }}</p>
                        </div>
                        <div class="card-footer bg-white border-0 pb-3">
                            <a href="{{ route('customer.orders.create', ['catering' => $catering, 'menu' => $menu->id]) }}"
                               class="btn btn-sm btn-outline-brand w-100">Pesan Menu Ini</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @empty
        <div class="card shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-egg-fried d-block mb-2" style="font-size: 2rem;"></i>
                Katering ini belum memiliki menu yang tersedia saat ini.
            </div>
        </div>
    @endforelse
@endsection
