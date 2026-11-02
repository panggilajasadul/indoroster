@props(['data', 'pageTitle' => null])

@php
    $badge = $data['badge'] ?? 'Produsen Roster Sejak 2019 · Purwakarta';
    $title = $data['title'] ?? 'Roster Beton Kualitas Mantap, Rumah Makin Sejuk & Cantik';
    $highlightText = $data['highlight_text'] ?? 'Sejuk & Cantik';
    $subtitle = $data['subtitle'] ?? 'Produsen roster beton sejak 2019 di Kp. Cicadas, Tegalwaru, Purwakarta — 90+ motif roster, genteng, bata ekspose, bata tempel, dan ornamen dinding 3D. Gratis ongkir Jabodetabek (min. 50 pcs), harga langsung pabrik, konsultasi gratis.';
    
    // Primary Button
    $btn1Text = $data['button_1_text'] ?? 'Chat WhatsApp Sekarang';
    $btn1Url = $data['button_1_url'] ?? 'https://wa.me/6281234567890?text=Halo%20IndoRoster,%20saya%20tertarik%20dengan%20koleksi%20roster';
    $btn1Type = $data['button_1_type'] ?? 'whatsapp';

    // Secondary Button
    $btn2Text = $data['button_2_text'] ?? 'Lihat Katalog Produk';
    $btn2Url = $data['button_2_url'] ?? route('catalog');

    // Image
    $imageUpload = $data['image_upload'] ?? null;
    $imageUrl = $data['image_url'] ?? null;
    $finalImage = $imageUpload ? asset('storage/' . $imageUpload) : ($imageUrl ?: 'https://res.cloudinary.com/indoroster/image/upload/v1765262980/2_zurmam.jpg');
    $imageAlt = $data['image_alt'] ?? $title;

    // Badges on image
    $showTopBadge = $data['show_top_badge'] ?? true;
    $topBadgeText = $data['top_badge_text'] ?? "GRATIS ONGKIR\nArea Jabodetabek min. 50 pcs";

    $showBottomBadge = $data['show_bottom_badge'] ?? true;
    $bottomBadgeRating = $data['bottom_badge_rating'] ?? '4.9/5';
    $bottomBadgeText = $data['bottom_badge_text'] ?? '120+ pelanggan puas';

    // Trust Points
    $trustPoints = $data['trust_points'] ?? [
        ['icon' => 'factory', 'text' => 'Produsen langsung sejak 2019'],
        ['icon' => 'palette', 'text' => '90+ motif roster & custom'],
        ['icon' => 'truck', 'text' => 'Gratis ongkir Jabodetabek (min. 50 pcs)'],
        ['icon' => 'shield', 'text' => 'Retak saat kirim? Ganti baru'],
    ];

    // Theme resolution: support both old 'theme' and new 'bg_theme' from PageBuilder
    $rawTheme = $data['bg_theme'] ?? ($data['theme'] ?? 'light-glow');
    $resolvedTheme = \App\Helpers\BlockTheme::resolve($rawTheme);
    $fontFamily = $data['font_family'] ?? 'font-poppins';
    $reverseMobile = $data['reverse_mobile'] ?? false;

    // Highlighting title with curve/color
    if (!empty($highlightText) && str_contains($title, $highlightText)) {
        $highlightHtml = '<span class="relative inline-block text-amber-600 dark:text-amber-500 font-extrabold whitespace-nowrap">' . e($highlightText) . '<svg class="absolute -bottom-1.5 left-0 w-full h-3 text-amber-500/80 -z-10" viewBox="0 0 100 20" preserveAspectRatio="none" fill="none"><path d="M3 14 C 25 18, 75 18, 97 12 C 80 15, 40 16, 5 14" stroke="currentColor" stroke-width="4.5" stroke-linecap="round"/></svg></span>';
        $formattedTitle = str_replace($highlightText, $highlightHtml, e($title));
    } else {
        $formattedTitle = e($title);
    }
@endphp

