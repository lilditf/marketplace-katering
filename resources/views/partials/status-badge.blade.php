{{-- Badge status order / pembayaran. Pemakaian:
     @include('partials.status-badge', ['status' => $order->status])
     atau ['status' => $invoice->payment_status] --}}
@php
    $badgeMap = [
        'pending' => ['Menunggu Konfirmasi', 'warning text-dark'],
        'dikonfirmasi' => ['Dikonfirmasi', 'info text-dark'],
        'dikirim' => ['Dikirim', 'primary'],
        'selesai' => ['Selesai', 'success'],
        'dibatalkan' => ['Dibatalkan', 'danger'],
        'belum_lunas' => ['Belum Lunas', 'warning text-dark'],
        'lunas' => ['Lunas', 'success'],
    ];
    [$badgeLabel, $badgeColor] = $badgeMap[$status] ?? [ucfirst($status), 'secondary'];
@endphp
<span class="badge bg-{{ $badgeColor }}">{{ $badgeLabel }}</span>
