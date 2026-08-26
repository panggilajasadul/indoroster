@props(['data' => []])

@php
    $badge = $data['badge'] ?? 'PROMO & VOUCHER WILAYAH';
    $title = $data['title'] ?? 'Klaim Promo Armada Pabrik Sesuai Lokasi Proyek Anda';
    $description = $data['description'] ?? 'Gunakan kode voucher pengiriman armada pabrik saat checkout atau sebutkan saat konsultasi dengan tim Admin WhatsApp.';
    $buttonText = $data['button_text'] ?? 'Konsultasi Admin Pabrik';
    $buttonUrl = $data['button_url'] ?? 'https://wa.me/6281389709847';
    $bgTheme = $data['bg_theme'] ?? 'white';

    $vouchers = \App\Models\Voucher::active()->get();
@endphp

<section class="py-12 sm:py-16 {{ $bgTheme === 'dark' ? 'bg-slate-950 text-white' : 'bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-white' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-amber-500/10 via-terra-500/5 to-amber-500/15 dark:from-slate-900/90 dark:via-slate-900 dark:to-slate-900/90 rounded-3xl border border-amber-300/60 dark:border-slate-800 p-6 sm:p-8 shadow-soft-xs relative overflow-hidden" x-data="{ copiedCode: null }">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/20 text-amber-900 dark:text-amber-300 text-[11px] font-black uppercase tracking-wider mb-2 border border-amber-500/30">
                        <span>🏷️</span>
                        <span>{{ $badge }}</span>
                    </div>
                    <h3 class="font-display text-xl sm:text-2xl font-black tracking-tight text-slate-900 dark:text-white">
                        {{ $title }}
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 max-w-2xl">
                        {{ $description }}
                    </p>
                </div>
                @if($buttonText)
                <a href="{{ $buttonUrl }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-terra-500 hover:bg-terra-600 text-white font-bold text-xs sm:text-sm shadow-xs transition-all shrink-0">
                    <span>💬 {{ $buttonText }}</span>
                </a>
                @endif
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($vouchers as $voucher)
                    <div class="bg-white dark:bg-slate-800/90 p-4 sm:p-5 rounded-2xl border border-slate-200/80 dark:border-slate-700/80 flex flex-col justify-between shadow-2xs group hover:border-terra-400 dark:hover:border-terra-500 transition-all">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-1 rounded-md bg-amber-50 dark:bg-amber-500/15 text-amber-900 dark:text-amber-300 text-[10px] sm:text-[11px] font-black uppercase tracking-wider border border-amber-300/80 dark:border-amber-500/30">
                                    {{ $voucher->badge_text ?: 'Promo Spesial' }}
                                </span>
                                <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-0.5 rounded-md border border-emerald-200/60 dark:border-emerald-900/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Aktif
                                </span>
                            </div>
                            <h4 class="font-bold text-sm text-slate-900 dark:text-white mb-1.5 group-hover:text-terra-600 dark:group-hover:text-terra-400 transition-colors">
                                {{ $voucher->name }}
                            </h4>
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed mb-4">
                                {{ $voucher->description }}
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between gap-2">
                            <div class="font-mono text-xs font-black tracking-wider text-slate-900 dark:text-amber-300 bg-slate-100 dark:bg-slate-950 px-2.5 py-1.5 rounded-lg border border-dashed border-slate-300 dark:border-slate-700">
                                {{ $voucher->code }}
                            </div>
                            <button 
                                type="button" 
                                @click="navigator.clipboard.writeText('{{ $voucher->code }}'); copiedCode = '{{ $voucher->code }}'; setTimeout(() => copiedCode = null, 2500)"
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs font-bold text-terra-600 dark:text-white bg-terra-50 dark:bg-terra-600 hover:bg-terra-500 hover:text-white dark:hover:bg-terra-500 transition-all cursor-pointer border border-terra-200/60 dark:border-transparent"
                            >
                                <span x-text="copiedCode === '{{ $voucher->code }}' ? '✓ Tersalin!' : 'Salin Kode'"></span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
