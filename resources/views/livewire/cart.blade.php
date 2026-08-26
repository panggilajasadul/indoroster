<div class="bg-slate-50 dark:bg-slate-950 min-h-screen py-12"
     x-data
     x-on:open-external-url.window="
         const data = Array.isArray($event.detail) ? $event.detail[0] : $event.detail;
         const url = data.url || data;
         if (url) {
             window.open(url, '_blank');
         }
     ">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-breadcrumb :items="[['label' => 'Keranjang Belanja']]" class="!px-0 !py-0 mb-8" />

        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-950/40 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-2xl">
                {{ session('success') }}
            </div>
        @endif

        @if(count($cartItems) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                @if (session()->has('error'))
                    <div class="mb-4 p-4 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-2xl">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs overflow-hidden mb-4">
                    <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/70 dark:bg-slate-800/50">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" wire:model.live="selectAll" class="w-5 h-5 text-terra-500 rounded border-slate-300 dark:border-slate-600 focus:ring-terra-500">
                            <span class="font-display text-sm font-bold text-slate-700 dark:text-slate-200">Pilih Semua</span>
                        </label>
                        <button wire:click="deleteSelected" class="font-display text-sm text-red-500 hover:text-red-600 font-bold transition-colors disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer" @if(empty($selectedItems)) disabled @endif>
                            Hapus Terpilih
                        </button>
                    </div>
                    <ul class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($cartItems as $item)
                        <!-- Desktop Layout -->
                        <li class="p-6 hidden md:grid md:grid-cols-12 md:gap-4 md:items-center">
                            <!-- Product Info (Col 4) -->
                            <div class="col-span-4 flex items-center gap-4 min-w-0">
                                <input type="checkbox" wire:model.live="selectedItems" value="{{ $item->id }}" class="w-5 h-5 text-terra-500 rounded border-slate-300 dark:border-slate-600 focus:ring-terra-500 shrink-0 cursor-pointer">
                                <a href="/produk/{{ $item->product->slug }}" class="w-16 h-16 flex-shrink-0 bg-slate-50 dark:bg-slate-800 rounded-xl overflow-hidden border border-slate-100 dark:border-slate-700 block">
                                    @if($item->product->primary_image)
                                        <img src="{{ $item->product->primary_image }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600 text-xs">No Image</div>
                                    @endif
                                </a>
                                <div class="min-w-0">
                                    <span class="block font-display text-[9px] text-terra-500 font-bold mb-0.5 uppercase tracking-widest">{{ $item->product->category->name ?? 'Kategori' }}</span>
                                    <h3 class="font-display text-sm font-bold text-slate-900 dark:text-white truncate">
                                        <a href="/produk/{{ $item->product->slug }}" class="hover:text-terra-500 transition-colors" title="{{ $item->product->name }}">{{ $item->product->name }}</a>
                                    </h3>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-green-50 dark:bg-green-950/50 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800 mt-1">
                                        Ready Stock
                                    </span>
                                </div>
                            </div>

                            <!-- Variation (Col 2) -->
                            <div class="col-span-2">
                                @if($item->product->variants->count() > 0)
                                    <div class="text-xs text-slate-400 dark:text-slate-500 mb-1 font-medium">Variasi:</div>
                                    <div class="relative w-full max-w-[130px]">
                                        <select wire:change="changeVariant({{ $item->id }}, $event.target.value)" class="text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-lg pl-3 pr-8 py-1.5 focus:ring-2 focus:ring-terra-500 focus:border-terra-500 focus:outline-none cursor-pointer transition-all appearance-none w-full">
                                            @foreach($item->product->variants as $var)
                                                <option value="{{ $var->id }}" @selected($item->product_variant_id == $var->id)>
                                                    {{ $var->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400 font-medium">-</span>
                                @endif
                            </div>

                            <!-- Unit Price (Col 1) -->
                            <div class="col-span-1 text-slate-500 dark:text-slate-400 text-xs font-medium">
                                Rp{{ number_format($item->variant ? $item->variant->final_price : ($item->product->discount_price ?: $item->product->price), 0, ',', '.') }}
                            </div>

                            <!-- Quantity (Col 2) -->
                            <div class="col-span-2">
                                <div class="flex items-center border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 h-8 w-24">
                                    <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" class="w-8 h-full text-slate-500 hover:text-terra-500 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-l-lg transition-colors focus:outline-none font-bold text-xs cursor-pointer">-</button>
                                    <input type="number" readonly value="{{ $item->quantity }}" class="w-8 h-full text-center border-0 focus:ring-0 text-slate-900 dark:text-white font-bold p-0 text-xs bg-transparent">
                                    <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" class="w-8 h-full text-slate-500 hover:text-terra-500 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-r-lg transition-colors focus:outline-none font-bold text-xs cursor-pointer">+</button>
                                </div>
                            </div>

                            <!-- Subtotal (Col 2) -->
                            <div class="col-span-2 text-right">
                                <span class="font-display text-base font-black text-terra-600 dark:text-terra-400">
                                    Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- Delete (Col 1) -->
                            <div class="col-span-1 text-right">
                                <button wire:click="removeItem({{ $item->id }})" class="text-slate-400 hover:text-red-500 transition-colors text-xs font-bold font-display uppercase tracking-wider cursor-pointer">
                                    Hapus
                                </button>
                            </div>
                        </li>

                        <!-- Mobile Layout -->
                        <li class="p-4 flex flex-col gap-3 md:hidden">
                            <div class="flex gap-3 items-start">
                                <input type="checkbox" wire:model.live="selectedItems" value="{{ $item->id }}" class="w-5 h-5 text-terra-500 rounded border-slate-300 dark:border-slate-600 focus:ring-terra-500 shrink-0 cursor-pointer mt-1">
                                <a href="/produk/{{ $item->product->slug }}" class="w-16 h-16 flex-shrink-0 bg-slate-50 dark:bg-slate-800 rounded-lg overflow-hidden border border-slate-100 dark:border-slate-700 block">
                                    @if($item->product->primary_image)
                                        <img src="{{ $item->product->primary_image }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600 text-xs">No Image</div>
                                    @endif
                                </a>
                                <div class="flex-grow min-w-0">
                                    <span class="block font-display text-[9px] text-terra-500 font-bold mb-0.5 uppercase tracking-widest">{{ $item->product->category->name ?? 'Kategori' }}</span>
                                    <h3 class="font-display text-sm font-bold text-slate-900 dark:text-white leading-tight">
                                        <a href="/produk/{{ $item->product->slug }}" class="hover:text-terra-500 transition-colors">{{ $item->product->name }}</a>
                                    </h3>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-green-50 dark:bg-green-950/50 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800 mt-1">
                                        Ready Stock
                                    </span>
                                </div>
                                <button wire:click="removeItem({{ $item->id }})" class="text-slate-400 hover:text-red-500 transition-colors text-xs font-bold font-display uppercase tracking-wider mt-1 cursor-pointer">
                                    Hapus
                                </button>
                            </div>

                            <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-50 dark:border-slate-800">
                                <div>
                                    @if($item->product->variants->count() > 0)
                                        <div class="relative inline-block">
                                            <select wire:change="changeVariant({{ $item->id }}, $event.target.value)" class="text-xs font-semibold text-slate-700 dark:text-slate-200 bg-slate-50 dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700 rounded-lg pl-3 pr-8 py-1 focus:ring-2 focus:ring-terra-500 focus:border-terra-500 focus:outline-none cursor-pointer transition-all appearance-none">
                                                @foreach($item->product->variants as $var)
                                                    <option value="{{ $var->id }}" @selected($item->product_variant_id == $var->id)>
                                                        {{ $var->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex items-center gap-3">
                                    <div class="flex items-center border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 h-8 w-24 shrink-0">
                                        <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" class="w-7 h-full text-slate-500 hover:text-terra-500 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-l-lg transition-colors focus:outline-none font-bold text-xs cursor-pointer">-</button>
                                        <input type="number" readonly value="{{ $item->quantity }}" class="w-10 h-full text-center border-0 focus:ring-0 text-slate-900 dark:text-white font-bold p-0 text-xs bg-transparent">
                                        <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" class="w-7 h-full text-slate-500 hover:text-terra-500 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-r-lg transition-colors focus:outline-none font-bold text-xs cursor-pointer">+</button>
                                    </div>
                                    <div class="font-display text-sm font-black text-terra-600 dark:text-terra-400">
                                        Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs p-6 sticky top-24">
                    <h3 class="font-display text-fluid-h3 font-black text-slate-900 dark:text-white mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">Ringkasan Belanja</h3>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-slate-600 dark:text-slate-300">
                            <span>Total Harga ({{ count($selectedItems) }} Barang)</span>
                            <span class="font-medium text-slate-900 dark:text-white">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600 dark:text-slate-300">
                            <span>Ongkos Kirim</span>
                            <span class="font-medium text-slate-900 dark:text-white italic text-sm">Dihitung saat Checkout</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-slate-100 dark:border-slate-800 pt-4 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-slate-900 dark:text-white text-lg">Subtotal</span>
                            <span class="font-display font-black text-terra-600 dark:text-terra-400 text-fluid-h2">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    @if($this->orderMode === 'whatsapp')
                        <div class="mb-4 p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-800 dark:text-emerald-300 text-xs flex items-start gap-2">
                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                            <span>Pemesanan langsung via WhatsApp Admin aktif. Seluruh item yang Anda centang akan dirangkum otomatis ke dalam chat WhatsApp.</span>
                        </div>

                        <button wire:click="checkoutSelected" wire:loading.attr="disabled" class="font-display w-full flex justify-center items-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 px-4 rounded-xl shadow-md shadow-emerald-600/20 transition-all gap-2 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer" @if(empty($selectedItems)) disabled @endif>
                            <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            <span wire:loading.remove wire:target="checkoutSelected">Pesan via WhatsApp</span>
                            <span wire:loading wire:target="checkoutSelected">Mempersiapkan Pesan...</span>
                        </button>
                    @else
                        <button wire:click="checkoutSelected" class="font-display w-full flex justify-center items-center bg-terra-500 hover:bg-terra-600 text-white font-bold py-4 px-4 rounded-xl shadow-md shadow-terra-500/20 transition-all gap-2 disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer" @if(empty($selectedItems)) disabled @endif>
                            Lanjut ke Checkout
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        </button>
                    @endif
                </div>
            </div>
            
        </div>
        @else
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-16 text-center shadow-soft-xs">
            <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
            </div>
            <h3 class="font-display text-fluid-h2 font-black text-slate-900 dark:text-white mb-3">Keranjang Masih Kosong</h3>
            <p class="text-slate-500 dark:text-slate-400 mb-8 max-w-md mx-auto">Anda belum menambahkan produk apapun ke dalam keranjang. Silakan lihat katalog untuk menemukan roster terbaik kami.</p>
            <a href="/katalog" class="font-display inline-block bg-terra-500 text-white px-8 py-3.5 rounded-xl font-bold hover:bg-terra-600 transition-colors shadow-md shadow-terra-500/20">
                Mulai Belanja
            </a>
        </div>
        @endif

    </div>
</div>
