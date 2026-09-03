<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApplicationPage extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'badge',
        'icon',
        'image',
        'meta_title',
        'meta_description',
        'keywords',
        'headline',
        'intro',
        'deep_narrative',
        'specs',
        'installation_guide',
        'design_tips',
        'benefits',
        'motifs',
        'gallery_images',
        'faqs',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'deep_narrative' => 'array',
            'specs' => 'array',
            'installation_guide' => 'array',
            'design_tips' => 'array',
            'benefits' => 'array',
            'motifs' => 'array',
            'gallery_images' => 'array',
            'faqs' => 'array',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ApplicationPage $page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });

        static::updating(function (ApplicationPage $page) {
            if (! empty($page->slug)) {
                $page->slug = Str::slug($page->slug);
            }
        });
    }
}
