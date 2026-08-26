<?php

use App\Models\Gallery;
use Illuminate\Contracts\Console\Kernel;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$cats = Gallery::select('category')->distinct()->pluck('category');
echo "Categories:\n";
foreach ($cats as $cat) {
    echo '- '.$cat."\n";
}
