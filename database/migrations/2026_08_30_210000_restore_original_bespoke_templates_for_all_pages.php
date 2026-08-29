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
        // Mengembalikan seluruh halaman ke mode template desain asli (rich bespoke blade)
        // dengan mengosongkan generic blocks
        $slugs = [
            'tentang-kami',
            'kontak',
            'proses-produksi',
            'syarat-dan-ketentuan',
            'kebijakan-privasi',
            'home',
            'katalog',
            'gallery',
            'indoroster-video',
            'video-inspirasi',
            'untuk-arsitek',
            'untuk-kontraktor',
            'untuk-developer',
            'supplier-roster-beton',
            'roster-beton-proyek',
            'kalkulator-roster',
            'lokasi',
        ];

        foreach ($slugs as $slug) {
            Page::where('slug', $slug)->update(['content' => []]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
