<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManualDocumentResource\Pages;
use App\Models\DocumentTemplate;
use App\Models\ManualDocument;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ManualDocumentResource extends Resource
{
    protected static ?string $model = ManualDocument::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationLabel = '📄 Dokumen & Cetak';

    protected static ?string $pluralModelLabel = 'Dokumen Offline';

    protected static ?string $modelLabel = 'Dokumen';

    protected static ?string $navigationGroup = 'Manajemen Pemenuhan';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Virtual/hidden form fields for designer Alpine binding
                Forms\Components\Hidden::make('orientation')->default('portrait'),
                Forms\Components\Hidden::make('paper_size')->default('a4'),
                Forms\Components\Hidden::make('margins')->default(['top' => 15, 'bottom' => 15, 'left' => 15, 'right' => 15]),
                Forms\Components\Hidden::make('logo_x')->default(15),
                Forms\Components\Hidden::make('logo_y')->default(15),
                Forms\Components\Hidden::make('logo_width')->default(50),
                Forms\Components\Hidden::make('logo_height')->default(25),
                Forms\Components\Hidden::make('signature_x')->default(140),
                Forms\Components\Hidden::make('signature_y')->default(220),
                Forms\Components\Hidden::make('signature_width')->default(40),
                Forms\Components\Hidden::make('signature_height')->default(20),
                Forms\Components\Hidden::make('stamp_x')->default(130),
                Forms\Components\Hidden::make('stamp_y')->default(215),
                Forms\Components\Hidden::make('stamp_width')->default(35),
                Forms\Components\Hidden::make('stamp_height')->default(35),
                Forms\Components\Hidden::make('stamp_opacity')->default(0.8),
                Forms\Components\Hidden::make('stamp_rotation')->default(0),
                Forms\Components\Hidden::make('elements')->default(null),
                Forms\Components\Hidden::make('logo_path')->default(null),
                Forms\Components\Hidden::make('stamp_path')->default(null),
                Forms\Components\Hidden::make('template_signature_path')->default(null),
                Forms\Components\Hidden::make('company_name')->default('Indoroster'),
                Forms\Components\Hidden::make('company_address')->default(null),
                Forms\Components\Hidden::make('company_phone')->default(null),
                Forms\Components\Hidden::make('company_email')->default(null),
                Forms\Components\Hidden::make('signer_name')->default(null),
                Forms\Components\Hidden::make('signer_position')->default(null),

                Forms\Components\Tabs::make('Document Editor')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Isi Dokumen')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Section::make('Informasi Utama')
                                                    ->schema([
                                                        Forms\Components\Select::make('type')
                                                            ->label('Tipe Dokumen')
                                                            ->options([
                                                                'invoice' => '🧾 Faktur Penjualan',
                                                                'surat_jalan' => '🚚 Surat Jalan',
                                                                'receipt' => '💰 Kwitansi Pembayaran',
                                                                'quotation' => '💼 Penawaran Harga (Quotation)',
                                                                'sales_order' => '📝 Surat Pesanan (Sales Order)',
                                                                'proforma_invoice' => '📄 Proforma Invoice',
                                                                'delivery_note' => '📦 Delivery Note',
                                                                'packing_list' => '📋 Packing List',
                                                                'purchase_order' => '🛒 Purchase Order',
                                                                'goods_receipt' => '🏢 Goods Receipt',
                                                                'supplier_invoice' => '💵 Supplier Invoice',
                                                                'customer_statement' => '📊 Customer Statement',
                                                                'commercial_invoice' => '🌐 Commercial Invoice (Ekspor)',
                                                                'export_packing_list' => '📦 Export Packing List (Ekspor)',
                                                                'shipping_instruction' => '⚓ Shipping Instruction (SI)',
                                                                'certificate_of_origin' => '📜 Certificate of Origin (COO)',
                                                            ])
                                                            ->required()
                                                            ->default('invoice')
                                                            ->live()
                                                            ->afterStateHydrated(function ($state, Forms\Set $set, $record) {
                                                                if ($record === null) {
                                                                    // Populate defaults for new records on load
                                                                    $set('company_name', SiteSetting::getValue('doc_company_name') ?? SiteSetting::getValue('factory_name') ?? 'INDOROSTER INDONESIA');
                                                                    $set('company_address', SiteSetting::getValue('doc_company_address') ?? SiteSetting::getValue('factory_address') ?? 'Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165');
                                                                    $set('company_phone', SiteSetting::getValue('doc_company_phone') ?? SiteSetting::getValue('whatsapp_number') ?? '0813-8970-9847');
                                                                    $set('company_email', SiteSetting::getValue('doc_company_email') ?? SiteSetting::getValue('contact_email') ?? 'abdulhamid66266@gmail.com');
                                                                    $set('signer_name', SiteSetting::getValue('doc_signer_name') ?? 'Penanggung Jawab');
                                                                    $set('signer_position', SiteSetting::getValue('doc_signer_position') ?? 'Authorized Signatory');
                                                                    $set('logo_path', SiteSetting::getValue('doc_logo_path'));
                                                                    $set('template_signature_path', SiteSetting::getValue('doc_signature_path'));
                                                                    $set('stamp_path', SiteSetting::getValue('doc_stamp_path'));

                                                                    $set('orientation', SiteSetting::getValue('doc_orientation') ?? 'portrait');
                                                                    $set('paper_size', SiteSetting::getValue('doc_paper_size') ?? 'a4');

                                                                    $marginsRaw = SiteSetting::getValue('doc_margins');
                                                                    $margins = $marginsRaw ? json_decode($marginsRaw, true) : null;
                                                                    $set('margins', $margins ?? ['top' => 15, 'bottom' => 15, 'left' => 15, 'right' => 15]);

                                                                    $set('elements', DocumentTemplate::getDefaultElementsConfig($state ?: 'invoice'));

                                                                    $set('logo_x', 15);
                                                                    $set('logo_y', 15);
                                                                    $set('logo_width', 50);
                                                                    $set('logo_height', 25);

                                                                    $set('signature_x', 140);
                                                                    $set('signature_y', 240);
                                                                    $set('signature_width', 40);
                                                                    $set('signature_height', 20);

                                                                    $set('stamp_x', 130);
                                                                    $set('stamp_y', 230);
                                                                    $set('stamp_width', 35);
                                                                    $set('stamp_height', 35);
                                                                    $set('stamp_opacity', 0.80);
                                                                    $set('stamp_rotation', 0);
                                                                }
                                                            })
                                                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                                                // Reset selected template when type changes
                                                                $set('document_template_id', null);

                                                                // Reset to global settings fallback
                                                                $set('company_name', SiteSetting::getValue('doc_company_name') ?? SiteSetting::getValue('factory_name') ?? 'INDOROSTER INDONESIA');
                                                                $set('company_address', SiteSetting::getValue('doc_company_address') ?? SiteSetting::getValue('factory_address') ?? 'Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165');
                                                                $set('company_phone', SiteSetting::getValue('doc_company_phone') ?? SiteSetting::getValue('whatsapp_number') ?? '0813-8970-9847');
                                                                $set('company_email', SiteSetting::getValue('doc_company_email') ?? SiteSetting::getValue('contact_email') ?? 'abdulhamid66266@gmail.com');
                                                                $set('signer_name', SiteSetting::getValue('doc_signer_name') ?? 'Penanggung Jawab');
                                                                $set('signer_position', SiteSetting::getValue('doc_signer_position') ?? 'Authorized Signatory');
                                                                $set('logo_path', SiteSetting::getValue('doc_logo_path'));
                                                                $set('template_signature_path', SiteSetting::getValue('doc_signature_path'));
                                                                $set('stamp_path', SiteSetting::getValue('doc_stamp_path'));

                                                                $set('orientation', SiteSetting::getValue('doc_orientation') ?? 'portrait');
                                                                $set('paper_size', SiteSetting::getValue('doc_paper_size') ?? 'a4');

                                                                $marginsRaw = SiteSetting::getValue('doc_margins');
                                                                $margins = $marginsRaw ? json_decode($marginsRaw, true) : null;
                                                                $set('margins', $margins ?? ['top' => 15, 'bottom' => 15, 'left' => 15, 'right' => 15]);

                                                                $set('elements', DocumentTemplate::getDefaultElementsConfig($state));

                                                                $set('logo_x', 15);
                                                                $set('logo_y', 15);
                                                                $set('logo_width', 50);
                                                                $set('logo_height', 25);

                                                                $set('signature_x', 140);
                                                                $set('signature_y', 240);
                                                                $set('signature_width', 40);
                                                                $set('signature_height', 20);

                                                                $set('stamp_x', 130);
                                                                $set('stamp_y', 230);
                                                                $set('stamp_width', 35);
                                                                $set('stamp_height', 35);
                                                                $set('stamp_opacity', 0.80);
                                                                $set('stamp_rotation', 0);
                                                            }),
                                                        Forms\Components\Select::make('document_template_id')
                                                            ->label('Template Desain')
                                                            ->relationship('documentTemplate', 'name', modifyQueryUsing: fn (Builder $query, Forms\Get $get) => $query->where('type', $get('type')))
                                                            ->live()
                                                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                                                if (empty($state)) {
                                                                    // Reset to global settings fallback
                                                                    $set('company_name', SiteSetting::getValue('doc_company_name') ?? SiteSetting::getValue('factory_name') ?? 'INDOROSTER INDONESIA');
                                                                    $set('company_address', SiteSetting::getValue('doc_company_address') ?? SiteSetting::getValue('factory_address') ?? 'Kp. Cicadas, RT 05 RW 03, Desa Cadasmekar, Kec. Tegalwaru, Kab. Purwakarta, Jawa Barat, 41165');
                                                                    $set('company_phone', SiteSetting::getValue('doc_company_phone') ?? SiteSetting::getValue('whatsapp_number') ?? '0813-8970-9847');
                                                                    $set('company_email', SiteSetting::getValue('doc_company_email') ?? SiteSetting::getValue('contact_email') ?? 'abdulhamid66266@gmail.com');
                                                                    $set('signer_name', SiteSetting::getValue('doc_signer_name') ?? 'Penanggung Jawab');
                                                                    $set('signer_position', SiteSetting::getValue('doc_signer_position') ?? 'Authorized Signatory');
                                                                    $set('logo_path', SiteSetting::getValue('doc_logo_path'));
                                                                    $set('template_signature_path', SiteSetting::getValue('doc_signature_path'));
                                                                    $set('stamp_path', SiteSetting::getValue('doc_stamp_path'));

                                                                    $set('orientation', SiteSetting::getValue('doc_orientation') ?? 'portrait');
                                                                    $set('paper_size', SiteSetting::getValue('doc_paper_size') ?? 'a4');

                                                                    $marginsRaw = SiteSetting::getValue('doc_margins');
                                                                    $margins = $marginsRaw ? json_decode($marginsRaw, true) : null;
                                                                    $set('margins', $margins ?? ['top' => 15, 'bottom' => 15, 'left' => 15, 'right' => 15]);

                                                                    $set('elements', DocumentTemplate::getDefaultElementsConfig($get('type')));

                                                                    $set('logo_x', 15);
                                                                    $set('logo_y', 15);
                                                                    $set('logo_width', 50);
                                                                    $set('logo_height', 25);

                                                                    $set('signature_x', 140);
                                                                    $set('signature_y', 240);
                                                                    $set('signature_width', 40);
                                                                    $set('signature_height', 20);

                                                                    $set('stamp_x', 130);
                                                                    $set('stamp_y', 230);
                                                                    $set('stamp_width', 35);
                                                                    $set('stamp_height', 35);
                                                                    $set('stamp_opacity', 0.80);
                                                                    $set('stamp_rotation', 0);

                                                                    return;
                                                                }
                                                                $template = DocumentTemplate::find($state);
                                                                if ($template) {
                                                                    $set('issued_by', $template->signer_name ?: auth()->user()?->name);

                                                                    // Copy template coordinates to virtual form fields for visual editor!
                                                                    $set('orientation', $template->orientation);
                                                                    $set('paper_size', $template->paper_size);
                                                                    $set('margins', $template->margins);
                                                                    $set('logo_x', $template->logo_x);
                                                                    $set('logo_y', $template->logo_y);
                                                                    $set('logo_width', $template->logo_width);
                                                                    $set('logo_height', $template->logo_height);
                                                                    $set('signature_x', $template->signature_x);
                                                                    $set('signature_y', $template->signature_y);
                                                                    $set('signature_width', $template->signature_width);
                                                                    $set('signature_height', $template->signature_height);
                                                                    $set('stamp_x', $template->stamp_x);
                                                                    $set('stamp_y', $template->stamp_y);
                                                                    $set('stamp_width', $template->stamp_width);
                                                                    $set('stamp_height', $template->stamp_height);
                                                                    $set('stamp_opacity', $template->stamp_opacity);
                                                                    $set('stamp_rotation', $template->stamp_rotation);
                                                                    $set('elements', $template->elements);

                                                                    // Also set logo, signature, and stamp paths so they are entangled and previewed in canvas!
                                                                    $set('logo_path', $template->logo_path);
                                                                    $set('template_signature_path', $template->signature_path);
                                                                    $set('stamp_path', $template->stamp_path);
                                                                    $set('company_name', $template->company_name);
                                                                    $set('company_address', $template->company_address);
                                                                    $set('company_phone', $template->company_phone);
                                                                    $set('company_email', $template->company_email);
                                                                    $set('signer_name', $template->signer_name);
                                                                    $set('signer_position', $template->signer_position);

                                                                    // Pre-fill textarea values based on template type if they are empty
                                                                    if (in_array($template->type, ['faktur', 'invoice', 'proforma_invoice', 'supplier_invoice'])) {
                                                                        $set('custom_payment_instructions_title', 'Petunjuk Pembayaran');
                                                                        $set('custom_payment_instructions', "Transfer Bank BCA No. Rek: 231-xxxx-xxx a/n INDOROSTER INDONESIA\nBayar DP minimal 50% untuk konfirmasi pesanan.\nPelunasan dilakukan sebelum barang dikirim.");
                                                                    } elseif ($template->type === 'commercial_invoice') {
                                                                        $set('custom_payment_instructions_title', 'Payment Instructions');
                                                                        $set('custom_payment_instructions', "Transfer Bank BCA No. Rek: 231-xxxx-xxx a/n INDOROSTER INDONESIA\nPayment Terms: T/T 50% deposit, 50% balance against copy of Bill of Lading (B/L).\nDelivery Terms: FOB Tanjung Priok Port, Jakarta, Indonesia.");
                                                                    } elseif (in_array($template->type, ['surat_jalan', 'delivery_note', 'packing_list', 'goods_receipt'])) {
                                                                        $set('custom_delivery_notes_title', 'Catatan Pengiriman');
                                                                        $set('custom_delivery_notes', "- Mohon periksa kondisi barang saat diterima.\n- Tanda tangani surat jalan sebagai bukti penerimaan.\n- Kerusakan akibat pengiriman harap dilaporkan dalam 1x24 jam.");
                                                                    } elseif (in_array($template->type, ['export_packing_list', 'shipping_instruction'])) {
                                                                        $set('custom_delivery_notes_title', 'Shipping & Packaging Information');
                                                                        if ($template->type === 'export_packing_list') {
                                                                            $set('custom_delivery_notes', "Shipping Mark: INDOROSTER/[CUSTOMER_NAME]/[PORT_OF_DESTINATION]\nPackaging: Heavy-duty wooden pallets with plastic wrapping and strapping bands.\nContainer Load: 1x20' FCL (Full Container Load).");
                                                                        } else {
                                                                            $set('custom_delivery_notes', "Shipper: INDOROSTER INDONESIA\nPort of Loading: Tanjung Priok Port, Jakarta, Indonesia\nPort of Discharge: [Destination Port]\nHS Code: 6810.11.00 (Concrete Roster Blocks)");
                                                                        }
                                                                    } elseif (in_array($template->type, ['kwitansi', 'receipt', 'customer_statement'])) {
                                                                        $set('custom_receipt_notes_title', 'Keterangan Tambahan');
                                                                        $set('custom_receipt_notes', "Kwitansi ini merupakan bukti pembayaran yang sah.\nHarap disimpan sebagai arsip transaksi Anda.");
                                                                    } elseif (in_array($template->type, ['penawaran', 'quotation', 'purchase_order', 'certificate_of_origin'])) {
                                                                        if ($template->type === 'certificate_of_origin') {
                                                                            $set('custom_terms_title', 'Certificate of Origin Information');
                                                                            $set('custom_terms_and_conditions', "Manufacturer: INDOROSTER INDONESIA, Purwakarta, West Java, Indonesia.\nCountry of Origin: Republic of Indonesia.\nPlace of Issue: Jakarta, Indonesia.");
                                                                        } else {
                                                                            $set('custom_terms_title', 'Syarat & Ketentuan Penawaran');
                                                                            $set('custom_terms_and_conditions', "1. Harga di atas dapat berubah menyesuaikan volume pemesanan final.\n2. Penawaran harga ini berlaku selama 30 hari sejak diterbitkan.\n3. Pembayaran DP 50%, pelunasan sebelum barang dikirim.\n4. Barang yang sudah diproduksi tidak dapat dibatalkan sepihak.\n5. Pengiriman menggunakan ekspedisi rekanan Indoroster Indonesia.");
                                                                        }
                                                                    } elseif (in_array($template->type, ['surat_pesanan', 'sales_order'])) {
                                                                        $set('custom_order_notes_title', 'Catatan Alur Pesanan');
                                                                        $set('custom_order_notes', "1. Pesanan ini bersifat mengikat setelah DP 50% diterima.\n2. Jadwal produksi akan dikonfirmasi dalam 1x24 jam.\n3. Estimasi waktu produksi 3-7 hari kerja tergantung volume.\n4. Barang yang sudah dalam proses produksi tidak dapat dibatalkan.\n5. Pembayaran penuh dilakukan sebelum barang dikirim.");
                                                                    }
                                                                    self::updateTotals($get, $set);
                                                                }
                                                            }),
                                                        Forms\Components\TextInput::make('document_number')
                                                            ->label('Nomor Dokumen')
                                                            ->placeholder('Auto-generated')
                                                            ->disabled()
                                                            ->dehydrated(false),
                                                        Forms\Components\DatePicker::make('document_date')
                                                            ->label('Tanggal Dokumen')
                                                            ->default(now())
                                                            ->required(),
                                                        Forms\Components\DatePicker::make('due_date')
                                                            ->label('Tanggal Jatuh Tempo')
                                                            ->placeholder('Opsional'),
                                                        Forms\Components\TextInput::make('issued_by')
                                                            ->label('Dibuat Oleh')
                                                            ->default(fn () => auth()->user()?->name)
                                                            ->required(),
                                                        Forms\Components\Select::make('status')
                                                            ->label('Status')
                                                            ->options([
                                                                'draft' => 'Draft',
                                                                'final' => 'Final (Siap Cetak & Kunci Snapshot)',
                                                            ])
                                                            ->default('draft')
                                                            ->required(),
                                                        Forms\Components\Select::make('custom_watermark')
                                                            ->label('Watermark Dokumen')
                                                            ->options([
                                                                'none' => 'Tanpa Watermark',
                                                                'LUNAS' => 'LUNAS (Lunas Pembayaran)',
                                                                'PAID' => 'PAID (Paid Payment)',
                                                                'DRAFT' => 'DRAFT (Rancangan)',
                                                                'VOID' => 'VOID (Batal/Tidak Berlaku)',
                                                                'ORIGINAL' => 'ORIGINAL (Dokumen Asli)',
                                                                'COPY' => 'COPY (Salinan Dokumen)',
                                                            ])
                                                            ->afterStateHydrated(function ($component, $record) {
                                                                $component->state($record?->extra_data['watermark'] ?? 'none');
                                                            })
                                                            ->default('none')
                                                            ->required(),
                                                    ])->columns(2),

                                                Forms\Components\Section::make('Informasi Klien')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('client_name')
                                                            ->label('Nama Klien / Instansi')
                                                            ->required()
                                                            ->live(debounce: 500)
                                                            ->placeholder('Contoh: PT. Abadi Jaya'),
                                                        Forms\Components\TextInput::make('client_phone')
                                                            ->label('Nomor Telepon/WA')
                                                            ->live(debounce: 500)
                                                            ->placeholder('Contoh: 0812xxxxxxxx'),
                                                        Forms\Components\TextInput::make('client_email')
                                                            ->label('Alamat Email')
                                                            ->email()
                                                            ->live(debounce: 500)
                                                            ->placeholder('Contoh: client@email.com'),
                                                        Forms\Components\Textarea::make('client_address')
                                                            ->label('Alamat Lengkap')
                                                            ->rows(2)
                                                            ->live(debounce: 500)
                                                            ->placeholder('Masukkan alamat pengiriman atau korespondensi...'),
                                                    ])->columns(2),

                                                Forms\Components\Section::make('Daftar Produk / Varian')
                                                    ->schema([
                                                        Forms\Components\Repeater::make('items')
                                                            ->label('Item Dokumen')
                                                            ->schema([
                                                                Forms\Components\Select::make('product_id')
                                                                    ->label('Cari Produk (Katalog)')
                                                                    ->searchable()
                                                                    ->getSearchResultsUsing(function (string $search) {
                                                                        $products = Product::where('name', 'like', "%{$search}%")->limit(15)->get();
                                                                        $options = [];
                                                                        foreach ($products as $p) {
                                                                            $options[$p->id] = $p->name.' (Stok: '.$p->stock.')';
                                                                        }

                                                                        return $options;
                                                                    })
                                                                    ->getOptionLabelUsing(fn ($value) => Product::find($value)?->name)
                                                                    ->reactive()
                                                                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                                                        if ($state) {
                                                                            $prod = Product::find($state);
                                                                            if ($prod) {
                                                                                $set('product_name', $prod->name);
                                                                                $set('price', floatval($prod->price));
                                                                                $set('sku', $prod->sku);
                                                                                $set('dimensions', $prod->dimensions);
                                                                                $set('variant_name', ''); // reset variant name
                                                                                $set('product_variant_id', null); // reset variant select
                                                                                if (! $get('quantity')) {
                                                                                    $set('quantity', 1);
                                                                                }
                                                                                self::updateItemTotal($get, $set);
                                                                                self::updateTotals($get, $set);
                                                                            }
                                                                        }
                                                                    })
                                                                    ->placeholder('Cari dari katalog...'),
                                                                Forms\Components\Select::make('product_variant_id')
                                                                    ->label('Pilih Varian')
                                                                    ->placeholder('Pilih varian...')
                                                                    ->options(function (Forms\Get $get) {
                                                                        $productId = $get('product_id');
                                                                        if (! $productId) {
                                                                            return [];
                                                                        }
                                                                        $variants = ProductVariant::where('product_id', $productId)->get();
                                                                        $options = [];
                                                                        foreach ($variants as $v) {
                                                                            $options[$v->id] = $v->name.($v->price_adjustment > 0 ? ' (+Rp '.number_format($v->price_adjustment, 0, ',', '.').')' : '');
                                                                        }

                                                                        return $options;
                                                                    })
                                                                    ->reactive()
                                                                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                                                                        if ($state) {
                                                                            $variant = ProductVariant::find($state);
                                                                            if ($variant) {
                                                                                $prod = Product::find($get('product_id'));
                                                                                $basePrice = $prod ? floatval($prod->price) : 0;
                                                                                $set('variant_name', $variant->name);
                                                                                $set('price', $basePrice + floatval($variant->price_adjustment));
                                                                                if ($variant->sku) {
                                                                                    $set('sku', $variant->sku);
                                                                                }
                                                                                self::updateItemTotal($get, $set);
                                                                                self::updateTotals($get, $set);
                                                                            }
                                                                        }
                                                                    }),
                                                                Forms\Components\TextInput::make('product_name')
                                                                    ->label('Nama Produk (Cetak)')
                                                                    ->required(),
                                                                Forms\Components\TextInput::make('variant_name')
                                                                    ->label('Varian / Warna (Cetak)')
                                                                    ->placeholder('Contoh: Abu Abu Natural'),
                                                                Forms\Components\TextInput::make('dimensions')
                                                                    ->label('Ukuran / Dimensi (Cetak)')
                                                                    ->placeholder('Contoh: 20 x 20 x 10 cm'),
                                                                Forms\Components\TextInput::make('sku')
                                                                    ->label('SKU')
                                                                    ->placeholder('Otomatis / Input Manual'),
                                                                Forms\Components\TextInput::make('quantity')
                                                                    ->label('Kuantitas')
                                                                    ->numeric()
                                                                    ->default(1)
                                                                    ->required()
                                                                    ->live()
                                                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                                                        self::updateItemTotal($get, $set);
                                                                        self::updateTotals($get, $set);
                                                                    }),
                                                                Forms\Components\TextInput::make('price')
                                                                    ->label('Harga Satuan')
                                                                    ->numeric()
                                                                    ->default(0)
                                                                    ->prefix('Rp')
                                                                    ->required()
                                                                    ->live()
                                                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set) {
                                                                        self::updateItemTotal($get, $set);
                                                                        self::updateTotals($get, $set);
                                                                    }),
                                                                Forms\Components\TextInput::make('total')
                                                                    ->label('Total Harga')
                                                                    ->numeric()
                                                                    ->disabled()
                                                                    ->dehydrated()
                                                                    ->prefix('Rp')
                                                                    ->default(0),
                                                            ])
                                                            ->columns(3)
                                                            ->minItems(1)
                                                            ->default([[]])
                                                            ->live()
                                                            ->afterStateUpdated(fn (Forms\Get $get, Forms\Set $set) => self::updateTotals($get, $set)),
                                                    ]),

                                                Forms\Components\Section::make('Konten Kustom Dokumen')
                                                    ->description('Teks di bawah ini sudah terisi template bawaan — edit sesuai kebutuhan sebelum cetak.')
                                                    ->schema([
                                                        Forms\Components\Placeholder::make('select_type_first')
                                                            ->label('')
                                                            ->content('Silakan pilih "Tipe Dokumen" di bagian paling atas formulir terlebih dahulu untuk memuat template bawaan yang bisa diedit.')
                                                            ->visible(fn (Forms\Get $get) => blank($get('type'))),

                                                        Forms\Components\TextInput::make('custom_payment_instructions_title')
                                                            ->label('Judul Bagian Pembayaran')
                                                            ->afterStateHydrated(function ($component, $record) {
                                                                $component->state($record?->extra_data['payment_instructions_title'] ?? 'Petunjuk Pembayaran');
                                                            })
                                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['faktur', 'invoice', 'proforma_invoice', 'supplier_invoice', 'commercial_invoice'])),
                                                        Forms\Components\Textarea::make('custom_payment_instructions')
                                                            ->label('Petunjuk Pembayaran (Isi)')
                                                            ->rows(4)
                                                            ->hint('Tampil di bagian bawah faktur')
                                                            ->afterStateHydrated(function ($component, $record) {
                                                                $default = "Transfer Bank BCA No. Rek: 231-xxxx-xxx a/n INDOROSTER INDONESIA\nBayar DP minimal 50% untuk konfirmasi pesanan.\nPelunasan dilakukan sebelum barang dikirim.";
                                                                $component->state($record?->extra_data['payment_instructions'] ?? $default);
                                                            })
                                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['faktur', 'invoice', 'proforma_invoice', 'supplier_invoice', 'commercial_invoice'])),

                                                        Forms\Components\TextInput::make('custom_delivery_notes_title')
                                                            ->label('Judul Catatan Pengiriman')
                                                            ->afterStateHydrated(function ($component, $record) {
                                                                $component->state($record?->extra_data['delivery_notes_title'] ?? 'Catatan Pengiriman');
                                                            })
                                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['surat_jalan', 'delivery_note', 'packing_list', 'goods_receipt', 'export_packing_list', 'shipping_instruction'])),
                                                        Forms\Components\Textarea::make('custom_delivery_notes')
                                                            ->label('Catatan Pengiriman (Isi)')
                                                            ->rows(4)
                                                            ->hint('Tampil di bagian bawah surat jalan')
                                                            ->afterStateHydrated(function ($component, $record) {
                                                                $default = "- Mohon periksa kondisi barang saat diterima.\n- Tanda tangani surat jalan sebagai bukti penerimaan.\n- Kerusakan akibat pengiriman harap dilaporkan dalam 1x24 jam.";
                                                                $component->state($record?->extra_data['delivery_notes'] ?? $default);
                                                            })
                                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['surat_jalan', 'delivery_note', 'packing_list', 'goods_receipt', 'export_packing_list', 'shipping_instruction'])),

                                                        Forms\Components\TextInput::make('custom_receipt_notes_title')
                                                            ->label('Judul Keterangan Tambahan')
                                                            ->afterStateHydrated(function ($component, $record) {
                                                                $component->state($record?->extra_data['receipt_notes_title'] ?? 'Keterangan Tambahan');
                                                            })
                                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['kwitansi', 'receipt', 'customer_statement'])),
                                                        Forms\Components\Textarea::make('custom_receipt_notes')
                                                            ->label('Keterangan Tambahan (Isi)')
                                                            ->rows(3)
                                                            ->hint('Tampil di bagian bawah kwitansi')
                                                            ->afterStateHydrated(function ($component, $record) {
                                                                $default = "Kwitansi ini merupakan bukti pembayaran yang sah.\nHarap disimpan sebagai arsip transaksi Anda.";
                                                                $component->state($record?->extra_data['receipt_notes'] ?? $default);
                                                            })
                                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['kwitansi', 'receipt', 'customer_statement'])),

                                                        Forms\Components\TextInput::make('custom_terms_title')
                                                            ->label('Judul Syarat & Ketentuan')
                                                            ->afterStateHydrated(function ($component, $record) {
                                                                $component->state($record?->extra_data['terms_title'] ?? 'Syarat & Ketentuan Penawaran');
                                                            })
                                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['penawaran', 'quotation', 'purchase_order', 'certificate_of_origin'])),
                                                        Forms\Components\Textarea::make('custom_terms_and_conditions')
                                                            ->label('Syarat & Ketentuan (Isi)')
                                                            ->rows(6)
                                                            ->hint('Setiap baris baru = satu poin ketentuan')
                                                            ->afterStateHydrated(function ($component, $record) {
                                                                $default = "1. Harga di atas dapat berubah menyesuaikan volume pemesanan final.\n2. Penawaran harga ini berlaku selama 30 hari sejak diterbitkan.\n3. Pembayaran DP 50%, pelunasan sebelum barang dikirim.\n4. Barang yang sudah diproduksi tidak dapat dibatalkan sepihak.\n5. Pengiriman menggunakan ekspedisi rekanan Indoroster Indonesia.";
                                                                $component->state($record?->extra_data['terms_and_conditions'] ?? $default);
                                                            })
                                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['penawaran', 'quotation', 'purchase_order', 'certificate_of_origin'])),

                                                        Forms\Components\TextInput::make('custom_order_notes_title')
                                                            ->label('Judul Catatan Alur Pesanan')
                                                            ->afterStateHydrated(function ($component, $record) {
                                                                $component->state($record?->extra_data['order_notes_title'] ?? 'Catatan Alur Pesanan');
                                                            })
                                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['surat_pesanan', 'sales_order'])),
                                                        Forms\Components\Textarea::make('custom_order_notes')
                                                            ->label('Catatan Alur Pesanan (Isi)')
                                                            ->rows(5)
                                                            ->hint('Tampil di bagian bawah surat pesanan')
                                                            ->afterStateHydrated(function ($component, $record) {
                                                                $default = "1. Pesanan ini bersifat mengikat setelah DP 50% diterima.\n2. Jadwal produksi akan dikonfirmasi dalam 1x24 jam.\n3. Estimasi waktu produksi 3-7 hari kerja tergantung volume.\n4. Barang yang sudah dalam proses produksi tidak dapat dibatalkan.\n5. Pembayaran penuh dilakukan sebelum barang dikirim.";
                                                                $component->state($record?->extra_data['order_notes'] ?? $default);
                                                            })
                                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['surat_pesanan', 'sales_order'])),

                                                        Forms\Components\Repeater::make('custom_sections')
                                                            ->label('Bagian Konten Kustom Tambahan')
                                                            ->schema([
                                                                Forms\Components\TextInput::make('title')
                                                                    ->label('Judul Bagian')
                                                                    ->required()
                                                                    ->live(debounce: 500),
                                                                Forms\Components\Textarea::make('content')
                                                                    ->label('Isi Konten')
                                                                    ->rows(3)
                                                                    ->required()
                                                                    ->live(debounce: 500),
                                                            ])
                                                            ->createItemButtonLabel('Tambah Bagian Konten Kustom')
                                                            ->afterStateHydrated(function ($component, $record) {
                                                                $component->state($record?->extra_data['custom_sections'] ?? []);
                                                            })
                                                            ->live()
                                                            ->collapsible()
                                                            ->default([]),
                                                    ])
                                                    ->collapsible()
                                                    ->collapsed(false),
                                            ])->columnSpan(2),

                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Section::make('Rincian Biaya')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('subtotal')
                                                            ->label('Subtotal')
                                                            ->numeric()
                                                            ->disabled()
                                                            ->dehydrated()
                                                            ->prefix('Rp')
                                                            ->default(0),
                                                        Forms\Components\Checkbox::make('has_tax')
                                                            ->label('Tambahkan PPN')
                                                            ->default(false)
                                                            ->live()
                                                            ->afterStateUpdated(fn (Forms\Get $get, Forms\Set $set) => self::updateTotals($get, $set)),
                                                        Forms\Components\TextInput::make('discount')
                                                            ->label('Diskon Khusus')
                                                            ->numeric()
                                                            ->prefix('Rp')
                                                            ->default(0)
                                                            ->live()
                                                            ->afterStateUpdated(fn (Forms\Get $get, Forms\Set $set) => self::updateTotals($get, $set)),
                                                        Forms\Components\TextInput::make('tax_amount')
                                                            ->label(function (Forms\Get $get) {
                                                                $taxRate = 11.00;
                                                                $templateId = $get('document_template_id');
                                                                if ($templateId) {
                                                                    $template = DocumentTemplate::find($templateId);
                                                                    if ($template) {
                                                                        $taxRate = floatval($template->tax_rate);
                                                                    }
                                                                }

                                                                return 'Nilai PPN ('.number_format($taxRate, 0).'%)';
                                                            })
                                                            ->numeric()
                                                            ->disabled()
                                                            ->dehydrated()
                                                            ->prefix('Rp')
                                                            ->default(0),
                                                        Forms\Components\TextInput::make('grand_total')
                                                            ->label('Total Akhir')
                                                            ->numeric()
                                                            ->disabled()
                                                            ->dehydrated()
                                                            ->prefix('Rp')
                                                            ->default(0),
                                                    ]),

                                                Forms\Components\Section::make('Tanda Tangan & Catatan')
                                                    ->schema([
                                                        Forms\Components\FileUpload::make('signature_path')
                                                            ->label('Upload Tanda Tangan Digital')
                                                            ->disk('public')
                                                            ->directory('manual-signatures')
                                                            ->image()
                                                            ->maxSize(2048)
                                                            ->fetchFileInformation(false)
                                                            ->placeholder('Opsional (Dibiarkan kosong untuk TTD manual)'),
                                                        Forms\Components\Textarea::make('notes')
                                                            ->label('Catatan Kaki')
                                                            ->placeholder('Catatan atau petunjuk pembayaran lainnya...'),
                                                    ]),
                                            ])->columnSpan(1),
                                    ]),
                            ]),
                        Forms\Components\Tabs\Tab::make('Desain & Posisi Elemen')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                Forms\Components\Grid::make(12)
                                    ->schema([
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Section::make('Kertas & Layout')
                                                    ->schema([
                                                        Forms\Components\Select::make('orientation')
                                                            ->label('Orientasi Kertas')
                                                            ->options([
                                                                'portrait' => 'Portrait (Vertikal)',
                                                                'landscape' => 'Landscape (Horizontal)',
                                                            ])
                                                            ->live(),
                                                        Forms\Components\Select::make('paper_size')
                                                            ->label('Ukuran Kertas')
                                                            ->options([
                                                                'a4' => 'A4',
                                                                'letter' => 'Letter',
                                                                'legal' => 'Legal',
                                                            ])
                                                            ->live(),
                                                    ])->columns(2),

                                                Forms\Components\Section::make('Margin Halaman (mm)')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('margins.top')->label('Atas (Top)')->numeric()->suffix('mm')->live(),
                                                        Forms\Components\TextInput::make('margins.bottom')->label('Bawah (Bottom)')->numeric()->suffix('mm')->live(),
                                                        Forms\Components\TextInput::make('margins.left')->label('Kiri (Left)')->numeric()->suffix('mm')->live(),
                                                        Forms\Components\TextInput::make('margins.right')->label('Kanan (Right)')->numeric()->suffix('mm')->live(),
                                                    ])->columns(2)->collapsible()->collapsed(),

                                                Forms\Components\Section::make('Pengaturan Stempel')
                                                    ->schema([
                                                        Forms\Components\TextInput::make('stamp_opacity')->label('Transparansi Stempel')->numeric()->step(0.05)->minValue(0.1)->maxValue(1.0)->live(),
                                                        Forms\Components\TextInput::make('stamp_rotation')->label('Rotasi (Derajat)')->numeric()->suffix('°')->live(),
                                                    ])->columns(2)->collapsible()->collapsed(),

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

                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Section::make('Visual Canva-like Designer')
                                                    ->description('Geser (Drag) elemen-elemen di canvas A4 untuk memosisikannya secara kustom untuk dokumen ini.')
                                                    ->schema([
                                                        Forms\Components\ViewField::make('elements')
                                                            ->view('components.document-designer')
                                                            ->viewData(['isDocumentMode' => true])
                                                            ->columnSpanFull(),
                                                    ]),
                                            ])->columnSpan(8),
                                    ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function updateItemTotal(Forms\Get $get, Forms\Set $set): void
    {
        $qty = floatval($get('quantity') ?? 0);
        $price = floatval($get('price') ?? 0);
        $set('total', $qty * $price);
    }

    public static function updateTotals(Forms\Get $get, Forms\Set $set): void
    {
        // Detect if we are inside a repeater item (if 'items' is not found directly)
        $isChild = false;
        $items = $get('items');
        if (is_null($items)) {
            $items = $get('../../items') ?? [];
            $isChild = true;
        }

        $subtotal = 0;
        foreach ($items as $item) {
            $qty = floatval($item['quantity'] ?? 0);
            $price = floatval($item['price'] ?? 0);
            $subtotal += ($qty * $price);
        }

        $discountPath = $isChild ? '../../discount' : 'discount';
        $hasTaxPath = $isChild ? '../../has_tax' : 'has_tax';
        $templateIdPath = $isChild ? '../../document_template_id' : 'document_template_id';

        $subtotalPath = $isChild ? '../../subtotal' : 'subtotal';
        $taxAmountPath = $isChild ? '../../tax_amount' : 'tax_amount';
        $grandTotalPath = $isChild ? '../../grand_total' : 'grand_total';

        $discount = floatval($get($discountPath) ?? 0);
        $hasTax = (bool) $get($hasTaxPath);

        // Fetch tax rate dynamically from selected template
        $templateId = $get($templateIdPath);
        $taxRate = 11.00;
        if ($templateId) {
            $template = DocumentTemplate::find($templateId);
            if ($template) {
                $taxRate = floatval($template->tax_rate);
            }
        }

        $taxAmount = 0;
        if ($hasTax) {
            $taxAmount = max(0, $subtotal - $discount) * ($taxRate / 100);
        }

        $grandTotal = max(0, $subtotal - $discount) + $taxAmount;

        $set($subtotalPath, $subtotal);
        $set($taxAmountPath, $taxAmount);
        $set($grandTotalPath, $grandTotal);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('document_number')
                    ->label('No. Dokumen')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'faktur' => 'success',
                        'surat_jalan' => 'danger',
                        'kwitansi' => 'warning',
                        'penawaran' => 'info',
                        'surat_pesanan' => 'primary',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'faktur' => '🧾 Faktur',
                        'surat_jalan' => '🚚 Surat Jalan',
                        'kwitansi' => '💰 Kwitansi',
                        'penawaran' => '💼 Penawaran',
                        'surat_pesanan' => '📝 Surat Pesanan',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('client_name')
                    ->label('Nama Klien')
                    ->searchable(),
                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total Akhir')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('document_date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'final' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state)),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe Dokumen')
                    ->options([
                        'faktur' => '🧾 Faktur Penjualan',
                        'surat_jalan' => '🚚 Surat Jalan',
                        'kwitansi' => '💰 Kwitansi Pembayaran',
                        'penawaran' => '💼 Penawaran Harga',
                        'surat_pesanan' => '📝 Surat Pesanan Offline',
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'final' => 'Final',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn (ManualDocument $record) => route('print.manual-document', $record))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListManualDocuments::route('/'),
            'create' => Pages\CreateManualDocument::route('/create'),
            'edit' => Pages\EditManualDocument::route('/{record}/edit'),
        ];
    }
}
