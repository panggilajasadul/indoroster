@props(['data'])

@php
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');
    $title = $data['title'] ?? 'Penasaran Detail Tekstur vs Tampak Jarak Jauh?';
    $subtitle = $data['subtitle'] ?? 'Bandingkan kualitas detail presisi cetakan jarak dekat dengan keanggunan fasad arsitektural saat terpasang dari kejauhan.';
    $badge = $data['badge'] ?? '🔍 Visual Inspector 360°';
    $items = $data['items'] ?? [];
    
    // Process items
    $processedItems = [];
    foreach ($items as $item) {
        $closeUpload = $item['close_up_upload'] ?? null;
        $closeUrl = $item['close_up_url'] ?? null;
        $closeFile = !empty($closeUpload) ? (is_array($closeUpload) ? array_values($closeUpload)[0] : $closeUpload) : $closeUrl;
        $closeImg = $closeFile ? (str_starts_with($closeFile, 'http') ? $closeFile : asset('storage/' . ltrim($closeFile, '/'))) : null;

        $farUpload = $item['far_view_upload'] ?? null;
        $farUrl = $item['far_view_url'] ?? null;
        $farFile = !empty($farUpload) ? (is_array($farUpload) ? array_values($farUpload)[0] : $farUpload) : $farUrl;
        $farImg = $farFile ? (str_starts_with($farFile, 'http') ? $farFile : asset('storage/' . ltrim($farFile, '/'))) : null;

        if ($closeImg || $farImg) {
            $processedItems[] = [
                'title' => $item['title'] ?? 'Roster Beton Arsitektural',
                'close_img' => $closeImg ?: $farImg,
                'close_desc' => $item['close_desc'] ?: 'Detail tekstur pori padat, sudut cetak siku 45° presisi, dan permukaan halus tanpa retak rambut.',
                'far_img' => $farImg ?: $closeImg,
                'far_desc' => $item['far_desc'] ?: 'Tampilan megah dan estetik pada fasad bangunan dengan ventilasi silang udara alami yang optimal.',
                'link' => $item['link'] ?? null,
                'button_text' => $item['button_text'] ?? 'Lihat Produk Ini',
            ];
        }
    }
@endphp

