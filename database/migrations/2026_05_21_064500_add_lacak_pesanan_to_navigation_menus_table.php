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
        DB::table('navigation_menus')->insert([
            'label' => 'Lacak Pesanan',
            'url' => '/lacak-pesanan',
            'order' => 8,
            'is_active' => true,
            'target' => '_self',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('navigation_menus')
            ->where('url', '/lacak-pesanan')
            ->delete();
    }
};
