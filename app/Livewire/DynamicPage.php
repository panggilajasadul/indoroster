<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Component;

class DynamicPage extends Component
{
    public Page $page;

    public function mount($slug = null)
    {
        if (! $slug && request()->routeIs('gallery')) {
            $slug = 'gallery';
        }

        $normalizedSlug = strtolower(trim($slug ?? ''));

        // Redirection mapping untuk halaman-halaman utama yang memiliki route dedicated
        $dedicatedRoutes = [
            'home' => '/',
            'tentang-kami' => '/tentang-kami',
            'kontak' => '/kontak',
            'proses-produksi' => '/proses-produksi',
            'katalog' => '/katalog',
            'gallery' => '/gallery',
            'indoroster-video' => '/video-inspirasi',
            'video-inspirasi' => '/video-inspirasi',
            'untuk-arsitek' => '/untuk-arsitek',
            'untuk-kontraktor' => '/untuk-kontraktor',
            'untuk-developer' => '/untuk-developer',
            'supplier-roster-beton' => '/supplier-roster-beton',
            'roster-beton-proyek' => '/roster-beton-proyek',
            'kalkulator-roster' => '/kalkulator-roster',
            'lokasi' => '/lokasi',
        ];

        if (isset($dedicatedRoutes[$normalizedSlug])) {
            $this->redirect($dedicatedRoutes[$normalizedSlug], navigate: false);

            return;
        }

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
        if (empty($this->page)) {
            return '<div></div>';
        }

        $metaTitle = $this->page->meta_title ?: ($this->page->title.' - IndoRoster Indonesia');
        $metaDesc = $this->page->meta_description ?: ($this->page->title.' - Informasi resmi dari Pabrik Roster Beton IndoRoster.');

        return view('livewire.dynamic-page', [
            'page' => $this->page,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDesc,
            'canonicalOverride' => route('dynamic.page', $this->page->slug),
            'ogType' => 'website',
        ]);
    }
}
