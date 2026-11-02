@props(['data', 'pageTitle' => null])

@php
    $badge = $data['badge'] ?? 'Galeri Instalasi';
    $title = $data['title'] ?? 'Hasil Nyata di Lapangan, Bukan Sekadar Katalog';
    $subtitle = $data['subtitle'] ?? 'Foto instalasi asli: pager & fasad dengan roster, interior roster, bata ekspose, hingga ornamen dinding 3D.';
    $rawTheme = $data['bg_theme'] ?? ($data['theme'] ?? 'white');
    $theme = \App\Helpers\BlockTheme::resolve($rawTheme);
    
    $rawWa = \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
    $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
    if (str_starts_with($waNumber, '0')) {
        $waNumber = '62' . substr($waNumber, 1);
    }

    $ctaText = $data['cta_text'] ?? 'Minta Foto Instalasi via WhatsApp';
    $ctaUrl = $data['cta_url'] ?? "https://wa.me/{$waNumber}?text=Halo%20IndoRoster!%20Saya%20mau%20lihat%20portofolio%20foto%20instalasi%20lengkap.%20Boleh%20dikirimkan?";
    $ctaNote = $data['cta_note'] ?? 'Kami punya ratusan foto instalasi & portofolio lengkap — tinggal minta gratis via WhatsApp.';

    // Source data: bisa dari repeater item atau otomatis tarik dari App\Models\Gallery
    $useDbGallery = $data['use_db_gallery'] ?? false;
    $items = [];

    if ($useDbGallery) {
        $dbGalleries = \App\Models\Gallery::where('is_active', true)->with('media')->orderBy('sort_order')->take(5)->get();
        foreach ($dbGalleries as $idx => $g) {
            $firstMedia = $g->media->first();
            $mediaUrl = $firstMedia ? $firstMedia->file_url : null;
            $items[] = [
                'title' => $g->title,
                'text' => $g->description ?: ($g->location ?? ''),
                'image_url' => $mediaUrl,
                'is_large' => ($idx === 0),
            ];
        }
    }

    if (empty($items)) {
        $items = $data['items'] ?? [
            [
                'title' => 'Pager Rumah dengan Roster',
                'text' => 'Roster bikin pager makin estetik sekaligus tetap memberikan sirkulasi udara.',
                'image_url' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260885/189153683_1030631617471276_2071152964924271585_n_wbq1kg.jpg',
                'is_large' => true,
            ],
            [
                'title' => 'Fasad Rumah dengan Roster',
                'text' => 'Tampilan depan rumah jadi mewah dan beda dari tetangga.',
                'image_url' => 'https://res.cloudinary.com/indoroster/image/upload/v1765262980/2_zurmam.jpg',
                'is_large' => false,
            ],
            [
                'title' => 'Interior dengan Roster',
                'text' => 'Sekat ruang yang sejuk, cahaya lembut menembus motif roster.',
                'image_url' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260025/210781640_1049103868957384_7584920712298347840_n_jhvxju.jpg',
                'is_large' => false,
            ],
            [
                'title' => 'Instalasi Bata Ekspose',
                'text' => 'Tekstur bata alami tanpa plester — hangat dan berkarakter.',
                'image_url' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259930/47_dmjh8d.jpg',
                'is_large' => false,
            ],
            [
                'title' => 'Ornamen Dinding 3D',
                'text' => 'Aksen dinding 3D yang bikin ruangan terasa premium.',
                'image_url' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259923/34_li9387.jpg',
                'is_large' => false,
            ],
        ];
    }
@endphp

