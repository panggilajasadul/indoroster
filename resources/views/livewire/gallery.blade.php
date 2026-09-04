<div class="bg-slate-50 dark:bg-slate-950 min-h-screen py-6 sm:py-10" x-data="{ 
    fullSizeModal: {{ $initialActiveIndex !== null ? 'true' : 'false' }}, 
    activeIndex: {{ $initialActiveIndex !== null ? $initialActiveIndex : 0 }}, 
    isLoggedIn: {{ auth()->check() ? 'true' : 'false' }}, 
    commentDrawerOpen: false, 
    activePhotoId: @entangle('activePhotoId'), 
    photos: @entangle('photos'),
    showToast: false,
    toastMessage: '',
    // Zoom & Pan state
    zoomScale: 1,
    zoomIn() {
        if (this.zoomScale < 3.5) this.zoomScale = parseFloat((this.zoomScale + 0.4).toFixed(1));
    },
    zoomOut() {
        if (this.zoomScale > 0.8) this.zoomScale = parseFloat((this.zoomScale - 0.4).toFixed(1));
    },
    resetZoom() {
        this.zoomScale = 1;
    },
    toggleZoom() {
        this.zoomScale = this.zoomScale > 1.1 ? 1 : 2.2;
    },
    // Mobile Touch / Swipe Vertical Navigation (TikTok/Reels style)
    touchStartY: 0,
    touchStartX: 0,
    touchEndY: 0,
    touchEndX: 0,
    initialPinchDistance: 0,
    handleTouchStart(e) {
        if (e.touches.length === 1) {
            this.touchStartY = e.touches[0].clientY;
            this.touchStartX = e.touches[0].clientX;
        } else if (e.touches.length === 2) {
            this.initialPinchDistance = Math.hypot(
                e.touches[0].clientX - e.touches[1].clientX,
                e.touches[0].clientY - e.touches[1].clientY
            );
        }
    },
    handleTouchMove(e) {
        if (e.touches.length === 2 && this.initialPinchDistance > 0) {
            const currentDist = Math.hypot(
                e.touches[0].clientX - e.touches[1].clientX,
                e.touches[0].clientY - e.touches[1].clientY
            );
            const factor = currentDist / this.initialPinchDistance;
            let newScale = this.zoomScale * factor;
            if (newScale >= 0.9 && newScale <= 3.8) {
                this.zoomScale = parseFloat(newScale.toFixed(2));
            }
            this.initialPinchDistance = currentDist;
        }
    },
    handleTouchEnd(e) {
        if (this.zoomScale > 1.15) {
            // When zoomed in, user is inspecting details, skip swipe change
            return;
        }
        if (e.changedTouches.length === 1) {
            this.touchEndY = e.changedTouches[0].clientY;
            this.touchEndX = e.changedTouches[0].clientX;
            const diffY = this.touchStartY - this.touchEndY;
            const diffX = this.touchStartX - this.touchEndX;

            // Only horizontal swipe changes photos, allowing natural vertical scroll on mobile!
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    this.nextPhoto();
                } else {
                    this.prevPhoto();
                }
            }
        }
    },
    nextPhoto() {
        if (this.photos && this.photos.length > 0) {
            this.activeIndex = (this.activeIndex + 1) % this.photos.length;
            this.activePhotoId = this.photos[this.activeIndex].id;
            this.resetZoom();
        }
    },
    prevPhoto() {
        if (this.photos && this.photos.length > 0) {
            this.activeIndex = (this.activeIndex - 1 + this.photos.length) % this.photos.length;
            this.activePhotoId = this.photos[this.activeIndex].id;
            this.resetZoom();
        }
    }
}" x-init="
    $watch('activePhotoId', id => {
        if (id && photos) {
            const idx = photos.findIndex(p => p.id === id);
            if (idx !== -1) { activeIndex = idx; }
        }
    });
    $watch('photos', list => {
        if (activePhotoId && list) {
            const idx = list.findIndex(p => p.id === activePhotoId);
            if (idx !== -1) { activeIndex = idx; }
        }
    });
    $watch('fullSizeModal', isOpen => {
        resetZoom();
        if (isOpen && photos && photos[activeIndex]) {
            const slug = photos[activeIndex].slug || photos[activeIndex].id;
            history.replaceState(null, '', '{{ url('/gallery') }}/' + slug);
        } else {
            history.replaceState(null, '', '{{ url('/gallery') }}');
        }
    });
    $watch('activeIndex', idx => {
        resetZoom();
        if (fullSizeModal && photos && photos[idx]) {
            const slug = photos[idx].slug || photos[idx].id;
            history.replaceState(null, '', '{{ url('/gallery') }}/' + slug);
        }
    });
