@php
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Beranda',
                'item' => route('home'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $page->h1,
                'item' => url($page->slug),
            ],
        ],
    ];

    $faqSection = $sections->firstWhere('section_type', 'faq');
    $faqSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => [
            [
                '@type' => 'Question',
                'name' => "Apakah IndoRoster melayani pembelian retail & proyek untuk {$page->primary_keyword}?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Ya, IndoRoster melayani pembelian partai kecil eceran untuk renovasi rumah tinggal hingga volume ribuan keping untuk proyek developer & kontraktor langsung dari pabrik Plered Purwakarta.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Bagaimana standar mutu cetak padat IndoRoster?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Setiap keping dicetak padat presisi siku 90 derajat dengan formulasi pasir abu batu murni pilihan, kokoh, berbobot 3.8 hingga 4.2 kg, minim pori, dan tidak mudah berlumut.",
                ],
            ],
            [
                '@type' => 'Question',
                'name' => "Apakah ada garansi jika barang pecah saat pengiriman?",
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => "Ada. Kami memberikan Garansi 100% Ganti Baru di tempat untuk setiap keping yang rusak atau pecah selama pengiriman oleh armada pabrik kami.",
                ],
            ],
        ],
    ];

    $serviceSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => "Pusat Produsen & Suplai {$page->primary_keyword}",
        'serviceType' => 'Suplai Roster Beton Minimalis & Material Arsitektural Presisi',
        'provider' => [
            '@type' => 'Organization',
            'name' => 'IndoRoster',
            'url' => route('home'),
            'logo' => asset('assets/logo_indoroster_no_text.PNG'),
        ],
        'areaServed' => [
            '@type' => 'Country',
            'name' => 'Indonesia',
        ],
        'description' => $page->meta_description ?: "Pusat produsen {$page->primary_keyword} mutu cetak padat presisi plat baja siku 90° langsung dari pabrik sentra Plered Purwakarta.",
    ];

    $offerCatalogSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'OfferCatalog',
        'name' => "Katalog Roster Pilihan untuk {$page->primary_keyword}",
        'itemListElement' => $explorerProducts->take(8)->map(function ($p, $idx) {
            return [
                '@type' => 'Offer',
                'itemOffered' => [
                    '@type' => 'Product',
                    'name' => $p->name,
                    'image' => $p->primary_image ?: asset('assets/logo_indoroster_no_text.PNG'),
                    'description' => "Roster beton minimalis {$p->name} cetak padat presisi 20x20x10 cm",
                    'brand' => [
                        '@type' => 'Brand',
                        'name' => 'IndoRoster',
                    ],
                ],
                'priceCurrency' => 'IDR',
                'price' => (float) ($p->variants->min('price') ?: 11000),
                'availability' => 'https://schema.org/InStock',
                'itemCondition' => 'https://schema.org/NewCondition',
            ];
        })->toArray(),
    ];
@endphp

