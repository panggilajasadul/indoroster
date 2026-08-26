@props(['data'])

@php
    $badge = $data['badge'] ?? 'TRANSFORMASI FASAD ARSITEKTURAL';
    $title = $data['title'] ?? 'Lihat Perbedaan Sebelum & Sesudah Pasang Roster';
    $subtitle = $data['subtitle'] ?? 'Geser tombol slider di tengah gambar untuk melihat bagaimana roster beton IndoRoster mengubah dinding polos menjadi fasad modern yang bernilai seni tinggi.';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'dark');

    $beforeImage = !empty($data['before_image_upload']) ? asset('storage/' . $data['before_image_upload']) : ($data['before_image'] ?? 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1200&q=80');
    $afterImage = !empty($data['after_image_upload']) ? asset('storage/' . $data['after_image_upload']) : ($data['after_image'] ?? 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80');
    $beforeLabel = $data['before_label'] ?? 'SEBELUM: Dinding Biasa Polos';
    $afterLabel = $data['after_label'] ?? 'SESUDAH: Fasad Roster IndoRoster';
@endphp

<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden font-sans">
    <x-blocks._bg-theme :theme="$theme" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-14 sm:mb-18">
            @if(!empty($badge))
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-black uppercase tracking-widest mb-4 shadow-soft-xs">
                <span>{{ $badge }}</span>
            </div>
            @endif

            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black {{ $theme->headingColor }} tracking-tight leading-tight mb-4">
                {{ $title }}
            </h2>

            @if(!empty($subtitle))
            <p class="text-sm sm:text-base {{ $theme->subColor }} leading-relaxed">
                {{ $subtitle }}
            </p>
            @endif
        </div>

        <!-- Before After Interactive Slider Container -->
        <div class="max-w-4xl mx-auto" 
            x-data="{
                sliderPos: 50,
                isDragging: false,
                updatePos(e) {
                    let rect = this.$refs.container.getBoundingClientRect();
                    let x = (e.clientX || (e.touches && e.touches[0].clientX)) - rect.left;
                    let percent = (x / rect.width) * 100;
                    this.sliderPos = Math.max(5, Math.min(95, percent));
                }
            }"
        >
            <div 
                x-ref="container" 
                @mousedown="isDragging = true; updatePos($event)" 
                @touchstart="isDragging = true; updatePos($event)" 
                @mousemove="if (isDragging) updatePos($event)" 
                @touchmove="if (isDragging) updatePos($event)" 
                @mouseup="isDragging = false" 
                @touchend="isDragging = false"
                class="relative aspect-16/10 sm:aspect-16/9 rounded-3xl overflow-hidden shadow-2xl border border-slate-700/60 select-none cursor-ew-resize group"
            >
                <!-- After Image (Background) -->
                <img src="{{ $afterImage }}" alt="Sesudah Pemasangan Roster" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute top-4 right-4 px-3.5 py-1.5 rounded-xl bg-terra-500/90 text-white text-xs font-black uppercase tracking-wider backdrop-blur-md shadow-lg z-10">
                    {{ $afterLabel }}
                </div>

                <!-- Before Image (Clipped Foreground) -->
                <div class="absolute inset-0 overflow-hidden" :style="'width: ' + sliderPos + '%'">
                    <img src="{{ $beforeImage }}" alt="Sebelum Pemasangan Roster" class="absolute top-0 left-0 h-full object-cover max-w-none" :style="'width: ' + ($refs.container ? $refs.container.clientWidth + 'px' : '100%')">
                    <div class="absolute top-4 left-4 px-3.5 py-1.5 rounded-xl bg-black/80 text-slate-300 text-xs font-black uppercase tracking-wider backdrop-blur-md shadow-lg z-10">
                        {{ $beforeLabel }}
                    </div>
                </div>

                <!-- Divider Handle Line -->
                <div class="absolute top-0 bottom-0 z-20 pointer-events-none" :style="'left: ' + sliderPos + '%'">
                    <div class="w-1 h-full bg-white shadow-2xl relative -translate-x-1/2">
                        <!-- Circle Drag Handle -->
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white text-slate-900 shadow-2xl flex items-center justify-center border-4 border-terra-500 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-terra-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 9l-4 3 4 3m8-6l4 3-4 3" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Drag Instruction Caption -->
            <div class="mt-4 flex items-center justify-center gap-2 text-xs font-bold text-slate-400">
                <svg class="w-4 h-4 text-terra-500 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 9l-4 3 4 3m8-6l4 3-4 3" /></svg>
                <span>Geser ke kiri / kanan untuk membandingkan transformasi</span>
            </div>
        </div>

    </div>
</section>
