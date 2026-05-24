<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Str;

class CleanProductDescriptions extends Command
{
    protected $signature = 'products:clean-descriptions';
    protected $description = 'Remove manual generic descriptions and keep only the professional template';

    public function handle()
    {
        $products = Product::all();
        $count = 0;

        foreach ($products as $product) {
            $desc = $product->description;
            
            // Check if it contains the template
            if (Str::contains($desc, 'PANDUAN PEMESANAN')) {
                // Find where the template starts
                $pos = strpos($desc, '<h3>📝 PANDUAN PEMESANAN');
                if ($pos !== false) {
                    // Extract only the template part
                    $newDesc = substr($desc, $pos);
                    
                    if ($desc !== $newDesc) {
                        $product->description = $newDesc;
                        $product->save();
                        $count++;
                    }
                }
            }
        }

        $this->info("Successfully cleaned {$count} product descriptions.");
    }
}
