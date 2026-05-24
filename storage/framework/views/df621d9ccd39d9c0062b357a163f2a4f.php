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
    $badge = $data['badge'] ?? 'Ready to start?';
    $title = $data['title'] ?? 'Wujudkan Hunian Impian Anda Sekarang';
    $buttonText = $data['button_text'] ?? 'Hubungi WhatsApp Sekarang';
    $buttonUrl = $data['button_url'] ?? '';
    $bgTheme = $data['bg_theme'] ?? 'accent';
    $bgClasses = match($bgTheme) { 'dark' => 'bg-slate-900 text-white', 'accent' => 'bg-accent text-white', 'slate' => 'bg-slate-50 text-slate-900', 'gradient' => 'bg-gradient-to-br from-slate-900 via-slate-800 to-terra-900 text-white', default => 'bg-white text-slate-900' };
    
    if (empty($buttonUrl)) {
        $whatsappNumber = \App\Models\SiteSetting::getValue('whatsapp_number', '081234567890');
        $buttonUrl = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $whatsappNumber);
    }
?>

<section class="py-24 <?php echo e($bgClasses); ?> relative overflow-hidden">
    <div class="absolute top-0 left-0 w-64 h-64 bg-black/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/10 rounded-full translate-x-1/3 translate-y-1/3"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative text-center">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($badge): ?>
        <span class="text-black font-black text-xs uppercase tracking-[0.3em] mb-6 block"><?php echo e($badge); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <h2 class="text-4xl md:text-6xl font-black font-display text-black leading-tight mb-10"><?php echo $title; ?></h2>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
            <a href="<?php echo e($buttonUrl); ?>" target="_blank" class="group relative px-12 py-6 bg-black text-white font-black text-sm uppercase tracking-[0.2em] rounded-full hover:scale-105 transition-all shadow-2xl">
                <span class="relative z-10 flex items-center gap-3">
                    <svg class="w-6 h-6 fill-accent" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412-.003 6.557-5.338 11.892-11.893 11.892-1.997-.001-3.951-.5-5.688-1.448l-6.309 1.656zm6.224-3.82l.446.265c1.404.835 2.99 1.276 4.6 1.277 5.252 0 9.527-4.275 9.529-9.528.002-2.546-.988-4.941-2.79-6.742s-4.195-2.791-6.741-2.792c-5.253 0-9.527 4.275-9.529 9.528 0 1.685.442 3.325 1.279 4.766l.291.503-1.11 4.053 4.146-1.088zm10.732-6.52c-.3-.15-1.774-.875-2.048-.974-.275-.1-.475-.15-.675.15-.2.3-.775.974-.95 1.174-.175.2-.35.225-.65.075-.3-.15-1.265-.467-2.41-1.485-.89-.794-1.49-1.775-1.665-2.075-.175-.3-.019-.462.13-.611.134-.134.3-.35.45-.525.15-.175.2-.3.3-.5.1-.2.05-.375-.025-.525-.075-.15-.675-1.625-.925-2.225-.244-.589-.491-.51-.675-.519l-.575-.01c-.2 0-.525.075-.8.375-.275.3-1.05 1.025-1.05 2.5 0 1.475 1.075 2.9 1.225 3.1.15.2 2.115 3.23 5.125 4.53.716.31 1.274.494 1.708.632.72.23 1.374.197 1.89.12.575-.085 1.774-.725 2.024-1.425.25-.7.25-1.3 0-1.425-.075-.125-.275-.2-.575-.35z"/></svg>
                    <?php echo e($buttonText); ?>

                </span>
            </a>
        </div>
    </div>
</section>
<?php /**PATH C:\xampp\htdocs\indoroster\resources\views/components/blocks/cta.blade.php ENDPATH**/ ?>