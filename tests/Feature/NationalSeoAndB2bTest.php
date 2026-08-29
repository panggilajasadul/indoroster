<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\SeoLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NationalSeoAndB2bTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $category = Category::create([
            'name' => 'Roster Beton Minimalis',
            'slug' => 'roster-beton-minimalis',
            'is_active' => true,
        ]);

        // Buat produk sample
        Product::create([
            'category_id' => $category->id,
            'name' => 'Roster Beton Minimalis Motif Nako (2 Sisi) 20 x 20 x 10 cm',
            'slug' => 'roster-beton-minimalis-motif-nako-2-sisi-20-x-20-x-10-cm',
            'description' => 'Roster beton minimalis berkualitas tinggi.',
            'price' => 12500,
            'is_active' => true,
        ]);

        // Buat lokasi sample
        SeoLocation::create([
            'name' => 'Bandung',
            'slug' => 'roster-beton-minimalis-bandung',
            'type' => 'city',
            'province_code' => '32',
            'priority' => 1,
            'seo_enabled' => true,
            'seo_score' => 95,
            'meta_title' => 'Jual Roster Beton Bandung | Produsen Resmi — IndoRoster',
            'meta_description' => 'Pusat roster beton minimalis di Bandung.',
            'headline' => 'Supplier Roster Beton untuk Wilayah Bandung',
            'intro_content' => 'Layanan pengiriman roster beton minimalis untuk proyek di Bandung Raya dengan kualitas cetak padat presisi.',
        ]);

        Page::firstOrCreate(
            ['slug' => 'untuk-kontraktor'],
            ['title' => 'Khusus Kontraktor Proyek', 'content' => [], 'is_active' => true]
        );

        Page::firstOrCreate(
            ['slug' => 'untuk-developer'],
            ['title' => 'Pengadaan Developer', 'content' => [], 'is_active' => true]
        );

        Page::firstOrCreate(
            ['slug' => 'untuk-arsitek'],
            ['title' => 'Katalog Teknis Arsitek', 'content' => [], 'is_active' => true]
        );

        Page::firstOrCreate(
            ['slug' => 'supplier-roster-beton'],
            ['title' => 'Grosir Toko Bangunan', 'content' => [], 'is_active' => true]
        );
    }

    public function test_homepage_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('IndoRoster');
    }

    public function test_b2b_contractor_page_loads_successfully()
    {
        $response = $this->get('/untuk-kontraktor');
        $response->assertStatus(200);
        $response->assertSee('Khusus Kontraktor');
    }

    public function test_b2b_developer_page_loads_successfully()
    {
        $response = $this->get('/untuk-developer');
        $response->assertStatus(200);
        $response->assertSee('Developer');
    }

    public function test_b2b_architect_page_loads_successfully()
    {
        $response = $this->get('/untuk-arsitek');
        $response->assertStatus(200);
        $response->assertSee('Arsitek');
    }

    public function test_b2b_supplier_page_loads_successfully()
    {
        $response = $this->get('/supplier-roster-beton');
        $response->assertStatus(200);
        $response->assertSee('Grosir');
    }

    public function test_roster_calculator_page_loads_successfully()
    {
        $response = $this->get('/kalkulator-roster');
        $response->assertStatus(200);
        $response->assertSee('Kalkulator Kebutuhan');
    }

    public function test_location_hub_and_detail_pages_load_successfully()
    {
        $hubResponse = $this->get('/lokasi');
        $hubResponse->assertStatus(200);
        $hubResponse->assertSee('Wilayah Layanan Pengiriman');

        $detailResponse = $this->get('/lokasi/bandung');
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Roster Beton');
    }
}
