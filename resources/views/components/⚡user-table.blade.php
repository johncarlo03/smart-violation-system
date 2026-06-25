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
        return User::where(function($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('id_number', 'like', '%' . $this->search . '%')
                  ->orWhere('rfid_number', 'like', '%' . $this->search . '%');
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);
    }
};
?>

<div>
    {{-- Search Bar --}}
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search by name, email, ID, or RFID..."
            class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50/70">
                    <th class="p-4 text-xs font-black uppercase tracking-wider text-gray-400">Name / Email</th>
                    <th class="p-4 text-xs font-black uppercase tracking-wider text-gray-400">ID / RFID</th>
                    <th class="p-4 text-xs font-black uppercase tracking-wider text-gray-400">Role</th>
                    <th class="p-4 text-xs font-black uppercase tracking-wider text-gray-400 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($this->users as $user)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="p-4">
                            <div class="font-bold text-gray-800 text-sm">{{ $user->name }}</div>
                            <div class="text-xs text-gray-400">{{ $user->email }}</div>
                        </td>
                        <td class="p-4 text-sm font-medium text-gray-600">
                            <div>ID: {{ $user->id_number }}</div>
                            <div class="text-xs text-gray-400">RFID: {{ $user->rfid_number ?? 'N/A' }}</div>
                        </td>
                        <td class="p-4">
                            @if($user->role == 1)
                                <span class="text-xs font-bold px-2 py-1 rounded-lg bg-blue-50 text-blue-600">Student</span>
                            @elseif($user->role == 2)
                                <span class="text-xs font-bold px-2 py-1 rounded-lg bg-orange-50 text-orange-600">CSO</span>
                            @elseif($user->role == 3)
                                <span class="text-xs font-bold px-2 py-1 rounded-lg bg-green-50 text-green-600">SAO</span>
                            @else
                                <span class="text-xs font-bold px-2 py-1 rounded-lg bg-indigo-50 text-indigo-600">Super Admin</span>
                            @endif
                        </td>
                        <td class="p-4 text-center">
                            <button type="button"
                                class="edit-user-btn px-4 py-2 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition"
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
                                class="delete-user-btn px-4 py-2 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition"
                                data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}">
                                Delete
                            </button>
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

        <form id="global-delete-form" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>

    {{-- Pagination --}}
    @if($this->users->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $this->users->links() }}
        </div>
    @endif
</div>