<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_variant_id',
        'is_custom_item',
        'product_name',
        'custom_variant_name',
        'product_price',
        'quantity',
        'subtotal',
        'item_notes',
    ];

    protected function casts(): array
    {
        return [
            'is_custom_item' => 'boolean',
            'product_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    protected static function booted(): void
    {
        static::saving(function (OrderItem $item) {
            if (empty($item->product_name) && $item->product_id) {
                $item->product_name = $item->product?->name ?? 'Produk Roster';
            }
            if (empty($item->custom_variant_name) && $item->product_variant_id) {
                $item->custom_variant_name = $item->variant?->name;
            }
        });
    }

    public function getProductVariantNameAttribute()
    {
        return $this->custom_variant_name ?: $this->variant?->name;
    }
}
