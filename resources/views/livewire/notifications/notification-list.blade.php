<div class="py-8 sm:py-12">
    <div class="mx-auto max-w-4xl px-3 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 px-5 py-4">
                <div><h2 class="text-xl font-bold text-gray-900">Mis notificaciones</h2><p class="text-sm text-gray-500">Consulta los avisos de tu cuenta.</p></div>
                <button type="button" wire:click="markAllAsRead" class="text-sm font-semibold text-primarycolor hover:underline">Marcar todas como leídas</button>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse ($notifications as $notification)
                    @php($type = \App\Enums\NotificationType::tryFrom($notification->data['type'] ?? ''))
                    <div wire:key="notification-list-{{ $notification->id }}" class="flex items-start justify-between gap-3 px-5 py-4 {{ $notification->read_at ? '' : 'bg-green-50' }}">
                        <div class="min-w-0"><p class="text-xs font-semibold text-primarycolor">{{ $type?->label() ?? 'Notificación' }} @if (! $notification->read_at) <span class="ml-1 rounded-full bg-green-100 px-2 py-0.5 text-green-800">Nueva</span> @endif</p><p class="mt-1 break-words text-sm text-gray-800">{{ $notification->data['message'] ?? '' }}</p><p class="mt-1 text-xs text-gray-500">{{ $notification->created_at->format('d/m/Y H:i') }}</p></div>
                        <div class="flex shrink-0 flex-col items-end gap-2 text-xs font-semibold">
                            @if ($type && auth()->user()->isAdmin()) <a href="{{ route($type->routeName()) }}" class="text-primarycolor hover:underline">Ver boletos</a> @endif
                            @if (! $notification->read_at) <button type="button" wire:click="markAsRead('{{ $notification->id }}')" class="text-gray-600 hover:underline">Marcar como leída</button> @endif
                        </div>
                    </div>
                @empty
                    <p class="px-5 py-10 text-center text-sm text-gray-500">Aún no tienes notificaciones.</p>
                @endforelse
            </div>
            <div class="px-5 py-4">{{ $notifications->links() }}</div>
        </div>
    </div>
</div>
