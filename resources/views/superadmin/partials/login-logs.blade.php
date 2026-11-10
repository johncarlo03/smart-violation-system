@php
    $roleNames = [1 => 'Student', 2 => 'CSO', 3 => 'SAO', 4 => 'Super Admin', 5 => 'Teaching Staff'];

    // Short, readable "Chrome on Windows" from the raw user agent
    $device = function (?string $ua) {
        if (!$ua) return '—';
        $browser = match (true) {
            str_contains($ua, 'Edg')     => 'Edge',
            str_contains($ua, 'OPR')     => 'Opera',
            str_contains($ua, 'Firefox') => 'Firefox',
            str_contains($ua, 'Chrome')  => 'Chrome',
            str_contains($ua, 'Safari')  => 'Safari',
            default                      => 'Unknown browser',
        };
        $os = match (true) {
            str_contains($ua, 'Windows')                              => 'Windows',
            str_contains($ua, 'Android')                              => 'Android',
            str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')  => 'iOS',
            str_contains($ua, 'Mac OS')                               => 'macOS',
            str_contains($ua, 'Linux')                                => 'Linux',
            default                                                   => 'Unknown OS',
        };
        return "$browser on $os";
    };
@endphp

{{-- Filters --}}
<form method="GET" class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5 mb-6">
    <input type="hidden" name="tab" value="logins">

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 items-end">
        <div class="lg:col-span-2">
            <label class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">Email or IP</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search..."
                class="w-full rounded-xl border-gray-200 text-sm">
        </div>

        <div>
            <label class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">Event</label>
            <select name="event" class="w-full rounded-xl border-gray-200 text-sm">
                <option value="">All events</option>
                @foreach(['login' => 'Login', 'logout' => 'Logout', 'failed' => 'Failed attempt'] as $key => $label)
                    <option value="{{ $key }}" @selected(request('event') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">From</label>
            <input type="date" name="from" value="{{ request('from') }}" class="w-full rounded-xl border-gray-200 text-sm">
        </div>

        <div>
            <label class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">To</label>
            <input type="date" name="to" value="{{ request('to') }}" class="w-full rounded-xl border-gray-200 text-sm">
        </div>

        <div class="flex gap-2">
            <x-primary-button type="submit">Filter</x-primary-button>
            <a href="{{ url()->current() }}?tab=logins"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold uppercase tracking-widest hover:bg-gray-200 flex items-center">
                Reset
            </a>
        </div>
    </div>

    <label class="inline-flex items-center gap-2 mt-4 text-sm text-gray-600 cursor-pointer">
        <input type="checkbox" name="privileged" value="1" @checked(request()->boolean('privileged'))
            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
        SAO and Super Admin accounts only
    </label>

    @error('from') <p class="text-xs font-bold text-red-500 mt-2">{{ $message }}</p> @enderror
    @error('to') <p class="text-xs font-bold text-red-500 mt-2">{{ $message }}</p> @enderror
</form>

{{-- Failed attempts today --}}
@if($failedToday > 0)
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl text-sm text-red-800 flex items-center justify-between gap-3">
        <p><span class="font-bold">{{ $failedToday }}</span> failed {{ \Illuminate\Support\Str::plural('login attempt', $failedToday) }} today.</p>
        <a href="{{ url()->current() }}?tab=logins&event=failed" class="font-semibold underline whitespace-nowrap">Show failed attempts</a>
    </div>
@endif

{{-- Log --}}
<div class="w-full bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100/50 flex justify-between items-center">
        <h3 class="text-lg font-black text-gray-800">Sign-in activity</h3>
        <span class="text-xs font-semibold text-gray-500">{{ $logins->total() }} {{ \Illuminate\Support\Str::plural('entry', $logins->total()) }}</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-500 text-xs font-semibold border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 whitespace-nowrap">When</th>
                    <th class="px-6 py-3">Account</th>
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3">Event</th>
                    <th class="px-6 py-3">IP address</th>
                    <th class="px-6 py-3">Device</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logins as $log)
                    @php
                        $privileged = in_array((int) $log->role, [3, 4], true);
                        $badge = match ($log->event) {
                            'login'  => 'bg-green-100 text-green-700',
                            'logout' => 'bg-gray-100 text-gray-700',
                            'failed' => 'bg-red-100 text-red-700',
                            default  => 'bg-gray-100 text-gray-700',
                        };
                        $eventLabel = $log->event === 'failed' ? 'Failed attempt' : ucfirst($log->event);
                    @endphp
                    <tr class="align-top {{ $privileged ? 'bg-indigo-50/40' : '' }} hover:bg-gray-50/70 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="font-medium text-gray-800">{{ $log->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $log->created_at->format('h:i:s A') }}</p>
                        </td>

                        <td class="px-6 py-4">
                            @if($log->user)
                                <p class="font-medium text-gray-800">{{ $log->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $log->email }}</p>
                            @else
                                <p class="font-medium text-gray-800">{{ $log->email ?: 'Unknown' }}</p>
                                <p class="text-xs text-gray-400">No matching account</p>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($log->role)
                                <span class="text-xs font-semibold {{ $privileged ? 'text-indigo-700' : 'text-gray-600' }}">
                                    {{ $roleNames[(int) $log->role] ?? 'Role ' . $log->role }}
                                </span>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $badge }}">{{ $eventLabel }}</span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600">{{ $log->ip_address ?: '—' }}</td>

                        <td class="px-6 py-4 text-xs text-gray-600" title="{{ $log->user_agent }}">{{ $device($log->user_agent) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">No sign-in activity matches these filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logins->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $logins->links() }}</div>
    @endif
</div>