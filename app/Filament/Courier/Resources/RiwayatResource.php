<?php

namespace App\Filament\Courier\Resources;

use App\Filament\Courier\Resources\RiwayatResource\Pages\ListRiwayat;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RiwayatResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Riwayat Pengiriman';

    protected static ?string $pluralModelLabel = 'Riwayat Pengiriman';

    protected static ?string $modelLabel = 'Pengiriman Selesai';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'riwayat';

    public static function getEloquentQuery(): Builder
    {
        $courierId = auth()->id();

        return parent::getEloquentQuery()
            ->where('status', 'completed')
            ->where(function ($query) use ($courierId) {
                $query->where('courier_id', $courierId)
                    ->orWhereHas('batches', fn ($q) => $q->where('courier_id', $courierId)
                        ->where('status', 'delivered')
                    );
            });
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ViewColumn::make('id')
                    ->view('filament.courier.riwayat-card'),
            ])
            ->contentGrid([
                'default' => 1,
                'md' => 2,
                'xl' => 3,
            ])
            ->defaultSort('completed_at', 'desc')
            ->actions([])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRiwayat::route('/'),
        ];
    }
}
