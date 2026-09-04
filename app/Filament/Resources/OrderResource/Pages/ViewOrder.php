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
        // 1. AKSI KHUSUS PO BATCH — Per Batch Status Transitions
        // ============================================================
        if ($record->fulfillment_type === 'po_batch') {
            $batches = $record->batches()->orderBy('batch_number')->get();

            foreach ($batches as $batch) {
                $batchId = $batch->id;
                $batchName = $batch->batch_name;

                // Aksi: Mulai Produksi (pending_production → producing)
                $actions[] = Actions\Action::make('batch_start_production_'.$batchId)
                    ->label("🔨 Mulai Produksi {$batchName}")
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->color('warning')
                    ->extraAttributes(['style' => 'display: none !important;'])
                    ->requiresConfirmation()
                    ->modalHeading("Konfirmasi: Mulai Produksi {$batchName}")
                    ->modalDescription("Ubah status {$batchName} menjadi 'Sedang Diproduksi'. ".number_format($batch->quantity, 0, ',', '.').' pcs akan masuk antrian produksi.')
                    ->modalSubmitActionLabel('Ya, Mulai Produksi')
                    ->action(function () use ($batchId, $record) {
                        $batch = OrderBatch::find($batchId);
                        if ($batch) {
                            $batch->update([
                                'status' => 'producing',
                                'actual_production_start_date' => now(),
                            ]);

                            $record->update(['status' => 'processing']);

                            Notification::make()
                                ->title("{$batch->batch_name} Masuk Produksi 🔨")
                                ->body('Status diubah ke: Sedang Diproduksi')
                                ->warning()
                                ->send();
                            $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                        }
                    });

                // Aksi: Tandai Siap Dikirim (producing → ready_to_ship)
                $actions[] = Actions\Action::make('batch_mark_ready_'.$batchId)
                    ->label("📦 Siap Kirim: {$batchName}")
                    ->icon('heroicon-o-cube')
                    ->color('info')
                    ->extraAttributes(['style' => 'display: none !important;'])
                    ->requiresConfirmation()
                    ->modalHeading("Konfirmasi: {$batchName} Siap Dikirim")
                    ->modalDescription("Ubah status {$batchName} menjadi 'Siap Dikirim'. ".number_format($batch->quantity, 0, ',', '.').' pcs telah selesai diproduksi dan siap dimuat ke armada.')
                    ->modalSubmitActionLabel('Konfirmasi Siap Kirim')
                    ->action(function () use ($batchId, $record) {
                        $batch = OrderBatch::find($batchId);
                        if ($batch) {
                            $batch->update([
                                'status' => 'ready_to_ship',
                                'actual_ready_date' => now(),
                            ]);
                            Notification::make()
                                ->title("{$batch->batch_name} Siap Dikirim 📦")
                                ->body('Status diubah ke: Siap Dikirim. Siapkan armada truk.')
                                ->info()
                                ->send();
                            $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                        }
                    });

                // Aksi: Berangkatkan Truk (ready_to_ship → shipped) + form supir/plat
                $actions[] = Actions\Action::make('batch_dispatch_'.$batchId)
                    ->label("🚚 Berangkatkan: {$batchName}")
                    ->icon('heroicon-o-truck')
                    ->color('danger')
                    ->extraAttributes(['style' => 'display: none !important;'])
                    ->modalHeading("Berangkatkan Armada: {$batchName}")
                    ->modalDescription(
                        'Muatan: '.number_format($batch->quantity, 0, ',', '.').' pcs | '.
                        'Tujuan: '.($record->shipping_address ?? '-').' ('.($record->shipping_city ?? '-').')'
                    )
                    ->form([
                        Forms\Components\Select::make('courier_id')
                            ->label('Pilih Akun Kurir Internal Pabrik (Opsional)')
                            ->options(User::where('role', 'courier')->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $courier = User::find($state);
                                    if ($courier) {
                                        $set('courier_name', $courier->name.' (Armada Pabrik)');
                                        $set('courier_phone', $courier->phone);
                                        $set('tracking_number', $courier->license_plate ?: 'Armada #'.rand(1, 9));
                                    }
                                }
                            }),
                        Forms\Components\TextInput::make('courier_name')
                            ->label('Nama Supir / Ekspedisi')
                            ->default(fn () => $batch->courier_name ?: ($record->courier ?: 'Armada Pabrik'))
                            ->required(),
                        Forms\Components\TextInput::make('courier_phone')
                            ->label('No. HP/WA Supir')
                            ->default(fn () => $batch->courier_phone ?: $record->courier_phone)
                            ->tel(),
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('No. Plat Nomor Truk / Resi')
                            ->placeholder('Contoh: B 9123 TDA / T 8472 AB')
                            ->default(fn () => $batch->tracking_number ?: $record->tracking_number)
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Muatan')
                            ->placeholder('Contoh: Muatan 850 pcs roster, hati-hati saat menurunkan.')
                            ->columnSpanFull(),
                    ])
                    ->modalSubmitActionLabel('🚚 Berangkatkan Sekarang & Cetak SJ')
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
                                Mail::to($email)->send(new OrderStatusMail($record, 'batch_shipped', $batch));
                            }
                        } catch (\Throwable $e) {
                            Log::error('Batch dispatch email error: '.$e->getMessage());
                        }

                        Notification::make()
                            ->title("{$batch->batch_name} Berhasil Diberangkatkan! 🚚")
                            ->body(
                                "Supir: {$batch->courier_name} | Plat: {$batch->tracking_number}\n".
                                'Muatan: '.number_format($batch->quantity, 0, ',', '.').' pcs'
                            )
                            ->success()
                            ->persistent()
                            ->actions([
                                Action::make('print_sj_'.$batchId)
                                    ->label("Cetak Surat Jalan {$batch->batch_name}")
                                    ->url(route('print.order', ['order' => $record->id, 'batch_id' => $batchId]), shouldOpenInNewTab: true)
                                    ->button()
                                    ->icon('heroicon-o-printer'),
                            ])
                            ->send();

                        $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                    });

                // Aksi: Tandai Diterima (shipped → delivered)
                $actions[] = Actions\Action::make('batch_delivered_'.$batchId)
                    ->label("✅ Diterima: {$batchName}")
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->extraAttributes(['style' => 'display: none !important;'])
                    ->modalHeading("Konfirmasi Diterima: {$batchName}")
                    ->modalDescription('Konfirmasi bahwa '.number_format($batch->quantity, 0, ',', '.')." pcs dari {$batchName} telah diterima di lokasi proyek pembeli.")
                    ->form([
                        Forms\Components\FileUpload::make('delivery_photo_path')
                            ->label('Unggah Foto Bukti Bongkar / Tiba di Proyek (Opsional)')
                            ->image()
                            ->directory('delivery-photos')
                            ->disk('public')
                            ->imagePreviewHeight('200')
                            ->columnSpanFull(),
                    ])
                    ->modalSubmitActionLabel('✅ Konfirmasi Diterima')
                    ->action(function (array $data) use ($batchId, $record) {
                        $batch = OrderBatch::find($batchId);
                        if ($batch) {
                            $updateData = [
                                'status' => 'delivered',
                                'actual_delivered_date' => now(),
                            ];
                            if (! empty($data['delivery_photo_path'])) {
                                $updateData['delivery_photo_path'] = $data['delivery_photo_path'];
                            }
                            $batch->update($updateData);

                            $record->refresh();
                            if ($record->isAllBatchesDelivered()) {
                                $record->update([
                                    'status' => 'completed',
                                    'completed_at' => now(),
                                ]);
                            }

                            try {
                                $email = $record->shipping_email ?? $record->user?->email;
                                if ($email) {
                                    Mail::to($email)->send(new OrderStatusMail($record, 'batch_delivered', $batch));
                                }
                            } catch (\Throwable $e) {
                                Log::error('Batch delivered email error: '.$e->getMessage());
                            }

                            Notification::make()
                                ->title("{$batch->batch_name} Telah Diterima di Proyek ✅")
                                ->body(number_format($batch->quantity, 0, ',', '.').' pcs material telah diterima.')
                                ->success()
                                ->send();

                            $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                        }
                    });
            }
        } else {
            // ============================================================
            // 2. AKSI KHUSUS PESANAN TUNGGAL (READY STOCK & PO SINGLE)
            // ============================================================

            // 2.1 Mulai Produksi PO Single
            $actions[] = Actions\Action::make('single_start_production')
                ->label('🔨 Mulai Produksi Sekarang')
                ->icon('heroicon-o-wrench-screwdriver')
                ->color('warning')
                ->extraAttributes(['style' => 'display: none !important;'])
                ->requiresConfirmation()
                ->modalHeading('Mulai Produksi Pesanan Sekarang?')
                ->modalDescription("Ubah status produksi menjadi 'Sedang Diproduksi'. Tanggal estimasi mulai produksi akan di-update ke hari ini (".now()->translatedFormat('d F Y').').')
                ->modalSubmitActionLabel('Ya, Mulai Produksi Sekarang')
                ->action(function () use ($record) {
                    $record->update([
                        'production_status' => 'producing',
                        'production_start_date' => now(),
                        'status' => 'processing',
                    ]);

                    try {
                        $email = $record->shipping_email ?? $record->user?->email;
                        if ($email) {
                            Mail::to($email)->send(new OrderStatusMail($record, 'processing'));
                        }
                    } catch (\Throwable $e) {
                        Log::error('Single order start production email error: '.$e->getMessage());
                    }

                    Notification::make()
                        ->title('Produksi Telah Dimulai! 🔨')
                        ->body("Pesanan {$record->order_number} kini berstatus Sedang Diproduksi.")
                        ->success()
                        ->send();

                    $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                });

            // 2.2 Tandai Siap Kirim
            $actions[] = Actions\Action::make('single_ready_to_ship')
                ->label('📦 Tandai Selesai & Siap Kirim')
                ->icon('heroicon-o-archive-box')
                ->color('info')
                ->extraAttributes(['style' => 'display: none !important;'])
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Material Siap Kirim?')
                ->modalDescription('Konfirmasi bahwa seluruh material pesanan telah selesai diproduksi/disiapkan di gudang dan siap diberangkatkan.')
                ->modalSubmitActionLabel('Ya, Siap Dikirim')
                ->action(function () use ($record) {
                    $record->update([
                        'production_status' => 'ready_to_ship',
                        'ready_shipping_date' => now(),
                    ]);

                    Notification::make()
                        ->title('Material Telah Siap Kirim! 📦')
                        ->body("Pesanan {$record->order_number} siap di loading dock pabrik.")
                        ->info()
                        ->send();

                    $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                });

            // 2.3 Berangkatkan Armada Truk
            $actions[] = Actions\Action::make('single_dispatch')
                ->label('🚚 Berangkatkan Armada Truk')
                ->icon('heroicon-o-truck')
                ->color('danger')
                ->extraAttributes(['style' => 'display: none !important;'])
                ->modalHeading('Berangkatkan Armada Truk')
                ->modalDescription("Tujuan Pengiriman: {$record->shipping_address} ({$record->shipping_city})")
                ->form([
                    Forms\Components\Select::make('courier_id')
                        ->label('Pilih Akun Kurir Internal Pabrik (Opsional)')
                        ->options(User::where('role', 'courier')->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            if ($state) {
                                $courier = User::find($state);
                                if ($courier) {
                                    $set('courier', $courier->name.' (Armada Pabrik)');
                                    $set('courier_phone', $courier->phone);
                                    $set('tracking_number', $courier->license_plate ?: 'Armada #'.rand(1, 9));
                                }
                            }
                        }),

                    Forms\Components\TextInput::make('courier')
                        ->label('Nama Supir / Ekspedisi')
                        ->default(fn () => $record->courier ?: 'Armada Truk Pabrik')
                        ->required(),

                    Forms\Components\TextInput::make('courier_phone')
                        ->label('No. WhatsApp / HP Supir')
                        ->default(fn () => $record->courier_phone)
                        ->tel(),

                    Forms\Components\TextInput::make('tracking_number')
                        ->label('Nomor Plat Truk / Resi')
                        ->placeholder('Contoh: B 9123 TDA / T 8472 AB')
                        ->default(fn () => $record->tracking_number)
                        ->required(),

                    Forms\Components\Textarea::make('admin_notes')
                        ->label('Catatan Muatan / Bongkar')
                        ->placeholder('Contoh: Penurunan di depan gerbang proyek.')
                        ->columnSpanFull(),
                ])
                ->modalSubmitActionLabel('🚚 Berangkatkan Sekarang & Cetak SJ')
                ->action(function (array $data) use ($record) {
                    $record->update([
                        'status' => 'shipped',
                        'production_status' => 'shipped',
                        'shipped_at' => now(),
                        'courier_id' => $data['courier_id'] ?? null,
                        'courier' => $data['courier'] ?? 'Armada Pabrik',
                        'courier_phone' => $data['courier_phone'] ?? null,
                        'tracking_number' => $data['tracking_number'] ?? null,
                        'admin_notes' => $data['admin_notes'] ?? null,
                    ]);

                    if ($record->user_id && $record->user) {
                        $record->user->notify(new OrderStatusUpdated($record, 'Dikirim'));
                    }

                    try {
                        $email = $record->shipping_email ?? $record->user?->email;
                        if ($email) {
                            Mail::to($email)->send(new OrderStatusMail($record, 'shipped'));
                        }
                    } catch (\Throwable $e) {
                        Log::error('Single order dispatch email error: '.$e->getMessage());
                    }

                    Notification::make()
                        ->title("Armada Truk {$record->order_number} Berangkat! 🚚")
                        ->body("Supir: {$record->courier} | Plat: {$record->tracking_number}")
                        ->success()
                        ->persistent()
                        ->actions([
                            Action::make('print_sj_single')
                                ->label('🖨️ Cetak Surat Jalan')
                                ->url(route('print.order', $record), shouldOpenInNewTab: true)
                                ->button()
                                ->icon('heroicon-o-printer'),
                        ])
                        ->send();

                    $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                });

            // 2.4 Konfirmasi Tiba & Selesai
            $actions[] = Actions\Action::make('single_delivered')
                ->label('✅ Selesai Diterima di Lokasi')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->extraAttributes(['style' => 'display: none !important;'])
                ->modalHeading('Konfirmasi Pesanan Diterima di Lokasi')
                ->modalDescription('Konfirmasi bahwa muatan barang telah selesai dibongkar dan diterima dengan baik oleh pembeli.')
                ->form([
                    Forms\Components\FileUpload::make('delivery_photo_path')
                        ->label('Unggah Foto Bukti Bongkar / Tiba di Lokasi (Opsional)')
                        ->image()
                        ->directory('delivery-photos')
                        ->disk('public')
                        ->imagePreviewHeight('200')
                        ->columnSpanFull(),
                ])
                ->modalSubmitActionLabel('✅ Konfirmasi Selesai Diterima')
                ->action(function (array $data) use ($record) {
                    $updateData = [
                        'status' => 'completed',
                        'production_status' => 'delivered',
                        'completed_at' => now(),
                    ];
                    if (! empty($data['delivery_photo_path'])) {
                        $updateData['delivery_photo_path'] = $data['delivery_photo_path'];
                    }
                    $record->update($updateData);

                    if ($record->user_id && $record->user) {
                        $record->user->notify(new OrderStatusUpdated($record, 'Selesai'));
                    }

                    try {
                        $email = $record->shipping_email ?? $record->user?->email;
                        if ($email) {
                            Mail::to($email)->send(new OrderStatusMail($record, 'delivered'));
                        }
                    } catch (\Throwable $e) {
                        Log::error('Single order delivered email error: '.$e->getMessage());
                    }

                    Notification::make()
                        ->title("Pesanan {$record->order_number} Telah Selesai! 🏁")
                        ->body('Material telah sukses dibongkar dan serah terima selesai.')
                        ->success()
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
