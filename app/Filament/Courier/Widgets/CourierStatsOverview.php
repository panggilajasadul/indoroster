<?php

namespace App\Filament\Courier\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CourierStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $userId = auth()->id();

        // Tugas Hari Ini = pesanan hari ini yang BELUM selesai (berkurang saat diselesaikan)
        $tugasHariIni = Order::where('courier_id', $userId)
            ->whereDate('created_at', today())
            ->whereIn('status', ['processing', 'shipped'])
            ->count();

        // Sisa Antaran = semua pesanan yang belum selesai (termasuk hari-hari sebelumnya)
        $sisaAntaran = Order::where('courier_id', $userId)
            ->whereIn('status', ['processing', 'shipped'])
            ->count();

        // Selesai Hari Ini = pesanan yang sudah berhasil diantarkan hari ini
        $selesaiHariIni = Order::where('courier_id', $userId)
            ->where('status', 'completed')
            ->whereDate('completed_at', today())
            ->count();

        return [
            Stat::make('Perlu Diantar', $sisaAntaran)
                ->description($sisaAntaran > 0 ? 'Belum diantar' : 'Semua sudah diantar! 🎉')
                ->descriptionIcon('heroicon-m-truck')
                ->color($sisaAntaran > 0 ? 'warning' : 'success')
                ->url('/courier/orders'),

            Stat::make('Selesai Hari Ini', $selesaiHariIni)
                ->description('Berhasil diantar hari ini')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->url('/courier/riwayat'),
        ];
    }
}
