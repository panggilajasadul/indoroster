<?php

namespace App\Livewire\Policy;

use Livewire\Component;

class ShippingPolicy extends Component
{
    public function render()
    {
        $metaTitle = 'Kebijakan Pengiriman & Logistik Pabrik — Armada Resmi | INDOROSTER';
        $metaDesc = 'Informasi resmi pengiriman roster beton langsung dari sentra pabrik Plered Purwakarta ke Jabodetabek, Jawa Barat, Jawa-Bali, dan seluruh Indonesia menggunakan armada mandiri.';

        return view('livewire.policy.shipping-policy')->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDesc,
            'canonicalOverride' => url('/kebijakan-pengiriman'),
        ]);
    }
}
