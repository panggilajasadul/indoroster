<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom CRM dan Profil pada tabel users dan addresses
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('customer_type', 50)->nullable()->default('individual')->after('role'); // individual, contractor, architect, commercial, developer
            $table->string('company_name')->nullable()->after('customer_type');
            $table->string('lead_status', 50)->nullable()->default('new')->after('company_name'); // new, contacted, quoted, customer, vip
            $table->text('crm_notes')->nullable()->after('lead_status');
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->text('truck_access_notes')->nullable()->after('full_address'); // catatan akses jalan truk armada pabrik
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['customer_type', 'company_name', 'lead_status', 'crm_notes']);
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn(['truck_access_notes']);
        });
    }
};
