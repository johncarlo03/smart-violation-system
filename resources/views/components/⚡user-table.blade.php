<?php

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;

new class extends Component {
    use WithPagination;

    #[Url]
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[Computed]
    public function users()
    {
        $search = trim($this->search);

        return User::query()
            ->select([
                'id',
                'name',
                'email',
                'id_number',
                'rfid_number',
                'role',
                'department_id',
                'course_id',
                'year_level',
            ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'ilike', "%{$search}%")
                        ->orWhere('email', 'ilike', "%{$search}%")
                        ->orWhere('id_number', 'ilike', "%{$search}%")
                        ->orWhere('rfid_number', 'ilike', "%{$search}%");
                });
            })
            ->orderByRaw("
                CASE role
                    WHEN 4 THEN 1
                    WHEN 3 THEN 2
                    WHEN 7 THEN 3
                    WHEN 6 THEN 4
                    WHEN 5 THEN 5
                    WHEN 2 THEN 6
                    WHEN 1 THEN 7
                    ELSE 8
                END ASC
            ")
            ->paginate(10);
    }
};
?>

<div class="min-w-0">
    {{-- Search Bar --}}
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100 bg-gray-50/50">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search by name, email, ID, or RFID..."
            class="w-full rounded-xl border-gray-200 text-base sm:text-sm font-medium text-gray-800 shadow-xs focus:border-[#1f2937] focus:ring-[#1f2937]"
        >
    </div>

    {{-- Mobile Card List (Visible below md) --}}
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($this->users as $user)
            @php
                $roleMeta = match ((int) $user->role) {
                    1 => ['label' => 'Student', 'classes' => 'bg-gray-100 text-gray-800 border-gray-200'],
                    2 => ['label' => 'CSO Officer', 'classes' => 'bg-amber-50 text-amber-800 border-amber-200'],
                    3 => ['label' => 'SAO Faculty', 'classes' => 'bg-emerald-50 text-emerald-800 border-emerald-200'],
                    4 => ['label' => 'Super Admin', 'classes' => 'bg-red-50 text-red-700 border-red-200'],
                    5 => ['label' => 'Teaching Staff', 'classes' => 'bg-amber-50 text-amber-800 border-amber-200'],
                    6 => ['label' => 'Chairman', 'classes' => 'bg-amber-50 text-amber-800 border-amber-200'],
                    7 => ['label' => 'Dean', 'classes' => 'bg-amber-50 text-amber-800 border-amber-200'],
                    default => ['label' => 'User', 'classes' => 'bg-gray-100 text-gray-700 border-gray-200'],
                };
            @endphp

            <div class="p-4 space-y-3 hover:bg-gray-50/70 transition">
                <div class="flex items-start justify-between gap-2.5">
                    <div class="min-w-0">
                        <div class="font-bold text-sm text-gray-900 truncate">{{ $user->name }}</div>
                        <div class="text-xs text-gray-500 truncate mt-0.5">{{ $user->email }}</div>
                    </div>

                    <span class="shrink-0 text-[11px] font-bold px-2.5 py-1 rounded-full border {{ $roleMeta['classes'] }}">
                        {{ $roleMeta['label'] }}
                    </span>
                </div>

                <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 flex flex-wrap items-center justify-between gap-2 text-xs text-gray-600">
                    <div><span class="font-semibold text-gray-800">ID:</span> {{ $user->id_number }}</div>
                    <div><span class="font-semibold text-gray-800">RFID:</span> {{ $user->rfid_number ?? 'N/A' }}</div>
                </div>

                <div class="flex items-center gap-2 pt-0.5">
                    <button type="button"
                        class="edit-user-btn flex-1 inline-flex items-center justify-center px-3 py-2 rounded-xl bg-[#1f2937] hover:bg-gray-800 text-white text-xs font-bold transition"
                        data-id="{{ $user->id }}"
                        data-name="{{ $user->name }}"
                        data-id_number="{{ $user->id_number }}"
                        data-rfid_number="{{ $user->rfid_number }}"
                        data-email="{{ $user->email }}"
                        data-role="{{ $user->role }}"
                        data-department_id="{{ $user->department_id ?? '' }}"
                        data-course_id="{{ $user->course_id ?? '' }}"
                        data-year_level="{{ $user->year_level ?? '' }}">
                        Edit
                    </button>

                    <button type="button"
                        class="delete-user-btn flex-1 inline-flex items-center justify-center px-3 py-2 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 text-xs font-bold transition"
                        data-id="{{ $user->id }}"
                        data-name="{{ $user->name }}">
                        Delete
                    </button>
                </div>
            </div>
        @empty
            <div class="p-10 text-center text-gray-400 text-sm">
                @if($search)
                    No users found matching "{{ $search }}".
                @else
                    No users registered yet.
                @endif
            </div>
        @endforelse
    </div>

    {{-- Desktop Table (Visible md and up) --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50/70">
                    <th class="p-4 text-xs font-black uppercase tracking-wider text-gray-500">Name / Email</th>
                    <th class="p-4 text-xs font-black uppercase tracking-wider text-gray-500">ID / RFID</th>
                    <th class="p-4 text-xs font-black uppercase tracking-wider text-gray-500">Role</th>
                    <th class="p-4 text-xs font-black uppercase tracking-wider text-gray-500 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($this->users as $user)
                    @php
                        $roleMeta = match ((int) $user->role) {
                            1 => ['label' => 'Student', 'classes' => 'bg-gray-100 text-gray-800 border-gray-200'],
                            2 => ['label' => 'CSO Officer', 'classes' => 'bg-amber-50 text-amber-800 border-amber-200'],
                            3 => ['label' => 'SAO Faculty', 'classes' => 'bg-emerald-50 text-emerald-800 border-emerald-200'],
                            4 => ['label' => 'Super Admin', 'classes' => 'bg-red-50 text-red-700 border-red-200'],
                            5 => ['label' => 'Teaching Staff', 'classes' => 'bg-amber-50 text-amber-800 border-amber-200'],
                            6 => ['label' => 'Chairman', 'classes' => 'bg-amber-50 text-amber-800 border-amber-200'],
                            7 => ['label' => 'Dean', 'classes' => 'bg-amber-50 text-amber-800 border-amber-200'],
                            default => ['label' => 'User', 'classes' => 'bg-gray-100 text-gray-700 border-gray-200'],
                        };
                    @endphp

                    <tr class="hover:bg-gray-50/70 transition">
                        <td class="p-4">
                            <div class="font-bold text-gray-900 text-sm">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $user->email }}</div>
                        </td>
                        <td class="p-4 text-sm font-medium text-gray-700">
                            <div>ID: {{ $user->id_number }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">RFID: {{ $user->rfid_number ?? 'N/A' }}</div>
                        </td>
                        <td class="p-4">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full border {{ $roleMeta['classes'] }}">
                                {{ $roleMeta['label'] }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="inline-flex items-center justify-center gap-2">
                                <button type="button"
                                    class="edit-user-btn inline-flex items-center px-3 py-1.5 rounded-xl bg-[#1f2937] hover:bg-gray-800 text-white text-xs font-bold transition"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-id_number="{{ $user->id_number }}"
                                    data-rfid_number="{{ $user->rfid_number }}"
                                    data-email="{{ $user->email }}"
                                    data-role="{{ $user->role }}"
                                    data-department_id="{{ $user->department_id ?? '' }}"
                                    data-course_id="{{ $user->course_id ?? '' }}"
                                    data-year_level="{{ $user->year_level ?? '' }}">
                                    Edit
                                </button>
                                <button type="button"
                                    class="delete-user-btn inline-flex items-center px-3 py-1.5 rounded-xl bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 text-xs font-bold transition"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-10 text-center text-gray-400 text-sm">
                            @if($search)
                                No users found matching "{{ $search }}".
                            @else
                                No users registered yet.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <form id="global-delete-form" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    {{-- Pagination --}}
    @if($this->users->hasPages())
        <div class="px-4 sm:px-6 py-4 bg-gray-50 border-t border-gray-100 overflow-x-auto">
            {{ $this->users->links() }}
        </div>
    @endif
</div>