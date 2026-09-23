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