<div class="bg-slate-50 dark:bg-slate-950 min-h-screen py-10 sm:py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Halaman -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <nav class="flex text-xs font-semibold text-slate-400 dark:text-slate-500 gap-1.5 mb-2 uppercase tracking-wider">
                    <a href="{{ route('home') }}" class="hover:text-slate-600 dark:hover:text-slate-300 transition-colors">Home</a>
                    <span>/</span>
                    <span class="text-slate-600 dark:text-slate-300">Buku Alamat</span>
                </nav>
                <h1 class="font-display text-fluid-h1 font-black text-slate-900 dark:text-white tracking-tight">Kelola Buku Alamat</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">Simpan dan kelola alamat pengiriman serta titik koordinat GPS untuk kemudahan pengiriman.</p>
            </div>
            
            @if(!$isFormOpen)
                <button wire:click="openCreateForm" class="font-display inline-flex items-center justify-center bg-terra-500 hover:bg-terra-600 text-white font-bold px-6 py-3.5 rounded-2xl transition-all duration-200 shadow-lg shadow-terra-500/25 gap-2 cursor-pointer text-sm shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ Tambah Alamat Baru</span>
                </button>
            @endif
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/40 text-emerald-800 dark:text-emerald-300 rounded-2xl flex items-center gap-3 shadow-2xs">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900/40 text-red-800 dark:text-red-300 rounded-2xl flex items-center gap-3 shadow-2xs">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="text-sm font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- FORM TAMBAH / UBAH ALAMAT (BERSIH & LENGKAP DI TENGAH HALAMAN) -->
        <!-- ========================================================================= -->
        @if($isFormOpen)
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-xl p-6 sm:p-10 mb-10"
                 x-data="addressBookMapHandler(@entangle('latitude'), @entangle('longitude'))"
                 x-init="initMap()">

                <!-- Header Form -->
                <div class="flex justify-between items-center pb-5 mb-8 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <span class="text-xs font-bold text-terra-600 dark:text-terra-400 uppercase tracking-widest bg-terra-50 dark:bg-terra-950/50 px-3 py-1 rounded-full border border-terra-100 dark:border-terra-900/40">
                            {{ $addressId ? 'Mode Edit Alamat' : 'Mode Tambah Alamat Baru' }}
                        </span>
                        <h2 class="font-display text-fluid-h2 font-black text-slate-900 dark:text-white mt-2">
                            {{ $addressId ? 'Ubah Rincian Alamat Pengiriman' : 'Formulir Tambah Alamat Pengiriman' }}
                        </h2>
                    </div>
                    <button wire:click="$set('isFormOpen', false)" class="text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 p-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-2xl transition-all cursor-pointer">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveAddress" class="space-y-6">
                    
                    <!-- 1. Identitas & Label -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="font-display block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Label Alamat</label>
                            <input type="text" wire:model="label" placeholder="Contoh: Rumah, Kantor, Proyek" class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 rounded-xl text-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 shadow-xs">
                            @error('label') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="font-display block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Nama Penerima</label>
                            <input type="text" wire:model="recipient_name" placeholder="Nama Lengkap Penerima" class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 rounded-xl text-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 shadow-xs">
                            @error('recipient_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="font-display block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Nomor HP / WhatsApp</label>
                            <input type="tel" wire:model="phone" placeholder="Contoh: 081234567890" class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 rounded-xl text-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 shadow-xs">
                            @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- 2. Wilayah (Provinsi, Kota, Kecamatan, Kode Pos) -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5 pt-2">
                        <div>
                            <label class="font-display block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Provinsi</label>
                            <select wire:model.live="province_id" class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-xs">
                                <option value="">Pilih Provinsi</option>
                                @foreach($provinces as $p)
                                    <option value="{{ $p->code }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                            @error('province_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="font-display block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2 flex justify-between">
                                <span>Kota / Kab</span>
                                <span wire:loading wire:target="province_id" class="text-[10px] text-terra-500 lowercase animate-pulse font-normal">Memuat...</span>
                            </label>
                            <select wire:model.live="city_id" @disabled(!$province_id) class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 bg-white dark:bg-slate-800 text-slate-900 dark:text-white disabled:bg-slate-50 dark:disabled:bg-slate-900 disabled:text-slate-400 dark:disabled:text-slate-600 shadow-xs">
                                <option value="">Pilih Kota / Kab</option>
                                @foreach($cities as $c)
                                    <option value="{{ $c->code }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('city_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="font-display block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2 flex justify-between">
                                <span>Kecamatan</span>
                                <span wire:loading wire:target="city_id" class="text-[10px] text-terra-500 lowercase animate-pulse font-normal">Memuat...</span>
                            </label>
                            <select wire:model="district_id" @disabled(!$city_id) class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 bg-white dark:bg-slate-800 text-slate-900 dark:text-white disabled:bg-slate-50 dark:disabled:bg-slate-900 disabled:text-slate-400 dark:disabled:text-slate-600 shadow-xs">
                                <option value="">Pilih Kecamatan</option>
                                @foreach($districts as $d)
                                    <option value="{{ $d->code }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                            @error('district_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="font-display block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Kode Pos</label>
                            <input type="text" wire:model="postal_code" placeholder="Contoh: 41165" class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 rounded-xl text-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 shadow-xs">
                            @error('postal_code') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- 3. Alamat Lengkap & Patokan -->
                    <div>
                        <label class="font-display block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Alamat Lengkap & Patokan</label>
                        <textarea wire:model="full_address" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW, nama gang/blok, atau patokan gerbang proyek..." class="w-full px-4 py-3 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 rounded-xl text-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 shadow-xs"></textarea>
                        @error('full_address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- 4. PETA INTERAKTIF (LEBAR, TENGAH & JELAS) -->
                    <div wire:ignore class="p-6 bg-slate-50/50 dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 mb-4 border-b border-slate-200/80 dark:border-slate-800">
                            <div>
                                <h3 class="font-display font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                                    <span>📍 Titik Koordinat Pengiriman (Peta Interaktif)</span>
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                    Tentukan titik gerbang pembongkaran muatan roster. Titik ini akan otomatis dicetak menjadi <strong>QR Code Navigasi Google Maps di Surat Jalan Driver</strong>.
                                </p>
                            </div>
                            <div class="flex items-center gap-2 flex-wrap shrink-0">
                                <button type="button" @click="syncFromAddressInput()" :disabled="isSearching" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold text-white bg-terra-500 hover:bg-terra-600 px-4 py-2.5 rounded-xl shadow-xs transition-all cursor-pointer">
                                    <span>🎯 Arahkan ke Alamat Terisi</span>
                                </button>
                                <button type="button" @click="locateMe()" :disabled="isLocating" title="Mengambil posisi fisik perangkat HP/Laptop Anda saat ini" class="inline-flex items-center justify-center gap-1.5 text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-slate-900 dark:hover:text-white bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 px-4 py-2.5 rounded-xl border border-slate-300 dark:border-slate-700 shadow-xs transition-all cursor-pointer">
                                    <span x-show="!isLocating">📍 GPS Perangkat</span>
                                    <span x-show="isLocating" class="animate-pulse">Mencari GPS...</span>
                                </button>
                            </div>
                        </div>

                        <!-- Kolom Pencarian Alamat Peta -->
                        <div class="flex gap-2 mb-3">
                            <div class="relative flex-1">
                                <input type="text" x-model="searchQuery" @keydown.enter.prevent="searchLocation()" placeholder="Ketik nama jalan, patokan gerbang, desa, atau kecamatan di peta..." class="w-full px-4 py-2.5 text-xs sm:text-sm bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 border border-slate-300 dark:border-slate-700 rounded-xl focus:border-terra-500 focus:outline-none shadow-xs">
                            </div>
                            <button type="button" @click="searchLocation()" :disabled="isSearching" class="px-5 py-2.5 bg-slate-900 hover:bg-black dark:bg-terra-500 dark:hover:bg-terra-600 text-white rounded-xl text-xs sm:text-sm font-bold shrink-0 transition-all cursor-pointer">
                                <span x-show="!isSearching">🔍 Cari di Peta</span>
                                <span x-show="isSearching" class="animate-pulse">Mencari...</span>
                            </button>
                        </div>

                        <!-- Petunjuk Presisi Peta Jalan & Satelit -->
                        <div class="flex items-start gap-2.5 p-3 bg-sky-50/90 dark:bg-sky-950/40 border border-sky-200/80 dark:border-sky-900/50 rounded-2xl text-sky-950 dark:text-sky-200 text-xs font-medium mb-3 shadow-xs">
                            <span class="text-base shrink-0 mt-0.5">🗺️</span>
                            <div class="leading-relaxed">
                                <strong>Peta Jalan Aktif:</strong> Nama jalan, gang, dan patokan kota ditampilkan jelas pada peta jalan. Setelah lokasi ditemukan, Anda dapat beralih ke tombol <strong class="text-terra-600 dark:text-terra-400">🛰️ Foto Satelit</strong> di pojok kanan atas peta untuk menaruh pin merah 📍 tepat di atas atap rumah atau gerbang proyek.
                            </div>
                        </div>

                        <!-- Canvas Peta Leaflet -->
                        <div id="main-map-picker" style="width: 100%; height: 320px; min-height: 320px; border-radius: 1rem;" class="border-2 border-slate-300 dark:border-slate-700 shadow-inner z-0 overflow-hidden relative mb-4"></div>

                        <!-- Kotak Koordinat Output -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-slate-200/80 dark:border-slate-800">
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1">Latitude (Garis Lintang)</label>
                                <input type="text" x-model="lat" placeholder="-6.5631" class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-200 font-semibold" readonly>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-1">Longitude (Garis Bujur)</label>
                                <input type="text" x-model="lng" placeholder="107.4439" class="w-full px-3.5 py-2 text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl font-mono text-slate-800 dark:text-slate-200 font-semibold" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Checkbox Alamat Utama -->
                    <div class="flex items-center gap-3 pt-2">
                        <input type="checkbox" id="is_default" wire:model="is_default" class="w-5 h-5 text-terra-600 border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg focus:ring-terra-500 cursor-pointer">
                        <label for="is_default" class="text-sm font-bold text-slate-800 dark:text-slate-200 select-none cursor-pointer">
                            Atur sebagai alamat utama pengiriman
                        </label>
                    </div>

                    <!-- Tombol Aksi Simpan & Batal -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-800">
                        <button type="button" wire:click="$set('isFormOpen', false)" class="px-6 py-3.5 rounded-2xl border border-slate-300 dark:border-slate-700 font-bold text-sm text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="font-display inline-flex items-center justify-center bg-slate-900 dark:bg-terra-500 hover:bg-black dark:hover:bg-terra-600 text-white font-bold px-8 py-3.5 rounded-2xl shadow-xl transition-all gap-2 cursor-pointer text-sm uppercase tracking-wider">
                            <span>💾 Simpan Alamat & Koordinat</span>
                        </button>
                    </div>

                </form>
            </div>
        @endif

        <!-- ========================================================================= -->
        <!-- DAFTAR ALAMAT TERSIMPAN (GRID DI TENGAH HALAMAN) -->
        <!-- ========================================================================= -->
        <div class="space-y-4">
            <h3 class="font-display font-bold text-base text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center justify-between">
                <span>Daftar Alamat Pengiriman Saya ({{ count($addresses) }})</span>
            </h3>

            @if(count($addresses) === 0)
                @if(!$isFormOpen)
                    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-soft-xs p-12 text-center">
                        <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="font-display font-bold text-lg text-slate-800 dark:text-white">Belum Ada Alamat Disimpan</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 max-w-sm mx-auto leading-relaxed">Anda belum menambahkan alamat pengiriman. Tambahkan sekarang untuk mempermudah proses pemesanan roster.</p>
                        <button wire:click="openCreateForm" class="font-display inline-flex items-center justify-center bg-slate-900 dark:bg-terra-500 hover:bg-black dark:hover:bg-terra-600 text-white font-bold px-6 py-3 rounded-2xl transition-all duration-200 mt-6 gap-2 cursor-pointer text-sm">
                            <span>+ Tambah Alamat Sekarang</span>
                        </button>
                    </div>
                @endif
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($addresses as $addr)
                        <div class="bg-white dark:bg-slate-900 rounded-3xl border transition-all duration-200 p-6 sm:p-7 shadow-soft-xs flex flex-col justify-between {{ $addr->is_default ? 'border-terra-500 ring-2 ring-terra-500/10' : 'border-slate-200 dark:border-slate-800 hover:border-slate-300 dark:hover:border-slate-700' }}">
                            <div>
                                <div class="flex justify-between items-start gap-4 mb-3">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-display font-black text-xs text-slate-900 dark:text-white bg-slate-100 dark:bg-slate-800 px-3 py-1 rounded-full uppercase tracking-wider">{{ $addr->label }}</span>
                                        @if($addr->is_default)
                                            <span class="font-display font-bold text-xs text-terra-600 dark:text-terra-400 bg-terra-50 dark:bg-terra-950/40 px-3 py-1 rounded-full border border-terra-100 dark:border-terra-900/40 uppercase tracking-wider">Alamat Utama</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Aksi Edit / Hapus -->
                                    <div class="flex items-center gap-1.5">
                                        <button wire:click="openEditForm({{ $addr->id }})" title="Ubah Alamat" class="p-2 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        @if(!$addr->is_default)
                                            <button onclick="confirm('Apakah Anda yakin ingin menghapus alamat ini?') || event.stopImmediatePropagation()" wire:click="deleteAddress({{ $addr->id }})" title="Hapus Alamat" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 rounded-xl transition-all cursor-pointer">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                
                                <h4 class="font-display font-bold text-base text-slate-900 dark:text-white">{{ $addr->recipient_name }}</h4>
                                <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 mt-0.5">{{ $addr->phone }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-300 mt-2.5 leading-relaxed">{{ $addr->formatted_address }}</p>

                                @if($addr->latitude && $addr->longitude)
                                    <div class="mt-3.5 flex items-center gap-2 flex-wrap">
                                        <span class="inline-flex items-center gap-1.5 text-xs text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 px-3 py-1 rounded-xl border border-emerald-200 dark:border-emerald-900/50 font-semibold font-mono">
                                            📍 GPS: {{ number_format($addr->latitude, 5) }}, {{ number_format($addr->longitude, 5) }}
                                        </span>
                                        <a href="{{ $addr->google_maps_url }}" target="_blank" class="text-xs font-bold text-terra-600 dark:text-terra-400 hover:text-terra-700 dark:hover:text-terra-300 underline flex items-center gap-1">
                                            Buka di Google Maps ↗
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-3 text-xs text-amber-600 dark:text-amber-400 flex items-center gap-1">
                                        <span>⚠️ Titik koordinat belum ditandai pada peta</span>
                                    </div>
                                @endif
                            </div>
                            
                            @if(!$addr->is_default)
                                <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                                    <button wire:click="setDefault({{ $addr->id }})" class="text-xs font-bold text-terra-500 dark:text-terra-400 hover:text-terra-600 dark:hover:text-terra-300 transition-colors flex items-center gap-1 cursor-pointer">
                                        Jadikan Alamat Utama
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>

<script>
function addressBookMapHandler(latRef, lngRef) {
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
                const mapEl = document.getElementById('main-map-picker');
                if (!mapEl || typeof L === 'undefined') return;

                if (this.map) {
                    this.map.remove();
                    this.map = null;
                }

                this.map = L.map('main-map-picker', {
                    center: [initialLat, initialLng],
                    zoom: initialZoom,
                    zoomControl: true
                });

                // Layer Peta Jalan (OpenStreetMap)
                const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                });

                // Layer Citra Satelit Esri (Foto Udara Atap Rumah Nyata)
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

                // Tambahkan Kontrol Switcher Layer di Kanan Atas Peta
                L.control.layers({
                    "🗺️ Peta Jalan (Nama Jalan Jelas)": streetLayer,
                    "🛰️ Foto Satelit (Atap Rumah)": satelliteLayer
                }, null, { position: 'topright' }).addTo(this.map);

                const pinIconHtml = '<div style="font-size: 32px; line-height: 1; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.35)); transform: translate(-16px, -32px); cursor: grab;">📍<' + '/div>';
                const pinIcon = L.divIcon({
                    className: 'custom-pin-marker',
                    html: pinIconHtml,
                    iconSize: [32, 32],
                    iconAnchor: [16, 32]
                });

                if (this.lat && this.lng) {
                    this.marker = L.marker([initialLat, initialLng], {
                        icon: pinIcon,
                        draggable: true
                    }).addTo(this.map);

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
                        this.marker = L.marker([lat, lng], {
                            icon: pinIcon,
                            draggable: true
                        }).addTo(this.map);

                        this.marker.on('dragend', (ev) => {
                            const pos = ev.target.getLatLng();
                            this.lat = pos.lat.toFixed(7);
                            this.lng = pos.lng.toFixed(7);
                        });
                    }
                });

                this.map.invalidateSize();
                setTimeout(() => { if (this.map) this.map.invalidateSize(); }, 300);
            }, 100);
        },
        setLocationOnMap(lat, lng, zoomLevel = 15) {
            this.lat = parseFloat(lat).toFixed(7);
            this.lng = parseFloat(lng).toFixed(7);

            if (this.map && typeof L !== 'undefined') {
                this.map.setView([lat, lng], zoomLevel);
                const pinIconHtml = '<div style="font-size: 32px; line-height: 1; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.35)); transform: translate(-16px, -32px); cursor: grab;">📍<' + '/div>';
                const pinIcon = L.divIcon({
                    className: 'custom-pin-marker',
                    html: pinIconHtml,
                    iconSize: [32, 32],
                    iconAnchor: [16, 32]
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
            const fullAddr = this.$wire.get('full_address') || '';
            const provSelect = document.querySelector('select[wire\\:model\\.live="province_id"]');
            const citySelect = document.querySelector('select[wire\\:model\\.live="city_id"]');
            const distSelect = document.querySelector('select[wire\\:model="district_id"]') || document.querySelector('select[wire\\:model\\.live="district_id"]');

            const provName = provSelect && provSelect.selectedIndex > 0 ? provSelect.options[provSelect.selectedIndex].text.replace(/^(PROVINSI|DKI|DI)\s+/i, '').trim() : '';
            const cityName = citySelect && citySelect.selectedIndex > 0 ? citySelect.options[citySelect.selectedIndex].text.replace(/^(KABUPATEN|KOTA|KAB\.)\s+/i, '').trim() : '';
            const distName = distSelect && distSelect.selectedIndex > 0 ? distSelect.options[distSelect.selectedIndex].text.replace(/^(KECAMATAN|KEC\.)\s+/i, '').trim() : '';
            const postalCode = (this.$wire.get('postal_code') || document.querySelector('input[wire\\:model="postal_code"]')?.value || '').trim();

            // Ekstrak nama desa / kampung / jalan utama dari alamat lengkap tanpa memotong teks
            const cleanAddr = fullAddr
                .replace(/\r?\n/g, ', ')
                .replace(/rt\s*\d+\s*(\/|rw)?\s*\d+/gi, '')
                .replace(/rt\s*\d+|rw\s*\d+/gi, '')
                .replace(/no\.?\s*\d+/gi, '')
                .replace(/blok\s*\w+/gi, '')
                .replace(/,\s*,/g, ',')
                .replace(/^[\s,]+|[\s,]+$/g, '')
                .trim();

            const desaMatch = fullAddr.match(/(desa|kelurahan|kel|kp|kampung|jl|jalan)\.?\s*([a-z0-9\s]+?)(,|$)/i);
            const desaName = (desaMatch && desaMatch[2]) ? desaMatch[2].trim() : '';

            // Gabungan lengkap dari seluruh data form
            const completeAddressText = [
                fullAddr.replace(/\r?\n/g, ', '),
                desaName,
                distName,
                cityName,
                postalCode,
                provName
            ].filter(Boolean).join(', ');

            // Set langsung ke kolom pencarian
            this.searchQuery = completeAddressText;

            let queries = [];

            // 1. Gabungan Lengkap: Alamat Lengkap + Desa + Kecamatan + Kota + Kode Pos + Provinsi
            if (cleanAddr || desaName || cityName) {
                queries.push([cleanAddr, desaName, distName, cityName, postalCode, provName].filter(Boolean).join(', '));
                queries.push([cleanAddr, desaName, distName, cityName, provName].filter(Boolean).join(', '));
                queries.push([cleanAddr, desaName, cityName].filter(Boolean).join(', '));
            }

            // 2. Alamat Lengkap Bersih + Kota
            if (cleanAddr && cityName) {
                queries.push(`${cleanAddr}, ${cityName}`);
            }

            // 3. Desa/Kelurahan + Kecamatan + Kota
            if (desaName && distName && cityName) {
                queries.push(`${desaName}, ${distName}, ${cityName}, ${provName}`);
                queries.push(`${desaName}, ${distName}, ${cityName}`);
                queries.push(`${desaName}, ${cityName}`);
            } else if (desaName && cityName) {
                queries.push(`${desaName}, ${cityName}`);
            }

            // 4. Kecamatan + Kota + Provinsi
            if (distName && cityName) {
                queries.push(`${distName}, ${cityName}, ${provName}`);
                queries.push(`${distName}, ${cityName}`);
            }

            // 5. Kota + Provinsi (Fallback)
            if (cityName) {
                queries.push(`${cityName}, ${provName}`);
                queries.push(cityName);
            }

            queries = [...new Set(queries.filter(q => q && q.trim().length > 0))];

            if (queries.length === 0) {
                alert('Silakan pilih Kota atau isi Alamat Lengkap terlebih dahulu.');
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

                fetch(`/api/geocode?q=${encodedQ}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.length > 0 && data[0].lat && data[0].lon) {
                            this.isSearching = false;
                            const zoom = index <= 1 ? 16 : (index <= 3 ? 15 : 13);
                            this.setLocationOnMap(data[0].lat, data[0].lon, zoom);
                            this.searchQuery = completeAddressText;
                        } else {
                            // Fallback ke Photon
                            return fetch(`https://photon.komoot.io/api/?q=${encodedQ}&limit=1`)
                                .then(pRes => pRes.json())
                                .then(pData => {
                                    if (pData && pData.features && pData.features.length > 0) {
                                        this.isSearching = false;
                                        const coords = pData.features[0].geometry.coordinates;
                                        const zoom = index <= 1 ? 16 : (index <= 3 ? 15 : 13);
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
                        fetch(`https://photon.komoot.io/api/?q=${encodedQ}&limit=1`)
                            .then(pRes => pRes.json())
                            .then(pData => {
                                if (pData && pData.features && pData.features.length > 0) {
                                    this.isSearching = false;
                                    const coords = pData.features[0].geometry.coordinates;
                                    const zoom = index <= 1 ? 16 : (index <= 3 ? 15 : 13);
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
                alert('Browser Anda tidak mendukung GPS otomatis.');
                return;
            }
            this.isLocating = true;
            navigator.geolocation.getCurrentPosition((pos) => {
                this.isLocating = false;
                this.setLocationOnMap(pos.coords.latitude, pos.coords.longitude, 16);
            }, (err) => {
                this.isLocating = false;
                alert('Gagal mengambil lokasi GPS: ' + err.message + '. Silakan klik tombol "Arahkan ke Alamat Terisi" atau klik langsung pada peta.');
            }, { enableHighAccuracy: true, timeout: 10000 });
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
                        return fetch(`https://photon.komoot.io/api/?q=${q}&limit=1`)
                            .then(pRes => pRes.json())
                            .then(pData => {
                                this.isSearching = false;
                                if (pData && pData.features && pData.features.length > 0) {
                                    const coords = pData.features[0].geometry.coordinates;
                                    this.setLocationOnMap(coords[1], coords[0], 15);
                                } else {
                                    alert('Lokasi tidak ditemukan. Coba ketik nama kota atau nama jalan lain.');
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
</script>
