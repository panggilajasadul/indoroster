<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-12">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-terra-500/10 border border-terra-500/30 text-terra-600 dark:text-terra-400 text-xs font-semibold uppercase tracking-wider mb-4">
                <span>🧮</span> Alat Perhitungan Akurat
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                Kalkulator Kebutuhan <span class="text-terra-500">Roster Beton Dinding</span>
            </h1>
            <p class="mt-3 text-slate-600 dark:text-slate-400 text-sm sm:text-base">
                Hitung estimasi akurat jumlah keping (pcs) roster beton yang Anda butuhkan untuk pagar, fasad, atau sekat partisi ruangan.
            </p>
        </div>

        <!-- Calculator Form Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-16">
            <!-- Left: Inputs -->
            <div class="lg:col-span-7 bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
                <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                    <span>📐</span> Masukkan Dimensi Dinding Anda
                </h2>

                <div class="space-y-6">
                    <!-- Wall Width & Height -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Panjang / Lebar Dinding (meter)
                            </label>
                            <input type="number" step="0.1" min="0.1" wire:model.live="wall_width" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/80 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-terra-500 focus:outline-none transition text-base">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                                Tinggi Dinding (meter)
                            </label>
                            <input type="number" step="0.1" min="0.1" wire:model.live="wall_height" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/80 text-slate-900 dark:text-white font-bold focus:ring-2 focus:ring-terra-500 focus:outline-none transition text-base">
                        </div>
                    </div>

                    <!-- Roster Size & Options -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Pilih Ukuran Roster
                        </label>
                        <select wire:model.live="roster_dimension" class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/80 text-slate-900 dark:text-white text-xs sm:text-sm font-semibold focus:ring-2 focus:ring-terra-500 focus:outline-none cursor-pointer">
                            <option value="20x20x8">20 × 20 × 8 cm (25 pcs/m²) — Ukuran paling populer & fleksibel untuk sirkulasi udara</option>
                            <option value="20x20x10">20 × 20 × 10 cm (25 pcs/m²) — Varian lebih tebal, kokoh untuk struktur pagar & fasad luar</option>
                            <option value="15x30x10">15 × 30 × 10 cm (~22.2 pcs/m²) — Motif nako / jalusi (anti tampias) vertikal rapi</option>
                        </select>
                        <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                            @if($roster_dimension === '20x20x8')
                                💡 <strong>20×20×8 cm:</strong> Ukuran paling populer dan fleksibel untuk sirkulasi udara serta pencahayaan (25 pcs/m²).
                            @elseif($roster_dimension === '20x20x10')
                                💡 <strong>20×20×10 cm:</strong> Varian lebih tebal yang memberikan kesan kokoh, cocok untuk struktur pagar atau fasad luar (25 pcs/m²).
                            @else
                                💡 <strong>15×30×10 cm:</strong> Sangat umum untuk motif nako atau jalusi/anti tampias, memberikan efek visual vertikal yang rapi (~22.2 pcs/m²).
                            @endif
                        </p>
                    </div>

                    <!-- Include Waste Checkbox -->
                    <div class="flex items-start sm:items-center gap-3 pt-2">
                        <input type="checkbox" id="waste" wire:model.live="include_waste" class="w-5 h-5 rounded text-terra-500 focus:ring-terra-500 border-slate-300 dark:border-slate-700 mt-0.5 sm:mt-0 cursor-pointer">
                        <label for="waste" class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 cursor-pointer">
                            Tambahkan <strong>Cadangan Pasang 5%</strong> (Rekomendasi tukang untuk potongan sudut dinding & nat semen)
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right: Results Card -->
            <div class="lg:col-span-5 bg-gradient-to-br from-slate-900 via-slate-850 to-slate-900 rounded-3xl p-6 sm:p-8 border border-slate-800 text-white shadow-2xl flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-terra-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10">
                    <span class="text-xs font-bold text-terra-400 uppercase tracking-wider block mb-2">
                        Hasil Estimasi Kebutuhan
                    </span>
                    <div class="mt-2 mb-6">
                        <span class="text-5xl sm:text-6xl font-black tracking-tight text-white">{{ $totalPcs }}</span>
                        <span class="text-lg sm:text-xl font-bold text-terra-400 ml-2">Pieces (Keping)</span>
                    </div>

                    <div class="space-y-3.5 pt-4 border-t border-slate-800 text-sm">
                        <div class="flex items-center justify-between text-slate-300">
                            <span>Luas Bersih Dinding</span>
                            <span class="font-bold text-white">{{ round($netArea, 2) }} m²</span>
                        </div>
                        <div class="flex items-center justify-between text-slate-300">
                            <span>Kebutuhan Bersih</span>
                            <span class="font-bold text-white">{{ $rawPcs }} pcs</span>
                        </div>
                        @if($include_waste)
                        <div class="flex items-center justify-between text-slate-300">
                            <span>Cadangan Pasang (5%)</span>
                            <span class="font-bold text-emerald-400">+{{ $wastePcs }} pcs</span>
                        </div>
                        @endif
                        <div class="flex items-center justify-between text-slate-300 pt-3 border-t border-slate-800/80">
                            <span>Estimasi Total Bobot</span>
                            <span class="font-bold text-white">~{{ $estimatedWeightKg }} kg</span>
                        </div>
                    </div>
                </div>

                <!-- Action Button to WhatsApp -->
                <div class="mt-8 pt-6 border-t border-slate-800 relative z-10">
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2.5 px-6 py-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm sm:text-base shadow-lg shadow-emerald-600/30 transition-all hover:scale-[1.02]">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        Kirim Hitungan Ini ke WhatsApp Admin
                    </a>
                    <p class="text-[11px] text-center text-slate-400 mt-3">
                        🛡️ Garansi 100% Bebas Pecah • Harga Pabrik Langsung
                    </p>
                </div>
            </div>
        </div>

        <!-- Featured Products -->
        <div>
            <div class="flex items-center justify-between mb-6 pb-2 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Pilihan Motif Roster Terpopuler</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Motif presisi paling banyak digunakan untuk dinding dan pagar arsitektural.</p>
                </div>
                <a href="{{ route('catalog') }}" class="text-xs font-bold text-terra-500 hover:text-terra-600 dark:hover:text-terra-400">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5 sm:gap-4">
                @foreach($featuredProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </div>
</div>

