<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Perjanjian Kerja Sama (SPK) - {{ $document->document_number }}</title>
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
            font-size: 15px;
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
        
        .pasal-title {
            font-weight: 800;
            font-size: 11.5px;
            color: #0f172a;
            text-align: center;
            margin: 10px 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .pasal-sub {
            font-weight: 700;
            text-align: center;
            font-size: 10.5px;
            color: #475569;
            margin-bottom: 6px;
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
            background: #f1f5f9;
            font-weight: 700;
            color: #334155;
            text-align: center;
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
        .meterai-box {
            width: 95px;
            height: 75px;
            border: 1.5px dashed #94a3b8;
            margin: 15px auto;
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
        <div class="title">SURAT PERJANJIAN KERJA SAMA (SPK)</div>
        <div style="font-size: 12px; font-weight: 600; color: #334155;">PENGADAAN MATERIAL ROSTER BETON ARSITEKTURAL</div>
        <div class="doc-number">Nomor: {{ $document->document_number }}</div>
    </div>

    <p style="text-align: justify; margin-bottom: 8px;">
        Pada hari ini, <strong>{{ \Carbon\Carbon::parse($document->document_date)->translatedFormat('l') }}</strong> tanggal <strong>{{ \Carbon\Carbon::parse($document->document_date)->translatedFormat('d F Y') }}</strong> ({{ \Carbon\Carbon::parse($document->document_date)->format('d/m/Y') }}), bertempat di Purwakarta, telah disepakati perjanjian kerja sama pengadaan material roster beton oleh dan antara para pihak di bawah ini:
    </p>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px; font-size: 11px;">
        <tr>
            <td style="width: 25px; vertical-align: top; font-weight: 700;">1.</td>
            <td style="width: 140px; vertical-align: top; font-weight: 700;">Nama Perusahaan</td>
            <td style="width: 10px; vertical-align: top;">:</td>
            <td style="vertical-align: top;"><strong>INDOROSTER INDONESIA</strong> (Pabrik Roster Plered Purwakarta)</td>
        </tr>
        <tr>
            <td></td>
            <td style="vertical-align: top;">Nama Penanggung Jawab</td>
            <td style="vertical-align: top;">:</td>
            <td style="vertical-align: top;"><strong>ABDUL HAMID</strong> (Divisi Keuangan &amp; Distribusi Pabrik)</td>
        </tr>
        <tr>
            <td></td>
            <td style="vertical-align: top;">Alamat Operasional</td>
            <td style="vertical-align: top;">:</td>
            <td style="vertical-align: top;">Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat - 41165</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="3" style="padding-top: 2px;">Bertindak untuk dan atas nama <strong>INDOROSTER INDONESIA</strong>, selanjutnya disebut sebagai <strong>PIHAK PERTAMA (Penyedia Material)</strong>.</td>
        </tr>
        <tr><td colspan="4" style="height: 6px;"></td></tr>
        <tr>
            <td style="vertical-align: top; font-weight: 700;">2.</td>
            <td style="vertical-align: top; font-weight: 700;">Nama Klien / Instansi</td>
            <td style="vertical-align: top;">:</td>
            <td style="vertical-align: top;"><strong>{{ strtoupper($document->client_name) }}</strong></td>
        </tr>
        <tr>
            <td></td>
            <td style="vertical-align: top;">Alamat Proyek / Kantor</td>
            <td style="vertical-align: top;">:</td>
            <td style="vertical-align: top;">{{ $document->client_address ?: 'Lokasi Titik Proyek Pemesan' }}</td>
        </tr>
        @if($document->client_phone)
        <tr>
            <td></td>
            <td style="vertical-align: top;">Kontak / WhatsApp</td>
            <td style="vertical-align: top;">:</td>
            <td style="vertical-align: top;">{{ $document->client_phone }}</td>
        </tr>
        @endif
        <tr>
            <td></td>
            <td colspan="3" style="padding-top: 2px;">Bertindak untuk dan atas nama <strong>{{ strtoupper($document->client_name) }}</strong>, selanjutnya disebut sebagai <strong>PIHAK KEDUA (Pembeli / Kontraktor)</strong>.</td>
        </tr>
    </table>

    <p style="margin-bottom: 6px;">Kedua belah pihak sepakat mengikatkan diri dalam Perjanjian Pengadaan Material Roster Beton dengan pasal-pasal ketentuan sebagai berikut:</p>

    <div class="pasal-title">PASAL 1: RINCIAN BARANG &amp; NILAI KONTRAK</div>
    <div class="pasal-sub">Spesifikasi Material dan Total Biaya Pengadaan</div>
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Nama Produk &amp; Spesifikasi</th>
                <th style="width: 75px;">Qty (Pcs)</th>
                <th style="width: 105px;">Harga Satuan</th>
                <th style="width: 120px;">Total Nilai</th>
            </tr>
        </thead>
        <tbody>
            @php $totalPcs = 0; @endphp
            @foreach($document->items as $idx => $it)
                @php 
                    $qty = (int)($it['quantity'] ?? 0);
                    $price = (float)($it['price'] ?? 0);
                    $sub = (float)($it['total'] ?? ($qty * $price));
                    $totalPcs += $qty;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td><strong>{{ $it['product_name'] ?? 'Roster Beton Arsitektural' }}</strong></td>
                    <td style="text-align: right;">{{ number_format($qty, 0, ',', '.') }} pcs</td>
                    <td style="text-align: right;">Rp {{ number_format($price, 0, ',', '.') }}</td>
                    <td style="text-align: right; font-weight: 700;">Rp {{ number_format($sub, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            <tr style="background: #f8fafc; font-weight: 800;">
                <td colspan="2" style="text-align: right;">TOTAL NILAI KONTRAK MATERIAL ({{ number_format($totalPcs, 0, ',', '.') }} Pcs):</td>
                <td style="text-align: right;">{{ number_format($totalPcs, 0, ',', '.') }} pcs</td>
                <td colspan="2" style="text-align: right; color: #ea580c; font-size: 12px;">Rp {{ number_format($document->grand_total, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="pasal-title">PASAL 2: TAHAPAN PEMBAYARAN</div>
    <div class="pasal-sub">Sistem Penagihan Bertahap</div>
    <ol style="margin: 0 0 8px 18px; padding: 0;">
        <li style="margin-bottom: 3px;"><strong>Pembayaran Uang Muka (DP):</strong> Disepakati oleh kedua pihak untuk mengunci antrean cetak pabrik dan komitmen produksi sebelum jadwal pengiriman dimulai.</li>
        <li style="margin-bottom: 3px;"><strong>Pembayaran Pelunasan Akhir:</strong> Dilunasi setelah seluruh material dinyatakan selesai dikirim dan ditandatanganinya Berita Acara Serah Terima (BAST) Fisik di lokasi proyek.</li>
        <li>Seluruh transaksi sah wajib ditransfer langsung ke Rekening Resmi Pabrik <strong>Bank BRI (4356-0100-9396-502), BCA (231-324-2412), atau Mandiri (1730-0197-04155) a/n ABDUL HAMID</strong>.</li>
    </ol>

    <div class="pasal-title">PASAL 3: PENGIRIMAN &amp; TOLERANSI PECAH DI JALAN</div>
    <div class="pasal-sub">Ketentuan Logistik Armada Truk</div>
    <ol style="margin: 0 0 8px 18px; padding: 0;">
        <li style="margin-bottom: 3px;">Material dikirim menggunakan armada truk langsung dari Pabrik Purwakarta menuju titik lokasi proyek yang dapat dilalui dan diakses oleh armada truk.</li>
        <li style="margin-bottom: 3px;">Mengingat sifat material beton arsitektural dan goncangan jalan, <strong>toleransi pecah sudut/retak wajar ditetapkan maksimal 1% - 2%</strong> dari total kuantitas kirim.</li>
        <li>Klaim garansi kerusakan melebihi batas toleransi wajib dicantumkan pada Surat Jalan saat serah terima barang dan disertai bukti dokumentasi foto bersama sopir armada.</li>
    </ol>

    @if(!empty($document->notes))
    <div style="margin: 10px 0; padding: 6px 10px; background: #fffbeb; border-left: 3px solid #f59e0b; font-size: 10.5px;">
        <strong>Catatan Tambahan Kesepakatan:</strong> {{ $document->notes }}
    </div>
    @endif

    <p style="margin-top: 10px;">Demikian Surat Perjanjian ini dibuat rangkap 2 (dua) bermeterai cukup yang masing-masing mempunyai kekuatan hukum yang sama dan ditandatangani oleh kedua belah pihak.</p>

    <table class="signatures">
        <tr>
            <td>
                <div style="font-weight: 700; color: #475569;">PIHAK PERTAMA (Penyedia)</div>
                <div style="font-weight: 800; color: #0f172a; margin-top: 2px;">INDOROSTER INDONESIA</div>
                
                <div class="meterai-box">
                    <span style="font-size: 8px; font-weight: 700;">TEMPAT</span>
                    <span style="font-size: 9.5px; font-weight: 800; margin: 2px 0;">METERAI</span>
                    <span style="font-size: 8px; font-weight: 700; color: #ea580c;">Rp 10.000</span>
                    <span style="font-size: 7px; color: #94a3b8;">(TTD Menyilang)</span>
                </div>

                <div style="font-weight: 800; text-decoration: underline; color: #0f172a; font-size: 12px;">ABDUL HAMID</div>
                <div style="font-size: 10px; color: #64748b;">Divisi Keuangan &amp; Distribusi Pabrik</div>
            </td>
            <td>
                <div style="font-weight: 700; color: #475569;">PIHAK KEDUA (Pembeli)</div>
                <div style="font-weight: 800; color: #0f172a; margin-top: 2px;">{{ strtoupper($document->client_name) }}</div>
                
                <div class="meterai-box">
                    <span style="font-size: 8px; font-weight: 700;">TEMPAT</span>
                    <span style="font-size: 9.5px; font-weight: 800; margin: 2px 0;">METERAI</span>
                    <span style="font-size: 8px; font-weight: 700; color: #ea580c;">Rp 10.000</span>
                    <span style="font-size: 7px; color: #94a3b8;">(TTD Menyilang)</span>
                </div>

                <div style="font-weight: 800; text-decoration: underline; color: #0f172a; font-size: 12px;">( .................................................. )</div>
                <div style="font-size: 10px; color: #64748b;">Direktur / Project Manager / Procurement</div>
            </td>
        </tr>
    </table>

</body>
</html>

