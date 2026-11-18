@if($violations->isEmpty())
    <div class="text-center py-8 text-gray-500 text-xs sm:text-sm">
        {{ $empty }}
    </div>
@else
    {{-- Mobile View: Stacked Cards with Explicit Labels (Offense, Penalty, Reported/Date, Status) --}}
    <div class="space-y-3 sm:hidden">
        @foreach($violations as $violation)
            @php
                $offense = $violation->offense_number;

                $latestAppeal = $violation->appeals->sortByDesc('created_at')->first();
                $pendingAppeal = $violation->appeals->whereIn('status', ['pending_chairman'])->first();

                $offenseLabel = match ((int) $offense) {
                    1 => '1st Offense',
                    2 => '2nd Offense',
                    3 => '3rd Offense',
                    default => $offense ? $offense . 'th Offense' : null,
                };
            @endphp
            <div class="bg-gray-50/70 border border-gray-200/80 rounded-xl p-4 space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div class="w-full">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-0.5">
                            Offense
                        </span>

                        <h5 class="text-sm font-bold text-gray-900 leading-snug">
                            {{ $violation->offense->name ?? 'Unspecified offense' }}
                        </h5>

                        @if($offenseLabel)
                            <span
                                class="inline-flex mt-1 items-center rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-bold text-indigo-700">
                                {{ $offenseLabel }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs pt-2 border-t border-gray-200/60">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-0.5">
                            Penalty
                        </span>

                        @if($violation->status === 'resolved')
                            <span class="font-semibold text-emerald-700">
                                {{ $violation->penalty->penalty_description ?? 'No penalty assigned' }}
                            </span>

                        @elseif(
                                in_array($violation->status, [
                                    'pending_chairman',
                                    'pending_dean',
                                    'pending_sao',
                                ])
                            )
                            @if($violation->recommended_penalty)
                                <span class="font-semibold text-amber-700">
                                    {{ $violation->recommended_penalty->penalty_description }}
                                </span>
                                @if ($violation->status === 'pending_chairman')
                                    <span class="block mt-1 text-[10px] text-gray-400">
                                        Expected penalty — subject to Chairman's decision
                                    </span>
                                @elseif ($violation->status === 'pending_dean')
                                    <span class="block mt-1 text-[10px] text-gray-400">
                                        Expected penalty — subject to Dean's decision
                                    </span>
                                @elseif ($violation->status === 'pending_sao')
                                    <span class="block mt-1 text-[10px] text-gray-400">
                                        Expected penalty — subject to SAO decision
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-500">
                                    Awaiting SAO decision
                                </span>
                            @endif

                        @elseif($violation->status === 'dismissed')
                            <span class="font-semibold text-gray-500">
                                No penalty — dismissed
                            </span>
                        @endif
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-0.5">Reported</span>
                        <span class="font-semibold text-gray-800">{{ $violation->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-gray-200/60">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-0.5">
                            Status
                        </span>

                        <span
                            class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold
                                            {{ $type === 'pending' ? 'bg-amber-100 text-amber-800' : ($type === 'resolved' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700') }}">
                            {{ ucfirst($type) }}
                        </span>
                    </div>

                    <div class="self-end">
                        @if($pendingAppeal)
                            <span class="inline-flex items-center gap-1.5 text-amber-800 text-xs font-semibold">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                Under review
                            </span>

                        @elseif($latestAppeal && $latestAppeal->status === 'approved')
                            <span class="inline-flex items-center gap-1.5 text-emerald-800 text-xs font-semibold">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Approved
                            </span>

                        @elseif($latestAppeal && $latestAppeal->status === 'denied')
                            <span class="inline-flex items-center gap-1.5 text-red-800 text-xs font-semibold">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                Denied
                            </span>

                        @elseif($type === 'pending')
                            <button type="button" onclick="openAppealModal({{ $violation->id }})"
                                class="px-3.5 py-1.5 rounded-lg bg-orange-800 text-white text-xs font-semibold hover:bg-orange-900 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-orange-700">
                                Submit Appeal
                            </button>

                        @else
                            <span class="text-xs text-gray-400 font-medium">
                                Locked
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Desktop View: Standard Table Layout --}}
    <div class="hidden sm:block">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="border-b border-gray-200 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="py-3 px-4 font-semibold">Offense</th>
                    <th class="py-3 px-4 font-semibold">Penalty</th>
                    <th class="py-3 px-4 font-semibold">Reported</th>
                    <th class="py-3 px-4 font-semibold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($violations as $violation)
                    @php
                        $offense = $violation->offense_number;

                        $latestAppeal = $violation->appeals->sortByDesc('created_at')->first();
                        $pendingAppeal = $violation->appeals->where('status', 'pending')->first();

                        $offenseLabel = match ((int) $offense) {
                            1 => '1st Offense',
                            2 => '2nd Offense',
                            3 => '3rd Offense',
                            default => $offense ? $offense . 'th Offense' : null,
                        };
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-4 font-medium text-gray-900">
                            <div>
                                <div>
                                    {{ $violation->offense->name ?? 'Unspecified offense' }}
                                </div>

                                @if($offenseLabel)
                                    <span
                                        class="inline-flex mt-1 items-center rounded-full bg-indigo-50 px-2 py-0.5 text-[10px] font-bold text-indigo-700">
                                        {{ $offenseLabel }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3.5 px-4 text-gray-600">
                            @if($violation->status === 'resolved')
                                <span class="font-semibold text-emerald-700">
                                    {{ $violation->penalty->penalty_description ?? 'No penalty assigned' }}
                                </span>

                            @elseif(
                                    in_array($violation->status, [
                                        'pending_chairman',
                                        'pending_dean',
                                        'pending_sao',
                                    ])
                                )
                                <div>
                                    <span class="font-semibold text-amber-700">
                                        {{ $violation->recommended_penalty->penalty_description ?? 'Awaiting SAO decision' }}
                                    </span>

                                    @if($violation->recommended_penalty)
                                        @if ($violation->status === 'pending_chairman')
                                            <span class="block mt-1 text-[10px] text-gray-400">
                                                Expected penalty — subject to Chairman's decision
                                            </span>
                                        @elseif ($violation->status === 'pending_dean')
                                            <span class="block mt-1 text-[10px] text-gray-400">
                                                Expected penalty — subject to Dean's decision
                                            </span>
                                        @elseif ($violation->status === 'pending_sao')
                                            <span class="block mt-1 text-[10px] text-gray-400">
                                                Expected penalty — subject to SAO decision
                                            </span>
                                        @endif
                                    @endif
                                </div>

                            @elseif($violation->status === 'dismissed')
                                <span class="text-gray-500">
                                    No penalty — dismissed
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-gray-600">
                            {{ $violation->created_at->format('M d, Y') }}
                        </td>
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
                                    Submit Appeal
                                </button>
                            @else
                                <span class="text-gray-400">Locked</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif