@props(['data', 'pageTitle' => null])

@php
    $badge = $data['badge'] ?? 'Tentang Kami';
    $locationLabel = $data['location_label'] ?? 'Produksi sendiri di';
    $locationText = $data['location_text'] ?? 'Cadasmekar, Tegalwaru, Purwakarta';
    $title = $data['title'] ?? 'Produsen Roster Beton Terpercaya Sejak 2019';
    $description = $data['description'] ?? 'IndoRoster adalah produsen roster beton cetak tumbuk padat yang berdiri sejak 2019 di Kp. Cicadas, Desa Cadasmekar, Tegalwaru, Purwakarta. Kami memproduksi sendiri semua produk — dari roster ventilasi udara, genteng, bata ekspose, bata tempel, hingga ornamen dinding 3D — sehingga kualitas dan harganya bisa kami jaga tetap terbaik untukmu.';
    $description2 = $data['description2'] ?? 'Kekuatan produksi kami sampai 6.000 pcs dalam 6 hari (tergantung motif), jadi pesanan proyek sekalipun kami sanggup layani tepat waktu. Saat ini kami menyediakan 90+ motif roster — jauh lebih banyak dari yang tampil di halaman ini.';
    $rawTheme = $data['bg_theme'] ?? ($data['theme'] ?? 'warm');
    $theme = \App\Helpers\BlockTheme::resolve($rawTheme);

    $stats = $data['stats'] ?? [
        ['number' => '2019', 'label' => 'Produsen Berpengalaman', 'sub' => 'Berdiri & produksi sendiri hingga kini'],
        ['number' => '90+', 'label' => 'Motif Roster', 'sub' => 'Terus bertambah tiap periode'],
        ['number' => '6.000 pcs', 'label' => 'Dalam 6 Hari', 'sub' => 'Kapasitas produksi (tergantung motif)'],
        ['number' => '5', 'label' => 'Kategori Produk', 'sub' => 'Roster, genteng, bata, ornamen 3D'],
    ];

    $bottomBadge = $data['bottom_badge'] ?? 'Motif yang kamu cari tidak ada di halaman ini? Tenang — brosur motif lengkap kami GRATIS. Tinggal minta via WhatsApp, atau kirim foto produk referensimu dan kami buatkan.';
    $btn1Text = $data['btn1_text'] ?? 'Minta Brosur Gratis';
    $btn1Url = $data['btn1_url'] ?? 'https://wa.me/6281234567890?text=Halo%20IndoRoster!%20Saya%20mau%20minta%20brosur%20motif%20lengkap.%20Boleh%20dikirim?';
    $btn2Text = $data['btn2_text'] ?? 'Lihat Kategori Produk';
    $btn2Url = $data['btn2_url'] ?? '/katalog';

    // Image
    $imageUpload = $data['image_upload'] ?? null;
    $imageUrl = $data['image_url'] ?? null;
    $finalImage = $imageUpload ? asset('storage/' . $imageUpload) : ($imageUrl ?: null);
@endphp

<section class="py-14 sm:py-20 lg:py-24 relative overflow-hidden font-sans {{ $theme->bgClasses }} transition-colors duration-300">
    <x-blocks._bg-theme :theme="$theme" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 xl:gap-20 items-center">

            {{-- Kiri: Teks & Stats --}}
            <div class="flex flex-col items-start">

                {{-- Location Pill --}}
                @if(!empty($locationText))
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold mb-3 border bg-amber-50 dark:bg-slate-900/90 text-amber-900 dark:text-amber-400 border-amber-200/80 dark:border-slate-800">
                        <svg class="w-3.5 h-3.5 text-amber-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $locationLabel }} <strong>{{ $locationText }}</strong></span>
                    </div>
                @endif

                {{-- Badge --}}
                @if(!empty($badge))
                    <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-4 {{ $theme->badgeClass }}">
                        {{ $badge }}
                    </span>
                @endif

                {{-- Judul --}}
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight leading-tight mb-5 {{ $theme->headingColor }}">
                    {{ $title }}
                </h2>

                {{-- Deskripsi --}}
                @if(!empty($description))
                    <p class="text-sm sm:text-base leading-relaxed mb-4 {{ $theme->subColor }}">
                        {{ $description }}
                    </p>
                @endif
                @if(!empty($description2))
                    <p class="text-sm sm:text-base leading-relaxed mb-8 opacity-80 {{ $theme->subColor }}">
                        {{ $description2 }}
                    </p>
                @endif

                {{-- Stats Grid --}}
                @if(!empty($stats) && is_array($stats))
                    <div class="grid grid-cols-2 gap-4 sm:gap-5 w-full mb-8">
                        @foreach($stats as $stat)
                            <div class="rounded-xl p-4 border {{ $theme->cardBg }}">
                                <div class="text-xl sm:text-2xl font-black mb-0.5 text-amber-600 dark:text-amber-400">
                                    {{ $stat['number'] ?? '' }}
                                </div>
                                <div class="text-xs sm:text-sm font-bold {{ $theme->cardTitle }}">
                                    {{ $stat['label'] ?? '' }}
                                </div>
                                @if(!empty($stat['sub']))
                                    <div class="text-[10px] sm:text-xs mt-0.5 {{ $theme->cardDesc }}">
                                        {{ $stat['sub'] }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Info brosur + CTA --}}
                @if(!empty($bottomBadge))
                    <div class="w-full rounded-2xl p-4 mb-6 border text-xs sm:text-sm leading-relaxed bg-amber-50/70 dark:bg-slate-900/90 border-amber-200/60 dark:border-slate-800 text-amber-900 dark:text-slate-300">
                        {{ $bottomBadge }}
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    @if(!empty($btn1Text))
                        <a href="{{ $btn1Url }}" target="_blank" rel="noopener"
                           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-full font-bold text-sm bg-emerald-600 hover:bg-emerald-700 text-white shadow-lg shadow-emerald-600/25 transition-transform hover:scale-105 active:scale-95">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.584 1.777.818 2.791.818 3.179 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.77-5.767-5.772zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/>
                            </svg>
                            {{ $btn1Text }}
                        </a>
                    @endif
                    @if(!empty($btn2Text))
                        <a href="{{ $btn2Url }}"
                           class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-full font-bold text-sm border transition-colors border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700">
                            {{ $btn2Text }}
                        </a>
                    @endif
                </div>

            </div>

            {{-- Kanan: Foto (Opsional) --}}
            @if($finalImage)
                <div class="relative w-full flex justify-center lg:justify-end">
                    <div class="relative max-w-md lg:max-w-none w-full">
                        <div class="absolute -inset-2 bg-gradient-to-br from-amber-400/20 via-emerald-400/15 to-teal-400/10 rounded-3xl blur-xl"></div>
                        <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl border border-white/80 dark:border-slate-800 shadow-slate-900/10">
                            <img
                                src="{{ $finalImage }}"
                                alt="{{ $title }}"
                                class="w-full h-[280px] sm:h-[360px] md:h-[440px] lg:h-[480px] object-cover"
                                loading="lazy">
                        </div>
                    </div>
                </div>
            @else
                <div class="hidden lg:grid grid-cols-2 gap-4 items-center">
                    <div class="col-span-2 rounded-2xl p-8 text-center border {{ $theme->cardBg }}">
                        <div class="text-5xl font-black text-amber-600 dark:text-amber-400 mb-2">6.000</div>
                        <div class="text-lg font-bold {{ $theme->cardTitle }}">pcs dalam 6 hari</div>
                        <div class="text-sm opacity-70 {{ $theme->cardDesc }} mt-1">Kapasitas produksi kami untuk proyek besar</div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</section>
