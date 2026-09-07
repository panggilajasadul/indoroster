<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Get a setting value by key.
     */
    public static function getValue(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        return $setting?->value ?? $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function setValue(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get all settings for a specific group.
     */
    public static function getGroup(string $group): array
    {
        return static::where('group', $group)
            ->pluck('value', 'key')
            ->toArray();
    }

    /**
     * Check if product prices should be displayed on the storefront.
     */
    public static function showPrices(): bool
    {
        return filter_var(static::getValue('show_product_prices', true), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Get the fallback label/text when product prices are hidden.
     */
    public static function hiddenPriceText(): string
    {
        return (string) static::getValue('hidden_price_text', 'Minta Penawaran');
    }
}
