@php
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => route('home'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'International Export',
                'item' => url('/export'),
            ],
        ],
    ];

    $serviceSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => 'International Breeze Blocks & Ventilation Blocks Exporter',
        'serviceType' => 'Export Supply of Architectural Screen Blocks and Ventilation Blocks',
        'provider' => [
            '@type' => 'Organization',
            'name' => 'IndoRoster',
            'url' => route('home'),
            'logo' => asset('assets/logo_indoroster_no_text.PNG'),
        ],
        'areaServed' => 'Worldwide (110 Export Destinations)',
        'description' => 'Official factory direct exporter of 90° precision steel-mould breeze blocks and concrete ventilation blocks to 110 countries worldwide.',
    ];
@endphp

@push('seo')
    <script type="application/ld+json">
    {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($serviceSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

<div class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 py-10 sm:py-16 selection:bg-terra-500 selection:text-white transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16 sm:space-y-20">
        
        {{-- ══════════════════════════════════════════════════════════════
             SECTION 01 — HERO BANNER
        ══════════════════════════════════════════════════════════════ --}}
        <div class="relative rounded-3xl overflow-hidden bg-slate-900 text-white p-8 sm:p-12 lg:p-16 shadow-2xl border border-slate-800">
            <div class="absolute -right-20 -top-20 w-96 h-96 bg-terra-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 max-w-4xl">
                <nav class="flex items-center gap-2 text-xs text-slate-400 mb-6 font-medium">
                    <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                    <span>/</span>
                    <span class="text-terra-400 font-bold">International Export Gateway</span>
                </nav>

                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-800/90 border border-slate-700 text-terra-400 text-xs font-bold uppercase tracking-wider mb-6">
                    <span class="w-2 h-2 rounded-full bg-terra-500 animate-pulse"></span>
                    Direct Factory Exporter — Global Sea Freight (110 Destinations)
                </div>

                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-tight mb-6 text-white">
                    Direct Factory Breeze Blocks & Screen Blocks Exporter
                </h1>

                <p class="text-base sm:text-lg text-slate-300 mb-8 leading-relaxed max-w-3xl">
                    IndoRoster supplies high-precision 90° steel-mould architectural breeze blocks, terracotta screen blocks, and natural dolomite ventilation blocks for contractors, developers, and architects across <strong>110 international destinations worldwide</strong>.
                </p>

                <div class="flex flex-wrap items-center gap-4">
                    <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hello IndoRoster Export Team, I am looking for Breeze Blocks export supply for our project. Please share your export catalog and quotation.') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2.5 px-8 py-4 rounded-xl bg-terra-500 hover:bg-terra-400 text-white font-bold text-sm sm:text-base shadow-xl shadow-terra-500/25 transition-all hover:scale-105">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        <span>WhatsApp Export Desk (+62 813-8970-9847)</span>
                    </a>
                    <a href="{{ route('export.gallery') }}" class="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl bg-slate-800/90 hover:bg-slate-750 text-slate-200 font-bold text-sm border border-slate-700 transition">
                        <span>📸 Architectural Projects (100+ Photos)</span>
                    </a>
                    <a href="https://drive.google.com/file/d/1wcBxdEv7yiytPlLSVE1ldl1rYpe0MHZZ/view?usp=drive_link" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl bg-slate-900/60 hover:bg-slate-800 text-slate-300 font-semibold text-sm border border-slate-700/80 transition">
                        <span>📥 PDF Catalog</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             SECTION 02 — THE COMMON RISKS OF INFERIOR SCREEN BLOCKS
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-red-50/70 dark:bg-red-950/20 rounded-3xl border border-red-200/80 dark:border-red-900/40 p-8 sm:p-10 shadow-soft-xs">
            <div class="max-w-3xl mb-6">
                <span class="text-xs font-bold uppercase tracking-wider text-red-600 dark:text-red-400">
                    The Import Risks You Must Avoid
                </span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1">
                    Why Cheap Wet-Cast Blocks Fail in Global Architectural Projects
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 mt-1">
                    Specifying low-grade artisanal breeze blocks often leads to expensive structural defects and client complaints:
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-xs not-prose">
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-red-100 dark:border-red-900/30 shadow-soft-xs">
                    <div class="text-2xl mb-2">❌</div>
                    <strong class="text-slate-900 dark:text-white block font-bold mb-1">Wavy Alignment & Thick Mortar</strong>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Inconsistent manual moulds lack 90° precision, forcing masons to use thick 10–15mm mortar joints that destroy modern facade lines.
                    </p>
                </div>
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-red-100 dark:border-red-900/30 shadow-soft-xs">
                    <div class="text-2xl mb-2">💥</div>
                    <strong class="text-slate-900 dark:text-white block font-bold mb-1">Transit Breakage & Cracking</strong>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Weak wet-cast cement crumbles under ocean freight vibrations and rapid temperature swings between container ships and desert/tropical sites.
                    </p>
                </div>
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-red-100 dark:border-red-900/30 shadow-soft-xs">
                    <div class="text-2xl mb-2">🦠</div>
                    <strong class="text-slate-900 dark:text-white block font-bold mb-1">Peeling Paint & Black Mould</strong>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Synthetic spray paints peel off within months under UV radiation, while high-porosity cement traps moisture and fosters black algae.
                    </p>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             SECTION 03 — 110 TARGET COUNTRY DESTINATIONS HUB
        ══════════════════════════════════════════════════════════════ --}}
        <div>
            <div class="text-center max-w-3xl mx-auto mb-10">
                <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">Target Destinations</span>
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white mt-1">
                    Select Your Country Destination Hub
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-2">
                    Tailored pricing, sea port transit details, and local architectural specifications across 110 countries.
                </p>
            </div>

            {{-- Regional Filter Tabs --}}
            <div class="flex items-center justify-center gap-2 overflow-x-auto pb-4 mb-8 no-scrollbar max-w-5xl mx-auto">
                <button wire:click="$set('selectedRegion', '')" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition {{ empty($selectedRegion) ? 'bg-terra-500 text-white shadow-md shadow-terra-500/20' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:border-terra-500' }}">
                    All Regions (110 Destinations)
                </button>
                @foreach($regions as $reg)
                <button wire:click="$set('selectedRegion', '{{ $reg }}')" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition {{ $selectedRegion === $reg ? 'bg-terra-500 text-white shadow-md shadow-terra-500/20' : 'bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 hover:border-terra-500' }}">
                    {{ $reg }}
                </button>
                @endforeach
            </div>

            {{-- Country Cards Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @foreach($countries as $c)
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-8 flex flex-col justify-between hover:border-terra-500 hover:shadow-xl transition-all duration-300 group">
                    <div>
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <span class="text-4xl">{{ $c['flag'] }}</span>
                            <span class="px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs font-bold border border-slate-200 dark:border-slate-700">
                                {{ $c['lang'] }}
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-black text-slate-900 dark:text-white group-hover:text-terra-600 dark:group-hover:text-terra-400 transition-colors mb-2">
                            {{ $c['name'] }}
                        </h3>

                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-6">
                            {{ $c['desc'] }}
                        </p>

                        <div class="space-y-2.5 pt-4 border-t border-slate-100 dark:border-slate-800 text-xs mb-6">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Pricing / Rate:</span>
                                <strong class="text-terra-600 dark:text-terra-400 font-bold">Wholesale FOB on Request</strong>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Destination Port:</span>
                                <strong class="text-slate-800 dark:text-slate-200">{{ $c['port'] }}</strong>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Transit Time:</span>
                                <strong class="text-slate-800 dark:text-slate-200">{{ $c['transit_time'] }}</strong>
                            </div>
                        </div>
                    </div>

                    <a href="{{ url('/export/' . $c['slug']) }}" class="w-full py-3 px-4 rounded-xl bg-slate-900 hover:bg-terra-600 dark:bg-terra-500 dark:hover:bg-terra-400 text-white text-xs font-bold flex items-center justify-center gap-2 transition-all shadow-md">
                        <span>View {{ $c['name'] }} Dedicated Hub</span>
                        <span>&rarr;</span>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             SECTION 04 — SEA FREIGHT & CONTAINER LOADING SPECS
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-8 sm:p-12 shadow-soft-xs space-y-8">
            <div class="max-w-3xl">
                <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">Sea Freight Logistics</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1">
                    Container Capacity & Export Packaging Specifications
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Direct container dispatch from Tanjung Priok Port (Jakarta) with heavy-duty export palletized crates.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 not-prose">
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                    <div class="text-3xl mb-3">🚢</div>
                    <h4 class="font-extrabold text-slate-900 dark:text-white text-base mb-1">20ft Container (FCL)</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Capacity: <strong>approx. 2,500 – 3,000 pcs</strong> (±12–14 metric tons). Suitable for luxury landed bungalows, cafes, and residential facades.
                    </p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                    <div class="text-3xl mb-3">🚢</div>
                    <h4 class="font-extrabold text-slate-900 dark:text-white text-base mb-1">40ft Container (FCL)</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Capacity: <strong>approx. 4,500 – 5,500 pcs</strong> (±22–26 metric tons). Optimal cost-efficiency for large developer projects, hotels, and condominiums.
                    </p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                    <div class="text-3xl mb-3">📦</div>
                    <h4 class="font-extrabold text-slate-900 dark:text-white text-base mb-1">Heavy-Duty Crate Packing</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Reinforced wooden pallets, foam/straw cushioning, corner protectors, heavy-duty strapping bands, and multi-layer waterproof shrink wrap.
                    </p>
                </div>
            </div>

            <div class="p-6 rounded-2xl bg-emerald-50/70 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">📄</span>
                    <div>
                        <h4 class="font-bold text-sm text-slate-900 dark:text-white">Certificate of Origin & Customs Documentation</h4>
                        <p class="text-xs text-slate-600 dark:text-slate-300 mt-0.5">We provide Form D / Certificate of Origin to facilitate preferential tariff and seamless customs clearance.</p>
                    </div>
                </div>
                <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hello IndoRoster Export Desk, I would like to inquire about export documentation and sea freight rates.') }}" target="_blank" rel="noopener noreferrer" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-1.5 shadow-sm transition flex-shrink-0">
                    <span>Contact Export Desk &rarr;</span>
                </a>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             SECTION 05 — THE HERITAGE & CRAFTSMANSHIP NARRATIVE
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-slate-900 rounded-3xl border border-slate-800 p-8 sm:p-12 text-white shadow-2xl relative overflow-hidden space-y-8">
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-terra-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 max-w-4xl">
                <span class="text-xs font-bold uppercase tracking-wider text-terra-400">
                    Heritage of Indonesian Stonemasonry
                </span>
                <h2 class="text-2xl sm:text-4xl font-black mt-2 mb-4 leading-tight">
                    Centenary Indonesian Craftsmanship Meets Industrial Steel Precision
                </h2>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed mb-6">
                    Behind every IndoRoster breeze block lies the deep-rooted heritage of Plered, Purwakarta — Indonesia’s world-renowned artisan pottery and stonemasonry hub active since the early 1900s. We combine time-honored semi-dry compaction techniques with razor-sharp laser-cut steel moulds, giving every piece an authentic human touch backed by industrial structural consistency.
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs pt-4 border-t border-slate-800">
                    <div>
                        <strong class="text-terra-400 block text-lg font-black">100+ Yrs</strong>
                        <span class="text-slate-300 text-[11px]">Plered Craft Heritage</span>
                    </div>
                    <div>
                        <strong class="text-terra-400 block text-lg font-black">&lt; 1 mm</strong>
                        <span class="text-slate-300 text-[11px]">Steel Mould Tolerance</span>
                    </div>
                    <div>
                        <strong class="text-terra-400 block text-lg font-black">40%</strong>
                        <span class="text-slate-300 text-[11px]">Passive Solar Cooling</span>
                    </div>
                    <div>
                        <strong class="text-terra-400 block text-lg font-black">110</strong>
                        <span class="text-slate-300 text-[11px]">Global Export Destinations</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             SECTION 06 — PRODUCT ANATOMY, FUNCTION & NATURAL MATERIAL GUIDE
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-8 sm:p-12 shadow-soft-xs space-y-10">
            <div class="max-w-3xl">
                <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">
                    Product Knowledge & Architectural Guide
                </span>
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white mt-1 leading-tight">
                    What is a Breeze Block? Purpose, Architectural Benefits & Natural Materials
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                    More than just a perforated masonry unit, architectural breeze blocks serve as a sustainable secondary skin facade that naturally cools buildings while creating dynamic light-and-shadow artistry.
                </p>
            </div>

            {{-- 4 Core Functions Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 not-prose">
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl mb-3 font-bold">🌬️</div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-1.5">Passive Cross-Ventilation</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Allows natural breezes to flow freely through rooms, eliminating humidity and stuffy air without mechanical energy.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl mb-3 font-bold">☀️</div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-1.5">40% Solar Heat Reduction</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Acts as a protective thermal buffer (Brise-Soleil / Mashrabiya) that deflects harsh glare and cuts air conditioning electricity bills.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl mb-3 font-bold">🛡️</div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-1.5">Open-Air Privacy Screen</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Occupants can clearly look outside while blocking direct visual sightlines from the street, ideal for pool and balcony enclosures.
                    </p>
                </div>

                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl mb-3 font-bold">✨</div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-1.5">Dynamic Light Artistry</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                        Casts mesmerizing geometric shadows throughout the day that shift with the sun, giving luxury facades a living, sculpted identity.
                    </p>
                </div>
            </div>

            {{-- 3 Authentic Natural Material Variants Guide --}}
            <div class="pt-8 border-t border-slate-200/80 dark:border-slate-800">
                <div class="mb-6">
                    <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">
                        100% Solid Natural Mineral Aggregates (Zero Spray Paint)
                    </span>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mt-1">
                        3 Authentic Material Finishes Available
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 not-prose">
                    {{-- 1. Raw Grey Sand Cement --}}
                    <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2.5 py-1 rounded-md bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200 text-[10px] font-black uppercase tracking-wider">Raw Grey</span>
                                <span class="text-xs text-slate-500 font-semibold">20×20×10 cm</span>
                            </div>
                            <h4 class="text-base font-black text-slate-900 dark:text-white mb-2">
                                Natural Mountain Stone Ash (Raw Grey)
                            </h4>
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                                Formulated from pure mountain stone aggregate and dense hydraulic cement. Features a bold, timeless industrial concrete hue favored for Brutalist architecture, urban lofts, and modern tropical minimalist facades.
                            </p>
                        </div>
                        <div class="pt-3 border-t border-slate-200/80 dark:border-slate-800 text-[11px] text-slate-500 dark:text-slate-400">
                            <strong>Best For:</strong> Industrial cafes, brutalist walls, modern tropical carports.
                        </div>
                    </div>

                    {{-- 2. Natural Milky White / Cream Dolomite --}}
                    <div class="p-6 rounded-2xl bg-amber-50/50 dark:bg-amber-950/20 border border-amber-200/70 dark:border-amber-900/40 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2.5 py-1 rounded-md bg-amber-100 dark:bg-amber-900/60 text-amber-900 dark:text-amber-200 text-[10px] font-black uppercase tracking-wider">Milky White / Cream</span>
                                <span class="text-xs text-amber-700 dark:text-amber-400 font-semibold">Anti-Algae Formula</span>
                            </div>
                            <h4 class="text-base font-black text-slate-900 dark:text-white mb-2">
                                Natural Milky White / Cream Dolomite Stone
                            </h4>
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                                Crafted from pure natural white dolomite mountain stone. Yields an elegant soft milky white to warm cream mineral tone depending on geological extraction. Highly resistant to coastal black algae, reflects solar heat, and ages with a pristine natural patina.
                            </p>
                        </div>
                        <div class="pt-3 border-t border-amber-200/60 dark:border-amber-900/40 text-[11px] text-slate-500 dark:text-slate-400">
                            <strong>Best For:</strong> Mediterranean villas, Palm Springs pool screens, luxury resorts.
                        </div>
                    </div>

                    {{-- 3. Plered Terracotta Red Clay --}}
                    <div class="p-6 rounded-2xl bg-orange-50/50 dark:bg-orange-950/20 border border-orange-200/70 dark:border-orange-900/40 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-2.5 py-1 rounded-md bg-orange-100 dark:bg-orange-900/60 text-orange-900 dark:text-orange-200 text-[10px] font-black uppercase tracking-wider">Terracotta Red</span>
                                <span class="text-xs text-orange-700 dark:text-orange-400 font-semibold">High-Fire Kiln</span>
                            </div>
                            <h4 class="text-base font-black text-slate-900 dark:text-white mb-2">
                                Authentic Plered High-Fire Terracotta
                            </h4>
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                                Made from selected Plered red clay and kiln-fired at high temperatures for optimal strength and porous breathability. Brings a warm, earthy terracotta charm reminiscent of Balearic summer houses, Bali resorts, and Spanish haciendas.
                            </p>
                        </div>
                        <div class="pt-3 border-t border-orange-200/60 dark:border-orange-900/40 text-[11px] text-slate-500 dark:text-slate-400">
                            <strong>Best For:</strong> Tropical resorts, rustic cafes, Spanish hacienda garden walls.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             SECTION 07 — PAYMENT METHODS & EXW (EX WORKS) TRADE TERMS
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-8 sm:p-12 shadow-soft-xs space-y-8">
            <div class="max-w-3xl">
                <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">
                    Trade Terms & Payment Security
                </span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1">
                    EXW (Ex Works) Factory Terms & Secure Payment Methods
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Transparent, safe, and internationally recognized manufacturing and procurement workflow.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 not-prose">
                {{-- EXW Trade Term Card --}}
                <div class="p-6 sm:p-8 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full bg-emerald-600/10 text-emerald-600 dark:text-emerald-400 text-xs font-black uppercase tracking-wider">Incoterms 2020: EXW (Ex Works)</span>
                            <span class="text-2xl">🏭</span>
                        </div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white mb-3">
                            Factory Direct Handover (Plered, West Java)
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                            Our primary international export agreement is EXW (Ex Works). IndoRoster is responsible for high-precision manufacturing, strict QC, heavy-duty ocean palletized crating, and loading onto your carrier vehicle at our factory gate.
                        </p>
                        <div class="p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-xs space-y-2">
                            <div class="flex items-start gap-2">
                                <span class="text-emerald-500 font-bold">✓</span>
                                <span class="text-slate-600 dark:text-slate-300"><strong>Factory Scope:</strong> Precision production, export pallet crating, and warehouse loading.</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <span class="text-blue-500 font-bold">ℹ</span>
                                <span class="text-slate-600 dark:text-slate-300"><strong>Buyer / Forwarder Scope:</strong> Inland trucking to port, customs export/import clearance, ocean sea freight, and marine cargo insurance.</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-4">
                        *You may use your preferred freight forwarder or we can connect you with trusted Indonesian ocean logistics partners.
                    </p>
                </div>

                {{-- Payment Methods & Milestone Card --}}
                <div class="p-6 sm:p-8 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full bg-blue-600/10 text-blue-600 dark:text-blue-400 text-xs font-black uppercase tracking-wider">Payment Channels</span>
                            <span class="text-2xl">🏦</span>
                        </div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white mb-3">
                            Accepted Payment Methods & Milestones
                        </h3>
                        <div class="space-y-3 text-xs mb-6">
                            <div class="p-3.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-3">
                                <div>
                                    <strong class="text-slate-900 dark:text-white block font-bold">1. International Telegraphic Transfer (T/T / Swift Wire)</strong>
                                    <span class="text-slate-500 text-[11px]">Direct bank wire transfer to our official corporate Indonesian bank account.</span>
                                </div>
                                <span class="px-2 py-1 rounded bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-600 dark:text-slate-300">USD / SGD / EUR</span>
                            </div>
                            <div class="p-3.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-3">
                                <div>
                                    <strong class="text-slate-900 dark:text-white block font-bold">2. Local Indonesian Bank Transfer (IDR)</strong>
                                    <span class="text-slate-500 text-[11px]">For local agents / representatives in Indonesia (BCA, Mandiri, BRI, BNI).</span>
                                </div>
                                <span class="px-2 py-1 rounded bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-600 dark:text-slate-300">IDR (Rupiah)</span>
                            </div>
                        </div>

                        {{-- Payment Milestones --}}
                        <div class="grid grid-cols-2 gap-3 text-xs">
                            <div class="p-3.5 rounded-xl bg-emerald-50/70 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800">
                                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-600 dark:text-emerald-400 block mb-1">Step 1 (Order Lock)</span>
                                <strong class="text-slate-900 dark:text-white text-base block font-black">50% Down Payment</strong>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Initiates precision steel mould fabrication & production schedule.</span>
                            </div>
                            <div class="p-3.5 rounded-xl bg-blue-50/70 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800">
                                <span class="text-[10px] font-black uppercase tracking-wider text-blue-600 dark:text-blue-400 block mb-1">Step 2 (Before Loading)</span>
                                <strong class="text-slate-900 dark:text-white text-base block font-black">50% Balance Payment</strong>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Paid upon QC inspection approval before factory dispatch.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             SECTION 08 — CURATED POPULAR EXPORT PRODUCTS PREVIEW
        ══════════════════════════════════════════════════════════════ --}}
        <div class="space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">
                        Popular Export Motifs & Patterns
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        High-demand 20×20×10 cm modular breeze blocks with 90° precision steel moulds.
                    </p>
                </div>
                <a href="{{ route('export.catalog') }}" class="text-xs font-bold text-terra-600 dark:text-terra-400 hover:underline">
                    Explore Full 45+ Export Patterns (No Prices) &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
                @foreach($featuredProducts as $p)
                @php
                    $imgUrl = $p->primary_image ?: asset('assets/logo_indoroster_no_text.PNG');
                    $expWaUrl = "https://wa.me/{$waNumber}?text=" . urlencode("Hello IndoRoster, I am interested in export quotation for motif: {$p->name} for international project.");
                @endphp
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 flex flex-col justify-between hover:border-terra-400 dark:hover:border-terra-500/80 shadow-soft-xs hover:shadow-soft-lg transition group">
                    <div>
                        <div class="aspect-square bg-slate-50 dark:bg-slate-950 rounded-xl overflow-hidden p-2 flex items-center justify-center mb-3 relative border border-slate-100 dark:border-slate-800">
                            <img src="{{ $imgUrl }}" alt="{{ $p->name }}" class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-300" loading="lazy">
                            <span class="absolute top-2 left-2 px-1.5 py-0.5 rounded bg-slate-900/90 text-white text-[9px] font-bold">
                                20×20×10 cm
                            </span>
                        </div>
                        <h3 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white line-clamp-1 mb-1 group-hover:text-terra-600 dark:group-hover:text-terra-400 transition-colors">
                            {{ $p->name }}
                        </h3>
                        <span class="text-[10px] text-slate-400 block mb-3">Pure Aggregate / Siku 90°</span>
                    </div>
                    <a href="{{ $expWaUrl }}" target="_blank" rel="noopener noreferrer" class="w-full py-2.5 rounded-xl bg-terra-500 hover:bg-terra-400 text-white text-xs font-bold flex items-center justify-center gap-1 shadow-md transition">
                        <span>Request Export Quote</span>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
