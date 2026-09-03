<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationPageResource\Pages;
use App\Models\ApplicationPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ApplicationPageResource extends Resource
{
    protected static ?string $model = ApplicationPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $navigationGroup = 'Konten & SEO';

    protected static ?string $navigationLabel = 'Aplikasi & Inspirasi Desain';

    protected static ?string $modelLabel = 'Halaman Aplikasi';

    protected static ?string $pluralModelLabel = 'Halaman Aplikasi Desain';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('ApplicationDetails')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('1. Informasi Dasar')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Judul Aplikasi')
                                    ->required()
                                    ->placeholder('Contoh: Roster Beton Pagar Minimalis Modern')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),

                                Forms\Components\TextInput::make('slug')
                                    ->label('URL Slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('pagar-rumah')
                                    ->helperText('Akses halaman: /aplikasi/{slug}'),

                                Forms\Components\TextInput::make('badge')
                                    ->label('Badge / Kategori')
                                    ->placeholder('🏡 Pagar & Pembatas Kavling'),

                                Forms\Components\TextInput::make('icon')
                                    ->label('Emoji / Icon')
                                    ->placeholder('🏡 atau 🏛️'),

                                Forms\Components\TextInput::make('subtitle')
                                    ->label('Subjudul Singkat')
                                    ->placeholder('Kombinasi privasi, sirkulasi udara, dan estetika modern.')
                                    ->columnSpanFull(),

                                Forms\Components\TextInput::make('image')
                                    ->label('Link Gambar Banner Utama')
                                    ->placeholder('https://res.cloudinary.com/... atau link gambar')
                                    ->columnSpanFull(),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Status Aktif')
                                    ->default(true),

                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Urutan Tampilan')
                                    ->numeric()
                                    ->default(0),
                            ]),
                        ]),

                    Forms\Components\Tabs\Tab::make('2. Narasi Arsitektural')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Forms\Components\TextInput::make('headline')
                                ->label('Headline Utama')
                                ->placeholder('Desain Pagar Rumah Minimalis Modern dengan Roster Beton Tumbuk Padat')
                                ->columnSpanFull(),

                            Forms\Components\Textarea::make('intro')
                                ->label('Paragraf Pengantar (Intro)')
                                ->rows(4)
                                ->placeholder('Penjelasan pengantar mendalam mengenai aplikasi ini...')
                                ->columnSpanFull(),

                            Forms\Components\Section::make('Ulasan Arsitektural Mendalam (Deep Narrative)')
                                ->schema([
                                    Forms\Components\TextInput::make('deep_narrative.title')
                                        ->label('Judul Narasi Mendalam')
                                        ->placeholder('Mengapa Roster Beton Adalah Material Terbaik untuk Pagar Rumah Tropis?'),

                                    Forms\Components\Textarea::make('deep_narrative.p1')
                                        ->label('Paragraf 1 (Analisis Desain/Termal)')
                                        ->rows(4),

                                    Forms\Components\Textarea::make('deep_narrative.p2')
                                        ->label('Paragraf 2 (Kekuatan & Materialitas)')
                                        ->rows(4),
                                ]),
                        ]),

                    Forms\Components\Tabs\Tab::make('3. Spesifikasi & Motif')
                        ->icon('heroicon-o-cube')
                        ->schema([
                            Forms\Components\TagsInput::make('motifs')
                                ->label('Rekomendasi Nama Motif')
                                ->placeholder('Tambah motif (cth: Nako Sipit, MMC, Petir)')
                                ->helperText('Ketik nama motif lalu tekan Enter')
                                ->columnSpanFull(),

                            Forms\Components\Section::make('Spesifikasi Teknis Standar')
                                ->schema([
                                    Forms\Components\TextInput::make('specs.dimensi')
                                        ->label('Dimensi Standar')
                                        ->default('20 × 20 × 10 cm (Standar Arsitektural)'),

                                    Forms\Components\TextInput::make('specs.bobot')
                                        ->label('Bobot per Keping')
                                        ->default('3.8 – 4.2 kg / keping'),

                                    Forms\Components\TextInput::make('specs.kebutuhan_luas')
                                        ->label('Kebutuhan per m²')
                                        ->default('25 keping per 1 meter persegi (m²)'),

                                    Forms\Components\TextInput::make('specs.komposisi')
                                        ->label('Komposisi Material')
                                        ->default('Pasir Abu Batu Murni Pilihan + Semen Mutu Tinggi'),

                                    Forms\Components\TextInput::make('specs.metode_produksi')
                                        ->label('Metode Produksi')
                                        ->default('Cetak Tumbuk Padat Plat Baja Siku 90° Presisi'),

                                    Forms\Components\TextInput::make('specs.pilihan_warna')
                                        ->label('Pilihan Warna')
                                        ->default('Abu Semen Natural, Putih Semen, Merah Terakota'),
                                ])->columns(2),
                        ]),

                    Forms\Components\Tabs\Tab::make('4. Panduan Pasang & FAQ')
                        ->icon('heroicon-o-wrench-screwdriver')
                        ->schema([
                            Forms\Components\Repeater::make('installation_guide.steps')
                                ->label('Langkah Panduan Pemasangan Teknis')
                                ->schema([
                                    Forms\Components\TextInput::make('step')
                                        ->label('Judul Langkah')
                                        ->placeholder('1. Pondasi Sloof Beton Bertulang')
                                        ->required(),
                                    Forms\Components\Textarea::make('desc')
                                        ->label('Penjelasan Teknis')
                                        ->rows(2)
                                        ->required(),
                                ])
                                ->columnSpanFull()
                                ->collapsible(),

                            Forms\Components\TagsInput::make('design_tips')
                                ->label('Tips Desain Arsitek')
                                ->placeholder('Tambah tips arsitektural')
                                ->columnSpanFull(),

                            Forms\Components\Repeater::make('faqs')
                                ->label('Tanya Jawab (FAQ)')
                                ->schema([
                                    Forms\Components\TextInput::make('q')
                                        ->label('Pertanyaan (Q)')
                                        ->required(),
                                    Forms\Components\Textarea::make('a')
                                        ->label('Jawaban (A)')
                                        ->rows(2)
                                        ->required(),
                                ])
                                ->columnSpanFull()
                                ->collapsible(),
                        ]),

                    Forms\Components\Tabs\Tab::make('5. Form SEO & Meta Data')
                        ->icon('heroicon-o-magnifying-glass')
                        ->schema([
                            Forms\Components\TextInput::make('meta_title')
                                ->label('Meta Title (Google & Mesin Pencari)')
                                ->placeholder('Kosongkan untuk auto-generate dari judul aplikasi')
                                ->maxLength(255),

                            Forms\Components\Textarea::make('meta_description')
                                ->label('Meta Description')
                                ->rows(3)
                                ->placeholder('Deskripsi singkat untuk hasil pencarian Google (140-160 karakter disarankan)'),

                            Forms\Components\TextInput::make('keywords')
                                ->label('Kata Kunci (Keywords)')
                                ->placeholder('roster pagar, pagar roster beton minimalis'),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('icon')
                    ->label('')
                    ->width('40px'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Aplikasi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('URL Slug')
                    ->copyable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('badge')
                    ->label('Kategori/Badge')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime('d M Y H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListApplicationPages::route('/'),
            'create' => Pages\CreateApplicationPage::route('/create'),
            'edit' => Pages\EditApplicationPage::route('/{record}/edit'),
        ];
    }
}
