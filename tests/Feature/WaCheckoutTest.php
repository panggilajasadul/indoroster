<?php

namespace Tests\Feature;

use App\Livewire\CartCount;
use App\Livewire\Checkout;
use App\Livewire\ProductDetail;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class WaCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        SiteSetting::setValue('order_mode', 'whatsapp');
    }

    public function test_cart_page_renders_in_whatsapp_mode()
    {
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
            'is_featured' => true,
        ]);

        Cart::create([
            'session_id' => 'test-session',
            'product_id' => $product->id,
            'quantity' => 50,
        ]);

        $response = $this->withCookie('cart_session_id', 'test-session')->get('/keranjang');
        $response->assertStatus(200);
        $response->assertSee('Roster Nako Minimalis');
    }

    public function test_checkout_page_renders_in_whatsapp_mode_and_creates_order()
    {
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
            'description' => 'Roster presisi cetak tumbuk padat.',
            'price' => 15000,
            'stock' => 500,
            'is_active' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'Budi Santoso',
            'phone' => '081234567890',
        ]);

        $cart = Cart::create([
            'user_id' => $user->id,
            'session_id' => 'test-session',
            'product_id' => $product->id,
            'quantity' => 100,
        ]);

        session()->put('selected_cart_items', [(string) $cart->id]);

        $this->actingAs($user);

        $component = Livewire::test(Checkout::class)
            ->set('name', 'Budi Santoso')
            ->set('phone', '081234567890')
            ->set('province_id', '32')
            ->set('city_id', '3214')
            ->set('district_id', '321401')
            ->set('address', 'Jl. Sudirman No. 45, Proyek Ruko')
            ->set('latitude', -6.5568)
            ->set('longitude', 107.4431)
            ->call('processCheckout');

        $order = Order::where('shipping_phone', '081234567890')->first();
        $this->assertNotNull($order, 'Order was not created in database');
        $this->assertEquals('whatsapp', $order->order_source);
        $this->assertEquals('pending_payment', $order->status);
        $this->assertEquals('unpaid', $order->payment_status);
        $this->assertEquals(1500000, $order->subtotal);
        $this->assertEquals(0, $order->shipping_cost);
        $this->assertEquals(-6.5568, $order->shipping_latitude);
        $this->assertEquals(107.4431, $order->shipping_longitude);

        $this->assertCount(1, $order->items);
        $this->assertEquals($product->id, $order->items->first()->product_id);

        $trackingResponse = $this->get('/lacak-pesanan?order_number='.$order->order_number.'&contact=081234567890');
        $trackingResponse->assertStatus(200);
        $trackingResponse->assertSee($order->order_number);
    }

    public function test_cart_count_renders_in_whatsapp_mode()
    {
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

        Cart::create([
            'session_id' => 'test-session',
            'product_id' => $product->id,
            'quantity' => 10,
        ]);

        Livewire::withCookie('cart_session_id', 'test-session')
            ->test(CartCount::class)
            ->assertSee('1');
    }

    public function test_product_detail_can_add_to_cart_in_whatsapp_mode()
    {
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

        Livewire::test(ProductDetail::class, ['slug' => $product->slug])
            ->set('quantity', 25)
            ->call('addToCart')
            ->assertDispatched('cart-updated');

        $this->assertDatabaseHas('carts', [
            'product_id' => $product->id,
            'quantity' => 25,
        ]);
    }
}
