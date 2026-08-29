<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_type',
        'bank',
        'va_number',
        'gross_amount',
        'status',
        'fraud_status',
        'payment_url',
        'raw_response',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'gross_amount' => 'decimal:2',
            'raw_response' => 'json',
            'paid_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getPaymentTypeLabelAttribute(): string
    {
        return match ($this->payment_type) {
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'qris' => 'QRIS',
            'bank_transfer' => 'Transfer Bank ('.strtoupper($this->bank ?? '').')',
            'echannel' => 'Transfer Bank (MANDIRI)',
            'credit_card' => 'Kartu Kredit',
            'cstore' => 'Minimarket',
            'manual' => 'Bayar via Midtrans',
            default => $this->payment_type ?? '-',
        };
    }

    public function getAmountAttribute(): float
    {
        return (float) ($this->gross_amount ?? 0);
    }

    /**
     * Check if payment is successful.
     */
    public function getIsSuccessAttribute(): bool
    {
        return in_array($this->status, ['settlement', 'capture', 'paid', 'success']);
    }

    public function getNotesAttribute(): ?string
    {
        return $this->raw_response['notes'] ?? null;
    }

    public function getInstallmentTitleAttribute(): string
    {
        return $this->raw_response['title'] ?? ('Pembayaran #'.$this->id);
    }

    public function getReceiptNumberAttribute(): string
    {
        return 'KW-'.($this->transaction_id ?: ($this->order ? $this->order->order_number.'-'.$this->id : $this->id));
    }
}
