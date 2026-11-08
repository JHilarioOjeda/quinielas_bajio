<?php

namespace App\Livewire\Notifications;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationList extends Component
{
    use WithPagination;

    public function markAsRead(string $id): void
    {
        Auth::user()->notifications()->findOrFail($id)->markAsRead();
        $this->dispatch('notifications-updated');
    }

    public function markAllAsRead(): void
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        $this->dispatch('notifications-updated');
    }

    public function render()
    {
        return view('livewire.notifications.notification-list', [
            'notifications' => Auth::user()->notifications()->latest()->paginate(15),
        ]);
    }
}
