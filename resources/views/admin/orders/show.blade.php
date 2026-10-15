@extends('layouts.portal')

@section('title', 'Order ' . $order->order_number . ' — Marketplace Katering')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Semua Order</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h1 class="h4 fw-bold mb-0">{{ $order->order_number }}</h1>
        @include('partials.status-badge', ['status' => $order->status])
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Rincian Item</div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Menu</th>
                                <th class="text-end">Harga</th>
                                <th class="text-center">Porsi</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="small fw-semibold">{{ $item->menu->name ?? '(menu dihapus)' }}</td>
                                    <td class="small text-end">Rp{{ number_format($item->price_snapshot, 0, ',', '.') }}</td>
                                    <td class="small text-center">{{ $item->qty }}</td>
                                    <td class="small text-end fw-semibold">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <th colspan="3" class="text-end">TOTAL</th>
                                <th class="text-end text-brand">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            @if ($order->notes)
                <div class="card shadow-sm">
                    <div class="card-body small">
                        <span class="text-muted">Catatan kantor:</span> {{ $order->notes }}
                    </div>
                </div>
            @endif
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Pihak Terkait</div>
                <div class="card-body small">
                    <div class="mb-3">
                        <div class="text-muted">Katering</div>
                        <div class="fw-semibold">{{ $order->merchantProfile->company_name ?? '-' }}</div>
                        <div class="text-muted">{{ $order->merchantProfile->city ?? '-' }}</div>
                    </div>
                    <div class="mb-0">
                        <div class="text-muted">Kantor</div>
                        <div class="fw-semibold">{{ $order->customerProfile->office_name ?? '-' }}</div>
                        <div class="text-muted">{{ $order->customerProfile->city ?? '-' }}</div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-body small">
                    <div class="text-muted mb-1">Tanggal pengiriman</div>
                    <div class="fw-semibold mb-3">{{ $order->delivery_date->translatedFormat('l, d F Y') }}</div>
                    <div class="text-muted mb-1">Invoice</div>
                    @if ($order->invoice)
                        <div class="fw-semibold">{{ $order->invoice->invoice_number }}</div>
                        <div class="mt-1">@include('partials.status-badge', ['status' => $order->invoice->payment_status])</div>
                    @else
                        <div class="text-muted">-</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
