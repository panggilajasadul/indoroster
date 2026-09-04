<x-mail::message>
<div style="text-align: left; display: table; width: 100%; border-bottom: 2px solid #f3f4f6; padding-bottom: 20px; margin-bottom: 20px;">
    <div style="display: table-cell; vertical-align: middle; width: 50%;">
        <img src="{{ $message->embed(public_path('assets/logo_indoroster-text.png')) }}" alt="Indoroster Logo" style="max-height: 80px; width: auto;">
    </div>
    <div style="display: table-cell; vertical-align: middle; width: 50%; text-align: right;">
        <div style="margin-bottom: 8px; font-size: 16px; font-weight: bold; color: #1f2937;">Notifikasi Admin</div>
        <div style="text-align: right;">
            <div style="display: inline-block; background-color: #dcfce7; color: #15803d; padding: 6px 14px; border-radius: 9999px; font-weight: bold; font-size: 14px;">
                ORDER WA
            </div>
        </div>
    </div>
</div>

# 🟢 Pesanan WhatsApp Baru Masuk!

Halo **Admin IndoRoster**,
Ada pesanan baru dari website melalui jalur **WhatsApp Order**. Pembeli telah mengirimkan detail pesanan via WA dan pesanan sudah tercatat di sistem.

<x-mail::panel>
### 👤 Info Pembeli
- **Nama:** {{ $order->shipping_name }}
- **No. WhatsApp:** {{ $order->shipping_phone }}
- **Email:** {{ $order->shipping_email ?? '(tidak ada email)' }}
- **Waktu Order:** {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB
</x-mail::panel>

<x-mail::panel>
### 📦 Rincian Pesanan
- **No. Pesanan:** `{{ $order->order_number }}`
- **Skema Bayar:** {{ match($order->payment_scheme ?? 'full') { 'dp_50_50' => 'DP 50% + Pelunasan 50%', 'termin_3x' => 'Termin 3x (30%+40%+30%)', 'custom_dp' => 'Kustom DP', default => 'Lunas Langsung (100%)' } }}
@foreach($order->items as $item)
- {{ $item->quantity }}x **{{ $item->product_name }}** {{ $item->product_variant_name ? '('.$item->product_variant_name.')' : '' }}
@endforeach
- *Ongkos Kirim: Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}*
@if($order->discount_amount > 0)
- *Diskon: -Rp {{ number_format($order->discount_amount, 0, ',', '.') }}*
@endif
</x-mail::panel>

### 💰 Total Tagihan
<div style="background-color: #fef08a; padding: 15px; border-radius: 8px; font-size: 24px; font-weight: bold; color: #854d0e; text-align: center; margin-bottom: 20px; border: 1px solid #eab308;">
Rp {{ number_format($order->grand_total, 0, ',', '.') }}
</div>

<x-mail::panel>
### 📍 Alamat Pengiriman / Titik Proyek
**{{ $order->shipping_name }}**
{{ $order->shipping_address }}
@if($order->shipping_village) Kel. {{ $order->shipping_village }},@endif
@if($order->shipping_district) Kec. {{ $order->shipping_district }},@endif
{{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}
@if($order->shipping_latitude && $order->shipping_longitude)
📌 **GPS:** [{{ $order->shipping_latitude }}, {{ $order->shipping_longitude }}](https://maps.google.com/?q={{ $order->shipping_latitude }},{{ $order->shipping_longitude }})
@endif
@if($order->notes)

💬 **Catatan Pesanan:** {{ $order->notes }}
@endif
</x-mail::panel>

<x-mail::button :url="config('app.url') . '/admin/wa-orders/' . $order->id">
🟢 Buka Detail Pesanan WA di Panel Admin
</x-mail::button>

---
*Pesan Otomatis dari Sistem {{ config('app.name') }}*
</x-mail::message>
