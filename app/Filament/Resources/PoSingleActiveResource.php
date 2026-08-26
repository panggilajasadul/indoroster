<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PoSingleActiveResource\Pages;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PoSingleActiveResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationLabel = '🔨 PO Tunggal';

    protected static ?string $pluralModelLabel = 'PO Tunggal';

    protected static ?string $modelLabel = 'Pesanan';

    protected static ?string $navigationGroup = 'Manajemen Pemenuhan';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereIn('status', ['processing', 'shipped', 'completed'])
            ->where('fulfillment_type', 'po_single');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = parent::getEloquentQuery()
            ->whereIn('status', ['processing', 'shipped'])
            ->where('fulfillment_type', 'po_single')
            ->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
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
            'index' => Pages\ListPoSingleActives::route('/'),
            'view' => Pages\ViewPoSingleActive::route('/{record}'),
        ];
    }
}
