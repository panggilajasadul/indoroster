@php
    $record = $getRecord();
@endphp
<div class="p-1">
    <div class="flex justify-between items-start mb-2">
        <p class="text-orange-600 dark:text-orange-500 font-bold text-sm sm:text-base margin-0">
            #{{ $record->order_number }}
        </p>
        <span class="bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-900/50 rounded-full px-3 py-0.5 text-[10px] font-bold">
            ✅ Selesai
        </span>
    </div>
    <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm mb-1">
        Pelanggan: <strong class="text-slate-800 dark:text-white font-bold">{{ $record->shipping_name }}</strong>
    </p>
    <p class="text-slate-400 dark:text-slate-500 text-[11px] sm:text-xs leading-relaxed mb-2">
        Alamat: {{ $record->shipping_address }}
    </p>
    @if ($record->completed_at)
        <p class="text-emerald-600 dark:text-emerald-400 text-[10px] sm:text-xs font-medium mt-1 flex items-center gap-1">
            <span>🕐 Diantar:</span>
            <strong>{{ \Carbon\Carbon::parse($record->completed_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') }}</strong>
        </p>
    @endif
</div>
