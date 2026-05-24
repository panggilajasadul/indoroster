<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

try {
    echo "Running Product::viral() query...\n";
    $products = Product::viral()->take(10)->get();
    
    echo "Query executed successfully! Total results fetched: " . $products->count() . "\n\n";
    
    foreach ($products as $index => $product) {
        $rank = $index + 1;
        echo "#{$rank}: ID: {$product->id} | Name: {$product->name}\n";
        echo "   -> Total Sold: {$product->total_sold}\n";
        echo "   -> Approved Reviews Count: {$product->approved_reviews_count}\n";
        echo "   -> Price Range: {$product->formatted_price_range}\n";
        echo "   -> Category: " . ($product->category->name ?? 'None') . "\n";
        echo "   -> Has Video: " . ($product->has_video ? 'Yes' : 'No') . "\n";
        echo "--------------------------------------------------------\n";
    }
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
