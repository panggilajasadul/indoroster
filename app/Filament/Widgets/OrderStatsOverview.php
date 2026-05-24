<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class OrderStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        // Pendapatan bulan ini
        $revenueThisMonth = Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered', 'completed'])
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('grand_total');

        $revenueLastMonth = Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered', 'completed'])
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('grand_total');

        $revenueTrend = $revenueLastMonth > 0 ? (($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100 : 100;
        $revenueDescription = $revenueTrend > 0 ? '+' . number_format($revenueTrend, 1) . '% dari bulan lalu' : number_format($revenueTrend, 1) . '% dari bulan lalu';
        $revenueIcon = $revenueTrend > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $revenueColor = $revenueTrend > 0 ? 'success' : 'danger';

        $revenueThisWeek = Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered', 'completed'])
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('grand_total');

        $revenueToday = Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered', 'completed'])
            ->whereDate('created_at', Carbon::today())
            ->sum('grand_total');

        return [
            Stat::make('Pesanan Baru Hari Ini', Order::whereDate('created_at', Carbon::today())->count())
                ->description('Total pesanan yang masuk hari ini')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info')
                ->url(route('filament.admin.resources.orders.index')),

            Stat::make('Pengiriman Perlu Diproses', Order::where('status', 'paid')->count())
                ->description('Pesanan lunas menunggu pengiriman')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->url(route('filament.admin.resources.orders.index', ['activeTab' => 'paid'])),

            Stat::make('Pengiriman Telah Diproses', Order::whereIn('status', ['processing', 'shipped', 'delivered', 'completed'])
                ->whereDate('updated_at', Carbon::today())
                ->count())
                ->description('Diproses/dikirim hari ini')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->url(route('filament.admin.resources.orders.index', ['activeTab' => 'processing'])),
        ];
    }
}
