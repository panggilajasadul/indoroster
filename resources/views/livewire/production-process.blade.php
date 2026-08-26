<div>
    @if($page && is_array($page->content) && count($page->content) > 0)
        <x-block-renderer :blocks="$page->content" :page-title="$page->title ?? 'Proses Produksi'" />
    @else
    <div class="bg-white dark:bg-slate-950 min-h-screen font-sans" x-data="{ fullSizeModal: false, activeUrl: '', activeTitle: '' }">
        <!-- Professional Video Player Plugin (Plyr) Resources -->
        <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
        <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>

        <!-- Main Content Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 sm:pt-16 pb-48">
            <x-breadcrumb :items="[['label' => 'Proses Produksi']]" class="!px-0 !py-0 mb-8 sm:mb-12" />
        
        @foreach($mainVideos as $index => $section)
            <div class="mb-64 last:mb-40">
                <div class="flex flex-col lg:flex-row gap-16 lg:gap-24 items-start">
                    <!-- Video Side -->
                    <div class="w-full lg:w-5/12">
                        <div 
                            class="relative aspect-[9/16] bg-black rounded-[2.5rem] overflow-hidden shadow-2xl border-8 border-white dark:border-slate-800 group cursor-pointer"
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
                        <span class="font-display text-terra-600 dark:text-terra-400 font-bold tracking-[0.2em] text-xs md:text-sm uppercase mb-4 block">{{ $section['subtitle'] }}</span>
                        <h2 class="font-display text-fluid-h2 font-black text-slate-900 dark:text-white mb-10 leading-[1.1] tracking-tight">
                            {{ $section['title'] }}
                        </h2>
                        <div class="w-20 h-1.5 bg-terra-500 rounded-full mb-12"></div>
                        <p class="text-slate-800 dark:text-slate-300 text-lg leading-relaxed mb-16 max-w-2xl">
                            {{ $section['description'] }}
                        </p>

                        @if(!empty($section['features']))
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                                @foreach($section['features'] as $feature)
                                    <div class="bg-slate-900 dark:bg-slate-900/90 rounded-3xl p-8 text-white shadow-xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-1 border border-slate-800">
                                        <div class="w-12 h-12 bg-terra-500/20 rounded-2xl flex items-center justify-center mb-6 border border-terra-500/30">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-terra-500">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $feature['icon'] }}" />
                                            </svg>
                                        </div>
                                        <h3 class="font-display text-fluid-h3 font-bold mb-3 leading-tight text-white">{{ $feature['title'] }}</h3>
                                        <p class="text-slate-400 text-sm leading-relaxed">{{ $feature['desc'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if($section['bottom_feature'])
                            <div class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-8 relative overflow-hidden group">
                                <div class="relative z-10 flex flex-col md:flex-row gap-6 items-center md:items-start text-center md:text-left">
                                    <div class="w-16 h-16 bg-white dark:bg-slate-800 rounded-2xl shadow-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-500 border border-slate-200 dark:border-slate-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-terra-600 dark:text-terra-400">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $section['bottom_feature']['icon'] }}" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-display text-fluid-h3 font-bold text-slate-900 dark:text-white mb-2">{{ $section['bottom_feature']['title'] }}</h3>
                                        <p class="text-slate-800 dark:text-slate-300 leading-relaxed">{{ $section['bottom_feature']['desc'] }}</p>
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
                    <h2 class="font-display text-fluid-h2 font-black text-slate-900 dark:text-white mb-2">Video Proses Produksi</h2>
                    <p class="text-slate-700 dark:text-slate-400 font-medium">Dari Pabrik hingga ke Hunian Anda</p>
                </div>
                <div class="w-20 h-1.5 bg-terra-500 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($productionProcess as $process)
                    <div 
                        class="group bg-white dark:bg-slate-900 rounded-3xl overflow-hidden border border-slate-100 dark:border-slate-800 shadow-soft-xs hover:shadow-2xl transition-all duration-500 flex flex-col cursor-pointer"
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
                            <h3 class="font-display text-fluid-h3 font-bold text-slate-900 dark:text-white mb-4 group-hover:text-terra-600 dark:group-hover:text-terra-400 transition-colors">{{ $process['title'] }}</h3>
                            <p class="text-slate-700 dark:text-slate-300 text-sm leading-relaxed mb-6">
                                {{ $process['desc'] }}
                            </p>
                            <div class="flex items-center justify-between pt-6 border-t border-slate-50 dark:border-slate-800">
                                <span class="text-[10px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">#ProdukRoster</span>
                                <span class="text-[10px] font-bold text-slate-600 dark:text-slate-400 uppercase tracking-[0.2em]">25K TAYANGAN</span>
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

        <!-- Close Button (Top Right) -->
        <button 
            @click="fullSizeModal = false; activeUrl = ''" 
            class="absolute top-6 right-6 z-50 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 p-3 rounded-full backdrop-blur-md transition-all duration-300 cursor-pointer"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Dynamic Brand Title Top Left -->
        <div class="absolute top-6 left-6 z-50 pointer-events-none flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-terra-500 flex items-center justify-center shadow-lg border border-white/20">
                <span class="text-xs font-black text-white">INDO</span>
            </div>
            <div>
                <h4 class="text-white text-sm font-black tracking-widest uppercase">INDOROSTER PROSES</h4>
                <p class="text-white/60 text-xs" x-text="activeTitle"></p>
            </div>
        </div>

        <!-- Video Player Modal Core -->
        <div class="relative z-10 w-full h-full flex items-center justify-center p-0 md:p-6">
            <div class="w-full h-full md:max-w-[450px] md:max-h-[85vh] bg-black md:rounded-[2.5rem] overflow-hidden shadow-2xl flex items-center justify-center border border-white/10 relative">
                
                <template x-if="activeUrl">
                    <video 
                        x-ref="modalVideo"
                        :src="activeUrl" 
                        class="w-full h-full object-cover"
                        playsinline
                        crossorigin
                    ></video>
                </template>

            </div>
        </div>
    </div>
    @endif
</div>
