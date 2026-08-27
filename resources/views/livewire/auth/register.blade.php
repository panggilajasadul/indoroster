<div class="min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50 dark:bg-slate-950">
    <div class="max-w-4xl w-full grid grid-cols-1 lg:grid-cols-12 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-2xl overflow-hidden">
        
        <!-- Left Side: Value Proposition & Benefit Kemitraan (B2B & B2C) -->
        <div class="lg:col-span-5 bg-gradient-to-br from-terra-600 to-terra-800 p-8 text-white flex flex-col justify-between relative overflow-hidden">
            <!-- Background Decorative Circles -->
            <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -left-12 -top-12 w-48 h-48 bg-black/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 mb-8 group">
                    <img src="{{ asset('assets/logo_indoroster_no_text.PNG') }}" alt="Indoroster Logo" class="h-10 w-auto brightness-0 invert transition-transform duration-300 group-hover:rotate-6">
                    <span class="text-2xl font-black tracking-widest text-white uppercase font-display">INDOROSTER</span>
                </a>

                <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/15 backdrop-blur-md rounded-full text-xs font-black uppercase tracking-wider mb-4 border border-white/20">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span>Kemitraan & Pelanggan</span>
                </div>

                <h2 class="font-display text-2xl sm:text-3xl font-black tracking-tight leading-snug mb-3">
                    Terhubung Langsung dengan Pabrik Roster
                </h2>
                <p class="text-terra-100 text-xs sm:text-sm leading-relaxed mb-6 font-normal">
                    Bergabung bersama ribuan pemilik rumah, kontraktor, arsitek, dan developer perumahan di seluruh Indonesia.
                </p>

                <!-- 4 Keuntungan Kemitraan -->
                <div class="space-y-3.5">
                    <div class="flex items-start gap-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                        <div class="w-7 h-7 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0 text-emerald-300 font-bold text-xs">
                            🏷️
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Diskon Khusus Proyek & Volume</p>
                            <p class="text-[11px] text-terra-100">Pricelist tangan pertama untuk kontraktor & pengadaan skala besar.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                        <div class="w-7 h-7 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0 text-amber-300 font-bold text-xs">
                            🚚
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Prioritas Armada Truk Pabrik</p>
                            <p class="text-[11px] text-terra-100">Kirim aman langsung ke titik proyek dengan garansi pecah ganti baru.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                        <div class="w-7 h-7 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0 text-blue-300 font-bold text-xs">
                            📐
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Konsultasi Hitungan Fasad Gratis</p>
                            <p class="text-[11px] text-terra-100">Dibantu kalkulasi kebutuhan keping, pola susun motif, dan semen.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                        <div class="w-7 h-7 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0 text-purple-300 font-bold text-xs">
                            📑
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Dokumen Faktur & Surat Jalan Resmi</p>
                            <p class="text-[11px] text-terra-100">Tertib administrasi untuk penagihan dan pengawasan mandor proyek.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-white/15 text-[11px] text-terra-200 flex items-center justify-between">
                <span>Pabrik Plered, Purwakarta</span>
                <span>Garansi 100% QC</span>
            </div>
        </div>

        <!-- Right Side: Form Pendaftaran -->
        <div class="lg:col-span-7 p-8 sm:p-10 flex flex-col justify-between">
            <div>
                <div class="mb-6">
                    <h1 class="font-display text-fluid-h2 font-black text-slate-900 dark:text-white tracking-tight">Daftar Akun Baru</h1>
                    <p class="mt-1 text-xs sm:text-sm text-slate-500 dark:text-slate-400">Mulai transaksi dengan mudah, simpan alamat proyek, dan nikmati layanan pabrik langsung.</p>
                </div>

                <!-- Form -->
                <form wire:submit.prevent="register" class="space-y-4">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="name" class="font-display block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nama Lengkap</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <input id="name" type="text" wire:model="name" placeholder="Contoh: Bpk. Budi Santoso / PT Graha Cipta" class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl shadow-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 text-slate-800 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500">
                        </div>
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="font-display block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Email Aktif</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                </svg>
                            </span>
                            <input id="email" type="email" wire:model="email" placeholder="nama@email.com" class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl shadow-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 text-slate-800 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500">
                        </div>
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nomor HP / WhatsApp -->
                    <div>
                        <label for="phone" class="font-display block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Nomor WhatsApp Aktif</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </span>
                            <input id="phone" type="tel" wire:model="phone" placeholder="Contoh: 0812XXXXXXXX" class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl shadow-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 text-slate-800 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500">
                        </div>
                        @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div x-data="{ show: false }">
                            <label for="password" class="font-display block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Password</label>
                            <div class="relative">
                                <input id="password" :type="show ? 'text' : 'password'" wire:model="password" placeholder="Min. 6 karakter" class="w-full pl-3.5 pr-9 py-2.5 text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl shadow-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 text-slate-800 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                                    <svg x-show="!show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="show" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                            @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div>
                            <label for="password_confirmation" class="font-display block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Ulangi Password</label>
                            <input id="password_confirmation" type="password" wire:model="password_confirmation" placeholder="Ulangi password" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl shadow-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 text-slate-800 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" wire:loading.attr="disabled" class="font-display w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-terra-500/20 text-sm font-bold text-white bg-terra-500 hover:bg-terra-600 focus:outline-none focus:ring-4 focus:ring-terra-500/20 transition-all duration-200 disabled:opacity-75 disabled:cursor-not-allowed cursor-pointer">
                            <span wire:loading.remove wire:target="register">Daftar Sekarang & Lengkapi Profil</span>
                            <span wire:loading wire:target="register" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer Link -->
            <div class="text-center mt-6 pt-5 border-t border-slate-100 dark:border-slate-800">
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="font-bold text-terra-500 hover:text-terra-600 dark:text-terra-400 dark:hover:text-terra-300 transition-colors">Masuk ke Akun Anda</a>
                </p>
            </div>
        </div>

    </div>
</div>
