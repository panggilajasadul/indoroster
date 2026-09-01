@props([
    'segment' => 'kontraktor', // kontraktor, developer, arsitek, supplier, project
    'highlightScale' => 'borongan', // eceran, borongan, partai-besar, kontrak-rutin
])

@php
    $rawWa = \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
    $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
    if (str_starts_with($waNumber, '0')) {
        $waNumber = '62' . substr($waNumber, 1);
    }

    $scaleCards = [
        [
            'id' => 'eceran',
            'badge' => 'Eceran & Sampel',
            'title' => 'Skala Eceran & Renovasi Rumah',
            'range' => '< 500 pcs (1 – 20 m²)',
            'desc' => 'Layanan pemesanan fleksibel untuk renovasi rumah tinggal, dinding pagar kecil, atau sampel mock-up arsitek. Packing rapi, bisa kirim via armada engkel/pick-up.',
            'features' => [
                'Garansi 100% Bebas Pecah di Jalan',
                'Bisa Campur Motif Tertentu',
                'Pengiriman Cepat 1 Hari Kerja Jabodetabek & Bandung',
            ],
            'cta' => 'Pesan Eceran (WhatsApp)',
            'wa_text' => "Halo Admin IndoRoster, saya ingin order roster beton skala eceran (< 500 pcs) untuk renovasi. Mohon info ketersediaan stok & estimasi ongkir.",
        ],
        [
            'id' => 'borongan',
            'badge' => 'Borongan Kontraktor',
            'title' => 'Skala Borongan & Kontraktor Menengah',
            'range' => '500 – 2.000 pcs (20 – 80 m²)',
            'desc' => 'Ditujukan untuk kontraktor, pemborong bangunan, dan cafe/ruko komersial. Dapatkan diskon harga borongan langsung dari lini produksi pabrik Plered.',
            'features' => [
                'Harga Bertingkat Khusus Proyek',
                'Surat Jalan Resmi & Faktur Komersial',
                'Jadwal Kirim Fleksibel Mengikuti Cor Dinding',
            ],
            'cta' => 'Minta Harga Borongan',
            'wa_text' => "Halo Tim Sales IndoRoster, saya Kontraktor ingin minta penawaran harga borongan (500-2.000 pcs) untuk proyek dinding. Mohon kirimkan pricelist proyek.",
        ],
        [
            'id' => 'partai-besar',
            'badge' => 'Partai Besar & Tender',
            'title' => 'Skala Partai Besar / Ritase Truk',
            'range' => '2.000 – 10.000 pcs (80 – 400 m²)',
            'desc' => 'Solusi pengadaan ribuan keping untuk proyek gedung, hotel, tender kontraktor utama, dan cluster perumahan. Kapasitas pabrik 10.000 pcs/bulan siap melayani.',
            'features' => [
                'Diskon Volume Grosir Maksimal',
                'Pengiriman Ritase Truk Terjadwal Bertahap',
                'Kelengkapan Dokumen Faktur Pajak & NIB/SIUP',
            ],
            'cta' => 'Konsultasi Partai Besar',
            'wa_text' => "Halo Tim IndoRoster, kami membutuhkan pengadaan roster beton partai besar (2.000-10.000 pcs) untuk proyek gedung/klaster. Mohon jadwalkan konsultasi penawaran tender.",
        ],
        [
            'id' => 'kontrak-rutin',
            'badge' => 'Kontrak Developer & Reseller',
            'title' => 'Kontrak Suplai Rutin & Berkelanjutan',
            'range' => '> 10.000 pcs / Kontrak Tahunan',
            'desc' => 'Dukungan suplai jangka panjang untuk developer perumahan skala ratusan unit, jaringan depo bahan bangunan, dan distributor material provinsi.',
            'features' => [
                'Harga Pabrik Terkunci Selama Masa Kontrak',
                'Prioritas Alokasi Cetak Harian di Pabrik',
                'SLA Garansi Pengiriman & Penggantian Terikat Kontrak',
            ],
            'cta' => 'Bahas Kontrak Suplai',
            'wa_text' => "Halo Manajemen IndoRoster, kami dari Developer / Distributor ingin membahas perjanjian kontrak suplai rutin roster beton (> 10.000 pcs). Mohon info PIC pengadaan.",
        ],
    ];
@endphp

