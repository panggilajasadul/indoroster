<?php

namespace App\Models;

use App\Services\ImageOptimizationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_url',
        'sort_order',
        'is_active',
        // SEO fields
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            } else {
                $category->slug = Str::slug($category->slug);
            }
        });

        static::updating(function (Category $category) {
            if (! empty($category->slug)) {
                $category->slug = Str::slug($category->slug);
            }
        });

        static::saving(function (Category $category) {
            if (! empty($category->image_url) && ! str_starts_with($category->image_url, 'http')) {
                $optimized = app(ImageOptimizationService::class)->optimizeExistingFile($category->image_url);
                if ($optimized) {
                    $category->image_url = $optimized;
                }
            }
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope: only active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
