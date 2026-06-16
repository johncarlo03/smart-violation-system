<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex">

        {{-- Sidebar Navigation Layout --}}
        <aside class="w-64 bg-white border-r border-gray-100 shadow-sm hidden md:flex flex-col shrink-0">
            <a href="{{ route('profile.edit') }}"
    class="px-6 py-5 border-b border-gray-100 flex items-center justify-between hover:bg-gray-50/70 transition duration-200 group">
    
    <div class="flex items-center gap-3 min-w-0">
        {{-- Avatar Wrapper: Removed the double-nesting conflict --}}
        <div class="relative shrink-0">
            @if(Auth::user()->profile_photo)
                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}"
                     class="w-10 h-10 rounded-xl object-cover shadow-sm group-hover:scale-105 transition duration-200">
            @else
                <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black text-sm shadow-sm group-hover:scale-105 transition duration-200">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
            @endif
        </div>

        {{-- Meta Copy Column --}}
        <div class="truncate min-w-0">
            <h1 class="text-sm font-black text-gray-800 tracking-tight truncate group-hover:text-indigo-600 transition duration-200">
                {{ Auth::user()->name }}
            </h1>
            <p class="text-[10px] font-black uppercase tracking-wider text-gray-400 mt-0.5 flex items-center gap-1">
                 Super Admin
            </p>
        </div>
    </div>

    {{-- Subtle Action Arrow (Disappears on mobile, clean anchor indicator) --}}
    <div class="text-gray-300 group-hover:text-indigo-500 group-hover:translate-x-0.5 transition duration-200 hidden sm:block pl-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
        </svg>
    </div>
</a>

            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <a href="{{ route('superadmin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 bg-indigo-50/60 border border-indigo-100/50 rounded-xl text-indigo-600 font-bold text-sm transition duration-200">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" />
                    </svg>
                    {{ __('Infrastructure Panel') }}
                </a>

                <a href="{{ route('superadmin.users') }}"
                    class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:text-gray-800 hover:bg-gray-50 rounded-xl font-bold text-sm transition duration-200 group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    {{ __('User Directory Governance') }}
                </a>

                <a href="#"
                    class="flex items-center gap-3 px-4 py-3 text-gray-500 hover:text-gray-800 hover:bg-gray-50 rounded-xl font-bold text-sm transition duration-200 group">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-gray-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    {{ __('System Triggers') }}
                </a>
            </nav>

            <div class="p-4 border-t border-gray-100 bg-gray-50/50 flex flex-col gap-3">
                {{-- Bottom Block: Full-Width Clean Action Segment --}}
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 hover:bg-red-100 border border-red-100 text-red-600 rounded-xl text-xs font-black uppercase tracking-wider transition duration-200 shadow-sm">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        {{ __('Log Out') }}
                    </button>
                </form>

            </div>
        </aside>

        {{-- Main Content Canvas Window --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto max-h-screen">
            <main class="p-8 lg:p-10">

                {{-- Header Titles Section --}}
                <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-3xl font-black text-gray-800 tracking-tight">
                            System Infrastructure & Governance
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">
                            Monitor system performance, user directories, and raw database integrity.
                        </p>
                    </div>
                </div>

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
                                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                                        <svg class="w-7 h-7 text-orange-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                                        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
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
                                            Total Database Payload
                                        </div>
                                        <div class="text-4xl font-black text-red-600 mt-2">
                                            {{ $databasePayload }}
                                        </div>
                                    </div>
                                    <div class="w-14 h-14 rounded-2xl bg-red-100 flex items-center justify-center">
                                        <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
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
                                        <p class="text-xs text-center text-gray-400 mt-2 italic">No logging data recorded
                                            yet.</p>
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
                                            <span
                                                class="text-[11px] mt-3 font-black text-gray-700 text-center uppercase tracking-wide">
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
                                    Recent Violation Activity
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    View recently submitted student incidents logged across campus departments.
                                </p>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr
                                            class="bg-gradient-to-r from-gray-50 to-gray-100 border-b text-gray-500 text-[11px] uppercase tracking-[0.15em]">
                                            <th class="px-6 py-4 font-black">Student</th>
                                            <th class="px-6 py-4 font-black">Offense</th>
                                            <th class="px-6 py-4 font-black">Logged By</th>
                                            <th class="px-6 py-4 font-black text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse($auditLogs as $log)
                                            <tr class="hover:bg-indigo-50/40 transition duration-200">
                                                <td class="px-6 py-5">
                                                    <div class="flex items-center gap-4">
                                                        @if($log->student->profile_photo)
                                                            <img src="{{ asset('storage/' . $log->student->profile_photo) }}"
                                                                class="w-12 h-12 rounded-full object-cover shadow-sm">
                                                        @else
                                                            <div
                                                                class="w-12 h-12 rounded-full {{ $log->department->badgeColor ?? 'bg-gray-100' }} flex items-center justify-center font-black text-sm shadow-sm">
                                                                {{ strtoupper(substr($log->student->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="font-bold text-gray-900">
                                                                {{ $log->student->name ?? 'System Node Deleted' }}
                                                            </div>
                                                            <div class="text-xs text-gray-500 mt-1">
                                                                {{ $log->department->acronym ?? 'N/A' }} •
                                                                {{ $log->student->id_number ?? 'No ID' }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-5">
                                                    <div class="font-bold text-gray-800">
                                                        {{ $log->offense->name ?? 'Raw Record Event' }}
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
                                                    <p class="font-bold text-gray-600">No telemetry logs found in current
                                                        session
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

            </main>
        </div>
    </div>


</x-app-layout>