<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">
                    Manage Violation
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Review the violation and take appropriate action.
                </p>
            </div>

            <a href="{{ route('sao.violations.index') }}"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200">
                Back
            </a>
        </div>
    </x-slot>
    @php
        $appealPending = $latestAppeal && $latestAppeal->status === 'pending';
    @endphp

    @if (session('success'))
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-sm font-semibold text-green-800">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- STATUS --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-gray-400">
                            Violation Status
                        </p>

                        <p class="text-2xl font-black text-gray-800 mt-1">
                            {{ ucfirst($violation->status) }}
                        </p>
                    </div>

                    <span class="px-4 py-2 rounded-full text-xs font-black uppercase
                        {{ $violation->status === 'pending'
    ? 'bg-orange-100 text-orange-700'
    : ($violation->status === 'resolved'
        ? 'bg-green-100 text-green-700'
        : 'bg-red-100 text-red-700') }}">

                        {{ $violation->status }}

                    </span>

                </div>

            </div>


            {{-- STUDENT INFORMATION --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">

                <h3 class="text-lg font-bold text-gray-800 mb-5">
                    Student Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <p class="text-xs font-bold uppercase text-gray-400">
                            Student Name
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $violation->student->name }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs font-bold uppercase text-gray-400">
                            Student ID
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $violation->student->id_number }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs font-bold uppercase text-gray-400">
                            Department
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $violation->department->name ?? 'N/A' }}
                        </p>
                    </div>

                </div>

            </div>


            {{-- VIOLATION INFORMATION --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">

                <h3 class="text-lg font-bold text-gray-800 mb-5">
                    Violation Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <p class="text-xs font-bold uppercase text-gray-400">
                            Offense
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $violation->offense->name ?? 'N/A' }}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs font-bold uppercase text-gray-400">
                            Category
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $violation->offense->category ?? 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-400">Date Reported</p>
                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $violation->created_at->format('F d, Y h:i A') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase text-gray-400">Reported By</p>
                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $violation->cso->name ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-xs font-bold uppercase text-gray-400 mb-2">
                            Reporter's Description
                        </p>

                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-700">
                                {{ $violation->description ?: 'No description provided.' }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>


            {{-- EVIDENCE --}}
            @if($violation->evidence_image)

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">

                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        Evidence
                    </h3>

                    <img src="{{ asset('storage/' . $violation->evidence_image) }}"
                        class="max-w-xl w-full rounded-xl border border-gray-200 shadow-sm" alt="Violation evidence">

                </div>

            @endif

            {{-- STUDENT APPEAL --}}
            @if($violation->status === 'pending')

                @php
                    $appeal = $violation->appeals
                        ->sortByDesc('created_at')
                        ->first();
                @endphp

                @if($appeal)

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">

                        <div class="flex items-start justify-between gap-4 mb-5">

                            <div>
                                <h3 class="text-lg font-bold text-gray-800">
                                    Student Appeal
                                </h3>

                                <p class="text-sm text-gray-500 mt-1">
                                    Review the student's appeal before taking action on this violation.
                                </p>
                            </div>

                            <span class="px-3 py-1.5 rounded-full text-xs font-black uppercase
                                                                                                                                                                                                            {{ $appeal->status === 'pending'
                    ? 'bg-orange-100 text-orange-700'
                    : ($appeal->status === 'approved'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-red-100 text-red-700') }}">
                                {{ $appeal->status }}
                            </span>

                        </div>

                        {{-- Appeal Information --}}
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">

                            <div class="mb-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                                    Submitted By
                                </p>

                                <p class="font-semibold text-gray-800 mt-1">
                                    {{ $appeal->student->name ?? $violation->student->name }}
                                </p>
                            </div>

                            <div class="mb-5">
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-400">
                                    Date Submitted
                                </p>

                                <p class="text-sm text-gray-700 mt-1">
                                    {{ $appeal->created_at->format('F d, Y h:i A') }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">
                                    Reason for Appeal
                                </p>

                                <div class="bg-white border border-gray-200 rounded-xl p-4">
                                    <p class="text-sm text-gray-700 whitespace-pre-line">
                                        {{ $appeal->reason }}
                                    </p>
                                </div>
                            </div>

                        </div>

                        {{-- Appeal Evidence --}}
                        @if($appeal->evidence_image)

                            <div class="mt-5">

                                <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">
                                    Supporting Evidence
                                </p>

                                <img src="{{ asset('storage/' . $appeal->evidence_image) }}" alt="Appeal supporting evidence"
                                    class="max-w-xl w-full rounded-xl border border-gray-200 shadow-sm">

                            </div>

                        @endif

                    </div>

                @endif

            @endif

            {{-- PENDING VIOLATION --}}
            @if($violation->status === 'pending')
                {{-- RECOMMENDED PENALTY --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">
                        Recommended Penalty
                    </h3>
                    <p class="text-sm text-gray-500 mb-5">
                        The penalty is automatically determined based on the student's
                        previous resolved violations of this offense.
                    </p>
                    <div class="p-4 bg-indigo-50 border border-indigo-100 rounded-xl">
                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-500">
                            Offense Level
                        </p>
                        <p class="text-xl font-black text-indigo-900 mt-1">
                            {{ \Illuminate\Support\Number::ordinal($offenseNumber) }} Offense
                        </p>

                        @if($recommendedPenalty)
                            <div class="mt-4">
                                <p class="text-xs font-bold uppercase text-gray-500">
                                    Penalty
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $recommendedPenalty->penalty_description }}
                                </p>
                            </div>
                        @else
                            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm font-semibold text-red-600">
                                    No penalty has been configured for this offense level.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ACTION SELECTION --}}
                @if($violation->status === 'pending')

                    <div x-data="{ action: '' }" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                        <h3 class="text-lg font-bold text-gray-800 mb-2">
                            Take Action
                        </h3>

                        <p class="text-sm text-gray-500 mb-5">
                            Choose how you want to process this violation.
                        </p>

                        {{-- ACTION CHOICES --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            {{-- RESOLVE --}}
                            <button type="button" @click="action = 'resolve'"
                                :class="action === 'resolve'
                                                                                                                                                            ? 'border-green-500 bg-green-50 ring-2 ring-green-100'
                                                                                                                                                            : 'border-gray-200 hover:bg-gray-50'"
                                class="p-5 border rounded-xl text-left transition">
                                <div class="flex items-center gap-3">

                                    @if($latestAppeal && $latestAppeal->status === 'pending')
                                        <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    @else
                                        <div
                                            class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div>

                                        @if($latestAppeal && $latestAppeal->status === 'pending')
                                            <p class="font-bold text-gray-800">
                                                Reject Appeal
                                            </p>
                                        @else
                                            <p class="font-bold text-gray-800">
                                                Resolve Violation
                                            </p>
                                        @endif

                                        @if($latestAppeal && $latestAppeal->status === 'pending')
                                            <p class="text-xs text-gray-500 mt-1">
                                                The appeal is not valid. Apply the penalty and resolve the violation.
                                            </p>
                                        @else
                                            <p class="text-xs text-gray-500 mt-1">
                                                Apply the recommended penalty and resolve the violation.
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </button>


                            {{-- DISMISS --}}
                            <button type="button" @click="action = 'dismiss'"
                                :class="action === 'dismiss'
                                                                                                                                                            ? 'border-red-500 bg-red-50 ring-2 ring-red-100'
                                                                                                                                                            : 'border-gray-200 hover:bg-gray-50'"
                                class="p-5 border rounded-xl text-left transition">
                                <div class="flex items-center gap-3">

                                    @if($latestAppeal && $latestAppeal->status === 'pending')
                                        <div class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </div>
                                    @endif

                                    <div>
                                        @if($latestAppeal && $latestAppeal->status === 'pending')
                                            <p class="font-bold text-gray-800">
                                                Accept Appeal
                                            </p>
                                        @else
                                            <p class="font-bold text-gray-800">
                                                Dismiss Violation
                                            </p>
                                        @endif

                                        @if($latestAppeal && $latestAppeal->status === 'pending')
                                            <p class="text-xs text-gray-500 mt-1">
                                                The appeal is valid. Dismiss the violation.
                                            </p>
                                        @else
                                            <p class="text-xs text-gray-500 mt-1">
                                                Dismiss this violation because it is invalid.
                                            </p>
                                        @endif
                                    </div>

                                </div>
                            </button>

                        </div>


                        {{-- ==================== RESOLVE FORM ==================== --}}
                        <div x-show="action === 'resolve'" x-transition x-cloak class="mt-6">

                            @if($recommendedPenalty)

                                    <div class="p-4 mb-5 bg-indigo-50 border border-indigo-100 rounded-xl">

                                        <p class="text-xs font-bold uppercase tracking-wider text-indigo-500">
                                            Recommended Penalty
                                        </p>

                                        <p class="text-sm font-semibold text-indigo-900 mt-1">
                                            {{ $recommendedPenalty->penalty_description }}
                                        </p>

                                    </div>

                                    <form action="{{ route('violations.resolve', $violation) }}" method="POST" class="js-confirm-form"
                                        data-title="{{ $appealPending ? 'Reject this appeal?' : 'Resolve this violation?' }}" data-text="{{ $appealPending
                                ? 'The appeal will be denied and the recommended penalty will be applied to ' . $violation->student->name . '.'
                                : 'The recommended penalty will be applied to ' . $violation->student->name . '.' }}"
                                        data-confirm="{{ $appealPending ? 'Yes, reject appeal' : 'Yes, resolve' }}"
                                        data-color="#15803d">
                                        @csrf
                                        @method('PUT')

                                        <div class="mb-5">

                                            <label for="resolution_notes" class="block text-sm font-semibold text-gray-700 mb-2">
                                                Resolution Notes
                                            </label>

                                            <textarea id="resolution_notes" name="resolution_notes" rows="4" required
                                                class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500"
                                                placeholder="Enter notes regarding the action taken...">{{ old('resolution_notes') }}</textarea>

                                        </div>

                                        <x-primary-button type="submit">
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
                        <div x-show="action === 'dismiss'" x-transition x-cloak class="mt-6">

                            <form action="{{ route('violations.dismiss', $violation) }}" method="POST" class="js-confirm-form"
                                data-title="{{ $appealPending ? 'Accept this appeal?' : 'Dismiss this violation?' }}" data-text="{{ $appealPending
                    ? 'The appeal will be approved and the violation dismissed. No penalty will apply.'
                    : 'The violation will be dismissed and no penalty will apply.' }}"
                                data-confirm="{{ $appealPending ? 'Yes, accept appeal' : 'Yes, dismiss' }}"
                                data-color="#dc2626">
                                @csrf
                                @method('PUT')

                                <div class="mb-5">

                                    <label for="dismissal_reason" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Dismissal Reason
                                    </label>

                                    <textarea id="dismissal_reason" name="dismissal_reason" rows="4" required
                                        class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500"
                                        placeholder="Explain why this violation is being dismissed...">{{ old('dismissal_reason') }}</textarea>

                                </div>

                                <x-danger-button type="submit">
                                    @if($appealPending)
                                        Accept Appeal & Dismiss
                                    @else
                                        Dismiss Violation
                                    @endif
                                </x-danger-button>

                            </form>

                        </div>

                    </div>

                @endif

            @else
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 space-y-5">
                    <h3 class="text-lg font-bold text-gray-800">Processing Information</h3>

                    @if($violation->status === 'resolved')
                        <div>
                            <p class="text-xs font-bold uppercase text-gray-400">Applied Penalty</p>
                            @if($violation->penalty)
                                <p class="font-bold text-gray-800 mt-1">
                                    {{ \Illuminate\Support\Number::ordinal($violation->penalty->level) }} Offense
                                </p>
                                <p class="text-sm text-gray-600 mt-1">{{ $violation->penalty->penalty_description }}</p>
                            @else
                                <p class="text-gray-500 mt-1">No penalty recorded.</p>
                            @endif
                        </div>
                    @endif

                    @if($violation->resolution_notes)
                        <div>
                            <p class="text-xs font-bold uppercase text-gray-400">
                                {{ $violation->status === 'dismissed' ? 'Dismissal Reason' : 'Resolution Notes' }}
                            </p>
                            <p class="text-sm text-gray-600 mt-1 whitespace-pre-line">{{ $violation->resolution_notes }}</p>
                        </div>
                    @endif

                    @if($violation->resolved_at)
                        <div>
                            <p class="text-xs font-bold uppercase text-gray-400">Date Processed</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $violation->resolved_at->format('F d, Y h:i A') }}</p>
                        </div>
                    @endif

                    @if($latestAppeal)
                        <div>
                            <p class="text-xs font-bold uppercase text-gray-400">Student Appeal</p>
                            <p class="text-sm text-gray-700 mt-1 whitespace-pre-line">{{ $latestAppeal->reason }}</p>
                            <p class="text-xs text-gray-500 mt-2">
                                Status: <span class="font-semibold">{{ ucfirst($latestAppeal->status) }}</span>
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

</x-app-layout>

<script>
    document.querySelectorAll('.js-confirm-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: form.dataset.title,
                text: form.dataset.text,
                icon: 'question',
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