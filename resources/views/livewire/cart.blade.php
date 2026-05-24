<div class="bg-slate-50 min-h-screen py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(count($cartItems) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                @if (session()->has('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden mb-4">
                    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" wire:model.live="selectAll" class="w-5 h-5 text-terra-500 rounded border-gray-300 focus:ring-terra-500">
                            <span class="font-display text-sm font-bold text-slate-700">Pilih Semua</span>
                        </label>
                        <button wire:click="deleteSelected" class="font-display text-sm text-red-500 hover:text-red-700 font-bold transition-colors disabled:opacity-50 disabled:cursor-not-allowed" @if(empty($selectedItems)) disabled @endif>
                            Hapus Terpilih
                        </button>
                    </div>
                    <ul class="divide-y divide-gray-100">
                        @foreach($cartItems as $item)
                        <!-- Desktop Layout -->
                        <li class="p-6 hidden md:grid md:grid-cols-12 md:gap-4 md:items-center">
                            <!-- Product Info (Col 4) -->
                            <div class="col-span-4 flex items-center gap-4 min-w-0">
                                <input type="checkbox" wire:model.live="selectedItems" value="{{ $item->id }}" class="w-5 h-5 text-terra-500 rounded border-slate-300 focus:ring-terra-500 shrink-0 cursor-pointer">
                                <a href="/produk/{{ $item->product->slug }}" class="w-16 h-16 flex-shrink-0 bg-slate-50 rounded-lg overflow-hidden border border-slate-100 block">
                                    @if($item->product->primary_image)
                                        <img src="{{ $item->product->primary_image }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300 text-xs">No Image</div>
                                    @endif
                                </a>
                                <div class="min-w-0">
                                    <span class="block font-display text-[9px] text-terra-500 font-bold mb-0.5 uppercase tracking-widest">{{ $item->product->category->name ?? 'Kategori' }}</span>
                                    <h3 class="font-display text-sm font-bold text-slate-900 truncate">
                                        <a href="/produk/{{ $item->product->slug }}" class="hover:text-terra-500 transition-colors" title="{{ $item->product->name }}">{{ $item->product->name }}</a>
                                    </h3>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-green-50 text-green-700 border border-green-200 mt-1">
                                        Ready Stock
                                    </span>
                                </div>
                            </div>

                            <!-- Variation (Col 2) -->
                            <div class="col-span-2">
                                @if($item->product->variants->count() > 0)
                                    <div class="text-xs text-slate-400 mb-1 font-medium">Variasi:</div>
                                    <div class="relative w-full max-w-[130px]">
                                        <select wire:change="changeVariant({{ $item->id }}, $event.target.value)" class="text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg pl-3 pr-8 py-1.5 focus:ring-2 focus:ring-terra-500/10 focus:border-terra-500 focus:outline-none cursor-pointer transition-all appearance-none w-full">
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
                            <div class="col-span-1 text-slate-500 text-xs font-medium">
                                Rp{{ number_format($item->variant ? $item->variant->final_price : ($item->product->discount_price ?: $item->product->price), 0, ',', '.') }}
                            </div>

                            <!-- Quantity (Col 2) -->
                            <div class="col-span-2">
                                <div class="flex items-center border border-slate-200 rounded-lg bg-white h-8 w-24">
                                    <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" class="w-8 h-full text-slate-500 hover:text-terra-500 hover:bg-slate-50 rounded-l-lg transition-colors focus:outline-none font-bold text-xs">-</button>
                                    <input type="number" readonly value="{{ $item->quantity }}" class="w-8 h-full text-center border-0 focus:ring-0 text-slate-900 font-bold p-0 text-xs">
                                    <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" class="w-8 h-full text-slate-500 hover:text-terra-500 hover:bg-slate-50 rounded-r-lg transition-colors focus:outline-none font-bold text-xs">+</button>
                                </div>
                            </div>

                            <!-- Subtotal (Col 2) -->
                            <div class="col-span-2 text-right">
                                <span class="font-display text-base font-black text-terra-600">
                                    Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- Delete (Col 1) -->
                            <div class="col-span-1 text-right">
                                <button wire:click="removeItem({{ $item->id }})" class="text-slate-400 hover:text-red-500 transition-colors text-xs font-bold font-display uppercase tracking-wider">
                                    Hapus
                                </button>
                            </div>
                        </li>

                        <!-- Mobile Layout -->
                        <li class="p-4 flex flex-col gap-3 md:hidden">
                            <div class="flex gap-3 items-start">
                                <input type="checkbox" wire:model.live="selectedItems" value="{{ $item->id }}" class="w-5 h-5 text-terra-500 rounded border-slate-300 focus:ring-terra-500 shrink-0 cursor-pointer mt-1">
                                <a href="/produk/{{ $item->product->slug }}" class="w-16 h-16 flex-shrink-0 bg-slate-50 rounded-lg overflow-hidden border border-slate-100 block">
                                    @if($item->product->primary_image)
                                        <img src="{{ $item->product->primary_image }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300 text-xs">No Image</div>
                                    @endif
                                </a>
                                <div class="flex-grow min-w-0">
                                    <span class="block font-display text-[9px] text-terra-500 font-bold mb-0.5 uppercase tracking-widest">{{ $item->product->category->name ?? 'Kategori' }}</span>
                                    <h3 class="font-display text-sm font-bold text-slate-900 leading-tight">
                                        <a href="/produk/{{ $item->product->slug }}" class="hover:text-terra-500 transition-colors">{{ $item->product->name }}</a>
                                    </h3>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-green-50 text-green-700 border border-green-200 mt-1">
                                        Ready Stock
                                    </span>
                                </div>
                                <button wire:click="removeItem({{ $item->id }})" class="text-slate-400 hover:text-red-500 transition-colors text-xs font-bold font-display uppercase tracking-wider mt-1">
                                    Hapus
                                </button>
                            </div>

                            <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-50">
                                <div>
                                    @if($item->product->variants->count() > 0)
                                        <div class="relative inline-block">
                                            <select wire:change="changeVariant({{ $item->id }}, $event.target.value)" class="text-xs font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-lg pl-3 pr-8 py-1 focus:ring-2 focus:ring-terra-500/10 focus:border-terra-500 focus:outline-none cursor-pointer transition-all appearance-none">
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
                                    <div class="flex items-center border border-slate-200 rounded-lg bg-white h-8 w-24 shrink-0">
                                        <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" class="w-7 h-full text-slate-500 hover:text-terra-500 hover:bg-slate-50 rounded-l-lg transition-colors focus:outline-none font-bold text-xs">-</button>
                                        <input type="number" readonly value="{{ $item->quantity }}" class="w-10 h-full text-center border-0 focus:ring-0 text-slate-900 font-bold p-0 text-xs">
                                        <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" class="w-7 h-full text-slate-500 hover:text-terra-500 hover:bg-slate-50 rounded-r-lg transition-colors focus:outline-none font-bold text-xs">+</button>
                                    </div>
                                    <div class="font-display text-sm font-black text-terra-600">
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
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 sticky top-24">
                    <h3 class="font-display text-fluid-h3 font-black text-slate-900 mb-6 pb-4 border-b border-gray-100">Ringkasan Belanja</h3>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-slate-600">
                            <span>Total Harga ({{ count($selectedItems) }} Barang)</span>
                            <span class="font-medium text-slate-900">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Ongkos Kirim</span>
                            <span class="font-medium text-slate-900 italic text-sm">Dihitung saat Checkout</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 pt-4 mb-8">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-slate-900 text-lg">Subtotal</span>
                            <span class="font-display font-black text-terra-600 text-fluid-h2">Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <button wire:click="checkoutSelected" class="font-display w-full flex justify-center items-center bg-terra-500 hover:bg-terra-600 text-white font-bold py-4 px-4 rounded-md shadow-md shadow-terra-500/20 transition-all gap-2 disabled:opacity-50 disabled:cursor-not-allowed" @if(empty($selectedItems)) disabled @endif>
                        Lanjut ke Checkout
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </button>
                </div>
            </div>
            
        </div>
        @else
        <div class="bg-white rounded-xl border border-gray-100 p-16 text-center shadow-sm">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
            </div>
            <h3 class="font-display text-fluid-h2 font-black text-slate-900 mb-3">Keranjang Masih Kosong</h3>
            <p class="text-slate-500 mb-8 max-w-md mx-auto">Anda belum menambahkan produk apapun ke dalam keranjang. Silakan lihat katalog untuk menemukan roster terbaik kami.</p>
            <a href="/katalog" class="font-display inline-block bg-terra-500 text-white px-8 py-3 rounded-md font-bold hover:bg-terra-600 transition-colors shadow-md shadow-terra-500/20">
                Mulai Belanja
            </a>
        </div>
        @endif

    </div>
</div>
