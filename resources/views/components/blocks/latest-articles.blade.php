@props(['data'])

@php
    $badge = $data['badge'] ?? 'BLOG & EDUKASI ARSITEKTUR';
    $title = $data['title'] ?? 'Tips, Inspirasi & Panduan Pasang Roster';
    $subtitle = $data['subtitle'] ?? 'Kumpulan artikel informatif untuk membantu perencanaan fasad dan dinding ventilasi rumah Anda.';
    $limit = (int) ($data['limit'] ?? 3);
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');

    $articles = \App\Models\Article::published()->with('category')->latest('published_at')->take($limit)->get();
@endphp

@if($articles->count() > 0)
<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden font-sans">
    <x-blocks._bg-theme :theme="$theme" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-14">
            <div class="max-w-2xl">
                @if(!empty($badge))
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-black uppercase tracking-widest mb-4 shadow-soft-xs">
                    <span>{{ $badge }}</span>
                </div>
                @endif

                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black {{ $theme->headingColor }} tracking-tight leading-tight">
                    {{ $title }}
                </h2>
            </div>

            <a href="{{ route('article.index') }}" class="inline-flex items-center gap-2 text-sm font-black text-terra-500 hover:text-terra-600 uppercase tracking-wider group shrink-0">
                <span>Lihat Semua Artikel</span>
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>

        <!-- Articles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($articles as $article)
            <article class="rounded-3xl {{ $theme->cardBg }} border overflow-hidden shadow-soft-xs hover:shadow-luxury transition-all duration-500 hover:-translate-y-2 group flex flex-col justify-between">
                <div>
                    <!-- Thumbnail Box -->
                    <a href="{{ route('article.detail', $article->slug) }}" class="block relative aspect-16/10 overflow-hidden bg-slate-900">
                        @if($article->thumbnail_url)
                            <img src="{{ $article->thumbnail_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-slate-800 text-slate-600">
                                <svg class="w-12 h-12 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                        @endif
                        <div class="absolute top-3 left-3 px-3 py-1 rounded-lg bg-terra-500 text-white text-[11px] font-black uppercase tracking-wider shadow-md">
                            {{ $article->category?->name ?? 'Tips Roster' }}
                        </div>
                    </a>

                    <!-- Content Body -->
                    <div class="p-6 sm:p-7">
                        <div class="text-xs text-slate-400 font-semibold mb-2.5">
                            {{ $article->published_at?->translatedFormat('d M Y') ?? $article->created_at->translatedFormat('d M Y') }} • {{ $article->views_count }}x Dibaca
                        </div>

                        <h3 class="text-lg font-bold {{ $theme->cardTitle }} group-hover:text-terra-500 transition-colors leading-snug mb-3 line-clamp-2">
                            <a href="{{ route('article.detail', $article->slug) }}">
                                {{ $article->title }}
                            </a>
                        </h3>

                        <p class="text-xs sm:text-sm {{ $theme->cardDesc }} leading-relaxed line-clamp-3">
                            {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 120) }}
                        </p>
                    </div>
                </div>

                <!-- Read More Footer -->
                <div class="px-6 pb-6 pt-0">
                    <a href="{{ route('article.detail', $article->slug) }}" class="text-xs font-black text-terra-500 group-hover:text-terra-600 flex items-center gap-1.5 uppercase tracking-wider">
                        <span>Baca Selengkapnya</span>
                        <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
            </article>
            @endforeach
        </div>

    </div>
</section>
@endif
