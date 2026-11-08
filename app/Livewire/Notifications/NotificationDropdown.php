<?php

namespace App\Livewire\Notifications;

use App\Enums\NotificationType;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationDropdown extends Component
{
    public bool $open = false;

    #[On('notifications-updated')]
    public function refreshNotifications(): void {}

    public function toggle(): void
    {
        $this->open = ! $this->open;
    }

    /** Solo se consulta y modifica la notificación del usuario autenticado. */
    public function openNotification(string $id): void
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $type = NotificationType::tryFrom($notification->data['type'] ?? '');
        if ($type && Auth::user()->isAdmin()) {
            $this->redirectRoute($type->routeName());
        }
    }

    public function markAllAsRead(): void
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
    }

    public function render()
    {
        $user = Auth::user();

        return view('livewire.notifications.notification-dropdown', [
            'unreadCount' => $user->unreadNotifications()->count(),
            'notifications' => $this->open ? $user->notifications()->latest()->limit(10)->get() : collect(),
        ]);
    }
}
