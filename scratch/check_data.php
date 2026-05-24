<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

$p = Product::where('name', 'like', '%Nako%')->first();
if ($p) {
    echo "Product: " . $p->name . "\n";
    echo "Base Price: " . $p->price . "\n";
    foreach ($p->variants as $v) {
        echo "Variant: " . $v->name . " | Adj: " . $v->price_adjustment . " | Stock: " . $v->stock . " | Active: " . ($v->is_active ? 'Yes' : 'No') . "\n";
    }
} else {
    echo "Product not found.\n";
}
