<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'paper_size',
        'orientation',
        'margins',
        'company_name',
        'company_address',
        'company_phone',
        'company_email',
        'logo_path',
        'logo_width',
        'logo_height',
        'logo_x',
        'logo_y',
        'signature_path',
        'signer_name',
        'signer_position',
        'signature_width',
        'signature_height',
        'signature_x',
        'signature_y',
        'stamp_path',
        'stamp_width',
        'stamp_height',
        'stamp_x',
        'stamp_y',
        'stamp_opacity',
        'stamp_rotation',
        'tax_rate',
        'elements',
        'is_default',
    ];

    protected $casts = [
        'margins' => 'array',
        'elements' => 'array',
        'is_default' => 'boolean',
        'tax_rate' => 'decimal:2',
        'logo_width' => 'integer',
        'logo_height' => 'integer',
        'logo_x' => 'integer',
        'logo_y' => 'integer',
        'signature_width' => 'integer',
        'signature_height' => 'integer',
        'signature_x' => 'integer',
        'signature_y' => 'integer',
        'stamp_width' => 'integer',
        'stamp_height' => 'integer',
        'stamp_x' => 'integer',
        'stamp_y' => 'integer',
        'stamp_opacity' => 'float',
        'stamp_rotation' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($template) {
            // Set default margins if empty
            if (empty($template->margins)) {
                $template->margins = [
                    'top' => 15,
                    'bottom' => 15,
                    'left' => 15,
                    'right' => 15,
                ];
            }

            // Set default company info from settings if empty
            if (empty($template->company_name)) {
                $template->company_name = SiteSetting::getValue('factory_name', 'INDOROSTER INDONESIA');
            }
            if (empty($template->company_address)) {
                $template->company_address = SiteSetting::getValue('factory_address', 'Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165');
            }
            if (empty($template->company_phone)) {
                $template->company_phone = SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
            }
            if (empty($template->company_email)) {
                $template->company_email = SiteSetting::getValue('contact_email', 'abdulhamid66266@gmail.com');
            }

            // Set default elements coordinates if empty
            if (empty($template->elements)) {
                $template->elements = static::getDefaultElementsConfig($template->type);
            }

            // Set default asset coordinates and dimensions if null
            if (is_null($template->logo_x)) {
                $template->logo_x = 15;
            }
            if (is_null($template->logo_y)) {
                $template->logo_y = 15;
            }
            if (is_null($template->logo_width)) {
                $template->logo_width = 50;
            }
            if (is_null($template->logo_height)) {
                $template->logo_height = 25;
            }

            if (is_null($template->signature_x)) {
                $template->signature_x = 140;
            }
            if (is_null($template->signature_y)) {
                $template->signature_y = 220;
            }
            if (is_null($template->signature_width)) {
                $template->signature_width = 40;
            }
            if (is_null($template->signature_height)) {
                $template->signature_height = 20;
            }

            if (is_null($template->stamp_x)) {
                $template->stamp_x = 130;
            }
            if (is_null($template->stamp_y)) {
                $template->stamp_y = 215;
            }
            if (is_null($template->stamp_width)) {
                $template->stamp_width = 35;
            }
            if (is_null($template->stamp_height)) {
                $template->stamp_height = 35;
            }
            if (is_null($template->stamp_opacity)) {
                $template->stamp_opacity = 0.80;
            }
            if (is_null($template->stamp_rotation)) {
                $template->stamp_rotation = 0;
            }
        });

        static::saving(function ($template) {
            // If this template is set as default, unset other defaults for the same document type
            if ($template->is_default) {
                static::where('type', $template->type)
                    ->where('id', '!=', $template->id)
                    ->update(['is_default' => false]);
            }
        });
    }

    /**
     * Get default coordinates (in mm) for standard page elements.
     */
    public static function getDefaultElementsConfig(string $type): array
    {
        return [
            'logo' => [
                'name' => 'Logo Perusahaan',
                'x' => 15,
                'y' => 15,
                'width' => 50,
                'height' => 25,
                'visible' => true,
            ],
            'company_info' => [
                'name' => 'Informasi Perusahaan',
                'x' => 100,
                'y' => 15,
                'width' => 95,
                'height' => 25,
                'visible' => true,
            ],
            'document_title' => [
                'name' => 'Judul Dokumen',
                'x' => 15,
                'y' => 45,
                'width' => 180,
                'height' => 10,
                'visible' => true,
            ],
            'metadata_table' => [
                'name' => 'Detail Nomor & Tanggal',
                'x' => 15,
                'y' => 58,
                'width' => 85,
                'height' => 20,
                'visible' => true,
            ],
            'customer_info' => [
                'name' => 'Informasi Pelanggan',
                'x' => 110,
                'y' => 58,
                'width' => 85,
                'height' => 20,
                'visible' => true,
            ],
            'intro_text' => [
                'name' => 'Teks Pengantar',
                'x' => 15,
                'y' => 82,
                'width' => 180,
                'height' => 15,
                'visible' => in_array($type, ['penawaran', 'quotation', 'surat_pesanan', 'sales_order']),
            ],
            'product_table' => [
                'name' => 'Tabel Produk/Item',
                'x' => 15,
                'y' => 100,
                'width' => 180,
                'height' => 80,
                'visible' => true,
            ],
            'financial_summary' => [
                'name' => 'Ringkasan Biaya (Subtotal/Total)',
                'x' => 110,
                'y' => 185,
                'width' => 85,
                'height' => 30,
                'visible' => ! in_array($type, ['surat_jalan', 'delivery_note', 'packing_list']),
            ],
            'terms_box' => [
                'name' => 'Syarat & Ketentuan / Petunjuk',
                'x' => 15,
                'y' => 185,
                'width' => 85,
                'height' => 30,
                'visible' => true,
            ],
            'signature_block' => [
                'name' => 'Tanda Tangan Pengirim',
                'x' => 140,
                'y' => 220,
                'width' => 55,
                'height' => 45,
                'visible' => true,
            ],
            'stamp_block' => [
                'name' => 'Stempel Perusahaan',
                'x' => 130,
                'y' => 215,
                'width' => 35,
                'height' => 35,
                'visible' => true,
            ],
            'recipient_sign_block' => [
                'name' => 'Tanda Tangan Penerima',
                'x' => 15,
                'y' => 220,
                'width' => 55,
                'height' => 45,
                'visible' => true,
            ],
            'footer' => [
                'name' => 'Footer Dokumen',
                'x' => 15,
                'y' => 275,
                'width' => 180,
                'height' => 10,
                'visible' => true,
            ],
        ];
    }
}
