<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PoBatchActiveResource\Pages;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PoBatchActiveResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = '🚚 PO Batch';

    protected static ?string $pluralModelLabel = 'PO Batch';

    protected static ?string $modelLabel = 'Pesanan';

    protected static ?string $navigationGroup = 'Manajemen Pemenuhan';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()
            ->whereIn('status', ['pending_payment', 'paid', 'processing', 'shipped', 'completed'])
            ->where('fulfillment_type', 'po_batch');
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()
            ->whereIn('status', ['pending_payment', 'paid', 'processing', 'shipped'])
            ->where('fulfillment_type', 'po_batch')
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
            'index' => Pages\ListPoBatchActives::route('/'),
            'view' => Pages\ViewPoBatchActive::route('/{record}'),
        ];
    }
}
