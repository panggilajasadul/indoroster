<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

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

        $validColumns = Schema::getColumnListing('seo_pages');
        $validColumnsFlip = array_flip($validColumns);

        foreach ($chunks as $index => $chunk) {
            $upsertData = [];
            foreach ($chunk as $item) {
                $row = array_intersect_key($item, $validColumnsFlip);
                unset($row['id']);

                if (isset($row['secondary_keywords']) && is_array($row['secondary_keywords'])) {
                    $row['secondary_keywords'] = json_encode($row['secondary_keywords']);
                }
                if (isset($row['quality_details']) && is_array($row['quality_details'])) {
                    $row['quality_details'] = json_encode($row['quality_details']);
                }
                if (isset($row['audit_checklist']) && is_array($row['audit_checklist'])) {
                    $row['audit_checklist'] = json_encode($row['audit_checklist']);
                }
                if (isset($row['product_ids']) && is_array($row['product_ids'])) {
                    $row['product_ids'] = json_encode($row['product_ids']);
                }
                if (isset($row['related_page_ids']) && is_array($row['related_page_ids'])) {
                    $row['related_page_ids'] = json_encode($row['related_page_ids']);
                }
                if (empty($row['status'])) {
                    $row['status'] = 'published';
                }

                $upsertData[] = $row;
            }

            if (! empty($upsertData)) {
                $updateColumns = array_keys($upsertData[0]);
                // Hilangkan 'slug' dan 'created_at' dari daftar kolom update
                $updateColumns = array_values(array_diff($updateColumns, ['slug', 'created_at']));

                DB::table('seo_pages')->upsert(
                    $upsertData,
                    ['slug'],
                    $updateColumns
                );
            }

            $inserted += count($chunk);
            $this->command->line("Progress: {$inserted}/{$total} halaman SEO disinkronkan...");
        }

        $currentCount = DB::table('seo_pages')->count();
        $this->command->info("✅ SELESAI! Total Halaman SEO di database sekarang: {$currentCount} Halaman.");
    }
}
