<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Mail\CustomerQuotationMail;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'CRM & Pelanggan';

    protected static ?string $navigationLabel = 'Database Pelanggan & Lead';

    protected static ?string $modelLabel = 'Pelanggan / Lead';

    protected static ?string $pluralModelLabel = 'Pelanggan & Lead CRM';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) static::getEloquentQuery()->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('role', 'customer')
            ->where('email', 'not like', 'dummy_user_%@indoroster.com')
            ->with(['addresses']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akun & Kontak')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap / PIC')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label('Alamat Email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->label('Nomor WhatsApp / HP')
                            ->tel()
                            ->required()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('password')
                            ->label('Password (Isi jika ingin ubah)')
                            ->password()
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Profil CRM & Kategori Kemitraan')
                    ->schema([
                        Forms\Components\Select::make('customer_type')
                            ->label('Kategori Pelanggan / Kemitraan')
                            ->options([
                                'individual' => '🏠 Pemilik Rumah / Pribadi',
                                'contractor' => '🏗️ Kontraktor / Pemborong Bangunan',
                                'architect' => '📐 Arsitek / Desainer Interior',
                                'commercial' => '☕ Kafe, Resto & Komersial',
                                'developer' => '🏢 Developer Perumahan / Real Estate',
                            ])
                            ->default('individual')
                            ->required(),
                        Forms\Components\TextInput::make('company_name')
                            ->label('Nama Usaha / Perusahaan / Studio')
                            ->placeholder('Contoh: PT Sinar Mandiri / Studio Reka'),
                        Forms\Components\Select::make('lead_status')
                            ->label('Status Hubungan / Lead Stage')
                            ->options([
                                'new' => '⚪ Baru Mendaftar (Lead Baru)',
                                'contacted' => '🟣 Sudah Dihubungi',
                                'quoted' => '🟡 Sudah Diberi Penawaran',
                                'customer' => '🔵 Pelanggan Aktif',
                                'vip' => '🟢 Mitra VIP / Volume Besar',
                            ])
                            ->default('new')
                            ->required(),
                        Forms\Components\Textarea::make('crm_notes')
                            ->label('Catatan CRM Internal Admin')
                            ->placeholder('Contoh: Tertarik motif roster NRT untuk proyek ruko di Bekasi, jadwal kirim pertengahan bulan.')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pelanggan')
                    ->searchable()
                    ->sortable()
                    ->description(fn (User $record): ?string => $record->company_name ?: null),

                Tables\Columns\TextColumn::make('customer_type')
                    ->label('Tipe Mitra')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'contractor' => '🏗️ Kontraktor',
                        'architect' => '📐 Arsitek',
                        'commercial' => '☕ Kafe/Resto',
                        'developer' => '🏢 Developer',
                        default => '🏠 Pemilik Rumah',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'contractor' => 'warning',
                        'architect' => 'info',
                        'commercial' => 'success',
                        'developer' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('phone')
                    ->label('WhatsApp')
                    ->searchable()
                    ->icon('heroicon-m-phone'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('addresses.city')
                    ->label('Kota Proyek')
                    ->badge()
                    ->color('gray')
                    ->default('-')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('lead_status')
                    ->label('Status Lead')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'contacted' => 'Sudah Dihubungi',
                        'quoted' => 'Diberi Penawaran',
                        'customer' => 'Pelanggan Aktif',
                        'vip' => 'VIP Mitra',
                        default => 'Lead Baru',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'vip' => 'success',
                        'customer' => 'info',
                        'quoted' => 'warning',
                        'contacted' => 'primary',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Daftar')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('customer_type')
                    ->label('Filter Tipe Mitra')
                    ->options([
                        'individual' => '🏠 Pemilik Rumah',
                        'contractor' => '🏗️ Kontraktor',
                        'architect' => '📐 Arsitek',
                        'commercial' => '☕ Kafe/Resto',
                        'developer' => '🏢 Developer',
                    ]),
                Tables\Filters\SelectFilter::make('lead_status')
                    ->label('Filter Status Lead')
                    ->options([
                        'new' => '⚪ Lead Baru',
                        'contacted' => '🟣 Sudah Dihubungi',
                        'quoted' => '🟡 Diberi Penawaran',
                        'customer' => '🔵 Pelanggan Aktif',
                        'vip' => '🟢 VIP Mitra',
                    ]),
            ])
            ->actions([
                // 1. KIRIM PENAWARAN WHATSAPP (1-CLICK DIRECT WITH TEMPLATES)
                Tables\Actions\Action::make('send_whatsapp')
                    ->label('Kirim WA')
                    ->icon('heroicon-m-chat-bubble-left-right')
                    ->color('success')
                    ->form([
                        Forms\Components\Select::make('template')
                            ->label('Pilih Template Pesan WhatsApp')
                            ->options([
                                'contractor' => '🏗️ Template 1: Penawaran & Pricelist Khusus Kontraktor',
                                'developer' => '🏢 Template 2: Penawaran Pengadaan Volume Besar Developer',
                                'commercial' => '☕ Template 3: Penawaran Fasad Estetik Kafe & Studio Arsitek',
                                'followup' => '📐 Template 4: Follow-up Bantuan Hitung Kebutuhan Roster',
                                'custom' => '✏️ Template 5: Pesan Custom Bebas',
                            ])
                            ->default(fn (User $record) => match ($record->customer_type) {
                                'contractor' => 'contractor',
                                'developer' => 'developer',
                                'commercial', 'architect' => 'commercial',
                                default => 'followup',
                            })
                            ->live()
                            ->required(),

                        Forms\Components\Textarea::make('custom_message')
                            ->label('Isi Pesan WhatsApp')
                            ->rows(6)
                            ->default(function (Forms\Get $get, User $record): string {
                                $tpl = $get('template') ?: 'contractor';
                                $name = $record->name;
                                $company = $record->company_name ? " dari {$record->company_name}" : '';

                                return match ($tpl) {
                                    'contractor' => "Halo Bpk/Ibu {$name}{$company},\n\nTerima kasih telah terhubung dengan Pabrik IndoRoster. Kami ingin menyampaikan Katalog Lengkap & Pricelist Khusus Kontraktor/Proyek dengan harga pabrik tangan pertama.\n\nApakah saat ini ada proyek ruko, pagar, atau fasad bangunan yang sedang membutuhkan suplai roster beton mutu K-200?\n\nKatalog dapat dilihat di: https://indoroster.com/katalog",
                                    'developer' => "Selamat siang tim {$name}{$company},\n\nSalam kenal dari IndoRoster. Kami siap menjadi mitra vendor pengadaan roster beton skala besar untuk proyek perumahan & kawasan klaster Anda dengan armada truk pabrik terjadwal.\n\nKami siap mengirimkan sampel fisik dan penawaran volume khusus.",
                                    'commercial' => "Halo Kak {$name}{$company},\n\nSalam hangat dari IndoRoster! Kami melihat kebutuhan Anda untuk dekorasi dan partisi arsitektural. Roster minimalis kami sangat cocok untuk menciptakan sirkulasi udara adem dan fasad estetik instagramable.\n\nButuh rekomendasi motif terbaik?",
                                    'followup' => "Halo Kak {$name},\n\nTerima kasih sudah mendaftar di IndoRoster! Apakah butuh bantuan untuk menghitung estimasi jumlah keping roster dan kebutuhan semen untuk renovasi rumah Anda?\n\nTim ahli kami siap bantu hitungkan gratis.",
                                    default => "Halo {$name}, salam hangat dari tim IndoRoster...",
                                };
                            }),
                    ])
                    ->action(function (array $data, User $record) {
                        $phone = preg_replace('/[^0-9]/', '', $record->phone);
                        if (str_starts_with($phone, '0')) {
                            $phone = '62'.substr($phone, 1);
                        }

                        $message = urlencode($data['custom_message']);
                        $waUrl = "https://wa.me/{$phone}?text={$message}";

                        // Update lead status jika masih baru
                        if ($record->lead_status === 'new') {
                            $record->update(['lead_status' => 'contacted']);
                        }

                        Notification::make()
                            ->title('Membuka WhatsApp...')
                            ->body("Mengarahkan ke chat WhatsApp {$record->name}")
                            ->success()
                            ->send();

                        return redirect()->away($waUrl);
                    }),

                // 2. KIRIM EMAIL PENAWARAN HARGA (EMAIL QUOTATION)
                Tables\Actions\Action::make('send_email')
                    ->label('Kirim Email')
                    ->icon('heroicon-m-envelope')
                    ->color('info')
                    ->form([
                        Forms\Components\TextInput::make('subject')
                            ->label('Subjek Email')
                            ->default(fn (User $record) => "Surat Penawaran Harga & Katalog Roster Beton — IndoRoster untuk {$record->name}")
                            ->required(),
                        Forms\Components\TextInput::make('offer_title')
                            ->label('Judul Penawaran / Highlight Banner')
                            ->default('Penawaran Spesial Mitra & Pengadaan Proyek'),
                        Forms\Components\Textarea::make('message_body')
                            ->label('Isi Surat Penawaran')
                            ->rows(8)
                            ->default(function (User $record): string {
                                return "Terima kasih telah mempercayakan kebutuhan arsitektur dan roster beton Anda kepada IndoRoster.\n\nKami melampirkan informasi harga pabrik tangan pertama dengan jaminan mutu beton K-200 padat getar, siku 90 derajat presisi, dan garansi pecah 100% ganti baru yang dikirim langsung dengan armada truk pabrik.\n\nUntuk konsultasi teknis, hitung volume, dan penjadwalan armada truk ke lokasi proyek Anda, silakan hubungi tim sales kami via tombol di bawah ini.";
                            })
                            ->required(),
                    ])
                    ->action(function (array $data, User $record) {
                        try {
                            Mail::to($record->email)->send(new CustomerQuotationMail(
                                user: $record,
                                subjectText: $data['subject'],
                                messageBody: $data['message_body'],
                                offerTitle: $data['offer_title']
                            ));

                            $record->update(['lead_status' => 'quoted']);

                            Notification::make()
                                ->title('Email Penawaran Terkirim!')
                                ->body("Surat penawaran berhasil dikirim ke {$record->email}")
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal Mengirim Email')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // EXPORT DATA PELANGGAN TO CSV
                Tables\Actions\BulkAction::make('export_csv')
                    ->label('Export Data Pelanggan (CSV)')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Collection $records): StreamedResponse {
                        $headers = [
                            'Content-Type' => 'text/csv; charset=UTF-8',
                            'Content-Disposition' => 'attachment; filename=customers-indoroster-'.date('Y-m-d').'.csv',
                        ];

                        $callback = function () use ($records) {
                            $file = fopen('php://output', 'w');
                            // Add UTF-8 BOM
                            fwrite($file, "\xEF\xBB\xBF");
                            fputcsv($file, ['ID', 'Nama', 'Email', 'No WhatsApp', 'Tipe Mitra', 'Perusahaan', 'Status Lead', 'Kota Proyek', 'Alamat Lengkap', 'Tgl Daftar']);

                            foreach ($records as $r) {
                                $address = $r->addresses->first();
                                fputcsv($file, [
                                    $r->id,
                                    $r->name,
                                    $r->email,
                                    $r->phone,
                                    $r->customer_type,
                                    $r->company_name ?: '-',
                                    $r->lead_status ?: 'new',
                                    $address ? $address->city : '-',
                                    $address ? $address->full_address : '-',
                                    $r->created_at->format('Y-m-d H:i:s'),
                                ]);
                            }
                            fclose($file);
                        };

                        return response()->stream($callback, 200, $headers);
                    }),
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
