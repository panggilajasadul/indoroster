<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Berita Acara Serah Terima (BAST) - {{ $document->document_number }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 20mm;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11.5px;
            color: #1e293b;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            background: #fff;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header table { width: 100%; border-collapse: collapse; }
        .logo { font-size: 22px; font-weight: 800; color: #ea580c; }
        .company-info { text-align: right; font-size: 10px; color: #475569; line-height: 1.35; }
        
        .title-box {
            text-align: center;
            margin-bottom: 18px;
        }
        .title {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }
        .doc-number {
            font-size: 12px;
            font-weight: 700;
            color: #ea580c;
        }
        
        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 15px 0;
            font-size: 11px;
        }
        table.items-table th, table.items-table td {
            border: 1px solid #cbd5e1;
            padding: 7px 10px;
        }
        table.items-table th {
            background: #f1f5f9;
            font-weight: 700;
            color: #334155;
            text-align: center;
        }
        
        .signatures {
            margin-top: 30px;
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .signatures td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding: 0 15px;
        }
        .sign-space {
            height: 85px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .meterai-box {
            width: 90px;
            height: 70px;
            border: 1.5px dashed #94a3b8;
            margin: 10px auto;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            color: #64748b;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td style="vertical-align: middle; width: 45%;">
                    @if(file_exists(public_path('assets/logo_indoroster-text.png')))
                        <img src="{{ public_path('assets/logo_indoroster-text.png') }}" alt="Indoroster Logo" style="max-height: 55px;">
                    @else
                        <div class="logo">INDOROSTER INDONESIA</div>
                    @endif
                </td>
                <td class="company-info" style="vertical-align: middle; width: 55%;">
                    <strong style="color: #ea580c; font-size: 13px;">INDOROSTER INDONESIA</strong><br>
                    <span>Pusat Roster Minimalis &amp; Bata Ekspos</span><br>
                    Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat - 41165<br>
                    WhatsApp: {{ \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847') }} | Web: www.indoroster.com
                </td>
            </tr>
        </table>
    </div>

    <div class="title-box">
        <div class="title">BERITA ACARA SERAH TERIMA BARANG (BAST)</div>
        <div style="font-size: 12px; font-weight: 600; color: #334155;">PENYERAHAN MATERIAL ROSTER BETON DI LOKASI PROYEK</div>
        <div class="doc-number">Nomor: {{ $document->document_number }}</div>
    </div>

    <p style="text-align: justify; margin-bottom: 10px;">
        Pada hari ini, <strong>{{ \Carbon\Carbon::parse($document->document_date)->translatedFormat('l') }}</strong> tanggal <strong>{{ \Carbon\Carbon::parse($document->document_date)->translatedFormat('d F Y') }}</strong> ({{ \Carbon\Carbon::parse($document->document_date)->format('d/m/Y') }}), bertempat di lokasi proyek: <strong>{{ $document->client_address ?: 'Titik Lokasi Proyek Pembeli' }}</strong>, kami yang bertanda tangan di bawah ini:
    </p>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 11px;">
        <tr>
            <td style="width: 25px; vertical-align: top; font-weight: 700;">1.</td>
            <td style="width: 150px; vertical-align: top; font-weight: 700;">Nama Pihak Penyerah</td>
            <td style="width: 10px; vertical-align: top;">:</td>
            <td style="vertical-align: top;"><strong>INDOROSTER INDONESIA</strong> (Pabrik Roster Plered Purwakarta)</td>
        </tr>
        <tr>
            <td></td>
            <td style="vertical-align: top;">Nama Petugas / Mandor</td>
            <td style="vertical-align: top;">:</td>
            <td style="vertical-align: top;"><strong>{{ strtoupper($document->issued_by ?: 'Abdul Hamid') }}</strong> ({{ $document->extra_data['signer_role'] ?? 'Petugas Logistik / Pengiriman' }})</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="padding-top: 2px;">Selanjutnya disebut sebagai <strong>PIHAK PERTAMA (Yang Menyerahkan)</strong>.</td>
        </tr>
        <tr><td colspan="4" style="height: 6px;"></td></tr>
        <tr>
            <td style="vertical-align: top; font-weight: 700;">2.</td>
            <td style="vertical-align: top; font-weight: 700;">Nama Pihak Penerima</td>
            <td style="vertical-align: top;">:</td>
            <td style="vertical-align: top;"><strong>{{ strtoupper($document->client_name) }}</strong></td>
        </tr>
        <tr>
            <td></td>
            <td style="vertical-align: top;">Alamat Lokasi Proyek</td>
            <td style="vertical-align: top;">:</td>
            <td style="vertical-align: top;">{{ $document->client_address ?: '-' }}</td>
        </tr>
        @if($document->client_phone)
        <tr>
            <td></td>
            <td style="vertical-align: top;">No. Telepon / WhatsApp</td>
            <td style="vertical-align: top;">:</td>
            <td style="vertical-align: top;">{{ $document->client_phone }}</td>
        </tr>
        @endif
        <tr>
            <td></td>
            <td colspan="3" style="padding-top: 2px;">Selanjutnya disebut sebagai <strong>PIHAK KEDUA (Yang Menerima)</strong>.</td>
        </tr>
    </table>

    <p style="text-align: justify; margin-bottom: 8px;">
        Dengan ini menyatakan bahwa <strong>PIHAK PERTAMA</strong> telah menyerahkan barang material roster beton kepada <strong>PIHAK KEDUA</strong>, dan <strong>PIHAK KEDUA</strong> telah menerima material tersebut secara LENGKAP, DALAM KEADAAN BAIK, dan SESUAI SPESIFIKASI yang disepakati dengan rincian sebagai berikut:
    </p>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 35px;">No</th>
                <th>Deskripsi Barang / Spesifikasi Material</th>
                <th style="width: 90px;">Jumlah Fisik</th>
                <th style="width: 110px;">Status Mutu</th>
                <th style="width: 140px;">Keterangan Serah Terima</th>
            </tr>
        </thead>
        <tbody>
            @php $totalPcs = 0; @endphp
            @foreach($document->items as $idx => $it)
                @php 
                    $qty = (int)($it['quantity'] ?? 0);
                    $totalPcs += $qty;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td><strong>{{ $it['product_name'] ?? 'Roster Beton Arsitektural' }}</strong></td>
                    <td style="text-align: right; font-weight: 700;">{{ number_format($qty, 0, ',', '.') }} pcs</td>
                    <td style="text-align: center; color: #15803d; font-weight: 700;">✓ Baik &amp; Utuh</td>
                    <td style="text-align: center; font-size: 10px; color: #475569;">Lengkap di Titik Bongkar</td>
                </tr>
            @endforeach
            <tr style="background: #f8fafc; font-weight: 800;">
                <td colspan="2" style="text-align: right;">TOTAL BARANG YANG DISERAH-TERIMAKAN:</td>
                <td style="text-align: right; color: #ea580c; font-size: 12px;">{{ number_format($totalPcs, 0, ',', '.') }} pcs</td>
                <td colspan="2" style="text-align: center; color: #15803d;">100% TELAH DITERIMA</td>
            </tr>
        </tbody>
    </table>

    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-left: 4px solid #16a34a; padding: 8px 12px; margin-bottom: 12px; font-size: 11px;">
        <strong>Pernyataan Bersama:</strong>
        <ul style="margin: 3px 0 0 16px; padding: 0;">
            <li>Pemeriksaan fisik kuantitas dan mutu telah dilaksanakan bersama oleh tim logistik pabrik dan perwakilan lapangan kontraktor di titik bongkar proyek.</li>
            <li>Dengan ditandatanganinya Berita Acara ini, kepemilikan material sepenuhnya beralih kepada <strong>PIHAK KEDUA</strong>, dan <strong>PIHAK PERTAMA</strong> berhak memproses penagihan sisa pelunasan sesuai kesepakatan kontrak.</li>
        </ul>
    </div>

    @if(!empty($document->notes))
    <div style="margin: 8px 0; font-size: 10.5px; color: #475569;">
        <strong>Catatan Tambahan:</strong> {{ $document->notes }}
    </div>
    @endif

    <p style="margin-top: 10px;">Demikian Berita Acara Serah Terima (BAST) ini dibuat dengan sebenarnya dalam kondisi sadar untuk dapat dipergunakan sebagaimana mestinya.</p>

    @php
        $isBlankSign = request()->query('blank_sign') == '1' || request()->query('blank') == '1';
    @endphp

    <table class="signatures">
        <tr>
            <td>
                <div style="font-weight: 700; color: #475569;">Yang Menyerahkan (Pabrik)</div>
                <div style="font-weight: 800; color: #0f172a; margin-top: 2px;">INDOROSTER INDONESIA</div>
                
                <div class="sign-space">
                    <span style="font-size: 10px; color: #94a3b8; border: 1px dashed #cbd5e1; padding: 4px 10px; border-radius: 4px;">(Tanda Tangan &amp; Cap Pabrik)</span>
                </div>

                @if($isBlankSign)
                    <div style="font-weight: 800; color: #0f172a; font-size: 12px;">( .................................................. )</div>
                @else
                    <div style="font-weight: 800; text-decoration: underline; color: #0f172a; font-size: 12px;">{{ $document->issued_by ?: 'Abdul Hamid' }}</div>
                @endif
                <div style="font-size: 10px; color: #64748b;">{{ $document->extra_data['signer_role'] ?? 'Petugas Logistik / Pengiriman' }}</div>
            </td>
            <td>
                <div style="font-weight: 700; color: #475569;">Yang Menerima (Kontraktor Proyek)</div>
                <div style="font-weight: 800; color: #0f172a; margin-top: 2px;">{{ strtoupper($document->client_name) }}</div>
                
                <div class="sign-space">
                    <span style="font-size: 10px; color: #94a3b8; border: 1px dashed #cbd5e1; padding: 4px 10px; border-radius: 4px;">(Tanda Tangan &amp; Cap Proyek)</span>
                </div>

                <div style="font-weight: 800; text-decoration: underline; color: #0f172a; font-size: 12px;">( .................................................. )</div>
                <div style="font-size: 10px; color: #64748b;">Pelaksana / Logistik / Mandor Penerima</div>
            </td>
        </tr>
    </table>

    {{-- FOOTER RESMI DOKUMEN SISTEM --}}
    <div style="margin-top: 30px; border-top: 1px solid #e2e8f0; padding-top: 8px; font-size: 9.5px; color: #94a3b8; width: 100%; display: table; page-break-inside: avoid;">
        <div style="display: table-cell; text-align: left; vertical-align: middle;">
            <strong style="color: #64748b;">INDOROSTER INDONESIA</strong> &bull; Pabrik Roster Minimalis &amp; Bata Ekspos<br>
            Website: <span style="color: #ea580c; font-weight: 600;">www.indoroster.com</span> &bull; Email: indoroster.official@gmail.com
        </div>
        <div style="display: table-cell; text-align: right; vertical-align: middle;">
            Dokumen Resmi Terverifikasi Sistem IndoRoster<br>
            Dicetak pada: {{ now()->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB
        </div>
    </div>

</body>
</html>

