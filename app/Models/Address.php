<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'phone',
        'province',
        'city',
        'district',
        'village',
        'postal_code',
        'full_address',
        'truck_access_notes',
        'latitude',
        'longitude',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get full formatted address string.
     */
    public function getFormattedAddressAttribute(): string
    {
        $parts = array_filter([
            $this->full_address,
            $this->village ? 'Kel. '.$this->village : null,
            $this->district ? 'Kec. '.$this->district : null,
            $this->city,
            $this->province,
        ]);
        $str = implode(', ', $parts);
        if ($this->postal_code) {
            $str .= ' '.$this->postal_code;
        }

        return $str;
    }

    /**
     * Get direct Google Maps URL for this address.
     */
    public function getGoogleMapsUrlAttribute(): string
    {
        if ($this->latitude && $this->longitude) {
            return "https://www.google.com/maps?q={$this->latitude},{$this->longitude}";
        }

        return 'https://www.google.com/maps/search/?api=1&query='.urlencode($this->formatted_address);
    }
}
