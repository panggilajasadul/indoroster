@props(['data'])

@php
    $badge = $data['badge'] ?? 'Penawaran Spesial Pabrik';
    $title = $data['title'] ?? 'Dapatkan Diskon Khusus Pemesanan Proyek & Volume Besar';
    $description = $data['description'] ?? 'Konsultasikan kebutuhan roster dinding bangunan Anda langsung dengan tim teknis pabrik kami.';
    $buttonText = $data['button_text'] ?? 'Hubungi WhatsApp Pabrik';
    $buttonUrl = $data['button_url'] ?? '';
    $endDate = $data['end_date'] ?? '';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'accent');
    
    if (empty($buttonUrl)) {
        $whatsappNumber = \App\Models\SiteSetting::getValue('whatsapp_number', '081389709847');
        $formattedNum = preg_replace('/[^0-9]/', '', $whatsappNumber);
        if (str_starts_with($formattedNum, '0')) {
            $formattedNum = '62' . substr($formattedNum, 1);
        }
        $buttonUrl = 'https://wa.me/' . $formattedNum . '?text=' . urlencode('Halo IndoRoster, saya tertarik dengan penawaran promo pabrik.');
    }
@endphp

<section class="py-16 sm:py-20 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-8 lg:gap-14">
            
            <!-- Content -->
            <div class="flex-1 text-center lg:text-left">
                @if($badge)
                <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full {{ $theme->badgeClass }} text-xs font-black uppercase tracking-wider mb-4">
                    <span>🔥 {{ $badge }}</span>
                </div>
                @endif
                @if($title)
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-display tracking-tight leading-tight mb-3.5 {{ $theme->headingColor }}">{!! $title !!}</h2>
                @endif
                @if($description)
                <p class="text-sm sm:text-base {{ $theme->subColor }} max-w-xl leading-relaxed">{!! $description !!}</p>
                @endif
            </div>

            <!-- CTA & Timer Side -->
            <div class="shrink-0 flex flex-col items-center gap-5">
                @if($endDate)
                <div x-data="countdown('{{ $endDate }}')" x-init="startCountdown()" class="flex items-center gap-2.5 bg-black/40 backdrop-blur-md border border-white/15 p-3 rounded-2xl">
                    <div class="text-center px-2">
                        <div class="text-xl sm:text-2xl font-black font-display text-white" x-text="days">00</div>
                        <div class="text-[9px] uppercase tracking-wider font-bold text-slate-400">Hari</div>
                    </div>
                    <span class="text-lg font-bold text-slate-500">:</span>
                    <div class="text-center px-2">
                        <div class="text-xl sm:text-2xl font-black font-display text-white" x-text="hours">00</div>
                        <div class="text-[9px] uppercase tracking-wider font-bold text-slate-400">Jam</div>
                    </div>
                    <span class="text-lg font-bold text-slate-500">:</span>
                    <div class="text-center px-2">
                        <div class="text-xl sm:text-2xl font-black font-display text-white" x-text="minutes">00</div>
                        <div class="text-[9px] uppercase tracking-wider font-bold text-slate-400">Menit</div>
                    </div>
                    <span class="text-lg font-bold text-slate-500">:</span>
                    <div class="text-center px-2">
                        <div class="text-xl sm:text-2xl font-black font-display text-terra-400" x-text="seconds">00</div>
                        <div class="text-[9px] uppercase tracking-wider font-bold text-slate-400">Detik</div>
                    </div>
                </div>
                @endif

                <a href="{{ $buttonUrl }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-3 px-8 sm:px-10 py-4 bg-terra-500 hover:bg-terra-400 text-white font-bold text-sm uppercase tracking-wider rounded-2xl hover:scale-105 active:scale-95 transition-all shadow-luxury">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    <span>{{ $buttonText }}</span>
                </a>
            </div>
        </div>
    </div>
</section>

@if($endDate)
<script>
function countdown(endDate) {
    return {
        days: '00', hours: '00', minutes: '00', seconds: '00',
        startCountdown() {
            const self = this;
            const end = new Date(endDate).getTime();
            function update() {
                const now = new Date().getTime();
                const diff = end - now;
                if (diff <= 0) { self.days = '00'; self.hours = '00'; self.minutes = '00'; self.seconds = '00'; return; }
                self.days = String(Math.floor(diff / (1000 * 60 * 60 * 24))).padStart(2, '0');
                self.hours = String(Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                self.minutes = String(Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                self.seconds = String(Math.floor((diff % (1000 * 60)) / 1000)).padStart(2, '0');
            }
            update();
            setInterval(update, 1000);
        }
    };
}
</script>
@endif
