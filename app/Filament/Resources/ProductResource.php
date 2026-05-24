<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Katalog';
    protected static ?string $navigationLabel = 'Produk';
    protected static ?string $modelLabel = 'Produk';
    protected static ?string $pluralModelLabel = 'Produk';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Produk')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Produk')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug (URL)')
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                Forms\Components\Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\TextInput::make('sku')
                                    ->label('SKU (Kode Produk)')
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(50)
                                    ->default(fn () => 'IR-' . strtoupper(Str::random(6)))
                                    ->suffixAction(
                                        Forms\Components\Actions\Action::make('generateSku')
                                            ->icon('heroicon-m-arrow-path')
                                            ->action(function (Forms\Set $set) {
                                                $set('sku', 'IR-' . strtoupper(Str::random(6)));
                                            })
                                    ),
                                Forms\Components\RichEditor::make('description')
                                    ->label('Deskripsi Lengkap')
                                    ->required()
                                    ->default('<h3>📝 PANDUAN PEMESANAN & LAYANAN KONSUMEN</h3><p>Di Indoroster, belanja roster jadi jauh lebih praktis. <strong>Anda tidak perlu login atau daftar akun</strong> untuk melakukan pemesanan. Cukup pilih, bayar, dan tunggu barang sampai!</p><h4>1. Cara Pemesanan (Tanpa Login)</h4><ul><li><strong>Pilih & Hitung:</strong> Gunakan kalkulator di atas untuk tahu jumlah yang dibutuhkan.</li><li><strong>Beli Langsung:</strong> Masukkan jumlah pcs dan klik Beli Sekarang.</li><li><strong>Isi Data:</strong> Langsung isi nama dan alamat pengiriman tanpa harus daftar akun.</li><li><strong>Terima Invoice:</strong> Setelah pembayaran berhasil, Anda akan langsung menerima Invoice Resmi sebagai bukti transaksi yang sah.</li></ul><h4>2. Informasi yang Akan Kami Kirimkan ke Anda</h4><p>Setelah Anda melakukan pemesanan, tim Admin kami akan menghubungi Anda melalui <strong>WhatsApp</strong> untuk memberikan informasi berikut:</p><ul><li><strong>Konfirmasi Pembayaran:</strong> Kami akan mengirimkan notifikasi bahwa dana Anda sudah kami terima dan pesanan masuk antrean.</li><li><strong>Validasi Pesanan & Alamat:</strong> Kami akan melakukan verifikasi ulang mengenai item yang dipesan dan titik lokasi pengiriman agar tidak ada kesalahan kirim.</li><li><strong>Jadwal Pengiriman:</strong> Kami akan menginfokan Hari & Jam estimasi truk kami sampai di lokasi Anda.</li><li><strong>Informasi Driver:</strong> Saat barang dalam perjalanan, kami akan memberikan informasi driver/armada yang bertugas agar Anda mudah berkoordinasi di lokasi.</li></ul><h4>3. Hubungi Kami</h4><p>Butuh info lebih lanjut? Hubungi kami langsung di:</p><ul><li><strong>WhatsApp Official:</strong> <a href="https://wa.me/6281389709847">0813 8970 9847</a></li><li><strong>Jam Operasional:</strong> Senin - Sabtu (08.00 - 17.00 WIB)</li></ul><p>🛡️ <strong>Jaminan Kami:</strong> Kami menjamin setiap pesanan akan mendapatkan layanan personal. Anda tidak akan dibiarkan menunggu tanpa kepastian. Semua status pengiriman akan diinfokan secara berkala oleh Admin kami.</p>')
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Forms\Components\Section::make('Media Produk (Foto & Video)')
                            ->description('Tambahkan link URL gambar atau video. Tandai salah satu sebagai visual utama.')
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
                                            Forms\Components\FileUpload::make('media_image_upload')
                                                ->label('Upload Gambar (Auto Kompres WebP)')
                                                ->directory('product-media')
                                                ->image()
                                                ->maxSize(10240) // 10MB
                                                ->live()
                                                ->required(fn(Forms\Get $get) => $get('media_source') === 'upload' && $get('media_type') === 'image')
                                                ->hidden(fn(Forms\Get $get) => $get('media_source') !== 'upload' || $get('media_type') !== 'image')
                                                ->afterStateHydrated(function (Forms\Components\FileUpload $component, $state, $record) {
                                                    if ($record && !str_starts_with($record->media_url, 'http') && $record->media_type === 'image') {
                                                        $component->state([$record->media_url]);
                                                    }
                                                }),
                                            Forms\Components\FileUpload::make('media_video_upload')
                                                ->label('Upload Video')
                                                ->directory('product-media')
                                                ->acceptedFileTypes(['video/*'])
                                                ->maxSize(102400) // 100MB
                                                ->live()
                                                ->required(fn(Forms\Get $get) => $get('media_source') === 'upload' && $get('media_type') === 'video')
                                                ->hidden(fn(Forms\Get $get) => $get('media_source') !== 'upload' || $get('media_type') !== 'video')
                                                ->afterStateHydrated(function (Forms\Components\FileUpload $component, $state, $record) {
                                                    if ($record && !str_starts_with($record->media_url, 'http') && $record->media_type === 'video') {
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
                                                    if ($record && str_starts_with($record->media_url, 'http')) {
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
                                                        if ($source === 'upload') {
                                                            return new HtmlString('<div class="text-sm text-gray-500 italic">Pratinjau (preview) untuk file upload otomatis tampil di dalam kotak upload di atas.</div>');
                                                        } else {
                                                            $url = $get('media_url_link');
                                                            if (empty($url) || !is_string($url)) return 'Belum ada link URL yang diisi';
                                                            $displayUrl = $url;
                                                        }

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

                                        Forms\Components\TextInput::make('alt_text')
                                            ->label('Alt Text (SEO)')
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                        Forms\Components\Toggle::make('is_primary')
                                            ->label('Visual Utama')
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
                                    ->collapsible()
                                    ->columnSpanFull(),
                            ]),
                            
                        Forms\Components\Radio::make('product_type')
                            ->label('Tipe Penjualan Produk')
                            ->options([
                                'single' => 'Produk Tunggal (Tanpa Varian)',
                                'variant' => 'Produk Multi Varian (Banyak Pilihan)'
                            ])
                            ->default('single')
                            ->inline()
                            ->live()
                            ->dehydrated(false)
                            ->afterStateHydrated(function (Forms\Components\Radio $component, ?Product $record) {
                                if ($record) {
                                    $component->state($record->variants()->count() > 0 ? 'variant' : 'single');
                                }
                            })
                            ->columnSpanFull(),

                        Forms\Components\Section::make('Varian Produk')
                            ->description('Tambahkan pilihan varian (contoh: Warna, Material) beserta harga dan stoknya.')
                            ->hidden(fn (Forms\Get $get) => $get('product_type') !== 'variant')
                            ->schema([
                                Forms\Components\Repeater::make('variants')
                                    ->relationship()
                                    ->schema([
                                        Forms\Components\Select::make('material_id')
                                            ->label('Opsi Varian')
                                            ->relationship('material', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                $material = \App\Models\Material::find($state);
                                                if ($material) {
                                                    $set('name', $material->name);
                                                }
                                            }),
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Tampilan di Web')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('price_adjustment')
                                            ->label('Harga Varian (Rp)')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->default(0)
                                            ->helperText('Masukkan harga jual untuk varian ini'),
                                        Forms\Components\TextInput::make('stock')
                                            ->label('Stok Varian')
                                            ->numeric()
                                            ->required()
                                            ->default(0),
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Aktif')
                                            ->default(true),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->addActionLabel('+ Tambah Varian')
                                    ->collapsible()
                            ]),
                    ])->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Harga Dasar & Diskon')
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga (Rp)')
                                    ->required(fn (Forms\Get $get) => $get('product_type') !== 'variant')
                                    ->hidden(fn (Forms\Get $get) => $get('product_type') === 'variant')
                                    ->numeric()
                                    ->prefix('Rp'),
                                Forms\Components\TextInput::make('original_price')
                                    ->label('Harga Coret (Sebelum Diskon) (Rp)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->helperText('Kosongkan jika tidak ada diskon. Akan tampil dicoret jika lebih besar dari harga jual.'),
                                Forms\Components\TextInput::make('stock')
                                    ->label('Stok')
                                    ->required(fn (Forms\Get $get) => $get('product_type') !== 'variant')
                                    ->hidden(fn (Forms\Get $get) => $get('product_type') === 'variant')
                                    ->numeric()
                                    ->default(0),
                            ]),

                        Forms\Components\Section::make('Aturan Pemesanan')
                            ->schema([
                                Forms\Components\TextInput::make('min_order')
                                    ->label('Min. Order (pcs)')
                                    ->required()
                                    ->numeric()
                                    ->default(1),
                            ]),

                        Forms\Components\Section::make('Spesifikasi')
                            ->schema([
                                Forms\Components\TextInput::make('dimensions')
                                    ->label('Dimensi')
                                    ->placeholder('20cm x 20cm x 8cm'),
                                Forms\Components\TextInput::make('weight')
                                    ->label('Berat (kg)')
                                    ->numeric()
                                    ->suffix('kg'),
                            ]),

                        Forms\Components\Section::make('Status')
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Produk Aktif')
                                    ->default(true),
                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Tampil di Halaman Depan'),
                            ]),

                        Forms\Components\Section::make('SEO')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->label('Meta Title')
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('meta_description')
                                    ->label('Meta Description')
                                    ->rows(2)
                                    ->maxLength(500),
                            ])->collapsed(),
                    ])->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->badge(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Harga')
                    ->getStateUsing(fn (Product $record): string => $record->formatted_price_range)
                    ->searchable(query: function ($query, string $search): Builder {
                        return $query->where('price', 'like', "%{$search}%");
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_stock')
                    ->label('Stok')
                    ->getStateUsing(fn (Product $record): int => $record->total_stock)
                    ->sortable(query: function ($query, string $direction): Builder {
                        return $query->orderBy('stock', $direction);
                    })
                    ->color(fn (int $state): string => $state <= 10 ? 'danger' : 'success'),
                Tables\Columns\TextColumn::make('min_order')
                    ->label('Min. Order')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('view_count')
                    ->label('Views')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                Tables\Filters\TernaryFilter::make('is_active')->label('Aktif'),
                Tables\Filters\TernaryFilter::make('is_featured')->label('Featured'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ReplicateAction::make()
                    ->label('Clone')
                    ->modalHeading('Clone Produk')
                    ->modalButton('Clone Sekarang')
                    ->beforeReplicaSaved(function (Product $replica, Product $record): void {
                        $replica->name = '[Salinan] ' . $record->name;
                        $replica->slug = Str::slug($replica->name) . '-' . uniqid();
                        $replica->is_active = false;
                        $replica->sku = $record->sku ? $record->sku . '-CLONE' : null;
                    })
                    ->after(function (Product $replica, Product $record): void {
                        // Replicate media
                        foreach ($record->media as $media) {
                            $newMedia = $media->replicate();
                            $newMedia->product_id = $replica->id;
                            $newMedia->save();
                        }

                        // Replicate variants
                        foreach ($record->variants as $variant) {
                            $newVariant = $variant->replicate();
                            $newVariant->product_id = $replica->id;
                            $newVariant->save();
                        }
                    })
                    ->successNotificationTitle('Produk berhasil dikloning sebagai salinan!'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
