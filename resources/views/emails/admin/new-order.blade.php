<x-mail::message>
<div style="text-align: left; display: table; width: 100%; border-bottom: 2px solid #f3f4f6; padding-bottom: 20px; margin-bottom: 20px;">
    <div style="display: table-cell; vertical-align: middle; width: 50%;">
        <img src="{{ $message->embed(public_path('assets/logo_indoroster-text.png')) }}" alt="Indoroster Logo" style="max-height: 80px; width: auto;">
    </div>
    <div style="display: table-cell; vertical-align: middle; width: 50%; text-align: right;">
        <div style="margin-bottom: 8px; font-size: 16px; font-weight: bold; color: #1f2937;">Notifikasi Admin</div>
        <div style="text-align: right;">
            <div style="display: inline-block; background-color: #dbeafe; color: #1e40af; padding: 6px 14px; border-radius: 9999px; font-weight: bold; font-size: 14px;">
                <span style="margin-right: 4px;">🔔</span> ORDER BARU
            </div>
        </div>
    </div>
</div>
# 🔔 Pesanan Baru Menunggu!

Halo **Admin Keuangan**,  
Ada pesanan baru masuk ke dalam sistem. Menunggu pembeli melakukan pembayaran via Midtrans.

<x-mail::panel>
### 👤 Info Pembeli
- **Nama:** {{ $order->shipping_name }}
- **Email:** {{ $order->shipping_email ?? '-' }}
- **No. HP:** {{ $order->shipping_phone }}
- **Waktu Order:** {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB
</x-mail::panel>

### 💰 Nominal Tagihan
<div style="background-color: #fef08a; padding: 15px; border-radius: 8px; font-size: 24px; font-weight: bold; color: #854d0e; text-align: center; margin-bottom: 20px; border: 1px solid #eab308;">
Rp {{ number_format($order->grand_total, 0, ',', '.') }}
</div>

### 🛒 Rincian Pesanan
@foreach($order->items as $item)
- {{ $item->quantity }}x {{ $item->product_name }} {{ $item->product_variant_name ? '('.$item->product_variant_name.')' : '' }}
@endforeach
- *Ongkos Kirim: Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}*
@if($order->discount_amount > 0)
- *Diskon: -Rp {{ number_format($order->discount_amount, 0, ',', '.') }}*
@endif

<x-mail::button :url="config('app.url') . '/admin/orders/' . $order->id">
Buka Dashboard Admin Sekarang
</x-mail::button>

---
*Pesan Otomatis dari Sistem {{ config('app.name') }}*
</x-mail::message>
