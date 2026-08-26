<?php

namespace App\Livewire\Member;

use App\Models\Order;
use App\Services\MidtransService;
use Livewire\Component;

class OrderHistory extends Component
{
    public $activeTab = 'semua';

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function payOrder($orderId)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($orderId);

        if ($order->payment_status === 'paid') {
            session()->flash('error', 'Pesanan ini sudah dibayar.');

            return;
        }

        try {
            $snapToken = $order->snap_token;
            if (! $snapToken) {
                $midtrans = new MidtransService;
                $snapToken = $midtrans->getSnapToken($order);
                $order->update(['snap_token' => $snapToken]);
            }

            // Dispatch event to open Midtrans Snap popup
            $this->dispatch('snap-pay', token: $snapToken, order_id: $order->order_number);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memproses pembayaran: '.$e->getMessage());
        }
    }

    public function render()
    {
        $userId = auth()->id();
        $baseQuery = Order::where('user_id', $userId);

        $tabCounts = [
            'semua' => (clone $baseQuery)->count(),
            'belum-bayar' => (clone $baseQuery)->where('status', 'pending_payment')->where('payment_status', '!=', 'paid')->count(),
            'diproses' => (clone $baseQuery)->whereIn('status', ['paid', 'processing'])->count(),
            'dikirim' => (clone $baseQuery)->whereIn('status', ['shipped', 'delivered'])->count(),
            'selesai' => (clone $baseQuery)->where('status', 'completed')->count(),
            'batal' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];

        $query = (clone $baseQuery)
            ->with(['items.product.media', 'items.variant', 'invoice'])
            ->orderByDesc('created_at');

        // Apply Tab Filter
        switch ($this->activeTab) {
            case 'belum-bayar':
                $query->where('status', 'pending_payment')->where('payment_status', '!=', 'paid');
                break;
            case 'diproses':
                $query->whereIn('status', ['paid', 'processing']);
                break;
            case 'dikirim':
                $query->whereIn('status', ['shipped', 'delivered']);
                break;
            case 'selesai':
                $query->where('status', 'completed');
                break;
            case 'batal':
                $query->where('status', 'cancelled');
                break;
        }

        $orders = $query->get();

        return view('livewire.member.order-history', [
            'orders' => $orders,
            'tabCounts' => $tabCounts,
        ])->layout('components.layouts.app', ['title' => 'Riwayat Pesanan - Indoroster']);
    }
}
