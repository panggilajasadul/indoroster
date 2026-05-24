<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('navigation_menus', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('url');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('target')->default('_self');
            $table->timestamps();
        });

        // Seed with default navigation items
        DB::table('navigation_menus')->insert([
            [
                'label' => 'Beranda',
                'url' => '/',
                'order' => 1,
                'is_active' => true,
                'target' => '_self',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'Produk',
                'url' => '/katalog',
                'order' => 2,
                'is_active' => true,
                'target' => '_self',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'Galeri',
                'url' => '/gallery',
                'order' => 3,
                'is_active' => true,
                'target' => '_self',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'Inspirasi',
                'url' => '/video-inspirasi',
                'order' => 4,
                'is_active' => true,
                'target' => '_self',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'Produksi',
                'url' => '/proses-produksi',
                'order' => 5,
                'is_active' => true,
                'target' => '_self',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'Tentang Kami',
                'url' => '/tentang-kami',
                'order' => 6,
                'is_active' => true,
                'target' => '_self',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'label' => 'Kontak',
                'url' => '/kontak',
                'order' => 7,
                'is_active' => true,
                'target' => '_self',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('navigation_menus');
    }
};
