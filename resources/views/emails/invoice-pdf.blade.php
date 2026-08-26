<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Faktur #{{ $order->order_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.4; }
        .header-table { width: 100%; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .logo-cell { width: 50%; vertical-align: middle; }
        .title-cell { width: 50%; text-align: right; vertical-align: middle; }
        .title { font-size: 28px; font-weight: bold; color: #000; text-transform: uppercase; margin: 0; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-cell { width: 50%; vertical-align: top; }
        .info-label { font-weight: bold; color: #666; margin-bottom: 3px; }
        
        .address-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .address-header { background-color: #f3f4f6; padding: 8px; font-weight: bold; border: 1px solid #e5e7eb; }
        .address-body { padding: 10px; border: 1px solid #e5e7eb; vertical-align: top; width: 50%; }
        
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .items-table th { background-color: #f3f4f6; padding: 10px; text-align: left; border: 1px solid #e5e7eb; font-weight: bold; }
        .items-table td { padding: 10px; border: 1px solid #e5e7eb; }
        .text-right { text-align: right; }
        
        .summary-table { width: 100%; }
        .summary-spacer { width: 60%; }
        .summary-content { width: 40%; }
        .summary-row td { padding: 5px 10px; border-bottom: 1px solid #f3f4f6; }
        .total-row { font-size: 16px; font-weight: bold; background-color: #f9fafb; }
        
        .footer { margin-top: 50px; text-align: center; color: #999; font-size: 10px; border-top: 1px solid #eee; padding-top: 10px; }
        .badge-lunas { 
            display: inline-block; 
            background-color: #def7ec; 
            color: #03543f; 
            padding: 5px 15px; 
            border-radius: 4px; 
            font-weight: bold; 
            text-transform: uppercase;
            border: 1px solid #03543f;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="{{ public_path('assets/logo_indoroster-text.png') }}" style="max-height: 120px;">
            </td>
            <td class="title-cell">
                <h1 class="title">INVOICE</h1>
                <div style="margin-top: 5px;">
                    <span class="badge-lunas">LUNAS</span>
                </div>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td class="info-cell">
                <div class="info-label">ID Invoice:</div>
                <div>#{{ $order->invoice->invoice_number ?? $order->order_number }}</div>
                <div class="info-label" style="margin-top: 10px;">Tanggal Invoice:</div>
                <div>{{ $order->paid_at ? $order->paid_at->format('d-m-Y') : now()->format('d-m-Y') }}</div>
            </td>
            <td class="info-cell" style="text-align: right;">
                <div class="info-label">ID Pesanan:</div>
                <div>#{{ $order->order_number }}</div>
                <div class="info-label" style="margin-top: 10px;">Tanggal Pesanan:</div>
                <div>{{ $order->created_at->format('d-m-Y') }}</div>
            </td>
        </tr>
    </table>

    <table class="address-table">
        <tr>
            <th class="address-header">Tagihan Kepada</th>
            <th class="address-header">Dikirim Kepada</th>
        </tr>
        <tr>
            <td class="address-body">
                <strong>{{ $order->shipping_name }}</strong><br>
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}<br>
                Kontak: {{ $order->shipping_phone }}
            </td>
            <td class="address-body">
                <strong>{{ $order->shipping_name }}</strong><br>
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}<br>
                Kontak: {{ $order->shipping_phone }}
            </td>
        </tr>
    </table>

    <table class="address-table">
        <tr>
            <th class="address-header">Metode Pembayaran</th>
            <th class="address-header">Metode Pengiriman</th>
        </tr>
        <tr>
            <td class="address-body">
                @if($order->latestPayment)
                    {{ $order->latestPayment->payment_type_label }}
                    @if($order->latestPayment->va_number)
                        <br><small style="color: #666;">VA: {{ $order->latestPayment->va_number }}</small>
                    @endif
                @else
                    Midtrans
                @endif
            </td>
            <td class="address-body">
                {{ $order->courier ?: 'Armada Indoroster' }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Produk</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Jumlah</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>
                    <strong>{{ $item->product_name }}</strong>
                    @if($item->product_variant_name && $item->product_variant_name !== '-')
                        <br><small>Varian: {{ $item->product_variant_name }}</small>
                    @endif
                </td>
                <td class="text-right">Rp{{ number_format($item->product_price, 0, ',', '.') }}</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary-table">
        <tr>
            <td class="summary-spacer"></td>
            <td class="summary-content">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr class="summary-row">
                        <td>Subtotal</td>
                        <td class="text-right">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="summary-row">
                        <td>Ongkos Kirim</td>
                        <td class="text-right">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    @if($order->discount_amount > 0)
                    <tr class="summary-row">
                        <td>Diskon</td>
                        <td class="text-right">-Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr class="summary-row total-row">
                        <td><strong>Total Keseluruhan</strong></td>
                        <td class="text-right"><strong>Rp{{ number_format($order->grand_total, 0, ',', '.') }}</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <!-- Tanda Tangan & Stempel Resmi Pabrik -->
    <table style="width: 100%; margin-top: 30px; border-collapse: collapse; page-break-inside: avoid;">
        <tr>
            <td style="width: 55%; vertical-align: top; font-size: 11px; color: #64748b; line-height: 1.5; padding-right: 20px;">
                <strong style="color: #334155;">Syarat &amp; Ketentuan Pengiriman:</strong>
                <ul style="margin: 4px 0 0 16px; padding: 0;">
                    <li>Penurunan material dilakukan di titik bongkar yang dapat diakses armada truk.</li>
                    <li>Klaim garansi pecah wajib disertakan bukti foto saat serah terima barang.</li>
                    <li>Dokumen ini merupakan bukti transaksi sah yang diterbitkan otomatis oleh sistem.</li>
                </ul>
            </td>
            <td style="width: 45%; vertical-align: top; text-align: center;">
                <div style="font-size: 12px; color: #475569; margin-bottom: 3px;">
                    Purwakarta, {{ $order->paid_at ? $order->paid_at->translatedFormat('d F Y') : date('d F Y') }}
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
                    Divisi Keuangan &amp; Distribusi Pabrik
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Terima kasih telah berbelanja di <strong>indoroster.com</strong>.<br>
        <strong>indoroster.com - Pabrik Roster &amp; Bata Ekspose dan Ornamen Dinding Terlengkap</strong>
    </div>
</body>
</html>
