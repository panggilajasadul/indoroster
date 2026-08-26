<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan - {{ $order->order_number }}{{ isset($batch) ? ' (' . $batch->batch_name . ')' : '' }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.4; }
        .invoice-box { max-width: 800px; margin: auto; padding: 25px; border: 1px solid #eee; position: relative; z-index: 1; }
        .watermark {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-28deg);
            -webkit-transform: translate(-50%, -50%) rotate(-28deg);
            font-size: 72px;
            font-weight: 900;
            color: #dc2626;
            opacity: 0.16;
            border: 4px solid #dc2626;
            border-radius: 12px;
            padding: 6px 20px;
            letter-spacing: 6px;
            text-transform: uppercase;
            z-index: 99;
            pointer-events: none;
            white-space: nowrap;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .watermark {
                opacity: 0.18 !important;
                display: block !important;
            }
        }
        .header { width: 100%; display: table; margin-bottom: 15px; }
        .header td { vertical-align: top; }
        .header .logo { font-size: 26px; font-weight: bold; color: #2d3748; }
        .header .company-info { text-align: right; color: #718096; font-size: 11px; max-width: 320px; }
        .title { font-size: 18px; font-weight: bold; text-align: center; margin: 15px 0 20px 0; text-transform: uppercase; color: #c2410c; border-top: 2px solid #c2410c; border-bottom: 2px solid #c2410c; padding: 8px 0; }
        .details { width: 100%; display: table; margin-bottom: 20px; }
        .details-col { display: table-cell; width: 50%; vertical-align: top; }
        .details-label { font-weight: bold; font-size: 11px; color: #718096; text-transform: uppercase; margin-bottom: 4px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; }
        table.items th, table.items td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        table.items th { background: #f8fafc; font-weight: bold; color: #4a5568; border-top: 1px solid #e2e8f0; }
        table.items td.right, table.items th.right { text-align: right; }
        
        .batch-box { background: #fff7ed; border: 1px solid #fdba74; border-radius: 6px; padding: 12px 15px; margin-bottom: 20px; font-size: 12px; }
        .batch-box h4 { margin: 0 0 8px 0; color: #c2410c; font-size: 13px; text-transform: uppercase; }
        .batch-table { width: 100%; border-collapse: collapse; }
        .batch-table td { padding: 3px 0; }
        .batch-table td.val { text-align: right; font-weight: bold; }
        
        .notes { margin-top: 20px; font-size: 11px; color: #64748b; border-top: 1px solid #e2e8f0; padding-top: 10px; line-height: 1.5; }
        .status-badge { display: inline-block; padding: 2px 7px; border-radius: 3px; font-size: 10px; font-weight: bold; }
        .status-primary { background: #ebf8ff; color: #2b6cb0; }
        .status-success { background: #f0fdf4; color: #166534; }
    </style>
</head>
<body>
    <div class="invoice-box">
        @if($order->payment_status === 'paid')
        <div class="watermark">LUNAS</div>
        @endif
        
        <table class="header">
            <tr>
                <td class="logo-cell">
                    <img src="{{ public_path('assets/logo_indoroster-text.png') }}" style="max-height: 90px;">
                </td>
                <td class="company-info">
                    <strong style="color: #c2410c; font-size: 15px;">INDOROSTER INDONESIA</strong><br>
                    <span style="font-size: 10px;">Pabrik Roster Beton & Ventilasi Arsitektural Terlengkap</span><br>
                    Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar,<br>
                    Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat - 41165<br>
                    WhatsApp: {{ \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847') }}
                </td>
            </tr>
        </table>

        <div class="title">
            @if(isset($batch))
                SURAT JALAN PENGIRIMAN BERTAHAP ({{ strtoupper($batch->batch_name) }} DARI {{ $order->batch_count }} BATCH)
            @else
                SURAT JALAN PENGIRIMAN
            @endif
        </div>

        <div class="details">
            <div class="details-col">
                <div class="details-label">Tujuan Pengiriman:</div>
                <strong style="font-size: 15px; color: #1e293b;">{{ $order->shipping_name }}</strong><br>
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}<br>
                <strong>No. HP/WA: {{ $order->shipping_phone }}</strong>
            </div>
            <div class="details-col" style="padding-left: 20px;">
                <div class="details-label">Informasi Pengiriman:</div>
                <table style="width: 100%; font-size: 12px;">
                    <tr><td width="42%">No. Pesanan</td><td>: <strong>{{ $order->order_number }}</strong></td></tr>
                    <tr><td>Tanggal Pesanan</td><td>: {{ $order->created_at->format('d M Y') }}</td></tr>
                    <tr><td>Tgl Keberangkatan</td><td>: <strong>{{ now()->format('d M Y') }}</strong></td></tr>
                    <tr><td>Armada / Supir</td><td>: <strong>{{ isset($batch) && $batch->courier_name ? $batch->courier_name : ($order->courier ?: 'Armada Pabrik') }}</strong></td></tr>
                    <tr><td>No. Plat Truk</td><td>: <strong>{{ isset($batch) && $batch->tracking_number ? $batch->tracking_number : ($order->tracking_number ?: '-') }}</strong></td></tr>
                    @if(isset($batch) && $batch->courier_phone)
                    <tr><td>No. HP Supir</td><td>: {{ $batch->courier_phone }}</td></tr>
                    @endif
                </table>
            </div>
        </div>

        @php
            $destLat = $order->shipping_latitude;
            $destLng = $order->shipping_longitude;
            if ($destLat && $destLng) {
                $navUrl = "https://maps.google.com/?q={$destLat},{$destLng}";
            } else {
                $navUrl = "https://maps.google.com/?q=" . urlencode($order->shipping_address . ', ' . $order->shipping_city);
            }
        @endphp

        <!-- KOTAK PANDUAN TITIK LOKASI & BARCODE GOOGLE MAPS -->
        <table style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 6px; background: #f8fafc; margin-bottom: 20px; border-collapse: separate; border-spacing: 0;">
            <tr>
                <td style="padding: 10px 14px; vertical-align: middle;">
                    <div style="font-size: 11px; font-weight: bold; color: #c2410c; text-transform: uppercase; margin-bottom: 3px;">
                        TITIK KOORDINAT GPS LOKASI BONGKAR:
                    </div>
                    @if($destLat && $destLng)
                    <div style="font-family: monospace; font-size: 14px; font-weight: bold; color: #0f172a; margin-bottom: 4px;">
                        {{ number_format($destLat, 7) }}, {{ number_format($destLng, 7) }}
                    </div>
                    @else
                    <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">
                        (Mengikuti alamat tertulis di atas)
                    </div>
                    @endif
                    <div style="font-size: 10.5px; color: #475569; line-height: 1.4;">
                        <strong>Petunjuk Supir:</strong> Ketik angka koordinat di atas pada kolom pencarian <strong>Google Maps</strong>, atau <strong>scan Barcode QR di samping</strong> dengan kamera HP untuk langsung navigasi rute ke titik gerbang/rumah pembeli tanpa ketik manual.
                    </div>
                </td>
                <td style="width: 110px; text-align: center; vertical-align: middle; padding: 10px; border-left: 1px dashed #cbd5e1; background: #ffffff;">
                    {!! \App\Helpers\QrCodeHelper::imgTag($navUrl, 82) !!}
                    <div style="font-size: 7.5px; font-weight: bold; color: #1e293b; margin-top: 4px; text-transform: uppercase; line-height: 1.1;">
                        SCAN GOOGLE MAPS
                    </div>
                </td>
            </tr>
        </table>

        @if(isset($batch))
        <!-- REKAPITULASI PROGRES PENGIRIMAN BERTAHAP (PO BATCH) -->
        <div class="batch-box">
            <h4>📊 Rekapitulasi Progres Pengiriman Proyek ({{ $batch->batch_name }}):</h4>
            <table class="batch-table">
                <tr>
                    <td width="60%">• Total Keseluruhan Pesanan Pelanggan</td>
                    <td class="val">{{ number_format($order->total_ordered_quantity, 0, ',', '.') }} pcs</td>
                </tr>
                <tr>
                    <td>• Total Terkirim pada Batch-Batch Sebelumnya</td>
                    <td class="val" style="color: #475569;">{{ number_format($batch->previous_shipped_quantity, 0, ',', '.') }} pcs</td>
                </tr>
                <tr style="border-top: 1px dashed #fdba74; border-bottom: 1px dashed #fdba74;">
                    <td style="padding: 5px 0; color: #c2410c; font-weight: bold;">• MUATAN DIKIRIM (TRUK TAHAP INI)</td>
                    <td class="val" style="color: #c2410c; font-size: 14px;">{{ number_format($batch->quantity, 0, ',', '.') }} pcs</td>
                </tr>
                <tr>
                    <td style="padding-top: 4px;">• Total Akumulasi Terkirim s/d Tahap Ini</td>
                    <td class="val" style="padding-top: 4px; color: #166534;">{{ number_format($batch->cumulative_shipped_quantity, 0, ',', '.') }} pcs ({{ $order->total_ordered_quantity > 0 ? round(($batch->cumulative_shipped_quantity / $order->total_ordered_quantity) * 100, 1) : 100 }}%)</td>
                </tr>
                <tr>
                    <td>• SISA PESANAN YANG BELUM TERKIRIM</td>
                    <td class="val" style="color: #dc2626;">{{ number_format($batch->remaining_quantity_after_this_batch, 0, ',', '.') }} pcs</td>
                </tr>
            </table>
        </div>
        @endif

        <table class="items">
            <thead>
                <tr>
                    <th>Item Produk & Spesifikasi</th>
                    <th class="right">Total Order</th>
                    @if(isset($batch))
                    <th class="right" style="background: #ffedd5; color: #9a3412;">Muatan Truk Ini</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <strong style="color: #1e293b;">{{ $item->product_name }}</strong><br>
                        <small style="color: #64748b;">Varian Warna/Motif: {{ $item->product_variant_name ?: 'Standar' }}</small>
                    </td>
                    <td class="right">{{ number_format($item->quantity, 0, ',', '.') }} pcs</td>
                    @if(isset($batch))
                    <td class="right" style="font-weight: bold; color: #c2410c; background: #fff7ed;">
                        {{ number_format($batch->quantity, 0, ',', '.') }} pcs
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($order->notes || $order->admin_notes || $order->fulfillment_notes || $order->requested_batch_notes || (isset($batch) && $batch->notes))
        <div style="width: 100%; border: 1.5px solid #cbd5e1; border-radius: 6px; background: #fffdf5; padding: 10px 14px; margin-bottom: 18px; box-sizing: border-box;">
            <div style="font-size: 11px; font-weight: bold; color: #b45309; text-transform: uppercase; margin-bottom: 6px;">
                CATATAN PENGIRIMAN:
            </div>
            
            @if($order->notes)
            <div style="font-size: 11.5px; color: #1e293b; margin-bottom: 4px; line-height: 1.4;">
                <strong style="color: #0f172a;">• Catatan dari Pembeli:</strong> 
                <span style="font-style: italic; color: #334155;">"{{ $order->notes }}"</span>
            </div>
            @endif

            @php
                $adminNotesList = array_unique(array_filter([
                    $order->admin_notes,
                    $order->fulfillment_notes,
                    (isset($batch) && $batch->notes && $batch->notes !== $order->notes) ? $batch->notes : null
                ]));
            @endphp

            @if(count($adminNotesList) > 0)
            <div style="font-size: 11.5px; color: #1e293b; margin-bottom: 4px; line-height: 1.4;">
                <strong style="color: #0f172a;">• Catatan dari Admin:</strong> 
                <span style="color: #c2410c; font-weight: 600;">{{ implode(', ', $adminNotesList) }}</span>
            </div>
            @endif

            @if($order->requested_batch_notes)
            <div style="font-size: 11.5px; color: #1e293b; line-height: 1.4;">
                <strong style="color: #0f172a;">• Permintaan Jadwal Proyek:</strong> 
                <span style="color: #334155;">{{ $order->requested_batch_notes }}</span>
            </div>
            @endif
        </div>
        @endif

        <div class="notes">
            <strong>Catatan & Ketentuan Pengiriman:</strong><br>
            1. Harap periksa fisik barang saat serah terima. Komplain barang pecah/kurang wajib dicatat di lembar surat jalan ini sebelum supir meninggalkan lokasi.<br>
            2. Jadwal dan kuantitas pengiriman per batch merupakan estimasi operasional pabrik yang dapat disesuaikan mengikuti kondisi cuaca, kapasitas produksi, serta kapasitas muatan armada truk.<br>
            3. Surat jalan ini sah dan berlaku sebagai bukti fisik serah terima barang di lokasi proyek.
        </div>

        <div style="margin-top: 40px; width: 100%; display: table;">
            <div style="display: table-cell; text-align: center; width: 33%;">
                Penerima / Mandor Proyek,<br><br><br><br>
                ( ................................... )<br>
                <small style="color: #64748b;">Tgl: ..... / ..... / 2026</small>
            </div>
            <div style="display: table-cell; text-align: center; width: 33%;">
                Supir Armada Pabrik,<br><br><br><br>
                ( ................................... )<br>
                <small style="color: #64748b;">Plat: {{ isset($batch) && $batch->tracking_number ? $batch->tracking_number : ($order->tracking_number ?: '-') }}</small>
            </div>
            <div style="display: table-cell; text-align: center; width: 33%;">
                Hormat Kami,<br><br><br><br>
                ( ................................... )<br>
                <small style="color: #64748b;">Bagian Logistik</small>
            </div>
        </div>
    </div>
</body>
</html>
