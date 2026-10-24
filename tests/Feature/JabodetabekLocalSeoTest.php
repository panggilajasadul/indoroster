<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\SeoLocation;
use Database\Seeders\SeoLocationPrdSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JabodetabekLocalSeoTest extends TestCase
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

        for ($i = 1; $i <= 5; $i++) {
            Product::create([
                'name' => "Roster Beton Motif {$i}",
                'slug' => "roster-beton-motif-{$i}",
                'category_id' => $category->id,
                'description' => "Deskripsi roster beton motif {$i} presisi abu batu murni Plered Purwakarta.",
                'price' => 12500,
                'is_active' => true,
                'stock' => 1000,
            ]);
        }
    }

    public function test_all_16_jabodetabek_locations_seeded_with_high_seo_score(): void
    {
        $this->seed(SeoLocationPrdSeeder::class);

        $jabodetabekSlugs = [
            'roster-beton-minimalis-jakarta',
            'roster-beton-minimalis-jakarta-selatan',
            'roster-beton-minimalis-jakarta-timur',
            'roster-beton-minimalis-jakarta-barat',
            'roster-beton-minimalis-jakarta-utara',
            'roster-beton-minimalis-jakarta-pusat',
            'roster-beton-minimalis-bogor',
            'roster-beton-minimalis-depok',
            'roster-beton-minimalis-tangerang',
            'roster-beton-minimalis-tangerang-selatan',
            'roster-beton-citraraya-tangerang',
            'roster-beton-bsd-city',
            'roster-beton-gading-serpong',
            'roster-beton-bintaro',
            'roster-beton-minimalis-bekasi',
            'roster-beton-minimalis-cikarang',
        ];

        $introContents = [];

        foreach ($jabodetabekSlugs as $slug) {
            $location = SeoLocation::where('slug', $slug)->first();
            $this->assertNotNull($location, "Lokasi dengan slug {$slug} harus terdaftar di database.");

            // 1. Validasi Skor SEO minimal 85 (Target 100)
            $this->assertGreaterThanOrEqual(85, $location->seo_score, "Skor SEO untuk {$location->name} harus >= 85.");

            // 2. Validasi Kelengkapan Konten
            $this->assertNotEmpty($location->meta_title);
            $this->assertNotEmpty($location->meta_description);
            $this->assertNotEmpty($location->headline);
            $this->assertGreaterThan(150, strlen($location->intro_content), "Intro content {$location->name} harus mendalam (> 150 karakter).");
            $this->assertNotEmpty($location->delivery_route_info);
            $this->assertNotEmpty($location->estimated_delivery_time);
            $this->assertNotEmpty($location->shipping_guarantee_text);
            $this->assertGreaterThanOrEqual(8, count($location->target_districts), "Target districts untuk {$location->name} harus minimal 8 kecamatan.");
            $this->assertGreaterThanOrEqual(3, count($location->custom_faqs), "Custom FAQs untuk {$location->name} harus minimal 3 item.");

            // 3. Validasi Anti-Duplikasi Konten (Setiap intro harus 100% unik)
            $this->assertNotContains($location->intro_content, $introContents, "Intro content untuk {$location->name} tidak boleh terduplikasi.");
            $introContents[] = $location->intro_content;

            // 4. Validasi Larangan Kata Terlarang (Blacklist Compliance)
            $fullText = $location->intro_content.' '.json_encode($location->custom_faqs).' '.$location->meta_description;
            $this->assertStringNotContainsStringIgnoringCase('k-200', $fullText);
            $this->assertStringNotContainsStringIgnoringCase('k200', $fullText);
            $this->assertStringNotContainsStringIgnoringCase('mesin hidrolik', $fullText);
            $this->assertStringNotContainsStringIgnoringCase('press hidrolik', $fullText);
            $this->assertStringNotContainsStringIgnoringCase('pasir silika', $fullText);
            $this->assertStringNotContainsStringIgnoringCase('cor basah', $fullText);
        }
    }

    public function test_jabodetabek_location_pages_render_successfully(): void
    {
        $this->seed(SeoLocationPrdSeeder::class);

        $sampleSlugs = [
            'roster-beton-minimalis-tangerang',
            'roster-beton-minimalis-tangerang-selatan',
            'roster-beton-citraraya-tangerang',
            'roster-beton-minimalis-bogor',
            'roster-beton-minimalis-depok',
            'roster-beton-minimalis-bekasi',
            'roster-beton-minimalis-jakarta',
        ];

        foreach ($sampleSlugs as $slug) {
            $response = $this->get('/lokasi/'.$slug);
            $response->assertStatus(200);
            $response->assertSee('Layanan Pengiriman Langsung Pabrik');
            $response->assertSee('Garansi 100%');
            $response->assertSee('Hitung Kebutuhan Dinding');
            $response->assertSee('https://schema.org');
            $response->assertSee('FAQPage');
            $response->assertSee('OfferCatalog');
            $response->assertSee('GeoCoordinates');
        }
    }
}
