<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('transaction_id')->nullable();
            $table->string('payment_type', 50)->nullable();
            $table->string('bank', 50)->nullable();
            $table->string('va_number', 50)->nullable();
            $table->decimal('gross_amount', 15, 2);
            $table->enum('status', [
                'pending', 'settlement', 'capture', 'deny', 'cancel', 'expire', 'refund',
            ])->default('pending');
            $table->string('fraud_status', 20)->nullable();
            $table->string('payment_url', 500)->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
