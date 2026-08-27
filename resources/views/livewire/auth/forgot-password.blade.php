<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50 dark:bg-slate-950">
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-xl dark:shadow-2xl">
        <!-- Header -->
        <div class="text-center">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 mb-6 group">
                <img src="{{ asset('assets/logo_indoroster_no_text.PNG') }}" alt="Indoroster Logo" class="h-10 w-auto transition-transform duration-300 group-hover:rotate-6">
                <span class="text-2xl font-black tracking-widest text-slate-900 dark:text-white uppercase font-display">INDOROSTER</span>
            </a>
            
            @if ($status)
                <div class="mx-auto w-14 h-14 bg-emerald-100 dark:bg-emerald-950/60 rounded-full flex items-center justify-center mb-4 text-emerald-600 dark:text-emerald-400">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h2 class="font-display text-fluid-h2 font-black text-slate-900 dark:text-white tracking-tight">Periksa Email Anda</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                    {{ $status }}
                </p>
            @else
                <h2 class="font-display text-fluid-h2 font-black text-slate-900 dark:text-white tracking-tight">Lupa Password?</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Masukkan alamat email Anda untuk menerima tautan pemulihan kata sandi.</p>
            @endif
        </div>

        @if ($status)
            <!-- Success State: Form Tersembunyi -->
            <div class="mt-8 space-y-4">
                <a href="{{ route('login') }}" class="font-display w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-terra-500/20 text-sm font-bold text-white bg-terra-500 hover:bg-terra-600 focus:outline-none focus:ring-4 focus:ring-terra-500/20 transition-all duration-200 cursor-pointer">
                    Kembali ke Halaman Masuk
                </a>

                <div class="text-center pt-2">
                    <button type="button" wire:click="$set('status', null)" class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-terra-500 dark:hover:text-terra-400 transition-colors">
                        Tidak menerima email? Kirim ulang atau gunakan email lain
                    </button>
                </div>
            </div>
        @else
            <!-- Form Input Email -->
            <form wire:submit.prevent="sendResetLink" class="mt-8 space-y-6">
                <div class="space-y-5">
                    <!-- Email -->
                    <div>
                        <label for="email" class="font-display block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Email Terdaftar</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                                </svg>
                            </span>
                            <input id="email" type="email" wire:model="email" placeholder="nama@email.com" class="w-full pl-11 pr-4 py-3 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-xl shadow-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 text-slate-800 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500">
                        </div>
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" wire:loading.attr="disabled" class="font-display w-full flex justify-center items-center gap-2 py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-terra-500/20 text-sm font-bold text-white bg-terra-500 hover:bg-terra-600 focus:outline-none focus:ring-4 focus:ring-terra-500/20 transition-all duration-200 cursor-pointer disabled:opacity-50">
                        <svg wire:loading class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span>Kirim Tautan Pemulihan</span>
                    </button>
                </div>
            </form>

            <!-- Footer Link -->
            <div class="text-center mt-6 pt-6 border-t border-slate-100 dark:border-slate-800 flex justify-center items-center gap-4">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-sm font-bold text-slate-600 dark:text-slate-400 hover:text-terra-500 dark:hover:text-terra-400 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Halaman Masuk
                </a>
            </div>
        @endif
    </div>
</div>
