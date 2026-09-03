<x-mail::message>
@php
    $validPayments = $order->getValidPayments();
    $totalPaid = (float) $validPayments->sum('gross_amount');
    $isPaid = ($order->payment_status === 'paid') || ($totalPaid >= (float) $order->grand_total && (float) $order->grand_total > 0 && $validPayments->isNotEmpty());
    $hasDp = ($totalPaid > 0 && !$isPaid) || ($order->down_payment_amount > 0 && $totalPaid > 0);
    $remaining = max(0, (float) $order->grand_total - $totalPaid);

    $stampText = 'DIPROSES';
    $stampColor = '#ea580c';

    if ($statusType === 'created') {
        $stampText = 'PENAWARAN & TAGIHAN';
        $stampColor = '#d97706';
    } elseif ($statusType === 'payment_received' || $statusType === 'paid') {
        if ($isPaid) {
            $stampText = 'LUNAS (100%)';
            $stampColor = '#16a34a';
        } else {
            $stampText = 'DP TERVERIFIKASI';
            $stampColor = '#0284c7';
        }
    } elseif ($statusType === 'processing') {
        $stampText = $order->fulfillment_type === 'po_batch' ? 'JADWAL BATCH' : ($order->fulfillment_type === 'po_single' ? 'PRODUKSI PO' : 'PENYIAPAN GUDANG');
        $stampColor = '#d97706';
    } elseif ($statusType === 'shipped' || $statusType === 'batch_shipped') {
        $stampText = 'SEDANG DIKIRIM';
        $stampColor = '#7c3aed';
    } elseif ($statusType === 'batch_delivered') {
        $stampText = 'BATCH DITERIMA';
        $stampColor = '#059669';
    } elseif ($statusType === 'completed' || $statusType === 'delivered') {
        $stampText = 'SELESAI (100%)';
        $stampColor = '#16a34a';
    }
@endphp

<div style="text-align: left; display: table; width: 100%; border-bottom: 2px solid #f3f4f6; padding-bottom: 20px; margin-bottom: 20px;">
    <div style="display: table-cell; vertical-align: middle; width: 50%;">
        <img src="{{ $message->embed(public_path('assets/logo_indoroster-text.png')) }}" alt="Indoroster Logo" style="max-height: 80px; width: auto;">
    </div>
    <div style="display: table-cell; vertical-align: middle; width: 50%; text-align: right;">
        <div style="margin-bottom: 8px; font-size: 14px; font-weight: bold; color: #64748b;">Status Pesanan</div>
        <div style="text-align: right;">
            <div style="display: inline-block; color: {{ $stampColor }}; font-weight: 900; font-size: 14px; border: 2.5px solid {{ $stampColor }}; padding: 4px 14px; border-radius: 6px; letter-spacing: 1.5px; transform: rotate(-3deg); margin-top: 5px;">
                {{ $stampText }}
            </div>
        </div>
    </div>
</div>

# Halo, {{ $order->shipping_name }}

{{-- TAHAP 1: PESANAN DIBUAT (SURAT PENAWARAN & PROFORMA TAGIHAN) --}}
@if($statusType === 'created')
Terima kasih atas pesanan Anda di Pabrik Roster Beton **IndoRoster Indonesia**. Pesanan Anda dengan nomor **{{ $order->order_number }}** telah berhasil dicatat di sistem pabrik kami dan menunggu pembayaran / verifikasi DP.

<x-mail::panel>
### 📋 Rincian Informasi Pesanan & Tagihan
- **Nomor Pesanan:** `{{ $order->order_number }}`
- **Tanggal Pemesanan:** {{ $order->created_at->format('d M Y, H:i') }} WIB
- **Total Kuantitas:** **{{ number_format($order->total_ordered_quantity, 0, ',', '.') }} pcs**
- **Total Nilai Tagihan:** **Rp{{ number_format($order->grand_total, 0, ',', '.') }}**
@if($order->down_payment_amount > 0 && $order->down_payment_amount < $order->grand_total)
- **Tagihan DP Minimal (di Awal):** **Rp{{ number_format($order->down_payment_amount, 0, ',', '.') }}**
@endif

---

### 🏦 Panduan Pembayaran Transfer Bank:
Silakan lakukan pembayaran transfer ke rekening resmi IndoRoster berikut:

- **Nama Bank:** **Bank Rakyat Indonesia (BRI)**
- **Nomor Rekening:** `0075-01-001962-30-8`
- **Atas Nama:** **PT INDOROSTER CIPTA KREASI**

