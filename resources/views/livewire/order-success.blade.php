<div class="bg-slate-50 dark:bg-slate-950 min-h-screen py-6" wire:init="processPaymentStatus" @if($order->payment_status !== 'paid') wire:poll.8s="checkDatabaseStatus" @endif>
    <!-- Midtrans Snap JS -->
    @if(config('midtrans.is_production'))
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-soft-xs border border-slate-200/80 dark:border-slate-800 overflow-hidden">
            
            @if($isVerifying)
                <!-- Verification Live Progress Bar -->
                <div class="w-full bg-terra-600 text-white px-4 sm:px-6 py-3 flex items-center justify-between gap-3 shadow-inner">
                    <div class="flex items-center gap-3">
                        <div class="relative flex items-center justify-center w-4 h-4 shrink-0">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-white"></span>
                        </div>
                        <span class="text-xs sm:text-sm font-bold tracking-wide">Sedang Memverifikasi Pembayaran Anda...</span>
                    </div>
                    <button wire:click="refreshStatus" class="text-xs bg-white/20 hover:bg-white/30 px-3 py-1.5 rounded-xl transition-all font-bold uppercase tracking-wider shrink-0 cursor-pointer shadow-xs">
                        🔄 Cek Status Sekarang
                    </button>
                </div>
            @endif

            <div class="p-6 sm:p-10 text-center">
                @if($isVerifying)
                    <!-- Loading Spinner Besar & Megah -->
                    <div class="mb-6 inline-flex items-center justify-center relative">
                        <div class="w-24 h-24 rounded-full border-4 border-terra-500/20 dark:border-terra-500/30 border-t-terra-500 animate-spin"></div>
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-terra-50 to-amber-50 dark:from-terra-950/80 dark:to-amber-950/60 flex items-center justify-center absolute shadow-inner">
                            <svg class="w-10 h-10 text-terra-600 dark:text-terra-400 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                @else
                    <div class="mb-4 inline-flex items-center justify-center w-20 h-20 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-3xl shadow-soft-sm">
                        <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                    </div>
                @endif

                <h1 class="font-display text-fluid-h1 font-black text-slate-900 dark:text-white mb-2">
                    @if($isVerifying)
                        Memverifikasi Transaksi...
                    @elseif($order->payment_status === 'paid')
                        Pembayaran Berhasil! 🎉
                    @else
                        Menunggu Pembayaran ⏳
                    @endif
                </h1>
                <p class="text-slate-500 dark:text-slate-400 mb-6 text-base sm:text-lg">
                    @if($isVerifying)
                        Transaksi Anda sedang dikonfirmasi ke bank. Halaman ini akan diperbarui otomatis saat pembayaran berhasil diverifikasi.
                    @elseif($order->payment_status === 'paid')
                        Terima kasih atas pesanan Anda. Kami telah menerima pembayaran dan sedang memproses pesanan.
                    @else
                        Pesanan Anda telah tercatat. Silakan selesaikan pembayaran Anda agar pesanan dapat kami proses.
                    @endif
                </p>

                @if($isVerifying)
                    <!-- Peringatan Jangan Tutup Halaman -->
                    <div class="mb-8 max-w-lg mx-auto bg-amber-500/10 dark:bg-amber-500/15 border-2 border-amber-500/40 dark:border-amber-500/30 rounded-2xl p-4 sm:p-5 text-left shadow-soft-xs">
                        <div class="flex items-start gap-3.5">
                            <div class="w-8 h-8 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 font-black text-base shadow-sm">
                                ⚠️
                            </div>
                            <div>
                                <h4 class="font-display font-black text-amber-900 dark:text-amber-200 text-xs sm:text-sm uppercase tracking-wider mb-1">
                                    MOHON JANGAN MENUTUP ATAU ME-REFRESH HALAMAN INI!
                                </h4>
                                <p class="text-xs text-amber-800 dark:text-amber-300/90 leading-relaxed">
                                    Sistem kami sedang menyinkronkan data pembayaran dengan server bank. Halaman ini akan <strong>otomatis berpindah ke status Sukses</strong> dalam hitungan detik setelah pembayaran terkonfirmasi.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Ringkasan Pesanan -->
                <div class="bg-slate-50 dark:bg-slate-950/60 rounded-2xl p-6 mb-6 text-left max-w-lg mx-auto border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
                    <h3 class="font-display font-bold text-slate-800 dark:text-slate-200 mb-4 text-xs sm:text-sm uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        <span>Ringkasan Pesanan</span>
                    </h3>
                    <div class="flex justify-between items-center mb-3 text-sm">
                        <span class="text-slate-600 dark:text-slate-400">Nomor Pesanan</span>
                        <span class="font-bold text-slate-900 dark:text-white font-mono">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-3 text-sm">
                        <span class="text-slate-600 dark:text-slate-400">Tanggal</span>
                        <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-3 text-sm">
                        <span class="text-slate-600 dark:text-slate-400">Status Pembayaran</span>
                        @if($isVerifying)
                            <span class="bg-blue-100 dark:bg-blue-950/70 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800/60 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase animate-pulse">MEMVERIFIKASI...</span>
                        @elseif($order->payment_status === 'paid')
                            <span class="bg-emerald-100 dark:bg-emerald-950/70 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/60 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase">LUNAS</span>
                        @else
                            <span class="bg-amber-100 dark:bg-amber-950/70 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase">{{ $order->payment_status }}</span>
                        @endif
                    </div>

                    <!-- Daftar Produk -->
                    <div class="border-t border-slate-200 dark:border-slate-800 mt-4 pt-4">
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Produk Dipesan</p>
                        @foreach($order->items as $item)
                        <div class="flex justify-between items-start mb-3 text-sm">
                            <div class="flex-1 pr-3">
                                <p class="font-display font-bold text-slate-900 dark:text-white leading-snug">
                                    {{ $item->product_name }}
                                    @if($item->product_variant_name)
                                        <span class="text-slate-500 dark:text-slate-400 font-normal text-xs">({{ $item->product_variant_name }})</span>
                                    @endif
                                </p>
                                <p class="text-slate-500 dark:text-slate-400 text-xs mt-0.5">{{ $item->quantity }}x @ Rp{{ number_format($item->product_price, 0, ',', '.') }}</p>
                            </div>
                            <span class="font-bold text-slate-900 dark:text-white shrink-0">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Subtotal, Ongkir, Total -->
                    <div class="border-t border-slate-200 dark:border-slate-800 mt-3 pt-3 space-y-2">
                        <div class="flex justify-between text-sm text-slate-600 dark:text-slate-400">
                            <span>Subtotal</span>
                            <span class="text-slate-800 dark:text-slate-200 font-semibold">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-slate-600 dark:text-slate-400">
                            <span>Ongkos Kirim</span>
                            <span class="text-slate-800 dark:text-slate-200 font-semibold">Rp{{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm text-red-600 dark:text-red-400">
                            <span>Diskon</span>
                            <span class="font-semibold">-Rp{{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="font-display flex justify-between text-base font-extrabold text-terra-600 dark:text-terra-400 border-t border-dashed border-slate-300 dark:border-slate-700 pt-3">
                            <span>Total Dibayar</span>
                            <span>Rp{{ number_format($order->grand_total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Info Email -->
                @if($order->payment_status === 'paid')
                <div class="p-4 bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900/40 rounded-xl flex gap-3 text-left text-blue-800 dark:text-blue-300 text-sm mb-8 max-w-lg mx-auto">
                    <svg class="w-5 h-5 shrink-0 mt-0.5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    <p><strong>Invoice telah dikirim</strong> ke email <strong>{{ $order->shipping_email ?? $order->user->email }}</strong>. Periksa folder Inbox atau Spam Anda.</p>
                </div>
                @endif

                @php
                    $adminWa = \App\Models\SiteSetting::getValue('whatsapp_number', '6281389709847');
                    $adminWa = preg_replace('/[^0-9]/', '', $adminWa);
                    if (str_starts_with($adminWa, '0')) {
                        $adminWa = '62' . substr($adminWa, 1);
                    }

                    $waItems = '';
                    foreach ($order->items as $i => $item) {
                        $variantName = $item->product_variant_name ? ' (' . $item->product_variant_name . ')' : '';
                        $waItems .= ($i + 1) . '. ' . $item->product_name . $variantName . ' (x' . $item->quantity . ') — Rp' . number_format($item->subtotal, 0, ',', '.') . "\n";
                    }

                    $introText = $order->payment_status === 'paid' 
                        ? "Saya sudah melakukan pembayaran:" 
                        : "Saya ingin memproses pesanan berikut:";

                    $waText = "Halo Admin Indoroster 👋\n\n"
                        . "{$introText}\n"
                        . "📋 *No. Order:* {$order->order_number}\n"
                        . "📅 *Tanggal:* " . $order->created_at->format('d M Y, H:i') . "\n"
                        . "👤 *Nama:* {$order->shipping_name}\n"
                        . "📱 *HP:* {$order->shipping_phone}\n\n"
                        . "🛒 *Produk Dipesan:*\n"
                        . $waItems . "\n"
                        . "--------------------------\n"
                        . "Subtotal: Rp" . number_format($order->subtotal, 0, ',', '.') . "\n"
                        . "Ongkir: Rp" . number_format($order->shipping_cost, 0, ',', '.') . "\n";
                    
                    if ($order->discount_amount > 0) {
                        $waText .= "Diskon: -Rp" . number_format($order->discount_amount, 0, ',', '.') . "\n";
                    }

                    $waText .= "💰 *TOTAL DIBAYAR: Rp" . number_format($order->grand_total, 0, ',', '.') . "*\n\n"
                        . "📦 *Alamat Kirim:*\n"
                        . "{$order->shipping_address}, {$order->shipping_city}, {$order->shipping_province} {$order->shipping_postal_code}\n\n"
                        . "Mohon segera diproses ya. Terima kasih! 🙏";

                    $waUrl = 'https://wa.me/' . $adminWa . '?text=' . urlencode($waText);
                @endphp

                <!-- Tombol Navigasi Bawah -->
                <div class="flex flex-col gap-3 max-w-lg mx-auto">
                    @if($order->payment_status !== 'paid')
                        @if($order->snap_token)
                            <button onclick="continuePayment('{{ $order->snap_token }}')" class="font-display w-full bg-terra-600 hover:bg-terra-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-terra-600/20 text-center flex justify-center items-center gap-2 cursor-pointer">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                <span>Lanjutkan Pembayaran</span>
                            </button>
                        @endif

                        <a href="/keranjang" class="font-display w-full bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 font-bold py-4 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-center flex justify-center items-center gap-2">
                            <svg class="w-5 h-5 text-slate-500 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                            <span>Ubah Pesanan / Kembali ke Keranjang</span>
                        </a>
                    @endif

                    @if($order->payment_status === 'paid')
                    <p class="text-slate-600 dark:text-slate-400 font-semibold text-sm mt-2">Silahkan konfirmasi ke admin biar cepat di proses</p>
                    
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener"
                       style="background-color: #25D366 !important;"
                       class="font-display w-full flex items-center justify-center gap-3 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-green-500/25 hover:shadow-green-500/40 hover:-translate-y-1">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        <span>Konfirmasi via WhatsApp</span>
                    </a>
                    @endif

                    <a href="{{ route('order.tracking', ['order_number' => $order->order_number, 'contact' => $order->shipping_email ?? $order->shipping_phone]) }}" class="font-display w-full bg-terra-500 hover:bg-terra-600 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-terra-500/20 text-center flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        <span>Lacak Detail Pengiriman</span>
                    </a>

                    <a href="/" class="font-display w-full bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 font-bold py-4 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-center">
                        <span>Kembali ke Beranda</span>
                    </a>

                    <a href="/katalog" class="font-display w-full bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 font-bold py-4 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-center">
                        <span>Belanja Lagi</span>
                    </a>
                </div>
            </div>

            <!-- Footer Message -->
            <div class="bg-slate-50 dark:bg-slate-950/60 p-6 border-t border-slate-100 dark:border-slate-800 text-center">
                <p class="text-sm text-slate-500 dark:text-slate-400 italic">Pesanan akan dikirimkan sesuai alamat yang Anda berikan. Hubungi kami via WhatsApp jika ada pertanyaan.</p>
            </div>
        </div>
    </div>

    <script>
        function continuePayment(token) {
            snap.pay(token, {
                onSuccess: function(result) {
                    window.location.reload();
                },
                onPending: function(result) {
                    window.location.reload();
                },
                onError: function(result) {
                    alert('Pembayaran gagal! Silakan coba lagi.');
                },
                onClose: function() {
                    window.location.reload();
                }
            });
        }
    </script>
</div>

