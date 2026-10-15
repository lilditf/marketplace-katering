@extends('layouts.app')

@section('title', 'Dashboard Merchant — Marketplace Katering')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h4 fw-bold mb-1">Dashboard Katering</h1>
            <p class="text-muted small mb-0">
                {{ $merchant->company_name }} · {{ $merchant->city }}
                <span class="badge bg-success-subtle text-success-emphasis ms-1">{{ $merchant->cuisine_type }}</span>
                @unless ($merchant->is_active)
                    <span class="badge bg-secondary ms-1">Profil Nonaktif (tidak tampil di pencarian)</span>
                @endunless
            </p>
        </div>
        <a href="{{ route('merchant.menus.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i>Tambah Menu
        </a>
    </div>

    {{-- Kartu statistik (angka dihitung agregat di database) --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small"><i class="bi bi-egg-fried me-1"></i>Menu Saya</div>
                    <div class="fs-3 fw-bold">{{ $totalMenus }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small"><i class="bi bi-receipt me-1"></i>Total Order</div>
                    <div class="fs-3 fw-bold">{{ $totalOrders }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small"><i class="bi bi-hourglass-split me-1"></i>Menunggu Konfirmasi</div>
                    <div class="fs-3 fw-bold {{ $ordersPending > 0 ? 'text-warning' : '' }}">{{ $ordersPending }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small"><i class="bi bi-cash-stack me-1"></i>Omzet Bulan Ini</div>
                    <div class="fs-5 fw-bold mt-2">Rp{{ number_format($revenueMonth, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        {{-- Menu terlaris --}}
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold"><i class="bi bi-trophy me-1 text-warning"></i>Menu Terlaris</div>
                <div class="card-body p-0">
                    @forelse ($topMenus as $item)
                        <div class="d-flex justify-content-between align-items-center border-bottom px-3 py-2">
                            <div class="d-flex align-items-center gap-2">
                                @if ($item->menu && $item->menu->photo_path)
                                    <img src="{{ asset('storage/' . $item->menu->photo_path) }}" alt="" class="rounded"
                                         style="width: 40px; height: 32px; object-fit: cover;">
                                @else
                                    <i class="bi bi-image text-muted"></i>
                                @endif
                                <span>{{ $item->menu->name ?? '(menu dihapus)' }}</span>
                            </div>
                            <span class="badge bg-success-subtle text-success-emphasis">{{ $item->total_qty }} porsi</span>
                        </div>
                    @empty
                        <p class="text-muted small p-3 mb-0">Belum ada penjualan. Semangat!</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Order terbaru --}}
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-clock-history me-1"></i>Order Terbaru</span>
                    <a href="{{ route('merchant.orders.index') }}" class="small">Lihat semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Order</th>
                                <th>Kantor</th>
                                <th>Kirim</th>
                                <th class="text-end">Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentOrders as $order)
                                <tr class="align-middle" style="cursor: pointer;"
                                    onclick="window.location='{{ route('merchant.orders.show', $order) }}'">
                                    <td class="small fw-semibold">{{ $order->order_number }}</td>
                                    <td class="small">{{ $order->customerProfile->office_name ?? '-' }}</td>
                                    <td class="small">{{ $order->delivery_date->translatedFormat('d M Y') }}</td>
                                    <td class="small text-end">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                    <td>@include('partials.status-badge', ['status' => $order->status])</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-muted small p-3">Belum ada order yang masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
