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
        Schema::table('quotation_requests', function (Blueprint $table) {
            if (! Schema::hasColumn('quotation_requests', 'installation_timeline')) {
                $table->string('installation_timeline')->nullable()->after('quantity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_requests', function (Blueprint $table) {
            if (Schema::hasColumn('quotation_requests', 'installation_timeline')) {
                $table->dropColumn('installation_timeline');
            }
        });
    }
};