<div style="font-size: 12px; color: #6b7280; margin-top: 8px;">
<em>Setelah melakukan transfer, mohon kirimkan bukti transfer melalui WhatsApp Admin kami atau balas email ini agar pesanan Anda dapat segera masuk antrean produksi / penyiapan armada.</em>
</div>
</x-mail::panel>

@php
    $invoiceRecord = $order->invoice;
@endphp
@if($invoiceRecord)
<div style="text-align: center; margin: 15px 0;">
    <a href="{{ URL::signedRoute('print.invoice', ['invoice' => $invoiceRecord->id]) }}" target="_blank" style="background: #0f172a; color: #ffffff; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-weight: bold; font-size: 13px; display: inline-block;">
        📄 Unduh Surat Penawaran & Proforma Tagihan (PDF)
    </a>
</div>
@endif

{{-- TAHAP 2: PEMBAYARAN DITERIMA (DP / PELUNASAN) --}}
@elseif($statusType === 'payment_received' || $statusType === 'paid')
Kabar baik! Pembayaran untuk pesanan **{{ $order->order_number }}** telah berhasil kami verifikasi dan dicatat di sistem administrasi keuangan pabrik IndoRoster.

<x-mail::panel>
### 🧾 Rincian Pembayaran Masuk
@if(isset($payment) && $payment)
- **Nominal Dana Masuk:** **Rp{{ number_format($payment->gross_amount, 0, ',', '.') }}**
- **Keterangan:** {{ $payment->installment_title ?: ($isPaid ? 'Pelunasan 100%' : 'Pembayaran DP') }}
- **Waktu Verifikasi:** {{ $payment->paid_at ? $payment->paid_at->format('d M Y, H:i') : now()->format('d M Y, H:i') }} WIB
@endif
- **Total Nilai Pesanan:** Rp{{ number_format($order->grand_total, 0, ',', '.') }}
- **Total Pembayaran Terverifikasi:** **Rp{{ number_format($totalPaid, 0, ',', '.') }}**
- **Sisa Tagihan:** **{{ $remaining > 0 ? 'Rp' . number_format($remaining, 0, ',', '.') : 'Rp 0 (LUNAS 100%)' }}**

---

### 📊 Status Kelanjutan Pesanan:
@if($isPaid)
<strong style="color: #16a34a;">Pesanan ini telah LUNAS 100%.</strong> Material roster Anda sedang dipersiapkan dan siap diberangkatkan sesuai jadwal pengiriman.
@else
<strong style="color: #0284c7;">Pembayaran DP telah Terverifikasi.</strong> Material pesanan Anda resmi masuk antrean cetak / produksi pabrik. Pelunasan sisa tagihan dapat diselesaikan saat barang siap dikirim.
@endif
</x-mail::panel>

@if(isset($payment) && $payment)
<div style="text-align: center; margin: 15px 0;">
    <a href="{{ route('print.receipt', ['payment' => $payment->id]) }}" target="_blank" style="background: #059669; color: #ffffff; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-weight: bold; font-size: 13px; display: inline-block;">
        🧾 Cetak Kuitansi Sah Pembayaran Ini (PDF)
    </a>
</div>
@endif

{{-- TAHAP 3: PROSES PRODUKSI / PENYIAPAN --}}
@elseif($statusType === 'processing')
    @if($order->fulfillment_type === 'ready_stock')
Pesanan Anda dengan nomor **{{ $order->order_number }}** saat ini sedang kami siapkan dan kami lakukan pengecekan kualitas (QC) di gudang pabrik kami di Plered, Purwakarta.

<x-mail::panel>
### 📦 Jadwal Penyiapan & Pengiriman
- **Status Barang:** **Ready Stock (Gudang Pabrik)**
- **Estimasi Siap Kirim:** {{ $order->ready_shipping_date ? $order->ready_shipping_date->format('d M Y') : '1–2 hari kerja' }}
- **Estimasi Tiba di Lokasi:** {{ $order->estimated_delivery_date ? $order->estimated_delivery_date->format('d M Y') : '2–4 hari kerja' }}

<div style="font-size: 12px; color: #6b7280; margin-top: 8px;">
<em>Kami akan mengirimkan notifikasi email kembali saat armada truk diberangkatkan, disertai info supir & nomor plat kendaraan.</em>
</div>
</x-mail::panel>

    @elseif($order->fulfillment_type === 'po_single')
Pesanan Anda dengan nomor **{{ $order->order_number }}** telah resmi dijadwalkan dan masuk ke **antrean produksi cetak tumbuk padat plat baja (Pre-Order)** di pabrik Plered, Purwakarta.

