<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WaOrderResource\Pages;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\URL;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;

class WaOrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = '🟢 Pesanan WhatsApp';

    protected static ?string $modelLabel = 'Pesanan WhatsApp';

    protected static ?string $pluralModelLabel = 'Pesanan WhatsApp';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('order_source', 'whatsapp')
            ->with(['items.product', 'items.variant', 'user', 'courierUser', 'invoice', 'shippingLabel', 'batches']);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getModel()::where('order_source', 'whatsapp')
            ->whereIn('status', ['draft', 'pending_payment', 'processing', 'shipped'])
            ->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // SECTION 1: DATA PEMBELI & LOKASI TITIK GPS
                Forms\Components\Section::make('1. Data Pembeli & Alamat Pengiriman Proyek')
                    ->description('Isi identitas pemesan, dropdown wilayah berjenjang, dan koordinat Google Maps untuk armada pengiriman pabrik.')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('Nomor Pesanan WA')
                            ->default(fn () => Order::generateWaOrderNumber())
                            ->disabled()
                            ->dehydrated()
                            ->required(),

                        Forms\Components\Select::make('user_id')
                            ->label('Hubungkan ke Akun Pelanggan Terdaftar (Opsional)')
                            ->options(User::where('role', 'customer')->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                if ($state) {
                                    $user = User::with('addresses')->find($state);
                                    if ($user) {
                                        $set('shipping_name', $user->name);
                                        $set('shipping_phone', $user->phone);
                                        $set('shipping_email', $user->email);
                                        $addr = $user->addresses()->where('is_default', true)->first() ?: $user->addresses()->first();
                                        if ($addr) {
                                            $set('shipping_address', $addr->full_address);
                                            $set('shipping_province', $addr->province);
                                            $set('shipping_city', $addr->city);
                                            $set('shipping_district', $addr->district);
                                            $set('shipping_village', $addr->village);
                                            $set('shipping_postal_code', $addr->postal_code);
                                            $set('shipping_latitude', $addr->latitude);
                                            $set('shipping_longitude', $addr->longitude);
                                        }
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('shipping_name')
                            ->label('Nama Pembeli / PIC Lapangan / Mandor')
                            ->placeholder('Contoh: Bpk. Hendra Saputra (Mandor Proyek Ruko)')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('shipping_phone')
                            ->label('Nomor WhatsApp Aktif')
                            ->placeholder('Contoh: 081234567890')
                            ->tel()
                            ->required()
                            ->maxLength(25),

                        Forms\Components\TextInput::make('shipping_email')
                            ->label('Alamat Email (Opsional)')
                            ->placeholder('nama@email.com')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('shipping_address')
                            ->label('Alamat Lengkap Titik Proyek / Tujuan Kirim')
                            ->placeholder('Contoh: Jl. Raya Mauk KM. 12, Kampung Gaga, RT 02/RW 03, Patokan depan Masjid Al-Ikhlas...')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        // DROPDOWN WILAYAH BERJENJANG SEPERTI DI BUKU ALAMAT
                        Forms\Components\Select::make('shipping_province')
                            ->label('Provinsi')
                            ->options(fn () => Province::orderBy('name')->pluck('name', 'name'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function (Set $set) {
                                $set('shipping_city', null);
                                $set('shipping_district', null);
                                $set('shipping_village', null);
                                $set('shipping_postal_code', null);
                            })
                            ->required(),

                        Forms\Components\Select::make('shipping_city')
                            ->label('Kota / Kabupaten')
                            ->options(function (Get $get) {
                                $provName = $get('shipping_province');
                                if (! $provName) {
                                    return [];
                                }
                                $prov = Province::where('name', $provName)->first();
                                if (! $prov) {
                                    return [];
                                }

                                return City::where('province_code', $prov->code)->orderBy('name')->pluck('name', 'name');
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->disabled(fn (Get $get) => empty($get('shipping_province')))
                            ->afterStateUpdated(function (Set $set) {
                                $set('shipping_district', null);
                                $set('shipping_village', null);
                                $set('shipping_postal_code', null);
                            })
                            ->required(),

                        Forms\Components\Select::make('shipping_district')
                            ->label('Kecamatan')
                            ->options(function (Get $get) {
                                $cityName = $get('shipping_city');
                                if (! $cityName) {
                                    return [];
                                }
                                $city = City::where('name', $cityName)->first();
                                if (! $city) {
                                    return [];
                                }

                                return District::where('city_code', $city->code)->orderBy('name')->pluck('name', 'name');
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->disabled(fn (Get $get) => empty($get('shipping_city')))
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $set('shipping_village', null);
                                if ($state) {
                                    $cityName = $get('shipping_city');
                                    $city = $cityName ? City::where('name', $cityName)->first() : null;
                                    $distQuery = District::where('name', $state);
                                    if ($city) {
                                        $distQuery->where('city_code', $city->code);
                                    }
                                    $dist = $distQuery->first();
                                    if ($dist) {
                                        $villages = Village::where('district_code', $dist->code)->get();
                                        $codes = $villages->map(function ($v) {
                                            $meta = is_string($v->meta) ? json_decode($v->meta, true) : $v->meta;

                                            return $meta['pos'] ?? null;
                                        })->filter()->unique()->values()->toArray();

                                        if (! empty($codes)) {
                                            $set('shipping_postal_code', (string) $codes[0]);
                                        }
                                    }
                                }
                            })
                            ->required(),

                        Forms\Components\Select::make('shipping_village')
                            ->label('Kelurahan / Desa')
                            ->options(function (Get $get) {
                                $cityName = $get('shipping_city');
                                $distName = $get('shipping_district');
                                if (! $distName) {
                                    return [];
                                }
                                $distQuery = District::where('name', $distName);
                                if ($cityName) {
                                    $city = City::where('name', $cityName)->first();
                                    if ($city) {
                                        $distQuery->where('city_code', $city->code);
                                    }
                                }
                                $dist = $distQuery->first();
                                if (! $dist) {
                                    return [];
                                }

                                return Village::where('district_code', $dist->code)->orderBy('name')->pluck('name', 'name');
                            })
                            ->searchable()
                            ->preload()
                            ->live()
                            ->disabled(fn (Get $get) => empty($get('shipping_district')))
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if ($state) {
                                    $cityName = $get('shipping_city');
                                    $distName = $get('shipping_district');
                                    $city = $cityName ? City::where('name', $cityName)->first() : null;
                                    $dist = null;
                                    if ($distName) {
                                        $distQuery = District::where('name', $distName);
                                        if ($city) {
                                            $distQuery->where('city_code', $city->code);
                                        }
                                        $dist = $distQuery->first();
                                    }
                                    if ($dist) {
                                        $v = Village::where('district_code', $dist->code)->where('name', $state)->first();
                                        if ($v) {
                                            $meta = is_string($v->meta) ? json_decode($v->meta, true) : $v->meta;
                                            if (! empty($meta['pos'])) {
                                                $set('shipping_postal_code', (string) $meta['pos']);
                                            }
                                        }
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('shipping_postal_code')
                            ->label('Kode Pos')
                            ->placeholder('Contoh: 15520')
                            ->datalist(function (Get $get) {
                                $cityName = $get('shipping_city');
                                $distName = $get('shipping_district');
                                if (! $distName) {
                                    return [];
                                }
                                $city = $cityName ? City::where('name', $cityName)->first() : null;
                                $distQuery = District::where('name', $distName);
                                if ($city) {
                                    $distQuery->where('city_code', $city->code);
                                }
                                $dist = $distQuery->first();
                                if (! $dist) {
                                    return [];
                                }

                                $villages = Village::where('district_code', $dist->code)->get();

                                return $villages->map(function ($v) {
                                    $meta = is_string($v->meta) ? json_decode($v->meta, true) : $v->meta;

                                    return $meta['pos'] ?? null;
                                })->filter()->unique()->values()->toArray();
                            })
                            ->helperText(function (Get $get) {
                                $cityName = $get('shipping_city');
                                $distName = $get('shipping_district');
                                if (! $distName) {
                                    return 'Otomatis terdeteksi saat memilih kecamatan / desa, bisa diketik manual.';
                                }
                                $city = $cityName ? City::where('name', $cityName)->first() : null;
                                $distQuery = District::where('name', $distName);
                                if ($city) {
                                    $distQuery->where('city_code', $city->code);
                                }
                                $dist = $distQuery->first();
                                if (! $dist) {
                                    return 'Otomatis terdeteksi saat memilih kecamatan / desa, bisa diketik manual.';
                                }

                                $villages = Village::where('district_code', $dist->code)->get();
                                $codes = $villages->map(function ($v) {
                                    $meta = is_string($v->meta) ? json_decode($v->meta, true) : $v->meta;

                                    return $meta['pos'] ?? null;
                                })->filter()->unique()->values()->toArray();

                                if (count($codes) > 1) {
                                    return '💡 Rekomendasi Kode Pos untuk Kec. '.$distName.': '.implode(', ', $codes).' (otomatis terisi saat pilih desa, atau pilih/ketik manual).';
                                } elseif (count($codes) === 1) {
                                    return '✅ Kode Pos terdeteksi otomatis untuk wilayah ini: '.$codes[0];
                                }

                                return 'Otomatis terdeteksi saat memilih kecamatan / desa, bisa diketik manual.';
                            })
                            ->maxLength(10),

                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('shipping_latitude')
                                    ->label('Titik Koordinat Latitude GPS')
                                    ->placeholder('-6.2845600')
                                    ->numeric()
                                    ->helperText('Contoh: -6.2845600 (Bisa copy dari titik pin Google Maps)'),

                                Forms\Components\TextInput::make('shipping_longitude')
                                    ->label('Titik Koordinat Longitude GPS')
                                    ->placeholder('107.1523400')
                                    ->numeric()
                                    ->helperText('Contoh: 107.1523400 (Untuk tombol navigasi supir armada truk)'),
                            ]),
                    ])->columns(3),

                // SECTION 2: DAFTAR BARANG PESANAN (KATALOG DB ATAU MANUAL CUSTOM)
                Forms\Components\Section::make('2. Rincian Item Pesanan (Katalog DB atau Custom Manual)')
                    ->description('Anda dapat memilih motif dari database atau mengetik nama produk & varian kustom secara bebas.')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship('items')
                            ->label('Daftar Produk / Roster Pesanan')
                            ->schema([
                                Forms\Components\Radio::make('is_custom_item')
                                    ->label('Metode Input Produk')
                                    ->options([
                                        0 => '📦 Pilih dari Katalog Database IndoRoster',
                                        1 => '✏️ Ketik Manual / Motif Custom Khusus',
                                    ])
                                    ->default(1)
                                    ->live()
                                    ->inline(),

                                // PILIHAN DARI DATABASE (Tampil HANYA saat is_custom_item == 0)
                                Forms\Components\Select::make('product_id')
                                    ->label('Pilih Produk dari Katalog')
                                    ->options(Product::where('is_active', true)->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->columnSpan(2)
                                    ->visible(fn (Get $get) => (int) $get('is_custom_item') === 0)
                                    ->required(fn (Get $get) => (int) $get('is_custom_item') === 0)
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        if ($state) {
                                            $prod = Product::find($state);
                                            if ($prod) {
                                                $set('product_name', $prod->name);
                                                $set('product_price', $prod->price);
                                                $qty = (int) ($get('quantity') ?: 1);
                                                $set('subtotal', $prod->price * $qty);
                                            }
                                        } else {
                                            $set('product_name', null);
                                            $set('product_variant_id', null);
                                            $set('custom_variant_name', null);
                                            $set('product_price', null);
                                            $set('subtotal', 0);
                                        }
                                    }),

                                Forms\Components\Select::make('product_variant_id')
                                    ->label('Pilih Varian Motif')
                                    ->options(function (Get $get) {
                                        $prodId = $get('product_id');
                                        if (! $prodId) {
                                            return [];
                                        }

                                        return ProductVariant::where('product_id', $prodId)->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->live()
                                    ->columnSpan(1)
                                    ->visible(fn (Get $get) => (int) $get('is_custom_item') === 0)
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        if ($state) {
                                            $var = ProductVariant::find($state);
                                            if ($var) {
                                                if ($var->price > 0) {
                                                    $set('product_price', $var->price);
                                                    $qty = (int) ($get('quantity') ?: 1);
                                                    $set('subtotal', $var->price * $qty);
                                                }
                                                $set('custom_variant_name', $var->name);
                                            }
                                        } else {
                                            $set('custom_variant_name', null);
                                        }
                                    }),

                                // PILIHAN KETIK MANUAL / CUSTOM (Tampil HANYA saat is_custom_item == 1)
                                Forms\Components\TextInput::make('product_name')
                                    ->label('Nama Produk / Roster')
                                    ->placeholder('Contoh: Roster Custom Motif Kawung 20x20 Abu')
                                    ->visible(fn (Get $get) => (int) $get('is_custom_item') === 1)
                                    ->required(fn (Get $get) => (int) $get('is_custom_item') === 1)
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('custom_variant_name')
                                    ->label('Varian / Warna / Spek Custom')
                                    ->placeholder('Contoh: Abu Natural / Putih Teraso / Merah Terakota')
                                    ->visible(fn (Get $get) => (int) $get('is_custom_item') === 1)
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('product_price')
                                    ->label('Harga Satuan (Rp)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $qty = (int) ($get('quantity') ?: 1);
                                        $set('subtotal', (float) $state * $qty);
                                    }),

                                Forms\Components\TextInput::make('quantity')
                                    ->label('Jumlah (Pcs / Keping)')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(1)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $price = (float) ($get('product_price') ?: 0);
                                        $set('subtotal', $price * (int) $state);
                                    }),

                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Subtotal (Rp)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),

                                Forms\Components\TextInput::make('item_notes')
                                    ->label('Catatan Item (Opsional)')
                                    ->placeholder('Contoh: Packing palet kayu / tebal siku 10cm')
                                    ->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->addActionLabel('+ Tambah Item Roster / Barang Lain')
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::calculateGrandTotal($get, $set);
                            }),

                        // KALKULASI ONGKIR & TOTAL
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('subtotal')
                                    ->label('Subtotal Produk (Rp)')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),

                                Forms\Components\TextInput::make('shipping_cost')
                                    ->label('Ongkir Armada Truk Pabrik (Rp)')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->default(0)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateGrandTotal($get, $set)),

                                Forms\Components\TextInput::make('discount_amount')
                                    ->label('Potongan Diskon Khusus (Rp)')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->default(0)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateGrandTotal($get, $set)),

                                Forms\Components\TextInput::make('grand_total')
                                    ->label('GRAND TOTAL TAGIHAN (Rp)')
                                    ->prefix('Rp')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->extraInputAttributes(['style' => 'font-weight: 800; font-size: 1.1rem; color: #ea580c;'])
                                    ->columnSpanFull()
                                    ->required(),
                            ]),
                    ]),

                // SECTION 3: SKEMA PEMENUHAN & REPEATER PO BATCH DINAMIS
                Forms\Components\Section::make('3. Skema Pemenuhan Pesanan & Penjadwalan Truk Armada')
                    ->description('Tentukan tipe pemenuhan. Untuk PO Batch, Anda dapat mengatur rincian rit truk dan kuantitas per batch.')
                    ->schema([
                        Forms\Components\Radio::make('fulfillment_type')
                            ->label('Tipe Pemenuhan Pesanan (*Fulfillment Mode*)')
                            ->options([
                                'ready_stock' => '📦 Ready Stock (Barang Siap Kirim dari Gudang)',
                                'po_single' => '🏭 PO Single (Pre-Order Produksi Pabrik — 1x Kirim)',
                                'po_batch' => '🚛 PO Bertahap / Multi-Batch (Kirim Per Rit Truk Armada)',
                            ])
                            ->default('ready_stock')
                            ->live()
                            ->required(),

                        // Fields untuk Ready Stock
                        Forms\Components\DatePicker::make('ready_shipping_date')
                            ->label('Jadwal Keberangkatan Truk Gudang')
                            ->visible(fn (Get $get) => $get('fulfillment_type') === 'ready_stock'),

                        // Fields untuk PO Single
                        Forms\Components\Grid::make(3)
                            ->visible(fn (Get $get) => $get('fulfillment_type') === 'po_single')
                            ->schema([
                                Forms\Components\DatePicker::make('production_start_date')
                                    ->label('Tgl Mulai Cetak / Produksi'),
                                Forms\Components\DatePicker::make('ready_shipping_date')
                                    ->label('Tgl Selesai Cetak & QC'),
                                Forms\Components\DatePicker::make('estimated_delivery_date')
                                    ->label('Estimasi Tiba di Titik Proyek'),
                            ]),

                        // Fields & Repeater untuk PO Batch Dinamis
                        Forms\Components\Group::make([
                            Forms\Components\Grid::make(2)
                                ->schema([
                                    Forms\Components\TextInput::make('batch_count')
                                        ->label('Rencana Jumlah Rit Truk (Batch)')
                                        ->numeric()
                                        ->default(2)
                                        ->helperText('Jumlah rit truk armada pengiriman yang direncanakan'),

                                    Forms\Components\Textarea::make('fulfillment_notes')
                                        ->label('Catatan Alokasi Lapangan / Mandor')
                                        ->placeholder('Contoh: Tiap rit truk muat 1.500 pcs sesuai kesiapan akses jalan proyek.')
                                        ->rows(1),
                                ]),

                            Forms\Components\Repeater::make('batches')
                                ->relationship('batches')
                                ->orderColumn('batch_number')
                                ->label('Rincian Kuantitas Muatan & Jadwal Tiap Rit Truk')
                                ->schema([
                                    Forms\Components\TextInput::make('batch_name')
                                        ->label('Nama Rit / Batch')
                                        ->placeholder('Contoh: Batch 1 / Rit #1')
                                        ->required()
                                        ->columnSpan(2),

                                    Forms\Components\TextInput::make('quantity')
                                        ->label('Muatan (Pcs)')
                                        ->numeric()
                                        ->required()
                                        ->columnSpan(2),

                                    Forms\Components\DatePicker::make('production_start_date')
                                        ->label('Tgl Mulai Cetak')
                                        ->columnSpan(2),

                                    Forms\Components\DatePicker::make('estimated_dispatch_date')
                                        ->label('Est. Berangkat Truk')
                                        ->columnSpan(2),

                                    Forms\Components\DatePicker::make('estimated_delivery_date')
                                        ->label('Est. Tiba di Proyek')
                                        ->columnSpan(2),

                                    Forms\Components\Select::make('status')
                                        ->label('Status Rit / Batch')
                                        ->options([
                                            'pending_production' => '🟡 Menunggu Produksi',
                                            'producing' => '🔵 Sedang Diproduksi',
                                            'ready_to_ship' => '📦 Siap Kirim',
                                            'shipped' => '🟣 Sedang Dikirim',
                                            'delivered' => '🟢 Diterima di Proyek',
                                        ])
                                        ->default('pending_production')
                                        ->required()
                                        ->columnSpan(2),
                                ])
                                ->columns(6)
                                ->columnSpanFull()
                                ->addActionLabel('+ Tambah Rit Truk / Batch Baru')
                                ->defaultItems(2),
                        ])
                            ->visible(fn (Get $get) => $get('fulfillment_type') === 'po_batch')
                            ->columnSpanFull(),

                        // Kurir Assignment Global (Khusus Ready Stock & PO Single jika ingin langsung ditugaskan)
                        Forms\Components\Grid::make(3)
                            ->visible(fn (Get $get) => in_array($get('fulfillment_type'), ['ready_stock', 'po_single']))
                            ->schema([
                                Forms\Components\Select::make('courier_id')
                                    ->label('Pilih Akun Kurir / Supir Truk Internal')
                                    ->options(User::where('role', 'courier')->pluck('name', 'id'))
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(function ($state, Set $set) {
                                        if ($state) {
                                            $c = User::find($state);
                                            if ($c) {
                                                $set('courier', $c->name.' (Armada Pabrik)');
                                                $set('courier_phone', $c->phone);
                                            }
                                        }
                                    }),

                                Forms\Components\TextInput::make('courier')
                                    ->label('Nama Ekspedisi / Supir Truk')
                                    ->default('Armada Truk IndoRoster'),

                                Forms\Components\TextInput::make('courier_phone')
                                    ->label('No. WhatsApp / HP Supir Truk')
                                    ->placeholder('0812XXXXXXXX'),
                            ]),
                    ]),

                // SECTION 4: STATUS PESANAN, DRAFT & SKEMA PEMBAYARAN DP / TERMIN
                Forms\Components\Section::make('4. Status Pesanan & Skema Pembayaran (DP / Termin)')
                    ->description('Atur apakah pesanan disimpan sebagai Draft atau langsung diterbitkan, serta atur opsi pembayaran DP / bertahap.')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status Pesanan Terkini')
                            ->options([
                                'draft' => '📜 Surat Penawaran Harga (Quotation) / Draft Pesanan',
                                'pending_payment' => '🟡 Menunggu Pembayaran DP / Pelunasan',
                                'processing' => '🔵 Sedang Diproses / Diproduksi Pabrik',
                                'shipped' => '🟣 Dalam Pengiriman Armada Truk',
                                'delivered' => '🟢 Sampai di Titik Proyek',
                                'completed' => '✅ Pesanan Selesai (100% Lengkap)',
                                'cancelled' => '❌ Dibatalkan',
                            ])
                            ->default('draft')
                            ->required()
                            ->live(),

                        Forms\Components\Select::make('payment_scheme')
                            ->label('Skema Pembayaran')
                            ->options([
                                'quotation' => '📜 Surat Penawaran Harga (Tahap Penawaran / Belum Deal)',
                                'full' => '💵 Lunas Langsung di Muka (100%)',
                                'dp_50_50' => '💳 DP 50% di Awal + Pelunasan 50% saat Siap Kirim (2x Bayar)',
                                'termin_3x' => '🏗️ Termin 3x (DP 30% + Produksi 40% + Pelunasan 30%)',
                                'custom_dp' => '✏️ Kustom Nominal DP / Termin Bebas',
                            ])
                            ->default('quotation')
                            ->selectablePlaceholder(false)
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                self::calculatePaymentScheme($state ?: 'quotation', $get, $set);
                            }),

                        Forms\Components\TextInput::make('down_payment_amount')
                            ->label('Nominal DP / Pembayaran Diterima (Rp)')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->dehydrateStateUsing(fn ($state) => (float) ($state ?: 0))
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                $grandTotal = (float) ($get('grand_total') ?: 0);
                                $dp = (float) ($state ?: 0);
                                $remaining = max(0, $grandTotal - $dp);
                                $set('remaining_balance', $remaining);

                                if ($dp >= $grandTotal && $grandTotal > 0) {
                                    $set('payment_status', 'paid');
                                } else {
                                    $set('payment_status', 'unpaid');
                                }
                            }),

                        Forms\Components\TextInput::make('remaining_balance')
                            ->label('SISA TAGIHAN PELUNASAN (Rp)')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(0)
                            ->dehydrateStateUsing(fn ($state, Get $get) => (float) ($state !== null && $state !== '' ? $state : max(0, (float) ($get('grand_total') ?: 0) - (float) ($get('down_payment_amount') ?: 0))))
                            ->disabled()
                            ->dehydrated()
                            ->extraInputAttributes(['style' => 'font-weight: 800; color: #dc2626;']),

                        Forms\Components\Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'unpaid' => '🔴 Belum Ada Pembayaran / Tahap Penawaran',
                                'paid' => '🟢 Lunas (100%)',
                                'refunded' => '⚪ Dikembalikan / Refund',
                            ])
                            ->default('unpaid')
                            ->required(),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Invoice Untuk Pembeli')
                            ->placeholder('Contoh: Pembayaran DP 50% telah diverifikasi. Pelunasan dilakukan saat material tiba di lokasi.')
                            ->rows(2),

                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Catatan Internal Admin / Pabrik')
                            ->placeholder('Contoh: Disetujui Bpk. Hamid. Muatan truk Colt Diesel bak terbuka.')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function calculateGrandTotal(Get $get, Set $set): void
    {
        $items = $get('items') ?? [];
        $subtotal = 0;

        foreach ($items as $item) {
            $price = (float) ($item['product_price'] ?? 0);
            $qty = (int) ($item['quantity'] ?? 0);
            $subtotal += ($price * $qty);
        }

        $shipping = (float) ($get('shipping_cost') ?? 0);
        $discount = (float) ($get('discount_amount') ?? 0);
        $grandTotal = max(0, $subtotal + $shipping - $discount);

        $set('subtotal', $subtotal);
        $set('grand_total', $grandTotal);

        // Update payment scheme calculations
        $scheme = $get('payment_scheme') ?: 'full';
        self::calculatePaymentScheme($scheme, $get, $set, $grandTotal);
    }

    public static function calculatePaymentScheme(?string $scheme, Get $get, Set $set, ?float $total = null): void
    {
        $scheme = $scheme ?: 'quotation';
        $grandTotal = $total !== null ? $total : (float) ($get('grand_total') ?: 0);

        if ($scheme === 'quotation') {
            $set('down_payment_amount', 0);
            $set('remaining_balance', $grandTotal);
            $set('payment_status', 'unpaid');
        } elseif ($scheme === 'full') {
            $set('down_payment_amount', $grandTotal);
            $set('remaining_balance', 0);
            $set('payment_status', 'unpaid');
        } elseif ($scheme === 'dp_50_50') {
            $dp = round($grandTotal * 0.5);
            $set('down_payment_amount', $dp);
            $set('remaining_balance', $grandTotal - $dp);
            $set('payment_status', 'unpaid');
        } elseif ($scheme === 'termin_3x') {
            $dp = round($grandTotal * 0.3);
            $set('down_payment_amount', $dp);
            $set('remaining_balance', $grandTotal - $dp);
            $set('payment_status', 'unpaid');
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('No. Invoice / WA')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('shipping_name')
                    ->label('Nama Pembeli')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Order $record) => $record->shipping_phone.' — '.($record->shipping_city ?: ($record->shipping_province ?: 'Proyek'))),

                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Item')
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('fulfillment_type')
                    ->label('Pemenuhan')
                    ->badge()
                    ->formatStateUsing(fn (?string $state, Order $record) => match ($state) {
                        'po_single' => '🏭 PO Single',
                        'po_batch' => '🚛 PO Batch ('.($record->batches()->count() ?: ($record->batch_count ?: 1)).' Rit)',
                        default => '📦 Ready Stock',
                    })
                    ->color(fn (?string $state) => match ($state) {
                        'po_single' => 'info',
                        'po_batch' => 'warning',
                        default => 'success',
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status Pesanan')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'draft' => '⚪ Draft / Rancangan',
                        'pending_payment' => 'Menunggu Pembayaran',
                        'processing' => 'Diproses / Produksi',
                        'shipped' => 'Dalam Truk Kirim',
                        'delivered' => 'Sampai Lokasi',
                        'completed' => 'Selesai',
                        'cancelled' => 'Batal',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'draft' => 'gray',
                        'pending_payment' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'delivered', 'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->formatStateUsing(function (string $state, Order $record) {
                        if ($record->down_payment_amount > 0 && $record->remaining_balance > 0) {
                            return '🟡 DP Masuk: Rp '.number_format($record->down_payment_amount, 0, ',', '.');
                        }

                        return match ($state) {
                            'paid' => '🟢 Lunas',
                            'unpaid' => '🔴 Belum Bayar',
                            default => $state,
                        };
                    })
                    ->color(function (string $state, Order $record) {
                        if ($record->down_payment_amount > 0 && $record->remaining_balance > 0) {
                            return 'warning';
                        }

                        return $state === 'paid' ? 'success' : 'danger';
                    }),

                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Total Tagihan')
                    ->money('IDR')
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Order $record) => $record->remaining_balance > 0 ? 'Sisa: Rp '.number_format($record->remaining_balance, 0, ',', '.') : null),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Order')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status Pesanan')
                    ->options([
                        'draft' => '⚪ Draft / Rancangan',
                        'pending_payment' => '🟡 Menunggu Pembayaran / DP',
                        'processing' => '🔵 Sedang Diproses / Produksi',
                        'shipped' => '🟣 Dalam Pengiriman Armada',
                        'delivered' => '🟢 Sampai di Titik Proyek',
                        'completed' => '✅ Selesai',
                        'cancelled' => '❌ Dibatalkan',
                    ]),

                Tables\Filters\SelectFilter::make('payment_scheme')
                    ->label('Filter Skema Bayar')
                    ->options([
                        'full' => 'Lunas Langsung',
                        'dp_50_50' => 'DP 50%',
                        'termin_3x' => 'Termin 3x',
                        'custom_dp' => 'Kustom DP',
                    ]),

                Tables\Filters\SelectFilter::make('fulfillment_type')
                    ->label('Filter Tipe Pemenuhan')
                    ->options([
                        'ready_stock' => 'Ready Stock',
                        'po_single' => 'PO Single',
                        'po_batch' => 'PO Bertahap (Multi-Batch)',
                    ]),
            ])
            ->actions([
                // 0. TERBITKAN DARI DRAFT JIKA STATUS DRAFT
                Tables\Actions\Action::make('publish_draft')
                    ->label('Terbitkan')
                    ->icon('heroicon-m-rocket-launch')
                    ->color('success')
                    ->visible(fn (Order $record) => $record->status === 'draft')
                    ->requiresConfirmation()
                    ->modalHeading('Terbitkan Pesanan WhatsApp?')
                    ->modalDescription('Pesanan akan diubah statusnya menjadi "Menunggu Pembayaran / DP" dan siap dikirimkan tagihan resminya ke pembeli.')
                    ->action(function (Order $record) {
                        $record->update(['status' => 'pending_payment']);

                        Notification::make()
                            ->title('Pesanan Diterbitkan!')
                            ->body("Pesanan {$record->order_number} berhasil diterbitkan.")
                            ->success()
                            ->send();
                    }),

                // 0.4. KELOLA PEMBAYARAN & KASIR TERPADU (Hanya Muncul Jika Masih Ada Sisa Tagihan)
                Tables\Actions\Action::make('manage_payment')
                    ->label('💳 Catat Pembayaran')
                    ->icon('heroicon-m-credit-card')
                    ->color('warning')
                    ->visible(fn (Order $record) => $record->status !== 'draft' && max(0, (float) $record->grand_total - (float) $record->total_paid_amount) > 0)
                    ->modalHeading(fn (Order $record) => "Kelola Pembayaran & Kasir: {$record->order_number}")
                    ->modalDescription(
                        fn (Order $record) => 'Total Pesanan: Rp '.number_format($record->grand_total, 0, ',', '.').
                        ' | Sudah Masuk: Rp '.number_format($record->total_paid_amount, 0, ',', '.').
                        ' | Sisa Tagihan: Rp '.number_format(max(0, $record->grand_total - $record->total_paid_amount), 0, ',', '.')
                    )
                    ->form([
                        Forms\Components\TextInput::make('installment_title')
                            ->label('Judul / Urutan Pembayaran')
                            ->placeholder('Contoh: Pembayaran #1 (DP Awal) / Pembayaran #2')
                            ->default(fn (Order $record) => $record->payments()->count() === 0 ? 'Pembayaran #1 (DP Awal)' : 'Pembayaran #'.($record->payments()->count() + 1))
                            ->datalist([
                                'Pembayaran #1 (DP Awal)',
                                'Pembayaran #2',
                                'Pembayaran #3',
                                'Pembayaran #4',
                                'Pembayaran Pelunasan Akhir',
                                'Cicilan Tambahan',
                            ])
                            ->required()
                            ->helperText('Bisa ketik bebas sesuai transaksi transferan pembeli.'),

                        Forms\Components\TextInput::make('payment_amount')
                            ->label('Nominal Uang Masuk Kali Ini (Rp)')
                            ->prefix('Rp')
                            ->numeric()
                            ->default(fn (Order $record) => $record->payments()->count() === 0 ? ($record->down_payment_amount ?: round($record->grand_total * 0.5)) : ($record->remaining_balance ?: max(0, $record->grand_total - $record->total_paid_amount)))
                            ->required()
                            ->helperText('Masukkan nominal rupiah yang ditransfer pada transaksi ini.'),

                        Forms\Components\Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->options([
                                'BCA' => 'Transfer Bank BCA (IndoRoster)',
                                'Mandiri' => 'Transfer Bank Mandiri (IndoRoster)',
                                'BRI' => 'Transfer Bank BRI (IndoRoster)',
                                'Cash' => 'Tunai / Cash di Proyek / Pabrik',
                                'Other' => 'Metode Lainnya',
                            ])
                            ->default('BCA')
                            ->required(),

                        Forms\Components\Textarea::make('notes')
                            ->label('Catatan Bukti Transfer / No. Rekening Pengirim')
                            ->placeholder('Contoh: Transfer DP via M-Banking BCA tgl 28/08')
                            ->columnSpanFull(),
                    ])
                    ->modalSubmitActionLabel('Simpan Transaksi & Terbitkan Kuitansi')
                    ->action(function (array $data, Order $record) {
                        $grandTotal = (float) $record->grand_total;
                        $amountThisTime = (float) ($data['payment_amount'] ?? 0);
                        $prevSum = (float) $record->payments()->whereIn('status', ['settlement', 'capture', 'paid', 'success'])->sum('gross_amount');
                        $newTotalPaid = $prevSum + $amountThisTime;
                        $remaining = max(0, $grandTotal - $newTotalPaid);
                        $isPaid = ($remaining <= 0 && $grandTotal > 0);

                        $payCount = $record->payments()->count() + 1;
                        $transId = 'PAY-WA-'.str_replace(['INV-WA-', 'INV-'], '', $record->order_number).'-'.$payCount;

                        $payment = Payment::create([
                            'order_id' => $record->id,
                            'transaction_id' => $transId,
                            'payment_type' => 'bank_transfer',
                            'bank' => $data['payment_method'],
                            'gross_amount' => $amountThisTime,
                            'status' => 'settlement',
                            'paid_at' => now(),
                            'raw_response' => [
                                'title' => $data['installment_title'] ?? ('Pembayaran #'.$payCount),
                                'notes' => $data['notes'] ?? null,
                                'remaining_after' => $remaining,
                            ],
                        ]);

                        $updateOrder = [
                            'down_payment_amount' => $newTotalPaid,
                            'remaining_balance' => $remaining,
                            'payment_status' => $isPaid ? 'paid' : 'unpaid',
                            'paid_at' => $isPaid ? now() : null,
                        ];

                        if ($record->status === 'draft' || $record->status === 'pending_payment') {
                            $updateOrder['status'] = 'processing';
                        }

                        $record->update($updateOrder);

                        if ($invoice = $record->invoice) {
                            $invoice->update([
                                'down_payment_amount' => $newTotalPaid,
                                'remaining_balance' => $remaining,
                                'status' => $isPaid ? 'paid' : 'sent',
                                'paid_at' => $isPaid ? now() : null,
                            ]);
                        }

                        Notification::make()
                            ->title('Pembayaran Berhasil Dicatat! 💳')
                            ->body('Uang Masuk: Rp '.number_format($amountThisTime, 0, ',', '.').' | Total Masuk: Rp '.number_format($newTotalPaid, 0, ',', '.').' | Sisa: Rp '.number_format($remaining, 0, ',', '.'))
                            ->success()
                            ->persistent()
                            ->actions([
                                Tables\Actions\Action::make('print_receipt_'.$payment->id)
                                    ->label('🖨️ Cetak Kuitansi '.$payment->receipt_number)
                                    ->url(route('print.receipt', $payment), shouldOpenInNewTab: true)
                                    ->button()
                                    ->color('success'),
                            ])
                            ->send();
                    }),

                Tables\Actions\ViewAction::make()
                    ->label('Kelola / Detail')
                    ->icon('heroicon-m-eye')
                    ->color('primary'),

                // 1. KIRIM UPDATE WHATSAPP (1-CLICK DENGAN LINK INVOICE RESMI & LACAK PESANAN)
                Tables\Actions\Action::make('send_whatsapp')
                    ->label('Kirim WA')
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('template')
                            ->label('Pilih Tahapan Notifikasi WhatsApp')
                            ->options([
                                'invoice_bill' => '📄 1. Tagihan & Surat Invoice Resmi Baru (Termasuk Rincian DP)',
                                'production' => '🏭 2. Update Sedang Diproduksi di Pabrik',
                                'shipping' => '🚛 3. Update Armada Truk Meluncur ke Proyek',
                                'delivered' => '✅ 4. Update Material Telah Sampai di Lokasi',
                                'custom' => '✏️ 5. Pesan Custom Bebas',
                            ])
                            ->default(fn (Order $record) => match ($record->status) {
                                'processing' => 'production',
                                'shipped' => 'shipping',
                                'delivered', 'completed' => 'delivered',
                                default => 'invoice_bill',
                            })
                            ->live()
                            ->required(),

                        Forms\Components\Textarea::make('custom_message')
                            ->label('Isi Pesan WhatsApp')
                            ->rows(8)
                            ->default(function (Get $get, Order $record): string {
                                $tpl = $get('template') ?: 'invoice_bill';
                                $name = $record->shipping_name;
                                $orderNum = $record->order_number;
                                $total = 'Rp '.number_format($record->grand_total, 0, ',', '.');
                                $dpInfo = '';
                                if ($record->down_payment_amount > 0 && $record->remaining_balance > 0) {
                                    $dpInfo = "\n*Nominal DP Diterima:* Rp ".number_format($record->down_payment_amount, 0, ',', '.')."\n*Sisa Tagihan Pelunasan:* Rp ".number_format($record->remaining_balance, 0, ',', '.');
                                }

                                $trackingUrl = url('/lacak-pesanan?order_number='.$orderNum.'&contact='.urlencode($record->shipping_phone));
                                $printUrl = route('print.order', ['order' => $record->id]);

                                return match ($tpl) {
                                    'invoice_bill' => "Halo Bpk/Ibu {$name},\n\nTerima kasih telah memesan roster beton di Pabrik IndoRoster.\n\nBerikut rincian pesanan Anda:\n*No. Pesanan:* {$orderNum}\n*Total Tagihan:* {$total}{$dpInfo}\n\n📄 *Download PDF Invoice Resmi (TTD & Stempel):*\n{$printUrl}\n\n🔍 *Lacak Status Live Pesanan:*\n{$trackingUrl}\n\nUntuk pembayaran via transfer bank dapat ditujukan ke rekening resmi IndoRoster. Terima kasih!",
                                    'production' => "Halo Bpk/Ibu {$name},\n\nKabar baik! Pesanan roster Anda (*No. Invoice: {$orderNum}*) saat ini telah masuk ke tahap *PROSES PRODUKSI & CETAK* di Pabrik IndoRoster.\n\n🔍 *Pantau Progress Pesanan:* {$trackingUrl}",
                                    'shipping' => "Halo Bpk/Ibu {$name},\n\nArmada truk pabrik IndoRoster telah dimuat dan sedang *MELUNCUR KE LOKASI PROYEK ANDA* ({$record->shipping_address}).\n\n*Supir/Kurir:* {$record->courier} ({$record->courier_phone})\n\n🔍 *Pantau Rute Armada Live:* {$trackingUrl}",
                                    'delivered' => "Halo Bpk/Ibu {$name},\n\nMaterial roster pesanan Anda (*No. Invoice: {$orderNum}*) telah *BERHASIL TIBA & DITERIMA* di titik proyek dengan selamat.\n\nTerima kasih telah mempercayakan pengadaan material kepada IndoRoster!",
                                    default => "Halo {$name}, salam hangat dari tim IndoRoster...",
                                };
                            }),
                    ])
                    ->action(function (array $data, Order $record) {
                        $phone = preg_replace('/[^0-9]/', '', $record->shipping_phone);
                        if (str_starts_with($phone, '0')) {
                            $phone = '62'.substr($phone, 1);
                        }

                        $message = urlencode($data['custom_message']);
                        $waUrl = "https://wa.me/{$phone}?text={$message}";

                        Notification::make()
                            ->title('Membuka WhatsApp...')
                            ->body("Mengarahkan ke chat WhatsApp {$record->shipping_name}")
                            ->success()
                            ->send();

                        return redirect()->away($waUrl);
                    }),

                // 2. CETAK INVOICE BER-TTD & STEMPEL
                Tables\Actions\Action::make('print_invoice')
                    ->label('Invoice')
                    ->icon('heroicon-m-document-text')
                    ->color('warning')
                    ->url(function (Order $record) {
                        $invoice = $record->invoice ?: Invoice::firstOrCreate(
                            ['order_id' => $record->id],
                            [
                                'invoice_number' => Invoice::generateWaInvoiceNumber(),
                                'invoice_date' => now(),
                                'subtotal' => $record->subtotal,
                                'shipping_cost' => $record->shipping_cost,
                                'discount_amount' => $record->discount_amount,
                                'grand_total' => $record->grand_total,
                                'payment_scheme' => $record->payment_scheme ?: 'full',
                                'down_payment_amount' => $record->down_payment_amount ?: 0,
                                'remaining_balance' => $record->remaining_balance ?: 0,
                                'status' => $record->payment_status === 'paid' ? 'paid' : ($record->status === 'draft' ? 'draft' : 'sent'),
                                'paid_at' => $record->payment_status === 'paid' ? now() : null,
                            ]
                        );

                        return URL::signedRoute('print.invoice', ['invoice' => $invoice->id]);
                    })
                    ->openUrlInNewTab(),

                // 3. CETAK SURAT JALAN
                Tables\Actions\Action::make('print_order')
                    ->label('Surat Jalan')
                    ->icon('heroicon-m-truck')
                    ->color('info')
                    ->url(fn (Order $record) => route('print.order', ['order' => $record->id]))
                    ->openUrlInNewTab(),

                // 4. BUKA KOORDINAT GOOGLE MAPS
                Tables\Actions\Action::make('open_maps')
                    ->label('Peta')
                    ->icon('heroicon-m-map-pin')
                    ->color('gray')
                    ->visible(fn (Order $record) => $record->shipping_latitude && $record->shipping_longitude)
                    ->url(fn (Order $record) => "https://www.google.com/maps/search/?api=1&query={$record->shipping_latitude},{$record->shipping_longitude}")
                    ->openUrlInNewTab(),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('💳 Riwayat Pembayaran & Termin Proyek (Installments & Receipts)')
                    ->description('Lacak jejak pembayaran DP & termin masuk, kuitansi resmi per-termin, dan sisa tagihan pelunasan yang saling terhubung.')
                    ->schema([
                        Infolists\Components\ViewEntry::make('payments_panel')
                            ->view('filament.order-payments-panel')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(false),

                Infolists\Components\Section::make('📦 Manajemen Pengiriman Bertahap (PO Batch)')
                    ->description('Pantau progres pengiriman per rit truk armada, aksi status produksi/kirim, foto bukti bongkar, dan cetak surat jalan.')
                    ->visible(fn ($record) => $record && $record->fulfillment_type === 'po_batch')
                    ->schema([
                        Infolists\Components\ViewEntry::make('batches_panel')
                            ->view('filament.order-batches-panel')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(false),

                Infolists\Components\Section::make('🚚 Progres Pengiriman & Status')
                    ->visible(fn ($record) => $record && $record->fulfillment_type !== 'po_batch')
                    ->schema([
                        Infolists\Components\ViewEntry::make('single_progress_panel')
                            ->view('filament.order-single-progress-panel')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(false),

                Infolists\Components\Grid::make(3)
                    ->schema([
                        Infolists\Components\Group::make([
                            Infolists\Components\Section::make('Informasi Pesanan & Status')
                                ->schema([
                                    Infolists\Components\TextEntry::make('order_number')
                                        ->label('No. Pesanan / Invoice')
                                        ->weight('bold')
                                        ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                        ->copyable(),
                                    Infolists\Components\TextEntry::make('status')
                                        ->label('Status Pesanan')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'draft' => 'gray',
                                            'pending_payment' => 'warning',
                                            'paid' => 'info',
                                            'processing' => 'primary',
                                            'shipped' => 'info',
                                            'delivered' => 'success',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            default => 'gray',
                                        })
                                        ->formatStateUsing(fn (string $state): string => match ($state) {
                                            'draft' => 'Draft',
                                            'pending_payment' => 'Menunggu Pembayaran / DP',
                                            'paid' => 'Dibayar',
                                            'processing' => 'Diproses / Diproduksi',
                                            'shipped' => 'Dalam Pengiriman',
                                            'delivered' => 'Tiba di Proyek',
                                            'completed' => 'Selesai',
                                            'cancelled' => 'Dibatalkan',
                                            default => $state,
                                        }),
                                    Infolists\Components\TextEntry::make('created_at')
                                        ->label('Waktu Dibuat')
                                        ->dateTime('d M Y H:i'),
                                    Infolists\Components\TextEntry::make('payment_scheme')
                                        ->label('Skema Pembayaran')
                                        ->formatStateUsing(fn ($state) => match ($state) {
                                            'full' => '💵 Lunas Langsung (100%)',
                                            'dp_50_50' => '💳 DP 50% + Pelunasan 50%',
                                            'termin_3x' => '🏗️ Termin 3x',
                                            'custom_dp' => '✏️ Kustom DP / Termin',
                                            default => '💵 Lunas Langsung',
                                        }),
                                ])->columns(2),

                            Infolists\Components\Section::make('Data Pembeli / Pelanggan WhatsApp')
                                ->schema([
                                    Infolists\Components\TextEntry::make('shipping_name')
                                        ->label('Nama Pemesan / Pembeli')
                                        ->weight('bold'),
                                    Infolists\Components\TextEntry::make('shipping_phone')
                                        ->label('No. WhatsApp / HP')
                                        ->icon('heroicon-m-phone'),
                                    Infolists\Components\TextEntry::make('shipping_email')
                                        ->label('Email Notifikasi')
                                        ->placeholder('(Tanpa email, update via WA)')
                                        ->icon('heroicon-m-envelope'),
                                    Infolists\Components\TextEntry::make('user.name')
                                        ->label('Akun Web Tertaut')
                                        ->placeholder('Tamu / Non-Member')
                                        ->icon('heroicon-m-user'),
                                ])->columns(2),

                            Infolists\Components\Section::make('Alamat Pengiriman & Titik Bongkar GPS')
                                ->schema([
                                    Infolists\Components\TextEntry::make('shipping_address')
                                        ->label('Alamat Lengkap / Patokan Titik')
                                        ->columnSpanFull(),
                                    Infolists\Components\TextEntry::make('shipping_village')
                                        ->label('Kelurahan / Desa')
                                        ->placeholder('-'),
                                    Infolists\Components\TextEntry::make('shipping_district')
                                        ->label('Kecamatan')
                                        ->placeholder('-'),
                                    Infolists\Components\TextEntry::make('shipping_city')
                                        ->label('Kota/Kabupaten'),
                                    Infolists\Components\TextEntry::make('shipping_province')
                                        ->label('Provinsi'),
                                    Infolists\Components\TextEntry::make('shipping_postal_code')
                                        ->label('Kode Pos'),
                                    Infolists\Components\TextEntry::make('google_maps_link')
                                        ->label('Navigasi Titik Lokasi')
                                        ->icon('heroicon-m-map-pin')
                                        ->color('primary')
                                        ->weight('bold')
                                        ->state(fn ($record) => $record->shipping_latitude && $record->shipping_longitude
                                            ? "🗺️ Buka Rute di Google Maps ({$record->shipping_latitude}, {$record->shipping_longitude})"
                                            : '-'
                                        )
                                        ->url(fn ($record) => $record->shipping_latitude && $record->shipping_longitude
                                            ? "https://www.google.com/maps/dir/?api=1&destination={$record->shipping_latitude},{$record->shipping_longitude}"
                                            : null, true),
                                ])->columns(3),
                        ])->columnSpan(2),

                        Infolists\Components\Group::make([
                            Infolists\Components\Section::make('Rincian Pembayaran & DP')
                                ->schema([
                                    Infolists\Components\TextEntry::make('subtotal')
                                        ->label('Subtotal Material')
                                        ->money('IDR'),
                                    Infolists\Components\TextEntry::make('shipping_cost')
                                        ->label('Ongkos Kirim Truk')
                                        ->money('IDR'),
                                    Infolists\Components\TextEntry::make('discount_amount')
                                        ->label('Potongan Diskon')
                                        ->money('IDR')
                                        ->color('danger'),
                                    Infolists\Components\TextEntry::make('grand_total')
                                        ->label('Grand Total Tagihan')
                                        ->money('IDR')
                                        ->weight('bold')
                                        ->size(Infolists\Components\TextEntry\TextEntrySize::Large),
                                    Infolists\Components\TextEntry::make('down_payment_amount')
                                        ->label('DP / Uang Muka Masuk')
                                        ->money('IDR')
                                        ->color('success')
                                        ->weight('bold'),
                                    Infolists\Components\TextEntry::make('remaining_balance')
                                        ->label('SISA TAGIHAN PELUNASAN')
                                        ->money('IDR')
                                        ->color('danger')
                                        ->weight('bold')
                                        ->size(Infolists\Components\TextEntry\TextEntrySize::Large),
                                    Infolists\Components\TextEntry::make('payment_status')
                                        ->label('Status Pembayaran')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'paid' => 'success',
                                            default => 'danger',
                                        })
                                        ->formatStateUsing(fn (string $state): string => match ($state) {
                                            'paid' => '🟢 Lunas (100%)',
                                            default => '🔴 Belum Lunas / DP Sebagian',
                                        }),
                                ]),

                            Infolists\Components\Section::make('Catatan Pesanan & Lapangan')
                                ->schema([
                                    Infolists\Components\TextEntry::make('notes')
                                        ->label('Catatan Invoice')
                                        ->placeholder('-'),
                                    Infolists\Components\TextEntry::make('admin_notes')
                                        ->label('Catatan Internal Pabrik')
                                        ->placeholder('-'),
                                ]),
                        ])->columnSpan(1),
                    ]),

                Infolists\Components\Section::make('Daftar Item Roster Beton Dipesan')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('items')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('product_name')
                                    ->label('Nama Produk / Roster')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('custom_variant_name')
                                    ->label('Varian Warna/Motif')
                                    ->default(fn ($record) => $record->product_variant_name ?: ($record->variant?->name ?: 'Standar Abu')),
                                Infolists\Components\TextEntry::make('quantity')
                                    ->label('Kuantitas (pcs)')
                                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.').' pcs')
                                    ->weight('bold'),
                                Infolists\Components\TextEntry::make('unit_price')
                                    ->label('Harga Satuan')
                                    ->money('IDR'),
                                Infolists\Components\TextEntry::make('subtotal')
                                    ->label('Total Harga Item')
                                    ->money('IDR')
                                    ->weight('bold'),
                            ])
                            ->columns(5)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWaOrders::route('/'),
            'create' => Pages\CreateWaOrder::route('/create'),
            'edit' => Pages\EditWaOrder::route('/{record}/edit'),
            'view' => Pages\ViewWaOrder::route('/{record}'),
        ];
    }
}
