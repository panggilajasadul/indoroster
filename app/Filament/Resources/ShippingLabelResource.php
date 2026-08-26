<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingLabelResource\Pages;
use App\Models\ShippingLabel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShippingLabelResource extends Resource
{
    protected static ?string $model = ShippingLabel::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Label Pengiriman';

    protected static ?string $modelLabel = 'Label Pengiriman';

    protected static ?string $pluralModelLabel = 'Label Pengiriman';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        $defaultSender = ShippingLabel::getDefaultSender();

        return $form->schema([
            Forms\Components\Section::make('Info Pesanan')
                ->schema([
                    Forms\Components\Select::make('order_id')
                        ->label('Pesanan')
                        ->relationship('order', 'order_number')
                        ->searchable()
                        ->required(),
                    Forms\Components\TextInput::make('label_number')
                        ->label('No. Label')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->default(fn () => ShippingLabel::generateLabelNumber()),
                ])->columns(2),

            Forms\Components\Section::make('Pengirim')
                ->schema([
                    Forms\Components\TextInput::make('sender_name')
                        ->label('Nama Pengirim')
                        ->required()
                        ->default($defaultSender['sender_name']),
                    Forms\Components\TextInput::make('sender_phone')
                        ->label('No. HP Pengirim')
                        ->tel()
                        ->required()
                        ->default($defaultSender['sender_phone']),
                    Forms\Components\Textarea::make('sender_address')
                        ->label('Alamat Pengirim')
                        ->required()
                        ->rows(2)
                        ->default($defaultSender['sender_address'])
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Penerima')
                ->schema([
                    Forms\Components\TextInput::make('recipient_name')
                        ->label('Nama Penerima')
                        ->required(),
                    Forms\Components\TextInput::make('recipient_phone')
                        ->label('No. HP Penerima')
                        ->tel()
                        ->required(),
                    Forms\Components\Textarea::make('recipient_address')
                        ->label('Alamat Penerima')
                        ->required()
                        ->rows(2)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('recipient_city')
                        ->label('Kota Tujuan')
                        ->required(),
                    Forms\Components\TextInput::make('recipient_postal_code')
                        ->label('Kode Pos'),
                    Forms\Components\TextInput::make('recipient_latitude')
                        ->label('Latitude GPS')
                        ->numeric()
                        ->placeholder('-6.2088'),
                    Forms\Components\TextInput::make('recipient_longitude')
                        ->label('Longitude GPS')
                        ->numeric()
                        ->placeholder('106.8456'),
                ])->columns(2),

            Forms\Components\Section::make('Detail Pengiriman')
                ->schema([
                    Forms\Components\TextInput::make('courier')
                        ->label('Kurir / Ekspedisi')
                        ->required()
                        ->placeholder('SiCepat, JNE, Truck Kargo'),
                    Forms\Components\TextInput::make('service_type')
                        ->label('Tipe Layanan')
                        ->placeholder('REG, YES, Kargo'),
                    Forms\Components\TextInput::make('tracking_number')
                        ->label('No. Resi'),
                    Forms\Components\TextInput::make('total_items')
                        ->label('Total Item (pcs)')
                        ->numeric()
                        ->required(),
                    Forms\Components\TextInput::make('total_weight')
                        ->label('Total Berat')
                        ->numeric()
                        ->suffix('kg')
                        ->required(),
                    Forms\Components\TextInput::make('total_packages')
                        ->label('Jumlah Koli')
                        ->numeric()
                        ->default(1),
                    Forms\Components\TextInput::make('package_description')
                        ->label('Isi Paket')
                        ->placeholder('Roster Beton 500 pcs')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('special_instructions')
                        ->label('Instruksi Khusus')
                        ->placeholder('FRAGILE - Barang Pecah Belah')
                        ->rows(2)
                        ->columnSpanFull(),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label_number')
                    ->label('No. Label')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('No. Pesanan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('recipient_name')
                    ->label('Penerima')
                    ->searchable(),
                Tables\Columns\TextColumn::make('recipient_city')
                    ->label('Kota Tujuan'),
                Tables\Columns\TextColumn::make('courier')
                    ->label('Kurir')
                    ->badge(),
                Tables\Columns\TextColumn::make('tracking_number')
                    ->label('No. Resi')
                    ->copyable(),
                Tables\Columns\TextColumn::make('total_items')
                    ->label('Item')
                    ->suffix(' pcs'),
                Tables\Columns\TextColumn::make('total_weight')
                    ->label('Berat')
                    ->suffix(' kg'),
                Tables\Columns\TextColumn::make('printed_at')
                    ->label('Dicetak')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('print')
                    ->label('Cetak Resi')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn (ShippingLabel $record) => route('print.shipping-label', $record))
                    ->openUrlInNewTab(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShippingLabels::route('/'),
            'create' => Pages\CreateShippingLabel::route('/create'),
            'edit' => Pages\EditShippingLabel::route('/{record}/edit'),
        ];
    }
}
