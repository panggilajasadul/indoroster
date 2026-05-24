<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;

class ProductCatalog extends Component
{
    use WithPagination;

    public $search = '';
    public $categorySlug = '';
    public $sortBy = 'newest'; // newest, price_asc, price_desc

    protected $queryString = [
        'search' => ['except' => ''],
        'categorySlug' => ['except' => '', 'as' => 'category'],
        'sortBy' => ['except' => 'newest'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategorySlug()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::where('is_active', true)->with('category', 'media', 'variants');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
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
        if (!$this->search && !$this->categorySlug) {
            $viralProducts = Product::with(['category', 'media'])->viral()->take(6)->get();
        }

        // Dynamic SEO per category
        $activeCategory = null;
        if ($this->categorySlug) {
            $activeCategory = Category::where('slug', $this->categorySlug)->first();
        }

        if ($activeCategory) {
            $metaTitle = 'Katalog ' . $activeCategory->name . ' | INDOROSTER — Pabrik Roster Beton Plered';
            $metaDescription = $activeCategory->description
                ?? 'Temukan koleksi lengkap ' . $activeCategory->name . ' dari pabrik tangan pertama INDOROSTER Plered Purwakarta. Kualitas K-200, harga pabrik, pengiriman seluruh Indonesia.';
        } elseif ($this->search) {
            $metaTitle = 'Hasil Pencarian "' . $this->search . '" | INDOROSTER';
            $metaDescription = 'Temukan produk roster beton yang Anda cari di INDOROSTER. Pabrik roster beton premium Plered Purwakarta.';
        } else {
            $metaTitle = 'Katalog Roster Beton & Bata Expose | INDOROSTER — Pabrik Plered Purwakarta';
            $metaDescription = 'Temukan berbagai koleksi roster beton minimalis, bata expose, dan ornamen dinding berkualitas dari pabrik tangan pertama INDOROSTER. Harga terbaik, pengiriman seluruh Indonesia.';
        }

        return view('livewire.product-catalog', [
            'products'       => $query->paginate(12),
            'categories'     => Category::where('is_active', true)->get(),
            'viralProducts'  => $viralProducts,
            'activeCategory' => $activeCategory,
        ])->layout('components.layouts.app', [
            'title'       => $metaTitle,
            'description' => $metaDescription,
        ]);
    }
}
