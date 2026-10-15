{{-- Invoice bersama: diakses merchant (viewer=merchant) dan customer (viewer=customer).
     Tombol aksi berbeda per viewer; bagian ini juga print-friendly (lihat @media print). --}}
@extends('layouts.portal')

@section('title', 'Invoice ' . $invoice->invoice_number . ' · Marketplace Katering')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    {{-- Kop invoice --}}
                    <div class="d-flex flex-wrap justify-content-between align-items-start mb-4 gap-2">
                        <div>
                            <h1 class="h4 fw-bold mb-0 text-brand">INVOICE</h1>
                            <div class="fw-semibold">{{ $invoice->invoice_number }}</div>
                            <div class="small text-muted">Terbit: {{ $invoice->issued_at->translatedFormat('d F Y') }}</div>
                        </div>
                        <div class="text-end">
                            @include('partials.status-badge', ['status' => $invoice->payment_status])
                            @if ($invoice->isPaid())
                                <div class="small text-muted mt-1">
                                    Dibayar: {{ $invoice->paid_at->translatedFormat('d M Y, H:i') }} WIB
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Dari & Kepada --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="small text-muted mb-1">Dari (Katering)</div>
                                <div class="fw-semibold">{{ $invoice->order->merchantProfile->company_name ?? '-' }}</div>
                                <div class="small text-muted">
                                    {{ $invoice->order->merchantProfile->city ?? '-' }}@if ($invoice->order->merchantProfile->address), {{ $invoice->order->merchantProfile->address }}@endif
                                </div>
                                <div class="small text-muted">
                                    PIC: {{ $invoice->order->merchantProfile->user->name ?? '-' }} ·
                                    {{ $invoice->order->merchantProfile->user->phone ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="small text-muted mb-1">Kepada (Kantor)</div>
                                <div class="fw-semibold">{{ $invoice->order->customerProfile->office_name ?? '-' }}</div>
                                <div class="small text-muted">
                                    {{ $invoice->order->customerProfile->city ?? '-' }}@if ($invoice->order->customerProfile->address), {{ $invoice->order->customerProfile->address }}@endif
                                </div>
                                <div class="small text-muted">
                                    PIC: {{ $invoice->order->customerProfile->user->name ?? '-' }} ·
                                    {{ $invoice->order->customerProfile->user->phone ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Referensi order --}}
                    <div class="small text-muted mb-3">
                        Order:
                        <a href="{{ $viewer === 'merchant'
                            ? route('merchant.orders.show', $invoice->order)
                            : route('customer.orders.show', $invoice->order) }}"
                           class="no-print">{{ $invoice->order->order_number }}</a>
                        <span class="d-inline d-print-none">·</span>
                        <span class="d-none d-print-inline">·</span>
                        Pengiriman: {{ $invoice->order->delivery_date->translatedFormat('l, d F Y') }}
                        · Status order: {{ $invoice->order->statusLabel() }}
                    </div>

                    {{-- Rincian item --}}
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Menu</th>
                                    <th class="text-end">Harga Satuan</th>
                                    <th class="text-center">Porsi</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->order->items as $i => $item)
                                    <tr>
                                        <td class="small text-muted">{{ $i + 1 }}</td>
                                        <td>
                                            <div class="fw-semibold small">{{ $item->menu->name ?? '(menu dihapus)' }}</div>
                                            <div class="text-muted" style="font-size: .75rem;">{{ $item->menu->category ?? '' }}</div>
                                        </td>
                                        <td class="text-end small">Rp{{ number_format($item->price_snapshot, 0, ',', '.') }}</td>
                                        <td class="text-center small">{{ $item->qty }}</td>
                                        <td class="text-end small fw-semibold">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <th colspan="4" class="text-end">TOTAL</th>
                                    <th class="text-end fs-5 text-brand">Rp{{ number_format($invoice->total, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    {{-- Aksi (tidak ikut tercetak) --}}
                    <div class="d-flex flex-wrap justify-content-between gap-2 mt-3 no-print">
                        <div class="d-flex flex-wrap gap-2">
                            @if ($viewer === 'merchant')
                                @unless ($invoice->isPaid())
                                    <form method="POST" action="{{ route('merchant.invoices.markPaid', $invoice) }}"
                                          onsubmit="return confirm('Tandai invoice {{ $invoice->invoice_number }} LUNAS?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-brand">
                                            <i class="bi bi-check2-circle me-1"></i>Tandai Lunas
                                        </button>
                                    </form>
                                @endunless
                                <a href="{{ route('merchant.orders.show', $invoice->order) }}"
                                   class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Order
                                </a>
                            @else
                                <a href="{{ route('customer.orders.show', $invoice->order) }}"
                                   class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Order
                                </a>
                            @endif
                        </div>
                        <button type="button" onclick="window.print()" class="btn btn-outline-dark btn-sm">
                            <i class="bi bi-printer me-1"></i>Cetak / Simpan PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
