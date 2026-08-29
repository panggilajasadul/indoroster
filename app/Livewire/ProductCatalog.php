<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use App\Models\Voucher;
use Livewire\Component;
use Livewire\WithPagination;

class ProductCatalog extends Component
{
    use WithPagination;

    public $search = '';

    public $categorySlug = '';

    public $sortBy = 'newest'; // newest, price_asc, price_desc

    public $perPage = 18;

    protected $queryString = [
        'search' => ['except' => ''],
        'categorySlug' => ['except' => '', 'as' => 'category'],
        'sortBy' => ['except' => 'newest'],
    ];

    public function mount($categorySlug = null)
    {
        // Baca categorySlug dari route parameter /katalog/{categorySlug}
        if ($categorySlug && empty($this->categorySlug)) {
            $this->categorySlug = $categorySlug;
        }
    }

    public function loadMore()
    {
        $this->perPage += 18;
    }

    public function updatingSearch()
    {
        $this->resetPage();
        $this->perPage = 18;
    }

    public function updatingCategorySlug()
    {
        $this->resetPage();
        $this->perPage = 18;
    }

    public function updatingSortBy()
    {
        $this->resetPage();
        $this->perPage = 18;
    }

    public function render()
    {
        $query = Product::where('is_active', true)->with('category', 'media', 'variants');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->categorySlug) {
            $category = Category::where('slug', $this->categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        switch ($this->sortBy) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $viralProducts = null;
        if (! $this->search && ! $this->categorySlug) {
            $viralProducts = Product::with(['category', 'media'])->viral()->take(6)->get();
        }

        // Dynamic SEO per category
        $activeCategory = null;
        if ($this->categorySlug) {
            $activeCategory = Category::where('slug', $this->categorySlug)->first();
        }

        $page = Page::where('slug', 'katalog')->where('is_active', true)->first();
        $vouchers = Voucher::active()->get();

        if ($activeCategory) {
            $metaTitle = 'Katalog '.$activeCategory->name.' | IndoRoster — Suplier Jabodetabek & Indonesia';
            $metaDescription = $activeCategory->meta_description
                ?? ($activeCategory->description
                    ?? 'Temukan koleksi lengkap '.$activeCategory->name.' berkualitas tinggi langsung dari pabrik IndoRoster. Kualitas cetak padat presisi, harga pabrik, siap kirim ke Jabodetabek, Bandung, Karawang, Cirebon & seluruh Indonesia.');
            if ($activeCategory->meta_title) {
                $metaTitle = $activeCategory->meta_title;
            }
            $canonicalOverride = route('catalog.category', $activeCategory->slug);
            $catName = strtolower($activeCategory->name);
            $keywords = $catName.', jual '.$catName.', harga '.$catName
                .', '.str_replace('roster', 'loster', $catName)
                .', '.$catName.' jakarta, '.$catName.' jabodetabek'
                .', '.$catName.' bandung, '.$catName.' bekasi'
                .', pabrik '.$catName.', '.$catName.' minimalis';
            $robotsMeta = 'index, follow';
        } elseif ($this->search) {
            $metaTitle = 'Hasil Pencarian "'.$this->search.'" | IndoRoster';
            $metaDescription = 'Temukan produk roster beton yang Anda cari di IndoRoster. Pabrik roster beton minimalis & bata expose siap kirim Jabodetabek & seluruh Indonesia.';
            $canonicalOverride = route('catalog');
            $keywords = 'cari roster beton, '.strtolower($this->search).', roster beton, loster beton';
            $robotsMeta = 'noindex, follow';
        } else {
            $metaTitle = $page?->meta_title ?: 'Katalog Roster Beton & Bata Expose | IndoRoster — Suplier Jabodetabek & Bandung';
            $metaDescription = $page?->meta_description ?: 'Temukan berbagai koleksi roster beton minimalis, bata expose, dan loster arsitektural berkualitas langsung dari pabrik resmi IndoRoster. Harga pabrik, melayani pengiriman Jabodetabek, Bandung, Karawang, Cianjur, Cirebon & seluruh Indonesia.';
            $canonicalOverride = route('catalog');
            $keywords = 'katalog roster beton, roster beton minimalis, loster beton minimalis, jual roster beton, harga roster beton, bata expose, ornamen dinding, jual roster beton jakarta, roster beton jabodetabek, roster beton bandung, supplier roster proyek, roster beton murah';
            $robotsMeta = 'index, follow';
        }

        return view('livewire.product-catalog', [
            'page' => $page,
            'products' => $query->paginate($this->perPage),
            'categories' => Category::where('is_active', true)->get(),
            'viralProducts' => $viralProducts,
            'activeCategory' => $activeCategory,
            'vouchers' => $vouchers,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'canonicalOverride' => $canonicalOverride,
            'keywords' => $keywords,
            'robots' => $robotsMeta,
        ]);
    }
}
