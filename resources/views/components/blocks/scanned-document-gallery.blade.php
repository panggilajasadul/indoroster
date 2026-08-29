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
            'category' => 'surat-jalan',
            'title' => 'Scan Surat Jalan Pengiriman 3.500 Pcs Roster BSD Serpong',
            'project_name' => 'Proyek Cluster Residensial — BSD City Tangerang',
            'doc_no' => 'DO/IR-PLR/2026/0412',
            'date_str' => '18 Februari 2026',
            'tag' => '✓ STEMPEL QC BASAH',
            'tag_color' => 'amber',
            'desc' => 'Lembar asli surat jalan rangkap 3 pengiriman 3.500 pcs roster beton minimalis motif nako & kotak dadu menggunakan armada truk CDD Long pabrik.',
            'image_upload' => null,
            'image_url' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259970/7_blkgfx.jpg',
        ],
        [
            'id' => 'scan-2',
            'category' => 'kwitansi',
            'title' => 'Scan Kwitansi Pelunasan Pengadaan Material Bermaterai',
            'project_name' => 'Pengadaan Fasade Kantor Komersial — Bandung Kulon',
            'doc_no' => 'KWT/IR-PLR/2026/0289',
            'date_str' => '04 Februari 2026',
            'tag' => '✓ MATERAI & STEMPEL LUNAS',
            'tag_color' => 'emerald',
            'desc' => 'Tanda terima pelunasan resmi bermaterai untuk transaksi pembelian 1.800 keping roster expose halus dengan stempel lunas penanggung jawab pabrik.',
            'image_upload' => null,
            'image_url' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259822/469209740_1825168834684213_7463143257193343054_n_l4pum3.jpg',
        ],
        [
            'id' => 'scan-3',
            'category' => 'uji-lab',
            'title' => 'Scan Lembar Hasil Uji Kuat Tekan Laboratorium SNI',
            'project_name' => 'Uji Mutu Teknis Beton — Lab Uji Bahan Konstruksi',
            'doc_no' => 'LAB/SNI-03-0349/2026/B-44',
            'date_str' => '12 Januari 2026',
            'tag' => '✓ STANDAR K-225/250',
            'tag_color' => 'rose',
            'desc' => 'Dokumen hasil uji tekan laboratorium resmi yang membuktikan kekuatan tekan roster beton cetak tumbuk padat kami memenuhi spesifikasi SNI.',
            'image_upload' => null,
            'image_url' => 'https://res.cloudinary.com/indoroster/image/upload/q_auto,f_auto,w_600/v1765260049/40_kt08ee.jpg',
        ],
        [
            'id' => 'scan-4',
            'category' => 'bast',
            'title' => 'Scan Berita Acara Serah Terima (BAST) Lapangan',
            'project_name' => 'Pembangunan Cafe & Resto Industrial — Karawang',
            'doc_no' => 'BAST/ROSTER/2026/I/014',
            'date_str' => '25 Januari 2026',
            'tag' => '✓ TTD PENGAWAS & SUPIR',
            'tag_color' => 'blue',
            'desc' => 'Berita acara pengecekan bersama di lokasi proyek yang menyatakan material roster tiba 100% utuh tanpa kerusakan dan siap dipasang.',
            'image_upload' => null,
            'image_url' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259970/7_blkgfx.jpg',
        ],
        [
            'id' => 'scan-5',
            'category' => 'sph',
            'title' => 'Scan Surat Penawaran Harga (SPH) Resmi Proyek Hotel',
            'project_name' => 'Paket Pekerjaan Dinding Roster — Sentul Bogor',
            'doc_no' => 'SPH/IR-PLR/2026/0073',
            'date_str' => '10 Februari 2026',
            'tag' => '✓ HARGA GROSIR TERKUNCI',
            'tag_color' => 'purple',
            'desc' => 'Dokumen penawaran harga resmi dengan rincian jadwal suplai bertahap, diskon volume besar, dan jaminan bebas kenaikan harga selama masa proyek.',
            'image_upload' => null,
            'image_url' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259822/469209740_1825168834684213_7463143257193343054_n_l4pum3.jpg',
        ],
        [
            'id' => 'scan-6',
            'category' => 'surat-jalan',
            'title' => 'Scan Surat Jalan Pengiriman 5.000 Pcs Proyek Cibubur',
            'project_name' => 'Fasade Dinding Pagar Roster — Cibubur Jakarta Timur',
            'doc_no' => 'DO/IR-PLR/2026/0501',
            'date_str' => '22 Februari 2026',
            'tag' => '✓ 2 RITASE ARMADA TRUK',
            'tag_color' => 'amber',
            'desc' => 'Surat jalan resmi 2 ritase armada truk mengangkut 5.000 keping roster beton cetak presisi dengan stempel timbang dan tanda terima site manager.',
            'image_upload' => null,
            'image_url' => 'https://res.cloudinary.com/indoroster/image/upload/q_auto,f_auto,w_600/v1765260049/40_kt08ee.jpg',
        ],
    ];

    $scans = !empty($data['scans']) ? $data['scans'] : $defaultScans;
