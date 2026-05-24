<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_labels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('label_number', 30)->unique();
            $table->string('courier', 50);
            $table->string('service_type', 50)->nullable();
            $table->string('tracking_number', 100)->nullable();
            $table->string('sender_name')->default('Indoroster');
            $table->string('sender_phone', 20);
            $table->text('sender_address');
            $table->string('recipient_name');
            $table->string('recipient_phone', 20);
            $table->text('recipient_address');
            $table->string('recipient_city', 100);
            $table->string('recipient_postal_code', 10);
            $table->unsignedInteger('total_items');
            $table->decimal('total_weight', 8, 2);
            $table->unsignedInteger('total_packages')->default(1);
            $table->string('package_description')->nullable();
            $table->text('special_instructions')->nullable();
            $table->timestamp('printed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_labels');
    }
};
