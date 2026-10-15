<?php

namespace Tests\Feature;

use App\Models\CustomerProfile;
use App\Models\Invoice;
use App\Models\Menu;
use App\Models\MerchantProfile;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MarketplaceFlowTest extends TestCase
{
    use RefreshDatabase;

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
