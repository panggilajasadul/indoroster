@props(['data'])

@php
    $badge = $data['badge'] ?? 'SIMULASI KEBUTUHAN CEPAT';
    $title = $data['title'] ?? 'Kalkulator Kebutuhan Roster & Semen Perekat';
    $description = $data['description'] ?? 'Masukkan ukuran dinding proyek Anda untuk mengetahui estimasi jumlah keping roster dan sak semen perekat yang dibutuhkan secara akurat.';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'slate');
    $defaultRatio = (float) ($data['roster_per_m2'] ?? 25); // 20x20 cm = 25 pcs/m2
@endphp

<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden font-sans"
    x-data="{
        length: 3,
        height: 2.5,
        wasteFactor: 5,
        rosterPerM2: {{ $defaultRatio }},
        selectedSize: '20x20',
        
        updateSize(size) {
            this.selectedSize = size;
            if (size === '20x20') this.rosterPerM2 = 25;
            else if (size === '20x40') this.rosterPerM2 = 12.5;
            else if (size === '30x30') this.rosterPerM2 = 11.1;
        },
        
        get totalArea() {
            let l = parseFloat(this.length) || 0;
            let h = parseFloat(this.height) || 0;
            return (l * h).toFixed(2);
        },
        
        get rawPcs() {
            let area = parseFloat(this.totalArea) || 0;
            return Math.ceil(area * this.rosterPerM2);
        },
        
        get wastePcs() {
            let raw = this.rawPcs;
            return Math.ceil(raw * (this.wasteFactor / 100));
        },
        
        get totalPcs() {
            return this.rawPcs + this.wastePcs;
        },
        
        get cementBags() {
            // Approx 1 sak mortar semen per 40-50 roster
            return Math.max(1, Math.ceil(this.totalPcs / 45));
        },
        
        get waUrl() {
            let msg = `Halo IndoRoster, saya sudah hitung estimasi kebutuhan di website:\n` +
                      `- Ukuran Dinding: ${this.length} m x ${this.height} m (Luas: ${this.totalArea} m²)\n` +
                      `- Ukuran Roster: ${this.selectedSize} cm\n` +
                      `- Estimasi Kebutuhan: ${this.totalPcs} pcs (termasuk cadangan 5%)\n` +
                      `- Estimasi Semen Perekat: ${this.cementBags} sak\n\n` +
                      `Mohon info total harga dan ongkir ke alamat saya ya. Terima kasih.`;
            return 'https://wa.me/6281389709847?text=' + encodeURIComponent(msg);
        }
    }"
