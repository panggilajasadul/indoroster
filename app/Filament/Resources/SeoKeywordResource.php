<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeoKeywordResource\Pages;
use App\Models\SeoKeyword;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SeoKeywordResource extends Resource
{
    protected static ?string $model = SeoKeyword::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationGroup = 'SEO Page Factory';

    protected static ?string $navigationLabel = 'Keyword Universe';

    protected static ?string $modelLabel = 'Keyword';

    protected static ?string $pluralModelLabel = 'Keywords';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('Keyword Tabs')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Keyword & Klasifikasi')
                        ->schema([
                            Forms\Components\TextInput::make('keyword')
                                ->label('Kata Kunci')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),
                            Forms\Components\Select::make('cluster')
                                ->label('Cluster')
                                ->required()
                                ->options([
                                    'supplier' => 'Supplier / Pabrik / Distributor',
                                    'kontraktor' => 'Kontraktor / Pemborong',
                                    'developer' => 'Developer / Perumahan',
                                    'gedung' => 'Gedung / Komersial',
                                    'fasad' => 'Fasad / Arsitektur',
                                    'ventilasi' => 'Ventilasi',
                                    'grosir' => 'Grosir / Volume',
                                    'procurement' => 'Procurement / Pengadaan',
                                    'harga' => 'Harga',
                                    'produk' => 'Produk / Motif',
                                    'lokasi' => 'Lokasi',
                                    'edukasi' => 'Edukasi / Informational',
                                ])
                                ->searchable(),
                            Forms\Components\Select::make('intent')
                                ->label('Search Intent')
                                ->options([
                                    'tofu' => 'TOFU — Informational',
                                    'mofu' => 'MOFU — Commercial Investigation',
                                    'bofu' => 'BOFU — Transactional',
                                ])
                                ->default('mofu')
                                ->required(),
                            Forms\Components\Select::make('buyer_type')
                                ->label('Target Buyer')
                                ->options([
                                    'kontraktor' => 'Kontraktor',
                                    'developer' => 'Developer',
                                    'pemborong' => 'Pemborong',
                                    'arsitek' => 'Arsitek',
                                    'procurement' => 'Procurement',
                                    'owner' => 'Pemilik Rumah/Proyek',
                                    'umum' => 'Umum',
                                ]),
                            Forms\Components\Select::make('project_type')
                                ->label('Tipe Proyek')
                                ->options([
                                    'perumahan' => 'Perumahan / Cluster',
                                    'gedung' => 'Gedung / Perkantoran',
                                    'komersial' => 'Komersial (Hotel, Restoran, dll)',
                                    'renovasi' => 'Renovasi',
                                    'fasad' => 'Fasad',
                                    'ventilasi' => 'Ventilasi',
                                    'umum' => 'Umum',
                                ]),
                            Forms\Components\TextInput::make('location')
                                ->label('Lokasi Target')
                                ->placeholder('Kosongkan jika nasional')
                                ->maxLength(100),
                        ])->columns(2),

                    Forms\Components\Tabs\Tab::make('Scoring & Prioritas')
                        ->schema([
                            Forms\Components\Select::make('business_value')
                                ->label('Nilai Bisnis (1-5)')
                                ->options([1 => '1 — Rendah', 2 => '2', 3 => '3 — Sedang', 4 => '4', 5 => '5 — Sangat Tinggi'])
                                ->default(3)
                                ->required(),
                            Forms\Components\Select::make('conversion_potential')
                                ->label('Potensi Konversi (1-5)')
                                ->options([1 => '1 — Rendah', 2 => '2', 3 => '3 — Sedang', 4 => '4', 5 => '5 — Sangat Tinggi'])
                                ->default(3)
                                ->required(),
                            Forms\Components\Select::make('competition')
                                ->label('Level Kompetisi (1-5)')
                                ->options([1 => '1 — Rendah', 2 => '2', 3 => '3 — Sedang', 4 => '4', 5 => '5 — Sangat Tinggi'])
                                ->default(3),
                            Forms\Components\TextInput::make('search_volume_est')
                                ->label('Estimasi Search Volume')
                                ->placeholder('contoh: 100-1K, 1K-10K')
                                ->maxLength(50),
                            Forms\Components\TextInput::make('priority_score')
                                ->label('Priority Score (auto)')
                                ->disabled()
                                ->dehydrated(false)
                                ->helperText('Dihitung otomatis berdasarkan scoring.'),
                        ])->columns(2),

                    Forms\Components\Tabs\Tab::make('Status & Mapping')
                        ->schema([
                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->options([
                                    'idea' => 'Ide',
                                    'researched' => 'Sudah Riset',
                                    'targeted' => 'Ditargetkan',
                                    'mapped' => 'Sudah Dimapping ke Halaman',
                                    'archived' => 'Diarsipkan',
                                ])
                                ->default('idea')
                                ->required(),
                            Forms\Components\Select::make('target_page_id')
                                ->label('Target Halaman SEO')
                                ->relationship('targetPage', 'title')
                                ->searchable()
                                ->preload()
                                ->helperText('Halaman SEO yang menargetkan keyword ini.'),
                            Forms\Components\Select::make('source')
                                ->label('Sumber')
                                ->options([
                                    'manual' => 'Input Manual',
                                    'gsc' => 'Google Search Console',
                                    'google_ads' => 'Google Ads Search Terms',
                                    'competitor' => 'Riset Kompetitor',
                                    'ai_suggestion' => 'AI Suggestion',
                                ])
                                ->default('manual'),
                            Forms\Components\Textarea::make('notes')
                                ->label('Catatan')
                                ->rows(3)
                                ->columnSpanFull(),
                        ])->columns(2),
                ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('keyword')
                    ->label('Kata Kunci')
                    ->searchable()
                    ->sortable()
                    ->limit(60),
                Tables\Columns\TextColumn::make('cluster')
                    ->label('Cluster')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('intent')
                    ->label('Intent')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'bofu' => 'success',
                        'mofu' => 'warning',
                        'tofu' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('buyer_type')
                    ->label('Buyer')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('priority_score')
                    ->label('Priority')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 8 => 'success',
                        $state >= 5 => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'mapped' => 'success',
                        'targeted' => 'info',
                        'researched' => 'warning',
                        'archived' => 'gray',
                        default => 'primary',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('targetPage.title')
                    ->label('Target Page')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('priority_score', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('cluster')
                    ->label('Cluster')
                    ->multiple()
                    ->options([
                        'supplier' => 'Supplier',
                        'kontraktor' => 'Kontraktor',
                        'developer' => 'Developer',
                        'gedung' => 'Gedung',
                        'fasad' => 'Fasad',
                        'ventilasi' => 'Ventilasi',
                        'grosir' => 'Grosir',
                        'procurement' => 'Procurement',
                        'harga' => 'Harga',
                        'produk' => 'Produk',
                        'lokasi' => 'Lokasi',
                    ]),
                Tables\Filters\SelectFilter::make('intent')
                    ->options([
                        'tofu' => 'TOFU',
                        'mofu' => 'MOFU',
                        'bofu' => 'BOFU',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->options([
                        'idea' => 'Ide',
                        'researched' => 'Sudah Riset',
                        'targeted' => 'Ditargetkan',
                        'mapped' => 'Sudah Dimapping',
                        'archived' => 'Diarsipkan',
                    ]),
                Tables\Filters\TernaryFilter::make('unmapped')
                    ->label('Belum Dimapping')
                    ->queries(
                        true: fn ($query) => $query->whereNull('target_page_id'),
                        false: fn ($query) => $query->whereNotNull('target_page_id'),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('calculate_priority')
                    ->label('Hitung Priority')
                    ->icon('heroicon-o-calculator')
                    ->action(function (SeoKeyword $record) {
                        $record->updatePriorityScore();
                    })
                    ->requiresConfirmation()
                    ->color('info'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('calculate_priority_bulk')
                        ->label('Hitung Priority Score')
                        ->icon('heroicon-o-calculator')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->updatePriorityScore();
                            }
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSeoKeywords::route('/'),
            'create' => Pages\CreateSeoKeyword::route('/create'),
            'edit' => Pages\EditSeoKeyword::route('/{record}/edit'),
        ];
    }
}
