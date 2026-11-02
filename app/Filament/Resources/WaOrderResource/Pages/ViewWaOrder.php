<?php

namespace App\Filament\Resources\WaOrderResource\Pages;

use App\Filament\Resources\ManualDocumentResource;
use App\Filament\Resources\WaOrderResource;
use App\Mail\OrderStatusMail;
use App\Models\Invoice;
use App\Models\ManualDocument;
use App\Models\Order;
use App\Models\OrderBatch;
use App\Models\Payment;
use App\Models\SiteSetting;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;

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
                        ->live(debounce: 600)
                        ->helperText('Bisa ketik bebas sesuai transaksi (misal: Pembayaran #1, Pembayaran #2, dst).'),

                    Forms\Components\TextInput::make('payment_amount')
                        ->label('Nominal Uang Masuk Kali Ini (Rp)')
                        ->prefix('Rp')
                        ->numeric()
                        ->default(fn () => $record->payments()->count() === 0 ? ($record->down_payment_amount ?: round($record->grand_total * 0.5)) : ($record->remaining_balance ?: max(0, $record->grand_total - $record->total_paid_amount)))
                        ->required()
                        ->live(debounce: 600)
                        ->helperText('Masukkan nominal rupiah yang ditransfer oleh pembeli pada transaksi ini.'),

                    Forms\Components\Select::make('payment_method')
                        ->label('Metode Pembayaran')
                        ->options([
                            'BCA' => 'Transfer Bank BCA - 231-324-2412 (a/n ABDUL HAMID)',
                            'Mandiri' => 'Transfer Bank Mandiri - 1730-0197-04155 (a/n ABDUL HAMID)',
                            'BRI' => 'Transfer Bank BRI - 4356-0100-9396-502 (a/n ABDUL HAMID)',
                            'Cash' => 'Tunai / Cash di Proyek / Pabrik',
                            'Other' => 'Metode Lainnya',
                        ])
                        ->default('BCA')
                        ->required()
                        ->live(),

                    Forms\Components\Textarea::make('notes')
                        ->label('Catatan Bukti Transfer / No. Rekening Pengirim')
                        ->placeholder('Contoh: Transfer DP 10 Juta via M-Banking BCA an. Ahmad Zainal tgl 28/08')
                        ->columnSpanFull(),

                    // ── Kotak konfirmasi ringkasan (live preview sebelum simpan) ─
                    Forms\Components\Placeholder::make('konfirmasi_ringkasan')
                        ->label('')
                        ->content(function (Forms\Get $get) use ($record): HtmlString {
                            $judul = $get('installment_title') ?: '—';
                            $nominal = (float) ($get('payment_amount') ?: 0);
                            $metode = match ($get('payment_method')) {
                                'BCA' => 'Transfer Bank BCA - 231-324-2412 (a/n ABDUL HAMID)',
                                'Mandiri' => 'Transfer Bank Mandiri - 1730-0197-04155 (a/n ABDUL HAMID)',
                                'BRI' => 'Transfer Bank BRI - 4356-0100-9396-502 (a/n ABDUL HAMID)',
                                'Cash' => 'Tunai / Cash di Proyek / Pabrik',
                                default => 'Metode Lainnya',
                            };
                            $prevSum = (float) $record->payments()
                                ->whereIn('status', ['settlement', 'capture', 'paid', 'success'])
                                ->sum('gross_amount');
                            $newTotal = $prevSum + $nominal;
                            $sisa = max(0, (float) $record->grand_total - $newTotal);
                            $lunas = ($sisa <= 0 && $record->grand_total > 0);

                            $namaFmt = 'Rp '.number_format($nominal, 0, ',', '.');
                            $totalFmt = 'Rp '.number_format($newTotal, 0, ',', '.');
                            $sisaFmt = 'Rp '.number_format($sisa, 0, ',', '.');
                            $statusBadge = $lunas
                                ? '<span style="background:#22c55e;color:#fff;padding:2px 8px;border-radius:4px;font-size:12px;font-weight:700;">✅ LUNAS</span>'
                                : '<span style="background:#f59e0b;color:#fff;padding:2px 8px;border-radius:4px;font-size:12px;font-weight:700;">⏳ Belum Lunas</span>';

                            return new HtmlString(
                                '<div style="background:#fefce8;border:2px solid #fbbf24;border-radius:8px;padding:14px 16px;margin-top:4px;">'
                                .'<div style="font-weight:700;font-size:13px;color:#92400e;margin-bottom:8px;">⚠️ Pastikan data berikut sudah benar sebelum menekan simpan:</div>'
                                .'<table style="width:100%;font-size:13px;border-collapse:collapse;">'
                                .'<tr><td style="padding:3px 0;color:#78350f;width:52%;">📋 Judul Transaksi</td><td style="font-weight:600;">'.$judul.'</td></tr>'
                                .'<tr><td style="padding:3px 0;color:#78350f;">💵 Uang Masuk Kali Ini</td><td style="font-weight:700;color:#15803d;">'.$namaFmt.'</td></tr>'
                                .'<tr><td style="padding:3px 0;color:#78350f;">🏦 Metode Pembayaran</td><td style="font-weight:600;">'.$metode.'</td></tr>'
                                .'<tr><td style="padding:3px 0;color:#78350f;">📊 Total Terbayar (Setelah Ini)</td><td style="font-weight:600;">'.$totalFmt.'</td></tr>'
                                .'<tr><td style="padding:3px 0;color:#78350f;">📉 Sisa Tagihan Setelah Ini</td><td style="font-weight:700;color:'.($lunas ? '#15803d' : '#b91c1c').'">'.$sisaFmt.' '.$statusBadge.'</td></tr>'
                                .'</table>'
                                .'<div style="margin-top:10px;font-size:11px;color:#92400e;">⚡ Klik <strong>Simpan</strong> hanya sekali. Tombol akan nonaktif otomatis saat proses berjalan.</div>'
                                .'</div>'
                            );
                        })
                        ->live()
                        ->columnSpanFull(),
                ])
                ->modalSubmitActionLabel('✅ Simpan Transaksi & Terbitkan Kuitansi')
                ->action(function (array $data) use ($record) {
                    // ── Cegah duplikasi: tolak jika pembayaran nominal sama sudah tercatat dalam 15 detik terakhir
                    $amountCheck = (float) ($data['payment_amount'] ?? 0);
                    $recentDuplicate = Payment::where('order_id', $record->id)
                        ->where('gross_amount', $amountCheck)
                        ->where('created_at', '>=', now()->subSeconds(15))
                        ->exists();
                    if ($recentDuplicate) {
                        Notification::make()
                            ->title('⚠️ Pembayaran Sudah Tercatat!')
                            ->body('Pembayaran dengan nominal yang sama baru saja disimpan. Jangan klik ganda — silakan cek riwayat pembayaran.')
                            ->warning()
                            ->persistent()
                            ->send();

                        return;
                    }
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

                    try {
                        $email = $record->shipping_email ?? $record->user?->email;
                        if ($email) {
                            Mail::to($email)->send(new OrderStatusMail($record, 'payment_received', null, $payment));
                        }
                    } catch (\Throwable $e) {
                        Log::error('Record payment email error: '.$e->getMessage());
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

                    $updateData = [
                        'payment_status' => 'paid',
                        'down_payment_amount' => $grandTotal,
                        'remaining_balance' => 0,
                        'paid_at' => now(),
                    ];

                    if (in_array($record->status, ['draft', 'pending_payment'])) {
                        $updateData['status'] = 'processing';
                    }

                    $record->update($updateData);

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
                            Mail::to($email)->send(new OrderStatusMail($record, 'paid', null, $payment ?? null));
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

        // Cetak & Sesuaikan Dokumen (Invoice / Penawaran)
        $actions[] = Actions\Action::make('print_invoice')
            ->label('📄 Cetak & Edit Dokumen')
            ->icon('heroicon-o-document-text')
            ->color('success')
            ->visible(fn ($record) => $record->invoice !== null || $record->status !== 'draft')
            ->modalHeading(fn ($record) => "📄 Sesuaikan & Cetak Dokumen (#{$record->order_number})")
            ->modalDescription('Pilih judul dokumen, sesuaikan catatan atau sembunyikan baris yang tidak perlu sebelum mengunduh PDF.')
            ->modalWidth('3xl')
            ->mountUsing(function (Forms\ComponentContainer $form, $record) {
                $invoice = $record->invoice ?: Invoice::firstOrCreate(
                    ['order_id' => $record->id],
                    [
                        'invoice_number' => Invoice::generateWaInvoiceNumber(),
                        'invoice_date' => now(),
                        'subtotal' => $record->subtotal,
                        'shipping_cost' => $record->shipping_cost,
                        'shipping_type' => $record->shipping_type ?: 'paid',
                        'discount_amount' => $record->discount_amount,
                        'grand_total' => $record->grand_total,
                        'payment_scheme' => $record->payment_scheme ?: 'full',
                        'down_payment_amount' => $record->down_payment_amount ?: 0,
                        'remaining_balance' => $record->remaining_balance ?: 0,
                        'status' => $record->payment_status === 'paid' ? 'paid' : ($record->status === 'draft' ? 'draft' : 'sent'),
                        'paid_at' => $record->payment_status === 'paid' ? now() : null,
                    ]
                );

                $defaultTitle = $invoice->document_title ?: match (true) {
                    $record->payment_status === 'paid' => 'INVOICE FINAL / FAKTUR PENJUALAN RESMI (LUNAS)',
                    $record->payment_scheme === 'quotation' => 'SURAT ESTIMASI PENAWARAN HARGA (QUOTATION)',
                    in_array($record->payment_scheme, ['dp_10_90', 'dp_30_70', 'dp_50_50', 'termin_3x', 'custom_dp']) && $record->down_payment_amount > 0 => 'INVOICE & SURAT TAGIHAN PEMBAYARAN DP',
                    default => 'INVOICE TAGIHAN PENJUALAN RESMI',
                };

                $form->fill([
                    'document_title' => $defaultTitle,
                    'shipping_type' => $record->shipping_type ?: ($invoice->shipping_type ?: 'paid'),
                    'show_nego_note' => $invoice->show_nego_note ?? ($record->payment_scheme === 'quotation'),
                    'show_shipping_warning' => $invoice->show_shipping_warning ?? ($record->shipping_type === 'unconfirmed'),
                    'show_payment_schedule' => $invoice->show_payment_schedule ?? true,
                    'show_bank_accounts' => $invoice->show_bank_accounts ?? true,
                    'bank_accounts_override' => $invoice->bank_accounts_override ?: SiteSetting::getBankAccounts(),
                    'show_signature_stamp' => $invoice->show_signature_stamp ?? true,
                    'signature_name' => $invoice->signature_name ?: 'Abdul Hamid',
                    'signature_title' => $invoice->signature_title ?: 'Divisi Keuangan & Distribusi Pabrik',
                    'signature_city' => $invoice->signature_city ?: 'Purwakarta',
                    'document_notes' => $invoice->document_notes ?: $record->notes,
                    'document_terms' => $invoice->document_terms ?: "1. Penurunan material dilakukan di titik bongkar yang dapat diakses armada truk.\n2. Klaim garansi pecah wajib disertakan bukti foto saat serah terima barang.\n3. Dokumen ini merupakan bukti transaksi sah yang diterbitkan otomatis oleh sistem.",
                ]);
            })
            ->form([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\TextInput::make('document_title')
                        ->label('Judul Header Dokumen')
                        ->helperText('Contoh: INVOICE, SURAT ESTIMASI PENAWARAN HARGA, INVOICE TAGIHAN DP')
                        ->required()
                        ->columnSpan(2),

                    Forms\Components\Select::make('shipping_type')
                        ->label('Status Ongkos Kirim')
                        ->options([
                            'paid' => 'Tarif Ongkir Dihitung',
                            'free' => '🎁 Gratis Ongkir (Rp 0)',
                            'unconfirmed' => '⏳ Belum Termasuk Ongkir (Dihitung Terpisah)',
                        ])
                        ->required(),

                    Forms\Components\Toggle::make('show_payment_schedule')
                        ->label('Tampilkan Rincian DP 1 & Jadwal Pelunasan')
                        ->default(true),

                    Forms\Components\Toggle::make('show_bank_accounts')
                        ->label('Tampilkan 3 Rekening Bank Resmi Pabrik')
                        ->default(true)
                        ->live(),

                    Forms\Components\Toggle::make('show_nego_note')
                        ->label('Tampilkan Catatan "Bisa Nego Pabrik"')
                        ->helperText('Matikan jika ini invoice resmi agar pembeli tidak menawar')
                        ->default(false),

                    Forms\Components\Toggle::make('show_shipping_warning')
                        ->label('Tampilkan Peringatan Estimasi Ongkir Pabrik')
                        ->default(false),
                ]),

                Forms\Components\Section::make('Rekening Bank Resmi Pabrik (Dinamis)')
                    ->description('Anda bisa mengubah nomor rekening, atas nama, atau menambah rekening secara langsung untuk dokumen ini.')
                    ->schema([
                        Forms\Components\Repeater::make('bank_accounts_override')
                            ->label('Daftar Rekening Pembayaran')
                            ->schema([
                                Forms\Components\TextInput::make('bank_name')
                                    ->label('Nama Bank')
                                    ->placeholder('Contoh: Bank BRI / BCA')
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('account_number')
                                    ->label('Nomor Rekening')
                                    ->placeholder('Contoh: 4356-01-009396-50-2')
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('account_name')
                                    ->label('Atas Nama')
                                    ->placeholder('Contoh: ABDUL HAMID')
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true)
                                    ->inline(false)
                                    ->columnSpan(1),
                            ])
                            ->columns(7)
                            ->maxItems(3)
                            ->reorderable(true)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => ($state['bank_name'] ?? 'Bank').' - '.($state['account_number'] ?? 'No. Rekening')),
                    ])
                    ->visible(fn (Forms\Get $get) => (bool) $get('show_bank_accounts')),

                Forms\Components\Section::make('Tanda Tangan & Stempel Resmi')
                    ->description('Atur apakah dokumen menyertakan tanda tangan & stempel digital atau dikosongkan untuk tanda tangan & cap fisik basah.')
                    ->schema([
                        Forms\Components\Toggle::make('show_signature_stamp')
                            ->label('Sertakan Tanda Tangan & Stempel Digital')
                            ->helperText('Nyalakan untuk cetak PDF/kirim online WA. Matikan jika dokumen akan dicetak fisik untuk tanda tangan pena & stempel basah asli.')
                            ->default(true)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('signature_name')
                            ->label('Nama Penandatangan')
                            ->placeholder('Contoh: Abdul Hamid / H. Ahmad')
                            ->default('Abdul Hamid')
                            ->required()
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('signature_title')
                            ->label('Jabatan / Bagian')
                            ->placeholder('Contoh: Divisi Keuangan & Distribusi Pabrik')
                            ->default('Divisi Keuangan & Distribusi Pabrik')
                            ->required()
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('signature_city')
                            ->label('Kota Penerbitan Dokumen')
                            ->placeholder('Purwakarta')
                            ->default('Purwakarta')
                            ->required()
                            ->columnSpan(1),
                    ])
                    ->columns(5),

                Forms\Components\Textarea::make('document_notes')
                    ->label('Catatan Dokumen untuk Pembeli (Dinamis)')
                    ->placeholder('Hapus atau edit catatan bila tidak diperlukan...')
                    ->rows(2)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('document_terms')
                    ->label('Syarat & Ketentuan Pengiriman (Dinamis)')
                    ->helperText('Hapus kalimat yang tidak perlu sebelum dokumen diunduh')
                    ->rows(3)
                    ->columnSpanFull(),
            ])
            ->modalSubmitActionLabel('🖨️ Simpan & Buka PDF Dokumen')
            ->action(function (array $data, $record) {
                $invoice = $record->invoice ?: Invoice::firstOrCreate(
                    ['order_id' => $record->id],
                    [
                        'invoice_number' => Invoice::generateWaInvoiceNumber(),
                        'invoice_date' => now(),
                        'subtotal' => $record->subtotal,
                        'shipping_cost' => $record->shipping_cost,
                        'shipping_type' => $data['shipping_type'] ?? 'paid',
                        'discount_amount' => $record->discount_amount,
                        'grand_total' => $record->grand_total,
                        'payment_scheme' => $record->payment_scheme ?: 'full',
                        'down_payment_amount' => $record->down_payment_amount ?: 0,
                        'remaining_balance' => $record->remaining_balance ?: 0,
                        'status' => $record->payment_status === 'paid' ? 'paid' : ($record->status === 'draft' ? 'draft' : 'sent'),
                        'paid_at' => $record->payment_status === 'paid' ? now() : null,
                    ]
                );

                $invoice->update([
                    'document_title' => $data['document_title'] ?? null,
                    'shipping_type' => $data['shipping_type'] ?? 'paid',
                    'show_nego_note' => (bool) ($data['show_nego_note'] ?? false),
                    'show_shipping_warning' => (bool) ($data['show_shipping_warning'] ?? false),
                    'show_payment_schedule' => (bool) ($data['show_payment_schedule'] ?? true),
                    'show_bank_accounts' => (bool) ($data['show_bank_accounts'] ?? true),
                    'bank_accounts_override' => isset($data['bank_accounts_override']) && is_array($data['bank_accounts_override']) ? array_values($data['bank_accounts_override']) : null,
                    'show_signature_stamp' => (bool) ($data['show_signature_stamp'] ?? true),
                    'signature_name' => $data['signature_name'] ?? 'Abdul Hamid',
                    'signature_title' => $data['signature_title'] ?? 'Divisi Keuangan & Distribusi Pabrik',
                    'signature_city' => $data['signature_city'] ?? 'Purwakarta',
                    'document_notes' => $data['document_notes'] ?? null,
                    'document_terms' => $data['document_terms'] ?? null,
                ]);

                if (! empty($data['shipping_type']) && $data['shipping_type'] !== $record->shipping_type) {
                    $record->update(['shipping_type' => $data['shipping_type']]);
                }

                $printUrl = URL::signedRoute('print.invoice', ['invoice' => $invoice->id]);

                $this->js("window.open('{$printUrl}', '_blank');");

                Notification::make()
                    ->title('Dokumen Berhasil Disesuaikan & Dicetak 🖨️')
                    ->body('Dokumen PDF sedang dibuka di tab baru.')
                    ->success()
                    ->actions([
                        Action::make('open_doc_view')
                            ->label('🖨️ Buka PDF')
                            ->url($printUrl, shouldOpenInNewTab: true)
                            ->button()
                            ->color('success'),
                    ])
                    ->send();
            });

        // ============================================================
        // 0. CETAK SPP PRODUKSI & SURAT PENGANTAR AMBIL BARANG
        // Hanya muncul setelah tipe pemenuhan dikonfirmasi oleh admin
        // ============================================================
        $actions[] = Actions\Action::make('print_spk')
            ->label($record->fulfillment_type === 'po_batch' ? '📄 Cetak SPP Master Batch' : '📄 Cetak Pengajuan Produksi (SPP)')
            ->icon('heroicon-o-document-chart-bar')
            ->color('warning')
            ->visible(fn ($record) => filled($record->fulfillment_type) && ! in_array($record->status, ['pending', 'cancelled']))
            ->modalHeading($record->fulfillment_type === 'po_batch' ? 'Cetak SPP Master Batch (Semua Tahapan)' : 'Cetak Surat Pengajuan Produksi (SPP)')
            ->modalDescription('Lengkapi data pabrik / vendor & mandor sebelum mencetak dokumen SPP.')
            ->form([
                Forms\Components\TextInput::make('factory_name')
                    ->label('Nama Pabrik / Vendor')
                    ->placeholder('Pabrik Utama Plered / Nama Vendor')
                    ->datalist(['Pabrik Utama Plered (Purwakarta)', 'Pabrik Anjun Plered', 'Pabrik Cadasmekar', 'CV. Sumber Berkah Roster'])
                    ->default(fn () => $record->factory_name ?: 'Pabrik Utama Plered (Purwakarta)')
                    ->required(),
                Forms\Components\TextInput::make('factory_pic_name')
                    ->label('Nama Mandor / PIC Pabrik')
                    ->placeholder('Contoh: Kang Asep')
                    ->default(fn () => $record->factory_pic_name ?: 'Kang Asep')
                    ->required(),
                Forms\Components\TextInput::make('factory_pic_phone')
                    ->label('No. HP / WhatsApp Mandor')
                    ->default(fn () => $record->factory_pic_phone)
                    ->tel(),
                Forms\Components\Textarea::make('factory_notes')
                    ->label('Catatan Khusus Produksi (Opsional)')
                    ->default(fn () => $record->fulfillment_notes)
                    ->columnSpanFull(),
            ])
            ->modalSubmitActionLabel('🖨️ Simpan & Buka SPP PDF')
            ->action(function (array $data) use ($record) {
                $record->update([
                    'factory_name' => $data['factory_name'] ?? null,
                    'factory_pic_name' => $data['factory_pic_name'] ?? null,
                    'factory_pic_phone' => $data['factory_pic_phone'] ?? null,
                    'fulfillment_notes' => $data['factory_notes'] ?? null,
                ]);

                $printUrl = route('print.production-order', $record);

                $this->js("window.open('{$printUrl}', '_blank');");

                Notification::make()
                    ->title('Data SPP Produksi Tersimpan')
                    ->body('Dokumen SPP PDF sedang dibuka di tab baru.')
                    ->success()
                    ->persistent()
                    ->actions([
                        Action::make('open_spk_pdf')
                            ->label('🖨️ Buka Dokumen SPP')
                            ->url($printUrl, shouldOpenInNewTab: true)
                            ->button()
                            ->color('warning'),
                    ])
                    ->send();
            });

        // Cetak Surat Pengantar Ambil Barang dari Pabrik / Vendor
        $actions[] = Actions\Action::make('print_spab')
            ->label('🚚 Cetak Pengantar Ambil Barang')
            ->icon('heroicon-o-truck')
            ->color('warning')
            ->visible(fn ($record) => filled($record->fulfillment_type) && ! in_array($record->status, ['pending', 'cancelled']))
            ->modalHeading('Cetak Surat Pengantar Pengambilan Barang (SPPB)')
            ->modalDescription('Lengkapi data pabrik / vendor dan supir armada penjemput.')
            ->form([
                Forms\Components\TextInput::make('factory_name')
                    ->label('Nama Pabrik / Vendor')
                    ->placeholder('CV. Sumber Berkah Roster / Pabrik Anjun')
                    ->datalist(['CV. Sumber Berkah Roster', 'Pabrik Anjun Plered', 'Pabrik Cadasmekar', 'Pabrik Utama Plered (Purwakarta)'])
                    ->default(fn () => $record->factory_name ?: 'CV. Sumber Berkah Roster')
                    ->required(),
                Forms\Components\TextInput::make('factory_pic_name')
                    ->label('Pemilik / Mandor / PIC Pabrik')
                    ->placeholder('Contoh: Pak Asep Hidayat')
                    ->default(fn () => $record->factory_pic_name ?: 'Pak Asep Hidayat')
                    ->required(),
                Forms\Components\TextInput::make('factory_pic_phone')
                    ->label('No. Telepon / WA PIC Pabrik')
                    ->default(fn () => $record->factory_pic_phone)
                    ->tel(),
                Forms\Components\Textarea::make('factory_address')
                    ->label('Alamat Pabrik / Lokasi Pengambilan')
                    ->placeholder('Contoh: Jl. Raya Anjun No. 45, Plered, Purwakarta')
                    ->default(fn () => $record->factory_address ?: 'Jl. Raya Anjun No. 45, Plered, Purwakarta')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('pickup_driver_name')
                    ->label('Nama Supir Penjemput')
                    ->default(fn () => $record->pickup_driver_name ?: ($record->courier ?: 'Mulyadi (Supir Internal)'))
                    ->required(),
                Forms\Components\TextInput::make('pickup_driver_plate')
                    ->label('Nomor Plat Truk Penjemput')
                    ->default(fn () => $record->pickup_driver_plate ?: ($record->tracking_number ?: 'T 8472 AB'))
                    ->required(),
                Forms\Components\Textarea::make('factory_notes')
                    ->label('Catatan Tambahan Penjemputan')
                    ->default(fn () => $record->fulfillment_notes)
                    ->columnSpanFull(),
            ])
            ->modalSubmitActionLabel('🖨️ Simpan & Buka Surat Pengantar PDF')
            ->action(function (array $data) use ($record) {
                $record->update([
                    'factory_name' => $data['factory_name'] ?? null,
                    'factory_pic_name' => $data['factory_pic_name'] ?? null,
                    'factory_pic_phone' => $data['factory_pic_phone'] ?? null,
                    'factory_address' => $data['factory_address'] ?? null,
                    'pickup_driver_name' => $data['pickup_driver_name'] ?? null,
                    'pickup_driver_plate' => $data['pickup_driver_plate'] ?? null,
                    'fulfillment_notes' => $data['factory_notes'] ?? null,
                ]);

                $printUrl = route('print.pickup-order', $record);

                $this->js("window.open('{$printUrl}', '_blank');");

                Notification::make()
                    ->title('Data Pengantar Penjemputan Tersimpan')
                    ->body('Surat pengantar PDF sedang dibuka di tab baru.')
                    ->success()
                    ->persistent()
                    ->actions([
                        Action::make('open_spab_pdf')
                            ->label('🚚 Buka Surat Pengantar')
                            ->url($printUrl, shouldOpenInNewTab: true)
                            ->button()
                            ->color('warning'),
                    ])
                    ->send();
            });

        // Cetak Rincian Proses Pesanan / Surat Jalan
        $actions[] = Actions\Action::make('print_order')
            ->label(fn ($record) => $record->fulfillment_type === 'po_batch' ? '📋 Cetak Rincian Jadwal PO Batch' : '🖨️ Cetak Surat Jalan')
            ->icon('heroicon-o-printer')
            ->color('info')
            ->url(fn ($record) => route('print.order', $record))
            ->openUrlInNewTab();

        // Terbitkan Dokumen Tambahan (Retail, B2B Proyek, Ekspor)
        $actions[] = Actions\Action::make('generate_custom_document')
            ->label('📄 Terbitkan Dokumen Tambahan')
            ->icon('heroicon-o-document-plus')
            ->color('warning')
            ->modalHeading("Terbitkan Dokumen Resmi Khusus — {$record->order_number}")
            ->modalDescription('Pilih kategori dan tipe dokumen yang ingin diterbitkan (Retail B2C, Kontraktor B2B, atau Ekspor Internasional). Data pesanan otomatis diimpor.')
            ->modalWidth('4xl')
            ->form([
                Forms\Components\Grid::make(3)->schema([
                    Forms\Components\Select::make('category')
                        ->label('1. Kategori Kebutuhan Dokumen')
                        ->options([
                            'retail' => '🏠 Retail / B2C (Pembeli Biasa)',
                            'b2b' => '🏢 Kontraktor / Developer Proyek (B2B)',
                            'export' => '🌐 Ekspor Internasional (Valas / Port Tanjung Priok)',
                        ])
                        ->default('b2b')
                        ->live()
                        ->required()
                        ->columnSpan(1),

                    Forms\Components\Select::make('type')
                        ->label('2. Tipe Dokumen yang Diterbitkan')
                        ->options(function (Forms\Get $get) {
                            $cat = $get('category');
                            if ($cat === 'retail') {
                                return [
                                    'penawaran' => '📝 Surat Penawaran Harga (SPH)',
                                    'faktur' => '🧾 Faktur Penjualan (Invoice)',
                                    'kwitansi' => '💰 Kwitansi Pembayaran Sah',
                                    'surat_jalan' => '🚚 Surat Jalan Pengiriman',
                                ];
                            } elseif ($cat === 'export') {
                                return [
                                    'commercial_invoice' => '🚢 Commercial Invoice (Ekspor - USD / Valas)',
                                    'export_packing_list' => '📦 Export Packing List (Peti Kayu / Crates)',
                                    'certificate_of_origin' => '📜 Certificate of Origin (COO / Form D)',
                                    'shipping_instruction' => '🚢 Shipping Instruction (Ocean Freight)',
                                ];
                            } else {
                                return [
                                    'spk' => '📜 Surat Perjanjian Kerja Sama (SPK / Kontrak Jual Beli B2B)',
                                    'bast' => '📋 Berita Acara Serah Terima (BAST Lapangan Proyek)',
                                    'sph' => '📝 SPH Resmi Tender / Surat Dukungan',
                                    'lab_test' => '🔬 Sertifikat Uji Kuat Tekan Lab SNI',
                                    'kwitansi' => '💰 Kwitansi Bermaterai (Rp 10.000)',
                                    'faktur' => '📑 Faktur Komersial B2B',
                                ];
                            }
                        })
                        ->default('bast')
                        ->live()
                        ->required()
                        ->columnSpan(2),
                ]),

                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Placeholder::make('document_number_preview')
                        ->label('Nomor Dokumen (otomatis)')
                        ->content(function (Forms\Get $get) use ($record) {
                            $type = $get('type') ?: 'bast';
                            $prefix = strtoupper(str_replace('_', '-', $type));
                            $year = date('Y');
                            $cleanNo = preg_replace('/[^0-9]/', '', $record->order_number);
                            $candidate = "{$prefix}/IR-PWK/{$year}/".substr($cleanNo, -4);

                            if (ManualDocument::where('document_number', $candidate)->exists()) {
                                $count = ManualDocument::where('document_number', 'like', "{$prefix}/IR-PWK/{$year}/%")->count() + 1;
                                $candidate = sprintf('%s/IR-PWK/%s/%04d', $prefix, $year, $count);
                                while (ManualDocument::where('document_number', $candidate)->exists()) {
                                    $count++;
                                    $candidate = sprintf('%s/IR-PWK/%s/%04d', $prefix, $year, $count);
                                }
                            }

                            return $candidate;
                        }),

                    Forms\Components\DatePicker::make('document_date')
                        ->label('Tanggal Terbit Dokumen')
                        ->default(now())
                        ->required(),
                ]),

                Forms\Components\Fieldset::make('Informasi Klien / Perusahaan')
                    ->schema([
                        Forms\Components\TextInput::make('client_name')
                            ->label('Nama Klien / PT / Instansi')
                            ->default(fn () => $record->shipping_name)
                            ->required(),

                        Forms\Components\TextInput::make('client_phone')
                            ->label('No. WhatsApp / Telepon')
                            ->default(fn () => $record->shipping_phone),

                        Forms\Components\TextInput::make('client_email')
                            ->label('Email Klien')
                            ->default(fn () => $record->shipping_email),

                        Forms\Components\Textarea::make('client_address')
                            ->label('Alamat Proyek / Lokasi Tujuan')
                            ->default(fn () => $record->shipping_address.($record->shipping_city ? ', '.$record->shipping_city : '').($record->shipping_province ? ', '.$record->shipping_province : ''))
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(3),

                Forms\Components\Fieldset::make('Rincian Produk & Nilai Dokumen')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->label('Daftar Produk / Barang')
                            ->default(function () use ($record) {
                                return $record->items->map(function ($it) {
                                    return [
                                        'product_name' => $it->product_name.($it->variant ? ' ('.$it->variant->name.')' : ''),
                                        'quantity' => $it->quantity,
                                        'price' => $it->product_price,
                                        'total' => $it->subtotal,
                                    ];
                                })->toArray();
                            })
                            ->schema([
                                Forms\Components\TextInput::make('product_name')
                                    ->label('Nama Barang / Uraian')
                                    ->required()
                                    ->columnSpan(4),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Qty (pcs)')
                                    ->numeric()
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('total')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->columnSpan(3),
                            ])
                            ->columns(12)
                            ->columnSpanFull(),

                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\TextInput::make('subtotal')
                                ->label('Subtotal')
                                ->numeric()
                                ->prefix('Rp')
                                ->default(fn () => $record->subtotal)
                                ->required(),

                            Forms\Components\TextInput::make('discount')
                                ->label('Diskon')
                                ->numeric()
                                ->prefix('Rp')
                                ->default(fn () => $record->discount_amount ?: 0),

                            Forms\Components\TextInput::make('grand_total')
                                ->label('Grand Total')
                                ->numeric()
                                ->prefix('Rp')
                                ->default(fn () => $record->grand_total)
                                ->required(),
                        ]),
                    ]),

                Forms\Components\Fieldset::make('Penandatangan Dokumen')
                    ->schema([
                        Forms\Components\TextInput::make('issued_by')
                            ->label('Nama Penandatangan (Pabrik)')
                            ->default(fn () => auth()->user()?->name ?? 'Abdul Hamid')
                            ->required()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('signer_role')
                            ->label('Jabatan Penandatangan')
                            ->default(function (Forms\Get $get) {
                                $type = $get('type') ?: 'bast';

                                return match ($type) {
                                    'bast' => 'Petugas Logistik / Pengiriman',
                                    'surat_jalan' => 'Bagian Logistik & Armada',
                                    'lab_test' => 'Quality Control Pabrik',
                                    'sph', 'penawaran' => 'Bagian Penjualan / Marketing',
                                    'spk' => 'Pimpinan Pabrik / Manajemen',
                                    'faktur', 'kwitansi' => 'Bagian Keuangan & Kasir',
                                    default => 'Authorized Signatory',
                                };
                            })
                            ->required()
                            ->columnSpan(1),
                    ])->columns(2),

                Forms\Components\Textarea::make('notes')
                    ->label('Catatan Khusus / Keterangan Legalitas')
                    ->placeholder('Contoh: Dokumen ini diterbitkan sebagai Berita Acara Serah Terima Resmi Proyek Cluster...')
                    ->rows(2)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('custom_terms_and_conditions')
                    ->label('Klausul Syarat & Ketentuan (Bisa Disesuaikan)')
                    ->helperText('Anda dapat mengedit, menambah, atau menghapus poin klausul di bawah ini sesuai kesepakatan.')
                    ->default(function (Forms\Get $get) {
                        $type = $get('type') ?: 'bast';
                        if ($type === 'sph') {
                            return "1. Penawaran harga ini berlaku selama 30 (tiga puluh) hari kalender sejak tanggal diterbitkan.\n".
                                "2. Pembayaran DP (uang muka) minimal 50% wajib dilunasi untuk mengkonfirmasi antrean produksi.\n".
                                "3. Pelunasan sisa pembayaran dilakukan sebelum barang dikirimkan dari fasilitas produksi kami.\n".
                                "4. Gratis ongkir untuk area Jabodetabek dengan pembelian minimal 100 pcs per pengiriman.\n".
                                "5. Retak atau kerusakan yang terjadi saat pengiriman akan diganti baru tanpa biaya tambahan.\n".
                                '6. Estimasi waktu produksi 3–7 hari kerja, tergantung volume dan jenis motif yang dipesan.';
                        } elseif ($type === 'bast') {
                            return "1. Pemeriksaan fisik kuantitas dan mutu dilaksanakan bersama di lokasi bongkar proyek.\n".
                                "2. Laporan kerusakan akibat pengiriman dilakukan dalam 1x24 jam setelah penerimaan barang.\n".
                                '3. Kerusakan saat pengiriman oleh armada pabrik diganti 100% baru.';
                        } elseif ($type === 'lab_test') {
                            return "1. Sertifikat mutu produksi internal mengacu pada standar SNI 03-0349-1989.\n".
                                "2. Bahan baku pasir abu batu murni pilihan dengan kepadatan optimal.\n".
                                '3. Siap koordinasi uji laboratorium independen terakreditasi KAN atas permintaan proyek.';
                        }

                        return null;
                    })
                    ->rows(4)
                    ->columnSpanFull(),
            ])
            ->modalSubmitActionLabel('📄 Terbitkan & Buat Dokumen')
            ->action(function (array $data) use ($record) {
                // Selalu hitung ulang nomor dokumen yang aman saat action dijalankan
                // (tidak bergantung pada nilai form yang mungkin sudah stale / tidak terupdate)
                $type = $data['type'] ?? 'bast';
                $prefix = strtoupper(str_replace('_', '-', $type));
                $year = date('Y');
                $cleanNo = preg_replace('/[^0-9]/', '', $record->order_number);
                $candidate = "{$prefix}/IR-PWK/{$year}/".substr($cleanNo, -4);

                // Jika kandidat sudah ada, cari nomor berikutnya yang masih bebas
                if (ManualDocument::where('document_number', $candidate)->exists()) {
                    $count = ManualDocument::where('document_number', 'like', "{$prefix}/IR-PWK/{$year}/%")->count() + 1;
                    $candidate = sprintf('%s/IR-PWK/%s/%04d', $prefix, $year, $count);
                    while (ManualDocument::where('document_number', $candidate)->exists()) {
                        $count++;
                        $candidate = sprintf('%s/IR-PWK/%s/%04d', $prefix, $year, $count);
                    }
                }

                $docNumber = $candidate;

                // Susun extra_data dengan konten teks resmi per tipe dokumen B2B
                $docType = $data['type'];
                $clientName = $data['client_name'];
                $projectAddress = $data['client_address'] ?? '-';
                $docDate = isset($data['document_date'])
                    ? Carbon::parse($data['document_date'])->translatedFormat('d F Y')
                    : now()->translatedFormat('d F Y');

                $extraData = [
                    'order_id' => $record->id,
                    'order_number' => $record->order_number,
                    'category' => $data['category'] ?? 'b2b',
                    'notes' => $data['notes'] ?? null,
                    'watermark' => 'none', // ← dokumen final, tanpa watermark
                ];

                // Inject teks konten resmi berdasarkan tipe dokumen (atau dari input form jika user mengubahnya)
                if (! empty($data['custom_terms_and_conditions'])) {
                    $extraData['terms_and_conditions'] = $data['custom_terms_and_conditions'];
                } elseif ($docType === 'sph') {
                    $extraData['terms_title'] = 'Syarat & Ketentuan Penawaran';
                    $extraData['terms_and_conditions'] =
                        "1. Penawaran harga ini berlaku selama 30 (tiga puluh) hari kalender sejak tanggal diterbitkan.\n".
                        "2. Pembayaran DP (uang muka) minimal 50% wajib dilunasi untuk mengkonfirmasi antrean produksi.\n".
                        "3. Pelunasan sisa pembayaran dilakukan sebelum barang dikirimkan dari fasilitas produksi kami.\n".
                        "4. Gratis ongkir untuk area Jabodetabek dengan pembelian minimal 100 pcs per pengiriman.\n".
                        "5. Retak atau kerusakan yang terjadi saat pengiriman akan diganti baru tanpa biaya tambahan.\n".
                        "6. Estimasi waktu produksi 3–7 hari kerja, tergantung volume dan jenis motif yang dipesan.\n".
                        "7. Harga dapat berubah apabila terjadi perubahan volume atau spesifikasi dari permintaan awal.\n".
                        '8. Untuk kebutuhan sertifikasi uji lab resmi SNI, IndoRoster siap berkoordinasi dengan lembaga terakreditasi KAN atas permintaan owner proyek.';
                } elseif ($docType === 'lab_test') {
                    $extraData['terms_title'] = 'Keterangan Mutu Produksi & Standar Acuan';
                    $extraData['terms_and_conditions'] =
                        "KETERANGAN MUTU PRODUKSI INDOROSTER INDONESIA:\n\n".
                        "• Bahan Baku: Pasir abu batu murni pilihan dari sumber terpilih, bukan pasir silika.\n".
                        "• Proses Produksi: Cetak tumbuk padat menggunakan plat baja presisi — bukan proses cor tuang biasa.\n".
                        "• Keunggulan: Kepadatan tinggi, permukaan halus merata, dimensi presisi, tahan cuaca dan UV.\n".
                        "• Standar Acuan Internal: SNI 03-0349-1989 (Bata Beton untuk Dinding) sebagai referensi terdekat.\n".
                        "• Masa Garansi: 30 hari sejak tanggal terima di lokasi. Kerusakan akibat pengiriman diganti baru.\n\n".
                        "CATATAN PENTING:\n".
                        'Dokumen ini merupakan Sertifikat Mutu Produksi Internal dari INDOROSTER INDONESIA. Untuk kebutuhan sertifikasi uji laboratorium resmi oleh lembaga terakreditasi KAN (Komite Akreditasi Nasional) sesuai SNI 1974:2011, IndoRoster siap berkoordinasi atas permintaan resmi dari pihak owner proyek.';
                } elseif ($docType === 'bast') {
                    $extraData['terms_title'] = 'Ketentuan Serah Terima & Klausul Garansi';
                    $extraData['terms_and_conditions'] =
                        "PERNYATAAN SERAH TERIMA:\n".
                        "Pada hari dan tanggal tersebut di atas, bertempat di {$projectAddress}, kami yang bertanda tangan di bawah ini:\n".
                        "1. PIHAK PERTAMA: INDOROSTER INDONESIA, Kp. Cicadas RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat.\n".
                        "2. PIHAK KEDUA: {$clientName} (selaku Penerima Material).\n\n".
                        "Dengan ini menyatakan bahwa PIHAK PERTAMA telah menyerahkan dan PIHAK KEDUA telah menerima material tersebut di atas dalam kondisi:\n".
                        "☐  Baik dan sesuai pesanan\n".
                        "☐  Terdapat catatan (lihat keterangan)\n\n".
                        "KLAUSUL GARANSI:\n".
                        "• Laporan kerusakan akibat pengiriman wajib dilakukan dalam 1×24 jam setelah penerimaan.\n".
                        "• Material yang retak/rusak akibat pengiriman akan diganti baru oleh PIHAK PERTAMA.\n".
                        '• Kerusakan yang disebabkan oleh kesalahan pemasangan di luar tanggung jawab PIHAK PERTAMA.';
                } elseif (in_array($docType, ['faktur', 'invoice'])) {
                    $extraData['payment_instructions_title'] = 'Petunjuk Pembayaran';
                    $extraData['payment_instructions'] =
                        "Transfer Bank BCA No. Rek: (hubungi admin untuk detil rekening) a/n INDOROSTER INDONESIA\n".
                        "Bayar DP minimal 50% untuk konfirmasi pesanan.\n".
                        'Pelunasan dilakukan sebelum barang dikirim dari fasilitas produksi kami.';
                } elseif ($docType === 'kwitansi') {
                    $extraData['receipt_notes_title'] = 'Keterangan';
                    $extraData['receipt_notes'] =
                        "Kwitansi ini merupakan bukti penerimaan pembayaran yang sah dari INDOROSTER INDONESIA.\n".
                        'Harap disimpan sebagai arsip transaksi Anda.';
                } elseif (in_array($docType, ['spk', 'perjanjian'])) {
                    $extraData['terms_title'] = 'Ketentuan Umum Perjanjian';
                    $extraData['terms_and_conditions'] =
                        "1. Perjanjian ini berlaku sejak ditandatangani oleh kedua belah pihak.\n".
                        "2. Pembayaran DP 50% wajib dilunasi dalam 3 hari kerja setelah penandatanganan.\n".
                        "3. Pelunasan sisa 50% dilakukan sebelum barang dikirim dari fasilitas produksi.\n".
                        "4. Perubahan spesifikasi atau volume wajib dikomunikasikan paling lambat 2 hari sebelum produksi dimulai.\n".
                        '5. Sengketa diselesaikan secara musyawarah, dan jika tidak tercapai, melalui jalur hukum yang berlaku.';
                }

                if (! empty($data['signer_role'])) {
                    $extraData['signer_role'] = $data['signer_role'];
                }

                $doc = ManualDocument::create([
                    'document_number' => $docNumber,
                    'type' => $docType,
                    'client_name' => $clientName,
                    'client_address' => $projectAddress,
                    'client_phone' => $data['client_phone'] ?? null,
                    'client_email' => $data['client_email'] ?? null,
                    'items' => $data['items'] ?? [],
                    'subtotal' => (float) ($data['subtotal'] ?? 0),
                    'discount' => (float) ($data['discount'] ?? 0),
                    'has_tax' => false,
                    'tax_amount' => 0,
                    'grand_total' => (float) ($data['grand_total'] ?? 0),
                    'document_date' => $data['document_date'] ?? now(),
                    'issued_by' => $data['issued_by'] ?? (auth()->user()?->name ?? 'Abdul Hamid'),
                    'status' => 'final',
                    'notes' => $data['notes'] ?? null,
                    'extra_data' => $extraData,
                ]);

                $docUrl = route('print.manual-document', $doc->id);
                $phone = preg_replace('/[^0-9]/', '', $record->shipping_phone ?? '');
                if (str_starts_with($phone, '0')) {
                    $phone = '62'.substr($phone, 1);
                }
                $waMsg = rawurlencode("Halo {$record->shipping_name}, berikut kami lampirkan dokumen resmi {$doc->getTypeLabel()} untuk pesanan #{$record->order_number}:\n\n🔗 Link Dokumen PDF: {$docUrl}\n\nTerima kasih.");
                $waLink = "https://wa.me/{$phone}?text={$waMsg}";

                $editUrl = ManualDocumentResource::getUrl('edit', ['record' => $doc]);

                Notification::make()
                    ->title("Dokumen {$doc->getTypeLabel()} Berhasil Diterbitkan! 📄")
                    ->body("No. Dokumen: {$doc->document_number}. Siap dicetak, diedit ulang, atau dikirimkan ke pembeli.")
                    ->success()
                    ->persistent()
                    ->actions([
                        Action::make('print_doc_'.$doc->id)
                            ->label('🖨️ Cetak PDF Dokumen')
                            ->url($docUrl, shouldOpenInNewTab: true)
                            ->button()
                            ->color('success')
                            ->icon('heroicon-o-printer'),
                        Action::make('edit_doc_'.$doc->id)
                            ->label('✏️ Edit Dokumen Ini')
                            ->url($editUrl, shouldOpenInNewTab: true)
                            ->button()
                            ->color('warning')
                            ->icon('heroicon-o-pencil-square'),
                        Action::make('wa_doc_'.$doc->id)
                            ->label('💬 Kirim via WhatsApp')
                            ->url($waLink, shouldOpenInNewTab: true)
                            ->button()
                            ->icon('heroicon-o-chat-bubble-left-ellipsis'),
                    ])
                    ->send();
            });

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

                // Aksi: Cetak SPP Produksi Per-Batch (dengan modal input mandor/pabrik)
                $actions[] = Actions\Action::make('batch_print_spk_'.$batchId)
                    ->label("📄 Cetak SPP {$batchName}")
                    ->icon('heroicon-o-document-chart-bar')
                    ->color('warning')
                    ->extraAttributes(['style' => 'display: none !important;'])
                    ->modalHeading("Cetak Pengajuan Produksi (SPP): {$batchName}")
                    ->modalDescription("Lengkapi data mandor & pabrik yang mengerjakan {$batchName} (".number_format($batch->quantity, 0, ',', '.').' pcs).')
                    ->form([
                        Forms\Components\TextInput::make('factory_name')
                            ->label('Nama Pabrik / Vendor')
                            ->placeholder('Pabrik Utama Plered / Nama Vendor')
                            ->datalist(['Pabrik Utama Plered (Purwakarta)', 'Pabrik Anjun Plered', 'Pabrik Cadasmekar', 'CV. Sumber Berkah Roster'])
                            ->default(fn () => $batch->factory_name ?: ($record->factory_name ?: 'Pabrik Utama Plered (Purwakarta)'))
                            ->required(),
                        Forms\Components\TextInput::make('factory_pic_name')
                            ->label('Nama Mandor / PIC Pabrik')
                            ->placeholder('Contoh: Kang Asep')
                            ->default(fn () => $batch->factory_pic_name ?: ($record->factory_pic_name ?: 'Kang Asep'))
                            ->required(),
                        Forms\Components\TextInput::make('factory_pic_phone')
                            ->label('No. HP / WhatsApp Mandor')
                            ->default(fn () => $batch->factory_pic_phone ?: $record->factory_pic_phone)
                            ->tel(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Khusus Batch (Opsional)')
                            ->default(fn () => $batch->notes ?: $record->fulfillment_notes)
                            ->columnSpanFull(),
                    ])
                    ->modalSubmitActionLabel('🖨️ Simpan & Buka SPP PDF')
                    ->action(function (array $data) use ($batchId, $record) {
                        $batch = OrderBatch::find($batchId);
                        if ($batch) {
                            $batch->update([
                                'source_type' => 'internal',
                                'factory_name' => $data['factory_name'] ?? null,
                                'factory_pic_name' => $data['factory_pic_name'] ?? null,
                                'factory_pic_phone' => $data['factory_pic_phone'] ?? null,
                                'notes' => $data['notes'] ?? null,
                            ]);
                        }

                        $printUrl = route('print.production-order', ['order' => $record->id, 'batch_id' => $batchId]);

                        $this->js("window.open('{$printUrl}', '_blank');");

                        Notification::make()
                            ->title("Data SPP {$batch?->batch_name} Tersimpan")
                            ->body('Dokumen SPP PDF sedang dibuka di tab baru.')
                            ->success()
                            ->persistent()
                            ->actions([
                                Action::make('open_pdf')
                                    ->label('🖨️ Buka Dokumen SPP')
                                    ->url($printUrl, shouldOpenInNewTab: true)
                                    ->button()
                                    ->color('warning'),
                            ])
                            ->send();
                    });

                // Aksi: Cetak Pengantar Ambil Barang Per-Batch (dari pabrik / vendor)
                $actions[] = Actions\Action::make('batch_print_spab_'.$batchId)
                    ->label("🚚 Cetak Pengantar Ambil {$batchName}")
                    ->icon('heroicon-o-truck')
                    ->color('warning')
                    ->extraAttributes(['style' => 'display: none !important;'])
                    ->modalHeading("Cetak Surat Pengantar Pengambilan Barang: {$batchName}")
                    ->modalDescription("Lengkapi data pabrik / vendor & armada penjemput untuk {$batchName} (".number_format($batch->quantity, 0, ',', '.').' pcs).')
                    ->form([
                        Forms\Components\TextInput::make('factory_name')
                            ->label('Nama Pabrik / Vendor')
                            ->placeholder('CV. Sumber Berkah Roster / Pabrik Anjun')
                            ->datalist(['CV. Sumber Berkah Roster', 'Pabrik Anjun Plered', 'Pabrik Cadasmekar', 'Pabrik Utama Plered (Purwakarta)'])
                            ->default(fn () => $batch->factory_name ?: 'CV. Sumber Berkah Roster')
                            ->required(),
                        Forms\Components\TextInput::make('factory_pic_name')
                            ->label('Pemilik / Mandor / PIC Pabrik')
                            ->placeholder('Contoh: Pak Asep Hidayat')
                            ->default(fn () => $batch->factory_pic_name ?: 'Pak Asep Hidayat')
                            ->required(),
                        Forms\Components\TextInput::make('factory_pic_phone')
                            ->label('No. Telepon / WA PIC Pabrik')
                            ->default(fn () => $batch->factory_pic_phone)
                            ->tel(),
                        Forms\Components\Textarea::make('factory_address')
                            ->label('Alamat Pabrik / Lokasi Pengambilan')
                            ->placeholder('Contoh: Jl. Raya Anjun No. 45, Plered, Purwakarta')
                            ->default(fn () => $batch->factory_address ?: 'Jl. Raya Anjun No. 45, Plered, Purwakarta')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('pickup_driver_name')
                            ->label('Nama Supir Penjemput')
                            ->default(fn () => $batch->pickup_driver_name ?: ($batch->courier_name ?: 'Mulyadi (Supir Internal)'))
                            ->required(),
                        Forms\Components\TextInput::make('pickup_driver_plate')
                            ->label('Nomor Plat Truk Penjemput')
                            ->default(fn () => $batch->pickup_driver_plate ?: ($batch->tracking_number ?: 'T 8472 AB'))
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Pengambilan (Opsional)')
                            ->default(fn () => $batch->notes ?: $record->fulfillment_notes)
                            ->columnSpanFull(),
                    ])
                    ->modalSubmitActionLabel('🖨️ Simpan & Buka Surat Pengantar PDF')
                    ->action(function (array $data) use ($batchId, $record) {
                        $batch = OrderBatch::find($batchId);
                        if ($batch) {
                            $batch->update([
                                'source_type' => 'vendor_pickup',
                                'factory_name' => $data['factory_name'] ?? null,
                                'factory_pic_name' => $data['factory_pic_name'] ?? null,
                                'factory_pic_phone' => $data['factory_pic_phone'] ?? null,
                                'factory_address' => $data['factory_address'] ?? null,
                                'pickup_driver_name' => $data['pickup_driver_name'] ?? null,
                                'pickup_driver_plate' => $data['pickup_driver_plate'] ?? null,
                                'notes' => $data['notes'] ?? null,
                            ]);
                        }

                        $printUrl = route('print.pickup-order', ['order' => $record->id, 'batch_id' => $batchId]);

                        $this->js("window.open('{$printUrl}', '_blank');");

                        Notification::make()
                            ->title("Data Pengantar Ambil {$batch?->batch_name} Tersimpan")
                            ->body('Surat pengantar PDF sedang dibuka di tab baru.')
                            ->success()
                            ->persistent()
                            ->actions([
                                Action::make('open_pdf')
                                    ->label('🖨️ Buka Surat Pengantar Ambil Barang')
                                    ->url($printUrl, shouldOpenInNewTab: true)
                                    ->button()
                                    ->color('warning'),
                            ])
                            ->send();
                    });

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
