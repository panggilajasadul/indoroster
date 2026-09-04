<?php

namespace App\Observers;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ShippingLabel;
use Illuminate\Support\Str;

class OrderObserver
{
    /**
     * Handle the Order "saving" event.
     */
    public function saving(Order $order): void
    {
        // Pastikan jika pembayaran sudah paid, status pesanan otomatis minimal processing
        if ($order->payment_status === 'paid' && in_array($order->status, ['draft', 'pending_payment'])) {
            $order->status = 'processing';
            if (! $order->paid_at) {
                $order->paid_at = now();
            }
        }
    }

    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        if ($order->payment_status === 'paid') {
            $this->ensureInvoiceExists($order);
            $this->ensurePaymentExists($order);
            $this->incrementProductTotalSold($order);
            $this->ensureShippingLabelExists($order);
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // 1. Jika status pembayaran berubah menjadi 'paid'
        if ($order->isDirty('payment_status') && $order->payment_status === 'paid') {
            $this->ensureInvoiceExists($order);
            $this->ensurePaymentExists($order);
            $this->incrementProductTotalSold($order);

            if (in_array($order->status, ['draft', 'pending_payment'])) {
                $order->status = 'processing';
                $order->saveQuietly();
            }

            $this->ensureShippingLabelExists($order);
        }

        // 2. Jika pesanan yang tadinya lunas dibatalkan / refund
        if ($order->isDirty('payment_status') && $order->getOriginal('payment_status') === 'paid' && in_array($order->payment_status, ['refunded', 'cancelled', 'failed'])) {
            $this->decrementProductTotalSold($order);
        }

        // 3. Jika status pesanan berubah menjadi 'processing'
        if ($order->isDirty('status') && $order->status === 'processing') {
            $this->ensureShippingLabelExists($order);
        }
    }

    /**
     * Pastikan invoice sudah dibuat.
     */
    private function ensureInvoiceExists(Order $order): void
    {
        if (! $order->invoice()->exists()) {
            $invoiceNumber = $order->order_source === 'whatsapp'
                ? Invoice::generateWaInvoiceNumber()
                : Invoice::generateInvoiceNumber();

            Invoice::create([
                'order_id' => $order->id,
                'invoice_number' => $invoiceNumber,
                'invoice_date' => now(),
                'subtotal' => $order->subtotal,
                'shipping_cost' => $order->shipping_cost,
                'discount_amount' => $order->discount_amount,
                'grand_total' => $order->grand_total,
                'payment_scheme' => $order->payment_scheme ?: 'full',
                'down_payment_amount' => $order->down_payment_amount ?: 0,
                'remaining_balance' => $order->remaining_balance ?: 0,
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
        if (! $order->payments()->whereIn('status', ['settlement', 'capture', 'manual'])->exists()) {
            Payment::create([
                'order_id' => $order->id,
                'transaction_id' => 'AUTO-'.strtoupper(Str::random(10)),
                'payment_type' => 'manual',
                'gross_amount' => $order->grand_total,
                'status' => 'settlement',
                'paid_at' => now(),
                'raw_response' => ['method' => 'auto_observer_generation'],
            ]);
        }
    }

    /**
     * Pastikan shipping label sudah dibuat saat diproses.
     */
    private function ensureShippingLabelExists(Order $order): void
    {
        if (! $order->shippingLabel()->exists()) {
            $sender = ShippingLabel::getDefaultSender();

            $totalWeight = 0;
            $productList = [];
            foreach ($order->items()->with('product')->get() as $item) {
                $weight = $item->product?->weight ?? 0;
                $totalWeight += $weight * $item->quantity;
                $variantName = $item->product_variant_name ? " ({$item->product_variant_name})" : '';
                $productList[] = ($item->product_name ?? $item->product?->name ?? 'Produk').$variantName.' x'.$item->quantity;
            }
            $packageDesc = implode(', ', $productList);

            $labelNumber = $order->order_source === 'whatsapp'
                ? ShippingLabel::generateWaLabelNumber()
                : ShippingLabel::generateLabelNumber();

            ShippingLabel::create(array_merge($sender, [
                'order_id' => $order->id,
                'label_number' => $labelNumber,
                'courier' => $order->courier ?? 'Armada Pabrik',
                'tracking_number' => $order->tracking_number ?? 'TRK-'.strtoupper(Str::random(8)),
                'recipient_name' => $order->shipping_name,
                'recipient_phone' => $order->shipping_phone,
                'recipient_address' => $order->shipping_address,
                'recipient_city' => $order->shipping_city,
                'recipient_postal_code' => $order->shipping_postal_code,
                'recipient_latitude' => $order->shipping_latitude,
                'recipient_longitude' => $order->shipping_longitude,
                'total_items' => $order->items()->sum('quantity'),
                'total_weight' => $totalWeight,
                'package_description' => $packageDesc,
            ]));
        }
    }

    /**
     * Otomatis tambahkan total_sold pada produk saat pesanan lunas.
     */
    private function incrementProductTotalSold(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product_id && $item->quantity > 0) {
                Product::where('id', $item->product_id)->increment('total_sold', (int) $item->quantity);
            }
        }
    }

    /**
     * Kurangi total_sold jika pesanan yang sebelumnya lunas dibatalkan.
     */
    private function decrementProductTotalSold(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product_id && $item->quantity > 0) {
                $product = Product::find($item->product_id);
                if ($product && $product->total_sold >= $item->quantity) {
                    $product->decrement('total_sold', (int) $item->quantity);
                }
            }
        }
    }
}
