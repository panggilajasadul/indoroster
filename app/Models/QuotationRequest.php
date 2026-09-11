<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotationRequest extends Model
{
    protected $fillable = [
        'reference_number',
        'user_id',
        'product_id',
        'product_variant_id',
        'name',
        'company_name',
        'phone',
        'email',
        'province_code',
        'city_code',
        'province_name',
        'city_name',
        'address',
        'quantity',
        'installation_timeline',
        'estimated_shipping_cost',
        'shipping_rate_type',
        'notes',
        'status',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'estimated_shipping_cost' => 'decimal:2',
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->reference_number)) {
                $model->reference_number = static::generateReferenceNumber();
            }
        });
    }

    /**
     * Generate unique reference number e.g. RFQ-202609-0001
     */
    public static function generateReferenceNumber(): string
    {
        $prefix = 'RFQ-'.Carbon::now()->format('Ym').'-';
        $latest = static::where('reference_number', 'like', $prefix.'%')
            ->orderBy('id', 'desc')
            ->first();

        if ($latest) {
            $lastNumber = (int) substr($latest->reference_number, strlen($prefix));
            $nextNumber = str_pad((string) ($lastNumber + 1), 4, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '0001';
        }

        return $prefix.$nextNumber;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * Build WhatsApp URL with structured message
     */
    public function getWhatsAppUrl(): string
    {
        $rawWa = SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
        $cleanWa = preg_replace('/[^0-9]/', '', (string) $rawWa);
        if (str_starts_with($cleanWa, '0')) {
            $cleanWa = '62'.substr($cleanWa, 1);
        } elseif (str_starts_with($cleanWa, '8')) {
            $cleanWa = '62'.$cleanWa;
        }

        $variantText = $this->variant ? "• Varian: {$this->variant->name}\n" : '';
        $companyText = $this->company_name ? "• Perusahaan/Proyek: {$this->company_name}\n" : '';
        $shippingText = $this->city_name ? "• Lokasi Proyek: {$this->city_name} (".($this->province_name ?? '').")\n" : '';
        $addressText = $this->address ? "• Alamat/Patokan: {$this->address}\n" : '';
        $timelineText = $this->installation_timeline ? "• Rencana Pasang/Kirim: {$this->installation_timeline}\n" : '';
        $notesText = $this->notes ? "• Catatan: {$this->notes}\n" : '';
        $productUrl = $this->product ? route('product.detail', $this->product->slug) : '';

        $message = "Halo Admin IndoRoster, saya ingin mengajukan *Permintaan Penawaran Resmi (RFQ)*:\n\n".
            "📄 *No. Referensi:* {$this->reference_number}\n".
            "👤 *Nama Pemesan:* {$this->name}\n".
            $companyText.
            "📱 *WhatsApp:* {$this->phone}\n".
            ($this->email ? "✉️ *Email:* {$this->email}\n" : '').
            $shippingText.
            $addressText.
            '📦 *Produk:* '.($this->product?->name ?? '-')."\n".
            $variantText.
            '🔢 *Jumlah Kebutuhan:* '.number_format($this->quantity, 0, ',', '.')." pcs\n".
            $timelineText.
            $notesText.
            ($productUrl ? "🔗 *Link Produk:* {$productUrl}\n\n" : "\n").
            'Mohon info penawaran harga terbaik, ketersediaan stok, dan ongkos kirim armada ke lokasi proyek kami. Terima kasih!';

        return 'https://wa.me/'.$cleanWa.'?text='.rawurlencode($message);
    }

    /**
     * Build WhatsApp URL for Admin to follow up with the client.
     */
    public function getAdminToClientWhatsAppUrl(): string
    {
        $cleanPhone = preg_replace('/[^0-9]/', '', (string) $this->phone);
        if (str_starts_with($cleanPhone, '0')) {
            $cleanPhone = '62'.substr($cleanPhone, 1);
        } elseif (str_starts_with($cleanPhone, '8')) {
            $cleanPhone = '62'.$cleanPhone;
        }

        $variantText = $this->variant ? " ({$this->variant->name})" : '';
        $companyText = $this->company_name ? " ({$this->company_name})" : '';
        $shippingText = $this->city_name ? "• Lokasi Proyek: {$this->city_name} (".($this->province_name ?? '').")\n" : '';
        $addressText = $this->address ? "• Alamat/Patokan: {$this->address}\n" : '';
        $timelineText = $this->installation_timeline ? "• Rencana Pasang: {$this->installation_timeline}\n" : '';

        $message = "Halo Bpk/Ibu *{$this->name}*{$companyText},\n\n".
            "Terima kasih telah mengajukan *Permintaan Penawaran Resmi* di IndoRoster dengan No. Referensi: *{$this->reference_number}*.\n\n".
            "Rincian Permintaan Anda:\n".
            '• Produk: '.($this->product?->name ?? '-').$variantText."\n".
            '• Jumlah Kebutuhan: '.number_format($this->quantity, 0, ',', '.')." pcs\n".
            $shippingText.
            $addressText.
            $timelineText.
            "\nPerkenalkan, saya dari Tim Sales Proyek IndoRoster. Kami siap memberikan penawaran harga pabrik langsung dan jadwal kirim armada terbaik.\n\n".
            'Apakah ada spesifikasi tambahan atau jadwal pengiriman yang ingin didiskusikan? Terima kasih!';

        return 'https://wa.me/'.$cleanPhone.'?text='.rawurlencode($message);
    }
}
