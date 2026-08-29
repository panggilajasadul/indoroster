<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Mail\InvoiceMail;
use App\Mail\OrderStatusMail;
use App\Models\Order;
use App\Models\OrderBatch;
use App\Models\User;
use App\Notifications\OrderStatusUpdated;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Actions\Action;
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

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Pesanan';

    protected static ?string $modelLabel = 'Pesanan';

    protected static ?string $pluralModelLabel = 'Pesanan';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where(function ($query) {
                $query->whereNull('order_source')
                    ->orWhere('order_source', '!=', 'whatsapp');
            });
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'paid')
            ->where(function ($query) {
                $query->whereNull('order_source')
                    ->orWhere('order_source', '!=', 'whatsapp');
            })
            ->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Detail Pesanan')
                            ->schema([
                                TextInput::make('order_number')
                                    ->label('No. Pesanan')
                                    ->disabled(),
                                Forms\Components\Select::make('user_id')
                                    ->label('Pembeli')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Forms\Components\Select::make('status')
                                    ->label('Status Pesanan')
                                    ->options([
                                        'pending_payment' => '⏳ Menunggu Pembayaran',
                                        'paid' => '💰 Dibayar',
                                        'processing' => '🔧 Diproses',
                                        'shipped' => '🚚 Dikirim',
                                        'delivered' => '📦 Diterima',
                                        'completed' => '✅ Selesai',
                                        'cancelled' => '❌ Dibatalkan',
                                    ])
                                    ->required(),
                                Forms\Components\Select::make('payment_status')
                                    ->label('Status Pembayaran')
                                    ->options([
                                        'unpaid' => '🔴 Belum Bayar',
                                        'paid' => '🟢 Lunas',
                                        'expired' => '⚪ Kedaluwarsa',
                                        'failed' => '🔴 Gagal',
                                        'refunded' => '🔵 Refund',
                                    ])
                                    ->required(),
                            ])->columns(2),

                        Forms\Components\Section::make('Alamat Pengiriman')
                            ->schema([
                                TextInput::make('shipping_name')
                                    ->label('Nama Penerima')
                                    ->required(),
                                TextInput::make('shipping_email')
                                    ->label('Email Penerima')
                                    ->email()
                                    ->required(),
                                TextInput::make('shipping_phone')
                                    ->label('No. HP Penerima')
                                    ->tel()
                                    ->required(),
                                Forms\Components\Textarea::make('shipping_address')
                                    ->label('Alamat Lengkap')
                                    ->required()
                                    ->rows(3)
                                    ->columnSpanFull(),
                                TextInput::make('shipping_city')
                                    ->label('Kota'),
                                TextInput::make('shipping_province')
                                    ->label('Provinsi'),
                                TextInput::make('shipping_postal_code')
                                    ->label('Kode Pos'),
                            ])->columns(3),

                        Forms\Components\Section::make('Catatan & Instruksi Surat Jalan')
                            ->schema([
                                Forms\Components\Textarea::make('notes')
                                    ->label('Catatan Pembeli saat Checkout')
                                    ->helperText('Otomatis tercetak di Surat Jalan untuk dibaca supir/pembongkar.')
                                    ->rows(2),
                                Forms\Components\Textarea::make('requested_batch_notes')
                                    ->label('Permintaan Jadwal Bertahap Pembeli')
                                    ->disabled()
                                    ->rows(2),
                                Forms\Components\Textarea::make('fulfillment_notes')
                                    ->label('Instruksi Tambahan dari Admin / Pabrik untuk Surat Jalan')
                                    ->helperText('Contoh: Hubungi mandor Pak Budi 0812xxx sebelum tiba, bongkar di samping gudang utama.')
                                    ->placeholder('Tulis instruksi khusus supir/pengiriman yang ingin dicetak di Surat Jalan...')
                                    ->rows(2)
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('admin_notes')
                                    ->label('Catatan Internal Admin (Rahasia / Tidak Dicetak di Surat Jalan)')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Forms\Components\Section::make('Penjadwalan & Pemenuhan Pesanan')
                            ->schema([
                                Forms\Components\Select::make('fulfillment_type')
                                    ->label('Tipe Pemenuhan')
                                    ->options([
                                        'ready_stock' => '📦 Ready Stock (Pabrik)',
                                        'po_single' => '🔨 Pre-Order (PO Tunggal)',
                                        'po_batch' => '🚚 PO Batch (Pengiriman Bertahap)',
                                    ]),
                                Forms\Components\DatePicker::make('production_start_date')
                                    ->label('Tgl Mulai Produksi'),
                                Forms\Components\DatePicker::make('ready_shipping_date')
                                    ->label('Est. Siap Kirim'),
                                Forms\Components\DatePicker::make('estimated_delivery_date')
                                    ->label('Est. Tiba di Lokasi'),
                                TextInput::make('batch_count')
                                    ->label('Jumlah Batch')
                                    ->numeric(),
                            ])->columns(3),

                        Forms\Components\Section::make('Daftar Batch Pengiriman (PO Batch)')
                            ->visible(fn ($record) => $record && $record->fulfillment_type === 'po_batch')
                            ->schema([
                                Forms\Components\Repeater::make('batches')
                                    ->relationship()
                                    ->schema([
                                        TextInput::make('batch_name')->label('Batch')->required(),
                                        TextInput::make('quantity')->label('Kuantitas (pcs)')->numeric()->required(),
                                        Forms\Components\Select::make('status')
                                            ->label('Status')
                                            ->options([
                                                'pending_production' => 'Menunggu Produksi',
                                                'producing' => 'Sedang Diproduksi',
                                                'ready_to_ship' => 'Siap Kirim',
                                                'shipped' => 'Sedang Dikirim',
                                                'delivered' => 'Diterima di Proyek',
                                            ]),
                                        Forms\Components\DatePicker::make('estimated_dispatch_date')->label('Est. Berangkat'),
                                        Forms\Components\DatePicker::make('estimated_delivery_date')->label('Est. Tiba'),
                                        TextInput::make('courier_name')->label('Supir / Armada'),
                                        TextInput::make('tracking_number')->label('No. Plat Truk'),
                                    ])
                                    ->columns(4)
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Section::make('Produk yang Dipesan')
                            ->schema([
                                Forms\Components\Repeater::make('items')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\Select::make('product_id')
                                            ->label('Produk')
                                            ->relationship('product', 'name')
                                            ->disabled()
                                            ->columnSpan(4),
                                        Forms\Components\Placeholder::make('product_variant_name')
                                            ->label('Varian')
                                            ->content(fn ($record) => $record?->variant?->name ?? '-')
                                            ->columnSpan(2),
                                        TextInput::make('quantity')
                                            ->label('Jumlah')
                                            ->numeric()
                                            ->disabled()
                                            ->columnSpan(1),
                                        TextInput::make('product_price')
                                            ->label('Harga')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->disabled()
                                            ->columnSpan(2),
                                        TextInput::make('total_price')
                                            ->label('Total')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->disabled()
                                            ->columnSpan(3),
                                    ])
                                    ->columns(12)
                                    ->addable(false)
                                    ->deletable(false)
                                    ->reorderable(false),
                            ]),
                    ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Ringkasan Biaya')
                            ->schema([
                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled(),
                                TextInput::make('shipping_cost')
                                    ->label('Ongkos Kirim')
                                    ->numeric()
                                    ->prefix('Rp'),
                                TextInput::make('discount_amount')
                                    ->label('Diskon')
                                    ->numeric()
                                    ->prefix('Rp'),
                                TextInput::make('grand_total')
                                    ->label('Total Bayar')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled(),
                            ]),

                        Forms\Components\Section::make('Pengiriman')
                            ->schema([
                                TextInput::make('courier')
                                    ->label('Kurir/Ekspedisi')
                                    ->placeholder('SiCepat, JNE, Truck'),
                                TextInput::make('tracking_number')
                                    ->label('No. Resi'),
                                Forms\Components\FileUpload::make('delivery_photo_path')
                                    ->label('Bukti Pengiriman (Oleh Kurir)')
                                    ->image()
                                    ->disabled()
                                    ->columnSpanFull()
                                    ->visible(fn ($record) => $record && $record->delivery_photo_path),
                            ]),

                        Forms\Components\Section::make('Waktu')
                            ->schema([
                                Forms\Components\DateTimePicker::make('paid_at')
                                    ->label('Waktu Bayar'),
                                Forms\Components\DateTimePicker::make('shipped_at')
                                    ->label('Waktu Kirim'),
                                Forms\Components\DateTimePicker::make('completed_at')
                                    ->label('Waktu Selesai'),
                            ]),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::getTableColumns())
            ->defaultSort('created_at', 'desc')
            ->recordAction(Tables\Actions\ViewAction::class)
            ->recordUrl(fn (Order $record): string => Pages\ViewOrder::getUrl([$record->id]))
            ->filters(static::getTableFilters())
            ->actions(static::getTableActions())
            ->bulkActions(static::getTableBulkActions())
            ->searchable()
            ->searchDebounce(500);
    }

    protected static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('order_number')
                ->label('No. Pesanan')
                ->description(fn (Order $record): string => $record->shipping_name)
                ->searchable(['order_number', 'shipping_name', 'shipping_email', 'shipping_phone'])
                ->sortable()
                ->copyable(),
            Tables\Columns\TextColumn::make('items_count')
                ->label('Produk')
                ->counts('items')
                ->suffix(' item')
                ->searchable(false),
            Tables\Columns\TextColumn::make('grand_total')
                ->label('Total')
                ->money('IDR')
                ->weight('bold')
                ->color('terra')
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->description(fn (Order $record): ?string => $record->fulfillment_type === 'po_batch'
                        ? ($record->batches()->count() > 0
                            ? $record->batches()->whereIn('status', ['shipped', 'delivered'])->count()
                              .'/'.$record->batches()->count().' batch terkirim'
                            : null)
                        : null
                )
                ->color(fn (string $state): string => match ($state) {
                    'pending_payment' => 'warning',
                    'paid' => 'info',
                    'processing' => 'primary',
                    'shipped' => 'info',
                    'delivered' => 'success',
                    'completed' => 'success',
                    'cancelled' => 'danger',
                    default => 'gray',
                })
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'pending_payment' => 'Menunggu Bayar',
                    'paid' => 'Dibayar',
                    'processing' => 'Diproses',
                    'shipped' => 'Dikirim',
                    'delivered' => 'Diterima',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    default => $state,
                }),
            Tables\Columns\TextColumn::make('fulfillment_type')
                ->label('Pemenuhan')
                ->badge()
                ->color(fn ($state): string => match ($state) {
                    'ready_stock' => 'success',
                    'po_single' => 'info',
                    'po_batch' => 'warning',
                    default => 'gray',
                })
                ->formatStateUsing(function ($state, Order $record): string {
                    if (is_null($state)) {
                        return '—';
                    }
                    if ($state === 'po_batch') {
                        $total = $record->batches()->count() ?: ($record->batch_count ?: 1);
                        $shipped = $record->batches()->whereIn('status', ['shipped', 'delivered'])->count();

                        return "🚚 PO Batch ({$shipped}/{$total} Terkirim)";
                    }

                    return match ($state) {
                        'ready_stock' => '📦 Ready Stock',
                        'po_single' => '🔨 PO Tunggal',
                        default => $state,
                    };
                }),
            Tables\Columns\TextColumn::make('payment_status')
                ->label('Bayar')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'paid' => 'success',
                    'unpaid' => 'danger',
                    'expired' => 'gray',
                    'failed' => 'danger',
                    'refunded' => 'info',
                    default => 'gray',
                }),
            Tables\Columns\TextColumn::make('latestPayment.payment_type')
                ->label('Metode Bayar')
                ->formatStateUsing(fn ($state, Order $record) => $record->latestPayment?->payment_type_label ?? '-')
                ->toggleable(),
            Tables\Columns\TextColumn::make('courier')
                ->label('Kurir')
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('tracking_number')
                ->label('Resi')
                ->copyable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal')
                ->dateTime('d M Y H:i')
                ->sortable(),
        ];
    }

    protected static function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('urutan')
                ->label('Urutkan')
                ->options([
                    'newest' => '🔽 Terbaru (Pesanan)',
                    'processed' => '⚡ Prosesan Terbaru',
                    'oldest' => '🔼 Terlama',
                ])
                ->default('newest')
                ->query(function (Builder $query, array $data): Builder {
                    if (($data['value'] ?? null) === 'oldest') {
                        return $query->orderBy('created_at', 'asc');
                    }
                    if (($data['value'] ?? null) === 'processed') {
                        return $query->orderBy('updated_at', 'desc');
                    }

                    return $query->orderBy('created_at', 'desc');
                }),
            Tables\Filters\SelectFilter::make('status')
                ->label('Status')
                ->options([
                    'pending_payment' => 'Menunggu Pembayaran',
                    'paid' => 'Dibayar',
                    'processing' => 'Diproses',
                    'shipped' => 'Dikirim',
                    'delivered' => 'Diterima',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                ]),
            Tables\Filters\SelectFilter::make('fulfillment_type')
                ->label('Tipe Pemenuhan')
                ->options([
                    'ready_stock' => '📦 Ready Stock',
                    'po_single' => '🔨 PO Tunggal',
                    'po_batch' => '🚚 PO Batch',
                ])
                ->placeholder('Semua Tipe Pemenuhan'),
            Tables\Filters\SelectFilter::make('payment_status')
                ->label('Pembayaran')
                ->options([
                    'unpaid' => 'Belum Bayar',
                    'paid' => 'Lunas',
                    'expired' => 'Kedaluwarsa',
                    'failed' => 'Gagal',
                    'refunded' => 'Refund',
                ]),
            Tables\Filters\Filter::make('product_name')
                ->label('Nama Produk')
                ->form([
                    TextInput::make('product_name')
                        ->label('Nama Produk')
                        ->placeholder('Cari nama produk...'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query->when(
                        $data['product_name'] ?? null,
                        fn (Builder $q, string $name) => $q->whereHas(
                            'items.product',
                            fn (Builder $pq) => $pq->where('name', 'like', "%{$name}%")
                        )
                    );
                }),
        ];
    }

    protected static function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->label('Lihat Detail')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->button(),

            // Kelompok Aksi Proses Pesanan
            Tables\Actions\ActionGroup::make([
                Tables\Actions\Action::make('confirm_payment')
                    ->label('Konfirmasi Pembayaran')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        $record->update(['status' => 'paid', 'payment_status' => 'paid', 'paid_at' => now()]);

                        if ($record->user_id && $record->user) {
                            $record->user->notify(new OrderStatusUpdated($record, 'Dibayar'));
                        }

                        Notification::make()
                            ->title('Pembayaran Berhasil Dikonfirmasi')
                            ->body('Log pembayaran dan invoice telah dibuat otomatis oleh sistem. Silakan cetak invoice jika diperlukan.')
                            ->success()
                            ->persistent()
                            ->actions([
                                Action::make('print_order')
                                    ->label('Cetak Detail Pesanan')
                                    ->icon('heroicon-o-printer')
                                    ->color('info')
                                    ->button()
                                    ->url(route('print.order', $record), shouldOpenInNewTab: true),
                                Action::make('print_invoice')
                                    ->label('Cetak Invoice')
                                    ->icon('heroicon-o-document-text')
                                    ->color('success')
                                    ->button()
                                    ->url(fn () => route('print.invoice', $record->refresh()->invoice), shouldOpenInNewTab: true),
                            ])
                            ->send();
                    })
                    ->visible(fn (Order $record) => $record->payment_status !== 'paid'),

                Tables\Actions\Action::make('process_order')
                    ->label('Proses')
                    ->icon('heroicon-o-cog')
                    ->color('warning')
                    ->visible(fn (Order $record) => $record->status === 'paid')
                    ->modalHeading('Proses & Jadwalkan Pemenuhan Pesanan')
                    ->modalDescription(fn (Order $record) => 'Total Pesanan: '.number_format($record->total_ordered_quantity, 0, ',', '.').' pcs. Silakan tentukan mode pemenuhan.')
                    ->modalSubmitActionLabel('Mulai Proses Pesanan')
                    ->form(function (Order $record) {
                        $totalQty = $record->total_ordered_quantity;

                        return [
                            Forms\Components\Radio::make('fulfillment_type')
                                ->label('Pilih Tipe Pemenuhan Pesanan')
                                ->options([
                                    'ready_stock' => '📦 Ready Stock (Stok Tersedia di Pabrik)',
                                    'po_single' => '🔨 Pre-Order / PO Tunggal (Produksi Sekaligus 1x Kirim)',
                                    'po_batch' => '🚚 Pre-Order / PO Batch (Kirim Bertahap Skala Besar)',
                                ])
                                ->default($record->requested_batch_delivery ? 'po_batch' : ($totalQty >= 2000 ? 'po_batch' : 'ready_stock'))
                                ->live()
                                ->required(),

                            // Ready Stock
                            Forms\Components\Section::make('Penjadwalan Ready Stock')
                                ->visible(fn (Forms\Get $get) => $get('fulfillment_type') === 'ready_stock')
                                ->schema([
                                    Forms\Components\DatePicker::make('ready_shipping_date')
                                        ->label('Estimasi Tanggal Siap Kirim')
                                        ->default(now()->addDays(1)->format('Y-m-d'))
                                        ->required(fn (Forms\Get $get) => $get('fulfillment_type') === 'ready_stock'),
                                    Forms\Components\DatePicker::make('estimated_delivery_date')
                                        ->label('Estimasi Tanggal Tiba di Lokasi')
                                        ->default(now()->addDays(3)->format('Y-m-d'))
                                        ->required(fn (Forms\Get $get) => $get('fulfillment_type') === 'ready_stock'),
                                    Forms\Components\Textarea::make('fulfillment_notes_ready')
                                        ->label('Catatan Penyiapan Gudang (Opsional)')
                                        ->placeholder('Contoh: Barang sudah dipacking di palet A-12.')
                                        ->columnSpanFull(),
                                ])->columns(2),

                            // PO Single
                            Forms\Components\Section::make('Penjadwalan Pre-Order (PO Tunggal)')
                                ->visible(fn (Forms\Get $get) => $get('fulfillment_type') === 'po_single')
                                ->schema([
                                    Forms\Components\DatePicker::make('production_start_date')
                                        ->label('Tanggal Mulai Produksi/Cetak')
                                        ->default(now()->format('Y-m-d'))
                                        ->required(fn (Forms\Get $get) => $get('fulfillment_type') === 'po_single'),
                                    Forms\Components\DatePicker::make('ready_shipping_date_po')
                                        ->label('Estimasi Selesai Produksi & Siap Kirim')
                                        ->default(now()->addDays(7)->format('Y-m-d'))
                                        ->required(fn (Forms\Get $get) => $get('fulfillment_type') === 'po_single'),
                                    Forms\Components\DatePicker::make('estimated_delivery_date_po')
                                        ->label('Estimasi Tanggal Tiba di Lokasi')
                                        ->default(now()->addDays(9)->format('Y-m-d'))
                                        ->required(fn (Forms\Get $get) => $get('fulfillment_type') === 'po_single'),
                                    Forms\Components\Textarea::make('fulfillment_notes_po')
                                        ->label('Catatan Produksi (Opsional)')
                                        ->placeholder('Contoh: Masuk antrean cetak mesin 2.')
                                        ->columnSpanFull(),
                                ])->columns(3),

                            // PO Batch
                            Forms\Components\Section::make('Penjadwalan Pre-Order Batch (Pengiriman Bertahap)')
                                ->visible(fn (Forms\Get $get) => $get('fulfillment_type') === 'po_batch')
                                ->schema([
                                    Forms\Components\Placeholder::make('batch_info')
                                        ->label('Informasi Pesanan Pelanggan')
                                        ->content(function () use ($record, $totalQty) {
                                            $text = 'Total Kuantitas Pesanan: '.number_format($totalQty, 0, ',', '.').' pcs.';
                                            if ($record->requested_batch_notes) {
                                                $text .= " (Catatan Permintaan Pembeli: {$record->requested_batch_notes})";
                                            }

                                            return $text;
                                        }),
                                    Forms\Components\Repeater::make('batches')
                                        ->label('Rincian Kuantitas & Estimasi Jadwal Tiap Batch')
                                        ->schema([
                                            TextInput::make('batch_name')
                                                ->label('Nama Batch')
                                                ->required(),
                                            TextInput::make('quantity')
                                                ->label('Muatan Truk (pcs)')
                                                ->numeric()
                                                ->required(),
                                            Forms\Components\DatePicker::make('production_start_date')
                                                ->label('Mulai Produksi'),
                                            Forms\Components\DatePicker::make('estimated_dispatch_date')
                                                ->label('Est. Berangkat')
                                                ->required(),
                                            Forms\Components\DatePicker::make('estimated_delivery_date')
                                                ->label('Est. Tiba')
                                                ->required(),
                                        ])
                                        ->columns(5)
                                        ->default(function () use ($totalQty) {
                                            $batchNum = $totalQty >= 8000 ? 8 : ($totalQty >= 4000 ? 4 : 2);
                                            $perBatch = (int) floor($totalQty / $batchNum);
                                            $remainder = $totalQty - ($perBatch * $batchNum);
                                            $items = [];
                                            for ($i = 1; $i <= $batchNum; $i++) {
                                                $qty = $perBatch + ($i === $batchNum ? $remainder : 0);
                                                // Setiap batch: produksi mulai 5 hari sebelum kirim
                                                $dispatch = now()->addDays(5 + ($i - 1) * 7);
                                                $prodStart = (clone $dispatch)->subDays(5);
                                                $delivery = (clone $dispatch)->addDays(1);
                                                $items[] = [
                                                    'batch_name' => "Batch #{$i}",
                                                    'quantity' => $qty,
                                                    'production_start_date' => $prodStart->format('Y-m-d'),
                                                    'estimated_dispatch_date' => $dispatch->format('Y-m-d'),
                                                    'estimated_delivery_date' => $delivery->format('Y-m-d'),
                                                ];
                                            }

                                            return $items;
                                        })
                                        ->columnSpanFull(),
                                    Forms\Components\Textarea::make('fulfillment_notes_batch')
                                        ->label('Catatan Operasional Pabrik')
                                        ->placeholder('Contoh: Jadwal pengiriman mengikuti kesiapan jalan proyek dan cuaca.')
                                        ->columnSpanFull(),
                                ]),
                        ];
                    })
                    ->action(function (Order $record, array $data) {
                        $type = $data['fulfillment_type'] ?? 'ready_stock';

                        $updateData = [
                            'status' => 'processing',
                            'fulfillment_type' => $type,
                        ];

                        if ($type === 'ready_stock') {
                            $updateData['ready_shipping_date'] = $data['ready_shipping_date'] ?? null;
                            $updateData['estimated_delivery_date'] = $data['estimated_delivery_date'] ?? null;
                            $updateData['fulfillment_notes'] = $data['fulfillment_notes_ready'] ?? null;
                            $updateData['batch_count'] = 1;
                            $updateData['production_status'] = 'ready_to_ship';
                        } elseif ($type === 'po_single') {
                            $updateData['production_start_date'] = $data['production_start_date'] ?? null;
                            $updateData['ready_shipping_date'] = $data['ready_shipping_date_po'] ?? null;
                            $updateData['estimated_delivery_date'] = $data['estimated_delivery_date_po'] ?? null;
                            $updateData['fulfillment_notes'] = $data['fulfillment_notes_po'] ?? null;
                            $updateData['batch_count'] = 1;
                            $updateData['production_status'] = 'pending';
                        } elseif ($type === 'po_batch') {
                            $batches = $data['batches'] ?? [];
                            $updateData['batch_count'] = count($batches) ?: 1;
                            $updateData['fulfillment_notes'] = $data['fulfillment_notes_batch'] ?? null;
                            $updateData['production_status'] = 'pending';

                            $record->batches()->delete();

                            $bNum = 1;
                            foreach ($batches as $b) {
                                $record->batches()->create([
                                    'batch_number' => $bNum,
                                    'batch_name' => $b['batch_name'] ?? "Batch #{$bNum}",
                                    'quantity' => (int) ($b['quantity'] ?? 0),
                                    'production_start_date' => $b['production_start_date'] ?? null,
                                    'estimated_dispatch_date' => $b['estimated_dispatch_date'] ?? null,
                                    'estimated_delivery_date' => $b['estimated_delivery_date'] ?? null,
                                    'status' => 'pending_production',
                                ]);
                                $bNum++;
                            }
                        }

                        $record->update($updateData);

                        // Reload fresh record dengan batches (penting untuk email PO Batch)
                        $record->refresh();
                        $record->load('batches');

                        if ($record->user_id && $record->user) {
                            $record->user->notify(new OrderStatusUpdated($record, 'Diproses'));
                        }

                        try {
                            $email = $record->shipping_email ?? $record->user?->email;
                            if ($email) {
                                $freshRecord = $record; // batches sudah ter-load
                                if (function_exists('defer')) {
                                    defer(fn () => Mail::to($email)->send(new OrderStatusMail($freshRecord, 'processing')));
                                } else {
                                    Mail::to($email)->send(new OrderStatusMail($freshRecord, 'processing'));
                                }
                            }
                        } catch (\Exception $e) {
                            Log::error('Failed to send status email: '.$e->getMessage());
                        }

                        // Bangun notifikasi sesuai tipe pemenuhan
                        $notifActions = [];
                        if ($type === 'po_batch') {
                            // Tombol khusus PO Batch: WA pembeli (info jadwal batch)
                            $waPhone = preg_replace('/[^0-9]/', '', $record->shipping_phone ?? '');
                            if ($waPhone) {
                                if (str_starts_with($waPhone, '0')) {
                                    $waPhone = '62'.substr($waPhone, 1);
                                }
                                $batchCount = $record->batch_count;
                                $waText = urlencode("Halo {$record->shipping_name}, pesanan Anda ({$record->order_number}) sejumlah *".number_format($record->total_ordered_quantity, 0, ',', '.')." pcs* telah kami jadwalkan untuk pengiriman bertahap ({$batchCount} batch). Cek email Anda untuk detail jadwal estimasi tiap batch. Lacak: ".route('order.tracking')."?order_number={$record->order_number}");
                                $notifActions[] = Action::make('wa_buyer_batch')
                                    ->label('WA Info Jadwal Batch ke Pembeli')
                                    ->url("https://wa.me/{$waPhone}?text={$waText}", shouldOpenInNewTab: true)
                                    ->button()
                                    ->color('success')
                                    ->icon('heroicon-o-chat-bubble-left-ellipsis');
                            }
                        } else {
                            // Tombol untuk ready_stock dan po_single: WA pembeli info estimasi
                            $waPhone = preg_replace('/[^0-9]/', '', $record->shipping_phone ?? '');
                            if ($waPhone) {
                                if (str_starts_with($waPhone, '0')) {
                                    $waPhone = '62'.substr($waPhone, 1);
                                }
                                $estShip = $record->ready_shipping_date?->format('d M Y') ?? '-';
                                $estArr = $record->estimated_delivery_date?->format('d M Y') ?? '-';
                                $mode = $type === 'ready_stock' ? 'Ready Stock (stok langsung tersedia)' : 'Pre-Order (masuk antrian produksi)';
                                $waText = urlencode("Halo {$record->shipping_name}, pesanan Anda ({$record->order_number}) sedang diproses dengan tipe *{$mode}*. Estimasi siap kirim: {$estShip}, estimasi tiba: {$estArr}. Lacak: ".route('order.tracking')."?order_number={$record->order_number}");
                                $notifActions[] = Action::make('wa_buyer')
                                    ->label('WA Info Estimasi ke Pembeli')
                                    ->url("https://wa.me/{$waPhone}?text={$waText}", shouldOpenInNewTab: true)
                                    ->button()
                                    ->color('success')
                                    ->icon('heroicon-o-chat-bubble-left-ellipsis');
                            }
                        }

                        Notification::make()
                            ->title('Pesanan Berhasil Diproses ('.$record->fulfillment_label.')')
                            ->body('Email konfirmasi telah dikirim ke pembeli.'.($type === 'po_batch' ? ' Jadwal '.$record->batch_count.' batch sudah tercantum di email.' : ''))
                            ->success()
                            ->persistent()
                            ->actions($notifActions)
                            ->send();
                    }),

                Tables\Actions\Action::make('dispatch_order')
                    ->label('Kirim Pesanan')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn (Order $record) => $record->status === 'processing' && $record->fulfillment_type !== 'po_batch')
                    ->form(fn (Order $record) => [
                        Forms\Components\Select::make('courier_id')
                            ->label('Kurir Internal')
                            ->relationship('courierUser', 'name', fn (Builder $query) => $query->where('role', 'courier'))
                            ->default($record->courier_id)
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $courier = User::find($state);
                                    if ($courier) {
                                        $set('courier', $courier->name);
                                        $set('courier_phone', $courier->phone);
                                        $set('tracking_number', $courier->license_plate);
                                    }
                                }
                            }),
                        TextInput::make('courier')
                            ->label('Atau Ekspedisi Luar')
                            ->default($record->courier),
                        TextInput::make('courier_phone')
                            ->label('Nomor WA Kurir / Supir (Opsional)')
                            ->default($record->courier_phone)
                            ->tel(),
                        TextInput::make('tracking_number')
                            ->label('No. Resi / Plat Nomor Truk (Opsional)')
                            ->default($record->tracking_number),
                    ])
                    ->modalHeading('Penyiapan Pengiriman Armada')
                    ->modalDescription('Lengkapi data supir/ekspedisi untuk dicetak di Surat Jalan. Pesanan akan diubah menjadi status "Dikirim".')
                    ->modalSubmitActionLabel('Kirim & Cetak Surat Jalan')
                    ->action(function (Order $record, array $data) {
                        $record->update([
                            'status' => 'shipped',
                            'shipped_at' => now(),
                            'courier_id' => $data['courier_id'] ?? null,
                            'courier' => $data['courier'] ?? null,
                            'courier_phone' => $data['courier_phone'] ?? null,
                            'tracking_number' => $data['tracking_number'] ?? null,
                        ]);

                        if ($record->user_id && $record->user) {
                            $record->user->notify(new OrderStatusUpdated($record, 'Dikirim'));
                        }

                        try {
                            $email = $record->shipping_email ?? $record->user?->email;
                            if ($email) {
                                if (function_exists('defer')) {
                                    defer(fn () => Mail::to($email)->send(new OrderStatusMail($record, 'shipped')));
                                } else {
                                    Mail::to($email)->send(new OrderStatusMail($record, 'shipped'));
                                }
                            }
                        } catch (\Exception $e) {
                            Log::error('Failed to send status email: '.$e->getMessage());
                        }

                        Notification::make()
                            ->title('Pesanan Sedang Dikirim')
                            ->body('Surat Jalan siap dicetak dan email notifikasi telah dikirim ke pembeli.')
                            ->success()
                            ->persistent()
                            ->actions([
                                Action::make('print_order')
                                    ->label('Cetak Surat Jalan')
                                    ->url(fn () => route('print.order', $record), shouldOpenInNewTab: true)
                                    ->button()
                                    ->icon('heroicon-o-printer'),
                                Action::make('send_wa')
                                    ->label('Kirim WA Pembeli')
                                    ->url(fn () => method_exists($record, 'getWaShippedLink') ? $record->getWaShippedLink() : '#', shouldOpenInNewTab: true)
                                    ->button()
                                    ->color('success')
                                    ->icon('heroicon-o-chat-bubble-left-ellipsis'),
                            ])
                            ->send();
                    }),

                Tables\Actions\Action::make('manage_batches')
                    ->label('Kelola & Kirim Batch')
                    ->icon('heroicon-o-queue-list')
                    ->color('info')
                    ->visible(fn (Order $record) => $record->status === 'processing' && $record->fulfillment_type === 'po_batch')
                    ->modalHeading('Kelola & Pengiriman Bertahap (PO Batch)')
                    ->modalDescription(fn (Order $record) => 'Total: '.number_format($record->total_ordered_quantity, 0, ',', '.').' pcs | Terkirim: '.number_format($record->total_shipped_quantity, 0, ',', '.').' pcs ('.$record->batch_progress_percentage.'%) | Sisa: '.number_format($record->remaining_quantity, 0, ',', '.').' pcs.')
                    ->form(function (Order $record) {
                        $availableBatches = $record->batches()->whereIn('status', ['pending_production', 'producing', 'ready_to_ship'])->get();
                        $options = [];
                        foreach ($availableBatches as $b) {
                            $options[$b->id] = "{$b->batch_name} — ".number_format($b->quantity, 0, ',', '.').' pcs (Jadwal: '.($b->estimated_dispatch_date ? $b->estimated_dispatch_date->format('d M Y') : '-').')';
                        }

                        return [
                            Forms\Components\Select::make('batch_id')
                                ->label('Pilih Batch yang Akan Diberangkatkan')
                                ->options($options)
                                ->required()
                                ->helperText('Pilih batch yang siap dikirimkan menggunakan armada truk.'),
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
                            TextInput::make('courier_name')
                                ->label('Nama Supir / Ekspedisi')
                                ->placeholder('Contoh: Pak Joko (Armada Pabrik)'),
                            TextInput::make('courier_phone')
                                ->label('Nomor HP/WA Supir')
                                ->placeholder('Contoh: 08123456789')
                                ->tel(),
                            TextInput::make('tracking_number')
                                ->label('No. Plat Nomor Truk')
                                ->placeholder('Contoh: B 9845 KLP')
                                ->required(),
                            Forms\Components\Textarea::make('notes')
                                ->label('Catatan Muatan Batch Ini')
                                ->placeholder('Contoh: Muatan 850 pcs roster terpal aman.')
                                ->columnSpanFull(),
                        ];
                    })
                    ->modalSubmitActionLabel('Berangkatkan Truk Batch Ini')
                    ->action(function (Order $record, array $data) {
                        $batch = OrderBatch::find($data['batch_id']);
                        if (! $batch) {
                            return;
                        }

                        $batch->update([
                            'status' => 'shipped',
                            'actual_dispatch_date' => now(),
                            'courier_id' => $data['courier_id'] ?? null,
                            'courier_name' => $data['courier_name'] ?? ($record->courier ?: 'Armada Pabrik'),
                            'courier_phone' => $data['courier_phone'] ?? null,
                            'tracking_number' => $data['tracking_number'] ?? null,
                            'notes' => $data['notes'] ?? null,
                        ]);

                        // Pesanan PO Batch TETAP di status 'processing' sampai semua batch selesai.
                        // Status 'shipped' hanya diset jika SEMUA batch sudah dikirim.
                        $record->refresh();
                        $allShipped = $record->isAllBatchesShipped();
                        if ($allShipped) {
                            $record->update([
                                'status' => 'shipped',
                                'shipped_at' => now(),
                            ]);
                        }
                        // Jika belum semua, pastikan status tetap 'processing'
                        if (! $allShipped && $record->status !== 'processing') {
                            $record->update(['status' => 'processing']);
                        }

                        // Send email per-batch
                        try {
                            $email = $record->shipping_email ?? $record->user?->email;
                            if ($email) {
                                if (function_exists('defer')) {
                                    defer(fn () => Mail::to($email)->send(new OrderStatusMail($record, 'batch_shipped', $batch)));
                                } else {
                                    Mail::to($email)->send(new OrderStatusMail($record, 'batch_shipped', $batch));
                                }
                            }
                        } catch (\Exception $e) {
                            Log::error('Failed to send batch status email: '.$e->getMessage());
                        }

                        Notification::make()
                            ->title("{$batch->batch_name} Berhasil Diberangkatkan!")
                            ->body('Muatan: '.number_format($batch->quantity, 0, ',', '.').' pcs. Sisa: '.number_format($batch->remaining_quantity_after_this_batch, 0, ',', '.').' pcs.')
                            ->success()
                            ->persistent()
                            ->actions([
                                Action::make('print_batch_label')
                                    ->label('Cetak Surat Jalan '.$batch->batch_name)
                                    ->url(route('print.order', ['order' => $record->id, 'batch_id' => $batch->id]), shouldOpenInNewTab: true)
                                    ->button()
                                    ->icon('heroicon-o-printer'),
                                Action::make('send_batch_wa')
                                    ->label('Kirim WA Info Supir')
                                    ->url($record->getWaBatchShippedLink($batch), shouldOpenInNewTab: true)
                                    ->button()
                                    ->color('success')
                                    ->icon('heroicon-o-chat-bubble-left-ellipsis'),
                            ])
                            ->send();
                    }),

                Tables\Actions\Action::make('mark_delivered')
                    ->label('Tandai Diterima')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Penerimaan')
                    ->modalDescription('Apakah Anda yakin barang sudah diterima oleh pembeli? Status pesanan akan diubah menjadi Selesai.')
                    ->modalSubmitActionLabel('Ya, Sudah Diterima')
                    ->action(function (Order $record) {
                        $record->update([
                            'status' => 'completed',
                            'completed_at' => now(),
                        ]);

                        if ($record->user_id && $record->user) {
                            $record->user->notify(new OrderStatusUpdated($record, 'Diterima'));
                        }

                        try {
                            $email = $record->shipping_email ?? $record->user?->email;
                            if ($email) {
                                if (function_exists('defer')) {
                                    defer(fn () => Mail::to($email)->send(new OrderStatusMail($record, 'completed')));
                                } else {
                                    Mail::to($email)->send(new OrderStatusMail($record, 'completed'));
                                }
                            }
                        } catch (\Exception $e) {
                            Log::error('Failed to send status email: '.$e->getMessage());
                        }

                        Notification::make()
                            ->title('Pesanan Selesai')
                            ->body('Pesanan telah ditandai sebagai diterima dan selesai.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Order $record) => in_array($record->status, ['shipped', 'delivered'])),
            ])->label('Proses')->icon('heroicon-m-chevron-right')->color('primary')->button(),

            // Kelompok Aksi Cetak
            Tables\Actions\ActionGroup::make([
                Tables\Actions\Action::make('print_order')
                    ->label('Cetak Surat Jalan')
                    ->icon('heroicon-o-printer')
                    ->modalHeading('Preview Surat Jalan')
                    ->modalWidth('5xl')
                    ->modalContent(fn (Order $record) => view('print.order-preview', ['order' => $record]))
                    ->modalSubmitAction(false)
                    ->extraModalFooterActions([
                        Tables\Actions\Action::make('open_order_in_new_tab')
                            ->label('Konfirmasi Cetak & Kirim')
                            ->icon('heroicon-o-truck')
                            ->color('primary')
                            ->url(fn (Order $record) => route('print.order', ['order' => $record, 'ship' => 1]))
                            ->openUrlInNewTab()
                            ->button()
                            ->visible(fn (Order $record) => $record->status === 'processing' && $record->fulfillment_type !== 'po_batch'),
                    ]),

                Tables\Actions\Action::make('print_invoice')
                    ->label('Cetak Invoice')
                    ->icon('heroicon-o-document-text')
                    ->visible(fn (Order $record) => $record->invoice !== null)
                    ->modalHeading('Preview Invoice')
                    ->modalWidth('5xl')
                    ->modalContent(fn (Order $record) => view('print.invoice-preview', ['invoice' => $record->invoice]))
                    ->modalSubmitAction(false)
                    ->extraModalFooterActions([
                        Tables\Actions\Action::make('open_invoice_in_new_tab')
                            ->label('Buka Full Page / Cetak')
                            ->icon('heroicon-o-arrow-top-right-on-square')
                            ->url(fn (Order $record) => $record->invoice ? route('print.invoice', $record->invoice) : '#')
                            ->openUrlInNewTab()
                            ->button()
                            ->visible(fn (Order $record) => $record->status === 'processing'),
                    ]),

                Tables\Actions\Action::make('print_label')
                    ->label('Cetak Resi / Label')
                    ->icon('heroicon-o-printer')
                    ->url(fn (Order $record) => route('print.shipping-label', $record->shippingLabel))
                    ->openUrlInNewTab()
                    ->visible(fn (Order $record) => $record->shippingLabel !== null && $record->status === 'processing'),
            ])->label('Cetak')->icon('heroicon-o-printer')->color('gray')->button()->visible(fn (Order $record) => ! in_array($record->status, ['pending_payment', 'paid'])),

            // Kelompok Aksi Email
            Tables\Actions\ActionGroup::make([
                Tables\Actions\Action::make('send_invoice_email')
                    ->label('Kirim Email Invoice')
                    ->icon('heroicon-o-envelope')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        try {
                            Mail::to($record->shipping_email ?? $record->user->email)
                                ->send(new InvoiceMail($record));

                            Notification::make()
                                ->title('Email Invoice Berhasil Dikirim')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal Mengirim Email')
                                ->body('Pastikan pengaturan SMTP sudah benar di menu Pengaturan Website. Error: '.$e->getMessage())
                                ->danger()
                                ->persistent()
                                ->send();
                        }
                    })
                    ->visible(fn (Order $record) => $record->invoice !== null),
            ])->label('Email')->icon('heroicon-o-envelope')->color('info')->button(),
        ];
    }

    protected static function getTableBulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                // Dihapus agar admin tidak bisa hapus pesanan
            ]),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('📦 Manajemen Pengiriman Bertahap')
                    ->description('Kelola status dan pengiriman setiap batch untuk pesanan proyek ini.')
                    ->visible(fn ($record) => $record && $record->fulfillment_type === 'po_batch')
                    ->schema([
                        Infolists\Components\ViewEntry::make('batches_panel')
                            ->view('filament.order-batches-panel')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(false),

                Infolists\Components\Section::make('🚚 Progres Pengiriman & Status')
                    ->visible(fn ($record) => $record && (in_array($record->fulfillment_type, ['ready_stock', 'po_single']) || is_null($record->fulfillment_type)))
                    ->schema([
                        Infolists\Components\ViewEntry::make('single_progress_panel')
                            ->view('filament.order-single-progress-panel')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(false),

                Infolists\Components\Grid::make(3)
                    ->schema([
                        Infolists\Components\Group::make([
                            Infolists\Components\Section::make('Status & Waktu')
                                ->schema([
                                    Infolists\Components\TextEntry::make('order_number')
                                        ->label('No. Pesanan')
                                        ->weight('bold')
                                        ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                        ->copyable(),
                                    Infolists\Components\TextEntry::make('status')
                                        ->label('Status Pesanan')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'pending_payment' => 'warning',
                                            'paid' => 'info',
                                            'processing' => 'primary',
                                            'shipped' => 'info',
                                            'delivered' => 'success',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            default => 'gray',
                                        })
                                        ->formatStateUsing(fn (string $state): string => match ($state) {
                                            'pending_payment' => 'Menunggu Bayar',
                                            'paid' => 'Dibayar',
                                            'processing' => 'Diproses',
                                            'shipped' => 'Dikirim',
                                            'delivered' => 'Diterima',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan',
                                            default => $state,
                                        }),
                                    Infolists\Components\TextEntry::make('created_at')
                                        ->label('Waktu Pesan')
                                        ->dateTime('d M Y H:i'),
                                    Infolists\Components\TextEntry::make('paid_at')
                                        ->label('Waktu Bayar')
                                        ->dateTime('d M Y H:i')
                                        ->placeholder('-'),
                                    Infolists\Components\TextEntry::make('latestPayment.payment_type')
                                        ->label('Metode Pembayaran')
                                        ->formatStateUsing(fn ($state, Order $record) => $record->latestPayment
                                                ? ($record->latestPayment->payment_type_label.($record->latestPayment->va_number ? ' (VA: '.$record->latestPayment->va_number.')' : ''))
                                                : '-'
                                        )
                                        ->placeholder('-'),
                                ])->columns(2),

                            Infolists\Components\Section::make('Data Pembeli')
                                ->schema([
                                    Infolists\Components\TextEntry::make('user.name')
                                        ->label('Nama Akun')
                                        ->icon('heroicon-m-user'),
                                    Infolists\Components\TextEntry::make('shipping_name')
                                        ->label('Nama Penerima'),
                                    Infolists\Components\TextEntry::make('shipping_email')
                                        ->label('Email')
                                        ->icon('heroicon-m-envelope'),
                                    Infolists\Components\TextEntry::make('shipping_phone')
                                        ->label('No. HP')
                                        ->icon('heroicon-m-phone'),
                                ])->columns(2),

                            Infolists\Components\Section::make('Alamat Pengiriman & Titik GPS')
                                ->schema([
                                    Infolists\Components\TextEntry::make('shipping_address')
                                        ->label('Alamat Lengkap')
                                        ->columnSpanFull(),
                                    Infolists\Components\TextEntry::make('shipping_city')
                                        ->label('Kota/Kabupaten'),
                                    Infolists\Components\TextEntry::make('shipping_province')
                                        ->label('Provinsi'),
                                    Infolists\Components\TextEntry::make('shipping_postal_code')
                                        ->label('Kode Pos'),
                                    Infolists\Components\TextEntry::make('shipping_latitude')
                                        ->label('Latitude GPS')
                                        ->placeholder('-'),
                                    Infolists\Components\TextEntry::make('shipping_longitude')
                                        ->label('Longitude GPS')
                                        ->placeholder('-'),
                                    Infolists\Components\TextEntry::make('google_maps_link')
                                        ->label('Navigasi Titik Lokasi')
                                        ->icon('heroicon-m-map-pin')
                                        ->color('primary')
                                        ->weight('bold')
                                        ->state(fn ($record) => $record->shipping_latitude && $record->shipping_longitude
                                            ? "🗺️ Buka di Google Maps ({$record->shipping_latitude}, {$record->shipping_longitude})"
                                            : '-'
                                        )
                                        ->url(fn ($record) => $record->shipping_latitude && $record->shipping_longitude
                                            ? "https://www.google.com/maps/dir/?api=1&destination={$record->shipping_latitude},{$record->shipping_longitude}"
                                            : null, true),
                                ])->columns(3),
                        ])->columnSpan(2),

                        Infolists\Components\Group::make([
                            Infolists\Components\Section::make('Ringkasan Biaya')
                                ->schema([
                                    Infolists\Components\TextEntry::make('subtotal')
                                        ->label('Subtotal')
                                        ->money('IDR'),
                                    Infolists\Components\TextEntry::make('shipping_cost')
                                        ->label('Ongkos Kirim')
                                        ->money('IDR'),
                                    Infolists\Components\TextEntry::make('discount_amount')
                                        ->label('Diskon')
                                        ->money('IDR')
                                        ->color('danger'),
                                    Infolists\Components\TextEntry::make('grand_total')
                                        ->label('Total Bayar')
                                        ->money('IDR')
                                        ->weight('bold')
                                        ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                        ->color('primary'),
                                ]),

                            Infolists\Components\Section::make('Pengiriman')
                                ->schema([
                                    Infolists\Components\TextEntry::make('courier')
                                        ->label('Kurir')
                                        ->placeholder('Belum diatur'),
                                    Infolists\Components\TextEntry::make('tracking_number')
                                        ->label('No. Resi')
                                        ->placeholder('-')
                                        ->copyable(),
                                    Infolists\Components\TextEntry::make('shipped_at')
                                        ->label('Waktu Kirim')
                                        ->dateTime()
                                        ->placeholder('-'),
                                    Infolists\Components\ImageEntry::make('delivery_photo_path')
                                        ->label('📸 Foto Bukti Pengiriman')
                                        ->disk('public')
                                        ->width(250)
                                        ->height(180)
                                        ->visible(fn ($record) => $record && $record->delivery_photo_path),
                                ]),

                            Infolists\Components\Section::make('Catatan')
                                ->schema([
                                    Infolists\Components\TextEntry::make('notes')
                                        ->label('Catatan Pembeli')
                                        ->placeholder('Tidak ada catatan'),
                                ]),
                        ])->columnSpan(1),
                    ]),

                Infolists\Components\Section::make('Daftar Produk')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('product.name')
                                    ->label('Nama Produk')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('product_variant_name')
                                    ->label('Varian'),
                                Infolists\Components\TextEntry::make('quantity')
                                    ->label('Jumlah')
                                    ->suffix(' item'),
                                Infolists\Components\TextEntry::make('product_price')
                                    ->label('Harga Satuan')
                                    ->money('IDR'),
                                Infolists\Components\TextEntry::make('subtotal')
                                    ->label('Total Harga')
                                    ->money('IDR')
                                    ->weight('bold'),
                            ])->columns(5),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }
}
