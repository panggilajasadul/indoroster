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
        Page::where('slug', 'katalog')->update([
            'title' => 'Katalog Produk',
            'meta_title' => 'Katalog Roster Beton & Bata Expose — Pabrik & Produsen Terpercaya',
            'meta_description' => 'Pusat katalog roster beton minimalis, bata expose, dan ornamen dinding langsung dari pabrik tangan pertama IndoRoster Plered Purwakarta. Hasil cetak tumbuk padat pengrajin ahli, keras, kokoh, dan rapi dengan harga grosir pabrik.',
            'content' => [], // Kosongkan blok default agar bersih dan tidak membingungkan admin
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
