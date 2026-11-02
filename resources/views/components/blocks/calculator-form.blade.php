@props(['data', 'pageTitle' => null])

@php
    $badge = $data['badge'] ?? 'GRATIS · TANPA KOMITMEN';
    $title = $data['title'] ?? 'Hitung Kebutuhanmu, Penawaran Datang Cepat';
    $subtitle = $data['subtitle'] ?? 'Isi form singkat ini — tim kami akan menghitung kebutuhan, estimasi biaya, dan ongkir ke lokasimu. Balasan cepat di jam kerja.';
    
    $rawTheme = $data['bg_theme'] ?? ($data['theme'] ?? 'white');
    $theme = \App\Helpers\BlockTheme::resolve($rawTheme);

    $rawWa = \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
    $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
    if (str_starts_with($waNumber, '0')) {
        $waNumber = '62' . substr($waNumber, 1);
    }
@endphp

<section class="py-14 sm:py-20 lg:py-24 relative overflow-hidden font-sans {{ $theme->bgClasses }} transition-colors duration-300"
    x-data="{
        nama: '',
        whatsapp: '',
        motif: '',
        jumlahPcs: '',
        luasM2: '',
        kapanPasang: '',
        lokasi: '',
        catatan: '',
        submitToWa() {
            if (!this.nama || !this.whatsapp) {
                alert('Silakan isi Nama dan No. WhatsApp Anda terlebih dahulu.');
                return;
            }
            let msg = `Halo *IndoRoster*! 👋\nSaya mau minta penawaran & estimasi kebutuhan roster beton:\n\n` +
                      `• *Nama:* ${this.nama}\n` +
                      `• *No. WhatsApp:* ${this.whatsapp}\n` +
                      (this.motif ? `• *Motif / Produk:* ${this.motif}\n` : '') +
                      (this.jumlahPcs ? `• *Jumlah Kebutuhan:* ${this.jumlahPcs} pcs\n` : '') +
                      (this.luasM2 ? `• *Luas Dinding:* ${this.luasM2} m²\n` : '') +
                      (this.kapanPasang ? `• *Rencana Pasang:* ${this.kapanPasang}\n` : '') +
                      (this.lokasi ? `• *Lokasi Proyek:* ${this.lokasi}\n` : '') +
                      (this.catatan ? `• *Catatan:* ${this.catatan}\n` : '') +
                      `\nMohon dibantu rincian harga & estimasi ongkirnya ya. Terima kasih!`;
            
            let url = `https://wa.me/{{ $waNumber }}?text=` + encodeURIComponent(msg);
            window.open(url, '_blank');
        }
    }">
    <x-blocks._bg-theme :theme="$theme" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
            
            {{-- KOLOM KIRI: INFO BENEFIT & ONGKIR --}}
            <div class="lg:col-span-6 flex flex-col items-start text-left">
                @if(!empty($badge))
                    <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-3.5 {{ $theme->badgeClass }}">
                        {{ $badge }}
                    </span>
                @endif

                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight leading-tight mb-5 {{ $theme->headingColor }}">
                    {{ $title }}
                </h2>

                <p class="text-sm sm:text-base md:text-lg leading-relaxed mb-8 {{ $theme->subColor }}">
                    {{ $subtitle }}
                </p>

                {{-- Checklist Benefits --}}
                <div class="space-y-3.5 mb-8 w-full">
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-xs sm:text-sm font-semibold {{ $theme->cardTitle }}">Balasan cepat via WhatsApp di jam kerja</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-xs sm:text-sm font-semibold {{ $theme->cardTitle }}">Harga transparan, langsung dari pabrik</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-xs sm:text-sm font-semibold {{ $theme->cardTitle }}">Dibantu hitung kebutuhan pcs-nya sekalian</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-6 h-6 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span class="text-xs sm:text-sm font-semibold {{ $theme->cardTitle }}">Data kamu aman, cuma dipakai untuk penawaran</span>
                    </div>
                </div>

                {{-- Kotak Hijau Info Ongkir --}}
                <div class="w-full rounded-2xl p-4 sm:p-5 border flex items-center gap-3.5 bg-emerald-50/80 dark:bg-slate-900 border-emerald-200/70 dark:border-slate-800 text-emerald-900 dark:text-emerald-300">
                    <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="text-xs sm:text-sm leading-relaxed">
                        <strong class="font-bold">Info ongkir:</strong> area Jabodetabek <strong>GRATIS</strong> untuk pembelian minimal 50 pcs. Luar Jabodetabek? Tetap kami hitung paling hemat.
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN: FORM CARD PUTIH --}}
            <div class="lg:col-span-6 w-full">
                <div class="rounded-3xl p-6 sm:p-8 border shadow-xl bg-white dark:bg-slate-900 border-slate-200/90 dark:border-slate-800">
                    <div class="mb-6">
                        <h3 class="text-xl font-black text-slate-900 dark:text-white mb-1">
                            Minta Penawaran
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Isi 2 menit langsung jadi chat WhatsApp otomatis.
                        </p>
                    </div>

                    <form @submit.prevent="submitToWa()" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Nama Kamu *</label>
                                <input type="text" x-model="nama" required placeholder="cth: Bapak Andi"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 text-xs sm:text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">No. WhatsApp *</label>
                                <input type="tel" x-model="whatsapp" required placeholder="cth: 0812-3456-7890"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 text-xs sm:text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Motif / Produk yang Diminati</label>
                            <input type="text" x-model="motif" placeholder="cth: roster nako 30x30, bata ekspose, genteng"
                                   class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 text-xs sm:text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none">
                            <span class="block text-[10px] text-slate-400 mt-1">Belum tahu motifnya? Tulis saja "minta rekomendasi" — kami bantu pilihkan.</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Jumlah Kebutuhan (pcs)</label>
                                <input type="text" x-model="jumlahPcs" placeholder="cth: 500"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 text-xs sm:text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kebutuhan Luas (m², opsional)</label>
                                <input type="text" x-model="luasM2" placeholder="cth: 12"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 text-xs sm:text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Kapan Rencana Dipasang?</label>
                                <input type="text" x-model="kapanPasang" placeholder="cth: minggu depan / akhir bulan"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 text-xs sm:text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Lokasi Proyek</label>
                                <input type="text" x-model="lokasi" placeholder="cth: Cikampek, Karawang, Bekasi"
                                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 text-xs sm:text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">Catatan Tambahan</label>
                            <textarea x-model="catatan" rows="2" placeholder="cth: butuh kirim hari Sabtu pagi, warna abu natural"
                                      class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800 text-xs sm:text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-amber-500 focus:outline-none"></textarea>
                        </div>

                        <button type="submit"
                                class="w-full py-3.5 px-6 rounded-full font-bold text-sm sm:text-base text-white bg-amber-600 hover:bg-amber-700 active:scale-[0.98] transition-all shadow-lg shadow-amber-600/30 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                            <span>Kirim & Lanjut ke WhatsApp</span>
                        </button>

                        <p class="text-center text-[11px] text-slate-400 dark:text-slate-500">
                            Data tersimpan aman & langsung tersambung ke admin kami.
                        </p>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

