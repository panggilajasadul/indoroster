@props(['data', 'pageTitle' => null])

@php
    $badge = $data['badge'] ?? 'Harga Langsung Pabrik · Kontraktor · Tukang · Distributor';
    $title = $data['title'] ?? 'Makin Banyak Makin Murah — Harga Langsung Pabrik';
    $subtitle = $data['subtitle'] ?? 'Kontraktor, tukang, distributor, dan pengembang — kapasitas produksi kami 6.000 pcs dalam 6 hari (tergantung motif). Pesan 1.000 pcs ke atas dan dapatkan harga spesial, plus gratis ongkir area Jabodetabek.';
    
    // Tiered Prices
    $wholesalePrice = $data['wholesale_price'] ?? '9.500';
    $normalPrice = $data['normal_price'] ?? '12.000';
    $storePrice = $data['store_price'] ?? '15.000';
    $minFreeOngkir = $data['min_free_ongkir'] ?? '50';
    
    $ctaUrl = $data['cta_url'] ?? 'https://wa.me/6281234567890?text=Halo%20IndoRoster,%20saya%20mau%20tanya%20penawaran%20harga%20grosir%20proyek';
    $ctaText = $data['cta_text'] ?? 'Tanya Harga Grosir via WhatsApp';
    $rawTheme = $data['bg_theme'] ?? ($data['theme'] ?? 'white');
    $theme = \App\Helpers\BlockTheme::resolve($rawTheme);
@endphp

