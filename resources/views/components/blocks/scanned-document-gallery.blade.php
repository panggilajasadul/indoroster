@props(['data'])

@php
    $badge = $data['badge'] ?? 'BUKTI FISIK & DOKUMENTASI PROYEK NYATA';
    $title = $data['title'] ?? 'Galeri Foto Scan Dokumen & Bukti Transaksi Asli';
    $subtitle = $data['subtitle'] ?? 'Dokumentasi otentik lembar fisik surat jalan armada, kwitansi bertanda tangan, surat penawaran, dan hasil uji laboratorium dari pesanan proyek pelanggan kami.';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'slate');

    $rawWa = \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
    $waNumClean = preg_replace('/[^0-9]/', '', $rawWa);
    if (str_starts_with($waNumClean, '0')) {
        $waNumClean = '62' . substr($waNumClean, 1);
    }

    $defaultScans = [
        [
            'id' => 'scan-1',
            'title' => 'Scan Surat Jalan Pengiriman 3.500 Pcs Roster BSD Serpong',
            'project_name' => 'Proyek Cluster Residensial — BSD City Tangerang',
            'doc_no' => 'DO/IR-PLR/2026/0412',
            'date_str' => '18 Februari 2026',
            'tag' => '✓ STEMPEL QC BASAH',
            'desc' => 'Lembar asli surat jalan rangkap 3 pengiriman 3.500 pcs roster beton minimalis motif nako & kotak dadu menggunakan armada truk CDD Long pabrik.',
            'image_upload' => null,
            'image_url' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259970/7_blkgfx.jpg',
        ],
        [
            'id' => 'scan-2',
            'title' => 'Scan Kwitansi Pelunasan Pengadaan Material Bermaterai',
            'project_name' => 'Pengadaan Fasade Kantor Komersial — Bandung Kulon',
            'doc_no' => 'KWT/IR-PLR/2026/0289',
            'date_str' => '04 Februari 2026',
            'tag' => '✓ MATERAI & STEMPEL LUNAS',
            'desc' => 'Tanda terima pelunasan resmi bermaterai untuk transaksi pembelian 1.800 keping roster expose halus dengan stempel lunas penanggung jawab pabrik.',
            'image_upload' => null,
            'image_url' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259822/469209740_1825168834684213_7463143257193343054_n_l4pum3.jpg',
        ],
        [
            'id' => 'scan-3',
            'title' => 'Scan Lembar Hasil Uji Kuat Tekan Laboratorium SNI',
            'project_name' => 'Uji Mutu Teknis Beton — Lab Uji Bahan Konstruksi',
            'doc_no' => 'LAB/SNI-03-0349/2026/B-44',
            'date_str' => '12 Januari 2026',
            'tag' => '✓ STANDAR K-225/250',
            'desc' => 'Dokumen hasil uji tekan laboratorium resmi yang membuktikan kekuatan tekan roster beton cetak tumbuk padat kami memenuhi spesifikasi SNI.',
            'image_upload' => null,
            'image_url' => 'https://res.cloudinary.com/indoroster/image/upload/q_auto,f_auto,w_600/v1765260049/40_kt08ee.jpg',
        ],
        [
            'id' => 'scan-4',
            'title' => 'Scan Berita Acara Serah Terima (BAST) Lapangan',
            'project_name' => 'Pembangunan Cafe & Resto Industrial — Karawang',
            'doc_no' => 'BAST/ROSTER/2026/I/014',
            'date_str' => '25 Januari 2026',
            'tag' => '✓ TTD PENGAWAS & SUPIR',
            'desc' => 'Berita acara pengecekan bersama di lokasi proyek yang menyatakan material roster tiba 100% utuh tanpa kerusakan dan siap dipasang.',
            'image_upload' => null,
            'image_url' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259970/7_blkgfx.jpg',
        ],
    ];

    $scans = !empty($data['scans']) ? $data['scans'] : $defaultScans;
@endphp

