<div class="min-h-screen bg-[#F8F9FA] py-24 md:py-36">
    <style>

        .contact-card-premium {
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            background: #ffffff;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .contact-card-premium:hover {
            transform: translateY(-12px);
            box-shadow: 0 40px 80px -20px rgba(0,0,0,0.08);
            border-color: #f75c20;
        }
        .text-accent {
            color: #f75c20;
        }
        .bg-accent {
            background-color: #f75c20;
        }
        .btn-whatsapp-premium {
            background-color: #f75c20 !important;
            color: #ffffff !important;
            transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            box-shadow: 0 15px 35px -10px rgba(247, 92, 32, 0.5);
        }
        .btn-whatsapp-premium:hover {
            background-color: #e9410f !important;
            transform: scale(1.02) translateY(-2px);
            box-shadow: 0 20px 45px -10px rgba(247, 92, 32, 0.7);
        }
        .label-industrial {
            font-family: var(--font-display);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.3em;
            color: #64748b;
            text-transform: uppercase;
        }
        .input-industrial {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0px; /* Sharp industrial look */
            transition: all 0.3s ease;
        }
        .input-industrial:focus {
            border-color: #000000;
            box-shadow: none;
            padding-left: 2.5rem;
        }
        .outline-text {
            -webkit-text-stroke: 1px rgba(0,0,0,0.1);
            color: transparent;
        }
    </style>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 font-sans">
        
        <!-- Background Decorative Text -->
        <div class="absolute top-0 left-0 w-full overflow-hidden pointer-events-none select-none opacity-[0.03] z-0">
            <h1 class="font-display text-[20rem] font-black outline-text whitespace-nowrap -ml-24 -mt-24">
                KONTAK
            </h1>
        </div>

        <div class="relative z-10">
            <!-- Header -->
            <div class="mb-32">
                <div class="flex items-center gap-4 mb-6">
                    <span class="w-12 h-[2px] bg-accent"></span>
                    <span class="label-industrial text-accent">Hubungi Kami</span>
                </div>
                <h1 class="font-display text-fluid-h1 font-black text-black leading-[0.9] tracking-tighter mb-10">
                    HUBUNGI <br> <span class="text-accent">KAMI.</span>
                </h1>
                <p class="text-xl text-slate-800 max-w-2xl leading-relaxed font-normal">
                    Solusi roster beton berkualitas tinggi untuk proyek arsitektur Anda. Hubungi kami untuk konsultasi teknis, estimasi harga, dan pengiriman seluruh Indonesia.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-24">
                
                <!-- Left: Contact Details -->
                <div class="lg:col-span-5 space-y-12">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-6">
                        
                        <!-- Phone/WA -->
                        <div class="contact-card-premium p-10 rounded-sm">
                            <div class="label-industrial mb-6">Layanan WhatsApp</div>
                            <div class="text-2xl md:text-3xl font-bold text-black mb-2">+62 813-8970-9847</div>
                            <p class="text-slate-600 text-sm">Respon cepat: Senin - Sabtu (08:00 - 17:00)</p>
                        </div>

                        <!-- Email -->
                        <div class="contact-card-premium p-10 rounded-sm">
                            <div class="label-industrial mb-6">Email Resmi</div>
                            <div class="text-xl md:text-2xl font-bold text-black mb-2 break-all">abdulhamid66266@gmail.com</div>
                            <p class="text-slate-600 text-sm">Untuk penawaran resmi & invoice proyek.</p>
                        </div>

                        <!-- Address -->
                        <div class="contact-card-premium p-10 rounded-sm lg:col-span-1">
                            <div class="label-industrial mb-6">Kantor Pusat</div>
                            <div class="text-xl font-bold text-black leading-snug mb-4">
                                Kp. Cicadas, Desa Cadasmekar,<br>Kec. Tegalwaru, Purwakarta
                            </div>
                            <a href="https://maps.google.com" target="_blank" class="text-xs font-black uppercase tracking-widest text-accent hover:underline flex items-center gap-2">
                                LIHAT DI PETA 
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Industrial Badge -->
                    <div class="bg-black p-12 text-white rounded-sm relative overflow-hidden group">
                        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-accent/20 rounded-full blur-3xl"></div>
                        <h4 class="font-display text-2xl font-black mb-4">KAPASITAS BESAR</h4>
                        <p class="text-slate-600 leading-relaxed text-sm">
                            Pabrik kami mampu memproduksi hingga ribuan roster per hari untuk memenuhi kebutuhan proyek skala nasional.
                        </p>
                    </div>
                </div>

                <!-- Right: Form -->
                <div class="lg:col-span-7">
                    <div class="bg-white p-8 md:p-16 shadow-sm border border-slate-100">
                        <div class="mb-12">
                            <h3 class="font-display text-fluid-h2 font-black text-black mb-4 uppercase tracking-tighter">Permintaan Penawaran</h3>
                            <p class="text-slate-700">Lengkapi formulir untuk mendapatkan katalog lengkap dan harga khusus proyek.</p>
                        </div>

                        <form wire:submit.prevent="sendWhatsApp" class="space-y-8">
                            <div class="space-y-8">
                                <div class="group">
                                    <label class="label-industrial block mb-3 group-focus-within:text-black transition-colors">Nama Lengkap</label>
                                    <input type="text" wire:model="name" placeholder="Masukkan nama lengkap Anda" 
                                        class="input-industrial w-full px-0 py-4 border-t-0 border-x-0 border-b-2 border-slate-200 focus:border-black outline-none font-bold text-xl placeholder:font-normal placeholder:text-slate-300">
                                    <p class="text-[11px] font-sans text-slate-500 mt-2 uppercase tracking-wider">Contoh: Abdul Hamid</p>
                                    @error('name') <span class="text-red-500 text-[10px] mt-2 font-bold uppercase tracking-widest">{{ $message }}</span> @enderror
                                </div>

                                <div class="group">
                                    <label class="label-industrial block mb-3 group-focus-within:text-black transition-colors">Alamat Email</label>
                                    <input type="email" wire:model="email" placeholder="contoh@domain.com" 
                                        class="input-industrial w-full px-0 py-4 border-t-0 border-x-0 border-b-2 border-slate-200 focus:border-black outline-none font-bold text-xl placeholder:font-normal placeholder:text-slate-300">
                                    <p class="text-[11px] font-sans text-slate-500 mt-2 uppercase tracking-wider">Contoh: abdulhamid66266@gmail.com</p>
                                    @error('email') <span class="text-red-500 text-[10px] mt-2 font-bold uppercase tracking-widest">{{ $message }}</span> @enderror
                                </div>

                                <div class="group">
                                    <label class="label-industrial block mb-3 group-focus-within:text-black transition-colors">Detail Proyek / Pesan</label>
                                    <textarea wire:model="message" rows="4" placeholder="Bagaimana kami bisa membantu Anda?"
                                        class="input-industrial w-full px-0 py-4 border-t-0 border-x-0 border-b-2 border-slate-200 focus:border-black outline-none font-bold text-xl placeholder:font-normal placeholder:text-slate-300 resize-none"></textarea>
                                    <p class="text-[11px] font-sans text-slate-500 mt-2 uppercase tracking-wider">Contoh: "Butuh 500 pcs roster MMC abu-abu kirim ke Bandung"</p>
                                    @error('message') <span class="text-red-500 text-[10px] mt-2 font-bold uppercase tracking-widest">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <button type="submit" 
                                class="btn-whatsapp-premium w-full py-8 px-10 font-black text-xl tracking-tighter flex items-center justify-center gap-6 group">
                                KIRIM PESAN VIA WHATSAPP
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 group-hover:translate-x-3 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>

                            <p class="text-center text-[10px] font-black text-slate-600 uppercase tracking-[0.4em] mt-10">
                                Transmisi Aman • Tanpa Spam
                            </p>
                        </form>
                    </div>
                </div>

            </div>

            <!-- Full Width Map Section -->
            <div class="mt-40">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <div class="label-industrial text-accent mb-2">Lokasi Kami</div>
                        <h2 class="font-display text-fluid-h2 font-black text-black uppercase tracking-tighter">KUNJUNGI WORKSHOP KAMI</h2>
                    </div>
                    <div class="hidden md:block">
                        <span class="text-slate-600 text-sm font-normal">Purwakarta, Jawa Barat, Indonesia</span>
                    </div>
                </div>
                
                <div class="w-full h-[500px] grayscale hover:grayscale-0 transition-all duration-1000 border-t border-b border-slate-200 bg-slate-100 relative group overflow-hidden">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.856!2d107.320!3d-6.522!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMzEnMTguOCJTIDEwN8KwMTknMTEuMSJF!5e0!3m2!1sen!2sid!4v1715150000000!5m2!1sen!2sid" 
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        class="opacity-80 group-hover:opacity-100 transition-opacity duration-1000">
                    </iframe>
                    
                    <!-- Decorative Frame -->
                    <div class="absolute inset-0 border-[20px] border-white pointer-events-none"></div>
                </div>
            </div>

        </div>
    </div>
</div>
