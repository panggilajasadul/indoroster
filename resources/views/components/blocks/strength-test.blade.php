@props(['data'])

@php
    $title = $data['title'] ?? 'Uji Kepadatan & Kekuatan Cetak Tumbuk';
    $description = $data['description'] ?? 'Roster kami diproduksi dengan teknik cetak tumbuk padat khusus oleh pengrajin berpengalaman Plered untuk menjamin kepadatan tanpa rongga, keras, dan tahan benturan.';
    $videoUrl = $data['video_url'] ?? '';
    $features = $data['features'] ?? [];
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'slate');
@endphp

<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
            
            <!-- Left Text Column -->
            <div class="lg:w-1/2" data-motion="fade-up">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-bold uppercase tracking-wider mb-5">
                    <svg class="w-4 h-4 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span>Standar Kualitas Teruji</span>
                </div>
                
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black font-display {{ $theme->headingColor }} tracking-tight leading-tight mb-6">
                    {!! $title !!}
                </h2>
                
                <p class="text-base sm:text-lg {{ $theme->subColor }} mb-8 leading-relaxed">
                    {!! $description !!}
                </p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5" data-motion="stagger">
                    @foreach($features as $feature)
                    <div data-motion-item data-tilt class="p-4 rounded-2xl border transition-all flex items-start gap-3.5 {{ $theme->cardBg }}">
                        <div class="w-10 h-10 rounded-xl bg-terra-500/15 text-terra-500 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm leading-snug {{ $theme->cardTitle }}">{{ $feature['title'] ?? '' }}</h4>
                            <p class="text-xs mt-1 leading-normal {{ $theme->cardDesc }}">{{ $feature['desc'] ?? $feature['description'] ?? '' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Right Video / Visual Column -->
            <div class="lg:w-1/2 w-full" data-motion="scale">
                <div data-tilt class="relative aspect-video rounded-3xl overflow-hidden shadow-luxury border-4 border-white/80 bg-slate-900 group">
                    @php
                        $finalVideoUrl = !empty($data['video_upload']) ? asset('storage/' . $data['video_upload']) : $videoUrl;
                        $ext = pathinfo(parse_url($finalVideoUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
                        $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg']) || str_contains(strtolower($finalVideoUrl), 'video');
                    @endphp
                    @if($isVideo)
                    <video class="w-full h-full object-cover" autoplay loop muted playsinline>
                        <source src="{{ $finalVideoUrl }}" type="video/mp4">
                    </video>
                    @elseif($finalVideoUrl)
                    <img src="{{ $finalVideoUrl }}" class="w-full h-full object-cover" alt="Uji Kekuatan Roster">
                    @else
                    <div class="w-full h-full flex flex-col items-center justify-center bg-slate-900 text-slate-400 p-8 text-center">
                        <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center mb-3">
                            <svg class="w-8 h-8 text-terra-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="font-bold text-white text-base">Video Uji Kepadatan Roster</span>
                        <span class="text-xs text-slate-400 mt-1">Pengujian kekuatan langsung di pabrik Plered Purwakarta</span>
                    </div>
                    @endif

                    <!-- Floating Quality Stamp -->
                    <div class="absolute bottom-4 right-4 bg-slate-950/80 backdrop-blur-md border border-white/20 px-3.5 py-1.5 rounded-xl flex items-center gap-2 text-white shadow-lg">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-[11px] font-black tracking-wider uppercase">Cetak Tumbuk Padat</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
