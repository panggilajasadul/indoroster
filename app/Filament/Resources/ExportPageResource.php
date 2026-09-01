<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExportPageResource\Pages;
use App\Models\ExportPage;
use App\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ExportPageResource extends Resource
{
    protected static ?string $model = ExportPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'Ekspor Global';

    protected static ?string $navigationLabel = 'Halaman Ekspor';

    protected static ?string $modelLabel = 'Halaman Ekspor';

    protected static ?int $navigationSort = 1;

    /**
     * Reusable Theme Color Selector for Sections
     */
    protected static function bgThemeSelect(string $default = 'clean_light'): Forms\Components\Select
    {
        return Forms\Components\Select::make('bg_theme')
            ->label('🎨 Tema Warna Latar & Kontras Section')
            ->options([
                'dark_charcoal' => '🌑 Solid Charcoal Luxury (Solid Arang Gelap, Bebas Silau)',
                'clean_light' => '☀️ Clean Modern Light (Putih Bersih di Light, Dark di Malam)',
                'soft_slate' => '🪨 Soft Slate Contrast (Abu-abu Lembut Kontras Tinggi)',
                'warm_terracotta' => '🧱 Warm Terracotta Accent (Sentuhan Terakota Plered)',
                'emerald_trust' => '🌿 Emerald Trust Accent (Sentuhan Hijau Ekspor)',
                'alert_red' => '⚠️ Alert Risk Tone (Khusus Peringatan Risiko Impor)',
            ])
            ->default($default)
            ->required();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('ExportPageTabs')
                ->tabs([
                    // TAB 1: INFORMASI UMUM & SEO
                    Forms\Components\Tabs\Tab::make('Informasi Negara & SEO')
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\TextInput::make('country_name')
                                    ->label('Nama Negara Tujuan')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('country_slug', Str::slug($state))),
                                Forms\Components\TextInput::make('country_slug')
                                    ->label('URL Slug (/export/{slug})')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->helperText(fn (?ExportPage $record) => $record && $record->country_slug
                                        ? new HtmlString('<a href="'.url('/export/'.$record->country_slug).'" target="_blank" class="text-terra-600 dark:text-terra-400 font-bold underline inline-flex items-center gap-1 mt-1">🔗 Buka Halaman: /export/'.$record->country_slug.'</a>')
                                        : 'Slug URL unik (contoh: uk, singapore, australia)'),
                                Forms\Components\TextInput::make('flag_emoji')
                                    ->label('Emoji Bendera')
                                    ->placeholder('🇬🇧')
                                    ->maxLength(10),
                            ]),

                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\Select::make('region')
                                    ->label('Kawasan / Regional')
                                    ->options([
                                        'ASEAN' => 'ASEAN',
                                        'Asia' => 'Asia Lainnya',
                                        'Timur Tengah' => 'Timur Tengah & Afrika Utara',
                                        'Eropa' => 'Eropa',
                                        'Amerika' => 'Amerika & Karibia',
                                        'Oseania' => 'Australia & Pasifik',
                                        'Afrika' => 'Afrika',
                                        'Global' => 'Global Lainnya',
                                    ])
                                    ->default('Asia')
                                    ->required(),
                                Forms\Components\TextInput::make('destination_port')
                                    ->label('Pelabuhan Tujuan Utama')
                                    ->placeholder('Port of Felixstowe / Southampton'),
                                Forms\Components\TextInput::make('transit_time')
                                    ->label('Estimasi Waktu Pelayaran')
                                    ->placeholder('24 – 28 Days Direct Sea Freight'),
                            ]),

                            Forms\Components\Toggle::make('is_active')
                                ->label('Aktif & Dapat Diakses Publik')
                                ->default(true),

                            Forms\Components\Section::make('Metadata SEO')
                                ->collapsed()
                                ->schema([
                                    Forms\Components\TextInput::make('meta_title')
                                        ->label('Meta Title')
                                        ->placeholder('Architectural Breeze Blocks Exporter to [Country] | IndoRoster'),
                                    Forms\Components\Textarea::make('meta_description')
                                        ->label('Meta Description')
                                        ->rows(2)
                                        ->placeholder('Discover precision 90° steel-mould architectural ventilation blocks crafted in Indonesia for [Country] projects...'),
                                ]),
                        ]),

                    // TAB 2: PAGE BUILDER MODULAR SECTIONS
                    Forms\Components\Tabs\Tab::make('Page Builder & Section Dinamis')
                        ->icon('heroicon-o-squares-plus')
                        ->schema([
                            Forms\Components\Builder::make('sections_config')
                                ->label('Susunan Blok Halaman Ekspor')
                                ->blockPickerColumns([
                                    'default' => 1,
                                    'sm' => 2,
                                    'lg' => 3,
                                ])
                                ->blockPickerWidth('5xl')
                                ->addActionLabel('Tambahkan Blok Section Baru (+)')
                                ->collapsible()
                                ->collapsed()
                                ->cloneable()
                                ->blocks([
                                    // 1. HERO BANNER
                                    Forms\Components\Builder\Block::make('hero_banner')
                                        ->label('[1. Hero] 🏰 Hero Header Banner')
                                        ->icon('heroicon-o-sparkles')
                                        ->schema([
                                            static::bgThemeSelect('dark_charcoal'),
                                            Forms\Components\TextInput::make('badge')->label('Teks Badge Atas')->default('🌐 Direct Factory Exporter — ASEAN Sea Freight'),
                                            Forms\Components\TextInput::make('headline')->label('Judul Utama (H1)')->required(),
                                            Forms\Components\Textarea::make('subheadline')->label('Deskripsi Sub-judul')->rows(3)->required(),
                                            Forms\Components\Grid::make(3)->schema([
                                                Forms\Components\Toggle::make('show_whatsapp_btn')->label('Tampilkan Tombol WhatsApp')->default(true),
                                                Forms\Components\TextInput::make('whatsapp_text')->label('Label Tombol WhatsApp')->default('WhatsApp Export Desk (+62 813-8970-9847)'),
                                                Forms\Components\Toggle::make('show_gallery_btn')->label('Tampilkan Tombol Galeri')->default(true),
                                                Forms\Components\Toggle::make('show_pdf_btn')->label('Tampilkan Tombol Unduh PDF')->default(true),
                                            ]),
                                        ]),

                                    // 2. MEDIA SHOWCASE / SPILL FOTO & VIDEO
                                    Forms\Components\Builder\Block::make('media_showcase')
                                        ->label('[2. Media] 🎬 Spill Foto Proyek & Video Inspirasi')
                                        ->icon('heroicon-o-video-camera')
                                        ->schema([
                                            static::bgThemeSelect('clean_light'),
                                            Forms\Components\TextInput::make('title')->label('Judul Section')->default('Explore the Architectural Possibilities'),
                                            Forms\Components\Textarea::make('subtitle')->label('Keterangan / Sub-judul')->rows(2)->default('Discover how decorative ventilation blocks introduce texture, shadow and airflow into real spaces.'),
                                            Forms\Components\Grid::make(3)->schema([
                                                Forms\Components\Select::make('media_source')
                                                    ->label('Sumber Media Spill')
                                                    ->options([
                                                        'gallery' => '🏛️ Galeri Proyek Arsitektur (Multi-Foto Interaktif)',
                                                        'video_inspiration' => '🎬 Video Inspirasi (Pilih Video dari Database)',
                                                        'custom_video' => '📹 Custom Video (Embed URL YouTube / Video Direct MP4)',
                                                    ])
                                                    ->default('gallery')
                                                    ->live()
                                                    ->required(),
                                                Forms\Components\Select::make('media_aspect')
                                                    ->label('📐 Orientasi Tampilan')
                                                    ->options([
                                                        'landscape' => '🖥️ Landscape (16:9 Horizontal)',
                                                        'portrait' => '📱 Portrait (9:16 Vertikal / Reels)',
                                                        'square' => '⏹️ Square (1:1 Persegi)',
                                                        'auto' => '✨ Auto Adapt (Sesuai Ukuran Asli)',
                                                    ])
                                                    ->default('landscape'),
                                                Forms\Components\TextInput::make('badge_text')->label('Teks Badge')->default('Live Architectural Projects'),
                                            ]),

                                            Forms\Components\Select::make('selected_video_id')
                                                ->label('Pilih Video Inspirasi dari Database')
                                                ->options(fn () => Gallery::whereHas('media', fn ($q) => $q->where('media_type', 'video'))->pluck('title', 'id')->toArray())
                                                ->searchable()
                                                ->visible(fn (Forms\Get $get) => $get('media_source') === 'video_inspiration'),

                                            Forms\Components\TextInput::make('custom_video_url')
                                                ->label('Tautan Video Kustom (YouTube / MP4 / CDN)')
                                                ->placeholder('https://www.youtube.com/watch?v=... atau https://domain.com/video.mp4')
                                                ->visible(fn (Forms\Get $get) => $get('media_source') === 'custom_video'),

                                            Forms\Components\Grid::make(3)->schema([
                                                Forms\Components\Toggle::make('show_bottom_cta')
                                                    ->label('Tampilkan Tombol Ajakan di Bawah Grid Foto')
                                                    ->default(true),
                                                Forms\Components\TextInput::make('bottom_cta_text')
                                                    ->label('Teks Tombol Ajakan')
                                                    ->default('📸 Jelajahi Seluruh Galeri Proyek Ekspor (100+ Foto Proyek)'),
                                                Forms\Components\TextInput::make('bottom_cta_url')
                                                    ->label('URL Tautan Tombol')
                                                    ->placeholder('/export/gallery atau https://...'),
                                            ]),
                                        ]),

                                    // 3. PROBLEM & IMPORT RISKS WARNING
                                    Forms\Components\Builder\Block::make('problem_risks')
                                        ->label('[3. Risk] ⚠️ Peringatan Risiko Impor Produk Murahan')
                                        ->icon('heroicon-o-exclamation-triangle')
                                        ->schema([
                                            static::bgThemeSelect('alert_red'),
                                            Forms\Components\TextInput::make('badge')->label('Teks Badge')->default('The Import Risks You Must Avoid'),
                                            Forms\Components\TextInput::make('title')->label('Judul Bahaya')->default('Why Cheap Wet-Cast Blocks Fail in Global Architectural Projects'),
                                            Forms\Components\Textarea::make('subtitle')->label('Deskripsi Bahaya')->rows(2),
                                            Forms\Components\Repeater::make('items')
                                                ->label('Daftar Kartu Risiko')
                                                ->schema([
                                                    Forms\Components\TextInput::make('icon')->label('Icon Emoji')->default('❌'),
                                                    Forms\Components\TextInput::make('title')->label('Nama Risiko')->required(),
                                                    Forms\Components\Textarea::make('desc')->label('Penjelasan Risiko')->rows(2)->required(),
                                                ])
                                                ->defaultItems(3)
                                                ->columns(3),
                                        ]),

                                    // 4. ARCHITECTURAL CONCEPT
                                    Forms\Components\Builder\Block::make('architectural_concept')
                                        ->label('[4. Concept] 💡 Konsep Arsitektural & Pencahayaan')
                                        ->icon('heroicon-o-light-bulb')
                                        ->schema([
                                            static::bgThemeSelect('clean_light'),
                                            Forms\Components\TextInput::make('badge')->label('Badge')->default('Architectural Materiality'),
                                            Forms\Components\TextInput::make('title')->label('Judul Konsep')->default('Architectural Materials Designed to Create Light, Air and Privacy'),
                                            Forms\Components\Textarea::make('subtitle')->label('Deskripsi Konsep')->rows(3),
                                        ]),

                                    // 5. PRODUCTS SHOWCASE
                                    Forms\Components\Builder\Block::make('products_showcase')
                                        ->label('[5. Products] 🧱 Showcase Koleksi Motif Roster')
                                        ->icon('heroicon-o-squares-2x2')
                                        ->schema([
                                            static::bgThemeSelect('clean_light'),
                                            Forms\Components\TextInput::make('badge')->label('Badge')->default('Modular Precision Motifs'),
                                            Forms\Components\TextInput::make('title')->label('Judul Showcase')->default('Explore Our Modular Architectural Patterns'),
                                            Forms\Components\Textarea::make('subtitle')->label('Deskripsi')->rows(2),
                                            Forms\Components\Grid::make(2)->schema([
                                                Forms\Components\Toggle::make('show_filter')->label('Tampilkan Filter Kategori & Pencarian')->default(true),
                                                Forms\Components\TextInput::make('per_page')->label('Jumlah Produk Per Halaman')->numeric()->default(8),
                                            ]),
                                        ]),

                                    // 6. FACTORY HERITAGE & CRAFTSMANSHIP (DENGAN FOTO & VIDEO)
                                    Forms\Components\Builder\Block::make('factory_heritage')
                                        ->label('[6. Factory] 🏭 Narasi Pengrajin Plered & Presisi Baja (Foto & Video)')
                                        ->icon('heroicon-o-building-office-2')
                                        ->schema([
                                            static::bgThemeSelect('dark_charcoal'),
                                            Forms\Components\TextInput::make('badge')->label('Badge')->default('Heritage of Indonesian Stonemasonry'),
                                            Forms\Components\TextInput::make('title')->label('Judul Pabrik')->default('Centenary Indonesian Craftsmanship Meets Industrial Steel Precision'),
                                            Forms\Components\Textarea::make('subtitle')->label('Narasi Sejarah & Kualitas')->rows(4),

                                            // Upload Foto & Video Pabrik
                                            Forms\Components\Grid::make(3)->schema([
                                                Forms\Components\FileUpload::make('factory_image')
                                                    ->label('📸 Unggah Foto Pabrik / Pengrajin')
                                                    ->image()
                                                    ->directory('export-factory')
                                                    ->imageEditor(),
                                                Forms\Components\TextInput::make('factory_video_url')
                                                    ->label('🎬 Tautan Video Pabrik (YouTube / MP4)')
                                                    ->placeholder('https://www.youtube.com/watch?v=...'),
                                                Forms\Components\Select::make('media_aspect')
                                                    ->label('📐 Orientasi Media')
                                                    ->options([
                                                        'landscape' => '🖥️ Landscape (16:9 / 4:3)',
                                                        'portrait' => '📱 Portrait (9:16 / 3:4)',
                                                        'square' => '⏹️ Square (1:1)',
                                                    ])
                                                    ->default('landscape'),
                                            ]),

                                            Forms\Components\Grid::make(4)->schema([
                                                Forms\Components\TextInput::make('stat_years')->label('Angka 1')->default('100+ Yrs'),
                                                Forms\Components\TextInput::make('stat_years_label')->label('Label 1')->default('Plered Craft Heritage'),
                                                Forms\Components\TextInput::make('stat_tolerance')->label('Angka 2')->default('< 1 mm'),
                                                Forms\Components\TextInput::make('stat_tolerance_label')->label('Label 2')->default('Steel Mould Tolerance'),
                                                Forms\Components\TextInput::make('stat_cooling')->label('Angka 3')->default('40%'),
                                                Forms\Components\TextInput::make('stat_cooling_label')->label('Label 3')->default('Passive Solar Cooling'),
                                                Forms\Components\TextInput::make('stat_reach')->label('Angka 4')->default('110'),
                                                Forms\Components\TextInput::make('stat_reach_label')->label('Label 4')->default('Global Export Destinations'),
                                            ]),
                                        ]),

                                    // 7. SPILL PROSES PRODUKSI (BARU)
                                    Forms\Components\Builder\Block::make('production_process_spill')
                                        ->label('[7. Production Spill] ⚙️ Spill Proses Produksi Pabrik Plered (Foto & Video)')
                                        ->icon('heroicon-o-cog-6-tooth')
                                        ->schema([
                                            static::bgThemeSelect('clean_light'),
                                            Forms\Components\TextInput::make('badge')->label('Badge')->default('Authentic Manufacturing Process'),
                                            Forms\Components\TextInput::make('title')->label('Judul Spill Produksi')->default('How We Manufacture High-Density Breeze Blocks'),
                                            Forms\Components\Textarea::make('subtitle')->label('Deskripsi Produksi')->rows(2)->default('Step-by-step glimpse into our semi-dry compaction, precision steel moulding, and strict curing process at Plered, Purwakarta.'),

                                            Forms\Components\Grid::make(4)->schema([
                                                Forms\Components\FileUpload::make('process_main_image')
                                                    ->label('📸 Foto Utama Produksi')
                                                    ->image()
                                                    ->directory('export-production'),
                                                Forms\Components\FileUpload::make('process_main_video_file')
                                                    ->label('🎬 Unggah Berkas Video')
                                                    ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime', 'video/ogg'])
                                                    ->directory('export-production-videos')
                                                    ->maxSize(51200),
                                                Forms\Components\TextInput::make('process_video_url')
                                                    ->label('🔗 Tautan Video (YouTube / MP4)')
                                                    ->placeholder('https://www.youtube.com/watch?v=...'),
                                                Forms\Components\Select::make('main_media_aspect')
                                                    ->label('📐 Orientasi Media Utama')
                                                    ->options([
                                                        'landscape' => '🖥️ Landscape (16:9)',
                                                        'portrait' => '📱 Portrait (9:16)',
                                                        'auto' => '✨ Auto Adapt (Otomatis)',
                                                    ])
                                                    ->default('landscape'),
                                            ]),

                                            Forms\Components\Repeater::make('showcase_videos')
                                                ->label('🎬 Galeri Multi-Video Portrait (Bisa Unggah Banyak Video Berjejer Rapi Kesamping)')
                                                ->schema([
                                                    Forms\Components\TextInput::make('title')->label('Judul Video / Caption')->placeholder('Cetak Tumbuk Plat Baja...'),
                                                    Forms\Components\FileUpload::make('video_file')
                                                        ->label('🎬 Berkas Video (MP4/WebM)')
                                                        ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime', 'video/ogg'])
                                                        ->directory('export-production-videos')
                                                        ->maxSize(51200),
                                                    Forms\Components\TextInput::make('video_url')
                                                        ->label('🔗 Tautan Video (YouTube / MP4)')
                                                        ->placeholder('https://...'),
                                                    Forms\Components\FileUpload::make('thumbnail')
                                                        ->label('📸 Thumbnail Poster (Opsional)')
                                                        ->image()
                                                        ->directory('export-production-thumbs'),
                                                ])
                                                ->collapsible()
                                                ->columns(2)
                                                ->defaultItems(0)
                                                ->addActionLabel('+ Tambahkan Video Portrait Berjejer'),

                                            Forms\Components\Repeater::make('steps')
                                                ->label('Tahapan Proses Produksi')
                                                ->schema([
                                                    Forms\Components\TextInput::make('step_num')->label('No. Tahap')->default('01'),
                                                    Forms\Components\TextInput::make('title')->label('Nama Tahap')->required(),
                                                    Forms\Components\Textarea::make('desc')->label('Penjelasan Teknis')->rows(2)->required(),
                                                    Forms\Components\FileUpload::make('image')
                                                        ->label('📸 Foto Tahap')
                                                        ->image()
                                                        ->directory('export-production-steps'),
                                                    Forms\Components\FileUpload::make('video_file')
                                                        ->label('🎬 Unggah Video Tahap')
                                                        ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime', 'video/ogg'])
                                                        ->directory('export-production-steps-videos')
                                                        ->maxSize(51200),
                                                    Forms\Components\TextInput::make('video_url')
                                                        ->label('🔗 Tautan Video Tahap')
                                                        ->placeholder('https://...'),
                                                    Forms\Components\Select::make('media_aspect')
                                                        ->label('📐 Orientasi Media Tahap')
                                                        ->options([
                                                            'landscape' => '🖥️ Landscape (16:9)',
                                                            'portrait' => '📱 Portrait (9:16 / 3:4)',
                                                            'auto' => '✨ Auto (Pas Sesuai Berkas)',
                                                        ])
                                                        ->default('landscape'),
                                                ])
                                                ->defaultItems(4)
                                                ->columns(3),
                                        ]),

                                    // 8. SPILL PENGIRIMAN & LOGISTIK KONTAINER (BARU)
                                    Forms\Components\Builder\Block::make('shipping_logistics_spill')
                                        ->label('[8. Shipping Spill] 📦 Spill Pengiriman & Pemuatan Kontainer (Foto & Video)')
                                        ->icon('heroicon-o-truck')
                                        ->schema([
                                            static::bgThemeSelect('clean_light'),
                                            Forms\Components\TextInput::make('badge')->label('Badge')->default('Export Packing & Ocean Logistics'),
                                            Forms\Components\TextInput::make('title')->label('Judul Spill Pengiriman')->default('Container Stuffing & Export Dispatch Process'),
                                            Forms\Components\Textarea::make('subtitle')->label('Deskripsi Pengiriman')->rows(2)->default('Watch how our breeze blocks are securely packed in heavy-duty wooden crates, strapped, and loaded into ocean containers at our factory gate.'),

                                            Forms\Components\Grid::make(4)->schema([
                                                Forms\Components\FileUpload::make('shipping_main_image')
                                                    ->label('📸 Foto Utama Pengiriman / Kontainer')
                                                    ->image()
                                                    ->directory('export-shipping'),
                                                Forms\Components\FileUpload::make('shipping_main_video_file')
                                                    ->label('🎬 Unggah Berkas Video')
                                                    ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime', 'video/ogg'])
                                                    ->directory('export-shipping-videos')
                                                    ->maxSize(51200),
                                                Forms\Components\TextInput::make('shipping_video_url')
                                                    ->label('🔗 Tautan Video (YouTube / MP4)')
                                                    ->placeholder('https://www.youtube.com/watch?v=...'),
                                                Forms\Components\Select::make('main_media_aspect')
                                                    ->label('📐 Orientasi Media Utama')
                                                    ->options([
                                                        'landscape' => '🖥️ Landscape (16:9)',
                                                        'portrait' => '📱 Portrait (9:16)',
                                                        'auto' => '✨ Auto Adapt (Otomatis)',
                                                    ])
                                                    ->default('landscape'),
                                            ]),

                                            Forms\Components\Repeater::make('showcase_videos')
                                                ->label('🎬 Galeri Multi-Video Portrait (Bisa Unggah Banyak Video Berjejer Rapi Kesamping)')
                                                ->schema([
                                                    Forms\Components\TextInput::make('title')->label('Judul Video / Caption')->placeholder('Pemuatan Kontainer 20ft...'),
                                                    Forms\Components\FileUpload::make('video_file')
                                                        ->label('🎬 Berkas Video (MP4/WebM)')
                                                        ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime', 'video/ogg'])
                                                        ->directory('export-shipping-videos')
                                                        ->maxSize(51200),
                                                    Forms\Components\TextInput::make('video_url')
                                                        ->label('🔗 Tautan Video (YouTube / MP4)')
                                                        ->placeholder('https://...'),
                                                    Forms\Components\FileUpload::make('thumbnail')
                                                        ->label('📸 Thumbnail Poster (Opsional)')
                                                        ->image()
                                                        ->directory('export-shipping-thumbs'),
                                                ])
                                                ->collapsible()
                                                ->columns(2)
                                                ->defaultItems(0)
                                                ->addActionLabel('+ Tambahkan Video Portrait Berjejer'),

                                            Forms\Components\Repeater::make('steps')
                                                ->label('Tahapan Standar Pengiriman')
                                                ->schema([
                                                    Forms\Components\TextInput::make('step_num')->label('No. Tahap')->default('01'),
                                                    Forms\Components\TextInput::make('title')->label('Nama Tahap Pengiriman')->required(),
                                                    Forms\Components\Textarea::make('desc')->label('Penjelasan Standar')->rows(2)->required(),
                                                    Forms\Components\FileUpload::make('image')
                                                        ->label('📸 Foto Dokumentasi')
                                                        ->image()
                                                        ->directory('export-shipping-steps'),
                                                    Forms\Components\FileUpload::make('video_file')
                                                        ->label('🎬 Unggah Video Pengiriman')
                                                        ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime', 'video/ogg'])
                                                        ->directory('export-shipping-steps-videos')
                                                        ->maxSize(51200),
                                                    Forms\Components\TextInput::make('video_url')
                                                        ->label('🔗 Tautan Video Pengiriman')
                                                        ->placeholder('https://...'),
                                                    Forms\Components\Select::make('media_aspect')
                                                        ->label('📐 Orientasi Media Tahap')
                                                        ->options([
                                                            'landscape' => '🖥️ Landscape (16:9)',
                                                            'portrait' => '📱 Portrait (9:16 / 3:4)',
                                                            'auto' => '✨ Auto (Pas Sesuai Berkas)',
                                                        ])
                                                        ->default('landscape'),
                                                ])
                                                ->defaultItems(4)
                                                ->columns(3),
                                        ]),

                                    // 9. FREE SAMPLE REQUEST (BARU)
                                    Forms\Components\Builder\Block::make('free_sample_request')
                                        ->label('[9. Free Sample] 🎁 Sampel Produk Gratis (Ongkir Ditanggung Pemesan)')
                                        ->icon('heroicon-o-gift')
                                        ->schema([
                                            static::bgThemeSelect('warm_terracotta'),
                                            Forms\Components\TextInput::make('badge')->label('Badge')->default('Physical Quality Verification'),
                                            Forms\Components\TextInput::make('title')->label('Judul Section Sampel')->default('Request Free Physical Sample Box Before Placing Container Orders'),
                                            Forms\Components\Textarea::make('subtitle')->label('Deskripsi Penjelasan')->rows(3)->default('We provide 100% free sample blocks (Raw Grey, White Dolomite, or Terracotta Red) so architects and contractors can test the 90° precision steel mould sharpness and material density. Sample units are free of charge; courier express air freight (DHL/FedEx/Aramex) or forwarder pickup is covered by the client.'),

                                            Forms\Components\Grid::make(3)->schema([
                                                Forms\Components\FileUpload::make('sample_image')->label('📸 Foto Paket Sample Box')->image()->directory('export-samples'),
                                                Forms\Components\FileUpload::make('sample_video_file')->label('🎬 Unggah Video Unboxing Sample')->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime', 'video/ogg'])->directory('export-samples-videos')->maxSize(51200),
                                                Forms\Components\TextInput::make('sample_video_url')->label('🔗 Tautan Video Unboxing')->placeholder('https://...'),
                                            ]),

                                            Forms\Components\Grid::make(3)->schema([
                                                Forms\Components\TextInput::make('feature_1_title')->label('Poin 1 Judul')->default('100% Free Sample Units'),
                                                Forms\Components\TextInput::make('feature_1_desc')->label('Poin 1 Penjelasan')->default('Order 1–3 physical breeze block units with zero product cost.'),

                                                Forms\Components\TextInput::make('feature_2_title')->label('Poin 2 Judul')->default('Freight Collect / Express Air'),
                                                Forms\Components\TextInput::make('feature_2_desc')->label('Poin 2 Penjelasan')->default('Worldwide express dispatch via DHL, FedEx, or your forwarder account.'),

                                                Forms\Components\TextInput::make('feature_3_title')->label('Poin 3 Judul')->default('Freight Rebate on FCL Order'),
                                                Forms\Components\TextInput::make('feature_3_desc')->label('Poin 3 Penjelasan')->default('Courier freight cost is 100% credited back when you place a 20ft/40ft FCL container order!'),
                                            ]),

                                            Forms\Components\Grid::make(2)->schema([
                                                Forms\Components\TextInput::make('cta_button_text')->label('Label Tombol WhatsApp')->default('🎁 Request Free Sample Kit via WhatsApp (+62 813-8970-9847)'),
                                                Forms\Components\TextInput::make('sample_wa_message')->label('Draft Pesan WhatsApp')->default('Hello IndoRoster, I would like to request a Free Architectural Sample Kit for our project. We will cover the express courier freight.'),
                                            ]),
                                        ]),

                                    // 10. LOGISTICS & CONTAINER SPECS
                                    Forms\Components\Builder\Block::make('logistics_specs')
                                        ->label('[10. Logistics Specs] 🚢 Spesifikasi Kapasitas Kontainer 20ft/40ft & Dokumen')
                                        ->icon('heroicon-o-document-chart-bar')
                                        ->schema([
                                            static::bgThemeSelect('clean_light'),
                                            Forms\Components\TextInput::make('badge')->label('Badge')->default('Sea Freight Logistics'),
                                            Forms\Components\TextInput::make('title')->label('Judul Logistik')->default('Container Capacity & Export Packaging Specifications'),
                                            Forms\Components\Textarea::make('subtitle')->label('Keterangan Pelabuhan & Palet')->rows(2),
                                            Forms\Components\Grid::make(2)->schema([
                                                Forms\Components\TextInput::make('cap_20ft')->label('Kapasitas 20ft FCL')->default('approx. 2,500 – 3,000 pcs (±12–14 metric tons)'),
                                                Forms\Components\TextInput::make('cap_40ft')->label('Kapasitas 40ft FCL')->default('approx. 4,500 – 5,500 pcs (±22–26 metric tons)'),
                                            ]),
                                            Forms\Components\Textarea::make('packing_desc')->label('Standar Palet & Pembungkusan Ekspor')->rows(2),
                                            Forms\Components\Textarea::make('form_d_text')->label('Keterangan Form D / Dokumen SKA')->rows(2),
                                        ]),

                                    // 10. NATURAL MATERIALS GUIDE (DENGAN FOTO & VIDEO PER BAHAN)
                                    Forms\Components\Builder\Block::make('natural_materials')
                                        ->label('[10. Materials] 🪨 Panduan 3 Bahan Baku Alami (Foto & Video)')
                                        ->icon('heroicon-o-cube')
                                        ->schema([
                                            static::bgThemeSelect('clean_light'),
                                            Forms\Components\TextInput::make('badge')->label('Badge')->default('100% Solid Natural Mineral Aggregates (Zero Spray Paint)'),
                                            Forms\Components\TextInput::make('title')->label('Judul Finis Bahan')->default('3 Authentic Material Finishes Available'),

                                            // 1. Raw Grey Sand Cement
                                            Forms\Components\Section::make('1. Abu Batu Pasir Murni (Raw Grey)')
                                                ->schema([
                                                    Forms\Components\Toggle::make('show_raw_grey')->label('Tampilkan Varian Raw Grey')->default(true),
                                                    Forms\Components\TextInput::make('raw_grey_title')->label('Judul Bahan')->default('Natural Mountain Stone Ash (Raw Grey)'),
                                                    Forms\Components\Textarea::make('raw_grey_desc')->label('Deskripsi Karakteristik')->rows(2)->default('Pure mountain stone aggregate and dense cement. Bold industrial concrete hue favored for Brutalist and modern minimalist facades.'),
                                                    Forms\Components\TextInput::make('raw_grey_best_for')->label('Rekomendasi Aplikasi')->default('Industrial cafes, brutalist walls, modern tropical carports.'),
                                                    Forms\Components\Grid::make(2)->schema([
                                                        Forms\Components\FileUpload::make('raw_grey_image')->label('Foto Tekstur/Aplikasi Raw Grey')->image()->directory('export-materials'),
                                                        Forms\Components\TextInput::make('raw_grey_video_url')->label('Tautan Video Tekstur Raw Grey (Opsional)')->placeholder('https://...'),
                                                    ]),
                                                ])->collapsible(),

                                            // 2. White Dolomite Stone
                                            Forms\Components\Section::make('2. Batu Dolomit Putih Alami (Milky White / Cream)')
                                                ->schema([
                                                    Forms\Components\Toggle::make('show_white_dolomite')->label('Tampilkan Varian Dolomit Putih')->default(true),
                                                    Forms\Components\TextInput::make('white_dolomite_title')->label('Judul Bahan')->default('Natural Milky White / Cream Dolomite Stone'),
                                                    Forms\Components\Textarea::make('white_dolomite_desc')->label('Deskripsi Karakteristik')->rows(2)->default('Crafted from pure natural white dolomite mountain stone. Yields an elegant soft milky white to warm cream mineral tone, anti-algae, and reflects solar heat.'),
                                                    Forms\Components\TextInput::make('white_dolomite_best_for')->label('Rekomendasi Aplikasi')->default('Mediterranean villas, Palm Springs pool screens, luxury resorts.'),
                                                    Forms\Components\Grid::make(2)->schema([
                                                        Forms\Components\FileUpload::make('white_dolomite_image')->label('Foto Tekstur/Aplikasi Putih Dolomit')->image()->directory('export-materials'),
                                                        Forms\Components\TextInput::make('white_dolomite_video_url')->label('Tautan Video Tekstur Putih Dolomit (Opsional)')->placeholder('https://...'),
                                                    ]),
                                                ])->collapsible(),

                                            // 3. Plered Terracotta Red Clay
                                            Forms\Components\Section::make('3. Terakota Merah Bakar Plered (Terracotta Red)')
                                                ->schema([
                                                    Forms\Components\Toggle::make('show_terracotta')->label('Tampilkan Varian Terakota')->default(true),
                                                    Forms\Components\TextInput::make('terracotta_title')->label('Judul Bahan')->default('Authentic Plered High-Fire Terracotta'),
                                                    Forms\Components\Textarea::make('terracotta_desc')->label('Deskripsi Karakteristik')->rows(2)->default('Made from selected Plered red clay and kiln-fired at high temperatures for optimal strength and porous breathability.'),
                                                    Forms\Components\TextInput::make('terracotta_best_for')->label('Rekomendasi Aplikasi')->default('Tropical resorts, rustic cafes, Spanish hacienda garden walls.'),
                                                    Forms\Components\Grid::make(2)->schema([
                                                        Forms\Components\FileUpload::make('terracotta_image')->label('Foto Tekstur/Aplikasi Terakota')->image()->directory('export-materials'),
                                                        Forms\Components\TextInput::make('terracotta_video_url')->label('Tautan Video Tekstur Terakota (Opsional)')->placeholder('https://...'),
                                                    ]),
                                                ])->collapsible(),
                                        ]),

                                    // 11. TRADE TERMS & PAYMENT SECURITY
                                    Forms\Components\Builder\Block::make('trade_terms')
                                        ->label('[11. Trade] 💳 Syarat Dagang EXW & Pembayaran T/T')
                                        ->icon('heroicon-o-banknotes')
                                        ->schema([
                                            static::bgThemeSelect('clean_light'),
                                            Forms\Components\TextInput::make('badge')->label('Badge')->default('Trade Terms & Payment Security'),
                                            Forms\Components\TextInput::make('title')->label('Judul Syarat Dagang')->default('EXW (Ex Works) Factory Terms & Secure Payment Methods'),
                                            Forms\Components\Textarea::make('subtitle')->label('Keterangan')->rows(2),
                                            Forms\Components\TextInput::make('trade_scope')->label('Lingkup Penyerahan EXW')->default('Incoterms 2020: EXW (Ex Works) — Factory Direct Handover (Plered, West Java)'),
                                            Forms\Components\TextInput::make('payment_methods')->label('Metode Transfer')->default('International Telegraphic Transfer (T/T / Swift Wire) in USD / EUR / SGD & Local IDR'),
                                            Forms\Components\Grid::make(2)->schema([
                                                Forms\Components\TextInput::make('dp_milestone')->label('Termin DP')->default('50% Down Payment (Order Lock & Production)'),
                                                Forms\Components\TextInput::make('balance_milestone')->label('Termin Pelunasan')->default('50% Balance Payment (Pre-Loading QC Inspection)'),
                                            ]),
                                        ]),

                                    // 12. FAQS ACCORDION
                                    Forms\Components\Builder\Block::make('faqs_accordion')
                                        ->label('[12. FAQ] ❓ Accordion Tanya Jawab Ekspor')
                                        ->icon('heroicon-o-question-mark-circle')
                                        ->schema([
                                            static::bgThemeSelect('clean_light'),
                                            Forms\Components\TextInput::make('badge')->label('Badge')->default('Export FAQ'),
                                            Forms\Components\TextInput::make('title')->label('Judul FAQ')->default('Frequently Asked Questions'),
                                            Forms\Components\Repeater::make('faqs')
                                                ->label('Daftar Tanya Jawab')
                                                ->schema([
                                                    Forms\Components\TextInput::make('q')->label('Pertanyaan')->required(),
                                                    Forms\Components\Textarea::make('a')->label('Jawaban')->rows(3)->required(),
                                                ])
                                                ->collapsible()
                                                ->columns(1),
                                        ]),

                                    // 13. RFQ FORM & LEAD MAGNET
                                    Forms\Components\Builder\Block::make('rfq_lead_magnet')
                                        ->label('[13. RFQ Form] 📩 Formulir Penawaran B2B & Unduh Brosur')
                                        ->icon('heroicon-o-document-text')
                                        ->schema([
                                            static::bgThemeSelect('dark_charcoal'),
                                            Forms\Components\TextInput::make('lead_magnet_title')->label('Judul Banner Brosur PDF')->default('Mencari Inspirasi Produk & Spesifikasi Penuh?'),
                                            Forms\Components\Textarea::make('lead_magnet_desc')->label('Deskripsi Brosur PDF')->rows(2),
                                            Forms\Components\TextInput::make('rfq_title')->label('Judul Form Permintaan Penawaran')->default('Request Export Quotation & Container Delivery Schedule'),
                                            Forms\Components\Textarea::make('rfq_subtitle')->label('Sub-judul Form')->rows(2),
                                        ]),

                                    // 14. CUSTOM CONTENT / FREE RICH TEXT
                                    Forms\Components\Builder\Block::make('custom_content')
                                        ->label('[14. Custom] 📝 Konten Bebas (Rich Text / Embed)')
                                        ->icon('heroicon-o-pencil-square')
                                        ->schema([
                                            static::bgThemeSelect('clean_light'),
                                            Forms\Components\TextInput::make('title')->label('Judul Seksi (Opsional)'),
                                            Forms\Components\RichEditor::make('content')->label('Isi Konten Bebas')->required(),
                                        ]),
                                ]),
                        ]),
                ])
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('flag_emoji')
                    ->label('Bendera')
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('country_name')
                    ->label('Negara Tujuan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('country_slug')
                    ->label('URL Slug')
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                Tables\Columns\TextColumn::make('region')
                    ->label('Regional')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ASEAN' => 'success',
                        'Eropa' => 'info',
                        'Timur Tengah' => 'warning',
                        'Amerika' => 'primary',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('destination_port')
                    ->label('Pelabuhan Utama')
                    ->limit(25)
                    ->placeholder('-'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('region')
                    ->label('Kawasan / Regional')
                    ->options([
                        'ASEAN' => 'ASEAN',
                        'Asia' => 'Asia Lainnya',
                        'Timur Tengah' => 'Timur Tengah & Afrika Utara',
                        'Eropa' => 'Eropa',
                        'Amerika' => 'Amerika & Karibia',
                        'Oseania' => 'Australia & Pasifik',
                        'Afrika' => 'Afrika',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Publikasi'),
            ])
            ->actions([
                Tables\Actions\Action::make('view_public')
                    ->label('Lihat')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (ExportPage $record) => url('/export/'.$record->country_slug))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListExportPages::route('/'),
            'create' => Pages\CreateExportPage::route('/create'),
            'edit' => Pages\EditExportPage::route('/{record}/edit'),
        ];
    }
}
