<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExportPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_export_hub_renders_successfully(): void
    {
        $response = $this->get('/export');

        $response->assertStatus(200);
        $response->assertSee('Direct Factory Breeze Blocks');
        $response->assertSee('Knowledge Hub');
        $response->assertSee('https://schema.org');
        $response->assertSee('/export/breeze-blocks');
        $response->assertSee('/export/singapore');
    }

    public function test_export_country_pages_render_successfully(): void
    {
        $sampleCountries = ['singapore', 'australia', 'usa', 'uae', 'uk', 'malaysia'];

        foreach ($sampleCountries as $country) {
            $response = $this->get('/export/'.$country);
            $response->assertStatus(200);
            $response->assertSee('https://schema.org');
            $response->assertSee('Export');
        }
    }

    public function test_export_content_cluster_pages_render_successfully(): void
    {
        $contentPages = [
            'breeze-blocks',
            'concrete-screen-blocks',
            'ventilation-blocks',
            'mashrabiya',
            'decorative-blocks',
            'screen-walls',
            'cobogo-blocks',
            'claustra-blocks',
            'for-villas',
            'for-hotels',
            'for-resorts',
            'for-commercial',
            'wholesale',
            'request-quote',
            'fob-shipping',
        ];

        foreach ($contentPages as $slug) {
            $response = $this->get('/export/'.$slug);
            $response->assertStatus(200);
            $response->assertSee('FAQPage');
            $response->assertSee('https://schema.org');
        }

        // Test that pages with HowTo schema output the HowTo structured data
        $howToPages = ['breeze-blocks', 'concrete-screen-blocks', 'fob-shipping'];
        foreach ($howToPages as $slug) {
            $response = $this->get('/export/'.$slug);
            $response->assertStatus(200);
            $response->assertSee('HowTo');
        }
    }

    public function test_export_subpages_render_successfully(): void
    {
        $catalogRes = $this->get('/export/catalog');
        $catalogRes->assertStatus(200);

        $galleryRes = $this->get('/export/gallery');
        $galleryRes->assertStatus(200);
    }
}
