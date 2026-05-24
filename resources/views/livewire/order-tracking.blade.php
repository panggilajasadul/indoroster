<div class="bg-slate-50 min-h-screen py-12">
    <!-- Midtrans Snap JS -->
    @if(config('midtrans.is_production'))
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- JIKA BELUM MELAKUKAN PENCARIAN ATAU TIDAK DITEMUKAN -->
        @if(!$searched || !$order)
            <div class="max-w-md mx-auto">
                <div class="text-center mb-8">
                    <div class="inline-flex p-3.5 bg-terra-50 text-terra-600 rounded-2xl mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h1 class="font-display text-fluid-h1 font-black text-slate-900 tracking-tight">Lacak Pesanan Anda</h1>
                    <p class="text-slate-500 mt-2 text-sm leading-relaxed">
                        Masukkan nomor invoice dan kontak yang Anda gunakan saat checkout untuk melihat status pengiriman.<br>
                        <span class="text-slate-400 text-xs mt-1 inline-block"><b class="text-slate-600">💡 Lupa Nomor Invoice atau Kontak?</b> Silakan cek email Invoice yang dikirimkan oleh Indoroster saat pesanan dibuat.</span>
                    </p>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-6 sm:p-8 shadow-sm">
                    @if (session()->has('error'))
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-xs rounded-xl font-semibold">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="track" class="space-y-5">
                        <div>
                            <label class="font-display block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor Invoice</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <span class="text-sm font-semibold">INV-</span>
                                </div>
                                <input type="text" wire:model="searchQuery" placeholder="20260521-0001" class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl text-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 font-mono font-bold uppercase">
                            </div>
                            @error('searchQuery') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="font-display block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email atau Nomor WA Penerima</label>
                            <input type="text" wire:model="contactQuery" placeholder="Contoh: budi@gmail.com / 0812..." class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200">
                            @error('contactQuery') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="font-display w-full flex justify-center items-center bg-slate-900 hover:bg-black text-white font-bold py-3.5 px-4 rounded-xl shadow-lg transition-all gap-2 cursor-pointer text-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <span>Lacak Sekarang</span>
                        </button>
                    </form>
                </div>
            </div>
        @else
            <!-- JIKA DETAIL PESANAN BERHASIL DITEMUKAN -->
            <div>
                <!-- Header Pelacakan -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                    <div>
                        <button wire:click="resetTracking" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors flex items-center gap-1 mb-2 uppercase tracking-wider">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            Kembali ke Pencarian
                        </button>
                        <h1 class="font-display text-fluid-h1 font-black text-slate-900 tracking-tight">Status Pesanan #{{ $order->order_number }}</h1>
                        <p class="text-slate-500 mt-1 text-sm">Pembaruan status secara real-time langsung dari sistem produksi dan armada pabrik.</p>
                    </div>

                    <!-- Badge Status -->
                    <div>
                        @php
                            $statusColors = match ($order->status) {
                                'pending_payment' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'paid' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'processing' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                'shipped' => 'bg-purple-50 text-purple-700 border-purple-200',
                                'delivered', 'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                default => 'bg-slate-50 text-slate-700 border-slate-200',
                            };
                        @endphp
                        <span class="font-display font-bold text-xs px-4 py-2 rounded-full border uppercase tracking-wider {{ $statusColors }}">
                            {{ $order->status_label }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    <!-- Linimasa Pelacakan (Kiri) -->
                    <div class="lg:col-span-2 space-y-6">
                        
                        <!-- Panel Animasi Truk Pengiriman Pabrik -->
                        @if(in_array($order->status, ['shipped', 'delivered', 'completed']))
                            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-6 text-white shadow-md relative overflow-hidden">
                                <div class="absolute -right-8 -bottom-8 opacity-10">
                                    <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                        <path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8h4l3 3v5a1 1 0 01-1 1h-1m-6 0h-2" />
                                    </svg>
                                </div>
                                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                    <div>
                                        <span class="text-[10px] font-bold tracking-widest text-terra-400 uppercase">Jaminan Armada Pabrik</span>
                                        <h3 class="font-display font-bold text-lg mt-1 flex items-center gap-2">
                                            <span>Roster Sedang Dikirim</span>
                                            <span class="animate-bounce">🚚</span>
                                        </h3>
                                        <p class="text-xs text-slate-300 mt-1 leading-relaxed">
                                            Roster beton premium dikirim menggunakan armada pabrik kami untuk menjamin barang selamat sampai tujuan tanpa pecah.
                                        </p>
                                    </div>
                                    
                                    @if($order->courier)
                                        <div class="bg-white/10 rounded-xl p-3 border border-white/10 text-xs shrink-0 w-full md:w-auto">
                                            <p class="text-white/60 font-semibold uppercase tracking-wider text-[9px]">Detail Sopir & Truk</p>
                                            <p class="font-bold mt-1 text-sm text-white">Sopir: {{ $order->courier }}</p>
                                            @if($order->tracking_number)
                                                <p class="text-white/80 mt-0.5">Plat Truk: <span class="bg-white text-slate-900 px-1.5 py-0.5 rounded font-mono font-bold">{{ $order->tracking_number }}</span></p>
                                            @endif
                                            @if($order->courier_phone)
                                                <div class="mt-2.5">
                                                    @php
                                                        $waPhone = preg_replace('/[^0-9]/', '', $order->courier_phone);
                                                        if (str_starts_with($waPhone, '0')) {
                                                            $waPhone = '62' . substr($waPhone, 1);
                                                        }
                                                        $waText = urlencode("Halo Pak {$order->courier}, saya penerima pesanan {$order->order_number}. Mau koordinasi lokasi pengantaran roster.");
                                                    @endphp
                                                    <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" class="inline-flex items-center gap-1 font-bold text-emerald-400 hover:text-emerald-300 transition-colors">
                                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                                        </svg>
                                                        Hubungi Sopir via WA
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Linimasa Status (Timeline) -->
                        <div class="bg-white rounded-2xl border border-slate-100 p-6 sm:p-8 shadow-sm">
                            <h3 class="font-display font-black text-slate-900 mb-8 pb-3 border-b border-slate-100 flex items-center gap-2">
                                <svg class="w-5 h-5 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Linimasa Pengiriman Roster
                            </h3>

                            @php
                                $status = $order->status;
                                $payStatus = $order->payment_status;
                                $isExpired = now()->diffInHours($order->created_at) >= 24;

                                // Step status check logic
                                $step1_done = in_array($status, ['paid', 'processing', 'shipped', 'delivered', 'completed']);
                                $step1_active = ($status === 'pending_payment' && $payStatus !== 'paid');

                                $step2_done = in_array($status, ['shipped', 'delivered', 'completed']);
                                $step2_active = in_array($status, ['paid', 'processing']);

                                $step3_done = in_array($status, ['completed']);
                                $step3_active = in_array($status, ['shipped', 'delivered']);

                                $step4_done = ($status === 'completed');
                                $step4_active = false; // completed is end node
                            @endphp

                            <div class="relative pl-6 border-l-2 border-slate-200 space-y-10 ml-3">
                                
                                <!-- Step 1: Pesanan Dibuat / Menunggu Pembayaran -->
                                <div class="relative">
                                    <!-- Indicator Node -->
                                    <div class="absolute -left-[37px] top-0.5 w-6 h-6 rounded-full flex items-center justify-center border-2 transition-all duration-300
                                        {{ $step1_done ? 'bg-emerald-500 border-emerald-500 text-white' : ($step1_active ? 'bg-white border-amber-500 text-amber-500 scale-110 shadow-lg shadow-amber-500/20' : 'bg-white border-slate-300 text-slate-400') }}">
                                        @if($step1_done)
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        @endif
                                    </div>
                                    <div class="pl-4">
                                        <div class="flex items-center justify-between gap-4 flex-wrap">
                                            <h4 class="font-display font-bold text-sm text-slate-800">
                                                @if($step1_done)
                                                    Pembayaran Diterima
                                                @else
                                                    Menunggu Pembayaran
                                                @endif
                                            </h4>
                                            <span class="text-[10px] font-semibold text-slate-400 font-mono">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                            @if($step1_done)
                                                Pembayaran berhasil dikonfirmasi secara otomatis via Midtrans.
                                                @if($order->paid_at)
                                                    (Terverifikasi pada {{ $order->paid_at->format('d M Y, H:i') }})
                                                @endif
                                            @else
                                                Silakan lakukan pembayaran sesuai instruksi Midtrans sebelum masa berlaku habis.
                                            @endif
                                        </p>
                                        
                                        @if(!$step1_done && $status === 'pending_payment')
                                            <div class="mt-3">
                                                @if(!$isExpired)
                                                    <button wire:click="payOrder" class="inline-flex items-center justify-center bg-slate-900 hover:bg-black text-white text-xs font-bold px-4 py-2 rounded-lg shadow-sm transition-all gap-1.5 cursor-pointer">
                                                        Lanjutkan Pembayaran
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                        </svg>
                                                    </button>
                                                @else
                                                    <span class="inline-flex bg-red-50 text-red-600 px-3 py-1.5 rounded-lg text-xs font-semibold border border-red-100">
                                                        Kedaluwarsa
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Step 2: Produksi / Penyiapan Roster -->
                                <div class="relative">
                                    <!-- Indicator Node -->
                                    <div class="absolute -left-[37px] top-0.5 w-6 h-6 rounded-full flex items-center justify-center border-2 transition-all duration-300
                                        {{ $step2_done ? 'bg-emerald-500 border-emerald-500 text-white' : ($step2_active ? 'bg-white border-blue-500 text-blue-500 scale-110 shadow-lg shadow-blue-500/20' : 'bg-white border-slate-200 text-slate-300') }}">
                                        @if($step2_done)
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        @endif
                                    </div>
                                    <div class="pl-4">
                                        <div class="flex items-center justify-between gap-4 flex-wrap">
                                            <h4 class="font-display font-bold text-sm text-slate-800">
                                                Roster Diproses di Pabrik
                                            </h4>
                                            @if($order->paid_at)
                                                <span class="text-[10px] font-semibold text-slate-400 font-mono">{{ $order->paid_at->format('d M Y, H:i') }} WIB</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                            @if($step2_done)
                                                Roster beton pesanan Anda telah dicetak, diperiksa kualitasnya, dan siap dikirim.
                                            @elseif($step2_active)
                                                Roster beton premium sedang dicetak atau disiapkan oleh tim produksi kami di Pabrik Plered, Purwakarta.
                                            @else
                                                Proses ini akan berjalan otomatis setelah pembayaran Anda terverifikasi.
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Step 3: Dalam Pengiriman Armada Pabrik -->
                                <div class="relative">
                                    <!-- Indicator Node -->
                                    <div class="absolute -left-[37px] top-0.5 w-6 h-6 rounded-full flex items-center justify-center border-2 transition-all duration-300
                                        {{ $step3_done ? 'bg-emerald-500 border-emerald-500 text-white' : ($step3_active ? 'bg-white border-purple-500 text-purple-500 scale-110 shadow-lg shadow-purple-500/20' : 'bg-white border-slate-200 text-slate-300') }}">
                                        @if($step3_done)
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        @endif
                                    </div>
                                    <div class="pl-4">
                                        <div class="flex items-center justify-between gap-4 flex-wrap">
                                            <h4 class="font-display font-bold text-sm text-slate-800">
                                                Dalam Pengiriman (Armada Pabrik)
                                            </h4>
                                            @if($order->shipped_at)
                                                <span class="text-[10px] font-semibold text-slate-400 font-mono">{{ $order->shipped_at->format('d M Y, H:i') }} WIB</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                            @if($step3_done)
                                                Pesanan telah dibawa dan dikirim menuju lokasi Anda oleh driver/kurir: <strong>{{ $order->courier ?? 'Armada Pabrik' }}</strong>.

                                            @elseif($step3_active)
                                                Pesanan telah dimuat ke truk armada pabrik. Driver sedang di perjalanan menuju alamat pengantaran Anda.
                                            @else
                                                Pesanan akan dimuat ke truk armada pabrik setelah produksi selesai.
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Step 4: Selesai -->
                                <div class="relative">
                                    <!-- Indicator Node -->
                                    <div class="absolute -left-[37px] top-0.5 w-6 h-6 rounded-full flex items-center justify-center border-2 transition-all duration-300
                                        {{ $step4_done ? 'bg-emerald-500 border-emerald-500 text-white' : ($status === 'completed' || $status === 'delivered' ? 'bg-white border-emerald-500 text-emerald-500 scale-110 shadow-lg shadow-emerald-500/20' : 'bg-white border-slate-200 text-slate-300') }}">
                                        @if($step4_done)
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        @endif
                                    </div>
                                    <div class="pl-4">
                                        <div class="flex items-center justify-between gap-4 flex-wrap">
                                            <h4 class="font-display font-bold text-sm text-slate-800">
                                                Pesanan Selesai
                                            </h4>
                                            @if($order->completed_at)
                                                <span class="text-[10px] font-semibold text-slate-400 font-mono">{{ $order->completed_at->format('d M Y, H:i') }} WIB</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                                            @if($step4_done)
                                                Pesanan telah selesai. Terima kasih telah mempercayai Indoroster sebagai penyedia roster beton premium Anda!
                                            @else
                                                Driver akan menyerahkan roster dan bukti serah terima kepada Anda setibanya di lokasi tujuan.
                                            @endif
                                        </p>
                                        @if($step4_done && $order->delivery_photo_path)
                                            <div class="mt-4 p-2.5 bg-slate-50 border border-slate-100 rounded-xl inline-block max-w-sm">
                                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                                    Bukti Pengiriman Kurir
                                                </p>
                                                <img src="{{ url('storage/' . $order->delivery_photo_path) }}" alt="Bukti Pengiriman" class="rounded-lg w-full h-auto object-cover shadow-sm">
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <!-- Detail Pesanan & Ringkasan Alamat (Kanan) -->
                    <div class="lg:col-span-1 space-y-6">
                        
                        <!-- Ringkasan Alamat Kirim -->
                        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                            <h3 class="font-display font-bold text-sm text-slate-900 uppercase tracking-wider mb-4 pb-2.5 border-b border-slate-50">Tujuan Pengiriman</h3>
                            
                            <div class="text-xs space-y-2">
                                <p class="font-bold text-slate-800 text-sm">{{ $order->shipping_name }}</p>
                                <p class="text-slate-500 font-semibold">{{ $order->shipping_phone }}</p>
                                <p class="text-slate-600 leading-relaxed">{{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</p>
                            </div>
                        </div>

                        <!-- Ringkasan Produk -->
                        <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm">
                            <h3 class="font-display font-bold text-sm text-slate-900 uppercase tracking-wider mb-4 pb-2.5 border-b border-slate-50">Produk Dipesan</h3>
                            
                            <div class="divide-y divide-slate-100">
                                @foreach($order->items as $item)
                                    <div class="py-3 first:pt-0 last:pb-0 flex items-start gap-3">
                                        <!-- Image -->
                                        <div class="w-12 h-12 bg-slate-100 rounded-lg shrink-0 overflow-hidden flex items-center justify-center border border-slate-200/50">
                                            @if($item->product && $item->product->primary_image)
                                                <img src="{{ $item->product->primary_image }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0 text-xs">
                                            <p class="font-bold text-slate-800 leading-tight truncate">{{ $item->product_name }}</p>
                                            @if($item->product_variant_name)
                                                <p class="text-slate-400 mt-0.5">Varian: {{ $item->product_variant_name }}</p>
                                            @endif
                                            <p class="text-slate-500 mt-1">Jumlah: {{ $item->quantity }} pcs</p>
                                        </div>
                                        <span class="text-xs font-bold text-slate-800 whitespace-nowrap">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t border-slate-100 mt-4 pt-4 space-y-2 text-xs">
                                <div class="flex justify-between text-slate-500">
                                    <span>Subtotal</span>
                                    <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-slate-500">
                                    <span>Ongkos Kirim</span>
                                    <span>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                </div>
                                @if($order->discount_amount > 0)
                                    <div class="flex justify-between text-red-500">
                                        <span>Diskon</span>
                                        <span>-Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <div class="font-display flex justify-between text-sm font-black text-terra-600 border-t border-dashed border-slate-200 pt-3">
                                    <span>Total Dibayar</span>
                                    <span>Rp{{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            @if($order->status === 'pending_payment' && $order->payment_status !== 'paid')
                                <div class="mt-4 pt-4 border-t border-slate-100">
                                    @if(!$isExpired)
                                        <button wire:click="payOrder" class="w-full inline-flex items-center justify-center bg-terra-600 hover:bg-terra-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm shadow-terra-500/30 transition-all gap-1.5 cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Lanjutkan Pembayaran
                                        </button>
                                        <p class="text-[10px] text-center text-slate-400 mt-2">Batas waktu: {{ $order->created_at->addDay()->format('d M Y, H:i') }}</p>
                                    @else
                                        <div class="bg-red-50 text-red-600 p-3 rounded-xl text-center text-xs font-semibold border border-red-100">
                                            Waktu pembayaran telah habis (Lebih dari 24 Jam)
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if($order->invoice)
                                <div class="mt-4 pt-4 border-t border-slate-100">
                                    <a href="{{ URL::signedRoute('print.invoice', ['invoice' => $order->invoice->id]) }}" target="_blank" class="w-full inline-flex items-center justify-center bg-slate-900 hover:bg-black text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all gap-1.5 cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Cetak Invoice
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        @endif

    </div>

    <!-- Script to Handle Midtrans Popup -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('snap-pay', (data) => {
                const token = data.token;
                const order_id = data.order_id;
                
                const redirectToVerification = () => {
                    window.location.href = '/checkout/success?order_id=' + order_id;
                };

                snap.pay(token, {
                    onSuccess: function(result) {
                        redirectToVerification();
                    },
                    onPending: function(result) {
                        redirectToVerification();
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal! Silakan coba lagi.');
                    },
                    onClose: function() {
                        redirectToVerification();
                    }
                });
            });
        });
    </script>
</div>
