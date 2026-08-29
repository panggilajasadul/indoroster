<?php

namespace App\Services;

use App\Models\DocumentTemplate;
use App\Models\ManualDocument;
use App\Models\SiteSetting;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentPdfService
{
    /**
     * Generate PDF from a ManualDocument.
     */
    public function generatePdf(ManualDocument $document)
    {
        $pages = [];
        $templateData = [];
        $docData = [];

        // 1. Check if the document has a finalized snapshot
        if (! empty($document->snapshot)) {
            $snapshot = $document->snapshot;
            $docData = $snapshot['document'];
            $templateData = $snapshot['template'];
        } else {
            // 2. No snapshot, fetch live data and active template
            $docData = [
                'document_number' => $document->document_number,
                'type' => $document->type,
                'client_name' => $document->client_name,
                'client_address' => $document->client_address,
                'client_phone' => $document->client_phone,
                'client_email' => $document->client_email,
                'items' => $document->items,
                'subtotal' => (float) $document->subtotal,
                'discount' => (float) $document->discount,
                'has_tax' => (bool) $document->has_tax,
                'tax_amount' => (float) $document->tax_amount,
                'grand_total' => (float) $document->grand_total,
                'document_date' => $document->document_date ? $document->document_date->format('Y-m-d') : null,
                'due_date' => $document->due_date ? $document->due_date->format('Y-m-d') : null,
                'issued_by' => $document->issued_by,
                'notes' => $document->notes,
                'extra_data' => $document->extra_data,
            ];

            $template = $document->documentTemplate ?? DocumentTemplate::where('type', $document->type)->where('is_default', true)->first();

            if ($template) {
                $templateData = $template->toArray();
            } else {
                $marginsRaw = SiteSetting::getValue('doc_margins');
                $margins = $marginsRaw ? json_decode($marginsRaw, true) : null;

                $elementsRaw = SiteSetting::getValue('doc_elements');
                $elements = $elementsRaw ? json_decode($elementsRaw, true) : null;

                $templateData = [
                    'name' => 'Default Fallback',
                    'type' => $document->type,
                    'paper_size' => SiteSetting::getValue('doc_paper_size') ?? 'a4',
                    'orientation' => SiteSetting::getValue('doc_orientation') ?? 'portrait',
                    'margins' => $margins ?? ['top' => 15, 'bottom' => 15, 'left' => 15, 'right' => 15],
                    'company_name' => SiteSetting::getValue('doc_company_name') ?? SiteSetting::getValue('factory_name') ?? 'INDOROSTER INDONESIA',
                    'company_address' => SiteSetting::getValue('doc_company_address') ?? SiteSetting::getValue('factory_address') ?? 'Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165',
                    'company_phone' => SiteSetting::getValue('doc_company_phone') ?? SiteSetting::getValue('whatsapp_number') ?? '0813-8970-9847',
                    'company_email' => SiteSetting::getValue('doc_company_email') ?? SiteSetting::getValue('contact_email') ?? 'abdulhamid66266@gmail.com',
                    'logo_path' => SiteSetting::getValue('doc_logo_path'),
                    'logo_width' => 50, 'logo_height' => 25, 'logo_x' => 15, 'logo_y' => 15,
                    'signature_path' => $document->signature_path ?: SiteSetting::getValue('doc_signature_path'),
                    'signer_name' => SiteSetting::getValue('doc_signer_name') ?? $document->issued_by,
                    'signer_position' => SiteSetting::getValue('doc_signer_position') ?? 'Authorized Signatory',
                    'signature_width' => 40, 'signature_height' => 20, 'signature_x' => 140, 'signature_y' => 240,
                    'stamp_path' => SiteSetting::getValue('doc_stamp_path'),
                    'stamp_width' => 35, 'stamp_height' => 35, 'stamp_x' => 130, 'stamp_y' => 230,
                    'stamp_opacity' => 0.80, 'stamp_rotation' => 0,
                    'tax_rate' => 11.00,
                    'elements' => $elements ?? DocumentTemplate::getDefaultElementsConfig($document->type),
                ];
            }

            // Merge custom coordinate layout overrides if they exist
            if (! empty($document->extra_data['layout_overrides'])) {
                $overrides = $document->extra_data['layout_overrides'];
                foreach ($overrides as $k => $v) {
                    if ($v !== null) {
                        $templateData[$k] = $v;
                    }
                }
            }
        }

        // 3. Format dates for display
        $docDateFormatted = ! empty($docData['document_date']) ? date('d M Y', strtotime($docData['document_date'])) : '-';
        $dueDateFormatted = ! empty($docData['due_date']) ? date('d M Y', strtotime($docData['due_date'])) : '';

        // 4. Process multi-page dividing
        $items = $docData['items'] ?? [];
        $itemsWithIndex = [];
        foreach ($items as $idx => $item) {
            $itemsWithIndex[] = [
                'index' => $idx + 1,
                'product_name' => $item['product_name'] ?? '-',
                'sku' => $item['sku'] ?? '',
                'dimensions' => $item['dimensions'] ?? '',
                'variant_name' => $item['variant_name'] ?? '',
                'quantity' => $item['quantity'] ?? 0,
                'price' => $item['price'] ?? 0,
                'total' => ($item['quantity'] ?? 0) * ($item['price'] ?? 0),
            ];
        }

        // Determine items per page based on Table element height (roughly 10mm per row, 80mm default -> 8 items)
        $tableHeight = $templateData['elements']['product_table']['height'] ?? 80;
        $itemsPerPage = max(3, floor($tableHeight / 10)); // Guarantee at least 3 items per page

        $chunkedItems = array_chunk($itemsWithIndex, $itemsPerPage);
        $totalPages = count($chunkedItems) ?: 1;
        if (empty($chunkedItems)) {
            $chunkedItems = [[]]; // At least one empty page
        }

        // Get type labels
        $titleLabel = $this->getTypeLabel($docData['type']);

        // Compile context terms and notes text
        $termsText = $docData['extra_data']['terms_and_conditions'] ?? $docData['extra_data']['payment_instructions'] ?? $docData['extra_data']['delivery_notes'] ?? $docData['extra_data']['receipt_notes'] ?? $docData['extra_data']['order_notes'] ?? '';
        $termsTitle = $docData['extra_data']['terms_title'] ?? $docData['extra_data']['payment_instructions_title'] ?? $docData['extra_data']['delivery_notes_title'] ?? $docData['extra_data']['receipt_notes_title'] ?? $docData['extra_data']['order_notes_title'] ?? 'Syarat & Ketentuan';

        // 5. Build dynamic pages structure
        for ($i = 0; $i < $totalPages; $i++) {
            $pageNumber = $i + 1;
            $isFirstPage = ($pageNumber === 1);
            $isLastPage = ($pageNumber === $totalPages);

            // Copy base elements visibility
            $elements = $templateData['elements'];

            // Fine-tune visibility for first vs interior vs last pages to prevent overlapping
            foreach ($elements as $key => &$el) {
                if (in_array($key, ['logo', 'company_info', 'document_title', 'metadata_table', 'customer_info', 'intro_text'])) {
                    $el['visible'] = $isFirstPage && ($el['visible'] ?? false);
                }
                if (in_array($key, ['terms_box', 'financial_summary', 'signature_block', 'stamp_block', 'recipient_sign_block'])) {
                    $el['visible'] = $isLastPage && ($el['visible'] ?? false);
                }
                if ($key === 'product_table') {
                    $el['visible'] = ! empty($chunkedItems[$i]) && ($el['visible'] ?? false);
                }
                if ($key === 'footer') {
                    $el['visible'] = $el['visible'] ?? false;
                }
            }

            $pages[] = [
                'page_number' => $pageNumber,
                'total_pages' => $totalPages,
                'elements' => $elements,
                'items' => $chunkedItems[$i],
                'document_date' => $docDateFormatted,
                'due_date' => $dueDateFormatted,
                'title_label' => $titleLabel,
                'show_pricing' => ! in_array($docData['type'], ['surat_jalan', 'delivery_note', 'packing_list']),
                'terms_title' => $termsTitle,
                'terms_text' => $termsText,
            ];
        }

        // 6. Render PDF
        $pdf = Pdf::loadView('print.dynamic-document', [
            'pages' => $pages,
            'template' => $templateData,
            'document' => $docData,
        ]);

        $orientation = $templateData['orientation'] ?? 'portrait';
        $paperSize = $templateData['paper_size'] ?? 'a4';
        $pdf->setPaper($paperSize, $orientation);

        return $pdf;
    }

    /**
     * Map document type code to title labels in Indonesian.
     */
    private function getTypeLabel(string $type): string
    {
        return match ($type) {
            'faktur', 'invoice' => 'Faktur Penjualan (Invoice)',
            'surat_jalan' => 'Surat Jalan Pengiriman',
            'kwitansi', 'receipt' => 'Kwitansi Pembayaran',
            'penawaran', 'quotation' => 'Surat Penawaran Harga',
            'bast' => 'Berita Acara Serah Terima (BAST)',
            'sph' => 'Surat Dukungan Tender & SPH',
            'lab_test', 'uji_lab' => 'Sertifikat Uji Kuat Tekan Lab SNI',
            'surat_pesanan', 'sales_order' => 'Surat Pesanan (Sales Order)',
            'proforma_invoice' => 'Proforma Invoice',
            'delivery_note' => 'Delivery Note',
            'packing_list' => 'Packing List',
            'purchase_order' => 'Purchase Order (PO)',
            'goods_receipt' => 'Penerimaan Barang (Goods Receipt)',
            'supplier_invoice' => 'Faktur Pembelian Supplier',
            'customer_statement' => 'Pernyataan Rekening Pelanggan',
            'commercial_invoice' => 'Commercial Invoice (Ekspor)',
            'export_packing_list' => 'Export Packing List (Ekspor)',
            'shipping_instruction' => 'Shipping Instruction (SI)',
            'certificate_of_origin' => 'Certificate of Origin (COO)',
            default => str_replace('_', ' ', strtoupper($type))
        };
    }
}
