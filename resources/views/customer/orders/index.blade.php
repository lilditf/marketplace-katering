@extends('layouts.portal')

@section('title', 'Order Saya — Marketplace Katering')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h1 class="h4 fw-bold mb-0"><i class="bi bi-clock-history me-1 text-brand"></i>Order Saya</h1>
        <a href="{{ route('customer.caterings.index') }}" class="btn btn-brand btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Order Baru
        </a>
    </div>

    {{-- Filter status sederhana --}}
    <ul class="nav nav-pills mb-3 flex-wrap gap-1">
        <li class="nav-item">
            <a class="nav-link py-1 px-3 {{ $status === null ? 'active' : '' }}"
               href="{{ route('customer.orders.index') }}">Semua</a>
        </li>
        @foreach (\App\Models\Order::STATUSES as $st)
            <li class="nav-item">
                <a class="nav-link py-1 px-3 {{ $status === $st ? 'active' : '' }}"
                   href="{{ route('customer.orders.index', ['status' => $st]) }}">
                    {{ \App\Models\Order::STATUS_LABELS[$st] }}
                </a>
            </li>
        @endforeach
    </ul>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Order</th>
                        <th>Katering</th>
                        <th>Tanggal Kirim</th>
                        <th class="text-center">Item</th>
                        <th class="text-end">Total</th>
                        <th>Status</th>
                        <th>Invoice</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr class="align-middle">
                            <td class="small fw-semibold">{{ $order->order_number }}</td>
                            <td class="small">{{ $order->merchantProfile->company_name ?? '-' }}</td>
                            <td class="small">{{ $order->delivery_date->translatedFormat('d M Y') }}</td>
                            <td class="text-center small">{{ $order->items_count }}</td>
                            <td class="text-end small fw-semibold">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                            <td>@include('partials.status-badge', ['status' => $order->status])</td>
                            <td>
                                @if ($order->invoice)
                                    <a href="{{ route('customer.invoices.show', $order->invoice) }}"
                                       class="small">{{ $order->invoice->invoice_number }}</a>
                                    <div class="mt-1">@include('partials.status-badge', ['status' => $order->invoice->payment_status])</div>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('customer.orders.show', $order) }}" class="btn btn-sm btn-outline-brand">
                                    Detail <i class="bi bi-chevron-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-basket2 d-block mb-2" style="font-size: 2rem;"></i>
                                Belum ada order. <a href="{{ route('customer.caterings.index') }}">Cari katering</a> untuk mulai memesan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($orders->hasPages())
            <div class="card-footer bg-white">{{ $orders->links() }}</div>
        @endif
    </div>
@endsection
