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
        Schema::create('export_pages', function (Blueprint $table) {
            $table->id();
            $table->string('country_slug', 100)->unique();
            $table->string('country_name', 150);
            $table->string('flag_emoji', 20)->nullable();
            $table->string('region', 100)->default('Asia');
            $table->string('destination_port', 150)->nullable();
            $table->string('transit_time', 100)->nullable();
            $table->boolean('is_active')->default(true);

            // SEO Metadata
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();

            // Hero Section Quick Overrides
            $table->string('hero_headline', 255)->nullable();
            $table->text('hero_subheadline')->nullable();
            $table->string('hero_badge', 100)->nullable();

            // Dynamic Modular Page Builder Config (JSON)
            $table->json('sections_config')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('export_pages');
    }
};
