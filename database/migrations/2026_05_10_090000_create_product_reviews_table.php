<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('reviewer_name');
            $table->string('reviewer_location', 100)->nullable();
            $table->tinyInteger('rating')->default(5);
            $table->text('content');
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_seeded')->default(false); // flag untuk data dummy
            $table->timestamps();

            $table->index(['product_id', 'is_approved', 'created_at']);
        });

        // Tambah kolom total_sold di products
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('total_sold')->default(0)->after('stock');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('total_sold');
        });
    }
};
