<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class FinancialStatsOverview extends BaseWidget
{
    protected static ?int $sort = 3;

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
        $revenueDescription = $revenueTrend > 0 ? '+'.number_format($revenueTrend, 1).'% dari bulan lalu' : number_format($revenueTrend, 1).'% dari bulan lalu';
        $revenueIcon = $revenueTrend > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';
        $revenueColor = $revenueTrend > 0 ? 'success' : 'danger';

        $revenueThisWeek = Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered', 'completed'])
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('grand_total');

        $revenueToday = Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered', 'completed'])
            ->whereDate('created_at', Carbon::today())
            ->sum('grand_total');

        return [
            Stat::make('Pendapatan Bulan Ini', 'Rp '.number_format($revenueThisMonth, 0, ',', '.'))
                ->description($revenueDescription)
                ->descriptionIcon($revenueIcon)
                ->color($revenueColor)
                ->url(route('filament.admin.resources.orders.index')),

            Stat::make('Pendapatan Minggu Ini', 'Rp '.number_format($revenueThisWeek, 0, ',', '.'))
                ->description('Mulai Senin minggu ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->url(route('filament.admin.resources.orders.index')),

            Stat::make('Pendapatan Hari Ini', 'Rp '.number_format($revenueToday, 0, ',', '.'))
                ->description('Total penjualan masuk hari ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->url(route('filament.admin.resources.orders.index')),
        ];
    }
}
