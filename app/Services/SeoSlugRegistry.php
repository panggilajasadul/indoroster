<?php

namespace App\Services;

use App\Models\Page;
use App\Models\Product;
use App\Models\SeoPage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * Slug Registry: memastikan slug SEO page tidak bertabrakan dengan
 * route statis, product slug, CMS page slug, atau SEO page lain.
 */
class SeoSlugRegistry
{
    /**
     * Slug yang sudah dipakai oleh route statis di web.php.
     * Diambil dari audit codebase — harus selalu diupdate jika ada route baru.
     */
    private const RESERVED_SLUGS = [
        'katalog', 'produk', 'keranjang', 'artikel', 'lacak-pesanan',
        'login', 'register', 'forgot-password', 'reset-password',
        'checkout', 'member', 'gallery', 'video-inspirasi',
        'proses-produksi', 'tentang-kami', 'kontak', 'untuk-kontraktor',
        'untuk-developer', 'untuk-arsitek', 'supplier-roster-beton',
        'roster-beton-grosir', 'roster-beton-proyek', 'kalkulator-roster',
        'lokasi', 'page', 'halaman', 'print', 'admin', 'courier',
        'api', 'sitemap.xml', 'email', 'logout', 'home',
        'preview-email',
    ];

    /**
     * Validasi apakah slug tersedia untuk digunakan oleh SeoPage.
     *
     * @param  int|null  $excludePageId  ID SeoPage yang sedang diedit (exclude dari duplicate check)
     * @return array{valid: bool, reason: string|null}
     */
    public function validate(string $slug, ?int $excludePageId = null): array
    {
        $slug = Str::slug($slug);

        if (empty($slug)) {
            return ['valid' => false, 'reason' => 'Slug tidak boleh kosong.'];
        }

        // 1. Cek reserved routes
        if (in_array($slug, self::RESERVED_SLUGS, true)) {
            return ['valid' => false, 'reason' => "Slug '{$slug}' sudah digunakan oleh route statis website."];
        }

        // 2. Cek CMS pages
        $pageExists = Page::where('slug', $slug)->exists();
        if ($pageExists) {
            return ['valid' => false, 'reason' => "Slug '{$slug}' sudah digunakan oleh halaman CMS."];
        }

        // 3. Cek product slugs (produk menggunakan /produk/{slug} tapi fallback /{slug} bisa conflict)
        $productExists = Product::where('slug', $slug)->exists();
        if ($productExists) {
            return ['valid' => false, 'reason' => "Slug '{$slug}' bertabrakan dengan slug produk."];
        }

        // 4. Cek SEO pages lain
        $seoPageQuery = SeoPage::where('slug', $slug);
        if ($excludePageId) {
            $seoPageQuery->where('id', '!=', $excludePageId);
        }
        if ($seoPageQuery->exists()) {
            return ['valid' => false, 'reason' => "Slug '{$slug}' sudah digunakan oleh halaman SEO lain."];
        }

        return ['valid' => true, 'reason' => null];
    }

    /**
     * Suggest slug alternatif jika slug yang diminta tidak tersedia.
     */
    public function suggest(string $baseSlug): string
    {
        $slug = Str::slug($baseSlug);
        $counter = 2;

        while (! $this->validate($slug)['valid']) {
            $slug = Str::slug($baseSlug).'-'.$counter;
            $counter++;

            if ($counter > 20) {
                break; // safety
            }
        }

        return $slug;
    }

    /**
     * Daftar semua slug yang sudah terpakai (untuk referensi).
     */
    public function getAllUsedSlugs(): array
    {
        $used = collect(self::RESERVED_SLUGS)
            ->merge(Page::pluck('slug'))
            ->merge(SeoPage::pluck('slug'))
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        return $used;
    }
}
