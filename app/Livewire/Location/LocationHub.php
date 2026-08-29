<?php

namespace App\Livewire\Location;

use App\Models\Page;
use App\Models\SeoLocation;
use Livewire\Component;

class LocationHub extends Component
{
    public function render()
    {
        $locations = SeoLocation::where('seo_enabled', true)
            ->priorityFirst()
            ->get();

        $page = Page::where('slug', 'lokasi')->first();
        $metaTitle = $page?->meta_title ?: 'Area Layanan Pengiriman Roster Beton Seluruh Indonesia | IndoRoster';
        $metaDescription = $page?->meta_description ?: 'Daftar kota dan wilayah jangkauan pengiriman langsung armada truk pabrik IndoRoster: Jabodetabek, Bandung, Karawang, Cianjur, Cirebon, dan ekspedisi nasional se-Indonesia.';
        $keywords = 'area pengiriman roster, jual roster jabodetabek, supplier roster jawa barat, pabrik roster jakarta bandung, ongkir roster beton';

        return view('livewire.location.location-hub', [
            'page' => $page,
            'locations' => $locations,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDescription,
            'keywords' => $keywords,
            'canonicalOverride' => route('location.index'),
        ]);
    }
}
