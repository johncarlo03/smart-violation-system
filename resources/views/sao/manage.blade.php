<x-app-layout>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 min-w-0">
            <div class="min-w-0">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 truncate">
                    Manage Violation
                </h2>

                <p class="text-xs sm:text-sm text-gray-500 mt-1">
                    Review the violation and take appropriate action.
                </p>
            </div>

            <a href="{{ route('sao.violations.index') }}"
                class="w-full sm:w-auto text-center px-4 py-2.5 sm:py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 shrink-0">
                Back
            </a>
        </div>
    </x-slot>

    @php
        $appealPending = $latestAppeal && $latestAppeal->status === 'pending';
        $appeal = in_array($violation->status, ['pending_chairman', 'pending_dean', 'pending_sao'])
            ? $violation->appeals->sortByDesc('created_at')->first()
            : null;
        $formattedStatus = ucwords(str_replace('_', ' ', $violation->status));
    @endphp

    @if (session('success'))
        <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8 mt-4 sm:mt-6">
            <div class="p-3.5 sm:p-4 bg-green-50 border border-green-200 rounded-xl text-xs sm:text-sm font-semibold text-green-800 break-words">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8 mt-4 sm:mt-6">
            <div class="p-3.5 sm:p-4 bg-red-50 border border-red-200 rounded-xl text-xs sm:text-sm text-red-700 space-y-1 break-words">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <div class="py-5 sm:py-8 overflow-x-clip">
        <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8 min-w-0">

            {{-- STATUS --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6 min-w-0">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 min-w-0">
                    <div class="min-w-0">
                        <p class="text-xs font-black uppercase tracking-widest text-gray-400">
                            Violation Status
                        </p>

                        <p class="text-xl sm:text-2xl font-black text-gray-800 mt-1 break-words">
                            {{ $formattedStatus }}
                        </p>
                    </div>

                    <span class="self-start sm:self-auto px-3.5 py-1.5 sm:px-4 sm:py-2 rounded-full text-xs font-black uppercase shrink-0
                        {{ $violation->status === 'pending_sao' ? 'bg-orange-100 text-orange-700' : ($violation->status === 'resolved'
                            ? 'bg-green-100 text-green-700'
                            : 'bg-red-100 text-red-700') }}">
                        {{ $formattedStatus }}
                    </span>
                </div>
            </div>

            {{-- STUDENT INFORMATION --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6 min-w-0">
                <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-4 sm:mb-5">
                    Student Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase text-gray-400">
                            Student Name
                        </p>

                        <p class="font-semibold text-gray-800 mt-1 break-words">
                            {{ $violation->student->name }}
                        </p>
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase text-gray-400">
                            Student ID
                        </p>

                        <p class="font-semibold text-gray-800 mt-1 break-words">
                            {{ $violation->student->id_number }}
                        </p>
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase text-gray-400">
                            Department
                        </p>

                        <p class="font-semibold text-gray-800 mt-1 break-words">
                            {{ $violation->department->name ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- VIOLATION INFORMATION --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6 min-w-0">
                <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-4 sm:mb-5">
                    Violation Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase text-gray-400">
                            Offense
                        </p>

                        <p class="font-semibold text-gray-800 mt-1 break-words">
                            {{ $violation->offense->name ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase text-gray-400">
                            Category
                        </p>

                        <p class="font-semibold text-gray-800 mt-1 break-words">
                            {{ $violation->offense->category ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase text-gray-400">Date Reported</p>
                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $violation->created_at->format('F d, Y h:i A') }}
                        </p>
                    </div>

                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase text-gray-400">Reported By</p>
                        <p class="font-semibold text-gray-800 mt-1 break-words">
                            {{ $violation->cso->name ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="md:col-span-2 min-w-0">
                        <p class="text-xs font-bold uppercase text-gray-400 mb-2">
                            Reporter's Description
                        </p>

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-3.5 sm:p-4">
                            <p class="text-sm text-gray-700 break-words">
                                {{ $violation->description ?: 'No description provided.' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- EVIDENCE --}}
            @if($violation->evidence_image)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6 min-w-0">
                    <div class="flex items-center justify-between gap-2 mb-3 sm:mb-4">
                        <h3 class="text-base sm:text-lg font-bold text-gray-800">
                            Evidence
                        </h3>

                        <a href="{{ asset('storage/' . $violation->evidence_image) }}" target="_blank" rel="noopener"
                            class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 shrink-0">
                            Open full size
                        </a>
                    </div>

                    <a href="{{ asset('storage/' . $violation->evidence_image) }}" target="_blank" rel="noopener" class="block">
                        <img src="{{ asset('storage/' . $violation->evidence_image) }}"
                            class="max-w-xl w-full max-h-96 rounded-xl border border-gray-200 shadow-sm object-contain bg-gray-50"
                            alt="Violation evidence">
                    </a>
                </div>
            @endif

            {{-- STUDENT APPEAL --}}
            @if($appeal)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 mb-4 sm:mb-5 min-w-0">
                        <div class="min-w-0">
                            <h3 class="text-base sm:text-lg font-bold text-gray-800">
                                Student Appeal
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">
                                Review the student's appeal before taking action on this violation.
                            </p>
                        </div>

                        <span
                            class="self-start sm:self-auto px-3 py-1.5 rounded-full text-xs font-black uppercase shrink-0
                                {{ $appeal->status === 'pending' ? 'bg-orange-100 text-orange-700' : ($appeal->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                            {{ ucwords(str_replace('_', ' ', $appeal->status)) }}
                        </span>
                    </div>

                    {{-- Appeal Information --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 sm:p-5 min-w-0">
                        <div class="mb-4 sm:mb-5 min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                                Submitted By
                            </p>

                            <p class="font-semibold text-gray-800 mt-1 break-words">
                                {{ $appeal->student->name ?? $violation->student->name }}
                            </p>
                        </div>

                        <div class="mb-4 sm:mb-5 min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                                Date Submitted
                            </p>

                            <p class="text-sm text-gray-700 mt-1">
                                {{ $appeal->created_at->format('F d, Y h:i A') }}
                            </p>
                        </div>

                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">
                                Reason for Appeal
                            </p>

                            <div class="bg-white border border-gray-200 rounded-xl p-3.5 sm:p-4">
                                <p class="text-sm text-gray-700 whitespace-pre-line break-words">
                                    {{ $appeal->reason }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Appeal Evidence --}}
                    @if($appeal->evidence_image)
                        <div class="mt-4 sm:mt-5 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-2">
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                                    Supporting Evidence
                                </p>

                                <a href="{{ asset('storage/' . $appeal->evidence_image) }}" target="_blank" rel="noopener"
                                    class="text-xs font-semibold text-indigo-600 hover:text-indigo-700 shrink-0">
                                    Open full size
                                </a>
                            </div>

                            <a href="{{ asset('storage/' . $appeal->evidence_image) }}" target="_blank" rel="noopener" class="block">
                                <img src="{{ asset('storage/' . $appeal->evidence_image) }}" alt="Appeal supporting evidence"
                                    class="max-w-xl w-full max-h-96 rounded-xl border border-gray-200 shadow-sm object-contain bg-gray-50">
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            {{-- PENDING VIOLATION --}}
            @if(
                    in_array($violation->status, [
                        'pending_chairman',
                        'pending_dean',
                        'pending_sao',
                    ])
                )
                {{-- RECOMMENDED PENALTY --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 mb-4 sm:mb-6 min-w-0">
                    <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-1.5 sm:mb-2">
                        Recommended Penalty
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-500 mb-4 sm:mb-5">
                        The penalty is automatically determined based on the student's
                        previous resolved violations of this offense.
                    </p>
                    <div class="p-3.5 sm:p-4 bg-indigo-50 border border-indigo-100 rounded-xl min-w-0">
                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-500">
                            Offense Level
                        </p>
                        <p class="text-lg sm:text-xl font-black text-indigo-900 mt-1">
                            {{ \Illuminate\Support\Number::ordinal($offenseNumber) }} Offense
                        </p>

                        @if($recommendedPenalty)
                            <div class="mt-3 sm:mt-4 min-w-0">
                                <p class="text-xs font-bold uppercase text-gray-500">
                                    Penalty
                                </p>
                                <p class="text-sm text-gray-600 mt-1 break-words">
                                    {{ $recommendedPenalty->penalty_description }}
                                </p>
                            </div>
                        @else
                            <div class="mt-3 sm:mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm font-semibold text-red-600">
                                    No penalty has been configured for this offense level.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ACTION SELECTION --}}
                <div x-data="{
                        action: '',
                        selectAction(nextAction, inputId) {
                            this.action = nextAction;
                            this.$nextTick(() => {
                                const el = document.getElementById(inputId);
                                if (el) {
                                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                    setTimeout(() => el.focus({ preventScroll: true }), 250);
                                }
                            });
                        }
                    }"
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 min-w-0">
                    <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-1.5 sm:mb-2">
                        Take Action
                    </h3>

                    <p class="text-xs sm:text-sm text-gray-500 mb-4 sm:mb-5">
                        Choose how you want to process this violation.
                    </p>

                    {{-- ACTION CHOICES --}}
                    @php
                        $isPendingAppeal = $latestAppeal && $latestAppeal->status === 'pending';
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">

                        {{-- RESOLVE --}}
                        <button type="button" @click="selectAction('resolve', 'resolution_notes')"
                            :aria-pressed="action === 'resolve'"
                            :class="action === 'resolve'
                                ? '{{ $isPendingAppeal ? 'border-red-500 bg-red-50 ring-2 ring-red-100'
                                : 'border-green-500 bg-green-50 ring-2 ring-green-100' }}' : 'border-gray-200 hover:bg-gray-50'"
                            class="p-4 sm:p-5 border rounded-xl text-left transition min-w-0 {{ $isPendingAppeal ? 'order-2' : 'order-1' }}">
                            <div class="flex items-start gap-3 min-w-0">
                                <div
                                    class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl {{ $isPendingAppeal ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }} flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="{{ $isPendingAppeal
                                                ? 'M6 18L18 6M6 6l12 12'
                                                : 'M5 13l4 4L19 7' }}" />
                                    </svg>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="font-bold text-gray-800">
                                            {{ $isPendingAppeal ? 'Reject Appeal' : 'Resolve Violation' }}
                                        </p>
                                        <span x-show="action === 'resolve'" x-cloak
                                            class="text-[11px] font-bold uppercase px-2 py-0.5 rounded-full shrink-0 {{ $isPendingAppeal ? 'bg-red-200 text-red-800' : 'bg-green-200 text-green-800' }}">
                                            Selected
                                        </span>
                                    </div>

                                    <p class="text-xs text-gray-500 mt-1 break-words">
                                        {{ $isPendingAppeal ? 'The appeal is not valid. Apply the penalty and resolve the violation.'
                                            : 'Apply the recommended penalty and resolve the violation.' }}
                                    </p>
                                </div>
                            </div>
                        </button>

                        {{-- DISMISS --}}
                        <button type="button" @click="selectAction('dismiss', 'dismissal_reason')"
                            :aria-pressed="action === 'dismiss'"
                            :class="action === 'dismiss'
                                ? '{{ $isPendingAppeal ? 'border-green-500 bg-green-50 ring-2 ring-green-100' : 'border-red-500 bg-red-50 ring-2 ring-red-100' }}'
                                : 'border-gray-200 hover:bg-gray-50'"
                            class="p-4 sm:p-5 border rounded-xl text-left transition min-w-0 {{ $isPendingAppeal ? 'order-1' : 'order-2' }}">
                            <div class="flex items-start gap-3 min-w-0">
                                <div
                                    class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl {{ $isPendingAppeal ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }} flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="{{ $isPendingAppeal
                                                ? 'M5 13l4 4L19 7'
                                                : 'M6 18L18 6M6 6l12 12' }}" />
                                    </svg>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <p class="font-bold text-gray-800">
                                            {{ $isPendingAppeal ? 'Accept Appeal' : 'Dismiss Violation' }}
                                        </p>
                                        <span x-show="action === 'dismiss'" x-cloak
                                            class="text-[11px] font-bold uppercase px-2 py-0.5 rounded-full shrink-0 {{ $isPendingAppeal ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                            Selected
                                        </span>
                                    </div>

                                    <p class="text-xs text-gray-500 mt-1 break-words">
                                        {{ $isPendingAppeal ? 'The appeal is valid. Dismiss the violation.' : 'Dismiss this violation because it is invalid.' }}
                                    </p>
                                </div>
                            </div>
                        </button>
                    </div>

                    {{-- FORWARD VIOLATION --}}
                    @if(
                        (auth()->user()->role == 7 && $violation->status === 'pending_dean') ||
                        (auth()->user()->role == 6 && $violation->status === 'pending_chairman')
                    )
                        <div class="mt-5 sm:mt-6 pt-4 border-t border-gray-100">
                            <form action="{{ route('violations.forward', $violation) }}"
                                  method="POST"
                                  class="js-confirm-form"
                                  data-title="Forward this violation?"
                                  data-text="This violation will be forwarded to the next reviewing office."
                                  data-confirm="Yes, forward"
                                  data-color="#4f46e5">

                                @csrf
                                @method('PUT')

                                <button type="submit"
                                    class="w-full px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition">

                                    @if(auth()->user()->role == 6)
                                        Forward to Dean
                                    @else
                                        Forward to SAO
                                    @endif

                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- ==================== RESOLVE FORM ==================== --}}
                    <div x-show="action === 'resolve'" x-transition x-cloak class="mt-5 sm:mt-6 pt-5 border-t border-gray-100 min-w-0">
                        @if($recommendedPenalty)
                            <div class="p-3.5 sm:p-4 mb-4 sm:mb-5 bg-indigo-50 border border-indigo-100 rounded-xl min-w-0">
                                <p class="text-xs font-bold uppercase tracking-wider text-indigo-500">
                                    Recommended Penalty
                                </p>

                                <p class="text-sm font-semibold text-indigo-900 mt-1 break-words">
                                    {{ $recommendedPenalty->penalty_description }}
                                </p>
                            </div>

                            <form action="{{ route('violations.resolve', $violation) }}" method="POST" class="js-confirm-form"
                                data-title="{{ $appealPending ? 'Reject this appeal?' : 'Resolve this violation?' }}"
                                data-text="{{ $appealPending
                                    ? 'The appeal will be denied and the recommended penalty will be applied to ' . $violation->student->name . '.'
                                    : 'The recommended penalty will be applied to ' . $violation->student->name . '.' }}"
                                data-confirm="{{ $appealPending ? 'Yes, reject appeal' : 'Yes, resolve' }}"
                                data-color="#15803d">
                                @csrf
                                @method('PUT')

                                <div class="mb-4 sm:mb-5">
                                    <label for="resolution_notes" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Resolution Notes
                                    </label>

                                    <textarea id="resolution_notes" name="resolution_notes" rows="4" required
                                        class="w-full rounded-xl border-gray-300 text-base sm:text-sm focus:border-green-500 focus:ring-green-500"
                                        placeholder="Enter notes regarding the action taken...">{{ old('resolution_notes') }}</textarea>
                                </div>

                                <x-primary-button type="submit" class="w-full sm:w-auto justify-center py-3 sm:py-2">
                                    @if($latestAppeal && $latestAppeal->status === 'pending')
                                        Reject Appeal & Resolve
                                    @else
                                        Apply Penalty & Resolve
                                    @endif
                                </x-primary-button>
                            </form>
                        @else
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-xl">
                                <p class="text-sm font-semibold text-yellow-800">
                                    This violation cannot be resolved because no penalty
                                    has been configured for the current offense level.
                                </p>
                            </div>
                        @endif
                    </div>

                    {{-- ==================== DISMISS FORM ==================== --}}
                    <div x-show="action === 'dismiss'" x-transition x-cloak class="mt-5 sm:mt-6 pt-5 border-t border-gray-100 min-w-0">
                        <form action="{{ route('violations.dismiss', $violation) }}" method="POST" class="js-confirm-form"
                            data-title="{{ $appealPending ? 'Accept this appeal?' : 'Dismiss this violation?' }}"
                            data-text="{{ $appealPending
                                ? 'The appeal will be approved and the violation dismissed. No penalty will apply.'
                                : 'The violation will be dismissed and no penalty will apply.' }}"
                            data-confirm="{{ $appealPending ? 'Yes, accept appeal' : 'Yes, dismiss' }}"
                            data-color="#dc2626">
                            @csrf
                            @method('PUT')

                            <div class="mb-4 sm:mb-5">
                                <label for="dismissal_reason" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Dismissal Reason
                                </label>

                                <textarea id="dismissal_reason" name="dismissal_reason" rows="4" required
                                    class="w-full rounded-xl border-gray-300 text-base sm:text-sm focus:border-red-500 focus:ring-red-500"
                                    placeholder="Explain why this violation is being dismissed...">{{ old('dismissal_reason') }}</textarea>
                            </div>

                            <x-danger-button type="submit" class="w-full sm:w-auto justify-center py-3 sm:py-2">
                                @if($appealPending)
                                    Accept Appeal & Dismiss
                                @else
                                    Dismiss Violation
                                @endif
                            </x-danger-button>
                        </form>
                    </div>
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 sm:p-6 space-y-4 sm:space-y-5 min-w-0">
                    <h3 class="text-base sm:text-lg font-bold text-gray-800">Processing Information</h3>

                    @if($violation->status === 'resolved')
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase text-gray-400">Applied Penalty</p>
                            @if($violation->penalty)
                                <p class="font-bold text-gray-800 mt-1">
                                    {{ \Illuminate\Support\Number::ordinal($violation->penalty->level) }} Offense
                                </p>
                                <p class="text-sm text-gray-600 mt-1 break-words">{{ $violation->penalty->penalty_description }}</p>
                            @else
                                <p class="text-gray-500 mt-1">No penalty recorded.</p>
                            @endif
                        </div>
                    @endif

                    @if($violation->resolution_notes)
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase text-gray-400">
                                {{ $violation->status === 'dismissed' ? 'Dismissal Reason' : 'Resolution Notes' }}
                            </p>
                            <p class="text-sm text-gray-600 mt-1 whitespace-pre-line break-words">{{ $violation->resolution_notes }}</p>
                        </div>
                    @endif

                    @if($violation->resolved_at)
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase text-gray-400">Date Processed</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $violation->resolved_at->format('F d, Y h:i A') }}</p>
                        </div>
                    @endif

                    @if($latestAppeal)
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase text-gray-400">Student Appeal</p>
                            <p class="text-sm text-gray-700 mt-1 whitespace-pre-line break-words">{{ $latestAppeal->reason }}</p>
                            <p class="text-xs text-gray-500 mt-2 break-words">
                                Status: <span class="font-semibold">{{ ucwords(str_replace('_', ' ', $latestAppeal->status)) }}</span>
                                @if($latestAppeal->sao_response)
                                    <br>SAO response: {{ $latestAppeal->sao_response }}
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <script>
        document.querySelectorAll('.js-confirm-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: form.dataset.title,
                    text: form.dataset.text,
                    icon: 'question',
                    width: 'min(92vw, 28rem)',
                    showCancelButton: true,
                    confirmButtonText: form.dataset.confirm,
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: form.dataset.color,
                    cancelButtonColor: '#6b7280',
                    reverseButtons: true,
                    customClass: { popup: 'rounded-2xl' },
                }).then(result => {
                    if (result.isConfirmed) {
                        form.querySelectorAll('button[type="submit"]').forEach(b => b.disabled = true);
                        form.submit();
                    }
                });
            });
        });
    </script>

</x-app-layout>