<?php

namespace App\Livewire\B2b;

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\SiteSetting;
use Livewire\Component;
use Livewire\WithPagination;

class SupplierHub extends Component
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

        $waMessage = urlencode('Halo Tim Grosir IndoRoster, saya dari Toko Bangunan / Distributor Material ingin menanyakan skema harga reseller/grosir pabrik dan ketentuan minimum order.');
        $waUrl = "https://wa.me/{$waNumber}?text={$waMessage}";

        $page = Page::where('slug', 'supplier-roster-beton')->first();
        $metaTitle = $page?->meta_title ?: 'Supplier Roster Beton & Grosir Pabrik Resmi | IndoRoster';
        $metaDescription = $page?->meta_description ?: 'Pabrik supplier roster beton untuk toko bangunan, agen, dan distributor material. Skema harga grosir per truk/ritase, stok ribuan pcs, pengiriman cepat se-Indonesia.';
        $keywords = 'supplier roster beton, grosir roster beton toko bangunan, distributor roster pabrik, agen loster purwakarta, jual roster per truk';

        return view('livewire.b2b.supplier-hub', [
            'page' => $page,
            'products' => $allProducts,
            'categories' => $categories,
            'waUrl' => $waUrl,
            'waNumber' => $waNumber,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'keywords' => $keywords,
            'canonicalOverride' => route('b2b.supplier'),
        ]);
    }
}
