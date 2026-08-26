<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DividerWidget extends Widget
{
    protected static string $view = 'filament.widgets.divider-widget';

    protected static ?int $sort = 2; // Berada di antara OrderStats (1) dan FinancialStats (3)

    protected int|string|array $columnSpan = 'full';
}
