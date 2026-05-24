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
                Order::where('courier_id', auth()->id())
                    ->whereIn('status', ['processing', 'shipped'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Pesanan'),
                Tables\Columns\TextColumn::make('shipping_name')
                    ->label('Penerima'),
                Tables\Columns\TextColumn::make('shipping_address')
                    ->label('Alamat Lengkap')
                    ->limit(50),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'processing' => 'warning',
                        'shipped' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        default => $state,
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('complete_delivery')
                    ->label('Selesaikan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
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
