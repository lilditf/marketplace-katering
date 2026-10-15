@extends('layouts.app')

@section('title', 'Akses Ditolak — Marketplace Katering')

@section('content')
    <div class="text-center py-5">
        <div class="display-4 fw-bold text-brand font-display">403</div>
        <h1 class="h3 fw-bold">Akses Ditolak</h1>
        <p class="text-muted">
            Anda tidak memiliki izin untuk mengakses halaman ini.
            {{ $exception->getMessage() }}
        </p>
        <a href="{{ route('home') }}" class="btn btn-brand"><i class="bi bi-house me-1"></i>Kembali ke Beranda</a>
    </div>
@endsection
