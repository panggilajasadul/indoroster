<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Component;

class AboutUs extends Component
{
    public function render()
    {
        $page = Page::where('slug', 'tentang-kami')->where('is_active', true)->first();

        $metaTitle = $page?->meta_title ?: 'Tentang Kami — INDOROSTER | Pabrik Roster Beton Plered Purwakarta';
        $metaDesc = $page?->meta_description ?: 'Kenali INDOROSTER lebih dekat. Kami adalah pabrik roster beton minimalis tangan pertama di Plered, Purwakarta, Jawa Barat. Berpengalaman melayani ribuan proyek rumah dan komersial di seluruh Indonesia.';

        return view('livewire.about-us', [
            'page' => $page,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDesc,
            'canonicalOverride' => route('about-us'),
        ]);
    }
}
