<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pengantar Pengambilan Barang - {{ $order->order_number }}{{ isset($batch) ? ' (' . $batch->batch_name . ')' : '' }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #333; line-height: 1.4; }
        .invoice-box { max-width: 800px; margin: auto; padding: 25px; border: 1px solid #eee; position: relative; z-index: 1; }
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
        .header { width: 100%; display: table; margin-bottom: 15px; }
        .header td { vertical-align: top; }
        .header .logo { font-size: 26px; font-weight: bold; color: #2d3748; }
        .header .company-info { text-align: right; color: #718096; font-size: 11px; max-width: 340px; }
        .title { font-size: 18px; font-weight: bold; text-align: center; margin: 15px 0 20px 0; text-transform: uppercase; color: #ea580c; border-top: 2px solid #ea580c; border-bottom: 2px solid #ea580c; padding: 8px 0; }
        .details { width: 100%; display: table; margin-bottom: 20px; }
        .details-col { display: table-cell; width: 50%; vertical-align: top; }
        .details-label { font-weight: bold; font-size: 11px; color: #c2410c; text-transform: uppercase; margin-bottom: 4px; border-bottom: 1px solid #fed7aa; padding-bottom: 2px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; }
        table.items th, table.items td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        table.items th { background: #fff7ed; font-weight: bold; color: #9a3412; border-top: 1px solid #fed7aa; }
        table.items td.right, table.items th.right { text-align: right; }
        table.items td.center, table.items th.center { text-align: center; }

        .notes-box { background: #fefce8; border: 1px solid #fef08a; border-radius: 6px; padding: 10px 14px; margin-bottom: 20px; font-size: 11.5px; color: #854d0e; }
        .notes-box strong { color: #713f12; }

        .status-badge { display: inline-block; padding: 2px 7px; border-radius: 3px; font-size: 10px; font-weight: bold; }
        .status-info { background: #fff7ed; color: #c2410c; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="header">
            <tr>
                <td class="logo-cell">
                    @if(file_exists(public_path('assets/logo_indoroster-text.png')))
                        <img src="{{ public_path('assets/logo_indoroster-text.png') }}" style="max-height: 85px;">
                    @else
                        <strong style="color: #ea580c; font-size: 22px; font-weight: bold;">INDOROSTER</strong>
                    @endif
                </td>
                <td class="company-info">
                    <strong style="color: #ea580c; font-size: 14px;">INDOROSTER INDONESIA</strong><br>
                    <span style="font-size: 10px; color: #64748b;">Distribusi & Logistik Material Roster Arsitektural</span><br>
                    Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar,<br>
                    Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat - 41165<br>
                    WhatsApp: {{ \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847') }}
                </td>
            </tr>
        </table>

        <div class="title">
            SURAT PENGANTAR PENGAMBILAN BARANG (SPPB){{ isset($batch) ? ' — ' . strtoupper($batch->batch_name) : '' }}
        </div>

        <div class="details">
            <div class="details-col">
                <div class="details-label">LOKASI PENGAMBILAN (PABRIK / VENDOR):</div>
                <table style="font-size: 12px; width: 100%;">
                    <tr>
                        <td style="width: 120px; color: #64748b;">Nama Pabrik</td>
                        <td>: <strong>{{ $factoryName }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Pemilik / PIC</td>
                        <td>: <strong>{{ $factoryPicName }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">No. Telepon/WA</td>
                        <td>: {{ $factoryPicPhone ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b; vertical-align: top;">Alamat Pabrik</td>
                        <td>: {{ $factoryAddress ?: '-' }}</td>
                    </tr>
                </table>
            </div>

            <div class="details-col" style="padding-left: 20px;">
                <div class="details-label">DETAIL PENGAMBILAN & ARMADA:</div>
                <table style="font-size: 12px; width: 100%;">
                    <tr>
                        <td style="width: 120px; color: #64748b;">No. SPPB</td>
                        <td>: <strong>{{ $sppbNumber ?? $spabNumber }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Ref. Pesanan</td>
                        <td>: <strong>{{ $order->order_number }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Tanggal Jemput</td>
                        <td>: <strong>{{ now()->format('d M Y') }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Nama Supir</td>
                        <td>: <strong style="color: #ea580c;">{{ $pickupDriverName }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Plat Nomor Truk</td>
                        <td>: <strong style="color: #ea580c;">{{ $pickupDriverPlate }}</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Tabel Muatan Barang --}}
        <div style="font-weight: bold; font-size: 12px; margin-bottom: 6px; color: #0f172a;">
            RINCIAN BARANG YANG WAJIB DIAMBIL / DIMUAT:
        </div>
        <table class="items">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Nama Barang / Model Roster</th>
                    <th style="width: 110px;">Ukuran</th>
                    <th style="width: 100px;">Warna / Bahan</th>
                    <th class="right" style="width: 100px;">Jumlah Muat</th>
                    <th class="center" style="width: 70px;">Cek Fisik</th>
                </tr>
            </thead>
            <tbody>
                @php $totalPcs = 0; @endphp
                @if(isset($batch))
                    @php $totalPcs = $batch->quantity; @endphp
                    @foreach($order->items as $idx => $item)
                        @php
                            $itemQtyInBatch = $order->total_ordered_quantity > 0 
                                ? round(($item->quantity / $order->total_ordered_quantity) * $batch->quantity) 
                                : $batch->quantity;

                            // Pembersihan nama produk & penyesuaian varian/ukuran
                            $cleanProductName = preg_replace('/\s*\d+\s*x\s*\d+(\s*x\s*\d+)?\s*cm\s*$/i', '', $item->product_name);
                            $dimensions = $item->product?->dimensions ?: (preg_match('/(\d+\s*x\s*\d+(\s*x\s*\d+)?\s*cm)/i', $item->product_name, $m) ? $m[1] : '20 x 20 x 10 cm');
                            $rawVariant = $item->custom_variant_name ?: ($item->variant?->name ?: ($item->variant?->material?->name ?: ($item->product?->material ?: 'Abu-Abu Natural')));
                            
                            $lowerVariant = strtolower(trim($rawVariant));
                            if (str_contains($lowerVariant, 'dolomit')) {
                                $displayColor = 'Putih Dolomit';
                            } elseif (str_contains($lowerVariant, 'terracota') || str_contains($lowerVariant, 'terakota')) {
                                $displayColor = 'Merah Terakota';
                            } elseif (str_contains($lowerVariant, 'abu')) {
                                $displayColor = 'Abu-Abu Natural';
                            } else {
                                $displayColor = $rawVariant;
                            }
                        @endphp
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>
                                <strong>{{ $cleanProductName }}</strong>
                                <br><small style="color: #64748b;">Varian: <strong>{{ $displayColor }}</strong></small>
                                <br><small style="color: #ea580c;">({{ $batch->batch_name }})</small>
                            </td>
                            <td>{{ $dimensions }}</td>
                            <td><strong>{{ $displayColor }}</strong></td>
                            <td class="right"><strong>{{ number_format($itemQtyInBatch, 0, ',', '.') }} pcs</strong></td>
                            <td class="center">[ &nbsp; ]</td>
                        </tr>
                    @endforeach
                @else
                    @foreach($order->items as $idx => $item)
                        @php
                            $totalPcs += $item->quantity;

                            // Pembersihan nama produk & penyesuaian varian/ukuran
                            $cleanProductName = preg_replace('/\s*\d+\s*x\s*\d+(\s*x\s*\d+)?\s*cm\s*$/i', '', $item->product_name);
                            $dimensions = $item->product?->dimensions ?: (preg_match('/(\d+\s*x\s*\d+(\s*x\s*\d+)?\s*cm)/i', $item->product_name, $m) ? $m[1] : '20 x 20 x 10 cm');
                            $rawVariant = $item->custom_variant_name ?: ($item->variant?->name ?: ($item->variant?->material?->name ?: ($item->product?->material ?: 'Abu-Abu Natural')));
                            
                            $lowerVariant = strtolower(trim($rawVariant));
                            if (str_contains($lowerVariant, 'dolomit')) {
                                $displayColor = 'Putih Dolomit';
                            } elseif (str_contains($lowerVariant, 'terracota') || str_contains($lowerVariant, 'terakota')) {
                                $displayColor = 'Merah Terakota';
                            } elseif (str_contains($lowerVariant, 'abu')) {
                                $displayColor = 'Abu-Abu Natural';
                            } else {
                                $displayColor = $rawVariant;
                            }
                        @endphp
                        <tr>
                            <td>{{ $idx + 1 }}</td>
                            <td>
                                <strong>{{ $cleanProductName }}</strong>
                                <br><small style="color: #64748b;">Varian: <strong>{{ $displayColor }}</strong></small>
                            </td>
                            <td>{{ $dimensions }}</td>
                            <td><strong>{{ $displayColor }}</strong></td>
                            <td class="right"><strong>{{ number_format($item->quantity, 0, ',', '.') }} pcs</strong></td>
                            <td class="center">[ &nbsp; ]</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr style="background: #fff7ed; font-weight: bold;">
                    <td colspan="4" class="right" style="color: #9a3412;">TOTAL MUATAN PENGAMBILAN :</td>
                    <td class="right" style="color: #ea580c; font-size: 13px;">{{ number_format($totalPcs, 0, ',', '.') }} pcs</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        {{-- Petunjuk Penjemputan --}}
        <div class="notes-box">
            <strong>Petunjuk Penjemputan Supir & Checklist Serah Terima:</strong><br>
            1. Periksa fisik roster saat disusun ke atas bak truk. Pastikan barang tidak retak, siku presisi, dan tidak gompal.<br>
            2. Gunakan bantalan pengaman/kardus di antara tumpukan agar barang tidak saling berbenturan saat perjalanan.<br>
            3. Surat ini wajib ditandatangani oleh Mandor / Gudang Pabrik / Vendor dan dibawa oleh Supir sebagai bukti muat sah.
            @if($factoryNotes)
                <br>4. <strong>Catatan Tambahan:</strong> {{ $factoryNotes }}
            @endif
        </div>

        {{-- Tanda Tangan Tiga Pihak --}}
        <div style="margin-top: 35px; width: 100%; display: table; page-break-inside: avoid !important; break-inside: avoid !important;">
            <div style="display: table-cell; text-align: center; width: 33%;">
                Admin Logistik / Operasional,<br><br><br><br>
                <strong>( {{ auth()->check() ? auth()->user()->name : 'Admin Logistik' }} )</strong><br>
                <small style="color: #64748b;">Tgl: {{ now()->format('d/m/Y') }}</small>
            </div>
            <div style="display: table-cell; text-align: center; width: 34%;">
                Pihak Pabrik / Vendor,<br><br><br><br>
                <strong>( {{ $factoryPicName }} )</strong><br>
                <small style="color: #64748b;">{{ $factoryName }}</small>
            </div>
            <div style="display: table-cell; text-align: center; width: 33%;">
                Supir Pembawa Muatan,<br><br><br><br>
                <strong>( {{ $pickupDriverName }} )</strong><br>
                <small style="color: #64748b;">Plat: {{ $pickupDriverPlate }}</small>
            </div>
        </div>
    </div>
</body>
</html>
