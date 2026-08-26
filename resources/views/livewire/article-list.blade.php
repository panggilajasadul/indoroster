<div>
    @php
        $collectionSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => 'Artikel, Tips Bangunan & Inspirasi Roster Beton',
            'description' => 'Kumpulan artikel arsitektural, panduan teknis pemasangan roster beton minimalis cetak tumbuk padat presisi, dan edukasi material dari IndoRoster.',
            'url' => route('article.index'),
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'IndoRoster Indonesia',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('assets/logo_indoroster_no_text.PNG'),
                ],
            ],
        ];

        $breadcrumbSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Beranda',
                    'item' => url('/'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Artikel & Edukasi',
                    'item' => route('article.index'),
                ],
            ],
        ];
    @endphp

    @push('seo')
    <script type="application/ld+json">
    {!! json_encode($collectionSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @endpush

    <!-- Header & Hero Section -->
    <div class="bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 text-white pt-12 pb-16 relative overflow-hidden">
        <!-- Subtle Pattern Background -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#ea580c_1px,transparent_1px)] [background-size:24px_24px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Breadcrumbs -->
            <x-breadcrumb :items="[['label' => 'Artikel & Edukasi']]" variant="dark" class="!px-0 !py-0 mb-6" />

            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-terra-500/20 text-terra-400 text-xs font-bold uppercase tracking-wider mb-4 border border-terra-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-terra-400 animate-pulse"></span>
                    Inspirasi & Edukasi Arsitektur
                </span>
                <h1 class="font-display text-3xl sm:text-4xl md:text-5xl font-black tracking-tight leading-tight mb-4 text-white">
                    Pusat Edukasi Roster Beton & Arsitektur Tropis
                </h1>
                <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
                    Panduan pemilihan roster beton minimalis, komparasi mutu fisik cetak tumbuk, inspirasi fasad dinding rumah, dan tips arsitektural langsung dari pabrik tangan pertama IndoRoster Purwakarta.
                </p>
            </div>

            <!-- Search Bar in Header -->
            <div class="mt-8 max-w-xl">
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.350ms="search" 
                        placeholder="Cari artikel, topik fasad, pemasangan, harga..." 
                        class="w-full h-12 pl-11 pr-4 rounded-2xl bg-slate-900/90 border border-slate-700 text-sm text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-terra-500 focus:border-terra-500 shadow-xl backdrop-blur-md"
                    />
                    <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    @if($search)
                    <button wire:click="$set('search', '')" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white text-xs bg-slate-800 px-2 py-1 rounded-lg">
                        Hapus
                    </button>
                    @endif
                </div>
            </div>

            <!-- Category Pills Bar -->
            <div class="mt-8 flex items-center gap-2 overflow-x-auto no-scrollbar pb-2">
                <button 
                    wire:click="setCategory('')" 
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap {{ empty($categorySlug) ? 'bg-terra-500 text-white shadow-md shadow-terra-500/30' : 'bg-slate-800/80 text-slate-300 hover:bg-slate-700 hover:text-white border border-slate-700/50' }}">
                    Semua Kategori
                </button>
                @foreach($categories as $cat)
                <button 
                    wire:click="setCategory('{{ $cat->slug }}')"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 whitespace-nowrap {{ $categorySlug === $cat->slug ? 'bg-terra-500 text-white shadow-md shadow-terra-500/30' : 'bg-slate-800/80 text-slate-300 hover:bg-slate-700 hover:text-white border border-slate-700/50' }}">
                    <span>{{ $cat->name }}</span>
                    <span class="text-[10px] opacity-75">({{ $cat->articles_count }})</span>
                </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="bg-slate-50 dark:bg-slate-950 py-12 sm:py-16 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Active Filter Badge Notification -->
            @if($search || $categorySlug || $tag)
            <div class="mb-8 flex items-center justify-between bg-white dark:bg-slate-900 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-soft-sm flex-wrap gap-3">
                <div class="flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                    <span class="font-bold">Filter aktif:</span>
                    @if($activeCategory)
                        <span class="inline-flex items-center gap-1 bg-terra-50 dark:bg-terra-500/20 text-terra-700 dark:text-terra-400 font-semibold px-2.5 py-1 rounded-lg text-xs border border-terra-200 dark:border-terra-500/30">
                            Kategori: {{ $activeCategory->name }}
                        </span>
                    @endif
                    @if($search)
                        <span class="inline-flex items-center gap-1 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-semibold px-2.5 py-1 rounded-lg text-xs">
                            Pencarian: "{{ $search }}"
                        </span>
                    @endif
                    @if($tag)
                        <span class="inline-flex items-center gap-1 bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 font-semibold px-2.5 py-1 rounded-lg text-xs border border-amber-200 dark:border-amber-800">
                            Tag: #{{ $tag }}
                        </span>
                    @endif
                </div>
                <button wire:click="clearFilters" class="text-xs font-bold text-terra-600 dark:text-terra-400 hover:underline transition">
                    Reset Filter
                </button>
            </div>
            @endif

            <!-- Hero Featured Article (Only on page 1 without filters) -->
            @if($featuredArticle)
            <div class="mb-14">
                <div class="relative bg-white dark:bg-slate-900 rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-luxury dark:shadow-luxury-dark hover:shadow-2xl transition-all duration-300 group grid grid-cols-1 lg:grid-cols-12">
                    <div class="lg:col-span-7 relative h-72 sm:h-96 lg:h-full overflow-hidden">
                        <img 
                            src="{{ $featuredArticle->thumbnail_url }}" 
                            alt="{{ $featuredArticle->thumbnail_alt ?: $featuredArticle->title }}" 
                            loading="eager"
                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent lg:hidden"></div>
                        <div class="absolute top-4 left-4 z-10">
                            <span class="bg-terra-500 text-white font-bold text-xs uppercase px-3 py-1.5 rounded-xl shadow-lg tracking-wider">
                                🔥 Sorotan Utama
                            </span>
                        </div>
                    </div>

                    <div class="lg:col-span-5 p-6 sm:p-8 lg:p-10 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 mb-3">
                                @if($featuredArticle->category)
                                <span class="text-terra-600 dark:text-terra-400 font-bold uppercase tracking-wider">{{ $featuredArticle->category->name }}</span>
                                <span>•</span>
                                @endif
                                <span>{{ $featuredArticle->published_at?->translatedFormat('d F Y') }}</span>
                                <span>•</span>
                                <span>{{ $featuredArticle->reading_time }} mnt baca</span>
                            </div>

                            <a href="{{ route('article.detail', $featuredArticle->slug) }}" class="block group-hover:text-terra-600 dark:group-hover:text-terra-400 transition-colors">
                                <h2 class="font-display text-xl sm:text-2xl lg:text-3xl font-black text-slate-900 dark:text-white leading-tight mb-4">
                                    {{ $featuredArticle->title }}
                                </h2>
                            </a>

                            <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base leading-relaxed line-clamp-3 sm:line-clamp-4 mb-6">
                                {{ $featuredArticle->excerpt }}
                            </p>
                        </div>

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-slate-900 dark:bg-slate-800 text-terra-400 flex items-center justify-center font-bold text-xs font-display border border-slate-700">
                                    IR
                                </div>
                                <span class="text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $featuredArticle->author_name }}</span>
                            </div>

                            <a href="{{ route('article.detail', $featuredArticle->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-terra-600 dark:text-terra-400 group-hover:underline transition">
                                <span>Baca Selengkapnya</span>
                                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Article Grid -->
            @if($articles->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($articles as $article)
                <article class="bg-white dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-soft hover:shadow-xl transition-all duration-300 flex flex-col group hover:-translate-y-1">
                    <!-- Thumbnail -->
                    <a href="{{ route('article.detail', $article->slug) }}" class="relative block aspect-[16/10] overflow-hidden bg-slate-100 dark:bg-slate-800">
                        <img 
                            src="{{ $article->thumbnail_url }}" 
                            alt="{{ $article->thumbnail_alt ?: $article->title }}" 
                            loading="lazy"
                            decoding="async"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        />
                        @if($article->category)
                        <span class="absolute top-3 left-3 bg-slate-900/80 backdrop-blur-md text-white text-[11px] font-bold px-2.5 py-1 rounded-lg">
                            {{ $article->category->name }}
                        </span>
                        @endif
                    </a>

                    <!-- Body -->
                    <div class="p-6 flex flex-col flex-grow">
                        <div class="flex items-center gap-2 text-xs text-slate-400 dark:text-slate-500 mb-2.5">
                            <span>{{ $article->published_at?->translatedFormat('d M Y') }}</span>
                            <span>•</span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $article->reading_time }} mnt baca
                            </span>
                        </div>

                        <a href="{{ route('article.detail', $article->slug) }}" class="block flex-grow group-hover:text-terra-600 dark:group-hover:text-terra-400 transition-colors">
                            <h3 class="font-display text-lg font-bold text-slate-900 dark:text-white leading-snug mb-3 line-clamp-2">
                                {{ $article->title }}
                            </h3>
                            <p class="text-slate-600 dark:text-slate-400 text-xs sm:text-sm leading-relaxed line-clamp-3 mb-4">
                                {{ $article->excerpt }}
                            </p>
                        </a>

                        <!-- Tags / Read More -->
                        <div class="pt-4 mt-auto border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium truncate max-w-[150px]">
                                By {{ $article->author_name }}
                            </span>
                            <a href="{{ route('article.detail', $article->slug) }}" class="text-xs font-bold text-terra-600 dark:text-terra-400 hover:underline flex items-center gap-1">
                                Baca &rarr;
                            </a>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $articles->links() }}
            </div>

            @else
            <!-- Empty State -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-12 text-center border border-slate-200 dark:border-slate-800 shadow-soft max-w-lg mx-auto">
                <div class="w-16 h-16 bg-terra-50 dark:bg-terra-500/20 text-terra-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Tidak Ada Artikel Ditemukan</h3>
                <p class="text-slate-500 dark:text-slate-400 text-sm mb-6">
                    Maaf, tidak ditemukan artikel yang sesuai dengan pencarian atau filter yang Anda pilih.
                </p>
                <button wire:click="clearFilters" class="px-5 py-2.5 bg-terra-500 hover:bg-terra-600 text-white text-xs font-bold rounded-xl shadow-md transition">
                    Lihat Semua Artikel
                </button>
            </div>
            @endif

        </div>
    </div>
</div>
