@props(['data', 'pageTitle' => null])

@php
    $badge = $data['badge'] ?? 'Kenapa IndoRoster?';
    $title = $data['title'] ?? 'Dibuat dengan Hati, Dikirim dengan Tanggung Jawab';
    $subtitle = $data['subtitle'] ?? 'Kami bukan sekadar jualan roster — kami bantu rumahmu jadi lebih sejuk, cantik, dan bernilai.';
    $rawTheme = $data['bg_theme'] ?? ($data['theme'] ?? 'warm');
    $theme = \App\Helpers\BlockTheme::resolve($rawTheme);
    
    $features = $data['features'] ?? [
        [
            'icon' => 'percent',
            'title' => 'Harga Langsung dari Produksi',
            'text' => 'Tidak lewat perantara. Kamu dapat harga pabrik untuk kualitas yang sama — hemat buat kebutuhan bangunan lain.'
        ],
        [
            'icon' => 'shield',
            'title' => 'Beton Padat & Awet Bertahun',
            'text' => 'Campuran dan proses pencetakan kami jaga ketat. Roster tidak mudah retak atau rapuh saat dipasang.'
        ],
        [
            'icon' => 'palette',
            'title' => '90+ Motif Lengkap, Bisa Custom',
            'text' => 'Dari motif kembang klasik sampai geometris modern. Tidak ada motifnya? Kirim foto referensi — kami buatkan.'
        ],
        [
            'icon' => 'truck',
            'title' => 'Gratis Ongkir Jabodetabek',
            'text' => 'Pembelian minimal 50 pcs, ongkir kami tanggung untuk area Jakarta, Bogor, Depok, Tangerang, Bekasi. Luar itu pun ramah di kantong.'
        ],
        [
            'icon' => 'chat',
            'title' => 'Bisa Konsultasi Dulu',
            'text' => 'Bingung pilih motif yang cocok? Ceritakan konsep rumahmu, kami bantu rekomendasikan gratis.'
        ],
        [
            'icon' => 'handshake',
            'title' => 'Pelayanan Ramah & Jujur',
            'text' => 'Sejak 2019 kami membangun kepercayaan pelanggan — banyak yang pesan kembali untuk proyek-proyek berikutnya.'
        ],
    ];
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

        {{-- 6 Feature Cards Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @foreach($features as $f)
                @php
                    $fTitle = $f['title'] ?? '';
                    $fText = $f['text'] ?? '';
                    $fIcon = $f['icon'] ?? 'check';
                @endphp
                <div class="group relative rounded-2xl p-6 sm:p-7 border transition-all duration-300 hover:-translate-y-1 hover:shadow-xl {{ $theme->cardBg }} hover:border-amber-400/50">
                    
                    {{-- Icon Box --}}
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-5 transition-transform group-hover:scale-110 duration-200 bg-amber-500/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                        @if($fIcon === 'percent')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zm10 5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                        @elseif($fIcon === 'shield')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        @elseif($fIcon === 'palette')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4 5 5 0 013-4.5 7 7 0 0110.5-5.5 7 7 0 013 7.5 4 4 0 01-4 4.5h-2a2 2 0 00-2 2v.5a2 2 0 01-2 2H7z"/></svg>
                        @elseif($fIcon === 'truck')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        @elseif($fIcon === 'chat')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        @elseif($fIcon === 'handshake')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        @endif
                    </div>

                    <h3 class="text-base sm:text-lg font-bold mb-2.5 {{ $theme->cardTitle }}">
                        {{ $fTitle }}
                    </h3>
                    <p class="text-xs sm:text-sm leading-relaxed {{ $theme->cardDesc }}">
                        {{ $fText }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
