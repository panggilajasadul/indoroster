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
        Schema::create('site_visitors', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 100)->index();
            $table->string('ip_address', 45)->nullable()->index();
            $table->string('country_code', 5)->default('ID')->index();
            $table->string('country_name', 100)->default('Indonesia');
            $table->string('user_agent', 255)->nullable();
            $table->date('visit_date')->index();
            $table->unsignedInteger('hits')->default(1);
            $table->timestamp('last_activity_at')->nullable()->index();
            $table->timestamps();

            $table->unique(['session_id', 'visit_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_visitors');
    }
};
