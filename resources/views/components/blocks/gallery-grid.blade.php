@props(['data'])

@php
    $badge = $data['badge'] ?? '';
    $title = $data['title'] ?? '';
    $description = $data['description'] ?? '';
    $items = $data['items'] ?? [];
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'dark');
@endphp

<section class="py-24 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-20">
            @if($badge)
            <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} font-bold text-xs uppercase tracking-widest mb-4">
                {{ $badge }}
            </span>
            @endif
            <h2 class="text-4xl md:text-6xl font-black font-display {{ $theme->headingColor }} mb-6">{!! $title !!}</h2>
            <p class="{{ $theme->subColor }} max-w-2xl mx-auto text-lg leading-relaxed">{!! $description !!}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($items as $item)
            @php
                $imageUrl = !empty($item['image_upload']) ? asset('storage/' . $item['image_upload']) : (str_starts_with($item['image'] ?? '', 'http') ? $item['image'] : asset('storage/' . ($item['image'] ?? '')));
                $ext = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
                $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg', 'mov', 'm4v', 'avi', 'mkv', '3gp']) || str_contains(strtolower($imageUrl), 'video');
            @endphp
            <div class="group relative aspect-square overflow-hidden rounded-2xl bg-slate-900">
                @if($isVideo)
                <video src="{{ $imageUrl }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-70 group-hover:opacity-100" autoplay loop muted playsinline></video>
                @elseif($imageUrl)
                <img src="{{ $imageUrl }}" alt="{{ $item['title'] ?? '' }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-70 group-hover:opacity-100">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                    <h3 class="text-xl font-black mb-2">{{ $item['title'] ?? '' }}</h3>
                    <a href="{{ route('gallery') }}" class="text-accent text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                        Lihat Detail Proyek
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-16 text-center">
            <a href="{{ route('gallery') }}" class="inline-block px-12 py-5 border border-white/20 hover:border-accent hover:text-accent font-black text-xs uppercase tracking-[0.2em] transition-all">
                Jelajahi Semua Inspirasi
            </a>
        </div>
    </div>
</section>
