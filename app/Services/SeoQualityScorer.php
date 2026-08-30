<?php

namespace App\Services;

use App\Models\SeoPage;

/**
 * Quality Gate: menghitung skor kualitas 12 kriteria untuk SeoPage.
 *
 * Setiap kriteria dinilai 0-5, kemudian dikalikan bobot untuk mendapatkan skor 0-100.
 * Minimum publish: quality_score >= 60 DAN semua kriteria kritis >= 4.
 */
class SeoQualityScorer
{
    /**
     * Bobot per kriteria (total = 100).
     */
    private const WEIGHTS = [
        'search_intent_match' => 10,
        'buyer_relevance' => 10,
        'product_relevance' => 8,
        'unique_information' => 10,
        'evidence' => 8,
        'local_relevance' => 6,
        'commercial_value' => 8,
        'conversion_clarity' => 10,
        'ux_readability' => 6,
        'internal_linking' => 6,
        'originality' => 10,
        'factual_accuracy' => 8,
    ];

    /**
     * Kriteria yang wajib >= 4 untuk publish.
     */
    private const CRITICAL_CRITERIA = [
        'search_intent_match',
        'buyer_relevance',
        'unique_information',
        'factual_accuracy',
        'conversion_clarity',
    ];

    /**
     * Hitung quality score dan detail per kriteria.
     *
     * @return array{score: int, details: array, is_publishable: bool, issues: array}
     */
    public function score(SeoPage $page): array
    {
        $details = [];
        $issues = [];

        // A. Search Intent Match (0-5)
        $details['search_intent_match'] = $this->scoreSearchIntentMatch($page);
        if ($details['search_intent_match'] < 4) {
            $issues[] = 'Search intent belum jelas terjawab di opening text dan H1.';
        }

        // B. Buyer Relevance (0-5)
        $details['buyer_relevance'] = $this->scoreBuyerRelevance($page);
        if ($details['buyer_relevance'] < 4) {
            $issues[] = 'Target buyer belum spesifik atau tidak terefleksi dalam konten.';
        }

        // C. Product Relevance (0-5)
        $details['product_relevance'] = $this->scoreProductRelevance($page);

        // D. Unique Information (0-5)
        $details['unique_information'] = $this->scoreUniqueInformation($page);
        if ($details['unique_information'] < 4) {
            $issues[] = 'Unique Value Proposition dan unique evidence masih kurang.';
        }

        // E. Evidence (0-5)
        $details['evidence'] = $this->scoreEvidence($page);

        // F. Local Relevance (0-5)
        $details['local_relevance'] = $this->scoreLocalRelevance($page);

        // G. Commercial Value (0-5)
        $details['commercial_value'] = $this->scoreCommercialValue($page);

        // H. Conversion Clarity (0-5)
        $details['conversion_clarity'] = $this->scoreConversionClarity($page);
        if ($details['conversion_clarity'] < 4) {
            $issues[] = 'CTA dan proses pemesanan belum jelas.';
        }

        // I. UX/Readability (0-5)
        $details['ux_readability'] = $this->scoreUxReadability($page);

        // J. Internal Linking (0-5)
        $details['internal_linking'] = $this->scoreInternalLinking($page);

        // K. Originality (0-5)
        $details['originality'] = $this->scoreOriginality($page);

        // L. Factual Accuracy (0-5)
        $details['factual_accuracy'] = $this->scoreFactualAccuracy($page);
        if ($details['factual_accuracy'] < 4) {
            $issues[] = 'Ada klaim yang tidak didukung data atau potensi informasi fiktif.';
        }

        // Hitung weighted score (0-100)
        $totalScore = 0;
        foreach ($details as $criteria => $value) {
            $weight = self::WEIGHTS[$criteria] ?? 0;
            // Normalisasi: (value/5) * weight
            $totalScore += ($value / 5) * $weight;
        }
        $totalScore = (int) round($totalScore);

        // Cek publishability
        $isPublishable = $totalScore >= 60;
        foreach (self::CRITICAL_CRITERIA as $criteria) {
            if (($details[$criteria] ?? 0) < 4) {
                $isPublishable = false;

                break;
            }
        }

        return [
            'score' => $totalScore,
            'details' => $details,
            'is_publishable' => $isPublishable,
            'issues' => $issues,
        ];
    }

    /**
     * Hitung dan simpan skor ke model SeoPage.
     */
    public function scoreAndSave(SeoPage $page): SeoPage
    {
        $result = $this->score($page);
        $page->update([
            'quality_score' => $result['score'],
            'quality_details' => $result['details'],
        ]);

        return $page;
    }

    // ──────────────────────────────────────────────
    // Kriteria Individual
    // ──────────────────────────────────────────────

