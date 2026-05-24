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
    $videos = $data['videos'] ?? [];
    $bgTheme = $data['bg_theme'] ?? 'white';
    $bgClasses = match($bgTheme) { 'dark' => 'bg-slate-900 text-white', 'accent' => 'bg-accent text-white', 'slate' => 'bg-slate-50 text-slate-900', 'gradient' => 'bg-gradient-to-br from-slate-900 via-slate-800 to-terra-900 text-white', default => 'bg-white text-slate-900' };
?>

<section class="py-24 <?php echo e($bgClasses); ?>">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
            <div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($badge): ?>
                <span class="text-accent font-black text-xs uppercase tracking-[0.3em] mb-4 block"><?php echo e($badge); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <h2 class="text-4xl md:text-5xl font-black font-display text-black leading-tight mb-8"><?php echo $title; ?></h2>
                <p class="text-slate-600 text-lg mb-10 leading-relaxed"><?php echo $description; ?></p>
                <div class="flex flex-wrap gap-4">
                    <a href="<?php echo e(route('video-inspiration')); ?>" class="px-8 py-4 bg-black text-white font-black text-xs uppercase tracking-widest hover:bg-accent hover:text-black transition-all">
                        Video Inspirasi Lengkap
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vid): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $videoUrl = !empty($vid['video_upload']) ? asset('storage/' . $vid['video_upload']) : ($vid['url'] ?? '');
                    $ext = pathinfo(parse_url($videoUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
                    $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg']) || str_contains(strtolower($videoUrl), 'video');
                ?>
                <div class="relative aspect-[9/16] rounded-3xl overflow-hidden bg-slate-100 shadow-2xl border-4 border-black/5">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isVideo): ?>
                    <video src="<?php echo e($videoUrl); ?>" autoplay muted loop playsinline class="w-full h-full object-cover"></video>
                    <?php elseif($videoUrl): ?>
                    <img src="<?php echo e($videoUrl); ?>" class="w-full h-full object-cover">
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-6">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-accent flex items-center justify-center">
                                <svg class="w-4 h-4 text-black" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                            <span class="text-white text-[10px] font-black uppercase tracking-widest">Live View</span>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php /**PATH C:\xampp\htdocs\indoroster\resources\views/components/blocks/ugc-videos.blade.php ENDPATH**/ ?>