<?php

namespace Tests\Feature;

use App\Models\ManualDocument;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WaOrderManualDocumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_b2b_bast_document_for_order()
    {
        $user = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $order = Order::create([
            'order_number' => 'ORD-WA-20260903-0001',
            'shipping_name' => 'PT. Mega Konstruksi Jaya',
            'shipping_phone' => '081234567890',
            'shipping_address' => 'Jl. Proyek Sudirman No. 88',
            'shipping_city' => 'Jakarta Selatan',
            'shipping_province' => 'DKI Jakarta',
            'shipping_cost' => 0,
            'subtotal' => 15000000,
            'grand_total' => 15000000,
            'status' => 'pending_payment',
            'payment_status' => 'unpaid',
            'payment_scheme' => 'full',
            'order_source' => 'whatsapp',
        ]);

        $doc = ManualDocument::create([
            'document_number' => 'BAST/IR-PWK/2026/0001',
            'type' => 'bast',
            'client_name' => $order->shipping_name,
            'client_address' => $order->shipping_address.', '.$order->shipping_city,
            'client_phone' => $order->shipping_phone,
            'items' => [
                [
                    'product_name' => 'Roster Beton Minimalis Motif Kotak (20x20 cm)',
                    'quantity' => 1000,
                    'price' => 15000,
                    'total' => 15000000,
                ],
            ],
            'subtotal' => 15000000,
            'discount' => 0,
            'has_tax' => false,
            'tax_amount' => 0,
            'grand_total' => 15000000,
            'document_date' => now(),
            'issued_by' => 'Admin Pabrik',
            'status' => 'final',
            'extra_data' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'category' => 'b2b',
            ],
        ]);

        $this->assertDatabaseHas('manual_documents', [
            'document_number' => 'BAST/IR-PWK/2026/0001',
            'type' => 'bast',
            'client_name' => 'PT. Mega Konstruksi Jaya',
        ]);

        $this->assertEquals('Berita Acara Serah Terima (BAST)', $doc->getTypeLabel());

        $this->actingAs($user);
        $response = $this->get(route('print.manual-document', $doc->id));
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_can_create_export_commercial_invoice_document()
    {
        $user = User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $doc = ManualDocument::create([
            'document_number' => 'EXP-INV/IR-PWK/2026/0002',
            'type' => 'commercial_invoice',
            'client_name' => 'Singapore Breeze Block Pte Ltd',
            'client_address' => '10 Marina Boulevard, Singapore',
            'client_phone' => '+6591234567',
            'items' => [
                [
                    'product_name' => 'Architectural Breeze Blocks - Geometric Quad (200x200x100 mm)',
                    'quantity' => 3000,
                    'price' => 2.5,
                    'total' => 7500,
                ],
            ],
            'subtotal' => 7500,
            'discount' => 0,
            'has_tax' => false,
            'tax_amount' => 0,
            'grand_total' => 7500,
            'document_date' => now(),
            'issued_by' => 'Export Desk IndoRoster',
            'status' => 'final',
            'extra_data' => [
                'category' => 'export',
                'port_of_loading' => 'Port of Tanjung Priok, Jakarta',
                'port_of_discharge' => 'Port of Singapore',
                'incoterms' => 'FOB Jakarta',
            ],
        ]);

        $this->assertEquals('Commercial Invoice (Ekspor)', $doc->getTypeLabel());

        $this->actingAs($user);
        $response = $this->get(route('print.manual-document', $doc->id));
        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }
}
