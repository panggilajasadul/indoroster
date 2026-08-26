@props(['data'])

@php
    $title = $data['title'] ?? 'Visual Showcase';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');
    $speed = $data['speed'] ?? 'animate-marquee-slow';
    $pauseOnHover = filter_var($data['pause_on_hover'] ?? false, FILTER_VALIDATE_BOOLEAN);

    $imagesUpload = is_array($data['images_upload'] ?? null)
        ? ($data['images_upload'] ?? [])
        : (filled($data['images_upload'] ?? null) ? [$data['images_upload']] : []);

    $imagesUrl = [];
    $rawImages = $data['images'] ?? [];
    if (is_array($rawImages)) {
        foreach ($rawImages as $item) {
            if (is_string($item) && filled($item)) {
                $imagesUrl[] = $item;
            } elseif (is_array($item) && !empty($item['url'])) {
                $imagesUrl[] = $item['url'];
            }
        }
    }
    $allImages = array_values(array_filter(array_merge($imagesUpload, $imagesUrl)));
@endphp

<section class="py-16 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10 relative z-10">
        <h2 class="text-center text-2xl md:text-4xl font-black font-display {{ $theme->headingColor }} leading-tight">
            {!! $title !!}
        </h2>
    </div>

    <div class="relative flex overflow-x-hidden group {{ $pauseOnHover ? 'pause-on-hover' : '' }}">
        @if(count($allImages) > 0)
            @php
                // Gandakan array agar animasi marquee infinite loop berjalan mulus tanpa jeda kosong
                $minItems = 12;
                $multiplier = max(2, (int) ceil($minItems / count($allImages)) * 2);
                $loopImages = [];
                for ($i = 0; $i < $multiplier; $i++) {
                    $loopImages = array_merge($loopImages, $allImages);
                }
            @endphp
            <div class="{{ $speed }} flex whitespace-nowrap gap-4 sm:gap-6 py-2 will-change-transform">
                @foreach($loopImages as $img)
                @php
                    $src = str_starts_with($img, 'http') ? $img : asset('storage/' . $img);
                    $ext = pathinfo(parse_url($src, PHP_URL_PATH), PATHINFO_EXTENSION);
                    $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg']) || str_contains(strtolower($src), 'video');
                @endphp
                <div class="w-[280px] sm:w-[380px] md:w-[440px] aspect-[4/3] rounded-2xl sm:rounded-3xl overflow-hidden shrink-0 shadow-lg dark:shadow-2xl/40 border border-slate-200/50 dark:border-slate-800/60 bg-slate-900/40 transition-transform duration-300 hover:scale-[1.02]">
                    @if($isVideo)
                    <video src="{{ $src }}" class="w-full h-full object-cover" autoplay loop muted playsinline></video>
                    @elseif($src)
                    <img src="{{ $src }}" alt="Inspirasi Estetika Roster Minimalis" class="w-full h-full object-cover select-none pointer-events-none" loading="lazy">
                    @endif
                </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
