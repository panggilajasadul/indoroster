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
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => 'Export Patterns Catalog',
                'item' => url('/export/catalog'),
            ],
        ],
    ];
@endphp

@push('seo')
    <script type="application/ld+json">
    {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="relative rounded-3xl overflow-hidden bg-slate-900 text-white p-8 sm:p-12 shadow-2xl mb-12 border border-slate-800">
            <div class="absolute -right-20 -top-20 w-96 h-96 bg-terra-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 max-w-3xl">
                <nav class="flex items-center gap-2 text-xs text-slate-400 mb-4 font-medium">
                    <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                    <span>/</span>
                    <a href="{{ url('/export') }}" class="hover:text-white transition">Export Hub</a>
                    <span>/</span>
                    <span class="text-terra-400 font-bold">Export Catalog</span>
                </nav>

                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800 text-terra-400 border border-slate-700 text-xs font-bold uppercase tracking-wider mb-4">
                    <span class="w-2 h-2 rounded-full bg-terra-500 animate-pulse"></span>
                    45+ Modular Patterns Collection
                </div>

                <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight mb-4 text-white">
                    Architectural Breeze Blocks Export Catalog
                </h1>

                <p class="text-sm sm:text-base text-slate-300 mb-6 leading-relaxed">
                    Explore all 45+ modular architectural ventilation blocks manufactured with 90° precision steel moulds. Available in Natural Raw Grey Sand Cement, Natural Milky White / Cream Dolomite, and Plered Red Terracotta.
                </p>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hello IndoRoster Export Team, I am browsing your International Export Catalog and would like to request an itemized quotation for container shipment.') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-terra-500 hover:bg-terra-400 text-white font-bold text-xs sm:text-sm shadow-lg shadow-terra-500/25 transition">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        <span>Request Export Quotation (WhatsApp)</span>
                    </a>
                    <a href="{{ route('export.gallery') }}" class="inline-flex items-center gap-1.5 px-5 py-3 rounded-xl bg-slate-800/90 hover:bg-slate-750 text-slate-200 font-bold text-xs border border-slate-700 transition">
                        <span>📸 View Project Gallery</span>
                    </a>
                    <a href="https://drive.google.com/file/d/1wcBxdEv7yiytPlLSVE1ldl1rYpe0MHZZ/view?usp=drive_link" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-5 py-3 rounded-xl bg-slate-900/60 hover:bg-slate-800 text-slate-300 font-semibold text-xs border border-slate-700/80 transition">
                        <span>📥 Download PDF Catalog</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Filter & Search Toolbar --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 sm:p-6 mb-10 shadow-soft-xs">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                {{-- Category Tabs --}}
                <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 no-scrollbar">
                    <button wire:click="$set('selectedCategory', '')" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition {{ empty($selectedCategory) ? 'bg-terra-500 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                        All Patterns (45+)
                    </button>
                    @foreach($categories as $cat)
                    <button wire:click="$set('selectedCategory', '{{ $cat->slug }}')" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition {{ $selectedCategory === $cat->slug ? 'bg-terra-500 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                        {{ $cat->name }}
                    </button>
                    @endforeach
                </div>

                {{-- Search Input --}}
                <div class="w-full md:w-64">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search motif name / code..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500 focus:outline-none">
                </div>
            </div>
        </div>

        {{-- Products Grid (Pure Architectural Showcase — No Prices) --}}
        @if($products->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 mb-12">
            @foreach($products as $product)
                @php
                    $displayMedia = $product->primary_media;
                    $imgUrl = ($displayMedia && $displayMedia->media_type === 'image') 
                        ? $displayMedia->formatted_url 
                        : ($product->primary_image ?: asset('assets/logo_indoroster_no_text.PNG'));
                    $itemWaUrl = "https://wa.me/{$waNumber}?text=" . urlencode("Hello IndoRoster Export Desk, I would like to request an export quote for: {$product->name} (SKU: " . ($product->sku ?: 'IR-STD') . ") with container shipping options.");
                    $activeVariants = $product->variants->where('is_active', true);
                @endphp

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 sm:p-5 flex flex-col justify-between hover:border-terra-400 dark:hover:border-terra-500/80 shadow-soft-xs hover:shadow-soft-lg transition-all group">
                    <div>
                        {{-- Product Thumbnail with Dimensions & SKU Tag --}}
                        <div class="aspect-square bg-slate-50 dark:bg-slate-950 rounded-xl overflow-hidden p-3 flex items-center justify-center mb-3 relative border border-slate-100 dark:border-slate-800/80">
                            <img src="{{ $imgUrl }}" alt="{{ $product->name }}" class="max-w-full max-h-full object-contain group-hover:scale-105 transition-transform duration-300" loading="lazy">
                            <span class="absolute top-2.5 left-2.5 px-2 py-0.5 rounded bg-slate-900/90 text-[10px] font-mono font-bold text-slate-200 border border-slate-700">
                                {{ $product->sku ?: 'IR-'.str_pad($product->id, 3, '0', STR_PAD_LEFT) }}
                            </span>
                            <span class="absolute top-2.5 right-2.5 px-2 py-0.5 rounded bg-terra-500 text-white text-[9px] font-bold">
                                25 pcs/m²
                            </span>
                        </div>

                        {{-- Category Tag & Title --}}
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

                            {{-- Material Variants Badges --}}
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

                    {{-- CTA Inquire Button --}}
                    <div class="pt-3 border-t border-slate-100 dark:border-slate-800">
                        <a href="{{ $itemWaUrl }}" target="_blank" rel="noopener noreferrer" class="w-full py-2.5 rounded-xl bg-terra-500 hover:bg-terra-400 text-white text-xs font-bold flex items-center justify-center gap-1.5 shadow-md transition">
                            <span>Request Export Quote</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Infinite Scroll Trigger --}}
        @if($products->hasMorePages())
            <div 
                x-data="{
                    observer: null,
                    isLoading: false,
                    init() {
                        this.observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting && !this.isLoading) {
                                    this.isLoading = true;
                                    $wire.loadMore().then(() => {
                                        this.isLoading = false;
                                    });
                                }
                            });
                        }, { rootMargin: '350px' });
                        this.observer.observe(this.$el);
                    },
                    destroy() {
                        if (this.observer) this.observer.disconnect();
                    }
                }"
                class="w-full flex flex-col items-center justify-center py-10"
            >
                <div class="inline-flex items-center gap-2.5 px-6 py-3 rounded-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold shadow-xs">
                    <svg class="animate-spin h-4 w-4 text-terra-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Loading More Patterns...</span>
                </div>
            </div>
        @endif

        @else
        <div class="text-center py-20 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800">
            <p class="text-slate-500 text-sm">No patterns found matching your search.</p>
        </div>
        @endif

    </div>
</div>
