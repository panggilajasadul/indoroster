<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Faktur Penjualan - {{ $document->document_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.4; }
        .invoice-box { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #eee; }
        .header { width: 100%; display: table; margin-bottom: 20px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; }
        .header td { vertical-align: top; }
        .header .logo { font-size: 24px; font-weight: bold; color: #2d3748; }
        .header .company-info { text-align: right; color: #4a5568; font-size: 11px; max-width: 350px; }
        .title { font-size: 20px; font-weight: bold; text-align: center; margin: 15px 0; text-transform: uppercase; color: #1e293b; }
        .details { width: 100%; display: table; margin-bottom: 20px; }
        .details-col { display: table-cell; width: 50%; vertical-align: top; }
        .details-label { font-weight: bold; font-size: 11px; color: #718096; text-transform: uppercase; margin-bottom: 5px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.items th, table.items td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        table.items th { background: #f8fafc; font-weight: bold; color: #4a5568; font-size: 11px; text-transform: uppercase; }
        table.items td.right, table.items th.right { text-align: right; }
        .summary { width: 100%; display: table; margin-top: 10px; }
        .summary-col { display: table-cell; width: 55%; vertical-align: top; font-size: 11px; color: #4a5568; }
        .summary-totals { display: table-cell; width: 45%; vertical-align: top; }
        table.totals { width: 100%; border-collapse: collapse; }
        table.totals td { padding: 6px 10px; text-align: right; }
        table.totals tr.bold td { font-weight: bold; font-size: 14px; border-top: 2px solid #cbd5e0; color: #c2410c; }
        .signatures { margin-top: 40px; width: 100%; display: table; text-align: center; }
        .signature-col { display: table-cell; width: 50%; vertical-align: bottom; }
        .signature-box { min-height: 80px; margin: 10px 0; vertical-align: middle; }
        .signature-img { max-height: 70px; max-width: 150px; }
        .notes { margin-top: 30px; font-size: 11px; color: #718096; border-top: 1px solid #e2e8f0; padding-top: 8px; }
        .status-badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 11px; text-transform: uppercase; border: 1px solid #c2410c; color: #c2410c; background: #fffbeb; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="header">
            <tr>
                <td class="logo">
                    <img src="{{ public_path('assets/logo_indoroster-text.png') }}" alt="Indoroster Logo" style="max-height: 70px;">
                </td>
                <td class="company-info">
                    <strong style="color: #c2410c; font-size: 14px;">{{ \App\Models\SiteSetting::getValue('factory_name', 'INDOROSTER INDONESIA') }}</strong><br>
                    <span>{{ \App\Models\SiteSetting::getValue('factory_tagline', 'Pabrik Roster Beton & Ventilasi Arsitektural Terlengkap') }}</span><br>
                    @php
                        $factoryAddress = \App\Models\SiteSetting::getValue('factory_address', 'Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165');
                        if (str_contains($factoryAddress, 'Cicadas')) {
                            $formattedAddress = "Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar,<br>Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat - 41165";
                        } else {
                            $formattedAddress = nl2br(e($factoryAddress));
                        }
                    @endphp
                    {!! $formattedAddress !!}<br>
                    WhatsApp: {{ \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847') }} | Email: {{ \App\Models\SiteSetting::getValue('contact_email', 'abdulhamid66266@gmail.com') }}
                </td>
            </tr>
        </table>

        <div class="title">FAKTUR PENJUALAN</div>

        <div class="details">
            <div class="details-col">
                <div class="details-label">Pelanggan:</div>
                <strong>{{ $document->client_name }}</strong><br>
                @if($document->client_address)
                    {!! nl2br(e($document->client_address)) !!}<br>
                @endif
                @if($document->client_phone)
                    HP/WhatsApp: {{ $document->client_phone }}<br>
                @endif
                @if($document->client_email)
                    Email: {{ $document->client_email }}
                @endif
            </div>
            <div class="details-col" style="padding-left: 30px;">
                <div class="details-label">Detail Faktur:</div>
                <table style="width: 100%; font-size: 13px;">
                    <tr><td width="45%">No. Faktur</td><td>: <strong>{{ $document->document_number }}</strong></td></tr>
                    <tr><td>Tanggal</td><td>: {{ $document->document_date->format('d M Y') }}</td></tr>
                    @if($document->due_date)
                        <tr><td>Jatuh Tempo</td><td>: {{ $document->due_date->format('d M Y') }}</td></tr>
                    @endif
                    <tr><td>Status</td><td>: <span class="status-badge">{{ strtoupper($document->status) }}</span></td></tr>
                </table>
            </div>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Produk / Deskripsi</th>
                    <th class="right" width="15%">Harga</th>
                    <th class="right" width="10%">Qty</th>
                    <th class="right" width="20%">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($document->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['product_name'] ?? '-' }}</td>
                        <td class="right">Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
                        <td class="right">{{ number_format($item['quantity'] ?? 0, 0, ',', '.') }}</td>
                        <td class="right">Rp {{ number_format(($item['quantity'] ?? 0) * ($item['price'] ?? 0), 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <div class="summary-col">
                <strong>Metode Pembayaran:</strong><br>
                Transfer Bank BCA: 231-xxxx-xxx A/N INDOROSTER INDONESIA<br>
                *Harap kirimkan bukti transfer setelah melakukan pembayaran.<br><br>
                Dibuat oleh: {{ $document->issued_by ?? '-' }}
            </div>
            <div class="summary-totals">
                <table class="totals">
                    <tr>
                        <td>Subtotal:</td>
                        <td width="50%">Rp {{ number_format($document->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @if($document->discount > 0)
                        <tr>
                            <td>Diskon:</td>
                            <td>-Rp {{ number_format($document->discount, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    @if($document->has_tax)
                        <tr>
                            <td>PPN (11%):</td>
                            <td>Rp {{ number_format($document->tax_amount, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                    <tr class="bold">
                        <td>Total Akhir:</td>
                        <td>Rp {{ number_format($document->grand_total, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @php
            $paymentInstructions = $document->extra_data['payment_instructions'] ?? "Transfer Bank BCA No. Rek: 231-xxxx-xxx a/n INDOROSTER INDONESIA\nBayar DP minimal 50% untuk konfirmasi pesanan.\nPelunasan dilakukan sebelum barang dikirim.";
            $paymentTitle = $document->extra_data['payment_instructions_title'] ?? "Petunjuk Pembayaran";
        @endphp
        <div class="notes">
            <strong>{{ $paymentTitle }}:</strong><br>
            {!! nl2br(e($paymentInstructions)) !!}
        </div>

        @if($document->notes)
            <div class="notes">
                <strong>Catatan:</strong><br>
                {!! nl2br(e($document->notes)) !!}
            </div>
        @endif

        <table class="signatures">
            <tr>
                <td class="signature-col">
                    Penerima / Klien,<br><br>
                    <div class="signature-box">
                        <!-- Kolom Kosong untuk Tanda Tangan Basah -->
                    </div>
                    ___________________________
                </td>
                <td class="signature-col">
                    Hormat Kami,<br><br>
                    <div class="signature-box">
                        @if($document->signature_path && file_exists(storage_path('app/public/' . $document->signature_path)))
                            <img class="signature-img" src="{{ storage_path('app/public/' . $document->signature_path) }}" alt="Ttd Digital">
                        @else
                            <!-- Dikosongkan jika tanda tangan basah -->
                        @endif
                    </div>
                    <strong>INDOROSTER INDONESIA</strong>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
