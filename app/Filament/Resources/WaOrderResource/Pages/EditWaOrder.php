<?php

namespace App\Filament\Resources\WaOrderResource\Pages;

use App\Filament\Resources\WaOrderResource;
use App\Models\Invoice;
use App\Models\Order;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditWaOrder extends EditRecord
{
    protected static string $resource = WaOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('publish_draft')
                ->label('🚀 Terbitkan Pesanan')
                ->color('success')
                ->visible(fn (Order $record) => $record->status === 'draft')
                ->requiresConfirmation()
                ->modalHeading('Terbitkan Pesanan WhatsApp?')
                ->modalDescription('Pesanan akan diubah statusnya menjadi "Menunggu Pembayaran / DP" dan siap dikirimkan tagihannya ke pembeli.')
                ->action(function (Order $record) {
                    $record->update(['status' => 'pending_payment']);

                    Notification::make()
                        ->title('Pesanan Diterbitkan!')
                        ->body("Pesanan {$record->order_number} berhasil diterbitkan.")
                        ->success()
                        ->send();

                    $this->refreshFormData(['status']);
                }),

            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $grandTotal = (float) ($data['grand_total'] ?? 0);
        $dp = isset($data['down_payment_amount']) && $data['down_payment_amount'] !== null && $data['down_payment_amount'] !== ''
            ? (float) $data['down_payment_amount']
            : 0.0;

        $data['down_payment_amount'] = $dp;
        $data['remaining_balance'] = max(0, $grandTotal - $dp);

        if ($dp >= $grandTotal && $grandTotal > 0) {
            $data['payment_status'] = 'paid';
        }

        return $data;
    }

    protected function afterSave(): void
    {
        /** @var Order $order */
        $order = $this->record;

        // Sinkronisasi data ke Invoice jika invoice sudah ada
        if ($invoice = $order->invoice) {
            $invoice->update([
                'subtotal' => $order->subtotal,
                'shipping_cost' => $order->shipping_cost,
                'discount_amount' => $order->discount_amount,
                'grand_total' => $order->grand_total,
                'payment_scheme' => $order->payment_scheme ?: 'full',
                'down_payment_amount' => $order->down_payment_amount ?: 0,
                'remaining_balance' => $order->remaining_balance ?: 0,
                'status' => $order->payment_status === 'paid' ? 'paid' : ($order->status === 'draft' ? 'draft' : 'sent'),
                'paid_at' => $order->payment_status === 'paid' ? ($invoice->paid_at ?: now()) : null,
            ]);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