<section class="py-14 sm:py-20 lg:py-24 relative overflow-hidden font-sans {{ $theme->bgClasses }} transition-colors duration-300">
    <x-blocks._bg-theme :theme="$theme" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-14">
            @if(!empty($badge))
                <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-3.5 {{ $theme->badgeClass }}">
                    {{ $badge }}
                </span>
            @endif
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight leading-tight mb-4 {{ $theme->headingColor }}">
                {{ $title }}
            </h2>
            @if(!empty($subtitle))
                <p class="text-sm sm:text-base leading-relaxed {{ $theme->subColor }}">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        {{-- Masonry / Bento Grid Galeri (Foto 1 Besar di Kiri, 4 Foto di Kanan) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-5 sm:gap-6 mb-12">
            @php
                $firstItem = $items[0] ?? null;
                $restItems = array_slice($items, 1);
            @endphp

            {{-- Kartu Utama (Besar di Kiri - Span 6/7) --}}
            @if($firstItem)
                @php
                    $imgSrc1 = !empty($firstItem['image_upload']) ? asset('storage/' . $firstItem['image_upload']) : ($firstItem['image_url'] ?? null);
                    $galleryId1 = $firstItem['gallery_id'] ?? null;
                    if (!$imgSrc1 && $galleryId1) {
                        $gModel = \App\Models\Gallery::with('media')->find($galleryId1);
                        $imgSrc1 = $gModel?->media?->first()?->file_url;
                    }
                    if (!$imgSrc1) $imgSrc1 = 'https://res.cloudinary.com/indoroster/image/upload/v1765260885/189153683_1030631617471276_2071152964924271585_n_wbq1kg.jpg';
                @endphp
                <div class="lg:col-span-7 group relative overflow-hidden rounded-3xl border shadow-xl {{ $theme->cardBg }} flex flex-col min-h-[380px] sm:min-h-[440px] lg:min-h-[500px]">
                    <div class="relative w-full h-[320px] sm:h-[380px] lg:h-[420px] overflow-hidden">
                        <img 
                            src="{{ $imgSrc1 }}" 
                            alt="{{ $firstItem['title'] ?? 'Galeri Proyek' }}" 
                            class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                            loading="lazy"
                        >
                    </div>
                    <div class="p-5 sm:p-6 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 flex-grow flex flex-col justify-center">
                        <h3 class="text-base sm:text-lg font-bold mb-1 leading-snug {{ $theme->cardTitle }}">
                            {{ $firstItem['title'] ?? '' }}
                        </h3>
                        @if(!empty($firstItem['text']))
                            <p class="text-xs sm:text-sm {{ $theme->cardDesc }}">
                                {{ $firstItem['text'] }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            {{-- 4 Kartu Kecil di Kanan (Grid 2x2 - Span 5) --}}
            <div class="lg:col-span-5 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                @foreach($restItems as $item)
                    @php
                        $imgSrc = !empty($item['image_upload']) ? asset('storage/' . $item['image_upload']) : ($item['image_url'] ?? null);
                        $galleryId = $item['gallery_id'] ?? null;
                        if (!$imgSrc && $galleryId) {
                            $gModel = \App\Models\Gallery::with('media')->find($galleryId);
                            $imgSrc = $gModel?->media?->first()?->file_url;
                        }
                        if (!$imgSrc) $imgSrc = 'https://res.cloudinary.com/indoroster/image/upload/v1765262980/2_zurmam.jpg';
                    @endphp
                    <div class="group relative overflow-hidden rounded-2xl border shadow-md {{ $theme->cardBg }} flex flex-col">
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img 
                                src="{{ $imgSrc }}" 
                                alt="{{ $item['title'] ?? 'Galeri Proyek' }}" 
                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                loading="lazy"
                            >
                        </div>
                        <div class="p-3.5 sm:p-4 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 flex-grow">
                            <h4 class="text-xs sm:text-sm font-bold mb-0.5 leading-snug {{ $theme->cardTitle }}">
                                {{ $item['title'] ?? '' }}
                            </h4>
                            @if(!empty($item['text']))
                                <p class="text-[11px] leading-relaxed line-clamp-2 {{ $theme->cardDesc }}">
                                    {{ $item['text'] }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        {{-- CTA Banner --}}
        @if(!empty($ctaText))
            <div class="text-center rounded-3xl p-6 sm:p-8 border {{ $theme->cardBg }} max-w-3xl mx-auto shadow-sm">
                @if(!empty($ctaNote))
                    <p class="text-xs sm:text-sm md:text-base font-medium mb-4 {{ $theme->cardTitle }}">
                        {{ $ctaNote }}
                    </p>
                @endif
                <a href="{{ $ctaUrl }}" target="_blank" rel="noopener"
                   class="inline-flex items-center gap-2.5 px-7 py-3.5 rounded-full font-bold text-xs sm:text-sm bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg shadow-emerald-600/25 transition-transform hover:scale-105 active:scale-95">
                    <svg class="w-4.5 h-4.5 fill-current" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.584 1.777.818 2.791.818 3.179 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.77-5.767-5.772zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/>
                    </svg>
                    <span>{{ $ctaText }}</span>
                </a>
            </div>
        @endif
    </div>
</section>
