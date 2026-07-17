<?php

use App\Models\Offense;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;

new class extends Component {

    #[Url]
    public $search = '';

    #[Computed]
    public function offenses()
    {
        return Offense::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('category', 'like', '%' . $this->search . '%');
        })
            ->orderByRaw("FIELD(category, 'Academic', 'Non-Academic', 'Serious', 'Very Serious') ASC")
            ->get();
    }
};
?>

<div>
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by violation name or category..."
            class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-gray-200 bg-gray-50">
                <th class="p-4 text-xs font-black uppercase tracking-wider text-gray-400">No.</th>
                <th class="p-4 text-xs font-black uppercase tracking-wider text-gray-400">Rule / Offense
                    Name</th>
                <th class="p-4 text-xs font-black uppercase tracking-wider text-gray-400">Category</th>
                <th class="p-4 text-xs font-black uppercase tracking-wider text-gray-400 text-center">
                    Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
            @forelse($this->offenses as $offense)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="p-4 font-mono text-gray-400">{{ $loop->iteration }}</td>
                    <td class="p-4 font-medium text-gray-900">{{ $offense->name }}</td>
                    <td class="p-4">
                        @if($offense->category == 'Academic')
                            <span
                                class="px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $offense->category }}
                            </span>
                        @elseif($offense->category == 'Serious' || $offense->category == 'Very Serious')
                            <span
                                class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-100">
                                {{ $offense->category }}
                            </span>
                        @else
                            <span
                                class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                {{ $offense->category }}
                            </span>
                        @endif
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <button onclick="document.getElementById('edit-modal-{{ $offense->id }}').showModal()"
                            class="edit-user-btn px-4 py-2 text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition">
                            Edit
                        </button>

                        <form action="{{ route('superadmin.offenses.destroy', $offense->id) }}" method="POST"
                            class="inline-block" onsubmit="return confirm('Are you sure you want to delete this rule?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="delete-user-btn px-4 py-2 text-xs font-bold text-red-600 bg-red-50 hover:bg-red-100 rounded-xl transition">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>

                <dialog id="edit-modal-{{ $offense->id }}"
                    class="backdrop:bg-black/50 rounded-xl p-6 w-full max-w-md shadow-2xl border border-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-900">Edit Rule</h3>
                        <button onclick="document.getElementById('edit-modal-{{ $offense->id }}').close()"
                            class="text-gray-400 hover:text-gray-600">&times;</button>
                    </div>
                    <form action="{{ route('superadmin.offenses.update', $offense->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="mb-2">
                            <label class="block text-xs font-semibold uppercase text-gray-600 mb-1">Offense Name</label>
                            <input type="text" name="name" value="{{ $offense->name }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase text-gray-600 mb-1">Category</label>
                            <select name="category" required
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="" disabled>Select a category</option>

                                <option value="Academic" @selected($offense->category === 'Academic')>Academic
                                </option>
                                <option value="Non-Academic" @selected($offense->category === 'Non-Academic')>
                                    Non-Academic</option>
                                <option value="Serious" @selected($offense->category === 'Serious')>Serious
                                </option>
                                <option value="Very Serious" @selected($offense->category === 'Very Serious')>
                                    Very Serious</option>
                            </select>
                        </div>
                        <div class="border-t pt-3 mt-3">
                            <label class="block text-xs font-bold uppercase text-blue-600 mb-2">Configure Penalties</label>
                            <div class="space-y-3">
                                @foreach($offense->penalties as $index => $penalty)
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 mb-1">
                                            @php
                                            $offenses = $index + 1;

                                            $suffix = match ($offenses) {
                                                1 => 'st',
                                                2 => 'nd',
                                                3 => 'rd',
                                                default => 'th',
                                            };
                                            @endphp
                                            {{ $penalty->level ?? $offenses . $suffix . ' Offense' }} Penalty
                                        </label>
                    
                    {{-- Array naming structure keeps input data tied directly to the correct penalty row ID --}}
                                        <textarea name="penalties[{{ $penalty->id }}][description]" 
                                                required 
                                                rows="2" 
                                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 text-sm">{{ $penalty->penalty_description }}</textarea>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex justify-end space-x-2 pt-2">
                            <button type="button" onclick="document.getElementById('edit-modal-{{ $offense->id }}').close()"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium">Cancel</button>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Save
                                Changes</button>
                        </div>
                    </form>
                </dialog>

            @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-gray-400">No offenses or rules found. Add
                        one to get started!</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>