<x-mail::panel>
### 🔨 Jadwal Produksi & Pengiriman (PO Single)
- **Tanggal Mulai Produksi:** {{ $order->production_start_date ? $order->production_start_date->format('d M Y') : now()->format('d M Y') }}
- **Masa Pengeringan Alami:** Minimal 7 hari agar kuat tekan beton matang maksimal
- **Estimasi Siap Kirim:** {{ $order->ready_shipping_date ? $order->ready_shipping_date->format('d M Y') : '7 hari kerja' }}
- **Estimasi Tiba di Lokasi Anda:** {{ $order->estimated_delivery_date ? $order->estimated_delivery_date->format('d M Y') : '9 hari kerja' }}

@if($order->fulfillment_notes)
**Catatan Operasional Pabrik:** {{ $order->fulfillment_notes }}
@endif

<div style="font-size: 12px; color: #6b7280; margin-top: 8px;">
<em>*Roster IndoRoster dicetak secara padat menggunakan abu batu murni pilihan dan memerlukan waktu pengeringan alami min. 7 hari sebelum dimuat ke armada. Kami akan menghubungi Anda jika ada penyesuaian jadwal.</em>
</div>
</x-mail::panel>

    @elseif($order->fulfillment_type === 'po_batch')
Pesanan skala proyek Anda dengan nomor **{{ $order->order_number }}** (Total: **{{ number_format($order->total_ordered_quantity, 0, ',', '.') }} pcs**) telah kami terima dan dijadwalkan untuk **Pengiriman Bertahap ({{ $order->batch_count }} Batch / Ritase Truk)**.

Setiap batch ritase truk akan diproduksi dan dikirimkan secara bertahap sesuai jadwal di bawah. Anda akan menerima email notifikasi otomatis setiap kali armada truk diberangkatkan.

<x-mail::panel>
### 📅 Rencana Jadwal Produksi & Pengiriman ({{ $order->batch_count }} Batch Rit Truk)

@foreach($order->batches as $b)
**{{ $b->batch_name }}** — {{ number_format($b->quantity, 0, ',', '.') }} pcs
@if($b->production_start_date)
&nbsp;&nbsp;&nbsp;🔨 Mulai Produksi: {{ $b->production_start_date->format('d M Y') }}
@endif
&nbsp;&nbsp;&nbsp;🚚 Est. Berangkat: {{ $b->estimated_dispatch_date ? $b->estimated_dispatch_date->format('d M Y') : '-' }}
&nbsp;&nbsp;&nbsp;📍 Est. Tiba: {{ $b->estimated_delivery_date ? $b->estimated_delivery_date->format('d M Y') : '-' }}

@endforeach
@if($order->fulfillment_notes)

**Catatan Operasional Pabrik:** {{ $order->fulfillment_notes }}
@endif

<div style="font-size: 12px; color: #6b7280; margin-top: 10px;">
<em>*Jadwal di atas disesuaikan dengan kapasitas muat armada truk dan cuaca. Anda akan menerima notifikasi setiap kali truk ritase berangkat.</em>
</div>
</x-mail::panel>
    @else
Pesanan Anda dengan nomor **{{ $order->order_number }}** sedang kami siapkan / produksi di pabrik. Kami akan mengabari Anda kembali saat armada siap berangkat.
    @endif

{{-- TAHAP 4: PENGIRIMAN BATCH BERANGKAT --}}
@elseif($statusType === 'batch_shipped' && isset($batch))
Kabar baik! Armada truk pabrik kami telah diberangkatkan untuk **{{ $batch->batch_name }}** dari pesanan proyek nomor **{{ $order->order_number }}**.

<x-mail::panel>
### 🚚 Rincian Muatan Truk Ini ({{ $batch->batch_name }}):
- **Jumlah Muatan Truk Ini:** **{{ number_format($batch->quantity, 0, ',', '.') }} pcs**
- **Supir / Armada:** {{ $batch->courier_name ?? 'Armada Pabrik IndoRoster' }}
- **No. Plat Truk:** `{{ $batch->tracking_number ?? '-' }}`
@if($batch->courier_phone)
- **No. HP/WA Supir:** {{ $batch->courier_phone }} *(dapat dihubungi untuk koordinasi bongkar muat)*
@endif

---

