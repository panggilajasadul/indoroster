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
                    'slider_duration' => 5000,
                    'banners' => [],
                ],
            ],
            [
                'type' => 'ticker',
                'data' => [
                    'bg_theme' => 'dark',
                    'speed' => 'normal',
                    'text' => '5000+ Proyek Selesai · Pabrik Tangan Pertama Plered Purwakarta · Garansi Pecah Ganti Baru 100% · Pengiriman Cepat Seluruh Indonesia · Mutu Beton Padat K-200',
                ],
            ],
            [
                'type' => 'visual_showcase',
                'data' => [
                    'title' => 'Tampilan Rumah Jadi 3x Lebih Mewah',
                    'subtitle' => 'Hanya dengan sentuhan roster beton minimalis & bata expose pilihan.',
                    'bg_theme' => 'slate',
                ],
            ],
            [
                'type' => 'strength_test',
                'data' => [
                    'title' => 'Seberapa Kuat Roster Beton Kami?',
                    'subtitle' => 'Uji ketahanan beban dan pemadatan hidrolik mutu K-200.',
                    'bg_theme' => 'dark',
                ],
            ],
            [
                'type' => 'featured_products',
                'data' => [
                    'badge' => '#1 Best Seller',
                    'title' => 'Motif Best Seller Bulan Ini',
                    'subtitle' => 'Pilihan motif roster beton terfavorit arsitek dan pemilik rumah.',
                    'grid_columns' => '6',
                    'limit' => 8,
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'viral_products',
                'data' => [
                    'title' => 'Produk Viral & Paling Banyak Dibeli 🔥',
                    'subtitle' => 'Trending hari ini dengan rating tertinggi dari pembeli.',
                    'limit' => 6,
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'why_us',
                'data' => [
                    'title' => 'Kenapa Memilih Roster Beton Minimalis Indoroster?',
                    'description' => 'Kami adalah produsen langsung dengan komitmen mutu terbaik dan garansi penuh.',
                    'bg_theme' => 'slate',
                    'items' => [
                        [
                            'title' => 'Kualitas Cetak Padat Presisi',
                            'description' => 'Dibuat dengan formula semen pasir terpilih dan pemadatan hidrolik agar kokoh dan siku 90° sempurna.',
                        ],
                        [
                            'title' => 'Langsung dari Pabrik (Tangan Pertama)',
                            'description' => 'Harga paling kompetitif tanpa perantara untuk pengadaan rumah pribadi maupun proyek tender.',
                        ],
                        [
                            'title' => 'Garansi Pecah Ganti Baru 100%',
                            'description' => 'Armada truk pabrik mengantarkan pesanan Anda dengan jaminan keamanan tiba di lokasi proyek.',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'shipping_info',
                'data' => [
                    'title' => 'Jangkauan Pengiriman Seluruh Indonesia',
                    'subtitle' => 'Pusat Jual Roster Beton Murah Jabodetabek & Seluruh Indonesia.',
                    'bg_theme' => 'dark',
                ],
            ],
            [
                'type' => 'document_procurement_proof',
                'data' => [
                    'title' => 'Bukti Dokumen Pengadaan & Legalitas Resmi',
                    'subtitle' => 'Kesiapan Surat Jalan, Invoice Komersial, Faktur Pajak, dan SPK Proyek.',
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'scanned_document_gallery',
                'data' => [
                    'title' => 'Galeri Foto Scan Dokumen Fisik Asli',
                    'subtitle' => 'Bukti pengiriman harian dan surat jalan berstempel resmi pabrik.',
                    'bg_theme' => 'slate',
                ],
            ],
            [
                'type' => 'social_review',
                'data' => [
                    'title' => 'Lihat Langsung Review Kreator (Viral on TikTok)',
                    'subtitle' => 'Dengarkan pengalaman langsung dari para kreator dekorasi rumah.',
                    'bg_theme' => 'dark',
                ],
            ],
            [
                'type' => 'testimonials',
                'data' => [
                    'title' => 'Kata Pelanggan Kami',
                    'subtitle' => 'Ulasan nyata kepuasan pelanggan di seluruh Indonesia.',
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'gallery_grid',
                'data' => [
                    'title' => 'Proyek yang Berbicara (Transformation Stories)',
                    'subtitle' => 'Inspirasi pemasangan roster dari proyek nyata di seluruh Indonesia.',
                    'bg_theme' => 'dark',
                ],
            ],
            [
                'type' => 'ugc_videos',
                'data' => [
                    'title' => 'Lihat Detailnya Lebih Dekat (Visual Experience)',
                    'subtitle' => 'Koleksi video estetika ventilasi udara dan pencahayaan roster.',
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'partner_cta',
                'data' => [
                    'badge' => 'Siap Wujudkan Hunian Impian?',
                    'title' => 'Wujudkan Hunian Impian Anda Sekarang',
                    'description' => 'Konsultasikan kebutuhan motif, ukuran, dan estimasi ongkos kirim armada pabrik gratis via WhatsApp.',
                    'bg_theme' => 'terra',
                    'cta_text_1' => 'Hubungi WhatsApp Sekarang',
                    'cta_url_1' => 'https://wa.me/6281389709847',
                    'cta_text_2' => 'Lihat Katalog Dahulu',
                    'cta_url_2' => '/katalog',
                ],
            ],
            [
                'type' => 'faq',
                'data' => [
                    'title' => 'FAQ Roster Beton Minimalis',
                    'subtitle' => 'Pertanyaan yang sering diajukan seputar ukuran, motif, dan pengiriman roster.',
                    'bg_theme' => 'white',
                ],
            ],
        ];

        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Beranda Utama (Home)',
                'content' => $homeBlocks,
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
