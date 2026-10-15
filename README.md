# Marketplace Katering

Web app Laravel yang menghubungkan perusahaan katering (merchant) dengan kantor
(customer) untuk pemesanan makan siang karyawan. Setiap pembelian menerbitkan
invoice otomatis yang bisa diakses kedua belah pihak.

Dibuat sebagai hasil tes coding Web Developer — PT Transindo Data Perkasa.

## Fitur

Portal Merchant (Katering)

- Registrasi & login khusus katering
- Kelola profil perusahaan: nama, kota, alamat, jenis makanan, deskripsi
- CRUD menu lengkap dengan foto, deskripsi, harga, kategori, dan toggle ketersediaan
- Daftar order masuk dengan filter status + detail invoice
- Alur status order: pending, dikonfirmasi, dikirim, selesai (atau dibatalkan)
- Tandai invoice lunas
- Dashboard: total order, omzet bulan ini, menu terlaris

Portal Customer (Kantor)

- Registrasi & login khusus kantor
- Pencarian katering: kata kunci, filter kota, filter jenis makanan, urutkan
- Detail katering beserta daftar menu
- Pemesanan: pilih menu, jumlah porsi per menu, tanggal pengiriman, catatan
- Riwayat order + akses invoice (print-friendly)
- Dashboard: order aktif & total transaksi

Non-fungsional

- Autentikasi manual (tanpa package scaffolding): hash bcrypt, throttle login
  5x/menit, regenerasi session, logout via POST
- Otorisasi per role via middleware + policy (merchant A tidak bisa menyentuh
  data merchant B)
- Transaksi database untuk pembuatan order + item + invoice (atomik)
- Harga dihitung ulang di server (anti price-tampering), price_snapshot menjaga
  invoice lama tidak berubah saat harga menu berubah
- Upload foto divalidasi (jpg/jpeg/png/webp, maks 2 MB)
- Halaman error kustom 403/404/500, flash message konsisten, responsif 375px+

## Stack

- Laravel 12, PHP 8.2+
- MySQL 8
- Blade + Bootstrap 5.3 via CDN (tanpa build step), Bootstrap Icons
- Layer CSS kustom: `public/css/app.css` (design tokens, satu warna aksen)

## Cara Menjalankan

Prasyarat: PHP 8.2+, Composer, MySQL 8 berjalan.

```bash
# 1. Install dependensi
composer install

# 2. Siapkan environment
cp .env.example .env
php artisan key:generate

# 3. Buat database MySQL bernama katering_marketplace, lalu sesuaikan
#    DB_USERNAME / DB_PASSWORD di .env bila perlu (default: root, tanpa password)

# 4. Migrasi + data contoh (4 katering, 22 menu berfoto, 2 kantor, 4 order)
php artisan migrate:fresh --seed

# 5. Link storage untuk foto menu
php artisan storage:link

# 6. Jalankan
php artisan serve
```

Buka http://127.0.0.1:8000

Alternatif: file SQL siap import tersedia di `database/katering_marketplace.sql`
(sudah termasuk schema + data contoh; tetap jalankan langkah 5).

## Akun Demo

Semua akun memakai password: `password`

| Role     | Email                  | Nama                        |
|----------|------------------------|-----------------------------|
| Merchant | sedap@katering.test    | Katering Sedap Nusantara (Jambi, Indonesia) |
| Merchant | dapurumi@katering.test | Dapur Umi Masakan Padang (Jambi, Padang)    |
| Merchant | greenbowl@katering.test| Green Bowl Healthy Catering (Batam, Sehat)  |
| Merchant | sakura@katering.test   | Sakura Bento Batam (Batam, Jepang)          |
| Customer | kantor@majujaya.test   | PT Maju Jaya Sentosa (Jambi)|
| Customer | kantor@batamtrade.test | CV Batam Trade Office (Batam)|

## Menjalankan Test

```bash
php artisan test
```

16 feature/unit test mencakup: registrasi + hashing password, batas akses antar
role & antar tenant, total order dari harga database, pembuatan invoice atomik,
penolakan order lintas merchant, penolakan upload file non-gambar/oversize,
escaping XSS, penolakan tanggal lampau, throttle login, dan batas porsi.

## Struktur Singkat

```
app/Http/Controllers/Auth/       register/login/logout manual
app/Http/Controllers/Merchant/   dashboard, profil, menu, order, invoice
app/Http/Controllers/Customer/   dashboard, pencarian, order, invoice
app/Http/Middleware/             EnsureUserHasRole (alias middleware "role")
app/Policies/                    otorisasi Menu/Order/Invoice
database/migrations/             7 tabel inti + tabel bawaan Laravel
database/seeders/                data demo + generator foto placeholder (GD)
database/katering_marketplace.sql  dump SQL siap import
resources/views/                 Blade (layouts, partials, kedua portal, errors)
public/css/app.css               design tokens & komponen brand
tests/Feature/MarketplaceFlowTest.php
```

## Catatan Keputusan Desain

- Satu tabel `users` + kolom `role` (bukan multi-guard): cukup untuk dua portal,
  lebih sedikit kompleksitas session/redirect.
- `order_items.price_snapshot`: harga menu bisa berubah, invoice lama harus tetap
  mencerminkan harga saat transaksi terjadi.
- FK `order_items.menu_id` RESTRICT: menu yang sudah pernah dipesan tidak bisa
  dihapus demi integritas riwayat; sebagai gantinya ada toggle "Tersedia".
- Nomor order & invoice unik, dibuat dalam `DB::transaction` yang sama dengan
  pembuatan order (anti duplikasi saat request beruntun).
- Total order selalu dihitung dari harga di database, bukan dari input form.
- Foto menu disimpan di disk `public` (`storage/app/public/menus`), hanya path
  relatif yang dicatat di database.
