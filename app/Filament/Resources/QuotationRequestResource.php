<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuotationRequestResource\Pages;
use App\Models\QuotationRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class QuotationRequestResource extends Resource
{
    protected static ?string $model = QuotationRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

    protected static ?string $navigationGroup = 'Penjualan';

    protected static ?string $navigationLabel = 'Permintaan Penawaran (RFQ)';

    protected static ?string $modelLabel = 'Permintaan Penawaran';

    protected static ?string $pluralModelLabel = 'Permintaan Penawaran';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
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
                        Forms\Components\Section::make('Informasi Klien & Proyek')
                            ->schema([
                                Forms\Components\TextInput::make('reference_number')
                                    ->label('No. Referensi (RFQ)')
                                    ->disabled()
                                    ->dehydrated(),
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Klien')
                                    ->required(),
                                Forms\Components\TextInput::make('company_name')
                                    ->label('Nama Perusahaan / Proyek')
                                    ->placeholder('Opsional'),
                                Forms\Components\TextInput::make('phone')
                                    ->label('No. WhatsApp / HP')
                                    ->tel()
                                    ->required()
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('openWhatsApp')
                                            ->icon('heroicon-m-chat-bubble-left-ellipsis')
                                            ->color('success')
                                            ->url(fn (?QuotationRequest $record) => $record ? $record->getAdminToClientWhatsAppUrl() : null, true)
                                    ),
                                Forms\Components\TextInput::make('email')
                                    ->label('Email')
                                    ->email(),
                            ])->columns(2),

                        Forms\Components\Section::make('Produk & Kebutuhan')
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Produk')
                                    ->relationship('product', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Select::make('product_variant_id')
                                    ->label('Varian')
                                    ->relationship('variant', 'name')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah Kebutuhan (Pcs)')
                                    ->numeric()
                                    ->required()
                                    ->suffix('pcs'),
                                Forms\Components\TextInput::make('installation_timeline')
                                    ->label('Rencana Waktu Pasang / Dibutuhkan')
                                    ->placeholder('Contoh: 1-2 Minggu ke depan / Secepatnya'),
                                Forms\Components\Textarea::make('notes')
                                    ->label('Catatan Kebutuhan / Spesifikasi Klien')
                                    ->columnSpanFull()
                                    ->rows(3),
                            ])->columns(2),

                        Forms\Components\Section::make('Lokasi Pengiriman Proyek')
                            ->schema([
                                Forms\Components\TextInput::make('province_name')
                                    ->label('Provinsi'),
                                Forms\Components\TextInput::make('city_name')
                                    ->label('Kota / Kabupaten'),
                                Forms\Components\Textarea::make('address')
                                    ->label('Alamat Lengkap / Patokan Proyek / Akses Truk')
                                    ->columnSpanFull()
                                    ->rows(2),
                            ])->columns(2),
                    ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Status Penawaran')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'pending' => '⏳ Baru (Pending)',
                                        'contacted' => '💬 Sudah Dihubungi',
                                        'quoted' => '📄 Penawaran Terkirim (SPH)',
                                        'deal' => '✅ Deal / Masuk Pesanan',
                                        'cancelled' => '❌ Dibatalkan / Batal',
                                    ])
                                    ->required()
                                    ->default('pending')
                                    ->native(false),
                                Forms\Components\Textarea::make('admin_notes')
                                    ->label('Catatan Internal Sales / Admin')
                                    ->placeholder('Tulis hasil negosiasi harga, jadwal survei, atau catatan khusus admin...')
                                    ->rows(4),
                            ]),

                        Forms\Components\Section::make('Info Sistem')
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')
                                    ->label('Waktu Masuk')
                                    ->content(fn (?QuotationRequest $record) => $record?->created_at ? $record->created_at->translatedFormat('d F Y H:i:s') : '-'),
                                Forms\Components\Placeholder::make('updated_at')
                                    ->label('Terakhir Diupdate')
                                    ->content(fn (?QuotationRequest $record) => $record?->updated_at ? $record->updated_at->translatedFormat('d F Y H:i:s') : '-'),
                            ])->collapsed(),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference_number')
                    ->label('No. Ref')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Klien / Proyek')
                    ->description(fn (QuotationRequest $record): ?string => $record->company_name)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('WhatsApp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Produk')
                    ->description(fn (QuotationRequest $record): ?string => $record->variant?->name)
                    ->limit(25)
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.').' pcs')
                    ->sortable(),
                Tables\Columns\TextColumn::make('installation_timeline')
                    ->label('Rencana Pasang')
                    ->badge()
                    ->color('info')
                    ->placeholder('-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('city_name')
                    ->label('Lokasi Kirim')
                    ->description(fn (QuotationRequest $record): ?string => $record->address ? Str::limit($record->address, 30) : $record->province_name)
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => '⏳ Pending',
                        'contacted' => '💬 Dihubungi',
                        'quoted' => '📄 SPH Terkirim',
                        'deal' => '✅ Deal',
                        'cancelled' => '❌ Batal',
                    ])
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'contacted' => 'Sudah Dihubungi',
                        'quoted' => 'Penawaran Terkirim',
                        'deal' => 'Deal',
                        'cancelled' => 'Batal',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('chatWhatsApp')
                    ->label('Chat WA Klien')
                    ->icon('heroicon-m-chat-bubble-left-ellipsis')
                    ->color('success')
                    ->url(fn (QuotationRequest $record) => $record->getAdminToClientWhatsAppUrl(), true),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuotationRequests::route('/'),
            'create' => Pages\CreateQuotationRequest::route('/create'),
            'edit' => Pages\EditQuotationRequest::route('/{record}/edit'),
        ];
    }
}
