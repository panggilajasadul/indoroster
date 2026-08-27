@props(['data', 'pageTitle' => null])

@php
    $banners = $data['banners'] ?? [];
    $sliderDuration = (int) ($data['slider_duration'] ?? 5000);
    $bannerCount = count($banners);
@endphp

<div id="heroSlider" class="relative bg-slate-950 overflow-hidden min-h-[580px] sm:min-h-[620px] md:min-h-[75vh] lg:min-h-[82vh] flex items-center select-none" data-duration="{{ $sliderDuration }}">
    @if($bannerCount > 0)
        @foreach($banners as $index => $banner)
            @php
                // Content alignment styling
                $align = $banner['alignment'] ?? 'left';
                $alignParentClass = 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex ';
                $alignContainerClass = 'max-w-3xl flex flex-col ';
                $alignButtonClass = 'flex flex-col sm:flex-row gap-3.5 sm:gap-4 w-full sm:w-auto ';
                $subtitleClass = 'text-sm sm:text-base md:text-lg text-slate-300 mb-6 sm:mb-8 max-w-xl leading-relaxed font-normal ';
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
                    'sm' => 'blur-xs',
                    'md' => 'blur-sm',
                    'lg' => 'blur-md',
                    'xl' => 'blur-lg',
                    default => 'blur-none',
                };
                $blurScaleClass = $blurLevel !== 'none' ? ' scale-105' : '';

                // Image opacity
                $imgOpacity = ($banner['image_opacity'] ?? 40) / 100;

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
                $opacity = ($banner['overlay_opacity'] ?? 80) / 100;
                $rgba = "rgba($r, $g, $b, $opacity)";

                if ($align === 'center') {
                    $desktopGradient = "radial-gradient(circle at center, rgba($r, $g, $b, " . ($opacity * 0.45) . ") 0%, rgba($r, $g, $b, " . ($opacity * 0.9) . ") 100%)";
                } elseif ($align === 'right') {
                    $desktopGradient = "linear-gradient(to left, rgba($r, $g, $b, " . $opacity . ") 0%, rgba($r, $g, $b, " . ($opacity * 0.85) . ") 50%, rgba($r, $g, $b, " . ($opacity * 0.4) . ") 100%)";
                } else {
                    $desktopGradient = "linear-gradient(to right, rgba($r, $g, $b, " . $opacity . ") 0%, rgba($r, $g, $b, " . ($opacity * 0.85) . ") 55%, rgba($r, $g, $b, " . ($opacity * 0.35) . ") 100%)";
                }
                $mobileGradient = "linear-gradient(to bottom, rgba($r, $g, $b, " . ($opacity * 0.35) . ") 0%, rgba($r, $g, $b, " . ($opacity * 0.65) . ") 50%, rgba($r, $g, $b, " . ($opacity * 0.90) . ") 100%)";
            @endphp
            <div 
                class="hero-slide absolute inset-0 transition-opacity duration-1000 ease-out {{ $index === 0 ? 'active' : '' }}"
                style="{{ $index === 0 ? 'opacity:1; z-index:2;' : 'opacity:0; z-index:1; pointer-events:none;' }}"
                data-slide="{{ $index }}"
            >
                @if(!empty($banner['image_link']))
                <a href="{{ $banner['image_link'] }}" class="absolute inset-0 z-0 block">
                @endif
                @php
                    $rawUpload = $banner['image_upload'] ?? null;
                    $rawImg = $banner['image'] ?? null;
                    $imageUrl = null;
                    if (!empty($rawUpload)) {
                        $imageUrl = asset('storage/' . $rawUpload);
                    } elseif (!empty($rawImg)) {
                        $imageUrl = str_starts_with($rawImg, 'http') ? $rawImg : asset('storage/' . $rawImg);
                    }
                    $ext = $imageUrl ? pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) : '';
                    $isVideo = !empty($imageUrl) && (in_array(strtolower($ext), ['mp4', 'webm', 'ogg', 'mov', 'm4v', 'avi', 'mkv', '3gp']) || str_contains(strtolower($imageUrl), 'video'));
                    $imageFitClass = $banner['image_fit'] ?? 'object-cover';
                @endphp
                @if($isVideo)
                <video 
                    src="{{ $imageUrl }}" 
                    class="w-full h-full {{ $imageFitClass }} {{ $blurClass . $blurScaleClass }}"
                    style="opacity: {{ max($imgOpacity, 0.6) }};"
                    autoplay loop muted playsinline
                ></video>
                @elseif(!empty($imageUrl))
                <img 
                    src="{{ $imageUrl }}" 
                    alt="{{ $banner['title'] ?? '' }}" 
                    class="w-full h-full {{ $imageFitClass }} {{ $blurClass . $blurScaleClass }}"
                    style="opacity: {{ max($imgOpacity, 0.6) }};"
                    {{ $index === 0 ? 'loading=eager fetchpriority=high' : 'loading=lazy' }}
                >
                @endif
                
                <!-- Atmospheric Overlays (Mobile gradient ensures background image is vibrant & text is sharp) -->
                <div class="absolute inset-0 sm:hidden" style="background: {{ $mobileGradient }};"></div>
                <div class="absolute inset-0 hidden sm:block" style="background: {{ $desktopGradient }};"></div>
                
                @if(!empty($banner['image_link']))
                </a>
                @endif
                
                <div class="absolute inset-0 flex items-center py-12 sm:py-16 md:py-0 z-10 pointer-events-none">
                    <div class="{{ $alignParentClass }} pointer-events-auto">
                        <div class="{{ $alignContainerClass }}">
                            
                            @if(!empty($pageTitle) && !request()->is('/'))
                            <div class="{{ $badgeParentClass }} mb-3 sm:mb-4">
                                <x-breadcrumb :items="[['label' => $pageTitle]]" variant="dark" class="!px-0 !py-0" />
                            </div>
                            @endif

                            <!-- Motion Badge (Framer-motion style) -->
                            @if(!empty($banner['badge']))
                            <div class="{{ $badgeParentClass }} motion-badge">
                                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/20 backdrop-blur-md text-terra-400 text-xs sm:text-sm font-black uppercase tracking-widest shadow-xl mb-4 sm:mb-5">
                                    <span class="w-2 h-2 rounded-full bg-terra-500 animate-ping"></span>
                                    <span>{{ $banner['badge'] }}</span>
                                </div>
                            </div>
                            @endif

                            @if(!empty($banner['top_text']))
                            <div class="{{ $badgeParentClass }} motion-badge">
                                <p class="text-xs sm:text-sm font-bold text-slate-300 uppercase tracking-widest mb-2 flex items-center gap-2">
                                    <span class="w-4 h-px bg-terra-500"></span>
                                    {{ $banner['top_text'] }}
                                    <span class="w-4 h-px bg-terra-500"></span>
                                </p>
                            </div>
                            @endif

                            <!-- Motion Title (Kinetic Typography) -->
                            @if(!empty($banner['title']))
                            <h2 class="{{ $banner['font_family'] ?? 'font-display' }} text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white leading-[1.12] tracking-tight mb-5 sm:mb-6 motion-title">
                                {!! $banner['title'] ?? '' !!}
                            </h2>
                            @endif

                            <!-- Motion Subtitle -->
                            @if(!empty($banner['subtitle']))
                            <div class="motion-subtitle">
                                <p class="{{ $subtitleClass }}">
                                    {!! $banner['subtitle'] ?? '' !!}
                                </p>
                            </div>
                            @endif

                            <!-- Motion Call-To-Action Buttons -->
                            @if(!empty($banner['button_text']) || !empty($banner['button_2_text']))
                            <div class="{{ $alignButtonClass }} motion-buttons">
                                @if(!empty($banner['button_text']))
                                    <a href="{{ $banner['button_url'] ?? route('catalog') }}" class="inline-flex items-center justify-center gap-2.5 px-8 py-4 bg-terra-500 hover:bg-terra-400 text-white font-bold text-sm uppercase tracking-wider rounded-2xl transition-all shadow-luxury hover:scale-105 active:scale-95 group">
                                        <span>{{ $banner['button_text'] }}</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                @endif
                                @if(!empty($banner['button_2_text']))
                                    <a href="{{ $banner['button_2_url'] ?? route('contact') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 border border-white/25 hover:border-white text-white hover:bg-white/10 backdrop-blur-md font-bold text-sm uppercase tracking-wider rounded-2xl transition-all hover:scale-105 active:scale-95">
                                        <span>{{ $banner['button_2_text'] }}</span>
                                    </a>
                                @endif
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    @if($bannerCount > 1)
        <!-- Prev Arrow (Glass Button) -->
        <button onclick="heroSliderPrev()" class="hidden md:flex absolute left-6 top-1/2 -translate-y-1/2 z-30 w-12 h-12 rounded-2xl bg-black/40 hover:bg-terra-500 border border-white/15 text-white cursor-pointer items-center justify-center transition-all duration-300 backdrop-blur-md hover:scale-110 active:scale-95 shadow-xl group" aria-label="Slide Sebelumnya">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="group-hover:-translate-x-0.5 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
        </button>
        
        <!-- Next Arrow (Glass Button) -->
        <button onclick="heroSliderNext()" class="hidden md:flex absolute right-6 top-1/2 -translate-y-1/2 z-30 w-12 h-12 rounded-2xl bg-black/40 hover:bg-terra-500 border border-white/15 text-white cursor-pointer items-center justify-center transition-all duration-300 backdrop-blur-md hover:scale-110 active:scale-95 shadow-xl group" aria-label="Slide Berikutnya">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="group-hover:translate-x-0.5 transition-transform"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
        </button>

        <!-- Framer-Motion Style Expanding Progress Indicator Dots -->
        <div id="heroDots" class="absolute bottom-8 left-1/2 -translate-x-1/2 z-30 flex items-center gap-2.5 bg-black/40 backdrop-blur-md px-4 py-2 rounded-full border border-white/10">
            @foreach($banners as $i => $b)
                <button onclick="heroSliderGoto({{ $i }})" data-dot="{{ $i }}" class="h-2 rounded-full transition-all duration-500 {{ $i === 0 ? 'w-8 bg-terra-500 shadow-sm' : 'w-2 bg-white/40 hover:bg-white/70' }}" aria-label="Ke Slide {{ $i + 1 }}"></button>
            @endforeach
        </div>
    @endif
