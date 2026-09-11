<?php

namespace App\Livewire\Policy;

use Livewire\Component;

class ReturnPolicy extends Component
{
    public function render()
    {
        $metaTitle = 'Kebijakan Garansi & Pengembalian Barang — Garansi Pecah Ganti 100% | INDOROSTER';
        $metaDesc = 'Kebijakan resmi garansi pengiriman aman dan retur penggantian 100% baru untuk roster beton IndoRoster yang pecah atau retak saat serah terima di lokasi proyek.';

        return view('livewire.policy.return-policy')->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDesc,
            'canonicalOverride' => url('/kebijakan-garansi-pengembalian'),
        ]);
    }
}
