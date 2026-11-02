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
        Schema::table('invoices', function (Blueprint $table) {
            if (! Schema::hasColumn('invoices', 'show_signature_stamp')) {
                $table->boolean('show_signature_stamp')->default(true)->after('bank_accounts_override');
            }
            if (! Schema::hasColumn('invoices', 'signature_name')) {
                $table->string('signature_name', 150)->nullable()->default('Abdul Hamid')->after('show_signature_stamp');
            }
            if (! Schema::hasColumn('invoices', 'signature_title')) {
                $table->string('signature_title', 150)->nullable()->default('Divisi Keuangan & Distribusi Pabrik')->after('signature_name');
            }
            if (! Schema::hasColumn('invoices', 'signature_city')) {
                $table->string('signature_city', 100)->nullable()->default('Purwakarta')->after('signature_title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $cols = [
                'show_signature_stamp',
                'signature_name',
                'signature_title',
                'signature_city',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('invoices', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