<div class="my-16 scroll-mt-24" id="skala-pesanan">
    <div class="text-center max-w-3xl mx-auto mb-12">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-terra-500/10 border border-terra-500/30 text-terra-600 dark:text-terra-400 text-xs font-bold uppercase tracking-wider mb-3">
            <span>📊</span> Kapasitas Produksi & Tingkatan Skala Pemesanan
        </div>
        <h2 class="text-2xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
            Melayani Mulai dari <span class="text-terra-500">Renovasi Kecil</span> hingga <span class="text-terra-500">Puluhan Ribu Pcs</span>
        </h2>
        <p class="text-slate-600 dark:text-slate-400 mt-3 text-sm sm:text-base leading-relaxed">
            Pabrik IndoRoster Plered Purwakarta memiliki kapasitas produksi hingga 10.000 pcs/bulan dengan lini cetak tumbuk padat plat baja presisi untuk memenuhi setiap tahapan skala proyek Anda.
        </p>
    </div>

    <!-- Scale Jump-Links Quick Tabs -->
    <div class="flex items-center justify-center gap-2 flex-wrap mb-10">
        <span class="text-xs font-bold text-slate-400 mr-2">Pintasan Skala:</span>
        <a href="#eceran" class="px-3.5 py-1.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-300 hover:border-terra-500 hover:text-terra-500 transition shadow-2xs">
            # Eceran (&lt;500 pcs)
        </a>
        <a href="#borongan" class="px-3.5 py-1.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-300 hover:border-terra-500 hover:text-terra-500 transition shadow-2xs">
            # Borongan (500–2.000 pcs)
        </a>
        <a href="#partai-besar" class="px-3.5 py-1.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-300 hover:border-terra-500 hover:text-terra-500 transition shadow-2xs">
            # Partai Besar (2.000–10.000 pcs)
        </a>
        <a href="#kontrak-rutin" class="px-3.5 py-1.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-xs font-bold text-slate-700 dark:text-slate-300 hover:border-terra-500 hover:text-terra-500 transition shadow-2xs">
            # Kontrak Rutin (&gt;10.000 pcs)
        </a>
    </div>

    <!-- Scale Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($scaleCards as $card)
        @php
            $isHighlighted = ($highlightScale === $card['id']);
            $cardWaUrl = "https://wa.me/{$waNumber}?text=" . urlencode($card['wa_text']);
        @endphp
        <div id="{{ $card['id'] }}" class="scroll-mt-28 rounded-3xl p-6 sm:p-7 flex flex-col justify-between transition-all duration-300 {{ $isHighlighted ? 'bg-gradient-to-b from-terra-500/10 via-white to-white dark:from-terra-500/10 dark:via-slate-900 dark:to-slate-900 border-2 border-terra-500 shadow-soft-lg' : 'bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-soft-xs hover:shadow-soft-md hover:border-slate-300 dark:hover:border-slate-700' }}">
            <div>
                <div class="flex items-center justify-between gap-2 mb-4">
                    <span class="px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider {{ $isHighlighted ? 'bg-terra-500 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300' }}">
                        {{ $card['badge'] }}
                    </span>
                    <a href="#{{ $card['id'] }}" class="text-xs font-mono text-slate-400 hover:text-terra-500" title="Copy Anchor Link">#{{ $card['id'] }}</a>
                </div>

                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1.5 leading-snug">
                    {{ $card['title'] }}
                </h3>

                <div class="text-xs font-black text-terra-600 dark:text-terra-400 mb-3 font-mono">
                    {{ $card['range'] }}
                </div>

                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-5">
                    {{ $card['desc'] }}
                </p>

                <div class="space-y-2 mb-6 pt-4 border-t border-slate-100 dark:border-slate-800">
                    @foreach($card['features'] as $feat)
                    <div class="flex items-start gap-2 text-xs text-slate-700 dark:text-slate-300">
                        <span class="text-emerald-500 font-bold shrink-0">✓</span>
                        <span class="leading-tight">{{ $feat }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <a href="{{ $cardWaUrl }}" target="_blank" rel="noopener noreferrer" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 transition-all {{ $isHighlighted ? 'bg-terra-500 hover:bg-terra-600 text-white shadow-md shadow-terra-500/20 hover:scale-[1.02]' : 'bg-slate-900 dark:bg-slate-800 hover:bg-terra-500 dark:hover:bg-terra-500 text-white hover:scale-[1.02]' }}">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                <span>{{ $card['cta'] }}</span>
            </a>
        </div>
        @endforeach
    </div>
</div>
