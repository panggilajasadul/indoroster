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
            if (! Schema::hasColumn('orders', 'factory_name')) {
                $table->string('factory_name')->nullable()->after('fulfillment_notes');
            }
            if (! Schema::hasColumn('orders', 'factory_pic_name')) {
                $table->string('factory_pic_name')->nullable()->after('factory_name');
            }
            if (! Schema::hasColumn('orders', 'factory_pic_phone')) {
                $table->string('factory_pic_phone')->nullable()->after('factory_pic_name');
            }
            if (! Schema::hasColumn('orders', 'factory_address')) {
                $table->text('factory_address')->nullable()->after('factory_pic_phone');
            }
            if (! Schema::hasColumn('orders', 'pickup_driver_name')) {
                $table->string('pickup_driver_name')->nullable()->after('factory_address');
            }
            if (! Schema::hasColumn('orders', 'pickup_driver_plate')) {
                $table->string('pickup_driver_plate')->nullable()->after('pickup_driver_name');
            }
        });

        Schema::table('order_batches', function (Blueprint $table) {
            if (! Schema::hasColumn('order_batches', 'source_type')) {
                $table->string('source_type')->default('internal')->nullable()->after('status');
            }
            if (! Schema::hasColumn('order_batches', 'factory_name')) {
                $table->string('factory_name')->nullable()->after('source_type');
            }
            if (! Schema::hasColumn('order_batches', 'factory_pic_name')) {
                $table->string('factory_pic_name')->nullable()->after('factory_name');
            }
            if (! Schema::hasColumn('order_batches', 'factory_pic_phone')) {
                $table->string('factory_pic_phone')->nullable()->after('factory_pic_name');
            }
            if (! Schema::hasColumn('order_batches', 'factory_address')) {
                $table->text('factory_address')->nullable()->after('factory_pic_phone');
            }
            if (! Schema::hasColumn('order_batches', 'pickup_driver_name')) {
                $table->string('pickup_driver_name')->nullable()->after('factory_address');
            }
            if (! Schema::hasColumn('order_batches', 'pickup_driver_plate')) {
                $table->string('pickup_driver_plate')->nullable()->after('pickup_driver_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'factory_name',
                'factory_pic_name',
                'factory_pic_phone',
                'factory_address',
                'pickup_driver_name',
                'pickup_driver_plate',
            ]);
        });

        Schema::table('order_batches', function (Blueprint $table) {
            $table->dropColumn([
                'source_type',
                'factory_name',
                'factory_pic_name',
                'factory_pic_phone',
                'factory_address',
                'pickup_driver_name',
                'pickup_driver_plate',
            ]);
        });
    }
};
