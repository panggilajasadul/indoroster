<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $document['document_number'] ?? 'Dokumen' }}</title>
    <style>
        @page {
            size: {{ $template['paper_size'] ?? 'a4' }} {{ $template['orientation'] ?? 'portrait' }};
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            background: #fff;
            -webkit-print-color-adjust: exact;
        }
        .page {
            width: {{ ($template['orientation'] ?? 'portrait') === 'landscape' ? '297mm' : '210mm' }};
            height: {{ ($template['orientation'] ?? 'portrait') === 'landscape' ? '210mm' : '297mm' }};
            position: relative;
            page-break-after: always;
            box-sizing: border-box;
            background: #fff;
            overflow: hidden;
        }
        .page:last-child {
            page-break-after: avoid;
        }
        
        /* Margins Visualizer */
        .page-content {
            position: absolute;
            top: {{ $template['margins']['top'] ?? 15 }}mm;
            bottom: {{ $template['margins']['bottom'] ?? 15 }}mm;
            left: {{ $template['margins']['left'] ?? 15 }}mm;
            right: {{ $template['margins']['right'] ?? 15 }}mm;
            box-sizing: border-box;
        }
        
        .element {
            position: absolute;
            box-sizing: border-box;
        }
        
        /* Logo & Company info styling */
        .company-name {
            font-size: 14px;
            font-weight: bold;
            color: #c2410c;
            margin-bottom: 2px;
        }
        .company-details {
            font-size: 9px;
            color: #555;
            line-height: 1.3;
        }
        
        /* Title styling */
        .doc-title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            color: #1e293b;
            letter-spacing: 0.5px;
        }
        
        /* Metadata & Customer Box styling */
        .info-box {
            font-size: 11px;
            border: none;
            padding: 0;
            background: transparent;
        }
        .info-box-title {
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            color: #1e293b;
            margin-bottom: 5px;
            border-bottom: none;
            padding-bottom: 0;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .info-table td.label {
            font-weight: bold;
            color: #333;
            width: 80px;
        }
        .info-table td.colon {
            width: 8px;
            color: #333;
        }
        
        /* Intro text */
        .intro-container {
            font-size: 11px;
            line-height: 1.5;
            color: #333;
        }
        
        /* Table Styling */
        .item-table {
            width: 100%;
            border-collapse: collapse;
        }
        .item-table th {
            background: #f8fafc;
            color: #4a5568;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            padding: 8px 10px;
            border-top: none;
            border-left: none;
            border-right: none;
            border-bottom: 1px solid #cbd5e1;
            text-align: left;
        }
        .item-table td {
            padding: 8px 10px;
            border-top: none;
            border-left: none;
            border-right: none;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
            color: #333;
            vertical-align: top;
        }
        .item-table th.right, .item-table td.right {
            text-align: right;
        }
        
        /* Financial Summary Box */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 4px 6px;
            text-align: right;
            font-size: 11px;
            color: #333;
        }
        .summary-table td.label {
            color: #4a5568;
        }
        .summary-table tr.total-row td {
            font-weight: bold;
            font-size: 13px;
            color: #c2410c;
            border-top: 2px solid #cbd5e1;
            padding-top: 6px;
        }
        
        /* Terms Box */
        .terms-container {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 8px;
            border-radius: 4px;
            font-size: 9px;
            color: #475569;
        }
        .terms-container h4 {
            margin: 0 0 4px 0;
            font-size: 10px;
            color: #1e293b;
        }
        .terms-text {
            white-space: pre-wrap;
            line-height: 1.3;
        }
        
        /* Signature & Stamp */
        .sign-title {
            font-size: 10px;
            text-align: center;
            margin-bottom: 25px;
        }
        .sign-name {
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
        }
        .sign-position {
            font-size: 9px;
            color: #64748b;
            text-align: center;
        }
        .signature-img {
            max-width: 100%;
            max-height: 100%;
            display: block;
            margin: 0 auto;
        }
        .stamp-img {
            width: 100%;
            height: 100%;
            display: block;
        }
        
        /* Footer */
        .footer-text {
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
            border-top: 1px dashed #cbd5e1;
            padding-top: 4px;
        }
        
        /* Watermark styling */
        .watermark {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-28deg);
            -webkit-transform: translate(-50%, -50%) rotate(-28deg);
            font-size: 76px;
            font-weight: 900;
            color: #c2410c;
            opacity: 0.15;
            border: 4px solid #c2410c;
            border-radius: 12px;
            padding: 6px 24px;
            letter-spacing: 8px;
            text-transform: uppercase;
            z-index: 999;
            pointer-events: none;
            white-space: nowrap;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .watermark {
                opacity: 0.18 !important;
                display: block !important;
            }
        }
    </style>
