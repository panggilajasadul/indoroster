@props(['data'])

@php
    $items = $data['items'] ?? [];
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'dark');
@endphp

<div class="py-6 {{ $theme->bgClasses }} overflow-hidden border-y border-white/10 relative">
    <x-blocks._bg-theme :theme="$theme" />
    <div class="flex whitespace-nowrap animate-marquee relative z-10">
        @for($i = 0; $i < 4; $i++)
        <div class="flex items-center gap-12 px-6">
            @foreach($items as $item)
            <span class="flex items-center gap-3 font-black text-xs uppercase tracking-[0.2em] {{ $theme->headingColor }}">
                <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                {{ $item['text'] ?? '' }}
            </span>
            @endforeach
        </div>
        @endfor
    </div>
</div>
