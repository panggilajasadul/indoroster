@php
    $visitorStats = \App\Services\VisitorTrackerService::getStats();
@endphp

<div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 sm:p-6 shadow-md text-slate-200 w-full">
    {{-- Header Widget --}}
    <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-800/80">
        <div class="flex items-center gap-3">
            <span class="text-xl">📊</span>
            <div>
                <h3 class="text-sm sm:text-base font-extrabold tracking-tight text-white uppercase font-display">
                    Statistik Pengunjung
                </h3>
                <div class="w-12 h-0.5 bg-terra-500 rounded-full mt-1"></div>
            </div>
        </div>
        <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>{{ number_format($visitorStats['online'], 0, ',', '.') }} Pengunjung Online</span>
        </div>
    </div>

    {{-- 5 Kolom Metrik Horizontal ke Samping di Desktop (Sesuai Foto) --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 pb-5 border-b border-slate-800/80">
        {{-- Item 1 --}}
        <div class="bg-slate-950/60 border border-slate-800/80 rounded-xl p-3.5 flex flex-col justify-center">
            <span class="text-xs text-slate-400 font-medium mb-1">Kunjungan Hari Ini</span>
            <span class="text-lg sm:text-xl font-extrabold text-white tracking-tight">
                {{ number_format($visitorStats['today_hits'], 0, ',', '.') }}
            </span>
        </div>

        {{-- Item 2 --}}
        <div class="bg-slate-950/60 border border-slate-800/80 rounded-xl p-3.5 flex flex-col justify-center">
            <span class="text-xs text-slate-400 font-medium mb-1">Pengunjung Hari Ini</span>
            <span class="text-lg sm:text-xl font-extrabold text-white tracking-tight">
                {{ number_format($visitorStats['today_visitors'], 0, ',', '.') }}
            </span>
        </div>

        {{-- Item 3 --}}
        <div class="bg-slate-950/60 border border-slate-800/80 rounded-xl p-3.5 flex flex-col justify-center">
            <span class="text-xs text-slate-400 font-medium mb-1">Total Kunjungan</span>
            <span class="text-lg sm:text-xl font-extrabold text-white tracking-tight">
                {{ number_format($visitorStats['total_hits'], 0, ',', '.') }}
            </span>
        </div>

        {{-- Item 4 --}}
        <div class="bg-slate-950/60 border border-slate-800/80 rounded-xl p-3.5 flex flex-col justify-center">
            <span class="text-xs text-slate-400 font-medium mb-1">Total Pengunjung</span>
            <span class="text-lg sm:text-xl font-extrabold text-white tracking-tight">
                {{ number_format($visitorStats['total_visitors'], 0, ',', '.') }}
            </span>
        </div>

        {{-- Item 5 --}}
        <div class="bg-slate-950/60 border border-slate-800/80 rounded-xl p-3.5 flex flex-col justify-center col-span-2 sm:col-span-1">
            <span class="text-xs text-emerald-400 font-medium mb-1 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Pengunjung Online
            </span>
            <span class="text-lg sm:text-xl font-extrabold text-emerald-400 tracking-tight">
                {{ number_format($visitorStats['online'], 0, ',', '.') }}
            </span>
        </div>
    </div>

    {{-- Bagian Asal Negara / Data Geografis (Grid Horizontal) --}}
    @if(!empty($visitorStats['countries']))
        <div class="mt-4 pt-1">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400 flex items-center gap-2">
                    <span>🌍</span> Asal Negara Pengunjung (Visitor Count)
                </span>
                <span class="text-[11px] text-slate-500">Update Realtime</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 text-xs">
                @foreach($visitorStats['countries'] as $country)
                    <div class="flex items-center justify-between px-3 py-2 rounded-lg bg-slate-950/50 border border-slate-800/60">
                        <span class="flex items-center gap-1.5 truncate pr-2 text-slate-300">
                            <span class="text-sm shrink-0">{{ $country['flag'] ?? '🌐' }}</span>
                            <span class="truncate">{{ $country['country_name'] ?? $country['country_code'] }}</span>
                        </span>
                        <span class="font-bold text-white shrink-0 font-mono text-[11px]">
                            {{ number_format($country['total_visitors'], 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
