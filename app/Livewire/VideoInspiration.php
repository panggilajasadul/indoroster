<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Gallery;
use App\Models\Like;
use App\Models\Page;
use App\Models\ProductReview;
use App\Notifications\CommentReplied;
use Illuminate\Support\Str;
use Livewire\Component;

class VideoInspiration extends Component
{
    public $videos = [];

    public $activeVideoId = null;

    public $initialActiveIndex = null;

    public $newCommentText = '';

    public $sortBy = 'latest';

    public $perPage = 12;

    public $replyToCommentId = null;

    public $replyToUserName = '';

    protected $queryString = [
        'sortBy' => ['except' => 'latest'],
    ];

    public $slug = null;

    public function mount($slug = null)
    {
        $this->slug = $slug;
        $this->loadVideos();

        $targetSlug = $slug ?: request()->query('slug');
        $videoId = request()->query('video');

        if ($targetSlug) {
            foreach ($this->videos as $idx => $v) {
                if (($v['slug'] ?? '') === $targetSlug || ($v['db_id'] ?? null) == $targetSlug || ($v['id'] ?? '') === $targetSlug) {
                    $this->initialActiveIndex = $idx;
                    $this->activeVideoId = $v['id'];
                    break;
                }
            }
        } elseif ($videoId) {
            foreach ($this->videos as $idx => $v) {
                if ($v['id'] == $videoId) {
                    $this->initialActiveIndex = $idx;
                    $this->activeVideoId = $videoId;
                    break;
                }
            }
        }
    }

    public function loadMore()
    {
        $this->perPage += 12;
    }

    public function setSortBy($sortBy)
    {
        $this->perPage = 12;
        $this->sortBy = $sortBy;
        $this->loadVideos();
    }

    public function incrementView($videoId)
    {
        if (str_starts_with($videoId, 'gallery-')) {
            $id = (int) str_replace('gallery-', '', $videoId);
            Gallery::where('id', $id)->increment('views_count');

            foreach ($this->videos as &$video) {
                if ($video['id'] === $videoId) {
                    $video['views_count'] = ($video['views_count'] ?? 0) + 1;
                    break;
                }
            }
        } elseif (str_starts_with($videoId, 'review-')) {
            $id = (int) str_replace('review-', '', $videoId);
            ProductReview::where('id', $id)->increment('views_count');

            foreach ($this->videos as &$video) {
                if ($video['id'] === $videoId) {
                    $video['views_count'] = ($video['views_count'] ?? 0) + 1;
                    break;
                }
            }
        }
    }

