@props(['data', 'pageTitle' => null])

@php
    $badge = $data['badge'] ?? 'Katalog Produk · 5 Kategori';
    $title = $data['title'] ?? 'Roster, Genteng, Bata Ekspose, Bata Tempel & Ornamen 3D';
    $subtitle = $data['subtitle'] ?? 'Tiap kategori kami tampilkan satu foto unggulan. Motif lengkapnya banyak banget — minta brosur GRATIS via WhatsApp untuk lihat semuanya.';
    
    $rawTheme = $data['bg_theme'] ?? ($data['theme'] ?? 'white');
    $theme = \App\Helpers\BlockTheme::resolve($rawTheme);

    $rawWa = \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
    $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
    if (str_starts_with($waNumber, '0')) {
        $waNumber = '62' . substr($waNumber, 1);
    }

    $categories = $data['categories'] ?? [
        [
            'category_badge' => 'Roster (Ventilasi Udara)',
            'name' => 'Roster Ventilasi Udara',
            'size' => 'Ukuran: 30 x 30 cm (beragam ukuran)',
            'desc' => '90+ motif roster lebih — kembang, lilit, tulip, batu, kotak, dan banyak lagi. Untuk ventilasi, pagar, fasad, dan interior. Motif lainnya lihat brosur lengkap gratis.',
            'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765262980/2_zurmam.jpg',
            'is_bestseller' => true,
        ],
        [
            'category_badge' => 'Genteng',
            'name' => 'Genteng Viral (program gentengisasi)',
            'size' => 'Ukuran: Sesuai model genteng',
            'desc' => 'Genteng kokoh, presisi, dan tahan cuaca ekstrem. Cocok untuk rumah tinggal hingga bangunan komersial. Model & warna lengkap di brosur gratis.',
            'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260885/189153683_1030631617471276_2071152964924271585_n_wbq1kg.jpg',
            'is_bestseller' => false,
        ],
        [
            'category_badge' => 'Bata Ekspose',
            'name' => 'Bata Ekspose',
            'size' => 'Ukuran: 5 x 10 / 10 x 20 cm',
            'desc' => 'Bata ekspose berkualitas dengan tekstur alami yang estetik — tanpa plester, tampilan industrial & hangat. Favorit kafe, rumah modern, dan vila.',
            'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259930/47_dmjh8d.jpg',
            'is_bestseller' => false,
        ],
        [
            'category_badge' => 'Bata Tempel',
            'name' => 'Bata Tempel',
            'size' => 'Ukuran: 5 x 15 / 7,5 x 20 cm',
            'desc' => 'Bata tempel tipis praktis untuk dinding rumah, pagar, dan taman. Instalasi cepat, hasil rapi seperti bata asli. Pilihan warna & tekstur di brosur gratis.',
            'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765259923/34_li9387.jpg',
            'is_bestseller' => false,
        ],
        [
            'category_badge' => 'Ornamen Dinding 3D',
            'name' => 'Ornamen Dinding 3D',
            'size' => 'Ukuran: Sesuai model ornamen',
            'desc' => 'Panel ornamen dinding 3D beton — bikin dinding biasa jadi pusat perhatian. Cocok untuk ruang tamu, kafe, hotel, dan backdrop. Desain lengkap di brosur gratis.',
            'image' => 'https://res.cloudinary.com/indoroster/image/upload/v1765260025/210781640_1049103868957384_7584920712298347840_n_jhvxju.jpg',
            'is_bestseller' => false,
        ],
    ];

    // Brosur Box config
    $showBrosurBox = $data['show_brosur_box'] ?? true;
    $brosurTitle = $data['brosur_title'] ?? 'Ada 90+ Motif Roster Lainnya di Brosur — dan Brosurnya GRATIS';
    $brosurDesc = $data['brosur_desc'] ?? 'Halaman ini cuma menampilkan satu foto per kategori. Motif lengkap ada di brosur — tinggal minta via WhatsApp, langsung kami kirim. Punya foto referensi produk? Kirim juga boleh, kami cocokkan.';
    $brosurBtnText = $data['brosur_btn_text'] ?? 'Download Brosur Gratis via WhatsApp';
@endphp

