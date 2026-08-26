@props(['data'])

@php
    $badge = $data['badge'] ?? 'TRANSAKSI AMAN & TERPERCAYA';
    $title = $data['title'] ?? 'Jaminan Keamanan & 4 Garansi Resmi Pembelian Online';
    $subtitle = $data['subtitle'] ?? 'Kami mengutamakan kepuasan dan ketenangan pikiran Anda saat berbelanja material langsung dari pabrik kami.';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'dark');
    
    $defaultGuarantees = [
        [
            'icon' => 'shield',
            'badge' => 'GARANSI 100%',
            'title' => 'Garansi Pecah Ganti Baru',
            'desc' => 'Jika ditemukan keping roster yang retak, gompal, atau pecah saat pengiriman, kami ganti unit baru tanpa biaya tambahan.',
        ],
        [
            'icon' => 'factory',
            'badge' => 'PABRIK NYATA',
            'title' => 'Terverifikasi & Bisa Survey Langsung',
            'desc' => 'Workshop fisik kami beroperasi di Plered, Purwakarta. Anda dipersilakan survey lokasi atau request Live Video Call WhatsApp untuk cek stok.',
        ],
        [
            'icon' => 'document',
            'badge' => 'LEGAL & RESMI',
            'title' => 'Invoice & Surat Jalan Berstempel',
            'desc' => 'Setiap transaksi diterbitkan dokumen resmi berbadan usaha dengan nomor surat jalan dan rincian spesifikasi yang jelas.',
        ],
        [
            'icon' => 'payment',
            'badge' => 'TRANSAKSI AMAN',
            'title' => 'Rekening Resmi & Multi-Payment',
            'desc' => 'Mendukung transfer rekening bank resmi, Payment Gateway Midtrans (QRIS, Kartu Kredit, Virtual Account), dan sistem DP aman.',
        ],
    ];

    $items = !empty($data['items']) ? $data['items'] : $defaultGuarantees;
@endphp

<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden">
    <x-blocks._bg-theme :theme="$theme" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 font-sans">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 sm:mb-20">
            @if(!empty($badge))
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs sm:text-sm font-black uppercase tracking-widest mb-5 shadow-soft-xs">
                <svg class="w-4 h-4 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>{{ $badge }}</span>
            </div>
            @endif

            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black {{ $theme->headingColor }} tracking-tight leading-tight mb-5">
                {{ $title }}
            </h2>

            @if(!empty($subtitle))
            <p class="text-base sm:text-lg {{ $theme->subColor }} leading-relaxed">
                {{ $subtitle }}
            </p>
            @endif
        </div>

        <!-- 4 Guarantee Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
            @foreach($items as $idx => $item)
            <div class="p-8 rounded-3xl {{ $theme->cardBg }} border transition-all duration-500 hover:-translate-y-2 hover:shadow-luxury group relative overflow-hidden flex flex-col justify-between">
                <!-- Decorative Corner Glow -->
                <div class="absolute -right-12 -top-12 w-32 h-32 bg-terra-500/10 rounded-full blur-2xl group-hover:bg-terra-500/20 transition-all"></div>

                <div>
                    <!-- Icon & Badge Header -->
                    <div class="flex items-center justify-between gap-4 mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-terra-500/15 border border-terra-500/30 flex items-center justify-center text-terra-500 group-hover:scale-110 group-hover:bg-terra-500 group-hover:text-white transition-all duration-300 shadow-soft-xs">
                            @if(($item['icon'] ?? '') === 'factory')
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            @elseif(($item['icon'] ?? '') === 'document')
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            @elseif(($item['icon'] ?? '') === 'payment')
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                            @else
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            @endif
                        </div>

                        @if(!empty($item['badge']))
                        <span class="text-[10px] font-black tracking-widest uppercase px-2.5 py-1 rounded-md bg-terra-500/10 text-terra-500 border border-terra-500/20">
                            {{ $item['badge'] }}
                        </span>
                        @endif
                    </div>

                    <!-- Title & Desc -->
                    <h3 class="text-lg font-bold {{ $theme->cardTitle }} mb-3 leading-snug">
                        {{ $item['title'] ?? '' }}
                    </h3>
                    <p class="text-sm {{ $theme->cardDesc }} leading-relaxed">
                        {{ $item['desc'] ?? '' }}
                    </p>
                </div>

                <!-- Verified Badge Footer -->
                <div class="mt-6 pt-4 border-t border-white/10 dark:border-slate-800 flex items-center gap-2 text-xs font-semibold text-emerald-500">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span>Terjamin Pabrik Resmi</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Trust Seal Banner -->
        <div class="mt-14 p-6 sm:p-8 rounded-3xl bg-terra-500/10 border border-terra-500/25 flex flex-col sm:flex-row items-center justify-between gap-6 backdrop-blur-md">
            <div class="flex items-center gap-4 text-center sm:text-left">
                <div class="w-12 h-12 rounded-2xl bg-terra-500 text-white flex items-center justify-center shrink-0 shadow-soft-sm mx-auto sm:mx-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                </div>
                <div>
                    <h4 class="font-bold text-base sm:text-lg {{ $theme->headingColor }}">Ragu Beli Online? Hubungi Kami untuk Live Video Call Pabrik</h4>
                    <p class="text-xs sm:text-sm {{ $theme->subColor }}">Tim CS kami siap memperlihatkan gudang stok, proses cetak, dan armada pengiriman langsung melalui kamera WhatsApp.</p>
                </div>
            </div>
            <a href="https://wa.me/6281389709847?text={{ urlencode('Halo IndoRoster, saya ingin konsultasi dan video call cek stok roster sebelum order online.') }}" target="_blank" class="px-6 py-3.5 bg-terra-500 hover:bg-terra-600 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-luxury hover:scale-105 shrink-0 flex items-center gap-2">
                <span>Request Video Call</span>
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>

    </div>
</section>
