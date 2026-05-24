<?php

namespace App\Livewire;

use App\Models\Order;
use App\Mail\InvoiceMail;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Midtrans\Transaction;
use App\Services\MidtransService;

class OrderSuccess extends Component
{
    public $order;
    public $isVerifying = true;

    public function mount()
    {
        $orderNumber = request()->query('order_id');
        
        if (!$orderNumber) {
            return redirect('/');
        }

        $this->order = Order::where('order_number', $orderNumber)->with(['items.variant', 'user'])->first();

        if (!$this->order) {
            return redirect('/');
        }
    }

    public function processPaymentStatus()
    {
        // Initialize Midtrans Configuration
        new MidtransService();

        try {
            // Check status manually as a fallback for localhost webhooks
            $status = (array) Transaction::status($this->order->order_number);
            $transactionStatus = $status['transaction_status'] ?? null;
            
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($this->order->payment_status !== 'paid') {
                    $this->order->update([
                        'payment_status' => 'paid',
                        'status' => 'paid',
                        'paid_at' => now(),
                    ]);

                    // Refresh relationship to ensure variants are still there
                    $this->order->load(['items.variant', 'user']);

                    // Send Invoice Email and Admin Notification
                    try {
                        Mail::to($this->order->shipping_email ?? $this->order->user->email)->send(new InvoiceMail($this->order));
                        Mail::to('abdulhamid66266@gmail.com')->send(new \App\Mail\AdminOrderNotification($this->order));
                    } catch (\Exception $e) {
                        \Log::error('Gagal mengirim email invoice/notifikasi admin: ' . $e->getMessage());
                    }
                }
            } else {
                // If not paid, refresh the order object to match DB
                $this->order->refresh()->load(['items.variant', 'user']);
            }
        } catch (\Exception $e) {
            \Log::warning('Midtrans Status Check Error: ' . $e->getMessage());
        }

        // Clear cart if payment is paid
        if ($this->order->payment_status === 'paid') {
            $this->clearCart();
        }

        $this->isVerifying = false;
    }

    public function checkDatabaseStatus()
    {
        // On localhost/local development, Midtrans webhooks cannot reach the local server.
        // Therefore, we query the Midtrans API directly during polling to ensure auto-refresh works in both local and production.
        $this->processPaymentStatus();
    }

    private function clearCart()
    {
        try {
            $sessionId = \Illuminate\Support\Facades\Cookie::get('cart_session_id');
            $userId = auth()->id() ?? $this->order->user_id;

            $cartQuery = \App\Models\Cart::query();
            if ($userId && $sessionId) {
                $cartQuery->where(function($q) use ($userId, $sessionId) {
                    $q->where('user_id', $userId)
                      ->orWhere('session_id', $sessionId);
                });
            } elseif ($userId) {
                $cartQuery->where('user_id', $userId);
            } elseif ($sessionId) {
                $cartQuery->where('session_id', $sessionId);
            }

            $cartQuery->delete();
            $this->dispatch('cart-updated');
        } catch (\Exception $e) {
            \Log::error('Gagal mengosongkan keranjang di Success Page: ' . $e->getMessage());
        }
    }

    public function refreshStatus()
    {
        $this->isVerifying = true;
        $this->processPaymentStatus();
    }

    public function render()
    {
        return view('livewire.order-success')
            ->layout('components.layouts.app', [
                'title' => 'Pesanan Berhasil | Indoroster',
            ]);
    }
}
