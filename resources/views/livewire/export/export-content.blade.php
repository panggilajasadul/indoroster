@php
    $h1          = $config['h1'] ?? 'Breeze Blocks Export';
    $intro       = $config['intro'] ?? '';
    $keywords    = $config['keywords'] ?? [];
    $faqs        = $config['faqs'] ?? [];
    $howTo       = $config['how_to'] ?? null;
    $relCountries= $config['related_countries'] ?? [];
    $relSlugs    = $config['related_slugs'] ?? [];
    $canonical   = $config['canonical'] ?? ('/export/'.$contentSlug);

    // ── JSON-LD: BreadcrumbList ──
    $breadcrumbSchema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'International Export', 'item' => url('/export')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $h1, 'item' => url($canonical)],
        ],
    ];

    // ── JSON-LD: FAQPage (AEO — Google AI Overviews / Bing Copilot / ChatGPT) ──
    $faqSchema = null;
    if (!empty($faqs)) {
        $faqSchema = [
            '@context'   => 'https://schema.org',
            '@type'      => 'FAQPage',
            'mainEntity' => array_map(fn($f) => [
                '@type'         => 'Question',
                'name'          => $f['q'],
                'acceptedAnswer'=> ['@type' => 'Answer', 'text' => $f['a']],
            ], $faqs),
        ];
    }

    // ── JSON-LD: HowTo (GEO — step-by-step untuk AI engine) ──
    $howToSchema = null;
    if (!empty($howTo)) {
        $howToSchema = [
            '@context' => 'https://schema.org',
            '@type'    => 'HowTo',
            'name'     => $howTo['name'],
            'step'     => array_map(fn($s) => [
                '@type' => 'HowToStep',
                'name'  => $s['name'],
                'text'  => $s['text'],
            ], $howTo['steps']),
        ];
    }

    // ── JSON-LD: Service ──
    $serviceSchema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Service',
        'name'        => $h1,
        'serviceType' => 'Export Supply of Architectural Concrete Blocks',
        'provider'    => [
            '@type' => 'Organization',
            'name'  => 'IndoRoster',
            'url'   => route('home'),
            'logo'  => asset('assets/logo_indoroster_no_text.PNG'),
        ],
        'areaServed'  => 'Worldwide',
        'description' => $intro,
    ];

    // ── Nama negara yang bisa ditampilkan dari slug ──
    $countryLabels = [
        'singapore' => 'Singapore 🇸🇬', 'malaysia' => 'Malaysia 🇲🇾', 'brunei' => 'Brunei 🇧🇳',
        'uae' => 'UAE 🇦🇪', 'saudi-arabia' => 'Saudi Arabia 🇸🇦', 'qatar' => 'Qatar 🇶🇦',
        'kuwait' => 'Kuwait 🇰🇼', 'oman' => 'Oman 🇴🇲', 'bahrain' => 'Bahrain 🇧🇭',
        'australia' => 'Australia 🇦🇺', 'new-zealand' => 'New Zealand 🇳🇿',
        'uk' => 'United Kingdom 🇬🇧', 'usa' => 'United States 🇺🇸', 'canada' => 'Canada 🇨🇦',
        'spain' => 'Spain 🇪🇸', 'france' => 'France 🇫🇷', 'portugal' => 'Portugal 🇵🇹',
        'greece' => 'Greece 🇬🇷', 'italy' => 'Italy 🇮🇹', 'germany' => 'Germany 🇩🇪',
        'brazil' => 'Brazil 🇧🇷', 'india' => 'India 🇮🇳', 'japan' => 'Japan 🇯🇵',
        'maldives' => 'Maldives 🇲🇻', 'mauritius' => 'Mauritius 🇲🇺', 'fiji' => 'Fiji 🇫🇯',
        'french-polynesia' => 'French Polynesia 🇵🇫', 'thailand' => 'Thailand 🇹🇭',
        'philippines' => 'Philippines 🇵🇭', 'vietnam' => 'Vietnam 🇻🇳',
        'morocco' => 'Morocco 🇲🇦', 'algeria' => 'Algeria 🇩🇿', 'jordan' => 'Jordan 🇯🇴',
    ];

    // ── Label nama konten ──
    $contentLabels = [
        'breeze-blocks'          => 'Breeze Blocks',
        'concrete-screen-blocks' => 'Concrete Screen Blocks',
        'ventilation-blocks'     => 'Ventilation Blocks',
        'mashrabiya'             => 'Mashrabiya',
        'decorative-blocks'      => 'Decorative Blocks',
        'screen-walls'           => 'Screen Walls',
        'cobogo-blocks'          => 'Cobogó Blocks',
        'claustra-blocks'        => 'Claustra Blocks',
        'for-villas'             => 'For Villas',
        'for-hotels'             => 'For Hotels',
        'for-resorts'            => 'For Resorts',
        'for-commercial'         => 'For Commercial',
        'wholesale'              => 'Wholesale',
        'request-quote'          => 'Request Quote',
        'fob-shipping'           => 'FOB Shipping Guide',
    ];
