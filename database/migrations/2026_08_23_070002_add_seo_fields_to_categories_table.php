<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan meta_title dan meta_description ke categories.
     * Digunakan oleh ProductCatalog dan clean category URLs /katalog/{slug}.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('meta_title', 255)->nullable()->after('description')
                ->comment('Meta title untuk halaman katalog kategori ini');
            $table->string('meta_description', 500)->nullable()->after('meta_title')
                ->comment('Meta description untuk halaman katalog kategori ini');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });
    }
};
