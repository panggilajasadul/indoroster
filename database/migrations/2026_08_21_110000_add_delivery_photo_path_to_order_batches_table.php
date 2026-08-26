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
        if (! Schema::hasColumn('order_batches', 'delivery_photo_path')) {
            Schema::table('order_batches', function (Blueprint $table) {
                $table->string('delivery_photo_path')->nullable()->after('notes');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('order_batches', 'delivery_photo_path')) {
            Schema::table('order_batches', function (Blueprint $table) {
                $table->dropColumn('delivery_photo_path');
            });
        }
    }
};
