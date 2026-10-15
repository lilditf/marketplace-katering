@extends('layouts.app')

@section('title', 'Terjadi Kesalahan — Marketplace Katering')

@section('content')
    <div class="text-center py-5">
        <div class="text-danger mb-3"><i class="bi bi-tools" style="font-size: 4rem;"></i></div>
        <h1 class="h3 fw-bold">500 — Terjadi Kesalahan pada Server</h1>
        <p class="text-muted">
            Maaf, terjadi kesalahan yang tidak terduga. Tim kami sudah mencatat kejadian ini.
            Silakan coba beberapa saat lagi.
        </p>
        <a href="{{ route('home') }}" class="btn btn-success"><i class="bi bi-house me-1"></i>Kembali ke Beranda</a>
    </div>
@endsection
