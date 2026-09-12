<?php

namespace Tests\Feature;

use App\Livewire\Promo\PromoRosterPabrik;
use App\Models\PromoPage;
use Database\Seeders\PromoPageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class MetaTrackingAndLandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_renders_successfully_with_meta_pixel(): void
    {
        $response = $this->get('/promo/roster-pabrik?kota=Bekasi');

        $response->assertStatus(200);
        $response->assertSee('Pusat Roster Beton Minimalis');
        $response->assertSee('Bekasi');
        $response->assertSee('947593387751313'); // Meta Pixel ID
        $response->assertSee('trackMetaEvent');
    }

    public function test_landing_page_aliases_work(): void
    {
        $this->get('/promo')->assertStatus(200);
        $this->get('/penawaran-proyek')->assertStatus(200);
        $this->get('/promo/roster-minimalis')->assertStatus(200);
    }

    public function test_livewire_interactive_calculator(): void
    {
        Livewire::test(PromoRosterPabrik::class, ['kota' => 'Bandung'])
            ->set('wallLength', 6)
            ->set('wallHeight', 3)
            ->set('wasteMargin', 5)
            ->assertSee('473 Pcs') // ceil(6*3*25 * 1.05) = ceil(450 * 1.05) = 473
            ->assertSee('Bandung');

        // Test 1m x 1m accurate calculation (no artificial minimum 100 pcs lock)
        Livewire::test(PromoRosterPabrik::class)
            ->set('wallLength', 1)
            ->set('wallHeight', 1)
            ->set('wasteMargin', 5)
            ->set('selectedSize', '20x20x10')
            ->assertSee('27 Pcs'); // ceil(1*1*25 * 1.05) = ceil(26.25) = 27

        // Test size 20x10x10 (50 pcs/m2)
        Livewire::test(PromoRosterPabrik::class)
            ->set('wallLength', 2)
            ->set('wallHeight', 1)
            ->set('wasteMargin', 0)
            ->set('selectedSize', '20x10x10')
            ->assertSee('100 Pcs'); // 2*1*50 = 100
    }

    public function test_meta_event_api_endpoint_handles_request(): void
    {
        Http::fake([
            'https://graph.facebook.com/*' => Http::response([
                'events_received' => 1,
                'fbtrace_id' => 'fake_trace_123',
            ], 200),
        ]);

        $response = $this->postJson('/api/meta-events', [
            'event_name' => 'Contact',
            'event_id' => 'evt_test_12345',
            'event_source_url' => 'http://localhost/promo/roster-pabrik',
            'custom_data' => [
                'content_name' => 'Hero WhatsApp CTA',
                'value' => 0,
            ],
            'user_data' => [
                'ph' => '08123456789',
                'ct' => 'Bekasi',
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'events_received' => 1,
        ]);
    }

    public function test_promo_page_database_and_dynamic_sections(): void
    {
        PromoPageSeeder::class;
        $this->seed(PromoPageSeeder::class);

        $page = PromoPage::where('slug', 'roster-pabrik')->first();
        $this->assertNotNull($page);
        $this->assertArrayHasKey('delivery_proof', $page->sections);
        $this->assertArrayHasKey('received_proof', $page->sections);

        $response = $this->get('/promo/roster-pabrik');
        $response->assertStatus(200);
        $response->assertSee('Bukti Muatan Armada Pengiriman Langsung Pabrik');
        $response->assertSee('Bukti Barang Tiba & Penurunan di Lokasi Pembeli');
    }

    public function test_promo_page_navbar_toggle_works(): void
    {
        PromoPage::create([
            'title' => 'Promo With Full Navbar',
            'slug' => 'promo-navbar-test',
            'is_active' => true,
            'sections' => [
                'show_navbar' => true,
            ],
        ]);

        $response = $this->get('/promo/promo-navbar-test');
        $response->assertStatus(200);
        // Full navbar contains glass-header
        $response->assertSee('glass-header');
    }
}
