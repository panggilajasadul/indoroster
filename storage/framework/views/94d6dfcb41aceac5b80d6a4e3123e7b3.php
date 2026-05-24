<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['data']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['data']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $title = $data['title'] ?? 'Produk Unggulan';
    $categoryIds = $data['categories'] ?? [];
    $limit = $data['limit'] ?? 8;
    $bgTheme = $data['bg_theme'] ?? 'white';
    $bgClasses = match($bgTheme) { 'dark' => 'bg-slate-900 text-white', 'accent' => 'bg-accent text-white', 'slate' => 'bg-slate-50 text-slate-900', 'gradient' => 'bg-gradient-to-br from-slate-900 via-slate-800 to-terra-900 text-white', default => 'bg-white text-slate-900' };

    $query = \App\Models\Product::with('category', 'media', 'variants')->active();
    if (!empty($categoryIds)) {
        $query->whereIn('category_id', $categoryIds);
    }
    $products = $query->latest()->limit($limit)->get();
?>

<section class="py-24 <?php echo e($bgClasses); ?> relative overflow-hidden">
    <!-- Decoration -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-accent/5 rounded-full blur-[100px] -mr-48 -mt-48"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-accent/5 rounded-full blur-[100px] -ml-48 -mb-48"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black font-display text-black leading-tight mb-8">
                <?php echo $title; ?>

            </h2>
            <div class="flex justify-center">
                <a href="<?php echo e(route('catalog')); ?>" class="group flex items-center gap-4 text-black font-black text-sm uppercase tracking-widest hover:text-accent transition-all">
                    <span>Lihat Semua Katalog</span>
                    <div class="w-12 h-12 rounded-full border border-black/10 flex items-center justify-center group-hover:bg-accent group-hover:border-accent transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </div>
                </a>
            </div>
        </div>

        <!-- Product Grid (Centered) -->
        <div class="flex flex-wrap justify-center gap-3 sm:gap-4 lg:gap-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('product.detail', $product->slug)); ?>" class="bg-white rounded-md border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 group flex flex-col overflow-hidden relative hover:border-terra-400 w-[calc(50%-0.75rem)] sm:w-[calc(33.333%-1rem)] md:w-[calc(25%-1.5rem)] lg:w-[calc(20%-1.5rem)] xl:w-[calc(16.666%-1.5rem)] max-w-[220px]">
                
                <!-- Media Section -->
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

                    <!-- Discount Badge -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->discount_percentage > 0): ?>
                        <div class="absolute top-0 right-0 bg-[#ffeee8] text-[#ee4d2d] border border-[#ffc9b8] text-[10px] font-bold px-1.5 py-0.5 rounded-bl z-10">
                            <?php echo e($product->discount_percentage); ?>% OFF
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Best Seller Badge -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($loop->first): ?>
                        <div class="absolute top-0 left-0 bg-black text-accent text-[9px] font-black px-2 py-1 rounded-br z-10 tracking-wider uppercase">
                            #1 Best
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Video Indicator -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->has_video): ?>
                        <div class="absolute bottom-1 right-1 bg-black/40 text-white rounded-full p-1 backdrop-blur-sm z-10 shadow-sm">
                            <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Info Section -->
                <div class="p-2 flex flex-col flex-grow">
                    <div class="text-xs text-slate-800 leading-snug mb-1 line-clamp-2 font-medium group-hover:text-terra-600 transition-colors">
                        <?php echo e($product->name); ?>

                    </div>
                    
                    <div class="mt-auto">
                        <div class="flex items-center justify-between gap-1 mb-0.5">
                            <span class="text-sm font-bold text-[#ee4d2d] leading-none"><?php echo e($product->formatted_price_range); ?></span>
                        </div>

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
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.page-builder video[autoplay]').forEach(function(video) {
        video.play().catch(function() {});
    });
});
</script>
<?php /**PATH C:\xampp\htdocs\indoroster\resources\views/components/blocks/featured-products.blade.php ENDPATH**/ ?>