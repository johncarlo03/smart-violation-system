<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script type="text/javascript"
    src="https://cdn.jsdelivr.net/npm/browser-image-compression@2.0.2/dist/browser-image-compression.js"></script>

<style>
    .ts-dropdown {
        z-index: 9999 !important;
    }

    @media (max-width: 639px) {
        .ts-wrapper.single .ts-control,
        .ts-wrapper .ts-control input {
            font-size: 16px !important;
        }
    }
</style>

<x-app-layout>

    <div id="studentInfoModal"
        class="fixed inset-0 z-[60] hidden bg-gray-900/50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-[90vh] overflow-y-auto my-auto">
            <div class="p-4 sm:p-6 text-center">
                {{-- <div id="modal_alert_container" class="mb-4 hidden">
                    <div id="modal_alert_badge"
                        class="inline-block px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest bg-red-600 text-white animate-pulse">
                    </div>
                </div> --}}
                <div class="mb-3 sm:mb-4">
                    <img id="modal_student_photo" src="/images/default-avatar.png"
                        class="w-24 h-24 sm:w-32 sm:h-32 rounded-full mx-auto object-cover border-4 shadow-md">
                </div>

                <h3 id="modal_student_name" class="text-lg sm:text-xl font-bold text-gray-800 break-words"></h3>
                <p id="modal_student_details" class="text-xs sm:text-sm text-gray-500 mb-3 sm:mb-4 break-words"></p>

                <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 gap-2 text-left bg-gray-50 p-3 sm:p-4 rounded-lg text-xs sm:text-sm">
                    <div class="min-w-0"><span class="text-gray-400">ID:</span> <span id="modal_student_id"
                            class="font-semibold break-all"></span></div>
                    <div class="min-w-0"><span class="text-gray-400">Year:</span> <span id="modal_student_year"
                            class="font-semibold"></span></div>
                    <div class="min-w-0"><span class="text-gray-400">Course:</span> <span id="modal_student_course"
                            class="font-semibold text-indigo-600 break-words"></span></div>
                    <div class="min-w-0"><span class="text-gray-400">Department:</span> <span id="modal_student_department"
                            class="font-semibold text-indigo-600 break-words"></span></div>
                </div>

                <div class="mt-5 sm:mt-6 flex flex-col-reverse sm:flex-row gap-2 sm:gap-3">
                    <button type="button" onclick="closeInfoModal()"
                        class="w-full sm:flex-1 px-4 py-2.5 sm:py-2 bg-gray-200 text-gray-700 text-sm rounded-lg hover:bg-gray-300">Close</button>
                    <button type="button" onclick="closeInfoModal()"
                        class="w-full sm:flex-1 px-4 py-2.5 sm:py-2 bg-[#1f2937] text-white text-sm rounded-lg hover:bg-gray-700">Confirm
                        Identity</button>
                </div>
            </div>
        </div>
    </div>

    <div id="violationModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-full px-3 py-4 sm:p-6">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"
                onclick="closeModal()"></div>

            <div
                class="relative z-10 w-full max-w-2xl overflow-visible text-left transition-all transform bg-white rounded-xl sm:rounded-lg shadow-xl my-auto">
                <div class="px-4 pt-4 pb-4 bg-white rounded-t-xl sm:rounded-t-lg sm:p-6 sm:pb-4">
                    <h3 class="text-base sm:text-lg font-medium leading-6 text-gray-900" id="modal-title">Select Violation Type</h3>

                    <div class="mt-3 sm:mt-4 space-y-3 sm:space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-4">
                            @if(auth()->user()->role != 2)
                                <button type="button" onclick="showOffenses('Academic')"
                                    class="p-3 sm:p-4 border rounded-lg hover:bg-blue-50 text-left transition min-w-0">
                                    <span class="font-bold text-sm sm:text-base block">Academic Violations</span>
                                    <span class="text-xs text-gray-500">e.g., Cheating, Plagiarism</span>
                                </button>
                            @endif
                            <button type="button" onclick="showOffenses('Non-Academic')"
                                class="p-3 sm:p-4 border rounded-lg hover:bg-green-50 text-left transition min-w-0">
                                <span class="font-bold text-sm sm:text-base block">Non-Academic Offenses</span>
                                <span class="text-xs text-gray-500">e.g., Dress code, ID violations</span>
                            </button>
                            <button type="button" onclick="showOffenses('Serious')"
                                class="p-3 sm:p-4 border rounded-lg hover:bg-orange-50 text-left transition min-w-0">
                                <span class="font-bold text-sm sm:text-base block">Serious Offenses</span>
                                <span class="text-xs text-gray-500">e.g., Vandalism, Smoking</span>
                            </button>
                            <button type="button" onclick="showOffenses('Very Serious')"
                                class="p-3 sm:p-4 border rounded-lg hover:bg-red-50 text-left transition min-w-0">
                                <span class="font-bold text-sm sm:text-base block">Very Serious Offenses</span>
                                <span class="text-xs text-gray-500">e.g., Drugs, Theft, Violence</span>
                            </button>
                        </div>

                        <div id="offenseList" class="hidden mt-4 sm:mt-6 border-t pt-4">
                            <h4 id="categoryTitle" class="font-bold text-sm sm:text-base text-gray-700 mb-2"></h4>
                            <select id="specificOffense" class="block w-full border-gray-300 rounded-md shadow-sm text-base sm:text-sm">
                            </select>
                            <x-primary-button type="button" onclick="confirmViolation()" class="mt-4 w-full sm:w-auto justify-center">Confirm
                                Selection</x-primary-button>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 rounded-b-xl sm:rounded-b-lg sm:px-6 flex flex-col sm:flex-row-reverse gap-2">
                    <button type="button" onclick="closeModal()"
                        class="w-full px-4 py-2.5 sm:py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 sm:w-auto">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-4 min-w-0">
            @if(in_array(Auth::user()->role, [2]))
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight truncate">
                Campus Safety Officer's Dashboard
            </h2>
            @endif
            @if(in_array(Auth::user()->role, [5]))
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight truncate">
                Teaching Staff's Dashboard
            </h2>
            @endif
            @if(in_array(Auth::user()->role, [3]))
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight truncate">
                SAO Violation Report
            </h2>
            @endif
            @if(in_array(Auth::user()->role, [6]))
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight truncate">
                Chairman Violation Report
            </h2>
            @endif
            @if(in_array(Auth::user()->role, [7]))
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight truncate">
                Dean Violation Report
            </h2>
            @endif
            @if(auth()->user()->role != 2)
                <a href="{{ url()->previous(route('dashboard')) }}"
                    class="w-full sm:w-auto text-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 shrink-0">
                    Back
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6 sm:py-12 overflow-x-clip">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 min-w-0">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg min-w-0">
                <div class="p-4 sm:p-6 text-gray-900 min-w-0">

                    <h3 class="text-base sm:text-lg font-bold mb-4">Report New Violation</h3>

                    <form method="POST" action="{{ route('violations.store') }}" enctype="multipart/form-data" class="min-w-0">
                        @csrf
                        <div class="mb-4 min-w-0">
                            <x-input-label for="rfid_input" value="Scan RFID or Search Student" />
                            {{-- <input type="text" id="rfid_listener" class="opacity-0 absolute" autofocus
                                autocomplete="off"> --}}

                            <select id="student_id" name="student_id" placeholder="Search student..." class="w-full text-base sm:text-sm">
                                <option value="">-- Select or Scan --</option>
                            </select>
                        </div>

                        <div class="mb-4 min-w-0">
                            <x-input-label for="type_display" value="Violation Category & Type" />
                            <div class="flex flex-col sm:flex-row gap-2 min-w-0">
                                <x-text-input id="type_display" class="block mt-1 w-full bg-gray-100 text-base sm:text-sm min-w-0" readonly
                                    placeholder="Click 'Select' to browse manual..." required />
                                <input type="hidden" name="offense_id" id="type_hidden"> <x-secondary-button
                                    type="button" onclick="openModal()" class="mt-1 w-full sm:w-auto justify-center shrink-0">Select</x-secondary-button>
                            </div>
                        </div>

                        <div class="mb-4 min-w-0">
                            <x-input-label for="description" value="Details" />
                            <textarea name="description" id="description" required
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-base sm:text-sm"
                                rows="3"></textarea>
                        </div>

                        <div class="mb-4 min-w-0">
                            <x-input-label for="evidence_image" value="Upload Evidence Photo (Optional)" />
                            <input type="file" onchange="handleImageUpload(event)" name="evidence_image"
                                id="evidence_image" class="block mt-1 w-full text-xs sm:text-sm text-gray-500
                                    file:mr-3 sm:file:mr-4 file:py-2 file:px-3 sm:file:px-4
                                    file:rounded-full file:border-0
                                    file:text-xs sm:text-sm file:font-semibold
                                    file:bg-indigo-50 file:text-indigo-700
                                    hover:file:bg-indigo-100" accept="image/*">
                            <p class="mt-1 text-xs text-gray-500">Formats: JPG, PNG. Max 2MB.</p>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                            <x-primary-button class="w-full sm:w-auto justify-center">Submit Report</x-primary-button>
                            @if (session('status'))
                                <p class="text-sm text-green-600">{{ session('status') }}</p>
                            @endif
                            <x-input-error :messages="$errors->get('offense_id')" class="mt-2" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        async function handleImageUpload(event) {
            const imageFile = event.target.files[0];
            if (!imageFile) return;

          //  console.log('originalFile instanceof Blob', imageFile instanceof Blob);
          //  console.log(`originalFile size ${imageFile.size / 1024 / 1024} MB`);

            const submitBtn = document.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerText;
            submitBtn.disabled = true;
            submitBtn.innerText = 'Optimizing Image...';

            const options = {
                maxSizeMB: 0.8,
                maxWidthOrHeight: 1280,
                useWebWorker: true,
            }

            try {
                const compressedFile = await imageCompression(imageFile, options);
               // console.log(`compressedFile size ${compressedFile.size / 1024 / 1024} MB`);

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(new File([compressedFile], imageFile.name, {
                    type: imageFile.type,
                }));

                event.target.files = dataTransfer.files;

              //  alert('Image optimized for faster upload!');
            } catch (error) {
              //  console.log(error);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = originalBtnText;
            }
        }
        const offensesByGroup = @json(\App\Models\Offense::all()->groupBy('category'));

        function openModal() {
            document.getElementById('violationModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('violationModal').classList.add('hidden');
        }

        function showOffenses(category) {
            const list = document.getElementById('offenseList');
            const title = document.getElementById('categoryTitle');

            list.classList.remove('hidden');
            title.innerText = category;

            offenseSelect.clear();
            offenseSelect.clearOptions();

            const categoryOffenses = offensesByGroup[category] || [];

            categoryOffenses.forEach(offense => {
                offenseSelect.addOption({
                    value: offense.id,
                    text: offense.name
                });
            });

            offenseSelect.refreshOptions(false);
            offenseSelect.open();
        }

        function confirmViolation() {
            const selectedId = offenseSelect.getValue();

            if (!selectedId) {
           //     alert('Please select an offense.');
                return;
            }

            const selectedOption = offenseSelect.options[selectedId];
            const selectedName = selectedOption.text;
            const category = document.getElementById('categoryTitle').innerText;

            document.getElementById('type_display').value =
                `${category}: ${selectedName}`;

            document.getElementById('type_hidden').value = selectedId;

            closeModal();
        }

        function closeInfoModal() {
            document.getElementById('studentInfoModal').classList.add('hidden');
        }
        const offenseSelect = new TomSelect("#specificOffense", {
            dropdownParent: 'body',
            searchField: ["text"],
            maxOptions: 10,
            placeholder: "Search offense...",
            allowEmptyOption: true,
        });

        new TomSelect("#student_id", {
            valueField: "id",
            labelField: "name",
            searchField: ["name", "id_number", "rfid"],
            maxOptions: 10,

            onChange: function (value) {
                if (!value) return;

                const item = this.options[value];

                const data = item;
                const count = parseInt(data.violation_count) || 0;
                const studentDeptId = data.department_id;

                const studentPhoto = document.getElementById('modal_student_photo');

                studentPhoto.classList.remove('border-red-700', 'border-blue-800', 'border-green-800', 'border-yellow-800', 'border-indigo-500');
                const ringColors = {
                    1: 'border-red-700',
                    2: 'border-blue-800',
                    3: 'border-green-800',
                    4: 'border-yellow-500'
                };
              //  console.log("Current Student:", data.name, "Count:", data.violation_count);


                const ringClass = ringColors[studentDeptId] || 'border-indigo-500';
                //console.log(studentDeptId);

                studentPhoto.classList.add(ringClass);
                document.getElementById('modal_student_name').innerText = data.name;
                document.getElementById('modal_student_id').innerText = data.id_number || 'N/A';
                document.getElementById('modal_student_course').innerText = data.course_name || 'N/A';
                document.getElementById('modal_student_department').innerText = data.dept_name || 'N/A';
                document.getElementById('modal_student_year').innerText = data.year_level || '';
                document.getElementById('modal_student_photo').src = data.profile_photo_url;

                document.getElementById('studentInfoModal').classList.remove('hidden');
            },

            load: function (query, callback) {
                if (!query.length) return callback();

                fetch(`/students/search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        console.log("RAW DATA:", data);
                        callback(data);
                    })
                    .catch(() => callback());
            },

            render: {
                option: function (item, escape) {
                    const name = item.name ? escape(item.name) : 'Unknown Student';
                    const rfid = item.rfid ? escape(item.rfid) : 'No RFID';
                    const idNum = item.id_number ? escape(item.id_number) : 'No ID';
                    const course = item.course_name ? escape(item.course_name) : 'No Course';
                    const year_level = item.year_level ? escape(item.year_level) : 'No Year Level';

                    const badgeColor = item.badge_color || 'bg-gray-100 text-gray-700';

                    return `<div class="py-2 px-3 border-b border-gray-50 flex flex-wrap items-center justify-between gap-2">
                    <div class="min-w-0">
                        <span class="font-semibold text-gray-900 break-words">${escape(item.name)}</span>
                        <div class="text-xs text-gray-400">ID: ${escape(item.id_number || 'N/A')}</div>
                    </div>
                    
                    <span class="text-[10px] uppercase font-bold px-2 py-1 rounded border shrink-0 ${badgeColor}">
                        ${escape(item.course_name)} ${escape(item.year_level)}
                    </span>
                </div>`;
                },
                item: function (item, escape) {
                    const badgeColor = item.badge_color || 'bg-gray-100 text-gray-700';
                    return `<div class="flex flex-wrap items-center gap-1 min-w-0"><span class="truncate">${escape(item.name)}</span> <span class="text-[11px] sm:text-[12px] uppercase font-bold px-2 py-0.5 rounded border shrink-0 ${badgeColor}">
                        ${escape(item.course_name)} ${escape(item.year_level)}
                    </span></div>`;
                }
            }

        });
    </script>
</x-app-layout>