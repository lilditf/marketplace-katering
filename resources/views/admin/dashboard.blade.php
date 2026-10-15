@extends('layouts.portal')

@section('title', 'Dashboard Admin · Marketplace Katering')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <h2 class="h4 fw-bold mb-1">Ringkasan Marketplace</h2>
            <p class="text-muted small mb-0">Pantauan global: katering, kantor, dan semua transaksi.</p>
        </div>
        <a href="{{ route('admin.cuisines.index') }}" class="btn btn-brand">
            <i class="bi bi-tags me-1"></i>Kelola Jenis Makanan
        </a>
    </div>

    {{-- Kartu statistik dengan icon tile berwarna --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body stat-card">
                    <div class="stat-tile"><i class="bi bi-shop"></i></div>
                    <div>
                        <div class="text-muted small">Katering Aktif</div>
                        <div class="fs-3 fw-bold lh-1">{{ $activeMerchantCount }}<span class="fs-6 text-muted fw-normal"> / {{ $merchantCount }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body stat-card">
                    <div class="stat-tile stat-tile--deep"><i class="bi bi-building"></i></div>
                    <div>
                        <div class="text-muted small">Kantor Terdaftar</div>
                        <div class="fs-3 fw-bold lh-1">{{ $customerCount }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body stat-card">
                    <div class="stat-tile stat-tile--amber"><i class="bi bi-receipt"></i></div>
                    <div>
                        <div class="text-muted small">Total Order</div>
                        <div class="fs-3 fw-bold lh-1">{{ $orderCount }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card shadow-sm h-100">
                <div class="card-body stat-card">
                    <div class="stat-tile stat-tile--soft"><i class="bi bi-cash-stack"></i></div>
                    <div>
                        <div class="text-muted small">Omzet Marketplace</div>
                        <div class="fs-6 fw-bold">Rp{{ number_format($omzet, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- Chart batang: order 7 hari terakhir (CSS murni, tanpa library JS) --}}
        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-bar-chart me-1 text-brand"></i>Order 7 Hari Terakhir
                </div>
                <div class="card-body">
                    <div class="chart-bars">
                        @foreach ($chartDays as $day)
                            <div class="chart-bar {{ $loop->last ? 'chart-bar-today' : '' }}"
                                 title="{{ $day['label'] }}: {{ $day['total'] }} order">
                                <span class="chart-bar-value">{{ $day['total'] ?: '' }}</span>
                                <div class="chart-bar-fill" style="height: {{ (int) round($day['total'] / $chartMax * 100) }}%"></div>
                                <span class="chart-bar-label">{{ $day['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Distribusi status order --}}
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-pie-chart me-1 text-brand"></i>Order per Status
                </div>
                <div class="card-body">
                    @php($statusTotal = max(1, $statusCounts->sum()))
                    @foreach ($statuses as $s)
                        @php($count = (int) ($statusCounts[$s] ?? 0))
                        <div class="status-row">
                            <span class="status-name">{{ ucfirst($s) }}</span>
                            <span class="status-track">
                                <span class="status-fill d-block" style="width: {{ (int) round($count / $statusTotal * 100) }}%"></span>
                            </span>
                            <span class="status-count">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Data table order terbaru: cari + per halaman + pagination --}}
    <h2 class="h5 fw-bold mb-3"><i class="bi bi-clock-history me-1 text-brand"></i>Order Terbaru</h2>
    @include('partials.datatable-toolbar')

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
                    @forelse ($latestOrders as $order)
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
                                {{ $q !== '' ? 'Tidak ada order yang cocok dengan pencarian. Coba kata kunci lain.' : 'Belum ada order.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($latestOrders->total() > 0)
            <div class="card-footer bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                @include('partials.pagination-info', ['rows' => $latestOrders])
                <a href="{{ route('admin.orders.index') }}" class="small">Lihat semua order</a>
            </div>
        @endif
    </div>
@endsection
