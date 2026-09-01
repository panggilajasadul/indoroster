<?php

namespace App\Models;

use App\Services\ImageOptimizationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductMedia extends Model
{
    protected $table = 'product_media';

    protected $fillable = [
        'product_id',
        'media_url',
        'media_type',
        'alt_text',
        'is_primary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (ProductMedia $media) {
            if ($media->media_type === 'image' && ! empty($media->media_url) && ! str_starts_with($media->media_url, 'http')) {
                // Auto optimize & convert to WebP if local image
                $optimized = app(ImageOptimizationService::class)->optimizeExistingFile($media->media_url);
                if ($optimized) {
                    $media->media_url = $optimized;
                }
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get formatted media URL (handle local storage vs external).
     */
    public function getFormattedUrlAttribute(): string
    {
        if (empty($this->media_url)) {
            return asset('assets/logo_indoroster_no_text.PNG');
        }

        return str_starts_with($this->media_url, 'http')
            ? $this->media_url
            : asset('storage/'.$this->media_url);
    }
}
