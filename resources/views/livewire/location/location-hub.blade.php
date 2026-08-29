<div>
@if(isset($page) && $page && is_array($page->content) && count($page->content) > 0)
    <x-block-renderer :blocks="$page->content" :page-title="$page->title" />
@else
<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-14">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-terra-500/10 border border-terra-500/30 text-terra-500 text-xs font-semibold uppercase tracking-wider mb-4">
                <span>📍</span> Jangkauan Distribusi Nasional
            </div>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                {!! !empty($page?->title) && $page->title !== 'Area Layanan Pengiriman Roster Beton Seluruh Indonesia' ? e($page->title) : 'Wilayah Layanan Pengiriman <span class="text-terra-500">Roster Beton</span>' !!}
            </h1>
            <p class="mt-4 text-slate-600 dark:text-slate-400 text-sm sm:text-base leading-relaxed">
                {{ $page?->meta_description ?: 'Sebagai produsen tangan pertama, armada truk kami siap mengirimkan pesanan partai kecil maupun ribuan pieces langsung ke pintu proyek Anda dengan jaminan garansi aman 100% bebas pecah.' }}
            </p>
        </div>

        <!-- City Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
            @foreach($locations as $loc)
            <a href="{{ route('location.detail', $loc->slug) }}" class="bg-white dark:bg-slate-900 rounded-3xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-xl hover:border-terra-500/40 transition duration-300 group flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-terra-50 dark:bg-terra-500/10 text-terra-600 dark:text-terra-400 text-xs font-bold">
                            <span>🚚</span> {{ $loc->estimated_delivery_time ?: '1-2 Hari Kerja' }}
                        </span>
                        <span class="text-xs font-semibold text-slate-400">Prioritas {{ $loc->priority }}</span>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white group-hover:text-terra-500 transition mb-2">
                        Roster Beton {{ $loc->name }}
                    </h2>
                    <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-3 mb-4 leading-relaxed">
                        {{ $loc->intro_content }}
                    </p>
                </div>
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs font-bold text-terra-500">
                    <span>Lihat Layanan & Produk &rarr;</span>
                    <span class="text-slate-400 font-normal">Garansi Pecah Ganti 100%</span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif
</div>
