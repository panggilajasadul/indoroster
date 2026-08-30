<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoKeyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword',
        'cluster',
        'intent',
        'buyer_type',
        'project_type',
        'location',
        'business_value',
        'conversion_potential',
        'search_volume_est',
        'competition',
        'priority_score',
        'status',
        'target_page_id',
        'source',
        'notes',
    ];

    protected $casts = [
        'business_value' => 'integer',
        'conversion_potential' => 'integer',
        'competition' => 'integer',
        'priority_score' => 'integer',
    ];

    // ──────────────────────────────────────────────
    // Relasi
    // ──────────────────────────────────────────────

    public function targetPage()
    {
        return $this->belongsTo(SeoPage::class, 'target_page_id');
    }

    // ──────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────

    public function scopeUnmapped($query)
    {
        return $query->whereNull('target_page_id');
    }

    public function scopeMapped($query)
    {
        return $query->whereNotNull('target_page_id');
    }

    public function scopeByCluster($query, string $cluster)
    {
        return $query->where('cluster', $cluster);
    }

    public function scopeByIntent($query, string $intent)
    {
        return $query->where('intent', $intent);
    }

    public function scopeHighPriority($query, int $minScore = 7)
    {
        return $query->where('priority_score', '>=', $minScore);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['archived']);
    }

    // ──────────────────────────────────────────────
    // Priority Score Calculation
    // ──────────────────────────────────────────────

    /**
     * Hitung priority_score berdasarkan weighted average.
     * Bobot: business_value (35%), conversion_potential (30%), competition_inverted (20%), search_volume (15%)
     */
    public function calculatePriorityScore(): int
    {
        $bv = $this->business_value ?? 3;
        $cp = $this->conversion_potential ?? 3;
        // Kompetisi: semakin rendah semakin bagus (invert 1-5 → 5-1)
        $compInverted = 6 - ($this->competition ?? 3);

        // Estimasi volume score (1-5 berdasarkan string)
        $vs = $this->estimateVolumeScore();

        $score = ($bv * 0.35) + ($cp * 0.30) + ($compInverted * 0.20) + ($vs * 0.15);

        // Normalisasi ke 1-10
        return (int) round($score * 2);
    }

    /**
     * Konversi search_volume_est string ke skor 1-5.
     */
    private function estimateVolumeScore(): int
    {
        $vol = strtolower($this->search_volume_est ?? '');

        if (str_contains($vol, '10k') || str_contains($vol, '100k')) {
            return 5;
        }
        if (str_contains($vol, '1k')) {
            return 4;
        }
        if (str_contains($vol, '100') || str_contains($vol, '500')) {
            return 3;
        }
        if (str_contains($vol, '10') || str_contains($vol, '50')) {
            return 2;
        }

        return 1; // unknown / very low
    }

    /**
     * Hitung dan simpan priority score.
     */
    public function updatePriorityScore(): self
    {
        $this->priority_score = $this->calculatePriorityScore();
        $this->save();

        return $this;
    }
}
