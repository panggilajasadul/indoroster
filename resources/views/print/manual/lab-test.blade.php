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
        .subtitle {
            font-size: 11px;
            color: #475569;
            font-weight: 600;
        }
        .doc-number {
            font-size: 11.5px;
            font-weight: 700;
            color: #ea580c;
            margin-top: 3px;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 11px;
        }
        .meta-table td {
            vertical-align: top;
            padding: 2px 4px;
        }
        .meta-left { width: 52%; }
        .meta-right { width: 48%; }

        .section-heading {
            font-weight: 800;
            font-size: 11.5px;
            color: #0f172a;
            background: #f1f5f9;
            padding: 5px 8px;
            margin: 12px 0 6px 0;
            border-left: 3px solid #ea580c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        table.spec-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10.5px;
        }
        table.spec-table th, table.spec-table td {
            border: 1px solid #cbd5e1;
            padding: 5px 7px;
        }
        table.spec-table th {
            background: #f8fafc;
            font-weight: 700;
            color: #334155;
            text-align: left;
        }

        .notice-box {
            border: 1.5px solid #fed7aa;
            background: #fffaf5;
            padding: 9px 12px;
            border-radius: 4px;
            margin-top: 10px;
            font-size: 10px;
            color: #7c2d12;
            line-height: 1.45;
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
        <div class="title">SERTIFIKAT MUTU PRODUKSI &amp; KELAYAKAN TEKNIS</div>
        <div class="subtitle">Standard Spesifikasi Bata Beton Berongga / Roster Arsitektural</div>
        <div class="doc-number">Nomor Sertifikat: {{ $document->document_number }}</div>
    </div>

    {{-- METADATA DOKUMEN & PEMESAN --}}
    <table class="meta-table">
        <tr>
            <td class="meta-left">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 32%; font-weight: 700; color: #64748b;">Tanggal Rilis</td>
                        <td style="width: 5%;">:</td>
                        <td style="font-weight: 600;">{{ $document->document_date ? \Carbon\Carbon::parse($document->document_date)->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 700; color: #64748b;">Metode Produksi</td>
                        <td>:</td>
                        <td>Presisi Standar Arsitektur</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 700; color: #64748b;">Bahan Baku Utama</td>
                        <td>:</td>
                        <td>Pasir Abu Batu Murni &amp; Semen Pilihan</td>
                    </tr>
                </table>
            </td>
            <td class="meta-right">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 30%; font-weight: 700; color: #64748b;">Klien / Proyek</td>
                        <td style="width: 5%;">:</td>
                        <td style="font-weight: 700; color: #0f172a;">{{ $document->client_name }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 700; color: #64748b;">Alamat Proyek</td>
                        <td>:</td>
                        <td>{{ $document->client_address ?: 'Wilayah Pemesan' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- RINCIAN SAMPEL PRODUK YANG DIUJI --}}
    <div class="section-heading">1. Rincian Sampel Material Produksi</div>
    <table class="spec-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 45%;">Tipe / Motif Produk Roster</th>
                <th style="width: 25%;">Dimensi Standar</th>
                <th style="width: 25%;">Hasil Visual &amp; Fisik</th>
            </tr>
        </thead>
        <tbody>
            @forelse($document->items as $idx => $item)
            <tr>
                <td style="text-align: center;">{{ $idx + 1 }}</td>
                <td><strong>{{ $item['product_name'] ?? ($item['name'] ?? 'Roster Beton Minimalis') }}</strong></td>
                <td>20 x 20 x 10 cm (Toleransi &plusmn; 2mm)</td>
                <td><span style="color: #15803d; font-weight: 700;">&check; Padat, Presisi, Sudut Siku Sempurna</span></td>
            </tr>
            @empty
            <tr>
                <td style="text-align: center;">1</td>
                <td><strong>Roster Beton Minimalis IndoRoster</strong></td>
                <td>20 x 20 x 10 cm (Toleransi &plusmn; 2mm)</td>
                <td><span style="color: #15803d; font-weight: 700;">&check; Padat, Presisi, Sudut Siku Sempurna</span></td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- PARAMETER PENGUJIAN MUTU INTERNAL --}}
    <div class="section-heading">2. Hasil Uji Karakteristik Material Internal (QC Pabrik)</div>
    <table class="spec-table">
        <thead>
            <tr>
                <th style="width: 35%;">Parameter Pengujian</th>
                <th style="width: 25%;">Standar / Acuan</th>
                <th style="width: 20%;">Hasil Uji Rata-rata</th>
                <th style="width: 20%;">Kesimpulan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Kuat Tekan (Compressive Strength)</strong></td>
                <td>SNI 03-0349-1989 (Bata Beton Berongga)</td>
                <td>Tumbuk Padat Plat Baja</td>
                <td style="color: #15803d; font-weight: 700;">MEMENUHI SYARAT</td>
            </tr>
            <tr>
                <td><strong>Kepadatan &amp; Homogenitas</strong></td>
                <td>Vibrasi &amp; Tumbukan Plat Baja</td>
                <td>Tinggi / Sangat Rapat</td>
                <td style="color: #15803d; font-weight: 700;">SANGAT BAIK</td>
            </tr>
            <tr>
                <td><strong>Daya Serap Air (Water Absorption)</strong></td>
                <td>Rendah (&lt; 15%)</td>
                <td>Tahan Rembesan Hujan</td>
                <td style="color: #15803d; font-weight: 700;">MEMENUHI SYARAT</td>
            </tr>
            <tr>
                <td><strong>Ketahanan Cuaca &amp; Jamur</strong></td>
                <td>Iklim Tropis Lembab</td>
                <td>Formula Abu Batu Alami</td>
                <td style="color: #15803d; font-weight: 700;">OPTIMAL</td>
            </tr>
        </tbody>
    </table>

    {{-- PERNYATAAN RESMI DAN INTEGRITAS KONTEN --}}
    <div class="notice-box">
        <strong>Pernyataan Resmi Pabrik &amp; Etika Transparansi Mutu:</strong><br>
        1. Dokumen ini merupakan <em>Quality Control Certificate</em> internal resmi dari pabrik IndoRoster Cadasmekar, Tegalwaru, Purwakarta.<br>
        2. Roster diproduksi murni menggunakan formula pasir abu batu pilihan dan semen berkualitas tinggi dengan kepadatan optimal, terbebas dari metode cetak basah/tuang biasa.<br>
        3. <strong>Dukungan Uji Lab Resmi KAN</strong>: Atas kebutuhan dokumen teknis tender pemerintah atau proyek swasta skala besar, IndoRoster siap berkoordinasi dan memfasilitasi pengujian sampel resmi di Balai Laboratorium Bahan Bangunan yang terakreditasi KAN (Komite Akreditasi Nasional) sesuai spesifikasi yang dipersyaratkan.
    </div>

    @php
        $isBlankSign = request()->query('blank_sign') == '1' || request()->query('blank') == '1';
    @endphp
    {{-- TANDA TANGAN TIM KONTROL MUTU --}}
    <table class="signatures">
        <tr>
            <td>
                <div style="font-weight: 700; color: #475569; margin-bottom: 5px;">Penerima / Verifikator Proyek:</div>
                <div class="sign-space"></div>
                <div class="signer-name">( {{ $document->client_name ?: '..........................................' }} )</div>
                <div class="signer-role">Site Engineer / Tim Proyek</div>
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
                <div class="signer-role">{{ $document->extra_data['signer_role'] ?? 'Quality Control' }}</div>
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
