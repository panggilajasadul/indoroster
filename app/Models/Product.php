<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'short_description',
        'material',
        'dimensions',
        'weight',
        'price',
        'original_price',
        'min_order',
        'stock',
        'is_featured',
        'is_active',
        'best_for',
        'meta_title',
        'meta_description',
        // SEO Growth Engine fields
        'focus_keyword',
        'secondary_keywords',
        'seo_h1',
        'og_title',
        'og_description',
        'seo_score',
        'opportunity_score',
        'seo_issues',
        'seo_last_analyzed',
        'view_count',
        'total_sold',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'original_price' => 'decimal:2',
            'weight' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'total_sold' => 'integer',
            // SEO Growth Engine casts
            'secondary_keywords' => 'array',
            'seo_issues' => 'array',
            'seo_score' => 'integer',
            'opportunity_score' => 'integer',
            'seo_last_analyzed' => 'datetime',
        ];
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class)->where('is_approved', true);
    }

    public function getAverageRatingAttribute(): float
    {
        return (float) round($this->approvedReviews()->avg('rating') ?? 5, 1);
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            } else {
                $product->slug = Str::slug($product->slug);
            }
        });

        static::updating(function (Product $product) {
            if (! empty($product->slug)) {
                $product->slug = Str::slug($product->slug);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function media(): HasMany
    {
        return $this->hasMany(ProductMedia::class)->orderBy('sort_order');
    }

    public function videoInspirations(): HasMany
    {
        return $this->hasMany(Gallery::class)->where('category', 'video-inspirasi');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get primary media object.
     */
    public function getPrimaryMediaAttribute(): ?ProductMedia
    {
        return $this->media()->where('is_primary', true)->first()
            ?? $this->media()->first();
    }

    /**
     * Get primary image URL (always returns an image, not video).
     */
    public function getPrimaryImageAttribute(): ?string
    {
        // First try: primary image media
        $imageMedia = $this->media()->where('is_primary', true)->where('media_type', 'image')->first();

        // Second try: any image media
        if (! $imageMedia) {
            $imageMedia = $this->media()->where('media_type', 'image')->first();
        }

        // Third try: primary media (could be video — use its formatted_url as fallback)
        if ($imageMedia) {
            return $imageMedia->formatted_url;
        }

        // Last resort: return formatted_url of whatever primary media is
        $media = $this->primary_media;

        return $media ? $media->formatted_url : null;
    }

    /**
     * Check if product has discount.
     */
    public function getHasDiscountAttribute(): bool
    {
        return $this->original_price !== null && $this->original_price > $this->min_price;
    }

    /**
     * Get discount percentage.
     */
    public function getDiscountPercentageAttribute(): int
    {
        if (! $this->has_discount) {
            return 0;
        }

        return (int) round((($this->original_price - $this->min_price) / $this->original_price) * 100);
    }

    /**
     * Check if product has video media.
     */
    public function getHasVideoAttribute(): bool
    {
        return $this->media()->where('media_type', 'video')->exists();
    }

    public function getStockStatusLabelAttribute(): string
    {
        return $this->total_stock > 0 ? 'Tersedia' : 'Stok Habis';
    }

    /**
     * Get total stock from active variants or base stock.
     */
    public function getTotalStockAttribute(): int
    {
        $activeVariants = $this->relationLoaded('variants')
            ? $this->variants->where('is_active', true)
            : $this->variants()->where('is_active', true)->get();

        if ($activeVariants->count() > 0) {
            return (int) $activeVariants->sum('stock');
        }

        return (int) ($this->stock ?? 0);
    }

    /**
     * Get minimum price across active variants or base price.
     */
    public function getMinPriceAttribute(): float
    {
        $basePrice = (float) ($this->price ?? 0);
        $variants = $this->relationLoaded('variants')
            ? $this->variants->where('is_active', true)
            : $this->variants()->where('is_active', true)->get();

        if ($variants->count() > 0) {
            $minAdjustment = $variants->min('price_adjustment') ?? 0;

            return $basePrice + (float) $minAdjustment;
        }

        return $basePrice;
    }

    /**
     * Get maximum price across active variants or base price.
     */
    public function getMaxPriceAttribute(): float
    {
        $basePrice = (float) ($this->price ?? 0);
        $variants = $this->relationLoaded('variants')
            ? $this->variants->where('is_active', true)
            : $this->variants()->where('is_active', true)->get();

        if ($variants->count() > 0) {
            $maxAdjustment = $variants->max('price_adjustment') ?? 0;

            return $basePrice + (float) $maxAdjustment;
        }

        return $basePrice;
    }

    /**
     * Get formatted price range for display.
     */
    public function getFormattedPriceRangeAttribute(): string
    {
        $min = $this->min_price;
        $max = $this->max_price;

        if ($min === $max) {
            return 'Rp'.number_format($min, 0, ',', '.');
        }

        return 'Rp'.number_format($min, 0, ',', '.').' - Rp'.number_format($max, 0, ',', '.');
    }

    /**
     * Get formatted total sold count.
     * If >= 10000, abbreviate using "rb". E.g., 13777 -> 13,8rb (rounded)
     */
    public function getFormattedTotalSoldAttribute(): string
    {
        $sold = $this->total_sold ?? 0;
        if ($sold >= 10000) {
            $value = $sold / 1000;
            $formatted = number_format($value, 1, ',', '');
            if (str_ends_with($formatted, ',0')) {
                $formatted = substr($formatted, 0, -2);
            }

            return $formatted.'rb';
        }

        return number_format($sold, 0, ',', '.');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeViral($query)
    {
        return $query->active()
            ->with(['media', 'variants', 'category'])
            ->withCount(['approvedReviews' => function ($q) {
                $q->where('is_approved', true);
            }])
            ->orderByDesc('total_sold')
            ->orderByDesc('approved_reviews_count');
    }

    /**
     * Parse dimensions string (e.g. "20 x 20 x 10") into numeric cm.
     * Default to 20x20 if parsing fails.
     */
    public function getParsedDimensionsAttribute(): array
    {
        $dims = $this->dimensions ?? '20 x 20';
        // Match numbers using regex
        preg_match_all('/\d+/', $dims, $matches);

        $numbers = $matches[0] ?? [];

        return [
            'width' => isset($numbers[0]) ? (int) $numbers[0] : 20,
            'height' => isset($numbers[1]) ? (int) $numbers[1] : 20,
            'depth' => isset($numbers[2]) ? (int) $numbers[2] : 10,
        ];
    }
}
