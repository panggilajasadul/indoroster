<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$videos = \App\Models\Gallery::where('category', 'video-inspirasi')
    ->where('is_active', true)
    ->with(['media', 'product.media'])
    ->get()
    ->map(function($gallery) {
        $media = $gallery->media->first();
        $product = $gallery->product;
        return [
            'id' => $gallery->id,
            'url' => $media ? $media->media_url : '',
            'title' => $gallery->title,
            'product' => $product ? [
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'formatted_price' => $product->formatted_price_range,
                'image' => $product->primary_image,
            ] : null
        ];
    })
    ->filter(fn($v) => !empty($v['url']))
    ->values()
    ->toArray();

print_r($videos);
