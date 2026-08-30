<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-10">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-white via-slate-50 to-amber-50/20 dark:from-slate-900 dark:via-slate-900 dark:to-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-900 dark:text-white p-8 sm:p-12 lg:p-16 shadow-soft-xl dark:shadow-2xl">
            <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 max-w-3xl">
                <x-breadcrumb :items="[['label' => 'Khusus Kontraktor & Pemborong']]" class="!px-0 !py-0 mb-6" />

                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-amber-400 text-xs font-bold uppercase tracking-wider mb-6 shadow-xs">
                    <span>🏗️</span> Khusus Kontraktor, Pemborong, & Estimator
                </div>
                <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight text-slate-900 dark:text-white mb-6">
                    Pusat Suplai Roster Beton untuk <span class="text-terra-500">Proyek & Kontraktor</span>
                </h1>
                <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 mb-8 leading-relaxed">
                    Kapasitas produksi pabrik 10.000 pcs per bulan, sudut siku 90° presisi untuk kecepatan pasang tukang di lapangan, garansi bebas pecah di jalan, dan kelengkapan dokumen pengadaan resmi.
                </p>
                <div class="flex flex-wrap items-center gap-4">
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm sm:text-base shadow-lg shadow-emerald-600/25 transition-all hover:scale-[1.02]">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        Minta Penawaran RAB Proyek (WhatsApp)
                    </a>
                    <a href="{{ route('tools.calculator') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold text-sm transition-all border border-slate-300 dark:border-slate-700 shadow-xs">
                        🧮 Kalkulator Kebutuhan Dinding &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Value Props for Contractors -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">Keunggulan IndoRoster untuk Rekan Kontraktor</h2>
            <p class="text-slate-600 dark:text-slate-400 mt-2 text-sm">Dirancang untuk menjamin kelancaran timeline dan efisiensi biaya proyek Anda.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-terra-500/10 text-terra-500 flex items-center justify-center text-2xl font-bold mb-5">⚙️</div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Presisi Siku 90° & Cetak Padat</h3>
                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                    Dibuat dengan cetakan baja presisi tinggi dan pemadatan hidrolik. Hasil keping rata sempurna, mempercepat waktu pemasangan tukang di lapangan dan menghemat penggunaan adukan semen/perekat.
                </p>
            </div>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-2xl font-bold mb-5">🚚</div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Pengiriman Bertahap (Batch Delivery)</h3>
                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                    Armada truk pabrik kami siap mengirim material secara bertahap mengikuti jadwal cor dan pemasangan proyek Anda. Area proyek tidak sempit dan material tersimpan aman.
                </p>
            </div>
            <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center text-2xl font-bold mb-5">📄</div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Dokumen Administrasi Lengkap</h3>
                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                    Surat Jalan resmi, Invoice Komersial, Faktur Pajak, dan kelengkapan dokumen pengadaan siap diterbitkan cepat untuk pelaporan administrasi proyek Anda.
                </p>
            </div>
        </div>
    </div>

    <!-- Product Recommendations for Project -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Katalog Lengkap Roster Beton untuk Proyek & Kontraktor
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Pilihan terfavorit pemborong bangunan dengan kapasitas produksi 10.000 pcs/bulan dan siku presisi 90°.
                </p>
            </div>

            <!-- Search Filter -->
            <div class="w-full md:w-72">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama motif..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500 focus:outline-none shadow-2xs">
            </div>
        </div>

        <!-- Category Tabs Filter -->
        <div class="flex items-center gap-2 overflow-x-auto pb-4 mb-8 no-scrollbar">
            <button wire:click="$set('categorySlug', '')" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition {{ empty($categorySlug) ? 'bg-terra-500 text-white shadow-md shadow-terra-500/20' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 hover:border-terra-500' }}">
                Semua Produk
            </button>
            @foreach($categories as $cat)
            <button wire:click="$set('categorySlug', '{{ $cat->slug }}')" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition {{ $categorySlug === $cat->slug ? 'bg-terra-500 text-white shadow-md shadow-terra-500/20' : 'bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-800 hover:border-terra-500' }}">
                {{ $cat->name }}
            </button>
            @endforeach
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($products as $product)
            @php
                $displayMedia = $product->primary_media;
                $imgUrl = ($displayMedia && $displayMedia->media_type === 'image') 
                    ? $displayMedia->formatted_url 
                    : ($product->primary_image ?: asset('assets/logo_indoroster_no_text.PNG'));
                $itemWaUrl = "https://wa.me/{$waNumber}?text=" . urlencode("Halo Sales IndoRoster, saya Kontraktor ingin minta penawaran harga proyek untuk motif: {$product->name}.");
            @endphp
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-soft-xs hover:shadow-soft-lg hover:border-terra-400/90 dark:hover:border-terra-500 transition-all duration-300 group flex flex-col justify-between">
                <a href="{{ route('product.detail', $product->slug) }}" class="block aspect-square relative bg-slate-100 dark:bg-slate-800 overflow-hidden">
                    <img src="{{ $imgUrl }}" alt="{{ $displayMedia->alt_text ?? $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                    <span class="absolute top-2 left-2 px-2 py-0.5 rounded-md bg-slate-900/80 text-white text-[9px] sm:text-[10px] font-semibold backdrop-blur-xs z-10">
                        {{ $product->dimensions ?: '20×20×10 cm' }}
                    </span>
                    @if($product->discount_percentage > 0)
                    <span class="absolute top-2 right-2 px-1.5 py-0.5 rounded-md bg-red-500 text-white text-[9px] font-black shadow-xs z-10">
                        -{{ $product->discount_percentage }}%
                    </span>
                    @endif
                </a>

                <div class="p-3.5 sm:p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <span class="text-[9px] sm:text-[10px] text-terra-600 dark:text-terra-400 font-bold uppercase tracking-wider block mb-1">
                            {{ $product->category->name ?? 'Roster Beton' }}
                        </span>
                        <a href="{{ route('product.detail', $product->slug) }}" class="font-bold text-slate-800 dark:text-slate-200 text-xs sm:text-sm line-clamp-2 hover:text-terra-600 dark:hover:text-terra-400 transition-colors leading-snug">
                            {{ $product->name }}
                        </a>
                    </div>

                    <div class="mt-3 pt-2.5 border-t border-slate-100 dark:border-slate-800">
                        <!-- Rating & Terjual -->
                        <div class="flex items-center gap-1.5 mb-2 text-[10px] text-slate-500 dark:text-slate-400 flex-wrap">
                            <div class="flex items-center text-amber-400">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                <span class="font-bold text-slate-700 dark:text-slate-300 ml-0.5">{{ number_format($product->average_rating, 1) }}</span>
                            </div>
                            <span class="text-slate-300 dark:text-slate-700">|</span>
                            <span class="truncate font-medium text-slate-600 dark:text-slate-300">{{ $product->total_sold > 0 ? $product->formatted_total_sold . ' Terjual' : 'Produk Unggulan' }}</span>
                        </div>

                        <!-- Price -->
                        <div class="flex items-baseline justify-between mb-3">
                            <div class="text-xs sm:text-sm font-bold text-[#ee4d2d] dark:text-terra-400">
                                {{ $product->formatted_price_range }}
                            </div>
                            <span class="text-[10px] text-slate-400">Harga Pabrik</span>
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('product.detail', $product->slug) }}" class="py-2 px-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 text-center text-xs font-semibold transition">
                                Detail
                            </a>
                            <a href="{{ $itemWaUrl }}" target="_blank" rel="noopener noreferrer" class="py-2 px-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold flex items-center justify-center gap-1 shadow-sm transition hover:scale-[1.02]">
                                <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                <span>Pesan WA</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Infinite Scroll Trigger / Auto Reveal Sentinel -->
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
                    <span>Memuat Produk Selanjutnya...</span>
                </div>
            </div>
        @endif

        @else
        <div class="text-center py-16 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800">
            <p class="text-slate-500 dark:text-slate-400 text-sm">Tidak ada motif produk yang cocok dengan pencarian Anda.</p>
        </div>
        @endif
    </div>

    <!-- Direct CTA Banner -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl bg-terra-500 text-white p-8 sm:p-12 text-center relative overflow-hidden shadow-xl shadow-terra-500/20">
            <div class="relative z-10 max-w-2xl mx-auto">
                <h2 class="text-2xl sm:text-4xl font-extrabold mb-4">Siap Mengirim Penawaran Terbaik untuk Proyek Anda</h2>
                <p class="text-terra-100 text-sm sm:text-base mb-8">
                    Diskusikan kebutuhan volume, jadwal pengiriman, dan dapatkan pricelist grosir tangan pertama langsung dari pabrik IndoRoster.
                </p>
                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2.5 px-8 py-4 rounded-xl bg-slate-900 hover:bg-slate-850 text-white font-bold text-base shadow-2xl transition hover:scale-105">
                    <span>💬</span> Hubungi Sales Proyek via WhatsApp Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
