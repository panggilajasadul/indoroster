<?php

namespace App\Livewire\Location;

use App\Models\SeoLocation;
use Livewire\Component;

class LocationHub extends Component
{
    public function render()
    {
        $locations = SeoLocation::where('seo_enabled', true)
            ->priorityFirst()
            ->get();

        $metaTitle = 'Area Layanan Pengiriman Roster Beton Seluruh Indonesia | IndoRoster';
        $metaDescription = 'Daftar kota dan wilayah jangkauan pengiriman langsung armada truk pabrik IndoRoster: Jabodetabek, Bandung, Karawang, Cianjur, Cirebon, dan ekspedisi nasional se-Indonesia.';
        $keywords = 'area pengiriman roster, jual roster jabodetabek, supplier roster jawa barat, pabrik roster jakarta bandung, ongkir roster beton';

        return view('livewire.location.location-hub', [
            'locations' => $locations,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'keywords' => $keywords,
            'canonicalOverride' => route('location.index'),
        ]);
    }
}
