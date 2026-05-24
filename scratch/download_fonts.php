<?php

$fonts = [
    'inter' => ['300', '400', '500', '600', '700'],
    'outfit' => ['400', '500', '600', '700', '800']
];

$outputDir = __DIR__ . '/../public/fonts';
if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

foreach ($fonts as $fontId => $variants) {
    echo "Fetching metadata for $fontId...\n";
    $apiUrl = "https://gwfh.caltech.ch/api/fonts/$fontId?subsets=latin";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $response = curl_exec($ch);
    if ($response === false) {
        echo "Curl error: " . curl_error($ch) . "\n";
    }
    curl_close($ch);
    
    if (!$response) {
        echo "Failed to fetch metadata for $fontId\n";
        continue;
    }
    
    $data = json_decode($response, true);
    if (!isset($data['variants'])) {
        echo "Invalid response for $fontId\n";
        continue;
    }
    
    foreach ($data['variants'] as $variant) {
        $id = $variant['id']; // e.g. '400'
        if (in_array($id, $variants)) {
            if (isset($variant['woff2'])) {
                $url = $variant['woff2'];
                $filename = $fontId . '-' . $id . '.woff2';
                $destPath = $outputDir . '/' . $filename;
                
                echo "Downloading $filename from $url...\n";
                
                $ch = curl_init($url);
                $fp = fopen($destPath, 'wb');
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_exec($ch);
                curl_close($ch);
                fclose($fp);
                
                if (file_exists($destPath) && filesize($destPath) > 0) {
                    echo "Successfully downloaded $filename (" . round(filesize($destPath) / 1024, 2) . " KB)\n";
                } else {
                    echo "Failed to download $filename\n";
                }
            }
        }
    }
}

echo "All done!\n";
