@props(['data', 'pageTitle' => null])

@php
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');
    $title = $data['title'] ?? null;
    $subtitle = $data['subtitle'] ?? null;
    $badge = $data['badge'] ?? null;
    $items = $data['items'] ?? [];
    $autoplay = !empty($data['autoplay']);
    $duration = (int) ($data['duration'] ?? 4500);
    if ($duration < 2000) $duration = 4500;
    $aspectRatio = $data['aspect_ratio'] ?? 'aspect-[21/9] sm:aspect-[24/9] md:aspect-[3/1]';
    $rounded = $data['rounded'] ?? 'rounded-2xl sm:rounded-3xl';
    
    // Resolve Items (Custom, Gallery, Video Inspiration)
    $processedItems = [];
    foreach ($items as $idx => $item) {
        $type = $item['type'] ?? 'custom';
        
        if ($type === 'gallery' && !empty($item['gallery_id'])) {
            $gallery = \App\Models\Gallery::with(['media' => fn($q) => $q->where('media_type', 'image')->orderBy('sort_order')])->find($item['gallery_id']);
            if ($gallery) {
                $primaryMedia = $gallery->media->first();
                $img = $primaryMedia ? (str_starts_with($primaryMedia->media_url, 'http') ? $primaryMedia->media_url : asset('storage/' . ltrim($primaryMedia->media_url, '/'))) : null;
                $processedItems[] = [
                    'media_url' => $img,
                    'is_video' => false,
                    'badge' => $item['custom_badge'] ?: 'Galeri Proyek',
                    'title' => $item['custom_title'] ?: $gallery->title,
                    'subtitle' => $item['custom_subtitle'] ?: ($gallery->location ?: 'Inspirasi Pemasangan Roster'),
                    'link' => $item['custom_link'] ?: url('/gallery/' . ($gallery->slug ?: $gallery->id)),
                    'button_text' => $item['custom_button_text'] ?: 'Lihat Proyek Ini',
                    'overlay_color' => $item['overlay_color'] ?? '#0f172a',
                    'overlay_opacity' => (int) ($item['overlay_opacity'] ?? 40),
                ];
            }
        } elseif ($type === 'video_inspiration' && !empty($item['video_id'])) {
            $video = \App\Models\Gallery::find($item['video_id']);
            if ($video) {
                $videoMedia = $video->media()->where('media_type', 'video')->first();
                $thumbMedia = $video->media()->where('media_type', 'image')->first();
                $mediaSrc = $videoMedia ? (str_starts_with($videoMedia->media_url, 'http') ? $videoMedia->media_url : asset('storage/' . ltrim($videoMedia->media_url, '/'))) : null;
                $thumbSrc = $thumbMedia ? (str_starts_with($thumbMedia->media_url, 'http') ? $thumbMedia->media_url : asset('storage/' . ltrim($thumbMedia->media_url, '/'))) : null;
                
                $processedItems[] = [
                    'media_url' => $mediaSrc ?: $thumbSrc,
                    'thumb_url' => $thumbSrc,
                    'is_video' => !empty($videoMedia),
                    'badge' => $item['custom_badge'] ?: '🎬 Video Inspirasi',
                    'title' => $item['custom_title'] ?: $video->title,
                    'subtitle' => $item['custom_subtitle'] ?: 'Tonton video ulasan & inspirasi desain arsitektural',
                    'link' => $item['custom_link'] ?: url('/video-inspirasi/' . ($video->slug ?: $video->id)),
                    'button_text' => $item['custom_button_text'] ?: 'Tonton Video',
                    'overlay_color' => $item['overlay_color'] ?? '#0f172a',
                    'overlay_opacity' => (int) ($item['overlay_opacity'] ?? 50),
                ];
            }
        } else {
            // Custom item
            $upload = $item['image_upload'] ?? null;
            $url = $item['image_url'] ?? null;
            $file = !empty($upload) ? (is_array($upload) ? array_values($upload)[0] : $upload) : $url;
            $mediaUrl = $file ? (str_starts_with($file, 'http') ? $file : asset('storage/' . ltrim($file, '/'))) : null;
            
            $ext = $mediaUrl ? strtolower(pathinfo(parse_url($mediaUrl, PHP_URL_PATH), PATHINFO_EXTENSION)) : '';
            $isVideo = in_array($ext, ['mp4', 'webm', 'ogg', 'mov', 'm4v', 'avi', 'mkv', '3gp']) || str_contains(strtolower($mediaUrl ?? ''), 'video');

            if ($mediaUrl) {
                $processedItems[] = [
                    'media_url' => $mediaUrl,
                    'is_video' => $isVideo,
                    'badge' => $item['badge'] ?? null,
                    'title' => $item['title'] ?? null,
                    'subtitle' => $item['subtitle'] ?? null,
                    'link' => $item['link'] ?? null,
                    'button_text' => $item['button_text'] ?? null,
                    'overlay_color' => $item['overlay_color'] ?? '#0f172a',
                    'overlay_opacity' => (int) ($item['overlay_opacity'] ?? 30),
                ];
            }
        }
    }
    
    $totalSlides = count($processedItems);