    public function loadVideos()
    {
        $userId = auth()->id();

        $adminVideos = Gallery::where('category', 'video-inspirasi')
            ->where('is_active', true)
            ->withCount('comments')
            ->with([
                'media',
                'product.media',
                'likes',
                'comments' => function ($q) {
                    $q->whereNull('parent_id')->with(['user', 'replies.user'])->latest();
                },
            ])
            ->get()
            ->map(function ($gallery) use ($userId) {
                $media = $gallery->media->first();
                $product = $gallery->product;

                return [
                    'id' => 'gallery-'.$gallery->id,
                    'url' => $media ? (str_starts_with($media->media_url, 'http') ? $media->media_url : asset('storage/'.$media->media_url)) : '',
                    'title' => $gallery->title,
                    'reviewer_name' => 'INDOROSTER OFFICIAL',
                    'reviewer_location' => 'Pabrik Purwakarta',
                    'rating' => null,
                    'type' => 'gallery',
                    'description' => $gallery->description ?: '',
                    'meta_title' => $gallery->meta_title ?: '',
                    'meta_description' => $gallery->meta_description ?: '',
                    'focus_keyword' => $gallery->focus_keyword ?: '',
                    'location' => $gallery->location ?: 'Pabrik Purwakarta',
                    'db_id' => $gallery->id,
                    'slug' => $gallery->slug ?: Str::slug($gallery->title),
                    'likes_count' => $gallery->likes->count(),
                    'comments_count' => $gallery->comments_count ?? 0,
                    'views_count' => $gallery->views_count ?? 0,
                    'created_at' => $gallery->created_at ? $gallery->created_at->toIso8601String() : now()->toIso8601String(),
                    'is_liked' => $userId ? $gallery->likes->contains('user_id', $userId) : false,
                    'comments' => $gallery->comments->map(function ($c) {
                        return [
                            'id' => $c->id,
                            'user_name' => $c->user?->name ?? 'Pengguna',
                            'body' => $c->body,
                            'created_at_human' => $c->created_at->diffForHumans(),
                            'replies' => $c->replies->map(function ($r) {
                                return [
                                    'id' => $r->id,
                                    'user_name' => $r->user?->name ?? 'Pengguna',
                                    'body' => $r->body,
                                    'created_at_human' => $r->created_at->diffForHumans(),
                                ];
                            })->toArray(),
                        ];
                    })->toArray(),
                    'product' => $product ? [
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'price' => $product->price,
                        'formatted_price' => $product->formatted_price_range,
                        'image' => $product->primary_image,
                    ] : null,
                ];
            })
            ->filter(fn ($v) => ! empty($v['url']))
            ->values();

        $reviewVideos = ProductReview::where('is_approved', true)
            ->whereNotNull('images')
            ->withCount('comments')
            ->with([
                'product',
                'likes',
                'comments' => function ($q) {
                    $q->whereNull('parent_id')->with(['user', 'replies.user'])->latest();
                },
            ])
            ->get()
            ->flatMap(function ($review) use ($userId) {
                $videos = [];
                foreach ($review->images as $path) {
                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    if (in_array($ext, ['mp4', 'mov', 'avi'])) {
                        $videos[] = [
                            'id' => 'review-'.$review->id,
                            'url' => asset('storage/'.$path),
                            'title' => 'Ulasan: '.$review->content,
                            'reviewer_name' => $review->masked_name,
                            'reviewer_location' => $review->reviewer_location,
                            'rating' => $review->rating,
                            'type' => 'review',
                            'db_id' => $review->id,
                            'slug' => 'review-'.$review->id,
                            'likes_count' => $review->likes->count(),
                            'comments_count' => $review->comments_count ?? 0,
                            'views_count' => $review->views_count ?? 0,
                            'created_at' => $review->created_at ? $review->created_at->toIso8601String() : now()->toIso8601String(),
                            'is_liked' => $userId ? $review->likes->contains('user_id', $userId) : false,
                            'comments' => $review->comments->map(function ($c) {
                                return [
                                    'id' => $c->id,
                                    'user_name' => $c->user?->name ?? 'Pengguna',
                                    'body' => $c->body,
                                    'created_at_human' => $c->created_at->diffForHumans(),
                                    'replies' => $c->replies->map(function ($r) {
                                        return [
                                            'id' => $r->id,
                                            'user_name' => $r->user?->name ?? 'Pengguna',
                                            'body' => $r->body,
                                            'created_at_human' => $r->created_at->diffForHumans(),
                                        ];
                                    })->toArray(),
                                ];
                            })->toArray(),
                            'product' => $review->product ? [
                                'name' => $review->product->name,
                                'slug' => $review->product->slug,
                                'price' => $review->product->price,
                                'formatted_price' => $review->product->formatted_price_range,
                                'image' => $review->product->primary_image,
                            ] : null,
                        ];
                    }
                }

                return $videos;
            });

        $collection = $adminVideos->concat($reviewVideos);

        if ($this->sortBy === 'oldest') {
            $collection = $collection->sortBy('created_at');
        } elseif ($this->sortBy === 'viral') {
            $collection = $collection->sortByDesc(function ($item) {
                return (($item['likes_count'] ?? 0) * 5) + (($item['comments_count'] ?? 0) * 10);
            });
        } elseif ($this->sortBy === 'views') {
            $collection = $collection->sortByDesc('views_count');
        } else {
            // Default: 'latest' (Upload terbaru selalu paling atas)
            $collection = $collection->sortByDesc('created_at');
        }

        $this->videos = $collection->values()->toArray();
    }

