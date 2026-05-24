<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
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
            'all' => \Filament\Resources\Components\Tab::make('Semua')
                ->badge(\App\Models\Order::count()),
            'pending' => \Filament\Resources\Components\Tab::make('Menunggu Bayar')
                ->badge(\App\Models\Order::where('status', 'pending_payment')->count())
                ->badgeColor('warning')
                ->query(fn ($query) => $query->where('status', 'pending_payment')),
            'paid' => \Filament\Resources\Components\Tab::make('Perlu Diproses')
                ->badge(\App\Models\Order::where('status', 'paid')->count())
                ->badgeColor('primary')
                ->query(fn ($query) => $query->where('status', 'paid')),
            'processing' => \Filament\Resources\Components\Tab::make('Diproses')
                ->badge(\App\Models\Order::where('status', 'processing')->count())
                ->badgeColor('info')
                ->query(fn ($query) => $query->where('status', 'processing')),
            'shipped' => \Filament\Resources\Components\Tab::make('Dikirim')
                ->badge(\App\Models\Order::where('status', 'shipped')->count())
                ->badgeColor('info')
                ->query(fn ($query) => $query->where('status', 'shipped')),
            'completed' => \Filament\Resources\Components\Tab::make('Selesai')
                ->badge(\App\Models\Order::where('status', 'completed')->count())
                ->badgeColor('success')
                ->query(fn ($query) => $query->where('status', 'completed')),
            'cancelled' => \Filament\Resources\Components\Tab::make('Dibatalkan')
                ->badge(\App\Models\Order::where('status', 'cancelled')->count())
                ->badgeColor('danger')
                ->query(fn ($query) => $query->where('status', 'cancelled')),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'paid';
    }
}
