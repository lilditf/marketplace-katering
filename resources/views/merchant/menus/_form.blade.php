{{-- Form bersama create/edit menu.
     Variabel: $menu (Model|null — null saat create), $categories (array).
     enctype multipart/form-data diset oleh form pemanggil. --}}
@php
    // Checkbox tidak terkirim saat unchecked → old() null. Saat redisplay
    // karena error validasi, gunakan old(); selain itu ambil dari model/default.
    $availableChecked = $errors->any()
        ? old('is_available') === '1'
        : (bool) ($menu->is_available ?? true);
@endphp

<div class="row g-3">
    <div class="col-md-8">
        <label for="name" class="form-label">Nama Menu <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
               value="{{ old('name', $menu->name ?? '') }}" maxlength="150" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
        <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
            <option value="">Pilih kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat }}" @selected(old('category', $menu->category ?? '') === $cat)>{{ $cat }}</option>
            @endforeach
        </select>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                  name="description" rows="3" maxlength="2000"
                  placeholder="mis. Nasi putih, ayam bakar, tempe orek, sambal, lalapan, air mineral">{{ old('description', $menu->description ?? '') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="price" class="form-label">Harga per Porsi (Rp) <span class="text-danger">*</span></label>
        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price"
               value="{{ old('price', isset($menu) ? rtrim(rtrim(number_format($menu->price, 2, '.', ''), '0'), '.') : '') }}"
               min="1000" max="10000000" step="1" required>
        <div class="form-text">Minimal Rp1.000, maksimal Rp10.000.000.</div>
        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label for="photo" class="form-label">
            Foto Menu @if (!isset($menu))<span class="text-danger">*</span>@endif
        </label>
        @if (isset($menu) && $menu->photo_path)
            <div class="mb-2">
                <img src="{{ asset('storage/' . $menu->photo_path) }}" alt="Foto {{ $menu->name }}"
                     class="rounded border" style="width: 120px; height: 90px; object-fit: cover;">
                <div class="form-text">Foto saat ini. Unggah file baru untuk mengganti.</div>
            </div>
        @endif
        <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo"
               accept="image/jpeg,image/png,image/webp" @if (!isset($menu)) required @endif>
        <div class="form-text">Format jpg/png/webp, maksimal 2 MB.</div>
        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="is_available"
                   name="is_available" value="1" @checked($availableChecked)>
            <label class="form-check-label" for="is_available">Tersedia (ditampilkan ke customer)</label>
        </div>
    </div>
</div>