</div>

@if($bannerCount > 1)
<script>
(function() {
    var sliderEl = document.getElementById('heroSlider');
    if (!sliderEl) return;

    var current = 0;
    var total = {{ $bannerCount }};
    var duration = {{ $sliderDuration }};
    var timer = null;

    function goto(n) {
        var slides = sliderEl.querySelectorAll('.hero-slide');
        var dots = sliderEl.querySelectorAll('#heroDots button');
        if (!slides.length) return;

        slides[current].classList.remove('active');
        slides[current].style.opacity = '0';
        slides[current].style.zIndex = '1';
        slides[current].style.pointerEvents = 'none';

        current = (n + total) % total;

        slides[current].classList.add('active');
        slides[current].style.opacity = '1';
        slides[current].style.zIndex = '2';
        slides[current].style.pointerEvents = '';

        dots.forEach(function(d, i) {
            if (i === current) {
                d.className = "h-2 w-8 bg-terra-500 rounded-full cursor-pointer transition-all duration-500 shadow-sm";
            } else {
                d.className = "h-2 w-2 bg-white/40 hover:bg-white/70 rounded-full cursor-pointer transition-all duration-500";
            }
        });
    }

    window.heroSliderNext = function() { resetTimer(); goto(current + 1); };
    window.heroSliderPrev = function() { resetTimer(); goto(current - 1); };
    window.heroSliderGoto = function(n) { resetTimer(); goto(n); };

    function startTimer() {
        if (timer) clearInterval(timer);
        timer = setInterval(function() { goto(current + 1); }, duration);
    }
    function resetTimer() {
        clearInterval(timer);
        startTimer();
    }

    // Touch Swipe Support for Mobile
    var touchStartX = 0;
    var touchEndX = 0;
    sliderEl.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    sliderEl.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        if (touchStartX - touchEndX > 50) {
            window.heroSliderNext();
        } else if (touchEndX - touchStartX > 50) {
            window.heroSliderPrev();
        }
    }, { passive: true });

    startTimer();
})();
</script>
@endif
