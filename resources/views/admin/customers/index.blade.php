@extends('layouts.portal')

@section('title', 'Kelola Kantor — Marketplace Katering')

@section('content')
    <h1 class="h4 fw-bold mb-3"><i class="bi bi-building me-1 text-brand"></i>Kelola Kantor (Customer)</h1>

    @include('partials.datatable-toolbar')

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kantor</th>
                        <th>PIC</th>
                        <th>Kota</th>
                        <th class="text-center">Order</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr class="align-middle">
                            <td>
                                <div class="fw-semibold small">{{ $customer->office_name }}</div>
                                <div class="text-muted" style="font-size: .75rem;">{{ $customer->user->email ?? '-' }}</div>
                            </td>
                            <td class="small">{{ $customer->pic_name }}</td>
                            <td class="small">{{ $customer->city }}</td>
                            <td class="small text-center">{{ $customer->orders_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-outline-brand">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted small p-3">Belum ada kantor terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($customers->total() > 0)
            <div class="card-footer bg-white">@include('partials.pagination-info', ['rows' => $customers])</div>
        @endif
    </div>
@endsection
