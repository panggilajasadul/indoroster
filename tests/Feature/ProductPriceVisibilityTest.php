<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPriceVisibilityTest extends TestCase
{
    use RefreshDatabase;

    private Category $category;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = Category::create([
            'name' => 'Roster Beton Minimalis',
            'slug' => 'roster-beton-minimalis',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Roster Nako Minimalis',
            'slug' => 'roster-nako-minimalis',
            'sku' => 'RST-NK-001',
            'description' => 'Roster beton presisi cetak tumbuk padat.',
            'price' => 15000,
            'stock' => 500,
            'is_active' => true,
            'is_featured' => true,
        ]);
    }

    public function test_prices_are_shown_when_toggle_is_on(): void
    {
        SiteSetting::setValue('show_product_prices', '1');

        $this->assertTrue(SiteSetting::showPrices());

        // Product Detail Page
        $response = $this->get('/produk/'.$this->product->slug);
        $response->assertStatus(200);
        $response->assertSee('Rp15.000');
        $response->assertSee('+ Keranjang');
        $response->assertSee('Beli Sekarang');

        // Order WA Message
        $order = Order::create([
            'order_number' => 'WA-TEST-001',
            'status' => 'pending_payment',
            'payment_status' => 'unpaid',
            'subtotal' => 750000,
            'grand_total' => 750000,
            'shipping_name' => 'Budi Santoso',
            'shipping_phone' => '081234567890',
            'shipping_address' => 'Jl. Merdeka No. 10',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_price' => 15000,
            'quantity' => 50,
            'subtotal' => 750000,
        ]);

        $waUrl = $order->getBuyerToAdminWaOrderLink();
        $this->assertStringContainsString('DETAIL%20PESANAN', $waUrl);
        $this->assertStringContainsString('Subtotal%20Barang', $waUrl);
    }

    public function test_prices_are_hidden_and_quotation_mode_active_when_toggle_is_off(): void
    {
        SiteSetting::setValue('show_product_prices', '0');
        SiteSetting::setValue('hidden_price_text', 'Minta Penawaran');

        $this->assertFalse(SiteSetting::showPrices());
        $this->assertEquals('Minta Penawaran', SiteSetting::hiddenPriceText());

        // Product Detail Page (Single Prominent Green Button)
        $response = $this->get('/produk/'.$this->product->slug);
        $response->assertStatus(200);
        $response->assertSee('Minta Penawaran');
        $response->assertDontSee('+ Keranjang');
        $response->assertSee('Minta Penawaran Resmi (RFQ)');

        // Cart Page
        Cart::create([
            'session_id' => 'test-cart-session',
            'product_id' => $this->product->id,
            'quantity' => 50,
        ]);

        $cartResponse = $this->withCookie('cart_session_id', 'test-cart-session')->get('/keranjang');
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee('Lanjutkan Minta Penawaran');

        // Order WA Message (Quotation mode)
        $order = Order::create([
            'order_number' => 'WA-TEST-002',
            'status' => 'pending_payment',
            'payment_status' => 'unpaid',
            'subtotal' => 750000,
            'grand_total' => 750000,
            'shipping_name' => 'Budi Santoso',
            'shipping_phone' => '081234567890',
            'shipping_address' => 'Jl. Merdeka No. 10',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_price' => 15000,
            'quantity' => 50,
            'subtotal' => 750000,
        ]);

        $waUrl = $order->getBuyerToAdminWaOrderLink();
        $this->assertStringContainsString('PERMINTAAN%20PENAWARAN%20HARGA', $waUrl);
        $this->assertStringContainsString('Surat%20Penawaran%20Resmi', $waUrl);
        $this->assertStringNotContainsString('Subtotal%20Barang', $waUrl);
    }
}
