<?php
use Livewire\Attributes\On;
use App\Models\Violation;
use App\Models\Department;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {

    #[Computed]
    public function totalViolations()
    {
        return Violation::count();
    }

    #[Computed]
    public function pendingReview()
    {
        return Violation::where('status', 'pending')->count();
    }

    #[Computed]
    public function majorIncidents()
    {
        return Violation::whereHas(
            'offense',
            fn($q) =>
                $q->whereIn('category', ['Serious', 'Very Serious'])
        )->where('status', '!=', 'void')->count();
    }

    #[On('echo:violations,ViolationUpdated')]
    public function refreshViolations()
    {
        // The computed properties will be re-evaluated
    }

    #[Computed]
    public function topOffenses()
    {
        return Violation::selectRaw('offense_id, count(*) as total')
            ->where('status', '!=', 'void')
            ->with('offense')
            ->groupBy('offense_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
    }

    #[Computed]
    public function deptStats()
    {
        return Department::withCount([
            'violations as total' => fn($q) =>
                $q->where('status', '!=', 'void')
        ])->get()->map(function ($d) {
            $colors = [
                1 => 'from-red-400 to-red-600',
                2 => 'from-blue-400 to-blue-600',
                3 => 'from-green-400 to-green-600',
                4 => 'from-yellow-400 to-yellow-500',
            ];
            $d->barColor = $colors[$d->id] ?? 'from-gray-400 to-gray-600';
            $d->acronym = $d->acronym ?? 'N/A';
            return $d;
        });
    }

    #[Computed]
    public function violations()
    {
        return Violation::with(['student', 'cso', 'department', 'offense'])
            ->latest()
            ->take(10)
            ->get();
    }
};
?>

<div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">Total Records</div>
                    <div class="text-4xl font-black text-gray-800 mt-2">{{ $this->totalViolations }}</div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">Pending Review</div>
                    <div class="text-4xl font-black text-orange-500 mt-2">{{ $this->pendingReview }}</div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-orange-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">Major Incidents</div>
                    <div class="text-4xl font-black text-red-600 mt-2">{{ $this->majorIncidents }}</div>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-red-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <h3 class="font-black text-gray-800 mb-6 text-lg">Top 5 Common Violations</h3>
            <div class="space-y-5">
                @php $maxTotal = $this->topOffenses->first()->total ?? 0; @endphp
                @forelse($this->topOffenses as $item)
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-semibold text-gray-700">{{ $item->offense->name ?? 'Unknown' }}</span>
                            <span class="font-black text-indigo-600">{{ $item->total }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                            <div class="bg-gradient-to-r from-indigo-500 to-indigo-700 h-2.5 rounded-full transition-all duration-700"
                                style="width: {{ $maxTotal > 0 ? ($item->total / $maxTotal) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-center text-gray-400 italic">No violations recorded yet.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
            <h3 class="font-black text-gray-800 mb-6 text-lg text-center">Reports by Department</h3>
            <div class="flex items-end justify-around h-56">
                @foreach($this->deptStats as $dept)
                    <div class="flex flex-col items-center">
                        <div class="relative group">
                            <div class="bg-gradient-to-t {{ $dept->barColor }} w-14 rounded-t-2xl shadow-md transition-all duration-300 group-hover:scale-105"
                                style="height: {{ $this->deptStats->max('total') > 0 ? ($dept->total / $this->deptStats->max('total')) * 140 : 0 }}px">
                            </div>
                        </div>
                        <span
                            class="text-[11px] mt-3 font-black text-gray-700 text-center uppercase tracking-wide">{{ $dept->acronym }}</span>
                        <span class="text-xs text-gray-400 font-semibold">{{ $dept->total }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Violation Queue Table --}}
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <h3 class="text-xl font-black text-gray-800">Recent Violation Queue</h3>
            <p class="text-sm text-gray-500 mt-1">Review recently submitted student incidents.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="bg-gradient-to-r from-gray-50 to-gray-100 border-b text-gray-500 text-[11px] uppercase tracking-[0.15em]">
                        <th class="px-6 py-4 font-black">Student</th>
                        <th class="px-6 py-4 font-black">Offense</th>
                        <th class="px-6 py-4 font-black">Reported By</th>
                        <th class="px-6 py-4 font-black text-center">Status</th>
                        <th class="px-6 py-4 font-black text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($this->violations as $v)
                        <tr class="hover:bg-indigo-50/40 transition duration-200">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    @if($v->student->profile_photo)
                                        <img src="{{ asset('storage/' . $v->student->profile_photo) }}"
                                            class="w-12 h-12 rounded-full object-cover shadow-sm">
                                    @else
                                        <div
                                            class="w-12 h-12 rounded-full {{ $v->department->badgeColor ?? 'bg-gray-100' }} flex items-center justify-center font-black text-sm shadow-sm">
                                            {{ strtoupper(substr($v->student->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-bold text-gray-900">{{ $v->student->name }}</div>
                                        <div class="text-xs text-gray-500 mt-1">{{ $v->department->acronym ?? 'N/A' }} •
                                            {{ $v->student->id_number }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="font-bold text-gray-800">{{ $v->offense->name ?? 'N/A' }}</div>
                                <div
                                    class="inline-flex mt-2 px-2 py-1 rounded-full bg-gray-100 text-gray-600 text-[10px] uppercase font-black tracking-wider">
                                    {{ $v->offense->category ?? '' }}
                                </div>
                            </td>
                            <td class="px-6 py-5 text-sm text-gray-600 font-medium">{{ $v->cso->name }}</td>
                            <td class="px-6 py-5 text-center">
                                <span
                                    class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black tracking-wider uppercase border
                                        {{ $v->status === 'pending' ? 'bg-orange-100 text-orange-700' : ($v->status === 'resolved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $v->status }}
                                </span>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    @if($v->evidence_image)
                                        <button onclick="openEvidenceModal('{{ $v->evidence_image }}')"
                                            class="inline-flex items-center px-3 py-2 rounded-xl bg-blue-100 text-blue-700 text-xs font-bold hover:bg-blue-200 transition">
                                            View Photo
                                        </button>
                                    @endif
                                    <a href="{{ route('violations.manage', $v->id) }}"
                                        class="inline-flex items-center px-3 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 shadow-sm transition">
                                        Manage
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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