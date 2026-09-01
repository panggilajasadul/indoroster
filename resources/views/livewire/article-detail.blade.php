<div>
    @php
        $breadcrumbList = [
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

        if ($article->category) {
            $breadcrumbList['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $article->category->name,
                'item' => route('article.index', ['kategori' => $article->category->slug]),
            ];
            $breadcrumbList['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => 4,
                'name' => $article->title,
                'item' => route('article.detail', $article->slug),
            ];
        } else {
            $breadcrumbList['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $article->title,
                'item' => route('article.detail', $article->slug),
            ];
        }

        $articleSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('article.detail', $article->slug),
            ],
            'headline' => $article->title,
            'description' => $article->meta_description ?: $article->excerpt,
            'image' => [
                $article->thumbnail_url,
            ],
            'datePublished' => $article->published_at?->toIso8601String(),
            'dateModified' => $article->updated_at?->toIso8601String(),
            'author' => [
                '@type' => 'Person',
                'name' => $article->author_name,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'IndoRoster Indonesia',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('assets/logo_indoroster_no_text.PNG'),
                ],
            ],
        ];
    @endphp

    <article class="bg-white dark:bg-slate-950 min-h-screen py-10 sm:py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumbs Navigation -->
            @php
                $articleBreadcrumbs = [
                    ['label' => 'Artikel & Edukasi', 'url' => route('article.index')],
                ];
                if ($article->category) {
                    $articleBreadcrumbs[] = ['label' => $article->category->name, 'url' => route('article.index', ['kategori' => $article->category->slug])];
                }
                $articleBreadcrumbs[] = ['label' => $article->title];
            @endphp
            <x-breadcrumb :items="$articleBreadcrumbs" class="!px-0 !py-0 mb-8" />

            <!-- Article Header -->
            <header class="mb-10">
                @if($article->category)
                <div class="mb-4">
                    <a href="{{ route('article.index', ['kategori' => $article->category->slug]) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-terra-50 dark:bg-terra-500/15 text-terra-600 dark:text-terra-400 text-xs font-bold uppercase tracking-wider border border-terra-200/80 dark:border-terra-500/30 hover:bg-terra-100 dark:hover:bg-terra-500/25 transition">
                        {{ $article->category->name }}
                    </a>
                </div>
                @endif

                <h1 class="font-display text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white leading-tight mb-6 tracking-tight">
                    {{ $article->title }}
                </h1>

                @if($article->excerpt)
                <p class="text-slate-600 dark:text-slate-300 text-base sm:text-lg leading-relaxed mb-6 font-medium border-l-4 border-terra-500 pl-4 py-1 bg-slate-50 dark:bg-slate-900 rounded-r-xl">
                    {{ $article->excerpt }}
                </p>
                @endif

                <!-- Meta Info (Author, Date, Reading Time, Views) -->
                <div class="flex flex-wrap items-center justify-between gap-4 py-4 border-y border-slate-100 dark:border-slate-800 text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-slate-900 dark:bg-slate-800 text-terra-400 flex items-center justify-center font-bold text-sm font-display shadow-xs border border-slate-700">
                            IR
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 dark:text-white">{{ $article->author_name }}</p>
                            <p class="text-xs text-slate-400 dark:text-slate-500">Tim Publikasi Pabrik IndoRoster</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            {{ $article->published_at?->translatedFormat('d F Y') }}
                        </span>
                        <span>•</span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $article->reading_time }} mnt baca
                        </span>
                        <span>•</span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            {{ number_format($article->views_count) }} views
                        </span>
                    </div>
                </div>
            </header>

            <!-- Featured Hero Image -->
            @if($article->thumbnail)
            <div class="mb-12 rounded-3xl overflow-hidden shadow-luxury border border-slate-100 dark:border-slate-800 bg-slate-100 dark:bg-slate-900">
                <img 
                    src="{{ $article->thumbnail_url }}" 
                    alt="{{ $article->thumbnail_alt ?: $article->title }}" 
                    loading="eager"
                    class="w-full max-h-[500px] object-cover"
                />
                @if($article->thumbnail_alt)
                <p class="text-center text-xs text-slate-500 dark:text-slate-400 py-3 bg-slate-50 dark:bg-slate-900/90 italic border-t border-slate-100 dark:border-slate-800">
                    {{ $article->thumbnail_alt }}
                </p>
                @endif
            </div>
            @endif

            <!-- Article Body Content -->
            <div class="prose prose-lg prose-slate dark:prose-invert max-w-none article-content font-sans prose-a:no-underline [&_a]:no-underline [&_a]:hover:no-underline [&_a.bg-emerald-600]:!text-white [&_a.bg-emerald-600_*]:!text-white [&_a.bg-emerald-600_svg]:!fill-white [&_a.bg-emerald-600_svg]:!text-white [&_.btn-variant]:!text-slate-800 dark:[&_.btn-variant]:!text-slate-100 [&_.btn-variant]:hover:!text-slate-900">
                {!! $article->content !!}
            </div>

            <!-- Tags Section -->
            @if(!empty($article->tags) && is_array($article->tags))
            <div class="mt-12 pt-8 border-t border-slate-200 dark:border-slate-800">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Topik Terkait:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($article->tags as $tag)
                    <a href="{{ route('article.index', ['tag' => $tag]) }}" class="text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-terra-50 dark:hover:bg-slate-700 hover:text-terra-600 dark:hover:text-terra-400 px-3 py-1.5 rounded-lg border border-slate-200/80 dark:border-slate-700 transition-colors">
                        #{{ $tag }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Social Share Bar (Interactive) -->
            <div class="mt-8 p-6 bg-slate-900 dark:bg-slate-900 text-white rounded-3xl border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xl" x-data="{ copied: false }">
                <div>
                    <h4 class="font-bold font-display text-base text-white">Bagikan Artikel Ini</h4>
                    <p class="text-xs text-slate-400">Bantu teman atau arsitek lainnya mendapatkan inspirasi ini.</p>
                </div>

                <div class="flex items-center gap-2">
                    <!-- WhatsApp -->
                    <a 
                        href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' - ' . route('article.detail', $article->slug)) }}" 
                        target="_blank" 
                        rel="noopener noreferrer"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl flex items-center gap-1.5 transition shadow-sm"
                        aria-label="Bagikan ke WhatsApp">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>WhatsApp</span>
                    </a>

                    <!-- Copy Link -->
                    <button 
                        @click="navigator.clipboard.writeText('{{ route('article.detail', $article->slug) }}'); copied = true; setTimeout(() => copied = false, 2500)"
                        class="px-3.5 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-bold rounded-xl flex items-center gap-1.5 transition border border-slate-700">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                        <span x-text="copied ? 'Tersalin!' : 'Salin Link'">Salin Link</span>
                    </button>
                </div>
            </div>

            <!-- Author & Factory Consultation Box -->
            <div class="mt-12 p-8 rounded-3xl bg-gradient-to-br from-terra-50 dark:from-terra-950/30 via-orange-50/50 dark:via-slate-900 to-amber-50 dark:to-slate-900 border border-terra-200/80 dark:border-terra-500/30 shadow-soft">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                    <img src="{{ asset('assets/logo_indoroster_no_text.PNG') }}" alt="IndoRoster Logo" class="w-16 h-16 object-contain p-2 bg-white dark:bg-slate-800 rounded-2xl border border-terra-200 dark:border-slate-700 shadow-sm shrink-0">
                    <div class="flex-grow">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">Konsultasi Produsen & Pabrik Roster</span>
                        <h3 class="font-display text-xl font-bold text-slate-900 dark:text-white mb-2">Butuh Suplai Roster Langsung dari Pabrik?</h3>
                        <p class="text-slate-600 dark:text-slate-300 text-xs sm:text-sm leading-relaxed mb-4">
                            Sebagai produsen dan supplier tangan pertama, tim teknis pabrik kami siap membantu menghitung estimasi kebutuhan keping roster, rekomendasi motif yang presisi, serta simulasi ongkir langsung dari sentra produksi Plered Purwakarta ke lokasi proyek Anda.
                        </p>
                        @php
                            $rawWa = \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
                            $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
                            if (str_starts_with($waNumber, '0')) {
                                $waNumber = '62' . substr($waNumber, 1);
                            }
                            $waText = urlencode("Halo IndoRoster, saya membaca artikel '{$article->title}' dan ingin konsultasi roster beton untuk proyek saya.");
                        @endphp
                        <div class="flex items-center gap-3 flex-wrap">
                            <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                <span>Konsultasi via WhatsApp</span>
                            </a>
                            <a href="{{ route('catalog') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold text-xs rounded-xl border border-slate-300 dark:border-slate-700 shadow-xs transition">
                                <span>Lihat Katalog Produk</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Core Pillar Hubs & Tools (Silo Inbound Links) -->
            <div class="mt-12 p-6 rounded-3xl bg-slate-50 dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800">
                <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400 block mb-2">Panduan Pilar & Katalog Terkait</span>
                <h3 class="text-base font-bold text-slate-900 dark:text-white mb-4">Eksplorasi Roster & Alat Desain IndoRoster</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                    <a href="/roster-beton" class="p-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/70 dark:border-slate-700 hover:border-terra-500 text-xs font-bold text-slate-800 dark:text-slate-200 hover:text-terra-600 transition flex items-center gap-1.5 shadow-2xs">
                        <span>🧱</span>
                        <span class="truncate">Roster Beton</span>
                    </a>
                    <a href="/roster-minimalis" class="p-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/70 dark:border-slate-700 hover:border-terra-500 text-xs font-bold text-slate-800 dark:text-slate-200 hover:text-terra-600 transition flex items-center gap-1.5 shadow-2xs">
                        <span>✨</span>
                        <span class="truncate">Roster Minimalis</span>
                    </a>
                    <a href="/roster-beton-minimalis" class="p-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/70 dark:border-slate-700 hover:border-terra-500 text-xs font-bold text-slate-800 dark:text-slate-200 hover:text-terra-600 transition flex items-center gap-1.5 shadow-2xs">
                        <span>🏭</span>
                        <span class="truncate">Pabrik Plered</span>
                    </a>
                    <a href="{{ route('tools.calculator') }}" class="p-3 rounded-xl bg-white dark:bg-slate-800 border border-slate-200/70 dark:border-slate-700 hover:border-terra-500 text-xs font-bold text-slate-800 dark:text-slate-200 hover:text-terra-600 transition flex items-center gap-1.5 shadow-2xs">
                        <span>🧮</span>
                        <span class="truncate">Kalkulator m²</span>
                    </a>
                </div>
            </div>

            <!-- Recommended Products Section -->
            @if(isset($featuredProducts) && $featuredProducts->count() > 0)
            <div class="mt-12 pt-10 border-t border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">Rekomendasi Material</span>
                        <h3 class="font-display text-xl font-bold text-slate-900 dark:text-white">Motif Roster Beton Terpopuler</h3>
                    </div>
                    <a href="{{ route('catalog') }}" class="text-xs font-bold text-terra-600 dark:text-terra-400 hover:underline">
                        Lihat Katalog Lengkap &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                    @foreach($featuredProducts as $prod)
                    @php
                        $media = $prod->primary_media;
                        $img = ($media && $media->media_type === 'image') ? $media->formatted_url : ($prod->primary_image ?: asset('assets/logo_indoroster_no_text.PNG'));
                    @endphp
                    <a href="{{ route('product.detail', $prod->slug) }}" class="group bg-slate-50 dark:bg-slate-900 p-3 rounded-2xl border border-slate-200/80 dark:border-slate-800 hover:border-terra-500 transition-all flex flex-col justify-between shadow-2xs">
                        <div class="aspect-square rounded-xl overflow-hidden bg-slate-200 dark:bg-slate-800 mb-2.5">
                            <img src="{{ $img }}" alt="{{ $prod->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                        </div>
                        <div>
                            <span class="text-[10px] text-terra-600 dark:text-terra-400 font-bold block mb-0.5">{{ $prod->category->name ?? 'Roster Beton' }}</span>
                            <h4 class="font-bold text-xs text-slate-900 dark:text-white line-clamp-1 group-hover:text-terra-600 transition-colors">{{ $prod->name }}</h4>
                            <div class="text-xs font-black text-[#ee4d2d] dark:text-terra-400 mt-1">{{ $prod->formatted_price_range }}</div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Related Articles -->
            @if($relatedArticles->count() > 0)
            <div class="mt-16 pt-12 border-t border-slate-200 dark:border-slate-800">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">Rekomendasi Bacaan</span>
                        <h2 class="font-display text-2xl font-bold text-slate-900 dark:text-white">Artikel Terkait Lainnya</h2>
                    </div>
                    <a href="{{ route('article.index') }}" class="text-xs font-bold text-terra-600 dark:text-terra-400 hover:underline flex items-center gap-1">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedArticles as $rel)
                    <article class="bg-slate-50 dark:bg-slate-900 rounded-2xl overflow-hidden border border-slate-200/80 dark:border-slate-800 shadow-soft hover:shadow-lg transition-all group flex flex-col">
                        <a href="{{ route('article.detail', $rel->slug) }}" class="block aspect-[16/10] overflow-hidden bg-slate-200 dark:bg-slate-800">
                            <img 
                                src="{{ $rel->thumbnail_url }}" 
                                alt="{{ $rel->thumbnail_alt ?: $rel->title }}" 
                                loading="lazy"
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            />
                        </a>
                        <div class="p-4 flex flex-col flex-grow">
                            <div class="text-[11px] text-slate-400 dark:text-slate-500 mb-1.5 flex items-center gap-1">
                                <span>{{ $rel->published_at?->translatedFormat('d M Y') }}</span>
                                <span>•</span>
                                <span>{{ $rel->reading_time }} mnt</span>
                            </div>
                            <a href="{{ route('article.detail', $rel->slug) }}" class="block group-hover:text-terra-600 dark:group-hover:text-terra-400 transition-colors flex-grow">
                                <h4 class="font-display font-bold text-sm text-slate-900 dark:text-white leading-snug line-clamp-2 mb-2">
                                    {{ $rel->title }}
                                </h4>
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </article>

    @push('seo')
    <script type="application/ld+json">
    {!! json_encode($articleSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($breadcrumbList, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @endpush
</div>
