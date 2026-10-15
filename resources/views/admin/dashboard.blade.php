@extends('layouts.portal')

@section('title', 'Dashboard Admin — Marketplace Katering')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h1 class="h4 fw-bold mb-1">Dashboard Admin</h1>
            <p class="text-muted small mb-0">Statistik global seluruh marketplace.</p>
        </div>
        <a href="{{ route('admin.cuisines.index') }}" class="btn btn-brand">
            <i class="bi bi-tags me-1"></i>Kelola Jenis Makanan
        </a>
    </div>

    {{-- Kartu statistik global --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small"><i class="bi bi-shop me-1"></i>Katering Aktif</div>
                    <div class="fs-3 fw-bold">{{ $activeMerchantCount }}<span class="fs-6 text-muted fw-normal"> / {{ $merchantCount }}</span></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small"><i class="bi bi-building me-1"></i>Kantor Terdaftar</div>
                    <div class="fs-3 fw-bold">{{ $customerCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small"><i class="bi bi-receipt me-1"></i>Total Order</div>
                    <div class="fs-3 fw-bold">{{ $orderCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small"><i class="bi bi-cash-stack me-1"></i>Omzet Marketplace</div>
                    <div class="fs-5 fw-bold mt-2">Rp{{ number_format($omzet, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Order terbaru lintas merchant --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
            <span><i class="bi bi-clock-history me-1"></i>Order Terbaru (Semua Katering)</span>
            <a href="{{ route('admin.orders.index') }}" class="small">Lihat semua</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Order</th>
                        <th>Katering</th>
                        <th>Kantor</th>
                        <th>Kirim</th>
                        <th class="text-end">Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestOrders as $order)
                        <tr class="align-middle" style="cursor: pointer;"
                            onclick="window.location='{{ route('admin.orders.show', $order) }}'">
                            <td class="small fw-semibold">{{ $order->order_number }}</td>
                            <td class="small">{{ $order->merchantProfile->company_name ?? '-' }}</td>
                            <td class="small">{{ $order->customerProfile->office_name ?? '-' }}</td>
                            <td class="small">{{ $order->delivery_date->translatedFormat('d M Y') }}</td>
                            <td class="small text-end">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                            <td>@include('partials.status-badge', ['status' => $order->status])</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted small p-3">Belum ada order.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
