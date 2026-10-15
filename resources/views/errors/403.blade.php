@extends('layouts.app')

@section('title', 'Akses Ditolak — Marketplace Katering')

@section('content')
    <div class="text-center py-5">
        <div class="text-danger mb-3"><i class="bi bi-shield-lock" style="font-size: 4rem;"></i></div>
        <h1 class="h3 fw-bold">403 — Akses Ditolak</h1>
        <p class="text-muted">
            Anda tidak memiliki izin untuk mengakses halaman ini.
            {{ $exception->getMessage() }}
        </p>
        <a href="{{ route('home') }}" class="btn btn-success"><i class="bi bi-house me-1"></i>Kembali ke Beranda</a>
    </div>
@endsection
