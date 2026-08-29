<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $backupJsonPath = base_path('scratch/backup_pages_data.json');
        if (! file_exists($backupJsonPath)) {
            return;
        }

        $pages = json_decode(file_get_contents($backupJsonPath), true);
        if (! is_array($pages)) {
            return;
        }

        foreach ($pages as $p) {
            $content = is_string($p['content']) ? json_decode($p['content'], true) : $p['content'];

            Page::updateOrCreate(
                ['slug' => $p['slug']],
                [
                    'title' => $p['title'],
                    'content' => is_array($content) ? $content : [],
                    'meta_title' => $p['meta_title'],
                    'meta_description' => $p['meta_description'],
                    'is_active' => (bool) $p['is_active'],
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
