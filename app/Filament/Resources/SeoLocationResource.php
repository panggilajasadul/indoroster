<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeoLocationResource\Pages;
use App\Models\Product;
use App\Models\SeoLocation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SeoLocationResource extends Resource
{
    protected static ?string $model = SeoLocation::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Sitemap & SEO';

    protected static ?string $navigationLabel = 'Lokasi SEO Multi-Kota';

    protected static ?string $modelLabel = 'Lokasi SEO';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Lokasi Tabs')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Informasi Wilayah')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Nama Kota / Wilayah')
                                ->required()
                                ->maxLength(100),
                            Forms\Components\TextInput::make('slug')
                                ->label('URL Slug')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(120),
                            Forms\Components\Select::make('type')
                                ->label('Tipe Wilayah')
                                ->options([
                                    'city' => 'Kota',
                                    'regency' => 'Kabupaten',
                                    'metropolitan_area' => 'Kawasan Metropolitan',
                                    'area' => 'Area / Kawasan Properti',
                                ])
                                ->default('city')
                                ->required(),
                            Forms\Components\Select::make('priority')
                                ->label('Prioritas Wilayah')
                                ->options([
                                    1 => 'Prioritas 1 (Jabodetabek / Bandung / Jabar Utama)',
                                    2 => 'Prioritas 2 (Kota Besar Jawa)',
                                    3 => 'Prioritas 3 (Luar Jawa)',
                                ])
                                ->default(1),
                            Forms\Components\Toggle::make('seo_enabled')
                                ->label('Aktifkan SEO & Index Google (Sitemap)')
                                ->default(true)
                                ->helperText('Hanya aktifkan jika konten unik dan informasi logistik sudah lengkap.'),
                            Forms\Components\TextInput::make('seo_score')
                                ->label('Skor Mutu SEO (0-100)')
                                ->disabled()
                                ->dehydrated(false)
                                ->helperText('Dihitung otomatis berdasarkan kelengkapan data & mutu konten.'),
                        ])->columns(2),

                    Forms\Components\Tabs\Tab::make('Konten & Logistik Pengiriman')
                        ->schema([
                            Forms\Components\TextInput::make('headline')
                                ->label('Headline Utama')
                                ->placeholder('Contoh: Supplier & Produsen Roster Beton untuk Wilayah Bandung Raya')
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('intro_content')
                                ->label('Konten Pengantar Unik')
                                ->rows(4)
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('delivery_route_info')
                                ->label('Info Rute Ekspedisi / Tol')
                                ->placeholder('Contoh: Pengiriman langsung via Tol Purbaleunyi')
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('estimated_delivery_time')
                                ->label('Estimasi Waktu Kirim')
                                ->placeholder('Contoh: 1 - 2 Hari Kerja'),
                            Forms\Components\TextInput::make('shipping_guarantee_text')
                                ->label('Teks Garansi Pengiriman')
                                ->placeholder('Contoh: Garansi 100% Bebas Pecah'),
                            Forms\Components\TagsInput::make('target_districts')
                                ->label('Kecamatan / Area Populer')
                                ->placeholder('Ketik nama kecamatan dan tekan Enter')
                                ->columnSpanFull(),
                        ])->columns(2),

                    Forms\Components\Tabs\Tab::make('Meta SEO & FAQ')
                        ->schema([
                            Forms\Components\TextInput::make('meta_title')
                                ->label('Meta Title')
                                ->maxLength(255)
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('meta_description')
                                ->label('Meta Description')
                                ->rows(3)
                                ->columnSpanFull(),
                            Forms\Components\Select::make('recommended_motif_ids')
                                ->label('Rekomendasi Produk di Kota Ini')
                                ->multiple()
                                ->options(Product::where('is_active', true)->pluck('name', 'id'))
                                ->columnSpanFull(),
                            Forms\Components\Repeater::make('custom_faqs')
                                ->label('FAQ Kontekstual Kota')
                                ->schema([
                                    Forms\Components\TextInput::make('q')->label('Pertanyaan')->required(),
                                    Forms\Components\Textarea::make('a')->label('Jawaban')->required(),
                                ])
                                ->columnSpanFull(),
                        ]),
                ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Wilayah')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->copyable(),
                Tables\Columns\TextColumn::make('priority')
                    ->label('Prioritas')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        1 => 'danger',
                        2 => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('seo_score')
                    ->label('Skor SEO')
                    ->badge()
                    ->color(fn (int $state): string => $state >= 75 ? 'success' : 'danger')
                    ->sortable(),
                Tables\Columns\IconColumn::make('seo_enabled')
                    ->label('SEO Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Update')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('priority', 'asc')
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
            'index' => Pages\ListSeoLocations::route('/'),
            'create' => Pages\CreateSeoLocation::route('/create'),
            'edit' => Pages\EditSeoLocation::route('/{record}/edit'),
        ];
    }
}
