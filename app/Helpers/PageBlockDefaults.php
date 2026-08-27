<?php

namespace App\Helpers;

class PageBlockDefaults
{
    /**
     * Get default values for a given block type.
     */
    public static function get(string $blockType): array
    {
        $defaults = [
            'shipping_info' => [
                'badge' => 'Armada Pengiriman Mandiri',
                'title' => 'Jangkauan Pengiriman Seluruh Jabodetabek & Indonesia',
                'content' => 'Pengiriman langsung dari pabrik dengan packing aman bersegel dan garansi ganti baru 100% jika terjadi kerusakan dalam perjalanan.',
                'button_text' => 'Konsultasi Ongkos Kirim',
                'button_url' => '',
                'bg_theme' => 'white',
            ],
            'why_us' => [
                'title' => 'Kenapa Memilih Roster Pabrik Kami?',
                'description' => 'Kami mengedepankan kualitas cetakan, kecepatan pengiriman armada mandiri, dan transparansi harga pabrik tangan pertama.',
                'bg_theme' => 'dark',
                'items' => [
                    ['title' => 'Pabrik Tangan Pertama', 'content' => 'Harga langsung dari produsen tanpa perantara agen atau toko material retail.'],
                    ['title' => 'Garansi Pecah Ganti Baru', 'content' => 'Setiap keping roster yang rusak atau pecah saat proses pengiriman armada kami ganti 100% tanpa biaya tambahan.'],
                    ['title' => 'Armada Truk Khusus', 'content' => 'Pengiriman tepat waktu terjadwal menggunakan truk boks dan armada khusus material.'],
                    ['title' => 'Motif Terlengkap', 'content' => 'Lebih dari 150+ variasi motif modern minimalis, klasik, bunga, dan geometris.'],
                ],
            ],
            'strength_test' => [
                'title' => 'Uji Kekuatan & Ketahanan Beban Nyata',
                'description' => 'Roster beton kami diproduksi menggunakan formula pasir silika dan semen mutu tinggi dengan sistem cetak getar padat. Hasilnya adalah struktur yang sangat padat, minim pori, dan sanggup menahan beban struktural maupun cuaca ekstrem.',
                'bg_theme' => 'white',
                'features' => [
                    ['title' => 'Beban Tekan Maksimal', 'desc' => 'Struktur sangat padat dan tidak mudah retak rambut saat dipasang pada bentang dinding tinggi.'],
                    ['title' => 'Tahan Cuaca Tropis', 'desc' => 'Tahan paparan panas terik dan hujan tanpa risiko lumut berlebih atau kerapuhan semen.'],
                    ['title' => 'Presisi Sudut 90°', 'desc' => 'Siku dan ketebalan seragam memudahkan tukang memasang dengan nat rapi dan sejajar.'],
                ],
            ],
            'social_review' => [
                'badge' => 'Ulasan Nyata Pelanggan',
                'title' => 'Dipercaya Ribuan Pemilik Rumah, Kontraktor & Arsitek',
                'description' => 'Tangkapan layar chat transaksi dan kepuasan pelanggan saat barang diterima di lokasi proyek.',
                'creators_count' => '2.500+ Proyek',
                'bg_theme' => 'dark',
            ],
            'gallery_grid' => [
                'badge' => 'Inspirasi Visual',
                'title' => 'Galeri Aplikasi Roster pada Bangunan Nyata',
                'description' => 'Lihat bagaimana produk roster kami mengubah fasad rumah, pagar, dan sekat ruang menjadi karya arsitektural yang estetik.',
                'bg_theme' => 'dark',
            ],
            'stats_counter' => [
                'badge' => 'PORTFOLIO & KAPASITAS',
                'title' => 'Angka Nyata Dedikasi Kami',
                'description' => 'Pengalaman bertahun-tahun melayani ribuan proyek rumah tinggal, ruko, perumahan, dan gedung perkantoran.',
                'stats' => [
                    ['value' => '5000+', 'label' => 'Proyek Selesai', 'description' => 'Dipercaya kontraktor & arsitek'],
                    ['value' => '10+', 'label' => 'Tahun Pengalaman', 'description' => 'Spesialis roster arsitektur'],
                    ['value' => '150+', 'label' => 'Motif Tersedia', 'description' => 'Koleksi desain terlengkap'],
                    ['value' => '100%', 'label' => 'Garansi Pabrik', 'description' => 'Pecah dalam perjalanan diganti'],
                ],
            ],
            'ugc_videos' => [
                'badge' => 'VISUAL EXPERIENCE',
                'title' => 'Lihat Detailnya Lebih Dekat',
                'description' => 'Kami percaya bahwa melihat adalah percaya. Koleksi video kami menunjukkan bagaimana cahaya dan udara mengalir melalui setiap celah roster kami.',
                'button_text' => 'Video Inspirasi Lengkap',
                'button_url' => '/galeri',
                'bg_theme' => 'white',
            ],
            'trust_payment_shipping' => [
                'badge' => 'Pusat Pabrik Roster Beton Plered Purwakarta',
                'title' => 'Nikmati Kemudahan & Keamanan Belanja Roster Tangan Pertama di IndoRoster',
                'description' => 'Pesan langsung dari pabrik dengan proses verifikasi cepat, metode pembayaran aman dan armada pengiriman mandiri.',
            ],
            'cta' => [
                'badge' => 'KONSULTASI GRATIS',
                'title' => 'Wujudkan Rumah Impian dengan Sentuhan Roster Modern',
                'subtitle' => 'Hubungi tim ahli kami untuk konsultasi motif, hitung estimasi kebutuhan keping, dan cek promo armada pabrik hari ini.',
                'button_text' => 'Hubungi Kami via WhatsApp',
                'button_url' => 'https://wa.me/6281389709847',
            ],
        ];

        return $defaults[$blockType] ?? [];
    }

    /**
     * Hydrate a block's data array with default values for missing or empty fields.
     */
    public static function hydrateBlock(array $block): array
    {
        $type = $block['type'] ?? '';
        $data = $block['data'] ?? [];

        if (empty($type)) {
            return $block;
        }

        $defaults = self::get($type);
        foreach ($defaults as $key => $defaultValue) {
            if (! isset($data[$key]) || $data[$key] === '' || $data[$key] === null || (is_array($defaultValue) && empty($data[$key]))) {
                $data[$key] = $defaultValue;
            }
        }

        $block['data'] = $data;

        return $block;
    }

    /**
     * Hydrate entire content array of blocks.
     */
    public static function hydrateBlocks(array $blocks): array
    {
        return array_map([self::class, 'hydrateBlock'], $blocks);
    }
}
