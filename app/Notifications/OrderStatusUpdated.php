<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    public $order;

    public $statusName;

    /**
     * Create a new notification instance.
     */
    public function __construct($order, $statusName)
    {
        $this->order = $order;
        $this->statusName = $statusName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'order_status',
            'title' => 'Status Pesanan Diperbarui',
            'message' => 'Pesanan Anda #'.($this->order->invoice_number ?? $this->order->order_number).' telah diperbarui menjadi '.$this->statusName.'.',
            'url' => route('order.tracking', [
                'order_number' => $this->order->order_number,
                'contact' => $this->order->shipping_email ?? $this->order->shipping_phone,
            ]),
            'order_id' => $this->order->id,
            'status' => $this->statusName,
        ];
    }
}
