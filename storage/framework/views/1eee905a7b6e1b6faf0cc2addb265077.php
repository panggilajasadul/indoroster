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
    $badge = $data['badge'] ?? '';
    $title = $data['title'] ?? '';
    $description = $data['description'] ?? '';
    $items = $data['items'] ?? [];
    $bgTheme = $data['bg_theme'] ?? 'dark';
    $bgClasses = match($bgTheme) { 'dark' => 'bg-slate-900 text-white', 'accent' => 'bg-accent text-white', 'slate' => 'bg-slate-50 text-slate-900', 'gradient' => 'bg-gradient-to-br from-slate-900 via-slate-800 to-terra-900 text-white', default => 'bg-white text-slate-900' };
?>

<section class="py-24 <?php echo e($bgClasses); ?> relative overflow-hidden">
    <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 40px 40px;"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center mb-20">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($badge): ?>
            <span class="text-accent font-black text-xs uppercase tracking-[0.3em] mb-4 block italic"><?php echo e($badge); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <h2 class="text-4xl md:text-6xl font-black font-display mb-6"><?php echo $title; ?></h2>
            <p class="text-slate-400 max-w-2xl mx-auto text-lg"><?php echo $description; ?></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $imageUrl = !empty($item['image_upload']) ? asset('storage/' . $item['image_upload']) : (str_starts_with($item['image'] ?? '', 'http') ? $item['image'] : asset('storage/' . ($item['image'] ?? '')));
                $ext = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
                $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg']) || str_contains(strtolower($imageUrl), 'video');
            ?>
            <div class="group relative aspect-square overflow-hidden rounded-2xl bg-slate-900">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isVideo): ?>
                <video src="<?php echo e($imageUrl); ?>" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-70 group-hover:opacity-100" autoplay loop muted playsinline></video>
                <?php elseif($imageUrl): ?>
                <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($item['title'] ?? ''); ?>" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110 opacity-70 group-hover:opacity-100">
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80"></div>
                <div class="absolute bottom-0 left-0 right-0 p-8 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                    <h3 class="text-xl font-black mb-2"><?php echo e($item['title'] ?? ''); ?></h3>
                    <a href="<?php echo e(route('gallery')); ?>" class="text-accent text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                        Lihat Detail Proyek
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="mt-16 text-center">
            <a href="<?php echo e(route('gallery')); ?>" class="inline-block px-12 py-5 border border-white/20 hover:border-accent hover:text-accent font-black text-xs uppercase tracking-[0.2em] transition-all">
                Jelajahi Semua Inspirasi
            </a>
        </div>
    </div>
</section>
<?php /**PATH C:\xampp\htdocs\indoroster\resources\views/components/blocks/gallery-grid.blade.php ENDPATH**/ ?>