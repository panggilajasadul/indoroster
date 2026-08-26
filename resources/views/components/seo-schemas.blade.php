@php
    $siteName    = 'Indoroster';
    $siteUrl     = config('app.url');
    $logoUrl     = asset('assets/logo_indoroster_no_text.PNG');
    $address     = \App\Models\SiteSetting::getValue('factory_address', 'Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165');
    $phone       = \App\Models\SiteSetting::getValue('whatsapp_number', '+62 812-3456-7890');
    $email       = \App\Models\SiteSetting::getValue('contact_email', 'hello@indoroster.com');
    $instagram   = \App\Models\SiteSetting::getValue('instagram_url', '');
    $tiktok      = \App\Models\SiteSetting::getValue('tiktok_url', '');
    $youtube     = \App\Models\SiteSetting::getValue('youtube_url', '');

    $sameAs = array_filter([$instagram, $tiktok, $youtube]);
@endphp

<!-- Organization Schema (Local & Global Export) -->
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "Organization",
    "name": "{{ $siteName }}",
    "alternateName": ["IndoRoster Indonesia", "IndoRoster Breeze Block Factory"],
    "url": "{{ $siteUrl }}",
    "logo": {
        "@@type": "ImageObject",
        "url": "{{ $logoUrl }}",
        "width": 200,
        "height": 200
    },
    "contactPoint": [
        {
            "@@type": "ContactPoint",
            "telephone": "{{ $phone }}",
            "contactType": "customer service",
            "areaServed": ["ID", "Worldwide"],
            "availableLanguage": ["Indonesian", "English"]
        },
        {
            "@@type": "ContactPoint",
            "telephone": "{{ $phone }}",
            "contactType": "sales & export",
            "areaServed": ["ID", "Worldwide"],
            "availableLanguage": ["Indonesian", "English"]
        }
    ],
    "address": {
        "@@type": "PostalAddress",
        "addressLocality": "Tegalwaru",
        "addressRegion": "Jawa Barat",
        "postalCode": "41165",
        "addressCountry": "ID",
        "streetAddress": "{{ $address }}"
    },
    "knowsAbout": [
        "Roster Beton Minimalis",
        "Architectural Breeze Blocks",
        "Concrete Screen Blocks",
        "Loster Beton Modern",
        "Bata Tempel Terakota",
        "Ventilation Blocks Exporter"
    ],
    "sameAs": {!! json_encode(array_values($sameAs)) !!}
}
</script>

<!-- LocalBusiness & Manufacturer Schema (Local & International Exporter) -->
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": ["LocalBusiness", "Manufacturer"],
    "name": "IndoRoster — Pabrik Roster Beton Minimalis & Breeze Block Exporter",
    "image": "{{ $logoUrl }}",
    "url": "{{ $siteUrl }}",
    "telephone": "{{ $phone }}",
    "email": "{{ $email }}",
    "description": "Pabrik dan produsen tangan pertama roster beton minimalis, bata tempel, dan loster arsitektural modern di Plered, Purwakarta. Melayani pengiriman proyek seluruh Jabodetabek, Jawa Barat, seluruh Indonesia, serta International Export (Custom Concrete Screen & Breeze Blocks).",
    "address": {
        "@@type": "PostalAddress",
        "streetAddress": "{{ $address }}",
        "addressLocality": "Tegalwaru",
        "addressRegion": "Purwakarta, Jawa Barat",
        "postalCode": "41165",
        "addressCountry": "ID"
    },
    "geo": {
        "@@type": "GeoCoordinates",
        "latitude": "-6.6689917",
        "longitude": "107.3619295"
    },
    "openingHoursSpecification": [
        {
            "@@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
            "opens": "08:00",
            "closes": "17:00"
        }
    ],
    "priceRange": "Rp / USD",
    "currenciesAccepted": "IDR, USD, SGD, AUD",
    "paymentAccepted": "Cash, Transfer Bank, QRIS, TT (Telegraphic Transfer), LC (Letter of Credit)",
    "areaServed": [
        "Jakarta", "Bogor", "Depok", "Tangerang", "Bekasi", "Bandung", "Purwakarta", 
        "Jawa Barat", "Indonesia", "Southeast Asia", "Australia", "Worldwide"
    ]
}
</script>

<!-- WebSite + SearchAction Schema -->
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebSite",
    "name": "{{ $siteName }}",
    "url": "{{ $siteUrl }}",
    "potentialAction": {
        "@@type": "SearchAction",
        "target": {
            "@@type": "EntryPoint",
            "urlTemplate": "{{ $siteUrl }}/katalog?search={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    }
}
</script>
