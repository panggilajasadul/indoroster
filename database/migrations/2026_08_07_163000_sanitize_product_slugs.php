<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Product;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Sanitize Product Slugs
        $products = Product::all();
        foreach ($products as $product) {
            if ($product->slug) {
                $cleanSlug = Str::slug($product->slug);
                if ($cleanSlug !== $product->slug) {
                    $product->slug = $cleanSlug;
                    $product->save();
                }
            }
        }

        // 2. Sanitize Category Slugs
        $categories = Category::all();
        foreach ($categories as $category) {
            if ($category->slug) {
                $cleanSlug = Str::slug($category->slug);
                if ($cleanSlug !== $category->slug) {
                    $category->slug = $cleanSlug;
                    $category->save();
                }
            }
        }

        // 3. Sanitize Page Slugs
        $pages = Page::all();
        foreach ($pages as $page) {
            if ($page->slug) {
                $cleanSlug = Str::slug($page->slug);
                if ($cleanSlug !== $page->slug) {
                    $page->slug = $cleanSlug;
                    $page->save();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse operation needed for sanitization
    }
};
