@props(['data'])

@php
    $title = $data['title'] ?? 'Mengapa Memilih Kami?';
    $subtitle = $data['subtitle'] ?? null;
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'light');

    $cards = $data['cards'] ?? [];
    if (empty($cards)) {
        $cards = [
            [
                'icon' => 'cube',
                'title' => 'Pilihan Model Roster Lengkap',
                'description' => 'Tersedia berbagai motif roster ventilasi dan dekoratif yang cocok digunakan untuk fasad rumah, pagar, sekat ruangan, hingga kebutuhan proyek bangunan.',
            ],
            [
                'icon' => 'thumb',
                'title' => 'Material Kuat dan Tahan Lama',
                'description' => 'Produk roster dibuat dari material pilihan yang kokoh dan tahan terhadap perubahan cuaca sehingga aman digunakan untuk interior maupun eksterior bangunan.',
            ],
            [
                'icon' => 'grid',
                'title' => 'Melayani Pembelian Satuan dan Proyek',
                'description' => 'Rumah Roster melayani kebutuhan pembelian dalam jumlah kecil hingga skala besar untuk rumah pribadi, kontraktor, maupun proyek pembangunan.',
            ],
            [
                'icon' => 'price',
                'title' => 'Harga Kompetitif Langsung dari Produsen',
                'description' => 'Sebagai supplier yang berada di sentra produksi roster Purwakarta, Rumah Roster menghadirkan harga yang lebih bersaing dengan kualitas produk yang tetap terjaga.',
            ],
            [
                'icon' => 'truck',
                'title' => 'Pengiriman Mudah dan Responsif',
                'description' => 'Melayani pengiriman area Purwakarta dan sekitarnya hingga luar kota dengan proses pemesanan yang cepat dan koordinasi pengiriman yang rapi.',
            ],
            [
                'icon' => 'home',
                'title' => 'Cocok untuk Berbagai Kebutuhan Bangunan',
                'description' => 'Roster dapat digunakan untuk ventilasi dinding, pagar rumah, sekat ruangan, fasad bangunan, hingga area taman sehingga menjadi solusi material yang fleksibel dan fungsional.',
            ],
        ];
    }
@endphp

<section class="py-16 sm:py-20 lg:py-24 {{ $theme->bgClasses }} relative overflow-hidden">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold font-display {{ $theme->headingColor }} tracking-tight">
                {{ $title }}
            </h2>
            {{-- Green Accent Line --}}
            <div class="w-12 h-1 bg-emerald-600 rounded-full mx-auto mt-3 sm:mt-4"></div>

            @if($subtitle)
                <p class="{{ $theme->subColor }} text-sm sm:text-base mt-4 max-w-2xl mx-auto leading-relaxed">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        {{-- 6 Modern Clean Cards Grid (3 cols desktop, 2 cols tablet, 1 col mobile) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7">
            @foreach($cards as $card)
                @php
                    $iconType = $card['icon'] ?? 'cube';
                    $cardTitle = $card['title'] ?? '';
                    $cardDesc = $card['description'] ?? '';
                @endphp
                <div class="bg-white dark:bg-neutral-900 rounded-2xl p-7 sm:p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.06)] hover:shadow-lg transition-all duration-300 border border-stone-100 dark:border-neutral-800 flex flex-col items-center text-center group">
                    {{-- Icon Container in Forest/Emerald Green --}}
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl flex items-center justify-center text-emerald-700 dark:text-emerald-400 mb-6 group-hover:scale-110 transition-transform duration-300">
                        @if($iconType === 'cube')
                            {{-- Geometric / Pattern / Mould Roster Icon --}}
                            <svg class="w-10 h-10 sm:w-11 sm:h-11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="3" rx="2" />
                                <rect width="6" height="6" x="9" y="9" />
                                <path d="M3 9h6M9 3v6M15 3v6M15 9h6M3 15h6M9 15v6M15 15v6M15 15h6" stroke-width="1.5" />
                            </svg>
                        @elseif($iconType === 'thumb')
                            {{-- Quality / Strong Thumb Up Icon --}}
                            <svg class="w-10 h-10 sm:w-11 sm:h-11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M7 10v12" />
                                <path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2a3.13 3.13 0 0 1 3 3.88Z" />
                            </svg>
                        @elseif($iconType === 'grid')
                            {{-- Grid / Roster Screen Architecture Icon --}}
                            <svg class="w-10 h-10 sm:w-11 sm:h-11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="18" height="18" x="3" y="3" rx="2" />
                                <path d="M3 9h18M3 15h18M9 3v18M15 3v18" stroke-dasharray="2 2" stroke-width="1.5" />
                            </svg>
                        @elseif($iconType === 'price')
                            {{-- Tag / Bill Price Direct Producer Icon --}}
                            <svg class="w-10 h-10 sm:w-11 sm:h-11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="14" height="18" x="5" y="3" rx="2" />
                                <path d="M9 8h6M9 12h6M11 16h2" />
                                <path d="M12 6v2M12 16v2" />
                            </svg>
                        @elseif($iconType === 'truck')
                            {{-- Delivery Truck Fast & Responsive Icon --}}
                            <svg class="w-10 h-10 sm:w-11 sm:h-11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2" />
                                <path d="M15 18H9" />
                                <path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14v10Z" />
                                <circle cx="17" cy="18.5" r="2.5" />
                                <circle cx="7" cy="18.5" r="2.5" />
                            </svg>
                        @elseif($iconType === 'home')
                            {{-- House / Building Need Icon --}}
                            <svg class="w-10 h-10 sm:w-11 sm:h-11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 10.5 12 3l9 7.5" />
                                <path d="M5 9v11a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V9" />
                                <path d="M9 21v-7a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v7" />
                            </svg>
                        @else
                            <span class="text-3xl">{{ $iconType }}</span>
                        @endif
                    </div>

                    {{-- Card Title --}}
                    <h3 class="text-base sm:text-lg font-bold text-stone-900 dark:text-white mb-3 tracking-tight">
                        {{ $cardTitle }}
                    </h3>

                    {{-- Card Description --}}
                    <p class="text-xs sm:text-sm text-stone-600 dark:text-neutral-400 leading-relaxed font-normal">
                        {{ $cardDesc }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

