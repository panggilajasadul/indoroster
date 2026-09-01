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
        $this->command->info('🚀 Memulai sinkronisasi seluruh database IndoRoster...');

        // 1. Lokasi & Wilayah Ekspedisi Pabrik (108 Halaman Lokasi & Kawasan)
        $this->command->info('1️⃣ Seeding Lokasi & Kawasan Proyek...');
        $this->call(SeoLocationPrdSeeder::class);

        // 2. Artikel & Panduan Edukasi Roster (209 Artikel Lengkap)
        $this->command->info('2️⃣ Seeding 209 Artikel Arsitektur & Edukasi...');
        $this->call(RestoredSitemapArticlesSeeder::class);

        // 3. 110 Portal & Negara Ekspor Dinamis (Beserta Modular Page Builder)
        $this->command->info('3️⃣ Seeding 110 Negara Ekspor Global Dinamis & Page Builder...');
        $this->call(ExportPagesSeeder::class);

        // 4. Sinkronisasi Seluruh 4.777 Halaman SEO Page Factory (Pillar, Usecase, Intent, Lokasi)
        $this->command->info('4️⃣ Sinkronisasi 4.777 Halaman Master SEO Page Factory...');
        $this->call(FullSeoPageSyncSeeder::class);

        $this->command->info('✅ Seluruh database (Ekspor, Artikel, Lokasi, dan 4.777 Halaman SEO) berhasil disinkronkan 100%!');
    }
}

