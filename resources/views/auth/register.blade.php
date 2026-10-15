@extends('layouts.app')

@section('title', 'Daftar — Marketplace Katering')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-7">
            <div class="card shadow-sm mt-lg-3">
                <div class="card-body p-4">
                    <h1 class="h4 fw-bold text-center mb-1">Daftar Akun</h1>
                    <p class="text-muted text-center small mb-4">Pilih jenis akun, lalu lengkapi data Anda.</p>

                    <form method="POST" action="{{ route('register.store') }}" novalidate>
                        @csrf

                        {{-- Pilihan role: menentukan field tambahan di bawah --}}
                        <div class="btn-group w-100 mb-4" role="group" aria-label="Jenis pendaftaran">
                            <input type="radio" class="btn-check" name="role" id="roleCustomer"
                                   value="customer" autocomplete="off" @checked(old('role', $role) === 'customer')>
                            <label class="btn btn-outline-brand py-2" for="roleCustomer">
                                <i class="bi bi-building me-1"></i>Kantor (Customer)
                            </label>

                            <input type="radio" class="btn-check" name="role" id="roleMerchant"
                                   value="merchant" autocomplete="off" @checked(old('role', $role) === 'merchant')>
                            <label class="btn btn-outline-brand py-2" for="roleMerchant">
                                <i class="bi bi-shop me-1"></i>Katering (Merchant)
                            </label>
                        </div>
                        @error('role')
                            <div class="alert alert-danger py-2 small">{{ $message }}</div>
                        @enderror

                        {{-- Field khusus merchant --}}
                        <div class="role-section" data-role="merchant">
                            <div class="row g-3">
                                <div class="col-md-7">
                                    <label for="company_name" class="form-label">Nama Perusahaan Katering <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                           id="company_name" name="company_name" value="{{ old('company_name') }}"
                                           placeholder="mis. Katering Sedap Nusantara">
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-5">
                                    <label for="cuisine_type" class="form-label">Jenis Makanan <span class="text-danger">*</span></label>
                                    <select class="form-select @error('cuisine_type') is-invalid @enderror"
                                            id="cuisine_type" name="cuisine_type">
                                        <option value="">Pilih jenis</option>
                                        @foreach ($cuisineTypes as $type)
                                            <option value="{{ $type }}" @selected(old('cuisine_type') === $type)>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                    @error('cuisine_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <hr class="my-4">
                        </div>

                        {{-- Field khusus customer --}}
                        <div class="role-section" data-role="customer">
                            <div class="mb-3">
                                <label for="office_name" class="form-label">Nama Kantor / Instansi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('office_name') is-invalid @enderror"
                                       id="office_name" name="office_name" value="{{ old('office_name') }}"
                                       placeholder="mis. PT Maju Jaya Sentosa">
                                @error('office_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <hr class="my-4">
                        </div>

                        {{-- Field umum --}}
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Nama Lengkap (PIC) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Nomor Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone') }}" placeholder="mis. 081234567890" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="city" class="form-label">Kota <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror"
                                       id="city" name="city" value="{{ old('city') }}" placeholder="mis. Kota Jambi" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" minlength="8" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password_confirmation"
                                       name="password_confirmation" minlength="8" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-brand w-100 py-2 mt-4">
                            <i class="bi bi-person-plus me-1"></i>Daftar Sekarang
                        </button>
                    </form>

                    <p class="text-center small text-muted mt-3 mb-0">
                        Sudah punya akun? <a href="{{ route('login') }}" class="fw-semibold">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Tampilkan hanya field yang relevan dengan role terpilih.
        // Field seksi yang tersembunyi di-disable agar tidak dikirim & tidak
        // memblokir validasi browser; server tetap memvalidasi ulang (required_if).
        function syncRoleSections() {
            const checked = document.querySelector('input[name="role"]:checked');
            const role = checked ? checked.value : 'customer';
            document.querySelectorAll('.role-section').forEach(function (section) {
                const visible = section.dataset.role === role;
                section.style.display = visible ? '' : 'none';
                section.querySelectorAll('input, select').forEach(function (input) {
                    input.disabled = !visible;
                });
            });
        }
        document.querySelectorAll('input[name="role"]').forEach(function (radio) {
            radio.addEventListener('change', syncRoleSections);
        });
        syncRoleSections();
    </script>
@endpush
