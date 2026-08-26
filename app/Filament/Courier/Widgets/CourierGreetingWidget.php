<?php

namespace App\Filament\Courier\Widgets;

use Filament\Widgets\Widget;

class CourierGreetingWidget extends Widget
{
    protected static string $view = 'filament.courier.widgets.greeting-widget';

    protected static ?int $sort = 0; // Display at the very top of dashboard

    protected int|string|array $columnSpan = 'full';
}
