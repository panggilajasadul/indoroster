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
            if (! Schema::hasColumn('orders', 'order_source')) {
                $table->string('order_source', 30)->default('web')->after('order_number');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->change();

            if (! Schema::hasColumn('order_items', 'is_custom_item')) {
                $table->boolean('is_custom_item')->default(false)->after('product_variant_id');
            }
            if (! Schema::hasColumn('order_items', 'custom_variant_name')) {
                $table->string('custom_variant_name')->nullable()->after('product_name');
            }
            if (! Schema::hasColumn('order_items', 'item_notes')) {
                $table->text('item_notes')->nullable()->after('subtotal');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'order_source')) {
                $table->dropColumn('order_source');
            }
        });

        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'is_custom_item')) {
                $table->dropColumn('is_custom_item');
            }
            if (Schema::hasColumn('order_items', 'custom_variant_name')) {
                $table->dropColumn('custom_variant_name');
            }
            if (Schema::hasColumn('order_items', 'item_notes')) {
                $table->dropColumn('item_notes');
            }
        });
    }
};
