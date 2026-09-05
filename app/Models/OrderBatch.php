<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderBatch extends Model
{
    protected $fillable = [
        'order_id',
        'batch_number',
        'batch_name',
        'quantity',
        'production_start_date',
        'estimated_dispatch_date',
        'estimated_delivery_date',
        'actual_dispatch_date',
        'actual_delivered_date',
        'status',
        'courier_id',
        'courier_name',
        'courier_phone',
        'tracking_number',
        'notes',
        'delivery_photo_path',
        'source_type',
        'factory_name',
        'factory_pic_name',
        'factory_pic_phone',
        'factory_address',
        'pickup_driver_name',
        'pickup_driver_plate',
    ];

    protected $casts = [
        'batch_number' => 'integer',
        'quantity' => 'integer',
        'production_start_date' => 'date',
        'estimated_dispatch_date' => 'date',
        'estimated_delivery_date' => 'date',
        'actual_dispatch_date' => 'date',
        'actual_delivered_date' => 'date',
    ];

    protected static function booted()
    {
        static::saving(function ($batch) {
            if (empty($batch->batch_number) || $batch->batch_number <= 1) {
                if (preg_match('/\b(?:batch|rit|ke)\s*#?\s*(\d+)\b/i', $batch->batch_name ?? '', $m)) {
                    $batch->batch_number = (int) $m[1];
                }
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function courierUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    /** Apakah batch sudah terkirim / diterima? */
    public function isShipped(): bool
    {
        return in_array($this->status, ['shipped', 'delivered']);
    }

    /** Apakah surat jalan bisa dicetak? (hanya batch yang sudah dispatch) */
    public function canPrintSuratJalan(): bool
    {
        return $this->isShipped() && ! empty($this->tracking_number);
    }

    /** Apakah batch ini masih bisa dibeangkatkan? */
    public function canBeDispatched(): bool
    {
        return $this->status === 'ready_to_ship';
    }

    /** Aksi selanjutnya yang tersedia untuk batch ini */
    public function getNextActionAttribute(): ?string
    {
        return match ($this->status) {
            'pending_production' => 'start_production',
            'producing' => 'mark_ready',
            'ready_to_ship' => 'dispatch',
            'shipped' => 'mark_delivered',
            'delivered' => null,
            default => null,
        };
    }

    /** Label aksi selanjutnya */
    public function getNextActionLabelAttribute(): ?string
    {
        return match ($this->status) {
            'pending_production' => '🔨 Mulai Produksi',
            'producing' => '✅ Tandai Siap Kirim',
            'ready_to_ship' => '🚚 Berangkatkan Truk',
            'shipped' => '✅ Tandai Diterima',
            'delivered' => null,
            default => null,
        };
    }

    /** Ikon heroicon per status (untuk Filament) */
    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            'pending_production' => 'heroicon-o-clock',
            'producing' => 'heroicon-o-wrench-screwdriver',
            'ready_to_ship' => 'heroicon-o-cube',
            'shipped' => 'heroicon-o-truck',
            'delivered' => 'heroicon-o-check-badge',
            default => 'heroicon-o-question-mark-circle',
        };
    }

    /** Total kuantitas terencana/terkirim pada batch-batch sebelum nomor batch ini */
    public function getPreviousShippedQuantityAttribute(): int
    {
        if (! $this->order) {
            return 0;
        }

        return (int) $this->order->batches()
            ->where('batch_number', '<', $this->batch_number)
            ->sum('quantity');
    }

    /** Cumulative shipped quantity including this batch */
    public function getCumulativeShippedQuantityAttribute(): int
    {
        return $this->previous_shipped_quantity + $this->quantity;
    }

    /** Remaining quantity after this batch */
    public function getRemainingQuantityAfterThisBatchAttribute(): int
    {
        $totalOrdered = $this->order ? $this->order->total_ordered_quantity : 0;
        $remaining = $totalOrdered - $this->cumulative_shipped_quantity;

        return max(0, $remaining);
    }

    /** Status label Indonesia */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending_production' => '⏳ Menunggu Produksi',
            'producing' => '🔨 Sedang Diproduksi',
            'ready_to_ship' => '📦 Siap Dikirim',
            'shipped' => '🚚 Sedang Dikirim',
            'delivered' => '✅ Diterima di Proyek',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    /** Status badge color (Filament) */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending_production' => 'gray',
            'producing' => 'warning',
            'ready_to_ship' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            default => 'gray',
        };
    }

    /** Status badge color hex (untuk Blade manual) */
    public function getStatusHexColorAttribute(): string
    {
        return match ($this->status) {
            'pending_production' => '#6b7280',
            'producing' => '#d97706',
            'ready_to_ship' => '#2563eb',
            'shipped' => '#ea580c',
            'delivered' => '#16a34a',
            default => '#6b7280',
        };
    }
}
