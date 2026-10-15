@extends('layouts.app')

@section('title', 'Masuk — Marketplace Katering')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card shadow-sm mt-lg-4">
                <div class="card-body p-4">
                    <h1 class="h4 fw-bold text-center mb-1">Masuk</h1>
                    <p class="text-muted text-center small mb-4">Portal katering &amp; kantor — Marketplace Katering</p>

                    <form method="POST" action="{{ route('login.store') }}" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember"
                                   value="1" @checked(old('remember'))>
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>

                        <button type="submit" class="btn btn-brand w-100 py-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                        </button>
                    </form>

                    <p class="text-center small text-muted mt-3 mb-0">
                        Belum punya akun? <a href="{{ route('register') }}" class="fw-semibold">Daftar sekarang</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
