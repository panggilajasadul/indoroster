<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-8 sm:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- ══════════════════════════════════════════════════════════════
             1. HERO SECTION (HIGH CONVERTING HOOK & VALUE STACK)
        ══════════════════════════════════════════════════════════════ --}}
        <div class="relative rounded-3xl overflow-hidden bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-900 dark:text-white p-6 sm:p-10 lg:p-12 shadow-soft-xl mb-12">
            {{-- Decorative Gradient Aura --}}
            <div class="absolute -right-20 -top-20 w-96 h-96 bg-terra-500/10 dark:bg-terra-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-amber-500/10 dark:bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10">
                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}" class="hover:text-terra-500 transition">Beranda</a>
                    <span>/</span>
                    @if($page->parentPage)
                        <a href="/{{ $page->parentPage->slug }}" class="hover:text-terra-500 transition">{{ $page->parentPage->h1 }}</a>
                        <span>/</span>
                    @endif
                    <span class="text-slate-700 dark:text-slate-300 font-medium truncate">{{ $page->h1 }}</span>
                </nav>

                {{-- Badges --}}
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-terra-50 dark:bg-terra-950/60 border border-terra-200 dark:border-terra-800 text-terra-700 dark:text-terra-300 text-xs font-bold uppercase tracking-wider">
                        🏭 Produsen Tangan Pertama Plered
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-xs font-bold uppercase tracking-wider">
                        🚚 Siap Kirim Jabodetabek & Jawa Barat
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 text-xs font-bold uppercase tracking-wider">
                        🛡️ Garansi Ganti Pecah di Lokasi
                    </span>
                </div>

                {{-- Main H1 --}}
                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight text-slate-900 dark:text-white mb-5 max-w-4xl">
                    {{ $page->h1 }}
                </h1>

                {{-- Opening Story Narrative --}}
                <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 mb-8 leading-relaxed max-w-4xl">
                    {{ $page->opening_text }}
                </p>

                {{-- Primary Action Buttons --}}
                <div class="flex flex-wrap items-center gap-3 sm:gap-4 mb-8">
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm sm:text-base shadow-lg shadow-emerald-600/25 transition-all hover:scale-[1.02]">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        <span>Konsultasi & Minta Penawaran via WA</span>
                    </a>
                    <a href="https://drive.google.com/file/d/1wcBxdEv7yiytPlLSVE1ldl1rYpe0MHZZ/view?usp=drive_link" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-terra-600 hover:bg-terra-500 text-white font-bold text-sm shadow-md transition-all hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Unduh Katalog PDF (Google Drive)</span>
                    </a>
                    <a href="{{ route('tools.calculator') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold text-sm transition-all border border-slate-200 dark:border-slate-700">
                        <span>🧮 Hitung Kebutuhan m²</span>
                    </a>
                </div>

                {{-- Key Trust Strip --}}
                <div class="pt-6 border-t border-slate-100 dark:border-slate-800 grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="text-terra-600 text-lg">📐</span>
                        <div>
                            <span class="font-black text-slate-900 dark:text-white block">Siku 90° Presisi</span>
                            <span class="text-slate-400">Cetak Plat Baja</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-600 text-lg">🛡️</span>
                        <div>
                            <span class="font-black text-slate-900 dark:text-white block">Garansi Pecah 100%</span>
                            <span class="text-slate-400">Ganti di Tempat</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-amber-500 text-lg">🚚</span>
                        <div>
                            <span class="font-black text-slate-900 dark:text-white block">Armada Pabrik</span>
                            <span class="text-slate-400">Colt Diesel & Fuso</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-indigo-500 text-lg">🏭</span>
                        <div>
                            <span class="font-black text-slate-900 dark:text-white block">10.000 Pcs/Bulan</span>
                            <span class="text-slate-400">Kapasitas Produksi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             2. DYNAMIC PROBLEM & SOLUTION NARRATIVE
        ══════════════════════════════════════════════════════════════ --}}
        @php
            $problemSection = $sections->firstWhere('section_type', 'problem');
            $solutionSection = $sections->firstWhere('section_type', 'solution');
        @endphp

        @if($problemSection || $solutionSection)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-14">
            @if($problemSection)
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-8 shadow-soft-xs flex flex-col justify-between">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 text-xs font-bold uppercase tracking-wider mb-4">
                        ⚠️ Masalah di Lapangan
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mb-3">
                        {{ $problemSection->heading }}
                    </h2>
                    <div class="prose prose-slate dark:prose-invert max-w-none text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                        {!! $problemSection->content !!}
                    </div>
                </div>
            </div>
            @endif

            @if($solutionSection)
            <div class="bg-gradient-to-br from-emerald-50/40 via-white to-terra-50/30 dark:from-slate-900 dark:via-slate-900 dark:to-slate-800/80 rounded-3xl border border-emerald-200/80 dark:border-slate-800 p-6 sm:p-8 shadow-soft-xs flex flex-col justify-between">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 text-xs font-bold uppercase tracking-wider mb-4">
                        ✨ Solusi Pabrik IndoRoster
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mb-3">
                        {{ $solutionSection->heading }}
                    </h2>
                    <div class="prose prose-slate dark:prose-invert max-w-none text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                        {!! $solutionSection->content !!}
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════
             3. EXPANSIVE PRODUCT SHOWCASE & LIVE MOTIF EXPLORER (BANYAK PRODUK)
        ══════════════════════════════════════════════════════════════ --}}
        <div id="product-showcase" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-10 mb-14 shadow-soft-sm">
            
            {{-- Header & Search/Filter Bar --}}
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8 pb-6 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">Pilihan Lengkap Motif</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight mt-1">
                        Koleksi Motif Roster Unggulan Pabrik IndoRoster
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Pilih motif yang sesuai dengan konsep arsitektur proyek Anda. Klik tombol WA pada motif yang diinginkan untuk tanya stok dan penawaran harga.
                    </p>
                </div>

                {{-- Interactive Controls --}}
                <div class="flex flex-wrap items-center gap-3">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama motif..." class="px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500 focus:outline-none w-48 sm:w-60 shadow-2xs">
                    <select wire:model.live="selectedCategory" class="px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500 focus:outline-none shadow-2xs">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- 1. Featured Spotlight Cards (Motif Terpilih Halaman Ini) --}}
            @if(isset($featuredProducts) && $featuredProducts->isNotEmpty())
            <div class="mb-10">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-4">
                    ⭐ Motif Rekomendasi Utama:
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($featuredProducts as $product)
                        @php
                            $displayMedia = $product->primary_media;
                            $imgUrl = ($displayMedia && $displayMedia->media_type === 'image' && !empty($displayMedia->formatted_url)) 
                                ? $displayMedia->formatted_url 
                                : ($product->primary_image ?: asset('assets/logo_indoroster_no_text.PNG'));
                            $itemWaUrl = "https://wa.me/{$waNumber}?text=" . urlencode("Halo Admin IndoRoster, saya ingin konsultasi & pesan motif: {$product->name} (Halaman: {$page->h1}). Mohon info stok dan total biaya kirim.");
                        @endphp
                        <div class="bg-slate-50 dark:bg-slate-800/70 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 p-4 sm:p-5 flex flex-col sm:flex-row gap-5 items-center hover:border-terra-400 transition-all">
                            {{-- Foto --}}
                            <a href="{{ route('product.detail', $product->slug) }}" class="w-full sm:w-44 h-44 bg-white dark:bg-slate-900 rounded-xl overflow-hidden flex items-center justify-center flex-shrink-0 p-3 border border-slate-200/60 dark:border-slate-700 relative group">
                                <img src="{{ $imgUrl }}" 
                                     alt="Roster {{ $product->name }}" 
                                     class="max-w-full max-h-full object-contain transition-transform duration-500 group-hover:scale-105" 
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='{{ asset('assets/logo_indoroster_no_text.PNG') }}'; this.className='w-16 h-16 object-contain opacity-70';">
                                <span class="absolute top-2 left-2 px-2 py-0.5 rounded bg-slate-900/80 text-white text-[9px] font-bold">
                                    📏 {{ $product->dimensions ?: '20×20×10 cm' }}
                                </span>
                            </a>

                            {{-- Info --}}
                            <div class="flex-1 flex flex-col justify-between w-full">
                                <div>
                                    <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-terra-100 dark:bg-terra-950 text-terra-700 dark:text-terra-300 border border-terra-200 dark:border-terra-800">
                                            {{ $product->category->name ?? 'Roster Minimalis' }}
                                        </span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            Siku 90° Plat
                                        </span>
                                    </div>
                                    <h4 class="font-extrabold text-sm sm:text-base text-slate-900 dark:text-white line-clamp-1 mb-1.5">
                                        {{ $product->name }}
                                    </h4>
                                    {{-- 3 Bahan --}}
                                    <div class="flex items-center gap-1 text-[9px] text-slate-500 dark:text-slate-400 mb-2 flex-wrap">
                                        <span class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">⚪ Dolomit</span>
                                        <span class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">⚫ Abu Pasir</span>
                                        <span class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">🔴 Merah Genteng</span>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-slate-200 dark:border-slate-700">
                                    <div class="flex items-center justify-between gap-2 mb-2.5">
                                        <span class="text-xs text-slate-400 font-medium">Harga Pabrik:</span>
                                        <span class="text-sm font-black text-[#ee4d2d] dark:text-terra-400">
                                            {{ $product->formatted_price_range }}
                                        </span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row items-center gap-2">
                                        <a href="{{ $itemWaUrl }}" target="_blank" rel="noopener noreferrer" class="w-full sm:flex-1 py-2 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold flex items-center justify-center gap-1.5 shadow-xs transition-all whitespace-nowrap">
                                            <svg class="w-4 h-4 fill-current flex-shrink-0" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                            <span>Pesan via WA</span>
                                        </a>
                                        <a href="{{ route('product.detail', $product->slug) }}" class="w-full sm:w-auto py-2 px-3 rounded-lg bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 text-xs font-bold border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition text-center whitespace-nowrap">
                                            Lihat Detail &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- 2. Extended Catalog Grid (Banyak Produk: 12-20+ Motif) --}}
            @if(isset($explorerProducts) && $explorerProducts->isNotEmpty())
            <div>
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-4">
                    🧱 Jelajahi 45+ Motif Roster Lengkap:
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
                    @foreach($explorerProducts as $expProduct)
                        @php
                            $expMedia = $expProduct->primary_media;
                            $expImg = ($expMedia && $expMedia->media_type === 'image' && !empty($expMedia->formatted_url)) 
                                ? $expMedia->formatted_url 
                                : ($expProduct->primary_image ?: asset('assets/logo_indoroster_no_text.PNG'));
                            $expWaUrl = "https://wa.me/{$waNumber}?text=" . urlencode("Halo Admin IndoRoster, saya ingin info motif: {$expProduct->name} (Halaman: {$page->h1}). Mohon info harga grosir dan stok.");
                        @endphp
                        <div class="group p-3 sm:p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/70 dark:border-slate-700/70 hover:border-terra-500 transition-all flex flex-col justify-between">
                            <div>
                                <a href="{{ route('product.detail', $expProduct->slug) }}" class="aspect-square rounded-xl overflow-hidden bg-white dark:bg-slate-900 mb-2.5 flex items-center justify-center p-2 border border-slate-200/50 dark:border-slate-700 relative block">
                                    <img src="{{ $expImg }}" 
                                         alt="{{ $expProduct->name }}" 
                                         class="max-w-full max-h-full object-contain group-hover:scale-105 transition duration-300" 
                                         loading="lazy"
                                         onerror="this.onerror=null; this.src='{{ asset('assets/logo_indoroster_no_text.PNG') }}'; this.className='w-14 h-14 object-contain opacity-60';">
                                    <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded bg-slate-900/80 text-white text-[8px] font-bold">
                                        📏 20×20 cm
                                    </span>
                                </a>
                                <h4 class="text-xs font-bold text-slate-900 dark:text-white line-clamp-1 mb-1">
                                    {{ $expProduct->name }}
                                </h4>
                                <p class="text-xs text-[#ee4d2d] dark:text-terra-400 font-extrabold mb-2">
                                    {{ $expProduct->formatted_price_range }}
                                </p>
                            </div>

                            <a href="{{ $expWaUrl }}" target="_blank" rel="noopener noreferrer" class="w-full py-1.5 px-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-bold flex items-center justify-center gap-1 shadow-2xs transition-all">
                                <svg class="w-3 h-3 fill-current flex-shrink-0" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                <span>Pesan via WA</span>
                            </a>
                        </div>
                    @endforeach
                </div>

                {{-- Tombol Lihat Katalog Lengkap --}}
                <div class="text-center pt-4 border-t border-slate-100 dark:border-slate-800">
                    <a href="{{ route('catalog') }}" class="inline-flex items-center justify-center gap-2.5 px-8 py-3.5 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-slate-100 dark:hover:bg-white text-white dark:text-slate-900 font-extrabold text-sm shadow-md transition-all hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                        <span>Lihat Katalog Lengkap Semua Motif (45+ Pilihan)</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
            @endif

        </div>

        {{-- ══════════════════════════════════════════════════════════════
             4. TRANSPARANSI MATERIAL ASLI (DOLOMIT, PASIR, BUBUK GENTENG)
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-10 mb-14 shadow-soft-xs">
            <div class="max-w-3xl mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">Transparansi Bahan Baku</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1">
                    3 Pilihan Bahan Alami Murni (Bukan Cat Semprot)
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Kami menjaga kejujuran komposisi material. Setiap varian warna roster diproduksi dari pigmen batuan dan mineral alami murni:
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                {{-- 1. Dolomit --}}
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80">
                    <div class="text-3xl mb-3">⚪</div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Dolomit Putih Super</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Terbuat dari 100% batu dolomit padat. Menghasilkan warna putih bersih alami yang tidak mudah menyerap air dan bebas dari pertumbuhan lumut hitam.
                    </p>
                </div>

                {{-- 2. Pasir Abu --}}
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80">
                    <div class="text-3xl mb-3">⚫</div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Pasir Pilihan & Semen</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Campuran pasir ayak berbutir tajam dan semen bermutu tinggi. Sangat kokoh untuk dinding pagar keliling dan dinding struktural luar ruangan.
                    </p>
                </div>

                {{-- 3. Bubuk Genteng Merah --}}
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80">
                    <div class="text-3xl mb-3">🔴</div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">Bubuk Genteng Bakar</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Warna merah terakota asli dari olahan bubuk genteng tanah liat bakar sentra keramik Plered. Menghadirkan nuansa klasik tropis yang sejuk dan adem.
                    </p>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 dark:border-slate-800 text-xs sm:text-sm text-slate-700 dark:text-slate-300 space-y-2">
                <div class="flex items-start gap-2">
                    <span class="text-emerald-600 font-bold">✓</span>
                    <span><strong>Cetak Plat Baja Siku 90°:</strong> Dikerjakan dengan pemadatan manual teliti oleh pengrajin berpengalaman di Plered (bukan mesin hidrolik).</span>
                </div>
                <div class="flex items-start gap-2">
                    <span class="text-emerald-600 font-bold">✓</span>
                    <span><strong>Armada Kirim Sendiri:</strong> Langsung dikirim menggunakan truk pabrik ke Jabodetabek & Jawa Barat dengan garansi ganti pecah 100% di lokasi.</span>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             5. LEAD MAGNET DOWNLOAD KATALOG PDF (GOOGLE DRIVE)
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-gradient-to-r from-terra-600 via-terra-700 to-amber-600 rounded-3xl p-8 sm:p-12 text-white mb-14 shadow-xl relative overflow-hidden">
            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8">
                <div class="max-w-xl text-center lg:text-left">
                    <span class="text-xs font-bold uppercase tracking-wider text-terra-100">Katalog Digital Resmi</span>
                    <h3 class="text-2xl sm:text-4xl font-black mt-1 mb-3">
                        Unduh Katalog Lengkap IndoRoster (PDF)
                    </h3>
                    <p class="text-xs sm:text-sm text-white/90 leading-relaxed">
                        Dapatkan inspirasi 45+ motif roster minimalis, nako anti tampias, geometris, dan floral lengkap dengan ukuran detail dan panduan pola pemasangan dinding.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto flex-shrink-0">
                    <a href="https://drive.google.com/file/d/1wcBxdEv7yiytPlLSVE1ldl1rYpe0MHZZ/view?usp=drive_link" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto px-7 py-4 rounded-xl bg-white text-terra-700 hover:bg-slate-100 font-extrabold text-sm shadow-md transition-all text-center flex items-center justify-center gap-2 hover:scale-[1.02]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Unduh PDF via Google Drive</span>
                    </a>
                    <a href="{{ route('catalog') }}" class="w-full sm:w-auto px-6 py-4 rounded-xl bg-slate-900/40 hover:bg-slate-900/60 text-white font-bold text-sm border border-white/30 transition-all text-center">
                        Lihat Web Katalog &rarr;
                    </a>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             6. FAQ SECTION
        ══════════════════════════════════════════════════════════════ --}}
        @php
            $faqSection = $sections->firstWhere('section_type', 'faq');
        @endphp

        @if($faqSection)
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-10 mb-14 shadow-soft-xs">
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                <span>❓</span>
                <span>{{ $faqSection->heading }}</span>
            </h2>
            <div class="prose prose-slate dark:prose-invert max-w-none text-sm leading-relaxed text-slate-700 dark:text-slate-300">
                {!! $faqSection->content !!}
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════
             7. FINAL HIGH-CONVERTING CLOSING BANNER
        ══════════════════════════════════════════════════════════════ --}}
        <div class="text-center p-8 sm:p-14 rounded-3xl bg-slate-900 text-white border border-slate-800 shadow-2xl relative overflow-hidden">
            <div class="max-w-2xl mx-auto">
                <span class="text-xs font-bold uppercase tracking-wider text-terra-400 mb-2 block">Dukungan Penjualan Langsung Pabrik</span>
                <h2 class="text-2xl sm:text-4xl font-black mb-3">
                    Konsultasikan Kebutuhan Proyek Anda Hari Ini
                </h2>
                <p class="text-xs sm:text-sm text-slate-300 mb-8 leading-relaxed">
                    Dapatkan simulasi jumlah kebutuhan keping per m², cek ketersediaan stok warna, dan penawaran harga terbaik langsung dari pabrik.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2.5 px-8 py-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm sm:text-base shadow-lg shadow-emerald-600/25 transition-all hover:scale-[1.02]">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        <span>Hubungi Sales Proyek via WhatsApp</span>
                    </a>
                    <a href="{{ route('tools.calculator') }}" class="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-sm transition-all border border-slate-700">
                        🧮 Buka Kalkulator Dinding
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
