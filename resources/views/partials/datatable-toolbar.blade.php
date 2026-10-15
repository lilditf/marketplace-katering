{{-- Toolbar data table standar admin: pencarian + jumlah baris per halaman.
     Variabel: $q (string|null keyword aktif). Query string lain (mis. status)
     dipertahankan lewat input hidden. --}}
<form method="GET" class="dt-toolbar row g-2 align-items-center mb-3">
    @foreach (request()->except(['q', 'per_page', 'page']) as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach
    <div class="col-sm-5 col-md-4">
        <div class="input-group input-group-sm">
            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
            <input type="search" name="q" class="form-control" value="{{ $q ?? '' }}"
                   placeholder="Cari..." aria-label="Cari">
        </div>
    </div>
    <div class="col-auto">
        <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()"
                aria-label="Baris per halaman">
            @foreach ([10, 15, 25, 50] as $size)
                <option value="{{ $size }}" @selected((int) request('per_page', 15) === $size)>{{ $size }} / halaman</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-sm btn-brand">Terapkan</button>
        @if (request()->hasAny(['q', 'status']) && (request('q') || request('status')))
            <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary">Reset</a>
        @endif
    </div>
</form>
