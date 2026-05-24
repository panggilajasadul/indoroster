<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$cats = \App\Models\Gallery::select('category')->distinct()->pluck('category');
echo "Categories:\n";
foreach($cats as $cat) {
    echo "- " . $cat . "\n";
}
