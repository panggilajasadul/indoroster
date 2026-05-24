<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoInspirationResource\Pages;
use App\Filament\Resources\VideoInspirationResource\RelationManagers;
use App\Models\VideoInspiration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

class VideoInspirationResource extends Resource
{
    protected static ?string $model = \App\Models\Gallery::class;
    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationGroup = 'Konten';
    protected static ?string $navigationLabel = 'Indoroster Video';
    protected static ?string $modelLabel = 'Indoroster Video';
    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('category', 'video-inspirasi');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Info Video')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul Video')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', \Illuminate\Support\Str::slug($state) . '-' . \Illuminate\Support\Str::random(5))),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true),
                    Forms\Components\Select::make('product_id')
                        ->relationship('product', 'name')
                        ->searchable()
                        ->preload()
                        ->label('Produk Terkait')
                        ->placeholder('Pilih produk (opsional)')
                        ->nullable(),
                    Forms\Components\Hidden::make('category')
                        ->default('video-inspirasi'),
                    Forms\Components\TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0),
                    Forms\Components\TextInput::make('views_count')
                        ->label('Jumlah Tayangan (Views)')
                        ->numeric()
                        ->default(0)
                        ->required(),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ])->columns(2),
            Forms\Components\Section::make('Video')
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
                                        if ($record && str_starts_with($record->media_url, 'http')) {
                                            $component->state('url');
                                        } else {
                                            $component->state('upload');
                                        }
                                    }),
                                Forms\Components\FileUpload::make('media_url_upload')
                                    ->label('Upload File (Video)')
                                    ->directory('video-inspirasi')
                                    ->acceptedFileTypes(['video/*'])
                                    ->maxSize(102400) // 100MB
                                    ->live()
                                    ->required(fn(Forms\Get $get) => $get('media_source') === 'upload')
                                    ->hidden(fn(Forms\Get $get) => $get('media_source') !== 'upload')
                                    ->afterStateHydrated(function (Forms\Components\FileUpload $component, $state, $record) {
                                        if ($record && !str_starts_with($record->media_url, 'http')) {
                                            $component->state($record->media_url);
                                        }
                                    }),
                                Forms\Components\TextInput::make('media_url_link')
                                    ->label('Link URL External / YouTube')
                                    ->placeholder('https://contoh.com/video.mp4 atau https://youtube.com/watch?v=...')
                                    ->live()
                                    ->required(fn(Forms\Get $get) => $get('media_source') === 'url')
                                    ->hidden(fn(Forms\Get $get) => $get('media_source') !== 'url')
                                    ->afterStateHydrated(function (Forms\Components\TextInput $component, $state, $record) {
                                        if ($record && str_starts_with($record->media_url, 'http')) {
                                            $component->state($record->media_url);
                                        }
                                    }),
                            ])->columnSpan(2),

                            Forms\Components\Section::make('Pratinjau')
                                ->schema([
                                    Forms\Components\Placeholder::make('media_preview')
                                        ->label('')
                                        ->content(function (Forms\Get $get) {
                                            $source = $get('media_source');
                                            $url = $source === 'upload' ? $get('media_url_upload') : $get('media_url_link');

                                            if (!$url) return 'Belum ada media dipilih';

                                            $displayUrl = $source === 'upload' 
                                                ? (str_starts_with($url, 'video-inspirasi') ? asset('storage/' . $url) : $url)
                                                : $url;

                                            // Check for YouTube
                                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $displayUrl, $matches)) {
                                                $youtubeId = $matches[1];
                                                return new HtmlString('<div class="aspect-video w-full"><iframe src="https://www.youtube.com/embed/'.$youtubeId.'" class="w-full h-full rounded shadow-sm" frameborder="0" allowfullscreen></iframe></div>');
                                            }
                                            return new HtmlString('<div class="flex justify-center bg-gray-100 rounded-lg p-2"><video src="'.$displayUrl.'" controls style="max-height: 150px; width: auto;" class="rounded shadow-sm"></video></div>');
                                        })
                                ])->columnSpan(1),

                            Forms\Components\Hidden::make('media_type')
                                ->default('video'),
                        ])
                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                            $source = $data['media_source'] ?? 'upload';
                            $data['media_url'] = $source === 'upload' ? ($data['media_url_upload'] ?? '') : ($data['media_url_link'] ?? '');
                            unset($data['media_source'], $data['media_url_upload'], $data['media_url_link']);
                            return $data;
                        })
                        ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                            $source = $data['media_source'] ?? 'upload';
                            $data['media_url'] = $source === 'upload' ? ($data['media_url_upload'] ?? '') : ($data['media_url_link'] ?? '');
                            unset($data['media_source'], $data['media_url_upload'], $data['media_url_link']);
                            return $data;
                        })
                        ->columns(3)
                        ->maxItems(1)
                        ->addActionLabel('+ Tambah Video')
                        ->deletable(false),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('media.media_url')
                    ->label('Link Video')
                    ->limit(40)
                    ->copyable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Produk Terkait')
                    ->placeholder('-')
                    ->searchable(),
                Tables\Columns\TextColumn::make('views_count')
                    ->label('Views')
                    ->sortable()
                    ->numeric(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ManageVideoInspirations::route('/'),
        ];
    }
}
