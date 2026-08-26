<?php

namespace App\Filament\Courier\Widgets;

use App\Models\Order;
use App\Models\OrderBatch;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CourierStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $userId = auth()->id();

        // Sisa Antaran = semua pesanan (biasa/batch) yang belum selesai ditugaskan ke kurir ini
        $sisaAntaran = Order::whereIn('status', ['processing', 'shipped'])
            ->where(function ($query) use ($userId) {
                $query->where('courier_id', $userId)
                    ->orWhereHas('batches', fn ($q) => $q->where('courier_id', $userId)
                        ->whereIn('status', ['shipped'])
                    );
            })
            ->count();

        // Selesai Hari Ini = pesanan biasa yang selesai hari ini, ATAU batch pengiriman yang selesai hari ini oleh kurir ini
        $selesaiOrderHariIni = Order::where('courier_id', $userId)
            ->where('status', 'completed')
            ->whereDate('completed_at', today())
            ->count();

        $selesaiBatchHariIni = OrderBatch::where('courier_id', $userId)
            ->where('status', 'delivered')
            ->whereDate('actual_delivered_date', today())
            ->count();

        $selesaiHariIni = $selesaiOrderHariIni + $selesaiBatchHariIni;

        return [
            Stat::make('Perlu Diantar', $sisaAntaran)
                ->description($sisaAntaran > 0 ? "Semangat! Tinggal {$sisaAntaran} pengiriman lagi. Hati-hati di jalan ya!" : 'Semua sudah diantar! Istirahat dulu jika lelah, jangan lupa ngopi ☕')
                ->descriptionIcon($sisaAntaran > 0 ? 'heroicon-m-heart' : 'heroicon-m-face-smile')
                ->color($sisaAntaran > 0 ? 'warning' : 'success')
                ->url('/courier/orders'),

            Stat::make('Selesai Hari Ini', $selesaiHariIni)
                ->description('Hebat! Terima kasih atas kerja keras Anda hari ini 💪')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->url('/courier/riwayat'),
        ];
    }
}
