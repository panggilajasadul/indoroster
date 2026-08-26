@props(['data'])

@php
    $badge = $data['badge'] ?? 'PAKET HEMAT SIAP PASANG';
    $title = $data['title'] ?? 'Pilihan Paket Bundling Fasad & Pagar Roster';
    $subtitle = $data['subtitle'] ?? 'Hemat biaya dan praktis tanpa repot hitung satuan. Sudah termasuk rekomendasi perekat dan proteksi kirim.';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');

    $defaultPackages = [
        [
            'name' => 'Paket Fasad Rumah Minimalis',
            'badge' => 'POPULER RUMAH TINGGAL',
            'is_featured' => false,
            'qty' => '150 Pcs Roster',
            'coverage' => 'Cocok untuk luas dinding ±6 m²',
            'features' => [
                'Pilihan Bebas Motif Roster Minimalis',
                'Cetak Tumbuk Padat Halus 2 Muka',
                'Bonus 3 Sak Semen Perekat Instan',
                'Garansi Pecah Ganti Baru 100%',
            ],
            'button_text' => 'Pesan Paket Fasad',
            'button_url' => 'https://wa.me/6281389709847?text=' . urlencode('Halo IndoRoster, saya tertarik dengan Paket Fasad Rumah Minimalis (150 pcs).'),
        ],
        [
            'name' => 'Paket Pagar Industrial Modern',
            'badge' => 'BEST VALUE REKOMENDASI',
            'is_featured' => true,
            'qty' => '300 Pcs Roster',
            'coverage' => 'Cocok untuk pagar depan & samping ±12 m²',
            'features' => [
                'Bebas Kombinasi 2 Motif Sekaligus',
                'Beton Padat Tahan Cuaca Panas & Hujan',
                'Bonus 6 Sak Semen Perekat Instan',
                'Diskon Subsidi Ongkos Kirim Jabodetabek',
                'Garansi Pecah Ganti Baru 100%',
            ],
            'button_text' => 'Pesan Paket Pagar',
            'button_url' => 'https://wa.me/6281389709847?text=' . urlencode('Halo IndoRoster, saya tertarik dengan Paket Pagar Industrial Modern (300 pcs).'),
        ],
        [
            'name' => 'Paket Proyek & Developer',
            'badge' => 'HARGA GROSIR PABRIK',
            'is_featured' => false,
            'qty' => '1.000+ Pcs Roster',
            'coverage' => 'Perumahan, Ruko, Cafe, Kantor',
            'features' => [
                'Harga Khusus Tender / Kontraktor',
                'Pengiriman Truk Fuso Langsung ke Proyek',
                'Prioritas Antrean Cetak & QC Khusus',
                'Faktur Pajak & Dokumen Resmi Lengkap',
                'Garansi Pengiriman Tepat Waktu',
            ],
            'button_text' => 'Konsultasi Proyek Besar',
            'button_url' => 'https://wa.me/6281389709847?text=' . urlencode('Halo IndoRoster, saya dari pihak kontraktor/developer ingin penawaran Paket Proyek Besar 1000+ pcs.'),
        ],
    ];

    $packages = !empty($data['packages']) ? $data['packages'] : $defaultPackages;
@endphp

<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden font-sans">
    <x-blocks._bg-theme :theme="$theme" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
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

        <!-- 3 Pricing Package Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
            @foreach($packages as $pkg)
            @php
                $isFeatured = !empty($pkg['is_featured']);
            @endphp
            <div class="rounded-3xl p-8 sm:p-10 border transition-all duration-500 hover:-translate-y-2 flex flex-col justify-between relative {{ $isFeatured ? 'bg-slate-950 text-white border-terra-500 shadow-luxury ring-2 ring-terra-500/50' : $theme->cardBg . ' shadow-soft-xs hover:shadow-luxury' }}">
                @if($isFeatured)
                <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full bg-terra-500 text-white text-[10px] font-black uppercase tracking-widest shadow-md">
                    {{ $pkg['badge'] ?? 'BEST SELLER' }}
                </div>
                @endif

                <div>
                    @if(!$isFeatured && !empty($pkg['badge']))
                    <div class="text-[10px] font-bold text-terra-500 uppercase tracking-wider mb-2">
                        {{ $pkg['badge'] }}
                    </div>
                    @endif

                    <h3 class="text-2xl font-black {{ $isFeatured ? 'text-white' : $theme->cardTitle }} mb-2 leading-tight">
                        {{ $pkg['name'] ?? '' }}
                    </h3>

                    <div class="text-xs {{ $isFeatured ? 'text-slate-300' : 'text-slate-500' }} mb-6">
                        {{ $pkg['coverage'] ?? '' }}
                    </div>

                    <!-- Big Qty -->
                    <div class="py-4 px-5 rounded-2xl {{ $isFeatured ? 'bg-white/10' : 'bg-terra-500/10' }} mb-8 text-center">
                        <div class="text-3xl font-black {{ $isFeatured ? 'text-terra-400' : 'text-terra-600' }}">
                            {{ $pkg['qty'] ?? '' }}
                        </div>
                    </div>

                    <!-- Features List -->
                    <ul class="space-y-3.5 mb-8 text-xs sm:text-sm">
                        @foreach(($pkg['features'] ?? []) as $feat)
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-emerald-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                            <span class="{{ $isFeatured ? 'text-slate-200' : 'text-slate-700 dark:text-slate-300' }} leading-relaxed">{{ $feat }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- CTA Button -->
                <a href="{{ $pkg['button_url'] ?? '#' }}" target="_blank" class="w-full py-4 px-6 rounded-2xl font-bold text-xs uppercase tracking-wider text-center transition-all shadow-md flex items-center justify-center gap-2 {{ $isFeatured ? 'bg-terra-500 hover:bg-terra-400 text-white hover:scale-105' : 'bg-slate-900 hover:bg-black text-white dark:bg-terra-500 dark:hover:bg-terra-600' }}">
                    <span>{{ $pkg['button_text'] ?? 'Pilih Paket Ini' }}</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>
            @endforeach
        </div>

    </div>
</section>
