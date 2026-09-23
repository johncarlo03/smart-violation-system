<?php

use App\Models\Violation;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\Computed;

new class extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public $statusFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    #[Computed]
    public function violations()
    {
        return Violation::with([
            'student',
            'department',
            'offense',
            'cso',
        ])
        ->where(function ($query) {
            $query->whereHas('student', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('id_number', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('rfid_number', 'like', '%' . $this->search . '%');
            })
            ->orWhere('status', 'like', '%' . $this->search . '%')
            ->orWhereHas('offense', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        })
        ->when($this->statusFilter !== '', function ($query) {
        $query->where('status', $this->statusFilter);
        })
        ->orderByRaw("FIELD(status, 'pending', 'resolved', 'dismissed')")
        ->paginate(10);
    }
};
?>

<div>
    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
    <div class="flex flex-col md:flex-row gap-3">

        <div class="flex-1">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Search by name, email, ID, or RFID..."
                class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            >
        </div>

        {{-- Status Filter --}}
        <div class="w-full md:w-56">
            <select
                wire:model.live="statusFilter"
                class="w-full rounded-xl border-gray-200 text-sm font-medium text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="resolved">Resolved</option>
                <option value="appealed">Appealed</option>
                <option value="dismissed">Dismissed</option>
            </select>
        </div>
    </div>
</div>

    {{-- Table --}}
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-left border-collapse">

            <thead>
                <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b text-gray-500 text-[11px] uppercase tracking-[0.15em]">
                    <th class="px-6 py-4 font-black"> Student </th>
                    <th class="px-6 py-4 font-black"> Offense </th>
                    <th class="px-6 py-4 font-black"> Reported By </th>
                    <th class="px-6 py-4 font-black text-center"> Status </th>
                    <th class="px-6 py-4 font-black text-right"> Action </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($this->violations as $v)
                    <tr class="hover:bg-indigo-50/40 transition duration-200">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                @if($v->student?->profile_photo)
                                    <img
                                        src="{{ asset('storage/' . $v->student->profile_photo) }}"
                                        class="w-12 h-12 rounded-full object-cover shadow-sm border border-gray-200"
                                    >
                                @else
                                    <div class="w-12 h-12 rounded-full {{ $v->department->badgeColor ?? 'bg-gray-100' }} flex items-center justify-center font-black text-lg shadow-sm">
                                        {{ strtoupper(substr($v->student?->name ?? 'N', 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-gray-900 leading-tight">
                                        {{ $v->student?->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 uppercase font-semibold">
                                        {{ $v->department?->acronym ?? 'N/A' }}
                                        •
                                        {{ $v->student?->id_number ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5">
                            <div class="font-bold text-gray-800 leading-snug">
                                {{ $v->offense?->name ?? 'N/A' }}
                            </div>
                            @if($v->offense?->category)
                                <div class="inline-flex mt-2 px-2 py-1 rounded-full bg-gray-100 text-gray-600 text-[10px] uppercase font-black tracking-wider">
                                    {{ $v->offense->category }}
                                </div>
                            @endif
                        </td>
                        
                        <td class="px-6 py-5 text-sm text-gray-600 font-medium">
                            {{ $v->cso?->name ?? 'N/A' }}
                        </td>

                        
                        <td class="px-6 py-5 text-center">
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black tracking-wider uppercase border
                                {{
                                    $v->status === 'pending'
                                        ? 'bg-orange-100 text-orange-700 border-orange-200'
                                        : ($v->status === 'resolved'
                                            ? 'bg-green-100 text-green-700 border-green-200'
                                            : 'bg-red-100 text-red-700 border-red-200')
                                }}" >
                                {{ $v->status }}
                            </span>

                        </td>

                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                @if($v->evidence_image)
                                    <button
                                        type="button"
                                        onclick="openEvidenceModal('{{ $v->evidence_image }}')"
                                        class="inline-flex items-center px-3 py-2 rounded-xl bg-blue-100 text-blue-700 text-xs font-bold hover:bg-blue-200 transition"
                                    >
                                        View Photo
                                    </button>
                                @endif
                                <a
                                    href="{{ route('violations.manage', $v->id) }}"
                                    class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow-sm hover:bg-indigo-700"
                                >
                                    Manage
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            No violations found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>