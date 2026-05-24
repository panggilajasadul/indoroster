<?php
    $siteName    = 'Indoroster';
    $siteUrl     = config('app.url');
    $logoUrl     = asset('assets/logo_indoroster_no_text.PNG');
    $address     = \App\Models\SiteSetting::getValue('factory_address', 'Plered, Purwakarta, Jawa Barat 41162');
    $phone       = \App\Models\SiteSetting::getValue('whatsapp_number', '+62 812-3456-7890');
    $email       = \App\Models\SiteSetting::getValue('contact_email', 'hello@indoroster.com');
    $instagram   = \App\Models\SiteSetting::getValue('instagram_url', '');
    $tiktok      = \App\Models\SiteSetting::getValue('tiktok_url', '');
    $youtube     = \App\Models\SiteSetting::getValue('youtube_url', '');

    $sameAs = array_filter([$instagram, $tiktok, $youtube]);
?>

<!-- Organization Schema -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "<?php echo e($siteName); ?>",
    "url": "<?php echo e($siteUrl); ?>",
    "logo": {
        "@type": "ImageObject",
        "url": "<?php echo e($logoUrl); ?>",
        "width": 200,
        "height": 200
    },
    "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "<?php echo e($phone); ?>",
        "contactType": "customer service",
        "areaServed": "ID",
        "availableLanguage": "Indonesian"
    },
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "Plered",
        "addressRegion": "Jawa Barat",
        "addressCountry": "ID",
        "streetAddress": "<?php echo e($address); ?>"
    },
    "sameAs": <?php echo json_encode(array_values($sameAs)); ?>

}
</script>

<!-- LocalBusiness Schema -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "Indoroster — Pabrik Roster Beton Minimalis",
    "image": "<?php echo e($logoUrl); ?>",
    "url": "<?php echo e($siteUrl); ?>",
    "telephone": "<?php echo e($phone); ?>",
    "email": "<?php echo e($email); ?>",
    "description": "Pabrik dan toko roster beton minimalis premium di Plered, Purwakarta. Produsen tangan pertama, harga pabrik, kualitas K-200. Melayani pengiriman ke seluruh Jabodetabek dan Indonesia.",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?php echo e($address); ?>",
        "addressLocality": "Plered",
        "addressRegion": "Purwakarta, Jawa Barat",
        "postalCode": "41162",
        "addressCountry": "ID"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": "-6.5631",
        "longitude": "107.4381"
    },
    "openingHoursSpecification": [
        {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],
            "opens": "08:00",
            "closes": "17:00"
        }
    ],
    "priceRange": "Rp",
    "currenciesAccepted": "IDR",
    "paymentAccepted": "Cash, Transfer Bank, QRIS",
    "areaServed": ["Jakarta", "Bogor", "Depok", "Tangerang", "Bekasi", "Bandung", "Indonesia"]
}
</script>

<!-- WebSite + SearchAction Schema -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "<?php echo e($siteName); ?>",
    "url": "<?php echo e($siteUrl); ?>",
    "potentialAction": {
        "@type": "SearchAction",
        "target": {
            "@type": "EntryPoint",
            "urlTemplate": "<?php echo e($siteUrl); ?>/katalog?search={search_term_string}"
        },
        "query-input": "required name=search_term_string"
    }
}
</script>
<?php /**PATH C:\xampp\htdocs\indoroster\resources\views/components/seo-schemas.blade.php ENDPATH**/ ?>