<?php

namespace App\Livewire;

use Livewire\Component;

class Contact extends Component
{
    public $name;
    public $email;
    public $message;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'message' => 'required|min:10',
    ];

    public function sendWhatsApp()
    {
        $this->validate();

        $phoneNumber = '6281389709847';
        $text = "Halo Indoroster,\n\n" .
                "Saya ingin menanyakan harga & stok:\n" .
                "*Nama:* {$this->name}\n" .
                "*Email:* {$this->email}\n" .
                "*Pesan:* {$this->message}\n\n" .
                "Terima kasih.";

        $url = "https://wa.me/{$phoneNumber}?text=" . urlencode($text);

        return redirect()->away($url);
    }

    public function render()
    {
        return view('livewire.contact')->layout('components.layouts.app', [
            'title'       => 'Kontak Kami | INDOROSTER — Hubungi Pabrik Roster Beton',
            'description' => 'Hubungi INDOROSTER untuk konsultasi, pemesanan roster beton minimalis, atau informasi pengiriman. Kami siap membantu Anda 24/7 via WhatsApp, telepon, atau email.',
            'robots'      => 'noindex, follow',
        ]);
    }
}
