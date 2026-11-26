<x-app-layout>
    {{-- @if (auth()->user()->role != 3) --}}
        <x-slot name="header">
            <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 sm:gap-4 min-w-0">
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 truncate">Violation Management</h2>

                <a href="{{ route('cso.dashboard') }}"
                    class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition shrink-0">
                    <svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="truncate">New Violation Report</span>
                </a>
            </div>
        </x-slot>
    {{-- @endif --}}

    <div class="py-4 sm:py-12 overflow-x-clip">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 min-w-0">

            @if (auth()->user()->role != 2)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-6 mb-6 sm:mb-8">
                    <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-md border border-gray-100 min-w-0">
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-[10px] sm:text-xs font-black uppercase tracking-[0.15em] sm:tracking-[0.2em] text-gray-400 truncate">Total Records</div>
                                <div class="text-2xl sm:text-4xl font-black text-gray-800 mt-1.5 sm:mt-2">{{ $totalViolations }}</div>
                            </div>
                            <div
                                class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-indigo-100 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 sm:w-7 sm:h-7 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Pending Review --}}
                    <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-md border border-gray-100 min-w-0">
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-[10px] sm:text-xs font-black uppercase tracking-[0.15em] sm:tracking-[0.2em] text-gray-400 truncate">Pending Review
                                </div>
                                <div class="text-2xl sm:text-4xl font-black text-orange-500 mt-1.5 sm:mt-2">{{ $pendingReview }}</div>
                            </div>
                            <div
                                class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-orange-100 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 sm:w-7 sm:h-7 text-orange-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Major Incidents --}}
                    <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-md border border-gray-100 sm:col-span-2 md:col-span-1 min-w-0">
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-[10px] sm:text-xs font-black uppercase tracking-[0.15em] sm:tracking-[0.2em] text-gray-400 truncate">Major Incidents
                                </div>
                                <div class="text-2xl sm:text-4xl font-black text-red-600 mt-1.5 sm:mt-2">{{ $majorIncidents }}</div>
                            </div>
                            <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-2xl bg-red-100 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 sm:w-7 sm:h-7 text-red-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm rounded-lg min-w-0">
                <div class="p-3 sm:p-6 min-w-0">
                    <h3 class="text-base sm:text-lg font-bold text-gray-700 mb-3 sm:mb-4">Violation Queue</h3>

                    <div class="min-w-0">
                        <livewire:manage-table />  {{-- v4 syntax --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="evidenceModal"
    class="fixed inset-0 z-[70] hidden bg-gray-900 bg-opacity-75 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto my-auto">
        <div class="flex justify-between items-center gap-3 p-3.5 sm:p-4 border-b">
            <h3 class="text-base sm:text-lg font-bold text-gray-800 truncate">Violation Evidence</h3>
            <button type="button" onclick="closeEvidenceModal()" class="text-gray-400 hover:text-gray-600 p-1 shrink-0">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <div class="p-2 sm:p-3 bg-gray-100">
            <img id="evidence_preview_img" src=""
                class="w-full h-auto max-h-[60vh] sm:max-h-[70vh] object-contain rounded shadow-inner mx-auto">
        </div>
        <div class="p-3.5 sm:p-4 bg-white text-center">
            <button type="button" onclick="closeEvidenceModal()"
                class="w-full sm:w-auto px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-bold transition">
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