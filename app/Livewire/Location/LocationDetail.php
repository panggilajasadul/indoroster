<?php

namespace App\Livewire\Location;

use App\Models\Category;
use App\Models\Product;
use App\Models\SeoLocation;
use App\Models\SiteSetting;
use Livewire\Component;
use Livewire\WithPagination;

class LocationDetail extends Component
{
    use WithPagination;

    public SeoLocation $location;

    public $categorySlug = '';

    public $search = '';

    public $perPage = 16;

    protected $queryString = [
        'categorySlug' => ['except' => '', 'as' => 'kategori'],
        'search' => ['except' => ''],
    ];

    public function mount($slug)
    {
        $cleanSlug = strtolower(trim($slug));

        // 1. Pencocokan langsung atau variasi standar
        $loc = SeoLocation::where(function ($q) use ($cleanSlug) {
            $q->where('slug', $cleanSlug)
                ->orWhere('slug', 'roster-beton-minimalis-'.$cleanSlug)
                ->orWhere('slug', str_replace('roster-beton-minimalis-', '', $cleanSlug))
                ->orWhere('slug', str_replace('jual-roster-beton-', 'roster-beton-minimalis-', $cleanSlug));
        })
            ->where('seo_enabled', true)
            ->first();

        // 2. Pencocokan variasi arah/wilayah (cth: jakarta-selatan, jakarta-timur -> jakarta)
        if (! $loc) {
            $strippedSlug = preg_replace('/-(selatan|barat|timur|utara|pusat|kota|kabupaten|kab|raya)$/i', '', str_replace('roster-beton-minimalis-', '', $cleanSlug));
            $loc = SeoLocation::where(function ($q) use ($strippedSlug) {
                $q->where('slug', $strippedSlug)
                    ->orWhere('slug', 'roster-beton-minimalis-'.$strippedSlug);
            })
                ->where('seo_enabled', true)
                ->first();
        }

        // 3. Pencocokan nama wilayah / target_districts
        if (! $loc) {
            $searchWord = str_replace(['roster-beton-minimalis-', 'jual-roster-beton-', '-'], [' ', ' ', ' '], $cleanSlug);
            $searchWord = trim($searchWord);

            if (! empty($searchWord)) {
                $loc = SeoLocation::where('seo_enabled', true)
                    ->where(function ($q) use ($searchWord) {
                        $q->where('name', 'like', "%{$searchWord}%")
                            ->orWhere('target_districts', 'like', "%{$searchWord}%");
                    })
                    ->first();
            }
        }

        // 4. Fallback ke lokasi terdekat/prioritas pertama jika tidak ditemukan
        if (! $loc) {
            $loc = SeoLocation::where('seo_enabled', true)->orderBy('priority')->first();
        }

        if (! $loc) {
            abort(404);
        }

        $this->location = $loc;
    }

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
        // Query SEMUA produk aktif
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

        $cityName = $this->location->name;
        $waText = "Halo Admin IndoRoster, saya sedang melihat katalog Roster Beton untuk wilayah {$cityName}. Boleh info ketersediaan stok motif, harga pabrik, dan jadwal pengiriman armada ke alamat proyek saya di {$cityName}?";
        $waUrl = "https://wa.me/{$waNumber}?text=".urlencode($waText);

        $metaTitle = $this->location->meta_title ?: "Jual Roster Beton {$cityName} | Produsen Resmi — IndoRoster";
        $metaDescription = $this->location->meta_description ?: "Pusat katalog lengkap roster beton minimalis, bata expose, dan loster arsitektural untuk wilayah {$cityName}. Kualitas cetak padat presisi harga pabrik, siap kirim bergaransi.";

        $keywords = "roster beton {$cityName}, jual loster {$cityName}, harga roster {$cityName}, supplier roster {$cityName}, pabrik roster {$cityName}, bata tempel {$cityName}, katalog roster {$cityName}";

        // Lokasi terkait / sekitar (Silo Internal Linking)
        $nearbyLocations = SeoLocation::where('seo_enabled', true)
            ->where('id', '!=', $this->location->id)
            ->when($this->location->province_code, function ($q) {
                $q->where('province_code', $this->location->province_code);
            })
            ->orderBy('priority', 'asc')
            ->take(8)
            ->get();

        if ($nearbyLocations->count() < 8) {
            $fallbackLocations = SeoLocation::where('seo_enabled', true)
                ->where('id', '!=', $this->location->id)
                ->whereNotIn('id', $nearbyLocations->pluck('id'))
                ->orderBy('priority', 'asc')
                ->take(8 - $nearbyLocations->count())
                ->get();
            $nearbyLocations = $nearbyLocations->merge($fallbackLocations);
        }

        return view('livewire.location.location-detail', [
            'location' => $this->location,
            'products' => $allProducts,
            'categories' => $categories,
            'nearbyLocations' => $nearbyLocations,
            'waUrl' => $waUrl,
            'waNumber' => $waNumber,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'keywords' => $keywords,
            'canonicalOverride' => route('location.detail', $this->location->slug),
        ]);
    }
}
