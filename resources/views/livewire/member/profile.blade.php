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
                    <p class="text-slate-500 dark:text-slate-400 mt-1 text-sm">Kelola data profil, nomor WhatsApp, dan kategori kemitraan Anda untuk mendapatkan penawaran pabrik terbaik.</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('member.addresses') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-terra-500 hover:bg-terra-600 text-white text-xs font-bold rounded-xl transition-all shadow-md shadow-terra-500/20">
                        <span>📍 Kelola Buku Alamat & Peta GPS</span>
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

                <!-- Submit Button -->
                <div class="flex justify-end pt-4 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" wire:loading.attr="disabled" class="font-display inline-flex items-center gap-2 px-7 py-3 bg-terra-500 hover:bg-terra-600 text-white font-black text-sm rounded-xl shadow-lg shadow-terra-500/25 transition-all duration-200 cursor-pointer disabled:opacity-75">
                        <span wire:loading.remove wire:target="save">Simpan Perubahan Profil</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </div>

            <!-- 2. STATUS ALAMAT & TITIK KOORDINAT GPS (INTEGRASI KE BUKU ALAMAT) -->
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-xl p-6 sm:p-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-amber-50 dark:bg-amber-950/50 flex items-center justify-center text-amber-600 dark:text-amber-400 font-bold">
                            📍
                        </div>
                        <div>
                            <h2 class="font-display text-lg font-black text-slate-900 dark:text-white">Alamat Pengiriman & Titik Koordinat GPS</h2>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Pengelolaan alamat utama dan penentuan titik GPS gerbang proyek terpusat di Buku Alamat.</p>
                        </div>
                    </div>

                    <a href="{{ route('member.addresses') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:bg-terra-500 dark:hover:bg-terra-500 dark:hover:text-white text-xs font-black rounded-xl transition-all shadow-md shrink-0">
                        <span>🗺️ Buka Buku Alamat & Peta GPS</span>
                    </a>
                </div>

                @if($defaultAddress)
                    <div class="p-5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700/80 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-terra-100 dark:bg-terra-950/60 text-terra-700 dark:text-terra-300 border border-terra-200 dark:border-terra-900/40">
                                    {{ $defaultAddress->label }} (Alamat Utama)
                                </span>
                                <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $defaultAddress->recipient_name }} ({{ $defaultAddress->phone }})</span>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">{{ $defaultAddress->formatted_address }}</p>
                            @if($defaultAddress->truck_access_notes)
                                <p class="text-[11px] text-amber-600 dark:text-amber-400 font-medium">🚚 Akses Truk: {{ $defaultAddress->truck_access_notes }}</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            @if($defaultAddress->latitude && $defaultAddress->longitude)
                                <span class="px-3 py-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900/50 text-emerald-700 dark:text-emerald-300 text-xs font-mono font-bold">
                                    📍 GPS Terpasang
                                </span>
                            @else
                                <a href="{{ route('member.addresses') }}" class="px-3 py-1.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-900/50 text-amber-700 dark:text-amber-300 text-xs font-bold hover:bg-amber-100 transition-colors">
                                    ⚠️ Pasang Titik GPS
                                </a>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="p-6 rounded-2xl bg-amber-50/50 dark:bg-amber-950/20 border border-dashed border-amber-300 dark:border-amber-800 text-center">
                        <p class="text-xs font-bold text-amber-900 dark:text-amber-200 mb-2">Anda belum menyimpan alamat pengiriman utama.</p>
                        <p class="text-[11px] text-amber-700 dark:text-amber-400 mb-4">Simpan alamat pengiriman lengkap dengan titik pin peta GPS agar surat jalan armada driver akurat.</p>
                        <a href="{{ route('member.addresses') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-xs font-black rounded-xl shadow-xs transition-all">
                            <span>+ Tambah Alamat & Titik GPS Sekarang</span>
                        </a>
                    </div>
                @endif
            </div>
        </form>

    </div>
</div>
