<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Student Affairs Office</h2>

            <a href="{{ route('cso.dashboard') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Violation Report
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-sm font-medium text-gray-500 uppercase">Total Records</div>
                    <div class="text-3xl font-bold">{{ $totalViolations }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-orange-400">
                    <div class="text-sm font-medium text-gray-500 uppercase">Pending Review</div>
                    <div class="text-3xl font-bold text-orange-500">{{ $pendingReview }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <div class="text-sm font-medium text-gray-500 uppercase">Major Incidents</div>
                    <div class="text-3xl font-bold text-red-600">{{ $majorIncidents }}</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Recent Violation Queue</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b text-gray-600 text-xs uppercase tracking-wider">
                                    <th class="px-4 py-3 font-semibold">Student</th>
                                    <th class="px-4 py-3 font-semibold">Offense</th>
                                    <th class="px-4 py-3 font-semibold">Officer (CSO)</th>
                                    <th class="px-4 py-3 font-semibold text-center">Status</th>
                                    <th class="px-4 py-3 font-semibold text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($violations as $v)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-4">
                                            <div class="font-bold text-gray-900">{{ $v->student->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $v->department->acronym ?? 'N/A' }} |
                                                {{ $v->student->id_number }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-700">
                                            <span class="block font-bold">{{ $v->offense->name ?? 'N/A' }}</span>

                                            <span class="text-[10px] text-gray-400 uppercase">{{ $v->offense->category ?? '' }}</span>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-600">
                                            {{ $v->cso->name }}
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span
                                                class="px-2 py-1 text-[10px] font-bold uppercase rounded-full 
                                                        {{ $v->status === 'pending' ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700' }}">
                                                {{ $v->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                @if($v->evidence_image)
                                                    <button onclick="openEvidenceModal('{{ $v->evidence_image }}')"
                                                        class="text-blue-600 hover:text-blue-800 text-xs font-bold uppercase tracking-tighter bg-blue-50 px-2 py-1 rounded">
                                                        View Photo
                                                    </button>
                                                @endif
                                                <a href="#"
                                                    class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded text-xs font-bold hover:bg-indigo-100 transition">
                                                    Manage
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-10 text-center text-gray-500">No violations recorded
                                            yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $violations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="evidenceModal"
        class="fixed inset-0 z-[70] hidden bg-gray-900 bg-opacity-75 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-bold text-gray-800">Violation Evidence</h3>
                <button onclick="closeEvidenceModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="p-2 bg-gray-100">
                <img id="evidence_preview_img" src=""
                    class="w-full h-auto max-h-[70vh] object-contain rounded shadow-inner">
            </div>
            <div class="p-4 bg-white text-center">
                <button onclick="closeEvidenceModal()"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-bold transition">
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