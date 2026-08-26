@props([])

@php
    $rawWa = \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
    $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
    if (str_starts_with($waNumber, '0')) {
        $waNumber = '62' . substr($waNumber, 1);
    }
    $csName = \App\Models\SiteSetting::getValue('whatsapp_cs_name', 'CS IndoRoster');
@endphp

<div 
    x-data="{ 
        open: false, 
        hasUnread: true, 
        message: '',
        currentTime: '',
        init() {
            const now = new Date();
            let hours = now.getHours().toString().padStart(2, '0');
            let mins = now.getMinutes().toString().padStart(2, '0');
            this.currentTime = hours + ':' + mins;
        },
        toggleChat() {
            this.open = !this.open;
            if (this.open) {
                this.hasUnread = false;
                this.$nextTick(() => {
                    if (this.$refs.chatInput) {
                        this.$refs.chatInput.focus();
                    }
                });
            }
        },
        sendMessage() {
            let text = this.message.trim();
            if (!text) {
                text = 'Halo {{ $csName }}, saya ingin konsultasi produk dan harga roster beton minimalis.';
            }
            let url = 'https://wa.me/{{ $waNumber }}?text=' + encodeURIComponent(text);
            window.open(url, '_blank');
            this.message = '';
            this.open = false;
        }
    }"
    class="fixed bottom-20 right-4 sm:bottom-6 sm:right-6 z-50 select-none"
>
    <!-- Chat Popup Window -->
    <div 
        x-show="open" 
        x-cloak
        x-transition:enter="transition cubic-bezier(0.16, 1, 0.3, 1) duration-300"
        x-transition:enter-start="opacity-0 translate-y-8 scale-90"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 scale-90"
        @click.outside="open = false"
        class="absolute bottom-16 sm:bottom-20 right-0 w-[calc(100vw-2rem)] max-w-[360px] sm:w-[380px] bg-slate-100 dark:bg-slate-900 rounded-3xl shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden flex flex-col z-50 font-sans"
        style="box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.35);"
    >
        <!-- Header -->
        <div class="bg-[#075E54] text-white p-4 flex items-center justify-between gap-3 shadow-md relative z-10">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-11 h-11 rounded-full bg-white/20 p-0.5 border border-white/40 overflow-hidden shadow-inner flex items-center justify-center">
                        <img 
                            src="{{ asset('assets/logo_indoroster_no_text.PNG') }}" 
                            alt="{{ $csName }}" 
                            class="w-full h-full object-cover bg-white rounded-full"
                        >
                    </div>
                    <!-- Online Dot Indicator -->
                    <span class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-emerald-400 border-2 border-[#075E54] rounded-full"></span>
                </div>
                <div>
                    <h4 class="font-bold text-sm tracking-wide text-white leading-tight flex items-center gap-1.5">
                        <span>{{ $csName }}</span>
                        <svg class="w-3.5 h-3.5 text-emerald-300 inline" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </h4>
                    <p class="text-[11px] text-emerald-100/90 flex items-center gap-1 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                        <span>Online · Membalas dalam beberapa menit</span>
                    </p>
                </div>
            </div>

            <!-- Close Button -->
            <button 
                @click="open = false" 
                class="w-8 h-8 rounded-full bg-white/10 hover:bg-white/25 active:scale-95 text-white flex items-center justify-center transition-all cursor-pointer"
                aria-label="Tutup Chat"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Chat Body with WhatsApp Pattern -->
        <div 
            class="p-4 flex-1 overflow-y-auto max-h-[340px] space-y-3 relative"
            style="background-color: #E5DDD5; background-image: radial-gradient(#d4cbbe 1.5px, transparent 1.5px); background-size: 16px 16px;"
        >
            <!-- Date / Status Pill -->
            <div class="flex justify-center">
                <span class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm text-slate-600 dark:text-slate-300 text-[10px] font-semibold px-3 py-1 rounded-lg shadow-xs uppercase tracking-wider">
                    Hari Ini
                </span>
            </div>

            <!-- Incoming Admin Message Bubble -->
            <div class="flex items-start gap-2 max-w-[88%]">
                <div class="bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 p-3.5 rounded-2xl rounded-tl-xs shadow-sm border border-slate-100 dark:border-slate-700/60 relative text-xs leading-relaxed">
                    <div class="font-bold text-[#075E54] dark:text-emerald-400 text-[11px] mb-1">
                        {{ $csName }}
                    </div>
                    <p class="mb-2">
                        Halo! Selamat datang di <strong>IndoRoster</strong> 👋
                    </p>
                    <p class="text-slate-600 dark:text-slate-300 mb-2">
                        Butuh info katalog motif, konsultasi ukuran & kebutuhan roster beton, atau estimasi ongkos kirim armada pabrik?
                    </p>
                    <p class="text-slate-700 dark:text-slate-200 font-medium">
                        Ketik pertanyaan Kakak di bawah untuk langsung terhubung ke WhatsApp kami! 💬
                    </p>
                    
                    <div class="flex items-center justify-end gap-1 mt-2 text-[10px] text-slate-400">
                        <span x-text="currentTime"></span>
                        <!-- WhatsApp Double Check Icon -->
                        <svg class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-3 0a.5.5 0 0 0-.708-.708L5 7.293l-.646-.647a.5.5 0 0 0-.708.708l1 1a.5.5 0 0 0 .708 0l4-4z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Input Footer -->
        <div class="p-3 bg-[#F0F2F5] dark:bg-slate-800/90 border-t border-slate-200 dark:border-slate-700 flex items-center gap-2">
            <input 
                type="text" 
                x-ref="chatInput"
                x-model="message"
                @keydown.enter.prevent="sendMessage()"
                placeholder="Ketik pesan Anda di sini..."
                class="flex-1 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 placeholder-slate-400 text-xs px-4 py-3 rounded-full border border-slate-200 dark:border-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent shadow-xs transition"
            >
            <button 
                @click="sendMessage()"
                class="w-10 h-10 rounded-full bg-[#25D366] hover:bg-emerald-600 active:scale-95 text-white flex items-center justify-center shadow-md transition-all cursor-pointer shrink-0"
                aria-label="Kirim Pesan ke WhatsApp"
            >
                <svg class="w-4 h-4 translate-x-0.5 fill-current" viewBox="0 0 24 24">
                    <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Floating Trigger Button (Berdenyut / Pulsing Ring + Notification Badge) -->
    <div class="relative group">
        <!-- Outer Pulse Radar Rings -->
        <span class="absolute -inset-1.5 rounded-full bg-emerald-500/40 animate-ping pointer-events-none opacity-75"></span>
        <span class="absolute -inset-1 rounded-full bg-emerald-400/30 animate-pulse pointer-events-none"></span>

        <!-- Floating Button -->
        <button 
            @click="toggleChat()"
            class="relative w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-[#25D366] hover:bg-[#20bd5a] text-white flex items-center justify-center shadow-2xl transition-all duration-300 hover:scale-105 active:scale-95 cursor-pointer z-10"
            aria-label="Buka Chat WhatsApp"
        >
            <!-- WhatsApp Icon -->
            <svg class="w-7 h-7 sm:w-8 sm:h-8 fill-current" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
        </button>

        <!-- Red Notification Badge with number 1 -->
        <span 
            x-show="hasUnread && !open" 
            x-transition
            class="absolute -top-1 -right-1 z-20 w-6 h-6 rounded-full bg-rose-500 text-white font-black text-xs flex items-center justify-center border-2 border-white dark:border-slate-900 shadow-md animate-bounce pointer-events-none"
        >
            1
        </span>
    </div>
</div>
