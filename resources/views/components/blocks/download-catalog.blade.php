@props(['data'])

@php
    $badge = $data['badge'] ?? 'E-KATALOG & PRICELIST TERBARU';
    $title = $data['title'] ?? 'Download Buku Katalog Lengkap 150+ Motif Roster (PDF)';
    $subtitle = $data['subtitle'] ?? 'Dapatkan spesifikasi lengkap, varian motif minimalis/klasik, inspirasi pemasangan fasad, dan tabel harga pabrik.';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'accent');

    $isUploaded = !empty($data['pdf_upload']);
    $isExternalUrl = !empty($data['pdf_url']);

    if ($isUploaded) {
        $finalUrl = asset('storage/' . $data['pdf_upload']);
        $defaultBtn = 'Download E-Katalog PDF';
        $isDirectFile = true;
    } elseif ($isExternalUrl) {
        $finalUrl = $data['pdf_url'];
        $defaultBtn = 'Buka & Download E-Katalog';
        $isDirectFile = false;
    } else {
        $finalUrl = 'https://wa.me/6281389709847?text=' . urlencode('Halo IndoRoster, tolong kirimkan file E-Katalog PDF dan Pricelist Roster terbaru ke WhatsApp saya ya. Terima kasih.');
        $defaultBtn = 'Kirim E-Katalog ke WhatsApp';
        $isDirectFile = false;
    }

    $buttonText = !empty($data['button_text']) ? $data['button_text'] : $defaultBtn;
@endphp

<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden font-sans">
    <x-blocks._bg-theme :theme="$theme" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="max-w-4xl mx-auto p-8 sm:p-14 rounded-3xl bg-slate-950 text-white border border-slate-800 shadow-luxury relative overflow-hidden flex flex-col md:flex-row items-center gap-10">
            <!-- Decorative Glow -->
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-terra-500/25 rounded-full blur-3xl pointer-events-none"></div>

            <!-- PDF Mockup / Book Preview -->
            <div class="w-full md:w-5/12 flex justify-center shrink-0">
                <div class="relative group">
                    <div class="w-48 sm:w-56 aspect-3/4 rounded-2xl bg-gradient-to-br from-slate-800 to-slate-900 border-2 border-terra-500/40 p-5 shadow-2xl flex flex-col justify-between group-hover:scale-105 transition-transform duration-500 relative">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black tracking-widest text-terra-400 uppercase">EDISI TERBARU</span>
                            <span class="px-2 py-0.5 rounded-full bg-terra-500 text-white text-[9px] font-black">PDF</span>
                        </div>

                        <div class="my-auto text-center">
                            <div class="w-12 h-12 rounded-xl bg-terra-500/20 text-terra-400 mx-auto flex items-center justify-center mb-3">
                                📖
                            </div>
                            <div class="font-display font-black text-lg text-white leading-tight">E-KATALOG</div>
                            <div class="text-[11px] text-slate-300 font-bold uppercase tracking-wider mt-1">INDOROSTER</div>
                            <div class="text-[9px] text-slate-400 mt-1">150+ Motif & Spesifikasi</div>
                        </div>

                        <div class="pt-3 border-t border-slate-700/80 text-center text-[9px] text-slate-400 uppercase tracking-wider font-semibold">
                            Pabrik Plered Purwakarta
                        </div>
                    </div>
                </div>
            </div>

            <!-- Text & Form Action -->
            <div class="w-full md:w-7/12 text-center md:text-left">
                @if(!empty($badge))
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-terra-500/20 text-terra-400 border border-terra-500/30 text-xs font-black uppercase tracking-widest mb-4">
                    <span>{{ $badge }}</span>
                </div>
                @endif

                <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-tight mb-3">
                    {{ $title }}
                </h2>

                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed mb-8">
                    {{ $subtitle }}
                </p>

                <!-- Action Button -->
                <div class="flex flex-col sm:flex-row gap-3.5">
                    <a href="{{ $finalUrl }}" 
                       target="_blank" 
                       {{ $isDirectFile ? 'download' : '' }} 
                       class="px-8 py-4 bg-terra-500 hover:bg-terra-400 text-white font-bold text-xs sm:text-sm uppercase tracking-wider rounded-2xl transition-all shadow-luxury hover:scale-105 flex items-center justify-center gap-2.5 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        <span>{{ $buttonText }}</span>
                    </a>
                </div>

                <div class="mt-4 flex items-center justify-center md:justify-start gap-4 text-[11px] text-slate-400">
                    <span>✓ Format File: PDF Lengkap</span>
                    <span>✓ Akses Cepat & Bebas Biaya</span>
                </div>
            </div>
        </div>

    </div>
</section>
