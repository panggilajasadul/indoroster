<?php

namespace App\Livewire\Seo;

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\SeoPage;
use App\Models\SiteSetting;
use Livewire\Component;

/**
 * SeoPageDetailFallback: Dispatcher Livewire untuk route fallback /{slug}.
 * Dilengkapi dengan 3-Level Product Display Engine:
 * - Level 1: Featured Curated Products (sesuai context intent)
 * - Level 2: Interactive Product Explorer (search & category filter live)
 * - Level 3: Related Products & Sibling Pages
 */
class SeoPageDetailFallback extends Component
{
    // Mode yang dipakai: 'seo' | 'cms'
    public string $mode = 'seo';

    // Data untuk mode SEO
    public ?SeoPage $seoPage = null;

    // Data untuk mode CMS
    public ?Page $cmsPage = null;

    // Filter interaktif untuk Level 2 Product Explorer
    public string $search = '';

    public string $selectedCategory = '';

    public function mount(string $slug)
    {
        $normalizedSlug = strtolower(trim($slug));

        // ── Dedicated route redirect untuk halaman dengan route sendiri ──
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

        // ── 1. Cek SeoPage published (prioritas utama) ──
        $seoPage = SeoPage::where('slug', $normalizedSlug)
            ->where('status', 'published')
            ->where('noindex', false)
            ->with(['sections' => fn ($q) => $q->where('is_visible', true)->orderBy('sort_order')])
            ->first();

        if ($seoPage) {
            $this->mode = 'seo';
            $this->seoPage = $seoPage;

            return;
        }

        // ── 2. Cek CMS Page (DynamicPage logic) ──
        $page = Page::where('slug', $normalizedSlug)->where('is_active', true)->first();

        if (! $page) {
            // Alias slug check
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

        if ($page) {
            $this->mode = 'cms';
            $this->cmsPage = $page;

            return;
        }

        // ── 3. Tidak ditemukan ──
        abort(404);
    }

    public function render()
    {
        if ($this->mode === 'seo' && $this->seoPage) {
            return $this->renderSeoPage();
        }

        if ($this->mode === 'cms' && $this->cmsPage) {
            return $this->renderCmsPage();
        }

        return '<div></div>';
    }

    // ──────────────────────────────────────────────
    // Render SEO Page (3-Level Product Display Engine)
    // ──────────────────────────────────────────────

    private function renderSeoPage()
    {
        $page = $this->seoPage;

        // Level 1: Featured Curated Products (3–8 items sesuai context)
        $featuredProducts = $page->matchProducts(8);

        // Level 2: Product Explorer (Katalog Interaktif)
        $explorerQuery = Product::where('is_active', true)
            ->with(['media', 'variants', 'category']);

        if (! empty($this->search)) {
            $explorerQuery->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('sku', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        if (! empty($this->selectedCategory)) {
            $explorerQuery->whereHas('category', fn ($q) => $q->where('slug', $this->selectedCategory));
        }

        $explorerProducts = $explorerQuery->limit(24)->get();

        // Level 3: Internal Linking (Related Pages)
        $relatedPages = $page->getRelatedPages(6);

        $categories = Category::orderBy('name')->get();
        $sections = $page->sections()->where('is_visible', true)->orderBy('sort_order')->get();
        $faqSections = $sections->where('section_type', 'faq');

        $rawWa = SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
        $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62'.substr($waNumber, 1);
        }
        $waUrl = $page->buildWhatsAppUrl();

        $keywords = $page->keywords->pluck('keyword')->implode(', ');

        return view('livewire.seo.seo-page-detail', [
            'page' => $page,
            'featuredProducts' => $featuredProducts,
            'explorerProducts' => $explorerProducts,
            'relatedPages' => $relatedPages,
            'categories' => $categories,
            'sections' => $sections,
            'faqSections' => $faqSections,
            'waUrl' => $waUrl,
            'waNumber' => $waNumber,
        ])->layout('components.layouts.app', [
            'title' => $page->title,
            'description' => $page->meta_description,
            'keywords' => $keywords,
            'canonicalOverride' => $page->canonical_url ?? url($page->slug),
            'noindex' => $page->noindex,
        ]);
    }

    // ──────────────────────────────────────────────
    // Render CMS Page
    // ──────────────────────────────────────────────

    private function renderCmsPage()
    {
        if (! $this->cmsPage) {
            return '<div></div>';
        }

        $page = $this->cmsPage;
        $metaTitle = $page->meta_title ?: ($page->title.' - IndoRoster Indonesia');
        $metaDesc = $page->meta_description ?: ($page->title.' - Informasi resmi dari Pabrik Roster Beton IndoRoster.');

        return view('livewire.dynamic-page', [
            'page' => $page,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDesc,
            'canonicalOverride' => route('dynamic.page', $page->slug),
            'ogType' => 'website',
        ]);
    }
}
