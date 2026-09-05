<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SPP Produksi - {{ $order->order_number }}{{ isset($batch) ? ' (' . $batch->batch_name . ')' : '' }}</title>
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
        .details-label { font-weight: bold; font-size: 11px; color: #718096; text-transform: uppercase; margin-bottom: 4px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; }
        table.items th, table.items td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        table.items th { background: #fff7ed; font-weight: bold; color: #9a3412; border-top: 1px solid #fed7aa; }
        table.items td.right, table.items th.right { text-align: right; }
        table.items td.center, table.items th.center { text-align: center; }
        
        .batch-box { background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 6px; padding: 12px 15px; margin-bottom: 20px; font-size: 12px; }
        .batch-box h4 { margin: 0 0 8px 0; color: #1e293b; font-size: 13px; text-transform: uppercase; }
        .batch-table { width: 100%; border-collapse: collapse; }
        .batch-table th, .batch-table td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; text-align: left; font-size: 11.5px; }
        .batch-table th { background: #f1f5f9; font-weight: bold; color: #475569; }
        .batch-table td.val { text-align: right; font-weight: bold; }
        
        .notes-box { background: #fefce8; border: 1px solid #fef08a; border-radius: 6px; padding: 10px 14px; margin-bottom: 20px; font-size: 12px; color: #854d0e; }
        .notes-box strong { color: #713f12; }

        .status-badge { display: inline-block; padding: 2px 7px; border-radius: 3px; font-size: 10px; font-weight: bold; }
        .status-warning { background: #fef3c7; color: #92400e; }
        .status-info { background: #e0f2fe; color: #075985; }
        .status-success { background: #dcfce7; color: #166534; }
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
                    <span style="font-size: 10px; color: #64748b;">Pabrik Roster Beton & Ventilasi Arsitektural</span><br>
                    Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar,<br>
                    Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat - 41165<br>
                    WhatsApp: {{ \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847') }}
                </td>
            </tr>
        </table>

        <div class="title">
            @if(isset($batch))
                SURAT PENGAJUAN PRODUKSI (SPP) — {{ strtoupper($batch->batch_name) }}
            @elseif($order->fulfillment_type === 'po_batch')
                SPP PRODUKSI INDUK & JADWAL BATCH PROYEK
            @else
                SURAT PENGAJUAN PRODUKSI (SPP)
            @endif
        </div>

        <div class="details">
            <div class="details-col">
                <div class="details-label">Data Surat & Referensi Pesanan:</div>
                <table style="font-size: 12px; width: 100%;">
                    <tr>
                        <td style="width: 130px; color: #64748b;">No. SPP</td>
                        <td>: <strong>{{ $sppNumber ?? $spkNumber }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Ref. No. Pesanan</td>
                        <td>: <strong>{{ $order->order_number }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Tanggal Pengajuan</td>
                        <td>: {{ now()->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Target Selesai / Muat</td>
                        <td>: <strong style="color: #ea580c;">{{ $targetDate }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Tipe Pemenuhan</td>
                        <td>: <span class="status-badge status-warning">{{ $order->fulfillment_label }}</span></td>
                    </tr>
                </table>
            </div>

            <div class="details-col" style="padding-left: 20px;">
                <div class="details-label">DETAIL PELAKSANA PRODUKSI:</div>
                <table style="font-size: 12px; width: 100%;">
                    <tr>
                        <td style="width: 120px; color: #64748b;">Unit / Pabrik</td>
                        <td>: <strong>{{ $factoryName }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Mandor / PIC</td>
                        <td>: <strong>{{ $factoryPicName }}</strong></td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Kontak Mandor</td>
                        <td>: {{ $factoryPicPhone ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Nama Pemesan</td>
                        <td>: {{ $order->shipping_name ?: ($order->user?->name ?: 'Pelanggan') }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Admin Pembuat</td>
                        <td>: {{ auth()->check() ? auth()->user()->name : 'Admin Operasional' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Tabel Rincian Produk --}}
        <div style="font-weight: bold; font-size: 12px; margin-bottom: 6px; color: #0f172a;">
            DAFTAR BARANG YANG HARUS DICETAK / DISIAPKAN:
        </div>
        <table class="items">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Model / Motif Roster</th>
                    <th style="width: 100px;">Ukuran</th>
                    <th style="width: 100px;">Warna / Bahan</th>
                    <th class="right" style="width: 100px;">Target (Qty)</th>
                    <th class="center" style="width: 70px;">Cek QC</th>
                </tr>
            </thead>
            <tbody>
                @php $totalPcs = 0; @endphp
                @if(isset($batch))
                    {{-- Tampilan khusus jika mencetak per batch --}}
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
                                <br><small style="color: #ea580c;">(Bagian dari {{ $batch->batch_name }} - Total Order: {{ number_format($item->quantity, 0, ',', '.') }} pcs)</small>
                            </td>
                            <td>{{ $dimensions }}</td>
                            <td><strong>{{ $displayColor }}</strong></td>
                            <td class="right"><strong>{{ number_format($itemQtyInBatch, 0, ',', '.') }} pcs</strong></td>
                            <td class="center">[ &nbsp; ]</td>
                        </tr>
                    @endforeach
                @else
                    {{-- Tampilan pesanan single atau rekap master batch --}}
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
                    <td colspan="4" class="right" style="color: #9a3412;">TOTAL TARGET PENGAJUAN PRODUKSI :</td>
                    <td class="right" style="color: #ea580c; font-size: 13px;">{{ number_format($totalPcs, 0, ',', '.') }} pcs</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        {{-- Tabel Milestone Jadwal Batch jika PO Batch Induk --}}
        @if($order->fulfillment_type === 'po_batch' && !isset($batch) && $order->batches->count() > 0)
        <div class="batch-box">
            <h4>JADWAL PENYELESAIAN PER BATCH:</h4>
            <table class="batch-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Batch</th>
                        <th style="width: 90px;">Jumlah (Qty)</th>
                        <th>Pabrik / Lokasi</th>
                        <th>Target Mulai</th>
                        <th>Target Selesai</th>
                        <th style="width: 80px; text-align: center;">Paraf Mandor</th>
                        <th style="width: 80px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->batches as $b)
                    <tr>
                        <td><strong>{{ $b->batch_name }}</strong></td>
                        <td><strong>{{ number_format($b->quantity, 0, ',', '.') }} pcs</strong></td>
                        <td>{{ $b->factory_name ?: ($order->factory_name ?: 'Pabrik Utama Plered') }}</td>
                        <td>{{ $b->production_start_date ? $b->production_start_date->format('d M Y') : '-' }}</td>
                        <td><strong style="color: #ea580c;">{{ $b->estimated_dispatch_date ? $b->estimated_dispatch_date->format('d M Y') : '-' }}</strong></td>
                        <td style="text-align: center;">[ &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ]</td>
                        <td>
                            <span class="status-badge {{ $b->status === 'ready_to_ship' || $b->status === 'shipped' ? 'status-success' : 'status-warning' }}">
                                {{ ucfirst(str_replace('_', ' ', $b->status)) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- Catatan Khusus --}}
        @if($factoryNotes)
        <div class="notes-box">
            <strong>Catatan Khusus Admin / Permintaan Lapangan:</strong><br>
            {{ $factoryNotes }}
        </div>
        @endif

        {{-- Tanda Tangan --}}
        <div style="margin-top: 35px; width: 100%; display: table; page-break-inside: avoid !important; break-inside: avoid !important;">
            <div style="display: table-cell; text-align: center; width: 33%;">
                Dibuat Oleh (Admin),<br><br><br><br>
                <strong>( {{ auth()->check() ? auth()->user()->name : 'Admin Operasional' }} )</strong><br>
                <small style="color: #64748b;">Tgl: {{ now()->format('d/m/Y') }}</small>
            </div>
            <div style="display: table-cell; text-align: center; width: 34%;">
                Mengetahui (Kepala Produksi),<br><br><br><br>
                <strong>( ................................... )</strong><br>
                <small style="color: #64748b;">Tgl: ..... / ..... / 2026</small>
            </div>
            <div style="display: table-cell; text-align: center; width: 33%;">
                Diterima Oleh (Mandor Pabrik),<br><br><br><br>
                <strong>( {{ $factoryPicName }} )</strong><br>
                <small style="color: #64748b;">{{ $factoryName }}</small>
            </div>
        </div>
    </div>
</body>
</html>
