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
        if (Schema::hasTable('seo_pages')) {
            Schema::table('seo_pages', function (Blueprint $table) {
                // Ubah page_type dari enum ke string agar fleksibel mendukung semua tipe landing page
                $table->string('page_type', 50)->default('product_landing')->change();

                if (! Schema::hasColumn('seo_pages', 'search_intent')) {
                    $table->string('search_intent', 50)->nullable()->after('secondary_keywords');
                }
                if (! Schema::hasColumn('seo_pages', 'use_case')) {
                    $table->string('use_case', 100)->nullable()->after('project_type');
                }
                if (! Schema::hasColumn('seo_pages', 'og_title')) {
                    $table->string('og_title', 255)->nullable()->after('meta_description');
                }
                if (! Schema::hasColumn('seo_pages', 'og_description')) {
                    $table->string('og_description', 500)->nullable()->after('og_title');
                }
                if (! Schema::hasColumn('seo_pages', 'og_image')) {
                    $table->string('og_image', 500)->nullable()->after('og_description');
                }
                if (! Schema::hasColumn('seo_pages', 'unique_value_proposition')) {
                    $table->text('unique_value_proposition')->nullable()->after('opening_text');
                }
                if (! Schema::hasColumn('seo_pages', 'unique_evidence')) {
                    $table->text('unique_evidence')->nullable()->after('unique_value_proposition');
                }
                if (! Schema::hasColumn('seo_pages', 'unique_angle')) {
                    $table->text('unique_angle')->nullable()->after('unique_evidence');
                }
                if (! Schema::hasColumn('seo_pages', 'cta_type')) {
                    $table->string('cta_type', 50)->default('whatsapp')->after('unique_angle');
                }
                if (! Schema::hasColumn('seo_pages', 'related_page_ids')) {
                    $table->json('related_page_ids')->nullable()->after('parent_page_id');
                }
                if (! Schema::hasColumn('seo_pages', 'structured_data_type')) {
                    $table->string('structured_data_type', 100)->nullable()->after('schema_type');
                }
                if (! Schema::hasColumn('seo_pages', 'priority_score')) {
                    $table->unsignedSmallInteger('priority_score')->default(0)->after('quality_score');
                }
                if (! Schema::hasColumn('seo_pages', 'quality_details')) {
                    $table->json('quality_details')->nullable()->after('quality_notes');
                }
                if (! Schema::hasColumn('seo_pages', 'last_reviewed_at')) {
                    $table->timestamp('last_reviewed_at')->nullable()->after('reviewed_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('seo_pages')) {
            Schema::table('seo_pages', function (Blueprint $table) {
                $columns = [
                    'search_intent',
                    'use_case',
                    'og_title',
                    'og_description',
                    'og_image',
                    'unique_value_proposition',
                    'unique_evidence',
                    'unique_angle',
                    'cta_type',
                    'related_page_ids',
                    'structured_data_type',
                    'priority_score',
                    'quality_details',
                    'last_reviewed_at',
                ];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('seo_pages', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
