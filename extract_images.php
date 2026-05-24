<?php

$content = file_get_contents('c:/xampp/htdocs/indoroster/gallery.txt');

preg_match_all('/<div class="gallery-item" data-cat="([^"]+)">\s*(?:<!--.*?-->\s*)?<img src="([^"]+)" alt="([^"]+)"/s', $content, $matches, PREG_SET_ORDER);

$images = [];
foreach ($matches as $match) {
    $images[] = [
        'url' => $match[2],
        'category' => $match[1],
        'alt' => $match[3],
    ];
}

file_put_contents('c:/xampp/htdocs/indoroster/extracted_images.json', json_encode($images, JSON_PRETTY_PRINT));
echo "Extracted " . count($images) . " images.\n";
