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
        // Mengosongkan blocks page builder untuk beranda (home)
        // agar secara otomatis menggunakan 16 section lengkap bawaan home.blade.php
        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Beranda Utama (Home)',
                'content' => [],
                'is_active' => true,
                'meta_title' => 'Pabrik Roster Beton Minimalis | Supplier Proyek Kirim Jabodetabek, Bandung & Seluruh Indonesia — IndoRoster',
                'meta_description' => 'Produsen resmi roster beton minimalis, loster arsitektural, dan bata expose kualitas cetak padat presisi harga pabrik. Melayani pengiriman partai kecil & proyek ribuan pcs ke Jakarta, Bogor, Depok, Tangerang, Bekasi, Bandung, Karawang, Cianjur, Cirebon & seluruh Indonesia.',
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
