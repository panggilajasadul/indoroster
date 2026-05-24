<?php

namespace App\Filament\Courier\Resources;

use App\Filament\Courier\Resources\OrderResource\Pages;
use App\Filament\Courier\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Tugas Pengiriman';
    protected static ?string $pluralModelLabel = 'Tugas Pengiriman';
    protected static ?string $modelLabel = 'Pesanan';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('courier_id', auth()->id());
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shipping_name')
                    ->label('Penerima')
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_address')
                    ->label('Alamat Lengkap')
                    ->limit(50),
                Tables\Columns\TextColumn::make('shipping_phone')
                    ->label('No. HP'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'processing' => 'warning',
                        'shipped' => 'primary',
                        'completed' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim (Dalam Perjalanan)',
                        'completed' => 'Selesai Dikirim',
                        default => $state,
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'processing' => 'Belum Diambil',
                        'shipped' => 'Dalam Perjalanan',
                        'completed' => 'Selesai',
                    ])
                    ->default('shipped'),
            ])
            ->actions([
                Tables\Actions\Action::make('complete_delivery')
                    ->label('Selesaikan Pesanan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Order $record) => in_array($record->status, ['processing', 'shipped']))
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
                Tables\Actions\Action::make('wa_customer')
                    ->label('WA Pelanggan')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('info')
                    ->url(function (Order $record) {
                        $phone = preg_replace('/[^0-9]/', '', $record->shipping_phone);
                        if (str_starts_with($phone, '0')) {
                            $phone = '62' . substr($phone, 1);
                        }
                        return 'https://wa.me/' . $phone . '?text=Halo%20' . urlencode($record->shipping_name) . ',%20saya%20kurir%20Indoroster%20ingin%20mengirimkan%20pesanan%20Anda.';
                    })
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
        ];
    }
}
