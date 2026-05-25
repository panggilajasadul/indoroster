<?php

namespace App\Filament\Courier\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Forms;

class LatestDeliveryTasks extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    
    protected static ?string $heading = 'Perlu Dikirim Segera';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Order::query()
                    ->where('courier_id', auth()->id())
                    ->whereIn('status', ['processing', 'shipped'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\ViewColumn::make('id')
                    ->view('filament.courier.order-card')
            ])
            ->contentGrid([
                'default' => 1,
                'md' => 2,
                'xl' => 3,
            ])
            ->actions([
                Tables\Actions\Action::make('complete_delivery')
                    ->label('Selesaikan')
                    ->icon('heroicon-o-check-circle')
                    ->color('primary')
                    ->form([
                        Forms\Components\FileUpload::make('delivery_photo_path')
                            ->label('Foto Bukti Kirim (Gunakan Kamera)')
                            ->directory('delivery-proofs')
                            ->image()
                            ->extraInputAttributes(['capture' => 'environment'])
                            ->required(),
                    ])
                    ->action(function (Order $record, array $data) {
                        $record->update([
                            'status' => 'completed',
                            'completed_at' => now(),
                            'delivery_photo_path' => $data['delivery_photo_path'],
                        ]);
                        
                        if ($record->user_id && $record->user) {
                            $record->user->notify(new \App\Notifications\OrderStatusUpdated($record, 'Selesai'));
                        }

                        try {
                            $email = $record->shipping_email ?? $record->user?->email;
                            if ($email) {
                                if (function_exists('defer')) {
                                    defer(fn () => \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\OrderStatusMail($record, 'completed')));
                                } else {
                                    \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\OrderStatusMail($record, 'completed'));
                                }
                            }
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Failed to send status email: ' . $e->getMessage());
                        }
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Pesanan Selesai')
                            ->body('Bukti pengiriman berhasil diupload.')
                            ->success()
                            ->send();
                    }),
            ])
            ->paginated(false);
    }
}
