@props([
    'badge' => null,
    'title' => null,
    'description' => null,
    'payments' => null,
    'shippings' => null,
])

@php
    $trustBadge = $badge ?: \App\Models\SiteSetting::getValue('trust_section_badge', 'Pusat Pabrik Roster Beton Plered Purwakarta');
    $trustTitle = $title ?: \App\Models\SiteSetting::getValue('trust_section_title', 'Nikmati Kemudahan & Keamanan Belanja Roster Tangan Pertama di IndoRoster');
    $trustDesc = $description ?: \App\Models\SiteSetting::getValue('trust_section_description', 'Selamat datang di IndoRoster, sentra produksi dan distribusi aneka motif roster beton minimalis, bata tempel dinding, dan loster modern 20x20x10 cm tangan pertama dari Plered, Purwakarta. Kami melayani pemesanan proyek skala kecil maupun ribuan pieces tanpa perantara dengan harga pabrik yang transparan. Nikmati pengiriman cepat armada sendiri untuk kawasan Jabodetabek & Jawa Barat serta kargo khusus material aman ke seluruh Indonesia dengan garansi ganti baru 100% jika pecah.');
    
    if (is_array($payments)) {
        // use as is
    } elseif (is_string($payments) && !empty($payments)) {
        $payments = array_filter(array_map('trim', explode(',', $payments)));
    } else {
        $rawPayments = \App\Models\SiteSetting::getValue('trust_section_payments', 'BCA, Mandiri, BNI, BRI, BSI, CIMB, Permata, QRIS, GoPay, ShopeePay, DANA, OVO');
        $payments = array_filter(array_map('trim', explode(',', $rawPayments)));
    }

    if (is_array($shippings)) {
        // use as is
    } elseif (is_string($shippings) && !empty($shippings)) {
        $shippings = array_filter(array_map('trim', explode(',', $shippings)));
    } else {
        $rawShippings = \App\Models\SiteSetting::getValue('trust_section_shippings', 'Armada Truk IndoRoster, Ekspedisi Kargo Material, JNE Trucking, Dakota Cargo, Indah Logistik, SiCepat, Pos Indonesia');
        $shippings = array_filter(array_map('trim', explode(',', $rawShippings)));
    }
@endphp

