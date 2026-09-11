<div class="min-h-screen bg-slate-50 dark:bg-slate-950 py-8 lg:py-12"
     x-data="{}"
     x-on:open-external-url.window="
         const data = Array.isArray($event.detail) ? $event.detail[0] : $event.detail;
         const url = typeof data === 'object' ? (data.url || '') : data;
         if (url) {
             window.open(url, '_blank');
         }
         window.scrollTo({ top: 0, behavior: 'smooth' });
     ">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Back -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('product.detail', $product->slug) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-terra-600 dark:hover:text-terra-400 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Detail Produk
            </a>
            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-700 dark:text-amber-300 bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800/80 px-2.5 py-1 rounded-full">
                📄 Jalur Permintaan Penawaran Resmi (RFQ)
            </span>
        </div>

        @if($isSubmitted)
            <!-- Success Card View (Auto Scroll To Top) -->
            <div x-init="window.scrollTo({ top: 0, behavior: 'smooth' })" class="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-10 border border-slate-200 dark:border-slate-800 shadow-xl text-center">
                <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center mx-auto mb-4 text-3xl shadow-sm">
                    ✓
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white font-display tracking-tight mb-2">
                    Permintaan Penawaran Berhasil Dibuat!
                </h1>
                <p class="text-sm text-slate-600 dark:text-slate-300 max-w-lg mx-auto mb-6">
                    Data penawaran Anda telah tersimpan di sistem kami dengan nomor referensi resmi. Tim Sales Proyek IndoRoster siap memberikan penawaran harga & jadwal pengiriman terbaik.
                </p>

                <!-- Reference Badge -->
                <div class="inline-block bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-3.5 mb-8">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider block mb-0.5">NOMOR REFERENSI PENAWARAN</span>
                    <span class="text-xl sm:text-2xl font-black text-terra-600 dark:text-terra-400 font-mono tracking-wider">{{ $submittedReference }}</span>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-3 max-w-md mx-auto">
                    <a href="{{ $whatsAppUrl }}" target="_blank" class="w-full sm:w-auto px-6 h-12 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-600/25 transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        Buka Chat WhatsApp Sales
                    </a>
                    <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 h-12 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 text-sm font-bold rounded-xl transition-all flex items-center justify-center">
                        Ke Beranda
                    </a>
                </div>
            </div>
        @else
            <!-- Header Title -->
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white font-display tracking-tight mb-2">
                    Formulir Permintaan Penawaran Resmi
                </h1>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400">
                    Isi data ringkas di bawah ini untuk mendapatkan penawaran harga pabrik, kalkulasi estimasi ongkos kirim armada, dan ketersediaan stok khusus proyek Anda.
                </p>
            </div>

            <form wire:submit.prevent="submitQuotation" class="space-y-6">
                
                <!-- Product Summary Card -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-5 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <img src="{{ $product->primary_image }}" alt="{{ $product->name }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover ring-1 ring-slate-200 dark:ring-slate-700 shrink-0">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-terra-600 dark:text-terra-400 bg-terra-50 dark:bg-terra-950/60 px-2 py-0.5 rounded-md border border-terra-200 dark:border-terra-800">
                                {{ $product->category->name ?? 'Roster Beton' }}
                            </span>
                            @if($product->material)
                                <span class="text-[10px] font-semibold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md">
                                    {{ $product->material }}
                                </span>
                            @endif
                        </div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-white truncate mb-1">
                            {{ $product->name }}
                        </h2>
                        <div class="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-3 flex-wrap">
                            @if($product->dimensions)
                                <span>📐 Dimensi: {{ $product->dimensions }}</span>
                            @endif
                            @if($product->weight > 0)
                                <span>⚖️ Berat: {{ $product->weight }} kg/pcs</span>
                            @endif
                        </div>
                    </div>

                    <!-- Variant Selection (if product has variants) -->
                    @if($product->variants->count() > 0)
                        <div class="w-full sm:w-48 shrink-0">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1">Pilihan Varian</label>
                            <select wire:model.live="selectedVariant" class="w-full h-10 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold px-3 text-slate-800 dark:text-white focus:ring-1 focus:ring-terra-500">
                                <option value="">Semua Varian / Default</option>
                                @foreach($product->variants->where('is_active', true) as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                <!-- Main Form Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Section 1: Data Kontak Pemesan -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                        <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-base">👤</span>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Data Pemesan / Klien</h3>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="name" placeholder="Contoh: Bpk. Hendra" class="w-full h-11 bg-slate-50 dark:bg-slate-800 border @error('name') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl text-xs sm:text-sm px-3.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500">
                            @error('name') <span class="text-[11px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Nama Perusahaan / Instansi / Proyek <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <input type="text" wire:model="company_name" placeholder="Contoh: PT Bangun Persada / Rumah Tinggal" class="w-full h-11 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm px-3.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                No. WhatsApp Aktif <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" wire:model="phone" placeholder="Contoh: 081234567890" class="w-full h-11 bg-slate-50 dark:bg-slate-800 border @error('phone') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl text-xs sm:text-sm px-3.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500">
                            @error('phone') <span class="text-[11px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Email <span class="text-slate-400 font-normal">(Opsional untuk pengiriman Surat Penawaran PDF)</span>
                            </label>
                            <input type="email" wire:model="email" placeholder="contoh@email.com" class="w-full h-11 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm px-3.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500">
                        </div>
                    </div>

                    <!-- Section 2: Kebutuhan & Lokasi Kirim -->
                    <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
                        <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                            <span class="text-base">📍</span>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">Lokasi Kirim & Kuantitas</h3>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Estimasi Kebutuhan (Pcs) <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" wire:model.live.debounce.300ms="quantity" min="1" class="w-full h-11 bg-slate-50 dark:bg-slate-800 border @error('quantity') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl text-xs sm:text-sm px-3.5 pr-12 font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500">
                                <span class="absolute right-3.5 top-3 text-xs text-slate-400 font-semibold">pcs</span>
                            </div>
                            @error('quantity') <span class="text-[11px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Provinsi Lokasi Proyek <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="province_id" class="w-full h-11 bg-slate-50 dark:bg-slate-800 border @error('province_id') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl text-xs sm:text-sm px-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500">
                                <option value="">Pilih Provinsi...</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov->code }}">{{ $prov->name }}</option>
                                @endforeach
                            </select>
                            @error('province_id') <span class="text-[11px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Kota / Kabupaten <span class="text-red-500">*</span>
                            </label>
                            <select wire:model.live="city_id" @disabled(empty($cities)) class="w-full h-11 bg-slate-50 dark:bg-slate-800 border @error('city_id') border-red-500 @else border-slate-200 dark:border-slate-700 @enderror rounded-xl text-xs sm:text-sm px-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500 disabled:opacity-50">
                                <option value="">{{ empty($cities) ? 'Pilih provinsi terlebih dahulu...' : 'Pilih Kota/Kabupaten...' }}</option>
                                @foreach($cities as $c)
                                    <option value="{{ $c->code }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('city_id') <span class="text-[11px] text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Alamat Lengkap / Patokan Proyek <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <input type="text" wire:model="address" placeholder="Contoh: Jl. Diponegoro No. 45, dekat Masjid Al-Ikhlas" class="w-full h-11 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm px-3.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Rencana Pemasangan / Waktu Dibutuhkan <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <input type="text" wire:model="installation_timeline" placeholder="Contoh: Akhir bulan ini / Sekitar tgl 25 / Menunggu tukang siap pasang..." class="w-full h-11 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm px-3.5 text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500">
                            <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-1">
                                💡 Tuliskan perkiraan kapan barang ingin diterima di lokasi atau estimasi jadwal mulai pasang proyek Anda.
                            </p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-1">
                                Catatan Tambahan / Akses Truk <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <textarea wire:model="notes" rows="2" placeholder="Contoh: Butuh surat penawaran PDF, truk engkel bisa masuk lokasi..." class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm p-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-terra-500"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button & Trust Guarantee -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 sm:p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400">
                        <span class="text-2xl">🛡️</span>
                        <div>
                            <div class="font-bold text-slate-800 dark:text-white">Layanan Personal & Cepat</div>
                            <p class="text-[11px]">Surat Penawaran Resmi & respon dalam hitungan menit via WhatsApp.</p>
                        </div>
                    </div>

                    <button type="submit" wire:loading.attr="disabled" class="w-full sm:w-auto px-8 h-12 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-600/25 transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-5 h-5 fill-current shrink-0" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        <span wire:loading.remove wire:target="submitQuotation">Kirim Permintaan Penawaran</span>
                        <span wire:loading wire:target="submitQuotation">Menyimpan & Menghubungkan ke WA...</span>
                    </button>
                </div>
            </form>
        @endif

    </div>
</div>
