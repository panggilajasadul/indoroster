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
    $title = $data['title'] ?? '';
    $description = $data['description'] ?? '';
    $videoUrl = $data['video_url'] ?? '';
    $features = $data['features'] ?? [];
    $bgTheme = $data['bg_theme'] ?? 'slate';
    $bgClasses = match($bgTheme) { 'dark' => 'bg-slate-900 text-white', 'accent' => 'bg-accent text-white', 'slate' => 'bg-slate-50 text-slate-900', 'gradient' => 'bg-gradient-to-br from-slate-900 via-slate-800 to-terra-900 text-white', default => 'bg-white text-slate-900' };
?>

<section class="py-24 <?php echo e($bgClasses); ?> relative overflow-hidden">
    <div class="absolute top-0 left-0 w-96 h-96 bg-accent/5 rounded-full blur-[100px] -ml-48 -mt-48"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-16">
            <div class="lg:w-1/2">
                <h2 class="text-4xl md:text-5xl font-black font-display leading-tight mb-6 uppercase italic">
                    <?php echo $title; ?>

                </h2>
                <p class="text-lg opacity-80 mb-8 leading-relaxed">
                    <?php echo $description; ?>

                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-accent/10 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold uppercase text-sm"><?php echo e($feature['title'] ?? ''); ?></h4>
                            <p class="text-xs opacity-75 mt-1"><?php echo e($feature['desc'] ?? $feature['description'] ?? ''); ?></p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <div class="lg:w-1/2 w-full">
                <div class="relative aspect-video rounded-3xl overflow-hidden shadow-2xl border-8 border-white group">
                    <?php
                        $finalVideoUrl = !empty($data['video_upload']) ? asset('storage/' . $data['video_upload']) : $videoUrl;
                        $ext = pathinfo(parse_url($finalVideoUrl, PHP_URL_PATH), PATHINFO_EXTENSION);
                        $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg']) || str_contains(strtolower($finalVideoUrl), 'video');
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isVideo): ?>
                    <video class="w-full h-full object-cover" autoplay loop muted playsinline>
                        <source src="<?php echo e($finalVideoUrl); ?>" type="video/mp4">
                    </video>
                    <?php elseif($finalVideoUrl): ?>
                    <img src="<?php echo e($finalVideoUrl); ?>" class="w-full h-full object-cover">
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php /**PATH C:\xampp\htdocs\indoroster\resources\views/components/blocks/strength-test.blade.php ENDPATH**/ ?>