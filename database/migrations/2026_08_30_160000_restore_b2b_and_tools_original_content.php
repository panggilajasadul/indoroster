<?php

use App\Models\Page;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $targetSlugs = [
            'untuk-kontraktor',
            'untuk-developer',
            'untuk-arsitek',
            'supplier-roster-beton',
            'roster-beton-proyek',
            'kalkulator-roster',
            'lokasi',
        ];

        foreach ($targetSlugs as $slug) {
            $page = Page::where('slug', $slug)->first();
            if ($page) {
                $page->update([
                    'content' => [],
                ]);
            }
        }
    }

    public function down(): void
    {
        // No-op
    }
};
