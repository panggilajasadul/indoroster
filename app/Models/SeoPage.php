<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SeoPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'page_type',
        'primary_keyword',
        'secondary_keywords',
        'search_intent',
        'buyer_type',
        'project_type',
        'use_case',
        'seo_location_id',
        'location_name',
        'title',
        'meta_description',
        'og_title',
        'og_description',
        'og_image',
        'canonical_url',
        'noindex',
        'h1',
        'opening_text',
        'unique_value_proposition',
        'unique_evidence',
        'unique_angle',
        'cta_type',
        'cta_text',
        'cta_wa_message',
        'product_matching_rule',
        'product_ids',
        'parent_page_id',
        'related_page_ids',
        'structured_data_type',
        'priority_score',
        'quality_score',
        'quality_details',
        'status',
        'published_at',
        'last_reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'secondary_keywords' => 'array',
        'product_ids' => 'array',
        'related_page_ids' => 'array',
        'quality_details' => 'array',
        'noindex' => 'boolean',
        'priority_score' => 'integer',
        'quality_score' => 'integer',
        'published_at' => 'datetime',
        'last_reviewed_at' => 'datetime',
    ];

    // ──────────────────────────────────────────────
    // Slug auto-generation
    // ──────────────────────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (SeoPage $page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->h1);
            } else {
                $page->slug = Str::slug($page->slug);
            }
        });

        static::updating(function (SeoPage $page) {
            if (! empty($page->slug)) {
                $page->slug = Str::slug($page->slug);
            }
        });
    }

    // ──────────────────────────────────────────────
    // Relasi
    // ──────────────────────────────────────────────

    public function sections()
    {
        return $this->hasMany(SeoPageSection::class)->orderBy('sort_order');
    }

    public function keywords()
    {
        return $this->hasMany(SeoKeyword::class, 'target_page_id');
    }

    public function seoLocation()
    {
        return $this->belongsTo(SeoLocation::class);
    }

    public function parentPage()
    {
        return $this->belongsTo(SeoPage::class, 'parent_page_id');
    }

    public function childPages()
    {
        return $this->hasMany(SeoPage::class, 'parent_page_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Relasi many-to-many ke produk via seo_page_products pivot.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'seo_page_products')
            ->withPivot('relevance_reason', 'sort_order')
            ->orderByPivot('sort_order');
    }

    // ──────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('noindex', false);
    }

    public function scopePublishable($query)
    {
        return $query->where('quality_score', '>=', 60)
            ->whereIn('status', ['ready', 'published']);
    }

    public function scopeByBuyer($query, string $buyerType)
    {
        return $query->where('buyer_type', $buyerType);
    }

    public function scopeByProject($query, string $projectType)
    {
        return $query->where('project_type', $projectType);
    }

    public function scopeByLocation($query, string $locationName)
    {
        return $query->where('location_name', $locationName);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPageType($query, string $pageType)
    {
        return $query->where('page_type', $pageType);
    }

    // ──────────────────────────────────────────────
    // Product Matching
    // ──────────────────────────────────────────────

    /**
     * Query produk yang relevan untuk halaman ini.
     * Prioritas: pivot table → product_matching_rule → product_ids → fallback
     */
    public function matchProducts(int $limit = 8)
    {
        // 1. Pivot relation (manual assignment via admin)
        $pivotProducts = $this->products()->with(['media', 'variants', 'category'])->get();
        if ($pivotProducts->isNotEmpty()) {
            return $pivotProducts->take($limit);
        }

        // 2. Product IDs (legacy/quick assignment)
        if (! empty($this->product_ids)) {
            return Product::whereIn('id', $this->product_ids)
                ->where('is_active', true)
                ->with(['media', 'variants', 'category'])
                ->limit($limit)
                ->get();
        }

        // 3. Product matching rule
        $rule = $this->product_matching_rule;
        if (! empty($rule)) {
            $query = Product::where('is_active', true)
                ->with(['media', 'variants', 'category']);

            if ($rule === 'all') {
                return $query->latest()->limit($limit)->get();
            }

            if ($rule === 'featured') {
                return $query->where('is_featured', true)->limit($limit)->get();
            }

            if (str_starts_with($rule, 'category:')) {
                $catSlug = str_replace('category:', '', $rule);

                return $query->whereHas('category', fn ($q) => $q->where('slug', $catSlug))
                    ->limit($limit)->get();
            }

            if (str_starts_with($rule, 'best_for:')) {
                $bestFor = str_replace('best_for:', '', $rule);

                return $query->where('best_for', 'like', "%{$bestFor}%")
                    ->limit($limit)->get();
            }
        }

        // 4. Default Curated Top Motifs: MMC, Petir, Nako Sipit, Nako LS, JaboL, PCL, Arrow, Batman
        $topMotifKeywords = ['MMC', 'Petir', 'Nako Sipit', 'Nako LS', 'JaboL', 'PCL', 'Arrow', 'Batman'];
        $topProducts = Product::where('is_active', true)
            ->where(function ($q) use ($topMotifKeywords) {
                foreach ($topMotifKeywords as $kw) {
                    $q->orWhere('name', 'like', "%{$kw}%");
                }
            })
            ->with(['media', 'variants', 'category'])
            ->limit($limit)
            ->get();

        if ($topProducts->isNotEmpty()) {
            return $topProducts;
        }

        // Fallback: featured atau terbaru
        return Product::where('is_active', true)
            ->where('is_featured', true)
            ->with(['media', 'variants', 'category'])
            ->limit($limit)
            ->get();
    }

    // ──────────────────────────────────────────────
    // CTA / WhatsApp Builder
    // ──────────────────────────────────────────────

    /**
     * Build URL WhatsApp dengan pesan custom berdasarkan buyer type.
     */
    public function buildWhatsAppUrl(): string
    {
        $rawWa = SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
        $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62'.substr($waNumber, 1);
        }

        $message = $this->cta_wa_message;
        if (empty($message)) {
            $message = $this->generateDefaultWaMessage();
        }

        return "https://wa.me/{$waNumber}?text=".urlencode($message);
    }

    /**
     * Generate pesan WA default berdasarkan buyer type dan page context.
     * MOQ verified: retail 1.000 pcs, grosir 5.000 pcs — berlaku semua motif (31 Agst 2026).
     */
    private function generateDefaultWaMessage(): string
    {
        $buyer = $this->buyer_type ?? 'umum';
        $location = $this->location_name ? " di {$this->location_name}" : '';

        $messages = [
            'kontraktor' => "Halo Tim Sales IndoRoster, saya Kontraktor ingin konsultasi kebutuhan roster beton untuk proyek{$location}. Mohon info ketersediaan produk, harga proyek, dan jadwal pengiriman. (Min. order proyek 1.000 pcs)",
            'developer' => "Halo Tim Sales IndoRoster, saya dari Developer perumahan{$location}. Ingin menanyakan ketersediaan roster beton untuk proyek perumahan kami, termasuk pilihan motif, penawaran volume, dan estimasi pengiriman. (Min. order 5.000 pcs untuk grosir)",
            'pemborong' => "Halo Tim Sales IndoRoster, saya Pemborong yang membutuhkan roster beton untuk proyek{$location}. Mohon info harga dan minimum pemesanan. (Min. 1.000 pcs retail / 5.000 pcs grosir)",
            'arsitek' => "Halo Tim IndoRoster, saya Arsitek yang sedang mencari roster beton untuk desain proyek{$location}. Boleh info katalog motif lengkap, spesifikasi teknis, dan opsi sample?",
            'procurement' => "Halo Tim Sales IndoRoster, saya dari bagian Pengadaan proyek{$location}. Mohon info proses request quotation, dokumen perusahaan (NIB, NPWP, SIUP), dan spesifikasi roster beton.",
        ];

        return $messages[$buyer] ?? "Halo Tim IndoRoster, saya tertarik dengan roster beton untuk kebutuhan proyek{$location}. Mohon info ketersediaan produk dan penawaran.";
    }

    // ──────────────────────────────────────────────
    // Related Pages
    // ──────────────────────────────────────────────

    /**
     * Ambil halaman terkait: parent, sibling, children, dan manual related.
     */
    public function getRelatedPages(int $limit = 6)
    {
        $ids = collect();

        // Parent
        if ($this->parent_page_id) {
            $ids->push($this->parent_page_id);
        }

        // Manual related
        if (! empty($this->related_page_ids)) {
            $ids = $ids->merge($this->related_page_ids);
        }

        // Siblings (same parent)
        if ($this->parent_page_id) {
            $siblingIds = self::where('parent_page_id', $this->parent_page_id)
                ->where('id', '!=', $this->id)
                ->where('status', 'published')
                ->pluck('id');
            $ids = $ids->merge($siblingIds);
        }

        // Children
        $childIds = self::where('parent_page_id', $this->id)
            ->where('status', 'published')
            ->pluck('id');
        $ids = $ids->merge($childIds);

        return self::whereIn('id', $ids->unique()->take($limit))
            ->where('status', 'published')
            ->get();
    }

    // ──────────────────────────────────────────────
    // Quality & Publishability
    // ──────────────────────────────────────────────

    /**
     * Cek apakah halaman layak dipublish.
     * Minimum: quality_score >= 60 DAN kriteria kritis >= 4/5.
     */
    public function isPublishable(): bool
    {
        if ($this->quality_score < 60) {
            return false;
        }

        $details = $this->quality_details ?? [];
        $criticalCriteria = ['search_intent_match', 'buyer_relevance', 'unique_information', 'factual_accuracy', 'conversion_clarity'];

        foreach ($criticalCriteria as $criteria) {
            if (($details[$criteria] ?? 0) < 4) {
                return false;
            }
        }

        return true;
    }

    // ──────────────────────────────────────────────
    // Status Helpers
    // ──────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'idea' => 'Ide',
            'research' => 'Riset',
            'approved' => 'Disetujui',
            'content_brief' => 'Content Brief',
            'draft' => 'Draft',
            'qa' => 'Quality Assurance',
            'needs_review' => 'Perlu Review',
            'ready' => 'Siap Publish',
            'published' => 'Published',
            'monitoring' => 'Monitoring',
            'update' => 'Perlu Update',
            'merge' => 'Perlu Merge',
            'noindex' => 'Noindex',
            'archived' => 'Diarsipkan',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'published', 'monitoring' => 'success',
            'ready' => 'info',
            'draft', 'qa', 'content_brief' => 'warning',
            'needs_review', 'update', 'merge' => 'danger',
            'archived', 'noindex' => 'gray',
            default => 'primary',
        };
    }
}