<section 
    class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden font-sans"
    x-data="{
        selectedIndex: null,
        zoomScale: 1,
        get currentScan() {
            return this.selectedIndex !== null ? $el.querySelectorAll('.scan-item-data')[this.selectedIndex] : null;
        },
        openLightbox(index) {
            this.selectedIndex = index;
            this.zoomScale = 1;
            document.body.style.overflow = 'hidden';
        },
        closeLightbox() {
            this.selectedIndex = null;
            this.zoomScale = 1;
            document.body.style.overflow = '';
        },
        prev() {
            if (this.selectedIndex > 0) {
                this.selectedIndex--;
                this.zoomScale = 1;
            } else {
                this.selectedIndex = {{ count($scans) - 1 }};
                this.zoomScale = 1;
            }
        },
        next() {
            if (this.selectedIndex < {{ count($scans) - 1 }}) {
                this.selectedIndex++;
                this.zoomScale = 1;
            } else {
                this.selectedIndex = 0;
                this.zoomScale = 1;
            }
        },
        zoomIn() {
            if (this.zoomScale < 3) this.zoomScale = parseFloat((this.zoomScale + 0.3).toFixed(1));
        },
        zoomOut() {
            if (this.zoomScale > 0.8) this.zoomScale = parseFloat((this.zoomScale - 0.3).toFixed(1));
        },
        resetZoom() {
            this.zoomScale = 1;
        }
    }"
    @keydown.escape.window="closeLightbox()"
    @keydown.arrow-left.window="if(selectedIndex !== null) prev()"
    @keydown.arrow-right.window="if(selectedIndex !== null) next()"
