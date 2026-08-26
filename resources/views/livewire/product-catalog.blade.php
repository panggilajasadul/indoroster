<div class="bg-slate-50 dark:bg-slate-950 min-h-screen py-6 sm:py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Header -->
        <div class="mb-6 sm:mb-8">
            @php
                $catalogBreadcrumbs = $activeCategory 
                    ? [['label' => 'Katalog Roster & Ornamen', 'url' => route('catalog')], ['label' => $activeCategory->name]]
                    : [['label' => 'Katalog Roster & Ornamen']];
            @endphp
            <x-breadcrumb :items="$catalogBreadcrumbs" class="!px-0 !py-0 mb-3" />
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    @if($activeCategory)
                        <h1 class="font-display text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 dark:text-white tracking-tight">Katalog {{ $activeCategory->name }} — Pabrik Roster Purwakarta</h1>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 max-w-3xl">{{ $activeCategory->description ?? 'Pilihan terlengkap '.$activeCategory->name.' langsung dari produsen tangan pertama IndoRoster Plered Purwakarta. Hasil cetak tumbuk padat khusus yang keras dan rapi, harga pabrik, siap kirim ke Jabodetabek dan seluruh Indonesia.' }}</p>
                    @elseif($search)
                        <h1 class="font-display text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 dark:text-white tracking-tight">Hasil Pencarian: "{{ $search }}"</h1>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Menampilkan katalog produk roster beton dan material terkait.</p>
                    @else
                        <h1 class="font-display text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                            {{ $page?->meta_title ?: ($page?->title ?: 'Katalog Roster Beton & Bata Expose — Pabrik & Produsen Terpercaya') }}
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 max-w-3xl">
                            {{ $page?->meta_description ?: 'Pusat katalog roster beton minimalis, bata expose, dan ornamen dinding langsung dari pabrik tangan pertama IndoRoster Plered Purwakarta. Hasil cetak tumbuk padat pengrajin ahli, keras, kokoh, dan rapi dengan harga grosir pabrik.' }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar Panel -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs p-3.5 sm:p-5 mb-8">
            <div class="flex flex-col lg:flex-row gap-3.5 sm:gap-4 items-stretch lg:items-center">
                
                <!-- Search Input -->
                <div class="flex-grow relative">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama motif roster, ukuran, bata..." class="w-full h-11 sm:h-12 pl-10 pr-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-800/80 focus:bg-white dark:focus:bg-slate-800 focus:ring-2 focus:ring-terra-500 focus:border-terra-500 text-xs sm:text-sm text-slate-800 dark:text-white transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    @if($search)
                    <button wire:click="$set('search', '')" class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                    @endif
                </div>

                <!-- Dropdown Filters -->
                <div class="flex items-center gap-2.5 shrink-0">
                    <!-- Category Dropdown -->
                    <div class="w-1/2 lg:w-48">
                        <select wire:model.live="categorySlug" class="w-full h-11 sm:h-12 border border-slate-200 dark:border-slate-700 rounded-xl px-3 text-xs sm:text-sm font-medium focus:ring-2 focus:ring-terra-500 focus:border-terra-500 bg-slate-50/70 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 cursor-pointer">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Sorting Dropdown -->
                    <div class="w-1/2 lg:w-44">
                        <select wire:model.live="sortBy" class="w-full h-11 sm:h-12 border border-slate-200 dark:border-slate-700 rounded-xl px-3 text-xs sm:text-sm font-medium focus:ring-2 focus:ring-terra-500 focus:border-terra-500 bg-slate-50/70 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 cursor-pointer">
                            <option value="newest">Terbaru</option>
                            <option value="price_asc">Harga: Termurah</option>
                            <option value="price_desc">Harga: Termahal</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Horizontal Quick Filter Chips -->
            <div class="mt-4 pt-3.5 border-t border-slate-100 dark:border-slate-800 flex items-center gap-2 overflow-x-auto no-scrollbar pb-1">
                <button wire:click="$set('categorySlug', '')" class="px-3.5 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition-all {{ empty($categorySlug) ? 'bg-slate-900 dark:bg-terra-500 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                    Semua
                </button>
                @foreach($categories as $cat)
                <button wire:click="$set('categorySlug', '{{ $cat->slug }}')" class="px-3.5 py-1.5 rounded-full text-xs font-bold whitespace-nowrap transition-all {{ $categorySlug === $cat->slug ? 'bg-terra-500 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                    {{ $cat->name }}
                </button>
                @endforeach
            </div>
        </div>

        <!-- Viral Products Section (When not searching) -->
        @if(!$search && !$categorySlug && isset($viralProducts) && $viralProducts->count() > 0)
            <div class="mb-10 bg-gradient-to-br from-amber-50/50 dark:from-amber-950/20 via-white dark:via-slate-900 to-orange-50/30 dark:to-orange-950/20 rounded-3xl border border-amber-200/60 dark:border-amber-900/40 shadow-soft-xs p-5 sm:p-7 relative overflow-hidden">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-amber-500/15 dark:bg-amber-500/25 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-sm">🔥</span>
                        <div>
                            <h2 class="font-display text-lg sm:text-xl font-black text-slate-900 dark:text-white tracking-tight">Motif Roster Terpopuler & Viral</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Pilihan roster beton terfavorit arsitek dan kontraktor.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4">
                    @foreach($viralProducts as $product)
                        <x-product-card :product="$product" :badgeText="'#' . $loop->iteration . ' Hot'" />
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Main Product Grid -->
        <div>
            @if($products->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4 lg:gap-5">
                    @foreach($products as $product)
                        <x-product-card :product="$product" wire:key="product-{{ $product->id }}" />

                        <!-- In-Feed Promotional & Regional Voucher Banner (Diselipkan di sela grid produk) -->
                        @if($loop->iteration == 6 && isset($vouchers) && $vouchers->count() > 0)
                            <div class="col-span-full my-4 bg-gradient-to-br from-amber-500/10 via-terra-500/5 to-amber-500/15 dark:from-slate-900/90 dark:via-slate-900 dark:to-slate-900/90 rounded-3xl border border-amber-300/60 dark:border-slate-800 p-5 sm:p-7 shadow-soft-xs relative overflow-hidden" x-data="{ copiedCode: null }">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5">
                                    <div>
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/20 text-amber-900 dark:text-amber-300 text-[11px] font-black uppercase tracking-wider mb-1.5 border border-amber-500/30">
                                            <span>🏷️</span>
                                            <span>Voucher Pengiriman & Promo Wilayah</span>
                                        </div>
                                        <h3 class="font-display text-lg sm:text-xl font-black text-slate-900 dark:text-white tracking-tight">
                                            Klaim Promo Armada Pabrik Sesuai Wilayah Proyek Anda
                                        </h3>
                                        <p class="text-xs text-slate-600 dark:text-slate-400">Gunakan kode voucher saat checkout atau sebutkan kode saat konsultasi ke Admin WhatsApp:</p>
                                    </div>
                                    <a href="https://wa.me/6281389709847?text=Halo%20Admin%20IndoRoster,%20saya%20ingin%20tanya%20klaim%20promo%20ongkir%20pabrik" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-terra-500 hover:bg-terra-600 text-white font-bold text-xs shadow-xs transition-all shrink-0">
                                        <span>💬 Konsultasi Admin Pabrik</span>
                                    </a>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5 sm:gap-4">
                                    @foreach($vouchers as $voucher)
                                        <div class="bg-white dark:bg-slate-800/90 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 flex flex-col justify-between shadow-2xs group hover:border-terra-400 dark:hover:border-terra-500 transition-all">
                                            <div>
                                                <div class="flex items-center justify-between gap-2 mb-3">
                                                    <span class="px-2.5 py-1 rounded-md bg-amber-50 dark:bg-amber-500/15 text-amber-900 dark:text-amber-300 text-[10px] sm:text-[11px] font-black uppercase tracking-wider border border-amber-300/80 dark:border-amber-500/30">
                                                        {{ $voucher->badge_text ?: 'Promo Spesial' }}
                                                    </span>
                                                    <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-md border border-emerald-200/60 dark:border-emerald-900/30">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                        Aktif
                                                    </span>
                                                </div>
                                                <h4 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white mb-1.5 group-hover:text-terra-600 dark:group-hover:text-terra-400 transition-colors">
                                                    {{ $voucher->name }}
                                                </h4>
                                                <p class="text-[11px] text-slate-600 dark:text-slate-300 leading-snug mb-3">
                                                    {{ $voucher->description }}
                                                </p>
                                            </div>

                                            <div class="pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between gap-2">
                                                <div class="font-mono text-xs font-black tracking-wider text-slate-900 dark:text-amber-300 bg-slate-100 dark:bg-slate-950 px-2.5 py-1.5 rounded-lg border border-dashed border-slate-300 dark:border-slate-700">
                                                    {{ $voucher->code }}
                                                </div>
                                                <button 
                                                    type="button" 
                                                    @click="navigator.clipboard.writeText('{{ $voucher->code }}'); copiedCode = '{{ $voucher->code }}'; setTimeout(() => copiedCode = null, 2500)"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold text-terra-600 dark:text-white bg-terra-50 dark:bg-terra-600 hover:bg-terra-500 hover:text-white dark:hover:bg-terra-500 transition-all cursor-pointer border border-terra-200/60 dark:border-transparent"
                                                >
                                                    <span x-text="copiedCode === '{{ $voucher->code }}' ? '✓ Tersalin!' : 'Salin Kode'"></span>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                
                <!-- Infinite Scroll Native Sensor (100% Otomatis saat Scroll) -->
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
                        <div class="inline-flex items-center gap-2.5 px-6 py-3 rounded-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-soft-xs text-slate-700 dark:text-slate-300 text-xs font-semibold">
                            <svg class="animate-spin h-4 w-4 text-terra-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                            <span>Memuat produk secara otomatis...</span>
                        </div>
                    </div>
                @else
                    <div class="w-full text-center py-10">
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-xs font-medium">
                            <span>✓ Semua produk telah ditampilkan (Total {{ $products->total() }} Produk)</span>
                        </div>
                    </div>
                @endif
            @else
                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-12 text-center shadow-soft-xs">
                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400 dark:text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Produk Tidak Ditemukan</h3>
                    <p class="text-slate-500 dark:text-slate-400 mb-6 text-sm">Tidak ada produk yang cocok dengan kata kunci atau filter yang Anda pilih.</p>
                    <button wire:click="$set('search', ''); $set('categorySlug', '');" class="bg-terra-500 hover:bg-terra-600 text-white font-bold px-6 py-2.5 rounded-xl transition-colors text-sm shadow-xs cursor-pointer">
                        Reset Filter & Tampilkan Semua
                    </button>
                </div>
            @endif
        </div>

        <!-- Custom Page Builder Blocks (Jika Admin menambahkan blok di /admin/pages untuk katalog) -->
        @if($page && is_array($page->content) && count($page->content) > 0)
            <div class="mt-12">
                <x-block-renderer :blocks="$page->content" />
            </div>
        @endif

        <!-- Section: Metode Pembayaran & Jasa Pengiriman Resmi (seperti Toco) -->
        <x-trust-payment-shipping />
        
    </div>
</div>

@push('seo')
@php
    $itemListElements = [];
    foreach ($products as $idx => $p) {
        $itemListElements[] = [
            '@type' => 'ListItem',
            'position' => $idx + 1,
            'name' => $p->name,
            'url' => route('product.detail', $p->slug),
            'image' => $p->featured_image ?? asset('assets/logo_indoroster_no_text.PNG'),
        ];
    }

    $schemaData = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => $activeCategory ? 'Katalog ' . $activeCategory->name : 'Katalog Roster Beton IndoRoster',
        'itemListElement' => $itemListElements,
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
