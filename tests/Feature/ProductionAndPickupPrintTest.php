<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderBatch;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class ProductionAndPickupPrintTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $customer;

    private Order $order;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->customer = User::factory()->create([
            'role' => 'customer',
            'is_active' => true,
        ]);

        $category = Category::create([
            'name' => 'Roster Beton',
            'slug' => 'roster-beton',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'name' => 'Roster Minimalis Bintang 20',
            'slug' => 'roster-minimalis-bintang-20',
            'description' => 'Roster beton minimalis',
            'price' => 15000,
            'stock' => 1000,
            'is_active' => true,
        ]);

        $this->order = Order::create([
            'user_id' => $this->customer->id,
            'order_number' => 'ORD-20260905-0001',
            'shipping_email' => $this->customer->email,
            'status' => 'processing',
            'payment_status' => 'paid',
            'fulfillment_type' => 'po_single',
            'subtotal' => 1500000,
            'shipping_cost' => 100000,
            'discount_amount' => 0,
            'grand_total' => 1600000,
            'shipping_name' => $this->customer->name,
            'shipping_phone' => '08123456789',
            'shipping_address' => 'Jl. Veteran No. 12',
            'shipping_city' => 'Purwakarta',
            'shipping_province' => 'Jawa Barat',
            'shipping_postal_code' => '41115',
            'factory_name' => 'Pabrik Utama Plered',
            'factory_pic_name' => 'Kang Asep',
            'factory_pic_phone' => '081234567890',
        ]);

        OrderItem::create([
            'order_id' => $this->order->id,
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_price' => 15000,
            'quantity' => 100,
            'subtotal' => 1500000,
        ]);
    }

    public function test_admin_can_view_production_order_pdf(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('print.production-order', $this->order));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_admin_can_view_pickup_order_pdf(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('print.pickup-order', $this->order));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_admin_can_view_batch_specific_spk_and_spab(): void
    {
        $batch = OrderBatch::create([
            'order_id' => $this->order->id,
            'batch_number' => 1,
            'batch_name' => 'Batch #1',
            'quantity' => 50,
            'factory_name' => 'CV. Sumber Berkah',
            'factory_pic_name' => 'Pak Asep',
            'factory_pic_phone' => '081299998888',
            'status' => 'pending_production',
        ]);

        // SPK batch
        $responseSpk = $this->actingAs($this->admin)
            ->get(route('print.production-order', ['order' => $this->order->id, 'batch_id' => $batch->id]));
        $responseSpk->assertStatus(200);
        $this->assertEquals('application/pdf', $responseSpk->headers->get('Content-Type'));

        // SPAB batch
        $responseSpab = $this->actingAs($this->admin)
            ->get(route('print.pickup-order', ['order' => $this->order->id, 'batch_id' => $batch->id]));
        $responseSpab->assertStatus(200);
        $this->assertEquals('application/pdf', $responseSpab->headers->get('Content-Type'));
    }

    public function test_non_admin_cannot_access_spk_without_signature(): void
    {
        $response = $this->actingAs($this->customer)
            ->get(route('print.production-order', $this->order));

        $response->assertStatus(403);
    }

    public function test_signed_url_allows_guest_access(): void
    {
        $signedUrl = URL::signedRoute('print.production-order', $this->order);

        $response = $this->get($signedUrl);
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }
}
