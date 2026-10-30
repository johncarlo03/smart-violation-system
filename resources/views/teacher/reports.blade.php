<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800">My Reports</h2>
                <p class="text-sm text-gray-500 mt-1">Violations you have reported and where they stand.</p>
            </div>

            {{-- Change this route to your report-a-violation page --}}
            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 whitespace-nowrap">
                Back
            </a>
        </div>
    </x-slot>

    @php
        $current = request('status');
        $tabs = [
            ''          => ['All', $total],
            'pending'   => ['Under review', $counts['pending'] ?? 0],
            'resolved'  => ['Resolved', $counts['resolved'] ?? 0],
            'dismissed' => ['Dismissed', $counts['dismissed'] ?? 0],
        ];
        $ordinal = fn ($n) => match ((int) $n) { 1 => '1st', 2 => '2nd', 3 => '3rd', default => (int) $n . 'th' };
    @endphp

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">

                {{-- Status tabs --}}
                <div class="flex gap-1 px-6 pt-4 border-b border-gray-200 overflow-x-auto">
                    @foreach($tabs as $key => [$label, $count])
                        <a href="{{ route('reports.mine', array_filter(['status' => $key, 'q' => request('q')])) }}"
                            class="px-4 py-3 text-sm font-semibold border-b-2 -mb-px whitespace-nowrap transition
                                {{ (string) $current === (string) $key
                                    ? 'border-indigo-600 text-indigo-700'
                                    : 'border-transparent text-gray-500 hover:text-gray-800' }}">
                            {{ $label }}
                            <span class="ml-1.5 text-xs px-2 py-0.5 rounded-full
                                {{ (string) $current === (string) $key ? 'bg-indigo-100 text-indigo-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $count }}
                            </span>
                        </a>
                    @endforeach
                </div>

                {{-- Search --}}
                <form method="GET" action="{{ route('reports.mine') }}" class="px-6 py-4 flex flex-col sm:flex-row gap-3 border-b border-gray-100">
                    @if($current) <input type="hidden" name="status" value="{{ $current }}"> @endif
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by student name or ID"
                        class="flex-1 rounded-xl border-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <div class="flex gap-2">
                        <x-primary-button type="submit">Search</x-primary-button>
                        @if(request('q'))
                            <a href="{{ route('reports.mine', array_filter(['status' => $current])) }}"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold uppercase tracking-widest hover:bg-gray-200 flex items-center">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50 text-gray-500 text-xs font-semibold border-b border-gray-200">
                            <tr>
                                <th scope="col" class="px-6 py-3">Student</th>
                                <th scope="col" class="px-6 py-3">Offense</th>
                                <th scope="col" class="px-6 py-3">Your details</th>
                                <th scope="col" class="px-6 py-3 whitespace-nowrap">Reported</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($violations as $v)
                                <tr class="align-top hover:bg-gray-50/70 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-gray-900">{{ $v->student->name ?? 'Unknown student' }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $v->student->id_number ?? '' }}</p>
                                    </td>

                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-800">{{ $v->offense->name ?? 'N/A' }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $v->offense->category ?? '' }}</p>
                                    </td>

                                    <td class="px-6 py-4 max-w-xs">
                                        <p class="text-gray-700">{{ \Illuminate\Support\Str::limit($v->description, 90) ?: '—' }}</p>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                        <p>{{ $v->created_at->format('M d, Y') }}</p>
                                        <p class="text-xs">{{ $v->created_at->format('h:i A') }}</p>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($v->status === 'pending')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Under review
                                            </span>
                                            <p class="text-xs text-gray-400 mt-1.5">Waiting for the SAO</p>

                                        @elseif($v->status === 'resolved')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Resolved
                                            </span>
                                            <p class="text-xs text-gray-500 mt-1.5">
                                                @if($v->penalty)
                                                    Recorded as {{ $ordinal($v->penalty->level) }} offense
                                                @endif
                                                @if($v->resolved_at)
                                                    <span class="block text-gray-400">{{ \Carbon\Carbon::parse($v->resolved_at)->format('M d, Y') }}</span>
                                                @endif
                                            </p>

                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                                                Dismissed
                                            </span>
                                            @if($v->resolved_at)
                                                <p class="text-xs text-gray-400 mt-1.5">{{ \Carbon\Carbon::parse($v->resolved_at)->format('M d, Y') }}</p>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-14 text-center text-gray-500">
                                        @if(request('q') || $current)
                                            <p class="font-medium">No reports match your filters.</p>
                                        @else
                                            <p class="font-medium">You haven't reported any violations yet.</p>
                                            <p class="text-xs text-gray-400 mt-1">Reports you file will appear here.</p>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($violations->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $violations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>