<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ThemeSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Tampilan & Tema';

    protected static ?string $title = 'Pengaturan Tampilan & Tema (Dark/Light)';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.theme-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::whereIn('group', ['theme', 'general'])->pluck('value', 'key')->toArray();

        $this->form->fill([
            'theme_default_mode' => $settings['theme_default_mode'] ?? 'light',
            'theme_allow_user_toggle' => filter_var($settings['theme_allow_user_toggle'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'top_bar_is_active' => filter_var($settings['top_bar_is_active'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'theme_accent_color' => $settings['theme_accent_color'] ?? '#f75c20',
            'theme_navbar_style' => $settings['theme_navbar_style'] ?? 'glassmorphism',
            'theme_border_radius' => $settings['theme_border_radius'] ?? 'rounded-2xl',

            // Foto 1: B2B Wholesale Banner di Detail Produk
            'b2b_wholesale_banner_active' => filter_var($settings['b2b_wholesale_banner_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'b2b_wholesale_title' => $settings['b2b_wholesale_title'] ?? 'Order Partai Besar / Proyek (>500 pcs)?',
            'b2b_wholesale_desc' => $settings['b2b_wholesale_desc'] ?? 'Dapatkan pricelist grosir tangan pertama & jadwal kirim armada.',
            'b2b_wholesale_btn_text' => $settings['b2b_wholesale_btn_text'] ?? 'Minta Harga Proyek →',
            'b2b_wholesale_custom_link' => $settings['b2b_wholesale_custom_link'] ?? '',
            'b2b_wholesale_wa_message' => $settings['b2b_wholesale_wa_message'] ?? 'Halo Tim Sales Proyek IndoRoster, saya membutuhkan penawaran harga grosir partai besar (>500 pcs) untuk produk {nama_produk}. Mohon info pricelist volume.',

            // Media Sosial Footer
            'tiktok_url' => $settings['tiktok_url'] ?? '',
            'instagram_url' => $settings['instagram_url'] ?? '',
            'youtube_url' => $settings['youtube_url'] ?? '',

            // Foto 2: Trust Top Bar (Bar Kuning E-Commerce)
            'trust_bar_active' => filter_var($settings['trust_bar_active'] ?? true, FILTER_VALIDATE_BOOLEAN),
            'trust_bar_intro' => $settings['trust_bar_intro'] ?? 'Transaksimu jadi lebih berarti di IndoRoster!',
            'trust_bar_item1_title' => $settings['trust_bar_item1_title'] ?? 'Beli Langsung',
            'trust_bar_item1_desc' => $settings['trust_bar_item1_desc'] ?? 'Dengan berbelanja di IndoRoster, Anda membeli langsung dari sentra produksi tangan pertama di Plered, Purwakarta tanpa potongan perantara atau toko bangunan.',
            'trust_bar_item2_title' => $settings['trust_bar_item2_title'] ?? 'Garansi Pecah Ganti Baru',
            'trust_bar_item2_desc' => $settings['trust_bar_item2_desc'] ?? 'Setiap keping roster yang pecah atau rusak dalam perjalanan pengiriman oleh armada pabrik kami akan langsung diganti baru tanpa biaya tambahan.',
            'trust_bar_item3_title' => $settings['trust_bar_item3_title'] ?? 'Transaksi Dijamin Aman',
            'trust_bar_item3_desc' => $settings['trust_bar_item3_desc'] ?? 'Transaksi resmi dan terlindungi dengan penerbitan Invoice Resmi otomatis serta konfirmasi jadwal langsung oleh tim Admin WhatsApp Pabrik.',
            'trust_bar_item4_title' => $settings['trust_bar_item4_title'] ?? 'Harga Terbaik Buat Kamu',
            'trust_bar_item4_desc' => $settings['trust_bar_item4_desc'] ?? 'Dapatkan harga pabrik paling transparan untuk pemesanan partai kecil hingga ribuan keping roster cetak padat presisi.',

            // Tata Letak Grid Produk Katalog & Beranda
            'catalog_product_grid_columns' => $settings['catalog_product_grid_columns'] ?? '4',
            'home_product_grid_columns' => $settings['home_product_grid_columns'] ?? '4',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Mode Tampilan & Bar Pengumuman')
                    ->description('Tentukan mode warna bawaan website IndoRoster dan izin bagi pengunjung untuk mengganti tema.')
                    ->schema([
                        Radio::make('theme_default_mode')
                            ->label('Mode Default Website')
                            ->options([
                                'light' => '☀️ Light Mode (Terang Bersih & Elegan)',
                                'dark' => '🌙 Dark Mode (Obsidian Slate & Bronze Luxury)',
                                'system' => '💻 Auto / Mengikuti Pengaturan HP/Laptop Pengunjung',
                            ])
                            ->descriptions([
                                'light' => 'Tampilan bernuansa putih bersih, aksen abu-abu beton, dan kontras tajam.',
                                'dark' => 'Tampilan bernuansa batu obsidian hitam-slate mewah dengan aksen glowing warm terakota.',
                                'system' => 'Menyesuaikan tema terang/gelap sesuai OS browser perangkat pengunjung.',
                            ])
                            ->default('light')
                            ->required(),

                        Toggle::make('theme_allow_user_toggle')
                            ->label('Tampilkan Tombol Switcher (Matahari / Bulan) untuk Pengunjung')
                            ->helperText('Jika diaktifkan, pengunjung website dapat bebas mengganti antara Dark Mode & Light Mode melalui icon switcher di navbar & mobile drawer.')
                            ->default(true),

                        Toggle::make('top_bar_is_active')
                            ->label('Tampilkan Bar Pengumuman Paling Atas Hitam (Top Trust Strip)')
                            ->helperText('Jika dimatikan (OFF), baris hitam di atas header ("Pabrik Tangan Pertama... | Lacak Pengiriman | WA") akan disembunyikan sepenuhnya.')
                            ->default(false),
                    ])->columns(1),

                Section::make('📦 Tata Letak Grid Produk Beranda (Foto 3 vs Foto 4)')
                    ->description('Pilih ukuran kartu dan jumlah kolom produk yang tampil di halaman Beranda (Home).')
                    ->schema([
                        Radio::make('home_product_grid_columns')
                            ->label('Ukuran Kartu Produk & Jumlah Kolom')
                            ->options([
                                '4' => '🧱 Ukuran Standar (4 Kolom per Baris — Sesuai Foto 3, Kartu Lebih Besar & Nyata)',
                                '6' => '📐 Ukuran Kompak (6 Kolom per Baris — Sesuai Katalog Foto 4, Kartu Lebih Rapat & Ringkas)',
                            ])
                            ->descriptions([
                                '4' => 'Tampilan grid 4 kolom di desktop. Sangat direkomendasikan untuk menonjolkan tekstur dan foto motif roster secara detail.',
                                '6' => 'Tampilan grid 6 kolom di desktop. Memuat lebih banyak pilihan motif dalam satu layar seperti halaman katalog penuh.',
                            ])
                            ->default('4')
                            ->required(),
                    ]),

                Section::make('🏢 Banner Pengadaan Partai Besar / Proyek di Detail Produk (Foto 1)')
                    ->description('Atur banner ajakan order partai besar (>500 pcs) yang tampil di bawah tombol Beli Sekarang pada setiap halaman produk.')
                    ->schema([
                        Toggle::make('b2b_wholesale_banner_active')
                            ->label('Aktifkan / Tampilkan Banner Partai Besar (ON/OFF)')
                            ->helperText('Jika di-OFF, banner kontak sales partai besar akan disembunyikan dari seluruh halaman detail produk.')
                            ->default(true),

                        TextInput::make('b2b_wholesale_title')
                            ->label('Judul Banner')
                            ->default('Order Partai Besar / Proyek (>500 pcs)?')
                            ->required(),

                        TextInput::make('b2b_wholesale_desc')
                            ->label('Deskripsi Singkat')
                            ->default('Dapatkan pricelist grosir tangan pertama & jadwal kirim armada.')
                            ->required(),

                        TextInput::make('b2b_wholesale_btn_text')
                            ->label('Teks Tombol')
                            ->default('Minta Harga Proyek →')
                            ->required(),

                        TextInput::make('b2b_wholesale_custom_link')
                            ->label('Link Kustom Tombol (Opsional)')
                            ->placeholder('Kosongkan untuk otomatis menggunakan WhatsApp Sales Proyek')
                            ->helperText('Jika dikosongkan, tombol otomatis membuka WhatsApp Admin dengan pesan otomatis membawa nama produk yang sedang dilihat.')
                            ->columnSpanFull(),

                        Textarea::make('b2b_wholesale_wa_message')
                            ->label('✏️ Template Pesan WhatsApp Otomatis (Saat Link Kustom Kosong)')
                            ->rows(3)
                            ->placeholder('Halo Tim Sales, saya butuh harga grosir untuk produk {nama_produk}...')
                            ->helperText('Gunakan {nama_produk} sebagai variabel yang akan otomatis diganti nama produk yang sedang dilihat pembeli.')
                            ->default('Halo Tim Sales Proyek IndoRoster, saya membutuhkan penawaran harga grosir partai besar (>500 pcs) untuk produk {nama_produk}. Mohon info pricelist volume.')
                            ->columnSpanFull()
                            ->required(),
                    ])->columns(2),

                Section::make('🏷️ Bar Kuning Kepercayaan & Transaksi / Trust Top Bar (Foto 2)')
                    ->description('Atur bar kuning "Transaksimu jadi lebih berarti di IndoRoster" yang tampil di atas navbar.')
                    ->schema([
                        Toggle::make('trust_bar_active')
                            ->label('Aktifkan / Tampilkan Bar Kuning (ON/OFF)')
                            ->helperText('Jika di-OFF, bar kuning e-commerce akan disembunyikan sepenuhnya dari website.')
                            ->default(true),

                        TextInput::make('trust_bar_intro')
                            ->label('Teks Pembuka')
                            ->default('Transaksimu jadi lebih berarti di IndoRoster!')
                            ->columnSpanFull()
                            ->required(),

                        Fieldset::make('Poin 1: Beli Langsung')
                            ->schema([
                                TextInput::make('trust_bar_item1_title')->label('Judul Singkat')->default('Beli Langsung')->required(),
                                Textarea::make('trust_bar_item1_desc')->label('Teks Penjelasan Tooltip')->rows(2)->default('Dengan berbelanja di IndoRoster, Anda membeli langsung dari sentra produksi tangan pertama di Plered, Purwakarta tanpa potongan perantara atau toko bangunan.')->required(),
                            ]),

                        Fieldset::make('Poin 2: Garansi Pecah Ganti Baru')
                            ->schema([
                                TextInput::make('trust_bar_item2_title')->label('Judul Singkat')->default('Garansi Pecah Ganti Baru')->required(),
                                Textarea::make('trust_bar_item2_desc')->label('Teks Penjelasan Tooltip')->rows(2)->default('Setiap keping roster yang pecah atau rusak dalam perjalanan pengiriman oleh armada pabrik kami akan langsung diganti baru tanpa biaya tambahan.')->required(),
                            ]),

                        Fieldset::make('Poin 3: Transaksi Dijamin Aman')
                            ->schema([
                                TextInput::make('trust_bar_item3_title')->label('Judul Singkat')->default('Transaksi Dijamin Aman')->required(),
                                Textarea::make('trust_bar_item3_desc')->label('Teks Penjelasan Tooltip')->rows(2)->default('Transaksi resmi dan terlindungi dengan penerbitan Invoice Resmi otomatis serta konfirmasi jadwal langsung oleh tim Admin WhatsApp Pabrik.')->required(),
                            ]),

                        Fieldset::make('Poin 4: Harga Terbaik Buat Kamu')
                            ->schema([
                                TextInput::make('trust_bar_item4_title')->label('Judul Singkat')->default('Harga Terbaik Buat Kamu')->required(),
                                Textarea::make('trust_bar_item4_desc')->label('Teks Penjelasan Tooltip')->rows(2)->default('Dapatkan harga pabrik paling transparan untuk pemesanan partai kecil hingga ribuan keping roster cetak padat presisi.')->required(),
                            ]),
                    ])->columns(2),

                Section::make('🏬 Ukuran Card & Tata Letak Grid Produk (Katalog & Beranda)')
                    ->description('Sesuaikan ukuran kartu produk dan jumlah kolom tampilan di halaman Katalog (/katalog) dan Beranda (/).')
                    ->schema([
                        Select::make('catalog_product_grid_columns')
                            ->label('Ukuran Card Halaman Katalog (/katalog)')
                            ->options([
                                '4' => '🖼️ 4 Kolom (Ukuran Besar & Lega — Standar Foto 2)',
                                '5' => '📐 5 Kolom (Ukuran Sedang Proporsional)',
                                '6' => '🔲 6 Kolom (Ukuran Kompak / Grid Padat — Foto 1)',
                                '3' => '🔍 3 Kolom (Ukuran Ekstra Besar)',
                            ])
                            ->default('4')
                            ->helperText('Semakin sedikit kolom (misal 4 kolom), ukuran kartu dan foto produk akan semakin besar & lega.')
                            ->required(),

                        Select::make('home_product_grid_columns')
                            ->label('Ukuran Card Halaman Beranda (/)')
                            ->options([
                                '4' => '🖼️ 4 Kolom (Ukuran Besar & Lega)',
                                '5' => '📐 5 Kolom (Ukuran Sedang)',
                                '6' => '🔲 6 Kolom (Ukuran Kompak)',
                            ])
                            ->default('4')
                            ->helperText('Jumlah kolom produk di halaman utama / beranda.')
                            ->required(),
                    ])->columns(2),

                Section::make('Palet Warna Aksen Signature')
                    ->description('Warna primer aksen untuk tombol CTA, badge, border fokus, dan highlight arsitektural.')
                    ->schema([
                        ColorPicker::make('theme_accent_color')
                            ->label('Warna Aksen Kustom (Hex)')
                            ->default('#f75c20')
                            ->helperText('Default: #f75c20 (Terracotta Red/Orange khas pabrik bata & roster IndoRoster).'),

                        Select::make('theme_navbar_style')
                            ->label('Gaya Header & Navbar')
                            ->options([
                                'glassmorphism' => '✨ Glassmorphic Blur (Transparan Mewah)',
                                'solid' => '🏢 Solid Crisp (Tegas Klasik)',
                            ])
                            ->default('glassmorphism')
                            ->required(),

                        Select::make('theme_border_radius')
                            ->label('Kelengkungan Sudut Komponen (Border Radius)')
                            ->options([
                                'rounded-2xl' => 'Modern Luxury (Meliuk Lembut 16px)',
                                'rounded-xl' => 'Standard Balanced (12px)',
                                'rounded-lg' => 'Architectural Sharp (Minimalis Tegas 8px)',
                            ])
                            ->default('rounded-2xl')
                            ->required(),
                    ])->columns(3),

                Section::make('📲 Link Media Sosial Footer (TikTok, Instagram, YouTube)')
                    ->description('Atur URL profil media sosial IndoRoster yang tampil sebagai ikon di bagian bawah (footer) website.')
                    ->schema([
                        TextInput::make('tiktok_url')
                            ->label('🎵 Link TikTok')
                            ->placeholder('https://www.tiktok.com/@indoroster')
                            ->url()
                            ->helperText('Tempel link profil TikTok IndoRoster. Kosongkan jika tidak ingin menampilkan ikon TikTok.'),

                        TextInput::make('instagram_url')
                            ->label('📸 Link Instagram')
                            ->placeholder('https://www.instagram.com/indoroster')
                            ->url()
                            ->helperText('Tempel link profil Instagram IndoRoster. Kosongkan jika tidak ingin menampilkan ikon Instagram.'),

                        TextInput::make('youtube_url')
                            ->label('▶️ Link YouTube')
                            ->placeholder('https://www.youtube.com/@indoroster')
                            ->url()
                            ->helperText('Tempel link channel YouTube IndoRoster. Kosongkan jika tidak ingin menampilkan ikon YouTube.'),
                    ])->columns(3),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Pengaturan Tema')
                ->color('primary')
                ->icon('heroicon-o-check')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        // Keys yang disimpan ke group 'general' (bukan 'theme')
        $generalKeys = ['tiktok_url', 'instagram_url', 'youtube_url', 'b2b_wholesale_wa_message', 'b2b_wholesale_custom_link'];

        foreach ($data as $key => $value) {
            $group = in_array($key, $generalKeys) ? 'general' : 'theme';
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => is_bool($value) ? ($value ? '1' : '0') : (string) $value, 'group' => $group]
            );
        }

        Notification::make()
            ->title('Pengaturan Tema Berhasil Disimpan')
            ->body('Perubahan tema, link media sosial, dan template WA langsung aktif di storefront website.')
            ->success()
            ->send();
    }
}
