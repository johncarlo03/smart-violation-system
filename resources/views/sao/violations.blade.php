<x-app-layout>
    @if (auth()->user()->role != 3)
        <x-slot name="header">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h2 class="text-xl font-bold text-gray-800">Violation Management</h2>

                <a href="{{ route('cso.dashboard') }}"
                    class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Violation Report
                </a>
            </div>
        </x-slot>
    @endif

    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if (auth()->user()->role != 3)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-8">
                    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">Total Records</div>
                                <div class="text-3xl sm:text-4xl font-black text-gray-800 mt-2">{{ $totalViolations }}</div>
                            </div>
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-indigo-100 flex items-center justify-center">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Pending Review --}}
                    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">Pending Review</div>
                                <div class="text-3xl sm:text-4xl font-black text-orange-500 mt-2">{{ $pendingReview }}</div>
                            </div>
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-orange-100 flex items-center justify-center">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Major Incidents --}}
                    <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">Major Incidents</div>
                                <div class="text-3xl sm:text-4xl font-black text-red-600 mt-2">{{ $majorIncidents }}</div>
                            </div>
                            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-red-100 flex items-center justify-center">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Recent Violation Queue</h3>

                    <div class="grid grid-cols-1 gap-4 md:hidden">
                        @forelse($violations as $v)
                        <p>Total: {{ $violations->total() }} | Current page count: {{ $violations->count() }}</p>
                            <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-sm hover:shadow-md transition">
                                <div class="flex items-center gap-3 mb-4">
                                    @if($v->student->profile_photo)
                                        <img src="{{ asset('storage/' . $v->student->profile_photo) }}" class="w-10 h-10 rounded-full object-cover shadow-sm">
                                    @else
                                        <div class="w-10 h-10 rounded-full {{ $v->department->badgeColor ?? 'bg-gray-100' }} flex items-center justify-center font-black text-sm shadow-sm">
                                            {{ strtoupper(substr($v->student->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <div class="font-bold text-gray-900 text-sm leading-tight">{{ $v->student->name }}</div>
                                        <div class="text-[10px] text-gray-400 uppercase font-semibold mt-0.5">
                                            {{ $v->department->acronym ?? 'N/A' }} • {{ $v->student->id_number }}
                                        </div>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-[9px] font-black uppercase border {{ $v->status === 'pending' ? 'bg-orange-100 text-orange-700 border-orange-200' : 'bg-green-100 text-green-700 border-green-200' }}">
                                        {{ $v->status }}
                                    </span>
                                </div>

                                <div class="bg-gray-50 rounded-xl p-3 mb-4">
                                    <div class="text-xs font-bold text-gray-800">{{ $v->offense->name ?? 'N/A' }}</div>
                                    <div class="text-[9px] text-gray-500 mt-1 uppercase tracking-widest font-bold">{{ $v->offense->category ?? '' }}</div>
                                </div>

                                <div class="flex items-center justify-between pt-2 border-t border-gray-50">
                                    <div class="text-[10px] text-gray-400">Reported By: <span class="text-gray-600 font-medium">{{ $v->cso->name }}</span></div>
                                    <div class="flex gap-2">
                                        @if($v->evidence_image)
                                            <button onclick="openEvidenceModal('{{ $v->evidence_image }}')" class="p-2 rounded-lg bg-blue-50 text-blue-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </button>
                                        @endif
                                        <a href="#" class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg shadow-sm">Manage</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 text-gray-400">No records found.</div>
                        @endforelse
                    </div>

                    {{-- DESKTOP VIEW: Table (Visible only on >= 768px) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b text-gray-500 text-[11px] uppercase tracking-[0.15em]">
                                    <th class="px-6 py-4 font-black">Student</th>
                                    <th class="px-6 py-4 font-black">Offense</th>
                                    <th class="px-6 py-4 font-black">Reported By</th>
                                    <th class="px-6 py-4 font-black text-center">Status</th>
                                    <th class="px-6 py-4 font-black text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($violations as $v)
                                    <tr class="hover:bg-indigo-50/40 transition duration-200">
                                        <td class="px-6 py-5">
                                            <div class="flex items-center gap-4">
                                                @if($v->student->profile_photo)
                                                    <img src="{{ asset('storage/' . $v->student->profile_photo) }}" class="w-12 h-12 rounded-full object-cover shadow-sm border border-gray-200">
                                                @else
                                                    <div class="w-12 h-12 rounded-full {{ $v->department->badgeColor ?? 'bg-gray-100' }} flex items-center justify-center font-black text-lg shadow-sm">
                                                        {{ strtoupper(substr($v->student->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-bold text-gray-900 leading-tight">{{ $v->student->name }}</div>
                                                    <div class="text-xs text-gray-500 mt-1 uppercase font-semibold">
                                                        {{ $v->department->acronym ?? 'N/A' }} • {{ $v->student->id_number }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-5">
                                            <div class="font-bold text-gray-800 leading-snug">{{ $v->offense->name ?? 'N/A' }}</div>
                                            <div class="inline-flex mt-2 px-2 py-1 rounded-full bg-gray-100 text-gray-600 text-[10px] uppercase font-black tracking-wider">
                                                {{ $v->offense->category ?? '' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-5 text-sm text-gray-600 font-medium">
                                            {{ $v->cso->name }}
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black tracking-wider uppercase border
                                                {{ $v->status === 'pending' ? 'bg-orange-100 text-orange-700 border-orange-200' : 'bg-green-100 text-green-700 border-green-200' }}">
                                                {{ $v->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-right">
                                            <div class="flex justify-end gap-2">
                                                @if($v->evidence_image)
                                                    <button onclick="openEvidenceModal('{{ $v->evidence_image }}')" class="inline-flex items-center px-3 py-2 rounded-xl bg-blue-100 text-blue-700 text-xs font-bold hover:bg-blue-200 transition">View Photo</button>
                                                @endif
                                                <a href="#" class="inline-flex items-center px-3 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 shadow-sm transition">Manage</a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
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