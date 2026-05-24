<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pesanan Baru Masuk - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; line-height: 1.6; margin: 0; padding: 0; background-color: #f9fafb; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); }
        .header { text-align: center; border-bottom: 2px solid #f3f4f6; padding-bottom: 20px; margin-bottom: 20px; }
        .invoice-title { font-size: 20px; color: #111827; margin-top: 10px; font-weight: bold; }
        .section-title { font-size: 16px; font-weight: bold; color: #374151; margin-bottom: 10px; border-bottom: 1px solid #e5e7eb; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        th { background-color: #f9fafb; font-weight: bold; color: #4b5563; }
        .text-right { text-align: right; }
        .text-terra { color: #c2410c; }
        .summary-row.total td { font-size: 18px; font-weight: bold; border-top: 2px solid #e5e7eb; padding-top: 15px; }
        .footer { text-align: center; font-size: 12px; color: #6b7280; border-top: 1px solid #e5e7eb; padding-top: 20px; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div style="font-size: 14px; color: #6b7280; font-weight: bold; text-transform: uppercase; letter-spacing: 1px;">Pesanan Lunas Masuk</div>
            <div style="font-size: 36px; font-weight: 900; color: #16a34a; margin: 10px 0;">
                +Rp {{ number_format($order->grand_total, 0, ',', '.') }}
            </div>
            <div style="color: #6b7280; font-size: 14px; background: #f3f4f6; display: inline-block; padding: 4px 12px; border-radius: 20px;">
                Order ID: <strong>{{ $order->order_number }}</strong>
            </div>
        </div>

        <div style="margin-bottom: 20px; font-size: 14px;">
            Halo Admin,<br>
            Ada orderan baru yang telah berhasil dibayar senilai <strong>Rp{{ number_format($order->grand_total, 0, ',', '.') }}</strong> via Midtrans.
        </div>

        <div class="section-title">Informasi Pembeli</div>
        <div style="margin-bottom: 20px; font-size: 14px;">
            <strong>Nama:</strong> {{ $order->shipping_name }}<br>
            <strong>Email:</strong> {{ $order->shipping_email }}<br>
            <strong>Telepon:</strong> {{ $order->shipping_phone }}<br>
            <strong>Alamat:</strong> {{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}
        </div>

        <div class="section-title">Rincian Pesanan</div>
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            {{ $item->product_name }}
                            @if($item->product_variant_name && $item->product_variant_name !== '-')
                                <br><small style="color: #6b7280;">Varian: {{ $item->product_variant_name }}</small>
                            @endif
                        </td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr class="summary-row total">
                    <td colspan="2" class="text-right">Total Keseluruhan</td>
                    <td class="text-right text-terra">Rp{{ number_format($order->grand_total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        @if($order->notes)
            <div class="section-title">Catatan Pembeli</div>
            <div style="margin-bottom: 20px; font-size: 14px; background: #fef3c7; padding: 10px; border-radius: 4px;">
                {{ $order->notes }}
            </div>
        @endif

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ config('app.url') }}/admin/orders/{{ $order->id }}" style="background-color: #c2410c; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Lihat Detail di Admin Panel</a>
        </div>

        <div class="footer">
            © {{ date('Y') }} {{ config('app.name') }}. Admin Notification System.
        </div>
    </div>
</body>
</html>
