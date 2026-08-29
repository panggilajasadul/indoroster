<?php

namespace App\Livewire\B2b;

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\SiteSetting;
use Livewire\Component;
use Livewire\WithPagination;

class ArchitectHub extends Component
{
    use WithPagination;

    public $categorySlug = '';

    public $search = '';

    public $perPage = 16;

    protected $queryString = [
        'categorySlug' => ['except' => '', 'as' => 'kategori'],
        'search' => ['except' => ''],
    ];

    public function loadMore()
    {
        $this->perPage += 16;
    }

    public function updatingSearch()
    {
        $this->resetPage();
        $this->perPage = 16;
    }

    public function updatingCategorySlug()
    {
        $this->resetPage();
        $this->perPage = 16;
    }

    public function render()
    {
        $productQuery = Product::where('is_active', true)
            ->with(['media', 'variants', 'category']);

        if (! empty($this->search)) {
            $productQuery->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        if (! empty($this->categorySlug)) {
            $cat = Category::where('slug', $this->categorySlug)->first();
            if ($cat) {
                $productQuery->where('category_id', $cat->id);
            }
        }

        $allProducts = $productQuery->latest()->paginate($this->perPage);
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        $rawWa = SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
        $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62'.substr($waNumber, 1);
        }

        $waMessage = urlencode('Halo Tim Desain IndoRoster, saya Arsitek / Desainer Interior ingin berkonsultasi spesifikasi teknis, request sampel fisik motif, dan katalog PDF arsitektur.');
        $waUrl = "https://wa.me/{$waNumber}?text={$waMessage}";

        $page = Page::where('slug', 'untuk-arsitek')->first();
        $metaTitle = $page?->meta_title ?: 'Katalog Roster Arsitektur untuk Arsitek & Desainer Interior | IndoRoster';
        $metaDescription = $page?->meta_description ?: 'Pusat eksplorasi material roster beton arsitektural untuk arsitek dan interior designer. Dimensi presisi, rasio ventilasi optimal, konsultasi motif custom, sampel fisik & katalog PDF.';
        $keywords = 'katalog roster arsitektur, roster beton minimalis arsitek, spesifikasi teknis roster beton, loster custom arsitek, ventilasi fasad kontemporer';

        return view('livewire.b2b.architect-hub', [
            'page' => $page,
            'products' => $allProducts,
            'categories' => $categories,
            'waUrl' => $waUrl,
            'waNumber' => $waNumber,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'keywords' => $keywords,
            'canonicalOverride' => route('b2b.architect'),
        ]);
    }
}
