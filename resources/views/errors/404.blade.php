@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan · Marketplace Katering')

@section('content')
    <div class="text-center py-5">
        <div class="display-4 fw-bold text-brand font-display">404</div>
        <h1 class="h3 fw-bold">Halaman Tidak Ditemukan</h1>
        <p class="text-muted">
            Halaman yang Anda cari tidak ada atau sudah dipindahkan.
        </p>
        <a href="{{ route('home') }}" class="btn btn-brand"><i class="bi bi-house me-1"></i>Kembali ke Beranda</a>
    </div>
@endsection
