<?php

$content = file_get_contents('c:/xampp/htdocs/indoroster/gallery.txt');

// More robust regex to find images within gallery-item divs
// This regex looks for the div first, then the img inside it
preg_match_all('/<div[^>]*class="gallery-item"[^>]*data-cat="([^"]+)"[^>]*>(.*?)<\/div>/s', $content, $matches, PREG_SET_ORDER);

$images = [];
foreach ($matches as $match) {
    $cat = $match[1];
    $innerContent = $match[2];
    
    if (preg_match('/<img[^>]+src="([^"]+)"[^>]*alt="([^"]*)"/i', $innerContent, $imgMatch)) {
        $images[] = [
            'url' => trim($imgMatch[1]),
            'category' => trim($cat),
            'alt' => trim($imgMatch[2]),
        ];
    }
}

echo "Found " . count($images) . " images.\n";

// Save to JSON
file_put_contents('c:/xampp/htdocs/indoroster/extracted_images.json', json_encode($images, JSON_PRETTY_PRINT));

// Let's also see some of the duplicates if any
$urls = array_column($images, 'url');
$counts = array_count_values($urls);
$duplicates = array_filter($counts, fn($c) => $c > 1);

if (!empty($duplicates)) {
    echo "Found " . count($duplicates) . " duplicate images.\n";
}
