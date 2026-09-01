<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\ExportPage;
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
use Spatie\Sitemap\SitemapIndex;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    /**
     * Master Sitemap Index (https://indoroster.com/sitemap.xml)
     */
    public function index(): Response
    {
        self::generate(rtrim(url('/'), '/'));

        return $this->serveXmlFile('sitemap.xml');
    }

    /**
     * Sub-sitemap: Halaman Statis, Pillar, CMS & B2B Hub
     */
    public function pages(): Response
    {
        return $this->serveXmlFile('sitemap-pages.xml');
    }

    /**
     * Sub-sitemap: Seluruh Halaman Use-Case Arsitektural (/aplikasi/*)
     */
    public function applications(): Response
    {
        return $this->serveXmlFile('sitemap-applications.xml');
    }

    /**
     * Sub-sitemap: Portal & 110 Negara Ekspor Global (/export/*)
     */
    public function exports(): Response
    {
        return $this->serveXmlFile('sitemap-exports.xml');
    }

    /**
     * Sub-sitemap: Kategori Katalog
     */
    public function categories(): Response
    {
        return $this->serveXmlFile('sitemap-categories.xml');
    }

    /**
     * Sub-sitemap: Seluruh Produk & Metadata Gambar
     */
    public function products(): Response
    {
        return $this->serveXmlFile('sitemap-products.xml');
    }

    /**
     * Sub-sitemap: Seluruh Halaman Wilayah & Klaster (/lokasi/*)
     */
    public function locations(): Response
    {
        return $this->serveXmlFile('sitemap-locations.xml');
    }

    /**
     * Sub-sitemap: Seluruh Artikel Edukasi & Panduan (/artikel/*)
     */
    public function articles(): Response
    {
        return $this->serveXmlFile('sitemap-articles.xml');
    }

    /**
     * Sub-sitemap: Seluruh Halaman SEO Page Factory (2,375 Landing Page)
     */
    public function seopages(): Response
    {
        return $this->serveXmlFile('sitemap-seopages.xml');
    }

    /**
     * Sub-sitemap: Galeri Proyek & Video Inspirasi
     */
    public function galleries(): Response
    {
        return $this->serveXmlFile('sitemap-galleries.xml');
    }

    /**
     * Stylesheet XSL untuk rendering sitemap yang cantik di browser
     */
    public function xsl(): Response
    {
        $filePath = public_path('sitemap.xsl');
        $content = file_exists($filePath) ? file_get_contents($filePath) : '';

        return response($content, 200, [
            'Content-Type' => 'text/xsl; charset=utf-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Helper untuk melayani berkas XML dengan header yang tepat
     */
    private function serveXmlFile(string $filename): Response
    {
        $filePath = public_path($filename);
        if (! file_exists($filePath)) {
            self::generate(rtrim(url('/'), '/'));
        }

        $xmlContent = file_exists($filePath) ? file_get_contents($filePath) : '';

        return response($xmlContent, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Generate master sitemap.xml dan seluruh sub-sitemap fisik ke public/*.xml.
     * Dipanggil dari: (1) HTTP request, (2) model observer hooks di AppServiceProvider, (3) Filament Admin.
     */
    public static function generate(?string $customBaseUrl = null): void
    {
        if (app()->runningUnitTests()) {
            return;
        }

        $rawUrl = $customBaseUrl ?? config('app.url', 'https://indoroster.com');
        if (empty($customBaseUrl) || str_contains($rawUrl, '127.0.0.1') || str_contains($rawUrl, 'localhost')) {
            $baseUrl = 'https://indoroster.com';
        } else {
            $baseUrl = rtrim($rawUrl, '/');
        }

        // ==========================================
        // 1. SITEMAP PAGES (Halaman Statis, Pillar & Hub)
        // ==========================================
        $sitemapPages = Sitemap::create();
        $sitemapPages->add(
            Url::create($baseUrl.'/')
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );

        $staticPages = [
            ['url' => '/katalog', 'priority' => 0.9, 'freq' => Url::CHANGE_FREQUENCY_DAILY],
            ['url' => '/artikel', 'priority' => 0.85, 'freq' => Url::CHANGE_FREQUENCY_DAILY],
            ['url' => '/untuk-kontraktor', 'priority' => 0.9, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => '/untuk-developer', 'priority' => 0.9, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => '/untuk-arsitek', 'priority' => 0.9, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => '/roster-beton-proyek', 'priority' => 0.9, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => '/kalkulator-roster', 'priority' => 0.85, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => '/lokasi', 'priority' => 0.85, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => '/video-inspirasi', 'priority' => 0.8, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => '/proses-produksi', 'priority' => 0.6, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => '/tentang-kami', 'priority' => 0.5, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => '/kontak', 'priority' => 0.5, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
        ];

        foreach ($staticPages as $sp) {
            $sitemapPages->add(
                Url::create($baseUrl.$sp['url'])
                    ->setLastModificationDate(Carbon::now())
                    ->setChangeFrequency($sp['freq'])
                    ->setPriority($sp['priority'])
            );
        }

        if (Schema::hasTable('pages')) {
            $pages = Page::where('is_active', true)
                ->whereNotIn('slug', [
                    'home', 'tentang-kami', 'kontak', 'katalog', 'gallery', 'lokasi',
                    'untuk-arsitek', 'untuk-kontraktor', 'untuk-developer', 'supplier-roster-beton', 'roster-beton-proyek',
                ])
                ->orderBy('updated_at', 'desc')
                ->get(['slug', 'updated_at']);

            foreach ($pages as $p) {
                $sitemapPages->add(
                    Url::create($baseUrl.'/page/'.trim($p->slug))
                        ->setLastModificationDate($p->updated_at ?? Carbon::now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                        ->setPriority(0.6)
                );
            }
        }

        self::saveSitemapFile($sitemapPages, 'sitemap-pages.xml', $baseUrl, 'Pages');

        // ==========================================
        // 2. SITEMAP APPLICATIONS (10 Use-Case Arsitektural)
        // ==========================================
        $sitemapApps = Sitemap::create();
        $sitemapApps->add(
            Url::create($baseUrl.'/aplikasi')
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.9)
        );

        $useCases = [
            'pagar-rumah' => 'Roster Beton Pagar Rumah Minimalis',
            'fasad-rumah' => 'Fasad Rumah Roster Beton Minimalis',
            'ventilasi-dinding' => 'Ventilasi Dinding & Lubang Angin Roster',
            'partisi-ruangan' => 'Partisi Ruangan & Sekat Interior Roster',
            'void-tangga' => 'Dinding Void Tangga Roster Beton',
            'fasad-cafe' => 'Fasad Cafe & Restoran Industrial Roster',
            'ruko' => 'Fasad Ruko & Commercial Building Roster',
            'perumahan-cluster' => 'Gerbang & Fasad Klaster Perumahan Roster',
            'gedung-komersial' => 'Fasad Gedung Hotel & Kantor Roster',
            'interior-cafe' => 'Interior Bar & Backdrop Cafe Roster',
        ];

        foreach ($useCases as $uSlug => $uTitle) {
            $appUrl = Url::create($baseUrl.'/aplikasi/'.$uSlug)
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.85);

            $appUrl->addImage(
                'https://res.cloudinary.com/indoroster/image/upload/v1765259970/7_blkgfx.jpg',
                $uTitle,
                'Purwakarta, Jawa Barat, Indonesia',
                $uTitle.' IndoRoster'
            );

            $sitemapApps->add($appUrl);
        }

        self::saveSitemapFile($sitemapApps, 'sitemap-applications.xml', $baseUrl, 'Applications');

        // ==========================================
        // 3. SITEMAP EXPORTS (113 Portal & Negara Ekspor Global)
        // ==========================================
        $sitemapExports = Sitemap::create();
        $sitemapExports->add(
            Url::create($baseUrl.'/export')
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );
        $sitemapExports->add(
            Url::create($baseUrl.'/export/catalog')
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.9)
        );
        $sitemapExports->add(
            Url::create($baseUrl.'/export/gallery')
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.9)
        );

        if (Schema::hasTable('export_pages')) {
            $exportPages = ExportPage::where('is_active', true)
                ->orderBy('updated_at', 'desc')
                ->get(['country_slug', 'updated_at']);

            foreach ($exportPages as $ep) {
                $sitemapExports->add(
                    Url::create($baseUrl.'/export/'.trim($ep->country_slug))
                        ->setLastModificationDate($ep->updated_at ?? Carbon::now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.85)
                );
            }
        }

        self::saveSitemapFile($sitemapExports, 'sitemap-exports.xml', $baseUrl, 'Exports');

        // ==========================================
        // 3. SITEMAP CATEGORIES (Kategori Katalog)
        // ==========================================
        $sitemapCategories = Sitemap::create();
        if (Schema::hasTable('categories')) {
            $categories = Category::where('is_active', true)
                ->with(['products' => function ($q) {
                    $q->where('is_active', true)
                        ->with(['media' => function ($mq) {
                            $mq->where('media_type', 'image')->orderBy('is_primary', 'desc');
                        }])
                        ->limit(10);
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

                $sitemapCategories->add($catUrl);
            }
        }

        self::saveSitemapFile($sitemapCategories, 'sitemap-categories.xml', $baseUrl, 'Categories');

        // ==========================================
        // 3. SITEMAP PRODUCTS (Semua Motif Produk Aktif)
        // ==========================================
        $sitemapProducts = Sitemap::create();
        $featuredProducts = collect();

        if (Schema::hasTable('products')) {
            $products = Product::where('is_active', true)
                ->with(['media' => function ($q) {
                    $q->where('media_type', 'image')->orderBy('is_primary', 'desc');
                }])
                ->orderBy('updated_at', 'desc')
                ->get(['id', 'name', 'slug', 'updated_at']);

            $featuredProducts = $products->take(5);

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

                $sitemapProducts->add($productUrl);
            }
        }

        self::saveSitemapFile($sitemapProducts, 'sitemap-products.xml', $baseUrl, 'Products');

        // ==========================================
        // 4. SITEMAP LOCATIONS (Halaman Wilayah & Klaster)
        // ==========================================
        $sitemapLocations = Sitemap::create();
        if (Schema::hasTable('seo_locations')) {
            $seoLocations = SeoLocation::where('seo_enabled', true)
                ->where('seo_score', '>=', 75)
                ->get();

            foreach ($seoLocations as $loc) {
                $locUrl = Url::create($baseUrl.'/lokasi/'.trim($loc->slug))
                    ->setLastModificationDate($loc->updated_at ?? Carbon::now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8);

                // Foto produk resmi IndoRoster untuk Google Images di pencarian lokal
                foreach ($featuredProducts->take(3) as $fp) {
                    $m = $fp->media->first();
                    if ($m && ! empty($m->media_url)) {
                        $imgUrl = str_starts_with($m->media_url, 'http') ? $m->media_url : $baseUrl.'/storage/'.ltrim($m->media_url, '/');
                        $locUrl->addImage($imgUrl, 'Roster Beton '.$loc->name.' - '.$fp->name, 'Purwakarta, Jawa Barat, Indonesia', 'Jual Roster Beton di '.$loc->name.' - '.$fp->name);
                    }
                }

                $sitemapLocations->add($locUrl);
            }
        }

        self::saveSitemapFile($sitemapLocations, 'sitemap-locations.xml', $baseUrl, 'Locations');

        // ==========================================
        // 5. SITEMAP ARTICLES (Artikel & Blog)
        // ==========================================
        $sitemapArticles = Sitemap::create();
        $existingArticleSlugs = [];

        if (Schema::hasTable('articles')) {
            $articles = Article::where('is_published', true)
                ->whereNotNull('published_at')
                ->where('published_at', '<=', Carbon::now())
                ->orderBy('updated_at', 'desc')
                ->get(['title', 'slug', 'thumbnail', 'thumbnail_alt', 'updated_at']);

            foreach ($articles as $article) {
                $cleanSlug = trim($article->slug);
                $existingArticleSlugs[$cleanSlug] = true;
                $articleUrl = Url::create($baseUrl.'/artikel/'.$cleanSlug)
                    ->setLastModificationDate($article->updated_at ?? Carbon::now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.75);

                if (! empty($article->thumbnail)) {
                    $imgUrl = str_starts_with($article->thumbnail, 'http') ? $article->thumbnail : $baseUrl.'/storage/'.ltrim($article->thumbnail, '/');
                    $articleUrl->addImage($imgUrl, $article->thumbnail_alt ?: $article->title, 'Purwakarta, Jawa Barat, Indonesia', $article->title);
                }

                $sitemapArticles->add($articleUrl);
            }
        }

        // Sertakan artikel hasil pemulihan dari crawled_articles.json
        $crawledJsonPath = database_path('crawled_articles.json');
        if (file_exists($crawledJsonPath)) {
            $crawledList = json_decode(file_get_contents($crawledJsonPath), true);
            if (is_array($crawledList)) {
                foreach ($crawledList as $cArt) {
                    $cSlug = trim($cArt['slug']);
                    if (! empty($cSlug) && ! isset($existingArticleSlugs[$cSlug])) {
                        $existingArticleSlugs[$cSlug] = true;
                        $cTitle = trim($cArt['title']) ?: 'Panduan Roster Beton Minimalis IndoRoster';
                        $cArticleUrl = Url::create($baseUrl.'/artikel/'.$cSlug)
                            ->setLastModificationDate(Carbon::now())
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                            ->setPriority(0.75);

                        $featuredImg = 'https://images.pexels.com/photos/3882638/pexels-photo-3882638.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940';
                        $cArticleUrl->addImage($featuredImg, $cTitle, 'Purwakarta, Jawa Barat, Indonesia', $cTitle);

                        $sitemapArticles->add($cArticleUrl);
                    }
                }
            }
        }

        self::saveSitemapFile($sitemapArticles, 'sitemap-articles.xml', $baseUrl, 'Articles');

        // ==========================================
        // 6. SITEMAP GALLERIES (Galeri & Video)
        // ==========================================
        $sitemapGalleries = Sitemap::create();
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
                $sitemapGalleries->add($singleGalleryUrl);
            }

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

                $sitemapGalleries->add($singleVideoUrl);
            }
        }

        self::saveSitemapFile($sitemapGalleries, 'sitemap-galleries.xml', $baseUrl, 'Galleries');

        // ==========================================
        // 7. SITEMAP SEO PAGES (2,375 Landing Page SEO Factory)
        // ==========================================
        $sitemapSeo = Sitemap::create();
        if (Schema::hasTable('seo_pages')) {
            $seoPages = SeoPage::where('status', 'published')
                ->where('noindex', false)
                ->orderBy('priority_score', 'desc')
                ->orderBy('updated_at', 'desc')
                ->get(['slug', 'priority_score', 'updated_at']);

            foreach ($seoPages as $sp) {
                $rawScore = ($sp->priority_score && $sp->priority_score > 0) ? $sp->priority_score : 85;
                $prio = round($rawScore / 100, 2);
                $prio = max(0.70, min(1.0, $prio));
                $sitemapSeo->add(
                    Url::create($baseUrl.'/'.trim($sp->slug))
                        ->setLastModificationDate($sp->updated_at ?? Carbon::now())
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority($prio)
                );
            }
        }

        self::saveSitemapFile($sitemapSeo, 'sitemap-seopages.xml', $baseUrl, 'SEO Pages');

        // ==========================================
        // 8. MASTER SITEMAP INDEX (sitemap.xml)
        // ==========================================
        $sitemapIndex = SitemapIndex::create();
        $sitemapIndex->add($baseUrl.'/sitemap-pages.xml');
        $sitemapIndex->add($baseUrl.'/sitemap-applications.xml');
        $sitemapIndex->add($baseUrl.'/sitemap-exports.xml');
        $sitemapIndex->add($baseUrl.'/sitemap-categories.xml');
        $sitemapIndex->add($baseUrl.'/sitemap-products.xml');
        $sitemapIndex->add($baseUrl.'/sitemap-locations.xml');
        $sitemapIndex->add($baseUrl.'/sitemap-articles.xml');
        $sitemapIndex->add($baseUrl.'/sitemap-galleries.xml');
        $sitemapIndex->add($baseUrl.'/sitemap-seopages.xml');

        $indexXml = $sitemapIndex->render();
        $indexXml = str_replace(['/generate_sitemap.php', '/test_sitemap.php'], '', $indexXml);

        if (! str_contains($indexXml, 'xml-stylesheet')) {
            $xslTag = '<?xml-stylesheet type="text/xsl" href="/sitemap.xsl?v=1.1"?>'."\n";
            $indexXml = preg_replace('/(<\?xml[^>]+>\s*)/', '$1'.$xslTag, $indexXml, 1);
        }

        $generatedAt = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s T');
        $indexXml = str_replace(
            '<?xml-stylesheet',
            "<!-- IndoRoster Master Sitemap Index | Generated: {$generatedAt} | Base: {$baseUrl} -->\n<?xml-stylesheet",
            $indexXml
        );

        file_put_contents(public_path('sitemap.xml'), $indexXml);
    }

    /**
     * Helper untuk menyimpan dan memformat berkas XML sitemap individu
     */
    private static function saveSitemapFile(Sitemap $sitemap, string $filename, string $baseUrl, string $label): void
    {
        $xmlContent = $sitemap->render();
        $xmlContent = str_replace(['/generate_sitemap.php', '/test_sitemap.php'], '', $xmlContent);

        if (! str_contains($xmlContent, 'xml-stylesheet')) {
            $xslTag = '<?xml-stylesheet type="text/xsl" href="/sitemap.xsl?v=1.1"?>'."\n";
            $xmlContent = preg_replace('/(<\?xml[^>]+>\s*)/', '$1'.$xslTag, $xmlContent, 1);
        }

        $generatedAt = Carbon::now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s T');
        $urlTotal = substr_count($xmlContent, '<url>');
        $xmlContent = str_replace(
            '<?xml-stylesheet',
            "<!-- IndoRoster {$label} Sitemap | Generated: {$generatedAt} | Total URLs: {$urlTotal} | Base: {$baseUrl} -->\n<?xml-stylesheet",
            $xmlContent
        );

        file_put_contents(public_path($filename), $xmlContent);
    }
}
