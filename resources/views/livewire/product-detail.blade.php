<div class="bg-white min-h-screen py-4 sm:py-8 lg:py-12">
@push('seo')
    <x-product-schema :product="$product" />    
@endpush
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="mb-4 sm:mb-8">
            <ol class="flex flex-wrap items-center gap-1 text-sm text-slate-500">
                <li>
                    <a href="{{ route('home') }}" class="hover:text-terra-500 transition-colors">Beranda</a>
                </li>
                <li aria-hidden="true"><span class="mx-1">/</span></li>
                <li>
                    <a href="{{ route('catalog') }}" class="hover:text-terra-500 transition-colors">Katalog</a>
                </li>
                @if($product->category)
                <li aria-hidden="true"><span class="mx-1">/</span></li>
                <li>
                    <a href="{{ route('catalog', ['category' => $product->category->slug]) }}" class="hover:text-terra-500 transition-colors">{{ $product->category->name }}</a>
                </li>
                @endif
                <li aria-hidden="true"><span class="mx-1">/</span></li>
                <li>
                    <span class="text-slate-900 font-medium" aria-current="page">{{ $product->name }}</span>
                </li>
            </ol>
        </nav>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-8 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-start gap-3">
                <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="flex-grow">{{ session('success') }}</div>
                <a href="{{ route('cart') }}" class="whitespace-nowrap font-medium text-green-800 hover:underline">Lihat Keranjang &rarr;</a>
            </div>
        @endif
        
        @if (session()->has('error'))
            <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="flex-grow">{{ session('error') }}</div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-16">
            
            <!-- Left Column -->
            <div class="flex flex-col w-full">
                <!-- Gallery -->
                <div class="flex flex-col gap-3">
                    <!-- Main Image -->
                    <div class="w-full relative bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 aspect-square">
                        @if($activeImage)
                            @if($activeMediaType === 'video')
                                @if(str_contains($activeImage, 'youtube.com') || str_contains($activeImage, 'youtu.be'))
                                    @php
                                        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $activeImage, $matches);
                                        $ytId = $matches[1] ?? '';
                                    @endphp
                                    @if($ytId)
                                        <iframe class="absolute inset-0 w-full h-full" src="https://www.youtube.com/embed/{{ $ytId }}?autoplay=1&mute=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                    @else
                                        <div class="absolute inset-0 w-full h-full flex items-center justify-center bg-slate-100 text-slate-500">Invalid YouTube URL</div>
                                    @endif
                                @else
                                    <video src="{{ $activeImage }}" class="absolute inset-0 w-full h-full object-cover" controls autoplay muted loop playsinline></video>
                                @endif
                            @else
                                <img src="{{ $activeImage }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover">
                            @endif
                        @else
                            <div class="absolute inset-0 w-full h-full flex items-center justify-center text-slate-400">No Image</div>
                        @endif
                    </div>

                    <!-- Thumbnails -->
                    @if($product->media->count() > 0)
                    <div class="flex flex-row gap-3 overflow-x-auto w-full pb-2 hide-scrollbar shrink-0">
                        @foreach($product->media as $media)
                            @php
                                $mediaUrl = str_starts_with($media->media_url, 'http') ? $media->media_url : asset('storage/' . $media->media_url);
                            @endphp
                            <button 
                                wire:click="setActiveImage('{{ $mediaUrl }}', '{{ $media->media_type }}')"
                                wire:mouseenter.debounce.100ms="setActiveImage('{{ $mediaUrl }}', '{{ $media->media_type }}')"
                                class="relative flex-shrink-0 w-20 h-20 md:w-24 md:h-24 rounded-lg overflow-hidden border-2 transition-all {{ $activeImage === $mediaUrl ? 'border-terra-500' : 'border-transparent hover:border-gray-300' }}">
                                @if($media->media_type === 'image')
                                    <img src="{{ $mediaUrl }}" alt="Thumbnail" class="w-full h-full object-cover">
                                @elseif($media->media_type === 'video')
                                    @if(str_contains($media->media_url, 'youtube.com') || str_contains($media->media_url, 'youtu.be'))
                                        @php
                                            preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $media->media_url, $ytMatches);
                                            $ytThumbId = $ytMatches[1] ?? '';
                                        @endphp
                                        <div class="w-full h-full relative">
                                            @if($ytThumbId)
                                                <img src="https://img.youtube.com/vi/{{ $ytThumbId }}/mqdefault.jpg" alt="Video thumbnail" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-slate-800"></div>
                                            @endif
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/30">
                                                <svg class="w-6 h-6 text-white/90" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-full h-full relative">
                                            <video src="{{ $mediaUrl }}" class="w-full h-full object-cover" autoplay muted loop playsinline></video>
                                            <div class="absolute bottom-1 right-1 bg-black/60 rounded px-1">
                                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </button>
                        @endforeach
                    </div>
                    @endif
                </div>

            </div>

            <!-- Product Info -->
            <div class="flex flex-col lg:justify-between lg:h-full">
                <div>
                    <div class="mb-4">
                        <h1 class="font-display text-xl sm:text-2xl font-bold text-slate-900 tracking-tight mb-1">{{ $product->name }}</h1>
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1.5 text-xs sm:text-sm text-slate-500 mb-3 border-b border-gray-100 pb-3">
                            <span class="inline-flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg> {{ $product->category->name ?? 'Kategori' }}</span>
                            <span>•</span>
                            <span class="inline-flex items-center gap-1.5"><svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg> {{ $product->average_rating }} ({{ $ratingStats['total'] }} ulasan)</span>
                            <span>•</span>
                            <span class="text-slate-900 font-semibold">{{ $product->formatted_total_sold }} Terjual</span>
                        </div>

                        <div class="flex items-baseline gap-2.5 flex-wrap mt-2">
                            @if(!$selectedVariant && $product->variants->count() > 0)
                                <span class="text-2xl sm:text-3xl font-black text-terra-500">{{ $this->priceRange }}</span>
                            @else
                                <span class="text-2xl sm:text-3xl font-black text-terra-500">Rp{{ number_format($this->activePrice, 0, ',', '.') }}</span>
                            @endif

                            @if($product->original_price > 0 && (!$selectedVariant || $product->original_price > $this->activePrice))
                                <span class="text-sm sm:text-base text-slate-400 line-through">Rp{{ number_format($product->original_price, 0, ',', '.') }}</span>
                                @php
                                    $discountPercentage = round((($product->original_price - $this->activePrice) / $product->original_price) * 100);
                                @endphp
                                @if($discountPercentage > 0)
                                    <span class="text-[10px] sm:text-xs font-bold text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded shadow-sm border border-slate-200/50">-{{ $discountPercentage }}%</span>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Variants -->
                    @if($product->variants->count() > 0)
                    <div class="mb-4 sm:mb-5">
                        <h3 class="font-display text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wider mb-2">Varian Produk</h3>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach($product->variants as $variant)
                            <label class="relative cursor-pointer">
                                <input type="radio" wire:model.live="selectedVariant" value="{{ $variant->id }}" class="peer sr-only" name="variant">
                                <div class="px-3 py-1.5 bg-white border-2 border-gray-200 rounded-lg text-xs sm:text-sm font-medium text-slate-600 peer-checked:border-terra-500 peer-checked:text-terra-600 peer-checked:bg-terra-50 hover:border-gray-300 transition-all">
                                    {{ $variant->name }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Shipping & Guarantee Info -->
                    <div class="mt-6 pt-5 border-t border-slate-100 space-y-4">
                        <!-- Pengiriman -->
                        <div x-data="{ open: false }" class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4">
                            <span class="w-full sm:w-24 shrink-0 text-xs font-bold text-slate-400 uppercase tracking-wider pt-1.5">Pengiriman</span>
                            <div class="flex-1">
                                <button @click="open = !open" class="flex items-center justify-between w-full text-left focus:outline-none group cursor-pointer">
                                    <div class="flex items-center gap-2.5">
                                        <div class="p-1.5 bg-terra-50 rounded-lg text-terra-600 shrink-0 group-hover:bg-terra-100 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1" />
                                            </svg>
                                        </div>
                                        <span class="text-xs sm:text-sm font-semibold text-slate-800">Pengiriman via Armada Indoroster</span>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" 
                                     x-collapse
                                     class="mt-2 pl-9 pr-4 text-[11px] sm:text-xs text-slate-500 leading-relaxed"
                                     style="display: none;">
                                    Roster dikirim langsung dengan armada operasional kami sendiri untuk menjamin keamanan & meminimalisir risiko kerusakan selama perjalanan.
                                </div>
                            </div>
                        </div>

                        <!-- Jaminan -->
                        <div x-data="{ open: true }" class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4 pt-4 border-t border-slate-50">
                            <span class="w-full sm:w-24 shrink-0 text-xs font-bold text-slate-400 uppercase tracking-wider pt-1.5">Jaminan</span>
                            <div class="flex-1">
                                <button @click="open = !open" class="flex items-center justify-between w-full text-left focus:outline-none group cursor-pointer">
                                    <div class="flex items-center gap-2.5">
                                        <div class="p-1.5 bg-terra-50 rounded-lg text-terra-600 shrink-0 group-hover:bg-terra-100 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <span class="text-xs sm:text-sm font-semibold text-slate-800">100% Original & Garansi Pecah</span>
                                    </div>
                                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="open" 
                                     x-collapse
                                     class="mt-3 pl-9 pr-4 flex flex-col gap-3"
                                     style="display: none;">
                                    <!-- Asli -->
                                    <div class="flex items-start gap-2.5">
                                        <div class="p-1 bg-slate-100 rounded text-slate-600 shrink-0 mt-0.5">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="text-xs font-bold text-slate-700">Produk 100% Asli</span>
                                            <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Semua roster beton dijamin original langsung diproduksi dari pabrik Indoroster.</p>
                                        </div>
                                    </div>
                                    <!-- Garansi Pecah -->
                                    <div class="flex items-start gap-2.5">
                                        <div class="p-1 bg-slate-100 rounded text-slate-600 shrink-0 mt-0.5">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="text-xs font-bold text-slate-700">Garansi Barang Pecah Diganti</span>
                                            <p class="text-[11px] text-slate-500 mt-0.5 leading-relaxed">Jika ada barang pecah selama pengiriman oleh armada kami, akan diganti baru 100% tanpa biaya tambahan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 mt-4">
                    <!-- Roster Calculator (Horizontal) -->
                    <div class="p-4 bg-terra-50 rounded-xl border border-terra-100 shadow-sm w-full">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-6 h-6 rounded bg-terra-500 flex items-center justify-center text-white shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                            <h3 class="font-display text-sm font-bold text-slate-900">Kalkulator Kebutuhan</h3>
                            <p class="text-[10px] text-slate-500 ml-auto italic mt-0.5">*Ukuran {{ $product->parsed_dimensions['width'] ?? '20' }}x{{ $product->parsed_dimensions['height'] ?? '20' }} cm</p>
                        </div>
                        
                        <div class="flex flex-col lg:flex-row items-end gap-2.5">
                            <div class="grid grid-cols-2 gap-2.5 w-full lg:w-44 shrink-0">
                                <div>
                                    <label class="font-display block text-[9px] font-bold text-slate-700 uppercase mb-0.5">Panjang (m)</label>
                                    <input type="number" step="0.1" wire:model.live="wall_width" class="w-full h-9 bg-white border-gray-200 rounded-lg text-xs focus:border-terra-500 focus:ring-terra-500 px-2.5">
                                </div>
                                <div>
                                    <label class="font-display block text-[9px] font-bold text-slate-700 uppercase mb-0.5">Tinggi (m)</label>
                                    <input type="number" step="0.1" wire:model.live="wall_height" class="w-full h-9 bg-white border-gray-200 rounded-lg text-xs focus:border-terra-500 focus:ring-terra-500 px-2.5">
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-2 bg-white rounded-lg border border-terra-100 w-full lg:flex-1 h-9">
                                <div class="flex items-center gap-1.5">
                                    <div class="text-[9px] text-slate-400 font-bold uppercase hidden xl:block">Estimasi:</div>
                                    <div class="text-base font-black text-terra-600 leading-none">{{ $calculatedRequirement }} <span class="text-[10px] font-normal text-slate-500">pcs</span></div>
                                </div>
                                <label class="flex items-center gap-1 cursor-pointer">
                                    <input type="checkbox" wire:model.live="include_waste" class="w-3 h-3 text-terra-500 border-gray-300 rounded focus:ring-terra-500">
                                    <span class="text-[9px] text-slate-600">+5% Cadangan</span>
                                </label>
                            </div>

                            <button wire:click="applyCalculatedQuantity" class="w-full lg:w-auto h-9 px-3 bg-white border-2 border-terra-500 text-terra-600 hover:bg-terra-50 text-[11px] font-bold rounded-lg transition-all flex items-center justify-center gap-1 shrink-0">
                                <span>Gunakan</span>
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Add to Cart Action -->
                    <div class="p-4 bg-slate-50 rounded-xl border border-gray-100 w-full">
                        <div class="flex flex-col lg:flex-row lg:items-end gap-3.5 w-full">
                            <div class="w-full lg:w-auto shrink-0">
                                <label class="font-display block text-xs font-bold text-slate-900 mb-1.5">Jumlah Beli</label>
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center border border-gray-300 rounded-md bg-white w-28 h-10">
                                        <button wire:click="decrementQuantity" class="w-8 h-full text-gray-500 hover:text-terra-500 hover:bg-gray-50 rounded-l-md transition-colors focus:outline-none">-</button>
                                        <input type="number" wire:model.blur="quantity" class="w-12 h-full text-center border-0 focus:ring-0 text-slate-900 font-bold p-0 text-sm" min="{{ $product->min_order ?? 1 }}" max="{{ $this->activeStock }}">
                                        <button wire:click="incrementQuantity" class="w-8 h-full text-gray-500 hover:text-terra-500 hover:bg-gray-50 rounded-r-md transition-colors focus:outline-none">+</button>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-xs sm:text-sm text-slate-500 font-semibold">Tersedia {{ $this->activeStock }}</span>
                                        @if($product->min_order > 0)
                                            <span class="text-[10px] text-terra-600 font-bold">Min. pembelian {{ $product->min_order }} pcs</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                                                     <div class="flex flex-col sm:flex-row gap-2.5 w-full lg:flex-1">
                                <button @click="openOrderWa($wire.quantity)" type="button" class="w-full sm:flex-1 h-10 bg-white border-2 border-terra-500 text-terra-600 hover:bg-terra-50 text-sm font-bold rounded-md transition-all flex items-center justify-center gap-2 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <span>Keranjang</span>
                                </button>
 
                                <button @click="openOrderWa($wire.quantity)" type="button" class="w-full sm:flex-1 h-10 bg-terra-500 hover:bg-terra-600 text-white text-sm font-bold rounded-md shadow-md shadow-terra-500/20 transition-all flex items-center justify-center gap-2 cursor-pointer">
                                    <span>Beli Sekarang</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details Section (Full Width) -->
        <div x-data="{ activeTab: 'spec' }" class="mt-16 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <!-- Tab Headers -->
            <div class="flex overflow-x-auto whitespace-nowrap border-b border-gray-100 bg-slate-50/50 scrollbar-hide">
                <button 
                    @click="activeTab = 'spec'" 
                    :class="{ 'bg-white border-b-2 border-terra-500 text-terra-600 shadow-[0_1px_0_0_#fff]': activeTab === 'spec', 'text-slate-500 hover:text-slate-700 hover:bg-gray-100/50': activeTab !== 'spec' }"
                    class="font-display shrink-0 px-6 sm:px-12 py-5 font-bold text-sm sm:text-base transition-all duration-200 outline-none"
                >
                    <span class="flex items-center justify-center gap-2 sm:gap-3">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Spesifikasi Teknis
                    </span>
                </button>
                <button 
                    @click="activeTab = 'how-to'" 
                    :class="{ 'bg-white border-b-2 border-terra-500 text-terra-600 shadow-[0_1px_0_0_#fff]': activeTab === 'how-to', 'text-slate-500 hover:text-slate-700 hover:bg-gray-100/50': activeTab !== 'how-to' }"
                    class="font-display shrink-0 px-6 sm:px-12 py-5 font-bold text-sm sm:text-base transition-all duration-200 outline-none"
                >
                    <span class="flex items-center justify-center gap-2 sm:gap-3">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Panduan & Cara Pesan
                    </span>
                </button>
            </div>

            <!-- Tab Content -->
            <div class="p-8 lg:p-12">
                <!-- Tab 1: Specifications -->
                <div x-show="activeTab === 'spec'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                        <div class="md:col-span-1">
                            <h4 class="font-display text-sm font-bold text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <span class="w-1.5 h-6 bg-terra-500 rounded-full"></span>
                                Detail Produk
                            </h4>
                            <div class="space-y-5">
                                <div class="flex items-center justify-between pb-4 border-b border-gray-50">
                                    <span class="text-slate-500 text-sm">Dimensi Satuan</span>
                                    <span class="font-bold text-slate-900 text-right">{{ $product->dimensions ?: 'Standard' }}</span>
                                </div>
                                <div class="flex items-center justify-between pb-4 border-b border-gray-50">
                                    <span class="text-slate-500 text-sm">Berat per Pcs</span>
                                    <span class="font-bold text-slate-900 text-right">{{ $product->weight ?: '0' }} kg</span>
                                </div>
                                <div class="flex items-center justify-between pb-4 border-b border-gray-50">
                                    <span class="text-slate-500 text-sm">Kode Produk (SKU)</span>
                                    <span class="font-mono text-sm bg-slate-100 px-3 py-1 rounded-md text-slate-700 tracking-wider">{{ $product->sku ?: '-' }}</span>
                                </div>
                                <div class="flex items-center justify-between pb-4 border-b border-gray-50">
                                    <span class="text-slate-500 text-sm">Kategori Produk</span>
                                    <span class="font-bold text-terra-600 text-right">{{ $product->category->name }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="md:col-span-2">
                            <h4 class="font-display text-sm font-bold text-slate-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <span class="w-1.5 h-6 bg-slate-300 rounded-full"></span>
                                Catatan Penting
                            </h4>
                            <div class="prose prose-slate max-w-none">
                                <p class="text-slate-600 leading-relaxed text-lg">
                                    Produk roster beton kami diproduksi menggunakan material premium dengan teknik casting tekanan tinggi untuk menghasilkan kepadatan maksimal. Finishing halus dan presisi memudahkan proses pemasangan.
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-8">
                                    <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl">
                                        <div class="p-2 bg-white rounded-lg shadow-sm"><svg class="w-5 h-5 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-sm">Kualitas Terjamin</div>
                                            <div class="text-xs text-slate-500 mt-0.5">Tahan cuaca ekstrem dan tidak mudah berlumut.</div>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl">
                                        <div class="p-2 bg-white rounded-lg shadow-sm"><svg class="w-5 h-5 text-terra-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg></div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-sm">Produksi Sendiri</div>
                                            <div class="text-xs text-slate-500 mt-0.5">Langsung dari pabrik kami untuk harga terbaik.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: How to Order (Tidy Formatting) -->
                <div x-show="activeTab === 'how-to'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <div class="order-guide-content">
                        <style>
                            .order-guide-content { line-height: 1.8 !important; color: #475569 !important; }
                            .order-guide-content h3 { font-size: 1.5rem !important; font-weight: 800 !important; color: #0f172a !important; margin-bottom: 2rem !important; margin-top: 1rem !important; display: block !important; }
                            .order-guide-content h4 { font-size: 1.125rem !important; font-weight: 700 !important; color: #1e293b !important; margin-bottom: 1.5rem !important; margin-top: 2.5rem !important; display: block !important; }
                            .order-guide-content p { margin-bottom: 1.5rem !important; display: block !important; }
                            .order-guide-content ul { margin-bottom: 2rem !important; list-style-type: disc !important; padding-left: 1.5rem !important; display: block !important; }
                            .order-guide-content li { margin-bottom: 1rem !important; padding-left: 0.5rem !important; display: list-item !important; }
                            .order-guide-content li strong { color: #0f172a !important; font-weight: 700 !important; }
                            .order-guide-content strong { color: #0f172a !important; }
                        </style>
                        <div class="prose max-w-none">
                            {!! $product->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rekomendasi Produk Viral Section -->
        @if($recommendedProducts->count() > 0)
        <div class="mt-20 border-t border-gray-100 pt-16">
            <div class="flex flex-col sm:flex-row sm:items-baseline justify-between mb-8 gap-4">
                <div>
                    <h2 class="font-display text-fluid-h2 font-black text-slate-900 tracking-tight flex items-center gap-2">
                        Rekomendasi Produk Viral <span class="inline-block animate-bounce">🔥</span>
                    </h2>
                    <p class="text-slate-500 text-sm mt-1">Pilihan roster beton minimalis terpopuler yang paling banyak dibeli pelanggan.</p>
                </div>
                <a href="{{ route('catalog') }}" class="text-sm font-bold text-terra-600 hover:text-terra-700 transition-colors flex items-center gap-1.5 shrink-0 group">
                    <span>Lihat Semua Katalog</span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-6">
                @foreach($recommendedProducts as $recProduct)
                <a href="{{ route('product.detail', $recProduct->slug) }}" class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 group flex flex-col overflow-hidden relative hover:border-terra-400">
                    <!-- Media Section -->
                    <div class="relative aspect-square overflow-hidden bg-gray-50">
                        @php
                            $recDisplay = $recProduct->primary_media;
                        @endphp

                        @if($recDisplay)
                            @if($recDisplay->media_type === 'video' && !str_contains($recDisplay->media_url, 'youtube.com') && !str_contains($recDisplay->media_url, 'youtu.be'))
                                <video src="{{ $recDisplay->formatted_url }}" 
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                    autoplay muted loop playsinline></video>
                            @else
                                <img src="{{ $recDisplay->media_type === 'image' ? $recDisplay->formatted_url : $recProduct->primary_image }}" alt="{{ $recProduct->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            @endif
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">No Image</div>
                        @endif

                        <!-- Badges -->
                        @if($recProduct->discount_percentage > 0)
                            <div class="absolute top-0 right-0 bg-red-50 text-red-600 border-l border-b border-red-100 text-[10px] font-bold px-2 py-0.5 rounded-bl-lg z-10 shadow-sm">
                                {{ $recProduct->discount_percentage }}% OFF
                            </div>
                        @endif

                        <div class="absolute top-2 left-2 flex flex-col gap-1 z-10">
                            <span class="bg-black/75 backdrop-blur-sm text-terra-400 text-[9px] font-black px-2 py-0.5 rounded-full tracking-wider uppercase flex items-center gap-1 shadow-sm">
                                <span class="w-1.5 h-1.5 bg-terra-500 rounded-full animate-ping"></span>
                                Viral
                            </span>
                        </div>

                        <!-- Video Indicator -->
                        @if($recProduct->has_video)
                            <div class="absolute bottom-2 right-2 bg-black/45 text-white rounded-full p-1.5 backdrop-blur-sm z-10 shadow-md">
                                <svg class="w-3.5 h-3.5 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                            </div>
                        @endif
                    </div>

                    <!-- Info Section -->
                    <div class="p-4 flex flex-col flex-grow">
                        <div class="text-xs text-slate-500 mb-1 font-semibold uppercase tracking-wider">{{ $recProduct->category->name ?? 'Roster' }}</div>
                        <div class="text-sm text-slate-800 leading-snug mb-2 line-clamp-2 font-bold group-hover:text-terra-600 transition-colors">
                            {{ $recProduct->name }}
                        </div>
                        
                        <div class="mt-auto">
                            <!-- Ratings & Sales info -->
                            <div class="flex items-center gap-1.5 mb-2.5">
                                <div class="flex text-amber-400">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                </div>
                                <span class="text-[11px] font-bold text-slate-700">{{ $recProduct->average_rating }}</span>
                                <span class="text-[11px] text-slate-400">•</span>
                                <span class="text-[11px] text-slate-500 font-medium">{{ $recProduct->total_sold > 0 ? $recProduct->formatted_total_sold . ' terjual' : '0 terjual' }}</span>
                            </div>

                            <div class="flex items-baseline justify-between gap-1 flex-wrap pt-2 border-t border-slate-50">
                                <span class="text-sm font-extrabold text-terra-600 leading-none">{{ $recProduct->formatted_price_range }}</span>
                                @if($recProduct->has_discount)
                                    <span class="text-[10px] text-slate-400 line-through leading-none">Rp{{ number_format($recProduct->original_price, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Review Section -->
        <div class="mt-20 border-t border-gray-100 pt-16">
            <div class="mb-12">
                <h2 class="font-display text-fluid-h2 font-black text-slate-900 mb-2">Penilaian Produk</h2>
                <p class="text-slate-500">Apa kata mereka yang sudah membeli roster di Indoroster</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-12">
                <!-- Rating Summary -->
                <div class="lg:col-span-1 bg-terra-50 rounded-2xl p-8 flex flex-col items-center justify-center text-center border border-terra-100">
                    <div class="text-5xl font-extrabold text-terra-600 mb-2">{{ $product->average_rating }} <span class="text-2xl text-terra-400 font-normal">/ 5</span></div>
                    <div class="flex gap-1 mb-4">
                        @for($i=1; $i<=5; $i++)
                            <svg class="w-6 h-6 {{ $i <= round($product->average_rating) ? 'text-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        @endfor
                    </div>
                    <p class="text-sm font-bold text-slate-600 uppercase tracking-wider">{{ $ratingStats['total'] }} PENILAIAN</p>
                </div>

                <!-- Rating Bars & Filter -->
                <div class="lg:col-span-3">
                    <div class="flex flex-wrap gap-3 items-center mb-8">
                        <button wire:click="setRatingFilter(0)" class="px-6 py-2 rounded-full text-sm font-semibold transition-all {{ $ratingFilter == 0 ? 'bg-terra-500 text-white shadow-lg shadow-terra-500/20' : 'bg-white border border-gray-200 text-slate-600 hover:border-terra-300' }}">
                            Semua
                        </button>
                        @foreach($ratingStats['stats'] as $star => $stat)
                        <button wire:click="setRatingFilter({{ $star }})" class="px-6 py-2 rounded-full text-sm font-semibold transition-all flex items-center gap-2 {{ $ratingFilter == $star ? 'bg-terra-500 text-white shadow-lg shadow-terra-500/20' : 'bg-white border border-gray-200 text-slate-600 hover:border-terra-300' }}">
                            {{ $star }} Bintang ({{ $stat['count'] }})
                        </button>
                        @endforeach
                    </div>

                    <!-- Progress Bars -->
                    <div class="space-y-3 max-w-md">
                        @foreach($ratingStats['stats'] as $star => $stat)
                        <div class="flex items-center gap-4">
                            <span class="text-sm font-medium text-slate-600 w-16">{{ $star }} Bintang</span>
                            <div class="flex-grow h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-400 rounded-full" style="width: {{ $stat['percent'] }}%"></div>
                            </div>
                            <span class="text-xs text-slate-400 w-10">{{ round($stat['percent']) }}%</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Review List -->
            <div class="space-y-8 mb-16">
                @forelse($reviews as $review)
                <div class="flex gap-6 pb-8 border-b border-gray-50 last:border-0" wire:key="review-{{ $review->id }}">
                    <div class="shrink-0">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-bold text-lg">
                            {{ substr($review->reviewer_name, 0, 1) }}
                        </div>
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center justify-between mb-1">
                            <h4 class="font-display font-bold text-slate-900">{{ $review->masked_name }}</h4>
                            <span class="text-xs text-slate-400">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        @if($review->reviewer_location)
                        <div class="text-xs text-terra-600 font-medium mb-3 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $review->reviewer_location }}
                        </div>
                        @endif
                        <div class="flex gap-0.5 mb-3">
                            @for($i=1; $i<=5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            @endfor
                        </div>
                        <p class="text-slate-600 leading-relaxed whitespace-pre-line mb-4">{{ $review->content }}</p>
                        
                        @if($review->images && count($review->images) > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach($review->images as $image)
                                @php
                                    $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                                    $isVideo = in_array($ext, ['mp4', 'mov', 'avi']);
                                @endphp
                                @if($isVideo)
                                    <button x-data @click="$dispatch('open-lightbox', { url: '{{ asset('storage/' . $image) }}', type: 'video' })" class="relative w-20 h-20 rounded-lg overflow-hidden border border-gray-100 hover:border-terra-300 transition-all bg-black flex items-center justify-center">
                                        <video src="{{ asset('storage/' . $image) }}" class="w-full h-full object-cover opacity-60" muted playsinline></video>
                                        <div class="absolute inset-0 flex items-center justify-center text-white">
                                            <svg class="w-6 h-6 text-white drop-shadow-md" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z" />
                                            </svg>
                                        </div>
                                    </button>
                                @else
                                    <button x-data @click="$dispatch('open-lightbox', { url: '{{ asset('storage/' . $image) }}', type: 'image' })" class="relative w-20 h-20 rounded-lg overflow-hidden border border-gray-100 hover:border-terra-300 transition-all">
                                        <img src="{{ asset('storage/' . $image) }}" class="w-full h-full object-cover">
                                    </button>
                                @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="py-12 text-center text-slate-400 italic">Belum ada ulasan untuk rating ini.</div>
                @endforelse

                @if($reviews->hasMorePages())
                <div class="pt-8 text-center">
                    <button wire:click="loadMoreReviews" class="px-8 py-3 bg-white border border-gray-200 text-slate-900 font-bold rounded-lg hover:bg-gray-50 transition-colors inline-flex items-center gap-2">
                        <span>Tampilkan Lebih Banyak</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                </div>
                @endif
            </div>

            <!-- Review Form -->
            <div class="bg-slate-50 rounded-3xl p-8 lg:p-12 border border-gray-100">
                <div class="max-w-3xl">
                    <h3 class="font-display text-fluid-h2 font-black text-slate-900 mb-2">Tulis Ulasan Anda</h3>
                    <p class="text-slate-500 mb-8">Bantu pembeli lain dengan berbagi pengalaman Anda menggunakan produk Indoroster.</p>

                    @if (session()->has('review_success'))
                        <div class="mb-8 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
                            <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ session('review_success') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="submitReview" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="font-display block text-sm font-bold text-slate-900 mb-2">Nama Lengkap</label>
                                <input type="text" wire:model="reviewer_name" class="w-full h-12 bg-white border-gray-200 rounded-xl focus:border-terra-500 focus:ring-terra-500 transition-all px-4" placeholder="Contoh: Hendra Saputra">
                                @error('reviewer_name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="font-display block text-sm font-bold text-slate-900 mb-2">Lokasi (Kota)</label>
                                <input type="text" wire:model="reviewer_location" class="w-full h-12 bg-white border-gray-200 rounded-xl focus:border-terra-500 focus:ring-terra-500 transition-all px-4" placeholder="Contoh: Bekasi">
                            </div>
                        </div>

                        <div>
                            <label class="font-display block text-sm font-bold text-slate-900 mb-3">Rating</label>
                            <div class="flex gap-2">
                                @for($i=1; $i<=5; $i++)
                                <button type="button" wire:click="$set('rating', {{ $i }})" class="focus:outline-none transition-transform active:scale-95">
                                    <svg class="w-10 h-10 {{ $i <= $rating ? 'text-amber-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                </button>
                                @endfor
                            </div>
                        </div>

                        <div>
                            <label class="font-display block text-sm font-bold text-slate-900 mb-2">Ulasan Anda</label>
                            <textarea wire:model="content" rows="4" class="w-full bg-white border-gray-200 rounded-2xl focus:border-terra-500 focus:ring-terra-500 transition-all px-4 py-3" placeholder="Tuliskan pengalaman Anda menggunakan produk ini..."></textarea>
                            @error('content') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="font-display block text-sm font-bold text-slate-900 mb-2">Tambahkan Foto / Video Ulasan (Opsional)</label>
                            <div class="flex flex-wrap gap-4 items-start">
                                <label class="w-24 h-24 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-2xl cursor-pointer hover:border-terra-500 hover:bg-terra-50 transition-all text-slate-400">
                                    <svg class="w-8 h-8 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                    <span class="text-[10px] font-bold uppercase">Upload</span>
                                    <input type="file" wire:model="review_images" multiple class="hidden" accept="image/*,video/*">
                                </label>

                                @if($review_images)
                                    @foreach($review_images as $index => $image)
                                    <div class="relative w-24 h-24 rounded-2xl overflow-hidden border border-gray-200 bg-slate-900">
                                        @php
                                            $isImage = false;
                                            try {
                                                $isImage = str_starts_with($image->getMimeType(), 'image/');
                                            } catch (\Exception $e) {}
                                        @endphp
                                        
                                        @if($isImage)
                                            <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center p-2 text-center text-white">
                                                <svg class="w-8 h-8 text-terra-500 mb-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z" />
                                                </svg>
                                                <span class="text-[8px] text-slate-300 truncate w-full" title="{{ $image->getClientOriginalName() }}">{{ $image->getClientOriginalName() }}</span>
                                            </div>
                                        @endif
                                        
                                        <button type="button" wire:click="$set('review_images.{{ $index }}', null)" class="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center shadow-md cursor-pointer hover:bg-red-600 transition-colors z-10">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            <div wire:loading wire:target="review_images" class="mt-2 text-xs text-terra-600 font-medium animate-pulse">Sedang mengunggah file...</div>
                            @error('review_images.*') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="px-10 h-14 bg-terra-500 hover:bg-terra-600 text-white font-bold rounded-xl shadow-lg shadow-terra-500/20 transition-all">
                            Kirim Ulasan
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- Lightbox Modal -->
    <div x-data="{ open: false, url: '', type: 'image' }" 
         x-on:open-lightbox.window="
             const data = Array.isArray($event.detail) ? $event.detail[0] : $event.detail;
             open = true; 
             url = data.url; 
             type = data.type || 'image';
         "
         x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/90"
         style="display: none;"
         @keydown.escape.window="open = false">
        
        <button @click="open = false" class="absolute top-6 right-6 text-white hover:text-gray-300 transition-colors cursor-pointer">
            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div @click.away="open = false" class="max-w-4xl w-full max-h-full flex items-center justify-center">
            <template x-if="type === 'image'">
                <img :src="url" class="rounded-lg shadow-2xl object-contain max-h-[85vh] max-w-full">
            </template>
            <template x-if="type === 'video'">
                <video :src="url" class="rounded-lg shadow-2xl object-contain max-h-[85vh] max-w-full" controls autoplay></video>
            </template>
        </div>
    </div>

    <!-- Warning Modal -->
    <div x-data="{ open: false, title: '', message: '' }"
         x-on:open-warning-modal.window="
             const data = Array.isArray($event.detail) ? $event.detail[0] : $event.detail;
             title = data.title || '';
             message = data.message || '';
             open = true;
         "
         x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/50"
         style="display: none;">
         
        <div @click.away="open = false" class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-100 relative">
            <button @click="open = false" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors cursor-pointer">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            
            <div class="flex items-center gap-3.5 mb-4">
                <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-500 shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="font-display text-lg font-bold text-slate-900" x-text="title">Pilih Varian</h3>
            </div>
            
            <p class="text-sm text-slate-500 mb-6 leading-relaxed" x-text="message">Silakan pilih opsi varian produk terlebih dahulu sebelum menambahkan ke keranjang atau membeli.</p>
            
            <div class="flex justify-end gap-3">
                <button @click="open = false" class="px-5 py-2.5 bg-terra-500 hover:bg-terra-600 text-white text-sm font-bold rounded-lg transition-colors shadow-md shadow-terra-500/10 cursor-pointer">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    <style>
    /* Hide scrollbar for Chrome, Safari and Opera */
    .hide-scrollbar::-webkit-scrollbar {
      display: none;
    }
    /* Hide scrollbar for IE, Edge and Firefox */
    .hide-scrollbar {
      -ms-overflow-style: none;  /* IE and Edge */
      scrollbar-width: none;  /* Firefox */
    }
    </style>

    <script>
    // Force autoplay on videos after Livewire updates DOM
    function forceAutoplayVideos() {
        document.querySelectorAll('video[autoplay]').forEach(function(video) {
            if (video.paused) {
                video.muted = true;
                video.play().catch(function() {});
            }
        });
    }
    
    // Open order WA helper with validation
    function openOrderWa(qty) {
        let hasVariants = {{ $product->variants->count() > 0 ? 'true' : 'false' }};
        let selectedVariant = '{{ $selectedVariant }}';
        if (hasVariants && !selectedVariant) {
            window.dispatchEvent(new CustomEvent('open-warning-modal', {
                detail: {
                    title: 'Pilih Varian',
                    message: 'Silakan pilih opsi varian produk terlebih dahulu sebelum melakukan pemesanan.'
                }
            }));
            return;
        }

        window.dispatchEvent(new CustomEvent('open-wa-modal', {
            detail: {
                qty: qty,
                price: {{ (float)$this->activePrice }},
                productName: {!! json_encode($product->name) !!},
                variantName: {!! json_encode($selectedVariant && $product->variants->find($selectedVariant) ? $product->variants->find($selectedVariant)->name : '') !!},
                imageUrl: {!! json_encode($product->primary_image) !!}
            }
        }));
    }
    
    // Run on initial load
    document.addEventListener('DOMContentLoaded', forceAutoplayVideos);
    
    // Run after every Livewire DOM update
    if (typeof Livewire !== 'undefined') {
        document.addEventListener('livewire:navigated', forceAutoplayVideos);
        Livewire.hook('morph.updated', () => {
            setTimeout(forceAutoplayVideos, 100);
        });
    }
    </script>

    <!-- JSON-LD Product Schema for SEO -->
    @php
        $schemaData = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => Str::limit(strip_tags($product->description ?? ''), 200),
            'image' => $product->primary_image ?? '',
            'sku' => $product->slug,
            'brand' => ['@type' => 'Brand', 'name' => 'Indoroster'],
            'category' => $product->category->name ?? 'Roster Beton',
            'offers' => [
                '@type' => 'Offer',
                'url' => url()->current(),
                'priceCurrency' => 'IDR',
                'price' => $product->display_price ?? 0,
                'availability' => 'https://schema.org/' . ($product->stock > 0 ? 'InStock' : 'OutOfStock'),
                'seller' => ['@type' => 'Organization', 'name' => 'Indoroster'],
            ],
        ];
        if (isset($ratingStats) && $ratingStats['total'] > 0) {
            $schemaData['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $product->average_rating ?? 5,
                'reviewCount' => $ratingStats['total'],
            ];
        }
    @endphp
    <script type="application/ld+json">{!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}</script>
 
    <!-- WhatsApp Order Modal component -->
    <div x-data="{
        open: false,
        name: '',
        address: '',
        qty: 1,
        productName: '',
        variantName: '',
        imageUrl: '',
        productPrice: 0,
        get activePriceFormatted() {
            return 'Rp' + new Intl.NumberFormat('id-ID').format(this.productPrice);
        },
        get totalPriceFormatted() {
            return 'Rp' + new Intl.NumberFormat('id-ID').format(this.qty * this.productPrice);
        },
        sendWaOrder() {
            if (!this.name.trim()) {
                alert('Silakan isi Nama Lengkap Anda.');
                return;
            }
            if (!this.address.trim()) {
                alert('Silakan isi Alamat Lengkap pengiriman.');
                return;
            }
            let minOrder = parseInt($wire.get('product.min_order')) || 1;
            if (this.qty < minOrder) {
                alert('Minimal pembelian untuk produk ini adalah ' + minOrder + ' pcs.');
                return;
            }
 
            let variantText = this.variantName ? '*Varian:* ' + this.variantName + '\n' : '';
            let message = 'Halo Indoroster, saya ingin memesan:\n\n' +
                '*Produk:* ' + this.productName + '\n' +
                variantText +
                '*Jumlah:* ' + this.qty + ' pcs\n' +
                '*Harga Satuan:* ' + this.activePriceFormatted + '\n' +
                '*Total Harga:* ' + this.totalPriceFormatted + '\n\n' +
                '*Data Pengiriman:*\n' +
                '*Nama:* ' + this.name + '\n' +
                '*Alamat:* ' + this.address + '\n\n' +
                'Terima kasih.';
 
            let encodedMessage = encodeURIComponent(message);
            let waUrl = 'https://wa.me/6281389709847?text=' + encodedMessage;
            window.open(waUrl, '_blank');
            this.open = false;
        }
    }"
    x-on:open-wa-modal.window="
        qty = $event.detail.qty || 1;
        productPrice = parseFloat($event.detail.price) || 0;
        productName = $event.detail.productName || '';
        variantName = $event.detail.variantName || '';
        imageUrl = $event.detail.imageUrl || '';
        open = true;
    "
    x-show="open"
    class="fixed inset-0 z-[120] overflow-y-auto"
    style="display: none;"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true">
        <!-- Backdrop -->
        <div x-show="open" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
             @click="open = false"></div>
 
        <!-- Modal Wrapper -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden transform transition-all border border-slate-100">
                
                <!-- Header -->
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900 font-display flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        Pemesanan via WhatsApp
                    </h3>
                    <button @click="open = false" type="button" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-lg hover:bg-slate-100 transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
 
                <!-- Body -->
                <div class="p-6 space-y-4 max-h-[calc(100vh-12rem)] overflow-y-auto">
                    <!-- Product Details Box -->
                    <div class="flex items-center gap-4 p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <img :src="imageUrl" class="w-16 h-16 rounded-lg object-cover border border-slate-200" alt="Product Image">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-display font-bold text-slate-900 text-sm truncate" x-text="productName"></h4>
                            <template x-if="variantName">
                                <p class="text-xs text-slate-500 mt-0.5">Varian: <span class="font-semibold text-slate-700" x-text="variantName"></span></p>
                            </template>
                            <p class="text-xs text-slate-500 mt-0.5">Harga: <span class="font-bold text-terra-600" x-text="activePriceFormatted"></span></p>
                        </div>
                    </div>
 
                    <!-- Warning info about online payment -->
                    <div class="p-3.5 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <div>
                            <span class="font-bold">Sistem Pembayaran Pemeliharaan (Maintenance):</span>
                            <p class="mt-0.5 leading-relaxed text-slate-600">Saat ini sistem pembayaran otomatis kami sedang dalam proses pemeliharaan berkala untuk meningkatkan keamanan dan kenyamanan transaksi Anda.</p>
                            <p class="mt-1 leading-relaxed text-slate-600">Namun jangan khawatir, Anda tetap bisa melakukan pemesanan secara mudah & aman melalui WhatsApp resmi Admin kami. Silakan lengkapi data pemesanan di bawah ini:</p>
                        </div>
                    </div>
 
                    <!-- Form Inputs -->
                    <div class="space-y-3.5">
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap Penerima</label>
                            <input type="text" x-model="name" placeholder="Masukkan nama lengkap Anda" class="w-full h-10 px-3 bg-white border border-gray-200 rounded-lg text-sm focus:border-terra-500 focus:ring-terra-500">
                        </div>
 
                        <!-- Jumlah Pesanan -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jumlah Pesanan (Pcs)</label>
                            <div class="flex items-center gap-3">
                                <div class="flex items-center border border-gray-300 rounded-lg bg-white h-9 w-28 shrink-0">
                                    <button @click="if(qty > (parseInt($wire.get('product.min_order')) || 1)) qty--" type="button" class="w-8 h-full text-gray-500 hover:text-terra-500 hover:bg-gray-50 rounded-l-lg transition-colors font-bold text-sm">-</button>
                                    <input type="number" x-model.number="qty" class="w-12 h-full text-center border-0 focus:ring-0 text-slate-900 font-bold p-0 text-xs" :min="parseInt($wire.get('product.min_order')) || 1">
                                    <button @click="qty++" type="button" class="w-8 h-full text-gray-500 hover:text-terra-500 hover:bg-gray-50 rounded-r-lg transition-colors font-bold text-sm">+</button>
                                </div>
                                <span class="text-xs text-slate-500 italic mt-1">(Minimal pembelian: <span x-text="parseInt($wire.get('product.min_order')) || 1"></span> pcs)</span>
                            </div>
                        </div>
 
                        <!-- Alamat Lengkap -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Pengiriman Lengkap</label>
                            <textarea x-model="address" rows="3" placeholder="Masukkan alamat lengkap pengiriman (Jalan, No Rumah, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Provinsi)" class="w-full p-3 bg-white border border-gray-200 rounded-lg text-sm focus:border-terra-500 focus:ring-terra-500 leading-relaxed"></textarea>
                        </div>
                    </div>
 
                    <!-- Price calculation summary -->
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between text-sm">
                        <span class="font-medium text-slate-600">Total Pembelian:</span>
                        <span class="font-display font-black text-terra-600 text-lg" x-text="totalPriceFormatted"></span>
                    </div>
                </div>
 
                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button @click="open = false" type="button" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-semibold hover:bg-slate-50 transition shadow-sm cursor-pointer">
                        Batal
                    </button>
                    <button @click="sendWaOrder()" type="button" class="px-5 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm font-bold shadow-md shadow-green-500/20 transition flex items-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        Pesan Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
