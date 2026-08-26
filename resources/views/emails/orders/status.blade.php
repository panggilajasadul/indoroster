<x-mail::message>
<div style="text-align: left; display: table; width: 100%; border-bottom: 2px solid #f3f4f6; padding-bottom: 20px; margin-bottom: 20px;">
    <div style="display: table-cell; vertical-align: middle; width: 50%;">
        <img src="{{ $message->embed(public_path('assets/logo_indoroster-text.png')) }}" alt="Indoroster Logo" style="max-height: 80px; width: auto;">
    </div>
    <div style="display: table-cell; vertical-align: middle; width: 50%; text-align: right;">
        <div style="margin-bottom: 8px; font-size: 16px; font-weight: bold; color: #1f2937;">Status Pesanan</div>
        <div style="text-align: right;">
            <div style="display: inline-block; color: #ea580c; font-weight: 900; font-size: 20px; border: 3px solid #ea580c; padding: 4px 16px; border-radius: 6px; letter-spacing: 2px; transform: rotate(-5deg); margin-top: 5px;">
                LUNAS
            </div>
        </div>
    </div>
</div>

# Halo, {{ $order->shipping_name }}

@if($statusType === 'processing')
    @if($order->fulfillment_type === 'ready_stock')
Pesanan Anda dengan nomor **{{ $order->order_number }}** sedang kami siapkan di gudang pabrik dan akan segera dikirim.

<x-mail::panel>
### 📦 Jadwal Pengiriman
- **Estimasi Siap Kirim:** {{ $order->ready_shipping_date ? $order->ready_shipping_date->format('d M Y') : '1–2 hari kerja' }}
- **Estimasi Tiba di Lokasi:** {{ $order->estimated_delivery_date ? $order->estimated_delivery_date->format('d M Y') : '2–4 hari kerja' }}

<div style="font-size: 12px; color: #6b7280; margin-top: 8px;">
<em>Kami akan mengirimkan notifikasi email kembali saat armada truk telah diberangkatkan disertai info supir & plat nomor kendaraan.</em>
</div>
</x-mail::panel>

    @elseif($order->fulfillment_type === 'po_single')
Pesanan Anda dengan nomor **{{ $order->order_number }}** telah diterima dan masuk ke **antrean produksi Pre-Order (PO)** di pabrik kami.

<x-mail::panel>
### 🔨 Jadwal Produksi & Pengiriman
- **Tanggal Mulai Produksi:** {{ $order->production_start_date ? $order->production_start_date->format('d M Y') : now()->format('d M Y') }}
- **Estimasi Selesai & Siap Kirim:** {{ $order->ready_shipping_date ? $order->ready_shipping_date->format('d M Y') : '7 hari kerja' }}
- **Estimasi Tiba di Lokasi Anda:** {{ $order->estimated_delivery_date ? $order->estimated_delivery_date->format('d M Y') : '9 hari kerja' }}

@if($order->fulfillment_notes)
**Catatan Pabrik:** {{ $order->fulfillment_notes }}
@endif

<div style="font-size: 12px; color: #6b7280; margin-top: 8px;">
<em>*Roster beton kami diproduksi dengan sistem cetak press dan memerlukan waktu pengeringan alami minimal 7 hari sebelum siap kirim. Kami akan menghubungi Anda jika ada perubahan jadwal.</em>
</div>
</x-mail::panel>

    @elseif($order->fulfillment_type === 'po_batch')
Pesanan skala besar Anda dengan nomor **{{ $order->order_number }}** (Total: **{{ number_format($order->total_ordered_quantity, 0, ',', '.') }} pcs**) telah kami terima dan dijadwalkan untuk **Pengiriman Bertahap ({{ $order->batch_count }} Batch)**.

Setiap batch akan diproduksi dan dikirim secara bertahap sesuai jadwal di bawah. Anda akan menerima email notifikasi otomatis setiap kali armada truk diberangkatkan, disertai info supir dan plat nomor kendaraan.

<x-mail::panel>
### 📅 Rencana Jadwal Produksi & Pengiriman ({{ $order->batch_count }} Batch)

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
<em>*Jadwal dan kuantitas per batch di atas merupakan estimasi operasional pabrik dan dapat disesuaikan mengikuti kondisi cuaca, kapasitas produksi, serta kapasitas muatan armada truk. Anda akan dihubungi jika ada perubahan signifikan.</em>
</div>
</x-mail::panel>
    @else
Pesanan Anda dengan nomor **{{ $order->order_number }}** sedang kami siapkan / produksi. Kami akan mengabari Anda kembali jika pesanan sudah siap dikirim.
    @endif

@elseif($statusType === 'batch_shipped' && isset($batch))
Kabar baik! Armada pabrik kami telah diberangkatkan untuk **{{ $batch->batch_name }}** dari pesanan proyek nomor **{{ $order->order_number }}**.

