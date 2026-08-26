<?php

namespace App\Livewire\Member;

use Livewire\Component;
use Livewire\WithPagination;

class Notifications extends Component
{
    use WithPagination;

    public function markAsRead($notificationId, $redirectUrl = null)
    {
        if (auth()->check()) {
            $notification = auth()->user()->notifications()->find($notificationId);
            if ($notification && $notification->unread()) {
                $notification->markAsRead();
            }
        }

        if ($redirectUrl && $redirectUrl !== '#') {
            return redirect($redirectUrl);
        }
    }

    public function markAllAsRead()
    {
        if (auth()->check()) {
            auth()->user()->unreadNotifications->markAsRead();
            session()->flash('success', 'Semua notifikasi ditandai sebagai dibaca.');
        }
    }

    public function deleteNotification($notificationId)
    {
        if (auth()->check()) {
            $notification = auth()->user()->notifications()->find($notificationId);
            if ($notification) {
                $notification->delete();
                session()->flash('success', 'Notifikasi berhasil dihapus.');
            }
        }
    }

    public function deleteAllNotifications()
    {
        if (auth()->check()) {
            auth()->user()->notifications()->delete();
            session()->flash('success', 'Semua riwayat notifikasi telah dibersihkan.');
        }
    }

    public function render()
    {
        $notifications = auth()->user()
            ->notifications()
            ->paginate(15);

        return view('livewire.member.notifications', [
            'notifications' => $notifications,
        ])->layout('components.layouts.app', ['title' => 'Notifikasi Saya - Indoroster']);
    }
}
