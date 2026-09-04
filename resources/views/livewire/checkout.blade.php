<div class="bg-slate-50 dark:bg-slate-950 min-h-screen py-12"
    x-data
    x-on:open-external-url.window="window.open($event.detail.url, '_blank')">
    <!-- Midtrans Snap JS (Hanya dimuat pada mode Midtrans) -->
    @if($orderMode === 'midtrans')
        @if(config('midtrans.is_production'))
            <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
        @else
            <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
        @endif
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-breadcrumb :items="[['label' => 'Keranjang Belanja', 'url' => route('cart')], ['label' => 'Checkout']]" class="!px-0 !py-0 mb-8" />
        <div class="mb-8">
            <h1 class="font-display text-fluid-h1 font-black text-slate-900 dark:text-white tracking-tight">Checkout</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2">Lengkapi data pengiriman untuk memproses pesanan Anda.</p>
        </div>

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-2xl">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="processCheckout" class="grid grid-cols-1 lg:grid-cols-3 gap-8 @if($isProcessing) opacity-75 pointer-events-none @endif">
            
            <!-- Formulir Pengiriman -->
            <div class="lg:col-span-2 space-y-6">
                
                @guest
                    <!-- Banner Persuasif Login -->
                    <div class="bg-blue-50 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900/40 rounded-2xl p-4 sm:p-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 text-blue-900 dark:text-blue-200 shadow-soft-xs">
                        <div class="flex gap-3">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h4 class="font-display font-bold text-sm text-blue-950 dark:text-white">Lebih Hemat & Mudah dengan Akun Indoroster</h4>
                                <p class="text-xs text-blue-800/90 dark:text-blue-300 mt-1 leading-relaxed">
                                    Masuk ke akun Anda untuk menggunakan alamat tersimpan, melacak pesanan otomatis, dan mendapatkan penawaran khusus.
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('login') }}" class="font-display shrink-0 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 dark:bg-terra-500 dark:hover:bg-terra-600 px-4 py-2.5 rounded-xl transition-colors">
                            Masuk / Daftar
                        </a>
                    </div>
                @endguest

                @auth
                    @if(count($savedAddresses) > 0)
                        <!-- Buku Alamat Selector -->
                        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs p-6 sm:p-8">
                            <h2 class="font-display text-fluid-h3 font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Pilih Alamat Tersimpan
                            </h2>
                            <div class="grid grid-cols-1 gap-4">
                                @foreach($savedAddresses as $addr)
                                    <label class="relative flex p-4 border rounded-xl cursor-pointer focus:outline-none transition-all duration-200 {{ $selectedAddressId == $addr->id ? 'border-terra-500 bg-terra-50/40 dark:bg-terra-500/10' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/80 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                        <input type="radio" name="selected_address" value="{{ $addr->id }}" wire:click="selectAddress({{ $addr->id }})" class="sr-only" {{ $selectedAddressId == $addr->id ? 'checked' : '' }}>
                                        <span class="flex flex-col text-left">
                                            <span class="flex items-center gap-2">
                                                <span class="font-display font-bold text-sm text-slate-900 dark:text-white">{{ $addr->label }}</span>
                                                @if($addr->is_default)
                                                    <span class="text-[10px] font-bold text-terra-600 dark:text-terra-400 bg-terra-50 dark:bg-terra-950/50 px-2 py-0.5 rounded-full border border-terra-100 dark:border-terra-900/40">Utama</span>
                                                @endif
                                                @if($addr->latitude && $addr->longitude)
                                                    <span class="text-[10px] font-bold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50 px-2 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-900/40">📍 GPS Terpasang</span>
                                                @endif
                                            </span>
                                            <span class="text-sm font-semibold text-slate-900 dark:text-slate-200 mt-1">{{ $addr->recipient_name }} ({{ $addr->phone }})</span>
                                            <span class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">{{ $addr->formatted_address }}</span>
                                        </span>
                                        @if($selectedAddressId == $addr->id)
                                            <span class="absolute top-4 right-4 text-terra-500">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            </span>
                                        @endif
                                    </label>
                                @endforeach

                                <!-- Pilihan Alamat Baru / Manual -->
                                <label class="relative flex p-4 border rounded-xl cursor-pointer focus:outline-none transition-all duration-200 {{ is_null($selectedAddressId) ? 'border-terra-500 bg-terra-50/40 dark:bg-terra-500/10' : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/80 hover:bg-slate-50 dark:hover:bg-slate-800' }}">
                                    <input type="radio" name="selected_address" value="manual" wire:click="useManualAddress" class="sr-only" {{ is_null($selectedAddressId) ? 'checked' : '' }}>
                                    <span class="flex flex-col text-left">
                                        <span class="flex items-center gap-2">
                                            <span class="font-display font-bold text-sm text-slate-900 dark:text-white">Tulis Alamat Baru / Manual</span>
                                        </span>
                                        <span class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Pilih opsi ini jika Anda ingin mengirim ke alamat baru atau mengisi alamat secara manual.</span>
                                    </span>
                                    @if(is_null($selectedAddressId))
                                        <span class="absolute top-4 right-4 text-terra-500">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    @endif
                                </label>
                            </div>

                            @if($selectedAddressId)
                                <!-- Catatan Pesanan khusus untuk alamat tersimpan -->
                                <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-800 text-left">
                                    <label class="font-display block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Catatan Pesanan (Opsional)</label>
                                    <input type="text" wire:model="notes" @disabled($isProcessing) placeholder="Contoh: Titip di pos satpam" class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-xl shadow-xs focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 disabled:bg-slate-50 dark:disabled:bg-slate-900 disabled:text-slate-500">
                                </div>
                            @endif

                            <div class="mt-4 text-right">
                                <a href="{{ route('member.addresses') }}" class="text-xs font-bold text-terra-600 dark:text-terra-400 hover:text-terra-700 dark:hover:text-terra-300 flex items-center justify-end gap-1">
                                    Kelola Buku Alamat
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                </a>
                            </div>
                        </div>
                    @endif
                @endauth

                @if(is_null($selectedAddressId))
                    <!-- Alert Validasi Data -->
                <div class="bg-amber-50 dark:bg-amber-950/40 border border-amber-200/80 dark:border-amber-900/40 rounded-2xl p-4 sm:p-5 flex gap-3.5 text-amber-900 dark:text-amber-200 shadow-soft-xs">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div>
                        <h4 class="font-display font-bold text-sm text-amber-950 dark:text-white">PENTING: Periksa Kembali Data Anda</h4>
                        <p class="text-xs text-amber-800/90 dark:text-amber-300 mt-1 leading-relaxed">
                            Mohon pastikan <strong>alamat pengiriman</strong> ditulis dengan sangat detail, serta <strong>email</strong> dan <strong>nomor WhatsApp</strong> yang Anda masukkan adalah benar dan aktif. Hal ini penting untuk keperluan pengiriman struk/invoice serta koordinasi pengiriman oleh <strong>Armada Pengiriman Pabrik Indoroster</strong>.
                        </p>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs p-6 sm:p-8">
                    <h2 class="font-display text-fluid-h3 font-bold text-slate-900 dark:text-white mb-6 pb-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Informasi Kontak
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="font-display block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nama Lengkap</label>
                            <input type="text" wire:model="name" @disabled($isProcessing) class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-xl shadow-xs focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 disabled:bg-slate-50 dark:disabled:bg-slate-900 disabled:text-slate-500">
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="font-display block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Email</label>
                            <input type="email" wire:model="email" @disabled($isProcessing) class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-xl shadow-xs focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 disabled:bg-slate-50 dark:disabled:bg-slate-900 disabled:text-slate-500">
                            <p class="text-slate-400 dark:text-slate-500 text-[11px] mt-1.5 flex items-start gap-1 leading-normal">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>Invoice PDF & bukti transaksi akan dikirimkan secara otomatis ke email ini.</span>
                            </p>
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="font-display block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Nomor HP / WhatsApp</label>
                            <input type="tel" wire:model="phone" @disabled($isProcessing) class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-xl shadow-xs focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 disabled:bg-slate-50 dark:disabled:bg-slate-900 disabled:text-slate-500">
                            <p class="text-slate-400 dark:text-slate-500 text-[11px] mt-1.5 flex items-start gap-1 leading-normal">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>Kurir akan menghubungi nomor ini saat pengantaran barang ke alamat Anda.</span>
                            </p>
                            @error('phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs p-6 sm:p-8">
                    <h2 class="font-display text-fluid-h3 font-bold text-slate-900 dark:text-white mb-6 pb-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
                        <svg class="w-6 h-6 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Alamat Pengiriman
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="font-display block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Alamat Lengkap</label>
                            <textarea wire:model="address" @disabled($isProcessing) rows="3" class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-xl shadow-xs focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 disabled:bg-slate-50 dark:disabled:bg-slate-900 disabled:text-slate-500"></textarea>
                            <p class="text-slate-400 dark:text-slate-500 text-[11px] mt-1.5 flex items-start gap-1 leading-normal">
                                <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>Tuliskan alamat lengkap secara detail (seperti nama jalan, nomor rumah, RT/RW, blok, atau patokan bangunan terdekat).</span>
                            </p>
                            @error('address') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="font-display flex justify-between items-center text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
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
                                <select x-ref="selectProvince" @disabled($isProcessing) class="w-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-xl shadow-xs focus:border-terra-500 focus:ring focus:ring-terra-200 transition-shadow disabled:bg-slate-50 dark:disabled:bg-slate-900">
                                    <option value="">Pilih Provinsi</option>
                                    @foreach($provinces as $p)
                                        <option value="{{ $p->code }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('province_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="font-display flex justify-between items-center text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
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
                                <select x-ref="selectCity" class="w-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-xl shadow-xs focus:border-terra-500 focus:ring focus:ring-terra-200 transition-shadow disabled:bg-slate-50 dark:disabled:bg-slate-900">
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                            </div>
                            @error('city_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="font-display flex justify-between items-center text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
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
                                <select x-ref="selectDistrict" class="w-full border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-xl shadow-xs focus:border-terra-500 focus:ring focus:ring-terra-200 transition-shadow disabled:bg-slate-50 dark:disabled:bg-slate-900">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                            @error('district_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="font-display flex justify-between items-center text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">
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
                                <select x-ref="selectVillage" class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-xl shadow-xs focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 disabled:bg-slate-50 dark:disabled:bg-slate-900 disabled:text-slate-500">
                                    <option value="">Pilih Kelurahan / Desa</option>
                                </select>
                            </div>
                            @error('village_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="font-display block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 flex justify-between items-center">
                                <span>Kode Pos</span>
                                <span wire:loading wire:target="district_id,village_id" class="text-[10px] text-terra-500 lowercase animate-pulse font-normal">Cek kode pos...</span>
                            </label>
                            <div class="relative">
                                <input type="text" wire:model="postal_code" list="checkout-postal-codes-list" placeholder="Contoh: 41165" @disabled($isProcessing) class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-xl shadow-xs focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 disabled:bg-slate-50 dark:disabled:bg-slate-900 disabled:text-slate-500">
                                <datalist id="checkout-postal-codes-list">
                                    @foreach($postalCodes as $code)
                                        <option value="{{ $code }}">{{ $code }}</option>
                                    @endforeach
                                </datalist>
                            </div>
                            @if(count($postalCodes) > 1)
                                <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                                    <span class="text-[11px] text-slate-500 dark:text-slate-400 font-medium">Pilihan:</span>
                                    @foreach($postalCodes as $code)
                                        <button type="button" wire:click="selectPostalCode('{{ $code }}')" class="px-2.5 py-0.5 text-xs font-semibold rounded-lg transition-all cursor-pointer {{ $postal_code == $code ? 'bg-terra-500 text-white shadow-xs' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 hover:bg-terra-100 dark:hover:bg-slate-600' }}">
                                            {{ $code }}
                                        </button>
                                    @endforeach
                                </div>
                            @elseif(count($postalCodes) === 1 && $postal_code)
                                <p class="text-[11px] text-emerald-600 dark:text-emerald-400 mt-1 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Kode pos terdeteksi otomatis
                                </p>
                            @endif
                            @error('postal_code') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Map Picker untuk Checkout Manual -->
                        <div wire:ignore class="md:col-span-2 pt-4 border-t border-slate-100 dark:border-slate-800"
                             x-data="checkoutMapHandler(@entangle('latitude'), @entangle('longitude'))"
                             x-init="initMap()">

                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3 pb-2 border-b border-slate-100 dark:border-slate-800">
                                <div>
                                    <label class="font-display block text-sm font-bold text-slate-900 dark:text-white">
                                        Titik Koordinat Pengiriman (Maps)
                                    </label>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Tentukan pin peta lokasi pembongkaran agar armada pabrik langsung menuju koordinat yang tepat.</p>
                                </div>
                                <div class="flex items-center gap-2 flex-wrap shrink-0">
                                    <button type="button" @click="syncFromAddressInput()" :disabled="isSearching" class="inline-flex items-center gap-1.5 text-xs font-bold text-white bg-terra-500 hover:bg-terra-600 px-3 py-2 rounded-xl shadow-xs transition-all cursor-pointer">
                                        <span>🎯 Arahkan ke Alamat</span>
                                    </button>
                                    <button type="button" @click="locateMe()" :disabled="isLocating" title="Mengambil posisi fisik perangkat HP/Laptop Anda" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-slate-900 dark:hover:text-white bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-600 shadow-xs transition-all cursor-pointer">
                                        <span x-show="!isLocating">📍 GPS Perangkat</span>
                                        <span x-show="isLocating" class="animate-pulse">Mencari GPS...</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Pencarian Lokasi -->
                            <div class="flex gap-2 mb-2">
                                <input type="text" x-model="searchQuery" @keydown.enter.prevent="searchLocation()" placeholder="Ketik nama jalan / kelurahan / patokan proyek..." class="w-full px-3.5 py-2 text-xs bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:border-terra-500 focus:outline-none shadow-xs">
                                <button type="button" @click="searchLocation()" :disabled="isSearching" class="px-4 py-2 bg-slate-900 hover:bg-black dark:bg-terra-500 dark:hover:bg-terra-600 text-white rounded-xl text-xs font-bold shrink-0 transition-all cursor-pointer">
                                    <span x-show="!isSearching">Cari</span>
                                    <span x-show="isSearching" class="animate-pulse">...</span>
                                </button>
                            </div>

                            <div class="flex items-start gap-2.5 p-3 bg-sky-50/90 dark:bg-sky-950/40 border border-sky-200/80 dark:border-sky-900/50 rounded-2xl text-sky-950 dark:text-sky-200 text-xs font-medium mb-3">
                                <span class="text-base leading-none mt-0.5">🗺️</span>
                                <div class="leading-relaxed">
                                    <strong>Peta Jalan Aktif:</strong> Nama jalan, gang, dan patokan kota ditampilkan jelas pada peta jalan. Setelah lokasi ditemukan, Anda dapat beralih ke tombol <strong class="text-terra-600 dark:text-terra-400">🛰️ Foto Satelit</strong> di pojok kanan atas peta untuk menaruh pin merah 📍 tepat di atas atap rumah atau gerbang proyek.
                                </div>
                            </div>

                            <div id="checkout-map-picker" style="width: 100%; height: 280px; min-height: 280px; border-radius: 1rem;" class="border-2 border-slate-300 dark:border-slate-700 shadow-inner z-0 overflow-hidden relative mb-3"></div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1">Latitude</label>
                                    <input type="text" x-model="lat" placeholder="-6.5631" class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-700 dark:text-slate-200" readonly>
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1">Longitude</label>
                                    <input type="text" x-model="lng" placeholder="107.4439" class="w-full px-3 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-700 dark:text-slate-200" readonly>
                                </div>
                            </div>

                            <!-- Status Koordinat Otomatis -->
                            <div class="flex items-center justify-between gap-3 pt-3 mt-3 border-t border-slate-100 dark:border-slate-800">
                                <div class="flex items-center gap-2">
                                    <template x-if="lat && lng">
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/50 px-3 py-1.5 rounded-xl border border-emerald-200/80 dark:border-emerald-900/50">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                            <span>Titik Koordinat Terkunci Otomatis</span>
                                        </span>
                                    </template>
                                    <template x-if="!lat || !lng">
                                        <span class="text-xs text-slate-500 dark:text-slate-400">
                                            *Klik pada peta untuk mengunci titik navigasi armada pabrik.
                                        </span>
                                    </template>
                                </div>
                                <template x-if="lat && lng">
                                    <button type="button" @click="lat = null; lng = null; if (marker) { map.removeLayer(marker); marker = null; }" class="text-xs font-bold text-slate-500 hover:text-red-500 dark:text-slate-400 dark:hover:text-red-400 transition-colors cursor-pointer">
                                        Reset Titik Peta
                                    </button>
                                </template>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="font-display block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Catatan Pesanan (Opsional)</label>
                            <input type="text" wire:model="notes" @disabled($isProcessing) placeholder="Contoh: Titip di pos satpam" class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-xl shadow-xs focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 disabled:bg-slate-50 dark:disabled:bg-slate-900 disabled:text-slate-500">
                        </div>

                        @if($totalQty >= 500)
                        <div class="md:col-span-2 bg-gradient-to-br from-amber-50 to-orange-50/50 dark:from-amber-950/30 dark:to-orange-950/20 border border-amber-200/80 dark:border-amber-900/50 rounded-2xl p-5 shadow-soft-xs">
                            <div class="flex items-start gap-3">
                                <input type="checkbox" id="reqBatch" wire:model.live="requestedBatchDelivery" class="w-5 h-5 mt-0.5 text-terra-600 rounded border-slate-300 dark:border-slate-600 focus:ring-terra-500 cursor-pointer">
                                <div class="flex-1">
                                    <label for="reqBatch" class="font-display block text-sm font-bold text-slate-900 dark:text-white cursor-pointer">
                                        🚚 Permintaan Pengiriman Bertahap (Batch Delivery) untuk Proyek
                                    </label>
                                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-1 leading-relaxed">
                                        Untuk pesanan kuantitas besar ({{ number_format($totalQty, 0, ',', '.') }} pcs), Anda dapat mengajukan jadwal pengiriman bertahap (misal per minggu) sesuai kesiapan area proyek Anda.
                                    </p>

                                    @if($requestedBatchDelivery)
                                    <div class="mt-4 pt-4 border-t border-amber-200/60 dark:border-amber-900/60 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Preferensi Pengiriman</label>
                                            <select wire:model="batchPreference" class="w-full text-sm border border-amber-300 dark:border-amber-900/80 rounded-xl px-3 py-2 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-terra-500 focus:ring-2 focus:ring-terra-500/20">
                                                <option value="tiap_minggu">Kirim Bertahap Tiap Minggu (Weekly)</option>
                                                <option value="2_tahap">Kirim dalam 2 Tahap Pengiriman</option>
                                                <option value="4_tahap">Kirim dalam 4 Tahap Pengiriman</option>
                                                <option value="8_tahap">Kirim dalam 8 Tahap Pengiriman</option>
                                                <option value="custom">Sesuai Kesepakatan Khusus Proyek</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1.5">Catatan Khusus Proyek (Opsional)</label>
                                            <input type="text" wire:model="batchCustomNotes" placeholder="Contoh: Kirim tiap Sabtu pagi @1.000 pcs" class="w-full text-sm border border-amber-300 dark:border-amber-900/80 rounded-xl px-3 py-2 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-terra-500 focus:ring-2 focus:ring-terra-500/20">
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <!-- Ringkasan & Pembayaran -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs p-6 sticky top-24">
                    <h3 class="font-display text-fluid-h3 font-black text-slate-900 dark:text-white mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">Detail Pesanan</h3>
                    
                    <ul class="divide-y divide-slate-100 dark:divide-slate-800 mb-6">
                        @foreach($cartItems as $item)
                        <li class="py-3 flex justify-between">
                            <div class="flex-1 pr-4">
                                <h4 class="font-display text-sm font-bold text-slate-800 dark:text-white line-clamp-1">
                                    {{ $item->product?->name ?? 'Produk Tidak Tersedia' }}
                                    @if($item->variant)
                                        <span class="text-slate-500 dark:text-slate-400 font-normal">({{ $item->variant->name }})</span>
                                    @endif
                                </h4>
                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $item->quantity }} x Rp{{ number_format($item->variant ? $item->variant->final_price : ($item->product?->price ?? 0), 0, ',', '.') }}</div>
                            </div>
                            <span class="text-sm font-medium text-slate-900 dark:text-white whitespace-nowrap">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </li>
                        @endforeach
                    </ul>
                    
                    <!-- Voucher & Promo Klaim Box -->
                    <div class="mb-5 p-3.5 bg-amber-50/50 dark:bg-slate-800/90 rounded-xl border border-amber-200/80 dark:border-slate-700">
                        <label class="block text-xs font-bold text-slate-800 dark:text-amber-200 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                            <span>🏷️</span>
                            <span>Punya Kode Voucher / Promo Wilayah?</span>
                        </label>
                        @if($appliedVoucher)
                            <div class="flex items-center justify-between p-2.5 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <span class="text-emerald-600 dark:text-emerald-400 font-bold text-xs">✓</span>
                                    <div>
                                        <div class="font-mono text-xs font-black text-emerald-800 dark:text-emerald-300 uppercase">{{ $appliedVoucher->code }}</div>
                                        <div class="text-[10px] text-emerald-600 dark:text-emerald-400">{{ $appliedVoucher->name }}</div>
                                    </div>
                                </div>
                                <button type="button" wire:click="removeVoucher" class="text-xs text-red-500 hover:text-red-700 font-bold px-2 py-1 rounded cursor-pointer">
                                    Hapus
                                </button>
                            </div>
                        @else
                            <div class="flex gap-2">
                                <input type="text" wire:model="voucherCode" placeholder="Masukkan Kode Voucher" class="flex-1 uppercase font-mono text-xs px-3 py-2 border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-slate-800 dark:text-white focus:ring-2 focus:ring-terra-500 focus:outline-none">
                                <button type="button" wire:click="applyVoucher" class="px-3.5 py-2 bg-slate-900 dark:bg-terra-500 hover:bg-black dark:hover:bg-terra-600 text-white font-bold text-xs rounded-lg transition-all cursor-pointer shrink-0">
                                    Terapkan
                                </button>
                            </div>
                        @endif

                        @if($voucherMessage)
                            <div class="mt-2 text-[11px] font-semibold text-emerald-600 dark:text-emerald-400">
                                {{ $voucherMessage }}
                            </div>
                        @endif

                        @if($voucherError)
                            <div class="mt-2 text-[11px] font-semibold text-red-500 dark:text-red-400">
                                {{ $voucherError }}
                            </div>
                        @endif
                    </div>

                    <div class="space-y-3 mb-6 bg-slate-50 dark:bg-slate-800/80 p-4 rounded-xl border border-slate-100 dark:border-slate-700">
                        <div class="flex justify-between text-slate-600 dark:text-slate-300 text-sm">
                            <span>Subtotal Produk</span>
                            <span class="font-medium text-slate-900 dark:text-white">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600 dark:text-slate-300 text-sm">
                            <span>Ongkos Kirim Armada</span>
                            <span class="font-medium text-slate-900 dark:text-white text-right">
                                @if($shippingCost > 0)
                                    Rp{{ number_format($shippingCost, 0, ',', '.') }}
                                    @if($shippingRateType === 'per_pcs')
                                        <span class="block text-[11px] font-normal text-slate-500 dark:text-slate-400">
                                            (Rp{{ number_format($shippingCostPerUnit, 0, ',', '.') }}/pcs × {{ number_format($totalQty, 0, ',', '.') }} pcs)
                                        </span>
                                    @endif
                                @elseif($city_id)
                                    <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50 px-2 py-0.5 rounded-full border border-emerald-200 dark:border-emerald-800">Dikonfirmasi via WA</span>
                                @else
                                    <span class="text-xs italic text-slate-400 dark:text-slate-500">(Pilih Kota Tujuan)</span>
                                @endif
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-500 dark:text-slate-400 flex gap-1 items-start leading-normal pt-1 border-t border-dashed border-slate-200 dark:border-slate-700">
                            <svg class="w-3.5 h-3.5 text-terra-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Dikirim langsung menggunakan <strong>Armada Truk Pabrik</strong> dari Plered, Purwakarta (Roster dijamin 100% aman sampai lokasi).</span>
                        </div>
                        @if($discountAmount > 0)
                        <div class="flex justify-between text-terra-600 dark:text-terra-400 text-sm">
                            <span>Diskon Voucher</span>
                            <span class="font-medium">-Rp{{ number_format($discountAmount, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="border-t border-slate-200 dark:border-slate-700 pt-3 flex justify-between items-center mt-3">
                            <span class="font-display font-bold text-slate-900 dark:text-white">{{ ($shippingCost > 0 || $orderMode !== 'whatsapp') ? 'Total Tagihan' : 'Total Harga Barang' }}</span>
                            <span class="font-display font-black text-terra-600 dark:text-terra-400 text-fluid-h3">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($orderMode === 'whatsapp')
                        <!-- WhatsApp Order Info Banner -->
                        <div class="mb-6 p-4 border border-emerald-200 dark:border-emerald-800/60 bg-emerald-50/80 dark:bg-emerald-950/40 rounded-2xl flex gap-3 text-emerald-900 dark:text-emerald-200 text-xs leading-relaxed shadow-soft-xs">
                            <div class="w-8 h-8 rounded-xl bg-emerald-500/10 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 mt-0.5 font-bold">
                                💬
                            </div>
                            <div>
                                <span class="font-display font-bold block text-sm mb-1 text-emerald-950 dark:text-white">Pemesanan Terhubung ke WhatsApp</span>
                                <p class="text-emerald-800/90 dark:text-emerald-300">
                                    Pesanan Anda otomatis tercatat resmi di database IndoRoster. Browser akan langsung membuka WhatsApp Admin beserta <strong>daftar barang, link produk, dan link titik peta Google Maps</strong> untuk konfirmasi ongkir armada & jadwal kirim.
                                </p>
                            </div>
                        </div>
                    @else
                        <!-- Midtrans Info Banner -->
                        <div class="mb-6 p-4 border border-blue-100 dark:border-blue-900/40 bg-blue-50 dark:bg-blue-950/40 rounded-xl flex gap-3 text-blue-800 dark:text-blue-300 text-sm">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <p>Pembayaran diproses dengan aman oleh <strong>Midtrans</strong>. Mendukung QRIS, GoPay, Transfer Bank (Virtual Account), dan lainnya.</p>
                        </div>
                    @endif
                    
                    @if($city_id && $minOrderQty > 0 && $totalQty < $minOrderQty)
                    <div class="mb-4 p-3.5 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900/50 rounded-xl flex gap-2.5 text-red-700 dark:text-red-300 text-xs">
                        <svg class="w-5 h-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <div>
                            <span class="font-bold block">Kuantitas di bawah batas minimal armada wilayah:</span>
                            <span>Minimal pemesanan armada untuk wilayah ini adalah <strong>{{ number_format($minOrderQty, 0, ',', '.') }} pcs</strong> (Pesanan Anda saat ini: {{ number_format($totalQty, 0, ',', '.') }} pcs). Silakan tambah kuantitas belanja Anda.</span>
                        </div>
                    </div>
                    @endif

                    @if($orderMode === 'whatsapp')
                        <button type="submit" wire:loading.attr="disabled" @disabled($isProcessing || ($city_id && $minOrderQty > 0 && $totalQty < $minOrderQty)) class="font-display w-full flex justify-center items-center bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 text-white font-bold py-4 px-4 rounded-xl shadow-lg shadow-emerald-600/25 transition-all gap-2.5 disabled:opacity-60 disabled:cursor-not-allowed cursor-pointer text-base"
                            x-on:click="
                                const mapEl = document.querySelector('[x-data^=\'checkoutMapHandler\']');
                                if (mapEl && mapEl._x_dataStack) {
                                    const mapData = mapEl._x_dataStack[0];
                                    if (mapData.lat) $wire.set('latitude', parseFloat(mapData.lat));
                                    if (mapData.lng) $wire.set('longitude', parseFloat(mapData.lng));
                                }
                            ">
                            <span wire:loading.remove wire:target="processCheckout, processWhatsappCheckout" class="flex items-center gap-2">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                <span>Kirim Pesanan ke WhatsApp</span>
                            </span>
                            <span wire:loading wire:target="processCheckout, processWhatsappCheckout" class="flex items-center gap-2.5">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="font-bold tracking-wide">Memproses Pesanan & Membuka WhatsApp...</span>
                            </span>
                        </button>
                    @else
                        <button type="submit" wire:loading.attr="disabled" @disabled($isProcessing || ($city_id && $totalQty < $minOrderQty)) class="font-display w-full flex justify-center items-center bg-slate-900 dark:bg-terra-500 hover:bg-black dark:hover:bg-terra-600 text-white font-bold py-4 px-4 rounded-xl shadow-lg shadow-slate-900/20 transition-all gap-2 disabled:opacity-70 disabled:cursor-not-allowed cursor-pointer"
                            x-on:click="
                                const mapEl = document.querySelector('[x-data^=\'checkoutMapHandler\']');
                                if (mapEl && mapEl._x_dataStack) {
                                    const mapData = mapEl._x_dataStack[0];
                                    if (mapData.lat) $wire.set('latitude', parseFloat(mapData.lat));
                                    if (mapData.lng) $wire.set('longitude', parseFloat(mapData.lng));
                                }
                            ">
                            <span wire:loading.remove wire:target="processCheckout">
                                @if($isProcessing)
                                    Menunggu Pembayaran...
                                @else
                                    Bayar Sekarang
                                @endif
                            </span>
                            <span wire:loading wire:target="processCheckout">Memproses...</span>
                            <svg wire:loading.remove wire:target="processCheckout" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 @if($isProcessing) hidden @endif" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </button>
                    @endif
                </div>
            </div>
            
        </form>

    <!-- Script to Handle Midtrans Popup & Map Picker -->
    <script>
        function checkoutMapHandler(latRef, lngRef) {
            return {
                lat: latRef,
                lng: lngRef,
                searchQuery: '',
                isSearching: false,
                isLocating: false,
                map: null,
                marker: null,
                initMap() {
                    const initialLat = parseFloat(this.lat) || -6.6689917;
                    const initialLng = parseFloat(this.lng) || 107.3619295;
                    const initialZoom = (this.lat && this.lng) ? 15 : 12;

                    setTimeout(() => {
                        const mapEl = document.getElementById('checkout-map-picker');
                        if (!mapEl || typeof L === 'undefined') return;

                        if (this.map) {
                            this.map.remove();
                            this.map = null;
                        }

                        this.map = L.map('checkout-map-picker', {
                            center: [initialLat, initialLng],
                            zoom: initialZoom,
                            zoomControl: true
                        });

                        const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                            attribution: '&copy; OpenStreetMap'
                        });

                        const satTiles = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                            maxZoom: 19,
                            attribution: 'Tiles &copy; Esri'
                        });
                        const satLabels = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                            maxZoom: 19
                        });
                        const satelliteLayer = L.layerGroup([satTiles, satLabels]);

                        // Default aktifkan Peta Jalan (Jelas Nama Jalan & Gang)
                        streetLayer.addTo(this.map);

                        L.control.layers({
                            "🗺️ Peta Jalan (Nama Jalan Jelas)": streetLayer,
                            "🛰️ Foto Satelit (Atap Rumah)": satelliteLayer
                        }, null, { position: 'topright' }).addTo(this.map);

                        const getPinIcon = () => {
                            return L.divIcon({
                                className: 'custom-pin-marker',
                                html: '<div style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: 32px; line-height: 1; filter: drop-shadow(0 3px 6px rgba(0,0,0,0.45)); cursor: grab; user-select: none;">📍</div>',
                                iconSize: [36, 36],
                                iconAnchor: [18, 34]
                            });
                        };

                        if (this.lat && this.lng) {
                            this.marker = L.marker([initialLat, initialLng], { icon: getPinIcon(), draggable: true }).addTo(this.map);
                            this.marker.on('dragend', (e) => {
                                const pos = e.target.getLatLng();
                                this.lat = pos.lat.toFixed(7);
                                this.lng = pos.lng.toFixed(7);
                            });
                        }

                        this.map.on('click', (e) => {
                            const { lat, lng } = e.latlng;
                            this.lat = lat.toFixed(7);
                            this.lng = lng.toFixed(7);
                            if (this.marker) {
                                this.marker.setLatLng([lat, lng]);
                            } else {
                                this.marker = L.marker([lat, lng], { icon: getPinIcon(), draggable: true }).addTo(this.map);
                                this.marker.on('dragend', (ev) => {
                                    const pos = ev.target.getLatLng();
                                    this.lat = pos.lat.toFixed(7);
                                    this.lng = pos.lng.toFixed(7);
                                });
                            }
                        });

                        this.map.invalidateSize();
                        setTimeout(() => { if (this.map) this.map.invalidateSize(); }, 250);
                    }, 150);
                },
                setLocationOnMap(lat, lng, zoomLevel = 17) {
                    this.lat = parseFloat(lat).toFixed(7);
                    this.lng = parseFloat(lng).toFixed(7);

                    if (this.map && typeof L !== 'undefined') {
                        this.map.setView([lat, lng], zoomLevel);
                        const pinIcon = L.divIcon({
                            className: 'custom-pin-marker',
                            html: '<div style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: 32px; line-height: 1; filter: drop-shadow(0 3px 6px rgba(0,0,0,0.45)); cursor: grab; user-select: none;">📍</div>',
                            iconSize: [36, 36],
                            iconAnchor: [18, 34]
                        });

                        if (this.marker) {
                            this.marker.setLatLng([lat, lng]);
                        } else {
                            this.marker = L.marker([lat, lng], { icon: pinIcon, draggable: true }).addTo(this.map);
                            this.marker.on('dragend', (ev) => {
                                const p = ev.target.getLatLng();
                                this.lat = p.lat.toFixed(7);
                                this.lng = p.lng.toFixed(7);
                            });
                        }
                    }
                },
                syncFromAddressInput() {
                    const fullAddr = (this.$wire.get('address') || document.querySelector('textarea[wire\\:model="address"]')?.value || '').trim();

                    const getCleanName = (txt) => {
                        if (!txt) return '';
                        return txt.replace(/^(PROVINSI|DKI|DI|KABUPATEN|KOTA|KAB\.|KECAMATAN|KEC\.|DESA|KELURAHAN|KEL\.|DESA\/KELURAHAN)\s+/i, '').trim();
                    };

                    // 1. Ambil Nama Provinsi
                    let provName = '';
                    const pEl = document.querySelector('select[x-ref="selectProvince"]') || document.querySelector('select[wire\\:model\\.live="province_id"]');
                    if (pEl && pEl.selectedIndex > 0 && pEl.options[pEl.selectedIndex]) {
                        provName = getCleanName(pEl.options[pEl.selectedIndex].text);
                    }

                    // 2. Ambil Nama Kota
                    let cityName = '';
                    const cEl = document.querySelector('select[x-ref="selectCity"]');
                    if (cEl && cEl.tomselect) {
                        const val = cEl.tomselect.getValue();
                        if (val && cEl.tomselect.options && cEl.tomselect.options[val]) {
                            cityName = getCleanName(cEl.tomselect.options[val].text);
                        }
                    }
                    if (!cityName && this.$wire.get('cities')) {
                        const cVal = this.$wire.get('city_id');
                        const cItem = (this.$wire.get('cities') || []).find(x => x.value == cVal);
                        if (cItem) cityName = getCleanName(cItem.text);
                    }
                    if (!cityName && cEl && cEl.selectedIndex > 0 && cEl.options[cEl.selectedIndex]) {
                        cityName = getCleanName(cEl.options[cEl.selectedIndex].text);
                    }

                    // 3. Ambil Nama Kecamatan
                    let distName = '';
                    const dEl = document.querySelector('select[x-ref="selectDistrict"]');
                    if (dEl && dEl.tomselect) {
                        const val = dEl.tomselect.getValue();
                        if (val && dEl.tomselect.options && dEl.tomselect.options[val]) {
                            distName = getCleanName(dEl.tomselect.options[val].text);
                        }
                    }
                    if (!distName && this.$wire.get('districts')) {
                        const dVal = this.$wire.get('district_id');
                        const dItem = (this.$wire.get('districts') || []).find(x => x.value == dVal);
                        if (dItem) distName = getCleanName(dItem.text);
                    }
                    if (!distName && dEl && dEl.selectedIndex > 0 && dEl.options[dEl.selectedIndex]) {
                        distName = getCleanName(dEl.options[dEl.selectedIndex].text);
                    }

                    // 4. Ambil Nama Kelurahan / Desa
                    let villName = '';
                    const vEl = document.querySelector('select[x-ref="selectVillage"]');
                    if (vEl && vEl.tomselect) {
                        const val = vEl.tomselect.getValue();
                        if (val && vEl.tomselect.options && vEl.tomselect.options[val]) {
                            villName = getCleanName(vEl.tomselect.options[val].text);
                        }
                    }
                    if (!villName && this.$wire.get('villages')) {
                        const vVal = this.$wire.get('village_id');
                        const vItem = (this.$wire.get('villages') || []).find(x => x.value == vVal);
                        if (vItem) villName = getCleanName(vItem.text);
                    }
                    if (!villName && vEl && vEl.selectedIndex > 0 && vEl.options[vEl.selectedIndex]) {
                        villName = getCleanName(vEl.options[vEl.selectedIndex].text);
                    }

                    // 5. Ambil Kode Pos
                    let postalCode = (this.$wire.get('postal_code') || document.querySelector('input[wire\\:model="postal_code"]')?.value || '').trim();

                    // Ekstrak teks alamat bersih
                    const cleanAddr = fullAddr
                        .replace(/\r?\n/g, ', ')
                        .replace(/rt\s*\d+\s*(\/|rw)?\s*\d+/gi, '')
                        .replace(/rt\s*\d+|rw\s*\d+/gi, '')
                        .replace(/no\.?\s*\d+/gi, '')
                        .replace(/blok\s*\w+/gi, '')
                        .replace(/,\s*,/g, ',')
                        .replace(/^[\s,]+|[\s,]+$/g, '')
                        .trim();

                    // Susun keyword baku gabungan lengkap dari semua form
                    const completeAddressText = [
                        fullAddr.replace(/\r?\n/g, ', '),
                        villName,
                        distName,
                        cityName,
                        postalCode,
                        provName
                    ].filter(Boolean).join(', ');

                    // Set langsung keyword pencarian peta ke alamat lengkap terisi
                    this.searchQuery = completeAddressText;

                    let queries = [];

                    // 1. Gabungan Lengkap: Alamat Lengkap + Desa + Kecamatan + Kota + Kode Pos + Provinsi
                    if (cleanAddr || villName || cityName) {
                        queries.push([cleanAddr, villName, distName, cityName, postalCode, provName].filter(Boolean).join(', '));
                        queries.push([cleanAddr, villName, distName, cityName, provName].filter(Boolean).join(', '));
                        queries.push([cleanAddr, villName, cityName].filter(Boolean).join(', '));
                    }

                    // 2. Alamat Lengkap + Kota
                    if (cleanAddr && cityName) {
                        queries.push(`${cleanAddr}, ${cityName}`);
                    }

                    // 3. Desa + Kecamatan + Kota + Provinsi
                    if (villName && distName && cityName) {
                        queries.push(`${villName}, ${distName}, ${cityName}, ${provName}`);
                        queries.push(`${villName}, ${distName}, ${cityName}`);
                        queries.push(`${villName}, ${cityName}`);
                    } else if (villName && cityName) {
                        queries.push(`${villName}, ${cityName}`);
                    }

                    // 4. Kecamatan + Kota + Provinsi
                    if (distName && cityName) {
                        queries.push(`${distName}, ${cityName}, ${provName}`);
                        queries.push(`${distName}, ${cityName}`);
                    }

                    // 5. Kota + Provinsi
                    if (cityName) {
                        queries.push(`${cityName}, ${provName}`);
                        queries.push(cityName);
                    }

                    // Hapus duplikasi query
                    queries = [...new Set(queries.filter(q => q && q.trim().length > 0))];

                    if (queries.length === 0) {
                        alert('Silakan pilih Kota atau isi Alamat terlebih dahulu.');
                        return;
                    }

                    this.isSearching = true;

                    const tryQuery = (index) => {
                        if (index >= queries.length) {
                            this.isSearching = false;
                            alert('Lokasi spesifik belum ditemukan otomatis. Silakan geser pin merah 📍 ke lokasi rumah/proyek Anda.');
                            return;
                        }

                        const queryText = queries[index];
                        const encodedQ = encodeURIComponent(queryText);

                        // Helper fetcher bertingkat
                        fetch(`/api/geocode?q=${encodedQ}`)
                            .then(res => {
                                if (!res.ok) throw new Error('API proxy error');
                                return res.json();
                            })
                            .then(data => {
                                if (data && data.length > 0 && data[0].lat && data[0].lon) {
                                    this.isSearching = false;
                                    const zoom = index <= 2 ? 16 : (index <= 4 ? 15 : 13);
                                    this.setLocationOnMap(data[0].lat, data[0].lon, zoom);
                                    this.searchQuery = completeAddressText;
                                } else {
                                    // Coba fallback langsung ke Photon Komoot jika internal kosong
                                    return fetch(`https://photon.komoot.io/api/?q=${encodedQ}&limit=1`)
                                        .then(pRes => pRes.json())
                                        .then(pData => {
                                            if (pData && pData.features && pData.features.length > 0) {
                                                this.isSearching = false;
                                                const coords = pData.features[0].geometry.coordinates;
                                                const zoom = index <= 2 ? 16 : (index <= 4 ? 15 : 13);
                                                this.setLocationOnMap(coords[1], coords[0], zoom);
                                                this.searchQuery = completeAddressText;
                                            } else {
                                                tryQuery(index + 1);
                                            }
                                        })
                                        .catch(() => tryQuery(index + 1));
                                }
                            })
                            .catch(() => {
                                // Fallback jika endpoint lokal bermasalah
                                fetch(`https://photon.komoot.io/api/?q=${encodedQ}&limit=1`)
                                    .then(pRes => pRes.json())
                                    .then(pData => {
                                        if (pData && pData.features && pData.features.length > 0) {
                                            this.isSearching = false;
                                            const coords = pData.features[0].geometry.coordinates;
                                            const zoom = index <= 2 ? 16 : (index <= 4 ? 15 : 13);
                                            this.setLocationOnMap(coords[1], coords[0], zoom);
                                            this.searchQuery = completeAddressText;
                                        } else {
                                            tryQuery(index + 1);
                                        }
                                    })
                                    .catch(() => tryQuery(index + 1));
                            });
                    };

                    tryQuery(0);
                },
                locateMe() {
                    if (!navigator.geolocation) {
                        alert('Browser Anda tidak mendukung deteksi GPS.');
                        return;
                    }
                    this.isLocating = true;
                    
                    const geoOptions = {
                        enableHighAccuracy: true,
                        timeout: 15000,
                        maximumAge: 0
                    };

                    navigator.geolocation.getCurrentPosition((pos) => {
                        this.isLocating = false;
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        const acc = pos.coords.accuracy ? Math.round(pos.coords.accuracy) : null;

                        this.setLocationOnMap(lat, lng, 18);

                        if (this.accuracyCircle && this.map) {
                            this.map.removeLayer(this.accuracyCircle);
                            this.accuracyCircle = null;
                        }
                        if (acc && acc > 5 && this.map) {
                            this.accuracyCircle = L.circle([lat, lng], {
                                radius: acc,
                                color: '#3b82f6',
                                fillColor: '#3b82f6',
                                fillOpacity: 0.15,
                                weight: 1.5,
                                dashArray: '4, 4'
                            }).addTo(this.map);
                        }

                        fetch(`/api/geocode/reverse?lat=${lat}&lon=${lng}`)
                            .then(r => r.json())
                            .then(data => {
                                if (data && data.display_name) {
                                    this.searchQuery = data.display_name;
                                }
                            })
                            .catch(() => {});

                    }, (err) => {
                        this.isLocating = false;
                        let msg = 'Gagal mendeteksi lokasi GPS.';
                        if (err.code === 1) {
                            msg = 'Izin lokasi (GPS) ditolak oleh browser/HP Anda. Silakan aktifkan izin akses lokasi pada pengaturan browser lalu coba lagi.';
                        } else if (err.code === 2) {
                            msg = 'Sinyal GPS tidak ditemukan. Pastikan Layanan Lokasi (GPS) di HP sudah aktif dalam mode Akurasi Tinggi.';
                        } else if (err.code === 3) {
                            msg = 'Waktu pencarian sinyal GPS habis. Silakan klik tombol "GPS Perangkat" kembali atau gunakan tombol "Arahkan ke Alamat".';
                        }
                        alert(msg);
                    }, geoOptions);
                },
                searchLocation() {
                    if (!this.searchQuery || this.searchQuery.trim().length < 3) {
                        alert('Ketik minimal 3 huruf nama jalan/daerah/kota.');
                        return;
                    }
                    this.isSearching = true;
                    const q = encodeURIComponent(this.searchQuery.trim());
                    
                    fetch(`/api/geocode?q=${q}`)
                        .then(res => res.json())
                        .then(data => {
                            if (data && data.length > 0 && data[0].lat && data[0].lon) {
                                this.isSearching = false;
                                this.setLocationOnMap(data[0].lat, data[0].lon, 15);
                            } else {
                                // Fallback ke Photon
                                return fetch(`https://photon.komoot.io/api/?q=${q}&limit=1`)
                                    .then(pRes => pRes.json())
                                    .then(pData => {
                                        this.isSearching = false;
                                        if (pData && pData.features && pData.features.length > 0) {
                                            const coords = pData.features[0].geometry.coordinates;
                                            this.setLocationOnMap(coords[1], coords[0], 15);
                                        } else {
                                            alert('Lokasi tidak ditemukan. Coba gunakan nama desa/kecamatan atau kota.');
                                        }
                                    });
                            }
                        })
                        .catch(err => {
                            this.isSearching = false;
                            alert('Gagal mencari lokasi: ' + err.message);
                        });
                }
            };
        }

        document.addEventListener('livewire:initialized', () => {
            Livewire.on('open-wa-order', (payload) => {
                const data = Array.isArray(payload) ? (payload[0] || {}) : (payload || {});
                if (data && data.waUrl) {
                    try {
                        const waLink = document.createElement('a');
                        waLink.href = data.waUrl;
                        waLink.target = '_blank';
                        waLink.rel = 'noopener noreferrer';
                        document.body.appendChild(waLink);
                        waLink.click();
                        document.body.removeChild(waLink);
                    } catch (e) {
                        window.open(data.waUrl, '_blank');
                    }
                }
                if (data && data.trackingUrl) {
                    setTimeout(() => {
                        window.location.href = data.trackingUrl;
                    }, 400);
                }
            });

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
