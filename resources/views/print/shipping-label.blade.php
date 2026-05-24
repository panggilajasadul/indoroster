<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Label Pengiriman - {{ $label->label_number }}</title>
    <style>
        /* CSS Khusus Printer Thermal 150x100mm */
        @page {
            size: 100mm 150mm; /* Lebar x Tinggi label thermal standar panjang */
            margin: 0;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 4mm;
            width: 92mm;
            height: 142mm;
            box-sizing: border-box;
            background: #fff;
            color: #000;
        }
        .container {
            border: 2px solid #000;
            height: 100%;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            position: relative;
            z-index: 1;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 85px;
            font-weight: bold;
            color: rgba(0, 0, 0, 0.25);
            z-index: 99;
            pointer-events: none;
            white-space: nowrap;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding: 5px;
        }
        .logo { font-size: 18px; font-weight: bold; }
        .courier { font-size: 20px; font-weight: bold; text-align: right; }
        .tracking-box {
            padding: 5px;
            text-align: center;
            border-bottom: 2px solid #000;
        }
        .tracking-number { font-size: 22px; font-weight: bold; letter-spacing: 1px; }
        
        .addresses {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .recipient {
            padding: 5px;
            border-bottom: 2px solid #000;
            flex-grow: 1;
        }
        .sender {
            padding: 5px;
            border-bottom: 2px solid #000;
            font-size: 12px;
        }
        .label-title {
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 2px;
            text-transform: uppercase;
        }
        .name { font-size: 16px; font-weight: bold; }
        .phone { font-size: 14px; font-weight: bold; }
        .address-text { font-size: 12px; line-height: 1.3; margin-top: 3px; }
        .city { font-size: 14px; font-weight: bold; margin-top: 3px; }
        
        .details {
            display: flex;
            padding: 5px;
            font-size: 11px;
            border-bottom: 2px solid #000;
        }
        .details div { flex: 1; }
        
        .items {
            padding: 5px;
            font-size: 10px;
            flex-grow: 1;
            border-bottom: 2px solid #000;
        }
        .items-title { font-weight: bold; margin-bottom: 2px; }
        
        .footer {
            padding: 5px;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            background: #000;
            color: #fff;
            -webkit-print-color-adjust: exact;
            color-adjust: exact;
        }
        
        /* Hilangkan elemen tak perlu saat print */
        @media print {
            body { padding: 0; margin: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="text-align: center; margin-bottom: 10px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">🖨️ Cetak Resi (Thermal)</button>
        <p style="font-size: 12px;">Pastikan setting ukuran kertas di browser adalah 100mm x 150mm dan margin "None".</p>
    </div>

    <div class="container">
        <div class="header">
            <div class="logo">INDOROSTER</div>
            <div class="courier">ARMADA PABRIK</div>
        </div>

        @if($label->order->payment_status === 'paid')
        <div class="watermark">LUNAS</div>
        @endif

        <!-- Tracking Number -->
        <div class="tracking-box">
            <div class="label-title">NO. RESI:</div>
            <div class="tracking-number">{{ $label->tracking_number ?: '____________________' }}</div>
            <div style="font-size: 10px; margin-top: 3px;">Order: {{ $label->order->order_number }}</div>
        </div>

        <div class="addresses">
            <!-- Penerima -->
            <div class="recipient">
                <div class="label-title">PENERIMA:</div>
                <div class="name">{{ $label->recipient_name }}</div>
                <div class="phone">{{ $label->recipient_phone }}</div>
                <div class="address-text">{{ $label->recipient_address }}</div>
                <div class="city">{{ strtoupper($label->recipient_city) }} {{ $label->recipient_postal_code }}</div>
            </div>

            <!-- Pengirim & Kurir -->
            <div style="display: flex; border-bottom: 2px solid #000;">
                <div class="sender" style="flex: 1; border-bottom: none; border-right: 2px solid #000;">
                    <div class="label-title">PENGIRIM:</div>
                    <div class="name" style="font-size: 14px;">{{ $label->sender_name }}</div>
                    <div class="phone" style="font-size: 12px;">{{ $label->sender_phone }}</div>
                    <div class="address-text" style="font-size: 10px;">{{ $label->sender_address }}</div>
                </div>
                <div class="sender" style="flex: 1; border-bottom: none;">
                    <div class="label-title">INFORMASI KURIR:</div>
                    <div class="name" style="font-size: 14px;">{{ $label->courier ?: ($label->order->courier ?? '-') }}</div>
                    <div class="phone" style="font-size: 12px;">WA: {{ $label->order->courier_phone ?? '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Detail Paket -->
        <div class="details">
            <div>
                <strong>Total Item:</strong> {{ $label->total_items }} pcs<br>
                <strong>Berat:</strong> {{ $label->total_weight }} kg
            </div>
            <div style="text-align: right;">
                <strong>Jumlah Koli:</strong> {{ $label->total_packages }}<br>
                <strong>Tgl:</strong> {{ \Carbon\Carbon::parse($label->created_at)->format('d/m/Y') }}
            </div>
        </div>

        <!-- Deskripsi Barang -->
        <div class="items">
            <div class="items-title">ISI PAKET:</div>
            <div>{{ $label->package_description ?: 'Material Bangunan / Roster' }}</div>
            
            @if($label->special_instructions)
            <div style="margin-top: 5px; font-weight: bold; border: 1px dashed #000; padding: 2px;">
                Catatan: {{ $label->special_instructions }}
            </div>
            @endif
        </div>

        <!-- Footer / Peringatan -->
        <div class="footer">
            FRAGILE - JANGAN DIBANTING
        </div>
    </div>
</body>
</html>
