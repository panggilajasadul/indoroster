<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $navigationLabel = 'Pengaturan Website';
    protected static ?string $modelLabel = 'Pengaturan';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Select::make('group')
                        ->label('Grup')
                        ->options([
                            'general' => '🏠 Umum',
                            'contact' => '📞 Kontak',
                            'payment' => '💳 Pembayaran',
                            'shipping' => '🚚 Pengiriman',
                            'seo' => '🔍 SEO',
                        ])
                        ->default('general')
                        ->required(),
                    Forms\Components\TextInput::make('key')
                        ->label('Key')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->placeholder('whatsapp_number'),
                    Forms\Components\Select::make('type')
                        ->label('Tipe Input')
                        ->options([
                            'text' => 'Teks Pendek',
                            'textarea' => 'Teks Panjang',
                            'boolean' => 'Ya/Tidak',
                            'number' => 'Angka',
                            'image' => 'Link Gambar',
                        ])
                        ->default('text'),
                    Forms\Components\TextInput::make('description')
                        ->label('Penjelasan')
                        ->placeholder('Deskripsi untuk admin'),
                    Forms\Components\Select::make('value')
                        ->label('Nilai')
                        ->options([
                            'left' => 'Kiri (Left)',
                            'center' => 'Tengah (Center)',
                            'right' => 'Kanan (Right)',
                        ])
                        ->visible(fn ($record) => $record?->key === 'navbar_alignment')
                        ->required()
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('value')
                        ->label('Nilai')
                        ->rows(3)
                        ->columnSpanFull()
                        ->hidden(fn ($record) => $record?->key === 'navbar_alignment'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->label('Grup')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'general' => 'primary',
                        'contact' => 'success',
                        'payment' => 'warning',
                        'shipping' => 'info',
                        'seo' => 'gray',
                        'mail' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('key')->label('Key')->searchable(),
                Tables\Columns\TextColumn::make('value')->label('Nilai')->limit(50),
                Tables\Columns\TextColumn::make('description')->label('Keterangan')->limit(30),
            ])
            ->defaultSort('group')
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->label('Grup')
                    ->options([
                        'general' => 'Umum',
                        'contact' => 'Kontak',
                        'payment' => 'Pembayaran',
                        'shipping' => 'Pengiriman',
                        'seo' => 'SEO',
                    ]),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('group', '!=', 'mail');
    }
}
