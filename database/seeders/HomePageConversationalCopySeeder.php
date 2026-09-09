<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class HomePageConversationalCopySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $blocks = [
            // 1. HERO SECTION (Gaya Ngobrol & H1 Kuat)
            [
                'type' => 'hero',
                'data' => [
                    'slider_duration' => 6000,
                    'banners' => [
                        [
                            'badge' => '🏭 Produsen Tangan Pertama · Plered, Purwakarta',
                            'title' => 'Produsen Roster Beton Minimalis? Kami Pabriknya Langsung!',
                            'subtitle' => 'Pernah nggak sih, beli roster di toko bangunan, eh ternyata harganya selangit karena udah dipotong sama perantara? Di sini, kamu beli langsung dari pabriknya—di Plered, Purwakarta. Plus, buat yang di Jabodetabek, ongkirnya gratis. Nggak percaya? Baca aja sendiri.',
                            'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765262980/2_zurmam.jpg',
                            'button_text' => '🗣️ Tanya Dulu Lewat WA',
                            'button_url' => 'https://wa.me/6281389709847?text='.urlencode('Halo IndoRoster, saya ingin konsultasi roster beton langsung dari pabrik.'),
                            'button_2_text' => '📂 Intip Katalog 45+ Motif',
                            'button_2_url' => '/katalog',
                            'alignment' => 'left',
                            'image_opacity' => 40,
                            'overlay_color' => '#020617',
                            'overlay_opacity' => 85,
                        ],
                        [
                            'badge' => '✨ Desain Bioklimatik Tropis & Fasad Estetik',
                            'title' => 'Fasad Rumah Jadi 3x Lebih Sejuk, Hemat Listrik & Mewah',
                            'subtitle' => 'Celah ventilasi silang (cross ventilation) buang hawa panas pengap secara alami. AC bisa istirahat, dompet aman, dan fasad rumah tampil ikonik berkarakter arsitektural modern.',
                            'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259970/7_blkgfx.jpg',
                            'button_text' => 'Lihat Galeri Fasad',
                            'button_url' => '/gallery',
                            'button_2_text' => 'Hitung Kebutuhan Dinding',
                            'button_2_url' => '/kalkulator-roster',
                            'alignment' => 'left',
                            'image_opacity' => 45,
                            'overlay_color' => '#020617',
                            'overlay_opacity' => 85,
                        ],
                    ],
                ],
            ],

            // 2. SOCIAL PROOF TICKER
            [
                'type' => 'ticker',
                'data' => [
                    'bg_theme' => 'dark',
                    'speed' => 'normal',
                    'text' => '🔥 2.500+ Proyek Terpasang se-Indonesia · 🏭 Kapasitas Produksi 10.000 pcs/Bulan · 🚚 100+ Armada Truk Pabrik Sendiri · 🆓 Gratis Ongkir Jabodetabek · 🛡️ Garansi Pecah Ganti Baru 100% · ⭐ Rating Kepuasan 4.9/5 · 🧱 Cetak Tumbuk Plat Baja Siku 90°',
                ],
            ],

            // 3. STATS COUNTER — TRUST SIGNAL
            [
                'type' => 'stats-counter',
                'data' => [
                    'bg_theme' => 'slate',
                    'badge' => '💬 Kredibilitas & Fakta Nyata',
                    'title' => 'Nggak Cuma 1-2 Orang, Udah 2.500+ Proyek yang Pakai Roster Kami',
                    'description' => 'Kita sadar, beli material bangunan itu butuh kepercayaan. Makanya, sebelum cerita panjang lebar, ini dulu fakta-fakta sederhananya:',
                    'alignment' => 'center',
                    'stats' => [
                        [
                            'value' => '10.000 pcs',
                            'label' => 'Kapasitas Produksi / Bln',
                            'description' => 'Kami bisa produksi banyak, jadi proyek besar pun nggak masalah.',
                        ],
                        [
                            'value' => '100+ Truk',
                            'label' => 'Armada Logistik',
                            'description' => 'Kami punya armada sendiri, barang diantar aman langsung ke lokasi.',
                        ],
                        [
                            'value' => '2.500+',
                            'label' => 'Proyek Terpasang',
                            'description' => 'Udah banyak yang percaya, dari rumah tinggal, kafe, sampai perumahan.',
                        ],
                        [
                            'value' => '4.9 / 5.0',
                            'label' => 'Rating Kepuasan',
                            'description' => 'Pelanggan puas—kebanyakan balik repeat order untuk proyek berikutnya.',
                        ],
                    ],
                ],
            ],

            // 4. PAIN POINT & SOLUSI — QUALITY COMPARISON
            [
                'type' => 'quality-comparison',
                'data' => [
                    'bg_theme' => 'white',
                    'badge' => '😤 Hayo Ngaku! Pernah Ngalamin Ini?',
                    'title' => '5 Hal yang Bikin Kesel Kalau Beli Roster Sembarangan',
                    'subtitle' => 'Kita ngobrol jujur aja ya. Sebelum kita kasih solusi, kamu pernah ngalamin salah satu dari ini? Kalo iya, kita paham banget perasaan kamu.',
                    'alignment' => 'center',
                    'comparisons' => [
                        [
                            'feature' => '1. Rumah Panas Kayak Oven, AC 24 Jam',
                            'indoroster' => 'Roster IndoRoster bikin sirkulasi udara lancar. Rumah jadi adem alami, AC bisa istirahat & tagihan listrik turun drastis.',
                            'market' => 'Dinding masif tanpa ventilasi mengurung hawa panas, tagihan listrik bengkak.',
                        ],
                        [
                            'feature' => '2. Roster 6 Bulan Udah Item & Berlumut',
                            'indoroster' => 'Padat tumbuk presisi pasir abu batu murni, pori sangat rapat, lumut susah tumbuh & fasad tetap bersih.',
                            'market' => 'Pori kasar & berongga besar, menyerap air jadi sarang lumut hitam kotor.',
                        ],
                        [
                            'feature' => '3. Tukang Ngomel Sudut Miring 85°',
                            'indoroster' => 'Cetakan plat baja siku 90° presisi. Nat lurus rapi, tukang senang, hemat semen & pengerjaan cepat.',
                            'market' => 'Sudut melengkung, nat semen bergelombang kayak ombak laut, ongkos tukang membengkak.',
                        ],
                        [
                            'feature' => '4. Harga Mahal Kualitas Jeblok',
                            'indoroster' => 'Langsung dari pabrik tangan pertama di Plered. Harga transparan tanpa potongan perantara/calo.',
                            'market' => 'Beli di toko/agen harga dimarkup 20-30%, tapi kualitas barang sering retak.',
                        ],
                        [
                            'feature' => '5. Takut Pecah di Jalan, Uang Hangus',
                            'indoroster' => 'Garansi ganti pecah 100%. Ada yang retak pas bongkar muat armada? Langsung kami ganti baru di tempat.',
                            'market' => 'Tanpa garansi pengiriman, risiko barang pecah dibebankan ke pembeli.',
                        ],
                    ],
                ],
            ],

            // 5. USP & KEUNGGULAN — 6 CARDS
            [
                'type' => 'why-choose-us-cards',
                'data' => [
                    'bg_theme' => 'dark',
                    'badge' => '⚡ Nah, Ini Dia Bedanya Roster Kami',
                    'title' => '6 Hal yang Bikin Roster IndoRoster Beda Dari yang Lain',
                    'description' => 'Oke, sekarang kita cerita tentang keunggulan kami. Ini bukan cuma klaim, tapi udah dibuktikan sama ribuan pelanggan di seluruh Indonesia.',
                    'columns' => '3',
                    'items' => [
                        [
                            'icon' => '🏭',
                            'title' => 'Kamu Beli Langsung dari Pabriknya',
                            'description' => 'Nggak ada perantara, nggak ada toko bangunan. Kamu beli dari pabrik kami di Plered, Purwakarta. Harganya transparan, kualitasnya jelas.',
                        ],
                        [
                            'icon' => '🎯',
                            'title' => 'Cetakan Plat Baja, Presisi Sempurna',
                            'description' => 'Kami pakai cetakan plat baja, bukan cor-coran asal. Hasilnya? Sudut 90°, nat lurus, pemasangan cepat, dan tukang nggak komplain.',
                        ],
                        [
                            'icon' => '🛡️',
                            'title' => 'Garansi Pecah? Ganti Baru, Nggak Ribet',
                            'description' => 'Kami paham kekhawatiran kamu. Makanya, kalau ada yang pecah di jalan, kami ganti baru saat bongkar muat armada.',
                        ],
                        [
                            'icon' => '🚚',
                            'title' => 'Gratis Ongkir Jabodetabek, Kirim se-Indonesia',
                            'description' => 'Buat kamu di Jabodetabek, ongkir kami tanggung. Di luar itu? Kami kirim juga, dari Sabang sampai Merauke via armada & kargo.',
                        ],
                        [
                            'icon' => '🧱',
                            'title' => 'Kualitas Beton Premium, Bukan Limbah',
                            'description' => 'Kami pakai pasir abu batu murni, bukan limbah cor. Bobot 3.8 - 4.2 kg per keping, padat, kokoh, dan anti-retak.',
                        ],
                        [
                            'icon' => '🎨',
                            'title' => '45+ Motif & 3 Pilihan Warna Alami',
                            'description' => 'Dari motif Nako, Bintang, sampai Z yang dinamis. Tersedia warna abu semen, putih dolomit, dan merah terakota alami tanpa cat.',
                        ],
                    ],
                ],
            ],

            // 6. JANGKAUAN LOGISTIK & PENGIRIMAN
            [
                'type' => 'shipping_info',
                'data' => [
                    'bg_theme' => 'white',
                    'badge' => '🌏 Kirim ke Mana Aja? Coba Sebutin!',
                    'title' => 'Kirim ke Seluruh Indonesia, Gratis buat Jabodetabek',
                    'subtitle' => 'Kami nggak cuma jualan, kami juga ngurus kirimnya. Jabodetabek gratis ongkir, Jawa Barat (Bandung, Cirebon, Karawang, Subang), Jawa Tengah, Jawa Timur, Sumatera, Kalimantan, Sulawesi, Bali hingga Papua kami siap antar.',
                ],
            ],

            // 7. KATALOG BEST SELLER & HARGA
            [
                'type' => 'featured_products',
                'data' => [
                    'bg_theme' => 'slate',
                    'badge' => '🎨 Motif Banyak, Harga Bersahabat',
                    'title' => '45+ Motif Roster, Tinggal Pilih yang Kamu Suka',
                    'subtitle' => 'Motif roster itu kayak baju buat rumah kamu. Pilih yang cocok sama karakternya: Nako 1A, Motif Z, Bintang, Petir, Arorow, hingga MMC Viral dengan harga pabrik langsung mulai Rp 11.000 - 13.500/pcs.',
                    'limit' => 6,
                    'grid_columns' => '6',
                ],
            ],

            // 8. PRODUK VIRAL & TRENDING
            [
                'type' => 'viral_products',
                'data' => [
                    'bg_theme' => 'white',
                    'badge' => '🔥 Paling Banyak Dicari & Viral',
                    'title' => 'Koleksi Motif Terfavorit Pilihan Arsitek & Desainer',
                    'subtitle' => 'Desain signature dengan volume pemesanan tertinggi bulan ini. Cocok untuk fasad rumah minimalis, pagar modern, dan partisi kafe industrial.',
                    'limit' => 6,
                ],
            ],

            // 9. TESTIMONI PELANGGAN
            [
                'type' => 'testimonials',
                'data' => [
                    'bg_theme' => 'slate',
                    'badge' => '🗣️ Kata Mereka yang Udah Pakai',
                    'title' => 'Ini Kata Mereka yang Udah Ngerasain Sendiri',
                    'subtitle' => 'Daripada kami yang cerita, mending kamu denger langsung dari pemilik rumah, kontraktor, dan pengembang perumahan yang telah membuktikan kualitas IndoRoster.',
                ],
            ],

            // 10. GALERI FOTO PROYEK
            [
                'type' => 'gallery_grid',
                'data' => [
                    'bg_theme' => 'dark',
                    'badge' => '🎬 Lihat Langsung Hasilnya',
                    'title' => 'Ini Bukti Nyata: Fasad Rumah Sebelum & Sesudah Pasang Roster',
                    'subtitle' => 'Kami tahu, kamu lebih percaya sama gambar nyata daripada kata-kata. Lihat inspirasi fasad rumah, pagar, partisi kafe, dan mushola yang tampak mewah.',
                ],
            ],

            // 11. VIDEO PENGALAMAN ALIRAN UDARA & CAHAYA
            [
                'type' => 'ugc_videos',
                'data' => [
                    'bg_theme' => 'white',
                    'badge' => '🎥 Visual Experience Video',
                    'title' => 'Lihat Detail Aliran Udara & Cahaya Melalui Celah Roster',
                    'subtitle' => 'Koleksi video nyata bagaimana angin segar berhembus leluasa dan bayangan matahari alami menciptakan ambience hangat di dalam ruangan.',
                ],
            ],

            // 12. ALUR PEMESANAN — 4 LANGKAH
            [
                'type' => 'buying-steps',
                'data' => [
                    'bg_theme' => 'slate',
                    'badge' => '📝 Gampang Banget, Cuma 4 Langkah!',
                    'title' => 'Bingung Cara Pesannya? Tenang, Ini Panduannya',
                    'subtitle' => 'Kami buat prosesnya simpel, transparan, dan nggak bikin pusing kepala:',
                    'steps' => [
                        [
                            'step' => '01',
                            'title' => '💬 Konsultasi Gratis Lewat WA',
                            'desc' => 'Kirim ukuran dinding atau foto denah. Kami hitung kebutuhan jumlah keping & kasih saran motif yang cocok. Nggak dipungut biaya!',
                        ],
                        [
                            'step' => '02',
                            'title' => '📄 Invoice Resmi Kami Kirim',
                            'desc' => 'Dapatkan surat penawaran dan invoice resmi. Harga pabrik transparan, ada diskon khusus buat pesanan volume proyek.',
                        ],
                        [
                            'step' => '03',
                            'title' => '🏭 Quality Control & Muat Armada',
                            'desc' => 'Roster diperiksa kelayakannya satu per satu. Kami kirim foto/video saat barang dimuat ke armada sebelum meluncur.',
                        ],
                        [
                            'step' => '04',
                            'title' => '🏠 Barang Sampai & Garansi Aktif',
                            'desc' => 'Cek roster bareng sopir armada kami di lokasi. Ada yang pecah? Langsung kami ganti baru di tempat tanpa ribet!',
                        ],
                    ],
                ],
            ],

            // 13. DOKUMEN PENGADAAN LENGKAP & SCAN RESMI
            [
                'type' => 'document_procurement_proof',
                'data' => [
                    'bg_theme' => 'white',
                    'badge' => '📄 Bukti Kami Transparan',
                    'title' => 'Kami Nggak Suka Sembunyi-sembunyi, Ini Dokumennya',
                    'subtitle' => 'Biar kamu makin yakin, ini bukti nyata transaksi pengadaan proyek kami: Surat Penawaran, Invoice Resmi, Kuitansi Pembayaran, Surat Jalan stempel QC, hingga Hasil Uji Lab.',
                ],
            ],
            [
                'type' => 'scanned_document_gallery',
                'data' => [
                    'bg_theme' => 'slate',
                    'badge' => '📸 Arsip Tanda Terima Lapangan',
                    'title' => 'Galeri Foto Scan Surat Jalan & Tanda Terima Mandor',
                    'subtitle' => 'Dokumentasi asli serah terima pengiriman armada pabrik ke proyek-proyek residensial, ruko, dan perumahan.',
                ],
            ],

            // 14. FAQ TANYA JAWAB
            [
                'type' => 'faq',
                'data' => [
                    'bg_theme' => 'white',
                    'badge' => '❓ Ada yang Mau Ditanya? Mungkin Ini Jawabannya',
                    'title' => 'Pertanyaan yang Sering Muncul Sebelum Order',
                    'subtitle' => 'Kami tahu sebelum beli pasti ada banyak pertanyaan. Nih, kami jawab yang paling sering ditanyakan pelanggan:',
                ],
            ],

            // 15. CTA FINAL PERSUASIF
            [
                'type' => 'partner_cta',
                'data' => [
                    'bg_theme' => 'terra',
                    'badge' => '🏆 Udah Baca Semua? Sekarang Giliran Kamu yang Gerak!',
                    'title' => 'Wujudkan Dinding Impianmu Hari Ini Langsung dari Pabrik!',
                    'description' => 'Gratis ongkir Jabodetabek (min. pesanan), harga pabrik tanpa perantara, garansi pecah 100% risiko di kami, dan konsultasi desain gratis. Tapi ingat: slot jadwal muat armada harian terbatas!',
                    'cta_text_1' => '🚀 Konsultasi Gratis via WhatsApp',
                    'cta_url_1' => 'https://wa.me/6281389709847?text='.urlencode('Halo IndoRoster, saya ingin konsultasi gratis dan hitung kebutuhan roster langsung dari pabrik.'),
                    'cta_text_2' => '📂 Intip Katalog 45+ Motif',
                    'cta_url_2' => '/katalog',
                ],
            ],
        ];

        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Beranda Utama (Home)',
                'meta_title' => 'Produsen & Pabrik Roster Beton Minimalis Plered Purwakarta | IndoRoster',
                'meta_description' => 'Beli roster beton minimalis langsung dari pabrik tangan pertama Plered Purwakarta. Gratis ongkir Jabodetabek, kirim se-Indonesia, garansi pecah 100%, 45+ motif presisi siku 90°.',
                'content' => $blocks,
                'is_active' => true,
            ]
        );
    }
}
