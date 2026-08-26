<?php

namespace App\Livewire;

use App\Models\Page;
use App\Models\SiteSetting;
use Livewire\Component;

class Contact extends Component
{
    public $name;

    public $email;

    public $phone;

    public $message;

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'phone' => 'nullable|min:8',
        'message' => 'required|min:10',
    ];

    public function sendWhatsApp()
    {
        $this->validate();

        $rawWa = SiteSetting::getValue('whatsapp_number', '0813-8970-9847');
        $phoneNumber = preg_replace('/[^0-9]/', '', $rawWa);
        if (str_starts_with($phoneNumber, '0')) {
            $phoneNumber = '62'.substr($phoneNumber, 1);
        }

        $text = "Halo IndoRoster,\n\n".
                "Saya ingin menanyakan harga & stok roster:\n".
                "*Nama:* {$this->name}\n".
                "*Email:* {$this->email}\n".
                ($this->phone ? "*WhatsApp:* {$this->phone}\n" : '').
                "*Pesan:* {$this->message}\n\n".
                'Terima kasih.';

        $url = "https://wa.me/{$phoneNumber}?text=".urlencode($text);

        return redirect()->away($url);
    }

    public function render()
    {
        $page = Page::where('slug', 'kontak')->where('is_active', true)->first();

        $metaTitle = $page?->meta_title ?: 'Kontak Kami | INDOROSTER — Hubungi Pabrik Roster Beton';
        $metaDesc = $page?->meta_description ?: 'Hubungi INDOROSTER untuk konsultasi, pemesanan roster beton minimalis, atau informasi pengiriman. Kami siap membantu Anda 24/7 via WhatsApp, telepon, atau email.';

        return view('livewire.contact', [
            'page' => $page,
        ])->layout('components.layouts.app', [
            'title' => $metaTitle,
            'description' => $metaDesc,
            'canonicalOverride' => route('contact'),
        ]);
    }
}