">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb & Header (Adaptive Light/Dark Theme) -->
        <div class="mb-6 sm:mb-8">
            <x-breadcrumb :items="[['label' => 'Galeri Foto Proyek']]" class="!px-0 !py-0 mb-3" />

            <div class="max-w-3xl">
                <h1 class="font-display text-2xl sm:text-3xl md:text-4xl font-black tracking-tight text-slate-900 dark:text-white mb-2">
                    {!! $title !!}
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                    {{ $description }}
                </p>
            </div>
        </div>

        <!-- Filters & Sorting -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8 pb-6 border-b border-slate-200 dark:border-slate-800">
            <!-- Categories -->
            <div class="flex flex-wrap items-center gap-2.5 md:gap-3">
                <button 
                    type="button"
                    wire:click="setTab('all')" 
                    class="group relative px-6 py-2.5 rounded-full text-[11px] font-bold uppercase tracking-[0.2em] transition-all duration-300 overflow-hidden cursor-pointer
                    {{ $activeTab === 'all' 
                        ? 'bg-terra-500 text-white shadow-[0_10px_20px_-5px_rgba(247,92,32,0.4)] translate-y-[-2px]' 
                        : 'bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-800 hover:border-terra-300 dark:hover:border-terra-500 hover:text-terra-600 dark:hover:text-terra-400 hover:shadow-md hover:translate-y-[-2px]' 
                    }}">
                    <span class="relative z-10">SEMUA</span>
                    @if($activeTab !== 'all')
                        <div class="absolute inset-0 bg-terra-50 dark:bg-terra-500/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                    @endif
                </button>

                @foreach($availableCategories as $category)
                    <button 
                        type="button"
                        wire:click="setTab('{{ $category }}')" 
                        class="group relative px-6 py-2.5 rounded-full text-[11px] font-bold uppercase tracking-[0.2em] transition-all duration-300 overflow-hidden cursor-pointer
                        {{ $activeTab === $category 
                            ? 'bg-terra-500 text-white shadow-[0_10px_20px_-5px_rgba(247,92,32,0.4)] translate-y-[-2px]' 
                            : 'bg-white dark:bg-slate-900 text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-800 hover:border-terra-300 dark:hover:border-terra-500 hover:text-terra-600 dark:hover:text-terra-400 hover:shadow-md hover:translate-y-[-2px]' 
                        }}">
                        <span class="relative z-10">{{ strtoupper($category) }}</span>
                        @if($activeTab !== $category)
                            <div class="absolute inset-0 bg-terra-50 dark:bg-terra-500/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        @endif
                    </button>
                @endforeach
            </div>

            <!-- Sorting Filter -->
            <div class="flex items-center gap-2.5 self-start lg:self-center">
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

        <!-- Photo Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="gallery-container">
            @foreach($displayedPhotos as $index => $image)
                <div 
                    @click="activeIndex = {{ $index }}; activePhotoId = '{{ $image['id'] }}'; fullSizeModal = true"
                    wire:key="img-{{ $activeTab }}-{{ $loop->index }}"
                    class="group relative aspect-square overflow-hidden rounded-2xl bg-white dark:bg-slate-900 cursor-pointer shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 dark:border-slate-800 hover:shadow-[0_20px_50px_rgba(0,0,0,0.1)] hover:-translate-y-2 transition-all duration-500 ease-out">
                    
                    {{-- Link Badge if associated with product --}}
                    @if(!empty($image['product']))
                        <div class="absolute top-4 left-4 z-10 pointer-events-auto">
                            <div 
                                @click.stop="window.location.href = '{{ url('/produk/' . $image['product']['slug']) }}'"
                                class="product-badge-btn inline-flex items-center gap-2 px-3 py-1.5 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md rounded-xl text-slate-800 dark:text-white text-[10px] md:text-xs font-bold border border-slate-200/50 dark:border-slate-700/50 shadow-md hover:bg-terra-500 hover:text-white hover:border-terra-500 hover:scale-105 transition-all duration-300 group/badge cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-terra-500 flex-shrink-0 group-hover/badge:text-white transition-colors">
                                    <path fill-rule="evenodd" d="M6 5v1h8V5a4 4 0 0 0-8 0ZM4 8.5a.75.75 0 0 1 .75-.75h10.5a.75.75 0 0 1 .75.75v6.75a2.25 2.25 0 0 1-2.25 2.25H6.25a2.25 2.25 0 0 1-2.25-2.25V8.5Zm3 1.5a.75.75 0 1 0-1.5 0v1.5a.75.75 0 1 0 1.5 0v-1.5Zm6.5-.75a.75.75 0 0 1 .75.75v1.5a.75.75 0 1 1-1.5 0v-1.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                </svg>
                                <div class="flex flex-col leading-tight">
                                    <span class="truncate max-w-[120px]">{{ $image['product']['name'] }}</span>
                                    <span class="text-terra-600 dark:text-terra-400 group-hover/badge:text-white/90 text-[9px] font-black">{{ $image['product']['formatted_price'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <img 
                        src="{{ $image['url'] }}" 
                        alt="{{ $image['title'] }}" 
                        loading="lazy"
                        class="w-full h-full object-cover transition-transform duration-1000 ease-out group-hover:scale-110"
                    />

                    {{-- Overlay with better gradient and animation --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-6 translate-y-4 group-hover:translate-y-0">
                        <span class="text-terra-400 text-xs font-bold uppercase tracking-widest mb-1">{{ $image['category'] ?? 'Proyek' }}</span>
                        <h3 class="font-display text-white text-sm font-bold leading-tight drop-shadow-sm">{{ $image['title'] }}</h3>
                        @if(!empty($image['location']))
                            <p class="text-white/70 text-[10px] mt-1 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $image['location'] }}
                            </p>
                        @endif
                        {{-- Hover Stats Overlay --}}
                        <div class="flex items-center gap-3 mt-3 text-white/90 text-xs font-medium">
                            <span class="flex items-center gap-1.5 bg-black/30 backdrop-blur-sm px-2 py-0.5 rounded-full text-[10px]">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-3 h-3 text-rose-500">
                                    <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                                </svg>
                                <span x-text="photos[{{ $index }}]?.likes_count || 0"></span>
                            </span>
                            <span class="flex items-center gap-1.5 bg-black/30 backdrop-blur-sm px-2 py-0.5 rounded-full text-[10px]">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-3 h-3 text-blue-400">
                                    <path fill-rule="evenodd" d="M4.804 21.644A6.707 6.707 0 0 0 6 21.75a6.721 6.721 0 0 0 3.583-1.022 7.478 7.478 0 0 0 2.417.422c4.142 0 7.5-3.134 7.5-7s-3.358-7-7.5-7-7.5 3.134-7.5 7c0 1.86.787 3.55 2.054 4.804-.15.42-.393.812-.716 1.156a.75.75 0 0 0 .584 1.254c.783-.02 1.543-.2 2.254-.53a6.719 6.719 0 0 0 .633-.298ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM9 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm6.75.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                                </svg>
                                <span x-text="photos[{{ $index }}]?.comments_count || 0"></span>
                            </span>
                        </div>
                    </div>

                    {{-- Floating Icon Indicator --}}
                    <div class="absolute top-4 right-4 w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-all duration-500 scale-50 group-hover:scale-100">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6" />
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
                    <span>Memuat foto proyek berikutnya...</span>
                </div>
            </div>
        @elseif($totalPhotos > 0)
            <div class="w-full text-center py-10">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-xs font-medium">
                    <span>✓ Semua foto proyek telah ditampilkan (Total {{ $totalPhotos }} Foto)</span>
                </div>
            </div>
        @endif

        @if(count($photos) === 0)
            <div class="text-center py-20">
                <p class="text-slate-500 italic text-lg">Belum ada foto untuk kategori ini.</p>
            </div>
        @endif
    </div>

    <!-- Custom Alpine Lightbox Modal (TikTok/Reels style for Photos) -->
    <div 
        x-show="fullSizeModal" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[100] bg-black/95 backdrop-blur-xl flex items-center justify-center"
        style="display: none;"
        @keydown.escape.window="fullSizeModal = false"
        @keydown.arrow-left.window="if (fullSizeModal) { activeIndex = (activeIndex - 1 + photos.length) % photos.length; activePhotoId = photos[activeIndex].id }"
        @keydown.arrow-right.window="if (fullSizeModal) { activeIndex = (activeIndex + 1) % photos.length; activePhotoId = photos[activeIndex].id }">
        
        <!-- Close Button (Global) -->
        <button 
            @click.stop="fullSizeModal = false" 
            class="absolute top-4 right-4 z-[120] p-3 bg-black/40 hover:bg-black/60 backdrop-blur-md rounded-full text-white transition-all focus:outline-none border border-white/10 shadow-xl">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Main Modal Container: Split Layout on Desktop, Vertical Scrollable on Mobile -->
        <div class="w-full h-full flex flex-col md:flex-row relative overflow-y-auto md:overflow-hidden">
            
            <!-- Left Side: Image Container & Navigation (Expansive Theatre) -->
            <div 
                class="w-full md:flex-1 min-h-[50vh] sm:min-h-[60vh] md:h-full flex items-center justify-center relative bg-black/40 p-2 sm:p-4 overflow-hidden shrink-0 select-none"
                @touchstart="handleTouchStart($event)"
                @touchmove="handleTouchMove($event)"
                @touchend="handleTouchEnd($event)"
            >
                <!-- Desktop Zoom Control Toolbar (Top-Left) -->
                <div class="hidden md:flex items-center gap-1.5 absolute top-4 left-4 z-[115] bg-slate-900/85 backdrop-blur-md px-3 py-1.5 rounded-2xl border border-white/10 shadow-xl">
                    <button 
                        @click.stop="zoomIn()" 
                        class="p-1.5 rounded-xl hover:bg-white/10 text-white/80 hover:text-white transition-colors cursor-pointer" 
                        title="Perbesar (+)">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                    </button>
                    <button 
                        @click.stop="zoomOut()" 
                        class="p-1.5 rounded-xl hover:bg-white/10 text-white/80 hover:text-white transition-colors cursor-pointer" 
                        title="Perkecil (-)">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"/></svg>
                    </button>
                    <button 
                        @click.stop="resetZoom()" 
                        class="px-2 py-1 rounded-xl hover:bg-white/10 text-[11px] font-bold text-white/90 transition-colors cursor-pointer" 
                        title="Reset Ukuran Normal (100%)">
                        <span x-text="Math.round(zoomScale * 100) + '%'"></span>
                    </button>
                    <span class="text-[10px] text-white/40 border-l border-white/10 pl-2 ml-1 hidden lg:inline">Klik 2x / Scroll</span>
                </div>

                <!-- Mobile Swipe Hint -->
                <div class="md:hidden absolute top-4 left-4 z-[115] bg-black/60 backdrop-blur-md px-3 py-1 rounded-full border border-white/10 text-[10px] text-white/80 flex items-center gap-1.5 pointer-events-none">
                    <svg class="w-3.5 h-3.5 text-terra-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    <span>Geser kiri/kanan</span>
                </div>
                
                <!-- Navigation: Previous (Desktop & Mobile) -->
                <button 
                    @click.stop="prevPhoto()" 
                    class="flex absolute left-3 sm:left-4 z-[110] p-2.5 sm:p-3 bg-black/50 hover:bg-black/70 backdrop-blur-md rounded-full text-white transition-all focus:outline-none border border-white/10 shadow-xl cursor-pointer"
                    title="Foto Sebelumnya">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </button>

                <!-- Photo Element with Zoom & Double-Tap Support -->
                <div class="w-full h-full flex items-center justify-center overflow-hidden">
                    <img 
                        :src="photos[activeIndex]?.url" 
                        :alt="photos[activeIndex]?.title" 
                        @dblclick="toggleZoom()"
                        :style="'transform: scale(' + zoomScale + '); transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1); transform-origin: center center;'"
                        class="max-h-[85vh] max-w-full object-contain rounded-xl shadow-2xl select-none cursor-zoom-in"
                    />
                </div>

                <!-- Navigation: Next (Desktop & Mobile) -->
                <button 
                    @click.stop="nextPhoto()" 
                    class="flex absolute right-3 sm:right-4 z-[110] p-2.5 sm:p-3 bg-black/50 hover:bg-black/70 backdrop-blur-md rounded-full text-white transition-all focus:outline-none border border-white/10 shadow-xl cursor-pointer"
                    title="Foto Berikutnya">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>

            <!-- Right Side: Details & Comments Panel (Desktop Sidebar & Mobile Scrollable View) -->
            <div class="flex flex-col w-full md:w-[380px] lg:w-[420px] md:h-full bg-slate-900 border-t md:border-t-0 md:border-l border-white/10 relative z-20 shrink-0 md:overflow-hidden">
                <!-- Header / Author Info & Story Caption -->
                <div class="p-6 border-b border-white/10 max-h-[350px] overflow-y-auto no-scrollbar">
                    <div class="flex items-center gap-3 mb-3.5">
                        <div class="w-10 h-10 rounded-full border border-white/40 flex items-center justify-center shadow-lg relative overflow-hidden shrink-0"
                             :class="photos[activeIndex]?.type === 'gallery' ? 'bg-terra-500' : 'bg-slate-700'">
                            <span class="text-xs font-black text-white" x-text="photos[activeIndex]?.type === 'gallery' ? 'INDO' : (photos[activeIndex]?.reviewer_name?.charAt(0) || 'U')"></span>
                        </div>
                        <div class="text-left min-w-0">
                            <p class="text-white text-xs font-bold uppercase tracking-widest truncate" x-text="photos[activeIndex]?.reviewer_name || 'INDOROSTER OFFICIAL'"></p>
                            <p class="text-white/60 text-[10px] mt-0.5 flex items-center gap-1" x-show="photos[activeIndex]?.location">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-terra-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span x-text="photos[activeIndex]?.location"></span>
                            </p>
                        </div>
                    </div>

                    <!-- Judul Proyek / Post -->
                    <h3 class="font-display text-white text-sm font-bold leading-snug text-left mb-2.5" x-text="photos[activeIndex]?.title"></h3>

                    <!-- Caption & Cerita Pemasangan Lengkap -->
                    <div class="text-slate-300 text-xs leading-relaxed whitespace-pre-line text-left space-y-1.5" x-show="photos[activeIndex]?.description || photos[activeIndex]?.caption">
                        <p x-text="photos[activeIndex]?.description || photos[activeIndex]?.caption"></p>
                    </div>
                </div>

                <!-- Shoppable Product Link (Desktop & Mobile) -->
                <template x-if="photos[activeIndex]?.product">
                    <div class="px-5 sm:px-6 py-4 border-b border-white/10 bg-slate-950/60">
                        <div class="text-[10px] uppercase font-bold tracking-wider text-emerald-400 mb-2 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            Motif Roster Terkait (Bisa Langsung Beli)
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white/5 backdrop-blur-md p-3.5 rounded-2xl border border-white/10 hover:border-emerald-500/50 transition-all">
                            <!-- Product Image & Details -->
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-slate-800 flex-shrink-0 border border-white/10">
                                    <img :src="photos[activeIndex]?.product?.image" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-grow min-w-0 text-left">
                                    <p class="text-xs font-bold text-white line-clamp-1 group-hover:text-terra-400 transition-colors" x-text="photos[activeIndex]?.product?.name"></p>
                                    <p class="text-xs font-black text-terra-400 mt-0.5" x-text="photos[activeIndex]?.product?.formatted_price"></p>
                                </div>
                            </div>

                            <!-- Cart Button -->
                            <a 
                                :href="'/produk/' + photos[activeIndex]?.product?.slug" 
                                class="w-full sm:w-auto px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 active:scale-95 text-white font-bold text-xs rounded-xl shadow-lg transition-all flex items-center justify-center gap-1.5 shrink-0 whitespace-nowrap">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                <span>🛒 Masukkan Keranjang</span>
                            </a>
                        </div>
                    </div>
                </template>

                <!-- Likes & Comments Counts + Interaction Buttons (Desktop) -->
                <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between text-white bg-slate-900/80">
                    <div class="flex flex-wrap items-center gap-4">

                        <!-- Likes Count Button -->
                        <button 
                            @click.stop="if (!isLoggedIn) { window.location.href = '{{ route('login') }}'; } else { $wire.toggleLike(photos[activeIndex].id) }" 
                            class="flex items-center gap-2 hover:text-rose-500 transition-colors focus:outline-none"
                            :class="photos[activeIndex]?.is_liked ? 'text-rose-500' : 'text-white/80'">
                            <svg xmlns="http://www.w3.org/2000/svg" :fill="photos[activeIndex]?.is_liked ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                            <span class="text-xs font-bold" x-text="(photos[activeIndex]?.likes_count || 0) + ' Suka'"></span>
                        </button>

                        <!-- Comments count indicator -->
                        <div class="flex items-center gap-2 text-white/80">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9s0-3-3-3m-6 3h6m-6 0A2.25 2.25 0 0 0 5.25 10.5v3.75a2.25 2.25 0 0 0 2.25 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44.74.44.85 0l1.107-4.423a1.106 1.106 0 0 1 1.09-.852h1.372a2.25 2.25 0 0 0 2.25-2.25V10.5a2.25 2.25 0 0 0-2.25-2.25h-9Z" />
                            </svg>
                            <span class="text-xs font-bold" x-text="(photos[activeIndex]?.comments_count || 0) + ' Komentar'"></span>
                        </div>
                    </div>

                    <!-- Share Button (Desktop) -->
                    <button 
                        @click.stop="
                            const shareUrl = `${window.location.origin}${window.location.pathname}?photo=${photos[activeIndex].id}`;
                            navigator.clipboard.writeText(shareUrl).then(() => {
                                toastMessage = 'Tautan foto berhasil disalin!';
                                showToast = true;
                                setTimeout(() => showToast = false, 3000);
                            });
                        "
                        class="flex items-center gap-1.5 hover:text-terra-400 text-white/80 transition-colors focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3" />
                        </svg>
                        <span class="text-xs font-bold">Bagikan</span>
                    </button>
                </div>

                <!-- Comments List (Desktop) -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4 no-scrollbar">
                    <template x-if="!(photos[activeIndex]?.comments && photos[activeIndex]?.comments.length)">
                        <div class="h-full flex flex-col items-center justify-center text-center py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-slate-500 mb-3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9s0-3-3-3m-6 3h6m-6 0A2.25 2.25 0 0 0 5.25 10.5v3.75a2.25 2.25 0 0 0 2.25 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44.74.44.85 0l1.107-4.423a1.106 1.106 0 0 1 1.09-.852h1.372a2.25 2.25 0 0 0 2.25-2.25V10.5a2.25 2.25 0 0 0-2.25-2.25h-9Z" />
                            </svg>
                            <p class="text-sm text-slate-400 font-medium">Belum ada komentar</p>
                            <p class="text-xs text-slate-500 mt-1">Jadilah yang pertama berkomentar!</p>
                        </div>
                    </template>

                    <template x-for="comment in photos[activeIndex]?.comments || []" :key="comment.id">
                        <div class="flex gap-3 text-left">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-terra-600 flex items-center justify-center border border-white/10 shadow">
                                <span class="text-xs font-bold text-white uppercase" x-text="comment.user_name.charAt(0)"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="bg-white/5 rounded-2xl px-4 py-2.5 border border-white/5">
                                    <p class="text-xs font-black text-terra-400" x-text="comment.user_name"></p>
                                    <p class="text-sm text-slate-200 mt-1 leading-relaxed break-words text-left" x-text="comment.body"></p>
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
                                                    <p class="text-xs text-slate-200 mt-0.5 leading-relaxed break-words text-left" x-text="reply.body"></p>
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

                <!-- Desktop Comment Input Form -->
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
    </div>

    <!-- Comment Drawer/Bottom Sheet (Mobile Only) -->
    <div 
        x-show="commentDrawerOpen"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="translate-y-full opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="translate-y-full opacity-0"
        class="fixed bottom-0 left-0 right-0 h-[65vh] bg-slate-900/95 backdrop-blur-xl border-t border-white/10 rounded-t-3xl z-[130] flex flex-col shadow-2xl overflow-hidden md:hidden"
        style="display: none;"
        @click.away="commentDrawerOpen = false">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-white/10 flex items-center justify-between bg-slate-900/50">
            <h3 class="font-display font-bold text-white text-base">
                Komentar (<span x-text="photos[activeIndex]?.comments_count || 0"></span>)
            </h3>
            <button 
                @click="commentDrawerOpen = false"
                class="p-1.5 rounded-full hover:bg-white/10 text-white/70 hover:text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Comments List (Mobile Drawer) -->
        <div class="flex-1 overflow-y-auto px-6 py-4 space-y-4 no-scrollbar">
            <template x-if="!(photos[activeIndex]?.comments && photos[activeIndex]?.comments.length)">
                <div class="h-full flex flex-col items-center justify-center text-center py-12">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-slate-500 mb-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9s0-3-3-3m-6 3h6m-6 0A2.25 2.25 0 0 0 5.25 10.5v3.75a2.25 2.25 0 0 0 2.25 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44.74.44.85 0l1.107-4.423a1.106 1.106 0 0 1 1.09-.852h1.372a2.25 2.25 0 0 0 2.25-2.25V10.5a2.25 2.25 0 0 0-2.25-2.25h-9Z" />
                    </svg>
                    <p class="text-sm text-slate-400 font-medium">Belum ada komentar</p>
                    <p class="text-xs text-slate-500 mt-1">Jadilah yang pertama berkomentar!</p>
                </div>
            </template>

            <template x-for="comment in photos[activeIndex]?.comments || []" :key="comment.id">
                <div class="flex gap-3 text-left">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-terra-600 flex items-center justify-center border border-white/10 shadow">
                        <span class="text-xs font-bold text-white uppercase" x-text="comment.user_name.charAt(0)"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="bg-white/5 rounded-2xl px-4 py-2.5 border border-white/5">
                            <p class="text-xs font-black text-terra-400" x-text="comment.user_name"></p>
                            <p class="text-sm text-slate-200 mt-1 leading-relaxed break-words text-left" x-text="comment.body"></p>
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
                                            <p class="text-xs text-slate-200 mt-0.5 leading-relaxed break-words text-left" x-text="reply.body"></p>
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

        <!-- Mobile Comment Form (Footer) -->
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
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .animate-hover {
            transition: all 0.2s ease-in-out;
        }
        .animate-hover:hover {
            transform: scale(1.1);
        }
    </style>
    </div>
</div>

@push('seo')
@php
    $siteUrl = config('app.url');
@endphp
@foreach($photos as $photo)
    @php
        $description = 'Inspirasi pemasangan roster beton minimalis ' . ($photo['product']['name'] ?? '') . ' pada kategori ' . $photo['category'] . ' berlokasi di ' . $photo['location'] . '. Dapatkan kualitas premium langsung dari pabrik INDOROSTER.';
    @endphp
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "ImageObject",
        "contentUrl": "{{ $photo['url'] }}",
        "name": "{{ e($photo['title']) }}",
        "description": "{{ e($description) }}",
        "datePublished": "{{ $photo['created_at'] }}",
        @if(!empty($photo['product']))
        "about": {
            "@@type": "Product",
            "name": "{{ e($photo['product']['name']) }}",
            "url": "{{ url('/produk/' . $photo['product']['slug']) }}",
            "image": "{{ $photo['product']['image'] }}"
        },
        @endif
        "author": {
            "@@type": "Organization",
            "name": "Indoroster",
            "url": "{{ $siteUrl }}"
        }
    }
    </script>
@endforeach
@endpush
