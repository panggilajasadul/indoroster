@php
    $record = $getRecord();
    $phone = preg_replace('/[^0-9]/', '', $record->shipping_phone ?? '');
    if (str_starts_with($phone, '0')) { $phone = '62' . substr($phone, 1); }
    $waUrl = 'https://wa.me/' . $phone . '?text=Halo%20' . urlencode($record->shipping_name) . ',%20saya%20kurir%20Indoroster%20ingin%20mengirimkan%20pesanan%20Anda.';
    $hasGps = !empty($record->shipping_latitude) && !empty($record->shipping_longitude);
    $mapsUrl = $hasGps
        ? 'https://www.google.com/maps/dir/?api=1&destination=' . $record->shipping_latitude . ',' . $record->shipping_longitude
        : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($record->shipping_address . ', ' . $record->shipping_city);

    // Untuk PO Batch: ambil batch yang ditugaskan ke kurir ini
    $isBatch = $record->fulfillment_type === 'po_batch';
    $myBatches = $isBatch
        ? $record->batches()->where('courier_id', auth()->id())->whereIn('status', ['shipped'])->orderBy('batch_number')->get()
        : collect();
    $totalBatches = $isBatch ? $record->batches()->count() : 0;
    $shippedBatches = $isBatch ? $record->batches()->whereIn('status', ['shipped', 'delivered'])->count() : 0;
@endphp

<div class="p-1 max-w-full box-border overflow-hidden break-words">

    {{-- Header Pesanan --}}
    <p class="text-orange-600 dark:text-orange-500 font-bold text-sm sm:text-base flex items-center justify-between mb-1">
        <span>Order: #{{ $record->order_number }}</span>
        @if($isBatch)
        <span class="bg-amber-100 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 text-[10px] px-2.5 py-0.5 rounded-full font-bold border border-amber-200 dark:border-amber-900/60 ml-2">
            🚚 PO Batch ({{ $shippedBatches }}/{{ $totalBatches }} Terkirim)
        </span>
        @endif
    </p>
    
    <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm mb-1">
        Pelanggan: <strong class="text-slate-800 dark:text-white font-bold">{{ $record->shipping_name }}</strong>
    </p>
    
    <p class="text-slate-500 dark:text-slate-500 text-[11px] sm:text-xs leading-relaxed mb-3">
        Alamat: {{ $record->shipping_address }}
        @if($hasGps)
            <span class="inline-flex items-center gap-1 text-[10px] text-emerald-600 dark:text-emerald-400 font-bold ml-1 bg-emerald-50 dark:bg-emerald-950/40 px-1.5 py-0.5 rounded border border-emerald-200 dark:border-emerald-800">
                📍 GPS Terpasang
            </span>
        @endif
    </p>

    {{-- Untuk PO Batch: tampilkan batch yang jadi tanggung jawab kurir ini --}}
    @if($isBatch && $myBatches->isNotEmpty())
    <div class="courier-batch-box">
        <div class="courier-batch-title">
            📦 Batch yang Kamu Antar
        </div>
        @foreach($myBatches as $batch)
        <div class="courier-batch-item">
            <div class="courier-batch-text-main">{{ $batch->batch_name }} — {{ number_format($batch->quantity, 0, ',', '.') }} pcs</div>
            <div class="courier-batch-text-sub">Plat: <span style="font-family: monospace; font-weight: 700;">{{ $batch->tracking_number ?? '-' }}</span></div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Tombol Utama: Selesaikan Pesanan --}}
    <button
        wire:click="mountTableAction('complete_delivery', '{{ $record->getKey() }}')"
        style="width: 100% !important; padding: 10px 12px !important; margin-bottom: 10px !important; background: linear-gradient(135deg, #f97316, #ea580c) !important; border: none !important; border-radius: 999px !important; color: #ffffff !important; font-weight: 700 !important; font-size: 0.82rem !important; text-transform: uppercase !important; letter-spacing: 0.04em !important; cursor: pointer !important; box-shadow: 0 4px 15px rgba(249,115,22,0.3) !important; display: flex !important; align-items: center !important; justify-content: center !important; gap: 6px !important; line-height: 1.5 !important;">
        <span>✅ Selesaikan Pengiriman</span>
    </button>

    {{-- Tombol Sekunder --}}
    <div style="display: grid !important; grid-template-columns: 1fr 1fr !important; gap: 8px !important; width: 100% !important;">
        <a href="{{ $waUrl }}" target="_blank"
           style="display: flex !important; align-items: center !important; justify-content: center !important; padding: 8px 4px !important; border: 1.5px solid #ea580c !important; border-radius: 999px !important; color: #ea580c !important; font-size: 0.75rem !important; font-weight: 600 !important; text-decoration: none !important; gap: 3px !important; background: transparent !important; text-align: center !important; box-sizing: border-box !important;">
            📞 WA Pelanggan
        </a>
        <a href="{{ $mapsUrl }}" target="_blank"
           style="display: flex !important; align-items: center !important; justify-content: center !important; padding: 8px 4px !important; border: 1.5px solid #ea580c !important; border-radius: 999px !important; color: #ea580c !important; font-size: 0.75rem !important; font-weight: 600 !important; text-decoration: none !important; gap: 3px !important; background: {{ $hasGps ? '#fff7ed' : 'transparent' }} !important; text-align: center !important; box-sizing: border-box !important;">
            {{ $hasGps ? '📍 Navigasi GPS' : '🗺️ Lacak Alamat' }}
        </a>
    </div>
</div>
