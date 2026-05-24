<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use App\Models\Order;
use App\Observers\OrderObserver;

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$orders = Order::where('payment_status', 'paid')->get();
$observer = new OrderObserver();

echo "Memulai perbaikan data...\n";
$count = 0;

    /** @var \App\Models\Order $order */
    foreach ($orders as $order) {
    // Simulasi status dirty agar observer mau jalan (isDirty check di observer biasanya butuh model yang sedang diupdate, 
    // tapi kita bisa panggil method internalnya langsung atau buat mock dirty state)
    // Karena kita panggil method di observer yang mengecek exists(), kita bisa panggil manual method privatnya jika kita modifikasi observernya 
    // atau panggil saja method yang kita inginkan.
    
    // Namun di observer tadi kita pakai isDirty. Untuk perbaikan massal, kita panggil logic-nya saja.
    
    // Kita panggil method 'updated' tapi kita bypass isDirty check dengan memanggil logic pembuatannya.
    // Cara termudah: panggil method updated dan pastikan isDirty mengembalikan true untuk field tersebut.
    // Tapi karena ini script satu kali, kita panggil logic creation-nya saja.
    
    $created = false;
    
    if (!$order->invoice()->exists()) {
        \App\Models\Invoice::create([
            'order_id' => $order->id,
            'invoice_number' => \App\Models\Invoice::generateInvoiceNumber(),
            'invoice_date' => $order->created_at,
            'subtotal' => $order->subtotal,
            'shipping_cost' => $order->shipping_cost,
            'discount_amount' => $order->discount_amount,
            'grand_total' => $order->grand_total,
            'status' => 'paid',
            'paid_at' => $order->paid_at ?? $order->updated_at,
        ]);
        $created = true;
    }
    
    if (!$order->payments()->whereIn('status', ['settlement', 'capture', 'manual'])->exists()) {
        \App\Models\Payment::create([
            'order_id' => $order->id,
            'transaction_id' => 'FIX-' . strtoupper(\Illuminate\Support\Str::random(10)),
            'payment_type' => 'manual',
            'gross_amount' => $order->grand_total,
            'status' => 'settlement',
            'paid_at' => $order->paid_at ?? $order->updated_at,
            'raw_response' => ['method' => 'one_time_fix_script']
        ]);
        $created = true;
    }
    
    if ($created) {
        $count++;
        echo "Berhasil memperbaiki Order: {$order->order_number}\n";
    }
}

echo "Selesai. Total {$count} pesanan diperbaiki.\n";
