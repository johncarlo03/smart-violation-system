<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-gray-800 tracking-tight">
                    System Infrastructure & Governance
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Monitor system performance, user directories, and raw database integrity.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Analytics Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

                <div
                    class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">
                                Total System Users
                            </div>
                            <div class="text-4xl font-black text-gray-800 mt-2">
                                {{ $totalUsers }}
                            </div>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">
                                Active CSO
                            </div>
                            <div class="text-4xl font-black text-orange-500 mt-2">
                                {{ $activeCSO }}
                            </div>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-orange-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">
                                Active SAO Faculty
                            </div>
                            <div class="text-4xl font-black text-green-500 mt-2">
                                {{ $activeSAO }}
                            </div>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-xl transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">
                                Total Database Payload
                            </div>
                            <div class="text-4xl font-black text-red-600 mt-2">
                                {{ $databasePayload }}
                            </div>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-red-100 flex items-center justify-center">
                            <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Charts --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                {{-- Left Chart: Top Active Officers --}}
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                    <h3 class="font-black text-gray-800 mb-6 text-lg">
                        Top 5 System Activity by Officer
                    </h3>
                    <div class="space-y-5">
                        @php $maxTotal = $topOfficers->first()->total ?? 0; @endphp
                        @forelse($topOfficers as $item)
                            <div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="font-semibold text-gray-700">
                                        {{ $item->cso->name ?? 'Unknown Officer' }}
                                    </span>
                                    <span class="font-black text-indigo-600">
                                        {{ $item->total }} payloads
                                    </span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2.5">
                                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-700 h-2.5 rounded-full transition-all duration-700"
                                        style="width: {{ $maxTotal > 0 ? ($item->total / $maxTotal) * 100 : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-center text-gray-400 mt-2 italic">No logging data recorded yet.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Right Chart: User Node Densities --}}
                <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
                    <h3 class="font-black text-gray-800 mb-6 text-lg text-center">
                        Student Counts
                    </h3>
                    <div class="flex items-end justify-around h-56">
                        @foreach($deptStats as $dept)
                            <div class="flex flex-col items-center">
                                <div class="relative group">
                                    <div class="bg-gradient-to-t {{ $dept->barColor ?? 'from-gray-400 to-gray-500' }} w-14 rounded-t-2xl shadow-md transition-all duration-300 group-hover:scale-105"
                                        style="height: {{ $deptStats->max('total') > 0 ? ($dept->total / $deptStats->max('total')) * 140 : 0 }}px">
                                    </div>
                                </div>
                                <span class="text-[11px] mt-3 font-black text-gray-700 text-center uppercase tracking-wide">
                                    {{ $dept->acronym ?? 'N/A' }}
                                </span>
                                <span class="text-xs text-gray-400 font-semibold">
                                    {{ $dept->total }} users
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- Table: Global Audit Trail --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="text-xl font-black text-gray-800">
                        Recent Violation Activity or Violation Logs
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        A live view of student violations logged across campus departments.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gradient-to-r from-gray-50 to-gray-100 border-b text-gray-500 text-[11px] uppercase tracking-[0.15em]">
                                <th class="px-6 py-4 font-black">Student</th>
                                <th class="px-6 py-4 font-black">Violation</th>
                                <th class="px-6 py-4 font-black">Logged By</th>
                                <th class="px-6 py-4 font-black text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($auditLogs as $log)
                                <tr class="hover:bg-indigo-50/40 transition duration-200">
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-12 h-12 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-black text-lg shadow-sm">
                                                ID
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-900">
                                                    {{ $log->student->name ?? 'System Node Deleted' }}</div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    {{ $log->department->acronym ?? 'N/A' }} •
                                                    {{ $log->student->id_number ?? 'No ID' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="font-bold text-gray-800">{{ $log->offense->name ?? 'Raw Record Event' }}
                                        </div>
                                        <div
                                            class="inline-flex mt-2 px-2 py-1 rounded-full bg-gray-100 text-gray-600 text-[10px] uppercase font-black tracking-wider">
                                            {{ $log->offense->category ?? 'General' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-sm text-gray-600 font-medium">
                                        {{ $log->cso->name ?? 'System Process' }}
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span
                                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black tracking-wider uppercase border {{ $log->status === 'void' ? 'bg-red-100 text-red-700 border-red-200' : 'bg-green-100 text-green-700 border-green-200' }}">
                                            {{ $log->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-20 text-center">
                                        <p class="font-bold text-gray-600">No telemetry logs found in current session
                                            context.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>