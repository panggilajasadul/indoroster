<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $pages = [
            0 => [
                'id' => 1,
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'content' => [
                    0 => [
                        'type' => 'hero',
                        'data' => [
                        ],
                    ],
                    1 => [
                        'type' => 'features',
                        'data' => [
                        ],
                    ],
                    2 => [
                        'type' => 'partner_cta',
                        'data' => [
                            'badge' => 'Kemitraan Pabrik & Pengadaan Proyek',
                            'title' => 'Terkoneksi Langsung dengan Pabrik Roster IndoRoster',
                            'description' => 'Solusi pengadaan roster beton arsitektural tangan pertama untuk pemilik rumah, kontraktor, arsitek, pemilik bisnis kafe, hingga developer kawasan perumahan di seluruh Indonesia.',
                            'bg_theme' => 'dark',
                            'cta_text_1' => 'Daftar Akun Mitra Sekarang',
                            'cta_url_1' => '/register',
                            'cta_text_2' => 'Konsultasi Pengadaan via WhatsApp',
                            'cta_url_2' => '',
                        ],
                    ],
                ],
                'meta_title' => 'Tentang Kami — Pabrik Roster Beton IndoRoster Plered Purwakarta',
                'meta_description' => 'Kenali IndoRoster lebih dekat sebagai sentra produksi tangan pertama roster beton arsitektural di Plered, Purwakarta. Berpengalaman melayani suplai ribuan pieces roster pagar, fasad, dan dinding.',
                'is_active' => true,
                'created_at' => '2026-08-27T10:28:35.000000Z',
                'updated_at' => '2026-08-27T10:42:51.000000Z',
            ],
            1 => [
                'id' => 2,
                'title' => 'Kontak Pabrik & Sales',
                'slug' => 'kontak',
                'content' => [
                    0 => [
                        'type' => 'contact_form',
                        'data' => [
                        ],
                    ],
                    1 => [
                        'type' => 'partner_cta',
                        'data' => [
                            'badge' => 'Kemitraan Pabrik & Pengadaan Proyek',
                            'title' => 'Terkoneksi Langsung dengan Pabrik Roster IndoRoster',
                            'description' => 'Solusi pengadaan roster beton arsitektural tangan pertama untuk pemilik rumah, kontraktor, arsitek, pemilik bisnis kafe, hingga developer kawasan perumahan di seluruh Indonesia.',
                            'bg_theme' => 'dark',
                            'cta_text_1' => 'Daftar Akun Mitra Sekarang',
                            'cta_url_1' => '/register',
                            'cta_text_2' => 'Konsultasi Pengadaan via WhatsApp',
                            'cta_url_2' => '',
                        ],
                    ],
                ],
                'meta_title' => 'Kontak Pabrik & Sales IndoRoster | Konsultasi Harga & Pengiriman',
                'meta_description' => 'Hubungi tim sales pabrik IndoRoster via WhatsApp untuk konsultasi motif roster, perhitungan kebutuhan dinding, diskon proyek, dan jadwal pengiriman armada truk.',
                'is_active' => true,
                'created_at' => '2026-08-27T10:28:36.000000Z',
                'updated_at' => '2026-08-27T10:42:51.000000Z',
            ],
            2 => [
                'id' => 3,
                'title' => 'Proses Produksi Pabrik',
                'slug' => 'proses-produksi',
                'content' => [
                    0 => [
                        'type' => 'hero',
                        'data' => [
                        ],
                    ],
                    1 => [
                        'type' => 'features',
                        'data' => [
                        ],
                    ],
                    2 => [
                        'type' => 'faq',
                        'data' => [
                        ],
                    ],
                    3 => [
                        'type' => 'partner_cta',
                        'data' => [
                            'badge' => 'Kemitraan Pabrik & Pengadaan Proyek',
                            'title' => 'Terkoneksi Langsung dengan Pabrik Roster IndoRoster',
                            'description' => 'Solusi pengadaan roster beton arsitektural tangan pertama untuk pemilik rumah, kontraktor, arsitek, pemilik bisnis kafe, hingga developer kawasan perumahan di seluruh Indonesia.',
                            'bg_theme' => 'dark',
                            'cta_text_1' => 'Daftar Akun Mitra Sekarang',
                            'cta_url_1' => '/register',
                            'cta_text_2' => 'Konsultasi Pengadaan via WhatsApp',
                            'cta_url_2' => '',
                        ],
                    ],
                ],
                'meta_title' => 'Proses Produksi Roster Beton Presisi | Pabrik IndoRoster Plered',
                'meta_description' => 'Melihat langsung proses pembuatan roster beton berkualitas tinggi di pabrik Plered Purwakarta. Cetak padat tumbuk pengrajin ahli, presisi siku, dan quality control ketat.',
                'is_active' => true,
                'created_at' => '2026-08-27T10:37:22.000000Z',
                'updated_at' => '2026-08-27T10:42:51.000000Z',
            ],
            3 => [
                'id' => 4,
                'title' => 'Syarat & Ketentuan',
                'slug' => 'syarat-dan-ketentuan',
                'content' => [
                    0 => [
                        'type' => 'faq',
                        'data' => [
                        ],
                    ],
                ],
                'meta_title' => 'Syarat & Ketentuan Pemesanan & Pengiriman | Pabrik IndoRoster',
                'meta_description' => 'Informasi resmi mengenai syarat pemesanan, tata cara pembayaran Midtrans, pengiriman armada pabrik, dan garansi ganti baru 100% jika ada keping roster yang pecah di jalan.',
                'is_active' => true,
                'created_at' => '2026-08-27T10:37:22.000000Z',
                'updated_at' => '2026-08-27T10:37:22.000000Z',
            ],
            4 => [
                'id' => 5,
                'title' => 'Kebijakan Privasi',
                'slug' => 'kebijakan-privasi',
                'content' => [
                    0 => [
                        'type' => 'faq',
                        'data' => [
                        ],
                    ],
                ],
                'meta_title' => 'Kebijakan Privasi & Keamanan Transaksi | IndoRoster',
                'meta_description' => 'Kebijakan perlindungan data pelanggan dan standar enkripsi transaksi pembayaran resmi di platform IndoRoster Indonesia.',
                'is_active' => true,
                'created_at' => '2026-08-27T10:37:22.000000Z',
                'updated_at' => '2026-08-27T10:37:22.000000Z',
            ],
            5 => [
                'id' => 6,
                'title' => 'Beranda Utama (Home)',
                'slug' => 'home',
                'content' => [],
                'meta_title' => 'Pabrik Roster Beton Minimalis Jabodetabek | IndoRoster',
                'meta_description' => 'Pabrik roster beton minimalis & bata expose cetak padat presisi. Melayani kirim Jabodetabek, Bandung & nasional dengan garansi aman bebas pecah.',
                'is_active' => true,
                'created_at' => '2026-08-27T10:42:51.000000Z',
                'updated_at' => '2026-08-29T18:31:33.000000Z',
            ],
            6 => [
                'id' => 7,
                'title' => 'Katalog Produk & Motif Roster',
                'slug' => 'katalog',
                'content' => [
                    0 => [
                        'type' => 'product_grid',
                        'data' => [
                        ],
                    ],
                    1 => [
                        'type' => 'partner_cta',
                        'data' => [
                            'badge' => 'Kemitraan Pabrik & Pengadaan Proyek',
                            'title' => 'Terkoneksi Langsung dengan Pabrik Roster IndoRoster',
                            'description' => 'Solusi pengadaan roster beton arsitektural tangan pertama untuk pemilik rumah, kontraktor, arsitek, pemilik bisnis kafe, hingga developer kawasan perumahan di seluruh Indonesia.',
                            'bg_theme' => 'dark',
                            'cta_text_1' => 'Daftar Akun Mitra Sekarang',
                            'cta_url_1' => '/register',
                            'cta_text_2' => 'Konsultasi Pengadaan via WhatsApp',
                            'cta_url_2' => null,
                        ],
                    ],
                ],
                'meta_title' => 'Katalog Roster Beton Minimalis & Bata Tempel | IndoRoster Pabrik Plered',
                'meta_description' => 'Temukan 50+ motif roster beton minimalis untuk pagar, fasad, partisi, dan ventilasi rumah. Kualitas cetak padat presisi langsung dari pabrik tangan pertama Plered Purwakarta.',
                'is_active' => true,
                'created_at' => '2026-08-27T10:42:51.000000Z',
                'updated_at' => '2026-08-29T16:01:27.000000Z',
            ],
            7 => [
                'id' => 8,
                'title' => 'Galeri Proyek & Inspirasi Fasad',
                'slug' => 'gallery',
                'content' => [
                    0 => [
                        'type' => 'hero',
                        'data' => [
                        ],
                    ],
                    1 => [
                        'type' => 'partner_cta',
                        'data' => [
                            'badge' => 'Kemitraan Pabrik & Pengadaan Proyek',
                            'title' => 'Terkoneksi Langsung dengan Pabrik Roster IndoRoster',
                            'description' => 'Solusi pengadaan roster beton arsitektural tangan pertama untuk pemilik rumah, kontraktor, arsitek, pemilik bisnis kafe, hingga developer kawasan perumahan di seluruh Indonesia.',
                            'bg_theme' => 'dark',
                            'cta_text_1' => 'Daftar Akun Mitra Sekarang',
                            'cta_url_1' => '/register',
                            'cta_text_2' => 'Konsultasi Pengadaan via WhatsApp',
                            'cta_url_2' => '',
                        ],
                    ],
                ],
                'meta_title' => 'Galeri Proyek Roster Beton Minimalis & Fasad Rumah | IndoRoster',
                'meta_description' => 'Inspirasi foto proyek nyata pemasangan roster beton minimalis, pagar modern, dinding ventilasi, dan partisi interior estetis dari pelanggan di seluruh Indonesia.',
                'is_active' => true,
                'created_at' => '2026-08-27T10:42:51.000000Z',
                'updated_at' => '2026-08-27T10:42:51.000000Z',
            ],
            8 => [
                'id' => 9,
                'title' => 'Video Inspirasi & Dokumentasi Proyek',
                'slug' => 'indoroster-video',
                'content' => [
                    0 => [
                        'type' => 'hero',
                        'data' => [
                        ],
                    ],
                    1 => [
                        'type' => 'partner_cta',
                        'data' => [
                            'badge' => 'Kemitraan Pabrik & Pengadaan Proyek',
                            'title' => 'Terkoneksi Langsung dengan Pabrik Roster IndoRoster',
                            'description' => 'Solusi pengadaan roster beton arsitektural tangan pertama untuk pemilik rumah, kontraktor, arsitek, pemilik bisnis kafe, hingga developer kawasan perumahan di seluruh Indonesia.',
                            'bg_theme' => 'dark',
                            'cta_text_1' => 'Daftar Akun Mitra Sekarang',
                            'cta_url_1' => '/register',
                            'cta_text_2' => 'Konsultasi Pengadaan via WhatsApp',
                            'cta_url_2' => '',
                        ],
                    ],
                ],
                'meta_title' => 'Video Inspirasi Pasang Roster Beton & Review Proyek | IndoRoster',
                'meta_description' => 'Tonton video dokumentasi proyek pemasangan roster beton minimalis, tutorial aplikasi dinding, dan ulasan langsung dari pembeli di seluruh Indonesia.',
                'is_active' => true,
                'created_at' => '2026-08-27T10:42:51.000000Z',
                'updated_at' => '2026-08-27T10:42:51.000000Z',
            ],
            9 => [
                'id' => 10,
                'title' => 'Katalog Teknis & Material Roster (Khusus Arsitek & Desainer)',
                'slug' => 'untuk-arsitek',
                'content' => [
                    0 => [
                        'type' => 'why_us',
                        'data' => [
                            'title' => 'Keunggulan Spesifikasi Material Roster untuk Arsitek',
                            'description' => 'Spesifikasi dimensi terstandarisasi, rasio pencahayaan alami & ventilasi silang optimal, akurasi sudut siku 90°, serta dukungan sampel fisik untuk presentasi moodboard kepada klien Anda.',
                            'bg_theme' => 'slate',
                            'items' => [
                                0 => [
                                    'title' => 'Sampel Fisik Presentasi Klien',
                                    'description' => 'Kami siap mengirimkan sampel keping roster fisik ke studio arsitektur atau kantor konsultan Anda untuk mencocokkan tekstur, warna, dan proporsi skala desain.',
                                ],
                                1 => [
                                    'title' => 'Desain Bioklimatik Tropis',
                                    'description' => 'Mereduksi panas radiasi matahari langsung (shading device) sekaligus menjaga ventilasi udara silang (cross-ventilation) alami untuk efisiensi energi bangunan.',
                                ],
                                2 => [
                                    'title' => 'Eksplorasi Motif Geometris & Custom',
                                    'description' => 'Tersedia puluhan variasi motif mulai dari minimalis kotak, nako, bunga, melati, bintang, terakota, hingga opsi motif khusus untuk volume proyek tertentu.',
                                ],
                            ],
                        ],
                    ],
                    1 => [
                        'type' => 'featured_products',
                        'data' => [
                            'badge' => 'Koleksi Arsitek',
                            'title' => 'Pilihan Koleksi Motif Roster Arsitektural & Geometris',
                            'subtitle' => 'Seluruh katalog motif roster beton minimalis presisi & bata expose siap suplai langsung dari pabrik.',
                            'limit' => 24,
                            'bg_theme' => 'white',
                        ],
                    ],
                    2 => [
                        'type' => 'partner_cta',
                        'data' => [
                            'badge' => 'Konsultasi Desain & Moodboard',
                            'title' => 'Butuh Sampel Fisik atau File 3D Roster untuk Proyek Anda?',
                            'description' => 'Tim teknis IndoRoster siap berdiskusi mengenai detail ukuran, rasio pori angin, dan rekomendasi motif terbaik sesuai konsep arsitektur bangunan Anda.',
                            'bg_theme' => 'dark',
                            'cta_text_1' => 'Request Sampel Fisik via WhatsApp',
                            'cta_url_1' => 'https://wa.me/6281389709847',
                            'cta_text_2' => 'Lihat Galeri Inspirasi Fasad',
                            'cta_url_2' => '/gallery',
                        ],
                    ],
                ],
                'meta_title' => 'Katalog Roster Arsitektur untuk Arsitek & Desainer Interior | IndoRoster',
                'meta_description' => 'Pusat eksplorasi material roster beton arsitektural untuk arsitek dan interior designer. Dimensi presisi, rasio ventilasi optimal, konsultasi motif custom, sampel fisik & katalog PDF.',
                'is_active' => true,
                'created_at' => '2026-08-29T16:05:43.000000Z',
                'updated_at' => '2026-08-29T16:05:43.000000Z',
            ],
            10 => [
                'id' => 11,
                'title' => 'Suplier Roster Beton & Pemborong (Khusus Kontraktor Proyek)',
                'slug' => 'untuk-kontraktor',
                'content' => [
                    0 => [
                        'type' => 'why_us',
                        'data' => [
                            'title' => 'Kemitraan Suplai Andal untuk Kontraktor & Pemborong',
                            'description' => 'Kapasitas produksi ribuan keping per hari, sudut siku 90° presisi untuk efisiensi tukang, jadwal kirim bertahap (batch delivery), dan kelengkapan dokumen resmi.',
                            'bg_theme' => 'slate',
                            'items' => [
                                0 => [
                                    'title' => 'Kapasitas Suplai Skala Besar',
                                    'description' => 'Kapasitas produksi pabrik mencapai ribuan keping per hari, siap mengamankan suplai volume proyek besar tanpa risiko keterlambatan progres tukang.',
                                ],
                                1 => [
                                    'title' => 'Siku 90° Presisi — Pasang Cepat',
                                    'description' => 'Tingkat kepresisian dimensi tinggi dengan sudut siku 90° seragam. Menghemat waktu kerja tukang di lapangan dan meminimalkan pemakaian semen nat adukan.',
                                ],
                                2 => [
                                    'title' => 'Dokumen Administrasi Lengkap',
                                    'description' => 'Surat Jalan resmi, Invoice Komersial, Faktur Pajak, dan kelengkapan dokumen pengadaan siap diterbitkan cepat untuk pelaporan pengadaan proyek B2B / BUMN.',
                                ],
                            ],
                        ],
                    ],
                    1 => [
                        'type' => 'featured_products',
                        'data' => [
                            'badge' => 'Proyek Fasad & Pagar',
                            'title' => 'Katalog Lengkap Roster Beton untuk Proyek & Kontraktor',
                            'subtitle' => 'Pilihan terfavorit pemborong bangunan dengan kapasitas produksi ribuan pcs/hari dan siku presisi 90°.',
                            'limit' => 24,
                            'bg_theme' => 'white',
                        ],
                    ],
                    2 => [
                        'type' => 'partner_cta',
                        'data' => [
                            'badge' => 'Penawaran RAB Proyek',
                            'title' => 'Siap Mengirim Penawaran Terbaik untuk Proyek Anda',
                            'description' => 'Diskusikan kebutuhan volume, jadwal pengiriman, dan dapatkan pricelist grosir harga pabrik langsung dari IndoRoster.',
                            'bg_theme' => 'dark',
                            'cta_text_1' => 'Hubungi Sales Proyek via WhatsApp',
                            'cta_url_1' => 'https://wa.me/6281389709847',
                            'cta_text_2' => 'Kalkulator Kebutuhan Dinding',
                            'cta_url_2' => '/kalkulator-roster',
                        ],
                    ],
                ],
                'meta_title' => 'Supplier Roster Beton untuk Kontraktor & Pemborong Proyek | IndoRoster',
                'meta_description' => 'Pabrik produsen roster beton resmi terpercaya untuk kontraktor & pemborong bangunan. Kapasitas ribuan pcs/hari, siku presisi 90°, harga grosir volume, surat jalan & faktur resmi. Kirim Jabodetabek & seluruh Indonesia.',
                'is_active' => true,
                'created_at' => '2026-08-29T16:05:43.000000Z',
                'updated_at' => '2026-08-29T16:05:43.000000Z',
            ],
            11 => [
                'id' => 12,
                'title' => 'Pengadaan Roster Skala Klaster (Khusus Developer Perumahan)',
                'slug' => 'untuk-developer',
                'content' => [
                    0 => [
                        'type' => 'why_us',
                        'data' => [
                            'title' => 'Solusi Pengadaan Roster untuk Pengembang Perumahan',
                            'description' => 'Jaminan keseragaman warna dan mutu cetak padat untuk puluhan hingga ratusan unit rumah cluster, fasad gerbang utama (main gate), serta clubhouse.',
                            'bg_theme' => 'slate',
                            'items' => [
                                0 => [
                                    'title' => 'Keseragaman Mutu & Warna Massal',
                                    'description' => 'Formula adukan semen dan pasir terstandarisasi menjamin keseragaman warna abu-abu natural maupun putih di seluruh unit perumahan klaster Anda.',
                                ],
                                1 => [
                                    'title' => 'Pengiriman Bertahap Sesuai SPK',
                                    'description' => 'Jadwal pengiriman armada truk disinkronkan dengan milestone progres konstruksi tiap blok unit rumah di lapangan.',
                                ],
                                2 => [
                                    'title' => 'Kontrak Suplai Harga Terkunci',
                                    'description' => 'Skema kerja sama kontrak dengan harga terkunci (fixed price) selama masa pembangunan proyek perumahan berlangsung.',
                                ],
                            ],
                        ],
                    ],
                    1 => [
                        'type' => 'featured_products',
                        'data' => [
                            'badge' => 'Fasad Klaster Perumahan',
                            'title' => 'Katalog Roster Favorit Fasad Perumahan & Cluster',
                            'subtitle' => 'Jaminan keseragaman warna dan mutu cetak padat untuk puluhan hingga ratusan unit rumah klaster.',
                            'limit' => 24,
                            'bg_theme' => 'white',
                        ],
                    ],
                    2 => [
                        'type' => 'partner_cta',
                        'data' => [
                            'badge' => 'Kemitraan Developer',
                            'title' => 'Bermitra dengan Pabrik Roster Resmi Terpercaya',
                            'description' => 'Dapatkan kepastian jadwal suplai material, spesifikasi terstandarisasi, dan harga khusus pengembang perumahan.',
                            'bg_theme' => 'dark',
                            'cta_text_1' => 'Konsultasi Pengadaan Developer via WA',
                            'cta_url_1' => 'https://wa.me/6281389709847',
                            'cta_text_2' => 'Lihat Direktori Layanan Wilayah',
                            'cta_url_2' => '/lokasi',
                        ],
                    ],
                ],
                'meta_title' => 'Pengadaan Roster Beton untuk Developer Perumahan & Cluster | IndoRoster',
                'meta_description' => 'Mitra pengadaan roster beton minimalis untuk developer perumahan dan klaster hunian. Keseragaman motif puluhan unit rumah, jaminan suplai kontinyu harga pabrik langsung.',
                'is_active' => true,
                'created_at' => '2026-08-29T16:05:43.000000Z',
                'updated_at' => '2026-08-29T16:05:43.000000Z',
            ],
            12 => [
                'id' => 13,
                'title' => 'Distributor & Grosir Pabrik (Khusus Toko Bangunan & Agen Material)',
                'slug' => 'supplier-roster-beton',
                'content' => [
                    0 => [
                        'type' => 'why_us',
                        'data' => [
                            'title' => 'Program Grosir Toko Bangunan & Agen Material',
                            'description' => 'Dapatkan margin keuntungan maksimal untuk toko bangunan, depo material, dan distributor daerah dengan skema harga per truk (ritase) paling kompetitif.',
                            'bg_theme' => 'slate',
                            'items' => [
                                0 => [
                                    'title' => 'Harga Pabrik Langsung',
                                    'description' => 'Langsung dari pusat produksi. Anda mendapatkan harga terendah dari pabrik sehingga toko atau depo material Anda dapat menjual kembali dengan margin profit yang sangat sehat.',
                                ],
                                1 => [
                                    'title' => 'Skema Pengiriman Per Truk (Ritase)',
                                    'description' => 'Armada truk siap antar hingga depan toko Anda di area Jabodetabek, Bandung, Karawang, Cirebon, dan seluruh Jawa Barat dengan garansi pecah 100%.',
                                ],
                                2 => [
                                    'title' => 'Perputaran Stok Cepat & Varian Lengkap',
                                    'description' => 'Motif-motif paling laris selalu ready stock di pabrik kami, siap re-stock cepat kapan pun inventori toko material Anda menipis.',
                                ],
                            ],
                        ],
                    ],
                    1 => [
                        'type' => 'featured_products',
                        'data' => [
                            'badge' => 'Grosir Toko Material',
                            'title' => 'Katalog Produk Grosir Toko Bangunan',
                            'subtitle' => 'Koleksi motif roster beton & bata expose paling laris di pasaran ritel toko bangunan.',
                            'limit' => 24,
                            'bg_theme' => 'white',
                        ],
                    ],
                    2 => [
                        'type' => 'partner_cta',
                        'data' => [
                            'badge' => 'Program Reseller & Agen',
                            'title' => 'Daftar Menjadi Mitra Toko Bangunan / Distributor Resmi',
                            'description' => 'Dapatkan katalog fisik toko, sampel display, dan ketentuan diskon grosir volume pabrik.',
                            'bg_theme' => 'dark',
                            'cta_text_1' => 'Hubungi Tim Grosir Pabrik (WhatsApp)',
                            'cta_url_1' => 'https://wa.me/6281389709847',
                            'cta_text_2' => 'Katalog Produk Lengkap',
                            'cta_url_2' => '/produk',
                        ],
                    ],
                ],
                'meta_title' => 'Supplier Roster Beton & Grosir Pabrik Resmi | IndoRoster',
                'meta_description' => 'Pabrik supplier roster beton untuk toko bangunan, agen, dan distributor material. Skema harga grosir per truk/ritase, stok ribuan pcs, pengiriman cepat se-Indonesia.',
                'is_active' => true,
                'created_at' => '2026-08-29T16:05:43.000000Z',
                'updated_at' => '2026-08-29T16:05:43.000000Z',
            ],
            13 => [
                'id' => 14,
                'title' => 'Pengadaan Roster Beton untuk Proyek Fasad & Gedung Komersial',
                'slug' => 'roster-beton-proyek',
                'content' => [
                    0 => [
                        'type' => 'why_us',
                        'data' => [
                            'title' => 'Pengadaan Roster Beton untuk Proyek Komersial & Gedung',
                            'description' => 'Solusi fasad arsitektural, partisi ventilasi estetis, dan ornamen dinding kokoh untuk proyek hotel, villa resort, cafe & restoran modern, ruko, masjid, hingga gedung perkantoran.',
                            'bg_theme' => 'slate',
                            'items' => [
                                0 => [
                                    'title' => 'Estetika Fasad Komersial Modern',
                                    'description' => 'Menciptakan bayangan pencahayaan alami dramatis dan sirkulasi sejuk yang sangat memikat untuk cafe, hotel, dan bangunan publik.',
                                ],
                                1 => [
                                    'title' => 'Kekuatan Beton Mutu Tinggi',
                                    'description' => 'Kepadatan tinggi formula beton tumbuk padat tahan terpaan angin badai dan paparan cuaca luar gedung selama puluhan tahun.',
                                ],
                                2 => [
                                    'title' => 'Dukungan Teknis & Konsultasi Volume',
                                    'description' => 'Perhitungan akurat volume keping dinding per m2, estimasi biaya material, dan skema pengiriman per ritase armada pabrik.',
                                ],
                            ],
                        ],
                    ],
                    1 => [
                        'type' => 'featured_products',
                        'data' => [
                            'badge' => 'Fasad Komersial',
                            'title' => 'Katalog Motif Roster Terbaik untuk Fasad Komersial',
                            'subtitle' => 'Solusi material dinding arsitektural untuk hotel, cafe resto, villa resort, dan ruko komersial.',
                            'limit' => 24,
                            'bg_theme' => 'white',
                        ],
                    ],
                    2 => [
                        'type' => 'partner_cta',
                        'data' => [
                            'badge' => 'Pengadaan Tender & BoQ',
                            'title' => 'Punya Rencana Proyek Gedung / Cafe / Hotel?',
                            'description' => 'Konsultasikan kebutuhan desain dan pengadaan material Anda dengan tim proyek IndoRoster.',
                            'bg_theme' => 'dark',
                            'cta_text_1' => 'Hubungi Tim Proyek via WhatsApp',
                            'cta_url_1' => 'https://wa.me/6281389709847',
                            'cta_text_2' => 'Lihat Galeri Inspirasi Fasad',
                            'cta_url_2' => '/gallery',
                        ],
                    ],
                ],
                'meta_title' => 'Pengadaan Roster Beton untuk Proyek Komersial, Cafe, & Gedung | IndoRoster',
                'meta_description' => 'Pabrik produsen roster beton dan bata expose untuk proyek komersial, hotel, villa, cafe, ruko, dan fasad gedung. Suplai volume ribuan keping dengan garansi mutu dan tepat waktu.',
                'is_active' => true,
                'created_at' => '2026-08-29T16:05:43.000000Z',
                'updated_at' => '2026-08-29T16:05:43.000000Z',
            ],
        ];

        foreach ($pages as $p) {
            unset($p['id'], $p['created_at'], $p['updated_at']);
            Page::updateOrCreate(
                ['slug' => $p['slug']],
                $p
            );
        }
    }

    public function down(): void
    {
        // No-op to preserve user edits
    }
};
