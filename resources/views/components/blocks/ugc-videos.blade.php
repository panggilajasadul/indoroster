@props(['data'])

@php
    $badge = $data['badge'] ?? 'VISUAL EXPERIENCE';
    $title = $data['title'] ?? 'Lihat Detailnya Lebih Dekat';
    $description = $data['description'] ?? 'Kami percaya bahwa melihat adalah percaya. Koleksi video inspirasi kami menunjukkan bagaimana cahaya dan udara mengalir melalui setiap celah roster kami.';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');
    $buttonText = $data['button_text'] ?? 'Video Inspirasi Lengkap';
    $buttonUrl = !empty($data['button_url']) ? $data['button_url'] : route('video-inspiration');

    $videoList = [];

    // Check multi-upload first
    if (!empty($data['videos_upload']) && is_array($data['videos_upload'])) {
        foreach ($data['videos_upload'] as $uploadPath) {
            $videoList[] = ['video_upload' => $uploadPath];
        }
    }

    // Check repeater items
    if (!empty($data['videos']) && is_array($data['videos'])) {
        foreach ($data['videos'] as $vid) {
            if (!empty($vid['video_upload']) || !empty($vid['url'])) {
                $videoList[] = $vid;
            }
        }
    }

    // Fallback if empty
    if (empty($videoList)) {
        $videoList = [];
    }
@endphp

<section class="py-20 sm:py-24 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <div data-motion="fade-up">
                @if($badge)
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-bold uppercase tracking-widest mb-4">
                    <span>{{ $badge }}</span>
                </div>
                @endif
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black font-display {{ $theme->headingColor }} leading-tight mb-6">{!! $title !!}</h2>
                <p class="{{ $theme->subColor }} text-base sm:text-lg mb-8 leading-relaxed">{!! $description !!}</p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ $buttonUrl }}" class="px-8 py-4 font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md {{ $theme->btnPrimary }}">
                        {{ $buttonText }}
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 sm:gap-6" data-motion="scale">
                @foreach($videoList as $vid)
                @php
                    $videoUrl = !empty($vid['video_upload']) ? asset('storage/' . $vid['video_upload']) : ($vid['url'] ?? '');
                    $ext = pathinfo(parse_url($videoUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
                    $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg', 'mov', 'm4v', 'avi', 'mkv', '3gp']) || str_contains(strtolower($videoUrl), 'video');
                    $frameClass = $theme->isDark 
                        ? 'bg-slate-900 shadow-2xl border-4 border-white/15 hover:border-terra-500/50' 
                        : 'bg-slate-100 shadow-soft-xl border-4 border-white ring-1 ring-slate-900/5 hover:shadow-2xl hover:scale-[1.02]';
                @endphp
                <div class="relative aspect-[9/16] rounded-3xl overflow-hidden {{ $frameClass }} group transition-all duration-300">
                    @if($isVideo)
                    <video src="{{ $videoUrl }}" autoplay muted loop playsinline class="w-full h-full object-cover"></video>
                    @elseif($videoUrl)
                    <img src="{{ $videoUrl }}" class="w-full h-full object-cover" alt="Video Inspirasi">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent flex items-end p-4 sm:p-5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-terra-500 text-white flex items-center justify-center shadow-md">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                            <span class="text-white text-[10px] font-black uppercase tracking-widest">Live View</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
