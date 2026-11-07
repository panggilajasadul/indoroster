<?php

namespace App\Livewire;

use App\Services\VisitorTrackerService;
use Livewire\Component;

class VisitorCounterRealtime extends Component
{
    public function render()
    {
        $visitorStats = VisitorTrackerService::getStats();

        return view('livewire.visitor-counter-realtime', [
            'visitorStats' => $visitorStats,
        ]);
    }
}
