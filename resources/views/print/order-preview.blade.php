<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        .preview-box { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.5; padding: 20px; background: white; border-radius: 8px; overflow-y: auto; max-height: 70vh; position: relative; z-index: 1; }
        .watermark {
            position: absolute;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            font-weight: bold;
            color: rgba(220, 38, 38, 0.15); /* Faint red */
            z-index: 99;
            pointer-events: none;
            white-space: nowrap;
        }
        .header { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .header td { vertical-align: top; }
        .company-info { text-align: right; color: #718096; font-size: 12px; max-width: 300px; }
        .title { font-size: 22px; font-weight: bold; text-align: center; margin: 20px 0; text-transform: uppercase; color: #4a5568; border-bottom: 2px solid #c2410c; padding-bottom: 10px; }
        .details { width: 100%; display: flex; margin-bottom: 30px; }
        .details-col { flex: 1; }
        .details-label { font-weight: bold; font-size: 11px; color: #718096; text-transform: uppercase; margin-bottom: 5px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table.items th, table.items td { padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        table.items th { background: #f7fafc; font-weight: bold; color: #4a5568; }
        .right { text-align: right !important; }
        .summary { width: 100%; display: flex; }
        .summary-col { flex: 1.5; }
        .summary-totals { flex: 1; }
        table.totals { width: 100%; border-collapse: collapse; }
        table.totals td { padding: 8px 10px; text-align: right; }
        table.totals tr.bold td { font-weight: bold; font-size: 16px; border-top: 2px solid #c2410c; color: #c2410c; }
        .status-badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: bold; background: #ebf8ff; color: #2b6cb0; }
        .notes { margin-top: 40px; font-size: 12px; color: #718096; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="preview-box">
        @if($order->payment_status === 'paid')
        <div class="watermark">LUNAS</div>
        @endif
        <table class="header">
            <tr>
                <td>
                    <img src="{{ asset('assets/logo_indoroster-text.png') }}" style="max-height: 120px;">
                </td>
                <td class="company-info">
                    <strong style="color: #c2410c; font-size: 16px;">indoroster.com</strong><br>
                    <span style="font-size: 11px;">Pabrik Roster & bata ekpose dan ornamen dinding Terlengkap</span><br>
                    Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar,<br>
                    Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165<br>
                    WhatsApp: {{ \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847') }}
                </td>
            </tr>
        </table>

        <div class="title">SURAT JALAN</div>

        <div class="details">
            <div class="details-col">
                <div class="details-label">Data Penerima:</div>
                <strong style="font-size: 16px;">{{ $order->shipping_name }}</strong><br>
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}<br>
                <strong>HP: {{ $order->shipping_phone }}</strong>
            </div>
            <div class="details-col" style="padding-left: 20px;">
                <div class="details-label">Informasi Pesanan:</div>
                <table style="width: 100%; font-size: 13px;">
                    <tr><td width="40%">No. Pesanan</td><td>: <strong>{{ $order->order_number }}</strong></td></tr>
                    <tr><td>Tanggal</td><td>: {{ $order->created_at->format('d M Y H:i') }}</td></tr>
                    @if($order->courier)
                    <tr><td>Kurir</td><td>: {{ $order->courier }}</td></tr>
                    @endif
                    @if($order->courier_phone)
                    <tr><td>No. WA Kurir</td><td>: {{ $order->courier_phone }}</td></tr>
                    @endif
                    @if($order->tracking_number)
                    <tr><td>No. Resi/Plat</td><td>: {{ $order->tracking_number }}</td></tr>
                    @endif
                    <tr><td>Status</td><td>: <span class="status-badge">
                        {{ match($order->status) {
                            'pending' => 'MENUNGGU',
                            'processing' => 'DIPROSES',
                            'shipped' => 'DIKIRIM',
                            'delivered' => 'DITERIMA',
                            'completed' => 'SELESAI',
                            'cancelled' => 'DIBATALKAN',
                            default => strtoupper($order->status)
                        } }}
                    </span></td></tr>
                    <tr><td>Pembayaran</td><td>: 
                        {{ match($order->payment_status) {
                            'unpaid' => 'BELUM BAYAR',
                            'paid' => 'LUNAS',
                            'expired' => 'KADALUWARSA',
                            'failed' => 'GAGAL',
                            default => strtoupper($order->payment_status)
                        } }}
                    </td></tr>
                </table>
            </div>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th class="right">Harga Satuan</th>
                    <th class="right">Qty</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product_name }}</strong><br>
                        <small style="color: #718096;">Varian: {{ $item->product_variant_name ?: '-' }}</small>
                    </td>
                    <td class="right">Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <div class="summary-col">
                <div class="notes">
                    <strong>Catatan Pembeli:</strong><br>
                    {{ $order->notes ?: 'Tidak ada catatan.' }}
                </div>
            </div>
            <div class="summary-totals">
                <table class="totals">
                    <tr>
                        <td>Subtotal</td>
                        <td>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Ongkos Kirim</td>
                        <td>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    @if($order->discount_amount > 0)
                    <tr>
                        <td>Diskon</td>
                        <td style="color: red;">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr class="bold">
                        <td>TOTAL AKHIR</td>
                        <td>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
