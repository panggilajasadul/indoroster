<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Component;

class DynamicPage extends Component
{
    public Page $page;

    public function mount($slug = null)
    {
        if (! $slug) {
            $routeName = request()->route()?->getName();
            $slug = match ($routeName) {
                'b2b.contractor' => 'untuk-kontraktor',
                'b2b.developer' => 'untuk-developer',
                'b2b.architect' => 'untuk-arsitek',
                'b2b.supplier', 'b2b.wholesale' => 'supplier-roster-beton',
                'b2b.project' => 'roster-beton-proyek',
                'gallery' => 'gallery',
                default => request()->path(),
            };
        }

        $normalizedSlug = strtolower(trim($slug ?? ''));

        // 1. Coba cari langsung dengan slug yang diminta
        $page = Page::where('slug', $normalizedSlug)->where('is_active', true)->first();

        // 2. Jika belum ditemukan, coba cari melalui daftar variasi / alias yang valid
        if (! $page) {
            $slugAliases = [
                'syarat-dan-ketentuan' => ['syarat-ketentuan', 'terms', 'terms-and-conditions', 'snk'],
                'syarat-ketentuan' => ['syarat-dan-ketentuan', 'terms', 'terms-and-conditions', 'snk'],
                'terms' => ['syarat-dan-ketentuan', 'syarat-ketentuan', 'terms-and-conditions'],
                'terms-and-conditions' => ['syarat-dan-ketentuan', 'syarat-ketentuan', 'terms'],
                'kebijakan-privasi' => ['privacy', 'privacy-policy', 'kebijakan-privacy'],
                'privacy' => ['kebijakan-privasi', 'privacy-policy', 'kebijakan-privacy'],
                'privacy-policy' => ['kebijakan-privasi', 'privacy', 'kebijakan-privacy'],
            ];

            if (isset($slugAliases[$normalizedSlug])) {
                $page = Page::whereIn('slug', $slugAliases[$normalizedSlug])->where('is_active', true)->first();
            }
        }

        if (! $page) {
            abort(404);
        }

        $this->page = $page;
    }

    public function render()
    {
        $metaTitle = $this->page->meta_title ?: ($this->page->title.' - IndoRoster Indonesia');
        $metaDesc = $this->page->meta_description ?: ($this->page->title.' - Informasi resmi dari Pabrik Roster Beton IndoRoster.');

        return view('livewire.dynamic-page')->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDesc,
            'canonicalOverride' => route('dynamic.page', $this->page->slug),
            'ogType' => 'website',
        ]);
    }
}
