<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'shipping_email',
        'status',
        'payment_status',
        'subtotal',
        'shipping_cost',
        'discount_amount',
        'grand_total',
        'snap_token',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_province',
        'shipping_postal_code',
        'courier',
        'courier_phone',
        'tracking_number',
        'notes',
        'admin_notes',
        'paid_at',
        'shipped_at',
        'completed_at',
        'courier_id',
        'delivery_photo_path',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'paid_at' => 'datetime',
            'shipped_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Generate unique order number.
     */
    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = static::where('order_number', 'like', 'INV-' . $date . '-%')
            ->orderBy('order_number', 'desc')
            ->first();

        $sequence = $lastOrder
            ? (int) substr($lastOrder->order_number, -4) + 1
            : 1;

        return 'INV-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courierUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->orderByDesc('created_at');
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function shippingLabel(): HasOne
    {
        return $this->hasOne(ShippingLabel::class);
    }

    /**
     * Check if order is paid.
     */
    public function getIsPaidAttribute(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Get status label in Indonesian.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending_payment' => 'Menunggu Pembayaran',
            'paid' => 'Dibayar',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Diterima',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getWaProcessingLink(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->shipping_phone);
        // Format to standard 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        $text = "Halo {$this->shipping_name},\n\nTerima kasih telah berbelanja di Indoroster. Pesanan Anda dengan nomor *{$this->order_number}* sedang kami siapkan.\n\n*Rincian Pesanan:*\n";
        
        foreach ($this->items as $item) {
            $variantInfo = $item->product_variant_name ? " ({$item->product_variant_name})" : '';
            $text .= "- {$item->product_name}{$variantInfo} (x{$item->quantity})\n";
        }
        
        $text .= "\nEstimasi penyiapan pesanan adalah maksimal 3 hari kerja tergantung antrean pesanan.\n\nKami akan mengabari Anda kembali jika pesanan sudah siap dikirim.\n\nSalam,\nTim Indoroster";
        return 'https://wa.me/' . $phone . '?text=' . urlencode($text);
    }

    public function getWaShippedLink(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->shipping_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        $text = "Pesanan Sedang Dikirim! 🚚\n\n";
        $text .= "Halo {$this->shipping_name},\n";
        $text .= "Pesanan Anda dengan nomor order *{$this->order_number}* sudah siap dan telah diangkut ke armada pengiriman kami. Saat ini pesanan sedang dalam perjalanan menuju lokasi Anda.\n\n";
        
        $text .= "*Rincian Pesanan:*\n";
        foreach ($this->items as $item) {
            $variantInfo = $item->product_variant_name ? " ({$item->product_variant_name})" : '';
            $text .= "- {$item->product_name}{$variantInfo} (x{$item->quantity})\n";
        }
        $text .= "\n";
        
        $text .= "*Status Pengiriman:*\n";
        $text .= "Pesanan sudah keluar dari pabrik dan siap dikirim ke tujuan. Mohon pastikan nomor telepon Anda aktif agar kurir kami dapat menghubungi Anda saat sampai di lokasi.\n\n";
        
        $text .= "*Detail Pengiriman*\n";
        $text .= "Penerima: {$this->shipping_name}\n";
        $text .= "Alamat: {$this->shipping_address}, {$this->shipping_city}, {$this->shipping_province} {$this->shipping_postal_code}\n";
        
        if ($this->courier) {
            $text .= "Kurir: {$this->courier}\n";
            if ($this->courier_phone) {
                $text .= "No. WA Kurir: {$this->courier_phone}\n";
            }
            if ($this->tracking_number) {
                $text .= "No. Resi / Plat: {$this->tracking_number}\n";
            }
        } else {
            $text .= "Kurir: Armada Pabrik\n";
        }
        
        $text .= "Estimasi Sampai: 2-4 hari kerja (tergantung jarak lokasi).\n\n";
        $text .= "Terima kasih atas kepercayaannya telah berbelanja di Indoroster.\n\nSalam,\nTim Indoroster";
        
        return 'https://wa.me/' . $phone . '?text=' . urlencode($text);
    }
}
