<?php

namespace App\Livewire;

use App\Models\Order;
use App\Services\MidtransService;
use Livewire\Component;

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

        if ($orderNumber) {
            $this->searchQuery = $orderNumber;
            if ($contact) {
                $this->contactQuery = $contact;
                $this->track();
            }
        }
    }

    public function track()
    {
        $this->validate();

        $this->searched = true;

        $invoiceNum = trim($this->searchQuery);
        $contact = trim($this->contactQuery);

        $rawPhone = preg_replace('/[^0-9]/', '', $contact);
        $phone0 = str_starts_with($rawPhone, '62') ? '0'.substr($rawPhone, 2) : $rawPhone;
        $phone62 = str_starts_with($rawPhone, '0') ? '62'.substr($rawPhone, 1) : $rawPhone;

        // Find order matching number and matching email OR phone
        $this->order = Order::where('order_number', $invoiceNum)
            ->where(function ($q) use ($contact, $rawPhone, $phone0, $phone62) {
                $q->where('shipping_email', $contact)
                    ->orWhere('shipping_phone', $contact)
                    ->orWhere('shipping_phone', $phone0)
                    ->orWhere('shipping_phone', $phone62);
                if (! empty($rawPhone)) {
                    $q->orWhereRaw("REPLACE(REPLACE(REPLACE(shipping_phone, '-', ''), ' ', ''), '+', '') = ?", [$rawPhone]);
                }
            })
            ->with(['items.product.media', 'items.variant', 'invoice', 'batches'])
            ->first();

        if (! $this->order) {
            session()->flash('error', 'Pesanan tidak ditemukan. Pastikan Nomor Invoice dan Email/No. WhatsApp Anda sesuai.');
        }
    }

    public function payOrder()
    {
        if (! $this->order) {
            return;
        }

        if ($this->order->payment_status === 'paid') {
            session()->flash('error', 'Pesanan ini sudah dibayar.');

            return;
        }

        try {
            $snapToken = $this->order->snap_token;
            if (! $snapToken) {
                $midtrans = new MidtransService;
                $snapToken = $midtrans->getSnapToken($this->order);
                $this->order->update(['snap_token' => $snapToken]);
            }

            // Dispatch event to open Midtrans Snap popup
            $this->dispatch('snap-pay', token: $snapToken, order_id: $this->order->order_number);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memproses pembayaran: '.$e->getMessage());
        }
    }

    public function resetTracking()
    {
        $this->order = null;
        $this->searched = false;
        $this->searchQuery = '';
        $this->contactQuery = '';
    }

    public function getDestinationCoordinates(): array
    {
        if (! $this->order) {
            return ['lat' => -6.2088, 'lng' => 106.8456, 'is_exact' => false];
        }

        if ($this->order->shipping_latitude && $this->order->shipping_longitude) {
            return [
                'lat' => (float) $this->order->shipping_latitude,
                'lng' => (float) $this->order->shipping_longitude,
                'is_exact' => true,
            ];
        }

        // Estimasi koordinat kota jika belum terekam
        $city = strtolower($this->order->shipping_city ?? '');
        $defaults = [
            'surabaya' => [-7.2575, 112.7521],
            'jakarta' => [-6.2088, 106.8456],
            'bandung' => [-6.9175, 107.6191],
            'semarang' => [-6.9667, 110.4167],
            'yogyakarta' => [-7.7956, 110.3695],
            'solo' => [-7.5755, 110.8243],
            'surakarta' => [-7.5755, 110.8243],
            'malang' => [-7.9666, 112.6326],
            'cirebon' => [-6.7320, 108.5523],
            'bogor' => [-6.5971, 106.8060],
            'depok' => [-6.4025, 106.7942],
            'tangerang' => [-6.1783, 106.6319],
            'bekasi' => [-6.2383, 106.9756],
            'karawang' => [-6.3073, 107.3079],
            'purwakarta' => [-6.6689917, 107.3619295],
            'bali' => [-8.6705, 115.2126],
            'denpasar' => [-8.6705, 115.2126],
            'serang' => [-6.1104, 106.1640],
            'cilegon' => [-6.0174, 106.0538],
            'sukabumi' => [-6.9277, 106.9300],
            'tasikmalaya' => [-7.3274, 108.2207],
            'garut' => [-7.2279, 107.9087],
        ];

        foreach ($defaults as $key => $coords) {
            if (str_contains($city, $key)) {
                return ['lat' => $coords[0], 'lng' => $coords[1], 'is_exact' => false];
            }
        }

        return ['lat' => -6.2088, 'lng' => 106.8456, 'is_exact' => false];
    }

    public function render()
    {
        $destCoords = $this->getDestinationCoordinates();

        return view('livewire.order-tracking', [
            'destCoords' => $destCoords,
        ])->layout('components.layouts.app', ['title' => 'Lacak Pesanan - Indoroster']);
    }
}
