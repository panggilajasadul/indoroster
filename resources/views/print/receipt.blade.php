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
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="header-left">
            <div class="company-name">INDOROSTER</div>
            <div class="company-tagline">Pabrik Roster & Bata Ekspose Terlengkap • indoroster.com</div>
            <div style="font-size: 10.5px; color: #64748b; margin-top: 3px;">
                Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat - 41165<br>
                WhatsApp: 0813-8970-9847
            </div>
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
            <div style="font-size: 11.5px; color: #475569; margin-bottom: 4px;">
                Purwakarta, {{ $payment->paid_at ? $payment->paid_at->translatedFormat('d F Y') : date('d F Y') }}
            </div>
            <div style="font-size: 12.5px; font-weight: 700; color: #0f172a; margin-bottom: 2px;">
                Pabrik IndoRoster
            </div>

            <div style="position: relative; height: 115px; width: 250px; margin: 0 auto;">
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
            </div>

            <div style="font-weight: 700; text-decoration: underline; color: #0f172a; font-size: 12.5px; margin-top: 2px;">
                Abdul Hamid
            </div>
            <div style="font-size: 10.5px; color: #64748b; margin-top: 1px;">
                Bagian Keuangan Pabrik IndoRoster
            </div>
        </div>
    </div>
</div>

</body>
</html>
