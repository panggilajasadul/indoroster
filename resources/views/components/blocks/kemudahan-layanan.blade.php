@props(['data'])

@php
    $title = $data['title'] ?? 'Kemudahan Layanan';
    $subtitle = $data['subtitle'] ?? null;
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');

    $columns = $data['columns'] ?? [];
    if (empty($columns)) {
        $columns = [
            [
                'title' => 'Layanan Konsultasi Produk',
                'items' => [
                    ['text' => 'Konsultasi pemilihan model roster'],
                    ['text' => 'Rekomendasi ukuran sesuai kebutuhan bangunan'],
                    ['text' => 'Panduan penggunaan roster untuk ventilasi dan fasad'],
                    ['text' => 'Saran kombinasi roster dengan desain rumah'],
                    ['text' => 'Konsultasi kebutuhan roster untuk proyek pembangunan'],
                ],
            ],
            [
                'title' => 'Ketersediaan Produk dan Pemesanan',
                'items' => [
                    ['text' => 'Pilihan motif roster lengkap'],
                    ['text' => 'Melayani pembelian satuan dan partai'],
                    ['text' => 'Stok tersedia langsung dari sentra produksi'],
                    ['text' => 'Pemesanan mudah dan responsif'],
                    ['text' => 'Siap melayani kebutuhan rumah dan proyek'],
                ],
            ],
            [
                'title' => 'Layanan Pengiriman dan Dukungan Teknis',
                'items' => [
                    ['text' => 'Pengiriman area Purwakarta dan sekitarnya'],
                    ['text' => 'Melayani pengiriman luar kota'],
                    ['text' => 'Pengemasan aman dan rapi'],
                    ['text' => 'Koordinasi pengiriman sesuai jadwal pelanggan'],
                    ['text' => 'Dukungan informasi pemasangan roster'],
                ],
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

        {{-- 3 Columns Checklist Layout --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
            @foreach($columns as $col)
                <div class="flex flex-col">
                    <h3 class="text-base sm:text-lg font-bold {{ $theme->headingColor }} mb-5 tracking-tight">
                        {{ $col['title'] ?? '' }}
                    </h3>

                    <ul class="divide-y divide-dashed divide-stone-200 dark:divide-neutral-800">
                        @foreach($col['items'] ?? [] as $item)
                            @php
                                $itemText = is_array($item) ? ($item['text'] ?? '') : $item;
                            @endphp
                            @if(!empty($itemText))
                                <li class="py-3.5 flex items-start gap-3">
                                    {{-- Emerald Green Checkmark Circle --}}
                                    <div class="flex-shrink-0 mt-0.5 w-5 h-5 rounded-full border border-emerald-500/60 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                    </div>
                                    <span class="text-xs sm:text-sm {{ $theme->isDark ? 'text-neutral-300' : 'text-stone-700' }} leading-relaxed">
                                        {{ $itemText }}
                                    </span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>

