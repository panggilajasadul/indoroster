<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan alt_text ke gallery_media.
     * Field ini penting untuk Google Images SEO dari halaman galeri.
     */
    public function up(): void
    {
        Schema::table('gallery_media', function (Blueprint $table) {
            $table->string('alt_text', 255)->nullable()->after('caption')
                ->comment('Alt text untuk gambar galeri, digunakan di Google Images SEO');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gallery_media', function (Blueprint $table) {
            $table->dropColumn('alt_text');
        });
    }
};
