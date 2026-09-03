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
        'order_source',
        'payment_method',
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
        'shipping_district',
        'shipping_village',
        'shipping_province',
        'shipping_postal_code',
        'shipping_latitude',
        'shipping_longitude',
        'payment_scheme',
        'down_payment_amount',
        'remaining_balance',
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
            'down_payment_amount' => 'decimal:2',
            'remaining_balance' => 'decimal:2',
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
     * Generate unique order number for online web orders.
     */
    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = static::where('order_number', 'like', 'INV-'.$date.'-%')
            ->where('order_source', '!=', 'whatsapp')
            ->orderBy('order_number', 'desc')
            ->first();

        $sequence = $lastOrder
            ? (int) substr($lastOrder->order_number, -4) + 1
            : 1;

        return 'INV-'.$date.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Generate unique order number for WhatsApp orders.
     */
    public static function generateWaOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = static::where('order_number', 'like', 'INV-WA-'.$date.'-%')
            ->orderBy('order_number', 'desc')
            ->first();

        $sequence = $lastOrder
            ? (int) substr($lastOrder->order_number, -4) + 1
            : 1;

        return 'INV-WA-'.$date.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);
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

    public function getValidPayments()
    {
        return $this->payments()->whereIn('status', ['settlement', 'capture', 'paid', 'success'])->orderBy('paid_at', 'asc')->orderBy('id', 'asc')->get();
    }

    public function getTotalPaidAmountAttribute(): float
    {
        $paymentsSum = (float) $this->payments()->whereIn('status', ['settlement', 'capture', 'paid', 'success'])->sum('gross_amount');
        if ($paymentsSum > 0) {
            return $paymentsSum;
        }

        return (float) ($this->down_payment_amount ?: ($this->payment_status === 'paid' ? $this->grand_total : 0));
    }

    /**
     * Get total quantity of all items ordered.
     */
    public function getTotalOrderedQuantityAttribute(): int
    {
        if ($this->relationLoaded('items')) {
            return (int) $this->items->sum('quantity');
        }

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

        return 'https://www.google.com/maps/search/?api=1&query='.urlencode($this->full_shipping_address);
    }

    /**
     * Get complete formatted shipping address including district, city, province, and postal code.
     */
    public function getFullShippingAddressAttribute(): string
    {
        $base = trim($this->shipping_address ?? '');
        $parts = [];
        if ($base) {
            $parts[] = $base;
        }
        if ($this->shipping_village && ! str_contains(strtolower($base), strtolower($this->shipping_village))) {
            $parts[] = 'Desa/Kel. '.$this->shipping_village;
        }
        if ($this->shipping_district && ! str_contains(strtolower($base), strtolower($this->shipping_district))) {
            $parts[] = 'Kec. '.$this->shipping_district;
        }
        if ($this->shipping_city && ! str_contains(strtolower($base), strtolower($this->shipping_city))) {
            $parts[] = $this->shipping_city;
        }
        if ($this->shipping_province && ! str_contains(strtolower($base), strtolower($this->shipping_province))) {
            $parts[] = $this->shipping_province;
        }
        $res = implode(', ', $parts);
        if ($this->shipping_postal_code && ! str_contains($res, (string) $this->shipping_postal_code)) {
            $res .= ' '.$this->shipping_postal_code;
        }

        return $res ?: '-';
    }

    /**
     * Get direct 1-click tracking URL with contact parameter for instant tracking.
     */
    public function getTrackingUrl(): string
    {
        $contact = $this->shipping_phone ?: $this->shipping_email;

        return route('order.tracking', [
            'order_number' => $this->order_number,
            'contact' => $contact,
        ]);
    }

    public function getTrackingUrlAttribute(): string
    {
        return $this->getTrackingUrl();
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
            'draft' => 'Draft / Penawaran',
            'pending_payment' => 'Menunggu Pembayaran',
            'paid' => 'Dibayar',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Diterima',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status ?: 'Draft',
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

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($text);
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

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($text);
    }

    public function getWaBatchShippedLink(?OrderBatch $batch = null): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->shipping_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        if ($batch) {
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
        } else {
            $text = "🚚 *Armada Pengiriman Telah Berangkat Menuju Lokasi Anda!*\n\n";
            $text .= "Halo {$this->shipping_name},\n";
            $text .= "Pesanan nomor *{$this->order_number}* telah diberangkatkan dari pabrik IndoRoster dan sedang menuju ke lokasi pengiriman Anda.\n\n";

            $text .= "*Rincian Pengiriman Armada:*\n";
            $text .= '• Total Muatan: *'.number_format($this->total_ordered_quantity, 0, ',', '.')." pcs*\n";
            if ($this->courier) {
                $text .= "• Supir / Ekspedisi: {$this->courier}\n";
            }
            if ($this->courier_phone) {
                $text .= "• No. HP / WA Supir: {$this->courier_phone}\n";
            }
            if ($this->tracking_number) {
                $text .= "• No. Plat Truk / Resi: {$this->tracking_number}\n";
            }
            $text .= "\n";
        }

        $text .= '🔍 Lacak progres lengkap: '.$this->getTrackingUrl()."\n\n";
        $text .= "Mohon pastikan area proyek siap untuk penerimaan dan bongkar muat.\n\nSalam hangat,\nTim Logistik & Distribusi IndoRoster";

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($text);
    }

    public function getWaProductionStartedLink(?OrderBatch $batch = null): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->shipping_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        $batchInfo = $batch ? " ({$batch->batch_name} - ".number_format($batch->quantity, 0, ',', '.').' pcs)' : '';
        $text = "🔨 *Update Produksi Pesanan Roster Beton*\n\n";
        $text .= "Halo {$this->shipping_name},\n";
        $text .= "Kabar baik! Pesanan Anda dengan No. Pesanan *{$this->order_number}*{$batchInfo} saat ini telah *MULAI DIPRODUKSI & DICETAK* di Pabrik IndoRoster.\n\n";

        if ($batch && $batch->estimated_dispatch_date) {
            $text .= '🗓️ *Estimasi Jadwal Berangkat Armada:* '.$batch->estimated_dispatch_date->format('d M Y')."\n";
        } elseif ($this->ready_shipping_date) {
            $text .= '🗓️ *Estimasi Siap Kirim:* '.$this->ready_shipping_date->format('d M Y')."\n";
        }

        $text .= "\n🔍 *Lacak Status Live Pesanan:* ".$this->getTrackingUrl()."\n\n";
        $text .= "Salam hangat,\nTim Produksi IndoRoster";

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($text);
    }

    public function getWaReadyToShipLink(?OrderBatch $batch = null): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->shipping_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        $batchInfo = $batch ? " ({$batch->batch_name} - ".number_format($batch->quantity, 0, ',', '.').' pcs)' : '';
        $text = "📦 *Update Kesiapan Pengiriman Material*\n\n";
        $text .= "Halo {$this->shipping_name},\n";
        $text .= "Material roster pesanan Anda (*{$this->order_number}*{$batchInfo}) telah *SELESAI DICETAK & LULUS QC* di pabrik. Saat ini material telah siap di area loading dock untuk dimuat ke armada truk pengiriman.\n\n";
        $text .= "Tim armada kami akan segera mengabari saat truk mulai meluncur ke lokasi proyek ({$this->shipping_address}).\n\n";
        $text .= '🔍 *Pantau Status Live:* '.$this->getTrackingUrl()."\n\n";
        $text .= "Salam,\nTim Logistik IndoRoster";

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($text);
    }

    public function getWaBatchScheduleLink(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->shipping_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        $text = "📋 *Konfirmasi Pesanan & Jadwal Pengiriman Bertahap*\n\n";
        $text .= "Halo {$this->shipping_name},\n";
        $text .= "Pesanan roster Anda No. *{$this->order_number}* telah kami jadwalkan untuk pengiriman bertahap ({$this->batch_count} Rit Truk Armada):\n\n";

        foreach ($this->batches as $b) {
            $tglKirim = $b->estimated_dispatch_date ? $b->estimated_dispatch_date->format('d M Y') : 'Menyesuaikan';
            $text .= '• *'.$b->batch_name.':* '.number_format($b->quantity, 0, ',', '.')." pcs (Est. Kirim: {$tglKirim})\n";
        }

        $text .= "\n📍 *Lokasi Proyek:* {$this->full_shipping_address}\n";
        $text .= '🔍 *Live Tracking & Progres Proyek:* '.$this->getTrackingUrl()."\n\n";
        $text .= "Terima kasih atas kepercayaan Anda memesan di IndoRoster!\n\nSalam,\nPabrik IndoRoster";

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($text);
    }

    public function getWaQuotationLink(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->shipping_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        $schemeLabel = match ($this->payment_scheme) {
            'quotation' => 'Tahap Pengajuan Penawaran Harga (Quotation Resmi)',
            'dp_50_50' => 'DP 50% di Awal (Pelunasan 50% saat barang siap dikirim)',
            'termin_3x' => 'Termin 3x (30% + 40% + 30%)',
            'custom_dp' => 'Kustom DP / Bertahap',
            default => 'Lunas Langsung di Muka (100%)',
        };

        $text = "📜 *SURAT PENAWARAN HARGA & RINCIAN PESANAN RESMI*\n";
        $text .= "Pabrik IndoRoster Indonesia\n\n";
        $text .= "Halo Bapak/Ibu {$this->shipping_name},\n";
        $text .= "Berikut rincian Surat Penawaran & Proforma Tagihan resmi untuk pesanan nomor *{$this->order_number}*:\n\n";

        $text .= "*📦 Rincian Produk Pesanan:*\n";
        foreach ($this->items as $idx => $item) {
            $no = $idx + 1;
            $var = $item->product_variant_name ? " ({$item->product_variant_name})" : '';
            $sub = 'Rp '.number_format($item->subtotal, 0, ',', '.');
            $price = 'Rp '.number_format($item->product_price, 0, ',', '.');
            $text .= "{$no}. *{$item->product_name}{$var}*\n";
            $text .= '   • Qty: '.number_format($item->quantity, 0, ',', '.')." pcs x {$price} = {$sub}\n";
        }
        $text .= "\n";

        $text .= "*💰 Ringkasan Keuangan:*\n";
        $text .= '• Subtotal Produk: Rp '.number_format($this->subtotal, 0, ',', '.')."\n";
        $text .= '• Biaya Pengiriman: Rp '.number_format($this->shipping_cost, 0, ',', '.')."\n";
        if ($this->discount_amount > 0) {
            $text .= '• Diskon: -Rp '.number_format($this->discount_amount, 0, ',', '.')."\n";
        }
        $text .= '• *TOTAL TAGIHAN:* *Rp '.number_format($this->grand_total, 0, ',', '.')."*\n\n";

        $text .= "*💳 Skema Pembayaran:*\n";
        $text .= "• Skema: {$schemeLabel}\n";
        if ($this->payment_scheme === 'dp_50_50' || $this->payment_scheme === 'termin_3x' || ($this->down_payment_amount > 0 && $this->down_payment_amount < $this->grand_total)) {
            $dpVal = (float) $this->down_payment_amount > 0
                ? (float) $this->down_payment_amount
                : ($this->payment_scheme === 'dp_50_50' ? round($this->grand_total * 0.5) : ($this->payment_scheme === 'termin_3x' ? round($this->grand_total * 0.3) : $this->grand_total));
            $remainingVal = max(0, $this->grand_total - $dpVal);
            $text .= '• Tagihan DP Awal: *Rp '.number_format($dpVal, 0, ',', '.')."*\n";
            $text .= '• Sisa Pelunasan: Rp '.number_format($remainingVal, 0, ',', '.')." (Saat Barang Siap Kirim)\n";
        }
        $text .= "\n";

        $text .= "*🏛️ Rekening Sah Pembayaran Pabrik:*\n";
        $text .= "• Bank: *Bank BRI*\n";
        $text .= "• No. Rekening: *4356-01-009396-50-2*\n";
        $text .= "• Atas Nama: *ABDUL HAMID*\n\n";

        $invoiceUrl = $this->invoice ? route('print.invoice', $this->invoice) : route('print.order', ['order' => $this->id]);
        $text .= "📄 *Download Dokumen Surat Penawaran / Invoice Sah:*\n{$invoiceUrl}\n\n";

        $text .= "📍 *Alamat Pengiriman / Titik Proyek:*\n{$this->full_shipping_address}\n\n";
        $text .= "Mohon konfirmasi jika bukti transfer pembayaran telah dilakukan.\n\nSalam hormat,\n*Manajemen Pabrik IndoRoster*";

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($text);
    }

    public function getWaPaymentReceiptLink(Payment $payment): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->shipping_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        $receiptUrl = route('print.receipt', $payment);
        $amount = (float) ($payment->gross_amount ?: $payment->amount ?: 0);
        $amountFormatted = 'Rp '.number_format($amount, 0, ',', '.');
        $totalPaidFormatted = 'Rp '.number_format($this->total_paid_amount, 0, ',', '.');
        $remainingFormatted = 'Rp '.number_format(max(0, $this->grand_total - $this->total_paid_amount), 0, ',', '.');
        $pct = $this->grand_total > 0 ? min(100, round(($this->total_paid_amount / $this->grand_total) * 100)) : 0;

        $text = "💳 *KONFIRMASI PEMBAYARAN MASUK TERVERIFIKASI*\n";
        $text .= "Pabrik IndoRoster Indonesia\n\n";
        $text .= "Halo Bapak/Ibu {$this->shipping_name},\n";
        $text .= "Terima kasih! Pembayaran *{$payment->installment_title}* untuk pesanan No. *{$this->order_number}* telah kami terima & diverifikasi ke rekening resmi pabrik.\n\n";

        $text .= "*📊 Rincian Transaksi Pembayaran:*\n";
        $text .= "• No. Kuitansi: *{$payment->receipt_number}*\n";
        $text .= "• Pembayaran: *{$payment->installment_title}*\n";
        $text .= "• Nominal Diterima: *{$amountFormatted}*\n";
        $text .= "• Metode: {$payment->payment_type_label}\n";
        $text .= '• Waktu: '.($payment->paid_at ? $payment->paid_at->format('d M Y H:i') : now()->format('d M Y H:i'))."\n\n";

        $text .= "*📈 Rekapitulasi Keuangan Proyek:*\n";
        $text .= '• Total Nilai Pesanan: Rp '.number_format($this->grand_total, 0, ',', '.')."\n";
        $text .= "• Total Uang Masuk: *{$totalPaidFormatted}* ({$pct}%)\n";
        $text .= "• *Sisa Tagihan:* *{$remainingFormatted}*\n\n";

        $text .= "🖨️ *Download Kuitansi Pembayaran Sah (TTD & Stempel):*\n{$receiptUrl}\n\n";
        $text .= '🔍 *Live Tracking Progres Pesanan:* '.$this->getTrackingUrl()."\n\n";
        $text .= "Pesanan Anda kini sedang diproses sesuai jadwal operasional pabrik.\n\nSalam hangat,\n*Administrasi Keuangan IndoRoster*";

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($text);
    }

    public function getWaRemainingBillLink(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->shipping_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        $remaining = max(0, $this->grand_total - $this->total_paid_amount);
        $remainingFormatted = 'Rp '.number_format($remaining, 0, ',', '.');
        $totalPaidFormatted = 'Rp '.number_format($this->total_paid_amount, 0, ',', '.');
        $grandTotalFormatted = 'Rp '.number_format($this->grand_total, 0, ',', '.');
        $invoiceUrl = $this->invoice ? route('print.invoice', $this->invoice) : route('print.order', ['order' => $this->id]);

        $text = "📋 *SURAT TAGIHAN PELUNASAN / SISA PEMBAYARAN PROYEK*\n";
        $text .= "Pabrik IndoRoster Indonesia\n\n";
        $text .= "Halo Bapak/Ibu {$this->shipping_name},\n";
        $text .= "Menginfokan bahwa pesanan roster Anda nomor *{$this->order_number}* saat ini sedang dipersiapkan untuk pengiriman / tahap berikutnya.\n\n";

        $text .= "*📊 Status Rekapitulasi Tagihan:*\n";
        $text .= "• Total Nilai Pesanan: {$grandTotalFormatted}\n";
        $text .= "• Pembayaran Masuk Sebelumnya: {$totalPaidFormatted}\n";
        $text .= "• *SISA TAGIHAN PELUNASAN:* *{$remainingFormatted}*\n\n";

        $text .= "*🏛️ Rekening Resmi Pembayaran Pabrik:*\n";
        $text .= "• Bank: *Bank BRI*\n";
        $text .= "• No. Rekening: *4356-01-009396-50-2*\n";
        $text .= "• Atas Nama: *ABDUL HAMID*\n\n";

        $text .= "📄 *Download Surat Tagihan Pelunasan Sah (TTD & Stempel):*\n{$invoiceUrl}\n\n";
        $text .= "Mohon dapat melakukan pelunasan sisa tagihan tersebut dan mengirimkan konfirmasi bukti transfer agar jadwal kirim / armada pengiriman dapat langsung diberangkatkan ke lokasi.\n\nSalam hormat,\n*Administrasi Keuangan Pabrik IndoRoster*";

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($text);
    }

    public function getWaSettlementPaidLink(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->shipping_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        $totalFormatted = 'Rp '.number_format($this->grand_total, 0, ',', '.');
        $invoiceUrl = $this->invoice ? route('print.invoice', $this->invoice) : route('print.order', ['order' => $this->id]);

        $text = "✅ *KONFIRMASI PELUNASAN PEMBAYARAN RESMI*\n\n";
        $text .= "Halo {$this->shipping_name},\n";
        $text .= "Terima kasih banyak! Pembayaran *PELUNASAN* untuk pesanan *{$this->order_number}* sebesar *{$totalFormatted}* telah kami terima dan diverifikasi dengan status *LUNAS (100%)*.\n\n";
        $text .= "📄 *Download Dokumen Invoice Resmi Lunas (TTD & Stempel Pabrik):*\n{$invoiceUrl}\n\n";
        $text .= "Terima kasih atas kepercayaan dan kerjasamanya bersama Pabrik IndoRoster Indonesia.\n\nSalam hormat,\nManajemen IndoRoster";

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($text);
    }

    public function getWaDeliveredLink(?OrderBatch $batch = null): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->shipping_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        if ($this->fulfillment_type === 'po_batch' && $batch) {
            $isFinalBatch = $this->isAllBatchesDelivered();

            if ($isFinalBatch) {
                $text = "🎉 *PENGIRIMAN PROYEK SELESAI & LENGKAP 100%*\n\n";
                $text .= "Halo {$this->shipping_name},\n";
                $text .= "Alhamdulillah muatan *{$batch->batch_name}* (".number_format($batch->quantity, 0, ',', '.')." pcs) telah selesai dibongkar di lokasi proyek Anda.\n\n";
                $text .= 'Seluruh pesanan proyek sebanyak *'.number_format($this->total_ordered_quantity, 0, ',', '.')."* pcs roster kini telah *LENGKAP 100% DITERIMA* dengan baik.\n\n";
            } else {
                $text = "🏁 *Konfirmasi Pembongkaran Material ({$batch->batch_name})*\n\n";
                $text .= "Halo {$this->shipping_name},\n";
                $text .= "Alhamdulillah muatan *{$batch->batch_name}* (".number_format($batch->quantity, 0, ',', '.')." pcs) pesanan No. *{$this->order_number}* telah *SELESAI DIBONGKAR* di lokasi proyek.\n\n";
                $text .= "*Rekapitulasi Pengiriman Proyek:*\n";
                $text .= '• Total Pesanan: '.number_format($this->total_ordered_quantity, 0, ',', '.')." pcs\n";
                $text .= '• Terkirim s/d Tahap Ini: '.number_format($batch->cumulative_shipped_quantity, 0, ',', '.')." pcs\n";
                $text .= '• Sisa Belum Terkirim: '.number_format($batch->remaining_quantity_after_this_batch, 0, ',', '.')." pcs\n\n";
                $text .= "Muatan rit berikutnya sedang dipersiapkan untuk jadwal pengiriman selanjutnya.\n\n";
            }

            if ($batch->delivery_photo_path) {
                $text .= '📸 *Foto Bukti Bongkar:* '.asset('storage/'.$batch->delivery_photo_path)."\n";
            }
        } elseif ($this->fulfillment_type === 'po_single') {
            $text = "🏁 *Konfirmasi Serah Terima Pesanan Roster (PO Selesai)*\n\n";
            $text .= "Halo {$this->shipping_name},\n";
            $text .= 'Seluruh material pesanan Pre-Order Anda sebanyak *'.number_format($this->total_ordered_quantity, 0, ',', '.')."* pcs (No. Pesanan: *{$this->order_number}*) telah *SELESAI DIBONGKAR & DITERIMA* dengan baik di lokasi proyek ({$this->shipping_address}).\n\n";
            if ($this->delivery_photo_path) {
                $text .= '📸 *Foto Bukti Bongkar:* '.asset('storage/'.$this->delivery_photo_path)."\n";
            }
        } else {
            $text = "🏁 *Konfirmasi Pesanan Selesai Diterima*\n\n";
            $text .= "Halo {$this->shipping_name},\n";
            $text .= 'Pesanan roster ready stock Anda sebanyak *'.number_format($this->total_ordered_quantity, 0, ',', '.')."* pcs (No. Pesanan: *{$this->order_number}*) telah *SELESAI DIBONGKAR & DITERIMA* dengan baik di alamat pengiriman.\n\n";
            if ($this->delivery_photo_path) {
                $text .= '📸 *Foto Bukti Bongkar:* '.asset('storage/'.$this->delivery_photo_path)."\n";
            }
        }

        $text .= '🔍 *Live Tracking & Riwayat Pesanan:* '.$this->getTrackingUrl()."\n\n";
        $text .= "Terima kasih banyak atas kepercayaan Anda berbelanja dan bermitra dengan Pabrik IndoRoster Indonesia!\n\nSalam hormat,\nTim Logistik Pabrik IndoRoster";

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($text);
    }
}
