<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua')
                ->badge(Order::count()),

            'pending' => Tab::make('Menunggu Bayar')
                ->badge(Order::where('status', 'pending_payment')->count())
                ->badgeColor('warning')
                ->query(fn ($query) => $query->where('status', 'pending_payment')),

            'paid' => Tab::make('Perlu Diproses')
                ->badge(Order::where('status', 'paid')->count())
                ->badgeColor('primary')
                ->query(fn ($query) => $query->where('status', 'paid')),

            'processing' => Tab::make('Diproses')
                ->badge(Order::where('status', 'processing')->count())
                ->badgeColor('info')
                ->query(fn ($query) => $query->where('status', 'processing')),

            'shipped' => Tab::make('Dikirim')
                ->badge(Order::where('status', 'shipped')->count())
                ->badgeColor('info')
                ->query(fn ($query) => $query->where('status', 'shipped')),

            'completed' => Tab::make('Selesai')
                ->badge(Order::where('status', 'completed')->count())
                ->badgeColor('success')
                ->query(fn ($query) => $query->where('status', 'completed')),

            'cancelled' => Tab::make('Dibatalkan')
                ->badge(Order::where('status', 'cancelled')->count())
                ->badgeColor('danger')
                ->query(fn ($query) => $query->where('status', 'cancelled')),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return 'paid';
    }
}
