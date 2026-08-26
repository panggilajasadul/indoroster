<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ArticleAiGeneratorService
{
    /**
     * Get list of preset articles from indoroster-blog-skills/articles.
     */
    public static function getPresets(): array
    {
        $dir = base_path('indoroster-blog-skills/articles');
        if (! File::isDirectory($dir)) {
            return [];
        }

        $files = File::files($dir);
        $presets = [];

        foreach ($files as $file) {
            if ($file->getExtension() !== 'md') {
                continue;
            }

            $content = File::get($file->getPathname());
            $title = self::extractMeta($content, 'Judul (H1)') ?? self::extractHeaderTitle($content);
            $slug = self::extractMeta($content, 'Slug') ?? Str::slug($title);
            $excerpt = self::extractSection($content, 'Ringkasan Singkat (Excerpt)');

            $presets[$file->getFilename()] = [
                'filename' => $file->getFilename(),
                'title' => $title,
                'slug' => $slug,
                'excerpt' => $excerpt,
                'file_path' => $file->getPathname(),
            ];
        }

        return $presets;
    }

    /**
     * Generate / Import an article from preset filename.
     */
    public static function generateFromPreset(string $filename): Article
    {
        $filePath = base_path("indoroster-blog-skills/articles/{$filename}");
        if (! File::exists($filePath)) {
            throw new \Exception("File preset {$filename} tidak ditemukan.");
        }

        $content = File::get($filePath);

        $title = self::extractMeta($content, 'Judul (H1)') ?? self::extractHeaderTitle($content);
        $slug = self::extractMeta($content, 'Slug') ?? Str::slug($title);
        $metaTitle = self::extractMeta($content, 'Meta Title') ?? $title;
        $metaDesc = self::extractMeta($content, 'Meta Description') ?? '';
        $metaKeywords = self::extractMeta($content, 'Focus Keyword') ?? 'roster beton, pabrik roster plered';
        $author = self::extractMeta($content, 'Author') ?? 'Tim Redaksi IndoRoster';
        $categoryName = self::extractMeta($content, 'Kategori') ?? 'Panduan & Tips';
        $excerpt = self::extractSection($content, 'Ringkasan Singkat (Excerpt)');

        $parts = preg_split('/##\s+Isi Konten Lengkap[^\n]*/i', $content);
        $bodyMarkdown = count($parts) > 1 ? trim($parts[1]) : $content;
        $bodyHtml = Str::markdown($bodyMarkdown);

        $category = ArticleCategory::firstOrCreate(
            ['slug' => Str::slug($categoryName)],
            ['name' => $categoryName, 'is_active' => true]
        );

        return Article::updateOrCreate(
            ['slug' => $slug],
            [
                'article_category_id' => $category->id,
                'title' => $title,
                'excerpt' => $excerpt ?: Str::limit(strip_tags($bodyHtml), 180),
                'content' => $bodyHtml,
                'author_name' => $author,
                'meta_title' => $metaTitle,
                'meta_description' => $metaDesc,
                'meta_keywords' => $metaKeywords,
                'is_published' => true,
                'published_at' => now(),
                'reading_time' => max(3, (int) ceil(str_word_count(strip_tags($bodyHtml)) / 180)),
            ]
        );
    }

    /**
     * Generate a new custom article based on topic and keyword using 8-skill guidelines.
     */
    public static function generateCustom(string $topic, ?string $focusKeyword = null, ?int $categoryId = null): Article
    {
        $focusKeyword = $focusKeyword ?: $topic;
        $apiKey = SiteSetting::getValue('gemini_api_key', config('services.gemini.api_key', env('GEMINI_API_KEY')));

        // If Gemini API is configured, use it with 8-skill system prompt
        if ($apiKey) {
            $generated = self::generateViaGeminiApi($topic, $focusKeyword, $apiKey);
            if ($generated) {
                return self::saveGeneratedData($generated, $categoryId);
            }
        }

        // Fallback to local 8-skill algorithmic generator
        return self::generateViaAlgorithmicEngine($topic, $focusKeyword, $categoryId);
    }

    /**
     * Generate using 8-skill algorithmic template engine.
     */
    private static function generateViaAlgorithmicEngine(string $topic, string $focusKeyword, ?int $categoryId = null): Article
    {
        $slug = Str::slug($topic);
        $title = Str::title($topic);

        $markdownContent = "Saat merencanakan renovasi atau pembangunan dinding arsitektural, topik seputar **{$topic}** selalu menjadi perhatian utama bagi pemilik rumah dan kontraktor di Indonesia.\n\n"
            ."Banyak orang sering kali hanya memperhatikan tampilan visual luarnya saja, tanpa memperhitungkan aspek teknis seperti kekuatan tekan semen, presisi sudut kepingan, dan ketahanan cuaca tropis.\n\n"
            ."---\n\n"
            ."### 1. Masalah Lapangan yang Sering Terjadi\n\n"
            ."Di daerah tropis dengan curah hujan tinggi seperti Jawa Barat, Jabodetabek, dan Bandung, pemilihan material yang keliru bisa berujung pada dinding yang cepat berlumut, retak rambut di sepanjang nat semen, atau ruangan yang tetap pengap karena sirkulasi udara tidak optimal.\n\n"
            ."Pemasangan material berstandar pabrikasi tangan pertama dari sentra Plered Purwakarta dengan teknik cetak tumbuk padat khusus memberikan jaminan kekuatan jangka panjang, kepadatan tanpa rongga, dan sudut siku $90^\\circ$ yang rapi saat dipasang tukang.\n\n"
            ."---\n\n"
            ."### 2. Tips Teknis dan Rekomendasi Pemasangan\n\n"
            ."* **Gunakan Semen Perekat Berkualitas**: Gunakan semen instan mortar atau adukan semen pasir ayak halus 1:3 agar daya rekat maksimal.\n"
            ."* **Beri Kolom Pengikat Praktis**: Setiap bentang dinding mencapai 2,5 – 3 meter, wajib dipasang kolom praktis beton bertulang.\n"
            ."* **Hitung Kebutuhan Cadangan (Waste)**: Selalu siapkan cadangan potongan 5% agar pekerjaan tidak terhenti di tengah jalan.\n\n"
            ."---\n\n"
            ."### Rekomendasi Editorial\n\n"
            .'Untuk mendapatkan hasil terbaik, konsultasikan kebutuhan volume dan motif dinding Anda langsung dengan tim teknis pabrik IndoRoster di Plered, Purwakarta. Anda dapat meninjau katalog produk lengkap kami di [katalog online IndoRoster](https://indoroster.com/katalog) atau melihat foto inspirasi di [galeri proyek](https://indoroster.com/gallery).';

        $bodyHtml = Str::markdown($markdownContent);
        $excerpt = "Panduan lengkap dan ulasan praktis mengenai {$topic}. Pelajari tips pemasangan, kalkulasi material, dan rekomendasi teknis terbaik untuk rumah tropis modern.";

        $category = $categoryId ? ArticleCategory::find($categoryId) : ArticleCategory::first();
        if (! $category) {
            $category = ArticleCategory::create(['name' => 'Panduan & Tips', 'slug' => 'panduan-tips', 'is_active' => true]);
        }

        return Article::updateOrCreate(
            ['slug' => $slug],
            [
                'article_category_id' => $category->id,
                'title' => $title,
                'excerpt' => $excerpt,
                'content' => $bodyHtml,
                'author_name' => 'Tim Redaksi IndoRoster',
                'meta_title' => "{$title} | IndoRoster",
                'meta_description' => Str::limit($excerpt, 155),
                'meta_keywords' => "{$focusKeyword}, roster beton, pabrik roster plered, loster minimalis",
                'is_published' => true,
                'published_at' => now(),
                'reading_time' => 4,
            ]
        );
    }

    /**
     * Generate via Gemini API using the 8-skill guidelines.
     */
    private static function generateViaGeminiApi(string $topic, string $focusKeyword, string $apiKey): ?array
    {
        try {
            $systemPrompt = "Anda adalah Master Content Writer untuk brand IndoRoster (pabrik roster beton di Plered, Purwakarta, Jawa Barat).\n"
                ."Tulis artikel blog berkualitas tinggi dalam bahasa Indonesia mengikuti 8 skill wajib:\n"
                ."1. voice.md: Nada bahasa praktis, cerdas, percaya diri, tanpa kata klise AI ('Di era modern', 'Selain itu', 'Dengan demikian').\n"
                ."2. stories.md: Skenario lapangan yang relatable (situasi -> masalah -> pertimbangan -> keputusan -> pelajaran).\n"
                ."3. stats.md: Perhitungan nyata, tabel dimensi (20x20 cm = 25 pcs/m2), estimasi semen, bobot beban struktur.\n"
                ."4. opinion.md: Sudut pandang editorial yang jujur, berani, anti-overclaim.\n"
                ."5. humor.md: 1-2 sentuhan humor ringan seputar drama renovasi rumah.\n"
                ."6. seolokal.md: Natural Local SEO (Plered, Purwakarta, Jawa Barat, Jabodetabek) tanpa keyword stuffing.\n"
                ."7. humanize.md: Ritme variatif, checklist, dan format markdown bersih.\n\n"
                .'Kembalikan output dalam format JSON valid dengan keys: title, slug, excerpt, content_markdown, meta_title, meta_description, meta_keywords.';

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => "{$systemPrompt}\n\nTopik Artikel: {$topic}\nFocus Keyword: {$focusKeyword}\nBuatkan artikel lengkap dalam format JSON."],
                            ],
                        ],
                    ],
                ]);

            if ($response->successful()) {
                $rawText = $response->json('candidates.0.content.parts.0.text');
                $jsonClean = preg_replace('/^```json\s*|\s*```$/m', '', trim($rawText));
                $data = json_decode($jsonClean, true);

                return is_array($data) ? $data : null;
            }
        } catch (\Exception $e) {
            // Silently fallback on failure
        }

        return null;
    }

    public static function fetchPexelsImage(string $query): ?array
    {
        $apiKey = SiteSetting::getValue('pexels_api_key', config('services.pexels.api_key', 'QWfuKh9eXYF9WbbHAtEFRiPGwyEceJUSY199h9vwbfPRbwGLg7DY32Ap'));
        if (! $apiKey) {
            return null;
        }

        try {
            $searchQuery = urlencode($query.' architecture concrete');
            $response = Http::withHeaders(['Authorization' => $apiKey])
                ->timeout(10)
                ->get("https://api.pexels.com/v1/search?query={$searchQuery}&per_page=1&orientation=landscape");

            if ($response->successful() && ! empty($response->json('photos'))) {
                $photo = $response->json('photos.0');
                $url = $photo['src']['large2x'] ?? ($photo['src']['large'] ?? $photo['src']['original']);

                return [
                    'url' => $url,
                    'alt' => $photo['alt'] ?: $query,
                ];
            }
        } catch (\Exception $e) {
            // Silently fallback on failure
        }

        return null;
    }

    private static function saveGeneratedData(array $data, ?int $categoryId = null): Article
    {
        $title = $data['title'] ?? 'Artikel IndoRoster';
        $slug = $data['slug'] ?? Str::slug($title);
        $contentMarkdown = $data['content_markdown'] ?? ($data['content'] ?? '');
        $bodyHtml = Str::markdown($contentMarkdown);

        $category = $categoryId ? ArticleCategory::find($categoryId) : ArticleCategory::first();
        if (! $category) {
            $category = ArticleCategory::create(['name' => 'Panduan & Tips', 'slug' => 'panduan-tips', 'is_active' => true]);
        }

        $photoData = self::fetchPexelsImage($data['meta_keywords'] ?? $title);

        return Article::updateOrCreate(
            ['slug' => $slug],
            [
                'article_category_id' => $category->id,
                'title' => $title,
                'thumbnail' => $photoData['url'] ?? null,
                'thumbnail_alt' => $photoData['alt'] ?? $title,
                'excerpt' => $data['excerpt'] ?? Str::limit(strip_tags($bodyHtml), 180),
                'content' => $bodyHtml,
                'author_name' => 'Tim Redaksi IndoRoster',
                'meta_title' => $data['meta_title'] ?? "{$title} | IndoRoster",
                'meta_description' => $data['meta_description'] ?? Str::limit($data['excerpt'] ?? '', 155),
                'meta_keywords' => $data['meta_keywords'] ?? 'roster beton, pabrik roster plered',
                'is_published' => true,
                'published_at' => now(),
                'reading_time' => max(3, (int) ceil(str_word_count(strip_tags($bodyHtml)) / 180)),
            ]
        );
    }

    private static function extractMeta(string $content, string $key): ?string
    {
        if (preg_match('/\* \*\*'.preg_quote($key, '/').'\*\*:\s*(.+)$/m', $content, $matches)) {
            return trim(trim($matches[1], '`'));
        }

        return null;
    }

    private static function extractHeaderTitle(string $content): string
    {
        if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
            return trim($matches[1]);
        }

        return 'Artikel IndoRoster';
    }

    private static function extractSection(string $content, string $sectionHeader): ?string
    {
        $pattern = '/##\s+'.preg_quote($sectionHeader, '/').'\s*\n(.*?)(?=\n##\s+|\z)/s';
        if (preg_match($pattern, $content, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }
}
