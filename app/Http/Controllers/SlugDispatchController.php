<?php

namespace App\Http\Controllers;

use App\Livewire\DynamicPage;
use App\Livewire\Seo\SeoPageDetail;
use App\Models\SeoPage;
use Illuminate\Http\Request;

class SlugDispatchController extends Controller
{
    /**
     * Dispatch slug ke SeoPageDetail jika published, atau DynamicPage (CMS).
     * Mencegah redirect loop dan memastikan SeoPage mendapat prioritas.
     */
    public function __invoke(Request $request, string $slug)
    {
        $normalizedSlug = strtolower(trim($slug));

        // Cek SeoPage yang published — prioritas utama
        $seoPageExists = SeoPage::where('slug', $normalizedSlug)
            ->where('status', 'published')
            ->where('noindex', false)
            ->exists();

        if ($seoPageExists) {
            return app()->call([SeoPageDetail::class, '__invoke'], ['slug' => $normalizedSlug]);
        }

        // Fallback ke DynamicPage CMS
        return app()->call([DynamicPage::class, '__invoke'], ['slug' => $normalizedSlug]);
    }
}
