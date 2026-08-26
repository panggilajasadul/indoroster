<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Mail\OrderStatusMail;
use App\Models\OrderBatch;
use App\Models\User;
use App\Notifications\OrderStatusUpdated;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        $record = $this->getRecord();
        $actions = [];

        // ============================================================
        // AKSI KHUSUS PO BATCH — Per Batch Status Transitions
        // ============================================================
        if ($record->fulfillment_type === 'po_batch') {
            $batches = $record->batches()->orderBy('batch_number')->get();

            foreach ($batches as $batch) {
                $batchId = $batch->id;
                $batchName = $batch->batch_name;

                // Aksi: Mulai Produksi (pending_production → producing)
                if ($batch->status === 'pending_production') {
                    $actions[] = Actions\Action::make('batch_start_production_'.$batchId)
                        ->label("🔨 Mulai Produksi {$batchName}")
                        ->icon('heroicon-o-wrench-screwdriver')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading("Konfirmasi: Mulai Produksi {$batchName}")
                        ->modalDescription("Ubah status {$batchName} menjadi 'Sedang Diproduksi'. ".number_format($batch->quantity, 0, ',', '.').' pcs akan masuk antrian produksi.')
                        ->modalSubmitActionLabel('Ya, Mulai Produksi')
                        ->action(function () use ($batchId, $record) {
                            $batch = OrderBatch::find($batchId);
                            if ($batch) {
                                $batch->update(['status' => 'producing']);
                                Notification::make()
                                    ->title("{$batch->batch_name} Masuk Produksi")
                                    ->body('Status diubah ke: 🔨 Sedang Diproduksi')
                                    ->warning()
                                    ->send();
                                $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                            }
                        });
                }

                // Aksi: Tandai Siap Dikirim (producing → ready_to_ship)
                if ($batch->status === 'producing') {
                    $actions[] = Actions\Action::make('batch_mark_ready_'.$batchId)
                        ->label("📦 Siap Kirim: {$batchName}")
                        ->icon('heroicon-o-cube')
                        ->color('info')
                        ->requiresConfirmation()
                        ->modalHeading("Konfirmasi: {$batchName} Siap Dikirim")
                        ->modalDescription("Ubah status {$batchName} menjadi 'Siap Dikirim'. ".number_format($batch->quantity, 0, ',', '.').' pcs telah selesai diproduksi dan siap dimuat ke armada.')
                        ->modalSubmitActionLabel('Konfirmasi Siap Kirim')
                        ->action(function () use ($batchId, $record) {
                            $batch = OrderBatch::find($batchId);
                            if ($batch) {
                                $batch->update(['status' => 'ready_to_ship']);
                                Notification::make()
                                    ->title("{$batch->batch_name} Siap Dikirim")
                                    ->body('Status diubah ke: 📦 Siap Dikirim. Siapkan armada truk.')
                                    ->info()
                                    ->send();
                                $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                            }
                        });
                }

                // Aksi: Berangkatkan Truk (ready_to_ship → shipped) + form supir/plat
                if ($batch->status === 'ready_to_ship') {
                    $actions[] = Actions\Action::make('batch_dispatch_'.$batchId)
                        ->label("🚚 Berangkatkan: {$batchName}")
                        ->icon('heroicon-o-truck')
                        ->color('danger')
                        ->modalHeading("Berangkatkan Armada: {$batchName}")
                        ->modalDescription(
                            'Muatan: '.number_format($batch->quantity, 0, ',', '.').' pcs | '.
                            'Est. Tiba: '.($batch->estimated_delivery_date?->format('d M Y') ?? '-')
                        )
                        ->form([
                            Forms\Components\Select::make('courier_id')
                                ->label('Pilih Kurir Internal (dari Daftar Armada)')
                                ->relationship('courierUser', 'name', fn (Builder $query) => $query->where('role', 'courier'))
                                ->searchable()
                                ->preload()
                                ->live()
                                ->afterStateUpdated(function ($state, Forms\Set $set) {
                                    if ($state) {
                                        $courier = User::find($state);
                                        if ($courier) {
                                            $set('courier_name', $courier->name);
                                            $set('courier_phone', $courier->phone);
                                            $set('tracking_number', $courier->license_plate);
                                        }
                                    }
                                }),
                            Forms\Components\TextInput::make('courier_name')
                                ->label('Nama Supir / Ekspedisi')
                                ->placeholder('Contoh: Pak Wahyu (Armada Pabrik)')
                                ->required(),
                            Forms\Components\TextInput::make('courier_phone')
                                ->label('No. HP/WA Supir')
                                ->placeholder('Contoh: 08123456789')
                                ->tel(),
                            Forms\Components\TextInput::make('tracking_number')
                                ->label('No. Plat Nomor Truk')
                                ->placeholder('Contoh: B 9123 TDA')
                                ->required(),
                            Forms\Components\Textarea::make('notes')
                                ->label('Catatan Muatan')
                                ->placeholder('Contoh: Muatan 850 pcs roster, hati-hati saat menurunkan.')
                                ->columnSpanFull(),
                        ])
                        ->modalSubmitActionLabel('🚚 Berangkatkan Sekarang')
                        ->action(function (array $data) use ($batchId, $record) {
                            $batch = OrderBatch::find($batchId);
                            if (! $batch) {
                                return;
                            }

                            $batch->update([
                                'status' => 'shipped',
                                'actual_dispatch_date' => now(),
                                'courier_id' => $data['courier_id'] ?? null,
                                'courier_name' => $data['courier_name'] ?? 'Armada Pabrik',
                                'courier_phone' => $data['courier_phone'] ?? null,
                                'tracking_number' => $data['tracking_number'] ?? null,
                                'notes' => $data['notes'] ?? null,
                            ]);

                            $record->refresh();

                            // Update status pesanan induk jika semua batch shipped
                            if ($record->isAllBatchesShipped()) {
                                $record->update(['status' => 'shipped', 'shipped_at' => now()]);
                            }

                            // Kirim email per batch
                            try {
                                $email = $record->shipping_email ?? $record->user?->email;
                                if ($email) {
                                    Mail::to($email)
                                        ->send(new OrderStatusMail($record, 'batch_shipped', $batch));
                                }
                            } catch (\Exception $e) {
                                Log::error('Batch email error: '.$e->getMessage());
                            }

                            Notification::make()
                                ->title("{$batch->batch_name} Berhasil Diberangkatkan! 🚚")
                                ->body(
                                    "Supir: {$batch->courier_name} | Plat: {$batch->tracking_number}\n".
                                    'Muatan: '.number_format($batch->quantity, 0, ',', '.').' pcs | '.
                                    'Sisa: '.number_format($batch->remaining_quantity_after_this_batch, 0, ',', '.').' pcs'
                                )
                                ->success()
                                ->persistent()
                                ->actions([
                                    Action::make('print_sj_'.$batchId)
                                        ->label("Cetak Surat Jalan {$batch->batch_name}")
                                        ->url(route('print.order', ['order' => $record->id, 'batch_id' => $batchId]), shouldOpenInNewTab: true)
                                        ->button()
                                        ->icon('heroicon-o-printer'),
                                    Action::make('wa_supir_'.$batchId)
                                        ->label('Kirim WA Info Supir')
                                        ->url($record->getWaBatchShippedLink($batch), shouldOpenInNewTab: true)
                                        ->button()
                                        ->color('success')
                                        ->icon('heroicon-o-chat-bubble-left-ellipsis'),
                                ])
                                ->send();

                            $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                        });
                }

                // Aksi: Tandai Diterima (shipped → delivered)
                if ($batch->status === 'shipped') {
                    $actions[] = Actions\Action::make('batch_delivered_'.$batchId)
                        ->label("✅ Diterima: {$batchName}")
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading("Konfirmasi: {$batchName} Sudah Diterima")
                        ->modalDescription('Konfirmasi bahwa '.number_format($batch->quantity, 0, ',', '.')." pcs dari {$batchName} telah diterima di lokasi proyek pembeli.")
                        ->modalSubmitActionLabel('✅ Konfirmasi Diterima')
                        ->action(function () use ($batchId, $record) {
                            $batch = OrderBatch::find($batchId);
                            if ($batch) {
                                $batch->update([
                                    'status' => 'delivered',
                                    'actual_delivered_date' => now(),
                                ]);
                                Notification::make()
                                    ->title("{$batch->batch_name} Telah Diterima ✅")
                                    ->body(number_format($batch->quantity, 0, ',', '.').' pcs diterima di lokasi proyek.')
                                    ->success()
                                    ->send();
                                $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                            }
                        });
                }
            }
        }

        // ============================================================
        // AKSI KHUSUS PO TUNGGAL & READY STOCK — Status Transitions
        // ============================================================
        if ($record->fulfillment_type === 'po_single' && $record->status === 'processing') {
            // Aksi: Mulai Produksi (pending → producing)
            if ($record->production_status === 'pending' || is_null($record->production_status)) {
                $actions[] = Actions\Action::make('po_single_start_production')
                    ->label('🔨 Mulai Produksi PO')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi: Mulai Produksi Pre-Order')
                    ->modalDescription('Ubah status pesanan PO Tunggal ini menjadi sedang diproduksi.')
                    ->modalSubmitActionLabel('Ya, Mulai Produksi')
                    ->action(function () use ($record) {
                        $record->update(['production_status' => 'producing']);
                        Notification::make()
                            ->title('Produksi PO Tunggal Dimulai')
                            ->body('Status pengerjaan diubah menjadi: 🔨 Sedang Diproduksi')
                            ->warning()
                            ->send();
                        $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                    });
            }

            // Aksi: Tandai Siap Kirim (producing → ready_to_ship)
            if ($record->production_status === 'producing') {
                $actions[] = Actions\Action::make('po_single_mark_ready')
                    ->label('📦 Siap Kirim')
                    ->icon('heroicon-o-cube')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi: Produksi Selesai & Siap Kirim')
                    ->modalDescription('Ubah status pesanan PO Tunggal ini menjadi siap kirim.')
                    ->modalSubmitActionLabel('Konfirmasi Siap Kirim')
                    ->action(function () use ($record) {
                        $record->update(['production_status' => 'ready_to_ship']);
                        Notification::make()
                            ->title('Produksi PO Selesai & Siap Kirim')
                            ->body('Status pengerjaan diubah menjadi: 📦 Siap Kirim')
                            ->info()
                            ->send();
                        $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                    });
            }
        }

        // Aksi: Kirim Pesanan (Berangkatkan Armada) untuk PO Tunggal (siap kirim) atau Ready Stock
        $isReadyForDispatch = ($record->status === 'processing') && (
            ($record->fulfillment_type === 'ready_stock' || is_null($record->fulfillment_type)) ||
            ($record->fulfillment_type === 'po_single' && $record->production_status === 'ready_to_ship')
        );

        if ($isReadyForDispatch) {
            $actions[] = Actions\Action::make('dispatch_single_order')
                ->label('🚚 Kirim Pesanan (Berangkatkan)')
                ->icon('heroicon-o-truck')
                ->color('danger')
                ->modalHeading('Berangkatkan Armada Pengiriman')
                ->modalDescription('Lengkapi data supir dan nomor plat armada untuk mencetak Surat Jalan.')
                ->form([
                    Forms\Components\Select::make('courier_id')
                        ->label('Pilih Kurir Internal')
                        ->relationship('courierUser', 'name', fn (Builder $query) => $query->where('role', 'courier'))
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            if ($state) {
                                $courier = User::find($state);
                                if ($courier) {
                                    $set('courier_name', $courier->name);
                                    $set('courier_phone', $courier->phone);
                                    $set('tracking_number', $courier->license_plate);
                                }
                            }
                        }),
                    Forms\Components\TextInput::make('courier_name')
                        ->label('Nama Supir / Ekspedisi')
                        ->placeholder('Contoh: Pak Wahyu (Armada Pabrik)')
                        ->required(),
                    Forms\Components\TextInput::make('courier_phone')
                        ->label('No. HP/WA Supir')
                        ->placeholder('Contoh: 08123456789')
                        ->tel(),
                    Forms\Components\TextInput::make('tracking_number')
                        ->label('No. Plat Nomor Truk')
                        ->placeholder('Contoh: B 9123 TDA')
                        ->required(),
                    Forms\Components\Textarea::make('notes')
                        ->label('Catatan Pengiriman')
                        ->placeholder('Contoh: Antar sebelum sore, hati-hati saat bongkar muatan.')
                        ->columnSpanFull(),
                ])
                ->modalSubmitActionLabel('🚚 Berangkatkan Sekarang')
                ->action(function (array $data) use ($record) {
                    $record->update([
                        'status' => 'shipped',
                        'shipped_at' => now(),
                        'production_status' => 'shipped',
                        'courier_id' => $data['courier_id'] ?? null,
                        'courier' => $data['courier_name'] ?? 'Armada Pabrik',
                        'courier_phone' => $data['courier_phone'] ?? null,
                        'tracking_number' => $data['tracking_number'] ?? null,
                        'admin_notes' => $data['notes'] ?? null,
                    ]);

                    if ($record->user_id && $record->user) {
                        $record->user->notify(new OrderStatusUpdated($record, 'Dikirim'));
                    }

                    try {
                        $email = $record->shipping_email ?? $record->user?->email;
                        if ($email) {
                            Mail::to($email)->send(new OrderStatusMail($record, 'shipped'));
                        }
                    } catch (\Exception $e) {
                        Log::error('Failed to send status email: '.$e->getMessage());
                    }

                    Notification::make()
                        ->title('Armada Berhasil Diberangkatkan! 🚚')
                        ->body("Supir: {$record->courier} | Plat: {$record->tracking_number}")
                        ->success()
                        ->persistent()
                        ->actions([
                            Action::make('print_sj_single')
                                ->label('Cetak Surat Jalan')
                                ->url(route('print.order', $record), shouldOpenInNewTab: true)
                                ->button()
                                ->icon('heroicon-o-printer'),
                        ])
                        ->send();

                    $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                });
        }

        // ============================================================
        // AKSI UMUM: Cetak Invoice, Cetak SJ, Label
        // ============================================================
        $actions[] = Actions\Action::make('print_invoice')
            ->label('Cetak Invoice')
            ->icon('heroicon-o-document-text')
            ->color('success')
            ->visible(fn ($record) => $record->invoice !== null)
            ->url(fn ($record) => $record->invoice ? route('print.invoice', $record->invoice) : '#')
            ->openUrlInNewTab();

        // Cetak SJ: untuk PO Batch tampilkan dropdown per batch
        if ($record->fulfillment_type === 'po_batch') {
            $shippedBatches = $record->batches()->whereIn('status', ['shipped', 'delivered'])->orderBy('batch_number')->get();
            foreach ($shippedBatches as $sb) {
                $actions[] = Actions\Action::make('reprint_sj_'.$sb->id)
                    ->label("🖨️ SJ {$sb->batch_name}")
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->url(route('print.order', ['order' => $record->id, 'batch_id' => $sb->id]))
                    ->openUrlInNewTab();
            }
        } else {
            $actions[] = Actions\Action::make('print_order')
                ->label('Cetak Surat Jalan')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->url(fn ($record) => route('print.order', $record))
                ->openUrlInNewTab();
        }

        return $actions;
    }
}
