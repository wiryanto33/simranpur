<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class NotificationIndex extends Component
{
    use WithPagination;

    public $filter = 'all'; // all, unread

    public function getListeners()
    {
        return [
            'echo:private-App.Models.User.' . auth()->id() . ',.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated' => '$refresh'
        ];
    }

    public function mount()
    {
        $this->filter = request('filter', 'all');
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function markAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->markAsRead();
            
            // redirect to url if exists
            if (isset($notification->data['url']) && $notification->data['url'] !== '#') {
                return redirect($notification->data['url']);
            }
        }
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
    }
    
    public function delete($notificationId)
    {
        $notification = auth()->user()->notifications()->find($notificationId);
        if ($notification) {
            $notification->delete();
        }
    }

    public function render()
    {
        if ($this->filter === 'global' && auth()->user()->hasRole('Admin')) {
            $query = \Illuminate\Notifications\DatabaseNotification::with('notifiable')->latest();
        } else {
            $query = auth()->user()->notifications();
            
            if ($this->filter === 'unread') {
                $query = auth()->user()->unreadNotifications();
            }
        }

        $notifications = $query->paginate(15);

        return view('livewire.notification-index', [
            'notifications' => $notifications
        ])->layout('layouts.app');
    }
}
