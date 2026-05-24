<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryResource\Pages;
use App\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;
    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Konten';
    protected static ?string $navigationLabel = 'Galeri';
    protected static ?string $modelLabel = 'Galeri';
    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('category', '!=', 'video-inspirasi');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Info Album')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul Album')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('location')
                        ->label('Lokasi Proyek')
                        ->placeholder('Jakarta Selatan'),
                    Forms\Components\Select::make('category')
                        ->label('Kategori')
                        ->options([
                            'fasad' => 'Fasad',
                            'pagar' => 'Pagar',
                            'interior' => 'Interior',
                            'ruang-tamu' => 'Ruang Tamu',
                            'teras' => 'Teras',
                            'taman' => 'Taman',
                            'kamar-mandi' => 'Kamar Mandi',
                            'dapur' => 'Dapur',
                            'kolam-renang' => 'Kolam Renang',
                            'lainnya' => 'Lainnya',
                        ])
                        ->required()
                        ->searchable(),
                    Forms\Components\Select::make('product_id')
                        ->relationship('product', 'name')
                        ->searchable()
                        ->preload()
                        ->label('Produk Terkait')
                        ->placeholder('Pilih produk (opsional)')
                        ->nullable(),
                    Forms\Components\TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0),
                    Forms\Components\TextInput::make('views_count')
                        ->label('Jumlah Tayangan (Views)')
                        ->numeric()
                        ->default(0)
                        ->required(),
                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(3)
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ])->columns(2),
            Forms\Components\Section::make('Media (Foto & Video)')
                ->schema([
                    Forms\Components\Repeater::make('media')
                        ->relationship()
                        ->schema([
                            Forms\Components\Group::make([
                                Forms\Components\Select::make('media_source')
                                    ->label('Sumber Media')
                                    ->options([
                                        'upload' => 'Upload File Lokal',
                                        'url' => 'Link URL External / YouTube',
                                    ])
                                    ->default('upload')
                                    ->live()
                                    ->afterStateHydrated(function (Forms\Components\Select $component, $state, $record) {
                                        if ($record && str_starts_with($record->media_url ?? '', 'http')) {
                                            $component->state('url');
                                        } else {
                                            $component->state('upload');
                                        }
                                    }),
                                Forms\Components\FileUpload::make('media_image_upload')
                                    ->label('Upload Gambar (Auto Kompres WebP)')
                                    ->directory('gallery')
                                    ->image()
                                    ->maxSize(10240) // 10MB
                                    ->live()
                                    ->required(fn(Forms\Get $get) => $get('media_source') === 'upload' && $get('media_type') === 'image')
                                    ->hidden(fn(Forms\Get $get) => $get('media_source') !== 'upload' || $get('media_type') !== 'image')
                                    ->afterStateHydrated(function (Forms\Components\FileUpload $component, $state, $record) {
                                        if ($record && !str_starts_with($record->media_url ?? '', 'http') && $record->media_type === 'image') {
                                            $component->state([$record->media_url]);
                                        }
                                    }),
                                Forms\Components\FileUpload::make('media_video_upload')
                                    ->label('Upload Video')
                                    ->directory('gallery')
                                    ->acceptedFileTypes(['video/*'])
                                    ->maxSize(102400) // 100MB
                                    ->live()
                                    ->required(fn(Forms\Get $get) => $get('media_source') === 'upload' && $get('media_type') === 'video')
                                    ->hidden(fn(Forms\Get $get) => $get('media_source') !== 'upload' || $get('media_type') !== 'video')
                                    ->afterStateHydrated(function (Forms\Components\FileUpload $component, $state, $record) {
                                        if ($record && !str_starts_with($record->media_url ?? '', 'http') && $record->media_type === 'video') {
                                            $component->state([$record->media_url]);
                                        }
                                    }),
                                Forms\Components\TextInput::make('media_url_link')
                                    ->label('Link URL External / YouTube')
                                    ->placeholder('https://contoh.com/gambar.jpg atau https://youtube.com/watch?v=...')
                                    ->live()
                                    ->required(fn(Forms\Get $get) => $get('media_source') === 'url')
                                    ->hidden(fn(Forms\Get $get) => $get('media_source') !== 'url')
                                    ->afterStateHydrated(function (Forms\Components\TextInput $component, $state, $record) {
                                        if ($record && str_starts_with($record->media_url ?? '', 'http')) {
                                            $component->state($record->media_url);
                                        }
                                    }),
                                Forms\Components\Select::make('media_type')
                                    ->label('Tipe')
                                    ->options([
                                        'image' => '🖼️ Gambar',
                                        'video' => '🎬 Video',
                                    ])
                                    ->default('image')
                                    ->live()
                                    ->required(),
                            ])->columnSpan(2),

                            Forms\Components\Section::make('Pratinjau')
                                ->schema([
                                    Forms\Components\Placeholder::make('media_preview')
                                        ->label('')
                                        ->content(function (Forms\Get $get) {
                                            $source = $get('media_source');
                                            $type = $get('media_type');
                                            $url = '';
                                            if ($source === 'upload') {
                                                $url = $type === 'image' ? $get('media_image_upload') : $get('media_video_upload');
                                                $url = is_array($url) ? (array_values($url)[0] ?? '') : $url;
                                            } else {
                                                $url = $get('media_url_link');
                                            }

                                            if (!$url || !is_string($url)) return 'Belum ada media dipilih';

                                            $displayUrl = $source === 'upload' 
                                                ? (str_starts_with($url, 'gallery') ? asset('storage/' . $url) : $url)
                                                : $url;

                                            if ($type === 'image') {
                                                return new HtmlString('<div class="flex justify-center bg-gray-100 rounded-lg p-2"><img src="'.$displayUrl.'" style="max-height: 150px; width: auto;" class="rounded shadow-sm"></div>');
                                            } else {
                                                // Check for YouTube
                                                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $displayUrl, $matches)) {
                                                    $youtubeId = $matches[1];
                                                    return new HtmlString('<div class="aspect-video w-full"><iframe src="https://www.youtube.com/embed/'.$youtubeId.'" class="w-full h-full rounded shadow-sm" frameborder="0" allowfullscreen></iframe></div>');
                                                }
                                                return new HtmlString('<div class="flex justify-center bg-gray-100 rounded-lg p-2"><video src="'.$displayUrl.'" controls style="max-height: 150px; width: auto;" class="rounded shadow-sm"></video></div>');
                                            }
                                        })
                                ])->columnSpan(1),

                            Forms\Components\TextInput::make('caption')
                                ->label('Alt Text (SEO)')
                                ->maxLength(255)
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('sort_order')
                                ->label('Urutan')
                                ->numeric()
                                ->default(0)
                                ->columnSpan(1),
                        ])
                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                            $source = $data['media_source'] ?? 'upload';
                            $type = $data['media_type'] ?? 'image';
                            if ($source === 'upload') {
                                $uploadValue = $type === 'image' ? ($data['media_image_upload'] ?? '') : ($data['media_video_upload'] ?? '');
                                $data['media_url'] = is_array($uploadValue) ? (array_values($uploadValue)[0] ?? '') : $uploadValue;
                            } else {
                                $data['media_url'] = $data['media_url_link'] ?? '';
                            }
                            unset($data['media_source'], $data['media_image_upload'], $data['media_video_upload'], $data['media_url_link']);
                            return $data;
                        })
                        ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                            $source = $data['media_source'] ?? 'upload';
                            $type = $data['media_type'] ?? 'image';
                            if ($source === 'upload') {
                                $uploadValue = $type === 'image' ? ($data['media_image_upload'] ?? '') : ($data['media_video_upload'] ?? '');
                                $data['media_url'] = is_array($uploadValue) ? (array_values($uploadValue)[0] ?? '') : $uploadValue;
                            } else {
                                $data['media_url'] = $data['media_url_link'] ?? '';
                            }
                            unset($data['media_source'], $data['media_image_upload'], $data['media_video_upload'], $data['media_url_link']);
                            return $data;
                        })
                        ->columns(3)
                        ->defaultItems(1)
                        ->addActionLabel('+ Tambah Media')
                        ->collapsible(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('category')->label('Kategori')->badge()->color('warning'),
                Tables\Columns\TextColumn::make('location')->label('Lokasi'),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Produk Terkait')
                    ->placeholder('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('media_count')->label('Media')->counts('media'),
                Tables\Columns\TextColumn::make('views_count')
                    ->label('Views')
                    ->sortable()
                    ->numeric(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalWidth('5xl'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageGalleries::route('/'),
        ];
    }
}
