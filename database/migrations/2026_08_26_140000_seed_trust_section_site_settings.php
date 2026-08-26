<?php

use App\Models\SiteSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
            [
                'group' => 'general',
                'key' => 'trust_section_badge',
                'value' => 'Pusat Pabrik Roster Beton Plered Purwakarta',
                'type' => 'text',
                'description' => 'Badge teks di atas judul deskripsi belanja',
            ],
            [
                'group' => 'general',
                'key' => 'trust_section_title',
                'value' => 'Nikmati Kemudahan & Keamanan Belanja Roster Tangan Pertama di IndoRoster',
                'type' => 'text',
                'description' => 'Judul section edukasi & kepercayaan belanja pabrik',
            ],
            [
                'group' => 'general',
                'key' => 'trust_section_description',
                'value' => 'Selamat datang di IndoRoster, sentra produksi dan distribusi aneka motif roster beton minimalis, bata tempel dinding, dan loster modern 20x20x10 cm tangan pertama dari Plered, Purwakarta. Kami melayani pemesanan proyek skala kecil maupun ribuan pieces tanpa perantara dengan harga pabrik yang transparan. Nikmati pengiriman cepat armada sendiri untuk kawasan Jabodetabek & Jawa Barat serta kargo khusus material aman ke seluruh Indonesia dengan garansi ganti baru 100% jika pecah.',
                'type' => 'textarea',
                'description' => 'Paragraf deskripsi kemudahan belanja langsung pabrik',
            ],
            [
                'group' => 'payment',
                'key' => 'trust_section_payments',
                'value' => 'BCA, Mandiri, BNI, BRI, BSI, CIMB, Permata, QRIS, GoPay, ShopeePay, DANA, OVO',
                'type' => 'textarea',
                'description' => 'Daftar bank & e-wallet resmi yang ditampilkan di footer katalog/produk (pisahkan dengan koma)',
            ],
            [
                'group' => 'shipping',
                'key' => 'trust_section_shippings',
                'value' => 'Armada Truk IndoRoster, Ekspedisi Kargo Material, JNE Trucking, Dakota Cargo, Indah Logistik, SiCepat, Pos Indonesia',
                'type' => 'textarea',
                'description' => 'Daftar ekspedisi & armada yang ditampilkan di footer katalog/produk (pisahkan dengan koma)',
            ],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        SiteSetting::whereIn('key', [
            'trust_section_badge',
            'trust_section_title',
            'trust_section_description',
            'trust_section_payments',
            'trust_section_shippings',
        ])->delete();
    }
};
