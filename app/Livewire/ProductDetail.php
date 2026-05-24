<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class ProductDetail extends Component
{
    use WithFileUploads;

    public Product $product;
    public $quantity = 1;
    public $selectedVariant = null;
    public $activeImage;
    public $activeMediaType = 'image';
    
    protected $queryString = [
        'selectedVariant' => ['except' => null, 'as' => 'variant'],
    ];
    
    // Review properties
    public $ratingFilter = 0; // 0 means all
    public $reviewPage = 1;
    public $reviewsPerPage = 5;
    
    // New Review Form
    public $reviewer_name;
    public $reviewer_location;
    public $rating = 5;
    public $content;
    public $review_images = [];
    
    // Calculator properties
    public $wall_width = 1;
    public $wall_height = 1;
    public $include_waste = true;
    
    public function mount($slug)
    {
        $this->product = Product::where('slug', $slug)
            ->with(['media', 'variants', 'category'])
            ->withCount(['approvedReviews' => function($query) {
                $query->where('is_approved', true);
            }])
            ->firstOrFail();
            
        // Prioritize video: if product has any video, show it first
        $videoMedia = $this->product->media->firstWhere('media_type', 'video');
        if ($videoMedia) {
            $this->activeImage = str_starts_with($videoMedia->media_url, 'http') 
                ? $videoMedia->media_url 
                : asset('storage/' . $videoMedia->media_url);
            $this->activeMediaType = 'video';
        } else {
            $this->activeImage = $this->product->primary_image;
            $this->activeMediaType = 'image';
        }
        $this->quantity = $this->product->min_order > 0 ? $this->product->min_order : 1;
        // Tampilkan gambar varian jika varian terpilih di URL query
        if ($this->selectedVariant) {
            $variant = $this->product->variants->find($this->selectedVariant);
            if ($variant && $variant->image_url) {
                $this->activeImage = str_starts_with($variant->image_url, 'http') 
                    ? $variant->image_url 
                    : asset('storage/' . $variant->image_url);
                $this->activeMediaType = 'image';
            }
        }
    }

    public function setRatingFilter($rating)
    {
        $this->ratingFilter = $rating;
        $this->reviewPage = 1;
    }

    public function loadMoreReviews()
    {
        $this->reviewPage++;
    }

    public function submitReview()
    {
        $this->validate([
            'reviewer_name' => 'required|min:2|max:50',
            'reviewer_location' => 'nullable|max:50',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|min:5|max:1000',
            'review_images.*' => 'nullable|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi|max:10240',
        ], [
            'reviewer_name.required' => 'Nama wajib diisi.',
            'content.required' => 'Isi review wajib diisi.',
            'review_images.*.file' => 'File review harus berupa file gambar atau video.',
            'review_images.*.mimes' => 'Format file harus berupa JPG, JPEG, PNG, WEBP, MP4, MOV, atau AVI.',
            'review_images.*.max' => 'Ukuran file maksimal 10MB.',
        ]);

        $imagePaths = [];
        if ($this->review_images) {
            foreach ($this->review_images as $image) {
                $imagePaths[] = $image->store('reviews', 'public');
            }
        }

        \App\Models\ProductReview::create([
            'product_id' => $this->product->id,
            'reviewer_name' => $this->reviewer_name,
            'reviewer_location' => $this->reviewer_location,
            'rating' => $this->rating,
            'content' => $this->content,
            'images' => $imagePaths,
            'is_approved' => true, // Auto approve for now as requested
        ]);

        $this->reset(['reviewer_name', 'reviewer_location', 'rating', 'content', 'review_images']);
        session()->flash('review_success', 'Terima kasih! Review Anda berhasil dikirim.');
        
        // Refresh product count
        $this->product->loadCount('approvedReviews');
    }

    public function getReviewsProperty()
    {
        $query = \App\Models\ProductReview::where('product_id', $this->product->id)
            ->where('is_approved', true)
            ->latest();
        
        if ($this->ratingFilter > 0) {
            $query->where('rating', $this->ratingFilter);
        }
        
        return $query->paginate($this->reviewPage * $this->reviewsPerPage, ['*'], 'review_page');
    }

    public function getRatingStatsProperty()
    {
        $stats = [];
        $total = \App\Models\ProductReview::where('product_id', $this->product->id)
            ->where('is_approved', true)
            ->count();

        for ($i = 5; $i >= 1; $i--) {
            $count = \App\Models\ProductReview::where('product_id', $this->product->id)
                ->where('is_approved', true)
                ->where('rating', $i)
                ->count();
            
            $stats[$i] = [
                'count' => $count,
                'percent' => $total > 0 ? ($count / $total) * 100 : 0
            ];
        }

        return [
            'total' => $total,
            'stats' => $stats
        ];
    }

    public function setActiveImage($url, $type = 'image')
    {
        $this->activeImage = $url;
        $this->activeMediaType = $type;
    }

    public function updatedSelectedVariant($value)
    {
        $variant = $this->product->variants->find($value);
        if ($variant && $variant->image_url) {
            // Jika image_url adalah URL lengkap (legacy), gunakan langsung. 
            // Jika bukan, gunakan asset('storage/...')
            $this->activeImage = str_starts_with($variant->image_url, 'http') 
                ? $variant->image_url 
                : asset('storage/' . $variant->image_url);
            $this->activeMediaType = 'image';
        }
    }

    public function incrementQuantity()
    {
        $this->quantity++;
    }

    public function decrementQuantity()
    {
        $min = $this->product->min_order > 0 ? $this->product->min_order : 1;
        if ($this->quantity > $min) {
            $this->quantity--;
        }
    }

    public function updatedQuantity($value)
    {
        $min = $this->product->min_order > 0 ? $this->product->min_order : 1;
        if (is_numeric($value) && (int)$value < $min) {
            $this->quantity = $min;
        }
    }

    public function getPriceRangeProperty()
    {
        return $this->product->formatted_price_range;
    }

    public function getActivePriceProperty()
    {
        if ($this->selectedVariant) {
            $variant = $this->product->variants->find($this->selectedVariant);
            return $variant ? (float)$variant->final_price : (float)$this->product->price;
        }
        
        return (float)$this->product->price;
    }
    
    public function getActiveStockProperty()
    {
        if ($this->product->variants->count() > 0) {
            if ($this->selectedVariant) {
                $variant = $this->product->variants->find($this->selectedVariant);
                return $variant ? $variant->stock : 0;
            }
            return $this->product->variants->sum('stock');
        }
        return $this->product->stock;
    }

    public function addToCart()
    {
        if ($this->product->variants->count() > 0 && !$this->selectedVariant) {
            $this->dispatch('open-warning-modal', [
                'title' => 'Pilih Varian',
                'message' => 'Silakan pilih opsi varian produk terlebih dahulu sebelum menambahkan ke keranjang.'
            ]);
            return false;
        }

        if ($this->getActiveStockProperty() < $this->quantity) {
            $this->dispatch('open-warning-modal', [
                'title' => 'Stok Tidak Mencukupi',
                'message' => 'Jumlah yang Anda pilih melebihi stok yang tersedia.'
            ]);
            return false;
        }

        $min = $this->product->min_order > 0 ? $this->product->min_order : 1;
        if ($this->quantity < $min) {
            $this->dispatch('open-warning-modal', [
                'title' => 'Minimal Order Produk',
                'message' => 'Minimal pembelian untuk produk ' . $this->product->name . ' adalah ' . $min . ' pcs.'
            ]);
            return false;
        }

        // Get or set session_id for guest cart
        $sessionId = Cookie::get('cart_session_id');
        if (!$sessionId) {
            $sessionId = Str::uuid()->toString();
            Cookie::queue('cart_session_id', $sessionId, 60 * 24 * 30); // 30 days
        }

        // Find existing cart item
        $cart = Cart::where('session_id', $sessionId)
            ->where('product_id', $this->product->id)
            ->where('product_variant_id', $this->selectedVariant)
            ->first();

        if ($cart) {
            $cart->quantity += $this->quantity;
            $cart->save();
        } else {
            Cart::create([
                'session_id' => $sessionId,
                'user_id' => auth()->id(),
                'product_id' => $this->product->id,
                'product_variant_id' => $this->selectedVariant,
                'quantity' => $this->quantity,
            ]);
        }

        $this->dispatch('cart-updated');
        session()->flash('success', 'Produk berhasil ditambahkan ke keranjang.');
        return true;
    }

    public function buyNow()
    {
        if ($this->product->variants->count() > 0 && !$this->selectedVariant) {
            $this->dispatch('open-warning-modal', [
                'title' => 'Pilih Varian',
                'message' => 'Silakan pilih opsi varian produk terlebih dahulu sebelum membeli.'
            ]);
            return false;
        }

        if ($this->getActiveStockProperty() < $this->quantity) {
            $this->dispatch('open-warning-modal', [
                'title' => 'Stok Tidak Mencukupi',
                'message' => 'Jumlah yang Anda pilih melebihi stok yang tersedia.'
            ]);
            return false;
        }

        $min = $this->product->min_order > 0 ? $this->product->min_order : 1;
        if ($this->quantity < $min) {
            $this->dispatch('open-warning-modal', [
                'title' => 'Minimal Order Produk',
                'message' => 'Minimal pembelian untuk produk ' . $this->product->name . ' adalah ' . $min . ' pcs.'
            ]);
            return false;
        }

        session()->put('buy_now_item', [
            'product_id' => $this->product->id,
            'product_variant_id' => $this->selectedVariant,
            'quantity' => $this->quantity,
        ]);

        return redirect()->route('checkout', ['mode' => 'buy_now']);
    }

    public function getCalculatedRequirementProperty()
    {
        $dims = $this->product->parsed_dimensions;
        $widthCm = $dims['width'];
        $heightCm = $dims['height'];
        
        if ($widthCm <= 0 || $heightCm <= 0) return 0;
        
        // Calculate pieces per m2
        // Example: 20x20cm -> 100/20 = 5 pcs per side -> 5x5 = 25 pcs per m2
        $pcsPerMeterWidth = 100 / $widthCm;
        $pcsPerMeterHeight = 100 / $heightCm;
        
        $wallWidth = (float) $this->wall_width;
        $wallHeight = (float) $this->wall_height;
        $totalArea = $wallWidth * $wallHeight;
        $totalPcs = $totalArea * ($pcsPerMeterWidth * $pcsPerMeterHeight);
        
        if ($this->include_waste) {
            $totalPcs = $totalPcs * 1.05; // 5% waste
        }
        
        return ceil($totalPcs);
    }

    public function applyCalculatedQuantity()
    {
        $this->quantity = $this->calculatedRequirement;
        $this->dispatch('quantity-applied');
    }

    public function render()
    {
        $recommendedProducts = Product::viral()
            ->where('id', '!=', $this->product->id)
            ->take(4)
            ->get();

        // Dynamic meta description: prefer explicit meta_description, otherwise auto-generate
        $metaDescription = $this->product->meta_description
            ?? 'Jual ' . $this->product->name . ' berkualitas dengan harga terbaik di INDOROSTER. '
               . 'Roster beton minimalis premium langsung dari pabrik Plered Purwakarta. '
               . 'Gratis konsultasi, pengiriman seluruh Indonesia.';

        // Meta title: prefer explicit meta_title, otherwise auto-generate
        $metaTitle = $this->product->meta_title
            ?? $this->product->name . ' | INDOROSTER — Pabrik Roster Beton Plered';

        // OG Image from primary product image
        $ogImage = $this->product->primary_image ?? null;

        return view('livewire.product-detail', [
            'reviews'             => $this->reviews,
            'ratingStats'         => $this->ratingStats,
            'calculatedRequirement' => $this->calculatedRequirement,
            'recommendedProducts' => $recommendedProducts,
        ])->layout('components.layouts.app', [
            'title'       => $metaTitle,
            'description' => $metaDescription,
            'ogImage'     => $ogImage,
            'ogType'      => 'product',
        ]);
    }
}
