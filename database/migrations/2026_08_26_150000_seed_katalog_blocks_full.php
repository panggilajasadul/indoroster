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
        $katalogBlocks = [
            [
                'type' => 'voucher_showcase',
                'data' => [
                    'badge' => 'PROMO & VOUCHER WILAYAH',
                    'title' => 'Klaim Promo Armada Pabrik Sesuai Lokasi Proyek Anda',
                    'description' => 'Gunakan kode voucher pengiriman armada pabrik saat checkout atau sebutkan saat konsultasi dengan tim Admin WhatsApp.',
                    'button_text' => 'Konsultasi Admin Pabrik',
                    'button_url' => 'https://wa.me/6281389709847',
                    'bg_theme' => 'white',
                ],
            ],
            [
                'type' => 'why_us',
                'data' => [
                    'title' => 'Keunggulan Roster Cetak Pabrik IndoRoster',
                    'description' => 'Mengapa ratusan arsitek dan kontraktor di Jabodetabek & Jawa Barat memilih langsung dari pabrik kami di Plered, Purwakarta.',
                    'bg_theme' => 'dark',
                    'items' => [
                        [
                            'title' => 'Komposisi Padat & Presisi Siku',
                            'content' => 'Dicetak menggunakan racikan pasir silica dan semen berkualitas dengan pemadatan maksimal sehingga tidak mudah gupil.',
                        ],
                        [
                            'title' => 'Harga Murni Pabrik Tangan Pertama',
                            'content' => 'Tanpa perantara toko bangunan, Anda mendapatkan penawaran harga grosir paling transparan.',
                        ],
                        [
                            'title' => 'Garansi 100% Pecah Diganti Baru',
                            'content' => 'Setiap pesanan yang dikirim oleh armada truk kami dijamin aman dan diganti baru jika ada keping yang rusak di jalan.',
                        ],
                    ],
                ],
            ],
            [
                'type' => 'faq',
                'data' => [
                    'title' => 'Pertanyaan Seputar Pemesanan & Pengiriman',
                    'bg_theme' => 'white',
                    'items' => [
                        [
                            'question' => 'Berapa minimal pemesanan roster beton di IndoRoster?',
                            'answer' => 'Kami melayani pemesanan mulai dari puluhan pcs hingga ribuan pieces untuk proyek rumah tinggal, ruko, maupun gedung komersial.',
                        ],
                        [
                            'question' => 'Bagaimana jika ada roster yang pecah saat pengiriman?',
                            'answer' => 'Kami memberikan garansi 100% ganti baru tanpa biaya tambahan untuk setiap keping roster yang rusak selama pengiriman oleh armada pabrik kami.',
                        ],
                        [
                            'question' => 'Berapa lama estimasi pengiriman ke Jabodetabek & Jawa Barat?',
                            'answer' => 'Untuk produk ready stock, pengiriman armada pabrik rata-rata tiba dalam 1-3 hari kerja langsung ke lokasi proyek Anda.',
                        ],
                    ],
                ],
            ],
        ];

        Page::where('slug', 'katalog')->update([
            'title' => 'Katalog Produk',
            'meta_title' => 'Katalog Roster Beton & Bata Expose — Pabrik & Produsen Terpercaya',
            'meta_description' => 'Pusat katalog roster beton minimalis, bata expose, dan ornamen dinding langsung dari pabrik tangan pertama IndoRoster Plered Purwakarta. Hasil cetak tumbuk padat pengrajin ahli, keras, kokoh, dan rapi dengan harga grosir pabrik.',
            'content' => $katalogBlocks,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
