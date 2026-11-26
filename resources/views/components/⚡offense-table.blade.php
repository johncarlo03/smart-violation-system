<?php

use App\Models\Offense;
use Livewire\Component;
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
            ->orderByRaw("
                CASE category
                    WHEN 'Academic' THEN 1
                    WHEN 'Non-Academic' THEN 2
                    WHEN 'Serious' THEN 3
                    WHEN 'Very Serious' THEN 4
                    ELSE 5
                END ASC
            ")
            ->get();
    }
};
?>

<div class="min-w-0">
    {{-- Search Bar --}}
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100 bg-gray-50/50">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by violation name or category..."
            class="w-full rounded-xl border-gray-200 text-base sm:text-sm font-medium text-gray-800 shadow-xs focus:border-[#1f2937] focus:ring-[#1f2937]">
    </div>

    {{-- Mobile Card List (Visible below md) --}}
    <div class="md:hidden divide-y divide-gray-100">
        @forelse($this->offenses as $offense)
            @php
                $badgeClasses = match ($offense->category) {
                    'Academic' => 'bg-gray-100 text-gray-800 border-gray-200',
                    'Serious', 'Very Serious' => 'bg-red-50 text-red-700 border-red-200',
                    default => 'bg-amber-50 text-amber-800 border-amber-200',
                };
            @endphp

            <div class="p-4 space-y-3 hover:bg-gray-50/70 transition-colors">
                <div class="flex items-start justify-between gap-2.5">
                    <div class="min-w-0">
                        <div class="text-[11px] font-mono text-gray-400">#{{ $loop->iteration }}</div>
                        <div class="font-bold text-sm text-gray-900 break-words mt-0.5">{{ $offense->name }}</div>
                    </div>

                    <span class="shrink-0 px-2.5 py-1 rounded-full text-[11px] font-semibold border {{ $badgeClasses }}">
                        {{ $offense->category }}
                    </span>
                </div>

                <div class="flex items-center gap-2 pt-0.5">
                    <button type="button" onclick="document.getElementById('edit-modal-{{ $offense->id }}').showModal()"
                        class="flex-1 inline-flex items-center justify-center px-3 py-2 text-xs font-bold text-white bg-[#1f2937] hover:bg-gray-800 rounded-xl transition">
                        Edit
                    </button>

                    <form action="{{ route('superadmin.offenses.destroy', $offense->id) }}" method="POST"
                        class="flex-1 delete-offense-form" data-name="{{ $offense->name }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-3 py-2 text-xs font-bold text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 rounded-xl transition">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="p-8 text-center text-gray-400 text-sm">
                No offenses or rules found. Add one to get started!
            </div>
        @endforelse
    </div>

    {{-- Desktop Table (Visible md and up) --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="p-4 text-xs font-black uppercase tracking-wider text-gray-500">No.</th>
                    <th class="p-4 text-xs font-black uppercase tracking-wider text-gray-500">Rule / Offense Name</th>
                    <th class="p-4 text-xs font-black uppercase tracking-wider text-gray-500">Category</th>
                    <th class="p-4 text-xs font-black uppercase tracking-wider text-gray-500 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                @forelse($this->offenses as $offense)
                    @php
                        $badgeClasses = match ($offense->category) {
                            'Academic' => 'bg-gray-100 text-gray-800 border-gray-200',
                            'Serious', 'Very Serious' => 'bg-red-50 text-red-700 border-red-200',
                            default => 'bg-amber-50 text-amber-800 border-amber-200',
                        };
                    @endphp

                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="p-4 font-mono text-gray-400">{{ $loop->iteration }}</td>
                        <td class="p-4 font-medium text-gray-900">{{ $offense->name }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badgeClasses }}">
                                {{ $offense->category }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="inline-flex items-center justify-center gap-2">
                                <button type="button" onclick="document.getElementById('edit-modal-{{ $offense->id }}').showModal()"
                                    class="px-3.5 py-1.5 text-xs font-bold text-white bg-[#1f2937] hover:bg-gray-800 rounded-xl transition">
                                    Edit
                                </button>

                                <form action="{{ route('superadmin.offenses.destroy', $offense->id) }}" method="POST"
                                    class="inline-block delete-offense-form" data-name="{{ $offense->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3.5 py-1.5 text-xs font-bold text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 rounded-xl transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-gray-400">
                            No offenses or rules found. Add one to get started!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Edit Rule Modals --}}
    @foreach($this->offenses as $offense)
        <dialog id="edit-modal-{{ $offense->id }}"
            class="backdrop:bg-gray-900/60 rounded-2xl p-0 w-[calc(100%-1.5rem)] max-w-md max-h-[90vh] overflow-y-auto shadow-2xl border border-gray-200/80 m-auto">
            <div class="h-1 w-full bg-gradient-to-r from-red-700 to-amber-500"></div>
            <div class="p-4 sm:p-6">
                <div class="flex justify-between items-center gap-3 mb-4">
                    <h3 class="text-base sm:text-lg font-black text-gray-900">Edit Rule</h3>
                    <button type="button" onclick="document.getElementById('edit-modal-{{ $offense->id }}').close()"
                        class="w-8 h-8 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 flex items-center justify-center text-xl leading-none transition shrink-0">&times;</button>
                </div>

                <form action="{{ route('superadmin.offenses.update', $offense->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-gray-600 mb-1.5">Offense Name</label>
                        <input type="text" name="name" value="{{ $offense->name }}" required
                            class="w-full border-gray-200 rounded-xl shadow-xs text-base sm:text-sm text-gray-800 focus:border-[#1f2937] focus:ring-[#1f2937]">
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase tracking-wider text-gray-600 mb-1.5">Category</label>
                        <select name="category" required
                            class="w-full border-gray-200 rounded-xl shadow-xs text-base sm:text-sm text-gray-800 focus:border-[#1f2937] focus:ring-[#1f2937]">
                            <option value="" disabled>Select a category</option>
                            <option value="Academic" @selected($offense->category === 'Academic')>Academic</option>
                            <option value="Non-Academic" @selected($offense->category === 'Non-Academic')>Non-Academic</option>
                            <option value="Serious" @selected($offense->category === 'Serious')>Serious</option>
                            <option value="Very Serious" @selected($offense->category === 'Very Serious')>Very Serious</option>
                        </select>
                    </div>

                    <div class="border-t border-gray-100 pt-3 mt-3">
                        <label class="block text-xs font-black uppercase tracking-wider text-red-700 mb-2.5">Configure Penalties</label>
                        <div class="space-y-3">
                            @foreach($offense->penalties as $index => $penalty)
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">
                                        @php
                                            $offensesCount = $index + 1;
                                            $suffix = match ($offensesCount) {
                                                1 => 'st',
                                                2 => 'nd',
                                                3 => 'rd',
                                                default => 'th',
                                            };
                                        @endphp
                                        {{ $penalty->level ?? $offensesCount . $suffix . ' Offense' }} Penalty
                                    </label>

                                    {{-- Array naming structure keeps input data tied directly to the correct penalty row ID --}}
                                    <textarea name="penalties[{{ $penalty->id }}][description]"
                                        required
                                        rows="2"
                                        class="w-full border-gray-200 rounded-xl shadow-xs focus:border-[#1f2937] focus:ring-[#1f2937] p-2.5 text-base sm:text-sm text-gray-800">{{ $penalty->penalty_description }}</textarea>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row justify-end gap-2 pt-3 border-t border-gray-100">
                        <button type="button" onclick="document.getElementById('edit-modal-{{ $offense->id }}').close()"
                            class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition">Cancel</button>
                        <button type="submit"
                            class="w-full sm:w-auto bg-[#1f2937] hover:bg-gray-800 text-white px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-xs transition">Save Changes</button>
                    </div>
                </form>
            </div>
        </dialog>
    @endforeach
</div>