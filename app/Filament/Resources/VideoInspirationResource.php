<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoInspirationResource\Pages;
use App\Models\Gallery;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class VideoInspirationResource extends Resource
{
    protected static ?string $model = Gallery::class;

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
            Forms\Components\Section::make('Informasi Video & Caption')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul Video Inspirasi')
                        ->placeholder('Contoh: Proses Pemasangan Roster Beton Minimalis')
                        ->helperText('Judul video yang otomatis dijadikan link URL unik.')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                    Forms\Components\TextInput::make('slug')
                        ->label('Alamat URL Link Unik (/video-inspirasi/...)')
                        ->helperText('Link permanen yang dapat dibagikan dan diindeks Google.')
                        ->required()
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('location')
                        ->label('Lokasi Proyek / Kota')
                        ->placeholder('Contoh: Purwakarta / Bandung / Jakarta'),
                    Forms\Components\Select::make('product_id')
                        ->relationship('product', 'name')
                        ->searchable()
                        ->preload()
                        ->label('Produk Terkait (Tombol Beli Langsung)')
                        ->placeholder('Pilih produk terkait (opsional)')
                        ->helperText('Jika dipilih, tombol beli & harga produk akan muncul di samping video.')
                        ->nullable(),
                    Forms\Components\Hidden::make('category')
                        ->default('video-inspirasi'),
                    Forms\Components\TextInput::make('sort_order')
                        ->label('Urutan Tampil')
                        ->numeric()
                        ->default(0),
                    Forms\Components\TextInput::make('views_count')
                        ->label('Jumlah Tayangan (Views)')
                        ->numeric()
                        ->default(0)
                        ->required(),
                    Forms\Components\Textarea::make('description')
                        ->label('Caption / Cerita Video Inspirasi (Tampil di Samping Video & Komentar)')
                        ->placeholder('Ceritakan ringkasan video, tips pemasangan, atau keunggulan roster dalam video ini...')
                        ->rows(3)
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Status Publikasi Aktif')
                        ->default(true),
                ])->columns(2),

            Forms\Components\Section::make('🔍 Optimasi SEO & Google Search (Opsional)')
                ->description('Pengaturan lanjutan agar video ini terindeks di tab Google Video Search.')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Forms\Components\TextInput::make('focus_keyword')
                        ->label('Target Kata Kunci (Focus Keyword)')
                        ->placeholder('Contoh: video pemasangan roster beton minimalis')
                        ->helperText('Kata kunci utama yang ditargetkan di pencarian Google.'),
                    Forms\Components\TextInput::make('meta_title')
                        ->label('Judul Google (Meta Title)')
                        ->placeholder('Biarkan kosong jika ingin otomatis menggunakan Judul Video'),
                    Forms\Components\Textarea::make('meta_description')
                        ->label('Deskripsi Snippet Google (Meta Description)')
                        ->placeholder('Biarkan kosong jika ingin otomatis mengambil dari Caption/Cerita di atas')
                        ->rows(2)
                        ->columnSpanFull(),
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
                                    ->imagePreviewHeight('120')
                                    ->openable()
                                    ->downloadable()
                                    ->maxSize(102400) // 100MB
                                    ->live()
                                    ->required(fn (Forms\Get $get) => $get('media_source') === 'upload')
                                    ->hidden(fn (Forms\Get $get) => $get('media_source') !== 'upload')
                                    ->afterStateHydrated(function (Forms\Components\FileUpload $component, $state, $record) {
                                        if ($record && ! str_starts_with($record->media_url ?? '', 'http')) {
                                            $component->state([$record->media_url]);
                                        }
                                    }),
                                Forms\Components\TextInput::make('media_url_link')
                                    ->label('Link URL External / YouTube')
                                    ->placeholder('https://contoh.com/video.mp4 atau https://youtube.com/watch?v=...')
                                    ->live()
                                    ->required(fn (Forms\Get $get) => $get('media_source') === 'url')
                                    ->hidden(fn (Forms\Get $get) => $get('media_source') !== 'url')
                                    ->afterStateHydrated(function (Forms\Components\TextInput $component, $state, $record) {
                                        if ($record && str_starts_with($record->media_url ?? '', 'http')) {
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

                                            if ($source === 'upload') {
                                                $url = is_array($url) ? (array_values($url)[0] ?? '') : $url;
                                            }

                                            if (! $url || ! is_string($url)) {
                                                return 'Belum ada media dipilih';
                                            }

                                            $displayUrl = $source === 'upload'
                                                ? (str_starts_with($url, 'video-inspirasi') ? asset('storage/'.$url) : $url)
                                                : $url;

                                            // Check for YouTube
                                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $displayUrl, $matches)) {
                                                $youtubeId = $matches[1];

                                                return new HtmlString('<div class="aspect-video w-full"><iframe src="https://www.youtube.com/embed/'.$youtubeId.'" class="w-full h-full rounded shadow-sm" frameborder="0" allowfullscreen></iframe></div>');
                                            }

                                            return new HtmlString('<div class="flex justify-center bg-gray-100 rounded-lg p-2"><video src="'.$displayUrl.'" controls style="max-height: 150px; width: auto;" class="rounded shadow-sm"></video></div>');
                                        }),
                                ])->columnSpan(1),

                            Forms\Components\Hidden::make('media_type')
                                ->default('video'),
                        ])
                        ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                            $source = $data['media_source'] ?? 'upload';
                            $uploadValue = $data['media_url_upload'] ?? '';
                            $uploadValue = is_array($uploadValue) ? (array_values($uploadValue)[0] ?? '') : $uploadValue;
                            $data['media_url'] = $source === 'upload' ? $uploadValue : ($data['media_url_link'] ?? '');
                            unset($data['media_source'], $data['media_url_upload'], $data['media_url_link']);

                            return $data;
                        })
                        ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                            $source = $data['media_source'] ?? 'upload';
                            $uploadValue = $data['media_url_upload'] ?? '';
                            $uploadValue = is_array($uploadValue) ? (array_values($uploadValue)[0] ?? '') : $uploadValue;
                            $data['media_url'] = $source === 'upload' ? $uploadValue : ($data['media_url_link'] ?? '');
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
