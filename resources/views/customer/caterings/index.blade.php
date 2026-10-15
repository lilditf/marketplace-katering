@extends('layouts.app')

@section('title', 'Cari Katering — Marketplace Katering')

@section('content')
    <h1 class="h4 fw-bold mb-3"><i class="bi bi-search me-1 text-success"></i>Cari Katering</h1>

    {{-- Form pencarian: method GET agar filter bisa dibagikan/di-bookmark --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('customer.caterings.index') }}" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label for="q" class="form-label small text-muted mb-1">Kata kunci</label>
                    <input type="text" class="form-control" id="q" name="q" value="{{ $keyword }}"
                           placeholder="Nama katering, deskripsi, atau kota…">
                </div>
                <div class="col-md-3">
                    <label for="city" class="form-label small text-muted mb-1">Lokasi (kota)</label>
                    <select class="form-select" id="city" name="city">
                        <option value="">Semua kota</option>
                        @foreach ($cities as $c)
                            <option value="{{ $c }}" @selected($city === $c)>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="cuisine" class="form-label small text-muted mb-1">Jenis makanan</label>
                    <select class="form-select" id="cuisine" name="cuisine">
                        <option value="">Semua jenis</option>
                        @foreach ($cuisineTypes as $type)
                            <option value="{{ $type }}" @selected($cuisine === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1 d-grid">
                    <button type="submit" class="btn btn-success" title="Cari"><i class="bi bi-search"></i></button>
                </div>
            </form>
            @if ($keyword || $city || $cuisine)
                <div class="mt-2 small">
                    Filter aktif:
                    @if ($keyword)<span class="badge bg-success-subtle text-success-emphasis">q: {{ $keyword }}</span>@endif
                    @if ($city)<span class="badge bg-success-subtle text-success-emphasis">{{ $city }}</span>@endif
                    @if ($cuisine)<span class="badge bg-success-subtle text-success-emphasis">{{ $cuisine }}</span>@endif
                    <a href="{{ route('customer.caterings.index') }}" class="ms-1">hapus semua</a>
                </div>
            @endif
        </div>
    </div>

    {{-- Hasil pencarian --}}
    <p class="small text-muted">Menampilkan {{ $merchants->total() }} katering.</p>
    <div class="row g-3">
        @forelse ($merchants as $merchantProfile)
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h2 class="h6 fw-bold mb-0">{{ $merchantProfile->company_name }}</h2>
                            <span class="badge bg-success-subtle text-success-emphasis">{{ $merchantProfile->cuisine_type }}</span>
                        </div>
                        <div class="small text-muted mb-2">
                            <i class="bi bi-geo-alt me-1"></i>{{ $merchantProfile->city }}
                            <span class="mx-1">·</span>
                            <i class="bi bi-egg-fried me-1"></i>{{ $merchantProfile->available_menus_count }} menu tersedia
                        </div>
                        <p class="small text-muted flex-grow-1">
                            {{ $merchantProfile->description ? \Illuminate\Support\Str::limit($merchantProfile->description, 110) : 'Belum ada deskripsi.' }}
                        </p>
                        <div class="d-flex gap-2">
                            <a href="{{ route('customer.caterings.show', $merchantProfile) }}"
                               class="btn btn-sm btn-outline-success flex-grow-1">Lihat Menu</a>
                            <a href="{{ route('customer.orders.create', $merchantProfile) }}"
                               class="btn btn-sm btn-success flex-grow-1">Pesan</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body text-center text-muted py-5">
                        <i class="bi bi-search d-block mb-2" style="font-size: 2rem;"></i>
                        Tidak ada katering yang cocok dengan filter Anda.<br>
                        Coba kata kunci lain atau <a href="{{ route('customer.caterings.index') }}">hapus filter</a>.
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    @if ($merchants->hasPages())
        <div class="mt-4">{{ $merchants->links() }}</div>
    @endif
@endsection
