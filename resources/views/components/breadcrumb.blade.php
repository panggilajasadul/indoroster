@props([
    'items' => [],
    'class' => '',
    'variant' => 'auto', // 'auto' (adaptive light/dark), 'dark' (for dark hero backgrounds), 'light' (for light backgrounds)
])

@php
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Beranda',
                'item' => url('/'),
            ]
        ]
    ];

    $pos = 2;
    foreach ($items as $item) {
        $name = is_array($item) ? ($item['label'] ?? '') : (string)$item;
        $url = is_array($item) ? ($item['url'] ?? null) : null;
        
        $entry = [
            '@type' => 'ListItem',
            'position' => $pos,
            'name' => $name,
        ];
        if ($url) {
            $entry['item'] = str_starts_with($url, 'http') ? $url : url($url);
        }
        
        $breadcrumbSchema['itemListElement'][] = $entry;
        $pos++;
    }

    // Styling configurations based on variant
    $isDark = $variant === 'dark';
    
    $containerClass = $isDark 
        ? 'text-slate-300 font-medium' 
        : 'text-slate-500 dark:text-slate-400 font-medium';
        
    $homeLinkClass = $isDark 
        ? 'text-slate-300 hover:text-terra-400' 
        : 'text-slate-600 dark:text-slate-400 hover:text-terra-600 dark:hover:text-terra-400';
        
    $iconClass = $isDark 
        ? 'text-slate-400' 
        : 'text-slate-400 dark:text-slate-500';
        
    $separatorClass = $isDark 
        ? 'text-slate-500' 
        : 'text-slate-300 dark:text-slate-600';
        
    $linkClass = $isDark 
        ? 'text-slate-300 hover:text-terra-400' 
        : 'text-slate-600 dark:text-slate-400 hover:text-terra-600 dark:hover:text-terra-400';
        
    $activeClass = $isDark 
        ? 'font-bold text-white' 
        : 'font-bold text-slate-900 dark:text-white';
@endphp

<!-- Breadcrumb Visual Navigation -->
<nav aria-label="Breadcrumb" class="{{ $class }}">
    <ol class="flex items-center flex-wrap gap-1.5 text-xs {{ $containerClass }}">
        <!-- Home Item -->
        <li class="inline-flex items-center">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-1.5 {{ $homeLinkClass }} transition-colors">
                <svg class="w-3.5 h-3.5 {{ $iconClass }} shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Beranda</span>
            </a>
        </li>

        @foreach($items as $index => $item)
            @php
                $label = is_array($item) ? ($item['label'] ?? '') : (string)$item;
                $url = is_array($item) ? ($item['url'] ?? null) : null;
                $isLast = $loop->last;
            @endphp
            
            <li class="select-none">
                <span class="{{ $separatorClass }}">/</span>
            </li>

            @if($url && !$isLast)
                <li class="inline-flex items-center">
                    <a href="{{ $url }}" class="{{ $linkClass }} transition-colors">
                        {{ $label }}
                    </a>
                </li>
            @else
                <li class="{{ $activeClass }} line-clamp-1" aria-current="page">
                    {{ $label }}
                </li>
            @endif
        @endforeach
    </ol>
</nav>

<!-- Structured Data JSON-LD for Google Search Console & SEO -->
<script type="application/ld+json">
{!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
