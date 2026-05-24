<div class="bg-slate-50 min-h-screen py-6" wire:init="processPaymentStatus" <?php if($order->payment_status !== 'paid'): ?> wire:poll.8s="checkDatabaseStatus" <?php endif; ?>>
    <!-- Midtrans Snap JS -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('midtrans.is_production')): ?>
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="<?php echo e(config('midtrans.client_key')); ?>"></script>
    <?php else: ?>
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo e(config('midtrans.client_key')); ?>"></script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isVerifying): ?>
                <!-- Verification Loading State -->
                <div class="bg-terra-600 text-white px-4 py-3 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="text-sm font-medium">Sedang memverifikasi pembayaran Anda...</span>
                    </div>
                    <button wire:click="refreshStatus" class="text-xs bg-white/20 hover:bg-white/30 px-3 py-1 rounded-md transition-colors font-bold uppercase tracking-wider">
                        Cek Manual
                    </button>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="p-4 sm:p-8 text-center">
                <div class="mb-4 inline-flex items-center justify-center w-16 h-16 bg-green-100 text-green-600 rounded-full">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isVerifying): ?>
                        <svg class="w-8 h-8 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <?php else: ?>
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <h1 class="font-display text-fluid-h1 font-black text-slate-900 mb-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isVerifying): ?>
                        Memverifikasi Transaksi...
                    <?php elseif($order->payment_status === 'paid'): ?>
                        Pembayaran Berhasil! 🎉
                    <?php else: ?>
                        Menunggu Pembayaran ⏳
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </h1>
                <p class="text-slate-500 mb-8 text-lg">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isVerifying): ?>
                        Transaksi Anda sedang diverifikasi. Halaman ini akan diperbarui otomatis jika pembayaran telah kami terima.
                    <?php elseif($order->payment_status === 'paid'): ?>
                        Terima kasih atas pesanan Anda. Kami telah menerima pembayaran dan sedang memproses pesanan.
                    <?php else: ?>
                        Pesanan Anda telah tercatat. Silakan selesaikan pembayaran Anda agar pesanan dapat kami proses.
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>

                <!-- Ringkasan Pesanan -->
                <div class="bg-slate-50 rounded-xl p-6 mb-6 text-left max-w-lg mx-auto border border-slate-100">
                    <h3 class="font-display font-bold text-slate-700 mb-4 text-sm uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        Ringkasan Pesanan
                    </h3>
                    <div class="flex justify-between mb-3 text-sm">
                        <span class="text-slate-500">Nomor Pesanan</span>
                        <span class="font-bold text-slate-900 font-mono"><?php echo e($order->order_number); ?></span>
                    </div>
                    <div class="flex justify-between mb-3 text-sm">
                        <span class="text-slate-500">Tanggal</span>
                        <span class="font-semibold text-slate-700"><?php echo e($order->created_at->format('d M Y, H:i')); ?></span>
                    </div>
                    <div class="flex justify-between mb-3 text-sm">
                        <span class="text-slate-500">Status Pembayaran</span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isVerifying): ?>
                            <span class="bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase animate-pulse">MEMVERIFIKASI...</span>
                        <?php elseif($order->payment_status === 'paid'): ?>
                            <span class="bg-green-100 text-green-700 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase">LUNAS</span>
                        <?php else: ?>
                            <span class="bg-yellow-100 text-yellow-700 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase"><?php echo e($order->payment_status); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Daftar Produk -->
                    <div class="border-t border-slate-200 mt-4 pt-4">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Produk Dipesan</p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex justify-between items-start mb-3 text-sm">
                            <div class="flex-1">
                                <p class="font-display font-bold text-slate-800">
                                    <?php echo e($item->product_name); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->product_variant_name): ?>
                                        <span class="text-slate-500 font-normal text-xs">(<?php echo e($item->product_variant_name); ?>)</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                                <p class="text-slate-400 text-xs"><?php echo e($item->quantity); ?>x @ Rp<?php echo e(number_format($item->product_price, 0, ',', '.')); ?></p>
                            </div>
                            <span class="font-bold text-slate-700 ml-4">Rp<?php echo e(number_format($item->subtotal, 0, ',', '.')); ?></span>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <!-- Subtotal, Ongkir, Total -->
                    <div class="border-t border-slate-200 mt-3 pt-3 space-y-2">
                        <div class="flex justify-between text-sm text-slate-500">
                            <span>Subtotal</span>
                            <span>Rp<?php echo e(number_format($order->subtotal, 0, ',', '.')); ?></span>
                        </div>
                        <div class="flex justify-between text-sm text-slate-500">
                            <span>Ongkos Kirim</span>
                            <span>Rp<?php echo e(number_format($order->shipping_cost, 0, ',', '.')); ?></span>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->discount_amount > 0): ?>
                        <div class="flex justify-between text-sm text-red-500">
                            <span>Diskon</span>
                            <span>-Rp<?php echo e(number_format($order->discount_amount, 0, ',', '.')); ?></span>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="font-display flex justify-between text-base font-bold text-terra-600 border-t border-dashed border-slate-300 pt-3">
                            <span>Total Dibayar</span>
                            <span>Rp<?php echo e(number_format($order->grand_total, 0, ',', '.')); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Info Email -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->payment_status === 'paid'): ?>
                <div class="p-4 bg-blue-50 border border-blue-100 rounded-lg flex gap-3 text-left text-blue-800 text-sm mb-8 max-w-lg mx-auto">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    <p><strong>Invoice telah dikirim</strong> ke email <strong><?php echo e($order->shipping_email ?? $order->user->email); ?></strong>. Periksa folder Inbox atau Spam Anda.</p>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php
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
                ?>

                <!-- Tombol Navigasi Bawah -->
                <div class="flex flex-col gap-3 max-w-lg mx-auto">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->payment_status !== 'paid'): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->snap_token): ?>
                            <button onclick="continuePayment('<?php echo e($order->snap_token); ?>')" class="font-display w-full bg-terra-600 hover:bg-terra-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-terra-600/20 text-center flex justify-center items-center gap-2">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                Lanjutkan Pembayaran
                            </button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <a href="/keranjang" class="font-display w-full bg-white border border-slate-200 text-slate-600 font-bold py-4 rounded-xl hover:bg-slate-50 transition-all text-center flex justify-center items-center gap-2">
                            <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                            Ubah Pesanan / Kembali ke Keranjang
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($order->payment_status === 'paid'): ?>
                    <p class="text-slate-600 font-semibold text-sm mt-2">Silahkan konfirmasi ke admin biar cepat di proses</p>
                    
                    <a href="<?php echo e($waUrl); ?>" target="_blank" rel="noopener"
                       style="background-color: #25D366 !important;"
                       class="font-display w-full flex items-center justify-center gap-3 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-green-500/25 hover:shadow-green-500/40 hover:-translate-y-1">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        <span>Konfirmasi via WhatsApp</span>
                    </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <a href="<?php echo e(route('order.tracking', ['order_number' => $order->order_number, 'contact' => $order->shipping_email ?? $order->shipping_phone])); ?>" class="font-display w-full bg-terra-500 hover:bg-terra-600 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-terra-500/20 text-center flex justify-center items-center gap-2">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                        Lacak Detail Pengiriman
                    </a>

                    <a href="/" class="font-display w-full bg-white border border-slate-200 text-slate-600 font-bold py-4 rounded-xl hover:bg-slate-50 transition-all text-center">
                        Kembali ke Beranda
                    </a>

                    <a href="/katalog" class="font-display w-full bg-white border border-slate-200 text-slate-600 font-bold py-4 rounded-xl hover:bg-slate-50 transition-all text-center">
                        Belanja Lagi
                    </a>
                </div>
            </div>

            <!-- Footer Message -->
            <div class="bg-slate-50 p-6 border-t border-slate-100 text-center">
                <p class="text-sm text-slate-400 italic">Pesanan akan dikirimkan sesuai alamat yang Anda berikan. Hubungi kami via WhatsApp jika ada pertanyaan.</p>
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

<?php /**PATH C:\xampp\htdocs\indoroster\resources\views/livewire/order-success.blade.php ENDPATH**/ ?>