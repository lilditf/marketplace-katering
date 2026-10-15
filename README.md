# Marketplace Katering

> Demo live (Railway): <https://marketplace-katering-production.up.railway.app>
> Catatan: filesystem Railway bersifat ephemeral. Data dan foto kembali ke
> kondisi seed setiap redeploy/restart.

Web app Laravel yang menghubungkan merchant katering dengan kantor customer
untuk pemesanan makan siang karyawan. Setiap pembelian menerbitkan
invoice otomatis yang bisa diakses kedua belah pihak.

Dibuat sebagai hasil tes coding Web Developer, PT Transindo Data Perkasa.

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

Portal Admin

- Akun dibuat via seeder (tidak bisa self-register), login di halaman yang sama
- Dashboard global: katering aktif, kantor terdaftar, total order, omzet marketplace
- Kelola katering: aktif/nonaktifkan (nonaktif = disembunyikan dari pencarian,
  data & riwayat tetap utuh)
- Pantau kantor customer + riwayat ordernya
- Pantau semua order lintas merchant (read-only; status tetap wewenang merchant)
- Kelola daftar resmi jenis makanan (tambah/ubah/nonaktifkan/hapus dengan guard:
  jenis yang dipakai katering tidak bisa dihapus). Daftar ini otomatis dipakai
  form registrasi, edit profil merchant, dan filter pencarian customer.

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
- Layer CSS kustom: `public/css/app.css` (deep emerald, saffron, warm cream;
  komponen responsif dan aksesibel)

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

# Opsional: buat ulang ilustrasi menu demo tanpa mengubah database
php artisan app:regenerate-menu-images

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
| Admin    | admin@katering.test    | Admin Marketplace            |

## Menjalankan Test

```bash
php artisan test
```

22 feature/unit test mencakup: registrasi + hashing password, batas akses antar
role & antar tenant, total order dari harga database, pembuatan invoice atomik,
penolakan order lintas merchant, penolakan upload file non-gambar/oversize,
escaping XSS, penolakan tanggal lampau, throttle login, batas porsi, plus alur
admin (akses portal, larangan registrasi admin, kelola jenis makanan,
nonaktifkan katering, redirect login per role).

## Struktur Singkat

```
app/Http/Controllers/Auth/       register/login/logout manual
app/Http/Controllers/Merchant/   dashboard, profil, menu, order, invoice
app/Http/Controllers/Customer/   dashboard, pencarian, order, invoice
app/Http/Controllers/Admin/      dashboard global, kelola katering/kantor/order/jenis makanan
app/Http/Middleware/             EnsureUserHasRole (alias middleware "role")
app/Policies/                    otorisasi Menu/Order/Invoice
database/migrations/             7 tabel inti + tabel bawaan Laravel
database/seeders/                data demo + generator foto placeholder (GD)
database/katering_marketplace.sql  dump SQL siap import
resources/views/                 Blade (layouts, partials, kedua portal, errors)
public/css/app.css               design tokens & komponen brand
tests/Feature/MarketplaceFlowTest.php
```

## Penambahan di Luar Spesifikasi

Spesifikasi mempersilakan kreativitas untuk hal yang tidak diatur. Penambahan
yang saya buat, beserta alasannya:

- Alur status order 5 tahap (pending, dikonfirmasi, dikirim, selesai,
  dibatalkan). Spek hanya menyebut "melihat pesanan"; tanpa status, merchant
  dan kantor tidak punya gambaran progres pesanan.
- Status pembayaran invoice (belum_lunas/lunas) + aksi "Tandai Lunas" oleh
  merchant. Invoice tanpa status bayar hanya selembar rincian harga.
- Toggle "Tersedia" pada menu. Menu yang sudah pernah dipesan tidak bisa
  dihapus (integritas invoice), jadi disediakan cara menyembunyikannya
  dari katalog tanpa menghapus data.
- Dashboard statistik: omzet bulan ini & menu terlaris (merchant), order
  aktif & total transaksi (kantor). Ringkasan ini hal pertama yang dibutuhkan
  saat membuka portal.
- Kategori menu (Makanan Berat/Ringan/Minuman/Paket) + pengurutan hasil
  pencarian. Membantu kantor memindai katalog dengan cepat.
- Batas tanggal pengiriman (minimal besok, maksimal 6 bulan) + catatan order
  (mis. "tanpa sambal untuk 10 porsi"), kebutuhan yang memang muncul di pemesanan katering.
- Nomor order/invoice berformat ORD-/INV- tanggal + urut, memudahkan rujukan
  saat komunikasi kedua pihak.
- Throttle login 5x/menit per email+IP, sebagai bagian dari "keamanan login"
  yang diminta petunjuk pengerjaan.
- Portal admin (role ketiga, akun via seeder): moderasi katering
  (aktif/nonaktif), pantau semua order, dan kelola daftar jenis makanan yang
  tadinya konstanta kode menjadi data yang bisa dikelola tanpa deploy.

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
