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
    $title = $data['title'] ?? 'Kata Pelanggan Kami';
    $mode = $data['mode'] ?? 'latest';
    $bgTheme = $data['bg_theme'] ?? 'white';
    $bgClasses = match($bgTheme) { 'dark' => 'bg-slate-900 text-white', 'accent' => 'bg-accent text-white', 'slate' => 'bg-slate-50 text-slate-900', 'gradient' => 'bg-gradient-to-br from-slate-900 via-slate-800 to-terra-900 text-white', default => 'bg-white text-slate-900' };
    $headingColor = match($bgTheme) { 'dark', 'gradient' => 'text-white', 'accent' => 'text-white', default => 'text-slate-900' };
    $subColor = match($bgTheme) { 'dark', 'gradient' => 'text-slate-300', 'accent' => 'text-white/80', default => 'text-slate-600' };
    $cardBg = match($bgTheme) { 'dark', 'gradient' => 'bg-white/5 border-white/10', 'accent' => 'bg-white/10 border-white/20', 'slate' => 'bg-white border-slate-200', default => 'bg-slate-50 border-slate-100' };
    $dividerColor = match($bgTheme) { 'dark', 'gradient' => 'bg-terra-500', 'accent' => 'bg-white', default => 'bg-accent' };
    
    if ($mode === 'latest') {
        $testimonials = \App\Models\Testimonial::latest()->limit(3)->get();
    } else {
        $testimonials = \App\Models\Testimonial::latest()->limit(3)->get();
    }
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($testimonials->count() > 0): ?>
<section class="py-20 <?php echo e($bgClasses); ?>">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-black font-display <?php echo e($headingColor); ?> leading-tight mb-4"><?php echo $title; ?></h2>
            <div class="w-24 h-1 <?php echo e($dividerColor); ?> mx-auto rounded-full"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimoni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $rating = min(max(intval($testimoni->rating ?? 5), 1), 5); ?>
            <div class="rounded-2xl p-8 border shadow-sm <?php echo e($cardBg); ?> hover:shadow-md transition-shadow duration-300">
                <div class="flex text-terra-500 mb-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i=0; $i<$rating; $i++): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i=$rating; $i<5; $i++): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-300" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                    <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <p class="mb-8 italic <?php echo e($subColor); ?>">"<?php echo e($testimoni->content); ?>"</p>
                <div class="flex items-center gap-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($testimoni->photo_url): ?>
                        <img src="<?php echo e($testimoni->photo_url); ?>" alt="<?php echo e($testimoni->customer_name); ?>" class="w-12 h-12 rounded-full object-cover">
                    <?php else: ?>
                        <div class="w-12 h-12 bg-terra-100 text-terra-600 font-bold text-xl rounded-full flex items-center justify-center"><?php echo e(substr($testimoni->customer_name, 0, 1)); ?></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div>
                        <div class="font-bold <?php echo e($headingColor); ?>"><?php echo e($testimoni->customer_name); ?></div>
                        <div class="text-sm <?php echo e($subColor); ?>"><?php echo e($testimoni->customer_role); ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\xampp\htdocs\indoroster\resources\views/components/blocks/testimonials.blade.php ENDPATH**/ ?>