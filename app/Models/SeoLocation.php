<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'city_code',
        'province_code',
        'parent_id',
        'latitude',
        'longitude',
        'priority',
        'seo_enabled',
        'seo_score',
        'meta_title',
        'meta_description',
        'headline',
        'intro_content',
        'delivery_route_info',
        'estimated_delivery_time',
        'shipping_guarantee_text',
        'target_districts',
        'custom_faqs',
        'recommended_motif_ids',
    ];

    protected $casts = [
        'seo_enabled' => 'boolean',
        'priority' => 'integer',
        'seo_score' => 'integer',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'target_districts' => 'array',
        'custom_faqs' => 'array',
        'recommended_motif_ids' => 'array',
    ];

    public function parent()
    {
        return $this->belongsTo(SeoLocation::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(SeoLocation::class, 'parent_id');
    }

    public function scopeActive($query)
    {
        return $query->where('seo_enabled', true);
    }

    public function scopePriorityFirst($query)
    {
        return $query->orderBy('priority', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Hitung Skor Kualitas SEO Otomatis (0 - 100)
     */
    public function calculateSeoScore(): int
    {
        $score = 0;

        // 1. Location Data lengkap (20 Poin)
        if (! empty($this->name) && ! empty($this->slug) && ! empty($this->province_code)) {
            $score += 10;
        }
        if (! empty($this->latitude) && ! empty($this->longitude)) {
            $score += 10;
        }

        // 2. Unique Content (20 Poin)
        if (! empty($this->intro_content) && strlen(strip_tags($this->intro_content)) > 150) {
            $score += 20;
        } elseif (! empty($this->intro_content)) {
            $score += 10;
        }

        // 3. Product Relevance (15 Poin)
        if (! empty($this->recommended_motif_ids) && count($this->recommended_motif_ids) >= 4) {
            $score += 15;
        } elseif (! empty($this->recommended_motif_ids)) {
            $score += 8;
        }

        // 4. Shipping & Delivery Info (10 Poin)
        if (! empty($this->delivery_route_info) && ! empty($this->estimated_delivery_time)) {
            $score += 10;
        } elseif (! empty($this->delivery_route_info) || ! empty($this->estimated_delivery_time)) {
            $score += 5;
        }

        // 5. Internal Linking & Target Districts (10 Poin)
        if (! empty($this->target_districts) && count($this->target_districts) >= 3) {
            $score += 10;
        }

        // 6. FAQ Relevan (10 Poin)
        if (! empty($this->custom_faqs) && count($this->custom_faqs) >= 2) {
            $score += 10;
        }

        // 7. Conversion CTA & Meta Setup (10 Poin)
        if (! empty($this->meta_title) && ! empty($this->meta_description)) {
            $score += 10;
        }

        // 8. Schema Integrity (5 Poin)
        $score += 5;

        return min($score, 100);
    }
}
