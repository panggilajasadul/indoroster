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
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'shipping_village')) {
                $table->string('shipping_village', 100)->nullable()->after('shipping_district');
            }
        });

        Schema::table('addresses', function (Blueprint $table) {
            if (! Schema::hasColumn('addresses', 'village')) {
                $table->string('village', 100)->nullable()->after('district');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('shipping_village');
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('village');
        });
    }
};
