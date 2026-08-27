<?php

namespace App\Console\Commands;

use App\Models\Page;
use Illuminate\Console\Command;

class CleanCloudinaryUrls extends Command
{
    protected $signature = 'indoroster:clean-cloudinary';

    protected $description = 'Clean dead Cloudinary URLs from pages content in database';

    public function handle(): int
    {
        $this->info('Cleaning dead Cloudinary URLs from Page Builder contents...');

        $pages = Page::all();
        $cleanedCount = 0;

        foreach ($pages as $page) {
            if (empty($page->content) || ! is_array($page->content)) {
                continue;
            }

            $hasChanged = false;
            $content = $this->cleanArray($page->content, $hasChanged);

            if ($hasChanged) {
                $page->content = $content;
                $page->saveQuietly();
                $cleanedCount++;
                $this->line("  ✓ Cleaned page: {$page->title} ({$page->slug})");
            }
        }

        $this->info("Done! Cleaned {$cleanedCount} pages.");

        return self::SUCCESS;
    }

    private function cleanArray(array $data, bool &$hasChanged): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->cleanArray($value, $hasChanged);
            } elseif (is_string($value) && str_contains($value, 'res.cloudinary.com')) {
                // If it is a cloudinary URL, empty it out
                $data[$key] = '';
                $hasChanged = true;
            }
        }

        return $data;
    }
}
