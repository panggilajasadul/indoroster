@props(['data'])

@php
    $badge = $data['badge'] ?? 'Keunggulan Kompetitif';
    $title = $data['title'] ?? 'Kenapa Memilih Roster Pabrik Kami?';
    $description = $data['description'] ?? 'Kami mengedepankan kualitas cetakan, kecepatan pengiriman armada mandiri, dan transparansi harga pabrik tangan pertama.';
    $items = $data['items'] ?? [];
    if (empty($items)) {
        $items = [
            [
                'title' => 'Pabrik Tangan Pertama',
                'content' => 'Harga langsung dari produsen tanpa perantara agen atau toko material retail.',
            ],
            [
                'title' => 'Garansi Pecah Ganti Baru',
                'content' => 'Setiap keping roster yang rusak atau pecah saat proses pengiriman armada kami ganti 100% tanpa biaya tambahan.',
            ],
            [
                'title' => 'Armada Truk Khusus',
                'content' => 'Pengiriman tepat waktu terjadwal menggunakan truk boks dan armada khusus material.',
            ],
            [
                'title' => 'Motif Terlengkap',
                'content' => 'Lebih dari 150+ variasi motif modern minimalis, klasik, bunga, dan geometris.',
            ],
        ];
    }
    $videos = $data['videos'] ?? [];
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'dark');
@endphp

<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div data-motion="fade-up">
                @if($badge)
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-bold uppercase tracking-wider mb-5">
                    <span>{{ $badge }}</span>
                </div>
                @endif
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black font-display {{ $theme->headingColor }} tracking-tight leading-tight mb-6">{!! $title !!}</h2>
                @if($description)
                <p class="{{ $theme->subColor }} mb-10 text-base sm:text-lg leading-relaxed">{!! $description !!}</p>
                @endif
                
                <div class="space-y-4 sm:space-y-6" data-motion="stagger">
                    @foreach($items as $item)
                    @php
                        $itemTitle = $item['title'] ?? '';
                        $itemDesc = $item['content'] ?? ($item['description'] ?? '');
                        $itemIcon = $item['icon'] ?? null;
                    @endphp
                    <div data-motion-item class="flex items-start gap-4 p-4 rounded-2xl border transition-all group {{ $theme->cardBg }}">
                        <div class="w-11 h-11 rounded-xl bg-terra-500/20 text-terra-500 flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            @if($itemIcon && mb_strlen($itemIcon) <= 3)
                                <span class="text-lg">{{ $itemIcon }}</span>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-base sm:text-lg font-bold mb-1 {{ $theme->cardTitle }}">{!! $itemTitle !!}</h3>
                            <p class="text-sm leading-relaxed {{ $theme->cardDesc }}">{!! $itemDesc !!}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="relative flex flex-col gap-6" data-motion="scale">
                @if(!empty($videos) && count($videos) > 0)
                    @foreach($videos as $video)
                    @php
                        $finalVideoUrl = !empty($video['video_upload']) ? asset('storage/' . $video['video_upload']) : ($video['url'] ?? '');
                        $ext = pathinfo(parse_url($finalVideoUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
                        $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg', 'mov', 'm4v', 'avi', 'mkv', '3gp']) || str_contains(strtolower($finalVideoUrl), 'video');
                        $videoBoxClass = $theme->isDark 
                            ? 'border-4 border-slate-800 bg-slate-900 shadow-luxury' 
                            : 'border-4 border-white bg-white shadow-soft-xl ring-1 ring-slate-900/5';
                    @endphp
                    <div class="relative rounded-3xl overflow-hidden aspect-video sm:aspect-[4/3] lg:aspect-square {{ $videoBoxClass }}">
                        @if($isVideo)
                        <video class="w-full h-full object-cover" autoplay loop muted playsinline>
                            <source src="{{ $finalVideoUrl }}">
                        </video>
                        @elseif($finalVideoUrl)
                        <img src="{{ $finalVideoUrl }}" class="w-full h-full object-cover" alt="{{ $title }}">
                        @endif
                    </div>
                    @endforeach
                @else
                    <div class="relative rounded-3xl overflow-hidden aspect-square border-4 border-slate-800/80 bg-slate-900 shadow-luxury flex flex-col items-center justify-center p-8 text-center text-white">
                        <span class="text-6xl mb-4">🏭</span>
                        <h3 class="text-2xl font-black mb-2">Sentra Pabrikasi Plered</h3>
                        <p class="text-sm text-slate-300">Kapasitas suplai harian ribuan pieces roster beton tumbuk padat mutu K-200 bergaransi.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
