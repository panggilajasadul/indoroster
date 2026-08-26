<?php

namespace App\Filament\Courier\Widgets;

use App\Mail\OrderStatusMail;
use App\Models\Order;
use App\Models\OrderBatch;
use App\Notifications\OrderStatusUpdated;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LatestDeliveryTasks extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Perlu Dikirim Segera';

    public function table(Table $table): Table
    {
        $courierId = auth()->id();

        return $table
            ->query(
                Order::query()
                    ->whereIn('status', ['processing', 'shipped'])
                    ->where(function ($query) use ($courierId) {
                        $query->where('courier_id', $courierId)
                            ->orWhereHas('batches', fn ($q) => $q->where('courier_id', $courierId)
                                ->whereIn('status', ['shipped'])
                            );
                    })
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\ViewColumn::make('id')
                    ->view('filament.courier.order-card'),
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
            ])
            ->paginated(false)
            ->emptyStateHeading('Semua Tugas Sudah Selesai! 🎉')
            ->emptyStateDescription('Hebat sekali! Semua barang telah sukses diantarkan. Istirahat dulu jika lelah, atau ngopi dulu jika mengantuk. Tetap utamakan keselamatan ya!')
            ->emptyStateIcon('heroicon-o-face-smile');
    }
}
