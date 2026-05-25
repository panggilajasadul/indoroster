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
    protected static ?int $navigationSort = 1;


    public static function getEloquentQuery(): Builder
    {
        // Hanya tampilkan pesanan yang PERLU diantarkan (processing & shipped)
        return parent::getEloquentQuery()
            ->where('courier_id', auth()->id())
            ->whereIn('status', ['processing', 'shipped']);
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
                Tables\Columns\ViewColumn::make('id')
                    ->view('filament.courier.order-card')
            ])
            ->contentGrid([
                'default' => 1,
                'md' => 2,
                'xl' => 3,
            ])
            ->defaultSort('created_at', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'processing' => '🟡 Belum Diambil',
                        'shipped'    => '🚚 Sedang Dikirim',
                    ])
                    ->placeholder('Semua Tugas Aktif'),
            ])
            ->actions([
                Tables\Actions\Action::make('complete_delivery')
                    ->label('Selesaikan Pesanan')
                    ->icon('heroicon-o-check-circle')
                    ->color('primary')
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
// Call Customer & Navigate are now embedded in order-card.blade.php
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
