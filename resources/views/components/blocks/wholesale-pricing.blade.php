@props(['data', 'pageTitle' => null])

@php
    $badge = $data['badge'] ?? null;
    $title = $data['title'] ?? 'Makin Banyak Makin Murah — Harga Langsung Pabrik';
    $subtitle = $data['subtitle'] ?? 'Kontraktor, tukang, distributor, dan pengembang — kapasitas produksi kami 6.000 pcs dalam 6 hari (tergantung motif). Pesan 1.000 pcs ke atas dan dapatkan harga spesial, plus gratis ongkir area Jabodetabek.';
    
    // Banner Highlight
    $bannerTitle = $data['banner_title'] ?? 'GRATIS ONGKIR area Jabodetabek 🎉';
    $bannerSubtitle = $data['banner_subtitle'] ?? 'Khusus pembelian minimal 50 pcs — Jakarta, Bogor, Depok, Tangerang, Bekasi.';
    $ctaUrl = $data['cta_url'] ?? 'https://wa.me/6281389709847?text=Halo%20IndoRoster,%20saya%20mau%20tanya%20penawaran%20harga%20grosir%20proyek';
    $ctaText = $data['cta_text'] ?? 'Tanya Harga Grosir via WhatsApp';

    // Card 1: Grosir Pabrik (Featured / Tengah)
    $card1Badge = $data['card1_badge'] ?? '⭐ Paling Hemat';
    $card1Label = $data['card1_label'] ?? 'Pesanan Proyek / 1.000+ pcs';
    $wholesalePrice = $data['wholesale_price'] ?? '9.500';
    $card1Unit = $data['card1_unit'] ?? '/pcs';
    $card1StrikePrice = $data['card1_strike_price'] ?? 'Rp 12.000';
    $card1Desc = $data['card1_desc'] ?? 'Hemat langsung dari produksi pengrajin. Kapasitas suplai terjaga hingga 6.000 pcs dalam 6 hari.';
    $card1Features = $data['card1_features'] ?? [
        ['text' => 'Stok prioritas & jadwal kirim terjadwal'],
        ['text' => 'Faktur / Kwitansi resmi proyek'],
        ['text' => 'Bantuan hitung m² & rincian pcs'],
    ];
    $card1BtnText = $data['card1_btn_text'] ?? 'Klaim Harga Grosir';
    $card1BtnUrl = $data['card1_btn_url'] ?? $ctaUrl;

    // Card 2: Harga Normal Eceran Pabrik (Kiri)
    $card2Label = $data['card2_label'] ?? 'Harga Pabrik Normal';
    $normalPrice = $data['normal_price'] ?? '12.000';
    $card2Unit = $data['card2_unit'] ?? '/pcs';
    $card2Desc = $data['card2_desc'] ?? 'Cocok untuk renovasi rumah tinggal, pagar, atau fasad pribadi dengan kualitas cetak tumbuk padat presisi.';
    $card2Features = $data['card2_features'] ?? [
        ['text' => 'Gratis ongkir Jabodetabek min. 50 pcs'],
        ['text' => 'Bebas pilih 90+ variasi motif'],
        ['text' => 'Garansi ganti baru jika retak'],
    ];
    $card2BtnText = $data['card2_btn_text'] ?? 'Pesan Eceran';
    $card2BtnUrl = $data['card2_btn_url'] ?? '/katalog';

    // Card 3: Toko Material Bangunan (Kanan)
    $card3Label = $data['card3_label'] ?? 'Toko Material Bangunan';
    $storePrice = $data['store_price'] ?? '15.000';
    $card3Unit = $data['card3_unit'] ?? '/pcs';
    $card3Desc = $data['card3_desc'] ?? 'Harga di pasaran umum/toko retail bisa jauh lebih tinggi karena jalur distribusi perantara berantai.';
    $card3Features = $data['card3_features'] ?? [
        ['text' => 'Harga lewat tangan perantara'],
        ['text' => 'Pilihan motif seringkali terbatas'],
        ['text' => 'Ongkir tambahan seringkali mahal'],
    ];
    $card3BadgeBottom = $data['card3_badge_bottom'] ?? '💡 Anda hemat hingga Rp 5.500/pcs bila beli langsung dari pabrik kami!';

    $rawTheme = $data['bg_theme'] ?? ($data['theme'] ?? 'white');
    $theme = \App\Helpers\BlockTheme::resolve($rawTheme);
