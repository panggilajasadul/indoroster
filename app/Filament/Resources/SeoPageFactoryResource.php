<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeoPageFactoryResource\Pages;
use App\Models\Product;
use App\Models\SeoPage;
use App\Services\SeoDuplicationChecker;
use App\Services\SeoQualityScorer;
use App\Services\SeoSlugRegistry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SeoPageFactoryResource extends Resource
{
    protected static ?string $model = SeoPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'SEO Page Factory';

    protected static ?string $navigationLabel = 'Halaman SEO';

    protected static ?string $modelLabel = 'Halaman SEO';

    protected static ?string $pluralModelLabel = 'Halaman SEO';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('SEO Page Tabs')
                ->tabs([
                    // ─── TAB 1: Identitas & Klasifikasi ───
                    Forms\Components\Tabs\Tab::make('Identitas')
                        ->icon('heroicon-o-identification')
                        ->schema([
                            Forms\Components\TextInput::make('slug')
                                ->label('URL Slug')
                                ->required()
                                ->maxLength(200)
                                ->unique(ignoreRecord: true)
                                ->rules([
                                    function () {
                                        return function (string $attribute, $value, $fail) {
                                            $registry = new SeoSlugRegistry;
                                            $pageId = request()->route('record');
                                            $result = $registry->validate($value, $pageId ? (int) $pageId : null);
                                            if (! $result['valid']) {
                                                $fail($result['reason']);
                                            }
                                        };
                                    },
                                ])
                                ->helperText('URL akan menjadi: indoroster.com/{slug}')
                                ->columnSpanFull(),

                            Forms\Components\Select::make('page_type')
                                ->label('Tipe Halaman')
                                ->required()
                                ->options([
                                    'pillar' => 'Pillar — Halaman Pilar Utama',
                                    'buyer' => 'Buyer — Spesifik untuk Tipe Pembeli',
                                    'project' => 'Project — Spesifik untuk Tipe Proyek',
                                    'usecase' => 'Use Case — Fasad, Ventilasi, dll',
                                    'product_landing' => 'Product Landing — Produk Group',
                                    'location' => 'Location — Commercial + Lokasi',
                                    'wholesale' => 'Wholesale — Volume / Grosir',
                                    'procurement' => 'Procurement — Vendor / Pengadaan',
                                    'pricing' => 'Pricing — Harga & Quotation',
                                    'guide' => 'Guide — Buying Guide',
                                    'faq_hub' => 'FAQ Hub',
                                    'case_study' => 'Case Study',
                                ]),

                            Forms\Components\TextInput::make('primary_keyword')
                                ->label('Primary Keyword')
                                ->required()
                                ->maxLength(255),

                            Forms\Components\TagsInput::make('secondary_keywords')
                                ->label('Secondary Keywords')
                                ->placeholder('Ketik keyword dan tekan Enter')
                                ->columnSpanFull(),

                            Forms\Components\Select::make('search_intent')
                                ->label('Search Intent')
                                ->required()
                                ->options([
                                    'tofu' => 'TOFU — Informational',
                                    'mofu' => 'MOFU — Commercial Investigation',
                                    'bofu' => 'BOFU — Transactional',
                                ])
                                ->default('bofu'),
                        ])->columns(2),

                    // ─── TAB 2: Target ───
                    Forms\Components\Tabs\Tab::make('Target')
                        ->icon('heroicon-o-user-group')
                        ->schema([
                            Forms\Components\Select::make('buyer_type')
                                ->label('Target Buyer')
                                ->options([
                                    'kontraktor' => 'Kontraktor',
                                    'developer' => 'Developer',
                                    'pemborong' => 'Pemborong',
                                    'arsitek' => 'Arsitek',
                                    'procurement' => 'Procurement',
                                    'owner' => 'Pemilik Rumah/Proyek',
                                    'umum' => 'Umum / Semua Buyer',
                                ]),
                            Forms\Components\Select::make('project_type')
                                ->label('Tipe Proyek')
                                ->options([
                                    'perumahan' => 'Perumahan / Cluster',
                                    'gedung' => 'Gedung / Perkantoran',
                                    'komersial' => 'Komersial (Hotel, Restoran, dll)',
                                    'renovasi' => 'Renovasi',
                                    'umum' => 'Umum',
                                ]),
                            Forms\Components\Select::make('use_case')
                                ->label('Use Case Produk')
                                ->options([
                                    'fasad' => 'Fasad',
                                    'ventilasi' => 'Ventilasi',
                                    'pagar' => 'Pagar / Dinding Pembatas',
                                    'carport' => 'Carport / Garasi',
                                    'dekoratif' => 'Dekoratif',
                                    'eksterior' => 'Dinding Eksterior',
                                ]),
                            Forms\Components\Select::make('seo_location_id')
                                ->label('Lokasi (SeoLocation)')
                                ->relationship('seoLocation', 'name')
                                ->searchable()
                                ->preload(),
                            Forms\Components\TextInput::make('location_name')
                                ->label('Nama Lokasi (Display)')
                                ->maxLength(100)
                                ->helperText('Isi manual jika tidak ada di SeoLocation'),
                        ])->columns(2),

                    // ─── TAB 3: Konten Utama ───
                    Forms\Components\Tabs\Tab::make('Konten')
                        ->icon('heroicon-o-pencil-square')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('SEO Title (<title>)')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('meta_description')
                                ->label('Meta Description')
                                ->required()
                                ->rows(3)
                                ->columnSpanFull(),
                            Forms\Components\TextInput::make('h1')
                                ->label('H1 Heading')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('opening_text')
                                ->label('Paragraf Pembuka')
                                ->required()
                                ->rows(4)
                                ->helperText('Harus langsung menjawab search intent. Min 100 karakter.')
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('unique_value_proposition')
                                ->label('Unique Value Proposition')
                                ->required()
                                ->rows(3)
                                ->helperText('Kenapa halaman ini PERLU ADA? Apa yang membedakan dari halaman lain?')
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('unique_evidence')
                                ->label('Evidence / Data Unik')
                                ->rows(3)
                                ->helperText('Fakta nyata, data produksi, informasi pengiriman, dll. JANGAN mengarang.')
                                ->columnSpanFull(),
                            Forms\Components\Textarea::make('unique_angle')
                                ->label('Unique Angle / Sudut Pandang')
                                ->rows(2)
                                ->columnSpanFull(),
                        ]),

                    // ─── TAB 4: Sections ───
                    Forms\Components\Tabs\Tab::make('Sections')
                        ->icon('heroicon-o-list-bullet')
                        ->schema([
                            Forms\Components\Repeater::make('sections')
                                ->label('Content Sections')
                                ->relationship()
                                ->schema([
                                    Forms\Components\Select::make('section_type')
                                        ->label('Tipe Section')
                                        ->required()
                                        ->options([
                                            'intro' => 'Pengantar',
                                            'problem' => 'Masalah Pembeli',
                                            'solution' => 'Solusi IndoRoster',
                                            'products' => 'Produk Relevan',
                                            'specs' => 'Spesifikasi',
                                            'usecase' => 'Aplikasi / Use Case',
                                            'process' => 'Cara Memesan',
                                            'shipping' => 'Pengiriman',
                                            'volume' => 'Kebutuhan Volume / MOQ',
                                            'pricing_guide' => 'Panduan Harga',
                                            'faq' => 'FAQ',
                                            'cta' => 'Call to Action',
                                            'related' => 'Halaman Terkait',
                                            'comparison' => 'Perbandingan',
                                            'custom' => 'Custom',
                                        ]),
                                    Forms\Components\TextInput::make('heading')
                                        ->label('Heading (H2/H3)')
                                        ->required()
                                        ->maxLength(255),
                                    Forms\Components\RichEditor::make('content')
                                        ->label('Konten')
                                        ->required()
                                        ->columnSpanFull(),
                                    Forms\Components\TextInput::make('sort_order')
                                        ->label('Urutan')
                                        ->numeric()
                                        ->default(0),
                                    Forms\Components\Toggle::make('is_visible')
                                        ->label('Tampilkan')
                                        ->default(true),
                                ])
                                ->orderColumn('sort_order')
                                ->collapsible()
                                ->cloneable()
                                ->columnSpanFull(),
                        ]),

                    // ─── TAB 5: Produk & CTA ───
                    Forms\Components\Tabs\Tab::make('Produk & CTA')
                        ->icon('heroicon-o-shopping-bag')
                        ->schema([
                            Forms\Components\Section::make('Product Matching')
                                ->schema([
                                    Forms\Components\Select::make('product_matching_rule')
                                        ->label('Aturan Product Matching')
                                        ->options([
                                            'manual' => 'Manual — Pilih produk spesifik',
                                            'all' => 'Semua Produk Aktif',
                                            'featured' => 'Produk Unggulan (is_featured)',
                                            'category:roster-beton' => 'Kategori: Roster Beton',
                                        ])
                                        ->default('featured'),
                                    Forms\Components\Select::make('product_ids')
                                        ->label('Produk Manual')
                                        ->multiple()
                                        ->options(Product::where('is_active', true)->pluck('name', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->helperText('Hanya digunakan jika matching rule = manual')
                                        ->columnSpanFull(),
                                ]),

                            Forms\Components\Section::make('CTA & Konversi')
                                ->schema([
                                    Forms\Components\Select::make('cta_type')
                                        ->label('Tipe CTA')
                                        ->required()
                                        ->options([
                                            'whatsapp' => 'WhatsApp',
                                            'quotation' => 'Request Quotation',
                                            'catalog' => 'Lihat Katalog',
                                            'calculator' => 'Kalkulator Roster',
                                            'contact' => 'Halaman Kontak',
                                        ])
                                        ->default('whatsapp'),
                                    Forms\Components\TextInput::make('cta_text')
                                        ->label('Teks CTA')
                                        ->maxLength(255)
                                        ->placeholder('Contoh: Kirim kebutuhan proyek Anda via WhatsApp'),
                                    Forms\Components\Textarea::make('cta_wa_message')
                                        ->label('Pesan WhatsApp Custom')
                                        ->rows(3)
                                        ->helperText('Kosongkan untuk pesan otomatis berdasarkan buyer type')
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    // ─── TAB 6: Linking & SEO Advanced ───
                    Forms\Components\Tabs\Tab::make('Linking & SEO')
                        ->icon('heroicon-o-link')
                        ->schema([
                            Forms\Components\Select::make('parent_page_id')
                                ->label('Parent Page (Pillar)')
                                ->relationship('parentPage', 'title')
                                ->searchable()
                                ->preload(),
                            Forms\Components\Select::make('related_page_ids')
                                ->label('Halaman Terkait')
                                ->multiple()
                                ->options(SeoPage::pluck('title', 'id'))
                                ->searchable(),
                            Forms\Components\Select::make('structured_data_type')
                                ->label('Structured Data')
                                ->options([
                                    'product_list' => 'Product List',
                                    'faq' => 'FAQ Page',
                                    'how_to' => 'How To',
                                    'article' => 'Article',
                                ]),
                            Forms\Components\TextInput::make('og_title')
                                ->label('OG Title')
                                ->maxLength(255),
                            Forms\Components\Textarea::make('og_description')
                                ->label('OG Description')
                                ->rows(2),
                            Forms\Components\TextInput::make('canonical_url')
                                ->label('Canonical URL Override')
                                ->maxLength(500),
                            Forms\Components\Toggle::make('noindex')
                                ->label('Noindex (jangan index di Google)')
                                ->default(false),
                        ])->columns(2),

                    // ─── TAB 7: Quality & Status ───
                    Forms\Components\Tabs\Tab::make('Quality & Status')
                        ->icon('heroicon-o-shield-check')
                        ->schema([
                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->required()
                                ->options([
                                    'idea' => 'Ide',
                                    'research' => 'Riset',
                                    'approved' => 'Disetujui',
                                    'content_brief' => 'Content Brief',
                                    'draft' => 'Draft',
                                    'qa' => 'Quality Assurance',
                                    'needs_review' => 'Perlu Review',
                                    'ready' => 'Siap Publish',
                                    'published' => 'Published',
                                    'monitoring' => 'Monitoring',
                                    'update' => 'Perlu Update',
                                    'merge' => 'Perlu Merge',
                                    'noindex' => 'Noindex',
                                    'archived' => 'Diarsipkan',
                                ])
                                ->default('idea'),
                            Forms\Components\TextInput::make('priority_score')
                                ->label('Priority Score')
                                ->numeric()
                                ->default(0),
                            Forms\Components\Placeholder::make('quality_score_display')
                                ->label('Quality Score')
                                ->content(fn (?SeoPage $record) => $record?->quality_score ? "{$record->quality_score}/100" : 'Belum dihitung'),
                            Forms\Components\Placeholder::make('quality_details_display')
                                ->label('Detail Kualitas')
                                ->content(function (?SeoPage $record) {
                                    if (! $record?->quality_details) {
                                        return 'Belum dihitung. Gunakan action "Hitung Quality Score".';
                                    }
                                    $lines = [];
                                    foreach ($record->quality_details as $key => $value) {
                                        $label = str_replace('_', ' ', ucfirst($key));
                                        $lines[] = "{$label}: {$value}/5";
                                    }

                                    return implode(' | ', $lines);
                                })
                                ->columnSpanFull(),
                            Forms\Components\Placeholder::make('publishable_display')
                                ->label('Status Publish')
                                ->content(fn (?SeoPage $record) => $record?->isPublishable() ? '✅ Layak Publish' : '❌ Belum Layak Publish')
                                ->columnSpanFull(),
                        ])->columns(2),
                ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('page_type')
                    ->label('Tipe')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('buyer_type')
                    ->label('Buyer')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('search_intent')
                    ->label('Intent')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'bofu' => 'success',
                        'mofu' => 'warning',
                        'tofu' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('quality_score')
                    ->label('Quality')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($record) => $record->status_color)
                    ->formatStateUsing(fn ($record) => $record->status_label)
                    ->sortable(),
                Tables\Columns\TextColumn::make('location_name')
                    ->label('Lokasi')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('priority_score')
                    ->label('Priority')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('priority_score', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('page_type')
                    ->label('Tipe')
                    ->multiple()
                    ->options([
                        'pillar' => 'Pillar',
                        'buyer' => 'Buyer',
                        'project' => 'Project',
                        'usecase' => 'Use Case',
                        'product_landing' => 'Product Landing',
                        'location' => 'Location',
                        'wholesale' => 'Wholesale',
                        'procurement' => 'Procurement',
                        'pricing' => 'Pricing',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->options([
                        'idea' => 'Ide',
                        'draft' => 'Draft',
                        'qa' => 'QA',
                        'needs_review' => 'Perlu Review',
                        'ready' => 'Siap Publish',
                        'published' => 'Published',
                    ]),
                Tables\Filters\SelectFilter::make('buyer_type')
                    ->options([
                        'kontraktor' => 'Kontraktor',
                        'developer' => 'Developer',
                        'pemborong' => 'Pemborong',
                        'arsitek' => 'Arsitek',
                        'procurement' => 'Procurement',
                    ]),
                Tables\Filters\SelectFilter::make('search_intent')
                    ->options([
                        'tofu' => 'TOFU',
                        'mofu' => 'MOFU',
                        'bofu' => 'BOFU',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('calculate_quality')
                    ->label('Hitung Quality')
                    ->icon('heroicon-o-shield-check')
                    ->action(function (SeoPage $record) {
                        $scorer = new SeoQualityScorer;
                        $result = $scorer->scoreAndSave($record);

                        Notification::make()
                            ->title("Quality Score: {$result->quality_score}/100")
                            ->body($result->isPublishable() ? '✅ Layak publish' : '❌ Belum layak publish')
                            ->success()
                            ->send();
                    })
                    ->color('info'),
                Tables\Actions\Action::make('check_duplication')
                    ->label('Cek Duplikasi')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (SeoPage $record) {
                        $checker = new SeoDuplicationChecker;
                        $result = $checker->check($record, $record->id);

                        Notification::make()
                            ->title($result['is_unique'] ? '✅ Unik' : '⚠️ Ada Kemiripan')
                            ->body($result['recommendation'])
                            ->duration(10000)
                            ->send();
                    })
                    ->color('warning'),
                Tables\Actions\Action::make('publish')
                    ->label('Publish')
                    ->icon('heroicon-o-globe-alt')
                    ->action(function (SeoPage $record) {
                        if (! $record->isPublishable()) {
                            Notification::make()
                                ->title('❌ Tidak bisa dipublish')
                                ->body('Quality score belum memenuhi syarat. Hitung quality score terlebih dahulu.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $record->update([
                            'status' => 'published',
                            'published_at' => now(),
                        ]);

                        Notification::make()
                            ->title('✅ Halaman dipublish!')
                            ->body("URL: /{$record->slug}")
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Publish Halaman SEO?')
                    ->modalDescription('Halaman akan aktif dan dapat diakses publik.')
                    ->color('success')
                    ->visible(fn (SeoPage $record) => $record->status !== 'published'),
                Tables\Actions\Action::make('unpublish')
                    ->label('Unpublish')
                    ->icon('heroicon-o-eye-slash')
                    ->action(function (SeoPage $record) {
                        $record->update(['status' => 'draft', 'published_at' => null]);
                        Notification::make()
                            ->title('Halaman di-unpublish')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->color('danger')
                    ->visible(fn (SeoPage $record) => $record->status === 'published'),
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn (SeoPage $record) => "/{$record->slug}")
                    ->openUrlInNewTab()
                    ->visible(fn (SeoPage $record) => in_array($record->status, ['ready', 'published', 'monitoring'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('calculate_quality_bulk')
                        ->label('Hitung Quality Score')
                        ->icon('heroicon-o-shield-check')
                        ->action(function ($records) {
                            $scorer = new SeoQualityScorer;
                            foreach ($records as $record) {
                                $scorer->scoreAndSave($record);
                            }
                            Notification::make()
                                ->title('Quality score dihitung untuk '.count($records).' halaman')
                                ->success()
                                ->send();
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
            'index' => Pages\ListSeoPages::route('/'),
            'create' => Pages\CreateSeoPage::route('/create'),
            'edit' => Pages\EditSeoPage::route('/{record}/edit'),
        ];
    }
}
