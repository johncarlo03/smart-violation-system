<x-app-layout>
    <div class="py-6 sm:py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
                <div class="h-1 w-full bg-gradient-to-r from-red-700 to-amber-500"></div>
                <div class="p-3 sm:p-6 min-w-0">
                    <livewire:violation-management />
                </div>
            </div>
        </div>
    </div>

    <div id="evidenceModal"
        class="fixed inset-0 z-[70] hidden bg-gray-900/65 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">

        <div
            class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto border border-gray-200/80">
            <div class="h-1 w-full bg-gradient-to-r from-red-700 to-amber-500"></div>

            <div class="flex justify-between items-start sm:items-center gap-3 p-4 sm:p-5 border-b border-gray-100">
                <div class="min-w-0">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 truncate">
                        Violation Evidence
                    </h3>

                    <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                        Attached proof submitted with the report.
                    </p>
                </div>

                <button type="button" onclick="closeEvidenceModal()"
                    class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl hover:bg-gray-100 text-gray-500 hover:text-gray-800 flex items-center justify-center transition shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="p-3 sm:p-4 bg-gray-50">
                <img id="evidence_preview_img" src="" alt="Violation Evidence"
                    class="w-full h-auto max-h-[65vh] sm:max-h-[70vh] object-contain rounded-xl mx-auto">
            </div>

            <div class="p-4 sm:p-5 bg-white text-center border-t border-gray-100">
                <button type="button" onclick="closeEvidenceModal()"
                    class="w-full sm:w-auto px-6 py-2.5 sm:py-3 bg-[#1f2937] hover:bg-gray-800 text-white text-sm font-semibold rounded-xl transition shadow-xs">
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

        img.src = '/storage/' + imagePath;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEvidenceModal() {
        const modal = document.getElementById('evidenceModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.getElementById('evidenceModal').addEventListener('click', function (event) {
        if (event.target === this) {
            closeEvidenceModal();
        }
    });
</script>