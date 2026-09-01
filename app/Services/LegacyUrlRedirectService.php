<?php

namespace App\Services;

use App\Models\Article;
use App\Models\SeoLocation;
use Illuminate\Support\Facades\Schema;

class LegacyUrlRedirectService
{
    /**
     * Cari URL tujuan redirect 301 berdasarkan slug legacy dari website lama.
     * Mengembalikan URL tujuan (string) jika ada kecocokan, atau null jika bukan URL legacy.
     */
    public static function resolveRedirect(string $slug): ?string
    {
        $cleanSlug = strtolower(trim($slug, '/ '));

        if (empty($cleanSlug)) {
            return null;
        }

        // 1. Blog index legacy
        if ($cleanSlug === 'blog') {
            return url('/artikel');
        }

        // 2. Pola URL Lokasi Legacy (cth: jual-roster-beton-di-jakarta-selatan, roster-beton-di-bandung, dll)
        $locationPatterns = [
            '/^(?:jual|pusat|supplier|pabrik|sentra|butuh)-roster-beton-di-(.+)$/',
            '/^(?:fasad|pagar|interior|sekat-ruang|ventilasi-udara|taman)-roster-beton-di-(.+)$/',
            '/^roster-beton-di-(.+)$/',
            '/^roster-beton-ventilasi-di-wilayah-(.+)$/',
            '/^pusat-roster-beton-(.+)$/',
        ];

        foreach ($locationPatterns as $pattern) {
            if (preg_match($pattern, $cleanSlug, $matches)) {
                $cityRaw = $matches[1];
                // Bersihkan suffix angka seperti -2 atau nama daerah
                $cityClean = preg_replace('/-\d+$/', '', $cityRaw);
                $cityClean = str_replace(['-bantul', '-garut', '-istimewa'], '', $cityClean);

                $targetLocation = self::findMatchingLocation($cityClean);
                if ($targetLocation) {
                    return route('location.detail', $targetLocation->slug);
                }

                // Fallback jika tidak menemukan kota spesifik: arahkan ke hub lokasi
                return route('location.index');
            }
        }

        // 3. Cek apakah cocok langsung dengan artikel yang ada di IndoRoster (Database atau Crawled JSON)
        if (Schema::hasTable('articles')) {
            $article = Article::where('slug', $cleanSlug)->where('is_published', true)->first();
            if ($article) {
                return route('article.detail', $article->slug);
            }
        }

        $jsonPath = database_path('crawled_articles.json');
        if (file_exists($jsonPath)) {
            $rawJson = json_decode(file_get_contents($jsonPath), true);
            if (is_array($rawJson)) {
                foreach ($rawJson as $item) {
                    if (trim($item['slug']) === $cleanSlug) {
                        return route('article.detail', $cleanSlug);
                    }
                }
            }
        }

        // 4. Pola artikel edukasi/tips umum dari web lama
        $knownArticleKeywords = [
            'mengecat', 'anti-tampias', 'solusi-rumah-panas', 'menghitung-roster',
            'tips-memilih', 'motif-petir', 'jalusi-nako', 'pola-acak', 'japandi',
            'partisi-ruangan', 'sirkulasi-udara', 'klasik-vs-minimalis', 'transformasi-ruang-tamu',
            'ventilasi-udara', 'shadow-pattern', 'pola-fasad', 'meredam-kebisingan',
            'pencahayaan-alami', 'fasad-simpel', 'brutalisme', 'gaya-kontemporer',
            'pasir-merapi', 'pembatas', 'motif-batik', 'ruang-laundry', 'estetika-modern',
            'sekat-ruang', 'pagar-roster', 'keunggulan', 'perawatan', 'hitung-kebutuhan',
        ];

        if (Schema::hasTable('articles')) {
            foreach ($knownArticleKeywords as $keyword) {
                if (str_contains($cleanSlug, $keyword)) {
                    $matchedArticle = Article::where('is_published', true)
                        ->where(function ($q) use ($keyword) {
                            $q->where('slug', 'like', "%{$keyword}%")
                                ->orWhere('title', 'like', "%{$keyword}%");
                        })
                        ->first();

                    if ($matchedArticle) {
                        return route('article.detail', $matchedArticle->slug);
                    }
                }
            }
        }

        // Fallback artikel default
        return route('article.index');
    }

    /**
     * Cari SeoLocation berdasarkan nama kota atau potongan slug kota.
     */
    protected static function findMatchingLocation(string $citySlug): ?SeoLocation
    {
        if (! Schema::hasTable('seo_locations')) {
            return null;
        }

        $citySlug = trim($citySlug);

        // 1. Exact slug atau variasi slug standar
        $candidates = [
            $citySlug,
            'roster-beton-minimalis-'.$citySlug,
            str_replace('roster-beton-minimalis-', '', $citySlug),
        ];

        $location = SeoLocation::whereIn('slug', $candidates)
            ->where('seo_enabled', true)
            ->first();

        if ($location) {
            return $location;
        }

        // 2. Pencocokan variasi arah/subdistrik (cth: jakarta-selatan -> jakarta)
        $strippedSlug = preg_replace('/-(selatan|barat|timur|utara|pusat|kota|kabupaten|kab|raya)$/i', '', str_replace('roster-beton-minimalis-', '', $citySlug));
        $location = SeoLocation::whereIn('slug', [
            $strippedSlug,
            'roster-beton-minimalis-'.$strippedSlug,
        ])
            ->where('seo_enabled', true)
            ->first();

        if ($location) {
            return $location;
        }

        // 3. Pencarian berdasarkan nama kota (like) atau target_districts
        $cityName = str_replace('-', ' ', $citySlug);
        $location = SeoLocation::where('seo_enabled', true)
            ->where(function ($q) use ($cityName, $citySlug) {
                $q->where('name', 'like', "%{$cityName}%")
                    ->orWhere('slug', 'like', "%{$citySlug}%")
                    ->orWhere('target_districts', 'like', "%{$cityName}%");
            })
            ->first();

        return $location;
    }
}
