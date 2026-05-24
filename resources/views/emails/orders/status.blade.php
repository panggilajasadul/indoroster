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
Pesanan Anda dengan nomor **{{ $order->order_number }}** sedang kami siapkan / produksi. Estimasi penyiapan pesanan adalah maksimal 3 hari kerja tergantung antrean pesanan. Kami akan mengabari Anda kembali jika pesanan sudah siap dikirim.
@elseif($statusType === 'shipped')
Kabar gembira! Pesanan Anda dengan nomor **{{ $order->order_number }}** telah selesai diproses dan saat ini sudah diserahkan ke pihak logistik untuk dikirim ke alamat Anda.

<x-mail::panel>
### 📦 Detail Pengiriman
- **Kurir / Ekspedisi:** {{ $order->courier ?? 'Armada Pabrik Indoroster' }}
- **Nomor Resi / Plat:** {{ $order->tracking_number ?? '-' }}
- **No. WA Kurir (Sopir):** {{ $order->courier_phone ?? '-' }}
- **Estimasi Sampai:** 2-4 Hari Kerja
</x-mail::panel>

@elseif($statusType === 'completed')
Terima kasih telah berbelanja kebutuhan material bangunan di Indoroster! Pesanan Anda dengan nomor **{{ $order->order_number }}** telah ditandai selesai. Kami harap Anda puas dengan produk dan layanan kami.

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
| {{ $item->product_name }} {{ $item->product_variant_name ? '('.$item->product_variant_name.')' : '' }} | {{ $item->quantity }} | Rp{{ number_format($item->product_price, 0, ',', '.') }} | Rp{{ number_format($item->subtotal, 0, ',', '.') }} |
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

### 📍 Alamat Tujuan
**{{ $order->shipping_name }}**  
{{ $order->shipping_address }}  
{{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}  
No. HP: {{ $order->shipping_phone }}

<x-mail::button :url="config('app.url') . '/member/pesanan/' . $order->order_number">
Lacak Pesanan Saya
</x-mail::button>

Terima kasih atas kepercayaannya.  
Jika ada pertanyaan, jangan ragu untuk membalas email ini.

Salam Hangat,<br>
**Tim {{ config('app.name') }}**
</x-mail::message>
