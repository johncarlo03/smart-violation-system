<x-app-layout>
    <div class="min-h-screen bg-gray-50 flex flex-col md:flex-row">

        @include('layouts.superadmin-nav')

        <div class="flex-1 flex flex-col min-w-0">
            <main class="p-4 sm:p-6 lg:p-10">

                <div class="max-w-7xl mx-auto">

                    {{-- Header & Tabs --}}
                    <div class="mb-6 sm:mb-8">
                        <h2 class="text-2xl sm:text-3xl font-black text-gray-900 tracking-tight">Audit Log</h2>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1">
                            A read-only record of who changed what, and when.
                        </p>

                        @php
                            $tab = request('tab') === 'logins' ? 'logins' : 'changes';
                        @endphp

                        <div class="flex gap-1 mt-5 border-b border-gray-200 overflow-x-auto">
                            @foreach(['changes' => 'Changes', 'logins' => 'Logins'] as $key => $label)
                                <a href="{{ url()->current() }}{{ $key === 'logins' ? '?tab=logins' : '' }}"
                                    class="px-4 sm:px-5 py-2.5 sm:py-3 text-xs sm:text-sm font-bold border-b-2 -mb-px whitespace-nowrap transition
                                        {{ $tab === $key ? 'border-red-700 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-800' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    @if($tab === 'logins')
                        <div class="min-w-0">
                            @include('superadmin.partials.login-logs')
                        </div>
                    @else
                        {{-- Filters --}}
                        <form method="GET" class="bg-white rounded-2xl shadow-xs border border-gray-200/80 p-4 sm:p-5 mb-6">
                            <input type="hidden" name="tab" value="changes">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 items-end">

                                <div class="min-w-0">
                                    <label
                                        class="block text-[11px] font-black uppercase tracking-wider text-gray-500 mb-1.5">User</label>
                                    <select name="user"
                                        class="w-full rounded-xl border-gray-200 text-base sm:text-sm text-gray-800 focus:border-[#1f2937] focus:ring-[#1f2937]">
                                        <option value="">All users</option>
                                        @foreach($users as $u)
                                            <option value="{{ $u->id }}" @selected(request('user') == $u->id)>{{ $u->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="min-w-0">
                                    <label
                                        class="block text-[11px] font-black uppercase tracking-wider text-gray-500 mb-1.5">Action</label>
                                    <select name="event"
                                        class="w-full rounded-xl border-gray-200 text-base sm:text-sm text-gray-800 focus:border-[#1f2937] focus:ring-[#1f2937]">
                                        <option value="">All actions</option>
                                        @foreach($events as $e)
                                            <option value="{{ $e }}" @selected(request('event') === $e)>{{ ucfirst($e) }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="min-w-0">
                                    <label
                                        class="block text-[11px] font-black uppercase tracking-wider text-gray-500 mb-1.5">Record</label>
                                    <select name="type"
                                        class="w-full rounded-xl border-gray-200 text-base sm:text-sm text-gray-800 focus:border-[#1f2937] focus:ring-[#1f2937]">
                                        <option value="">All records</option>
                                        @foreach($types as $t)
                                            <option value="{{ $t }}" @selected(request('type') === $t)>{{ class_basename($t) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="min-w-0">
                                    <label
                                        class="block text-[11px] font-black uppercase tracking-wider text-gray-500 mb-1.5">From</label>
                                    <input type="date" name="from" value="{{ request('from') }}"
                                        class="w-full rounded-xl border-gray-200 text-base sm:text-sm text-gray-800 focus:border-[#1f2937] focus:ring-[#1f2937]">
                                </div>

                                <div class="min-w-0">
                                    <label
                                        class="block text-[11px] font-black uppercase tracking-wider text-gray-500 mb-1.5">To</label>
                                    <input type="date" name="to" value="{{ request('to') }}"
                                        class="w-full rounded-xl border-gray-200 text-base sm:text-sm text-gray-800 focus:border-[#1f2937] focus:ring-[#1f2937]">
                                </div>

                                <div class="flex gap-2">
                                    <button type="submit"
                                        class="flex-1 sm:flex-initial px-4 py-2.5 bg-[#1f2937] hover:bg-gray-800 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition">
                                        Filter
                                    </button>
                                    <a href="{{ url()->current() }}"
                                        class="flex-1 sm:flex-initial px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-gray-200 flex items-center justify-center transition">
                                        Reset
                                    </a>
                                </div>
                            </div>
                            @error('from')
                                <p class="text-xs font-bold text-red-700 mt-2">{{ $message }}</p>
                            @enderror
                            @error('to')
                                <p class="text-xs font-bold text-red-700 mt-2">{{ $message }}</p>
                            @enderror
                        </form>

                        {{-- Log --}}
                        <div class="w-full bg-white rounded-2xl shadow-xs border border-gray-200/80 overflow-hidden">
                            <div class="h-1 w-full bg-gradient-to-r from-red-700 to-amber-500"></div>
                            <div
                                class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-100 bg-gray-50/70 flex items-center justify-between gap-3">
                                <h3 class="text-base sm:text-lg font-black text-gray-900">Activity</h3>
                                <span class="text-xs font-semibold text-gray-500 shrink-0">{{ $audits->total() }}
                                    {{ \Illuminate\Support\Str::plural('entry', $audits->total()) }}</span>
                            </div>

                            {{-- Mobile Card List (Visible below md) --}}
                            <div class="md:hidden divide-y divide-gray-100">
                                @forelse($audits as $audit)
                                    @php
                                        $old = $audit->old_values ?? [];
                                        $new = $audit->new_values ?? [];
                                        $fields = array_values(array_unique(array_merge(array_keys($old), array_keys($new))));
                                        $fmt = function ($v, $field) use ($labels) {
                                            if ($v === null || $v === '')
                                                return '—';

                                            if (isset($labels[$field])) {
                                                $name = $labels[$field]->get($v);
                                                return $name
                                                    ? \Illuminate\Support\Str::limit($name, 60) . " (#{$v})"
                                                    : "#{$v} (record no longer exists)";
                                            }

                                            return \Illuminate\Support\Str::limit(is_array($v) ? json_encode($v) : (string) $v, 80);
                                        };
                                        $badge = match ($audit->event) {
                                            'created' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                            'updated' => 'bg-amber-50 text-amber-800 border-amber-200',
                                            'deleted' => 'bg-red-50 text-red-700 border-red-200',
                                            'restored' => 'bg-gray-100 text-gray-800 border-gray-200',
                                            default => 'bg-gray-100 text-gray-700 border-gray-200',
                                        };
                                    @endphp

                                    <div class="p-4 space-y-3 hover:bg-gray-50/70 transition-colors">
                                        <div class="flex items-start justify-between gap-2.5">
                                            <div class="min-w-0">
                                                <p class="font-bold text-sm text-gray-900 truncate">{{ $audit->user->name ?? 'System' }}</p>
                                                <p class="text-xs text-gray-500 mt-0.5">
                                                    {{ $audit->created_at->format('M d, Y') }} • {{ $audit->created_at->format('h:i A') }}
                                                    @if($audit->ip_address)
                                                        • {{ $audit->ip_address }}
                                                    @endif
                                                </p>
                                            </div>

                                            <span class="shrink-0 inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold border {{ $badge }}">
                                                {{ ucfirst($audit->event) }}
                                            </span>
                                        </div>

                                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 space-y-2">
                                            <div class="min-w-0">
                                                <p class="font-bold text-xs text-gray-800 break-words">
                                                    {{ class_basename($audit->auditable_type) }} #{{ $audit->auditable_id }}
                                                </p>
                                                @if($audit->auditable instanceof \App\Models\Violation)
                                                    <p class="text-xs text-gray-500 break-words mt-0.5">
                                                        {{ $audit->auditable->student->name ?? 'Unknown student' }}
                                                        &middot; {{ $audit->auditable->offense->name ?? 'Unknown offense' }}
                                                    </p>
                                                @elseif($audit->auditable instanceof \App\Models\User)
                                                    <p class="text-xs text-gray-500 break-words mt-0.5">{{ $audit->auditable->name }}</p>
                                                @endif
                                            </div>

                                            <div class="border-t border-gray-200/70 pt-2 text-xs space-y-1">
                                                @forelse($fields as $field)
                                                    <div class="break-words">
                                                        <span class="font-semibold text-gray-700">{{ \Illuminate\Support\Str::headline(\Illuminate\Support\Str::replaceLast('_id', '', $field)) }}::</span>
                                                        @if($audit->event === 'updated')
                                                            <span class="text-gray-500">{{ $fmt($old[$field] ?? null, $field) }}</span>
                                                            <span class="text-gray-400">&rarr;</span>
                                                            <span class="text-gray-900 font-medium">{{ $fmt($new[$field] ?? null, $field) }}</span>
                                                        @elseif($audit->event === 'deleted')
                                                            <span class="text-gray-500">{{ $fmt($old[$field] ?? null, $field) }}</span>
                                                        @else
                                                            <span class="text-gray-900 font-medium">{{ $fmt($new[$field] ?? null, $field) }}</span>
                                                        @endif
                                                    </div>
                                                @empty
                                                    <span class="text-gray-400">No field changes recorded</span>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="px-4 py-12 text-center text-sm text-gray-500">
                                        No audit entries match these filters.
                                    </div>
                                @endforelse
                            </div>

                            {{-- Desktop Table (Visible md and up) --}}
                            <div class="hidden md:block overflow-x-auto">
                                <table class="w-full text-left text-sm text-gray-600">
                                    <thead class="bg-gray-50 text-gray-500 text-xs font-semibold border-b border-gray-200">
                                        <tr>
                                            <th class="px-6 py-3 whitespace-nowrap">When</th>
                                            <th class="px-6 py-3">Who</th>
                                            <th class="px-6 py-3">Action</th>
                                            <th class="px-6 py-3">Record</th>
                                            <th class="px-6 py-3">Changes</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse($audits as $audit)
                                            @php
                                                $old = $audit->old_values ?? [];
                                                $new = $audit->new_values ?? [];
                                                $fields = array_values(array_unique(array_merge(array_keys($old), array_keys($new))));
                                                $fmt = function ($v, $field) use ($labels) {
                                                    if ($v === null || $v === '')
                                                        return '—';

                                                    if (isset($labels[$field])) {
                                                        $name = $labels[$field]->get($v);
                                                        return $name
                                                            ? \Illuminate\Support\Str::limit($name, 60) . " (#{$v})"
                                                            : "#{$v} (record no longer exists)";
                                                    }

                                                    return \Illuminate\Support\Str::limit(is_array($v) ? json_encode($v) : (string) $v, 80);
                                                };
                                                $badge = match ($audit->event) {
                                                    'created' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                                    'updated' => 'bg-amber-50 text-amber-800 border-amber-200',
                                                    'deleted' => 'bg-red-50 text-red-700 border-red-200',
                                                    'restored' => 'bg-gray-100 text-gray-800 border-gray-200',
                                                    default => 'bg-gray-100 text-gray-700 border-gray-200',
                                                };
                                            @endphp
                                            <tr class="align-top hover:bg-gray-50/70 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <p class="font-medium text-gray-800">{{ $audit->created_at->format('M d, Y') }}
                                                    </p>
                                                    <p class="text-xs text-gray-500">{{ $audit->created_at->format('h:i A') }}</p>
                                                </td>

                                                <td class="px-6 py-4">
                                                    <p class="font-medium text-gray-800">{{ $audit->user->name ?? 'System' }}</p>
                                                    @if($audit->ip_address)
                                                        <p class="text-xs text-gray-400">{{ $audit->ip_address }}</p>
                                                    @endif
                                                </td>

                                                <td class="px-6 py-4">
                                                    <span
                                                        class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold border {{ $badge }}">
                                                        {{ ucfirst($audit->event) }}
                                                    </span>
                                                </td>

                                                <td class="px-6 py-4">
                                                    <p class="font-medium text-gray-800">
                                                        {{ class_basename($audit->auditable_type) }} #{{ $audit->auditable_id }}</p>
                                                    @if($audit->auditable instanceof \App\Models\Violation)
                                                        <p class="text-xs text-gray-500">
                                                            {{ $audit->auditable->student->name ?? 'Unknown student' }}
                                                            &middot; {{ $audit->auditable->offense->name ?? 'Unknown offense' }}
                                                        </p>
                                                    @elseif($audit->auditable instanceof \App\Models\User)
                                                        <p class="text-xs text-gray-500">{{ $audit->auditable->name }}</p>
                                                    @endif
                                                </td>

                                                <td class="px-6 py-4 text-xs max-w-md">
                                                    @forelse($fields as $field)
                                                        <div class="mb-1 break-words">
                                                            <span
                                                                class="font-semibold text-gray-700">{{ \Illuminate\Support\Str::headline(\Illuminate\Support\Str::replaceLast('_id', '', $field)) }}::</span>
                                                            @if($audit->event === 'updated')
                                                                <span class="text-gray-500">{{ $fmt($old[$field] ?? null, $field) }}</span>
                                                                <span class="text-gray-400">&rarr;</span>
                                                                <span class="text-gray-900">{{ $fmt($new[$field] ?? null, $field) }}</span>
                                                            @elseif($audit->event === 'deleted')
                                                                <span class="text-gray-500">{{ $fmt($old[$field] ?? null, $field) }}</span>
                                                            @else
                                                                <span class="text-gray-900">{{ $fmt($new[$field] ?? null, $field) }}</span>
                                                            @endif
                                                        </div>
                                                    @empty
                                                        <span class="text-gray-400">No field changes recorded</span>
                                                    @endforelse
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                                    No audit entries match these filters.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if($audits->hasPages())
                                <div class="px-4 sm:px-6 py-4 border-t border-gray-100 overflow-x-auto">
                                    {{ $audits->links() }}
                                </div>
                            @endif
                        </div>
                    @endif

                </div>

            </main>
        </div>
    </div>
</x-app-layout>