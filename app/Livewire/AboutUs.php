<?php

namespace App\Livewire;

use Livewire\Component;

class AboutUs extends Component
{
    public function render()
    {
        return view('livewire.about-us')
            ->layout('components.layouts.app', [
                'title'       => 'Tentang Kami — INDOROSTER | Pabrik Roster Beton Plered Purwakarta',
                'description' => 'Kenali INDOROSTER lebih dekat. Kami adalah pabrik roster beton minimalis tangan pertama di Plered, Purwakarta, Jawa Barat. Berpengalaman melayani ribuan proyek rumah dan komersial di seluruh Indonesia.',
            ]);
    }
}
