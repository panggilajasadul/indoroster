<?php

namespace App\Filament\Resources\WaOrderResource\Pages;

use App\Filament\Resources\WaOrderResource;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateWaOrder extends CreateRecord
{
    protected static string $resource = WaOrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['order_source'] = 'whatsapp';

        if (empty($data['order_number'])) {
            $data['order_number'] = Order::generateWaOrderNumber();
        }

        // Kalkulasi DP default jika belum diatur
        $grandTotal = (float) ($data['grand_total'] ?? 0);
        $scheme = $data['payment_scheme'] ?? 'full';

        $dp = isset($data['down_payment_amount']) && $data['down_payment_amount'] !== null && $data['down_payment_amount'] !== ''
            ? (float) $data['down_payment_amount']
            : null;

        if ($dp === null) {
            if ($scheme === 'full') {
                $dp = $grandTotal;
            } elseif ($scheme === 'dp_50_50') {
                $dp = round($grandTotal * 0.5);
            } elseif ($scheme === 'termin_3x') {
                $dp = round($grandTotal * 0.3);
            } else {
                $dp = 0.0;
            }
        }

        $data['down_payment_amount'] = $dp;
        $data['remaining_balance'] = max(0, $grandTotal - $dp);
        $data['payment_status'] = $data['payment_status'] ?? 'unpaid';

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var Order $order */
        $order = $this->record;

        // 1. Kurangi stok HANYA jika tipe pemenuhan Ready Stock dan item berasal dari katalog database
        // (PO Single & PO Batch TIDAK memotong stok karena diproduksi / cetak baru di pabrik)
        if ($order->fulfillment_type === 'ready_stock' || empty($order->fulfillment_type)) {
            foreach ($order->items as $item) {
                if ($item->product_id && $item->quantity > 0) {
                    if ($item->variant) {
                        $item->variant->decrement('stock', (int) $item->quantity);
                    } elseif ($item->product) {
                        $item->product->decrement('stock', (int) $item->quantity);
                    }
                }
            }
        }

        // 2. Tambah total_sold jika status pesanan langsung lunas (Berlaku untuk SEMUA tipe: Ready Stock, PO Single, PO Batch)
        if ($order->payment_status === 'paid') {
            foreach ($order->items as $item) {
                if ($item->product_id && $item->quantity > 0) {
                    Product::where('id', $item->product_id)->increment('total_sold', (int) $item->quantity);
                }
            }
        }

        // 3. Buat Invoice otomatis jika belum ada
        if (! $order->invoice()->exists()) {
            Invoice::create([
                'order_id' => $order->id,
                'invoice_number' => Invoice::generateWaInvoiceNumber(),
                'invoice_date' => now(),
                'subtotal' => $order->subtotal,
                'shipping_cost' => $order->shipping_cost,
                'discount_amount' => $order->discount_amount,
                'grand_total' => $order->grand_total,
                'payment_scheme' => $order->payment_scheme ?: 'full',
                'down_payment_amount' => $order->down_payment_amount ?: 0,
                'remaining_balance' => $order->remaining_balance ?: 0,
                'status' => $order->payment_status === 'paid' ? 'paid' : ($order->status === 'draft' ? 'draft' : 'sent'),
                'paid_at' => $order->payment_status === 'paid' ? now() : null,
            ]);
        }
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label('🚀 Terbitkan Pesanan')
                ->color('success')
                ->action(function () {
                    // Set status to pending_payment if was draft
                    $this->data['status'] = $this->data['status'] === 'draft' ? 'pending_payment' : ($this->data['status'] ?: 'pending_payment');
                    $this->create();
                }),

            Action::make('create_draft')
                ->label('💾 Simpan sebagai Draft')
                ->color('gray')
                ->action(function () {
                    $this->data['status'] = 'draft';
                    $this->create();
                }),

            $this->getCancelFormAction(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
