<?php

namespace App\Livewire;

use Livewire\Component;

class Gallery extends Component
{
    public $activeTab = 'all';
    public $title = 'Inspirasi <span class="text-terra-600">Proyek</span> Kami';
    public $description = 'Jelajahi koleksi mahakarya pemasangan roster beton minimalis yang telah menghiasi berbagai hunian dan ruang komersial.';
    public $photos = [];
    public $activePhotoId = null;
    public $initialActiveIndex = null;
    public $newCommentText = '';
    public $sortBy = 'viral';
    public $replyToCommentId = null;
    public $replyToUserName = '';

    protected $queryString = [
        'sortBy' => ['except' => 'viral'],
    ];

    public function mount($title = null, $description = null)
    {
        if ($title) $this->title = $title;
        if ($description) $this->description = $description;
        $this->loadPhotos();

        $photoId = request()->query('photo');
        if ($photoId) {
            foreach ($this->photos as $idx => $p) {
                if ($p['id'] == $photoId) {
                    $this->initialActiveIndex = $idx;
                    $this->activePhotoId = $photoId;
                    break;
                }
            }
        }
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->loadPhotos();
        $this->dispatch('galleryUpdated');
    }

    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
        $this->loadPhotos();
        $this->dispatch('galleryUpdated');
    }

    public function incrementView($photoId)
    {
        if (str_starts_with($photoId, 'gallery-')) {
            $parts = explode('-', $photoId);
            $id = (int)$parts[1];
            \App\Models\Gallery::where('id', $id)->increment('views_count');
            
            foreach ($this->photos as &$photo) {
                if ($photo['id'] === $photoId) {
                    $photo['views_count'] = ($photo['views_count'] ?? 0) + 1;
                    break;
                }
            }
        } elseif (str_starts_with($photoId, 'review-')) {
            $parts = explode('-', $photoId);
            $id = (int)$parts[1];
            \App\Models\ProductReview::where('id', $id)->increment('views_count');
            
            foreach ($this->photos as &$photo) {
                if ($photo['id'] === $photoId) {
                    $photo['views_count'] = ($photo['views_count'] ?? 0) + 1;
                    break;
                }
            }
        }
    }

    public function loadPhotos()
    {
        $userId = auth()->id();

        // 1. Fetch Admin Photos
        $adminPhotos = \App\Models\Gallery::withCount('comments')
            ->with([
                'media', 
                'product.category', 
                'likes', 
                'comments' => function($q) {
                    $q->whereNull('parent_id')->with(['user', 'replies.user'])->latest();
                }
            ])
            ->active()
            ->where('category', '!=', 'video-inspirasi')
            ->when($this->activeTab !== 'all', function($query) {
                return $query->where('category', $this->activeTab);
            })
            ->latest()
            ->get()
            ->flatMap(function($gallery) use ($userId) {
                $photos = [];
                $product = $gallery->product;
                foreach ($gallery->media as $media) {
                    if ($media->media_type === 'video') continue;
                    
                    $photos[] = [
                        'id' => 'gallery-' . $gallery->id . '-' . $media->id,
                        'url' => str_starts_with($media->media_url, 'http') ? $media->media_url : asset('storage/' . $media->media_url),
                        'title' => $gallery->title,
                        'location' => $gallery->location ?: 'Proyek Indoroster',
                        'reviewer_name' => 'INDOROSTER OFFICIAL',
                        'reviewer_location' => $gallery->location ?: 'Pabrik Purwakarta',
                        'rating' => null,
                        'type' => 'gallery',
                        'category' => $gallery->category ?: 'Proyek',
                        'db_id' => $gallery->id,
                        'likes_count' => $gallery->likes->count(),
                        'comments_count' => $gallery->comments_count ?? 0,
                        'views_count' => $gallery->views_count ?? 0,
                        'created_at' => $gallery->created_at ? $gallery->created_at->toIso8601String() : now()->toIso8601String(),
                        'is_liked' => $userId ? $gallery->likes->contains('user_id', $userId) : false,
                        'comments' => $gallery->comments->map(function($c) {
                            return [
                                'id' => $c->id,
                                'user_name' => $c->user->name,
                                'body' => $c->body,
                                'created_at_human' => $c->created_at->diffForHumans(),
                                'replies' => $c->replies->map(function($r) {
                                    return [
                                        'id' => $r->id,
                                        'user_name' => $r->user->name,
                                        'body' => $r->body,
                                        'created_at_human' => $r->created_at->diffForHumans()
                                    ];
                                })->toArray()
                            ];
                        })->toArray(),
                        'product' => $product ? [
                            'name' => $product->name,
                            'slug' => $product->slug,
                            'price' => $product->price,
                            'formatted_price' => $product->formatted_price_range,
                            'image' => $product->primary_image,
                        ] : null
                    ];
                }
                return $photos;
            });

        // 2. Fetch Review Photos
        $reviewPhotos = \App\Models\ProductReview::where('is_approved', true)
            ->whereNotNull('images')
            ->withCount('comments')
            ->with([
                'product.category', 
                'likes', 
                'comments' => function($q) {
                    $q->whereNull('parent_id')->with(['user', 'replies.user'])->latest();
                }
            ])
            ->get()
            ->flatMap(function($review) use ($userId) {
                $photos = [];
                $product = $review->product;
                
                // If filtering by category, filter by product category
                if ($this->activeTab !== 'all') {
                    $prodCat = $product->category->slug ?? '';
                    if ($prodCat !== $this->activeTab) {
                        return [];
                    }
                }
                
                foreach ($review->images as $index => $path) {
                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                    if (!in_array($ext, ['mp4', 'mov', 'avi'])) {
                        $photos[] = [
                            'id' => 'review-' . $review->id . '-' . $index,
                            'url' => asset('storage/' . $path),
                            'title' => 'Ulasan: ' . $review->content,
                            'location' => $review->reviewer_location ?: 'Indonesia',
                            'reviewer_name' => $review->masked_name,
                            'reviewer_location' => $review->reviewer_location ?: 'Indonesia',
                            'rating' => $review->rating,
                            'type' => 'review',
                            'category' => $product->category->name ?? 'Ulasan',
                            'db_id' => $review->id,
                            'likes_count' => $review->likes->count(),
                            'comments_count' => $review->comments_count ?? 0,
                            'views_count' => $review->views_count ?? 0,
                            'created_at' => $review->created_at ? $review->created_at->toIso8601String() : now()->toIso8601String(),
                            'is_liked' => $userId ? $review->likes->contains('user_id', $userId) : false,
                            'comments' => $review->comments->map(function($c) {
                                return [
                                    'id' => $c->id,
                                    'user_name' => $c->user->name,
                                    'body' => $c->body,
                                    'created_at_human' => $c->created_at->diffForHumans(),
                                    'replies' => $c->replies->map(function($r) {
                                        return [
                                            'id' => $r->id,
                                            'user_name' => $r->user->name,
                                            'body' => $r->body,
                                            'created_at_human' => $r->created_at->diffForHumans()
                                        ];
                                    })->toArray()
                                ];
                            })->toArray(),
                            'product' => $product ? [
                                'name' => $product->name,
                                'slug' => $product->slug,
                                'price' => $product->price,
                                'formatted_price' => $product->formatted_price_range,
                                'image' => $product->primary_image,
                            ] : null
                        ];
                    }
                }
                return $photos;
            });

        // Merge and assign
        $collection = $adminPhotos->concat($reviewPhotos);

        if ($this->sortBy === 'viral') {
            $collection = $collection->sortByDesc(function ($item) {
                return (($item['likes_count'] ?? 0) * 5) + (($item['comments_count'] ?? 0) * 10);
            });
        } else {
            $collection = $collection->sortByDesc('created_at');
        }

        $this->photos = $collection->values()->toArray();
    }

    public function toggleLike($photoId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (str_starts_with($photoId, 'gallery-')) {
            $type = 'gallery';
            $parts = explode('-', $photoId);
            $id = (int)$parts[1];
        } else {
            $type = 'review';
            $parts = explode('-', $photoId);
            $id = (int)$parts[1];
        }

        $modelClass = $type === 'gallery' ? \App\Models\Gallery::class : \App\Models\ProductReview::class;
        $userId = auth()->id();

        $like = \App\Models\Like::where('user_id', $userId)
            ->where('likeable_type', $modelClass)
            ->where('likeable_id', $id)
            ->first();

        if ($like) {
            $like->delete();
        } else {
            \App\Models\Like::create([
                'user_id' => $userId,
                'likeable_type' => $modelClass,
                'likeable_id' => $id,
            ]);
        }

        $this->loadPhotos();
    }

    public function submitComment()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (empty(trim($this->newCommentText))) {
            return;
        }

        if (str_starts_with($this->activePhotoId, 'gallery-')) {
            $type = 'gallery';
            $parts = explode('-', $this->activePhotoId);
            $id = (int)$parts[1];
        } else {
            $type = 'review';
            $parts = explode('-', $this->activePhotoId);
            $id = (int)$parts[1];
        }

        $modelClass = $type === 'gallery' ? \App\Models\Gallery::class : \App\Models\ProductReview::class;

        $comment = \App\Models\Comment::create([
            'user_id' => auth()->id(),
            'parent_id' => $this->replyToCommentId,
            'commentable_type' => $modelClass,
            'commentable_id' => $id,
            'body' => trim($this->newCommentText),
        ]);

        if ($this->replyToCommentId) {
            $parentComment = \App\Models\Comment::find($this->replyToCommentId);
            if ($parentComment && $parentComment->user_id !== auth()->id()) {
                $parentComment->user->notify(new \App\Notifications\CommentReplied($comment, $parentComment));
            }
        }

        $this->newCommentText = '';
        $this->replyToCommentId = null;
        $this->replyToUserName = '';
        $this->loadPhotos();
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
        // Get all unique categories for the tabs
        $categories = \App\Models\Gallery::active()
            ->where('category', '!=', 'video-inspirasi')
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->toArray();

        return view('livewire.gallery', [
            'availableCategories' => $categories
        ])->layout('components.layouts.app', [
            'title'       => 'Galeri Proyek Roster Beton | INDOROSTER — Inspirasi Desain Rumah',
            'description' => 'Temukan inspirasi desain rumah modern dengan roster beton minimalis INDOROSTER. Lihat ratusan foto proyek nyata pemasangan roster di berbagai hunian dan gedung komersial.',
        ]);
    }
}
