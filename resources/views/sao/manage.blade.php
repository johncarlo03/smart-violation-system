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


                    <div>
                        <p class="text-xs font-bold uppercase text-gray-400">
                            Reported By
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">
                            {{ $violation->cso->name ?? 'N/A' }}
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
                            @if($offenseNumber == 1)
                                1st
                            @elseif($offenseNumber == 2)
                                2nd
                            @elseif($offenseNumber == 3)
                                3rd
                            @else
                                {{ $offenseNumber }}th
                            @endif
                            Offense
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

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-2">
                        Resolve Violation
                    </h3>
                    <p class="text-sm text-gray-500 mb-5">
                        The recommended penalty above will automatically be applied when
                        this violation is resolved.
                    </p>
                    @if($recommendedPenalty)

                        <form action="{{ route('violations.resolve', $violation) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-5">
                                <label for="resolution_notes" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Resolution Notes
                                </label>
                                <textarea id="resolution_notes" name="resolution_notes" rows="3" required
                                    class="w-full rounded-xl border-gray-300 focus:border-green-500 focus:ring-green-500"
                                    placeholder="Enter notes regarding the action taken...">{{ old('resolution_notes') }}</textarea>
                            </div>

                            @if($latestAppeal && $latestAppeal->status === 'pending')

                                <x-primary-button type="submit"
                                    onclick="return confirm('Are you sure you want to apply the recommended penalty and resolve this violation?')">
                                    Deny Penalty & Resolve
                                </x-primary-button>
                            @else
                                <x-primary-button type="submit"
                                    onclick="return confirm('Are you sure you want to resolve this violation?')">
                                    Apply Penalty & Resolve
                                </x-primary-button>
                            @endif
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

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

                    <h3 class="text-lg font-bold text-gray-800 mb-2">
                        Dismiss Violation
                    </h3>

                    <p class="text-sm text-gray-500 mb-5">
                        Dismiss the violation if it is invalid, incorrect, or cannot be
                        sufficiently verified.
                    </p>


                    <form action="{{ route('violations.dismiss', $violation) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-5">
                            <label for="dismissal_reason" class="block text-sm font-semibold text-gray-700 mb-2">
                                Dismissal Reason
                            </label>

                            <textarea id="dismissal_reason" name="dismissal_reason" rows="3" required
                                class="w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500"
                                placeholder="Explain why this violation is being dismissed...">{{ old('dismissal_reason') }}</textarea>
                        </div>

                        @if(!$latestAppeal)
                            <x-danger-button type="submit" onclick="return confirm('Are you sure you want to dismiss this violation?')">
                                Dismiss Violation
                            </x-danger-button>
                        @endif
                        @if($latestAppeal && $latestAppeal->status === 'pending')
                            <x-danger-button type="submit"
                                onclick="return confirm('Are you sure you want to accept this appeal and dismiss the violation?')">
                                Accept Appeal & Dismiss
                            </x-danger-button>
                        @endif
                    </form>

                </div>

            @else
                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-5">
                        Processing Information
                    </h3>

                    @if($violation->status === 'resolved')

                        <div class="mb-5">
                            <p class="text-xs font-bold uppercase text-gray-400">
                                Applied Penalty
                            </p>

                            @if($violation->penalty)
                                <p class="font-bold text-gray-800 mt-1">
                                    {{ $violation->penalty->level }} Offense
                                </p>

                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $violation->penalty->penalty_description }}
                                </p>
                            @else
                                <p class="text-gray-500 mt-1">
                                    No penalty recorded.
                                </p>
                            @endif
                        </div>

                        @if($violation->resolution_notes)
                            <div>
                                <p class="text-xs font-bold uppercase text-gray-400">
                                    Resolution Notes
                                </p>

                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $violation->resolution_notes }}
                                </p>
                            </div>
                        @endif
                    @elseif($violation->status === 'dismissed')
                        @if($violation->resolution_notes)
                            <div>
                                <p class="text-xs font-bold uppercase text-gray-400">
                                    Dismissal Reason
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $violation->resolution_notes }}
                                </p>
                            </div>
                        @endif
                    @endif
                </div>
            @endif
        </div>
    </div>

</x-app-layout>