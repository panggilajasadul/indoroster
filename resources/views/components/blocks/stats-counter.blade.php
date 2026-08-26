@props(['data'])

@php
    $badge = $data['badge'] ?? 'Kredibilitas & Pengalaman';
    $title = $data['title'] ?? 'Dipercaya Oleh Ribuan Proyek di Indonesia';
    $description = $data['description'] ?? 'Dari proyek residensial pribadi hingga fasad gedung komersial bertingkat di berbagai penjuru nusantara.';
    $stats = $data['stats'] ?? [];
    $alignment = $data['alignment'] ?? 'center';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');

    $headerAlign = match($alignment) {
        'left' => 'text-left flex flex-col items-start',
        'right' => 'text-right flex flex-col items-end',
        default => 'text-center flex flex-col items-center mx-auto'
    };
    $pAlign = match($alignment) {
        'left' => 'text-left mr-auto',
        'right' => 'text-right ml-auto',
        default => 'text-center mx-auto'
    };
@endphp

<section class="py-20 sm:py-24 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        @if($badge || $title || $description)
        <div class="{{ $headerAlign }} mb-16" data-motion="fade-up">
            @if($badge)
            <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full {{ $theme->badgeClass }} font-bold text-xs uppercase tracking-wider mb-4">
                {{ $badge }}
            </span>
            @endif
            @if($title)
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-black font-display {{ $theme->headingColor }} tracking-tight leading-tight mb-4">{!! $title !!}</h2>
            @endif
            @if($description)
            <p class="{{ $theme->subColor }} max-w-2xl {{ $pAlign }} text-base sm:text-lg leading-relaxed">{!! $description !!}</p>
            @endif
        </div>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-{{ min(max(count($stats), 2), 4) }} gap-4 sm:gap-6" data-motion="stagger">
            @foreach($stats as $index => $stat)
                @php
                    $rawVal = $stat['value'] ?? '0';
                    preg_match('/^([^0-9]*)([0-9.,]+)(.*)$/', $rawVal, $matches);
                    $prefix = $matches[1] ?? '';
                    $numStr = str_replace(['.', ','], '', $matches[2] ?? '0');
                    $suffix = $matches[3] ?? '';
                    $isNumeric = is_numeric($numStr) && (int)$numStr > 0;
                    $statValColor = $theme->isDark ? 'text-terra-400' : 'text-terra-600';
                @endphp
            <div data-motion-item data-tilt class="p-6 sm:p-8 rounded-2xl border {{ $theme->cardBg }} text-center flex flex-col items-center justify-center transition-all group">
                <div class="text-3xl sm:text-5xl md:text-6xl font-black font-display {{ $statValColor }} mb-2 tracking-tight group-hover:scale-105 transition-transform duration-300">
                    @if($isNumeric)
                        <span data-counter="{{ (int)$numStr }}" data-prefix="{{ $prefix }}" data-suffix="{{ $suffix }}">{{ $rawVal }}</span>
                    @else
                        {{ $rawVal }}
                    @endif
                </div>
                <div class="text-xs sm:text-sm font-bold uppercase tracking-wider {{ $theme->cardTitle }} mb-1">
                    {{ $stat['label'] ?? '' }}
                </div>
                @if(!empty($stat['description']))
                <p class="text-xs {{ $theme->cardDesc }} mt-1 leading-snug">{{ $stat['description'] }}</p>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
