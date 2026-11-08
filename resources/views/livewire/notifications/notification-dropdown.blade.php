<div class="relative" wire:poll.10s @click.outside="$wire.set('open', false)">
    <button type="button" wire:click="toggle"
        aria-label="Notificaciones" aria-expanded="{{ $open ? 'true' : 'false' }}"
        class="relative inline-flex min-h-11 min-w-11 items-center justify-center rounded-full text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-primarycolor">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.86 17.08A23.84 23.84 0 0 0 19.5 16c-1.28-1.28-1.5-3.16-1.5-5a6 6 0 1 0-12 0c0 1.84-.22 3.72-1.5 5 1.47.51 3.02.88 4.64 1.08m5.72 0a24.26 24.26 0 0 1-5.72 0m5.72 0a3 3 0 1 1-5.72 0" />
        </svg>
        @if ($unreadCount > 0)
            <span class="absolute right-0 top-0 flex min-h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-bold text-white">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
            <span class="sr-only">{{ $unreadCount }} notificaciones sin leer</span>
        @endif
    </button>

    @if ($open)
        <div class="absolute right-0 z-50 mt-2 w-[min(90vw,24rem)] overflow-hidden rounded-xl border border-gray-200 bg-white shadow-xl">
            <div class="flex items-center justify-between gap-2 border-b border-gray-100 px-4 py-3">
                <div><h2 class="text-base font-bold text-gray-900">Notificaciones</h2><p class="text-xs text-gray-500">{{ $unreadCount }} sin leer</p></div>
                @if ($unreadCount > 0)
                    <button type="button" wire:click="markAllAsRead" class="text-xs font-semibold text-primarycolor hover:underline">Marcar todas como leídas</button>
                @endif
            </div>
            <div class="max-h-96 overflow-y-auto">
                @forelse ($notifications as $notification)
                    @php($type = \App\Enums\NotificationType::tryFrom($notification->data['type'] ?? ''))
                    <button type="button" wire:key="notification-{{ $notification->id }}" wire:click="openNotification('{{ $notification->id }}')"
                        class="flex w-full gap-3 border-b border-gray-100 px-4 py-3 text-left hover:bg-gray-50 {{ $notification->read_at ? '' : 'bg-green-50' }}">
                        <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full {{ $notification->read_at ? 'bg-gray-300' : 'bg-primarycolor' }}" aria-hidden="true"></span>
                        <span class="min-w-0"><span class="block text-xs font-semibold text-primarycolor">{{ $type?->label() ?? 'Notificación' }}</span><span class="mt-1 block break-words text-sm text-gray-800">{{ $notification->data['message'] ?? '' }}</span><span class="mt-1 block text-xs text-gray-500">{{ $notification->created_at->format('d/m/Y H:i') }}</span></span>
                    </button>
                @empty
                    <p class="px-4 py-8 text-center text-sm text-gray-500">Aún no tienes notificaciones.</p>
                @endforelse
            </div>
            <a href="{{ route('notifications.index') }}" class="block px-4 py-3 text-center text-sm font-semibold text-primarycolor hover:bg-gray-50">Ver todas las notificaciones</a>
        </div>
    @endif
</div>
