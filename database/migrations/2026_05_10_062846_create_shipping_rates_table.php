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
        Schema::create('shipping_rates', function (Blueprint $table) {
            $table->id();
            $table->char('city_code', 4)->unique();
            $table->decimal('shipping_cost', 12, 2)->default(180000);
            $table->decimal('min_order_amount', 12, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('city_code')
                ->references('code')
                ->on('indonesia_cities') // Laravolt uses indonesia_ prefix by default if config says so, but looking at migration above it uses config('laravolt.indonesia.table_prefix'). Let's check config.
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_rates');
    }
};
