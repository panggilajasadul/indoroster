@props(['data'])

@php
    $badge = $data['badge'] ?? 'DOKUMENTASI PENGIRIMAN NYATA';
    $title = $data['title'] ?? 'Bukti Pengiriman & Bongkar Muat Harian';
    $description = $data['description'] ?? 'Ratusan ribu keping roster telah terkirim aman ke berbagai kota dan pulau di Indonesia langsung dari pabrik kami.';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');

    $defaultProofs = [
        [
            'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260853/162067858_988931008308004_8757323712171815873_n_kpbq7h.jpg',
            'destination' => 'PIK 2, Jakarta Utara',
            'qty' => '850 Pcs Roster Beton',
            'vehicle' => 'Truk CDD 6 Roda',
            'status' => 'Terkirim Aman 100%',
        ],
        [
            'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260853/162040523_301019624734327_8783457199144865187_n_c4ebs6.jpg',
            'destination' => 'Dago Atas, Bandung',
            'qty' => '1.200 Pcs Roster Minimalis',
            'vehicle' => 'Truk Fuso Pabrik',
            'status' => 'Terkirim Aman 100%',
        ],
        [
            'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260854/162443423_465492824683050_2508781488168434771_n_oixmwn.jpg',
            'destination' => 'BSD City, Tangerang Selatan',
            'qty' => '600 Pcs Roster Abu-Abu',
            'vehicle' => 'Truk Engkel 4 Roda',
            'status' => 'Terkirim Aman 100%',
        ],
        [
            'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260855/162744318_115865207198888_2731872166667500125_n_oazhvv.jpg',
            'destination' => 'Summarecon, Bekasi',
            'qty' => '450 Pcs Roster Terracotta',
            'vehicle' => 'Armada Pick-up Cepat',
            'status' => 'Terkirim Aman 100%',
        ],
    ];

    $shipments = !empty($data['shipments']) ? $data['shipments'] : $defaultProofs;
@endphp

<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden font-sans">
    <x-blocks._bg-theme :theme="$theme" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-14 sm:mb-18">
            <div class="max-w-2xl">
                @if(!empty($badge))
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-black uppercase tracking-widest mb-4 shadow-soft-xs">
                    <span class="w-2 h-2 rounded-full bg-terra-500 animate-pulse"></span>
                    <span>{{ $badge }}</span>
                </div>
                @endif

                <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black {{ $theme->headingColor }} tracking-tight leading-tight">
                    {{ $title }}
                </h2>
            </div>

            @if(!empty($description))
            <p class="text-sm sm:text-base {{ $theme->subColor }} max-w-md leading-relaxed">
                {{ $description }}
            </p>
            @endif
        </div>

        <!-- Shipments Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($shipments as $item)
            @php
                $img = !empty($item['image_upload']) ? asset('storage/' . $item['image_upload']) : ($item['image'] ?? 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?auto=format&fit=crop&w=800&q=80');
            @endphp
            <div class="rounded-3xl {{ $theme->cardBg }} overflow-hidden border shadow-soft-xs hover:shadow-luxury transition-all duration-500 hover:-translate-y-2 group flex flex-col">
                <!-- Image Box -->
                <div class="relative aspect-4/3 overflow-hidden bg-slate-950">
                    <img src="{{ $img }}" alt="{{ $item['destination'] ?? 'Pengiriman Roster' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-90 group-hover:opacity-100" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-black/20"></div>

                    <!-- Destination Tag -->
                    <div class="absolute top-3 left-3 px-3 py-1 rounded-lg bg-black/60 backdrop-blur-md border border-white/20 text-white text-xs font-bold flex items-center gap-1.5 shadow-md">
                        <svg class="w-3.5 h-3.5 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <span>{{ $item['destination'] ?? 'Proyek Jabodetabek' }}</span>
                    </div>

                    <!-- Verified Status Pill -->
                    <div class="absolute bottom-3 right-3 px-2.5 py-1 rounded-full bg-emerald-500/90 text-white text-[10px] font-black uppercase tracking-wider flex items-center gap-1 shadow-md">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>{{ $item['status'] ?? 'Sampai Lokasi' }}</span>
                    </div>
                </div>

                <!-- Info Body -->
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="text-xs font-bold text-terra-500 uppercase tracking-wider mb-1">
                            {{ $item['vehicle'] ?? 'Armada Pabrik' }}
                        </div>
                        <h3 class="text-base font-bold {{ $theme->cardTitle }} leading-snug">
                            {{ $item['qty'] ?? 'Pesanan Roster Beton' }}
                        </h3>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-200/60 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span>Pemeriksaan Muatan: <strong>Selesai</strong></span>
                        <span class="text-emerald-600 dark:text-emerald-400 font-bold">✓ Lolos QC</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Social Proof Stats Footer -->
        <div class="mt-14 pt-8 border-t border-slate-200/80 dark:border-slate-800 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div>
                <div class="text-2xl sm:text-3xl font-black text-terra-500">100%</div>
                <div class="text-xs sm:text-sm font-semibold {{ $theme->subColor }} mt-1">Garansi Utuh Tiba di Lokasi</div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-black {{ $theme->headingColor }}">50+ Kota</div>
                <div class="text-xs sm:text-sm font-semibold {{ $theme->subColor }} mt-1">Jangkauan Pengiriman Indonesia</div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-black {{ $theme->headingColor }}">Armada Sendiri</div>
                <div class="text-xs sm:text-sm font-semibold {{ $theme->subColor }} mt-1">Sopir & Helper Ahli Bongkar Muat</div>
            </div>
            <div>
                <div class="text-2xl sm:text-3xl font-black {{ $theme->headingColor }}">Live Tracking</div>
                <div class="text-xs sm:text-sm font-semibold {{ $theme->subColor }} mt-1">Update Foto Sebelum Berangkat</div>
            </div>
        </div>

    </div>
</section>
