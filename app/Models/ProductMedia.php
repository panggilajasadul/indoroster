<?php

namespace App\Models;

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
            return asset('images/placeholder.png');
        }

        return str_starts_with($this->media_url, 'http')
            ? $this->media_url
            : asset('storage/'.$this->media_url);
    }
}
