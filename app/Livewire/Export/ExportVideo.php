<?php

namespace App\Livewire\Export;

use App\Models\Gallery;
use App\Models\SiteSetting;
use Livewire\Component;

class ExportVideo extends Component
{
    public int $perPage = 12;

    public function loadMore()
    {
        $this->perPage += 12;
    }

    public function render()
    {
        $videos = Gallery::where('is_active', true)
            ->whereHas('media', function ($q) {
                $q->where('media_type', 'video');
            })
            ->with(['media' => function ($q) {
                $q->where('media_type', 'video')->orderBy('sort_order');
            }, 'product'])
            ->latest()
            ->paginate($this->perPage);

        $rawWa = SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
        $waNumber = preg_replace('/[^0-9]/', '', $rawWa);
        if (str_starts_with($waNumber, '0')) {
            $waNumber = '62'.substr($waNumber, 1);
        }

        return view('livewire.export.export-video', [
            'videos' => $videos,
            'waNumber' => $waNumber,
        ])->layout('components.layouts.app', [
            'title' => 'Artisan Workshop & Production Video Gallery | IndoRoster Global Export',
            'metaDescription' => 'Watch genuine workshop videos of IndoRoster craftsmen in Plered, Purwakarta. Hand-compacted precision steel moulds, stone ash formulations, and EXW container packing.',
        ]);
    }
}
