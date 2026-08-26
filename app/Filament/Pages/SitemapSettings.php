<?php

namespace App\Filament\Pages;

use App\Http\Controllers\SitemapController;
use App\Models\Article;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\Product;
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

        $urlCount = 0;
        $imageCount = 0;
        if ($exists) {
            $content = File::get($sitemapPath);
            $urlCount = substr_count($content, '<url>');
            $imageCount = substr_count($content, '<image:image>') + substr_count($content, '<image:loc>');
        }

        $activeProducts = Product::where('is_active', true)->count();
        $activeCategories = Category::where('is_active', true)->count();
        $publishedArticles = class_exists(Article::class) ? Article::where('is_published', true)->count() : 0;
        $activeGalleries = class_exists(Gallery::class) ? Gallery::where('is_active', true)->count() : 0;
        $activePages = class_exists(Page::class) ? Page::where('is_active', true)->count() : 0;

        $sitemapUrl = url('/sitemap.xml');

        return [
            'exists' => $exists,
            'last_modified' => $lastModified,
            'last_modified_formatted' => $lastModified ? $lastModified->translatedFormat('d F Y, H:i:s').' WIB' : 'Belum pernah dibuat',
            'last_modified_relative' => $lastModified ? $lastModified->diffForHumans() : '-',
            'file_size' => $fileSize,
            'url_count' => $urlCount,
            'image_count' => $imageCount,
            'active_products' => $activeProducts,
            'active_categories' => $activeCategories,
            'published_articles' => $publishedArticles,
            'active_galleries' => $activeGalleries,
            'active_pages' => $activePages,
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
                        SitemapController::generate();
                        Notification::make()
                            ->title('Sitemap Berhasil Diperbarui!')
                            ->body('File sitemap.xml telah dibuat ulang dengan data produk, kategori, artikel, dan galeri terbaru.')
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
