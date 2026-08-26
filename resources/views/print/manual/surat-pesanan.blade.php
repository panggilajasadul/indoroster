<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pesanan - {{ $document->document_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.4; }
        .invoice-box { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #eee; }
        .header { width: 100%; display: table; margin-bottom: 20px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; }
        .header td { vertical-align: top; }
        .header .logo { font-size: 24px; font-weight: bold; color: #2d3748; }
        .header .company-info { text-align: right; color: #4a5568; font-size: 11px; max-width: 350px; }
        .title { font-size: 20px; font-weight: bold; text-align: center; margin: 15px 0; text-transform: uppercase; color: #1e293b; letter-spacing: 1px; }
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

        <div class="title">SURAT PESANAN BARANG (PO OFFLINE)</div>

        <div class="details">
            <div class="details-col">
                <div class="details-label">Pemesan / Klien:</div>
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
                <div class="details-label">Detail Pesanan:</div>
                <table style="width: 100%; font-size: 13px;">
                    <tr><td width="45%">No. Pesanan</td><td>: <strong>{{ $document->document_number }}</strong></td></tr>
                    <tr><td>Tanggal Pesan</td><td>: {{ $document->document_date->format('d M Y') }}</td></tr>
                    @if($document->due_date)
                        <tr><td>Est. Pengiriman</td><td>: {{ $document->due_date->format('d M Y') }}</td></tr>
                    @endif
                    <tr><td>Dibuat Oleh</td><td>: {{ $document->issued_by ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Produk / Deskripsi Barang</th>
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
                <strong>Catatan Alur Pemesanan Offline:</strong><br>
                1. Rencana jadwal cetak / muat barang akan diinfokan setelah DP diterima.<br>
                2. Barang yang sudah dipesan tidak dapat dibatalkan secara sepihak.<br>
                3. Mohon konfirmasi alamat bongkar muatan sebelum keberangkatan armada supir.
            </div>
            <div class="summary-totals">
                <table class="totals">
                    <tr>
                        <td>Subtotal:</td>
                        <td width="50%">Rp {{ number_format($document->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @if($document->discount > 0)
                        <tr>
                            <td>Diskon Khusus:</td>
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
                        <td>Total Biaya:</td>
                        <td>Rp {{ number_format($document->grand_total, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @php
            $orderNotes = $document->extra_data['order_notes'] ?? "1. Pesanan ini bersifat mengikat setelah DP 50% diterima.\n2. Jadwal produksi akan dikonfirmasi dalam 1x24 jam.\n3. Estimasi waktu produksi 3-7 hari kerja tergantung volume.\n4. Barang yang sudah dalam proses produksi tidak dapat dibatalkan.\n5. Pembayaran penuh dilakukan sebelum barang dikirim.";
            $orderTitle = $document->extra_data['order_notes_title'] ?? "Catatan Alur Pesanan";
        @endphp
        <div class="notes">
            <strong>{{ $orderTitle }}:</strong><br>
            {!! nl2br(e($orderNotes)) !!}
        </div>

        @if($document->notes)
            <div class="notes">
                <strong>Catatan Tambahan / Spesifikasi Khusus:</strong><br>
                {!! nl2br(e($document->notes)) !!}
            </div>
        @endif

        <table class="signatures">
            <tr>
                <td class="signature-col">
                    Pemesan / Klien,<br><br>
                    <div class="signature-box">
                        <!-- Kolom Kosong untuk Tanda Tangan Basah -->
                    </div>
                    ___________________________
                </td>
                <td class="signature-col">
                    Penerima Pesanan,<br><br>
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
