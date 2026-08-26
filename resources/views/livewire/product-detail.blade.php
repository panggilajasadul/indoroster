<div class="bg-white dark:bg-slate-950 min-h-screen py-4 sm:py-8 lg:py-12">
@push('seo')
    <x-product-schema :product="$product" />    
@endpush
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb Navigation -->
        @php
            $productBreadcrumbs = [
                ['label' => 'Katalog', 'url' => route('catalog')],
            ];
            if ($product->category) {
                $productBreadcrumbs[] = ['label' => $product->category->name, 'url' => route('catalog', ['category' => $product->category->slug])];
            }
            $productBreadcrumbs[] = ['label' => $product->name];
        @endphp
        <x-breadcrumb :items="$productBreadcrumbs" class="!px-0 !py-0 mb-4 sm:mb-8" />

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="mb-8 p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 rounded-2xl flex items-center justify-between shadow-soft-xs">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <a href="{{ route('cart') }}" class="text-xs sm:text-sm font-bold text-emerald-700 dark:text-emerald-300 hover:underline ml-4 whitespace-nowrap">Lihat Keranjang &rarr;</a>
            </div>
        @endif
        
        @if (session()->has('error'))
            <div class="mb-8 p-4 bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 rounded-2xl flex items-center gap-3 shadow-soft-xs">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-14">
            
            <!-- Left Column: Gallery & Media -->
            <div class="flex flex-col w-full">
                <div class="flex flex-col gap-3.5">
                    <!-- Main Image / Video Viewport -->
                    <div class="w-full relative bg-slate-900 rounded-3xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-soft-md aspect-square">
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
                                        <div class="absolute inset-0 w-full h-full flex items-center justify-center bg-slate-900 text-slate-400">Invalid Video</div>
                                    @endif
                                @else
                                    <video src="{{ $activeImage }}" class="absolute inset-0 w-full h-full object-cover" controls autoplay muted loop playsinline></video>
                                @endif
                            @else
                                <img src="{{ $activeImage }}" alt="{{ $product->name }}" class="absolute inset-0 w-full h-full object-cover">
                            @endif
                        @else
                            <div class="absolute inset-0 w-full h-full flex items-center justify-center text-slate-400">No Image Available</div>
                        @endif

                        <!-- Quality & Direct Factory Badges -->
                        <div class="absolute top-4 left-4 z-10 flex flex-col gap-1.5">
                            <span class="bg-slate-950/80 backdrop-blur-md text-white text-[10px] font-bold px-2.5 py-1 rounded-xl shadow-md border border-white/10">
                                🏭 Pabrik Purwakarta
                            </span>
                        </div>
                    </div>

                    <!-- Thumbnails Carousel -->
                    @if($product->media->count() > 0)
                    <div class="flex flex-row gap-2.5 overflow-x-auto w-full pb-2 no-scrollbar shrink-0">
                        @foreach($product->media as $media)
                            @php
                                $mediaUrl = str_starts_with($media->media_url, 'http') ? $media->media_url : asset('storage/' . $media->media_url);
                            @endphp
                            <button 
                                wire:click="setActiveImage('{{ $mediaUrl }}', '{{ $media->media_type }}')"
                                wire:mouseenter.debounce.100ms="setActiveImage('{{ $mediaUrl }}', '{{ $media->media_type }}')"
                                class="relative flex-shrink-0 w-18 h-18 sm:w-20 sm:h-20 rounded-2xl overflow-hidden border-2 transition-all {{ $activeImage === $mediaUrl ? 'border-terra-500 shadow-soft-sm scale-105' : 'border-slate-200/80 dark:border-slate-800 hover:border-slate-400 opacity-75 hover:opacity-100' }}">
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
                                                <div class="w-full h-full bg-slate-900"></div>
                                            @endif
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/40 text-white">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                            </div>
                                        </div>
                                    @else
                                        <div class="w-full h-full relative">
                                            <video src="{{ $mediaUrl }}" class="w-full h-full object-cover" muted playsinline></video>
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/40 text-white">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
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

            <!-- Right Column: Product Info & Actions -->
            <div class="flex flex-col justify-between">
                <div>
                    <!-- Product Title -->
                    <h1 class="font-display text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight mb-2">{{ $product->name }}</h1>
                    
                    <!-- Metadata Row (Category, Rating, Ulasan, Terjual) -->
                    <div class="flex flex-wrap items-center gap-x-2.5 gap-y-1.5 text-xs sm:text-sm text-slate-500 dark:text-slate-400 mb-4 pb-2 border-b border-slate-100 dark:border-slate-800">
                        <div class="flex items-center gap-1 text-slate-600 dark:text-slate-300">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span class="font-medium">{{ $product->category->name ?? 'Roster Beton' }}</span>
                        </div>
                        <span class="text-slate-300 dark:text-slate-700">•</span>
                        <span class="inline-flex items-center gap-1 text-amber-500 font-bold">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            {{ $product->average_rating }}
                            <a href="#reviews-section" class="font-normal text-slate-500 dark:text-slate-400 hover:text-terra-600 dark:hover:text-terra-400 transition-colors">({{ $ratingStats['total'] }} ulasan)</a>
                        </span>
                        <span class="text-slate-300 dark:text-slate-700">•</span>
                        <span class="text-slate-800 dark:text-slate-200 font-bold">{{ $product->formatted_total_sold }} Terjual</span>
                    </div>

                    <!-- Pricing Display -->
                    <div class="mb-5 flex items-baseline gap-3 flex-wrap">
                        @if(!$selectedVariant && $product->variants->count() > 0)
                            <span class="text-2xl sm:text-3xl font-black text-terra-600 dark:text-terra-400 font-display">{{ $this->priceRange }}</span>
                        @else
                            <span class="text-2xl sm:text-3xl font-black text-terra-600 dark:text-terra-400 font-display">Rp{{ number_format($this->activePrice, 0, ',', '.') }}</span>
                        @endif

                        @if($product->original_price > 0 && (!$selectedVariant || $product->original_price > $this->activePrice))
                            <span class="text-sm sm:text-base text-slate-400 dark:text-slate-500 line-through">Rp{{ number_format($product->original_price, 0, ',', '.') }}</span>
                            @php
                                $discountPercentage = round((($product->original_price - $this->activePrice) / $product->original_price) * 100);
                            @endphp
                            @if($discountPercentage > 0)
                                <span class="text-xs font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/40 border border-red-100 dark:border-red-900/40 px-1.5 py-0.5 rounded">-{{ $discountPercentage }}%</span>
                            @endif
                        @endif
                    </div>

                    <!-- Variants Selection -->
                    @if($product->variants->count() > 0)
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">VARIAN PRODUK</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($product->variants as $variant)
                                @if($variant->is_active)
                                    <label class="relative cursor-pointer">
                                        <input type="radio" wire:model.live="selectedVariant" value="{{ $variant->id }}" class="peer sr-only" name="variant">
                                        <div class="px-4 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-700 dark:text-slate-300 peer-checked:border-terra-500 dark:peer-checked:border-terra-500 peer-checked:text-terra-600 dark:peer-checked:text-terra-400 peer-checked:bg-terra-50/40 dark:peer-checked:bg-terra-500/10 hover:border-slate-300 dark:hover:border-slate-600 transition-all">
                                            {{ $variant->name }}
                                        </div>
                                    </label>
                                @else
                                    <div class="relative cursor-not-allowed select-none" title="Varian sedang tidak tersedia">
                                        <div class="px-3.5 py-1.5 bg-slate-100 dark:bg-slate-800 border border-dashed border-slate-300 dark:border-slate-700 rounded-lg text-xs font-medium text-slate-400 dark:text-slate-500 flex items-center gap-1.5 opacity-60">
                                            <span class="line-through">{{ $variant->name }}</span>
                                            <span class="text-[10px] text-red-400 font-normal">(Nonaktif)</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Trust & Service Details Accordions (Pengiriman & Jaminan) -->
                    <div class="border-t border-slate-100 dark:border-slate-800 pt-3 mb-5 space-y-2">
                        <!-- Pengiriman Accordion -->
                        <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4 py-2">
                            <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider w-24 pt-1 shrink-0">PENGIRIMAN</span>
                            <div x-data="{ open: false }" class="flex-1">
                                <button @click="open = !open" class="flex items-center justify-between w-full text-left py-1 text-xs font-semibold text-slate-800 dark:text-slate-200 hover:text-terra-600 dark:hover:text-terra-400 transition-colors cursor-pointer">
                                    <span class="flex items-center gap-2">
                                        <span class="text-terra-500 text-sm">🚚</span>
                                        <span>Pengiriman Cepat Armada Pabrik & Kargo Nasional</span>
                                    </span>
                                    <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                <div x-show="open" x-collapse class="pt-2.5 pl-6 text-xs text-slate-600 dark:text-slate-300 leading-relaxed space-y-2">
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400">Diproduksi langsung di Plered, Purwakarta. Armada truk kami siap mengirimkan pesanan partai kecil maupun ribuan pieces langsung ke gerbang proyek Anda dengan garansi aman:</p>
                                    <div class="space-y-1.5 pt-1">
                                        <div class="flex items-start gap-1.5 text-[11px]">
                                            <span class="text-terra-500 font-bold shrink-0">📍</span>
                                            <span><strong>Wilayah Jabodetabek:</strong> Seluruh DKI Jakarta (Jaksel, Jakbar, Jaktim, Jakut, Jakpus), Bogor, Depok, Tangerang, Tangsel, & Bekasi.</span>
                                        </div>
                                        <div class="flex items-start gap-1.5 text-[11px]">
                                            <span class="text-terra-500 font-bold shrink-0">📍</span>
                                            <span><strong>Wilayah Jawa Barat:</strong> Plered, Purwakarta, Karawang, Cikampek, Subang, Bandung Raya, Cimahi, Sumedang, Cirebon, Indramayu, Sukabumi, & Cianjur.</span>
                                        </div>
                                        <div class="flex items-start gap-1.5 text-[11px]">
                                            <span class="text-terra-500 font-bold shrink-0">📍</span>
                                            <span><strong>Pengiriman Nasional:</strong> Ekspedisi kargo aman menjangkau Jawa Tengah, Jawa Timur, Bali, Sumatera, Kalimantan, & Sulawesi.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Jaminan Accordion -->
                        <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-4 py-2 border-t border-slate-100 dark:border-slate-800">
                            <span class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider w-24 pt-1 shrink-0">JAMINAN</span>
                            <div x-data="{ open: true }" class="flex-1">
                                <button @click="open = !open" class="flex items-center justify-between w-full text-left py-1 text-xs font-semibold text-slate-800 dark:text-slate-200 hover:text-terra-600 dark:hover:text-terra-400 transition-colors cursor-pointer">
                                    <span class="flex items-center gap-2">
                                        <span class="text-terra-500 text-sm">🛡️</span>
                                        <span>100% Original & Garansi Pecah</span>
                                    </span>
                                    <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': !open }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
                                </button>
                                <div x-show="open" x-collapse class="pt-2 pl-6 space-y-2.5">
                                    <div class="flex items-start gap-2">
                                        <span class="text-blue-600 dark:text-blue-400 font-bold text-xs mt-0.5">✓</span>
                                        <div>
                                            <div class="text-xs font-bold text-slate-800 dark:text-white">Produk 100% Asli</div>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Semua roster beton dijamin original langsung diproduksi dari pabrik Indoroster.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-2">
                                        <span class="text-blue-600 dark:text-blue-400 font-bold text-xs mt-0.5">✓</span>
                                        <div>
                                            <div class="text-xs font-bold text-slate-800 dark:text-white">Garansi Barang Pecah Diganti</div>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400">Jika ada barang pecah selama pengiriman oleh armada kami, akan diganti baru 100% tanpa biaya tambahan.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Roster Wall Calculator -->
                    <div class="p-3.5 sm:p-4 bg-orange-50/40 dark:bg-orange-950/20 rounded-2xl border border-orange-200/60 dark:border-orange-900/40 mb-5">
                        <div class="flex items-center justify-between mb-2.5">
                            <div class="flex items-center gap-2">
                                <span class="text-sm">🧮</span>
                                <h3 class="font-bold text-xs text-slate-900 dark:text-white">Kalkulator Kebutuhan</h3>
                            </div>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500">ukuran: {{ $product->parsed_dimensions['width'] ?? '20' }}x{{ $product->parsed_dimensions['height'] ?? '20' }} cm</span>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-2.5">
                            <div class="w-20 sm:w-24">
                                <span class="block text-[9px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-0.5">PANJANG (M)</span>
                                <input type="number" step="0.1" wire:model.live="wall_width" class="w-full h-8 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-center font-bold px-2 text-slate-900 dark:text-white focus:ring-1 focus:ring-terra-500">
                            </div>
                            <div class="w-20 sm:w-24">
                                <span class="block text-[9px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-0.5">TINGGI (M)</span>
                                <input type="number" step="0.1" wire:model.live="wall_height" class="w-full h-8 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-xs text-center font-bold px-2 text-slate-900 dark:text-white focus:ring-1 focus:ring-terra-500">
                            </div>
                            <div class="flex items-center gap-1.5 text-xs">
                                <span class="text-[10px] text-slate-400 dark:text-slate-500 font-bold uppercase">ESTIMASI:</span>
                                <span class="text-sm font-black text-terra-600 dark:text-terra-400">{{ $calculatedRequirement }} pcs</span>
                            </div>
                            <label class="flex items-center gap-1 text-[11px] text-slate-600 dark:text-slate-300 cursor-pointer select-none ml-auto">
                                <input type="checkbox" wire:model.live="include_waste" class="rounded text-terra-500 focus:ring-terra-500">
                                <span>+5% Cadangan</span>
                            </label>
                            <button wire:click="applyCalculatedQuantity" class="h-8 px-3 bg-white dark:bg-slate-800 border border-terra-500 text-terra-600 dark:text-terra-400 hover:bg-terra-500 hover:text-white dark:hover:bg-terra-500 dark:hover:text-white text-xs font-bold rounded-lg transition-all flex items-center justify-center gap-1 cursor-pointer shadow-2xs">
                                <span>Gunakan</span>
                                <span>✓</span>
                            </button>
                        </div>
                    </div>

                    <!-- Quantity & Purchase Action -->
                    <div class="p-3.5 bg-slate-50 dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-slate-700 dark:text-slate-300 whitespace-nowrap">Jumlah Beli</span>
                            <div class="flex items-center border border-slate-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 h-9 shadow-2xs">
                                <button wire:click="decrementQuantity" class="w-8 h-full text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-l-lg transition-colors font-bold text-sm focus:outline-none cursor-pointer">-</button>
                                <input type="number" wire:model.live.debounce.300ms="quantity" class="w-12 h-full text-center border-0 focus:ring-0 text-slate-900 dark:text-white font-bold p-0 text-xs bg-transparent" min="1" max="{{ $this->activeStock }}">
                                <button wire:click="incrementQuantity" class="w-8 h-full text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-r-lg transition-colors font-bold text-sm focus:outline-none cursor-pointer">+</button>
                            </div>
                            <div class="text-[11px] text-slate-500 dark:text-slate-400">
                                <span>Tersedia {{ $this->activeStock }}</span>
                                @if($product->min_order > 1)
                                    <span class="text-terra-600 dark:text-terra-400 block text-[10px]">Min. {{ $product->min_order }} pcs</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($this->orderMode === 'whatsapp')
                                <button wire:click="orderWhatsApp" wire:loading.attr="disabled" wire:target="orderWhatsApp" class="w-full px-6 h-10 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center gap-2 cursor-pointer disabled:opacity-75">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                    <span wire:loading.remove wire:target="orderWhatsApp">Pesan via WhatsApp</span>
                                    <span wire:loading wire:target="orderWhatsApp">Membuka WhatsApp...</span>
                                </button>
                            @else
                                <button wire:click="addToCart" wire:loading.attr="disabled" wire:target="addToCart" class="px-4 h-10 bg-white dark:bg-slate-800 border border-terra-500 text-terra-600 dark:text-terra-400 hover:bg-terra-50 dark:hover:bg-slate-700 text-xs font-bold rounded-xl transition-all flex items-center justify-center gap-1.5 shadow-2xs cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                    <span>Keranjang</span>
                                </button>
                                <button wire:click="buyNow" wire:loading.attr="disabled" wire:target="buyNow" class="px-5 h-10 bg-terra-500 hover:bg-terra-600 text-white text-xs font-bold rounded-xl shadow-md shadow-terra-500/20 transition-all flex items-center justify-center gap-1 cursor-pointer">
                                    <span>Beli Sekarang</span>
                                </button>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Tab Details (Spesifikasi Teknis & Panduan) -->
        <div x-data="{ activeTab: 'spec' }" class="mt-16 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 shadow-soft-xs overflow-hidden">
            <!-- Tab Headers -->
            <div class="flex border-b border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900">
                <button @click="activeTab = 'spec'" :class="{ 'text-terra-600 dark:text-terra-400 font-bold border-b-2 border-terra-500 bg-white dark:bg-slate-900': activeTab === 'spec', 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200': activeTab !== 'spec' }" class="flex items-center gap-2 px-8 py-4.5 text-sm font-semibold transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Spesifikasi Teknis</span>
                </button>
                <button @click="activeTab = 'how-to'" :class="{ 'text-terra-600 dark:text-terra-400 font-bold border-b-2 border-terra-500 bg-white dark:bg-slate-900': activeTab === 'how-to', 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-200': activeTab !== 'how-to' }" class="flex items-center gap-2 px-8 py-4.5 text-sm font-semibold transition-all cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Panduan & Cara Pesan</span>
                </button>
            </div>

            <!-- Tab Body -->
            <div class="p-6 sm:p-10">
                <!-- Spesifikasi Teknis Content -->
                <div x-show="activeTab === 'spec'" x-cloak class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                    <!-- Left Column: Detail Produk -->
                    <div class="lg:col-span-5 space-y-4">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-1 h-4 bg-terra-500 rounded-full"></span>
                            <h3 class="font-display text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">DETAIL PRODUK</h3>
                        </div>
                        
                        <div class="space-y-3 text-xs sm:text-sm">
                            <div class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-800">
                                <span class="text-slate-500 dark:text-slate-400">Dimensi Satuan</span>
                                <span class="font-bold text-slate-900 dark:text-white">{{ $product->dimensions ?: '20×20×10' }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-800">
                                <span class="text-slate-500 dark:text-slate-400">Berat per Pcs</span>
                                <span class="font-bold text-slate-900 dark:text-white">{{ $product->weight ? number_format((float)$product->weight, 2) : '3.00' }} kg</span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-slate-100 dark:border-slate-800">
                                <span class="text-slate-500 dark:text-slate-400">Kode Produk (SKU)</span>
                                <span class="px-2.5 py-1 rounded bg-slate-100 dark:bg-slate-800 font-mono text-xs font-bold text-slate-700 dark:text-slate-300 uppercase">{{ $product->sku ?: 'IR-XPHTAK' }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <span class="text-slate-500 dark:text-slate-400">Kategori Produk</span>
                                <span class="font-bold text-terra-600 dark:text-terra-400">{{ $product->category->name ?? 'Roster Beton' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Catatan Penting -->
                    <div class="lg:col-span-7 space-y-4">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-1 h-4 bg-slate-400 rounded-full"></span>
                            <h3 class="font-display text-xs font-black text-slate-900 dark:text-white uppercase tracking-widest">CATATAN PENTING</h3>
                        </div>
                        
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                            Produk roster beton kami diproduksi menggunakan material premium dengan teknik casting tekanan tinggi untuk menghasilkan kepadatan maksimal. Finishing halus dan presisi memudahkan proses pemasangan.
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/80 border border-slate-100 dark:border-slate-800 flex items-start gap-3">
                                <span class="w-8 h-8 rounded-xl bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-terra-500 flex items-center justify-center shrink-0 shadow-xs">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </span>
                                <div>
                                    <div class="text-xs font-bold text-slate-900 dark:text-white">Kualitas Terjamin</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 leading-snug mt-0.5">Tahan cuaca ekstrem dan tidak mudah berlumut.</div>
                                </div>
                            </div>

                            <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/80 border border-slate-100 dark:border-slate-800 flex items-start gap-3">
                                <span class="w-8 h-8 rounded-xl bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-terra-500 flex items-center justify-center shrink-0 shadow-xs">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </span>
                                <div>
                                    <div class="text-xs font-bold text-slate-900 dark:text-white">Produksi Sendiri</div>
                                    <div class="text-[11px] text-slate-500 dark:text-slate-400 leading-snug mt-0.5">Langsung dari pabrik kami untuk harga terbaik.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panduan & Cara Pesan Content -->
                <div x-show="activeTab === 'how-to'" x-cloak class="space-y-6">
                    <div class="prose prose-slate dark:prose-invert max-w-none text-xs sm:text-sm leading-relaxed">
                        {!! $product->description !!}
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/80 border border-slate-100 dark:border-slate-800">
                            <span class="font-bold text-xs text-slate-900 dark:text-white block mb-1">1. Pilih Motif & Varian</span>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Gunakan kalkulator dinding di atas untuk menghitung jumlah pcs yang tepat.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/80 border border-slate-100 dark:border-slate-800">
                            <span class="font-bold text-xs text-slate-900 dark:text-white block mb-1">2. Checkout / WhatsApp</span>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Pesan via website atau klik WhatsApp untuk konsultasi ongkos kirim armada pabrik.</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/80 border border-slate-100 dark:border-slate-800">
                            <span class="font-bold text-xs text-slate-900 dark:text-white block mb-1">3. Pengiriman & Garansi</span>
                            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">Barang dikirim aman sampai ke lokasi Anda dengan jaminan ganti baru 100% jika pecah.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rekomendasi Produk Pilihan Lainnya (Placed above Reviews) -->
        @if($recommendedProducts->count() > 0)
        <div class="mt-16 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-10 shadow-soft-xs">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <div class="flex items-center gap-2 text-terra-600 dark:text-terra-400 text-xs font-bold uppercase tracking-wider mb-1">
                        <span>✨ Rekomendasi Pilihan</span>
                    </div>
                    <h2 class="font-display text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight mb-1">Motif Roster Terkait & Serupa</h2>
                    <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Pilihan model roster beton dengan dimensi & spesifikasi sejenis untuk inspirasi dinding fasad Anda.</p>
                </div>
                <a href="{{ route('catalog') }}" class="hidden sm:inline-flex items-center gap-1 text-xs font-bold text-terra-600 dark:text-terra-400 hover:text-terra-700 transition-colors">
                    <span>Lihat Semua Katalog</span>
                    <span>→</span>
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-3 sm:gap-4 lg:gap-5">
                @foreach($recommendedProducts as $recProduct)
                    <x-product-card :product="$recProduct" wire:key="rec-product-{{ $recProduct->id }}" />
                @endforeach
            </div>
        </div>
        @endif

        <!-- Section: Ulasan & Rating Pelanggan -->
        <div id="reviews-section" class="mt-12 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/80 dark:border-slate-800 p-6 sm:p-10 shadow-soft-xs">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-8 border-b border-slate-100 dark:border-slate-800">
                <div>
                    <h2 class="font-display text-2xl font-black text-slate-900 dark:text-white tracking-tight mb-1">Ulasan & Penilaian Pelanggan</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Ulasan nyata dari pembeli dan kontraktor proyek yang telah menggunakan produk ini.</p>
                </div>
                
                <!-- Summary Rating Score & Animated Bars -->
                <div class="flex flex-col sm:flex-row items-center gap-6 bg-slate-50 dark:bg-slate-800/80 p-6 rounded-2xl border border-slate-100 dark:border-slate-700">
                    <div class="text-center sm:text-left flex items-center gap-4">
                        <div class="font-display text-5xl font-black text-slate-900 dark:text-white leading-none">{{ $product->average_rating }}</div>
                        <div>
                            <div class="flex text-amber-400 gap-0.5 mb-1">
                                @for($i=1; $i<=5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= round($product->average_rating) ? 'fill-current' : 'text-slate-200 dark:text-slate-700 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                @endfor
                            </div>
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-bold block">{{ $ratingStats['total'] }} Ulasan Terverifikasi</span>
                        </div>
                    </div>

                    <div class="hidden sm:block h-14 w-px bg-slate-200 dark:bg-slate-700"></div>

                    <!-- Progress Bars per Star -->
                    <div class="w-full sm:w-56 space-y-1.5">
                        @for($star = 5; $star >= 1; $star--)
                            @php
                                $percent = $ratingStats['stats'][$star]['percentage'] ?? $ratingStats['stats'][$star]['percent'] ?? 0;
                                $count = $ratingStats['stats'][$star]['count'] ?? 0;
                            @endphp
                            <div class="flex items-center gap-2 text-xs">
                                <span class="w-5 text-slate-500 dark:text-slate-400 font-bold text-[11px] text-right">{{ $star }}★</span>
                                <div class="flex-1 h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-amber-400 rounded-full transition-all duration-1000 ease-out" style="width: {{ $percent }}%"></div>
                                </div>
                                <span class="w-8 text-[10px] text-slate-400 dark:text-slate-500 text-right">{{ $percent }}%</span>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Filter Rating Stars Chips -->
            <div class="flex flex-wrap items-center gap-2 py-6 border-b border-slate-100 dark:border-slate-800">
                <span class="text-xs font-bold text-slate-600 dark:text-slate-300 mr-2">Filter Rating:</span>
                <button wire:click="setRatingFilter(0)" class="px-4 py-2 rounded-xl text-xs font-bold transition-all cursor-pointer {{ $ratingFilter === 0 ? 'bg-slate-900 dark:bg-terra-500 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                    Semua ({{ $ratingStats['total'] }})
                </button>
                @for($star = 5; $star >= 1; $star--)
                    @php $count = $ratingStats['stats'][$star]['count'] ?? 0; @endphp
                    <button wire:click="setRatingFilter({{ $star }})" class="px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 cursor-pointer {{ $ratingFilter === $star ? 'bg-terra-500 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                        <span>{{ $star }} Bintang</span>
                        <span class="opacity-75">({{ $count }})</span>
                    </button>
                @endfor
            </div>

            <!-- Review Items List -->
            <div class="py-6 space-y-6">
                @forelse($reviews as $rev)
                    <div class="p-5 sm:p-6 rounded-2xl bg-slate-50/60 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-3">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <span class="font-bold text-sm text-slate-900 dark:text-white">{{ $rev->reviewer_name }}</span>
                                    @if($rev->reviewer_location)
                                        <span class="text-[11px] bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 px-2 py-0.5 rounded-md font-medium">{{ $rev->reviewer_location }}</span>
                                    @endif
                                    <span class="text-[11px] text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-950/60 px-2 py-0.5 rounded-md font-bold flex items-center gap-1">
                                        <svg class="w-3 h-3 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                        Pembeli Terverifikasi
                                    </span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-slate-400 dark:text-slate-500">
                                    <div class="flex text-amber-400 gap-0.5">
                                        @for($i=1; $i<=5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $rev->rating ? 'fill-current' : 'text-slate-200 dark:text-slate-700 fill-current' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                        @endfor
                                    </div>
                                    <span>•</span>
                                    <span>{{ $rev->created_at ? $rev->created_at->diffForHumans() : 'Baru saja' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Review Text -->
                        <p class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed">{{ $rev->content }}</p>

                        <!-- Review Photos / Videos -->
                        @if(!empty($rev->images) && is_array($rev->images))
                            <div class="flex flex-wrap gap-2.5 pt-2">
                                @foreach($rev->images as $img)
                                    @php
                                        $imgUrl = str_starts_with($img, 'http') ? $img : asset('storage/' . $img);
                                        $isVid = str_contains(strtolower($img), '.mp4') || str_contains(strtolower($img), '.mov');
                                    @endphp
                                    <button 
                                        @click="$dispatch('open-lightbox', { url: '{{ $imgUrl }}', type: '{{ $isVid ? 'video' : 'image' }}' })"
                                        class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-slate-900 relative group cursor-pointer hover:border-terra-500 transition-all">
                                        @if($isVid)
                                            <video src="{{ $imgUrl }}" class="w-full h-full object-cover"></video>
                                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center text-white">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                            </div>
                                        @else
                                            <img src="{{ $imgUrl }}" alt="Foto Ulasan" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12 bg-slate-50 dark:bg-slate-800/50 rounded-2xl">
                        <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada ulasan untuk kategori rating ini.</p>
                    </div>
                @endforelse

                <!-- Load More Button -->
                @if($reviews->hasMorePages())
                    <div class="text-center pt-4">
                        <button wire:click="loadMoreReviews" wire:loading.attr="disabled" class="px-8 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 hover:border-terra-500 dark:hover:border-terra-500 hover:text-terra-600 dark:hover:text-terra-400 text-slate-800 dark:text-slate-200 text-xs font-bold rounded-xl transition-all shadow-xs cursor-pointer">
                            <span wire:loading.remove wire:target="loadMoreReviews">Muat Ulasan Lainnya</span>
                            <span wire:loading wire:target="loadMoreReviews">Memuat...</span>
                        </button>
                    </div>
                @endif
            </div>

            <!-- Tulis Ulasan Form Box -->
            <div class="mt-10 pt-10 border-t border-slate-100 dark:border-slate-800">
                <h3 class="font-display text-lg font-bold text-slate-900 dark:text-white mb-2">Tulis Pengalaman Anda</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-6">Berikan ulasan dan penilaian Anda mengenai kualitas produk dan layanan kami.</p>

                @if (session()->has('review_success'))
                    <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 rounded-xl text-xs font-semibold">
                        {{ session('review_success') }}
                    </div>
                @endif

                <form wire:submit.prevent="submitReview" class="space-y-4 max-w-2xl">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Nama Lengkap</label>
                            <input type="text" wire:model="reviewer_name" placeholder="Nama Anda" class="w-full h-11 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 text-xs text-slate-800 dark:text-white focus:ring-2 focus:ring-terra-500 focus:bg-white dark:focus:bg-slate-800 transition-all">
                            @error('reviewer_name') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Kota Asal (Opsional)</label>
                            <input type="text" wire:model="reviewer_location" placeholder="Contoh: Bandung, Bekasi" class="w-full h-11 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 text-xs text-slate-800 dark:text-white focus:ring-2 focus:ring-terra-500 focus:bg-white dark:focus:bg-slate-800 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1.5">Rating Bintang</label>
                        <div class="flex items-center gap-1.5">
                            @for($i=1; $i<=5; $i++)
                                <button type="button" wire:click="$set('rating', {{ $i }})" class="p-1 focus:outline-none hover:scale-110 transition-transform cursor-pointer">
                                    <svg class="w-7 h-7 {{ $i <= $rating ? 'text-amber-400' : 'text-slate-200 dark:text-slate-700' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                </button>
                            @endfor
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Ulasan Anda</label>
                        <textarea wire:model="content" rows="3" placeholder="Ceritakan kepuasan Anda mengenai kekuatan beton, kerapian presisi, atau kecepatan pengiriman..." class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-xs text-slate-800 dark:text-white focus:ring-2 focus:ring-terra-500 focus:bg-white dark:focus:bg-slate-800 transition-all"></textarea>
                        @error('content') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="px-8 py-3.5 bg-terra-500 hover:bg-terra-600 text-white text-xs font-bold rounded-xl shadow-md shadow-terra-500/20 transition-all cursor-pointer">
                        Kirim Ulasan Sekarang
                    </button>
                </form>
            </div>
        </div>

        <!-- Section: Metode Pembayaran & Jasa Pengiriman Resmi (seperti referensi Toco) -->
        <x-trust-payment-shipping />

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
         x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/90 backdrop-blur-sm"
         style="display: none;"
         @keydown.escape.window="open = false">
        
        <button @click="open = false" class="absolute top-6 right-6 text-white hover:text-gray-300 transition-colors cursor-pointer">
            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div @click.away="open = false" class="max-w-4xl w-full max-h-full flex items-center justify-center">
            <template x-if="type === 'image'">
                <img :src="url" class="rounded-2xl shadow-2xl object-contain max-h-[85vh] max-w-full">
            </template>
            <template x-if="type === 'video'">
                <video :src="url" class="rounded-2xl shadow-2xl object-contain max-h-[85vh] max-w-full" controls autoplay></video>
            </template>
        </div>
    </div>

    <!-- Warning Modal & External URL Listener -->
    <div x-data="{ open: false, title: '', message: '' }"
         x-on:open-warning-modal.window="
             const data = Array.isArray($event.detail) ? $event.detail[0] : $event.detail;
             title = data.title || '';
             message = data.message || '';
             open = true;
         "
         x-on:open-external-url.window="
             const data = Array.isArray($event.detail) ? $event.detail[0] : $event.detail;
             const url = data.url || data;
             if (url) {
                 window.open(url, '_blank');
             }
         "
         x-show="open" 
         x-cloak
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
         style="display: none;">
         
        <div @click.away="open = false" class="bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 shadow-luxury border border-slate-100 dark:border-slate-800 relative">
            <h3 class="font-display text-lg font-bold text-slate-900 dark:text-white mb-2" x-text="title">Perhatian</h3>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mb-6 leading-relaxed" x-text="message"></p>
            <div class="flex justify-end">
                <button @click="open = false" class="px-5 py-2.5 bg-terra-500 text-white text-xs font-bold rounded-xl shadow-xs cursor-pointer">
                    Mengerti
                </button>
            </div>
        </div>
    </div>
</div>
