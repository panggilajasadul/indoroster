@props(['data'])

@php
    $badge = $data['badge'] ?? '';
    $title = $data['title'] ?? '';
    $description = $data['description'] ?? '';
    $stats = $data['stats'] ?? [];
    $bg = $data['bg_theme'] ?? 'white';
    
    $bgClasses = match($bg) {
        'dark' => 'bg-slate-900 text-white',
        'accent' => 'bg-accent text-black',
        'slate' => 'bg-slate-50 text-slate-900',
        default => 'bg-white text-slate-900',
    };
    $subtextClass = match($bg) {
        'dark' => 'text-slate-400',
        'accent' => 'text-black/60',
        default => 'text-slate-500',
    };
    $statValueClass = match($bg) {
        'dark' => 'text-accent',
        'accent' => 'text-black',
        default => 'text-terra-500',
    };
    $dividerClass = match($bg) {
        'dark' => 'border-slate-700',
        'accent' => 'border-black/10',
        default => 'border-slate-200',
    };
@endphp

<section class="py-20 {{ $bgClasses }} relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($badge || $title || $description)
        <div class="text-center mb-16">
            @if($badge)
            <span class="font-black text-xs uppercase tracking-[0.3em] mb-4 block {{ $statValueClass }}">{{ $badge }}</span>
            @endif
            @if($title)
            <h2 class="text-3xl md:text-5xl font-black font-display leading-tight mb-4">{!! $title !!}</h2>
            @endif
            @if($description)
            <p class="{{ $subtextClass }} max-w-2xl mx-auto text-lg">{!! $description !!}</p>
            @endif
        </div>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-{{ min(count($stats), 4) }} gap-8 md:gap-12">
            @foreach($stats as $index => $stat)
            <div class="text-center {{ $index > 0 ? 'border-l ' . $dividerClass : '' }} px-4">
                <div class="text-4xl md:text-6xl font-black font-display {{ $statValueClass }} mb-2 tracking-tight">
                    {{ $stat['value'] ?? '0' }}
                </div>
                <div class="text-sm md:text-base font-semibold uppercase tracking-wider {{ $subtextClass }}">
                    {{ $stat['label'] ?? '' }}
                </div>
                @if(!empty($stat['description']))
                <p class="text-xs {{ $subtextClass }} mt-2 opacity-75">{{ $stat['description'] }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
