<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentTemplateResource\Pages;
use App\Models\DocumentTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Validation\ValidationRule;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class DocumentTemplateResource extends Resource
{
    protected static ?string $model = DocumentTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationLabel = '🎨 Template Desain';

    protected static ?string $pluralModelLabel = 'Template Dokumen';

    protected static ?string $modelLabel = 'Template';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(12)
                    ->schema([
                        // Left Column: Parameters & Settings (Span 4)
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Konfigurasi Template')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Template')
                                            ->required()
                                            ->placeholder('Contoh: Invoice Formal Biru'),
                                        Forms\Components\Select::make('type')
                                            ->label('Jenis Dokumen')
                                            ->options(self::getTypes())
                                            ->required()
                                            ->live(),
                                        Forms\Components\Select::make('paper_size')
                                            ->label('Ukuran Kertas')
                                            ->options([
                                                'a4' => 'A4 (210 x 297 mm)',
                                                'letter' => 'Letter (216 x 279 mm)',
                                            ])
                                            ->default('a4')
                                            ->required()
                                            ->live(),
                                        Forms\Components\Select::make('orientation')
                                            ->label('Orientasi')
                                            ->options([
                                                'portrait' => 'Tegak (Portrait)',
                                                'landscape' => 'Mendatar (Landscape)',
                                            ])
                                            ->default('portrait')
                                            ->required()
                                            ->live(),
                                        Forms\Components\TextInput::make('tax_rate')
                                            ->label('Tarif PPN (%)')
                                            ->numeric()
                                            ->default(11.00)
                                            ->suffix('%')
                                            ->required(),
                                        Forms\Components\Toggle::make('is_default')
                                            ->label('Jadikan Default')
                                            ->helperText('Gunakan template ini otomatis saat mencetak jenis dokumen terkait')
                                            ->default(false),
                                    ])->collapsible(),

                                Forms\Components\Section::make('Profil & Informasi Perusahaan')
                                    ->schema([
                                        Forms\Components\TextInput::make('company_name')
                                            ->label('Nama Perusahaan')
                                            ->required(),
                                        Forms\Components\Textarea::make('company_address')
                                            ->label('Alamat Perusahaan')
                                            ->rows(3)
                                            ->required(),
                                        Forms\Components\TextInput::make('company_phone')
                                            ->label('No. HP / WA Perusahaan')
                                            ->required(),
                                        Forms\Components\TextInput::make('company_email')
                                            ->label('Email Perusahaan')
                                            ->email()
                                            ->required(),
                                    ])->collapsible()->collapsed(),

                                Forms\Components\Section::make('Aset & Branding')
                                    ->schema([
                                        Forms\Components\FileUpload::make('logo_path')
                                            ->label('Logo Dokumen')
                                            ->disk('public')
                                            ->directory('document-assets/logos')
                                            ->extraInputAttributes(['accept' => 'image/*'])
                                            ->fetchFileInformation(false)
                                            ->rules([
                                                new class implements ValidationRule
                                                {
                                                    public function validate(string $attribute, mixed $value, \Closure $fail): void
                                                    {
                                                        if ($value instanceof TemporaryUploadedFile) {
                                                            $ext = strtolower($value->getClientOriginalExtension());
                                                            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                                                                $fail('Logo Dokumen harus berupa gambar (jpg, jpeg, png, gif, webp, svg).');
                                                            }
                                                        }
                                                    }
                                                },
                                            ]),
                                        Forms\Components\FileUpload::make('signature_path')
                                            ->label('Tanda Tangan Penanggung Jawab')
                                            ->disk('public')
                                            ->directory('document-assets/signatures')
                                            ->extraInputAttributes(['accept' => 'image/*'])
                                            ->fetchFileInformation(false)
                                            ->rules([
                                                new class implements ValidationRule
                                                {
                                                    public function validate(string $attribute, mixed $value, \Closure $fail): void
                                                    {
                                                        if ($value instanceof TemporaryUploadedFile) {
                                                            $ext = strtolower($value->getClientOriginalExtension());
                                                            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                                                                $fail('Tanda Tangan Penanggung Jawab harus berupa gambar (jpg, jpeg, png, gif, webp, svg).');
                                                            }
                                                        }
                                                    }
                                                },
                                            ]),
                                        Forms\Components\TextInput::make('signer_name')
                                            ->label('Nama Penanggung Jawab')
                                            ->placeholder('Contoh: Abdul Hamid'),
                                        Forms\Components\TextInput::make('signer_position')
                                            ->label('Jabatan')
                                            ->placeholder('Contoh: Direktur Utama'),
                                        Forms\Components\FileUpload::make('stamp_path')
                                            ->label('Stempel Perusahaan')
                                            ->disk('public')
                                            ->directory('document-assets/stamps')
                                            ->extraInputAttributes(['accept' => 'image/*'])
                                            ->fetchFileInformation(false)
                                            ->rules([
                                                new class implements ValidationRule
                                                {
                                                    public function validate(string $attribute, mixed $value, \Closure $fail): void
                                                    {
                                                        if ($value instanceof TemporaryUploadedFile) {
                                                            $ext = strtolower($value->getClientOriginalExtension());
                                                            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                                                                $fail('Stempel Perusahaan harus berupa gambar (jpg, jpeg, png, gif, webp, svg).');
                                                            }
                                                        }
                                                    }
                                                },
                                            ]),
                                        Forms\Components\TextInput::make('stamp_opacity')
                                            ->label('Transparansi Stempel')
                                            ->numeric()
                                            ->default(0.80)
                                            ->step(0.05)
                                            ->minValue(0.1)
                                            ->maxValue(1.0)
                                            ->live(),
                                        Forms\Components\TextInput::make('stamp_rotation')
                                            ->label('Rotasi Stempel (Derajat)')
                                            ->numeric()
                                            ->default(0)
                                            ->suffix('°')
                                            ->live(),
                                    ])->collapsible()->collapsed(),

                                Forms\Components\Section::make('Margin Halaman (mm)')
                                    ->schema([
                                        Forms\Components\TextInput::make('margins.top')
                                            ->label('Atas (Top)')
                                            ->numeric()
                                            ->default(15)
                                            ->suffix('mm')
                                            ->required()
                                            ->live(),
                                        Forms\Components\TextInput::make('margins.bottom')
                                            ->label('Bawah (Bottom)')
                                            ->numeric()
                                            ->default(15)
                                            ->suffix('mm')
                                            ->required()
                                            ->live(),
                                        Forms\Components\TextInput::make('margins.left')
                                            ->label('Kiri (Left)')
                                            ->numeric()
                                            ->default(15)
                                            ->suffix('mm')
                                            ->required()
                                            ->live(),
                                        Forms\Components\TextInput::make('margins.right')
                                            ->label('Kanan (Right)')
                                            ->numeric()
                                            ->default(15)
                                            ->suffix('mm')
                                            ->required()
                                            ->live(),
                                    ])->columns(2)->collapsible()->collapsed(),

                                // Coords inputs for absolute positions (Fallback fields and fine-tuning)
                                Forms\Components\Section::make('Fine-Tune Aset (Posisi mm)')
                                    ->schema([
                                        Forms\Components\TextInput::make('logo_x')->label('Logo X')->numeric()->suffix('mm')->live(),
                                        Forms\Components\TextInput::make('logo_y')->label('Logo Y')->numeric()->suffix('mm')->live(),
                                        Forms\Components\TextInput::make('logo_width')->label('Logo W')->numeric()->suffix('mm')->live(),
                                        Forms\Components\TextInput::make('logo_height')->label('Logo H')->numeric()->suffix('mm')->live(),

                                        Forms\Components\TextInput::make('signature_x')->label('TTD X')->numeric()->suffix('mm')->live(),
                                        Forms\Components\TextInput::make('signature_y')->label('TTD Y')->numeric()->suffix('mm')->live(),
                                        Forms\Components\TextInput::make('signature_width')->label('TTD W')->numeric()->suffix('mm')->live(),
                                        Forms\Components\TextInput::make('signature_height')->label('TTD H')->numeric()->suffix('mm')->live(),

                                        Forms\Components\TextInput::make('stamp_x')->label('Stempel X')->numeric()->suffix('mm')->live(),
                                        Forms\Components\TextInput::make('stamp_y')->label('Stempel Y')->numeric()->suffix('mm')->live(),
                                        Forms\Components\TextInput::make('stamp_width')->label('Stempel W')->numeric()->suffix('mm')->live(),
                                        Forms\Components\TextInput::make('stamp_height')->label('Stempel H')->numeric()->suffix('mm')->live(),
                                    ])->columns(4)->collapsible()->collapsed(),
                            ])->columnSpan(4),

                        // Right Column: A4 Live Visual Editor (Span 8)
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Visual Canva-like Designer')
                                    ->description('Geser (Drag) elemen-elemen di canvas A4 untuk memosisikannya secara instan. Arahkan mouse dan seret untuk memindahkan.')
                                    ->schema([
                                        Forms\Components\ViewField::make('elements')
                                            ->view('components.document-designer')
                                            ->columnSpanFull(),
                                    ]),
                            ])->columnSpan(8),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Template')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe Dokumen')
                    ->badge()
                    ->formatStateUsing(fn ($state) => self::getTypes()[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'invoice', 'faktur' => 'success',
                        'surat_jalan' => 'danger',
                        'kwitansi', 'receipt' => 'warning',
                        'quotation', 'penawaran' => 'info',
                        'sales_order', 'surat_pesanan' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('paper_size')
                    ->label('Kertas')
                    ->formatStateUsing(fn ($state) => strtoupper($state)),
                Tables\Columns\TextColumn::make('orientation')
                    ->label('Orientasi')
                    ->formatStateUsing(fn ($state) => $state === 'portrait' ? 'Portrait' : 'Landscape'),
                Tables\Columns\IconColumn::make('is_default')
                    ->label('Default')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diubah')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis Dokumen')
                    ->options(self::getTypes()),
            ])
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
            'index' => Pages\ListDocumentTemplates::route('/'),
            'create' => Pages\CreateDocumentTemplate::route('/create'),
            'edit' => Pages\EditDocumentTemplate::route('/{record}/edit'),
        ];
    }

    public static function getTypes(): array
    {
        return [
            'quotation' => '💼 Penawaran Harga (Quotation)',
            'sph' => '📝 Surat Dukungan Tender & SPH',
            'sales_order' => '📝 Surat Pesanan (Sales Order)',
            'invoice' => '🧾 Faktur Penjualan (Invoice)',
            'receipt' => '💰 Kwitansi Pembayaran (Receipt)',
            'proforma_invoice' => '📄 Proforma Invoice',
            'surat_jalan' => '🚚 Surat Jalan Pengiriman',
            'bast' => '📋 Berita Acara Serah Terima (BAST)',
            'lab_test' => '🔬 Sertifikat Uji Kuat Tekan Lab SNI',
            'delivery_note' => '📦 Delivery Note',
            'packing_list' => '📋 Packing List',
            'purchase_order' => '🛒 Purchase Order (PO)',
            'goods_receipt' => '🏢 Goods Receipt (Penerimaan Barang)',
            'supplier_invoice' => '💵 Supplier Invoice (Tagihan Pemasok)',
            'customer_statement' => '📊 Customer Statement (Rekening Koran)',
            'commercial_invoice' => '🌐 Commercial Invoice (Ekspor)',
            'export_packing_list' => '📦 Export Packing List (Ekspor)',
            'shipping_instruction' => '⚓ Shipping Instruction (SI)',
            'certificate_of_origin' => '📜 Certificate of Origin (COO)',
        ];
    }
}
