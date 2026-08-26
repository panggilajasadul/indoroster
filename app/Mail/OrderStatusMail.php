<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\OrderBatch;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public string $statusType;

    public ?OrderBatch $batch;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order, string $statusType, ?OrderBatch $batch = null)
    {
        $this->order = $order;
        $this->statusType = $statusType;
        $this->batch = $batch;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Update Pesanan '.$this->order->order_number;

        if ($this->statusType === 'processing') {
            if ($this->order->fulfillment_type === 'po_batch') {
                $subject = '📋 Konfirmasi & Jadwal Pengiriman Bertahap ('.$this->order->batch_count.' Batch) - '.$this->order->order_number;
            } elseif ($this->order->fulfillment_type === 'po_single') {
                $subject = '🔨 Pesanan Anda Masuk Antrean Produksi (PO) - '.$this->order->order_number;
            } else {
                $subject = '📦 Pesanan Ready Stock Anda Sedang Disiapkan - '.$this->order->order_number;
            }
        } elseif ($this->statusType === 'shipped') {
            $subject = '🚚 Pesanan Anda Sedang Dikirim - '.$this->order->order_number;
        } elseif ($this->statusType === 'batch_shipped' && $this->batch) {
            $subject = "🚚 Pengiriman {$this->batch->batch_name} dari {$this->order->batch_count} Batch Sedang Menuju Lokasi Anda - ".$this->order->order_number;
        } elseif ($this->statusType === 'batch_delivered' && $this->batch) {
            $subject = "✅ Pengiriman {$this->batch->batch_name} dari {$this->order->batch_count} Batch Telah Tiba di Lokasi - ".$this->order->order_number;
        } elseif ($this->statusType === 'completed') {
            $subject = '✅ Pesanan Anda Telah Selesai Diterima - '.$this->order->order_number;
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
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
