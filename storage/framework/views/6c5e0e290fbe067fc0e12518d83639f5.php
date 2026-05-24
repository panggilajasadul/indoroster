<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['product', 'breadcrumbs' => []]));

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

foreach (array_filter((['product', 'breadcrumbs' => []]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $siteUrl    = config('app.url');
    $productUrl = route('product.detail', $product->slug);
    $imageUrl   = $product->primary_image ?? asset('assets/logo_indoroster_no_text.PNG');
    $price      = number_format($product->min_price, 0, '.', '');
    $currency   = 'IDR';
    $stock      = $product->total_stock > 0 ? 'InStock' : 'OutOfStock';
    $rating     = $product->average_rating;
    $reviewCount = $product->reviews_count;

    // Build breadcrumb list
    $breadcrumbItems = [
        ['id' => $siteUrl . '/', 'name' => 'Beranda'],
        ['id' => route('catalog'), 'name' => 'Katalog'],
    ];
    if ($product->category) {
        $breadcrumbItems[] = [
            'id'   => route('catalog', ['category' => $product->category->slug]),
            'name' => $product->category->name,
        ];
    }
    $breadcrumbItems[] = ['id' => $productUrl, 'name' => $product->name];
?>

<!-- Product Schema -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "<?php echo e($product->name); ?>",
    "description": "<?php echo e(strip_tags($product->short_description ?? $product->description ?? 'Roster beton minimalis premium dari pabrik Indoroster Plered Purwakarta')); ?>",
    "image": ["<?php echo e($imageUrl); ?>"],
    "sku": "<?php echo e($product->sku ?? $product->slug); ?>",
    "brand": {
        "@type": "Brand",
        "name": "Indoroster"
    },
    "manufacturer": {
        "@type": "Organization",
        "name": "Indoroster",
        "url": "<?php echo e($siteUrl); ?>"
    },
    "category": "<?php echo e($product->category->name ?? 'Roster Beton'); ?>",
    "url": "<?php echo e($productUrl); ?>",
    <?php if($reviewCount > 0): ?>
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "<?php echo e($rating); ?>",
        "reviewCount": "<?php echo e($reviewCount); ?>",
        "bestRating": "5",
        "worstRating": "1"
    },
    <?php endif; ?>
    "offers": {
        "@type": "Offer",
        "url": "<?php echo e($productUrl); ?>",
        "priceCurrency": "<?php echo e($currency); ?>",
        "price": "<?php echo e($price); ?>",
        "availability": "https://schema.org/<?php echo e($stock); ?>",
        "itemCondition": "https://schema.org/NewCondition",
        "seller": {
            "@type": "Organization",
            "name": "Indoroster"
        },
        "areaServed": {
            "@type": "Country",
            "name": "Indonesia"
        }
    }
}
</script>

<!-- BreadcrumbList Schema -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        <?php $__currentLoopData = $breadcrumbItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        {
            "@type": "ListItem",
            "position": <?php echo e($index + 1); ?>,
            "name": "<?php echo e($item['name']); ?>",
            "item": "<?php echo e($item['id']); ?>"
        }<?php echo e(!$loop->last ? ',' : ''); ?>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    ]
}
</script>
<?php /**PATH C:\xampp\htdocs\indoroster\resources\views/components/product-schema.blade.php ENDPATH**/ ?>