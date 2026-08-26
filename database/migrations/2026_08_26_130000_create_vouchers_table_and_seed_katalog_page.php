<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('vouchers')) {
            Schema::create('vouchers', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name');
                $table->string('badge_text')->nullable();
                $table->enum('type', ['free_shipping', 'fixed_discount', 'percent_discount'])->default('free_shipping');
                $table->decimal('discount_amount', 12, 2)->default(0);
                $table->decimal('min_order_amount', 12, 2)->default(0);
                $table->integer('min_order_qty')->default(0);
                $table->json('allowed_regions')->nullable();
                $table->text('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->dateTime('valid_from')->nullable();
                $table->dateTime('valid_until')->nullable();
                $table->timestamps();
            });
        }

        // Seed initial Regional Vouchers
        DB::table('vouchers')->updateOrInsert(
            ['code' => 'ONGKIRJABODETABEK'],
            [
                'name' => 'Gratis Ongkir Armada Pabrik Jabodetabek',
                'badge_text' => 'Khusus Jabodetabek',
                'type' => 'free_shipping',
                'discount_amount' => 0,
                'min_order_amount' => 500000,
                'min_order_qty' => 100,
                'allowed_regions' => json_encode(['DKI Jakarta', 'Jakarta', 'Bogor', 'Depok', 'Tangerang', 'Tangerang Selatan', 'Bekasi', 'Jabodetabek']),
                'description' => 'Gratis pengiriman langsung dengan armada truk pabrik IndoRoster untuk area Jabodetabek minimal pemesanan 100 pcs.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('vouchers')->updateOrInsert(
            ['code' => 'PROMOJAWABARAT'],
            [
                'name' => 'Potongan Ongkir Armada Jawa Barat',
                'badge_text' => 'Khusus Jawa Barat',
                'type' => 'fixed_discount',
                'discount_amount' => 150000,
                'min_order_amount' => 1000000,
                'min_order_qty' => 150,
                'allowed_regions' => json_encode(['Purwakarta', 'Karawang', 'Bandung', 'Cimahi', 'Subang', 'Cirebon', 'Indramayu', 'Sukabumi', 'Cianjur', 'Jawa Barat']),
                'description' => 'Subsidi potongan ongkir Rp150.000 untuk pengiriman area Jawa Barat dengan armada pabrik.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('vouchers')->updateOrInsert(
            ['code' => 'CARGONASIONAL'],
            [
                'name' => 'Cashback Ongkir Kargo Luar Pulau',
                'badge_text' => 'Ekspedisi Nasional',
                'type' => 'percent_discount',
                'discount_amount' => 10,
                'min_order_amount' => 2000000,
                'min_order_qty' => 200,
                'allowed_regions' => json_encode(['Jawa Tengah', 'Jawa Timur', 'Bali', 'Sumatera', 'Kalimantan', 'Sulawesi', 'Nasional']),
                'description' => 'Diskon 10% total pesanan untuk pengiriman kargo khusus material ke luar Jawa / seluruh Indonesia.',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Seed Record Halaman 'katalog' ke tabel pages
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
        ];

        DB::table('pages')->updateOrInsert(
            ['slug' => 'katalog'],
            [
                'title' => 'Katalog Produk',
                'meta_title' => 'Katalog Roster Beton Minimalis Lengkap | IndoRoster Pabrik Plered',
                'meta_description' => 'Temukan 50+ motif roster beton minimalis modern presisi langsung dari pabrik tangan pertama Plered Purwakarta. Pengiriman cepat armada Jabodetabek, Jawa Barat, dan ekspedisi kargo nasional.',
                'content' => json_encode($katalogBlocks),
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
        Schema::dropIfExists('vouchers');
        DB::table('pages')->where('slug', 'katalog')->delete();
    }
};
