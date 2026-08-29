<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleAndPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_dynamic_page_route_renders_successfully(): void
    {
        $page = Page::updateOrCreate(
            ['slug' => 'panduan-garansi-roster-test'],
            [
                'title' => 'Panduan Garansi Roster Test',
                'content' => [
                    [
                        'type' => 'rich_text',
                        'data' => [
                            'title' => 'Panduan Garansi Roster Test',
                            'content' => '<p>Ketentuan garansi layanan resmi IndoRoster.</p>',
                        ],
                    ],
                ],
                'is_active' => true,
            ]
        );

        $response = $this->get('/page/'.$page->slug);
        $response->assertStatus(200);
        $response->assertSee('Panduan Garansi Roster Test');
        $response->assertSee('Ketentuan garansi layanan resmi IndoRoster');
    }

    public function test_high_trust_blocks_render_cleanly(): void
    {
        $page = Page::updateOrCreate(
            ['slug' => 'test-trust-blocks'],
            [
                'title' => 'Test Trust Blocks',
                'content' => [
                    ['type' => 'buyer_protection', 'data' => []],
                    ['type' => 'shipment_proof', 'data' => []],
                    ['type' => 'buying_steps', 'data' => []],
                    ['type' => 'quality_comparison', 'data' => []],
                    ['type' => 'roster_calculator', 'data' => []],
                    ['type' => 'before_after', 'data' => []],
                    ['type' => 'client_logos', 'data' => []],
                    ['type' => 'download_catalog', 'data' => []],
                    ['type' => 'map_location', 'data' => []],
                    ['type' => 'pricing_packages', 'data' => []],
                    ['type' => 'technical_specs', 'data' => []],
                ],
                'is_active' => true,
            ]
        );

        $response = $this->get('/page/'.$page->slug);
        $response->assertStatus(200);
        $response->assertSee('Garansi Pecah Ganti Baru');
        $response->assertSee('Bukti Pengiriman');
        $response->assertSee('Langkah Mudah & Aman');
        $response->assertSee('Mengapa Roster Kami Berbeda');
        $response->assertSee('Kalkulator Kebutuhan Roster');
        $response->assertSee('Lihat Perbedaan Sebelum & Sesudah');
        $response->assertSee('Telah Digunakan di Berbagai Proyek');
        $response->assertSee('Download Buku Katalog');
        $response->assertSee('Kunjungi Workshop & Pabrik');
        $response->assertSee('Pilihan Paket Bundling Fasad');
        $response->assertSee('Data Teknis & Presisi Dimensi');
    }

    public function test_legacy_halaman_route_redirects_to_page_route(): void
    {
        $response = $this->get('/halaman/syarat-dan-ketentuan');
        $response->assertStatus(301);
        $response->assertRedirect('/page/syarat-dan-ketentuan');
    }

    public function test_about_us_page_renders_dynamically_from_database(): void
    {
        $response = $this->get('/tentang-kami');
        $response->assertStatus(200);
        $response->assertSee('IndoRoster');
    }

    public function test_contact_page_renders_dynamically_from_database(): void
    {
        $response = $this->get('/kontak');
        $response->assertStatus(200);
        $response->assertSee('IndoRoster');
    }

    public function test_production_process_page_renders_dynamically_from_database(): void
    {
        $response = $this->get('/proses-produksi');
        $response->assertStatus(200);
        $response->assertSee('IndoRoster');
    }

    public function test_article_list_renders_and_filters_published_articles(): void
    {
        $category = ArticleCategory::create([
            'name' => 'Kategori Uji Fasad',
            'slug' => 'kategori-uji-fasad',
            'is_active' => true,
        ]);

        $publishedArticle = Article::create([
            'article_category_id' => $category->id,
            'title' => 'Desain Fasad Roster Unik Modern',
            'slug' => 'desain-fasad-roster-unik-modern',
            'excerpt' => 'Panduan singkat desain fasad.',
            'content' => '<p>Konten lengkap artikel arsitektur.</p>',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $draftArticle = Article::create([
            'article_category_id' => $category->id,
            'title' => 'Draft Artikel Rahasia Belum Rilis',
            'slug' => 'draft-artikel-rahasia-belum-rilis',
            'content' => '<p>Konten rahasia.</p>',
            'is_published' => false,
        ]);

        $response = $this->get('/artikel');
        $response->assertStatus(200);
        $response->assertSee('Desain Fasad Roster Unik Modern');
        $response->assertDontSee('Draft Artikel Rahasia Belum Rilis');
    }

    public function test_article_detail_page_increments_views_and_renders_content(): void
    {
        $article = Article::create([
            'title' => 'Cara Hitung Kebutuhan Roster 20x20',
            'slug' => 'cara-hitung-kebutuhan-roster-20x20',
            'excerpt' => 'Rumus hitung 25 pcs per m2.',
            'content' => '<p>Langkah 1: ukur luas dinding.</p>',
            'is_published' => true,
            'published_at' => now(),
            'views_count' => 0,
        ]);

        $response = $this->get('/artikel/'.$article->slug);
        $response->assertStatus(200);
        $response->assertSee('Cara Hitung Kebutuhan Roster 20x20');
        $response->assertSee('Langkah 1: ukur luas dinding.');

        $this->assertEquals(1, $article->fresh()->views_count);
    }
}
