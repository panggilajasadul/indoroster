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
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $application['title'],
                'item' => route('application.detail', $slug),
            ],
        ],
    ];

    $webPageSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebPage',
        'name' => $application['title'],
        'description' => $application['meta_description'],
        'url' => route('application.detail', $slug),
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'IndoRoster',
            'url' => route('home'),
            'logo' => asset('assets/logo_indoroster_no_text.PNG'),
        ],
    ];

    $serviceSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => "Suplai Roster Beton untuk {$application['title']}",
        'serviceType' => 'Suplai Roster Beton Minimalis & Material Arsitektural Presisi',
        'provider' => [
            '@type' => 'Organization',
            'name' => 'IndoRoster',
            'url' => route('home'),
            'logo' => asset('assets/logo_indoroster_no_text.PNG'),
        ],
        'areaServed' => [
            '@type' => 'Country',
            'name' => 'Indonesia',
        ],
        'description' => $application['meta_description'],
    ];

    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(function ($item) {
            return [
                '@type' => 'Question',
                'name' => $item['q'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $item['a'],
                ],
            ];
        }, $application['faqs']),
    ];
@endphp

@push('seo')
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode($webPageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode($serviceSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<script type="application/ld+json">
{!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
<x-ecommerce-itemlist-schema :name="'Katalog Rekomendasi Roster untuk ' . $application['title']" :description="$application['meta_description']" :products="$recommendedProducts" />
@endpush

<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-8 sm:py-12" x-data="{ lightboxOpen: false, lightboxImg: '', lightboxTitle: '', lightboxDesc: '' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12 sm:space-y-16">

        {{-- ══════════════════════════════════════════════════════════════
             1. HERO SECTION: BREADCRUMB, VALUE STACK, & TITLE
        ══════════════════════════════════════════════════════════════ --}}
        <div class="relative rounded-3xl overflow-hidden bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-900 dark:text-white p-6 sm:p-10 lg:p-12 shadow-soft-xl">
            <div class="absolute -right-20 -top-20 w-96 h-96 bg-terra-500/10 dark:bg-terra-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-amber-500/10 dark:bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10">
                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-5 font-medium" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}" class="hover:text-terra-500 transition">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('application.index') }}" class="hover:text-terra-500 transition">Inspirasi Aplikasi</a>
                    <span>/</span>
                    <span class="text-terra-600 dark:text-terra-400 font-bold truncate">{{ $application['title'] }}</span>
                </nav>

                <!-- Trust Badges -->
                <div class="flex flex-wrap items-center gap-2 mb-6">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-terra-50 dark:bg-terra-950/60 border border-terra-200 dark:border-terra-800 text-terra-700 dark:text-terra-300 text-xs font-bold uppercase tracking-wider">
                        🏭 Produsen Tangan Pertama Plered
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-xs font-bold uppercase tracking-wider">
                        🚚 Siap Kirim Jabodetabek & Nasional
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 text-xs font-bold uppercase tracking-wider">
                        📄 Termasuk Pajak (Bisa Faktur)
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 text-xs font-bold uppercase tracking-wider">
                        🛡️ Garansi 100% Ganti Baru
                    </span>
                </div>

                <!-- Main H1 Headline -->
                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight text-slate-900 dark:text-white mb-6 max-w-4xl">
                    {{ $application['headline'] }}
                </h1>

                <!-- Rich Intro Narrative -->
                <p class="text-sm sm:text-base lg:text-lg text-slate-600 dark:text-slate-300 leading-relaxed max-w-4xl mb-8">
                    {{ $application['intro'] }}
                </p>

                <!-- Quick Action Buttons -->
                <div class="flex flex-wrap items-center gap-4 pt-2">
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm sm:text-base shadow-lg shadow-emerald-600/25 transition-all hover:scale-[1.02]">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        <span>Konsultasi Proyek (WhatsApp)</span>
                    </a>
                    <a href="{{ route('tools.calculator') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold text-sm transition-all border border-slate-300 dark:border-slate-700 shadow-xs">
                        <span>🧮 Hitung Kebutuhan Keping</span>
                        <span>&rarr;</span>
                    </a>
                    <a href="#katalog-eksplorasi" class="inline-flex items-center justify-center gap-1.5 px-5 py-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800/80 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 font-semibold text-xs sm:text-sm transition">
                        <span>🧱 Lihat Semua Motif</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             2. LEVEL 1: REKOMENDASI MOTIF UTAMA TERPILIH
        ══════════════════════════════════════════════════════════════ --}}
        @if($recommendedProducts->count() > 0)
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-200/80 dark:border-slate-800">
                <div>
                    <span class="text-xs font-bold text-terra-600 dark:text-terra-400 uppercase tracking-wider block mb-1">Rekomendasi Kurasi</span>
                    <h2 class="text-xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                        Motif Roster Terbaik untuk {{ $application['title'] }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Dipilih berdasarkan sirkulasi udara optimal, privasi visual, dan kepresisian garis nat dinding.
                    </p>
                </div>
                <a href="#katalog-eksplorasi" class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-terra-600 dark:text-terra-400 hover:underline">
                    <span>Eksplorasi 45+ Motif Lainnya</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($recommendedProducts as $recProduct)
                    <x-product-card :product="$recProduct" wire:key="app-rec-{{ $recProduct->id }}" />
                @endforeach
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════
             3. ARCHITECTURAL DEEP-DIVE & SPESIFIKASI TEKNIS
        ══════════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Left: Deep Narrative & Best Practice -->
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
                    <span class="text-xs font-bold text-terra-600 dark:text-terra-400 uppercase tracking-widest block mb-2">Analisis Desain Arsitektural</span>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mb-4 leading-snug">
                        {{ $application['deep_narrative']['title'] ?? 'Keunggulan Roster Beton IndoRoster' }}
                    </h2>
                    <div class="prose prose-slate dark:prose-invert max-w-none text-xs sm:text-sm leading-relaxed space-y-3.5 text-slate-600 dark:text-slate-300">
                        <p>{{ $application['deep_narrative']['p1'] ?? $application['intro'] }}</p>
                        @if(isset($application['deep_narrative']['p2']))
                        <p>{{ $application['deep_narrative']['p2'] }}</p>
                        @endif
                    </div>
                </div>

                <!-- Installation Guide Steps -->
                @if(isset($application['installation_guide']))
                <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
                    <span class="text-xs font-bold text-terra-600 dark:text-terra-400 uppercase tracking-widest block mb-2">Panduan Konstruksi Lapangan</span>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white mb-5">
                        {{ $application['installation_guide']['title'] }}
                    </h3>
                    <div class="space-y-4">
                        @foreach($application['installation_guide']['steps'] as $step)
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                            <h4 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white mb-1">{{ $step['step'] }}</h4>
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right: Technical Specs & Design Tips Card -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Specs Table -->
                @if(isset($application['specs']))
                <div class="bg-white dark:bg-slate-900 p-6 sm:p-7 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <span>📋</span> Spesifikasi Teknis Material
                    </h3>
                    <div class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                        <div class="py-2.5 flex justify-between gap-3">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Dimensi Modul</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 text-right">{{ $application['specs']['dimensi'] }}</span>
                        </div>
                        <div class="py-2.5 flex justify-between gap-3">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Bobot Keping</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 text-right">{{ $application['specs']['bobot'] }}</span>
                        </div>
                        <div class="py-2.5 flex justify-between gap-3">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Kebutuhan / m²</span>
                            <span class="font-bold text-terra-600 dark:text-terra-400 text-right">{{ $application['specs']['kebutuhan_luas'] }}</span>
                        </div>
                        <div class="py-2.5 flex justify-between gap-3">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Bahan Baku</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 text-right">{{ $application['specs']['komposisi'] }}</span>
                        </div>
                        <div class="py-2.5 flex justify-between gap-3">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Teknologi Cetak</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 text-right">{{ $application['specs']['metode_produksi'] }}</span>
                        </div>
                        <div class="py-2.5 flex justify-between gap-3">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Pilihan Warna</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 text-right">{{ $application['specs']['pilihan_warna'] }}</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Design Tips -->
                @if(isset($application['design_tips']))
                <div class="bg-gradient-to-br from-amber-500/10 via-white to-white dark:from-amber-500/10 dark:via-slate-900 dark:to-slate-900 p-6 sm:p-7 rounded-3xl border border-amber-500/30 shadow-soft-xs">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                        <span>💡</span> Tips Desain & Pencahayaan
                    </h3>
                    <ul class="space-y-2.5 text-xs text-slate-600 dark:text-slate-300">
                        @foreach($application['design_tips'] as $tip)
                        <li class="flex items-start gap-2">
                            <span class="text-amber-500 font-bold shrink-0 mt-0.5">✦</span>
                            <span class="leading-relaxed">{{ $tip }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             4. LEVEL 2: LIVE PRODUCT EXPLORER & SEARCH
        ══════════════════════════════════════════════════════════════ --}}
        <div id="katalog-eksplorasi" class="scroll-mt-24">
            <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                            Eksplorasi Seluruh Katalog Motif Roster
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                            Cari motif berdasarkan nama, model lubang, atau filter kategori bahan.
                        </p>
                    </div>

                    <!-- Search & Filter Controls -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama motif..." class="w-full sm:w-56 px-4 py-2 pl-9 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-terra-500 focus:outline-hidden">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>

                        <select wire:model.live="selectedCategory" class="px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500 focus:outline-hidden">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4 mb-6">
                    @forelse($explorerProducts as $p)
                        <x-product-card :product="$p" wire:key="explorer-p-{{ $p->id }}" />
                    @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-slate-400 text-xs">Tidak ada motif yang sesuai dengan pencarian Anda.</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($explorerProducts->hasPages())
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $explorerProducts->links() }}
                </div>
                @endif
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             5. REAL-WORLD PROJECT GALLERY SHOWCASE
        ══════════════════════════════════════════════════════════════ --}}
        @if(!empty($application['gallery_images']) || $randomGalleryMedia->count() > 0)
        <div class="bg-white dark:bg-slate-900 p-6 sm:p-10 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                        Galeri Realisasi Proyek: {{ $application['title'] }}
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Dokumentasi instalasi nyata hasil terpasang roster beton IndoRoster di berbagai proyek pelanggan.
                    </p>
                </div>
                <a href="{{ route('gallery') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-terra-600 dark:text-terra-400 hover:underline">
                    Lihat Seluruh Galeri &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                @foreach($application['gallery_images'] as $idx => $gImg)
                <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-slate-100 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 group relative cursor-pointer"
                     @click="lightboxOpen = true; lightboxImg = '{{ $gImg }}'; lightboxTitle = 'Realisasi {{ addslashes($application['title']) }}'; lightboxDesc = 'Dokumentasi proyek terpasang roster beton IndoRoster.'">
                    <img src="{{ $gImg }}" alt="{{ $application['title'] }} — Dokumentasi Proyek IndoRoster" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                    <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1">
                        <span>🔍 Perbesar</span>
                    </div>
                </div>
                @endforeach

                @foreach($randomGalleryMedia->take(4) as $gm)
                <div class="rounded-2xl overflow-hidden aspect-[4/3] bg-slate-100 dark:bg-slate-800 border border-slate-200/60 dark:border-slate-700/60 group relative cursor-pointer"
                     @click="lightboxOpen = true; lightboxImg = '{{ $gm->formatted_url }}'; lightboxTitle = '{{ addslashes($gm->title ?? 'Proyek IndoRoster') }}'; lightboxDesc = '{{ addslashes($gm->caption ?? '') }}'">
                    <img src="{{ $gm->formatted_url }}" alt="{{ $gm->title ?? 'Galeri Proyek IndoRoster' }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                    <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-bold gap-1">
                        <span>🔍 Perbesar</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════
             6. SEGMENT SCALE SECTION (#eceran, #borongan, #partai-besar, #kontrak-rutin)
        ══════════════════════════════════════════════════════════════ --}}
        <x-b2b-scale-section segment="kontraktor" highlight-scale="borongan" />

        {{-- ══════════════════════════════════════════════════════════════
             7. AREA LAYANAN / DISTRIBUSI KOTA
        ══════════════════════════════════════════════════════════════ --}}
        @if($topLocations->count() > 0)
        <div class="bg-white dark:bg-slate-900 p-6 sm:p-10 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                        Jangkauan Pengiriman Roster untuk {{ $application['title'] }}
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Armada truk pabrik IndoRoster melayani pengiriman langsung ke lokasi proyek di kota-kota berikut:
                    </p>
                </div>
                <a href="{{ route('location.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-terra-600 dark:text-terra-400 hover:underline whitespace-nowrap">
                    Lihat Semua 25+ Kota Area Layanan &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                @foreach($topLocations as $loc)
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

        {{-- ══════════════════════════════════════════════════════════════
             8. COMPREHENSIVE FAQS SECTION
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-slate-900 p-6 sm:p-10 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
            <h3 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white mb-6">
                Pertanyaan yang Sering Diajukan Seputar {{ $application['title'] }}
            </h3>
            <div class="space-y-4">
                @foreach($application['faqs'] as $faq)
                <div class="p-5 rounded-2xl bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/70">
                    <h4 class="font-bold text-slate-900 dark:text-white text-sm sm:text-base mb-2">{{ $faq['q'] }}</h4>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">{{ $faq['a'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             9. BOTTOM CALL-TO-ACTION BANNER
        ══════════════════════════════════════════════════════════════ --}}
        <div class="rounded-3xl bg-gradient-to-r from-slate-900 via-slate-850 to-slate-900 text-white p-8 sm:p-12 border border-slate-800 shadow-2xl flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="max-w-2xl">
                <span class="text-xs font-bold text-terra-400 uppercase tracking-widest block mb-2">Konsultasi Desain & Pengadaan</span>
                <h3 class="text-2xl sm:text-3xl font-black mb-3">Siap Mewujudkan {{ $application['title'] }} Impian Anda?</h3>
                <p class="text-sm text-slate-300 leading-relaxed mb-0">
                    Diskusikan kebutuhan motif, hitungan volume dinding, jadwal pengiriman armada, serta dapatkan harga langsung pabrik Plered dengan jaminan 100% bebas pecah.
                </p>
            </div>
            <div class="flex flex-wrap gap-3 shrink-0">
                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="px-7 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-lg shadow-emerald-600/25 transition-all hover:scale-105 flex items-center gap-2">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    <span>Hubungi Sales via WhatsApp</span>
                </a>
                <a href="{{ route('tools.calculator') }}" class="px-6 py-3.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-sm backdrop-blur-md transition-all border border-white/20">
                    🧮 Kalkulator Dinding
                </a>
            </div>
        </div>

    </div>

    {{-- Lightbox Modal --}}
    <div x-show="lightboxOpen" x-cloak class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4" @click.self="lightboxOpen = false">
        <div class="relative max-w-4xl w-full bg-slate-900 rounded-2xl overflow-hidden border border-slate-800 text-white">
            <button @click="lightboxOpen = false" class="absolute top-4 right-4 z-10 w-9 h-9 rounded-full bg-black/60 text-white flex items-center justify-center hover:bg-black transition text-lg">&times;</button>
            <div class="aspect-video bg-black flex items-center justify-center overflow-hidden">
                <img :src="lightboxImg" :alt="lightboxTitle" class="w-full h-full object-contain">
            </div>
            <div class="p-4 sm:p-6 bg-slate-900">
                <h4 class="font-bold text-base text-white mb-1" x-text="lightboxTitle"></h4>
                <p class="text-xs text-slate-400" x-text="lightboxDesc"></p>
            </div>
        </div>
    </div>
</div>