<section class="py-14 sm:py-20 lg:py-24 relative overflow-hidden font-sans {{ $theme->bgClasses }} transition-colors duration-300">
    <x-blocks._bg-theme :theme="$theme" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
            @if(!empty($badge))
                <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-3.5 {{ $theme->badgeClass }}">
                    {{ $badge }}
                </span>
            @endif
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight leading-tight mb-4 {{ $theme->headingColor }}">
                {{ $title }}
            </h2>
            @if(!empty($subtitle))
                <p class="text-sm sm:text-base md:text-lg leading-relaxed {{ $theme->subColor }}">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        {{-- Benefits Highlight Bar --}}
        <div class="max-w-4xl mx-auto mb-10 rounded-2xl p-4 sm:p-5 border flex flex-col md:flex-row items-center justify-between gap-4 bg-emerald-50/80 dark:bg-slate-900 border-emerald-200/70 dark:border-slate-800 text-emerald-900 dark:text-emerald-300">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-emerald-600/30">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-sm sm:text-base">GRATIS ONGKIR area Jabodetabek 🎉</h4>
                    <p class="text-xs sm:text-sm opacity-90 text-slate-600 dark:text-slate-400">Khusus pembelian minimal {{ $minFreeOngkir }} pcs — Jakarta, Bogor, Depok, Tangerang, Bekasi.</p>
                </div>
            </div>
            <a href="{{ $ctaUrl }}" target="_blank" rel="noopener" class="shrink-0 px-5 py-2.5 rounded-full text-xs sm:text-sm font-bold bg-emerald-600 hover:bg-emerald-700 text-white shadow-md transition-transform hover:scale-105 active:scale-95">
                {{ $ctaText }}
            </a>
        </div>

        {{-- 3 Price Comparison Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto items-stretch">
            
            {{-- Card 1: Grosir Pabrik (Featured / Highlighted) --}}
            <div class="relative order-1 md:order-2 rounded-3xl p-7 border-2 border-emerald-500 shadow-2xl flex flex-col justify-between transition-transform duration-300 md:-translate-y-2 bg-white dark:bg-slate-900 text-slate-900 dark:text-white">
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-emerald-600 text-white text-xs font-black tracking-wide uppercase shadow-md">
                    ⭐ Paling Hemat
                </div>
                <div>
                    <span class="block text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 mb-1">Pesanan Proyek / 1.000+ pcs</span>
                    <div class="flex items-baseline gap-2 mb-2">
                        <span class="text-3xl sm:text-4xl font-black text-emerald-600 dark:text-emerald-400">Rp {{ $wholesalePrice }}</span>
                        <span class="text-sm font-semibold text-slate-400">/pcs</span>
                        <span class="text-xs line-through text-slate-400 ml-auto">Rp {{ $normalPrice }}</span>
                    </div>
                    <p class="text-xs sm:text-sm leading-relaxed mb-6 text-slate-600 dark:text-slate-300">
                        Hemat langsung dari produksi pengrajin. Kapasitas suplai terjaga hingga 6.000 pcs dalam 6 hari.
                    </p>
                    <ul class="space-y-2.5 text-xs sm:text-sm mb-6 text-slate-700 dark:text-slate-300">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Stok prioritas & jadwal kirim terjadwal</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Faktur / Kwitansi resmi proyek</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Bantuan hitung m² & rincian pcs</span>
                        </li>
                    </ul>
                </div>
                <a href="{{ $ctaUrl }}" target="_blank" rel="noopener" class="w-full py-3 rounded-full text-center font-bold text-sm bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg shadow-emerald-600/30 transition-all duration-200">
                    Klaim Harga Grosir
                </a>
            </div>

            {{-- Card 2: Harga Normal Eceran Pabrik --}}
            <div class="order-2 md:order-1 rounded-3xl p-6 border flex flex-col justify-between {{ $theme->cardBg }}">
                <div>
                    <span class="block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Harga Pabrik Normal</span>
                    <div class="flex items-baseline gap-1 mb-2">
                        <span class="text-2xl sm:text-3xl font-black {{ $theme->cardTitle }}">Rp {{ $normalPrice }}</span>
                        <span class="text-sm font-semibold text-slate-400">/pcs</span>
                    </div>
                    <p class="text-xs sm:text-sm leading-relaxed mb-6 {{ $theme->cardDesc }}">
                        Cocok untuk renovasi rumah tinggal, pagar, atau fasad pribadi dengan kualitas cetak tumbuk padat presisi.
                    </p>
                    <ul class="space-y-2.5 text-xs sm:text-sm mb-6 text-slate-700 dark:text-slate-300">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Gratis ongkir Jabodetabek min. {{ $minFreeOngkir }} pcs</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Bebas pilih 90+ variasi motif</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Garansi ganti baru jika retak</span>
                        </li>
                    </ul>
                </div>
                <a href="{{ $ctaUrl }}" target="_blank" rel="noopener" class="w-full py-2.5 rounded-full text-center font-bold text-xs sm:text-sm border transition-colors border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800">
                    Pesan Eceran
                </a>
            </div>

            {{-- Card 3: Perbandingan Toko Material Bangunan --}}
            <div class="order-3 rounded-3xl p-6 border opacity-90 flex flex-col justify-between bg-slate-50 dark:bg-slate-900/40 border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white">
                <div>
                    <span class="block text-xs font-bold uppercase tracking-wider text-rose-500 mb-1">Toko Material Bangunan</span>
                    <div class="flex items-baseline gap-1 mb-2">
                        <span class="text-2xl sm:text-3xl font-black text-rose-600 dark:text-rose-400">Rp {{ $storePrice }}</span>
                        <span class="text-sm font-semibold text-slate-400">/pcs</span>
                    </div>
                    <p class="text-xs sm:text-sm leading-relaxed mb-6 text-slate-600 dark:text-slate-400">
                        Harga di pasaran umum/toko retail bisa jauh lebih tinggi karena jalur distribusi perantara berantai.
                    </p>
                    <ul class="space-y-2.5 text-xs sm:text-sm mb-6 text-slate-500">
                        <li class="flex items-center gap-2">
                            <span class="text-rose-500 font-bold">✕</span>
                            <span>Harga lewat tangan perantara</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-rose-500 font-bold">✕</span>
                            <span>Pilihan motif seringkali terbatas</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-rose-500 font-bold">✕</span>
                            <span>Ongkir tambahan seringkali mahal</span>
                        </li>
                    </ul>
                </div>
                <div class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-center text-xs font-bold text-amber-700 dark:text-amber-400">
                    💡 Anda hemat hingga Rp 5.500/pcs bila beli langsung dari pabrik kami!
                </div>
            </div>

        </div>
    </div>
</section>
