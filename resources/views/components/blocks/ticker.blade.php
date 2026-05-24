@props(['data'])

@php
    $items = $data['items'] ?? [];
@endphp

<div class="py-6 bg-black text-white overflow-hidden border-y border-white/10">
    <div class="flex whitespace-nowrap animate-marquee">
        @for($i = 0; $i < 4; $i++)
        <div class="flex items-center gap-12 px-6">
            @foreach($items as $item)
            <span class="flex items-center gap-3 font-black text-xs uppercase tracking-[0.2em]">
                <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                {{ $item['text'] ?? '' }}
            </span>
            @endforeach
        </div>
        @endfor
    </div>
</div>
