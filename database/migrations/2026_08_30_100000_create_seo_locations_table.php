<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->string('type', 50)->default('city');
            $table->char('city_code', 4)->nullable()->index();
            $table->char('province_code', 2)->nullable()->index();
            $table->foreignId('parent_id')->nullable()->constrained('seo_locations')->nullOnDelete();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->tinyInteger('priority')->default(2); // 1: Utama (Jabodetabek/Bandung), 2: Jawa, 3: Luar Jawa
            $table->boolean('seo_enabled')->default(false); // Guard agar hanya di-index jika sudah lolos quality check
            $table->integer('seo_score')->default(0); // 0 - 100 Quality score

            // Metadata SEO & Konten Unik
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('headline', 255)->nullable();
            $table->text('intro_content')->nullable();

            // Karakteristik & Logistik Nyata
            $table->string('delivery_route_info', 255)->nullable();
            $table->string('estimated_delivery_time', 100)->nullable();
            $table->text('shipping_guarantee_text')->nullable();
            $table->json('target_districts')->nullable();
            $table->json('custom_faqs')->nullable();
            $table->json('recommended_motif_ids')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_locations');
    }
};
