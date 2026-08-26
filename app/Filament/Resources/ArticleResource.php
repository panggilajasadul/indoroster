<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Services\ArticleAiGeneratorService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\File;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Artikel & Blog';

    protected static ?string $modelLabel = 'Artikel';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Konten Artikel')
                        ->headerActions([
                            Forms\Components\Actions\Action::make('generate_with_ai')
                                ->label('✨ Isi Form dengan AI (8-Skill)')
                                ->icon('heroicon-o-sparkles')
                                ->color('warning')
                                ->modalHeading('Pilih atau Buat Konten dengan AI IndoRoster')
                                ->modalDescription('Pilih naskah artikel berkualitas tinggi untuk langsung mengisi seluruh kolom formulir di bawah ini.')
                                ->form([
                                    Forms\Components\Select::make('preset_file')
                                        ->label('Pilih Topik Master Artikel')
                                        ->options(function () {
                                            $presets = ArticleAiGeneratorService::getPresets();
                                            $options = [];
                                            foreach ($presets as $key => $p) {
                                                $options[$key] = $p['title'];
                                            }

                                            return $options;
                                        })
                                        ->required(),
                                ])
                                ->action(function (array $data, Forms\Set $set) {
                                    $filePath = base_path("indoroster-blog-skills/articles/{$data['preset_file']}");
                                    if (File::exists($filePath)) {
                                        $content = File::get($filePath);

                                        preg_match('/\* \*\*Judul \(H1\)\*\*:\s*(.+)$/m', $content, $mTitle);
                                        preg_match('/\* \*\*Slug\*\*:\s*(.+)$/m', $content, $mSlug);
                                        preg_match('/\* \*\*Meta Title\*\*:\s*(.+)$/m', $content, $mMetaTitle);
                                        preg_match('/\* \*\*Meta Description\*\*:\s*(.+)$/m', $content, $mMetaDesc);
                                        preg_match('/\* \*\*Focus Keyword\*\*:\s*(.+)$/m', $content, $mKeywords);

                                        $title = isset($mTitle[1]) ? trim(trim($mTitle[1], '`')) : 'Artikel Baru';
                                        $slug = isset($mSlug[1]) ? trim(trim($mSlug[1], '`')) : Str::slug($title);

                                        $partsExcerpt = preg_split('/##\s+Ringkasan Singkat[^\n]*/i', $content);
                                        $excerpt = '';
                                        if (count($partsExcerpt) > 1) {
                                            $subParts = preg_split('/##\s+/i', $partsExcerpt[1]);
                                            $excerpt = trim($subParts[0]);
                                        }

                                        $partsBody = preg_split('/##\s+Isi Konten Lengkap[^\n]*/i', $content);
                                        $bodyMarkdown = count($partsBody) > 1 ? trim($partsBody[1]) : $content;
                                        $bodyHtml = Str::markdown($bodyMarkdown);

                                        $set('title', $title);
                                        $set('slug', $slug);
                                        $set('excerpt', $excerpt);
                                        $set('content', $bodyHtml);
                                        if (isset($mMetaTitle[1])) {
                                            $set('meta_title', trim(trim($mMetaTitle[1], '`')));
                                        }
                                        if (isset($mMetaDesc[1])) {
                                            $set('meta_description', trim(trim($mMetaDesc[1], '`')));
                                        }
                                        if (isset($mKeywords[1])) {
                                            $set('meta_keywords', trim(trim($mKeywords[1], '`')));
                                        }

                                        Notification::make()
                                            ->title('Form Berhasil Diisi dengan AI!')
                                            ->success()
                                            ->send();
                                    }
                                }),
                        ])
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Judul Artikel')
                                ->placeholder('Contoh: 7 Inspirasi Fasad Roster Beton...')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),

                            Forms\Components\TextInput::make('slug')
                                ->label('Slug URL')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->helperText('Alamat URL artikel: /artikel/{slug}'),

                            Forms\Components\Textarea::make('excerpt')
                                ->label('Ringkasan / Excerpt')
                                ->placeholder('Tuliskan ringkasan 1-2 kalimat menarik yang akan muncul di kartu artikel dan Google search snippet...')
                                ->rows(3)
                                ->helperText('Ringkasan singkat untuk preview katalog dan SEO meta description otomatis jika meta description kosong.'),

                            Forms\Components\RichEditor::make('content')
                                ->label('Isi Lengkap Artikel')
                                ->required()
                                ->fileAttachmentsDirectory('articles/content')
                                ->toolbarButtons([
                                    'attachFiles',
                                    'blockquote',
                                    'bold',
                                    'bulletList',
                                    'codeBlock',
                                    'h2',
                                    'h3',
                                    'italic',
                                    'link',
                                    'orderedList',
                                    'redo',
                                    'strike',
                                    'underline',
                                    'undo',
                                ])
                                ->columnSpanFull(),

                            Forms\Components\Placeholder::make('editor_tips')
                                ->label('💡 Panduan Foto, Video, Perataan Teks & Grid Kolom')
                                ->content(new HtmlString('
                                    <div class="text-xs space-y-2.5 bg-slate-50 dark:bg-slate-900/80 p-4 rounded-2xl border border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-300">
                                        <div class="font-bold text-slate-800 dark:text-white flex items-center gap-1.5">
                                            <span>📷 Upload & Penempatan Foto / Gambar:</span>
                                        </div>
                                        <p>• <b>Upload Foto:</b> Klik ikon klip kertas <b>📎 (Attach Files)</b> di toolbar atas untuk unggah foto langsung.</p>
                                        <p>• <b>Ukuran Sedang (Tengah):</b> Bungkus foto dengan tag <code>&lt;div class="img-medium"&gt;[foto]&lt;/div&gt;</code></p>
                                        <p>• <b>Ukuran Kompak / Kecil:</b> Bungkus foto dengan tag <code>&lt;div class="img-small"&gt;[foto]&lt;/div&gt;</code></p>
                                        <p>• <b>Foto Melayang di Kiri Teks (Float Left):</b> <code>&lt;div class="img-left"&gt;[foto]&lt;/div&gt;</code></p>
                                        <p>• <b>Foto Melayang di Kanan Teks (Float Right):</b> <code>&lt;div class="img-right"&gt;[foto]&lt;/div&gt;</code></p>
                                        <p>• <b>Galeri Grid 2 Kolom:</b> <code>&lt;div class="img-grid-2"&gt;[foto 1] [foto 2]&lt;/div&gt;</code></p>
                                        <p>• <b>Galeri Grid 3 Kolom:</b> <code>&lt;div class="img-grid-3"&gt;[foto 1] [foto 2] [foto 3]&lt;/div&gt;</code></p>

                                        <div class="font-bold text-slate-800 dark:text-white flex items-center gap-1.5 pt-2 border-t border-slate-200/60 dark:border-slate-800">
                                            <span>🎥 Sematkan Video (YouTube / MP4):</span>
                                        </div>
                                        <p>• <b>Video YouTube:</b> Masukkan iframe embed YouTube (otomatis 16:9 responsive dan berujung rounded).</p>
                                        <p>• <b>Video File MP4:</b> <code>&lt;video controls src="URL_VIDEO_MP4"&gt;&lt;/video&gt;</code></p>

                                        <div class="font-bold text-slate-800 dark:text-white flex items-center gap-1.5 pt-2 border-t border-slate-200/60 dark:border-slate-800">
                                            <span>✍️ Perataan Teks (Rata Kiri, Tengah, Kanan, Justify):</span>
                                        </div>
                                        <p>• <b>Rata Kanan-Kiri (Justify):</b> <code>&lt;div class="text-justify"&gt;...teks...&lt;/div&gt;</code> atau <code>&lt;p style="text-align: justify;"&gt;...&lt;/p&gt;</code></p>
                                        <p>• <b>Rata Tengah (Center):</b> <code>&lt;div class="text-center"&gt;...teks...&lt;/div&gt;</code> atau <code>&lt;p style="text-align: center;"&gt;...&lt;/p&gt;</code></p>
                                        <p>• <b>Rata Kanan (Right):</b> <code>&lt;div class="text-right"&gt;...teks...&lt;/div&gt;</code> atau <code>&lt;p style="text-align: right;"&gt;...&lt;/p&gt;</code></p>
                                    </div>
                                '))
                                ->columnSpanFull(),
                        ]),

                    Forms\Components\Section::make('Optimasi Google SEO & Gambar')
                        ->description('Pastikan metadata lengkap agar artikel dan foto terindeks optimal di Google Search dan Google Images.')
                        ->schema([
                            Forms\Components\TextInput::make('meta_title')
                                ->label('Meta Title (Judul di Google)')
                                ->placeholder('Judul yang memikat dengan kata kunci utama (maks. 60-70 karakter)'),

                            Forms\Components\Textarea::make('meta_description')
                                ->label('Meta Description')
                                ->placeholder('Deskripsi ringkas yang muncul di hasil pencarian Google...')
                                ->rows(2),

                            Forms\Components\TextInput::make('meta_keywords')
                                ->label('Meta Keywords')
                                ->placeholder('roster beton, fasad minimalis, roster plered, desain rumah tropis'),
                        ])
                        ->collapsed(),
                ])
                ->columnSpan(['lg' => 2]),

            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Publikasi & Kategori')
                        ->schema([
                            Forms\Components\Select::make('article_category_id')
                                ->label('Kategori')
                                ->options(ArticleCategory::where('is_active', true)->pluck('name', 'id'))
                                ->searchable()
                                ->preload()
                                ->createOptionForm([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nama Kategori')
                                        ->required(),
                                    Forms\Components\TextInput::make('slug')
                                        ->label('Slug')
                                        ->required(),
                                ]),

                            Forms\Components\Toggle::make('is_published')
                                ->label('Terbitkan (Published)')
                                ->default(true)
                                ->helperText('Jika nonaktif, artikel tersimpan sebagai draft.'),

                            Forms\Components\Toggle::make('is_featured')
                                ->label('Jadikan Sorotan (Featured)')
                                ->default(false)
                                ->helperText('Akan tampil di banner utama halaman katalog artikel.'),

                            Forms\Components\DateTimePicker::make('published_at')
                                ->label('Tanggal Publikasi')
                                ->default(now()),

                            Forms\Components\TextInput::make('author_name')
                                ->label('Nama Penulis / Redaksi')
                                ->default('Tim Redaksi IndoRoster'),

                            Forms\Components\TagsInput::make('tags')
                                ->label('Tag / Kata Kunci')
                                ->placeholder('Tambah tag...')
                                ->separator(','),
                        ]),

                    Forms\Components\Section::make('Foto Utama (Thumbnail & SEO Image)')
                        ->schema([
                            Forms\Components\FileUpload::make('thumbnail')
                                ->label('Upload Foto Utama')
                                ->image()
                                ->imageEditor()
                                ->directory('articles/thumbnails')
                                ->imagePreviewHeight('180')
                                ->openable()
                                ->downloadable()
                                ->helperText('Rekomendasi rasio 16:9 atau 16:10 untuk hasil tampilan artikel terbaik.'),

                            Forms\Components\TextInput::make('thumbnail_alt')
                                ->label('Alt Text Gambar (SEO Google Images)')
                                ->placeholder('Contoh: Foto Pemasangan Roster Beton Minimalis Mutu K-200 IndoRoster')
                                ->helperText('Sangat penting untuk Google Images! Jelaskan gambar secara deskriptif.'),
                        ]),
                ])
                ->columnSpan(['lg' => 1]),
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Foto')
                    ->circular(false)
                    ->extraImgAttributes(['class' => 'rounded-lg object-cover'])
                    ->defaultImageUrl(asset('assets/logo_indoroster_no_text.PNG')),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Artikel')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn (Article $record): string => $record->category?->name ?? 'Tanpa Kategori'),

                Tables\Columns\IconColumn::make('is_published')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Highlight')
                    ->boolean()
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning'),

                Tables\Columns\TextColumn::make('views_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Tayang')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('article_category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Status Terbit'),
            ])
            ->actions([
                Tables\Actions\Action::make('view_live')
                    ->label('Lihat')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Article $record): string => route('article.detail', $record->slug))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
