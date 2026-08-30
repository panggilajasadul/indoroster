@props(['data'])

@php
    $badge = $data['badge'] ?? '💎 KEUNGGULAN KOMPETITIF';
    $title = $data['title'] ?? 'Kenapa Memilih Roster Pabrik Kami?';
    $description = $data['description'] ?? ($data['subtitle'] ?? 'Dedikasi tanpa kompromi pada mutu beton K-200, kepresisian cetakan siku 90°, dan jaminan keamanan pengiriman langsung ke lokasi proyek Anda.');
    $alignment = $data['alignment'] ?? 'center';
    $columns = $data['columns'] ?? '4';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'dark');
    
    $items = $data['items'] ?? [];
    if (empty($items)) {
        $items = [
            [
                'icon' => '🏭',
                'title' => 'Pabrik Produsen Tangan Pertama',
                'description' => 'Harga langsung dari sentra pengrajin Plered Purwakarta tanpa markup perantara agen atau calo.',
            ],
            [
                'icon' => '🛡️',
                'title' => 'Garansi 100% Bebas Pecah',
                'description' => 'Setiap keping yang rusak atau sompel saat proses pengiriman armada langsung kami ganti baru di tempat.',
            ],
            [
                'icon' => '📐',
                'title' => 'Sudut Siku 90° & Mutu K-200',
                'description' => 'Teknik cetak tumbuk padat bertekanan tinggi menghasilkan pori rapat, kokoh puluhan tahun, dan hemat semen nat.',
            ],
            [
                'icon' => '🚚',
                'title' => 'Armada Truk Logistik Harian',
                'description' => 'Pengiriman tepat waktu terjadwal menjangkau Jabodetabek, Bandung, Banten, Jawa Barat, dan se-Indonesia.',
            ],
        ];
    }

    $headerAlign = match($alignment) {
        'left' => 'text-left items-start',
        'right' => 'text-right items-end',
        default => 'text-center items-center mx-auto'
    };

    $gridCols = match($columns) {
        '2' => 'grid-cols-1 md:grid-cols-2 max-w-4xl mx-auto',
        '3' => 'grid-cols-1 md:grid-cols-3 max-w-6xl mx-auto',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 max-w-7xl mx-auto', // '4'
    };
@endphp

<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Section Header --}}
        <div class="flex flex-col {{ $headerAlign }} max-w-3xl mb-14 sm:mb-18" data-motion="fade-up">
            @if($badge)
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-bold uppercase tracking-wider mb-4 shadow-xs">
                <span>{{ $badge }}</span>
            </div>
            @endif
            
            <h2 class="text-3xl sm:text-4xl md:text-5xl font-black font-display {{ $theme->headingColor }} tracking-tight leading-tight mb-5">
                {!! $title !!}
            </h2>
            
            @if($description)
            <p class="{{ $theme->subColor }} text-base sm:text-lg md:text-xl leading-relaxed">
                {!! $description !!}
            </p>
            @endif
        </div>

        {{-- Grid Cards Layout --}}
        <div class="grid {{ $gridCols }} gap-6 sm:gap-8" data-motion="stagger">
            @foreach($items as $item)
            @php
                $itemIcon = $item['icon'] ?? '💎';
                $itemTitle = $item['title'] ?? '';
                $itemDesc = $item['description'] ?? ($item['content'] ?? '');
            @endphp
            <div data-motion-item class="relative flex flex-col justify-between p-7 sm:p-8 rounded-3xl border transition-all duration-300 group hover:-translate-y-1.5 hover:shadow-2xl {{ $theme->cardBg }}">
                <div>
                    <div class="w-14 h-14 rounded-2xl bg-terra-500/15 dark:bg-terra-500/20 text-terra-600 dark:text-terra-400 flex items-center justify-center text-2xl shrink-0 group-hover:scale-110 group-hover:bg-terra-500 group-hover:text-white transition-all duration-300 shadow-xs mb-6">
                        @if(mb_strlen($itemIcon) <= 3)
                            <span>{{ $itemIcon }}</span>
                        @else
                            <svg class="w-7 h-7 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        @endif
                    </div>
                    
                    <h3 class="text-lg sm:text-xl font-black mb-3 leading-snug {{ $theme->cardTitle }}">
                        {!! $itemTitle !!}
                    </h3>
                    
                    <p class="text-sm sm:text-base leading-relaxed {{ $theme->cardDesc }}">
                        {!! $itemDesc !!}
                    </p>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-200/50 dark:border-slate-800/80 flex items-center gap-2 text-xs font-bold text-terra-500 group-hover:translate-x-1 transition-transform">
                    <span>Standar Mutu Pabrik</span>
                    <svg class="w-3.5 h-3.5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
