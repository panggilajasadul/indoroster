<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ═══════════════════════════════════════════════════════════════
        // TABEL 1: seo_keywords — Master Keyword Universe
        // ═══════════════════════════════════════════════════════════════
        Schema::create('seo_keywords', function (Blueprint $table) {
            $table->id();
            $table->string('keyword', 255);
            $table->string('cluster', 100)->index(); // supplier, kontraktor, developer, gedung, fasad, ventilasi, grosir, procurement, harga, produk
            $table->enum('intent', ['tofu', 'mofu', 'bofu'])->default('mofu');
            $table->string('buyer_type', 100)->nullable(); // kontraktor, developer, pemborong, arsitek, procurement, owner, umum
            $table->string('project_type', 100)->nullable(); // perumahan, gedung, komersial, renovasi, fasad, ventilasi, umum
            $table->string('location', 100)->nullable(); // null = nasional
            $table->unsignedTinyInteger('business_value')->default(3); // 1-5
            $table->unsignedTinyInteger('conversion_potential')->default(3); // 1-5
            $table->string('search_volume_est', 50)->nullable(); // "100-1K", "1K-10K", dsb
            $table->unsignedTinyInteger('competition')->default(3); // 1-5
            $table->unsignedTinyInteger('priority_score')->default(0); // kalkulasi otomatis
            $table->enum('status', [
                'idea',
                'researched',
                'targeted',
                'mapped',
                'archived',
            ])->default('idea');
            $table->unsignedBigInteger('target_page_id')->nullable();
            $table->string('source', 100)->default('manual'); // manual, gsc, google_ads, competitor, ai_suggestion
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['cluster', 'intent']);
            $table->index(['status', 'priority_score']);
        });

        // ═══════════════════════════════════════════════════════════════
        // TABEL 2: seo_pages — Page Matrix & Content Engine
        // ═══════════════════════════════════════════════════════════════
        Schema::create('seo_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 200)->unique();

            // Identitas & Klasifikasi Halaman
            $table->enum('page_type', [
                'pillar',          // Halaman pilar utama
                'buyer',           // Buyer-specific (kontraktor, developer, dll)
                'project',         // Project-specific (perumahan, gedung, dll)
                'usecase',         // Use-case (fasad, ventilasi, pagar, dll)
                'product_landing', // Halaman produk group/cluster
                'location',        // Location-commercial page
                'guide',           // Buying guide / informational
                'faq_hub',         // FAQ hub page
                'case_study',      // Case study nyata
                'wholesale',       // Volume/grosir commercial
                'procurement',     // Procurement/vendor
                'pricing',         // Harga & quotation
            ])->index();

            $table->string('primary_keyword', 255);
            $table->json('secondary_keywords')->nullable();
            $table->enum('search_intent', ['tofu', 'mofu', 'bofu'])->default('bofu');

            // Target — siapa, untuk apa, di mana
            $table->string('buyer_type', 100)->nullable()->index();
            $table->string('project_type', 100)->nullable();
            $table->string('use_case', 100)->nullable(); // fasad, ventilasi, pagar, carport, dekoratif
            $table->foreignId('seo_location_id')->nullable()->constrained('seo_locations')->nullOnDelete();
            $table->string('location_name', 100)->nullable(); // untuk display, bisa tanpa seo_location

            // SEO Meta
            $table->string('title', 255); // <title> tag
            $table->text('meta_description');
            $table->string('og_title', 255)->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image', 500)->nullable();
            $table->string('canonical_url', 500)->nullable();
            $table->boolean('noindex')->default(false);

            // Konten Utama
            $table->string('h1', 255);
            $table->text('opening_text');
            $table->text('unique_value_proposition'); // UVP — kenapa halaman ini ada
            $table->text('unique_evidence')->nullable(); // Data unik / fakta nyata
            $table->text('unique_angle')->nullable(); // Apa yang membedakan dari halaman lain

            // CTA & Konversi
            $table->string('cta_type', 50)->default('whatsapp'); // whatsapp, quotation, catalog, calculator, contact
            $table->string('cta_text', 255)->nullable();
            $table->text('cta_wa_message')->nullable();

            // Product Matching
            $table->string('product_matching_rule', 100)->nullable(); // all, category:slug, best_for:value, featured, manual
            $table->json('product_ids')->nullable(); // Manual override product IDs

            // Struktur & Linking
            $table->foreignId('parent_page_id')->nullable()->constrained('seo_pages')->nullOnDelete();
            $table->json('related_page_ids')->nullable();
            $table->string('structured_data_type', 50)->nullable(); // product_list, faq, how_to, article

            // Scoring & Quality
            $table->unsignedTinyInteger('priority_score')->default(0);
            $table->unsignedTinyInteger('quality_score')->default(0); // 0-100
            $table->json('quality_details')->nullable(); // Detail per kriteria

            // Workflow & Status
            $table->enum('status', [
                'idea',
                'research',
                'approved',
                'content_brief',
                'draft',
                'qa',
                'needs_review',
                'ready',
                'published',
                'monitoring',
                'update',
                'merge',
                'noindex',
                'archived',
            ])->default('idea')->index();

            $table->timestamp('published_at')->nullable();
            $table->timestamp('last_reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->index(['status', 'priority_score']);
            $table->index(['buyer_type', 'project_type']);
        });

        // ═══════════════════════════════════════════════════════════════
        // TABEL 3: seo_page_sections — Content Blocks per Halaman
        // ═══════════════════════════════════════════════════════════════
        Schema::create('seo_page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seo_page_id')->constrained('seo_pages')->cascadeOnDelete();

            $table->enum('section_type', [
                'intro',         // Pengantar / problem statement
                'problem',       // Masalah yang dihadapi buyer
                'solution',      // Solusi IndoRoster
                'products',      // Produk relevan
                'specs',         // Spesifikasi teknis
                'usecase',       // Aplikasi / use case
                'process',       // Cara memesan / proses
                'shipping',      // Informasi pengiriman
                'volume',        // MOQ / kebutuhan volume
                'pricing_guide', // Panduan harga (tanpa harga fiktif)
                'faq',           // FAQ section
                'cta',           // Call to action
                'related',       // Halaman terkait / internal links
                'testimonial',   // Testimoni (hanya yang otentik)
                'calculator',    // Link/embed kalkulator
                'comparison',    // Perbandingan produk/material
                'custom',        // Section custom
            ]);

            $table->string('heading', 255); // H2/H3
            $table->text('content'); // HTML content
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->string('source', 100)->nullable(); // manual, ai_draft, gsc_data
            $table->text('unique_angle')->nullable();

            $table->timestamps();

            $table->index(['seo_page_id', 'sort_order']);
        });

        // ═══════════════════════════════════════════════════════════════
        // TABEL 4: seo_page_products — Pivot table relasi produk
        // ═══════════════════════════════════════════════════════════════
        Schema::create('seo_page_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seo_page_id')->constrained('seo_pages')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('relevance_reason', 255)->nullable(); // Alasan produk relevan untuk halaman ini
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['seo_page_id', 'product_id']);
        });

        // Tambahkan FK constraint seo_keywords.target_page_id → seo_pages
        // setelah kedua tabel sudah ada
        Schema::table('seo_keywords', function (Blueprint $table) {
            $table->foreign('target_page_id')->references('id')->on('seo_pages')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('seo_keywords', function (Blueprint $table) {
            $table->dropForeign(['target_page_id']);
        });
        Schema::dropIfExists('seo_page_products');
        Schema::dropIfExists('seo_page_sections');
        Schema::dropIfExists('seo_pages');
        Schema::dropIfExists('seo_keywords');
    }
};
