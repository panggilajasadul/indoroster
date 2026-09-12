<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromoPageResource\Pages;
use App\Models\Gallery;
use App\Models\Product;
use App\Models\PromoPage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PromoPageResource extends Resource
{
    protected static ?string $model = PromoPage::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?string $navigationGroup = 'Konten & SEO';

    protected static ?string $navigationLabel = 'Halaman Promo Iklan';

    protected static ?string $modelLabel = 'Halaman Promo';

    protected static ?string $pluralModelLabel = 'Halaman Promo Iklan';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Tabs::make('PromoPageTabs')
                ->tabs([
                    // TAB 1: PENGATURAN & SEO
                    Forms\Components\Tabs\Tab::make('1. Informasi & SEO')
                        ->icon('heroicon-o-cog-6-tooth')
                        ->schema([
                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label('Judul Halaman Promo (Internal)')
                                    ->required()
                                    ->placeholder('Contoh: Promo Pabrik Roster Beton Minimalis')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),

                                Forms\Components\TextInput::make('slug')
                                    ->label('URL Slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->placeholder('roster-pabrik')
                                    ->helperText('Akses via: /promo/{slug} atau alias /promo'),

                                Forms\Components\TextInput::make('default_city')
                                    ->label('Kota / Wilayah Default')
                                    ->default('Jabodetabek & Jawa Barat')
                                    ->placeholder('Jabodetabek & Jawa Barat / Bekasi / Bandung'),

                                Forms\Components\TextInput::make('whatsapp_number')
                                    ->label('Nomor WhatsApp Khusus Promo (Opsional)')
                                    ->placeholder('Kosongkan untuk memakai WhatsApp utama website'),

                                Forms\Components\Toggle::make('is_active')
                                    ->label('Status Aktif Halaman')
                                    ->default(true),

                                Forms\Components\Toggle::make('sections.show_navbar')
                                    ->label('Tampilkan Navbar Menu Lengkap Website')
                                    ->helperText('Aktifkan jika ingin menampilkan menu lengkap website (Katalog, Galeri, dll). Matikan untuk mode fokus konversi iklan (Header Minimalis).')
                                    ->default(false),

                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Urutan Tampilan')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\Select::make('robots')
                                    ->label('Status Indeks Google & Sitemap (Robots Meta)')
                                    ->options([
                                        'index, follow' => '🟢 Masuk Google Search & Sitemap XML (Diindeks)',
                                        'noindex, follow' => '🟡 Khusus Iklan Ads Saja (Noindex / Tidak Masuk Sitemap)',
                                    ])
                                    ->default('index, follow')
                                    ->helperText('Pilih status indeks. Jika diset "Masuk Google Search", halaman ini otomatis masuk ke sitemap.xml IndoRoster.')
                                    ->required(),

                                Forms\Components\TextInput::make('meta_title')
                                    ->label('Meta Title (Judul Tab Browser & Google Snippet)')
                                    ->placeholder('IndoRoster — Pusat Roster Beton Minimalis')
                                    ->columnSpanFull(),

                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Description (Ringkasan Snippet Google)')
                                    ->rows(2)
                                    ->placeholder('Deskripsi singkat penawaran yang muncul di hasil pencarian Google...')
                                    ->columnSpanFull(),

                                Forms\Components\Section::make('Susunan & Urutan Tampilan Section')
                                    ->collapsible()
                                    ->collapsed()
                                    ->description('Geser (Drag & Drop) untuk mengatur urutan susunan section dari atas ke bawah pada halaman promo.')
                                    ->schema([
                                        Forms\Components\Repeater::make('sections.section_order')
                                            ->label('Urutan Section (Atas ke Bawah)')
                                            ->simple(
                                                Forms\Components\Select::make('section')
                                                    ->options([
                                                        'hero' => '1. Hero & Headline Utama',
                                                        'cost_comparison' => '2. Perbandingan Biaya Toko vs Pabrik & Simulasi Hemat',
                                                        'quality_education' => '3. Edukasi Mutu (Risiko Pasaran vs Standar Pabrik)',
                                                        'catalog' => '4. Rekomendasi Motif Roster Terlaris (Katalog)',
                                                        'delivery_proof' => '5. Bukti Muatan Armada Pengiriman (🚚)',
                                                        'received_proof' => '6. Bukti Barang Tiba di Pembeli / Unloading (📦)',
                                                        'gallery' => '7. Galeri Foto Pemasangan Nyata (3 Kolom)',
                                                        'calculator' => '8. Kalkulator Kebutuhan Instan (m²)',
                                                        'shipping_coverage' => '9. Jangkauan Kirim & Promo Ongkir',
                                                        'tiering' => '10. Pilihan Paket Volume Pemesanan',
                                                        'faq' => '11. Tanya Jawab Pertanyaan Umum (FAQ)',
                                                        'cta_bottom' => '12. Banner Penawaran Terakhir (Bottom CTA)',
                                                    ])
                                                    ->required()
                                            )
                                            ->reorderable()
                                            ->collapsible()
                                            ->defaultItems(12)
                                            ->columnSpanFull(),
                                    ])
                                    ->columnSpanFull(),
                            ]),
                        ]),

                    // TAB 2: HERO & TOP STRIP
                    Forms\Components\Tabs\Tab::make('2. Hero & Top Strip')
                        ->icon('heroicon-o-sparkles')
                        ->schema([
                            Forms\Components\Section::make('Top Trust Strip (Pengumuman Paling Atas)')
                                ->collapsible()
                                ->schema([
                                    Forms\Components\Grid::make(3)->schema([
                                        Forms\Components\TextInput::make('sections.top_strip.badge')
                                            ->label('Badge Teks')
                                            ->default('🚚 PROMO GRATIS ONGKIR'),
                                        Forms\Components\TextInput::make('sections.top_strip.text')
                                            ->label('Teks Promo')
                                            ->default('Armada Langsung ke Jabodetabek, Seluruh Jawa Barat & Banten'),
                                        Forms\Components\TextInput::make('sections.top_strip.warranty')
                                            ->label('Teks Garansi')
                                            ->default('🛡️ Garansi 100% Pecah Ganti Baru di Tempat'),
                                    ]),
                                ]),

                            Forms\Components\Section::make('Hero Headline & Copywriting')
                                ->collapsible()
                                ->schema([
                                    Forms\Components\Grid::make(3)->schema([
                                        Forms\Components\TextInput::make('sections.hero.badge_1')
                                            ->label('Badge 1')
                                            ->default('🚚 Gratis Ongkir Jabodetabek, Jabar & Banten'),
                                        Forms\Components\TextInput::make('sections.hero.badge_2')
                                            ->label('Badge 2')
                                            ->default('IndoRoster — Pusat Roster Beton Minimalis'),
                                        Forms\Components\TextInput::make('sections.hero.badge_3')
                                            ->label('Badge 3')
                                            ->default('⭐ Min. Order 100 Pcs'),
                                    ]),
                                    Forms\Components\TextInput::make('sections.hero.headline')
                                        ->label('Headline Utama')
                                        ->default('Harga Pabrik Langsung Roster Beton Minimalis — Kirim Cepat ke')
                                        ->helperText('Nama kota pengunjung akan otomatis ditambahkan di akhir headline'),
                                    Forms\Components\Textarea::make('sections.hero.subtext')
                                        ->label('Paragraf Subtext Copywriting')
                                        ->rows(3)
                                        ->default('Beli di toko material bangunan harganya bisa mencapai Rp 15.000 – Rp 25.000/pcs plus ongkir mahal. Di IndoRoster, Anda dapat harga tangan pertama pabrik langsung mulai Rp 12.500/pcs dengan kualitas padat dan presisi dan Gratis Ongkir armada pabrik!'),
                                ]),

                            Forms\Components\Section::make('Visual Hero (Foto & Video Utama)')
                                ->collapsible()
                                ->schema([
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\FileUpload::make('sections.hero.image_upload')
                                            ->label('Upload Foto Utama Hero')
                                            ->image()
                                            ->directory('promo/hero')
                                            ->helperText('Format JPG, PNG, WEBP max 5MB'),
                                        Forms\Components\TextInput::make('sections.hero.image_url')
                                            ->label('Atau Link URL Gambar (Cloudinary)')
                                            ->placeholder('https://res.cloudinary.com/...'),

                                        Forms\Components\FileUpload::make('sections.hero.video_upload')
                                            ->label('Upload Video Hero (Opsional)')
                                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime'])
                                            ->directory('promo/videos')
                                            ->maxSize(50000)
                                            ->helperText('Format MP4/WEBM max 50MB'),
                                        Forms\Components\TextInput::make('sections.hero.video_url')
                                            ->label('Atau Link Video (Cloudinary / YouTube)')
                                            ->placeholder('https://...'),

                                        Forms\Components\TextInput::make('sections.hero.card_title')
                                            ->label('Teks Kartu Melayang di Atas Foto')
                                            ->default('Siku 90° Presisi Milimeter — Fasad Rapi, Tukang Pasang Cepat & Hemat Semen')
                                            ->columnSpan(2),
                                        Forms\Components\TextInput::make('sections.hero.card_price')
                                            ->label('Teks Harga Kartu')
                                            ->default('Mulai Rp 12.500/pcs'),
                                        Forms\Components\TextInput::make('sections.hero.card_badge')
                                            ->label('Badge Status Kartu')
                                            ->default('🚚 Siap Kirim'),
                                    ]),
                                ]),
                        ]),

                    // TAB 3: BUKTI PENGIRIMAN ARMADA (🚚)
                    Forms\Components\Tabs\Tab::make('3. Bukti Pengiriman')
                        ->icon('heroicon-o-truck')
                        ->schema([
                            Forms\Components\Section::make('Section Bukti Pengiriman Armada Pabrik (Tanpa Overlay Gelap)')
                                ->description('Tampilkan foto-foto muatan armada pick-up dan truk pabrik yang siap berangkat untuk membangun kepercayaan calon pembeli.')
                                ->schema([
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('sections.delivery_proof.badge')
                                            ->label('Badge Section')
                                            ->default('🚚 Real Armada Pengiriman'),
                                        Forms\Components\TextInput::make('sections.delivery_proof.title')
                                            ->label('Judul Section')
                                            ->default('Bukti Muatan Armada Pengiriman Langsung Pabrik'),
                                        Forms\Components\Textarea::make('sections.delivery_proof.subtitle')
                                            ->label('Deskripsi Singkat')
                                            ->default('Dokumentasi nyata persiapan muat & keberangkatan armada pabrik IndoRoster setiap hari menuju proyek konsumen.')
                                            ->columnSpanFull(),
                                    ]),

                                    Forms\Components\Repeater::make('sections.delivery_proof.items')
                                        ->label('Daftar Foto / Video Bukti Pengiriman (3 Kolom ke Samping)')
                                        ->schema([
                                            Forms\Components\Grid::make(2)->schema([
                                                Forms\Components\TextInput::make('title')
                                                    ->label('Judul Muatan / Armada')
                                                    ->required()
                                                    ->placeholder('Contoh: Muatan 800 Pcs Truk CDD Pabrik'),
                                                Forms\Components\TextInput::make('destination')
                                                    ->label('Tujuan Pengiriman')
                                                    ->required()
                                                    ->placeholder('Contoh: Harapan Indah, Bekasi / Buahbatu, Bandung'),
                                                Forms\Components\TextInput::make('status_badge')
                                                    ->label('Badge Status')
                                                    ->default('🚚 Armada Berangkat')
                                                    ->placeholder('🚚 Siap Kirim / Armada Berangkat'),
                                                Forms\Components\TextInput::make('caption')
                                                    ->label('Keterangan Singkat')
                                                    ->placeholder('Contoh: Roster motif minimalis pesanan kontraktor cluster.'),
                                            ]),
                                            Forms\Components\Grid::make(2)->schema([
                                                Forms\Components\FileUpload::make('image_upload')
                                                    ->label('Upload Foto Pengiriman')
                                                    ->image()
                                                    ->directory('promo/delivery')
                                                    ->columnSpan(1),
                                                Forms\Components\TextInput::make('image_url')
                                                    ->label('Atau Link URL Gambar')
                                                    ->placeholder('https://res.cloudinary.com/...')
                                                    ->columnSpan(1),
                                                Forms\Components\FileUpload::make('video_upload')
                                                    ->label('Upload Video Singkat Pengiriman (Opsional)')
                                                    ->acceptedFileTypes(['video/mp4', 'video/webm'])
                                                    ->directory('promo/delivery-videos')
                                                    ->maxSize(50000)
                                                    ->columnSpan(1),
                                                Forms\Components\TextInput::make('video_url')
                                                    ->label('Atau Link Video URL')
                                                    ->placeholder('https://...')
                                                    ->columnSpan(1),
                                            ]),
                                        ])
                                        ->collapsible()
                                        ->defaultItems(3)
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    // TAB 4: BUKTI BARANG TIBA DI PEMBELI (📦)
                    Forms\Components\Tabs\Tab::make('4. Bukti Barang Tiba')
                        ->icon('heroicon-o-check-badge')
                        ->schema([
                            Forms\Components\Section::make('Section Bukti Barang Tiba & Unloading di Pembeli (Tanpa Overlay Gelap)')
                                ->description('Tampilkan foto keping roster yang telah diturunkan di lokasi konsumen dengan kondisi utuh, rapi, dan garansi ganti di tempat.')
                                ->schema([
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('sections.received_proof.badge')
                                            ->label('Badge Section')
                                            ->default('📦 Serah Terima & Unloading'),
                                        Forms\Components\TextInput::make('sections.received_proof.title')
                                            ->label('Judul Section')
                                            ->default('Bukti Barang Tiba & Penurunan di Lokasi Pembeli'),
                                        Forms\Components\Textarea::make('sections.received_proof.subtitle')
                                            ->label('Deskripsi Singkat')
                                            ->default('Kepuasan konsumen saat keping roster diterima di depan gerbang proyek tanpa risiko pecah (garansi ganti baru di tempat).')
                                            ->columnSpanFull(),
                                    ]),

                                    Forms\Components\Repeater::make('sections.received_proof.items')
                                        ->label('Daftar Bukti Serah Terima Konsumen (3 Kolom ke Samping)')
                                        ->schema([
                                            Forms\Components\Grid::make(2)->schema([
                                                Forms\Components\TextInput::make('customer_name')
                                                    ->label('Nama Penerima / Proyek')
                                                    ->required()
                                                    ->placeholder('Contoh: Bpk. Hendra S. (Kontraktor Cluster)'),
                                                Forms\Components\TextInput::make('location')
                                                    ->label('Lokasi Proyek')
                                                    ->required()
                                                    ->placeholder('Contoh: Bekasi, Jawa Barat'),
                                                Forms\Components\TextInput::make('verification_badge')
                                                    ->label('Badge Verifikasi')
                                                    ->default('⭐ Terverifikasi Tiba 100% Utuh'),
                                                Forms\Components\TextInput::make('order_volume')
                                                    ->label('Volume Order')
                                                    ->placeholder('Contoh: 500 Pcs Roster Abu Batu'),
                                            ]),
                                            Forms\Components\Textarea::make('testimony_quote')
                                                ->label('Kutipan Review / Testimoni Konsumen')
                                                ->rows(2)
                                                ->placeholder('Contoh: "Barang sudah diturunkan rapi di lokasi, siku plat bajanya presisi dan tidak ada yang sompal."'),
                                            Forms\Components\Grid::make(2)->schema([
                                                Forms\Components\FileUpload::make('image_upload')
                                                    ->label('Upload Foto Penurunan / Barang Tiba')
                                                    ->image()
                                                    ->directory('promo/received')
                                                    ->columnSpan(1),
                                                Forms\Components\TextInput::make('image_url')
                                                    ->label('Atau Link URL Foto')
                                                    ->placeholder('https://res.cloudinary.com/...')
                                                    ->columnSpan(1),
                                                Forms\Components\FileUpload::make('video_upload')
                                                    ->label('Upload Video Unloading (Opsional)')
                                                    ->acceptedFileTypes(['video/mp4', 'video/webm'])
                                                    ->directory('promo/received-videos')
                                                    ->maxSize(50000)
                                                    ->columnSpan(1),
                                                Forms\Components\TextInput::make('video_url')
                                                    ->label('Atau Link Video URL')
                                                    ->placeholder('https://...')
                                                    ->columnSpan(1),
                                            ]),
                                        ])
                                        ->collapsible()
                                        ->defaultItems(3)
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    // TAB 5: KATALOG PRODUK & REKOMENDASI (🧱)
                    Forms\Components\Tabs\Tab::make('5. Rekomendasi Produk')
                        ->icon('heroicon-o-squares-2x2')
                        ->schema([
                            Forms\Components\Section::make('Pengaturan Rekomendasi Produk / Katalog Roster')
                                ->description('Admin dapat memilih produk spesifik dari database atau mengatur jumlah produk yang ditampilkan di halaman promo.')
                                ->schema([
                                    Forms\Components\Grid::make(3)->schema([
                                        Forms\Components\TextInput::make('sections.catalog.title')
                                            ->label('Judul Section Katalog')
                                            ->default('Pilihan Motif Roster Terlaris (20×20 cm)')
                                            ->columnSpan(2),
                                        Forms\Components\Select::make('sections.catalog.product_limit')
                                            ->label('Berapa Banyak Produk Ditampilkan?')
                                            ->options([
                                                4 => '4 Produk (1 Baris 4 Kolom)',
                                                6 => '6 Produk (2 Baris 3 Kolom)',
                                                8 => '8 Produk (2 Baris 4 Kolom)',
                                                12 => '12 Produk',
                                                16 => '16 Produk',
                                                50 => 'Semua Produk Aktif',
                                            ])
                                            ->default(8)
                                            ->helperText('Jika admin tidak memilih produk spesifik di bawah, sistem mengambil sesuai jumlah ini.'),
                                    ]),
                                    Forms\Components\Textarea::make('sections.catalog.subtitle')
                                        ->label('Deskripsi Subjudul Katalog')
                                        ->rows(2)
                                        ->default('Standar modular 20×20×10 cm (25 pcs/m²). Tersedia warna Abu Natural (Abu Batu Murni), Putih Bersih (Dolomit), dan Merah Terakota.')
                                        ->columnSpanFull(),

                                    Forms\Components\Select::make('sections.catalog.product_ids')
                                        ->label('Pilih Produk Tertentu dari Database (Opsional - Bisa Pilih Banyak)')
                                        ->multiple()
                                        ->searchable()
                                        ->preload()
                                        ->options(function () {
                                            return Product::query()
                                                ->where('is_active', true)
                                                ->orderBy('name')
                                                ->pluck('name', 'id');
                                        })
                                        ->helperText('Biarkan kosong untuk otomatis menampilkan produk terpopuler/terlaris sistem.')
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    // TAB 6: GALERI DOKUMENTASI NYATA (📸)
                    Forms\Components\Tabs\Tab::make('6. Galeri Dokumentasi')
                        ->icon('heroicon-o-photo')
                        ->schema([
                            Forms\Components\Section::make('Inspirasi Pemasangan Roster (3 Kolom ke Samping, Sisanya ke Bawah)')
                                ->description('Pilih foto inspirasi dari Galeri Proyek yang sudah ada di database, atau unggah foto dokumentasi kustom.')
                                ->schema([
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('sections.gallery.title')
                                            ->label('Judul Galeri')
                                            ->default('Inspirasi Hasil Pemasangan Roster di Lapangan'),
                                        Forms\Components\Textarea::make('sections.gallery.subtitle')
                                            ->label('Deskripsi Galeri')
                                            ->rows(2)
                                            ->default('Dokumentasi nyata hasil pasang fasad rumah tinggal, pagar villa, sekat cafe, dan ventilasi gedung di Jabodetabek & Jawa Barat.')
                                            ->columnSpanFull(),
                                    ]),

                                    Forms\Components\Select::make('sections.gallery.gallery_ids')
                                        ->label('Pilih Foto dari Galeri Pabrik (Bisa Pilih Banyak)')
                                        ->multiple()
                                        ->searchable()
                                        ->preload()
                                        ->options(function () {
                                            return Gallery::with(['media', 'product'])
                                                ->orderBy('category')
                                                ->get()
                                                ->mapWithKeys(function ($g) {
                                                    $cat = strtoupper($g->category ?: 'UMUM');
                                                    $loc = $g->location ? " ({$g->location})" : '';
                                                    $hasMedia = $g->media->isNotEmpty() || ($g->product && ! empty($g->product->primary_image));
                                                    $mediaBadge = $hasMedia ? ' [📷 Foto Siap]' : '';

                                                    return [$g->id => "[{$cat}] {$g->title}{$loc}{$mediaBadge}"];
                                                });
                                        })
                                        ->helperText('Pilih foto proyek yang ingin ditampilkan. Jika kosong, sistem otomatis menampilkan 6 galeri terpopuler.')
                                        ->columnSpanFull(),

                                    Forms\Components\Repeater::make('sections.gallery.items')
                                        ->label('Atau Tambah Foto/Video Dokumentasi Kustom')
                                        ->schema([
                                            Forms\Components\TextInput::make('title')
                                                ->label('Judul / Nama Proyek')
                                                ->required()
                                                ->placeholder('Contoh: Pagar Depan Roster Nako Sipit'),
                                            Forms\Components\TextInput::make('location')
                                                ->label('Lokasi Proyek')
                                                ->placeholder('Contoh: Bandung / Jakarta Selatan'),
                                            Forms\Components\FileUpload::make('image_upload')
                                                ->label('Upload Gambar Proyek')
                                                ->directory('promo/gallery')
                                                ->image()
                                                ->imagePreviewHeight('140')
                                                ->maxSize(5120),
                                            Forms\Components\TextInput::make('image_url')
                                                ->label('Atau URL Gambar / Cloudinary')
                                                ->placeholder('https://res.cloudinary.com/... atau https://...'),
                                            Forms\Components\FileUpload::make('video_upload')
                                                ->label('Upload Video Singkat (Opsional)')
                                                ->directory('promo/gallery_videos')
                                                ->acceptedFileTypes(['video/*'])
                                                ->maxSize(20480),
                                            Forms\Components\TextInput::make('caption')
                                                ->label('Catatan / Keterangan')
                                                ->placeholder('Contoh: Standar Presisi IndoRoster • Terpasang Rapi')
                                                ->columnSpanFull(),
                                        ])
                                        ->columns(2)
                                        ->collapsible()
                                        ->collapsed()
                                        ->itemLabel(fn (array $state): ?string => $state['title'] ?? 'Foto Dokumentasi')
                                        ->addActionLabel('+ Tambah Foto Proyek Kustom')
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    // TAB 7: PERBANDINGAN BIAYA & SIMULASI PENGHEMATAN (💰)
                    Forms\Components\Tabs\Tab::make('7. Perbandingan Biaya')
                        ->icon('heroicon-o-banknotes')
                        ->schema([
                            Forms\Components\Section::make('Perbandingan Harga Toko Bangunan vs Pabrik')
                                ->schema([
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('sections.comparison_cost.title')
                                            ->label('Judul Perbandingan Biaya')
                                            ->default('Kenapa Beli Roster di Toko Material Bisa 2x Lipat Lebih Mahal?'),
                                        Forms\Components\TextInput::make('sections.comparison_cost.pabrik_price_start')
                                            ->label('Harga Mulai Pabrik')
                                            ->default('Mulai Rp 12.500 / pcs'),
                                    ]),
                                    Forms\Components\Textarea::make('sections.comparison_cost.subtitle')
                                        ->label('Deskripsi Singkat')
                                        ->rows(2)
                                        ->default('Toko bangunan biasa mengambil dari perantara dan menaikkan harga hingga Rp 15.000 – Rp 25.000 / pcs. Beli langsung dari IndoRoster — Pusat Roster Beton Minimalis, Anda dapat harga asli pabrik tangan pertama plus garansi aman.'),
                                ]),

                            Forms\Components\Section::make('Simulasi Penghematan Riil (Kotak Perhitungan)')
                                ->collapsible()
                                ->schema([
                                    Forms\Components\Repeater::make('sections.comparison_cost.simulations')
                                        ->label('Daftar Kotak Simulasi Penghematan')
                                        ->schema([
                                            Forms\Components\Grid::make(2)->schema([
                                                Forms\Components\TextInput::make('label')
                                                    ->label('Kebutuhan / Volume')
                                                    ->required()
                                                    ->placeholder('Contoh: Kebutuhan 300 Pcs (Fasad Kecil)'),
                                                Forms\Components\TextInput::make('badge')
                                                    ->label('Badge Khusus (Opsional)')
                                                    ->placeholder('Contoh: Paling Umum / Proyek Favorit'),
                                                Forms\Components\TextInput::make('store_price')
                                                    ->label('Harga di Toko Material')
                                                    ->default('Toko Material: Rp 5.400.000'),
                                                Forms\Components\TextInput::make('factory_price')
                                                    ->label('Harga di Pabrik IndoRoster')
                                                    ->default('Pabrik: Rp 3.750.000'),
                                                Forms\Components\TextInput::make('savings_label')
                                                    ->label('Nominal Penghematan')
                                                    ->default('HEMAT Rp 1.650.000')
                                                    ->columnSpanFull(),
                                            ]),
                                        ])
                                        ->collapsible()
                                        ->defaultItems(3)
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    // TAB 8: EDUKASI KUALITAS ROSTER (🛡️)
                    Forms\Components\Tabs\Tab::make('8. Edukasi Mutu')
                        ->icon('heroicon-o-shield-check')
                        ->schema([
                            Forms\Components\Section::make('Edukasi Kualitas Roster (Standar Pabrik vs Abal-Abal)')
                                ->schema([
                                    Forms\Components\TextInput::make('sections.comparison_quality.title')
                                        ->label('Judul Section Edukasi Mutu')
                                        ->default('Mengapa 90% Kontraktor & Arsitek Memilih Roster Beton Minimalis IndoRoster?'),
                                    Forms\Components\Textarea::make('sections.comparison_quality.subtitle')
                                        ->label('Subjudul Deskripsi')
                                        ->rows(2)
                                        ->default('Beli roster abal-abal terlihat murah beberapa ratus rupiah di awal, tapi rugi jutaan rupiah karena dinding miring dan boros semen nat.'),
                                ]),

                            Forms\Components\Grid::make(2)->schema([
                                Forms\Components\Section::make('Daftar Risiko Roster Pasaran Abal-Abal (Kiri / Merah)')
                                    ->collapsible()
                                    ->schema([
                                        Forms\Components\Repeater::make('sections.comparison_quality.bad_items')
                                            ->label('Poin Negatif Pasaran')
                                            ->schema([
                                                Forms\Components\TextInput::make('title')->label('Judul Masalah')->required(),
                                                Forms\Components\TextInput::make('desc')->label('Penjelasan Dampak')->required(),
                                            ])
                                            ->collapsible()
                                            ->defaultItems(4),
                                    ]),

                                Forms\Components\Section::make('Daftar Standar Kualitas IndoRoster (Kanan / Hijau)')
                                    ->collapsible()
                                    ->schema([
                                        Forms\Components\Repeater::make('sections.comparison_quality.good_items')
                                            ->label('Poin Keunggulan Pabrik')
                                            ->schema([
                                                Forms\Components\TextInput::make('title')->label('Judul Keunggulan')->required(),
                                                Forms\Components\TextInput::make('desc')->label('Penjelasan Manfaat')->required(),
                                            ])
                                            ->collapsible()
                                            ->defaultItems(4),
                                    ]),
                            ]),
                        ]),

                    // TAB 9: JANGKAUAN KIRIM & ONGKIR (🚚)
                    Forms\Components\Tabs\Tab::make('9. Jangkauan Kirim')
                        ->icon('heroicon-o-map-pin')
                        ->schema([
                            Forms\Components\Section::make('Jangkauan Pengiriman & Promo Ongkir Pabrik')
                                ->schema([
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('sections.shipping_coverage.badge')
                                            ->label('Badge Section')
                                            ->default('🚚 Jangkauan Kirim & Promo Ongkir'),
                                        Forms\Components\TextInput::make('sections.shipping_coverage.title')
                                            ->label('Judul Section')
                                            ->default('Gratis Ongkir Armada Pabrik ke Wilayah Anda'),
                                        Forms\Components\Textarea::make('sections.shipping_coverage.subtitle')
                                            ->label('Deskripsi')
                                            ->rows(2)
                                            ->default('Armada mobil pick-up, truk engkel, hingga truk Colt Diesel siap antar langsung dari pabrik IndoRoster ke depan gerbang proyek Anda.')
                                            ->columnSpanFull(),
                                    ]),

                                    Forms\Components\Repeater::make('sections.shipping_coverage.regions')
                                        ->label('Daftar Kartu Wilayah Pengiriman')
                                        ->schema([
                                            Forms\Components\Grid::make(2)->schema([
                                                Forms\Components\TextInput::make('region_name')->label('Nama Wilayah (Contoh: Jabodetabek)')->required(),
                                                Forms\Components\TextInput::make('eta_badge')->label('Estimasi Sampai (Contoh: 1–2 Hari Sampai)')->default('1–2 Hari Sampai'),
                                                Forms\Components\TextInput::make('promo_badge')->label('Badge Promo (Contoh: Gratis Ongkir)')->default('Gratis Ongkir'),
                                                Forms\Components\TextInput::make('sub_label')->label('Label Sub (Contoh: Wilayah Utama)')->default('Wilayah Utama'),
                                                Forms\Components\Textarea::make('coverage_cities')->label('Daftar Kota / Area')->rows(2)->required(),
                                                Forms\Components\Textarea::make('features')->label('Poin Keunggulan (Pisahkan dengan baris baru)')->rows(3)->required(),
                                            ]),
                                        ])
                                        ->collapsible()
                                        ->defaultItems(3)
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    // TAB 10: PILIHAN PAKET VOLUME (📦)
                    Forms\Components\Tabs\Tab::make('10. Paket Volume')
                        ->icon('heroicon-o-cube')
                        ->schema([
                            Forms\Components\Section::make('Pilihan Paket Volume Pemesanan')
                                ->schema([
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('sections.tiering.badge')
                                            ->label('Badge Section')
                                            ->default('Pilihan Paket Volume'),
                                        Forms\Components\TextInput::make('sections.tiering.title')
                                            ->label('Judul Section')
                                            ->default('Paket Pemesanan Roster Sesuai Skala Proyek'),
                                    ]),

                                    Forms\Components\Repeater::make('sections.tiering.tiers')
                                        ->label('Daftar Paket Volume / Skala')
                                        ->schema([
                                            Forms\Components\Grid::make(3)->schema([
                                                Forms\Components\TextInput::make('audience_tag')->label('Kategori (Contoh: Rumah Tinggal / Villa / Kontraktor)')->required(),
                                                Forms\Components\TextInput::make('package_name')->label('Nama Paket (Contoh: Paket Renovasi & Sekat)')->required(),
                                                Forms\Components\TextInput::make('volume_range')->label('Volume Pcs (Contoh: 100 – 300 Pcs)')->required(),
                                                Forms\Components\TextInput::make('badge_highlight')->label('Badge Highlight (Contoh: Paling Favorit / Opsional)')->placeholder('Paling Favorit'),
                                                Forms\Components\Toggle::make('is_featured')->label('Jadikan Paket Unggulan (Sorotan)')->default(false),
                                                Forms\Components\TextInput::make('button_text')->label('Teks Tombol WA')->default('Pesan Paket Ini (WA)'),
                                                Forms\Components\Textarea::make('description')->label('Deskripsi Paket')->rows(2)->columnSpanFull(),
                                                Forms\Components\Textarea::make('features')->label('Poin Fasilitas Paket (Pisahkan baris baru)')->rows(3)->columnSpanFull(),
                                            ]),
                                        ])
                                        ->collapsible()
                                        ->defaultItems(3)
                                        ->columnSpanFull(),
                                ]),
                        ]),

                    // TAB 11: FAQ & BOTTOM CTA (❓)
                    Forms\Components\Tabs\Tab::make('11. FAQ & Bottom CTA')
                        ->icon('heroicon-o-question-mark-circle')
                        ->schema([
                            Forms\Components\Section::make('Frequently Asked Questions (FAQ)')
                                ->collapsible()
                                ->schema([
                                    Forms\Components\Repeater::make('sections.faqs.items')
                                        ->label('Daftar Tanya Jawab')
                                        ->schema([
                                            Forms\Components\TextInput::make('q')->label('Pertanyaan')->required(),
                                            Forms\Components\Textarea::make('a')->label('Jawaban')->required()->rows(2),
                                        ])
                                        ->collapsible()
                                        ->columnSpanFull(),
                                ]),
                            Forms\Components\Section::make('Bottom Final CTA')
                                ->collapsible()
                                ->schema([
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\TextInput::make('sections.cta_bottom.title')
                                            ->label('Judul CTA Bawah')
                                            ->default('Siap Wujudkan Dinding & Fasad Mewah Hemat Biaya?'),
                                        Forms\Components\TextInput::make('sections.cta_bottom.button_text')
                                            ->label('Teks Tombol CTA')
                                            ->default('💬 Chat Sales Pabrik & Ambil Promo Gratis Ongkir'),
                                    ]),
                                    Forms\Components\Textarea::make('sections.cta_bottom.subtitle')
                                        ->label('Subjudul Penawaran')
                                        ->rows(2)
                                        ->default('Dapatkan penawaran harga tangan pertama IndoRoster — Pusat Roster Beton Minimalis + promo gratis ongkir sekarang juga.')
                                        ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Promo')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('slug')
                    ->label('URL')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn ($state) => '/promo/'.$state),

                Tables\Columns\TextColumn::make('default_city')
                    ->label('Target Wilayah')
                    ->badge()
                    ->color('success'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diupdate')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPromoPages::route('/'),
            'create' => Pages\CreatePromoPage::route('/create'),
            'edit' => Pages\EditPromoPage::route('/{record}/edit'),
        ];
    }
}
