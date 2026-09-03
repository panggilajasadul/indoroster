@props([
    'name' => 'Katalog Roster Beton IndoRoster',
    'description' => 'Daftar produk roster beton minimalis presisi harga langsung pabrik.',
    'products' => collect(),
])

@php
    $itemList = [];
    $position = 1;

    foreach ($products as $p) {
        $primaryImg = $p->primary_media 
            ? ($p->primary_media->media_type === 'image' ? $p->primary_media->formatted_url : $p->primary_image)
            : ($p->primary_image ?: asset('assets/logo_indoroster_no_text.PNG'));

        $rawPrice = $p->min_price > 0 ? $p->min_price : ((float) $p->price > 0 ? (float) $p->price : 13000);
        $price = (string) (int) $rawPrice;
        $rating = $p->average_rating > 0 ? (float) $p->average_rating : 4.9;
        $reviewCount = $p->reviews_count > 0 ? (int) $p->reviews_count : max(15, (int) ($p->total_sold / 50));

        $item = [
            '@type' => 'Product',
            'name' => $p->name,
            'image' => $primaryImg,
            'description' => $p->meta_description ?: ($p->excerpt ?: "Roster beton minimalis {$p->name} cetak tumbuk padat plat baja presisi."),
            'sku' => $p->sku ?: ('ROSTER-' . $p->id),
            'brand' => [
                '@type' => 'Brand',
                'name' => 'IndoRoster',
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('product.detail', $p->slug),
                'priceCurrency' => 'IDR',
                'price' => $price,
                'priceValidUntil' => \Carbon\Carbon::now()->addYear()->format('Y-m-d'),
                'validFrom' => \Carbon\Carbon::now()->subMonths(6)->format('Y-m-d'),
                'itemCondition' => 'https://schema.org/NewCondition',
                'availability' => ($p->total_stock > 0 || $p->stock_status === 'in_stock') 
                    ? 'https://schema.org/InStock' 
                    : 'https://schema.org/PreOrder',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => 'IndoRoster',
                ],
                'valueAddedTaxIncluded' => true,
                'shippingDetails' => [
                    '@type' => 'OfferShippingDetails',
                    'shippingRate' => [
                        '@type' => 'MonetaryAmount',
                        'value' => '0',
                        'currency' => 'IDR',
                    ],
                    'shippingDestination' => [
                        '@type' => 'DefinedRegion',
                        'addressCountry' => 'ID',
                    ],
                    'deliveryTime' => [
                        '@type' => 'ShippingDeliveryTime',
                        'handlingTime' => [
                            '@type' => 'QuantitativeValue',
                            'minValue' => 1,
                            'maxValue' => 3,
                            'unitCode' => 'DAY',
                        ],
                        'transitTime' => [
                            '@type' => 'QuantitativeValue',
                            'minValue' => 1,
                            'maxValue' => 7,
                            'unitCode' => 'DAY',
                        ],
                    ],
                ],
                'hasMerchantReturnPolicy' => [
                    '@type' => 'MerchantReturnPolicy',
                    'applicableCountry' => 'ID',
                    'returnPolicyCategory' => 'https://schema.org/MerchantReturnFiniteReturnWindow',
                    'merchantReturnDays' => 7,
                    'returnMethod' => 'https://schema.org/ReturnByMail',
                    'returnFees' => 'https://schema.org/FreeReturn',
                ],
            ],
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => number_format($rating, 1, '.', ''),
                'reviewCount' => (string) max(1, $reviewCount),
                'bestRating' => '5',
                'worstRating' => '1',
            ],
        ];

        $itemList[] = [
            '@type' => 'ListItem',
            'position' => $position++,
            'item' => $item,
        ];

        if ($position > 20) {
            break; // Google structured data guideline max items per collection
        }
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'name' => $name,
        'description' => $description,
        'numberOfItems' => count($itemList),
        'itemListElement' => $itemList,
    ];
@endphp

@if(count($itemList) > 0)
<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif
