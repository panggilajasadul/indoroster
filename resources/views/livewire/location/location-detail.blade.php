@php
    // Structured Data JSON-LD
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
                'name' => 'Area Layanan',
                'item' => route('location.index'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $location->name,
                'item' => route('location.detail', $location->slug),
            ],
        ],
    ];

    $faqList = !empty($location->custom_faqs) ? $location->custom_faqs : [
        [
            'q' => "Bagaimana cara pesan roster beton untuk area {$location->name}?",
            'a' => "Pilih motif roster yang Anda inginkan di katalog online IndoRoster, lalu hubungi tim sales kami via WhatsApp untuk mendapatkan total estimasi harga pabrik dan jadwal pengiriman armada langsung.",
        ],
        [
            'q' => "Berapa lama estimasi pengiriman roster ke {$location->name}?",
            'a' => "Pengiriman ke wilayah {$location->name} memakan waktu sekitar " . ($location->estimated_delivery_time ?: '1-2 hari kerja') . " via rute " . ($location->delivery_route_info ?: 'armada truk pabrik langsung') . ".",
        ],
        [
            'q' => "Apakah ada garansi jika roster pecah saat dikirim ke {$location->name}?",
            'a' => "Ada. IndoRoster memberikan Garansi 100% Ganti Baru di tempat untuk setiap keping roster yang rusak atau pecah selama pengiriman oleh armada pabrik kami.",
        ],
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
        }, $faqList),
    ];

    $serviceSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => "Pengiriman & Supply Roster Beton {$location->name}",
        'serviceType' => 'Suplai Roster Beton Minimalis & Material Arsitektural',
        'provider' => [
            '@type' => 'Organization',
            'name' => 'IndoRoster',
            'url' => route('home'),
            'logo' => asset('assets/logo_indoroster_no_text.PNG'),
        ],
        'areaServed' => [
            '@type' => 'City',
            'name' => $location->name,
        ],
        'hasOfferCatalog' => [
            '@type' => 'OfferCatalog',
            'name' => "Katalog Roster Beton {$location->name}",
            'itemListElement' => [
                [
                    '@type' => 'Offer',
                    'itemOffered' => [
                        '@type' => 'Product',
                        'name' => 'Roster Beton Minimalis Presisi',
                        'description' => "Roster beton cetak tumbuk padat plat baja presisi untuk wilayah {$location->name}.",
                    ],
                ],
            ],
        ],
        'description' => $location->intro_content ?: "Layanan pengadaan dan pengiriman langsung pabrik roster beton presisi untuk wilayah {$location->name}.",
    ];

    if ($location->latitude && $location->longitude) {
        $serviceSchema['areaServed']['geo'] = [
            '@type' => 'GeoCoordinates',
            'latitude' => (float) $location->latitude,
            'longitude' => (float) $location->longitude,
        ];
    }
@endphp

