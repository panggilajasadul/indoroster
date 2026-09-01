<?php

namespace App\Filament\Pages;

use App\Http\Controllers\SitemapController;
use App\Models\Article;
use App\Models\Category;
use App\Models\ExportPage;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\Product;
use App\Models\SeoLocation;
use App\Models\SeoPage;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page as FilamentPage;
use Illuminate\Support\Facades\File;

class SitemapSettings extends FilamentPage
{
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Sitemap & SEO';

    protected static ?string $title = 'Manajemen Sitemap & Indeks SEO';

    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.sitemap-settings';

    public function getSitemapStats(): array
    {
        $sitemapPath = public_path('sitemap.xml');
        $exists = File::exists($sitemapPath);
        $lastModified = $exists ? Carbon::createFromTimestamp(File::lastModified($sitemapPath))->timezone('Asia/Jakarta') : null;
        $fileSize = $exists ? round(File::size($sitemapPath) / 1024, 2) : 0;

        $subSitemaps = [
            [
                'name' => 'Halaman Statis, Pillar & B2B Hub',
                'filename' => 'sitemap-pages.xml',
                'url' => url('/sitemap-pages.xml'),
                'icon' => 'heroicon-o-document-duplicate',
                'badge' => 'Prioritas 0.85 – 1.0',
            ],
            [
                'name' => '113 Portal & Negara Ekspor Global',
                'filename' => 'sitemap-exports.xml',
                'url' => url('/sitemap-exports.xml'),
                'icon' => 'heroicon-o-globe-alt',
                'badge' => 'Prioritas 0.85 – 1.0',
            ],
            [
                'name' => 'Katalog Produk & Foto Spesifikasi',
                'filename' => 'sitemap-products.xml',
                'url' => url('/sitemap-products.xml'),
                'icon' => 'heroicon-o-cube',
                'badge' => 'Prioritas 0.85',
            ],
            [
                'name' => 'Kategori Produk Roster',
                'filename' => 'sitemap-categories.xml',
                'url' => url('/sitemap-categories.xml'),
                'icon' => 'heroicon-o-tag',
                'badge' => 'Prioritas 0.80',
            ],
            [
                'name' => 'Halaman SEO Lokasi, Kota & Kawasan',
                'filename' => 'sitemap-locations.xml',
                'url' => url('/sitemap-locations.xml'),
                'icon' => 'heroicon-o-map-pin',
                'badge' => 'Prioritas 0.80',
            ],
            [
                'name' => 'Artikel & Blog Panduan Arsitektur',
                'filename' => 'sitemap-articles.xml',
                'url' => url('/sitemap-articles.xml'),
                'icon' => 'heroicon-o-newspaper',
                'badge' => 'Prioritas 0.75',
            ],
            [
                'name' => 'Galeri Proyek & Video Inspirasi',
                'filename' => 'sitemap-galleries.xml',
                'url' => url('/sitemap-galleries.xml'),
                'icon' => 'heroicon-o-photo',
                'badge' => 'Prioritas 0.80',
            ],
            [
                'name' => '2.375 Halaman SEO Page Factory (Pillar & Usecase)',
                'filename' => 'sitemap-seopages.xml',
                'url' => url('/sitemap-seopages.xml'),
                'icon' => 'heroicon-o-sparkles',
                'badge' => 'Prioritas 0.80 – 1.0',
            ],
        ];

        $totalUrls = 0;
        $totalImages = 0;
        $subSitemapList = [];

        foreach ($subSitemaps as $sub) {
            $path = public_path($sub['filename']);
            $subExists = File::exists($path);
            $subUrls = 0;
            $subImages = 0;
            $subMod = null;

            if ($subExists) {
                $content = File::get($path);
                $subUrls = substr_count($content, '<url>');
                $subImages = substr_count($content, '<image:image>') + substr_count($content, '<image:loc>');
                $subMod = Carbon::createFromTimestamp(File::lastModified($path))->timezone('Asia/Jakarta');
                $totalUrls += $subUrls;
                $totalImages += $subImages;
            }

            $subSitemapList[] = array_merge($sub, [
                'exists' => $subExists,
                'url_count' => $subUrls,
                'image_count' => $subImages,
                'last_modified' => $subMod ? $subMod->format('H:i').' WIB' : '-',
            ]);
        }

        // Fallback jika membaca sitemap.xml biasa tanpa sub-sitemap
        if ($totalUrls === 0 && $exists) {
            $content = File::get($sitemapPath);
            $totalUrls = substr_count($content, '<url>');
            $totalImages = substr_count($content, '<image:image>') + substr_count($content, '<image:loc>');
        }

        $activeProducts = Product::where('is_active', true)->count();
        $activeCategories = Category::where('is_active', true)->count();
        $publishedArticles = class_exists(Article::class) ? Article::where('is_published', true)->count() : 0;
        $activeGalleries = class_exists(Gallery::class) ? Gallery::where('is_active', true)->count() : 0;
        $activePages = class_exists(Page::class) ? Page::where('is_active', true)->count() : 0;
        $activeExportPages = class_exists(ExportPage::class) ? ExportPage::where('is_active', true)->count() : 0;
        $activeSeoPages = class_exists(SeoPage::class) ? SeoPage::where('status', 'published')->where('noindex', false)->count() : 0;
        $activeSeoLocations = class_exists(SeoLocation::class)
            ? SeoLocation::where('seo_enabled', true)->where('seo_score', '>=', 75)->count()
            : 0;

        $sitemapUrl = url('/sitemap.xml');

        return [
            'exists' => $exists,
            'last_modified' => $lastModified,
            'last_modified_formatted' => $lastModified ? $lastModified->translatedFormat('d F Y, H:i:s').' WIB' : 'Belum pernah dibuat',
            'last_modified_relative' => $lastModified ? $lastModified->diffForHumans() : '-',
            'file_size' => $fileSize,
            'url_count' => $totalUrls,
            'image_count' => $totalImages,
            'active_products' => $activeProducts,
            'active_categories' => $activeCategories,
            'published_articles' => $publishedArticles,
            'active_galleries' => $activeGalleries,
            'active_pages' => $activePages,
            'active_export_pages' => $activeExportPages,
            'active_seo_pages' => $activeSeoPages,
            'active_seo_locations' => $activeSeoLocations,
            'sub_sitemaps' => $subSitemapList,
            'sitemap_url' => $sitemapUrl,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate_now')
                ->label('Perbarui Sitemap Sekarang')
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                ->action(function () {
                    try {
                        SitemapController::generate(rtrim(url('/'), '/'));
                        Notification::make()
                            ->title('Sitemap Berhasil Diperbarui!')
                            ->body('File sitemap.xml telah dibuat ulang dengan 182+ data produk, kategori, lokasi SEO, artikel, dan galeri terbaru.')
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Gagal Memperbarui Sitemap')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('view_sitemap')
                ->label('Buka sitemap.xml')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->url(url('/sitemap.xml'), true),
        ];
    }

    public function generateSitemap(): void
    {
        try {
            SitemapController::generate();
            Notification::make()
                ->title('Sitemap Berhasil Diperbarui!')
                ->body('File sitemap.xml telah diperbarui secara instan.')
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->title('Gagal Memperbarui Sitemap')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
