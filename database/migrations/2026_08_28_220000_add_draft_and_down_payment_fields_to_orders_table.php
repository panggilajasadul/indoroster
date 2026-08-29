<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Modifikasi ENUM status pada tabel orders untuk mendukung status 'draft'
        try {
            DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('draft','pending_payment','paid','processing','shipped','delivered','completed','cancelled') NOT NULL DEFAULT 'pending_payment'");
        } catch (Throwable $e) {
            // Fallback for SQLite in tests
        }

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'shipping_district')) {
                $table->string('shipping_district', 100)->nullable()->after('shipping_city');
            }
            if (! Schema::hasColumn('orders', 'payment_scheme')) {
                $table->string('payment_scheme', 50)->default('full')->after('discount_amount');
            }
            if (! Schema::hasColumn('orders', 'down_payment_amount')) {
                $table->decimal('down_payment_amount', 15, 2)->default(0)->after('payment_scheme');
            }
            if (! Schema::hasColumn('orders', 'remaining_balance')) {
                $table->decimal('remaining_balance', 15, 2)->default(0)->after('down_payment_amount');
            }
        });

        Schema::table('invoices', function (Blueprint $table) {
            if (! Schema::hasColumn('invoices', 'payment_scheme')) {
                $table->string('payment_scheme', 50)->default('full')->after('tax_amount');
            }
            if (! Schema::hasColumn('invoices', 'down_payment_amount')) {
                $table->decimal('down_payment_amount', 15, 2)->default(0)->after('payment_scheme');
            }
            if (! Schema::hasColumn('invoices', 'remaining_balance')) {
                $table->decimal('remaining_balance', 15, 2)->default(0)->after('down_payment_amount');
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
                'shipping_district',
                'payment_scheme',
                'down_payment_amount',
                'remaining_balance',
            ]);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'payment_scheme',
                'down_payment_amount',
                'remaining_balance',
            ]);
        });
    }
};