@endphp

<section class="py-14 sm:py-20 lg:py-24 relative overflow-hidden font-sans {{ $theme->bgClasses }} transition-colors duration-300">
    <x-blocks._bg-theme :theme="$theme" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-14">
            @if(!empty($badge))
                <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-3.5 {{ $theme->badgeClass }}">
                    {{ $badge }}
                </span>
            @endif
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight leading-tight mb-4 {{ $theme->headingColor }}">
                {{ $title }}
            </h2>
            @if(!empty($subtitle))
                <p class="text-sm sm:text-base leading-relaxed max-w-2xl mx-auto {{ $theme->subColor }}">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        {{-- Benefits Highlight Bar --}}
        @if(!empty($bannerTitle))
        <div class="max-w-4xl mx-auto mb-10 rounded-2xl p-4 sm:p-5 border flex flex-col md:flex-row items-center justify-between gap-4 bg-emerald-50/90 dark:bg-slate-900 border-emerald-200/80 dark:border-slate-800 text-emerald-950 dark:text-emerald-300 shadow-xs">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-emerald-600/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                </div>
                <div>
                    <h4 class="font-extrabold text-sm sm:text-base text-stone-900 dark:text-white">{{ $bannerTitle }}</h4>
                    <p class="text-xs sm:text-sm text-stone-600 dark:text-stone-400">{{ $bannerSubtitle }}</p>
                </div>
            </div>
            @if(!empty($ctaText))
            <a href="{{ $ctaUrl }}" target="_blank" rel="noopener" class="shrink-0 px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-md transition-transform hover:scale-105 active:scale-95">
                {{ $ctaText }}
            </a>
            @endif
        </div>
        @endif

        {{-- 3 Price Comparison Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto items-stretch">
            
            {{-- Card 2: Harga Normal Eceran Pabrik (Left) --}}
            <div class="order-2 md:order-1 rounded-3xl p-6 sm:p-7 border flex flex-col justify-between {{ $theme->cardBg }} shadow-sm">
                <div>
                    <span class="block text-xs font-bold uppercase tracking-wider text-stone-500 dark:text-stone-400 mb-2">{{ $card2Label }}</span>
                    <div class="flex items-baseline gap-1 mb-3">
                        <span class="text-2xl sm:text-3xl font-extrabold {{ $theme->cardTitle }}">Rp {{ $normalPrice }}</span>
                        <span class="text-xs sm:text-sm font-semibold text-stone-400">{{ $card2Unit }}</span>
                    </div>
                    @if(!empty($card2Desc))
                    <p class="text-xs sm:text-sm leading-relaxed mb-6 {{ $theme->cardDesc }}">
                        {{ $card2Desc }}
                    </p>
                    @endif
                    <ul class="space-y-3 text-xs sm:text-sm mb-6 text-stone-700 dark:text-stone-300">
                        @foreach($card2Features as $f)
                            @php $txt = is_array($f) ? ($f['text'] ?? '') : $f; @endphp
                            @if(!empty($txt))
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $txt }}</span>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                @if(!empty($card2BtnText))
                <a href="{{ $card2BtnUrl }}" class="w-full py-2.5 rounded-full text-center font-bold text-xs sm:text-sm border transition-colors border-stone-300 dark:border-stone-700 text-stone-700 dark:text-stone-200 hover:bg-stone-100 dark:hover:bg-neutral-800">
                    {{ $card2BtnText }}
                </a>
                @endif
            </div>

            {{-- Card 1: Grosir Pabrik (Center / Featured) --}}
            <div class="relative order-1 md:order-2 rounded-3xl p-7 sm:p-8 border-2 border-emerald-500 shadow-xl flex flex-col justify-between transition-transform duration-300 md:-translate-y-2 bg-white dark:bg-neutral-900 text-stone-900 dark:text-white">
                @if(!empty($card1Badge))
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-emerald-600 text-white text-xs font-black tracking-wide uppercase shadow-md whitespace-nowrap">
                    {{ $card1Badge }}
                </div>
                @endif
                <div>
                    <span class="block text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-2">{{ $card1Label }}</span>
                    <div class="flex items-baseline gap-2 mb-3">
                        <span class="text-3xl sm:text-4xl font-black text-emerald-600 dark:text-emerald-400">Rp {{ $wholesalePrice }}</span>
                        <span class="text-xs sm:text-sm font-semibold text-stone-400">{{ $card1Unit }}</span>
                        @if(!empty($card1StrikePrice))
                        <span class="text-xs line-through text-stone-400 ml-auto">{{ $card1StrikePrice }}</span>
                        @endif
                    </div>
                    @if(!empty($card1Desc))
                    <p class="text-xs sm:text-sm leading-relaxed mb-6 text-stone-600 dark:text-stone-300">
                        {{ $card1Desc }}
                    </p>
                    @endif
                    <ul class="space-y-3 text-xs sm:text-sm mb-6 text-stone-700 dark:text-stone-300">
                        @foreach($card1Features as $f)
                            @php $txt = is_array($f) ? ($f['text'] ?? '') : $f; @endphp
                            @if(!empty($txt))
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ $txt }}</span>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                @if(!empty($card1BtnText))
                <a href="{{ $card1BtnUrl }}" target="_blank" rel="noopener" class="w-full py-3 rounded-full text-center font-bold text-sm bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg shadow-emerald-600/30 transition-all duration-200">
                    {{ $card1BtnText }}
                </a>
                @endif
            </div>

            {{-- Card 3: Perbandingan Toko Material Bangunan (Right) --}}
            <div class="order-3 rounded-3xl p-6 sm:p-7 border flex flex-col justify-between bg-stone-50 dark:bg-neutral-900/50 border-stone-200 dark:border-neutral-800 text-stone-900 dark:text-white shadow-sm">
                <div>
                    <span class="block text-xs font-bold uppercase tracking-wider text-rose-500 mb-2">{{ $card3Label }}</span>
                    <div class="flex items-baseline gap-1 mb-3">
                        <span class="text-2xl sm:text-3xl font-extrabold text-rose-600 dark:text-rose-400">Rp {{ $storePrice }}</span>
                        <span class="text-xs sm:text-sm font-semibold text-stone-400">{{ $card3Unit }}</span>
                    </div>
                    @if(!empty($card3Desc))
                    <p class="text-xs sm:text-sm leading-relaxed mb-6 text-stone-600 dark:text-stone-400">
                        {{ $card3Desc }}
                    </p>
                    @endif
                    <ul class="space-y-3 text-xs sm:text-sm mb-6 text-stone-600 dark:text-stone-400">
                        @foreach($card3Features as $f)
                            @php $txt = is_array($f) ? ($f['text'] ?? '') : $f; @endphp
                            @if(!empty($txt))
                            <li class="flex items-start gap-2.5">
                                <span class="text-rose-500 font-bold leading-none mt-0.5">✕</span>
                                <span>{{ $txt }}</span>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                @if(!empty($card3BadgeBottom))
                <div class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-center text-xs font-bold text-amber-800 dark:text-amber-400 mt-4 leading-relaxed">
                    {{ $card3BadgeBottom }}
                </div>
                @endif
            </div>

        </div>
    </div>
</section>
