@extends('layouts.app')

@section('title', 'Marketplace Katering · Makan Siang Kantor Tanpa Repot')

@section('content')
    <section class="market-hero mb-4 mb-lg-5">
        <div class="hero-glow hero-glow-one"></div>
        <div class="hero-glow hero-glow-two"></div>

        <div class="row align-items-center g-5 position-relative">
            <div class="col-lg-7">
                <div class="eyebrow mb-3">
                    <i class="bi bi-stars"></i>
                    Pemesanan katering untuk makan siang kantor
                </div>
                <h1 class="hero-title mb-3">
                    Makan siang kantor,<br>
                    <span>beres tanpa repot.</span>
                </h1>
                <p class="hero-copy mb-4">
                    Temukan katering tepercaya, pilih menu sesuai kebutuhan tim, atur tanggal
                    pengiriman, dan dapatkan invoice otomatis dalam satu alur yang rapi.
                </p>

                <div class="d-flex flex-wrap gap-2 mb-4">
                    <a href="{{ route('register', ['role' => 'customer']) }}" class="btn btn-accent btn-lg px-4">
                        Mulai sebagai Kantor <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <a href="{{ route('register', ['role' => 'merchant']) }}" class="btn btn-hero-outline btn-lg px-4">
                        <i class="bi bi-shop me-1"></i>Gabung sebagai Katering
                    </a>
                </div>

                <div class="hero-assurance d-flex flex-wrap gap-3 gap-md-4">
                    <span><i class="bi bi-shield-check"></i> Akun aman</span>
                    <span><i class="bi bi-receipt"></i> Invoice otomatis</span>
                    <span><i class="bi bi-calendar2-check"></i> Jadwal fleksibel</span>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="menu-showcase">
                    @forelse ($featuredMenus as $index => $menu)
                        <article class="showcase-card showcase-card-{{ $index + 1 }}">
                            <img src="{{ asset('storage/'.$menu->photo_path) }}"
                                 alt="{{ $menu->name }} dari {{ $menu->merchantProfile->company_name }}">
                            <div class="showcase-content">
                                <div class="showcase-category">{{ $menu->category }}</div>
                                <h2>{{ $menu->name }}</h2>
                                <div class="d-flex justify-content-between align-items-end gap-2">
                                    <span>{{ $menu->merchantProfile->company_name }}</span>
                                    <strong>Rp{{ number_format((float) $menu->price, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="showcase-empty">
                            <i class="bi bi-basket2"></i>
                            <span>Menu pilihan akan tampil di sini.</span>
                        </div>
                    @endforelse
                    <div class="showcase-note">
                        <span class="showcase-note-icon"><i class="bi bi-check2"></i></span>
                        <span><strong>Siap dipesan</strong><small>Menu aktif dari merchant</small></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="market-stats mb-5" aria-label="Ringkasan marketplace">
        <div class="stat-item">
            <span class="stat-icon" aria-hidden="true"><i class="bi bi-shop-window"></i></span>
            <div class="stat-copy"><strong>{{ $merchantCount }}</strong><span>Katering aktif</span></div>
        </div>
        <div class="stat-item">
            <span class="stat-icon" aria-hidden="true"><i class="bi bi-grid"></i></span>
            <div class="stat-copy"><strong>{{ $menuCount }}</strong><span>Pilihan menu</span></div>
        </div>
        <div class="stat-item">
            <span class="stat-icon" aria-hidden="true"><i class="bi bi-geo-alt"></i></span>
            <div class="stat-copy"><strong>{{ $cities->count() }}</strong><span>Kota tersedia</span></div>
        </div>
        <div class="stat-item">
            <span class="stat-icon" aria-hidden="true"><i class="bi bi-receipt-cutoff"></i></span>
            <div class="stat-copy"><strong>Otomatis</strong><span>Pesanan &amp; invoice</span></div>
        </div>
    </section>

    <section class="mb-5">
        <div class="section-heading text-center mx-auto mb-4">
            <span class="section-kicker">Cara kerjanya</span>
            <h2>Alur yang sama untuk kedua pihak</h2>
            <p>Setiap langkah dibuat singkat agar urusan makan siang tidak menyita waktu tim Anda.</p>
        </div>

        <div class="journey-grid">
            <article class="journey-card">
                <div class="journey-topline"><span>01</span><i class="bi bi-person-plus"></i></div>
                <h3>Daftar sesuai peran</h3>
                <p>Kantor dan katering memiliki portal masing-masing dengan fungsi yang relevan.</p>
            </article>
            <article class="journey-card">
                <div class="journey-topline"><span>02</span><i class="bi bi-search"></i></div>
                <h3>Temukan dan pilih menu</h3>
                <p>Filter berdasarkan kota dan jenis makanan, lalu tentukan menu serta jumlah porsi.</p>
            </article>
            <article class="journey-card">
                <div class="journey-topline"><span>03</span><i class="bi bi-receipt-cutoff"></i></div>
                <h3>Pantau dalam satu tempat</h3>
                <p>Kedua pihak melihat order, status pengiriman, dan invoice yang sama.</p>
            </article>
        </div>
    </section>

    <section class="portal-section mb-3">
        <div class="section-heading mb-4">
            <span class="section-kicker">Dua portal yang saling terhubung</span>
            <h2>Katering mengelola menu, kantor mengatur jadwal</h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <article class="portal-card portal-merchant h-100">
                    <div class="portal-icon"><i class="bi bi-shop"></i></div>
                    <div>
                        <span class="portal-label">Untuk Merchant</span>
                        <h3>Kelola bisnis katering lebih teratur</h3>
                        <ul class="feature-list">
                            <li><i class="bi bi-check2-circle"></i>Kelola profil dan ketersediaan menu</li>
                            <li><i class="bi bi-check2-circle"></i>Terima order lengkap dengan rincian porsi</li>
                            <li><i class="bi bi-check2-circle"></i>Pantau status order dan pembayaran invoice</li>
                        </ul>
                        <a href="{{ route('register', ['role' => 'merchant']) }}" class="btn btn-outline-brand">
                            Daftarkan Katering <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </article>
            </div>
            <div class="col-lg-6">
                <article class="portal-card portal-customer h-100">
                    <div class="portal-icon"><i class="bi bi-buildings"></i></div>
                    <div>
                        <span class="portal-label">Untuk Customer</span>
                        <h3>Pesan makan siang kantor tanpa ribet</h3>
                        <ul class="feature-list">
                            <li><i class="bi bi-check2-circle"></i>Cari katering berdasarkan lokasi dan jenis makanan</li>
                            <li><i class="bi bi-check2-circle"></i>Pilih beberapa menu dan jadwal pengiriman</li>
                            <li><i class="bi bi-check2-circle"></i>Akses riwayat order dan invoice kapan saja</li>
                        </ul>
                        <a href="{{ route('register', ['role' => 'customer']) }}" class="btn btn-brand">
                            Daftar sebagai Kantor <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </article>
            </div>
        </div>
    </section>
@endsection
