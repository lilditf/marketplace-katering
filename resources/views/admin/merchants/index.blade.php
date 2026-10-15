@extends('layouts.app')

@section('title', 'Kelola Katering — Marketplace Katering')

@section('content')
    <h1 class="h4 fw-bold mb-3"><i class="bi bi-shop me-1 text-brand"></i>Kelola Katering</h1>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Katering</th>
                        <th>Kota</th>
                        <th>Jenis</th>
                        <th class="text-center">Menu</th>
                        <th class="text-center">Order</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($merchants as $merchant)
                        <tr class="align-middle">
                            <td>
                                <div class="fw-semibold small">{{ $merchant->company_name }}</div>
                                <div class="text-muted" style="font-size: .75rem;">{{ $merchant->user->email ?? '-' }}</div>
                            </td>
                            <td class="small">{{ $merchant->city }}</td>
                            <td><span class="badge bg-brand-subtle text-brand-emphasis">{{ $merchant->cuisine_type }}</span></td>
                            <td class="small text-center">{{ $merchant->menus_count }}</td>
                            <td class="small text-center">{{ $merchant->orders_count }}</td>
                            <td>
                                @if ($merchant->is_active)
                                    <span class="badge text-bg-success">Aktif</span>
                                @else
                                    <span class="badge text-bg-secondary">Nonaktif</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.merchants.show', $merchant) }}" class="btn btn-sm btn-outline-brand">Detail</a>
                                <form method="POST" action="{{ route('admin.merchants.toggle', $merchant) }}" class="d-inline"
                                      onsubmit="return confirm('Ubah status aktif {{ $merchant->company_name }}?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $merchant->is_active ? 'btn-outline-secondary' : 'btn-brand' }}">
                                        {{ $merchant->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted small p-3">Belum ada katering terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($merchants->hasPages())
            <div class="card-footer bg-white">{{ $merchants->links() }}</div>
        @endif
    </div>
@endsection