### 📊 Rekapitulasi Progres Pengiriman Proyek:
| Keterangan | Jumlah |
|:-----------|-------:|
| Total Keseluruhan Pesanan | **{{ number_format($order->total_ordered_quantity, 0, ',', '.') }} pcs** |
| Muatan Truk Ini ({{ $batch->batch_name }}) | **{{ number_format($batch->quantity, 0, ',', '.') }} pcs** |
| Total Terkirim s/d Tahap Ini | **{{ number_format($batch->cumulative_shipped_quantity, 0, ',', '.') }} pcs** ({{ $order->total_ordered_quantity > 0 ? round(($batch->cumulative_shipped_quantity / $order->total_ordered_quantity) * 100, 1) : 100 }}%) |
| **🔴 Sisa Belum Terkirim** | **{{ number_format($batch->remaining_quantity_after_this_batch, 0, ',', '.') }} pcs** |

<div style="font-size: 12px; color: #6b7280; margin-top: 8px;">
<em>Mohon pastikan area proyek siap untuk penerimaan dan proses bongkar muat roster.</em>
</div>
</x-mail::panel>

{{-- TAHAP 5: BATCH DITERIMA DI PROYEK --}}
@elseif($statusType === 'batch_delivered' && isset($batch))
Pengiriman **{{ $batch->batch_name }}** dari pesanan proyek nomor **{{ $order->order_number }}** telah sukses diturunkan dan diterima di lokasi proyek Anda.

<x-mail::panel>
### ✅ Rincian Penerimaan Truk Ini ({{ $batch->batch_name }}):
- **Jumlah Roster Diterima:** **{{ number_format($batch->quantity, 0, ',', '.') }} pcs**
- **Waktu Penerimaan:** {{ $batch->actual_delivered_date ? $batch->actual_delivered_date->format('d M Y, H:i') : now()->format('d M Y, H:i') }} WIB
- **Supir / Armada:** {{ $batch->courier_name ?? 'Armada Pabrik IndoRoster' }}
- **No. Plat Truk:** `{{ $batch->tracking_number ?? '-' }}`

@if($batch->delivery_photo_path && file_exists(storage_path('app/public/' . $batch->delivery_photo_path)))
---

### 📸 Bukti Pembongkaran Roster di Lokasi  
<img src="{{ $message->embed(storage_path('app/public/' . $batch->delivery_photo_path)) }}" alt="Bukti Pengiriman" style="max-width: 100%; border-radius: 8px;">
@endif
</x-mail::panel>

<x-mail::panel>
### 📊 Rekapitulasi Progres Pengiriman Proyek:
| Keterangan | Jumlah |
|:-----------|-------:|
| Total Keseluruhan Pesanan | **{{ number_format($order->total_ordered_quantity, 0, ',', '.') }} pcs** |
| Total Diterima s/d Tahap Ini | **{{ number_format($order->batches()->whereIn('status', ['delivered'])->sum('quantity'), 0, ',', '.') }} pcs** ({{ $order->total_ordered_quantity > 0 ? round(($order->batches()->whereIn('status', ['delivered'])->sum('quantity') / $order->total_ordered_quantity) * 100, 1) : 100 }}%) |
| **🔴 Sisa Belum Kirim** | **{{ number_format($order->remaining_quantity, 0, ',', '.') }} pcs** |
</x-mail::panel>

{{-- TAHAP 4: PENGIRIMAN SINGLE / READY STOCK BERANGKAT --}}
@elseif($statusType === 'shipped')
Kabar gembira! Armada truk pabrik kami telah diberangkatkan membawa pesanan Anda dengan nomor **{{ $order->order_number }}** menuju alamat pengiriman.

<x-mail::panel>
### 🚚 Detail Pengiriman & Supir Armada
- **Armada / Ekspedisi:** {{ $order->courier ?? 'Armada Pabrik IndoRoster' }}
- **Nomor Plat Truk / Resi:** `{{ $order->tracking_number ?? '-' }}`
@if($order->courier_phone)
- **No. HP/WhatsApp Supir:** **{{ $order->courier_phone }}** *(dapat dihubungi untuk koordinasi bongkar muat)*
@endif
- **Estimasi Tiba:** 1–3 Hari Kerja

<div style="font-size: 12px; color: #6b7280; margin-top: 8px;">
<em>Mohon pastikan ada perwakilan di lokasi untuk proses serah terima dan pengecekan jumlah roster saat armada tiba.</em>
</div>
</x-mail::panel>

{{-- TAHAP 5: PESANAN SELESAI / DITERIMA LENGKAP --}}
@elseif($statusType === 'completed' || $statusType === 'delivered')
Terima kasih telah mempercayakan kebutuhan roster arsitektural kepada Pabrik **IndoRoster Indonesia**! Seluruh pesanan Anda dengan nomor **{{ $order->order_number }}** telah sukses dibongkar dan serah terima telah ditandai **SELESAI (100% LENGKAP)**.

