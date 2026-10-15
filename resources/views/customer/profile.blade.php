@extends('layouts.portal')

@section('title', 'Profil Kantor — Marketplace Katering')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h4 fw-bold mb-3"><i class="bi bi-building me-1 text-brand"></i>Profil Kantor</h1>

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('customer.profile.update') }}" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-7">
                                <label for="office_name" class="form-label">Nama Kantor / Instansi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('office_name') is-invalid @enderror"
                                       id="office_name" name="office_name"
                                       value="{{ old('office_name', $customer->office_name) }}" required>
                                @error('office_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-5">
                                <label for="city" class="form-label">Kota <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror"
                                       id="city" name="city" value="{{ old('city', $customer->city) }}" required>
                                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="address" class="form-label">Alamat</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror"
                                       id="address" name="address" value="{{ old('address', $customer->address) }}"
                                       placeholder="Alamat lengkap kantor (dipakai sebagai tujuan pengiriman)">
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="pic_name" class="form-label">Nama PIC</label>
                                <input type="text" class="form-control @error('pic_name') is-invalid @enderror"
                                       id="pic_name" name="pic_name" value="{{ old('pic_name', $customer->pic_name) }}">
                                @error('pic_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap Akun <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
