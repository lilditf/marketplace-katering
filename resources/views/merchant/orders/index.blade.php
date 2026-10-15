@extends('layouts.app')

@section('title', 'Order Masuk — Marketplace Katering')

@section('content')
    <h1 class="h4 fw-bold mb-3"><i class="bi bi-inboxes me-1 text-success"></i>Order Masuk</h1>

    {{-- Tab filter status dengan jumlah order per status --}}
    <ul class="nav nav-pills mb-3 flex-wrap gap-1">
        <li class="nav-item">
            <a class="nav-link py-1 px-3 {{ $status === null ? 'active' : '' }}"
               href="{{ route('merchant.orders.index') }}">
                Semua <span class="badge bg-light text-dark ms-1">{{ $countByStatus->sum() }}</span>
            </a>
        </li>
        @foreach (\App\Models\Order::STATUSES as $st)
            @php $count = $countByStatus[$st] ?? 0; @endphp
            <li class="nav-item">
                <a class="nav-link py-1 px-3 {{ $status === $st ? 'active' : '' }}"
                   href="{{ route('merchant.orders.index', ['status' => $st]) }}">
                    {{ \App\Models\Order::STATUS_LABELS[$st] }}
                    <span class="badge {{ $status === $st ? 'bg-light text-dark' : 'bg-light text-dark' }} ms-1">{{ $count }}</span>
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
                        <th>Kantor Pemesan</th>
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
                            <td>
                                <div class="small fw-semibold">{{ $order->customerProfile->office_name ?? '-' }}</div>
                                <div class="small text-muted">{{ $order->customerProfile->user->name ?? '' }}</div>
                            </td>
                            <td class="small">{{ $order->delivery_date->translatedFormat('d M Y') }}</td>
                            <td class="text-center small">{{ $order->items_count }}</td>
                            <td class="text-end small fw-semibold">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                            <td>@include('partials.status-badge', ['status' => $order->status])</td>
                            <td>
                                @if ($order->invoice)
                                    <a href="{{ route('merchant.invoices.show', $order->invoice) }}"
                                       class="small">{{ $order->invoice->invoice_number }}</a>
                                    <div class="mt-1">@include('partials.status-badge', ['status' => $order->invoice->payment_status])</div>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('merchant.orders.show', $order) }}" class="btn btn-sm btn-outline-success">
                                    Detail <i class="bi bi-chevron-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox d-block mb-2" style="font-size: 2rem;"></i>
                                Belum ada order{{ $status ? ' dengan status ini' : '' }}.
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