@endphp

<section class="py-8 sm:py-12 {{ $theme->bgClasses }} relative overflow-hidden select-none">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-[1440px] mx-auto px-3 sm:px-6 lg:px-8 relative z-10">
        {{-- Section Header if set --}}
        @if($title || $subtitle || $badge)
            <div class="mb-6 sm:mb-8 text-center max-w-3xl mx-auto px-4">
                @if($badge)
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $theme->badgeClass }} text-xs font-bold uppercase tracking-wider mb-2.5 shadow-sm">
                        <x-heroicon-m-sparkles class="w-3.5 h-3.5 text-terra-500" />
                        {{ $badge }}
                    </div>
                @endif
                @if($title)
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-display {{ $theme->headingColor }} tracking-tight leading-tight">
                        {!! $title !!}
                    </h2>
                @endif
                @if($subtitle)
                    <p class="mt-2 text-sm sm:text-base {{ $theme->subColor }} leading-relaxed">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        @endif

        @if($totalSlides > 0)
            <div 
                x-data="{
                    active: 0,
                    total: {{ $totalSlides }},
                    autoplay: {{ $autoplay ? 'true' : 'false' }},
                    duration: {{ $duration }},
                    timer: null,
                    touchStartX: 0,
                    touchEndX: 0,
                    init() {
                        if (this.autoplay && this.total > 1) {
                            this.startAutoplay();
                        }
                    },
                    startAutoplay() {
                        this.stopAutoplay();
                        this.timer = setInterval(() => {
                            this.next();
                        }, this.duration);
                    },
                    stopAutoplay() {
                        if (this.timer) clearInterval(this.timer);
                    },
                    next() {
                        this.active = (this.active + 1) % this.total;
                    },
                    prev() {
                        this.active = (this.active - 1 + this.total) % this.total;
                    },
                    goTo(idx) {
                        this.active = idx;
                    },
                    handleTouchStart(e) {
                        this.touchStartX = e.changedTouches[0].screenX;
                        this.stopAutoplay();
                    },
                    handleTouchEnd(e) {
                        this.touchEndX = e.changedTouches[0].screenX;
                        if (this.touchStartX - this.touchEndX > 50) {
                            this.next();
                        } else if (this.touchEndX - this.touchStartX > 50) {
                            this.prev();
                        }
                        if (this.autoplay) this.startAutoplay();
                    }
                }"
                @mouseenter="stopAutoplay()"
                @mouseleave="if(autoplay) startAutoplay()"
                @touchstart="handleTouchStart($event)"
                @touchend="handleTouchEnd($event)"
                class="relative group/carousel">

                {{-- Carousel Track / Slider Multi-Card Peek --}}
                <div class="relative overflow-hidden w-full py-2">
                    <div 
                        class="flex transition-transform duration-500 ease-out"
                        :style="'transform: translateX(-' + (active * 100) + '%);'">
                        
                        @foreach($processedItems as $i => $slide)
                            @php
                                $hex = ltrim($slide['overlay_color'] ?? '#0f172a', '#');
                                if (strlen($hex) == 3) {
                                    $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
                                    $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
                                    $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
                                } else {
                                    $r = hexdec(substr($hex, 0, 2) ?: '0f');
                                    $g = hexdec(substr($hex, 2, 2) ?: '17');
                                    $b = hexdec(substr($hex, 4, 2) ?: '2a');
                                }
                                $op = ($slide['overlay_opacity'] ?? 30) / 100;
                                $overlayRgba = "rgba($r, $g, $b, $op)";
                            @endphp

                            <div class="w-full shrink-0 px-1 sm:px-2 md:px-3 transition-all duration-300">
                                <div class="relative w-full {{ $aspectRatio }} min-h-[190px] sm:min-h-[260px] md:min-h-[340px] lg:min-h-[380px] {{ $rounded }} overflow-hidden shadow-xl border border-slate-200/60 dark:border-slate-800/80 group">
                                    
                                    {{-- Media Background --}}
                                    @if($slide['is_video'])
                                        <video 
                                            src="{{ $slide['media_url'] }}" 
                                            class="absolute inset-0 w-full h-full object-cover" 
                                            autoplay loop muted playsinline>
                                        </video>
                                    @else
                                        <img 
                                            src="{{ $slide['media_url'] }}" 
                                            alt="{{ $slide['title'] ?? 'Banner IndoRoster' }}" 
                                            loading="lazy"
                                            class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105" />
                                    @endif

                                    {{-- Dynamic Overlay --}}
                                    <div 
                                        class="absolute inset-0 pointer-events-none transition-opacity duration-300"
                                        style="background: linear-gradient(135deg, {{ $overlayRgba }} 0%, rgba({{ $r }}, {{ $g }}, {{ $b }}, {{ min(0.9, $op * 1.5) }}) 100%);">
                                    </div>

                                    {{-- Clickable Full Card Link --}}
                                    @if(!empty($slide['link']))
                                        <a href="{{ $slide['link'] }}" class="absolute inset-0 z-20" aria-label="{{ $slide['title'] ?? 'Buka Tautan' }}"></a>
                                    @endif

                                    {{-- Content Layer (if title/badge/subtitle exists) --}}
                                    @if($slide['title'] || $slide['badge'] || $slide['subtitle'] || $slide['button_text'])
                                        <div class="absolute inset-0 p-5 sm:p-8 md:p-12 flex flex-col justify-end sm:justify-center z-10 max-w-2xl text-left pointer-events-none">
                                            @if(!empty($slide['badge']))
                                                <div class="mb-2 sm:mb-3">
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-terra-500/90 text-white font-extrabold text-xs sm:text-sm tracking-wide uppercase shadow-lg shadow-terra-500/30 backdrop-blur-sm">
                                                        <x-heroicon-m-bolt class="w-3.5 h-3.5 text-amber-200" />
                                                        {{ $slide['badge'] }}
                                                    </span>
                                                </div>
                                            @endif

                                            @if(!empty($slide['title']))
                                                <h3 class="text-xl sm:text-3xl md:text-4xl font-black text-white font-display tracking-tight leading-tight drop-shadow-md">
                                                    {{ $slide['title'] }}
                                                </h3>
                                            @endif

                                            @if(!empty($slide['subtitle']))
                                                <p class="mt-1.5 sm:mt-2.5 text-xs sm:text-base text-slate-100 font-medium line-clamp-2 max-w-lg drop-shadow">
                                                    {{ $slide['subtitle'] }}
                                                </p>
                                            @endif

                                            @if(!empty($slide['button_text']))
                                                <div class="mt-4 sm:mt-6">
                                                    <span class="inline-flex items-center gap-2 px-4 py-2 sm:px-6 sm:py-2.5 rounded-xl bg-white dark:bg-slate-900 text-slate-900 dark:text-white font-bold text-xs sm:text-sm shadow-xl group-hover:bg-terra-500 group-hover:text-white transition-all duration-300 pointer-events-auto">
                                                        {{ $slide['button_text'] }}
                                                        <x-heroicon-m-arrow-right class="w-4 h-4 text-terra-500 group-hover:text-white transition-transform group-hover:translate-x-1" />
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Left & Right Navigation Arrow Buttons (Persis Foto) --}}
                @if($totalSlides > 1)
                    <button 
                        type="button" 
                        @click="prev()"
                        class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 z-30 w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-white/90 dark:bg-slate-900/90 text-slate-700 dark:text-slate-200 shadow-xl border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-center hover:scale-110 hover:bg-white dark:hover:bg-slate-800 active:scale-95 transition-all duration-200 focus:outline-none backdrop-blur-md"
                        aria-label="Slide Sebelumnya">
                        <x-heroicon-o-chevron-left class="w-5 h-5 sm:w-6 sm:h-6" />
                    </button>

                    <button 
                        type="button" 
                        @click="next()"
                        class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 z-30 w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-white/90 dark:bg-slate-900/90 text-slate-700 dark:text-slate-200 shadow-xl border border-slate-200/80 dark:border-slate-700/80 flex items-center justify-center hover:scale-110 hover:bg-white dark:hover:bg-slate-800 active:scale-95 transition-all duration-200 focus:outline-none backdrop-blur-md"
                        aria-label="Slide Selanjutnya">
                        <x-heroicon-o-chevron-right class="w-5 h-5 sm:w-6 sm:h-6" />
                    </button>

                    {{-- Navigation Dots Indicators --}}
                    <div class="flex items-center justify-center gap-2 mt-4">
                        @foreach($processedItems as $dotIdx => $dotSlide)
                            <button 
                                type="button" 
                                @click="goTo({{ $dotIdx }})"
                                class="h-2 rounded-full transition-all duration-300 focus:outline-none"
                                :class="active === {{ $dotIdx }} ? 'w-8 bg-terra-500 shadow-md shadow-terra-500/30' : 'w-2 bg-slate-300 dark:bg-slate-700 hover:bg-slate-400 dark:hover:bg-slate-600'"
                                aria-label="Ke Slide {{ $dotIdx + 1 }}">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-12 px-4 rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-700 text-slate-400">
                <x-heroicon-o-photo class="w-12 h-12 mx-auto mb-2 opacity-50" />
                <p class="font-medium text-sm">Belum ada slide banner yang ditambahkan pada seksi ini.</p>
            </div>
        @endif
    </div>
</section>
