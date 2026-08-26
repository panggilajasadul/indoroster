@props(['data'])

@php
    $badge = $data['badge'] ?? 'KOMPARASI STANDAR MUTU';
    $title = $data['title'] ?? 'Mengapa Roster Kami Berbeda dari Pasaran?';
    $subtitle = $data['subtitle'] ?? 'Jangan tergiur harga murah tapi mudah retak saat dipasang. Bandingkan kualitas mutu fisik kami:';
    $alignment = $data['alignment'] ?? 'center';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');

    $headerAlign = match($alignment) {
        'left' => 'text-left flex flex-col items-start',
        'right' => 'text-right flex flex-col items-end',
        default => 'text-center flex flex-col items-center mx-auto'
    };

    $defaultComparisons = [
        [
            'feature' => 'Komposisi Bahan Baku',
            'indoroster' => 'Cetak tumbuk padat dengan abu batu silika dan semen pilihan (padat & keras)',
            'market' => 'Campuran semen minim, dominan pasir biasa, rapuh & mudah rontok',
        ],
        [
            'feature' => 'Presisi Sudut & Ukuran',
            'indoroster' => 'Siku presisi 90° hasil cetakan CNC (pemasangan rapi tanpa nat tebal)',
            'market' => 'Ukuran sering melengkung dan berbeda ukuran tiap keping',
        ],
        [
            'feature' => 'Finishing Permukaan',
            'indoroster' => 'Halus & padat 2 muka, siap dicat tanpa perlu plamir tebal',
            'market' => 'Permukaan kasar, berpori besar, banyak rongga udara',
        ],
        [
            'feature' => 'Ketahanan & Kekuatan',
            'indoroster' => 'Tahan benturan keras, tahan cuaca ekstrem panas & hujan tanpa retak',
            'market' => 'Gampang gupil/pecah saat pengiriman dan saat dibor/dipasang tukang',
        ],
        [
            'feature' => 'Jaminan Garansi Pengiriman',
            'indoroster' => 'Garansi 100% Pecah Ganti Baru diantar langsung ke lokasi',
            'market' => 'Tanpa garansi pengiriman, risiko ditanggung sepenuhnya oleh pembeli',
        ],
    ];

    $comparisons = !empty($data['comparisons']) ? $data['comparisons'] : $defaultComparisons;
@endphp

<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden font-sans">
    <x-blocks._bg-theme :theme="$theme" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header -->
        <div class="{{ $headerAlign }} max-w-3xl mb-16">
            @if(!empty($badge))
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-black uppercase tracking-widest mb-4 shadow-soft-xs">
                <span>{{ $badge }}</span>
            </div>
            @endif

            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black {{ $theme->headingColor }} tracking-tight leading-tight mb-4">
                {{ $title }}
            </h2>

            @if(!empty($subtitle))
            <p class="text-sm sm:text-base {{ $theme->subColor }} leading-relaxed">
                {{ $subtitle }}
            </p>
            @endif
        </div>

        <!-- Comparison Table / Card Container -->
        <div class="max-w-4xl mx-auto rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-luxury bg-white dark:bg-slate-900">
            <!-- Table Header -->
            <div class="grid grid-cols-12 bg-slate-950 text-white p-5 sm:p-6 border-b border-slate-800 text-sm sm:text-base font-bold items-center">
                <div class="col-span-4 sm:col-span-4 text-slate-400 uppercase tracking-wider text-xs">
                    Kriteria Uji
                </div>
                <div class="col-span-4 sm:col-span-4 text-terra-400 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-terra-500"></span>
                    <span>IndoRoster (Pabrik)</span>
                </div>
                <div class="col-span-4 sm:col-span-4 text-slate-400 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                    <span>Roster Pasaran</span>
                </div>
            </div>

            <!-- Table Rows -->
            <div class="divide-y divide-slate-100 dark:divide-slate-800/80">
                @foreach($comparisons as $row)
                <div class="grid grid-cols-12 p-5 sm:p-6 text-xs sm:text-sm items-center gap-2 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                    <!-- Feature -->
                    <div class="col-span-12 sm:col-span-4 font-bold text-slate-900 dark:text-white mb-2 sm:mb-0">
                        {{ $row['feature'] ?? '' }}
                    </div>

                    <!-- IndoRoster (Winner) -->
                    <div class="col-span-6 sm:col-span-4 text-slate-800 dark:text-slate-200 flex items-start gap-2 bg-terra-500/5 dark:bg-terra-500/10 p-3 sm:p-4 rounded-2xl border border-terra-500/15">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                        <span class="font-medium leading-relaxed">{{ $row['indoroster'] ?? '' }}</span>
                    </div>

                    <!-- Market (Loser) -->
                    <div class="col-span-6 sm:col-span-4 text-slate-500 dark:text-slate-400 flex items-start gap-2 p-3 sm:p-4 rounded-2xl">
                        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                        <span class="leading-relaxed">{{ $row['market'] ?? '' }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Table Footer -->
            <div class="p-6 bg-slate-50 dark:bg-slate-950/60 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
                <div class="text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                    💡 <em>Investasi jangka panjang untuk dinding rumah yang kokoh dan bebas retak.</em>
                </div>
                <a href="{{ route('catalog') }}" class="px-6 py-3 bg-slate-900 hover:bg-black text-white dark:bg-terra-500 dark:hover:bg-terra-600 font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md">
                    Lihat Katalog Roster Presisi
                </a>
            </div>
        </div>

    </div>
</section>