>
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-14 sm:mb-18" data-motion="fade-up">
            @if(!empty($badge))
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-black uppercase tracking-widest mb-5 shadow-soft-xs">
                <svg class="w-4 h-4 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ $badge }}</span>
            </div>
            @endif

            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black {{ $theme->headingColor }} tracking-tight leading-tight mb-5">
                {{ $title }}
            </h2>

            @if(!empty($subtitle))
            <p class="text-base sm:text-lg {{ $theme->subColor }} leading-relaxed">
                {{ $subtitle }}
            </p>
            @endif
        </div>

        {{-- Grid Gallery of Document Scans (Tanpa Tab - Tampil Bebas & Terbuka) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8" data-motion="stagger">
            @foreach($scans as $index => $scan)
            @php
                $imgUrl = !empty($scan['image_upload']) 
                    ? asset('storage/' . $scan['image_upload']) 
                    : ($scan['image_url'] ?? 'https://res.cloudinary.com/indoroster/image/upload/v1765259970/7_blkgfx.jpg');
                $tagText = $scan['tag'] ?? '✓ DOKUMEN RESMI';
            @endphp
            <div 
                data-motion-item
                @click="openLightbox({{ $index }})"
                class="group relative rounded-3xl overflow-hidden border transition-all duration-300 hover:-translate-y-1.5 hover:shadow-2xl cursor-pointer flex flex-col justify-between {{ $theme->cardBg }}"
            >
                {{-- Document Photo Preview Thumbnail --}}
                <div class="relative aspect-[4/3] bg-slate-950 overflow-hidden">
                    <img 
                        src="{{ $imgUrl }}" 
                        alt="{{ $scan['title'] ?? 'Dokumen IndoRoster' }}" 
                        class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105 opacity-90 group-hover:opacity-100"
                        loading="lazy"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/30 pointer-events-none"></div>

                    {{-- Top Tag Badge --}}
                    <div class="absolute top-4 left-4 z-10">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-slate-900/90 backdrop-blur-md text-amber-400 border border-amber-500/30 text-[11px] font-extrabold tracking-wide shadow-md">
                            {{ $tagText }}
                        </span>
                    </div>

                    {{-- Zoom Overlay Icon --}}
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-slate-950/40 backdrop-blur-xs">
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white text-slate-900 font-bold text-xs shadow-luxury transform scale-90 group-hover:scale-100 transition-transform">
                            <svg class="w-4 h-4 text-terra-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                            Klik Lihat Dokumen Full
                        </span>
                    </div>
                </div>

                {{-- Document Card Details --}}
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        @if(!empty($scan['doc_no']))
                        <div class="text-xs font-mono font-bold text-terra-500 mb-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>{{ $scan['doc_no'] }}</span>
                        </div>
                        @endif

                        <h3 class="text-base sm:text-lg font-black {{ $theme->cardTitle }} leading-snug mb-2 group-hover:text-terra-500 transition-colors">
                            {{ $scan['title'] ?? 'Dokumen Resmi' }}
                        </h3>

                        @if(!empty($scan['desc']))
                        <p class="text-xs sm:text-sm {{ $theme->cardDesc }} leading-relaxed line-clamp-2 mb-4">
                            {{ $scan['desc'] }}
                        </p>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-slate-200/60 dark:border-slate-800/80 flex items-center justify-between text-xs {{ $theme->cardDesc }}">
                        <span class="font-medium truncate max-w-[180px]">{{ $scan['project_name'] ?? 'Proyek IndoRoster' }}</span>
                        <span class="font-bold text-terra-500 flex items-center gap-1">
                            Buka Foto &rarr;
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>

    {{-- FULLSCREEN INTERACTIVE LIGHTBOX MODAL (ZOOM & FULL DETAIL) --}}
    <template x-if="selectedIndex !== null">
        <div 
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/95 backdrop-blur-md p-2 sm:p-6"
            @click.self="closeLightbox()"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
        >
            {{-- Top Navigation & Control Toolbar --}}
            <div class="absolute top-4 left-4 right-4 z-50 flex items-center justify-between text-white pointer-events-auto">
                {{-- Document Indicator --}}
                <div class="bg-slate-900/90 backdrop-blur-md px-4 py-2 rounded-2xl border border-slate-800 text-xs font-bold flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Dokumen <span x-text="selectedIndex + 1"></span> dari {{ count($scans) }}</span>
                </div>

                {{-- Zoom & Action Controls --}}
                <div class="flex items-center gap-2">
                    <button 
                        @click="zoomIn()" 
                        class="p-2.5 rounded-2xl bg-slate-900/90 hover:bg-slate-800 border border-slate-700 text-white transition-all cursor-pointer"
                        title="Zoom In (+)"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                    </button>
                    <button 
                        @click="zoomOut()" 
                        class="p-2.5 rounded-2xl bg-slate-900/90 hover:bg-slate-800 border border-slate-700 text-white transition-all cursor-pointer"
                        title="Zoom Out (-)"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"/></svg>
                    </button>
                    <button 
                        @click="resetZoom()" 
                        class="px-3 py-2 rounded-2xl bg-slate-900/90 hover:bg-slate-800 border border-slate-700 text-xs font-bold text-white transition-all cursor-pointer"
                        title="Reset Zoom"
                    >
                        <span x-text="Math.round(zoomScale * 100) + '%'"></span>
                    </button>
                    <button 
                        @click="closeLightbox()" 
                        class="p-2.5 rounded-2xl bg-red-600/90 hover:bg-red-700 text-white transition-all cursor-pointer shadow-lg ml-2"
                        title="Tutup (ESC)"
                    >
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            {{-- Main Modal Content Container --}}
            <div class="w-full h-full max-w-6xl flex flex-col lg:flex-row items-center justify-center gap-6 pt-16 pb-4 px-2 sm:px-4">
                
                {{-- Prev Button (Desktop) --}}
                <button 
                    @click="prev()" 
                    class="hidden lg:flex p-3 rounded-full bg-slate-900/80 hover:bg-terra-600 text-white border border-slate-700 transition-all cursor-pointer shrink-0"
                    title="Dokumen Sebelumnya"
                >
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>

                {{-- High-Resolution Zoomable Image Display --}}
                <div class="relative flex-1 w-full h-[60vh] sm:h-[70vh] lg:h-[80vh] flex items-center justify-center overflow-auto rounded-3xl bg-black/60 border border-slate-800 p-2 sm:p-4">
                    @foreach($scans as $idx => $scan)
                    @php
                        $fullImgUrl = !empty($scan['image_upload']) 
                            ? asset('storage/' . $scan['image_upload']) 
                            : ($scan['image_url'] ?? 'https://res.cloudinary.com/indoroster/image/upload/v1765259970/7_blkgfx.jpg');
                    @endphp
                    <div 
                        x-show="selectedIndex === {{ $idx }}" 
                        class="w-full h-full flex items-center justify-center transition-transform duration-200"
                        :style="`transform: scale(${zoomScale}); transform-origin: center center;`"
                    >
                        <img 
                            src="{{ $fullImgUrl }}" 
                            alt="{{ $scan['title'] ?? '' }}" 
                            class="max-w-full max-h-full object-contain rounded-xl shadow-2xl"
                        >
                    </div>
                    @endforeach
                </div>

                {{-- Side Details Panel (Desktop & Mobile) --}}
                <div class="w-full lg:w-96 max-h-[25vh] lg:max-h-[80vh] overflow-y-auto bg-slate-900/90 backdrop-blur-md rounded-3xl border border-slate-800 p-5 sm:p-6 text-white shrink-0 flex flex-col justify-between">
                    @foreach($scans as $idx => $scan)
                    @php
                        $fullImgUrl = !empty($scan['image_upload']) 
                            ? asset('storage/' . $scan['image_upload']) 
                            : ($scan['image_url'] ?? 'https://res.cloudinary.com/indoroster/image/upload/v1765259970/7_blkgfx.jpg');
                    @endphp
                    <div x-show="selectedIndex === {{ $idx }}" class="space-y-4">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/30 text-xs font-bold">
                            {{ $scan['tag'] ?? '✓ DOKUMEN RESMI' }}
                        </div>

                        <div>
                            @if(!empty($scan['doc_no']))
                            <p class="text-xs font-mono text-terra-400 font-bold mb-1">{{ $scan['doc_no'] }}</p>
                            @endif
                            <h3 class="text-lg sm:text-xl font-bold leading-snug">{{ $scan['title'] ?? 'Dokumen Resmi' }}</h3>
                        </div>

                        @if(!empty($scan['project_name']))
                        <div class="bg-slate-800/80 p-3 rounded-2xl border border-slate-700 text-xs space-y-1">
                            <p class="text-slate-400">Proyek / Klien:</p>
                            <p class="font-bold text-slate-200">{{ $scan['project_name'] }}</p>
                            @if(!empty($scan['date_str']))
                            <p class="text-[11px] text-slate-400 mt-1">Tanggal: {{ $scan['date_str'] }}</p>
                            @endif
                        </div>
                        @endif

                        @if(!empty($scan['desc']))
                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">{{ $scan['desc'] }}</p>
                        @endif

                        <div class="pt-2 flex flex-col gap-2.5">
                            <a 
                                href="{{ $fullImgUrl }}" 
                                target="_blank" 
                                class="w-full py-3 px-4 rounded-2xl bg-white text-slate-950 hover:bg-slate-100 font-bold text-xs text-center flex items-center justify-center gap-2 shadow-md cursor-pointer transition-all"
                            >
                                <svg class="w-4 h-4 text-terra-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                Buka Foto Resolusi Asli
                            </a>
                            <a 
                                href="https://wa.me/{{ $waNumClean }}?text={{ urlencode('Halo IndoRoster, saya ingin menanyakan verifikasi dokumen ' . ($scan['title'] ?? '')) }}" 
                                target="_blank" 
                                class="w-full py-2.5 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs text-center flex items-center justify-center gap-2 transition-all cursor-pointer"
                            >
                                💬 Verifikasi Dokumen via WA
                            </a>
                        </div>
                    </div>
                    @endforeach

                    {{-- Mobile Next/Prev Bar --}}
                    <div class="flex lg:hidden items-center justify-between pt-4 mt-4 border-t border-slate-800">
                        <button @click="prev()" class="px-4 py-2 rounded-xl bg-slate-800 text-xs font-bold flex items-center gap-1">&larr; Sebelumnya</button>
                        <button @click="next()" class="px-4 py-2 rounded-xl bg-slate-800 text-xs font-bold flex items-center gap-1">Berikutnya &rarr;</button>
                    </div>
                </div>

                {{-- Next Button (Desktop) --}}
                <button 
                    @click="next()" 
                    class="hidden lg:flex p-3 rounded-full bg-slate-900/80 hover:bg-terra-600 text-white border border-slate-700 transition-all cursor-pointer shrink-0"
                    title="Dokumen Berikutnya"
                >
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>

            </div>
        </div>
    </template>
</section>
