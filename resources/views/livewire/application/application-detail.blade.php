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

    $rawFaqs = is_array($application['faqs'] ?? null) ? $application['faqs'] : [];
    $validFaqs = array_values(array_filter($rawFaqs, fn ($item) => is_array($item) && !empty($item['q'] ?? ($item['question'] ?? null))));
    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(function ($item) {
            return [
                '@type' => 'Question',
                'name' => $item['q'] ?? ($item['question'] ?? ''),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $item['a'] ?? ($item['answer'] ?? ''),
                ],
            ];
        }, $validFaqs),
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

<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-8 sm:py-12" 
     x-data="{ 
         lightboxOpen: false, 
         activeIndex: 0, 
         zoomScale: 1, 
         items: {{ Js::from($lightboxItems) }},
         openLightbox(idx) {
             this.activeIndex = idx;
             this.zoomScale = 1;
             this.lightboxOpen = true;
             document.body.style.overflow = 'hidden';
         },
         closeLightbox() {
             this.lightboxOpen = false;
             this.zoomScale = 1;
             document.body.style.overflow = '';
         },
         nextItem() {
             if (this.items && this.items.length > 0) {
                 this.activeIndex = (this.activeIndex + 1) % this.items.length;
                 this.zoomScale = 1;
             }
         },
         prevItem() {
             if (this.items && this.items.length > 0) {
                 this.activeIndex = (this.activeIndex - 1 + this.items.length) % this.items.length;
                 this.zoomScale = 1;
             }
         },
         zoomIn() {
             if (this.zoomScale < 3.5) this.zoomScale = parseFloat((this.zoomScale + 0.4).toFixed(1));
         },
         zoomOut() {
             if (this.zoomScale > 0.8) this.zoomScale = parseFloat((this.zoomScale - 0.4).toFixed(1));
         },
         resetZoom() {
             this.zoomScale = 1;
         },
         toggleZoom() {
             this.zoomScale = this.zoomScale > 1.1 ? 1 : 2.2;
         }
     }">
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
             2. DOKUMENTASI PENGAPLIKASIAN NYATA (SEBELUM KATALOG PRODUK)
             Layout: 2 Foto ke Samping, Sisanya Mengalir ke Bawah, Resolusi Jernih & Jelas
             Mendukung Foto dari Galeri Pabrik (Dengan Keranjang & Non-Keranjang)
        ══════════════════════════════════════════════════════════════ --}}
        @php
            $galleryItems = $application['gallery_images'] ?? [];
            $hasGalleries = ($selectedGalleries && $selectedGalleries->isNotEmpty()) || !empty($galleryItems) || $randomGalleryMedia->count() > 0;
            $totalPhotoCount = ($selectedGalleries && $selectedGalleries->isNotEmpty()) 
                ? $selectedGalleries->count() 
                : (!empty($galleryItems) ? count($galleryItems) : $randomGalleryMedia->count());
        @endphp

        @if($hasGalleries)
        <div class="bg-white dark:bg-slate-900 p-6 sm:p-10 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-terra-50 dark:bg-terra-950/60 border border-terra-200 dark:border-terra-800 text-terra-700 dark:text-terra-300 text-xs font-bold uppercase tracking-wider mb-2">
                        📸 Inspirasi Hasil Pemasangan
                    </span>
                    <h2 class="text-xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                        Dokumentasi Pengaplikasian: {{ $application['title'] }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-2xl leading-relaxed">
                        Lihat contoh nyata hasil terpasang roster beton pada desain {{ strtolower($application['title']) }}. Foto yang bertanda keranjang dapat langsung Anda beli motifnya. Klik foto untuk melihat resolusi tinggi.
                    </p>
                </div>
                <div class="shrink-0 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold">
                        <span>🖼️</span> {{ $totalPhotoCount }} Foto Dokumentasi
                    </span>
                </div>
            </div>

            {{-- Grid 2 Kolom ke Samping (2 ke samping, sisanya mengalir ke bawah) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                @if($selectedGalleries && $selectedGalleries->isNotEmpty())
                    @foreach($selectedGalleries as $idx => $gal)
                    @php
                        $firstMedia = $gal->media->first();
                        $photoUrl = $firstMedia?->formatted_url ?? $firstMedia?->media_url;
                        if (!$photoUrl) {
                            $photoUrl = 'https://images.pexels.com/photos/7946866/pexels-photo-7946866.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940';
                        }
                        $prod = $gal->product;
                    @endphp
                    <div class="group relative rounded-3xl overflow-hidden bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-soft-sm hover:shadow-soft-xl hover:border-terra-400/80 dark:hover:border-terra-500 transition-all duration-300 flex flex-col justify-between">
                        
                        {{-- Bagian Foto (Klik untuk perbesar HD Theatre Modal) --}}
                        <div class="aspect-[16/10] overflow-hidden relative bg-slate-950 cursor-pointer"
                             @click="openLightbox({{ $idx }})">
                            <img src="{{ $photoUrl }}" 
                                 alt="{{ $gal->title }}" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" 
                                 loading="lazy">
                            
                            {{-- Badges di Atas Foto --}}
                            <div class="absolute top-3.5 left-3.5 flex items-center gap-2 z-10 flex-wrap">
                                <span class="bg-black/60 backdrop-blur-md text-white text-[11px] font-bold px-3 py-1 rounded-full border border-white/20 shadow-md">
                                    📸 Inspirasi #{{ $loop->iteration }}
                                </span>
                                @if($prod)
                                    <span class="bg-emerald-600/90 backdrop-blur-md text-white text-[11px] font-bold px-3 py-1 rounded-full border border-emerald-400/30 shadow-md flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                        Ada Keranjang Produk
                                    </span>
                                @else
                                    <span class="bg-slate-800/80 backdrop-blur-md text-slate-200 text-[11px] font-medium px-2.5 py-1 rounded-full border border-white/10 shadow-md">
                                        Inspirasi Proyek
                                    </span>
                                @endif
                            </div>

                            {{-- Overlay Hover Perbesar --}}
                            <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <span class="px-4 py-2 rounded-xl bg-white/95 dark:bg-slate-900/95 text-slate-900 dark:text-white font-bold text-xs shadow-lg backdrop-blur-md flex items-center gap-1.5 transform translate-y-2 group-hover:translate-y-0 transition-transform">
                                    <span>🔍</span> Perbesar Foto HD
                                </span>
                            </div>
                        </div>

                        {{-- Bagian Info & Keranjang --}}
                        <div class="p-5 flex flex-col flex-grow justify-between bg-white dark:bg-slate-900">
                            <div>
                                <h3 class="font-display font-bold text-base text-slate-900 dark:text-white mb-1 leading-snug">
                                    {{ $gal->title }}
                                </h3>
                                @if($gal->description)
                                <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed line-clamp-2 mb-3">
                                    {{ $gal->description }}
                                </p>
                                @endif
                            </div>

                            @if($prod)
                                {{-- KOTAK PRODUK DENGAN TOMBOL KERANJANG --}}
                                <div class="mt-2 p-3 bg-slate-50 dark:bg-slate-800/70 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <img src="{{ $prod->primary_image }}" alt="{{ $prod->name }}" class="w-12 h-12 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shrink-0 bg-white" onerror="this.onerror=null; this.src='{{ asset('assets/logo_indoroster_no_text.PNG') }}';">
                                        <div class="min-w-0">
                                            <div class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Motif Terkait:</div>
                                            <div class="font-bold text-xs text-slate-900 dark:text-white truncate">{{ $prod->name }}</div>
                                            <div class="text-xs font-black text-[#ee4d2d] dark:text-terra-400 mt-0.5">{{ $prod->formatted_price_range }}</div>
                                        </div>
                                    </div>
                                    <a href="{{ route('product.detail', $prod->slug) }}" class="w-full sm:w-auto px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-1.5 shrink-0 whitespace-nowrap">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                        <span>🛒 Masukkan Keranjang</span>
                                    </a>
                                </div>
                            @else
                                {{-- NON-KERANJANG FOOTER --}}
                                <div class="mt-2 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-400">
                                    <span class="flex items-center gap-1 text-[11px]">
                                        <span>📍</span> {{ $gal->location ?: 'Proyek Hunian Modern' }}
                                    </span>
                                    <span class="text-terra-600 dark:text-terra-400 font-bold text-xs">
                                        Inspirasi Arsitektur
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @elseif(!empty($galleryItems))
                    {{-- Fallback: gallery_images (Pexels / Custom URLs) --}}
                    @foreach($galleryItems as $idx => $gImg)
                    @php
                        $rawPhoto = is_array($gImg) ? ($gImg['image_url'] ?? ($gImg['url'] ?? '')) : $gImg;
                        $photoUrl = str_starts_with((string)$rawPhoto, 'http') ? $rawPhoto : asset('storage/' . $rawPhoto);
                    @endphp
                    <div class="group relative rounded-3xl overflow-hidden bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-soft-sm hover:shadow-soft-xl hover:border-terra-400/80 dark:hover:border-terra-500 transition-all duration-300 cursor-pointer flex flex-col"
                         @click="openLightbox({{ $idx }})">
                        
                        <div class="aspect-[16/10] sm:aspect-[16/10] overflow-hidden relative bg-slate-900">
                            <img src="{{ $photoUrl }}" 
                                 alt="{{ $application['title'] }} — Dokumentasi Proyek #{{ $idx + 1 }}" 
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" 
                                 loading="lazy">
                            
                            <div class="absolute top-3.5 left-3.5 z-10">
                                <span class="bg-black/60 backdrop-blur-md text-white text-[11px] font-bold px-3 py-1 rounded-full border border-white/20 shadow-md">
                                    📸 Inspirasi #{{ $idx + 1 }}
                                </span>
                            </div>

                            <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                <span class="px-4 py-2 rounded-xl bg-white/95 dark:bg-slate-900/95 text-slate-900 dark:text-white font-bold text-xs shadow-lg backdrop-blur-md flex items-center gap-1.5 transform translate-y-2 group-hover:translate-y-0 transition-transform">
                                    <span>🔍</span> Perbesar Foto HD
                                </span>
                            </div>
                        </div>

                        <div class="p-4 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs">
                            <span class="font-bold text-slate-800 dark:text-slate-200 truncate">
                                Aplikasi {{ $application['badge'] ?? $application['title'] }}
                            </span>
                            <span class="text-terra-600 dark:text-terra-400 font-semibold flex items-center gap-1 shrink-0">
                                <span>Lihat Detail</span> &rarr;
                            </span>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════
             3. LEVEL 1: REKOMENDASI MOTIF UTAMA & BELI / KERANJANG
        ══════════════════════════════════════════════════════════════ --}}
        @if($recommendedProducts->count() > 0)
        <div class="bg-white dark:bg-slate-900 p-6 sm:p-10 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-200/80 dark:border-slate-800">
                <div>
                    <span class="text-xs font-bold text-terra-600 dark:text-terra-400 uppercase tracking-wider block mb-1">Pilihan Produk Sesuai Desain</span>
                    <h2 class="text-xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                        Motif Roster Terbaik untuk {{ $application['title'] }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Pilih motif yang Anda sukai dari foto di atas, pesan langsung dari pabrik dengan jaminan presisi cetak plat baja.
                    </p>
                </div>
                <a href="#katalog-eksplorasi" class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-terra-600 dark:text-terra-400 hover:underline">
                    <span>Eksplorasi 45+ Motif Lainnya</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($recommendedProducts as $recProduct)
                    <div class="flex flex-col h-full group/card">
                        <x-product-card :product="$recProduct" wire:key="app-rec-{{ $recProduct->id }}" class="flex-grow" />
                        <a href="{{ route('product.detail', $recProduct->slug) }}" class="mt-2.5 w-full py-2.5 px-3 bg-terra-500 hover:bg-terra-600 active:scale-[0.98] text-white text-xs font-bold rounded-xl flex items-center justify-center gap-2 shadow-sm hover:shadow transition-all text-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Pesan / Masukkan Keranjang</span>
                        </a>
                    </div>
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
                @if(isset($application['installation_guide']) && is_array($application['installation_guide']) && !empty($application['installation_guide']['steps']) && is_array($application['installation_guide']['steps']))
                <div class="bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
                    <span class="text-xs font-bold text-terra-600 dark:text-terra-400 uppercase tracking-widest block mb-2">Panduan Konstruksi Lapangan</span>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white mb-5">
                        {{ $application['installation_guide']['title'] ?? 'Panduan Teknis Pemasangan' }}
                    </h3>
                    <div class="space-y-4">
                        @foreach($application['installation_guide']['steps'] as $step)
                        @if(is_array($step))
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800">
                            <h4 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white mb-1">{{ $step['step'] ?? '' }}</h4>
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">{{ $step['desc'] ?? '' }}</p>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right: Technical Specs & Design Tips Card -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Specs Table -->
                @if(isset($application['specs']) && is_array($application['specs']))
                <div class="bg-white dark:bg-slate-900 p-6 sm:p-7 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <span>📋</span> Spesifikasi Teknis Material
                    </h3>
                    <div class="divide-y divide-slate-100 dark:divide-slate-800 text-xs">
                        <div class="py-2.5 flex justify-between gap-3">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Dimensi Modul</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 text-right">{{ $application['specs']['dimensi'] ?? '20 × 20 × 10 cm' }}</span>
                        </div>
                        <div class="py-2.5 flex justify-between gap-3">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Bobot Keping</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 text-right">{{ $application['specs']['bobot'] ?? '3.8 – 4.2 kg / keping' }}</span>
                        </div>
                        <div class="py-2.5 flex justify-between gap-3">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Kebutuhan / m²</span>
                            <span class="font-bold text-terra-600 dark:text-terra-400 text-right">{{ $application['specs']['kebutuhan_luas'] ?? '25 keping / m²' }}</span>
                        </div>
                        <div class="py-2.5 flex justify-between gap-3">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Bahan Baku</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 text-right">{{ $application['specs']['komposisi'] ?? 'Pasir Abu Batu Murni Pilihan' }}</span>
                        </div>
                        <div class="py-2.5 flex justify-between gap-3">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Teknologi Cetak</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 text-right">{{ $application['specs']['metode_produksi'] ?? 'Cetak Tumbuk Padat Plat Baja Siku 90°' }}</span>
                        </div>
                        <div class="py-2.5 flex justify-between gap-3">
                            <span class="text-slate-500 dark:text-slate-400 font-medium">Pilihan Warna</span>
                            <span class="font-bold text-slate-800 dark:text-slate-200 text-right">{{ $application['specs']['pilihan_warna'] ?? 'Abu Natural, Putih, Terakota' }}</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Design Tips -->
                @if(isset($application['design_tips']) && is_array($application['design_tips']) && !empty($application['design_tips']))
                <div class="bg-gradient-to-br from-amber-500/10 via-white to-white dark:from-amber-500/10 dark:via-slate-900 dark:to-slate-900 p-6 sm:p-7 rounded-3xl border border-amber-500/30 shadow-soft-xs">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-3 flex items-center gap-2">
                        <span>💡</span> Tips Desain & Pencahayaan
                    </h3>
                    <ul class="space-y-2.5 text-xs text-slate-600 dark:text-slate-300">
                        @foreach($application['design_tips'] as $tip)
                        @if(is_string($tip))
                        <li class="flex items-start gap-2">
                            <span class="text-amber-500 font-bold shrink-0 mt-0.5">✦</span>
                            <span class="leading-relaxed">{{ $tip }}</span>
                        </li>
                        @endif
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
                        <div class="flex flex-col h-full group/card">
                            <x-product-card :product="$p" wire:key="explorer-p-{{ $p->id }}" class="flex-grow" />
                            <a href="{{ route('product.detail', $p->slug) }}" class="mt-2 w-full py-2 px-2 bg-slate-100 hover:bg-terra-500 text-slate-700 hover:text-white dark:bg-slate-800 dark:hover:bg-terra-500 dark:text-slate-300 dark:hover:text-white text-[11px] font-bold rounded-lg flex items-center justify-center gap-1.5 transition-all text-center">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span>🛒 Pesan</span>
                            </a>
                        </div>
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
        @if(!empty($validFaqs))
        <div class="bg-white dark:bg-slate-900 p-6 sm:p-10 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
            <h3 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white mb-6">
                Pertanyaan yang Sering Diajukan Seputar {{ $application['title'] ?? 'Roster Beton' }}
            </h3>
            <div class="space-y-4">
                @foreach($validFaqs as $faq)
                <div class="p-5 rounded-2xl bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/70">
                    <h4 class="font-bold text-slate-900 dark:text-white text-sm sm:text-base mb-2">{{ $faq['q'] ?? ($faq['question'] ?? '') }}</h4>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">{{ $faq['a'] ?? ($faq['answer'] ?? '') }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

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

    {{-- ══════════════════════════════════════════════════════════════
         FULLSCREEN THEATRE MODAL (MATCHING FOTO 3 / FACEBOOK MARKETPLACE STYLE)
         Mendukung: Penuh di Layar, Zoom Tool, Split Layout Desktop,
         Mobile Smooth Scrollable, dan WAJIB TAMPIL KERANJANG BELI
    ══════════════════════════════════════════════════════════════ --}}
    <div 
        x-show="lightboxOpen" 
        x-cloak 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-2xl flex flex-col overflow-hidden" 
        @keydown.escape.window="closeLightbox()"
        @keydown.arrow-left.window="if (lightboxOpen) prevItem()"
        @keydown.arrow-right.window="if (lightboxOpen) nextItem()"
        style="display: none;">

        {{-- Top Bar Navigation / Header --}}
        <div class="h-14 sm:h-16 px-4 sm:px-6 bg-slate-950/90 border-b border-white/10 flex items-center justify-between z-30 shrink-0 backdrop-blur-md">
            {{-- Brand / Info Tag --}}
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-terra-500 flex items-center justify-center text-white font-black text-xs shadow-md shrink-0">
                    INDO
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold text-white uppercase tracking-wider truncate">INDOROSTER DOKUMENTASI PROYEK</span>
                        <span class="hidden sm:inline-block text-[10px] px-2 py-0.5 rounded-md bg-white/10 text-slate-300 font-semibold uppercase" x-text="items[activeIndex]?.category"></span>
                    </div>
                    <div class="text-[11px] text-slate-400 truncate" x-text="items[activeIndex]?.location"></div>
                </div>
            </div>

            {{-- Photo Counter & Actions --}}
            <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                <span class="text-xs text-slate-400 font-mono font-medium px-2.5 py-1 rounded-full bg-white/5 border border-white/10">
                    <span class="text-white font-bold" x-text="(activeIndex + 1)"></span> / <span x-text="items.length"></span>
                </span>

                {{-- Close Button --}}
                <button 
                    @click="closeLightbox()" 
                    class="p-2 rounded-full bg-white/10 hover:bg-white/20 text-white transition-all active:scale-95 border border-white/10 shadow-lg cursor-pointer"
                    title="Tutup (Esc)">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>

        {{-- Main Stage: Split Layout Desktop & Vertical Scrollable Mobile --}}
        <div class="flex-1 w-full flex flex-col md:flex-row overflow-y-auto md:overflow-hidden relative">

            {{-- LEFT SIDE: PHOTO VIEWER (EXPANSIVE THEATRE) --}}
            <div class="w-full md:flex-1 min-h-[50vh] sm:min-h-[60vh] md:h-full flex items-center justify-center relative bg-black/40 p-3 sm:p-6 overflow-hidden shrink-0 select-none">
                
                {{-- Zoom Controls Toolbar (Top-Left) --}}
                <div class="absolute top-4 left-4 z-20 flex items-center gap-1.5 bg-slate-900/90 backdrop-blur-md px-3 py-1.5 rounded-2xl border border-white/15 shadow-xl">
                    <button 
                        @click.stop="zoomIn()" 
                        class="p-1.5 rounded-xl hover:bg-white/10 text-white/80 hover:text-white transition-colors cursor-pointer" 
                        title="Perbesar (+)">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                    </button>
                    <button 
                        @click.stop="zoomOut()" 
                        class="p-1.5 rounded-xl hover:bg-white/10 text-white/80 hover:text-white transition-colors cursor-pointer" 
                        title="Perkecil (-)">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"/></svg>
                    </button>
                    <button 
                        @click.stop="resetZoom()" 
                        class="px-2 py-1 rounded-xl hover:bg-white/10 text-[11px] font-bold text-white/90 transition-colors cursor-pointer" 
                        title="Reset 100%">
                        <span x-text="Math.round(zoomScale * 100) + '%'"></span>
                    </button>
                    <span class="text-[10px] text-white/40 border-l border-white/10 pl-2 ml-1 hidden sm:inline">Klik 2x / Scroll</span>
                </div>

                {{-- Previous Button --}}
                <button 
                    @click.stop="prevItem()" 
                    class="absolute left-3 sm:left-6 z-20 p-3 sm:p-3.5 bg-black/50 hover:bg-black/80 backdrop-blur-md rounded-full text-white transition-all active:scale-95 border border-white/10 shadow-2xl cursor-pointer"
                    title="Foto Sebelumnya (&larr;)">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                </button>

                {{-- Photo with Double-Click & Scale Transform --}}
                <div class="w-full h-full flex items-center justify-center overflow-hidden" @dblclick="toggleZoom()">
                    <img 
                        :src="items[activeIndex]?.image" 
                        :alt="items[activeIndex]?.title" 
                        :style="'transform: scale(' + zoomScale + '); transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1); transform-origin: center center;'"
                        class="max-h-[85vh] max-w-full object-contain rounded-xl shadow-2xl transition-transform cursor-zoom-in">
                </div>

                {{-- Next Button --}}
                <button 
                    @click.stop="nextItem()" 
                    class="absolute right-3 sm:right-6 z-20 p-3 sm:p-3.5 bg-black/50 hover:bg-black/80 backdrop-blur-md rounded-full text-white transition-all active:scale-95 border border-white/10 shadow-2xl cursor-pointer"
                    title="Foto Berikutnya (&rarr;)">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>

            {{-- RIGHT SIDE: DETAILS & SHOPPABLE CART PANEL (SCROLLABLE) --}}
            <div class="w-full md:w-[400px] lg:w-[440px] md:h-full bg-slate-900 border-t md:border-t-0 md:border-l border-white/10 text-white flex flex-col shrink-0 md:overflow-hidden z-20">
                
                {{-- Scrollable Container Inside Right Panel --}}
                <div class="p-5 sm:p-7 md:overflow-y-auto space-y-6 flex-1">
                    
                    {{-- Title & Meta --}}
                    <div>
                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-terra-500/20 text-terra-300 text-[10px] font-bold uppercase tracking-wider border border-terra-500/30" x-text="'📸 ' + (items[activeIndex]?.category || 'Inspirasi')"></span>
                            <span class="inline-flex items-center gap-1 text-[11px] text-slate-400">
                                <span>📍</span> <span x-text="items[activeIndex]?.location || 'Indonesia'"></span>
                            </span>
                        </div>
                        <h3 class="font-display text-lg sm:text-xl font-black text-white leading-tight" x-text="items[activeIndex]?.title"></h3>
                    </div>

                    {{-- Description Narrative --}}
                    <div class="text-xs sm:text-sm text-slate-300 leading-relaxed whitespace-pre-line bg-white/5 p-4 rounded-2xl border border-white/5">
                        <p x-text="items[activeIndex]?.description"></p>
                    </div>

                    {{-- ══════════════════════════════════════════════════════════
                         KARTU PRODUK DENGAN KERANJANG (WAJIB TAMPIL JIKA ADA PRODUK)
                    ══════════════════════════════════════════════════════════ --}}
                    <template x-if="items[activeIndex]?.has_product">
                        <div class="rounded-2xl bg-gradient-to-b from-slate-800 to-slate-850 p-4 border border-emerald-500/40 shadow-xl space-y-3.5 relative overflow-hidden">
                            <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

                            {{-- Product Header Tag --}}
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-bold text-emerald-400 uppercase tracking-wider flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    Motif Terpasang Pada Foto Ini
                                </span>
                                <span class="text-slate-400 text-[10px]">Langsung Pabrik</span>
                            </div>

                            {{-- Product Details Row --}}
                            <div class="flex items-center gap-3.5 bg-black/30 p-3 rounded-xl border border-white/10">
                                <img :src="items[activeIndex]?.product_image" :alt="items[activeIndex]?.product_name" class="w-14 h-14 rounded-xl object-cover border border-white/15 bg-white shrink-0">
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-bold text-xs sm:text-sm text-white truncate" x-text="items[activeIndex]?.product_name"></h4>
                                    <div class="text-sm font-black text-terra-400 mt-1" x-text="items[activeIndex]?.product_price"></div>
                                    <div class="text-[10px] text-emerald-400 flex items-center gap-1 mt-0.5 font-medium">
                                        <span>✓</span> Siap Kirim • Abu Batu Murni
                                    </div>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="space-y-2 pt-1">
                                <a :href="items[activeIndex]?.product_url" class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-500 active:scale-98 text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                    <span>🛒 Masukkan Keranjang / Beli</span>
                                </a>
                                <a :href="items[activeIndex]?.wa_link" target="_blank" rel="noopener noreferrer" class="w-full py-2.5 px-4 bg-white/10 hover:bg-white/15 text-white font-semibold text-xs rounded-xl border border-white/10 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4 fill-emerald-400" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                    <span>Konsultasi Stok via WhatsApp</span>
                                </a>
                            </div>
                        </div>
                    </template>

                    {{-- Technical Specifications Card --}}
                    <div class="rounded-2xl bg-white/5 p-4 border border-white/5 space-y-2.5">
                        <div class="text-[11px] font-bold text-slate-300 uppercase tracking-wider">
                            ⚙️ Keunggulan Roster IndoRoster
                        </div>
                        <ul class="text-xs text-slate-300 space-y-1.5">
                            <li class="flex items-start gap-2">
                                <span class="text-emerald-400 font-bold shrink-0">✓</span>
                                <span>Cetak tumbuk padat plat baja presisi siku 90° (Sentra Plered).</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-emerald-400 font-bold shrink-0">✓</span>
                                <span>Pasir abu batu murni pilihan (bebas pasir silika / limbah).</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-emerald-400 font-bold shrink-0">✓</span>
                                <span>Garansi 100% ganti baru jika terdapat keping pecah di perjalanan.</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Mobile Navigation Helper Buttons --}}
                    <div class="flex md:hidden items-center justify-between gap-3 pt-2">
                        <button 
                            @click="prevItem()" 
                            class="flex-1 py-2.5 px-4 rounded-xl bg-white/10 hover:bg-white/15 text-white font-bold text-xs flex items-center justify-center gap-1.5 transition">
                            <span>&larr; Foto Sebelumnya</span>
                        </button>
                        <button 
                            @click="nextItem()" 
                            class="flex-1 py-2.5 px-4 rounded-xl bg-terra-600 hover:bg-terra-500 text-white font-bold text-xs flex items-center justify-center gap-1.5 transition">
                            <span>Foto Berikutnya &rarr;</span>
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
