<div class="bg-slate-50 dark:bg-slate-950 min-h-screen py-12">
    <!-- Midtrans Snap JS (Hanya jika Mode Midtrans Aktif) -->
    @if($orderMode === 'midtrans')
        @if(config('midtrans.is_production'))
            <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
        @else
            <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
        @endif
    @endif

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex text-xs font-semibold text-slate-400 dark:text-slate-500 gap-1.5 mb-2 uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:text-slate-600 dark:hover:text-slate-300 transition-colors">Home</a>
                <span>/</span>
                <span class="text-slate-600 dark:text-slate-300">{{ $orderMode === 'whatsapp' ? 'Pesanan WhatsApp & Proyek' : 'Pesanan Online Saya' }}</span>
            </nav>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="font-display text-fluid-h1 font-black text-slate-900 dark:text-white tracking-tight">
                        {{ $orderMode === 'whatsapp' ? 'Pesanan & Proyek WhatsApp Saya' : 'Pesanan Online Saya' }}
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-1">
                        {{ $orderMode === 'whatsapp' 
                            ? 'Pantau rincian penawaran harga pabrik, progres termin DP, dan jadwal ritase armada pengiriman pesanan WhatsApp Anda.' 
                            : 'Pantau status produksi, pengantaran armada pabrik, dan riwayat belanja online Anda.' }}
                    </p>
                </div>
                @if($orderMode === 'whatsapp')
                    @php
                        $waNum = \App\Models\SiteSetting::getValue('whatsapp_order_number', '6281389709847');
                        $cleanWa = preg_replace('/[^0-9]/', '', $waNum);
                        if (str_starts_with($cleanWa, '0')) {
                            $cleanWa = '62' . substr($cleanWa, 1);
                        }
                    @endphp
                    <a href="https://wa.me/{{ $cleanWa }}?text={{ urlencode('Halo Admin Pabrik IndoRoster, saya ingin konsultasi / cek status pesanan proyek saya.') }}" target="_blank" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-md shadow-emerald-600/20 transition-all duration-200">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.197 1.45 4.817 1.453 5.461 0 9.903-4.44 9.907-9.9.002-2.646-1.03-5.132-2.903-7.008C16.599 1.821 14.113.79 11.467.79c-5.467 0-9.911 4.439-9.915 9.899-.001 1.78.48 3.524 1.393 5.068L1.879 21.65l6.012-1.574-.01.008z"/></svg>
                        Konsultasi WhatsApp Pabrik
                    </a>
                @endif
            </div>
        </div>

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900/40 text-red-700 dark:text-red-300 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filter Status (Tabs) -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-1.5 shadow-soft-xs mb-6 flex overflow-x-auto scrollbar-none gap-1.5">
            @foreach([
                'semua' => 'Semua',
                'penawaran' => 'Penawaran',
                'belum-bayar' => 'Belum Bayar',
                'diproses' => 'Diproses',
                'dikirim' => 'Dikirim',
                'selesai' => 'Selesai',
                'batal' => 'Dibatalkan'
            ] as $key => $label)
                <button wire:click="setTab('{{ $key }}')" 
                    class="font-display inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all duration-200 shrink-0 cursor-pointer
                    {{ $activeTab === $key 
                        ? 'bg-terra-500 text-white shadow-md shadow-terra-500/25' 
                        : 'text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                    <span>{{ $label }}</span>
                    @if(isset($tabCounts[$key]) && $tabCounts[$key] > 0)
                        <span class="inline-flex items-center justify-center px-2 py-0.5 min-w-[20px] text-[10px] font-black rounded-full transition-colors {{ $activeTab === $key ? 'bg-white/20 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300/60 dark:border-slate-700' }}">
                            {{ $tabCounts[$key] }}
                        </span>
                    @endif
                </button>
            @endforeach
        </div>

        <!-- Daftar Pesanan -->
        <div class="space-y-6">
            @if(count($orders) === 0)
                <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-2xl shadow-soft-xs p-16 text-center">
                    <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-lg text-slate-800 dark:text-white">Tidak Ada Pesanan</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 max-w-sm mx-auto">
                        {{ $orderMode === 'whatsapp' 
                            ? 'Belum ada catatan pesanan proyek WhatsApp yang terhubung ke akun Anda.' 
                            : 'Tidak ditemukan transaksi untuk kategori ini. Yuk, jelajahi katalog produk premium kami.' }}
                    </p>
                    @if($orderMode === 'whatsapp')
                        @php
                            $waNum = \App\Models\SiteSetting::getValue('whatsapp_order_number', '6281389709847');
                            $cleanWa = preg_replace('/[^0-9]/', '', $waNum);
                            if (str_starts_with($cleanWa, '0')) {
                                $cleanWa = '62' . substr($cleanWa, 1);
                            }
                        @endphp
                        <a href="https://wa.me/{{ $cleanWa }}?text={{ urlencode('Halo Admin Pabrik IndoRoster, saya ingin memesan / konsultasi kebutuhan roster beton proyek saya.') }}" target="_blank" class="font-display inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-3 rounded-xl transition-all duration-200 mt-6 gap-2 text-sm shadow-md shadow-emerald-600/20">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.197 1.45 4.817 1.453 5.461 0 9.903-4.44 9.907-9.9.002-2.646-1.03-5.132-2.903-7.008C16.599 1.821 14.113.79 11.467.79c-5.467 0-9.911 4.439-9.915 9.899-.001 1.78.48 3.524 1.393 5.068L1.879 21.65l6.012-1.574-.01.008z"/></svg>
                            Pesan via WhatsApp
                        </a>
                    @else
                        <a href="{{ route('catalog') }}" class="font-display inline-flex items-center justify-center bg-slate-900 dark:bg-terra-500 hover:bg-black dark:hover:bg-terra-600 text-white font-bold px-6 py-3 rounded-xl transition-all duration-200 mt-6 gap-2 text-sm">
                            Belanja Sekarang
                        </a>
                    @endif
                </div>
            @else
                @foreach($orders as $order)
                    <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-soft-xs overflow-hidden transition-all duration-200 hover:shadow-md">
                        <!-- Top Header Card -->
                        <div class="bg-slate-50 dark:bg-slate-800/80 px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex flex-wrap justify-between items-center gap-3">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="font-display font-black text-sm text-slate-900 dark:text-white tracking-wide">{{ $order->order_number }}</span>
                                <span class="text-slate-300 dark:text-slate-700">|</span>
                                <span class="text-xs text-slate-500 dark:text-slate-400 font-semibold">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                                @if($order->order_source === 'whatsapp')
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.197 1.45 4.817 1.453 5.461 0 9.903-4.44 9.907-9.9.002-2.646-1.03-5.132-2.903-7.008C16.599 1.821 14.113.79 11.467.79c-5.467 0-9.911 4.439-9.915 9.899-.001 1.78.48 3.524 1.393 5.068L1.879 21.65l6.012-1.574-.01.008z"/></svg>
                                        Pesanan WA / Proyek
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Badges -->
                            <div class="flex items-center gap-2 flex-wrap">
                                @if($order->fulfillment_type)
                                <span class="font-display font-bold text-[10px] px-2.5 py-1 rounded-full border uppercase tracking-wider
                                    {{ match($order->fulfillment_type) {
                                        'ready_stock' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                        'po_single' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                        'po_batch' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                        default => 'bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700',
                                    } }}">
                                    @if($order->is_batch_order)
                                        PO Batch ({{ $order->batches()->whereIn('status', ['shipped', 'delivered'])->count() }}/{{ $order->batch_count }})
                                    @else
                                        {{ $order->fulfillment_label }}
                                    @endif
                                </span>
                                @endif

                                <!-- Status Badge -->
                                @php
                                    $statusColors = match ($order->status) {
                                        'draft' => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700',
                                        'pending_payment' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border-amber-200 dark:border-amber-800',
                                        'paid' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                        'processing' => 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800',
                                        'shipped' => 'bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border-purple-200 dark:border-purple-800',
                                        'delivered', 'completed' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                        'cancelled' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border-rose-200 dark:border-rose-800',
                                        default => 'bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700',
                                    };
                                @endphp
                                <span class="font-display font-bold text-[10px] px-3 py-1 rounded-full border uppercase tracking-wider {{ $statusColors }}">
                                    {{ $order->status === 'draft' ? 'Surat Penawaran' : $order->status_label }}
                                </span>
                            </div>
                        </div>

                        <!-- Order Items List -->
                        <div class="p-6 divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($order->items as $item)
                                <div class="py-4 first:pt-0 last:pb-0 flex gap-4">
                                    <!-- Image -->
                                    <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-xl shrink-0 overflow-hidden flex items-center justify-center border border-slate-200/60 dark:border-slate-700">
                                        @if($item->product && $item->product->primary_image)
                                            <img src="{{ $item->product->primary_image }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-6 h-6 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-display font-bold text-sm text-slate-900 dark:text-white truncate">
                                            {{ $item->product_name }}
                                        </h4>
                                        @if($item->product_variant_name)
                                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Varian: {{ $item->product_variant_name }}</p>
                                        @endif
                                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Jumlah: {{ number_format($item->quantity, 0, ',', '.') }} pcs</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-slate-400 dark:text-slate-500 font-medium">Rp{{ number_format($item->product_price, 0, ',', '.') }}</div>
                                        <div class="text-sm font-bold text-slate-900 dark:text-white mt-0.5">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Skema & Rincian Pembayaran Proyek -->
                        @php
                            $totalPaid = $order->total_paid_amount;
                            $grandTotal = (float) $order->grand_total;
                            $remaining = max(0, $grandTotal - $totalPaid);
                            $payPct = $grandTotal > 0 ? min(100, round(($totalPaid / $grandTotal) * 100)) : 0;
                        @endphp
                        <div class="mx-6 mb-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/60 dark:border-slate-700/60">
                            <div class="flex flex-wrap items-center justify-between gap-2 mb-2">
                                <div class="text-xs font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                    <span>💳 Skema Pembayaran:</span>
                                    <span class="text-slate-900 dark:text-white font-black">
                                        {{ match($order->payment_scheme) {
                                            'quotation' => 'Surat Penawaran Harga (Quotation)',
                                            'dp_50_50' => 'DP 50% di Awal (Pelunasan Siap Kirim)',
                                            'termin_3x' => 'Termin 3x (30% + 40% + 30%)',
                                            'custom_dp' => 'DP / Termin Kustom',
                                            default => 'Lunas Langsung (100%)'
                                        } }}
                                    </span>
                                </div>
                                <div class="text-xs font-bold">
                                    @if($remaining <= 0 || $order->payment_status === 'paid')
                                        <span class="text-emerald-600 dark:text-emerald-400 font-black">✅ LUNAS 100%</span>
                                    @elseif($totalPaid > 0)
                                        <span class="text-amber-600 dark:text-amber-400 font-bold">Terbayar {{ $payPct }}% (Sisa Rp {{ number_format($remaining, 0, ',', '.') }})</span>
                                    @elseif($order->status === 'draft' || $order->payment_scheme === 'quotation')
                                        <span class="text-slate-500 dark:text-slate-400">Tahap Penawaran</span>
                                    @else
                                        <span class="text-rose-600 dark:text-rose-400 font-bold">Menunggu Pembayaran (Rp {{ number_format($order->down_payment_amount ?: $grandTotal, 0, ',', '.') }})</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2 overflow-hidden mb-2">
                                <div class="h-2 rounded-full transition-all duration-500 {{ $payPct >= 100 ? 'bg-emerald-500' : 'bg-terra-500' }}" style="width: {{ $payPct }}%"></div>
                            </div>

                            <div class="grid grid-cols-3 gap-2 text-center text-[11px] pt-1 border-t border-slate-200/40 dark:border-slate-700/40">
                                <div>
                                    <span class="text-slate-400 block">Total Pesanan</span>
                                    <strong class="text-slate-800 dark:text-slate-200">Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block">Sudah Dibayar</span>
                                    <strong class="text-emerald-600 dark:text-emerald-400">Rp {{ number_format($totalPaid, 0, ',', '.') }}</strong>
                                </div>
                                <div>
                                    <span class="text-slate-400 block">Sisa Tagihan</span>
                                    <strong class="{{ $remaining > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-slate-500' }}">Rp {{ number_format($remaining, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Info Shipping & Action Footer -->
                        <div class="bg-slate-50 dark:bg-slate-900/90 border-t border-slate-100 dark:border-slate-800 p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            
                            <!-- Delivery Info (Armada Pabrik) -->
                            <div class="flex-1">
                                @if(in_array($order->status, ['shipped', 'delivered', 'completed']))
                                    <div class="bg-white dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/80 rounded-2xl p-4 flex gap-3.5 text-slate-700 dark:text-slate-200 shadow-soft-xs max-w-md">
                                        <div class="w-10 h-10 bg-terra-50 dark:bg-terra-500/10 text-terra-600 dark:text-terra-400 border border-terra-100 dark:border-terra-500/20 rounded-xl flex items-center justify-center shrink-0">
                                            <svg class="w-6 h-6 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8h4l3 3v5a1 1 0 01-1 1h-1m-6 0h-2" />
                                            </svg>
                                        </div>
                                        <div class="text-xs leading-normal">
                                            <h5 class="font-display font-bold text-slate-900 dark:text-white">Armada Pengiriman Pabrik</h5>
                                            @if($order->courier)
                                                <p class="text-slate-600 dark:text-slate-300 mt-0.5">Sopir: <strong class="text-slate-900 dark:text-white">{{ $order->courier }}</strong></p>
                                            @endif
                                            @if($order->tracking_number)
                                                <p class="text-slate-500 dark:text-slate-400 mt-0.5">Plat Truk: <span class="bg-slate-100 dark:bg-slate-700/90 text-slate-800 dark:text-white px-2 py-0.5 rounded-md font-mono font-bold text-[11px] border border-slate-200 dark:border-slate-600">{{ $order->tracking_number }}</span></p>
                                            @endif
                                            @if($order->courier_phone)
                                                <div class="mt-2">
                                                    @php
                                                        $waPhone = preg_replace('/[^0-9]/', '', $order->courier_phone);
                                                        if (str_starts_with($waPhone, '0')) {
                                                            $waPhone = '62' . substr($waPhone, 1);
                                                        }
                                                        $waText = urlencode("Halo Pak {$order->courier}, saya penerima pesanan {$order->order_number}. Mau koordinasi lokasi pengantaran roster.");
                                                    @endphp
                                                    <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" class="inline-flex items-center gap-1.5 font-bold text-xs text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors">
                                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.724-1.455L0 24zm6.59-4.846c1.6.95 3.197 1.45 4.817 1.453 5.461 0 9.903-4.44 9.907-9.9.002-2.646-1.03-5.132-2.903-7.008C16.599 1.821 14.113.79 11.467.79c-5.467 0-9.911 4.439-9.915 9.899-.001 1.78.48 3.524 1.393 5.068L1.879 21.65l6.012-1.574-.01.008zM17.06 13.9c-.277-.139-1.64-.809-1.895-.901-.254-.093-.44-.139-.624.139-.184.277-.717.901-.879 1.085-.162.184-.323.208-.6.069-.277-.139-1.17-.431-2.228-1.374-.823-.733-1.378-1.64-1.54-1.917-.162-.277-.017-.427.121-.565.125-.124.277-.323.416-.485.139-.162.184-.277.277-.462.093-.185.046-.347-.023-.485-.069-.139-.624-1.503-.855-2.057-.225-.54-.471-.466-.647-.475-.167-.008-.36-.01-.554-.01-.194 0-.508.073-.774.36-.266.287-1.016.993-1.016 2.42 0 1.428 1.039 2.808 1.184 3.002.145.194 2.045 3.123 4.956 4.378.692.299 1.233.477 1.655.611.696.222 1.329.19 1.83.115.558-.083 1.64-.67 1.871-1.316.23-.647.23-1.202.161-1.316-.069-.115-.254-.184-.531-.323z" />
                                                        </svg>
                                                        Hubungi Sopir (WA)
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="text-xs text-slate-500 dark:text-slate-400 max-w-sm leading-relaxed">
                                        Status: <strong class="text-slate-700 dark:text-slate-200">{{ $order->status === 'draft' ? 'Surat Penawaran' : $order->status_label }}</strong>. 
                                        @if($order->status === 'draft')
                                            Dokumen Surat Penawaran resmi siap diunduh.
                                        @elseif($order->status === 'pending_payment')
                                            Menunggu penyelesaian pembayaran DP / Pelunasan.
                                        @elseif($order->status === 'paid' || $order->status === 'processing')
                                            Roster beton Anda sedang kami cetak/siapkan di pabrik Plered, Purwakarta.
                                        @elseif($order->status === 'cancelled')
                                            Pesanan telah dibatalkan.
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Total & CTA Action Buttons -->
                            <div class="flex flex-col items-end gap-2.5 shrink-0">
                                <div class="text-right">
                                    <div class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Total Belanja</div>
                                    <div class="font-display font-black text-terra-600 dark:text-terra-400 text-xl tracking-tight mt-0.5">Rp{{ number_format($order->grand_total, 0, ',', '.') }}</div>
                                </div>
                                
                                <div class="flex gap-2 flex-wrap justify-end">
                                    {{-- Tombol Bayar Online Midtrans (jika pesanan web belum bayar) --}}
                                    @if($order->order_source !== 'whatsapp' && $order->status === 'pending_payment' && $order->payment_status === 'unpaid')
                                        <button wire:click="payOrder({{ $order->id }})" class="font-display inline-flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md shadow-emerald-600/25 hover:shadow-lg hover:shadow-emerald-600/35 hover:-translate-y-0.5 transition-all duration-200 gap-1.5 cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Bayar Sekarang
                                        </button>
                                    @endif
                                    
                                    {{-- Tombol Lacak Pengiriman --}}
                                    <a href="{{ route('order.tracking', ['order_number' => $order->order_number, 'contact' => $order->shipping_email ?? $order->shipping_phone]) }}" class="inline-flex items-center justify-center bg-terra-500 hover:bg-terra-600 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md shadow-terra-500/25 hover:shadow-lg hover:shadow-terra-500/35 hover:-translate-y-0.5 transition-all duration-200 gap-1.5 cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Lacak Pengiriman
                                    </a>
                                    
                                    {{-- Dokumen Penawaran / Invoice Sah --}}
                                    @php
                                        $docUrl = $order->invoice ? URL::signedRoute('print.invoice', ['invoice' => $order->invoice->id]) : route('print.order', ['order' => $order->id]);
                                        $docLabel = ($order->status === 'draft' || $order->payment_scheme === 'quotation') ? 'Surat Penawaran' : ($order->payment_status === 'paid' ? 'Invoice Lunas' : 'Invoice / Tagihan');
                                    @endphp
                                    <a href="{{ $docUrl }}" target="_blank" class="group inline-flex items-center justify-center border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 hover:border-slate-300 dark:hover:border-slate-600 text-slate-700 dark:text-white hover:text-slate-900 dark:hover:text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-2xs transition-all duration-200 gap-1.5 cursor-pointer">
                                        <svg class="w-4 h-4 text-slate-400 dark:text-slate-300 group-hover:text-slate-600 dark:group-hover:text-white transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        📄 {{ $docLabel }}
                                    </a>

                                    {{-- Kuitansi Pembayaran Lengkap (DP, Termin, Pelunasan) --}}
                                    @php
                                        $validPayments = $order->getValidPayments();
                                    @endphp
                                    @if($validPayments->isNotEmpty())
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
                                            <a href="{{ route('print.receipt', ['payment' => $payment->id]) }}" target="_blank" class="group inline-flex items-center justify-center border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 dark:hover:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 text-xs font-bold px-3 py-2.5 rounded-xl shadow-2xs transition-all duration-200 gap-1.5 cursor-pointer whitespace-nowrap" title="Cetak Kuitansi {{ $payTitle }} - Rp {{ number_format((float)$payment->gross_amount, 0, ',', '.') }}">
                                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                🧾 Kuitansi {{ $payTitle }}
                                            </a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            @endif
        </div>
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
