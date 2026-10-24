<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\ShippingLabel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class InvoicePrintTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $customer;

    private User $otherUser;

    private Order $order;

    private Invoice $invoice;

    private ShippingLabel $shippingLabel;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Users
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->customer = User::factory()->create([
            'role' => 'customer',
            'is_active' => true,
        ]);

        $this->otherUser = User::factory()->create([
            'role' => 'customer',
            'is_active' => true,
        ]);

        // 2. Create Order
        $this->order = Order::create([
            'user_id' => $this->customer->id,
            'order_number' => 'INV-20260521-0001',
            'shipping_email' => $this->customer->email,
            'status' => 'paid',
            'payment_status' => 'paid',
            'subtotal' => 500000,
            'shipping_cost' => 50000,
            'discount_amount' => 0,
            'grand_total' => 550000,
            'shipping_name' => $this->customer->name,
            'shipping_phone' => '08123456789',
            'shipping_address' => 'Jl. Kebon Jeruk No. 10',
            'shipping_city' => 'Jakarta Barat',
            'shipping_province' => 'DKI Jakarta',
            'shipping_postal_code' => '11530',
        ]);

        // 3. Create Invoice
        $this->invoice = Invoice::create([
            'order_id' => $this->order->id,
            'invoice_number' => 'INV/2026/05/0001',
            'invoice_date' => now(),
            'subtotal' => 500000,
            'shipping_cost' => 50000,
            'discount_amount' => 0,
            'grand_total' => 550000,
            'status' => 'paid',
        ]);

        // 4. Create Shipping Label
        $this->shippingLabel = ShippingLabel::create([
            'order_id' => $this->order->id,
            'label_number' => 'SL-20260521-0001',
            'courier' => 'JNE',
            'sender_phone' => '08123456789',
            'sender_address' => 'Plered, Purwakarta',
            'recipient_name' => $this->customer->name,
            'recipient_phone' => '08123456789',
            'recipient_address' => 'Jl. Kebon Jeruk No. 10',
            'recipient_city' => 'Jakarta Barat',
            'recipient_postal_code' => '11530',
            'total_items' => 50,
            'total_weight' => 25.5,
        ]);
    }

    public function test_admin_can_print_any_invoice(): void
    {
        $url = route('print.invoice', $this->invoice);
        $response = $this->actingAs($this->admin)->get($url);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_owner_can_print_own_invoice_without_signature(): void
    {
        $response = $this->actingAs($this->customer)
            ->get(route('print.invoice', $this->invoice));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_other_user_cannot_print_invoice(): void
    {
        $response = $this->actingAs($this->otherUser)
            ->get(route('print.invoice', $this->invoice));

        $response->assertStatus(403);
    }

    public function test_guest_can_print_invoice_with_signed_url(): void
    {
        $signedUrl = URL::signedRoute('print.invoice', ['invoice' => $this->invoice->id]);

        $response = $this->get($signedUrl);

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_guest_cannot_print_invoice_without_signed_url(): void
    {
        $response = $this->get(route('print.invoice', $this->invoice));

        $response->assertStatus(403);
    }

    public function test_admin_can_print_order(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('print.order', $this->order));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_non_admin_cannot_print_order(): void
    {
        $response = $this->actingAs($this->customer)
            ->get(route('print.order', $this->order));

        $response->assertStatus(403);
    }

    public function test_admin_can_print_shipping_label(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('print.shipping-label', $this->shippingLabel));

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_print_shipping_label(): void
    {
        $response = $this->actingAs($this->customer)
            ->get(route('print.shipping-label', $this->shippingLabel));

        $response->assertStatus(403);
    }

    public function test_invoice_displays_custom_title_and_dp_10_breakdown(): void
    {
        $order = Order::create([
            'order_number' => 'INV-WA-20261024-0010',
            'order_source' => 'whatsapp',
            'status' => 'pending_payment',
            'payment_status' => 'unpaid',
            'payment_scheme' => 'dp_10_90',
            'subtotal' => 10000000,
            'shipping_type' => 'paid',
            'shipping_cost' => 500000,
            'discount_amount' => 0,
            'grand_total' => 10500000,
            'down_payment_amount' => 1050000,
            'remaining_balance' => 9450000,
            'shipping_name' => 'Budi Santoso',
            'shipping_phone' => '081299887766',
            'shipping_address' => 'Jl. Proyek Perumahan Indah No. 5',
            'shipping_city' => 'Bandung',
            'shipping_province' => 'Jawa Barat',
        ]);

        $invoice = Invoice::create([
            'order_id' => $order->id,
            'invoice_number' => 'INV-WA-20261024-0010',
            'invoice_date' => now(),
            'document_title' => 'INVOICE & SURAT TAGIHAN PEMBAYARAN DP',
            'subtotal' => 10000000,
            'shipping_type' => 'paid',
            'shipping_cost' => 500000,
            'discount_amount' => 0,
            'grand_total' => 10500000,
            'payment_scheme' => 'dp_10_90',
            'down_payment_amount' => 1050000,
            'remaining_balance' => 9450000,
            'status' => 'sent',
            'show_payment_schedule' => true,
            'show_bank_accounts' => true,
            'document_terms' => '1. Klausul khusus proyek custom.',
        ]);

        $invoice->load(['order.items.product', 'order.items.variant', 'order.user']);

        // Assert rendered Blade HTML
        $html = view('print.invoice', ['invoice' => $invoice, 'paymentStage' => null])->render();
        $this->assertStringContainsString('INVOICE &amp; SURAT TAGIHAN PEMBAYARAN DP', $html);
        $this->assertStringContainsString('Tahap 1: Tagihan DP Awal (10%)', $html);
        $this->assertStringContainsString('Tahap 2: Sisa Pelunasan (90%)', $html);
        $this->assertStringContainsString('1.050.000', $html);
        $this->assertStringContainsString('9.450.000', $html);
        $this->assertStringContainsString('Klausul khusus proyek custom.', $html);
        $this->assertStringContainsString('4356-01-009396-50-2', $html); // Rekening BRI
        $this->assertStringContainsString('024-123-4567', $html); // Rekening BCA
        $this->assertStringContainsString('131-00-1234567-8', $html); // Rekening Mandiri

        // Assert PDF generation endpoint works
        $url = URL::signedRoute('print.invoice', ['invoice' => $invoice->id]);
        $response = $this->get($url);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_invoice_displays_free_shipping_and_dp_30_breakdown(): void
    {
        $order = Order::create([
            'order_number' => 'INV-WA-20261024-0030',
            'order_source' => 'whatsapp',
            'status' => 'pending_payment',
            'payment_status' => 'unpaid',
            'payment_scheme' => 'dp_30_70',
            'subtotal' => 20000000,
            'shipping_type' => 'free',
            'shipping_cost' => 0,
            'discount_amount' => 0,
            'grand_total' => 20000000,
            'down_payment_amount' => 6000000,
            'remaining_balance' => 14000000,
            'shipping_name' => 'PT Bangun Megah',
            'shipping_phone' => '081299887711',
            'shipping_address' => 'Kawasan Industri GIIC Cikarang',
            'shipping_city' => 'Bekasi',
            'shipping_province' => 'Jawa Barat',
        ]);

        $invoice = Invoice::create([
            'order_id' => $order->id,
            'invoice_number' => 'INV-WA-20261024-0030',
            'invoice_date' => now(),
            'document_title' => 'INVOICE TAGIHAN PENJUALAN RESMI',
            'subtotal' => 20000000,
            'shipping_type' => 'free',
            'shipping_cost' => 0,
            'discount_amount' => 0,
            'grand_total' => 20000000,
            'payment_scheme' => 'dp_30_70',
            'down_payment_amount' => 6000000,
            'remaining_balance' => 14000000,
            'status' => 'sent',
            'show_payment_schedule' => true,
        ]);

        $invoice->load(['order.items.product', 'order.items.variant', 'order.user']);

        // Assert rendered Blade HTML
        $html = view('print.invoice', ['invoice' => $invoice, 'paymentStage' => null])->render();
        $this->assertStringContainsString('GRATIS ONGKIR', $html);
        $this->assertStringContainsString('Tahap 1: Tagihan DP Awal (30%)', $html);
        $this->assertStringContainsString('Tahap 2: Sisa Pelunasan (70%)', $html);
        $this->assertStringContainsString('6.000.000', $html);
        $this->assertStringContainsString('14.000.000', $html);
        $this->assertStringNotContainsString('SURAT ESTIMASI PENAWARAN HARGA (BISA NEGO VOLUME)', $html);

        // Assert PDF generation endpoint works
        $url = URL::signedRoute('print.invoice', ['invoice' => $invoice->id]);
        $response = $this->get($url);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
