<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('full_address');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('shipping_latitude', 10, 8)->nullable()->after('shipping_postal_code');
            $table->decimal('shipping_longitude', 11, 8)->nullable()->after('shipping_latitude');
        });

        Schema::table('shipping_labels', function (Blueprint $table) {
            $table->decimal('recipient_latitude', 10, 8)->nullable()->after('recipient_postal_code');
            $table->decimal('recipient_longitude', 11, 8)->nullable()->after('recipient_latitude');
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_latitude', 'shipping_longitude']);
        });

        Schema::table('shipping_labels', function (Blueprint $table) {
            $table->dropColumn(['recipient_latitude', 'recipient_longitude']);
        });
    }
};
