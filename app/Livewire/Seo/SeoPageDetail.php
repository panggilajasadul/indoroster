<?php

namespace App\Livewire\Seo;

use App\Models\Category;
use App\Models\SeoPage;
use App\Models\SiteSetting;
use Livewire\Component;

class SeoPageDetail extends Component
{
    public SeoPage $seoPage;

    public function mount(string $slug)
    {
        $this->seoPage = SeoPage::where('slug', $slug)
            ->where('status', 'published')
            ->where('noindex', false)
            ->with(['sections' => fn ($q) => $q->visible()->ordered()])
            ->firstOrFail();
    }

    public function render()
    {
        $page = $this->seoPage;

        // Produk yang relevan
        $products = $page->matchProducts(8);

        // Halaman terkait
        $relatedPages = $page->getRelatedPages(6);

        // Kategori untuk sidebar/filter
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        // WhatsApp CTA
        $waUrl = $page->buildWhatsAppUrl();

        // Nomor WA untuk display
        $rawWa = SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
        $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62'.substr($waNumber, 1);
        }

        // Sections dikelompokkan berdasarkan tipe
        $sections = $page->sections->where('is_visible', true)->sortBy('sort_order');

        // FAQ sections (untuk structured data)
        $faqSections = $sections->where('section_type', 'faq');

        // Meta
        $metaTitle = $page->title;
        $metaDescription = $page->meta_description;
        $keywords = $page->primary_keyword;
        if (! empty($page->secondary_keywords)) {
            $keywords .= ', '.implode(', ', $page->secondary_keywords);
        }

        return view('livewire.seo.seo-page-detail', [
            'page' => $page,
            'products' => $products,
            'relatedPages' => $relatedPages,
            'categories' => $categories,
            'sections' => $sections,
            'faqSections' => $faqSections,
            'waUrl' => $waUrl,
            'waNumber' => $waNumber,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'keywords' => $keywords,
            'canonicalOverride' => $page->canonical_url ?? url($page->slug),
            'noindex' => $page->noindex,
        ]);
    }
}