<section class="py-12 sm:py-16 {{ $theme->bgClasses }} relative overflow-hidden select-none">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Section Header --}}
        <div class="mb-8 sm:mb-12 text-center max-w-3xl mx-auto">
            @if($badge)
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full {{ $theme->badgeClass }} text-xs font-bold uppercase tracking-wider mb-3 shadow-sm">
                    <x-heroicon-m-eye class="w-3.5 h-3.5 text-terra-500" />
                    {{ $badge }}
                </div>
            @endif
            @if($title)
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-display {{ $theme->headingColor }} tracking-tight leading-tight">
                    {!! $title !!}
                </h2>
            @endif
            @if($subtitle)
                <p class="mt-2.5 text-sm sm:text-base {{ $theme->subColor }} leading-relaxed">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        @if(count($processedItems) > 0)
            <div class="space-y-12">
                @foreach($processedItems as $index => $item)
                    <div 
                        x-data="{ 
                            mode: 'close', 
                            lightbox: false, 
                            currentImg: '{{ $item['close_img'] }}',
                            currentTitle: '{{ $item['title'] }}'
                        }"
                        class="p-5 sm:p-8 rounded-3xl bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-slate-200/80 dark:border-slate-800 shadow-xl shadow-slate-200/40 dark:shadow-slate-950/50">
                        
                        {{-- Top Title & Interactive Switcher --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
                            <div>
                                <h3 class="text-xl sm:text-2xl font-black font-display text-slate-900 dark:text-white">
                                    {{ $item['title'] }}
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                                    Klik tombol sudut pandang di bawah untuk melihat perbedaan detail:
                                </p>
                            </div>

                            {{-- View Mode Switcher Pills --}}
                            <div class="inline-flex p-1 rounded-2xl bg-slate-100 dark:bg-slate-800/90 border border-slate-200/80 dark:border-slate-700/80 shrink-0 shadow-inner">
                                <button 
                                    type="button" 
                                    @click="mode = 'close'"
                                    :class="mode === 'close' ? 'bg-white dark:bg-slate-900 text-terra-600 dark:text-terra-400 shadow-md font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'"
                                    class="px-4 py-2 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center gap-1.5 focus:outline-none">
                                    <x-heroicon-m-magnifying-glass-plus class="w-4 h-4" />
                                    <span>🔍 Jarak Dekat (Detail Tekstur)</span>
                                </button>

                                <button 
                                    type="button" 
                                    @click="mode = 'far'"
                                    :class="mode === 'far' ? 'bg-white dark:bg-slate-900 text-terra-600 dark:text-terra-400 shadow-md font-bold' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-medium'"
                                    class="px-4 py-2 rounded-xl text-xs sm:text-sm transition-all duration-200 flex items-center gap-1.5 focus:outline-none">
                                    <x-heroicon-m-building-office-2 class="w-4 h-4" />
                                    <span>🏢 Jarak Jauh (Tampak Fasad)</span>
                                </button>
                            </div>
                        </div>

                        {{-- Main Visual Display & Description --}}
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-center mt-6">
                            {{-- Interactive Visual Stage --}}
                            <div class="lg:col-span-8 relative aspect-[16/10] sm:aspect-[16/9] rounded-2xl overflow-hidden bg-slate-950 border border-slate-200 dark:border-slate-800 shadow-inner group cursor-pointer"
                                 @click="lightbox = true; currentImg = (mode === 'close' ? '{{ $item['close_img'] }}' : '{{ $item['far_img'] }}')">
                                
                                {{-- Close-up View Image --}}
                                <img 
                                    src="{{ $item['close_img'] }}" 
                                    alt="{{ $item['title'] }} - Detail Jarak Dekat"
                                    class="absolute inset-0 w-full h-full object-cover transition-all duration-500 ease-out"
                                    :class="mode === 'close' ? 'opacity-100 scale-100 z-10' : 'opacity-0 scale-95 pointer-events-none z-0'"
                                    loading="lazy" />

                                {{-- Far View Image --}}
                                <img 
                                    src="{{ $item['far_img'] }}" 
                                    alt="{{ $item['title'] }} - Tampak Jarak Jauh"
                                    class="absolute inset-0 w-full h-full object-cover transition-all duration-500 ease-out"
                                    :class="mode === 'far' ? 'opacity-100 scale-100 z-10' : 'opacity-0 scale-95 pointer-events-none z-0'"
                                    loading="lazy" />

                                {{-- Floating View Indicator Tag --}}
                                <div class="absolute top-4 left-4 z-20">
                                    <span 
                                        x-show="mode === 'close'"
                                        x-transition
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-950/80 text-white font-bold text-xs shadow-lg backdrop-blur-md border border-white/20">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                        Mode Jarak Dekat (Macro Texture)
                                    </span>

                                    <span 
                                        x-show="mode === 'far'"
                                        x-transition
                                        class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-950/80 text-white font-bold text-xs shadow-lg backdrop-blur-md border border-white/20">
                                        <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                                        Mode Jarak Jauh (Architectural Facade)
                                    </span>
                                </div>

                                {{-- Zoom Magnifier Hint --}}
                                <div class="absolute bottom-4 right-4 z-20 opacity-90 group-hover:opacity-100 transition-opacity">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white/90 dark:bg-slate-900/90 text-slate-800 dark:text-slate-200 text-xs font-bold shadow-lg backdrop-blur-md border border-slate-200 dark:border-slate-700 group-hover:scale-105 transition-transform">
                                        <x-heroicon-o-arrows-pointing-out class="w-3.5 h-3.5 text-terra-500" />
                                        Klik untuk Perbesar Fullscreen
                                    </span>
                                </div>
                            </div>

                            {{-- Description & Specification Info --}}
                            <div class="lg:col-span-4 flex flex-col justify-between space-y-6">
                                <div class="space-y-4">
                                    {{-- Active Perspective Description Card --}}
                                    <div 
                                        x-show="mode === 'close'" 
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-y-2"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        class="p-4 sm:p-5 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-slate-800 dark:text-slate-200">
                                        <div class="flex items-center gap-2 font-bold text-amber-600 dark:text-amber-400 text-sm mb-1.5">
                                            <x-heroicon-m-check-badge class="w-5 h-5 shrink-0" />
                                            Keunggulan Detail Presisi:
                                        </div>
                                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                                            {{ $item['close_desc'] }}
                                        </p>
                                    </div>

                                    <div 
                                        x-show="mode === 'far'" 
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-y-2"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        class="p-4 sm:p-5 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-slate-800 dark:text-slate-200">
                                        <div class="flex items-center gap-2 font-bold text-blue-600 dark:text-blue-400 text-sm mb-1.5">
                                            <x-heroicon-m-home-modern class="w-5 h-5 shrink-0" />
                                            Keanggunan Fasad Jarak Jauh:
                                        </div>
                                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                                            {{ $item['far_desc'] }}
                                        </p>
                                    </div>

                                    {{-- Quality Specs Bullet Points --}}
                                    <div class="grid grid-cols-2 gap-3 pt-2 text-xs">
                                        <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-700/60">
                                            <div class="text-slate-400 font-medium">Kepadatan Semen</div>
                                            <div class="font-bold text-slate-800 dark:text-slate-200 mt-0.5">Mutu Beton K-200</div>
                                        </div>
                                        <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-700/60">
                                            <div class="text-slate-400 font-medium">Ketebalan Standar</div>
                                            <div class="font-bold text-slate-800 dark:text-slate-200 mt-0.5">10 cm Kokoh & Stabil</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- CTA Link Button --}}
                                @if(!empty($item['link']))
                                    <a 
                                        href="{{ $item['link'] }}" 
                                        class="inline-flex items-center justify-center gap-2 w-full px-5 py-3 rounded-xl bg-terra-500 hover:bg-terra-600 text-white font-bold text-sm shadow-lg shadow-terra-500/30 hover:scale-[1.02] active:scale-98 transition-all duration-200">
                                        <span>{{ $item['button_text'] }}</span>
                                        <x-heroicon-m-arrow-right class="w-4 h-4" />
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Lightbox Modal --}}
                        <div 
                            x-show="lightbox" 
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 backdrop-blur-none"
                            x-transition:enter-end="opacity-100 backdrop-blur-xl"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 backdrop-blur-xl"
                            x-transition:leave-end="opacity-0 backdrop-blur-none"
                            @keydown.escape.window="lightbox = false"
                            class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-950/90"
                            style="display: none;">
                            
                            <div class="relative max-w-5xl w-full bg-slate-900 rounded-3xl overflow-hidden shadow-2xl border border-slate-700 flex flex-col max-h-[90vh]">
                                <div class="flex items-center justify-between p-4 border-b border-slate-800 text-white">
                                    <div class="font-bold text-sm sm:text-base flex items-center gap-2">
                                        <x-heroicon-m-magnifying-glass class="w-4 h-4 text-terra-400" />
                                        <span x-text="currentTitle"></span>
                                    </div>
                                    <button 
                                        type="button" 
                                        @click="lightbox = false"
                                        class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white flex items-center justify-center transition">
                                        <x-heroicon-m-x-mark class="w-5 h-5" />
                                    </button>
                                </div>
                                <div class="p-2 sm:p-4 overflow-auto flex items-center justify-center bg-black/60 min-h-[300px]">
                                    <img :src="currentImg" class="max-w-full max-h-[75vh] object-contain rounded-xl shadow-2xl" />
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12 px-4 rounded-2xl border-2 border-dashed border-slate-300 dark:border-slate-700 text-slate-400">
                <x-heroicon-o-photo class="w-12 h-12 mx-auto mb-2 opacity-50" />
                <p class="font-medium text-sm">Belum ada item inspektur detail & jarak jauh yang ditambahkan.</p>
            </div>
        @endif
    </div>
</section>
