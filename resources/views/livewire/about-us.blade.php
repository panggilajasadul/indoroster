<div>
    @if($page && is_array($page->content) && count($page->content) > 0)
        <x-block-renderer :blocks="$page->content" :page-title="$page->title ?? 'Tentang Kami'" />
    @else
    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-10 sm:py-16 md:py-20 overflow-hidden transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative font-sans">
            <x-breadcrumb :items="[['label' => 'Tentang Kami']]" class="!px-0 !py-0 mb-8 sm:mb-12" />
            
            <!-- Hero Header Section -->
            <div class="text-center max-w-4xl mx-auto mb-16 md:mb-20">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-terra-50 dark:bg-terra-950/60 border border-terra-200/80 dark:border-terra-900/60 text-terra-600 dark:text-terra-400 text-xs sm:text-sm font-bold uppercase tracking-wider mb-6 shadow-soft-xs">
                    <span>🏭</span>
                    <span>Sentra Produksi Plered Purwakarta</span>
                </div>
                
                <h1 class="font-display text-fluid-h1 font-black text-slate-900 dark:text-white tracking-tight leading-tight uppercase mb-6">
                    Tentang <span class="text-terra-600 dark:text-terra-400">IndoRoster</span>
                    <br class="hidden sm:inline"> Pabrik Roster Beton & Material Dinding Arsitektural
                </h1>
                
                <p class="text-slate-600 dark:text-slate-300 text-base sm:text-lg lg:text-xl leading-relaxed font-normal">
                    Lahir dari sentra kerajinan dan pabrikasi beton Plered, Purwakarta, <strong>IndoRoster</strong> memproduksi langsung aneka motif <a href="{{ route('catalog') }}" class="text-terra-600 dark:text-terra-400 font-semibold hover:underline">roster beton minimalis</a>, bata tempel dinding, dan ornamen arsitektur untuk kebutuhan rumah tinggal hingga proyek komersial di seluruh Indonesia.
                </p>
            </div>

            <!-- Pernyataan Fokus Bisnis: Murni Pabrik Material -->
            <div class="bg-gradient-to-br from-slate-900 via-slate-850 to-slate-900 dark:from-slate-900 dark:to-slate-950 rounded-3xl p-8 sm:p-12 text-white shadow-xl mb-16 md:mb-24 border border-slate-800 relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-terra-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-8">
                        <div class="inline-flex items-center gap-2 text-xs font-black tracking-widest text-terra-400 uppercase bg-terra-950/60 px-3.5 py-1 rounded-full border border-terra-800/80 mb-4">
                            <span>🛡️</span>
                            <span>Prinsip Usaha Kami</span>
                        </div>
                        <h2 class="font-display text-fluid-h2 font-black text-white mb-4 tracking-tight">
                            Murni Produsen Tangan Pertama, Tanpa Biaya Jasa Konsultan
                        </h2>
                        <p class="text-slate-300 text-sm sm:text-base leading-relaxed mb-4">
                            Kami adalah <strong>pabrik manufaktur material</strong>, bukan biro konsultan arsitektur atau jasa kontraktor borongan. Fokus kami jelas: mencetak roster beton yang presisi, padat, dan rapi agar tukang Anda di lapangan bisa memasangnya dengan cepat dan hemat adukan semen.
                        </p>
                        <p class="text-slate-400 text-xs sm:text-sm leading-relaxed">
                            Bagi pemilik rumah, arsitek, mandor, dan pengembang proyek di kawasan Jabodetabek maupun kota lainnya, Anda bertransaksi langsung dengan produsen di sentra Plered—tanpa mata rantai perantara yang membuat anggaran bengkak.
                        </p>
                    </div>
                    <div class="lg:col-span-4 flex flex-col gap-3 sm:gap-4">
                        <a href="{{ route('catalog') }}" class="font-display inline-flex items-center justify-center bg-terra-500 hover:bg-terra-600 text-white font-bold py-4 px-6 rounded-2xl transition-all duration-200 shadow-lg shadow-terra-500/25 text-center text-sm uppercase tracking-wider">
                            <span>🛒 Cek Katalog & Harga Pabrik</span>
                        </a>
                        <a href="{{ route('gallery') }}" class="font-display inline-flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/20 text-white font-bold py-3.5 px-6 rounded-2xl transition-all duration-200 text-center text-sm">
                            <span>📸 Lihat Foto Proyek Terpasang</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Standar Teknis & Fakta Lapangan (stats.md integration) -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 sm:p-12 border border-slate-200/80 dark:border-slate-800 shadow-soft-xs mb-16 md:mb-24">
                <div class="max-w-3xl mb-8">
                    <div class="inline-flex items-center gap-2 text-xs font-bold text-terra-600 dark:text-terra-400 uppercase bg-terra-50 dark:bg-terra-950/50 px-3.5 py-1 rounded-full mb-3">
                        <span>📐</span>
                        <span>Standar Teknis & Produksi</span>
                    </div>
                    <h2 class="font-display text-fluid-h2 font-black text-slate-900 dark:text-white uppercase tracking-tight mb-3">
                        Fakta Teknis Material IndoRoster
                    </h2>
                    <p class="text-slate-600 dark:text-slate-400 text-sm sm:text-base">
                        Setiap unit roster beton dicetak dengan takaran pasir abu batu murni dan semen pilihan berteknik cetak tumbuk padat untuk ketahanan cuaca tropis jangka panjang.
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                        <div class="text-3xl font-black font-mono text-terra-600 dark:text-terra-400 mb-1">20×20×10 cm</div>
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-2">Dimensi Standar Presisi</div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            Ukuran modul simetris dengan sudut siku tegak lurus, memudahkan penyelarasan nat dinding.
                        </p>
                    </div>

                    <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                        <div class="text-3xl font-black font-mono text-terra-600 dark:text-terra-400 mb-1">25 pcs / m²</div>
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-2">Rasio Kebutuhan Bidang</div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            Rumus hitung praktis: luas dinding (m²) dikalikan 25 pcs ditambah cadangan toleransi 3–5%.
                        </p>
                    </div>

                    <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                        <div class="text-3xl font-black font-mono text-terra-600 dark:text-terra-400 mb-1">~4,5 kg / pcs</div>
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-2">Bobot Padat & Mantap</div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            Material padat tanpa rongga udara keropos, kokoh menahan terpaan angin dan hujan deras.
                        </p>
                    </div>

                    <div class="p-6 rounded-2xl bg-slate-50 dark:bg-slate-950/60 border border-slate-200/80 dark:border-slate-800">
                        <div class="text-3xl font-black font-mono text-terra-600 dark:text-terra-400 mb-1">50+ Motif</div>
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-900 dark:text-white mb-2">Pilihan Desain & Warna</div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                            Varian warna Abu Beton, Putih Dolomit, Gravel, dan Merah Terakota untuk aneka gaya arsitektur.
                        </p>
                    </div>
                </div>
            </div>

            <!-- 4 Keunggulan Belanja di Pabrik IndoRoster -->
            <div class="mb-16 md:mb-24">
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <h2 class="font-display text-fluid-h2 font-black text-slate-900 dark:text-white uppercase tracking-tight mb-3">
                        Mengapa Membeli Langsung dari IndoRoster?
                    </h2>
                    <p class="text-slate-600 dark:text-slate-400 text-sm sm:text-base">
                        Keuntungan bertransaksi langsung dengan produsen tangan pertama di sentra beton Plered Purwakarta.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Keunggulan 1 -->
                    <div class="bg-white dark:bg-slate-900 rounded-3xl p-7 border border-slate-200/80 dark:border-slate-800 shadow-soft-xs hover:border-terra-500/50 dark:hover:border-terra-500/50 transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="w-14 h-14 bg-terra-50 dark:bg-terra-950/60 text-terra-600 dark:text-terra-400 rounded-2xl flex items-center justify-center text-2xl mb-6">
                                🏭
                            </div>
                            <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white mb-2">Harga Pabrik Langsung</h3>
                            <p class="text-slate-600 dark:text-slate-400 text-xs sm:text-sm leading-relaxed">
                                Tanpa perantara distributor atau toko material perantara. Harga lebih hemat untuk anggaran renovasi rumah maupun proyek skala besar.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 text-[11px] font-bold text-terra-600 dark:text-terra-400 uppercase tracking-wider">
                            #DirectFromFactory
                        </div>
                    </div>

                    <!-- Keunggulan 2 -->
                    <div class="bg-white dark:bg-slate-900 rounded-3xl p-7 border border-slate-200/80 dark:border-slate-800 shadow-soft-xs hover:border-terra-500/50 dark:hover:border-terra-500/50 transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="w-14 h-14 bg-terra-50 dark:bg-terra-950/60 text-terra-600 dark:text-terra-400 rounded-2xl flex items-center justify-center text-2xl mb-6">
                                📐
                            </div>
                            <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white mb-2">Presisi & Siap Pasang</h3>
                            <p class="text-slate-600 dark:text-slate-400 text-xs sm:text-sm leading-relaxed">
                                Cetakan baja rapi menghasilkan permukaan halus dan siku yang lurus. Pengerjaan tukang di lapangan menjadi lebih cepat dan efisien.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 text-[11px] font-bold text-terra-600 dark:text-terra-400 uppercase tracking-wider">
                            #PresisiTinggi
                        </div>
                    </div>

                    <!-- Keunggulan 3 -->
                    <div class="bg-white dark:bg-slate-900 rounded-3xl p-7 border border-slate-200/80 dark:border-slate-800 shadow-soft-xs hover:border-terra-500/50 dark:hover:border-terra-500/50 transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="w-14 h-14 bg-terra-50 dark:bg-terra-950/60 text-terra-600 dark:text-terra-400 rounded-2xl flex items-center justify-center text-2xl mb-6">
                                ✨
                            </div>
                            <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white mb-2">Pilihan Motif Komplit</h3>
                            <p class="text-slate-600 dark:text-slate-400 text-xs sm:text-sm leading-relaxed">
                                Dari motif minimalis modern, geometris industrial, hingga pola klasik untuk pagar, sekat partisi, void, dan fasad rumah tropis.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 text-[11px] font-bold text-terra-600 dark:text-terra-400 uppercase tracking-wider">
                            #KatalogTerlengkap
                        </div>
                    </div>

                    <!-- Keunggulan 4 -->
                    <div class="bg-white dark:bg-slate-900 rounded-3xl p-7 border border-slate-200/80 dark:border-slate-800 shadow-soft-xs hover:border-terra-500/50 dark:hover:border-terra-500/50 transition-all duration-300 flex flex-col justify-between">
                        <div>
                            <div class="w-14 h-14 bg-terra-50 dark:bg-terra-950/60 text-terra-600 dark:text-terra-400 rounded-2xl flex items-center justify-center text-2xl mb-6">
                                🛡️
                            </div>
                            <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white mb-2">Garansi Pengiriman Aman</h3>
                            <p class="text-slate-600 dark:text-slate-400 text-xs sm:text-sm leading-relaxed">
                                Jika ada barang yang pecah atau retak saat pengiriman sampai di lokasi Anda, kami ganti baru tanpa biaya tambahan.
                            </p>
                        </div>
                        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 text-[11px] font-bold text-terra-600 dark:text-terra-400 uppercase tracking-wider">
                            #GaransiPecahGantiBaru
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jangkauan Pengiriman & Wilayah Layanan (Local SEO Section) -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 sm:p-12 border border-slate-200/80 dark:border-slate-800 shadow-soft-xs mb-16 md:mb-24">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    <div class="lg:col-span-6">
                        <div class="inline-flex items-center gap-2 text-xs font-bold text-terra-600 dark:text-terra-400 uppercase bg-terra-50 dark:bg-terra-950/50 px-3.5 py-1 rounded-full mb-3">
                            <span>🚚</span>
                            <span>Jangkauan Armada Pabrik</span>
                        </div>
                        <h2 class="font-display text-fluid-h2 font-black text-slate-900 dark:text-white uppercase tracking-tight mb-4">
                            Pengiriman Langsung Jabodetabek, Jawa Barat, & Luar Pulau
                        </h2>
                        <p class="text-slate-600 dark:text-slate-300 text-sm sm:text-base leading-relaxed mb-6">
                            Didukung armada pengiriman pabrik dan jaringan kargo material terpercaya, kami melayani pengiriman langsung ke alamat proyek:
                        </p>
                        <div class="space-y-3 text-xs sm:text-sm text-slate-700 dark:text-slate-300">
                            <div class="flex items-start gap-2.5">
                                <span class="text-terra-500 font-bold">📍</span>
                                <div><strong>Kawasan Jabodetabek:</strong> Jakarta (Selatan, Barat, Timur, Utara, Pusat), Bogor, Depok, Tangerang, Tangerang Selatan, dan Bekasi.</div>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <span class="text-terra-500 font-bold">📍</span>
                                <div><strong>Kawasan Jawa Barat:</strong> Purwakarta, Karawang, Cikampek, Subang, Bandung Raya, Cimahi, Sumedang, Cirebon, Indramayu, Sukabumi, dan Cianjur.</div>
                            </div>
                            <div class="flex items-start gap-2.5">
                                <span class="text-terra-500 font-bold">📍</span>
                                <div><strong>Antar Kota & Luar Pulau:</strong> Jawa Tengah, Jawa Timur, Bali, Sumatera, Kalimantan, Sulawesi via ekspedisi khusus material aman.</div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-6 bg-slate-50 dark:bg-slate-950/70 p-6 sm:p-8 rounded-2xl border border-slate-200/80 dark:border-slate-800">
                        <h3 class="font-display font-bold text-base text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                            <span>🏭</span>
                            <span>Alamat Pabrik & Sentra Produksi</span>
                        </h3>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                            <strong>INDOROSTER</strong><br>
                            Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar,<br>
                            Kecamatan Tegalwaru, Kabupaten Purwakarta,<br>
                            Jawa Barat 41165 — Indonesia
                        </p>
                        <div class="flex flex-wrap gap-3 pt-2">
                            @php
                                $adminWa = \App\Models\SiteSetting::getValue('whatsapp_number', '6281389709847');
                                $adminWa = preg_replace('/[^0-9]/', '', $adminWa);
                                if (str_starts_with($adminWa, '0')) {
                                    $adminWa = '62' . substr($adminWa, 1);
                                }
                            @endphp
                            <a href="https://wa.me/{{ $adminWa }}?text=Halo%20Admin%20Indoroster,%20saya%20ingin%20tanya%20stok%20roster%20dan%20ongkir" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                <span>💬 Hubungi Admin WhatsApp</span>
                            </a>
                            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 text-slate-800 dark:text-white rounded-xl text-xs font-bold transition-all">
                                <span>Lihat Peta Lokasi</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visual Showcase & Galeri Pabrik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16 md:mb-24">
                <div class="rounded-3xl overflow-hidden shadow-soft-xs border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 group">
                    <div class="h-60 overflow-hidden relative">
                        <img src="{{ asset('assets/indoroster-production-place.png') }}" alt="Pabrik Roster Beton Plered Purwakarta IndoRoster" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <span class="absolute bottom-3 left-4 text-xs font-bold text-white uppercase tracking-wider bg-black/40 backdrop-blur-xs px-3 py-1 rounded-full">Area Pabrik</span>
                    </div>
                    <div class="p-6">
                        <h3 class="font-display font-bold text-base text-slate-900 dark:text-white mb-2">Sentra Produksi Plered</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-xs leading-relaxed">
                            Kapasitas produksi 10.000 pcs per bulan dengan kontrol kualitas yang teliti di setiap proses pengeringan.
                        </p>
                    </div>
                </div>

                <div class="rounded-3xl overflow-hidden shadow-soft-xs border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 group">
                    <div class="h-60 overflow-hidden relative">
                        <img src="https://res.cloudinary.com/indoroster/image/upload/v1765262980/2_zurmam.jpg" alt="Roster Beton Minimalis Presisi" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <span class="absolute bottom-3 left-4 text-xs font-bold text-white uppercase tracking-wider bg-black/40 backdrop-blur-xs px-3 py-1 rounded-full">Katalog Produk</span>
                    </div>
                    <div class="p-6">
                        <h3 class="font-display font-bold text-base text-slate-900 dark:text-white mb-2">Koleksi 50+ Motif Modern</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-xs leading-relaxed">
                            Desain ventilasi udara dan pencahayaan alami yang estetik untuk pagar, sekat partisi, dan fasad rumah.
                        </p>
                    </div>
                </div>

                <div class="rounded-3xl overflow-hidden shadow-soft-xs border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 group">
                    <div class="h-60 overflow-hidden relative">
                        <img src="https://res.cloudinary.com/indoroster/image/upload/v1765260853/162067858_988931008308004_8757323712171815873_n_kpbq7h.jpg" alt="Pemasangan Roster Beton Fasad Minimalis" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <span class="absolute bottom-3 left-4 text-xs font-bold text-white uppercase tracking-wider bg-black/40 backdrop-blur-xs px-3 py-1 rounded-full">Proyek Nyata</span>
                    </div>
                    <div class="p-6">
                        <h3 class="font-display font-bold text-base text-slate-900 dark:text-white mb-2">Terpasang di Ribuan Proyek</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-xs leading-relaxed">
                            Telah dipercaya oleh arsitek, pemilik rumah, dan kontraktor di Jabodetabek serta berbagai kota di Indonesia.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Call to Action Banner -->
            <div class="bg-gradient-to-r from-terra-600 via-terra-500 to-terra-700 rounded-3xl p-8 sm:p-12 text-center text-white shadow-xl">
                <h2 class="font-display text-fluid-h2 font-black uppercase tracking-tight mb-4">
                    Siap Mempercantik Hunian atau Melengkapi Proyek Anda?
                </h2>
                <p class="text-terra-100 max-w-2xl mx-auto text-sm sm:text-base leading-relaxed mb-8">
                    Dapatkan katalog lengkap dengan harga pabrik langsung. Hubungi admin kami untuk cek ketersediaan stok motif dan estimasi ongkir ke kota Anda.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="{{ route('catalog') }}" class="font-display inline-flex items-center justify-center bg-white text-terra-600 hover:bg-slate-100 font-bold px-8 py-4 rounded-2xl shadow-md transition-all text-sm uppercase tracking-wider">
                        <span>Lihat Semua Produk Roster</span>
                    </a>
                    <a href="https://wa.me/{{ $adminWa }}?text=Halo%20Admin%20Indoroster,%20saya%20ingin%20konsultasi%20stok%20dan%20harga%20pabrik" target="_blank" rel="noopener" class="font-display inline-flex items-center justify-center bg-slate-900/80 hover:bg-slate-900 text-white font-bold px-8 py-4 rounded-2xl border border-white/20 transition-all text-sm">
                        <span>Konsultasi Stok via WhatsApp</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
    @endif
</div>
