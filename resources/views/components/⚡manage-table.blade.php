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
        $user = auth()->user();
        
        $office = match ((int) $user->role) {
            7 => 'dean',
            6 => 'chairman',
            3 => 'sao',
            default => null,
        };

        $pendingStatus = match ($office) {
            'chairman' => 'pending_chairman',
            'dean' => 'pending_dean',
            'sao' => 'pending_sao',
            default => null,
        };

        $query = Violation::with([
            'student',
            'department',
            'offense',
            'cso',
        ])->withExists([
            'appeals as has_pending_appeal' => fn ($q) => $q->where('status', 'pending'),
        ]);

        // Reject users without reviewer roles.
        if (!$office) {
            return $query->whereRaw('1 = 0')->simplePaginate(10);
        }

        // Restrict Chairman and Dean to their assigned department.
if (in_array($office, ['chairman', 'dean'], true)) {
    if (!$user->department_id) {
        abort(403, 'Your account has no assigned department.');
    }

    $query->where('department_id', $user->department_id);
}

        // Current office's pending violations and
        // violations finalized by this office.
        $query->where(function ($q) use ($pendingStatus, $office) {
            $q->where('status', $pendingStatus)
                ->orWhere(function ($q) use ($office) {
                    $q->whereIn('status', ['resolved', 'dismissed'])
                        ->where('resolved_by', $office);
                });
        });

        // Search only when there is an actual search term.
        $query->when(filled($this->search), function ($query) {
            $search = '%' . trim($this->search) . '%';

            $query->where(function ($q) use ($search) {
                $q->whereHas('student', function ($studentQuery) use ($search) {
                    $studentQuery->where('name', 'ilike', $search)
                        ->orWhere('id_number', 'ilike', $search)
                        ->orWhere('email', 'ilike', $search)
                        ->orWhere('rfid_number', 'ilike', $search);
                })
                ->orWhere('status', 'ilike', $search)
                ->orWhereHas('offense', function ($offenseQuery) use ($search) {
                    $offenseQuery->where('name', 'ilike', $search);
                });
            });
        });

        // Status filter.
        $query->when($this->statusFilter !== '', function ($q) use ($pendingStatus) {
            if ($this->statusFilter === 'pending') {
                $q->where('status', $pendingStatus);
            } elseif ($this->statusFilter === 'appealed') {
                $q->whereHas('appeals', function ($appealQuery) {
                    $appealQuery->where('status', 'pending');
                });
            } else {
                $q->where('status', $this->statusFilter);
            }
        });

        return $query
            ->orderByDesc('has_pending_appeal')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(10);
    }
};
?>

