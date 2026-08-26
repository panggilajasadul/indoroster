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
            if (! Schema::hasColumn('orders', 'fulfillment_type')) {
                $table->string('fulfillment_type')->nullable()->after('payment_status');
            }
            if (! Schema::hasColumn('orders', 'requested_batch_delivery')) {
                $table->boolean('requested_batch_delivery')->default(false)->after('fulfillment_type');
            }
            if (! Schema::hasColumn('orders', 'requested_batch_notes')) {
                $table->text('requested_batch_notes')->nullable()->after('requested_batch_delivery');
            }
            if (! Schema::hasColumn('orders', 'production_start_date')) {
                $table->date('production_start_date')->nullable()->after('requested_batch_notes');
            }
            if (! Schema::hasColumn('orders', 'ready_shipping_date')) {
                $table->date('ready_shipping_date')->nullable()->after('production_start_date');
            }
            if (! Schema::hasColumn('orders', 'estimated_delivery_date')) {
                $table->date('estimated_delivery_date')->nullable()->after('ready_shipping_date');
            }
            if (! Schema::hasColumn('orders', 'batch_count')) {
                $table->unsignedInteger('batch_count')->default(1)->after('estimated_delivery_date');
            }
            if (! Schema::hasColumn('orders', 'fulfillment_notes')) {
                $table->text('fulfillment_notes')->nullable()->after('batch_count');
            }
        });

        if (! Schema::hasTable('order_batches')) {
            Schema::create('order_batches', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->unsignedInteger('batch_number')->default(1);
                $table->string('batch_name')->default('Batch #1');
                $table->unsignedInteger('quantity')->default(0);
                $table->date('production_start_date')->nullable();
                $table->date('estimated_dispatch_date')->nullable();
                $table->date('estimated_delivery_date')->nullable();
                $table->date('actual_dispatch_date')->nullable();
                $table->date('actual_delivered_date')->nullable();
                $table->string('status')->default('pending_production'); // pending_production, producing, ready_to_ship, shipped, delivered
                $table->foreignId('courier_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('courier_name')->nullable();
                $table->string('courier_phone')->nullable();
                $table->string('tracking_number')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_batches');

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'fulfillment_type',
                'requested_batch_delivery',
                'requested_batch_notes',
                'production_start_date',
                'ready_shipping_date',
                'estimated_delivery_date',
                'batch_count',
                'fulfillment_notes',
            ]);
        });
    }
};
