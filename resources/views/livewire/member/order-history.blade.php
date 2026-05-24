<div class="bg-slate-50 min-h-screen py-12">
    <!-- Midtrans Snap JS -->
    @if(config('midtrans.is_production'))
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex text-xs font-semibold text-slate-400 gap-1.5 mb-2 uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:text-slate-600 transition-colors">Home</a>
                <span>/</span>
                <span class="text-slate-600">Pesanan Saya</span>
            </nav>
            <h1 class="font-display text-fluid-h1 font-black text-slate-900 tracking-tight">Pesanan Saya</h1>
            <p class="text-slate-500 mt-1">Pantau status produksi, pengantaran armada pabrik, dan riwayat belanja Anda.</p>
        </div>

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filter Status (Tabs) -->
        <div class="bg-white border border-slate-100 rounded-2xl p-1.5 shadow-sm mb-6 flex overflow-x-auto scrollbar-none gap-1">
            @foreach([
                'semua' => 'Semua',
                'belum-bayar' => 'Belum Bayar',
                'diproses' => 'Diproses',
                'dikirim' => 'Dikirim',
                'selesai' => 'Selesai',
                'batal' => 'Dibatalkan'
            ] as $key => $label)
                <button wire:click="setTab('{{ $key }}')" 
                    class="font-display px-5 py-3 rounded-xl text-sm font-bold transition-all duration-200 shrink-0 cursor-pointer
                    {{ $activeTab === $key ? 'bg-terra-500 text-white shadow-md shadow-terra-500/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <!-- Daftar Pesanan -->
        <div class="space-y-6">
            @if(count($orders) === 0)
                <div class="bg-white border border-slate-100 rounded-2xl shadow-sm p-16 text-center">
                    <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h3 class="font-display font-bold text-lg text-slate-800">Tidak Ada Pesanan</h3>
                    <p class="text-sm text-slate-500 mt-2 max-w-sm mx-auto">Tidak ditemukan transaksi untuk kategori ini. Yuk, jelajahi katalog produk premium kami.</p>
                    <a href="{{ route('catalog') }}" class="font-display inline-flex items-center justify-center bg-slate-900 hover:bg-black text-white font-bold px-6 py-3 rounded-xl transition-all duration-200 mt-6 gap-2 text-sm">
                        Belanja Sekarang
                    </a>
                </div>
            @else
                @foreach($orders as $order)
                    <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md">
                        <!-- Top Header Card -->
                        <div class="bg-slate-50/50 px-6 py-4 border-b border-slate-100 flex flex-wrap justify-between items-center gap-3">
                            <div class="flex items-center gap-3 flex-wrap">
                                <span class="font-display font-black text-sm text-slate-900">{{ $order->order_number }}</span>
                                <span class="text-slate-300">|</span>
                                <span class="text-xs text-slate-500 font-semibold">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                            </div>
                            
                            <!-- Badges -->
                            <div class="flex items-center gap-2">
                                <!-- Status Badge -->
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
                                <span class="font-display font-bold text-[10px] px-3 py-1 rounded-full border uppercase tracking-wider {{ $statusColors }}">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                        </div>

                        <!-- Order Items List -->
                        <div class="p-6 divide-y divide-slate-100">
                            @foreach($order->items as $item)
                                <div class="py-4 first:pt-0 last:pb-0 flex gap-4">
                                    <!-- Image (Fallback placeholder) -->
                                    <div class="w-16 h-16 bg-slate-100 rounded-lg shrink-0 overflow-hidden flex items-center justify-center border border-slate-200/50">
                                        @if($item->product && $item->product->primary_image)
                                            <img src="{{ $item->product->primary_image }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-display font-bold text-sm text-slate-800 truncate">
                                            {{ $item->product_name }}
                                        </h4>
                                        @if($item->product_variant_name)
                                            <p class="text-xs text-slate-500 mt-0.5">Varian: {{ $item->product_variant_name }}</p>
                                        @endif
                                        <p class="text-xs text-slate-400 mt-1">Jumlah: {{ $item->quantity }} pcs</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-slate-400 font-medium">Rp{{ number_format($item->product_price, 0, ',', '.') }}</div>
                                        <div class="text-sm font-bold text-slate-800 mt-0.5">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Info Shipping & Action Footer -->
                        <div class="bg-slate-50/30 border-t border-slate-100 p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            
                            <!-- Delivery Info (Armada Pabrik) -->
                            <div class="flex-1">
                                @if(in_array($order->status, ['shipped', 'delivered', 'completed']))
                                    <div class="bg-white border border-slate-100 rounded-xl p-3.5 flex gap-3 text-slate-700 shadow-sm max-w-md">
                                        <!-- Truck Icon -->
                                        <div class="w-10 h-10 bg-terra-50 text-terra-600 rounded-lg flex items-center justify-center shrink-0">
                                            <svg class="w-6 h-6 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8h4l3 3v5a1 1 0 01-1 1h-1m-6 0h-2" />
                                            </svg>
                                        </div>
                                        <div class="text-xs leading-normal">
                                            <h5 class="font-display font-bold text-slate-900">Armada Pengiriman Pabrik</h5>
                                            @if($order->courier)
                                                <p class="text-slate-600 mt-0.5">Sopir: <strong>{{ $order->courier }}</strong></p>
                                            @endif
                                            @if($order->tracking_number)
                                                <p class="text-slate-500">Plat Truk: <span class="bg-slate-100 text-slate-800 px-1.5 py-0.5 rounded font-mono font-bold text-[10px]">{{ $order->tracking_number }}</span></p>
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
                                                    <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" class="inline-flex items-center gap-1 font-bold text-emerald-600 hover:text-emerald-700 transition-colors">
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
                                    <div class="text-xs text-slate-500 max-w-sm leading-relaxed">
                                        Status: <strong>{{ $order->status_label }}</strong>. 
                                        @if($order->status === 'pending_payment')
                                            Menunggu penyelesaian pembayaran.
                                        @elseif($order->status === 'paid' || $order->status === 'processing')
                                            Roster beton Anda sedang kami cetak/siapkan di pabrik Plered, Purwakarta.
                                        @elseif($order->status === 'cancelled')
                                            Pesanan telah dibatalkan.
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Total & CTA -->
                            <div class="flex flex-col items-end gap-2 shrink-0">
                                <div class="text-right">
                                    <div class="text-xs text-slate-400">Total Belanja</div>
                                    <div class="font-display font-black text-terra-600 text-lg">Rp{{ number_format($order->grand_total, 0, ',', '.') }}</div>
                                </div>
                                
                                <div class="flex gap-2.5">
                                    @if($order->status === 'pending_payment' && $order->payment_status === 'unpaid')
                                        <button wire:click="payOrder({{ $order->id }})" class="font-display inline-flex items-center justify-center bg-slate-900 hover:bg-black text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all gap-1.5 cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            Bayar Sekarang
                                        </button>
                                    @endif
                                    
                                    <a href="{{ route('order.tracking', ['order_number' => $order->order_number, 'contact' => $order->shipping_email ?? $order->shipping_phone]) }}" class="inline-flex items-center justify-center bg-terra-50 text-terra-600 hover:bg-terra-100 hover:text-terra-700 text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition-all gap-1.5">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Lacak Pengiriman
                                    </a>
                                    
                                    @if($order->invoice)
                                        <a href="{{ URL::signedRoute('print.invoice', ['invoice' => $order->invoice->id]) }}" target="_blank" class="inline-flex items-center justify-center border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition-all gap-1.5">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                            Cetak Invoice
                                        </a>
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
