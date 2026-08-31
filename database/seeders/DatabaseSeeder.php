<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Memulai pengisian database IndoRoster...');

        // 1. Lokasi & Wilayah Ekspedisi Pabrik (101 Halaman Lokasi & Kawasan)
        $this->call(SeoLocationSeeder::class);

        // 2. Artikel & Panduan Edukasi Roster
        $this->call(SkillArticlesSeeder::class);

        // 3. Master Keyword Universe
        $this->call(SeoKeywordBatch1Seeder::class);

        // 4. 150 Halaman SEO Komersial (Kontraktor, Developer, Fasad, dll)
        $this->call(SeoPage150GeneratorSeeder::class);

        $this->command->info('✅ Seluruh halaman baru & master SEO berhasil diterbitkan 100%!');
    }
}
