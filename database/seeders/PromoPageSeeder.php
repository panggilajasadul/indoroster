<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\PromoPage;
use Illuminate\Database\Seeder;

class PromoPageSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil beberapa gambar galeri asli jika ada
        $galleries = Gallery::with('media')->where('is_active', true)->take(6)->get();
        $g1 = $galleries->get(0)?->media?->first()?->media_url ?? 'assets/logo_indoroster_no_text.PNG';
        $g2 = $galleries->get(1)?->media?->first()?->media_url ?? 'assets/logo_indoroster_no_text.PNG';
        $g3 = $galleries->get(2)?->media?->first()?->media_url ?? 'assets/logo_indoroster_no_text.PNG';
        $g4 = $galleries->get(3)?->media?->first()?->media_url ?? 'assets/logo_indoroster_no_text.PNG';

        $defaultSections = [
            'top_strip' => [
                'badge' => '🚚 PROMO GRATIS ONGKIR',
                'text' => 'Armada Langsung ke Jabodetabek, Seluruh Jawa Barat & Banten',
                'warranty' => '🛡️ Garansi 100% Pecah Ganti Baru di Tempat',
            ],
            'hero' => [
                'badge_1' => '🚚 Gratis Ongkir Jabodetabek, Jabar & Banten',
                'badge_2' => 'IndoRoster — Pusat Roster Beton Minimalis',
                'badge_3' => '⭐ Min. Order 100 Pcs',
                'headline' => 'Harga Pabrik Langsung Roster Beton Minimalis — Kirim Cepat ke',
                'subtext' => 'Beli di toko material bangunan harganya bisa mencapai Rp 15.000 – Rp 25.000/pcs plus ongkir mahal. Di IndoRoster, Anda dapat harga tangan pertama pabrik langsung mulai Rp 12.500/pcs dengan kualitas padat dan presisi dan Gratis Ongkir armada pabrik!',
                'cta_text' => 'Klaim Promo Ongkir & Harga Pabrik',
                'image_url' => $g1,
                'card_title' => 'Siku 90° Presisi Milimeter — Fasad Rapi, Tukang Pasang Cepat & Hemat Semen',
                'card_price' => 'Mulai Rp 12.500/pcs',
            ],
            'delivery_proof' => [
                'badge' => '🚚 Real Armada Pengiriman',
                'title' => 'Bukti Muatan Armada Pengiriman Langsung Pabrik',
                'subtitle' => 'Dokumentasi nyata persiapan muat keping roster & keberangkatan armada pabrik IndoRoster setiap hari menuju alamat proyek Anda.',
                'items' => [
                    [
                        'title' => 'Muatan 800 Pcs Truk CDD Pabrik',
                        'destination' => 'Bekasi & Cikarang',
                        'status_badge' => '🚚 Armada Berangkat',
                        'caption' => 'Roster motif minimalis pesanan kontraktor cluster perumahan Harapan Indah. Packing palet rapi terlindungi terpal tebal.',
                        'image_url' => $g1,
                    ],
                    [
                        'title' => 'Muatan 500 Pcs Mobil Pick-Up',
                        'destination' => 'Bandung Raya & Cimahi',
                        'status_badge' => '🚚 On The Way (Tol Cipularang)',
                        'caption' => 'Pesanan renovasi fasad rumah tinggal Buahbatu. Pengiriman cepat same-day sampai langsung depan pagar.',
                        'image_url' => $g2,
                    ],
                    [
                        'title' => 'Muatan 1.200 Pcs Truk Engkel',
                        'destination' => 'Tangerang & Banten',
                        'status_badge' => '🚚 Siap Antar',
                        'caption' => 'Pengiriman berkala roster terakota & abu batu untuk partisi ruko komersial BSD Serpong.',
                        'image_url' => $g3,
                    ],
                ],
            ],
            'received_proof' => [
                'badge' => '📦 Serah Terima & Unloading',
                'title' => 'Bukti Barang Tiba & Penurunan di Lokasi Pembeli',
                'subtitle' => 'Kondisi keping roster diterima 100% utuh tanpa sompal di depan gerbang proyek pembeli. Supir dan tim armada membantu proses penurunan barang.',
                'items' => [
                    [
                        'customer_name' => 'Bpk. Hendra S. (Kontraktor)',
                        'location' => 'Bekasi, Jawa Barat',
                        'verification_badge' => '⭐ Terverifikasi Tiba 100% Utuh',
                        'order_volume' => 'Order: 800 pcs Roster Kotak',
                        'testimony_quote' => 'Muatan 800 pcs sudah diturunkan rapi di lokasi proyek. Siku plat bajanya sangat presisi, keping utuh tidak ada yang gompal. Tukang pasang langsung selesai cepat.',
                        'image_url' => $g2,
                    ],
                    [
                        'customer_name' => 'Ibu Anita (Owner Rumah)',
                        'location' => 'Bandung, Jawa Barat',
                        'verification_badge' => '⭐ Terverifikasi Tiba 100% Utuh',
                        'order_volume' => 'Order: 250 pcs Roster Abu Batu',
                        'testimony_quote' => 'Barang sampai tepat waktu di Bandung. Supir ramah bantu turunkan barang ke halaman. Ada 2 keping retak di jalan langsung diganti baru di tempat tanpa ribet.',
                        'image_url' => $g3,
                    ],
                    [
                        'customer_name' => 'David K. (Owner Cafe)',
                        'location' => 'Jakarta Selatan',
                        'verification_badge' => '⭐ Terverifikasi Tiba 100% Utuh',
                        'order_volume' => 'Order: 450 pcs Roster Terakota',
                        'testimony_quote' => 'Partisi outdoor cafe kami pakai roster terakota IndoRoster. Warnanya natural mewah, estetik sekali saat sore hari. Sangat puas dengan pelayanan pabrik!',
                        'image_url' => $g4,
                    ],
                ],
            ],
            'comparison_cost' => [
                'title' => 'Kenapa Beli Roster di Toko Material Bisa 2x Lipat Lebih Mahal?',
                'subtitle' => 'Toko bangunan biasa mengambil dari perantara dan menaikkan harga hingga Rp 15.000 – Rp 25.000 / pcs. Beli langsung dari IndoRoster — Pusat Roster Beton Minimalis, Anda dapat harga asli pabrik tangan pertama plus garansi aman.',
                'pabrik_price_start' => 'Mulai Rp 12.500 / pcs',
                'simulations' => [
                    [
                        'label' => 'Kebutuhan 300 Pcs (Fasad Kecil)',
                        'badge' => '',
                        'store_price' => 'Toko Material: Rp 5.400.000',
                        'factory_price' => 'Pabrik: Rp 3.750.000',
                        'savings_label' => 'HEMAT Rp 1.650.000',
                    ],
                    [
                        'label' => 'Kebutuhan 500 Pcs (Fasad & Pagar)',
                        'badge' => 'Paling Umum',
                        'store_price' => 'Toko Material: Rp 9.500.000',
                        'factory_price' => 'Pabrik: Rp 6.250.000',
                        'savings_label' => 'HEMAT Rp 3.250.000',
                    ],
                    [
                        'label' => 'Kebutuhan 1.000 Pcs (Proyek / Cafe)',
                        'badge' => '',
                        'store_price' => 'Toko Material: Rp 19.000.000',
                        'factory_price' => 'Pabrik: Rp 12.500.000',
                        'savings_label' => 'HEMAT Rp 6.500.000+',
                    ],
                ],
            ],
            'comparison_quality' => [
                'title' => 'Mengapa 90% Kontraktor & Arsitek Memilih Roster Beton Minimalis IndoRoster?',
                'subtitle' => 'Beli roster abal-abal terlihat murah beberapa ratus rupiah di awal, tapi rugi jutaan rupiah karena dinding miring dan boros semen nat.',
                'bad_items' => [
                    ['title' => 'Sudut Miring & Tidak Siku', 'desc' => 'Dinding bergelombang saat disusun tinggi, tukang lambat kerja.'],
                    ['title' => 'Banyak Campuran Tanah & Pasir Rapuh', 'desc' => 'Pori kasar menyerap air hujan, cepat berlumut hitam dan gompal.'],
                    ['title' => 'Boros Semen Acian', 'desc' => 'Harus menambal selisih ketebalan, biaya semen membengkak 30%.'],
                    ['title' => 'Tanpa Garansi Pecah', 'desc' => 'Barang rusak di jalan ditanggung pembeli sendiri.'],
                ],
                'good_items' => [
                    ['title' => 'Siku Presisi 90° Plat Baja', 'desc' => 'Ukuran milimeter konsisten, dinding tegak lurus rata & hemat semen nat.'],
                    ['title' => 'Pasir Abu Batu Murni', 'desc' => 'Kepadatan tinggi tanpa rongga rapuh, tahan cuaca ekstrem & anti lumut.'],
                    ['title' => 'Standar Uji Kuat Tekan', 'desc' => 'Aman untuk dinding partisi tinggi, secondary skin lantai 2, dan pagar luar.'],
                    ['title' => 'Garansi 100% Pecah Ganti Baru', 'desc' => 'Supir armada langsung ganti keping baru di tempat saat penurunan keping.'],
                ],
            ],
            'catalog' => [
                'title' => 'Pilihan Motif Roster Terlaris (20×20 cm)',
                'subtitle' => 'Standar modular 20×20×10 cm (25 pcs/m²). Tersedia warna Abu Natural (Abu Batu Murni), Putih Bersih (Dolomit), dan Merah Terakota.',
                'product_limit' => 8,
            ],
            'gallery' => [
                'title' => 'Inspirasi Hasil Pemasangan Roster di Lapangan',
                'subtitle' => 'Dokumentasi nyata hasil pasang fasad rumah tinggal, pagar villa, sekat cafe, dan ventilasi gedung di Jabodetabek & Jawa Barat.',
            ],
            'shipping_coverage' => [
                'badge' => '🚚 Jangkauan Kirim & Promo Ongkir',
                'title' => 'Gratis Ongkir Armada Pabrik ke Wilayah Anda',
                'subtitle' => 'Armada mobil pick-up, truk engkel, hingga truk Colt Diesel siap antar langsung dari pabrik IndoRoster ke depan gerbang proyek Anda.',
                'regions' => [
                    [
                        'region_name' => 'Jabodetabek',
                        'sub_label' => 'Wilayah Utama',
                        'eta_badge' => '1–2 Hari Sampai',
                        'promo_badge' => 'Gratis Ongkir',
                        'coverage_cities' => 'Jakarta (Pusat, Selatan, Timur, Barat, Utara), Bogor, Depok, Tangerang Raya, Bekasi',
                        'features' => "✓ GRATIS ONGKIR (Min. volume order)\n✓ Pengiriman setiap hari kerja\n✓ Supir bantu penurunan keping di lokasi",
                    ],
                    [
                        'region_name' => 'Seluruh Area Jawa Barat',
                        'sub_label' => 'Armada Khusus Pabrik',
                        'eta_badge' => 'Same Day / 1 Hari',
                        'promo_badge' => 'Gratis Ongkir',
                        'coverage_cities' => 'Bandung Raya, Karawang, Purwakarta, Subang, Cianjur, Sukabumi, Cirebon, Sumedang, Garut, Tasikmalaya',
                        'features' => "✓ GRATIS ONGKIR radius terdekat pabrik\n✓ Jalur tol langsung tanpa macet\n✓ Pengiriman jumlah kecil hingga skala tronton",
                    ],
                    [
                        'region_name' => 'Provinsi Banten',
                        'sub_label' => 'Jalur Barat',
                        'eta_badge' => '1–2 Hari Sampai',
                        'promo_badge' => 'Gratis Ongkir',
                        'coverage_cities' => 'Serang, Cilegon, Pandeglang, Rangkasbitung Lebak, Tangerang Kota & Tangerang Selatan',
                        'features' => "✓ GRATIS ONGKIR armada rutin jalur Banten\n✓ Packing palet kayu aman terlindungi terpal\n✓ Garansi tukar baru jika ada keping retak",
                    ],
                ],
            ],
            'tiering' => [
                'badge' => 'Pilihan Paket Volume',
                'title' => 'Paket Pemesanan Roster Sesuai Skala Proyek',
                'tiers' => [
                    [
                        'audience_tag' => 'Rumah Tinggal',
                        'package_name' => 'Paket Renovasi & Sekat',
                        'volume_range' => '100 – 300 Pcs',
                        'badge_highlight' => '',
                        'is_featured' => false,
                        'button_text' => 'Pesan Paket Ini (WA)',
                        'description' => 'Untuk partisi ruang tamu, pagar depan mini, ventilasi kamar mandi, atau sekat dapur.',
                        'features' => "✓ Bebas campur motif best seller\n✓ Pengiriman mobil pick-up pabrik\n✓ Garansi 100% ganti pecah di tempat",
                    ],
                    [
                        'audience_tag' => 'Villa & Fasad',
                        'package_name' => 'Paket Fasad & Pagar',
                        'volume_range' => '300 – 1.000 Pcs',
                        'badge_highlight' => 'Paling Favorit',
                        'is_featured' => true,
                        'button_text' => 'Pesan Paket Ini (WA)',
                        'description' => 'Pilihan paling populer untuk fasad 2 lantai, dinding secondary skin, dan pagar keliling hunian.',
                        'features' => "✓ Gratis Ongkir area Jabodetabek & Jabar\n✓ Pengiriman truk engkel pabrik\n✓ Konsultasi hitung kebutuhan gratis",
                    ],
                    [
                        'audience_tag' => 'Kontraktor & B2B',
                        'package_name' => 'Paket Proyek & Grosir',
                        'volume_range' => '1.000+ Pcs',
                        'badge_highlight' => '',
                        'is_featured' => false,
                        'button_text' => 'Minta Penawaran B2B (WA)',
                        'description' => 'Untuk developer perumahan, kontraktor gedung, arsitek lanskap, dan distributor toko material.',
                        'features' => "✓ Harga Spesial Kontrak Proyek\n✓ Suplai bertahap sesuai progres (JIT)\n✓ Pengiriman Truk CDD / Tronton",
                    ],
                ],
            ],
            'show_navbar' => false,
            'section_order' => [
                'hero',
                'cost_comparison',
                'quality_education',
                'catalog',
                'delivery_proof',
                'received_proof',
                'gallery',
                'calculator',
                'shipping_coverage',
                'tiering',
                'faq',
                'cta_bottom',
            ],
            'faqs' => [
                'items' => [
                    [
                        'q' => 'Bagaimana sistem pengiriman dan promo gratis ongkirnya?',
                        'a' => 'Pengiriman dilakukan langsung dari armada pabrik IndoRoster (pick-up, truk engkel, atau colt diesel) ke seluruh wilayah Jabodetabek, Jawa Barat, dan Banten. Untuk kuantitas tertentu, ongkir 100% GRATIS sampai depan gerbang lokasi Anda.',
                    ],
                    [
                        'q' => 'Berapa minimal order roster untuk dapat harga pabrik?',
                        'a' => 'Minimal pemesanan promo harga pabrik mulai dari 100 pcs (bisa campur beberapa motif standar 20x20 cm). Untuk kebutuhan di bawah 100 pcs silakan konsultasikan langsung dengan sales kami melalui WhatsApp.',
                    ],
                    [
                        'q' => 'Bagaimana jika ada keping yang pecah atau sompal saat perjalanan?',
                        'a' => 'Kami memberikan Garansi 100% Pecah Ganti Baru di Tempat. Supir armada pabrik kami akan langsung mengganti keping yang rusak saat proses penurunan barang di lokasi Anda tanpa biaya tambahan.',
                    ],
                    [
                        'q' => 'Berapa lama estimasi barang sampai setelah pemesanan?',
                        'a' => 'Untuk area Jabodetabek dan Jawa Barat, pengiriman biasanya tiba dalam 1-2 hari kerja (atau Same Day jika jadwal armada sejalur tersedia).',
                    ],
                ],
            ],
            'cta_bottom' => [
                'title' => 'Siap Wujudkan Dinding & Fasad Mewah Hemat Biaya?',
                'subtitle' => 'Dapatkan penawaran harga tangan pertama IndoRoster — Pusat Roster Beton Minimalis + promo gratis ongkir sekarang juga.',
                'button_text' => '💬 Chat Sales Pabrik & Ambil Promo Gratis Ongkir',
            ],
        ];

        $pages = [
            [
                'title' => 'Promo Pabrik Roster Beton Minimalis',
                'slug' => 'roster-pabrik',
                'default_city' => 'Jabodetabek & Jawa Barat',
                'meta_title' => 'IndoRoster — Pusat Roster Beton Minimalis — Penawaran Khusus',
                'meta_description' => 'IndoRoster — Pusat Roster Beton Minimalis kualitas padat dan presisi. Melayani pesanan mulai 100 pcs hingga proyek besar.',
            ],
            [
                'title' => 'Promo Utama Roster Beton Pabrik',
                'slug' => 'promo',
                'default_city' => 'Jabodetabek & Jawa Barat',
                'meta_title' => 'IndoRoster — Promo Roster Beton Minimalis Tangan Pertama',
                'meta_description' => 'Dapatkan harga tangan pertama pabrik langsung mulai Rp 12.500/pcs + promo gratis ongkir armada pabrik.',
            ],
            [
                'title' => 'Penawaran Proyek Roster Beton Minimalis',
                'slug' => 'penawaran-proyek',
                'default_city' => 'Jabodetabek & Jawa Barat',
                'meta_title' => 'Penawaran Khusus Proyek & Kontraktor — IndoRoster',
                'meta_description' => 'Suplai volume besar roster beton minimalis langsung dari pabrik IndoRoster dengan garansi 100% aman.',
            ],
            [
                'title' => 'Promo Roster Minimalis Presisi',
                'slug' => 'roster-minimalis',
                'default_city' => 'Jabodetabek & Jawa Barat',
                'meta_title' => 'Roster Beton Minimalis Presisi — Promo Pabrik IndoRoster',
                'meta_description' => 'Pusat roster beton minimalis modern kualitas padat dan presisi siku 90 derajat abu batu murni.',
            ],
        ];

        foreach ($pages as $p) {
            PromoPage::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'title' => $p['title'],
                    'default_city' => $p['default_city'],
                    'meta_title' => $p['meta_title'],
                    'meta_description' => $p['meta_description'],
                    'robots' => 'noindex, follow',
                    'sections' => $defaultSections,
                    'is_active' => true,
                    'sort_order' => 0,
                ]
            );
        }
    }
}
