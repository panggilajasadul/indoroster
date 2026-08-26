<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom SEO ke tabel products.
     * Kolom-kolom ini diisi oleh Python SEO Engine via Laravel API,
     * dan bisa diedit secara manual melalui admin panel Filament.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Keyword SEO
            $table->string('focus_keyword', 100)->nullable()->after('meta_description')
                ->comment('Keyword utama untuk produk ini, dianalisis oleh Python SEO Engine');
            $table->json('secondary_keywords')->nullable()->after('focus_keyword')
                ->comment('Array keyword sekunder termasuk sinonim dan long-tail');

            // On-page SEO
            $table->string('seo_h1', 255)->nullable()->after('secondary_keywords')
                ->comment('Saran H1 dari Python SEO Engine, bisa berbeda dari meta_title');

            // Open Graph terpisah
            $table->string('og_title', 255)->nullable()->after('seo_h1')
                ->comment('OG title untuk social media sharing, bisa berbeda dari meta_title');
            $table->string('og_description', 500)->nullable()->after('og_title')
                ->comment('OG description untuk social media sharing');

            // SEO Scoring (diisi oleh Python engine)
            $table->unsignedTinyInteger('seo_score')->nullable()->after('og_description')
                ->comment('SEO Health Score 0-100, dihitung oleh Python SEO Engine');
            $table->unsignedTinyInteger('opportunity_score')->nullable()->after('seo_score')
                ->comment('SEO Opportunity Score 0-100, peluang peningkatan ranking');

            // Issues & Tracking
            $table->json('seo_issues')->nullable()->after('opportunity_score')
                ->comment('Array isu SEO yang terdeteksi oleh Python engine');
            $table->timestamp('seo_last_analyzed')->nullable()->after('seo_issues')
                ->comment('Waktu terakhir produk dianalisis oleh Python SEO Engine');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'focus_keyword',
                'secondary_keywords',
                'seo_h1',
                'og_title',
                'og_description',
                'seo_score',
                'opportunity_score',
                'seo_issues',
                'seo_last_analyzed',
            ]);
        });
    }
};
