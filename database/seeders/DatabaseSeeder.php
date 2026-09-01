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

        // 4. Master Keyword Universe & 150 Halaman Komersial B2B
        $this->command->info('4️⃣ Seeding Halaman B2B Kontraktor & Developer...');
        $this->call(SeoPage150GeneratorSeeder::class);

        // 5. 800 Halaman Master SEO Transaksional (100% Clean URL)
        $this->command->info('5️⃣ Seeding 800 Halaman Master Geo-Transactional SEO...');
        $this->call(MassGeoTransactionalSeoSeeder::class);

        $this->command->info('✅ Seluruh database (Ekspor, Artikel, Lokasi, dan Ribuan Halaman SEO) berhasil disinkronkan 100%!');
    }
}

