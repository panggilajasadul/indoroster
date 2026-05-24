@props(['product', 'breadcrumbs' => []])

@php
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
@endphp

<!-- Product Schema -->
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Product",
    "name": "{{ $product->name }}",
    "description": "{{ strip_tags($product->short_description ?? $product->description ?? 'Roster beton minimalis premium dari pabrik Indoroster Plered Purwakarta') }}",
    "image": ["{{ $imageUrl }}"],
    "sku": "{{ $product->sku ?? $product->slug }}",
    "brand": {
        "@@type": "Brand",
        "name": "Indoroster"
    },
    "manufacturer": {
        "@@type": "Organization",
        "name": "Indoroster",
        "url": "{{ $siteUrl }}"
    },
    "category": "{{ $product->category->name ?? 'Roster Beton' }}",
    "url": "{{ $productUrl }}",
    @if ($reviewCount > 0)
    "aggregateRating": {
        "@@type": "AggregateRating",
        "ratingValue": "{{ $rating }}",
        "reviewCount": "{{ $reviewCount }}",
        "bestRating": "5",
        "worstRating": "1"
    },
    @endif
    "offers": {
        "@@type": "Offer",
        "url": "{{ $productUrl }}",
        "priceCurrency": "{{ $currency }}",
        "price": "{{ $price }}",
        "availability": "https://schema.org/{{ $stock }}",
        "itemCondition": "https://schema.org/NewCondition",
        "seller": {
            "@@type": "Organization",
            "name": "Indoroster"
        },
        "areaServed": {
            "@@type": "Country",
            "name": "Indonesia"
        }
    }
}
</script>

<!-- BreadcrumbList Schema -->
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        @foreach ($breadcrumbItems as $index => $item)
        {
            "@@type": "ListItem",
            "position": {{ $index + 1 }},
            "name": "{{ $item['name'] }}",
            "item": "{{ $item['id'] }}"
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ]
}
</script>
