@props(['data'])

@php
    $images = $data['images'] ?? [];
    $title = $data['title'] ?? 'Visual Showcase';
@endphp

<section class="py-16 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <h2 class="text-center text-2xl md:text-4xl font-black font-display text-slate-900 leading-tight">
            {!! $title !!}
        </h2>
    </div>

    <div class="relative flex overflow-x-hidden group">
        @php
            $speed = $data['speed'] ?? 'animate-marquee';
        @endphp
        <div class="{{ $speed }} flex whitespace-nowrap gap-1">
            @php
                $imagesUpload = $data['images_upload'] ?? [];
                // 'images' is a simple() repeater, so it's already an array of strings.
                $imagesUrl = is_array($images) ? array_filter($images) : [];
                $allImages = array_merge($imagesUpload, $imagesUrl);
            @endphp
            @foreach(array_merge($allImages, $allImages) as $img)
            @php
                $src = str_starts_with($img, 'http') ? $img : asset('storage/' . $img);
                $ext = pathinfo(parse_url($src, PHP_URL_PATH), PATHINFO_EXTENSION);
                $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg']) || str_contains(strtolower($src), 'video');
            @endphp
            <div class="w-[300px] md:w-[450px] aspect-[4/3] rounded-none overflow-hidden shrink-0 shadow-lg border border-slate-100">
                @if($isVideo)
                <video src="{{ $src }}" class="w-full h-full object-cover" autoplay loop muted playsinline></video>
                @elseif($src)
                <img src="{{ $src }}" class="w-full h-full object-cover" loading="lazy">
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
