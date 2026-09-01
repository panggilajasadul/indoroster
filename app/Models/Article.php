<?php

namespace App\Models;

use App\Http\Controllers\SitemapController;
use App\Services\ImageOptimizationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $fillable = [
        'article_category_id',
        'title',
        'slug',
        'thumbnail',
        'thumbnail_alt',
        'excerpt',
        'content',
        'tags',
        'author_name',
        'views_count',
        'reading_time',
        'is_published',
        'is_featured',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
            'views_count' => 'integer',
            'reading_time' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Article $article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            } else {
                $article->slug = Str::slug($article->slug);
            }

            if (empty($article->published_at) && $article->is_published) {
                $article->published_at = now();
            }

            if (empty($article->reading_time)) {
                $wordCount = str_word_count(strip_tags($article->content ?? ''));
                $article->reading_time = max(1, (int) ceil($wordCount / 180));
            }
        });

        static::updating(function (Article $article) {
            if (! empty($article->slug)) {
                $article->slug = Str::slug($article->slug);
            }

            if (empty($article->reading_time)) {
                $wordCount = str_word_count(strip_tags($article->content ?? ''));
                $article->reading_time = max(1, (int) ceil($wordCount / 180));
            }
        });

        static::saving(function (Article $article) {
            if (! empty($article->thumbnail) && ! str_starts_with($article->thumbnail, 'http')) {
                $optimized = app(ImageOptimizationService::class)->optimizeExistingFile($article->thumbnail);
                if ($optimized) {
                    $article->thumbnail = $optimized;
                }
            }
        });

        // Trigger sitemap update on save/delete
        $sitemapGenerator = function () {
            try {
                SitemapController::generate();
            } catch (\Exception $e) {
                // Silently ignore errors in test/console environments
            }
        };

        static::saved($sitemapGenerator);
        static::deleted($sitemapGenerator);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('excerpt', 'like', "%{$term}%")
                ->orWhere('content', 'like', "%{$term}%");
        });
    }

    /**
     * Get the full URL for the article thumbnail with fallback.
     */
    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (empty($this->thumbnail)) {
                    return asset('assets/logo_indoroster_no_text.PNG');
                }

                if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
                    return $this->thumbnail;
                }

                return asset('storage/'.$this->thumbnail);
            }
        );
    }
}
