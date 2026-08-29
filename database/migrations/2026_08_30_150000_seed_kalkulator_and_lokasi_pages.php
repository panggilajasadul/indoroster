<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Page::firstOrCreate(
            ['slug' => 'kalkulator-roster'],
            [
                'title' => 'Kalkulator Kebutuhan Roster Beton Dinding',
                'content' => [],
                'meta_title' => 'Kalkulator Kebutuhan Roster Beton Dinding | Hitung Akurat — IndoRoster',
                'meta_description' => 'Hitung estimasi kebutuhan jumlah keping roster beton per meter persegi (m2) secara akurat untuk dinding fasad, pagar, dan sekat partisi. Dilengkapi perhitungan safety waste.',
                'is_active' => true,
            ]
        );

        Page::firstOrCreate(
            ['slug' => 'lokasi'],
            [
                'title' => 'Area Layanan Pengiriman Roster Beton Seluruh Indonesia',
                'content' => [],
                'meta_title' => 'Area Layanan Pengiriman Roster Beton Seluruh Indonesia | IndoRoster',
                'meta_description' => 'Daftar kota dan wilayah jangkauan pengiriman langsung armada truk pabrik IndoRoster: Jabodetabek, Bandung, Karawang, Cianjur, Cirebon, dan ekspedisi nasional se-Indonesia.',
                'is_active' => true,
            ]
        );
    }

    public function down(): void
    {
        // No-op
    }
};
