<?php
use Livewire\Attributes\On;
use App\Models\Violation;
use App\Models\Department;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Illuminate\Database\Eloquent\Builder;

new class extends Component {

    public string $office;

    public function mount(): void
    {
        $this->office = match ((int) auth()->user()->role) {
            7 => 'dean',
            6 => 'chairman',
            3 => 'sao',
            default => abort(403),
        };
    }

    #[On('echo-private:office.{office},.ViolationUpdated')]
    public function refreshViolations($event = []): void
    {
        unset(
            $this->summaryStats,
            $this->totalViolations,
            $this->pendingReview,
            $this->majorIncidents,
            $this->topOffenses,
            $this->deptStats,
            $this->violations
        );

        // Send the notification details to the browser.
        $this->dispatch(
            'show-violation-toast',
            message: $event['message'] ?? 'A violation has been updated.',
            student: $event['student'] ?? 'Student',
            offense: $event['offense'] ?? 'Violation',
            action: $event['action'] ?? 'updated'
        );
    }

    private function getUserOffice(): string
    {
        return match ((int) auth()->user()->role) {
            7 => 'dean',
            6 => 'chairman',
            3 => 'sao',
            default => abort(403, 'Unauthorized access.'),
        };
    }

    private function getPendingStatus(): string
    {
        return match ($this->getUserOffice()) {
            'chairman' => 'pending_chairman',
            'dean' => 'pending_dean',
            'sao' => 'pending_sao',
        };
    }

    #[Computed]
    public function summaryStats()
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

        return $this->inPeriod()
            ->leftJoin('offenses', 'violations.offense_id', '=', 'offenses.id')
            ->selectRaw('
            COUNT(violations.id) AS total,
            COUNT(violations.id) FILTER (
                WHERE violations.status = ?
            ) AS pending,
            COUNT(violations.id) FILTER (
                WHERE offenses.category IN (?, ?)
                AND violations.status IN (?)
            ) AS major
        ', [
                $this->getPendingStatus(),
                'Serious',
                'Very Serious',
                $pendingStatus,
            ])
            ->first();
    }

    #[Computed]
    public function totalViolations()
    {
        return $this->summaryStats->total;
    }

    #[Computed]
    public function pendingReview()
    {
        return $this->summaryStats->pending;
    }

    #[Computed]
    public function majorIncidents()
    {
        return $this->summaryStats->major;
    }

    #[On('echo:violations,ViolationUpdated')]
    public function refreshViolations()
    {
        // The computed properties will be re-evaluated
    }

    #[Computed]
    public function topOffenses()
    {
        return $this->inPeriod()
            ->join('offenses', 'violations.offense_id', '=', 'offenses.id')
            ->selectRaw('
            violations.offense_id,
            offenses.name as offense_name,
            COUNT(violations.id) as total
        ')
            ->where('violations.status', '!=', 'void')
            ->groupBy('violations.offense_id', 'offenses.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function deptStats()
    {
        $office = $this->getUserOffice();
        $pendingStatus = $this->getPendingStatus();

        return Department::withCount([
            'violations as total' => function ($query) use ($office, $pendingStatus) {

                $query->where(function ($q) use ($office, $pendingStatus) {
                    $q->where('status', $pendingStatus)
                        ->orWhere(function ($q) use ($office) {
                            $q->whereIn('status', ['dismissed', 'resolved'])
                                ->where('resolved_by', $office);
                        });
                })
                    ->when(
                        $this->periodStart,
                        fn($q, $start) => $q->where(
                            'violations.created_at',
                            '>=',
                            $start
                        )
                    );
            },
        ])->get()->map(function ($d) {

            $colors = [
                1 => 'from-red-600 to-red-800',
                2 => 'from-gray-600 to-gray-800',
                3 => 'from-amber-400 to-amber-600',
                4 => 'from-red-400 to-amber-500',
            ];

            $d->barColor = $colors[$d->id] ?? 'from-gray-500 to-gray-700';
            $d->acronym = $d->acronym ?? 'N/A';

            return $d;
        });
    }

    #[Computed]
    public function violations()
    {
        return $this->inPeriod()
            ->with([
                'student',
                'cso',
                'department',
                'offense',
            ])
            ->latest()
            ->take(5)
            ->get();
    }

    #[Url(as: 'period')]
    public string $period = 'all';

    public function setPeriod(string $period): void
    {
        $this->period = in_array($period, ['day', 'week', 'month', 'year', 'all'], true)
            ? $period
            : 'month';
    }

    #[Computed]
    public function periodStart()
    {
        return match ($this->period) {
            'day' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'year' => now()->startOfYear(),
            'all' => null,
            default => now()->startOfMonth(),
        };
    }

    #[Computed]
    public function periodLabel(): string
    {
        return match ($this->period) {
            'day' => 'Today',
            'week' => 'This week',
            'year' => 'This year',
            'all' => 'All time',
            default => 'This month',
        };
    }

    private function inPeriod(): Builder
    {
        $office = $this->getUserOffice();
        $pendingStatus = $this->getPendingStatus();

        return Violation::query()
            ->where(function ($query) use ($office, $pendingStatus) {
                $query->where('violations.status', $pendingStatus)
                    ->orWhere(function ($query) use ($office) {
                        $query->whereIn('violations.status', ['dismissed', 'resolved'])
                            ->where('violations.resolved_by', $office);
                    });
            })
            ->when(
                $this->periodStart,
                fn($q, $start) => $q->where('violations.created_at', '>=', $start)
            );
    }

};
?>

<div class="space-y-6">
    {{-- Period Filter Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <p class="text-xs sm:text-sm text-gray-500">
            Showing <span class="font-bold text-gray-900">{{ $this->periodLabel }}</span>
        </p>

        <div class="flex sm:inline-flex overflow-x-auto bg-gray-50 border border-gray-200 rounded-xl p-1 [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden"
            role="group" aria-label="Time period">
            @foreach(['day' => 'Today', 'week' => 'Week', 'month' => 'Month', 'year' => 'Year', 'all' => 'All'] as $key => $label)
                <button type="button" wire:click="setPeriod('{{ $key }}')"
                    class="flex-1 sm:flex-initial px-3 sm:px-3.5 py-1.5 text-xs font-bold rounded-lg whitespace-nowrap transition
                        {{ $this->period === $key ? 'bg-[#1f2937] text-white shadow-xs' : 'text-gray-600 hover:bg-white hover:text-gray-900' }}">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Summary Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
        <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-xs">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Total Records</div>
                    <div class="text-3xl sm:text-4xl font-black text-gray-900 mt-1.5">{{ $this->totalViolations }}</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-[#1f2937]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-xs">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Pending Review</div>
                    <div class="text-3xl sm:text-4xl font-black text-amber-600 mt-1.5">{{ $this->pendingReview }}</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 border border-amber-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-xs">
            <div class="flex items-center justify-between gap-3">
                <div class="min-w-0">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-gray-400">Major Incidents</div>
                    <div class="text-3xl sm:text-4xl font-black text-red-700 mt-1.5">{{ $this->majorIncidents }}</div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-red-50 border border-red-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Analytics Row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-gray-200/80 shadow-xs">
            <h3 class="font-bold text-gray-900 mb-5 text-base sm:text-lg">Top 5 Common Violations</h3>
            <div class="space-y-4">
                @php $maxTotal = $this->topOffenses->first()->total ?? 0; @endphp
                @forelse($this->topOffenses as $item)
                    <div>
                        <div class="flex justify-between items-baseline gap-2 text-xs sm:text-sm mb-1.5">
                            <span class="font-semibold text-gray-700 break-words">{{ $item->offense_name ?? 'Unknown' }}</span>
                            <span class="font-black text-indigo-600 shrink-0">{{ $item->total }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-gradient-to-r from-indigo-500 to-indigo-700 h-2 rounded-full transition-all duration-500"
                                style="width: {{ $maxTotal > 0 ? ($item->total / $maxTotal) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-center text-gray-400 italic py-6">No violations recorded yet.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-gray-200/80 shadow-xs">
            <h3 class="font-bold text-gray-900 mb-5 text-base sm:text-lg text-center">Reports by Department</h3>
            <div class="flex items-end justify-around gap-2 h-48 sm:h-52 pt-4">
                @php $maxDeptTotal = $this->deptStats->max('total') ?? 0; @endphp
                @foreach($this->deptStats as $dept)
                    <div class="flex flex-col items-center flex-1 min-w-0">
                        <div class="bg-gradient-to-t {{ $dept->barColor }} w-10 sm:w-14 rounded-t-xl transition-all duration-300"
                            style="height: {{ $maxDeptTotal > 0 ? max(($dept->total / $maxDeptTotal) * 130, $dept->total > 0 ? 8 : 0) : 0 }}px">
                        </div>
                        <span class="text-[11px] mt-2.5 font-bold text-gray-700 text-center uppercase tracking-wide truncate w-full">
                            {{ $dept->acronym }}
                        </span>
                        <span class="text-xs text-gray-500 font-semibold">{{ $dept->total }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Recent Violation Queue --}}
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-xs overflow-hidden">
        <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100 flex items-center justify-between gap-3">
            <div class="min-w-0">
                <h3 class="text-base sm:text-lg font-bold text-gray-900">Recent Violation Queue</h3>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Review recently submitted student incidents.</p>
            </div>
            <span class="shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full bg-gray-100 text-gray-700">
                Latest {{ $this->violations->count() }}
            </span>
        </div>

        {{-- Mobile Card List (Visible below md) --}}
        <div class="md:hidden divide-y divide-gray-100">
            @forelse($this->violations as $v)
                @php
                    $isPending = str_starts_with($v->status, 'pending_');
                    $statusLabel = match ($v->status) {
                        'pending_chairman' => 'Pending Chairman',
                        'pending_dean' => 'Pending Dean',
                        'pending_sao' => 'Pending SAO',
                        'resolved' => 'Resolved',
                        'dismissed' => 'Dismissed',
                        default => ucfirst(str_replace('_', ' ', $v->status)),
                    };
                @endphp

                <div class="p-4 space-y-3 hover:bg-gray-50/70 transition-colors">
                    {{-- Top Row: Avatar, Student Info & Status --}}
                    <div class="flex items-start justify-between gap-2.5">
                        <div class="flex items-center gap-3 min-w-0">
                            @if($v->student?->profile_photo)
                                <img src="{{ asset('storage/' . $v->student->profile_photo) }}"
                                    alt="{{ $v->student->name }}"
                                    class="w-10 h-10 rounded-full object-cover border border-gray-200 shrink-0">
                            @else
                                <div class="w-10 h-10 rounded-full bg-[#1f2937] text-amber-400 flex items-center justify-center font-bold text-sm shrink-0">
                                    {{ strtoupper(substr($v->student->name ?? '?', 0, 1)) }}
                                </div>
                            @endif

                            <div class="min-w-0">
                                <div class="font-bold text-sm text-gray-900 truncate">{{ $v->student->name ?? 'Unknown Student' }}</div>
                                <div class="text-xs text-gray-500 mt-0.5 truncate">
                                    {{ $v->department->acronym ?? 'N/A' }} • {{ $v->student->id_number ?? 'No ID' }}
                                </div>
                            </div>
                        </div>

                        <span class="shrink-0 inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase
                            {{ $isPending
                                ? 'bg-amber-100 text-amber-800'
                                : ($v->status === 'resolved'
                                    ? 'bg-emerald-100 text-emerald-800'
                                    : 'bg-red-100 text-red-800') }}">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    {{-- Middle Box: Offense & Reporter --}}
                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 space-y-1.5">
                        <div class="flex flex-wrap items-center justify-between gap-1.5">
                            <span class="font-bold text-xs text-gray-800 break-words">{{ $v->offense->name ?? 'N/A' }}</span>
                            @if($v->offense?->category)
                                <span class="px-2 py-0.5 rounded-full bg-white border border-gray-200 text-gray-600 text-[10px] uppercase font-bold tracking-wider">
                                    {{ $v->offense->category }}
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-gray-500">
                            Reported by <span class="font-semibold text-gray-700">{{ $v->cso->name ?? 'Unknown' }}</span>
                        </p>
                    </div>

                    {{-- Bottom Row: Action Buttons --}}
                    <div class="flex items-center gap-2 pt-0.5">
                        @if($v->evidence_image)
                            <button type="button" onclick="openEvidenceModal('{{ $v->evidence_image }}')"
                                class="flex-1 inline-flex items-center justify-center px-3 py-2 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-bold hover:bg-amber-100 transition">
                                View Photo
                            </button>
                        @endif
                        <a href="{{ route('violations.manage', $v->uuid) }}"
                            class="flex-1 inline-flex items-center justify-center px-3 py-2 rounded-xl bg-[#1f2937] text-white text-xs font-bold hover:bg-gray-800 transition">
                            Manage
                        </a>
                    </div>
                </div>
            @empty
                <div class="px-4 py-14 text-center">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                            </svg>
                        </div>
                        <p class="font-bold text-sm text-gray-600">No violation records yet</p>
                        <p class="text-xs text-gray-400 mt-1">Reports will appear here once submitted.</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Desktop Table (Visible md and up) --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-gray-500 text-[11px] uppercase tracking-[0.15em]">
                        <th class="px-6 py-3.5 font-bold">Student</th>
                        <th class="px-6 py-3.5 font-bold">Offense</th>
                        <th class="px-6 py-3.5 font-bold">Reported By</th>
                        <th class="px-6 py-3.5 font-bold text-center">Status</th>
                        <th class="px-6 py-3.5 font-bold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($this->violations as $v)
                        @php
                            $isPending = str_starts_with($v->status, 'pending_');
                            $statusLabel = match ($v->status) {
                                'pending_chairman' => 'Pending Chairman',
                                'pending_dean' => 'Pending Dean',
                                'pending_sao' => 'Pending SAO',
                                'resolved' => 'Resolved',
                                'dismissed' => 'Dismissed',
                                default => ucfirst(str_replace('_', ' ', $v->status)),
                            };
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3.5">
                                    @if($v->student?->profile_photo)
                                        <img src="{{ asset('storage/' . $v->student->profile_photo) }}"
                                            alt="{{ $v->student->name }}"
                                            class="w-10 h-10 rounded-full object-cover border border-gray-200 shrink-0">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-[#1f2937] text-amber-400 flex items-center justify-center font-bold text-sm shrink-0">
                                            {{ strtoupper(substr($v->student->name ?? '?', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-sm text-gray-900">{{ $v->student->name ?? 'Unknown Student' }}</div>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            {{ $v->department->acronym ?? 'N/A' }} • {{ $v->student->id_number ?? 'No ID' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-sm text-gray-800">{{ $v->offense->name ?? 'N/A' }}</div>
                                @if($v->offense?->category)
                                    <div class="inline-flex mt-1.5 px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-600 text-[10px] uppercase font-bold tracking-wider">
                                        {{ $v->offense->category }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-medium">{{ $v->cso->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase
                                    {{ $isPending
                                        ? 'bg-amber-100 text-amber-800'
                                        : ($v->status === 'resolved'
                                            ? 'bg-emerald-100 text-emerald-800'
                                            : 'bg-red-100 text-red-800') }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">
                                    @if($v->evidence_image)
                                        <button type="button" onclick="openEvidenceModal('{{ $v->evidence_image }}')"
                                            class="inline-flex items-center px-3 py-2 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-xs font-bold hover:bg-amber-100 transition">
                                            View Photo
                                        </button>
                                    @endif
                                    <a href="{{ route('violations.manage', $v->uuid) }}"
                                        class="inline-flex items-center px-3.5 py-2 rounded-xl bg-[#1f2937] text-white text-xs font-bold hover:bg-gray-800 transition">
                                        Manage
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                                        </svg>
                                    </div>
                                    <p class="font-bold text-gray-600">No violation records yet</p>
                                    <p class="text-sm text-gray-400 mt-1">Reports will appear here once submitted.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
