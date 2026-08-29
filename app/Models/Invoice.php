<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'order_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'subtotal',
        'shipping_cost',
        'discount_amount',
        'tax_amount',
        'grand_total',
        'payment_scheme',
        'down_payment_amount',
        'remaining_balance',
        'status',
        'notes',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'down_payment_amount' => 'decimal:2',
            'remaining_balance' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    /**
     * Generate unique invoice number.
     */
    public static function generateInvoiceNumber(): string
    {
        $year = now()->format('Y');
        $month = now()->format('m');
        $lastInvoice = static::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->where('invoice_number', 'not like', 'INV-WA-%')
            ->orderByDesc('id')
            ->first();

        $sequence = $lastInvoice
            ? (int) substr($lastInvoice->invoice_number, -4) + 1
            : 1;

        return 'INV/'.$year.'/'.$month.'/'.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate unique invoice number for WhatsApp orders.
     */
    public static function generateWaInvoiceNumber(): string
    {
        $date = now()->format('Ymd');
        $lastInvoice = static::where('invoice_number', 'like', 'INV-WA-'.$date.'-%')
            ->orderByDesc('id')
            ->first();

        $sequence = $lastInvoice
            ? (int) substr($lastInvoice->invoice_number, -4) + 1
            : 1;

        return 'INV-WA-'.$date.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get status label in Indonesian.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'sent' => 'Terkirim',
            'paid' => 'Lunas',
            'overdue' => 'Jatuh Tempo',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }
}
