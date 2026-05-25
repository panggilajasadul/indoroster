@php
    $record = $getRecord();
@endphp
<div style="padding: 4px 2px 8px 2px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px;">
        <p style="color: #f97316; font-weight: 700; font-size: 0.95rem; margin: 0;">
            #{{ $record->order_number }}
        </p>
        <span style="background: rgba(34,197,94,0.15); color: #22c55e; border: 1px solid rgba(34,197,94,0.3); border-radius: 999px; padding: 2px 10px; font-size: 0.72rem; font-weight: 600;">
            ✅ Selesai
        </span>
    </div>
    <p style="color: #9ca3af; font-size: 0.88rem; margin: 0 0 4px 0;">
        Pelanggan: <strong style="color: #ffffff; font-weight: 700;">{{ $record->shipping_name }}</strong>
    </p>
    <p style="color: #6b7280; font-size: 0.82rem; margin: 0 0 8px 0; line-height: 1.5;">
        Alamat: {{ $record->shipping_address }}
    </p>
    @if ($record->completed_at)
        <p style="color: #22c55e; font-size: 0.78rem; margin: 0;">
            🕐 Diantar: {{ \Carbon\Carbon::parse($record->completed_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }}
        </p>
    @endif
</div>
