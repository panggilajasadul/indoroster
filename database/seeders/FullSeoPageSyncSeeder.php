<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FullSeoPageSyncSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gzPath = database_path('data/seo_pages.json.gz');
        $jsonPath = database_path('data/seo_pages.json');

        $json = null;
        if (File::exists($gzPath)) {
            $json = gzdecode(File::get($gzPath));
        } elseif (File::exists($jsonPath)) {
            $json = File::get($jsonPath);
        }

        if (! $json) {
            $this->command->error('File database/data/seo_pages.json.gz tidak ditemukan.');
            return;
        }

        $records = json_decode($json, true);
        if (! is_array($records)) {
            $this->command->error('Gagal membaca data JSON seo_pages.');
            return;
        }

        $total = count($records);
        $this->command->info("Membersihkan catatan -vol- lawas dan memulai sinkronisasi {$total} Halaman Master SEO...");

        DB::table('seo_pages')->whereRaw("slug REGEXP '-vol-[0-9]+$' OR slug REGEXP '-vol[0-9]+$'")->delete();

        $chunks = array_chunk($records, 250);
        $inserted = 0;

        foreach ($chunks as $index => $chunk) {
            // Hilangkan kolom 'id' agar auto-increment atau gunakan upsert berdasarkan slug
            $upsertData = array_map(function ($item) {
                unset($item['id']);
                return $item;
            }, $chunk);

            DB::table('seo_pages')->upsert(
                $upsertData,
                ['slug'],
                [
                    'page_type', 'primary_keyword', 'secondary_keywords', 'search_intent',
                    'buyer_type', 'project_type', 'use_case', 'seo_location_id', 'location_name',
                    'title', 'meta_description', 'og_title', 'og_description', 'og_image',
                    'canonical_url', 'noindex', 'h1', 'opening_text', 'unique_value_proposition',
                    'unique_evidence', 'unique_angle', 'cta_type', 'cta_text', 'cta_wa_message',
                    'product_matching_rule', 'product_ids', 'parent_page_id', 'related_page_ids',
                    'structured_data_type', 'priority_score', 'quality_score', 'quality_details',
                    'status', 'published_at', 'last_reviewed_at', 'updated_at'
                ]
            );

            $inserted += count($chunk);
            $this->command->line("Progress: {$inserted}/{$total} halaman SEO disinkronkan...");
        }

        $currentCount = DB::table('seo_pages')->count();
        $this->command->info("✅ SELESAI! Total Halaman SEO di database sekarang: {$currentCount} Halaman.");
    }
}
