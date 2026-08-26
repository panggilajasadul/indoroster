<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penawaran Harga - {{ $document->document_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.5; }
        .invoice-box { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #eee; }
        .header { width: 100%; display: table; margin-bottom: 20px; border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; }
        .header td { vertical-align: top; }
        .header .logo { font-size: 24px; font-weight: bold; color: #2d3748; }
        .header .company-info { text-align: right; color: #4a5568; font-size: 11px; max-width: 350px; }
        .title { font-size: 18px; font-weight: bold; text-align: center; margin: 15px 0; text-transform: uppercase; color: #1e293b; letter-spacing: 1px; }
        .doc-details { width: 100%; display: table; margin-bottom: 20px; font-size: 12px; }
        .doc-details-col { display: table-cell; width: 50%; vertical-align: top; }
        
        .intro-text { margin-bottom: 20px; font-size: 13px; }
        
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.items th, table.items td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        table.items th { background: #f8fafc; font-weight: bold; color: #4a5568; font-size: 11px; text-transform: uppercase; }
        table.items td.right, table.items th.right { text-align: right; }
        
        table.totals { width: 45%; border-collapse: collapse; margin-left: 55%; margin-bottom: 20px; }
        table.totals td { padding: 6px 10px; text-align: right; }
        table.totals tr.bold td { font-weight: bold; font-size: 14px; border-top: 2px solid #cbd5e0; color: #c2410c; }
        
        .terms { margin-top: 25px; padding: 12px; background: #f8fafc; border-radius: 4px; border: 1px solid #e2e8f0; font-size: 11px; color: #4a5568; }
        .terms h4 { margin: 0 0 5px 0; color: #1e293b; font-size: 12px; }
        .terms ul { margin: 0; padding-left: 15px; }
        
        .signatures { margin-top: 40px; width: 100%; display: table; text-align: center; }
        .signature-col { display: table-cell; width: 50%; vertical-align: bottom; }
        .signature-box { min-height: 80px; margin: 10px 0; vertical-align: middle; }
        .signature-img { max-height: 70px; max-width: 150px; }
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

        <div class="title">SURAT PENAWARAN HARGA</div>

        <div class="doc-details">
            <div class="doc-details-col">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 60px; vertical-align: top; padding: 2px 0;"><strong>Nomor</strong></td>
                        <td style="width: 10px; vertical-align: top; padding: 2px 0;">:</td>
                        <td style="vertical-align: top; padding: 2px 0;">{{ $document->document_number }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; padding: 2px 0;"><strong>Tanggal</strong></td>
                        <td style="vertical-align: top; padding: 2px 0;">:</td>
                        <td style="vertical-align: top; padding: 2px 0;">{{ $document->document_date->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; padding: 2px 0;"><strong>Perihal</strong></td>
                        <td style="vertical-align: top; padding: 2px 0;">:</td>
                        <td style="vertical-align: top; padding: 2px 0;">Penawaran Harga Roster Beton</td>
                    </tr>
                </table>
            </div>
            <div class="doc-details-col" style="padding-left: 20px;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 80px; vertical-align: top; padding: 2px 0;"><strong>Kepada Yth.</strong></td>
                        <td style="width: 10px; vertical-align: top; padding: 2px 0;">:</td>
                        <td style="vertical-align: top; padding: 2px 0;"><strong>{{ $document->client_name }}</strong></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; padding: 2px 0;"><strong>Alamat</strong></td>
                        <td style="vertical-align: top; padding: 2px 0;">:</td>
                        <td style="vertical-align: top; padding: 2px 0; line-height: 1.4;">{{ $document->client_address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; padding: 2px 0;"><strong>Kontak</strong></td>
                        <td style="vertical-align: top; padding: 2px 0;">:</td>
                        <td style="vertical-align: top; padding: 2px 0;">{{ $document->client_phone ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="intro-text">
            Dengan hormat,<br>
            Bersama surat ini, kami dari <strong>INDOROSTER INDONESIA</strong> mengajukan penawaran harga produk roster beton minimalis berkualitas tinggi untuk kebutuhan proyek Anda. Berikut rincian harga yang kami tawarkan:
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Produk / Spesifikasi</th>
                    <th class="right" width="15%">Harga Satuan</th>
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

        <table class="totals">
            <tr>
                <td>Subtotal:</td>
                <td>Rp {{ number_format($document->subtotal, 0, ',', '.') }}</td>
            </tr>
            @if($document->discount > 0)
                <tr>
                    <td>Diskon khusus:</td>
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
                <td>Total Penawaran:</td>
                <td>Rp {{ number_format($document->grand_total, 0, ',', '.') }}</td>
            </tr>
        </table>

        @php
            $termsText = $document->extra_data['terms_and_conditions'] ?? "1. Harga di atas dapat berubah menyesuaikan volume pemesanan final.\n2. Penawaran harga ini berlaku selama 30 hari sejak diterbitkan.\n3. Pembayaran DP 50%, pelunasan sebelum barang dikirim.\n4. Barang yang sudah diproduksi tidak dapat dibatalkan sepihak.\n5. Pengiriman menggunakan ekspedisi rekanan Indoroster Indonesia.";
            $termsTitle = $document->extra_data['terms_title'] ?? "Syarat & Ketentuan Penawaran";
        @endphp
        <div class="terms">
            <h4>{{ $termsTitle }}:</h4>
            <p style="margin:0; line-height:1.8;">{!! nl2br(e($termsText)) !!}</p>
        </div>

        @if($document->notes)
            <div style="margin-top: 15px; font-size: 11px; color: #4a5568;">
                <strong>Keterangan Tambahan:</strong><br>
                {!! nl2br(e($document->notes)) !!}
            </div>
        @endif

        <table class="signatures">
            <tr>
                <td class="signature-col">
                    Disetujui Oleh,<br>
                    Penerima / Klien<br><br>
                    <div class="signature-box">
                        <!-- Kolom Kosong untuk Persetujuan Klien -->
                    </div>
                    ___________________________
                </td>
                <td class="signature-col">
                    Hormat Kami,<br>
                    Sales / Representative<br><br>
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
