<div class="min-h-screen bg-[#F8F9FA] py-24 md:py-36 overflow-hidden">
    <style>
        .text-accent { color: #f75c20; }
        .bg-accent { background-color: #f75c20; }
        .border-accent { border-color: #f75c20; }

        .about-card {
            background: #ffffff;
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            border: 1px solid rgba(0,0,0,0.05);
        }
        .about-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 40px 80px -20px rgba(0,0,0,0.1);
        }
        .btn-industrial {
            background-color: #f75c20;
            color: #ffffff;
            font-weight: 800;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        .btn-industrial:hover {
            background-color: #000000;
            color: #f75c20;
            transform: scale(1.02);
        }
        .outline-text {
            -webkit-text-stroke: 1px rgba(0,0,0,0.05);
            color: transparent;
        }
        .label-cap {
            font-family: var(--font-display);
            font-size: 12px;
            font-weight: 900;
            letter-spacing: 0.4em;
            text-transform: uppercase;
            color: #f75c20;
        }
    </style>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 font-sans relative">
        
        <!-- Large Background Text -->
        <div class="absolute top-0 right-0 pointer-events-none select-none opacity-[0.03] z-0">
            <h1 class="font-display text-[15rem] font-black outline-text whitespace-nowrap rotate-90 translate-x-1/2 mt-40">
                HISTORY
            </h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-24 relative z-10">
            
            <!-- Left: Main Content -->
            <div class="lg:col-span-7">
                <div class="mb-20">
                    <div class="flex items-center gap-4 mb-8">
                        <span class="w-12 h-[2px] bg-accent"></span>
                        <span class="label-cap">Tentang IndoRoster</span>
                    </div>
                    <h1 class="font-display text-fluid-h1 font-black text-black mb-8 tracking-tighter leading-none uppercase">
                        Solusi Estetika & Ventilasi Modern
                    </h1>
                    
                    <div class="space-y-8 text-slate-800 leading-relaxed text-lg font-normal">
                        <p>
                            <strong class="text-black font-bold">IndoRoster</strong> adalah produsen spesialis <span class="text-black font-bold italic">roster minimalis</span> berkualitas tinggi yang berfokus pada pemenuhan kebutuhan arsitektur modern di Indonesia. Kami memproduksi roster beton dengan standar daya tahan tinggi, presisi dimensi yang akurat, serta beragam desain unik yang tidak hanya berfungsi sebagai ventilasi dan pencahayaan alami, tetapi juga memberikan nilai estetika premium pada setiap bangunan.
                        </p>
                        <p>
                            Berpusat di <strong class="text-black font-bold">Plered, Purwakarta</strong>, yang merupakan sentra produksi kerajinan beton terkemuka, kami mengelola kapasitas produksi besar untuk melayani berbagai skala proyek di seluruh Indonesia. Lokasi produksi kami yang strategis menjamin kelancaran logistik dan efisiensi biaya pengiriman.
                        </p>
                        <p>
                            Di IndoRoster, kualitas adalah prioritas utama kami. Kami menyeleksi bahan baku terbaik secara teliti untuk memastikan setiap produk memenuhi standar performa tinggi, keawetan jangka panjang, dan karakteristik ramah lingkungan.
                        </p>
                        <p>
                            Selain roster beton, kami juga memproduksi <strong class="text-black font-bold">bata tempel</strong> (brick veneers) berkualitas. Kami melayani kebutuhan material untuk pemilik rumah, arsitek, hingga kontraktor dengan pilihan motif yang beragam dan layanan kustomisasi desain.
                        </p>
                    </div>
                </div>

                <!-- Vision Section -->
                <div class="bg-white p-12 border-l-8 border-accent shadow-sm mb-16">
                    <h2 class="font-display text-fluid-h2 font-black text-black mb-6 tracking-tighter uppercase">Visi Kami</h2>
                    <p class="text-xl text-slate-800 leading-relaxed font-normal mb-10">
                        Visi kami bukan sekadar menghadirkan produk berkualitas tinggi, namun juga membangun kemitraan jangka panjang yang berlandaskan pada kepercayaan, profesionalisme, dan pertumbuhan bersama.
                    </p>
                    <a href="{{ route('production') }}" class="inline-flex items-center justify-center w-full py-6 px-10 btn-industrial rounded-full text-lg">
                        Mengenal Proses Produksi Kami
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Right: Feature Cards -->
            <div class="lg:col-span-5 space-y-12">
                
                <!-- Card 0: Production Place -->
                <div class="about-card rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                    <div class="h-64 bg-slate-200 overflow-hidden relative group">
                        <img src="{{ asset('assets/indoroster-production-place.png') }}" alt="Tempat Produksi IndoRoster" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                    <div class="p-8 md:p-10">
                        <h3 class="font-display text-2xl font-bold text-black mb-4 leading-tight">Pabrik & Sentra Produksi IndoRoster</h3>
                        <p class="text-slate-700 text-sm leading-relaxed mb-6">
                            Pabrik kami berpusat di Plered, Purwakarta. Kami memiliki kapasitas produksi besar dan proses kendali mutu yang ketat untuk menghasilkan roster beton berkualitas tinggi secara konsisten.
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <span class="text-[10px] font-black uppercase tracking-widest bg-orange-100 text-[#f75c20] px-3 py-1 rounded-full">#PabrikRoster</span>
                            <span class="text-[10px] font-black uppercase tracking-widest bg-orange-100 text-[#f75c20] px-3 py-1 rounded-full">#Purwakarta</span>
                            <span class="text-[10px] font-black uppercase tracking-widest bg-orange-100 text-[#f75c20] px-3 py-1 rounded-full">#ProduksiLokal</span>
                        </div>
                    </div>
                </div>

                <!-- Card 1: Consistency -->
                <div class="about-card rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                    <div class="h-64 bg-slate-200 overflow-hidden relative group">
                        <img src="https://res.cloudinary.com/indoroster/image/upload/v1765262980/2_zurmam.jpg" alt="Roster Consistency" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                    <div class="p-8 md:p-10">
                        <h3 class="font-display text-2xl font-bold text-black mb-4 leading-tight">Ukuran Konsisten untuk Hasil Pemasangan yang Lebih Rapi</h3>
                        <p class="text-slate-700 text-sm leading-relaxed mb-6">
                            Roster ini dibuat dengan presisi tinggi dan konsistensi ukuran yang stabil, menghasilkan permukaan yang rata dan rapi, sangat cocok untuk fasad maupun interior.
                        </p>
                        <div class="flex flex-wrap gap-2 mb-8">
                            <span class="text-[10px] font-black uppercase tracking-widest bg-orange-100 text-[#f75c20] px-3 py-1 rounded-full">#Roster</span>
                            <span class="text-[10px] font-black uppercase tracking-widest bg-orange-100 text-[#f75c20] px-3 py-1 rounded-full">#BreezeBlock</span>
                            <span class="text-[10px] font-black uppercase tracking-widest bg-orange-100 text-[#f75c20] px-3 py-1 rounded-full">#Ventilasi</span>
                        </div>
                        <a href="{{ route('catalog') }}" class="inline-block w-full text-center py-4 bg-accent text-black font-black text-xs uppercase tracking-widest hover:bg-black hover:text-accent transition-all duration-300">
                            Lihat Katalog Produk Kami
                        </a>
                    </div>
                </div>

                <!-- Card 2: Modern Fasad -->
                <div class="about-card rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                    <div class="h-64 bg-slate-200 overflow-hidden relative group">
                        <img src="https://res.cloudinary.com/indoroster/image/upload/v1765260853/162067858_988931008308004_8757323712171815873_n_kpbq7h.jpg" alt="Modern Fasad" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    </div>
                    <div class="p-8 md:p-10">
                        <h3 class="font-display text-2xl font-bold text-black mb-4 leading-tight">Pemasangan Roster Motif MMC pada Fasad Rumah Modern</h3>
                        <p class="text-slate-700 text-sm leading-relaxed mb-6">
                            Roster motif MMC memberikan tampilan fasad yang tegas dan modern, dengan pola geometris yang menciptakan ritme visual yang rapi dan artistik.
                        </p>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('gallery') }}" class="text-center py-4 bg-slate-100 text-black font-black text-[10px] uppercase tracking-widest hover:bg-accent transition-all duration-300">
                                Galeri Foto
                            </a>
                            <a href="{{ route('video-inspiration') }}" class="text-center py-4 bg-slate-100 text-black font-black text-[10px] uppercase tracking-widest hover:bg-accent transition-all duration-300">
                                Galeri Video
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
