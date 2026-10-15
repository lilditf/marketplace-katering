@extends('layouts.portal')

@section('title', 'Dashboard Kantor · Marketplace Katering')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h4 fw-bold mb-1">{{ $customer->office_name }}</h1>
            <p class="text-muted small mb-0">
                {{ $customer->city }} · PIC: {{ $customer->pic_name ?: '-' }}
            </p>
        </div>
        <a href="{{ route('customer.caterings.index') }}" class="btn btn-brand">
            <i class="bi bi-search me-1"></i>Cari Katering
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small"><i class="bi bi-receipt me-1"></i>Total Order</div>
                    <div class="fs-3 fw-bold">{{ $totalOrders }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small"><i class="bi bi-truck me-1"></i>Order Aktif</div>
                    <div class="fs-3 fw-bold {{ $activeOrders->count() > 0 ? 'text-primary' : '' }}">{{ $activeOrders->count() }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small"><i class="bi bi-cash-stack me-1"></i>Total Belanja (Selesai)</div>
                    <div class="fs-5 fw-bold mt-2">Rp{{ number_format($totalSpent, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Order yang sedang berjalan --}}
    <h2 class="h5 fw-bold mb-3"><i class="bi bi-activity me-1 text-primary"></i>Sedang Berjalan</h2>
    @forelse ($activeOrders as $order)
        <div class="card shadow-sm mb-2">
            <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <div class="fw-semibold">{{ $order->merchantProfile->company_name ?? '-' }}</div>
                    <div class="small text-muted">
                        {{ $order->order_number }} · kirim {{ $order->delivery_date->translatedFormat('d M Y') }} ·
                        Rp{{ number_format($order->subtotal, 0, ',', '.') }}
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @include('partials.status-badge', ['status' => $order->status])
                    @if ($order->invoice)
                        @include('partials.status-badge', ['status' => $order->invoice->payment_status])
                    @endif
                    <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-sm btn-outline-brand">Detail</a>
                </div>
            </div>
        </div>
    @empty
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center text-muted py-4">
                <i class="bi bi-basket2 d-block mb-2" style="font-size: 2rem;"></i>
                Tidak ada order aktif. <a href="{{ route('customer.caterings.index') }}">Cari katering sekarang</a> untuk pesan makan siang kantor.
            </div>
        </div>
    @endforelse

    {{-- Riwayat terbaru --}}
    <h2 class="h5 fw-bold mb-3 mt-4"><i class="bi bi-clock-history me-1"></i>Riwayat Terbaru</h2>
    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Order</th>
                        <th>Katering</th>
                        <th>Tanggal Kirim</th>
                        <th class="text-end">Total</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentOrders as $order)
                        <tr class="align-middle">
                            <td class="small fw-semibold">{{ $order->order_number }}</td>
                            <td class="small">{{ $order->merchantProfile->company_name ?? '-' }}</td>
                            <td class="small">{{ $order->delivery_date->translatedFormat('d M Y') }}</td>
                            <td class="small text-end">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                            <td>@include('partials.status-badge', ['status' => $order->status])</td>
                            <td class="text-end">
                                <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted small p-3">Belum ada riwayat order.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
