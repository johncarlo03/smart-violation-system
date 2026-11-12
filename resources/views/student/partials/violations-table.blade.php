@if($violations->isEmpty())
    <div class="text-center py-8 text-gray-500 text-xs sm:text-sm">
        {{ $empty }}
    </div>
@else
    {{-- Mobile View: Stacked Cards with Explicit Labels (Offense, Penalty, Reported/Date, Status) --}}
    <div class="space-y-3 sm:hidden">
        @foreach($violations as $violation)
            <div class="bg-gray-50/70 border border-gray-200/80 rounded-xl p-4 space-y-3">
                <div class="flex items-start justify-between gap-2">
                    <div class="w-full">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-0.5">Offense</span>
                        <h5 class="text-sm font-bold text-gray-900 leading-snug">
                            {{ $violation->offense_name ?? $violation->title }}
                        </h5>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs pt-2 border-t border-gray-200/60">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-0.5">Penalty</span>
                        <span class="font-semibold text-gray-800">{{ $violation->penalty ?? 'None' }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-0.5">Reported</span>
                        <span class="font-semibold text-gray-800">{{ $violation->created_at->format('M d, Y') }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-gray-200/60">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-0.5">Status</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold
                            {{ $type === 'pending' ? 'bg-amber-100 text-amber-800' : ($type === 'resolved' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-700') }}">
                            {{ ucfirst($type) }}
                        </span>
                    </div>

                    @if($type === 'pending')
                        <div class="self-end">
                            <button type="button" onclick="openAppealModal('{{ $violation->id }}')" 
                                class="bg-amber-800 hover:bg-amber-900 text-white text-xs font-semibold py-1.5 px-3 rounded-lg transition active:scale-[0.99]">
                                Appeal
                            </button>
                        </div>
                    @endif
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
                    <th class="py-3 px-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($violations as $violation)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="py-3.5 px-4 font-medium text-gray-900">
                            {{ $violation->offense_name ?? $violation->title }}
                        </td>
                        <td class="py-3.5 px-4 text-gray-600">
                            {{ $violation->penalty ?? 'None' }}
                        </td>
                        <td class="py-3.5 px-4 text-gray-600">
                            {{ $violation->created_at->format('M d, Y') }}
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            @if($type === 'pending')
                                <button type="button" onclick="openAppealModal('{{ $violation->id }}')" 
                                    class="text-xs bg-amber-800 hover:bg-amber-900 text-white font-semibold py-1.5 px-3 rounded-lg transition">
                                    Appeal
                                </button>
                            @else
                                <span class="text-xs text-gray-400 font-medium">Locked</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif