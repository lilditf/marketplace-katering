<?php

namespace Tests\Feature;

use App\Models\CuisineType;
use App\Models\CustomerProfile;
use App\Models\Invoice;
use App\Models\Menu;
use App\Models\MerchantProfile;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MarketplaceFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Daftar jenis makanan kini dikelola admin via tabel cuisine_types;
        // test butuh daftar ini ada (mis. validasi registrasi merchant).
        foreach (['Indonesia', 'Padang', 'Jepang'] as $cuisine) {
            CuisineType::create(['name' => $cuisine, 'is_active' => true]);
        }
    }

    public function test_admin_can_open_admin_dashboard_and_other_roles_cannot(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [$merchantUser] = $this->createMerchant();
        [$customerUser] = $this->createCustomer();

        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
        $this->actingAs($merchantUser)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($customerUser)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_registration_cannot_create_admin_account(): void
    {
        $response = $this->post(route('register.store'), [
            'role' => 'admin',
            'name' => 'Admin Palsu',
            'email' => 'palsu@example.test',
            'phone' => '081234567890',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'city' => 'Jambi',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertDatabaseMissing('users', ['email' => 'palsu@example.test']);
    }

    public function test_admin_can_add_cuisine_type_and_it_appears_in_register_form(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.cuisines.store'), ['name' => 'Korea'])
            ->assertSessionHas('success');

        $this->assertDatabaseHas('cuisine_types', ['name' => 'Korea', 'is_active' => true]);

        // Cache daftar jenis harus sudah bersih sehingga pilihan baru tampil.
        // (logout dulu: form register hanya untuk guest)
        $this->post(route('logout'));

        $this->get(route('register', ['role' => 'merchant']))
            ->assertSee('Korea');
    }

    public function test_admin_cannot_delete_cuisine_type_that_is_in_use(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [, $merchant] = $this->createMerchant(); // cuisine_type: Indonesia

        $cuisine = CuisineType::where('name', 'Indonesia')->firstOrFail();

        $this->actingAs($admin)
            ->delete(route('admin.cuisines.destroy', $cuisine))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('cuisine_types', ['name' => 'Indonesia']);
    }

    public function test_admin_can_deactivate_merchant_and_it_disappears_from_search(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        [, $merchant] = $this->createMerchant('Katering Pertama');
        [$customerUser] = $this->createCustomer();

        $this->actingAs($admin)
            ->patch(route('admin.merchants.toggle', $merchant))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('merchant_profiles', [
            'id' => $merchant->id,
            'is_active' => false,
        ]);

        // Bersihkan flash session admin (pesan sukses toggle menyebut nama
        // katering) agar tidak ikut terbaca di request berikutnya.
        $this->flushSession();

        // Katering nonaktif tidak muncul di pencarian customer.
        $response = $this->actingAs($customerUser)->get(route('customer.caterings.index'));
        $response->assertDontSee('Katering Pertama');
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        User::factory()->create(['role' => 'admin', 'email' => 'admin@uji.test']);

        $this->post(route('login.store'), [
            'email' => 'admin@uji.test',
            'password' => 'password',
        ])->assertRedirect(route('admin.dashboard'));
    }

    public function test_merchant_can_register_and_password_is_hashed(): void
    {
        $response = $this->post(route('register.store'), [
            'role' => 'merchant',
            'name' => 'Dapur Uji',
            'email' => 'merchant@example.test',
            'phone' => '081234567890',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
            'city' => 'Jambi',
            'company_name' => 'Dapur Uji Jambi',
            'cuisine_type' => 'Indonesia',
        ]);

        $user = User::where('email', 'merchant@example.test')->firstOrFail();

        $response->assertRedirect(route('merchant.dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('rahasia123', $user->password));
        $this->assertDatabaseHas('merchant_profiles', [
            'user_id' => $user->id,
            'company_name' => 'Dapur Uji Jambi',
        ]);
    }

    public function test_customer_cannot_open_merchant_portal(): void
    {
        [$customerUser] = $this->createCustomer();

        $this->actingAs($customerUser)
            ->get(route('merchant.dashboard'))
            ->assertForbidden();
    }

    public function test_order_uses_database_price_and_creates_invoice_atomically(): void
    {
        [, $merchant, $menu] = $this->createMerchant();
        [$customerUser, $customer] = $this->createCustomer();

        $response = $this->actingAs($customerUser)->post(
            route('customer.orders.store', $merchant),
            [
                'delivery_date' => now()->addDay()->toDateString(),
                'notes' => 'Antar pukul 11.30.',
                'items' => [[
                    'menu_id' => $menu->id,
                    'qty' => 3,
                    'price' => 1,
                ]],
            ]
        );

        $order = Order::firstOrFail();

        $response->assertRedirect(route('customer.orders.show', $order));
        $this->assertSame($customer->id, $order->customer_profile_id);
        $this->assertEquals('75000.00', $order->subtotal);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'menu_id' => $menu->id,
            'qty' => 3,
            'price_snapshot' => 25000,
            'subtotal' => 75000,
        ]);
        $this->assertDatabaseHas('invoices', [
            'order_id' => $order->id,
            'total' => 75000,
            'payment_status' => 'belum_lunas',
        ]);
    }

    public function test_customer_cannot_order_a_menu_from_another_merchant(): void
    {
        [, $selectedMerchant] = $this->createMerchant('Katering Pertama');
        [, , $foreignMenu] = $this->createMerchant('Katering Kedua');
        [$customerUser] = $this->createCustomer();

        $response = $this->actingAs($customerUser)
            ->from(route('customer.orders.create', $selectedMerchant))
            ->post(route('customer.orders.store', $selectedMerchant), [
                'delivery_date' => now()->addDay()->toDateString(),
                'items' => [[
                    'menu_id' => $foreignMenu->id,
                    'qty' => 2,
                ]],
            ]);

        $response->assertRedirect(route('customer.orders.create', $selectedMerchant));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('invoices', 0);
    }

    public function test_customer_cannot_view_another_customers_invoice(): void
    {
        [, $merchant, $menu] = $this->createMerchant();
        [$ownerUser] = $this->createCustomer('Kantor Pemilik');
        [$otherUser] = $this->createCustomer('Kantor Lain');

        $this->actingAs($ownerUser)->post(route('customer.orders.store', $merchant), [
            'delivery_date' => now()->addDay()->toDateString(),
            'items' => [['menu_id' => $menu->id, 'qty' => 1]],
        ]);

        $invoice = Invoice::firstOrFail();

        $this->actingAs($otherUser)
            ->get(route('customer.invoices.show', $invoice))
            ->assertForbidden();
    }

    public function test_merchant_cannot_manage_another_merchants_menu(): void
    {
        [$merchantUser] = $this->createMerchant('Katering Pemilik');
        [, , $foreignMenu] = $this->createMerchant('Katering Lain');

        $this->actingAs($merchantUser)
            ->put(route('merchant.menus.update', $foreignMenu), [
                'name' => 'Menu yang Dimanipulasi',
                'description' => 'Seharusnya tidak tersimpan.',
                'price' => 1000,
                'category' => 'Paket',
                'is_available' => '1',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('menus', [
            'id' => $foreignMenu->id,
            'name' => 'Menu yang Dimanipulasi',
        ]);
    }

    public function test_merchant_cannot_view_another_merchants_invoice(): void
    {
        [, $ownerMerchant, $menu] = $this->createMerchant('Katering Pemilik');
        [$otherMerchantUser] = $this->createMerchant('Katering Lain');
        [$customerUser] = $this->createCustomer();

        $this->actingAs($customerUser)->post(route('customer.orders.store', $ownerMerchant), [
            'delivery_date' => now()->addDay()->toDateString(),
            'items' => [['menu_id' => $menu->id, 'qty' => 1]],
        ]);

        $invoice = Invoice::firstOrFail();

        $this->actingAs($otherMerchantUser)
            ->get(route('merchant.invoices.show', $invoice))
            ->assertForbidden();
    }

    public function test_merchant_cannot_skip_order_status_sequence(): void
    {
        [$merchantUser, $merchant, $menu] = $this->createMerchant();
        [$customerUser] = $this->createCustomer();

        $this->actingAs($customerUser)->post(route('customer.orders.store', $merchant), [
            'delivery_date' => now()->addDay()->toDateString(),
            'items' => [['menu_id' => $menu->id, 'qty' => 1]],
        ]);

        $order = Order::firstOrFail();

        $response = $this->actingAs($merchantUser)
            ->patch(route('merchant.orders.updateStatus', $order), [
                'status' => 'selesai',
            ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'pending',
        ]);
    }

    public function test_duplicate_menu_rows_cannot_bypass_quantity_limit(): void
    {
        [, $merchant, $menu] = $this->createMerchant();
        [$customerUser] = $this->createCustomer();

        $response = $this->actingAs($customerUser)
            ->from(route('customer.orders.create', $merchant))
            ->post(route('customer.orders.store', $merchant), [
                'delivery_date' => now()->addDay()->toDateString(),
                'items' => [
                    ['menu_id' => $menu->id, 'qty' => 6000],
                    ['menu_id' => $menu->id, 'qty' => 6000],
                ],
            ]);

        $response->assertRedirect(route('customer.orders.create', $merchant));
        $response->assertSessionHasErrors('items');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_menu_photo_rejects_non_image_file(): void
    {
        [$merchantUser] = $this->createMerchant();

        $file = UploadedFile::fake()->create('shell.php', 10, 'application/x-php');

        $response = $this->actingAs($merchantUser)
            ->post(route('merchant.menus.store'), [
                'name' => 'Menu Uji',
                'price' => 15000,
                'category' => 'Paket',
                'photo' => $file,
            ]);

        $response->assertSessionHasErrors('photo');
        $this->assertDatabaseCount('menus', 1); // hanya menu bawaan helper
    }

    public function test_menu_photo_rejects_file_over_2mb(): void
    {
        [$merchantUser] = $this->createMerchant();

        $file = UploadedFile::fake()->image('besar.jpg')->size(3000);

        $response = $this->actingAs($merchantUser)
            ->post(route('merchant.menus.store'), [
                'name' => 'Menu Uji',
                'price' => 15000,
                'category' => 'Paket',
                'photo' => $file,
            ]);

        $response->assertSessionHasErrors('photo');
        $this->assertDatabaseCount('menus', 1);
    }

    public function test_menu_name_with_script_tag_is_escaped_in_output(): void
    {
        [$merchantUser, $merchant] = $this->createMerchant();
        Menu::create([
            'merchant_profile_id' => $merchant->id,
            'name' => '<script>alert("xss")</script>',
            'price' => 10000,
            'category' => 'Paket',
            'is_available' => true,
        ]);

        $response = $this->actingAs($merchantUser)->get(route('merchant.menus.index'));

        $response->assertOk();
        $response->assertDontSee('<script>alert("xss")</script>', false);
        $response->assertSee('&lt;script&gt;', false);
    }

    public function test_delivery_date_in_the_past_is_rejected(): void
    {
        [, $merchant, $menu] = $this->createMerchant();
        [$customerUser] = $this->createCustomer();

        $response = $this->actingAs($customerUser)
            ->post(route('customer.orders.store', $merchant), [
                'delivery_date' => now()->subDay()->toDateString(),
                'items' => [['menu_id' => $menu->id, 'qty' => 1]],
            ]);

        $response->assertSessionHasErrors('delivery_date');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_login_is_throttled_after_five_failed_attempts(): void
    {
        [$customerUser] = $this->createCustomer();
        $payload = ['email' => $customerUser->email, 'password' => 'salah123'];

        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login.store'), $payload);
        }

        // Percobaan ke-6: bahkan password BENAR pun ditolak selama terkunci.
        $response = $this->post(route('login.store'), [
            'email' => $customerUser->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * @return array{User, MerchantProfile, Menu}
     */
    private function createMerchant(string $companyName = 'Katering Uji'): array
    {
        $user = User::factory()->merchant()->create();
        $merchant = MerchantProfile::create([
            'user_id' => $user->id,
            'company_name' => $companyName,
            'city' => 'Jambi',
            'address' => 'Jl. Pengujian No. 1',
            'cuisine_type' => 'Indonesia',
            'description' => 'Katering untuk pengujian.',
            'is_active' => true,
        ]);
        $menu = Menu::create([
            'merchant_profile_id' => $merchant->id,
            'name' => 'Nasi Kotak Uji',
            'description' => 'Menu untuk pengujian.',
            'price' => 25000,
            'photo_path' => 'menus/test.jpg',
            'category' => 'Paket',
            'is_available' => true,
        ]);

        return [$user, $merchant, $menu];
    }

    /**
     * @return array{User, CustomerProfile}
     */
    private function createCustomer(string $officeName = 'Kantor Uji'): array
    {
        $user = User::factory()->customer()->create();
        $customer = CustomerProfile::create([
            'user_id' => $user->id,
            'office_name' => $officeName,
            'city' => 'Jambi',
            'address' => 'Jl. Pengujian No. 2',
            'pic_name' => $user->name,
        ]);

        return [$user, $customer];
    }
}