<div class="mt-16 pt-12 border-t border-slate-200/80 dark:border-slate-800 font-sans w-full">
    <!-- SEO & Edukasi Marketplace Pabrik (Full Width Container) -->
    <div class="w-full bg-slate-50 dark:bg-slate-900/60 rounded-3xl p-6 sm:p-8 lg:p-10 border border-slate-200/70 dark:border-slate-800/80 mb-10 transition-colors">
        <div class="w-full">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-terra-50 dark:bg-terra-950/50 text-terra-600 dark:text-terra-400 text-xs font-bold uppercase tracking-wider mb-3">
                <span>🏭</span>
                <span>{{ $trustBadge }}</span>
            </div>
            <h2 class="font-display text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight mb-3">
                {{ $trustTitle }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-6">
                {{ $trustDesc }}
            </p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 pt-4 border-t border-slate-200/70 dark:border-slate-800 text-xs font-medium">
                <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                    <span class="w-4 h-4 rounded-full bg-terra-100 dark:bg-terra-950 text-terra-600 dark:text-terra-400 flex items-center justify-center font-bold text-[10px]">✓</span>
                    <span>Harga Pabrik Tanpa Mark-up</span>
                </div>
                <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                    <span class="w-4 h-4 rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold text-[10px]">✓</span>
                    <span>Garansi Pecah Diganti Baru</span>
                </div>
                <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                    <span class="w-4 h-4 rounded-full bg-blue-100 dark:bg-blue-950 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-[10px]">✓</span>
                    <span>Cetak Padat Presisi Siku</span>
                </div>
                <div class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                    <span class="w-4 h-4 rounded-full bg-amber-100 dark:bg-amber-950 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-[10px]">✓</span>
                    <span>Beli Cepat Tanpa Wajib Akun</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Logos & Badges: Metode Pembayaran & Jasa Pengiriman (Persis seperti Toco Foto 2) -->
    <div class="w-full grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">
        
        <!-- Metode Pembayaran Resmi -->
        <div class="space-y-3">
            <h3 class="font-display text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                <span class="w-1.5 h-3.5 bg-terra-500 rounded-full"></span>
                <span>Metode Pembayaran</span>
            </h3>
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5 pt-1">
                @foreach($payments as $pay)
                    @php
                        $isHighlight = in_array(strtoupper($pay), ['QRIS', 'GOPAY', 'SHOPEEPAY', 'DANA']);
                    @endphp
                    <div class="h-9 px-3.5 rounded-xl bg-white dark:bg-slate-800 border {{ $isHighlight ? 'border-terra-300 dark:border-terra-700 text-terra-700 dark:text-terra-300 font-black' : 'border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold' }} shadow-2xs flex items-center justify-center text-xs tracking-tight transition-all hover:scale-105 hover:border-terra-400 select-none">
                        @if(strtoupper($pay) === 'BCA')
                            <span class="text-blue-700 dark:text-blue-400 font-black tracking-wide">BCA</span>
                        @elseif(strtoupper($pay) === 'MANDIRI')
                            <span class="text-amber-600 dark:text-amber-400 font-black tracking-wide">mandiri</span>
                        @elseif(strtoupper($pay) === 'BNI')
                            <span class="text-orange-600 dark:text-orange-400 font-black tracking-wide">BNI</span>
                        @elseif(strtoupper($pay) === 'BRI')
                            <span class="text-blue-800 dark:text-blue-300 font-black tracking-wide">BRI</span>
                        @elseif(strtoupper($pay) === 'BSI')
                            <span class="text-emerald-600 dark:text-emerald-400 font-black tracking-wide">BSI</span>
                        @elseif(strtoupper($pay) === 'QRIS')
                            <span class="text-red-600 dark:text-red-400 font-black tracking-wider">QRIS</span>
                        @elseif(strtoupper($pay) === 'GOPAY')
                            <span class="text-sky-600 dark:text-sky-400 font-black">go<span class="text-emerald-500">pay</span></span>
                        @elseif(strtoupper($pay) === 'SHOPEEPAY')
                            <span class="text-orange-500 font-black">Shopee<span class="text-amber-500">Pay</span></span>
                        @elseif(strtoupper($pay) === 'DANA')
                            <span class="text-sky-500 font-black tracking-wide">DANA</span>
                        @elseif(strtoupper($pay) === 'OVO')
                            <span class="text-purple-600 dark:text-purple-400 font-black">OVO</span>
                        @else
                            <span>{{ $pay }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Jasa Pengiriman & Ekspedisi Logistik -->
        <div class="space-y-3">
            <h3 class="font-display text-xs font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                <span class="w-1.5 h-3.5 bg-emerald-500 rounded-full"></span>
                <span>Jasa Pengiriman</span>
            </h3>
            <div class="flex flex-wrap items-center gap-2 sm:gap-2.5 pt-1">
                @foreach($shippings as $ship)
                    @php
                        $isArmada = str_contains(strtolower($ship), 'armada') || str_contains(strtolower($ship), 'indoroster');
                    @endphp
                    <div class="h-9 px-3.5 rounded-xl bg-white dark:bg-slate-800 border {{ $isArmada ? 'border-amber-300 dark:border-amber-700 bg-amber-50/50 dark:bg-amber-950/40 text-amber-900 dark:text-amber-200 font-black' : 'border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-200 font-bold' }} shadow-2xs flex items-center justify-center text-xs tracking-tight transition-all hover:scale-105 select-none">
                        @if($isArmada)
                            <span class="flex items-center gap-1.5">
                                <span class="text-amber-600 dark:text-amber-400">🚚</span>
                                <span>{{ $ship }}</span>
                            </span>
                        @elseif(str_contains(strtolower($ship), 'jne'))
                            <span class="text-blue-700 dark:text-blue-400 font-black italic">JNE <span class="text-red-500 not-italic font-bold text-[10px]">Trucking</span></span>
                        @elseif(str_contains(strtolower($ship), 'sicepat'))
                            <span class="text-red-600 dark:text-red-400 font-black italic">SiCepat</span>
                        @elseif(str_contains(strtolower($ship), 'dakota'))
                            <span class="text-slate-900 dark:text-white font-black">DAKOTA <span class="text-terra-500 text-[10px]">Cargo</span></span>
                        @elseif(str_contains(strtolower($ship), 'indah'))
                            <span class="text-orange-600 dark:text-orange-400 font-black">INDAH <span class="text-slate-600 dark:text-slate-300 text-[10px]">Logistik</span></span>
                        @elseif(str_contains(strtolower($ship), 'pos'))
                            <span class="text-orange-500 font-black">Pos<span class="text-blue-600 text-[10px]">Aja!</span></span>
                        @else
                            <span>{{ $ship }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
