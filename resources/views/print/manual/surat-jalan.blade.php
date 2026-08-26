<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan - {{ $document->document_number }}</title>
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
        .signatures { margin-top: 50px; width: 100%; display: table; text-align: center; }
        .signature-col { display: table-cell; width: 33%; vertical-align: bottom; }
        .signature-box { min-height: 70px; margin: 10px 0; vertical-align: middle; }
        .signature-img { max-height: 60px; max-width: 130px; }
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

        <div class="title">SURAT JALAN PENGIRIMAN</div>

        <div class="details">
            <div class="details-col">
                <div class="details-label">Tujuan Pengiriman / Penerima:</div>
                <strong>{{ $document->client_name }}</strong><br>
                @if($document->client_address)
                    {!! nl2br(e($document->client_address)) !!}<br>
                @endif
                @if($document->client_phone)
                    HP/WhatsApp: {{ $document->client_phone }}
                @endif
            </div>
            <div class="details-col" style="padding-left: 30px;">
                <div class="details-label">Detail Surat Jalan:</div>
                <table style="width: 100%; font-size: 13px;">
                    <tr><td width="45%">No. Surat Jalan</td><td>: <strong>{{ $document->document_number }}</strong></td></tr>
                    <tr><td>Tanggal Kirim</td><td>: {{ $document->document_date->format('d M Y') }}</td></tr>
                    @if(isset($document->extra_data['courier_name']))
                        <tr><td>Nama Supir</td><td>: {{ $document->extra_data['courier_name'] }}</td></tr>
                    @endif
                    @if(isset($document->extra_data['tracking_number']))
                        <tr><td>No. Plat Kendaraan</td><td>: {{ $document->extra_data['tracking_number'] }}</td></tr>
                    @endif
                    @if(isset($document->extra_data['courier_phone']))
                        <tr><td>Kontak Supir</td><td>: {{ $document->extra_data['courier_phone'] }}</td></tr>
                    @endif
                </table>
            </div>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th width="8%">No</th>
                    <th>Nama Produk / Deskripsi Barang</th>
                    <th class="right" width="20%">Kuantitas / Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($document->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['product_name'] ?? '-' }}</td>
                        <td class="right" style="font-weight: bold; font-size: 14px;">{{ number_format($item['quantity'] ?? 0, 0, ',', '.') }} pcs</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @php
            $deliveryNotes = $document->extra_data['delivery_notes'] ?? "- Mohon periksa kondisi barang saat diterima.\n- Tanda tangani surat jalan sebagai bukti penerimaan.\n- Kerusakan akibat pengiriman harap dilaporkan dalam 1x24 jam.";
            $deliveryTitle = $document->extra_data['delivery_notes_title'] ?? "Catatan Pengiriman";
        @endphp
        <div class="notes">
            <strong>{{ $deliveryTitle }}:</strong><br>
            {!! nl2br(e($deliveryNotes)) !!}
        </div>

        @if($document->notes)
            <div class="notes">
                <strong>Keterangan Tambahan:</strong><br>
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
                    Pengirim / Supir,<br><br>
                    <div class="signature-box">
                        <!-- Kolom Kosong untuk Tanda Tangan Basah Supir -->
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
