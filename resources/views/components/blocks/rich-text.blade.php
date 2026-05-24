@props(['data'])

@php
    $title = $data['title'] ?? '';
    $content = $data['content'] ?? '';
    $maxWidth = $data['max_width'] ?? '4xl';
    $alignment = $data['alignment'] ?? 'left';
    $bg = $data['bg_theme'] ?? 'white';
    
    $widthClass = match($maxWidth) {
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
        '5xl' => 'max-w-5xl',
        '7xl' => 'max-w-7xl',
        default => 'max-w-4xl'
    };
    
    $alignClass = match($alignment) {
        'center' => 'text-center mx-auto',
        'right' => 'text-right ml-auto',
        default => 'text-left'
    };
    
    $bgClasses = match($bg) {
        'dark' => 'bg-slate-900 text-white',
        'accent' => 'bg-accent text-black',
        'slate' => 'bg-slate-50 text-slate-900',
        default => 'bg-white text-slate-900',
    };
    $proseTheme = $bg === 'dark' ? 'prose-invert' : 'prose-slate';
@endphp

<section class="py-16 {{ $bgClasses }}">
    <div class="{{ $widthClass }} {{ $alignClass }} px-4 sm:px-6 lg:px-8">
        @if($title)
        <h2 class="text-3xl md:text-4xl font-black font-display leading-tight mb-8">{!! $title !!}</h2>
        @endif
        <div class="prose prose-lg {{ $proseTheme }} max-w-none prose-headings:font-black prose-headings:font-display prose-a:text-accent prose-strong:font-bold">
            {!! $content !!}
        </div>
    </div>
</section>
