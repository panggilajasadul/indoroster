<?php

namespace Tests\Feature;

use App\Models\SeoLocation;
use App\Models\SeoPage;
use App\Models\SeoPageSection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoExpansionTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_hub_renders_successfully(): void
    {
        $response = $this->get('/aplikasi');

        $response->assertStatus(200);
        $response->assertSee('Inspirasi Aplikasi Roster');
        $response->assertSee('pagar-rumah');
        $response->assertSee('fasad-rumah');
    }

    public function test_application_detail_renders_all_10_use_cases(): void
    {
        $useCases = [
            'pagar-rumah', 'fasad-rumah', 'ventilasi-dinding', 'partisi-ruangan', 'void-tangga',
            'fasad-cafe', 'ruko', 'perumahan-cluster', 'gedung-komersial', 'interior-cafe',
        ];

        foreach ($useCases as $slug) {
            $response = $this->get('/aplikasi/'.$slug);
            $response->assertStatus(200);
            $response->assertSee('Hitung Kebutuhan Keping');
            $response->assertSee('https://schema.org');
        }
    }

    public function test_b2b_hub_pages_have_scale_sections_and_anchors(): void
    {
        $b2bRoutes = [
            '/untuk-kontraktor',
            '/untuk-developer',
            '/untuk-arsitek',
            '/supplier-roster-beton',
            '/roster-beton-proyek',
        ];

        foreach ($b2bRoutes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
            $response->assertSee('#eceran');
            $response->assertSee('#borongan');
            $response->assertSee('#partai-besar');
            $response->assertSee('#kontrak-rutin');
        }
    }

    public function test_location_detail_renders_with_rich_schemas(): void
    {
        $location = SeoLocation::create([
            'name' => 'Bandung',
            'slug' => 'roster-beton-minimalis-bandung',
            'type' => 'city',
            'province_code' => '32',
            'priority' => 1,
            'seo_enabled' => true,
            'headline' => 'Pabrik & Supplier Roster Beton Bandung',
            'intro_content' => 'Layanan suplai roster beton berkualitas langsung pabrik.',
            'delivery_route_info' => 'Pengiriman via Tol Cipularang.',
            'estimated_delivery_time' => '1 hari kerja',
            'shipping_guarantee_text' => 'Garansi 100% ganti baru.',
            'target_districts' => ['Coblong', 'Dago', 'Sukajadi'],
            'custom_faqs' => [
                ['q' => 'Berapa ongkir ke Bandung?', 'a' => 'Ongkir dihitung terjangkau via armada truk.'],
            ],
            'seo_score' => 95,
        ]);

        $response = $this->get('/lokasi/roster-beton-minimalis-bandung');
        $response->assertStatus(200);
        $response->assertSee('Bandung');
        $response->assertSee('Tol Cipularang');
        $response->assertSee('Berapa ongkir ke Bandung?');
        $response->assertSee('OfferCatalog');
    }

    public function test_geo_transactional_page_renders_with_13_sections_and_storytelling(): void
    {
        $page = SeoPage::create([
            'slug' => 'jual-roster-beton-minimalis-bsd-city-harga-pabrik-siap-kirim',
            'page_type' => 'location',
            'primary_keyword' => 'jual roster beton bsd city',
            'location_name' => 'BSD City Tangerang Selatan',
            'title' => 'Jual Roster Beton Minimalis BSD City | Harga Pabrik',
            'meta_description' => 'Beli roster beton minimalis di BSD City langsung dari pabrik sentra Plered.',
            'h1' => 'Jual Roster Beton Minimalis BSD City | Harga Pabrik & Siap Kirim',
            'opening_text' => 'Mencari roster beton minimalis di BSD City dan sekitarnya (Gading Serpong, Alam Sutera)?',
            'status' => 'published',
            'published_at' => now(),
        ]);

        SeoPageSection::create([
            'seo_page_id' => $page->id,
            'section_type' => 'usecase',
            'heading' => 'Eksplorasi Desain Arsitektur & Dinamika Hunian di BSD City',
            'content' => '<p>Kebutuhan fasad di BSD City dan area sekitarnya seperti Gading Serpong, Alam Sutera terus meningkat.</p>',
            'sort_order' => 3,
            'is_visible' => true,
        ]);

        SeoPageSection::create([
            'seo_page_id' => $page->id,
            'section_type' => 'testimonial',
            'heading' => 'Kisah Pengalaman Pembeli IndoRoster di Area BSD City',
            'content' => '<p>Pengakuan kontraktor klaster yang menyatakan pekerjaan pemasangan dinding roster fasad selesai 2 kali lebih cepat.</p>',
            'sort_order' => 4,
            'is_visible' => true,
        ]);

        $response = $this->get('/jual-roster-beton-minimalis-bsd-city-harga-pabrik-siap-kirim');
        $response->assertStatus(200);
        $response->assertSee('Jual Roster Beton Minimalis BSD City');
        $response->assertSee('Dinamika Desain');
        $response->assertSee('Pak Hendra');
        $response->assertSee('Bu Ratna');
        $response->assertSee('Mas Dimas');
        $response->assertSee('Gading Serpong, Alam Sutera');
    }
}
