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
        if (! Schema::hasTable('seo_keywords')) {
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
        }

        // ═══════════════════════════════════════════════════════════════
        // TABEL 2: seo_pages — Page Matrix & Content Engine
        // ═══════════════════════════════════════════════════════════════
        if (! Schema::hasTable('seo_pages')) {
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
                ])->default('product_landing')->index();

                $table->string('buyer_type', 100)->nullable()->index();   // kontraktor, developer, arsitek, procurement, dll
                $table->string('project_type', 100)->nullable()->index(); // perumahan, gedung, komersial, villa, dll
                $table->string('location_name', 100)->nullable()->index(); // null = nasional

                // Keyword Target
                $table->string('primary_keyword', 255)->index();
                $table->json('secondary_keywords')->nullable(); // array string

                // On-Page Content Elements
                $table->string('title', 255);            // Meta Title (≤ 60 char)
                $table->string('meta_description', 500); // Meta Description (120-160 char)
                $table->string('h1', 255);               // H1 Tag utama
                $table->text('opening_text');            // Narasi pembuka (orientasi buyer)

                // CTA Configuration
                $table->string('cta_text', 100)->default('Konsultasi via WhatsApp');
                $table->text('cta_wa_message')->nullable(); // Custom WhatsApp prefilled text

                // Content Quality & Audit Scores
                $table->unsignedTinyInteger('word_count')->default(0);
                $table->unsignedTinyInteger('quality_score')->default(0); // 0-100 (bobot per section)
                $table->json('audit_checklist')->nullable();             // status checklist lolos/gagal
                $table->text('quality_notes')->nullable();

                // Status & Review Workflow
                $table->enum('status', [
                    'draft',        // Masih berupa draft/kerangka
                    'in_review',    // Siap di-review
                    'approved',     // Disetujui (skor ≥ 75)
                    'published',    // Live di web
                    'archived',     // Diarsipkan
                ])->default('draft')->index();

                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamp('published_at')->nullable();

                // Relasi Hirarki Halaman (Silo Struktur)
                $table->unsignedBigInteger('parent_page_id')->nullable()->index();
                $table->unsignedBigInteger('seo_location_id')->nullable()->index();

                // Aturan Produk yang Ditampilkan
                $table->json('product_ids')->nullable(); // Array ID produk manual
                $table->string('product_matching_rule', 100)->nullable(); // auto, category:roster-beton, dll

                // Advanced SEO
                $table->string('canonical_url', 500)->nullable();
                $table->boolean('noindex')->default(false);
                $table->string('schema_type', 100)->default('WebPage'); // Product, ItemList, Service, WebPage

                $table->timestamps();
                $table->softDeletes();

                $table->index(['page_type', 'status']);
                $table->index(['buyer_type', 'project_type']);
            });
        }

        // ═══════════════════════════════════════════════════════════════
        // TABEL 3: seo_page_sections — Content Blocks per Halaman
        // ═══════════════════════════════════════════════════════════════
        if (! Schema::hasTable('seo_page_sections')) {
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
        }

        // ═══════════════════════════════════════════════════════════════
        // TABEL 4: seo_page_products — Pivot table relasi produk
        // ═══════════════════════════════════════════════════════════════
        if (! Schema::hasTable('seo_page_products')) {
            Schema::create('seo_page_products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('seo_page_id')->constrained('seo_pages')->cascadeOnDelete();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                $table->string('relevance_reason', 255)->nullable(); // Alasan produk relevan untuk halaman ini
                $table->unsignedTinyInteger('sort_order')->default(0);
                $table->timestamps();

                $table->unique(['seo_page_id', 'product_id']);
            });
        }

        // Tambahkan FK constraint seo_keywords.target_page_id → seo_pages
        // setelah kedua tabel sudah ada
        if (Schema::hasTable('seo_keywords') && Schema::hasTable('seo_pages')) {
            try {
                Schema::table('seo_keywords', function (Blueprint $table) {
                    $table->foreign('target_page_id')->references('id')->on('seo_pages')->nullOnDelete();
                });
            } catch (Throwable $e) {
                // Abaikan jika FK sudah ada
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('seo_keywords')) {
            try {
                Schema::table('seo_keywords', function (Blueprint $table) {
                    $table->dropForeign(['target_page_id']);
                });
            } catch (Throwable $e) {
            }
        }
        Schema::dropIfExists('seo_page_products');
        Schema::dropIfExists('seo_page_sections');
        Schema::dropIfExists('seo_pages');
        Schema::dropIfExists('seo_keywords');
    }
};
