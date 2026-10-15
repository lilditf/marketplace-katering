@extends('layouts.app')

@section('title', 'Marketplace Katering — Pesan Makan Siang Kantor dari Katering Terpercaya')

@section('content')
    {{-- Hero --}}
    <div class="hero p-4 p-lg-5 mb-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <h1 class="h2 fw-bold mb-3">Makan siang kantor beres,<br>tanpa drama setiap hari.</h1>
                <p class="lead text-muted mb-4">
                    Marketplace Katering menghubungkan perusahaan katering dengan kantor yang
                    karyawannya butuh makan siang. Pilih menu, tentukan jumlah porsi dan tanggal
                    kirim, lalu invoice terbit otomatis untuk kedua pihak.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('register', ['role' => 'customer']) }}" class="btn btn-brand btn-lg px-4">
                        <i class="bi bi-building me-1"></i>Daftar sebagai Kantor
                    </a>
                    <a href="{{ route('register', ['role' => 'merchant']) }}" class="btn btn-outline-brand btn-lg px-4">
                        <i class="bi bi-shop me-1"></i>Daftarkan Katering
                    </a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="row g-3 text-center">
                    <div class="col-4">
                        <div class="stat">
                            <div class="stat-value">{{ $merchantCount }}</div>
                            <div class="small text-muted">Katering</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat">
                            <div class="stat-value">{{ $menuCount }}</div>
                            <div class="small text-muted">Menu</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat">
                            <div class="stat-value">{{ $cities->count() }}</div>
                            <div class="small text-muted">Kota</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cara kerja: stepper proses, bukan kartu generik --}}
    <h2 class="h4 fw-bold text-center mb-4">Cara Kerja</h2>
    <div class="stepper mb-5">
        <div class="step">
            <div class="step-number">1</div>
            <h3 class="h6 fw-bold">Daftar &amp; Login</h3>
            <p class="small text-muted mb-0">
                Kantor mendaftar sebagai customer, perusahaan katering sebagai merchant.
                Lengkapi profil, lalu masuk ke portal masing-masing.
            </p>
        </div>
        <div class="step">
            <div class="step-number">2</div>
            <h3 class="h6 fw-bold">Cari &amp; Pesan</h3>
            <p class="small text-muted mb-0">
                Cari katering berdasarkan lokasi dan jenis makanan. Pilih menu, tentukan
                jumlah porsi dan tanggal pengiriman.
            </p>
        </div>
        <div class="step">
            <div class="step-number">3</div>
            <h3 class="h6 fw-bold">Invoice Otomatis</h3>
            <p class="small text-muted mb-0">
                Setiap pembelian menerbitkan invoice yang bisa diakses kantor maupun
                katering, lengkap dengan rincian menu dan total biaya.
            </p>
        </div>
    </div>

    {{-- Dua portal --}}
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card card-hover h-100">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold"><i class="bi bi-shop text-brand me-2"></i>Portal Katering (Merchant)</h2>
                    <ul class="small text-muted mb-3 ps-3">
                        <li>Kelola profil perusahaan katering.</li>
                        <li>Tambah, ubah, dan hapus menu lengkap dengan foto dan harga.</li>
                        <li>Pantau semua pesanan &amp; invoice yang masuk, perbarui status order.</li>
                    </ul>
                    <a href="{{ route('register', ['role' => 'merchant']) }}" class="btn btn-outline-brand btn-sm">
                        Daftarkan Katering <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-hover h-100">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold"><i class="bi bi-building text-brand me-2"></i>Portal Kantor (Customer)</h2>
                    <ul class="small text-muted mb-3 ps-3">
                        <li>Cari katering berdasarkan kota dan jenis makanan.</li>
                        <li>Pesan menu dengan jumlah porsi dan tanggal pengiriman.</li>
                        <li>Akses invoice &amp; pantau status pesanan kapan saja.</li>
                    </ul>
                    <a href="{{ route('register', ['role' => 'customer']) }}" class="btn btn-brand btn-sm">
                        Daftar sebagai Kantor <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
