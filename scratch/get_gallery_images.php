<?php

use App\Models\Gallery;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$images = Gallery::with('media')
    ->active()
    ->where('category', '!=', 'video-inspirasi')
    ->latest()
    ->get();

$imagesToShow = [];
foreach ($images as $gallery) {
    foreach ($gallery->media as $media) {
        $imagesToShow[] = [
            'title' => $gallery->title,
            'url' => $media->media_url,
        ];
    }
}

file_put_contents(__DIR__.'/images.json', json_encode($imagesToShow, JSON_PRETTY_PRINT));
echo 'Done. Count: '.count($imagesToShow)."\n";
