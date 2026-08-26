<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SeoController extends Controller
{
    /**
     * Mengambil data produk lengkap beserta status medianya untuk dianalisis oleh Python.
     */
    public function getProductData($id)
    {
        $product = Product::with(['category', 'media'])->find($id);

        if (! $product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], 404);
        }

        // Siapkan list media
        $mediaList = $product->media->map(function ($m) {
            return [
                'id' => $m->id,
                'media_url' => $m->media_url,
                'media_type' => $m->media_type,
                'alt_text' => $m->alt_text,
                'is_primary' => $m->is_primary,
            ];
        });

        // Deteksi secara programatik kelengkapan skema
        $hasShoppingFields = ! empty($product->sku) && ! empty($product->dimensions) && ! empty($product->weight);
        $hasVideo = $product->media->contains('media_type', 'video');

        return response()->json([
            'status' => 'success',
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'description' => $product->description,
                'short_description' => $product->short_description,
                'material' => $product->material,
                'dimensions' => $product->dimensions,
                'weight' => $product->weight,
                'price' => $product->price,
                'original_price' => $product->original_price,
                'focus_keyword' => $product->focus_keyword,
                'secondary_keywords' => $product->secondary_keywords,
                'seo_h1' => $product->seo_h1,
                'meta_title' => $product->meta_title,
                'meta_description' => $product->meta_description,
                'og_title' => $product->og_title,
                'og_description' => $product->og_description,
                'is_active' => $product->is_active,
                'view_count' => $product->view_count ?? 0,
                'has_recommended_products' => true, // Detail page has take(4) recommendedProducts
            ],
            'category_name' => $product->category?->name,
            'media' => $mediaList,
            'schemas' => [
                'has_product_schema' => true, // Rendered via component x-product-schema
                'has_breadcrumb_schema' => true, // Rendered via component x-product-schema
                'has_video_schema' => $hasVideo, // If has video, VideoObject is recommended
                'has_shopping_fields' => $hasShoppingFields,
            ],
        ]);
    }

    /**
     * Menyimpan hasil analisis SEO produk dari Python engine.
     */
    public function saveProductResults(Request $request, $id)
    {
        $product = Product::find($id);

        if (! $product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found',
            ], 404);
        }

        $validated = $request->validate([
            'seo_score' => 'nullable|integer|min:0|max:100',
            'opportunity_score' => 'nullable|integer|min:0|max:100',
            'seo_h1' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'seo_issues' => 'nullable|array',
            'alt_texts' => 'nullable|array',
            'alt_texts.*.id' => 'required|integer',
            'alt_texts.*.alt_text' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Update fields produk
            $product->update([
                'seo_score' => $validated['seo_score'] ?? $product->seo_score,
                'opportunity_score' => $validated['opportunity_score'] ?? $product->opportunity_score,
                'seo_h1' => $validated['seo_h1'] ?? $product->seo_h1,
                'meta_title' => $validated['meta_title'] ?? $product->meta_title,
                'meta_description' => $validated['meta_description'] ?? $product->meta_description,
                'og_title' => $validated['og_title'] ?? $product->og_title,
                'og_description' => $validated['og_description'] ?? $product->og_description,
                'seo_issues' => $validated['seo_issues'] ?? $product->seo_issues,
                'seo_last_analyzed' => now(),
            ]);

            // Update Alt Text Gambar Produk
            if (! empty($validated['alt_texts'])) {
                foreach ($validated['alt_texts'] as $altItem) {
                    ProductMedia::where('id', $altItem['id'])
                        ->where('product_id', $product->id)
                        ->update(['alt_text' => $altItem['alt_text']]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'SEO results saved successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to save SEO results for product ID {$id}: ".$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save results: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Batch update Alt Text Gambar Produk.
     */
    public function saveImageAlts(Request $request)
    {
        $validated = $request->validate([
            'alt_texts' => 'required|array',
            'alt_texts.*.id' => 'required|integer',
            'alt_texts.*.alt_text' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['alt_texts'] as $altItem) {
                ProductMedia::where('id', $altItem['id'])->update([
                    'alt_text' => $altItem['alt_text'],
                ]);
            }
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Batch Alt Texts updated successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to save batch Image ALTs: '.$e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save batch Image ALTs: '.$e->getMessage(),
            ], 500);
        }
    }
}
