@extends('layouts.portal')

@section('title', 'Profil Katering · Marketplace Katering')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h4 fw-bold mb-3"><i class="bi bi-shop me-1 text-brand"></i>Profil Katering</h1>

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('merchant.profile.update') }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-7">
                                <label for="company_name" class="form-label">Nama Perusahaan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                       id="company_name" name="company_name"
                                       value="{{ old('company_name', $merchant->company_name) }}" required>
                                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-5">
                                <label for="cuisine_type" class="form-label">Jenis Makanan <span class="text-danger">*</span></label>
                                <select class="form-select @error('cuisine_type') is-invalid @enderror"
                                        id="cuisine_type" name="cuisine_type" required>
                                    @foreach ($cuisineTypes as $type)
                                        <option value="{{ $type }}" @selected(old('cuisine_type', $merchant->cuisine_type) === $type)>{{ $type }}</option>
                                    @endforeach
                                </select>
                                @error('cuisine_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="city" class="form-label">Kota <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror"
                                       id="city" name="city" value="{{ old('city', $merchant->city) }}" required>
                                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="address" class="form-label">Alamat</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror"
                                       id="address" name="address" value="{{ old('address', $merchant->address) }}">
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                          name="description" rows="4"
                                          placeholder="Ceritakan katering Anda: spesialisasi, kapasitas porsi, jam operasional, dll.">{{ old('description', $merchant->description) }}</textarea>
                                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <hr class="my-2">

                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Penanggung Jawab <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="is_active"
                                           name="is_active" value="1"
                                           @checked(old('is_active', $merchant->is_active ? '1' : null))>
                                    <label class="form-check-label" for="is_active">
                                        Profil aktif (tampil di pencarian customer)
                                    </label>
                                </div>
                                <div class="form-text">
                                    Nonaktifkan bila katering sedang tidak menerima pesanan. Data &amp; riwayat tetap tersimpan.
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-brand px-4">
                                <i class="bi bi-save me-1"></i>Simpan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
