<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\ShippingLabel;
use Illuminate\Support\Str;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // 1. Jika status pembayaran berubah menjadi 'paid'
        if ($order->isDirty('payment_status') && $order->payment_status === 'paid') {
            $this->ensureInvoiceExists($order);
            $this->ensurePaymentExists($order);
        }

        // 2. Jika status pesanan berubah menjadi 'processing'
        if ($order->isDirty('status') && $order->status === 'processing') {
            $this->ensureShippingLabelExists($order);
        }
    }

    /**
     * Pastikan invoice sudah dibuat.
     */
    private function ensureInvoiceExists(Order $order): void
    {
        if (!$order->invoice()->exists()) {
            Invoice::create([
                'order_id' => $order->id,
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'invoice_date' => now(),
                'subtotal' => $order->subtotal,
                'shipping_cost' => $order->shipping_cost,
                'discount_amount' => $order->discount_amount,
                'grand_total' => $order->grand_total,
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }
    }

    /**
     * Pastikan record pembayaran sudah dibuat.
     */
    private function ensurePaymentExists(Order $order): void
    {
        if (!$order->payments()->whereIn('status', ['settlement', 'capture', 'manual'])->exists()) {
            Payment::create([
                'order_id' => $order->id,
                'transaction_id' => 'AUTO-' . strtoupper(Str::random(10)),
                'payment_type' => 'manual',
                'gross_amount' => $order->grand_total,
                'status' => 'settlement',
                'paid_at' => now(),
                'raw_response' => ['method' => 'auto_observer_generation']
            ]);
        }
    }

    /**
     * Pastikan shipping label sudah dibuat saat diproses.
     */
    private function ensureShippingLabelExists(Order $order): void
    {
        if (!$order->shippingLabel()->exists()) {
            $sender = ShippingLabel::getDefaultSender();
            
            $totalWeight = 0;
            $productList = [];
            foreach ($order->items()->with('product')->get() as $item) {
                $weight = $item->product->weight ?? 0;
                $totalWeight += $weight * $item->quantity;
                $variantName = $item->product_variant_name ? " ({$item->product_variant_name})" : '';
                $productList[] = ($item->product_name ?? $item->product?->name ?? 'Produk') . $variantName . ' x' . $item->quantity;
            }
            $packageDesc = implode(', ', $productList);

            ShippingLabel::create(array_merge($sender, [
                'order_id' => $order->id,
                'label_number' => ShippingLabel::generateLabelNumber(),
                'courier' => $order->courier ?? 'Armada Pabrik',
                'tracking_number' => $order->tracking_number ?? 'TRK-' . strtoupper(Str::random(8)),
                'recipient_name' => $order->shipping_name,
                'recipient_phone' => $order->shipping_phone,
                'recipient_address' => $order->shipping_address,
                'recipient_city' => $order->shipping_city,
                'recipient_postal_code' => $order->shipping_postal_code,
                'total_items' => $order->items()->sum('quantity'),
                'total_weight' => $totalWeight,
                'package_description' => $packageDesc,
            ]));
        }
    }
}
