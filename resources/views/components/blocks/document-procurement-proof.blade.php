@props(['data'])

@php
    $badge = $data['badge'] ?? 'DOKUMEN RESMI PABRIK & TRANSAKSI B2B';
    $title = $data['title'] ?? 'Kelengkapan Dokumen Transaksi Resmi & Administrasi Pengadaan Proyek';
    $subtitle = $data['subtitle'] ?? 'Spill lembar dokumen pengadaan asli pabrik siap terbit cepat. Transparansi penuh untuk pelaporan SPJ proyek, tanda terima material, kwitansi bermaterai, dan verifikasi kontraktor.';
    $theme = \App\Helpers\BlockTheme::resolve($data['bg_theme'] ?? 'white');

    $companyName = \App\Models\SiteSetting::getValue('doc_company_name') 
        ?? ($data['company_legal_name'] ?? 'INDOROSTER INDONESIA');
    $factoryAddress = \App\Models\SiteSetting::getValue('factory_address') 
        ?? 'Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165';
    $rawWa = \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
    $waNumClean = preg_replace('/[^0-9]/', '', $rawWa);
    if (str_starts_with($waNumClean, '0')) {
        $waNumClean = '62' . substr($waNumClean, 1);
    }
    
    $quickBadge1 = $data['quick_badge_1'] ?? '⚡ Terbit Cepat 1x24 Jam';
    $quickBadge2 = $data['quick_badge_2'] ?? '📜 Stempel Basah & TTD Pabrik Asli';
    $quickBadge3 = $data['quick_badge_3'] ?? '🏢 Siap Kontraktor & Pengadaan Proyek';

    $ctaTitle = $data['cta_title'] ?? 'Butuh Dokumen Penawaran Resmi (RAB / SPH / Kwitansi) Hari Ini?';
    $ctaBtnText = $data['cta_btn_text'] ?? 'Minta Dokumen Penawaran via WhatsApp';
    $ctaBtnLink = $data['cta_btn_link'] ?? '';

    if (empty($ctaBtnLink)) {
        $ctaBtnLink = "https://wa.me/{$waNumClean}?text=" . urlencode("Halo Tim Sales IndoRoster, saya membutuhkan kelengkapan dokumen resmi (Surat Jalan / Invoice / SPH / Kwitansi / Uji Lab) untuk keperluan pengadaan proyek.");
    }

    // Resolusi Stamp & Tanda Tangan Asli dari Database / Storage
    $docStampSetting = \App\Models\SiteSetting::getValue('doc_stamp_path');
    $docSignSetting = \App\Models\SiteSetting::getValue('doc_signature_path');
    $docLogoSetting = \App\Models\SiteSetting::getValue('doc_logo_path');

    $stampImg = !empty($docStampSetting) ? asset('storage/' . $docStampSetting) : asset('storage/document-assets/stamps/01M0QFEEYH7K6QVZ1YHDMW41FV.png');
    $signImg = !empty($docSignSetting) ? asset('storage/' . $docSignSetting) : asset('storage/document-assets/signatures/01M0QFEEYD0WMFCWNBBX0WFTRT.PNG');
    $logoImg = !empty($docLogoSetting) ? asset('storage/' . $docLogoSetting) : asset('assets/logo_indoroster-text.png');

    $defaultDocuments = [
        [
            'id' => 'doc-1',
            'category' => 'surat-jalan',
            'type_badge' => 'SURAT JALAN RESMI',
            'title' => 'Surat Jalan Pabrik & Delivery Order (DO)',
            'desc' => 'Diterbitkan rangkap untuk setiap armada truk pengiriman dari pabrik Plered. Memuat rincian motif roster, kuantitas keping, nomor polisi truk, nama supir armada, serta stempel basah QC pabrik.',
            'status' => 'SIAP TERBIT BERSAMA ARMADA',
            'sample_no' => 'DO/IR-PLR/' . date('Y/m') . '/0842',
            'date_str' => date('d F Y'),
            'usage' => 'Bukti Bongkar Proyek & Security Clearance',
            'features' => [
                'Nomor seri unik & barcode identifikasi muatan',
                'Daftar rincian koli & motif roster terperinci',
                'Tanda tangan 3 pihak (Pengirim, Supir, Penerima Proyek)',
                'Stempel basah Quality Control (QC) bebas pecah'
            ],
            'table_rows' => [
                ['name' => 'Roster Beton Minimalis Motif Nako 20x20x10', 'qty' => '1.500 Pcs', 'unit' => 'Keping', 'note' => 'QC Pass - Palet A'],
                ['name' => 'Roster Beton Minimalis Motif Kotak Dadu 20x20x10', 'qty' => '800 Pcs', 'unit' => 'Keping', 'note' => 'QC Pass - Palet B'],
                ['name' => 'Bata Tempel Expose Terakota Natural Halus', 'qty' => '3.000 Pcs', 'unit' => 'Keping', 'note' => 'QC Pass - Box'],
            ],
            'notes' => 'Catatan: Seluruh material telah melalui uji sortir fisik di pabrik Plered. Segala kerusakan selama pengangkutan armada pabrik diganti unit baru di lokasi.',
            'footer_sign_1' => 'Petugas Gudang / QC',
            'footer_sign_2' => 'Supir Ekspedisi Armada',
            'footer_sign_3' => 'Penerima / Project Manager',
        ],
        [
            'id' => 'doc-2',
            'category' => 'invoice',
            'type_badge' => 'INVOICE RESMI',
            'title' => 'Invoice Penjualan & Tagihan Resmi Pabrik',
            'desc' => 'Dokumen penagihan resmi pabrik dengan nomor rekening bank resmi, rincian termin pembayaran fleksibel (DP, Termin Progres, Pelunasan), dan rincian harga pabrik langsung tanpa perantara.',
            'status' => 'TERBIT OTOMATIS & FLEKSIBEL',
            'sample_no' => 'INV/IR-PLR/' . date('Ymd') . '/912',
            'date_str' => date('d F Y'),
            'usage' => 'Pertanggungjawaban Keuangan & Pembukuan Proyek',
            'features' => [
                'Rincian PO, harga satuan pabrik, dan diskon volume',
                'Mendukung termin pembayaran bertahap B2B (DP & Sisa)',
                'Nomor rekening bank resmi terverifikasi atas nama owner',
                'Rincian ongkir armada truk & estimasi jadwal sampai'
            ],
            'table_rows' => [
                ['name' => 'Roster Beton Minimalis Abu Presisi 20x20x10', 'qty' => '2.500 Pcs', 'unit' => 'Rp 12.500', 'note' => 'Rp 31.250.000'],
                ['name' => 'Ongkos Kirim Armada Truk CDD (Pabrik Plered - Lokasi Proyek)', 'qty' => '1 Ritase', 'unit' => 'Rp 1.500.000', 'note' => 'Rp 1.500.000'],
                ['name' => 'Diskon Potongan Pembelian Volume Proyek (>2000 pcs)', 'qty' => '1 Lot', 'unit' => '-Rp 1.250.000', 'note' => '-Rp 1.250.000'],
            ],
            'notes' => 'Pembayaran sah ditransfer ke Rekening Resmi IndoRoster. Dokumen ini menjadi bukti transaksi sah pengadaan material langsung dari pabrik.',
            'footer_sign_1' => 'Bagian Administrasi',
            'footer_sign_2' => 'Penanggung Jawab Pabrik',
            'footer_sign_3' => 'Pembeli / Pelanggan',
        ],
        [
            'id' => 'doc-3',
            'category' => 'receipt',
            'type_badge' => 'KWITANSI RESMI',
            'title' => 'Kwitansi Pembayaran Sah Bermaterai',
            'desc' => 'Tanda terima uang resmi yang diterbitkan setelah konfirmasi dana masuk, lengkap dengan rincian peruntukan pembayaran material dan stempel LUNAS bertanda tangan.',
            'status' => 'BUKTI PEMBAYARAN SAH',
            'sample_no' => 'KWT/IR-PLR/' . date('Y') . '/0541',
            'date_str' => date('d F Y'),
            'usage' => 'Bukti Lunas SPJ & Laporan Pertanggungjawaban',
            'features' => [
                'Nomor registrasi kwitansi resmi penerimaan dana',
                'Tertera jumlah nominal angka dan terbilang lengkap',
                'Mendukung penempelan materai fisik / e-meterai',
                'Stempel basah LUNAS dan tanda tangan penanggung jawab'
            ],
            'table_rows' => [
                ['name' => 'Pelunasan Pengadaan Roster Beton Proyek Tahap 1', 'qty' => '1 Paket', 'unit' => 'Lunas', 'note' => 'Rp 31.500.000'],
                ['name' => 'Biaya Bongkar Muat Tenaga Lapangan', 'qty' => '1 Armada', 'unit' => 'Termasuk', 'note' => 'Rp 0 (Free)'],
                ['name' => 'Status Pembayaran', 'qty' => '100%', 'unit' => 'Transfer Bank', 'note' => 'LUNAS SAH'],
            ],
            'notes' => 'Telah diterima dari pihak pemesan sejumlah uang untuk pembayaran pengadaan roster beton sesuai Invoice terlampir. Kwitansi ini sah dan berkekuatan hukum.',
            'footer_sign_1' => 'Kasir / Admin Keuangan',
            'footer_sign_2' => 'Penanggung Jawab Keuangan',
            'footer_sign_3' => 'Materai 10.000 / Stempel Lunas',
        ],
        [
            'id' => 'doc-4',
            'category' => 'bast',
            'type_badge' => 'SERAH TERIMA FISIK',
            'title' => 'Berita Acara Serah Terima (BAST)',
            'desc' => 'Dokumen serah terima fisik material di lokasi pekerjaan setelah proses inspeksi bersama antara pihak supir/pengirim pabrik dan tim pengawas kontraktor di lapangan.',
            'status' => 'DITANDATANGANI DI LOKASI',
            'sample_no' => 'BAST/ROSTER/' . date('Y') . '/IV/029',
            'date_str' => date('d F Y'),
            'usage' => 'Opname Lapangan & Dokumen Pencairan Termin',
            'features' => [
                'Berita acara verifikasi jumlah koli dan keutuhan roster',
                'Pernyataan garansi ganti baru keping rusak langsung di tempat',
                'Dokumentasi serah terima material di lokasi pekerjaan',
                'Menjadi prasyarat opname laporan pengawas lapangan'
            ],
            'table_rows' => [
                ['name' => 'Pemeriksaan Visual Kerataan Siku & Dimensi Roster', 'qty' => '100%', 'unit' => 'Sesuai', 'note' => 'Presisi ±1mm'],
                ['name' => 'Pemeriksaan Jumlah Koli Material Tiba di Lokasi', 'qty' => '100%', 'unit' => 'Lengkap', 'note' => 'Sesuai PO No. 841'],
                ['name' => 'Kondisi Fisik Saat Pembongkaran (Zero Damage)', 'qty' => '100%', 'unit' => 'Utuh', 'note' => 'Bebas Retak'],
            ],
            'notes' => 'Pihak Pertama telah menyerahkan barang dalam kondisi baik dan lengkap kepada Pihak Kedua di lokasi proyek, dan Pihak Kedua telah menerima material tersebut dengan baik.',
            'footer_sign_1' => 'Pihak Pertama (Penyedia)',
            'footer_sign_2' => 'Pihak Kedua (Kontraktor)',
            'footer_sign_3' => 'Pengawas Lapangan',
        ],
        [
            'id' => 'doc-5',
            'category' => 'tender',
            'type_badge' => 'PENAWARAN & SPH',
            'title' => 'Surat Penawaran Harga Resmi (SPH / Quotation)',
            'desc' => 'Surat penawaran harga resmi dari produsen dengan rincian spesifikasi teknis, harga grosir volume besar, diskon khusus kontraktor, dan masa berlaku harga terkunci.',
            'status' => 'TERBIT DALAM 1X24 JAM',
            'sample_no' => 'SPH/IR-PLR/' . date('Y') . '/0118',
            'date_str' => date('d F Y'),
            'usage' => 'Pengajuan Anggaran RAB Kontraktor & Owner',
            'features' => [
                'Kop surat resmi pabrik produsen Plered Purwakarta',
                'Pernyataan jaminan kapasitas produksi harian/mingguan',
                'Masa berlaku penawaran harga terkunci (Bebas Kenaikan)',
                'Rincian syarat teknis pemasangan dan garansi produk'
            ],
            'table_rows' => [
                ['name' => 'Kapasitas Suplai Mingguan Roster Cetak Padat', 'qty' => '15.000 Pcs', 'unit' => 'Per Minggu', 'note' => 'Pabrik Plered'],
                ['name' => 'Kesiapan Armada Logistik Pengiriman Proyek', 'qty' => 'Armada Truk', 'unit' => 'Siap Jalan', 'note' => 'Jadwal Rutin'],
                ['name' => 'Masa Berlaku Harga Terkunci Selama Proyek', 'qty' => '60 Hari', 'unit' => 'Garansi Harga', 'note' => 'Bebas Kenaikan'],
            ],
            'notes' => 'Surat penawaran ini diterbitkan atas permintaan calon pembeli/kontraktor sebagai acuan resmi pengadaan material roster beton cetak presisi.',
            'footer_sign_1' => 'Admin Sales B2B',
            'footer_sign_2' => 'Penanggung Jawab Pabrik',
            'footer_sign_3' => 'Stempel Resmi IndoRoster',
        ],
        [
            'id' => 'doc-6',
            'category' => 'uji-lab',
            'type_badge' => 'MUTU & SERTIFIKASI',
            'title' => 'Sertifikat Uji Kuat Tekan Laboratorium SNI',
            'desc' => 'Hasil uji laboratorium independen mengenai ketahanan tekan beton (Compressive Strength Test - kg/cm²), daya serap air, dan berat jenis untuk membuktikan kelayakan struktural dinding arsitektural.',
            'status' => 'STANDAR SNI BAHAN BANGUNAN',
            'sample_no' => 'LAB-TEST/SNI-03-0349/' . date('Y') . '/B-44',
            'date_str' => date('d F Y'),
            'usage' => 'Approval Konsultan Pengawas / Arsitek Proyek',
            'features' => [
                'Nilai kuat tekan beton teruji standar struktural (K-225/K-250)',
                'Pengujian porositas & daya serap air rendah',
                'Hasil uji lab beton independen terstandar teknis',
                'Rekomendasi teknis beban dinding roster tinggi'
            ],
            'table_rows' => [
                ['name' => 'Uji Kuat Tekan Roster Beton (Compressive Strength)', 'qty' => 'K-225 / K-250', 'unit' => 'Kg/cm²', 'note' => 'LULUS UJI'],
                ['name' => 'Uji Porositas & Penyerapan Air (Water Absorption)', 'qty' => '< 8.5%', 'unit' => 'Persen', 'note' => 'STANDAR SNI'],
                ['name' => 'Uji Ketahanan Cuaca Luar & Kelembapan Tropis', 'qty' => '50 Siklus', 'unit' => 'Weathering', 'note' => 'TIDAK RETAK'],
            ],
            'notes' => 'Pengujian dilakukan mengacu pada standar SNI 03-0349 (Bata Beton untuk Pasangan Dinding). Benda uji memenuhi syarat mutu untuk dinding struktural & pagar luar ruangan.',
            'footer_sign_1' => 'Kepala Laboratorium Penguji',
            'footer_sign_2' => 'Teknisi Pengujian Bahan',
            'footer_sign_3' => 'Stempel Akreditasi Lab',
        ]
    ];

    $documents = !empty($data['documents']) ? $data['documents'] : $defaultDocuments;
