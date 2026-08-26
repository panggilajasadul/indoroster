<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class ShippingLabel extends Model
{
    protected $fillable = [
        'order_id',
        'label_number',
        'courier',
        'service_type',
        'tracking_number',
        'sender_name',
        'sender_phone',
        'sender_address',
        'recipient_name',
        'recipient_phone',
        'recipient_address',
        'recipient_city',
        'recipient_postal_code',
        'recipient_latitude',
        'recipient_longitude',
        'total_items',
        'total_weight',
        'total_packages',
        'package_description',
        'special_instructions',
        'printed_at',
    ];

    protected function casts(): array
    {
        return [
            'total_weight' => 'decimal:2',
            'recipient_latitude' => 'float',
            'recipient_longitude' => 'float',
            'printed_at' => 'datetime',
        ];
    }

    /**
     * Generate unique shipping label number.
     */
    public static function generateLabelNumber(): string
    {
        $date = now()->format('Ymd');
        $lastLabel = static::whereDate('created_at', today())
            ->orderByDesc('id')
            ->first();

        $sequence = $lastLabel
            ? (int) substr($lastLabel->label_number, -4) + 1
            : 1;

        return 'SHP-'.$date.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Mark as printed.
     */
    public function markAsPrinted(): void
    {
        $this->printed_at = Carbon::now();
        $this->save();
    }

    /**
     * Default sender info from Indoroster factory.
     */
    public static function getDefaultSender(): array
    {
        return [
            'sender_name' => SiteSetting::getValue('site_name', 'Indoroster'),
            'sender_phone' => SiteSetting::getValue('whatsapp_number', '6281389709847'),
            'sender_address' => SiteSetting::getValue(
                'factory_address',
                'Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165'
            ),
        ];
    }
}
