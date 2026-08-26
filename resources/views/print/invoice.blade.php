<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #334155; line-height: 1.5; margin: 0; padding: 20px 0; background: #ffffff; }
        .invoice-box { max-width: 800px; margin: auto; padding: 10px 20px; background: #ffffff; }
        .header { width: 100%; display: table; margin-bottom: 5px; }
        .header td { vertical-align: middle; }
        .header .logo { font-size: 28px; font-weight: bold; color: #1e293b; width: 50%; }
        .header .company-info { text-align: right; color: #64748b; font-size: 12px; width: 50%; line-height: 1.45; }
        .header-divider { border: none; border-top: 1.5px solid #e2e8f0; margin: 15px 0 25px 0; }
        .title { font-size: 22px; font-weight: 800; text-align: center; margin: 10px 0 25px 0; text-transform: uppercase; color: #0f172a; letter-spacing: 3px; }
        .details { width: 100%; display: table; margin-bottom: 25px; }
        .details-col { display: table-cell; width: 50%; vertical-align: top; }
        .details-label { font-weight: 700; font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 25px; table-layout: fixed; }
        table.items th, table.items td { padding: 10px 8px; border-bottom: 1px solid #e2e8f0; vertical-align: middle; }
        table.items th { background: transparent; font-weight: 700; color: #475569; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-top: 1px solid #e2e8f0; }
        table.items td { font-size: 13px; }
        table.items th.col-product, table.items td.col-product { width: 44%; text-align: left; }
        table.items th.col-price, table.items td.col-price { width: 20%; text-align: right; white-space: nowrap; }
        table.items th.col-qty, table.items td.col-qty { width: 14%; text-align: right; white-space: nowrap; }
        table.items th.col-total, table.items td.col-total { width: 22%; text-align: right; white-space: nowrap; }
        .summary { width: 100%; display: table; table-layout: fixed; margin-top: 10px; }
        .summary-col { display: table-cell; width: 46%; vertical-align: top; padding-right: 25px; }
        .summary-totals { display: table-cell; width: 54%; vertical-align: top; }
        table.totals { width: 100%; border-collapse: collapse; }
        table.totals td { padding: 6px 8px; text-align: right; font-size: 13px; white-space: nowrap; }
        table.totals td.label { text-align: left; color: #64748b; font-weight: 500; }
        table.totals tr.bold td { font-weight: 800; font-size: 16px; border-top: 2px solid #e2e8f0; color: #ea580c; padding-top: 12px; }
        table.totals tr.bold td.label { color: #ea580c; font-weight: 800; }
        .notes { font-size: 11.5px; color: #64748b; border: 1px dashed #e2e8f0; padding: 12px 14px; border-radius: 6px; background: transparent; line-height: 1.5; }
        .status { display: inline-block; padding: 3px 8px; border-radius: 4px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        .status-paid { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .status-unpaid { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        @media print {
            body { padding: 0; }
            .invoice-box { padding: 0; max-width: 100%; }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="header">
            <tr>
                <td class="logo">
                    <img src="{{ public_path('assets/logo_indoroster-text.png') }}" alt="Indoroster Logo" style="max-height: 100px;">
                </td>
                <td class="company-info">
                    <strong style="color: #ea580c; font-size: 15px;">indoroster.com</strong><br>
                    <span style="font-size: 11px;">Pabrik Roster & Bata Ekpose Terlengkap</span><br>
                    Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar,<br>
                    Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat - 41165<br>
                    WhatsApp: {{ \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847') }}
                </td>
            </tr>
        </table>
        
        <hr class="header-divider">

        <div class="title">INVOICE</div>

        <div class="details">
            <div class="details-col">
                <div class="details-label">Ditagihkan Kepada:</div>
                <strong style="color: #0f172a; font-size: 14px;">{{ $invoice->order->shipping_name }}</strong><br>
                <span style="color: #475569; font-size: 12.5px; line-height: 1.5;">
                    {{ $invoice->order->shipping_address }}<br>
                    {{ $invoice->order->shipping_city }}, {{ $invoice->order->shipping_province }} {{ $invoice->order->shipping_postal_code }}<br>
                    HP / WA: {{ $invoice->order->shipping_phone }}
                </span>
            </div>
            <div class="details-col" style="padding-left: 20px;">
                <div class="details-label">Informasi Invoice:</div>
                <table style="width: 100%; font-size: 13px; line-height: 1.6;">
                    <tr><td width="40%" style="color: #64748b;">No. Invoice</td><td>: <strong style="color: #0f172a;">{{ $invoice->invoice_number }}</strong></td></tr>
                    <tr><td style="color: #64748b;">Tanggal</td><td>: {{ $invoice->invoice_date->format('d M Y') }}</td></tr>
                    <tr><td style="color: #64748b;">No. Pesanan</td><td>: {{ $invoice->order->order_number }}</td></tr>
                    @if($invoice->order->latestPayment)
                    <tr>
                        <td style="color: #64748b;">Metode Bayar</td>
                        <td>: {{ $invoice->order->latestPayment->payment_type_label }}
                            @if($invoice->order->latestPayment->va_number)
                                (VA: {{ $invoice->order->latestPayment->va_number }})
                            @endif
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td style="color: #64748b;">Status</td>
                        <td>: 
                            @if($invoice->status === 'paid')
                                <span class="status status-paid">LUNAS</span>
                            @else
                                <span class="status status-unpaid">{{ strtoupper($invoice->status_label) }}</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th class="col-product">Produk</th>
                    <th class="col-price">Harga Satuan</th>
                    <th class="col-qty">Qty</th>
                    <th class="col-total">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->order->items as $item)
                <tr>
                    <td class="col-product">
                        <strong style="color: #0f172a;">{{ $item->product_name }}</strong><br>
                        @if($item->variant)
                            <small style="color: #64748b;">Varian: {{ $item->variant->name }}</small><br>
                        @endif
                        <small style="color: #64748b;">{{ $item->product->material ?? '' }} {{ $item->product->dimensions ? '('.$item->product->dimensions.')' : '' }}</small>
                    </td>
                    <td class="col-price">Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                    <td class="col-qty">{{ number_format($item->quantity, 0, ',', '.') }} pcs</td>
                    <td class="col-total" style="font-weight: 600; color: #0f172a;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <div class="summary-col">
                <div class="notes">
                    <strong style="color: #334155;">Catatan:</strong><br>
                    {{ $invoice->notes ?: 'Terima kasih telah berbelanja di indoroster.com. Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan.' }}
                </div>
            </div>
            <div class="summary-totals">
                <table class="totals">
                    <tr>
                        <td class="label">Subtotal</td>
                        <td>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Ongkos Kirim</td>
                        <td>Rp {{ number_format($invoice->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    @if($invoice->discount_amount > 0)
                    <tr>
                        <td class="label">Diskon</td>
                        <td style="color: #dc2626;">- Rp {{ number_format($invoice->discount_amount, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    @if($invoice->tax_amount > 0)
                    <tr>
                        <td class="label">Pajak</td>
                        <td>Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr class="bold">
                        <td class="label">GRAND TOTAL</td>
                        <td>Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Signature & Stamp Section Resmi Pabrik -->
        <table style="width: 100%; margin-top: 30px; border-collapse: collapse; page-break-inside: avoid;">
            <tr>
                <td style="width: 55%; vertical-align: top; font-size: 11px; color: #64748b; line-height: 1.5; padding-right: 20px;">
                    <strong style="color: #334155;">Syarat & Ketentuan Pengiriman:</strong>
                    <ul style="margin: 4px 0 0 16px; padding: 0;">
                        <li>Penurunan material dilakukan di titik bongkar yang dapat diakses armada truk.</li>
                        <li>Klaim garansi pecah wajib disertakan bukti foto saat serah terima barang.</li>
                        <li>Dokumen ini merupakan bukti transaksi sah yang diterbitkan otomatis oleh sistem.</li>
                    </ul>
                </td>
                <td style="width: 45%; vertical-align: top; text-align: center;">
                    <div style="font-size: 12px; color: #475569; margin-bottom: 3px;">
                        Purwakarta, {{ $invoice->created_at ? $invoice->created_at->translatedFormat('d F Y') : date('d F Y') }}
                    </div>
                    <div style="font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                        Pabrik IndoRoster
                    </div>

                    <div style="position: relative; height: 135px; width: 290px; margin: 0 auto;">
                        @php
                            $stampPath = null;
                            $sigPath = null;
                            $combinedPath = null;

                            $combinedCandidates = ['stamp_signature.png', 'stempel_ttd.png', 'ttd_stempel.png', 'stamp_ttd.png', 'signature_stamp.png', 'stempel_dan_ttd.png', 'stempel_ttd.PNG', 'stamp_signature.PNG', 'stempel_ttd.jpg'];
                            $stampCandidates = ['stamp.png', 'stempel.png', 'company_stamp.png', 'stempel_indoroster.png', 'stamp.PNG', 'stempel.PNG', 'stamp.jpg', 'stempel.jpg', 'stempel_pabrik.png'];
                            $sigCandidates = ['signature.png', 'ttd.png', 'tanda_tangan.png', 'signature.PNG', 'ttd.PNG', 'signature.jpg', 'ttd.jpg'];

                            foreach ($combinedCandidates as $f) {
                                if (file_exists(public_path('assets/' . $f))) { $combinedPath = public_path('assets/' . $f); break; }
                                if (file_exists(base_path('assets/' . $f))) { $combinedPath = base_path('assets/' . $f); break; }
                            }
                            if (!$combinedPath) {
                                foreach ($stampCandidates as $f) {
                                    if (file_exists(public_path('assets/' . $f))) { $stampPath = public_path('assets/' . $f); break; }
                                    if (file_exists(base_path('assets/' . $f))) { $stampPath = base_path('assets/' . $f); break; }
                                }
                                foreach ($sigCandidates as $f) {
                                    if (file_exists(public_path('assets/' . $f))) { $sigPath = public_path('assets/' . $f); break; }
                                    if (file_exists(base_path('assets/' . $f))) { $sigPath = base_path('assets/' . $f); break; }
                                }
                            }
                        @endphp

                        @if($combinedPath)
                            <img src="{{ $combinedPath }}" alt="Stempel & TTD" style="max-height: 130px; max-width: 290px; object-fit: contain;">
                        @elseif($stampPath || $sigPath)
                            @if($stampPath)
                                <img src="{{ $stampPath }}" alt="Stempel" style="position: absolute; left: 0px; top: 10px; width: 120px; height: 120px; object-fit: contain; opacity: 0.95; transform: rotate(-6deg);">
                            @endif
                            @if($sigPath)
                                <img src="{{ $sigPath }}" alt="TTD" style="position: absolute; left: 40px; top: 5px; width: 260px; height: 125px; object-fit: contain; z-index: 2;">
                            @endif
                        @else
                            <!-- Stempel Digital Otomatis Resmi IndoRoster -->
                            <div style="position: absolute; left: 15px; top: 10px; width: 105px; height: 105px; border: 2.5px dashed #ea580c; border-radius: 50%; color: #ea580c; text-align: center; font-weight: 800; font-size: 10px; line-height: 1.15; padding-top: 18px; box-sizing: border-box; transform: rotate(-6deg); background: rgba(234,88,12,0.03);">
                                INDOROSTER<br>
                                <span style="display: block; border-top: 1px solid #ea580c; border-bottom: 1px solid #ea580c; color: #16a34a; font-size: 11px; margin: 3px 0;">★ LUNAS ★</span>
                                PLERED PWK
                            </div>
                            <div style="position: absolute; left: 75px; top: 35px; font-family: 'Brush Script MT', cursive, sans-serif; font-size: 32px; color: #0284c7; font-weight: bold; transform: rotate(-5deg); z-index: 2;">
                                IndoRoster
                            </div>
                        @endif
                    </div>

                    <div style="font-weight: 700; text-decoration: underline; color: #0f172a; font-size: 13px; margin-top: 4px;">
                        Abdul Hamid
                    </div>
                    <div style="font-size: 10.5px; color: #64748b; margin-top: 2px;">
                        Divisi Keuangan & Distribusi Pabrik
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
