@props(['data'])

@php
    $badge = $data['badge'] ?? 'PETA LOKASI WORKSHOP';
    $title = $data['title'] ?? 'Kunjungi Workshop & Pabrik Langsung';
    $subtitle = $data['subtitle'] ?? 'Kami menyambut kedatangan arsitek, kontraktor, dan calon pemilik rumah untuk melihat langsung stok dan proses produksi di workshop kami.';
    $address = $data['address'] ?? 'Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165';
    $hours = $data['hours'] ?? 'Senin – Sabtu, 08.00 – 17.00 WIB';
    $mapEmbed = $data['map_embed'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.671569421715!2d107.35935457499427!3d-6.668991693325996!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69073a5c4870d1%3A0x9daaab3cd6ae595d!2sIndoroster%20-%20Produsen%20Roster%20Minimalis%20%26%20Aneka%20Bata%20Murah!5e0!3m2!1sid!2sid!4v1740565000000!5m2!1sid!2sid';
    $mapUrl = $data['map_url'] ?? 'https://www.google.com/maps/place/Indoroster+-+Produsen+Roster+Minimalis+%26+Aneka+Bata+Murah/@-6.6689917,107.3619295,19z/data=!4m6!3m5!1s0x2e69073a5c4870d1:0x9daaab3cd6ae595d!8m2!3d-6.6689917!4d107.3619295!16s%2Fg%2F11njz2_9sv';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'slate');
@endphp

<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden font-sans">
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

            @if(!empty($subtitle))
            <p class="text-sm sm:text-base {{ $theme->subColor }} leading-relaxed">
                {{ $subtitle }}
            </p>
            @endif
        </div>

        <!-- Map & Info Card Container -->
        <div class="max-w-6xl mx-auto rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-luxury bg-white dark:bg-slate-900 grid grid-cols-1 lg:grid-cols-12">
            
            <!-- Left Info Panel -->
            <div class="lg:col-span-5 p-8 sm:p-12 flex flex-col justify-between">
                <div>
                    <h3 class="text-xl font-bold {{ $theme->cardTitle }} mb-6">Pusat Pabrik & Gudang Utama</h3>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-terra-500/10 text-terra-500 flex items-center justify-center shrink-0 mt-0.5">
                                📍
                            </div>
                            <div>
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Alamat Fisik</div>
                                <div class="text-sm sm:text-base font-semibold text-slate-800 dark:text-slate-200 mt-1 leading-snug">
                                    {{ $address }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-terra-500/10 text-terra-500 flex items-center justify-center shrink-0 mt-0.5">
                                ⏰
                            </div>
                            <div>
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Jam Operasional Pabrik</div>
                                <div class="text-sm sm:text-base font-semibold text-slate-800 dark:text-slate-200 mt-1">
                                    {{ $hours }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-terra-500/10 text-terra-500 flex items-center justify-center shrink-0 mt-0.5">
                                📱
                            </div>
                            <div>
                                <div class="text-xs font-bold uppercase tracking-wider text-slate-400">Konfirmasi Kunjungan</div>
                                <div class="text-sm sm:text-base font-semibold text-terra-500 mt-1">
                                    0813-8970-9847
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row gap-3">
                    <a href="{{ $mapUrl }}" target="_blank" class="px-6 py-3.5 bg-terra-500 hover:bg-terra-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-md text-center flex items-center justify-center gap-2">
                        <span>Buka Rute Google Maps</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                    </a>
                </div>
            </div>

            <!-- Right: Interactive Iframe Map -->
            <div class="lg:col-span-7 h-[380px] lg:h-auto min-h-[380px] bg-slate-100 dark:bg-slate-950 relative">
                <iframe 
                    src="{{ $mapEmbed }}" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    class="w-full h-full min-h-[380px]">
                </iframe>
            </div>

        </div>

    </div>
</section>
