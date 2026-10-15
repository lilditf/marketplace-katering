@extends('layouts.app')

@section('title', 'Pesan — ' . $catering->company_name)

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('customer.caterings.index') }}">Cari Katering</a></li>
            <li class="breadcrumb-item"><a href="{{ route('customer.caterings.show', $catering) }}">{{ $catering->company_name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">Pesan</li>
        </ol>
    </nav>

    <h1 class="h4 fw-bold mb-1"><i class="bi bi-cart-plus me-1 text-success"></i>Form Pemesanan</h1>
    <p class="text-muted small mb-3">
        Pesan dari <strong>{{ $catering->company_name }}</strong> · {{ $catering->city }}.
        Isi jumlah porsi pada menu yang diinginkan (0 = tidak dipesan).
    </p>

    <form method="POST" action="{{ route('customer.orders.store', $catering) }}" id="orderForm" novalidate>
        @csrf
        {{-- Container input hidden items[i][menu_id] / items[i][qty] diisi JS saat submit --}}
        <div id="itemsContainer"></div>

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white fw-semibold">Pilih Menu &amp; Jumlah Porsi</div>
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Menu</th>
                                    <th class="text-end">Harga/Porsi</th>
                                    <th class="text-center" style="width: 110px;">Porsi</th>
                                    <th class="text-end" style="width: 130px;">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($menus as $menu)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($menu->photo_path)
                                                    <img src="{{ asset('storage/' . $menu->photo_path) }}" alt=""
                                                         class="rounded" style="width: 44px; height: 34px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <div class="fw-semibold small">{{ $menu->name }}</div>
                                                    <div class="text-muted" style="font-size: .75rem;">
                                                        {{ $menu->category }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end small">Rp{{ number_format($menu->price, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <input type="number" class="form-control form-control-sm qty-input text-center"
                                                   min="0" max="10000" step="1" value="0"
                                                   data-menu-id="{{ $menu->id }}" data-price="{{ $menu->price }}"
                                                   aria-label="Jumlah porsi {{ $menu->name }}">
                                        </td>
                                        <td class="text-end small fw-semibold" id="sub-{{ $menu->id }}">—</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Tidak ada menu yang tersedia di katering ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm sticky-lg-top" style="top: 1rem;">
                    <div class="card-header bg-white fw-semibold">Ringkasan Pesanan</div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="text-muted" id="orderCount">0 menu dipilih</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fw-semibold">Estimasi Total</span>
                            <span class="fw-bold fs-5 text-success" id="orderTotal">Rp0</span>
                        </div>

                        <div class="mb-3">
                            <label for="delivery_date" class="form-label small text-muted">
                                Tanggal Pengiriman <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control @error('delivery_date') is-invalid @enderror"
                                   id="delivery_date" name="delivery_date" value="{{ old('delivery_date') }}" required>
                            <div class="form-text">Minimal besok; maksimal 6 bulan ke depan.</div>
                            @error('delivery_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label small text-muted">Catatan (opsional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                                      rows="2" maxlength="500"
                                      placeholder="mis. Diantar sebelum jam 11, tanpa sambal untuk 10 porsi">{{ old('notes') }}</textarea>
                            @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        @error('items')
                            <div class="alert alert-danger py-2 small">{{ $message }}</div>
                        @enderror
                        @if ($errors->hasAny(['items.0.menu_id', 'items.0.qty']))
                            <div class="alert alert-danger py-2 small">Data pesanan tidak valid — periksa kembali isian porsi.</div>
                        @endif

                        <button type="submit" class="btn btn-success w-100 py-2" {{ $menus->isEmpty() ? 'disabled' : '' }}>
                            <i class="bi bi-check2-circle me-1"></i>Buat Order &amp; Terbitkan Invoice
                        </button>
                        <p class="small text-muted mt-2 mb-0">
                            <i class="bi bi-info-circle me-1"></i>Total final dihitung server dari harga terbaru di database.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        // Hitung subtotal per baris + total secara live di browser.
        // Angka final TETAP dihitung ulang di server (anti price tampering).
        const qtyInputs = document.querySelectorAll('.qty-input');
        const totalEl = document.getElementById('orderTotal');
        const countEl = document.getElementById('orderCount');

        function formatRp(n) {
            return 'Rp' + Math.round(n).toLocaleString('id-ID');
        }

        function recalc() {
            let total = 0, count = 0;
            qtyInputs.forEach(function (inp) {
                const qty = parseInt(inp.value || '0', 10);
                const price = parseFloat(inp.dataset.price);
                const sub = qty > 0 ? qty * price : 0;
                const cell = document.getElementById('sub-' + inp.dataset.menuId);
                if (cell) cell.textContent = sub > 0 ? formatRp(sub) : '—';
                total += sub;
                if (qty > 0) count += 1;
            });
            totalEl.textContent = formatRp(total);
            countEl.textContent = count + ' menu dipilih';
        }

        qtyInputs.forEach(function (inp) {
            inp.addEventListener('input', recalc);
        });

        // Batas minimal tanggal = besok (waktu lokal browser).
        const d = new Date();
        d.setDate(d.getDate() + 1);
        const isoTomorrow = d.getFullYear() + '-' +
            String(d.getMonth() + 1).padStart(2, '0') + '-' +
            String(d.getDate()).padStart(2, '0');
        document.getElementById('delivery_date').min = isoTomorrow;

        // Saat submit: kumpulkan baris dengan qty > 0 menjadi items[i][menu_id|qty].
        document.getElementById('orderForm').addEventListener('submit', function (e) {
            const container = document.getElementById('itemsContainer');
            container.innerHTML = '';
            let i = 0;
            qtyInputs.forEach(function (inp) {
                const qty = parseInt(inp.value || '0', 10);
                if (qty > 0) {
                    container.insertAdjacentHTML('beforeend',
                        '<input type="hidden" name="items[' + i + '][menu_id]" value="' + inp.dataset.menuId + '">' +
                        '<input type="hidden" name="items[' + i + '][qty]" value="' + qty + '">');
                    i += 1;
                }
            });
            if (i === 0) {
                e.preventDefault();
                alert('Pilih minimal satu menu dengan jumlah porsi lebih dari 0.');
            }
        });

        recalc();
    </script>
@endpush
