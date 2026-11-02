@props(['data', 'pageTitle' => null])

@php
    $badge = $data['badge'] ?? 'Semudah Chat';
    $title = $data['title'] ?? 'Cara Pesannya Gampang Banget';
    $subtitle = $data['subtitle'] ?? 'Tanpa formulir panjang dan tanpa drama — seperti chat dengan tetangga sendiri.';
    $rawTheme = $data['bg_theme'] ?? ($data['theme'] ?? 'warm');
    $theme = \App\Helpers\BlockTheme::resolve($rawTheme);
    
    $steps = $data['steps'] ?? [
        [
            'number' => '1',
            'title' => 'Lihat Kategori & Minta Brosur',
            'text' => 'Lihat koleksi produk di halaman ini, lalu minta brosur motif lengkap gratis via WhatsApp.'
        ],
        [
            'number' => '2',
            'title' => 'Pilih Motif Favorit',
            'text' => 'Dari brosur, tentukan motifnya. Bisa juga kirim foto referensi kalau punya contoh sendiri.'
        ],
        [
            'number' => '3',
            'title' => 'Deal & Konfirmasi',
            'text' => 'Sepakati jumlah & jadwal. Pembayaran bisa transfer atau COD untuk area tertentu.'
        ],
        [
            'number' => '4',
            'title' => 'Kirim & Pasang',
            'text' => 'Pesanan dijemput atau kami antar rapi — gratis ongkir Jabodetabek min. 50 pcs.'
        ],
    ];

    $ctaText = $data['cta_text'] ?? 'Mulai Chat WhatsApp Sekarang';
    $ctaUrl = $data['cta_url'] ?? 'https://wa.me/6281234567890?text=Halo%20IndoRoster,%20saya%20mau%20pesan%20roster';
@endphp

<section class="py-14 sm:py-20 lg:py-24 relative overflow-hidden font-sans {{ $theme->bgClasses }} transition-colors duration-300">
    <x-blocks._bg-theme :theme="$theme" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        {{-- Section Header --}}
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

        {{-- 4 Steps Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 relative">
            @foreach($steps as $idx => $step)
                @php
                    $num = $step['number'] ?? ($idx + 1);
                    $sTitle = $step['title'] ?? '';
                    $sText = $step['text'] ?? '';
                @endphp
                <div class="relative rounded-2xl p-6 border flex flex-col items-start transition-all duration-300 hover:shadow-lg {{ $theme->cardBg }} hover:border-amber-400/50">
                    
                    {{-- Step Number Pill --}}
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-base mb-5 shadow-sm bg-amber-500 text-white">
                        {{ $num }}
                    </div>

                    <h3 class="text-base sm:text-lg font-bold mb-2.5 {{ $theme->cardTitle }}">
                        {{ $sTitle }}
                    </h3>
                    <p class="text-xs sm:text-sm leading-relaxed {{ $theme->cardDesc }}">
                        {{ $sText }}
                    </p>
                </div>
            @endforeach
        </div>

        {{-- Bottom CTA button --}}
        @if(!empty($ctaText))
            <div class="text-center mt-12">
                <a href="{{ $ctaUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2.5 px-8 py-4 rounded-full font-bold text-sm sm:text-base text-white bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-600/30 transition-transform hover:scale-105 active:scale-95">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.584 1.777.818 2.791.818 3.179 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.77-5.767-5.772zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/>
                    </svg>
                    <span>{{ $ctaText }}</span>
                </a>
            </div>
        @endif
    </div>
</section>
