<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReadyStockActiveResource\Pages;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ReadyStockActiveResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = '📦 Ready Stock';

    protected static ?string $pluralModelLabel = 'Ready Stock';

    protected static ?string $modelLabel = 'Pesanan';

    protected static ?string $navigationGroup = 'Manajemen Pemenuhan';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereIn('status', ['processing', 'shipped', 'completed'])
            ->where(fn ($q) => $q->whereNull('fulfillment_type')->orWhere('fulfillment_type', 'ready_stock'));
    }

    public static function getNavigationBadge(): ?string
    {
        $count = parent::getEloquentQuery()
            ->whereIn('status', ['processing', 'shipped'])
            ->where(fn ($q) => $q->whereNull('fulfillment_type')->orWhere('fulfillment_type', 'ready_stock'))
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }

    public static function form(Form $form): Form
    {
        return OrderResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return OrderResource::table($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return OrderResource::infolist($infolist);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReadyStockActives::route('/'),
            'view' => Pages\ViewReadyStockActive::route('/{record}'),
        ];
    }
}