@endphp

<section 
    class="py-20 sm:py-28 {{ $theme->bgClasses }} relative overflow-hidden font-sans select-none" 
    x-data="{ 
        activeFilter: 'all', 
        activeDoc: null,
        openModal(doc) {
            this.activeDoc = doc;
            document.body.style.overflow = 'hidden';
        },
        closeModal() {
            this.activeDoc = null;
            document.body.style.overflow = '';
        }
    }"
    @keydown.escape.window="closeModal()"
>
    @include('components.blocks._bg-theme', ['theme' => $theme])

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        {{-- Top Header Section --}}
        <div class="text-center max-w-3xl mx-auto mb-14 sm:mb-18">
            @if(!empty($badge))
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full {{ $theme->badgeClass }} text-xs font-black uppercase tracking-widest mb-5 shadow-soft-xs">
                <svg class="w-4 h-4 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span>{{ $badge }}</span>
            </div>
            @endif

            <h2 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black {{ $theme->headingColor }} tracking-tight leading-tight mb-5">
                {{ $title }}
            </h2>

            @if(!empty($subtitle))
            <p class="text-base sm:text-lg {{ $theme->subColor }} leading-relaxed">
                {{ $subtitle }}
            </p>
            @endif

            {{-- Trust Badges Strip --}}
            <div class="mt-8 flex flex-wrap items-center justify-center gap-3 sm:gap-4">
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 text-xs font-bold shadow-xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    {{ $quickBadge1 }}
                </span>
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20 text-xs font-bold shadow-xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    {{ $quickBadge2 }}
                </span>
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20 text-xs font-bold shadow-xs">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    {{ $quickBadge3 }}
                </span>
            </div>
        </div>

        {{-- Interactive Filter Categories --}}
        <div class="flex items-center justify-center flex-wrap gap-2 mb-12">
            <button 
                @click="activeFilter = 'all'" 
                :class="activeFilter === 'all' ? 'bg-terra-500 text-white shadow-md shadow-terra-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
                📑 Semua Dokumen ({{ count($documents) }})
            </button>
            <button 
                @click="activeFilter = 'surat-jalan'" 
                :class="activeFilter === 'surat-jalan' ? 'bg-amber-600 text-white shadow-md shadow-amber-600/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
                🚚 Surat Jalan / DO
            </button>
            <button 
                @click="activeFilter = 'invoice'" 
                :class="activeFilter === 'invoice' ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
                🧾 Invoice Resmi
            </button>
            <button 
                @click="activeFilter = 'receipt'" 
                :class="activeFilter === 'receipt' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
                💰 Kwitansi Lunas
            </button>
            <button 
                @click="activeFilter = 'tender'" 
                :class="activeFilter === 'tender' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
                📝 SPH Penawaran
            </button>
            <button 
                @click="activeFilter = 'uji-lab'" 
                :class="activeFilter === 'uji-lab' ? 'bg-rose-600 text-white shadow-md shadow-rose-600/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700'"
                class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all cursor-pointer">
                🔬 Uji Kuat Tekan SNI
            </button>
        </div>

        {{-- Document Sheet Grid (Realistic Document Presentation) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($documents as $idx => $doc)
            @php
                $category = $doc['category'] ?? 'general';
                $sampleUpload = $doc['sample_image_upload'] ?? null;
                $sampleUrl = $doc['sample_image_url'] ?? null;
                $hasCustomImage = !empty($sampleUpload) || !empty($sampleUrl);
                $finalImgSrc = !empty($sampleUpload) ? asset('storage/'.$sampleUpload) : $sampleUrl;
            @endphp
            <div 
                x-show="activeFilter === 'all' || activeFilter === '{{ $category }}'"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="flex flex-col justify-between group cursor-pointer"
                @click="openModal({{ json_encode($doc) }})">
                
                {{-- REALISTIC A4 PHYSICAL PAPER SHEET MOCKUP --}}
                <div class="relative bg-white text-slate-800 rounded-2xl shadow-xl hover:shadow-2xl border border-slate-200/80 p-5 sm:p-6 transition-all duration-300 group-hover:-translate-y-2 overflow-hidden flex flex-col justify-between min-h-[470px]">
                    
                    {{-- Folded Corner Paper Effect --}}
                    <div class="absolute top-0 right-0 w-8 h-8 bg-gradient-to-br from-slate-200 to-slate-300 border-l border-b border-slate-300/80 rounded-bl-xl shadow-xs pointer-events-none"></div>

                    {{-- Watermark Overlay --}}
                    <div class="absolute inset-0 flex items-center justify-center opacity-[0.035] pointer-events-none select-none font-black text-3xl uppercase -rotate-25 tracking-widest text-slate-900">
                        CONTOH DOKUMEN RESMI
                    </div>

                    {{-- Hover Zoom Pill --}}
                    <div class="absolute inset-0 bg-slate-950/40 backdrop-blur-[2px] opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center z-20">
                        <span class="px-4 py-2 rounded-xl bg-terra-500 text-white font-bold text-xs shadow-luxury flex items-center gap-1.5 transform scale-95 group-hover:scale-100 transition-transform">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                            Klik untuk Perbesar Lembar
                        </span>
                    </div>

                    <div>
                        {{-- Physical Paper Header (Kop Surat Realistis Pabrik) --}}
                        <div class="border-b-2 border-slate-900 pb-3 mb-3.5 flex items-start justify-between gap-2">
                            <div>
                                <div class="text-[13px] font-black tracking-tight text-slate-900 leading-none">
                                    {{ $companyName }}
                                </div>
                                <div class="text-[9px] font-semibold text-slate-500 mt-1 uppercase tracking-wide">
                                    Produsen & Pabrik Roster Beton Plered Purwakarta
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded bg-slate-900 text-white font-mono text-[9px] font-black uppercase tracking-wider shrink-0">
                                {{ $doc['type_badge'] }}
                            </span>
                        </div>

                        {{-- Metadata Document Strip --}}
                        <div class="bg-slate-50 rounded-lg p-2.5 mb-3 border border-slate-100 text-[10px] space-y-1 font-mono text-slate-600">
                            <div class="flex justify-between">
                                <span>No. Dokumen:</span>
                                <strong class="text-slate-900">{{ $doc['sample_no'] }}</strong>
                            </div>
                            <div class="flex justify-between">
                                <span>Tanggal Terbit:</span>
                                <span>{{ $doc['date_str'] ?? date('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Status Keabsahan:</span>
                                <span class="text-emerald-700 font-bold">✓ {{ $doc['status'] }}</span>
                            </div>
                        </div>

                        {{-- Real Data Sample Table --}}
                        <div class="border border-slate-200 rounded-lg overflow-hidden mb-3">
                            <table class="w-full text-left text-[10px]">
                                <thead class="bg-slate-100 text-slate-700 font-bold border-b border-slate-200">
                                    <tr>
                                        <th class="p-1.5">Deskripsi Item / Produk</th>
                                        <th class="p-1.5 text-right">Vol</th>
                                        <th class="p-1.5 text-right">Ket</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-slate-600 font-mono">
                                    @if(!empty($doc['table_rows']) && is_array($doc['table_rows']))
                                        @foreach(array_slice($doc['table_rows'], 0, 3) as $row)
                                        <tr>
                                            <td class="p-1.5 truncate max-w-[140px]">{{ $row['name'] }}</td>
                                            <td class="p-1.5 text-right font-bold text-slate-900">{{ $row['qty'] }}</td>
                                            <td class="p-1.5 text-right text-[9px] text-emerald-600">{{ $row['note'] }}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        {{-- Custom Photo if uploaded --}}
                        @if($hasCustomImage)
                        <div class="mb-3 rounded-lg overflow-hidden border border-slate-200">
                            <img src="{{ $finalImgSrc }}" alt="{{ $doc['title'] }}" class="w-full h-24 object-cover object-top">
                        </div>
                        @endif

                        <div class="text-[9px] text-slate-500 italic mb-2 line-clamp-2">
                            {{ $doc['notes'] ?? 'Dokumen sah pabrik, dilengkapi tanda tangan dan stempel Quality Control (QC).' }}
                        </div>
                    </div>

                    {{-- Bottom Stempel & Signatures Line (With Real Stamp & Signature Asset) --}}
                    <div class="pt-3 border-t border-slate-200 flex items-end justify-between text-[9px] text-slate-600 mt-2 relative">
                        <div>
                            <div class="text-[8px] uppercase font-bold text-slate-400">Verifikasi QC:</div>
                            <div class="font-bold text-slate-800">{{ $doc['footer_sign_1'] ?? 'Pemeriksa' }}</div>
                        </div>

                        {{-- Realistic Official Stamp & Signature Overlay --}}
                        <div class="relative flex items-center justify-center">
                            @if(file_exists(public_path('storage/document-assets/stamps/01M0QFEEYH7K6QVZ1YHDMW41FV.png')))
                                <img src="{{ $stampImg }}" alt="Stempel Pabrik" class="w-12 h-12 object-contain opacity-85 rotate-[-8deg] pointer-events-none">
                            @else
                                <div class="w-11 h-11 rounded-full border-2 border-dashed border-terra-600 text-terra-600 flex flex-col items-center justify-center font-black text-[7px] leading-tight rotate-[-8deg] shadow-xs">
                                    <span>QC PASS</span>
                                    <span>PLERED</span>
                                </div>
                            @endif
                        </div>

                        <div class="text-right">
                            <div class="text-[8px] uppercase font-bold text-slate-400">Otorisasi:</div>
                            <div class="font-bold text-slate-800">{{ $doc['footer_sign_2'] ?? 'Penanggung Jawab' }}</div>
                        </div>
                    </div>

                </div>

                {{-- Summary Title & Actions Below Paper --}}
                <div class="mt-4 px-1">
                    <h3 class="text-base font-bold {{ $theme->cardTitle }} leading-tight mb-1.5 group-hover:text-terra-500 transition-colors">
                        {{ $doc['title'] }}
                    </h3>
                    <p class="text-xs {{ $theme->cardDesc }} line-clamp-2 leading-relaxed mb-3">
                        {{ $doc['desc'] }}
                    </p>
                    <div class="flex items-center justify-between text-xs font-bold text-terra-600 dark:text-terra-400">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Spill Lembar Lengkap
                        </span>
                        <span class="text-slate-400 font-normal text-[11px]">{{ $doc['usage'] }}</span>
                    </div>
                </div>

            </div>
            @endforeach
        </div>

        {{-- LIGHTBOX FULL-SCREEN MODAL FOR VIEWING/ZOOMING DOCUMENT SHEET --}}
        <template x-if="activeDoc">
            <div 
                class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-950/80 backdrop-blur-md overflow-y-auto"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click.self="closeModal()"
            >
                <div 
                    class="relative w-full max-w-3xl bg-white text-slate-900 rounded-3xl shadow-2xl overflow-hidden border border-slate-200 my-auto flex flex-col max-h-[90vh]"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                    x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                >
                    {{-- Modal Header Bar --}}
                    <div class="px-6 py-4 bg-slate-900 text-white flex items-center justify-between border-b border-slate-800 shrink-0">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></span>
                            <div>
                                <div class="text-xs font-mono text-slate-400 uppercase" x-text="activeDoc.type_badge"></div>
                                <h3 class="text-sm sm:text-base font-bold text-white leading-tight" x-text="activeDoc.title"></h3>
                            </div>
                        </div>
                        <button 
                            @click="closeModal()" 
                            class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition cursor-pointer">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Modal Scrollable Paper View --}}
                    <div class="p-6 sm:p-10 overflow-y-auto flex-1 bg-slate-50 space-y-6 font-sans">
                        
                        {{-- Rendered Official Letterhead Sheet --}}
                        <div class="bg-white p-6 sm:p-8 rounded-2xl shadow-md border border-slate-200 text-slate-900 relative overflow-hidden">
                            
                            {{-- Watermark --}}
                            <div class="absolute inset-0 flex items-center justify-center opacity-[0.04] pointer-events-none select-none font-black text-4xl sm:text-5xl uppercase -rotate-25 tracking-widest text-slate-900">
                                CONTOH RESMI INDOROSTER
                            </div>

                            {{-- Official Header (Kop Surat Pabrik Plered Purwakarta) --}}
                            <div class="border-b-2 border-slate-900 pb-4 mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <div class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">{{ $companyName }}</div>
                                    <div class="text-xs font-bold text-terra-600 uppercase">Produsen & Pabrik Roster Beton Arsitektural, Loster & Bata Expose</div>
                                    <div class="text-[11px] text-slate-500 mt-1">{{ $factoryAddress }}</div>
                                    <div class="text-[11px] text-slate-500">WhatsApp: {{ $rawWa }}</div>
                                </div>
                                <div class="sm:text-right font-mono text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-200 shrink-0">
                                    <div>No: <strong class="text-slate-900" x-text="activeDoc.sample_no"></strong></div>
                                    <div>Tgl: <span x-text="activeDoc.date_str"></span></div>
                                    <div class="text-emerald-700 font-bold mt-0.5">STATUS: RESMI PABRIK</div>
                                </div>
                            </div>

                            {{-- Title of Document --}}
                            <div class="text-center my-6">
                                <h4 class="text-base sm:text-lg font-black text-slate-900 uppercase tracking-widest border-b border-dashed border-slate-300 pb-2 inline-block" x-text="activeDoc.title"></h4>
                            </div>

                            {{-- Table of Document Items --}}
                            <div class="border border-slate-200 rounded-xl overflow-hidden mb-6">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-slate-100 text-slate-800 font-bold border-b border-slate-200">
                                        <tr>
                                            <th class="p-3">Rincian Item / Produk Roster</th>
                                            <th class="p-3 text-right">Kuantitas</th>
                                            <th class="p-3 text-right">Status / Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 font-mono text-slate-700">
                                        <template x-for="(row, rIdx) in activeDoc.table_rows" :key="rIdx">
                                            <tr>
                                                <td class="p-3 font-sans font-medium" x-text="row.name"></td>
                                                <td class="p-3 text-right font-bold text-slate-900" x-text="row.qty"></td>
                                                <td class="p-3 text-right text-emerald-600 font-bold" x-text="row.note"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                            {{-- Explanatory Notes --}}
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs text-slate-600 leading-relaxed mb-8" x-text="activeDoc.notes"></div>

                            {{-- Three Column Signature Section with Real Stamp --}}
                            <div class="grid grid-cols-3 gap-4 text-center text-xs pt-4 border-t border-slate-200">
                                <div>
                                    <div class="text-[10px] text-slate-400 uppercase font-bold mb-4" x-text="activeDoc.footer_sign_1"></div>
                                    <div class="h-10 flex items-center justify-center">
                                        <span class="text-xs text-slate-400 italic">( Tanda Tangan )</span>
                                    </div>
                                    <div class="font-bold text-slate-900 border-t border-slate-300 pt-1 mx-2">( Petugas Pabrik )</div>
                                </div>
                                <div class="flex flex-col items-center justify-center">
                                    {{-- Real Official Stamp Asset --}}
                                    <div class="relative w-20 h-20 flex items-center justify-center">
                                        <img src="{{ $stampImg }}" alt="Stempel Pabrik IndoRoster" class="w-18 h-18 object-contain opacity-90 rotate-[-8deg] pointer-events-none drop-shadow-xs">
                                    </div>
                                    <div class="text-[10px] text-slate-500 uppercase font-bold mt-1" x-text="activeDoc.footer_sign_2"></div>
                                </div>
                                <div>
                                    <div class="text-[10px] text-slate-400 uppercase font-bold mb-4" x-text="activeDoc.footer_sign_3"></div>
                                    <div class="h-10 flex items-center justify-center">
                                        <span class="text-xs text-slate-400 italic">( Tanda Tangan )</span>
                                    </div>
                                    <div class="font-bold text-slate-900 border-t border-slate-300 pt-1 mx-2">( Pihak Pemesan )</div>
                                </div>
                            </div>

                        </div>

                        {{-- Features & Guidance --}}
                        <div class="bg-white p-5 rounded-2xl border border-slate-200">
                            <h5 class="text-xs font-black uppercase text-terra-600 tracking-wider mb-2">Keabsahan & Fungsi Dokumen Ini:</h5>
                            <p class="text-xs text-slate-600 leading-relaxed mb-3" x-text="activeDoc.desc"></p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-700">
                                <template x-for="(f, fIdx) in activeDoc.features" :key="fIdx">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span x-text="f"></span>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>

                    {{-- Modal Action Footer --}}
                    <div class="p-4 sm:p-5 bg-white border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 shrink-0">
                        <div class="text-xs text-slate-500 text-center sm:text-left">
                            Butuh format dokumen / surat penawaran harga resmi atas pesanan Anda?
                        </div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <button 
                                @click="closeModal()" 
                                class="w-1/2 sm:w-auto px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-100 transition cursor-pointer">
                                Tutup
                            </button>
                            <a 
                                :href="'https://wa.me/{{ $waNumClean }}?text=' + encodeURIComponent('Halo Tim Sales IndoRoster, saya ingin meminta format/contoh resmi dokumen: ' + activeDoc.title + ' untuk keperluan pesanan proyek kami.')"
                                target="_blank"
                                class="w-1/2 sm:w-auto px-5 py-2.5 rounded-xl bg-terra-500 hover:bg-terra-600 text-white text-xs font-bold transition flex items-center justify-center gap-2 shadow-md cursor-pointer">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                <span>Minta Format Ini</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- Bottom Comprehensive CTA Box --}}
        <div class="mt-14 sm:mt-18 rounded-3xl p-8 sm:p-10 bg-gradient-to-r from-slate-900 via-slate-850 to-slate-900 border border-slate-800 text-white shadow-luxury relative overflow-hidden flex flex-col lg:flex-row items-center justify-between gap-8">
            
            {{-- Background decorative glow --}}
            <div class="absolute -top-24 -left-24 w-72 h-72 bg-terra-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-amber-500/15 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 max-w-2xl text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-terra-500/20 text-terra-400 border border-terra-500/30 text-xs font-black uppercase tracking-wider mb-3">
                    <span>🏢 PENGADAAN KONTRAKTOR & PROYEK B2B</span>
                </div>
                <h3 class="text-xl sm:text-2xl lg:text-3xl font-black text-white tracking-tight mb-2">
                    {{ $ctaTitle }}
                </h3>
                <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                    Kirimkan format RAB atau kebutuhan kuantitas roster proyek Anda. Kami siap menerbitkan Surat Penawaran Harga (SPH), konfirmasi jadwal armada, dan rincian dokumen pengadaan dalam waktu 1x24 jam kerja.
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-3 text-xs text-slate-400">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Pabrik Langsung Tangan Pertama Plered
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Stempel Basah QC & Kwitansi Sah
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Garansi Pecah Ganti Baru di Lokasi
                    </span>
                </div>
            </div>

            <div class="relative z-10 shrink-0 w-full sm:w-auto">
                <a 
                    href="{{ $ctaBtnLink }}" 
                    target="_blank" 
                    rel="noopener"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-8 py-4 bg-gradient-to-r from-terra-500 to-amber-600 hover:from-terra-600 hover:to-amber-700 text-white font-black text-sm uppercase tracking-wider rounded-2xl shadow-luxury hover:scale-105 transition-all duration-200 cursor-pointer">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    <span>{{ $ctaBtnText }}</span>
                </a>
            </div>
        </div>

    </div>
</section>
