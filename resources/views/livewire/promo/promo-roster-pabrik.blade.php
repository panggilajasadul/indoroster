<div class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 selection:bg-terra-500 selection:text-white">

    @php
        $topStrip = $sections['top_strip'] ?? [];
        $hero = $sections['hero'] ?? [];
        $deliveryProof = $sections['delivery_proof'] ?? [];
        $receivedProof = $sections['received_proof'] ?? [];
        $catalogSection = $sections['catalog'] ?? [];
        $gallerySection = $sections['gallery'] ?? [];
        $costComp = $sections['comparison_cost'] ?? [];
        $qualComp = $sections['comparison_quality'] ?? [];
        $shippingCoverage = $sections['shipping_coverage'] ?? [];
        $tiering = $sections['tiering'] ?? [];
        $faqSection = $sections['faqs'] ?? [];
        $ctaBottom = $sections['cta_bottom'] ?? [];

        $defaultSectionOrder = [
            'hero',
            'cost_comparison',
            'quality_education',
            'catalog',
            'delivery_proof',
            'received_proof',
            'gallery',
            'calculator',
            'lead_form',
            'shipping_coverage',
            'tiering',
            'faq',
            'cta_bottom',
        ];
        $sectionOrderRaw = $sections['section_order'] ?? $defaultSectionOrder;
        $sectionOrder = [];
        foreach ($sectionOrderRaw as $sItem) {
            $val = is_array($sItem) ? ($sItem['section'] ?? ($sItem['section_key'] ?? '')) : $sItem;
            if (!empty($val) && in_array($val, $defaultSectionOrder)) {
                $sectionOrder[] = $val;
            }
        }
        if (empty($sectionOrder)) {
            $sectionOrder = $defaultSectionOrder;
        }
    @endphp

    {{-- 1. TOP TRUST ANNOUNCEMENT STRIP --}}
    <div class="bg-gradient-to-r from-terra-700 via-terra-600 to-amber-600 text-white text-xs md:text-sm font-semibold py-2.5 px-4 text-center shadow-md relative z-20">
        <div class="max-w-6xl mx-auto flex items-center justify-center gap-2 md:gap-4 flex-wrap">
            <span class="inline-flex items-center gap-1.5 bg-emerald-500 text-white px-2.5 py-0.5 rounded-full text-[11px] font-black tracking-wide uppercase shadow-xs">
                {{ $topStrip['badge'] ?? '🚚 PROMO GRATIS ONGKIR' }}
            </span>
            <span>{{ $topStrip['text'] ?? 'Armada Langsung ke' }} <strong>{{ $cityName }} & Seluruh Wilayah</strong></span>
            <span class="hidden sm:inline opacity-70">•</span>
            <span class="hidden sm:inline-flex items-center gap-1 font-bold text-amber-200">
                {{ $topStrip['warranty'] ?? '🛡️ Garansi 100% Pecah Ganti Baru di Tempat' }}
            </span>
        </div>
    </div>

    
    @foreach($sectionOrder as $sectionKey)
        @if($sectionKey === 'hero')
            {{-- 2. HERO SECTION (High-Impact & Fast Conversion) --}}
                <section class="relative pt-6 pb-12 md:pt-12 md:pb-18 overflow-hidden bg-gradient-to-b from-terra-50/70 via-white to-slate-50 dark:from-slate-900 dark:via-slate-900/90 dark:to-slate-950 border-b border-slate-200/80 dark:border-slate-800">
                    {{-- Background Glow --}}
                    <div class="absolute top-0 right-1/4 w-96 h-96 bg-terra-500/10 dark:bg-terra-500/5 rounded-full blur-3xl pointer-events-none -z-10"></div>
                    <div class="absolute bottom-10 left-10 w-80 h-80 bg-amber-500/10 dark:bg-amber-500/5 rounded-full blur-3xl pointer-events-none -z-10"></div>
            
                    <div class="max-w-6xl mx-auto px-4 sm:px-6" x-data="{ heroLightbox: false, lightboxUrl: '', lightboxType: 'image' }">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center">
                            
                            {{-- Left: Copywriting --}}
                            <div class="lg:col-span-6 space-y-5 text-center lg:text-left">
                                
                                {{-- Badges Stack --}}
                                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-950/80 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                        {{ $hero['badge_1'] ?? '🚚 Gratis Ongkir Jabodetabek, Jabar & Banten' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-terra-100 dark:bg-terra-950/80 text-terra-700 dark:text-terra-300 border border-terra-200 dark:border-terra-800">
                                        {{ $hero['badge_2'] ?? 'IndoRoster — Pusat Roster Beton Minimalis' }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 dark:bg-amber-950/80 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                        {{ $hero['badge_3'] ?? '⭐ Min. Order 100 Pcs' }}
                                    </span>
                                </div>
            
                                {{-- Main Headline --}}
                                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-slate-900 dark:text-white leading-[1.15] tracking-tight font-display">
                                    {{ $hero['headline'] ?? 'Harga Pabrik Langsung Roster Beton Minimalis — Kirim Cepat ke' }} <span class="text-transparent bg-clip-text bg-gradient-to-r from-terra-600 to-amber-500">{{ $cityName }}</span>
                                </h1>
            
                                {{-- Problem-Solution Subtext --}}
                                <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-relaxed font-normal">
                                    {{ $hero['subtext'] ?? 'Beli di toko material bangunan harganya bisa mencapai Rp 15.000 – Rp 25.000/pcs plus ongkir mahal. Di IndoRoster, Anda dapat harga tangan pertama pabrik langsung mulai Rp 12.500/pcs dengan kualitas padat dan presisi dan Gratis Ongkir armada pabrik!' }}
                                </p>
            
                                {{-- Micro Trust Cards --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1 text-left">
                                    <div class="flex items-start gap-2.5 p-3 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/60 shadow-xs">
                                        <span class="text-emerald-600 dark:text-emerald-400 font-black text-base">✓</span>
                                        <div>
                                            <h4 class="text-xs font-black text-slate-900 dark:text-white">Gratis Ongkir Armada Pabrik</h4>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Siap kirim ke {{ $cityName }} tanpa ribet sewa mobil luar.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-2.5 p-3 rounded-xl bg-white dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/60 shadow-xs">
                                        <span class="text-emerald-600 dark:text-emerald-400 font-black text-base">✓</span>
                                        <div>
                                            <h4 class="text-xs font-black text-slate-900 dark:text-white">Garansi Pecah Ganti 100%</h4>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Pecah saat penurunan langsung diganti keping baru di tempat.</p>
                                        </div>
                                    </div>
                                </div>
            
                                {{-- CTA Buttons --}}
                                <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 justify-center lg:justify-start">
                                    <a href="{{ $this->getWhatsAppUrl() }}" 
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       data-meta-event="Contact"
                                       data-content-name="Hero CTA - Tanya Harga Pabrik"
                                       class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-600 text-white font-black text-base sm:text-lg shadow-lg shadow-emerald-600/30 hover:scale-105 active:scale-95 transition-all group cursor-pointer">
                                        <svg class="w-6 h-6 fill-current animate-bounce group-hover:animate-none" viewBox="0 0 24 24">
                                            <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/>
                                        </svg>
                                        <span>{{ $hero['cta_text'] ?? 'Klaim Promo Ongkir & Harga Pabrik' }}</span>
                                    </a>
            
                                    <a href="#kalkulator-section" class="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-2xl bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold text-sm border border-slate-200 dark:border-slate-700 shadow-xs transition-colors">
                                        <span>🧮 Hitung Kebutuhan (m²)</span>
                                    </a>
                                </div>
            
                                <div class="text-[12px] text-slate-500 dark:text-slate-400 flex items-center justify-center lg:justify-start gap-3 flex-wrap">
                                    <span>⚡ Respon Cepat < 5 Menit</span>
                                    <span>•</span>
                                    <span>🚚 Armada Langsung Pabrik</span>
                                    <span>•</span>
                                    <span>💰 Mulai Rp 12.500/pcs (Hemat vs Toko Material)</span>
                                </div>
            
                            </div>
            
                            {{-- Right: Visual Showcase (Image or Video - Clean, Large & Card Below) --}}
                            <div class="lg:col-span-6 flex flex-col gap-3.5">
                                
                                {{-- 1. Main Media Box (Jauh Lebih Besar di Desktop, Jelas Tanpa Tertutup Card) --}}
                                <div class="relative rounded-3xl overflow-hidden border-2 border-terra-500/40 dark:border-terra-500/30 shadow-2xl bg-slate-950 aspect-[4/3] sm:aspect-[16/10] lg:aspect-[4/3] min-h-[300px] sm:min-h-[400px] lg:min-h-[460px] w-full flex items-center justify-center group cursor-pointer"
                                     @click="@if(!empty($heroVideoUrl)) lightboxUrl = '{{ $heroVideoUrl }}'; lightboxType = 'video'; heroLightbox = true; @elseif(!empty($heroImageUrl)) lightboxUrl = '{{ $heroImageUrl }}'; lightboxType = 'image'; heroLightbox = true; @endif">
                                    
                                    @if(!empty($heroVideoUrl))
                                        <video src="{{ $heroVideoUrl }}" 
                                               autoplay muted loop playsinline 
                                               class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"></video>
                                    @elseif(!empty($heroImageUrl))
                                        <img src="{{ cloudinary_thumb($heroImageUrl, 1000) }}" 
                                             alt="Roster Beton Minimalis IndoRoster" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                             onerror="this.src='{{ asset('assets/logo_indoroster_no_text.PNG') }}'">
                                    @else
                                        <img src="{{ asset('assets/logo_indoroster_no_text.PNG') }}" 
                                             alt="Roster Beton Minimalis IndoRoster" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90">
                                    @endif

                                    {{-- Zoom / Enlarge Button Hint --}}
                                    <div class="absolute top-3 right-3 z-10 opacity-80 group-hover:opacity-100 transition-opacity">
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-extrabold bg-slate-950/80 backdrop-blur-md text-white border border-white/20 shadow-md">
                                            🔍 Klik Perbesar
                                        </span>
                                    </div>
                                </div>

                                {{-- 2. Highlight Card (Foto 2 - Ditaruh Rapi Di Bawah Foto/Video agar Visual Bersih 100%) --}}
                                <div class="p-4 sm:p-5 rounded-3xl bg-slate-900/95 dark:bg-slate-900 border border-slate-800 text-white shadow-xl space-y-2.5">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-xs font-black uppercase tracking-wider text-terra-400">
                                            Pabrik Tangan Pertama
                                        </span>
                                        <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                            {{ $hero['card_badge'] ?? '🚚 Siap Kirim' }}
                                        </span>
                                    </div>
                                    <p class="text-xs sm:text-sm font-bold text-slate-100 leading-snug">
                                        {{ $hero['card_title'] ?? 'Siku 90° Presisi Milimeter — Fasad Rapi, Tukang Pasang Cepat & Hemat Semen' }}
                                    </p>
                                    <div class="flex items-center justify-between text-xs text-slate-300 pt-2 border-t border-white/10">
                                        <span class="font-medium">📍 {{ $cityName }}</span>
                                        <span class="text-amber-400 font-black text-xs sm:text-sm">{{ $hero['card_price'] ?? 'Mulai Rp 12.500/pcs' }}</span>
                                    </div>
                                </div>

                            </div>
            
                        </div>

                        {{-- Lightbox Modal Fullscreen Saat Diklik --}}
                        <div x-show="heroLightbox" 
                             x-cloak 
                             @click.self="heroLightbox = false"
                             @keydown.escape.window="heroLightbox = false"
                             class="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex items-center justify-center p-4 sm:p-8"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95">
                            
                            <button @click="heroLightbox = false" class="absolute top-4 right-4 z-50 text-white bg-slate-800/80 hover:bg-slate-700 w-10 h-10 rounded-full flex items-center justify-center font-bold text-xl border border-white/20 shadow-xl">
                                ✕
                            </button>

                            <div class="relative max-w-5xl w-full max-h-[90vh] flex items-center justify-center rounded-3xl overflow-hidden border border-white/10 shadow-2xl bg-black">
                                <template x-if="lightboxType === 'video'">
                                    <video :src="lightboxUrl" controls autoplay class="max-h-[85vh] w-auto max-w-full rounded-2xl"></video>
                                </template>
                                <template x-if="lightboxType === 'image'">
                                    <img :src="lightboxUrl" alt="Roster IndoRoster Preview" class="max-h-[85vh] w-auto max-w-full object-contain rounded-2xl">
                                </template>
                            </div>
                        </div>

                    </div>
                </section>
        @elseif($sectionKey === 'cost_comparison')
            {{-- 3. PERBANDINGAN HARGA: TOKO MATERIAL VS PABRIK INDOROSTER --}}
                <section class="py-12 md:py-18 bg-gradient-to-b from-amber-50/60 via-white to-slate-50 dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 border-b border-slate-200/80 dark:border-slate-800">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6">
                        
                        <div class="text-center max-w-3xl mx-auto mb-10 space-y-3">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-amber-100 dark:bg-amber-950/80 text-amber-900 dark:text-amber-300 uppercase tracking-wider">
                                💰 Perbandingan Biaya Nyata
                            </span>
                            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 dark:text-white font-display">
                                {{ $costComp['title'] ?? 'Kenapa Beli Roster di Toko Material Bisa 2x Lipat Lebih Mahal?' }}
                            </h2>
                            <p class="text-xs sm:text-base text-slate-600 dark:text-slate-300">
                                {{ $costComp['subtitle'] ?? 'Toko bangunan biasa mengambil dari perantara dan menaikkan harga hingga Rp 15.000 – Rp 25.000 / pcs. Beli langsung dari IndoRoster — Pusat Roster Beton Minimalis, Anda dapat harga asli pabrik tangan pertama plus garansi aman.' }}
                            </p>
                        </div>
            
                        {{-- Comparison Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 items-stretch mb-10">
                            
                            {{-- Kiri: Toko Material Bangunan --}}
                            <div class="p-6 sm:p-8 rounded-3xl bg-white dark:bg-slate-800/90 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col justify-between space-y-6">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-black uppercase text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/50 px-3 py-1 rounded-full border border-rose-200 dark:border-rose-900">
                                            Jalur Toko Material Tradisional
                                        </span>
                                        <span class="text-xs font-bold text-slate-400">Perantara Berlapis</span>
                                    </div>
            
                                    <div>
                                        <h3 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white">Beli di Toko Bangunan Biasa</h3>
                                        <div class="mt-2 text-2xl sm:text-3xl font-black text-rose-600 dark:text-rose-400">
                                            Rp 15.000 – Rp 25.000 <span class="text-xs font-semibold text-slate-500">/ pcs</span>
                                        </div>
                                    </div>
            
                                    <ul class="space-y-3 text-xs sm:text-sm text-slate-600 dark:text-slate-300 pt-2 border-t border-slate-100 dark:border-slate-700">
                                        <li class="flex items-start gap-2.5">
                                            <span class="text-rose-500 font-bold shrink-0">✕</span>
                                            <span><strong>Harga Tinggi:</strong> Kena mark-up keuntungan agen, distributor, dan pemilik toko retail.</span>
                                        </li>
                                        <li class="flex items-start gap-2.5">
                                            <span class="text-rose-500 font-bold shrink-0">✕</span>
                                            <span><strong>Ongkos Kirim Tambahan:</strong> Masih harus sewa pick-up toko sendiri atau bayar ongkir mahal per ritasi.</span>
                                        </li>
                                        <li class="flex items-start gap-2.5">
                                            <span class="text-rose-500 font-bold shrink-0">✕</span>
                                            <span><strong>Stok Terbatas & Motif Campur:</strong> Sulit mencari 1 motif seragam dalam jumlah ratusan pcs.</span>
                                        </li>
                                        <li class="flex items-start gap-2.5">
                                            <span class="text-rose-500 font-bold shrink-0">✕</span>
                                            <span><strong>Tanpa Garansi Pecah:</strong> Keping yang pecah atau sompal saat pengantaran toko tidak bisa diganti.</span>
                                        </li>
                                    </ul>
                                </div>
            
                                <div class="p-3.5 rounded-2xl bg-rose-50/80 dark:bg-rose-950/30 text-rose-800 dark:text-rose-300 text-xs font-semibold text-center border border-rose-200/60 dark:border-rose-900/40">
                                    Total biaya membengkak hingga jutaan rupiah untuk kebutuhan rumah/ruko.
                                </div>
                            </div>
            
                            {{-- Kanan: Pabrik Tangan Pertama IndoRoster --}}
                            <div class="p-6 sm:p-8 rounded-3xl bg-gradient-to-b from-emerald-50/80 via-white to-emerald-50/30 dark:from-slate-800 dark:via-slate-800 dark:to-emerald-950/20 border-2 border-emerald-500 shadow-2xl flex flex-col justify-between space-y-6 relative overflow-hidden">
                                <div class="absolute top-0 right-0 bg-emerald-600 text-white text-[10px] font-black uppercase px-4 py-1.5 rounded-bl-2xl tracking-wider shadow-sm">
                                    ⭐ Tangan Pertama Pabrik
                                </div>
            
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-black uppercase text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-950 px-3 py-1 rounded-full border border-emerald-300 dark:border-emerald-800">
                                            Pusat Roster Beton Minimalis
                                        </span>
                                    </div>
            
                                    <div>
                                        <h3 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white">Pesan Langsung di IndoRoster</h3>
                                        <div class="mt-2 text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400">
                                            {{ $costComp['pabrik_price_start'] ?? 'Mulai Rp 12.500 / pcs' }}
                                        </div>
                                    </div>
            
                                    <ul class="space-y-3 text-xs sm:text-sm text-slate-700 dark:text-slate-200 pt-2 border-t border-emerald-200 dark:border-slate-700">
                                        <li class="flex items-start gap-2.5">
                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold shrink-0">✓</span>
                                            <span><strong>Harga Asli Pabrik:</strong> Tanpa mark-up perantara toko retail, hemat hingga 40% langsung dari pabrik IndoRoster.</span>
                                        </li>
                                        <li class="flex items-start gap-2.5">
                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold shrink-0">✓</span>
                                            <span><strong>GRATIS ONGKIR / Subsidi Armada:</strong> Siap kirim langsung ke <strong>Jabodetabek, Jawa Barat, dan Banten</strong>.</span>
                                        </li>
                                        <li class="flex items-start gap-2.5">
                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold shrink-0">✓</span>
                                            <span><strong>Kualitas Padat & Presisi Siku 90°:</strong> Ukuran seragam presisi milimeter, dinding tegak lurus rata & hemat semen.</span>
                                        </li>
                                        <li class="flex items-start gap-2.5">
                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold shrink-0">✓</span>
                                            <span><strong>Garansi Pecah Ganti 100%:</strong> Supir armada langsung mengganti keping baru di tempat saat bongkar muat.</span>
                                        </li>
                                    </ul>
                                </div>
            
                                <a href="{{ $this->getWhatsAppUrl() }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   data-meta-event="Contact"
                                   data-content-name="Comparison Section CTA - Klaim Harga Pabrik"
                                   class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-6 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-black shadow-lg shadow-emerald-600/30 hover:scale-105 active:scale-95 transition-all cursor-pointer">
                                    <span>💬 Hubungi Pabrik & Klaim Promo Gratis Ongkir</span> &rarr;
                                </a>
                            </div>
            
                        </div>
            
                        {{-- Kalkulasi Nyata Penghematan Anggaran --}}
                        @php
                            $simulations = $costComp['simulations'] ?? [
                                [
                                    'label' => 'Kebutuhan 300 Pcs (Fasad Kecil)',
                                    'badge' => '',
                                    'store_price' => 'Toko Material: Rp 5.400.000',
                                    'factory_price' => 'Pabrik: Rp 3.750.000',
                                    'savings_label' => 'HEMAT Rp 1.650.000',
                                ],
                                [
                                    'label' => 'Kebutuhan 500 Pcs (Fasad & Pagar)',
                                    'badge' => 'Paling Umum',
                                    'store_price' => 'Toko Material: Rp 9.500.000',
                                    'factory_price' => 'Pabrik: Rp 6.250.000',
                                    'savings_label' => 'HEMAT Rp 3.250.000',
                                ],
                                [
                                    'label' => 'Kebutuhan 1.000 Pcs (Proyek / Cafe)',
                                    'badge' => '',
                                    'store_price' => 'Toko Material: Rp 19.000.000',
                                    'factory_price' => 'Pabrik: Rp 12.500.000',
                                    'savings_label' => 'HEMAT Rp 6.500.000+',
                                ],
                            ];
                        @endphp
                        <div class="p-6 sm:p-8 rounded-3xl bg-slate-900 text-white border border-white/10 shadow-2xl">
                            <div class="text-center max-w-2xl mx-auto mb-6 space-y-1">
                                <span class="text-xs font-bold text-amber-400 uppercase tracking-widest">Simulasi Penghematan Riil</span>
                                <h3 class="text-lg sm:text-2xl font-black text-white">Berapa Banyak yang Anda Hemat dengan Pesan di Pabrik?</h3>
                            </div>
            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center">
                                @foreach($simulations as $sim)
                                    @php
                                        $isFeaturedSim = !empty($sim['badge']);
                                    @endphp
                                    <div class="p-4 rounded-2xl {{ $isFeaturedSim ? 'bg-gradient-to-b from-terra-950/80 to-slate-800 border border-terra-500/40' : 'bg-slate-800/80 border border-slate-700/80' }} space-y-2 relative">
                                        @if($isFeaturedSim)
                                            <span class="absolute -top-2.5 left-1/2 -translate-x-1/2 bg-terra-500 text-white text-[9px] font-black uppercase px-2 py-0.5 rounded-full">
                                                {{ $sim['badge'] }}
                                            </span>
                                        @endif
                                        <div class="text-xs {{ $isFeaturedSim ? 'text-slate-300' : 'text-slate-400' }} font-bold">{{ $sim['label'] }}</div>
                                        <div class="text-xs line-through text-rose-400">{{ $sim['store_price'] }}</div>
                                        <div class="text-base sm:text-lg font-black text-emerald-400">{{ $sim['factory_price'] }}</div>
                                        <div class="text-[11px] font-black text-amber-300 bg-amber-950/60 py-1 px-2 rounded-lg border border-amber-500/30">
                                            {{ $sim['savings_label'] }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
            
                    </div>
                </section>
        @elseif($sectionKey === 'quality_education')
            {{-- 4. PERBANDINGAN MUTU MATERIAL --}}
                @php
                    $badItems = $qualComp['bad_items'] ?? [
                        ['title' => 'Sudut Miring & Tidak Siku', 'desc' => 'Dinding bergelombang saat disusun tinggi, tukang lambat kerja.'],
                        ['title' => 'Banyak Campuran Tanah & Pasir Rapuh', 'desc' => 'Pori kasar menyerap air hujan, cepat berlumut hitam dan gompal.'],
                        ['title' => 'Boros Semen Acian', 'desc' => 'Harus menambal selisih ketebalan, biaya semen membengkak 30%.'],
                        ['title' => 'Tanpa Garansi Pecah', 'desc' => 'Barang rusak di jalan ditanggung pembeli sendiri.'],
                    ];
                    $goodItems = $qualComp['good_items'] ?? [
                        ['title' => 'Siku Presisi 90° Plat Baja', 'desc' => 'Ukuran milimeter konsisten, dinding tegak lurus rata & hemat semen nat.'],
                        ['title' => 'Pasir Abu Batu Murni', 'desc' => 'Kepadatan tinggi tanpa rongga rapuh, tahan cuaca ekstrem & anti lumut.'],
                        ['title' => 'Standar Uji Kuat Tekan', 'desc' => 'Aman untuk dinding partisi tinggi, secondary skin lantai 2, dan pagar luar.'],
                        ['title' => 'Garansi 100% Pecah Ganti Baru', 'desc' => 'Supir armada langsung ganti keping baru di tempat saat penurunan keping.'],
                    ];
                @endphp
                <section class="py-12 md:py-18 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6">
                        
                        <div class="text-center max-w-3xl mx-auto mb-10 space-y-2">
                            <span class="text-xs font-black tracking-widest text-terra-600 dark:text-terra-400 uppercase">
                                Edukasi Kualitas Roster
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white font-display">
                                {{ $qualComp['title'] ?? 'Mengapa 90% Kontraktor & Arsitek Memilih Roster Beton Minimalis IndoRoster?' }}
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300">
                                {{ $qualComp['subtitle'] ?? 'Beli roster abal-abal terlihat murah beberapa ratus rupiah di awal, tapi rugi jutaan rupiah karena dinding miring dan boros semen nat.' }}
                            </p>
                        </div>
            
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 items-stretch">
                            
                            {{-- Kiri: Risiko Pasaran (Pain Point) --}}
                            <div class="p-6 sm:p-7 rounded-3xl bg-rose-50/60 dark:bg-rose-950/20 border border-rose-200 dark:border-rose-900/50 flex flex-col justify-between">
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-rose-100 dark:bg-rose-900/60 flex items-center justify-center text-rose-600 dark:text-rose-400 font-black text-lg">✕</div>
                                        <div>
                                            <h3 class="text-base sm:text-lg font-black text-rose-950 dark:text-rose-200">Risiko Roster Pasaran Abal-Abal</h3>
                                            <p class="text-xs text-rose-700/80 dark:text-rose-300/80">Cetakan kayu / cetak basah manual biasa</p>
                                        </div>
                                    </div>
            
                                    <ul class="space-y-2.5 text-xs sm:text-sm text-slate-700 dark:text-slate-300 pt-1">
                                        @foreach($badItems as $bad)
                                            <li class="flex items-start gap-2.5">
                                                <span class="text-rose-500 font-bold shrink-0">✕</span>
                                                <span><strong>{{ $bad['title'] }}:</strong> {{ $bad['desc'] }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
            
                                <div class="mt-5 pt-3 border-t border-rose-200/60 dark:border-rose-900/40 text-[11px] font-semibold text-rose-700 dark:text-rose-400 text-center">
                                    ⚠️ Murah di awal, namun sangat boros di ongkos tukang & perbaikan.
                                </div>
                            </div>
            
                            {{-- Kanan: Standar IndoRoster (Solution) --}}
                            <div class="p-6 sm:p-7 rounded-3xl bg-emerald-50/60 dark:bg-emerald-950/20 border-2 border-emerald-500/60 dark:border-emerald-500/40 shadow-xl flex flex-col justify-between relative overflow-hidden">
                                <div class="absolute top-0 right-0 bg-emerald-600 text-white text-[10px] font-black uppercase px-3 py-1 rounded-bl-xl tracking-wider">
                                    Standar IndoRoster
                                </div>
            
                                <div class="space-y-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-emerald-500 flex items-center justify-center text-white font-black text-lg shadow-md shadow-emerald-500/30">✓</div>
                                        <div>
                                            <h3 class="text-base sm:text-lg font-black text-emerald-950 dark:text-emerald-200">Keunggulan Roster Pabrik IndoRoster</h3>
                                            <p class="text-xs text-emerald-700 dark:text-emerald-300">Kualitas padat dan presisi</p>
                                        </div>
                                    </div>
            
                                    <ul class="space-y-2.5 text-xs sm:text-sm text-slate-700 dark:text-slate-200 pt-1">
                                        @foreach($goodItems as $good)
                                            <li class="flex items-start gap-2.5">
                                                <span class="text-emerald-600 dark:text-emerald-400 font-bold shrink-0">✓</span>
                                                <span><strong>{{ $good['title'] }}:</strong> {{ $good['desc'] }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
            
                                <div class="mt-5 pt-3 border-t border-emerald-200 dark:border-slate-700 text-[11px] font-semibold text-emerald-700 dark:text-emerald-300 text-center">
                                    🛡️ Dinding kokoh, rapi, garansi 100% ganti di tempat tanpa sompal.
                                </div>
                            </div>
            
                        </div>
            
                    </div>
                </section>
        @elseif($sectionKey === 'catalog')
            {{-- 5. TRANSPARANSI HARGA & REKOMENDASI PRODUK BEST SELLER (Foto 4 - Clean & Dynamic) --}}
                <section class="py-12 md:py-18 bg-slate-50 dark:bg-slate-950 border-b border-slate-200/80 dark:border-slate-800">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6">
                        
                        <div class="text-center max-w-2xl mx-auto mb-10 space-y-2">
                            <span class="text-xs font-black tracking-widest text-terra-600 dark:text-terra-400 uppercase">
                                Katalog & Transparansi Harga
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white font-display">
                                {{ $catalogSection['title'] ?? 'Pilihan Motif Roster Terlaris (20×20 cm)' }}
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300">
                                {{ $catalogSection['subtitle'] ?? 'Standar modular 20×20×10 cm (25 pcs/m²). Tersedia warna Abu Natural (Abu Batu Murni), Putih Bersih (Dolomit), dan Merah Terakota.' }}
                            </p>
                        </div>
            
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                            @foreach($featuredProducts as $product)
                                @php
                                    $primaryMedia = $product->media->first();
                                    $primaryImg = $product->primary_image ?: ($primaryMedia?->media_url ?? '');
                                    $priceFormatted = $product->price > 0 ? 'Rp ' . number_format($product->price, 0, ',', '.') : ($catalogSection['price_label'] ?? 'Mulai Rp 12.500');
                                @endphp
                                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-4 flex flex-col justify-between hover:shadow-xl hover:border-terra-400 transition-all group shadow-sm">
                                    
                                    <div>
                                        {{-- Product Image Thumbnail --}}
                                        <div class="aspect-square rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 mb-3.5 border border-slate-200 dark:border-slate-700/60 relative flex items-center justify-center">
                                            @if(!empty($primaryImg))
                                                <img src="{{ cloudinary_thumb($primaryImg, 400) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                     loading="lazy"
                                                     onerror="this.src='{{ asset('assets/logo_indoroster_no_text.PNG') }}'">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-800 dark:to-slate-900 flex flex-col items-center justify-center p-3 text-center">
                                                    <span class="text-2xl mb-1">🧱</span>
                                                    <span class="text-[11px] font-bold text-slate-700 dark:text-slate-300 line-clamp-1">{{ $product->name }}</span>
                                                </div>
                                            @endif
            
                                            <span class="absolute top-2.5 left-2.5 bg-slate-900/80 backdrop-blur-xs text-white text-[9px] font-black px-2.5 py-0.5 rounded-full uppercase tracking-wider shadow-sm">
                                                20×20 cm
                                            </span>
                                        </div>
            
                                        <h3 class="text-xs sm:text-sm font-black text-slate-900 dark:text-white line-clamp-2 group-hover:text-terra-600 transition-colors leading-snug">
                                            {{ $product->name }}
                                        </h3>
                                        <div class="mt-2 flex items-baseline justify-between gap-1">
                                            <span class="text-sm sm:text-base font-black text-terra-600 dark:text-terra-400">{{ $priceFormatted }}</span>
                                            <span class="text-[10px] text-slate-400 font-semibold">/ pcs</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-4 mt-0.5">
                                            25 pcs/m² • Tebal 10 cm • Padat
                                        </p>
                                    </div>
            
                                    {{-- Action Button --}}
                                    <a href="{{ $this->getWhatsAppUrl($product->name) }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       data-meta-event="Contact"
                                       data-content-name="Motif Card - {{ $product->name }}"
                                       class="w-full inline-flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-sm hover:scale-[1.02] active:scale-[0.98] transition-all">
                                        <span>💬 Pesan Motif Ini</span>
                                    </a>
            
                                </div>
                            @endforeach
                        </div>
            
                        <div class="text-center mt-10">
                            <a href="{{ $this->getWhatsAppUrl('Lihat Seluruh 50+ Motif Roster') }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               data-meta-event="Contact"
                               data-content-name="Katalog - Minta PDF Motif Lengkap"
                               class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold text-xs sm:text-sm border border-slate-300 dark:border-slate-700 shadow-sm transition-colors">
                                <span>📑 Minta Katalog PDF Lengkap (50+ Motif) via WhatsApp</span> &rarr;
                            </a>
                        </div>
            
                    </div>
                </section>
        @elseif($sectionKey === 'delivery_proof')
            {{-- 6. SECTION BUKTI PENGIRIMAN ARMADA PABRIK (Foto 3 - Bersih Tanpa Overlay Gelap) --}}
                @php
                    $deliveryItems = $deliveryProof['items'] ?? [];
                @endphp
                @if(!empty($deliveryItems))
                <section class="py-12 md:py-18 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6">
                        
                        <div class="text-center max-w-3xl mx-auto mb-10 space-y-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 uppercase tracking-wider">
                                {{ $deliveryProof['badge'] ?? '🚚 Real Armada Pengiriman' }}
                            </span>
                            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 dark:text-white font-display">
                                {{ $deliveryProof['title'] ?? 'Bukti Muatan Armada Pengiriman Langsung Pabrik' }}
                            </h2>
                            <p class="text-xs sm:text-base text-slate-600 dark:text-slate-300">
                                {{ $deliveryProof['subtitle'] ?? 'Dokumentasi nyata persiapan muat keping roster & keberangkatan armada pabrik IndoRoster setiap hari menuju alamat proyek Anda.' }}
                            </p>
                        </div>
            
                        {{-- 3 Kolom ke Samping, Sisanya ke Bawah --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @foreach($deliveryItems as $item)
                                @php
                                    $itemImg = !empty($item['image_upload']) ? asset('storage/'.$item['image_upload']) : ($item['image_url'] ?? '');
                                    $itemVideo = !empty($item['video_upload']) ? asset('storage/'.$item['video_upload']) : ($item['video_url'] ?? '');
                                @endphp
                                <div class="bg-white dark:bg-slate-850 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-lg flex flex-col justify-between group hover:border-emerald-500 transition-all">
                                    
                                    {{-- Image / Video Box (Clean & Bright - NO DARK OVERLAY) --}}
                                    <div class="relative aspect-4/3 bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                        @if(!empty($itemVideo))
                                            <video src="{{ $itemVideo }}" 
                                                   autoplay muted loop playsinline 
                                                   class="w-full h-full object-cover"></video>
                                        @elseif(!empty($itemImg))
                                            <img src="{{ cloudinary_thumb($itemImg, 600) }}" 
                                                 alt="{{ $item['title'] ?? 'Bukti Pengiriman Roster' }}" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                 loading="lazy"
                                                 onerror="this.src='{{ asset('assets/logo_indoroster_no_text.PNG') }}'">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-emerald-100 to-slate-200 dark:from-emerald-950 dark:to-slate-800 flex items-center justify-center text-4xl">
                                                🚚
                                            </div>
                                        @endif
            
                                        {{-- Clean Status Chip --}}
                                        <div class="absolute top-3 right-3 z-10">
                                            <span class="bg-emerald-600 text-white text-[10px] font-black uppercase px-3 py-1 rounded-full shadow-md">
                                                {{ $item['status_badge'] ?? '🚚 Siap Antar' }}
                                            </span>
                                        </div>
                                    </div>
            
                                    {{-- Details Below Image --}}
                                    <div class="p-5 sm:p-6 space-y-3 bg-white dark:bg-slate-900">
                                        <div class="flex items-center gap-1.5 text-xs font-bold text-amber-600 dark:text-amber-400">
                                            <span>📍</span>
                                            <span>Tujuan: {{ $item['destination'] ?? $cityName }}</span>
                                        </div>
                                        <h4 class="text-base font-black text-slate-900 dark:text-white leading-snug">
                                            {{ $item['title'] ?? 'Muatan Armada Pabrik' }}
                                        </h4>
                                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                                            {{ $item['caption'] ?? 'Keping roster disusun rapi di palet kayu terlindungi terpal aman menuju lokasi proyek.' }}
                                        </p>
                                    </div>
            
                                </div>
                            @endforeach
                        </div>
            
                        <div class="text-center mt-10">
                            <a href="{{ $this->getWhatsAppUrl('Cek Jadwal Armada Terdekat') }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               data-meta-event="Contact"
                               data-content-name="Delivery CTA - Cek Jadwal Armada"
                               class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-emerald-600/30 hover:scale-105 active:scale-95 transition-all">
                                <span>📅 Cek Jadwal Keberangkatan Armada ke {{ $cityName }}</span> &rarr;
                            </a>
                        </div>
            
                    </div>
                </section>
                @endif
        @elseif($sectionKey === 'received_proof')
            {{-- 7. SECTION BUKTI BARANG TIBA DI PEMBELI & UNLOADING (Foto 2 - Bersih Tanpa Overlay Gelap) --}}
                @php
                    $receivedItems = $receivedProof['items'] ?? [];
                @endphp
                @if(!empty($receivedItems))
                <section class="py-12 md:py-18 bg-slate-50 dark:bg-slate-950 border-b border-slate-200/80 dark:border-slate-800">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6">
                        
                        <div class="text-center max-w-3xl mx-auto mb-10 space-y-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-terra-100 dark:bg-terra-950/80 text-terra-700 dark:text-terra-300 uppercase tracking-wider">
                                {{ $receivedProof['badge'] ?? '📦 Serah Terima & Unloading' }}
                            </span>
                            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 dark:text-white font-display">
                                {{ $receivedProof['title'] ?? 'Bukti Barang Tiba & Penurunan di Lokasi Pembeli' }}
                            </h2>
                            <p class="text-xs sm:text-base text-slate-600 dark:text-slate-300">
                                {{ $receivedProof['subtitle'] ?? 'Kepuasan konsumen saat keping roster diterima di depan gerbang proyek tanpa risiko pecah (garansi ganti baru di tempat).' }}
                            </p>
                        </div>
            
                        {{-- 3 Kolom ke Samping, Sisanya ke Bawah --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @foreach($receivedItems as $item)
                                @php
                                    $itemImg = !empty($item['image_upload']) ? asset('storage/'.$item['image_upload']) : ($item['image_url'] ?? '');
                                    $itemVideo = !empty($item['video_upload']) ? asset('storage/'.$item['video_upload']) : ($item['video_url'] ?? '');
                                @endphp
                                <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-lg flex flex-col justify-between group hover:border-terra-400 transition-all">
                                    
                                    {{-- Image / Video Box (Clean & Bright - NO DARK OVERLAY) --}}
                                    <div class="relative aspect-4/3 bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                        @if(!empty($itemVideo))
                                            <video src="{{ $itemVideo }}" 
                                                   autoplay muted loop playsinline 
                                                   class="w-full h-full object-cover"></video>
                                        @elseif(!empty($itemImg))
                                            <img src="{{ cloudinary_thumb($itemImg, 600) }}" 
                                                 alt="{{ $item['customer_name'] ?? 'Bukti Barang Tiba' }}" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                 loading="lazy"
                                                 onerror="this.src='{{ asset('assets/logo_indoroster_no_text.PNG') }}'">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-terra-100 to-slate-200 dark:from-terra-950 dark:to-slate-800 flex items-center justify-center text-4xl">
                                                📦
                                            </div>
                                        @endif
            
                                        {{-- Clean Verification Badge --}}
                                        <div class="absolute top-3 right-3 z-10">
                                            <span class="bg-amber-500 text-slate-950 text-[10px] font-black px-3 py-1 rounded-full shadow-md">
                                                {{ $item['verification_badge'] ?? '⭐ Terverifikasi Tiba 100% Utuh' }}
                                            </span>
                                        </div>
                                    </div>
            
                                    {{-- Testimony Box Below Image --}}
                                    <div class="p-5 sm:p-6 space-y-4 bg-white dark:bg-slate-900">
                                        <div>
                                            <h4 class="text-sm sm:text-base font-black text-slate-900 dark:text-white">
                                                {{ $item['customer_name'] ?? 'Konsumen IndoRoster' }}
                                            </h4>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400 font-semibold mt-0.5">
                                                📍 {{ $item['location'] ?? $cityName }} • {{ $item['order_volume'] ?? 'Roster Minimalis' }}
                                            </p>
                                        </div>
            
                                        <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/60 text-xs text-slate-700 dark:text-slate-200 italic leading-relaxed">
                                            "{{ $item['testimony_quote'] ?? 'Barang sudah tiba dan diturunkan rapi di lokasi. Presisi siku plat bajanya sangat bagus, tidak ada yang sompal.' }}"
                                        </div>
            
                                        <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 pt-1 border-t border-slate-100 dark:border-slate-800">
                                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">✓ Garansi Ganti di Tempat</span>
                                            <span class="text-amber-400 font-bold tracking-widest">★★★★★</span>
                                        </div>
                                    </div>
            
                                </div>
                            @endforeach
                        </div>
            
                    </div>
                </section>
                @endif
        @elseif($sectionKey === 'gallery')
            {{-- 8. GALERI FOTO PEMASANGAN NYATA (Foto 1 - 3 Kolom ke Samping, Sisanya ke Bawah) --}}
                @php
                    $customGalleryItems = $gallerySection['items'] ?? [];
                @endphp
                @if(!empty($customGalleryItems) || $installationGalleries->isNotEmpty())
                <section class="py-12 md:py-18 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6">
                        
                        <div class="text-center max-w-3xl mx-auto mb-10 space-y-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-terra-100 dark:bg-terra-950/80 text-terra-700 dark:text-terra-300 uppercase tracking-wider">
                                📸 Galeri Dokumentasi Nyata
                            </span>
                            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 dark:text-white font-display">
                                {{ $gallerySection['title'] ?? 'Inspirasi Hasil Pemasangan Roster di Lapangan' }}
                            </h2>
                            <p class="text-xs sm:text-base text-slate-600 dark:text-slate-300">
                                {{ $gallerySection['subtitle'] ?? 'Dokumentasi nyata hasil pasang fasad rumah tinggal, pagar villa, sekat cafe, dan ventilasi gedung di Jabodetabek & Jawa Barat.' }}
                            </p>
                        </div>
            
                        {{-- 3 Kolom ke Samping, Sisanya ke Bawah --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            {{-- 1. Custom Items from Repeater if configured --}}
                            @if(!empty($customGalleryItems))
                                @foreach($customGalleryItems as $item)
                                    @php
                                        $itemImg = !empty($item['image_upload']) ? asset('storage/'.$item['image_upload']) : ($item['image_url'] ?? '');
                                        $itemVideo = !empty($item['video_upload']) ? asset('storage/'.$item['video_upload']) : ($item['video_url'] ?? '');
                                    @endphp
                                    <div class="group rounded-3xl overflow-hidden bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-800 shadow-lg flex flex-col justify-between hover:border-terra-400 transition-all">
                                        <div class="relative aspect-4/3 bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                            @if(!empty($itemVideo))
                                                <video src="{{ $itemVideo }}" autoplay muted loop playsinline class="w-full h-full object-cover"></video>
                                            @elseif(!empty($itemImg))
                                                <img src="{{ cloudinary_thumb($itemImg, 600) }}" 
                                                     alt="{{ $item['title'] ?? 'Dokumentasi Pemasangan Roster' }}" 
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                     loading="lazy"
                                                     onerror="this.src='{{ asset('assets/logo_indoroster_no_text.PNG') }}'">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-terra-100 to-slate-200 dark:from-terra-900 dark:to-slate-800 flex items-center justify-center p-3 text-center">
                                                    <span class="text-3xl">🏛️</span>
                                                </div>
                                            @endif

                                            <div class="absolute top-3 left-3 z-10">
                                                <span class="bg-slate-900/80 backdrop-blur-xs text-amber-300 text-[10px] font-black uppercase px-2.5 py-1 rounded-full shadow-md">
                                                    📍 {{ $item['location'] ?? $cityName }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="p-5 bg-white dark:bg-slate-900 space-y-1">
                                            <h4 class="text-sm sm:text-base font-black text-slate-900 dark:text-white line-clamp-2 group-hover:text-terra-600 transition-colors leading-snug">
                                                {{ $item['title'] ?? 'Inspirasi Pemasangan Roster' }}
                                            </h4>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1">
                                                {{ $item['caption'] ?? 'Standar Presisi IndoRoster • Terpasang Rapi' }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            {{-- 2. Database Galleries (selected or active) --}}
                            @if(empty($customGalleryItems) || !empty($gallerySection['gallery_ids']))
                                @foreach($installationGalleries as $gallery)
                                    @php
                                        $primaryImg = $gallery->primary_image ?: ($gallery->media->first()?->formatted_url ?: ($gallery->product?->primary_image ?? ''));
                                    @endphp
                                    <div class="group rounded-3xl overflow-hidden bg-white dark:bg-slate-850 border border-slate-200 dark:border-slate-800 shadow-lg flex flex-col justify-between hover:border-terra-400 transition-all">
                                        
                                        {{-- Photo View (Clean & Bright - NO HEAVY OVERLAY) --}}
                                        <div class="relative aspect-4/3 bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                            @if(!empty($primaryImg))
                                                <img src="{{ cloudinary_thumb($primaryImg, 600) }}" 
                                                     alt="{{ $gallery->title }}" 
                                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                     loading="lazy"
                                                     onerror="this.src='{{ asset('assets/logo_indoroster_no_text.PNG') }}'">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-terra-100 to-slate-200 dark:from-terra-900 dark:to-slate-800 flex items-center justify-center p-3 text-center">
                                                    <span class="text-3xl">🏛️</span>
                                                </div>
                                            @endif
                                
                                            {{-- Clean Location Badge --}}
                                            <div class="absolute top-3 left-3 z-10">
                                                <span class="bg-slate-900/80 backdrop-blur-xs text-amber-300 text-[10px] font-black uppercase px-2.5 py-1 rounded-full shadow-md">
                                                    📍 {{ $gallery->location ?: $cityName }}
                                                </span>
                                            </div>
                                        </div>
                                
                                        {{-- Caption Info Below --}}
                                        <div class="p-5 bg-white dark:bg-slate-900 space-y-1">
                                            <h4 class="text-sm sm:text-base font-black text-slate-900 dark:text-white line-clamp-2 group-hover:text-terra-600 transition-colors leading-snug">
                                                {{ $gallery->title }}
                                            </h4>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-1">
                                                {{ $gallery->description ?: 'Standar Presisi IndoRoster • Terpasang Rapi' }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
            
                        <div class="text-center mt-10">
                            <a href="{{ $this->getWhatsAppUrl('Konsultasi Model Pemasangan') }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               data-meta-event="Contact"
                               data-content-name="Gallery CTA - Konsultasi Desain Pasang"
                               class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-terra-600 dark:text-terra-400 hover:text-terra-700 underline underline-offset-4">
                                <span>💡 Ingin konsultasi pola motif yang cocok untuk fasad Anda? Chat Admin WA &rarr;</span>
                            </a>
                        </div>
            
                    </div>
                </section>
                @endif
        @elseif($sectionKey === 'calculator')
            {{-- 9. KALKULATOR KEBUTUHAN INSTAN (Direct to WhatsApp Precision) --}}
                <section id="kalkulator-section" class="py-12 md:py-18 bg-gradient-to-b from-slate-900 to-slate-950 text-white relative overflow-hidden">
                    <div class="max-w-4xl mx-auto px-4 sm:px-6 relative z-10">
                        
                        <div class="text-center max-w-2xl mx-auto mb-8 space-y-2">
                            <span class="text-xs font-black tracking-widest text-terra-400 uppercase">
                                Kalkulator Kebutuhan Instan
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-black text-white font-display">
                                Hitung Jumlah Keping Roster Dinding Anda
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-300">
                                Masukkan panjang & tinggi dinding dan ukuran roster. Sistem kami menghitung kebutuhan keping secara instan & akurat.
                            </p>
                        </div>
            
                        <div class="bg-slate-800/95 backdrop-blur-md rounded-3xl border border-white/10 p-5 sm:p-8 shadow-2xl space-y-6">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                
                                {{-- Input Panjang --}}
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                                        Panjang Dinding (m)
                                    </label>
                                    <div class="relative">
                                        <input type="number" 
                                               wire:model.live.debounce.300ms="wallLength" 
                                               step="0.5" 
                                               min="0.1" 
                                               max="100" 
                                               class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-base font-bold focus:ring-2 focus:ring-terra-500 focus:outline-none">
                                        <span class="absolute right-4 top-3.5 text-xs text-slate-400 font-semibold">Meter</span>
                                    </div>
                                </div>
            
                                {{-- Input Tinggi --}}
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                                        Tinggi Dinding (m)
                                    </label>
                                    <div class="relative">
                                        <input type="number" 
                                               wire:model.live.debounce.300ms="wallHeight" 
                                               step="0.5" 
                                               min="0.1" 
                                               max="20" 
                                               class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-base font-bold focus:ring-2 focus:ring-terra-500 focus:outline-none">
                                        <span class="absolute right-4 top-3.5 text-xs text-slate-400 font-semibold">Meter</span>
                                    </div>
                                </div>
            
                                {{-- Cadangan Potongan --}}
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                                        Cadangan Potong Tukang
                                    </label>
                                    <select wire:model.live="wasteMargin" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm font-bold focus:ring-2 focus:ring-terra-500 focus:outline-none">
                                        <option value="0">0% (Pas Sesuai Luas)</option>
                                        <option value="5">5% (Sangat Dianjurkan)</option>
                                        <option value="10">10% (Banyak Sudut Potongan)</option>
                                    </select>
                                </div>
            
                            </div>
            
                            {{-- Input Data Konsultasi (Nama & Lokasi Kirim Spesifik) --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-slate-700/80">
                                
                                {{-- Input Nama --}}
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                                        Nama Anda / Proyek (Opsional)
                                    </label>
                                    <input type="text" 
                                            wire:model.live.debounce.300ms="customerName" 
                                            placeholder="Contoh: Bpk. Hendra / Bu Rini" 
                                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm font-semibold focus:ring-2 focus:ring-terra-500 focus:outline-none placeholder:text-slate-500">
                                </div>
            
                                {{-- Input Kota / Alamat Kirim --}}
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                                        Kota / Kecamatan Kirim
                                    </label>
                                    <input type="text" 
                                            wire:model.live.debounce.300ms="customerAddress" 
                                            placeholder="Contoh: Tambun, Bekasi / Buahbatu, Bandung" 
                                            class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm font-semibold focus:ring-2 focus:ring-terra-500 focus:outline-none placeholder:text-slate-500">
                                </div>
            
                                {{-- Pilihan Ukuran --}}
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                                        Pilih Ukuran Roster
                                    </label>
                                    <select wire:model.live="selectedSize" class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm font-semibold focus:ring-2 focus:ring-terra-500 focus:outline-none">
                                        <option value="20x20x10">20 x 20 x 10 cm (Default • 25 pcs/m²)</option>
                                        <option value="30x15x10">30 x 15 x 10 cm (23 pcs/m²)</option>
                                        <option value="25x15x10">25 x 15 x 10 cm (27 pcs/m²)</option>
                                        <option value="20x10x10">20 x 10 x 10 cm (50 pcs/m²)</option>
                                    </select>
                                </div>
            
                            </div>
            
                            {{-- Output Calculation Box --}}
                            <div class="p-5 sm:p-6 rounded-2xl bg-gradient-to-r from-terra-950/90 via-slate-900 to-amber-950/90 border border-terra-500/40 flex flex-col sm:flex-row items-center justify-between gap-5 text-center sm:text-left">
                                
                                <div class="space-y-1">
                                    <div class="text-xs text-terra-300 font-bold uppercase tracking-wider">
                                        Luas Dinding: <strong>{{ $this->wallArea }} m²</strong> ({{ $this->wallLength }}m × {{ $this->wallHeight }}m • {{ $this->sizeLabel }})
                                    </div>
                                    <div class="text-3xl sm:text-4xl font-black text-white font-display">
                                        Estimasi: <span class="text-transparent bg-clip-text bg-gradient-to-r from-terra-400 to-amber-400">{{ $this->estimatedPcs }} Pcs</span>
                                    </div>
                                    <div class="text-[11px] text-slate-400">
                                        (Ukuran: {{ $this->sizeLabel }} • Termasuk cadangan potong {{ $this->wasteMargin }}% • Lokasi: <strong class="text-slate-200">{{ $this->customerAddress ? $this->customerAddress : 'Tulis di WA' }}</strong>)
                                    </div>
                                </div>
            
                                {{-- Button: Kirim Hasil Langsung ke WhatsApp --}}
                                <a href="{{ $this->getWhatsAppUrl(null, $this->estimatedPcs, true) }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   data-meta-event="Contact"
                                   data-content-name="Kalkulator CTA - Kirim Estimasi {{ $this->estimatedPcs }} pcs"
                                   class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-sm shadow-lg shadow-emerald-600/40 hover:scale-105 active:scale-95 transition-all shrink-0 cursor-pointer">
                                    <span>💬 Kirim Estimasi {{ $this->estimatedPcs }} Pcs ke Sales WA</span> &rarr;
                                </a>
            
                            </div>
            
                        </div>
            
                    </div>
                </section>
        @elseif($sectionKey === 'lead_form')
            {{-- 9. FORMULIR KONSULTASI PABRIK & KLAIM ONGKIR (High Conversion & High EMQ 9.5) --}}
            <section id="form-konsultasi-section" class="py-12 md:py-18 bg-gradient-to-b from-slate-900 via-slate-950 to-slate-900 text-white border-b border-slate-800 relative overflow-hidden">
                <!-- Background glow -->
                <div class="absolute -top-24 right-10 w-96 h-96 bg-terra-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 left-10 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="max-w-4xl mx-auto px-4 sm:px-6 relative z-10">
                    <div class="bg-slate-800/90 dark:bg-slate-900/95 border border-slate-700/80 rounded-3xl p-6 sm:p-10 shadow-2xl backdrop-blur-md">
                        
                        <div class="text-center max-w-2xl mx-auto mb-8 space-y-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 uppercase tracking-wide">
                                💬 Formulir Konsultasi Resmi Pabrik
                            </span>
                            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-white font-display">
                                Minta Pricelist & Cek Ongkir ke <span class="text-transparent bg-clip-text bg-gradient-to-r from-terra-400 to-amber-400">{{ $cityName }}</span>
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                                Isi data singkat di bawah ini. Tim teknis & sales pabrik akan langsung mengirimkan katalog motif lengkap, hitungan kebutuhan, dan konfirmasi jadwal armada gratis ongkir via WhatsApp.
                            </p>
                        </div>

                        <form wire:submit.prevent="submitLeadForm" class="space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                                        Nama Lengkap / Panggilan <span class="text-terra-400">*</span>
                                    </label>
                                    <input type="text" 
                                           wire:model="lead_name" 
                                           required 
                                           placeholder="Contoh: Pak Hendra / Ibu Maya" 
                                           class="w-full h-12 px-4 text-sm bg-slate-900/90 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-terra-500 focus:border-terra-500 transition-all font-medium">
                                    @error('lead_name') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                                        Nomor WhatsApp Aktif <span class="text-terra-400">*</span>
                                    </label>
                                    <input type="tel" 
                                           wire:model="lead_phone" 
                                           required 
                                           placeholder="Contoh: 081234567890" 
                                           class="w-full h-12 px-4 text-sm bg-slate-900/90 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-terra-500 focus:border-terra-500 transition-all font-medium">
                                    @error('lead_phone') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                                        Kota / Lokasi Pengiriman
                                    </label>
                                    <input type="text" 
                                           wire:model="lead_city" 
                                           placeholder="Contoh: {{ $cityName }}" 
                                           class="w-full h-12 px-4 text-sm bg-slate-900/90 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-terra-500 focus:border-terra-500 transition-all font-medium">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                                        Pilihan Motif (Opsional)
                                    </label>
                                    <input type="text" 
                                           wire:model="lead_motif" 
                                           placeholder="Contoh: Nako Sipit / MMC / Bebas" 
                                           class="w-full h-12 px-4 text-sm bg-slate-900/90 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-terra-500 focus:border-terra-500 transition-all font-medium">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1.5">
                                        Estimasi Jumlah (Pcs)
                                    </label>
                                    <input type="text" 
                                           wire:model="lead_qty" 
                                           placeholder="Contoh: 200 pcs / Luas 15 m²" 
                                           class="w-full h-12 px-4 text-sm bg-slate-900/90 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:ring-2 focus:ring-terra-500 focus:border-terra-500 transition-all font-medium">
                                </div>
                            </div>

                            <div class="pt-3 space-y-3">
                                <button type="submit" 
                                        wire:loading.attr="disabled"
                                        class="w-full h-14 rounded-2xl bg-gradient-to-r from-emerald-600 via-emerald-500 to-emerald-600 hover:from-emerald-500 hover:to-emerald-500 text-white font-black text-base sm:text-lg shadow-xl shadow-emerald-600/30 flex items-center justify-center gap-3 hover:scale-[1.01] active:scale-[0.99] transition-all cursor-pointer">
                                    <svg class="w-6 h-6 fill-current animate-bounce" viewBox="0 0 24 24">
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/>
                                    </svg>
                                    <span wire:loading.remove wire:target="submitLeadForm">Kirim & Lanjut Chat WhatsApp Resmi ➔</span>
                                    <span wire:loading wire:target="submitLeadForm">Menyiapkan Penawaran WhatsApp...</span>
                                </button>

                                <div class="flex items-center justify-center gap-4 text-xs text-slate-400 flex-wrap pt-1">
                                    <span>🛡️ Garansi 100% Pecah Ganti Baru</span>
                                    <span>•</span>
                                    <span>🚚 Armada Langsung Pabrik Plered</span>
                                    <span>•</span>
                                    <span>⚡ Respon Sales < 5 Menit</span>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </section>
        @elseif($sectionKey === 'shipping_coverage')
            {{-- 10. TRANSPARANSI PENGIRIMAN & ESTIMASI ONGKIR ARMADA PABRIK --}}
                @php
                    $regions = $shippingCoverage['regions'] ?? [
                        [
                            'region_name' => 'Jabodetabek',
                            'sub_label' => 'Wilayah Utama',
                            'eta_badge' => '1–2 Hari Sampai',
                            'promo_badge' => 'Gratis Ongkir',
                            'coverage_cities' => 'Jakarta (Pusat, Selatan, Timur, Barat, Utara), Bogor, Depok, Tangerang Raya, Bekasi',
                            'features' => "✓ GRATIS ONGKIR (Min. volume order)\n✓ Pengiriman setiap hari kerja\n✓ Supir bantu penurunan keping di lokasi",
                        ],
                        [
                            'region_name' => 'Seluruh Area Jawa Barat',
                            'sub_label' => 'Armada Khusus Pabrik',
                            'eta_badge' => 'Same Day / 1 Hari',
                            'promo_badge' => 'Gratis Ongkir',
                            'coverage_cities' => 'Bandung Raya, Karawang, Purwakarta, Subang, Cianjur, Sukabumi, Cirebon, Sumedang, Garut, Tasikmalaya',
                            'features' => "✓ GRATIS ONGKIR radius terdekat pabrik\n✓ Jalur tol langsung tanpa macet\n✓ Pengiriman jumlah kecil hingga skala tronton",
                        ],
                        [
                            'region_name' => 'Provinsi Banten',
                            'sub_label' => 'Jalur Barat',
                            'eta_badge' => '1–2 Hari Sampai',
                            'promo_badge' => 'Gratis Ongkir',
                            'coverage_cities' => 'Serang, Cilegon, Pandeglang, Rangkasbitung Lebak, Tangerang Kota & Tangerang Selatan',
                            'features' => "✓ GRATIS ONGKIR armada rutin jalur Banten\n✓ Packing palet kayu aman terlindungi terpal\n✓ Garansi tukar baru jika ada keping retak",
                        ],
                    ];
                @endphp
                <section class="py-12 md:py-18 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6">
                        
                        <div class="text-center max-w-3xl mx-auto mb-10 space-y-3">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 uppercase tracking-wider">
                                {{ $shippingCoverage['badge'] ?? '🚚 Jangkauan Kirim & Promo Ongkir' }}
                            </span>
                            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 dark:text-white font-display">
                                {{ $shippingCoverage['title'] ?? 'Gratis Ongkir Armada Pabrik ke Wilayah Anda' }}
                            </h2>
                            <p class="text-xs sm:text-base text-slate-600 dark:text-slate-300">
                                {{ $shippingCoverage['subtitle'] ?? 'Armada mobil pick-up, truk engkel, hingga truk Colt Diesel siap antar langsung dari pabrik IndoRoster ke depan gerbang proyek Anda.' }}
                            </p>
                        </div>
            
                        {{-- Regional Highlight Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                            @foreach($regions as $region)
                                @php
                                    $featureLines = is_array($region['features']) ? $region['features'] : explode("\n", trim($region['features']));
                                @endphp
                                <div class="p-6 rounded-3xl bg-gradient-to-b from-emerald-50/70 to-white dark:from-slate-800 dark:to-slate-800/60 border-2 border-emerald-500/80 shadow-lg space-y-4 relative overflow-hidden flex flex-col justify-between">
                                    @if(!empty($region['promo_badge']))
                                        <div class="absolute top-0 right-0 bg-emerald-600 text-white text-[10px] font-black uppercase px-3 py-1 rounded-bl-xl tracking-wider">
                                            {{ $region['promo_badge'] }}
                                        </div>
                                    @endif
                                    <div class="space-y-4">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-black uppercase text-emerald-700 dark:text-emerald-400">{{ $region['sub_label'] ?? 'Wilayah Layanan' }}</span>
                                            <span class="text-[11px] bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 px-2.5 py-0.5 rounded-full font-bold">{{ $region['eta_badge'] ?? '1–2 Hari' }}</span>
                                        </div>
                                        <div>
                                            <h4 class="text-base sm:text-lg font-black text-slate-900 dark:text-white">{{ $region['region_name'] }}</h4>
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $region['coverage_cities'] }}</p>
                                        </div>
                                        <ul class="text-xs text-slate-700 dark:text-slate-300 space-y-2 pt-2 border-t border-emerald-200/60 dark:border-slate-700">
                                            @foreach($featureLines as $fLine)
                                                @if(!empty(trim($fLine)))
                                                    <li>{{ $fLine }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
            
                        {{-- Banner Bawah Pengiriman --}}
                        <div class="p-5 sm:p-6 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
                            <div class="space-y-1">
                                <h5 class="text-sm font-black text-slate-900 dark:text-white">Lokasi Anda di Luar Pulau Jawa atau Wilayah Lain?</h5>
                                <p class="text-xs text-slate-600 dark:text-slate-400">Kami bekerja sama dengan ekspedisi kargo darat & laut ke seluruh Indonesia dengan tarif pabrik termurah.</p>
                            </div>
                            <a href="{{ $this->getWhatsAppUrl('Cek Ongkir Luar Kota / Antar Pulau') }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               data-meta-event="Contact"
                               data-content-name="Shipping CTA - Cek Ongkir Kota Lain"
                               class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-xs font-bold shrink-0 transition-colors cursor-pointer">
                                <span>💬 Cek Jadwal Kirim ke {{ $cityName }}</span> &rarr;
                            </a>
                        </div>
            
                    </div>
                </section>
        @elseif($sectionKey === 'tiering')
            {{-- 11. TIERING SKALA PEMESANAN --}}
                @php
                    $tiers = $tiering['tiers'] ?? [
                        [
                            'audience_tag' => 'Rumah Tinggal',
                            'package_name' => 'Paket Renovasi & Sekat',
                            'volume_range' => '100 – 300 Pcs',
                            'badge_highlight' => '',
                            'is_featured' => false,
                            'button_text' => 'Pesan Paket Ini (WA)',
                            'description' => 'Untuk partisi ruang tamu, pagar depan mini, ventilasi kamar mandi, atau sekat dapur.',
                            'features' => "✓ Bebas campur motif best seller\n✓ Pengiriman mobil pick-up pabrik\n✓ Garansi 100% ganti pecah di tempat",
                        ],
                        [
                            'audience_tag' => 'Villa & Fasad',
                            'package_name' => 'Paket Fasad & Pagar',
                            'volume_range' => '300 – 1.000 Pcs',
                            'badge_highlight' => 'Paling Favorit',
                            'is_featured' => true,
                            'button_text' => 'Pesan Paket Ini (WA)',
                            'description' => 'Pilihan paling populer untuk fasad 2 lantai, dinding secondary skin, dan pagar keliling hunian.',
                            'features' => "✓ Gratis Ongkir area Jabodetabek & Jabar\n✓ Pengiriman truk engkel pabrik\n✓ Konsultasi hitung kebutuhan gratis",
                        ],
                        [
                            'audience_tag' => 'Kontraktor & B2B',
                            'package_name' => 'Paket Proyek & Grosir',
                            'volume_range' => '1.000+ Pcs',
                            'badge_highlight' => '',
                            'is_featured' => false,
                            'button_text' => 'Minta Penawaran B2B (WA)',
                            'description' => 'Untuk developer perumahan, kontraktor gedung, arsitek lanskap, dan distributor toko material.',
                            'features' => "✓ Harga Spesial Kontrak Proyek\n✓ Suplai bertahap sesuai progres (JIT)\n✓ Pengiriman Truk CDD / Tronton",
                        ],
                    ];
                @endphp
                <section class="py-12 md:py-18 bg-white dark:bg-slate-900 border-b border-slate-200/80 dark:border-slate-800">
                    <div class="max-w-6xl mx-auto px-4 sm:px-6">
                        
                        <div class="text-center max-w-2xl mx-auto mb-10 space-y-2">
                            <span class="text-xs font-black tracking-widest text-terra-600 dark:text-terra-400 uppercase">
                                {{ $tiering['badge'] ?? 'Pilihan Paket Volume' }}
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white font-display">
                                {{ $tiering['title'] ?? 'Paket Pemesanan Roster Sesuai Skala Proyek' }}
                            </h2>
                        </div>
            
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($tiers as $tier)
                                @php
                                    $isFeaturedTier = !empty($tier['is_featured']);
                                    $tierFeatures = is_array($tier['features']) ? $tier['features'] : explode("\n", trim($tier['features']));
                                @endphp
                                <div class="p-6 rounded-3xl {{ $isFeaturedTier ? 'bg-gradient-to-b from-terra-50/80 to-white dark:from-slate-800 dark:to-slate-800/80 border-2 border-terra-500 shadow-xl' : 'bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700' }} flex flex-col justify-between space-y-5 relative overflow-hidden">
                                    @if(!empty($tier['badge_highlight']))
                                        <div class="absolute top-0 right-0 bg-terra-600 text-white text-[9px] font-black uppercase px-3 py-1 rounded-bl-xl tracking-wider">
                                            {{ $tier['badge_highlight'] }}
                                        </div>
                                    @endif
            
                                    <div class="space-y-3">
                                        <span class="text-xs font-bold {{ $isFeaturedTier ? 'text-terra-600' : 'text-slate-500' }} uppercase tracking-wider">{{ $tier['audience_tag'] }}</span>
                                        <h3 class="text-lg font-black text-slate-900 dark:text-white">{{ $tier['package_name'] }}</h3>
                                        <div class="text-2xl font-black text-terra-600">{{ $tier['volume_range'] }}</div>
                                        <p class="text-xs {{ $isFeaturedTier ? 'text-slate-600 dark:text-slate-300' : 'text-slate-600 dark:text-slate-400' }}">
                                            {{ $tier['description'] }}
                                        </p>
                                        <ul class="text-xs {{ $isFeaturedTier ? 'text-slate-700 dark:text-slate-200' : 'text-slate-700 dark:text-slate-300' }} space-y-2 pt-2 border-t {{ $isFeaturedTier ? 'border-terra-200 dark:border-slate-700' : 'border-slate-200 dark:border-slate-700' }}">
                                            @foreach($tierFeatures as $tFeature)
                                                @if(!empty(trim($tFeature)))
                                                    <li>{{ $tFeature }}</li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
            
                                    <a href="{{ $this->getWhatsAppUrl($tier['package_name']) }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       data-meta-event="Contact"
                                       data-content-name="Tiering - {{ $tier['package_name'] }}"
                                       class="w-full inline-flex items-center justify-center py-2.5 px-4 rounded-xl {{ $isFeaturedTier ? 'bg-terra-600 hover:bg-terra-500 text-white font-black shadow-md shadow-terra-600/30' : 'bg-slate-900 hover:bg-slate-800 dark:bg-slate-700 dark:hover:bg-slate-600 text-white font-bold' }} text-xs transition-all cursor-pointer">
                                        {{ $tier['button_text'] ?? 'Pesan Paket Ini (WA)' }}
                                    </a>
                                </div>
                            @endforeach
                        </div>
            
                    </div>
                </section>
        @elseif($sectionKey === 'faq')
            {{-- 12. FAQ (TANYA JAWAB PRAKTIS) --}}
                @php
                    $faqItems = $faqSection['items'] ?? [
                        [
                            'q' => 'Bagaimana sistem pengiriman dan promo gratis ongkirnya?',
                            'a' => 'Pengiriman dilakukan langsung dari armada pabrik IndoRoster ke seluruh wilayah ' . $cityName . ' dan sekitarnya sampai ke titik lokasi Anda.',
                        ],
                        [
                            'q' => 'Berapa minimal order roster untuk dapat harga pabrik?',
                            'a' => 'Minimal pemesanan promo harga pabrik mulai dari 100 pcs (bisa campur motif 20x20 cm).',
                        ],
                        [
                            'q' => 'Bagaimana jika ada keping yang pecah saat perjalanan?',
                            'a' => 'Kami memberikan Garansi 100% Pecah Ganti Baru di Tempat. Supir armada pabrik kami akan langsung mengganti keping yang rusak saat proses penurunan barang.',
                        ],
                        [
                            'q' => 'Berapa lama estimasi barang sampai setelah pemesanan?',
                            'a' => 'Untuk area ' . $cityName . ', pengiriman rata-rata 1–2 hari kerja setelah konfirmasi pesanan.',
                        ]
                    ];
                @endphp
                <section class="py-12 md:py-18 bg-slate-50 dark:bg-slate-950 border-b border-slate-200/80 dark:border-slate-800">
                    <div class="max-w-4xl mx-auto px-4 sm:px-6">
                        
                        <div class="text-center max-w-2xl mx-auto mb-10 space-y-2">
                            <span class="text-xs font-black tracking-widest text-terra-600 dark:text-terra-400 uppercase">
                                Pertanyaan Umum
                            </span>
                            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white font-display">
                                Tanya Jawab Pemesanan Roster Pabrik
                            </h2>
                        </div>
            
                        <div class="space-y-4" x-data="{ activeFaq: null }">
                            @foreach($faqItems as $idx => $faq)
                                <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 overflow-hidden shadow-xs">
                                    <button type="button" 
                                            @click="activeFaq = (activeFaq === {{ $idx }} ? null : {{ $idx }})"
                                            class="w-full py-4 px-6 text-left flex items-center justify-between gap-4 font-bold text-slate-900 dark:text-white text-sm sm:text-base hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                        <span>{{ $faq['q'] }}</span>
                                        <span class="text-terra-600 text-lg transition-transform duration-200" :class="activeFaq === {{ $idx }} ? 'rotate-180' : ''">&darr;</span>
                                    </button>
                                    <div x-show="activeFaq === {{ $idx }}" x-collapse class="px-6 pb-4 pt-1 text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed border-t border-slate-100 dark:border-slate-800">
                                        {{ $faq['a'] }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
            
                    </div>
                </section>
        @elseif($sectionKey === 'cta_bottom')
            {{-- 13. BOTTOM FINAL CTA BANNER --}}
                <section class="py-14 md:py-20 bg-gradient-to-br from-terra-900 via-slate-950 to-slate-900 text-white text-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-terra-500/20 via-transparent to-transparent pointer-events-none"></div>
            
                    <div class="max-w-3xl mx-auto px-4 sm:px-6 relative z-10 space-y-6">
                        <span class="inline-flex items-center gap-1.5 px-4 py-1 rounded-full text-xs font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 uppercase tracking-wider">
                            ⚡ Respon Cepat < 5 Menit Langsung dari Sales Pabrik
                        </span>
            
                        <h2 class="text-2xl sm:text-4xl md:text-5xl font-black tracking-tight font-display text-white">
                            {{ $ctaBottom['title'] ?? 'Siap Wujudkan Dinding & Fasad Mewah Hemat Biaya?' }}
                        </h2>
            
                        <p class="text-xs sm:text-base text-slate-300 max-w-2xl mx-auto leading-relaxed">
                            {{ $ctaBottom['subtitle'] ?? 'Dapatkan penawaran harga tangan pertama IndoRoster — Pusat Roster Beton Minimalis + promo gratis ongkir sekarang juga.' }}
                        </p>
            
                        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-4">
                            <a href="{{ $this->getWhatsAppUrl() }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               data-meta-event="Contact"
                               data-content-name="Bottom Final CTA - Ambil Promo"
                               class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-base sm:text-lg shadow-2xl shadow-emerald-600/50 hover:scale-105 active:scale-95 transition-all cursor-pointer">
                                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/>
                                </svg>
                                <span>{{ $ctaBottom['button_text'] ?? '💬 Chat Sales Pabrik & Ambil Promo Gratis Ongkir' }}</span>
                            </a>
                        </div>
            
                        <p class="text-[11px] text-slate-400">
                            🛡️ Transaksi Aman • Garansi 100% Pecah Ganti Baru di Tempat • Pelayanan Ramah
                        </p>
                    </div>
                </section>
        @endif
    @endforeach

    {{-- FLOATING WHATSAPP CTA (Sticky Mobile Bottom & Desktop Float) --}}
    <div class="md:hidden fixed bottom-0 left-0 right-0 z-50 bg-slate-900/95 backdrop-blur-md border-t border-slate-700/80 p-3 shadow-2xl">
        <div class="flex items-center justify-between gap-3">
            <div class="leading-tight">
                <span class="text-[10px] text-amber-300 font-bold uppercase">Promo Pabrik Tangan Pertama</span>
                <div class="text-xs font-black text-white">Mulai Rp 12.500/pcs</div>
            </div>
            <a href="{{ $this->getWhatsAppUrl() }}"
               target="_blank"
               rel="noopener noreferrer"
               data-meta-event="Contact"
               data-content-name="Mobile Sticky Floating CTA"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-xs shadow-lg shadow-emerald-600/40 shrink-0 cursor-pointer">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.099.824z"/>
                </svg>
                <span>Chat Sales WA</span>
            </a>
        </div>
    </div>

    {{-- Desktop Floating Button --}}
    <div class="hidden md:block fixed bottom-6 right-6 z-50">
        <a href="{{ $this->getWhatsAppUrl() }}"
           target="_blank"
           rel="noopener noreferrer"
           data-meta-event="Contact"
           data-content-name="Desktop Floating CTA"
           class="inline-flex items-center gap-3 px-6 py-3.5 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white font-black text-sm shadow-2xl shadow-emerald-600/50 hover:scale-105 active:scale-95 transition-all border-2 border-white/20 cursor-pointer group">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-300 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
            </span>
            <span>💬 Chat Sales / Minta Pricelist WA</span>
        </a>
    </div>

    @script
    <script>
        $wire.on('lead-submitted-open-wa', (event) => {
            const data = event[0] || event;
            try {
                localStorage.setItem('indoroster_lead_user', JSON.stringify({
                    name: data.name,
                    phone: data.phone,
                    city: data.city
                }));
            } catch(e) {}
            if (data.waUrl) {
                window.open(data.waUrl, '_blank');
            }
        });
    </script>
    @endscript

</div>

