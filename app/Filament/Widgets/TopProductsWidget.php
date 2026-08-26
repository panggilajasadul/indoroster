<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopProductsWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected static ?string $heading = '🏆 Top 5 Produk Paling Laris';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::query()
                    ->where('is_active', true)
                    ->orderBy('total_sold', 'desc')
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('media.file_path')
                    ->label('Foto')
                    ->limit(1)
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Produk'),
                Tables\Columns\TextColumn::make('total_sold')
                    ->label('Terjual')
                    ->badge()
                    ->color('success')
                    ->suffix(' unit'),
            ])
            ->paginated(false);
    }
}
