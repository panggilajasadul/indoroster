<div class="bg-white min-h-screen font-sans" x-data="{ fullSizeModal: false, activeUrl: '', activeTitle: '' }">
    <!-- Professional Video Player Plugin (Plyr) Resources -->
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
    <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>

    <!-- Main Content Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-48">
        
        @foreach($mainVideos as $index => $section)
            <div class="mb-64 last:mb-40">
                <div class="flex flex-col lg:flex-row gap-16 lg:gap-24 items-start">
                    <!-- Video Side -->
                    <div class="w-full lg:w-5/12">
                        <div 
                            class="relative aspect-[9/16] bg-black rounded-[2.5rem] overflow-hidden shadow-2xl border-8 border-white group cursor-pointer"
                            @click="activeUrl = '{{ $section['video'] }}'; activeTitle = '{{ $section['title'] }}'; fullSizeModal = true"
                        >
                            <video 
                                src="{{ $section['video'] }}" 
                                class="w-full h-full object-cover"
                                muted 
                                loop 
                                playsinline
                                x-init="new IntersectionObserver((entries) => {
                                    entries.forEach(entry => {
                                        if (entry.isIntersecting) $el.play().catch(() => {});
                                        else $el.pause();
                                    });
                                }, { threshold: 0.5 }).observe($el)"
                            ></video>
                            
                            <!-- Play Overlay Indication -->
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black/20 backdrop-blur-[2px]">
                                <div class="w-20 h-20 bg-white/30 backdrop-blur-md rounded-full flex items-center justify-center border border-white/40 shadow-2xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-10 h-10 text-white translate-x-1">
                                        <path d="M8 5v14l11-7z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Branding Small -->
                            <div class="absolute bottom-6 left-6 right-6 pointer-events-none">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-5 h-5 rounded-full bg-terra-500 flex items-center justify-center">
                                        <span class="text-[6px] font-black text-white">INDO</span>
                                    </div>
                                    <span class="text-white text-[8px] font-bold tracking-widest uppercase opacity-80">INDOROSTER</span>
                                </div>
                                <p class="text-white text-xs font-medium drop-shadow-md line-clamp-1">{{ $section['title'] }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Text Side -->
                    <div class="w-full lg:w-7/12 pt-4">
                        <span class="font-display text-terra-600 font-bold tracking-[0.2em] text-xs md:text-sm uppercase mb-4 block">{{ $section['subtitle'] }}</span>
                        <h2 class="font-display text-fluid-h2 font-black text-slate-900 mb-10 leading-[1.1] tracking-tight">
                            {{ $section['title'] }}
                        </h2>
                        <div class="w-20 h-1.5 bg-terra-500 rounded-full mb-12"></div>
                        <p class="text-slate-800 text-lg leading-relaxed mb-16 max-w-2xl">
                            {{ $section['description'] }}
                        </p>

                        @if(!empty($section['features']))
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                                @foreach($section['features'] as $feature)
                                    <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-1">
                                        <div class="w-12 h-12 bg-terra-500/20 rounded-2xl flex items-center justify-center mb-6 border border-terra-500/30">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-terra-500">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feature['icon'] }}" />
                                            </svg>
                                        </div>
                                        <h3 class="font-display text-fluid-h3 font-bold mb-3 leading-tight">{{ $feature['title'] }}</h3>
                                        <p class="text-slate-600 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($section['bottom_feature'])
                            <div class="bg-slate-50 border border-slate-200 rounded-3xl p-8 relative overflow-hidden group">
                                <div class="relative z-10 flex flex-col md:flex-row gap-6 items-center md:items-start text-center md:text-left">
                                    <div class="w-16 h-16 bg-white rounded-2xl shadow-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-terra-600">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $section['bottom_feature']['icon'] }}" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-display text-fluid-h3 font-bold text-slate-900 mb-2">{{ $section['bottom_feature']['title'] }}</h3>
                                        <p class="text-slate-800 leading-relaxed">{{ $section['bottom_feature']['desc'] }}</p>
                                    </div>
                                </div>
                                <!-- Subtle Background Pattern -->
                                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-terra-500/5 rounded-full blur-3xl group-hover:bg-terra-500/10 transition-colors"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Production Process Section -->
        <div class="mt-64">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
                <div>
                    <h2 class="font-display text-fluid-h2 font-black text-slate-900 mb-2">Video Proses Produksi</h2>
                    <p class="text-slate-700 font-medium">Dari Pabrik hingga ke Hunian Anda</p>
                </div>
                <div class="w-20 h-1.5 bg-terra-500 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($productionProcess as $process)
                    <div 
                        class="group bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-500 flex flex-col cursor-pointer"
                        @click="activeUrl = '{{ $process['video'] }}'; activeTitle = '{{ $process['title'] }}'; fullSizeModal = true"
                    >
                        <div class="relative aspect-[4/5] overflow-hidden bg-black">
                            <video 
                                src="{{ $process['video'] }}" 
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000"
                                muted 
                                loop 
                                playsinline
                                x-init="new IntersectionObserver((entries) => {
                                    entries.forEach(entry => {
                                        if (entry.isIntersecting) $el.play().catch(() => {});
                                        else $el.pause();
                                    });
                                }, { threshold: 0.5 }).observe($el)"
                            ></video>
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            
                            <!-- Floating Badge -->
                            <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md px-3 py-1.5 rounded-full border border-white/20">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 bg-terra-500 rounded-full animate-pulse"></div>
                                    <span class="text-[10px] text-white font-bold uppercase tracking-wider">LIVE PRODUCTION</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-8 flex-grow">
                            <h3 class="font-display text-fluid-h3 font-bold text-slate-900 mb-4 group-hover:text-terra-600 transition-colors">{{ $process['title'] }}</h3>
                            <p class="text-slate-700 text-sm leading-relaxed mb-6">
                                {{ $process['desc'] }}
                            </p>
                            <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-[0.2em]">#ProdukRoster</span>
                                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-[0.2em]">25K TAYANGAN</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Truly Immersive Full-Size Modal (TikTok/Reels Experience) -->
    <div 
        x-show="fullSizeModal" 
        x-data="{ 
            player: null,
            initPlayer() {
                this.$nextTick(() => {
                    const videoElement = this.$refs.modalVideo;
                    if (!videoElement) return;
                    
                    if (this.player) this.player.destroy();
                    
                    this.player = new Plyr(videoElement, {
                        controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'fullscreen'],
                        clickToPlay: true,
                        hideControls: true,
                        autoplay: true
                    });
                    
                    this.player.play();
                });
            }
        }"
        x-init="$watch('fullSizeModal', value => { if(value) initPlayer(); else if(player) player.pause(); })"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-105"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] bg-black"
        style="display: none;"
        @keydown.escape.window="fullSizeModal = false; activeUrl = ''">
        
        <!-- Background Blur Effect -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-3xl z-0"></div>

        {{-- Navigation Bar (Top) --}}
        <div class="absolute top-0 left-0 right-0 z-[120] p-6 flex justify-between items-start pointer-events-none">
            {{-- Back Button (App Style) --}}
            <button 
                @click.stop="fullSizeModal = false; activeUrl = ''" 
                class="pointer-events-auto p-3 bg-white/10 hover:bg-white/20 backdrop-blur-xl rounded-full text-white transition-all focus:outline-none border border-white/20 shadow-2xl group">
                <div class="flex items-center gap-2 pr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    <span class="text-xs font-bold uppercase tracking-widest hidden md:block">Kembali</span>
                </div>
            </button>

            {{-- Close Button (Standard) --}}
            <button 
                @click.stop="fullSizeModal = false; activeUrl = ''" 
                class="pointer-events-auto p-3 bg-white/10 hover:bg-white/20 backdrop-blur-xl rounded-full text-white transition-all focus:outline-none border border-white/20 shadow-2xl">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Video Viewport - Zero Padding Edge-to-Edge --}}
        <div class="w-full h-full relative flex items-center justify-center overflow-hidden z-10">
            <template x-if="fullSizeModal">
                <div class="w-full h-full max-w-5xl mx-auto">
                    <video 
                        x-ref="modalVideo"
                        playsinline 
                        class="w-full h-full object-cover md:object-contain">
                        <source :src="activeUrl" type="video/mp4" />
                    </video>
                </div>
            </template>
        </div>

        {{-- Floating Branding Overlay --}}
        <div class="absolute bottom-10 left-8 right-8 z-[110] pointer-events-none">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-full bg-terra-500 flex items-center justify-center border border-white/40">
                    <span class="text-[10px] font-black text-white">INDO</span>
                </div>
                <span class="text-white text-xs font-bold tracking-[0.2em] uppercase drop-shadow-lg">INDOROSTER PRODUCTION</span>
            </div>
            <h2 class="font-display text-white text-fluid-h2 font-black drop-shadow-lg max-w-xl" x-text="activeTitle"></h2>
        </div>
    </div>

    <style>
        :root {
            --plyr-color-main: #f75c20;
            --plyr-video-background: transparent;
        }
        
        .plyr, .plyr__video-wrapper {
            width: 100% !important;
            height: 100% !important;
            background: transparent !important;
        }

        .plyr video {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
        }

        @media (min-width: 768px) {
            .plyr video {
                object-fit: contain !important;
            }
        }
    </style>
</div>
