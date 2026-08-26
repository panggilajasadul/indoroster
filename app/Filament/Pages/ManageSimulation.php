<?php

namespace App\Filament\Pages;

use App\Helpers\SimulationHelper;
use App\Models\Comment;
use App\Models\Gallery;
use App\Models\Like;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Attributes\On;

class ManageSimulation extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Simulasi Konten';

    protected static ?string $title = 'Simulasi Konten & Komentar';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.manage-simulation';

    // Statistics properties
    public int $totalReviews = 0;

    public int $seededReviews = 0;

    public int $totalComments = 0;

    public int $seededComments = 0;

    public int $seededUsers = 0;

    public int $totalLikes = 0;

    public int $seededLikes = 0;

    public int $videoComments = 0;

    public int $photoComments = 0;

    // Form data arrays
    public ?array $reviewData = [];

    public ?array $videoData = [];

    public ?array $photoData = [];

    public ?array $likeData = [];

    public ?array $specificLikeData = [];

    // Custom bulk simulation count properties
    public int $customReviewsCount = 50;

    public int $customVideoCommentsCount = 500;

    public int $customPhotoCommentsCount = 500;

    public function mount(): void
    {
        $this->refreshStats();
        $this->reviewForm->fill();
        $this->videoForm->fill();
        $this->photoForm->fill();
        $this->likeForm->fill();
        $this->specificLikeForm->fill();
    }

    #[On('refreshSimulationStats')]
    public function refreshStats(): void
    {
        $this->totalReviews = ProductReview::count();
        $this->seededReviews = ProductReview::where('is_seeded', true)->count();
        $this->totalComments = Comment::count();
        $this->seededComments = Comment::where('is_seeded', true)->count();
        $this->seededUsers = User::where('email', 'like', 'dummy_user_%@indoroster.com')->count();

        $this->totalLikes = Like::count();
        $this->seededLikes = Like::whereHas('user', function ($q) {
            $q->where('email', 'like', 'dummy_user_%@indoroster.com');
        })->count();

        // Count video comments
        $adminVideoIds = Gallery::where('category', 'video-inspirasi')->pluck('id')->toArray();
        $reviewVideoIds = ProductReview::whereNotNull('images')->get()->filter(function ($review) {
            if (! $review->images) {
                return false;
            }
            foreach ($review->images as $path) {
                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                if (in_array($ext, ['mp4', 'mov', 'avi'])) {
                    return true;
                }
            }

            return false;
        })->pluck('id')->toArray();

        $this->videoComments = Comment::where(function ($q) use ($adminVideoIds, $reviewVideoIds) {
            $q->where(fn ($sq) => $sq->where('commentable_type', Gallery::class)->whereIn('commentable_id', $adminVideoIds))
                ->orWhere(fn ($sq) => $sq->where('commentable_type', ProductReview::class)->whereIn('commentable_id', $reviewVideoIds));
        })->count();

        $this->photoComments = $this->totalComments - $this->videoComments;
    }

    protected function getForms(): array
    {
        return [
            'reviewForm',
            'videoForm',
            'photoForm',
            'likeForm',
            'specificLikeForm',
        ];
    }

    public function reviewForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_id')
                    ->label('Pilih Produk Baru / Lama')
                    ->options(Product::latest()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('rating')
                    ->label('Rating Bintang')
                    ->options([
                        0 => 'Acak (Random)',
                        1 => '1 Bintang',
                        2 => '2 Bintang',
                        3 => '3 Bintang',
                        4 => '4 Bintang',
                        5 => '5 Bintang',
                    ])
                    ->default(0)
                    ->required(),
                TextInput::make('quantity')
                    ->label('Jumlah Ulasan')
                    ->numeric()
                    ->default(5)
                    ->minValue(1)
                    ->required(),
            ])
            ->statePath('reviewData');
    }

    public function videoForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('video_id')
                    ->label('Pilih Video Galeri')
                    ->options(
                        Gallery::where('category', 'video-inspirasi')
                            ->where('is_active', true)
                            ->pluck('title', 'id')
                    )
                    ->searchable()
                    ->required(),
                TextInput::make('quantity')
                    ->label('Jumlah Komentar')
                    ->numeric()
                    ->default(10)
                    ->minValue(1)
                    ->required(),
            ])
            ->statePath('videoData');
    }

    public function photoForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('photo_id')
                    ->label('Pilih Foto Galeri')
                    ->options(
                        Gallery::where('category', '!=', 'video-inspirasi')
                            ->where('is_active', true)
                            ->pluck('title', 'id')
                    )
                    ->searchable()
                    ->required(),
                TextInput::make('quantity')
                    ->label('Jumlah Komentar')
                    ->numeric()
                    ->default(10)
                    ->minValue(1)
                    ->required(),
            ])
            ->statePath('photoData');
    }

    public function submitReviewForm(): void
    {
        $data = $this->reviewForm->getState();
        $productId = $data['product_id'];
        $rating = $data['rating'] == 0 ? null : $data['rating'];
        $quantity = $data['quantity'];

        $created = SimulationHelper::generateProductReviewsForProduct($productId, $rating, $quantity);
        $this->refreshStats();

        Notification::make()
            ->title('Ulasan Berhasil Ditambahkan')
            ->body("Berhasil membuat {$created} ulasan untuk produk tersebut.")
            ->success()
            ->send();

        $this->reviewForm->fill();
    }

    public function submitVideoForm(): void
    {
        $data = $this->videoForm->getState();
        $videoId = $data['video_id'];
        $quantity = $data['quantity'];

        $created = SimulationHelper::generateVideoCommentsForMedia(Gallery::class, $videoId, $quantity);
        $this->refreshStats();

        Notification::make()
            ->title('Komentar Video Berhasil Ditambahkan')
            ->body("Berhasil membuat {$created} komentar pada video tersebut.")
            ->success()
            ->send();

        $this->videoForm->fill();
    }

    public function submitPhotoForm(): void
    {
        $data = $this->photoForm->getState();
        $photoId = $data['photo_id'];
        $quantity = $data['quantity'];

        $created = SimulationHelper::generatePhotoCommentsForMedia(Gallery::class, $photoId, $quantity);
        $this->refreshStats();

        Notification::make()
            ->title('Komentar Foto Berhasil Ditambahkan')
            ->body("Berhasil membuat {$created} komentar pada foto tersebut.")
            ->success()
            ->send();

        $this->photoForm->fill();
    }

    public function likeForm(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('quantity')
                    ->label('Jumlah Like Acak')
                    ->numeric()
                    ->default(1000)
                    ->minValue(1)
                    ->required(),
            ])
            ->statePath('likeData');
    }

    public function submitLikeForm(): void
    {
        $data = $this->likeForm->getState();
        $quantity = $data['quantity'];

        $created = SimulationHelper::generateRandomLikes($quantity);
        $this->refreshStats();

        Notification::make()
            ->title('Like Acak Berhasil Dikirim')
            ->body("Berhasil mengirimkan {$created} like acak ke seluruh video dan foto.")
            ->success()
            ->send();

        $this->likeForm->fill();
    }

    public function specificLikeForm(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('media_type')
                    ->label('Tipe Media')
                    ->options([
                        'video' => 'Video Galeri',
                        'photo' => 'Foto Galeri',
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('gallery_id', null)),
                Select::make('gallery_id')
                    ->label('Pilih Video/Foto')
                    ->options(function (Get $get) {
                        $mediaType = $get('media_type');
                        if ($mediaType === 'video') {
                            return Gallery::where('category', 'video-inspirasi')
                                ->where('is_active', true)
                                ->pluck('title', 'id');
                        } elseif ($mediaType === 'photo') {
                            return Gallery::where('category', '!=', 'video-inspirasi')
                                ->where('is_active', true)
                                ->pluck('title', 'id');
                        }

                        return [];
                    })
                    ->searchable()
                    ->required()
                    ->disabled(fn (Get $get) => ! $get('media_type')),
                TextInput::make('quantity')
                    ->label('Jumlah Like')
                    ->numeric()
                    ->default(100)
                    ->minValue(1)
                    ->required(),
            ])
            ->statePath('specificLikeData');
    }

    public function submitSpecificLikeForm(): void
    {
        $data = $this->specificLikeForm->getState();
        $galleryId = $data['gallery_id'];
        $quantity = $data['quantity'];

        $created = SimulationHelper::generateLikesForMedia(Gallery::class, $galleryId, $quantity);
        $this->refreshStats();

        Notification::make()
            ->title('Like Spesifik Berhasil Ditambahkan')
            ->body("Berhasil mengirimkan {$created} like ke media tersebut.")
            ->success()
            ->send();

        $this->specificLikeForm->fill();
    }

    public function generateReviews(): void
    {
        $count = max(1, (int) $this->customReviewsCount);
        $created = SimulationHelper::generateProductReviews($count);
        $this->refreshStats();

        Notification::make()
            ->title('Ulasan Produk Berhasil Dibuat')
            ->body("Berhasil membuat {$created} ulasan produk simulasi.")
            ->success()
            ->send();
    }

    public function generateVideoComments(): void
    {
        $count = max(1, (int) $this->customVideoCommentsCount);
        $created = SimulationHelper::generateVideoComments($count);
        $this->refreshStats();

        Notification::make()
            ->title('Komentar Video Berhasil Dibuat')
            ->body("Berhasil membuat {$created} komentar video (TikTok-style) simulasi.")
            ->success()
            ->send();
    }

    public function generatePhotoComments(): void
    {
        $count = max(1, (int) $this->customPhotoCommentsCount);
        $created = SimulationHelper::generatePhotoComments($count);
        $this->refreshStats();

        Notification::make()
            ->title('Komentar Foto Berhasil Dibuat')
            ->body("Berhasil membuat {$created} komentar foto (Instagram-style) simulasi.")
            ->success()
            ->send();
    }

    public function clearSimulation(): void
    {
        $cleared = SimulationHelper::clearAllSimulation();
        $this->refreshStats();

        Notification::make()
            ->title('Data Simulasi Berhasil Dibersihkan')
            ->body("Berhasil menghapus {$cleared['reviews']} ulasan, {$cleared['comments']} komentar, dan {$cleared['users']} user dummy.")
            ->warning()
            ->send();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Comment::query()->latest())
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama User')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('commentable_type')
                    ->label('Tipe Media')
                    ->formatStateUsing(fn ($state) => $state === Gallery::class ? 'Galeri' : 'Ulasan Produk'),
                TextColumn::make('commentable_id')
                    ->label('ID Media')
                    ->sortable(),
                TextColumn::make('body')
                    ->label('Komentar')
                    ->limit(60)
                    ->searchable(),
                IconColumn::make('is_seeded')
                    ->label('Simulasi?')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                DeleteAction::make()
                    ->after(fn () => $this->refreshStats()),
            ]);
    }
}
