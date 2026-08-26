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
        Schema::table('document_templates', function (Blueprint $table) {
            $table->integer('logo_width')->nullable()->change();
            $table->integer('logo_height')->nullable()->change();
            $table->integer('logo_x')->nullable()->change();
            $table->integer('logo_y')->nullable()->change();

            $table->integer('signature_width')->nullable()->change();
            $table->integer('signature_height')->nullable()->change();
            $table->integer('signature_x')->nullable()->change();
            $table->integer('signature_y')->nullable()->change();

            $table->integer('stamp_width')->nullable()->change();
            $table->integer('stamp_height')->nullable()->change();
            $table->integer('stamp_x')->nullable()->change();
            $table->integer('stamp_y')->nullable()->change();
            $table->decimal('stamp_opacity', 3, 2)->nullable()->change();
            $table->integer('stamp_rotation')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_templates', function (Blueprint $table) {
            //
        });
    }
};
