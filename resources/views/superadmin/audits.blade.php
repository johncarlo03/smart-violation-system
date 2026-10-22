<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex">

        @include('layouts.superadmin-nav')

        <div class="flex-1 flex flex-col min-w-0 overflow-y-auto max-h-screen">
            <main class="p-8 lg:p-10">

                <div class="mb-8">
                    <h2 class="text-3xl font-black text-gray-800 tracking-tight">Audit Log</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        A read-only record of who changed what, and when.
                    </p>
                </div>

                {{-- Filters --}}
                <form method="GET" class="bg-white rounded-2xl shadow-lg border border-gray-100 p-5 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 items-end">

                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">User</label>
                            <select name="user" class="w-full rounded-xl border-gray-200 text-sm">
                                <option value="">All users</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" @selected(request('user') == $u->id)>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">Action</label>
                            <select name="event" class="w-full rounded-xl border-gray-200 text-sm">
                                <option value="">All actions</option>
                                @foreach($events as $e)
                                    <option value="{{ $e }}" @selected(request('event') === $e)>{{ ucfirst($e) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">Record</label>
                            <select name="type" class="w-full rounded-xl border-gray-200 text-sm">
                                <option value="">All records</option>
                                @foreach($types as $t)
                                    <option value="{{ $t }}" @selected(request('type') === $t)>{{ class_basename($t) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">From</label>
                            <input type="date" name="from" value="{{ request('from') }}"
                                class="w-full rounded-xl border-gray-200 text-sm">
                        </div>

                        <div>
                            <label
                                class="block text-xs font-black uppercase tracking-[0.15em] text-gray-400 mb-2">To</label>
                            <input type="date" name="to" value="{{ request('to') }}"
                                class="w-full rounded-xl border-gray-200 text-sm">
                        </div>

                        <div class="flex gap-2">
                            <x-primary-button type="submit">Filter</x-primary-button>
                            <a href="{{ url()->current() }}"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold uppercase tracking-widest hover:bg-gray-200 flex items-center">
                                Reset
                            </a>
                        </div>
                    </div>
                    @error('from')
                    <p class="text-xs font-bold text-red-500 mt-2">{{ $message }}</p> @enderror
                    @error('to')
                    <p class="text-xs font-bold text-red-500 mt-2">{{ $message }}</p> @enderror
                </form>

                {{-- Log --}}
                <div class="w-full bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div
                        class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100/50 flex justify-between items-center">
                        <h3 class="text-lg font-black text-gray-800">Activity</h3>
                        <span class="text-xs font-semibold text-gray-500">{{ $audits->total() }}
                            {{ \Illuminate\Support\Str::plural('entry', $audits->total()) }}</span>
                    </div>

                    <div class="overflow-x-auto">
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
                                            'created' => 'bg-green-100 text-green-700',
                                            'updated' => 'bg-indigo-100 text-indigo-700',
                                            'deleted' => 'bg-red-100 text-red-700',
                                            'restored' => 'bg-amber-100 text-amber-700',
                                            default => 'bg-gray-100 text-gray-700',
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
                                                class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $badge }}">
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
                        <div class="px-6 py-4 border-t border-gray-100">
                            {{ $audits->links() }}
                        </div>
                    @endif
                </div>

            </main>
        </div>
    </div>
</x-app-layout>