    public function toggleLike($videoId)
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if (str_starts_with($videoId, 'gallery-')) {
            $type = 'gallery';
            $id = (int) str_replace('gallery-', '', $videoId);
        } else {
            $type = 'review';
            $id = (int) str_replace('review-', '', $videoId);
        }

        $modelClass = $type === 'gallery' ? Gallery::class : ProductReview::class;
        $userId = auth()->id();

        $like = Like::where('user_id', $userId)
            ->where('likeable_type', $modelClass)
            ->where('likeable_id', $id)
            ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $userId,
                'likeable_type' => $modelClass,
                'likeable_id' => $id,
            ]);
        }

        $this->loadVideos();
    }

    public function submitComment()
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        if (empty(trim($this->newCommentText))) {
            return;
        }

        if (str_starts_with($this->activeVideoId, 'gallery-')) {
            $type = 'gallery';
            $id = (int) str_replace('gallery-', '', $this->activeVideoId);
        } else {
            $type = 'review';
            $id = (int) str_replace('review-', '', $this->activeVideoId);
        }

        $modelClass = $type === 'gallery' ? Gallery::class : ProductReview::class;

        $comment = Comment::create([
            'user_id' => auth()->id(),
            'parent_id' => $this->replyToCommentId,
            'commentable_type' => $modelClass,
            'commentable_id' => $id,
            'body' => trim($this->newCommentText),
        ]);

        if ($this->replyToCommentId) {
            $parentComment = Comment::find($this->replyToCommentId);
            if ($parentComment && $parentComment->user_id !== auth()->id()) {
                $parentComment->user->notify(new CommentReplied($comment, $parentComment));
            }
        }

        $this->newCommentText = '';
        $this->replyToCommentId = null;
        $this->replyToUserName = '';
        $this->loadVideos();
        $this->dispatch('comment-added');
    }

    public function setReplyTo($commentId, $userName)
    {
        $this->replyToCommentId = $commentId;
        $this->replyToUserName = $userName;
    }

    public function cancelReply()
    {
        $this->replyToCommentId = null;
        $this->replyToUserName = '';
    }

    public function render()
    {
        $displayedVideos = array_slice($this->videos, 0, $this->perPage);
        $hasMore = count($this->videos) > $this->perPage;

        $page = Page::where('slug', 'indoroster-video')->where('is_active', true)->first();
        $metaTitle = $page?->meta_title ?: 'Video Inspirasi Pasang Roster Beton & Review Proyek | IndoRoster';
        $metaDesc = $page?->meta_description ?: 'Tonton video dokumentasi proyek pemasangan roster beton minimalis, tutorial aplikasi dinding, dan ulasan langsung dari pembeli di seluruh Indonesia.';
        $canonical = route('video-inspiration');

        if ($this->initialActiveIndex !== null && isset($this->videos[$this->initialActiveIndex])) {
            $activeItem = $this->videos[$this->initialActiveIndex];
            $metaTitle = ($activeItem['meta_title'] ?? null) ?: $activeItem['title'].' | Video Inspirasi IndoRoster';
            $metaDesc = ($activeItem['meta_description'] ?? null) ?: ($activeItem['description'] ?: 'Tonton video inspirasi roster beton minimalis '.($activeItem['product']['name'] ?? '').' dari IndoRoster Pabrik Plered Purwakarta.');
            $canonical = url('/video-inspirasi/'.($activeItem['slug'] ?? $activeItem['id']));
        }

        return view('livewire.video-inspiration', [
            'page' => $page,
            'displayedVideos' => $displayedVideos,
            'hasMore' => $hasMore,
            'totalVideos' => count($this->videos),
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDesc,
            'canonicalOverride' => $canonical,
        ]);
    }
}
