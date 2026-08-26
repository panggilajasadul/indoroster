<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'name',
        'badge_text',
        'type',
        'discount_amount',
        'min_order_amount',
        'min_order_qty',
        'allowed_regions',
        'description',
        'is_active',
        'valid_from',
        'valid_until',
    ];

    protected function casts(): array
    {
        return [
            'allowed_regions' => 'array',
            'is_active' => 'boolean',
            'discount_amount' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'min_order_qty' => 'integer',
            'valid_from' => 'datetime',
            'valid_until' => 'datetime',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('valid_from')->orWhere('valid_from', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('valid_until')->orWhere('valid_until', '>=', now());
            });
    }

    /**
     * Cek apakah wilayah/alamat pembeli memenuhi syarat voucher.
     */
    public function isEligibleForLocation(?string $location): bool
    {
        if (empty($this->allowed_regions) || count($this->allowed_regions) === 0) {
            return true;
        }

        if (empty($location)) {
            return true; // Default preview
        }

        $loc = strtolower($location);

        foreach ($this->allowed_regions as $region) {
            $reg = strtolower($region);
            if ($reg === 'nasional' || str_contains($loc, $reg) || str_contains($reg, $loc)) {
                return true;
            }
        }

        return false;
    }
}
