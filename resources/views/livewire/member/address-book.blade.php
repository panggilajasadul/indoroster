<div class="bg-slate-50 min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb / Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <nav class="flex text-xs font-semibold text-slate-400 gap-1.5 mb-2 uppercase tracking-wider">
                    <a href="{{ route('home') }}" class="hover:text-slate-600 transition-colors">Home</a>
                    <span>/</span>
                    <span class="text-slate-600">Buku Alamat</span>
                </nav>
                <h1 class="font-display text-fluid-h1 font-black text-slate-900 tracking-tight">Kelola Buku Alamat</h1>
                <p class="text-slate-500 mt-1">Simpan dan atur alamat pengiriman Anda untuk proses checkout yang lebih cepat.</p>
            </div>
            
            @if(!$isFormOpen)
                <button wire:click="openCreateForm" class="font-display inline-flex items-center justify-center bg-terra-500 hover:bg-terra-600 text-white font-bold px-5 py-3 rounded-xl transition-all duration-200 shadow-lg shadow-terra-500/25 gap-2 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Alamat Baru
                </button>
            @endif
        </div>

        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <p class="text-sm font-semibold">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Daftar Alamat -->
            <div class="lg:col-span-2 space-y-4">
                @if(count($addresses) === 0)
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-12 text-center">
                        <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="font-display font-bold text-lg text-slate-800">Belum Ada Alamat Disimpan</h3>
                        <p class="text-sm text-slate-500 mt-2 max-w-sm mx-auto leading-relaxed">Anda belum menambahkan alamat pengiriman apapun. Tambahkan alamat pertama Anda sekarang untuk kemudahan pemesanan.</p>
                        <button wire:click="openCreateForm" class="font-display inline-flex items-center justify-center bg-slate-900 hover:bg-black text-white font-bold px-5 py-2.5 rounded-xl transition-all duration-200 mt-6 gap-2 cursor-pointer text-sm">
                            Buat Alamat Pertama
                        </button>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($addresses as $addr)
                            <div class="bg-white rounded-2xl border transition-all duration-200 p-6 shadow-sm {{ $addr->is_default ? 'border-terra-500 ring-2 ring-terra-500/5' : 'border-slate-100 hover:border-slate-300' }}">
                                <div class="flex justify-between items-start gap-4">
                                    <div>
                                        <div class="flex items-center gap-2.5 flex-wrap">
                                            <span class="font-display font-black text-xs text-slate-900 bg-slate-100 px-3 py-1 rounded-full uppercase tracking-wider">{{ $addr->label }}</span>
                                            @if($addr->is_default)
                                                <span class="font-display font-bold text-xs text-terra-600 bg-terra-50 px-3 py-1 rounded-full border border-terra-100 uppercase tracking-wider">Alamat Utama</span>
                                            @endif
                                        </div>
                                        
                                        <h4 class="font-display font-bold text-base text-slate-800 mt-3">{{ $addr->recipient_name }}</h4>
                                        <p class="text-sm font-semibold text-slate-500 mt-0.5">{{ $addr->phone }}</p>
                                        <p class="text-sm text-slate-600 mt-2.5 leading-relaxed">{{ $addr->formatted_address }}</p>
                                    </div>
                                    
                                    <!-- Aksi Edit/Delete Kanan Atas -->
                                    <div class="flex items-center gap-2">
                                        <button wire:click="openEditForm({{ $addr->id }})" title="Ubah Alamat" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-xl transition-all">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        @if(!$addr->is_default)
                                            <button onclick="confirm('Apakah Anda yakin ingin menghapus alamat ini?') || event.stopImmediatePropagation()" wire:click="deleteAddress({{ $addr->id }})" title="Hapus Alamat" class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                
                                @if(!$addr->is_default)
                                    <div class="mt-5 pt-4 border-t border-slate-100 flex justify-end">
                                        <button wire:click="setDefault({{ $addr->id }})" class="text-xs font-bold text-terra-500 hover:text-terra-600 transition-colors flex items-center gap-1">
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

            <!-- Form Edit/Tambah -->
            <div class="lg:col-span-1">
                @if($isFormOpen)
                    <div class="bg-white rounded-2xl border border-slate-100 p-6 sm:p-8 shadow-sm sticky top-24">
                        <div class="flex justify-between items-center pb-4 mb-6 border-b border-slate-100">
                            <h2 class="font-display text-fluid-h3 font-black text-slate-900">
                                {{ $addressId ? 'Ubah Alamat' : 'Tambah Alamat' }}
                            </h2>
                            <button wire:click="$set('isFormOpen', false)" class="text-slate-400 hover:text-slate-600 p-1.5 hover:bg-slate-50 rounded-xl transition-all">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form wire:submit.prevent="saveAddress" class="space-y-4">
                            <div>
                                <label class="font-display block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Label Alamat</label>
                                <input type="text" wire:model="label" placeholder="Contoh: Rumah, Kantor, Toko" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200">
                                @error('label') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="font-display block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Penerima</label>
                                <input type="text" wire:model="recipient_name" placeholder="Nama Lengkap" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200">
                                @error('recipient_name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="font-display block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor HP</label>
                                <input type="tel" wire:model="phone" placeholder="Contoh: 081234567890" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200">
                                @error('phone') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="font-display block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Provinsi</label>
                                <select wire:model.live="province_id" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 bg-white">
                                    <option value="">Pilih Provinsi</option>
                                    @foreach($provinces as $p)
                                        <option value="{{ $p->code }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                                @error('province_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="font-display block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex justify-between">
                                    <span>Kota / Kabupaten</span>
                                    <span wire:loading wire:target="province_id" class="text-[10px] text-terra-500 lowercase animate-pulse font-normal">Memuat...</span>
                                </label>
                                <select wire:model.live="city_id" @disabled(!$province_id) class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 bg-white disabled:bg-slate-50 disabled:text-slate-400">
                                    <option value="">Pilih Kota / Kabupaten</option>
                                    @foreach($cities as $c)
                                        <option value="{{ $c->code }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                @error('city_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="font-display block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 flex justify-between">
                                    <span>Kecamatan</span>
                                    <span wire:loading wire:target="city_id" class="text-[10px] text-terra-500 lowercase animate-pulse font-normal">Memuat...</span>
                                </label>
                                <select wire:model="district_id" @disabled(!$city_id) class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200 bg-white disabled:bg-slate-50 disabled:text-slate-400">
                                    <option value="">Pilih Kecamatan</option>
                                    @foreach($districts as $d)
                                        <option value="{{ $d->code }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                                @error('district_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="font-display block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Kode Pos</label>
                                <input type="text" wire:model="postal_code" placeholder="Kode Pos" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200">
                                @error('postal_code') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="font-display block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Lengkap</label>
                                <textarea wire:model="full_address" rows="3" placeholder="Nama jalan, nomor rumah, patokan, dsb." class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:border-terra-500 focus:ring-4 focus:ring-terra-500/10 focus:outline-none transition-all duration-200"></textarea>
                                @error('full_address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex items-center gap-2 py-2">
                                <input type="checkbox" id="is_default" wire:model="is_default" class="w-4 h-4 text-terra-600 border-slate-200 rounded focus:ring-terra-500 focus:ring-opacity-25 focus:ring-offset-0 focus:outline-none">
                                <label for="is_default" class="text-xs font-bold text-slate-700 select-none cursor-pointer">Atur sebagai alamat utama</label>
                            </div>

                            <button type="submit" class="font-display w-full flex justify-center items-center bg-slate-900 hover:bg-black text-white font-bold py-3 px-4 rounded-xl shadow-lg transition-all gap-2 cursor-pointer text-sm">
                                <span>Simpan Alamat</span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-6 text-white shadow-lg sticky top-24">
                        <h3 class="font-display font-black text-fluid-h3 mb-3">Informasi Buku Alamat</h3>
                        <p class="text-xs text-slate-300 leading-relaxed mb-4">
                            Semua alamat tersimpan di sini akan terhubung langsung dengan halaman checkout Anda secara otomatis.
                        </p>
                        <div class="space-y-3.5">
                            <div class="flex gap-3 items-start">
                                <div class="w-5 h-5 bg-slate-800 text-terra-500 rounded-lg flex items-center justify-center shrink-0 mt-0.5 font-bold">1</div>
                                <p class="text-xs text-slate-200"><strong>Alamat Utama:</strong> Alamat yang pertama kali diisi otomatis saat Anda masuk ke halaman checkout.</p>
                            </div>
                            <div class="flex gap-3 items-start">
                                <div class="w-5 h-5 bg-slate-800 text-terra-500 rounded-lg flex items-center justify-center shrink-0 mt-0.5 font-bold">2</div>
                                <p class="text-xs text-slate-200"><strong>Wilayah Pengiriman:</strong> Pembatasan wilayah serta ongkos kirim mengikuti koordinat kecamatan yang terhubung ke database Armada Pabrik Indoroster.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