@push('seo')
    @if($location->latitude && $location->longitude)
    <meta name="geo.position" content="{{ $location->latitude }};{{ $location->longitude }}">
    <meta name="ICBM" content="{{ $location->latitude }}, {{ $location->longitude }}">
    @endif
    <meta name="geo.placename" content="{{ $location->name }}, Indonesia">

    <script type="application/ld+json">
    {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($serviceSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <x-ecommerce-itemlist-schema :name="'Katalog Roster Beton Area ' . $location->name" :description="'Daftar motif roster beton minimalis presisi siap kirim ke wilayah ' . $location->name" :products="$products" />
@endpush

<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-10">
    <!-- Hero Section with Local Narrative -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-white via-slate-50 to-orange-50/20 dark:from-slate-900 dark:via-slate-850 dark:to-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-900 dark:text-white p-8 sm:p-12 lg:p-16 shadow-soft-xl dark:shadow-2xl">
            <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-terra-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 max-w-4xl">
                <!-- Breadcrumbs -->
                <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-6 font-medium">
                    <a href="{{ route('home') }}" class="hover:text-slate-900 dark:hover:text-white transition">Beranda</a>
                    <span>/</span>
                    <a href="{{ route('location.index') }}" class="hover:text-slate-900 dark:hover:text-white transition">Area Layanan</a>
                    <span>/</span>
                    <span class="text-terra-600 dark:text-terra-400 font-bold">{{ $location->name }}</span>
                </nav>

                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-terra-500/10 border border-terra-500/30 text-terra-600 dark:text-terra-400 text-xs font-bold uppercase tracking-wider mb-6 shadow-xs">
                    <span>📍</span> Layanan Pengiriman Langsung Pabrik ke Wilayah {{ $location->name }}
                </div>
                
                <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight text-slate-900 dark:text-white mb-6">
                    {{ $location->headline ?: 'Pabrik & Supplier Roster Beton untuk Wilayah ' . $location->name }}
                </h1>
                
                <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 mb-8 leading-relaxed">
                    {{ $location->intro_content }}
                </p>

                <div class="flex flex-wrap items-center gap-4">
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm sm:text-base shadow-lg shadow-emerald-600/25 transition-all hover:scale-[1.02]">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        Pesan Cepat ke {{ $location->name }} (WhatsApp)
                    </a>
                    <a href="{{ route('tools.calculator') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-white hover:bg-slate-100 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold text-sm transition-all border border-slate-300 dark:border-slate-700 shadow-xs">
                        🧮 Hitung Kebutuhan Dinding &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Logistics Highlights -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
                <div class="w-10 h-10 rounded-xl bg-terra-500/10 text-terra-600 dark:text-terra-400 flex items-center justify-center text-xl font-bold mb-3">🚚</div>
                <h2 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Jalur Ekspedisi ke {{ $location->name }}</h2>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">{{ $location->delivery_route_info ?: 'Pengiriman via armada truk material berpengalaman langsung ke pintu proyek Anda.' }}</p>
            </div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl font-bold mb-3">⏱️</div>
                <h2 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Estimasi Waktu Sampai</h2>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">{{ $location->estimated_delivery_time ?: '1 - 2 Hari Kerja' }} (Tergantung antrian armada harian)</p>
            </div>
            <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
                <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl font-bold mb-3">🛡️</div>
                <h2 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Garansi Bebas Risiko</h2>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">{{ $location->shipping_guarantee_text ?: 'Garansi 100% Bebas Pecah: Setiap keping yang rusak di perjalanan diganti baru.' }}</p>
            </div>
        </div>
    </div>

    <!-- Complete Product Catalog Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 pb-4 border-b border-slate-200 dark:border-slate-800">
            <div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Katalog Lengkap Roster Beton — Siap Kirim ke {{ $location->name }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Seluruh koleksi motif roster beton minimalis, loster arsitektural, dan bata expose kualitas cetak padat presisi pabrik.
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
                $itemWaUrl = "https://wa.me/{$waNumber}?text=" . urlencode("Halo Admin IndoRoster, saya ingin memesan produk: {$product->name} untuk dikirim ke wilayah {$location->name}. Mohon info total biaya dan ketersediaan stok.");
            @endphp
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-soft-xs hover:shadow-soft-lg hover:border-terra-400/90 dark:hover:border-terra-500 transition-all duration-300 group flex flex-col justify-between">
                <a href="{{ route('product.detail', $product->slug) }}" class="block aspect-square relative bg-slate-100 dark:bg-slate-800 overflow-hidden">
                    <img src="{{ $imgUrl }}" alt="Roster Beton Minimalis {{ $product->name }} — Pengiriman Wilayah {{ $location->name }} IndoRoster" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
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
                        <!-- Rating & Terjual (Social Proof) -->
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

                        <a href="{{ $itemWaUrl }}" target="_blank" rel="noopener noreferrer" class="w-full py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold flex items-center justify-center gap-1.5 shadow-sm transition-all hover:scale-[1.02]">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                            <span>Pesan ke {{ $location->name }}</span>
                        </a>
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
            <p class="text-slate-500 dark:text-slate-400 text-sm">Tidak ada motif produk yang cocok dengan kata kunci pencarian Anda.</p>
        </div>
        @endif
    </div>

    <!-- Targeted Districts & FAQs -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Target Districts -->
            @if(!empty($location->target_districts))
            <div class="lg:col-span-5 bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">Cakupan Area & Kecamatan di {{ $location->name }}</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 mb-4 leading-relaxed">Armada kami siap mendistribusikan pesanan langsung ke seluruh wilayah kecamatan berikut:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($location->target_districts as $district)
                    <span class="px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200/60 dark:border-slate-700/60 text-xs font-semibold">
                        📍 {{ $district }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- FAQs -->
            <div class="{{ !empty($location->target_districts) ? 'lg:col-span-7' : 'lg:col-span-12' }} bg-white dark:bg-slate-900 p-6 sm:p-8 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">Pertanyaan Seputar Pemesanan & Pengiriman ke {{ $location->name }}</h3>
                <div class="space-y-4">
                    @if(!empty($location->custom_faqs))
                        @foreach($location->custom_faqs as $faq)
                        <div class="p-4 sm:p-5 rounded-2xl bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/70">
                            <h4 class="font-bold text-slate-900 dark:text-white text-sm mb-1.5">{{ $faq['q'] }}</h4>
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">{{ $faq['a'] }}</p>
                        </div>
                        @endforeach
                    @else
                        <div class="p-4 sm:p-5 rounded-2xl bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/70">
                            <h4 class="font-bold text-slate-900 dark:text-white text-sm mb-1.5">Bagaimana cara pesan roster untuk area {{ $location->name }}?</h4>
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">Anda cukup memilih motif pada katalog di atas dan klik tombol WhatsApp untuk mendapatkan penawaran total biaya dan jadwal pengiriman armada.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Nearby Locations / Kawasan Terkait (Silo Internal Linking) -->
    @if(isset($nearbyLocations) && $nearbyLocations->count() > 0)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="bg-white dark:bg-slate-900 p-6 sm:p-10 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                        Cakupan Kawasan & Area Pengiriman Terkait
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Armada pabrik IndoRoster juga melayani pengiriman langsung ke kawasan proyek dan wilayah sekitar {{ $location->name }}:
                    </p>
                </div>
                <a href="{{ route('location.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-terra-600 dark:text-terra-400 hover:underline whitespace-nowrap">
                    Lihat Semua Area &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                @foreach($nearbyLocations as $nearby)
                <a href="{{ route('location.detail', $nearby->slug) }}" class="group p-3.5 sm:p-4 rounded-2xl bg-slate-50/80 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 border border-slate-200/70 dark:border-slate-700/60 hover:border-terra-500 dark:hover:border-terra-500 hover:shadow-soft-md transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1.5 text-[11px] font-bold text-terra-600 dark:text-terra-400 mb-1">
                            <span>📍</span>
                            <span class="truncate">{{ $nearby->type === 'cluster' ? 'Kawasan Klaster' : 'Wilayah / Kota' }}</span>
                        </div>
                        <h4 class="font-bold text-slate-800 dark:text-slate-200 text-xs sm:text-sm group-hover:text-terra-600 dark:group-hover:text-terra-400 transition-colors line-clamp-1">
                            {{ $nearby->name }}
                        </h4>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 line-clamp-2 mt-1 leading-snug">
                            {{ $nearby->meta_description ?: 'Suplai roster beton presisi harga pabrik langsung ke lokasi proyek ' . $nearby->name . '.' }}
                        </p>
                    </div>
                    <div class="mt-3 pt-2 border-t border-slate-200/50 dark:border-slate-700/50 flex items-center justify-between text-[10px] font-semibold text-slate-500 dark:text-slate-400 group-hover:text-terra-600 dark:group-hover:text-terra-400">
                        <span>Jual Roster Beton</span>
                        <span class="transform group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- B2B Segment & Use-Case Cross Links -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-16">
        <div class="p-8 sm:p-10 rounded-3xl bg-slate-900 text-white border border-slate-800 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <span class="text-xs font-bold text-terra-400 uppercase tracking-widest block mb-2">Layanan Khusus B2B & Proyek</span>
                <h3 class="text-xl sm:text-2xl font-black mb-2">Punya Proyek Konstruksi di {{ $location->name }}?</h3>
                <p class="text-xs sm:text-sm text-slate-300 max-w-2xl leading-relaxed">
                    Kami menyediakan penawaran khusus RAB, faktur pajak resmi, dan jadwal pengiriman bertahap untuk rekan kontraktor, developer perumahan, arsitek, dan pemilik toko bangunan di {{ $location->name }}.
                </p>
            </div>
            <div class="flex flex-wrap gap-2.5 shrink-0">
                <a href="{{ route('b2b.contractor') }}" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-terra-500 text-white text-xs font-bold transition border border-white/15">
                    🏗️ Untuk Kontraktor
                </a>
                <a href="{{ route('b2b.developer') }}" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-terra-500 text-white text-xs font-bold transition border border-white/15">
                    🏘️ Untuk Developer
                </a>
                <a href="{{ route('b2b.architect') }}" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-terra-500 text-white text-xs font-bold transition border border-white/15">
                    📐 Untuk Arsitek
                </a>
                <a href="{{ route('catalog') }}" class="px-4 py-2 rounded-xl bg-terra-500 hover:bg-terra-600 text-white text-xs font-bold transition shadow-md">
                    Lihat Katalog &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
