<?php

namespace App\Livewire\Member;

use App\Models\Order;
use App\Models\SiteSetting;
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
        $orderMode = SiteSetting::getValue('order_mode', 'midtrans');
        $user = auth()->user();
        $userId = $user->id;
        $userPhone = $user->phone ? preg_replace('/[^0-9]/', '', $user->phone) : null;
        $userEmail = $user->email;

        $baseQuery = Order::query();

        // Pisahkan строго sesuai mode transaksi yang aktif
        if ($orderMode === 'whatsapp') {
            $baseQuery->where('order_source', 'whatsapp');
        } else {
            $baseQuery->where(function ($q) {
                $q->where('order_source', '!=', 'whatsapp')->orWhereNull('order_source');
            });
        }

        $baseQuery->where(function ($q) use ($userId, $userPhone, $userEmail) {
            $q->where('user_id', $userId);
            if ($userPhone) {
                $cleanPhone = str_starts_with($userPhone, '62') ? '0'.substr($userPhone, 2) : $userPhone;
                $intlPhone = str_starts_with($userPhone, '0') ? '62'.substr($userPhone, 1) : $userPhone;
                $q->orWhereIn('shipping_phone', array_unique(array_filter([$userPhone, $cleanPhone, $intlPhone])));
            }
            if ($userEmail) {
                $q->orWhere('shipping_email', $userEmail);
            }
        });

        $tabCounts = [
            'semua' => (clone $baseQuery)->count(),
            'penawaran' => (clone $baseQuery)->where('status', 'draft')->count(),
            'belum-bayar' => (clone $baseQuery)->where('status', 'pending_payment')->where('payment_status', '!=', 'paid')->where(function ($q) {
                $q->whereNull('down_payment_amount')->orWhere('down_payment_amount', '<=', 0);
            })->count(),
            'diproses' => (clone $baseQuery)->where(function ($q) {
                $q->whereIn('status', ['paid', 'processing'])
                    ->orWhere(function ($sub) {
                        $sub->where('payment_status', 'paid')
                            ->whereNotIn('status', ['shipped', 'delivered', 'completed', 'cancelled']);
                    });
            })->count(),
            'dikirim' => (clone $baseQuery)->whereIn('status', ['shipped', 'delivered'])->count(),
            'selesai' => (clone $baseQuery)->where('status', 'completed')->count(),
            'batal' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];

        $query = (clone $baseQuery)
            ->with(['items.product.media', 'items.variant', 'invoice', 'payments', 'batches'])
            ->orderByDesc('created_at');

        // Apply Tab Filter
        switch ($this->activeTab) {
            case 'penawaran':
                $query->where('status', 'draft');
                break;
            case 'belum-bayar':
                $query->where('status', 'pending_payment')
                    ->where('payment_status', '!=', 'paid')
                    ->where(function ($q) {
                        $q->whereNull('down_payment_amount')->orWhere('down_payment_amount', '<=', 0);
                    });
                break;
            case 'diproses':
                $query->where(function ($q) {
                    $q->whereIn('status', ['paid', 'processing'])
                        ->orWhere(function ($sub) {
                            $sub->where('payment_status', 'paid')
                                ->whereNotIn('status', ['shipped', 'delivered', 'completed', 'cancelled']);
                        });
                });
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
            'orderMode' => $orderMode,
        ])->layout('components.layouts.app', ['title' => ($orderMode === 'whatsapp' ? 'Pesanan WhatsApp & Proyek - IndoRoster' : 'Riwayat Pesanan - IndoRoster')]);
    }
}