@endphp

<section 
    class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden font-sans select-none"
    x-data="{
        activeCategory: 'all',
        selectedScan: null,
        openLightbox(scan) {
            this.selectedScan = scan;
            document.body.style.overflow = 'hidden';
        },
        closeLightbox() {
            this.selectedScan = null;
            document.body.style.overflow = '';
        }
    }"
    @keydown.escape.window="closeLightbox()"
>
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-14">
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

        {{-- Interactive Filter Categories --}}
        <div class="flex items-center justify-center flex-wrap gap-2 mb-12">
            <button 
                @click="activeCategory = 'all'" 
                :class="activeCategory === 'all' ? 'bg-terra-500 text-white shadow-md shadow-terra-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
                📸 Semua Scan Dokumen ({{ count($scans) }})
            </button>
            <button 
                @click="activeCategory = 'surat-jalan'" 
                :class="activeCategory === 'surat-jalan' ? 'bg-amber-600 text-white shadow-md shadow-amber-600/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
                🚚 Surat Jalan Asli
            </button>
            <button 
                @click="activeCategory = 'kwitansi'" 
                :class="activeCategory === 'kwitansi' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
                💰 Kwitansi Bermaterai
            </button>
            <button 
                @click="activeCategory = 'uji-lab'" 
                :class="activeCategory === 'uji-lab' ? 'bg-rose-600 text-white shadow-md shadow-rose-600/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
                🔬 Uji Kuat Tekan Lab
            </button>
            <button 
                @click="activeCategory = 'bast'" 
                :class="activeCategory === 'bast' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
                📋 BAST Lapangan
            </button>
            <button 
                @click="activeCategory = 'sph'" 
                :class="activeCategory === 'sph' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
                📝 SPH Penawaran
            </button>
        </div>

        {{-- Scanned Photo Grid Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($scans as $scan)
            @php
                $cat = $scan['category'] ?? 'general';
                $imgSrc = !empty($scan['image_upload']) 
                    ? asset('storage/' . $scan['image_upload']) 
                    : ($scan['image_url'] ?? 'https://res.cloudinary.com/indoroster/image/upload/v1765259970/7_blkgfx.jpg');
            @endphp
            <div 
                x-show="activeCategory === 'all' || activeCategory === '{{ $cat }}'"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                class="group flex flex-col justify-between rounded-2xl overflow-hidden {{ $theme->cardBg }} shadow-md hover:shadow-2xl transition-all duration-300 hover:-translate-y-1.5 cursor-pointer"
                @click="openLightbox({{ json_encode(array_merge($scan, ['final_image' => $imgSrc])) }})"
            >
                <div>
                    {{-- Photo Frame Container --}}
                    <div class="relative aspect-[4/3] bg-slate-900 overflow-hidden">
                        <img 
                            src="{{ $imgSrc }}" 
                            alt="{{ $scan['title'] }}" 
                            class="w-full h-full object-cover group-hover:scale-108 transition-transform duration-700 opacity-90 group-hover:opacity-100"
                            loading="lazy"
                        >

                        {{-- Dark Gradient Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/30 pointer-events-none"></div>

                        {{-- Tag Badge --}}
                        <div class="absolute top-3 left-3 z-10">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-black/60 backdrop-blur-md text-white text-[10px] font-black tracking-wider uppercase border border-white/20 shadow-xs">
                                {{ $scan['tag'] ?? '✓ ASLI' }}
                            </span>
                        </div>

                        {{-- Paper Clip / Scan Icon --}}
                        <div class="absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white border border-white/30 shadow-xs">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        </div>

                        {{-- Hover Magnifier Overlay --}}
                        <div class="absolute inset-0 bg-terra-500/20 backdrop-blur-[1px] opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center z-20">
                            <span class="px-4 py-2 rounded-xl bg-slate-900/90 text-white font-bold text-xs shadow-luxury flex items-center gap-2 transform scale-90 group-hover:scale-100 transition-transform">
                                <svg class="w-4 h-4 text-terra-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                Buka Foto Scan Asli
                            </span>
                        </div>

                        {{-- Bottom Meta on Photo --}}
                        <div class="absolute bottom-3 left-3 right-3 text-white z-10 flex items-center justify-between text-[11px] font-mono">
                            <span class="truncate max-w-[180px]">{{ $scan['doc_no'] ?? 'NO. REG' }}</span>
                            <span class="text-slate-300 shrink-0">{{ $scan['date_str'] ?? '' }}</span>
                        </div>
                    </div>

                    {{-- Card Info Body --}}
                    <div class="p-5">
                        <div class="text-[11px] font-bold text-terra-600 dark:text-terra-400 uppercase tracking-wider mb-1 truncate">
                            {{ $scan['project_name'] ?? 'Proyek Pelanggan' }}
                        </div>
                        <h3 class="text-base font-bold {{ $theme->cardTitle }} leading-snug mb-2 group-hover:text-terra-500 transition-colors line-clamp-2">
                            {{ $scan['title'] }}
                        </h3>
                        <p class="text-xs {{ $theme->cardDesc }} leading-relaxed line-clamp-2 mb-3">
                            {{ $scan['desc'] ?? '' }}
                        </p>
                    </div>
                </div>

                {{-- Card Action Footer --}}
                <div class="px-5 pb-5 pt-0 flex items-center justify-between border-t border-slate-200 dark:border-slate-800 pt-3 text-xs font-bold text-terra-600 dark:text-terra-400">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Lihat Foto Utuh
                    </span>
                    <span class="text-slate-400 text-[11px] font-mono">Klik Foto 🔍</span>
                </div>

            </div>
            @endforeach
        </div>

        {{-- LIGHTBOX FULL-SCREEN MODAL FOR SCAN PHOTOS --}}
        <template x-if="selectedScan">
            <div 
                class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-950/90 backdrop-blur-md overflow-y-auto"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click.self="closeLightbox()"
            >
                <div 
                    class="relative w-full max-w-4xl bg-slate-900 text-white rounded-3xl shadow-2xl overflow-hidden border border-slate-800 my-auto flex flex-col max-h-[92vh]"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                >
                    {{-- Modal Header --}}
                    <div class="px-6 py-4 bg-slate-950 flex items-center justify-between border-b border-slate-800 shrink-0">
                        <div>
                            <span class="px-2.5 py-0.5 rounded bg-terra-500/20 text-terra-400 border border-terra-500/30 text-[10px] font-mono font-black uppercase" x-text="selectedScan.tag || 'DOKUMEN ASLI'"></span>
                            <h3 class="text-sm sm:text-base font-bold text-white leading-tight mt-1" x-text="selectedScan.title"></h3>
                        </div>
                        <button 
                            @click="closeLightbox()" 
                            class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Modal Body: Full Scan Image & Details --}}
                    <div class="p-4 sm:p-6 overflow-y-auto flex-1 flex flex-col lg:flex-row gap-6">
                        
                        {{-- Large Image Container --}}
                        <div class="flex-1 bg-black rounded-2xl overflow-hidden border border-slate-800 flex items-center justify-center relative min-h-[320px]">
                            <img 
                                :src="selectedScan.final_image" 
                                :alt="selectedScan.title" 
                                class="max-h-[65vh] w-auto object-contain rounded-xl select-none"
                            >
                            {{-- Watermark --}}
                            <div class="absolute bottom-3 right-3 px-3 py-1 bg-black/70 backdrop-blur-md rounded-lg text-[10px] font-mono text-slate-300 border border-white/10 select-none">
                                INDOROSTER AUTHENTIC SCAN
                            </div>
                        </div>

                        {{-- Metadata Side Panel --}}
                        <div class="lg:w-80 flex flex-col justify-between space-y-4 text-xs font-sans">
                            <div class="space-y-4">
                                <div class="p-4 bg-slate-800/80 rounded-2xl border border-slate-700/80 space-y-2">
                                    <div class="text-[10px] font-black uppercase text-slate-400">Informasi Dokumen</div>
                                    <div class="flex justify-between font-mono">
                                        <span class="text-slate-400">No. Registrasi:</span>
                                        <strong class="text-white" x-text="selectedScan.doc_no"></strong>
                                    </div>
                                    <div class="flex justify-between font-mono">
                                        <span class="text-slate-400">Tanggal Terbit:</span>
                                        <span class="text-white" x-text="selectedScan.date_str"></span>
                                    </div>
                                    <div class="flex justify-between font-mono">
                                        <span class="text-slate-400">Peruntukan:</span>
                                        <span class="text-emerald-400 font-bold truncate max-w-[130px]" x-text="selectedScan.project_name"></span>
                                    </div>
                                </div>

                                <div class="p-4 bg-slate-800/80 rounded-2xl border border-slate-700/80">
                                    <div class="text-[10px] font-black uppercase text-slate-400 mb-1">Catatan Dokumen</div>
                                    <p class="text-slate-300 leading-relaxed" x-text="selectedScan.desc"></p>
                                </div>
                            </div>

                            <div class="space-y-2 pt-2">
                                <a 
                                    :href="'https://wa.me/{{ $waNumClean }}?text=' + encodeURIComponent('Halo Tim Sales IndoRoster, saya melihat scan bukti dokumen: ' + selectedScan.title + ' di website. Saya ingin meminta penawaran serupa untuk proyek saya.')"
                                    target="_blank"
                                    class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-terra-500 to-amber-600 hover:from-terra-600 hover:to-amber-700 text-white font-bold text-center flex items-center justify-center gap-2 shadow-luxury cursor-pointer transition">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    <span>Tanya Dokumen Proyek Ini</span>
                                </a>
                                <button 
                                    @click="closeLightbox()" 
                                    class="w-full py-2.5 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-center font-bold transition cursor-pointer">
                                    Tutup Pratinjau
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </template>

    </div>
</section>
