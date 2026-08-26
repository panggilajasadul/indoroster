@props(['data'])

@php
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');
    $title = $data['title'] ?? null;
    $subtitle = $data['subtitle'] ?? null;
    $badge = $data['badge'] ?? null;
    $source = $data['source'] ?? 'auto';
    $iconShape = $data['icon_shape'] ?? 'circle'; // circle, squircle, card
    $showProductCount = !empty($data['show_product_count']);
    $categoriesList = [];

    if ($source === 'auto') {
        $dbCategories = \App\Models\Category::where('is_active', true)
            ->withCount(['products' => fn($q) => $q->where('is_active', true)])
            ->with(['products' => function($q) {
                $q->where('is_active', true)
                  ->with(['media' => fn($m) => $m->where('media_type', 'image')->orderBy('is_primary', 'desc')])
                  ->take(1);
            }])
            ->orderBy('sort_order', 'asc')
            ->get();

        foreach ($dbCategories as $cat) {
            $img = $cat->image_url;
            if (empty($img)) {
                $firstProd = $cat->products->first();
                $firstMedia = $firstProd?->media->first();
                if ($firstMedia) {
                    $img = str_starts_with($firstMedia->media_url, 'http') ? $firstMedia->media_url : asset('storage/' . ltrim($firstMedia->media_url, '/'));
                }
            } else {
                $img = str_starts_with($img, 'http') ? $img : asset('storage/' . ltrim($img, '/'));
            }

            // Fallback decorative styling if no image found
            $categoriesList[] = [
                'name' => $cat->name,
                'slug' => $cat->slug,
                'image_url' => $img,
                'link' => url('/katalog/' . $cat->slug),
                'count' => $cat->products_count,
            ];
        }
    } else {
        $rawItems = $data['items'] ?? [];
        foreach ($rawItems as $item) {
            $upload = $item['image_upload'] ?? null;
            $url = $item['image_url'] ?? null;
            $file = !empty($upload) ? (is_array($upload) ? array_values($upload)[0] : $upload) : $url;
            $img = $file ? (str_starts_with($file, 'http') ? $file : asset('storage/' . ltrim($file, '/'))) : null;

            $categoriesList[] = [
                'name' => $item['name'] ?? 'Kategori',
                'image_url' => $img,
                'link' => $item['link'] ?: url('/katalog'),
                'count' => $item['badge'] ?? null,
            ];
        }
    }
@endphp

<section class="py-8 sm:py-12 {{ $theme->bgClasses }} relative overflow-hidden select-none">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Header if set --}}
        @if($title || $subtitle || $badge)
            <div class="mb-6 sm:mb-10 text-center max-w-3xl mx-auto">
                @if($badge)
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $theme->badgeClass }} text-xs font-bold uppercase tracking-wider mb-2.5 shadow-sm">
                        <x-heroicon-m-tag class="w-3.5 h-3.5 text-terra-500" />
                        {{ $badge }}
                    </div>
                @endif
                @if($title)
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-display {{ $theme->headingColor }} tracking-tight leading-tight">
                        {!! $title !!}
                    </h2>
                @endif
                @if($subtitle)
                    <p class="mt-2 text-sm sm:text-base {{ $theme->subColor }} leading-relaxed">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        @endif

        {{-- Categories Scrollable / Grid Container --}}
        @if(count($categoriesList) > 0)
            <div class="relative">
                <div class="flex items-center justify-start md:justify-center gap-4 sm:gap-6 md:gap-8 overflow-x-auto pb-4 pt-2 px-2 no-scrollbar scroll-smooth">
                    @foreach($categoriesList as $category)
                        <a 
                            href="{{ $category['link'] }}" 
                            class="group flex flex-col items-center shrink-0 w-24 sm:w-28 md:w-32 text-center transition-all duration-300 transform hover:-translate-y-1.5 focus:outline-none">
                            
                            {{-- Circular / Squircle Icon Card Container --}}
                            <div class="relative mb-2.5 sm:mb-3">
                                @php
                                    $shapeClasses = match($iconShape) {
                                        'squircle' => 'rounded-2xl sm:rounded-3xl',
                                        'card' => 'rounded-xl',
                                        default => 'rounded-full',
                                    };
                                @endphp

                                <div class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 {{ $shapeClasses }} bg-white dark:bg-slate-800 p-2 sm:p-2.5 shadow-md shadow-slate-200/60 dark:shadow-slate-950/40 border-2 border-slate-100 dark:border-slate-700/80 group-hover:border-terra-500 dark:group-hover:border-terra-500 group-hover:shadow-xl group-hover:shadow-terra-500/20 transition-all duration-300 flex items-center justify-center overflow-hidden">
                                    @if(!empty($category['image_url']))
                                        <img 
                                            src="{{ $category['image_url'] }}" 
                                            alt="{{ $category['name'] }}" 
                                            loading="lazy"
                                            class="w-full h-full object-contain p-0.5 transform group-hover:scale-110 transition-transform duration-300" />
                                    @else
                                        {{-- Stylized Fallback Architectural Icon --}}
                                        <div class="w-full h-full rounded-full bg-gradient-to-br from-terra-50 to-amber-50 dark:from-slate-700 dark:to-slate-800 flex items-center justify-center text-terra-600 dark:text-terra-400 group-hover:scale-110 transition-transform duration-300">
                                            <x-heroicon-o-squares-2x2 class="w-7 h-7 sm:w-9 sm:h-9" />
                                        </div>
                                    @endif
                                </div>

                                {{-- Notification Badge / Count --}}
                                @if($showProductCount && !empty($category['count']))
                                    <span class="absolute -top-1 -right-1 px-1.5 py-0.5 text-[10px] font-bold rounded-full bg-terra-500 text-white shadow-sm">
                                        {{ $category['count'] }}
                                    </span>
                                @endif
                            </div>

                            {{-- Category Title Label --}}
                            <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-terra-600 dark:group-hover:text-terra-400 leading-snug line-clamp-2 transition-colors duration-200">
                                {{ $category['name'] }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-8 text-slate-400 text-sm">
                Belum ada kategori untuk ditampilkan.
            </div>
        @endif
    </div>
</section>
