@props([
    'title' => 'Cakupan Distribusi Proyek ke Kota-Kota Utama',
    'cities' => ['jakarta', 'bandung', 'bekasi', 'tangerang', 'karawang', 'bogor'],
])

@php
    $cityModels = \App\Models\SeoLocation::where('seo_enabled', true)
        ->where(function ($q) use ($cities) {
            foreach ($cities as $c) {
                $q->orWhere('slug', 'like', "%{$c}%")
                  ->orWhere('name', 'like', "%{$c}%");
            }
        })
        ->orderBy('priority', 'asc')
        ->take(6)
        ->get();
@endphp

@if($cityModels->count() > 0)
<div class="my-16 bg-white dark:bg-slate-900 p-6 sm:p-10 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h3 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                {{ $title }}
            </h3>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                Armada truk material IndoRoster melayani rute pengiriman rutin langsung ke lokasi proyek di kota-kota berikut:
            </p>
        </div>
        <a href="{{ route('location.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-terra-600 dark:text-terra-400 hover:underline whitespace-nowrap">
            Lihat Semua 25+ Kota Area Layanan &rarr;
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
        @foreach($cityModels as $loc)
        <a href="{{ route('location.detail', $loc->slug) }}" class="p-3.5 rounded-2xl bg-slate-50/80 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 border border-slate-200/70 dark:border-slate-700/60 hover:border-terra-500 dark:hover:border-terra-500 hover:shadow-soft-md transition-all duration-300 flex flex-col justify-between group">
            <div>
                <div class="text-terra-600 dark:text-terra-400 text-xs font-bold mb-1">📍 {{ $loc->name }}</div>
                <div class="text-[10px] text-slate-500 dark:text-slate-400 line-clamp-1">{{ $loc->estimated_delivery_time ?: '1-2 Hari Kerja' }}</div>
            </div>
            <div class="mt-2 pt-2 border-t border-slate-200/50 dark:border-slate-700/50 flex items-center justify-between text-[10px] font-semibold text-slate-500 dark:text-slate-400 group-hover:text-terra-600 dark:group-hover:text-terra-400">
                <span>Roster {{ $loc->name }}</span>
                <span class="transform group-hover:translate-x-0.5 transition-transform">&rarr;</span>
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif
