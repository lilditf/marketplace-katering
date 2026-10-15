@extends('layouts.app')

@section('title', 'Halaman Tidak Ditemukan — Marketplace Katering')

@section('content')
    <div class="text-center py-5">
        <div class="text-warning mb-3"><i class="bi bi-compass" style="font-size: 4rem;"></i></div>
        <h1 class="h3 fw-bold">404 — Halaman Tidak Ditemukan</h1>
        <p class="text-muted">
            Halaman yang Anda cari tidak ada atau sudah dipindahkan.
        </p>
        <a href="{{ route('home') }}" class="btn btn-success"><i class="bi bi-house me-1"></i>Kembali ke Beranda</a>
    </div>
@endsection
