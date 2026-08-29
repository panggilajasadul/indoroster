<?php

namespace App\Livewire\B2b;

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\SiteSetting;
use Livewire\Component;
use Livewire\WithPagination;

class ContractorHub extends Component
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

        $waMessage = urlencode('Halo Tim Sales Proyek IndoRoster, saya Kontraktor/Pemborong ingin meminta penawaran harga khusus partai besar (RAB) dan info jadwal kirim bertahap armada pabrik.');
        $waUrl = "https://wa.me/{$waNumber}?text={$waMessage}";

        $page = Page::where('slug', 'untuk-kontraktor')->first();
        $metaTitle = $page?->meta_title ?: 'Supplier Roster Beton untuk Kontraktor & Pemborong Proyek | IndoRoster';
        $metaDescription = $page?->meta_description ?: 'Pabrik produsen roster beton resmi terpercaya untuk kontraktor & pemborong bangunan. Kapasitas ribuan pcs/hari, siku presisi 90°, harga grosir volume, surat jalan & faktur resmi. Kirim Jabodetabek & seluruh Indonesia.';
        $keywords = 'supplier roster kontraktor, pabrik roster beton pemborong, grosir roster proyek, jual roster skala besar, harga roster tender';

        return view('livewire.b2b.contractor-hub', [
            'page' => $page,
            'products' => $allProducts,
            'categories' => $categories,
            'waUrl' => $waUrl,
            'waNumber' => $waNumber,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'keywords' => $keywords,
            'canonicalOverride' => route('b2b.contractor'),
        ]);
    }
}