<x-mail::panel>
### 🚚 Rincian Muatan Truk Ini ({{ $batch->batch_name }}):
- **Jumlah Muatan Truk Ini:** **{{ number_format($batch->quantity, 0, ',', '.') }} pcs**
- **Supir / Armada:** {{ $batch->courier_name ?? 'Armada Pabrik Indoroster' }}
- **No. Plat Truk:** `{{ $batch->tracking_number ?? '-' }}`
@if($batch->courier_phone)
- **No. HP/WA Supir:** {{ $batch->courier_phone }} *(bisa dihubungi untuk koordinasi bongkar muat)*
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
<em>Sisa {{ number_format($batch->remaining_quantity_after_this_batch, 0, ',', '.') }} pcs akan dikirim pada batch berikutnya sesuai jadwal. Mohon pastikan area proyek siap untuk penerimaan dan proses bongkar muat.</em>
</div>
</x-mail::panel>

@elseif($statusType === 'batch_delivered' && isset($batch))
Kabar baik! Pengiriman **{{ $batch->batch_name }}** dari pesanan proyek nomor **{{ $order->order_number }}** telah sukses diturunkan di lokasi Anda.

<x-mail::panel>
### ✅ Rincian Penerimaan Truk Ini ({{ $batch->batch_name }}):
- **Jumlah Roster Diterima:** **{{ number_format($batch->quantity, 0, ',', '.') }} pcs**
- **Waktu Penerimaan:** {{ $batch->actual_delivered_date ? $batch->actual_delivered_date->format('d M Y H:i') : now()->format('d M Y H:i') }}
- **Supir / Armada:** {{ $batch->courier_name ?? 'Armada Pabrik Indoroster' }}
- **No. Plat Truk:** `{{ $batch->tracking_number ?? '-' }}`

@if($batch->delivery_photo_path)
---

### 📸 Bukti Pembongkaran Roster  
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

@elseif($statusType === 'shipped')
Kabar gembira! Pesanan Anda dengan nomor **{{ $order->order_number }}** telah selesai diproses dan saat ini sudah diserahkan ke pihak logistik untuk dikirim ke alamat Anda.

<x-mail::panel>
### 📦 Detail Pengiriman
- **Kurir / Ekspedisi:** {{ $order->courier ?? 'Armada Pabrik Indoroster' }}
- **Nomor Resi / Plat:** {{ $order->tracking_number ?? '-' }}
- **No. WA Kurir (Sopir):** {{ $order->courier_phone ?? '-' }}
- **Estimasi Sampai:** 2–4 Hari Kerja
</x-mail::panel>

@elseif($statusType === 'completed')
Terima kasih telah berbelanja kebutuhan material bangunan di Indoroster! Pesanan Anda dengan nomor **{{ $order->order_number }}** telah ditandai **Selesai**. Kami harap Anda puas dengan produk dan layanan kami.

@if($order->delivery_photo_path)
<x-mail::panel>
### 📸 Bukti Pengiriman  
<img src="{{ $message->embed(storage_path('app/public/' . $order->delivery_photo_path)) }}" alt="Bukti Pengiriman" style="max-width: 100%; border-radius: 8px;">
</x-mail::panel>
@endif
@endif

---

### 🛒 Ringkasan Pesanan
<div style="font-size: 13px; color: #6b7280; margin-bottom: 10px;">
<em>*Hanya sebagai rincian dokumentasi. Pesanan ini <strong>Telah Lunas</strong> dibayar sebelumnya. Anda tidak perlu membayar apapun kepada kurir (kecuali pengiriman belum termasuk).</em>
</div>

<x-mail::table>
| Produk | Qty | Harga | Subtotal |
|:-------|:---:|:-----:|:--------:|
@foreach($order->items as $item)
| {{ $item->product_name }} {{ $item->product_variant_name ? '('.$item->product_variant_name.')' : '' }} | {{ number_format($item->quantity, 0, ',', '.') }} pcs | Rp{{ number_format($item->product_price, 0, ',', '.') }} | Rp{{ number_format($item->subtotal, 0, ',', '.') }} |
@endforeach
| | | | |
| **Subtotal** | | | **Rp{{ number_format($order->subtotal, 0, ',', '.') }}** |
| **Ongkos Kirim** | | | **Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}** |
@if($order->discount_amount > 0)
| **Diskon** | | | **-Rp{{ number_format($order->discount_amount, 0, ',', '.') }}** |
@endif
| **Total Akhir (LUNAS)** | | | **Rp{{ number_format($order->grand_total, 0, ',', '.') }}** |
</x-mail::table>

---

### 📍 Alamat Tujuan Pengiriman
**{{ $order->shipping_name }}**  
{{ $order->shipping_address }}  
{{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}  
No. HP: {{ $order->shipping_phone }}

@if($order->fulfillment_type === 'po_batch' || $statusType === 'batch_shipped')
<x-mail::button :url="config('app.url') . '/lacak-pesanan?order_number=' . $order->order_number . '&contact=' . $order->shipping_phone">
Lacak Progres Proyek Saya
</x-mail::button>
@else
<x-mail::button :url="config('app.url') . '/lacak-pesanan?order_number=' . $order->order_number . '&contact=' . $order->shipping_phone">
Lacak Pesanan Saya
</x-mail::button>
@endif

Terima kasih atas kepercayaannya.  
Jika ada pertanyaan, jangan ragu untuk membalas email ini atau hubungi kami via WhatsApp.

Salam Hangat,<br>
**Tim {{ config('app.name') }}**
</x-mail::message>
