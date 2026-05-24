<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Product;
use App\Models\Category;
use App\Models\Page;
use Carbon\Carbon;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $sitemap = Sitemap::create();

        // 1. Homepage
        $sitemap->add(
            Url::create('/')
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(1.0)
        );

        // 2. Halaman Katalog Produk (tanpa filter)
        $sitemap->add(
            Url::create('/katalog')
                ->setLastModificationDate(Carbon::now())
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.9)
        );

        // 3. Katalog per Kategori
        $categories = Category::where('is_active', true)->get();
        foreach ($categories as $category) {
            $sitemap->add(
                Url::create('/katalog?category=' . $category->slug)
                    ->setLastModificationDate($category->updated_at ?? Carbon::now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.8)
            );
        }

        // 4. Semua halaman produk aktif
        $products = Product::where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        foreach ($products as $product) {
            $sitemap->add(
                Url::create('/produk/' . $product->slug)
                    ->setLastModificationDate($product->updated_at ?? Carbon::now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority(0.85)
            );
        }

        // 5. Halaman Statis
        $staticPages = [
            ['url' => '/gallery', 'priority' => 0.7, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => '/video-inspirasi', 'priority' => 0.7, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => '/proses-produksi', 'priority' => 0.6, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => '/tentang-kami', 'priority' => 0.5, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => '/kontak', 'priority' => 0.5, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
        ];

        foreach ($staticPages as $page) {
            $sitemap->add(
                Url::create($page['url'])
                    ->setLastModificationDate(Carbon::now())
                    ->setChangeFrequency($page['freq'])
                    ->setPriority($page['priority'])
            );
        }

        // 6. Dynamic Pages (excluding home page)
        $pages = Page::where('is_active', true)
            ->where('slug', '!=', 'home')
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);

        foreach ($pages as $page) {
            $sitemap->add(
                Url::create('/halaman/' . $page->slug)
                    ->setLastModificationDate($page->updated_at ?? Carbon::now())
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.6)
            );
        }

        return response($sitemap->render(), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
