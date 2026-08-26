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
        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // quotation, invoice, surat_jalan, etc.
            $table->string('paper_size')->default('a4');
            $table->string('orientation')->default('portrait');
            $table->json('margins')->nullable(); // margins: top, bottom, left, right in mm

            // Company Branding Info
            $table->string('company_name')->nullable();
            $table->text('company_address')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_email')->nullable();

            // Logo parameters
            $table->string('logo_path')->nullable();
            $table->integer('logo_width')->default(50); // in mm
            $table->integer('logo_height')->default(25); // in mm
            $table->integer('logo_x')->default(15);      // in mm
            $table->integer('logo_y')->default(15);      // in mm

            // Signature parameters
            $table->string('signature_path')->nullable();
            $table->string('signer_name')->nullable();
            $table->string('signer_position')->nullable();
            $table->integer('signature_width')->default(40); // in mm
            $table->integer('signature_height')->default(20); // in mm
            $table->integer('signature_x')->default(140);     // in mm
            $table->integer('signature_y')->default(240);     // in mm

            // Stamp parameters
            $table->string('stamp_path')->nullable();
            $table->integer('stamp_width')->default(35); // in mm
            $table->integer('stamp_height')->default(35); // in mm
            $table->integer('stamp_x')->default(130);     // in mm
            $table->integer('stamp_y')->default(230);     // in mm
            $table->decimal('stamp_opacity', 3, 2)->default(0.80);
            $table->integer('stamp_rotation')->default(0); // in degrees

            // Financials
            $table->decimal('tax_rate', 5, 2)->default(11.00); // Dynamic tax rate (PPN)

            // Layout Coordinates & Visibility config for standard elements (title, meta, table, customer, terms, footer, etc.)
            $table->json('elements')->nullable();

            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_templates');
    }
};
