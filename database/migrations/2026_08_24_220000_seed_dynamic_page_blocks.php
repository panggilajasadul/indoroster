<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tentang Kami Page
        $aboutUsBlocks = [
            [
                'type' => 'hero',
                'data' => [
                    'slider_duration' => 5000,
                    'banners' => [
                        [
                            'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1920&q=80',
                            'top_text' => 'PRODUSEN LANGSUNG TANGAN PERTAMA',
                            'badge' => 'PABRIK PLERED PURWAKARTA',
                            'title' => 'Mengenal IndoRoster Lebih Dekat',
                            'subtitle' => 'Produsen spesialis roster beton minimalis (breeze blocks), loster modern 20x20x10 cm, bata tempel dinding terakota, roster pagar, dan roster fasad dari sentra kerajinan Plered, Purwakarta.',
                            'button_text' => 'Cek Katalog & Harga Pabrik',
                            'button_url' => '/katalog',
                            'button_2_text' => 'Lihat Galeri Proyek',
                            'button_2_url' => '/gallery',
                            'alignment' => 'center',
                            'overlay_color' => '#020617',
                            'overlay_opacity' => '75',
                            'image_opacity' => '45',
                            'blur_level' => 'none',
                            'image_fit' => 'object-cover',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'stats_counter',
                'data' => [
                    'badge' => 'KAPASITAS & DEDIKASI PABRIK',
                    'title' => 'Pusat Suplai Roster Beton & Bata Tempel Terlengkap',
                    'description' => 'Dipercaya oleh ribuan pemilik rumah, arsitek, mandor, dan kontraktor di Jabodetabek, Jawa Barat, dan seluruh Indonesia.',
                    'bg_theme' => 'white',
                    'stats' => [
                        ['value' => '5.000+', 'label' => 'Proyek Tersuplai', 'description' => 'Rumah Tinggal, Ruko & Komersial'],
                        ['value' => 'Langsung', 'label' => 'Harga Pabrik', 'description' => 'Tanpa Mark-up Toko Perantara'],
                        ['value' => '50+', 'label' => 'Pilihan Motif', 'description' => 'Roster Fasad, Pagar & Partisi'],
                        ['value' => '100%', 'label' => 'Garansi Kirim', 'description' => 'Pecah Kami Ganti Baru'],
                    ],
                ],
            ],
            [
                'type' => 'why_us',
                'data' => [
                    'badge' => 'KEUNGGULAN BELANJA PABRIK',
                    'title' => 'Mengapa Membeli Langsung dari IndoRoster?',
                    'description' => 'Kami adalah murni pabrik produsen material bangunan, bukan biro konsultan. Fokus kami adalah menyediakan suplai loster beton minimalis 20x20x10 cm, bata tempel dinding, dan roster fasad dengan harga pabrik yang transparan.',
                    'items' => [
                        ['title' => 'Harga Pabrik Tangan Pertama', 'content' => 'Langsung dari sentra Plered Purwakarta tanpa perantara distributor atau toko material perantara.'],
                        ['title' => 'Presisi Dimensi & Siap Pasang', 'content' => 'Formula pasir silika dan semen pilihan dengan cetakan baja menghasilkan loster beton yang presisi, sudut siku rapi, dan hemat adukan semen.'],
                        ['title' => 'Garansi Pengiriman Aman', 'content' => 'Pengiriman armada truk pabrik bergaransi. Jika ada unit roster yang pecah di jalan, langsung kami ganti baru.'],
                    ],
                    'videos' => [],
                    'bg_theme' => 'slate',
                ],
            ],
            [
                'type' => 'technical_specs',
                'data' => [
                    'badge' => 'SPESIFIKASI STANDAR PABRIK',
                    'title' => 'Data Teknis & Presisi Dimensi Roster',
                    'subtitle' => 'Standar modul loster modern 20x20x10 cm dengan kebutuhan 25 pcs/m² untuk perhitungan gambar kerja dan RAB proyek dinding ventilasi.',
                    'bg_theme' => 'dark',
                ],
            ],
            [
                'type' => 'rich_text',
                'data' => [
                    'title' => 'Target Produk Utama & Material Arsitektural',
                    'content' => '<h3>Spesialisasi Produk & Material Dinding Arsitektural</h3><p>IndoRoster memproduksi dan menyediakan ragam kebutuhan material dinding dan ventilasi berkualitas ekspor untuk pasar lokal:</p><ul><li><strong>Roster Beton Minimalis (Breeze Blocks):</strong> Modul loster modern 20x20x10 cm untuk sirkulasi udara optimal dan pencahayaan alami ruangan tropis.</li><li><strong>Roster Pagar & Roster Fasad (Secondary Skin):</strong> Menambah estetika tampak depan rumah sekaligus menjaga privasi penghuni.</li><li><strong>Bata Tempel Dinding Terakota & Semen:</strong> Solusi aksen dinding industrial, cafe, dan interior bergaya natural.</li><li><strong>Ventilasi Udara Arsitektural:</strong> Lebih dari 50+ pilihan motif geometris, minimalis, dan klasik dalam warna Abu Beton, Putih Dolomit, Gravel, dan Merah Terakota.</li></ul>',
                    'bg_theme' => 'white',
                    'max_width' => '4xl',
                    'alignment' => 'left',
                ],
            ],
            [
                'type' => 'shipping_info',
                'data' => [
                    'badge' => 'JANGKAUAN LOGISTIK & LOCAL SEO',
                    'title' => 'Pengiriman Cepat Jabodetabek, Jawa Barat & Ekspedisi Nasional',
                    'content' => '<p class="lead">Sebagai pusat produksi tangan pertama di <strong>Plered, Purwakarta</strong>, armada truk kami siap mengirimkan pesanan partai kecil maupun ribuan pieces langsung ke gerbang proyek Anda dengan jaminan garansi aman sampai tujuan:</p><div style="margin-top: 15px; display: grid; gap: 12px;"><div><strong>📍 Wilayah Jabodetabek:</strong> Melayani seluruh kawasan DKI Jakarta (Jakarta Selatan, Jakarta Barat, Jakarta Timur, Jakarta Utara, Jakarta Pusat), Bogor, Depok, Tangerang, Tangerang Selatan, dan Bekasi.</div><div><strong>📍 Wilayah Jawa Barat:</strong> Plered, Purwakarta, Karawang, Cikampek, Subang, Bandung Raya, Cimahi, Sumedang, Cirebon, Indramayu, Sukabumi, dan Cianjur.</div><div><strong>📍 Pengiriman Nasional & Luar Pulau:</strong> Ekspedisi kargo khusus material aman menjangkau Jawa Tengah, Jawa Timur, Bali, Sumatera, Kalimantan, dan Sulawesi.</div></div>',
                    'button_text' => 'Konsultasi Stok & Ongkir WhatsApp',
                    'button_url' => 'https://wa.me/6281389709847',
                    'bg_theme' => 'slate',
                ],
            ],
            [
                'type' => 'cta',
                'data' => [
                    'badge' => 'Katalog & Penawaran Harga',
                    'title' => 'Siap Mewujudkan Dinding & Fasad Roster Impian?',
                    'subtitle' => 'Dapatkan katalog lengkap 50+ motif roster beton minimalis, bata tempel dinding, dan harga pabrik langsung ke lokasi Anda.',
                    'button_text' => 'Konsultasi via WhatsApp',
                    'button_url' => 'https://wa.me/6281389709847',
                    'bg_theme' => 'dark',
                ],
            ],
        ];

        DB::table('pages')->updateOrInsert(
            ['slug' => 'tentang-kami'],
            [
                'title' => 'Tentang Kami',
                'meta_title' => 'Tentang Kami — INDOROSTER | Pabrik Roster Beton Plered Purwakarta',
                'meta_description' => 'Kenali INDOROSTER lebih dekat. Pabrik produsen roster beton minimalis tangan pertama di Plered, Purwakarta. Suplai roster pagar, roster fasad, bata tempel ke Jabodetabek dan seluruh Indonesia.',
                'content' => json_encode($aboutUsBlocks),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 2. Kontak Page
        $contactBlocks = [
            [
                'type' => 'hero',
                'data' => [
                    'slider_duration' => 5000,
                    'banners' => [
                        [
                            'image' => 'https://images.unsplash.com/photo-1541888946425-d0fbb18086f6?auto=format&fit=crop&w=1920&q=80',
                            'top_text' => 'RESPON CEPAT PABRIK LANGSUNG',
                            'badge' => 'LAYANAN KONSULTASI & TANYA STOK',
                            'title' => 'Hubungi Kami & Dapatkan Penawaran',
                            'subtitle' => 'Pabrik produsen roster beton minimalis tangan pertama di Plered, Purwakarta. Konsultasikan motif, estimasi kebutuhan semen & keping, serta simulasi ongkir aman ke lokasi proyek Anda.',
                            'button_text' => 'Chat WhatsApp Langsung',
                            'button_url' => 'https://wa.me/6281389709847',
                            'button_2_text' => 'Buka Rute Pabrik',
                            'button_2_url' => '#peta-lokasi',
                            'alignment' => 'center',
                            'overlay_color' => '#020617',
                            'overlay_opacity' => '75',
                            'image_opacity' => '45',
                            'blur_level' => 'none',
                            'image_fit' => 'object-cover',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'contact_form',
                'data' => [
                    'badge' => 'HUBUNGI PABRIK LANGSUNG',
                    'title' => 'Formulir Permintaan Penawaran & Info Kontak',
                    'subtitle' => 'Lengkapi formulir di bawah untuk konsultasi motif, simulasi ongkir, atau dapatkan surat penawaran harga resmi untuk proyek hunian dan komersial.',
                    'alignment' => 'left',
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'map_location',
                'data' => [
                    'badge' => 'PETA LOKASI WORKSHOP',
                    'title' => 'Kunjungi Workshop & Pabrik Langsung',
                    'subtitle' => 'Kami menyambut kedatangan arsitek, kontraktor, mandor, dan calon pemilik rumah untuk melihat langsung stok fisik dan proses cetak di workshop kami.',
                    'address' => 'Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165',
                    'hours' => 'Senin – Sabtu, 08.00 – 17.00 WIB',
                    'map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.671569421715!2d107.35935457499427!3d-6.668991693325996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69073a5c4870d1%3A0x9daaab3cd6ae595d!2sIndoroster%20-%20Produsen%20Roster%20Minimalis%20%26%20Aneka%20Bata%20Murah!5e0!3m2!1sid!2sid!4v1740565000000!5m2!1sid!2sid',
                    'map_url' => 'https://www.google.com/maps/place/Indoroster+-+Produsen+Roster+Minimalis+%26+Aneka+Bata+Murah/@-6.6689917,107.3619295,19z/data=!4m6!3m5!1s0x2e69073a5c4870d1:0x9daaab3cd6ae595d!8m2!3d-6.6689917!4d107.3619295!16s%2Fg%2F11njz2_9sv',
                    'alignment' => 'left',
                    'bg_theme' => 'slate',
                ],
            ],
            [
                'type' => 'faq',
                'data' => [
                    'title' => 'Pertanyaan yang Sering Diajukan (FAQ)',
                    'description' => 'Jawaban cepat seputar cara pemesanan roster, pengiriman armada aman, dan garansi ganti baru.',
                    'limit' => 6,
                    'alignment' => 'center',
                    'bg_theme' => 'white',
                ],
            ],
        ];

        DB::table('pages')->updateOrInsert(
            ['slug' => 'kontak'],
            [
                'title' => 'Kontak',
                'meta_title' => 'Kontak Kami | INDOROSTER — Hubungi Pabrik Roster Beton',
                'meta_description' => 'Hubungi INDOROSTER untuk konsultasi, pemesanan roster beton minimalis, atau informasi pengiriman. Kami siap membantu Anda 24/7 via WhatsApp, telepon, atau email.',
                'content' => json_encode($contactBlocks),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 3. Proses Produksi Page
        $productionBlocks = [
            [
                'type' => 'hero',
                'data' => [
                    'slider_duration' => 5000,
                    'banners' => [
                        [
                            'image' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=1920&q=80',
                            'top_text' => 'TRANSPARANSI PRODUKSI PABRIK',
                            'badge' => 'SENTRA PLERED PURWAKARTA',
                            'title' => 'Proses Produksi Roster Presisi & Berkualitas',
                            'subtitle' => 'Saksikan langsung dari balik layar workshop kami: pemilihan agregat pasir silika, proses cetak tumbuk padat bertekanan tinggi, hingga pengawasan mutu (Quality Control) ketat sebelum pengiriman ke lokasi proyek Anda.',
                            'button_text' => 'Cek Video Produksi',
                            'button_url' => '#video-produksi',
                            'button_2_text' => 'Tanya Stok & Motif',
                            'button_2_url' => 'https://wa.me/6281389709847',
                            'alignment' => 'center',
                            'overlay_color' => '#020617',
                            'overlay_opacity' => '75',
                            'image_opacity' => '45',
                            'blur_level' => 'none',
                            'image_fit' => 'object-cover',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'strength_test',
                'data' => [
                    'title' => 'Uji Kekuatan & Ketahanan Beban di Lapangan',
                    'description' => 'Simak bukti nyata ketangguhan fisik roster beton IndoRoster. Teruji tahan benturan keras, tidak mudah gupil di bagian sudut, dan mampu menopang beban susunan dinding fasad tinggi dengan lurus dan presisi.',
                    'video_url' => 'https://res.cloudinary.com/indoroster/video/upload/v1765641154/1213_owgax5.mp4',
                    'alignment' => 'left',
                    'bg_theme' => 'white',
                    'features' => [
                        ['title' => 'Konstruksi Padat & Bebas Rongga Udara', 'desc' => 'Komposisi agregat pasir silika dan semen pilihan dengan pemadatan maksimal menghasilkan keping roster yang kokoh dan tahan retak.'],
                        ['title' => 'Cetakan Baja Presisi Tinggi', 'desc' => 'Sudut siku 90 derajat sempurna memudahkan tukang memasang dinding secara rapi dengan nat semen yang hemat dan tipis.'],
                        ['title' => 'Tahan Cuaca Panas & Hujan Tropis', 'desc' => 'Daya serap air rendah mencegah pelapukan dan lumut, menjaga fasad rumah tetap estetik dalam jangka panjang.'],
                        ['title' => 'Garansi Pengiriman Aman 100%', 'desc' => 'Jika ditemukan unit roster yang retak atau pecah saat dibongkar di lokasi proyek, kami ganti unit baru tanpa biaya tambahan.'],
                    ],
                ],
            ],
            [
                'type' => 'ugc_videos',
                'data' => [
                    'badge' => 'DOKUMENTASI WORKSHOP NYATA',
                    'title' => 'Tahapan Lengkap Proses Cetak & Quality Control',
                    'description' => 'Mulai dari pembuatan motif rumit, proses cetak manual bertonase presisi, pelepasan cetakan rapi, hingga tahap akhir pemilahan kualitas sebelum armada berangkat.',
                    'alignment' => 'center',
                    'button_text' => 'Konsultasi Kebutuhan Proyek',
                    'button_url' => 'https://wa.me/6281389709847',
                    'bg_theme' => 'dark',
                    'videos' => [
                        ['url' => 'https://res.cloudinary.com/indoroster/video/upload/v1765261084/5_ttttx3.mp4'],
                        ['url' => 'https://res.cloudinary.com/indoroster/video/upload/v1765261092/22_qoynzd.mp4'],
                        ['url' => 'https://res.cloudinary.com/indoroster/video/upload/v1765261204/3_pttu7e.mp4'],
                        ['url' => 'https://res.cloudinary.com/indoroster/video/upload/v1765261610/23_ajgyzf.mp4'],
                        ['url' => 'https://res.cloudinary.com/indoroster/video/upload/v1765641914/432_txkdbm.mp4'],
                        ['url' => 'https://res.cloudinary.com/indoroster/video/upload/v1765640940/1213_6_qhfncx.mp4'],
                    ],
                ],
            ],
            [
                'type' => 'quality_comparison',
                'data' => [
                    'badge' => 'KOMPARASI STANDAR MUTU',
                    'title' => 'Mengapa Roster Kami Berbeda dari Pasaran?',
                    'subtitle' => 'Bandingkan standar material dan kepresisian fisik roster pabrik IndoRoster dibanding produk pasaran biasa:',
                    'alignment' => 'center',
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'shipment_proof',
                'data' => [
                    'badge' => 'PENGIRIMAN & MUAT ARMADA',
                    'title' => 'Dokumentasi Muatan Harian Pabrik',
                    'description' => 'Armada truk dan kargo kami melayani pengiriman rutin ratusan ribu pieces ke Jabodetabek, Bandung, dan seluruh Indonesia.',
                    'alignment' => 'center',
                    'bg_theme' => 'slate',
                ],
            ],
            [
                'type' => 'cta',
                'data' => [
                    'badge' => 'Katalog & Penawaran Harga',
                    'title' => 'Tertarik Memesan Roster Langsung dari Pabrik?',
                    'subtitle' => 'Dapatkan katalog lengkap 50+ motif loster modern 20x20x10 cm, bata tempel, dan penawaran harga tangan pertama untuk proyek Anda.',
                    'button_text' => 'Konsultasi via WhatsApp',
                    'button_url' => 'https://wa.me/6281389709847',
                    'alignment' => 'center',
                    'bg_theme' => 'dark',
                ],
            ],
        ];

        DB::table('pages')->updateOrInsert(
            ['slug' => 'proses-produksi'],
            [
                'title' => 'Proses Produksi',
                'meta_title' => 'Proses Produksi Roster Beton | INDOROSTER — Pabrik Plered Purwakarta',
                'meta_description' => 'Transparansi proses produksi roster beton INDOROSTER dari cetakan hingga pengiriman. Presisi tinggi, bahan padat berkualitas, dan quality control ketat untuk setiap keping roster.',
                'content' => json_encode($productionBlocks),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No deletion needed
    }
};
