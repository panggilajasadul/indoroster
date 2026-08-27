<div class="bg-slate-50 dark:bg-slate-950 min-h-screen py-10 sm:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Header -->
        <div class="mb-8">
            <nav class="flex text-xs font-semibold text-slate-400 dark:text-slate-500 gap-1.5 mb-2 uppercase tracking-wider">
                <a href="{{ route('home') }}" class="hover:text-slate-600 dark:hover:text-slate-300 transition-colors">Home</a>
                <span>/</span>
                <span class="text-slate-600 dark:text-slate-300">Profil & Kemitraan</span>
            </nav>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display text-fluid-h1 font-black text-slate-900 dark:text-white tracking-tight">Profil & Informasi Kemitraan</h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">Lengkapi data akun dan lokasi proyek Anda untuk kemudahan penawaran harga pabrik & pengiriman armada.</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('member.addresses') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all shadow-2xs">
                        <span>📍 Buku Alamat</span>
                    </a>
                    <a href="{{ route('member.orders') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-700 dark:text-slate-200 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-all shadow-2xs">
                        <span>📦 Riwayat Pesanan</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/40 text-emerald-800 dark:text-emerald-300 rounded-2xl flex items-center gap-3 shadow-2xs">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-bold">{{ session('success') }}</p>
            </div>
        @endif

        <form wire:submit.prevent="save" class="space-y-8">
            <!-- 1. IDENTITAS AKUN & TIPE KEMITRAAN -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-xl p-6 sm:p-8 space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-10 h-10 rounded-2xl bg-terra-50 dark:bg-terra-950/50 flex items-center justify-center text-terra-600 dark:text-terra-400 font-bold">
                        👤
                    </div>
                    <div>
                        <h2 class="font-display text-lg font-black text-slate-900 dark:text-white">Identitas & Profil Kemitraan</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Pilih kategori profil Anda untuk mendapatkan penawaran & katalog yang relevan.</p>
                    </div>
                </div>

                <!-- Pilihan Tipe Pembeli / Kemitraan (Cards) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-3">
                        Kategori Kebutuhan / Profesi Anda <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        <!-- Option 1: Individual -->
                        <label class="relative flex items-center p-3.5 rounded-2xl border-2 cursor-pointer transition-all {{ $customer_type === 'individual' ? 'border-terra-500 bg-terra-50/40 dark:bg-terra-950/20' : 'border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700' }}">
                            <input type="radio" wire:model.live="customer_type" value="individual" class="sr-only">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">🏠</span>
                                <div>
                                    <p class="text-xs font-bold text-slate-900 dark:text-white">Pemilik Rumah / Pribadi</p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Renovasi pagar, fasad & taman</p>
                                </div>
                            </div>
                        </label>

                        <!-- Option 2: Contractor -->
                        <label class="relative flex items-center p-3.5 rounded-2xl border-2 cursor-pointer transition-all {{ $customer_type === 'contractor' ? 'border-terra-500 bg-terra-50/40 dark:bg-terra-950/20' : 'border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700' }}">
                            <input type="radio" wire:model.live="customer_type" value="contractor" class="sr-only">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">🏗️</span>
                                <div>
                                    <p class="text-xs font-bold text-slate-900 dark:text-white">Kontraktor & Pemborong</p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Proyek ruko, gedung, hunian</p>
                                </div>
                            </div>
                        </label>

                        <!-- Option 3: Architect -->
                        <label class="relative flex items-center p-3.5 rounded-2xl border-2 cursor-pointer transition-all {{ $customer_type === 'architect' ? 'border-terra-500 bg-terra-50/40 dark:bg-terra-950/20' : 'border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700' }}">
                            <input type="radio" wire:model.live="customer_type" value="architect" class="sr-only">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">📐</span>
                                <div>
                                    <p class="text-xs font-bold text-slate-900 dark:text-white">Arsitek & Desainer Interior</p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Konsultan desain arsitektural</p>
                                </div>
                            </div>
                        </label>

                        <!-- Option 4: Commercial / Cafe -->
                        <label class="relative flex items-center p-3.5 rounded-2xl border-2 cursor-pointer transition-all {{ $customer_type === 'commercial' ? 'border-terra-500 bg-terra-50/40 dark:bg-terra-950/20' : 'border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700' }}">
                            <input type="radio" wire:model.live="customer_type" value="commercial" class="sr-only">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">☕</span>
                                <div>
                                    <p class="text-xs font-bold text-slate-900 dark:text-white">Kafe, Resto & Bisnis</p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Dekorasi ruang komersial</p>
                                </div>
                            </div>
                        </label>

                        <!-- Option 5: Developer -->
                        <label class="relative flex items-center p-3.5 rounded-2xl border-2 cursor-pointer transition-all {{ $customer_type === 'developer' ? 'border-terra-500 bg-terra-50/40 dark:bg-terra-950/20' : 'border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700' }}">
                            <input type="radio" wire:model.live="customer_type" value="developer" class="sr-only">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">🏢</span>
                                <div>
                                    <p class="text-xs font-bold text-slate-900 dark:text-white">Developer Perumahan</p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Pengadaan klaster & kawasan</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Nama Lengkap / PIC Proyek <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="name" class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-4 focus:ring-terra-500/10 focus:border-terra-500">
                        @error('name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nama Perusahaan / Studio (Jika bukan perorangan) -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Nama Perusahaan / Studio / Bisnis (Opsional)
                        </label>
                        <input type="text" wire:model="company_name" placeholder="Contoh: PT Sinar Mandiri / Studio Reka" class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-4 focus:ring-terra-500/10 focus:border-terra-500">
                        @error('company_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Nomor WhatsApp Aktif <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" wire:model="phone" placeholder="0812XXXXXXXX" class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-4 focus:ring-terra-500/10 focus:border-terra-500">
                        <p class="text-[11px] text-slate-400 mt-1">Digunakan admin pabrik untuk koordinasi surat jalan & pengiriman truk.</p>
                        @error('phone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email Akun -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Alamat Email (Terdaftar)
                        </label>
                        <input type="email" value="{{ $email }}" disabled class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800/50 text-slate-500 dark:text-slate-400 cursor-not-allowed">
                    </div>
                </div>
            </div>

            <!-- 2. ALAMAT UTAMA / LOKASI PROYEK PENGIRIMAN -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-xl p-6 sm:p-8 space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/50 flex items-center justify-center text-amber-600 dark:text-amber-400 font-bold">
                        🚚
                    </div>
                    <div>
                        <h2 class="font-display text-lg font-black text-slate-900 dark:text-white">Alamat Lokasi Proyek & Pengiriman Utama</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Data ini otomatis terisi saat Anda memesan produk roster di IndoRoster.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <!-- Label Alamat -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Label Lokasi</label>
                        <input type="text" wire:model="label" placeholder="Misal: Lokasi Proyek / Rumah" class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-4 focus:ring-terra-500/10 focus:border-terra-500">
                    </div>

                    <!-- Nama Penerima Lapangan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Penerima di Lapangan</label>
                        <input type="text" wire:model="recipient_name" placeholder="Nama mandor / penerima" class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-4 focus:ring-terra-500/10 focus:border-terra-500">
                    </div>

                    <!-- No HP Penerima Lapangan -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">No. HP Penerima</label>
                        <input type="tel" wire:model="address_phone" placeholder="0812XXXXXXXX" class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-4 focus:ring-terra-500/10 focus:border-terra-500">
                    </div>
                </div>

                <!-- Dropdown Wilayah -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Provinsi</label>
                        <select wire:model.live="province_id" class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-4 focus:ring-terra-500/10 focus:border-terra-500">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->code }}">{{ $prov->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Kota / Kabupaten</label>
                        <select wire:model.live="city_id" class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-4 focus:ring-terra-500/10 focus:border-terra-500" {{ empty($cities) ? 'disabled' : '' }}>
                            <option value="">-- Pilih Kota/Kabupaten --</option>
                            @foreach($cities as $c)
                                <option value="{{ $c->code }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">Kecamatan</label>
                        <select wire:model.live="district_id" class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-4 focus:ring-terra-500/10 focus:border-terra-500" {{ empty($districts) ? 'disabled' : '' }}>
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($districts as $d)
                                <option value="{{ $d->code }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Alamat Lengkap & Catatan Armada -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Alamat Lengkap & Patokan Jalan
                        </label>
                        <textarea wire:model="full_address" rows="3" placeholder="Nama jalan, nomor bangunan, RT/RW, patokan lokasi..." class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-4 focus:ring-terra-500/10 focus:border-terra-500"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Catatan Akses Jalan Truk Armada Pabrik (Opsional)
                        </label>
                        <input type="text" wire:model="truck_access_notes" placeholder="Contoh: Akses jalan muat truk CDD 6 roda / Titik bongkar di depan gerbang proyek" class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-4 focus:ring-terra-500/10 focus:border-terra-500">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" wire:loading.attr="disabled" class="font-display inline-flex items-center gap-2 px-8 py-4 bg-terra-500 hover:bg-terra-600 text-white font-black text-sm rounded-2xl shadow-xl shadow-terra-500/25 transition-all duration-200 cursor-pointer disabled:opacity-75">
                    <span wire:loading.remove wire:target="save">Simpan Pembaruan Profil</span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyimpan...
                    </span>
                </button>
            </div>
        </form>

    </div>
</div>