>
    <x-blocks._bg-theme :theme="$theme" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-14 sm:mb-18">
            @if(!empty($badge))
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-black uppercase tracking-widest mb-4 shadow-soft-xs">
                <span>{{ $badge }}</span>
            </div>
            @endif

            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black {{ $theme->headingColor }} tracking-tight leading-tight mb-4">
                {{ $title }}
            </h2>

            @if(!empty($description))
            <p class="text-sm sm:text-base {{ $theme->subColor }} leading-relaxed">
                {{ $description }}
            </p>
            @endif
        </div>

        <!-- Calculator Layout -->
        <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            
            <!-- Left: Inputs -->
            <div class="lg:col-span-6 p-8 sm:p-10 rounded-3xl {{ $theme->cardBg }} border shadow-luxury flex flex-col justify-between">
                <div>
                    <h3 class="text-xl font-bold {{ $theme->cardTitle }} mb-6 flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-terra-500 text-white flex items-center justify-center text-sm font-black">1</span>
                        <span>Masukkan Dimensi Dinding</span>
                    </h3>

                    <!-- Size Selector Pills -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider mb-2.5">Ukuran Modul Roster</label>
                        <div class="grid grid-cols-3 gap-2.5">
                            <button type="button" @click="updateSize('20x20')" :class="selectedSize === '20x20' ? 'bg-terra-500 text-white border-terra-500 shadow-md font-bold' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700'" class="py-2.5 px-3 rounded-xl border text-xs sm:text-sm transition-all cursor-pointer text-center">
                                20 × 20 cm
                            </button>
                            <button type="button" @click="updateSize('20x40')" :class="selectedSize === '20x40' ? 'bg-terra-500 text-white border-terra-500 shadow-md font-bold' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700'" class="py-2.5 px-3 rounded-xl border text-xs sm:text-sm transition-all cursor-pointer text-center">
                                20 × 40 cm
                            </button>
                            <button type="button" @click="updateSize('30x30')" :class="selectedSize === '30x30' ? 'bg-terra-500 text-white border-terra-500 shadow-md font-bold' : 'bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700'" class="py-2.5 px-3 rounded-xl border text-xs sm:text-sm transition-all cursor-pointer text-center">
                                30 × 30 cm
                            </button>
                        </div>
                    </div>

                    <!-- Length Input Slider -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Panjang / Lebar Dinding (Meter)</label>
                            <span class="text-base font-black text-terra-500" x-text="length + ' m'"></span>
                        </div>
                        <input type="range" x-model="length" min="0.5" max="30" step="0.1" class="w-full h-2 bg-slate-200 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-terra-500">
                        <div class="flex justify-between text-[10px] text-slate-400 mt-1">
                            <span>0.5 m</span>
                            <span>15 m</span>
                            <span>30 m</span>
                        </div>
                    </div>

                    <!-- Height Input Slider -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Tinggi Dinding (Meter)</label>
                            <span class="text-base font-black text-terra-500" x-text="height + ' m'"></span>
                        </div>
                        <input type="range" x-model="height" min="0.5" max="10" step="0.1" class="w-full h-2 bg-slate-200 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-terra-500">
                        <div class="flex justify-between text-[10px] text-slate-400 mt-1">
                            <span>0.5 m</span>
                            <span>5 m</span>
                            <span>10 m</span>
                        </div>
                    </div>
                </div>

                <!-- Safety Note -->
                <div class="p-4 rounded-2xl bg-terra-500/10 border border-terra-500/20 text-xs text-slate-700 dark:text-slate-300 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-terra-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Termasuk cadangan 5% untuk potongan sudut & penyesuaian tukang saat instalasi.</span>
                </div>
            </div>

            <!-- Right: Results Card -->
            <div class="lg:col-span-6 p-8 sm:p-10 rounded-3xl bg-slate-950 text-white border border-slate-800 shadow-luxury flex flex-col justify-between relative overflow-hidden">
                <!-- Ambient Glow -->
                <div class="absolute -top-16 -right-16 w-48 h-48 bg-terra-500/20 rounded-full blur-3xl"></div>

                <div class="relative z-10">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2.5">
                        <span class="w-8 h-8 rounded-xl bg-terra-500 text-white flex items-center justify-center text-sm font-black">2</span>
                        <span>Hasil Estimasi Kebutuhan</span>
                    </h3>

                    <!-- Big Total Pcs Display -->
                    <div class="p-6 rounded-2xl bg-white/5 border border-white/10 text-center mb-6 backdrop-blur-sm">
                        <div class="text-xs uppercase tracking-widest text-slate-400 font-bold mb-1">Rekomendasi Jumlah Roster</div>
                        <div class="text-5xl sm:text-6xl font-black text-terra-400 mb-1" x-text="totalPcs"></div>
                        <div class="text-xs text-slate-400">Keping Roster Beton Presisi</div>
                    </div>

                    <!-- Breakdown Grid -->
                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                            <div class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">Luas Dinding</div>
                            <div class="text-xl font-black text-white mt-1" x-text="totalArea + ' m²'"></div>
                        </div>

                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10">
                            <div class="text-[11px] text-slate-400 uppercase tracking-wider font-semibold">Semen Perekat</div>
                            <div class="text-xl font-black text-emerald-400 mt-1" x-text="'± ' + cementBags + ' Sak'"></div>
                        </div>
                    </div>
                </div>

                <!-- WhatsApp CTA Action -->
                <div class="relative z-10">
                    <a :href="waUrl" target="_blank" class="w-full py-5 px-6 bg-terra-500 hover:bg-terra-400 text-white font-black text-sm uppercase tracking-wider rounded-2xl transition-all shadow-luxury hover:scale-[1.02] flex items-center justify-center gap-3 group">
                        <span>Pesan Jumlah Ini via WhatsApp</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                    <p class="text-center text-[10px] text-slate-500 uppercase tracking-widest mt-3">
                        Konsultasi Gratis • Harga Pabrik Tangan Pertama
                    </p>
                </div>
            </div>

        </div>

    </div>
</section>
