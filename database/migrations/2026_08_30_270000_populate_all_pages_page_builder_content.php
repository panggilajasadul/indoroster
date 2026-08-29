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

        // 4. B2B HUBS
        $b2bBlocks = [
            'untuk-kontraktor' => [
                'title' => 'Suplier Roster Beton & Pemborong (Khusus Kontraktor Proyek)',
                'badge' => '🏗️ Portal Khusus Kontraktor & Pemborong',
                'heading' => 'Pengadaan Roster Beton Skala Proyek dengan Harga Pabrik',
                'desc' => 'Dukungan penuh untuk pemborong & kontraktor: harga grosir bertingkat, kepastian jadwal suplai berkala ribuan pcs, dan kelengkapan SPK serta Faktur Pajak resmi.',
            ],
            'untuk-developer' => [
                'title' => 'Pengadaan Roster Skala Klaster (Khusus Developer Perumahan)',
                'badge' => '🏘️ Portal Khusus Developer Perumahan',
                'heading' => 'Suplai Roster Beton Klaster Perumahan & Kawasan',
                'desc' => 'Kapasitas produksi pabrikasi tinggi siap memasok ribuan keping roster motif seragam untuk puluhan unit rumah klaster real estate Anda.',
            ],
            'untuk-arsitek' => [
                'title' => 'Katalog Teknis & Material Roster (Khusus Arsitek & Desainer)',
                'badge' => '📐 Portal Khusus Arsitek & Desainer',
                'heading' => 'Spesifikasi Teknis & Estetika Roster Arsitektural',
                'desc' => 'Koleksi motif roster geometris modern dengan presisi dimensi siku 90°, data CAD/3D, dan sample material fisik untuk studio arsitektur Anda.',
            ],
            'supplier-roster-beton' => [
                'title' => 'Distributor & Grosir Pabrik (Khusus Toko Bangunan & Agen Material)',
                'badge' => '🏪 Portal Distributor & Toko Bangunan',
                'heading' => 'Peluang Keagenan & Pasokan Grosir Toko Bangunan',
                'desc' => 'Dapatkan margin keuntungan terbaik dengan menjadi mitra distribusi resmi produk roster beton IndoRoster di kota Anda.',
            ],
            'roster-beton-proyek' => [
                'title' => 'Pengadaan Roster Beton untuk Proyek Fasad & Gedung Komersial',
                'badge' => '🏢 Portal Pengadaan Fasad Gedung Komersial',
                'heading' => 'Solusi Secondary Skin & Fasad Komersial Skala Besar',
                'desc' => 'Material ventilasi arsitektur tahan cuaca untuk gedung perkantoran, hotel, kafe industrial, dan bangunan publik di seluruh Indonesia.',
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

        // Update B2B Hubs
        foreach ($b2bBlocks as $slug => $meta) {
            $blocks = [
                [
                    'type' => 'hero',
                    'data' => [
                        'slider_duration' => 5000,
                        'banners' => [
                            [
                                'title' => $meta['heading'],
                                'subtitle' => $meta['desc'],
                                'badge' => $meta['badge'],
                                'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259970/7_blkgfx.jpg',
                                'button_text' => 'Request Quotation / Penawaran',
                                'button_url' => 'https://wa.me/6281389709847',
                                'button_2_text' => 'Lihat Katalog Teknis',
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
                        'text' => '🏭 Harga Pabrik Tangan Pertama · 📑 Faktur Pajak PPN/PPh & SPK Sah · 🛡️ Garansi Pecah Ganti Baru 100% · 🚚 Kapasitas Suplai Rutin Ribuan Pcs Tiap Minggu',
                    ],
                ],
                [
                    'type' => 'why_us',
                    'data' => [
                        'badge' => '💎 Keunggulan Skala Proyek',
                        'title' => 'Keuntungan Bermitra Langsung dengan Pabrik IndoRoster',
                        'description' => 'Jaminan kepastian pasokan, akurasi dimensi sudut siku 90°, dan fleksibilitas harga terbaik untuk kelancaran proyek Anda.',
                        'bg_theme' => 'slate',
                        'items' => [
                            [
                                'title' => 'Harga Bertingkat & MOQ Fleksibel',
                                'description' => 'Mendapatkan skema diskon volume bertingkat langsung dari pabrikasi tanpa markup perantara.',
                            ],
                            [
                                'title' => 'Kepastian Jadwal & Kapasitas Suplai Besar',
                                'description' => 'Armada truk logistik siap mengirim pesanan bertahap sesuai timeline kurva S proyek konstruksi Anda.',
                            ],
                            [
                                'title' => 'Kelengkapan Legalitas Administrasi',
                                'description' => 'Surat Jalan berstempel resmi, Faktur Pajak PPN/PPh, dan SPK siap diterbitkan untuk kelancaran klaim termin proyek.',
                            ],
                        ],
                    ],
                ],
                [
                    'type' => 'document_procurement_proof',
                    'data' => [
                        'badge' => '📑 Administrasi Resmi',
                        'title' => 'Kesiapan Dokumen Pengadaan & Faktur Pajak',
                        'subtitle' => 'Surat Jalan (DO), Invoice Komersial, Faktur Pajak PPN/PPh resmi, dan SPK siap kami terbitkan.',
                        'bg_theme' => 'white',
                    ],
                ],
                [
                    'type' => 'featured_products',
                    'data' => [
                        'badge' => '📦 Motif Rekomendasi Proyek',
                        'title' => 'Motif Roster Paling Banyak Dipilih untuk Proyek',
                        'subtitle' => 'Pilihan motif roster minimalis presisi dengan efisiensi pasang tinggi dan stok produksi melimpah.',
                        'grid_columns' => '6',
                        'limit' => 8,
                        'bg_theme' => 'white',
                    ],
                ],
                [
                    'type' => 'partner_cta',
                    'data' => [
                        'badge' => 'Request Quotation Proyek',
                        'title' => 'Dapatkan Penawaran Harga Khusus Proyek Sekarang',
                        'description' => 'Kirimkan Bill of Quantity (BOQ) atau kebutuhan motif roster Anda untuk mendapatkan surat penawaran harga resmi dari Sales Proyek IndoRoster.',
                        'bg_theme' => 'terra',
                        'cta_text_1' => 'Kirim BOQ via WhatsApp',
                        'cta_url_1' => 'https://wa.me/6281389709847',
                        'cta_text_2' => 'Download Katalog Lengkap',
                        'cta_url_2' => '/katalog',
                    ],
                ],
            ];

            Page::updateOrCreate(
                ['slug' => $slug],
                [
                    'title' => $meta['title'],
                    'content' => array_values($blocks),
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
