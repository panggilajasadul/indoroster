<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\Product;
use App\Models\SeoLocation;
use App\Models\SeoPage;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index(): Response
    {
        // Selalu regenerate dengan base URL dari HTTP request yang sedang berjalan
        self::generate(rtrim(url('/'), '/'));

        $sitemapPath = public_path('sitemap.xml');
        $xmlContent = file_exists($sitemapPath) ? file_get_contents($sitemapPath) : '';

        return response($xmlContent, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Generate sitemap.xml ke public/sitemap.xml.
     * Dipanggil dari: (1) HTTP request via index(), (2) model observer hooks di AppServiceProvider.
     * Selalu gunakan APP_URL dari config sebagai base — bisa di-override via parameter.
     */
    public static function generate(?string $customBaseUrl = null): void
    {
        if (app()->runningUnitTests()) {
            return;
        }

        // Gunakan domain resmi https://indoroster.com jika tidak ada custom base URL atau jika berjalan di local
        $rawUrl = $customBaseUrl ?? config('app.url', 'https://indoroster.com');
        if (empty($customBaseUrl) || str_contains($rawUrl, '127.0.0.1') || str_contains($rawUrl, 'localhost')) {
            $baseUrl = 'https://indoroster.com';
        } else {
            $baseUrl = rtrim($rawUrl, '/');
        }

        $sitemap = Sitemap::create();

        // 1. Homepage
        $sitemap->add(
            Url::create($baseUrl.'/')
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );

        // 2. Halaman Katalog Produk (tanpa filter) + Foto Produk Unggulan
        $catalogUrl = Url::create($baseUrl.'/katalog')
            ->setLastModificationDate(Carbon::now())
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(0.9);

        $featuredProducts = Product::where('is_active', true)
            ->with(['media' => function ($q) {
                $q->where('media_type', 'image')->orderBy('is_primary', 'desc');
            }])
            ->limit(10)
            ->get();

        foreach ($featuredProducts as $fp) {
            $m = $fp->media->first();
            if ($m && ! empty($m->media_url)) {
                $imgUrl = str_starts_with($m->media_url, 'http') ? $m->media_url : $baseUrl.'/storage/'.ltrim($m->media_url, '/');
                $catalogUrl->addImage($imgUrl, $m->alt_text ?: $fp->name, 'Purwakarta, Jawa Barat, Indonesia', 'Katalog Roster IndoRoster - '.$fp->name);
            }
        }
        $sitemap->add($catalogUrl);

        // 3. Katalog per Kategori — menggunakan clean URL /katalog/{slug} + Foto Produk Kategori
        $categories = Category::where('is_active', true)
            ->with(['products' => function ($q) {
                $q->where('is_active', true)
                    ->with(['media' => function ($mq) {
                        $mq->where('media_type', 'image')->orderBy('is_primary', 'desc');
                    }])
                    ->limit(15);
            }])
            ->get();

        foreach ($categories as $category) {
            $catUrl = Url::create($baseUrl.'/katalog/'.trim($category->slug))
                ->setLastModificationDate($category->updated_at ?? Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8);

            foreach ($category->products as $cp) {
                $cm = $cp->media->first();
                if ($cm && ! empty($cm->media_url)) {
                    $imgUrl = str_starts_with($cm->media_url, 'http') ? $cm->media_url : $baseUrl.'/storage/'.ltrim($cm->media_url, '/');
                    $catUrl->addImage($imgUrl, $cm->alt_text ?: $cp->name, 'Purwakarta, Jawa Barat, Indonesia', 'Katalog '.$category->name.' - '.$cp->name);
                }
            }

            $sitemap->add($catUrl);
        }

        // 4. Semua halaman produk aktif + Metadata Google Images
        $products = Product::where('is_active', true)
            ->with(['media' => function ($q) {
                $q->where('media_type', 'image')->orderBy('is_primary', 'desc');
            }])
            ->orderBy('updated_at', 'desc')
            ->get(['id', 'name', 'slug', 'updated_at']);

        foreach ($products as $product) {
            $productUrl = Url::create($baseUrl.'/produk/'.trim($product->slug))
                ->setLastModificationDate($product->updated_at ?? Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.85);

            foreach ($product->media as $m) {
                if (! empty($m->media_url)) {
                    $imgUrl = str_starts_with($m->media_url, 'http') ? $m->media_url : $baseUrl.'/storage/'.ltrim($m->media_url, '/');
                    $productUrl->addImage($imgUrl, $m->alt_text ?: $product->name, 'Purwakarta, Jawa Barat, Indonesia', $product->name);
                }
            }

            $sitemap->add($productUrl);
        }

        // 5. Halaman Statis & B2B Hub & Tools
        $staticPages = [
            ['url' => '/artikel', 'priority' => 0.85, 'freq' => Url::CHANGE_FREQUENCY_DAILY],
            ['url' => '/untuk-kontraktor', 'priority' => 0.9, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => '/untuk-developer', 'priority' => 0.9, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => '/untuk-arsitek', 'priority' => 0.9, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => '/supplier-roster-beton', 'priority' => 0.9, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => '/roster-beton-proyek', 'priority' => 0.9, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => '/kalkulator-roster', 'priority' => 0.85, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => '/lokasi', 'priority' => 0.85, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => '/video-inspirasi', 'priority' => 0.8, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => '/proses-produksi', 'priority' => 0.6, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => '/tentang-kami', 'priority' => 0.5, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => '/kontak', 'priority' => 0.5, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
        ];

        foreach ($staticPages as $page) {
            $sitemap->add(
                Url::create($baseUrl.$page['url'])
                    ->setLastModificationDate(Carbon::now())
                    ->setChangeFrequency($page['freq'])
                    ->setPriority($page['priority'])
            );
        }

        // 5b. Halaman Lokasi SEO Aktif (Quality Score >= 75)
        if (Schema::hasTable('seo_locations')) {
            $seoLocations = SeoLocation::where('seo_enabled', true)
                ->where('seo_score', '>=', 75)
                ->get();

            foreach ($seoLocations as $loc) {
                $sitemap->add(
                    Url::create($baseUrl.'/lokasi/'.trim($loc->slug))
                        ->setLastModificationDate($loc->updated_at ?? Carbon::now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.8)
                );
            }
        }

        // 6. Halaman Galeri Proyek Mandiri (/gallery/{slug}) + Metadata Foto Google Images
        if (Schema::hasTable('galleries')) {
            $photoGalleries = Gallery::where('is_active', true)
                ->where('category', '!=', 'video-inspirasi')
                ->with(['media' => function ($q) {
                    $q->where('media_type', 'image');
                }])
                ->orderBy('updated_at', 'desc')
                ->get();

            foreach ($photoGalleries as $g) {
                $itemSlug = $g->slug ?: Str::slug($g->title);
                $singleGalleryUrl = Url::create($baseUrl.'/gallery/'.$itemSlug)
                    ->setLastModificationDate($g->updated_at ?? Carbon::now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8);

                foreach ($g->media as $gm) {
                    if (! empty($gm->media_url)) {
                        $imgUrl = str_starts_with($gm->media_url, 'http') ? $gm->media_url : $baseUrl.'/storage/'.ltrim($gm->media_url, '/');
                        $singleGalleryUrl->addImage($imgUrl, $g->title ?: 'Inspirasi Roster Beton', $g->location ?: 'Purwakarta, Jawa Barat', $g->title ?: 'Galeri Proyek IndoRoster');
                    }
                }
                $sitemap->add($singleGalleryUrl);
            }

            // 7. Video Inspirasi Mandiri (/video-inspirasi/{slug})
            $videoGalleries = Gallery::where('is_active', true)
                ->where('category', 'video-inspirasi')
                ->orderBy('updated_at', 'desc')
                ->get();

            foreach ($videoGalleries as $vg) {
                $itemSlug = $vg->slug ?: Str::slug($vg->title);
                $singleVideoUrl = Url::create($baseUrl.'/video-inspirasi/'.$itemSlug)
                    ->setLastModificationDate($vg->updated_at ?? Carbon::now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8);

                $sitemap->add($singleVideoUrl);
            }
        }

        // 6. Artikel & Blog Postings + Thumbnail Gambar
        if (Schema::hasTable('articles')) {
            $articles = Article::where('is_published', true)
                ->whereNotNull('published_at')
                ->where('published_at', '<=', Carbon::now())
                ->orderBy('updated_at', 'desc')
                ->get(['title', 'slug', 'thumbnail', 'thumbnail_alt', 'updated_at']);

            foreach ($articles as $article) {
                $articleUrl = Url::create($baseUrl.'/artikel/'.trim($article->slug))
                    ->setLastModificationDate($article->updated_at ?? Carbon::now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.75);

                if (! empty($article->thumbnail)) {
                    $imgUrl = str_starts_with($article->thumbnail, 'http') ? $article->thumbnail : $baseUrl.'/storage/'.ltrim($article->thumbnail, '/');
                    $articleUrl->addImage($imgUrl, $article->thumbnail_alt ?: $article->title, 'Purwakarta, Jawa Barat, Indonesia', $article->title);
                }

                $sitemap->add($articleUrl);
            }
        }

        // 7. Dynamic CMS Pages (/page/{slug})
        if (Schema::hasTable('pages')) {
            $pages = Page::where('is_active', true)
                ->whereNotIn('slug', [
                    'home', 'tentang-kami', 'kontak', 'katalog', 'gallery', 'lokasi',
                    'untuk-arsitek', 'untuk-kontraktor', 'untuk-developer', 'supplier-roster-beton', 'roster-beton-proyek',
                ])
                ->orderBy('updated_at', 'desc')
                ->get(['slug', 'updated_at']);

            foreach ($pages as $page) {
                $sitemap->add(
                    Url::create($baseUrl.'/page/'.trim($page->slug))
                        ->setLastModificationDate($page->updated_at ?? Carbon::now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority(0.6)
                );
            }
        }

        // 8. SEO Page Factory Commercial Landing Pages (/{slug})
        if (Schema::hasTable('seo_pages')) {
            $seoPages = SeoPage::where('status', 'published')
                ->where('noindex', false)
                ->orderBy('updated_at', 'desc')
                ->get(['slug', 'page_type', 'updated_at']);

            foreach ($seoPages as $seoPage) {
                $priority = match ($seoPage->page_type) {
                    'pillar' => 0.9,
                    'buyer', 'project' => 0.85,
                    'usecase', 'product_landing' => 0.8,
                    'location' => 0.75,
                    default => 0.7,
                };

                $sitemap->add(
                    Url::create($baseUrl.'/'.trim($seoPage->slug))
                        ->setLastModificationDate($seoPage->updated_at ?? Carbon::now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority($priority)
                );
            }
        }

        $xmlContent = $sitemap->render();

        // Clean up script name prefix if generated via browser direct scripts
        $xmlContent = str_replace(['/generate_sitemap.php', '/test_sitemap.php'], '', $xmlContent);

        // Inject XSL stylesheet for modern visual browser display
        // Use root-relative /sitemap.xsl so browser never encounters CORS issues on any port/domain
        if (! str_contains($xmlContent, 'xml-stylesheet')) {
            $xslTag = '<?xml-stylesheet type="text/xsl" href="/sitemap.xsl"?>'."\n";
            $xmlContent = preg_replace('/(<\?xml[^>]+>\s*)/', '$1'.$xslTag, $xmlContent, 1);
        }

        // Simpan tanggal & URL count ke komentar di file untuk debugging
        $generatedAt = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s T');
        $urlTotal = substr_count($xmlContent, '<url>');
        $xmlContent = str_replace(
            '<?xml-stylesheet',
            "<!-- IndoRoster Sitemap | Generated: {$generatedAt} | Total URLs: {$urlTotal} | Base: {$baseUrl} -->\n<?xml-stylesheet",
            $xmlContent
        );

        file_put_contents(public_path('sitemap.xml'), $xmlContent);
    }
}
