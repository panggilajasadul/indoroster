<x-filament-widgets::widget>
    @php
        $quotes = [
            'Hati-hati di jalan ya, keluarga menanti Anda pulang dengan selamat di rumah. ❤️',
            'Jangan dipaksakan jika lelah, segera cari tempat aman untuk istirahat sejenak. ☕',
            'Ngopi dulu jika mengantuk, keselamatan Anda adalah yang nomor satu! ☕🚛',
            'Tetap fokus berkendara dan utamakan keselamatan lalu lintas ya. 🛣️',
            'Semoga hari Anda menyenangkan dan semua pengiriman berjalan dengan lancar! 🌟',
            'Anda adalah pahlawan Indoroster hari ini. Terima kasih atas kerja keras Anda! 💪',
        ];
        $seed = date('G'); // seed hourly
        mt_srand($seed);
        $randomQuote = $quotes[mt_rand(0, count($quotes) - 1)];
        mt_srand();
    @endphp

    <div x-data="{
        time: '',
        date: '',
        greeting: 'Selamat bekerja',
        updateClock() {
            const now = new Date();
            
            // 1. Sapaan dinamis berdasarkan jam lokal kurir
            const hour = now.getHours();
            if (hour >= 4 && hour < 11) {
                this.greeting = 'Selamat pagi';
            } else if (hour >= 11 && hour < 15) {
                this.greeting = 'Selamat siang';
            } else if (hour >= 15 && hour < 19) {
                this.greeting = 'Selamat sore';
            } else {
                this.greeting = 'Selamat malam';
            }
            
            // 2. Tanggal format Indonesia lokal
            const options = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
            this.date = now.toLocaleDateString('id-ID', options);
            
            // 3. Jam berdetik real-time
            this.time = now.toLocaleTimeString('id-ID', { hour12: false }) + ' WIB';
        }
    }" x-init="updateClock(); setInterval(() => updateClock(), 1000)" class="p-5 rounded-2xl bg-gradient-to-r from-orange-500/10 via-amber-500/5 to-transparent border border-orange-500/20 dark:border-orange-500/30 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="hidden sm:flex w-12 h-12 rounded-full bg-orange-500/20 text-orange-600 dark:text-orange-400 items-center justify-center text-2xl shrink-0">
                ☕
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-800 dark:text-white leading-tight">
                    <span x-text="greeting"></span>, {{ auth()->user()->name }}! 👋
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1.5 font-medium leading-relaxed">
                    {{ $randomQuote }}
                </p>
            </div>
        </div>
        
        <!-- Live Clock & Date -->
        <div class="sm:text-right shrink-0 border-t border-slate-100 dark:border-slate-800 sm:border-0 pt-3 sm:pt-0">
            <div x-text="date" class="text-xs text-slate-400 dark:text-slate-500 font-semibold uppercase tracking-wider"></div>
            <div x-text="time" class="text-lg sm:text-xl font-bold text-orange-600 dark:text-orange-500 font-mono tracking-tight mt-0.5"></div>
        </div>
    </div>
</x-filament-widgets::widget>
