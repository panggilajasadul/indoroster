@php
    $isEn = ($country['lang'] ?? 'en') === 'en';
    $countryName = $country['name'] ?? ucfirst($countrySlug);
    $flag = $country['flag'] ?? '🌐';
    $port = $country['port_name'] ?? 'Designated Container Port';
    $transit = $country['transit_time'] ?? '14 – 28 Days Sea Freight';
    $sections = $country['sections_config'] ?? [];

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => $isEn ? 'Home' : 'Utama',
                'item' => route('home'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $isEn ? 'International Export' : 'Eksport Antarabangsa',
                'item' => url('/export'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $countryName,
                'item' => url('/export/' . $countrySlug),
            ],
        ],
    ];

    $faqs = $country['faqs'] ?? [];
    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(function ($f) {
            return [
                '@type' => 'Question',
                'name' => $f['q'] ?? '',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $f['a'] ?? '',
                ],
            ];
        }, $faqs),
    ];

    $serviceSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => $country['headline'] ?? ("Breeze Blocks Supplier " . $countryName),
        'serviceType' => 'Export Supply of Architectural Breeze Blocks and Screen Blocks',
        'provider' => [
            '@type' => 'Organization',
            'name' => 'IndoRoster',
            'url' => route('home'),
            'logo' => asset('assets/logo_indoroster_no_text.PNG'),
        ],
        'areaServed' => [
            '@type' => 'Country',
            'name' => $countryName,
        ],
        'description' => $country['subheadline'] ?? '',
    ];

    if (!function_exists('getThemeClasses')) {
        function getThemeClasses($theme = 'clean_light') {
            return match($theme) {
                'dark_charcoal' => 'bg-slate-900 text-white border border-slate-800 shadow-2xl',
                'clean_light' => 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200/80 dark:border-slate-800 shadow-soft-xs',
                'soft_slate' => 'bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-800 shadow-soft-xs',
                'warm_terracotta' => 'bg-orange-50/70 dark:bg-orange-950/20 text-slate-900 dark:text-white border border-orange-200/70 dark:border-orange-900/40 shadow-soft-xs',
                'emerald_trust' => 'bg-emerald-50/70 dark:bg-emerald-950/20 text-slate-900 dark:text-white border border-emerald-200/70 dark:border-emerald-900/40 shadow-soft-xs',
                'alert_red' => 'bg-red-50/70 dark:bg-red-950/20 text-slate-900 dark:text-white border border-red-200/80 dark:border-red-900/40 shadow-soft-xs',
                default => 'bg-white dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200/80 dark:border-slate-800 shadow-soft-xs',
            };
        }
    }

    if (!function_exists('getMediaAspectClass')) {
        function getMediaAspectClass(?string $aspect, string $default = 'landscape'): string {
            $aspect = $aspect ?: $default;
            return match ($aspect) {
                'portrait' => 'aspect-[9/16] sm:aspect-[3/4] max-h-[580px] max-w-sm mx-auto shadow-xl',
                'square' => 'aspect-square max-h-[480px] max-w-md mx-auto shadow-xl',
                'auto' => 'max-h-[580px] w-auto max-w-full mx-auto shadow-xl',
                default => 'aspect-video w-full max-w-4xl mx-auto shadow-xl',
            };
        }
    }

    if (!function_exists('getStepMediaAspectClass')) {
        function getStepMediaAspectClass(?string $aspect): string {
            return match ($aspect) {
                'portrait' => 'aspect-[3/4] max-h-72 w-full',
                'auto' => 'aspect-video sm:aspect-4/3 w-full bg-slate-950/80',
                default => 'aspect-video w-full',
            };
        }
    }
@endphp

