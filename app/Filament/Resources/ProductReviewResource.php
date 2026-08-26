<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductReviewResource\Pages;
use App\Models\ProductReview;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductReviewResource extends Resource
{
    protected static ?string $model = ProductReview::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $navigationGroup = 'Produk & Inventori';

    protected static ?string $label = 'Ulasan Produk';

    protected static ?string $pluralLabel = 'Ulasan Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Ulasan')
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->relationship('product', 'name')
                            ->required()
                            ->searchable(),
                        Forms\Components\TextInput::make('reviewer_name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('reviewer_location')
                            ->maxLength(100),
                        Forms\Components\Select::make('rating')
                            ->options([
                                1 => '1 Bintang',
                                2 => '2 Bintang',
                                3 => '3 Bintang',
                                4 => '4 Bintang',
                                5 => '5 Bintang',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('content')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('views_count')
                            ->label('Jumlah Tayangan (Views)')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Forms\Components\Toggle::make('is_approved')
                            ->label('Disetujui / Tampilkan')
                            ->default(true),
                        Forms\Components\Toggle::make('is_seeded')
                            ->label('Data Seeded (Dummy)')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),

                Forms\Components\Section::make('Foto Review')
                    ->schema([
                        Forms\Components\FileUpload::make('images')
                            ->label('Foto dari Pembeli')
                            ->multiple()
                            ->reorderable()
                            ->appendFiles()
                            ->openable()
                            ->downloadable()
                            ->previewable(true)
                            ->panelLayout('grid')
                            ->imagePreviewHeight('100')
                            ->image()
                            ->directory('reviews')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Produk')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('reviewer_name')
                    ->label('Reviewer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Bintang')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        5 => 'success',
                        4 => 'success',
                        3 => 'warning',
                        default => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('content')
                    ->label('Isi Review')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('views_count')
                    ->label('Views')
                    ->sortable()
                    ->numeric(),
                Tables\Columns\IconColumn::make('is_approved')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('images')
                    ->label('Foto')
                    ->circular()
                    ->stacked()
                    ->limit(3),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product')
                    ->relationship('product', 'name'),
                Tables\Filters\SelectFilter::make('rating')
                    ->options([
                        1 => '1 Bintang',
                        2 => '2 Bintang',
                        3 => '3 Bintang',
                        4 => '4 Bintang',
                        5 => '5 Bintang',
                    ]),
                Tables\Filters\TernaryFilter::make('is_approved')
                    ->label('Status Persetujuan'),
                Tables\Filters\TernaryFilter::make('is_seeded')
                    ->label('Data Dummy'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->hidden(fn (ProductReview $record): bool => $record->is_approved)
                    ->action(fn (ProductReview $record) => $record->update(['is_approved' => true])),
                Tables\Actions\Action::make('disapprove')
                    ->label('Sembunyikan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (ProductReview $record): bool => $record->is_approved)
                    ->action(fn (ProductReview $record) => $record->update(['is_approved' => false])),
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
            'index' => Pages\ListProductReviews::route('/'),
            'create' => Pages\CreateProductReview::route('/create'),
            'edit' => Pages\EditProductReview::route('/{record}/edit'),
        ];
    }
}
