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
        Schema::create('manual_documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_number')->unique();
            $table->string('type'); // faktur, surat_jalan, kwitansi, penawaran, surat_pesanan
            $table->string('client_name');
            $table->text('client_address')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('client_email')->nullable();
            $table->json('items'); // JSON array of products, quantities, prices, etc.
            $table->decimal('subtotal', 15, 2);
            $table->decimal('discount', 15, 2)->default(0);
            $table->boolean('has_tax')->default(false);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2);
            $table->date('document_date');
            $table->date('due_date')->nullable();
            $table->string('issued_by')->nullable();
            $table->string('status')->default('draft'); // draft, final
            $table->string('signature_path')->nullable();
            $table->json('extra_data')->nullable(); // For supir/plat on Surat Jalan, or bank info on Invoice, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_documents');
    }
};
