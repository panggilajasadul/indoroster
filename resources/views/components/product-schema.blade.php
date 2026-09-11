@props(['product', 'breadcrumbs' => []])

@php
    $siteUrl    = config('app.url');
    $productUrl = route('product.detail', $product->slug);
    $currency   = 'IDR';
    $stock      = $product->total_stock > 0 ? 'InStock' : 'OutOfStock';
    $rating     = $product->average_rating;
    $reviewCount = $product->reviews_count;
    $price      = number_format($product->min_price > 0 ? $product->min_price : ((float) $product->price > 0 ? (float) $product->price : 13000), 0, '.', '');

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

    // additionalProperty untuk spesifikasi teknis lengkap (Google Shopping & AI Parsing)
    $additionalProperties = [];
    if ($product->material) {
        $additionalProperties[] = ['name' => 'Material', 'value' => $product->material];
    } else {
        $additionalProperties[] = ['name' => 'Material', 'value' => 'Pasir Abu Batu Murni / Dolomit / Terakota Plered'];
    }
    
    $dimValue = $product->dimensions ? $product->dimensions : '20 x 20 x 10 cm';
    $additionalProperties[] = ['name' => 'Dimensi', 'value' => $dimValue];
    
    // Hitung estimasi kebutuhan modular per m²
    if (str_contains($dimValue, '30 x 15') || str_contains($dimValue, '30x15')) {
        $additionalProperties[] = ['name' => 'Kebutuhan per m²', 'value' => '22.2 pcs / m²'];
    } elseif (str_contains($dimValue, '25 x 15') || str_contains($dimValue, '25x15')) {
        $additionalProperties[] = ['name' => 'Kebutuhan per m²', 'value' => '26.7 pcs / m²'];
    } elseif (str_contains($dimValue, '20 x 10') || str_contains($dimValue, '20x10')) {
        $additionalProperties[] = ['name' => 'Kebutuhan per m²', 'value' => '50 pcs / m²'];
    } else {
        $additionalProperties[] = ['name' => 'Kebutuhan per m²', 'value' => '25 pcs / m²'];
    }

    $weightValue = $product->weight ? $product->weight . ' kg' : '3.8 - 4.2 kg';
    $additionalProperties[] = ['name' => 'Berat Satuan', 'value' => $weightValue];
    $additionalProperties[] = ['name' => 'Metode Produksi', 'value' => 'Cetak Tumbuk Padat Plat Baja Siku 90° Presisi Plered'];
    $additionalProperties[] = ['name' => 'Finishing & Fitur', 'value' => '2 Sisi Halus Presisi & Anti-Tampias Hujan'];

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
        "priceValidUntil": "{{ ($product->updated_at ?? now())->addYear()->format('Y-m-d') }}",
        "validFrom": "{{ ($product->created_at ?? now()->subMonths(6))->format('Y-m-d') }}",
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
            "shippingRate": {
                "@@type": "MonetaryAmount",
                "value": "0",
                "currency": "{{ $currency }}"
            },
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
        },
        "hasMerchantReturnPolicy": {
            "@@type": "MerchantReturnPolicy",
            "applicableCountry": "ID",
            "returnPolicyCategory": "https://schema.org/MerchantReturnFiniteReturnWindow",
            "merchantReturnDays": 7,
            "returnMethod": "https://schema.org/ReturnByMail",
            "returnFees": "https://schema.org/FreeReturn"
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
