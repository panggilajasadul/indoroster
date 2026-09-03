<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MemberOrderHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_can_view_order_history_with_multiple_orders()
    {
        SiteSetting::setValue('order_mode', 'whatsapp');

        $user = User::factory()->create([
            'email' => 'abdulhamid66266@gmail.com',
            'phone' => '081389709847',
        ]);

        $category = Category::create([
            'name' => 'Roster Beton Minimalis',
            'slug' => 'roster-beton-minimalis',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Roster Nako Minimalis',
            'slug' => 'roster-nako-minimalis',
            'sku' => 'RST-NK-001',
            'description' => 'Roster beton presisi cetak tumbuk padat.',
            'price' => 15000,
            'stock' => 500,
            'is_active' => true,
        ]);

        // Create 2 orders for user
        for ($i = 1; $i <= 2; $i++) {
            $order = Order::create([
                'order_number' => 'INV-WA-20260903-000'.$i,
                'user_id' => $user->id,
                'status' => 'pending_payment',
                'payment_status' => 'unpaid',
                'order_source' => 'whatsapp',
                'subtotal' => 1500000,
                'shipping_cost' => 0,
                'discount_amount' => 0,
                'grand_total' => 1500000,
                'shipping_name' => $user->name,
                'shipping_email' => $user->email,
                'shipping_phone' => $user->phone,
                'shipping_address' => 'Jl. Sudirman No. 45',
                'shipping_city' => 'Purwakarta',
                'shipping_district' => 'Purwakarta',
                'shipping_province' => 'Jawa Barat',
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'quantity' => 100,
                'subtotal' => 1500000,
            ]);
        }

        $response = $this->actingAs($user)->get('/member/pesanan');

        $response->assertStatus(200);
        $response->assertSee('INV-WA-20260903-0001');
        $response->assertSee('INV-WA-20260903-0002');
    }
}
