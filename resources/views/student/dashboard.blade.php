<script src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.2/dist/browser-image-compression.js"></script>

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

    <div class="py-4 sm:py-8 bg-gray-50/50 min-h-screen overflow-x-hidden">
        <div class="max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 space-y-5 sm:space-y-8">

            {{-- Greeting & Student Info Card --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white sm:bg-transparent p-4 sm:p-0 rounded-2xl border sm:border-0 border-gray-100 shadow-sm sm:shadow-none">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 tracking-tight flex items-center gap-2">
    <span>Welcome back, {{ $user->name }}</span>
    <svg class="w-6 h-6 text-amber-600 shrink-0 inline-block animate-wave origin-[70%_70%]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11.5V14m0-2.5v-6a1.5 1.5 0 113 0m-3 6a1.5 1.5 0 00-3 0v2a7.5 7.5 0 0015 0v-5a1.5 1.5 0 00-3 0m-6-3V11m0-5.5a1.5 1.5 0 013 0v5.5m0 0V8a1.5 1.5 0 013 0v4.5" />
    </svg>
</h3>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                        {{ $user->course->name ?? 'Student' }}
                        @if($user->year_level) &middot; Year {{ $user->year_level }} @endif
                    </p>
                </div>
                <div class="inline-flex items-center self-start sm:self-auto bg-gray-100/80 px-3 py-1.5 rounded-xl border border-gray-200/60 text-xs sm:text-sm">
                    <span class="text-gray-500 mr-1.5">ID:</span>
                    <span class="font-semibold text-gray-800 tracking-wide">{{ $user->id_number ?? $user->id }}</span>
                </div>
            </div>

            {{-- Clearance Status Banner --}}
            <section aria-label="Clearance status"
                class="rounded-2xl border p-4 sm:p-8 flex flex-col md:flex-row md:items-center justify-between gap-5 transition-all duration-200 shadow-sm
                {{ $cleared ? 'bg-emerald-50/80 border-emerald-200/80' : 'bg-amber-50/80 border-amber-200/80' }}">

                <div class="flex items-start sm:items-center gap-3.5">
                    <div class="shrink-0 w-11 h-11 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center shadow-sm
                        {{ $cleared ? 'bg-emerald-600 text-white' : 'bg-amber-600 text-white' }}">
                        @if($cleared)
                            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                        @else
                            <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 9v4m0 4h.01M10.3 3.9L2.4 18a2 2 0 001.7 3h15.8a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z" />
                            </svg>
                        @endif
                    </div>

                    <div>
                        @if($cleared)
                            <h4 class="text-base sm:text-xl font-bold text-emerald-950">Cleared with SAO</h4>
                            <p class="text-xs sm:text-sm text-emerald-800/90 mt-0.5">You have no active violations on record.</p>
                        @else
                            <h4 class="text-base sm:text-xl font-bold text-amber-950">
                                {{ $pendingViolations->count() }} Pending {{ Str::plural('Violation', $pendingViolations->count()) }}
                            </h4>
                            <p class="text-xs sm:text-sm text-amber-900/80 mt-0.5">
                                Review your record below. Submit an appeal while violations are pending.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Summary Counters --}}
                <div class="grid grid-cols-3 gap-2 pt-3 md:pt-0 border-t md:border-t-0 border-black/5 text-center md:text-right shrink-0">
                    <div class="bg-white/60 sm:bg-transparent p-2 sm:p-0 rounded-xl">
                        <dt class="text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider">Resolved</dt>
                        <dd class="text-base sm:text-xl font-bold text-gray-900 mt-0.5">{{ $resolvedViolations->count() }}</dd>
                    </div>
                    <div class="bg-white/60 sm:bg-transparent p-2 sm:p-0 rounded-xl">
                        <dt class="text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider">Dismissed</dt>
                        <dd class="text-base sm:text-xl font-bold text-gray-900 mt-0.5">{{ $dismissedViolations->count() }}</dd>
                    </div>
                    <div class="bg-white/60 sm:bg-transparent p-2 sm:p-0 rounded-xl">
                        <dt class="text-[10px] sm:text-xs font-medium text-gray-500 uppercase tracking-wider">Total</dt>
                        <dd class="text-base sm:text-xl font-bold text-gray-900 mt-0.5">{{ $totalViolationsCount }}</dd>
                    </div>
                </div>
            </section>

            {{-- Records Section --}}
            <section x-data="{ tab: '{{ $defaultTab }}' }" class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
                <div class="px-4 pt-5 pb-3 sm:px-6 sm:pt-6">
                    <h4 class="text-base sm:text-lg font-bold text-gray-900">Violation Records</h4>
                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Track offenses, penalties, and status updates.</p>
                </div>

                {{-- Wrapped Tabs for Mobile Fit --}}
                <div role="tablist" class="flex flex-wrap gap-1 px-4 sm:px-6 border-b border-gray-100">
                    @foreach([
                        'pending'   => ['Pending',   $pendingViolations->count()],
                        'resolved'  => ['Resolved',  $resolvedViolations->count()],
                        'dismissed' => ['Dismissed', $dismissedViolations->count()],
                    ] as $key => [$label, $count])
                        <button type="button" role="tab" @click="tab = '{{ $key }}'"
                            :aria-selected="tab === '{{ $key }}'"
                            :class="tab === '{{ $key }}'
                                ? 'border-amber-700 text-amber-900 font-bold bg-amber-50/50 sm:bg-transparent'
                                : 'border-transparent text-gray-500 hover:text-gray-700 font-medium'"
                            class="py-2.5 px-3 text-xs sm:text-sm border-b-2 -mb-px transition flex items-center gap-1.5 rounded-t focus:outline-none">
                            <span>{{ $label }}</span>
                            <span class="text-[10px] sm:text-[11px] px-1.5 py-0.2 rounded-full font-semibold transition"
                                :class="tab === '{{ $key }}' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-600'">
                                {{ $count }}
                            </span>
                        </button>
                    @endforeach
                </div>

                {{-- Tab Content Panels --}}
                <div class="p-3 sm:p-6">
                    <div x-show="tab === 'pending'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" role="tabpanel">
                        @include('student.partials.violations-table', [
                            'violations' => $pendingViolations,
                            'type' => 'pending',
                            'empty' => 'No pending violations. You are in good standing.',
                        ])
                    </div>

                    <div x-show="tab === 'resolved'" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" role="tabpanel">
                        @include('student.partials.violations-table', [
                            'violations' => $resolvedViolations,
                            'type' => 'resolved',
                            'empty' => 'No resolved violations yet.',
                        ])
                    </div>

                    <div x-show="tab === 'dismissed'" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" role="tabpanel">
                        @include('student.partials.violations-table', [
                            'violations' => $dismissedViolations,
                            'type' => 'dismissed',
                            'empty' => 'No dismissed violations.',
                        ])
                    </div>
                </div>
            </section>
        </div>
    </div>
    <style>
    @keyframes wave {
        0%, 100% { transform: rotate(0deg) translateZ(0); }
        20%, 60% { transform: rotate(14deg) translateZ(0); }
        40%, 80% { transform: rotate(-8deg) translateZ(0); }
    }

    /* Only run animation if user hasn't disabled motion in OS */
    @media (prefers-reduced-motion: no-preference) {
        .animate-wave {
            animation: wave 2.5s infinite ease-in-out;
            will-change: transform;
        }
    }
</style>

{{-- --}}
    
    @include('student.partials.appeal-modal')
@include('student.partials.chatbot')
    <script>
        function openAppealModal(violationId) {
            window.dispatchEvent(new CustomEvent('open-appeal', { detail: violationId }));
        }
    </script>
</x-app-layout>