    private function scoreSearchIntentMatch(SeoPage $page): int
    {
        $score = 0;

        // Harus punya primary keyword
        if (! empty($page->primary_keyword)) {
            $score++;
        }

        // Harus punya H1 yang mengandung kata kunci
        if (! empty($page->h1) && $this->containsKeywordFragment($page->h1, $page->primary_keyword)) {
            $score++;
        }

        // Harus punya opening text yang substantif
        if (! empty($page->opening_text) && strlen(strip_tags($page->opening_text)) > 100) {
            $score++;
        }

        // Harus punya search intent yang jelas
        if (! empty($page->search_intent)) {
            $score++;
        }

        // Harus punya meta description yang relevan
        if (! empty($page->meta_description) && strlen($page->meta_description) >= 80) {
            $score++;
        }

        return min($score, 5);
    }

    private function scoreBuyerRelevance(SeoPage $page): int
    {
        $score = 0;

        // Buyer type harus ditentukan
        if (! empty($page->buyer_type)) {
            $score += 2;
        }

        // Opening text harus menyebut buyer
        if (! empty($page->opening_text) && ! empty($page->buyer_type)) {
            $buyerTerms = $this->getBuyerTerms($page->buyer_type);
            foreach ($buyerTerms as $term) {
                if (stripos($page->opening_text, $term) !== false) {
                    $score++;

                    break;
                }
            }
        }

        // UVP harus ada
        if (! empty($page->unique_value_proposition) && strlen($page->unique_value_proposition) > 50) {
            $score++;
        }

        // CTA harus relevan untuk buyer
        if (! empty($page->cta_type) && ! empty($page->cta_text)) {
            $score++;
        }

        return min($score, 5);
    }

    private function scoreProductRelevance(SeoPage $page): int
    {
        $score = 0;

        // Harus punya product matching rule atau product_ids atau pivot products
        if (! empty($page->product_matching_rule) || ! empty($page->product_ids)) {
            $score += 2;
        }

        // Cek pivot products
        if ($page->relationLoaded('products') ? $page->products->isNotEmpty() : $page->products()->exists()) {
            $score += 2;
        }

        // Harus punya use_case yang jelas
        if (! empty($page->use_case) || ! empty($page->project_type)) {
            $score++;
        }

        return min($score, 5);
    }

    private function scoreUniqueInformation(SeoPage $page): int
    {
        $score = 0;

        // UVP wajib ada dan substantif
        if (! empty($page->unique_value_proposition)) {
            $len = strlen(strip_tags($page->unique_value_proposition));
            if ($len > 200) {
                $score += 3;
            } elseif ($len > 100) {
                $score += 2;
            } elseif ($len > 30) {
                $score++;
            }
        }

        // Unique angle/evidence
        if (! empty($page->unique_evidence)) {
            $score++;
        }
        if (! empty($page->unique_angle)) {
            $score++;
        }

        return min($score, 5);
    }

    private function scoreEvidence(SeoPage $page): int
    {
        $score = 1; // baseline: produk ada di website

        // Punya evidence tertulis
        if (! empty($page->unique_evidence) && strlen(strip_tags($page->unique_evidence)) > 50) {
            $score += 2;
        }

        // Punya sections yang substantif
        $sectionCount = $page->relationLoaded('sections') ? $page->sections->count() : $page->sections()->count();
        if ($sectionCount >= 4) {
            $score += 2;
        } elseif ($sectionCount >= 2) {
            $score++;
        }

        return min($score, 5);
    }

    private function scoreLocalRelevance(SeoPage $page): int
    {
        // Hanya relevan untuk location pages
        if (empty($page->location_name) && empty($page->seo_location_id)) {
            return 3; // N/A, skor netral
        }

        $score = 0;

        // Punya location name
        if (! empty($page->location_name)) {
            $score++;
        }

        // Link ke SeoLocation
        if (! empty($page->seo_location_id)) {
            $score++;
        }

        // Konten menyebut lokasi di opening
        if (! empty($page->opening_text) && ! empty($page->location_name)) {
            if (stripos($page->opening_text, $page->location_name) !== false) {
                $score++;
            }
        }

        // Ada info pengiriman (section type shipping)
        $hasShipping = $page->relationLoaded('sections')
            ? $page->sections->where('section_type', 'shipping')->isNotEmpty()
            : $page->sections()->where('section_type', 'shipping')->exists();
        if ($hasShipping) {
            $score += 2;
        }

        return min($score, 5);
    }

    private function scoreCommercialValue(SeoPage $page): int
    {
        $score = 0;

        // Intent BOFU paling bernilai
        if ($page->search_intent === 'bofu') {
            $score += 2;
        } elseif ($page->search_intent === 'mofu') {
            $score++;
        }

        // Harus punya CTA
        if (! empty($page->cta_type)) {
            $score++;
        }

        // Harus punya produk yang terhubung
        if (! empty($page->product_matching_rule) || ! empty($page->product_ids)) {
            $score++;
        }

        // Harus punya conversion path yang jelas
        if (! empty($page->cta_text)) {
            $score++;
        }

        return min($score, 5);
    }

