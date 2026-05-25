@php
    $record = $getRecord();
    $phone = preg_replace('/[^0-9]/', '', $record->shipping_phone ?? '');
    if (str_starts_with($phone, '0')) { $phone = '62' . substr($phone, 1); }
    $waUrl = 'https://wa.me/' . $phone . '?text=Halo%20' . urlencode($record->shipping_name) . ',%20saya%20kurir%20Indoroster%20ingin%20mengirimkan%20pesanan%20Anda.';
    $mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($record->shipping_address);
@endphp
<div style="padding: 4px 2px 8px 2px; max-width: 100%; box-sizing: border-box; overflow: hidden; word-break: break-word;">
    <p style="color: #f97316; font-weight: 700; font-size: 0.95rem; margin: 0 0 6px 0;">
        Order: #{{ $record->order_number }}
    </p>
    <p style="color: #9ca3af; font-size: 0.88rem; margin: 0 0 4px 0;">
        Pelanggan: <strong style="color: #ffffff; font-weight: 700;">{{ $record->shipping_name }}</strong>
    </p>
    <p style="color: #6b7280; font-size: 0.82rem; margin: 0 0 14px 0; line-height: 1.5;">
        Alamat: {{ $record->shipping_address }}
    </p>

    {{-- Tombol Utama: Selesaikan Pesanan --}}
    <button
        wire:click="mountTableAction('complete_delivery', '{{ $record->getKey() }}')"
        style="width:100%; padding: 10px 12px; margin-bottom: 10px; background: linear-gradient(135deg, #f97316, #ea580c); border: none; border-radius: 999px; color: #fff; font-weight: 700; font-size: clamp(0.72rem, 3.5vw, 0.85rem); text-transform: uppercase; letter-spacing: 0.04em; cursor: pointer; box-shadow: 0 4px 15px rgba(249,115,22,0.4); display: flex; align-items: center; justify-content: center; gap: 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; box-sizing: border-box;">
        ✅ Selesaikan Pesanan
    </button>

    {{-- Tombol Sekunder --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px; width: 100%; box-sizing: border-box;">
        <a href="{{ $waUrl }}" target="_blank"
           style="display:flex; align-items:center; justify-content:center; padding: 8px 4px; border: 1.5px solid #f97316; border-radius: 999px; color: #f97316; font-size: clamp(0.65rem, 3vw, 0.78rem); font-weight: 600; text-decoration: none; gap: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; box-sizing: border-box;">
            📞 WA Pelanggan
        </a>
        <a href="{{ $mapsUrl }}" target="_blank"
           style="display:flex; align-items:center; justify-content:center; padding: 8px 4px; border: 1.5px solid #f97316; border-radius: 999px; color: #f97316; font-size: clamp(0.65rem, 3vw, 0.78rem); font-weight: 600; text-decoration: none; gap: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; box-sizing: border-box;">
            🗺️ Lacak Alamat
        </a>
    </div>
</div>