</head>
<body>
    @foreach($pages as $pageIdx => $page)
        @php
            $isExport = in_array($document['type'] ?? '', ['commercial_invoice', 'export_packing_list', 'shipping_instruction', 'certificate_of_origin']);
        @endphp
        <div class="page">
            @php
                $watermark = $document['extra_data']['watermark'] ?? null;
                // Fallback to old dynamic behavior if not explicitly set
                if (empty($watermark)) {
                    if (($document['status'] ?? 'draft') === 'final' && in_array($document['type'] ?? '', ['faktur', 'invoice', 'kwitansi', 'receipt'])) {
                        $watermark = 'LUNAS';
                    } elseif (($document['status'] ?? 'draft') === 'draft') {
                        $watermark = 'DRAFT';
                    } else {
                        $watermark = 'none';
                    }
                }
            @endphp

            @if($watermark !== 'none')
                <div class="watermark">{{ strtoupper($watermark) }}</div>
            @endif

            <!-- Logo -->
            @if(($page['elements']['logo']['visible'] ?? false))
                <div class="element logo" style="left: {{ $page['elements']['logo']['x'] }}mm; top: {{ $page['elements']['logo']['y'] }}mm; width: {{ $page['elements']['logo']['width'] }}mm; height: {{ $page['elements']['logo']['height'] }}mm; z-index: 5;">
                    @if(!empty($template['logo_path']))
                        <img src="{{ public_path('storage/' . $template['logo_path']) }}" class="signature-img" style="object-fit: contain;">
                    @else
                        <img src="{{ public_path('assets/logo_indoroster-text.png') }}" class="signature-img" style="object-fit: contain;">
                    @endif
                </div>
            @endif

            <!-- Company Info -->
            @if(($page['elements']['company_info']['visible'] ?? false))
                <div class="element company-info" style="left: {{ $page['elements']['company_info']['x'] }}mm; top: {{ $page['elements']['company_info']['y'] }}mm; width: {{ $page['elements']['company_info']['width'] }}mm; height: {{ $page['elements']['company_info']['height'] }}mm; text-align: right; z-index: 5;">
                    <div class="company-name">{{ $template['company_name'] }}</div>
                    <div class="company-details">
                        {!! nl2br(e($template['company_address'])) !!}<br>
                        Telp/WA: {{ $template['company_phone'] }} | Email: {{ $template['company_email'] }}
                    </div>
                </div>
            @endif

            <!-- Header Divider Line (Kop Surat Line) -->
            @php
                $logo = $page['elements']['logo'] ?? null;
                $logoY = $logo ? floatval($logo['y'] ?? 15) : 15;
                $logoH = $logo ? floatval($logo['height'] ?? 25) : 25;
                
                $companyInfo = $page['elements']['company_info'] ?? null;
                $companyInfoY = $companyInfo ? floatval($companyInfo['y'] ?? 15) : 15;
                $companyInfoH = $companyInfo ? floatval($companyInfo['height'] ?? 25) : 25;
                
                $headerBottom = max($logoY + $logoH, $companyInfoY + $companyInfoH);
                $lineTop = $headerBottom + 1;
            @endphp
            <div style="position: absolute; left: {{ $template['margins']['left'] ?? 15 }}mm; right: {{ $template['margins']['right'] ?? 15 }}mm; top: {{ $lineTop }}mm; border-bottom: 2px solid #c2410c; z-index: 5;"></div>

            <!-- Document Title & Dynamic Spacing Coordinates -->
            @php
                $titleY = floatval($page['elements']['document_title']['y'] ?? 45);
                // Enforce that title is always below the header line top with at least 4mm gap
                if (($page['elements']['document_title']['visible'] ?? false) && $titleY < $lineTop + 4) {
                    $titleY = $lineTop + 4;
                }
                
                $metadataY = floatval($page['elements']['metadata_table']['y'] ?? 55);
                if (($page['elements']['document_title']['visible'] ?? false) && $metadataY < $titleY + 10) {
                    $metadataY = $titleY + 10;
                }
                
                $customerInfoY = floatval($page['elements']['customer_info']['y'] ?? 55);
                if (($page['elements']['document_title']['visible'] ?? false) && $customerInfoY < $titleY + 10) {
                    $customerInfoY = $titleY + 10;
                }

                // Dynamic spacing calculations to automatically place summary & terms box below table
                $tableY = floatval($page['elements']['product_table']['y'] ?? 100);
                $tableHeight = max(15, 12 + (count($page['items']) * 8));
                $page['elements']['product_table']['height'] = $tableHeight;

                $financialSummaryY = $tableY + $tableHeight + 3;
                $page['elements']['financial_summary']['y'] = $financialSummaryY;

                $financialSummaryHeight = floatval($page['elements']['financial_summary']['height'] ?? 20);
                $termsBoxY = $financialSummaryY + $financialSummaryHeight + 5;
                $page['elements']['terms_box']['y'] = $termsBoxY;
            @endphp

            <!-- Document Title -->
            @if(($page['elements']['document_title']['visible'] ?? false))
                <div class="element document-title" style="left: {{ $page['elements']['document_title']['x'] }}mm; top: {{ $titleY }}mm; width: {{ $page['elements']['document_title']['width'] }}mm; height: {{ $page['elements']['document_title']['height'] }}mm; z-index: 5;">
                    <div class="doc-title">
                        {{ strtoupper($page['title_label']) }}
                    </div>
                </div>
            @endif

            <!-- Metadata Box -->
            @if(($page['elements']['metadata_table']['visible'] ?? false))
                <div class="element metadata-table" style="left: {{ $page['elements']['metadata_table']['x'] }}mm; top: {{ $metadataY }}mm; width: {{ $page['elements']['metadata_table']['width'] }}mm; height: {{ $page['elements']['metadata_table']['height'] }}mm; z-index: 5;">
                    <div class="info-box">
                        <div class="info-box-title">{{ $isExport ? 'Document Details' : 'Detail Dokumen' }}</div>
                        <table class="info-table">
                            <tr>
                                <td class="label">{{ $isExport ? 'Document No.' : 'Nomor' }}</td>
                                <td class="colon">:</td>
                                <td>{{ $document['document_number'] }}</td>
                            </tr>
                            <tr>
                                <td class="label">{{ $isExport ? 'Date' : 'Tanggal' }}</td>
                                <td class="colon">:</td>
                                <td>{{ $page['document_date'] }}</td>
                            </tr>
                            @if(!empty($document['due_date']))
                                <tr>
                                    <td class="label">{{ $isExport ? 'Due Date' : 'Jatuh Tempo' }}</td>
                                    <td class="colon">:</td>
                                    <td>{{ $page['due_date'] }}</td>
                                </tr>
                            @endif
                            @if(!empty($document['extra_data']['payment_method']))
                                <tr>
                                    <td class="label">{{ $isExport ? 'Payment Method' : 'Metode Bayar' }}</td>
                                    <td class="colon">:</td>
                                    <td>{{ $document['extra_data']['payment_method'] }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            @endif

            <!-- Customer Info Box -->
            @if(($page['elements']['customer_info']['visible'] ?? false))
                <div class="element customer-info" style="left: {{ $page['elements']['customer_info']['x'] }}mm; top: {{ $customerInfoY }}mm; width: {{ $page['elements']['customer_info']['width'] }}mm; height: {{ $page['elements']['customer_info']['height'] }}mm; z-index: 5;">
                    <div class="info-box">
                        <div class="info-box-title">{{ $isExport ? 'Consignee / Buyer' : 'Kepada Yth.' }}</div>
                        <table class="info-table">
                            <tr>
                                <td class="label">{{ $isExport ? 'Name' : 'Nama' }}</td>
                                <td class="colon">:</td>
                                <td><strong>{{ $document['client_name'] }}</strong></td>
                            </tr>
                            @if(!empty($document['client_phone']))
                                <tr>
                                    <td class="label">{{ $isExport ? 'Phone/WhatsApp' : 'Telepon/WA' }}</td>
                                    <td class="colon">:</td>
                                    <td>{{ $document['client_phone'] }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td class="label">{{ $isExport ? 'Address' : 'Alamat' }}</td>
                                <td class="colon">:</td>
                                <td>{{ $document['client_address'] ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Intro Text -->
            @if(($page['elements']['intro_text']['visible'] ?? false))
                <div class="element intro-text" style="left: {{ $page['elements']['intro_text']['x'] }}mm; top: {{ $page['elements']['intro_text']['y'] }}mm; width: {{ $page['elements']['intro_text']['width'] }}mm; height: {{ $page['elements']['intro_text']['height'] }}mm; z-index: 5;">
                    <div class="intro-container">
                        @if(in_array($document['type'], ['quotation', 'penawaran']))
                            Dengan hormat,<br>
                            Bersama surat ini, kami dari <strong>{{ $template['company_name'] }}</strong> mengajukan penawaran harga produk roster beton minimalis berkualitas tinggi untuk kebutuhan proyek Anda. Berikut rincian harga yang kami tawarkan:
                        @elseif(in_array($document['type'], ['invoice', 'faktur']))
                            Dengan hormat,<br>
                            Bersama surat ini kami lampirkan tagihan resmi (faktur penjualan) atas transaksi pemesanan produk roster beton arsitektural Anda yang telah selesai diproduksi. Rincian tagihan Anda adalah sebagai berikut:
                        @elseif($document['type'] === 'proforma_invoice')
                            Dengan hormat,<br>
                            Berikut kami lampirkan Proforma Invoice (faktur sementara) untuk detail pemesanan roster beton Anda sebagai dasar pembayaran uang muka (DP) guna memulai antrean produksi. Rincian pesanan adalah sebagai berikut:
                        @elseif(in_array($document['type'], ['sales_order', 'surat_pesanan']))
                            Dengan hormat,<br>
                            Terima kasih telah mempercayai IndoRoster. Kami konfirmasikan bahwa pesanan produk roster beton Anda telah resmi terdaftar dalam antrean jadwal produksi kami dengan rincian sebagai berikut:
                        @elseif(in_array($document['type'], ['surat_jalan', 'delivery_note', 'goods_receipt']))
                            Penerima yang terhormat,<br>
                            Bersama surat pengantar ini kami kirimkan produk roster arsitektural pesanan Anda menggunakan armada ekspedisi kami. Mohon diperiksa kecocokan jenis dan jumlah barang saat diterima. Rincian barang kiriman:
                        @elseif(in_array($document['type'], ['receipt', 'kwitansi']))
                            Dengan hormat,<br>
                            Terima kasih atas pembayaran Anda. Dokumen ini diterbitkan sebagai bukti penerimaan dana yang sah atas pemesanan produk roster beton arsitektural dengan rincian berikut:
                        @elseif($document['type'] === 'commercial_invoice')
                            Dear Valued Customer,<br>
                            We hereby enclose the Commercial Invoice for the shipment of premium architectural concrete roster blocks. The transaction details are as follows:
                        @elseif($document['type'] === 'export_packing_list')
                            Dear Valued Customer,<br>
                            Please find below the detailed Export Packing List for the shipment of architectural concrete roster blocks. This document details the packaging, dimensions, and weight of the cargo:
                        @elseif($document['type'] === 'shipping_instruction')
                            To Shipping Carrier / Freight Forwarder,<br>
                            Please execute the shipment booking for our export cargo of concrete masonry blocks with the following shipping instructions:
                        @elseif($document['type'] === 'certificate_of_origin')
                            This is to certify that the architectural concrete roster blocks described below have been produced and manufactured in the Republic of Indonesia by INDOROSTER INDONESIA:
                        @else
                            Dengan hormat,<br>
                            Bersama surat ini kami sampaikan rincian dokumen untuk tipe {{ $page['title_label'] }} dari <strong>{{ $template['company_name'] }}</strong> dengan rincian sebagai berikut:
                        @endif
                    </div>
                </div>
            @endif

            <!-- Product Table -->
            @if(($page['elements']['product_table']['visible'] ?? false))
                <div class="element product-table" style="left: {{ $page['elements']['product_table']['x'] }}mm; top: {{ $page['elements']['product_table']['y'] }}mm; width: {{ $page['elements']['product_table']['width'] }}mm; height: {{ $page['elements']['product_table']['height'] }}mm; z-index: 5;">
                    <table class="item-table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>{{ $isExport ? 'Description of Goods & Specification' : 'Deskripsi Item Produk & Varian' }}</th>
                                <th width="12%" class="right">{{ $isExport ? 'Quantity' : 'Kuantitas' }}</th>
                                @if($page['show_pricing'])
                                    <th width="18%" class="right">{{ $isExport ? 'Unit Price' : 'Harga Satuan' }}</th>
                                    <th width="20%" class="right">{{ $isExport ? 'Total Amount' : 'Total' }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($page['items'] as $idx => $item)
                                <tr>
                                    <td>{{ $item['index'] }}</td>
                                    <td>
                                        <strong>{{ $item['product_name'] }}</strong>
                                        @php
                                            $details = [];
                                            if (!empty($item['sku'])) {
                                                $details[] = 'SKU: ' . $item['sku'];
                                            }
                                            if (!empty($item['dimensions'])) {
                                                $details[] = ($isExport ? 'Size: ' : 'Ukuran: ') . $item['dimensions'];
                                            }
                                            if (!empty($item['variant_name'])) {
                                                $details[] = ($isExport ? 'Color: ' : 'Warna: ') . $item['variant_name'];
                                            }
                                        @endphp
                                        @if(!empty($details))
                                            <br><small style="color: #64748b;">{{ implode(' | ', $details) }}</small>
                                        @endif
                                    </td>
                                    <td class="right">{{ number_format($item['quantity'], 0, ',', '.') }}</td>
                                    @if($page['show_pricing'])
                                        <td class="right">Rp {{ number_format($item['price'], 0, ',', '.') }}</td>
                                        <td class="right">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Financial Summary Box -->
            @if(($page['elements']['financial_summary']['visible'] ?? false))
                <div class="element financial-summary" style="left: {{ $page['elements']['financial_summary']['x'] }}mm; top: {{ $page['elements']['financial_summary']['y'] }}mm; width: {{ $page['elements']['financial_summary']['width'] }}mm; height: {{ $page['elements']['financial_summary']['height'] }}mm; z-index: 5;">
                    <table class="summary-table">
                        <tr>
                            <td class="label">{{ $isExport ? 'Subtotal:' : 'Subtotal:' }}</td>
                            <td>Rp {{ number_format($document['subtotal'], 0, ',', '.') }}</td>
                        </tr>
                        @if($document['discount'] > 0)
                            <tr>
                                <td class="label">{{ $isExport ? 'Discount:' : 'Diskon:' }}</td>
                                <td style="color: #dc2626;">-Rp {{ number_format($document['discount'], 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        @if($document['has_tax'])
                            <tr>
                                <td class="label">{{ $isExport ? 'VAT' : 'PPN' }} ({{ number_format($template['tax_rate'], 0) }}%):</td>
                                <td>Rp {{ number_format($document['tax_amount'], 0, ',', '.') }}</td>
                            </tr>
                        @endif
                        <tr class="total-row">
                            <td class="label">{{ $isExport ? 'Grand Total:' : 'Total Akhir:' }}</td>
                            <td>Rp {{ number_format($document['grand_total'], 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            @endif

            <!-- Terms & Conditions Box -->
            @if(($page['elements']['terms_box']['visible'] ?? false))
                <div class="element terms-box" style="left: {{ $page['elements']['terms_box']['x'] }}mm; top: {{ $page['elements']['terms_box']['y'] }}mm; width: {{ $page['elements']['terms_box']['width'] }}mm; height: {{ $page['elements']['terms_box']['height'] }}mm; z-index: 5;">
                    <div class="terms-container">
                        <h4>{{ $page['terms_title'] }}</h4>
                        <div class="terms-text">{!! nl2br(e($page['terms_text'])) !!}</div>
                    </div>
                </div>
            @endif

            <!-- Dynamic Extra Custom Sections (Absolute positioning) -->
            @if(!empty($document['extra_data']['custom_sections']))
                @foreach($document['extra_data']['custom_sections'] as $idx => $sec)
                    @php
                        $secKey = 'custom_section_' . $idx;
                        $secElement = $page['elements'][$secKey] ?? null;
                        $secX = $secElement['x'] ?? ($page['elements']['terms_box']['x'] ?? 15);
                        $secY = $secElement['y'] ?? (($page['elements']['terms_box']['y'] ?? 200) + 35 * ($idx + 1));
                        $secW = $secElement['width'] ?? ($page['elements']['terms_box']['width'] ?? 180);
                        $secH = $secElement['height'] ?? 30;
                        $secVisible = $secElement['visible'] ?? true;
                    @endphp
                    @if($secVisible && !empty($sec['title']) && !empty($sec['content']))
                        <div class="element terms-box" style="left: {{ $secX }}mm; top: {{ $secY }}mm; width: {{ $secW }}mm; height: {{ $secH }}mm; z-index: 5;">
                            <div class="terms-container">
                                <h4>{{ $sec['title'] }}</h4>
                                <div class="terms-text">{!! nl2br(e($sec['content'])) !!}</div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif

            <!-- Signature block -->
            @if(($page['elements']['signature_block']['visible'] ?? false))
                <div class="element signature-block" style="left: {{ $page['elements']['signature_block']['x'] }}mm; top: {{ $page['elements']['signature_block']['y'] }}mm; width: {{ $page['elements']['signature_block']['width'] }}mm; height: {{ $page['elements']['signature_block']['height'] }}mm; z-index: 10;">
                    <div class="sign-title">
                        {{ $isExport ? 'Yours faithfully,' : 'Hormat Kami,' }}<br>
                        <strong>{{ $template['company_name'] }}</strong>
                    </div>
                    
                    <!-- Stamp Image embedded if visible and overlay matches coordinates (Placed underneath signature) -->
                    @if(!empty($template['stamp_path']))
                        <div style="position: absolute; left: {{ $template['stamp_x'] - $page['elements']['signature_block']['x'] }}mm; top: {{ $template['stamp_y'] - $page['elements']['signature_block']['y'] }}mm; width: {{ $template['stamp_width'] }}mm; height: {{ $template['stamp_height'] }}mm; opacity: {{ $template['stamp_opacity'] }}; transform: rotate({{ $template['stamp_rotation'] }}deg); z-index: 11;">
                            <img src="{{ public_path('storage/' . $template['stamp_path']) }}" class="stamp-img" style="object-fit: contain;">
                        </div>
                    @endif

                    <!-- Signature Image embedded if visible (Placed on top of stamp) -->
                    @if(!empty($template['signature_path']))
                        <div style="position: absolute; left: {{ $template['signature_x'] - $page['elements']['signature_block']['x'] }}mm; top: {{ $template['signature_y'] - $page['elements']['signature_block']['y'] }}mm; width: {{ $template['signature_width'] }}mm; height: {{ $template['signature_height'] }}mm; z-index: 12;">
                            <img src="{{ public_path('storage/' . $template['signature_path']) }}" class="signature-img" style="object-fit: contain;">
                        </div>
                    @endif

                    <div style="position: absolute; bottom: 0; left: 0; right: 0;">
                        <div class="sign-name">{{ $template['signer_name'] ?? 'Penanggung Jawab' }}</div>
                        <div class="sign-position">{{ $template['signer_position'] ?? 'Authorized Signatory' }}</div>
                    </div>
                </div>
            @endif

            <!-- Recipient sign block -->
            @if(($page['elements']['recipient_sign_block']['visible'] ?? false))
                <div class="element recipient-sign-block" style="left: {{ $page['elements']['recipient_sign_block']['x'] }}mm; top: {{ $page['elements']['recipient_sign_block']['y'] }}mm; width: {{ $page['elements']['recipient_sign_block']['width'] }}mm; height: {{ $page['elements']['recipient_sign_block']['height'] }}mm; z-index: 10;">
                    <div class="sign-title">
                        {{ $isExport ? 'Approved & Accepted By,' : 'Disetujui & Diterima Oleh,' }}<br>
                        {{ $isExport ? 'Client / Consignee' : 'Klien / Penerima' }}
                    </div>
                    <div style="position: absolute; bottom: 0; left: 0; right: 0;">
                        <div class="sign-name" style="text-decoration: none;">( _______________________ )</div>
                        <div class="sign-position">{{ $isExport ? 'Signature & Full Name' : 'Tanda Tangan & Nama Terang' }}</div>
                    </div>
                </div>
            @endif

            <!-- Footer -->
            @if(($page['elements']['footer']['visible'] ?? false))
                <div class="element footer" style="left: {{ $page['elements']['footer']['x'] }}mm; top: {{ $page['elements']['footer']['y'] }}mm; width: {{ $page['elements']['footer']['width'] }}mm; height: {{ $page['elements']['footer']['height'] }}mm; z-index: 5;">
                    <div class="footer-text">
                        {{ $isExport ? 'Thank you for your business. Page ' . $page['page_number'] . ' of ' . $page['total_pages'] : 'Terima kasih atas kerja sama dan kepercayaan Anda. Halaman ' . $page['page_number'] . ' dari ' . $page['total_pages'] }}
                    </div>
                </div>
            @endif
        </div>
    @endforeach
</body>
</html>
