<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
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
                    Forms\Components\TextInput::make('slug')
                        ->label('URL Slug Halaman')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->helperText(fn (?Page $record) => $record && $record->slug ? new HtmlString('<a href="'.static::getPublicPageUrl($record).'" target="_blank" class="text-terra-600 dark:text-terra-400 font-bold underline inline-flex items-center gap-1 mt-1">🔗 Klik Di Sini untuk Buka Halaman: '.static::getPublicPageUrl($record).'</a>') : 'Alamat link unik (contoh: katalog-produk-roster-jabodetabek)'),
                    Forms\Components\Toggle::make('is_active')->label('Aktif (Publikasikan)')->default(true),
                ])->columns(3),

            Forms\Components\Section::make('Page Builder')
                ->description('Susun halaman Anda dengan blok-blok di bawah ini.')
                ->schema([
                    Forms\Components\Builder::make('content')
                        ->label('Konten Halaman')
                        ->blockPickerColumns([
                            'default' => 1,
                            'sm' => 2,
                            'lg' => 3,
                        ])
                        ->blockPickerWidth('4xl')
                        ->addActionLabel('Tambahkan Blok Halaman (+)')
                        ->collapsible()
                        ->collapsed()
                        ->cloneable()
                        ->blocks([
                            Forms\Components\Builder\Block::make('horizon_banner_slider')
                                ->label('[1. Hero] 🎠 Horizon Multi-Card Banner Slider (Galeri / Video / Promo)')
                                ->icon('heroicon-o-view-columns')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Teks Badge Atas (Opsional)'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi (Opsional)'),
                                    Forms\Components\Textarea::make('subtitle')->label('Sub-judul / Keterangan (Opsional)')->rows(2),
                                    static::bgThemeSelect('white'),
                                    Forms\Components\Grid::make(3)->schema([
                                        Forms\Components\Toggle::make('autoplay')->label('Autoplay Slider Otomatis')->default(true),
                                        Forms\Components\TextInput::make('duration')->label('Durasi Pergantian (ms)')->numeric()->default(4500),
                                        Forms\Components\Select::make('aspect_ratio')
                                            ->label('Rasio Kartu Slider')
                                            ->options([
                                                'aspect-[21/9] sm:aspect-[24/9] md:aspect-[3/1]' => 'Landscape Toko (3:1 / Sangat Lebar)',
                                                'aspect-[16/9]' => 'Standar Layar Lebar (16:9)',
                                                'aspect-[21/9]' => 'Cinematic Panorama (21:9)',
                                            ])
                                            ->default('aspect-[21/9] sm:aspect-[24/9] md:aspect-[3/1]'),
                                    ]),
                                    Forms\Components\Repeater::make('items')
                                        ->label('Koleksi Slide Banner')
                                        ->schema([
                                            Forms\Components\Select::make('type')
                                                ->label('Sumber Konten Slide')
                                                ->options([
                                                    'custom' => '🎨 Custom Banner (Upload Foto/Video & Tautan Bebas)',
                                                    'gallery' => '🏛️ Galeri Proyek (Tarik dari Database Galeri)',
                                                    'video_inspiration' => '🎬 Video Inspirasi (Tarik dari Database Video)',
                                                ])
                                                ->default('custom')
                                                ->live()
                                                ->required(),

                                            // Mode Custom
                                            Forms\Components\FileUpload::make('image_upload')
                                                ->label('Upload Banner Foto/Video')
                                                ->acceptedFileTypes(['image/*', 'video/*'])
                                                ->maxSize(102400)
                                                ->imagePreviewHeight('100')
                                                ->directory('pages/banners')
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'custom')
                                                ->live(),
                                            Forms\Components\TextInput::make('image_url')
                                                ->label('Atau URL Foto/Video Eksternal')
                                                ->placeholder('https://...')
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'custom')
                                                ->live(),
                                            static::mediaPreview('image_upload', 'image_url')
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'custom'),
                                            Forms\Components\TextInput::make('badge')
                                                ->label('Badge Promo (Contoh: Diskon 80%, Sat Set Ongkir Hemat!)')
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'custom'),
                                            Forms\Components\TextInput::make('title')
                                                ->label('Judul Banner')
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'custom'),
                                            Forms\Components\TextInput::make('subtitle')
                                                ->label('Sub-judul Banner')
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'custom'),
                                            Forms\Components\TextInput::make('link')
                                                ->label('Link Tujuan Saat Diklik')
                                                ->placeholder('Contoh: /katalog atau /produk/roster-petir')
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'custom'),
                                            Forms\Components\TextInput::make('button_text')
                                                ->label('Teks Tombol CTA (Opsional)')
                                                ->placeholder('Contoh: Beli Sekarang / Lihat Promo')
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'custom'),

                                            // Mode Gallery
                                            Forms\Components\Select::make('gallery_id')
                                                ->label('Pilih Galeri Proyek')
                                                ->options(fn () => Gallery::where('category', '!=', 'video-inspirasi')->pluck('title', 'id'))
                                                ->searchable()
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'gallery'),

                                            // Mode Video
                                            Forms\Components\Select::make('video_id')
                                                ->label('Pilih Video Inspirasi')
                                                ->options(fn () => Gallery::where('category', 'video-inspirasi')->pluck('title', 'id'))
                                                ->searchable()
                                                ->visible(fn (Forms\Get $get) => $get('type') === 'video_inspiration'),

                                            // Override Text (Gallery/Video)
                                            Forms\Components\TextInput::make('custom_badge')
                                                ->label('Custom Badge (Opsional)')
                                                ->visible(fn (Forms\Get $get) => in_array($get('type'), ['gallery', 'video_inspiration'])),
                                            Forms\Components\TextInput::make('custom_title')
                                                ->label('Custom Judul (Opsional - Timpa judul asli)')
                                                ->visible(fn (Forms\Get $get) => in_array($get('type'), ['gallery', 'video_inspiration'])),
                                            Forms\Components\TextInput::make('custom_subtitle')
                                                ->label('Custom Sub-judul (Opsional)')
                                                ->visible(fn (Forms\Get $get) => in_array($get('type'), ['gallery', 'video_inspiration'])),
                                            Forms\Components\TextInput::make('custom_link')
                                                ->label('Custom URL Link (Opsional)')
                                                ->visible(fn (Forms\Get $get) => in_array($get('type'), ['gallery', 'video_inspiration'])),
                                            Forms\Components\TextInput::make('custom_button_text')
                                                ->label('Custom Tombol (Opsional)')
                                                ->visible(fn (Forms\Get $get) => in_array($get('type'), ['gallery', 'video_inspiration'])),

                                            // Overlay Controls
                                            Forms\Components\ColorPicker::make('overlay_color')->label('Warna Overlay Gelap')->default('#0f172a'),
                                            Forms\Components\Select::make('overlay_opacity')
                                                ->label('Kegelapan Overlay')
                                                ->options([
                                                    '0' => '0% (Tanpa Overlay)',
                                                    '20' => '20% (Sangat Halus)',
                                                    '35' => '35% (Sedang)',
                                                    '50' => '50% (Standar)',
                                                    '70' => '70% (Tegas)',
                                                ])
                                                ->default('35'),
                                        ])
                                        ->collapsible()
                                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? ($state['custom_title'] ?? 'Slide Banner')),
                                ]),

                            Forms\Components\Builder\Block::make('category_showcase')
                                ->label('[2. Produk] 🏷️ Kategori Produk & Ikon Estetik (Category Grid / Carousel)')
                                ->icon('heroicon-o-tag')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Teks Badge Atas')->default('Katalog Produk'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi (Opsional)')->default('Jelajahi Berdasarkan Kategori'),
                                    Forms\Components\Textarea::make('subtitle')->label('Sub-judul (Opsional)')->rows(2),
                                    static::bgThemeSelect('white'),
                                    Forms\Components\Select::make('source')
                                        ->label('Sumber Kategori')
                                        ->options([
                                            'auto' => '⚡ Otomatis (Tarik semua kategori aktif dari Database)',
                                            'custom' => '🛠️ Kustom (Atur manual nama, ikon, dan tautan)',
                                        ])
                                        ->default('auto')
                                        ->live(),
                                    Forms\Components\Select::make('icon_shape')
                                        ->label('Bentuk Ikon/Foto Kategori')
                                        ->options([
                                            'circle' => 'Bulat Elegan (Circular Icon - Persis Foto)',
                                            'squircle' => 'Rounded 3D (Squircle Modern)',
                                            'card' => 'Kotak Bersudut (Card Minimalist)',
                                        ])
                                        ->default('circle'),
                                    Forms\Components\Toggle::make('show_product_count')->label('Tampilkan Jumlah Produk di Tiap Kategori')->default(false),
                                    Forms\Components\Repeater::make('items')
                                        ->label('Daftar Kategori Kustom')
                                        ->visible(fn (Forms\Get $get) => $get('source') === 'custom')
                                        ->schema([
                                            Forms\Components\TextInput::make('name')->label('Nama Kategori')->required(),
                                            Forms\Components\FileUpload::make('image_upload')
                                                ->label('Upload Ikon/Foto Kategori')
                                                ->image()
                                                ->directory('pages/categories')
                                                ->live(),
                                            Forms\Components\TextInput::make('image_url')->label('Atau URL Ikon/Foto')->live(),
                                            static::mediaPreview('image_upload', 'image_url'),
                                            Forms\Components\TextInput::make('link')->label('Link Tujuan')->placeholder('/katalog/roster-beton'),
                                            Forms\Components\TextInput::make('badge')->label('Badge / Keterangan Singkat (Opsional)'),
                                        ])
                                        ->columns(2),
                                ]),

                            Forms\Components\Builder\Block::make('detail_distance_showcase')
                                ->label('[4. Galeri] 🔍 Inspektur Detail Tekstur vs Jarak Jauh (Close-up & Far Facade)')
                                ->icon('heroicon-o-magnifying-glass-plus')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge Seksi')->default('🔍 Visual Inspector 360°'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi')->default('Penasaran Detail Tekstur vs Tampak Jarak Jauh?'),
                                    Forms\Components\Textarea::make('subtitle')->label('Sub-judul / Edukasi')->rows(2)->default('Bandingkan kualitas detail presisi cetakan jarak dekat dengan keanggunan fasad arsitektural saat terpasang dari kejauhan.'),
                                    static::bgThemeSelect('white'),
                                    Forms\Components\Repeater::make('items')
                                        ->label('Koleksi Perbandingan Produk / Proyek')
                                        ->schema([
                                            Forms\Components\TextInput::make('title')->label('Nama Motif / Produk / Proyek')->required()->default('Roster Beton Minimalis Motif Petir'),
                                            Forms\Components\Section::make('Foto 1: Jarak Dekat (Close-up Texture & Precision)')
                                                ->schema([
                                                    Forms\Components\FileUpload::make('close_up_upload')->label('Upload Foto Jarak Dekat (Detail Tekstur)')->image()->directory('pages/inspector')->live(),
                                                    Forms\Components\TextInput::make('close_up_url')->label('Atau URL Foto Jarak Dekat')->live(),
                                                    static::mediaPreview('close_up_upload', 'close_up_url'),
                                                    Forms\Components\Textarea::make('close_desc')->label('Keterangan Keunggulan Tekstur Jarak Dekat')->rows(2)->default('Detail pori padat, sudut cetak siku 45° presisi, dan permukaan halus tanpa retak rambut.'),
                                                ]),
                                            Forms\Components\Section::make('Foto 2: Jarak Jauh (Far View / Tampak Fasad Penuh)')
                                                ->schema([
                                                    Forms\Components\FileUpload::make('far_view_upload')->label('Upload Foto Jarak Jauh (Tampak Fasad)')->image()->directory('pages/inspector')->live(),
                                                    Forms\Components\TextInput::make('far_view_url')->label('Atau URL Foto Jarak Jauh')->live(),
                                                    static::mediaPreview('far_view_upload', 'far_view_url'),
                                                    Forms\Components\Textarea::make('far_desc')->label('Keterangan Keanggunan Jarak Jauh')->rows(2)->default('Tampilan megah dan estetik pada fasad bangunan dengan sirkulasi udara alami maksimal.'),
                                                ]),
                                            Forms\Components\TextInput::make('link')->label('Link Menuju Produk / Katalog')->placeholder('/produk/roster-beton-motif-petir'),
                                            Forms\Components\TextInput::make('button_text')->label('Teks Tombol CTA')->default('Lihat Detail Produk Ini'),
                                        ])
                                        ->collapsible()
                                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Item Perbandingan Detail'),
                                ]),

                            Forms\Components\Builder\Block::make('hero')
                                ->label('[1. Hero] 🌟 Hero Banner Slider Modern')
                                ->icon('heroicon-o-presentation-chart-bar')
                                ->schema([
                                    Forms\Components\Repeater::make('banners')
                                        ->schema([
                                            Forms\Components\FileUpload::make('image_upload')
                                                ->label('Upload Media Banner (Foto/Video)')
                                                ->acceptedFileTypes(['image/*', 'video/*'])
                                                ->maxSize(102400)
                                                ->imagePreviewHeight('120')
                                                ->openable()
                                                ->downloadable()
                                                ->live()
                                                ->directory('pages/hero'),
                                            Forms\Components\TextInput::make('image')
                                                ->label('Atau URL Media Eksternal (Foto/Video)')
                                                ->placeholder('https://...')
                                                ->live()
                                                ->helperText('Jika Anda mengupload file di atas, kolom URL ini akan diabaikan.'),
                                            static::mediaPreview('image_upload', 'image'),
                                            Forms\Components\TextInput::make('image_link')
                                                ->label('Link Gambar (Jika di-klik)')
                                                ->placeholder('https://... (Opsional)')
                                                ->helperText('Isi ini jika ingin seluruh gambar banner bisa diklik.'),
                                            Forms\Components\TextInput::make('top_text')->label('Teks Teratas (Dengan garis samping)'),
                                            Forms\Components\TextInput::make('badge')->label('Teks Badge (Pill Orange Atas)'),
                                            Forms\Components\Select::make('font_family')
                                                ->label('Pilihan Font Judul')
                                                ->options([
                                                    'font-poppins' => 'Poppins (Modern, Bersih & Paling Populer)',
                                                    'font-jost' => 'Jost (Elegan & Geometris / Bauhaus)',
                                                    'font-outfit' => 'Outfit (Display Bold Estetik)',
                                                    'font-urbanist' => 'Urbanist (Minimalis & Modern)',
                                                    'font-plus-jakarta' => 'Plus Jakarta Sans (Harmonis & Tajam)',
                                                    'font-sans' => 'Inter (Sans-Serif Standard)',
                                                    'font-serif' => 'Serif (Klasik Mewah)',
                                                    'font-mono' => 'Monospace (Industrial)',
                                                ])
                                                ->default('font-poppins'),
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
                                ->label('[1. Hero] 📢 Running Ticker Teks Berjalan')
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
                                    static::bgThemeSelect('dark'),
                                ]),

                            Forms\Components\Builder\Block::make('visual_showcase')
                                ->label('[1. Hero] ✨ Visual Showcase 3D Estetika')
                                ->icon('heroicon-o-photo')
                                ->schema([
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi'),
                                    Forms\Components\Select::make('speed')
                                        ->label('Kecepatan Slider')
                                        ->options([
                                            'animate-marquee-slow' => 'Sangat Halus & Santai (70s)',
                                            'animate-marquee' => 'Sedang / Normal (40s)',
                                            'animate-marquee-fast' => 'Cepat (22s)',
                                        ])
                                        ->default('animate-marquee-slow'),
                                    Forms\Components\Toggle::make('pause_on_hover')
                                        ->label('Jeda Saat Kursor Mengarah (Pause on Hover)')
                                        ->default(false)
                                        ->helperText('Jika dinonaktifkan (OFF), galeri foto akan meluncur terus tanpa henti meskipun tersentuh kursor mouse.'),
                                    Forms\Components\FileUpload::make('images_upload')
                                        ->label('Upload Koleksi Media (Foto/Video)')
                                        ->acceptedFileTypes(['image/*', 'video/*'])
                                        ->multiple()
                                        ->reorderable()
                                        ->appendFiles()
                                        ->openable()
                                        ->downloadable()
                                        ->previewable(true)
                                        ->panelLayout('grid')
                                        ->imagePreviewHeight('100')
                                        ->maxFiles(50)
                                        ->maxSize(102400)
                                        ->directory('pages/showcase')
                                        ->helperText('Bisa pilih/upload banyak foto/video sekaligus atau tambah secara bertahap.'),
                                    Forms\Components\Repeater::make('images')
                                        ->label('Atau Tambahkan URL Media Eksternal')
                                        ->simple(
                                            Forms\Components\TextInput::make('url')
                                                ->placeholder('Link foto...')
                                        )
                                        ->helperText('Tempelkan link foto satu per satu.'),
                                    static::bgThemeSelect('white'),
                                ]),

                            Forms\Components\Builder\Block::make('stats_counter')
                                ->label('[3. Pabrik] 📊 Angka & Statistik Pabrik (Stats Counter)')
                                ->icon('heroicon-o-chart-bar')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge (Opsional)')->default('PORTFOLIO & KAPASITAS'),
                                    Forms\Components\TextInput::make('title')->label('Judul')->default('Angka Nyata Dedikasi Kami'),
                                    Forms\Components\Textarea::make('description')->label('Deskripsi')->default('Pengalaman bertahun-tahun melayani ribuan proyek rumah tinggal, ruko, perumahan, dan gedung perkantoran.'),
                                    static::alignmentSelect('center'),
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
                                ->label('[4. Galeri] 💪 Uji Kekuatan & Ketahanan Beban')
                                ->icon('heroicon-o-shield-check')
                                ->schema([
                                    Forms\Components\TextInput::make('title')->label('Judul')->default('Uji Kekuatan & Ketahanan Beban Nyata'),
                                    Forms\Components\Textarea::make('description')->label('Deskripsi')->default('Roster beton kami diproduksi menggunakan formula pasir silika dan semen mutu tinggi dengan sistem cetak getar padat. Hasilnya adalah struktur yang sangat padat, minim pori, dan sanggup menahan beban struktural maupun cuaca ekstrem.'),
                                    static::alignmentSelect('left'),
                                    Forms\Components\FileUpload::make('video_upload')
                                        ->label('Upload Media (Foto/Video)')
                                        ->acceptedFileTypes(['image/*', 'video/*'])
                                        ->maxSize(102400)
                                        ->imagePreviewHeight('120')
                                        ->openable()
                                        ->downloadable()
                                        ->live()
                                        ->directory('pages/strength_test'),
                                    Forms\Components\TextInput::make('video_url')
                                        ->label('Atau URL Media Eksternal (Foto/Video)')
                                        ->live(),
                                    static::mediaPreview('video_upload', 'video_url'),
                                    static::bgThemeSelect('white'),
                                    Forms\Components\Repeater::make('features')
                                        ->schema([
                                            Forms\Components\TextInput::make('title')->label('Fitur'),
                                            Forms\Components\TextInput::make('desc')->label('Keterangan Singkat'),
                                        ])
                                        ->columns(2)
                                        ->default([
                                            ['title' => 'Beban Tekan Maksimal', 'desc' => 'Struktur sangat padat dan tidak mudah retak rambut saat dipasang pada bentang dinding tinggi.'],
                                            ['title' => 'Tahan Cuaca Tropis', 'desc' => 'Tahan paparan panas terik dan hujan tanpa risiko lumut berlebih atau kerapuhan semen.'],
                                            ['title' => 'Presisi Sudut 90°', 'desc' => 'Siku dan ketebalan seragam memudahkan tukang memasang dengan nat rapi dan sejajar.'],
                                        ]),
                                ]),

                            Forms\Components\Builder\Block::make('featured_products')
                                ->label('[2. Produk] 📦 Produk Pilihan / Semua Katalog (Auto-Reveal)')
                                ->icon('heroicon-o-shopping-bag')
                                ->schema([
                                    Forms\Components\TextInput::make('title')
                                        ->label('Judul Seksi')
                                        ->default('Koleksi Produk Roster Beton Minimalis')
                                        ->required(),
                                    Forms\Components\TextInput::make('subtitle')
                                        ->label('Sub-judul / Keterangan Wilayah')
                                        ->placeholder('Contoh: Melayani pengiriman langsung armada pabrik ke seluruh Jabodetabek'),
                                    Forms\Components\TextInput::make('badge')
                                        ->label('Teks Badge Atas')
                                        ->default('Koleksi Pilihan Arsitek'),
                                    Forms\Components\Select::make('categories')
                                        ->label('Pilih Kategori Produk')
                                        ->multiple()
                                        ->options(Category::pluck('name', 'id'))
                                        ->helperText('Biarkan kosong jika ingin menampilkan SEMUA kategori produk roster.'),
                                    Forms\Components\Select::make('grid_columns')
                                        ->label('Tata Letak Ukuran Kartu & Jumlah Kolom')
                                        ->options([
                                            '4' => '🧱 Ukuran Standar (4 Kolom per Baris — Sesuai Foto 3)',
                                            '6' => '📐 Ukuran Kompak (6 Kolom per Baris — Sesuai Katalog Foto 4)',
                                        ])
                                        ->default('4')
                                        ->helperText('Pilih 4 kolom untuk tampilan kartu lebih besar dan jelas, atau 6 kolom untuk tampilan kompak seperti di halaman katalog.'),
                                    Forms\Components\TextInput::make('limit')
                                        ->label('Jumlah Produk Ditampilkan')
                                        ->helperText('Contoh: 12, 24, atau 50 agar semua produk tampil.')
                                        ->numeric()
                                        ->default(24),
                                    static::bgThemeSelect('white'),
                                ]),

                            Forms\Components\Builder\Block::make('viral_products')
                                ->label('[2. Produk] 🔥 Produk Terlaris & Viral (Top Sold)')
                                ->icon('heroicon-o-fire')
                                ->schema([
                                    Forms\Components\TextInput::make('title')
                                        ->label('Judul Seksi')
                                        ->default('Produk Terlaris & Viral 🔥'),
                                    Forms\Components\TextInput::make('subtitle')
                                        ->label('Sub-judul')
                                        ->default('Koleksi roster terpopuler dengan penjualan dan ulasan terbanyak'),
                                    static::alignmentSelect('center'),
                                    Forms\Components\TextInput::make('limit')
                                        ->label('Jumlah Produk')
                                        ->numeric()
                                        ->default(6),
                                    static::bgThemeSelect('white'),
                                ]),

                            Forms\Components\Builder\Block::make('voucher_showcase')
                                ->label('[2. Produk] 🏷️ Voucher & Promo Diskon Wilayah')
                                ->icon('heroicon-o-ticket')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')
                                        ->label('Badge')
                                        ->default('PROMO & VOUCHER WILAYAH'),
                                    Forms\Components\TextInput::make('title')
                                        ->label('Judul Seksi')
                                        ->default('Klaim Promo Armada Pabrik Sesuai Lokasi Proyek Anda'),
                                    Forms\Components\Textarea::make('description')
                                        ->label('Deskripsi Singkat')
                                        ->default('Gunakan kode voucher pengiriman armada pabrik saat checkout atau sebutkan saat konsultasi dengan tim Admin WhatsApp.'),
                                    Forms\Components\TextInput::make('button_text')
                                        ->label('Teks Tombol')
                                        ->default('Konsultasi Admin Pabrik'),
                                    Forms\Components\TextInput::make('button_url')
                                        ->label('Link Tombol')
                                        ->default('https://wa.me/6281389709847'),
                                    static::bgThemeSelect('white'),
                                ]),

                            Forms\Components\Builder\Block::make('why_us')
                                ->label('[3. Pabrik] 💎 Kenapa Memilih Kami (Why Choose Us - Multi Poin & Desain)')
                                ->icon('heroicon-o-hand-thumb-up')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Teks Badge Atas')->default('💎 KEUNGGULAN KOMPETITIF'),
                                    Forms\Components\TextInput::make('title')->label('Judul Utama')->default('Kenapa Memilih Roster Pabrik Kami?')->required(),
                                    Forms\Components\Textarea::make('description')->label('Sub-judul / Narasi Pengantar')->rows(2)->default('Kami mengedepankan kualitas cetakan mutu K-200, kepresisian sudut 90°, armada mandiri tepat waktu, dan transparansi harga tangan pertama.'),
                                    Forms\Components\Select::make('layout_style')
                                        ->label('Format Tata Letak (Layout Style)')
                                        ->options([
                                            'grid' => '🔲 Grid Kartu Elegan (3 - 4 Kolom Penuh)',
                                            'split' => '🖼️ 2 Kolom (Poin Kiri + Media/Foto/Video Kanan)',
                                        ])
                                        ->default('grid'),
                                    static::alignmentSelect('center'),
                                    static::bgThemeSelect('dark'),
                                    Forms\Components\Repeater::make('items')
                                        ->label('Daftar Poin-Poin Keunggulan')
                                        ->schema([
                                            Forms\Components\TextInput::make('icon')->label('Emoji / Icon')->placeholder('Contoh: 🏭, 🛡️, 📐, 🚚, 📑, 💎')->default('💎'),
                                            Forms\Components\TextInput::make('title')->label('Judul Poin')->required(),
                                            Forms\Components\Textarea::make('description')->label('Deskripsi / Penjelasan Poin')->rows(2)->required(),
                                        ])
                                        ->columns(3)
                                        ->default([
                                            ['icon' => '🏭', 'title' => 'Pabrik Produsen Tangan Pertama', 'description' => 'Harga langsung dari sentra Plered Purwakarta tanpa markup calo atau toko perantara.'],
                                            ['icon' => '🛡️', 'title' => 'Garansi 100% Bebas Pecah', 'description' => 'Setiap keping yang rusak atau sompel saat proses pengiriman armada langsung kami ganti baru.'],
                                            ['icon' => '📐', 'title' => 'Sudut Siku 90° & Mutu K-200', 'description' => 'Dipadatkan dengan hidrolik bertenaga tinggi, pori-pori rapat, dan presisi saat dipasang tukang.'],
                                            ['icon' => '🚚', 'title' => 'Armada Truk Logistik Harian', 'description' => 'Pengiriman rutin terjadwal menjangkau Jabodetabek, Bandung, Banten, dan seluruh Jawa Barat.'],
                                        ]),
                                    Forms\Components\Repeater::make('videos')
                                        ->label('Media Samping (Foto / Video - Khusus Mode 2 Kolom)')
                                        ->schema([
                                            Forms\Components\FileUpload::make('video_upload')
                                                ->label('Upload Media (Foto/Video)')
                                                ->acceptedFileTypes(['image/*', 'video/*'])
                                                ->maxSize(102400)
                                                ->imagePreviewHeight('120')
                                                ->openable()
                                                ->downloadable()
                                                ->live()
                                                ->directory('pages/why_us'),
                                            Forms\Components\TextInput::make('url')
                                                ->label('Atau URL Media Eksternal (Foto/Video)')
                                                ->live(),
                                            static::mediaPreview('video_upload', 'url'),
                                        ]),
                                ]),

                            Forms\Components\Builder\Block::make('shipping_info')
                                ->label('[7. Kontak] 🚚 Informasi Pengiriman & Armada Truk')
                                ->icon('heroicon-o-truck')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Teks Kecil (Badge)')->default('Armada Pengiriman Mandiri'),
                                    Forms\Components\TextInput::make('title')->label('Judul')->default('Jangkauan Pengiriman Seluruh Jabodetabek & Indonesia'),
                                    Forms\Components\Textarea::make('content')->label('Isi Konten')->default('Pengiriman langsung dari pabrik dengan packing aman bersegel dan garansi ganti baru 100% jika terjadi kerusakan dalam perjalanan.'),
                                    static::alignmentSelect('left'),
                                    Forms\Components\FileUpload::make('video_upload')
                                        ->label('Upload Media (Foto/Video)')
                                        ->acceptedFileTypes(['image/*', 'video/*'])
                                        ->maxSize(102400)
                                        ->imagePreviewHeight('120')
                                        ->openable()
                                        ->downloadable()
                                        ->live()
                                        ->directory('pages/shipping'),
                                    Forms\Components\TextInput::make('video_url')
                                        ->label('Atau URL Media Eksternal (Foto/Video)')
                                        ->live(),
                                    static::mediaPreview('video_upload', 'video_url'),
                                    Forms\Components\TextInput::make('button_text')->label('Teks Tombol')->default('Konsultasi Ongkos Kirim'),
                                    Forms\Components\TextInput::make('button_url')->label('Link Tombol'),
                                    static::bgThemeSelect('white'),
                                ]),

                            Forms\Components\Builder\Block::make('social_review')
                                ->label('[5. Ulasan] 💬 Screenshot Chat WhatsApp & Sosmed')
                                ->icon('heroicon-o-device-phone-mobile')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Teks Kecil (Badge)')->default('Ulasan Nyata Pelanggan'),
                                    Forms\Components\TextInput::make('title')->label('Judul')->default('Dipercaya Ribuan Pemilik Rumah, Kontraktor & Arsitek'),
                                    Forms\Components\Textarea::make('description')->label('Deskripsi')->default('Tangkapan layar chat transaksi dan kepuasan pelanggan saat barang diterima di lokasi proyek.'),
                                    static::alignmentSelect('center'),
                                    Forms\Components\FileUpload::make('video_upload')
                                        ->label('Upload Media (Foto/Video)')
                                        ->acceptedFileTypes(['image/*', 'video/*'])
                                        ->maxSize(102400)
                                        ->imagePreviewHeight('120')
                                        ->openable()
                                        ->downloadable()
                                        ->live()
                                        ->directory('pages/social'),
                                    Forms\Components\TextInput::make('video_url')
                                        ->label('Atau URL Media Eksternal (Foto/Video)')
                                        ->live(),
                                    static::mediaPreview('video_upload', 'video_url'),
                                    Forms\Components\TextInput::make('creators_count')->label('Jumlah Kreator')->default('2.500+ Proyek'),
                                    static::bgThemeSelect('dark'),
                                ]),

                            Forms\Components\Builder\Block::make('testimonials')
                                ->label('[5. Ulasan] ⭐ Testimoni Pelanggan & Rating Bintang')
                                ->icon('heroicon-o-chat-bubble-left-right')
                                ->schema([
                                    Forms\Components\TextInput::make('title')->label('Judul'),
                                    static::alignmentSelect('center'),
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
                                ->label('[4. Galeri] 🖼️ Grid Galeri Foto Proyek Arsitektur')
                                ->icon('heroicon-o-squares-2x2')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge')->default('Inspirasi Visual'),
                                    Forms\Components\TextInput::make('title')->label('Judul')->default('Galeri Aplikasi Roster pada Bangunan Nyata'),
                                    Forms\Components\Textarea::make('description')->label('Deskripsi')->default('Lihat bagaimana produk roster kami mengubah fasad rumah, pagar, dan sekat ruang menjadi karya arsitektural yang estetik.'),
                                    static::alignmentSelect('center'),
                                    static::bgThemeSelect('dark'),
                                    Forms\Components\Repeater::make('items')
                                        ->schema([
                                            Forms\Components\FileUpload::make('image_upload')
                                                ->label('Upload Media Proyek (Foto/Video)')
                                                ->acceptedFileTypes(['image/*', 'video/*'])
                                                ->maxSize(102400)
                                                ->imagePreviewHeight('120')
                                                ->openable()
                                                ->downloadable()
                                                ->live()
                                                ->directory('pages/gallery'),
                                            Forms\Components\TextInput::make('image')
                                                ->label('Atau URL Media Eksternal (Foto/Video)')
                                                ->placeholder('Link foto...')
                                                ->live(),
                                            static::mediaPreview('image_upload', 'image'),
                                            Forms\Components\TextInput::make('title')->label('Judul Proyek'),
                                        ])
                                        ->columns(2),
                                ]),

                            Forms\Components\Builder\Block::make('ugc_videos')
                                ->label('[4. Galeri] 🎥 Video Inspirasi UGC & Reel Proyek (9:16)')
                                ->icon('heroicon-o-video-camera')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')
                                        ->label('Badge')
                                        ->placeholder('VISUAL EXPERIENCE')
                                        ->default('VISUAL EXPERIENCE'),
                                    Forms\Components\TextInput::make('title')
                                        ->label('Judul Seksi')
                                        ->placeholder('Lihat Detailnya Lebih Dekat')
                                        ->default('Lihat Detailnya Lebih Dekat'),
                                    Forms\Components\Textarea::make('description')
                                        ->label('Deskripsi')
                                        ->placeholder('Kami percaya bahwa melihat adalah percaya...')
                                        ->default('Kami percaya bahwa melihat adalah percaya. Koleksi video inspirasi kami menunjukkan bagaimana cahaya dan udara mengalir melalui setiap celah roster kami.'),
                                    static::alignmentSelect('center'),
                                    Forms\Components\TextInput::make('button_text')
                                        ->label('Teks Tombol')
                                        ->placeholder('Video Inspirasi Lengkap')
                                        ->default('Video Inspirasi Lengkap'),
                                    Forms\Components\TextInput::make('button_url')
                                        ->label('Link URL Tombol')
                                        ->placeholder('/inspirasi-video atau https://...')
                                        ->default('/inspirasi-video'),
                                    static::bgThemeSelect('white'),
                                    Forms\Components\FileUpload::make('videos_upload')
                                        ->label('Upload Koleksi Video/Foto (Bisa Pilih Banyak Sekaligus)')
                                        ->acceptedFileTypes(['video/*', 'image/*'])
                                        ->multiple()
                                        ->reorderable()
                                        ->appendFiles()
                                        ->openable()
                                        ->downloadable()
                                        ->previewable(true)
                                        ->panelLayout('grid')
                                        ->imagePreviewHeight('100')
                                        ->maxFiles(20)
                                        ->maxSize(102400)
                                        ->directory('pages/ugc')
                                        ->helperText('Format: Semua Video/Foto (aspek rasio 9:16 vertikal lebih bagus).'),
                                    Forms\Components\Repeater::make('videos')
                                        ->label('Atau Tambah Media Satuan / URL Eksternal (Cloudinary / Link)')
                                        ->schema([
                                            Forms\Components\FileUpload::make('video_upload')
                                                ->label('Upload Media (Foto/Video)')
                                                ->acceptedFileTypes(['image/*', 'video/*'])
                                                ->maxSize(102400)
                                                ->imagePreviewHeight('120')
                                                ->openable()
                                                ->downloadable()
                                                ->live()
                                                ->directory('pages/ugc'),
                                            Forms\Components\TextInput::make('url')
                                                ->label('Atau URL Media Eksternal (Foto/Video)')
                                                ->placeholder('https://...')
                                                ->live(),
                                            static::mediaPreview('video_upload', 'url'),
                                        ]),
                                ]),

                            Forms\Components\Builder\Block::make('gallery_collection')
                                ->label('[4. Galeri] 🗂️ Koleksi Galeri Terfilter (Fasad/Pagar)')
                                ->icon('heroicon-o-rectangle-group')
                                ->schema([
                                    Forms\Components\Placeholder::make('hint')
                                        ->label('')
                                        ->content('Tips: Foto-foto dalam koleksi ini dikelola secara terpusat melalui menu "Galeri" di sidebar kiri.'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi')->default('Inspirasi Proyek Kami'),
                                    Forms\Components\Textarea::make('description')->label('Deskripsi')->default('Jelajahi koleksi mahakarya pemasangan roster beton minimalis yang telah menghiasi berbagai hunian.'),
                                    static::alignmentSelect('center'),
                                    static::bgThemeSelect('slate'),
                                ]),

                            Forms\Components\Builder\Block::make('faq')
                                ->label('[6. Fitur] ❓ Tanya Jawab Seputar Roster (FAQ Accordion - Kustom / Database)')
                                ->icon('heroicon-o-question-mark-circle')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Teks Badge Atas')->default('❓ PUSAT INFORMASI & FAQ'),
                                    Forms\Components\TextInput::make('title')
                                        ->label('Judul Seksi')
                                        ->default('Pertanyaan yang Sering Diajukan (FAQ)')
                                        ->required(),
                                    Forms\Components\Textarea::make('description')->label('Deskripsi / Narasi Pengantar')->rows(2)->default('Jawaban lengkap dan transparan seputar spesifikasi roster beton, pengiriman armada pabrik, dan klaim garansi.'),
                                    static::alignmentSelect('center'),
                                    static::bgThemeSelect('slate'),
                                    Forms\Components\Radio::make('source_type')
                                        ->label('Sumber Data Tanya Jawab (FAQ)')
                                        ->options([
                                            'custom' => '✍️ Tulis Kustom Sendiri (Pertanyaan & Jawaban Khusus Halaman Ini)',
                                            'database' => '🌐 Ambil Otomatis dari Database Global FAQ (Menu Sidebar)',
                                        ])
                                        ->default('custom')
                                        ->live(),
                                    Forms\Components\Repeater::make('custom_faqs')
                                        ->label('Daftar Tanya Jawab Kustom')
                                        ->visible(fn (Forms\Get $get) => ($get('source_type') ?? 'custom') === 'custom')
                                        ->schema([
                                            Forms\Components\TextInput::make('question')->label('Pertanyaan')->required(),
                                            Forms\Components\Textarea::make('answer')->label('Jawaban')->rows(3)->required(),
                                        ])
                                        ->default([
                                            [
                                                'question' => 'Berapa berat rata-rata per keping roster beton?',
                                                'answer' => 'Rata-rata berat per keping roster beton ukuran standar 20x20x10 cm adalah sekitar 4.0 hingga 4.5 kg karena diproduksi dengan teknik cetak tumbuk padat mutu K-200 tanpa rongga rapuh.',
                                            ],
                                            [
                                                'question' => 'Apakah ada minimal order untuk pengiriman luar kota?',
                                                'answer' => 'Tidak ada minimal order khusus. Anda bisa memesan sesuai kebutuhan proyek. Namun untuk efisiensi ongkos kirim armada truk pabrik, disarankan menggabungkan pesanan atau memanfaatkan rute pengiriman berkala kami.',
                                            ],
                                            [
                                                'question' => 'Bagaimana jika ada roster yang pecah saat tiba di lokasi proyek?',
                                                'answer' => 'IndoRoster memberikan Garansi Bebas Pecah 100%. Cukup foto keping yang rusak saat serah terima barang bersama supir kami, dan unit pengganti baru akan segera kami kirimkan gratis tanpa biaya tambahan.',
                                            ],
                                        ]),
                                    Forms\Components\TextInput::make('limit')
                                        ->label('Jumlah FAQ Ditampilkan dari Database')
                                        ->numeric()
                                        ->default(10)
                                        ->visible(fn (Forms\Get $get) => $get('source_type') === 'database'),
                                ]),

                            Forms\Components\Builder\Block::make('promo_banner')
                                ->label('[1. Hero] ⏳ Promo Countdown Banner (Timer Diskon)')
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
                                    static::alignmentSelect('center'),
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

                            Forms\Components\Builder\Block::make('buyer_protection')
                                ->label('[3. Pabrik] 🛡️ Jaminan Transaksi Online (Buyer Protection)')
                                ->icon('heroicon-o-shield-check')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge')->default('TRANSAKSI AMAN & TERPERCAYA'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi')->default('Jaminan Keamanan & 4 Garansi Resmi Pembelian Online'),
                                    Forms\Components\Textarea::make('subtitle')->label('Sub-judul')->default('Kami mengutamakan kepuasan dan ketenangan pikiran Anda saat berbelanja material langsung dari pabrik kami.'),
                                    static::alignmentSelect('center'),
                                    static::bgThemeSelect('dark'),
                                    Forms\Components\Repeater::make('items')
                                        ->label('Daftar Jaminan / Garansi')
                                        ->schema([
                                            Forms\Components\Select::make('icon')
                                                ->label('Ikon')
                                                ->options([
                                                    'shield' => '🛡️ Perisai / Garansi 100%',
                                                    'factory' => '🏭 Pabrik Fisik / Workshop',
                                                    'document' => '📑 Dokumen Resmi / Invoice',
                                                    'payment' => '💳 Pembayaran Aman / Payment Gateway',
                                                ])
                                                ->default('shield'),
                                            Forms\Components\TextInput::make('badge')->label('Badge Kecil')->default('GARANSI RESMI'),
                                            Forms\Components\TextInput::make('title')->label('Judul Jaminan')->required(),
                                            Forms\Components\Textarea::make('desc')->label('Keterangan Jaminan')->required(),
                                        ])
                                        ->columns(2)
                                        ->default([
                                            ['icon' => 'shield', 'badge' => 'GARANSI 100%', 'title' => 'Garansi Pecah Ganti Baru', 'desc' => 'Jika ditemukan keping roster yang retak, gompal, atau pecah saat pengiriman, kami ganti unit baru tanpa biaya tambahan.'],
                                            ['icon' => 'factory', 'badge' => 'PABRIK NYATA', 'title' => 'Terverifikasi & Bisa Survey Langsung', 'desc' => 'Workshop fisik kami beroperasi di Plered, Purwakarta. Anda dipersilakan survey lokasi atau request Live Video Call WhatsApp untuk cek stok.'],
                                            ['icon' => 'document', 'badge' => 'LEGAL & RESMI', 'title' => 'Invoice & Surat Jalan Berstempel', 'desc' => 'Setiap transaksi diterbitkan dokumen resmi berbadan usaha dengan nomor surat jalan dan rincian spesifikasi yang jelas.'],
                                            ['icon' => 'payment', 'badge' => 'TRANSAKSI AMAN', 'title' => 'Rekening Resmi & Multi-Payment', 'desc' => 'Mendukung transfer rekening bank resmi, Payment Gateway Midtrans (QRIS, Kartu Kredit, Virtual Account), dan sistem DP aman.'],
                                        ]),
                                ]),

                            Forms\Components\Builder\Block::make('trust_payment_shipping')
                                ->label('[3. Pabrik] 🏭 Edukasi Pabrik, Pembayaran & Armada Truk')
                                ->icon('heroicon-o-credit-card')
                                ->schema([
                                    Forms\Components\Placeholder::make('info')
                                        ->label('')
                                        ->content(new HtmlString('<div class="p-3 bg-terra-50 dark:bg-terra-950/40 rounded-xl text-xs text-terra-700 dark:text-terra-300 font-medium">✨ Blok ini menampilkan ringkasan profil pabrik, 4 jaminan keaslian, logo metode pembayaran, dan armada pengiriman. Anda bisa mengatur nama bank dan armada truk khusus untuk halaman ini di bawah. (Biarkan kosong jika ingin menggunakan default).</div>')),
                                    Forms\Components\TextInput::make('badge')
                                        ->label('Teks Badge Pill Atas (Opsional)')
                                        ->placeholder('Pusat Pabrik Roster Beton Plered Purwakarta'),
                                    Forms\Components\TextInput::make('title')
                                        ->label('Judul Utama (Opsional)')
                                        ->placeholder('Nikmati Kemudahan & Keamanan Belanja Roster Tangan Pertama di IndoRoster'),
                                    Forms\Components\Textarea::make('description')
                                        ->label('Deskripsi / Penjelasan Wilayah (Opsional)')
                                        ->placeholder('Biarkan kosong jika ingin menggunakan deskripsi standar pabrik.')
                                        ->rows(3),
                                    Forms\Components\TagsInput::make('payments')
                                        ->label('💳 Daftar Metode Pembayaran (Opsional)')
                                        ->placeholder('Ketik nama bank/e-wallet lalu tekan Enter...')
                                        ->helperText('Contoh: BCA, Mandiri, BNI, BRI, BSI, QRIS, GoPay, ShopeePay, DANA, OVO. (Kosongkan untuk memakai default)')
                                        ->suggestions(['BCA', 'Mandiri', 'BNI', 'BRI', 'BSI', 'CIMB', 'Permata', 'QRIS', 'GoPay', 'ShopeePay', 'DANA', 'OVO', 'COD / Bayar di Tempat']),
                                    Forms\Components\TagsInput::make('shippings')
                                        ->label('🚚 Daftar Jasa Pengiriman & Armada Truk (Opsional)')
                                        ->placeholder('Ketik nama armada/ekspedisi lalu tekan Enter...')
                                        ->helperText('Contoh: Armada Truk IndoRoster, Ekspedisi Kargo Material, JNE Trucking, Dakota Cargo, Indah Logistik. (Kosongkan untuk memakai default)')
                                        ->suggestions(['Armada Truk IndoRoster', 'Ekspedisi Kargo Material', 'JNE Trucking', 'Dakota Cargo', 'Indah Logistik', 'SiCepat', 'Pos Indonesia', 'Deliveree', 'Lalamove', 'Truk Tronton / CDD']),
                                ]),

                            Forms\Components\Builder\Block::make('shipment_proof')
                                ->label('[3. Pabrik] 📸 Bukti Pengiriman & Surat Jalan Harian')
                                ->icon('heroicon-o-truck')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge')->default('DOKUMENTASI PENGIRIMAN NYATA'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi')->default('Bukti Pengiriman & Bongkar Muat Harian'),
                                    Forms\Components\Textarea::make('description')->label('Deskripsi')->default('Ratusan ribu keping roster telah terkirim aman ke berbagai kota dan pulau di Indonesia langsung dari pabrik kami.'),
                                    static::alignmentSelect('center'),
                                    static::bgThemeSelect('white'),
                                    Forms\Components\Repeater::make('shipments')
                                        ->label('Daftar Dokumentasi Pengiriman')
                                        ->schema([
                                            Forms\Components\FileUpload::make('image_upload')
                                                ->label('Upload Foto Muatan/Bongkar')
                                                ->acceptedFileTypes(['image/*'])
                                                ->imagePreviewHeight('120')
                                                ->openable()
                                                ->downloadable()
                                                ->live()
                                                ->directory('pages/proofs'),
                                            Forms\Components\TextInput::make('image')
                                                ->label('Atau URL Foto Eksternal')
                                                ->placeholder('https://...')
                                                ->live(),
                                            static::mediaPreview('image_upload', 'image'),
                                            Forms\Components\TextInput::make('destination')->label('Kota / Lokasi Proyek')->placeholder('PIK 2, Jakarta Utara')->required(),
                                            Forms\Components\TextInput::make('qty')->label('Jumlah & Motif Roster')->placeholder('850 Pcs Roster Beton')->required(),
                                            Forms\Components\TextInput::make('vehicle')->label('Jenis Armada')->placeholder('Truk CDD 6 Roda')->default('Armada Pabrik'),
                                            Forms\Components\TextInput::make('status')->label('Status Pengiriman')->placeholder('Terkirim Aman 100%')->default('Terkirim Aman 100%'),
                                        ])
                                        ->columns(2)
                                        ->default([
                                            ['image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260853/162067858_988931008308004_8757323712171815873_n_kpbq7h.jpg', 'destination' => 'PIK 2, Jakarta Utara', 'qty' => '850 Pcs Roster Beton', 'vehicle' => 'Truk CDD 6 Roda', 'status' => 'Terkirim Aman 100%'],
                                            ['image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260853/162040523_301019624734327_8783457199144865187_n_c4ebs6.jpg', 'destination' => 'Dago Atas, Bandung', 'qty' => '1.200 Pcs Roster Minimalis', 'vehicle' => 'Truk Fuso Pabrik', 'status' => 'Terkirim Aman 100%'],
                                            ['image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260854/162443423_465492824683050_2508781488168434771_n_oixmwn.jpg', 'destination' => 'BSD City, Tangerang Selatan', 'qty' => '600 Pcs Roster Abu-Abu', 'vehicle' => 'Truk Engkel 4 Roda', 'status' => 'Terkirim Aman 100%'],
                                            ['image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260855/162744318_115865207198888_2731872166667500125_n_oazhvv.jpg', 'destination' => 'Summarecon, Bekasi', 'qty' => '450 Pcs Roster Terracotta', 'vehicle' => 'Armada Pick-up Cepat', 'status' => 'Terkirim Aman 100%'],
                                        ]),
                                ]),

                            Forms\Components\Builder\Block::make('buying_steps')
                                ->label('[6. Fitur] 📝 Panduan Cara Pemesanan Mudah (Buying Steps)')
                                ->icon('heroicon-o-arrow-path')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge')->default('ALUR PEMBELIAN AMAN'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi')->default('4 Langkah Mudah & Aman Order Roster ke Pabrik'),
                                    Forms\Components\Textarea::make('subtitle')->label('Sub-judul')->default('Proses transparan mulai dari konsultasi gambar/motif hingga roster terpasang di dinding Anda.'),
                                    static::alignmentSelect('center'),
                                    static::bgThemeSelect('slate'),
                                    Forms\Components\Repeater::make('steps')
                                        ->label('Langkah-Langkah')
                                        ->schema([
                                            Forms\Components\TextInput::make('step')->label('Nomor Langkah (01, 02...)')->required(),
                                            Forms\Components\TextInput::make('title')->label('Judul Langkah')->required(),
                                            Forms\Components\Textarea::make('desc')->label('Penjelasan Langkah')->required(),
                                        ])
                                        ->columns(3)
                                        ->default([
                                            ['step' => '01', 'title' => 'Konsultasi & Hitung Kebutuhan', 'desc' => 'Kirim ukuran dinding atau foto denah Anda. Tim ahli kami bantu hitung jumlah keping & semen perekat secara gratis tanpa biaya konsultasi.'],
                                            ['step' => '02', 'title' => 'Penerbitan Invoice Resmi', 'desc' => 'Dapatkan Surat Penawaran & Invoice resmi berstempel dengan rincian harga pabrik transparan, diskon kuantiti, dan nomor rekening sah perusahaan.'],
                                            ['step' => '03', 'title' => 'Quality Control & Muat Armada', 'desc' => 'Roster diperiksa kelayakan sudut dan kekuatannya. Anda akan dikirimi foto/video armada pabrik saat barang dimuat sebelum berangkat.'],
                                            ['step' => '04', 'title' => 'Barang Sampai & Garansi Aktif', 'desc' => 'Cek kondisi roster bersama sopir armada pabrik kami di lokasi. Garansi 100% ganti baru langsung berlaku jika ada barang rusak di jalan.'],
                                        ]),
                                ]),

                            Forms\Components\Builder\Block::make('quality_comparison')
                                ->label('[6. Fitur] ⚖️ Komparasi Kualitas (Pabrik vs Pasaran)')
                                ->icon('heroicon-o-scale')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge')->default('KOMPARASI STANDAR MUTU'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi')->default('Mengapa Roster Kami Berbeda dari Pasaran?'),
                                    Forms\Components\Textarea::make('subtitle')->label('Sub-judul')->default('Jangan tergiur harga murah tapi mudah retak saat dipasang. Bandingkan kualitas mutu fisik kami:'),
                                    static::alignmentSelect('center'),
                                    static::bgThemeSelect('white'),
                                    Forms\Components\Repeater::make('comparisons')
                                        ->label('Kriteria Perbandingan')
                                        ->schema([
                                            Forms\Components\TextInput::make('feature')->label('Kriteria Uji')->required(),
                                            Forms\Components\Textarea::make('indoroster')->label('Kualitas IndoRoster (Pabrik)')->required(),
                                            Forms\Components\Textarea::make('market')->label('Kualitas Roster Pasaran')->required(),
                                        ])
                                        ->columns(3)
                                        ->default([
                                            ['feature' => 'Komposisi Bahan Baku', 'indoroster' => 'Beton padat dengan agregat dan semen pilihan standar mutu tinggi', 'market' => 'Campuran semen minim, dominan pasir biasa, rapuh & mudah rontok'],
                                            ['feature' => 'Presisi Sudut & Ukuran', 'indoroster' => 'Siku presisi 90° hasil cetakan baja (pemasangan rapi tanpa nat tebal)', 'market' => 'Ukuran sering melengkung dan berbeda ukuran tiap keping'],
                                            ['feature' => 'Finishing Permukaan', 'indoroster' => 'Halus & padat 2 muka, siap dicat tanpa perlu plamir tebal', 'market' => 'Permukaan kasar, berpori besar, banyak rongga udara'],
                                            ['feature' => 'Ketahanan & Kekuatan', 'indoroster' => 'Tahan benturan keras, tahan cuaca ekstrem panas & hujan tanpa retak', 'market' => 'Gampang gupil/pecah saat pengiriman dan saat dibor/dipasang tukang'],
                                            ['feature' => 'Jaminan Garansi Pengiriman', 'indoroster' => 'Garansi 100% Pecah Ganti Baru diantar langsung ke lokasi', 'market' => 'Tanpa garansi pengiriman, risiko ditanggung sepenuhnya oleh pembeli'],
                                        ]),
                                ]),

                            Forms\Components\Builder\Block::make('roster_calculator')
                                ->label('[6. Fitur] 🧮 Kalkulator Kebutuhan Roster Otomatis (P × L)')
                                ->icon('heroicon-o-calculator')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge')->default('SIMULASI KEBUTUHAN CEPAT'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi')->default('Kalkulator Kebutuhan Roster & Semen Perekat'),
                                    Forms\Components\Textarea::make('description')->label('Deskripsi')->default('Masukkan ukuran dinding proyek Anda untuk mengetahui estimasi jumlah keping roster dan sak semen perekat yang dibutuhkan secara akurat.'),
                                    static::alignmentSelect('center'),
                                    Forms\Components\TextInput::make('roster_per_m2')->label('Rasio Roster per m² (Standar 20x20cm)')->numeric()->default(25),
                                    static::bgThemeSelect('slate'),
                                ]),

                            Forms\Components\Builder\Block::make('latest_articles')
                                ->label('[7. Kontak] 📰 Artikel & Tips Arsitektur Terbaru')
                                ->icon('heroicon-o-newspaper')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge')->default('BLOG & EDUKASI ARSITEKTUR'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi')->default('Tips, Inspirasi & Panduan Pasang Roster'),
                                    Forms\Components\Textarea::make('subtitle')->label('Sub-judul')->default('Kumpulan artikel informatif untuk membantu perencanaan fasad dan dinding ventilasi rumah Anda.'),
                                    static::alignmentSelect('center'),
                                    Forms\Components\TextInput::make('limit')->label('Jumlah Artikel Ditampilkan')->numeric()->default(3),
                                    static::bgThemeSelect('white'),
                                ]),

                            Forms\Components\Builder\Block::make('before_after')
                                ->label('[5. Ulasan] 🔄 Slider Sebelum vs Sesudah (Before/After)')
                                ->icon('heroicon-o-arrows-right-left')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge')->default('TRANSFORMASI FASAD ARSITEKTURAL'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi')->default('Lihat Perbedaan Sebelum & Sesudah Pasang Roster'),
                                    Forms\Components\Textarea::make('subtitle')->label('Sub-judul')->default('Geser tombol slider di tengah gambar untuk melihat bagaimana roster beton IndoRoster mengubah dinding polos menjadi fasad modern yang bernilai seni tinggi.'),
                                    static::alignmentSelect('center'),
                                    static::bgThemeSelect('dark'),
                                    Forms\Components\FileUpload::make('before_image_upload')
                                        ->label('Upload Foto Sebelum (Dinding Polos/Kusam)')
                                        ->acceptedFileTypes(['image/*'])
                                        ->imagePreviewHeight('120')
                                        ->openable()
                                        ->downloadable()
                                        ->live()
                                        ->directory('pages/before_after'),
                                    Forms\Components\TextInput::make('before_image')
                                        ->label('Atau URL Foto Sebelum')
                                        ->placeholder('https://...')
                                        ->live(),
                                    static::mediaPreview('before_image_upload', 'before_image'),
                                    Forms\Components\TextInput::make('before_label')->label('Label Foto Sebelum')->default('SEBELUM: Dinding Biasa Polos'),
                                    Forms\Components\FileUpload::make('after_image_upload')
                                        ->label('Upload Foto Sesudah (Fasad Roster IndoRoster)')
                                        ->acceptedFileTypes(['image/*'])
                                        ->imagePreviewHeight('120')
                                        ->openable()
                                        ->downloadable()
                                        ->live()
                                        ->directory('pages/before_after'),
                                    Forms\Components\TextInput::make('after_image')
                                        ->label('Atau URL Foto Sesudah')
                                        ->placeholder('https://...')
                                        ->live(),
                                    static::mediaPreview('after_image_upload', 'after_image'),
                                    Forms\Components\TextInput::make('after_label')->label('Label Foto Sesudah')->default('SESUDAH: Fasad Roster IndoRoster'),
                                ]),

                            Forms\Components\Builder\Block::make('client_logos')
                                ->label('[5. Ulasan] 🏢 Logo Rekanan Arsitek & Kontraktor')
                                ->icon('heroicon-o-briefcase')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge')->default('DIPERCAYA ARSITEK & KONTRAKTOR'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi')->default('Telah Digunakan di Berbagai Proyek Ternama'),
                                    Forms\Components\Textarea::make('subtitle')->label('Sub-judul')->default('Dipercaya oleh developer perumahan, konsultan arsitektur, dan ribuan pemilik hunian di seluruh Indonesia.'),
                                    static::alignmentSelect('center'),
                                    static::bgThemeSelect('white'),
                                    Forms\Components\Repeater::make('logos')
                                        ->label('Daftar Klien / Partner')
                                        ->schema([
                                            Forms\Components\FileUpload::make('image_upload')
                                                ->label('Upload Logo')
                                                ->acceptedFileTypes(['image/*'])
                                                ->imagePreviewHeight('80')
                                                ->openable()
                                                ->downloadable()
                                                ->live()
                                                ->directory('pages/logos'),
                                            Forms\Components\TextInput::make('image')
                                                ->label('Atau URL Logo')
                                                ->placeholder('https://...')
                                                ->live(),
                                            static::mediaPreview('image_upload', 'image'),
                                            Forms\Components\TextInput::make('name')->label('Nama Partner / Proyek')->required(),
                                            Forms\Components\TextInput::make('category')->label('Kategori (cth: Developer, Kontraktor, Cafe)'),
                                        ])
                                        ->columns(2)
                                        ->default([
                                            ['name' => 'Wika Gedung', 'category' => 'Kontraktor Nasional'],
                                            ['name' => 'Adhi Karya', 'category' => 'Infrastruktur'],
                                            ['name' => 'Summarecon', 'category' => 'Developer Perumahan'],
                                            ['name' => 'Sinarmas Land', 'category' => 'Kawasan Mandiri'],
                                        ]),
                                ]),

                            Forms\Components\Builder\Block::make('download_catalog')
                                ->label('[7. Kontak] 📥 Download E-Katalog PDF (Katalog Lengkap)')
                                ->icon('heroicon-o-arrow-down-tray')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge')->default('E-KATALOG & PRICELIST TERBARU'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi')->default('Download Buku Katalog Lengkap 150+ Motif Roster (PDF)'),
                                    Forms\Components\Textarea::make('subtitle')->label('Sub-judul')->default('Dapatkan spesifikasi lengkap, varian motif minimalis/klasik, inspirasi pemasangan fasad, dan tabel harga pabrik.'),
                                    static::alignmentSelect('center'),
                                    Forms\Components\FileUpload::make('pdf_upload')
                                        ->label('Upload File Katalog PDF')
                                        ->acceptedFileTypes(['application/pdf'])
                                        ->openable()
                                        ->downloadable()
                                        ->directory('pages/catalogs')
                                        ->helperText('Upload file PDF dari komputer. Tombol akan langsung mendownload file ini.'),
                                    Forms\Components\TextInput::make('pdf_url')
                                        ->label('Atau Link URL PDF / Google Drive')
                                        ->placeholder('https://drive.google.com/... atau https://...')
                                        ->helperText('Tempelkan link Google Drive / link PDF eksternal. Tombol akan langsung membuka link ini.'),
                                    Forms\Components\TextInput::make('button_text')
                                        ->label('Teks Tombol')
                                        ->default('Download E-Katalog PDF'),
                                    static::bgThemeSelect('accent'),
                                ]),

                            Forms\Components\Builder\Block::make('map_location')
                                ->label('[7. Kontak] 📍 Peta Lokasi Pabrik (Google Maps)')
                                ->icon('heroicon-o-map-pin')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge')->default('PETA LOKASI WORKSHOP'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi')->default('Kunjungi Workshop & Pabrik Langsung'),
                                    Forms\Components\Textarea::make('subtitle')->label('Sub-judul')->default('Kami menyambut kedatangan arsitek, kontraktor, dan calon pemilik rumah untuk melihat langsung stok dan proses produksi di workshop kami.'),
                                    static::alignmentSelect('left'),
                                    Forms\Components\TextInput::make('address')->label('Alamat Fisik Workshop')->default('Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165'),
                                    Forms\Components\TextInput::make('hours')->label('Jam Operasional')->default('Senin – Sabtu, 08.00 – 17.00 WIB'),
                                    Forms\Components\TextInput::make('map_embed')->label('URL Embed Google Maps (iframe src)')->default('https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.856!2d107.320!3d-6.522!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMzEnMTguOCJTIDEwN8KwMTknMTEuMSJF!5e0!3m2!1sen!2sid!4v1715150000000!5m2!1sen!2sid'),
                                    static::bgThemeSelect('slate'),
                                ]),

                            Forms\Components\Builder\Block::make('contact_form')
                                ->label('[7. Kontak] ✉️ Formulir Kontak Kirim Pesan')
                                ->icon('heroicon-o-envelope')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge')->default('HUBUNGI PABRIK LANGSUNG'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi')->default('Hubungi Pabrik & Konsultasi Proyek'),
                                    Forms\Components\Textarea::make('subtitle')->label('Sub-judul')->default('Solusi roster beton minimalis berkualitas tinggi dari sentra Plered Purwakarta. Hubungi kami untuk konsultasi motif, RAB, sampel material, dan pengiriman ke seluruh Indonesia.'),
                                    static::alignmentSelect('left'),
                                    static::bgThemeSelect('white'),
                                ]),

                            Forms\Components\Builder\Block::make('pricing_packages')
                                ->label('[2. Produk] 💰 Paket Harga Grosir & Proyek (Pricing)')
                                ->icon('heroicon-o-tag')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge')->default('PAKET HEMAT SIAP PASANG'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi')->default('Pilihan Paket Bundling Fasad & Pagar Roster'),
                                    Forms\Components\Textarea::make('subtitle')->label('Sub-judul')->default('Hemat biaya dan praktis tanpa repot hitung satuan. Sudah termasuk rekomendasi perekat dan proteksi kirim.'),
                                    static::alignmentSelect('center'),
                                    static::bgThemeSelect('white'),
                                    Forms\Components\Repeater::make('packages')
                                        ->label('Daftar Paket')
                                        ->schema([
                                            Forms\Components\TextInput::make('name')->label('Nama Paket')->required(),
                                            Forms\Components\TextInput::make('badge')->label('Badge')->default('POPULER'),
                                            Forms\Components\Toggle::make('is_featured')->label('Highlight / Unggulan')->default(false),
                                            Forms\Components\TextInput::make('qty')->label('Jumlah Roster')->placeholder('150 Pcs Roster')->required(),
                                            Forms\Components\TextInput::make('coverage')->label('Estimasi Luas / Kegunaan')->placeholder('±6 m² Dinding'),
                                            Forms\Components\Repeater::make('features')
                                                ->label('Poin Keuntungan')
                                                ->simple(Forms\Components\TextInput::make('item')->required()),
                                            Forms\Components\TextInput::make('button_text')->label('Teks Tombol')->default('Pesan Paket Ini'),
                                            Forms\Components\TextInput::make('button_url')->label('Link Tombol (WA / Checkout)'),
                                        ])
                                        ->columns(2),
                                ]),

                            Forms\Components\Builder\Block::make('technical_specs')
                                ->label('[3. Pabrik] 📐 Spesifikasi Teknis & Dimensi Roster')
                                ->icon('heroicon-o-wrench-screwdriver')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge')->default('SPESIFIKASI STANDAR PABRIK'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi')->default('Data Teknis & Presisi Dimensi Roster'),
                                    Forms\Components\Textarea::make('subtitle')->label('Sub-judul')->default('Standar modul loster modern 20x20x10 cm dengan kebutuhan 25 pcs/m² untuk perhitungan gambar kerja dan RAB proyek dinding ventilasi.'),
                                    static::alignmentSelect('center'),
                                    static::bgThemeSelect('dark'),
                                    Forms\Components\Section::make('Diagram & Dimensi Modul (Sisi Kiri)')
                                        ->schema([
                                            Forms\Components\TextInput::make('dimension_label')->label('Label Dimensi')->default('DIMENSI MODUL STANDAR'),
                                            Forms\Components\TextInput::make('dimension_value')->label('Ukuran Dimensi')->default('20 × 20 × 10 cm'),
                                            Forms\Components\TextInput::make('dimension_tolerance')->label('Keterangan Toleransi')->default('Toleransi Presisi Cetakan: ± 0.5 mm'),
                                            Forms\Components\FileUpload::make('dimension_image_upload')
                                                ->label('Upload Custom Ilustrasi / Gambar Modul')
                                                ->acceptedFileTypes(['image/*'])
                                                ->directory('pages/specs')
                                                ->imagePreviewHeight('100')
                                                ->helperText('Opsional: Kosongkan jika ingin memakai diagram modul 4 lubang bawaan sistem.'),
                                        ])
                                        ->columns(2)
                                        ->collapsed(),
                                    Forms\Components\Repeater::make('specs')
                                        ->label('Daftar Rincian Spesifikasi (Sisi Kanan)')
                                        ->schema([
                                            Forms\Components\TextInput::make('label')->label('Kategori / Label')->required(),
                                            Forms\Components\TextInput::make('value')->label('Nilai / Spesifikasi')->required(),
                                            Forms\Components\TextInput::make('description')->label('Keterangan Singkat'),
                                        ])
                                        ->columns(3)
                                        ->default([
                                            ['label' => 'Kepadatan & Kekuatan', 'value' => 'Cetak Tumbuk Padat & Keras', 'description' => 'Padat tanpa rongga, tahan cuaca & benturan.'],
                                            ['label' => 'Bobot Rata-Rata', 'value' => '± 4.0 – 4.5 kg / pcs', 'description' => 'Material padat, kokoh dan tidak getas.'],
                                            ['label' => 'Kebutuhan per m²', 'value' => '25 Keping (pcs)', 'description' => 'Perhitungan baku luas m² dinding.'],
                                            ['label' => 'Finishing Permukaan', 'value' => 'Halus 2 Muka (Depan & Belakang)', 'description' => 'Rapi dari sisi luar maupun dalam.'],
                                            ['label' => 'Ketahanan Cuaca', 'value' => '100% Tahan UV & Hujan', 'description' => 'Bebas lumut dengan pelapis coating.'],
                                            ['label' => 'Pilihan Varian Warna', 'value' => 'Abu-Abu, Putih, Terakota', 'description' => 'Warna asli material tanpa pewarna luntur.'],
                                        ]),
                                ]),

                            Forms\Components\Builder\Block::make('cta')
                                ->label('[7. Kontak] 🚀 Call To Action Multi-Tombol (CTA Banner)')
                                ->icon('heroicon-o-cursor-arrow-rays')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Teks Badge Atas')->default('KONSULTASI GRATIS & PENAWARAN'),
                                    Forms\Components\TextInput::make('title')->label('Judul Utama CTA')->default('Wujudkan Rumah Impian & Fasad Megah dengan Sentuhan Roster Modern')->required(),
                                    Forms\Components\Textarea::make('subtitle')->label('Sub-judul / Narasi Penjelasan')->rows(2)->default('Dapatkan harga pabrik tangan pertama, sampel produk fisik gratis, dan perhitungan kebutuhan presisi langsung dari spesialis pabrik IndoRoster.'),
                                    static::alignmentSelect('center'),
                                    static::bgThemeSelect('dark'),
                                    Forms\Components\Repeater::make('buttons')
                                        ->label('Daftar Tombol Aksi (Bisa Tambah Berbagai Tombol)')
                                        ->schema([
                                            Forms\Components\TextInput::make('text')->label('Teks Tombol')->required(),
                                            Forms\Components\TextInput::make('url')->label('Link Target (URL)')->placeholder('Contoh: https://wa.me/... atau /katalog atau /register')->required(),
                                            Forms\Components\Select::make('style')
                                                ->label('Gaya Warna Tombol')
                                                ->options([
                                                    'primary' => '🔥 Oranye / Terra (Warna Utama)',
                                                    'whatsapp' => '💬 Hijau WhatsApp (Menonjol)',
                                                    'secondary' => '⚪ Outline / Border Elegan',
                                                    'dark' => '🌑 Hitam / Slate Pekat',
                                                    'white' => '✨ Putih Kontras',
                                                ])
                                                ->default('primary'),
                                            Forms\Components\Select::make('icon')
                                                ->label('Icon Tombol')
                                                ->options([
                                                    'none' => 'Tanpa Icon',
                                                    'whatsapp' => 'WhatsApp',
                                                    'catalog' => 'Katalog / Grid',
                                                    'user' => 'User / Daftar Akun',
                                                    'truck' => 'Truk / Pengiriman',
                                                    'calculator' => 'Kalkulator',
                                                    'arrow' => 'Panah Kanan (→)',
                                                    'phone' => 'Telepon',
                                                ])
                                                ->default('none'),
                                            Forms\Components\Toggle::make('is_new_tab')->label('Buka di Tab Baru (_blank)')->default(false),
                                        ])
                                        ->columns(2)
                                        ->default([
                                            ['text' => 'Konsultasi Sales via WhatsApp', 'url' => 'https://wa.me/6281389709847', 'style' => 'whatsapp', 'icon' => 'whatsapp', 'is_new_tab' => true],
                                            ['text' => 'Lihat Katalog Produk', 'url' => '/katalog', 'style' => 'secondary', 'icon' => 'catalog', 'is_new_tab' => false],
                                        ]),
                                    Forms\Components\TextInput::make('button_text')->label('Fallback Teks Tombol')->hidden(),
                                    Forms\Components\TextInput::make('button_url')->label('Fallback Link Tombol')->hidden(),
                                ]),

                            Forms\Components\Builder\Block::make('rich_text')
                                ->label('[7. Kontak] 📄 Teks Bebas / Artikel Narasi (Rich Text)')
                                ->icon('heroicon-o-pencil-square')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')->label('Badge (Opsional)'),
                                    Forms\Components\TextInput::make('title')->label('Judul Seksi (Opsional)'),
                                    static::alignmentSelect('left'),
                                    Forms\Components\Select::make('max_width')
                                        ->label('Lebar Maksimal Kontainer')
                                        ->options([
                                            '3xl' => 'Kompak (3xl)',
                                            '4xl' => 'Standar (4xl)',
                                            '5xl' => 'Lebar (5xl)',
                                            '7xl' => 'Penuh (7xl)',
                                        ])
                                        ->default('5xl'),
                                    static::bgThemeSelect('white'),
                                    Forms\Components\RichEditor::make('content')
                                        ->label('Isi Teks / Narasi')
                                        ->fileAttachmentsDirectory('pages/content')
                                        ->toolbarButtons([
                                            'attachFiles',
                                            'blockquote',
                                            'bold',
                                            'bulletList',
                                            'codeBlock',
                                            'h2',
                                            'h3',
                                            'italic',
                                            'link',
                                            'orderedList',
                                            'redo',
                                            'strike',
                                            'underline',
                                            'undo',
                                        ])
                                        ->columnSpanFull(),

                                    Forms\Components\Section::make('🖼️ Pengaturan Media Foto / Video (Opsional - Tanpa Koding)')
                                        ->description('Tambahkan foto atau video langsung dengan memilih posisi dan ukuran tampilan yang diinginkan.')
                                        ->schema([
                                            Forms\Components\Select::make('media_type')
                                                ->label('Jenis Media Tambahan')
                                                ->options([
                                                    'none' => 'Tanpa Media Tambahan',
                                                    'image' => '📷 Pasang Foto / Galeri Gambar',
                                                    'video' => '🎥 Pasang Video (YouTube / MP4)',
                                                ])
                                                ->default('none')
                                                ->live(),

                                            Forms\Components\FileUpload::make('images')
                                                ->label('Pilih / Upload Foto')
                                                ->directory('pages/rich-text')
                                                ->image()
                                                ->multiple()
                                                ->reorderable()
                                                ->openable()
                                                ->downloadable()
                                                ->visible(fn (Forms\Get $get) => $get('media_type') === 'image')
                                                ->columnSpanFull(),

                                            Forms\Components\Select::make('image_layout')
                                                ->label('Posisi & Penataan Foto')
                                                ->options([
                                                    'bottom' => '⬇️ Di Bawah Teks (Tengah Rapi)',
                                                    'top' => '⬆️ Di Atas Teks (Header)',
                                                    'float_left' => '◀️ Melayang di Kiri Teks (Text Wrap)',
                                                    'float_right' => '▶️ Melayang di Kanan Teks (Text Wrap)',
                                                    'grid_2' => '🔲 Grid 2 Kolom (Foto Berdampingan)',
                                                    'grid_3' => '🔳 Grid 3 Kolom (3 Foto Berjejer)',
                                                ])
                                                ->default('bottom')
                                                ->visible(fn (Forms\Get $get) => $get('media_type') === 'image'),

                                            Forms\Components\Select::make('image_size')
                                                ->label('Ukuran Foto')
                                                ->options([
                                                    'full' => 'Penuh (100% Lebar)',
                                                    'medium' => 'Sedang (Proporsional Rapi)',
                                                    'small' => 'Kecil / Kompak',
                                                ])
                                                ->default('medium')
                                                ->visible(fn (Forms\Get $get) => $get('media_type') === 'image'),

                                            Forms\Components\TextInput::make('video_url')
                                                ->label('URL Video (Link YouTube atau Link MP4)')
                                                ->placeholder('https://www.youtube.com/watch?v=... atau https://domain.com/video.mp4')
                                                ->visible(fn (Forms\Get $get) => $get('media_type') === 'video')
                                                ->columnSpanFull(),

                                            Forms\Components\Select::make('video_position')
                                                ->label('Posisi Video')
                                                ->options([
                                                    'bottom' => 'Di Bawah Teks',
                                                    'top' => 'Di Atas Teks',
                                                ])
                                                ->default('bottom')
                                                ->visible(fn (Forms\Get $get) => $get('media_type') === 'video'),
                                        ])
                                        ->columns(2)
                                        ->collapsible()
                                        ->collapsed(),
                                ]),
                            Forms\Components\Builder\Block::make('partner_cta')
                                ->label('[CTA] 🤝 Ajakan Kemitraan Proyek & Pelanggan (B2B & B2C)')
                                ->icon('heroicon-o-user-group')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')
                                        ->label('Teks Badge Atas')
                                        ->default('Kemitraan Pabrik & Pengadaan Proyek'),
                                    Forms\Components\TextInput::make('title')
                                        ->label('Judul Ajakan Kemitraan')
                                        ->default('Terkoneksi Langsung dengan Pabrik Roster IndoRoster')
                                        ->required(),
                                    Forms\Components\Textarea::make('description')
                                        ->label('Deskripsi Ajakan')
                                        ->rows(3)
                                        ->default('Solusi pengadaan roster beton arsitektural tangan pertama untuk pemilik rumah, kontraktor, arsitek, pemilik bisnis kafe, hingga developer kawasan perumahan di seluruh Indonesia.'),
                                    static::bgThemeSelect('dark'),
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('cta_text_1')
                                            ->label('Teks Tombol 1 (Daftar Akun)')
                                            ->default('Daftar Akun Mitra Sekarang'),
                                        Forms\Components\TextInput::make('cta_url_1')
                                            ->label('Link Tombol 1')
                                            ->default('/register'),
                                        Forms\Components\TextInput::make('cta_text_2')
                                            ->label('Teks Tombol 2 (WhatsApp B2B)')
                                            ->default('Konsultasi Pengadaan via WhatsApp'),
                                        Forms\Components\TextInput::make('cta_url_2')
                                            ->label('Link Tombol 2')
                                            ->placeholder('Biarkan kosong untuk otomatis ke WhatsApp Sales Proyek'),
                                    ]),
                                ]),

                            Forms\Components\Builder\Block::make('document_procurement_proof')
                                ->label('[B2B & Dokumen] 📑 Bukti Dokumen Pengadaan Resmi (Spill Surat Jalan, Invoice, Kwitansi & Uji Lab)')
                                ->icon('heroicon-o-document-check')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')
                                        ->label('Teks Badge Atas')
                                        ->default('DOKUMEN RESMI PABRIK & TRANSAKSI B2B'),
                                    Forms\Components\TextInput::make('title')
                                        ->label('Judul Seksi')
                                        ->default('Kelengkapan Dokumen Transaksi Resmi & Administrasi Pengadaan Proyek')
                                        ->required(),
                                    Forms\Components\Textarea::make('subtitle')
                                        ->label('Sub-judul / Keterangan')
                                        ->rows(2)
                                        ->default('Spill lembar dokumen pengadaan asli pabrik siap terbit cepat. Transparansi penuh untuk pelaporan SPJ proyek, tanda terima material, kwitansi bermaterai, dan verifikasi kontraktor.'),
                                    static::bgThemeSelect('white'),

                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('company_legal_name')
                                            ->label('Nama Brand / Produsen')
                                            ->default('INDOROSTER INDONESIA')
                                            ->helperText('Ditampilkan pada kop preview dokumen (Surat Jalan, Invoice, SPH).'),
                                        Forms\Components\TextInput::make('npwp_status')
                                            ->label('Keterangan Dokumen / Legalitas')
                                            ->default('Dokumen Transaksi Sah & Kwitansi Bermaterai'),
                                    ]),

                                    Forms\Components\Grid::make(3)->schema([
                                        Forms\Components\TextInput::make('quick_badge_1')->label('Badge Cepat 1')->default('⚡ Terbit Cepat 1x24 Jam'),
                                        Forms\Components\TextInput::make('quick_badge_2')->label('Badge Cepat 2')->default('📜 Stempel Basah & TTD Pabrik Asli'),
                                        Forms\Components\TextInput::make('quick_badge_3')->label('Badge Cepat 3')->default('🏢 Siap Kontraktor & Pengadaan Proyek'),
                                    ]),

                                    Forms\Components\Repeater::make('documents')
                                        ->label('Daftar Dokumen Resmi yang Ditampilkan (Spill Documents)')
                                        ->collapsible()
                                        ->itemLabel(fn (array $state): ?string => ($state['type_badge'] ?? '').' — '.($state['title'] ?? 'Dokumen'))
                                        ->schema([
                                            Forms\Components\Grid::make(3)->schema([
                                                Forms\Components\Select::make('category')
                                                    ->label('Kategori Filter Tab')
                                                    ->options([
                                                        'surat-jalan' => '🚚 Surat Jalan / Delivery Order (DO)',
                                                        'invoice' => '🧾 Invoice Penjualan & Tagihan',
                                                        'receipt' => '💰 Kwitansi Pembayaran Sah',
                                                        'bast' => '📋 Berita Acara Serah Terima (BAST)',
                                                        'tender' => '📝 Surat Penawaran Harga (SPH / Quotation)',
                                                        'uji-lab' => '🔬 Sertifikat Uji Kuat Tekan Lab SNI',
                                                    ])
                                                    ->default('surat-jalan')
                                                    ->required(),
                                                Forms\Components\TextInput::make('type_badge')
                                                    ->label('Label Badge Dokumen')
                                                    ->default('SURAT JALAN RESMI')
                                                    ->required(),
                                                Forms\Components\TextInput::make('status')
                                                    ->label('Status Kesiapan')
                                                    ->default('SIAP TERBIT BERSAMA ARMADA')
                                                    ->required(),
                                            ]),
                                            Forms\Components\TextInput::make('title')
                                                ->label('Judul Dokumen')
                                                ->default('Surat Jalan Pabrik & Delivery Order (DO)')
                                                ->required(),
                                            Forms\Components\Textarea::make('desc')
                                                ->label('Deskripsi Fungsi & Keabsahan Dokumen')
                                                ->rows(3)
                                                ->default('Diterbitkan rangkap untuk setiap armada pengiriman pabrik. Memuat rincian motif roster, jumlah keping, nomor polisi truk armada, data supir, tanda terima penerima proyek, serta stempel basah Quality Control (QC).')
                                                ->required(),
                                            Forms\Components\Grid::make(2)->schema([
                                                Forms\Components\TextInput::make('sample_no')
                                                    ->label('Format / Nomor Seri Contoh')
                                                    ->default('DO/IR/PLR/2026/0842'),
                                                Forms\Components\TextInput::make('usage')
                                                    ->label('Peruntukan Penggunaan Dokumen')
                                                    ->default('Bukti Bongkar Proyek & Security Clearance'),
                                            ]),
                                            Forms\Components\TagsInput::make('features')
                                                ->label('Poin Keunggulan & Ciri Keabsahan Dokumen (Tekan Enter)')
                                                ->placeholder('Tambahkan poin keabsahan...')
                                                ->default([
                                                    'Nomor seri surat jalan unik & barcode pelacakan',
                                                    'Daftar rincian koli & motif roster terperinci',
                                                    'Kolom tanda tangan 3 pihak (Pengirim, Supir, Penerima)',
                                                    'Stempel basah Quality Control bebas pecah',
                                                ]),
                                            Forms\Components\Section::make('Foto Contoh Dokumen Asli (Opsional - Jika Ingin Spill Foto Fisik)')
                                                ->schema([
                                                    Forms\Components\FileUpload::make('sample_image_upload')
                                                        ->label('Upload Foto/Scan Dokumen (Watermarked)')
                                                        ->image()
                                                        ->directory('pages/legal-documents')
                                                        ->live(),
                                                    Forms\Components\TextInput::make('sample_image_url')
                                                        ->label('Atau URL Gambar Dokumen')
                                                        ->live(),
                                                    static::mediaPreview('sample_image_upload', 'sample_image_url'),
                                                ])
                                                ->collapsed(),
                                        ]),

                                    Forms\Components\Section::make('Kotak Ajakan Aksi B2B (Bottom CTA)')
                                        ->schema([
                                            Forms\Components\TextInput::make('cta_title')
                                                ->label('Judul Banner CTA')
                                                ->default('Butuh Dokumen Penawaran Resmi (RAB / SPH / Faktur) Hari Ini?'),
                                            Forms\Components\Grid::make(2)->schema([
                                                Forms\Components\TextInput::make('cta_btn_text')
                                                    ->label('Teks Tombol')
                                                    ->default('Minta Dokumen Penawaran via WhatsApp'),
                                                Forms\Components\TextInput::make('cta_btn_link')
                                                    ->label('Link WhatsApp Kustom (Opsional)')
                                                    ->placeholder('Kosongkan untuk otomatis ke WhatsApp Sales B2B'),
                                            ]),
                                        ])
                                        ->collapsed(),
                                ]),

                            Forms\Components\Builder\Block::make('scanned_document_gallery')
                                ->label('[B2B & Bukti Foto] 📸 Galeri Foto Scan Dokumen Fisik Asli (Surat Jalan, Kwitansi, BAST & Uji Lab Asli)')
                                ->icon('heroicon-o-camera')
                                ->schema([
                                    Forms\Components\TextInput::make('badge')
                                        ->label('Teks Badge Atas')
                                        ->default('BUKTI FISIK & DOKUMENTASI PROYEK NYATA'),
                                    Forms\Components\TextInput::make('title')
                                        ->label('Judul Seksi')
                                        ->default('Galeri Foto Scan Dokumen & Bukti Transaksi Asli')
                                        ->required(),
                                    Forms\Components\Textarea::make('subtitle')
                                        ->label('Sub-judul / Keterangan')
                                        ->rows(2)
                                        ->default('Dokumentasi otentik lembar fisik surat jalan armada, kwitansi bertanda tangan, surat penawaran, dan hasil uji laboratorium dari pesanan proyek pelanggan kami.'),
                                    static::bgThemeSelect('slate'),

                                    Forms\Components\Repeater::make('scans')
                                        ->label('Daftar Foto Scan Dokumen Fisik')
                                        ->collapsible()
                                        ->itemLabel(fn (array $state): ?string => ($state['doc_no'] ?? '').' — '.($state['title'] ?? 'Scan Dokumen'))
                                        ->schema([
                                            Forms\Components\Grid::make(3)->schema([
                                                Forms\Components\Select::make('category')
                                                    ->label('Kategori Filter Tab')
                                                    ->options([
                                                        'surat-jalan' => '🚚 Surat Jalan Asli',
                                                        'kwitansi' => '💰 Kwitansi Bermaterai',
                                                        'uji-lab' => '🔬 Uji Kuat Tekan Lab',
                                                        'bast' => '📋 BAST Lapangan',
                                                        'sph' => '📝 SPH Penawaran',
                                                    ])
                                                    ->default('surat-jalan')
                                                    ->required(),
                                                Forms\Components\TextInput::make('tag')
                                                    ->label('Badge Tag di Foto')
                                                    ->default('✓ STEMPEL QC BASAH')
                                                    ->required(),
                                                Forms\Components\TextInput::make('doc_no')
                                                    ->label('Nomor Registrasi / Kode Dokumen')
                                                    ->placeholder('Contoh: DO/IR-PLR/2026/0412')
                                                    ->required(),
                                            ]),
                                            Forms\Components\TextInput::make('title')
                                                ->label('Judul / Nama Dokumen')
                                                ->placeholder('Contoh: Scan Surat Jalan Pengiriman 3.500 Pcs Roster BSD Serpong')
                                                ->required(),
                                            Forms\Components\Grid::make(2)->schema([
                                                Forms\Components\TextInput::make('project_name')
                                                    ->label('Nama Proyek / Lokasi / Klien')
                                                    ->placeholder('Contoh: Proyek Cluster Residensial — BSD City Tangerang')
                                                    ->required(),
                                                Forms\Components\TextInput::make('date_str')
                                                    ->label('Tanggal Terbit Dokumen')
                                                    ->placeholder('Contoh: 18 Februari 2026')
                                                    ->required(),
                                            ]),
                                            Forms\Components\Textarea::make('desc')
                                                ->label('Keterangan / Rincian Pengiriman Dokumen')
                                                ->rows(2)
                                                ->placeholder('Rincian singkat muatan koli, motif roster, atau hasil lab...'),
                                            Forms\Components\Section::make('Foto Scan Asli (Wajib Diupload atau URL)')
                                                ->schema([
                                                    Forms\Components\FileUpload::make('image_upload')
                                                        ->label('Upload File Foto Scan (Kamera / Scanner HP)')
                                                        ->image()
                                                        ->directory('pages/scanned-documents')
                                                        ->live(),
                                                    Forms\Components\TextInput::make('image_url')
                                                        ->label('Atau URL Gambar / Link Foto Dokumen')
                                                        ->placeholder('https://...')
                                                        ->live(),
                                                    static::mediaPreview('image_upload', 'image_url'),
                                                ]),
                                        ]),
                                ]),
                        ])
                        ->columnSpanFull()
                        ->collapsed(),
                ]),

            Forms\Components\Section::make('Judul & SEO Halaman (Meta Data)')
                ->description('Teks judul dan deskripsi di bawah ini akan otomatis menjadi judul utama halaman di web dan meta tag pencarian Google.')
                ->schema([
                    Forms\Components\TextInput::make('meta_title')
                        ->label('Judul Utama Halaman (Heading 1 & Title)')
                        ->placeholder('Contoh: Katalog Roster Beton & Bata Expose — Pabrik & Produsen Terpercaya')
                        ->live(onBlur: true)
                        ->hint(fn ($state): string => strlen($state ?? '').' / 60 karakter')
                        ->hintColor(fn ($state): string => match (true) {
                            strlen($state ?? '') > 60 => 'danger',
                            strlen($state ?? '') >= 40 => 'success',
                            default => 'gray',
                        })
                        ->helperText('Ideal SEO: 40–60 karakter. Teks ini tampil sebagai judul di browser dan hasil pencarian Google.'),
                    Forms\Components\Textarea::make('meta_description')
                        ->label('Deskripsi Paragraf Halaman & SEO')
                        ->rows(4)
                        ->placeholder('Contoh: Pusat katalog roster beton minimalis...')
                        ->live(onBlur: true)
                        ->hint(fn ($state): string => strlen($state ?? '').' / 160 karakter')
                        ->hintColor(fn ($state): string => match (true) {
                            strlen($state ?? '') > 160 => 'danger',
                            strlen($state ?? '') >= 120 => 'success',
                            default => 'gray',
                        })
                        ->helperText('Ideal SEO: 120–160 karakter. Lebih dari 160 karakter akan terpotong di hasil Google.'),
                ])->columns(1),
        ]);
    }

    /**
     * Reusable background theme select for Page Builder blocks.
     */
    protected static function bgThemeSelect(string $default = 'white'): Forms\Components\Select
    {
        return Forms\Components\Select::make('bg_theme')
            ->label('Tema Latar & Motif Seksi')
            ->options([
                'white' => '🌓 Otomatis Adaptif (Terang di Light Mode, Gelap di Dark Mode)',
                'slate' => '🔲 Abu-abu Beton Arsitektural (Soft Concrete Slate)',
                'pattern-light' => '🧱 Motif Grid Roster Terang (Architectural Motif Light)',
                'pattern-dark' => '🏛️ Motif Grid Roster Gelap (Luxury Pattern Dark)',
                'dark' => '⬛ Hitam Obsidian Mewah (Selalu Gelap / Dark Focus)',
                'accent' => '🟧 Terakota Khas Roster (Warm Terracotta Signature)',
                'gradient' => '🌈 Gradien Gelap Mewah (Dark Luxury Gradient)',
                'gradient-terra' => '🔥 Gradien Terakota Dinamis (Terracotta Glow)',
            ])
            ->default($default)
            ->helperText('Pilih nuansa visual untuk seksi ini. Mode Otomatis Adaptif akan mengikuti tema website.');
    }

    /**
     * Reusable content alignment select for Page Builder blocks.
     */
    protected static function alignmentSelect(string $default = 'center'): Forms\Components\Select
    {
        return Forms\Components\Select::make('alignment')
            ->label('Posisi / Perataan Konten')
            ->options([
                'left' => '⬅️ Rata Kiri (Left Aligned)',
                'center' => '↔️ Rata Tengah (Centered)',
                'right' => '➡️ Rata Kanan (Right Aligned)',
                'justify' => '⏸️ Rata Kanan-Kiri (Justified / Rata Penuh)',
            ])
            ->default($default)
            ->helperText('Pilih posisi perataan teks dan elemen judul (Kiri, Tengah, Kanan, atau Rata Penuh).');
    }

    /**
     * Reusable live media preview for Page Builder blocks.
     */
    protected static function mediaPreview(string $uploadField, ?string $urlField = null): Forms\Components\Placeholder
    {
        return Forms\Components\Placeholder::make('preview_'.$uploadField)
            ->label('Pratinjau Media (Preview)')
            ->content(function (Forms\Get $get) use ($uploadField, $urlField) {
                $upload = $get($uploadField);
                $url = $urlField ? $get($urlField) : null;

                $file = '';
                if (! empty($upload)) {
                    if (is_array($upload)) {
                        $file = array_values($upload)[0] ?? '';
                    } else {
                        $file = $upload;
                    }
                } elseif (! empty($url)) {
                    $file = $url;
                }

                if (empty($file) || ! is_string($file)) {
                    return new HtmlString('<span class="text-xs text-slate-400 italic">Belum ada file media yang diupload/diisi</span>');
                }

                if (str_contains($file, 'res.cloudinary.com')) {
                    return new HtmlString('
                        <div class="rounded-xl border border-amber-300 dark:border-amber-700/60 bg-amber-50 dark:bg-amber-950/30 p-2.5 max-w-sm">
                            <div class="text-[11px] text-amber-700 dark:text-amber-400 font-semibold flex items-center gap-1.5">
                                <span>⚠️</span> Link Cloudinary lama terdeteksi (tidak aktif). Silakan upload file baru pada kotak upload di atas atau kosongkan field URL.
                            </div>
                        </div>
                    ');
                }

                $src = str_starts_with($file, 'http') ? $file : asset('storage/'.$file);
                $ext = strtolower(pathinfo(parse_url($src, PHP_URL_PATH), PATHINFO_EXTENSION));
                $isVideo = in_array($ext, ['mp4', 'webm', 'ogg', 'mov', 'm4v', 'avi', 'mkv', '3gp', 'quicktime']) || str_contains(strtolower($src), 'video');

                if ($isVideo) {
                    return new HtmlString('
                        <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-950 p-2.5 max-w-sm shadow-sm">
                            <video src="'.e($src).'" controls preload="metadata" class="w-full rounded-lg max-h-48 bg-black"></video>
                            <div class="text-[11px] text-emerald-400 font-semibold mt-1.5 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                Video tersimpan & siap tayang ('.e(basename($file)).')
                            </div>
                        </div>
                    ');
                }

                return new HtmlString('
                    <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-2.5 max-w-sm shadow-sm">
                        <img src="'.e($src).'" class="w-full rounded-lg max-h-48 object-contain">
                        <div class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold mt-1.5 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Foto tersimpan & siap tayang ('.e(basename($file)).')
                        </div>
                    </div>
                ');
            });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('URL Link Halaman')
                    ->formatStateUsing(fn ($state, Page $record) => static::getPublicPagePath($record))
                    ->url(fn (Page $record) => static::getPublicPageUrl($record))
                    ->openUrlInNewTab()
                    ->color('primary')
                    ->weight('bold'),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->label('Terakhir Diubah')->dateTime('d M Y H:i')->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_page')
                    ->label('Buka')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('success')
                    ->url(fn (Page $record) => static::getPublicPageUrl($record))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('clone')
                    ->label('Duplikat')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('warning')
                    ->modalHeading('Duplikat / Clone Halaman untuk SEO Lokal')
                    ->modalDescription('Seluruh susunan blok builder dan gambar akan disalin otomatis 100%. Anda cukup menyesuaikan nama kota/wilayah dan keyword SEO.')
                    ->form([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Halaman Baru')
                            ->required()
                            ->default(fn (Page $record) => $record->title.' (Salinan)')
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->label('URL Slug Baru (untuk SEO Wilayah)')
                            ->required()
                            ->helperText('Contoh: roster-beton-jakarta-selatan, jual-roster-bandung, promo-roster-bekasi')
                            ->default(fn (Page $record) => $record->slug.'-salinan-'.substr(md5(uniqid()), 0, 5)),
                        Forms\Components\TextInput::make('meta_title')
                            ->label('Meta Title SEO')
                            ->default(fn (Page $record) => $record->meta_title),
                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description SEO')
                            ->rows(3)
                            ->default(fn (Page $record) => $record->meta_description),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Langsung Publikasikan / Aktifkan')
                            ->default(true),
                    ])
                    ->action(function (Page $record, array $data): void {
                        $cloned = Page::create([
                            'title' => $data['title'],
                            'slug' => Str::slug($data['slug']),
                            'content' => $record->content, // Seluruh isi blok builder ter-clone 100%!
                            'meta_title' => $data['meta_title'] ?: $record->meta_title,
                            'meta_description' => $data['meta_description'] ?: $record->meta_description,
                            'is_active' => $data['is_active'] ?? true,
                        ]);

                        Notification::make()
                            ->title('Halaman Berhasil Dikloning!')
                            ->body("Halaman '{$cloned->title}' dengan URL /page/{$cloned->slug} telah siap digunakan.")
                            ->success()
                            ->send();
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPublicPagePath(Page $record): string
    {
        $customRoutes = [
            'home' => '/',
            'tentang-kami' => '/tentang-kami',
            'kontak' => '/kontak',
            'proses-produksi' => '/proses-produksi',
            'katalog' => '/katalog',
            'gallery' => '/gallery',
            'indoroster-video' => '/video-inspirasi',
            'video-inspirasi' => '/video-inspirasi',
            'untuk-arsitek' => '/untuk-arsitek',
            'untuk-kontraktor' => '/untuk-kontraktor',
            'untuk-developer' => '/untuk-developer',
            'supplier-roster-beton' => '/supplier-roster-beton',
            'roster-beton-proyek' => '/roster-beton-proyek',
            'kalkulator-roster' => '/kalkulator-roster',
            'lokasi' => '/lokasi',
        ];

        return $customRoutes[$record->slug] ?? ('/page/'.$record->slug);
    }

    public static function getPublicPageUrl(Page $record): string
    {
        return url(static::getPublicPagePath($record));
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
