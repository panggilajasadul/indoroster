@props(['data', 'pageTitle' => null])

@php
    $badge = $data['badge'] ?? 'KUNJUNGI KAMI';
    $title = $data['title'] ?? 'Mampir ke Produksi, atau Chat Aja Lebih Cepat';
    $subtitle = $data['subtitle'] ?? 'Silakan lihat langsung kualitas roster kami di lokasi — atau kalau sibuk, WhatsApp juga bisa banget.';
    
    $rawTheme = $data['bg_theme'] ?? ($data['theme'] ?? 'white');
    $theme = \App\Helpers\BlockTheme::resolve($rawTheme);

    $rawWa = \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
    $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
    if (str_starts_with($waNumber, '0')) {
        $waNumber = '62' . substr($waNumber, 1);
    }

    $address = $data['address'] ?? \App\Models\SiteSetting::getValue('factory_address') ?? 'Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165';
    $mapsUrl = $data['maps_url'] ?? 'https://maps.google.com/?q=Kp+Cicadas+Desa+Cadasmekar+Tegalwaru+Purwakarta';
    $hours = $data['hours'] ?? 'Senin – Sabtu: 07.00 – 17.00 WIB · Minggu: 08.00 – 14.00 WIB';
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

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
            
            {{-- Kiri: 3 Kotak Info (Alamat, WA, Jam Operasional) --}}
            <div class="lg:col-span-6 flex flex-col justify-between gap-4">
                
                {{-- Box 1: Alamat --}}
                <div class="rounded-2xl p-6 border flex items-start gap-4 transition-all hover:shadow-md {{ $theme->cardBg }}">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 dark:bg-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-sm sm:text-base font-bold mb-1 {{ $theme->cardTitle }}">Alamat Produksi</h4>
                        <p class="text-xs sm:text-sm leading-relaxed mb-2.5 {{ $theme->cardDesc }}">{{ $address }}</p>
                        <a href="{{ $mapsUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 text-xs font-bold text-amber-600 dark:text-amber-400 hover:underline">
                            <span>Buka di Google Maps</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Box 2: WhatsApp --}}
                <div class="rounded-2xl p-6 border flex items-start gap-4 transition-all hover:shadow-md {{ $theme->cardBg }}">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div class="flex-grow">
                        <h4 class="text-sm sm:text-base font-bold mb-1 {{ $theme->cardTitle }}">WhatsApp / Telepon</h4>
                        <p class="text-xs sm:text-sm font-semibold mb-3 text-slate-800 dark:text-slate-200">{{ $rawWa }}</p>
                        <a href="https://wa.me/{{ $waNumber }}?text=Halo%20IndoRoster!%20%F0%9F%91%8B%0ASaya%20mau%20konsultasi%20langsung." target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition-all">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.584 1.777.818 2.791.818 3.179 0 5.766-2.587 5.767-5.766.001-3.182-2.585-5.77-5.767-5.772zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.174.086.275.072.376-.043.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/></svg>
                            <span>Chat Sekarang</span>
                        </a>
                    </div>
                </div>

                {{-- Box 3: Jam Operasional --}}
                <div class="rounded-2xl p-6 border flex items-start gap-4 transition-all hover:shadow-md {{ $theme->cardBg }}">
                    <div class="w-10 h-10 rounded-xl bg-slate-500/10 dark:bg-slate-500/20 text-slate-600 dark:text-slate-400 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-sm sm:text-base font-bold mb-1 {{ $theme->cardTitle }}">Jam Operasional</h4>
                        <p class="text-xs sm:text-sm leading-relaxed {{ $theme->cardDesc }}">{{ $hours }}</p>
                    </div>
                </div>

            </div>

            {{-- Kanan: Peta Google Maps Visual --}}
            <div class="lg:col-span-6 w-full min-h-[340px]">
                <div class="w-full h-full min-h-[340px] rounded-3xl overflow-hidden border shadow-xl relative {{ $theme->cardBg }}">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3961.234!2d107.4520!3d-6.6830!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e690b0b0b0b0b0b%3A0x0!2sKp.%20Cicadas%2C%20Desa%20Cadasmekar%2C%20Tegalwaru%2C%20Purwakarta!5e0!3m2!1sid!2sid!4v1680000000000!5m2!1sid!2sid" 
                        class="w-full h-full min-h-[340px] border-0" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>

        </div>
    </div>
</section>

