<?php

namespace App\Livewire;

use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Livewire\Component;
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
            ->withCount(['approvedReviews' => function ($query) {
                $query->where('is_approved', true);
            }])
            ->firstOrFail();

        // Prioritize video: if product has any video, show it first
        $videoMedia = $this->product->media->firstWhere('media_type', 'video');
        if ($videoMedia) {
            $this->activeImage = str_starts_with($videoMedia->media_url, 'http')
                ? $videoMedia->media_url
                : asset('storage/'.$videoMedia->media_url);
            $this->activeMediaType = 'video';
        } else {
            $this->activeImage = $this->product->primary_image;
            $this->activeMediaType = 'image';
        }
        $this->quantity = $this->product->min_order > 0 ? $this->product->min_order : 1;
        // Tampilkan gambar varian jika varian terpilih di URL query
        if ($this->selectedVariant) {
            $variant = $this->product->variants->where('is_active', true)->firstWhere('id', (int) $this->selectedVariant);
            if ($variant && $variant->image_url) {
                $this->activeImage = str_starts_with($variant->image_url, 'http')
                    ? $variant->image_url
                    : asset('storage/'.$variant->image_url);
                $this->activeMediaType = 'image';
            } elseif (! $variant) {
                $this->selectedVariant = null;
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

        ProductReview::create([
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
        $query = ProductReview::where('product_id', $this->product->id)
            ->where('is_approved', true)
            ->latest();

        if ($this->ratingFilter > 0) {
            $query->where('rating', $this->ratingFilter);
        }

        return $query->paginate($this->reviewPage * $this->reviewsPerPage, ['*'], 'review_page');
    }

    public function getRatingStatsProperty()
    {
        $ratingCounts = ProductReview::where('product_id', $this->product->id)
            ->where('is_approved', true)
            ->selectRaw('rating, count(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating');

        $total = $ratingCounts->sum();
        $stats = [];

        for ($i = 5; $i >= 1; $i--) {
            $count = (int) ($ratingCounts->get($i) ?? 0);
            $percent = $total > 0 ? round(($count / $total) * 100) : 0;

            $stats[$i] = [
                'count' => $count,
                'percent' => $percent,
                'percentage' => $percent,
            ];
        }

        return [
            'total' => $total,
            'stats' => $stats,
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
                : asset('storage/'.$variant->image_url);
            $this->activeMediaType = 'image';
        }
    }

    public function incrementQuantity()
    {
        $this->quantity++;
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function updatedQuantity($value)
    {
        if (is_numeric($value) && (int) $value < 1) {
            $this->quantity = 1;
        }
    }

    public function getPriceRangeProperty()
    {
        return $this->product->formatted_price_range;
    }

    public function getActivePriceProperty()
    {
        if ($this->selectedVariant) {
            $variant = $this->product->variants->where('is_active', true)->firstWhere('id', (int) $this->selectedVariant);

            return $variant ? (float) $variant->final_price : (float) $this->product->price;
        }

        return (float) $this->product->price;
    }

    public function getActiveStockProperty()
    {
        $activeVariants = $this->product->variants->where('is_active', true);
        if ($activeVariants->count() > 0) {
            if ($this->selectedVariant) {
                $variant = $activeVariants->firstWhere('id', (int) $this->selectedVariant);

                return $variant ? $variant->stock : 0;
            }

            return $activeVariants->sum('stock');
        }

        return $this->product->stock;
    }

    public function getOrderModeProperty(): string
    {
        return SiteSetting::getValue('order_mode', 'midtrans');
    }

    public function getWhatsAppOrderUrlProperty(): string
    {
        $phone = SiteSetting::getValue('order_wa_number', '081389709847');
        $phone = preg_replace('/[^0-9]/', '', (string) $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            $phone = '62'.$phone;
        }

        $template = SiteSetting::getValue('order_wa_template_product', "Halo Admin IndoRoster, saya ingin memesan:\n• Produk: {product_name}\n• Varian: {variant}\n• Harga Satuan: {unit_price}\n• Jumlah: {qty} pcs\n• Estimasi Total: {total_price}\n• Link: {product_url}\n\nMohon info ketersediaan stok & perkiraan ongkos kirim ke lokasi saya. Terima kasih.");

        $variantName = '-';
        if ($this->selectedVariant) {
            $variant = $this->product->variants->where('is_active', true)->firstWhere('id', (int) $this->selectedVariant);
            $variantName = $variant ? $variant->name : '-';
        }

        $price = $this->activePrice;
        $unitPrice = 'Rp'.number_format($price, 0, ',', '.');
        $totalPrice = 'Rp'.number_format($price * $this->quantity, 0, ',', '.');
        $productUrl = route('product.detail', $this->product->slug);

        $message = str_replace(
            ['{product_name}', '{variant}', '{price}', '{unit_price}', '{qty}', '{total_price}', '{product_url}'],
            [$this->product->name, $variantName, $unitPrice, $unitPrice, $this->quantity, $totalPrice, $productUrl],
            $template
        );

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($message);
    }

    public function orderWhatsApp()
    {
        $activeVariants = $this->product->variants->where('is_active', true);
        if ($activeVariants->count() > 0 && ! $this->selectedVariant) {
            $this->dispatch('open-warning-modal', [
                'title' => 'Pilih Varian',
                'message' => 'Silakan pilih opsi varian produk terlebih dahulu sebelum memesan via WhatsApp.',
            ]);

            return;
        }

        $min = $this->product->min_order > 0 ? $this->product->min_order : 1;
        if ($this->quantity < $min) {
            $this->dispatch('open-warning-modal', [
                'title' => 'Minimal Order Produk',
                'message' => 'Minimal pembelian untuk produk '.$this->product->name.' adalah '.$min.' pcs.',
            ]);

            return;
        }

        $this->dispatch('open-external-url', url: $this->whatsAppOrderUrl);
    }

    public function addToCart()
    {
        $activeVariants = $this->product->variants->where('is_active', true);
        if ($activeVariants->count() > 0) {
            if (! $this->selectedVariant) {
                $this->dispatch('open-warning-modal', [
                    'title' => 'Pilih Varian',
                    'message' => 'Silakan pilih opsi varian produk terlebih dahulu sebelum menambahkan ke keranjang.',
                ]);

                return false;
            }

            $variant = $activeVariants->firstWhere('id', (int) $this->selectedVariant);
            if (! $variant) {
                $this->dispatch('open-warning-modal', [
                    'title' => 'Varian Tidak Aktif',
                    'message' => 'Varian yang Anda pilih saat ini sedang tidak aktif / tidak tersedia.',
                ]);

                return false;
            }
        }

        if ($this->getActiveStockProperty() < $this->quantity) {
            $this->dispatch('open-warning-modal', [
                'title' => 'Stok Tidak Mencukupi',
                'message' => 'Jumlah yang Anda pilih melebihi stok yang tersedia.',
            ]);

            return false;
        }

        $min = $this->product->min_order > 0 ? $this->product->min_order : 1;
        if ($this->quantity < $min) {
            $this->dispatch('open-warning-modal', [
                'title' => 'Minimal Order Produk',
                'message' => 'Minimal pembelian untuk produk '.$this->product->name.' adalah '.$min.' pcs.',
            ]);

            return false;
        }

        // Get or set session_id for guest cart
        $sessionId = Cookie::get('cart_session_id');
        if (! $sessionId) {
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
        $activeVariants = $this->product->variants->where('is_active', true);
        if ($activeVariants->count() > 0) {
            if (! $this->selectedVariant) {
                $this->dispatch('open-warning-modal', [
                    'title' => 'Pilih Varian',
                    'message' => 'Silakan pilih opsi varian produk terlebih dahulu sebelum membeli.',
                ]);

                return false;
            }

            $variant = $activeVariants->firstWhere('id', (int) $this->selectedVariant);
            if (! $variant) {
                $this->dispatch('open-warning-modal', [
                    'title' => 'Varian Tidak Aktif',
                    'message' => 'Varian yang Anda pilih saat ini sedang tidak aktif / tidak tersedia.',
                ]);

                return false;
            }
        }

        if ($this->getActiveStockProperty() < $this->quantity) {
            $this->dispatch('open-warning-modal', [
                'title' => 'Stok Tidak Mencukupi',
                'message' => 'Jumlah yang Anda pilih melebihi stok yang tersedia.',
            ]);

            return false;
        }

        $min = $this->product->min_order > 0 ? $this->product->min_order : 1;
        if ($this->quantity < $min) {
            $this->dispatch('open-warning-modal', [
                'title' => 'Minimal Order Produk',
                'message' => 'Minimal pembelian untuk produk '.$this->product->name.' adalah '.$min.' pcs.',
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

        if ($widthCm <= 0 || $heightCm <= 0) {
            return 0;
        }

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
        $calculated = (int) $this->calculatedRequirement;
        if ($calculated > 0) {
            $this->quantity = $calculated;
        }
    }

    public function getRecommendedProductsProperty()
    {
        $currentProduct = $this->product;
        $currentDims = $currentProduct->dimensions;
        $currentCategoryId = $currentProduct->category_id;

        // Tentukan tipe sisi (Satu Sisi / Dua Sisi) jika ada
        $currentType = '';
        if (preg_match('/\((.*?)\)/', $currentProduct->name, $matches)) {
            $currentType = $matches[1];
        }

        $collectedIds = [$currentProduct->id];
        $recommendations = collect();

        // TIER 1: Motif dengan Ukuran & Tipe Sisi yang Sama Persis
        if ($currentDims) {
            $tier1Query = Product::where('is_active', true)
                ->whereNotIn('id', $collectedIds)
                ->where('dimensions', $currentDims)
                ->with(['category', 'media', 'variants']);

            if (! empty($currentType)) {
                $tier1Query->where('name', 'like', "%({$currentType})%");
            }

            $tier1 = $tier1Query->orderByDesc('total_sold')->take(6)->get();
            foreach ($tier1 as $item) {
                $recommendations->push($item);
                $collectedIds[] = $item->id;
            }
        }

        // TIER 2: Motif dalam Kategori Sama (jika belum mencapai 6 produk)
        if ($recommendations->count() < 6 && $currentCategoryId) {
            $needed = 6 - $recommendations->count();
            $tier2 = Product::where('is_active', true)
                ->whereNotIn('id', $collectedIds)
                ->where('category_id', $currentCategoryId)
                ->with(['category', 'media', 'variants'])
                ->orderByDesc('total_sold')
                ->take($needed)
                ->get();

            foreach ($tier2 as $item) {
                $recommendations->push($item);
                $collectedIds[] = $item->id;
            }
        }

        // TIER 3: Fallback Popularitas / Viral (jika masih kurang dari 6 produk)
        if ($recommendations->count() < 6) {
            $needed = 6 - $recommendations->count();
            $tier3 = Product::where('is_active', true)
                ->whereNotIn('id', $collectedIds)
                ->with(['category', 'media', 'variants'])
                ->orderByDesc('total_sold')
                ->take($needed)
                ->get();

            foreach ($tier3 as $item) {
                $recommendations->push($item);
                $collectedIds[] = $item->id;
            }
        }

        return $recommendations;
    }

    public function render()
    {
        $recommendedProducts = $this->recommendedProducts;

        // Meta title: prefer explicit meta_title, otherwise auto-generate
        $metaTitle = $this->product->meta_title
            ?? $this->product->name.' | INDOROSTER — Pabrik Roster Beton Plered';

        // Meta description: prefer explicit, otherwise auto-generate
        $metaDescription = $this->product->meta_description
            ?? 'Jual '.$this->product->name.' harga pabrik termurah kualitas cetak padat dan presisi. Roster beton minimalis modern presisi untuk pagar rumah, fasad dinding, ventilasi udara, dan sekat partisi. Pengiriman Cepat Jabodetabek, Jawa Barat & Ekspedisi Nasional. Garansi aman sampai tujuan!';

        // Canonical: selalu URL bersih tanpa ?variant untuk menghindari duplicate content
        $canonicalOverride = route('product.detail', $this->product->slug);

        // OG Image dari primary product image
        $ogImage = $this->product->primary_image ?? null;

        // OG title/description: bisa lebih panjang/berbeda dari meta untuk social sharing
        $ogTitle = $this->product->og_title ?? $metaTitle;
        $ogDescription = $this->product->og_description ?? $metaDescription;

        // Keywords per-produk: gabungkan nama + kategori + material + sinonim relevan
        $productName = strtolower($this->product->name);
        $categoryName = strtolower($this->product->category->name ?? 'roster beton');
        $materialStr = $this->product->material ? strtolower($this->product->material) : '';
        $dimensionsStr = $this->product->dimensions ?? '';

        // Base keywords dari focus_keyword jika ada, atau auto-generate
        if ($this->product->focus_keyword) {
            $baseKeywords = [$this->product->focus_keyword];
        } else {
            $baseKeywords = [$productName, $categoryName];
        }

        // Secondary keywords jika ada
        $secondaryKeywords = $this->product->secondary_keywords ?? [];

        // Sinonim roster/loster + keyword lokal & use cases selalu disertakan
        $synonymKeywords = [
            str_replace('roster', 'loster', $productName),
            'harga '.$productName,
            'jual '.$productName,
            $productName.' plered purwakarta',
            $productName.' jakarta',
            $productName.' bekasi',
            $productName.' tangerang',
            $productName.' bogor depok',
            $productName.' bandung jawa barat',
            'roster pagar minimalis',
            'roster fasad dinding',
            'roster partisi interior',
            'beli '.$categoryName,
            'toko '.$categoryName.' terdekat',
            'pabrik roster jabodetabek',
        ];

        $allKeywords = array_merge($baseKeywords, $secondaryKeywords, $synonymKeywords);
        $allKeywords = array_unique(array_filter($allKeywords));
        $keywords = implode(', ', array_slice($allKeywords, 0, 20));

        return view('livewire.product-detail', [
            'reviews' => $this->reviews,
            'ratingStats' => $this->ratingStats,
            'calculatedRequirement' => $this->calculatedRequirement,
            'recommendedProducts' => $recommendedProducts,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'ogImage' => $ogImage,
            'ogType' => 'product',
            'ogTitle' => $ogTitle,
            'ogDescription' => $ogDescription,
            'canonicalOverride' => $canonicalOverride,
            'keywords' => $keywords,
        ]);
    }
}
