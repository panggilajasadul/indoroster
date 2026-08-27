<?php

namespace App\Console\Commands;

use App\Helpers\PageBlockDefaults;
use App\Models\Page;
use Illuminate\Console\Command;

class SyncPageDefaults extends Command
{
    protected $signature = 'indoroster:sync-page-defaults';

    protected $description = 'Sync and fill empty block fields in pages with default standard values';

    public function handle(): int
    {
        $this->info('Synchronizing default block values across all pages in database...');

        $pages = Page::all();
        $updatedCount = 0;

        foreach ($pages as $page) {
            if (empty($page->content) || ! is_array($page->content)) {
                continue;
            }

            $originalJson = json_encode($page->content);
            $hydrated = PageBlockDefaults::hydrateBlocks($page->content);

            if (json_encode($hydrated) !== $originalJson) {
                $page->content = $hydrated;
                $page->saveQuietly();
                $updatedCount++;
                $this->line("  ✓ Synced & populated defaults for: {$page->title} ({$page->slug})");
            }
        }

        $this->info("Done! Successfully populated default values for {$updatedCount} pages.");

        return self::SUCCESS;
    }
}
