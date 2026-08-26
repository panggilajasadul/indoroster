@props(['product', 'breadcrumbs' => []])

@php
    $siteUrl    = config('app.url');
    $productUrl = route('product.detail', $product->slug);
    $currency   = 'IDR';
    $stock      = $product->total_stock > 0 ? 'InStock' : 'OutOfStock';
    $rating     = $product->average_rating;
    $reviewCount = $product->reviews_count;
    $price      = number_format($product->min_price, 0, '.', '');

    // Kumpulkan semua URL gambar produk (Google Images & Google Shopping membutuhkan array)
    $imageUrls = $product->media
        ->where('media_type', 'image')
        ->map(fn($m) => $m->formatted_url)
        ->filter()
        ->values()
        ->toArray();

    // Fallback ke logo jika tidak ada gambar
    if (empty($imageUrls)) {
        $imageUrls = [asset('assets/logo_indoroster_no_text.PNG')];
    }

    // additionalProperty untuk material, dimensi, berat (Google Shopping fitur)
    $additionalProperties = [];
    if ($product->material) {
        $additionalProperties[] = ['name' => 'Material', 'value' => $product->material];
    }
    if ($product->dimensions) {
        $additionalProperties[] = ['name' => 'Dimensi', 'value' => $product->dimensions . ' cm'];
    }
    if ($product->weight) {
        $additionalProperties[] = ['name' => 'Berat', 'value' => $product->weight . ' kg'];
    }

    // Breadcrumb: gunakan clean category URL
    $breadcrumbItems = [
        ['id' => $siteUrl . '/', 'name' => 'Beranda'],
        ['id' => route('catalog'), 'name' => 'Katalog'],
    ];
    if ($product->category) {
        $breadcrumbItems[] = [
            'id'   => route('catalog.category', $product->category->slug),
            'name' => $product->category->name,
        ];
    }
    $breadcrumbItems[] = ['id' => $productUrl, 'name' => $product->name];
@endphp

<!-- Product Schema (Google Search & Google Shopping) -->
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Product",
    "name": "{{ e($product->name) }}",
    "description": "{{ e(strip_tags($product->short_description ?? Str::limit(strip_tags($product->description ?? ''), 500) ?? 'Roster beton minimalis premium dari pabrik Indoroster Plered Purwakarta')) }}",
    "image": {!! json_encode($imageUrls) !!},
    "sku": "{{ e($product->sku ?? $product->slug) }}",
    "mpn": "{{ e($product->sku ?? $product->slug) }}",
    "brand": {
        "@@type": "Brand",
        "name": "Indoroster"
    },
    "manufacturer": {
        "@@type": "Organization",
        "name": "Indoroster",
        "url": "{{ $siteUrl }}"
    },
    "category": "{{ e($product->category->name ?? 'Roster Beton') }}",
    "url": "{{ $productUrl }}",
    @if(count($additionalProperties) > 0)
    "additionalProperty": [
        @foreach($additionalProperties as $prop)
        {
            "@@type": "PropertyValue",
            "name": "{{ $prop['name'] }}",
            "value": "{{ e($prop['value']) }}"
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ],
    @endif
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
        "priceValidUntil": "{{ now()->addYear()->format('Y-m-d') }}",
        "availability": "https://schema.org/{{ $stock }}",
        "itemCondition": "https://schema.org/NewCondition",
        "seller": {
            "@@type": "Organization",
            "name": "Indoroster"
        },
        "areaServed": {
            "@@type": "Country",
            "name": "Indonesia"
        },
        "shippingDetails": {
            "@@type": "OfferShippingDetails",
            "shippingDestination": {
                "@@type": "DefinedRegion",
                "addressCountry": "ID"
            },
            "deliveryTime": {
                "@@type": "ShippingDeliveryTime",
                "handlingTime": {
                    "@@type": "QuantitativeValue",
                    "minValue": 1,
                    "maxValue": 3,
                    "unitCode": "DAY"
                },
                "transitTime": {
                    "@@type": "QuantitativeValue",
                    "minValue": 1,
                    "maxValue": 7,
                    "unitCode": "DAY"
                }
            }
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
            "name": "{{ e($item['name']) }}",
            "item": "{{ $item['id'] }}"
        }{{ !$loop->last ? ',' : '' }}
        @endforeach
    ]
}
</script>
