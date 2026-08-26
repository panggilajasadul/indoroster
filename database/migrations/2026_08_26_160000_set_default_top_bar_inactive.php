<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('site_settings')->updateOrInsert(
            ['key' => 'top_bar_is_active'],
            [
                'group' => 'theme',
                'value' => '0',
                'type' => 'boolean',
                'description' => 'Tampilkan/Sembunyikan top bar pengumuman paling atas (1 = Ya, 0 = Tidak)',
                'updated_at' => now(),
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('site_settings')->where('key', 'top_bar_is_active')->delete();
    }
};
