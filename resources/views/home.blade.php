@extends('layouts.app')

@section('title', 'Marketplace Katering — Pesan Makan Siang Kantor dari Katering Terpercaya')

@section('content')
    {{-- Hero --}}
    <div class="bg-success bg-gradient text-white rounded-4 p-4 p-lg-5 mb-4">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <h1 class="h2 fw-bold mb-3">Makan siang kantor beres,<br>tanpa drama setiap hari.</h1>
                <p class="lead mb-4 opacity-75">
                    Marketplace Katering menghubungkan perusahaan katering dengan kantor yang
                    karyawannya butuh makan siang. Pilih menu, tentukan jumlah porsi dan tanggal
                    kirim — invoice terbit otomatis untuk kedua pihak.
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('register', ['role' => 'customer']) }}" class="btn btn-light btn-lg px-4 fw-semibold">
                        <i class="bi bi-building me-1"></i>Daftar sebagai Kantor
                    </a>
                    <a href="{{ route('register', ['role' => 'merchant']) }}" class="btn btn-outline-light btn-lg px-4">
                        <i class="bi bi-shop me-1"></i>Daftarkan Katering
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center">
                <div class="bg-white text-success rounded-4 p-4 shadow">
                    <i class="bi bi-egg-fried" style="font-size: 4rem;"></i>
                    <div class="row text-dark mt-3 g-2">
                        <div class="col-4">
                            <div class="fw-bold fs-4">{{ $merchantCount }}</div>
                            <div class="small text-muted">Katering</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold fs-4">{{ $menuCount }}</div>
                            <div class="small text-muted">Menu</div>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold fs-4">{{ $cities->count() }}</div>
                            <div class="small text-muted">Kota</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cara kerja --}}
    <h2 class="h4 fw-bold text-center mb-4">Cara Kerja</h2>
    <div class="row g-3 mb-5 text-center">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-success mb-2"><i class="bi bi-person-plus" style="font-size: 2rem;"></i></div>
                    <h3 class="h6 fw-bold">1. Daftar &amp; Login</h3>
                    <p class="small text-muted mb-0">
                        Kantor mendaftar sebagai customer, perusahaan katering sebagai merchant.
                        Lengkapi profil, lalu masuk ke portal masing-masing.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-success mb-2"><i class="bi bi-search" style="font-size: 2rem;"></i></div>
                    <h3 class="h6 fw-bold">2. Cari &amp; Pesan</h3>
                    <p class="small text-muted mb-0">
                        Cari katering berdasarkan lokasi dan jenis makanan. Pilih menu, tentukan
                        jumlah porsi dan tanggal pengiriman.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body p-4">
                    <div class="text-success mb-2"><i class="bi bi-receipt" style="font-size: 2rem;"></i></div>
                    <h3 class="h6 fw-bold">3. Invoice Otomatis</h3>
                    <p class="small text-muted mb-0">
                        Setiap pembelian menerbitkan invoice yang bisa diakses kantor maupun
                        katering, lengkap dengan rincian menu dan total biaya.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Dua portal --}}
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold"><i class="bi bi-shop text-success me-2"></i>Portal Katering (Merchant)</h2>
                    <ul class="small text-muted mb-3 ps-3">
                        <li>Kelola profil perusahaan katering.</li>
                        <li>Tambah, ubah, dan hapus menu lengkap dengan foto dan harga.</li>
                        <li>Pantau semua pesanan &amp; invoice yang masuk, perbarui status order.</li>
                    </ul>
                    <a href="{{ route('register', ['role' => 'merchant']) }}" class="btn btn-outline-success btn-sm">
                        Daftarkan Katering <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold"><i class="bi bi-building text-success me-2"></i>Portal Kantor (Customer)</h2>
                    <ul class="small text-muted mb-3 ps-3">
                        <li>Cari katering berdasarkan kota dan jenis makanan.</li>
                        <li>Pesan menu dengan jumlah porsi dan tanggal pengiriman.</li>
                        <li>Akses invoice &amp; pantau status pesanan kapan saja.</li>
                    </ul>
                    <a href="{{ route('register', ['role' => 'customer']) }}" class="btn btn-success btn-sm">
                        Daftar sebagai Kantor <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
