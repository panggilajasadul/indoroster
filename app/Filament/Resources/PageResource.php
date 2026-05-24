<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Konten';
    protected static ?string $navigationLabel = 'Halaman';
    protected static ?string $modelLabel = 'Halaman';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul Halaman')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                    Forms\Components\Toggle::make('is_active')->label('Aktif')->default(true),
                ])->columns(3),

            Forms\Components\Section::make('Page Builder')
                ->description('Susun halaman Anda dengan blok-blok di bawah ini.')
                ->schema([
                    Forms\Components\Builder::make('content')
                        ->label('Konten Halaman')
                ->blocks([
                            Forms\Components\Builder\Block::make('hero')
                                ->label('Hero Banner Slider')
                                ->icon('heroicon-o-presentation-chart-bar')
                                ->schema([
                                    Forms\Components\Repeater::make('banners')
                                        ->schema([
                                            Forms\Components\FileUpload::make('image_upload')
                                                ->label('Upload Media Banner (Foto/Video)')
                                                ->acceptedFileTypes(['image/*', 'video/mp4', 'video/webm'])
                                                ->directory('pages/hero'),
                                            Forms\Components\TextInput::make('image')
                                                ->label('Atau URL Media Eksternal (Foto/Video)')
                                                ->placeholder('https://...')
                                                ->helperText('Jika Anda mengupload file di atas, kolom URL ini akan diabaikan.'),
                                            Forms\Components\TextInput::make('image_link')
                                                ->label('Link Gambar (Jika di-klik)')
                                                ->placeholder('https://... (Opsional)')
                                                ->helperText('Isi ini jika ingin seluruh gambar banner bisa diklik.'),
                                            Forms\Components\TextInput::make('top_text')->label('Teks Teratas (Dengan garis samping)'),
                                            Forms\Components\TextInput::make('badge')->label('Teks Badge (Pill Orange Atas)'),
                                            Forms\Components\Select::make('font_family')
                                                ->label('Pilihan Font Judul')
                                                ->options([
                                                    'font-display' => 'Display (Modern)',
                                                    'font-sans' => 'Sans-Serif (Standard)',
                                                    'font-serif' => 'Serif (Klasik/Elegan)',
                                                    'font-mono' => 'Monospace',
                                                ])
                                                ->default('font-display'),
                                            Forms\Components\TextInput::make('title')->label('Judul'),
                                            Forms\Components\TextInput::make('subtitle')->label('Sub-judul'),
                                            Forms\Components\TextInput::make('button_text')->label('Teks Tombol 1'),
                                            Forms\Components\TextInput::make('button_url')->label('Link Tombol 1'),
                                            Forms\Components\TextInput::make('button_2_text')->label('Teks Tombol 2 (Opsional)'),
                                            Forms\Components\TextInput::make('button_2_url')->label('Link Tombol 2 (Opsional)'),
                                            Forms\Components\Select::make('alignment')
                                                ->label('Perataan Konten')
                                                ->options([
                                                    'left' => 'Rata Kiri',
                                                    'center' => 'Rata Tengah',
                                                    'right' => 'Rata Kanan',
                                                ])
                                                ->default('left')
                                                ->required(),
                                            Forms\Components\ColorPicker::make('overlay_color')
                                                ->label('Warna Overlay')
                                                ->default('#020617'),
                                            Forms\Components\Select::make('overlay_opacity')
                                                ->label('Kegelapan Overlay')
                                                ->options([
                                                    '0' => 'Tanpa Overlay (0%)',
                                                    '10' => '10%',
                                                    '25' => '25%',
                                                    '40' => '40%',
                                                    '50' => '50%',
                                                    '60' => '60%',
                                                    '75' => '75%',
                                                    '85' => '85%',
                                                    '90' => '90%',
                                                    '95' => '95%',
                                                ])
                                                ->default('75')
                                                ->required(),
                                            Forms\Components\Select::make('image_opacity')
                                                ->label('Kecerahan Gambar (Transparansi)')
                                                ->options([
                                                    '10' => '10%',
                                                    '20' => '20%',
                                                    '30' => '30%',
                                                    '45' => '45%',
                                                    '60' => '60%',
                                                    '80' => '80%',
                                                    '100' => '100%',
                                                ])
                                                ->default('45')
                                                ->required(),
                                            Forms\Components\Select::make('blur_level')
                                                ->label('Tingkat Blur Latar')
                                                ->options([
                                                    'none' => 'Tanpa Blur',
                                                    'sm' => 'Halus (sm)',
                                                    'md' => 'Sedang (md)',
                                                    'lg' => 'Tebal (lg)',
                                                    'xl' => 'Sangat Tebal (xl)',
                                                ])
                                                ->default('none')
                                                ->required(),
                                            Forms\Components\Select::make('image_fit')
                                                ->label('Ukuran Latar (Fit)')
                                                ->options([
                                                    'object-cover' => 'Penuhi Layar (Bisa Terpotong)',
                                                    'object-contain' => 'Tampilkan Utuh (Ada Ruang Kosong)',
                                                ])
                                                ->default('object-cover')
                                                ->required(),
                                        ])
                                        ->columns(2),
                                    Forms\Components\TextInput::make('slider_duration')
                                        ->label('Durasi Slider (ms)')
                                        ->numeric()
                                        ->default(5000),
                                ]),

                            Forms\Components\Builder\Block::make('ticker')
                                ->label('Social Proof Ticker')
                                ->icon('heroicon-o-arrows-right-left')
                                ->schema([
                                    Forms\Components\Repeater::make('items')
                                        ->schema([
                                            Forms\Components\TextInput::make('text')->required(),
                                        ])
                                        ->default([
                                            ['text' => '5000+ Proyek Selesai'],
                                            ['text' => 'Pabrik Tangan Pertama'],
                                            ['text' => 'Garansi Pecah Ganti Baru'],
                                            ['text' => 'Pengiriman Seluruh Indonesia'],
                                        ]),
                                ]),

                            Forms\Components\Builder\Block::make('visual_showcase')
                                ->label('Visual Showcase (Auto-Slider)')
                                ->icon('heroicon-o-photo')
                                ->schema([
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi'),
                                    Forms\Components\Select::make('speed')
                                        ->label('Kecepatan Slider')
                                        ->options([
                                            'animate-marquee' => 'Cepat (Normal)',
                                            'animate-marquee-slow' => 'Lambat (Santai)',
                                        ])
                                        ->default('animate-marquee'),
                                    Forms\Components\FileUpload::make('images_upload')
                                        ->label('Upload Koleksi Media (Foto/Video)')
                                        ->acceptedFileTypes(['image/*', 'video/mp4', 'video/webm'])
                                        ->multiple()
                                        ->panelLayout('grid')
                                        ->imagePreviewHeight('150')
                                        ->directory('pages/showcase'),
                                    Forms\Components\Repeater::make('images')
                                        ->label('Atau Tambahkan URL Media Eksternal')
                                        ->simple(
                                            Forms\Components\TextInput::make('url')
                                                ->placeholder('Link foto...')
                                        )
                                        ->helperText('Tempelkan link foto satu per satu.'),
                                ]),

                            Forms\Components\Builder\Block::make('stats_counter')
                                ->label('📊 Angka Statistik')
                                ->icon('heroicon-o-chart-bar')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge (Opsional)'),
                                    Forms\Components\TextInput::make('title')->label('Judul'),
                                    Forms\Components\Textarea::make('description')->label('Deskripsi'),
                                    static::bgThemeSelect(),
                                    Forms\Components\Repeater::make('stats')
                                        ->label('Statistik')
                                        ->schema([
                                            Forms\Components\TextInput::make('value')
                                                ->label('Angka')
                                                ->placeholder('5000+')
                                                ->required(),
                                            Forms\Components\TextInput::make('label')
                                                ->label('Label')
                                                ->placeholder('Proyek Selesai')
                                                ->required(),
                                            Forms\Components\TextInput::make('description')
                                                ->label('Keterangan Singkat (Opsional)'),
                                        ])
                                        ->columns(3)
                                        ->default([
                                            ['value' => '5000+', 'label' => 'Proyek Selesai'],
                                            ['value' => '10+', 'label' => 'Tahun Pengalaman'],
                                            ['value' => '150+', 'label' => 'Motif Tersedia'],
                                            ['value' => '100%', 'label' => 'Pabrik Langsung'],
                                        ]),
                                ]),

                            Forms\Components\Builder\Block::make('strength_test')
                                ->label('Seksi Uji Kekuatan')
                                ->icon('heroicon-o-shield-check')
                                ->schema([
                                    Forms\Components\TextInput::make('title')->label('Judul'),
                                    Forms\Components\Textarea::make('description')->label('Deskripsi'),
                                    Forms\Components\FileUpload::make('video_upload')
                                        ->label('Upload Media (Foto/Video)')
                                        ->acceptedFileTypes(['image/*', 'video/mp4', 'video/webm'])
                                        ->directory('pages/strength_test'),
                                    Forms\Components\TextInput::make('video_url')->label('Atau URL Media Eksternal (Foto/Video)'),
                                    static::bgThemeSelect('white'),
                                    Forms\Components\Repeater::make('features')
                                        ->schema([
                                            Forms\Components\TextInput::make('title')->label('Fitur'),
                                            Forms\Components\TextInput::make('desc')->label('Keterangan Singkat'),
                                        ])
                                        ->columns(2),
                                ]),

                            Forms\Components\Builder\Block::make('featured_products')
                                ->label('Produk Unggulan')
                                ->icon('heroicon-o-shopping-bag')
                                ->schema([
                                    Forms\Components\TextInput::make('title')->label('Judul'),
                                    Forms\Components\Select::make('categories')
                                        ->label('Pilih Kategori')
                                        ->multiple()
                                        ->options(\App\Models\Category::pluck('name', 'id')),
                                    Forms\Components\TextInput::make('limit')
                                        ->label('Jumlah Produk')
                                        ->numeric()
                                        ->default(4),
                                    static::bgThemeSelect('white'),
                                ]),

                            Forms\Components\Builder\Block::make('viral_products')
                                ->label('Produk Viral & Terlaris (Top Sold)')
                                ->icon('heroicon-o-fire')
                                ->schema([
                                    Forms\Components\TextInput::make('title')
                                        ->label('Judul Seksi')
                                        ->default('Produk Terlaris & Viral 🔥'),
                                    Forms\Components\TextInput::make('subtitle')
                                        ->label('Sub-judul')
                                        ->default('Koleksi roster terpopuler dengan penjualan dan ulasan terbanyak'),
                                    Forms\Components\TextInput::make('limit')
                                        ->label('Jumlah Produk')
                                        ->numeric()
                                        ->default(6),
                                    static::bgThemeSelect('white'),
                                ]),

                            Forms\Components\Builder\Block::make('why_us')
                                ->label('Kenapa Memilih Kami')
                                ->icon('heroicon-o-hand-thumb-up')
                                ->schema([
                                    Forms\Components\TextInput::make('title')->label('Judul'),
                                    Forms\Components\Textarea::make('description')->label('Deskripsi'),
                                    static::bgThemeSelect('dark'),
                                    Forms\Components\Repeater::make('items')
                                        ->schema([
                                            Forms\Components\TextInput::make('title')->label('Judul Poin'),
                                            Forms\Components\Textarea::make('content')->label('Isi Poin'),
                                        ])
                                        ->columns(2),
                                    Forms\Components\Repeater::make('videos')
                                        ->schema([
                                            Forms\Components\FileUpload::make('video_upload')
                                                ->label('Upload Media (Foto/Video)')
                                                ->acceptedFileTypes(['image/*', 'video/mp4', 'video/webm'])
                                                ->directory('pages/why_us'),
                                            Forms\Components\TextInput::make('url')->label('Atau URL Media Eksternal (Foto/Video)'),
                                        ]),
                                ]),

                            Forms\Components\Builder\Block::make('shipping_info')
                                ->label('Jangkauan Pengiriman')
                                ->icon('heroicon-o-truck')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Teks Kecil (Badge)'),
                                    Forms\Components\TextInput::make('title')->label('Judul'),
                                    Forms\Components\Textarea::make('content')->label('Isi Konten'),
                                    Forms\Components\FileUpload::make('video_upload')
                                        ->label('Upload Media (Foto/Video)')
                                        ->acceptedFileTypes(['image/*', 'video/mp4', 'video/webm'])
                                        ->directory('pages/shipping'),
                                    Forms\Components\TextInput::make('video_url')->label('Atau URL Media Eksternal (Foto/Video)'),
                                    Forms\Components\TextInput::make('button_text')->label('Teks Tombol'),
                                    Forms\Components\TextInput::make('button_url')->label('Link Tombol'),
                                    static::bgThemeSelect('white'),
                                ]),

                            Forms\Components\Builder\Block::make('social_review')
                                ->label('TikTok/Social Review')
                                ->icon('heroicon-o-device-phone-mobile')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Teks Kecil (Badge)'),
                                    Forms\Components\TextInput::make('title')->label('Judul'),
                                    Forms\Components\Textarea::make('description')->label('Deskripsi'),
                                    Forms\Components\FileUpload::make('video_upload')
                                        ->label('Upload Media (Foto/Video)')
                                        ->acceptedFileTypes(['image/*', 'video/mp4', 'video/webm'])
                                        ->directory('pages/social'),
                                    Forms\Components\TextInput::make('video_url')->label('Atau URL Media Eksternal (Foto/Video)'),
                                    Forms\Components\TextInput::make('creators_count')->label('Jumlah Kreator'),
                                    static::bgThemeSelect('dark'),
                                ]),

                            Forms\Components\Builder\Block::make('testimonials')
                                ->label('Testimonial')
                                ->icon('heroicon-o-chat-bubble-left-right')
                                ->schema([
                                    Forms\Components\TextInput::make('title')->label('Judul'),
                                    Forms\Components\Select::make('mode')
                                        ->label('Mode Tampil')
                                        ->options([
                                            'latest' => 'Ambil Otomatis Terbaru',
                                            'manual' => 'Pilih Manual',
                                        ])
                                        ->default('latest'),
                                    static::bgThemeSelect('white'),
                                ]),

                            Forms\Components\Builder\Block::make('gallery_grid')
                                ->label('Gallery Grid (Transformation)')
                                ->icon('heroicon-o-squares-2x2')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge'),
                                    Forms\Components\TextInput::make('title')->label('Judul'),
                                    Forms\Components\Textarea::make('description')->label('Deskripsi'),
                                    static::bgThemeSelect('dark'),
                                    Forms\Components\Repeater::make('items')
                                        ->schema([
                                            Forms\Components\FileUpload::make('image_upload')
                                                ->label('Upload Media Proyek (Foto/Video)')
                                                ->acceptedFileTypes(['image/*', 'video/mp4', 'video/webm'])
                                                ->directory('pages/gallery'),
                                            Forms\Components\TextInput::make('image')
                                                ->label('Atau URL Media Eksternal (Foto/Video)')
                                                ->placeholder('Link foto...'),
                                            Forms\Components\TextInput::make('title')->label('Judul Proyek'),
                                        ])
                                        ->columns(2),
                                ]),

                            Forms\Components\Builder\Block::make('ugc_videos')
                                ->label('UGC Video Grid')
                                ->icon('heroicon-o-video-camera')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge'),
                                    Forms\Components\TextInput::make('title')->label('Judul'),
                                    Forms\Components\Textarea::make('description')->label('Deskripsi'),
                                    static::bgThemeSelect('white'),
                                    Forms\Components\Repeater::make('videos')
                                        ->schema([
                                            Forms\Components\FileUpload::make('video_upload')
                                                ->label('Upload Media (Foto/Video)')
                                                ->acceptedFileTypes(['image/*', 'video/mp4', 'video/webm'])
                                                ->directory('pages/ugc'),
                                            Forms\Components\TextInput::make('url')->label('Atau URL Media Eksternal (Foto/Video)'),
                                        ]),
                                ]),

                            Forms\Components\Builder\Block::make('gallery_collection')
                                ->label('Koleksi Galeri (Ber-Kategori)')
                                ->icon('heroicon-o-rectangle-group')
                                ->schema([
                                    Forms\Components\Placeholder::make('hint')
                                        ->label('')
                                        ->content('Tips: Foto-foto dalam koleksi ini dikelola secara terpusat melalui menu "Galeri" di sidebar kiri.'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi')->default('Inspirasi Proyek Kami'),
                                    Forms\Components\Textarea::make('description')->label('Deskripsi')->default('Jelajahi koleksi mahakarya pemasangan roster beton minimalis yang telah menghiasi berbagai hunian.'),
                                ]),

                            Forms\Components\Builder\Block::make('faq')
                                ->label('📋 FAQ (Accordion)')
                                ->icon('heroicon-o-question-mark-circle')
                                ->schema([
                                    Forms\Components\Placeholder::make('hint')
                                        ->label('')
                                        ->content('Tips: Data FAQ dikelola melalui menu "FAQ" di sidebar kiri. Blok ini akan mengambil data FAQ secara otomatis.'),
                                    Forms\Components\TextInput::make('title')
                                        ->label('Judul Seksi')
                                        ->default('Pertanyaan yang Sering Diajukan'),
                                    Forms\Components\Textarea::make('description')->label('Deskripsi'),
                                    Forms\Components\TextInput::make('limit')
                                        ->label('Jumlah FAQ Ditampilkan')
                                        ->numeric()
                                        ->default(10),
                                    static::bgThemeSelect('slate'),
                                ]),

                            Forms\Components\Builder\Block::make('promo_banner')
                                ->label('🔥 Banner Promo / Urgency')
                                ->icon('heroicon-o-fire')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')
                                        ->label('Badge Promo')
                                        ->placeholder('Promo Akhir Bulan!'),
                                    Forms\Components\TextInput::make('title')
                                        ->label('Judul Promo')
                                        ->placeholder('Diskon 15% untuk Semua Produk'),
                                    Forms\Components\Textarea::make('description')
                                        ->label('Deskripsi Promo'),
                                    Forms\Components\TextInput::make('button_text')
                                        ->label('Teks Tombol')
                                        ->default('Hubungi Kami Sekarang'),
                                    Forms\Components\TextInput::make('button_url')
                                        ->label('Link Tombol (Kosongkan = WA Otomatis)'),
                                    Forms\Components\DateTimePicker::make('end_date')
                                        ->label('Batas Waktu Promo (Countdown)')
                                        ->helperText('Kosongkan jika tidak pakai countdown timer.'),
                                    static::bgThemeSelect('accent'),
                                ]),

                            Forms\Components\Builder\Block::make('cta')
                                ->label('Final Call to Action')
                                ->icon('heroicon-o-cursor-arrow-rays')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge'),
                                    Forms\Components\TextInput::make('title')->label('Judul Utama'),
                                    Forms\Components\TextInput::make('button_text')->label('Teks Tombol WA'),
                                    Forms\Components\TextInput::make('button_url')->label('Link Tombol (Kosongkan untuk WA Otomatis)'),
                                    static::bgThemeSelect('white'),
                                ]),

                            Forms\Components\Builder\Block::make('rich_text')
                                ->label('Teks Bebas (Rich Text)')
                                ->icon('heroicon-o-pencil-square')
                                ->schema([
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi (Opsional)'),
                                    Forms\Components\RichEditor::make('content')
                                        ->label('Konten')
                                        ->required()
                                        ->columnSpanFull(),
                                    static::bgThemeSelect('white'),
                                ]),
                        ])
                        ->columnSpanFull()
                        ->collapsed(),
                ]),

            Forms\Components\Section::make('SEO')
                ->schema([
                    Forms\Components\TextInput::make('meta_title')->label('Meta Title'),
                    Forms\Components\Textarea::make('meta_description')->label('Meta Description')->rows(2),
                ])->columns(2)->collapsed(),
        ]);
    }

    /**
     * Reusable background theme select for Page Builder blocks.
     */
    protected static function bgThemeSelect(string $default = 'white'): Forms\Components\Select
    {
        return Forms\Components\Select::make('bg_theme')
            ->label('Tema Latar')
            ->options([
                'white'    => '⬜ Putih',
                'slate'    => '🔲 Abu-abu Muda',
                'dark'     => '⬛ Gelap',
                'accent'   => '🟧 Aksen (Orange)',
                'gradient' => '🌈 Gradien',
            ])
            ->default($default)
            ->helperText('Pilih warna latar belakang untuk seksi ini.');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')->label('URL'),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->label('Terakhir Diubah')->dateTime('d M Y H:i')->sortable(),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
