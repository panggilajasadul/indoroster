<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50 dark:bg-slate-950">
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
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <span>Portal Mitra & Pelanggan</span>
                </div>

                <h2 class="font-display text-2xl sm:text-3xl font-black tracking-tight leading-snug mb-3">
                    Akses Langsung ke Pabrik Roster
                </h2>
                <p class="text-terra-100 text-xs sm:text-sm leading-relaxed mb-6 font-normal">
                    Masuk ke akun Anda untuk mengecek riwayat pesanan, kelola alamat proyek, dan nikmati kemudahan konsultasi pengadaan.
                </p>

                <!-- 3 Keuntungan Akun -->
                <div class="space-y-3">
                    <div class="flex items-start gap-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                        <div class="w-7 h-7 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0 text-emerald-300 font-bold text-xs">
                            ⚡
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Pemesanan Instan</p>
                            <p class="text-[11px] text-terra-100">Data profil & lokasi proyek otomatis terhubung saat pemesanan.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                        <div class="w-7 h-7 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0 text-amber-300 font-bold text-xs">
                            🚚
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Pantau Pengiriman Armada</p>
                            <p class="text-[11px] text-terra-100">Tracking status muatan armada truk langsung dari pabrik.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 bg-white/10 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                        <div class="w-7 h-7 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0 text-blue-300 font-bold text-xs">
                            🤝
                        </div>
                        <div>
                            <p class="text-xs font-bold text-white">Layanan Sales Prioritas</p>
                            <p class="text-[11px] text-terra-100">Konsultasi teknis motif & perhitungan volume proyek.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t border-white/15 text-[11px] text-terra-200 flex items-center justify-between">
                <span>IndoRoster Indonesia</span>
                <span>Pabrik Terpercaya</span>
            </div>
        </div>

        <!-- Right Side: Form Login -->
        <div class="lg:col-span-7 p-8 sm:p-10 flex flex-col justify-between">
            <div>
                <div class="mb-6">
                    <h1 class="font-display text-fluid-h2 font-black text-slate-900 dark:text-white tracking-tight">Selamat Datang Kembali</h1>
                    <p class="mt-1 text-xs sm:text-sm text-slate-500 dark:text-slate-400">Masuk ke akun Anda untuk melanjutkan aktivitas belanja & kemitraan.</p>
                </div>

                <!-- Success Alert -->
                @if (session()->has('success'))
                    <div class="mb-5 p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/40 text-emerald-700 dark:text-emerald-300 text-xs sm:text-sm rounded-xl">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Form -->
                <form wire:submit.prevent="login" class="space-y-4">
                    <!-- Email -->
                    <div>
                        <label for="email" class="font-display block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Email Anda</label>
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

                    <!-- Password -->
                    <div x-data="{ show: false }">
                        <div class="flex justify-between items-center mb-1.5">
                            <label for="password" class="font-display block text-xs font-bold text-slate-700 dark:text-slate-300">Password</label>
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-terra-500 hover:text-terra-600 dark:text-terra-400 dark:hover:text-terra-300 transition-colors">
                                Lupa Password?
                            </a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input id="password" :type="show ? 'text' : 'password'" wire:model="password" placeholder="••••••••" class="w-full pl-10 pr-10 py-2.5 text-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl shadow-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 text-slate-800 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
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

                    <!-- Remember Me -->
                    <div class="flex items-center pt-1">
                        <input id="remember" type="checkbox" wire:model="remember" class="h-4 w-4 text-terra-500 focus:ring-terra-500 border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 rounded">
                        <label for="remember" class="ml-2 block text-xs text-slate-600 dark:text-slate-400">Ingat saya di perangkat ini</label>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" wire:loading.attr="disabled" class="font-display w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-terra-500/20 text-sm font-bold text-white bg-terra-500 hover:bg-terra-600 focus:outline-none focus:ring-4 focus:ring-terra-500/20 transition-all duration-200 cursor-pointer disabled:opacity-75">
                            <span wire:loading.remove wire:target="login">Masuk Sekarang</span>
                            <span wire:loading wire:target="login" class="flex items-center gap-2">
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
                    Belum memiliki akun? 
                    <a href="{{ route('register') }}" class="font-bold text-terra-500 hover:text-terra-600 dark:text-terra-400 dark:hover:text-terra-300 transition-colors">Daftar Akun Baru & Dapatkan Diskon</a>
                </p>
            </div>
        </div>

    </div>
</div>
