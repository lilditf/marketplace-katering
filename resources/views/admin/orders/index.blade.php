@extends('layouts.portal')

@section('title', 'Semua Order — Marketplace Katering')

@section('content')
    <h1 class="h4 fw-bold mb-3"><i class="bi bi-inboxes me-1 text-brand"></i>Semua Order</h1>

    {{-- Filter status --}}
    <form method="GET" class="row g-2 mb-3">
        <div class="col-auto">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua status</option>
                @foreach ($statuses as $s)
                    <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        @if ($status !== '')
            <div class="col-auto">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        @endif
    </form>

    <div class="card shadow-sm">
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
                    @forelse ($orders as $order)
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
                            <td colspan="6" class="text-muted small p-3">
                                {{ $status !== '' ? 'Tidak ada order dengan status ini.' : 'Belum ada order.' }}
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
