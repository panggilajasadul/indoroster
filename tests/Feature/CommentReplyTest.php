<?php

namespace Tests\Feature;

use App\Livewire\Gallery;
use App\Livewire\VideoInspiration;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Gallery as GalleryModel;
use App\Models\GalleryMedia;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CommentReplyTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private GalleryModel $galleryItem;

    protected function setUp(): void
    {
        parent::setUp();

        // Create user
        $this->user = User::factory()->create([
            'is_active' => true,
        ]);

        // Create category
        $category = Category::create([
            'name' => 'Roster Beton',
            'slug' => 'roster-beton',
            'is_active' => true,
        ]);

        // Create product (required for Gallery relations)
        $product = Product::create([
            'category_id' => $category->id,
            'name' => 'Roster Minimalis',
            'slug' => 'roster-minimalis',
            'description' => 'Deskripsi roster minimalis',
            'price' => 15000,
            'is_active' => true,
        ]);

        // Create gallery item
        $this->galleryItem = GalleryModel::create([
            'title' => 'Inspirasi Roster Minimalis',
            'category' => 'video-inspirasi',
            'product_id' => $product->id,
            'is_active' => true,
            'views_count' => 0,
        ]);
    }

    public function test_user_can_submit_comment_and_reply_in_video_inspiration(): void
    {
        // Create video media for this gallery item
        GalleryMedia::create([
            'gallery_id' => $this->galleryItem->id,
            'media_url' => 'https://example.com/video.mp4',
            'media_type' => 'video',
        ]);

        $this->actingAs($this->user);

        // Test Livewire component
        Livewire::test(VideoInspiration::class)
            ->set('activeVideoId', 'gallery-'.$this->galleryItem->id)
            ->set('newCommentText', 'Keren sekali!')
            ->call('submitComment');

        // Assert parent comment exists
        $parentComment = Comment::where('body', 'Keren sekali!')->first();
        $this->assertNotNull($parentComment);
        $this->assertNull($parentComment->parent_id);

        // Test replying
        Livewire::test(VideoInspiration::class)
            ->set('activeVideoId', 'gallery-'.$this->galleryItem->id)
            ->call('setReplyTo', $parentComment->id, $this->user->name)
            ->set('newCommentText', 'Setuju, keren banget!')
            ->call('submitComment');

        // Assert reply comment exists with correct parent_id
        $replyComment = Comment::where('body', 'Setuju, keren banget!')->first();
        $this->assertNotNull($replyComment);
        $this->assertEquals($parentComment->id, $replyComment->parent_id);

        // Verify counts and loading in component
        $component = Livewire::test(VideoInspiration::class);
        $videos = $component->get('videos');

        $this->assertNotEmpty($videos);
        $videoData = $videos[0];

        // Check comment count (should be 2, parent + reply)
        $this->assertEquals(2, $videoData['comments_count']);

        // Check nesting structure
        $comments = $videoData['comments'];
        $this->assertCount(1, $comments); // Only root comment at the top level of array
        $this->assertEquals('Keren sekali!', $comments[0]['body']);
        $this->assertCount(1, $comments[0]['replies']);
        $this->assertEquals('Setuju, keren banget!', $comments[0]['replies'][0]['body']);
    }

    public function test_user_can_submit_comment_and_reply_in_gallery(): void
    {
        // Change category to non-video for gallery
        $this->galleryItem->update(['category' => 'all']);

        // Create image media for this gallery item
        $media = GalleryMedia::create([
            'gallery_id' => $this->galleryItem->id,
            'media_url' => 'https://example.com/image.jpg',
            'media_type' => 'image',
        ]);

        $this->actingAs($this->user);

        $photoId = 'gallery-'.$this->galleryItem->id.'-'.$media->id;

        // Test Livewire component
        Livewire::test(Gallery::class)
            ->set('activePhotoId', $photoId)
            ->set('newCommentText', 'Foto teras yang indah.')
            ->call('submitComment');

        // Assert parent comment exists
        $parentComment = Comment::where('body', 'Foto teras yang indah.')->first();
        $this->assertNotNull($parentComment);
        $this->assertNull($parentComment->parent_id);

        // Test replying
        Livewire::test(Gallery::class)
            ->set('activePhotoId', $photoId)
            ->call('setReplyTo', $parentComment->id, $this->user->name)
            ->set('newCommentText', 'Lokasinya di mana ya?')
            ->call('submitComment');

        // Assert reply comment exists with correct parent_id
        $replyComment = Comment::where('body', 'Lokasinya di mana ya?')->first();
        $this->assertNotNull($replyComment);
        $this->assertEquals($parentComment->id, $replyComment->parent_id);

        // Verify structure in component
        $component = Livewire::test(Gallery::class);
        $photos = $component->get('photos');

        $this->assertNotEmpty($photos);
        $photoData = $photos[0];

        // Comments count should be 2 (parent + reply)
        $this->assertEquals(2, $photoData['comments_count']);

        // Check nested comments array
        $comments = $photoData['comments'];
        $this->assertCount(1, $comments); // Only parent comment
        $this->assertEquals('Foto teras yang indah.', $comments[0]['body']);
        $this->assertCount(1, $comments[0]['replies']);
        $this->assertEquals('Lokasinya di mana ya?', $comments[0]['replies'][0]['body']);
    }
}
