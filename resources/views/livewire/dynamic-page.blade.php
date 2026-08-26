<div>
    @php
        $pageSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $page->title,
            'description' => $page->meta_description ?: $page->title,
            'url' => route('dynamic.page', $page->slug),
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
                    'name' => $page->title,
                    'item' => route('dynamic.page', $page->slug),
                ],
            ],
        ];
    @endphp

    @push('seo')
    <script type="application/ld+json">
    {!! json_encode($pageSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @endpush

    @if(is_array($page->content) && count($page->content) > 0 && isset($page->content[0]['type']) && $page->content[0]['type'] !== 'rich_text')
        @php
            $firstBlockType = $page->content[0]['type'] ?? '';
            $hasHeroFirst = $firstBlockType === 'hero';
        @endphp
        @if(!$hasHeroFirst)
            <div class="bg-slate-50 dark:bg-slate-950 border-b border-slate-200/70 dark:border-slate-800/70 py-3.5">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <x-breadcrumb :items="[['label' => $page->title]]" class="!px-0 !py-0" />
                </div>
            </div>
        @endif
        <x-block-renderer :blocks="$page->content" :page-title="$hasHeroFirst ? $page->title : null" />
    @elseif(is_array($page->content) && count($page->content) > 0 && isset($page->content[0]['type']) && $page->content[0]['type'] === 'rich_text')
        <div class="bg-white dark:bg-slate-900 min-h-screen py-12 sm:py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumbs -->
                <x-breadcrumb :items="[['label' => $page->title]]" class="!px-0 !py-0 mb-8" />

                <!-- Header -->
                <div class="mb-10 text-left border-b border-slate-100 dark:border-slate-800 pb-8">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-xs font-bold uppercase tracking-wider mb-3">
                        <svg class="w-3.5 h-3.5 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                        Informasi Resmi IndoRoster
                    </div>
                    <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">
                        {{ $page->title }}
                    </h1>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-2">
                        Pembaruan Terakhir: {{ $page->updated_at?->translatedFormat('d F Y') ?? date('d F Y') }}
                    </p>
                </div>

                <!-- Content -->
                <div class="prose prose-lg prose-slate dark:prose-invert max-w-none article-content font-sans">
                    {!! $page->content[0]['data']['content'] ?? '' !!}
                </div>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-slate-900 min-h-screen py-12 sm:py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumbs -->
                <x-breadcrumb :items="[['label' => $page->title]]" class="!px-0 !py-0 mb-8" />

                <!-- Header -->
                <div class="mb-10 text-left border-b border-slate-100 dark:border-slate-800 pb-8">
                    <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white tracking-tight leading-tight">
                        {{ $page->title }}
                    </h1>
                </div>

                <!-- Content -->
                <div class="prose prose-lg prose-slate dark:prose-invert max-w-none article-content font-sans">
                    {!! is_string($page->content) ? $page->content : '' !!}
                </div>
            </div>
        </div>
    @endif
</div>