@endphp

{{-- ── STRUCTURED DATA (SEO / AEO / GEO) ── --}}
@push('seo')
    {{-- BreadcrumbList --}}
    <script type="application/ld+json">
    {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    {{-- FAQPage — untuk Google AI Overviews, Bing Copilot (AEO) --}}
    @if($faqSchema)
    <script type="application/ld+json">
    {!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @endif

    {{-- HowTo — untuk GEO step-by-step AI synthesis --}}
    @if($howToSchema)
    <script type="application/ld+json">
    {!! json_encode($howToSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @endif

    {{-- Service schema --}}
    <script type="application/ld+json">
    {!! json_encode($serviceSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    {{-- hreflang (x-default + en) untuk signal multi-market --}}
    <link rel="alternate" hreflang="en" href="{{ url($canonical) }}" />
    <link rel="alternate" hreflang="x-default" href="{{ url('/export') }}" />
@endpush

<div class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 selection:bg-terra-500 selection:text-white transition-colors duration-300">

    {{-- ════════════════════════════════════════════════
         HERO BANNER
    ════════════════════════════════════════════════ --}}
    <div class="relative overflow-hidden bg-slate-900 text-white py-14 sm:py-20 px-4">
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute -right-32 -top-32 w-96 h-96 bg-terra-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -left-32 -bottom-32 w-96 h-96 bg-emerald-500/8 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 max-w-5xl mx-auto">
            {{-- Breadcrumb --}}
            <nav class="flex items-center flex-wrap gap-1.5 text-xs text-slate-400 mb-6 font-medium" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                <span>/</span>
                <a href="{{ url('/export') }}" class="hover:text-white transition">Export</a>
                <span>/</span>
                <span class="text-terra-400 font-bold">{{ $contentLabels[$contentSlug] ?? ucwords(str_replace('-', ' ', $contentSlug)) }}</span>
            </nav>

            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-800/90 border border-slate-700 text-terra-400 text-xs font-bold uppercase tracking-wider mb-5">
                <span class="w-2 h-2 rounded-full bg-terra-500 animate-pulse"></span>
                IndoRoster — Factory Direct Export · 110 Destinations · FOB Jakarta
            </div>

            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight mb-5 text-white max-w-4xl">
                {{ $h1 }}
            </h1>

            <p class="text-sm sm:text-base lg:text-lg text-slate-300 leading-relaxed max-w-3xl mb-8">
                {!! $intro !!}
            </p>

            {{-- Keywords as semantic tags (hidden from user, visible to crawler) --}}
            @if(!empty($keywords))
            <div class="sr-only" aria-hidden="false">
                <span>Related terms: </span>
                @foreach($keywords as $kw)
                    <span>{{ $kw }}{{ !$loop->last ? ', ' : '.' }}</span>
                @endforeach
            </div>
            @endif

            {{-- CTAs --}}
            <div class="flex flex-wrap items-center gap-3">
                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hello IndoRoster Export Desk, I am interested in your '.$h1.'. Please send me your export catalog and FOB quotation.') }}"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2.5 px-7 py-3.5 rounded-xl bg-terra-500 hover:bg-terra-400 text-white font-bold text-sm shadow-xl shadow-terra-500/25 transition-all hover:scale-105">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    <span>WhatsApp Export Desk</span>
                </a>
                <a href="{{ url('/export/catalog') }}"
                   class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-slate-800/90 hover:bg-slate-700 text-slate-200 font-semibold text-sm border border-slate-700 transition">
                    📄 View Export Catalog
                </a>
                <a href="{{ url('/export') }}"
                   class="inline-flex items-center gap-2 px-6 py-3.5 rounded-xl bg-slate-900/60 hover:bg-slate-800 text-slate-300 font-semibold text-sm border border-slate-700/80 transition">
                    🌍 All 110 Countries
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 space-y-16">

        {{-- ════════════════════════════════════════════════
             KEYWORD PILLS — SEO Signal
        ════════════════════════════════════════════════ --}}
        @if(!empty($keywords))
        <div>
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Related search terms</p>
            <div class="flex flex-wrap gap-2">
                @foreach($keywords as $kw)
                    <span class="px-3 py-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-medium rounded-full border border-slate-200/70 dark:border-slate-700">{{ $kw }}</span>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ════════════════════════════════════════════════
             FAQ — AEO (Answer Engine Optimization)
        ════════════════════════════════════════════════ --}}
        @if(!empty($faqs))
        <div id="faq" class="scroll-mt-20">
            <div class="mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">Frequently Asked Questions</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1">
                    {{ $contentLabels[$contentSlug] ?? ucwords(str_replace('-', ' ', $contentSlug)) }} — Expert Q&A
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Answers optimised for Google AI Overviews, Bing Copilot, and ChatGPT web search.</p>
            </div>

            <div class="space-y-4" itemscope itemtype="https://schema.org/FAQPage">
                @foreach($faqs as $i => $faq)
                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm overflow-hidden"
                     x-data="{ open: {{ $i === 0 ? 'true' : 'false' }} }"
                     itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
                    <button @click="open = !open"
                            class="w-full flex items-center justify-between gap-4 px-6 py-4 text-left font-semibold text-slate-900 dark:text-white text-sm sm:text-base hover:bg-slate-50 dark:hover:bg-slate-800/60 transition"
                            :aria-expanded="open">
                        <span itemprop="name">{{ $faq['q'] }}</span>
                        <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 text-terra-500 shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse
                         class="px-6 pb-5 text-sm text-slate-600 dark:text-slate-300 leading-relaxed"
                         itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                        <p itemprop="text">{{ $faq['a'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ════════════════════════════════════════════════
             HOW-TO STEPS — GEO (Generative Engine Optimization)
        ════════════════════════════════════════════════ --}}
        @if(!empty($howTo))
        <div id="how-to" class="scroll-mt-20">
            <div class="mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Step-by-Step Process</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1">{{ $howTo['name'] }}</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Optimised for AI synthesis by Google, Bing Copilot, and ChatGPT.</p>
            </div>

            <div class="relative" itemscope itemtype="https://schema.org/HowTo">
                <meta itemprop="name" content="{{ $howTo['name'] }}">
                <div class="space-y-4">
                    @foreach($howTo['steps'] as $si => $step)
                    <div class="flex gap-5 items-start p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm"
                         itemscope itemprop="step" itemtype="https://schema.org/HowToStep">
                        <div class="shrink-0 w-9 h-9 rounded-full bg-terra-500 text-white flex items-center justify-center font-black text-sm">
                            {{ $si + 1 }}
                        </div>
                        <div>
                            <strong class="block font-bold text-slate-900 dark:text-white text-sm mb-1" itemprop="name">{{ $step['name'] }}</strong>
                            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed" itemprop="text">{{ $step['text'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- ════════════════════════════════════════════════
             PRODUCT GALLERY
        ════════════════════════════════════════════════ --}}
        @if($featuredProducts->isNotEmpty())
        <div>
            <div class="mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Export Product Catalog</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1">
                    Available for Export — View Catalog
                </h2>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($featuredProducts as $product)
                @php $pm = $product->media->first(); @endphp
                <a href="{{ route('product.detail', $product->slug) }}" class="group block rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    @if($pm)
                    <div class="aspect-square overflow-hidden bg-slate-100 dark:bg-slate-800">
                        <img src="{{ $pm->media_url }}"
                             alt="{{ $pm->alt_text ?: $product->name }} — Export Product IndoRoster"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             loading="lazy">
                    </div>
                    @endif
                    <div class="p-3">
                        <p class="text-xs font-bold text-slate-900 dark:text-white line-clamp-2 leading-tight">{{ $product->name }}</p>
                        @if($product->category)
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">{{ $product->category->name }}</p>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
            <div class="text-center mt-6">
                <a href="{{ url('/export/catalog') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-terra-500 hover:bg-terra-400 text-white font-bold text-sm shadow-lg transition-all hover:scale-105">
                    View Full Export Catalog ({{ $featuredProducts->count() }}+ Products)
                </a>
            </div>
        </div>
        @endif

        {{-- ════════════════════════════════════════════════
             RELATED COUNTRIES (Internal Links — GEO Signals)
        ════════════════════════════════════════════════ --}}
        @if(!empty($relCountries))
        <div class="bg-slate-100 dark:bg-slate-900/50 rounded-3xl p-8 sm:p-10 border border-slate-200/70 dark:border-slate-800">
            <div class="mb-6">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Export Destinations</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mt-1">
                    We Export to These Countries
                </h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Click a country for specific shipping info, transit times, and port details.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                @foreach($relCountries as $cSlug)
                <a href="{{ url('/export/'.$cSlug) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 text-sm font-semibold hover:border-terra-400 hover:text-terra-600 dark:hover:text-terra-400 transition-all shadow-sm hover:shadow-md">
                    {{ $countryLabels[$cSlug] ?? ucwords(str_replace('-', ' ', $cSlug)) }}
                </a>
                @endforeach
                <a href="{{ url('/export') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-terra-500/10 border border-terra-400/30 text-terra-600 dark:text-terra-400 text-sm font-semibold hover:bg-terra-500/20 transition">
                    🌍 All 110 Countries →
                </a>
            </div>
        </div>
        @endif

        {{-- ════════════════════════════════════════════════
             RELATED CONTENT PAGES (Internal Links)
        ════════════════════════════════════════════════ --}}
        @if(!empty($relSlugs))
        <div>
            <div class="mb-6">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Related Export Topics</span>
                <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mt-1">Explore More Export Information</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($relSlugs as $rSlug)
                <a href="{{ url('/export/'.$rSlug) }}"
                   class="block p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
                    <span class="block text-2xl mb-2">🧱</span>
                    <strong class="block font-bold text-slate-900 dark:text-white text-sm group-hover:text-terra-600 dark:group-hover:text-terra-400 transition">
                        {{ $contentLabels[$rSlug] ?? ucwords(str_replace('-', ' ', $rSlug)) }}
                    </strong>
                    <span class="text-xs text-terra-500 font-semibold mt-1 block">Learn more →</span>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ════════════════════════════════════════════════
             CTA FOOTER BLOCK
        ════════════════════════════════════════════════ --}}
        <div class="relative rounded-3xl overflow-hidden bg-slate-900 text-white p-8 sm:p-12 shadow-2xl border border-slate-800 text-center">
            <div class="absolute -right-20 -top-20 w-72 h-72 bg-terra-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <h2 class="text-2xl sm:text-3xl font-black mb-3 relative z-10">Ready to Place Your Export Order?</h2>
            <p class="text-slate-300 text-sm sm:text-base mb-7 max-w-xl mx-auto relative z-10">
                Contact our Export Desk via WhatsApp for a free FOB quotation, sample pack, or container delivery schedule.
                Response guaranteed within 24 business hours.
            </p>
            <div class="flex flex-wrap justify-center gap-3 relative z-10">
                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hello IndoRoster, I want to inquire about your '.$h1.' export. Please send me your price list and catalog.') }}"
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2.5 px-8 py-4 rounded-xl bg-terra-500 hover:bg-terra-400 text-white font-bold text-sm shadow-xl shadow-terra-500/25 transition-all hover:scale-105">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    WhatsApp Export Desk
                </a>
                <a href="{{ url('/export/catalog') }}"
                   class="inline-flex items-center gap-2 px-6 py-4 rounded-xl bg-slate-800/90 hover:bg-slate-700 text-slate-200 font-semibold text-sm border border-slate-700 transition">
                    📄 Browse Full Catalog
                </a>
            </div>
        </div>

    </div>{{-- end max-w-7xl --}}
</div>
