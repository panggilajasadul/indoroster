<?php

namespace Tests\Unit;

use App\Models\Article;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\GalleryMedia;
use App\Models\Product;
use App\Models\ProductMedia;
use App\Services\ImageOptimizationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageOptimizationTest extends TestCase
{
    use RefreshDatabase;

    protected ImageOptimizationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->service = new ImageOptimizationService;
    }

    /**
     * Test 1: Upload JPG normal -> JPG to WebP
     */
    public function test_1_upload_jpg_converts_to_webp(): void
    {
        $file = UploadedFile::fake()->image('test-product.jpg', 800, 600);

        $savedPath = $this->service->optimizeUploadedFile($file, 'product-media');

        $this->assertNotNull($savedPath);
        $this->assertStringEndsWith('.webp', $savedPath);
        $this->assertTrue(Storage::disk('public')->exists($savedPath));
    }

    /**
     * Test 2: Upload PNG -> PNG to WebP
     */
    public function test_2_upload_png_converts_to_webp(): void
    {
        $file = UploadedFile::fake()->image('test-logo.png', 500, 500);

        $savedPath = $this->service->optimizeUploadedFile($file, 'product-media');

        $this->assertNotNull($savedPath);
        $this->assertStringEndsWith('.webp', $savedPath);
        $this->assertTrue(Storage::disk('public')->exists($savedPath));
    }

    /**
     * Test 3: Upload WebP -> WebP to optimized WebP
     */
    public function test_3_upload_webp_optimizes_cleanly(): void
    {
        $file = UploadedFile::fake()->image('already.webp', 600, 400);

        $savedPath = $this->service->optimizeUploadedFile($file, 'product-media');

        $this->assertNotNull($savedPath);
        $this->assertStringEndsWith('.webp', $savedPath);
        $this->assertTrue(Storage::disk('public')->exists($savedPath));
    }

    /**
     * Test 4: Upload image 4000px+ -> resized to <= 1600px max
     */
    public function test_4_large_image_resized_to_max_1600px(): void
    {
        $file = UploadedFile::fake()->image('giant-facade.jpg', 4000, 3000);

        $savedPath = $this->service->optimizeUploadedFile($file, 'product-media');

        $this->assertNotNull($savedPath);
        $fullPath = Storage::disk('public')->path($savedPath);
        [$width, $height] = getimagesize($fullPath);

        $this->assertLessThanOrEqual(1600, $width);
        $this->assertLessThanOrEqual(1600, $height);
    }

    /**
     * Test 5: Upload small image -> not upscaled
     */
    public function test_5_small_image_is_not_upscaled(): void
    {
        $file = UploadedFile::fake()->image('small-icon.png', 300, 200);

        $savedPath = $this->service->optimizeUploadedFile($file, 'product-media');

        $this->assertNotNull($savedPath);
        $fullPath = Storage::disk('public')->path($savedPath);
        [$width, $height] = getimagesize($fullPath);

        $this->assertEquals(300, $width);
        $this->assertEquals(200, $height);
    }

    /**
     * Test 6: Upload non-image file -> rejected
     */
    public function test_6_reject_non_image_file(): void
    {
        $fakeDoc = UploadedFile::fake()->create('malicious.pdf', 100, 'application/pdf');

        $savedPath = $this->service->optimizeUploadedFile($fakeDoc, 'product-media');

        $this->assertNull($savedPath);
    }

    /**
     * Test 7: Upload file exceeding max size -> rejected
     */
    public function test_7_reject_oversized_file(): void
    {
        // 20MB exceeds default 10MB limit
        $oversized = UploadedFile::fake()->image('huge.jpg', 1000, 1000)->size(20000);

        $savedPath = $this->service->optimizeUploadedFile($oversized, 'product-media');

        $this->assertNull($savedPath);
    }

    /**
     * Test 8: Upload two images with same name -> no collision
     */
    public function test_8_duplicate_names_do_not_collide(): void
    {
        $file1 = UploadedFile::fake()->image('roster-bintang.jpg', 800, 800);
        $file2 = UploadedFile::fake()->image('roster-bintang.jpg', 800, 800);

        $path1 = $this->service->optimizeUploadedFile($file1, 'product-media');
        $path2 = $this->service->optimizeUploadedFile($file2, 'product-media');

        $this->assertNotEquals($path1, $path2);
        $this->assertTrue(Storage::disk('public')->exists($path1));
        $this->assertTrue(Storage::disk('public')->exists($path2));
    }

    /**
     * Test 9: Model saving auto-converts local media to WebP
     */
    public function test_9_product_media_model_auto_converts_on_save(): void
    {
        $category = Category::create([
            'name' => 'Roster Beton',
            'slug' => 'roster-beton',
            'is_active' => true,
        ]);

        // Place a test jpg in storage
        $testJpg = UploadedFile::fake()->image('sample-brick.jpg', 600, 600);
        $rawPath = $testJpg->store('product-media', 'public');

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Roster Bintang Presisi',
            'slug' => 'roster-bintang-presisi',
            'description' => 'Roster beton presisi abu batu murni',
            'price' => 15000,
            'stock' => 100,
            'is_active' => true,
        ]);

        $media = ProductMedia::create([
            'product_id' => $product->id,
            'media_url' => $rawPath,
            'media_type' => 'image',
            'is_primary' => true,
        ]);

        // Model saving hook should convert media_url to .webp
        $this->assertStringEndsWith('.webp', $media->media_url);
        $this->assertTrue(Storage::disk('public')->exists($media->media_url));
    }

    /**
     * Test 10: Frontend uses optimized WebP URL
     */
    public function test_10_frontend_product_model_returns_webp_url(): void
    {
        $category = Category::create([
            'name' => 'Roster Beton Lotus',
            'slug' => 'roster-beton-lotus',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Roster Minimalis Lotus',
            'slug' => 'roster-minimalis-lotus',
            'description' => 'Roster minimalis lotus presisi',
            'price' => 15000,
            'stock' => 100,
            'is_active' => true,
        ]);

        $testJpg = UploadedFile::fake()->image('lotus-raw.jpg', 600, 600);
        $rawPath = $testJpg->store('product-media', 'public');

        ProductMedia::create([
            'product_id' => $product->id,
            'media_url' => $rawPath,
            'media_type' => 'image',
            'is_primary' => true,
        ]);

        $primaryImage = $product->fresh()->primary_image;
        $this->assertNotNull($primaryImage);
        $this->assertStringEndsWith('.webp', $primaryImage);
    }

    /**
     * Test 11: GalleryMedia model auto converts on save
     */
    public function test_11_gallery_media_model_auto_converts_on_save(): void
    {
        $gallery = Gallery::create([
            'title' => 'Fasad Rumah Dago',
            'slug' => 'fasad-rumah-dago',
            'category' => 'fasad',
            'is_active' => true,
        ]);

        $testJpg = UploadedFile::fake()->image('dago-facade.jpg', 600, 600);
        $rawPath = $testJpg->store('gallery-media', 'public');

        $media = GalleryMedia::create([
            'gallery_id' => $gallery->id,
            'media_url' => $rawPath,
            'media_type' => 'image',
        ]);

        $this->assertStringEndsWith('.webp', $media->media_url);
        $this->assertTrue(Storage::disk('public')->exists($media->media_url));
    }

    /**
     * Test 12: Article model auto converts thumbnail on save
     */
    public function test_12_article_model_auto_converts_thumbnail_on_save(): void
    {
        $testJpg = UploadedFile::fake()->image('article-banner.jpg', 800, 500);
        $rawPath = $testJpg->store('articles', 'public');

        $article = Article::create([
            'title' => 'Panduan Memilih Roster Anti Tampias',
            'slug' => 'panduan-memilih-roster-anti-tampias',
            'thumbnail' => $rawPath,
            'content' => '<p>Tips memilih roster</p>',
            'is_published' => true,
        ]);

        $this->assertStringEndsWith('.webp', $article->thumbnail);
        $this->assertTrue(Storage::disk('public')->exists($article->thumbnail));
    }
}
