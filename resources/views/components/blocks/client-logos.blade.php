@props(['data'])

@php
    $badge = $data['badge'] ?? 'DIPERCAYA ARSITEK & KONTRAKTOR';
    $title = $data['title'] ?? 'Telah Digunakan di Berbagai Proyek Ternama';
    $subtitle = $data['subtitle'] ?? 'Dipercaya oleh developer perumahan, konsultan arsitektur, dan ribuan pemilik hunian di seluruh Indonesia.';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');

    $defaultLogos = [
        ['name' => 'Wika Gedung', 'category' => 'Kontraktor BUMN'],
        ['name' => 'Adhi Karya', 'category' => 'Infrastruktur'],
        ['name' => 'Summarecon', 'category' => 'Developer Perumahan'],
        ['name' => 'Sinarmas Land', 'category' => 'Kawasan Mandiri'],
        ['name' => 'Ciputra Group', 'category' => 'Residensial'],
        ['name' => 'Agung Podomoro', 'category' => 'Komersial & Mall'],
        ['name' => 'Studio Arsitek Urban', 'category' => 'Konsultan Desain'],
        ['name' => 'Kopi Kenangan Project', 'category' => 'Fasad Cafe & Retail'],
    ];

    $logos = !empty($data['logos']) ? $data['logos'] : $defaultLogos;
@endphp

<section class="py-16 sm:py-24 {{ $theme->bgClasses }} relative overflow-hidden font-sans border-y border-slate-200/60 dark:border-slate-800">
    <x-blocks._bg-theme :theme="$theme" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
            @if(!empty($badge))
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-black uppercase tracking-widest mb-3.5 shadow-soft-xs">
                <span>{{ $badge }}</span>
            </div>
            @endif

            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black {{ $theme->headingColor }} tracking-tight leading-tight">
                {{ $title }}
            </h2>

            @if(!empty($subtitle))
            <p class="text-xs sm:text-sm {{ $theme->subColor }} mt-2 max-w-xl mx-auto">
                {{ $subtitle }}
            </p>
            @endif
        </div>

        <!-- Logos Grid / Badges -->
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach($logos as $logo)
            @php
                $logoImg = !empty($logo['image_upload']) ? asset('storage/' . $logo['image_upload']) : ($logo['image'] ?? null);
            @endphp
            <div class="p-6 rounded-2xl {{ $theme->cardBg }} border flex flex-col items-center justify-center text-center hover:border-terra-500/50 hover:shadow-luxury transition-all duration-300 group">
                @if($logoImg)
                    <img src="{{ $logoImg }}" alt="{{ $logo['name'] ?? 'Logo Partner' }}" class="max-h-12 w-auto object-contain grayscale group-hover:grayscale-0 transition-all">
                @else
                    <div class="w-12 h-12 rounded-xl bg-terra-500/10 text-terra-500 flex items-center justify-center font-black text-xl mb-2 group-hover:scale-110 transition-transform">
                        🏛️
                    </div>
                    <div class="font-bold text-sm sm:text-base {{ $theme->cardTitle }} leading-tight">
                        {{ $logo['name'] ?? 'Partner Proyek' }}
                    </div>
                    <div class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold mt-1">
                        {{ $logo['category'] ?? 'Proyek Nasional' }}
                    </div>
                @endif
            </div>
            @endforeach
        </div>

    </div>
</section>
