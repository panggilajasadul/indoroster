<div class="relative inline-block text-left" 
     x-data="{ open: false, cartTimer: null }"
     @mouseenter="clearTimeout(cartTimer); open = true; $wire.loadCartItems()"
     @mouseleave="cartTimer = setTimeout(() => { open = false }, 300)">
    
    <!-- Cart Icon Button -->
    <a href="/keranjang" class="relative p-2 block text-slate-600 hover:text-terra-500 transition-colors" wire:navigate>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
        </svg>
        
        @if($count > 0)
        <span class="absolute top-1 right-1 bg-terra-500 text-white text-[10px] font-bold h-4 w-4 rounded-full flex items-center justify-center">
            {{ $count > 99 ? '99+' : $count }}
        </span>
        @endif
    </a>

    <!-- Dropdown Mini Cart (Shopee Style) -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="origin-top-right absolute right-0 mt-2 w-[calc(100vw-2rem)] sm:w-96 rounded-xl shadow-2xl bg-white border border-slate-100 divide-y divide-slate-100 focus:outline-none z-50 overflow-hidden"
         style="display: none;">
         
        <div class="px-4 py-3 bg-slate-50/50">
            <span class="text-xs font-semibold text-slate-400">Baru Ditambahkan</span>
        </div>

        <div class="max-h-[300px] overflow-y-auto divide-y divide-slate-50">
            @forelse($cartItems as $item)
                <a href="/keranjang" wire:navigate class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition-colors group">
                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-10 h-10 rounded border border-slate-100 object-cover flex-shrink-0">
                    <div class="flex-grow min-w-0">
                        <p class="text-xs font-medium text-slate-800 truncate group-hover:text-terra-600 transition-colors">{{ $item['name'] }}</p>
                        @if($item['variant_name'])
                            <p class="text-[10px] text-slate-400 mt-0.5">Varian: {{ $item['variant_name'] }}</p>
                        @endif
                        <p class="text-[10px] text-slate-400 mt-0.5">Jumlah: {{ $item['quantity'] }}</p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <span class="text-xs font-bold text-terra-600">{{ $item['price'] }}</span>
                    </div>
                </a>
            @empty
                <div class="px-6 py-8 text-center">
                    <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-2 text-slate-300">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </div>
                    <p class="text-xs text-slate-500 font-medium">Keranjang belanja kosong</p>
                </div>
            @endforelse
        </div>

        @if($count > 0)
            <div class="px-4 py-3 bg-slate-50/50 flex items-center justify-between">
                <span class="text-[11px] text-slate-500 font-medium">
                    @if($count > 5)
                        {{ $count - 5 }} Produk Lainnya
                    @else
                        {{ $count }} Produk di Keranjang
                    @endif
                </span>
                <a href="/keranjang" wire:navigate class="bg-terra-500 text-white text-xs font-bold px-3 py-2 rounded-lg hover:bg-terra-600 transition shadow-sm">
                    Tampilkan Keranjang
                </a>
            </div>
        @endif
    </div>
</div>
