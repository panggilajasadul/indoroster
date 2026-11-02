<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $document->getTypeLabel() }} - {{ $document->document_number }}</title>
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
        .logo { width: 45%; }
        .logo img { max-height: 65px; }
        .company-info { text-align: right; font-size: 10px; color: #475569; line-height: 1.35; width: 55%; }
        
        .title-box {
            text-align: center;
            margin-bottom: 16px;
        }
        .title {
            font-size: 15px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }
        .doc-number {
            font-size: 11.5px;
            font-weight: 700;
            color: #ea580c;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 11px;
        }
        .meta-table td {
            vertical-align: top;
            padding: 2px 4px;
        }
        .meta-left { width: 52%; }
        .meta-right { width: 48%; }

        .intro-box {
            margin-bottom: 12px;
            font-size: 11px;
            line-height: 1.45;
            color: #334155;
            text-align: justify;
        }

        table.items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0 12px 0;
            font-size: 11px;
        }
        table.items-table th, table.items-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
        }
        table.items-table th {
            background: #f8fafc;
            font-weight: 700;
            color: #334155;
            text-align: center;
            font-size: 10.5px;
            text-transform: uppercase;
        }

        .totals-table {
            width: 45%;
            margin-left: 55%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 11px;
        }
        .totals-table td {
            padding: 4px 6px;
            text-align: right;
        }
        .totals-table tr.grand-total td {
            font-weight: 800;
            font-size: 12.5px;
            color: #ea580c;
            border-top: 1.5px solid #cbd5e1;
            padding-top: 6px;
        }

        .terms-box {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 9px 12px;
            border-radius: 4px;
            margin-top: 10px;
            font-size: 10px;
            color: #334155;
            line-height: 1.45;
            page-break-inside: avoid;
        }
        .terms-box strong {
            color: #0f172a;
            font-size: 10.5px;
            display: block;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .signatures {
            margin-top: 25px;
            width: 100%;
            border-collapse: collapse;
            page-break-inside: avoid;
        }
        .signatures td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding: 0 10px;
        }
        .sign-space {
            height: 75px;
            position: relative;
        }
        .sign-img {
            max-height: 65px;
            max-width: 140px;
        }
        .stamp-img {
            position: absolute;
            max-height: 70px;
            left: 30%;
            top: 5px;
            opacity: 0.85;
        }
        .signer-name {
            font-weight: 800;
            font-size: 11px;
            text-decoration: underline;
            color: #0f172a;
        }
        .signer-role {
            font-size: 10px;
            color: #64748b;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT RESMI INDOROSTER --}}
    <div class="header">
        <table>
            <tr>
                <td class="logo">
                    @if(file_exists(public_path('assets/logo_indoroster-text.png')))
                        <img src="{{ public_path('assets/logo_indoroster-text.png') }}" alt="IndoRoster Logo">
                    @else
                        <span style="font-size: 20px; font-weight: 900; color: #ea580c; letter-spacing: 1px;">INDOROSTER</span>
                    @endif
                </td>
                <td class="company-info">
                    <strong style="color: #ea580c; font-size: 13px;">INDOROSTER INDONESIA</strong><br>
                    <span style="color: #64748b;">Pusat Roster Minimalis &amp; Bata Ekspos</span><br>
                    Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar,<br>
                    Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat - 41165<br>
                    WhatsApp: {{ \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847') }} | Web: www.indoroster.com
                </td>
            </tr>
        </table>
    </div>

    {{-- JUDUL DOKUMEN --}}
    <div class="title-box">
        <div class="title">SURAT PENAWARAN HARGA &amp; DUKUNGAN PABRIK</div>
        <div class="doc-number">Nomor: {{ $document->document_number }}</div>
    </div>

    {{-- METADATA DOKUMEN & TUJUAN --}}
    <table class="meta-table">
        <tr>
            <td class="meta-left">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 25%; font-weight: 700; color: #64748b;">Tanggal</td>
                        <td style="width: 5%;">:</td>
                        <td style="font-weight: 600;">{{ $document->document_date ? \Carbon\Carbon::parse($document->document_date)->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 700; color: #64748b;">Masa Berlaku</td>
                        <td>:</td>
                        <td>30 (Tiga Puluh) Hari Kalender</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 700; color: #64748b;">Sifat</td>
                        <td>:</td>
                        <td>Resmi / Penawaran B2B &amp; Proyek</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 700; color: #64748b;">Perihal</td>
                        <td>:</td>
                        <td style="font-weight: 700; color: #0f172a;">Penawaran Pengadaan Material Roster Beton</td>
                    </tr>
                </table>
            </td>
            <td class="meta-right">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 30%; font-weight: 700; color: #64748b;">Kepada Yth.</td>
                        <td style="width: 5%;">:</td>
                        <td style="font-weight: 700; color: #0f172a;">{{ $document->client_name }}</td>
                    </tr>
                    @if(!empty($document->client_phone))
                    <tr>
                        <td style="font-weight: 700; color: #64748b;">Kontak / WA</td>
                        <td>:</td>
                        <td>{{ $document->client_phone }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="font-weight: 700; color: #64748b;">Lokasi Proyek</td>
                        <td>:</td>
                        <td>{{ $document->client_address ?: 'Wilayah Pemesan' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- TEKS PENGANTAR RESMI --}}
    <div class="intro-box">
        Dengan hormat,<br>
        Sehubungan dengan kebutuhan material dinding arsitektural dan ventilasi estetis untuk proyek Saudara, kami dari <strong>INDOROSTER INDONESIA</strong> bermaksud mengajukan rincian spesifikasi serta harga resmi terbaik sebagai berikut:
    </div>

    {{-- TABEL ITEM SPESIFIKASI & HARGA --}}
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 50%; text-align: left;">Uraian Produk &amp; Spesifikasi Mutu</th>
                <th style="width: 12%;">Volume</th>
                <th style="width: 15%; text-align: right;">Harga Satuan</th>
                <th style="width: 18%; text-align: right;">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($document->items as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item['product_name'] ?? ($item['name'] ?? 'Roster Beton') }}</strong>
                        @if(!empty($item['notes']))
                            <div style="font-size: 10px; color: #64748b;">Catatan: {{ $item['notes'] }}</div>
                        @else
                            <div style="font-size: 9.5px; color: #64748b;">Komposisi bahan berkualitas, tekstur padat, presisi &amp; rapi</div>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ number_format($item['quantity'] ?? 1, 0, ',', '.') }} {{ $item['unit'] ?? 'pcs' }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item['price'] ?? 0, 0, ',', '.') }}</td>
                    <td style="text-align: right; font-weight: 600;">Rp {{ number_format($item['total'] ?? (($item['price'] ?? 0) * ($item['quantity'] ?? 1)), 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #94a3b8; padding: 12px;">Belum ada item rincian penawaran.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- TOTAL BIAYA --}}
    <table class="totals-table">
        <tr>
            <td style="color: #64748b; font-weight: 600;">Subtotal:</td>
            <td style="font-weight: 700;">Rp {{ number_format($document->subtotal, 0, ',', '.') }}</td>
        </tr>
        @if($document->discount > 0)
        <tr>
            <td style="color: #15803d; font-weight: 600;">Diskon Khusus Proyek:</td>
            <td style="color: #15803d; font-weight: 700;">-Rp {{ number_format($document->discount, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if($document->has_tax && $document->tax_amount > 0)
        <tr>
            <td style="color: #64748b; font-weight: 600;">PPN (11%):</td>
            <td style="font-weight: 700;">Rp {{ number_format($document->tax_amount, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr class="grand-total">
            <td>Total Penawaran:</td>
            <td>Rp {{ number_format($document->grand_total, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- SYARAT DAN KETENTUAN RESMI (SOP IR-PRJ-01) --}}
    <div class="terms-box">
        <strong>Syarat &amp; Ketentuan Penawaran (SOP IndoRoster):</strong>
        <ol style="margin: 0; padding-left: 15px;">
            <li><strong>Masa Berlaku</strong>: Penawaran berlaku 30 (tiga puluh) hari kalender terhitung sejak tanggal surat diterbitkan.</li>
            <li><strong>Skema Pembayaran</strong>: Uang Muka (DP) minimal 50% untuk penguncian antrean cetak &amp; jadwal produksi. Pelunasan sebelum keberangkatan armada (atau termin bertahap sesuai kesepakatan PJB).</li>
            <li><strong>Ongkos Kirim &amp; Bongkar</strong>: Gratis ongkos kirim area Jabodetabek minimal order 100 pcs (franco proyek di atas armada / penurunan dibantu tenaga proyek atau sesuai kesepakatan).</li>
            <li><strong>Jaminan Mutu &amp; Garansi Pecah</strong>: Material yang pecah atau cacat akibat pengiriman ekspedisi pabrik diganti 100% baru tanpa tambahan biaya material.</li>
            <li><strong>Standar Material</strong>: Roster berkualitas dengan kepadatan tinggi dan dimensi presisi (tahan lumut, kokoh untuk iklim tropis).</li>
        </ol>
    </div>

    @php
        $isBlankSign = request()->query('blank_sign') == '1' || request()->query('blank') == '1';
    @endphp
    {{-- TANDA TANGAN 2 BELAH PIHAK --}}
    <table class="signatures">
        <tr>
            <td>
                <div style="font-weight: 700; color: #475569; margin-bottom: 5px;">Disetujui Oleh (Pemesan / Kontraktor):</div>
                <div class="sign-space"></div>
                <div class="signer-name">( {{ $document->client_name ?: '..........................................' }} )</div>
                <div class="signer-role">Penanggung Jawab Proyek</div>
            </td>
            <td>
                <div style="font-weight: 700; color: #475569; margin-bottom: 5px;">Hormat Kami,</div>
                <div class="sign-space">
                    @if(!$isBlankSign)
                        @if(!empty($document->signature_path) && file_exists(storage_path('app/public/'.$document->signature_path)))
                            <img src="{{ storage_path('app/public/'.$document->signature_path) }}" class="sign-img">
                        @endif
                        @php $stampPath = \App\Models\SiteSetting::getValue('doc_stamp_path'); @endphp
                        @if(!empty($stampPath) && file_exists(public_path($stampPath)))
                            <img src="{{ public_path($stampPath) }}" class="stamp-img">
                        @endif
                    @endif
                </div>
                @if($isBlankSign)
                    <div class="signer-name">( .......................................... )</div>
                @else
                    <div class="signer-name">{{ $document->issued_by ?: 'Abdul Hamid' }}</div>
                @endif
                <div class="signer-role">{{ $document->extra_data['signer_role'] ?? 'Bagian Penjualan' }}</div>
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

