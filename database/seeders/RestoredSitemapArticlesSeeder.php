<?php

namespace Database\Seeders;

use App\Http\Controllers\SitemapController;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RestoredSitemapArticlesSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('crawled_articles.json');
        if (! file_exists($jsonPath)) {
            $this->command->error('File crawled_articles.json tidak ditemukan.');

            return;
        }

        $articles = json_decode(file_get_contents($jsonPath), true);
        $this->command->info('Memproses '.count($articles).' artikel hasil crawl ke database...');

        // Ambil produk IndoRoster aktif untuk Card Produk
        $products = Product::where('is_active', true)->get();
        if ($products->isEmpty()) {
            $products = Product::all();
        }
        $productCount = $products->count();

        // Kategori Artikel
        $catEdukasi = ArticleCategory::firstOrCreate(['slug' => 'panduan-tips'], [
            'name' => 'Panduan & Tips Roster',
            'description' => 'Panduan teknis, tips pemasangan, perawatan, dan inspirasi desain roster beton.',
            'is_active' => true,
        ]);

        $catInspirasi = ArticleCategory::firstOrCreate(['slug' => 'inspirasi-desain'], [
            'name' => 'Inspirasi Desain',
            'description' => 'Ide arsitektural fasad dan partisi roster beton.',
            'is_active' => true,
        ]);

        $inserted = 0;

        Article::withoutEvents(function () use ($articles, $products, $productCount, $catEdukasi, $catInspirasi, &$inserted) {
            foreach ($articles as $art) {
                $slug = trim($art['slug']);
                $title = trim($art['title']);
                $rawContent = $art['content'];
                $metaDesc = trim($art['meta_description']);

                if (empty($title) || empty($slug) || empty($rawContent)) {
                    continue;
                }

                // Bersihkan sisa branding lama
                $cleanContent = str_ireplace(
                    ['rosterbetonminimalis.com', 'rosterbetonminimalis', 'artomoro'],
                    ['indoroster.com', 'IndoRoster', 'IndoRoster'],
                    $rawContent
                );

                // GANTI SEMUA FOTO LAMA DENGAN CARD PRODUK RESMI INDOROSTER
                $cleanContent = preg_replace_callback('/<figure[^>]*>.*?<\/figure>|<img[^>]*>/is', function () use ($products, $productCount) {
                    if ($productCount === 0) {
                        return '';
                    }
                    $p = $products->random();
                    $pName = htmlspecialchars($p->name);
                    $pImg = $p->primary_image_url ?: 'https://images.pexels.com/photos/3882638/pexels-photo-3882638.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940';
                    $pPrice = $p->price ? 'Rp '.number_format($p->price, 0, ',', '.') : 'Harga Pabrik Langsung';
                    $waText = urlencode("Halo IndoRoster, saya tertarik dengan produk {$p->name} dari artikel website.");
                    $pUrl = route('product.detail', $p->slug);

                    return <<<HTML
<div class="my-8 p-6 bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 text-white rounded-2xl shadow-xl border border-slate-700 not-prose">
    <div class="flex flex-col sm:flex-row items-center gap-6">
        <div class="w-36 h-36 shrink-0 rounded-xl overflow-hidden shadow-lg border-2 border-terra-500 bg-slate-800">
            <img src="{$pImg}" alt="{$pName}" class="w-full h-full object-cover" loading="lazy" />
        </div>
        <div class="flex-1 text-center sm:text-left">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold uppercase tracking-wider bg-terra-500/20 text-terra-400 border border-terra-500/30 rounded-full mb-2">
                <span class="w-2 h-2 rounded-full bg-terra-400"></span>
                Rekomendasi Produk Resmi IndoRoster
            </div>
            <h4 class="text-xl font-bold text-white mb-1">{$pName}</h4>
            <p class="text-sm text-slate-300 mb-4">Mutu Beton Padat K-200 • Presisi Siku 90° • Tahan Cuaca & Anti-Lumut • <strong class="text-terra-400">{$pPrice}</strong></p>
            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                <a href="https://wa.me/6281389709847?text={$waText}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2.5 bg-terra-500 hover:bg-terra-600 text-white text-sm font-semibold rounded-xl shadow-md transition-all">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    Pesan via WhatsApp
                </a>
                <a href="{$pUrl}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 text-sm font-semibold rounded-xl border border-slate-600 transition-all">
                    Lihat Spesifikasi Lengkap
                </a>
            </div>
        </div>
    </div>
</div>
HTML;
                }, $cleanContent);

                // Tentukan kategori
                $catId = (str_contains($slug, 'inspirasi') || str_contains($slug, 'transformasi') || str_contains($slug, 'fasad') || str_contains($slug, 'desain'))
                    ? $catInspirasi->id
                    : $catEdukasi->id;

                // Generate Excerpt
                $plainText = strip_tags($cleanContent);
                $excerpt = Str::limit(trim(preg_replace('/\s+/', ' ', $plainText)), 180);

                // Thumbnail default yang relevan & estetik
                $thumbnail = 'https://images.pexels.com/photos/3882638/pexels-photo-3882638.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940';

                Article::updateOrCreate(['slug' => $slug], [
                    'article_category_id' => $catId,
                    'title' => $title,
                    'thumbnail' => $thumbnail,
                    'thumbnail_alt' => $title,
                    'excerpt' => $excerpt,
                    'content' => $cleanContent,
                    'author_name' => 'Tim Teknis & Desain IndoRoster',
                    'is_published' => true,
                    'is_featured' => (str_contains($slug, 'cara-mengecat') || str_contains($slug, 'anti-tampias') || str_contains($slug, 'solusi-rumah-panas')),
                    'published_at' => now()->subDays(rand(1, 60)),
                    'meta_title' => $title.' | IndoRoster',
                    'meta_description' => $metaDesc ?: $excerpt,
                    'reading_time' => max(2, (int) ceil(str_word_count($plainText) / 180)),
                    'tags' => ['Roster Beton', 'IndoRoster', 'Tips Bangunan', 'Fasad Rumah', 'Arsitektur'],
                ]);

                $inserted++;
            }
        });

        // Regenerate sitemap once at the end
        try {
            (new SitemapController)->generate();
        } catch (\Throwable $e) {
            // silent
        }

        $this->command->info("Sukses menambahkan {$inserted} artikel narasi lengkap ke database IndoRoster!");
    }
}
