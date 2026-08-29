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
        // 1. PROSES PRODUKSI (slug: proses-produksi)
        $prosesProduksiBlocks = [
            [
                'type' => 'hero',
                'data' => [
                    'slider_duration' => 5000,
                    'banners' => [
                        [
                            'title' => 'Standar Pabrikasi Roster Beton Cetak Tumbuk K-200',
                            'subtitle' => 'Melihat langsung dapur produksi IndoRoster di sentra Plered Purwakarta: formula agregat pasir silika terpilih, pemadatan hidrolik mekanis, dan kontrol mutu presisi sudut 90° sebelum dikirim ke proyek Anda.',
                            'badge' => '⚙️ Standar Pabrikasi & QC Ketat',
                            'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765262980/2_zurmam.jpg',
                            'button_text' => 'Konsultasi Spesifikasi Teknis',
                            'button_url' => 'https://wa.me/6281389709847',
                            'button_2_text' => 'Lihat Katalog Produk',
                            'button_2_url' => '/katalog',
                            'alignment' => 'left',
                            'image_opacity' => 40,
                            'overlay_color' => '#020617',
                            'overlay_opacity' => 80,
                        ],
                    ],
                ],
            ],
            [
                'type' => 'ticker',
                'data' => [
                    'bg_theme' => 'dark',
                    'speed' => 'normal',
                    'text' => '⚙️ Formula Mutu K-200 · 📐 Akurasi Sudut 90° Presisi · 🏭 Sentra Pabrikasi Plered Purwakarta · 🛡️ Garansi Pecah Ganti Baru 100% · 🚚 Truk Logistik Siap Kirim Tiap Hari',
                ],
            ],
            [
                'type' => 'why_us',
                'data' => [
                    'badge' => '🏭 4 Tahapan Kontrol Kualitas Pabrik',
                    'title' => 'Bagaimana Roster Beton Berkualitas Dibuat?',
                    'description' => 'Kami tidak menggunakan metode cor basah manual yang rentan keropos. Setiap keping melewati 4 tahap manufaktur presisi.',
                    'bg_theme' => 'slate',
                    'items' => [
                        [
                            'title' => '1. Seleksi Agregat Pasir Silika & Semen Berkualitas',
                            'description' => 'Pencampuran pasir silika gradasi khusus dengan semen Portland murni mutu K-200 untuk kekuatan ikatan maksimal.',
                        ],
                        [
                            'title' => '2. Pemadatan Hidrolik Cetak Tumbuk Mekanis',
                            'description' => 'Proses press tumbuk padat dengan tekanan hidrolik tinggi untuk menghilangkan rongga udara di dalam adukan semen.',
                        ],
                        [
                            'title' => '3. Curing & Pengeringan Alami Bebas Retak',
                            'description' => 'Proses hidrasi pengeringan bertahap dengan kelembaban terkontrol agar beton matang sempurna dan tidak rapuh.',
                        ],
                        [
                            'title' => '4. Kalibrasi Sudut 90° & Quality Check Akhir',
                            'description' => 'Pemeriksaan dimensi tiap keping agar sudut siku sempurna 90° sehingga tukang bangunan dapat memasang nat dengan rapi dan cepat.',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'strength_test',
                'data' => [
                    'badge' => '💪 Uji Ketahanan Beban',
                    'title' => 'Uji Kekuatan Beton Cetak Tumbuk Padat',
                    'subtitle' => 'Kepadatan tinggi menjamin keping roster kuat menahan benturan dan tidak mudah rompal saat bongkar muat di lokasi proyek Anda.',
                    'bg_theme' => 'dark',
                ],
            ],
            [
                'type' => 'ugc_videos',
                'data' => [
                    'badge' => '🎬 Dokumentasi Lapangan',
                    'title' => 'Video Dokumentasi Proses Manufaktur & Pemuatan',
                    'subtitle' => 'Simak video dokumentasi aktivitas para pengrajin ahli kami di pabrik Plered Purwakarta.',
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'faq',
                'data' => [
                    'badge' => '❓ FAQ Manufaktur & Teknis',
                    'title' => 'Pertanyaan Teknis Seputar Produksi Roster',
                    'subtitle' => 'Penjelasan mengenai mutu beton K-200, berat per keping (~4-4.5 kg), finishing permukaan halus abu/putih, dan estimasi waktu produksi partai besar.',
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'partner_cta',
                'data' => [
                    'badge' => 'Kemitraan Pabrik & Pengadaan Proyek',
                    'title' => 'Terkoneksi Langsung dengan Pabrik Roster IndoRoster',
                    'description' => 'Solusi pengadaan roster beton arsitektural tangan pertama untuk pemilik rumah, kontraktor, arsitek, pemilik bisnis kafe, hingga developer kawasan perumahan di seluruh Indonesia.',
                    'bg_theme' => 'terra',
                    'cta_text_1' => 'Konsultasi Pengadaan via WhatsApp',
                    'cta_url_1' => 'https://wa.me/6281389709847',
                    'cta_text_2' => 'Lihat Katalog Produk',
                    'cta_url_2' => '/katalog',
                ],
            ],
        ];

        // 2. TENTANG KAMI (slug: tentang-kami)
        $tentangKamiBlocks = [
            [
                'type' => 'hero',
                'data' => [
                    'slider_duration' => 5000,
                    'banners' => [
                        [
                            'title' => 'Produsen Resmi Roster Beton Arsitektural & Bata Expose',
                            'subtitle' => 'Berangkat dari warisan keahlian pengrajin cetak beton di sentra Plered Purwakarta, IndoRoster hadir memberikan kemudahan akses material arsitektur berkualitas harga pabrik bagi masyarakat Indonesia.',
                            'badge' => '🏛️ Profil & Legalitas Pabrik',
                            'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259970/7_blkgfx.jpg',
                            'button_text' => 'Hubungi Tim Sales',
                            'button_url' => 'https://wa.me/6281389709847',
                            'button_2_text' => 'Lihat Galeri Proyek',
                            'button_2_url' => '/gallery',
                            'alignment' => 'left',
                            'image_opacity' => 40,
                            'overlay_color' => '#020617',
                            'overlay_opacity' => 80,
                        ],
                    ],
                ],
            ],
            [
                'type' => 'ticker',
                'data' => [
                    'bg_theme' => 'dark',
                    'speed' => 'normal',
                    'text' => '🏛️ Produsen Terpercaya Plered Purwakarta · 📦 5000+ Proyek Sukses · 🛡️ Garansi Pecah 100% · 📑 Dokumen SPK & Faktur Pajak Resmi · 🚚 Jangkauan Kirim Nasional',
                ],
            ],
            [
                'type' => 'why_us',
                'data' => [
                    'badge' => '💎 Nilai Utama Kami',
                    'title' => 'Komitmen Kualitas & Transparansi Tanpa Perantara',
                    'description' => 'Kami percaya bahwa setiap hunian dan bangunan komersial berhak mendapatkan material arsitektur terbaik dengan harga yang adil dan garansi keamanan penuh.',
                    'bg_theme' => 'slate',
                    'items' => [
                        [
                            'title' => 'Integritas Mutu Material',
                            'description' => 'Kami menjaga konsistensi formula beton padat mutu K-200 agar kokoh, berumur panjang, dan tahan terhadap cuaca tropis ekstrem.',
                        ],
                        [
                            'title' => 'Harga Pabrik yang Transparan',
                            'description' => 'Tanpa biaya markup perantara, memberikan keuntungan maksimal bagi pemilik rumah dan fleksibilitas margin bagi kontraktor.',
                        ],
                        [
                            'title' => 'Layanan Logistik & Tanggung Jawab 100%',
                            'description' => 'Kami menjamin setiap keping barang yang dikirim sampai dengan aman. Klaim ganti baru dilayani dengan mudah tanpa birokrasi berbelit.',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'document_procurement_proof',
                'data' => [
                    'badge' => '📑 Administrasi & Legalitas Sah',
                    'title' => 'Kesiapan Administrasi Resmi & Faktur Pajak',
                    'subtitle' => 'Kelengkapan dokumen Surat Jalan resmi, Faktur Pajak PPN/PPh, dan SPK untuk kemudahan administrasi Anda.',
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'partner_cta',
                'data' => [
                    'badge' => 'Kemitraan Terpercaya',
                    'title' => 'Siap Bermitra dengan Pabrik IndoRoster?',
                    'description' => 'Dapatkan penawaran harga terbaik dan konsultasi motif fasad gratis dari tim ahli kami.',
                    'bg_theme' => 'terra',
                    'cta_text_1' => 'Hubungi via WhatsApp',
                    'cta_url_1' => 'https://wa.me/6281389709847',
                    'cta_text_2' => 'Lihat Katalog Produk',
                    'cta_url_2' => '/katalog',
                ],
            ],
        ];

        // 3. KONTAK (slug: kontak)
        $kontakBlocks = [
            [
                'type' => 'hero',
                'data' => [
                    'slider_duration' => 5000,
                    'banners' => [
                        [
                            'title' => 'Hubungi Pabrik & Tim Sales Resmi IndoRoster',
                            'subtitle' => 'Konsultasi kebutuhan motif, perhitungan luas dinding (m2), dan cek estimasi ongkos kirim armada truk pabrik langsung bersama tim kami.',
                            'badge' => '📞 Layanan Pelanggan Fast Response',
                            'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765262980/2_zurmam.jpg',
                            'button_text' => 'Chat WhatsApp Sekarang',
                            'button_url' => 'https://wa.me/6281389709847',
                            'button_2_text' => 'Kalkulator Kebutuhan',
                            'button_2_url' => '/kalkulator-roster',
                            'alignment' => 'left',
                            'image_opacity' => 40,
                            'overlay_color' => '#020617',
                            'overlay_opacity' => 80,
                        ],
                    ],
                ],
            ],
            [
                'type' => 'shipping_info',
                'data' => [
                    'badge' => '🚚 Armada Pengiriman',
                    'title' => 'Jangkauan Pengiriman Seluruh Indonesia',
                    'subtitle' => 'Pengiriman rutin armada pabrik ke Jakarta, Bogor, Depok, Tangerang, Bekasi, Bandung, Karawang, Cianjur, Cirebon, hingga ekspedisi kargo seluruh Nusantara.',
                    'bg_theme' => 'dark',
                ],
            ],
            [
                'type' => 'faq',
                'data' => [
                    'badge' => '❓ FAQ Pemesanan & Pembayaran',
                    'title' => 'Pertanyaan Seputar Pemesanan & Pengiriman',
                    'subtitle' => 'Informasi mengenai cara order via WhatsApp, jadwal kirim armada pabrik, dan prosedur klaim garansi ganti baru jika ada unit retak.',
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'partner_cta',
                'data' => [
                    'badge' => 'Sales Pabrik',
                    'title' => 'Butuh Penawaran Harga atau Mau Kunjungi Pabrik?',
                    'description' => 'Tim kami siap menyambut dan memberikan solusi material terbaik untuk proyek Anda.',
                    'bg_theme' => 'terra',
                    'cta_text_1' => 'Chat WhatsApp Sales',
                    'cta_url_1' => 'https://wa.me/6281389709847',
                    'cta_text_2' => 'Lihat Katalog Produk',
                    'cta_url_2' => '/katalog',
                ],
            ],
        ];

        // Update Proses Produksi
        Page::updateOrCreate(
            ['slug' => 'proses-produksi'],
            [
                'title' => 'Proses Produksi Pabrik',
                'content' => array_values($prosesProduksiBlocks),
                'meta_title' => 'Proses Produksi Roster Beton Cetak Padat Presisi K-200 — IndoRoster Plered Purwakarta',
                'meta_description' => 'Melihat langsung standar manufaktur roster beton IndoRoster: pemilihan agregat pasir silika, pemadatan hidrolik mutu K-200, pengeringan alami, dan kontrol presisi siku 90° di Plered Purwakarta.',
                'is_active' => true,
            ]
        );

        // Update Tentang Kami
        Page::updateOrCreate(
            ['slug' => 'tentang-kami'],
            [
                'title' => 'Tentang Kami',
                'content' => array_values($tentangKamiBlocks),
                'meta_title' => 'Tentang IndoRoster — Pabrik Produsen Roster Beton & Bata Expose Plered Purwakarta',
                'meta_description' => 'Profil resmi IndoRoster, produsen tangan pertama roster beton minimalis, bata expose, dan ornamen dinding arsitektural berbasis di sentra pengrajin Plered Purwakarta melayani pengiriman seluruh Indonesia.',
                'is_active' => true,
            ]
        );

        // Update Kontak
        Page::updateOrCreate(
            ['slug' => 'kontak'],
            [
                'title' => 'Kontak Pabrik & Sales',
                'content' => array_values($kontakBlocks),
                'meta_title' => 'Hubungi Kami — Layanan Konsultasi Pabrik & Sales Resmi IndoRoster',
                'meta_description' => 'Kontak resmi pabrik dan sales IndoRoster. Konsultasi gratis pilihan motif roster, estimasi hitung kebutuhan dinding (m2), dan cek ongkos kirim armada truk pabrik ke lokasi proyek Anda.',
                'is_active' => true,
            ]
        );

        // Update B2B Hubs (Tetap murni layout bespoke 12 fase interaktif)
        $b2bSlugs = [
            'untuk-kontraktor' => 'Suplier Roster Beton & Pemborong (Khusus Kontraktor Proyek)',
            'untuk-developer' => 'Pengadaan Roster Skala Klaster (Khusus Developer Perumahan)',
            'untuk-arsitek' => 'Katalog Teknis & Material Roster (Khusus Arsitek & Desainer)',
            'supplier-roster-beton' => 'Distributor & Grosir Pabrik (Khusus Toko Bangunan & Agen Material)',
            'roster-beton-proyek' => 'Pengadaan Roster Beton untuk Proyek Fasad & Gedung Komersial',
        ];

        foreach ($b2bSlugs as $slug => $title) {
            Page::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $title,
                    'content' => [],
                    'is_active' => true,
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
