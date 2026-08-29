<?php

namespace App\Livewire\B2b;

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\SiteSetting;
use Livewire\Component;
use Livewire\WithPagination;

class ProjectHub extends Component
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

        $waMessage = urlencode('Halo Tim Sales Proyek IndoRoster, saya ingin menanyakan pengadaan roster beton untuk proyek skala besar (Tender / Hotel / Cafe / Gedung Komersial).');
        $waUrl = "https://wa.me/{$waNumber}?text={$waMessage}";

        $page = Page::where('slug', 'roster-beton-proyek')->first();
        $metaTitle = $page?->meta_title ?: 'Pengadaan Roster Beton untuk Proyek Komersial, Cafe, & Gedung | IndoRoster';
        $metaDescription = $page?->meta_description ?: 'Pabrik produsen roster beton dan bata expose untuk proyek komersial, hotel, villa, cafe, ruko, dan fasad gedung. Suplai volume ribuan keping dengan garansi mutu dan tepat waktu.';
        $keywords = 'pengadaan roster proyek, roster fasad cafe, roster hotel resort, loster gedung komersial, roster dinding ruko';

        return view('livewire.b2b.project-hub', [
            'page' => $page,
            'products' => $allProducts,
            'categories' => $categories,
            'waUrl' => $waUrl,
            'waNumber' => $waNumber,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'keywords' => $keywords,
            'canonicalOverride' => route('b2b.project'),
        ]);
    }
}
