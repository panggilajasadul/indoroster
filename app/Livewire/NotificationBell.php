<?php

namespace App\Livewire;

use Livewire\Component;

class NotificationBell extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $isOpen = false;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $this->notifications = $user->notifications()->take(10)->get();
            $this->unreadCount = $user->unreadNotifications()->count();
        }
    }

    public function toggleDropdown()
    {
        $this->isOpen = !$this->isOpen;
        if ($this->isOpen) {
            $this->loadNotifications(); // Refresh when opening
        }
    }

    public function markAsRead($notificationId, $redirectUrl = null)
    {
        if (auth()->check()) {
            $notification = auth()->user()->notifications()->find($notificationId);
            if ($notification && $notification->unread()) {
                $notification->markAsRead();
                $this->loadNotifications();
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
            $this->loadNotifications();
        }
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
