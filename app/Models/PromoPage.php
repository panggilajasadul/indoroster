<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PromoPage extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'meta_title',
        'meta_description',
        'robots',
        'default_city',
        'whatsapp_number',
        'sections',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sections' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (PromoPage $page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            } else {
                $page->slug = Str::slug($page->slug);
            }
        });

        static::updating(function (PromoPage $page) {
            if (! empty($page->slug)) {
                $page->slug = Str::slug($page->slug);
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Ambil konfigurasi section tertentu dengan fallback aman.
     */
    public function getSection(string $key, mixed $default = null): mixed
    {
        return $this->sections[$key] ?? $default;
    }
}
