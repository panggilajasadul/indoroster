<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CompleteAddressNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Lengkapi Alamat Anda',
            'message' => 'Email berhasil diverifikasi! Silakan lengkapi alamat pengiriman Anda untuk mempermudah transaksi.',
            'url' => route('member.addresses'),
            'type' => 'system',
        ];
    }
}
