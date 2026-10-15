@extends('layouts.app')

@section('title', $merchant->company_name . ' — Marketplace Katering')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('admin.merchants.index') }}">Kelola Katering</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $merchant->company_name }}</li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h1 class="h4 fw-bold mb-0">{{ $merchant->company_name }}</h1>
        <form method="POST" action="{{ route('admin.merchants.toggle', $merchant) }}"
              onsubmit="return confirm('Ubah status aktif {{ $merchant->company_name }}?');">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-sm {{ $merchant->is_active ? 'btn-outline-secondary' : 'btn-brand' }}">
                {{ $merchant->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
            </button>
        </form>
    </div>

    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Profil</div>
                <div class="card-body small">
                    <dl class="row mb-0">
                        <dt class="col-4 text-muted">Status</dt>
                        <dd class="col-8">
                            @if ($merchant->is_active)
                                <span class="badge text-bg-success">Aktif</span>
                            @else
                                <span class="badge text-bg-secondary">Nonaktif</span>
                            @endif
                        </dd>
                        <dt class="col-4 text-muted">Jenis</dt>
                        <dd class="col-8"><span class="badge bg-brand-subtle text-brand-emphasis">{{ $merchant->cuisine_type }}</span></dd>
                        <dt class="col-4 text-muted">Kota</dt>
                        <dd class="col-8">{{ $merchant->city }}</dd>
                        <dt class="col-4 text-muted">Alamat</dt>
                        <dd class="col-8">{{ $merchant->address ?? '-' }}</dd>
                        <dt class="col-4 text-muted">Kontak</dt>
                        <dd class="col-8">{{ $merchant->user->name ?? '-' }} · {{ $merchant->user->phone ?? '-' }}</dd>
                        <dt class="col-4 text-muted">Email</dt>
                        <dd class="col-8">{{ $merchant->user->email ?? '-' }}</dd>
                        <dt class="col-4 text-muted">Total Order</dt>
                        <dd class="col-8">{{ $merchant->orders_count }}</dd>
                        <dt class="col-4 text-muted">Deskripsi</dt>
                        <dd class="col-8 mb-0">{{ $merchant->description ?? '-' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Menu ({{ $merchant->menus->count() }})</div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Menu</th>
                                <th>Kategori</th>
                                <th class="text-end">Harga</th>
                                <th>Tersedia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($merchant->menus as $menu)
                                <tr class="align-middle">
                                    <td class="small fw-semibold">{{ $menu->name }}</td>
                                    <td class="small">{{ $menu->category }}</td>
                                    <td class="small text-end">Rp{{ number_format($menu->price, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($menu->is_available)
                                            <span class="badge text-bg-success">Ya</span>
                                        @else
                                            <span class="badge text-bg-secondary">Tidak</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted small p-3">Belum ada menu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
