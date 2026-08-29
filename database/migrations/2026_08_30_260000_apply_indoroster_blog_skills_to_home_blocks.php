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
                    'banners' => [
                        [
                            'title' => 'Pabrik Roster Beton Cetak Tumbuk Padat & Siku Presisi 90°',
                            'subtitle' => 'Diproduksi langsung dari sentra pengrajin Plered, Purwakarta. Formula adukan pasir silika & semen mutu K-200, hemat semen nat saat pasang, dan bergaransi pecah ganti baru 100% kirim ke seluruh Indonesia.',
                            'badge' => '🏭 Produsen Tangan Pertama · Plered, Purwakarta',
                            'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765262980/2_zurmam.jpg',
                            'button_text' => 'Lihat Katalog Motif',
                            'button_url' => '/katalog',
                            'button_2_text' => 'Konsultasi Pabrik & Sales',
                            'button_2_url' => 'https://wa.me/6281389709847',
                            'alignment' => 'left',
                            'image_opacity' => 40,
                            'overlay_color' => '#020617',
                            'overlay_opacity' => 80,
                        ],
                        [
                            'title' => 'Solusi Fasad Rumah Sejuk, Estetik & Privasi Terjaga',
                            'subtitle' => 'Mereduksi panas radiasi matahari langsung (shading) sekaligus mengoptimalkan sirkulasi udara silang (cross-ventilation). Cocok untuk fasad, pagar, partisi void, dan dinding kafe industrial.',
                            'badge' => '✨ Desain Bioklimatik Tropis',
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
                    'text' => '🔥 5000+ Proyek Terpasang se-Indonesia · 🏭 Sentra Pabrikasi Plered Purwakarta · 🛡️ Garansi Pecah Ganti Baru 100% · 🚚 Kirim Rutin Tiap Hari ke Jabodetabek & Bandung · 🧱 Mutu Beton Tumbuk K-200 Sudut Siku 90°',
                ],
            ],
            [
                'type' => 'visual_showcase',
                'data' => [
                    'badge' => '✨ Inspirasi Arsitektur',
                    'title' => 'Tampilan Rumah Jadi 3x Lebih Mewah & Berkarakter',
                    'subtitle' => 'Sentuhan roster beton arsitektural menghadirkan permainan bayangan cahaya matahari alami yang dramatis sekaligus menjaga rumah tetap adem sepanjang hari.',
                    'bg_theme' => 'slate',
                ],
            ],
            [
                'type' => 'strength_test',
                'data' => [
                    'badge' => '💪 Uji Ketahanan Material K-200',
                    'title' => 'Seberapa Kuat Roster Beton IndoRoster?',
                    'subtitle' => 'Bukan cetak basah rapuh yang mudah rompal di jalan. Kami menggunakan teknik cetak tumbuk padat hidrolik dengan densitas tinggi—menghasilkan keping roster padat, tahan benturan, minim porositas, dan tahan terpaan cuaca luar puluhan tahun.',
                    'bg_theme' => 'dark',
                ],
            ],
            [
                'type' => 'featured_products',
                'data' => [
                    'badge' => '🏆 #1 Pilihan Arsitek & Pemborong',
                    'title' => 'Motif Roster Best Seller Bulan Ini',
                    'subtitle' => 'Deretan motif roster beton minimalis terfavorit dengan tingkat kepresisian tinggi, stok produksi siap suplai, dan paling banyak dipasang untuk pagar serta dinding fasad.',
                    'grid_columns' => '6',
                    'limit' => 8,
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'viral_products',
                'data' => [
                    'badge' => '🔥 Paling Dicari Pemilik Rumah',
                    'title' => 'Produk Viral & Paling Banyak Dibeli',
                    'subtitle' => 'Koleksi motif roster arsitektural modern yang sedang tren di media sosial dan banyak diaplikasikan pada hunian bertema Japandi, Industrial, dan Tropis Modern.',
                    'limit' => 6,
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'why_us',
                'data' => [
                    'badge' => '💎 Standar Mutu Lapangan',
                    'title' => 'Kenapa Tukang & Arsitek Memilih IndoRoster?',
                    'description' => 'Kami adalah produsen langsung di sentra Plered Purwakarta yang mengerti kebutuhan teknis lapangan: dimensi presisi agar tukang hemat semen adukan, dan pasokan aman tanpa risiko mandek di tengah proyek.',
                    'bg_theme' => 'slate',
                    'items' => [
                        [
                            'title' => 'Akurasi Siku 90° & Cetak Tumbuk Padat',
                            'description' => 'Setiap keping dicetak padat presisi. Tukang di lapangan tidak perlu repot meratakan adukan semen nat tebal-tipis, sehingga dinding cepat berdiri rapi dan hemat ongkos tukang.',
                        ],
                        [
                            'title' => 'Murni Pabrik Tangan Pertama (Tanpa Perantara)',
                            'description' => 'Harga langsung dari sentra produksi Plered. Melayani pembelian eceran puluhan pcs untuk renovasi rumah hingga volume ribuan keping untuk pengadaan proyek komersial & perumahan.',
                        ],
                        [
                            'title' => 'Garansi Pecah Ganti Baru 100% di Tempat',
                            'description' => 'Jika ada unit yang retak atau pecah saat dibongkar dari truk di lokasi proyek Anda, cukup foto Surat Jalan dan kami ganti unit baru secara gratis.',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'shipping_info',
                'data' => [
                    'badge' => '🚚 Armada Logistik Pabrik',
                    'title' => 'Jangkauan Pengiriman Seluruh Indonesia',
                    'subtitle' => 'Pusat Suplai Roster Beton Murah Jabodetabek (Jakarta, Bogor, Depok, Tangerang, Bekasi), Bandung, Cianjur, Sukabumi, Karawang, Cirebon, hingga Ekspedisi Kargo ke Luar Jawa.',
                    'bg_theme' => 'dark',
                ],
            ],
            [
                'type' => 'document_procurement_proof',
                'data' => [
                    'badge' => '📑 Dokumen Pengadaan Lengkap',
                    'title' => 'Kesiapan Administrasi Sah & Faktur Pajak Resmi',
                    'subtitle' => 'Surat Jalan (DO) berstempel resmi pabrik, Invoice Komersial, Faktur Pajak PPN/PPh resmi, dan SPK siap kami terbitkan cepat untuk kebutuhan administrasi proyek kontraktor & developer.',
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'scanned_document_gallery',
                'data' => [
                    'badge' => '📸 Bukti Nyata Transaksi Lapangan',
                    'title' => 'Galeri Foto Scan Dokumen & Bukti Pengiriman Harian',
                    'subtitle' => 'Transparansi pengiriman harian armada truk pabrik dan tanda terima bertandatangan asli dari para mandor dan pemilik proyek di berbagai kota.',
                    'bg_theme' => 'slate',
                ],
            ],
            [
                'type' => 'social_review',
                'data' => [
                    'badge' => '📱 Viral on TikTok',
                    'title' => 'Lihat Langsung Review Para Kreator Dekorasi Rumah',
                    'subtitle' => 'Dengarkan ulasan jujur dari kreator renovasi rumah dan desainer tentang kepadatan beton, kerapian sudut, dan hasil estetika roster kami setelah terpasang di dinding.',
                    'bg_theme' => 'dark',
                ],
            ],
            [
                'type' => 'testimonials',
                'data' => [
                    'badge' => '⭐ Pengalaman Pembeli Nyata',
                    'title' => 'Kata Pelanggan & Pemilik Rumah',
                    'subtitle' => 'Kepuasan para pemilik hunian pribadi, arsitek, dan kontraktor yang telah merasakan langsung kualitas produk dan ketepatan jadwal kirim armada kami.',
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'gallery_grid',
                'data' => [
                    'badge' => '🏛️ Dokumentasi Proyek Nyata',
                    'title' => 'Proyek yang Berbicara: Sebelum & Sesudah Terpasang',
                    'subtitle' => 'Inspirasi pemasangan roster beton minimalis pada fasad rumah tinggal, pagar pembatas, sekat interior void, hingga kafe kekinian di berbagai wilayah.',
                    'bg_theme' => 'dark',
                ],
            ],
            [
                'type' => 'ugc_videos',
                'data' => [
                    'badge' => '🎬 Visual Experience Video',
                    'title' => 'Lihat Detail Aliran Angin & Pencahayaan Lebih Dekat',
                    'subtitle' => 'Koleksi video estetika celah geometris roster beton: bagaimana udara segar mengalir lancar dan pencahayaan alami mempercantik sudut ruangan.',
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'partner_cta',
                'data' => [
                    'badge' => '🔥 Konsultasi Gratis & Hitung Kebutuhan',
                    'title' => 'Wujudkan Dinding Fasad Estetik Impian Anda Sekarang',
                    'description' => 'Diskusikan pilihan motif, luas bidang dinding (m2), dan dapatkan estimasi ongkos kirim armada pabrik termurah langsung dari tim sales resmi IndoRoster.',
                    'bg_theme' => 'terra',
                    'cta_text_1' => 'Konsultasi via WhatsApp (Fast Response)',
                    'cta_url_1' => 'https://wa.me/6281389709847',
                    'cta_text_2' => 'Lihat Katalog Lengkap Dahulu',
                    'cta_url_2' => '/katalog',
                ],
            ],
            [
                'type' => 'faq',
                'data' => [
                    'badge' => '❓ Panduan & Tanya Jawab Lengkap',
                    'title' => 'Pertanyaan yang Sering Diajukan Seputar Roster Beton',
                    'subtitle' => 'Informasi praktis seputar dimensi ukuran (20x20 cm), cara menghitung kebutuhan (25 pcs/m2), teknik pasang nat presisi, dan jaminan keamanan kirim armada pabrik.',
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
