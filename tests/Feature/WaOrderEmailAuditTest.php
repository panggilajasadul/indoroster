<?php

namespace Tests\Feature;

use App\Mail\OrderStatusMail;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderBatch;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class WaOrderEmailAuditTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        SiteSetting::setValue('order_mode', 'whatsapp');
    }

    public function test_all_5_email_stages_render_and_send_successfully()
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'pembeli@example.com',
            'phone' => '081234567890',
        ]);

        $category = Category::create([
            'name' => 'Roster Beton',
            'slug' => 'roster-beton',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Roster Minimalis Nako',
            'slug' => 'roster-minimalis-nako',
            'sku' => 'RST-001',
            'description' => 'Roster cetak tumbuk padat plat baja presisi.',
            'price' => 15000,
            'stock' => 1000,
            'is_active' => true,
        ]);

        $order = Order::create([
            'order_number' => 'INV-WA-20260903-0099',
            'user_id' => $user->id,
            'status' => 'pending_payment',
            'payment_status' => 'unpaid',
            'fulfillment_type' => 'ready_stock',
            'order_source' => 'whatsapp',
            'subtotal' => 3000000,
            'shipping_cost' => 200000,
            'discount_amount' => 0,
            'grand_total' => 3200000,
            'down_payment_amount' => 1500000,
            'remaining_balance' => 1700000,
            'shipping_name' => 'Budi Santoso',
            'shipping_email' => 'pembeli@example.com',
            'shipping_phone' => '081234567890',
            'shipping_address' => 'Jl. Proyek Perumahan No. 10',
            'shipping_city' => 'Jakarta Selatan',
            'shipping_province' => 'DKI Jakarta',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_price' => $product->price,
            'quantity' => 200,
            'subtotal' => 3000000,
        ]);

        // 1. Test Stage 1: Created (Surat Penawaran & Proforma Tagihan)
        $mail1 = new OrderStatusMail($order, 'created');
        $rendered1 = $mail1->render();
        $this->assertStringContainsString('PENAWARAN', $rendered1);
        $this->assertStringContainsString('0075-01-001962-30-8', $rendered1);
        Mail::to($order->shipping_email)->send($mail1);

        // 2. Test Stage 2: Payment Received (DP)
        $payment = Payment::create([
            'order_id' => $order->id,
            'transaction_id' => 'PAY-WA-0099-1',
            'payment_type' => 'bank_transfer',
            'bank' => 'BRI',
            'gross_amount' => 1500000,
            'status' => 'settlement',
            'paid_at' => now(),
            'raw_response' => ['title' => 'Pembayaran DP 50%'],
        ]);

        $mail2 = new OrderStatusMail($order, 'payment_received', null, $payment);
        $rendered2 = $mail2->render();
        $this->assertStringContainsString('DP TERVERIFIKASI', $rendered2);
        $this->assertStringContainsString('Rp1.500.000', $rendered2);
        Mail::to($order->shipping_email)->send($mail2);

        // 3. Test Stage 3: Processing (Ready Stock / PO Single / PO Batch)
        $mail3 = new OrderStatusMail($order, 'processing');
        $rendered3 = $mail3->render();
        $this->assertStringContainsString('Ready Stock (Gudang Pabrik)', $rendered3);
        Mail::to($order->shipping_email)->send($mail3);

        // 4. Test Stage 4: Shipped (Armada Berangkat)
        $order->update([
            'courier' => 'Pak Joko (Armada Pabrik)',
            'tracking_number' => 'B 9821 TDA',
            'courier_phone' => '081299887766',
        ]);
        $mail4 = new OrderStatusMail($order, 'shipped');
        $rendered4 = $mail4->render();
        $this->assertStringContainsString('B 9821 TDA', $rendered4);
        $this->assertStringContainsString('Pak Joko', $rendered4);
        Mail::to($order->shipping_email)->send($mail4);

        // 5. Test Stage 5: Delivered / Completed
        $mail5 = new OrderStatusMail($order, 'completed');
        $rendered5 = $mail5->render();
        $this->assertStringContainsString('SELESAI (100%)', $rendered5);
        Mail::to($order->shipping_email)->send($mail5);

        // Batch test: Multi-batch email tests
        $orderBatch = OrderBatch::create([
            'order_id' => $order->id,
            'batch_number' => 1,
            'batch_name' => 'Batch 1 - Ritase Truk 1',
            'quantity' => 100,
            'status' => 'shipped',
            'courier_name' => 'Pak Agus',
            'tracking_number' => 'T 8123 AA',
            'courier_phone' => '081377889900',
        ]);

        $mailBatchShipped = new OrderStatusMail($order, 'batch_shipped', $orderBatch);
        $renderedBatch = $mailBatchShipped->render();
        $this->assertStringContainsString('Ritase Truk 1', $renderedBatch);
        $this->assertStringContainsString('T 8123 AA', $renderedBatch);

        Mail::assertSent(OrderStatusMail::class, 5);
    }
}
