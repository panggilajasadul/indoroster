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
        'shipping_latitude',
        'shipping_longitude',
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
        'fulfillment_type',
        'requested_batch_delivery',
        'requested_batch_notes',
        'production_start_date',
        'ready_shipping_date',
        'estimated_delivery_date',
        'batch_count',
        'fulfillment_notes',
        'production_status',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'shipping_latitude' => 'float',
            'shipping_longitude' => 'float',
            'paid_at' => 'datetime',
            'shipped_at' => 'datetime',
            'completed_at' => 'datetime',
            'requested_batch_delivery' => 'boolean',
            'production_start_date' => 'date',
            'ready_shipping_date' => 'date',
            'estimated_delivery_date' => 'date',
            'batch_count' => 'integer',
        ];
    }

    /**
     * Generate unique order number.
     */
    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = static::where('order_number', 'like', 'INV-'.$date.'-%')
            ->orderBy('order_number', 'desc')
            ->first();

        $sequence = $lastOrder
            ? (int) substr($lastOrder->order_number, -4) + 1
            : 1;

        return 'INV-'.$date.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);
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

    public function batches(): HasMany
    {
        return $this->hasMany(OrderBatch::class)->orderBy('batch_number');
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
     * Get total quantity of all items ordered.
     */
    public function getTotalOrderedQuantityAttribute(): int
    {
        return (int) $this->items()->sum('quantity');
    }

    /**
     * Get total quantity already shipped across all batches.
     */
    public function getTotalShippedQuantityAttribute(): int
    {
        if ($this->fulfillment_type === 'po_batch') {
            return (int) $this->batches()->whereIn('status', ['shipped', 'delivered'])->sum('quantity');
        }

        return in_array($this->status, ['shipped', 'delivered', 'completed']) ? $this->total_ordered_quantity : 0;
    }

    /**
     * Get remaining quantity to be shipped.
     */
    public function getRemainingQuantityAttribute(): int
    {
        return max(0, $this->total_ordered_quantity - $this->total_shipped_quantity);
    }

    /**
     * Get batch progress percentage (0 - 100).
     */
    public function getBatchProgressPercentageAttribute(): float
    {
        if ($this->total_ordered_quantity <= 0) {
            return 0.0;
        }
        $pct = ($this->total_shipped_quantity / $this->total_ordered_quantity) * 100;

        return (float) round(min(100.0, $pct), 1);
    }

    /**
     * Get direct turn-by-turn navigation or search URL for driver/customer.
     */
    public function getGoogleMapsNavigationUrlAttribute(): string
    {
        if ($this->shipping_latitude && $this->shipping_longitude) {
            return "https://www.google.com/maps/dir/?api=1&destination={$this->shipping_latitude},{$this->shipping_longitude}";
        }

        $fullAddr = "{$this->shipping_address}, {$this->shipping_city}, {$this->shipping_province} {$this->shipping_postal_code}";

        return 'https://www.google.com/maps/search/?api=1&query='.urlencode($fullAddr);
    }

    /**
     * Check if this order is a batch fulfillment order.
     */
    public function getIsBatchOrderAttribute(): bool
    {
        return $this->fulfillment_type === 'po_batch';
    }

    /**
     * Check if all batches in this order have been shipped.
     */
    public function isAllBatchesShipped(): bool
    {
        if (! $this->is_batch_order) {
            return in_array($this->status, ['shipped', 'delivered', 'completed']);
        }
        $totalBatches = $this->batches()->count();
        if ($totalBatches === 0) {
            return false;
        }
        $shippedBatches = $this->batches()->whereIn('status', ['shipped', 'delivered'])->count();

        return $shippedBatches >= $totalBatches;
    }

    /**
     * Get human readable fulfillment type label.
     */
    public function getFulfillmentLabelAttribute(): string
    {
        return match ($this->fulfillment_type) {
            'ready_stock' => 'Ready Stock (Pabrik)',
            'po_single' => 'Pre-Order (PO Tunggal)',
            'po_batch' => 'PO Batch (Bertahap)',
            default => 'Standar',
        };
    }

    /**
     * Get badge color for fulfillment type.
     */
    public function getFulfillmentBadgeColorAttribute(): string
    {
        return match ($this->fulfillment_type) {
            'ready_stock' => 'success',
            'po_single' => 'warning',
            'po_batch' => 'info',
            default => 'gray',
        };
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
            $phone = '62'.substr($phone, 1);
        }
        $text = "Halo {$this->shipping_name},\n\nTerima kasih telah berbelanja di Indoroster. Pesanan Anda dengan nomor *{$this->order_number}* sedang kami siapkan.\n\n*Rincian Pesanan:*\n";

        foreach ($this->items as $item) {
            $variantInfo = $item->product_variant_name ? " ({$item->product_variant_name})" : '';
            $text .= "- {$item->product_name}{$variantInfo} (x{$item->quantity})\n";
        }

        $text .= "\nEstimasi penyiapan pesanan adalah maksimal 3 hari kerja tergantung antrean pesanan.\n\nKami akan mengabari Anda kembali jika pesanan sudah siap dikirim.\n\nSalam,\nTim Indoroster";

        return 'https://wa.me/'.$phone.'?text='.urlencode($text);
    }

    public function getWaShippedLink(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->shipping_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
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

        return 'https://wa.me/'.$phone.'?text='.urlencode($text);
    }

    public function getWaBatchShippedLink(OrderBatch $batch): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->shipping_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }
        $text = "🚚 Pengiriman Bertahap ({$batch->batch_name} dari {$this->batch_count} Batch) Sedang Menuju Lokasi Anda!\n\n";
        $text .= "Halo {$this->shipping_name},\n";
        $text .= "Armada pabrik Indoroster telah diberangkatkan untuk *{$batch->batch_name}* pesanan nomor *{$this->order_number}*.\n\n";

        $text .= "*Rincian Muatan Truk Ini:*\n";
        $text .= '- Jumlah Muatan: *'.number_format($batch->quantity, 0, ',', '.')." pcs*\n";
        if ($batch->courier_name) {
            $text .= "- Supir: {$batch->courier_name}\n";
        }
        if ($batch->courier_phone) {
            $text .= "- No. HP Supir: {$batch->courier_phone}\n";
        }
        if ($batch->tracking_number) {
            $text .= "- No. Plat Truk: {$batch->tracking_number}\n";
        }
        $text .= "\n";

        $text .= "*Rekapitulasi Progres Proyek:*\n";
        $text .= '• Total Pesanan: '.number_format($this->total_ordered_quantity, 0, ',', '.')." pcs\n";
        $text .= '• Total Terkirim s/d Tahap Ini: '.number_format($batch->cumulative_shipped_quantity, 0, ',', '.')." pcs\n";
        $text .= '• Sisa Belum Terkirim: '.number_format($batch->remaining_quantity_after_this_batch, 0, ',', '.')." pcs\n\n";

        $text .= 'Lacak progres lengkap: '.route('order.tracking')."?order_number={$this->order_number}\n\n";
        $text .= "Mohon pastikan area proyek siap untuk penerimaan dan bongkar muat.\n\nSalam,\nTim Logistik Indoroster";

        return 'https://wa.me/'.$phone.'?text='.urlencode($text);
    }
}
