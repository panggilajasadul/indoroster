<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->unsignedInteger('views_count')->default(0)->after('is_active');
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->unsignedInteger('views_count')->default(0)->after('is_seeded');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn('views_count');
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropColumn('views_count');
        });
    }
};
