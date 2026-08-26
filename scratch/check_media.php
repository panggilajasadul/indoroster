<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use Illuminate\Contracts\Console\Kernel;

// Check Roster Petir media
$p = Product::where('slug', 'roster-petir')->with('media')->first();
if ($p) {
    echo 'Product: '.$p->name."\n";
    echo 'Media Count: '.$p->media->count()."\n";
    foreach ($p->media as $m) {
        echo '  Type: '.$m->media_type.' | URL: '.$m->media_url.' | Primary: '.($m->is_primary ? 'YES' : 'NO')."\n";
        echo '  Formatted URL: '.$m->formatted_url."\n";
    }
    echo "\nPrimary Media: \n";
    $pm = $p->primary_media;
    if ($pm) {
        echo '  Type: '.$pm->media_type.' | URL: '.$pm->media_url."\n";
        echo '  Formatted: '.$pm->formatted_url."\n";
    } else {
        echo "  NULL\n";
    }
}

// Check featured products
echo "\n\n=== FEATURED PRODUCTS ===\n";
$featured = Product::where('is_featured', true)->with('media')->get();
foreach ($featured as $fp) {
    $pm = $fp->primary_media;
    echo $fp->name.': ';
    if ($pm) {
        echo 'Type='.$pm->media_type.' URL='.substr($pm->media_url, 0, 60)."\n";
    } else {
        echo "NO MEDIA\n";
    }
}
