@extends('layouts.app')

@section('title', 'Order ' . $order->order_number . ' — Marketplace Katering')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('customer.orders.index') }}">Order Saya</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h1 class="h4 fw-bold mb-0">Order {{ $order->order_number }}</h1>
        <div class="d-flex align-items-center gap-2">
            @include('partials.status-badge', ['status' => $order->status])
            @if ($order->invoice)
                @include('partials.status-badge', ['status' => $order->invoice->payment_status])
            @endif
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold"><i class="bi bi-shop me-1"></i>Katering</div>
                <div class="card-body small">
                    <div class="fw-semibold mb-1">
                        <a href="{{ route('customer.caterings.show', $order->merchant_profile_id) }}" class="text-decoration-none">
                            {{ $order->merchantProfile->company_name ?? '-' }}
                        </a>
                    </div>
                    <div class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $order->merchantProfile->city ?? '-' }}</div>
                    <div class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $order->merchantProfile->user->phone ?? '-' }}</div>
                </div>
            </div>

            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold"><i class="bi bi-truck me-1"></i>Pengiriman</div>
                <div class="card-body small">
                    <div><span class="text-muted">Tanggal:</span>
                        <span class="fw-semibold">{{ $order->delivery_date->translatedFormat('l, d F Y') }}</span></div>
                    <div class="mt-1"><span class="text-muted">Catatan:</span> {{ $order->notes ?: '—' }}</div>
                    <div class="mt-1 text-muted">Dibuat: {{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB</div>
                </div>
            </div>

            @if ($order->invoice)
                <div class="card shadow-sm">
                    <div class="card-header bg-white fw-semibold"><i class="bi bi-receipt me-1"></i>Invoice</div>
                    <div class="card-body small">
                        <div class="fw-semibold">{{ $order->invoice->invoice_number }}</div>
                        <div class="text-muted">Terbit: {{ $order->invoice->issued_at->translatedFormat('d M Y') }}</div>
                        <div class="mt-2">
                            @include('partials.status-badge', ['status' => $order->invoice->payment_status])
                        </div>
                        <a href="{{ route('customer.invoices.show', $order->invoice) }}"
                           class="btn btn-sm btn-outline-success w-100 mt-3">Lihat Invoice</a>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold"><i class="bi bi-cart3 me-1"></i>Rincian Pesanan</div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Menu</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-center">Porsi</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if ($item->menu && $item->menu->photo_path)
                                                <img src="{{ asset('storage/' . $item->menu->photo_path) }}" alt=""
                                                     class="rounded" style="width: 44px; height: 34px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <div class="fw-semibold small">{{ $item->menu->name ?? '(menu dihapus)' }}</div>
                                                <div class="text-muted" style="font-size: .75rem;">{{ $item->menu->category ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end small">Rp{{ number_format($item->price_snapshot, 0, ',', '.') }}</td>
                                    <td class="text-center small">{{ $item->qty }}</td>
                                    <td class="text-end small fw-semibold">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th class="text-end fs-6">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