<div class="min-w-0">
    {{-- Search & Status Filter --}}
    <div class="px-4 sm:px-6 py-4 border-b border-gray-100 bg-gray-50/50">
        <div class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 min-w-0">
                <input
                    type="text"
                    wire:model.live.debounce.600ms="search"
                    placeholder="Search by name, email, ID, or RFID..."
                    class="w-full rounded-xl border-gray-200 text-base sm:text-sm font-medium text-gray-800 shadow-xs focus:border-[#1f2937] focus:ring-[#1f2937]"
                >
            </div>

            <div class="w-full md:w-56 shrink-0">
                <select
                    wire:model.live="statusFilter"
                    class="w-full rounded-xl border-gray-200 text-base sm:text-sm font-medium text-gray-800 shadow-xs focus:border-[#1f2937] focus:ring-[#1f2937]">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="resolved">Resolved</option>
                    <option value="appealed">Appealed</option>
                    <option value="dismissed">Dismissed</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Mobile Card List (Visible below md) --}}
    <div wire:loading.class="opacity-50 pointer-events-none"
        class="md:hidden divide-y divide-gray-100 transition-opacity">
        @forelse($this->violations as $v)
            @php
                $studentName = trim($v->student?->name ?? 'N');
                $nameParts = preg_split('/\s+/', $studentName);
                $initials = strtoupper(
                    substr($nameParts[0] ?? 'N', 0, 1) .
                    (count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : '')
                );
            @endphp

            <div class="p-4 space-y-3 hover:bg-gray-50/70 transition-colors">
                {{-- Top Row: Avatar, Student Info & Status --}}
                <div class="flex items-start justify-between gap-2.5">
                    <div class="flex items-center gap-3 min-w-0">
                        @if($v->student?->profile_photo)
                            <img
                                src="{{ asset('storage/' . $v->student->profile_photo) }}"
                                alt="{{ $v->student?->name ?? 'Student' }}"
                                class="w-11 h-11 rounded-full object-cover shadow-xs border border-amber-500 shrink-0"
                            >
                        @else
                            <div class="w-11 h-11 rounded-full bg-[#1f2937] text-amber-400 border border-amber-500 flex items-center justify-center font-black text-xs shadow-xs shrink-0">
                                {{ $initials }}
                            </div>
                        @endif

                        <div class="min-w-0">
                            <div class="font-bold text-sm text-gray-900 truncate">
                                {{ $v->student?->name ?? 'N/A' }}
                            </div>
                            <div class="text-xs text-gray-500 mt-0.5 uppercase font-semibold truncate">
                                {{ $v->department?->acronym ?? 'N/A' }}
                                &bull;
                                {{ $v->student?->id_number ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="shrink-0">
                        @if($v->has_pending_appeal)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                                </svg>
                                Appealed
                            </span>
                        @elseif(in_array($v->status, [
                            'pending_chairman',
                            'pending_dean',
                            'pending_sao',
                        ]))
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200">
                                Pending
                            </span>
                        @elseif($v->status === 'resolved')
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 border border-emerald-200">
                                Resolved
                            </span>
                        @elseif($v->status === 'dismissed')
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-100 text-red-700 border border-red-200">
                                Dismissed
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Middle Box: Offense & Reporter --}}
                <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 space-y-1.5">
                    <div class="flex flex-wrap items-center justify-between gap-1.5">
                        <span class="font-bold text-xs text-gray-800 break-words">
                            {{ $v->offense?->name ?? 'N/A' }}
                        </span>
                        @if($v->offense?->category)
                            <span class="px-2 py-0.5 rounded-full bg-white border border-gray-200 text-gray-600 text-[10px] uppercase font-bold tracking-wider">
                                {{ $v->offense->category }}
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500">
                        Reported by <span class="font-semibold text-gray-700">{{ $v->cso?->name ?? 'N/A' }}</span>
                    </p>
                </div>

                {{-- Bottom Row: Action Buttons --}}
                <div class="flex items-center gap-2 pt-0.5">
                    @if($v->evidence_image)
                        <button
                            type="button"
                            onclick="openEvidenceModal('{{ $v->evidence_image }}')"
                            class="flex-1 inline-flex items-center justify-center px-3 py-2 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-bold hover:bg-amber-100 transition"
                        >
                            View Photo
                        </button>
                    @endif

                    @if(auth()->user()->role == 2)
                        <span
                            class="flex-1 inline-flex items-center justify-center px-3 py-2
                                bg-[#1f2937] text-white text-xs font-bold
                                rounded-xl shadow-xs opacity-40
                                cursor-not-allowed"
                        >
                            Manage
                        </span>
                    @else
                        <a
                            href="{{ route('violations.manage', ['violation' => $v->uuid]) }}"
                            class="flex-1 inline-flex items-center justify-center px-3 py-2
                                bg-[#1f2937] text-white text-xs font-bold
                                rounded-xl shadow-xs hover:bg-gray-800 transition"
                        >
                            Manage
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="px-4 py-10 text-center text-sm text-gray-500">
                No violations found.
            </div>
        @endforelse

        @if($this->violations->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 overflow-x-auto">
                {{ $this->violations->links() }}
            </div>
        @endif
    </div>

    {{-- Desktop Table (Visible md and up) --}}
    <div wire:loading.class="opacity-50 pointer-events-none"
        class="hidden md:block overflow-x-auto transition-opacity">
        <table class="w-full text-left border-collapse">

            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-[11px] uppercase tracking-[0.15em]">
                    <th class="px-6 py-4 font-black">Student</th>
                    <th class="px-6 py-4 font-black">Offense</th>
                    <th class="px-6 py-4 font-black">Reported By</th>
                    <th class="px-6 py-4 font-black text-center">Status</th>
                    <th class="px-6 py-4 font-black text-right">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                @forelse($this->violations as $v)
                    @php
                        $studentName = trim($v->student?->name ?? 'N');
                        $nameParts = preg_split('/\s+/', $studentName);
                        $initials = strtoupper(
                            substr($nameParts[0] ?? 'N', 0, 1) .
                            (count($nameParts) > 1 ? substr(end($nameParts), 0, 1) : '')
                        );
                    @endphp

                    <tr class="hover:bg-gray-50/70 transition duration-200">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                @if($v->student?->profile_photo)
                                    <img
                                        src="{{ asset('storage/' . $v->student->profile_photo) }}"
                                        alt="{{ $v->student?->name ?? 'Student' }}"
                                        class="w-12 h-12 rounded-full object-cover shadow-xs border border-amber-500 shrink-0"
                                    >
                                @else
                                    <div class="w-12 h-12 rounded-full bg-[#1f2937] text-amber-400 border border-amber-500 flex items-center justify-center font-black text-xs shadow-xs shrink-0">
                                        {{ $initials }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-gray-900 leading-tight">
                                        {{ $v->student?->name ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 uppercase font-semibold">
                                        {{ $v->department?->acronym ?? 'N/A' }}
                                        &bull;
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
                            @if($v->has_pending_appeal)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full
                                            text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z"/>
                                    </svg>
                                    Appealed
                                </span>
                            @elseif(in_array($v->status, [
                                'pending_chairman',
                                'pending_dean',
                                'pending_sao',
                            ]))
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                    Pending
                                </span>
                            @elseif($v->status === 'resolved')
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                            bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Resolved
                                </span>
                            @elseif($v->status === 'dismissed')
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                            bg-red-100 text-red-700 border border-red-200">
                                    Dismissed
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                @if($v->evidence_image)
                                    <button
                                        type="button"
                                        onclick="openEvidenceModal('{{ $v->evidence_image }}')"
                                        class="inline-flex items-center px-3 py-2 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-bold hover:bg-amber-100 transition"
                                    >
                                        View Photo
                                    </button>
                                @endif
                                @if(auth()->user()->role == 2)
                                    <span
                                        class="inline-flex items-center px-4 py-2
                                            bg-[#1f2937] text-white text-xs font-bold
                                            rounded-xl shadow-xs opacity-40
                                            cursor-not-allowed"
                                    >
                                        Manage
                                    </span>
                                @else
                                    <a
                                        href="{{ route('violations.manage', ['violation' => $v->uuid]) }}"
                                        class="inline-flex items-center px-4 py-2
                                            bg-[#1f2937] text-white text-xs font-bold
                                            rounded-xl shadow-xs hover:bg-gray-800 transition"
                                    >
                                        Manage
                                    </a>
                                @endif
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
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $this->violations->links() }}
        </div>
    </div>

</div>