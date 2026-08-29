<?php

namespace App\Filament\Resources\WaOrderResource\Pages;

use App\Filament\Resources\WaOrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListWaOrders extends ListRecords
{
    protected static string $resource = WaOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('+ Buat Pesanan WhatsApp Baru')
                ->icon('heroicon-m-plus'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Pesanan')
                ->badge(Order::where('order_source', 'whatsapp')->count()),

            'draft' => Tab::make('⚪ Draft')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'draft'))
                ->badge(Order::where('order_source', 'whatsapp')->where('status', 'draft')->count())
                ->badgeColor('gray'),

            'pending_payment' => Tab::make('🟡 Menunggu Bayar / DP')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending_payment'))
                ->badge(Order::where('order_source', 'whatsapp')->where('status', 'pending_payment')->count())
                ->badgeColor('warning'),

            'processing' => Tab::make('🔵 Diproses / Produksi')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'processing'))
                ->badge(Order::where('order_source', 'whatsapp')->where('status', 'processing')->count())
                ->badgeColor('info'),

            'shipped' => Tab::make('🟣 Dalam Pengiriman')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'shipped'))
                ->badge(Order::where('order_source', 'whatsapp')->where('status', 'shipped')->count())
                ->badgeColor('primary'),

            'completed' => Tab::make('✅ Selesai')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', ['delivered', 'completed']))
                ->badge(Order::where('order_source', 'whatsapp')->whereIn('status', ['delivered', 'completed'])->count())
                ->badgeColor('success'),
        ];
    }
}
