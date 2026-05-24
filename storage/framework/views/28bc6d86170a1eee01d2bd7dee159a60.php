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
    $images = $data['images'] ?? [];
    $title = $data['title'] ?? 'Visual Showcase';
?>

<section class="py-16 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <h2 class="text-center text-2xl md:text-4xl font-black font-display text-slate-900 leading-tight">
            <?php echo $title; ?>

        </h2>
    </div>

    <div class="relative flex overflow-x-hidden group">
        <?php
            $speed = $data['speed'] ?? 'animate-marquee';
        ?>
        <div class="<?php echo e($speed); ?> flex whitespace-nowrap gap-1">
            <?php
                $imagesUpload = $data['images_upload'] ?? [];
                // 'images' is a simple() repeater, so it's already an array of strings.
                $imagesUrl = is_array($images) ? array_filter($images) : [];
                $allImages = array_merge($imagesUpload, $imagesUrl);
            ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = array_merge($allImages, $allImages); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $src = str_starts_with($img, 'http') ? $img : asset('storage/' . $img);
                $ext = pathinfo(parse_url($src, PHP_URL_PATH), PATHINFO_EXTENSION);
                $isVideo = in_array(strtolower($ext), ['mp4', 'webm', 'ogg']) || str_contains(strtolower($src), 'video');
            ?>
            <div class="w-[300px] md:w-[450px] aspect-[4/3] rounded-none overflow-hidden shrink-0 shadow-lg border border-slate-100">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isVideo): ?>
                <video src="<?php echo e($src); ?>" class="w-full h-full object-cover" autoplay loop muted playsinline></video>
                <?php elseif($src): ?>
                <img src="<?php echo e($src); ?>" class="w-full h-full object-cover" loading="lazy">
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>
<?php /**PATH C:\xampp\htdocs\indoroster\resources\views/components/blocks/visual-showcase.blade.php ENDPATH**/ ?>