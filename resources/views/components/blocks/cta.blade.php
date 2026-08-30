@props(['data'])

@php
    $badge = $data['badge'] ?? 'Siap Memulai Proyek Anda?';
    $title = $data['title'] ?? 'Konsultasikan Kebutuhan Roster Beton Minimalis dengan Pabrik Kami';
    $subtitle = $data['subtitle'] ?? ($data['description'] ?? 'Dapatkan harga pabrik tangan pertama, sampel produk, dan perhitungan kebutuhan presisi langsung dari spesialis roster kami.');
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

    // Parse buttons
    $buttons = $data['buttons'] ?? [];
    if (empty($buttons)) {
        // Fallback for single button or partner_cta buttons
        if (!empty($data['button_text'])) {
            $buttons[] = [
                'text' => $data['button_text'],
                'url' => $data['button_url'] ?? '',
                'style' => 'primary',
                'icon' => 'whatsapp',
                'is_new_tab' => true,
            ];
        }
        if (!empty($data['cta_text_1'])) {
            $buttons[] = [
                'text' => $data['cta_text_1'],
                'url' => $data['cta_url_1'] ?? '/register',
                'style' => 'primary',
                'icon' => 'user',
                'is_new_tab' => false,
            ];
        }
        if (!empty($data['cta_text_2'])) {
            $buttons[] = [
                'text' => $data['cta_text_2'],
                'url' => $data['cta_url_2'] ?? '',
                'style' => 'whatsapp',
                'icon' => 'whatsapp',
                'is_new_tab' => true,
            ];
        }
        
        // Default fallback if still empty
        if (empty($buttons)) {
            $buttons = [
                [
                    'text' => 'Hubungi WhatsApp Pabrik',
                    'url' => '',
                    'style' => 'whatsapp',
                    'icon' => 'whatsapp',
                    'is_new_tab' => true,
                ],
                [
                    'text' => 'Buka Katalog Lengkap',
                    'url' => '/katalog',
                    'style' => 'secondary',
                    'icon' => 'catalog',
                    'is_new_tab' => false,
                ]
            ];
        }
    }

    $whatsappNumber = \App\Models\SiteSetting::getValue('whatsapp_number', '081389709847');
    $formattedNum = preg_replace('/[^0-9]/', '', $whatsappNumber);
    if (str_starts_with($formattedNum, '0')) {
        $formattedNum = '62' . substr($formattedNum, 1);
    }
    $defaultWaUrl = 'https://wa.me/' . $formattedNum . '?text=' . urlencode('Halo IndoRoster, saya ingin konsultasi kebutuhan roster untuk proyek saya.');
@endphp

<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10" data-motion="scale">
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
            <p class="text-base sm:text-lg md:text-xl {{ $theme->subColor }} max-w-3xl mb-10 leading-relaxed">
                {!! $subtitle !!}
            </p>
            @endif

            <div class="w-full flex flex-wrap items-center {{ $btnAlign }} gap-4 sm:gap-5">
                @foreach($buttons as $btn)
                @php
                    $rawUrl = $btn['url'] ?? '';
                    $btnUrl = empty($rawUrl) || str_contains($rawUrl, 'wa.me') ? ($rawUrl ?: $defaultWaUrl) : (str_starts_with($rawUrl, 'http') || str_starts_with($rawUrl, '/') || str_starts_with($rawUrl, '#') ? $rawUrl : url($rawUrl));
                    $btnStyle = $btn['style'] ?? 'primary';
                    $btnIcon = $btn['icon'] ?? 'none';
                    $isNewTab = !empty($btn['is_new_tab']) || str_contains($btnUrl, 'wa.me');

                    $styleClass = match($btnStyle) {
                        'whatsapp' => 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg hover:shadow-emerald-600/30 ring-1 ring-emerald-500/50',
                        'secondary' => $theme->btnSecondary,
                        'dark' => 'bg-slate-900 hover:bg-slate-950 text-white border border-slate-700 shadow-md',
                        'white' => 'bg-white hover:bg-slate-100 text-slate-900 font-bold shadow-luxury ring-1 ring-slate-900/10',
                        default => $theme->btnPrimary,
                    };
                @endphp
                <a href="{{ $btnUrl }}" 
                   @if($isNewTab) target="_blank" rel="noopener noreferrer" @endif
                   data-magnetic 
                   class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 sm:px-10 py-4 sm:py-4.5 font-bold text-sm uppercase tracking-wider rounded-2xl transition-all duration-300 transform hover:-translate-y-0.5 cursor-pointer {{ $styleClass }} group">
                    
                    @if($btnIcon === 'whatsapp' || str_contains($btnUrl, 'wa.me'))
                        <svg class="w-5 h-5 fill-current shrink-0" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    @elseif($btnIcon === 'catalog')
                        <svg class="w-5 h-5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    @elseif($btnIcon === 'user')
                        <svg class="w-5 h-5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    @elseif($btnIcon === 'truck')
                        <svg class="w-5 h-5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                    @elseif($btnIcon === 'calculator')
                        <svg class="w-5 h-5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    @elseif($btnIcon === 'phone')
                        <svg class="w-5 h-5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                    @endif

                    <span>{{ $btn['text'] }}</span>

                    @if($btnIcon === 'arrow' || empty($btnIcon) || $btnIcon === 'none')
                        <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
