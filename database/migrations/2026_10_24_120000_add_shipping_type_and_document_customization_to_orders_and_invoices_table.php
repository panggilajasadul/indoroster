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
            if (! Schema::hasColumn('orders', 'shipping_type')) {
                $table->string('shipping_type', 50)->default('paid')->after('shipping_cost');
            }
        });

        Schema::table('invoices', function (Blueprint $table) {
            if (! Schema::hasColumn('invoices', 'shipping_type')) {
                $table->string('shipping_type', 50)->default('paid')->after('shipping_cost');
            }
            if (! Schema::hasColumn('invoices', 'document_title')) {
                $table->string('document_title', 150)->nullable()->after('remaining_balance');
            }
            if (! Schema::hasColumn('invoices', 'document_notes')) {
                $table->text('document_notes')->nullable()->after('notes');
            }
            if (! Schema::hasColumn('invoices', 'document_terms')) {
                $table->text('document_terms')->nullable()->after('document_notes');
            }
            if (! Schema::hasColumn('invoices', 'show_nego_note')) {
                $table->boolean('show_nego_note')->default(false)->after('document_terms');
            }
            if (! Schema::hasColumn('invoices', 'show_shipping_warning')) {
                $table->boolean('show_shipping_warning')->default(true)->after('show_nego_note');
            }
            if (! Schema::hasColumn('invoices', 'show_payment_schedule')) {
                $table->boolean('show_payment_schedule')->default(true)->after('show_shipping_warning');
            }
            if (! Schema::hasColumn('invoices', 'show_bank_accounts')) {
                $table->boolean('show_bank_accounts')->default(true)->after('show_payment_schedule');
            }
            if (! Schema::hasColumn('invoices', 'bank_accounts_override')) {
                $table->json('bank_accounts_override')->nullable()->after('show_bank_accounts');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'shipping_type')) {
                $table->dropColumn('shipping_type');
            }
        });

        Schema::table('invoices', function (Blueprint $table) {
            $cols = [
                'shipping_type',
                'document_title',
                'document_notes',
                'document_terms',
                'show_nego_note',
                'show_shipping_warning',
                'show_payment_schedule',
                'show_bank_accounts',
                'bank_accounts_override',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('invoices', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
