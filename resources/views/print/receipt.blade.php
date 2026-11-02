@php
    function terbilang($nilai) {
        $nilai = abs($nilai);
        $huruf = ["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];
        $temp = "";
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = terbilang($nilai - 10) . " Belas";
        } else if ($nilai < 100) {
            $temp = terbilang($nilai / 10) . " Puluh" . terbilang($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " Seratus" . terbilang($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = terbilang($nilai / 100) . " Ratus" . terbilang($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " Seribu" . terbilang($nilai - 1000);
        } else if ($nilai < 1000000) {
            $temp = terbilang($nilai / 1000) . " Ribu" . terbilang($nilai % 1000);
        } else if ($nilai < 1000000000) {
            $temp = terbilang($nilai / 1000000) . " Juta" . terbilang($nilai % 1000000);
        } else if ($nilai < 1000000000000) {
            $temp = terbilang($nilai / 1000000000) . " Milyar" . terbilang(fmod($nilai, 1000000000));
        } else if ($nilai < 1000000000000000) {
            $temp = terbilang($nilai / 1000000000000) . " Trilyun" . terbilang(fmod($nilai, 1000000000000));
        }
        return $temp;
    }

    $order = $payment->order;
    $amount = (float) $payment->gross_amount;
    $terbilangText = trim(terbilang($amount)) . " Rupiah";
    $grandTotal = (float) ($order ? $order->grand_total : $amount);
    
    // Hitung akumulasi pembayaran yang sah HANYA s/d tahap pembayaran kuitansi ini
    $allPayments = $order ? $order->getValidPayments() : collect([$payment]);
    $paymentsUpToThis = $allPayments->filter(fn($p) => $p->id <= $payment->id);
    if ($paymentsUpToThis->isEmpty()) {
        $paymentsUpToThis = collect([$payment]);
    }
    $cumulativePaid = (float) $paymentsUpToThis->sum('gross_amount');
    if ($cumulativePaid <= 0) {
        $cumulativePaid = $amount;
    }
    $remainingAfterThis = max(0, $grandTotal - $cumulativePaid);
    $isLunasAfterThis = ($remainingAfterThis <= 0);

    // Stempel & TTD Path Discovery
    $stampPath = null;
    $sigPath = null;
    $combinedPath = null;

    $combinedCandidates = ['stamp_signature.png', 'stempel_ttd.png', 'ttd_stempel.png', 'stamp_ttd.png', 'signature_stamp.png', 'stempel_dan_ttd.png', 'stempel_ttd.PNG', 'stamp_signature.PNG', 'stempel_ttd.jpg'];
    $stampCandidates = ['stamp.png', 'stempel.png', 'company_stamp.png', 'stempel_indoroster.png', 'stamp.PNG', 'stempel.PNG', 'stamp.jpg', 'stempel.jpg', 'stempel_pabrik.png'];
    $sigCandidates = ['signature.png', 'ttd.png', 'tanda_tangan.png', 'signature.PNG', 'ttd.PNG', 'signature.jpg', 'ttd.jpg'];

    foreach ($combinedCandidates as $f) {
        if (file_exists(public_path('assets/' . $f))) { $combinedPath = public_path('assets/' . $f); break; }
        if (file_exists(base_path('assets/' . $f))) { $combinedPath = base_path('assets/' . $f); break; }
    }
    if (!$combinedPath) {
        foreach ($stampCandidates as $f) {
            if (file_exists(public_path('assets/' . $f))) { $stampPath = public_path('assets/' . $f); break; }
            if (file_exists(base_path('assets/' . $f))) { $stampPath = base_path('assets/' . $f); break; }
        }
        foreach ($sigCandidates as $f) {
            if (file_exists(public_path('assets/' . $f))) { $sigPath = public_path('assets/' . $f); break; }
            if (file_exists(base_path('assets/' . $f))) { $sigPath = base_path('assets/' . $f); break; }
        }
    }
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kuitansi-{{ $payment->receipt_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 13px;
            color: #1e293b;
            background: #fff;
            padding: 30px;
        }
        .container {
            border: 2px solid #0f172a;
            border-radius: 8px;
            padding: 24px 30px;
            background: #ffffff;
            position: relative;
        }
        .header {
            display: table;
            width: 100%;
            border-bottom: 2px dashed #cbd5e1;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-left {
            display: table-cell;
            vertical-align: middle;
            width: 60%;
        }
        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            width: 40%;
        }
        .company-name {
            font-size: 19px;
            font-weight: 800;
            color: #ea580c;
            letter-spacing: -0.5px;
        }
        .company-tagline {
            font-size: 11px;
            color: #475569;
            margin-top: 2px;
            font-weight: 600;
        }
        .title {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .receipt-no {
            font-size: 13px;
            font-weight: 700;
            color: #ea580c;
            margin-top: 4px;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .content-table td {
            padding: 8px 6px;
            vertical-align: top;
        }
        .content-table .label {
            width: 28%;
            font-weight: 600;
            color: #475569;
        }
        .content-table .colon {
            width: 3%;
            text-align: center;
        }
        .content-table .value {
            width: 69%;
            color: #0f172a;
        }
        .terbilang-box {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-left: 4px solid #ea580c;
            padding: 8px 12px;
            font-style: italic;
            font-weight: 600;
            color: #1e293b;
            border-radius: 4px;
        }
        .nominal-badge {
            display: inline-block;
            background: #f0fdf4;
            border: 1.5px solid #22c55e;
            color: #15803d;
            font-size: 18px;
            font-weight: 800;
            padding: 6px 16px;
            border-radius: 6px;
        }
        .summary-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 20px;
            display: table;
            width: 100%;
        }
        .summary-col {
            display: table-cell;
            width: 33.3%;
            vertical-align: middle;
        }
        .summary-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
            font-weight: 600;
        }
        .summary-value {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 3px;
        }
        .footer {
            display: table;
            width: 100%;
            margin-top: 15px;
        }
        .footer-note {
            display: table-cell;
            width: 55%;
            vertical-align: bottom;
            font-size: 11px;
            color: #64748b;
            line-height: 1.5;
            padding-right: 15px;
        }
        .footer-sign {
            display: table-cell;
            width: 45%;
            text-align: center;
            vertical-align: top;
        }
        @media print {
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="header-left">
            <table style="border-collapse: collapse;">
                <tr>
                    @if(file_exists(public_path('assets/logo_indoroster-text.png')))
                    <td style="vertical-align: middle; padding-right: 12px;">
                        <img src="{{ public_path('assets/logo_indoroster-text.png') }}" alt="Indoroster Logo" style="max-height: 65px;">
                    </td>
                    @endif
                    <td style="vertical-align: middle;">
                        <div class="company-name" style="font-size: 15px; font-weight: 800; color: #ea580c;">INDOROSTER INDONESIA</div>
                        <div class="company-tagline" style="font-size: 10.5px; color: #64748b; font-weight: 600; margin-top: 1px;">Pusat Roster Minimalis &amp; Bata Ekspos</div>
                        <div style="font-size: 10px; color: #64748b; margin-top: 3px; line-height: 1.35;">
                            Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar,<br>
                            Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat - 41165<br>
                            WhatsApp: {{ \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847') }} | Web: www.indoroster.com
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="header-right">
            <div class="title">KUITANSI PEMBAYARAN</div>
            <div class="receipt-no">{{ $payment->receipt_number }}</div>
        </div>
    </div>

    <table class="content-table">
        <tr>
            <td class="label">Telah Diterima Dari</td>
            <td class="colon">:</td>
            <td class="value">
                <strong style="font-size: 14px;">{{ $order->shipping_name ?? $order->user?->name ?? 'Pelanggan' }}</strong>
                @if($order && $order->shipping_phone)
                    <span style="color: #64748b; font-size: 12px;">({{ $order->shipping_phone }})</span>
                @endif
                @if($order && $order->shipping_address)
                    <div style="color: #64748b; font-size: 11.5px; margin-top: 2px;">{{ $order->shipping_address }} ({{ $order->shipping_city }})</div>
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Uang Sejumlah</td>
            <td class="colon">:</td>
            <td class="value">
                <div class="terbilang-box">
                    "{{ $terbilangText }}"
                </div>
            </td>
        </tr>
        <tr>
            <td class="label">Untuk Pembayaran</td>
            <td class="colon">:</td>
            <td class="value">
                <strong>{{ $payment->installment_title }}</strong> untuk Pesanan Nomor <strong>{{ $order->order_number ?? '-' }}</strong>
                @if($payment->notes)
                    <div style="color: #475569; font-size: 12px; margin-top: 2px;">Catatan: {{ $payment->notes }}</div>
                @endif
            </td>
        </tr>
        <tr>
            <td class="label">Metode Pembayaran</td>
            <td class="colon">:</td>
            <td class="value">
                {{ $payment->payment_type_label }}
            </td>
        </tr>
        <tr>
            <td class="label">Nominal Diterima</td>
            <td class="colon">:</td>
            <td class="value">
                <div class="nominal-badge">
                    Rp {{ number_format($amount, 0, ',', '.') }}
                </div>
            </td>
        </tr>
    </table>

    {{-- Kotak Status Pelunasan Proyek S.d Tahap Ini --}}
    @if($order)
    <div class="summary-box">
        <div class="summary-col">
            <div class="summary-label">Total Nilai Pesanan</div>
            <div class="summary-value">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
        </div>
        <div class="summary-col">
            <div class="summary-label">Total Diterima (s.d Tahap Ini)</div>
            <div class="summary-value" style="color: #16a34a;">Rp {{ number_format($cumulativePaid, 0, ',', '.') }}</div>
        </div>
        <div class="summary-col">
            <div class="summary-label">Sisa Tagihan Setelah Tahap Ini</div>
            <div class="summary-value" style="color: {{ $isLunasAfterThis ? '#16a34a' : '#dc2626' }};">
                {{ $isLunasAfterThis ? 'LUNAS (Rp 0)' : 'Rp ' . number_format($remainingAfterThis, 0, ',', '.') }}
            </div>
        </div>
    </div>
    @endif

    <div class="footer">
        <div class="footer-note">
            <strong>Catatan:</strong><br>
            • Kuitansi ini merupakan bukti penerimaan pembayaran yang sah yang diterbitkan otomatis oleh sistem resmi IndoRoster.<br>
            • Harap simpan bukti kuitansi ini sebagai referensi administrasi proyek.
        </div>
        <div class="footer-sign">
            @php
                $sigDate = $payment->paid_at ?: ($payment->created_at ?: ($payment->order?->created_at ?: now()));
                $invoice = $payment->order?->invoice;
                $sigCity = $invoice?->signature_city ?: 'Purwakarta';
                $sigName = $invoice?->signature_name ?: 'Abdul Hamid';
                $sigTitle = $invoice?->signature_title ?: 'Bagian Keuangan Pabrik IndoRoster';
                $showSigStamp = request()->has('stamp') ? (bool) request()->get('stamp') : ($invoice?->show_signature_stamp ?? true);
            @endphp
            <div style="font-size: 11.5px; color: #475569; margin-bottom: 4px;">
                {{ $sigCity }}, {{ $sigDate->translatedFormat('d F Y') }}
            </div>
            <div style="font-size: 12.5px; font-weight: 600; color: #334155; margin-bottom: 2px;">
                Hormat Kami,
            </div>

            <div style="position: relative; height: 115px; width: 250px; margin: 0 auto;">
                @if($showSigStamp)
                    @if($combinedPath)
                        <img src="{{ $combinedPath }}" alt="Stempel & TTD" style="max-height: 110px; max-width: 250px; object-fit: contain;">
                    @elseif($stampPath || $sigPath)
                        @if($stampPath)
                            <img src="{{ $stampPath }}" alt="Stempel" style="position: absolute; left: 10px; top: 5px; width: 100px; height: 100px; object-fit: contain; opacity: 0.95; transform: rotate(-6deg);">
                        @endif
                        @if($sigPath)
                            <img src="{{ $sigPath }}" alt="TTD" style="position: absolute; left: 30px; top: 0px; width: 210px; height: 105px; object-fit: contain; z-index: 2;">
                        @endif
                    @else
                        <!-- Stempel & TTD Digital Resmi Pabrik IndoRoster -->
                        <div style="position: absolute; left: 10px; top: 5px; width: 95px; height: 95px; border: 2.5px dashed #ea580c; border-radius: 50%; color: #ea580c; text-align: center; font-weight: 800; font-size: 9.5px; line-height: 1.15; padding-top: 15px; box-sizing: border-box; transform: rotate(-6deg); background: rgba(234,88,12,0.03);">
                            INDOROSTER<br>
                            <span style="display: block; border-top: 1px solid #ea580c; border-bottom: 1px solid #ea580c; color: #16a34a; font-size: 10px; margin: 3px 0;">★ SAH ★</span>
                            PLERED PWK
                        </div>
                        <div style="position: absolute; left: 60px; top: 25px; font-family: 'Brush Script MT', cursive, sans-serif; font-size: 28px; color: #0284c7; font-weight: bold; transform: rotate(-5deg); z-index: 2;">
                            IndoRoster
                        </div>
                    @endif
                @else
                    {{-- Ruang Kosong Khusus Cetak Fisik: untuk Tanda Tangan Basah, Cap Stempel Asli & Meterai --}}
                    <div style="height: 115px; position: relative; width: 100%;">
                        @if($amount >= 5000000)
                            {{-- Kotak Panduan Penempelan Meterai Rp 10.000 Sesuai UU Bea Meterai (> Rp 5 Juta) --}}
                            <div style="position: absolute; left: 15px; top: 12px; width: 85px; height: 75px; border: 1.5px dashed #94a3b8; border-radius: 4px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; background: #f8fafc; color: #64748b; padding: 4px; box-sizing: border-box;">
                                <span style="font-size: 8px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">TEMPAT</span>
                                <span style="font-size: 9.5px; font-weight: 800; color: #0f172a; margin: 2px 0;">METERAI</span>
                                <span style="font-size: 8.5px; font-weight: 700; color: #ea580c;">Rp 10.000</span>
                                <span style="font-size: 7px; color: #94a3b8; line-height: 1; margin-top: 2px;">(TTD Menyilang)</span>
                            </div>
                            <div style="position: absolute; right: 15px; bottom: 8px; color: #94a3b8; font-size: 9px; letter-spacing: 0.3px;">
                                <span class="no-print" style="border: 1px dashed #cbd5e1; padding: 2px 6px; border-radius: 3px;">(Area TTD &amp; Cap Basah)</span>
                            </div>
                        @else
                            <div style="height: 100%; display: flex; align-items: flex-end; justify-content: center; color: #cbd5e1; font-size: 9.5px; letter-spacing: 0.5px;">
                                <span class="no-print" style="border: 1px dashed #cbd5e1; padding: 3px 8px; border-radius: 4px;">(Area TTD &amp; Stempel Basah Asli)</span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div style="font-weight: 700; text-decoration: underline; color: #0f172a; font-size: 12.5px; margin-top: 2px;">
                {{ $sigName }}
            </div>
            <div style="font-size: 10.5px; color: #64748b; margin-top: 1px;">
                {{ $sigTitle }}
            </div>
        </div>
    </div>

    <div style="margin-top: 25px; border-top: 1px dashed #cbd5e1; padding-top: 8px; font-size: 10px; color: #94a3b8; width: 100%; display: table;">
        <div style="display: table-cell; text-align: left; vertical-align: middle;">
            <strong style="color: #64748b;">INDOROSTER INDONESIA</strong> &bull; Pabrik Roster Beton &amp; Ventilasi Arsitektural<br>
            Website: <span style="color: #ea580c; font-weight: 600;">www.indoroster.com</span> &bull; Email: indoroster.official@gmail.com
        </div>
        <div style="display: table-cell; text-align: right; vertical-align: middle;">
            Kuitansi Sah Terverifikasi Sistem IndoRoster<br>
            Dicetak pada: {{ now()->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB
        </div>
    </div>
</div>

</body>
</html>
