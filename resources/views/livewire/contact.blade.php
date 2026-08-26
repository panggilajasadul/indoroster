<div>
    @if($page && is_array($page->content) && count($page->content) > 0)
        <x-block-renderer :blocks="$page->content" :page-title="$page->title ?? 'Kontak Kami'" />
    @else
    <div class="min-h-screen bg-[#F8F9FA] dark:bg-slate-950 py-16 sm:py-24 md:py-28">
        @php
            $rawWa = \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
            $email = \App\Models\SiteSetting::getValue('contact_email', 'abdulhamid66266@gmail.com');
            $address = \App\Models\SiteSetting::getValue('factory_address', 'Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165');
            
            $waFormatted = preg_replace('/[^0-9]/', '', $rawWa);
            if (str_starts_with($waFormatted, '0')) {
                $waFormatted = '+62 ' . substr($rawWa, 1);
            } else {
                $waFormatted = $rawWa;
            }
        @endphp

    <style>
        .contact-card-premium {
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            background: #ffffff;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .dark .contact-card-premium {
            background: #0f172a;
            border: 1px solid rgba(255,255,255,0.08);
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
        .dark .label-industrial {
            color: #94a3b8;
        }
        .input-industrial {
            background: transparent;
            border-radius: 0px;
            transition: all 0.3s ease;
        }
        .input-industrial:focus {
            box-shadow: none;
            padding-left: 1rem;
        }
        .outline-text {
            -webkit-text-stroke: 1px rgba(0,0,0,0.1);
            color: transparent;
        }
        .dark .outline-text {
            -webkit-text-stroke: 1px rgba(255,255,255,0.05);
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
            <div class="mb-14 sm:mb-20">
                <x-breadcrumb :items="[['label' => 'Kontak Kami']]" class="!px-0 !py-0 mb-6" />
                <div class="flex items-center gap-4 mb-4">
                    <span class="w-10 h-[2px] bg-accent"></span>
                    <span class="label-industrial text-accent">Hubungi Kami</span>
                </div>
                <h1 class="font-display text-fluid-h1 font-black text-black dark:text-white leading-[0.95] tracking-tighter mb-6">
                    HUBUNGI <br> <span class="text-accent">KAMI.</span>
                </h1>
                <p class="text-base sm:text-lg text-slate-800 dark:text-slate-300 max-w-2xl leading-relaxed font-normal">
                    Solusi roster beton berkualitas tinggi untuk proyek arsitektur Anda. Hubungi kami untuk konsultasi teknis, estimasi harga, dan pengiriman seluruh Indonesia.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
                
                <!-- Left: Contact Details -->
                <div class="lg:col-span-5 space-y-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-6">
                        
                        <!-- Phone/WA -->
                        <div class="contact-card-premium p-8 rounded-2xl">
                            <div class="label-industrial mb-4">Layanan WhatsApp</div>
                            <div class="text-2xl md:text-3xl font-bold text-black dark:text-white mb-2">{{ $waFormatted }}</div>
                            <p class="text-slate-600 dark:text-slate-400 text-sm">Respon cepat: Senin - Sabtu (08:00 - 17:00 WIB)</p>
                        </div>

                        <!-- Email -->
                        <div class="contact-card-premium p-8 rounded-2xl">
                            <div class="label-industrial mb-4">Email Resmi</div>
                            <div class="text-xl md:text-2xl font-bold text-black dark:text-white mb-2 break-all">{{ $email }}</div>
                            <p class="text-slate-600 dark:text-slate-400 text-sm">Untuk penawaran resmi, invoice & tender proyek.</p>
                        </div>

                        <!-- Address -->
                        <div class="contact-card-premium p-8 rounded-2xl lg:col-span-1">
                            <div class="label-industrial mb-4">Alamat Pabrik & Workshop</div>
                            <div class="text-base sm:text-lg font-bold text-black dark:text-white leading-snug mb-4">
                                {{ $address }}
                            </div>
                            <a href="https://www.google.com/maps/place/Indoroster+-+Produsen+Roster+Minimalis+%26+Aneka+Bata+Murah/@-6.6689917,107.3619295,19z/data=!4m6!3m5!1s0x2e69073a5c4870d1:0x9daaab3cd6ae595d!8m2!3d-6.6689917!4d107.3619295!16s%2Fg%2F11njz2_9sv" target="_blank" class="text-xs font-black uppercase tracking-widest text-accent hover:underline flex items-center gap-2">
                                BUKA GOOGLE MAPS 
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Industrial Badge -->
                    <div class="bg-black dark:bg-slate-900 border border-slate-800 p-8 text-white rounded-2xl relative overflow-hidden group">
                        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-accent/20 rounded-full blur-3xl"></div>
                        <h4 class="font-display text-2xl font-black mb-3 text-white">PRODUKSI LANGSUNG PABRIK</h4>
                        <p class="text-slate-300 dark:text-slate-400 leading-relaxed text-sm">
                            Kapasitas produksi ribuan pcs per hari dengan teknik cetak tumbuk padat alat pres khusus, siap melayani pesanan retail hunian maupun proyek komersial skala besar ke seluruh Indonesia.
                        </p>
                    </div>
                </div>

                <!-- Right: Form -->
                <div class="lg:col-span-7">
                    <div class="bg-white dark:bg-slate-900 p-8 md:p-14 rounded-3xl shadow-soft-xs border border-slate-200/80 dark:border-slate-800">
                        <div class="mb-10">
                            <h3 class="font-display text-fluid-h2 font-black text-black dark:text-white mb-3 uppercase tracking-tighter">Formulir Permintaan Penawaran</h3>
                            <p class="text-slate-600 dark:text-slate-400 text-sm sm:text-base">Lengkapi formulir di bawah ini untuk konsultasi motif, simulasi ongkir, dan penawaran harga khusus langsung dari pabrik.</p>
                        </div>

                        <form wire:submit.prevent="sendWhatsApp" class="space-y-6">
                            <div class="space-y-6">
                                <div class="group">
                                    <label class="label-industrial block mb-2 group-focus-within:text-black dark:group-focus-within:text-white transition-colors">Nama Lengkap</label>
                                    <input type="text" wire:model="name" placeholder="Masukkan nama lengkap Anda" 
                                        class="input-industrial w-full px-0 py-3.5 border-t-0 border-x-0 border-b-2 border-slate-200 dark:border-slate-700 focus:border-black dark:focus:border-white text-slate-900 dark:text-white outline-none font-bold text-lg placeholder:font-normal placeholder:text-slate-300 dark:placeholder:text-slate-600">
                                    <p class="text-[11px] font-sans text-slate-400 dark:text-slate-500 mt-1 uppercase tracking-wider">Contoh: Abdul Hamid</p>
                                    @error('name') <span class="text-red-500 text-[11px] mt-1.5 block font-bold uppercase tracking-wider">{{ $message }}</span> @enderror
                                </div>

                                <div class="group">
                                    <label class="label-industrial block mb-2 group-focus-within:text-black dark:group-focus-within:text-white transition-colors">Alamat Email</label>
                                    <input type="email" wire:model="email" placeholder="contoh@domain.com" 
                                        class="input-industrial w-full px-0 py-3.5 border-t-0 border-x-0 border-b-2 border-slate-200 dark:border-slate-700 focus:border-black dark:focus:border-white text-slate-900 dark:text-white outline-none font-bold text-lg placeholder:font-normal placeholder:text-slate-300 dark:placeholder:text-slate-600">
                                    <p class="text-[11px] font-sans text-slate-400 dark:text-slate-500 mt-1 uppercase tracking-wider">Contoh: {{ $email }}</p>
                                    @error('email') <span class="text-red-500 text-[11px] mt-1.5 block font-bold uppercase tracking-wider">{{ $message }}</span> @enderror
                                </div>

                                <div class="group">
                                    <label class="label-industrial block mb-2 group-focus-within:text-black dark:group-focus-within:text-white transition-colors">Nomor WhatsApp</label>
                                    <input type="text" wire:model="phone" placeholder="08123456789" 
                                        class="input-industrial w-full px-0 py-3.5 border-t-0 border-x-0 border-b-2 border-slate-200 dark:border-slate-700 focus:border-black dark:focus:border-white text-slate-900 dark:text-white outline-none font-bold text-lg placeholder:font-normal placeholder:text-slate-300 dark:placeholder:text-slate-600">
                                    <p class="text-[11px] font-sans text-slate-400 dark:text-slate-500 mt-1 uppercase tracking-wider">Untuk pengiriman invoice & foto stok real-time</p>
                                    @error('phone') <span class="text-red-500 text-[11px] mt-1.5 block font-bold uppercase tracking-wider">{{ $message }}</span> @enderror
                                </div>

                                <div class="group">
                                    <label class="label-industrial block mb-2 group-focus-within:text-black dark:group-focus-within:text-white transition-colors">Pesan & Kebutuhan Proyek</label>
                                    <textarea wire:model="message" rows="3" placeholder="Sebutkan jenis roster, jumlah kebutuhan (pcs/m²), dan kota tujuan pengiriman..." 
                                        class="input-industrial w-full px-0 py-3.5 border-t-0 border-x-0 border-b-2 border-slate-200 dark:border-slate-700 focus:border-black dark:focus:border-white text-slate-900 dark:text-white outline-none font-bold text-base placeholder:font-normal placeholder:text-slate-300 dark:placeholder:text-slate-600"></textarea>
                                    <p class="text-[11px] font-sans text-slate-400 dark:text-slate-500 mt-1 uppercase tracking-wider">Contoh: Butuh roster nako 500 pcs untuk proyek di Bandung</p>
                                    @error('message') <span class="text-red-500 text-[11px] mt-1.5 block font-bold uppercase tracking-wider">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <button type="submit" class="btn-whatsapp-premium w-full py-5 px-8 font-black uppercase text-xs tracking-widest flex items-center justify-center gap-3 mt-8 cursor-pointer rounded-xl">
                                <span>KIRIM PESAN VIA WHATSAPP</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
    @endif
</div>
