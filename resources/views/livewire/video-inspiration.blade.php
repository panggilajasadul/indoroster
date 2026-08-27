<div class="bg-slate-50 dark:bg-slate-950 min-h-screen py-6 sm:py-10" x-data="{ 
    fullSizeModal: {{ $initialActiveIndex !== null ? 'true' : 'false' }}, 
    activeIndex: {{ $initialActiveIndex !== null ? $initialActiveIndex : 0 }}, 
    isMuted: true, 
    isLoggedIn: {{ auth()->check() ? 'true' : 'false' }}, 
    commentDrawerOpen: false, 
    activeVideoId: @entangle('activeVideoId'), 
    videos: @entangle('videos'),
    showToast: false,
    toastMessage: '',
    _opening: false,
    _viewedIds: {},
    
    playVideo(index) {
        this.pauseAllVideos();
        const video = document.getElementById('video-element-' + index);
        if (video) {
            video.muted = this.isMuted;
            video.play().catch(() => {});
        }
        if (this.videos && this.videos[index]) {
            const currentId = this.videos[index].id;
            if (currentId && !this._viewedIds[currentId]) {
                this._viewedIds[currentId] = true;
                $wire.incrementView(currentId);
            }
        }
    },
    pauseAllVideos() {
        document.querySelectorAll('.modal-video-player').forEach(v => {
            v.pause();
            v.currentTime = 0;
        });
    },
    openModal(index) {
        this._opening = true;
        this.activeIndex = index;
        this.activeVideoId = this.videos[index]?.id;
        this.fullSizeModal = true;
        this.$nextTick(() => {
            const container = document.getElementById('modal-scroll-feed');
            if (container) {
                container.scrollTop = index * container.clientHeight;
            }
            setTimeout(() => {
                this.playVideo(index);
                this._opening = false;
            }, 150);
        });
    },
    closeModal() {
        this._opening = true;
        this.pauseAllVideos();
        this.fullSizeModal = false;
        this.commentDrawerOpen = false;
        setTimeout(() => { this._opening = false; }, 200);
    },
    handleScroll(e) {
        if (this._opening) return;
        const container = e.target;
        const height = container.clientHeight;
        if (!height) return;
        const newIndex = Math.round(container.scrollTop / height);
        if (newIndex !== this.activeIndex && newIndex >= 0 && newIndex < this.videos.length) {
            this.activeIndex = newIndex;
            this.activeVideoId = this.videos[newIndex]?.id;
            this.playVideo(newIndex);
        }
    }
}" x-init="
    if (fullSizeModal) {
        _opening = true;
        $nextTick(() => {
            const container = document.getElementById('modal-scroll-feed');
            if (container) container.scrollTop = activeIndex * container.clientHeight;
            setTimeout(() => {
                playVideo(activeIndex);
                _opening = false;
            }, 150);
        });
    }
    $watch('fullSizeModal', isOpen => {
        if (isOpen && videos && videos[activeIndex]) {
            const slug = videos[activeIndex].slug || videos[activeIndex].id;
            history.replaceState(null, '', '{{ url('/video-inspirasi') }}/' + slug);
        } else {
            history.replaceState(null, '', '{{ url('/video-inspirasi') }}');
        }
    });
    $watch('activeIndex', idx => {
        if (fullSizeModal && videos && videos[idx]) {
            const slug = videos[idx].slug || videos[idx].id;
            history.replaceState(null, '', '{{ url('/video-inspirasi') }}/' + slug);
        }
    });
    $watch('activeVideoId', id => {
        if (id && videos) {
            const idx = videos.findIndex(v => v.id === id);
            if (idx !== -1 && idx !== activeIndex) {
                activeIndex = idx;
                const container = document.getElementById('modal-scroll-feed');
                if (container) {
                    container.scrollTop = idx * container.clientHeight;
                }
            }
        }
    });
    $watch('videos', list => {
        if (activeVideoId && list) {
            const idx = list.findIndex(v => v.id === activeVideoId);
            if (idx !== -1) {
                const oldIndex = activeIndex;
                activeIndex = idx;
                if (oldIndex !== idx) {
                    const container = document.getElementById('modal-scroll-feed');
                    if (container) {
                        _opening = true;
                        container.scrollTop = idx * container.clientHeight;
                        setTimeout(() => {
                            _opening = false;
                        }, 50);
                    }
                }
            }
        }
    });
