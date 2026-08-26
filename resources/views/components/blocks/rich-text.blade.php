@props(['data'])

@php
    $badge = $data['badge'] ?? '';
    $title = $data['title'] ?? '';
    $content = $data['content'] ?? '';
    $maxWidth = $data['max_width'] ?? '5xl';
    $alignment = $data['alignment'] ?? 'left';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');

    $mediaType = $data['media_type'] ?? 'none';
    $images = $data['images'] ?? [];
    $imageLayout = $data['image_layout'] ?? 'bottom';
    $imageSize = $data['image_size'] ?? 'medium';
    $videoUrl = $data['video_url'] ?? '';
    $videoPosition = $data['video_position'] ?? 'bottom';
    
    $widthClass = match($maxWidth) {
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
        '5xl' => 'max-w-5xl',
        '7xl' => 'max-w-7xl',
        default => 'max-w-5xl'
    };
    
    $textAlignClass = match($alignment) {
        'center' => 'text-center',
        'right' => 'text-right',
        'justify' => 'text-justify',
        default => 'text-left'
    };

    $headerAlignClass = match($alignment) {
        'center' => 'flex flex-col items-center text-center',
        'right' => 'flex flex-col items-end text-right',
        'justify' => 'flex flex-col items-start text-left',
        default => 'flex flex-col items-start text-left'
    };

    $imgSizeClass = match($imageSize) {
        'small' => 'max-w-xs',
        'full' => 'w-full',
        default => 'max-w-xl'
    };

    $imgGridClass = match($imageLayout) {
        'grid_2' => 'grid grid-cols-1 sm:grid-cols-2 gap-4 my-6',
        'grid_3' => 'grid grid-cols-1 sm:grid-cols-3 gap-4 my-6',
        'float_left' => 'float-none sm:float-left sm:mr-6 sm:mb-4 my-4 max-w-full sm:max-w-sm',
        'float_right' => 'float-none sm:float-right sm:ml-6 sm:mb-4 my-4 max-w-full sm:max-w-sm',
        'top' => 'my-6 flex flex-wrap justify-center gap-4',
        default => 'my-8 flex flex-wrap justify-center gap-4'
    };

    $youtubeId = null;
    if ($videoUrl) {
        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videoUrl, $match)) {
            $youtubeId = $match[1];
        }
    }
@endphp

<section class="py-16 sm:py-24 {{ $theme->bgClasses }} relative overflow-hidden font-sans">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="{{ $widthClass }} mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="p-8 sm:p-12 md:p-14 rounded-3xl {{ $theme->cardBg }} border shadow-soft-xs backdrop-blur-xs transition-all duration-300">
            
            @if($badge || $title)
            <div class="{{ $headerAlignClass }} mb-8 pb-6 border-b border-slate-200/60 dark:border-slate-800/60">
                @if($badge)
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-black uppercase tracking-wider mb-4 shadow-soft-xs">
                    <span>{{ $badge }}</span>
                </div>
                @endif

                @if($title)
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-display {{ $theme->headingColor }} tracking-tight leading-tight">
                    {!! $title !!}
                </h2>
                @endif
            </div>
            @endif

            {{-- Video di Atas Teks --}}
            @if($mediaType === 'video' && $videoUrl && $videoPosition === 'top')
            <div class="my-6 rounded-2xl overflow-hidden shadow-soft-md border border-slate-200/80 dark:border-slate-800">
                @if($youtubeId)
                    <iframe class="w-full aspect-video rounded-2xl" src="https://www.youtube.com/embed/{{ $youtubeId }}" title="Video Player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                @else
                    <video controls class="w-full aspect-video rounded-2xl" src="{{ $videoUrl }}"></video>
                @endif
            </div>
            @endif

            {{-- Foto di Atas Teks --}}
            @if($mediaType === 'image' && !empty($images) && in_array($imageLayout, ['top', 'grid_2', 'grid_3']))
            <div class="{{ $imgGridClass }}">
                @foreach($images as $img)
                    <div class="rounded-2xl overflow-hidden shadow-soft-xs border border-slate-200/80 dark:border-slate-800 bg-slate-100 dark:bg-slate-900 group">
                        <img src="{{ Storage::url($img) }}" alt="{{ $title ?: 'Foto IndoRoster' }}" class="w-full h-auto object-cover group-hover:scale-102 transition-transform duration-300">
                    </div>
                @endforeach
            </div>
            @endif

            <div class="prose prose-base sm:prose-lg {{ $theme->isDark ? 'prose-invert' : 'prose-slate' }} max-w-none {{ $textAlignClass }} prose-headings:font-black prose-headings:font-display prose-headings:tracking-tight prose-a:text-terra-500 hover:prose-a:text-terra-600 prose-strong:font-bold prose-strong:text-slate-900 dark:prose-strong:text-white prose-li:my-1.5 leading-relaxed overflow-hidden">
                {{-- Foto Melayang Kiri / Kanan (Text Wrap) --}}
                @if($mediaType === 'image' && !empty($images) && in_array($imageLayout, ['float_left', 'float_right']))
                    @foreach($images as $img)
                    <div class="{{ $imgGridClass }}">
                        <img src="{{ Storage::url($img) }}" alt="{{ $title ?: 'Foto IndoRoster' }}" class="rounded-2xl shadow-soft-sm border border-slate-200/80 dark:border-slate-800 w-full h-auto">
                    </div>
                    @endforeach
                @endif

                {!! $content !!}
            </div>

            {{-- Foto di Bawah Teks --}}
            @if($mediaType === 'image' && !empty($images) && $imageLayout === 'bottom')
            <div class="{{ $imgGridClass }}">
                @foreach($images as $img)
                    <div class="rounded-2xl overflow-hidden shadow-soft-xs border border-slate-200/80 dark:border-slate-800 bg-slate-100 dark:bg-slate-900 {{ $imgSizeClass }}">
                        <img src="{{ Storage::url($img) }}" alt="{{ $title ?: 'Foto IndoRoster' }}" class="w-full h-auto object-cover">
                    </div>
                @endforeach
            </div>
            @endif

            {{-- Video di Bawah Teks --}}
            @if($mediaType === 'video' && $videoUrl && $videoPosition === 'bottom')
            <div class="my-8 rounded-2xl overflow-hidden shadow-soft-md border border-slate-200/80 dark:border-slate-800">
                @if($youtubeId)
                    <iframe class="w-full aspect-video rounded-2xl" src="https://www.youtube.com/embed/{{ $youtubeId }}" title="Video Player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                @else
                    <video controls class="w-full aspect-video rounded-2xl" src="{{ $videoUrl }}"></video>
                @endif
            </div>
            @endif

        </div>
    </div>
</section>
