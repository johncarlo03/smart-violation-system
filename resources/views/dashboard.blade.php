<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
                <div x-data="{
                        open: false,
                        loading: false,
                        messages: [
                            {text: 'Hi! I am the CTU-SAO Assistant. How can I help you today?', bot: true}
                        ]
                    }"
                    class="fixed bottom-6 right-6 z-50">

                    <div x-show="open"
                        class="bg-white shadow-2xl rounded-xl w-80 mb-4 border border-gray-200 overflow-hidden">
                        <div class="bg-indigo-600 p-4 text-white font-bold flex justify-between">
                            <span>SAO Assistant</span>
                            <button @click="open = false">&times;</button>
                        </div>

                        <div class="h-64 p-4 overflow-y-auto bg-gray-50 space-y-2 text-sm" id="chat-box">
                            <template x-for="msg in messages">
                                <div :class="msg.bot ? 'text-left' : 'text-right'">
                                    <span
                                        :class="msg.bot ? 'bg-white border text-gray-800' : 'bg-indigo-500 text-white'"
                                        class="inline-block p-2 rounded-lg shadow-sm" x-text="msg.text"></span>
                                </div>
                            </template>
                        </div>

                        <div class="p-2 border-t flex">
                            <input type="text"
    x-ref="input"
    @keydown.enter.prevent="
        let userMsg = $refs.input.value;

        if (!userMsg.trim() || loading) return;

        messages.push({text: userMsg, bot: false});
        $refs.input.value = '';

        loading = true;

        fetch(`/chatbot/ask?query=${encodeURIComponent(userMsg)}`)
            .then(res => res.json())
            .then(data => {
                messages.push({text: data.reply ?? 'No response', bot: true});

                setTimeout(() => {
                    const box = document.getElementById('chat-box');
                    box.scrollTop = box.scrollHeight;
                }, 50);
            })
            .finally(() => loading = false);
    "
    class="flex-grow border-none focus:ring-0 text-sm"
    placeholder="Ask about policies..."
>
                        </div>
                    </div>

                    <button @click="open = !open"
                        class="bg-indigo-600 text-white p-4 rounded-full shadow-lg hover:bg-indigo-700 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                            </path>
                        </svg>
                    </button>
                </div>
                {{-- <div class="fixed bottom-4 right-4 w-80 bg-white shadow-xl rounded-xl border">

                    <div class="p-3 bg-indigo-600 text-white font-bold rounded-t-xl">
                        Student Assistant
                    </div>

                    <div id="chatMessages" class="h-80 overflow-y-auto p-3 text-sm"></div>

                    <div class="p-2 border-t flex">
                        <input id="chatInput" class="flex-1 border rounded px-2 py-1 text-sm"
                            placeholder="Ask something...">

                        <button onclick="sendMessage()" class="ml-2 bg-indigo-600 text-white px-3 rounded">
                            Send
                        </button>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</x-app-layout>
{{--
<script>
    async function sendMessage() {

        const input = document.getElementById('chatInput');
        const chat = document.getElementById('chatMessages');

        const message = input.value;
        if (!message) return;

        chat.innerHTML += `
        <div class="text-right mb-2">
            <span class="bg-blue-100 px-2 py-1 rounded">
                ${message}
            </span>
        </div>
    `;

        input.value = '';

        const response = await fetch('/chatbot', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message })
        });

        const data = await response.json();
        console.log(data);
        chat.innerHTML += `
        <div class="mb-2">
            <span class="bg-gray-100 px-2 py-1 rounded">
                ${data.reply}
            </span>
        </div>
    `;

        chat.scrollTop = chat.scrollHeight;
    }
</script> --}}