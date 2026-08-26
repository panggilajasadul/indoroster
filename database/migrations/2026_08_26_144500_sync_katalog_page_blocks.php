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
                'type' => 'hero',
                'data' => [
                    'slider_duration' => 5000,
                    'banners' => [
                        [
                            'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1920&q=80',
                            'top_text' => 'SENTRA PRODUKSI PLERED PURWAKARTA',
                            'badge' => 'HARGA PABRIK TANGAN PERTAMA',
                            'title' => 'Katalog Lengkap Roster Beton Minimalis',
                            'subtitle' => 'Pusat produksi 50+ motif roster beton minimalis, bata tempel dinding, dan loster modern 20x20x10 cm cetak padat presisi siap kirim se-Jabodetabek & Indonesia.',
                            'button_text' => 'Konsultasi Kebutuhan Dinding',
                            'button_url' => 'https://wa.me/6281389709847',
                            'button_2_text' => 'Lihat Galeri Terpasang',
                            'button_2_url' => '/gallery',
                            'alignment' => 'center',
                            'overlay_color' => '#020617',
                            'overlay_opacity' => '75',
                            'image_opacity' => '40',
                            'blur_level' => 'none',
                            'image_fit' => 'object-cover',
                        ],
                    ],
                ],
            ],
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
                'type' => 'viral_products',
                'data' => [
                    'title' => 'Motif Roster Terpopuler & Viral 🔥',
                    'subtitle' => 'Pilihan roster beton terfavorit arsitek dan kontraktor dengan ulasan terbanyak',
                    'limit' => 6,
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
        ];

        Page::where('slug', 'katalog')->update([
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
