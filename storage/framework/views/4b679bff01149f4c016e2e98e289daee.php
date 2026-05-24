<div class="bg-slate-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6 text-center hidden md:block">
            <h1 class="font-display text-fluid-h2 font-black text-slate-900 tracking-tight mb-2">Katalog Roster & Bata</h1>
            <p class="text-base text-slate-500 max-w-2xl mx-auto">Temukan berbagai koleksi roster beton, bata expose, dan ornamen dinding dengan kualitas pabrik terbaik.</p>
        </div>

        <!-- Horizontal Filter Bar (Sticky) -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-3 md:p-5 mb-6 relative z-30">
            <div class="flex flex-col lg:flex-row gap-4 lg:gap-5 items-start lg:items-center w-full">
                
                <!-- Search Section (Expanded) -->
                <div class="w-full lg:flex-grow">
                    <div class="flex flex-col gap-1.5">
                        <label class="font-display text-[10px] md:text-[11px] font-bold text-terra-600 uppercase tracking-wider ml-1">Cari Produk Roster, Bata & Ornamen</label>
                        <div class="relative">
                            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Masukkan nama produk atau deskripsi..." style="padding-left: 2.5rem;" class="w-full h-10 md:h-12 pr-3 md:pr-4 py-2 border border-gray-200 rounded-lg md:rounded-xl focus:ring-2 focus:ring-terra-500 focus:border-terra-500 text-xs md:text-sm bg-slate-50 transition-all">
                            <svg class="w-4 md:w-5 h-4 md:h-5 text-gray-400 absolute left-3 md:left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Filters Section (Compact) -->
                <div class="flex flex-row gap-2 md:gap-3 w-full lg:w-auto shrink-0">
                    <!-- Category Dropdown -->
                    <div class="w-1/2 lg:w-48">
                        <div class="flex flex-col gap-1.5">
                            <label class="font-display text-[10px] md:text-[11px] font-bold text-slate-400 uppercase tracking-wider ml-1">Kategori</label>
                            <select wire:model.live="categorySlug" class="w-full h-10 md:h-12 border border-gray-200 rounded-lg md:rounded-xl px-2 md:px-4 py-2 text-xs md:text-sm focus:ring-2 focus:ring-terra-500 focus:border-terra-500 bg-slate-50 text-slate-600 cursor-pointer">
                                <option value="">Semua Kategori</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->slug); ?>"><?php echo e($cat->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Sort -->
                    <div class="w-1/2 lg:w-40">
                        <div class="flex flex-col gap-1.5">
                            <label class="font-display text-[10px] md:text-[11px] font-bold text-slate-400 uppercase tracking-wider ml-1">Urutan</label>
                            <select wire:model.live="sortBy" class="w-full h-10 md:h-12 border border-gray-200 rounded-lg md:rounded-xl px-2 md:px-4 py-2 text-xs md:text-sm focus:ring-2 focus:ring-terra-500 focus:border-terra-500 bg-slate-50 text-slate-600 cursor-pointer">
                                <option value="newest">Terbaru</option>
                                <option value="price_asc">Termurah</option>
                                <option value="price_desc">Termahal</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommendations for Viral Products (Placed below Search/Filters) -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$search && !$categorySlug && isset($viralProducts) && $viralProducts->count() > 0): ?>
            <div class="mb-10 bg-gradient-to-br from-amber-50/40 via-white to-rose-50/40 rounded-2xl border border-amber-100/60 shadow-sm p-4 sm:p-6 relative overflow-hidden">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-bl from-amber-100/40 to-transparent rounded-full opacity-50 blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-gradient-to-tr from-rose-100/40 to-transparent rounded-full opacity-50 blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10">
                    <!-- Header -->
                    <div class="mb-5">
                        <h2 class="font-display text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                            Rekomendasi Produk Viral <span class="inline-block animate-bounce text-2xl">🔥</span>
                        </h2>
                        <p class="text-slate-500 text-sm mt-1">Pilihan roster beton minimalis terpopuler yang paling banyak dibeli pelanggan.</p>
                    </div>

                    <!-- 6 Columns Grid like main products -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2 sm:gap-3 lg:gap-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $viralProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="/produk/<?php echo e($product->slug); ?>" class="bg-white rounded-md border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 group flex flex-col overflow-hidden relative hover:border-terra-400">
                            
                            <!-- Bagian Gambar / Media -->
                            <div class="relative aspect-square overflow-hidden bg-gray-100">
                                <?php
                                    $displayMedia = $product->primary_media;
                                ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($displayMedia): ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($displayMedia->media_type === 'video' && !str_contains($displayMedia->media_url, 'youtube.com') && !str_contains($displayMedia->media_url, 'youtu.be')): ?>
                                        <video src="<?php echo e($displayMedia->formatted_url); ?>" 
                                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                            autoplay muted loop playsinline></video>
                                    <?php else: ?>
                                        <img src="<?php echo e($displayMedia->media_type === 'image' ? $displayMedia->formatted_url : $product->primary_image); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">No Image</div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                
                                <!-- Badge Diskon (Kanan Atas) -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->discount_percentage > 0): ?>
                                    <div class="absolute top-0 right-0 bg-[#ffeee8] text-[#ee4d2d] border border-[#ffc9b8] text-[10px] font-bold px-1.5 py-0.5 rounded-bl z-10">
                                        <?php echo e($product->discount_percentage); ?>% OFF
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- VIRAL Badge (Kiri Atas) -->
                                <div class="absolute top-1 left-1 z-10">
                                    <span class="bg-black/75 backdrop-blur-sm text-terra-400 text-[8px] font-black px-1.5 py-0.5 rounded-sm tracking-wider uppercase flex items-center gap-1 shadow-sm">
                                        <span class="w-1 h-1 bg-terra-500 rounded-full animate-ping"></span>
                                        Viral
                                    </span>
                                </div>

                                <!-- Indikator Video (Kanan Bawah Gambar) -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_video): ?> 
                                    <div class="absolute bottom-1 right-1 bg-black/40 text-white rounded-full p-1 backdrop-blur-sm z-10 shadow-sm">
                                        <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->stock_status === 'out_of_stock'): ?>
                                    <div class="absolute inset-0 bg-white/60 backdrop-blur-sm flex items-center justify-center z-20">
                                        <span class="bg-slate-900 text-white text-xs font-bold px-2 py-1 rounded shadow-lg transform -rotate-12">HABIS</span>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <!-- Bagian Detail Teks -->
                            <div class="p-2 flex flex-col flex-grow">
                                <div class="text-[9px] text-slate-400 mb-0.5 font-semibold uppercase tracking-wider"><?php echo e($product->category->name ?? 'Roster'); ?></div>
                                <!-- Nama Produk -->
                                <div class="font-display text-xs text-slate-800 leading-snug mb-1 line-clamp-2 font-normal group-hover:text-terra-600 transition-colors">
                                    <?php echo e($product->name); ?>

                                </div>
                                
                                <div class="mt-auto">
                                    <!-- Rating -->
                                    <div class="flex items-center gap-0.5 mb-1">
                                        <svg class="w-2.5 h-2.5 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span class="text-[9px] font-bold text-slate-700"><?php echo e($product->average_rating); ?></span>
                                    </div>
                                    <!-- Harga -->
                                    <div class="flex items-center justify-between gap-1 mb-0.5">
                                        <span class="text-sm font-bold text-[#ee4d2d] leading-none"><?php echo e($product->formatted_price_range); ?></span>
                                    </div>

                                    <!-- Terjual & Rating Coretan (Opsional) -->
                                    <div class="flex items-center justify-between">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_discount): ?>
                                            <span class="text-[9px] text-slate-400 line-through leading-none">Rp<?php echo e(number_format($product->original_price, 0, ',', '.')); ?></span>
                                        <?php else: ?>
                                            <span></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <span class="text-[9px] text-slate-500 whitespace-nowrap">
                                            <?php echo e($product->total_sold > 0 ? $product->formatted_total_sold . ' terjual' : ''); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                </div> <!-- End relative z-10 -->
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Product Grid Section -->
        <div class="w-full">
            <div wire:loading class="w-full text-center py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-terra-500"></div>
                <p class="mt-2 text-slate-500 text-sm">Memuat produk...</p>
            </div>

            <div wire:loading.remove>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($products->count() > 0): ?>
                    <!-- 6 Columns on Extra Large Desktop, 2 on Mobile -->
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-2 sm:gap-3 lg:gap-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="/produk/<?php echo e($product->slug); ?>" class="bg-white rounded-md border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 group flex flex-col overflow-hidden relative hover:border-terra-400">
                            
                            <!-- Bagian Gambar / Media -->
                            <div class="relative aspect-square overflow-hidden bg-gray-100">
                                <?php
                                    $displayMedia = $product->primary_media;
                                ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($displayMedia): ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($displayMedia->media_type === 'video' && !str_contains($displayMedia->media_url, 'youtube.com') && !str_contains($displayMedia->media_url, 'youtu.be')): ?>
                                        <video src="<?php echo e($displayMedia->formatted_url); ?>" 
                                            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                                            autoplay muted loop playsinline></video>
                                    <?php else: ?>
                                        <img src="<?php echo e($displayMedia->media_type === 'image' ? $displayMedia->formatted_url : $product->primary_image); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php else: ?>
                                    <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs">No Image</div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                
                                <!-- Badge Diskon (Kanan Atas) -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->discount_percentage > 0): ?>
                                    <div class="absolute top-0 right-0 bg-[#ffeee8] text-[#ee4d2d] border border-[#ffc9b8] text-[10px] font-bold px-1.5 py-0.5 rounded-bl z-10">
                                        <?php echo e($product->discount_percentage); ?>% OFF
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <!-- Indikator Video (Kanan Bawah Gambar) -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_video): ?> 
                                    <div class="absolute bottom-1 right-1 bg-black/40 text-white rounded-full p-1 backdrop-blur-sm z-10 shadow-sm">
                                        <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->stock_status === 'out_of_stock'): ?>
                                    <div class="absolute inset-0 bg-white/60 backdrop-blur-sm flex items-center justify-center z-20">
                                        <span class="bg-slate-900 text-white text-xs font-bold px-2 py-1 rounded shadow-lg transform -rotate-12">HABIS</span>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <!-- Bagian Detail Teks -->
                            <div class="p-2 flex flex-col flex-grow">
                                <!-- Nama Produk -->
                                <div class="font-display text-xs text-slate-800 leading-snug mb-1 line-clamp-2 font-normal group-hover:text-terra-600 transition-colors">
                                    <?php echo e($product->name); ?>

                                </div>
                                
                                <div class="mt-auto">
                                    <!-- Harga -->
                                    <div class="flex items-center justify-between gap-1 mb-0.5">
                                        <span class="text-sm font-bold text-[#ee4d2d] leading-none"><?php echo e($product->formatted_price_range); ?></span>
                                    </div>

                                    <!-- Terjual & Rating Coretan (Opsional) -->
                                    <div class="flex items-center justify-between">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_discount): ?>
                                            <span class="text-[9px] text-slate-400 line-through leading-none">Rp<?php echo e(number_format($product->original_price, 0, ',', '.')); ?></span>
                                        <?php else: ?>
                                            <span></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <span class="text-[9px] text-slate-500 whitespace-nowrap">
                                            <?php echo e($product->total_sold > 0 ? $product->formatted_total_sold . ' terjual' : ''); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    
                    <div class="mt-8 mb-4">
                        <?php echo e($products->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="bg-white rounded-xl border border-gray-100 p-12 text-center shadow-sm">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75l-2.489-2.489m0 0a3.375 3.375 0 10-4.773-4.773 3.375 3.375 0 004.774 4.774zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Produk Tidak Ditemukan</h3>
                        <p class="text-slate-500 mb-4 text-sm">Maaf, kami tidak dapat menemukan produk yang sesuai dengan pencarian Anda.</p>
                        <button wire:click="$set('search', '')" class="bg-terra-500 text-white px-5 py-2 rounded font-medium hover:bg-terra-600 transition-colors text-sm">
                            Hapus Pencarian
                        </button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        
    </div>
    <style>
    /* Sembunyikan scrollbar untuk filter kategori tapi tetap bisa discroll */
    .hide-scroll-bar {
      -ms-overflow-style: none;  /* IE and Edge */
      scrollbar-width: none;  /* Firefox */
    }
    .hide-scroll-bar::-webkit-scrollbar {
      display: none;
    }
    </style>
</div>

<?php /**PATH C:\xampp\htdocs\indoroster\resources\views/livewire/product-catalog.blade.php ENDPATH**/ ?>