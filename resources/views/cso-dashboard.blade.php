<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<x-app-layout>

    <div id="violationModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

            <div
                class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Select Violation Type</h3>

                    <div class="mt-4 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <button type="button" onclick="showOffenses('Academic')"
                                class="p-4 border rounded hover:bg-blue-50 text-left">
                                <span class="font-bold block">Academic Violations</span>
                                <span class="text-xs text-gray-500">e.g., Cheating, Plagiarism</span>
                            </button>
                            <button type="button" onclick="showOffenses('Non-Academic')"
                                class="p-4 border rounded hover:bg-green-50 text-left">
                                <span class="font-bold block">Non-Academic Offenses</span>
                                <span class="text-xs text-gray-500">e.g., Dress code, ID violations</span>
                            </button>
                            <button type="button" onclick="showOffenses('Serious')"
                                class="p-4 border rounded hover:bg-orange-50 text-left">
                                <span class="font-bold block">Serious Offenses</span>
                                <span class="text-xs text-gray-500">e.g., Vandalism, Smoking</span>
                            </button>
                            <button type="button" onclick="showOffenses('Very Serious')"
                                class="p-4 border rounded hover:bg-red-50 text-left">
                                <span class="font-bold block">Very Serious Offenses</span>
                                <span class="text-xs text-gray-500">e.g., Drugs, Theft, Violence</span>
                            </button>
                        </div>

                        <div id="offenseList" class="hidden mt-6 border-t pt-4">
                            <h4 id="categoryTitle" class="font-bold text-gray-700 mb-2"></h4>
                            <select id="specificOffense" class="block w-full border-gray-300 rounded-md shadow-sm">
                            </select>
                            <x-primary-button type="button" onclick="confirmViolation()" class="mt-4">Confirm
                                Selection</x-primary-button>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeModal()"
                        class="w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::user()->name }}'s Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-bold mb-4">Report New Violation</h3>

                    {{-- <form method="POST" action="{{ route('violations.store') }}" enctype="multipart/form-data">
                        --}}
                        @csrf
                        <div class="mb-4">
                            <x-input-label for="rfid_input" value="Scan RFID or Search Student" />
                            {{-- <input type="text" id="rfid_listener" class="opacity-0 absolute" autofocus
                                autocomplete="off"> --}}

                            <select id="student_id" name="student_id" placeholder="Search student...">
                                <option value="">-- Select or Scan --</option>
                                @foreach(\App\Models\User::where('role', 1)->get() as $student)

                                    @php
                                        // Map Department IDs to Tailwind colors
                                        $colors = [
                                            1 => 'bg-red-100 text-red-700 border-red-200',   // COE
                                            2 => 'bg-blue-100 text-blue-700 border-blue-200', // CEAS
                                            3 => 'bg-green-100 text-green-700 border-green-200',  // CME
                                            4 => 'bg-yellow-100 text-yellow-700 border-yellow-200',    // COT
                                        ];
                                        $badgeColor = $colors[$student->department_id] ?? 'bg-gray-100 text-gray-700 border-gray-200';
                                    @endphp

                                    <option value="{{ $student->id }}" data-rfid="{{ $student->rfid_number }}"
                                        data-id_number="{{ $student->id_number }}" data-course="{{ $student->course }}"
                                        data-year_level="{{ $student->year_level }}" data-badge_color="{{ $badgeColor }}">
                                        {{ $student->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="type_display" value="Violation Category & Type" />
                            <div class="flex gap-2">
                                <x-text-input id="type_display" class="block mt-1 w-full bg-gray-100" readonly
                                    placeholder="Click 'Select' to browse manual..." required />
                                <input type="hidden" name="type" id="type_hidden"> <x-secondary-button type="button"
                                    onclick="openModal()" class="mt-1">Select</x-secondary-button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" value="Details" />
                            <textarea name="description" id="description"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                rows="3" required></textarea>
                        </div>

                        <div class="mb-4">
                            <x-input-label for="evidence_image" value="Upload Evidence Photo (Optional)" />
                            <input type="file" name="evidence_image" id="evidence_image" class="block mt-1 w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100" accept="image/*">
                            <p class="mt-1 text-xs text-gray-500">Formats: JPG, PNG. Max 2MB.</p>
                        </div>



                        <div class="flex items-center gap-4">
                            <x-primary-button>Submit Report</x-primary-button>

                            @if (session('status'))
                                <p class="text-sm text-green-600">{{ session('status') }}</p>
                            @endif
                        </div>
                        {{--
                    </form> --}}
                </div>
            </div>
        </div>
    </div>


    <script>

        const offenses = {
            'Academic': ['Plagiarism', 'False Authorship/Contract Cheating', 'Collusion', 'Falsifying Data/Evidence', 'Exam Proxy', 'Grade Tampering', 'Exam Collusion', 'Test Leaking', 'Program Non-Attendance'],
            'Non-Academic': ['ID Policy Non-compliance', 'Improper Uniform/Haircut', 'Unauthorized Gadget Use', 'Class/Activity Disturbance', 'Damage/Misuse of School Property', 'Unauthorized After-Hours Stay', 'Speeding/Excessive Vehicle Noise', 'Unruly Behavior on Campus', 'Use of Vulgar/Profane Language', 'Possession of Gambling Tools', 'Disrespect towards Personnel/Visitors', 'Disobedience to Lawful Orders'],
            'Serious': ['Smoking on Campus', 'Vandalism', 'Gambling', 'Bullying'],
            'Very Serious': ['Possession of Illegal Drugs', 'Theft', 'Physical Assault', 'Carrying Deadly Weapons']
        };

        function openModal() {
            document.getElementById('violationModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('violationModal').classList.add('hidden');
        }

        function showOffenses(category) {
            const list = document.getElementById('offenseList');
            const select = document.getElementById('specificOffense');
            const title = document.getElementById('categoryTitle');

            list.classList.remove('hidden');
            title.innerText = category;

            // Clear and fill the select
            select.innerHTML = '';
            offenses[category].forEach(offense => {
                let opt = document.createElement('option');
                opt.value = `${category}: ${offense}`;
                opt.innerText = offense;
                select.appendChild(opt);
            });
        }

        function confirmViolation() {
            const selectedValue = document.getElementById('specificOffense').value;
            document.getElementById('type_display').value = selectedValue;
            document.getElementById('type_hidden').value = selectedValue;
            closeModal();
        }

        new TomSelect("#student_id", {
            valueField: "id",
            labelField: "name",
            searchField: ["name", "id_number", "rfid"],
            maxOptions: 10,

            load: function (query, callback) {
                if (!query.length) return callback();

                fetch(`/students/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => callback(data))
                    .catch(() => callback());
            },

            render: {
                option: function (item, escape) {
                    const name = item.name ? escape(item.name) : 'Unknown Student';
                    const rfid = item.rfid ? escape(item.rfid) : 'No RFID';
                    const idNum = item.id_number ? escape(item.id_number) : 'No ID';
                    const course = item.course ? escape(item.course) : 'No Course';
                    const year_level = item.year_level ? escape(item.year_level) : 'No Year Level';

                    const badgeColor = item.badge_color || 'bg-gray-100 text-gray-700';
                    
                    return `<div class="py-2 px-3 border-b border-gray-50 flex items-center justify-between">
                    <div>
                        <span class="font-semibold text-gray-900">${escape(item.name)}</span>
                        <div class="text-xs text-gray-400">ID: ${escape(item.id_number || 'N/A')}</div>
                    </div>
                    
                    <span class="text-[10px] uppercase font-bold px-2 py-1 rounded border ${badgeColor}">
                        ${escape(item.course)} ${escape(item.year_level)}
                    </span>
                </div>`;
                },
                item: function (item, escape) {
                    const badgeColor = item.badge_color || 'bg-gray-100 text-gray-700';
                    // This is what shows in the bar AFTER you select someone
                    return `<div>${escape(item.name)} <span class="ml-1 text-[12px] uppercase font-bold px-2 py-1 rounded border ${badgeColor}">
                        ${escape(item.course)} ${escape(item.year_level)}
                    </span></div>`;
                }
            }
        });
    </script>
</x-app-layout>