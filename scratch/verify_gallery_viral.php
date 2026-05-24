<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Gallery;
use App\Models\ProductReview;

echo "--- Testing Gallery View Increment ---\n";
$gallery = Gallery::first();
if ($gallery) {
    $before = $gallery->views_count;
    echo "Gallery '{$gallery->title}' views before: {$before}\n";
    $gallery->increment('views_count');
    $gallery->refresh();
    $after = $gallery->views_count;
    echo "Gallery '{$gallery->title}' views after: {$after}\n";
    if ($after === $before + 1) {
        echo "SUCCESS: Increment works!\n";
    } else {
        echo "FAILED: Increment failed.\n";
    }
} else {
    echo "No gallery items found.\n";
}

echo "\n--- Testing ProductReview View Increment ---\n";
$review = ProductReview::first();
if ($review) {
    $before = $review->views_count;
    echo "ProductReview ID {$review->id} views before: {$before}\n";
    $review->increment('views_count');
    $review->refresh();
    $after = $review->views_count;
    echo "ProductReview ID {$review->id} views after: {$after}\n";
    if ($after === $before + 1) {
        echo "SUCCESS: Increment works!\n";
    } else {
        echo "FAILED: Increment failed.\n";
    }
} else {
    echo "No product reviews found.\n";
}

echo "\n--- Testing Sorting Logic ---\n";
$sortBy = 'viral';
$adminVideos = Gallery::where('category', 'video-inspirasi')
    ->where('is_active', true)
    ->with(['likes', 'comments'])
    ->get()
    ->map(function($gallery) {
        return [
            'id' => 'gallery-' . $gallery->id,
            'title' => $gallery->title,
            'likes_count' => $gallery->likes->count(),
            'comments_count' => $gallery->comments->count(),
            'views_count' => $gallery->views_count ?? 0,
        ];
    });

$reviewVideos = ProductReview::where('is_approved', true)
    ->whereNotNull('images')
    ->with(['likes', 'comments'])
    ->get()
    ->flatMap(function($review) {
        $videos = [];
        foreach ($review->images as $path) {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            if (in_array($ext, ['mp4', 'mov', 'avi'])) {
                $videos[] = [
                    'id' => 'review-' . $review->id,
                    'title' => 'Ulasan: ' . $review->content,
                    'likes_count' => $review->likes->count(),
                    'comments_count' => $review->comments->count(),
                    'views_count' => $review->views_count ?? 0,
                ];
            }
        }
        return $videos;
    });

$collection = $adminVideos->concat($reviewVideos);

$sorted = $collection->sortByDesc(function ($item) {
    $score = ($item['views_count'] ?? 0) + (($item['likes_count'] ?? 0) * 5) + (($item['comments_count'] ?? 0) * 10);
    echo "Item: {$item['id']} | Title: {$item['title']} | Views: {$item['views_count']} | Likes: {$item['likes_count']} | Comments: {$item['comments_count']} | Score: {$score}\n";
    return $score;
});

echo "\n--- Top 3 Sorted Viral Media ---\n";
$i = 1;
foreach ($sorted->take(3) as $item) {
    $score = ($item['views_count'] ?? 0) + (($item['likes_count'] ?? 0) * 5) + (($item['comments_count'] ?? 0) * 10);
    echo "{$i}. {$item['title']} (Score: {$score})\n";
    $i++;
}
