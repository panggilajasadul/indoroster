<?php

namespace Tests\Feature;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\WaOrderResource;
use App\Filament\Resources\WaOrderResource\Pages\CreateWaOrder;
use App\Livewire\OrderTracking;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingLabel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

class WaOrderFulfillmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_wa_order_can_be_created_with_custom_items_and_gps_coordinates()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $orderNumber = Order::generateWaOrderNumber();
        $this->assertStringStartsWith('INV-WA-', $orderNumber);

        $order = Order::create([
            'order_number' => $orderNumber,
            'order_source' => 'whatsapp',
            'shipping_name' => 'Bpk. Hendra Saputra',
            'shipping_phone' => '081234567890',
            'shipping_email' => 'hendra.proyek@example.com',
            'shipping_address' => 'Jl. Kawasan Industri Cikarang Blok B No. 12',
            'shipping_city' => 'Bekasi',
            'shipping_province' => 'Jawa Barat',
            'shipping_postal_code' => '17530',
            'shipping_latitude' => -6.28456,
            'shipping_longitude' => 107.15234,
            'fulfillment_type' => 'po_batch',
            'batch_count' => 3,
            'fulfillment_notes' => 'Rit 1: 1.000 pcs, Rit 2: 1.000 pcs, Rit 3: 1.000 pcs',
            'courier' => 'Armada Truk IndoRoster',
            'courier_phone' => '081399887766',
            'status' => 'processing',
            'payment_status' => 'paid',
            'subtotal' => 45000000,
            'shipping_cost' => 1500000,
            'discount_amount' => 500000,
            'grand_total' => 46000000,
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'product_id' => null, // Custom Manual Item
            'is_custom_item' => true,
            'product_name' => 'Roster Custom Motif Kawung 20x20 Abu K-200',
            'custom_variant_name' => 'Abu Natural Tebal 10cm',
            'product_price' => 15000,
            'quantity' => 3000,
            'subtotal' => 45000000,
            'item_notes' => 'Palet kayu rapi strapping plastik',
        ]);

        $this->assertDatabaseHas('orders', [
            'order_number' => $orderNumber,
            'order_source' => 'whatsapp',
            'shipping_name' => 'Bpk. Hendra Saputra',
            'fulfillment_type' => 'po_batch',
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => null,
            'product_name' => 'Roster Custom Motif Kawung 20x20 Abu K-200',
            'custom_variant_name' => 'Abu Natural Tebal 10cm',
        ]);

        $this->assertEquals('Abu Natural Tebal 10cm', $item->product_variant_name);
    }

    public function test_wa_order_generates_distinct_invoice_and_shipping_label_numbers()
    {
        $order = Order::create([
            'order_number' => Order::generateWaOrderNumber(),
            'order_source' => 'whatsapp',
            'shipping_name' => 'Ibu Maya Lestari',
            'shipping_phone' => '081298765432',
            'shipping_address' => 'Perumahan Villa Indah Blok C3 No. 5',
            'shipping_city' => 'Bogor',
            'status' => 'processing',
            'payment_status' => 'paid',
            'subtotal' => 7500000,
            'shipping_cost' => 350000,
            'discount_amount' => 0,
            'grand_total' => 7850000,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => null,
            'is_custom_item' => true,
            'product_name' => 'Roster Minimalis Motif Kotak L20 Putih',
            'custom_variant_name' => 'Putih Teraso',
            'product_price' => 15000,
            'quantity' => 500,
            'subtotal' => 7500000,
        ]);

        // Trigger observer if needed
        $invoice = Invoice::create([
            'order_id' => $order->id,
            'invoice_number' => Invoice::generateWaInvoiceNumber(),
            'invoice_date' => now(),
            'subtotal' => $order->subtotal,
            'shipping_cost' => $order->shipping_cost,
            'discount_amount' => $order->discount_amount,
            'grand_total' => $order->grand_total,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $this->assertStringStartsWith('INV-WA-', $invoice->invoice_number);

        $labelNumber = ShippingLabel::generateWaLabelNumber();
        $this->assertStringStartsWith('SJ-WA-', $labelNumber);
    }

    public function test_wa_order_can_be_tracked_on_website()
    {
        $order = Order::create([
            'order_number' => 'INV-WA-20260828-9999',
            'order_source' => 'whatsapp',
            'shipping_name' => 'Bpk. Gunawan',
            'shipping_phone' => '081233445566',
            'shipping_address' => 'Jl. Pahlawan No. 10',
            'shipping_city' => 'Bandung',
            'shipping_latitude' => -6.9175,
            'shipping_longitude' => 107.6191,
            'status' => 'shipped',
            'payment_status' => 'paid',
            'subtotal' => 3000000,
            'shipping_cost' => 200000,
            'discount_amount' => 0,
            'grand_total' => 3200000,
            'courier' => 'Armada Truk Colt Diesel',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => null,
            'is_custom_item' => true,
            'product_name' => 'Bata Tempel Expose Terakota',
            'custom_variant_name' => 'Merah Natural',
            'product_price' => 3000,
            'quantity' => 1000,
            'subtotal' => 3000000,
        ]);

        Livewire::test(OrderTracking::class)
            ->set('searchQuery', 'INV-WA-20260828-9999')
            ->set('contactQuery', '081233445566')
            ->call('track')
            ->assertHasNoErrors()
            ->assertSee('INV-WA-20260828-9999')
            ->assertSee('Bata Tempel Expose Terakota')
            ->assertSee('Merah Natural');
    }

    public function test_wa_order_invoice_pdf_view_renders_without_errors()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $order = Order::create([
            'order_number' => 'INV-WA-20260828-8888',
            'order_source' => 'whatsapp',
            'shipping_name' => 'CV Karya Cipta',
            'shipping_phone' => '081122334455',
            'shipping_address' => 'Komplek Pergudangan Surya No. 8',
            'shipping_city' => 'Tangerang',
            'shipping_province' => 'Banten',
            'shipping_postal_code' => '15111',
            'status' => 'processing',
            'payment_status' => 'paid',
            'subtotal' => 15000000,
            'shipping_cost' => 500000,
            'discount_amount' => 250000,
            'grand_total' => 15250000,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => null,
            'is_custom_item' => true,
            'product_name' => 'Roster Angin Minimalis Tipe X',
            'custom_variant_name' => 'Abu Semen',
            'product_price' => 15000,
            'quantity' => 1000,
            'subtotal' => 15000000,
            'item_notes' => 'Khusus lantai 2 gedung',
        ]);

        $invoice = Invoice::create([
            'order_id' => $order->id,
            'invoice_number' => 'INV-WA-20260828-8888',
            'invoice_date' => now(),
            'subtotal' => $order->subtotal,
            'shipping_cost' => $order->shipping_cost,
            'discount_amount' => $order->discount_amount,
            'grand_total' => $order->grand_total,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $this->actingAs($admin);

        $url = URL::signedRoute('print.invoice', ['invoice' => $invoice->id]);
        $response = $this->get($url);

        $response->assertStatus(200);
        $this->assertNotEmpty($response->getContent());
    }

    public function test_wa_order_resource_queries_only_whatsapp_orders()
    {
        Order::create([
            'order_number' => 'INV-20260828-0001',
            'order_source' => 'web',
            'shipping_name' => 'Web Customer',
            'shipping_phone' => '081200000001',
            'shipping_address' => 'Web Address',
            'subtotal' => 100000,
            'grand_total' => 100000,
        ]);

        Order::create([
            'order_number' => 'INV-WA-20260828-0002',
            'order_source' => 'whatsapp',
            'shipping_name' => 'WA Customer',
            'shipping_phone' => '081200000002',
            'shipping_address' => 'WA Address',
            'subtotal' => 200000,
            'grand_total' => 200000,
        ]);

        $webOrders = OrderResource::getEloquentQuery()->pluck('order_number')->toArray();
        $this->assertContains('INV-20260828-0001', $webOrders);
        $this->assertNotContains('INV-WA-20260828-0002', $webOrders);

        $waOrders = WaOrderResource::getEloquentQuery()->pluck('order_number')->toArray();
        $this->assertContains('INV-WA-20260828-0002', $waOrders);
        $this->assertNotContains('INV-20260828-0001', $waOrders);
    }

    public function test_wa_order_stock_deduction_and_total_sold()
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $this->actingAs($admin);

        $category = Category::create([
            'name' => 'Roster Beton',
            'slug' => 'roster-beton',
            'is_active' => true,
        ]);

        $productReady = Product::create([
            'category_id' => $category->id,
            'name' => 'Roster Beton Motif Bintang',
            'slug' => 'roster-beton-motif-bintang',
            'description' => 'Deskripsi roster motif bintang',
            'price' => 12000,
            'stock' => 500,
            'total_sold' => 0,
            'is_active' => true,
        ]);

        $productPO = Product::create([
            'category_id' => $category->id,
            'name' => 'Roster Beton Custom PO',
            'slug' => 'roster-beton-custom-po',
            'description' => 'Deskripsi roster custom PO',
            'price' => 15000,
            'stock' => 200,
            'total_sold' => 10,
            'is_active' => true,
        ]);

        // 1. Create WA Order Ready Stock -> Stock should decrease from 500 to 400
        $readyTest = Livewire::test(CreateWaOrder::class);
        $readyUuid = array_key_first($readyTest->get('data.items'));
        $readyTest
            ->set("data.items.{$readyUuid}.is_custom_item", 0)
            ->set("data.items.{$readyUuid}.product_id", $productReady->id)
            ->set("data.items.{$readyUuid}.product_name", $productReady->name)
            ->set("data.items.{$readyUuid}.product_price", 12000)
            ->set("data.items.{$readyUuid}.quantity", 100)
            ->set("data.items.{$readyUuid}.subtotal", 1200000)
            ->fillForm([
                'order_source' => 'whatsapp',
                'shipping_name' => 'Pak Budi Ready Stock',
                'shipping_phone' => '081234567890',
                'shipping_address' => 'Jl. Merdeka No. 1',
                'shipping_province' => 'JAWA BARAT',
                'shipping_city' => 'KABUPATEN PURWAKARTA',
                'shipping_district' => 'PURWAKARTA',
                'fulfillment_type' => 'ready_stock',
                'status' => 'pending_payment',
                'payment_status' => 'paid',
                'subtotal' => 1200000,
                'grand_total' => 1200000,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $productReady->refresh();
        $this->assertEquals(400, $productReady->stock, 'Ready stock product stock should be decremented by 100');
        $this->assertEquals(100, $productReady->total_sold, 'Ready stock product total_sold should be incremented by 100');

        // 2. Create WA Order PO Single -> Stock should NOT decrease (stays 200), total_sold should increase by 50 (from 10 to 60)
        $poTest = Livewire::test(CreateWaOrder::class);
        $poUuid = array_key_first($poTest->get('data.items'));
        $poTest
            ->set("data.items.{$poUuid}.is_custom_item", 0)
            ->set("data.items.{$poUuid}.product_id", $productPO->id)
            ->set("data.items.{$poUuid}.product_name", $productPO->name)
            ->set("data.items.{$poUuid}.product_price", 15000)
            ->set("data.items.{$poUuid}.quantity", 50)
            ->set("data.items.{$poUuid}.subtotal", 750000)
            ->fillForm([
                'order_source' => 'whatsapp',
                'shipping_name' => 'Pak Joko PO Produksi',
                'shipping_phone' => '081234567899',
                'shipping_address' => 'Jl. Sudirman No. 2',
                'shipping_province' => 'JAWA BARAT',
                'shipping_city' => 'KABUPATEN PURWAKARTA',
                'shipping_district' => 'PURWAKARTA',
                'fulfillment_type' => 'po_single',
                'status' => 'pending_payment',
                'payment_status' => 'paid',
                'subtotal' => 750000,
                'grand_total' => 750000,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $productPO->refresh();
        $this->assertEquals(200, $productPO->stock, 'PO order should NOT reduce stock');
        $this->assertEquals(60, $productPO->total_sold, 'PO order should increase total_sold when paid');
    }
}
