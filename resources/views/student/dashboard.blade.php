<script type="text/javascript"
    src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.2/dist/browser-image-compression.js"></script>
<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    @php
        $user = Auth::user();
        $cleared = $pendingViolations->isEmpty();
        $defaultTab = $pendingViolations->isNotEmpty() ? 'pending'
            : ($resolvedViolations->isNotEmpty() ? 'resolved' : 'pending');
    @endphp

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Greeting --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">Welcome back, {{ $user->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $user->course->name ?? 'Student' }}
                        @if($user->year_level) &middot; Year {{ $user->year_level }} @endif
                    </p>
                </div>
                <p class="text-sm text-gray-500">Student ID <span class="font-semibold text-gray-800">{{ $user->id_number ?? $user->id }}</span></p>
            </div>

            {{-- Clearance status: the one thing a student needs to know first --}}
            <section aria-label="Clearance status"
                class="rounded-2xl border p-6 sm:p-8 flex flex-col sm:flex-row sm:items-center gap-6
                {{ $cleared ? 'bg-emerald-50 border-emerald-200' : 'bg-orange-50 border-orange-200' }}">

                <div class="shrink-0 w-14 h-14 rounded-full flex items-center justify-center
                    {{ $cleared ? 'bg-emerald-600 text-white' : 'bg-orange-800 text-white' }}">
                    @if($cleared)
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    @else
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M12 9v4m0 4h.01M10.3 3.9L2.4 18a2 2 0 001.7 3h15.8a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z" />
                        </svg>
                    @endif
                </div>

                <div class="flex-1">
                    @if($cleared)
                        <h4 class="text-xl font-bold text-emerald-900">You're cleared with the SAO</h4>
                        <p class="text-sm text-emerald-800 mt-1">You have no pending violations on your record.</p>
                    @else
                        <h4 class="text-xl font-bold text-orange-900">
                            {{ $pendingViolations->count() }} pending {{ Str::plural('violation', $pendingViolations->count()) }}
                            {{ $pendingViolations->count() === 1 ? 'needs' : 'need' }} your attention
                        </h4>
                        <p class="text-sm text-orange-900/80 mt-1">
                            Review each one below. You can submit an appeal while a violation is still pending.
                        </p>
                    @endif
                </div>

                {{-- Counts as quiet text, not three more cards --}}
                <dl class="flex gap-6 text-sm sm:text-right">
                    <div>
                        <dt class="text-gray-500">Resolved</dt>
                        <dd class="text-xl font-bold text-gray-900">{{ $resolvedViolations->count() }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Dismissed</dt>
                        <dd class="text-xl font-bold text-gray-900">{{ $dismissedViolations->count() }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Total</dt>
                        <dd class="text-xl font-bold text-gray-900">{{ $totalViolationsCount }}</dd>
                    </div>
                </dl>
            </section>

            {{-- Records --}}
            <section x-data="{ tab: '{{ $defaultTab }}' }" class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                <div class="px-6 pt-5">
                    <h4 class="font-bold text-gray-900">Your violation records</h4>
                    <p class="text-sm text-gray-500 mt-0.5">Each record shows the offense, the penalty assigned, and where it stands.</p>
                </div>

                <div role="tablist" class="flex gap-1 px-6 mt-4 border-b border-gray-200 overflow-x-auto">
                    @foreach([
                        'pending'   => ['Pending',   $pendingViolations->count()],
                        'resolved'  => ['Resolved',  $resolvedViolations->count()],
                        'dismissed' => ['Dismissed', $dismissedViolations->count()],
                    ] as $key => [$label, $count])
                        <button type="button" role="tab" @click="tab = '{{ $key }}'"
                            :aria-selected="tab === '{{ $key }}'"
                            :class="tab === '{{ $key }}'
                                ? 'border-orange-800 text-orange-800'
                                : 'border-transparent text-gray-500 hover:text-gray-800'"
                            class="px-4 py-3 text-sm font-semibold border-b-2 -mb-px whitespace-nowrap transition focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-700 rounded-t">
                            {{ $label }}
                            <span class="ml-1.5 text-xs px-2 py-0.5 rounded-full"
                                :class="tab === '{{ $key }}' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-600'">
                                {{ $count }}
                            </span>
                        </button>
                    @endforeach
                </div>

                <div x-show="tab === 'pending'" role="tabpanel">
                    @include('student.partials.violations-table', [
                        'violations' => $pendingViolations,
                        'type' => 'pending',
                        'empty' => 'No pending violations. You are in good standing.',
                    ])
                </div>

                <div x-show="tab === 'resolved'" x-cloak role="tabpanel">
                    @include('student.partials.violations-table', [
                        'violations' => $resolvedViolations,
                        'type' => 'resolved',
                        'empty' => 'No resolved violations yet.',
                    ])
                </div>

                <div x-show="tab === 'dismissed'" x-cloak role="tabpanel">
                    @include('student.partials.violations-table', [
                        'violations' => $dismissedViolations,
                        'type' => 'dismissed',
                        'empty' => 'No dismissed violations.',
                    ])
                </div>
            </section>
        </div>
    </div>

    @include('student.partials.chatbot')
    @include('student.partials.appeal-modal')

    <script>
        
        function openAppealModal(violationId) {
            window.dispatchEvent(new CustomEvent('open-appeal', { detail: violationId }));
        }
    </script>
</x-app-layout>