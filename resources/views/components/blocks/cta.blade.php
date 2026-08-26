@props(['data'])

@php
    $badge = $data['badge'] ?? 'Siap Memulai Proyek Anda?';
    $title = $data['title'] ?? 'Konsultasikan Kebutuhan Roster Beton Minimalis dengan Pabrik Kami';
    $subtitle = $data['subtitle'] ?? 'Dapatkan harga pabrik tangan pertama, sampel produk, dan perhitungan kebutuhan presisi langsung dari spesialis roster kami.';
    $buttonText = $data['button_text'] ?? 'Hubungi WhatsApp Pabrik';
    $buttonUrl = $data['button_url'] ?? '';
    $alignment = $data['alignment'] ?? 'center';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'dark');
    
    $containerAlign = match($alignment) {
        'left' => 'text-left flex flex-col items-start',
        'right' => 'text-right flex flex-col items-end',
        default => 'text-center flex flex-col items-center mx-auto'
    };
    $btnAlign = match($alignment) {
        'left' => 'justify-start',
        'right' => 'justify-end',
        default => 'justify-center'
    };

    if (empty($buttonUrl)) {
        $whatsappNumber = \App\Models\SiteSetting::getValue('whatsapp_number', '081389709847');
        $formattedNum = preg_replace('/[^0-9]/', '', $whatsappNumber);
        if (str_starts_with($formattedNum, '0')) {
            $formattedNum = '62' . substr($formattedNum, 1);
        }
        $buttonUrl = 'https://wa.me/' . $formattedNum . '?text=' . urlencode('Halo IndoRoster, saya ingin konsultasi kebutuhan roster untuk proyek saya.');
    }
@endphp

<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10" data-motion="scale">
        <div class="{{ $containerAlign }}">
            @if($badge)
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full {{ $theme->badgeClass }} font-bold text-xs uppercase tracking-widest mb-6">
                <span>{{ $badge }}</span>
            </div>
            @endif
            
            <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black font-display tracking-tight leading-tight mb-6 {{ $theme->headingColor }}">
                {!! $title !!}
            </h2>
            
            @if($subtitle)
            <p class="text-base sm:text-lg {{ $theme->subColor }} max-w-2xl mb-10 leading-relaxed">
                {!! $subtitle !!}
            </p>
            @endif

            <div class="w-full flex flex-col sm:flex-row items-center {{ $btnAlign }} gap-4 sm:gap-6">
                <a href="{{ $buttonUrl }}" target="_blank" data-magnetic class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 sm:px-10 py-4 sm:py-5 font-black text-sm uppercase tracking-wider rounded-2xl transition-all {{ $theme->btnPrimary }} group cursor-pointer">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    <span>{{ $buttonText }}</span>
                </a>
                <a href="{{ route('catalog') }}" data-magnetic class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-8 py-4 sm:py-5 border font-bold text-sm uppercase tracking-wider rounded-2xl transition-all cursor-pointer {{ $theme->btnSecondary }}">
                    <span>Buka Katalog Lengkap</span>
                </a>
            </div>
        </div>
    </div>
</section>
