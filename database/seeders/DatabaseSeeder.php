<?php

namespace Database\Seeders;

use App\Models\CuisineType;
use App\Models\CustomerProfile;
use App\Models\Invoice;
use App\Models\Menu;
use App\Models\MerchantProfile;
use App\Models\Order;
use App\Models\User;
use App\Support\PlaceholderImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * Data demo untuk review & pengujian.
 *
 * Semua akun demo memakai password yang sama (lihat README):
 *   - 4 katering (2 di Kota Jambi, 2 di Kota Batam)
 *   - 2 kantor sebagai customer
 *   - 22 menu dengan foto placeholder yang DIGENERATE SENDIRI via GD
 *   - 4 order contoh (selesai+lunas, dikonfirmasi, pending, dibatalkan)
 *     masing-masing dengan invoice.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        // ── Jenis makanan resmi (dikelola admin lewat dashboard) ────────
        foreach (['Indonesia', 'Padang', 'Chinese', 'Jepang', 'Barat', 'Sehat', 'Tradisional'] as $cuisine) {
            CuisineType::create(['name' => $cuisine, 'is_active' => true]);
        }

        // ── Akun admin (tidak bisa didaftarkan lewat form registrasi) ────
        User::create([
            'name' => 'Admin Marketplace',
            'email' => 'admin@katering.test',
            'phone' => '081100000001',
            'password' => $password,
            'role' => 'admin',
        ]);

        $menusDir = Storage::disk('public')->path('menus');

        if (! is_dir($menusDir)) {
            mkdir($menusDir, 0777, true);
        }

        // Warna placeholder per kategori menu.
        $colors = [
            'Makanan Berat' => '#D9744F',
            'Makanan Ringan' => '#E3B23C',
            'Minuman' => '#4F97D9',
            'Paket' => '#5FA97A',
        ];

        $sedap = $this->createMerchant($password, [
            'name' => 'Sri Wahyuni',
            'email' => 'sedap@katering.test',
            'phone' => '081234567001',
        ], [
            'company_name' => 'Katering Sedap Nusantara',
            'city' => 'Kota Jambi',
            'address' => 'Jl. Hayam Wuruk No. 12, Jelutung, Kota Jambi',
            'cuisine_type' => 'Indonesia',
            'description' => 'Katering rumahan sejak 2015, spesialis nasi kotak dan prasmanan untuk kantor serta acara keluarga. Bahan segar setiap hari, halal, dan tanpa MSG berlebihan.',
        ], [
            ['Nasi Kotak Ayam Bakar', 25000, 'Makanan Berat', 'nasi-kotak-ayam-bakar', 'Nasi putih, ayam bakar madu, tempe orek, tumis buncis, sambal, kerupuk.', true],
            ['Nasi Kotak Ikan Goreng', 23000, 'Makanan Berat', 'nasi-kotak-ikan-goreng', 'Nasi putih, ikan nila goreng, tahu, tumis kangkung, sambal terasi.', true],
            ['Soto Ayam Spesial', 18000, 'Makanan Berat', 'soto-ayam-spesial', 'Soto ayam kuah kuning dengan nasi terpisah, telur, perkedel, dan emping.', true],
            ['Ayam Geprek Sambal Bawang', 20000, 'Makanan Berat', 'ayam-geprek-sambal-bawang', 'Ayam krispi digeprek sambal bawang (level 0-5), nasi putih, lalapan segar.', true],
            ['Es Teh Manis', 5000, 'Minuman', 'es-teh-manis', 'Teh manis dingin kemasan cup 400 ml, diseduh segar setiap pagi.', true],
            ['Snack Box 3 Item', 12000, 'Makanan Ringan', 'snack-box-3-item', 'Tiga kue pilihan: risoles, lemper, dan pastel. Cocok untuk rapat pagi.', true],
        ], $menusDir, $colors);

        $dapurUmi = $this->createMerchant($password, [
            'name' => 'Yeti Wulandari',
            'email' => 'dapurumi@katering.test',
            'phone' => '081234567002',
        ], [
            'company_name' => 'Dapur Umi Masakan Padang',
            'city' => 'Kota Jambi',
            'address' => 'Jl. Gatot Subroto No. 88, Pasar Jambi, Kota Jambi',
            'cuisine_type' => 'Padang',
            'description' => 'Masakan Padang otentik dengan bumbu warisan keluarga. Melayani porsi perorangan sampai ratusan porsi untuk instansi, diantar tepat waktu.',
        ], [
            ['Nasi Padang Rendang', 30000, 'Makanan Berat', 'nasi-padang-rendang', 'Rendang daging sapi empuk, sayur nangka, gulai rebung, sambal lado merah.', true],
            ['Nasi Padang Ayam Pop', 27000, 'Makanan Berat', 'nasi-padang-ayam-pop', 'Ayam pop gurih lembut, sambal tomat segar, daun singkong rebus.', true],
            ['Gulai Tunjang', 32000, 'Makanan Berat', 'gulai-tunjang', 'Gulai tunjang sapi kuah kental berbumbu rempah, disajikan dengan nasi putih.', true],
            ['Paket Hemat Anak Kantor', 22000, 'Paket', 'paket-hemat-anak-kantor', 'Nasi, ayam gulai, sayur nangka, sambal. Khusus pemesanan minimal 10 porsi.', true],
            ['Teh Talua', 8000, 'Minuman', 'teh-talua', 'Teh talua khas Minang: teh, telur, dan gula aren dikocok berbusa.', false],
        ], $menusDir, $colors);

        $greenBowl = $this->createMerchant($password, [
            'name' => 'Maya Kusuma',
            'email' => 'greenbowl@katering.test',
            'phone' => '081234567003',
        ], [
            'company_name' => 'Green Bowl Healthy Catering',
            'city' => 'Kota Batam',
            'address' => 'Komplek Nagoya Business Centre Blok C3, Batam',
            'cuisine_type' => 'Sehat',
            'description' => 'Katering sehat untuk gaya hidup aktif: rendah minyak, tanpa pengawet, dan informasi kalori tercantum per porsi. Cocok untuk program makan siang kantor.',
        ], [
            ['Chicken Salad Bowl', 35000, 'Makanan Berat', 'chicken-salad-bowl', 'Dada ayam panggang, mixed lettuce, tomat ceri, alpukat, dressing lemon madu.', true],
            ['Berry Smoothie Bowl', 30000, 'Makanan Ringan', 'berry-smoothie-bowl', 'Smoothie stroberi dan blueberry, topping granola, chia seed, dan pisang.', true],
            ['Set Nasi Merah Ayam Panggang', 38000, 'Paket', 'set-nasi-merah-ayam-panggang', 'Nasi merah, ayam panggang herb, brokoli kukus, telur rebus. Sekitar 520 kkal.', true],
            ['Green Juice 500ml', 15000, 'Minuman', 'green-juice-500ml', 'Campuran bayam, apel hijau, timun, dan jeruk nipis tanpa gula tambahan.', true],
            ['Overnight Oats Cokelat', 20000, 'Makanan Ringan', 'overnight-oats-cokelat', 'Oat direndam susu almond, kakao, madu, dan potongan pisang.', true],
        ], $menusDir, $colors);

        $sakura = $this->createMerchant($password, [
            'name' => 'Rina Tanaka',
            'email' => 'sakura@katering.test',
            'phone' => '081234567004',
        ], [
            'company_name' => 'Sakura Bento Batam',
            'city' => 'Kota Batam',
            'address' => 'Jl. Raja Ali Haji No. 21, Batam Centre, Batam',
            'cuisine_type' => 'Jepang',
            'description' => 'Bento dan masakan Jepang halal-friendly untuk makan siang kantor. Dikemas rapi, porsi mengenyangkan, dan selalu tepat waktu.',
        ], [
            ['Chicken Teriyaki Bento', 40000, 'Paket', 'chicken-teriyaki-bento', 'Ayam teriyaki, nasi tabur wijen, salad kol, dan tamagoyaki.', true],
            ['Beef Gyudon', 45000, 'Makanan Berat', 'beef-gyudon', 'Irisan daging sapi manis-gurih di atas nasi hangat, taburan nori.', true],
            ['Sushi Roll Set 8 pcs', 42000, 'Makanan Berat', 'sushi-roll-set-8-pcs', 'Delapan roll sushi isi crab stick dan timun, lengkap saus shoyu dan wasabi.', true],
            ['Ramen Tori Paitan', 38000, 'Makanan Berat', 'ramen-tori-paitan', 'Ramen kuah kaldu ayam kental, chashu ayam, telur ramen, dan menma.', true],
            ['Ocha Dingin', 7000, 'Minuman', 'ocha-dingin', 'Teh hijau Jepang tanpa gula, botol 350 ml.', true],
            ['Takoyaki 6 pcs', 18000, 'Makanan Ringan', 'takoyaki-6-pcs', 'Takoyaki isi gurita dengan saus takoyaki, mayones, dan katsuobushi.', false],
        ], $menusDir, $colors);

        // ── Customer (kantor) ──────────────────────────────────────────
        $majuJaya = $this->createCustomer($password, [
            'name' => 'Andi Rahman',
            'email' => 'kantor@majujaya.test',
            'phone' => '081345678001',
        ], [
            'office_name' => 'PT Maju Jaya Sentosa',
            'city' => 'Kota Jambi',
            'address' => 'Jl. Sultan Agung No. 45, Kota Jambi',
            'pic_name' => 'Andi Rahman',
        ]);

        $batamTrade = $this->createCustomer($password, [
            'name' => 'Siska Melati',
            'email' => 'kantor@batamtrade.test',
            'phone' => '081345678002',
        ], [
            'office_name' => 'CV Batam Trade Office',
            'city' => 'Kota Batam',
            'address' => 'Komplek Batam Business Park Blok B2, Batam',
            'pic_name' => 'Siska Melati',
        ]);

        // ── Order contoh + invoice ──────────────────────────────────────
        // 1) Selesai & lunas: PT Maju Jaya dari Katering Sedap Nusantara (3 hari lalu).
        $menusSedap = $sedap->menus->keyBy('name');
        $this->createOrder($sedap, $majuJaya, [
            [$menusSedap['Nasi Kotak Ayam Bakar'], 30],
            [$menusSedap['Es Teh Manis'], 30],
        ], now()->subDays(3), now()->subDay()->toDateString(), 'selesai', 'Diantar ke resepsionis lantai 1.', true);

        // 2) Dikonfirmasi & belum lunas: CV Batam Trade dari Green Bowl (hari ini, kirim +2 hari).
        $menusGreen = $greenBowl->menus->keyBy('name');
        $this->createOrder($greenBowl, $batamTrade, [
            [$menusGreen['Chicken Salad Bowl'], 15],
            [$menusGreen['Green Juice 500ml'], 15],
        ], now(), now()->addDays(2)->toDateString(), 'dikonfirmasi', null, false);

        // 3) Pending: PT Maju Jaya dari Dapur Umi (hari ini, kirim besok).
        $menusUmi = $dapurUmi->menus->keyBy('name');
        $this->createOrder($dapurUmi, $majuJaya, [
            [$menusUmi['Nasi Padang Rendang'], 20],
        ], now(), now()->addDay()->toDateString(), 'pending', 'Mohon sambal dipisah.', false);

        // 4) Dibatalkan: CV Batam Trade dari Sakura Bento (kemarin).
        $menusSakura = $sakura->menus->keyBy('name');
        $this->createOrder($sakura, $batamTrade, [
            [$menusSakura['Beef Gyudon'], 10],
        ], now()->subDay(), now()->toDateString(), 'dibatalkan', null, false);
    }

    /**
     * Buat user merchant + profil + menu-menu (dengan foto placeholder).
     *
     * @return MerchantProfile dengan relasi menus sudah dimuat.
     */
    private function createMerchant(string $password, array $user, array $profile, array $menus, string $menusDir, array $colors): MerchantProfile
    {
        $merchantUser = User::create([
            'name' => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'password' => $password,
            'role' => 'merchant',
        ]);

        $merchantProfile = MerchantProfile::create($profile + ['user_id' => $merchantUser->id]);

        foreach ($menus as [$name, $price, $category, $slug, $description, $available]) {
            PlaceholderImage::make(
                $menusDir.DIRECTORY_SEPARATOR."{$slug}.jpg",
                $name,
                $colors[$category]
            );

            $merchantProfile->menus()->create([
                'name' => $name,
                'description' => $description,
                'price' => $price,
                // Path relatif terhadap disk "public" (sudah termasuk folder menus/).
                'photo_path' => "menus/{$slug}.jpg",
                'category' => $category,
                'is_available' => $available,
            ]);
        }

        return $merchantProfile->load('menus');
    }

    private function createCustomer(string $password, array $user, array $profile): CustomerProfile
    {
        $customerUser = User::create([
            'name' => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'password' => $password,
            'role' => 'customer',
        ]);

        return CustomerProfile::create($profile + ['user_id' => $customerUser->id]);
    }

    /**
     * Buat order + item + invoice dengan tanggal dibuat yang dimundurkan,
     * meniru pola penomoran di Customer\OrderController@store.
     *
     * @param  array<int, array{0: Menu, 1: int}>  $lines  pasangan [menu, qty]
     */
    private function createOrder(
        MerchantProfile $merchant,
        CustomerProfile $customer,
        array $lines,
        \Illuminate\Support\Carbon $createdAt,
        string $deliveryDate,
        string $status,
        ?string $notes,
        bool $paid
    ): Order {
        $subtotal = 0.0;
        foreach ($lines as [$menu, $qty]) {
            $subtotal += round((float) $menu->price * $qty, 2);
        }

        $order = new Order([
            'order_number' => 'TMP-SEED',
            'merchant_profile_id' => $merchant->id,
            'customer_profile_id' => $customer->id,
            'delivery_date' => $deliveryDate,
            'notes' => $notes,
            'subtotal' => round($subtotal, 2),
            'status' => $status,
        ]);
        $order->created_at = $createdAt;
        $order->updated_at = $createdAt;
        $order->save();

        $order->order_number = sprintf('ORD-%s-%04d', $createdAt->format('Ymd'), $order->id);
        $order->save();

        foreach ($lines as [$menu, $qty]) {
            $order->items()->create([
                'menu_id' => $menu->id,
                'qty' => $qty,
                'price_snapshot' => $menu->price,
                'subtotal' => round((float) $menu->price * $qty, 2),
            ]);
        }

        $invoice = new Invoice([
            'order_id' => $order->id,
            'invoice_number' => sprintf('INV-%s-%04d', $createdAt->format('Ymd'), $order->id),
            'issued_at' => $createdAt->toDateString(),
            'total' => round($subtotal, 2),
            'payment_status' => $paid ? 'lunas' : 'belum_lunas',
            'paid_at' => $paid ? $createdAt->copy()->addDay() : null,
        ]);
        $invoice->created_at = $createdAt;
        $invoice->updated_at = $createdAt;
        $invoice->save();

        return $order;
    }
}
