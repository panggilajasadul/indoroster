<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VoucherResource\Pages;
use App\Models\Voucher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Voucher & Promo';

    protected static ?string $modelLabel = 'Voucher & Promo';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Utama Voucher')
                ->schema([
                    Forms\Components\TextInput::make('code')
                        ->label('Kode Voucher')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->placeholder('Contoh: ONGKIRJABODETABEK')
                        ->extraInputAttributes(['style' => 'text-transform: uppercase;']),
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Promo / Voucher')
                        ->required()
                        ->placeholder('Contoh: Gratis Ongkir Armada Pabrik Jabodetabek'),
                    Forms\Components\TextInput::make('badge_text')
                        ->label('Label Badge Promo')
                        ->placeholder('Contoh: Khusus Jabodetabek / Promo Jawa Barat'),
                    Forms\Components\Select::make('type')
                        ->label('Tipe Potongan')
                        ->options([
                            'free_shipping' => '🚚 Gratis Ongkir Armada Pabrik',
                            'fixed_discount' => '💵 Potongan Nominal Tetap (Rp)',
                            'percent_discount' => '🏷️ Diskon Persentase (%)',
                        ])
                        ->default('free_shipping')
                        ->required()
                        ->live(),
                    Forms\Components\TextInput::make('discount_amount')
                        ->label('Nilai Potongan (Rp atau %)')
                        ->numeric()
                        ->default(0)
                        ->helperText('Kosongkan 0 untuk tipe Gratis Ongkir.')
                        ->visible(fn (Forms\Get $get) => $get('type') !== 'free_shipping'),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Voucher Aktif')
                        ->default(true),
                ])->columns(2),

            Forms\Components\Section::make('Aturan Wilayah & Syarat Minimum Order')
                ->schema([
                    Forms\Components\TagsInput::make('allowed_regions')
                        ->label('Cakupan Wilayah / Kota Target')
                        ->placeholder('Tambah wilayah (Tekan Enter)')
                        ->helperText('Masukkan nama kota/provinsi yang berhak mengklaim promo ini (contoh: Jakarta, Bogor, Depok, Tangerang, Bekasi, Bandung, Jawa Barat, Nasional).')
                        ->suggestions([
                            'DKI Jakarta',
                            'Jakarta Selatan',
                            'Jakarta Barat',
                            'Jakarta Timur',
                            'Jakarta Utara',
                            'Jakarta Pusat',
                            'Bogor',
                            'Depok',
                            'Tangerang',
                            'Tangerang Selatan',
                            'Bekasi',
                            'Jabodetabek',
                            'Purwakarta',
                            'Karawang',
                            'Bandung',
                            'Cimahi',
                            'Subang',
                            'Cirebon',
                            'Indramayu',
                            'Sukabumi',
                            'Cianjur',
                            'Jawa Barat',
                            'Jawa Tengah',
                            'Jawa Timur',
                            'Bali',
                            'Sumatera',
                            'Kalimantan',
                            'Sulawesi',
                            'Nasional',
                        ]),
                    Forms\Components\TextInput::make('min_order_qty')
                        ->label('Minimal Jumlah Order (Pcs Roster)')
                        ->numeric()
                        ->default(0)
                        ->helperText('Contoh: 100 pcs untuk memenuhi syarat gratis ongkir.'),
                    Forms\Components\TextInput::make('min_order_amount')
                        ->label('Minimal Total Belanja (Rp)')
                        ->numeric()
                        ->prefix('Rp')
                        ->default(0),
                    Forms\Components\DateTimePicker::make('valid_from')
                        ->label('Berlaku Mulai (Opsional)'),
                    Forms\Components\DateTimePicker::make('valid_until')
                        ->label('Berlaku Sampai (Opsional)'),
                    Forms\Components\Textarea::make('description')
                        ->label('Syarat & Ketentuan Voucher')
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->badge()
                    ->color('primary')
                    ->copyable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Promo')
                    ->searchable()
                    ->description(fn (Voucher $record): ?string => $record->badge_text),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'free_shipping' => 'Gratis Ongkir',
                        'fixed_discount' => 'Diskon Nominal',
                        'percent_discount' => 'Diskon Persen',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'free_shipping' => 'success',
                        'fixed_discount' => 'warning',
                        'percent_discount' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('min_order_qty')
                    ->label('Min. Pcs')
                    ->formatStateUsing(fn ($state) => $state > 0 ? "{$state} pcs" : '-'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
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
            'index' => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'edit' => Pages\EditVoucher::route('/{record}/edit'),
        ];
    }
}
