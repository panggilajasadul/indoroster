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

    /**
     * Get up to 3 bank accounts for payments and official documents.
     */
    public static function getBankAccounts(): array
    {
        $val = static::getValue('bank_accounts');
        if ($val) {
            $decoded = is_array($val) ? $val : json_decode($val, true);
            if (is_array($decoded) && count($decoded) > 0) {
                return $decoded;
            }
        }

        return [
            [
                'bank_name' => 'Bank BRI (Bank Rakyat Indonesia)',
                'account_number' => '4356-01-009396-50-2',
                'account_name' => 'ABDUL HAMID',
                'is_active' => true,
            ],
            [
                'bank_name' => 'Bank BCA (Bank Central Asia)',
                'account_number' => '024-123-4567',
                'account_name' => 'ABDUL HAMID',
                'is_active' => true,
            ],
            [
                'bank_name' => 'Bank Mandiri',
                'account_number' => '131-00-1234567-8',
                'account_name' => 'INDOROSTER INDONESIA',
                'is_active' => true,
            ],
        ];
    }
}
