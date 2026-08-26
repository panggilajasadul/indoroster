<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImportSkillArticles extends Command
{
    protected $signature = 'blog:import {--force : Overwrite existing articles with same slug}';

    protected $description = 'Import all 10 ready-to-publish blog articles from indoroster-blog-skills/articles into the database';

    public function handle(): int
    {
        $articlesDir = base_path('indoroster-blog-skills/articles');

        if (! File::isDirectory($articlesDir)) {
            $this->error("Directory not found: {$articlesDir}");

            return 1;
        }

        $files = File::files($articlesDir);
        $importedCount = 0;

        // Ensure default categories exist
        $defaultCategory = ArticleCategory::firstOrCreate(
            ['slug' => 'panduan-tips'],
            [
                'name' => 'Panduan & Tips Konstruksi',
                'description' => 'Panduan teknis, tips perhitungan, dan cara pasang roster beton berkualitas.',
                'is_active' => true,
            ]
        );

        $designCategory = ArticleCategory::firstOrCreate(
            ['slug' => 'inspirasi-desain'],
            [
                'name' => 'Inspirasi & Desain Arsitektur',
                'description' => 'Ide arsitektur fasad tropis, partisi interior, dan estetika rumah modern.',
                'is_active' => true,
            ]
        );

        $materialCategory = ArticleCategory::firstOrCreate(
            ['slug' => 'material-komparasi'],
            [
                'name' => 'Material & Komparasi',
                'description' => 'Uji ketahanan bahan, komparasi mutu semen, dan spesifikasi teknis.',
                'is_active' => true,
            ]
        );

        foreach ($files as $file) {
            if ($file->getExtension() !== 'md') {
                continue;
            }

            $content = File::get($file->getPathname());

            // Extract metadata from markdown header
            $title = $this->extractMeta($content, 'Judul (H1)') ?? $this->extractHeaderTitle($content);
            $slug = $this->extractMeta($content, 'Slug') ?? Str::slug($title);
            $metaTitle = $this->extractMeta($content, 'Meta Title') ?? $title;
            $metaDesc = $this->extractMeta($content, 'Meta Description') ?? '';
            $metaKeywords = $this->extractMeta($content, 'Focus Keyword') ?? '';
            $author = $this->extractMeta($content, 'Author') ?? 'Tim Redaksi IndoRoster';
            $categoryName = $this->extractMeta($content, 'Kategori') ?? '';
            $excerpt = $this->extractSection($content, 'Ringkasan Singkat (Excerpt)');
            $bodyMarkdown = $this->extractSection($content, 'Isi Konten Lengkap (Content Body)') ?? $content;

            // Map category
            $categoryId = $defaultCategory->id;
            if (Str::contains(strtolower($categoryName), ['inspirasi', 'desain'])) {
                $categoryId = $designCategory->id;
            } elseif (Str::contains(strtolower($categoryName), ['material', 'komparasi', 'profil'])) {
                $categoryId = $materialCategory->id;
            }

            // Convert markdown content to rich HTML
            $bodyHtml = Str::markdown($bodyMarkdown);

            // Insert or Update Article
            $existing = Article::where('slug', $slug)->first();

            if ($existing && ! $this->option('force')) {
                $this->line("⏩ Skipped existing article: {$title}");

                continue;
            }

            Article::updateOrCreate(
                ['slug' => $slug],
                [
                    'article_category_id' => $categoryId,
                    'title' => $title,
                    'excerpt' => $excerpt ?: Str::limit(strip_tags($bodyHtml), 180),
                    'content' => $bodyHtml,
                    'author_name' => $author,
                    'meta_title' => $metaTitle,
                    'meta_description' => $metaDesc,
                    'meta_keywords' => $metaKeywords,
                    'is_published' => true,
                    'is_featured' => $importedCount < 3,
                    'published_at' => now()->subHours(rand(1, 72)),
                    'views_count' => rand(120, 650),
                    'reading_time' => max(3, (int) ceil(str_word_count(strip_tags($bodyHtml)) / 180)),
                ]
            );

            $this->info("✅ Imported: {$title}");
            $importedCount++;
        }

        $this->info("🎉 Successfully imported {$importedCount} articles to database!");

        return 0;
    }

    private function extractMeta(string $content, string $key): ?string
    {
        if (preg_match('/\* \*\*'.preg_quote($key, '/').'\*\*:\s*(.+)$/m', $content, $matches)) {
            return trim(trim($matches[1], '`'));
        }

        return null;
    }

    private function extractHeaderTitle(string $content): string
    {
        if (preg_match('/^#\s+(.+)$/m', $content, $matches)) {
            return trim($matches[1]);
        }

        return 'Artikel IndoRoster';
    }

    private function extractSection(string $content, string $sectionHeader): ?string
    {
        if (Str::contains(strtolower($sectionHeader), 'isi konten')) {
            $parts = preg_split('/##\s+Isi Konten Lengkap[^\n]*/i', $content);
            if (count($parts) > 1) {
                return trim($parts[1]);
            }
        }

        $pattern = '/##\s+'.preg_quote($sectionHeader, '/').'\s*\n(.*?)(?=\n##\s+|\z)/s';
        if (preg_match($pattern, $content, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }
}
