<div class="bg-slate-50 dark:bg-slate-950 min-h-screen py-12">
    <!-- Midtrans Snap JS -->
    @if(config('midtrans.is_production'))
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-breadcrumb :items="[['label' => 'Lacak Pesanan']]" class="!px-0 !py-0 mb-8" />
        
        <!-- JIKA BELUM MELAKUKAN PENCARIAN ATAU TIDAK DITEMUKAN -->
        @if(!$searched || !$order)
            <div class="max-w-md mx-auto">
                <div class="text-center mb-8">
                    <div class="inline-flex p-3.5 bg-terra-50 dark:bg-terra-500/20 text-terra-600 dark:text-terra-400 rounded-2xl mb-4 border border-terra-100 dark:border-terra-500/30">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h1 class="font-display text-fluid-h1 font-black text-slate-900 dark:text-white tracking-tight">Lacak Pesanan Anda</h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm leading-relaxed">
                        Masukkan nomor pesanan dan kontak yang Anda gunakan saat checkout untuk melihat status pengiriman.<br>
                        <span class="text-slate-400 dark:text-slate-500 text-xs mt-1 inline-block"><b class="text-slate-600 dark:text-slate-300">💡 Lupa Nomor Pesanan atau Kontak?</b> Silakan cek email konfirmasi yang dikirimkan oleh Indoroster saat pesanan dibuat.</span>
                    </p>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-8 shadow-soft-xs">
                    @if (session()->has('error'))
                        <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-xs rounded-xl font-semibold">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="track" class="space-y-5">
                        <div>
                            <label class="font-display block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Nomor Pesanan</label>
                            <div class="relative">
                                <input type="text" wire:model="searchQuery" placeholder="Contoh: INV-20260821-0001" class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-800 rounded-xl text-sm text-slate-900 dark:text-white focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 font-mono font-bold uppercase">
                            </div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5">📋 Nomor pesanan ada di email konfirmasi Anda (format: <span class="font-mono font-semibold text-slate-500 dark:text-slate-400">INV-YYYYMMDD-XXXX</span>)</p>
                            @error('searchQuery') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="font-display block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Email atau Nomor WA Penerima</label>
                            <input type="text" wire:model="contactQuery" placeholder="Contoh: budi@gmail.com / 0812..." class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-800 rounded-xl text-sm text-slate-900 dark:text-white focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200">
                            @error('contactQuery') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="font-display w-full flex justify-center items-center bg-slate-900 dark:bg-terra-500 hover:bg-black dark:hover:bg-terra-600 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg transition-all gap-2 cursor-pointer text-sm">
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
                        <button wire:click="resetTracking" class="text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-white transition-colors flex items-center gap-1 mb-2 uppercase tracking-wider cursor-pointer">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                            Kembali ke Pencarian
                        </button>
                        <h1 class="font-display text-fluid-h1 font-black text-slate-900 dark:text-white tracking-tight">Status Pesanan #{{ $order->order_number }}</h1>
                        <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">Pembaruan status secara real-time langsung dari sistem produksi dan armada pabrik.</p>
                    </div>

                    <!-- Badge Status -->
                    <div>
                        @php
                            $statusColors = match ($order->status) {
                                'pending_payment' => 'bg-amber-50 dark:bg-amber-950/40 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                'paid' => 'bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                'processing' => 'bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800',
                                'shipped' => 'bg-purple-50 dark:bg-purple-950/40 text-purple-700 dark:text-purple-300 border-purple-200 dark:border-purple-800',
                                'delivered', 'completed' => 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                'cancelled' => 'bg-rose-50 dark:bg-rose-950/40 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800',
                                default => 'bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700',
                            };
                        @endphp
                        <span class="font-display font-bold text-xs px-4 py-2 rounded-full border uppercase tracking-wider {{ $statusColors }}">
                            {{ $order->status_label }}
                        </span>
                    </div>
                </div>

                <!-- BANNER AKSI WHATSAPP JIKA PESANAN VIA WA & MENUNGGU KONFIRMASI -->
                @if($order->order_source === 'whatsapp' && ($order->status === 'pending_payment' || $order->status === 'draft'))
                    <div class="mb-8 p-6 sm:p-7 bg-gradient-to-br from-emerald-600 via-emerald-700 to-teal-800 text-white rounded-3xl shadow-xl shadow-emerald-950/20 border-2 border-emerald-400/80 relative overflow-hidden">
                        <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6 relative z-10">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center shrink-0 text-2xl sm:text-3xl font-bold shadow-inner border border-white/20">
                                    💬
                                </div>
                                <div>
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/40 border border-emerald-300/40 rounded-full text-[11px] font-black uppercase tracking-wider mb-2 text-emerald-100">
                                        <span class="w-2 h-2 rounded-full bg-emerald-300 animate-ping"></span>
                                        <span>Penting: Konfirmasi WhatsApp Admin</span>
                                    </div>
                                    <h2 class="text-lg sm:text-xl font-black font-display tracking-tight text-white">
                                        Kirimkan Rincian Pesanan ke WhatsApp Admin
                                    </h2>
                                    <p class="text-emerald-100 text-xs sm:text-sm mt-1 max-w-xl leading-relaxed">
                                        Pesanan <strong>#{{ $order->order_number }}</strong> telah berhasil dicatat. Silakan klik tombol di samping untuk membuka WhatsApp Admin dengan template format pesanan lengkap yang sudah terisi otomatis.
                                    </p>
                                </div>
                            </div>
                            <div class="shrink-0 w-full md:w-auto">
                                <a href="{{ $order->getBuyerToAdminWaOrderLink() }}" target="_blank" rel="noopener noreferrer" class="w-full md:w-auto inline-flex items-center justify-center gap-3 bg-white hover:bg-emerald-50 text-emerald-800 font-display font-black text-sm sm:text-base px-6 py-4 rounded-2xl shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-0.5 active:translate-y-0 border border-white/60 cursor-pointer">
                                    <svg class="w-5 h-5 fill-current text-emerald-600" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    <span>Kirim Pesanan ke WhatsApp</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    <!-- Linimasa Pelacakan (Kiri) -->
                    <div class="lg:col-span-2 space-y-6">
                        
                        <!-- PETA RUTE LOGISTIK & ANIMASI PENGIRIMAN PABRIK -->
                        <div wire:ignore class="bg-gradient-to-br from-slate-900 via-slate-900 to-slate-800 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-slate-800"
                             x-data="orderTrackingMapHandler({
                                 originLat: -6.6689917,
                                 originLng: 107.3619295,
                                 destLat: {{ $destCoords['lat'] }},
                                 destLng: {{ $destCoords['lng'] }},
                                 destCity: '{{ addslashes($order->shipping_city ?? 'Lokasi Pembeli') }}',
                                 status: '{{ $order->status }}'
                             })"
                             x-init="initMap()">

                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4 pb-4 border-b border-slate-800">
                                <div>
                                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-terra-500/20 border border-terra-500/30 text-terra-400 text-xs font-bold uppercase tracking-wider mb-2">
                                        <span class="w-2 h-2 rounded-full bg-terra-400 animate-ping"></span>
                                        <span>Live Tracking Rute Pengiriman</span>
                                    </div>
                                    <h3 class="font-display font-black text-xl text-white flex items-center gap-2">
                                        <span>Purwakarta</span>
                                        <span class="text-terra-400">➔</span>
                                        <span>{{ $order->shipping_city ?? 'Lokasi Proyek' }}</span>
                                    </h3>
                                    <p class="text-xs text-slate-400 mt-1">
                                        Dikirim langsung dari pabrik utama Indoroster Purwakarta ke titik koordinat penerima.
                                    </p>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="https://www.google.com/maps/dir/?api=1&origin=-6.6689917,107.3619295&destination={{ $destCoords['lat'] }},{{ $destCoords['lng'] }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-terra-500 hover:bg-terra-600 text-white font-bold text-xs rounded-xl shadow-lg transition-all">
                                        <span>🗺️ Buka Google Maps</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Map Canvas Container -->
                            <div id="tracking-live-map" class="w-full h-72 sm:h-80 rounded-2xl overflow-hidden border border-slate-700/80 shadow-inner z-0 mb-4"></div>

                            <!-- Milestone Progress Bar -->
                            <div class="grid grid-cols-3 gap-2 sm:gap-4 pt-2">
                                <div class="bg-slate-800/80 p-3 rounded-xl border border-slate-700/60 text-center">
                                    <div class="text-lg">🏭</div>
                                    <div class="text-[11px] font-bold text-slate-200 mt-1">Pabrik Purwakarta</div>
                                    <div class="text-[9px] text-slate-400 uppercase tracking-wider">Muat Material</div>
                                </div>
                                <div class="p-3 rounded-xl border text-center {{ in_array($order->status, ['shipped', 'delivered', 'completed']) ? 'bg-amber-500/20 border-amber-500/40 text-amber-300' : 'bg-slate-800/80 border-slate-700/60 text-slate-400' }}">
                                    <div class="text-lg {{ $order->status === 'shipped' ? 'animate-bounce' : '' }}">🚚</div>
                                    <div class="text-[11px] font-bold mt-1">Dalam Perjalanan</div>
                                    <div class="text-[9px] uppercase tracking-wider">{{ $order->status === 'shipped' ? 'Sedang Bergerak' : 'Armada Standby' }}</div>
                                </div>
                                <div class="p-3 rounded-xl border text-center {{ in_array($order->status, ['delivered', 'completed']) ? 'bg-emerald-500/20 border-emerald-500/40 text-emerald-300' : 'bg-slate-800/80 border-slate-700/60 text-slate-400' }}">
                                    <div class="text-lg">📍</div>
                                    <div class="text-[11px] font-bold mt-1">Tiba di Lokasi</div>
                                    <div class="text-[9px] uppercase tracking-wider">{{ in_array($order->status, ['delivered', 'completed']) ? 'Selesai Dibongkar' : 'Menunggu Tiba' }}</div>
                                </div>
                            </div>

                            @if($order->courier)
                                <div class="mt-4 pt-4 border-t border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 text-xs text-slate-300">
                                    <div class="flex items-center gap-3">
                                        <span class="text-slate-400">Sopir: <strong class="text-white">{{ $order->courier }}</strong></span>
                                        @if($order->tracking_number)
                                            <span class="text-slate-400">Plat Truk: <strong class="bg-white text-slate-900 px-1.5 py-0.5 rounded font-mono font-bold">{{ $order->tracking_number }}</strong></span>
                                        @endif
                                    </div>
                                    @if($order->courier_phone)
                                        @php
                                            $waPhone = preg_replace('/[^0-9]/', '', $order->courier_phone);
                                            if (str_starts_with($waPhone, '0')) {
                                                $waPhone = '62' . substr($waPhone, 1);
                                            }
                                            $waText = urlencode("Halo Pak {$order->courier}, saya penerima pesanan {$order->order_number}. Mau koordinasi posisi armada pengiriman.");
                                        @endphp
                                        <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" class="inline-flex items-center gap-1.5 text-emerald-400 hover:text-emerald-300 font-bold">
                                            <span>💬 Hubungi Sopir via WhatsApp</span>
                                        </a>
                                    @endif
                                </div>
                            @else
                                <div class="mt-4 pt-4 border-t border-slate-800 flex items-center gap-2 text-xs text-slate-400">
                                    <span class="text-base shrink-0">ℹ️</span>
                                    <span><strong>Pengiriman Armada Eksternal / Kargo:</strong> Pesanan dikirim via ekspedisi atau supir logistik rekanan. Pembaruan posisi mengikuti milestone status berkala dari sistem gudang pabrik.</span>
                                </div>
                            @endif
                        </div>

                        @if($order->is_batch_order && $order->batches->count() > 0)
                        <!-- PROGRES PENGIRIMAN PROYEK BERTAHAP (PO BATCH) -->
                        <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden border border-slate-700/60">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-white/10 pb-6 mb-6">
                                <div>
                                    <span class="inline-flex items-center gap-1.5 bg-amber-500/20 text-amber-300 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border border-amber-500/30">
                                        🚚 Pre-Order Proyek Bertahap ({{ $order->batch_count }} Batch)
                                    </span>
                                    <h3 class="font-display font-black text-xl sm:text-2xl mt-2 text-white">
                                        Progres Pengiriman Proyek
                                    </h3>
                                    <p class="text-xs sm:text-sm text-slate-300 mt-1">
                                        Total Pesanan: <strong class="text-white">{{ number_format($order->total_ordered_quantity, 0, ',', '.') }} pcs</strong> roster beton.
                                    </p>
                                </div>

                                <div class="text-left sm:text-right">
                                    <div class="text-3xl font-black font-display text-amber-400">
                                        {{ $order->batch_progress_percentage }}%
                                    </div>
                                    <span class="text-[11px] text-slate-400 font-medium">Terkirim ke Lokasi</span>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="w-full bg-slate-700/80 rounded-full h-4 p-0.5 mb-6 overflow-hidden">
                                <div class="bg-gradient-to-r from-amber-500 to-orange-500 h-full rounded-full transition-all duration-700 shadow-sm" style="width: {{ max(5, $order->batch_progress_percentage) }}%"></div>
                            </div>

                            <!-- 3 Mini Stats -->
                            <div class="grid grid-cols-3 gap-3 sm:gap-4 text-center">
                                <div class="bg-white/5 rounded-2xl p-3.5 border border-white/5">
                                    <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block mb-1">Total Order</span>
                                    <strong class="text-sm sm:text-base font-bold text-white">{{ number_format($order->total_ordered_quantity, 0, ',', '.') }}</strong> <span class="text-[11px] text-slate-400">pcs</span>
                                </div>
                                <div class="bg-emerald-500/10 rounded-2xl p-3.5 border border-emerald-500/20">
                                    <span class="text-[10px] text-emerald-400 uppercase font-bold tracking-wider block mb-1">Sudah Terkirim</span>
                                    <strong class="text-sm sm:text-base font-bold text-emerald-300">{{ number_format($order->total_shipped_quantity, 0, ',', '.') }}</strong> <span class="text-[11px] text-emerald-400/80">pcs</span>
                                </div>
                                <div class="bg-amber-500/10 rounded-2xl p-3.5 border border-amber-500/20">
                                    <span class="text-[10px] text-amber-400 uppercase font-bold tracking-wider block mb-1">Sisa Belum Kirim</span>
                                    <strong class="text-sm sm:text-base font-bold text-amber-300">{{ number_format($order->remaining_quantity, 0, ',', '.') }}</strong> <span class="text-[11px] text-amber-400/80">pcs</span>
                                </div>
                            </div>

                            <!-- Batch Schedule Accordion / Table -->
                            <div class="mt-8 pt-6 border-t border-white/10">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-300 mb-4 flex items-center justify-between">
                                    <span>📅 Rincian Jadwal & Status Setiap Batch:</span>
                                    <span class="text-[11px] text-slate-400 lowercase font-normal">*estimasi pabrik</span>
                                </h4>

                                <div class="space-y-3">
                                    @foreach($order->batches as $batch)
                                    <div class="bg-white/5 hover:bg-white/10 transition-colors rounded-2xl p-4 border border-white/5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl flex items-center justify-center font-bold text-xs shrink-0
                                                {{ in_array($batch->status, ['shipped', 'delivered']) ? 'bg-emerald-500 text-white' : ($batch->status === 'producing' ? 'bg-amber-500 text-white' : 'bg-slate-700 text-slate-300') }}">
                                                #{{ $batch->batch_number }}
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <strong class="text-sm text-white font-display">{{ $batch->batch_name }}</strong>
                                                    <span class="text-xs text-amber-300 font-bold">({{ number_format($batch->quantity, 0, ',', '.') }} pcs)</span>
                                                </div>
                                                <p class="text-[11px] text-slate-400 mt-0.5">
                                                    Est. Berangkat: <strong>{{ $batch->estimated_dispatch_date ? $batch->estimated_dispatch_date->format('d M Y') : '-' }}</strong> 
                                                    (Tiba: {{ $batch->estimated_delivery_date ? $batch->estimated_delivery_date->format('d M Y') : '-' }})
                                                </p>
                                                @if($batch->courier_name || $batch->tracking_number)
                                                <p class="text-[11px] text-slate-300 mt-1 flex items-center gap-1.5">
                                                    <span>🚚 Supir: <strong>{{ $batch->courier_name ?: '-' }}</strong></span>
                                                    @if($batch->tracking_number)
                                                    <span class="bg-slate-700 text-white px-1.5 py-0.5 rounded font-mono font-bold">{{ $batch->tracking_number }}</span>
                                                    @endif
                                                </p>
                                                @endif
                                                @if($batch->delivery_photo_path)
                                                <div class="mt-2.5">
                                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">📸 Foto Bukti Pengiriman (Bongkar Muat):</p>
                                                    <a href="{{ asset('storage/' . $batch->delivery_photo_path) }}" target="_blank" class="inline-block group relative">
                                                        <img src="{{ asset('storage/' . $batch->delivery_photo_path) }}" class="max-h-24 sm:max-h-28 rounded-xl border border-slate-700 hover:border-amber-500 transition-colors shadow-md object-cover" alt="Bukti Pengiriman">
                                                        <span class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-[10px] text-white font-bold rounded-xl">🔍 Lihat</span>
                                                    </a>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
                                            <span class="text-xs px-3 py-1 rounded-full font-bold
                                                {{ match($batch->status) {
                                                    'shipped' => 'bg-blue-500/20 text-blue-300 border border-blue-500/30',
                                                    'delivered' => 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30',
                                                    'producing' => 'bg-amber-500/20 text-amber-300 border border-amber-500/30',
                                                    'ready_to_ship' => 'bg-purple-500/20 text-purple-300 border border-purple-500/30',
                                                    default => 'bg-slate-700 text-slate-400',
                                                } }}">
                                                {{ $batch->status_label }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Linimasa Status (Timeline) -->
                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-8 shadow-soft-xs">
                            <h3 class="font-display font-black text-slate-900 dark:text-white mb-8 pb-3 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Linimasa Status Pesanan
                            </h3>

                            @php
                                $status = $order->status;
                                $payStatus = $order->payment_status;
                                $prodStatus = $order->production_status ?? 'pending';
                                $isExpired = now()->diffInHours($order->created_at) >= 24;

                                // Step status check logic
                                $step1_done = in_array($status, ['paid', 'processing', 'shipped', 'completed']);
                                $step1_active = ($status === 'pending_payment' && $payStatus !== 'paid');

                                $step2_done = in_array($prodStatus, ['ready_to_ship', 'shipped', 'delivered']) || in_array($status, ['shipped', 'completed']);
                                $step2_active = ($status === 'processing' && in_array($prodStatus, ['pending', 'producing']));

                                $step3_done = in_array($prodStatus, ['delivered']) || ($status === 'completed');
                                $step3_active = ($status === 'shipped');

                                $step4_done = ($status === 'completed');
                                $step4_active = false; // completed is end node
                            @endphp

                            <div class="relative pl-6 border-l-2 border-slate-200 dark:border-slate-800 space-y-10 ml-3">
                                
                                <!-- Step 1: Pesanan Dibuat / Menunggu Pembayaran -->
                                <div class="relative">
                                    <!-- Indicator Node -->
                                    <div class="absolute -left-[37px] top-0.5 w-6 h-6 rounded-full flex items-center justify-center border-2 transition-all duration-300
                                        {{ $step1_done ? 'bg-emerald-500 border-emerald-500 text-white' : ($step1_active ? 'bg-white dark:bg-slate-900 border-amber-500 text-amber-500 scale-110 shadow-lg shadow-amber-500/20' : 'bg-white dark:bg-slate-900 border-slate-300 dark:border-slate-700 text-slate-400') }}">
                                        @if($step1_done)
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        @endif
                                    </div>
                                    <div class="pl-4">
                                        <div class="flex items-center justify-between gap-4 flex-wrap">
                                            <h4 class="font-display font-bold text-sm text-slate-800 dark:text-white">
                                                @if($step1_done)
                                                    Pembayaran Diterima
                                                @else
                                                    Menunggu Pembayaran
                                                @endif
                                            </h4>
                                            <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 font-mono">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                                        </div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                                            @if($step1_done)
                                                @if($order->order_source === 'whatsapp')
                                                    Pembayaran transfer telah diverifikasi & dicatat oleh Admin IndoRoster.
                                                @else
                                                    Pembayaran berhasil dikonfirmasi secara otomatis via Midtrans.
                                                @endif
                                                @if($order->paid_at)
                                                    (Terverifikasi pada {{ $order->paid_at->format('d M Y, H:i') }})
                                                @endif
                                            @else
                                                @if($order->order_source === 'whatsapp')
                                                    Pesanan tercatat di pabrik dan menunggu transfer pembayaran / DP. Silakan konfirmasi bukti transfer via WhatsApp Admin.
                                                @else
                                                    Pesanan berhasil dibuat dan menunggu pembayaran Anda.
                                                @endif
                                            @endif
                                        </p>
                                        
                                        @if(!$step1_done && $status === 'pending_payment')
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @if($order->order_source === 'whatsapp')
                                                    @php
                                                        $rawWa = \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
                                                        $waPhone = preg_replace('/[^0-9]/', '', (string) $rawWa);
                                                        if (str_starts_with($waPhone, '0')) {
                                                            $waPhone = '62'.substr($waPhone, 1);
                                                        } elseif (str_starts_with($waPhone, '8')) {
                                                            $waPhone = '62'.$waPhone;
                                                        }
                                                        $waMsg = "Halo Admin IndoRoster, saya ingin konfirmasi pembayaran untuk pesanan #{$order->order_number} sebesar Rp" . number_format($order->grand_total, 0, ',', '.') . ".";
                                                        $waConfirmUrl = "https://wa.me/{$waPhone}?text=" . rawurlencode($waMsg);
                                                    @endphp
                                                    <a href="{{ $waConfirmUrl }}" target="_blank" class="inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-lg shadow-sm transition-all gap-1.5 cursor-pointer">
                                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                                        <span>Konfirmasi Transfer via WA</span>
                                                    </a>
                                                @else
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
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Step 2: Produksi / Penyiapan Roster -->
                                <div class="relative">
                                    <!-- Indicator Node -->
                                    <div class="absolute -left-[37px] top-0.5 w-6 h-6 rounded-full flex items-center justify-center border-2 transition-all duration-300
                                        {{ $step2_done ? 'bg-emerald-500 border-emerald-500 text-white' : ($step2_active ? 'bg-white dark:bg-slate-900 border-indigo-500 text-indigo-500 scale-110 shadow-lg shadow-indigo-500/20' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-300 dark:text-slate-600') }}">
                                        @if($step2_done)
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        @endif
                                    </div>
                                    <div class="pl-4">
                                        <div class="flex items-center justify-between gap-4 flex-wrap">
                                            <h4 class="font-display font-bold text-sm text-slate-800 dark:text-white">
                                                @if($order->fulfillment_type === 'po_single')
                                                    Produksi Roster Cetak (Pabrik Plered)
                                                @else
                                                    Penyiapan Material di Gudang
                                                @endif
                                            </h4>
                                            @if($order->processed_at)
                                                <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 font-mono">{{ $order->processed_at->format('d M Y, H:i') }} WIB</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                                            @if($order->fulfillment_type === 'po_single')
                                                @if($step2_done)
                                                    Roster beton pesanan PO Anda telah selesai dicetak, diperiksa kualitasnya, dan siap dikirim.
                                                @elseif($prodStatus === 'producing')
                                                    Roster beton pesanan Anda sedang dicetak oleh tim produksi di Pabrik Plered, Purwakarta.
                                                @else
                                                    Pesanan masuk antrean pengerjaan cetakan di Pabrik.
                                                @endif
                                            @else
                                                @if($step2_done)
                                                    Roster beton pesanan Anda telah disiapkan dari stok gudang dan siap dikirim.
                                                @elseif($step2_active)
                                                    Roster beton pesanan Anda sedang disiapkan dan dikemas oleh tim gudang.
                                                @else
                                                    Proses ini akan berjalan otomatis setelah pembayaran Anda terverifikasi.
                                                @endif
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Step 3: Dalam Pengiriman Armada Pabrik -->
                                <div class="relative">
                                    <!-- Indicator Node -->
                                    <div class="absolute -left-[37px] top-0.5 w-6 h-6 rounded-full flex items-center justify-center border-2 transition-all duration-300
                                        {{ $step3_done ? 'bg-emerald-500 border-emerald-500 text-white' : ($step3_active ? 'bg-white dark:bg-slate-900 border-purple-500 text-purple-500 scale-110 shadow-lg shadow-purple-500/20' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-300 dark:text-slate-600') }}">
                                        @if($step3_done)
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        @endif
                                    </div>
                                    <div class="pl-4">
                                        <div class="flex items-center justify-between gap-4 flex-wrap">
                                            <h4 class="font-display font-bold text-sm text-slate-800 dark:text-white">
                                                Dalam Pengiriman (Armada Pabrik)
                                            </h4>
                                            @if($order->shipped_at)
                                                <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 font-mono">{{ $order->shipped_at->format('d M Y, H:i') }} WIB</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
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
                                        {{ $step4_done ? 'bg-emerald-500 border-emerald-500 text-white' : ($status === 'completed' || $status === 'delivered' ? 'bg-white dark:bg-slate-900 border-emerald-500 text-emerald-500 scale-110 shadow-lg shadow-emerald-500/20' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 text-slate-300 dark:text-slate-600') }}">
                                        @if($step4_done)
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        @endif
                                    </div>
                                    <div class="pl-4">
                                        <div class="flex items-center justify-between gap-4 flex-wrap">
                                            <h4 class="font-display font-bold text-sm text-slate-800 dark:text-white">
                                                Pesanan Selesai
                                            </h4>
                                            @if($order->completed_at)
                                                <span class="text-[10px] font-semibold text-slate-400 dark:text-slate-500 font-mono">{{ $order->completed_at->format('d M Y, H:i') }} WIB</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">
                                            @if($step4_done)
                                                Pesanan telah selesai. Terima kasih telah mempercayai Indoroster sebagai penyedia roster beton premium Anda!
                                            @else
                                                Driver akan menyerahkan roster dan bukti serah terima kepada Anda setibanya di lokasi tujuan.
                                            @endif
                                        </p>
                                        @if($step4_done && $order->delivery_photo_path)
                                            <div class="mt-4 p-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-xl inline-block max-w-sm">
                                                <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
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
                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-soft-xs">
                            <h3 class="font-display font-bold text-sm text-slate-900 dark:text-white uppercase tracking-wider mb-4 pb-2.5 border-b border-slate-100 dark:border-slate-800">Tujuan Pengiriman</h3>
                            
                            <div class="text-xs space-y-2">
                                <p class="font-bold text-slate-800 dark:text-white text-sm">{{ $order->shipping_name }}</p>
                                <p class="text-slate-500 dark:text-slate-400 font-semibold">{{ $order->shipping_phone }}</p>
                                <p class="text-slate-600 dark:text-slate-300 leading-relaxed">{{ $order->shipping_address }}, {{ $order->shipping_city }}, {{ $order->shipping_province }} {{ $order->shipping_postal_code }}</p>
                            </div>
                        </div>

                        <!-- Ringkasan Produk -->
                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 p-6 shadow-soft-xs">
                            <h3 class="font-display font-bold text-sm text-slate-900 dark:text-white uppercase tracking-wider mb-4 pb-2.5 border-b border-slate-100 dark:border-slate-800">Produk Dipesan</h3>
                            
                            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                                @foreach($order->items as $item)
                                    <div class="py-3 first:pt-0 last:pb-0 flex items-start gap-3">
                                        <!-- Image -->
                                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-800 rounded-lg shrink-0 overflow-hidden flex items-center justify-center border border-slate-200/50 dark:border-slate-700">
                                            @if($item->product && $item->product->primary_image)
                                                <img src="{{ $item->product->primary_image }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0 text-xs">
                                            <p class="font-bold text-slate-800 dark:text-white leading-tight truncate">{{ $item->product_name }}</p>
                                            @if($item->product_variant_name)
                                                <p class="text-slate-400 dark:text-slate-500 mt-0.5">Varian: {{ $item->product_variant_name }}</p>
                                            @endif
                                            <p class="text-slate-500 dark:text-slate-400 mt-1">Jumlah: {{ $item->quantity }} pcs</p>
                                        </div>
                                        <span class="text-xs font-bold text-slate-800 dark:text-white whitespace-nowrap">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t border-slate-100 dark:border-slate-800 mt-4 pt-4 space-y-2 text-xs">
                                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                                    <span>Subtotal</span>
                                    <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-slate-500 dark:text-slate-400">
                                    <span>Ongkos Kirim</span>
                                    <span>Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                                </div>
                                @if($order->discount_amount > 0)
                                    <div class="flex justify-between text-red-500 dark:text-red-400">
                                        <span>Diskon</span>
                                        <span>-Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <div class="font-display flex justify-between text-sm font-black text-terra-600 dark:text-terra-400 border-t border-dashed border-slate-200 dark:border-slate-800 pt-3">
                                    <span>Total Dibayar</span>
                                    <span>Rp{{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            @if($order->status === 'pending_payment' && $order->payment_status !== 'paid')
                                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                                    @if($order->order_source === 'whatsapp')
                                        @php
                                            $rawWa = \App\Models\SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
                                            $waPhone = preg_replace('/[^0-9]/', '', (string) $rawWa);
                                            if (str_starts_with($waPhone, '0')) {
                                                $waPhone = '62'.substr($waPhone, 1);
                                            } elseif (str_starts_with($waPhone, '8')) {
                                                $waPhone = '62'.$waPhone;
                                            }
                                            $waMsg = "Halo Admin IndoRoster, saya ingin konfirmasi pembayaran untuk pesanan #{$order->order_number} sebesar Rp" . number_format($order->grand_total, 0, ',', '.') . ".";
                                            $waConfirmUrl = "https://wa.me/{$waPhone}?text=" . rawurlencode($waMsg);
                                        @endphp
                                        <div class="p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-xl mb-3 text-xs text-emerald-800 dark:text-emerald-300">
                                            <p class="font-bold mb-1">🏦 Menunggu Transfer Bank</p>
                                            <p class="text-[11px] leading-relaxed">Silakan transfer dan kirimkan bukti bayar ke WhatsApp Admin agar status pesanan diverifikasi.</p>
                                        </div>
                                        <a href="{{ $waConfirmUrl }}" target="_blank" class="w-full inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm shadow-emerald-600/30 transition-all gap-1.5 cursor-pointer">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                            <span>Konfirmasi Bayar ke WhatsApp</span>
                                        </a>
                                    @else
                                        @if(!$isExpired)
                                            <button wire:click="payOrder" class="w-full inline-flex items-center justify-center bg-terra-600 hover:bg-terra-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm shadow-terra-500/30 transition-all gap-1.5 cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                Lanjutkan Pembayaran
                                            </button>
                                            <p class="text-[10px] text-center text-slate-400 dark:text-slate-500 mt-2">Batas waktu: {{ $order->created_at->addDay()->format('d M Y, H:i') }}</p>
                                        @else
                                            <div class="bg-red-50 dark:bg-red-950/40 text-red-600 dark:text-red-400 p-3 rounded-xl text-center text-xs font-semibold border border-red-100 dark:border-red-900/40">
                                                Waktu pembayaran telah habis (Lebih dari 24 Jam)
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endif

                            @php
                                $invoiceRecord = $order->invoice ?: \App\Models\Invoice::firstOrCreate(
                                    ['order_id' => $order->id],
                                    [
                                        'invoice_number' => \App\Models\Invoice::generateWaInvoiceNumber(),
                                        'invoice_date' => now(),
                                        'subtotal' => $order->subtotal,
                                        'shipping_cost' => $order->shipping_cost,
                                        'discount_amount' => $order->discount_amount,
                                        'grand_total' => $order->grand_total,
                                        'payment_scheme' => $order->payment_scheme ?: 'full',
                                        'down_payment_amount' => $order->down_payment_amount ?: 0,
                                        'remaining_balance' => $order->remaining_balance ?: 0,
                                        'status' => $order->payment_status === 'paid' ? 'paid' : 'sent',
                                        'paid_at' => $order->payment_status === 'paid' ? now() : null,
                                    ]
                                );
                            @endphp
                            @if($invoiceRecord)
                                <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                                    <a href="{{ URL::signedRoute('print.invoice', ['invoice' => $invoiceRecord->id]) }}" target="_blank" class="w-full inline-flex items-center justify-center bg-slate-900 dark:bg-slate-800 hover:bg-black dark:hover:bg-slate-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all gap-1.5 cursor-pointer border border-slate-700">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        {{ $order->payment_status === 'paid' ? '📄 Cetak Invoice Lunas' : '📄 Cetak Surat Penawaran & Tagihan' }}
                                    </a>
                                </div>
                            @endif

                            @php
                                $validPayments = $order->getValidPayments();
                            @endphp
                            @if($validPayments->isNotEmpty())
                                <div class="mt-3 flex flex-col gap-2">
                                    @foreach($validPayments as $idx => $payment)
                                        @php
                                            $payTitle = $payment->installment_title;
                                            if (empty($payTitle) || $payTitle === 'Pembayaran #'.$payment->id) {
                                                if ($validPayments->count() === 1) {
                                                    $payTitle = ($order->payment_status === 'paid' || (float)$payment->gross_amount >= (float)$order->grand_total) ? 'Lunas' : 'DP';
                                                } elseif ($idx === 0) {
                                                    $payTitle = 'DP';
                                                } elseif ($idx === $validPayments->count() - 1 && ($order->payment_status === 'paid' || (float)$order->remaining_balance <= 0)) {
                                                    $payTitle = 'Pelunasan';
                                                } else {
                                                    $payTitle = 'Tahap ' . ($idx + 1);
                                                }
                                            }
                                        @endphp
                                        <a href="{{ route('print.receipt', ['payment' => $payment->id]) }}" target="_blank" class="w-full inline-flex items-center justify-center bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 text-xs font-bold px-4 py-2.5 rounded-xl border border-emerald-200 dark:border-emerald-800 transition-all gap-1.5 cursor-pointer">
                                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Cetak Kuitansi {{ $payTitle }} (Rp {{ number_format((float)$payment->gross_amount, 0, ',', '.') }})
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        @endif

    </div>

    <!-- Script to Handle Live Tracking & Midtrans Popup -->
    <script>
        function orderTrackingMapHandler(config) {
            return {
                originLat: config.originLat,
                originLng: config.originLng,
                destLat: config.destLat,
                destLng: config.destLng,
                destCity: config.destCity,
                status: config.status,
                map: null,
                truckMarker: null,
                animFrame: null,
                initMap() {
                    setTimeout(() => {
                        const mapEl = document.getElementById('tracking-live-map');
                        if (!mapEl || typeof L === 'undefined') return;

                        if (this.map) {
                            this.map.remove();
                            this.map = null;
                        }

                        this.map = L.map('tracking-live-map', {
                            zoomControl: true,
                            attributionControl: false
                        });

                        const streetLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                            maxZoom: 19,
                            subdomains: 'abcd'
                        });

                        const satTiles = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                            maxZoom: 19
                        });
                        const satLabels = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                            maxZoom: 19
                        });
                        const satelliteLayer = L.layerGroup([satTiles, satLabels]);

                        // Default Peta Jalan untuk Lacak Rute Antar Kota
                        streetLayer.addTo(this.map);

                        L.control.layers({
                            "🗺️ Peta Jalan": streetLayer,
                            "🛰️ Satelit (Atap Rumah)": satelliteLayer
                        }, null, { position: 'topright' }).addTo(this.map);

                        const origin = [this.originLat, this.originLng];
                        const dest = [this.destLat, this.destLng];

                        const makeIcon = (emoji, bg, border, size) => {
                            const html = '<div style="width:' + size + 'px;height:' + size + 'px;background:' + bg + ';border:2px solid ' + border + ';color:#fff;border-radius:9999px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 6px rgba(0,0,0,0.3);font-size:' + (size > 36 ? 18 : 16) + 'px;">' + emoji + '<' + '/div>';
                            return L.divIcon({
                                className: 'custom-map-icon',
                                html: html,
                                iconSize: [size, size],
                                iconAnchor: [size / 2, size / 2]
                            });
                        };

                        const factoryIcon = makeIcon('🏭', '#0f172a', '#fbbf24', 36);
                        const destIcon = makeIcon('📍', '#ea580c', '#ffffff', 36);
                        const truckIcon = makeIcon('🚚', '#f59e0b', '#ffffff', 40);

                        L.marker(origin, { icon: factoryIcon }).addTo(this.map)
                            .bindPopup('Pabrik Indoroster (Purwakarta, Jawa Barat)');

                        L.marker(dest, { icon: destIcon }).addTo(this.map)
                            .bindPopup('Tujuan: ' + this.destCity);

                        const polyline = L.polyline([origin, dest], {
                            color: '#f97316',
                            weight: 4,
                            opacity: 0.85,
                            dashArray: '8, 8'
                        }).addTo(this.map);

                        this.map.fitBounds(polyline.getBounds(), { padding: [40, 40] });

                        let progress = 0;
                        if (this.status === 'delivered' || this.status === 'completed') {
                            progress = 1.0;
                        } else if (this.status === 'shipped') {
                            progress = 0.5;
                        } else {
                            progress = 0.05;
                        }

                        const currentLat = origin[0] + (dest[0] - origin[0]) * progress;
                        const currentLng = origin[1] + (dest[1] - origin[1]) * progress;

                        this.truckMarker = L.marker([currentLat, currentLng], { icon: truckIcon }).addTo(this.map);
                        this.truckMarker.bindPopup('Armada Pabrik: ' + (this.status === 'shipped' ? 'Sedang Dalam Perjalanan' : (this.status === 'delivered' || this.status === 'completed' ? 'Tiba di Lokasi' : 'Dipersiapkan di Pabrik')));

                        if (this.status === 'shipped') {
                            let step = 0;
                            const animate = () => {
                                step = (step + 0.003) % 1;
                                const lat = origin[0] + (dest[0] - origin[0]) * step;
                                const lng = origin[1] + (dest[1] - origin[1]) * step;
                                if (this.truckMarker) {
                                    this.truckMarker.setLatLng([lat, lng]);
                                }
                                this.animFrame = requestAnimationFrame(animate);
                            };
                            this.animFrame = requestAnimationFrame(animate);
                        }

                        this.map.invalidateSize();
                        setTimeout(() => { if (this.map) this.map.invalidateSize(); }, 300);
                    }, 150);
                }
            };
        }

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
            @if(isset($order) && $order && ($order->order_source === 'whatsapp') && (request()->query('open_wa') == 1 || session('new_wa_order')))
                // Auto trigger WhatsApp jika baru selesai checkout
                setTimeout(() => {
                    const waUrl = @json($order->getBuyerToAdminWaOrderLink());
                    if (waUrl && !sessionStorage.getItem('wa_opened_{{ $order->order_number }}')) {
                        sessionStorage.setItem('wa_opened_{{ $order->order_number }}', '1');
                        try {
                            const a = document.createElement('a');
                            a.href = waUrl;
                            a.target = '_blank';
                            a.rel = 'noopener noreferrer';
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                        } catch (e) {
                            console.log('WA Auto Open handled by browser banner');
                        }
                    }
                }, 300);
            @endif
        });
    </script>
</div>
