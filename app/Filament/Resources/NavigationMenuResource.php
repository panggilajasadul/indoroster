<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NavigationMenuResource\Pages;
use App\Models\NavigationMenu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NavigationMenuResource extends Resource
{
    protected static ?string $model = NavigationMenu::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';
    protected static ?string $navigationLabel = 'Menu Navigasi';
    protected static ?string $modelLabel = 'Menu Navigasi';
    protected static ?string $pluralModelLabel = 'Menu Navigasi';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Menu Navigasi')
                    ->schema([
                        Forms\Components\TextInput::make('label')
                            ->label('Nama Menu')
                            ->placeholder('Contoh: Beranda, Hubungi Kami')
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\TextInput::make('url')
                            ->label('URL / Path')
                            ->placeholder('Contoh: / , /katalog , /gallery , atau https://google.com')
                            ->helperText("Gunakan '/' untuk beranda, '/katalog' untuk produk, '/gallery' untuk galeri, atau masukkan URL eksternal penuh.")
                            ->required()
                            ->maxLength(255),
                            
                        Forms\Components\Select::make('target')
                            ->label('Target Link')
                            ->options([
                                '_self' => 'Buka di Tab yang Sama',
                                '_blank' => 'Buka di Tab Baru',
                            ])
                            ->default('_self')
                            ->required(),
                            
                        Forms\Components\TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->required(),
                            
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('label')
                    ->label('Nama Menu')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('url')
                    ->label('URL / Path')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('target')
                    ->label('Target')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '_self' => 'gray',
                        '_blank' => 'info',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
                    
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Aktif'),
            ])
            ->defaultSort('order')
            ->reorderable('order')
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNavigationMenus::route('/'),
            'create' => Pages\CreateNavigationMenu::route('/create'),
            'edit' => Pages\EditNavigationMenu::route('/{record}/edit'),
        ];
    }
}
