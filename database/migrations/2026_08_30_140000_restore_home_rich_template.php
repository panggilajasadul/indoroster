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
        Page::where('slug', 'home')->update([
            'content' => [],
            'meta_title' => 'Pabrik Roster Beton Minimalis Jabodetabek | IndoRoster',
            'meta_description' => 'Pabrik roster beton minimalis & bata expose cetak padat presisi. Melayani kirim Jabodetabek, Bandung & nasional dengan garansi aman bebas pecah.',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
