<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $homeBlocks = [
            [
                'type' => 'hero',
                'data' => [
                    'slider_duration' => 4500,
                    'banners' => [
                        [
                            'title' => 'Pabrik Roster Beton Minimalis Cetak Tumbuk Padat & Presisi',
                            'subtitle' => 'Produksi langsung pengrajin ahli Plered Purwakarta dengan mutu cetak padat presisi, siku 90°, kokoh tahan cuaca, dan bergaransi pengiriman ke seluruh Indonesia.',
                            'badge' => '🏭 Pabrik Tangan Pertama · Plered, Purwakarta',
                            'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765262980/2_zurmam.jpg',
                            'button_text' => 'Lihat Katalog Produk',
                            'button_url' => '/katalog',
                            'button_2_text' => 'Konsultasi Pabrik Gratis',
                            'button_2_url' => 'https://wa.me/6281389709847',
                            'alignment' => 'left',
                            'image_opacity' => 40,
                            'overlay_color' => '#020617',
                            'overlay_opacity' => 80,
                        ],
                        [
                            'title' => 'Fasad Estetik & Ventilasi Udara Tropis Modern',
                            'subtitle' => 'Mereduksi panas radiasi matahari langsung (shading) sekaligus mengoptimalkan sirkulasi udara alami untuk rumah tinggal, villa, kafe, dan bangunan komersial.',
                            'badge' => '✨ Solusi Hunian Sejuk & Hemat Energi',
                            'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259970/7_blkgfx.jpg',
                            'button_text' => 'Eksplorasi Galeri Proyek',
                            'button_url' => '/gallery',
                            'button_2_text' => 'Kalkulator Kebutuhan Dinding',
                            'button_2_url' => '/kalkulator-roster',
                            'alignment' => 'left',
                            'image_opacity' => 45,
                            'overlay_color' => '#020617',
                            'overlay_opacity' => 85,
                        ],
                    ],
                ],
            ],
            [
                'type' => 'ticker',
                'data' => [
                    'bg_theme' => 'dark',
                    'speed' => 'normal',
                    'text' => '🔥 5000+ Proyek Selesai se-Indonesia · 🏭 Pabrik Tangan Pertama Plered Purwakarta · 🛡️ Garansi Pecah Ganti Baru 100% · 🚚 Armada Truk Siap Kirim Tiap Hari · 🧱 Mutu Beton Padat K-200 Siku Presisi 90°',
                ],
            ],
            [
                'type' => 'visual_showcase',
                'data' => [
                    'title' => 'Tampilan Rumah Jadi 3x Lebih Mewah & Estetis',
                    'subtitle' => 'Sentuhan roster beton arsitektural minimalis menciptakan pencahayaan alami dramatis dan sirkulasi udara sejuk di hunian Anda.',
                    'bg_theme' => 'slate',
                ],
            ],
            [
                'type' => 'strength_test',
                'data' => [
                    'badge' => '💪 Standar Mutu K-200',
                    'title' => 'Seberapa Kuat Roster Beton Kami?',
                    'subtitle' => 'Menggunakan teknik cetak tumbuk padat hidrolik dengan campuran semen pasir pilihan, menghasilkan roster beton ekstra kokoh, minim pori rapuh, dan tahan segala cuaca ekstrem puluhan tahun.',
                    'bg_theme' => 'dark',
                ],
            ],
            [
                'type' => 'featured_products',
                'data' => [
                    'badge' => '🏆 #1 Pilihan Arsitek & Desainer',
                    'title' => 'Motif Roster Best Seller Bulan Ini',
                    'subtitle' => 'Koleksi motif roster beton minimalis terfavorit dengan angka penjualan tertinggi dan stok siap kirim langsung dari pabrik.',
                    'grid_columns' => '6',
                    'limit' => 8,
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'viral_products',
                'data' => [
                    'badge' => '🔥 Trending Hari Ini',
                    'title' => 'Produk Viral & Paling Banyak Dibeli',
                    'subtitle' => 'Rekomendasi motif roster arsitektural yang paling banyak diaplikasikan pada fasad rumah modern dan kafe kekinian.',
                    'limit' => 6,
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'why_us',
                'data' => [
                    'badge' => '💎 Keunggulan IndoRoster',
                    'title' => 'Kenapa Memilih Roster Beton Minimalis IndoRoster?',
                    'description' => 'Kami adalah produsen langsung di sentra Plered Purwakarta dengan standar kontrol kualitas ketat, harga tangan pertama, dan jaminan keamanan transaksi terpercaya.',
                    'bg_theme' => 'slate',
                    'items' => [
                        [
                            'title' => 'Kualitas Cetak Tumbuk Padat & Siku Presisi 90°',
                            'description' => 'Formula adukan beton mutu K-200 dicetak padat presisi, memudahkan tukang memasang dengan cepat dan menghemat adukan semen nat.',
                        ],
                        [
                            'title' => 'Langsung dari Pabrik Tangan Pertama',
                            'description' => 'Harga paling kompetitif tanpa rantai perantara. Melayani pesanan ritel eceran rumah tinggal hingga volume ribuan keping untuk proyek tender & developer.',
                        ],
                        [
                            'title' => 'Garansi Pecah Ganti Baru 100%',
                            'description' => 'Setiap pengiriman menggunakan armada logistik pabrik dan ekspedisi kargo resmi dilindungi garansi penggantian unit baru gratis jika ada yang rusak di perjalanan.',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'shipping_info',
                'data' => [
                    'badge' => '🚚 Logistik & Pengiriman Cepat',
                    'title' => 'Jangkauan Pengiriman Seluruh Indonesia',
                    'subtitle' => 'Pusat Jual Roster Beton Murah Jabodetabek, Bandung, Jawa Barat & Ekspedisi Kargo ke Seluruh Nusantara.',
                    'bg_theme' => 'dark',
                ],
            ],
            [
                'type' => 'document_procurement_proof',
                'data' => [
                    'badge' => '📑 Legalitas & Administrasi Sah',
                    'title' => 'Kesiapan Dokumen Pengadaan & Legalitas Proyek',
                    'subtitle' => 'Surat Jalan (DO), Invoice Komersial, Faktur Pajak PPN/PPh resmi, dan SPK siap kami terbitkan untuk kelancaran administrasi proyek B2B & kontraktor Anda.',
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'scanned_document_gallery',
                'data' => [
                    'badge' => '📸 Bukti Nyata Transaksi',
                    'title' => 'Galeri Foto Scan Dokumen Fisik Berstempel Asli',
                    'subtitle' => 'Transparansi pengiriman harian armada pabrik dan bukti tanda terima asli dari ribuan proyek pelanggan di seluruh Indonesia.',
                    'bg_theme' => 'slate',
                ],
            ],
            [
                'type' => 'social_review',
                'data' => [
                    'badge' => '📱 Viral on TikTok',
                    'title' => 'Lihat Langsung Review Para Kreator & Ahli Dekorasi',
                    'subtitle' => 'Dengarkan pengalaman langsung dari para kreator rumah dan mandor bangunan tentang kepadatan dan estetika roster beton kami. Real testimony, real quality.',
                    'bg_theme' => 'dark',
                ],
            ],
            [
                'type' => 'testimonials',
                'data' => [
                    'badge' => '⭐ Ulasan Pelanggan',
                    'title' => 'Kata Pelanggan & Pemilik Proyek Kami',
                    'subtitle' => 'Kepuasan dan cerita nyata pelanggan setelah mengaplikasikan roster beton IndoRoster pada hunian dan bangunan mereka.',
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'gallery_grid',
                'data' => [
                    'badge' => '🏛️ Transformation Stories',
                    'title' => 'Proyek yang Berbicara: Sebelum & Sesudah',
                    'subtitle' => 'Inspirasi pengaplikasian roster beton pada fasad rumah minimalis, sekat interior industrial, dan pagar estetis di berbagai kota.',
                    'bg_theme' => 'dark',
                ],
            ],
            [
                'type' => 'ugc_videos',
                'data' => [
                    'badge' => '🎬 Video Experience',
                    'title' => 'Lihat Detail Estetika Cahaya & Angin Lebih Dekat',
                    'subtitle' => 'Koleksi video estetika aliran ventilasi udara silang dan bayangan cahaya matahari yang melewati celah geometris roster kami.',
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'partner_cta',
                'data' => [
                    'badge' => '🔥 Penawaran Harga Spesial Pabrik',
                    'title' => 'Wujudkan Hunian Impian & Bangunan Estetik Anda Sekarang',
                    'description' => 'Konsultasikan kebutuhan motif, perhitungan luas dinding (m2), dan estimasi ongkos kirim armada pabrik gratis bersama tim sales IndoRoster.',
                    'bg_theme' => 'terra',
                    'cta_text_1' => 'Hubungi WhatsApp Sales Pabrik',
                    'cta_url_1' => 'https://wa.me/6281389709847',
                    'cta_text_2' => 'Lihat Katalog Produk Lengkap',
                    'cta_url_2' => '/katalog',
                ],
            ],
            [
                'type' => 'faq',
                'data' => [
                    'badge' => '❓ Bantuan & Tanya Jawab',
                    'title' => 'Pertanyaan yang Sering Diajukan (FAQ)',
                    'subtitle' => 'Jawaban lengkap seputar dimensi ukuran, kekuatan material cetak padat, cara perhitungan kebutuhan dinding, dan jaminan garansi kirim.',
                    'bg_theme' => 'white',
                ],
            ],
        ];

        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Beranda Utama (Home)',
                'content' => array_values($homeBlocks),
                'meta_title' => 'Pabrik Roster Beton Minimalis | Supplier Proyek Kirim Jabodetabek, Bandung & Seluruh Indonesia — IndoRoster',
                'meta_description' => 'Produsen resmi roster beton minimalis, loster arsitektural, dan bata expose kualitas cetak padat presisi harga pabrik. Melayani pengiriman partai kecil & proyek ribuan pcs ke Jakarta, Bogor, Depok, Tangerang, Bekasi, Bandung, Karawang, Cianjur, Cirebon & seluruh Indonesia.',
                'is_active' => true,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
