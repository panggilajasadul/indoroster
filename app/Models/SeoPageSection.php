<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoPageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'seo_page_id',
        'section_type',
        'heading',
        'content',
        'sort_order',
        'is_visible',
        'source',
        'unique_angle',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
    ];

    // ──────────────────────────────────────────────
    // Relasi
    // ──────────────────────────────────────────────

    public function seoPage()
    {
        return $this->belongsTo(SeoPage::class);
    }

    // ──────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // ──────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────

    public function getSectionTypeLabelAttribute(): string
    {
        return match ($this->section_type) {
            'intro' => 'Pengantar',
            'problem' => 'Masalah Pembeli',
            'solution' => 'Solusi IndoRoster',
            'products' => 'Produk Relevan',
            'specs' => 'Spesifikasi',
            'usecase' => 'Aplikasi / Use Case',
            'process' => 'Cara Memesan',
            'shipping' => 'Pengiriman',
            'volume' => 'Kebutuhan Volume / MOQ',
            'pricing_guide' => 'Panduan Harga',
            'faq' => 'FAQ',
            'cta' => 'Call to Action',
            'related' => 'Halaman Terkait',
            'testimonial' => 'Testimoni',
            'calculator' => 'Kalkulator',
            'comparison' => 'Perbandingan',
            'custom' => 'Custom',
            default => ucfirst($this->section_type),
        };
    }
}
