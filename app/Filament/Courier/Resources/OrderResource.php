<?php

namespace App\Filament\Courier\Resources;

use App\Filament\Courier\Resources\OrderResource\Pages;
use App\Mail\OrderStatusMail;
use App\Models\Order;
use App\Models\OrderBatch;
use App\Notifications\OrderStatusUpdated;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        $courierId = auth()->id();

        // Tampilkan pesanan yang:
        // 1. Langsung ditugaskan ke kurir ini (pesanan biasa), ATAU
        // 2. Pesanan PO Batch yang salah satu batchnya ditugaskan ke kurir ini
        return parent::getEloquentQuery()
            ->whereIn('status', ['processing', 'shipped'])
            ->where(function ($query) use ($courierId) {
                $query->where('courier_id', $courierId)
                    ->orWhereHas('batches', fn ($q) => $q->where('courier_id', $courierId)
                        ->whereIn('status', ['shipped'])
                    );
            });
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
                    ->view('filament.courier.order-card'),
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
                        'shipped' => '🚚 Sedang Dikirim',
                    ])
                    ->placeholder('Semua Tugas Aktif'),
            ])
            ->actions([
                Tables\Actions\Action::make('complete_delivery')
                    ->label('Selesaikan Pesanan')
                    ->icon('heroicon-o-check-circle')
                    ->color('primary')
                    ->visible(fn (Order $record) => in_array($record->status, ['processing', 'shipped']))
                    ->modalHeading('⚠️ Konfirmasi Sampai & Bongkar Muatan')
                    ->modalDescription('PENTING: Pastikan armada truk Anda sudah terparkir aman di lokasi proyek pembeli dan seluruh muatan roster telah selesai dibongkar muat dengan rapi sebelum melanjutkan.')
                    ->modalSubmitActionLabel('Selesaikan & Simpan Bukti')
                    ->form(function (Order $record) {
                        $formSchema = [];

                        if ($record->fulfillment_type === 'po_batch') {
                            $myActiveBatches = $record->batches()
                                ->where('courier_id', auth()->id())
                                ->where('status', 'shipped')
                                ->get();

                            $options = [];
                            foreach ($myActiveBatches as $b) {
                                $options[$b->id] = "{$b->batch_name} — ".number_format($b->quantity, 0, ',', '.').' pcs';
                            }

                            $formSchema[] = Forms\Components\Select::make('batch_id')
                                ->label('Pilih Batch yang Akan Diselesaikan')
                                ->options($options)
                                ->required()
                                ->helperText('Pilih batch yang baru saja Anda bongkar muatannya di lokasi proyek.');
                        }

                        $formSchema[] = Forms\Components\FileUpload::make('delivery_photo_path')
                            ->label('Foto Bukti Pembongkaran Roster')
                            ->helperText('Ambil foto secara profesional yang memperlihatkan tumpukan roster yang sudah diturunkan beserta area proyek/lokasi sekitar sebagai bukti serah terima yang valid.')
                            ->directory('delivery-proofs')
                            ->image()
                            ->extraInputAttributes(['capture' => 'environment'])
                            ->required();

                        return $formSchema;
                    })
                    ->action(function (Order $record, array $data) {
                        if ($record->fulfillment_type === 'po_batch') {
                            $batch = OrderBatch::find($data['batch_id']);
                            if ($batch) {
                                $batch->update([
                                    'status' => 'delivered',
                                    'actual_delivered_date' => now(),
                                    'delivery_photo_path' => $data['delivery_photo_path'],
                                ]);

                                $record->refresh();

                                // Kirim email notifikasi batch delivered
                                try {
                                    $email = $record->shipping_email ?? $record->user?->email;
                                    if ($email) {
                                        Mail::to($email)->send(new OrderStatusMail($record, 'batch_delivered', $batch));
                                    }
                                } catch (\Exception $e) {
                                    Log::error('Batch delivered email error: '.$e->getMessage());
                                }

                                // Jika semua batch dari order ini sudah 'delivered', maka update order induk ke 'completed'
                                $totalBatches = $record->batches()->count();
                                $deliveredBatches = $record->batches()->where('status', 'delivered')->count();

                                if ($deliveredBatches >= $totalBatches) {
                                    $record->update([
                                        'status' => 'completed',
                                        'completed_at' => now(),
                                        'delivery_photo_path' => $data['delivery_photo_path'],
                                    ]);

                                    try {
                                        $email = $record->shipping_email ?? $record->user?->email;
                                        if ($email) {
                                            Mail::to($email)->send(new OrderStatusMail($record, 'completed'));
                                        }
                                    } catch (\Exception $e) {
                                        Log::error('Order completed email error: '.$e->getMessage());
                                    }
                                } else {
                                    $allShippedOrDelivered = $record->batches()->whereIn('status', ['shipped', 'delivered'])->count() >= $totalBatches;
                                    if ($allShippedOrDelivered && $record->status !== 'shipped') {
                                        $record->update(['status' => 'shipped']);
                                    } elseif (! $allShippedOrDelivered && $record->status !== 'processing') {
                                        $record->update(['status' => 'processing']);
                                    }
                                }

                                Notification::make()
                                    ->title("Pengiriman {$batch->batch_name} Selesai")
                                    ->body('Bukti pengiriman berhasil diupload dan status batch diubah menjadi Diterima.')
                                    ->success()
                                    ->send();
                            }
                        } else {
                            $record->update([
                                'status' => 'completed',
                                'completed_at' => now(),
                                'delivery_photo_path' => $data['delivery_photo_path'],
                                'production_status' => 'delivered',
                            ]);

                            if ($record->user_id && $record->user) {
                                $record->user->notify(new OrderStatusUpdated($record, 'Selesai'));
                            }

                            try {
                                $email = $record->shipping_email ?? $record->user?->email;
                                if ($email) {
                                    Mail::to($email)->send(new OrderStatusMail($record, 'completed'));
                                }
                            } catch (\Exception $e) {
                                Log::error('Failed to send status email: '.$e->getMessage());
                            }

                            Notification::make()
                                ->title('Pesanan Selesai')
                                ->body('Bukti pengiriman berhasil diupload.')
                                ->success()
                                ->send();
                        }
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
