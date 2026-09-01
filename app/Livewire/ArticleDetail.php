<?php

namespace App\Livewire;

use App\Models\Article;
use App\Models\Product;
use App\Services\LegacyUrlRedirectService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

class ArticleDetail extends Component
{
    public Article $article;

    public function mount(string $slug)
    {
        $cleanSlug = trim($slug);
        $article = Article::with('category')
            ->where('slug', $cleanSlug)
            ->where('is_published', true)
            ->first();

        if (! $article) {
            $jsonPath = database_path('crawled_articles.json');
            if (file_exists($jsonPath)) {
                $rawJson = json_decode(file_get_contents($jsonPath), true);
                if (is_array($rawJson)) {
                    foreach ($rawJson as $item) {
                        if (trim($item['slug']) === $cleanSlug) {
                            $plainText = strip_tags($item['content']);
                            $article = new Article([
                                'title' => $item['title'],
                                'slug' => $cleanSlug,
                                'thumbnail' => 'https://images.pexels.com/photos/3882638/pexels-photo-3882638.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
                                'thumbnail_alt' => $item['title'],
                                'excerpt' => Str::limit($plainText, 180),
                                'content' => $item['content'],
                                'author_name' => 'Tim Desain & Arsitektur IndoRoster',
                                'views_count' => rand(150, 450),
                                'reading_time' => max(2, (int) ceil(str_word_count($plainText) / 180)),
                                'is_published' => true,
                                'published_at' => now()->subDays(rand(2, 30)),
                                'meta_title' => $item['title'].' | IndoRoster',
                                'meta_description' => $item['meta_description'] ?: Str::limit($plainText, 155),
                                'tags' => ['Roster Beton', 'IndoRoster', 'Tips Bangunan', 'Fasad Rumah', 'Arsitektur'],
                            ]);
                            $article->id = abs(crc32($cleanSlug));
                            break;
                        }
                    }
                }
            }
        }

        if (! $article) {
            // Smart Fallback 1: Cek apakah ada artikel lain dengan kota/kata kunci yang sama
            if (preg_match('/(?:di-|wilayah-|ke-|untuk-)([a-z0-9\-]+)$/i', $cleanSlug, $m)) {
                $cityKey = preg_replace('/-\d+$/', '', str_replace(['-bantul', '-garut', '-istimewa'], '', $m[1]));
                $similarArt = Article::where('is_published', true)->where('slug', 'like', "%{$cityKey}%")->first();
                if ($similarArt) {
                    return $this->redirect(route('article.detail', $similarArt->slug), navigate: true);
                }
            }

            // Smart Fallback 2: Cek Legacy Url Redirect
            $legacyUrl = LegacyUrlRedirectService::resolveRedirect($cleanSlug);
            if ($legacyUrl) {
                return $this->redirect($legacyUrl, navigate: false);
            }

            // Smart Fallback 3: Redirect ke Hub Artikel
            return $this->redirect(route('article.index'), navigate: true);
        }

        $this->article = $article;

        // Increment view count safely once per session per article if persisted
        if ($this->article->exists) {
            $sessionKey = 'viewed_article_'.$this->article->id;
            if (! session()->has($sessionKey)) {
                $this->article->increment('views_count');
                session()->put($sessionKey, true);
            }
        }
    }

    public function render()
    {
        $relatedArticles = Article::published()
            ->where('id', '!=', $this->article->id)
            ->when($this->article->article_category_id, function ($q) {
                $q->where('article_category_id', $this->article->article_category_id);
            })
            ->latest('published_at')
            ->take(3)
            ->get();

        // If not enough related in same category, fill with latest articles
        if ($relatedArticles->count() < 3) {
            $fillers = Article::published()
                ->where('id', '!=', $this->article->id)
                ->whereNotIn('id', $relatedArticles->pluck('id'))
                ->latest('published_at')
                ->take(3 - $relatedArticles->count())
                ->get();
            $relatedArticles = $relatedArticles->concat($fillers);
        }

        // Featured Products for Cross-Silo Linking
        $featuredProducts = Product::where('is_active', true)
            ->with(['media', 'category'])
            ->latest()
            ->take(4)
            ->get();

        // Injeksi foto produk asli dari database secara dinamis ke dalam konten artikel
        $this->article->content = $this->injectRealProductPhotos($this->article->content);

        $metaTitle = $this->article->meta_title ?: ($this->article->title.' - IndoRoster');
        $metaDescription = $this->article->meta_description ?: ($this->article->excerpt ?: Str::limit(strip_tags($this->article->content), 155));
        $metaKeywords = $this->article->meta_keywords ?: 'roster beton, fasad minimalis, roster plered, arsitektur tropis, indoroster';

        return view('livewire.article-detail', [
            'relatedArticles' => $relatedArticles,
            'featuredProducts' => $featuredProducts,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'ogImage' => $this->article->thumbnail_url,
            'ogType' => 'article',
            'keywords' => $metaKeywords,
            'canonicalOverride' => route('article.detail', $this->article->slug),
        ]);
    }

    /**
     * Injeksi foto produk asli dari tabel product_media secara dinamis saat render.
     * Hanya mengganti src <img> yang berisi placeholder (pexels / localhost) dengan
     * foto asli dari database — AMAN, tidak mengubah struktur HTML sama sekali.
     */
    private function injectRealProductPhotos(string $content): string
    {
        // Bangun map: slug -> URL foto primary (1 query untuk semua produk)
        static $photoMap = null;
        if ($photoMap === null) {
            $photoMap = DB::table('product_media as pm')
                ->join('products as p', 'p.id', '=', 'pm.product_id')
                ->where('p.is_active', true)
                ->where('pm.is_primary', true)
                ->select('p.slug', 'pm.media_url')
                ->pluck('pm.media_url', 'p.slug')
                ->toArray();
        }

        if (empty($photoMap)) {
            return $content;
        }

        // Pecah konten per blok kartu produk (outer white card)
        $parts = preg_split('/(?=<div class="my-10 p-6 sm:p-8 bg-slate-50)/', $content);

        $rebuilt = '';
        foreach ($parts as $part) {
            if (str_contains($part, '/produk/') && preg_match('|href="[^"]*?/produk/([^"?#/]+)"|i', $part, $m)) {
                $slug = rtrim($m[1], '/');
                if (!empty($photoMap[$slug])) {
                    $realImg = $photoMap[$slug];
                    // Ganti hanya src img yang berisi pexels atau localhost — struktur HTML tidak disentuh
                    $part = preg_replace_callback(
                        '/<img([^>]*?)src="([^"]*?)"([^>]*?)>/i',
                        function ($matches) use ($realImg) {
                            $src = $matches[2];
                            if (str_contains($src, 'pexels') || str_contains($src, '127.0.0.1') || empty($src)) {
                                return '<img' . $matches[1] . 'src="' . $realImg . '"' . $matches[3] . '>';
                            }
                            return $matches[0];
                        },
                        $part,
                        1
                    );
                }
            }
            $rebuilt .= $part;
        }

        return $rebuilt;
    }
}