    private function scoreConversionClarity(SeoPage $page): int
    {
        $score = 0;

        // CTA type harus ada
        if (! empty($page->cta_type)) {
            $score++;
        }

        // CTA text harus ada dan spesifik (bukan generik)
        if (! empty($page->cta_text) && strlen($page->cta_text) > 15) {
            $score += 2;
        }

        // Harus punya section proses pemesanan
        $hasProcess = $page->relationLoaded('sections')
            ? $page->sections->where('section_type', 'process')->isNotEmpty()
            : $page->sections()->where('section_type', 'process')->exists();
        if ($hasProcess) {
            $score++;
        }

        // WA message harus custom (bukan default)
        if (! empty($page->cta_wa_message)) {
            $score++;
        }

        return min($score, 5);
    }

    private function scoreUxReadability(SeoPage $page): int
    {
        $score = 0;

        // Title dan H1 harus berbeda (tidak copy-paste)
        if (! empty($page->title) && ! empty($page->h1) && $page->title !== $page->h1) {
            $score++;
        }

        // Opening text harus cukup panjang tapi tidak terlalu panjang
        $openingLen = strlen(strip_tags($page->opening_text ?? ''));
        if ($openingLen >= 100 && $openingLen <= 600) {
            $score += 2;
        } elseif ($openingLen > 0) {
            $score++;
        }

        // Sections harus terurut dan punya heading
        $sectionCount = $page->relationLoaded('sections') ? $page->sections->count() : $page->sections()->count();
        if ($sectionCount >= 3) {
            $score += 2;
        } elseif ($sectionCount >= 1) {
            $score++;
        }

        return min($score, 5);
    }

    private function scoreInternalLinking(SeoPage $page): int
    {
        $score = 0;

        // Punya parent page
        if (! empty($page->parent_page_id)) {
            $score += 2;
        }

        // Punya related pages
        if (! empty($page->related_page_ids) && count($page->related_page_ids) >= 2) {
            $score += 2;
        }

        // Punya child pages
        $childCount = $page->relationLoaded('childPages') ? $page->childPages->count() : $page->childPages()->count();
        if ($childCount > 0) {
            $score++;
        }

        return min($score, 5);
    }

    private function scoreOriginality(SeoPage $page): int
    {
        // Baseline: cek konten length vs generik
        $score = 2; // default acceptable

        // Opening yang panjang dan substantif
        $openingLen = strlen(strip_tags($page->opening_text ?? ''));
        if ($openingLen > 200) {
            $score++;
        }

        // Unique angle ada
        if (! empty($page->unique_angle)) {
            $score++;
        }

        // Sections punya unique_angle
        $sectionsWithAngle = $page->relationLoaded('sections')
            ? $page->sections->filter(fn ($s) => ! empty($s->unique_angle))->count()
            : $page->sections()->whereNotNull('unique_angle')->count();
        if ($sectionsWithAngle >= 2) {
            $score++;
        }

        return min($score, 5);
    }

    private function scoreFactualAccuracy(SeoPage $page): int
    {
        $score = 3; // baseline: tidak mengarang

        // Punya evidence
        if (! empty($page->unique_evidence)) {
            $score++;
        }

        // Produk yang ditampilkan ada di database
        if (! empty($page->product_ids) || ! empty($page->product_matching_rule)) {
            $score++;
        }

        return min($score, 5);
    }

    // ──────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────

    /**
     * Cek apakah teks mengandung fragmen kata kunci.
     */
    private function containsKeywordFragment(string $text, string $keyword): bool
    {
        $words = explode(' ', strtolower($keyword));
        $textLower = strtolower($text);

        $matchCount = 0;
        foreach ($words as $word) {
            if (strlen($word) >= 3 && str_contains($textLower, $word)) {
                $matchCount++;
            }
        }

        // Minimal 50% kata dari keyword harus ada di teks
        $significantWords = count(array_filter($words, fn ($w) => strlen($w) >= 3));

        return $significantWords > 0 && ($matchCount / $significantWords) >= 0.5;
    }

    /**
     * Daftar istilah buyer berdasarkan type.
     */
    private function getBuyerTerms(string $buyerType): array
    {
        return match ($buyerType) {
            'kontraktor' => ['kontraktor', 'pembangun', 'konstruksi', 'proyek'],
            'developer' => ['developer', 'pengembang', 'perumahan', 'cluster'],
            'pemborong' => ['pemborong', 'tukang', 'mandor', 'pelaksana'],
            'arsitek' => ['arsitek', 'desainer', 'arsitektur', 'desain'],
            'procurement' => ['procurement', 'pengadaan', 'vendor', 'tender'],
            'owner' => ['pemilik', 'owner', 'rumah', 'renovasi'],
            default => ['proyek', 'kebutuhan', 'pembeli'],
        };
    }
}
