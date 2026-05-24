<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;

class OrderTracking extends Component
{
    public $searchQuery = '';
    public $contactQuery = '';
    public $order = null;
    public $searched = false;

    protected $rules = [
        'searchQuery' => 'required|string',
        'contactQuery' => 'required|string',
    ];

    protected $messages = [
        'searchQuery.required' => 'Nomor Invoice wajib diisi.',
        'contactQuery.required' => 'Email atau Nomor WhatsApp wajib diisi.',
    ];

    public function mount()
    {
        $orderNumber = request()->query('order_number');
        $contact = request()->query('contact');

        if ($orderNumber && $contact) {
            $this->searchQuery = $orderNumber;
            $this->contactQuery = $contact;
            $this->track();
        }
    }

    public function track()
    {
        $this->validate();

        $this->searched = true;

        $invoiceNum = trim($this->searchQuery);
        $contact = trim($this->contactQuery);

        // Find order matching number and matching email OR phone
        $this->order = Order::where('order_number', $invoiceNum)
            ->where(function($q) use ($contact) {
                $q->where('shipping_email', $contact)
                  ->orWhere('shipping_phone', $contact);
            })
            ->with(['items.product.media', 'items.variant', 'invoice'])
            ->first();

        if (!$this->order) {
            session()->flash('error', 'Pesanan tidak ditemukan. Pastikan Nomor Invoice dan Email/No. WhatsApp Anda sesuai.');
        }
    }

    public function payOrder()
    {
        if (!$this->order) return;
        
        if ($this->order->payment_status === 'paid') {
            session()->flash('error', 'Pesanan ini sudah dibayar.');
            return;
        }

        try {
            $snapToken = $this->order->snap_token;
            if (!$snapToken) {
                $midtrans = new \App\Services\MidtransService();
                $snapToken = $midtrans->getSnapToken($this->order);
                $this->order->update(['snap_token' => $snapToken]);
            }

            // Dispatch event to open Midtrans Snap popup
            $this->dispatch('snap-pay', token: $snapToken, order_id: $this->order->order_number);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function resetTracking()
    {
        $this->order = null;
        $this->searched = false;
        $this->searchQuery = '';
        $this->contactQuery = '';
    }

    public function render()
    {
        return view('livewire.order-tracking')
            ->layout('components.layouts.app', ['title' => 'Lacak Pesanan - Indoroster']);
    }
}
