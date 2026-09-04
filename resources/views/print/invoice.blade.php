<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12.5px; color: #334155; line-height: 1.45; margin: 0; padding: 15px 0; background: #ffffff; }
        .invoice-box { max-width: 800px; margin: auto; padding: 10px 20px; background: #ffffff; }
        .header { width: 100%; display: table; margin-bottom: 5px; }
        .header td { vertical-align: middle; }
        .header .logo { font-size: 28px; font-weight: bold; color: #1e293b; width: 50%; }
        .header .company-info { text-align: right; color: #64748b; font-size: 11.5px; width: 50%; line-height: 1.4; }
        .header-divider { border: none; border-top: 1.5px solid #e2e8f0; margin: 10px 0 18px 0; }
        .title { font-size: 20px; font-weight: 800; text-align: center; margin: 8px 0 18px 0; text-transform: uppercase; color: #0f172a; letter-spacing: 2.5px; }
        .details { width: 100%; display: table; margin-bottom: 18px; }
        .details-col { display: table-cell; width: 50%; vertical-align: top; }
        .details-label { font-weight: 700; font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 18px; table-layout: fixed; }
        table.items th, table.items td { padding: 8px 6px; border-bottom: 1px solid #e2e8f0; vertical-align: middle; }
        table.items th { background: transparent; font-weight: 700; color: #475569; font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.5px; border-top: 1px solid #e2e8f0; }
        table.items td { font-size: 12.5px; }
        table.items th.col-product, table.items td.col-product { width: 44%; text-align: left; }
        table.items th.col-price, table.items td.col-price { width: 20%; text-align: right; white-space: nowrap; }
        table.items th.col-qty, table.items td.col-qty { width: 14%; text-align: right; white-space: nowrap; }
        table.items th.col-total, table.items td.col-total { width: 22%; text-align: right; white-space: nowrap; }
        .summary { width: 100%; display: table; table-layout: fixed; margin-top: 5px; page-break-inside: avoid !important; break-inside: avoid !important; }
        .summary-col { display: table-cell; width: 46%; vertical-align: top; padding-right: 20px; }
        .summary-totals { display: table-cell; width: 54%; vertical-align: top; }
        table.totals { width: 100%; border-collapse: collapse; page-break-inside: avoid !important; break-inside: avoid !important; }
        table.totals td { padding: 5px 6px; text-align: right; font-size: 12.5px; white-space: nowrap; }
        table.totals td.label { text-align: left; color: #64748b; font-weight: 500; }
        table.totals tr.bold td { font-weight: 800; font-size: 15px; border-top: 2px solid #e2e8f0; color: #ea580c; padding-top: 8px; }
        table.totals tr.bold td.label { color: #ea580c; font-weight: 800; }
        .notes { font-size: 11px; color: #64748b; border: 1px dashed #e2e8f0; padding: 10px 12px; border-radius: 6px; background: transparent; line-height: 1.45; page-break-inside: avoid !important; break-inside: avoid !important; }
        .status { display: inline-block; padding: 2px 7px; border-radius: 4px; font-weight: 700; font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px; }
        .status-paid { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .status-unpaid { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        
        .avoid-break, .payment-history-box, .bank-instruction-box, .signature-section {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        tr {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        @media print {
            body { padding: 0; }
            .invoice-box { padding: 0; max-width: 100%; }
            .avoid-break, .payment-history-box, .bank-instruction-box, .signature-section {
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="header">
            <tr>
                <td class="logo">
                    <img src="{{ public_path('assets/logo_indoroster-text.png') }}" alt="Indoroster Logo" style="max-height: 100px;">
                </td>
                <td class="company-info">
                    <strong style="color: #ea580c; font-size: 15px;">indoroster.com</strong><br>
                    <span style="font-size: 11px;">Pabrik Roster & Bata Ekpose Terlengkap</span><br>
                    Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar,<br>
                    Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat - 41165<br>
                    WhatsApp: {{ \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847') }}
                </td>
            </tr>
        </table>
        
        <hr class="header-divider">

        @php
            $paymentStage = $paymentStage ?? null;
            $order = $invoice->order;
            $allPayments = $order ? $order->getValidPayments() : collect();
            $totalPaid = (float) $allPayments->sum('gross_amount');
            $grandTotal = (float) $invoice->grand_total;
            
            if ($paymentStage) {
                $payments = $allPayments->filter(fn($p) => $p->id <= $paymentStage->id);
                $stagePaid = (float) $payments->sum('gross_amount');
                $dp = $stagePaid;
                $remaining = max(0, $grandTotal - $stagePaid);
                $isStageLunas = ($remaining <= 0 || $paymentStage->is_settlement_receipt);
            } else {
                $payments = $allPayments;
                $isLunas = ($order && $order->payment_status === 'paid') || ($invoice->status === 'paid') || ($totalPaid >= $grandTotal && $grandTotal > 0 && $allPayments->isNotEmpty());
                $isStageLunas = $isLunas;
                $dp = $totalPaid > 0 ? $totalPaid : 0;
                $remaining = $isLunas ? 0 : max(0, $grandTotal - $totalPaid);
            }
        @endphp

        <div class="title">
            @if($paymentStage)
                INVOICE {{ strtoupper($paymentStage->installment_title) }}
            @elseif($isStageLunas)
                INVOICE FINAL / FAKTUR PENJUALAN RESMI (LUNAS)
            @elseif($allPayments->count() > 0 && $remaining > 0)
                SURAT TAGIHAN PELUNASAN (SISA TERMIN)
            @elseif($order && ($order->status === 'draft' || $order->status === 'pending_payment' || $order->payment_status === 'unpaid' || $invoice->status === 'unpaid'))
                @if($order->payment_scheme !== 'full' && $order->down_payment_amount > 0 && $order->down_payment_amount < $invoice->grand_total)
                    SURAT PENAWARAN & TAGIHAN PEMBAYARAN DP
                @else
                    SURAT PENAWARAN & PROFORMA TAGIHAN
                @endif
            @else
                SURAT PENAWARAN & PROFORMA TAGIHAN
            @endif
        </div>

        <div class="details">
            <div class="details-col">
                <div class="details-label">Ditagihkan Kepada:</div>
                <strong style="color: #0f172a; font-size: 14px;">{{ $invoice->order->shipping_name }}</strong><br>
                <span style="color: #475569; font-size: 12.5px; line-height: 1.5;">
                    {{ $invoice->order->shipping_address }}<br>
                    {{ $invoice->order->shipping_village ? 'Kel. '.$invoice->order->shipping_village.', ' : '' }}{{ $invoice->order->shipping_district ? 'Kec. '.$invoice->order->shipping_district.', ' : '' }}{{ $invoice->order->shipping_city }}, {{ $invoice->order->shipping_province }} {{ $invoice->order->shipping_postal_code }}<br>
                    HP / WA: {{ $invoice->order->shipping_phone }}
                </span>
            </div>
            <div class="details-col" style="padding-left: 20px;">
                <div class="details-label">Informasi Invoice:</div>
                <table style="width: 100%; font-size: 13px; line-height: 1.6;">
                    <tr><td width="40%" style="color: #64748b;">No. Invoice</td><td>: <strong style="color: #0f172a;">{{ $invoice->invoice_number }}{{ $paymentStage ? ' / ' . $paymentStage->receipt_number : '' }}</strong></td></tr>
                    <tr><td style="color: #64748b;">Tanggal</td><td>: {{ $paymentStage && $paymentStage->paid_at ? $paymentStage->paid_at->format('d M Y') : $invoice->invoice_date->format('d M Y') }}</td></tr>
                    <tr><td style="color: #64748b;">No. Pesanan</td><td>: {{ $invoice->order->order_number }}</td></tr>
                    @if($paymentStage)
                    <tr>
                        <td style="color: #64748b;">Metode Bayar</td>
                        <td>: {{ $paymentStage->payment_type_label }}</td>
                    </tr>
                    @elseif($invoice->order->latestPayment)
                    <tr>
                        <td style="color: #64748b;">Metode Bayar</td>
                        <td>: {{ $invoice->order->latestPayment->payment_type_label }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="color: #64748b;">Status</td>
                        <td>: 
                            @if($paymentStage)
                                @if($isStageLunas)
                                    <span class="status status-paid">LUNAS (100%)</span>
                                @else
                                    <span class="status" style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a;">{{ strtoupper($paymentStage->installment_title) }} TERVERIFIKASI</span>
                                @endif
                            @elseif($isStageLunas)
                                <span class="status status-paid">LUNAS (100%)</span>
                            @elseif($allPayments->count() > 0 && $remaining > 0)
                                <span class="status" style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;">MENUNGGU PELUNASAN SISA (Rp {{ number_format($remaining, 0, ',', '.') }})</span>
                            @elseif($order && ($order->status === 'draft' || $order->status === 'pending_payment' || $order->payment_status === 'unpaid'))
                                @if($order->payment_scheme !== 'full' && $order->down_payment_amount > 0 && $order->down_payment_amount < $invoice->grand_total)
                                    <span class="status" style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a;">MENUNGGU PEMBAYARAN DP (Rp {{ number_format($order->down_payment_amount, 0, ',', '.') }})</span>
                                @else
                                    <span class="status status-unpaid">MENUNGGU PEMBAYARAN TRANSFER</span>
                                @endif
                            @else
                                <span class="status status-unpaid">MENUNGGU PEMBAYARAN</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th class="col-product">Deskripsi Produk</th>
                    <th class="col-price">Harga Satuan</th>
                    <th class="col-qty">Jumlah</th>
                    <th class="col-total">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->order->items as $item)
                <tr>
                    <td class="col-product">
                        <strong>{{ $item->product_name }}</strong>
                        @if($item->variant)
                            <br><small style="color: #64748b;">Varian: {{ $item->variant->name }}</small>
                        @endif
                    </td>
                    <td class="col-price">Rp {{ number_format($item->product_price, 0, ',', '.') }}</td>
                    <td class="col-qty">{{ $item->quantity }} pcs</td>
                    <td class="col-total">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <div class="summary-col">
                <div class="notes">
                    <strong>Catatan & Keterangan Pengiriman:</strong><br>
                    {{ $invoice->order->notes ?: 'Material pesanan diproduksi langsung dari pabrik IndoRoster Purwakarta dengan standar cetak padat presisi.' }}
                    @if($invoice->order->shipping_latitude && $invoice->order->shipping_longitude)
                        <br><br><strong>Titik Koordinat Google Maps:</strong><br>
                        {{ $invoice->order->shipping_latitude }}, {{ $invoice->order->shipping_longitude }}
                    @endif
                </div>
            </div>
            <div class="summary-totals">
                <table class="totals">
                    <tr>
                        <td class="label">Subtotal</td>
                        <td>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Ongkos Kirim</td>
                        @if((float)$invoice->shipping_cost > 0)
                            <td>Rp {{ number_format($invoice->shipping_cost, 0, ',', '.') }}</td>
                        @elseif($invoice->order && $invoice->order->order_source === 'whatsapp' && $invoice->order->payment_status !== 'paid')
                            <td style="color: #b45309; font-style: italic; font-size: 11px;">[Menunggu Konfirmasi Pabrik]</td>
                        @else
                            <td>Rp 0</td>
                        @endif
                    </tr>
                    @if($invoice->discount_amount > 0)
                    <tr>
                        <td class="label">Diskon</td>
                        <td style="color: #dc2626;">- Rp {{ number_format($invoice->discount_amount, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    @if($invoice->tax_amount > 0)
                    <tr>
                        <td class="label">Pajak</td>
                        <td>Rp {{ number_format($invoice->tax_amount, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    <tr class="bold">
                        <td class="label">{{ ((float)$invoice->shipping_cost == 0 && $invoice->order && $invoice->order->order_source === 'whatsapp' && $invoice->order->payment_status !== 'paid') ? 'TOTAL SEMENTARA (BELUM TERMASUK ONGKIR)' : 'GRAND TOTAL' }}</td>
                        <td>Rp {{ number_format($invoice->grand_total, 0, ',', '.') }}</td>
                    </tr>
                    @php
                        $scheme = $invoice->order->payment_scheme ?? 'full';
                        $grandTotalVal = (float) $invoice->grand_total;
                    @endphp
                    @if($dp > 0 && $remaining > 0)
                    <tr>
                        <td class="label" style="color: #059669; font-weight: 600;">{{ $paymentStage ? 'Total Masuk (s/d ' . $paymentStage->installment_title . ')' : 'Total DP / Telah Dibayar' }}</td>
                        <td style="color: #059669; font-weight: 600;">Rp {{ number_format($dp, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="bold" style="background: #fef2f2;">
                        <td class="label" style="color: #dc2626;">SISA TAGIHAN PELUNASAN<br><small style="font-weight: normal; color: #64748b; font-size: 9px;">(Saat Barang Siap Dikirim)</small></td>
                        <td style="color: #dc2626; font-size: 13px; font-weight: 800;">Rp {{ number_format($remaining, 0, ',', '.') }}</td>
                    </tr>
                    @elseif($isStageLunas && $dp > 0)
                    <tr style="background: #f0fdf4;">
                        <td class="label" style="color: #15803d; font-weight: 700;">TOTAL PEMBAYARAN MASUK (100%)</td>
                        <td style="color: #15803d; font-weight: 800; font-size: 13px;">Rp {{ number_format($dp, 0, ',', '.') }}</td>
                    </tr>
                    <tr style="background: #f0fdf4;">
                        <td class="label" style="color: #15803d; font-weight: 700;">SISA TAGIHAN</td>
                        <td style="color: #15803d; font-weight: 800; font-size: 13px;">Rp 0 (LUNAS)</td>
                    </tr>
                    @elseif($dp == 0 && ($invoice->order->status === 'draft' || $invoice->order->status === 'pending_payment' || $invoice->order->payment_status === 'unpaid'))
                        @if($scheme === 'full')
                        <tr style="background: #fef2f2;">
                            <td class="label" style="color: #dc2626; font-weight: 700;">TOTAL TAGIHAN TRANSFER<br><small style="font-weight: normal; color: #64748b; font-size: 9px;">(Menunggu Pembayaran Transfer)</small></td>
                            <td style="color: #dc2626; font-weight: 800; font-size: 13px;">Rp {{ number_format($grandTotalVal, 0, ',', '.') }}</td>
                        </tr>
                        @elseif($scheme === 'dp_50_50')
                        <tr style="background: #fffbeb;">
                            <td class="label" style="color: #b45309; font-weight: 700;">Tagihan DP Awal (50%)<br><small style="font-weight: normal; color: #64748b; font-size: 9px;">(Untuk Konfirmasi Cetak Pabrik)</small></td>
                            <td style="color: #b45309; font-weight: 800; font-size: 12px;">Rp {{ number_format(round($grandTotalVal * 0.5), 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="label" style="color: #475569;">Sisa Pelunasan (50%)<br><small style="font-weight: normal; color: #64748b; font-size: 9px;">(Saat Barang Siap Dikirim)</small></td>
                            <td style="color: #475569; font-weight: 600;">Rp {{ number_format(round($grandTotalVal * 0.5), 0, ',', '.') }}</td>
                        </tr>
                        @elseif($scheme === 'termin_3x')
                        <tr style="background: #fffbeb;">
                            <td class="label" style="color: #b45309; font-weight: 700;">Tagihan DP #1 (30%)</td>
                            <td style="color: #b45309; font-weight: 800;">Rp {{ number_format(round($grandTotalVal * 0.3), 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="label" style="color: #475569;">Termin #2 (40%) / Pelunasan #3 (30%)<br><small style="font-weight: normal; color: #64748b; font-size: 9px;">(Pelunasan Saat Barang Siap Dikirim)</small></td>
                            <td style="color: #475569; font-weight: 600;">Rp {{ number_format(round($grandTotalVal * 0.7), 0, ',', '.') }}</td>
                        </tr>
                        @elseif($scheme === 'custom_dp' && ($invoice->order->down_payment_amount ?? 0) > 0)
                        @php $customDp = (float) $invoice->order->down_payment_amount; @endphp
                        <tr style="background: #fffbeb;">
                            <td class="label" style="color: #b45309; font-weight: 700;">Tagihan DP Awal Disepakati</td>
                            <td style="color: #b45309; font-weight: 800; font-size: 12px;">Rp {{ number_format($customDp, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="label" style="color: #475569;">Sisa Pelunasan<br><small style="font-weight: normal; color: #64748b; font-size: 9px;">(Saat Barang Siap Dikirim)</small></td>
                            <td style="color: #475569; font-weight: 600;">Rp {{ number_format(max(0, $grandTotalVal - $customDp), 0, ',', '.') }}</td>
                        </tr>
                        @endif
                    @endif
                </table>
            </div>
        </div>

        @if($payments->count() > 0)
        <!-- Riwayat Pembayaran Bertahap Terhubung -->
        <div class="payment-history-box avoid-break" style="margin-top: 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 12px; page-break-inside: avoid !important; break-inside: avoid !important;">
            <div style="font-size: 10.5px; font-weight: 800; color: #1e293b; text-transform: uppercase; margin-bottom: 4px; letter-spacing: 0.03em;">
                Rincian Pembayaran Masuk & Kuitansi Terhubung:
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 10.5px;">
                <thead>
                    <tr style="border-bottom: 1px solid #cbd5e1; color: #64748b;">
                        <th style="text-align: left; padding: 3px 0;">Tahap Pembayaran</th>
                        <th style="text-align: left; padding: 3px 0;">Tanggal</th>
                        <th style="text-align: left; padding: 3px 0;">No. Kuitansi</th>
                        <th style="text-align: left; padding: 3px 0;">Metode Bank</th>
                        <th style="text-align: right; padding: 3px 0;">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $idx => $p)
                    <tr style="border-bottom: 1px dashed #e2e8f0; color: #334155;">
                        <td style="padding: 3px 0; font-weight: 600;">{{ $p->installment_title }}</td>
                        <td style="padding: 3px 0;">{{ $p->paid_at ? $p->paid_at->format('d/m/Y') : $p->created_at->format('d/m/Y') }}</td>
                        <td style="padding: 3px 0; color: #ea580c; font-weight: 600;">{{ $p->receipt_number }}</td>
                        <td style="padding: 3px 0;">{{ $p->payment_type_label }}</td>
                        <td style="padding: 3px 0; text-align: right; font-weight: 700; color: #15803d;">Rp {{ number_format($p->gross_amount, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        @if($remaining > 0 || $dp == 0)
        <!-- Petunjuk Rekening Pembayaran Resmi Pabrik -->
        <div class="bank-instruction-box avoid-break" style="margin-top: 10px; background: #fffdfa; border: 1.5px solid #fed7aa; border-left: 5px solid #ea580c; border-radius: 6px; padding: 8px 12px; font-size: 10.5px; page-break-inside: avoid !important; break-inside: avoid !important;">
            <div style="color: #9a3412; font-weight: 800; text-transform: uppercase; font-size: 10.5px; margin-bottom: 3px; letter-spacing: 0.03em;">
                Petunjuk Pembayaran & Rekening Resmi:
            </div>
            <div style="color: #334155; line-height: 1.45;">
                @if($scheme === 'full')
                    Silakan melakukan transfer pembayaran penuh (100%) ke rekening resmi berikut:
                @else
                    Silakan melakukan transfer pembayaran / DP ke rekening resmi berikut:
                @endif
                <table style="margin-top: 3px; font-size: 10.5px; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 1.5px 0; color: #64748b; width: 100px;">Bank Tujuan</td>
                        <td style="padding: 1.5px 0; font-weight: 700; color: #0f172a;">: Bank BRI (Bank Rakyat Indonesia)</td>
                    </tr>
                    <tr>
                        <td style="padding: 1.5px 0; color: #64748b;">Nomor Rekening</td>
                        <td style="padding: 1.5px 0; font-weight: 800; font-family: monospace; font-size: 12px; color: #ea580c;">: 4356-01-009396-50-2 <span style="font-weight: normal; font-size: 9.5px; color: #64748b;">(435601009396502)</span></td>
                    </tr>
                    <tr>
                        <td style="padding: 1.5px 0; color: #64748b;">Atas Nama</td>
                        <td style="padding: 1.5px 0; font-weight: 700; color: #0f172a;">: ABDUL HAMID</td>
                    </tr>
                </table>
                <div style="margin-top: 4px; font-size: 9.5px; color: #9a3412; font-style: italic; line-height: 1.35;">
                    @if($scheme !== 'full')
                    * Sisa pelunasan diselesaikan saat material telah selesai diproduksi & siap dikirim dari pabrik.<br>
                    @endif
                    * Harap konfirmasikan bukti transfer ke WhatsApp resmi kami (0813-8970-9847) untuk penerbitan Kuitansi Resmi dan verifikasi pesanan.
                </div>
                @if((float)$invoice->shipping_cost == 0 && $invoice->order && $invoice->order->order_source === 'whatsapp' && $invoice->order->payment_status !== 'paid')
                <div style="margin-top: 6px; padding: 5px 8px; background: #fff7ed; border: 1px solid #fdba74; border-radius: 4px; color: #9a3412; font-size: 9.5px; font-weight: bold; line-height: 1.4;">
                    ⚠️ PERHATIAN: Biaya ongkos kirim armada truk saat ini sedang dihitung oleh tim logistik. Mohon jangan melakukan transfer sebelum total ongkir dikonfirmasi oleh Admin WhatsApp (0813-8970-9847).
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Signature & Stamp Section Resmi Pabrik -->
        <table class="signature-section avoid-break" style="width: 100%; margin-top: 15px; border-collapse: collapse; page-break-inside: avoid !important; break-inside: avoid !important;">
            <tr>
                <td style="width: 55%; vertical-align: top; font-size: 11px; color: #64748b; line-height: 1.5; padding-right: 20px;">
                    <strong style="color: #334155;">Syarat & Ketentuan Pengiriman:</strong>
                    <ul style="margin: 4px 0 0 16px; padding: 0;">
                        <li>Penurunan material dilakukan di titik bongkar yang dapat diakses armada truk.</li>
                        <li>Klaim garansi pecah wajib disertakan bukti foto saat serah terima barang.</li>
                        <li>Dokumen ini merupakan bukti transaksi sah yang diterbitkan otomatis oleh sistem.</li>
                    </ul>
                </td>
                <td style="width: 45%; vertical-align: top; text-align: center;">
                    <div style="font-size: 12px; color: #475569; margin-bottom: 3px;">
                        Purwakarta, {{ $invoice->created_at ? $invoice->created_at->translatedFormat('d F Y') : date('d F Y') }}
                    </div>
                    <div style="font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                        Pabrik IndoRoster
                    </div>

                    <div style="position: relative; height: 135px; width: 290px; margin: 0 auto;">
                        @php
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

                        @if($combinedPath)
                            <img src="{{ $combinedPath }}" alt="Stempel & TTD" style="max-height: 130px; max-width: 290px; object-fit: contain;">
                        @elseif($stampPath || $sigPath)
                            @if($stampPath)
                                <img src="{{ $stampPath }}" alt="Stempel" style="position: absolute; left: 0px; top: 10px; width: 120px; height: 120px; object-fit: contain; opacity: 0.95; transform: rotate(-6deg);">
                            @endif
                            @if($sigPath)
                                <img src="{{ $sigPath }}" alt="TTD" style="position: absolute; left: 40px; top: 5px; width: 260px; height: 125px; object-fit: contain; z-index: 2;">
                            @endif
                        @else
                            <!-- Stempel Digital Otomatis Resmi IndoRoster -->
                            <div style="position: absolute; left: 15px; top: 10px; width: 105px; height: 105px; border: 2.5px dashed #ea580c; border-radius: 50%; color: #ea580c; text-align: center; font-weight: 800; font-size: 10px; line-height: 1.15; padding-top: 18px; box-sizing: border-box; transform: rotate(-6deg); background: rgba(234,88,12,0.03);">
                                INDOROSTER<br>
                                <span style="display: block; border-top: 1px solid #ea580c; border-bottom: 1px solid #ea580c; color: #16a34a; font-size: 11px; margin: 3px 0;">★ LUNAS ★</span>
                                PLERED PWK
                            </div>
                            <div style="position: absolute; left: 75px; top: 35px; font-family: 'Brush Script MT', cursive, sans-serif; font-size: 32px; color: #0284c7; font-weight: bold; transform: rotate(-5deg); z-index: 2;">
                                IndoRoster
                            </div>
                        @endif
                    </div>

                    <div style="font-weight: 700; text-decoration: underline; color: #0f172a; font-size: 13px; margin-top: 4px;">
                        Abdul Hamid
                    </div>
                    <div style="font-size: 10.5px; color: #64748b; margin-top: 2px;">
                        Divisi Keuangan & Distribusi Pabrik
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
