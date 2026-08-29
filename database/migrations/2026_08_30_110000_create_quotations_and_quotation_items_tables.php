<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_number', 50)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('contact_name', 100);
            $table->string('company_name', 150)->nullable();
            $table->string('job_title', 100)->nullable(); // Kontraktor, Project Manager, Arsitek, Developer, Owner
            $table->string('phone', 30); // WhatsApp
            $table->string('email', 100)->nullable();
            $table->string('project_type', 100); // Perumahan, Cluster, Hotel, Villa, Cafe, Kantor, Ruko, dll
            $table->string('project_location_city', 100);
            $table->text('project_address')->nullable();
            $table->date('deadline_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('attachment_path')->nullable(); // RAB / BoQ / Gambar Arsitek
            $table->enum('status', ['pending', 'reviewed', 'estimated', 'approved', 'rejected'])->default('pending');
            $table->decimal('estimated_total', 15, 2)->nullable();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name', 200);
            $table->string('variant_name', 100)->nullable();
            $table->integer('quantity');
            $table->string('unit', 20)->default('pcs');
            $table->decimal('unit_price', 12, 2)->nullable();
            $table->decimal('subtotal', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
        Schema::dropIfExists('quotations');
    }
};
