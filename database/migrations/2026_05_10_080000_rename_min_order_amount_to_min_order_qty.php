<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipping_rates', function (Blueprint $table) {
            $table->dropColumn('min_order_amount');
        });

        Schema::table('shipping_rates', function (Blueprint $table) {
            $table->unsignedInteger('min_order_qty')->default(0)->after('shipping_cost');
        });
    }

    public function down(): void
    {
        Schema::table('shipping_rates', function (Blueprint $table) {
            $table->dropColumn('min_order_qty');
        });

        Schema::table('shipping_rates', function (Blueprint $table) {
            $table->decimal('min_order_amount', 12, 2)->default(0)->after('shipping_cost');
        });
    }
};
