<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ManualDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_number',
        'type',
        'client_name',
        'client_address',
        'client_phone',
        'client_email',
        'items',
        'subtotal',
        'discount',
        'has_tax',
        'tax_amount',
        'grand_total',
        'document_date',
        'due_date',
        'issued_by',
        'status',
        'signature_path',
        'extra_data',
        'notes',
        'document_template_id',
        'snapshot',
    ];

    protected $casts = [
        'items' => 'array',
        'extra_data' => 'array',
        'document_date' => 'date',
        'due_date' => 'date',
        'has_tax' => 'boolean',
        'snapshot' => 'array',
    ];

    protected $appends = [
        'notes',
    ];

    public function documentTemplate()
    {
        return $this->belongsTo(DocumentTemplate::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Assign default template if not set
            if (empty($model->document_template_id)) {
                $defaultTemplate = DocumentTemplate::where('type', $model->type)
                    ->where('is_default', true)
                    ->first();
                if ($defaultTemplate) {
                    $model->document_template_id = $defaultTemplate->id;
                }
            }

            if (empty($model->document_number)) {
                $prefix = match ($model->type) {
                    'invoice', 'faktur' => 'INV',
                    'surat_jalan' => 'SJ',
                    'receipt', 'kwitansi' => 'REC',
                    'quotation', 'penawaran' => 'QTN',
                    'sales_order', 'surat_pesanan' => 'SO',
                    'proforma_invoice' => 'PRO',
                    'delivery_note' => 'DN',
                    'packing_list' => 'PL',
                    'purchase_order' => 'PO',
                    'goods_receipt' => 'GR',
                    'supplier_invoice' => 'SIN',
                    'customer_statement' => 'CS',
                    'commercial_invoice' => 'CINV',
                    'export_packing_list' => 'EPL',
                    'shipping_instruction' => 'SI',
                    'certificate_of_origin' => 'COO',
                    default => 'DOC'
                };
                $dateStr = now()->format('Ymd');

                // Cari dokumen terakhir dengan tipe yang sama yang dibuat hari ini
                $lastDoc = static::where('type', $model->type)
                    ->where('document_number', 'like', $prefix.'-'.$dateStr.'-%')
                    ->orderBy('id', 'desc')
                    ->first();

                $increment = 1;
                if ($lastDoc) {
                    $parts = explode('-', $lastDoc->document_number);
                    $lastIncrement = (int) end($parts);
                    $increment = $lastIncrement + 1;
                }

                $model->document_number = sprintf('%s-%s-%03d', $prefix, $dateStr, $increment);
            }
        });

        static::saving(function ($model) {
            // Generate snapshot only when transitioning to final and snapshot is currently empty
            if ($model->status === 'final' && empty($model->snapshot)) {
                $template = $model->documentTemplate ?? DocumentTemplate::where('type', $model->type)->where('is_default', true)->first();

                $marginsRaw = SiteSetting::getValue('doc_margins');
                $margins = $marginsRaw ? json_decode($marginsRaw, true) : null;

                $elementsRaw = SiteSetting::getValue('doc_elements');
                $elements = $elementsRaw ? json_decode($elementsRaw, true) : null;

                $templateData = $template ? $template->toArray() : [
                    'name' => 'Default Fallback',
                    'type' => $model->type,
                    'paper_size' => SiteSetting::getValue('doc_paper_size') ?? 'a4',
                    'orientation' => SiteSetting::getValue('doc_orientation') ?? 'portrait',
                    'margins' => $margins ?? ['top' => 15, 'bottom' => 15, 'left' => 15, 'right' => 15],
                    'company_name' => SiteSetting::getValue('doc_company_name') ?? SiteSetting::getValue('factory_name') ?? 'INDOROSTER INDONESIA',
                    'company_address' => SiteSetting::getValue('doc_company_address') ?? SiteSetting::getValue('factory_address') ?? 'Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165',
                    'company_phone' => SiteSetting::getValue('doc_company_phone') ?? SiteSetting::getValue('whatsapp_number') ?? '0813-8970-9847',
                    'company_email' => SiteSetting::getValue('doc_company_email') ?? SiteSetting::getValue('contact_email') ?? 'abdulhamid66266@gmail.com',
                    'logo_path' => SiteSetting::getValue('doc_logo_path'),
                    'logo_width' => 50, 'logo_height' => 25, 'logo_x' => 15, 'logo_y' => 15,
                    'signature_path' => $model->signature_path ?: SiteSetting::getValue('doc_signature_path'),
                    'signer_name' => SiteSetting::getValue('doc_signer_name') ?? $model->issued_by,
                    'signer_position' => SiteSetting::getValue('doc_signer_position') ?? 'Authorized Signatory',
                    'signature_width' => 40, 'signature_height' => 20, 'signature_x' => 140, 'signature_y' => 240,
                    'stamp_path' => SiteSetting::getValue('doc_stamp_path'),
                    'stamp_width' => 35, 'stamp_height' => 35, 'stamp_x' => 130, 'stamp_y' => 230,
                    'stamp_opacity' => 0.80, 'stamp_rotation' => 0,
                    'tax_rate' => 11.00,
                    'elements' => $elements ?? DocumentTemplate::getDefaultElementsConfig($model->type),
                ];

                // Merge custom coordinate layout overrides if they exist
                if (! empty($model->extra_data['layout_overrides'])) {
                    $overrides = $model->extra_data['layout_overrides'];
                    foreach ($overrides as $k => $v) {
                        if ($v !== null) {
                            $templateData[$k] = $v;
                        }
                    }
                }

                $model->snapshot = [
                    'document' => [
                        'document_number' => $model->document_number,
                        'type' => $model->type,
                        'client_name' => $model->client_name,
                        'client_address' => $model->client_address,
                        'client_phone' => $model->client_phone,
                        'client_email' => $model->client_email,
                        'items' => $model->items,
                        'subtotal' => (float) $model->subtotal,
                        'discount' => (float) $model->discount,
                        'has_tax' => (bool) $model->has_tax,
                        'tax_amount' => (float) $model->tax_amount,
                        'grand_total' => (float) $model->grand_total,
                        'document_date' => $model->document_date ? $model->document_date->format('Y-m-d') : null,
                        'due_date' => $model->due_date ? $model->due_date->format('Y-m-d') : null,
                        'issued_by' => $model->issued_by,
                        'notes' => $model->notes,
                        'extra_data' => $model->extra_data,
                    ],
                    'template' => $templateData,
                    'timestamp' => now()->toDateTimeString(),
                ];
            }
        });
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'invoice', 'faktur' => 'Faktur Penjualan (Invoice)',
            'surat_jalan' => 'Surat Jalan',
            'receipt', 'kwitansi' => 'Kwitansi Pembayaran',
            'quotation', 'penawaran' => 'Penawaran Harga (Quotation)',
            'sales_order', 'surat_pesanan' => 'Surat Pesanan Offline',
            'proforma_invoice' => 'Proforma Invoice',
            'delivery_note' => 'Delivery Note',
            'packing_list' => 'Packing List',
            'purchase_order' => 'Purchase Order',
            'goods_receipt' => 'Goods Receipt',
            'supplier_invoice' => 'Supplier Invoice',
            'customer_statement' => 'Customer Statement',
            'commercial_invoice' => 'Commercial Invoice (Ekspor)',
            'export_packing_list' => 'Export Packing List (Ekspor)',
            'shipping_instruction' => 'Shipping Instruction (SI)',
            'certificate_of_origin' => 'Certificate of Origin (COO)',
            default => $this->type
        };
    }

    public function getSignatureUrlAttribute(): ?string
    {
        return $this->signature_path ? Storage::disk('public')->url($this->signature_path) : null;
    }

    public function getNotesAttribute(): ?string
    {
        return $this->extra_data['notes'] ?? null;
    }

    public function setNotesAttribute(?string $value): void
    {
        $extraData = $this->extra_data ?? [];
        $extraData['notes'] = $value;
        $this->extra_data = $extraData;
    }
}
