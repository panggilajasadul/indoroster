<div class="bg-[#fff9d6] dark:bg-[#1a1708] border-b border-[#fae27a]/70 dark:border-amber-900/40 text-[12px] sm:text-[13px] text-slate-800 dark:text-amber-100 relative z-30 transition-colors duration-200 shadow-2xs font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 sm:py-2.5">
        <div class="flex items-center justify-center flex-wrap gap-x-4 sm:gap-x-7 gap-y-2 text-center">
            
            <!-- Intro Text (seperti Foto 3 Toco) -->
            <div class="font-bold text-slate-900 dark:text-amber-200">
                Transaksimu jadi lebih berarti di IndoRoster!
            </div>

            <!-- Item 1: Ayo Beli Langsung Pabrik (Hover Tooltip) -->
            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative inline-block">
                <button type="button" class="inline-flex items-center gap-1.5 font-bold text-slate-900 dark:text-amber-300 underline decoration-amber-500/80 hover:text-amber-800 dark:hover:text-amber-200 transition-colors cursor-pointer">
                    <span class="text-amber-600">💛</span>
                    <span>Beli Langsung</span>
                </button>
                <!-- Popover Tooltip Box (seperti Foto 3) -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-150" 
                     x-transition:enter-start="opacity-0 translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0" 
                     x-transition:leave="transition ease-in duration-100" 
                     x-transition:leave-start="opacity-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 translate-y-1" 
                     x-cloak 
                     class="absolute left-1/2 -translate-x-1/2 top-full mt-2 w-72 p-3.5 bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-amber-200 dark:border-slate-700 text-left text-xs leading-relaxed text-slate-700 dark:text-slate-200 z-50 pointer-events-none">
                    Dengan berbelanja di <strong>IndoRoster</strong>, Anda membeli langsung dari sentra produksi tangan pertama di Plered, Purwakarta tanpa potongan perantara atau toko bangunan.
                    <div class="absolute -top-1.5 left-1/2 -translate-x-1/2 w-3 h-3 bg-white dark:bg-slate-900 border-t border-l border-amber-200 dark:border-slate-700 rotate-45"></div>
                </div>
            </div>

            <!-- Item 2: Garansi Pecah Ganti Baru (Hover Tooltip) -->
            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative inline-block">
                <button type="button" class="inline-flex items-center gap-1.5 font-bold text-slate-900 dark:text-emerald-300 underline decoration-emerald-500/80 hover:text-emerald-800 dark:hover:text-emerald-200 transition-colors cursor-pointer">
                    <span class="text-emerald-600">🛡️</span>
                    <span>Garansi Pecah Ganti Baru</span>
                </button>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-150" 
                     x-transition:enter-start="opacity-0 translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0" 
                     x-transition:leave="transition ease-in duration-100" 
                     x-transition:leave-start="opacity-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 translate-y-1" 
                     x-cloak 
                     class="absolute left-1/2 -translate-x-1/2 top-full mt-2 w-72 p-3.5 bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-emerald-200 dark:border-slate-700 text-left text-xs leading-relaxed text-slate-700 dark:text-slate-200 z-50 pointer-events-none">
                    Setiap keping roster yang pecah atau rusak dalam perjalanan pengiriman oleh armada pabrik kami akan langsung diganti baru tanpa biaya tambahan.
                    <div class="absolute -top-1.5 left-1/2 -translate-x-1/2 w-3 h-3 bg-white dark:bg-slate-900 border-t border-l border-emerald-200 dark:border-slate-700 rotate-45"></div>
                </div>
            </div>

            <!-- Item 3: Transaksi Dijamin Aman (Hover Tooltip) -->
            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative inline-block">
                <button type="button" class="inline-flex items-center gap-1.5 font-bold text-slate-900 dark:text-blue-300 underline decoration-blue-500/80 hover:text-blue-800 dark:hover:text-blue-200 transition-colors cursor-pointer">
                    <span class="text-blue-600">💳</span>
                    <span>Transaksi Dijamin Aman</span>
                </button>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-150" 
                     x-transition:enter-start="opacity-0 translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0" 
                     x-transition:leave="transition ease-in duration-100" 
                     x-transition:leave-start="opacity-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 translate-y-1" 
                     x-cloak 
                     class="absolute left-1/2 -translate-x-1/2 top-full mt-2 w-72 p-3.5 bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-blue-200 dark:border-slate-700 text-left text-xs leading-relaxed text-slate-700 dark:text-slate-200 z-50 pointer-events-none">
                    Transaksi resmi dan terlindungi dengan penerbitan Invoice Resmi otomatis serta konfirmasi jadwal langsung oleh tim Admin WhatsApp Pabrik.
                    <div class="absolute -top-1.5 left-1/2 -translate-x-1/2 w-3 h-3 bg-white dark:bg-slate-900 border-t border-l border-blue-200 dark:border-slate-700 rotate-45"></div>
                </div>
            </div>

            <!-- Item 4: Harga Terbaik Buat Kamu (Hover Tooltip) -->
            <div x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" class="relative inline-block">
                <button type="button" class="inline-flex items-center gap-1.5 font-bold text-slate-900 dark:text-amber-300 underline decoration-amber-500/80 hover:text-amber-800 dark:hover:text-amber-200 transition-colors cursor-pointer">
                    <span class="text-amber-600">🏷️</span>
                    <span>Harga Terbaik Buat Kamu</span>
                </button>
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-150" 
                     x-transition:enter-start="opacity-0 translate-y-1" 
                     x-transition:enter-end="opacity-100 translate-y-0" 
                     x-transition:leave="transition ease-in duration-100" 
                     x-transition:leave-start="opacity-100 translate-y-0" 
                     x-transition:leave-end="opacity-0 translate-y-1" 
                     x-cloak 
                     class="absolute right-0 sm:left-1/2 sm:-translate-x-1/2 top-full mt-2 w-72 p-3.5 bg-white dark:bg-slate-900 rounded-xl shadow-2xl border border-amber-200 dark:border-slate-700 text-left text-xs leading-relaxed text-slate-700 dark:text-slate-200 z-50 pointer-events-none">
                    Dapatkan harga pabrik paling transparan untuk pemesanan partai kecil hingga ribuan keping roster cetak padat presisi.
                    <div class="absolute -top-1.5 right-6 sm:left-1/2 sm:-translate-x-1/2 w-3 h-3 bg-white dark:bg-slate-900 border-t border-l border-amber-200 dark:border-slate-700 rotate-45"></div>
                </div>
            </div>

        </div>
    </div>
</div>
