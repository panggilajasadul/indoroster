<div class="bg-slate-50 min-h-screen py-12">
    <!-- Midtrans Snap JS -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('midtrans.is_production')): ?>
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="<?php echo e(config('midtrans.client_key')); ?>"></script>
    <?php else: ?>
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo e(config('midtrans.client_key')); ?>"></script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="font-display text-fluid-h1 font-black text-slate-900 tracking-tight">Checkout</h1>
            <p class="text-slate-500 mt-2">Lengkapi data pengiriman untuk memproses pesanan Anda.</p>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('error')): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <form wire:submit.prevent="processCheckout" class="grid grid-cols-1 lg:grid-cols-3 gap-8 <?php if($isProcessing): ?> opacity-75 pointer-events-none <?php endif; ?>">
            
            <!-- Formulir Pengiriman -->
            <div class="lg:col-span-2 space-y-6">
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->guest()): ?>
                    <!-- Banner Persuasif Login -->
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 sm:p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-blue-900 shadow-sm">
                        <div class="flex gap-3">
                            <svg class="w-6 h-6 text-blue-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h4 class="font-display font-bold text-sm text-blue-950">Lebih Hemat & Mudah dengan Akun Indoroster</h4>
                                <p class="text-xs text-blue-800/90 mt-1 leading-relaxed">
                                    Masuk ke akun Anda untuk menggunakan alamat tersimpan, melacak pesanan otomatis, dan mendapatkan penawaran khusus.
                                </p>
                            </div>
                        </div>
                        <a href="<?php echo e(route('login')); ?>" class="font-display shrink-0 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 px-4 py-2.5 rounded-xl transition-colors">
                            Masuk / Daftar
                        </a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($savedAddresses) > 0): ?>
                        <!-- Buku Alamat Selector -->
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 sm:p-8">
                            <h2 class="font-display text-fluid-h3 font-bold text-slate-900 mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Pilih Alamat Tersimpan
                            </h2>
                            <div class="grid grid-cols-1 gap-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $savedAddresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="relative flex p-4 border rounded-xl cursor-pointer focus:outline-none transition-all duration-200 <?php echo e($selectedAddressId == $addr->id ? 'border-terra-500 bg-terra-50/30' : 'border-gray-200 hover:bg-slate-50'); ?>">
                                        <input type="radio" name="selected_address" value="<?php echo e($addr->id); ?>" wire:click="selectAddress(<?php echo e($addr->id); ?>)" class="sr-only" <?php echo e($selectedAddressId == $addr->id ? 'checked' : ''); ?>>
                                        <span class="flex flex-col text-left">
                                            <span class="flex items-center gap-2">
                                                <span class="font-display font-bold text-sm text-slate-800"><?php echo e($addr->label); ?></span>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($addr->is_default): ?>
                                                    <span class="text-[10px] font-bold text-terra-600 bg-terra-50 px-2 py-0.5 rounded-full border border-terra-100">Utama</span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </span>
                                            <span class="text-sm font-semibold text-slate-900 mt-1"><?php echo e($addr->recipient_name); ?> (<?php echo e($addr->phone); ?>)</span>
                                            <span class="text-xs text-slate-500 mt-1 leading-relaxed"><?php echo e($addr->formatted_address); ?></span>
                                        </span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedAddressId == $addr->id): ?>
                                            <span class="absolute top-4 right-4 text-terra-500">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- Pilihan Alamat Baru / Manual -->
                                <label class="relative flex p-4 border rounded-xl cursor-pointer focus:outline-none transition-all duration-200 <?php echo e(is_null($selectedAddressId) ? 'border-terra-500 bg-terra-50/30' : 'border-gray-200 hover:bg-slate-50'); ?>">
                                    <input type="radio" name="selected_address" value="manual" wire:click="useManualAddress" class="sr-only" <?php echo e(is_null($selectedAddressId) ? 'checked' : ''); ?>>
                                    <span class="flex flex-col text-left">
                                        <span class="flex items-center gap-2">
                                            <span class="font-display font-bold text-sm text-slate-800">Tulis Alamat Baru / Manual</span>
                                        </span>
                                        <span class="text-xs text-slate-500 mt-1 leading-relaxed">Pilih opsi ini jika Anda ingin mengirim ke alamat baru atau mengisi alamat secara manual.</span>
                                    </span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_null($selectedAddressId)): ?>
                                        <span class="absolute top-4 right-4 text-terra-500">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </label>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedAddressId): ?>
                                <!-- Catatan Pesanan khusus untuk alamat tersimpan -->
                                <div class="mt-6 pt-6 border-t border-gray-100 text-left">
                                    <label class="font-display block text-sm font-bold text-slate-700 mb-2">Catatan Pesanan (Opsional)</label>
                                    <input type="text" wire:model="notes" <?php if($isProcessing): echo 'disabled'; endif; ?> placeholder="Contoh: Titip di pos satpam" class="w-full px-4 py-3 border border-slate-200 rounded-xl shadow-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 disabled:bg-slate-50 disabled:text-slate-500">
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="mt-4 text-right">
                                <a href="<?php echo e(route('member.addresses')); ?>" class="text-xs font-bold text-terra-500 hover:text-terra-600 flex items-center justify-end gap-1">
                                    Kelola Buku Alamat
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_null($selectedAddressId)): ?>
                    <!-- Alert Validasi Data -->
                <div class="bg-amber-50 border border-amber-200/80 rounded-xl p-4 sm:p-5 flex gap-3.5 text-amber-900 shadow-sm">
                    <svg class="w-6 h-6 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <h4 class="font-display font-bold text-sm text-amber-950">PENTING: Periksa Kembali Data Anda</h4>
                        <p class="text-xs text-amber-800/90 mt-1 leading-relaxed">
                            Mohon pastikan <strong>alamat pengiriman</strong> ditulis dengan sangat detail, serta <strong>email</strong> dan <strong>nomor WhatsApp</strong> yang Anda masukkan adalah benar dan aktif. Hal ini penting untuk keperluan pengiriman struk/invoice serta koordinasi pengiriman oleh <strong>Armada Pengiriman Pabrik Indoroster</strong>.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 sm:p-8">
                    <h2 class="font-display text-fluid-h3 font-bold text-slate-900 mb-6 pb-4 border-b border-gray-100 flex items-center gap-2">
                        <svg class="w-6 h-6 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Informasi Kontak
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="font-display block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                            <input type="text" wire:model="name" <?php if($isProcessing): echo 'disabled'; endif; ?> class="w-full px-4 py-3 border border-slate-200 rounded-xl shadow-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 disabled:bg-slate-50 disabled:text-slate-500">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="font-display block text-sm font-bold text-slate-700 mb-2">Email</label>
                            <input type="email" wire:model="email" <?php if($isProcessing): echo 'disabled'; endif; ?> class="w-full px-4 py-3 border border-slate-200 rounded-xl shadow-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 disabled:bg-slate-50 disabled:text-slate-500">
                            <p class="text-slate-400 text-[11px] mt-1.5 flex items-start gap-1 leading-normal">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>Invoice PDF & bukti transaksi akan dikirimkan secara otomatis ke email ini.</span>
                            </p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="md:col-span-2">
                            <label class="font-display block text-sm font-bold text-slate-700 mb-2">Nomor HP / WhatsApp</label>
                            <input type="tel" wire:model="phone" <?php if($isProcessing): echo 'disabled'; endif; ?> class="w-full px-4 py-3 border border-slate-200 rounded-xl shadow-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 disabled:bg-slate-50 disabled:text-slate-500">
                            <p class="text-slate-400 text-[11px] mt-1.5 flex items-start gap-1 leading-normal">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>Kurir akan menghubungi nomor ini saat pengantaran barang ke alamat Anda.</span>
                            </p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 sm:p-8">
                    <h2 class="font-display text-fluid-h3 font-bold text-slate-900 mb-6 pb-4 border-b border-gray-100 flex items-center gap-2">
                        <svg class="w-6 h-6 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Alamat Pengiriman
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="font-display block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap</label>
                            <textarea wire:model="address" <?php if($isProcessing): echo 'disabled'; endif; ?> rows="3" class="w-full px-4 py-3 border border-slate-200 rounded-xl shadow-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 disabled:bg-slate-50 disabled:text-slate-500"></textarea>
                            <p class="text-slate-400 text-[11px] mt-1.5 flex items-start gap-1 leading-normal">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>Tuliskan alamat lengkap secara detail (seperti nama jalan, nomor rumah, RT/RW, blok, atau patokan bangunan terdekat).</span>
                            </p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="font-display flex justify-between items-center text-sm font-bold text-slate-700 mb-2">
                                <span>Provinsi</span>
                            </label>
                            <div wire:ignore x-data="{
                                tom: null,
                                init() {
                                    this.tom = new TomSelect($refs.selectProvince, {
                                        create: false,
                                        openOnFocus: true,
                                    });
                                    this.tom.on('change', (val) => {
                                        $wire.set('province_id', val);
                                    });
                                    $watch('$wire.isProcessing', (val) => {
                                        val ? this.tom.disable() : this.tom.enable();
                                    });
                                    $watch('$wire.province_id', (val) => {
                                        if (val && this.tom.getValue() !== val) {
                                            this.tom.setValue(val, true);
                                        }
                                    });
                                }
                            }">
                                <select x-ref="selectProvince" <?php if($isProcessing): echo 'disabled'; endif; ?> class="w-full border-gray-300 rounded-lg shadow-sm focus:border-terra-500 focus:ring focus:ring-terra-200 transition-shadow disabled:bg-gray-50">
                                    <option value="">Pilih Provinsi</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $provinces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($p->code); ?>"><?php echo e($p->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['province_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="font-display flex justify-between items-center text-sm font-bold text-slate-700 mb-2">
                                <span>Kota/Kabupaten</span>
                                <span wire:loading wire:target="province_id" class="text-xs text-terra-500 animate-pulse">Memuat...</span>
                            </label>
                            <div wire:ignore x-data="{
                                tom: null,
                                init() {
                                    this.tom = new TomSelect($refs.selectCity, {
                                        create: false,
                                        openOnFocus: true,
                                        placeholder: 'Pilih Kota/Kabupaten',
                                    });
                                    this.tom.disable();
                                    this.tom.on('change', (val) => {
                                        if (val) $wire.set('city_id', val);
                                    });
                                    $watch('$wire.cities', (items) => {
                                        this.tom.clear(true);
                                        this.tom.clearOptions();
                                        if (!items || items.length === 0) {
                                            this.tom.disable();
                                            return;
                                        }
                                        items.forEach(item => {
                                            this.tom.addOption({ value: item.value, text: item.text });
                                        });
                                        this.tom.enable();
                                        this.tom.refreshOptions(false);
                                    });
                                    $watch('$wire.city_id', (val) => {
                                        if (val && this.tom.getValue() !== val) {
                                            this.tom.setValue(val, true);
                                        }
                                    });
                                }
                            }">
                                <select x-ref="selectCity" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-terra-500 focus:ring focus:ring-terra-200 transition-shadow disabled:bg-gray-50">
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['city_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="font-display flex justify-between items-center text-sm font-bold text-slate-700 mb-2">
                                <span>Kecamatan</span>
                                <span wire:loading wire:target="city_id" class="text-xs text-terra-500 animate-pulse">Memuat...</span>
                            </label>
                            <div wire:ignore x-data="{
                                tom: null,
                                init() {
                                    this.tom = new TomSelect($refs.selectDistrict, {
                                        create: false,
                                        openOnFocus: true,
                                        placeholder: 'Pilih Kecamatan',
                                    });
                                    this.tom.disable();
                                    this.tom.on('change', (val) => {
                                        if (val) $wire.set('district_id', val);
                                    });
                                    $watch('$wire.districts', (items) => {
                                        this.tom.clear(true);
                                        this.tom.clearOptions();
                                        if (!items || items.length === 0) {
                                            this.tom.disable();
                                            return;
                                        }
                                        items.forEach(item => {
                                            this.tom.addOption({ value: item.value, text: item.text });
                                        });
                                        this.tom.enable();
                                        this.tom.refreshOptions(false);
                                    });
                                    $watch('$wire.district_id', (val) => {
                                        if (val && this.tom.getValue() !== val) {
                                            this.tom.setValue(val, true);
                                        }
                                    });
                                }
                            }">
                                <select x-ref="selectDistrict" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-terra-500 focus:ring focus:ring-terra-200 transition-shadow disabled:bg-gray-50">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['district_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="font-display flex justify-between items-center text-sm font-bold text-slate-700 mb-2">
                                <span>Kelurahan / Desa</span>
                                <span wire:loading wire:target="district_id" class="text-xs text-terra-500 animate-pulse">Memuat...</span>
                            </label>
                            <div wire:ignore x-data="{
                                tom: null,
                                init() {
                                    this.tom = new TomSelect($refs.selectVillage, {
                                        create: false,
                                        openOnFocus: true,
                                        placeholder: 'Pilih Kelurahan / Desa',
                                    });
                                    this.tom.disable();
                                    this.tom.on('change', (val) => {
                                        if (val) $wire.set('village_id', val);
                                    });
                                    $watch('$wire.villages', (items) => {
                                        this.tom.clear(true);
                                        this.tom.clearOptions();
                                        if (!items || items.length === 0) {
                                            this.tom.disable();
                                            return;
                                        }
                                        items.forEach(item => {
                                            this.tom.addOption({ value: item.value, text: item.text });
                                        });
                                        this.tom.enable();
                                        this.tom.refreshOptions(false);
                                    });
                                    $watch('$wire.village_id', (val) => {
                                        if (val && this.tom.getValue() !== val) {
                                            this.tom.setValue(val, true);
                                        }
                                    });
                                }
                            }">
                                <select x-ref="selectVillage" class="w-full px-4 py-3 border border-slate-200 rounded-xl shadow-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 disabled:bg-slate-50 disabled:text-slate-500">
                                    <option value="">Pilih Kelurahan / Desa</option>
                                </select>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['village_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="font-display block text-sm font-bold text-slate-700 mb-2">Kode Pos</label>
                            <input type="text" wire:model="postal_code" <?php if($isProcessing): echo 'disabled'; endif; ?> class="w-full px-4 py-3 border border-slate-200 rounded-xl shadow-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 disabled:bg-slate-50 disabled:text-slate-500">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['postal_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-500 text-xs mt-1"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="md:col-span-2">
                            <label class="font-display block text-sm font-bold text-slate-700 mb-2">Catatan Pesanan (Opsional)</label>
                            <input type="text" wire:model="notes" <?php if($isProcessing): echo 'disabled'; endif; ?> placeholder="Contoh: Titip di pos satpam" class="w-full px-4 py-3 border border-slate-200 rounded-xl shadow-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 disabled:bg-slate-50 disabled:text-slate-500">
                        </div>
                    </div>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Ringkasan & Pembayaran -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 sticky top-24">
                    <h3 class="font-display text-fluid-h3 font-black text-slate-900 mb-6 pb-4 border-b border-gray-100">Detail Pesanan</h3>
                    
                    <ul class="divide-y divide-gray-100 mb-6">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="py-3 flex justify-between">
                            <div class="flex-1 pr-4">
                                <h4 class="font-display text-sm font-bold text-slate-800 line-clamp-1">
                                    <?php echo e($item->product?->name ?? 'Produk Tidak Tersedia'); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->variant): ?>
                                        <span class="text-slate-500 font-normal">(<?php echo e($item->variant->name); ?>)</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </h4>
                                <div class="text-xs text-slate-500"><?php echo e($item->quantity); ?> x Rp<?php echo e(number_format($item->variant ? $item->variant->final_price : ($item->product?->price ?? 0), 0, ',', '.')); ?></div>
                            </div>
                            <span class="text-sm font-medium text-slate-900 whitespace-nowrap">Rp<?php echo e(number_format($item->subtotal, 0, ',', '.')); ?></span>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                    
                    <div class="space-y-3 mb-6 bg-gray-50 p-4 rounded-lg">
                        <div class="flex justify-between text-slate-600 text-sm">
                            <span>Subtotal</span>
                            <span class="font-medium text-slate-900">Rp<?php echo e(number_format($subtotal, 0, ',', '.')); ?></span>
                        </div>
                        <div class="flex justify-between text-slate-600 text-sm">
                            <span>Ongkos Kirim</span>
                            <span class="font-medium text-slate-900">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($shippingCost > 0): ?>
                                    Rp<?php echo e(number_format($shippingCost, 0, ',', '.')); ?>

                                <?php else: ?>
                                    <span class="text-xs italic text-slate-400">(Tentukan Kota)</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-500 flex gap-1 items-start leading-normal pt-1 border-t border-dashed border-gray-100">
                            <svg class="w-3.5 h-3.5 text-terra-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Dikirim langsung menggunakan <strong>Armada Truk Pabrik</strong> dari Plered, Purwakarta (Roster dijamin aman sampai lokasi).</span>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($discountAmount > 0): ?>
                        <div class="flex justify-between text-terra-600 text-sm">
                            <span>Diskon</span>
                            <span class="font-medium">-Rp<?php echo e(number_format($discountAmount, 0, ',', '.')); ?></span>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="border-t border-gray-200 pt-3 flex justify-between items-center mt-3">
                            <span class="font-display font-bold text-slate-900">Total Tagihan</span>
                            <span class="font-display font-black text-terra-600 text-fluid-h3">Rp<?php echo e(number_format($grandTotal, 0, ',', '.')); ?></span>
                        </div>
                    </div>

                    <div class="mb-6 p-4 border border-blue-100 bg-blue-50 rounded-lg flex gap-3 text-blue-800 text-sm">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <p>Pembayaran diproses dengan aman oleh <strong>Midtrans</strong>. Mendukung QRIS, GoPay, Transfer Bank (Virtual Account), dan lainnya.</p>
                    </div>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($city_id && $totalQty < $minOrderQty): ?>
                    <div class="mb-4 p-3 bg-red-50 border border-red-100 rounded-lg flex gap-2 text-red-700 text-xs italic">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <span>Minimal belanja untuk wilayah ini adalah <?php echo e($minOrderQty); ?> pcs. Pesanan Anda saat ini baru <?php echo e($totalQty); ?> pcs.</span>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <button type="submit" wire:loading.attr="disabled" <?php if($isProcessing || ($city_id && $totalQty < $minOrderQty)): echo 'disabled'; endif; ?> class="font-display w-full flex justify-center items-center bg-slate-900 hover:bg-black text-white font-bold py-4 px-4 rounded-md shadow-lg shadow-slate-900/20 transition-all gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="processCheckout">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isProcessing): ?>
                                Menunggu Pembayaran...
                            <?php else: ?>
                                Bayar Sekarang
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </span>
                        <span wire:loading wire:target="processCheckout">Memproses...</span>
                        <svg wire:loading.remove wire:target="processCheckout" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 <?php if($isProcessing): ?> hidden <?php endif; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </button>
                </div>
            </div>
            
        </form>

    </div>

    <!-- Script to Handle Midtrans Popup -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('snap-pay', (data) => {
                const token = data.token;
                const order_id = data.order_id;
                
                // Helper function to redirect to verification page
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
                        // Redirect ke halaman verifikasi alih-alih home
                        // Supaya sistem OrderSuccess me-running pengecekan status di belakang layar
                        redirectToVerification();
                    }
                });
            });
        });
    </script>
</div>
<?php /**PATH C:\xampp\htdocs\indoroster\resources\views/livewire/checkout.blade.php ENDPATH**/ ?>