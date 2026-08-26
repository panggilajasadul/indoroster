<div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6 bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-soft-xs border border-slate-100 dark:border-slate-800">
        {{-- Step Indicator --}}
        <div class="flex items-center justify-center gap-2 mb-2">
            <div class="flex items-center gap-1.5">
                <div class="w-7 h-7 rounded-full bg-terra-500 text-white flex items-center justify-center text-xs font-bold">✓</div>
                <span class="text-xs font-semibold text-terra-600 dark:text-terra-400 hidden sm:inline">Daftar</span>
            </div>
            <div class="w-8 h-0.5 bg-terra-500"></div>
            <div class="flex items-center gap-1.5">
                <div class="w-7 h-7 rounded-full bg-terra-500 text-white flex items-center justify-center text-xs font-bold animate-pulse">2</div>
                <span class="text-xs font-semibold text-terra-600 dark:text-terra-400 hidden sm:inline">Verifikasi</span>
            </div>
            <div class="w-8 h-0.5 bg-slate-200 dark:bg-slate-700"></div>
            <div class="flex items-center gap-1.5">
                <div class="w-7 h-7 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center text-xs font-bold">3</div>
                <span class="text-xs font-medium text-slate-400 dark:text-slate-500 hidden sm:inline">Selesai</span>
            </div>
        </div>

        <div class="text-center">
            <div class="mx-auto w-16 h-16 bg-terra-50 dark:bg-terra-950/40 rounded-full flex items-center justify-center mb-6">
                <svg class="w-8 h-8 text-terra-600 dark:text-terra-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">
                Cek Email Anda
            </h2>
            <p class="mt-4 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                Kami telah mengirimkan tautan verifikasi ke alamat email:
            </p>
            <p class="mt-1 text-sm font-bold text-terra-600 dark:text-terra-400 bg-terra-50 dark:bg-terra-950/40 px-4 py-2 rounded-lg inline-block border border-terra-100 dark:border-terra-900/40">
                {{ auth()->user()->email }}
            </p>
            <p class="mt-4 text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                Buka kotak masuk email Anda dan klik tautan verifikasi. Jika tidak menemukan email tersebut, periksa folder <strong>Spam</strong> atau klik tombol di bawah untuk mengirim ulang.
            </p>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/40 p-4 rounded-xl flex items-start gap-3">
                <svg class="w-5 h-5 text-emerald-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm text-emerald-700 dark:text-emerald-300">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        <div class="space-y-3">
            <button wire:click="resend" wire:loading.attr="disabled" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-terra-600 hover:bg-terra-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-terra-500 transition-colors disabled:opacity-75 disabled:cursor-not-allowed cursor-pointer">
                <span wire:loading.remove wire:target="resend">Kirim Ulang Email Verifikasi</span>
                <span wire:loading wire:target="resend" class="flex items-center gap-2">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Mengirim...
                </span>
            </button>

            <a href="{{ route('logout') }}" class="w-full flex justify-center py-3 px-4 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm text-sm font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-200 transition-colors">
                Keluar
            </a>
        </div>
    </div>
</div>
