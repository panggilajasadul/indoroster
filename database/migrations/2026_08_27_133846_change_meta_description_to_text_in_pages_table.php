<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ubah kolom meta_description dari varchar(500) menjadi text
     * agar admin bisa menyimpan deskripsi SEO yang lebih panjang dari 500 karakter
     * tanpa mendapat error 500 dari Livewire/MySQL.
     */
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->text('meta_description')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('meta_description', 500)->nullable()->change();
        });
    }
};