@push('seo')
    <meta name="geo.region" content="ID">
    <meta name="geo.placename" content="Indonesia">
    <script type="application/ld+json">
    {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($serviceSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
    {!! json_encode($offerCatalogSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endpush

<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-8 sm:py-12" x-data="{ lightboxOpen: false, lightboxImg: '', lightboxTitle: '', lightboxLocation: '', lightboxDesc: '', zoomLevel: 1 }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- ══════════════════════════════════════════════════════════════
             1. INTRODUCTION (HERO & ARSITETUR VALUE STACK)
        ══════════════════════════════════════════════════════════════ --}}
        <div class="relative rounded-3xl overflow-hidden bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-slate-900 dark:text-white p-6 sm:p-10 lg:p-12 shadow-soft-xl mb-12">
            <div class="absolute -right-20 -top-20 w-96 h-96 bg-terra-500/10 dark:bg-terra-500/15 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-amber-500/10 dark:bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10">
                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 mb-4" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}" class="hover:text-terra-500 transition">Beranda</a>
                    <span>/</span>
                    @if($page->parentPage)
                        <a href="/{{ $page->parentPage->slug }}" class="hover:text-terra-500 transition">{{ $page->parentPage->h1 }}</a>
                        <span>/</span>
                    @endif
                    <span class="text-slate-700 dark:text-slate-300 font-medium truncate">{{ $page->h1 }}</span>
                </nav>

                {{-- Badges --}}
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-terra-50 dark:bg-terra-950/60 border border-terra-200 dark:border-terra-800 text-terra-700 dark:text-terra-300 text-xs font-bold uppercase tracking-wider">
                        🏭 Produsen Tangan Pertama Plered
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-xs font-bold uppercase tracking-wider">
                        🚚 Siap Kirim Jabodetabek & Jawa Barat
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 text-xs font-bold uppercase tracking-wider">
                        🛡️ Garansi Ganti Pecah di Lokasi
                    </span>
                </div>

                {{-- Main H1 --}}
                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-tight text-slate-900 dark:text-white mb-5 max-w-4xl">
                    {{ $page->h1 }}
                </h1>

                {{-- Opening Story Narrative --}}
                <div class="text-base sm:text-lg text-slate-600 dark:text-slate-300 mb-8 leading-relaxed max-w-4xl space-y-4">
                    <p>
                        {{ $page->opening_text }}
                    </p>
                </div>

                {{-- Primary Action Buttons --}}
                <div class="flex flex-wrap items-center gap-3 sm:gap-4 mb-8">
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2.5 px-7 py-3.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm sm:text-base shadow-lg shadow-emerald-600/25 transition-all hover:scale-[1.02]">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        <span>Konsultasi & Minta Penawaran via WA</span>
                    </a>
                    <a href="https://drive.google.com/file/d/1wcBxdEv7yiytPlLSVE1ldl1rYpe0MHZZ/view?usp=drive_link" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-terra-600 hover:bg-terra-500 text-white font-bold text-sm shadow-md transition-all hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>Unduh Katalog PDF (Google Drive)</span>
                    </a>
                    <a href="{{ route('tools.calculator') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 font-bold text-sm transition-all border border-slate-200 dark:border-slate-700">
                        <span>🧮 Hitung Kebutuhan m²</span>
                    </a>
                </div>

                {{-- Key Trust Strip --}}
                <div class="pt-6 border-t border-slate-100 dark:border-slate-800 grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="text-terra-600 text-lg">📐</span>
                        <div>
                            <span class="font-black text-slate-900 dark:text-white block">Siku 90° Presisi</span>
                            <span class="text-slate-400">Cetak Plat Baja</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-emerald-600 text-lg">🛡️</span>
                        <div>
                            <span class="font-black text-slate-900 dark:text-white block">Garansi Pecah 100%</span>
                            <span class="text-slate-400">Ganti di Tempat</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-amber-500 text-lg">🚚</span>
                        <div>
                            <span class="font-black text-slate-900 dark:text-white block">Armada Pabrik</span>
                            <span class="text-slate-400">Colt Diesel & Fuso</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-indigo-500 text-lg">🏭</span>
                        <div>
                            <span class="font-black text-slate-900 dark:text-white block">10.000 Pcs/Bulan</span>
                            <span class="text-slate-400">Kapasitas Produksi</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             2. THE PROBLEM & 3. WHY ROSTER CAN BE CONSIDERED
        ══════════════════════════════════════════════════════════════ --}}
        @php
            $problemSection = $sections->firstWhere('section_type', 'problem');
            $solutionSection = $sections->firstWhere('section_type', 'solution');
            $specsSection = $sections->firstWhere('section_type', 'specs');
        @endphp

        @if($problemSection && $solutionSection)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 mb-14">
            {{-- 2. The Problem Card --}}
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/90 dark:border-slate-800 p-6 sm:p-8 lg:p-10 shadow-soft-xs flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-rose-500"></div>
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-600 dark:text-rose-400 text-xs font-bold uppercase tracking-wider mb-4">
                        ⚠️ Tantangan di Lapangan
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mb-4 leading-snug">
                        {{ $problemSection->heading }}
                    </h2>
                    <div class="prose prose-slate dark:prose-invert max-w-none text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                        {!! $problemSection->content !!}
                    </div>
                </div>
            </div>

            {{-- 3. Why Roster / Solution Card --}}
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-emerald-200/80 dark:border-slate-800 p-6 sm:p-8 lg:p-10 shadow-soft-xs flex flex-col justify-between relative overflow-hidden">
                <div class="absolute top-0 left-0 w-2 h-full bg-emerald-500"></div>
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 text-xs font-bold uppercase tracking-wider mb-4">
                        ✨ Keunggulan Cetak Padat IndoRoster
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mb-4 leading-snug">
                        {{ $solutionSection->heading }}
                    </h2>
                    <div class="prose prose-slate dark:prose-invert max-w-none text-sm leading-relaxed text-slate-600 dark:text-slate-300">
                        {!! $solutionSection->content !!}
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════
             NEW: DEDICATED MANFAAT & BENEFIT SECTION
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white rounded-3xl p-6 sm:p-10 mb-14 shadow-soft-lg relative overflow-hidden border border-slate-700">
            <div class="max-w-3xl mb-8 relative z-10">
                <span class="text-xs font-bold uppercase tracking-wider text-terra-400">Nilai Tambah & Solusi Nyata</span>
                <h2 class="text-2xl sm:text-3xl font-black text-white mt-1">
                    4 Manfaat Utama Menggunakan Roster Beton IndoRoster
                </h2>
                <p class="text-xs sm:text-sm text-slate-300 mt-1">
                    Investasi jangka panjang yang meningkatkan kualitas kenyamanan hunian dan efisiensi biaya operasional bangunan Anda.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 relative z-10 not-prose">
                <div class="p-5 rounded-2xl bg-white/5 border border-white/10 hover:border-terra-400/50 transition">
                    <div class="text-3xl mb-3">❄️</div>
                    <h4 class="font-bold text-white text-base mb-1.5">Efisiensi Listrik AC</h4>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Sirkulasi silang pasif (*cross ventilation*) meredam suhu panas ruangan hingga 40%, membuat ruangan sejuk alami dan memangkas tagihan listrik.
                    </p>
                </div>
                <div class="p-5 rounded-2xl bg-white/5 border border-white/10 hover:border-emerald-400/50 transition">
                    <div class="text-3xl mb-3">👁️</div>
                    <h4 class="font-bold text-white text-base mb-1.5">Privasi Tanpa Gelap</h4>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Membatasi pandangan visual orang luar tanpa membuat area teras dan interior terasa pengap, suram, atau terisolasi.
                    </p>
                </div>
                <div class="p-5 rounded-2xl bg-white/5 border border-white/10 hover:border-amber-400/50 transition">
                    <div class="text-3xl mb-3">🏗️</div>
                    <h4 class="font-bold text-white text-base mb-1.5">Kepadatan Cetak Padat</h4>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Bobot mantap 3.8 – 4.2 kg per keping dari pasir abu batu murni menjamin dinding berdiri kokoh, tahan benturan dan bebas retak rambut.
                    </p>
                </div>
                <div class="p-5 rounded-2xl bg-white/5 border border-white/10 hover:border-indigo-400/50 transition">
                    <div class="text-3xl mb-3">📈</div>
                    <h4 class="font-bold text-white text-base mb-1.5">Estetika Arsitektural</h4>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        Permainan bayangan cahaya matahari (*shadow play*) memberikan sentuhan kemewahan modern yang meningkatkan nilai jual properti Anda.
                    </p>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             4. NARASI EKSPLORASI ARSITEKTUR LOKAL (IKLIM, DAERAH TERDEKAT & HUMOR)
        ══════════════════════════════════════════════════════════════ --}}
        @php
            $localNarrativeSection = $sections->firstWhere('section_type', 'usecase') ?? $sections->firstWhere('section_type', 'custom') ?? $sections->firstWhere('section_type', 'local_narrative');
            $locationTitle = $page->location_name ?: 'Jabodetabek & Sekitarnya';
        @endphp
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-10 mb-14 shadow-soft-xs relative overflow-hidden">
            <div class="max-w-4xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-terra-50 dark:bg-terra-950/60 border border-terra-200 dark:border-terra-800 text-terra-700 dark:text-terra-300 text-xs font-bold uppercase tracking-wider mb-4">
                    <span>🧭</span> Dinamika Desain & Karakter Wilayah
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mb-5 leading-snug">
                    {{ $localNarrativeSection->heading ?? "Karakter Arsitektur & Solusi Dinding Roster Beton di {$locationTitle}" }}
                </h2>
                <div class="prose prose-slate dark:prose-invert max-w-none text-sm sm:text-base leading-relaxed text-slate-600 dark:text-slate-300 space-y-4">
                    @if($localNarrativeSection && !empty($localNarrativeSection->content))
                        {!! $localNarrativeSection->content !!}
                    @else
                        <p>
                            Membangun hunian, cafe, ruko, maupun gedung fasilitas di wilayah <strong>{{ $locationTitle }}</strong> dan kawasan terdekatnya membutuhkan perhatian ekstra terhadap iklim tropis Indonesia. Seringkali pemilik bangunan terjebak dalam dilema klasik: ingin privasi maksimal tetapi akhirnya membuat ruangan pengap seperti oven panggang, sehingga AC harus menyala 24 jam non-stop dan tagihan listrik melonjak tajam di akhir bulan.
                        </p>
                        <p>
                            Kehadiran roster beton minimalis (sering disebut juga <em>breeze blocks</em>, loster beton, atau bata angin jalusi) menjadi penyelamat arsitektur modern. Dengan susunan modul cetak presisi 20×20 cm atau 10×20 cm berbahan <strong>pasir abu batu murni</strong>, dinding fasad dan pagar bangunan Anda mendapatkan sirkulasi silang (*cross-ventilation*) alami. Udara panas terdorong keluar secara pasif, sementara cahaya alami tetap masuk tanpa menyilaukan mata.
                        </p>
                        <p>
                            Dan bicara soal pemasangan, tukang bangunan mana pun pasti paham rasanya drama menghadapi roster murahan yang tidak presisi—sudutnya miring, nat bergelombang, dan adukan semen terbuang percuma. Di IndoRoster, setiap keping dicetak tumbuk padat menggunakan plat baja bersiku 90° presisi oleh pengrajin sentra Plered Purwakarta, memastikan garis nat dinding Anda lurus rapi, kokoh tahan puluhan tahun, dan bebas retak.
                        </p>
                    @endif
                </div>

                {{-- Delivery Radius Banner --}}
                <div class="mt-8 p-4 sm:p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">🚚</span>
                        <div>
                            <span class="text-xs font-bold text-slate-900 dark:text-white block">Jangkauan Pengiriman Armada Pabrik Langsung</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">Melayani pengiriman proyek ke area <strong>{{ $locationTitle }}</strong> serta wilayah tetangga sekitarnya dengan garansi 100% ganti baru di tempat.</span>
                        </div>
                    </div>
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center justify-center gap-1.5 transition shrink-0 whitespace-nowrap">
                        <span>Cek Ongkir Armada WA</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             5. STORYTELLING PENGALAMAN NYATA PEMBELI (CERITA LUWES & HANGAT)
        ══════════════════════════════════════════════════════════════ --}}
        @php
            $customerStorySection = $sections->firstWhere('section_type', 'testimonial') ?? $sections->firstWhere('section_type', 'customer_story');
        @endphp
        <div class="bg-gradient-to-br from-amber-500/5 via-slate-50 to-amber-500/5 dark:from-slate-900 dark:via-slate-850 dark:to-slate-900 rounded-3xl border border-amber-200/60 dark:border-slate-800 p-6 sm:p-10 mb-14 shadow-soft-xs">
            <div class="max-w-3xl mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">Pengalaman Nyata Pelanggan</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1">
                    Cerita Mereka yang Sudah Membuktikan Kualitas IndoRoster
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Bukan sekadar testimoni kaku, inilah kisah nyata para kontraktor, arsitek, dan pemilik rumah yang mempercayakan dinding ventilasi bangunannya kepada pabrik kami:
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 not-prose">
                {{-- Story 1: Kontraktor --}}
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 shadow-soft-2xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-terra-500/10 text-terra-600 dark:text-terra-400 font-bold flex items-center justify-center text-sm">
                                PH
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900 dark:text-white">Pak Hendra</h4>
                                <span class="text-[11px] text-slate-400 block">Kontraktor Klaster Perumahan</span>
                            </div>
                        </div>
                        <div class="text-xs text-amber-500 mb-3">★★★★★ <span class="text-slate-400 text-[10px] ml-1">(Terverifikasi Proyek 2.500 Pcs)</span></div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed italic">
                            &ldquo;Dulu saya sering diprotes mandor karena beli roster dari tempat lain yang sudutnya miring 85°. Tukang ngedumel seharian karena nat semen bergelombang. Begitu beralih ke IndoRoster cetak plat baja siku 90°, tukang saya kerjanya 2x lebih cepat dan garis nat dindingnya lurus rapi tanpa tambal-sulam lagi.&rdquo;
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700/60 text-[10px] text-slate-400">
                        Motif: Minimalis 20x20 & Nako Anti Tampias
                    </div>
                </div>

                {{-- Story 2: Pemilik Rumah --}}
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 shadow-soft-2xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-bold flex items-center justify-center text-sm">
                                BR
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900 dark:text-white">Bu Ratna</h4>
                                <span class="text-[11px] text-slate-400 block">Pemilik Rumah Tinggal</span>
                            </div>
                        </div>
                        <div class="text-xs text-amber-500 mb-3">★★★★★ <span class="text-slate-400 text-[10px] ml-1">(Renovasi Fasad Rumah)</span></div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed italic">
                            &ldquo;Ruang tamu kami hadap barat, jam 2 siang hawanya seperti oven dan tagihan AC bengkak. Setelah pasang secondary skin roster IndoRoster motif Bintang & Nako, rumah jadi adem semilir dan bayangan matahari sore di dinding cantik sekali buat santai minum teh bersama keluarga.&rdquo;
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700/60 text-[10px] text-slate-400">
                        Motif: Bintang Geometris & Abu Pasir Murni
                    </div>
                </div>

                {{-- Story 3: Owner Cafe --}}
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-800 border border-slate-200/80 dark:border-slate-700 shadow-soft-2xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-blue-500/10 text-blue-600 dark:text-blue-400 font-bold flex items-center justify-center text-sm">
                                MD
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-slate-900 dark:text-white">Mas Dimas</h4>
                                <span class="text-[11px] text-slate-400 block">Owner Coffee Shop & Resto</span>
                            </div>
                        </div>
                        <div class="text-xs text-amber-500 mb-3">★★★★★ <span class="text-slate-400 text-[10px] ml-1">(Partisi Semi-Outdoor Cafe)</span></div>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed italic">
                            &ldquo;Bikin cafe outdoor butuh partisi yang aesthetic tapi budget masuk akal. Langsung dapat harga pabrik di IndoRoster. Waktu pengiriman ada 2 keping gompal di jalan, supir armada langsung ganti baru dari stok cadangan truk tanpa debat. Pelayanan seperti ini yang bikin tenang!&rdquo;
                        </p>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700/60 text-[10px] text-slate-400">
                        Motif: 3D Wall Panel & Bata Roster 10x20
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             6. DESIGN & APPLICATION EXAMPLES (EXPANSIVE 2-COLUMN SHOWCASE)
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-10 mb-14 shadow-soft-sm">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">Inspirasi Desain Nyata Terpasang</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight mt-1">
                        Contoh Aplikasi & Hasil Pemasangan {{ $page->primary_keyword }}
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 max-w-2xl">
                        Dokumentasi riil pengerjaan dinding fasad, pagar, dan partisi arsitektural. Seluruh foto, judul proyek, lokasi, dan detail motif di bawah ini terhubung langsung dengan database galeri resmi IndoRoster.
                    </p>
                </div>
                <a href="{{ route('gallery') }}" class="inline-flex items-center gap-2 text-xs font-bold text-terra-600 dark:text-terra-400 hover:underline flex-shrink-0">
                    Lihat Semua Galeri Proyek &rarr;
                </a>
            </div>

            {{-- 2-COLUMN EXPANSIVE GALLERY SHOWCASE WITH PRODUCT SPILL --}}
            @if(isset($randomGalleryMedia) && $randomGalleryMedia->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
                @foreach($randomGalleryMedia as $gMedia)
                    @php
                        $mediaImgUrl = str_starts_with($gMedia->media_url, 'http') ? $gMedia->media_url : asset('storage/'.$gMedia->media_url);
                        $gallery = $gMedia->gallery;
                        $galTitle = $gallery->title ?? 'Pemasangan Roster Minimalis Modern';
                        $galLocation = $gallery->location ?? 'Jabodetabek';
                        $galDesc = $gallery->description ?? 'Dokumentasi hasil pengerjaan proyek dinding ventilasi presisi IndoRoster.';
                        $galCategory = $gallery->category ? strtoupper($gallery->category) : 'PROYEK';
                        $linkedProduct = $gallery ? $gallery->product : null;
                        $galWaUrl = "https://wa.me/{$waNumber}?text=" . urlencode("Halo Admin IndoRoster, saya melihat foto hasil pasang: {$galTitle} di {$galLocation}. Saya ingin konsultasi pemesanan motif seperti ini.");
                    @endphp
                    <div class="bg-slate-50 dark:bg-slate-800/70 rounded-3xl border border-slate-200/80 dark:border-slate-700/80 overflow-hidden flex flex-col justify-between hover:border-terra-400 shadow-sm hover:shadow-md transition-all group">
                        {{-- Photo Canvas --}}
                        <div class="relative w-full aspect-[4/3] bg-slate-950 overflow-hidden cursor-pointer"
                             @click="lightboxImg = '{{ $mediaImgUrl }}'; lightboxTitle = '{{ addslashes($galTitle) }}'; lightboxLocation = '{{ addslashes($galLocation) }}'; lightboxDesc = '{{ addslashes($galDesc) }}'; zoomLevel = 1; lightboxOpen = true">
                            <img src="{{ $mediaImgUrl }}" 
                                 alt="{{ $gMedia->alt_text ?: $galTitle }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                 loading="lazy"
                                 onerror="this.onerror=null; this.src='{{ asset('assets/logo_indoroster_no_text.PNG') }}';">
                            
                            {{-- Top Badges --}}
                            <div class="absolute top-3 left-3 right-3 flex items-center justify-between pointer-events-none">
                                <span class="px-3 py-1 rounded-full bg-slate-900/85 backdrop-blur-xs text-white text-xs font-bold shadow-sm">
                                    📍 {{ $galLocation }}
                                </span>
                                <span class="px-3 py-1 rounded-full bg-terra-600/90 text-white text-[11px] font-bold shadow-sm">
                                    🔍 Klik Perbesar & Zoom
                                </span>
                            </div>
                        </div>

                        {{-- Card Content & Spill Product Cart --}}
                        <div class="p-6 flex flex-col justify-between flex-1">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-terra-100 dark:bg-terra-950 text-terra-700 dark:text-terra-300 border border-terra-200 dark:border-terra-800">
                                        {{ $galCategory }}
                                    </span>
                                </div>
                                <h3 class="font-extrabold text-lg text-slate-900 dark:text-white mb-2 leading-snug">
                                    {{ $galTitle }}
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                                    {{ $galDesc }}
                                </p>
                            </div>

                            {{-- Mini Product Spill / Cart Tag --}}
                            <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                                @if($linkedProduct)
                                <div class="flex items-center justify-between gap-3 bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200/80 dark:border-slate-700 mb-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-1 flex-shrink-0 border border-slate-200 dark:border-slate-700">
                                            <img src="{{ $linkedProduct->primary_image ?: $mediaImgUrl }}" 
                                                 alt="{{ $linkedProduct->name }}" 
                                                 class="w-full h-full object-contain"
                                                 onerror="this.onerror=null; this.src='{{ asset('assets/logo_indoroster_no_text.PNG') }}';">
                                        </div>
                                        <div class="min-w-0">
                                            <span class="text-[10px] uppercase font-bold text-slate-400 block">Motif Digunakan:</span>
                                            <span class="text-xs font-black text-slate-900 dark:text-white truncate block">{{ $linkedProduct->name }}</span>
                                            <span class="text-xs font-black text-[#ee4d2d] dark:text-terra-400">{{ $linkedProduct->formatted_price_range }}</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('product.detail', $linkedProduct->slug) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs font-bold transition flex-shrink-0">
                                        Detail &rarr;
                                    </a>
                                </div>
                                @endif

                                {{-- Action Buttons --}}
                                <div class="flex items-center gap-2">
                                    <a href="{{ $galWaUrl }}" target="_blank" rel="noopener noreferrer" class="flex-1 py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-xs transition">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                        <span>Pesan Motif Hasil Pasang Ini</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
        </div>

        {{-- LIGHTBOX MODAL DIALOG WITH ZOOM CONTROLS --}}
        <div x-show="lightboxOpen" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/95 backdrop-blur-md p-4 sm:p-6"
             @keydown.escape.window="lightboxOpen = false">
            <div class="relative max-w-5xl w-full max-h-[90vh] bg-slate-900 rounded-3xl overflow-hidden border border-slate-700 shadow-2xl flex flex-col" @click.away="lightboxOpen = false">
                {{-- Lightbox Toolbar --}}
                <div class="p-4 bg-slate-950/80 border-b border-slate-800 flex items-center justify-between z-20">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-terra-500/20 text-terra-400 border border-terra-500/30" x-text="lightboxLocation"></span>
                        <span class="text-xs text-slate-400 hidden sm:inline">(Klik ganda / gunakan tombol zoom untuk memperbesar)</span>
                    </div>
                    {{-- Zoom Controls --}}
                    <div class="flex items-center gap-2">
                        <button @click="zoomLevel = Math.max(zoomLevel - 0.5, 1)" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-white font-bold text-sm flex items-center justify-center transition" title="Zoom Out">
                            −
                        </button>
                        <span class="text-xs font-mono text-slate-300 w-12 text-center" x-text="Math.round(zoomLevel * 100) + '%'"></span>
                        <button @click="zoomLevel = Math.min(zoomLevel + 0.5, 3)" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 text-white font-bold text-sm flex items-center justify-center transition" title="Zoom In">
                            +
                        </button>
                        <button @click="zoomLevel = 1" class="px-2.5 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-bold transition">
                            Reset
                        </button>
                        <button @click="lightboxOpen = false" class="w-8 h-8 rounded-lg bg-rose-600 hover:bg-rose-500 text-white flex items-center justify-center font-bold text-sm ml-2 transition">
                            ✕
                        </button>
                    </div>
                </div>

                {{-- Interactive Zoomable Image Area --}}
                <div class="relative flex-1 bg-black overflow-auto p-4 flex items-center justify-center min-h-[300px] sm:min-h-[450px]">
                    <img :src="lightboxImg" 
                         :alt="lightboxTitle" 
                         class="max-w-full max-h-[60vh] object-contain transition-transform duration-200 cursor-zoom-in"
                         :style="'transform: scale(' + zoomLevel + '); transform-origin: center center;'"
                         @dblclick="zoomLevel = (zoomLevel > 1 ? 1 : 2)">
                </div>

                {{-- Lightbox Footer Info --}}
                <div class="p-5 bg-slate-900 border-t border-slate-800 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="max-w-2xl">
                        <h3 class="text-base font-bold text-white mb-1" x-text="lightboxTitle"></h3>
                        <p class="text-xs text-slate-400" x-text="lightboxDesc"></p>
                    </div>
                    <a :href="'https://wa.me/{{ $waNumber }}?text=' + encodeURIComponent('Halo Admin IndoRoster, saya tertarik dengan foto: ' + lightboxTitle + ' di ' + lightboxLocation + '. Bisa info harga dan stok?')" target="_blank" rel="noopener noreferrer" class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center justify-center gap-2 transition flex-shrink-0">
                        <span>Konsultasi Motif Ini via WA</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             5. WHAT SHOULD BE CONSIDERED? (PANDUAN TEKNIS WAJIB DIPERHATIKAN)
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-10 mb-14 shadow-soft-xs">
            <div class="max-w-3xl mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">Tips Tukang & Arsitek</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1">
                    Hal Penting yang Wajib Diperhatikan Saat Memasang Roster
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Pastikan dinding roster Anda berdiri kokoh, aman dari gempa, dan tahan cuaca tropis puluhan tahun dengan mengikuti 4 standar teknis berikut:
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 not-prose">
                <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/70 border border-slate-200/80 dark:border-slate-700/80">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xl font-bold mb-3">🔩</div>
                    <h4 class="font-bold text-slate-900 dark:text-white text-sm mb-1.5">1. Struktur Tulangan Besi</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">Untuk dinding dengan tinggi lebih dari 2 meter, wajib pasang besi behel (tulangan) horizontal & vertikal setiap selang 2-3 susun agar dinding kokoh anti-roboh.</p>
                </div>
                <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/70 border border-slate-200/80 dark:border-slate-700/80">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl font-bold mb-3">🥣</div>
                    <h4 class="font-bold text-slate-900 dark:text-white text-sm mb-1.5">2. Semen Mortar Instan</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">Gunakan semen mortar perekat tipis (thin-bed) khusus bata ringan/roster untuk ketebalan nat 2-3 mm yang rapi, kuat merekat, dan hemat adukan.</p>
                </div>
                <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/70 border border-slate-200/80 dark:border-slate-700/80">
                    <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl font-bold mb-3">🛡️</div>
                    <h4 class="font-bold text-slate-900 dark:text-white text-sm mb-1.5">3. Pelapis Coating Waterproofing</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">Untuk area pagar outdoor atau fasad terbuka yang sering terkena hujan, aplikasikan 1-2 lapis clear coating anti-lumut agar warna tetap segar dan tidak berkerak.</p>
                </div>
                <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/70 border border-slate-200/80 dark:border-slate-700/80">
                    <div class="w-10 h-10 rounded-xl bg-terra-500/10 text-terra-600 dark:text-terra-400 flex items-center justify-center text-xl font-bold mb-3">🧭</div>
                    <h4 class="font-bold text-slate-900 dark:text-white text-sm mb-1.5">4. Arah Orientasi Matahari</h4>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">Arahkan kisi-kisi atau bukaan miring menghadap arah angin dominan untuk sirkulasi maksimal, dan posisikan kemiringan sirip ke bawah untuk halau tampias.</p>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             6. PRODUCT OPTIONS / INTERACTIVE CATALOG (45+ MOTIF)
        ══════════════════════════════════════════════════════════════ --}}
        <div id="product-showcase" class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-10 mb-14 shadow-soft-sm">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8 pb-6 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">Pilihan Lengkap Motif</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight mt-1">
                        Koleksi Motif Roster Unggulan Pabrik IndoRoster
                    </h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Pilih motif yang sesuai dengan konsep arsitektur proyek Anda. Klik tombol WA pada motif yang diinginkan untuk tanya stok dan penawaran harga.
                    </p>
                </div>

                {{-- Interactive Controls --}}
                <div class="flex flex-wrap items-center gap-3">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama motif..." class="px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500 focus:outline-none w-48 sm:w-60 shadow-2xs">
                    <select wire:model.live="selectedCategory" class="px-3.5 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-xs text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500 focus:outline-none shadow-2xs">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- 1. Featured Spotlight Cards --}}
            @if(isset($featuredProducts) && $featuredProducts->isNotEmpty())
            <div class="mb-10">
                <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-4">
                    ⭐ Motif Rekomendasi Utama:
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($featuredProducts as $product)
                        @php
                            $displayMedia = $product->primary_media;
                            $imgUrl = ($displayMedia && $displayMedia->media_type === 'image' && !empty($displayMedia->formatted_url)) 
                                ? $displayMedia->formatted_url 
                                : ($product->primary_image ?: asset('assets/logo_indoroster_no_text.PNG'));
                            $itemWaUrl = "https://wa.me/{$waNumber}?text=" . urlencode("Halo Admin IndoRoster, saya ingin konsultasi & pesan motif: {$product->name} (Halaman: {$page->h1}). Mohon info stok dan total biaya kirim.");
                        @endphp
                        <div class="bg-slate-50 dark:bg-slate-800/70 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 p-4 sm:p-5 flex flex-col sm:flex-row gap-5 items-center hover:border-terra-400 transition-all">
                            <a href="{{ route('product.detail', $product->slug) }}" class="w-full sm:w-44 h-44 bg-white dark:bg-slate-900 rounded-xl overflow-hidden flex items-center justify-center flex-shrink-0 p-3 border border-slate-200/60 dark:border-slate-700 relative group">
                                <img src="{{ $imgUrl }}" 
                                     alt="Roster {{ $product->name }}" 
                                     class="max-w-full max-h-full object-contain transition-transform duration-500 group-hover:scale-105" 
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='{{ asset('assets/logo_indoroster_no_text.PNG') }}'; this.className='w-16 h-16 object-contain opacity-70';">
                                <span class="absolute top-2 left-2 px-2 py-0.5 rounded bg-slate-900/80 text-white text-[9px] font-bold">
                                    📏 {{ $product->dimensions ?: '20×20×10 cm' }}
                                </span>
                            </a>

                            <div class="flex-1 flex flex-col justify-between w-full">
                                <div>
                                    <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-terra-100 dark:bg-terra-950 text-terra-700 dark:text-terra-300 border border-terra-200 dark:border-terra-800">
                                            {{ $product->category->name ?? 'Roster Minimalis' }}
                                        </span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            Siku 90° Plat
                                        </span>
                                    </div>
                                    <h4 class="font-extrabold text-sm sm:text-base text-slate-900 dark:text-white line-clamp-1 mb-1.5">
                                        {{ $product->name }}
                                    </h4>
                                    <div class="flex items-center gap-1 text-[9px] text-slate-500 dark:text-slate-400 mb-2 flex-wrap">
                                        <span class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">⚪ Dolomit</span>
                                        <span class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">⚫ Abu Pasir</span>
                                        <span class="px-1.5 py-0.5 rounded bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700">🔴 Merah Genteng</span>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-slate-200 dark:border-slate-700">
                                    <div class="flex items-center justify-between gap-2 mb-2.5">
                                        <span class="text-xs text-slate-400 font-medium">Harga Pabrik:</span>
                                        <span class="text-sm font-black text-[#ee4d2d] dark:text-terra-400">
                                            {{ $product->formatted_price_range }}
                                        </span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row items-center gap-2">
                                        <a href="{{ $itemWaUrl }}" target="_blank" rel="noopener noreferrer" class="w-full sm:flex-1 py-2 px-3 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold flex items-center justify-center gap-1.5 shadow-xs transition-all whitespace-nowrap">
                                            <svg class="w-4 h-4 fill-current flex-shrink-0" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                            <span>Pesan via WA</span>
                                        </a>
                                        <a href="{{ route('product.detail', $product->slug) }}" class="w-full sm:w-auto py-2 px-3 rounded-lg bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 text-xs font-bold border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition text-center whitespace-nowrap">
                                            Lihat Detail &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- 2. Extended Catalog Grid (Curated Preview 8 Items) --}}
            @if(isset($explorerProducts) && $explorerProducts->isNotEmpty())
            <div>
                <div class="flex items-center justify-between gap-4 mb-4">
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        🧱 Motif Paling Populer (Preview 8 Motif):
                    </h3>
                    <a href="{{ route('catalog') }}" class="text-xs font-bold text-terra-600 dark:text-terra-400 hover:underline">
                        Lihat Semua 45+ Motif di Katalog &rarr;
                    </a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
                    @foreach($explorerProducts as $expProduct)
                        @php
                            $expImgUrl = $expProduct->primary_image ?: asset('assets/logo_indoroster_no_text.PNG');
                            $expWaUrl = "https://wa.me/{$waNumber}?text=" . urlencode("Halo Admin IndoRoster, saya berminat dengan motif: {$expProduct->name}. Mohon info harga grosir dan stok.");
                        @endphp
                        <div class="bg-slate-50 dark:bg-slate-800/70 rounded-xl border border-slate-200/80 dark:border-slate-700/80 p-3 flex flex-col justify-between hover:border-terra-400 hover:shadow-sm transition-all group">
                            <div>
                                <a href="{{ route('product.detail', $expProduct->slug) }}" class="w-full aspect-square bg-white dark:bg-slate-900 rounded-lg overflow-hidden flex items-center justify-center p-2 border border-slate-200/60 dark:border-slate-700 mb-2.5 relative block">
                                    <img src="{{ $expImgUrl }}" 
                                         alt="Roster {{ $expProduct->name }}" 
                                         class="max-w-full max-h-full object-contain transition-transform duration-500 group-hover:scale-105" 
                                         loading="lazy"
                                         onerror="this.onerror=null; this.src='{{ asset('assets/logo_indoroster_no_text.PNG') }}'; this.className='w-12 h-12 object-contain opacity-70';">
                                    <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 rounded bg-slate-900/80 text-white text-[8px] font-bold">
                                        {{ $expProduct->dimensions ?: '20×20×10 cm' }}
                                    </span>
                                </a>
                                <h4 class="font-bold text-xs text-slate-900 dark:text-white line-clamp-1 mb-1">
                                    <a href="{{ route('product.detail', $expProduct->slug) }}" class="hover:text-terra-600 transition">
                                        {{ $expProduct->name }}
                                    </a>
                                </h4>
                                <span class="text-xs font-black text-[#ee4d2d] dark:text-terra-400 block mb-2">
                                    {{ $expProduct->formatted_price_range }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <a href="{{ $expWaUrl }}" target="_blank" rel="noopener noreferrer" class="flex-1 py-1.5 rounded-md bg-emerald-600 hover:bg-emerald-500 text-white text-[11px] font-bold flex items-center justify-center gap-1 shadow-2xs transition">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                    <span>Tanya Stok WA</span>
                                </a>
                                <a href="{{ route('product.detail', $expProduct->slug) }}" class="p-1.5 rounded-md bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 text-[11px] hover:bg-slate-300 dark:hover:bg-slate-600 transition" title="Lihat Detail">
                                    🔍
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Backlink Banner to Full Catalog --}}
                <div class="p-5 rounded-2xl bg-gradient-to-r from-slate-100 via-slate-50 to-slate-100 dark:from-slate-800 dark:via-slate-800/80 dark:to-slate-800 border border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
                    <div>
                        <h4 class="font-extrabold text-sm text-slate-900 dark:text-white">Ingin Menjelajahi Seluruh Pilihan Motif?</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tersedia lebih dari 45+ motif roster minimalis, nako jalusi anti-tampias, dan ornamen 3D di katalog produk utama kami.</p>
                    </div>
                    <a href="{{ route('catalog') }}" class="px-5 py-2.5 rounded-xl bg-terra-600 hover:bg-terra-500 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-md hover:shadow-lg transition-all flex-shrink-0">
                        <span>Buka Katalog Lengkap 45+ Motif &rarr;</span>
                    </a>
                </div>
            </div>
            @endif
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             7. QUANTITY / SIZE SPECIFICATIONS (MODUL 20x20x10 CM & m²)
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-10 mb-14 shadow-soft-xs">
            <div class="max-w-3xl mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">Standar Modular & Dimensi</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1">
                    Spesifikasi Teknis & Panduan Perhitungan Kebutuhan m²
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                    Standar ukuran modular loster IndoRoster dirancang presisi untuk memudahkan perhitungan tukang dan arsitek di lapangan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 not-prose mb-8">
                <div class="p-6 rounded-3xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                    <div class="text-2xl mb-2">📐</div>
                    <h4 class="font-bold text-slate-900 dark:text-white text-base mb-1">Rasio Luas 25 Pcs / m²</h4>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                        Ukuran modular 20 × 20 × 10 cm menghasilkan tepat 25 keping per 1 meter persegi luas bidang dinding pasangan.
                    </p>
                </div>
                <div class="p-6 rounded-3xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                    <div class="text-2xl mb-2">⚖️</div>
                    <h4 class="font-bold text-slate-900 dark:text-white text-base mb-1">Bobot Padat 3.8 – 4.2 kg</h4>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                        Kepadatan agregat pasir abu batu murni menghasilkan struktur padat kokoh yang tahan guncangan dan benturan.
                    </p>
                </div>
                <div class="p-6 rounded-3xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                    <div class="text-2xl mb-2">📐</div>
                    <h4 class="font-bold text-slate-900 dark:text-white text-base mb-1">Margin Cadangan 3% – 5%</h4>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                        Selalu tambahkan cadangan 3–5% untuk antisipasi pemotongan keping di sudut nat kolom tukang saat pemasangan.
                    </p>
                </div>
            </div>

            {{-- 3 Bahan Alami Transparansi --}}
            <div class="p-6 rounded-2xl bg-amber-50/50 dark:bg-amber-950/20 border border-amber-200/60 dark:border-amber-900/40">
                <h4 class="font-extrabold text-sm text-slate-900 dark:text-white mb-2">3 Pilihan Bahan Alami Murni IndoRoster (Tanpa Cat Semprot):</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div>
                        <strong class="text-slate-900 dark:text-white block mb-0.5">⚫ Pasir Abu Batu Natural</strong>
                        <p class="text-slate-600 dark:text-slate-300">Warna abu-abu semen unfinish alami dari pasir abu batu murni bergradasi rapat.</p>
                    </div>
                    <div>
                        <strong class="text-slate-900 dark:text-white block mb-0.5">⚪ Dolomit Putih Susu / Cream Alami</strong>
                        <p class="text-slate-600 dark:text-slate-300">Batuan dolomit alami berkarakter putih susu hingga cream hangat anti-lumut hitam.</p>
                    </div>
                    <div>
                        <strong class="text-slate-900 dark:text-white block mb-0.5">🔴 Terakota Merah Bata</strong>
                        <p class="text-slate-600 dark:text-slate-300">Bubuk genteng tanah liat bakar khas Plered yang sejuk menyerap panas.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             8. ORDERING & LOGISTICS (TATA CARA PEMESANAN & ARMADA TRUK)
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-10 mb-14 shadow-soft-xs">
            <div class="max-w-3xl mb-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold uppercase tracking-wider mb-3">
                    <span>🚚</span> Layanan Pengiriman Langsung Pabrik Plered
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1">
                    Jalur Ekspedisi, Estimasi Pengiriman & Alur Pemesanan
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                    Pengadaan roster beton di IndoRoster dikirim langsung dari sentra pabrik Plered Purwakarta menggunakan armada truk sendiri dengan perlindungan Garansi 100% Bebas Pecah di Tempat.
                </p>
            </div>

            {{-- 3 Logistics Highlight Cards (Like Rank #1 Location Page) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700">
                    <div class="w-10 h-10 rounded-xl bg-terra-500/10 text-terra-600 dark:text-terra-400 flex items-center justify-center text-xl font-bold mb-3">🚚</div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Jalur Distribusi Langsung Pabrik</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Rute pengiriman harian via Tol Cipularang, JORR, Jagorawi, Tol Trans-Jawa & Penyeberangan Ferry Trans-Sumatera langsung ke gerbang proyek Anda.
                    </p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl font-bold mb-3">⏱️</div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Estimasi Waktu Sampai</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        <strong>1 – 2 Hari Kerja</strong> untuk wilayah Jabodetabek, Banten & Jawa Barat. Terjadwal rapi dan koordinasi langsung dengan sopir armada.
                    </p>
                </div>
                <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center text-xl font-bold mb-3">🛡️</div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Garansi 100% Bebas Pecah</h3>
                    <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                        Setiap keping yang mengalami retak atau gompal saat dibongkar di lokasi akan <strong>langsung diganti keping baru di tempat</strong> oleh sopir kami.
                    </p>
                </div>
            </div>

            {{-- Free Shipping & Truck Options Banner --}}
            <div class="p-6 rounded-2xl bg-gradient-to-r from-emerald-50 via-teal-50/50 to-emerald-50 dark:from-emerald-950/30 dark:via-slate-800/80 dark:to-emerald-950/30 border border-emerald-200/80 dark:border-emerald-800/60 mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-emerald-600 text-white text-[10px] font-black uppercase tracking-wider mb-2">
                            PROMO FASILITAS GRATIS ONGKIR
                        </div>
                        <h4 class="font-extrabold text-base text-slate-900 dark:text-white">Gratis Biaya Pengiriman Khusus Jabodetabek, Banten & Jawa Barat</h4>
                        <p class="text-xs text-slate-600 dark:text-slate-300 mt-1">Dapatkan fasilitas subsidi ongkir hingga 100% GRATIS untuk pemesanan volume armada truk tertentu langsung ke lokasi Anda.</p>
                    </div>
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-md transition flex-shrink-0">
                        <span>Klaim Promo Ongkir via WA &rarr;</span>
                    </a>
                </div>

                <div class="mt-5 pt-4 border-t border-emerald-200/60 dark:border-emerald-800/40 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-3.5 rounded-xl bg-white/80 dark:bg-slate-900/80 border border-emerald-100 dark:border-emerald-900/40">
                        <strong class="text-slate-900 dark:text-white flex items-center gap-1.5 mb-1">
                            <span>🚛</span> Armada Truk Colt Diesel (Muatan 800 – 1.200 pcs)
                        </strong>
                        <p class="text-slate-600 dark:text-slate-300 text-[11px] leading-relaxed">Pilihan tepat dan fleksibel untuk kebutuhan renovasi rumah tinggal, ruko, kafe, pagar, dan jalan pemukiman.</p>
                    </div>
                    <div class="p-3.5 rounded-xl bg-white/80 dark:bg-slate-900/80 border border-emerald-100 dark:border-emerald-900/40">
                        <strong class="text-slate-900 dark:text-white flex items-center gap-1.5 mb-1">
                            <span>🚚</span> Armada Truk Fuso / Tronton (Muatan 2.500 – 4.000 pcs)
                        </strong>
                        <p class="text-slate-600 dark:text-slate-300 text-[11px] leading-relaxed">Opsi paling ekonomis untuk proyek tender kontraktor, perumahan cluster developer, dan pengiriman luar pulau.</p>
                    </div>
                </div>
            </div>

            {{-- 3 Step Ordering Process --}}
            <div>
                <h4 class="font-bold text-sm text-slate-900 dark:text-white mb-4">3 Langkah Mudah Pemesanan Roster:</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 not-prose">
                    <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/60">
                        <div class="flex items-center gap-2.5 mb-2">
                            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white font-black text-xs flex items-center justify-center">1</span>
                            <h5 class="font-bold text-xs text-slate-900 dark:text-white">Konsultasi & Share Location</h5>
                        </div>
                        <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed">Kirimkan motif dan luas dinding via WhatsApp. Tim sales kami akan menghitung total kebutuhan keping dan tarif ongkir terbaik.</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/60">
                        <div class="flex items-center gap-2.5 mb-2">
                            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white font-black text-xs flex items-center justify-center">2</span>
                            <h5 class="font-bold text-xs text-slate-900 dark:text-white">Muat & Berangkat Langsung</h5>
                        </div>
                        <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed">Barang ditata rapi menggunakan jerami/palet pelindung benturan di bak truk pabrik Plered dan langsung meluncur ke lokasi Anda.</p>
                    </div>
                    <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200/60 dark:border-slate-700/60">
                        <div class="flex items-center gap-2.5 mb-2">
                            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white font-black text-xs flex items-center justify-center">3</span>
                            <h5 class="font-bold text-xs text-slate-900 dark:text-white">Bongkar & Ganti Baru di Tempat</h5>
                        </div>
                        <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed">Sopir membongkar muatan di lokasi Anda. Jika ditemukan keping yang retak, langsung ditukar keping baru saat itu juga.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             9. TANYA JAWAB UMUM (FAQ ACCORDION)
        ══════════════════════════════════════════════════════════════ --}}
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-10 mb-14 shadow-soft-xs">
            <div class="max-w-3xl mb-8">
                <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">Tanya Jawab</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1">
                    Pertanyaan yang Sering Diajukan Seputar {{ $page->primary_keyword }}
                </h2>
            </div>

            <div class="space-y-4 max-w-4xl" x-data="{ activeAccordion: null }">
                <div class="rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden bg-slate-50 dark:bg-slate-800/50">
                    <button @click="activeAccordion = (activeAccordion === 1 ? null : 1)" class="w-full p-5 text-left flex items-center justify-between font-bold text-sm text-slate-900 dark:text-white">
                        <span>Berapa ukuran standar dan kebutuhan loster per meter persegi?</span>
                        <span class="text-terra-600 text-lg" x-text="activeAccordion === 1 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeAccordion === 1" class="px-5 pb-5 text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed border-t border-slate-200 dark:border-slate-700 pt-4">
                        Ukuran modular standar loster IndoRoster adalah 20 × 20 × 10 cm. Kebutuhannya tepat 25 keping per 1 meter persegi luas dinding. Kami menyarankan menambahkan cadangan 3–5% untuk potongan nat tukang di sudut dinding.
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden bg-slate-50 dark:bg-slate-800/50">
                    <button @click="activeAccordion = (activeAccordion === 2 ? null : 2)" class="w-full p-5 text-left flex items-center justify-between font-bold text-sm text-slate-900 dark:text-white">
                        <span>Apakah bisa pesan partai besar untuk proyek kontraktor atau developer?</span>
                        <span class="text-terra-600 text-lg" x-text="activeAccordion === 2 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeAccordion === 2" class="px-5 pb-5 text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed border-t border-slate-200 dark:border-slate-700 pt-4">
                        Sangat bisa. Kami memiliki kapasitas produksi besar di sentra Plered Purwakarta (mencapai 10.000 keping per bulan) dan siap melayani kontrak pengadaan berkala, SPK resmi, serta penerbitan nota dan kwitansi resmi pabrik.
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden bg-slate-50 dark:bg-slate-800/50">
                    <button @click="activeAccordion = (activeAccordion === 3 ? null : 3)" class="w-full p-5 text-left flex items-center justify-between font-bold text-sm text-slate-900 dark:text-white">
                        <span>Bagaimana jika ada keping yang rusak atau pecah saat pengiriman tiba?</span>
                        <span class="text-terra-600 text-lg" x-text="activeAccordion === 3 ? '−' : '+'"></span>
                    </button>
                    <div x-show="activeAccordion === 3" class="px-5 pb-5 text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed border-t border-slate-200 dark:border-slate-700 pt-4">
                        Seluruh pengiriman armada pabrik IndoRoster dilindungi Garansi Bebas Pecah 100%. Setiap keping yang rusak saat perjalanan akan langsung diganti baru di tempat tanpa biaya tambahan apapun.
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             9.5. CAKUPAN KAWASAN & AREA LAYANAN (ENTITY AUTHORITY & SILO LINKING)
        ══════════════════════════════════════════════════════════════ --}}
        @if(isset($topLocations) && $topLocations->isNotEmpty())
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-10 mb-14 shadow-soft-xs">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400">Jaringan Logistik & Wilayah</span>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mt-1">
                        Cakupan Kawasan & Area Distribusi Terkait
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
                        Armada pabrik IndoRoster melayani pengiriman harian bergaransi langsung ke kawasan proyek dan perumahan berikut:
                    </p>
                </div>
                <a href="{{ route('location.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-terra-600 dark:text-terra-400 hover:underline whitespace-nowrap flex-shrink-0">
                    Lihat Semua 100+ Wilayah &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                @foreach($topLocations as $topLoc)
                <a href="{{ route('location.detail', $topLoc->slug) }}" class="group p-3.5 sm:p-4 rounded-2xl bg-slate-50/80 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 border border-slate-200/70 dark:border-slate-700/60 hover:border-terra-500 dark:hover:border-terra-500 hover:shadow-soft-md transition-all duration-300 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-1.5 text-[11px] font-bold text-terra-600 dark:text-terra-400 mb-1">
                            <span>📍</span>
                            <span class="truncate">{{ $topLoc->type === 'cluster' ? 'Kawasan Klaster' : 'Wilayah / Kota' }}</span>
                        </div>
                        <h4 class="font-bold text-slate-800 dark:text-slate-200 text-xs sm:text-sm group-hover:text-terra-600 dark:group-hover:text-terra-400 transition-colors line-clamp-1">
                            {{ $topLoc->name }}
                        </h4>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 line-clamp-2 mt-1 leading-snug">
                            {{ $topLoc->meta_description ?: 'Suplai roster beton presisi harga pabrik langsung ke lokasi proyek ' . $topLoc->name . '.' }}
                        </p>
                    </div>
                    <div class="mt-3 pt-2 border-t border-slate-200/50 dark:border-slate-700/50 flex items-center justify-between text-[10px] font-semibold text-slate-500 dark:text-slate-400 group-hover:text-terra-600 dark:group-hover:text-terra-400">
                        <span>Pesan Roster</span>
                        <span class="transform group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════
             10. FINAL CTA GATEWAY B2B & WHATSAPP DIRECT
        ══════════════════════════════════════════════════════════════ --}}
        <div class="rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white p-8 sm:p-12 lg:p-16 relative overflow-hidden border border-slate-700 shadow-2xl text-center">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-terra-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 max-w-3xl mx-auto">
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-terra-500/20 border border-terra-500/30 text-terra-300 text-xs font-bold uppercase tracking-wider mb-4">
                    🤝 Pabrik Tangan Pertama IndoRoster
                </span>
                <h2 class="text-2xl sm:text-4xl font-black tracking-tight mb-4">
                    Wujudkan Dinding Roster Impian Anda Bersama IndoRoster
                </h2>
                <p class="text-sm sm:text-base text-slate-300 mb-8 leading-relaxed">
                    Dapatkan penawaran harga pabrik tangan pertama, konsultasi motif arsitektural gratis, dan kepastian pengiriman bergaransi langsung ke lokasi Anda.
                </p>

                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-black text-sm sm:text-base shadow-xl shadow-emerald-600/30 transition-all hover:scale-105">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                        <span>Hubungi Sales Pabrik via WhatsApp</span>
                    </a>
                    <a href="https://drive.google.com/file/d/1wcBxdEv7yiytPlLSVE1ldl1rYpe0MHZZ/view?usp=drive_link" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-sm border border-slate-700 transition">
                        <span>Unduh Katalog PDF</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
