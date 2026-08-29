@php
    use Illuminate\Support\Facades\URL;
    $order = isset($record) ? $record : $getRecord();
    $payments = $order->getValidPayments();
    $grandTotal = (float) $order->grand_total;
    $totalPaid = (float) $order->total_paid_amount;
    $remaining = max(0, $grandTotal - $totalPaid);
    $progressPct = $grandTotal > 0 ? min(100, round(($totalPaid / $grandTotal) * 100)) : 0;
    $invoice = $order->invoice;
@endphp

<div style="padding: 0 0 8px 0;" wire:poll.5s>
    {{-- ===== SUMMARY STATUS PEMBAYARAN ===== --}}
    <div style="background: linear-gradient(135deg, #0f172a, #1e293b); border-radius: 12px; padding: 20px; margin-bottom: 16px; border: 1px solid #334155;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <div>
                <div style="color: #10b981; font-size: 0.8rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">
                    Akumulasi Pembayaran Proyek
                </div>
                <div style="color: #ffffff; font-size: 1.3rem; font-weight: 800; margin-top: 2px;">
                    Rp {{ number_format($totalPaid, 0, ',', '.') }} <span style="font-size: 0.9rem; color: #94a3b8; font-weight: 500;">/ Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="color: {{ $progressPct >= 100 ? '#10b981' : '#f59e0b' }}; font-size: 2rem; font-weight: 800; line-height: 1;">
                    {{ $progressPct }}%
                </div>
                <div style="color: #94a3b8; font-size: 0.75rem; margin-top: 2px;">
                    {{ $progressPct >= 100 ? '🟢 LUNAS (100%)' : ($totalPaid > 0 ? '🟡 DP / Termin Sebagian' : '⚪ Menunggu DP Awal') }}
                </div>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div style="background: #334155; border-radius: 999px; height: 10px; overflow: hidden;">
            <div style="background: {{ $progressPct >= 100 ? 'linear-gradient(90deg, #10b981, #059669)' : 'linear-gradient(90deg, #f59e0b, #d97706)' }}; height: 100%; border-radius: 999px; width: {{ $progressPct }}%; transition: width 0.5s ease;"></div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 12px; font-size: 0.78rem; flex-wrap: wrap; gap: 6px;">
            <span style="color: {{ $remaining > 0 ? '#f87171' : '#34d399' }}; font-weight: 700; font-size: 0.85rem;">
                {{ $remaining > 0 ? '🔴 Sisa Tagihan: Rp ' . number_format($remaining, 0, ',', '.') : '✅ Semua Tagihan Telah Lunas (Sisa: Rp 0)' }}
            </span>
            <span style="color: #94a3b8; font-size: 0.75rem;">
                Skema: {{ match($order->payment_scheme) {
                    'dp_50_50' => 'DP 50% + Pelunasan 50%',
                    'termin_3x' => 'Termin 3x (30% + 40% + 30%)',
                    'custom_dp' => 'Kustom DP / Bertahap',
                    default => 'Lunas Langsung'
                } }}
            </span>
        </div>

        {{-- Action Buttons Langsung di Dalam Kartu Keuangan --}}
        @if($order->status !== 'draft')
        <div style="display: flex; justify-content: flex-end; align-items: center; margin-top: 12px; padding-top: 10px; border-top: 1px dashed #334155; gap: 8px; flex-wrap: wrap;">
            @if($remaining > 0)
            <button type="button" wire:click.prevent="mountAction('update_down_payment')"
                    style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: #d97706; border: 1.5px solid #f59e0b; border-radius: 8px; color: #ffffff; font-size: 0.78rem; font-weight: 700; cursor: pointer;">
                💳 + Catat Pembayaran Masuk
            </button>
            <button type="button" wire:click.prevent="mountAction('settle_payment')"
                    style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; background: #059669; border: 1.5px solid #10b981; border-radius: 8px; color: #ffffff; font-size: 0.78rem; font-weight: 700; cursor: pointer;">
                💰 Lunaskan Sisa (Rp {{ number_format($remaining, 0, ',', '.') }})
            </button>
            @else
            <span style="display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; background: #064e3b; border: 1px solid #059669; border-radius: 8px; color: #6ee7b7; font-size: 0.75rem; font-weight: 700;">
                ✓ Seluruh Tagihan Lunas 100%
            </span>
            @endif
        </div>
        @endif
    </div>

    {{-- ===== TIMELINE RIWAYAT TRANSAKSI / PEMBAYARAN ===== --}}
    <div style="display: flex; flex-direction: column; gap: 12px;">
        
        {{-- 1. TAHAP AWAL: SURAT PENAWARAN HARGA / TAGIHAN DP PROFORMA --}}
        @php
            $dpInitial = (float) ($order->down_payment_amount ?: ($order->payment_scheme === 'dp_50_50' ? round($grandTotal * 0.5) : ($order->payment_scheme === 'termin_3x' ? round($grandTotal * 0.3) : $grandTotal)));
            $isDpScheme = ($order->payment_scheme !== 'full' && $dpInitial > 0 && $dpInitial < $grandTotal);
        @endphp
        <div style="background: #1e293b; border-radius: 10px; border: 1px solid #334155; padding: 14px 16px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 10px;">
                <div style="display: flex; gap: 12px; align-items: center;">
                    <div style="width: 34px; height: 34px; border-radius: 8px; background: #334155; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                        📜
                    </div>
                    <div>
                        <div style="color: #ffffff; font-weight: 700; font-size: 0.9rem;">
                            {{ $isDpScheme ? 'Tahap Awal: Surat Penawaran & Tagihan DP Awal' : 'Tahap Awal: Surat Penawaran & Proforma Tagihan 100%' }}
                        </div>
                        <div style="color: #94a3b8; font-size: 0.75rem; margin-top: 2px;">
                            No. Dokumen: {{ $order->order_number }} • Diterbitkan: {{ $order->created_at?->format('d M Y H:i') }}
                            @if($isDpScheme)
                                • <span style="color: #fcd34d; font-weight: 600;">Nominal Tagihan DP: Rp {{ number_format($dpInitial, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                    @if($invoice)
                    <a href="{{ URL::signedRoute('print.invoice', ['invoice' => $invoice->id]) }}" target="_blank"
                       style="display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; background: #0f172a; border: 1px solid #475569; border-radius: 6px; color: #cbd5e1; font-size: 0.72rem; font-weight: 600; text-decoration: none;">
                        📄 {{ $isDpScheme ? 'Lihat Surat Penawaran / Tagihan DP' : 'Lihat Dokumen Penawaran' }}
                    </a>
                    @endif
                    <a href="{{ $order->getWaQuotationLink() }}" target="_blank"
                       style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px; background: #064e3b; border: 1px solid #10b981; border-radius: 6px; color: #ffffff; font-size: 0.72rem; font-weight: 700; text-decoration: none;">
                        💬 {{ $isDpScheme ? 'Kirim Tagihan DP via WA' : 'Kirim Penawaran WA' }}
                    </a>
                </div>
            </div>
        </div>

        {{-- 2. TRANSAKSI PEMBAYARAN MASUK --}}
        @if($payments->count() > 0)
            @foreach($payments as $index => $pay)
            @php
                $payNumber = $index + 1;
                $isLastPayment = ($payNumber === $payments->count() && $remaining == 0);
            @endphp
            <div style="background: #1e293b; border-radius: 10px; border: 1px solid {{ $isLastPayment ? '#059669' : '#d97706' }}; padding: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 10px;">
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: {{ $isLastPayment ? '#064e3b' : '#78350f' }}; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: #fff; font-weight: 800;">
                            {{ $isLastPayment ? '✓' : '#' . $payNumber }}
                        </div>
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="color: #ffffff; font-weight: 700; font-size: 0.95rem;">
                                    {{ $pay->installment_title }}
                                </span>
                                <span style="background: {{ $isLastPayment ? '#064e3b' : '#78350f' }}; color: {{ $isLastPayment ? '#6ee7b7' : '#fcd34d' }}; font-size: 0.68rem; font-weight: 700; padding: 2px 8px; border-radius: 999px; border: 1px solid {{ $isLastPayment ? '#059669' : '#d97706' }};">
                                    {{ $isLastPayment ? 'LUNAS (100%)' : 'DP / CICILAN TERVERIFIKASI' }}
                                </span>
                            </div>
                            <div style="color: #94a3b8; font-size: 0.75rem; margin-top: 3px;">
                                📅 {{ $pay->paid_at ? $pay->paid_at->format('d M Y H:i') : $pay->created_at->format('d M Y H:i') }} • 
                                💳 {{ $pay->payment_type_label }} • 
                                🔢 Ref: {{ $pay->receipt_number }}
                            </div>
                            @if($pay->notes)
                            <div style="color: #cbd5e1; font-size: 0.75rem; margin-top: 4px; font-style: italic;">
                                📝 Catatan: "{{ $pay->notes }}"
                            </div>
                            @endif
                        </div>
                    </div>

                    <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 6px;">
                        <div style="color: #10b981; font-size: 1.15rem; font-weight: 800;">
                            + Rp {{ number_format($pay->gross_amount, 0, ',', '.') }}
                        </div>
                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                            <a href="{{ route('print.receipt', $pay) }}" target="_blank"
                               style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px; background: #064e3b; border: 1px solid #059669; border-radius: 6px; color: #6ee7b7; font-size: 0.72rem; font-weight: 600; text-decoration: none;">
                                🖨️ Kuitansi #{{ $payNumber }}
                            </a>
                            @if($invoice)
                            <a href="{{ URL::signedRoute('print.invoice', ['invoice' => $invoice->id, 'payment_id' => $pay->id]) }}" target="_blank"
                               style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px; background: #1e3a5f; border: 1px solid #2563eb; border-radius: 6px; color: #93c5fd; font-size: 0.72rem; font-weight: 600; text-decoration: none;">
                                📄 Invoice Tahap #{{ $payNumber }}
                            </a>
                            @endif
                            <a href="{{ $isLastPayment ? $order->getWaSettlementPaidLink() : $order->getWaPaymentReceiptLink($pay) }}" target="_blank"
                               style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px; background: #064e3b; border: 1.5px solid #10b981; border-radius: 6px; color: #ffffff; font-size: 0.72rem; font-weight: 700; text-decoration: none;">
                                💬 Kirim Kuitansi WA
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- 3. TAHAP PENAGIHAN SISA PELUNASAN / TERMIN BERIKUTNYA --}}
            @if($remaining > 0)
            <div style="background: linear-gradient(135deg, #1e293b, #2d1810); border-radius: 10px; border: 1.5px solid #dc2626; padding: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 10px;">
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <div style="width: 36px; height: 36px; border-radius: 8px; background: #7f1d1d; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: #fecaca; font-weight: 800;">
                            ⏳
                        </div>
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span style="color: #ffffff; font-weight: 700; font-size: 0.95rem;">
                                    Tahap Penagihan: Surat Tagihan Sisa Pelunasan
                                </span>
                                <span style="background: #7f1d1d; color: #fecaca; font-size: 0.68rem; font-weight: 700; padding: 2px 8px; border-radius: 999px; border: 1px solid #ef4444;">
                                    BELUM DILUNASI
                                </span>
                            </div>
                            <div style="color: #fca5a5; font-size: 0.8rem; margin-top: 3px; font-weight: 600;">
                                Sisa Tagihan yang Harus Dibayar: Rp {{ number_format($remaining, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 6px;">
                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                            @if($invoice)
                            <a href="{{ URL::signedRoute('print.invoice', ['invoice' => $invoice->id]) }}" target="_blank"
                               style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px; background: #0f172a; border: 1px solid #dc2626; border-radius: 6px; color: #fca5a5; font-size: 0.72rem; font-weight: 600; text-decoration: none;">
                                📄 Dokumen Tagihan Sisa
                            </a>
                            @endif
                            <a href="{{ $order->getWaRemainingBillLink() }}" target="_blank"
                               style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px; background: #991b1b; border: 1.5px solid #ef4444; border-radius: 6px; color: #ffffff; font-size: 0.72rem; font-weight: 700; text-decoration: none;">
                                💬 Kirim Tagihan Sisa via WA
                            </a>
                            <button type="button" wire:click.prevent="mountAction('update_down_payment')"
                                    style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px; background: #d97706; border: 1px solid #f59e0b; border-radius: 6px; color: #ffffff; font-size: 0.72rem; font-weight: 700; cursor: pointer;">
                                💳 + Catat Cicilan
                            </button>
                            <button type="button" wire:click.prevent="mountAction('settle_payment')"
                                    style="display: inline-flex; align-items: center; gap: 4px; padding: 5px 10px; background: #059669; border: 1px solid #10b981; border-radius: 6px; color: #ffffff; font-size: 0.72rem; font-weight: 700; cursor: pointer;">
                                💰 Lunaskan Sisa
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @else
            <div style="background: #1e293b; border-radius: 10px; border: 1px dashed #475569; padding: 20px; text-align: center; color: #94a3b8; font-size: 0.82rem;">
                ⏳ Belum ada pembayaran masuk dari pembeli. Dokumen saat ini berstatus <strong>{{ $isDpScheme ? 'Surat Penawaran & Tagihan DP Awal' : 'Surat Penawaran & Proforma Tagihan' }}</strong>. Klik tombol di atas untuk mengirimkan tagihan resmi via WhatsApp atau catat pembayaran setelah pembeli transfer.
            </div>
        @endif
    </div>
</div>
