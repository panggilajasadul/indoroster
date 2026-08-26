<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi Pembayaran - {{ $document->document_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.4; }
        .receipt-container { max-width: 800px; margin: auto; padding: 25px; border: 3px double #cbd5e1; background: #fafafa; position: relative; }
        .header { width: 100%; display: table; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px; }
        .header td { vertical-align: top; }
        .header .logo { font-size: 22px; font-weight: bold; color: #2d3748; }
        .header .company-info { text-align: right; color: #4a5568; font-size: 10px; }
        .title { font-size: 22px; font-weight: bold; text-align: center; margin: 10px 0; text-transform: uppercase; color: #1e293b; letter-spacing: 2px; }
        .receipt-no { text-align: center; font-size: 13px; font-weight: bold; color: #4a5568; margin-bottom: 25px; }
        
        table.receipt-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        table.receipt-table td { padding: 10px 5px; vertical-align: top; }
        table.receipt-table td.label { width: 22%; font-weight: bold; color: #4a5568; font-size: 12px; text-transform: uppercase; }
        table.receipt-table td.colon { width: 3%; }
        table.receipt-table td.value { border-bottom: 1px dotted #94a3b8; font-size: 14px; }
        table.receipt-table td.terbilang-box { background: #f1f5f9; padding: 10px; border-radius: 4px; border: 1px solid #e2e8f0; font-style: italic; }

        .footer-receipt { width: 100%; display: table; margin-top: 30px; }
        .amount-box-col { display: table-cell; width: 50%; vertical-align: middle; }
        .signature-col { display: table-cell; width: 50%; text-align: center; vertical-align: bottom; }
        
        .amount-box { display: inline-block; background: #c2410c; color: #fff; font-size: 20px; font-weight: bold; padding: 12px 25px; border-radius: 4px; border: 1px solid #9a3412; box-shadow: inset 0 2px 4px rgba(0,0,0,0.1); }
        .signature-box { min-height: 70px; margin: 10px 0; vertical-align: middle; }
        .signature-img { max-height: 60px; max-width: 150px; }
    </style>
</head>
<body>
    @php
        function penyebut($nilai) {
            $nilai = abs($nilai);
            $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
            $temp = "";
            if ($nilai < 12) {
                $temp = " " . $huruf[$nilai];
            } else if ($nilai <20) {
                $temp = penyebut($nilai - 10). " belas";
            } else if ($nilai < 100) {
                $temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
            } else if ($nilai < 200) {
                $temp = " seratus" . penyebut($nilai - 100);
            } else if ($nilai < 1000) {
                $temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
            } else if ($nilai < 2000) {
                $temp = " seribu" . penyebut($nilai - 1000);
            } else if ($nilai < 1000000) {
                $temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
            } else if ($nilai < 1000000000) {
                $temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
            } else if ($nilai < 1000000000000) {
                $temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
            }
            return $temp;
        }

        function terbilang($nilai) {
            if($nilai<0) {
                $hasil = "minus ". trim(penyebut($nilai));
            } else {
                $hasil = trim(penyebut($nilai));
            }     
            return ucwords($hasil) . " Rupiah";
        }
    @endphp

    <div class="receipt-container">
        <table class="header">
            <tr>
                <td class="logo">
                    <img src="{{ public_path('assets/logo_indoroster-text.png') }}" alt="Indoroster Logo" style="max-height: 55px;">
                </td>
                <td class="company-info">
                    <strong style="color: #c2410c; font-size: 13px;">{{ \App\Models\SiteSetting::getValue('factory_name', 'INDOROSTER INDONESIA') }}</strong><br>
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
                    WhatsApp: {{ \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847') }}
                </td>
            </tr>
        </table>

        <div class="title">KWITANSI PEMBAYARAN</div>
        <div class="receipt-no">NO. {{ $document->document_number }}</div>

        <table class="receipt-table">
            <tr>
                <td class="label">Telah Diterima Dari</td>
                <td class="colon">:</td>
                <td class="value"><strong>{{ $document->extra_data['received_from'] ?? $document->client_name }}</strong></td>
            </tr>
            <tr>
                <td class="label">Uang Sejumlah</td>
                <td class="colon">:</td>
                <td class="value terbilang-box">### {{ terbilang($document->grand_total) }} ###</td>
            </tr>
            <tr>
                <td class="label">Untuk Pembayaran</td>
                <td class="colon">:</td>
                <td class="value">{{ $document->extra_data['payment_for'] ?? ($document->notes ?? 'Pembelian Roster Beton Minimalis') }}</td>
            </tr>
            @if(isset($document->extra_data['payment_method']))
            <tr>
                <td class="label">Metode Pembayaran</td>
                <td class="colon">:</td>
                <td class="value">{{ $document->extra_data['payment_method'] }}</td>
            </tr>
            @endif
        </table>

        @php
            $receiptNotes = $document->extra_data['receipt_notes'] ?? "Kwitansi ini merupakan bukti pembayaran yang sah.\nHarap disimpan sebagai arsip transaksi Anda.";
            $receiptTitle = $document->extra_data['receipt_notes_title'] ?? "Keterangan Tambahan";
        @endphp
        <div class="footer-receipt">
            <div class="amount-box-col">
                <div class="amount-box">
                    Rp {{ number_format($document->grand_total, 0, ',', '.') }},-
                </div>
                <div style="margin-top:8px; font-size:10px; color:#555;">
                    <strong>{{ $receiptTitle }}:</strong><br>
                    {!! nl2br(e($receiptNotes)) !!}
                </div>
            </div>
            <div class="signature-col">
                Purwakarta, {{ $document->document_date->format('d M Y') }}<br>
                Penerima,<br>
                <div class="signature-box">
                    @if($document->signature_path && file_exists(storage_path('app/public/' . $document->signature_path)))
                        <img class="signature-img" src="{{ storage_path('app/public/' . $document->signature_path) }}" alt="Ttd Digital">
                    @else
                        <!-- Dikosongkan jika tanda tangan basah -->
                    @endif
                </div>
                <strong>INDOROSTER INDONESIA</strong>
            </div>
        </div>
    </div>
</body>
</html>
