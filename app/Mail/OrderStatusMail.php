<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $statusType;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, string $statusType)
    {
        $this->order = $order;
        $this->statusType = $statusType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Update Pesanan ' . $this->order->order_number;

        if ($this->statusType === 'processing') {
            $subject = '🔧 Pesanan Anda Sedang Diproses - ' . $this->order->order_number;
        } elseif ($this->statusType === 'shipped') {
            $subject = '🚚 Pesanan Anda Sedang Dikirim - ' . $this->order->order_number;
        } elseif ($this->statusType === 'completed') {
            $subject = '✅ Pesanan Anda Telah Selesai - ' . $this->order->order_number;
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.orders.status',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