@if($order->fulfillment_type === 'po_batch' && $order->batches()->whereNotNull('delivery_photo_path')->exists())
<x-mail::panel>
### 📸 Rekapitulasi Bukti Pembongkaran Seluruh Ritase ({{ $order->batches()->count() }} Rit Truk):

@foreach($order->batches()->whereNotNull('delivery_photo_path')->get() as $b)
#### 🚚 {{ $b->batch_name }} ({{ number_format($b->quantity, 0, ',', '.') }} pcs)
- **Waktu Penerimaan:** {{ $b->actual_delivered_date ? $b->actual_delivered_date->format('d M Y H:i') : '-' }}
- **Supir / Armada:** {{ $b->courier_name ?? 'Armada Pabrik IndoRoster' }}
@if($b->tracking_number)
- **No. Plat Truk:** `{{ $b->tracking_number }}`
@endif

@if(file_exists(storage_path('app/public/' . $b->delivery_photo_path)))
<img src="{{ $message->embed(storage_path('app/public/' . $b->delivery_photo_path)) }}" alt="Bukti {{ $b->batch_name }}" style="max-width: 100%; border-radius: 8px; margin-top: 8px; margin-bottom: 12px;">
@endif

---
@endforeach
</x-mail::panel>
@elseif($order->delivery_photo_path && file_exists(storage_path('app/public/' . $order->delivery_photo_path)))
<x-mail::panel>
### 📸 Bukti Pengiriman & Pembongkaran Material
- **Kurir / Supir Armada:** {{ $order->courier ?? 'Armada Pabrik IndoRoster' }}
- **No. Plat / Resi:** `{{ $order->tracking_number ?? '-' }}`
@if($order->courier_phone)
- **No. Kontak Supir:** {{ $order->courier_phone }}
@endif
- **Waktu Selesai:** {{ $order->completed_at ? $order->completed_at->format('d M Y H:i') : now()->format('d M Y H:i') }}

<img src="{{ $message->embed(storage_path('app/public/' . $order->delivery_photo_path)) }}" alt="Bukti Pengiriman" style="max-width: 100%; border-radius: 8px; margin-top: 8px;">
</x-mail::panel>
@endif

@endif

---

### 🛒 Ringkasan Barang Pesanan
<x-mail::table>
| Produk | Qty | Harga | Subtotal |
|:-------|:---:|:-----:|:--------:|
@foreach($order->items as $item)
| {{ $item->product_name }} {{ $item->product_variant_name ? '('.$item->product_variant_name.')' : '' }} | {{ number_format($item->quantity, 0, ',', '.') }} pcs | Rp{{ number_format($item->product_price, 0, ',', '.') }} | Rp{{ number_format($item->subtotal, 0, ',', '.') }} |
@endforeach
| | | | |
| **Subtotal Produk** | | | **Rp{{ number_format($order->subtotal, 0, ',', '.') }}** |
| **Ongkos Kirim Armada** | | | **Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}** |
@if($order->discount_amount > 0)
| **Diskon** | | | **-Rp{{ number_format($order->discount_amount, 0, ',', '.') }}** |
@endif
| **Grand Total** | | | **Rp{{ number_format($order->grand_total, 0, ',', '.') }}** |
| **Sudah Dibayar** | | | **Rp{{ number_format($totalPaid, 0, ',', '.') }}** |
| **Sisa Tagihan** | | | **{{ $remaining > 0 ? 'Rp' . number_format($remaining, 0, ',', '.') : 'Rp 0 (LUNAS)' }}** |
</x-mail::table>

---

### 📍 Alamat Tujuan Pengiriman
**{{ $order->shipping_name }}**  
{{ $order->shipping_address }}  
{{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}  
No. HP: {{ $order->shipping_phone }}

<x-mail::button :url="config('app.url') . '/lacak-pesanan?order_number=' . $order->order_number . '&contact=' . ($order->shipping_phone ?? $order->shipping_email)">
📍 Lacak Status Pesanan Saya
</x-mail::button>

Terima kasih atas kepercayaannya memilih produk roster beton presisi IndoRoster.  
Jika ada pertanyaan, jangan ragu untuk membalas email ini atau menghubungi WhatsApp Customer Support kami.

Salam Hangat,<br>
**Tim Operasional {{ config('app.name') }}**
</x-mail::message>
