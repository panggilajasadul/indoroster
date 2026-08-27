@props(['data'])

@php
    $title = $data['title'] ?? 'Mengapa Memilih Roster Pabrik Kami?';
    $description = $data['description'] ?? 'Dedikasi tanpa kompromi pada mutu beton, kepresisian cetakan, dan jaminan keamanan pengiriman langsung ke lokasi proyek Anda.';
    $items = $data['items'] ?? [];
    $videos = $data['videos'] ?? [];
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'dark');
@endphp

<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div data-motion="fade-up">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-bold uppercase tracking-wider mb-5">
                    <span>Keunggulan Kompetitif</span>
                </div>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black font-display {{ $theme->headingColor }} tracking-tight leading-tight mb-6">{!! $title !!}</h2>
                <p class="{{ $theme->subColor }} mb-10 text-base sm:text-lg leading-relaxed">{!! $description !!}</p>
                
                <div class="space-y-4 sm:space-y-6" data-motion="stagger">
                    @foreach($items as $item)
                    <div data-motion-item class="flex items-start gap-4 p-4 rounded-2xl border transition-all group {{ $theme->cardBg }}">
                        <div class="w-11 h-11 rounded-xl bg-terra-500/20 text-terra-500 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold mb-1 {{ $theme->cardTitle }}">{{ $item['title'] }}</h3>
                            <p class="text-sm leading-relaxed {{ $theme->cardDesc }}">{{ $item['content'] ?? $item['description'] ?? '' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="relative flex flex-col gap-6" data-motion="scale">
                @foreach($videos as $video)
                @php
                    $finalVideoUrl = !empty($video['video_upload']) ? asset('storage/' . $video['video_upload']) : ($video['url'] ?? '');
                    $ext = pathinfo(parse_url($finalVideoUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
                    $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg', 'mov', 'm4v', 'avi', 'mkv', '3gp']) || str_contains(strtolower($finalVideoUrl), 'video');
                    $videoBoxClass = $theme->isDark 
                        ? 'border-4 border-slate-800 bg-slate-900 shadow-luxury' 
                        : 'border-4 border-white bg-white shadow-soft-xl ring-1 ring-slate-900/5';
                @endphp
                <div class="relative rounded-3xl overflow-hidden aspect-video {{ $videoBoxClass }}">
                    @if($isVideo)
                    <video class="w-full h-full object-cover" autoplay loop muted playsinline>
                        <source src="{{ $finalVideoUrl }}">
                    </video>
                    @elseif($finalVideoUrl)
                    <img src="{{ $finalVideoUrl }}" class="w-full h-full object-cover" alt="Keunggulan IndoRoster">
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
