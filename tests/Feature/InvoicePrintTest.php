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
}
