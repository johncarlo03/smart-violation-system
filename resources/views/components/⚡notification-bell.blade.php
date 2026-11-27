<?php

use Livewire\Component;

new class extends Component {

    public string $office = '';
    public ?int $departmentId = null;

    public function mount(): void
    {
        $user = auth()->user();

        $this->office = match ((int) $user->role) {
            7 => 'dean',
            6 => 'chairman',
            3 => 'sao',
            default => abort(403, 'Unauthorized access.'),
        };

        $this->departmentId = $user->department_id
            ? (int) $user->department_id
            : null;
    }

    public function getListeners(): array
    {
        $userId = auth()->id();

        return [
            // Triggered immediately by violation-management on the dashboard
            'notification-received' => '$refresh',
        ];
    }

    public function markAsRead(string $id): void
    {
        auth()->user()
            ->notifications()
            ->where('id', $id)
            ->update(['read_at' => now()]);
    }

    public function markAllAsRead(): void
    {
        auth()->user()
            ->unreadNotifications()
            ->update(['read_at' => now()]);
    }

    public function with(): array
    {
        $user = auth()->user();

        return [
            'notifications' => $user->notifications()->latest()->take(10)->get(),
            'unreadCount'   => $user->unreadNotifications()->count(),
        ];
    }
};
?>

<div x-data="{ bellOpen: false }" class="relative"
     x-on:show-violation-toast.window="setTimeout(() => $wire.$refresh(), 1500)">
    <button type="button" @click="bellOpen = !bellOpen" class="relative p-2 rounded-xl hover:bg-gray-100 transition focus:outline-none">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-5-5.9V4a1 1 0 00-2 0v1.1A6 6 0 006 11v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 01-6 0" />
        </svg>

        @if ($unreadCount > 0)
            <span class="absolute top-0.5 right-0.5 min-w-5 h-5 px-1 flex items-center justify-center rounded-full bg-red-500 text-white text-[11px] font-bold">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <div x-show="bellOpen" @click.outside="bellOpen = false" x-transition x-cloak
        class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-gray-200 z-50 overflow-hidden">

        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-800">Notifications</h3>
                <p class="text-xs text-gray-500">{{ $unreadCount }} unread</p>
            </div>

            @if ($unreadCount > 0)
                <button type="button" wire:click.stop="markAllAsRead" wire:loading.attr="disabled"
                    wire:target="markAllAsRead"
                    class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 disabled:opacity-50">
                    <span wire:loading.remove wire:target="markAllAsRead">Mark all as read</span>
                    <span wire:loading wire:target="markAllAsRead">Updating...</span>
                </button>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse ($notifications as $notification)
                <button type="button" wire:key="notification-{{ $notification->id }}"
                    wire:click.stop="markAsRead('{{ $notification->id }}')"
                    class="w-full text-left p-4 border-b border-gray-100 hover:bg-gray-50 transition
                           {{ $notification->read_at ? 'bg-white' : 'bg-indigo-50/60' }}">
                    <div class="flex gap-3">
                        <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                            <span>!</span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ $notification->data['message'] ?? 'You have a new notification.' }}
                            </p>

                            @if (!empty($notification->data['student']))
                                <p class="text-xs text-gray-600 mt-1">Student: {{ $notification->data['student'] }}</p>
                            @endif

                            @if (!empty($notification->data['offense']))
                                <p class="text-xs text-gray-600">Offense: {{ $notification->data['offense'] }}</p>
                            @endif

                            <p class="text-xs text-gray-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>

                        @if (!$notification->read_at)
                            <span class="w-2 h-2 rounded-full bg-indigo-600 shrink-0 mt-2"></span>
                        @endif
                    </div>
                </button>
            @empty
                <div class="p-8 text-center">
                    <p class="text-sm text-gray-500">No notifications yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@script
<script>
    if (window.Echo) {
        window.Echo.private('App.Models.User.' + @js(auth()->id()))
            .notification(() => $wire.$refresh());
    }
</script>
@endscript