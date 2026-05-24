<?php

namespace App\Filament\Courier\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CourierStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $userId = auth()->id();

        $totalHariIni = Order::where('courier_id', $userId)
            ->whereDate('created_at', today())
            ->count();

        $sisaAntaran = Order::where('courier_id', $userId)
            ->whereIn('status', ['processing', 'shipped'])
            ->count();

        $selesaiHariIni = Order::where('courier_id', $userId)
            ->where('status', 'completed')
            ->whereDate('completed_at', today())
            ->count();

        return [
            Stat::make('Tugas Hari Ini', $totalHariIni)
                ->description('Total pesanan masuk hari ini')
                ->descriptionIcon('heroicon-m-inbox-arrow-down')
                ->color('primary')
                ->url('/courier/orders'),
                
            Stat::make('Sisa Antaran', $sisaAntaran)
                ->description('Pesanan yang belum selesai')
                ->descriptionIcon('heroicon-m-truck')
                ->color('warning')
                ->url('/courier/orders?tableFilters[status][value]=shipped'),
                
            Stat::make('Selesai Hari Ini', $selesaiHariIni)
                ->description('Pesanan sukses diantar')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->url('/courier/orders?tableFilters[status][value]=completed'),
        ];
    }
}
