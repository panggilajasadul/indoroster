<?php

use App\Models\SiteSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $seoSettings = [
            [
                'key' => 'meta_title_default',
                'name' => 'Judul SEO Default Website (Meta Title)',
                'value' => 'Pabrik Roster Beton Minimalis | Suplier Proyek Kirim Jabodetabek & Nasional — IndoRoster',
                'group' => 'seo',
                'type' => 'text',
                'description' => 'Judul utama default website yang muncul di hasil pencarian Google jika halaman tidak memiliki judul khusus.',
            ],
            [
                'key' => 'meta_description_default',
                'name' => 'Meta Deskripsi SEO Default',
                'value' => 'Pusat produsen tangan pertama roster beton minimalis, bata expose, dan loster arsitektural modern harga pabrik. Melayani pengiriman cepat partai kecil & proyek ribuan pcs ke Jabodetabek, Bandung, Karawang, Cirebon & seluruh Indonesia.',
                'group' => 'seo',
                'type' => 'textarea',
                'description' => 'Ringkasan deskripsi website yang muncul di bawah judul pada pencarian Google.',
            ],
            [
                'key' => 'seo_keywords_default',
                'name' => 'Kata Kunci Global (Meta Keywords)',
                'value' => 'roster beton minimalis, loster beton minimalis, jual roster beton, pabrik roster beton, harga roster beton, roster dinding minimalis, ventilasi beton, jual roster jakarta, roster beton jabodetabek, roster beton bandung, supplier roster proyek',
                'group' => 'seo',
                'type' => 'textarea',
                'description' => 'Daftar kata kunci utama industri roster beton minimalis yang ditargetkan di seluruh website.',
            ],
            [
                'key' => 'google_site_verification',
                'name' => 'Kode Verifikasi Google Search Console',
                'value' => '5T-7RFSLMEwCNdq2lx93GU5S5BckFBgjFPf5B-HlT1Y',
                'group' => 'seo',
                'type' => 'text',
                'description' => 'Kode meta tag verifikasi kepemilikan domain di Google Search Console / Webmaster Tools.',
            ],
            [
                'key' => 'bing_site_verification',
                'name' => 'Kode Verifikasi Bing Webmaster Tools',
                'value' => '',
                'group' => 'seo',
                'type' => 'text',
                'description' => 'Kode meta tag verifikasi kepemilikan domain di Bing Webmaster Tools (Opsional).',
            ],
            [
                'key' => 'google_analytics_id',
                'name' => 'Google Analytics Measurement ID (GA4)',
                'value' => 'G-GZQXJ03B4C',
                'group' => 'seo',
                'type' => 'text',
                'description' => 'Kode tracking Google Analytics 4 (GA4) untuk melacak trafik pengunjung organik.',
            ],
        ];

        foreach ($seoSettings as $s) {
            SiteSetting::updateOrCreate(
                ['key' => $s['key']],
                [
                    'name' => $s['name'],
                    'value' => $s['value'],
                    'group' => $s['group'],
                    'type' => $s['type'],
                    'description' => $s['description'],
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        SiteSetting::where('group', 'seo')->delete();
    }
};
