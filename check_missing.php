<?php

$content = file_get_contents('c:/xampp/htdocs/indoroster/gallery.txt');

// Find all image URLs
preg_match_all('/https:\/\/res\.cloudinary\.com\/indoroster\/image\/upload\/[^\s"\'>]+/i', $content, $allUrls);
$allUrls = array_unique($allUrls[0]);

// Find captured URLs from the previous script
$extracted = json_decode(file_get_contents('c:/xampp/htdocs/indoroster/extracted_images.json'), true);
$capturedUrls = array_column($extracted, 'url');

$missing = array_diff($allUrls, $capturedUrls);

echo 'Total unique Cloudinary URLs found: '.count($allUrls)."\n";
echo 'Captured URLs: '.count($capturedUrls)."\n";
echo 'Missing URLs: '.count($missing)."\n";

foreach ($missing as $url) {
    echo "- $url\n";
}
