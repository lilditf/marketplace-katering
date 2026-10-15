@extends('layouts.portal')

@section('title', 'Order ' . $order->order_number . ' · Marketplace Katering')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('merchant.orders.index') }}">Order Masuk</a></li>
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
            {{-- Info pemesan --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold"><i class="bi bi-building me-1"></i>Kantor Pemesan</div>
                <div class="card-body small">
                    <div class="fw-semibold mb-1">{{ $order->customerProfile->office_name ?? '-' }}</div>
                    <div class="text-muted">PIC: {{ $order->customerProfile->user->name ?? '-' }}</div>
                    <div class="text-muted">
                        <i class="bi bi-telephone me-1"></i>{{ $order->customerProfile->user->phone ?? '-' }}
                    </div>
                    <div class="text-muted">
                        <i class="bi bi-envelope me-1"></i>{{ $order->customerProfile->user->email ?? '-' }}
                    </div>
                    <div class="text-muted">
                        <i class="bi bi-geo-alt me-1"></i>{{ $order->customerProfile->city ?? '-' }}
                    </div>
                </div>
            </div>

            {{-- Pengiriman --}}
            <div class="card shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold"><i class="bi bi-truck me-1"></i>Pengiriman</div>
                <div class="card-body small">
                    <div><span class="text-muted">Tanggal:</span>
                        <span class="fw-semibold">{{ $order->delivery_date->translatedFormat('l, d F Y') }}</span></div>
                    <div class="mt-1"><span class="text-muted">Catatan:</span>
                        {{ $order->notes ?: '-' }}</div>
                    <div class="mt-1 text-muted">Dipesan: {{ $order->created_at->translatedFormat('d M Y, H:i') }} WIB</div>
                </div>
            </div>

            {{-- Ubah status (hanya transisi yang diizinkan) --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold"><i class="bi bi-arrow-repeat me-1"></i>Ubah Status</div>
                <div class="card-body">
                    @if (count($allowedNext) > 0)
                        <form method="POST" action="{{ route('merchant.orders.updateStatus', $order) }}">
                            @csrf
                            @method('PATCH')
                            <label for="status" class="form-label small text-muted">Status berikutnya</label>
                            <select class="form-select mb-2" id="status" name="status" required>
                                <option value="">Pilih status</option>
                                @foreach ($allowedNext as $next)
                                    <option value="{{ $next }}">{{ \App\Models\Order::STATUS_LABELS[$next] }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-brand btn-sm w-100">Perbarui Status</button>
                        </form>
                    @else
                        <p class="small text-muted mb-0">
                            <i class="bi bi-lock me-1"></i>Order berstatus <strong>{{ $order->statusLabel() }}</strong>
                            bersifat final; tidak ada perubahan status lagi.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            {{-- Rincian pesanan --}}
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-cart3 me-1"></i>Rincian Pesanan</span>
                    @if ($order->invoice)
                        <a href="{{ route('merchant.invoices.show', $order->invoice) }}" class="btn btn-sm btn-outline-secondary no-print">
                            <i class="bi bi-receipt me-1"></i>Lihat Invoice {{ $order->invoice->invoice_number }}
                        </a>
                    @endif
                </div>
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
