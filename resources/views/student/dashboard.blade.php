<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Header -->
            <div class="bg-orange-800 rounded-2xl p-6 text-white shadow-lg flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold">Welcome back, {{ Auth::user()->name }}!</h3>
                    <p class="text-orange-200 text-sm mt-1">Check your active conduct records, penalties, and SAO
                        clearance status below.</p>
                </div>
                <div class="hidden sm:block">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-900 text-orange-100 border border-orange-700">
                        Student ID: {{ Auth::user()->id_number ?? Auth::id() }}
                    </span>
                </div>
            </div>

            <!-- Overview Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Violations -->
                <div
                    class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Records</p>
                        <p class="text-2xl font-extrabold text-gray-900 mt-1">{{ $totalViolationsCount ?? 0 }}</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-orange-50 text-orange-800 flex items-center justify-center font-bold">
                        📋
                    </div>
                </div>

                <!-- Pending Penalties -->
                <div
                    class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pending Penalties</p>
                        <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $pendingViolationsCount ?? 0 }}</p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold">
                        ⏳
                    </div>
                </div>

                <!-- Clearance Status -->
                <div
                    class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">SAO Clearance Status</p>
                        <p class="text-sm font-bold mt-1">
                            @if(($pendingViolationsCount ?? 0) === 0)
                                <span class="text-emerald-600 flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Cleared
                                </span>
                            @else
                                <span class="text-rose-600 flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span> Action Required
                                </span>
                            @endif
                        </p>
                    </div>
                    <div
                        class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                        🛡️
                    </div>
                </div>
            </div>

            <!-- Violations Table -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <div>
                        <h4 class="font-bold text-gray-800">Violation Records & Assigned Penalties</h4>
                        <p class="text-xs text-gray-500">View detailed information regarding reported offenses and
                            required actions.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead
                            class="bg-gray-100/70 text-gray-700 uppercase text-[11px] font-bold tracking-wider border-b border-gray-200">
                            <tr>
                                <th class="px-5 py-3">Offense Name</th>
                                <th class="px-5 py-3">Offense Level</th>
                                <th class="px-5 py-3">Required Action / Penalty</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Date Reported</th>
                                <th class="px-5 py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($studentViolations as $violation)
                                <tr class="hover:bg-gray-50/80 transition">
                                    <td class="px-5 py-4 font-semibold text-gray-900">
                                        {{ $violation->offense?->name ?? 'Unspecified Offense' }}
                                    </td>
                                    <td class="px-5 py-4">
                                        @php
                                            $level = $violation->offense_number;
                                            $suffix = match ((int) $level) {
                                                1 => '1st',
                                                2 => '2nd',
                                                3 => '3rd',
                                                default => $level . 'th',
                                            };
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            {{ $suffix }} Offense
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 text-gray-700">
                                        {{ $violation->penalty?->penalty_description ?? 'No explicit description' }}
                                    </td>
                                    <td class="px-5 py-4">
                                        @if(strtolower($violation->status) === 'pending')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                Pending
                                            </span>
                                        @elseif(strtolower($violation->status) === 'resolved')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Resolved
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                                                {{ ucfirst($violation->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-xs text-gray-500">
                                        {{ $violation->created_at ? $violation->created_at->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-5 py-4 text-xs">
                                        @php
                                            $pendingAppeal = $violation->appeals
                                                ->where('status', 'pending')
                                                ->first();
                                            $latestAppeal = $violation->appeals
                                                ->sortByDesc('created_at')
                                                ->first();
                                        @endphp
                                        @if($pendingAppeal)
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-amber-100 text-amber-800 font-semibold">
                                                    Appeal Pending
                                                    </span>
                                        @elseif($latestAppeal && $latestAppeal->status === 'approved')
                                                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg
                                               bg-green-100 text-green-800 font-semibold">
                                                                    Appeal Approved
                                                                </span>
                                        @elseif($latestAppeal && $latestAppeal->status === 'denied')
                                                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg
                                               bg-red-100 text-red-800 font-semibold">
                                                                    Appeal Denied
                                                                </span>
                                        @elseif($violation->status === 'pending')
                                                                <button type="button" onclick="openAppealModal({{ $violation->id }})" class="inline-flex items-center px-3 py-1.5
                                               rounded-lg bg-orange-800 text-white
                                               font-semibold hover:bg-orange-900 transition">
                                                                    Appeal
                                                                </button>
                                        @else
                                            <span class="text-gray-400">
                                                Not Available
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-gray-500">
                                        <p class="font-medium">No violation records found.</p>
                                        <p class="text-xs text-gray-400 mt-1">You currently have a clean record with the
                                            Student Affairs Office.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Floating SAO Assistant Component -->
            <div x-data="{
                open: false,
                loading: false,
                messages: [
                    {text: 'Hi! I am the CTU-SAO Assistant. How can I help you today?', bot: true}
                ],
                formatText(text) {
                    if (!text) return '';
                    return text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                },
                sendMessage() {
                    let userMsg = this.$refs.input.value;
                    if (!userMsg.trim() || this.loading) return;

                    this.messages.push({text: userMsg, bot: false});
                    this.$refs.input.value = '';
                    this.loading = true;

                    fetch(`/chatbot/ask?query=${encodeURIComponent(userMsg)}`)
                        .then(res => res.json())
                        .then(data => {
                            this.messages.push({text: data.reply ?? 'No response', bot: true});
                        })
                        .catch(() => {
                            this.messages.push({text: 'Connection error. Please try again.', bot: true});
                        })
                        .finally(() => {
                            this.loading = false;
                            setTimeout(() => {
                                const box = document.getElementById('chat-box');
                                if (box) box.scrollTop = box.scrollHeight;
                            }, 50);
                        });
                }
            }" class="fixed bottom-6 right-6 z-50">

                <!-- Chat Window -->
                <div x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-4"
                    class="bg-white shadow-2xl rounded-2xl w-80 mb-4 border border-gray-200 overflow-hidden">

                    <!-- Header -->
                    <div class="bg-orange-800 px-4 py-3 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full overflow-hidden border border-orange-600">
                                <img src="{{ asset('images/sao_img.jpg') }}" alt="SAO"
                                    class="w-full h-auto object-cover">
                            </div>
                            <div>
                                <p class="text-white font-semibold text-sm leading-none">SAO Assistant</p>
                                <p class="text-orange-200 text-xs">CTU Student Affairs Office</p>
                            </div>
                        </div>
                        <button @click="open = false"
                            class="text-orange-200 hover:text-white transition text-lg leading-none">&times;</button>
                    </div>

                    <!-- Messages -->
                    <div class="h-72 p-3 overflow-y-auto bg-gray-50 space-y-3 text-sm" id="chat-box">
                        <template x-for="(msg, index) in messages" :key="index">
                            <div :class="msg.bot ? 'flex items-end gap-2' : 'flex items-end justify-end gap-2'">
                                <div x-show="msg.bot"
                                    class="w-6 h-6 rounded-full bg-orange-800 flex-shrink-0 flex items-center justify-center overflow-hidden">
                                    <img src="{{ asset('images/sao_img.jpg') }}" alt="SAO"
                                        class="w-full h-auto object-cover">
                                </div>
                                <div :class="msg.bot
                                    ? 'bg-white border border-gray-200 text-gray-800 rounded-2xl rounded-bl-sm [&_strong]:font-bold [&_b]:font-bold'
                                    : 'bg-orange-800 text-white rounded-2xl rounded-br-sm'"
                                    class="px-3 py-2 max-w-[85%] shadow-sm leading-relaxed"
                                    x-html="formatText(msg.text)">
                                </div>
                            </div>
                        </template>

                        <!-- Typing Indicator -->
                        <div x-show="loading" class="flex items-end gap-2">
                            <div
                                class="w-6 h-6 rounded-full bg-orange-800 flex-shrink-0 flex items-center justify-center text-white text-[9px] font-bold">
                                SAO
                            </div>
                            <div class="bg-white border border-gray-200 rounded-2xl rounded-bl-sm px-4 py-2 shadow-sm">
                                <div class="flex gap-1 items-center h-4">
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"
                                        style="animation-delay: 0ms"></span>
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"
                                        style="animation-delay: 150ms"></span>
                                    <span class="w-1.5 h-1.5 bg-gray-400 rounded-full animate-bounce"
                                        style="animation-delay: 300ms"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Input -->
                    <div class="p-2 border-t bg-white flex gap-2 items-center">
                        <input type="text" x-ref="input" x-on:keydown.enter.prevent="sendMessage()" :disabled="loading"
                            class="flex-grow border border-gray-200 rounded-full px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-700 disabled:bg-gray-100"
                            placeholder="Ask about policies...">
                        <button x-on:click="sendMessage()" :disabled="loading"
                            class="bg-orange-800 hover:bg-orange-900 disabled:opacity-50 text-white rounded-full w-8 h-8 flex items-center justify-center transition flex-shrink-0">
                            <svg class="w-4 h-4 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Toggle Button -->
                <button @click="open = !open"
                    class="bg-orange-800 text-white p-4 rounded-full shadow-lg hover:bg-orange-900 transition relative">
                    <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

<!-- Appeal Modal -->
<div
    x-data="{
        open: false,
        violationId: null
    }"
    x-show="open"
    x-cloak
    @open-appeal.window="
        violationId = $event.detail;
        open = true;
    "
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-[100] flex items-center justify-center p-4"
>

    <!-- Background -->
    <div
        class="absolute inset-0 bg-black/50"
        @click="open = false"
    ></div>

    <!-- Modal -->
    <div
        x-show="open"
        x-transition
        class="relative bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden"
    >

        <!-- Header -->
        <div class="bg-orange-800 px-6 py-4 text-white">

            <div class="flex items-center justify-between">

                <div>
                    <h3 class="text-lg font-bold">
                        Appeal Violation
                    </h3>

                    <p class="text-orange-200 text-sm mt-1">
                        Submit your request for SAO review.
                    </p>
                </div>

                <button
                    @click="open = false"
                    class="text-orange-200 hover:text-white text-2xl"
                >
                    &times;
                </button>

            </div>

        </div>
        <!-- Body -->
        <form
            method="POST"
            :action="`/student/violations/${violationId}/appeal`"
            enctype="multipart/form-data"
            class="p-6" >
            @csrf
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Reason for Appeal
                </label>
                <textarea
                    name="reason"
                    rows="5"
                    required
                    minlength="20"
                    maxlength="2000"
                    class="w-full rounded-xl border-gray-300
                           focus:border-orange-500
                           focus:ring-orange-500"
                    placeholder="Explain why you believe this violation should be reviewed..."
                ></textarea>
                <p class="text-xs text-gray-400 mt-1">
                    Please provide a clear and factual explanation.
                </p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Supporting Evidence
                    <span class="font-normal text-gray-400">
                        (Optional)
                    </span>
                </label>
                <input
                    type="file"
                    name="evidence_image"
                    accept=".jpg,.jpeg,.png"
                    class="block w-full text-sm text-gray-600
                           border border-gray-300 rounded-xl
                           p-2"
                >
                <p class="text-xs text-gray-400 mt-1">
                    JPG or PNG, maximum 2MB.
                </p>
            </div>
            <div class="bg-orange-50 border border-orange-100 rounded-xl p-4 mb-6">
                <p class="text-sm text-orange-900">
                    <strong>Before submitting:</strong>
                    Please provide accurate information and any supporting
                    evidence relevant to your appeal. The Student Affairs
                    Office will review your submission.
                </p>
            </div>
            <div class="flex justify-end gap-3">
                <button
                    type="button"
                    @click="open = false"
                    class="px-4 py-2.5 rounded-xl
                           bg-gray-100 text-gray-700
                           font-semibold hover:bg-gray-200"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-xl
                           bg-orange-800 text-white
                           font-semibold hover:bg-orange-900"
                >
                    Submit Appeal
                </button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>

<script>
    function openAppealModal(violationId) {
        window.dispatchEvent(
            new CustomEvent('open-appeal', {
                detail: violationId
            })
        );
    }
</script>