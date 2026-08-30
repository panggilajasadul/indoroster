<?php

namespace App\Services;

use App\Models\Page;
use App\Models\SeoPage;

/**
 * Duplication Checker: deteksi duplikasi konten antar SeoPage.
 *
 * Membandingkan title, H1, opening, dan section content
 * untuk mencegah halaman copy-paste yang hanya ganti nama kota.
 */
class SeoDuplicationChecker
{
    /**
     * Threshold similarity untuk ditandai sebagai duplicat potensial.
     * 0.0 = tidak mirip, 1.0 = identik
     */
    private const SIMILARITY_THRESHOLD = 0.75;

    /**
     * Cek duplikasi halaman baru terhadap semua halaman existing.
     *
     * @param  int|null  $excludeId  ID halaman yang sedang diedit (exclude dari check)
     * @return array{is_unique: bool, duplicates: array, recommendation: string}
     */
    public function check(SeoPage $page, ?int $excludeId = null): array
    {
        $duplicates = [];

        // Query semua SEO pages yang bukan dirinya sendiri
        $existingPages = SeoPage::query()
            ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
            ->whereNotIn('status', ['archived', 'merged'])
            ->get();

        foreach ($existingPages as $existing) {
            $similarity = $this->calculateSimilarity($page, $existing);

            if ($similarity['overall'] >= self::SIMILARITY_THRESHOLD) {
                $duplicates[] = [
                    'page_id' => $existing->id,
                    'slug' => $existing->slug,
                    'title' => $existing->title,
                    'status' => $existing->status,
                    'similarity' => $similarity,
                ];
            }
        }

        // Cek juga terhadap CMS Pages
        $cmsPages = Page::where('is_active', true)->get();
        foreach ($cmsPages as $cmsPage) {
            $cmsSimilarity = $this->calculateCmsSimilarity($page, $cmsPage);
            if ($cmsSimilarity >= self::SIMILARITY_THRESHOLD) {
                $duplicates[] = [
                    'page_id' => 'cms-'.$cmsPage->id,
                    'slug' => $cmsPage->slug,
                    'title' => $cmsPage->title,
                    'status' => 'cms',
                    'similarity' => ['overall' => $cmsSimilarity, 'type' => 'cms_page'],
                ];
            }
        }

        $isUnique = empty($duplicates);
        $recommendation = $this->getRecommendation($duplicates);

        return [
            'is_unique' => $isUnique,
            'duplicates' => $duplicates,
            'recommendation' => $recommendation,
        ];
    }

    /**
     * Hitung similarity antara dua SeoPage.
     */
    private function calculateSimilarity(SeoPage $pageA, SeoPage $pageB): array
    {
        $scores = [];

        // 1. Title similarity
        $scores['title'] = $this->textSimilarity($pageA->title ?? '', $pageB->title ?? '');

        // 2. H1 similarity
        $scores['h1'] = $this->textSimilarity($pageA->h1 ?? '', $pageB->h1 ?? '');

        // 3. Opening text similarity
        $scores['opening'] = $this->textSimilarity(
            strip_tags($pageA->opening_text ?? ''),
            strip_tags($pageB->opening_text ?? '')
        );

        // 4. UVP similarity
        $scores['uvp'] = $this->textSimilarity(
            strip_tags($pageA->unique_value_proposition ?? ''),
            strip_tags($pageB->unique_value_proposition ?? '')
        );

        // 5. Meta description similarity
        $scores['meta'] = $this->textSimilarity(
            $pageA->meta_description ?? '',
            $pageB->meta_description ?? ''
        );

        // Overall: weighted average
        $scores['overall'] = (
            $scores['title'] * 0.20 +
            $scores['h1'] * 0.20 +
            $scores['opening'] * 0.30 +
            $scores['uvp'] * 0.20 +
            $scores['meta'] * 0.10
        );

        return $scores;
    }

    /**
     * Hitung similarity antara SeoPage dan CMS Page.
     */
    private function calculateCmsSimilarity(SeoPage $seoPage, Page $cmsPage): float
    {
        $titleSim = $this->textSimilarity($seoPage->title ?? '', $cmsPage->title ?? '');
        $metaSim = $this->textSimilarity(
            $seoPage->meta_description ?? '',
            $cmsPage->meta_description ?? ''
        );

        return $titleSim * 0.5 + $metaSim * 0.5;
    }

    /**
     * Hitung kesamaan dua teks (0.0 - 1.0).
     * Menggunakan similar_text PHP built-in.
     */
    private function textSimilarity(string $a, string $b): float
    {
        if (empty($a) || empty($b)) {
            return 0.0;
        }

        $a = mb_strtolower(trim($a));
        $b = mb_strtolower(trim($b));

        if ($a === $b) {
            return 1.0;
        }

        similar_text($a, $b, $percent);

        return round($percent / 100, 3);
    }

    /**
     * Berikan rekomendasi berdasarkan hasil duplikasi.
     */
    private function getRecommendation(array $duplicates): string
    {
        if (empty($duplicates)) {
            return 'UNIQUE — Halaman ini unik dan aman untuk dipublish.';
        }

        $maxSimilarity = max(array_column(
            array_map(fn ($d) => ['overall' => is_array($d['similarity']) ? $d['similarity']['overall'] : $d['similarity']], $duplicates),
            'overall'
        ));

        if ($maxSimilarity >= 0.90) {
            return 'DUPLICATE — Halaman ini sangat mirip dengan halaman existing. Pertimbangkan MERGE atau optimalkan halaman yang sudah ada.';
        }

        if ($maxSimilarity >= 0.80) {
            return 'NEEDS_REVIEW — Halaman ini cukup mirip dengan halaman lain. Pastikan ada perbedaan substantif sebelum publish.';
        }

        return 'NEEDS_REVIEW — Halaman ini memiliki kemiripan dengan beberapa halaman. Review UVP dan konten unik.';
    }
}
