<?php

namespace App\Filament\Resources\WaOrderResource\Pages;

use App\Filament\Resources\WaOrderResource;
use App\Mail\OrderStatusMail;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderBatch;
use App\Models\Payment;
use App\Models\User;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class ViewWaOrder extends ViewRecord
{
    protected static string $resource = WaOrderResource::class;

    protected function getHeaderActions(): array
    {
        /** @var Order $record */
        $record = $this->getRecord();
        $actions = [];

        // ============================================================
        // 0. AKSI UTAMA DI HEADER (TERBITKAN, PELUNASAN, CETAK)
        // ============================================================
        if ($record->status === 'draft') {
            $actions[] = Actions\Action::make('publish_draft_order')
                ->label('🚀 Terbitkan Pesanan')
                ->icon('heroicon-o-rocket-launch')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Terbitkan Pesanan WhatsApp?')
                ->modalDescription('Pesanan akan diubah statusnya menjadi "Menunggu Pembayaran / DP" dan siap dikirimkan tagihan resminya ke pembeli.')
                ->action(function () use ($record) {
                    $record->update(['status' => 'pending_payment']);

                    Notification::make()
                        ->title('Pesanan WhatsApp Diterbitkan!')
                        ->body("Pesanan {$record->order_number} berhasil diterbitkan.")
                        ->success()
                        ->send();

                    $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                });
        }

        // 1. Catat / Update Pembayaran DP & Termin Masuk
        if ($record->status !== 'draft' && $record->payment_status !== 'paid') {
            $actions[] = Actions\Action::make('update_down_payment')
                ->label('💳 Catat Pembayaran / DP Masuk')
                ->icon('heroicon-o-credit-card')
                ->color('warning')
                ->extraAttributes(['style' => 'display: none !important;'])
                ->modalHeading('Catat Transaksi Pembayaran / DP Masuk')
                ->modalDescription(
                    'Total Nilai Pesanan: Rp '.number_format($record->grand_total, 0, ',', '.').' | '.
                    'Sudah Terbayar: Rp '.number_format($record->total_paid_amount, 0, ',', '.').' | '.
                    'Sisa Tagihan: Rp '.number_format($record->remaining_balance ?: max(0, $record->grand_total - $record->total_paid_amount), 0, ',', '.')
                )
                ->form([
                    Forms\Components\TextInput::make('installment_title')
                        ->label('Judul / Urutan Pembayaran')
                        ->placeholder('Contoh: Pembayaran #1 (DP Awal) / Pembayaran #2')
                        ->default(fn () => $record->payments()->count() === 0 ? 'Pembayaran #1 (DP Awal)' : 'Pembayaran #'.($record->payments()->count() + 1))
                        ->datalist([
                            'Pembayaran #1 (DP Awal)',
                            'Pembayaran #2',
                            'Pembayaran #3',
                            'Pembayaran #4',
                            'Pembayaran #5',
                            'Pembayaran Pelunasan Akhir',
                            'Cicilan Tambahan',
                        ])
                        ->required()
                        ->helperText('Bisa ketik bebas sesuai transaksi (misal: Pembayaran #1, Pembayaran #2, dst).'),

                    Forms\Components\TextInput::make('payment_amount')
                        ->label('Nominal Uang Masuk Kali Ini (Rp)')
                        ->prefix('Rp')
                        ->numeric()
                        ->default(fn () => $record->payments()->count() === 0 ? ($record->down_payment_amount ?: round($record->grand_total * 0.5)) : ($record->remaining_balance ?: max(0, $record->grand_total - $record->total_paid_amount)))
                        ->required()
                        ->helperText('Masukkan nominal rupiah yang ditransfer oleh pembeli pada transaksi ini.'),

                    Forms\Components\Select::make('payment_method')
                        ->label('Metode Pembayaran')
                        ->options([
                            'BCA' => 'Transfer Bank BCA (IndoRoster)',
                            'Mandiri' => 'Transfer Bank Mandiri (IndoRoster)',
                            'BRI' => 'Transfer Bank BRI (IndoRoster)',
                            'Cash' => 'Tunai / Cash di Proyek / Pabrik',
                            'Other' => 'Metode Lainnya',
                        ])
                        ->default('BCA')
                        ->required(),

                    Forms\Components\Textarea::make('notes')
                        ->label('Catatan Bukti Transfer / No. Rekening Pengirim')
                        ->placeholder('Contoh: Transfer DP 10 Juta via M-Banking BCA an. Ahmad Zainal tgl 28/08')
                        ->columnSpanFull(),
                ])
                ->modalSubmitActionLabel('Simpan Transaksi & Terbitkan Kuitansi')
                ->action(function (array $data) use ($record) {
                    $grandTotal = (float) $record->grand_total;
                    $amountThisTime = (float) ($data['payment_amount'] ?? 0);
                    $prevSum = (float) $record->payments()->whereIn('status', ['settlement', 'capture', 'paid', 'success'])->sum('gross_amount');
                    $newTotalPaid = $prevSum + $amountThisTime;
                    $remaining = max(0, $grandTotal - $newTotalPaid);
                    $isPaid = ($remaining <= 0 && $grandTotal > 0);

                    // Buat Record Payment Resmi
                    $payCount = $record->payments()->count() + 1;
                    $transId = 'PAY-WA-'.str_replace(['INV-WA-', 'INV-'], '', $record->order_number).'-'.$payCount;

                    $payment = Payment::create([
                        'order_id' => $record->id,
                        'transaction_id' => $transId,
                        'payment_type' => 'bank_transfer',
                        'bank' => $data['payment_method'],
                        'gross_amount' => $amountThisTime,
                        'status' => 'settlement',
                        'paid_at' => now(),
                        'raw_response' => [
                            'title' => $data['installment_title'] ?? ('Pembayaran #'.$payCount),
                            'notes' => $data['notes'] ?? null,
                            'remaining_after' => $remaining,
                        ],
                    ]);

                    // Update Status Pesanan & DP
                    $updateOrder = [
                        'down_payment_amount' => $newTotalPaid,
                        'remaining_balance' => $remaining,
                        'payment_status' => $isPaid ? 'paid' : 'unpaid',
                        'paid_at' => $isPaid ? now() : null,
                    ];

                    if ($record->status === 'draft' || $record->status === 'pending_payment') {
                        $updateOrder['status'] = 'processing';
                    }

                    $record->update($updateOrder);

                    if ($invoice = $record->invoice) {
                        $invoice->update([
                            'down_payment_amount' => $newTotalPaid,
                            'remaining_balance' => $remaining,
                            'status' => $isPaid ? 'paid' : 'sent',
                            'paid_at' => $isPaid ? now() : null,
                        ]);
                    }

                    Notification::make()
                        ->title('Pembayaran '.($data['installment_title'] ?? ('#'.$payCount)).' Berhasil Dicatat! 💳')
                        ->body('Uang Masuk: Rp '.number_format($amountThisTime, 0, ',', '.').' | Total Terbayar: Rp '.number_format($newTotalPaid, 0, ',', '.').' | Sisa Tagihan: Rp '.number_format($remaining, 0, ',', '.'))
                        ->success()
                        ->persistent()
                        ->actions([
                            Action::make('print_receipt_'.$payment->id)
                                ->label('🖨️ Cetak Kuitansi '.$payment->receipt_number)
                                ->url(route('print.receipt', $payment), shouldOpenInNewTab: true)
                                ->button()
                                ->color('success')
                                ->icon('heroicon-o-printer'),
                            Action::make('wa_receipt_'.$payment->id)
                                ->label('💬 Kirim Kuitansi via WA')
                                ->url($record->getWaPaymentReceiptLink($payment), shouldOpenInNewTab: true)
                                ->button()
                                ->color('success')
                                ->icon('heroicon-o-chat-bubble-left-ellipsis'),
                            Action::make('print_inv_updated')
                                ->label('📄 Cetak Invoice Terkini')
                                ->url(URL::signedRoute('print.invoice', ['invoice' => $record->invoice?->id]), shouldOpenInNewTab: true)
                                ->button()
                                ->icon('heroicon-o-document-text'),
                        ])
                        ->send();

                    $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                });
        }

        // 2. Catat Pelunasan Cepat 1-Klik
        if ($record->status !== 'draft' && $record->payment_status !== 'paid') {
            $actions[] = Actions\Action::make('settle_payment')
                ->label('💰 Catat Pelunasan Tagihan')
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->extraAttributes(['style' => 'display: none !important;'])
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Pelunasan Tagihan?')
                ->modalDescription(
                    'Konfirmasi bahwa sisa tagihan sebesar Rp '.
                    number_format($record->remaining_balance ?: max(0, $record->grand_total - $record->total_paid_amount), 0, ',', '.').
                    " telah diterima secara penuh dan pesanan {$record->order_number} dinyatakan Lunas 100%."
                )
                ->modalSubmitActionLabel('Ya, Konfirmasi Lunas (100%)')
                ->action(function () use ($record) {
                    $grandTotal = (float) $record->grand_total;
                    $prevSum = (float) $record->payments()->whereIn('status', ['settlement', 'capture', 'paid', 'success'])->sum('gross_amount');
                    $sisa = max(0, $grandTotal - $prevSum);

                    $payCount = $record->payments()->count() + 1;
                    $transId = 'PAY-WA-'.str_replace(['INV-WA-', 'INV-'], '', $record->order_number).'-'.$payCount;

                    // Buat Record Pelunasan jika masih ada sisa
                    if ($sisa > 0) {
                        $payment = Payment::create([
                            'order_id' => $record->id,
                            'transaction_id' => $transId,
                            'payment_type' => 'bank_transfer',
                            'bank' => 'BCA',
                            'gross_amount' => $sisa,
                            'status' => 'settlement',
                            'paid_at' => now(),
                            'raw_response' => [
                                'title' => 'Pembayaran Pelunasan Akhir',
                                'notes' => 'Pelunasan 100% tagihan pesanan',
                                'remaining_after' => 0,
                            ],
                        ]);
                    }

                    $record->update([
                        'payment_status' => 'paid',
                        'down_payment_amount' => $grandTotal,
                        'remaining_balance' => 0,
                        'paid_at' => now(),
                    ]);

                    if ($invoice = $record->invoice) {
                        $invoice->update([
                            'down_payment_amount' => $grandTotal,
                            'remaining_balance' => 0,
                            'status' => 'paid',
                            'paid_at' => now(),
                        ]);
                    }

                    try {
                        $email = $record->shipping_email ?? $record->user?->email;
                        if ($email) {
                            Mail::to($email)->send(new OrderStatusMail($record, 'paid'));
                        }
                    } catch (\Throwable $e) {
                        Log::error('Settlement email error: '.$e->getMessage());
                    }

                    Notification::make()
                        ->title('Pembayaran Berhasil Dilunasi! 🟢')
                        ->body("Pesanan {$record->order_number} kini berstatus LUNAS (100%). Seluruh kuitansi dan invoice lunas telah siap.")
                        ->success()
                        ->persistent()
                        ->actions([
                            Action::make('print_receipt_settle')
                                ->label('🖨️ Cetak Kuitansi Pelunasan')
                                ->url(route('print.receipt', $payment), shouldOpenInNewTab: true)
                                ->button()
                                ->color('success')
                                ->icon('heroicon-o-printer'),
                            Action::make('print_inv_paid')
                                ->label('📄 Cetak Invoice Lunas')
                                ->url(URL::signedRoute('print.invoice', ['invoice' => $record->invoice?->id]), shouldOpenInNewTab: true)
                                ->button()
                                ->icon('heroicon-o-document-text'),
                            Action::make('wa_settlement')
                                ->label('💬 Kirim Bukti Lunas ke WA')
                                ->url($record->getWaSettlementPaidLink(), shouldOpenInNewTab: true)
                                ->button()
                                ->color('success')
                                ->icon('heroicon-o-chat-bubble-left-ellipsis'),
                        ])
                        ->send();

                    $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                });
        }

        // Cetak Invoice
        $actions[] = Actions\Action::make('print_invoice')
            ->label('📄 Cetak Invoice')
            ->icon('heroicon-o-document-text')
            ->color('success')
            ->visible(fn ($record) => $record->invoice !== null || $record->status !== 'draft')
            ->url(function ($record) {
                $invoice = $record->invoice ?: Invoice::firstOrCreate(
                    ['order_id' => $record->id],
                    [
                        'invoice_number' => Invoice::generateWaInvoiceNumber(),
                        'invoice_date' => now(),
                        'subtotal' => $record->subtotal,
                        'shipping_cost' => $record->shipping_cost,
                        'discount_amount' => $record->discount_amount,
                        'grand_total' => $record->grand_total,
                        'payment_scheme' => $record->payment_scheme ?: 'full',
                        'down_payment_amount' => $record->down_payment_amount ?: 0,
                        'remaining_balance' => $record->remaining_balance ?: 0,
                        'status' => $record->payment_status === 'paid' ? 'paid' : ($record->status === 'draft' ? 'draft' : 'sent'),
                        'paid_at' => $record->payment_status === 'paid' ? now() : null,
                    ]
                );

                return URL::signedRoute('print.invoice', ['invoice' => $invoice->id]);
            })
            ->openUrlInNewTab();

        // Cetak Rincian Proses Pesanan / Surat Jalan
        $actions[] = Actions\Action::make('print_order')
            ->label(fn ($record) => $record->fulfillment_type === 'po_batch' ? '📋 Cetak Rincian Jadwal PO Batch' : '🖨️ Cetak Surat Jalan')
            ->icon('heroicon-o-printer')
            ->color('info')
            ->url(fn ($record) => route('print.order', $record))
            ->openUrlInNewTab();

        $actions[] = Actions\EditAction::make();

        // ============================================================
        // 1. AKSI BATCH DI REGISTER KE HALAMAN AGAR BISA DI-TRIGGER
        //    DARI KARTU BATCH (HIDDEN DARI HEADER ATAS DENGAN CSS)
        // ============================================================
        if ($record->fulfillment_type === 'po_batch') {
            $batches = $record->batches()->orderBy('batch_number')->get();

            foreach ($batches as $batch) {
                $batchId = $batch->id;
                $batchName = $batch->batch_name;

                // 1.1 Mulai Produksi
                $actions[] = Actions\Action::make('batch_start_production_'.$batchId)
                    ->label("🔨 Mulai Produksi {$batchName}")
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->color('warning')
                    ->extraAttributes(['style' => 'display: none !important;'])
                    ->requiresConfirmation()
                    ->modalHeading("Mulai Produksi: {$batchName}")
                    ->modalDescription("Ubah status {$batchName} menjadi 'Sedang Diproduksi'. Sebanyak ".number_format($batch->quantity, 0, ',', '.').' pcs roster akan masuk antrean cetak pabrik.')
                    ->modalSubmitActionLabel('Ya, Mulai Produksi')
                    ->action(function () use ($batchId, $record) {
                        $batch = OrderBatch::find($batchId);
                        if ($batch) {
                            $batch->update([
                                'status' => 'producing',
                                'production_start_date' => $batch->production_start_date ?: now(),
                            ]);

                            if ($record->status === 'pending_payment' || $record->status === 'draft') {
                                $record->update(['status' => 'processing']);
                            }

                            // Kirim email konfirmasi & jadwal pengiriman bertahap HANYA saat mulai produksi batch PERTAMA
                            if ($batch->batch_number === 1) {
                                try {
                                    $email = $record->shipping_email ?? $record->user?->email;
                                    if ($email) {
                                        Mail::to($email)->send(new OrderStatusMail($record, 'processing', $batch));
                                    }
                                } catch (\Throwable $e) {
                                    Log::error('Batch start email error: '.$e->getMessage());
                                }
                            }

                            Notification::make()
                                ->title("{$batch->batch_name} Masuk Tahap Produksi 🔨")
                                ->body('Status pengerjaan diubah menjadi: Sedang Diproduksi')
                                ->warning()
                                ->persistent()
                                ->actions([
                                    Action::make('wa_prod_'.$batchId)
                                        ->label('💬 Kirim WA Jadwal Cetak')
                                        ->url($record->getWaProductionStartedLink($batch), shouldOpenInNewTab: true)
                                        ->button()
                                        ->color('success')
                                        ->icon('heroicon-o-chat-bubble-left-ellipsis'),
                                ])
                                ->send();

                            $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                        }
                    });

                // 1.2 Siap Kirim
                $actions[] = Actions\Action::make('batch_mark_ready_'.$batchId)
                    ->label("📦 Siap Kirim: {$batchName}")
                    ->icon('heroicon-o-cube')
                    ->color('info')
                    ->extraAttributes(['style' => 'display: none !important;'])
                    ->requiresConfirmation()
                    ->modalHeading("Konfirmasi Siap Kirim: {$batchName}")
                    ->modalDescription("Ubah status {$batchName} menjadi 'Siap Dikirim'. Sebanyak ".number_format($batch->quantity, 0, ',', '.').' pcs telah selesai diproduksi dan siap dimuat ke armada.')
                    ->modalSubmitActionLabel('Konfirmasi Siap Kirim')
                    ->action(function () use ($batchId, $record) {
                        $batch = OrderBatch::find($batchId);
                        if ($batch) {
                            $batch->update(['status' => 'ready_to_ship']);

                            Notification::make()
                                ->title("{$batch->batch_name} Siap Dikirim 📦")
                                ->body('Material telah siap di loading dock pabrik.')
                                ->info()
                                ->persistent()
                                ->actions([
                                    Action::make('wa_ready_'.$batchId)
                                        ->label('💬 Kirim WA Siap Kirim')
                                        ->url($record->getWaReadyToShipLink($batch), shouldOpenInNewTab: true)
                                        ->button()
                                        ->color('info')
                                        ->icon('heroicon-o-chat-bubble-left-ellipsis'),
                                ])
                                ->send();

                            $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                        }
                    });

                // 1.3 Berangkatkan Truk
                $actions[] = Actions\Action::make('batch_dispatch_'.$batchId)
                    ->label("🚚 Berangkatkan: {$batchName}")
                    ->icon('heroicon-o-truck')
                    ->color('danger')
                    ->extraAttributes(['style' => 'display: none !important;'])
                    ->modalHeading("Berangkatkan Armada Truk: {$batchName}")
                    ->modalDescription(
                        'Muatan: '.number_format($batch->quantity, 0, ',', '.').' pcs | '.
                        'Tujuan: '.$record->shipping_address.' ('.$record->shipping_city.')'
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
                            ->label('Nama Supir / Ekspedisi (Bisa Ketik Manual)')
                            ->default(fn () => $batch->courier_name ?: ($record->courier ?: 'Armada Truk Pabrik'))
                            ->required(),

                        Forms\Components\TextInput::make('courier_phone')
                            ->label('No. WhatsApp / HP Supir')
                            ->default(fn () => $batch->courier_phone ?: $record->courier_phone)
                            ->tel(),

                        Forms\Components\TextInput::make('tracking_number')
                            ->label('Nomor Plat Truk / Kendaraan')
                            ->placeholder('Contoh: B 9123 TDA / T 8472 AB')
                            ->default(fn () => $batch->tracking_number ?: $record->tracking_number)
                            ->required(),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Muatan / Bongkar')
                            ->placeholder('Contoh: Muatan 1.500 pcs roster, penurunan di depan gerbang proyek.')
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

                        if ($record->isAllBatchesShipped()) {
                            $record->update(['status' => 'shipped', 'shipped_at' => now()]);
                        } elseif ($record->status !== 'shipped' && $record->status !== 'delivered') {
                            $record->update(['status' => 'processing']);
                        }

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
                                'Muatan: '.number_format($batch->quantity, 0, ',', '.').' pcs | '.
                                'Sisa Belum Terkirim: '.number_format($batch->remaining_quantity_after_this_batch, 0, ',', '.').' pcs'
                            )
                            ->success()
                            ->persistent()
                            ->actions([
                                Action::make('print_sj_'.$batchId)
                                    ->label("🖨️ Cetak Surat Jalan {$batch->batch_name}")
                                    ->url(route('print.order', ['order' => $record->id, 'batch_id' => $batchId]), shouldOpenInNewTab: true)
                                    ->button()
                                    ->icon('heroicon-o-printer'),
                                Action::make('wa_supir_'.$batchId)
                                    ->label('💬 Kirim WA Info Supir & Muatan')
                                    ->url($record->getWaBatchShippedLink($batch), shouldOpenInNewTab: true)
                                    ->button()
                                    ->color('success')
                                    ->icon('heroicon-o-chat-bubble-left-ellipsis'),
                            ])
                            ->send();

                        $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                    });

                // 1.4 Tandai Diterima
                $actions[] = Actions\Action::make('batch_delivered_'.$batchId)
                    ->label("✅ Diterima: {$batchName}")
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->extraAttributes(['style' => 'display: none !important;'])
                    ->modalHeading("Konfirmasi Diterima: {$batchName}")
                    ->modalDescription('Konfirmasi bahwa '.number_format($batch->quantity, 0, ',', '.')." pcs dari {$batchName} telah selesai dibongkar dan diterima dengan baik di lokasi proyek.")
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
                                ->persistent()
                                ->actions([
                                    Action::make('wa_batch_delivered_'.$batchId)
                                        ->label('💬 Kirim WA Bukti Bongkar')
                                        ->url($record->getWaDeliveredLink($batch), shouldOpenInNewTab: true)
                                        ->button()
                                        ->color('success')
                                        ->icon('heroicon-o-chat-bubble-left-ellipsis'),
                                ])
                                ->send();

                            $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                        }
                    });
            }
        } else {
            // ============================================================
            // 2. AKSI SINGLE ORDER (PO TUNGGAL & READY STOCK)
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
                        ->persistent()
                        ->actions([
                            Action::make('wa_single_ready')
                                ->label('💬 Kirim WA Siap Kirim')
                                ->url($record->getWaReadyToShipLink(), shouldOpenInNewTab: true)
                                ->button()
                                ->color('info')
                                ->icon('heroicon-o-chat-bubble-left-ellipsis'),
                        ])
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
                    ]);

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
                            Action::make('wa_single_shipped')
                                ->label('💬 Kirim WA Info Supir & Muatan')
                                ->url($record->getWaBatchShippedLink(), shouldOpenInNewTab: true)
                                ->button()
                                ->color('success')
                                ->icon('heroicon-o-chat-bubble-left-ellipsis'),
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
                ->modalHeading('Konfirmasi Pesanan Diterima di Proyek')
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

                    try {
                        $email = $record->shipping_email ?? $record->user?->email;
                        if ($email) {
                            Mail::to($email)->send(new OrderStatusMail($record, 'delivered'));
                        }
                    } catch (\Throwable $e) {
                        Log::error('Single order delivered email error: '.$e->getMessage());
                    }

                    Notification::make()
                        ->title("Pesanan {$record->order_number} Telah Diterima! 🏁")
                        ->body('Material telah sukses dibongkar dan serah terima selesai.')
                        ->success()
                        ->persistent()
                        ->actions([
                            Action::make('wa_single_delivered')
                                ->label('💬 Kirim WA Bukti Serah Terima')
                                ->url($record->getWaDeliveredLink(), shouldOpenInNewTab: true)
                                ->button()
                                ->color('success')
                                ->icon('heroicon-o-chat-bubble-left-ellipsis'),
                        ])
                        ->send();

                    $this->redirect(request()->header('Referer') ?? static::getUrl(['record' => $record]));
                });
        }

        return $actions;
    }
}
