<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'material_id',
        'name',
        'image_url',
        'sku',
        'price_adjustment',
        'stock',
        'weight',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_adjustment' => 'decimal:2',
            'weight' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Get the final price (product price + adjustment).
     */
    public function getFinalPriceAttribute(): float
    {
        return (float) (($this->product?->price ?? 0) + $this->price_adjustment);
    }
}
