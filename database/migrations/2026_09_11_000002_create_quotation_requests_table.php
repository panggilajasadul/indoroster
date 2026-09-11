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
        Schema::create('quotation_requests', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();

            // Client details
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();

            // Location & shipping calculation
            $table->string('province_code')->nullable();
            $table->string('city_code')->nullable();
            $table->string('province_name')->nullable();
            $table->string('city_name')->nullable();
            $table->text('address')->nullable();

            // Quotation details
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('estimated_shipping_cost', 12, 2)->default(0);
            $table->string('shipping_rate_type')->default('flat'); // flat / per_pcs
            $table->text('notes')->nullable();

            // Status and tracking
            $table->string('status')->default('pending'); // pending, contacted, quoted, deal, cancelled
            $table->text('admin_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_requests');
    }
};
