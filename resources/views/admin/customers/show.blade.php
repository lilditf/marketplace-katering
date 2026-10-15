@extends('layouts.portal')

@section('title', $customer->office_name . ' — Marketplace Katering')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Kelola Kantor</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $customer->office_name }}</li>
        </ol>
    </nav>

    <h1 class="h4 fw-bold mb-3">{{ $customer->office_name }}</h1>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Profil</div>
                <div class="card-body small">
                    <dl class="row mb-0">
                        <dt class="col-4 text-muted">PIC</dt>
                        <dd class="col-8">{{ $customer->pic_name }}</dd>
                        <dt class="col-4 text-muted">Email</dt>
                        <dd class="col-8">{{ $customer->user->email ?? '-' }}</dd>
                        <dt class="col-4 text-muted">Telepon</dt>
                        <dd class="col-8">{{ $customer->user->phone ?? '-' }}</dd>
                        <dt class="col-4 text-muted">Kota</dt>
                        <dd class="col-8">{{ $customer->city }}</dd>
                        <dt class="col-4 text-muted">Alamat</dt>
                        <dd class="col-8 mb-0">{{ $customer->address ?? '-' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Riwayat Order ({{ $customer->orders->count() }})</div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Order</th>
                                <th>Katering</th>
                                <th>Kirim</th>
                                <th class="text-end">Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customer->orders as $order)
                                <tr class="align-middle" style="cursor: pointer;"
                                    onclick="window.location='{{ route('admin.orders.show', $order) }}'">
                                    <td class="small fw-semibold">{{ $order->order_number }}</td>
                                    <td class="small">{{ $order->merchantProfile->company_name ?? '-' }}</td>
                                    <td class="small">{{ $order->delivery_date->translatedFormat('d M Y') }}</td>
                                    <td class="small text-end">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                    <td>@include('partials.status-badge', ['status' => $order->status])</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-muted small p-3">Belum ada order.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