<section class="py-14 sm:py-20 lg:py-24 relative overflow-hidden font-sans {{ $theme->bgClasses }} transition-colors duration-300">
    <x-blocks._bg-theme :theme="$theme" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        {{-- Header Section --}}
        <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
            @if(!empty($badge))
                <span class="inline-block px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-3.5 {{ $theme->badgeClass }}">
                    {{ $badge }}
                </span>
            @endif
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight leading-tight mb-4 {{ $theme->headingColor }}">
                {{ $title }}
            </h2>
            @if(!empty($subtitle))
                <p class="text-sm sm:text-base md:text-lg leading-relaxed {{ $theme->subColor }}">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        {{-- Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-7 mb-14">
            @foreach($categories as $item)
                @php
                    $cBadge = $item['category_badge'] ?? 'Produk';
                    $cName = $item['name'] ?? '';
                    $cSize = $item['size'] ?? '';
                    $cDesc = $item['desc'] ?? '';
                    $cImg = !empty($item['image_upload']) ? asset('storage/' . $item['image_upload']) : ($item['image'] ?? 'https://res.cloudinary.com/indoroster/image/upload/v1765262980/2_zurmam.jpg');
                    $isBest = !empty($item['is_bestseller']);
                    
                    $waMsgBrosur = urlencode("Halo IndoRoster! 👋\n\nSaya mau minta *BROSUR GRATIS* motif lengkap untuk kategori:\n• {$cName}\n\nTolong dikirimkan ya, terima kasih!");
                    $waMsgTanya = urlencode("Halo IndoRoster! 👋\n\nSaya mau tanya stok & harga untuk produk:\n• {$cName} ({$cSize})\n\nBisa dibantu?");
                @endphp
                
                <article class="group flex flex-col h-full rounded-2xl overflow-hidden border transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl {{ $theme->cardBg }} hover:border-amber-400/50">
                    
                    {{-- Media Card Container --}}
                    <div class="relative aspect-square overflow-hidden bg-slate-100 dark:bg-slate-800">
                        <img 
                            src="{{ $cImg }}" 
                            alt="{{ $cName }}" 
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                            loading="lazy"
                        >

                        {{-- Badges Atas --}}
                        <div class="absolute top-3 left-3 flex items-center gap-1.5 z-10">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-600 text-white shadow-sm">
                                ✓ Ready Produksi
                            </span>
                        </div>
                        @if($isBest)
                            <div class="absolute top-3 right-3 z-10">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold bg-amber-500 text-white shadow-sm">
                                    Terlaris
                                </span>
                            </div>
                        @endif

                        {{-- Badge Melayang Brosur Gratis Bawah Foto --}}
                        <div class="absolute bottom-3 left-3 right-3 flex justify-center z-10">
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[11px] font-bold bg-white/95 dark:bg-slate-900/95 text-slate-800 dark:text-slate-100 shadow-md backdrop-blur-xs border border-slate-200/60 dark:border-slate-700">
                                Brosur motif lengkap: <strong class="text-amber-600 dark:text-amber-400">GRATIS 🎁</strong>
                            </span>
                        </div>
                    </div>

                    {{-- Content Info --}}
                    <div class="p-5 sm:p-6 flex flex-col flex-grow">
                        <span class="text-xs font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400 mb-1">
                            {{ $cBadge }}
                        </span>
                        <h3 class="text-lg sm:text-xl font-bold mb-1 leading-snug {{ $theme->cardTitle }}">
                            {{ $cName }}
                        </h3>
                        @if(!empty($cSize))
                            <p class="text-xs font-semibold text-slate-400 dark:text-slate-500 mb-3">
                                {{ $cSize }}
                            </p>
                        @endif
                        <p class="text-xs sm:text-sm leading-relaxed mb-6 {{ $theme->cardDesc }}">
                            {{ $cDesc }}
                        </p>

                        {{-- 2 Action Buttons --}}
                        <div class="mt-auto space-y-2">
                            <a href="https://wa.me/{{ $waNumber }}?text={{ $waMsgBrosur }}" target="_blank" rel="noopener"
                               class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-xs sm:text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] transition-all shadow-md shadow-emerald-600/20">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.584 1.777.818 2.791.818 3.179 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.77-5.767-5.772zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/></svg>
                                <span>Minta Brosur Gratis via WA</span>
                            </a>
                            <a href="https://wa.me/{{ $waNumber }}?text={{ $waMsgTanya }}" target="_blank" rel="noopener"
                               class="w-full inline-flex items-center justify-center gap-2 py-2 px-4 rounded-xl text-xs sm:text-sm font-semibold border transition-colors border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <span>Tanya Stok & Harga</span>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- KOTAK DOWNLOAD BROSUR ESTETIK (DASHED BOX KUNING - SESUAI FOTO 5) --}}
        @if($showBrosurBox)
            <div class="max-w-4xl mx-auto rounded-3xl p-8 sm:p-10 border-2 border-dashed border-amber-400/80 dark:border-amber-500/50 bg-amber-50/40 dark:bg-slate-900/80 text-center relative shadow-sm">
                
                {{-- Download Icon --}}
                <div class="w-12 h-12 mx-auto mb-4 rounded-2xl bg-amber-500/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </div>

                {{-- Judul Kotak --}}
                <h3 class="text-xl sm:text-2xl font-black mb-3 text-slate-900 dark:text-white leading-tight">
                    {{ $brosurTitle }}
                </h3>

                {{-- Deskripsi --}}
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 max-w-2xl mx-auto leading-relaxed mb-6">
                    {{ $brosurDesc }}
                </p>

                {{-- Tombol Unduh Brosur via WA --}}
                @php
                    $waMsgGeneralBrosur = urlencode("Halo IndoRoster! 👋\n\nSaya mau minta *BROSUR LENGKAP 90+ MOTIF ROSTER & DAFTAR HARGA*.\n\nTolong dikirimkan ke nomor ini ya, terima kasih!");
                @endphp
                <div class="inline-block">
                    <a href="https://wa.me/{{ $waNumber }}?text={{ $waMsgGeneralBrosur }}" target="_blank" rel="noopener"
                       class="inline-flex items-center justify-center gap-2.5 px-8 py-3.5 sm:py-4 rounded-full font-bold text-sm sm:text-base text-white bg-emerald-600 hover:bg-emerald-700 active:scale-95 shadow-lg shadow-emerald-600/30 transition-all">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.584 1.777.818 2.791.818 3.179 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.77-5.767-5.772zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/></svg>
                        <span>{{ $brosurBtnText }}</span>
                    </a>
                    <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400 mt-2.5">
                        100% gratis · tanpa syarat · langsung dari produsen
                    </p>
                </div>

            </div>
        @endif

    </div>
</section>

