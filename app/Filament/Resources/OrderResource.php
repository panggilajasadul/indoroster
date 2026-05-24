<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Pesanan';
    protected static ?string $modelLabel = 'Pesanan';
    protected static ?string $pluralModelLabel = 'Pesanan';
    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'paid')->count() ?: null;
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
                                Forms\Components\TextInput::make('order_number')
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
                                Forms\Components\TextInput::make('shipping_name')
                                    ->label('Nama Penerima')
                                    ->required(),
                                Forms\Components\TextInput::make('shipping_email')
                                    ->label('Email Penerima')
                                    ->email()
                                    ->required(),
                                Forms\Components\TextInput::make('shipping_phone')
                                    ->label('No. HP Penerima')
                                    ->tel()
                                    ->required(),
                                Forms\Components\Textarea::make('shipping_address')
                                    ->label('Alamat Lengkap')
                                    ->required()
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Forms\Components\TextInput::make('shipping_city')
                                    ->label('Kota'),
                                Forms\Components\TextInput::make('shipping_province')
                                    ->label('Provinsi'),
                                Forms\Components\TextInput::make('shipping_postal_code')
                                    ->label('Kode Pos'),
                            ])->columns(3),

                        Forms\Components\Section::make('Catatan')
                            ->schema([
                                Forms\Components\Textarea::make('notes')
                                    ->label('Catatan Pembeli')
                                    ->disabled()
                                    ->rows(2),
                                Forms\Components\Textarea::make('admin_notes')
                                    ->label('Catatan Admin (Internal)')
                                    ->rows(2),
                            ])->columns(2),

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
                                        Forms\Components\TextInput::make('quantity')
                                            ->label('Jumlah')
                                            ->numeric()
                                            ->disabled()
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('product_price')
                                            ->label('Harga')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->disabled()
                                            ->columnSpan(2),
                                        Forms\Components\TextInput::make('total_price')
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
                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled(),
                                Forms\Components\TextInput::make('shipping_cost')
                                    ->label('Ongkos Kirim')
                                    ->numeric()
                                    ->prefix('Rp'),
                                Forms\Components\TextInput::make('discount_amount')
                                    ->label('Diskon')
                                    ->numeric()
                                    ->prefix('Rp'),
                                Forms\Components\TextInput::make('grand_total')
                                    ->label('Total Bayar')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled(),
                            ]),

                        Forms\Components\Section::make('Pengiriman')
                            ->schema([
                                Forms\Components\TextInput::make('courier')
                                    ->label('Kurir/Ekspedisi')
                                    ->placeholder('SiCepat, JNE, Truck'),
                                Forms\Components\TextInput::make('tracking_number')
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
            ->bulkActions(static::getTableBulkActions());
    }

    protected static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('order_number')
                ->label('No. Pesanan')
                ->description(fn (Order $record): string => $record->shipping_name)
                ->searchable()
                ->sortable()
                ->copyable(),
            Tables\Columns\TextColumn::make('items_count')
                ->label('Produk')
                ->counts('items')
                ->suffix(' item'),
            Tables\Columns\TextColumn::make('grand_total')
                ->label('Total')
                ->money('IDR')
                ->weight('bold')
                ->color('terra')
                ->sortable(),
            Tables\Columns\TextColumn::make('status')
                ->label('Status')
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
            Tables\Filters\SelectFilter::make('payment_status')
                ->label('Pembayaran')
                ->options([
                    'unpaid' => 'Belum Bayar',
                    'paid' => 'Lunas',
                    'expired' => 'Kedaluwarsa',
                    'failed' => 'Gagal',
                    'refunded' => 'Refund',
                ]),
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
                            $record->user->notify(new \App\Notifications\OrderStatusUpdated($record, 'Dibayar'));
                        }
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Pembayaran Berhasil Dikonfirmasi')
                            ->body('Log pembayaran dan invoice telah dibuat otomatis oleh sistem. Silakan cetak invoice jika diperlukan.')
                            ->success()
                            ->persistent()
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('print_order')
                                    ->label('Cetak Detail Pesanan')
                                    ->icon('heroicon-o-printer')
                                    ->color('info')
                                    ->button()
                                    ->url(route('print.order', $record), shouldOpenInNewTab: true),
                                \Filament\Notifications\Actions\Action::make('print_invoice')
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
                    ->form([
                        Forms\Components\Select::make('courier_id')
                            ->label('Pilih Kurir Internal (Opsional)')
                            ->relationship('courierUser', 'name', fn (Builder $query) => $query->where('role', 'courier'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $courier = \App\Models\User::find($state);
                                    if ($courier) {
                                        $set('courier', $courier->name);
                                        $set('courier_phone', $courier->phone);
                                        $set('tracking_number', $courier->license_plate);
                                    }
                                }
                            }),
                        Forms\Components\TextInput::make('courier')
                            ->label('Atau Ekspedisi Luar')
                            ->placeholder('Contoh: GoSend, Ekspedisi Luar'),
                        Forms\Components\TextInput::make('courier_phone')
                            ->label('Nomor WA Kurir (Opsional)')
                            ->placeholder('Contoh: 08123456789')
                            ->tel(),
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('No. Resi / Plat Truk (Opsional)')
                            ->placeholder('Contoh: B 1234 CD'),
                    ])
                    ->modalHeading('Siapkan Pesanan')
                    ->modalDescription('Pesanan akan dipindahkan ke tab "Diproses". Anda bisa mengisi data kurir sekarang atau nanti saat pesanan dikirim.')
                    ->modalSubmitActionLabel('Proses & Opsi Cetak')
                    ->action(function (Order $record, array $data) {
                        $record->update([
                            'status' => 'processing',
                            'courier_id' => $data['courier_id'] ?? null,
                            'courier' => $data['courier'] ?? null,
                            'courier_phone' => $data['courier_phone'] ?? null,
                            'tracking_number' => $data['tracking_number'] ?? null,
                        ]);
                        
                        if ($record->user_id && $record->user) {
                            $record->user->notify(new \App\Notifications\OrderStatusUpdated($record, 'Diproses'));
                        }

                        try {
                            $email = $record->shipping_email ?? $record->user?->email;
                            if ($email) {
                                if (function_exists('defer')) {
                                    defer(fn () => \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\OrderStatusMail($record, 'processing')));
                                } else {
                                    \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\OrderStatusMail($record, 'processing'));
                                }
                            }
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Failed to send status email: ' . $e->getMessage());
                        }
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Pesanan Berhasil Disiapkan')
                            ->body('Silakan cetak resi dan beri tahu pembeli via WA.')
                            ->success()
                            ->persistent()
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('print_label')
                                    ->label('Cetak Resi')
                                    ->url(fn () => ($label = $record->refresh()->shippingLabel) ? route('print.shipping-label', $label) : '#', shouldOpenInNewTab: true)
                                    ->button()
                                    ->icon('heroicon-o-printer'),
                                \Filament\Notifications\Actions\Action::make('send_wa')
                                    ->label('Kirim WA Pembeli')
                                    ->url(fn() => method_exists($record, 'getWaProcessingLink') ? $record->getWaProcessingLink() : '#', shouldOpenInNewTab: true)
                                    ->button()
                                    ->color('success')
                                    ->icon('heroicon-o-chat-bubble-left-ellipsis'),
                            ])
                            ->send();
                    }),

                Tables\Actions\Action::make('dispatch_order')
                    ->label('Siapkan Pengiriman')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn (Order $record) => $record->status === 'processing')
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
                                    $courier = \App\Models\User::find($state);
                                    if ($courier) {
                                        $set('courier', $courier->name);
                                        $set('courier_phone', $courier->phone);
                                        $set('tracking_number', $courier->license_plate);
                                    }
                                }
                            }),
                        Forms\Components\TextInput::make('courier')
                            ->label('Atau Ekspedisi Luar')
                            ->default($record->courier),
                        Forms\Components\TextInput::make('courier_phone')
                            ->label('Nomor WA Kurir (Opsional)')
                            ->default($record->courier_phone)
                            ->tel(),
                        Forms\Components\TextInput::make('tracking_number')
                            ->label('No. Resi / Plat Nomor Kendaraan (Opsional)')
                            ->default($record->tracking_number),
                    ])
                    ->modalHeading('Penyiapan untuk Dikirim')
                    ->modalDescription('Lengkapi data kurir untuk dicetak di Surat Jalan. Pesanan akan diubah menjadi status "Dikirim".')
                    ->modalSubmitActionLabel('Kirim & Opsi Cetak')
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
                            $record->user->notify(new \App\Notifications\OrderStatusUpdated($record, 'Dikirim'));
                        }

                        try {
                            $email = $record->shipping_email ?? $record->user?->email;
                            if ($email) {
                                if (function_exists('defer')) {
                                    defer(fn () => \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\OrderStatusMail($record, 'shipped')));
                                } else {
                                    \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\OrderStatusMail($record, 'shipped'));
                                }
                            }
                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\Log::error('Failed to send status email: ' . $e->getMessage());
                        }

                        \Filament\Notifications\Notification::make()
                            ->title('Pesanan Siap Dikirim')
                            ->body('Silakan cetak Surat Jalan dan kirim update ke pembeli via WA.')
                            ->success()
                            ->persistent()
                            ->actions([
                                \Filament\Notifications\Actions\Action::make('print_order')
                                    ->label('Cetak Surat Jalan')
                                    ->url(fn() => route('print.order', $record), shouldOpenInNewTab: true)
                                    ->button()
                                    ->icon('heroicon-o-printer'),
                                \Filament\Notifications\Actions\Action::make('send_wa')
                                    ->label('Kirim WA Pembeli')
                                    ->url(fn() => method_exists($record, 'getWaShippedLink') ? $record->getWaShippedLink() : '#', shouldOpenInNewTab: true)
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
                            $record->user->notify(new \App\Notifications\OrderStatusUpdated($record, 'Diterima'));
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
                            ->body('Pesanan telah ditandai sebagai diterima dan selesai.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Order $record) => $record->status === 'shipped'),
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
                            ->visible(fn (Order $record) => $record->status === 'processing'),
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
            ])->label('Cetak')->icon('heroicon-o-printer')->color('gray')->button()->visible(fn (Order $record) => !in_array($record->status, ['pending_payment', 'paid'])),

            // Kelompok Aksi Email
            Tables\Actions\ActionGroup::make([
                Tables\Actions\Action::make('send_invoice_email')
                    ->label('Kirim Email Invoice')
                    ->icon('heroicon-o-envelope')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        try {
                            \Illuminate\Support\Facades\Mail::to($record->shipping_email ?? $record->user->email)
                                ->send(new \App\Mail\InvoiceMail($record));
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Email Invoice Berhasil Dikirim')
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Gagal Mengirim Email')
                                ->body('Pastikan pengaturan SMTP sudah benar di menu Pengaturan Website. Error: ' . $e->getMessage())
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
                                        ->formatStateUsing(fn ($state, Order $record) => 
                                            $record->latestPayment 
                                                ? ($record->latestPayment->payment_type_label . ($record->latestPayment->va_number ? " (VA: " . $record->latestPayment->va_number . ")" : ""))
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

                            Infolists\Components\Section::make('Alamat Pengiriman')
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
