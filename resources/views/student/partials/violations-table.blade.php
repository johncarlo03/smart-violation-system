{{-- Expects: $violations, $type ('pending'|'resolved'|'dismissed'), $empty --}}
<div class="overflow-x-auto">
    <table class="w-full text-left text-sm text-gray-600">
        <thead class="text-gray-500 text-xs font-semibold border-b border-gray-200 bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3">Offense</th>
                @if($type !== 'dismissed')
                    <th scope="col" class="px-6 py-3">Penalty</th>
                @else
                    <th scope="col" class="px-6 py-3">Reason for dismissal</th>
                @endif
                <th scope="col" class="px-6 py-3 whitespace-nowrap">Reported</th>
                @if($type !== 'pending')
                    <th scope="col" class="px-6 py-3 whitespace-nowrap">{{ ucfirst($type) }}</th>
                @endif
                <th scope="col" class="px-6 py-3">Appeal</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-100">
            @forelse($violations as $violation)
                @php
                    $pendingAppeal = $violation->appeals->where('status', 'pending')->first();
                    $latestAppeal = $violation->appeals->sortByDesc('created_at')->first();
                @endphp

                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-semibold text-gray-900">{{ $violation->offense->name ?? 'Unspecified offense' }}</p>
                        @if($type !== 'dismissed')
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ \Illuminate\Support\Number::ordinal($violation->offense_number) }} Offense</p>
                        @endif
                    </td>

                    <td class="px-6 py-4 text-gray-700 max-w-md">
                        @if($type !== 'dismissed')
                                    {{ $violation->penalty->penalty_description
                            ?? $violation->recommended_penalty->penalty_description
                            ?? 'No penalty description yet' }}
                        @else
                            {{ $violation->resolution_notes ?: 'No reason provided' }}
                        @endif
                    </td>

                    <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                        {{ $violation->created_at?->format('M d, Y') ?? 'N/A' }}
                    </td>

                    @if($type !== 'pending')
                        <td class="px-6 py-4 text-gray-500 whitespace-nowrap">
                            {{ $violation->resolved_at?->format('M d, Y') ?? 'N/A' }}
                        </td>
                    @endif

                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($pendingAppeal)
                            <span class="inline-flex items-center gap-1.5 text-amber-800 font-semibold">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Under review
                            </span>
                        @elseif($latestAppeal && $latestAppeal->status === 'approved')
                            <span class="inline-flex items-center gap-1.5 text-emerald-800 font-semibold">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Approved
                            </span>
                        @elseif($latestAppeal && $latestAppeal->status === 'denied')
                            <span class="inline-flex items-center gap-1.5 text-red-800 font-semibold">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span> Denied
                            </span>
                        @elseif($type === 'pending')
                            <button type="button" onclick="openAppealModal({{ $violation->id }})"
                                class="px-3.5 py-1.5 rounded-lg bg-orange-800 text-white text-xs font-semibold hover:bg-orange-900 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-orange-700">
                                Submit appeal
                            </button>
                        @else
                            <span class="text-gray-400">None</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">{{ $empty }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>