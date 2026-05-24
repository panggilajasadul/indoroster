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

    <div class="footer">
        Terima kasih telah berbelanja di <strong>indoroster.com</strong>.<br>
        Faktur ini dihasilkan secara otomatis dan sah tanpa tanda tangan.<br>
        <strong>indoroster.com - Pabrik Roster & bata ekpose dan ornamen dinding Terlengkap</strong>
    </div>
</body>
</html>
