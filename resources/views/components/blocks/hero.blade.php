@props(['data'])

@php
    $banners = $data['banners'] ?? [];
    $sliderDuration = $data['slider_duration'] ?? 5000;
    $bannerCount = count($banners);
@endphp

<div id="heroSlider" class="relative bg-slate-900 overflow-hidden min-h-[620px] sm:min-h-[580px] md:min-h-[70vh] lg:min-h-[75vh]">
    @if($bannerCount > 0)
        @foreach($banners as $index => $banner)
            @php
                // Content alignment styling
                $align = $banner['alignment'] ?? 'left';
                $alignParentClass = 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-12 md:mt-0 flex ';
                $alignContainerClass = 'max-w-2xl flex flex-col ';
                $alignButtonClass = 'flex flex-col sm:flex-row gap-3 sm:gap-4 w-full sm:w-auto ';
                $subtitleClass = 'text-sm sm:text-base text-slate-300 mb-6 sm:mb-8 max-w-lg leading-relaxed ';
                $badgeParentClass = 'w-full flex ';

                if ($align === 'center') {
                    $alignParentClass .= 'justify-center text-center';
                    $alignContainerClass .= 'items-center text-center';
                    $alignButtonClass .= 'justify-center';
                    $subtitleClass .= 'mx-auto';
                    $badgeParentClass .= 'justify-center';
                } elseif ($align === 'right') {
                    $alignParentClass .= 'justify-end text-right';
                    $alignContainerClass .= 'items-end text-right';
                    $alignButtonClass .= 'justify-end';
                    $subtitleClass .= 'ml-auto mr-0';
                    $badgeParentClass .= 'justify-end';
                } else { // left
                    $alignParentClass .= 'justify-start text-left';
                    $alignContainerClass .= 'items-start text-left';
                    $alignButtonClass .= 'justify-start';
                    $badgeParentClass .= 'justify-start';
                }

                // Blur configuration
                $blurLevel = $banner['blur_level'] ?? 'none';
                $blurClass = match($blurLevel) {
                    'sm' => 'blur-sm',
                    'md' => 'blur',
                    'lg' => 'blur-lg',
                    'xl' => 'blur-xl',
                    default => 'blur-none',
                };
                $blurScaleClass = $blurLevel !== 'none' ? ' scale-105' : '';

                // Image opacity
                $imgOpacity = ($banner['image_opacity'] ?? 45) / 100;

                // Color overlay configuration
                $hex = $banner['overlay_color'] ?? '#020617';
                $hex = ltrim($hex, '#');
                if (strlen($hex) == 3) {
                    $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
                    $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
                    $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
                } else {
                    $r = hexdec(substr($hex, 0, 2));
                    $g = hexdec(substr($hex, 2, 2));
                    $b = hexdec(substr($hex, 4, 2));
                }
                $opacity = ($banner['overlay_opacity'] ?? 75) / 100;
                $rgba = "rgba($r, $g, $b, $opacity)";

                if ($align === 'center') {
                    $desktopGradient = "radial-gradient(circle, rgba($r, $g, $b, " . ($opacity * 0.4) . ") 0%, $rgba 100%)";
                } elseif ($align === 'right') {
                    $desktopGradient = "linear-gradient(to left, $rgba 0%, rgba($r, $g, $b, " . ($opacity * 0.8) . ") 45%, $rgba 100%)";
                } else {
                    $desktopGradient = "linear-gradient(to right, $rgba 0%, rgba($r, $g, $b, " . ($opacity * 0.8) . ") 45%, $rgba 100%)";
                }
            @endphp
            <div 
                class="hero-slide absolute inset-0 transition-opacity duration-1000 {{ $index === 0 ? 'active' : '' }}"
                style="{{ $index === 0 ? 'opacity:1; z-index:1;' : 'opacity:0; z-index:0;' }}"
                data-slide="{{ $index }}"
            >
                @if(!empty($banner['image_link']))
                <a href="{{ $banner['image_link'] }}" class="absolute inset-0 z-0 block">
                @endif
                @php
                    $imageUrl = !empty($banner['image_upload']) ? asset('storage/' . $banner['image_upload']) : (str_starts_with($banner['image'] ?? '', 'http') ? $banner['image'] : asset('storage/' . ($banner['image'] ?? '')));
                    $ext = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
                    $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg']) || str_contains(strtolower($imageUrl), 'video');
                    $imageFitClass = $banner['image_fit'] ?? 'object-cover';
                @endphp
                @if($isVideo)
                <video 
                    src="{{ $imageUrl }}" 
                    class="w-full h-full {{ $imageFitClass }} {{ $blurClass . $blurScaleClass }}"
                    style="opacity: {{ $imgOpacity }};"
                    autoplay loop muted playsinline
                ></video>
                @elseif($imageUrl)
                <img 
                    src="{{ $imageUrl }}" 
                    alt="{{ $banner['title'] ?? '' }}" 
                    class="w-full h-full {{ $imageFitClass }} {{ $blurClass . $blurScaleClass }}"
                    style="opacity: {{ $imgOpacity }};"
                >
                @endif
                <div class="absolute inset-0 sm:hidden" style="background: {{ $rgba }};"></div>
                <div class="absolute inset-0 hidden sm:block" style="background: {{ $desktopGradient }};"></div>
                @if(!empty($banner['image_link']))
                </a>
                @endif
                
                <div class="absolute inset-0 flex items-center py-12 sm:py-16 md:py-0 z-10 pointer-events-none">
                    <div class="{{ $alignParentClass }} pointer-events-auto">
                        <div class="{{ $alignContainerClass }}">
                            @if(!empty($banner['top_text']))
                            <div class="{{ $badgeParentClass }}">
                                <h1 class="text-sm font-bold text-terra-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                                    <span class="w-5 h-px bg-terra-500"></span>
                                    {{ $banner['top_text'] }}
                                    <span class="w-5 h-px bg-terra-500"></span>
                                </h1>
                            </div>
                            @endif
                            @if(!empty($banner['badge']))
                            <div class="{{ $badgeParentClass }}">
                                <div class="inline-block bg-terra-500/15 border border-terra-500/40 text-orange-500 px-3 py-1 rounded-full text-xs sm:text-sm font-semibold mb-4 sm:mb-5 tracking-wider uppercase">
                                    {{ $banner['badge'] }}
                                </div>
                            </div>
                            @endif
                            @if(!empty($banner['title']))
                            <h2 class="{{ $banner['font_family'] ?? 'font-display' }} text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-semibold text-white leading-tight mb-6 sm:mb-8 tracking-normal">
                                {!! $banner['title'] ?? '' !!}
                            </h2>
                            @endif
                            @if(!empty($banner['subtitle']))
                            <p class="{{ $subtitleClass }}">
                                {!! $banner['subtitle'] ?? '' !!}
                            </p>
                            @endif
                            <div class="{{ $alignButtonClass }}">
                                @if(!empty($banner['button_text']))
                                    <a href="{{ $banner['button_url'] ?? route('catalog') }}" class="inline-flex items-center justify-center border border-white bg-black/20 hover:bg-white hover:text-slate-900 text-white px-6 sm:px-8 py-3 sm:py-3.5 rounded-full font-semibold text-sm sm:text-base transition-all text-center backdrop-blur-sm">
                                        {{ $banner['button_text'] }}
                                    </a>
                                @endif
                                @if(!empty($banner['button_2_text']))
                                    <a href="{{ $banner['button_2_url'] ?? '#' }}" class="inline-flex items-center justify-center border border-terra-500 bg-black/20 hover:bg-terra-500 hover:text-white text-terra-500 px-6 sm:px-8 py-3 sm:py-3.5 rounded-full font-semibold text-sm sm:text-base transition-all text-center backdrop-blur-sm">
                                        {{ $banner['button_2_text'] }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        {{-- Static Hero Fallback (From initial migration) --}}
        <div class="absolute inset-0 flex items-center py-12 sm:py-16 md:py-0 bg-slate-900">
            <div class="absolute inset-0" style="background: radial-gradient(circle at 70% 30%, rgba(196,80,49,0.15) 0%, transparent 70%); z-index: 1;"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-12 md:mt-0 relative z-10">
                <div class="max-w-3xl">
                    @if($data['badge'] ?? '')
                        <span class="inline-block px-4 py-1.5 rounded-full bg-accent/10 border border-accent/20 text-accent font-black text-xs uppercase tracking-[0.2em] mb-6 sm:mb-8">{{ $data['badge'] }}</span>
                    @endif
                    <h1 class="text-3xl sm:text-5xl md:text-7xl font-black text-white leading-[1.1] mb-6 sm:mb-8">
                        {!! $data['title'] ?? '' !!}
                    </h1>
                    <p class="text-sm sm:text-base md:text-xl text-slate-400 mb-8 sm:mb-12 max-w-xl leading-relaxed">
                        {!! $data['description'] ?? '' !!}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 w-full sm:w-auto">
                        <a href="{{ $data['primary_button_url'] ?? '/produk' }}" class="inline-flex items-center justify-center px-6 sm:px-10 py-3.5 sm:py-5 bg-accent text-black font-black text-xs uppercase tracking-widest hover:scale-105 transition-all shadow-[0_0_40px_rgba(255,102,0,0.3)] text-center">
                            {{ $data['primary_button_text'] ?? 'Lihat Produk' }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($bannerCount > 1)
        <!-- Prev Arrow -->
        <button onclick="heroSliderPrev()" class="hidden md:flex absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-black/30 hover:bg-black/60 border-none text-white cursor-pointer items-center justify-center transition-colors group">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="group-hover:-translate-x-1 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <!-- Next Arrow -->
        <button onclick="heroSliderNext()" class="hidden md:flex absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-full bg-black/30 hover:bg-black/60 border-none text-white cursor-pointer items-center justify-center transition-colors group">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="group-hover:translate-x-1 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </button>
        <!-- Dots -->
        <div id="heroDots" class="absolute bottom-7 left-1/2 -translate-x-1/2 z-20 flex gap-3">
            @foreach($banners as $i => $b)
                <button onclick="heroSliderGoto({{ $i }})" data-dot="{{ $i }}" class="w-3 h-3 rounded-full border transition-all duration-300 {{ $i === 0 ? 'border-terra-500 bg-terra-500' : 'border-white/50 bg-transparent' }}"></button>
            @endforeach
        </div>
    @endif
</div>

@if($bannerCount > 1)
<script>
(function() {
    var current = 0;
    var total = {{ $bannerCount }};
    var duration = {{ $sliderDuration }};
    var timer = null;

    function init() {
        startTimer();
    }

    function goto(n) {
        var slides = document.querySelectorAll('#heroSlider .hero-slide');
        var dots = document.querySelectorAll('#heroDots button');
        slides[current].classList.remove('active');
        slides[current].style.opacity = '0';
        slides[current].style.zIndex = '0';
        slides[current].style.pointerEvents = 'none';
        current = (n + total) % total;
        slides[current].classList.add('active');
        slides[current].style.opacity = '1';
        slides[current].style.zIndex = '1';
        slides[current].style.pointerEvents = '';
        dots.forEach(function(d, i) {
            if (i === current) {
                d.className = "w-3 h-3 rounded-full border border-terra-500 bg-terra-500 cursor-pointer transition-all duration-300";
            } else {
                d.className = "w-3 h-3 rounded-full border border-white/50 bg-transparent cursor-pointer transition-all duration-300";
            }
        });
    }

    window.heroSliderNext = function() { resetTimer(); goto(current + 1); };
    window.heroSliderPrev = function() { resetTimer(); goto(current - 1); };
    window.heroSliderGoto = function(n) { resetTimer(); goto(n); };

    function startTimer() {
        timer = setInterval(function() { goto(current + 1); }, duration);
    }
    function resetTimer() {
        clearInterval(timer);
        startTimer();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endif
