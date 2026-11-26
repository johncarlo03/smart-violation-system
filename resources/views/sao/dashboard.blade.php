<x-app-layout>
    {{-- <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="text-xl font-bold text-gray-800">Dashboard</h2>

            <a href="{{ route('cso.dashboard') }}"
                class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Violation Report
            </a>
        </div>
    </x-slot> --}}

<!-- Real-time Violation Notification -->
<div
    x-data="{
        open: false,
        message: '',
        student: '',
        offense: '',
        action: '',
        timer: null
    }"
    x-on:show-violation-toast.window="
        message = $event.detail.message;
        student = $event.detail.student;
        offense = $event.detail.offense;
        action = $event.detail.action;
        open = true;
        clearTimeout(timer);
        timer = setTimeout(() => open = false, 5000);
    "
    x-show="open"
    x-transition
    x-cloak
    class="fixed top-5 right-5 z-[100] w-full max-w-sm"
>
    <div class="bg-white rounded-2xl shadow-2xl border border-indigo-100 p-5">
        <div class="flex items-start gap-3">

            <div class="w-11 h-11 rounded-xl bg-indigo-100 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-indigo-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>

            <div class="flex-1 min-w-0">
                <div class="font-black text-gray-800">
                    {{ __('Violation Notification') }}
                </div>

                <p class="text-sm text-gray-600 mt-1" x-text="message"></p>

                <div class="mt-3 text-xs text-gray-500 space-y-1">
                    <p>
                        <span class="font-bold">Student:</span>
                        <span x-text="student"></span>
                    </p>
                    <p>
                        <span class="font-bold">Offense:</span>
                        <span x-text="offense"></span>
                    </p>
                </div>

                <div class="text-[10px] text-indigo-600 font-bold uppercase mt-3"
                    x-text="action">
                </div>
            </div>

            <button
                type="button"
                x-on:click="open = false"
                class="text-gray-400 hover:text-gray-700"
                aria-label="Close notification">
                &times;
            </button>
        </div>
    </div>
</div>

    <div class="py-10 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:violation-management />
        </div>
    </div>
    <div id="evidenceModal"
        class="fixed inset-0 z-[70] hidden bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">

        <div
            class="bg-white rounded-3xl shadow-[0_20px_60px_rgba(0,0,0,0.25)] max-w-3xl w-full overflow-hidden border border-gray-100">

            <div class="flex justify-between items-center p-5 border-b border-gray-100">
                <div>
                    <h3 class="text-xl font-black text-gray-800">
                        Violation Evidence
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Attached proof submitted with the report.
                    </p>
                </div>

                <button onclick="closeEvidenceModal()"
                    class="w-10 h-10 rounded-full hover:bg-gray-100 flex items-center justify-center transition">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="p-4 bg-gray-100">
                <img id="evidence_preview_img" src=""
                    class="w-full h-auto max-h-[70vh] object-contain rounded-2xl shadow-inner">
            </div>

            <div class="p-5 bg-white text-center border-t border-gray-100">
                <button onclick="closeEvidenceModal()"
                    class="px-6 py-3 bg-indigo-600 text-white rounded-2xl hover:bg-indigo-700 font-bold transition shadow-md">
                    Close Preview
                </button>
            </div>

        </div>

    </div>
</x-app-layout>

<script>
    function openEvidenceModal(imagePath) {
        const modal = document.getElementById('evidenceModal');
        const img = document.getElementById('evidence_preview_img');

        // Set the source to the standard storage path
        img.src = '/storage/' + imagePath;

        // Show the modal
        modal.classList.remove('hidden');

        // Prevent background scrolling
        document.body.style.overflow = 'hidden';
    }

    function closeEvidenceModal() {
        const modal = document.getElementById('evidenceModal');
        modal.classList.add('hidden');

        // Restore scrolling
        document.body.style.overflow = 'auto';
    }

    // Close modal if user clicks outside the white box
    window.onclick = function (event) {
        const modal = document.getElementById('evidenceModal');
        if (event.target == modal) {
            closeEvidenceModal();
        }
    }
</script>