@push('seo')
    <link rel="alternate" hreflang="en-GB" href="https://indoroster.com/export/uk" />
    <link rel="alternate" hreflang="en-US" href="https://indoroster.com/export/usa" />
    <link rel="alternate" hreflang="en-AU" href="https://indoroster.com/export/australia" />
    <link rel="alternate" hreflang="en-SG" href="https://indoroster.com/export/singapore" />
    <link rel="alternate" hreflang="ms-MY" href="https://indoroster.com/export/malaysia" />
    <link rel="alternate" hreflang="ms-BN" href="https://indoroster.com/export/brunei" />
    <link rel="alternate" hreflang="id-ID" href="https://indoroster.com/" />
    <link rel="alternate" hreflang="x-default" href="https://indoroster.com/export" />

    <script type="application/ld+json">
    {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($serviceSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

<div class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 py-10 sm:py-16 selection:bg-terra-500 selection:text-white transition-colors duration-300"
     x-data="{
         lightboxOpen: false,
         lightboxImg: '',
         lightboxTitle: '',
         lightboxLocation: '',
         lightboxDesc: '',
         lightboxPattern: '',
         zoomLevel: 1,
         panX: 0,
         panY: 0,
         isDragging: false,
         startX: 0,
         startY: 0,
         openLightbox(img, title, loc, desc, pattern) {
             this.lightboxOpen = true;
             this.lightboxImg = img;
             this.lightboxTitle = title;
             this.lightboxLocation = loc;
             this.lightboxDesc = desc;
             this.lightboxPattern = pattern;
             this.resetZoom();
         },
         resetZoom() {
             this.zoomLevel = 1;
             this.panX = 0;
             this.panY = 0;
             this.isDragging = false;
         },
         zoomIn() {
             this.zoomLevel = Math.min(Math.round((this.zoomLevel + 0.5) * 10) / 10, 4);
         },
         zoomOut() {
             this.zoomLevel = Math.max(Math.round((this.zoomLevel - 0.5) * 10) / 10, 1);
             if (this.zoomLevel <= 1) {
                 this.panX = 0;
                 this.panY = 0;
             }
         },
         startDrag(e) {
             if (this.zoomLevel <= 1) return;
             this.isDragging = true;
             const clientX = e.touches ? e.touches[0].clientX : e.clientX;
             const clientY = e.touches ? e.touches[0].clientY : e.clientY;
             this.startX = clientX - this.panX;
             this.startY = clientY - this.panY;
         },
         onDrag(e) {
             if (!this.isDragging || this.zoomLevel <= 1) return;
             if (e.cancelable) e.preventDefault();
             const clientX = e.touches ? e.touches[0].clientX : e.clientX;
             const clientY = e.touches ? e.touches[0].clientY : e.clientY;
             this.panX = clientX - this.startX;
             this.panY = clientY - this.startY;
         },
         endDrag() {
             this.isDragging = false;
         },
         handleWheel(e) {
             if (e.deltaY < 0) {
                 this.zoomIn();
             } else {
                 this.zoomOut();
             }
         },
         toggleDoubleZoom() {
             if (this.zoomLevel === 1) {
                 this.zoomLevel = 2;
             } else {
                 this.resetZoom();
             }
         }
     }">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16 sm:space-y-24">
        
        @foreach($sections as $block)
            @php
                $type = $block['type'] ?? '';
                $data = $block['data'] ?? [];
                $themeClass = getThemeClasses($data['bg_theme'] ?? 'clean_light');
            @endphp

            {{-- 1. HERO BANNER BLOCK --}}
            @if($type === 'hero_banner')
                <div class="relative rounded-3xl overflow-hidden {{ $themeClass }} p-8 sm:p-12 lg:p-16">
                    <div class="absolute -right-20 -top-20 w-96 h-96 bg-terra-500/15 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="relative z-10 max-w-4xl">
                        <nav class="flex items-center gap-2 text-xs text-slate-400 mb-6 font-medium">
                            <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                            <span>/</span>
                            <a href="{{ url('/export') }}" class="hover:text-white transition">Export Hub</a>
                            <span>/</span>
                            <span class="text-terra-400 font-bold">{{ $countryName }} {{ $flag }}</span>
                        </nav>

                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-800/90 text-terra-400 border border-slate-700 text-xs font-bold uppercase tracking-wider mb-6">
                            <span class="w-2 h-2 rounded-full bg-terra-500 animate-pulse"></span>
                            {{ $data['badge'] ?? 'Direct Factory Exporter — ASEAN Sea Freight' }}
                        </div>

                        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight mb-6 text-white">
                            {{ $data['headline'] ?? $country['headline'] }}
                        </h1>

                        <p class="text-base sm:text-lg text-slate-300 mb-8 leading-relaxed max-w-3xl">
                            {{ $data['subheadline'] ?? $country['subheadline'] }}
                        </p>

                        <div class="flex flex-wrap items-center gap-4">
                            @if($data['show_whatsapp_btn'] ?? true)
                            <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hello IndoRoster Export Team, I am looking for Breeze Blocks export supply for our project in ' . $countryName . '. Please share your quotation.') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2.5 px-8 py-4 rounded-xl bg-terra-500 hover:bg-terra-400 text-white font-bold text-sm sm:text-base shadow-xl shadow-terra-500/25 transition-all hover:scale-105">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                <span>{{ $data['whatsapp_text'] ?? 'WhatsApp Export Desk (+62 813-8970-9847)' }}</span>
                            </a>
                            @endif

                            @if($data['show_gallery_btn'] ?? true)
                            <a href="{{ route('export.gallery') }}" class="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl bg-slate-800/90 hover:bg-slate-750 text-slate-200 font-bold text-sm border border-slate-700 transition">
                                <span>📸 Architectural Projects (100+ Photos)</span>
                            </a>
                            @endif

                            @if($data['show_pdf_btn'] ?? true)
                            <a href="https://drive.google.com/file/d/1wcBxdEv7yiytPlLSVE1ldl1rYpe0MHZZ/view?usp=drive_link" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl bg-slate-900/60 hover:bg-slate-800 text-slate-300 font-semibold text-sm border border-slate-700/80 transition">
                                <span>📥 PDF Catalog</span>
                            </a>
                            @endif
                        </div>

                        {{-- 5 Export Trust Badges --}}
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 pt-8 mt-8 border-t border-slate-800 text-[11px] text-slate-300">
                            <div class="flex items-center gap-2">
                                <span class="text-terra-400 font-bold">✓</span>
                                <span>Direct Manufacturer</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-terra-400 font-bold">✓</span>
                                <span>Mould Siku 90° (&lt;1mm)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-terra-400 font-bold">✓</span>
                                <span>Pure Mountain Aggregate</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-terra-400 font-bold">✓</span>
                                <span>Heavy Pallet Crate</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-terra-400 font-bold">✓</span>
                                <span>FCL Sea Container</span>
                            </div>
                        </div>
                    </div>
                </div>

            {{-- 2. MEDIA SHOWCASE / SPILL FOTO & VIDEO BLOCK --}}
            @elseif($type === 'media_showcase')
                <div class="rounded-3xl p-8 sm:p-12 {{ $themeClass }} space-y-8">
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                        <div class="max-w-3xl">
                            @if(!empty($data['badge_text']))
                            <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">
                                {{ $data['badge_text'] }}
                            </span>
                            @endif
                            <h2 class="text-2xl sm:text-4xl font-black mt-1">
                                {{ $data['title'] ?? 'Explore the Architectural Possibilities' }}
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                                {{ $data['subtitle'] ?? 'Discover how decorative ventilation blocks introduce texture, shadow and airflow into real spaces.' }}
                            </p>
                        </div>
                        <a href="{{ route('export.gallery') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-terra-600 dark:text-terra-400 hover:underline flex-shrink-0">
                            <span>Explore Full Project Gallery (100+ Projects)</span>
                            <span>&rarr;</span>
                        </a>
                    </div>

                    {{-- Dynamic Video Inspiration Spill --}}
                    @if(($data['media_source'] ?? '') === 'custom_video' && !empty($data['custom_video_url']))
                        <div class="aspect-video w-full rounded-2xl overflow-hidden bg-slate-950 shadow-soft-lg">
                            <iframe src="{{ $data['custom_video_url'] }}" class="w-full h-full border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    @else
                        {{-- Photo Spill Grid --}}
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                            @foreach($randomGalleryMedia as $media)
                                @php
                                    $gallery = $media->gallery;
                                    $title = $gallery ? $gallery->title : 'Modern Facade Project';
                                    $loc = $gallery && $gallery->location ? $gallery->location : ($countryName . ' Application');
                                    $desc = $gallery && $gallery->description ? $gallery->description : 'Architectural ventilation block installation.';
                                    $pat = $gallery && $gallery->product ? $gallery->product->name : '20×20×10 cm Modular Pattern';
                                @endphp
                                <div class="group relative aspect-4/3 rounded-2xl overflow-hidden bg-slate-950 cursor-pointer shadow-soft-xs hover:shadow-soft-lg transition-all"
                                     @click="openLightbox('{{ $media->formatted_url }}', '{{ addslashes($title) }}', '{{ addslashes($loc) }}', '{{ addslashes($desc) }}', '{{ addslashes($pat) }}')">
                                    <img src="{{ $media->formatted_url }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-80 group-hover:opacity-95 transition-opacity"></div>
                                    <div class="absolute bottom-3 left-3 right-3 text-white">
                                        <p class="text-xs font-bold line-clamp-1 text-white">{{ $title }}</p>
                                        <span class="text-[10px] text-slate-300 flex items-center gap-1 mt-0.5">
                                            <span>📍</span> {{ $loc }}
                                        </span>
                                    </div>
                                    <div class="absolute top-2.5 right-2.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <span class="w-7 h-7 rounded-full bg-terra-500 text-white flex items-center justify-center text-xs font-bold shadow-md">🔍</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Bottom CTA to Export Gallery --}}
                    @if($data['show_bottom_cta'] ?? true)
                    <div class="pt-6 sm:pt-8 flex flex-col sm:flex-row items-center justify-center gap-4 text-center border-t border-slate-200/60 dark:border-slate-800/80">
                        <a href="{{ !empty($data['bottom_cta_url']) ? $data['bottom_cta_url'] : route('export.gallery') }}" 
                           class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-terra-500 hover:bg-terra-400 text-white font-bold text-sm sm:text-base shadow-xl shadow-terra-500/25 transition-all hover:scale-105 active:scale-95 group">
                            <span>{{ $data['bottom_cta_text'] ?? ($isEn ? '📸 Explore Full Architectural Project Gallery (100+ Real Photos)' : '📸 Jelajahi Seluruh Galeri Proyek Ekspor (100+ Foto Proyek)') }}</span>
                            <span class="group-hover:translate-x-1.5 transition-transform duration-300 font-bold">&rarr;</span>
                        </a>
                    </div>
                    @endif
                </div>

            {{-- 3. PROBLEM & IMPORT RISKS WARNING BLOCK --}}
            @elseif($type === 'problem_risks')
                <div class="rounded-3xl p-8 sm:p-10 {{ $themeClass }} space-y-6">
                    <div class="max-w-3xl">
                        <span class="text-xs font-bold uppercase tracking-wider text-red-600 dark:text-red-400">
                            {{ $data['badge'] ?? 'The Import Risks You Must Avoid' }}
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-black mt-1">
                            {{ $data['title'] ?? 'Why Cheap Wet-Cast Blocks Fail in Global Architectural Projects' }}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 mt-1">
                            {{ $data['subtitle'] ?? 'Specifying low-grade artisanal breeze blocks often leads to expensive structural defects:' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs not-prose">
                        @foreach(($data['items'] ?? []) as $item)
                        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-red-100 dark:border-red-900/30 shadow-soft-xs">
                            <div class="text-2xl mb-2">{{ $item['icon'] ?? '❌' }}</div>
                            <strong class="text-slate-900 dark:text-white block font-bold mb-1">{{ $item['title'] ?? '' }}</strong>
                            <p class="text-slate-600 dark:text-slate-400 leading-relaxed">{{ $item['desc'] ?? '' }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>

            {{-- 4. ARCHITECTURAL CONCEPT BLOCK --}}
            @elseif($type === 'architectural_concept')
                <div class="rounded-3xl p-8 sm:p-12 {{ $themeClass }} space-y-8">
                    <div class="max-w-3xl">
                        <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">
                            {{ $data['badge'] ?? 'Architectural Materiality' }}
                        </span>
                        <h2 class="text-2xl sm:text-4xl font-black mt-1">
                            {{ $data['title'] ?? 'Architectural Materials Designed to Create Light, Air and Privacy' }}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                            {{ $data['subtitle'] ?? 'Ventilation blocks are more than functional building materials. Their patterns shape how light, airflow, and privacy interact within an architectural space.' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 not-prose">
                        <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl mb-3 font-bold">🌬️</div>
                            <h3 class="font-bold text-sm mb-1.5">Passive Cross-Ventilation</h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">Eliminates trapped humidity and heat without requiring energy consumption.</p>
                        </div>
                        <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                            <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl mb-3 font-bold">☀️</div>
                            <h3 class="font-bold text-sm mb-1.5">40% Solar Heat Reduction</h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">Acts as a thermal screen that diffuses direct solar glare and cuts cooling costs.</p>
                        </div>
                        <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl mb-3 font-bold">🛡️</div>
                            <h3 class="font-bold text-sm mb-1.5">Open-Air Privacy Screen</h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">Enables outward vision while protecting interior spaces from direct sightlines.</p>
                        </div>
                        <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                            <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl mb-3 font-bold">✨</div>
                            <h3 class="font-bold text-sm mb-1.5">Dynamic Shadow Artistry</h3>
                            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">Transforms walls into living sculptures with geometric shadows shifting across the day.</p>
                        </div>
                    </div>
                </div>

            {{-- 5. PRODUCTS SHOWCASE BLOCK --}}
            @elseif($type === 'products_showcase')
                <div class="space-y-8" id="products-catalog-section">
                    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">
                                {{ $data['badge'] ?? 'Modular Precision Motifs' }}
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-black mt-1">
                                {{ $data['title'] ?? 'Explore Our Modular Architectural Patterns' }}
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                                {{ $data['subtitle'] ?? 'High-precision 90° steel-mould breeze blocks in 20×20×10 cm standard dimensions.' }}
                            </p>
                        </div>
                        <a href="{{ route('export.catalog') }}" class="text-xs font-bold text-terra-600 dark:text-terra-400 hover:underline flex-shrink-0">
                            Explore Full 45+ Export Patterns (No Prices) &rarr;
                        </a>
                    </div>

                    @if($data['show_filter'] ?? true)
                    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 shadow-soft-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 no-scrollbar">
                            <button wire:click="$set('categorySlug', '')" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition {{ empty($categorySlug) ? 'bg-terra-500 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                                All Patterns
                            </button>
                            @foreach($categories as $cat)
                            <button wire:click="$set('categorySlug', '{{ $cat->slug }}')" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition {{ $categorySlug === $cat->slug ? 'bg-terra-500 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                                {{ $cat->name }}
                            </button>
                            @endforeach
                        </div>
                        <div class="w-full md:w-64">
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search pattern name / code..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500 focus:outline-none">
                        </div>
                    </div>
                    @endif

                    @if($products->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                        @foreach($products as $product)
                            @php
                                $displayMedia = $product->primary_media;
                                $imgUrl = ($displayMedia && $displayMedia->media_type === 'image') 
                                    ? $displayMedia->formatted_url 
                                    : ($product->primary_image ?: asset('assets/logo_indoroster_no_text.PNG'));
                                $itemWaUrl = "https://wa.me/{$waNumber}?text=" . urlencode("Hello IndoRoster Export Desk, I would like to request an export quote for: {$product->name} (SKU: " . ($product->sku ?: 'IR-STD') . ") with container shipping to {$countryName}.");
                                $activeVariants = $product->variants->where('is_active', true);
                            @endphp

                            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 flex flex-col justify-between hover:border-terra-400 dark:hover:border-terra-500/80 shadow-soft-xs hover:shadow-soft-lg transition-all group">
                                <div>
                                    <div class="aspect-square bg-slate-50 dark:bg-slate-950 rounded-xl overflow-hidden p-3 flex items-center justify-center mb-3 relative border border-slate-100 dark:border-slate-800/80">
                                        <img src="{{ $imgUrl }}" alt="{{ $product->name }}" class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                        <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded bg-slate-900/90 text-[10px] font-mono font-bold text-slate-200 border border-slate-700">
                                            {{ $product->sku ?: 'IR-'.str_pad($product->id, 3, '0', STR_PAD_LEFT) }}
                                        </span>
                                        <span class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded bg-terra-500 text-white text-[9px] font-bold">
                                            25 pcs/m²
                                        </span>
                                    </div>

                                    <div class="space-y-1.5 mb-4">
                                        <span class="text-[10px] text-terra-600 dark:text-terra-400 font-bold uppercase tracking-wider block">
                                            {{ $product->category->name ?? 'Architectural Breeze Block' }}
                                        </span>

                                        <h3 class="font-black text-sm sm:text-base text-slate-900 dark:text-white line-clamp-1 group-hover:text-terra-600 dark:group-hover:text-terra-400 transition-colors">
                                            {{ $product->name }}
                                        </h3>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                            <strong>Dimensions:</strong> {{ $product->dimensions ?: '20 × 20 × 10 cm' }}
                                        </p>

                                        <div class="pt-1">
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Available Variants:</span>
                                            <div class="flex flex-wrap gap-1">
                                                @if($activeVariants->isNotEmpty())
                                                    @foreach($activeVariants as $variant)
                                                        <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-[10px] font-semibold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                            {{ $variant->name }}
                                                        </span>
                                                    @endforeach
                                                @else
                                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-[10px] font-semibold text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                        Raw Grey
                                                    </span>
                                                    <span class="px-2 py-0.5 rounded-md bg-amber-50 dark:bg-amber-950/40 text-[10px] font-semibold text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-900/40">
                                                        White Dolomite
                                                    </span>
                                                    <span class="px-2 py-0.5 rounded-md bg-orange-50 dark:bg-orange-950/40 text-[10px] font-semibold text-orange-800 dark:text-orange-300 border border-orange-200 dark:border-orange-900/40">
                                                        Terracotta Red
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                                    <a href="{{ $itemWaUrl }}" target="_blank" rel="noopener noreferrer" class="w-full py-2.5 rounded-xl bg-terra-500 hover:bg-terra-400 text-white text-xs font-bold flex items-center justify-center gap-1.5 shadow-md transition">
                                        <span>Request Export Quote</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($products->hasMorePages())
                    <div class="w-full flex flex-col items-center justify-center py-6">
                        <button wire:click="loadMore" class="px-8 py-3 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-bold text-slate-800 dark:text-slate-200 shadow-soft-xs hover:border-terra-500 transition">
                            Load More Patterns...
                        </button>
                    </div>
                    @endif

                    @else
                    <div class="text-center py-16 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800">
                        <p class="text-slate-500 text-sm">No patterns found matching your search.</p>
                    </div>
                    @endif
                </div>

            {{-- 6. FACTORY HERITAGE BLOCK (DENGAN FOTO & VIDEO) --}}
            @elseif($type === 'factory_heritage')
                <div class="rounded-3xl p-8 sm:p-12 {{ $themeClass }} relative overflow-hidden space-y-8">
                    <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-terra-500/15 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-7 space-y-6">
                            <span class="text-xs font-bold uppercase tracking-wider text-terra-400">
                                {{ $data['badge'] ?? 'Heritage of Indonesian Stonemasonry' }}
                            </span>
                            <h2 class="text-2xl sm:text-4xl font-black leading-tight text-white">
                                {{ $data['title'] ?? 'Centenary Indonesian Craftsmanship Meets Industrial Steel Precision' }}
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                                {{ $data['subtitle'] ?? 'Behind every IndoRoster breeze block lies the deep-rooted heritage of Plered, Purwakarta — Indonesia’s world-renowned artisan pottery and stonemasonry hub active since the early 1900s.' }}
                            </p>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs pt-4 border-t border-slate-800">
                                <div>
                                    <strong class="text-terra-400 block text-lg font-black">{{ $data['stat_years'] ?? '100+ Yrs' }}</strong>
                                    <span class="text-slate-300 text-[11px]">{{ $data['stat_years_label'] ?? 'Plered Craft Heritage' }}</span>
                                </div>
                                <div>
                                    <strong class="text-terra-400 block text-lg font-black">{{ $data['stat_tolerance'] ?? '< 1 mm' }}</strong>
                                    <span class="text-slate-300 text-[11px]">{{ $data['stat_tolerance_label'] ?? 'Steel Mould Tolerance' }}</span>
                                </div>
                                <div>
                                    <strong class="text-terra-400 block text-lg font-black">{{ $data['stat_cooling'] ?? '40%' }}</strong>
                                    <span class="text-slate-300 text-[11px]">{{ $data['stat_cooling_label'] ?? 'Passive Solar Cooling' }}</span>
                                </div>
                                <div>
                                    <strong class="text-terra-400 block text-lg font-black">{{ $data['stat_reach'] ?? '110' }}</strong>
                                    <span class="text-slate-300 text-[11px]">{{ $data['stat_reach_label'] ?? 'Global Export Destinations' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Factory Media (Video / Photo Spill) --}}
                        <div class="lg:col-span-5">
                            @if(!empty($data['factory_video_url']))
                                <div class="{{ getMediaAspectClass($data['media_aspect'] ?? 'landscape') }} rounded-2xl overflow-hidden bg-slate-950 border border-slate-800">
                                    <iframe src="{{ $data['factory_video_url'] }}" class="w-full h-full border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            @elseif(!empty($data['factory_image']))
                                <div class="{{ getMediaAspectClass($data['media_aspect'] ?? 'landscape') }} rounded-2xl overflow-hidden bg-slate-950 border border-slate-800 group relative cursor-pointer"
                                     @click="openLightbox('{{ asset('storage/' . $data['factory_image']) }}', 'Factory Craftsmanship Plered', 'Plered, Purwakarta', 'IndoRoster Factory Production', 'Precision Steel Mould')">
                                    <img src="{{ asset('storage/' . $data['factory_image']) }}" alt="IndoRoster Factory Plered" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>
                                    <div class="absolute bottom-3 left-3 text-white text-xs font-bold">🏭 Plered Stonemasonry Factory</div>
                                </div>
                            @else
                                <div class="aspect-4/3 w-full rounded-2xl overflow-hidden bg-slate-950 border border-slate-800 shadow-xl p-6 flex flex-col justify-between">
                                    <div>
                                        <span class="text-3xl mb-2 block">🏭</span>
                                        <h4 class="font-bold text-white text-base mb-1">Authentic Plered Stonemasonry</h4>
                                        <p class="text-xs text-slate-400 leading-relaxed">Artisan stamping & hydraulic compression facility in Purwakarta, West Java.</p>
                                    </div>
                                    <div class="pt-3 border-t border-slate-800/80 flex items-center justify-between text-[11px] text-terra-400">
                                        <span>✓ Plat Baja Siku 90°</span>
                                        <span>✓ Abu Batu Murni</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            {{-- 7. SPILL PROSES PRODUKSI (BARU) --}}
            @elseif($type === 'production_process_spill')
                <div class="rounded-3xl p-8 sm:p-12 {{ $themeClass }} space-y-8">
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                        <div class="max-w-3xl">
                            <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">
                                {{ $data['badge'] ?? 'Authentic Manufacturing Process' }}
                            </span>
                            <h2 class="text-2xl sm:text-4xl font-black mt-1">
                                {{ $data['title'] ?? 'How We Manufacture High-Density Breeze Blocks' }}
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                                {{ $data['subtitle'] ?? 'Step-by-step glimpse into our semi-dry compaction, precision steel moulding, and strict curing process at Plered, Purwakarta.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Multi-Video Portrait Grid (Bisa Berjajar Rapi Kesamping) --}}
                    @if(!empty($data['showcase_videos']) && is_array($data['showcase_videos']) && count($data['showcase_videos']) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-{{ min(count($data['showcase_videos']), 4) }} gap-5 max-w-6xl mx-auto mb-10">
                            @foreach($data['showcase_videos'] as $v)
                                <div class="aspect-[9/16] rounded-2xl overflow-hidden bg-slate-950 border border-slate-800 shadow-2xl relative group">
                                    @if(!empty($v['video_file']))
                                        <video src="{{ asset('storage/' . $v['video_file']) }}" controls class="w-full h-full object-cover" poster="{{ !empty($v['thumbnail']) ? asset('storage/' . $v['thumbnail']) : '' }}"></video>
                                    @elseif(!empty($v['video_url']))
                                        <iframe src="{{ $v['video_url'] }}" class="w-full h-full border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    @endif
                                    @if(!empty($v['title']))
                                        <div class="absolute bottom-0 inset-x-0 p-3 bg-gradient-to-t from-black/90 to-transparent pointer-events-none">
                                            <p class="text-white text-xs font-bold line-clamp-2">{{ $v['title'] }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Fallback Single Video / Media --}}
                        @if(!empty($data['process_main_video_file']))
                        <div class="{{ ($data['main_media_aspect'] ?? 'landscape') === 'portrait' ? 'max-w-xs aspect-[9/16] mx-auto rounded-2xl overflow-hidden bg-slate-950 shadow-2xl mb-8' : getMediaAspectClass($data['main_media_aspect'] ?? 'landscape') . ' rounded-2xl overflow-hidden bg-slate-950 mb-8' }}">
                            <video src="{{ asset('storage/' . $data['process_main_video_file']) }}" controls class="w-full h-full object-cover"></video>
                        </div>
                        @elseif(!empty($data['process_video_url']))
                        <div class="{{ ($data['main_media_aspect'] ?? 'landscape') === 'portrait' ? 'max-w-xs aspect-[9/16] mx-auto rounded-2xl overflow-hidden bg-slate-950 shadow-2xl mb-8' : getMediaAspectClass($data['main_media_aspect'] ?? 'landscape') . ' rounded-2xl overflow-hidden bg-slate-950 mb-8' }}">
                            <iframe src="{{ $data['process_video_url'] }}" class="w-full h-full border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        @elseif(!empty($data['process_main_image']))
                        <div class="{{ ($data['main_media_aspect'] ?? 'landscape') === 'portrait' ? 'max-w-xs aspect-[9/16] mx-auto rounded-2xl overflow-hidden bg-slate-950 shadow-2xl mb-8 cursor-pointer' : getMediaAspectClass($data['main_media_aspect'] ?? 'landscape') . ' rounded-2xl overflow-hidden bg-slate-950 mb-8 cursor-pointer' }}"
                             @click="openLightbox('{{ asset('storage/' . $data['process_main_image']) }}', '{{ $data['title'] ?? 'Proses Produksi' }}', 'Plered, Purwakarta', 'IndoRoster Factory', 'Produksi')">
                            <img src="{{ asset('storage/' . $data['process_main_image']) }}" alt="Proses Produksi Roster" class="w-full h-full object-cover hover:scale-102 transition-transform duration-500">
                        </div>
                        @endif
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 not-prose">
                        @foreach(($data['steps'] ?? []) as $step)
                        <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between space-y-4">
                            <div>
                                <span class="w-8 h-8 rounded-lg bg-terra-500/10 text-terra-600 dark:text-terra-400 font-mono font-bold text-xs flex items-center justify-center mb-3">
                                    {{ $step['step_num'] ?? '01' }}
                                </span>
                                <h3 class="font-bold text-sm mb-1.5">{{ $step['title'] ?? '' }}</h3>
                                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">{{ $step['desc'] ?? '' }}</p>
                            </div>

                            @if(!empty($step['video_file']))
                            <div class="{{ getStepMediaAspectClass($step['media_aspect'] ?? 'landscape') }} rounded-xl overflow-hidden bg-slate-900">
                                <video src="{{ asset('storage/' . $step['video_file']) }}" controls class="w-full h-full object-contain bg-black"></video>
                            </div>
                            @elseif(!empty($step['video_url']))
                            <div class="{{ getStepMediaAspectClass($step['media_aspect'] ?? 'landscape') }} rounded-xl overflow-hidden bg-slate-900">
                                <iframe src="{{ $step['video_url'] }}" class="w-full h-full border-0" allowfullscreen></iframe>
                            </div>
                            @elseif(!empty($step['image']))
                            <div class="{{ getStepMediaAspectClass($step['media_aspect'] ?? 'landscape') }} rounded-xl overflow-hidden bg-slate-900 cursor-pointer"
                                 @click="openLightbox('{{ asset('storage/' . $step['image']) }}', '{{ $step['title'] }}', 'Tahap {{ $step['step_num'] }}', '{{ $step['desc'] }}', 'Proses Produksi')">
                                <img src="{{ asset('storage/' . $step['image']) }}" alt="{{ $step['title'] }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

            {{-- 8. SPILL PENGIRIMAN & LOGISTIK KONTAINER (BARU) --}}
            @elseif($type === 'shipping_logistics_spill')
                <div class="rounded-3xl p-8 sm:p-12 {{ $themeClass }} space-y-8">
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                        <div class="max-w-3xl">
                            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">
                                {{ $data['badge'] ?? 'Export Packing & Ocean Logistics' }}
                            </span>
                            <h2 class="text-2xl sm:text-4xl font-black mt-1">
                                {{ $data['title'] ?? 'Container Stuffing & Export Dispatch Process' }}
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                                {{ $data['subtitle'] ?? 'Watch how our breeze blocks are securely packed in heavy-duty wooden crates, strapped, and loaded into ocean containers at our factory gate.' }}
                            </p>
                        </div>
                    </div>

                    {{-- Multi-Video Portrait Grid (Bisa Berjajar Rapi Kesamping) --}}
                    @if(!empty($data['showcase_videos']) && is_array($data['showcase_videos']) && count($data['showcase_videos']) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-{{ min(count($data['showcase_videos']), 4) }} gap-5 max-w-6xl mx-auto mb-10">
                            @foreach($data['showcase_videos'] as $v)
                                <div class="aspect-[9/16] rounded-2xl overflow-hidden bg-slate-950 border border-slate-800 shadow-2xl relative group">
                                    @if(!empty($v['video_file']))
                                        <video src="{{ asset('storage/' . $v['video_file']) }}" controls class="w-full h-full object-cover" poster="{{ !empty($v['thumbnail']) ? asset('storage/' . $v['thumbnail']) : '' }}"></video>
                                    @elseif(!empty($v['video_url']))
                                        <iframe src="{{ $v['video_url'] }}" class="w-full h-full border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    @endif
                                    @if(!empty($v['title']))
                                        <div class="absolute bottom-0 inset-x-0 p-3 bg-gradient-to-t from-black/90 to-transparent pointer-events-none">
                                            <p class="text-white text-xs font-bold line-clamp-2">{{ $v['title'] }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Fallback Single Video / Media --}}
                        @if(!empty($data['shipping_main_video_file']))
                        <div class="{{ ($data['main_media_aspect'] ?? 'landscape') === 'portrait' ? 'max-w-xs aspect-[9/16] mx-auto rounded-2xl overflow-hidden bg-slate-950 shadow-2xl mb-8' : getMediaAspectClass($data['main_media_aspect'] ?? 'landscape') . ' rounded-2xl overflow-hidden bg-slate-950 mb-8' }}">
                            <video src="{{ asset('storage/' . $data['shipping_main_video_file']) }}" controls class="w-full h-full object-cover"></video>
                        </div>
                        @elseif(!empty($data['shipping_video_url']))
                        <div class="{{ ($data['main_media_aspect'] ?? 'landscape') === 'portrait' ? 'max-w-xs aspect-[9/16] mx-auto rounded-2xl overflow-hidden bg-slate-950 shadow-2xl mb-8' : getMediaAspectClass($data['main_media_aspect'] ?? 'landscape') . ' rounded-2xl overflow-hidden bg-slate-950 mb-8' }}">
                            <iframe src="{{ $data['shipping_video_url'] }}" class="w-full h-full border-0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                        @elseif(!empty($data['shipping_main_image']))
                        <div class="{{ ($data['main_media_aspect'] ?? 'landscape') === 'portrait' ? 'max-w-xs aspect-[9/16] mx-auto rounded-2xl overflow-hidden bg-slate-950 shadow-2xl mb-8 cursor-pointer' : getMediaAspectClass($data['main_media_aspect'] ?? 'landscape') . ' rounded-2xl overflow-hidden bg-slate-950 mb-8 cursor-pointer' }}"
                             @click="openLightbox('{{ asset('storage/' . $data['shipping_main_image']) }}', '{{ $data['title'] ?? 'Pengiriman Ekspor' }}', 'Tanjung Priok / Plered', 'Stuffing Kontainer', 'Logistik')">
                            <img src="{{ asset('storage/' . $data['shipping_main_image']) }}" alt="Pengiriman Ekspor Kontainer" class="w-full h-full object-cover hover:scale-102 transition-transform duration-500">
                        </div>
                        @endif
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 not-prose">
                        @foreach(($data['steps'] ?? []) as $step)
                        <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between space-y-4">
                            <div>
                                <span class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-mono font-bold text-xs flex items-center justify-center mb-3">
                                    {{ $step['step_num'] ?? '01' }}
                                </span>
                                <h3 class="font-bold text-sm mb-1.5">{{ $step['title'] ?? '' }}</h3>
                                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">{{ $step['desc'] ?? '' }}</p>
                            </div>

                            @if(!empty($step['video_file']))
                            <div class="{{ getStepMediaAspectClass($step['media_aspect'] ?? 'landscape') }} rounded-xl overflow-hidden bg-slate-900">
                                <video src="{{ asset('storage/' . $step['video_file']) }}" controls class="w-full h-full object-contain bg-black"></video>
                            </div>
                            @elseif(!empty($step['video_url']))
                            <div class="{{ getStepMediaAspectClass($step['media_aspect'] ?? 'landscape') }} rounded-xl overflow-hidden bg-slate-900">
                                <iframe src="{{ $step['video_url'] }}" class="w-full h-full border-0" allowfullscreen></iframe>
                            </div>
                            @elseif(!empty($step['image']))
                            <div class="{{ getStepMediaAspectClass($step['media_aspect'] ?? 'landscape') }} rounded-xl overflow-hidden bg-slate-900 cursor-pointer"
                                 @click="openLightbox('{{ asset('storage/' . $step['image']) }}', '{{ $step['title'] }}', 'Tahap {{ $step['step_num'] }}', '{{ $step['desc'] }}', 'Pengiriman Ekspor')">
                                <img src="{{ asset('storage/' . $step['image']) }}" alt="{{ $step['title'] }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

            {{-- 9. FREE SAMPLE REQUEST (BARU) --}}
            @elseif($type === 'free_sample_request')
                <div class="rounded-3xl p-8 sm:p-12 {{ $themeClass }} relative overflow-hidden space-y-8">
                    <div class="max-w-3xl">
                        <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">
                            {{ $data['badge'] ?? 'Physical Quality Verification' }}
                        </span>
                        <h2 class="text-2xl sm:text-4xl font-black mt-1">
                            {{ $data['title'] ?? 'Request Free Physical Sample Box Before Placing Container Orders' }}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 mt-2 leading-relaxed">
                            {{ $data['subtitle'] ?? 'We provide 100% free sample blocks (Raw Grey, White Dolomite, or Terracotta Red) so architects and contractors can test the 90° precision steel mould sharpness and material density. Sample units are free of charge; courier express air freight (DHL/FedEx/Aramex) or forwarder pickup is covered by the client.' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center not-prose">
                        {{-- Left Side: 3 Value Cards --}}
                        <div class="lg:col-span-7 space-y-4">
                            <div class="p-5 rounded-2xl bg-white/80 dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-800 shadow-soft-xs flex items-start gap-4">
                                <span class="w-10 h-10 rounded-xl bg-terra-500/10 text-terra-600 dark:text-terra-400 flex items-center justify-center font-black text-lg flex-shrink-0">🎁</span>
                                <div>
                                    <h4 class="font-black text-sm mb-1">{{ $data['feature_1_title'] ?? '100% Free Sample Units' }}</h4>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">{{ $data['feature_1_desc'] ?? 'Order 1–3 physical breeze block units with zero product cost.' }}</p>
                                </div>
                            </div>

                            <div class="p-5 rounded-2xl bg-white/80 dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-800 shadow-soft-xs flex items-start gap-4">
                                <span class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center font-black text-lg flex-shrink-0">✈️</span>
                                <div>
                                    <h4 class="font-black text-sm mb-1">{{ $data['feature_2_title'] ?? 'Freight Collect / Express Air' }}</h4>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">{{ $data['feature_2_desc'] ?? 'Worldwide express dispatch via DHL, FedEx, or your forwarder account.' }}</p>
                                </div>
                            </div>

                            <div class="p-5 rounded-2xl bg-white/80 dark:bg-slate-900/80 border border-slate-200/80 dark:border-slate-800 shadow-soft-xs flex items-start gap-4">
                                <span class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-black text-lg flex-shrink-0">💰</span>
                                <div>
                                    <h4 class="font-black text-sm mb-1">{{ $data['feature_3_title'] ?? 'Freight Rebate on FCL Order' }}</h4>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">{{ $data['feature_3_desc'] ?? 'Courier freight cost is 100% credited back when you place a 20ft/40ft FCL container order!' }}</p>
                                </div>
                            </div>

                            <div class="pt-4">
                                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($data['sample_wa_message'] ?? ('Hello IndoRoster, I would like to request a Free Architectural Sample Kit for our project in ' . $countryName . '. We will cover the express courier freight.')) }}" 
                                   target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center justify-center gap-2.5 px-8 py-4 rounded-xl bg-terra-500 hover:bg-terra-400 text-white font-black text-sm sm:text-base shadow-xl shadow-terra-500/25 transition-all hover:scale-105">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                    <span>{{ $data['cta_button_text'] ?? 'Request Free Sample Kit via WhatsApp (+62 813-8970-9847)' }}</span>
                                </a>
                            </div>
                        </div>

                        {{-- Right Side: Media Showcase (Sample Box Photo / Video) --}}
                        <div class="lg:col-span-5">
                            @if(!empty($data['sample_video_file']))
                                <div class="aspect-[9/16] max-h-[480px] max-w-xs mx-auto rounded-2xl overflow-hidden bg-slate-950 shadow-2xl border border-slate-800">
                                    <video src="{{ asset('storage/' . $data['sample_video_file']) }}" controls class="w-full h-full object-cover"></video>
                                </div>
                            @elseif(!empty($data['sample_video_url']))
                                <div class="aspect-video w-full rounded-2xl overflow-hidden bg-slate-950 shadow-2xl border border-slate-800">
                                    <iframe src="{{ $data['sample_video_url'] }}" class="w-full h-full border-0" allowfullscreen></iframe>
                                </div>
                            @elseif(!empty($data['sample_image']))
                                <div class="aspect-4/3 w-full rounded-2xl overflow-hidden bg-slate-950 shadow-2xl border border-slate-800 cursor-pointer"
                                     @click="openLightbox('{{ asset('storage/' . $data['sample_image']) }}', 'Free Architectural Sample Box', '{{ $countryName }} Delivery', 'Physical Sample Kit', 'Sample Unit')">
                                    <img src="{{ asset('storage/' . $data['sample_image']) }}" alt="Sample Box IndoRoster" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                </div>
                            @else
                                <div class="p-8 rounded-3xl bg-slate-900 text-white border border-slate-800 shadow-2xl text-center space-y-4">
                                    <div class="text-5xl">📦</div>
                                    <h4 class="font-black text-lg">Architectural Sample Kit</h4>
                                    <p class="text-xs text-slate-300 leading-relaxed">Contains 20×20×10 cm genuine breeze blocks, aggregate texture samples, and full technical specifications brochure.</p>
                                    <div class="pt-2">
                                        <span class="inline-block px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-bold">100% Free Product Supply</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            {{-- 9. LOGISTICS SPECS BLOCK --}}
            @elseif($type === 'logistics_specs')
                <div class="rounded-3xl p-8 sm:p-12 {{ $themeClass }} space-y-8">
                    <div class="max-w-3xl">
                        <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">
                            {{ $data['badge'] ?? 'Sea Freight Logistics' }}
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-black mt-1">
                            {{ $data['title'] ?? 'Container Capacity & Export Packaging Specifications' }}
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                            {{ $data['subtitle'] ?? ('Direct container dispatch from Tanjung Priok Port (Jakarta) to ' . ($data['port_name'] ?? $port) . '.') }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 not-prose">
                        <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                            <div class="text-3xl mb-3">🚢</div>
                            <h4 class="font-extrabold text-base mb-1">20ft Container (FCL)</h4>
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                Capacity: <strong>{{ $data['cap_20ft'] ?? 'approx. 2,500 – 3,000 pcs (±12–14 metric tons)' }}</strong>.
                            </p>
                        </div>
                        <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                            <div class="text-3xl mb-3">🚢</div>
                            <h4 class="font-extrabold text-base mb-1">40ft Container (FCL)</h4>
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                Capacity: <strong>{{ $data['cap_40ft'] ?? 'approx. 4,500 – 5,500 pcs (±22–26 metric tons)' }}</strong>.
                            </p>
                        </div>
                        <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                            <div class="text-3xl mb-3">📦</div>
                            <h4 class="font-extrabold text-base mb-1">Heavy-Duty Crate Packing</h4>
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                {{ $data['packing_desc'] ?? 'Reinforced wooden pallets, foam/straw cushioning, corner protectors, and multi-layer waterproof shrink wrap.' }}
                            </p>
                        </div>
                    </div>

                    <div class="p-6 rounded-2xl bg-emerald-50/70 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">📄</span>
                            <div>
                                <h4 class="font-bold text-sm">Certificate of Origin & Customs Clearance</h4>
                                <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">{{ $data['form_d_text'] ?? 'Supported with official trade documents for preferential tariff / zero-duty import.' }}</p>
                            </div>
                        </div>
                        <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hello IndoRoster Export Desk, I would like to inquire about documentation and sea freight rates to ' . $countryName . '.') }}" target="_blank" rel="noopener noreferrer" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-sm transition flex-shrink-0">
                            <span>Contact Export Desk &rarr;</span>
                        </a>
                    </div>
                </div>

            {{-- 10. NATURAL MATERIALS BLOCK (DENGAN FOTO & VIDEO TIAP BAHAN) --}}
            @elseif($type === 'natural_materials')
                <div class="rounded-3xl p-8 sm:p-12 {{ $themeClass }} space-y-8">
                    <div class="max-w-3xl">
                        <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">
                            {{ $data['badge'] ?? '100% Solid Natural Mineral Aggregates (Zero Spray Paint)' }}
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-black mt-1">
                            {{ $data['title'] ?? '3 Authentic Material Finishes Available' }}
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 not-prose">
                        {{-- 1. Raw Grey --}}
                        @if($data['show_raw_grey'] ?? true)
                        <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="px-2.5 py-1 rounded-md bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200 text-[10px] font-black uppercase tracking-wider">Raw Grey</span>
                                    <span class="text-xs text-slate-500 font-semibold">20×20×10 cm</span>
                                </div>
                                
                                @if(!empty($data['raw_grey_image']))
                                <div class="aspect-4/3 w-full rounded-xl overflow-hidden bg-slate-900 mb-3">
                                    <img src="{{ asset('storage/' . $data['raw_grey_image']) }}" alt="Raw Grey" class="w-full h-full object-cover">
                                </div>
                                @endif

                                <h4 class="text-base font-black mb-2">{{ $data['raw_grey_title'] ?? 'Natural Mountain Stone Ash (Raw Grey)' }}</h4>
                                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                                    {{ $data['raw_grey_desc'] ?? 'Pure mountain stone aggregate and dense cement. Bold industrial concrete hue favored for Brutalist and modern minimalist facades.' }}
                                </p>
                            </div>
                            <div class="pt-3 border-t border-slate-200/80 dark:border-slate-800 text-[11px] text-slate-500 dark:text-slate-400">
                                <strong>Best For:</strong> {{ $data['raw_grey_best_for'] ?? 'Modern lofts, industrial facades, carports.' }}
                            </div>
                        </div>
                        @endif

                        {{-- 2. White Dolomite --}}
                        @if($data['show_white_dolomite'] ?? true)
                        <div class="p-6 rounded-2xl bg-amber-50/50 dark:bg-amber-950/20 border border-amber-200/70 dark:border-amber-900/40 flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="px-2.5 py-1 rounded-md bg-amber-100 dark:bg-amber-900/60 text-amber-900 dark:text-amber-200 text-[10px] font-black uppercase tracking-wider">Milky White / Cream</span>
                                    <span class="text-xs text-amber-700 dark:text-amber-400 font-semibold">Anti-Algae</span>
                                </div>

                                @if(!empty($data['white_dolomite_image']))
                                <div class="aspect-4/3 w-full rounded-xl overflow-hidden bg-slate-900 mb-3">
                                    <img src="{{ asset('storage/' . $data['white_dolomite_image']) }}" alt="White Dolomite" class="w-full h-full object-cover">
                                </div>
                                @endif

                                <h4 class="text-base font-black mb-2">{{ $data['white_dolomite_title'] ?? 'Natural White Dolomite Stone' }}</h4>
                                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                                    {{ $data['white_dolomite_desc'] ?? 'Crafted from pure natural white dolomite mountain stone. Elegant soft warm cream mineral tone that reflects solar heat.' }}
                                </p>
                            </div>
                            <div class="pt-3 border-t border-amber-200/60 dark:border-amber-900/40 text-[11px] text-slate-500 dark:text-slate-400">
                                <strong>Best For:</strong> {{ $data['white_dolomite_best_for'] ?? 'Mediterranean villas, Palm Springs pool screens.' }}
                            </div>
                        </div>
                        @endif

                        {{-- 3. Terracotta Red --}}
                        @if($data['show_terracotta'] ?? true)
                        <div class="p-6 rounded-2xl bg-orange-50/50 dark:bg-orange-950/20 border border-orange-200/70 dark:border-orange-900/40 flex flex-col justify-between space-y-4">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="px-2.5 py-1 rounded-md bg-orange-100 dark:bg-orange-900/60 text-orange-900 dark:text-orange-200 text-[10px] font-black uppercase tracking-wider">Terracotta Red</span>
                                    <span class="text-xs text-orange-700 dark:text-orange-400 font-semibold">High-Fire Kiln</span>
                                </div>

                                @if(!empty($data['terracotta_image']))
                                <div class="aspect-4/3 w-full rounded-xl overflow-hidden bg-slate-900 mb-3">
                                    <img src="{{ asset('storage/' . $data['terracotta_image']) }}" alt="Terracotta" class="w-full h-full object-cover">
                                </div>
                                @endif

                                <h4 class="text-base font-black mb-2">{{ $data['terracotta_title'] ?? 'Authentic Plered High-Fire Terracotta' }}</h4>
                                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                                    {{ $data['terracotta_desc'] ?? 'Made from selected Plered red clay and kiln-fired at high temperatures for optimal strength and porous breathability.' }}
                                </p>
                            </div>
                            <div class="pt-3 border-t border-orange-200/60 dark:border-orange-900/40 text-[11px] text-slate-500 dark:text-slate-400">
                                <strong>Best For:</strong> {{ $data['terracotta_best_for'] ?? 'Tropical resorts, Spanish hacienda garden walls.' }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

            {{-- 9. TRADE TERMS & PAYMENT SECURITY BLOCK --}}
            @elseif($type === 'trade_terms')
                <div class="rounded-3xl p-8 sm:p-12 {{ $themeClass }} space-y-8">
                    <div class="max-w-3xl">
                        <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">
                            {{ $data['badge'] ?? 'Trade Terms & Payment Security' }}
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-black mt-1">
                            {{ $data['title'] ?? 'EXW (Ex Works) Factory Terms & Secure Payment Methods' }}
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 not-prose">
                        <div class="p-6 sm:p-8 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between">
                            <div>
                                <span class="px-3 py-1 rounded-full bg-emerald-600/10 text-emerald-600 dark:text-emerald-400 text-xs font-black uppercase tracking-wider block mb-3">Incoterms 2020: EXW (Ex Works)</span>
                                <h3 class="text-lg font-black mb-3">Factory Direct Handover (Plered, West Java)</h3>
                                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                                    {{ $data['trade_scope'] ?? 'IndoRoster is responsible for high-precision manufacturing, strict QC, heavy-duty ocean palletized crating, and loading onto your carrier vehicle.' }}
                                </p>
                            </div>
                        </div>

                        <div class="p-6 sm:p-8 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between">
                            <div>
                                <span class="px-3 py-1 rounded-full bg-blue-600/10 text-blue-600 dark:text-blue-400 text-xs font-black uppercase tracking-wider block mb-3">Payment Milestones</span>
                                <h3 class="text-lg font-black mb-3">Accepted Payment Channels & Milestones</h3>
                                <div class="grid grid-cols-2 gap-3 text-xs">
                                    <div class="p-3.5 rounded-xl bg-emerald-50/70 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-emerald-600 dark:text-emerald-400 block mb-1">Step 1</span>
                                        <strong class="text-sm block font-black">{{ $data['dp_milestone'] ?? '50% Down Payment' }}</strong>
                                    </div>
                                    <div class="p-3.5 rounded-xl bg-blue-50/70 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800">
                                        <span class="text-[10px] font-black uppercase tracking-wider text-blue-600 dark:text-blue-400 block mb-1">Step 2</span>
                                        <strong class="text-sm block font-black">{{ $data['balance_milestone'] ?? '50% Balance Payment' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            {{-- 10. FAQS ACCORDION BLOCK --}}
            @elseif($type === 'faqs_accordion')
                <div class="rounded-3xl p-8 sm:p-12 {{ $themeClass }} space-y-8">
                    <div class="max-w-3xl">
                        <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">
                            {{ $data['badge'] ?? 'Export FAQ' }}
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-black mt-1">
                            {{ $data['title'] ?? ('Frequently Asked Questions for ' . $countryName . ' Projects') }}
                        </h2>
                    </div>

                    <div class="space-y-4" x-data="{ activeFaq: null }">
                        @foreach(($data['faqs'] ?? $faqs) as $idx => $f)
                        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/60 overflow-hidden">
                            <button @click="activeFaq = (activeFaq === {{ $idx }} ? null : {{ $idx }})" class="w-full p-5 text-left flex items-center justify-between gap-4 font-bold text-sm">
                                <span>{{ $f['q'] ?? '' }}</span>
                                <span class="text-lg transition-transform duration-200" :class="activeFaq === {{ $idx }} ? 'rotate-180 text-terra-500' : 'text-slate-400'">▼</span>
                            </button>
                            <div x-show="activeFaq === {{ $idx }}" x-collapse class="px-5 pb-5 text-xs text-slate-600 dark:text-slate-300 leading-relaxed border-t border-slate-200/60 dark:border-slate-800/80 pt-3">
                                {{ $f['a'] ?? '' }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            {{-- 11. RFQ FORM & LEAD MAGNET BLOCK --}}
            @elseif($type === 'rfq_lead_magnet')
                <div class="space-y-12">
                    <div class="rounded-3xl p-8 sm:p-12 {{ $themeClass }} relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-8">
                        <div class="relative z-10 max-w-2xl">
                            <span class="text-xs font-bold uppercase tracking-wider text-terra-400 block mb-2">Architectural Resource</span>
                            <h3 class="text-2xl sm:text-3xl font-black text-white mb-2">{{ $data['lead_magnet_title'] ?? 'Mencari Inspirasi Produk & Spesifikasi Penuh?' }}</h3>
                            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">{{ $data['lead_magnet_desc'] ?? 'Dapatkan katalog arsitektural lengkap berisi 45+ motif roster presisi, detail dimensi, dan panduan instalasi fasad dalam format PDF.' }}</p>
                        </div>
                        <a href="https://drive.google.com/file/d/1wcBxdEv7yiytPlLSVE1ldl1rYpe0MHZZ/view?usp=drive_link" target="_blank" rel="noopener noreferrer" class="relative z-10 px-6 py-3.5 rounded-xl bg-terra-500 hover:bg-terra-400 text-white font-bold text-xs sm:text-sm shadow-lg shadow-terra-500/25 transition flex-shrink-0">
                            <span>📥 Download PDF Catalog</span>
                        </a>
                    </div>

                    {{-- RFQ Form --}}
                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-8 sm:p-12 shadow-soft-xs" id="rfq-form">
                        <div class="max-w-2xl mb-8">
                            <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">Direct Factory Quotation</span>
                            <h3 class="text-2xl sm:text-3xl font-black mt-1">{{ $data['rfq_title'] ?? ('Request Export Quotation for ' . $countryName) }}</h3>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $data['rfq_subtitle'] ?? 'Fill out your project specifications to receive an official itemized export quotation.' }}</p>
                        </div>

                        <form wire:submit="submitRfq" class="space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Full Name *</label>
                                    <input type="text" wire:model="fullName" placeholder="e.g. John Doe" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-terra-500 focus:outline-none" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Company / Studio</label>
                                    <input type="text" wire:model="companyName" placeholder="e.g. Studio Architecture Ltd" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-terra-500 focus:outline-none">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Business Email *</label>
                                    <input type="email" wire:model="businessEmail" placeholder="john@company.com" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-terra-500 focus:outline-none" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Your Role</label>
                                    <select wire:model="buyerRole" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-terra-500 focus:outline-none">
                                        <option value="Architect">Architect / Specifier</option>
                                        <option value="Contractor">Main Contractor / Builder</option>
                                        <option value="Developer">Property Developer</option>
                                        <option value="Importer">Building Material Importer / Distributor</option>
                                        <option value="Owner">Private Homeowner</option>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Estimated Quantity / Volume</label>
                                <select wire:model="estimatedQuantity" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-terra-500 focus:outline-none">
                                    <option value="1,000–3,000 Pieces (1x 20ft FCL Container)">1,000–3,000 Pieces (1× 20ft FCL Container)</option>
                                    <option value="4,500–6,000 Pieces (1x 40ft FCL Container)">4,500–6,000 Pieces (1× 40ft FCL Container)</option>
                                    <option value="Multi-Container Project (>10,000 Pieces)">Multi-Container Project (>10,000 Pieces)</option>
                                    <option value="LCL / Sample Pallet Inquiry">LCL / Sample Pallet Inquiry</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Project Brief & Preferred Motifs</label>
                                <textarea wire:model="projectDetails" rows="3" placeholder="Tell us about your project location, preferred pattern codes, material finish, and expected delivery timeline..." class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white text-xs focus:ring-2 focus:ring-terra-500 focus:outline-none"></textarea>
                            </div>

                            <button type="submit" class="w-full py-4 rounded-xl bg-terra-500 hover:bg-terra-400 text-white font-bold text-sm shadow-xl shadow-terra-500/25 transition">
                                <span>Submit Request for Quotation &rarr;</span>
                            </button>
                        </form>
                    </div>
                </div>

            {{-- 12. CUSTOM CONTENT BLOCK --}}
            @elseif($type === 'custom_content')
                <div class="rounded-3xl p-8 sm:p-12 {{ $themeClass }} space-y-6">
                    @if(!empty($data['title']))
                    <h2 class="text-2xl sm:text-3xl font-black">{{ $data['title'] }}</h2>
                    @endif
                    <div class="prose dark:prose-invert max-w-none text-xs sm:text-sm leading-relaxed">
                        {!! $data['content'] ?? '' !!}
                    </div>
                </div>
            @endif

        @endforeach

    </div>

    {{-- Interactive Lightbox Modal --}}
    <div x-show="lightboxOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-md"
         @keydown.escape.window="lightboxOpen = false"
         style="display: none;">
        
        <div class="relative max-w-5xl w-full bg-slate-900 rounded-3xl border border-slate-800 overflow-hidden shadow-2xl flex flex-col md:flex-row"
             @click.away="lightboxOpen = false">
            
            <div class="relative md:w-2/3 aspect-4/3 md:aspect-auto bg-slate-950 flex items-center justify-center overflow-hidden"
                 @wheel.prevent="handleWheel($event)"
                 @mousedown="startDrag($event)"
                 @mousemove="onDrag($event)"
                 @mouseup="endDrag()"
                 @mouseleave="endDrag()"
                 @touchstart="startDrag($event)"
                 @touchmove="onDrag($event)"
                 @touchend="endDrag()"
                 @dblclick="toggleDoubleZoom()">
                
                <img :src="lightboxImg" 
                     :alt="lightboxTitle" 
                     class="max-w-full max-h-[75vh] object-contain transition-transform duration-100 select-none"
                     :style="`transform: translate3d(${panX}px, ${panY}px, 0) scale(${zoomLevel}); cursor: ${zoomLevel > 1 ? (isDragging ? 'grabbing' : 'grab') : 'zoom-in'}`">
                
                {{-- Zoom Controls Overlay --}}
                <div class="absolute bottom-4 right-4 flex items-center gap-1.5 p-1.5 rounded-xl bg-slate-900/90 backdrop-blur-md border border-slate-700/80 shadow-lg text-white">
                    <button @click="zoomOut()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-sm font-bold transition" title="Zoom Out">-</button>
                    <span class="text-xs font-mono font-bold px-1.5" x-text="`${Math.round(zoomLevel * 100)}%`"></span>
                    <button @click="zoomIn()" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-sm font-bold transition" title="Zoom In">+</button>
                    <button @click="resetZoom()" class="px-2 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-xs font-semibold transition" title="Reset Zoom">Reset</button>
                </div>
            </div>

            <div class="md:w-1/3 p-6 sm:p-8 flex flex-col justify-between text-white border-t md:border-t-0 md:border-l border-slate-800">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-2.5 py-1 rounded-full bg-terra-500/20 text-terra-300 border border-terra-500/30 text-[10px] font-bold">
                            Live Project Photo
                        </span>
                        <button @click="lightboxOpen = false" class="text-slate-400 hover:text-white text-lg font-bold">✕</button>
                    </div>

                    <h3 class="text-xl font-black mb-2" x-text="lightboxTitle"></h3>
                    <p class="text-xs text-slate-300 mb-4" x-text="lightboxDesc"></p>

                    <div class="space-y-2 pt-4 border-t border-slate-800 text-xs text-slate-400">
                        <div>
                            <span class="block text-[10px] text-slate-400 font-bold uppercase">Location:</span>
                            <strong class="text-slate-200" x-text="lightboxLocation"></strong>
                        </div>
                        <div>
                            <span class="block text-[10px] text-slate-400 font-bold uppercase">Pattern Specified:</span>
                            <strong class="text-terra-400" x-text="lightboxPattern"></strong>
                        </div>
                    </div>
                </div>

                <div class="pt-6">
                    <a :href="`https://wa.me/{{ $waNumber }}?text=${encodeURIComponent('Hello IndoRoster Export Desk, I saw this project in your gallery: ' + lightboxTitle + ' (' + lightboxLocation + '). I would like to inquire about this pattern.')}`" target="_blank" rel="noopener noreferrer" class="w-full py-3 rounded-xl bg-terra-500 hover:bg-terra-400 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-lg shadow-terra-500/25 transition">
                        <span>Inquire This Pattern (WhatsApp) &rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
