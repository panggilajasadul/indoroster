<?php

use App\Models\GalleryMedia;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$medias = GalleryMedia::take(10)->get();
foreach ($medias as $m) {
    echo "ID: {$m->id}, URL: {$m->media_url}, Formatted: {$m->formatted_url}, Exists: ".(file_exists(public_path(str_replace(url('/'), '', $m->formatted_url))) ? 'YES' : 'NO').PHP_EOL;
}
