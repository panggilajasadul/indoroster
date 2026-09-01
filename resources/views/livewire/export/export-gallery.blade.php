@php
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => route('home'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'International Export',
                'item' => url('/export'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => 'Architectural Gallery',
                'item' => url('/export/gallery'),
            ],
        ],
    ];
@endphp

@push('seo')
    <script type="application/ld+json">
    {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-8 sm:py-12" 
     x-data="{ 
         lightboxOpen: false, 
         lightboxImg: '', 
         lightboxTitle: '', 
         lightboxLocation: '', 
         lightboxDesc: '', 
         linkedPattern: '', 
         zoomLevel: 1,
         panX: 0,
         panY: 0,
         isDragging: false,
         startX: 0,
         startY: 0,
         openLightbox(img, title, loc, desc, pattern) {
             this.lightboxOpen = true;
             this.lightboxImg = img;
             this.lightboxTitle = title;
             this.lightboxLocation = loc;
             this.lightboxDesc = desc;
             this.linkedPattern = pattern;
             this.resetZoom();
         },
         resetZoom() {
             this.zoomLevel = 1;
             this.panX = 0;
             this.panY = 0;
             this.isDragging = false;
         },
         zoomIn() {
             this.zoomLevel = Math.min(Math.round((this.zoomLevel + 0.5) * 10) / 10, 4);
         },
         zoomOut() {
             this.zoomLevel = Math.max(Math.round((this.zoomLevel - 0.5) * 10) / 10, 1);
             if (this.zoomLevel <= 1) {
                 this.panX = 0;
                 this.panY = 0;
             }
         },
         startDrag(e) {
             if (this.zoomLevel <= 1) return;
             this.isDragging = true;
             const clientX = e.touches ? e.touches[0].clientX : e.clientX;
             const clientY = e.touches ? e.touches[0].clientY : e.clientY;
             this.startX = clientX - this.panX;
             this.startY = clientY - this.panY;
         },
         onDrag(e) {
             if (!this.isDragging || this.zoomLevel <= 1) return;
             if (e.cancelable) e.preventDefault();
             const clientX = e.touches ? e.touches[0].clientX : e.clientX;
             const clientY = e.touches ? e.touches[0].clientY : e.clientY;
             this.panX = clientX - this.startX;
             this.panY = clientY - this.startY;
         },
         endDrag() {
             this.isDragging = false;
         },
         handleWheel(e) {
             if (e.deltaY < 0) {
                 this.zoomIn();
             } else {
                 this.zoomOut();
             }
         },
         toggleDoubleZoom() {
             if (this.zoomLevel === 1) {
                 this.zoomLevel = 2;
             } else {
                 this.resetZoom();
             }
         }
     }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="relative rounded-3xl overflow-hidden bg-slate-900 text-white p-8 sm:p-12 shadow-2xl mb-12 border border-slate-800">
            <div class="absolute -right-20 -top-20 w-96 h-96 bg-terra-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 max-w-3xl">
                <nav class="flex items-center gap-2 text-xs text-slate-400 mb-4 font-medium">
                    <a href="{{ route('home') }}" class="hover:text-white transition">Home</a>
                    <span>/</span>
                    <a href="{{ url('/export') }}" class="hover:text-white transition">Export Hub</a>
                    <span>/</span>
                    <span class="text-terra-400 font-bold">Project Gallery</span>
                </nav>

                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800 text-terra-400 border border-slate-700 text-xs font-bold uppercase tracking-wider mb-4">
                    <span class="w-2 h-2 rounded-full bg-terra-500 animate-pulse"></span>
                    Architectural Project Showcase (100+ Photos)
                </div>

                <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight mb-4 text-white">
                    Breeze Blocks Architectural Portfolio
                </h1>

                <p class="text-sm sm:text-base text-slate-300 mb-6 leading-relaxed">
                    Real architectural installations showcasing our 90° precision steel-mould breeze blocks and concrete screen walls on landed bungalows, tropical cafes, hotel facades, and modern residential spaces.
                </p>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Hello IndoRoster Export Team, I am browsing your International Project Gallery and would like to consult on breeze blocks for our project.') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-terra-500 hover:bg-terra-400 text-white font-bold text-xs sm:text-sm shadow-lg shadow-terra-500/25 transition">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        <span>Consult on Custom Project (WhatsApp)</span>
                    </a>
                    <a href="{{ url('/export') }}" class="inline-flex items-center gap-1 px-5 py-3 rounded-xl bg-slate-800/90 hover:bg-slate-750 text-slate-200 font-bold text-xs border border-slate-700 transition">
                        <span>&larr; Back to Export Hub</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Filter & Search Toolbar --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 sm:p-6 mb-10 shadow-soft-xs">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                {{-- Category Tabs --}}
                <div class="flex items-center gap-2 overflow-x-auto pb-2 md:pb-0 no-scrollbar">
                    <button wire:click="$set('selectedCategory', '')" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition {{ empty($selectedCategory) ? 'bg-terra-500 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                        All Projects
                    </button>
                    @foreach($categories as $cat)
                    <button wire:click="$set('selectedCategory', '{{ $cat->slug }}')" class="px-4 py-2 rounded-xl text-xs font-bold whitespace-nowrap transition {{ $selectedCategory === $cat->slug ? 'bg-terra-500 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                        {{ $cat->name }}
                    </button>
                    @endforeach
                </div>

                {{-- Search Input --}}
                <div class="w-full md:w-64">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search project / motif..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500 focus:outline-none">
                </div>
            </div>
        </div>

        {{-- Project Gallery Grid (No Prices Shown) --}}
        @if($photos->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @foreach($photos as $item)
                @php
                    $gallery = $item->gallery;
                    $photoUrl = $item->formatted_url;
                    $title = $gallery ? $gallery->title : 'Architectural Breeze Block Installation';
                    $location = $gallery && $gallery->location ? $gallery->location : 'Regional Project';
                    $desc = $gallery && $gallery->description ? $gallery->description : 'High precision 90° steel mould breeze block application.';
                    $linkedProduct = $gallery ? $gallery->product : null;
                    $patternName = $linkedProduct ? $linkedProduct->name : '20×20×10 cm Modular Pattern';
                @endphp

                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 overflow-hidden shadow-soft-xs hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                    <div class="relative aspect-4/3 w-full bg-slate-950 overflow-hidden cursor-pointer"
                         @click="openLightbox('{{ $photoUrl }}', '{{ addslashes($title) }}', '{{ addslashes($location) }}', '{{ addslashes($desc) }}', '{{ addslashes($patternName) }}')">
                        <img src="{{ $photoUrl }}" alt="{{ $title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-70 group-hover:opacity-90 transition-opacity"></div>

                        <div class="absolute top-3 left-3">
                            <span class="px-2.5 py-1 rounded-full bg-slate-900/80 backdrop-blur-xs text-white text-[10px] font-bold flex items-center gap-1 border border-white/20">
                                <span>📍</span> {{ $location }}
                            </span>
                        </div>

                        <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="w-8 h-8 rounded-full bg-terra-600 text-white flex items-center justify-center text-xs font-bold shadow-md">
                                🔍
                            </span>
                        </div>

                        <div class="absolute bottom-3 left-3 right-3">
                            <h3 class="text-sm sm:text-base font-extrabold text-white line-clamp-1 group-hover:text-terra-400 transition-colors">
                                {{ $title }}
                            </h3>
                            <p class="text-xs text-slate-300 line-clamp-1 mt-0.5">
                                {{ $desc }}
                            </p>
                        </div>
                    </div>

                    {{-- Spec & Inquiry Bar (Clean — No Prices) --}}
                    <div class="p-4 bg-slate-50 dark:bg-slate-800/60 border-t border-slate-200/80 dark:border-slate-700/80 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            @if($linkedProduct)
                                @php
                                    $prodImg = $linkedProduct->primary_image ?: asset('assets/logo_indoroster_no_text.PNG');
                                @endphp
                                <img src="{{ $prodImg }}" alt="{{ $linkedProduct->name }}" class="w-9 h-9 rounded-lg object-contain bg-white dark:bg-slate-900 p-1 border border-slate-200 dark:border-slate-700 flex-shrink-0">
                                <div class="min-w-0">
                                    <span class="text-[10px] text-terra-600 dark:text-terra-400 font-bold block truncate">Pattern Used:</span>
                                    <h4 class="font-bold text-xs text-slate-900 dark:text-white truncate">{{ $linkedProduct->name }}</h4>
                                </div>
                            @else
                                <div class="w-8 h-8 rounded-lg bg-terra-500/10 text-terra-600 dark:text-terra-400 flex items-center justify-center text-sm font-bold flex-shrink-0">📐</div>
                                <div class="min-w-0">
                                    <span class="text-[10px] text-slate-400 font-bold block">Dimensions:</span>
                                    <h4 class="font-bold text-xs text-slate-900 dark:text-white truncate">20×20×10 cm (Siku 90°)</h4>
                                </div>
                            @endif
                        </div>

                        <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode("Hello IndoRoster, I am inquiring about the project pattern '{$patternName}' seen in your International Gallery.") }}" target="_blank" rel="noopener noreferrer" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold flex items-center gap-1 shadow-2xs transition flex-shrink-0">
                            <span>Inquire Pattern</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Infinite Scroll Trigger --}}
        @if($photos->hasMorePages())
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
                <div class="inline-flex items-center gap-2.5 px-6 py-3 rounded-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 text-xs font-semibold shadow-xs">
                    <svg class="animate-spin h-4 w-4 text-terra-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Loading More Projects...</span>
                </div>
            </div>
        @endif

        @else
        <div class="text-center py-20 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800">
            <p class="text-slate-500 text-sm">No project photos found matching your criteria.</p>
        </div>
        @endif

    </div>

    {{-- Interactive Fullscreen Lightbox Zoom Modal --}}
    <div x-show="lightboxOpen" 
         x-cloak 
         class="fixed inset-0 z-50 bg-slate-950/95 backdrop-blur-md flex items-center justify-center p-2 sm:p-6"
         @keydown.escape.window="lightboxOpen = false"
         @click.self="lightboxOpen = false">
        
        <div class="relative w-full max-w-5xl bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl flex flex-col max-h-[95vh]" @click.stop>
            {{-- Modal Top Bar --}}
            <div class="p-4 sm:p-5 border-b border-slate-800 flex items-center justify-between gap-4 bg-slate-950/60">
                <div class="min-w-0">
                    <span class="text-[10px] font-bold text-terra-400 uppercase tracking-wider block" x-text="lightboxLocation"></span>
                    <h4 class="font-extrabold text-sm sm:text-base text-white truncate" x-text="lightboxTitle"></h4>
                </div>

                <div class="flex items-center gap-2">
                    <button @click="zoomIn()" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700 transition" title="Zoom In (+)">➕</button>
                    <button @click="zoomOut()" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700 transition" title="Zoom Out (-)">➖</button>
                    <button @click="resetZoom()" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700 transition" title="Reset Zoom">↺</button>
                    <button @click="lightboxOpen = false" class="p-2 rounded-xl bg-red-500/20 hover:bg-red-500/30 text-red-300 text-xs font-bold border border-red-500/30 transition">✕</button>
                </div>
            </div>

            {{-- Zoomable & Grabbable Image Container --}}
            <div class="flex-1 overflow-hidden bg-slate-950 p-4 flex items-center justify-center min-h-[300px] sm:min-h-[500px] select-none relative cursor-grab"
                 :class="{ 'cursor-grabbing': isDragging, 'cursor-zoom-in': zoomLevel <= 1 }"
                 @wheel.prevent="handleWheel($event)"
                 @mousedown="startDrag($event)"
                 @mousemove="onDrag($event)"
                 @mouseup="endDrag()"
                 @mouseleave="endDrag()"
                 @touchstart="startDrag($event)"
                 @touchmove="onDrag($event)"
                 @touchend="endDrag()">
                <img :src="lightboxImg" 
                     :alt="lightboxTitle" 
                     class="max-w-full max-h-[70vh] object-contain select-none pointer-events-none transition-transform"
                     :class="isDragging ? 'duration-0' : 'duration-200 ease-out'"
                     :style="`transform: translate3d(${panX}px, ${panY}px, 0px) scale(${zoomLevel});`"
                     @dblclick="toggleDoubleZoom()"
                     draggable="false">
            </div>

            {{-- Modal Footer Bar --}}
            <div class="p-4 bg-slate-950/80 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
                <p class="line-clamp-1" x-text="lightboxDesc"></p>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <span class="text-[11px] text-slate-400">💡 Klik & seret mouse untuk geser saat di-zoom</span>
                    <a :href="`https://wa.me/{{ $waNumber }}?text=Hello%20IndoRoster%20Export%20Desk,%20I%20am%20inquiring%20about%20the%20pattern:%20` + encodeURIComponent(linkedPattern) + `%20from%20project:%20` + encodeURIComponent(lightboxTitle)" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center gap-1.5 transition">
                        <span>Inquire This Pattern (WhatsApp)</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
