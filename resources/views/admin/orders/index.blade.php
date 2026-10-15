@extends('layouts.portal')

@section('title', 'Semua Order — Marketplace Katering')

@section('content')
    <h1 class="h4 fw-bold mb-3"><i class="bi bi-inboxes me-1 text-brand"></i>Semua Order</h1>

    {{-- Toolbar data table: pencarian + filter status + baris per halaman --}}
    <form method="GET" class="dt-toolbar row g-2 align-items-center mb-3">
        <div class="col-sm-4 col-md-3">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                <input type="search" name="q" class="form-control" value="{{ $q }}"
                       placeholder="Cari no. order / katering / kantor..." aria-label="Cari">
            </div>
        </div>
        <div class="col-auto">
            <select name="status" class="form-select form-select-sm" aria-label="Filter status">
                <option value="">Semua status</option>
                @foreach ($statuses as $s)
                    <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <select name="per_page" class="form-select form-select-sm" aria-label="Baris per halaman">
                @foreach ([10, 15, 25, 50] as $size)
                    <option value="{{ $size }}" @selected((int) request('per_page', 15) === $size)>{{ $size }} / halaman</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-brand">Terapkan</button>
            @if ($status !== '' || $q !== '')
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            @endif
        </div>
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
        @if ($orders->total() > 0)
            <div class="card-footer bg-white">@include('partials.pagination-info', ['rows' => $orders])</div>
        @endif
    </div>
@endsection
