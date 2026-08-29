@props(['data'])

@php
    $title = $data['title'] ?? 'Koleksi Produk Roster Pilihan';
    $subtitle = $data['subtitle'] ?? null;
    $badge = $data['badge'] ?? 'Koleksi Pilihan Arsitek';
    $categoryIds = $data['categories'] ?? [];
    $limit = $data['limit'] ?? 24;
    $gridColumns = (string) ($data['grid_columns'] ?? \App\Models\SiteSetting::getValue('home_product_grid_columns', '4'));
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');

    $query = \App\Models\Product::with('category', 'media', 'variants')->active();
    if (!empty($categoryIds)) {
        $query->whereIn('category_id', $categoryIds);
    }
    $products = $query->latest()->limit($limit)->get();
@endphp

<section 
    x-data="{
        showCount: 12,
        total: {{ count($products) }},
        observer: null,
        init() {
            if (this.showCount < this.total) {
                this.$nextTick(() => {
                    const sentinel = this.$refs.sentinel;
                    if (sentinel) {
                        this.observer = new IntersectionObserver((entries) => {
                            if (entries[0].isIntersecting) {
                                this.revealMore();
                            }
                        }, { rootMargin: '300px' });
                        this.observer.observe(sentinel);
                    }
                });
            }
        },
        revealMore() {
            if (this.showCount < this.total) {
                this.showCount = Math.min(this.showCount + 12, this.total);
                if (this.showCount >= this.total && this.observer) {
                    this.observer.disconnect();
                }
            }
        }
    }"
    class="py-20 sm:py-24 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 sm:mb-16 gap-6" data-motion="fade-up">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full {{ $theme->badgeClass }} text-xs font-bold uppercase tracking-wider mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-terra-500 animate-pulse"></span>
                    {{ $badge }}
                </div>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black font-display {{ $theme->headingColor }} tracking-tight leading-tight">
                    {!! $title !!}
                </h2>
                @if($subtitle)
                    <p class="text-sm sm:text-base text-slate-500 dark:text-slate-400 mt-2 max-w-2xl font-normal">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
            <div>
                <a href="{{ route('catalog') }}" class="inline-flex items-center gap-3 px-5 py-2.5 rounded-full border font-bold text-xs uppercase tracking-wider transition-all shadow-xs group {{ $theme->btnSecondary }}" data-magnetic>
                    <span>Buka Filter Katalog Lengkap</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Product Grid (Auto-Reveal Smooth Animation) -->
        <div class="grid {{ $gridColumns === '6' ? 'grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 sm:gap-4' : 'grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-5' }}" data-motion="stagger">
            @foreach($products as $index => $product)
                <div 
                    x-show="{{ $index }} < showCount" 
                    x-transition:enter="transition ease-out duration-300 transform opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="h-full">
                    <x-product-card :product="$product" :badgeText="$loop->first ? '#1 BEST' : null" />
                </div>
            @endforeach
        </div>

        <!-- Auto-Reveal Sentinel & Load More Indicator -->
        <div x-show="showCount < total" class="mt-12 flex flex-col items-center justify-center">
            <div x-ref="sentinel" class="h-4 w-full"></div>
            <button 
                @click="revealMore()" 
                class="inline-flex items-center gap-2.5 px-6 py-3 rounded-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 hover:border-terra-500 hover:text-terra-500 text-xs font-bold uppercase tracking-wider shadow-sm transition-all duration-300 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-terra-500 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
                <span>Memuat Lebih Banyak Produk... (<span x-text="showCount"></span> dari <span x-text="total"></span>)</span>
            </button>
        </div>
    </div>
</section>
