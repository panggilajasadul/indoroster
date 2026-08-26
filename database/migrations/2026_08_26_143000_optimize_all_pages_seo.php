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
        $pageSeos = [
            'home' => [
                'title' => 'Home',
                'meta_title' => 'Pabrik Roster Beton Minimalis Plered Purwakarta | IndoRoster Jabodetabek & Indonesia',
                'meta_description' => 'Pusat produksi tangan pertama aneka motif roster beton minimalis modern, bata tempel dinding, dan loster 20x20x10 cm cetak padat presisi. Siap kirim armada pabrik ke Jakarta, Bogor, Depok, Tangerang, Bekasi, Bandung, Jawa Barat, dan ekspedisi kargo nasional.',
            ],
            'katalog' => [
                'title' => 'Katalog Produk',
                'meta_title' => 'Katalog Roster Beton Minimalis & Bata Tempel | IndoRoster Pabrik Plered',
                'meta_description' => 'Temukan 50+ motif roster beton minimalis untuk pagar, fasad, partisi, dan ventilasi rumah. Kualitas cetak padat presisi langsung dari pabrik tangan pertama Plered Purwakarta. Pengiriman cepat Jabodetabek, Jawa Barat & Ekspedisi Nasional.',
            ],
            'tentang-kami' => [
                'title' => 'Tentang Kami',
                'meta_title' => 'Tentang Kami — Pabrik Roster Beton IndoRoster Plered Purwakarta',
                'meta_description' => 'Kenali IndoRoster lebih dekat sebagai sentra produksi tangan pertama roster beton arsitektural di Plered, Purwakarta. Berpengalaman melayani suplai ribuan pieces roster pagar, fasad, dan dinding ke Jabodetabek, Jawa Barat, dan seluruh Indonesia.',
            ],
            'kontak' => [
                'title' => 'Kontak Kami',
                'meta_title' => 'Kontak Pabrik & Sales IndoRoster | Konsultasi Harga & Pengiriman',
                'meta_description' => 'Hubungi tim sales pabrik IndoRoster via WhatsApp untuk konsultasi motif roster, perhitungan kebutuhan dinding, diskon proyek, dan jadwal pengiriman armada truk ke Jabodetabek, Jawa Barat, maupun kargo nasional.',
            ],
            'gallery' => [
                'title' => 'Galeri Proyek',
                'meta_title' => 'Galeri Proyek Roster Beton Minimalis & Fasad Rumah | IndoRoster',
                'meta_description' => 'Inspirasi foto proyek nyata pemasangan roster beton minimalis, pagar modern, dinding ventilasi, dan partisi interior estetis dari pelanggan dan arsitek di seluruh Indonesia.',
            ],
            'indoroster-video' => [
                'title' => 'Video Inspirasi',
                'meta_title' => 'Video Inspirasi Pasang Roster Beton & Review Proyek | IndoRoster',
                'meta_description' => 'Tonton video dokumentasi proyek pemasangan roster beton minimalis, tutorial aplikasi dinding, dan ulasan langsung dari pembeli di seluruh Indonesia.',
            ],
            'proses-produksi' => [
                'title' => 'Proses Produksi',
                'meta_title' => 'Proses Produksi Roster Beton Presisi | Pabrik IndoRoster Plered',
                'meta_description' => 'Melihat langsung proses pembuatan roster beton berkualitas tinggi di pabrik Plered Purwakarta. Cetak padat tumbuk pengrajin ahli, presisi siku, dan quality control ketat sebelum pengiriman armada.',
            ],
            'syarat-dan-ketentuan' => [
                'title' => 'Syarat & Ketentuan',
                'meta_title' => 'Syarat & Ketentuan Pemesanan & Pengiriman | Pabrik IndoRoster',
                'meta_description' => 'Informasi resmi mengenai syarat pemesanan, tata cara pembayaran Midtrans, pengiriman armada pabrik, dan garansi ganti baru 100% jika ada keping roster yang pecah di jalan.',
            ],
            'kebijakan-privasi' => [
                'title' => 'Kebijakan Privasi',
                'meta_title' => 'Kebijakan Privasi & Keamanan Transaksi | IndoRoster',
                'meta_description' => 'Kebijakan perlindungan data pelanggan dan standar enkripsi transaksi pembayaran resmi di platform IndoRoster Indonesia.',
            ],
        ];

        foreach ($pageSeos as $slug => $data) {
            $existing = Page::where('slug', $slug)->first();
            if ($existing) {
                $existing->update([
                    'title' => $data['title'],
                    'meta_title' => $data['meta_title'],
                    'meta_description' => $data['meta_description'],
                    'is_active' => true,
                ]);
            } else {
                Page::create([
                    'slug' => $slug,
                    'title' => $data['title'],
                    'meta_title' => $data['meta_title'],
                    'meta_description' => $data['meta_description'],
                    'content' => [],
                    'is_active' => true,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reversal needed
    }
};
