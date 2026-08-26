<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ProductReview extends Model
{
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    protected $fillable = [
        'product_id',
        'reviewer_name',
        'reviewer_location',
        'rating',
        'content',
        'images',
        'is_approved',
        'is_seeded',
        'views_count',
    ];

    protected $casts = [
        'rating' => 'integer',
        'images' => 'array',
        'is_approved' => 'boolean',
        'is_seeded' => 'boolean',
        'views_count' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function getMaskedNameAttribute(): string
    {
        $name = $this->reviewer_name;
        if (strlen($name) <= 2) {
            return $name;
        }

        $first = substr($name, 0, 1);
        $last = substr($name, -1);

        return $first.'***'.$last;
    }
}