<section class="relative overflow-hidden pt-8 pb-14 sm:pt-12 sm:pb-20 lg:pt-16 lg:pb-24 {{ $fontFamily }} {{ $resolvedTheme->bgClasses }} transition-colors duration-300">
    
    {{-- Background Motif & Ambience dari BlockTheme --}}
    <x-blocks._bg-theme :theme="$resolvedTheme" />

    {{-- Ambient Glow Tambahan Khusus Mode Light Glow / Warm (Bila di light mode hangat) --}}
    @if(in_array($rawTheme, ['light-glow', 'light_glow', 'warm']))
        <div class="pointer-events-none absolute -top-24 -left-24 w-96 h-96 sm:w-[500px] sm:h-[500px] bg-amber-200/40 dark:bg-amber-500/10 rounded-full blur-3xl -z-0"></div>
        <div class="pointer-events-none absolute top-1/3 -right-20 w-80 h-80 sm:w-[450px] sm:h-[450px] bg-emerald-100/50 dark:bg-emerald-500/10 rounded-full blur-3xl -z-0"></div>
        <div class="pointer-events-none absolute bottom-0 left-1/4 w-72 h-72 bg-orange-100/30 dark:bg-transparent rounded-full blur-2xl -z-0"></div>
    @endif

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 xl:gap-16 items-center {{ $reverseMobile ? 'flex flex-col-reverse lg:grid' : '' }}">
            
            {{-- KOLOM KIRI: TEKS & CTA --}}
            <div class="lg:col-span-7 flex flex-col items-start text-left">
                
                {{-- Badge Lokasi / Brand Atas --}}
                @if(!empty($badge))
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs sm:text-sm font-semibold tracking-wide shadow-sm mb-5 sm:mb-6 transition-transform hover:scale-105 duration-200 bg-amber-50 dark:bg-slate-900/90 text-amber-900 dark:text-amber-400 border border-amber-200/80 dark:border-slate-800 shadow-amber-900/5">
                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $badge }}</span>
                    </div>
                @endif

                {{-- Headline Judul --}}
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-5xl xl:text-[54px] font-black tracking-tight leading-[1.18] sm:leading-[1.15] mb-5 sm:mb-6 {{ $resolvedTheme->headingColor }}">
                    {!! $formattedTitle !!}
                </h1>

                {{-- Deskripsi Subtitle --}}
                @if(!empty($subtitle))
                    <p class="text-sm sm:text-base md:text-lg leading-relaxed mb-7 sm:mb-9 max-w-2xl {{ $resolvedTheme->subColor }}">
                        {{ $subtitle }}
                    </p>
                @endif

                {{-- Tombol Aksi (CTA) --}}
                <div class="w-full sm:w-auto flex flex-col sm:flex-row items-stretch sm:items-center gap-3.5 sm:gap-4 mb-8 sm:mb-10">
                    {{-- Tombol Utama --}}
                    @if(!empty($btn1Text))
                        <a href="{{ $btn1Url }}" 
                           @if(str_contains($btn1Url, 'wa.me') || str_contains($btn1Url, 'whatsapp')) target="_blank" rel="noopener" @endif
                           class="inline-flex items-center justify-center gap-2.5 px-6 sm:px-7 py-3.5 sm:py-4 rounded-full font-bold text-sm sm:text-base text-white bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] shadow-lg shadow-emerald-600/30 transition-all duration-200 group text-center">
                            @if(str_contains($btn1Url, 'wa.me') || str_contains($btn1Url, 'whatsapp') || $btn1Type === 'whatsapp')
                                <svg class="w-5 h-5 fill-current shrink-0 group-hover:scale-110 transition-transform" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.584 1.777.818 2.791.818 3.179 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.77-5.767-5.772zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2.05 21.95l4.908-1.286A9.957 9.957 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18.2c-1.637 0-3.155-.472-4.437-1.288l-.318-.202-3.29.863.878-3.208-.208-.33A8.167 8.167 0 013.8 12c0-4.521 3.679-8.2 8.2-8.2 4.52 0 8.2 3.679 8.2 8.2 0 4.522-3.68 8.2-8.2 8.2z"/>
                                </svg>
                            @endif
                            <span>{{ $btn1Text }}</span>
                        </a>
                    @endif

                    {{-- Tombol Kedua (Katalog / Sekunder) --}}
                    @if(!empty($btn2Text))
                        <a href="{{ $btn2Url }}" 
                           class="inline-flex items-center justify-center gap-2 px-6 sm:px-7 py-3.5 sm:py-4 rounded-full font-bold text-sm sm:text-base border transition-all duration-200 group text-center border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900/60 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-800 dark:text-white shadow-sm hover:border-slate-400">
                            <span>{{ $btn2Text }}</span>
                            <svg class="w-4 h-4 text-slate-400 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                            </svg>
                        </a>
                    @endif
                </div>

                {{-- Trust Badges / Nilai Unggul di Bawah CTA --}}
                @if(!empty($trustPoints) && is_array($trustPoints))
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-3.5 w-full pt-4 border-t border-slate-200/80 dark:border-slate-800/80">
                        @foreach($trustPoints as $point)
                            @php
                                $ptText = $point['text'] ?? '';
                                $ptIcon = $point['icon'] ?? 'check';
                            @endphp
                            @if(!empty($ptText))
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg shrink-0 flex items-center justify-center bg-emerald-100/70 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800">
                                        @if($ptIcon === 'truck')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                                        @elseif($ptIcon === 'shield')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        @elseif($ptIcon === 'palette')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4 5 5 0 013-4.5 7 7 0 0110.5-5.5 7 7 0 013 7.5 4 4 0 01-4 4.5h-2a2 2 0 00-2 2v.5a2 2 0 01-2 2H7z"/></svg>
                                        @elseif($ptIcon === 'factory')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        @endif
                                    </div>
                                    <span class="text-xs sm:text-sm font-medium text-slate-700 dark:text-slate-300">{{ $ptText }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

            </div>

            {{-- KOLOM KANAN: FOTO CARD MODERN DENGAN FLOATING BADGE --}}
            <div class="lg:col-span-5 relative w-full flex justify-center lg:justify-end">
                <div class="relative w-full max-w-md sm:max-w-lg lg:max-w-none group">
                    
                    {{-- Decorative Blur Shadow Background --}}
                    <div class="absolute -inset-2 bg-gradient-to-r from-amber-400/20 via-emerald-400/20 to-teal-400/20 rounded-3xl blur-xl opacity-75 group-hover:opacity-100 transition duration-500"></div>

                    {{-- Main Photo Card Container --}}
                    <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl border border-white/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-slate-900/10">
                        <img 
                            src="{{ $finalImage }}" 
                            alt="{{ $imageAlt }}" 
                            class="w-full h-[280px] sm:h-[360px] md:h-[400px] lg:h-[420px] object-cover object-center transform group-hover:scale-[1.02] transition-transform duration-500 ease-out"
                            loading="eager"
                        >
                    </div>

                    {{-- FLOATING BADGE 1: Top-Left (Gratis Ongkir / Info Proyek) --}}
                    @if($showTopBadge && !empty($topBadgeText))
                        <div class="absolute -top-3 -left-2 sm:-left-4 z-20 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md px-3.5 py-2.5 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-800 flex items-start gap-2.5 max-w-[210px] sm:max-w-[230px] animate-fade-in">
                            <div class="w-7 h-7 rounded-xl bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 mt-0.5 border border-emerald-100 dark:border-emerald-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                </svg>
                            </div>
                            <div class="leading-tight text-left">
                                @php
                                    $lines = explode("\n", $topBadgeText);
                                    $head = $lines[0] ?? '';
                                    $sub = $lines[1] ?? '';
                                @endphp
                                <span class="block text-[11px] sm:text-xs font-black tracking-wide text-emerald-800 dark:text-emerald-300 uppercase">{{ $head }}</span>
                                @if(!empty($sub))
                                    <span class="block text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 font-medium">{{ $sub }}</span>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- FLOATING BADGE 2: Bottom-Right (Rating / Review Puas) --}}
                    @if($showBottomBadge)
                        <div class="absolute -bottom-3 -right-2 sm:-right-4 z-20 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md px-4 py-2.5 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-800 flex items-center gap-3 animate-fade-in">
                            <div class="flex items-center gap-0.5 text-amber-400">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <div class="leading-tight text-left border-l border-slate-200 dark:border-slate-700 pl-2.5">
                                <span class="block text-xs sm:text-sm font-black text-slate-900 dark:text-white">{{ $bottomBadgeRating }}</span>
                                <span class="block text-[10px] sm:text-[11px] text-slate-500 dark:text-slate-400 font-medium whitespace-nowrap">{{ $bottomBadgeText }}</span>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</section>
