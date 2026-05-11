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
    ],
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
                    box.scrollTop = box.scrollHeight;
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

                                    <!-- Bot avatar -->
                                    <div x-show="msg.bot"
                                        class="w-6 h-6 rounded-full bg-orange-800 flex-shrink-0 flex items-center justify-center">
                                       <img src="{{ asset('images/sao_img.jpg') }}" alt="SAO"
                                        class="w-full h-auto object-cover">
                                    </div>

                                    <!-- Bubble -->
                                    <div :class="msg.bot
                            ? 'bg-white border border-gray-200 text-gray-800 rounded-2xl rounded-bl-sm'
                            : 'bg-orange-800 text-white rounded-2xl rounded-br-sm'"
                                        class="px-3 py-2 max-w-[85%] shadow-sm leading-relaxed" x-text="msg.text">
                                    </div>
                                </div>
                            </template>

                            <!-- Typing indicator -->
                            <div x-show="loading" class="flex items-end gap-2">
                                <div
                                    class="w-6 h-6 rounded-full bg-orange-800 flex-shrink-0 flex items-center justify-center text-white text-[9px] font-bold">
                                    SAO
                                </div>
                                <div
                                    class="bg-white border border-gray-200 rounded-2xl rounded-bl-sm px-4 py-2 shadow-sm">
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
                            <input type="text" x-ref="input" x-on:keydown.enter.prevent="sendMessage()"
                                :disabled="loading"
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
    </div>
</x-app-layout>