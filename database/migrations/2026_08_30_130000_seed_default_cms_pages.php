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
                'content' => [],
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
                'content' => [],
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
                'content' => [],
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
                'content' => [],
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
                'content' => [],
                'meta_title' => 'Pengadaan Roster Beton untuk Proyek Komersial, Cafe, & Gedung | IndoRoster',
                'meta_description' => 'Pabrik produsen roster beton dan bata expose untuk proyek komersial, hotel, villa, cafe, ruko, dan fasad gedung. Suplai volume ribuan keping dengan garansi mutu dan tepat waktu.',
                'is_active' => true,
                'created_at' => '2026-08-29T16:05:43.000000Z',
                'updated_at' => '2026-08-29T16:05:43.000000Z',
            ],
            14 => [
                'title' => 'Kalkulator Kebutuhan Roster Beton Dinding',
                'slug' => 'kalkulator-roster',
                'content' => [],
                'meta_title' => 'Kalkulator Kebutuhan Roster Beton Dinding | Hitung Akurat — IndoRoster',
                'meta_description' => 'Hitung estimasi kebutuhan jumlah keping roster beton per meter persegi (m2) secara akurat untuk dinding fasad, pagar, dan sekat partisi. Dilengkapi perhitungan safety waste.',
                'is_active' => true,
            ],
            15 => [
                'title' => 'Area Layanan Pengiriman Roster Beton Seluruh Indonesia',
                'slug' => 'lokasi',
                'content' => [],
                'meta_title' => 'Area Layanan Pengiriman Roster Beton Seluruh Indonesia | IndoRoster',
                'meta_description' => 'Daftar kota dan wilayah jangkauan pengiriman langsung armada truk pabrik IndoRoster: Jabodetabek, Bandung, Karawang, Cianjur, Cirebon, dan ekspedisi nasional se-Indonesia.',
                'is_active' => true,
            ],
        ];

        foreach ($pages as $p) {
            unset($p['id'], $p['created_at'], $p['updated_at']);
            Page::firstOrCreate(
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
