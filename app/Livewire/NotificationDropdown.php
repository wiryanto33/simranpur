<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Notifications\DatabaseNotification;

class NotificationDropdown extends Component
{
    public $unreadCount = 0;
    public $notifications = [];

    public function getListeners()
    {
        return [
            'echo:private-App.Models.User.' . auth()->id() . ',.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated' => 'refreshNotifications',
            'refreshNotifications' => '$refresh'
        ];
    }

    public function mount()
    {
        $this->refreshNotifications();
    }

    public function refreshNotifications()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $this->unreadCount = $user->unreadNotifications()->count();
            // Get latest 5 for dropdown
            $this->notifications = $user->notifications()->take(5)->get();
        }
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
        }
        $this->refreshNotifications();
        
        // redirect to url if exists
        if (isset($notification->data['url']) && $notification->data['url'] !== '#') {
            return redirect($notification->data['url']);
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        $this->refreshNotifications();
    }

    public function render()
    {
        return view('livewire.notification-dropdown');
    }
}
