<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        .preview-box { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.5; padding: 20px; background: white; border-radius: 8px; overflow-y: auto; max-height: 70vh; position: relative; z-index: 1; }
        .watermark {
            position: absolute;
            top: 30%;
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
        }
        .header { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .header td { vertical-align: top; }
        .company-info { text-align: right; color: #718096; font-size: 12px; max-width: 300px; }
        .title { font-size: 22px; font-weight: bold; text-align: center; margin: 20px 0; text-transform: uppercase; color: #4a5568; border-bottom: 2px solid #c2410c; padding-bottom: 10px; }
        .details { width: 100%; display: flex; margin-bottom: 30px; }
        .details-col { flex: 1; }
        .details-label { font-weight: bold; font-size: 11px; color: #718096; text-transform: uppercase; margin-bottom: 5px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table.items th, table.items td { padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        table.items th { background: #f7fafc; font-weight: bold; color: #4a5568; }
        .right { text-align: right !important; }
        .summary { width: 100%; display: flex; }
        .summary-col { flex: 1.5; }
        .summary-totals { flex: 1; }
        table.totals { width: 100%; border-collapse: collapse; }
        table.totals td { padding: 8px 10px; text-align: right; }
        table.totals tr.bold td { font-weight: bold; font-size: 16px; border-top: 2px solid #c2410c; color: #c2410c; }
        .status-badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: bold; background: #ebf8ff; color: #2b6cb0; }
        .notes { margin-top: 40px; font-size: 12px; color: #718096; border-top: 1px solid #e2e8f0; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="preview-box">
        @if($order->fulfillment_type === 'po_batch')
            <div style="padding: 10px 0;">
                <table class="header">
                    <tr>
                        <td>
                            <img src="{{ asset('assets/logo_indoroster-text.png') }}" style="max-height: 100px;">
                        </td>
                        <td class="company-info">
                            <strong style="color: #c2410c; font-size: 16px;">indoroster.com</strong><br>
                            Purwakarta, Jawa Barat<br>
                            No. Pesanan: <strong>{{ $order->order_number }}</strong>
                        </td>
                    </tr>
                </table>

                <div class="title" style="margin-top: 10px;">Daftar Surat Jalan Bertahap (PO Batch)</div>
                
                <p style="color: #718096; font-size: 13.5px; margin-bottom: 20px; text-align: center; line-height: 1.6;">
                    Pesanan skala besar ini dikirim menggunakan sistem **Pengiriman Bertahap (PO Batch)**.<br>
                    Silakan pilih batch yang sudah dikirim/diberangkatkan di bawah ini untuk mencetak Surat Jalan:
                </p>

                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px; margin-top: 15px;">
                    <thead>
                        <tr style="background: #f7fafc;">
                            <th style="padding: 12px 10px; border-bottom: 2px solid #e2e8f0; font-weight: bold; color: #4a5568;">Batch</th>
                            <th style="padding: 12px 10px; border-bottom: 2px solid #e2e8f0; font-weight: bold; color: #4a5568;">Kuantitas</th>
                            <th style="padding: 12px 10px; border-bottom: 2px solid #e2e8f0; font-weight: bold; color: #4a5568;">Status Pengiriman</th>
                            <th style="padding: 12px 10px; border-bottom: 2px solid #e2e8f0; font-weight: bold; color: #4a5568;">Armada / Supir / Plat</th>
                            <th style="padding: 12px 10px; border-bottom: 2px solid #e2e8f0; font-weight: bold; color: #4a5568; text-align: right;">Aksi Cetak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->batches()->orderBy('batch_number')->get() as $b)
                        <tr>
                            <td style="padding: 14px 10px; border-bottom: 1px solid #e2e8f0;">
                                <strong style="font-size: 14px; color: #2d3748;">{{ $b->batch_name }}</strong>
                            </td>
                            <td style="padding: 14px 10px; border-bottom: 1px solid #e2e8f0; font-weight: 600; color: #1a202c;">
                                {{ number_format($b->quantity, 0, ',', '.') }} pcs
                            </td>
                            <td style="padding: 14px 10px; border-bottom: 1px solid #e2e8f0;">
                                <span style="display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 11px; font-weight: bold; background: {{ $b->status_hex_color }}18; color: {{ $b->status_hex_color }}; border: 1px solid {{ $b->status_hex_color }}33;">
                                    {{ $b->status_label }}
                                </span>
                            </td>
                            <td style="padding: 14px 10px; border-bottom: 1px solid #e2e8f0; color: #4a5568;">
                                @if($b->isShipped())
                                    <div style="font-weight: 600; color: #2d3748;">{{ $b->courier_name }}</div>
                                    <div style="font-size: 11px; color: #718096; font-family: monospace;">{{ $b->tracking_number }}</div>
                                @else
                                    <span style="color: #a0aec0; font-style: italic;">Belum diberangkatkan</span>
                                @endif
                            </td>
                            <td style="padding: 14px 10px; border-bottom: 1px solid #e2e8f0; text-align: right;">
                                @if($b->isShipped())
                                    <a href="{{ route('print.order', ['order' => $order->id, 'batch_id' => $b->id]) }}" target="_blank" 
                                       style="display: inline-flex; align-items: center; gap: 4px; padding: 6px 12px; background: #c2410c; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 12px; border: none; cursor: pointer; box-shadow: 0 2px 4px rgba(194,65,12,0.2);">
                                        🖨️ Cetak Surat Jalan
                                    </a>
                                @else
                                    <span style="font-size: 12px; color: #cbd5e0; font-weight: 500;">Menunggu Pengiriman</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            @if($order->payment_status === 'paid')
            <div class="watermark">LUNAS</div>
            @endif
            <table class="header">
                <tr>
                    <td>
                        <img src="{{ asset('assets/logo_indoroster-text.png') }}" style="max-height: 120px;">
                    </td>
                    <td class="company-info">
                        <strong style="color: #c2410c; font-size: 16px;">indoroster.com</strong><br>
                        <span style="font-size: 11px;">Pabrik Roster & bata ekpose dan ornamen dinding Terlengkap</span><br>
                        Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar,<br>
                        Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165<br>
                        WhatsApp: {{ \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847') }}
                    </td>
                </tr>
            </table>

            <div class="title">SURAT JALAN</div>

            <div class="details">
                <div class="details-col">
                    <div class="details-label">Data Penerima:</div>
                    <strong style="font-size: 16px;">{{ $order->shipping_name }}</strong><br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}<br>
                    <strong>HP: {{ $order->shipping_phone }}</strong>
                </div>
                <div class="details-col" style="padding-left: 20px;">
                    <div class="details-label">Informasi Pesanan:</div>
                    <table style="width: 100%; font-size: 13px;">
                        <tr><td width="40%">No. Pesanan</td><td>: <strong>{{ $order->order_number }}</strong></td></tr>
                        <tr><td>Tanggal</td><td>: {{ $order->created_at->format('d M Y H:i') }}</td></tr>
                        @if($order->courier)
                        <tr><td>Kurir</td><td>: {{ $order->courier }}</td></tr>
                        @endif
                        @if($order->courier_phone)
                        <tr><td>No. WA Kurir</td><td>: {{ $order->courier_phone }}</td></tr>
                        @endif
                        @if($order->tracking_number)
                        <tr><td>No. Resi/Plat</td><td>: {{ $order->tracking_number }}</td></tr>
                        @endif
                        <tr><td>Status</td><td>: <span class="status-badge">
                            {{ match($order->status) {
                                'pending' => 'MENUNGGU',
                                'processing' => 'DIPROSES',
                                'shipped' => 'DIKIRIM',
                                'delivered' => 'DITERIMA',
                                'completed' => 'SELESAI',
                                'cancelled' => 'DIBATALKAN',
                                default => strtoupper($order->status)
                            } }}
                        </span></td></tr>
                        <tr><td>Pembayaran</td><td>: 
                            {{ match($order->payment_status) {
                                'unpaid' => 'BELUM BAYAR',
                                'paid' => 'LUNAS',
                                'expired' => 'KADALUWARSA',
                                'failed' => 'GAGAL',
                                default => strtoupper($order->payment_status)
                            } }}
                        </td></tr>
                    </table>
                </div>
            </div>

            <table class="items">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="right">Harga Satuan</th>
                        <th class="right">Qty</th>
                        <th class="right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product_name }}</strong><br>
                            <small style="color: #718096;">Varian: {{ $item->product_variant_name ?: '-' }}</small>
                        </td>
                        <td class="right">Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                        <td class="right">{{ $item->quantity }}</td>
                        <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="summary">
                <div class="summary-col">
                    <div class="notes">
                        <strong>Catatan dari Pembeli:</strong><br>
                        {{ $order->notes ?: 'Tidak ada catatan.' }}
                        @php
                            $adminNotesList = array_unique(array_filter([
                                $order->admin_notes,
                                $order->fulfillment_notes,
                                (isset($batch) && $batch->notes && $batch->notes !== $order->notes) ? $batch->notes : null
                            ]));
                        @endphp
                        @if(count($adminNotesList) > 0)
                            <br><br><strong>Catatan dari Admin:</strong><br>
                            <span style="color: #c2410c; font-weight: 600;">{{ implode(', ', $adminNotesList) }}</span>
                        @endif
                        @if($order->requested_batch_notes)
                            <br><br><strong>Permintaan Jadwal Proyek:</strong><br>
                            <span style="color: #334155;">{{ $order->requested_batch_notes }}</span>
                        @endif
                    </div>
                </div>
                <div class="summary-totals">
                    <table class="totals">
                        <tr>
                            <td>Subtotal</td>
                            <td>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Ongkos Kirim</td>
                            <td>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                        </tr>
                        @if($order->discount_amount > 0)
                        <tr>
                            <td>Diskon</td>
                            <td style="color: red;">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</td>
                        </tr>
                        @endif
                        <tr class="bold">
                            <td>TOTAL AKHIR</td>
                            <td>Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