">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb & Header (Adaptive Light/Dark Theme) -->
        <div class="mb-6 sm:mb-8">
            <x-breadcrumb :items="[['label' => 'Video Inspirasi']]" class="!px-0 !py-0 mb-3" />

            <div class="max-w-3xl">
                <h1 class="font-display text-2xl sm:text-3xl md:text-4xl font-black tracking-tight text-slate-900 dark:text-white mb-2">
                    Inspirasi Video Roster Beton & Proyek Arsitektur
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                    Koleksi video nyata pemasangan roster beton, pagar minimalis, partisi angin, dan fasad bangunan dari lokasi proyek. Klik video untuk melihat layar penuh dan beli produk terkait.
                </p>
            </div>
        </div>

        <!-- Filters & Sorting -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 pb-6 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-terra-50 dark:bg-terra-500/10 text-terra-600 dark:text-terra-400 rounded-full text-xs font-bold uppercase tracking-wider">
                    🎬 {{ count($videos) }} Video Inspirasi
                </span>
            </div>

            <!-- Sorting Filter -->
            <div class="flex items-center gap-2.5 self-start sm:self-center">
                <div class="relative inline-flex items-center">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 mr-2 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-terra-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h5.25m5.25-.75L17.25 9m0 0L21 12.75M17.25 9v12" />
                        </svg>
                        Urutkan:
                    </span>
                    <select 
                        wire:change="setSortBy($event.target.value)"
                        class="text-xs font-semibold bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-800 rounded-xl px-3.5 py-2 pr-8 shadow-sm hover:border-terra-400 focus:ring-2 focus:ring-terra-500/20 focus:border-terra-500 cursor-pointer transition-all">
                        <option value="latest" @selected($sortBy === 'latest')>⚡ Terbaru (Upload Terbaru)</option>
                        <option value="oldest" @selected($sortBy === 'oldest')>⏳ Terlama</option>
                        <option value="viral" @selected($sortBy === 'viral')>🔥 Terpopuler / Viral</option>
                        <option value="views" @selected($sortBy === 'views')>👁️ Paling Banyak Dilihat</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Video Grid (Reels Style) -->
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3 md:gap-8">
            @foreach($displayedVideos as $index => $video)
                <div 
                    x-data="{ 
                        playing: false,
                        progress: 0,
                        init() {
                            const video = this.$refs.videoElement;
                            const observer = new IntersectionObserver((entries) => {
                                entries.forEach(entry => {
                                    if (entry.isIntersecting) {
                                        video.play().catch(() => {});
                                        this.playing = true;
                                    } else {
                                        video.pause();
                                        this.playing = false;
                                    }
                                });
                            }, { threshold: 0.6 });
                            observer.observe(this.$el);
                        },
                        updateProgress() {
                            const video = this.$refs.videoElement;
                            if (video.duration) {
                                this.progress = (video.currentTime / video.duration) * 100;
                            }
                        }
                    }"
                    class="group relative aspect-[9/16] bg-black rounded-2xl md:rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-1 md:hover:-translate-y-2 border border-slate-200 cursor-pointer"
                    @click="openModal({{ $index }})">
                    
                    {{-- Video Element --}}
                    <video 
                        wire:ignore
                        x-ref="videoElement"
                        @timeupdate="updateProgress"
                        src="{{ $video['url'] }}" 
                        class="w-full h-full object-cover"
                        preload="none"
                        muted 
                        loop 
                        playsinline>
                    </video>

                    {{-- Link Badge if associated with product --}}
                    @if(!empty($video['product']))
                        <div class="absolute top-3 left-3 z-10 pointer-events-auto">
                            <div 
                                @click.stop="window.location.href = '{{ url('/produk/' . $video['product']['slug']) }}'"
                                class="inline-flex items-center gap-2 px-2.5 py-1.5 bg-white/95 backdrop-blur-md rounded-xl text-slate-800 text-[10px] font-bold border border-white/20 shadow-md hover:bg-terra-500 hover:text-white hover:border-terra-500 hover:scale-105 transition-all duration-300 group/badge cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-terra-500 flex-shrink-0 group-hover/badge:text-white transition-colors">
                                    <path fill-rule="evenodd" d="M6 5v1h8V5a4 4 0 0 0-8 0ZM4 8.5a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 .75.75v6.75a2.25 2.25 0 0 1-2.25 2.25H6.25a2.25 2.25 0 0 1-2.25-2.25V8.5Zm3 1.5a.75.75 0 1 0-1.5 0v1.5a.75.75 0 1 0 1.5 0v-1.5Zm6.5-.75a.75.75 0 0 1 .75.75v1.5a.75.75 0 1 1-1.5 0v-1.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                </svg>
                                <div class="flex flex-col leading-tight">
                                    <span class="truncate max-w-[90px] md:max-w-[120px]">{{ $video['product']['name'] }}</span>
                                    <span class="text-terra-600 group-hover/badge:text-white text-[9px] font-black">{{ $video['product']['formatted_price'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Overlay Details --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-4 md:p-6 text-white pointer-events-none">
                        <span class="text-terra-400 text-[10px] md:text-xs font-bold uppercase tracking-widest mb-1">{{ $video['category'] ?? 'Video Inspirasi' }}</span>
                        <h3 class="font-display text-sm md:text-base font-bold leading-tight mb-2 line-clamp-2">{{ $video['title'] }}</h3>
                        
                        <div class="flex items-center justify-between text-xs text-slate-300 pt-2 border-t border-white/10">
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <span x-text="videos[{{ $index }}]?.views_count || 0">{{ $video['views_count'] }}</span>
                            </span>
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5 text-red-500">
                                    <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                                </svg>
                                <span x-text="videos[{{ $index }}]?.likes_count || 0">{{ $video['likes_count'] }}</span>
                            </span>
                        </div>
                    </div>

                    {{-- Custom Progress Bar --}}
                    <div class="absolute bottom-0 left-0 h-0.5 md:h-1 bg-white/20 w-full overflow-hidden">
                        <div 
                            class="h-full bg-terra-500 transition-all duration-300 ease-linear shadow-[0_0_8px_rgba(247,92,32,1)]"
                            :style="`width: ${progress}%`"
                        ></div>
                    </div>

                    {{-- Play Icon (Indication) --}}
                    <div class="absolute top-3 right-3 p-2 bg-black/20 backdrop-blur-md rounded-full text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                        </svg>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Infinite Scroll Native Sensor (100% Otomatis saat Scroll) -->
        @if($hasMore)
            <div 
                x-data="{
                    observer: null,
                    isLoading: false,
                    init() {
                        this.observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting && !this.isLoading) {
                                    this.isLoading = true;
                                    $wire.loadMore().then(() => {
                                        this.isLoading = false;
                                    });
                                }
                            });
                        }, { rootMargin: '350px' });
                        this.observer.observe(this.$el);
                    },
                    destroy() {
                        if (this.observer) this.observer.disconnect();
                    }
                }"
                class="w-full flex flex-col items-center justify-center py-10"
            >
                <div class="inline-flex items-center gap-2.5 px-6 py-3 rounded-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-soft-xs text-slate-700 dark:text-slate-300 text-xs font-semibold">
                    <svg class="animate-spin h-4 w-4 text-terra-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Memuat video inspirasi berikutnya...</span>
                </div>
            </div>
        @elseif($totalVideos > 0)
            <div class="w-full text-center py-10">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-xs font-medium">
                    <span>✓ Semua video inspirasi telah ditampilkan (Total {{ $totalVideos }} Video)</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Truly Immersive Full-Size Modal (TikTok/Reels Experience with Vertical Snap Scroll) -->
    <div 
        x-show="fullSizeModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-105"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[100] bg-black"
        style="display: none;"
        @keydown.escape.window="closeModal()">
        
        {{-- Navigation Bar (Top) --}}
        <div class="absolute top-0 left-0 right-0 z-[120] p-4 md:p-6 flex justify-between items-center pointer-events-none">
            {{-- Back Button (App Style) --}}
            <button 
                @click.stop="closeModal()" 
                class="pointer-events-auto p-3 bg-black/40 hover:bg-black/60 backdrop-blur-md rounded-full text-white transition-all focus:outline-none border border-white/10 shadow-xl group">
                <div class="flex items-center gap-2 pr-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    <span class="text-xs font-bold uppercase tracking-widest hidden md:block">Kembali</span>
                </div>
            </button>

            {{-- Global Mute/Unmute Button --}}
            <button 
                @click.stop="isMuted = !isMuted; $nextTick(() => { document.querySelectorAll('.modal-video-player').forEach(v => v.muted = isMuted) })"
                class="pointer-events-auto p-3 bg-black/40 hover:bg-black/60 backdrop-blur-md rounded-full text-white transition-all focus:outline-none border border-white/10 shadow-xl">
                <template x-if="isMuted">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path d="M13.5 4.06c0-1.336-1.616-2.005-2.56-1.06l-4.5 4.5H4.508c-1.141 0-2.063.922-2.063 2.063v4.875c0 1.141.922 2.062 2.063 2.062h1.932l4.5 4.5c.944.945 2.56.276 2.56-1.06V4.06ZM17.78 9.22a.75.75 0 1 0-1.06 1.06L18.44 12l-1.72 1.72a.75.75 0 0 0 1.06 1.06l1.72-1.72 1.72 1.72a.75.75 0 1 0 1.06-1.06L20.56 12l1.72-1.72a.75.75 0 0 0-1.06-1.06l-1.72 1.72-1.72-1.72Z" />
                    </svg>
                </template>
                <template x-if="!isMuted">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path d="M13.5 4.06c0-1.336-1.616-2.005-2.56-1.06l-4.5 4.5H4.508c-1.141 0-2.063.922-2.063 2.063v4.875c0 1.141.922 2.062 2.063 2.062h1.932l4.5 4.5c.944.945 2.56.276 2.56-1.06V4.06Zm4.44 5.64a.75.75 0 0 1 1.06 0 8.96 8.96 0 0 1 0 12.6a.75.75 0 0 1-1.06-1.06 7.46 7.46 0 0 0 0-10.48.75.75 0 0 1 0-1.06ZM15.75 12a3.75 3.75 0 0 1 0 5.3a.75.75 0 1 1-1.06-1.06 2.25 2.25 0 0 0 0-3.18.75.75 0 1 1 1.06-1.06Z" />
                    </svg>
                </template>
            </button>
        </div>

        <!-- Vertical Snap Scroll Feed -->
        <div 
            id="modal-scroll-feed"
            @scroll.passive="handleScroll"
            class="w-full h-full overflow-y-auto snap-y snap-mandatory bg-black no-scrollbar"
            style="scrollbar-width: none; -ms-overflow-style: none;">
            
            @foreach($videos as $index => $video)
                <div 
                    id="modal-video-{{ $index }}"
                    class="w-full h-full snap-start snap-always relative flex items-center justify-center bg-black overflow-hidden">
                    
                    <!-- Video Wrapper with aspect 9/16 for mobile and contain on desktop -->
                    <div 
                        x-data="{ paused: false }"
                        class="w-full h-full md:max-w-[480px] md:h-[90vh] md:rounded-3xl overflow-hidden relative flex items-center justify-center bg-black md:border md:border-white/10 md:shadow-2xl transition-all duration-300 ease-in-out"
                        :class="commentDrawerOpen ? 'md:-translate-x-[200px]' : ''">
                        
                        <video 
                            wire:ignore
                            id="video-element-{{ $index }}"
                            class="modal-video-player w-full h-full object-cover"
                            src="{{ $video['url'] }}" 
                            preload="none"
                            loop 
                            playsinline
                            :muted="isMuted"
                            @click="if ($el.paused) { $el.play(); paused = false; } else { $el.pause(); paused = true; }"
                            @play="paused = false"
                            @pause="paused = true">
                        </video>

                        <!-- Big Play Icon Overlay -->
                        <div 
                            x-show="paused" 
                            x-transition
                            class="absolute inset-0 flex items-center justify-center bg-black/20 pointer-events-none">
                            <div class="p-5 rounded-full bg-black/55 backdrop-blur-md text-white shadow-2xl">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-10 h-10">
                                    <path fill-rule="evenodd" d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>

                        {{-- Overlay Gradient --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-black/20 pointer-events-none"></div>

                        {{-- Floating Branding & Info Overlay --}}
                        <div class="absolute bottom-28 left-4 right-4 z-[110] pointer-events-none text-left">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 rounded-full border border-white/40 flex items-center justify-center shadow-lg relative overflow-hidden"
                                     :class="videos[{{ $index }}]?.type === 'gallery' ? 'bg-terra-500' : 'bg-slate-600'">
                                    <template x-if="videos[{{ $index }}]?.type === 'gallery'">
                                        <span class="text-[8px] font-black text-white">INDO</span>
                                    </template>
                                    <template x-if="videos[{{ $index }}]?.type !== 'gallery'">
                                        <span class="text-xs font-bold text-white uppercase" x-text="videos[{{ $index }}]?.reviewer_name?.charAt(0) || 'U'"></span>
                                    </template>
                                </div>
                                <span class="text-white text-xs font-bold tracking-[0.2em] uppercase drop-shadow-lg" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.6);" x-text="videos[{{ $index }}]?.reviewer_name || 'INDOROSTER OFFICIAL'">INDOROSTER OFFICIAL</span>
                            </div>
                            <h2 class="font-display text-white text-sm md:text-base font-normal drop-shadow-lg max-w-[85%]" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.8);" x-text="videos[{{ $index }}]?.title">
                                {{ $video['title'] }}
                            </h2>
                        </div>

                        {{-- Floating Shopee-Style Product Card --}}
                        @if(!empty($video['product']))
                            <div class="absolute bottom-6 left-4 right-4 z-[110] pointer-events-auto">
                                <a 
                                    href="{{ url('/produk/' . $video['product']['slug']) }}" 
                                    class="flex items-center gap-3 bg-white/95 backdrop-blur-md p-2.5 rounded-2xl shadow-2xl border border-white/20 hover:bg-white hover:scale-[1.02] transition-all duration-300 group max-w-full">
                                    <!-- Product Image -->
                                    <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-200">
                                        @if($video['product']['image'])
                                            <img src="{{ $video['product']['image'] }}" alt="{{ $video['product']['name'] }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-terra-50 text-terra-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Product Info -->
                                    <div class="flex-grow min-w-0 text-left">
                                        <p class="text-xs font-bold text-slate-800 line-clamp-1 group-hover:text-terra-600 transition-colors">
                                            {{ $video['product']['name'] }}
                                        </p>
                                        <p class="text-[11px] font-black text-terra-600 mt-0.5">
                                            {{ $video['product']['formatted_price'] }} • Beli Sekarang
                                        </p>
                                    </div>

                                    <!-- Action Button / Bag Icon -->
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-terra-500 group-hover:bg-terra-600 flex items-center justify-center text-white shadow-md transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 0 0 4.25 22.5h15.5a1.875 1.875 0 0 0 1.865-2.071l-1.262-12a1.875 1.875 0 0 0-1.865-1.679H16.5V6a4.5 4.5 0 1 0-9 0ZM12 3a3 3 0 0 0-3 3v.75h6V6a3 3 0 0 0-3-3Zm-3 8.25a.75.75 0 1 0-1.5 0v-.75a.75.75 0 0 0 1.5 0v.75Zm7.5-.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-1.5 0v-.75a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </a>
                            </div>
                        @endif

                        <!-- TikTok Actions Sidebar (Floating) -->
                        <div class="absolute right-4 bottom-32 z-[115] flex flex-col items-center gap-4 text-white">
                            <!-- User/Author Avatar -->
                            <div class="flex flex-col items-center">
                                <div class="w-11 h-11 rounded-full border border-white/40 bg-slate-800 flex items-center justify-center shadow-lg relative">
                                    <template x-if="videos[{{ $index }}]?.type === 'gallery'">
                                        <div class="w-full h-full rounded-full bg-terra-600 flex items-center justify-center">
                                            <span class="text-[10px] font-black text-white">INDO</span>
                                        </div>
                                    </template>
                                    <template x-if="videos[{{ $index }}]?.type !== 'gallery'">
                                        <div class="w-full h-full rounded-full bg-slate-600 flex items-center justify-center">
                                            <span class="text-sm font-bold text-white uppercase" x-text="videos[{{ $index }}]?.reviewer_name?.charAt(0) || 'U'"></span>
                                        </div>
                                    </template>
                                    <!-- Verified Badge -->
                                    <template x-if="videos[{{ $index }}]?.type === 'gallery'">
                                        <div class="absolute -bottom-1 -right-1 bg-blue-500 rounded-full p-0.5 border border-white text-white">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-2.5 h-2.5">
                                                <path fill-rule="evenodd" d="M16.403 12.652a3 3 0 000-5.304 3 3 0 00-3.75-3.751 3 3 0 00-5.305 0 3 3 0 00-3.751 3.75 3 3 0 000 5.305 3 3 0 003.75 3.751 3 3 0 005.305 0 3 3 0 003.751-3.75zm-7.446-3.82a.75.75 0 111.086-1.04l3.723 3.876a.75.75 0 11-1.08 1.04l-3.729-3.876z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </template>
                                </div>
                            </div>


                            <!-- Views Count Display -->
                            <div class="flex flex-col items-center justify-center text-white/80">
                                <div class="w-11 h-11 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center border border-white/10 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </div>
                                <span class="text-[11px] font-bold mt-1 drop-shadow-md" x-text="videos[{{ $index }}]?.views_count || 0"></span>
                            </div>

                            <!-- Like Button -->
                            <button 
                                @click.stop="if (!isLoggedIn) { window.location.href = '{{ route('login') }}'; } else { $wire.toggleLike(videos[{{ $index }}].id) }" 
                                class="flex flex-col items-center justify-center group focus:outline-none cursor-pointer">
                                <div class="w-11 h-11 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center transition-all duration-200 group-hover:scale-110 border border-white/10 active:scale-95 text-white"
                                    :class="videos[{{ $index }}]?.is_liked ? 'text-rose-500' : ''">
                                    <svg xmlns="http://www.w3.org/2000/svg" :fill="videos[{{ $index }}]?.is_liked ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                    </svg>
                                </div>
                                <span class="text-[11px] font-bold mt-1 drop-shadow-md" x-text="videos[{{ $index }}]?.likes_count || 0"></span>
                            </button>

                            <!-- Comment Button -->
                            <button 
                                @click.stop="if (!isLoggedIn) { window.location.href = '{{ route('login') }}'; } else { activeVideoId = videos[{{ $index }}].id; commentDrawerOpen = true; }" 
                                class="flex flex-col items-center justify-center group focus:outline-none cursor-pointer">
                                <div class="w-11 h-11 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center transition-all duration-200 group-hover:scale-110 border border-white/10 active:scale-95 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9s0-3-3-3m-6 3h6m-6 0A2.25 2.25 0 0 0 5.25 10.5v3.75a2.25 2.25 0 0 0 2.25 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44.74.44.85 0l1.107-4.423a1.106 1.106 0 0 1 1.09-.852h1.372a2.25 2.25 0 0 0 2.25-2.25V10.5a2.25 2.25 0 0 0-2.25-2.25h-9Z" />
                                    </svg>
                                </div>
                                <span class="text-[11px] font-bold mt-1 drop-shadow-md" x-text="videos[{{ $index }}]?.comments_count || 0"></span>
                            </button>

                            <!-- Share Button -->
                            <button 
                                @click.stop="
                                    const slug = videos[{{ $index }}].slug || videos[{{ $index }}].id;
                                    const shareUrl = `${window.location.origin}/video-inspirasi/${slug}`;
                                    if (navigator.share) {
                                        navigator.share({
                                            title: videos[{{ $index }}].title,
                                            text: 'Lihat video inspirasi roster beton menarik ini di Indoroster!',
                                            url: shareUrl
                                        }).catch(() => {});
                                    } else {
                                        navigator.clipboard.writeText(shareUrl).then(() => {
                                            toastMessage = 'Tautan video berhasil disalin!';
                                            showToast = true;
                                            setTimeout(() => showToast = false, 3000);
                                        });
                                    }
                                " 
                                class="flex flex-col items-center justify-center group focus:outline-none cursor-pointer">
                                <div class="w-11 h-11 rounded-full bg-black/40 backdrop-blur-md flex items-center justify-center transition-all duration-200 group-hover:scale-110 border border-white/10 active:scale-95 text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3" />
                                    </svg>
                                </div>
                                <span class="text-[11px] font-bold mt-1 drop-shadow-md">Bagikan</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Comment Drawer/Bottom Sheet -->
        <div 
            x-show="commentDrawerOpen"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="translate-y-full md:translate-y-0 md:translate-x-full opacity-0"
            x-transition:enter-end="translate-y-0 md:translate-x-0 opacity-100"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="translate-y-0 md:translate-x-0 opacity-100"
            x-transition:leave-end="translate-y-full md:translate-y-0 md:translate-x-full opacity-0"
            class="fixed bottom-0 left-0 right-0 h-[60vh] md:h-screen md:w-[400px] md:bottom-0 md:top-0 md:left-auto md:right-0 bg-slate-900/95 backdrop-blur-xl border-t md:border-t-0 md:border-l border-white/10 rounded-t-3xl md:rounded-none z-[130] flex flex-col shadow-2xl overflow-hidden"
            style="display: none;"
            @click.away="commentDrawerOpen = false">
            
            <!-- Header -->
            <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between bg-slate-900/50">
                <div class="flex items-center gap-2">
                    <h3 class="font-display font-bold text-white text-base">
                        Komentar (<span x-text="videos[activeIndex]?.comments_count || 0"></span>)
                    </h3>
                </div>
                <button 
                    @click="commentDrawerOpen = false"
                    class="p-1.5 rounded-full hover:bg-white/10 text-white/70 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Comments List -->
            <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4 no-scrollbar">
                <template x-if="!(videos[activeIndex]?.comments && videos[activeIndex]?.comments.length)">
                    <div class="h-full flex flex-col items-center justify-center text-center py-12">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-slate-500 mb-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9s0-3-3-3m-6 3h6m-6 0A2.25 2.25 0 0 0 5.25 10.5v3.75a2.25 2.25 0 0 0 2.25 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44.74.44.85 0l1.107-4.423a1.106 1.106 0 0 1 1.09-.852h1.372a2.25 2.25 0 0 0 2.25-2.25V10.5a2.25 2.25 0 0 0-2.25-2.25h-9Z" />
                        </svg>
                        <p class="text-sm text-slate-400 font-medium">Belum ada komentar</p>
                        <p class="text-xs text-slate-500 mt-1">Jadilah yang pertama berkomentar!</p>
                    </div>
                </template>

                <template x-for="comment in videos[activeIndex]?.comments || []" :key="comment.id">
                    <div class="flex gap-3 text-left">
                        <!-- User Avatar -->
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-terra-600 flex items-center justify-center border border-white/10 shadow">
                            <span class="text-xs font-bold text-white uppercase" x-text="comment.user_name.charAt(0)"></span>
                        </div>
                        <!-- Comment Details -->
                        <div class="flex-1 min-w-0">
                            <div class="bg-white/5 rounded-2xl px-4 py-2.5 border border-white/5">
                                <p class="text-xs font-black text-terra-400" x-text="comment.user_name"></p>
                                <p class="text-sm text-slate-200 mt-1 leading-relaxed break-words" x-text="comment.body"></p>
                            </div>
                            <div class="flex items-center gap-3 ml-2 mt-1">
                                <span class="text-[10px] text-slate-400" x-text="comment.created_at_human"></span>
                                <button 
                                    @click.prevent="$wire.setReplyTo(comment.id, comment.user_name)"
                                    class="text-[10px] font-bold text-terra-400 hover:text-terra-300 transition-colors">
                                    Balas
                                </button>
                            </div>

                            <!-- Nested Replies -->
                            <div x-show="comment.replies && comment.replies.length > 0" class="mt-3 ml-2 space-y-3 border-l border-white/10 pl-3">
                                <template x-for="reply in comment.replies || []" :key="reply.id">
                                    <div class="flex gap-2.5 text-left">
                                        <!-- Reply User Avatar -->
                                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-slate-700 flex items-center justify-center border border-white/10 shadow">
                                            <span class="text-[10px] font-bold text-white uppercase" x-text="reply.user_name.charAt(0)"></span>
                                        </div>
                                        <!-- Reply Details -->
                                        <div class="flex-1 min-w-0">
                                            <div class="bg-white/5 rounded-2xl px-3.5 py-2 border border-white/5">
                                                <p class="text-[11px] font-black text-terra-400" x-text="reply.user_name"></p>
                                                <p class="text-xs text-slate-200 mt-0.5 leading-relaxed break-words" x-text="reply.body"></p>
                                            </div>
                                            <span class="text-[9px] text-slate-400 ml-2 mt-0.5 inline-block" x-text="reply.created_at_human"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Comment Form (Footer) -->
            <div class="p-4 border-t border-white/10 bg-slate-950/40">
                @if($replyToCommentId)
                    <div class="flex items-center justify-between bg-terra-500/10 border border-terra-500/20 rounded-xl px-3 py-1.5 mb-2 text-xs">
                        <span class="text-slate-300">
                            Membalas <span class="font-bold text-terra-400">@<span>{{ $replyToUserName }}</span></span>
                        </span>
                        <button 
                            type="button"
                            wire:click="cancelReply"
                            class="text-slate-400 hover:text-white transition-colors p-0.5 rounded-full hover:bg-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                                <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                            </svg>
                        </button>
                    </div>
                @endif

                <form wire:submit.prevent="submitComment" class="flex gap-2">
                    <input 
                        type="text" 
                        wire:model="newCommentText"
                        placeholder="{{ $replyToCommentId ? 'Tulis balasan...' : 'Tulis komentar...' }}"
                        class="flex-1 bg-white/5 text-white placeholder-slate-400 rounded-full py-2 px-4 text-sm border border-white/10 focus:outline-none focus:border-terra-500 focus:ring-1 focus:ring-terra-500 transition-all">
                    <button 
                        type="submit" 
                        class="bg-terra-500 hover:bg-terra-600 text-white rounded-full p-2.5 shadow-lg active:scale-95 transition-all flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0 1 21.485 12 59.77 59.77 0 0 1 3.27 20.876L5.999 12zm0 0h7.5" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        @media (max-width: 640px) {
            .grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
    </style>

    <!-- Premium Toast Notification -->
    <div 
        x-show="showToast"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-y-10 opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="translate-y-10 opacity-0"
        class="fixed bottom-10 left-1/2 -translate-x-1/2 z-[200] bg-slate-900/90 backdrop-blur-md text-white border border-white/10 px-6 py-3 rounded-full flex items-center gap-3 shadow-2xl"
        style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 text-terra-500">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <span class="text-xs md:text-sm font-bold tracking-wide" x-text="toastMessage"></span>
    </div>
    </div>
</div>

@push('seo')
@php
    $siteUrl = config('app.url');
@endphp
@foreach($videos as $video)
    @php
        $isYoutube = str_contains($video['url'], 'youtube.com') || str_contains($video['url'], 'youtu.be');
        $embedUrl = null;
        $contentUrl = null;

        if ($isYoutube) {
            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $video['url'], $matches);
            $ytId = $matches[1] ?? '';
            if ($ytId) {
                $embedUrl = "https://www.youtube.com/embed/{$ytId}";
            }
        } else {
            $contentUrl = $video['url'];
        }

        $thumbnail = $video['product']['image'] ?? asset('assets/logo_indoroster_no_text.PNG');
        $description = 'Video inspirasi roster beton minimalis ' . ($video['product']['name'] ?? '') . ' dari INDOROSTER Pabrik Plered Purwakarta. Kategori ' . ($video['type'] === 'gallery' ? 'Galeri Proyek' : 'Ulasan Pelanggan') . '.';
    @endphp
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "VideoObject",
        "name": "{{ e($video['title']) }}",
        "description": "{{ e($description) }}",
        "thumbnailUrl": "{{ $thumbnail }}",
        "uploadDate": "{{ $video['created_at'] }}",
        @if($embedUrl)
        "embedUrl": "{{ $embedUrl }}",
        @endif
        @if($contentUrl)
        "contentUrl": "{{ $contentUrl }}",
        @endif
        "publisher": {
            "@@type": "Organization",
            "name": "Indoroster",
            "logo": {
                "@@type": "ImageObject",
                "url": "{{ asset('assets/logo_indoroster_no_text.PNG') }}",
                "width": 600,
                "height": 600
            }
        }
    }
    </script>
@endforeach
@endpush
