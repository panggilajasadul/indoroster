@props(['data'])

@php
    $badge = $data['badge'] ?? 'Armada Pengiriman Mandiri';
    $title = $data['title'] ?? 'Jangkauan Pengiriman Seluruh Jabodetabek & Indonesia';
    $content = $data['content'] ?? 'Pengiriman langsung dari pabrik dengan packing aman bersegel dan garansi ganti baru 100% jika terjadi kerusakan dalam perjalanan.';
    $videoUrl = $data['video_url'] ?? '';
    $buttonText = $data['button_text'] ?? 'Cek Estimasi Ongkir';
    $buttonUrl = $data['button_url'] ?? '';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');

    if (empty($buttonUrl)) {
        $whatsappNumber = \App\Models\SiteSetting::getValue('whatsapp_number', '081389709847');
        $formattedNum = preg_replace('/[^0-9]/', '', $whatsappNumber);
        if (str_starts_with($formattedNum, '0')) {
            $formattedNum = '62' . substr($formattedNum, 1);
        }
        $buttonUrl = 'https://wa.me/' . $formattedNum . '?text=' . urlencode('Halo IndoRoster, saya ingin konsultasi ongkos kirim ke alamat saya.');
    }
@endphp

<section class="py-20 sm:py-24 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="rounded-3xl p-8 sm:p-12 md:p-16 border shadow-soft-lg flex flex-col lg:flex-row items-center gap-10 lg:gap-14 {{ $theme->cardBg }}">
            
            <div class="flex-1">
                @if($badge)
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} font-bold text-xs uppercase tracking-wider mb-5">
                    <svg class="w-4 h-4 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                    <span>{{ $badge }}</span>
                </div>
                @endif
                
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-black font-display {{ $theme->headingColor }} tracking-tight leading-tight mb-5">{!! $title !!}</h2>
                
                <p class="text-sm sm:text-base {{ $theme->subColor }} mb-6 leading-relaxed">
                    {!! $content !!}
                </p>

                @php
                    $blockLocations = class_exists(\App\Models\SeoLocation::class)
                        ? \App\Models\SeoLocation::where('seo_enabled', true)->orderBy('priority', 'asc')->take(16)->get()
                        : collect();
                @endphp

                @if($blockLocations->count() > 0)
                <div class="mb-8">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider block mb-3">📍 Wilayah Layanan Pengiriman Cepat:</span>
                    <div class="flex flex-wrap items-center gap-2">
                        @foreach($blockLocations as $tLoc)
                            <a href="{{ route('location.detail', $tLoc->slug) }}" class="px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-terra-500 hover:text-terra-600 dark:hover:text-terra-400 rounded-lg text-xs font-medium text-slate-700 dark:text-slate-300 transition shadow-sm">
                                Roster {{ $tLoc->name }}
                            </a>
                        @endforeach
                        <a href="{{ route('location.index') }}" class="px-3 py-1.5 bg-terra-50 dark:bg-terra-950/40 text-terra-600 dark:text-terra-400 hover:bg-terra-500 hover:text-white rounded-lg text-xs font-bold transition">
                            Semua Wilayah &rarr;
                        </a>
                    </div>
                </div>
                @endif

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                    <a href="{{ $buttonUrl }}" target="_blank" class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-xl font-bold text-sm transition-all shadow-md group {{ $theme->btnPrimary }}">
                        <svg class="w-4 h-4 text-emerald-400 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>{{ $buttonText ?: 'Hubungi Sales Pabrik' }}</span>
                    </a>
                    <a href="{{ route('order.tracking') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl border font-bold text-sm transition-all {{ $theme->btnSecondary }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8h4l3 3v5a1 1 0 01-1 1h-1m-6 0h-2" /></svg>
                        <span>Lacak Pesanan</span>
                    </a>
                </div>
            </div>

            <div class="flex-1 w-full relative">
                <div class="rounded-2xl shadow-luxury w-full overflow-hidden aspect-[4/3] bg-slate-900 border-2 border-white">
                    @php
                        $finalVideoUrl = !empty($data['video_upload']) ? asset('storage/' . $data['video_upload']) : $videoUrl;
                        $ext = pathinfo(parse_url($finalVideoUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
                        $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg', 'mov', 'm4v', 'avi', 'mkv', '3gp']) || str_contains(strtolower($finalVideoUrl), 'video');
                    @endphp
                    @if($isVideo)
                    <video class="w-full h-full object-cover" autoplay loop muted playsinline>
                        <source src="{{ $finalVideoUrl }}">
                    </video>
                    @elseif($finalVideoUrl)
                    <img src="{{ $finalVideoUrl }}" class="w-full h-full object-cover" alt="Pengiriman Roster">
                    @else
                    <div class="w-full h-full flex flex-col items-center justify-center p-6 text-center text-slate-300">
                        <div class="w-14 h-14 rounded-2xl bg-terra-500/20 text-terra-400 flex items-center justify-center mb-3">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8h4l3 3v5a1 1 0 01-1 1h-1m-6 0h-2" />
                            </svg>
                        </div>
                        <span class="font-bold text-white text-base">Armada Truk & Pick-Up Khusus</span>
                        <span class="text-xs text-slate-400 mt-1">Pengiriman aman terjadwal langsung ke lokasi proyek</span>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
