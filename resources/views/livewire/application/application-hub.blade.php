@php
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Beranda',
                'item' => route('home'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Inspirasi Aplikasi Roster',
                'item' => route('application.index'),
            ],
        ],
    ];

    $catalogSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'OfferCatalog',
        'name' => 'Katalog Aplikasi & Inspirasi Roster Beton',
        'description' => 'Panduan pengaplikasian roster beton minimalis untuk pagar, fasad rumah, ventilasi dinding, partisi ruangan, cafe, dan gedung komersial.',
        'itemListElement' => array_map(function ($app) {
            return [
                '@type' => 'Offer',
                'itemOffered' => [
                    '@type' => 'Service',
                    'name' => $app['title'],
                    'description' => $app['subtitle'],
                    'url' => route('application.detail', $app['slug']),
                ],
            ];
        }, $applications),
    ];
@endphp

@push('seo')
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode($catalogSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-10">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-white via-slate-50 to-orange-50/20 dark:from-slate-900 dark:via-slate-850 dark:to-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-900 dark:text-white p-8 sm:p-12 lg:p-16 shadow-soft-xl dark:shadow-2xl">
            <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-terra-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 max-w-3xl">
                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-6 font-medium">
                    <a href="{{ route('home') }}" class="hover:text-slate-900 dark:hover:text-white transition">Beranda</a>
                    <span>/</span>
                    <span class="text-terra-600 dark:text-terra-400 font-bold">Inspirasi Aplikasi Roster</span>
                </nav>

                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-terra-500/10 border border-terra-500/30 text-terra-600 dark:text-terra-400 text-xs font-bold uppercase tracking-wider mb-6 shadow-xs">
                    <span>📐</span> Panduan Desain & Arsitektur Roster Beton
                </div>

                <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight text-slate-900 dark:text-white mb-6">
                    Inspirasi Aplikasi <span class="text-terra-500">Roster Beton</span> Minimalis Modern
                </h1>

                <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 mb-8 leading-relaxed">
                    Eksplorasi ragam pengaplikasian roster beton arsitektural cetak tumbuk padat presisi: dari pagar rumah tropis, fasad secondary skin, ventilasi dinding bebas pengap, partisi ruangan estetik, hingga fasad cafe dan gedung komersial.
                </p>

                <div class="flex flex-wrap items-center gap-4">
                    <a href="{{ route('catalog') }}" class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-xl bg-terra-500 hover:bg-terra-600 text-white font-bold text-sm sm:text-base shadow-lg shadow-terra-500/25 transition-all hover:scale-[1.02]">
                        Lihat Katalog Lengkap &rarr;
                    </a>
                    <a href="{{ route('tools.calculator') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold text-sm transition-all border border-slate-300 dark:border-slate-700 shadow-xs">
                        🧮 Hitung Kebutuhan Dinding &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Cards Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($applications as $app)
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-soft-xs hover:shadow-soft-xl hover:border-terra-400/90 dark:hover:border-terra-500 transition-all duration-300 group flex flex-col justify-between">
                <a href="{{ route('application.detail', $app['slug']) }}" class="block aspect-[16/10] relative bg-slate-100 dark:bg-slate-800 overflow-hidden">
                    <img src="{{ $app['image'] }}" alt="{{ $app['title'] }} — Roster Beton IndoRoster" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>
                    <span class="absolute top-4 left-4 px-3 py-1 rounded-xl bg-slate-950/80 text-white text-xs font-bold backdrop-blur-xs z-10 flex items-center gap-1.5 border border-white/10">
                        <span>{{ $app['icon'] }}</span>
                        <span>Aplikasi Desain</span>
                    </span>
                </a>

                <div class="p-6 sm:p-7 flex-1 flex flex-col justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-terra-600 dark:group-hover:text-terra-400 transition-colors">
                            <a href="{{ route('application.detail', $app['slug']) }}">
                                {{ $app['title'] }}
                            </a>
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-6">
                            {{ $app['subtitle'] }}
                        </p>
                    </div>

                    <div>
                        <div class="mb-5 pt-4 border-t border-slate-100 dark:border-slate-800">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Motif Rekomendasi:</span>
                            <div class="flex flex-wrap gap-1.5">
                                @foreach($app['recommended_motifs'] as $motif)
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-[11px] font-semibold border border-slate-200/60 dark:border-slate-700/60">
                                    {{ $motif }}
                                </span>
                                @endforeach
                            </div>
                        </div>

                        <a href="{{ route('application.detail', $app['slug']) }}" class="w-full py-2.5 px-4 rounded-xl bg-slate-900 dark:bg-slate-800 hover:bg-terra-500 dark:hover:bg-terra-500 text-white text-xs font-bold flex items-center justify-center gap-2 transition-all group-hover:bg-terra-500">
                            <span>Pelajari Detail & Rekomendasi</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Quick CTA Banner -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-terra-500 text-white p-8 sm:p-12 text-center relative overflow-hidden shadow-xl shadow-terra-500/20">
            <div class="relative z-10 max-w-2xl mx-auto">
                <h2 class="text-2xl sm:text-4xl font-extrabold mb-4">Konsultasikan Kebutuhan Desain Roster Anda</h2>
                <p class="text-terra-100 text-sm sm:text-base mb-8">
                    Dapatkan rekomendasi motif terbaik sesuai luas dinding dan arah pencahayaan ruangan Anda langsung dari tim ahli pabrik IndoRoster.
                </p>
                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Admin IndoRoster, saya ingin konsultasi pemilihan motif roster beton untuk kebutuhan proyek saya.') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2.5 px-8 py-4 rounded-xl bg-slate-900 hover:bg-slate-850 text-white font-bold text-base shadow-2xl transition hover:scale-105">
                    <span>💬</span> Chat Konsultasi Desain via WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>
