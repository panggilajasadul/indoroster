@props(['data'])

@php
    $badge = $data['badge'] ?? 'ALUR PEMBELIAN AMAN';
    $title = $data['title'] ?? '4 Langkah Mudah & Aman Order Roster ke Pabrik';
    $subtitle = $data['subtitle'] ?? 'Proses transparan mulai dari konsultasi gambar/motif hingga roster terpasang di dinding Anda.';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'slate');

    $defaultSteps = [
        [
            'step' => '01',
            'title' => 'Konsultasi & Hitung Kebutuhan',
            'desc' => 'Kirim ukuran dinding atau foto denah Anda. Tim ahli kami bantu hitung jumlah keping & semen perekat secara gratis tanpa biaya konsultasi.',
        ],
        [
            'step' => '02',
            'title' => 'Penerbitan Invoice Resmi',
            'desc' => 'Dapatkan Surat Penawaran & Invoice resmi berstempel dengan rincian harga pabrik transparan, diskon kuantiti, dan nomor rekening sah perusahaan.',
        ],
        [
            'step' => '03',
            'title' => 'Quality Control & Muat Armada',
            'desc' => 'Roster diperiksa kelayakan sudut dan kekuatannya. Anda akan dikirimi foto/video armada pabrik saat barang dimuat sebelum berangkat.',
        ],
        [
            'step' => '04',
            'title' => 'Barang Sampai & Garansi Aktif',
            'desc' => 'Cek kondisi roster bersama sopir armada pabrik kami di lokasi. Garansi 100% ganti baru langsung berlaku jika ada barang rusak di jalan.',
        ],
    ];

    $steps = !empty($data['steps']) ? $data['steps'] : $defaultSteps;
@endphp

<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden font-sans">
    <x-blocks._bg-theme :theme="$theme" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-16 sm:mb-20">
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

        <!-- 4 Step Flow -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative">
            @foreach($steps as $idx => $step)
            <div class="relative flex flex-col justify-between p-8 rounded-3xl {{ $theme->cardBg }} border shadow-soft-xs hover:shadow-luxury transition-all duration-500 hover:-translate-y-2 group">
                <!-- Step Number Watermark -->
                <div class="font-black text-6xl text-terra-500/10 dark:text-terra-500/15 absolute top-4 right-6 group-hover:text-terra-500/25 transition-colors select-none">
                    {{ $step['step'] ?? sprintf('%02d', $idx + 1) }}
                </div>

                <div>
                    <!-- Step Badge -->
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-terra-500 text-white font-black text-lg mb-6 shadow-soft-sm group-hover:scale-110 transition-transform">
                        {{ $step['step'] ?? sprintf('%02d', $idx + 1) }}
                    </div>

                    <h3 class="text-lg font-bold {{ $theme->cardTitle }} mb-3 leading-snug">
                        {{ $step['title'] ?? '' }}
                    </h3>

                    <p class="text-sm {{ $theme->cardDesc }} leading-relaxed">
                        {{ $step['desc'] ?? '' }}
                    </p>
                </div>

                <div class="mt-8 pt-4 border-t border-slate-200/60 dark:border-slate-800 flex items-center gap-2 text-xs font-bold text-terra-500">
                    <span>Langkah {{ $idx + 1 }} dari 4</span>
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Bottom Safe Guarantee Strip -->
        <div class="mt-14 text-center">
            <a href="https://wa.me/6281389709847?text={{ urlencode('Halo IndoRoster, saya ingin konsultasi pembelian roster langsung ke pabrik.') }}" target="_blank" class="inline-flex items-center justify-center gap-3 px-8 py-4 bg-terra-500 hover:bg-terra-600 text-white font-bold text-sm uppercase tracking-wider rounded-2xl transition-all shadow-luxury hover:scale-105">
                <span>Mulai Konsultasi Gratis via WhatsApp</span>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>

    </div>
</section>
