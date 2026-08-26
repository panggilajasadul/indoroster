@props(['data'])

@php
    $badge = $data['badge'] ?? 'HUBUNGI PABRIK LANGSUNG';
    $title = $data['title'] ?? 'Hubungi Pabrik & Konsultasi Proyek';
    $subtitle = $data['subtitle'] ?? 'Solusi roster beton minimalis berkualitas tinggi dari sentra Plered Purwakarta. Hubungi kami untuk konsultasi motif, RAB, sampel material, dan pengiriman ke seluruh Indonesia.';
    $alignment = $data['alignment'] ?? 'left';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');

    $rawWa = \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
    $email = \App\Models\SiteSetting::getValue('contact_email', 'abdulhamid66266@gmail.com');
    $address = \App\Models\SiteSetting::getValue('factory_address', 'Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165');
    
    $waFormatted = preg_replace('/[^0-9]/', '', $rawWa);
    if (str_starts_with($waFormatted, '0')) {
        $waFormatted = '+62 ' . substr($rawWa, 1);
    } else {
        $waFormatted = $rawWa;
    }

    $headerAlign = match($alignment) {
        'center' => 'text-center flex flex-col items-center mx-auto',
        'right' => 'text-right flex flex-col items-end',
        default => 'text-left flex flex-col items-start'
    };
@endphp

<section class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden font-sans">
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header -->
        <div class="{{ $headerAlign }} max-w-3xl mb-16">
            @if(!empty($badge))
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-black uppercase tracking-widest mb-4 shadow-soft-xs">
                <span>{{ $badge }}</span>
            </div>
            @endif

            <h2 class="text-3xl sm:text-4xl md:text-5xl font-black font-display {{ $theme->headingColor }} tracking-tight leading-tight mb-4">
                {!! $title !!}
            </h2>

            @if(!empty($subtitle))
            <p class="text-base sm:text-lg {{ $theme->subColor }} leading-relaxed">
                {!! $subtitle !!}
            </p>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">
            
            <!-- Left: Contact Details Cards -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Phone/WA Card -->
                <div class="p-7 sm:p-8 rounded-3xl {{ $theme->cardBg }} border shadow-soft-xs transition-all duration-300 hover:-translate-y-1 hover:border-terra-500/40">
                    <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-terra-500 mb-3">
                        <svg class="w-4 h-4 fill-current text-emerald-500" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span>Layanan WhatsApp Sales</span>
                    </div>
                    <div class="text-2xl sm:text-3xl font-black font-display text-slate-900 dark:text-white mb-1.5">{{ $waFormatted }}</div>
                    <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm">Respon Cepat: Senin – Sabtu (08.00 – 17.00 WIB)</p>
                </div>

                <!-- Email Card -->
                <div class="p-7 sm:p-8 rounded-3xl {{ $theme->cardBg }} border shadow-soft-xs transition-all duration-300 hover:-translate-y-1 hover:border-terra-500/40">
                    <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-terra-500 mb-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        <span>Email Resmi Pabrik</span>
                    </div>
                    <div class="text-lg sm:text-xl font-black font-display text-slate-900 dark:text-white mb-1.5 break-all">{{ $email }}</div>
                    <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm">Untuk permintaan penawaran resmi, invoice & tender proyek arsitektur.</p>
                </div>

                <!-- Address Card -->
                <div class="p-7 sm:p-8 rounded-3xl {{ $theme->cardBg }} border shadow-soft-xs transition-all duration-300 hover:-translate-y-1 hover:border-terra-500/40">
                    <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-terra-500 mb-3">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                        <span>Alamat Pabrik & Workshop</span>
                    </div>
                    <div class="text-sm sm:text-base font-bold text-slate-900 dark:text-white leading-relaxed mb-4">
                        {{ $address }}
                    </div>
                    <a href="https://maps.google.com/?q={{ urlencode($address) }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-wider text-terra-500 hover:text-terra-600">
                        <span>Buka Rute di Google Maps</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                </div>

                <!-- Factory Capacity Badge -->
                <div class="p-7 sm:p-8 rounded-3xl bg-slate-950 text-white border border-slate-800 relative overflow-hidden shadow-luxury">
                    <div class="absolute -right-8 -bottom-8 w-36 h-36 bg-terra-500/20 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="text-xs font-black uppercase tracking-widest text-terra-400 mb-2">PRODUKSI LANGSUNG PABRIK</div>
                    <h4 class="text-xl font-black font-display mb-2 text-white">Kapasitas Ribuan Pcs / Hari</h4>
                    <p class="text-slate-400 text-xs sm:text-sm leading-relaxed">
                        Sentra manufaktur Plered Purwakarta dengan presisi cetakan baja, melayani pesanan retail hunian maupun partai besar kontraktor ke seluruh Indonesia.
                    </p>
                </div>

            </div>

            <!-- Right: Interactive WhatsApp Form -->
            <div class="lg:col-span-7">
                <div class="p-8 sm:p-12 md:p-14 rounded-3xl {{ $theme->cardBg }} border shadow-luxury">
                    <div class="mb-8 pb-6 border-b border-slate-200/60 dark:border-slate-800/60">
                        <div class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-widest text-terra-500 mb-2">
                            <span>FORMULIR PENAWARAN HARGA</span>
                        </div>
                        <h3 class="text-2xl sm:text-3xl font-black font-display {{ $theme->headingColor }} tracking-tight">
                            Kirim Permintaan & Tanya Stok
                        </h3>
                        <p class="{{ $theme->subColor }} text-sm mt-1.5">
                            Isi formulir di bawah ini untuk konsultasi motif, simulasi ongkir, dan penawaran harga pabrik yang langsung terhubung ke WhatsApp Sales.
                        </p>
                    </div>

                    <form wire:submit.prevent="sendWhatsApp" class="space-y-6">
                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">Nama Lengkap</label>
                            <input type="text" wire:model="name" placeholder="Masukkan nama Anda..." 
                                class="w-full px-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-900 focus:border-terra-500 dark:focus:border-terra-500 text-slate-900 dark:text-white outline-none font-medium text-sm transition-all">
                            @error('name') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">Alamat Email</label>
                                <input type="email" wire:model="email" placeholder="contoh@email.com" 
                                    class="w-full px-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-900 focus:border-terra-500 dark:focus:border-terra-500 text-slate-900 dark:text-white outline-none font-medium text-sm transition-all">
                                @error('email') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">Nomor WhatsApp</label>
                                <input type="text" wire:model="phone" placeholder="08123456789" 
                                    class="w-full px-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-900 focus:border-terra-500 dark:focus:border-terra-500 text-slate-900 dark:text-white outline-none font-medium text-sm transition-all">
                                @error('phone') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-2">Pesan & Kebutuhan Material</label>
                            <textarea wire:model="message" rows="4" placeholder="Sebutkan jenis roster, estimasi jumlah kebutuhan (pcs/m²), dan kota tujuan pengiriman..." 
                                class="w-full px-4 py-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-950/50 focus:bg-white dark:focus:bg-slate-900 focus:border-terra-500 dark:focus:border-terra-500 text-slate-900 dark:text-white outline-none font-medium text-sm transition-all"></textarea>
                            @error('message') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full py-4 px-8 rounded-xl font-black uppercase text-xs tracking-widest flex items-center justify-center gap-3 shadow-lg bg-terra-500 hover:bg-terra-600 text-white cursor-pointer transition-all duration-300 hover:scale-[1.01]">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            <span>KIRIM PESAN LANGSUNG KE WHATSAPP</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</section>
