<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChartWidget extends ChartWidget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.custom-revenue-chart';

    protected static ?string $heading = 'Tren Pendapatan';

    protected static ?int $sort = 4; // Berada di bawah FinancialStats (3)

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '300px';

    public ?string $startDate = null;

    public ?string $endDate = null;

    public function mount(): void
    {
        $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('startDate')
                    ->label(false)
                    ->placeholder('Mulai')
                    ->live()
                    ->maxDate(now())
                    ->extraAttributes(['class' => 'w-32']),
                DatePicker::make('endDate')
                    ->label(false)
                    ->placeholder('Sampai')
                    ->live()
                    ->maxDate(now())
                    ->extraAttributes(['class' => 'w-32']),
            ]);
    }

    protected function getData(): array
    {
        $start = Carbon::parse($this->startDate ?? Carbon::now()->subDays(30))->startOfDay();
        $end = Carbon::parse($this->endDate ?? Carbon::now())->endOfDay();

        $data = [];
        $labels = [];

        // Loop through each day in the range
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $revenue = Order::whereIn('status', ['paid', 'processing', 'shipped', 'delivered', 'completed'])
                ->whereDate('created_at', $date)
                ->sum('grand_total');

            $data[] = $revenue;
            $labels[] = $date->format('d M');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $data,
                    'fill' => 'start',
                    'backgroundColor' => 'rgba(239, 108, 0, 0.2)',
                    'borderColor' => '#ef6c00',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
