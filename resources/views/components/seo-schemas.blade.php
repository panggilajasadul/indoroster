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
        "Pengadaan Roster Partai Besar Proyek",
        "Suplier Tender Proyek Kontraktor",
        "Pengadaan Roster Developer Klaster Perumahan",
        "Grosir Roster Toko Bangunan & Ritase Truk",
        "Architectural Breeze Blocks",
        "Loster Beton Modern",
        "Pusat Pabrikasi Roster Plered Purwakarta"
    ],
    "sameAs": {!! json_encode(array_values($sameAs)) !!}
}
</script>

<!-- LocalBusiness & Manufacturer Schema (Local & International Exporter) -->
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": ["LocalBusiness", "Manufacturer"],
    "name": "IndoRoster — Pabrik Roster Beton Minimalis & Suplier Proyek Nasional",
    "image": "{{ $logoUrl }}",
    "url": "{{ $siteUrl }}",
    "telephone": "{{ $phone }}",
    "email": "{{ $email }}",
    "description": "Pusat produsen dan pabrik roster beton minimalis, loster arsitektural, dan bata expose di Plered, Purwakarta. Siap melayani pengadaan partai besar (ribuan hingga puluhan ribu pcs) untuk kontraktor proyek, developer perumahan, arsitek, dan toko bangunan dengan garansi 100% bebas pecah dan armada pengiriman ke Jabodetabek, Bandung, serta seluruh Indonesia.",
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
    "priceRange": "Rp / Grosir Proyek",
    "currenciesAccepted": "IDR, USD",
    "paymentAccepted": "Cash, Transfer Bank, QRIS, Invoice Proyek",
    "hasOfferCatalog": {
        "@@type": "OfferCatalog",
        "name": "Layanan Pengadaan Roster IndoRoster",
        "itemListElement": [
            {
                "@@type": "OfferCatalog",
                "name": "Pengadaan Partai Besar & Proyek Kontraktor",
                "description": "Suplai ribuan pcs roster beton cetak padat sudut siku 90° presisi, dokumen surat jalan & faktur resmi, garansi bebas pecah."
            },
            {
                "@@type": "OfferCatalog",
                "name": "Pengadaan Developer & Klaster Perumahan",
                "description": "Kontrak harga pabrik terkunci untuk puluhan hingga ratusan unit rumah perumahan dan gerbang utama cluster."
            },
            {
                "@@type": "OfferCatalog",
                "name": "Grosir Toko Bahan Bangunan (Ritase Truk)",
                "description": "Harga grosir per ritase truk dengan margin penjualan maksimal untuk toko depo material."
            }
        ]
    },
    "areaServed": [
        "Jakarta", "Bogor", "Depok", "Tangerang", "Bekasi", "Bandung", "Karawang", "Cianjur", "Cirebon", "Sukabumi", 
        "Jawa Barat", "DKI Jakarta", "Banten", "Jawa Tengah", "Jawa Timur", "Indonesia", "Worldwide"
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
