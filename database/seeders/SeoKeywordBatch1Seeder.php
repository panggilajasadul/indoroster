<?php

namespace Database\Seeders;

use App\Models\SeoKeyword;
use Illuminate\Database\Seeder;

/**
 * Seed keyword universe Batch 1 untuk SEO Page Factory IndoRoster.
 *
 * Keyword dikelompokkan berdasarkan cluster dan buyer intent.
 * Priority score dihitung otomatis setelah seeding.
 *
 * Sumber: analisis studi kasus buyer journey + keyword research manual.
 * Akan diperbarui berdasarkan data GSC setelah halaman mulai terindeks.
 */
class SeoKeywordBatch1Seeder extends Seeder
{
    public function run(): void
    {
        $keywords = [
            // ─── A. Core Supplier / Pabrik ───
            ['keyword' => 'supplier roster beton', 'cluster' => 'supplier', 'intent' => 'bofu', 'buyer_type' => 'umum', 'business_value' => 5, 'conversion_potential' => 5, 'competition' => 3, 'search_volume_est' => '100-1K'],
            ['keyword' => 'pabrik roster beton', 'cluster' => 'supplier', 'intent' => 'bofu', 'buyer_type' => 'kontraktor', 'business_value' => 5, 'conversion_potential' => 5, 'competition' => 3, 'search_volume_est' => '100-1K'],
            ['keyword' => 'jual roster beton', 'cluster' => 'supplier', 'intent' => 'bofu', 'buyer_type' => 'umum', 'business_value' => 4, 'conversion_potential' => 4, 'competition' => 4, 'search_volume_est' => '100-1K'],
            ['keyword' => 'distributor roster beton', 'cluster' => 'supplier', 'intent' => 'bofu', 'buyer_type' => 'umum', 'business_value' => 4, 'conversion_potential' => 4, 'competition' => 3, 'search_volume_est' => '10-100'],
            ['keyword' => 'toko roster beton', 'cluster' => 'supplier', 'intent' => 'bofu', 'buyer_type' => 'umum', 'business_value' => 3, 'conversion_potential' => 3, 'competition' => 4, 'search_volume_est' => '100-1K'],

            // ─── B. Grosir / Volume ───
            ['keyword' => 'supplier roster beton grosir', 'cluster' => 'grosir', 'intent' => 'bofu', 'buyer_type' => 'kontraktor', 'business_value' => 5, 'conversion_potential' => 5, 'competition' => 2, 'search_volume_est' => '10-100'],
            ['keyword' => 'grosir roster beton', 'cluster' => 'grosir', 'intent' => 'bofu', 'buyer_type' => 'kontraktor', 'business_value' => 5, 'conversion_potential' => 5, 'competition' => 2, 'search_volume_est' => '10-100'],
            ['keyword' => 'roster beton grosir murah', 'cluster' => 'grosir', 'intent' => 'bofu', 'buyer_type' => 'pemborong', 'business_value' => 4, 'conversion_potential' => 4, 'competition' => 3, 'search_volume_est' => '10-100'],
            ['keyword' => 'roster beton volume besar', 'cluster' => 'grosir', 'intent' => 'bofu', 'buyer_type' => 'developer', 'business_value' => 5, 'conversion_potential' => 5, 'competition' => 1, 'search_volume_est' => '10-100'],

            // ─── C. Kontraktor ───
            ['keyword' => 'supplier roster untuk kontraktor', 'cluster' => 'kontraktor', 'intent' => 'bofu', 'buyer_type' => 'kontraktor', 'business_value' => 5, 'conversion_potential' => 5, 'competition' => 1, 'search_volume_est' => '10-100'],
            ['keyword' => 'roster beton untuk proyek kontraktor', 'cluster' => 'kontraktor', 'intent' => 'bofu', 'buyer_type' => 'kontraktor', 'business_value' => 5, 'conversion_potential' => 5, 'competition' => 1, 'search_volume_est' => '10-100'],
            ['keyword' => 'harga roster beton proyek', 'cluster' => 'kontraktor', 'intent' => 'mofu', 'buyer_type' => 'kontraktor', 'business_value' => 4, 'conversion_potential' => 4, 'competition' => 3, 'search_volume_est' => '100-1K'],
            ['keyword' => 'roster beton siku 90 derajat', 'cluster' => 'kontraktor', 'intent' => 'mofu', 'buyer_type' => 'kontraktor', 'business_value' => 4, 'conversion_potential' => 3, 'competition' => 1, 'search_volume_est' => '10-100'],

            // ─── D. Developer ───
            ['keyword' => 'roster beton untuk developer perumahan', 'cluster' => 'developer', 'intent' => 'bofu', 'buyer_type' => 'developer', 'project_type' => 'perumahan', 'business_value' => 5, 'conversion_potential' => 5, 'competition' => 1, 'search_volume_est' => '10-100'],
            ['keyword' => 'supplier roster cluster perumahan', 'cluster' => 'developer', 'intent' => 'bofu', 'buyer_type' => 'developer', 'project_type' => 'perumahan', 'business_value' => 5, 'conversion_potential' => 5, 'competition' => 1, 'search_volume_est' => '10-100'],
            ['keyword' => 'roster beton untuk perumahan', 'cluster' => 'developer', 'intent' => 'mofu', 'buyer_type' => 'developer', 'project_type' => 'perumahan', 'business_value' => 4, 'conversion_potential' => 4, 'competition' => 2, 'search_volume_est' => '10-100'],

            // ─── E. Pemborong ───
            ['keyword' => 'supplier roster untuk pemborong', 'cluster' => 'kontraktor', 'intent' => 'bofu', 'buyer_type' => 'pemborong', 'business_value' => 4, 'conversion_potential' => 4, 'competition' => 1, 'search_volume_est' => '10-100'],
            ['keyword' => 'beli roster beton untuk pemborong', 'cluster' => 'kontraktor', 'intent' => 'bofu', 'buyer_type' => 'pemborong', 'business_value' => 4, 'conversion_potential' => 4, 'competition' => 1, 'search_volume_est' => '10-100'],

            // ─── F. Procurement ───
            ['keyword' => 'vendor roster beton untuk proyek', 'cluster' => 'procurement', 'intent' => 'bofu', 'buyer_type' => 'procurement', 'business_value' => 5, 'conversion_potential' => 5, 'competition' => 1, 'search_volume_est' => '10-100'],
            ['keyword' => 'pengadaan roster beton proyek', 'cluster' => 'procurement', 'intent' => 'bofu', 'buyer_type' => 'procurement', 'business_value' => 5, 'conversion_potential' => 5, 'competition' => 1, 'search_volume_est' => '10-100'],

            // ─── G. Project-Specific ───
            ['keyword' => 'roster beton untuk proyek perumahan', 'cluster' => 'developer', 'intent' => 'bofu', 'buyer_type' => 'kontraktor', 'project_type' => 'perumahan', 'business_value' => 5, 'conversion_potential' => 5, 'competition' => 1, 'search_volume_est' => '10-100'],
            ['keyword' => 'roster beton untuk proyek gedung', 'cluster' => 'gedung', 'intent' => 'bofu', 'buyer_type' => 'kontraktor', 'project_type' => 'gedung', 'business_value' => 5, 'conversion_potential' => 5, 'competition' => 1, 'search_volume_est' => '10-100'],
            ['keyword' => 'roster beton untuk bangunan komersial', 'cluster' => 'gedung', 'intent' => 'mofu', 'buyer_type' => 'arsitek', 'project_type' => 'komersial', 'business_value' => 4, 'conversion_potential' => 4, 'competition' => 1, 'search_volume_est' => '10-100'],
            ['keyword' => 'roster beton proyek besar', 'cluster' => 'grosir', 'intent' => 'bofu', 'buyer_type' => 'developer', 'business_value' => 4, 'conversion_potential' => 4, 'competition' => 1, 'search_volume_est' => '10-100'],

            // ─── H. Use Case: Fasad ───
            ['keyword' => 'roster beton untuk fasad', 'cluster' => 'fasad', 'intent' => 'mofu', 'buyer_type' => 'arsitek', 'business_value' => 4, 'conversion_potential' => 4, 'competition' => 2, 'search_volume_est' => '100-1K'],
            ['keyword' => 'roster beton fasad rumah minimalis', 'cluster' => 'fasad', 'intent' => 'mofu', 'buyer_type' => 'owner', 'business_value' => 4, 'conversion_potential' => 3, 'competition' => 3, 'search_volume_est' => '100-1K'],
            ['keyword' => 'roster untuk dinding depan rumah', 'cluster' => 'fasad', 'intent' => 'tofu', 'buyer_type' => 'owner', 'business_value' => 3, 'conversion_potential' => 3, 'competition' => 3, 'search_volume_est' => '100-1K'],

            // ─── I. Use Case: Ventilasi ───
            ['keyword' => 'roster beton untuk ventilasi', 'cluster' => 'ventilasi', 'intent' => 'tofu', 'buyer_type' => 'owner', 'business_value' => 3, 'conversion_potential' => 3, 'competition' => 2, 'search_volume_est' => '100-1K'],
            ['keyword' => 'roster lubang angin', 'cluster' => 'ventilasi', 'intent' => 'tofu', 'buyer_type' => 'umum', 'business_value' => 2, 'conversion_potential' => 2, 'competition' => 3, 'search_volume_est' => '100-1K'],
            ['keyword' => 'bata roster ventilasi udara', 'cluster' => 'ventilasi', 'intent' => 'tofu', 'buyer_type' => 'umum', 'business_value' => 2, 'conversion_potential' => 2, 'competition' => 2, 'search_volume_est' => '10-100'],

            // ─── J. Produk: Minimalis ───
            ['keyword' => 'roster beton minimalis', 'cluster' => 'produk', 'intent' => 'mofu', 'buyer_type' => 'umum', 'business_value' => 4, 'conversion_potential' => 4, 'competition' => 4, 'search_volume_est' => '1K-10K'],
            ['keyword' => 'jual roster beton minimalis', 'cluster' => 'produk', 'intent' => 'bofu', 'buyer_type' => 'umum', 'business_value' => 4, 'conversion_potential' => 4, 'competition' => 4, 'search_volume_est' => '100-1K'],
            ['keyword' => 'supplier roster beton minimalis', 'cluster' => 'supplier', 'intent' => 'bofu', 'buyer_type' => 'umum', 'business_value' => 4, 'conversion_potential' => 4, 'competition' => 3, 'search_volume_est' => '10-100'],

            // ─── K. Harga ───
            ['keyword' => 'harga roster beton', 'cluster' => 'harga', 'intent' => 'mofu', 'buyer_type' => 'umum', 'business_value' => 3, 'conversion_potential' => 3, 'competition' => 4, 'search_volume_est' => '1K-10K'],
            ['keyword' => 'harga roster beton per pcs', 'cluster' => 'harga', 'intent' => 'mofu', 'buyer_type' => 'umum', 'business_value' => 3, 'conversion_potential' => 3, 'competition' => 4, 'search_volume_est' => '100-1K'],
            ['keyword' => 'harga roster beton untuk proyek', 'cluster' => 'harga', 'intent' => 'bofu', 'buyer_type' => 'kontraktor', 'business_value' => 4, 'conversion_potential' => 4, 'competition' => 3, 'search_volume_est' => '10-100'],

            // ─── L. Lokasi ───
            ['keyword' => 'supplier roster beton jakarta', 'cluster' => 'lokasi', 'intent' => 'bofu', 'buyer_type' => 'umum', 'location' => 'Jakarta', 'business_value' => 5, 'conversion_potential' => 5, 'competition' => 3, 'search_volume_est' => '100-1K'],
            ['keyword' => 'supplier roster beton bekasi', 'cluster' => 'lokasi', 'intent' => 'bofu', 'buyer_type' => 'kontraktor', 'location' => 'Bekasi', 'business_value' => 4, 'conversion_potential' => 5, 'competition' => 2, 'search_volume_est' => '10-100'],
            ['keyword' => 'supplier roster beton bandung', 'cluster' => 'lokasi', 'intent' => 'bofu', 'buyer_type' => 'umum', 'location' => 'Bandung', 'business_value' => 4, 'conversion_potential' => 4, 'competition' => 3, 'search_volume_est' => '10-100'],
            ['keyword' => 'jual roster beton depok', 'cluster' => 'lokasi', 'intent' => 'bofu', 'buyer_type' => 'umum', 'location' => 'Depok', 'business_value' => 3, 'conversion_potential' => 3, 'competition' => 2, 'search_volume_est' => '10-100'],
            ['keyword' => 'supplier roster beton tangerang', 'cluster' => 'lokasi', 'intent' => 'bofu', 'buyer_type' => 'umum', 'location' => 'Tangerang', 'business_value' => 3, 'conversion_potential' => 3, 'competition' => 2, 'search_volume_est' => '10-100'],

            // ─── M. Edukasi / Informational ───
            ['keyword' => 'roster beton adalah', 'cluster' => 'edukasi', 'intent' => 'tofu', 'buyer_type' => 'umum', 'business_value' => 2, 'conversion_potential' => 2, 'competition' => 3, 'search_volume_est' => '100-1K'],
            ['keyword' => 'keunggulan roster beton', 'cluster' => 'edukasi', 'intent' => 'tofu', 'buyer_type' => 'owner', 'business_value' => 2, 'conversion_potential' => 2, 'competition' => 2, 'search_volume_est' => '10-100'],
            ['keyword' => 'cara pasang roster beton', 'cluster' => 'edukasi', 'intent' => 'tofu', 'buyer_type' => 'umum', 'business_value' => 1, 'conversion_potential' => 2, 'competition' => 3, 'search_volume_est' => '100-1K'],
        ];

        // Hapus keyword existing dari seeder ini untuk idempotency
        // (hanya hapus yang bersumber dari seeder ini, bukan yang manual)
        SeoKeyword::where('source', 'manual')
            ->whereIn('keyword', array_column($keywords, 'keyword'))
            ->delete();

        foreach ($keywords as $data) {
            $keyword = SeoKeyword::create(array_merge($data, [
                'status' => 'idea',
                'source' => 'manual',
            ]));

            $keyword->updatePriorityScore();
        }

        $this->command->info('✅ Seeded '.count($keywords).' keywords untuk Batch 1.');
    }
}
